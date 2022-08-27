<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
				require_once("../include_RedThemes/odbc_connect.php");				
				$empno_user = $_SESSION["empno_user"];
		//============= Start-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				$flagAction = @$_POST["flagAction"];
				if($flagAction != ""){
						$pr_no=$_POST["pr_no"];
						$code=$_POST["code"];
						$prod_no=$_POST["prod_no"];
						$prod_name=$_POST["prod_name"];
						$prod_qty=$_POST["prod_qty"];
						$prod_unit=$_POST["prod_unit"];
						$prod_price=$_POST["prod_price"];
						$discount_percent=$_POST["discount_percent"];
						$discount_baht=$_POST["discount_baht"];		
						
						if($discount_percent=="")$discount_percent=0;
						if($discount_baht=="")$discount_baht=0;
						
						if($flagAction=="AddCode"){
								$str_mx = "select ISNULL(max(id)+1,1) id  from pr_details where pr_no='$pr_no'";			
								$cur_mx = @odbc_exec($conn,$str_mx);
								$id = @odbc_result($cur_mx, "id");
				
								$txt_add = "insert into pr_details (
															id,pr_no,code,prod_no,prod_name,prod_qty,
															prod_unit,prod_price,discount_percent,discount_baht,
															rec_user,rec_date
													) values(
															'$id','$pr_no','$code','$prod_no','$prod_name','$prod_qty',
															'$prod_unit','$prod_price','$discount_percent','$discount_baht',
															'$empno_user',sysdate
													)";
						
								$exe_add = odbc_exec($conn,$txt_add);
								$exe_commit = odbc_exec($conn,"commit");
								//echo $txt_add;
						}else{  //if($flagAction=="UpCode"){
								$id=@$_POST["id"];
								$txt_up = "update pr_details set 
															prod_no='$prod_no',
															code='$code',
															prod_name='$prod_name',
															prod_qty='$prod_qty',
															prod_unit='$prod_unit',
															prod_price='$prod_price',
															discount_percent='$discount_percent',
															discount_baht='$discount_baht',
															last_user='$empno_user',
															last_date=sysdate 
													where id='$id' 
													and pr_no='$pr_no'";
						
								$exe_up = odbc_exec($conn,$txt_up);
								$exe_commit = odbc_exec($conn,"commit");
						}		
						$txt_up = "update pr_master set pr_status='1',mng_remark='' where pr_no='$pr_no'";
						$exe_up = odbc_exec($conn,$txt_up);
						$exe_commit = odbc_exec($conn,"commit");
				
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'window.opener.location.reload("./prmas_edit.php");';
						echo 'window.close();';
						echo '</script>';	
				}
		//============= End-��ǹ��÷ӧҹ����ͼ�ҹ��á����� SUBMIT �˹�ҡ�÷ӧҹ��� (code) ===============	
				
				$pr_no = $_SESSION["sespk_no"]; 
				$flag = @$_GET["flag"];
				$vat_include=@$_GET["vat_include"];
				
				if($flag=='edit'){
						$id=@$_GET["id"];
												
						$str_pr_details = "select  * from pr_details  where pr_no = '$pr_no' and id= '$id'";
						$cur_pr_details = odbc_exec($conn,$str_pr_details);

						$pr_no = @odbc_result($cur_pr_details, "pr_no");
						$code = @odbc_result($cur_pr_details, "code");
						$prod_no = @odbc_result($cur_pr_details, "prod_no");
						$prod_name = @odbc_result($cur_pr_details, "prod_name");
						$prod_qty = @odbc_result($cur_pr_details, "prod_qty");
						$prod_unit = @odbc_result($cur_pr_details, "prod_unit");
						$prod_price = @odbc_result($cur_pr_details, "prod_price");
						$discount_percent = @odbc_result($cur_pr_details, "discount_percent");
						$discount_baht = @odbc_result($cur_pr_details, "discount_baht");
						$total_price = ($prod_qty * $prod_price)-$discount_baht;
						$flagAction = 'UpCode';
						
												
				}else{
						$flagAction = 'AddCode';
				}
?>

<html>
<head>
<title>*** ������¡���Թ��� ***</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script language='javascript' src='./include/radio_check.js'></script>							
			<!--	
		<script language='javascript' src='../include/lov_frame_product_ml.js'></script>	
		<script language='javascript' src='../include/lov_frame_code_account.js'></script>	
-->
		<!-- Check Not null -->
		<script language='javascript'>			
				function check_prdet(obj){
							if(obj.prod_no.value==""){  	
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.prod_no.focus();
								return false;
							}			
							if(obj.prod_qty.value==""){  	
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.prod_qty.focus();
								return false;
							}			
							if(obj.prod_price.value==""){  	
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.prod_price.focus();
								return false;
							}			
							obj.submit();
				}
		</script>	
		<!-- Calculate -->
		<script language='javascript'>
				// a=�ӹǹ, b=�Ҥ�, c=��ǹŴ%, d=��ǹŴ(�Թ), e=�Ҥ����
				function cal_prdet_price(a,b,c,d,e,objname){	
						if((a.value=="")||(b.value=="")){
								e.value = '';
								d.value = '';
								c.value = '';
						}else if((objname=='a') || (objname=='b')){
										if(d.value==""){
											e.value= round(a.value*b.value,2);
										}else{
											e.value= round(a.value*b.value - d.value,2);
										}
						}else if(objname=='c'){
								if(c.value==""){
										d.value = '';
										e.value =  round(a.value * b.value,2);
								}else{
				/////////////////
									var flo_calculate1 = (a.value * b.value);
									var flo_calculate2 = 0;
									var vars = c.value.split("%");
									for (var i=0;i<vars.length;i++) {
											flo_calculate2 = flo_calculate2 + (flo_calculate1 * vars[i] /100);
											flo_calculate1 = flo_calculate1 - flo_calculate2;
									} 
									d.value = round(flo_calculate2,2);
									e.value = round((a.value * b.value) - d.value,2);
				/////////////////
								}
						}else if(objname=='d'){
								if(d.value==""){
										c.value = '';
										e.value =  round(a.value * b.value,2);
								}else{
										c.value = round((100 * d.value)/(a.value*b.value),2);
										e.value = round((a.value * b.value) - d.value,2);
								}
						}
				}
				
				function round(number,X) {
					X = (!X ? 2 : X);
					return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
				}

				function fn_FillorChoice(obj,click){
					if(click=='yes'){
							if(obj.FillorChoice.checked == true){
								obj.code.value='';
			
								obj.prod_no.readOnly = false;
								obj.prod_no.style.background = "#ffffff";
								obj.prod_no.style.color = "#000000";
								obj.prod_name.readOnly = false;
								obj.prod_name.style.background = "#ffffff";
								obj.prod_name.style.color = "#000000";
								obj.prod_unit.readOnly = false;
								obj.prod_unit.style.background = "#ffffff";
								obj.prod_unit.style.color = "#000000";
								obj.prod_no.focus();
							}else{
								obj.code.value='';
								obj.prod_no.value='';
								obj.prod_name.value='';
			
								obj.prod_no.readOnly = true;
								obj.prod_no.style.background = "#CFE4FE";
								obj.prod_no.style.color = "#666666";
								obj.prod_name.readOnly = true;
								obj.prod_name.style.background = "#CFE4FE";
								obj.prod_name.style.color = "#666666";
								obj.prod_unit.readOnly = true;
								obj.prod_unit.style.background = "#CFE4FE";
								obj.prod_unit.style.color = "#666666";								
							}
					}else{
							if(obj.FillorChoice.checked == true){
								obj.prod_no.readOnly = false;
								obj.prod_no.style.background = "#ffffff";
								obj.prod_no.style.color = "#000000";
								obj.prod_name.readOnly = false;
								obj.prod_name.style.background = "#ffffff";
								obj.prod_name.style.color = "#000000";
								obj.prod_unit.readOnly = false;
								obj.prod_unit.style.background = "#ffffff";
								obj.prod_unit.style.color = "#000000";
								
								obj.prod_no.focus();
							}else{
								obj.prod_no.readOnly = true;
								obj.prod_no.style.background = "#CFE4FE";
								obj.prod_no.style.color = "#666666";
								obj.prod_name.readOnly = true;
								obj.prod_name.style.background = "#CFE4FE";
								obj.prod_name.style.color = "#666666";
								obj.prod_unit.readOnly = true;
								obj.prod_unit.style.background = "#CFE4FE";
								obj.prod_unit.style.color = "#666666";
							}
					}
				}				
		</script>			
		
		<!-- LOV : Product	 -->			
		<script type="text/javascript" language="javascript">
			function openProduct(type_use){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_product.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovProduct(){
				returnvalue = openProduct('ML'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("code").value = values[0];  }else{ document.getElementById("code").value =''; }
					if(values[1]!= ""){ document.getElementById("prod_no").value = values[1];  }else{ document.getElementById("prod_no").value =''; }					
					if(values[2]!= ""){ document.getElementById("prod_name").value = values[2];  }else{ document.getElementById("prod_name").value =''; }					
					if(values[3]!= ""){ document.getElementById("prod_unit").value = values[3];  }else{ document.getElementById("prod_unit").value =''; }	

					document.getElementById("FillorChoice").checked = false;			
						
					document.getElementById("prod_no").readOnly = true;			
					document.getElementById("prod_no").style.background = "#CFE4FE";		
					document.getElementById("prod_no").style.color = "#666666";	
						
					document.getElementById("prod_name").readOnly = true;			
					document.getElementById("prod_name").style.background = "#CFE4FE";		
					document.getElementById("prod_name").style.color = "#666666";						

					document.getElementById("prod_unit").readOnly = true;			
					document.getElementById("prod_unit").style.background = "#CFE4FE";		
					document.getElementById("prod_unit").style.color = "#666666";						
				 }
			}       
		</script>
		<!-- LOV : CodeAccount -->			
		<script type="text/javascript" language="javascript">
			function openCodeAccount(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_code_account.php?themes=DefaultBlue','newWin','dialogWidth:780px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovCodeAccount(){
				returnvalue = openCodeAccount(); 
				if (returnvalue != null){ 
						var values =  returnvalue.split("|-|");						
						// 0=acc_id,1=acc_name
						document.getElementById("prod_no").value =values[0];
						document.getElementById("prod_name").value =values[1];

						document.getElementById("FillorChoice").checked = true;
						document.getElementById("code").value='';
						
						document.getElementById("prod_no").readOnly = false;
						document.getElementById("prod_no").style.background = "#ffffff";
						document.getElementById("prod_no").style.color = "#000000";
						
						document.getElementById("prod_name").readOnly = false;
						document.getElementById("prod_name").style.background = "#ffffff";
						document.getElementById("prod_name").style.color = "#000000";							

						document.getElementById("prod_unit").readOnly = false;
						document.getElementById("prod_unit").style.background = "#ffffff";
						document.getElementById("prod_unit").style.color = "#000000";							
				 }
			}       
		</script>
		
</head>

<body topmargin="0" leftmargin="0">
<div align="center">  
	  <form name="form_prdet" action="prdet_add.php" method="post">
	  <input name="flagAction" type="hidden" value="<?= $flagAction; ?>">
	  <input name="id" type="hidden" value="<? echo $id; ?>">
	  <input name="pr_no" type="hidden" value="<? echo $pr_no; ?>">
        <table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
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
                  <td width="120" class="tdleftwhite">&nbsp;�Ţ���� PR  <span class="style_star">*</span></td>
                  <td width="439"><input name="pr_no" type="text" class="style_readonly" value="<? echo $pr_no; ?>"  readonly=""></td>
                </tr>
              </table>		</td>
		</tr>			  
		<tr><td>
              <table width="100%"  border="1" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="120"  class="tdleftwhite">&nbsp;�����Թ��� <span class="style_star">*</span> </td>
                  <td>
				  <input name="prod_no" id="prod_no" type="text"  value="<? echo @$prod_no; ?>" size="30" maxlength="20" onKeyUp="return check_string(document.form_prdet.prod_no,50);"><input name="product_but" type="button" value="�Թ���(ML)" onClick="lovProduct();"><input name="acc_but" type="button" value="���ʺѭ��" onClick="lovCodeAccount()"><input name="FillorChoice" type="checkbox" id="FillorChoice" onClick="fn_FillorChoice(document.form_prdet,'yes');" <? if(@$code=="")echo "checked"; ?>>��͡�Թ����ͧ
				  <br>
				  <span class="style_text">�ó�����Һ�����Թ��� ��س��ͺ������Ἱ� MS Local �µç	    </span>
				  <input name="code" type="hidden" id="code"   value="<? echo @$code; ?>" size="8" maxlength="50">
				  </td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;�����Թ���</td>
                  <td><input name="prod_name" id="prod_name" type="text"  value="<? echo @$prod_name; ?>" size="60" maxlength="300"  onKeyUp="return check_string(document.form_prdet.prod_name,300);">				  
				  </td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;�ӹǹ <span class="style_star">* </span></td>
                  <td><input name="prod_qty" type="text"  onKeyDown="return check_number();" value="<? echo @$prod_qty; ?>" size="10" maxlength="8" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'a');"></td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;˹���</td>
                  <td><input name="prod_unit" type="text" value="<? echo @$prod_unit; ?>" size="20"  maxlength="15" onKeyUp="return check_string(document.form_prdet.prod_unit,15);"> 
				<script language="JavaScript" type="text/JavaScript">
						fn_FillorChoice(document.form_prdet,"no");
				</script>
				  
				  </td>				  
                </tr>
                
                <tr>
                  <td  class="tdleftwhite">&nbsp;�Ҥҵ��˹��� <span class="style_star">*</span></td>
                  <td><input name="prod_price" type="text"  onKeyDown="return check_number();" value="<? echo @$prod_price; ?>" size="20" maxlength="15" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'b');">
				  <?
				  		if($vat_include=="1")echo '<span class="style_text">(�Ҥ���� VAT����)</span>'; else if($vat_include=="0")echo '<span class="style_text">(�Ҥ��ѧ������ VAT)</span>';
				  ?>
				  </td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;��ǹŴ</td>
                  <td class="tdleftwhite"><input name="discount_percent" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_percent; ?>" size="5" maxlength="5" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'c');"> 
                    % �Դ���Թ 
                    <input name="discount_baht" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_baht; ?>" size="16" maxlength="15" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'d');"> 
                    �ҷ                    </td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;�Ҥ���ѧ�ѡ��ǹŴ</td>
                  <td><input name="total_price" type="text"  value="<? echo @$total_price; ?>" size="20" class="style_readonly"  readonly=""></td>
                </tr>
              </table>
	</td></tr>			  
	<tr><td>				
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
                        <th ><div align="right">                        
                          <a onClick="return check_prdet(document.form_prdet);" style="cursor:hand"
						onMouseDown="document.images['butsave'].src=save3.src" 
						onMouseUp="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src"> 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 <a  onClick="document.form_prdet.reset();" style="cursor:hand"
						 onMousedown="document.images['butcancel'].src=cancel3.src" 
						onMouseup="document.images['butcancel'].src=cancel1.src"						
						 onMouseOver="document.images['butcancel'].src=cancel2.src" 
						 onMouseOut="document.images['butcancel'].src=cancel1.src">						  
						 <img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >						 </a>
                        </div>						</th>
                      </tr>		
              </table>
  
          </td>
        </tr>
      </table>	  </td>
    </tr>
  </table>
	    
	  </form>
  
</div>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
