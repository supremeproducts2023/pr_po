<?
@session_start();
header('Content-type: text/html; charset=tis-620');
//header('Content-type: text/html; charset=iso-8859-1');
if(session_is_registered("valid_userprpo")) {
	include("../include_RedThemes/odbc_connect.php");						
	$empno_user = $_SESSION["empno_user"];		
	$http_host = '172.10.0.16';
	
	$doc_no= @$_GET["doc_no"];
	$doc_type= @$_GET["doc_type"];
	
	$po_file= @$_GET["po_file"];
	$po_file2= @$_GET["po_file2"];
	$po_file3= @$_GET["po_file3"];
	
	// หาชื่อผู้ส่งและ Email ผู้ส่ง		
		$strEmp = "select e_name,e_mail from emp where empno='$empno_user'";

		$curEmp = odbc_exec($conn,$strEmp);	
		$strFromName = odbc_result($curEmp, "e_name");
		//$strFrom =  "ITCenter@hitera.com";
		$strFrom =  odbc_result($curEmp, "e_mail");
		$defaultEmailBcc =  $strFrom;
	
	// ========= Start : ทำงานกรณีกดปุ่ม SUBMIT =============
	$flagAction=@$_POST["flagAction"];	
	if($flagAction == "SendMail"){
		$doc_no=@$_POST["doc_no"];
		$doc_type=@$_POST["doc_type"];
		$txtEmailTo1=@$_POST["txtEmailTo1"];
		$txtEmailTo2=@$_POST["txtEmailTo2"];
		$txtEmailTo3=@$_POST["txtEmailTo3"];
		$txtEmailBcc=@$_POST["txtEmailBcc"];
		require("../phpmailer/class.phpmailer.php");
		// Subject ของ PO
			$strPoMaster = "select s.company_name
						from po_master p,supplier s 
						where p.supplier_id=s.supplier_id(+) 
						and p.po_no='$doc_no'";
			$curPoMaster = odbc_exec($conn,$strPoMaster);	
			$company_name=odbc_result($curPoMaster, "company_name");
			$txtSubject = "PO เลขที่ $doc_no จากบริษัท $company_name ส่งถึงคุณค่ะ";
		// นำชื่อไฟล์  ใบกรรมวิธีตรวจรับสินค้า ใส่ตัวแปร $arr_PR_path
			$arr_PR_path = array();
			$strReceiveProduct = "select pr.pr_path
							from pr_and_po pap,pr_master pr
							where pap.pr_no=pr.pr_no
							and po_no='$doc_no'";
			$curReceiveProduct = odbc_exec($conn,$strReceiveProduct);	
			$x=0;																	
			while(odbc_fetch_row($curReceiveProduct)){ 
				$v_pr_path=odbc_result($curReceiveProduct, "pr_path");	
				$arr_PR_path[$x] = $v_pr_path;
				$x++;
			}
			
			$strQUEStatus = "select po_status from po_master where po_no='$doc_no'";
			$curQUEStatus = odbc_exec($conn,$strQUEStatus);	
			$po_status = odbc_result($curQUEStatus, "po_status");			
			if($po_status=="1") $po_status = ",po_status='2'";
			else $po_status = "";
			
			$strUPDemail= "update po_master set e_mail_date=sysdate $po_status where po_no='$doc_no'";
			$exeUPDemail = odbc_exec($conn,$strUPDemail);					
			
			if($exeUPDemail){
					if($doc_type=="systemgen"){
							require_once("./CreateReport.php");				
							
							// PO แบบแสดงรายการทั้งหมด
								if($txtEmailTo1 !=""){ 	
									$subject =$txtSubject."(แสดงรายการทั้งหมด)";					
									CreatePO($doc_no,"1");	 // all
										
									// สร้าง Report ชื่อ potomail_all.pdf
										$app_obj = new COM("CrystalRuntime.Application") or Die ("Did not open");
										$rpt_obj = $app_obj->OpenReport(dirname(__FILE__)."\\report\\po_report.rpt",1);
										$rpt_obj->EnableParameterPrompting=FALSE;
										 
										$crystalExportOptions = $rpt_obj->ExportOptions;
										$filename = "potomail_all.doc";
										$crystalExportOptions->DiskFileName = dirname(__FILE__)."\\report\\".$filename;
										$crystalExportOptions->FormatType = 14;
										$crystalExportOptions->DestinationType = 1;
										$rpt_obj->Export(FALSE);
									// สร้าง+ส่ง Mail	
										$m = new PHPMailer();
										$m->IsSMTP();                                  			// send via SMTP
										$m->Host     = "mail.hitera.com"; 	// SMTP servers
										$m->SMTPAuth = true;    				// turn on SMTP authentication	
										$m->CharSet = "tis-620";
										$x=0;
										$m->AddAttachment("./report/potomail_all.doc"); 													
										// กรณีมีใบกรรมวิธีตรวจรับสินค้า
											while($x<count($arr_PR_path)){
												$pr_file = $arr_PR_path[$x];
												$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
												$m->AddAttachment($file); 																																
												$x++;
											}																
										// ใส่ E-mail ผู้รับ
											if($txtEmailTo1 != ""){
												$arrEmailTo1 = explode("\n",$txtEmailTo1);
												for($i=0;$i<count($arrEmailTo1);$i++)
													$m->AddAddress(trim($arrEmailTo1[$i]),"");
											}
											if($txtEmailBcc != ""){
												$arrEmailBcc = explode("\n",$txtEmailBcc);
												for($i=0;$i<count($arrEmailBcc);$i++)
													$m->AddBCC(trim($arrEmailBcc[$i]),"");
											}
										$m->From = $strFrom;
										$m->FromName = $strFromName;
										$m->IsHTML(false);                               // send as HTML ?
										$m->Subject  =  $subject;
										$m->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
										$m->Send(); 			
										//$m->Send(); 			
								}else $m=1;
							// PO แบบปิดราคา
								if($txtEmailTo2 !=""){ 	
									$subject =$txtSubject."(ปิดราคา)";
									CreatePO($doc_no,"2");	 // nonprice
				
									// สร้าง Report ชื่อ potomail_price.pdf
										$app_obj = new COM("CrystalRuntime.Application") or Die ("Did not open");
										$rpt_obj = $app_obj->OpenReport(dirname(__FILE__)."\\report\\po_report.rpt",1);
										$rpt_obj->EnableParameterPrompting=FALSE;
										 
										$crystalExportOptions = $rpt_obj->ExportOptions;
										$filename = "potomail_price.doc";
										$crystalExportOptions->DiskFileName = dirname(__FILE__)."\\report\\".$filename;
										$crystalExportOptions->FormatType = 14;
										$crystalExportOptions->DestinationType = 1;
										
										$rpt_obj->Export(FALSE);
									// สร้าง+ส่ง Mail	
										$m2 = new PHPMailer();
										$m2->IsSMTP();                                  			// send via SMTP
										$m2->Host     = "mail.hitera.com"; 	// SMTP servers
										$m2->SMTPAuth = true;    				// turn on SMTP authentication	
										$m2->CharSet = "tis-620";
										$m2->AddAttachment("./report/potomail_price.doc"); 													
										// กรณีมีใบกรรมวิธีตรวจรับสินค้า
											while($x<count($arr_PR_path)){
												$pr_file = $arr_PR_path[$x];
												$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
												$m->AddAttachment($file); 																																
												$x++;
											}																																
										// ใส่ E-mail ผู้รับ
											if($txtEmailTo2 != ""){
												$arrEmailTo2 = explode("\n",$txtEmailTo2);
												for($i=0;$i<count($arrEmailTo2);$i++)
													$m2->AddAddress(trim($arrEmailTo2[$i]),"");
											}
											if($txtEmailBcc != ""){
												$arrEmailBcc = explode("\n",$txtEmailBcc);
												for($i=0;$i<count($arrEmailBcc);$i++)
													$m2->AddBCC(trim($arrEmailBcc[$i]),"");
											}													
										$m2->From = $strFrom;
										$m2->FromName = $strFromName;
										$m2->IsHTML(false);                               // send as HTML ?
										$m2->Subject  =  $subject;
										$m2->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
										$m2->Send(); 			
										//$m2->Send(); 			
								}else $m2=1;
							// PO แบบปิดท้าย
								if($txtEmailTo3 !=""){ 	
									$subject =$txtSubject."(ปิดท้าย)";
									CreatePO($doc_no,"3");	 // nontail
									
									// สร้าง Report ชื่อ potomail_tail.pdf
										$app_obj = new COM("CrystalRuntime.Application") or Die ("Did not open");
										$rpt_obj = $app_obj->OpenReport(dirname(__FILE__)."\\report\\po_report.rpt",1);
										$rpt_obj->EnableParameterPrompting=FALSE;
										 
										$crystalExportOptions = $rpt_obj->ExportOptions;
										$filename = "potomail_tail.doc";
										$crystalExportOptions->DiskFileName = dirname(__FILE__)."\\report\\".$filename;
										$crystalExportOptions->FormatType = 14;
										$crystalExportOptions->DestinationType = 1;
										
										$rpt_obj->Export(FALSE);
									// สร้าง+ส่ง Mail	
										$m3 = new PHPMailer();
										$m3->IsSMTP();                                  			// send via SMTP
										$m3->Host     = "mail.hitera.com"; 	// SMTP servers
										$m3->SMTPAuth = true;    				// turn on SMTP authentication	
										$m3->CharSet = "tis-620";
										$m3->AddAttachment("./report/potomail_tail.doc"); 													
										// กรณีมีใบกรรมวิธีตรวจรับสินค้า
											while($x<count($arr_PR_path)){
												$pr_file = $arr_PR_path[$x];
												$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
												$m->AddAttachment($file); 																																
												$x++;
											}																
										// ใส่ E-mail ผู้รับ
											if($txtEmailTo3 != ""){
												$arrEmailTo3 = explode("\n",$txtEmailTo3);
												for($i=0;$i<count($arrEmailTo3);$i++)
													$m3->AddAddress(trim($arrEmailTo3[$i]),"");
											}
											if($txtEmailBcc != ""){
												$arrEmailBcc = explode("\n",$txtEmailBcc);
												for($i=0;$i<count($arrEmailBcc);$i++)
													$m3->AddBCC(trim($arrEmailBcc[$i]),"");
											}													
										$m3->From = $strFrom;
										$m3->FromName = $strFromName;
										$m3->IsHTML(false);                               // send as HTML ?
										$m3->Subject  =  $subject;//iconv("tis-620", "utf-8",$subject);
										$m3->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
										$m3->Send(); 			
										//$m3->Send(); 			
								} else $m3=1;
										
							// ผลการส่ง Mail 
								if(($m)&&($m2)&&($m3)){
									echo '<script language="JavaScript" type="text/JavaScript">';
									echo 'alert ("ส่งเอกสารเรียบร้อยแล้วค่ะ!!!2");';
									echo 'window.close();';
									echo '</script>';									
								}
					}else if($doc_type=="userup"){
						// PO แบบแสดงรายการทั้งหมด
							if($txtEmailTo1 !=""){ 	
								$subject =$txtSubject."(แสดงรายการทั้งหมด)";
								$strPoMaster = "select po_file from po_master where po_no='$doc_no'";
								$curPoMaster = odbc_exec($conn,$strPoMaster);	
								$po_file=odbc_result($curPoMaster, "po_file");
								$file ="\\\\".$http_host."\\iso\\po_thai\\$po_file";
								// สร้าง+ส่ง Mail	
									$m = new PHPMailer();
									$m->IsSMTP();                                  			// send via SMTP
									$m->Host     = "mail.hitera.com"; 	// SMTP servers
									$m->SMTPAuth = true;    				// turn on SMTP authentication	
									$m->CharSet = "tis-620";
									$m->AddAttachment($file); 													
									// กรณีมีใบกรรมวิธีตรวจรับสินค้า
										while($x<count($arr_PR_path)){
											$pr_file = $arr_PR_path[$x];
											$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
											$m->AddAttachment($file); 																																
											$x++;
										}																
									// ใส่ E-mail ผู้รับ
										if($txtEmailTo1 != ""){
											$arrEmailTo1 = explode("\n",$txtEmailTo1);
											for($i=0;$i<count($arrEmailTo1);$i++)
												$m->AddAddress(trim($arrEmailTo1[$i]),"");
										}
										if($txtEmailBcc != ""){
											$arrEmailBcc = explode("\n",$txtEmailBcc);
											for($i=0;$i<count($arrEmailBcc);$i++)
												$m->AddBCC(trim($arrEmailBcc[$i]),"");
										}
									$m->From = $strFrom;
									$m->FromName = $strFromName;
									$m->IsHTML(false);                               // send as HTML ?
									$m->Subject  =  $subject;
									$m->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
									$m->Send(); 			
									//$m->Send(); 			
							}else $m=1;
						// PO แบบปิดราคา
							if($txtEmailTo2 !=""){ 	
								$subject =$txtSubject."(ปิดราคา)";
								$strPoMaster = "select po_file2 from po_master where po_no='$doc_no'";
								$curPoMaster = odbc_exec($conn,$strPoMaster);	
								$po_file2=odbc_result($curPoMaster, "po_file2");
								$file ="\\\\".$http_host."\\iso\\po_thai2\\$po_file2";
								// สร้าง+ส่ง Mail	
									$m2 = new PHPMailer();
									$m2->IsSMTP();                                  			// send via SMTP
									$m2->Host     = "mail.hitera.com"; 	// SMTP servers
									$m2->SMTPAuth = true;    				// turn on SMTP authentication
									$m2->CharSet = "tis-620";
									$m2->AddAttachment($file); 													
									// กรณีมีใบกรรมวิธีตรวจรับสินค้า
										while($x<count($arr_PR_path)){
											$pr_file = $arr_PR_path[$x];
											$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
											$m->AddAttachment($file); 																																
											$x++;
										}																
									// ใส่ E-mail ผู้รับ
										if($txtEmailTo2 != ""){
											$arrEmailTo2 = explode("\n",$txtEmailTo2);
											for($i=0;$i<count($arrEmailTo2);$i++)
												$m2->AddAddress(trim($arrEmailTo2[$i]),"");
										}
										if($txtEmailBcc != ""){
											$arrEmailBcc = explode("\n",$txtEmailBcc);
											for($i=0;$i<count($arrEmailBcc);$i++)
												$m2->AddBCC(trim($arrEmailBcc[$i]),"");
										}													
									$m2->From = $strFrom;
									$m2->FromName = $strFromName;
									$m2->IsHTML(false);                               // send as HTML ?
									$m2->Subject  =  $subject;
									$m2->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
									$m2->Send(); 														
									//$m2->Send(); 														
							}else $m2=1;
						// PO แบบปิดท้าย
							if($txtEmailTo3 !=""){ 	
								$subject =$txtSubject."(ปิดท้าย)";
								$strPoMaster = "select po_file3 from po_master where po_no='$doc_no'";
								$curPoMaster = odbc_exec($conn,$strPoMaster);	
								$po_file3=odbc_result($curPoMaster, "po_file3");
								$file ="\\\\".$http_host."\\iso\\po_thai3\\$po_file3";
								// สร้าง+ส่ง Mail	
									$m3 = new PHPMailer();
									$m3->IsSMTP();                                  			// send via SMTP
									$m3->Host     = "mail.hitera.com"; 	// SMTP servers
									$m3->SMTPAuth = true;    				// turn on SMTP authentication	
									$m3->CharSet = "tis-620";
									$m3->AddAttachment($file); 													
									// กรณีมีใบกรรมวิธีตรวจรับสินค้า
										while($x<count($arr_PR_path)){
											$pr_file = $arr_PR_path[$x];
											$file ="\\\\".$http_host."\\iso\\pr_thai\\$pr_file";
											$m->AddAttachment($file); 																																
											$x++;
										}																
									// ใส่ E-mail ผู้รับ
										if($txtEmailTo3 != ""){
											$arrEmailTo3 = explode("\n",$txtEmailTo3);
											for($i=0;$i<count($arrEmailTo3);$i++)
												$m3->AddAddress(trim($arrEmailTo3[$i]),"");
										}
										if($txtEmailBcc != ""){
											$arrEmailBcc = explode("\n",$txtEmailBcc);
											for($i=0;$i<count($arrEmailBcc);$i++)
												$m3->AddBCC(trim($arrEmailBcc[$i]),"");
										}													
									$m3->From = $strFrom;
									$m3->FromName = $strFromName;
									$m3->IsHTML(false);                               // send as HTML ?
									$m3->Subject  =  $subject;
									$m3->Body     =  "เอกสารอยู่ในไฟล์ที่ Attach มาค่ะ";
									$m3->Send(); 			
									//$m3->Send(); 			
							}else $m3=1;
						// ผลการส่ง Mail 
							if(($m)&&($m2)&&($m3)){
								echo '<script language="JavaScript" type="text/JavaScript">';
								echo 'alert ("ส่งเอกสารเรียบร้อยแล้วค่ะ!!!1");';
								echo 'window.close();';
								echo '</script>';									
							}
					}
			}else{
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo 'alert ("!!! การส่ง E-mail ล้มเหลวค่ะ !!!");';
					echo 'window.close();';
					echo '</script>';											
			}											
		}		
	// ========= Stop : ทำงานกรณีกดปุ่ม SUBMIT =============
	
	
	// Default E-mail
	/* //Edit  By SUN  @28/02/2555
		$defaultEmp1 = "'09001','09007','08001','08003','14002','08041','11045','08044','11042'";
		$defaultEmail1='';
		$defaultEmp2="'12007','13256','13377','13319','13299','13352','13380'";
	*/	
		$defaultEmp1 = "'09001','09007','09031','08001','08003','14002','08041','16012','09046'";
		$defaultEmail1="stock@supremeproducts.co.th";
		$defaultEmp2="'12007','13256','13352'";
		$defaultEmail2="logistic@supremeproducts.co.th\nrungroi.jam@supremeproducts.co.th";							
		$defaultEmp3="";
		$defaultEmail3='';
		
		if($defaultEmp1!=""){
			$strEmp = "select e_mail from emp where e_mail is not null and empno in ($defaultEmp1) and quitdate is null order by e_mail desc ";
			$curEmp = odbc_exec($conn,$strEmp);	
			while(odbc_fetch_row($curEmp)){
				$v_e_mail = odbc_result($curEmp, "e_mail");
				if($defaultEmail1!="") $defaultEmail1 .="\n";
				$defaultEmail1 .= $v_e_mail;
			}																		
		}						
		if($defaultEmp2!=""){
			$strEmp = "select e_mail from emp where e_mail is not null and empno in ($defaultEmp2) and quitdate is null order by e_mail ";
			$curEmp = odbc_exec($conn,$strEmp);	
			while(odbc_fetch_row($curEmp)){
				$v_e_mail = odbc_result($curEmp, "e_mail");
				if($defaultEmail2!="") $defaultEmail2 .="\n";
				$defaultEmail2 .= $v_e_mail;
			}																		
		}						
		if($defaultEmp3!=""){
			$strEmp = "select e_mail from emp where e_mail is not null and empno in ($defaultEmp3) and quitdate is null order by e_mail ";
			$curEmp = odbc_exec($conn,$strEmp);	
			while(odbc_fetch_row($curEmp)){
				$v_e_mail = odbc_result($curEmp, "e_mail");
				if($defaultEmail3!="") $defaultEmail3 .="\n";
				$defaultEmail3 .= $v_e_mail;
			}																		
		}
?>
<html>
	<head>
		<title>MAIL</title>
		<meta http-equiv="Content-Type" content="text/html; charset=tis-620">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">

		<!-- Calculate -->
		<script language='javascript'>
				function del_emailEnter(txtEmail){
					 var vars = txtEmail.value.split("\n");
					 var txtvalue='';
					 for (var i=0;i<vars.length-1;i++){						 
						  txtvalue = txtvalue+vars[i]
					}
					txtEmail.value=txtvalue.substring(0,txtvalue.length-1);
				}
				
				function checkFillEmail(obj){
							if((obj.txtEmailTo1.value=="")&&(obj.txtEmailTo2.value=="")&&(obj.txtEmailTo3.value=="")){  	
								alert("กรุณากรอก E-mail ที่ต้องการส่งถึงค่ะ");
								return false;
							}			
							obj.submit();
				}				
		</script>	

		<!-- LOV : Emp -->			
		<script type="text/javascript" language="javascript">
			function openEmp(type_use){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_emp.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovEmailTo1(){
				returnvalue = openEmp('email'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[2]!= ""){ 
						if(document.getElementById("txtEmailTo1").value != "") document.getElementById("txtEmailTo1").value += '\n'+ values[2]; 
						else document.getElementById("txtEmailTo1").value = values[2];  
					}					
				 }
			}       

			function lovEmailTo2(){
				returnvalue = openEmp('email'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[2]!= ""){ 
						if(document.getElementById("txtEmailTo2").value != "") document.getElementById("txtEmailTo2").value += '\n'+ values[2]; 
						else document.getElementById("txtEmailTo2").value = values[2];  
					}					
				 }
			}       
			
			function lovEmailTo3(){
				returnvalue = openEmp('email'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[2]!= ""){ 
						if(document.getElementById("txtEmailTo3").value != "") document.getElementById("txtEmailTo3").value += '\n'+ values[2]; 
						else document.getElementById("txtEmailTo3").value = values[2];  
					}					
				 }
			}       
	
			function lovEmailBcc(){
				returnvalue = openEmp('email'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[2]!= ""){ 
						if(document.getElementById("txtEmailBcc").value != "") document.getElementById("txtEmailBcc").value += '\n'+ values[2]; 
						else document.getElementById("txtEmailBcc").value = values[2];  
					}					
				 }
			}       
		</script>
		

	</head>
	<body leftmargin="0" topmargin="0">
		<form name="form_mail" action="SendToMail.php" method="post">
			<input name="doc_type" type="hidden" value="<? echo $doc_type; ?>">
			<table width="480"  border="0" align="center" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
			<tr>
				<td width="550">
					<table width="100%" border="0" align="center">
					<tr>
						<td>
							<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tdleftwhite">&nbsp;เลขที่เอกสาร<span class="style_star">*
								    <input name="doc_no" type="text" class="style_readonly" value="<? echo $doc_no; ?>" size="10"  readonly="">
								</span></td>
								<td><span class="tdleftwhite">ประเภทเอกสาร <span class="style_star">*
								  <input name="doc_type2" type="text" class="style_readonly" value="<? if($doc_type=="systemgen") echo "PO ที่ระบบ Generate ให้"; else if($doc_type=="userup") echo "PO ที่ User เป็นผู้ Upload"; ?>"  readonly="">
                                  <input name="flagAction" type="hidden" value="SendMail">
</span></span></td>
							</tr>
						</table>						</td>
					</tr>
					<tr>
						<td>
							<table width="100%"  border="1" cellspacing="0" cellpadding="0">
							<tr>
							  <td  class="tdleftwhite">&nbsp;&nbsp;&nbsp;แบบแสดงทั้งหมด : <br>
							    <table border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="6px;">&nbsp;</td>
                                    <td class="tdleftwhite"><textarea name="txtEmailTo1" cols="60" rows="9"  readonly class="style_readonly"><? if((@$po_file != '')||($doc_type=='systemgen'))echo @$defaultEmail1;  ?></textarea></td>
                                    <td>
										<input name="addEmail1" type="button" value="เพิ่ม E-mail"  onClick="lovEmailTo1();" <? if((@$po_file == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="delEmail1" type="button" value="ลบ E-mail"  onClick="return del_emailEnter(document.form_mail.txtEmailTo1);" <? if((@$po_file == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="showEmail1" type="button" value="ค่าเริ่มต้น"  onClick="document.form_mail.txtEmailTo1.value=document.form_mail.defaultEmail1.value;" <? if((@$po_file == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="clearEmail1" type="button" value=" ลบทั้งหมด"  onClick="document.form_mail.txtEmailTo1.value='';" <? if((@$po_file == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="defaultEmail1" type="hidden" value="<? if((@$po_file != '')||($doc_type=='systemgen'))echo @$defaultEmail1;  ?>">									</td>
                                  </tr>
                                </table>							</td>
							  </tr>
							<tr>
							  <td  class="tdleftwhite">&nbsp;&nbsp;&nbsp;แบบปิดราคา :<br>
							    <table border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="6px;">&nbsp;</td>
                                    <td class="tdleftwhite"><textarea name="txtEmailTo2" cols="60" rows="6"  readonly class="style_readonly"><? if((@$po_file2 != '')||($doc_type=='systemgen'))echo @$defaultEmail2;  ?></textarea></td>
                                    <td>
										<input name="addEmail2" type="button" value="เพิ่ม E-mail"  onClick="lovEmailTo2();"  <? if((@$po_file2 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;"><br>
										<input name="delEmail2" type="button" value="ลบ E-mail"  onClick="return del_emailEnter(document.form_mail.txtEmailTo2);" <? if((@$po_file2 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="showEmail2" type="button" value="ค่าเริ่มต้น"  onClick="document.form_mail.txtEmailTo2.value=document.form_mail.defaultEmail2.value;" <? if((@$po_file2 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="clearEmail2" type="button" value=" ลบทั้งหมด"  onClick="document.form_mail.txtEmailTo2.value='';" <? if((@$po_file2 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="defaultEmail2" type="hidden" value="<? if((@$po_file2 != '')||($doc_type=='systemgen'))echo @$defaultEmail2;  ?>">									</td>		
                                  </tr>
                                </table>							</td>
							  </tr>
							<tr>
							  <td  class="tdleftwhite">&nbsp;&nbsp;&nbsp;แบบปิดท้าย :<br>
							    <table border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="6px;">&nbsp;</td>
                                    <td class="tdleftwhite"><textarea name="txtEmailTo3" cols="60" rows="5"  readonly class="style_readonly"><? if((@$po_file3 != '')||($doc_type=='systemgen'))echo @$defaultEmail3;  ?></textarea></td>
                                    <td>
										<input name="addEmail3" type="button" value="เพิ่ม E-mail"  onClick="lovEmailTo3();" <? if((@$po_file3 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;"><br>
	                                    <input name="delEmail3" type="button" value="ลบ E-mail"  onClick="return del_emailEnter(document.form_mail.txtEmailTo3);" <? if((@$po_file3 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="showEmail3" type="button" value="ค่าเริ่มต้น"  onClick="document.form_mail.txtEmailTo3.value=document.form_mail.defaultEmail3.value;" <? if((@$po_file3 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="clearEmail3" type="button" value=" ลบทั้งหมด"  onClick="document.form_mail.txtEmailTo3.value='';" <? if((@$po_file3 == '')&&($doc_type=='userup')) echo "disabled"; ?> style="width:70px;">
	                                    <input name="defaultEmail3" type="hidden" value="<? if((@$po_file3 != '')||($doc_type=='systemgen'))echo @$defaultEmail3;  ?>">									</td>
                                  </tr>
                                </table>							  </td>
							  </tr>
							<tr>
							  <td  class="tdleftwhite">&nbsp;&nbsp;&nbsp;Bcc :<br>
							    <table border="0" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td width="6px;">&nbsp;</td>
                                    <td class="tdleftwhite"><textarea name="txtEmailBcc" cols="60" rows="5"  readonly class="style_readonly"><? echo @$defaultEmailBcc;  ?></textarea></td>
                                    <td>
										<input name="addEmailBcc" type="button" value="เพิ่ม E-mail"  onClick="lovEmailBcc();" style="width:70px;"><br>
										<input name="delEmailBcc" type="button" value="ลบ E-mail"  onClick="return del_emailEnter(document.form_mail.txtEmailBcc);" style="width:70px;">
	                                    <input name="showEmailBcc" type="button" value="ค่าเริ่มต้น"  onClick="document.form_mail.txtEmailBcc.value=document.form_mail.defaultEmailBcc.value;" style="width:70px;">
	                                    <input name="clearEmailBcc" type="button" value=" ลบทั้งหมด"  onClick="document.form_mail.txtEmailBcc.value='';"  style="width:70px;">
	                                    <input name="defaultEmailBcc" type="hidden" value="<? echo @$defaultEmailBcc;  ?>">									</td>
                                  </tr>
                                </table>						      </td>
							  </tr>
						</table>						</td>
					</tr>
					<tr>
						<td>
							<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<th >
									<div align="right"> 
										<a onClick="return checkFillEmail(document.form_mail);" style="cursor:hand">
											<img src="../include/button/send1.gif" name="butsave" width="106" height="24" border="0" >										</a>
										<a  onClick="document.form_mail.reset();" style="cursor:hand">
											<img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >										</a>									</div>								</th>
							</tr>
							</table>						</td>
					</tr>
					</table>				</td>
			</tr>
			</table>
		</form>
	</body>
</html>	
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>

