<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		$empno_user = $_SESSION["empno_user"];
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='AddCode'){  // �óա��������ͷӧҹ˹�� code
					$v_po_no = @$_POST["po_no"];
					
					$checkbox=@$_POST["checkbox"];
					$arr_garqty=@$_POST["arr_garqty"];
					$arr_garprice=@$_POST["arr_garprice"];
					
					
					$v_prod_noshow = $_POST["prod_noshow"];					
					$v_prod_no = $_POST["prod_no"];					
					$v_prod_name=$_POST["prod_name"];					
					$v_prod_unit=$_POST["prod_unit"];
					$v_gar_unit=$_POST["gar_unit"];
					$v_prod_qty = $_POST["prod_qty"];
					$v_prod_price = $_POST["prod_price"];

					$strMAX = "select ISNULL(max(id)+1,1) id  from po_details where po_no='$v_po_no'";			
					$curMAX = @odbc_exec($conn,$strMAX);
					$id = @odbc_result($curMAX, "id");
					$strINSMaster = "insert into po_details (
										id,po_no,prod_type,show_id,
										code,prod_no,
										prod_name,gar_unit,
										prod_qty,prod_price,prod_unit,
										rec_user,rec_date
										) values(
										'$id','$v_po_no','2','1',
										'$v_prod_no','$v_prod_noshow',
										'$v_prod_name','$v_gar_unit',
										'$v_prod_qty','$v_prod_price','$v_prod_unit',
										'$empno_user',getdate())";
					$exeINSMaster = odbc_exec($conn,$strINSMaster);
					
					$i=0;
					$ok = 0;
					$sum_gar_qty = 0;
					$sum_gar_price = 0;					
					while($i<count($checkbox)){
							$strValiable = $checkbox[$i];
							list($strrow,$v_subjob) = explode("|-|",$strValiable);	
							$v_gar_qty = $arr_garqty[$strrow];
							$v_gar_price = $arr_garprice[$strrow];

							if($v_gar_qty=="") $v_gar_qty=0;
							if($v_gar_price=="") $v_gar_price=0;
							
							$sum_gar_qty += $v_gar_qty;
							$sum_gar_price += $v_gar_price*$v_gar_qty;
							
							$strINS = "insert into po_details_subjob (
										po_no,id,subjob,qty,cost
									) values(
										'$v_po_no','$id','$v_subjob','$v_gar_qty','$v_gar_price'
									)";
							$exeINS = odbc_exec($conn,$strINS);
							if($exeINS){
								$ok++;
							}
							$i++;
					}// end while($i<count($checkbox))
					
					$result=odbc_exec($conn,"update po_details set gar_qty='$sum_gar_qty',gar_price='$sum_gar_price' where po_no='$v_po_no' and id='$id'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					$result=odbc_exec($conn,"update po_master set po_status='1' where po_no='$v_po_no'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡�ä��");';
					echo '		window.opener.location.reload("./pomas_edit.php");';
					echo '		window.close();';
					echo '</script>';
				}// end if($flagAction!='')
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
	$v_po_no = $_GET["po_no"]; 
	include "../include_RedThemes/wait.php";
	flush();
?>
<html>
	<head>
		<title>�����Թ��һ����� SubContract</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>

		<!-- Check Not null -->
		<script language='javascript'>
				function check_podetsubc(obj){
							if(obj.prod_noshow.value==""){  	
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.btnBOM.focus();
								return false;
							}			
							obj.submit();
				}		
		</script>				
		<!-- Calculate -->
		<script language='javascript'>
				function cal_podetqty(obj){	
						j = k = 0;
						for(var i=0; i < obj.elements.length ; i++){ 
								if((obj.elements[i].name=="checkbox[]")&&(obj.elements[i+1].value!="")&&(obj.elements[i+2].value!="")){									
									if(obj.elements[i].checked){
											 j = j + parseInt(obj.elements[i+1].value);
											k = k+ (parseFloat(obj.elements[i+1].value) * parseFloat(obj.elements[i+2].value));
									}
								}
						}
						obj.sum_prod_qty.value = j;
						obj.sum_prod_price.value = k;
						return null;
				}				
		</script>				

		<!-- LOV : BOM	-->			
		<script type="text/javascript" language="javascript">
			function openBOM(type_use){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_bom.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovBOM(type_use){
				returnvalue = openBOM(type_use); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("prod_no").value = values[0];  }else{ document.getElementById("prod_no").value =''; }
					if(values[1]!= ""){ document.getElementById("prod_noshow").value = values[1];  }	else{ document.getElementById("prod_noshow").value =''; }				
					if(values[2]!= ""){ document.getElementById("prod_name").value = values[2];  }else{ document.getElementById("prod_name").value =''; }					
					if(values[3]!= ""){ document.getElementById("prod_unit").value = values[3];  }else{ document.getElementById("prod_unit").value =''; }					
					if(values[3]!= ""){ document.getElementById("gar_unit").value = values[3];  }else{ document.getElementById("gar_unit").value =''; }					
				 }
				 
			}       
		</script>
		
	</head>	
<body topmargin="0" leftmargin="0">
	<div align="center">  
		<form name="form1" action="podet_addsubc.php" method="post">
			<input name="flagAction" type="hidden" value="AddCode">	 
			<table width="800"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
			<tr>
				<th width="550">&nbsp;&nbsp;��¡���Թ���</th>
			</tr>
			<tr>
				<td> 
					<table width="100%" border="0" align="center">
					<tr>
						<td>
							<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td width="150" class="tdleftwhite">�Ţ���� PO  <span class="style_star">*</span></td>
							  <td width="300"><input name="po_no" type="text" class="style_readonly" value="<?=$v_po_no; ?>"  readonly=""></td>
							  <td width="120"><span class="tdleftwhite">&nbsp;�������Թ��ҷ��ѹ�֡</span></td>
							  <td class="style_text">SubContact </td>
							</tr>
							</table>
						</td>
					</tr>			  
					<tr>
						<td>
							<table width="100%"  border="1" cellspacing="0" cellpadding="0">
							<tr>
								<td  class="tdleftwhite">&nbsp;�����Թ��� <span class="style_star">*</span> </td>
								<td colspan="3">
									<input name="prod_noshow" type="text"  value="" size="20" maxlength="50" readonly="" class="style_readonly"><input name="btnBOM" type="button" id="btnBOM"  value="�Թ���(BOM)"  onClick="lovBOM('R,P');">
									<input name="prod_no" type="hidden"  value=""></td>
							</tr>
							<tr>
								<td  class="tdleftwhite">&nbsp;�����Թ���</td>
								<td colspan="3"><input name="prod_name" type="text"  value="" size="60" maxlength="300"  onKeyUp="return chkStringInput(this,300);"></td>
							</tr>
							<tr>
								<td width="150"  class="tdleftwhite">&nbsp;�ӹǹ(��� PO)</td>
								<td width="200"><input name="prod_qty" type="text"   onKeyDown="return chkNumberInput('float');" size="15" maxlength="8">
							    
								<td width="120"><span class="tdleftwhite">&nbsp;˹���(��� PO)</span></td>
							    <td width="300"><input name="prod_unit" type="text" value="" size="20"  maxlength="15"></td>
							</tr>
							<tr>
							  <td  class="tdleftwhite">&nbsp;�Ҥҵ��˹��� (��� PO)</td>
							  <td><input name="prod_price" type="text"   onKeyDown="return chkNumberInput('float');" size="15" maxlength="16">
						      
							  <td class="tdleftwhite">&nbsp;˹���(�Ѻ���)</td>
							  <td><input name="gar_unit" type="text" value="" size="20"  maxlength="15" readonly="" class="style_readonly"></td>
							  </tr>
							</table>
						</td>
					</tr>		
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table border="1" align="left" cellpadding="0" cellspacing="0" >
									<tr>
										<td width="25" rowspan="2" class="tdcenterblack"><input type="checkbox" name="CheckOrUn" onClick="return unOrCheckCheckbox(document.form1);"></td>
										<td width="80" rowspan="2" class="tdcenterblack">Subjob No. </td>
										<td width="130" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td width="398" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td colspan="2" class="tdcenterblack">��¡���Ѻ���</td>
										<td width="15" rowspan="2" class="tdcenterblack">&nbsp;</td>
								      </tr>
									<tr>
									  <td width="50" class="tdcenterblack">�ӹǹ</td>
									  <td width="80" class="tdcenterblack">�Ҥҵ��˹���</td>
									</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div id="maentabel" style="  height:282px; width:795; overflow:auto; z-index=2;display:block;">		  	
										<table border="1" align="left" cellpadding="0" cellspacing="0">
										<?
											$strQUEDetailSubjob = "select pd.subjob,pd.subjob_show,v.bom_coderdh,v.bom_desc 
															from mrp_pd pd,mrp_fm fm,mrp_om om,mrp_pm pm,v_bom_detail v
															where pd.f_no=fm.f_no
															and om.jobno=fm.f_jobno
															and pm.p_status='1'
															and pm.p_no=pd.p_no
															and pm.p_prodno=v.bom_code";
											$curQUEDetailSubjob = odbc_exec($conn,$strQUEDetailSubjob);
											$i=0;
											while(odbc_fetch_row($curQUEDetailSubjob)){
												$v_subjob = odbc_result($curQUEDetailSubjob, "subjob");
												$v_subjob_show = odbc_result($curQUEDetailSubjob, "subjob_show");
												$v_bom_coderdh = odbc_result($curQUEDetailSubjob, "bom_coderdh");
												$v_bom_desc = odbc_result($curQUEDetailSubjob, "bom_desc");												
										?>
										<tr>
											<td width="25" class="tdcenterwhite"><input type=checkbox name="checkbox[]" value=<?=$i."|-|".$v_subjob; ?> onClick="return cal_podetqty(document.form1); if(this.checked==true){ document.form1.elements[<?=$i*3+11;  ?>].select(); } "></td>
											<td width="80">&nbsp;<?=$v_subjob_show; ?></td>
											<td width="130">&nbsp;<?=$v_bom_coderdh; ?></td>
											<td width="398">&nbsp;<?=$v_bom_desc; ?></td>
										  <td width="50"><input name="arr_garqty[]" type="text"   onKeyDown="return chkNumberInput('float');" size="3" maxlength="8" onKeyUp="return cal_podetqty(document.form1);"></td>
										  <td width="80"><input name="arr_garprice[]" type="text"   onKeyDown="return chkNumberInput('float');" size="8" maxlength="8" onKeyUp="return cal_podetqty(document.form1);"></td>
										</tr>
										<?	
												$i++;												
											}
										?>				
										</table>	
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<table border="0" align="left" cellpadding="0" cellspacing="0" >
									<tr>
										<td width="644" align="right" class="tdrightblack">���&nbsp;&nbsp;</td>
										<td width="50" class="tdleftblack" ><input name="sum_prod_qty" type="text"   size="3" maxlength="8" readonly=""  class="style_readonly"></td>
									    <td width="82" class="tdleftblack" ><input name="sum_prod_price" type="text"  class="style_readonly" id="sum_prod_price"   size="8" maxlength="8" readonly=""></td>
								      </tr>
									</table>
								</td>
							</tr>
							</table>
						</td>
					</tr>	  
					<tr>
						<td>				
							<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<th >
									<div align="right">                        
										<a OnClick="if(check_podetsubc(document.form1)&&chkCheckboxChoose(document.form1)){  document.form1.submit(); }" style="cursor:hand"><img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
										<a  onClick="document.form1.reset();" style="cursor:hand"><img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" ></a>
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
	</div>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>	
	<script language="JavaScript" type="text/JavaScript">
		document.form1.btnBOM.focus();
	</script>
</body>
</html>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>

