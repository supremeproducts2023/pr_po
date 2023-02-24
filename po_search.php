<script type="text/javascript" language="javascript">
function openEmp(type_use)
{
		returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_emp.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
		return returnvalue;
}
			
function lovEmp()
{
		returnvalue = openEmp(''); 
		if (returnvalue != null)
		{ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= "")
							document.getElementById("empno").value = values[0]; 
					else document.getElementById("empno").value =''; 
					
					if(values[1]!= "")
							document.getElementById("empno_show").value = values[1]; 
					else 
							document.getElementById("empno_show").value =''; 		
		}
}       
function clearname()
{
		document.getElementById("empno").value = '';
		document.getElementById("empno_show").value = '';
}
</script>
<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		require_once("../include/alert.php");
		$roles_user = $_SESSION["roles_user"];
		include "../include_RedThemes/wait.php";
		flush();
?>
<html>
	<head>
		<title>**Search ใบ PO **</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/check_date.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>				
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/button_add.js'></script>						
		<script language='javascript' src='../include/button_del.js'></script>				
	</head>
	<body  topmargin="0" leftmargin="0">
<?
		$s_empno = "";
		$choice_value= $_SESSION["choice_value"];	
		//========== เรื่องการ Search แล้วค้าง keyword ==================
		$flagSearch = @$_POST["flagSearch"];
		if(@$flagSearch == 'PushSearch'){
            $s_po_company = @$_POST["po_company"];
			$s_po_no=@$_POST["s_po_no"];
			$s_start=@$_POST["s_start"];
			$s_stop=@$_POST["s_stop"];
			$s_supplier=@$_POST["s_supplier"];
			$s_po_status=@$_POST["s_po_status"];
			$s_for_ref=@$_POST["s_for_ref"];
			$s_empno=@$_POST["empno"];
			$s_prod_name=@$_POST["s_prod_name"];
			if(($s_start=='')&&($s_stop!='')) $s_start =$s_stop;
			else if(($s_start!='')&&($s_stop=='')) $s_stop = $s_start;
			$_SESSION["ses_Search"] = "po|-|".$s_po_no."|-|".$s_start."|-|".$s_stop."|-|".$s_supplier."|-|".$s_po_status."|-|".$s_for_ref."|-|".$s_prod_name."|-|".$s_po_company."|-|".$s_empno;
		}else{
			$ses_Search = $_SESSION["ses_Search"];
			$arr_Search=explode("|-|",$ses_Search);				
			$flagSearch=trim($arr_Search[0]);		
			if($flagSearch=="po"){
				$s_po_no=trim($arr_Search[1]);						
				$s_start=trim($arr_Search[2]);						
				$s_stop=trim($arr_Search[3]);				
				$s_supplier=trim($arr_Search[4]);						
				$s_po_status=trim($arr_Search[5]);						
				$s_for_ref=trim($arr_Search[6]);			
				$s_prod_name=trim($arr_Search[7]);			
				$s_po_company = trim($arr_Search[8]);
				$s_empno = trim($arr_Search[9]);
			}
		}
		//================================================
		if((@$s_po_status=="") and (($roles_user=='ShowPO') or ($roles_user=='ShowPO2') or ($roles_user=='MNGGWD') or ($roles_user=='MNGShowPO'))){
			 $v_month = date("m");
			 $v_year = date("Y");
			 
			 $s_start='1-'.$v_month.'-'.$v_year;
			 switch($v_month){
			 	case '01'	:	
			 	case '03'	:	
			 	case '05'	:	
			 	case '07'	:	
			 	case '08'	:	
			 	case '10'	:	
			 	case '12'	:	$s_stop='31'; break;
			 	case '04'	:	
			 	case '06'	:	
			 	case '09'	:	
			 	case '11'	:	$s_stop='30'; break;
				case '02'	:	$s_stop='28'; break;
			 }
			  $s_stop .= '-'.$v_month.'-'.$v_year;
		}
		$strQUEGeneral = "select  distinct  p.po_no,format(p.po_date,'DD-MM-YYYY')  po_date,
												p.po_status,format(p.e_mail_date,'DD-MM-YYYY HH:MI') e_mail_date,
												s.supplier_title,s.company_name,p.po_file,p.po_file2,p.po_file3,p.po_company
											from po_master p
											left join supplier s on p.supplier_id=s.supplier_id
											left join po_details pd on p.po_no=pd.po_no
											where 1 = 1 ";
		if(@$s_po_no != '') $strQUEGeneral .= "and   p.po_no like upper('%$s_po_no%')  ";			
		if(@$s_start != '') $strQUEGeneral .= "and   format(p.po_date,'DD-MM-YYYY') between format('$s_start','DD-MM-YYYY') and format('$s_stop','DD-MM-YYYY')  ";			
		if(@$s_supplier!='') $strQUEGeneral .= "and  upper(s.company_name) like upper('%$s_supplier%')  ";
		if(@$s_for_ref!="") $strQUEGeneral .= "and upper(p.for_ref) like upper('%$s_for_ref%') ";		
		if(@$s_prod_name!="") $strQUEGeneral .= "and ((upper(pd.prod_name) like upper('%$s_prod_name%')) or (upper(pd.prod_no) like upper('%$s_prod_name%'))) ";	
		if(@$s_empno!="") $strQUEGeneral .= "and upper(p.rec_user) like upper('%$s_empno%') ";	
		if(@$s_po_company=="T") $strQUEGeneral .= "and p.po_company in ('T') ";
        else $strQUEGeneral .= "and (p.po_company not in ('T') or p.po_company is null) ";

		if($roles_user=='ShowPO' or $roles_user=='MNGShowPO' or $roles_user=='ShowPO2' or $roles_user=='MNGGWD'){ 
				$strQUEGeneral  .= "and p.po_status in ('2','3','4','5') ";	
				if(@$s_po_status=="")  $s_po_status='all';					
				else if(@$s_po_status!="all") $strQUEGeneral  .= "and p.po_status = '$s_po_status' ";							
		}else{
				if(@$s_po_status=="") $strQUEGeneral .= "and p.po_status = '1' ";								
				else if(@$s_po_status!="all") $strQUEGeneral  .= "and p.po_status = '$s_po_status' ";							
		}		
		switch($choice_value){
			case 'poup'		:  	$strQUEGeneral .= "and p.po_status in ('1','2','3') ";		break;
			case 'podel'		:  	$strQUEGeneral .= "and p.po_status in ('1','2','3') ";		break;
		}

		if($roles_user=='ShowPO' or $roles_user=='MNGShowPO' or $roles_user=='ShowPO2' or $roles_user=='MNGGWD'){ 
			$strQUEGeneral .="order by  po_no desc";
		}else{
			$strQUEGeneral .="order by  po_status,po_no";
		}

	?>
		<br>
		<center>
			<form name="po_search" method="post" action="po_search.php">
				<input name="flagSearch" type="hidden" value="PushSearch">
				<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750">&nbsp;&nbsp;ค้นหา</th>
				</tr>
				<tr >
					<td>
						<table width="100%" border="0" align="center">
						<tr>
							<td>
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td width="120" class="tdleftwhite">&nbsp;</td>
									<td width="100" class="tdleftwhite">เลขที่ PO</td>
									<td width="403"><input name="s_po_no" type="text"    onKeyDown="if(event.keyCode==13) document.po_search.submit();" value="<?  echo @$s_po_no; ?>" size="40"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">วันที่เปิด PO</td>
								
									<td>
									<input name="s_start" type="text"  value="<?  echo @$s_start; ?>" size="10" maxlength="10" onBlur="return testdate(document.po_search.s_start);"  onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
										ถึง
									<input name="s_stop" type="text"  value="<?  echo @$s_stop; ?>" size="10" maxlength="10"  onKeyDown="if(event.keyCode==13){ testdate(document.po_search.s_stop); document.po_search.submit(); }"> 
										(รูปแบบ DD-MM-YYYY) 
									</td>
								</tr>
                                <tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">ชื่อผู้จัดทำ PO</td>
									<td>
                                     <?
											$str = "select e_name from emp where empno = '".$s_empno."'";
											$cur_query = odbc_exec($conn,$str);
											$empno_show = odbc_result($cur_query, "e_name");
									  ?>
                                    		<input name="empno" id="empno" type="text" value="<?  echo @$s_empno; ?>"   size="10" maxlength="15" class="style_readonly" readonly="">
                                            <input name="empno_show" id="empno_show" type="text" value="<?  echo $empno_show ?>"   size="50"  class="style_readonly" readonly="">
                                            <img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovEmp();">
                                            <img src="include/images/close.png" width="20" height="19" onClick="clearname();">
                                    </td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">ชื่อ Supplier </td>
									<td><input name="s_supplier" type="text"  onKeyDown="if(event.keyCode==13) document.po_search.submit();" value="<?  echo @$s_supplier; ?>" size="40"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">FOR</td>
									<td><input name="s_for_ref" type="text"  onKeyDown="if(event.keyCode==13) document.po_search.submit();" value="<?  echo @$s_for_ref; ?>" size="40"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">รหัส/ชื่อสินค้า</td>
									<td><input name="s_prod_name" type="text"  onKeyDown="if(event.keyCode==13) document.po_search.submit();" value="<?  echo @$s_prod_name; ?>" size="40"></td>
								</tr>								
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">สถานะของ PO </td>
									<td>
										<select name="s_po_status">
										<? if($roles_user=='MS'){  ?>
											<option value="1" <? if((@$s_po_status=="")||(@$s_po_status=="1")) echo "selected"; ?>>MS สร้างใบ PO ในระบบ</option>
										<? } ?>
											<option value="2" <? if(@$s_po_status=="2") echo "selected"; ?>>MS พิมพ์ใบ PO เรียบร้อยแล้ว</option>
											<option value="3" <? if(@$s_po_status=="3") echo "selected"; ?>>MS Clear ใบ PO</option>
											<option value="4" <? if(@$s_po_status=="4") echo "selected"; ?>>โกดังทำรับเข้าแล้ว</option>
											<option value="5" <? if(@$s_po_status=="5") echo "selected"; ?>>PO ถูกยกเลิกโดย MS</option>
											<option value="all" <? if(@$s_po_status=="all") echo "selected"; ?>>ทุกสถานะ</option>
										</select>
									</td>
								</tr>
								<tr>
                                    <td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">บริษัท </td>
                                    <td>
                                        <select name="po_company">
                                          <option value = "S" <?php if(@$s_po_company=="") echo "selected"; ?>>Supreme</option>
                                          <option value = "T" <?php if(@$s_po_company=="T") echo "selected"; ?>>Transtek</option>
                                        </select>
                                    </td>
								</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<th colspan="3">
										<div align="right"> 
											<a target="_blank" style="cursor:hand" onClick="document.po_search.submit();"> 
												<img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0"> 
											</a> 
										</div>
									</th>
								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</form>
			<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750">&nbsp;&nbsp;ผลการค้นหา</th>
				</tr>
				<tr >
					<td>
						<table width="100%" border="0" align="center">
						<tr>
							<td>
								<table width="100%"  border="1" cellspacing="0" cellpadding="0" >
								<tr>
								<? 
									switch($choice_value){
										case 'poup'		: ?><td class="tdcenterblack" width="25">Edit</td>
																		<td class="tdcenterblack" width="60">เลื่อนวันส่ง</td><?			break;
										case 'podel'		: ?><td class="tdcenterblack" width="25">Del</td><?			break;
										case 'porep'		: ?><td class="tdcenterblack" width="126">Report</td>
																			<? if($roles_user=='MS'){ ?>
																					<td width="120" class="tdcenterblack">ปรับสถานะ</td>																																					
																					<td width="125" class="tdcenterblack">Port Data to B1</td><? } ?>
																		<?		break;
									}
								?>
									<td width="70" align="center"   class="tdcenterblack"><p>เลขที่ PO</p></td>
									<td width="70" align="center"   class="tdcenterblack">วันที่เปิด PO</td>
									<td class="tdcenterblack"><p>Supplier</p></td>
									<td width="150" class="tdcenterblack"><p class="tdcenterblack">สถานะ</p></td>
									<td width="70" align="center" class="tdcenterblack"><p class="tdcenterblack">บริษัท</p></td>
								</tr>
								<?
								  if($flagSearch== 'PushSearch'){
								//echo $strQUEGeneral;	
									$curQUEGeneral= odbc_exec($conn,$strQUEGeneral);	
									while(odbc_fetch_row($curQUEGeneral)){
										$po_no = odbc_result($curQUEGeneral, "po_no");
										$po_date = odbc_result($curQUEGeneral, "po_date");
										$company_name = odbc_result($curQUEGeneral, "supplier_title").' '.odbc_result($curQUEGeneral, "company_name");
										$po_status = odbc_result($curQUEGeneral, "po_status");
										$po_file = odbc_result($curQUEGeneral, "po_file");
										$po_file2 = odbc_result($curQUEGeneral, "po_file2");
										$po_file3 = odbc_result($curQUEGeneral, "po_file3");
										$v_e_mail_date = odbc_result($curQUEGeneral, "e_mail_date");
										$po_company = odbc_result($curQUEGeneral, "po_company");
								?>
									<tr <?php echo ''; ?>>
									<?
										switch($choice_value){
											case 'poup'		:	?><td>
																<div align="center">	
															<a href="./pomas_edit.php?po_no=<? echo $po_no; ?>&flag=edit"  title="ดูรายละเอียด">
																		<img src="../include/images/edit_icon.png" border="0"></a>
																</div>
															</td>	
															<td>
															<div align="center">	
															<a href="./po_search_Delivery.php?po_no=<? echo $po_no; ?>"  title="เลื่อนวันส่ง">
																		<img src="../include/menu_pic/Calendar.png" border="0" height="25px" width="25px"></a>
																</div>
															</td>
															<? break;
											case 'podel'		:	?><td>
																<div align="center">	
																<a onClick="remote_del('pomas_delcode.php?po_no=<? echo $po_no; ?>&flag=del');"   style="cursor:hand">
																		<img src="../include/images/del_icon.png" border="0"></a>
																</div>
															</td>	<? break;
											case 'porep'		:	?><td>
												<? if($roles_user=='MS'){  ?>
																<div align="center">	
																	<table width="125" border="0" cellpadding="0" cellspacing="0">
																	<tr>
																		<td width="60">
																			<a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=systemgen',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a>
																			<a onClick="remote_add2('SendToMail.php?doc_no=<? echo $po_no; ?>&doc_type=systemgen',480,630,530,0);" style="cursor:hand"><img src="../include/images/mail_icon.png" border="0"></a></td>                                                        
																		<td>System</td>
																	</tr>
																	<? if(($po_file != "")||($po_file2 != "")||($po_file3 != "")){ ?>
																	<tr>
																		<td>
																			<a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=userup&po_file=<? echo $po_file; ?>&po_file2=<? echo $po_file2; ?>&po_file3=<? echo $po_file3; ?>',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a>
																			<a onClick="remote_add2('SendToMail.php?doc_no=<? echo $po_no; ?>&doc_type=userup&po_file=<? echo $po_file; ?>&po_file2=<? echo $po_file2; ?>&po_file3=<? echo $po_file3; ?>',480,630,530,0);" style="cursor:hand"><img src="../include/images/mail_icon.png" border="0"></a>
																		</td>
																		<td>User Upload </td>
																	</tr>
																	<? } ?>
																	</table>
																	<?
																	if($v_e_mail_date !=""){
																			print "<font color=blue>Mail : ".$v_e_mail_date."</font>";
																	}
																	?>
																</div>
															</td>
														<?
															switch($po_status){
																case '1'	:	?><td>
																				<div align="center">
																					<a onClick="remote_do('po_closecode.php?po_no=<? echo $po_no; ?>&flag=2');"   style="cursor:hand">
																					<font color="red">ออก PO เรียบร้อย กดที่นี่</font>																														</a>																				</div>
																					</td>
																					<td>&nbsp;</td>
																					<? break;		
																case '2'	:	?><td><div align="center">
																						<a onClick="remote_close('po_closecode.php?po_no=<? echo $po_no; ?>&flag=3');"  
																						style="cursor:hand">
																						<font color="red">ปิดงาน กดที่นี่</font>																														</a>																					</div></td>
																						<td><div align="center">
																							<a onClick="remote_port('po_portData.php?po_no=<?= $po_no; ?>');" style="cursor:pointer"> 
																							<font color="#0033FF">คลิกที่นี่ เพื่อ Port ข้อมูล</font>	</a>
																						</div></td>	
																						<? break;																	
																default	:	?><td>&nbsp;</td>
																					<td>&nbsp;</td>
																					<? break;
															} 
													}else{
														if(($roles_user=='ShowPO2')||($roles_user=='MNGGWD')){
															 if($po_file2 != ""){
																	?><a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=userup&po_file=<? echo $po_file; ?>&po_file2=<? echo $po_file2; ?>&po_file3=<? echo $po_file3; ?>',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a> By User Upload<?
															 }else{
																	?><a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=systemgen',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a> By System<?
															 }
														}else{ // ShowPO
															 if(($po_file != "")||($po_file2 != "")||($po_file3 != "")){
																	?><a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=userup&po_file=<? echo $po_file; ?>&po_file2=<? echo $po_file2; ?>&po_file3=<? echo $po_file3; ?>',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a> By User Upload<?
															 }else{
																	?><a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=systemgen',500,155);" style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a> By System<?
															 }														
														}
														if($v_e_mail_date !=""){
																print "<br><font color=blue>Mail : ".$v_e_mail_date."</font>";
														}														 
													}												
										}
									?> 				
										<td ><div align="center"><? echo $po_no;?></div></td>
										<td ><div align="center">&nbsp;<? echo $po_date;?></div></td>
										<td  >&nbsp;<? echo $company_name;?></td>
										<td  >&nbsp;
											<?
												switch($po_status){
													case '1'	: echo "MS สร้างใบ PO ในระบบ"; 		break;
													case '2'	: echo "MS พิมพ์ใบ PO เรียบร้อยแล้ว"; 		break;
													case '3'	: echo "MS Clear ใบ PO"; 			break;
													case '4'	: echo "โกดังทำรับเข้าแล้ว"; 		break;
													case '5'   	: echo "PO ถูกยกเลิกโดย MS"; 		break;
												}
											?>										</td>
										<td>&nbsp;<?php if($po_company=="T") echo "Transtek"; else echo "Supreme";?></td>
									</tr>
								<?
									}
									}
								?>
								</table>							
								</td>
						</tr>
						</table>					</td>
				</tr>
			<tr>
				<td>
					<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<th>&nbsp;</th>
	</tr>
	</table>				</td>
			</tr>			
			</table>
			<br>
			<table  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" ><strong>หมายเหตุ - สถานะของ PO ทั้งหมด </strong></td>
			</tr>
			<tr>
				<td>
					<table width="900">
					<tr>
						<td width="20">1</td>
						<td width="200"> MS สร้างใบ PO ในระบบ</td>
						<td width="20">2</td>
						<td width="200">MS พิมพ์ใบ PO เรียบร้อยแล้ว</td>
						<td width="20">3</td>
						<td width="200">MS Clear ใบ PO</td>
						<td width="20">4</td>
						<td>โกดังทำรับเข้าแล้ว</td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;&nbsp;แถวสีเหลือง คือ PO ที่เคย port ข้อมูล ไปยัง B1 แล้ว</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</center>
			<script language="JavaScript" type="text/JavaScript">
				document.po_search.s_po_no.select();
			</script>
		
	</body>
</html>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
}else{
	include("../include_RedThemes/SessionTimeOut.php");
}
?>

