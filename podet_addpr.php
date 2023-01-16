<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		$empno_user = $_SESSION["empno_user"];
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='SearchCode'){ 
						$v_po_no= $_POST["po_no"];
						$type_search = @$_POST["type_search"];
						$keyword = @$_POST["keyword"];
							
                        $strSQL = "select po_company from po_master where po_no = '$v_po_no'";
                        $strResult = odbc_exec($conn,$strSQL);
                        $po_company = @odbc_result($strResult,"po_company");
                        if($po_company == "T") $flag_obj = "pm.flag_obj = '8'"; else $flag_obj = "pm.flag_obj not in ('8')";
                                                                 $strQUE = "select pm.pr_no,pm.flag_obj,
																			pd.id,pd.code,pd.prod_no,pd.prod_name,
																			pd.prod_qty,pd.prod_unit,pd.prod_price,pd.discount_baht
																	from pr_master pm,pr_details pd,emp e
																	where pm.pr_no=pd.pr_no
																	and pm.empno=e.empno
																	and ".$flag_obj."
																	and pm.pr_status='4' ";

									if($keyword != ""){
											switch($type_search){
												case 'pr_no'				:	$strQUE .= "and (upper(pm.pr_no) like upper('%$keyword%')) ";
																						break;
												case 'e_name'			:	$strQUE .= "and (upper(e.e_name) like upper('%$keyword%')) ";
																						break;
												case 'prod_no'			:	$strQUE .= "and (upper(prod_no) like upper('%$keyword%')) ";
																						break;
												case 'prod_name'	:	$strQUE .= "and (upper(prod_name) like upper('%$keyword%')) ";
																						break;
											}	
									}
									$curQUE= odbc_exec($conn,$strQUE." order by pm.pr_no,pd.id");						
				}else if($flagAction=='AddCode'){ 
					$v_po_no = @$_POST["po_no"];
					
					$checkbox=@$_POST["checkbox"];
					
					$arr_prod_type=@$_POST["arr_prod_type"];
					$arr_prod_no=@$_POST["arr_prod_no"];
					$arr_prod_name=@$_POST["arr_prod_name"];
					$arr_prod_qty=@$_POST["arr_prod_qty"];
					$arr_prod_unit=@$_POST["arr_prod_unit"];
					$arr_prod_price=@$_POST["arr_prod_price"];
					$arr_gar_qty=@$_POST["arr_gar_qty"];
					$arr_gar_unit=@$_POST["arr_gar_unit"];
					$arr_gar_price=@$_POST["arr_gar_price"];

					$discount_percent=0;
					$discount_baht=0;		

					$strMAX = "select ISNULL(max(id)+1,1) id  from po_details where po_no='$v_po_no'";			
					$curMAX = @odbc_exec($conn,$strMAX);
					$id = @odbc_result($curMAX, "id");

					$i=0;
					$ok = 0;
					while($i<count($checkbox)){
							$strValiable = $checkbox[$i];
							list($strrow,$v_code,$v_pr_no,$v_id) = explode("|-|",$strValiable);		
													
							$v_prod_type = $arr_prod_type[$strrow];
							$v_prod_no = $arr_prod_no[$strrow];
							$v_prod_name = $arr_prod_name[$strrow];
							$v_prod_qty = $arr_prod_qty[$strrow];
							$v_prod_unit = $arr_prod_unit[$strrow];
							$v_prod_price = $arr_prod_price[$strrow];
							$v_gar_qty = $arr_gar_qty[$strrow];
							$v_gar_unit = $arr_gar_unit[$strrow];
							$v_gar_price = $arr_gar_price[$strrow];
							
							$id += $i;							
							
							switch($v_prod_type){
								case '1'	:	// BOM
								case '3'	:	// Product
								case '4'	:	// Service
													if($v_gar_qty=="") $v_gar_qty=0;
													if($v_gar_price=="") $v_gar_price=0;								
													$strINS = "insert into po_details (
																		id,po_no,prod_type,show_id,
																		code,prod_no,prod_name,												
																		prod_qty,prod_price,prod_unit,
																		gar_qty,gar_price,gar_unit,
																		rec_user,rec_date
																		) values(
																		'$id','$v_po_no','$v_prod_type','1',
																		'$v_code','$v_prod_no','$v_prod_name',
																		'$v_prod_qty','$v_prod_price','$v_prod_unit',
																		'$v_gar_qty','$v_gar_price','$v_gar_unit',
																		'$empno_user',getdate())";
													$exeINS = odbc_exec($conn,$strINS);
													break;
								case '5'	:	// ETC
								case '6'	:	// Detail
													$strINS = "insert into po_details (
																		id,po_no,prod_type,
																		prod_no,prod_name,												
																		prod_qty,prod_price,prod_unit,
																		rec_user,rec_date
																		) values(
																		'$id','$v_po_no','$v_prod_type',
																		'$v_prod_no','$v_prod_name',
																		'$v_prod_qty','$v_prod_price','$v_prod_unit',
																		'$empno_user',getdate())";
													$exeINS = @odbc_exec($conn,$strINS);								
													break;
								case '2'	:	// Sub Contact
													if($v_gar_qty=="") $v_gar_qty=0;
													if($v_gar_price=="") $v_gar_price=0;								
													$strINS = "insert into po_details (
																		id,po_no,prod_type,show_id
																		code,prod_no,prod_name,												
																		prod_qty,prod_price,prod_unit,
																		gar_qty,gar_price,gar_unit,
																		rec_user,rec_date
																		) values(
																		'$id','$v_po_no','$v_prod_type','1',
																		'$v_code','$v_prod_no','$v_prod_name',
																		'$v_prod_qty','$v_prod_price','$v_prod_unit',
																		'$v_gar_qty','$v_gar_price','$v_gar_unit',
																		'$empno_user',getdate())";
													$exeINS = odbc_exec($conn,$strINS);		
													
													$strINSSubjob = "insert into po_details_subjob (po_no,id,subjob,qty,cost) (select '".$v_po_no."','".$id."',subjob,qty,'".$v_gar_price."' from pr_details_subjob where pr_no='".$v_pr_no."' and id='".$v_id."')";
													$exeINSSubjob = odbc_exec($conn,$strINSSubjob);															
													break;																
							}
							
							if($exeINS){
								$ok++;
								$result=@odbc_exec($conn,"insert into pr_and_po (po_no,pr_no) values('$v_po_no','$v_pr_no')");														
							}
							$i++;
					}// end while($i<count($checkbox))
					
					$result=odbc_exec($conn,"update po_master set po_status='1' where po_no='$v_po_no'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡��\n\n  �����������������������ѹ�������ҧ PO ��� PR ���º�������Ǥ��");';
					echo '		window.opener.location.reload("./pomas_edit.php");';
					echo '		window.close();';
					echo '</script>'; 								
				}else{
						$v_po_no = @$_GET["po_no"]; 
				}
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
	include "../include_RedThemes/wait.php";
	flush();
		
		
									
		
?>
<html>
	<head>
		<title>�����Թ��ҵ�� PR (���»�����)</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>

		<!-- Calculate -->
		<script language="javascript">
			function fn_SetFill(obj,i){
					prod_type = i +1;
					prodno =  i +2;
					gar_qty =  i +7;
					gar_unit = i+8;
					gar_price = i + 9;
					
				switch(obj.elements[prod_type].value){
					case '1'		:	obj.elements[prodno].readOnly = true;		obj.elements[prodno].style.background = "#CFE4FE";	obj.elements[prodno].style.color = "#666666";
											obj.elements[gar_qty].readOnly = false;	obj.elements[gar_qty].style.background = "#ffffff";				obj.elements[gar_qty].style.color = "#000000";
											obj.elements[gar_price].readOnly = false;	obj.elements[gar_price].style.background = "#ffffff";		obj.elements[gar_price].style.color = "#000000";
											break;
					case '2'		:	obj.elements[prodno].readOnly = true;		obj.elements[prodno].style.background = "#CFE4FE";	obj.elements[prodno].style.color = "#666666";
											obj.elements[gar_qty].readOnly = true;		obj.elements[gar_qty].style.background = "#CFE4FE";	obj.elements[gar_qty].style.color = "#666666";
											obj.elements[gar_price].readOnly = false;	obj.elements[gar_price].style.background = "#ffffff";		obj.elements[gar_price].style.color = "#000000";
											break;
					case '3'		:	obj.elements[prodno].readOnly = true;		obj.elements[prodno].style.background = "#CFE4FE";	obj.elements[prodno].style.color = "#666666";
											obj.elements[gar_qty].readOnly = false;	obj.elements[gar_qty].style.background = "#ffffff";				obj.elements[gar_qty].style.color = "#000000";
											obj.elements[gar_price].readOnly = false;obj.elements[gar_price].style.background = "#ffffff";		obj.elements[gar_price].style.color = "#000000";
											break;
					case '4'		:	obj.elements[prodno].readOnly = true;		obj.elements[prodno].style.background = "#CFE4FE";	obj.elements[prodno].style.color = "#666666";
											obj.elements[gar_qty].readOnly = false;	obj.elements[gar_qty].style.background = "#ffffff";				obj.elements[gar_qty].style.color = "#000000";
											obj.elements[gar_price].readOnly = false;obj.elements[gar_price].style.background = "#ffffff";		obj.elements[gar_price].style.color = "#000000";
											break;
					case '5'		:	obj.elements[prodno].readOnly = false;	obj.elements[prodno].style.background = "#ffffff";				obj.elements[prodno].style.color = "#000000";
											obj.elements[gar_qty].readOnly = true;		obj.elements[gar_qty].style.background = "#CFE4FE";	obj.elements[gar_qty].style.color = "#666666";
											obj.elements[gar_price].readOnly = true;	obj.elements[gar_price].style.background = "#CFE4FE";obj.elements[gar_price].style.color = "#666666";
											break;
					case '6'		:	obj.elements[prodno].readOnly = false;	obj.elements[prodno].style.background = "#ffffff";				obj.elements[prodno].style.color = "#000000";
											obj.elements[gar_qty].readOnly = true;		obj.elements[gar_qty].style.background = "#CFE4FE";	obj.elements[gar_qty].style.color = "#666666";
											obj.elements[gar_price].readOnly = true;	obj.elements[gar_price].style.background = "#CFE4FE";obj.elements[gar_price].style.color = "#666666";
											break;
				}
				obj.elements[gar_unit].readOnly = true;	obj.elements[gar_unit].style.background = "#CFE4FE";		obj.elements[gar_unit].style.color = "#666666";
			}		
		</script>
	</head>	
<body topmargin="0" leftmargin="0">
	<div align="center">  
		<form name="form1" action="podet_addpr.php" method="post">
			<input name="flagAction" type="hidden" value="SearchCode">
			<table width="950"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
			<tr>
				<td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="130" class="tdleftwhite">&nbsp;�Ţ���� PO <span class="style_star">*</span></td>
                    <td><input name="po_no" type="text" class="style_readonly" value="<?=$v_po_no; ?>"  readonly=""></td>
                  </tr>

                </table></td>
			</tr>
			<tr>
				<th width="550">&nbsp;&nbsp;Search</th>
			</tr>
			<tr>
				<td> 
					<table width="100%" border="0" align="center">			  
					<tr>
						<td>
							<table width="100%"  border="1" cellspacing="0" cellpadding="0">
							<tr>
								<td width="130"  class="tdleftwhite">&nbsp;Keyword �������� </td>
								<td>
										<select name="type_search">
										  <option value="pr_no" <? if((@$type_search=="pr_no")||(@$type_search==""))echo 'selected="selected"'; ?>>���ҵ���Ţ��� PR</option>
										  <option value="e_name" <? if(@$type_search=="e_name")echo 'selected="selected"'; ?>>���ҵ�����ͼ���Դ PR</option>
										  <option value="prod_no" <? if(@$type_search=="prod_no")echo 'selected="selected"'; ?>>���ҵ�������Թ���</option>
										  <option value="prod_name" <? if(@$type_search=="prod_name")echo 'selected="selected"'; ?>>���ҵ�������Թ���</option>
										</select>
										  <input name="keyword" type="text"  size="50" value="<?= @$keyword; ?>">
										  <input type="submit" name="Submit" value="����" onKeyDown="if(event.keyCode==13) document.form1.submit();">
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
										<td width="25" rowspan="2" class="tdcenterblack"><input type="checkbox" name="CheckOrUn" onClick="return unOrCheckCheckbox(document.form1);"></td>
										<td width="83" rowspan="2" class="tdcenterblack">������</td>
										<td width="60" rowspan="2" class="tdcenterblack">PR No. </td>
										<td width="130" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td width="305" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td colspan="3" class="tdcenterblack">��¡�ú�� PO </td>
										<td colspan="3" class="tdcenterblack">��¡���Ѻ���</td>
										<td width="15" rowspan="2" class="tdcenterblack">&nbsp;</td>
								      </tr>
									<tr>
									  <td width="40" class="tdcenterblack">�ӹǹ</td>
									  <td width="40" class="tdcenterblack">˹���</td>
									  <td width="70" class="tdcenterblack">�Ҥҵ��˹���</td>
									  <td width="40" class="tdcenterblack">�ӹǹ</td>
									  <td width="40" class="tdcenterblack">˹���</td>
									  <td width="70" class="tdcenterblack">�Ҥҵ��˹���</td>
									</tr>
									</table>								</td>
							</tr>
							<tr>
								<td>
									<div id="maentabel" style="  height:390px; width:945; overflow:auto; z-index=2;display:block;">	
										<table border="1" align="left" cellpadding="0" cellspacing="0">
										<?
											$i=0;
											while(@odbc_fetch_row($curQUE)){
												$v_pr_no = odbc_result($curQUE, "pr_no");
												$v_id = odbc_result($curQUE, "id");
												$v_flag_obj = odbc_result($curQUE, "flag_obj");
												$v_code =  odbc_result($curQUE, "code");												
												$v_prod_no =  odbc_result($curQUE, "prod_no");												
												$v_prod_name =  odbc_result($curQUE, "prod_name");												
												$v_prod_qty =  odbc_result($curQUE, "prod_qty");												
												$v_prod_unit =  odbc_result($curQUE, "prod_unit");												
												$v_prod_price =  odbc_result($curQUE, "prod_price");												
												$v_discount_baht =  odbc_result($curQUE, "discount_baht");	
												
												if((($v_prod_price*$v_prod_qty)-$v_discount_baht)==0) $prod_price = 0;
												else $v_prod_price = (($v_prod_price*$v_prod_qty)-$v_discount_baht)/$v_prod_qty;
												
												
												$num_of_obj = 6;
										?>
										<tr>
											<td width="25" class="tdcenterwhite"><input type=checkbox name="checkbox[]" value=<?=$i."|-|".$v_code."|-|".$v_pr_no."|-|".$v_id; ?> onClick="if(this.checked==true){ document.form1.elements[<?=$i*10+$num_of_obj+1;  ?>].focus(); } " ></td>
											<td width="83">
												  <select name="arr_prod_type[]" style="width:81px; font-size:9px;">
														<? if($v_code==""){ ?>
															<option value="5">ETC</option>
															<option value="6">Detail</option>
														<? }elseif($v_flag_obj=='7'){	 ?>
															<option value="2">SubContact</option>
															<option value="1">BOM</option>
														<? }elseif($v_flag_obj=='2'){	 ?>
															<option value="1">BOM</option>
														<? }elseif($v_flag_obj=='3'){	 ?>
															<option value="4">Service</option>
															<option value="3">Product</option>
														<? }else{	 ?>
															<option value="3">Product</option>
															<option value="4">Service</option>
														<? }?>
												  </select>
											</td>
											<td width="60"><?=$v_pr_no; ?>
										    </td>
											<td width="130"><input name="arr_prod_no[]" type="text" id="arr_prod_no[]"  size="21"  value="<?=$v_prod_no; ?>" style="font-size:9px;"></td>
										  <td width="305"><input name="arr_prod_name[]" type="text" id="arr_prod_name[]"   onKeyUp="return chkStringInput(this);" size="56" maxlength="300" value="<?=$v_prod_name; ?>" style="font-size:9px;"></td>
										  <td width="40"><input name="arr_prod_qty[]" type="text" id="arr_prod_qty[]"   onKeyDown="return chkNumberInput('float');" size="3" maxlength="8" value="<?= $v_prod_qty; ?>" style="font-size:9px;"></td>
										  <td width="40"><input name="arr_prod_unit[]" type="text" id="arr_prod_unit[]"   onKeyUp="return chkStringInput(this);" size="3" maxlength="15"  value="<?= $v_prod_unit; ?>" style="font-size:9px;"></td>
										  <td width="70"><input name="arr_prod_price[]" type="text" id="arr_prod_price[]"   onKeyDown="return chkNumberInput('float');" size="9" maxlength="16" value="<?= $v_prod_price; ?>" style="font-size:9px;"></td>
										  <td width="40"><input name="arr_gar_qty[]" type="text" id="arr_gar_qty[]"   onKeyDown="return chkNumberInput('float');" size="3" maxlength="8" value="<? if($v_code!= "")echo $v_prod_qty; ?>" style="font-size:9px;"></td>
										  <td width="40"><input name="arr_gar_unit[]" type="text" id="arr_gar_unit[]"  size="3" value="<? if($v_code!= "")echo $v_prod_unit; ?>" style="font-size:9px;"></td>
										  <td width="70"><input name="arr_gar_price[]" type="text" id="arr_gar_price[]"   onKeyDown="return chkNumberInput('float');" size="9" maxlength="16"  value="<? if($v_code!= "")echo $v_prod_price; ?>" style="font-size:9px;"></td>
										</tr>
										  <script language="javascript">
												  fn_SetFill(document.form1,<?= $i*10+$num_of_obj;?>);
										  </script>															
										
										<?	
												$i++;												
											}
										?>				
										</table>	
									</div>								
								</td>
							</tr>
							</table>						</td>
					</tr>	  
					<tr>
						<td>				
							<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<th >
									<div align="right">                        
										<a OnClick="if(chkCheckboxChoose(document.form1)){  document.form1.flagAction.value='AddCode'; document.form1.submit(); }" style="cursor:hand"><img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
										<a  onClick="document.form1.reset();" style="cursor:hand"><img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" ></a>									</div>								</th>
							</tr>		
							</table>						</td>
					</tr>
					</table>
				</td>
			</tr>
		  </table>
		</form>
	</div>
</body>
</html>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>
<script language="javascript">
	document.form1.keyword.select();
</script>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>

