<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
				require_once("../include_RedThemes/odbc_connect.php");				
				$empno_user = $_SESSION["empno_user"];
				
		//============= Start-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
				$flagAction = @$_POST["flagAction"];
				if($flagAction != ""){
						$pr_no=$_POST["pr_no"];
						$prod_no=$_POST["prod_no"];
						$prod_noshow=$_POST["prod_noshow"];
						$prod_name=$_POST["prod_name"];
						$prod_qty=$_POST["prod_qty"];
						$prod_unit=$_POST["prod_unit"];
						$prod_price=$_POST["prod_price"];
						$discount_percent=$_POST["discount_percent"];
						$discount_baht=$_POST["discount_baht"];		
						
						if($discount_percent=="")$discount_percent=0;
						if($discount_baht=="")$discount_baht=0;
						
						if($flagAction=="AddCode"){
								$str_mx = "select nvl(max(id)+1,1) id  from pr_details where pr_no='$pr_no'";			
								$cur_mx = @odbc_exec($conn,$str_mx);
								$id = @odbc_result($cur_mx, "id");
								
								$txt_add = "insert into pr_details (
														id,pr_no,code,prod_no,prod_name,prod_qty,
														prod_unit,prod_price,discount_percent,discount_baht,
														rec_user,rec_date
													) values(
														'$id','$pr_no','$prod_no','$prod_noshow','$prod_name','$prod_qty',
														'$prod_unit','$prod_price','$discount_percent','$discount_baht',
														'$empno_user',sysdate
													)";
								$exe_add = odbc_exec($conn,$txt_add);
								$exe_commit = odbc_exec($conn,"commit");
						}else{		//if($flagAction=="UpCode"){
							$id=@$_POST["id"];
				
								
								$txt_up = "update pr_details set 
												code='$prod_no',
												prod_no='$prod_noshow',
												prod_name='$prod_name',
												prod_qty='$prod_qty',
												prod_unit='$prod_unit',
												prod_price='$prod_price',
												discount_percent='$discount_percent',
												discount_baht='$discount_baht',
												last_user='$empno_user',
												last_date=sysdate 
												where id='$id' and pr_no='$pr_no'";
								$exe_up = odbc_exec($conn,$txt_up);
								$exe_commit = odbc_exec($conn,"commit");							
						}		
						$txt_up = "update pr_master set pr_status='1',mng_remark='' where pr_no='$pr_no'";
						$exe_up = odbc_exec($conn,$txt_up);
						$exe_commit = odbc_exec($conn,"commit");
				
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo '		window.opener.location.reload("./prmas_edit.php");';
						echo '		window.close();';
						echo '</script>';
				}
		//============= End-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
				
				$pr_no = $_SESSION["sespk_no"]; 
				$flag = @$_GET["flag"];
				$vat_include=@$_GET["vat_include"];

				if($flag=='edit'){
						$id=@$_GET["id"];
												
						$str_pr_details = "select  * from pr_details where  pr_no = '$pr_no' and id= '$id'";
						$cur_pr_details = odbc_exec($conn,$str_pr_details);

						$pr_no = @odbc_result($cur_pr_details, "pr_no");
						$prod_no = @odbc_result($cur_pr_details, "code");
						$prod_noshow = @odbc_result($cur_pr_details, "prod_no");
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
<title>*** เพิ่มรายการสินค้า ***</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		

		<!-- Check Not null -->
		<script language='javascript'>			
				function check_prdet(obj){
							if(obj.prod_no.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.prod_no.focus();
								return false;
							}			
							if(obj.prod_qty.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.prod_qty.focus();
								return false;
							}			
							if(obj.prod_unit.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.prod_unit.focus();
								return false;
							}			
							if(obj.prod_price.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.prod_price.focus();
								return false;
							}			
							obj.submit();
				}
		</script>	

		<!-- Calculate -->
		<script language='javascript'>
				// a=จำนวน, b=ราคา, c=ส่วนลด%, d=ส่วนลด(เงิน), e=ราคารวม
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
		</script>			

		<!-- LOV : BOM	 -->								
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
		
</head>

<body topmargin="0" leftmargin="0">
<div align="center">  
	  <form name="form_prdet" action="prdet_addbom.php" method="post">
	  <input name="flagAction" type="hidden" value="<?= $flagAction; ?>">
	  <input name="id" type="hidden" value="<? echo $id; ?>">
	  <input name="pr_no" type="hidden" value="<? echo $pr_no; ?>">
        <table width="550"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
    <tr>
      <th width="550">&nbsp;&nbsp;รายการสินค้า</th>
    </tr>
    <tr>
      <td> 
	  <table width="100%" border="0" align="center">
        <tr>
          <td>
           
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="120" class="tdleftwhite">&nbsp;เลขที่ใบ PR  <span class="style_star">*</span></td>
                  <td width="439"><input name="pr_no" type="text" class="style_readonly" value="<? echo $pr_no; ?>"  readonly=""></td>
                </tr>
              </table>
		</td>
		</tr>			  
		<tr><td>
              <table width="100%"  border="1" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="120"  class="tdleftwhite">&nbsp;รหัสสินค้า <span class="style_star">*</span> </td>
                  <td width="433">
				  <input name="prod_noshow" type="text"  value="<? echo @$prod_noshow; ?>" size="20" maxlength="50" readonly="" class="style_readonly"><input name="FG" type="button" id="FG"  onClick="lovBOM('1,2');"  value="สินค้า(BOM)">
				  <input name="prod_no" type="hidden"  value="<? echo @$prod_no; ?>">
				  <br><span class="style_text">กรณีไม่ทราบรหัสสินค้า กรุณาสอบถามที่แผนก MS Local โดยตรง	    </span>
</td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;ชื่อสินค้า</td>
                  <td><input name="prod_name" type="text"  value="<? echo @$prod_name; ?>" size="60" maxlength="300"  onKeyUp="return check_string(document.form_prdet.prod_name,300);" readonly="" class="style_readonly"></td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;จำนวน <span class="style_star">* </span></td>
                  <td><input name="prod_qty" type="text"  onKeyDown="return check_number();" value="<? echo @$prod_qty; ?>" size="10" maxlength="8" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'a');"></td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;หน่วย  <span class="style_star">*</span></td>
                  <td><input name="prod_unit" type="text" value="<? echo @$prod_unit; ?>" size="20"  maxlength="15" readonly="" class="style_readonly"> </td>
                </tr>
                
                <tr>
                  <td  class="tdleftwhite">&nbsp;ราคาต่อหน่วย <span class="style_star">*</span></td>
                  <td><input name="prod_price" type="text"  onKeyDown="return check_number();" value="<? echo @$prod_price; ?>" size="20" maxlength="15" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'b');">
				  <?
				  		if($vat_include=="1")echo '<span class="style_text">(ราคารวม VATแล้ว)</span>'; else if($vat_include=="0")echo '<span class="style_text">(ราคายังไม่รวม VAT)</span>';
				  ?></td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;ส่วนลด</td>
                  <td class="tdleftwhite"><input name="discount_percent" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_percent; ?>" size="5" maxlength="5" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'c');"> 
                    % คิดเป็นเงิน 
                    <input name="discount_baht" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_baht; ?>" size="16" maxlength="15" onKeyUp="return cal_prdet_price(document.form_prdet.prod_qty,document.form_prdet.prod_price,document.form_prdet.discount_percent,document.form_prdet.discount_baht,document.form_prdet.total_price,'d');"> 
                    บาท 
                    </td>
                </tr>
                <tr>
                  <td  class="tdleftwhite">&nbsp;ราคาหลังหักส่วนลด</td>
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
						 <img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
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
  
</div>
		<script language="JavaScript" type="text/JavaScript">
				document.form_prdet.FG.focus();
		</script>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
