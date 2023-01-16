<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		$empno_user = $_SESSION["empno_user"];
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='SearchCode'){ 
						$prod_type = @$_POST["s_prod_type"];
						$keyword = @$_POST["keyword"];
						$v_po_no= $_POST["po_no"];
							
							if($prod_type=='1'){	
									$strQUE = "select bom_code code,bom_codeshow prod_no,bom_desc prod_name,bom_unit prod_unit,
																		case bom_type when 2 then 'Assembly Part' else 'Raw Material' end type
														 from nbom_description 
														 where bom_type in (1,2) 
														 and used_flag='Y' ";
									if($keyword != "") $strQUE .= "and (upper(bom_codeshow) like upper('%$keyword%')) or (upper(bom_desc) like upper('%$keyword%'))";
									$curQUE= odbc_exec($conn,$strQUE." order by bom_type,bom_codeshow");					
							}else if(($prod_type=='3')||($prod_type=='4')){
									$strQUE = "select prodno code,princ_prodno prod_no,engname +'('+ id_keep +')' prod_name,unittype prod_unit,prod_type type from product where status='1' ";
									if($keyword != "") $strQUE .= "and (upper(princ_prodno) like upper('%$keyword%')) or (upper(engname) like upper('%$keyword%'))";
									$curQUE= odbc_exec($conn,$strQUE." order by princ_prodno");					
							}
				}else if($flagAction=='AddCode'){ 
					$v_po_no = @$_POST["po_no"];
					$v_prod_type = @$_POST["prod_type"];
					
					$checkbox	=@$_POST["checkbox"];				
					$arr_prod_no=@$_POST["arr_prod_no"];
					$arr_prod_name=@$_POST["arr_prod_name"];
					
					$arr_prod_qty=@$_POST["arr_prod_qty"];
					$arr_prod_unit=@$_POST["arr_prod_unit"];
					$arr_prod_price=@$_POST["arr_prod_price"];
					
					$arr_gar_qty=@$_POST["arr_gar_qty"];
					$arr_gar_unit=@$_POST["arr_gar_unit"];
					$arr_gar_price=@$_POST["arr_gar_price"];


					$strMAX = "select ISNULL(max(id)+1,1) id  from po_details where po_no='$v_po_no'";			
					$curMAX = @odbc_exec($conn,$strMAX);
					$id = @odbc_result($curMAX, "id");

					$i=0;
					$ok = 0;
					while($i<count($checkbox)){
							$strValiable = $checkbox[$i];
							list($strrow,$v_code) = explode("|-|",$strValiable);	
							$v_prod_no = $arr_prod_no[$strrow];
							$v_prod_name = $arr_prod_name[$strrow];
							$v_prod_qty = $arr_prod_qty[$strrow];
							$v_prod_unit = $arr_prod_unit[$strrow];
							$v_prod_price = $arr_prod_price[$strrow];
							$v_gar_qty = $arr_gar_qty[$strrow];
							$v_gar_unit = $arr_gar_unit[$strrow];
							$v_gar_price = $arr_gar_price[$strrow];

							if($v_gar_qty=="") $v_gar_qty=0;
							if($v_gar_price=="") $v_gar_price=0;
							
							$id += $i;							
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
							
							if($exeINS){
								$ok++;
							}
							$i++;
					}// end while($i<count($checkbox))
					
					$result=odbc_exec($conn,"update po_master set po_status='1' where po_no='$v_po_no'");						
					$exeCOMMIT = odbc_exec($conn,"commit");
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡�ä��");';
					echo '		window.opener.location.reload("./pomas_edit.php");';
					echo '		window.close();';
					echo '</script>'; 
				}else{
					$v_po_no = $_GET["po_no"]; 
				}
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
	include "../include_RedThemes/wait.php";
	flush();
?>
<html>
	<head>
		<title>�����Թ��һ����� BOM, Product, Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>
		
	</head>	
<body topmargin="0" leftmargin="0">
	<div align="center">  
		<form name="form1" action="podet_addbom.php" method="post">
			<input name="flagAction" type="hidden" value="SearchCode">
			<input name="prod_type" value="<?= @$prod_type; ?>" type="hidden">
			<table width="900"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
			<tr>
				<td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="130" class="tdleftwhite">&nbsp;�Ţ���� PO <span class="style_star">*</span></td>
                    <td width="250"><input name="po_no" type="text" class="style_readonly" value="<?=$v_po_no; ?>"  readonly=""></td>
                    <td width="120"><span class="tdleftwhite">&nbsp;�������Թ��ҷ��ѹ�֡</span></td>
                    <td class="style_text">&nbsp;
                      <? if(@$prod_type=="1") echo 'BOM'; else if(@$prod_type=="3") echo 'Product'; else if(@$prod_type=="4") echo 'Service'; ?></td>
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
								<td><select name="s_prod_type">
                                  <option value="1" <? if((@$prod_type=="")||(@$prod_type=="1"))echo 'selected="selected"'; ?>>BOM</option>
                                  <option value="3" <? if(@$prod_type=="3")echo 'selected="selected"'; ?>>Product</option>
                                  <option value="4" <? if(@$prod_type=="4")echo 'selected="selected"'; ?>>Service</option>
                                </select>
								  <input name="keyword" type="text"  size="60" value="<?= @$keyword; ?>"> 
							    (�����Թ���, �����Թ���) 
						        <input type="submit" name="Submit" value="����" onKeyDown="if(event.keyCode==13) document.form1.submit();"></td>
							</tr>
							</table>						</td>
					</tr>		
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table border="1" align="left" cellpadding="0" cellspacing="0" >
									<tr>
										<td width="25" rowspan="2" class="tdcenterblack"><input type="checkbox" name="CheckOrUn" onClick="return unOrCheckCheckbox(document.form1);"></td>
										<td width="80" rowspan="2" class="tdcenterblack">������</td>
										<td width="130" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td width="260" rowspan="2" class="tdcenterblack">�����Թ���</td>
										<td colspan="3" class="tdcenterblack">��¡�ú�� PO </td>
										<td colspan="3" class="tdcenterblack">��¡���Ѻ���</td>
										<td width="15" rowspan="2" class="tdcenterblack">&nbsp;</td>
								      </tr>
									<tr>
									  <td width="50" class="tdcenterblack">�ӹǹ</td>
									  <td width="50" class="tdcenterblack">˹���</td>
									  <td width="80" class="tdcenterblack">�Ҥҵ��˹���</td>
									  <td width="50" class="tdcenterblack">�ӹǹ</td>
									  <td width="50" class="tdcenterblack">˹���</td>
									  <td width="80" class="tdcenterblack">�Ҥҵ��˹���</td>
									</tr>
									</table>								</td>
							</tr>
							<tr>
								<td>
									<div id="maentabel" style="  height:390px; width:895; overflow:auto; z-index=2;display:block;">	
									<? if(@$prod_type != ""){ ?>
										<table border="1" align="left" cellpadding="0" cellspacing="0">
										<?
											$i=0;
											while(odbc_fetch_row($curQUE)){
												$v_code = odbc_result($curQUE, "code");
												$v_prod_no = odbc_result($curQUE, "prod_no");
												$v_prod_name = odbc_result($curQUE, "prod_name");
												$v_prod_unit = odbc_result($curQUE, "prod_unit");		
												$v_type =  odbc_result($curQUE, "type");												
										?>
										<tr>
											<td width="25" class="tdcenterwhite"><input type=checkbox name="checkbox[]" value=<?=$i."|-|".$v_code; ?> onClick="if(this.checked==true){ document.form1.elements[<?=$i*9+8;  ?>].select(); } "></td>
											<td width="80">&nbsp;<?=$v_type; ?></td>
											<td width="130"><?=$v_prod_no; ?><input name="arr_prod_no[]" type="hidden" id="arr_prod_no[]"  size="18"  value="<?=$v_prod_no; ?>" readonly="" class="style_readonly"></td>
											<td width="260"><input name="arr_prod_name[]" type="text" id="arr_prod_name[]"   onKeyUp="return chkStringInput(this);" size="37" maxlength="300" value="<?=$v_prod_name; ?>"></td>
										  <td width="50"><input name="arr_prod_qty[]" type="text" id="arr_prod_qty[]"   onKeyDown="return chkNumberInput('float');" size="3" maxlength="8" ></td>
										  <td width="50"><input name="arr_prod_unit[]" type="text" id="arr_prod_unit[]"   onKeyUp="return chkStringInput(this);" size="3" maxlength="15" ></td>
										  <td width="80"><input name="arr_prod_price[]" type="text" id="arr_prod_price[]"   onKeyDown="return chkNumberInput('float');" size="8" maxlength="16" ></td>
										  <td width="50"><input name="arr_gar_qty[]" type="text" id="arr_gar_qty[]"   onKeyDown="return chkNumberInput('float');" size="3" maxlength="8" ></td>
										  <td width="50"><input name="arr_gar_unit[]" type="text" id="arr_gar_unit[]"  size="3" value="<?= $v_prod_unit; ?>" readonly="" class="style_readonly"></td>
										  <td width="80"><input name="arr_gar_price[]" type="text" id="arr_gar_price[]"   onKeyDown="return chkNumberInput('float');" size="8" maxlength="16" ></td>
										</tr>
										<?	
												$i++;												
											}
										?>				
										</table>	
									<? } ?>
									</div>								</td>
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

