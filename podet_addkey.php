<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");		
		$empno_user = $_SESSION["empno_user"];
	
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='AddCode'){  // �óա��������ͷӧҹ˹�� code
						$v_data = $_POST["data"];
						$v_po_no = @$_POST["po_no"];
						
						$i=0;
						$ok = 0;
						while($i<count($v_data)){
								$v_prod_type = trim($v_data[$i++]);	
								$v_Item = $v_data[$i++];				
								$v_prod_no = $v_data[$i++];		
								$v_prod_name = $v_data[$i++];	
								$v_prod_qty = $v_data[$i++];		
								$v_prod_unit = $v_data[$i++];		
								$v_prod_price = $v_data[$i++];	
								if($v_prod_type=="Etc") $v_prod_type='5'; else $v_prod_type='6';	 
								$str_mx = "select ISNULL(max(id)+1,1) mx  from po_details where po_no='$v_po_no'";			
								$cur_mx = @odbc_exec($conn,$str_mx);
								$v_mx = @odbc_result($cur_mx, "mx");
								$strINS = "insert into po_details (
													id,po_no,prod_type,";
													
								if($v_prod_type!='6') $strINS .= "show_id,";
													
								$strINS .= "prod_no,prod_name,
													prod_qty,prod_unit,prod_price,
													rec_user,rec_date,Item_Code
													) values(
													'$v_mx','$v_po_no','$v_prod_type',";
													
								if($v_prod_type!='6') $strINS .= "'1',";
													
								$strINS .= "'$v_prod_no','$v_prod_name',
													'$v_prod_qty','$v_prod_unit','$v_prod_price',
													'$empno_user',sysdate,'$v_Item')";
									//				echo $strINS;
								$exeINS = odbc_exec($conn,$strINS);
								if($exeINS)$ok++;
						}// end while($i<count($data))

						$result=odbc_exec($conn,"update po_master set po_status='1' where po_no='$v_po_no' ");
						$exe_commit = odbc_exec($conn,"commit");
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo '		alert ("�����Ŷ١�ѹ�֡������ '.$ok.' ��¡�ä��");';
						echo '		window.opener.location.reload("./pomas_edit.php");';
						echo '		window.close();';
						echo '</script>';

				}// end if($flagAction!='')
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
		$v_po_no = @$_POST["po_no"];
		if($v_po_no=="")$v_po_no = @$_GET["po_no"];
		?>
		<html>
				<head>
						<title>�����Թ��һ����� Detail, Etc</title>
						<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
						<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>		
						<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>		
						<link href="../include/style1.css" rel="stylesheet" type="text/css">
						<script language="javascript">
								var arrInput = new Array(0);
								  var arrInputValue = new Array(0);
								
								function openItem(type_use){
									returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_Item.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:600px;dialogHeight:500px;');											
									return returnvalue;
								}
								
								function lovItem(id1,id2,index1,index2){
									returnvalue = openItem(''); 
									if (returnvalue != null){ 
										var values =  returnvalue.split("|-|");
										if(values[0]!= ""){ document.getElementById(id1).value = values[0]; saveValue(index1,values[0]); }		
										if(values[1]!= ""){ document.getElementById(id2).value = values[1]; saveValue(index2,values[1]); }
									 }
								}      
								
								function display() {
								  document.getElementById('parah').innerHTML="";
								  intI = 0;
								  while(intI < arrInput.length){
									document.getElementById('parah').innerHTML+=createProd_type(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML+=createItem(arrInput[intI], arrInputValue[intI], arrInputValue[intI-1], arrInput[++intI] , arrInputValue[intI]); 
									intI++;
									document.getElementById('parah').innerHTML+=createProd_no(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML+=createProd_name(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML+=createProd_qty(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML+=createProd_unit(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML+=createProd_price(arrInput[intI], arrInputValue[intI]);
									intI++;
									document.getElementById('parah').innerHTML += "<br>";
								  }
								}
								
								function saveValue(intId,strValue) {
								  arrInputValue[intId]=strValue;
								}  
								
								function createProd_type(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' readonly='readonly'  style='background-color:#CFE4FE;border-style:groove;color:#FF0000; font-weight:bold; width:80'>";
								}

								function createItem(ID1,value1,check,ID2,value2) {
										if(check=="Etc"){	
												return "<input name='btn_select' type='button' id='btn"+ ID1 +"' value='���͡...' onClick='lovItem(\"test\"+ "+ID1+",\"test\"+ "+ID2+","+ID1+","+ID2+");' style='width:40'><input type='text' id='test"+ ID1 +"' value='"+ value1 +"' readonly='readonly'  style='width:206;background-color:#FF99CC'><input name='data[]' id='test"+ ID2 +"' type='hidden' value='"+ value2 +"' />";
										}else{
												return "<input name='btn_select' type='button' value='���͡...' onClick='lovItem(\"test\"+ "+ID1+",\"test\"+ "+ID2+","+ID1+","+ID2+");' style='width:40' disabled='disabled'><input type='text' id='test"+ ID1 +"' value='"+ value1 +"' readonly='readonly'  style='width:206;background-color:#FF99CC'><input name='data[]' id='test"+ ID2 +"' type='hidden' value='"+ value2 +"' />";
										}
								}

								function createProd_no(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' maxlength='50' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' style=' width:161'>";
								}
								
								function createProd_name(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' maxlength='300' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' style='width:200'>";
								}

								function createProd_qty(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' maxlength='11' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' onKeyDown='return chkNumberInput(\"signfloat\");' style=' width:52'>";
								}
								function createProd_unit(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' maxlength='15' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' style=' width:61'>";
								}
								function createProd_price(id,value) {
								  return "<input type='text' id='test"+ id +"' name='data[]' maxlength='17' onChange='javascript:saveValue("+ id +",this.value)' value='"+ value +"' onKeyDown='return chkNumberInput(\"signfloat\");' style=' width:81'>";
								}
								
								function addInput2(type){
									arrInput.push(arrInput.length);
										if(type=='etc') arrInputValue.push("Etc");
										else arrInputValue.push("Detail");
									arrInput.push(arrInput.length);
									arrInputValue.push("");	
									arrInput.push(arrInput.length);
									arrInputValue.push("");	
									arrInput.push(arrInput.length);
									arrInputValue.push("");
									arrInput.push(arrInput.length);
									arrInputValue.push("");
									arrInput.push(arrInput.length);
									arrInputValue.push("");
									arrInput.push(arrInput.length);
									arrInputValue.push("");
									arrInput.push(arrInput.length);
									arrInputValue.push("");
									display();
								}
								
								function addInput(type) {
									if(arrInput.length>0){
										if(document.getElementById('test'+(arrInput.length-8)).value=='Etc' && 
										 document.getElementById('test'+(arrInput.length-7)).value==''){
													alert("��س����͡������ͧ�������´��¤��");
													document.getElementById('btn'+(arrInput.length-7)).focus();
										  }else if(document.getElementById('test'+(arrInput.length-4)).value==''){
													alert("��سҡ�͡�����Թ��Ҵ��¤��");
													document.getElementById('test'+(arrInput.length-4)).focus();
										  }else{
											  		addInput2(type);
										  }	 
									}else{
										  addInput2(type);
									}
								}
								
								function deleteInput() {
									  if (arrInput.length > 0) { 
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
										 arrInput.pop(); 
										 arrInputValue.pop();
									  }
									  display(); 
								}		
								
								function checknotnull(chk){
									if(chk <= 6){
										alert('�س�ѧ�����ӡ��������¡�ä��');
										document.getElementById("flagAction").value = "close";
										return false;
									}else{
										 if(document.getElementById('test'+(arrInput.length-8)).value=='Etc' && 
										 document.getElementById('test'+(arrInput.length-7)).value==''){
													alert("��س����͡������ͧ�������´��¤��");
													document.getElementById('btn'+(arrInput.length-7)).focus();
													return false;
										  }else if(document.getElementById('test'+(arrInput.length-4)).value==''){
													alert("��سҡ�͡�����Թ��Ҵ��¤��");
													document.getElementById('test'+(arrInput.length-4)).focus();
													return false;
										  }
									}
									return true;
								}	
						</script>
							
				</head>
				<body  leftmargin="0" topmargin="0"> 
						<center>
								<form name="form1" method="post" action="podet_addkey.php">
								  <input name="flagAction" id="flagAction"  type="hidden" >
											<center>
											  <table width="900"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
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
																					  <td width="120" class="tdleftwhite">�Ţ���� PO <span class="style_star">*</span></td>
																					  <td><input name="po_no" type="text" class="style_readonly" value="<?=$v_po_no; ?>"  readonly=""></td>
																					</tr>
																				  </table>																	
																	</td>
																</tr>
																<tr>
																  <td>
																				<table width="900"  border="1" cellspacing="0" cellpadding="0">
																				  <tr>
																					<td width="80" rowspan="2" class="tdcenterblack">�������Թ���</td>
																					<td width="248" rowspan="2" class="tdcenterblack">������ͧ��������</td>
																					<td width="162" rowspan="2" class="tdcenterblack">�����Թ���</td>
																					<td width="200" rowspan="2" class="tdcenterblack" >�����Թ���</td>
																					<td colspan="3" class="tdcenterblack" >��㹡����觫���</td>
																					<td width="16" rowspan="2" class="tdcenterblack" >&nbsp;</td>
																				  </tr>
																				  <tr>
																					<td width="50" class="tdcenterblack" >�ӹǹ</td>
																					<td width="60" class="tdcenterblack" >˹���</td>
																					<td width="80" class="tdcenterblack" >�Ҥҵ��˹���</td>
																				  </tr>
																		  </table>	  
																				<div id="maentabel" style="  height:350px; width:900; overflow:auto; z-index=2;display:block; font-size:11px; text-align:left" >
																								<p id="parah">
																								
																											�������� "���� ETC"  ���ͻ��� "���� Detail" <br>
																											����������ͧ����͡��¡���Թ��Ҥ��																								</p>																	
																				</div>																  </td>
																  </tr>
																<tr>
																  <td>
																				<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
																				<tr>
																					<th >
																							<input type="button" name="Submit" value="�ѹ�֡��¡�÷�����ŧ��� PO" onClick="if(checknotnull(document.form1.length)){ document.form1.flagAction.value='AddCode'; document.form1.submit(); }
																							else if(document.form1.flagAction.value=='close'){ document.form1.submit(); }">
																					</th>																						
																				    <th><div  align="right">
																							<input type="button" name="AddEtc" onClick="javascript:addInput('etc');" value="���� ETC">
																							<input type="button" name="AddDetail" onClick="javascript:addInput('detail');" value="���� Detail">
																							<input type="button" name="AddEtc" onClick="javascript:deleteInput()" value="ź��÷Ѵ��ҧ�ش">																					
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
											</center>	
								</form>
						</center>
				</body>
		</html>
		
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>








