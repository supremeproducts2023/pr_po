<?
@session_start();
if(session_is_registered("valid_userprpo")) {
	require_once("../include_RedThemes/odbc_connect.php");		
	$empno_user = $_SESSION["empno_user"];
			
	include "../include_RedThemes/wait.php";
	flush();
?>
<html>
	<head>
		<title>*** ������¡���Թ��� ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>
		
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
				 }
			}       
		</script>
		
		<!-- Check Not null -->
		<script language='javascript'>
				function check_prdetsubc(obj){
					if(obj.prod_no.value==""){  	
						alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
						obj.FG.focus();
						return false;
					}			
					return true;
				}
		</script>			
		
		<!-- Calculate -->
		<script language="javascript">
				function cal_prdetqty(obj){	
						j = 0;
						for(var i=0; i < obj.elements.length ; i++){ 
								if((obj.elements[i].name=="checkbox[]")&&(obj.elements[i+1].value!="")){
									if(obj.elements[i].checked) j = j+parseInt(obj.elements[i+1].value);
								}
						}
						obj.sum_prod_qty.value = j;
						return null;
				}		
		</script>	
		
	</head>
<body topmargin="0" leftmargin="0">
<?
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='AddCode'){  // �óա��������ͷӧҹ˹�� code
					$v_pr_no = @$_POST["pr_no"];
					$v_prod_noshow = $_POST["prod_noshow"];
					$v_prod_no = $_POST["prod_no"];
					$v_prod_name=$_POST["prod_name"];
					$v_prod_unit=$_POST["prod_unit"];
					$discount_percent=0;
					$discount_baht=0;		
					
					$strMAX = "select ISNULL(max(id)+1,1) id  from pr_details where pr_no='$v_pr_no'";			
					$curMAX = @odbc_exec($conn,$strMAX);
					$id = @odbc_result($curMAX, "id");
					$strINSMaster = "insert into pr_details (
															id,pr_no,prod_no,prod_name,prod_qty,code,
															prod_unit,prod_price,discount_percent,discount_baht,
															rec_user,rec_date
													) values(
															'$id','$v_pr_no','$v_prod_noshow','$v_prod_name','0','$v_prod_no',
															'$v_prod_unit','0','$discount_percent','$discount_baht',
															'$empno_user',sysdate
													)";
					$exeINSMaster = odbc_exec($conn,$strINSMaster);
					
					$i=0;
					$ok = 0;
					$v_sum_prod_qty=0;
					while($i<count($checkbox)){
							$strValiable = $checkbox[$i];
							list($strrow,$v_subjob) = explode("|-|",$strValiable);	
							$v_prod_qty = $arr_qty[$strrow];
							if($v_prod_qty=="") $v_prod_qty=0;
							
							$strINS = "insert into pr_details_subjob (
													pr_no,id,subjob,qty
												) values(
													'$v_pr_no','$id','$v_subjob','$v_prod_qty'
												)";
							$exeINS = odbc_exec($conn,$strINS);
							if($exeINS){
								$ok++;
								$v_sum_prod_qty += $v_prod_qty;
							}
							$i++;
					}// end while($i<count($checkbox))
					$strUPDMaster = "update pr_details set prod_qty='$v_sum_prod_qty' where id='$id' and pr_no='$v_pr_no'";
					$exeUPDMaster = odbc_exec($conn,$strUPDMaster);
					
					$result=odbc_exec($conn,"update pr_master set pr_status='1',mng_remark='' where pr_no='$v_pr_no'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡�ä��");';
					echo '		window.opener.location.reload("./prmas_edit.php");';
					echo '		window.close();';
					echo '</script>';
				}else if($flagAction == 'UpCode'){
					$v_pr_no = @$_POST["pr_no"];
					$v_prod_name=$_POST["prod_name"];
					$v_id=$_POST["id"];

					$strUPDMaster = "delete from pr_details_subjob where id='$v_id' and pr_no='$v_pr_no'";
					$exeUPDMaster = odbc_exec($conn,$strUPDMaster);					
					$i=0;
					$ok = 0;
					$v_sum_prod_qty=0;
					while($i<count($checkbox)){
						$strValiable = $checkbox[$i];
						list($strrow,$v_subjob) = explode("|-|",$strValiable);	
						$v_prod_qty = $arr_qty[$strrow];
						if($v_prod_qty=="") $v_prod_qty=0;
						
						$strINS = "insert into pr_details_subjob (
												pr_no,id,subjob,qty
											) values(
												'$v_pr_no','$id','$v_subjob','$v_prod_qty'
											)";
						$exeINS = odbc_exec($conn,$strINS);
						if($exeINS){
							$ok++;
							$v_sum_prod_qty += $v_prod_qty;
						}
						$i++;
					}// end while($i<count($checkbox))
					$strUPDMaster = "update pr_details set 
															prod_name='$v_prod_name',
															prod_qty='$v_sum_prod_qty' 
													where id='$v_id' 
													and pr_no='$v_pr_no'";
					$exeUPDMaster = odbc_exec($conn,$strUPDMaster);
					
					$result=odbc_exec($conn,"update pr_master set pr_status='1',mng_remark='' where pr_no='$v_pr_no'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡�ä��");';
					echo '		window.opener.location.reload("./prmas_edit.php");';
					echo '		window.close();';
					echo '</script>';				
				}// end if($flagAction!='')
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	

	$pr_no = $_SESSION["sespk_no"]; 
	$vat_include=$_GET["vat_include"];
	$flag= $_GET["flag"];
	if($flag=="edit"){
			$id= $_GET["id"];		
			$strQUE = "select prod_no,code,prod_name,prod_unit  
								from pr_details
								where pr_no='$pr_no' 
								and id='$id'";
			$curQUE = odbc_exec($conn, $strQUE );
			$prod_noshow =odbc_result($curQUE,"prod_no");		
			$prod_no =odbc_result($curQUE,"code");		
			$prod_name =odbc_result($curQUE,"prod_name");		
			$prod_unit =odbc_result($curQUE,"prod_unit");		
	}
?>
	<div align="center">  
		<form name="form_prdet" action="prdet_addsubc.php" method="post">
			<input name="id" type="hidden" value="<?=$id; ?>">
			<input name="flagAction" type="hidden" value="<? if($flag=="edit")echo 'UpCode'; else echo 'AddCode'; ?>">	 
			<table width="790"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
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
								<td width="120" class="tdleftwhite">�Ţ���� PR  <span class="style_star">*</span></td>
								<td width="439"><input name="pr_no" type="text" class="style_readonly" value="<?=$pr_no; ?>"  readonly=""></td>
							</tr>
							</table>
						</td>
					</tr>			  
					<tr>
						<td>
							<table width="100%"  border="1" cellspacing="0" cellpadding="0">
							<tr>
								<td width="120"  class="tdleftwhite">&nbsp;�����Թ��� <span class="style_star">*</span> </td>
								<td width="433">
									<input name="prod_noshow" type="text"  value="<?=@$prod_noshow; ?>" size="20" maxlength="50" readonly="" class="style_readonly"><input name="FG" type="button" id="FG"  value="�Թ���(BOM)"  onClick="lovBOM('R,P');" <? if($flag=="edit")echo "disabled"; ?>>
									<input name="prod_no" type="hidden"  value="<?=@$prod_no; ?>">
									<br><span class="style_text">�ó�����Һ�����Թ��� ��س��ͺ������Ἱ� MS Local �µç</span>
								</td>
							</tr>
							<tr>
								<td  class="tdleftwhite">&nbsp;�����Թ���</td>
								<td><input name="prod_name" type="text"  value="<?=@$prod_name; ?>" size="60" maxlength="300"  onKeyUp="return chkStringInput(this,300);" readonly="" class="style_readonly"></td>
							</tr>
							<tr>
								<td  class="tdleftwhite">&nbsp;˹���  <span class="style_star">*</span></td>
								<td>
									<input name="prod_unit" type="text" value="<?=@$prod_unit; ?>" size="20"  maxlength="15" readonly="" class="style_readonly">
									<?
				  						if($vat_include=="1")echo '<span class="style_text">(�Ҥ���� VAT����)</span>'; else if($vat_include=="0")echo '<span class="style_text">(�Ҥ��ѧ������ VAT)</span>';
									  ?>
								</td>
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
										<td width="25" class="tdcenterblack"><input type="checkbox" name="CheckOrUn" onClick="return unOrCheckCheckbox(document.form_prdet);"></td>
										<td width="80" class="tdcenterblack">Subjob No. </td>
										<td width="130" class="tdcenterblack">�����Թ���</td>
										<td width="455" class="tdcenterblack">�����Թ���</td>
										<td width="60" class="tdcenterblack">�ӹǹ</td>
									  </tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div id="maentabel" style="  height:282px; width:790; overflow:auto; z-index=2;display:block;">		  	
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
											<td width="25" class="tdcenterwhite"><input type=checkbox name="checkbox[]" value=<?=$i."|-|".$v_subjob; ?> onClick="if(this.checked==true){ document.form_prdet.elements[<?=$i*2+10;  ?>].select(); } "></td>
											<td width="80">&nbsp;<?=$v_subjob_show; ?></td>
											<td width="130">&nbsp;<?=$v_bom_coderdh; ?></td>
											<td width="455">&nbsp;<?=$v_bom_desc; ?></td>
										  <td width="60"><input name="arr_qty[]" type="text"   onKeyDown="return chkNumberInput('float');" size="4" maxlength="8" onKeyUp="return cal_prdetqty(document.form_prdet);"></td>
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
										<td width="690" align="right" class="tdrightblack">���&nbsp;&nbsp;</td>
										<td width="50" class="tdleftblack" ><input name="sum_prod_qty" type="text"   size="4" maxlength="8" readonly=""  class="style_readonly"></td>
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
										<a OnClick="if(check_prdetsubc(document.form_prdet)&&chkCheckboxChoose(document.form_prdet)){  document.form_prdet.submit(); }" style="cursor:hand"><img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
										<a  onClick="document.form_prdet.reset();" style="cursor:hand"><img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" ></a>
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
	<script language="JavaScript" type="text/JavaScript">
		document.form_prdet.FG.focus();
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

