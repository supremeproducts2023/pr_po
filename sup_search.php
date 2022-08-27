<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
				require_once("../include_RedThemes/odbc_connect.php");				
				$choice_value= $_SESSION["choice_value"];	

				//========== เรื่องการ Search แล้วค้าง keyword ==================
				$flagSearch = @$_POST["flagSearch"];
				if(@$flagSearch == 'PushSearch'){
					$strCompany_name=@$_POST["strCompany_name"];
					$_SESSION["ses_Search"] = "sup|-|".$strCompany_name;	
					$strSupplierQUE = "select  supplier_id,company_name,supplier_address1,supplier_title,
															supplier_address2,supplier_address3,supplier_address3_1,
															tambol,district,province,postcode,supplier_payment,fax_number,c.country 
													from supplier s,cushos_country c
													where s.country = c.id (+) ";
					if(@$strCompany_name != '') $strSupplierQUE .= " and upper(company_name) like upper('%$strCompany_name%')  ";			
					$strSupplierQUE .= "order by  company_name";
				}else{
						$ses_Search = $_SESSION["ses_Search"];
						$arr_Search=explode("|-|",$ses_Search);				
						$flagSearch=trim($arr_Search[0]);
						if($flagSearch=="sup"){
								$strCompany_name=trim($arr_Search[1]);						
						}
				}				
				//================================================
	include "../include_RedThemes/wait.php";
	flush();
?>
<html>
<head>
		<title>**Search ข้อมูล Supplier **</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/button_add.js'></script>						
		<script language='javascript' src='../include/button_del.js'></script>				
</head>
<body  topmargin="0" leftmargin="0">
<br>
<center>
<form name="sup_search" method="post" action="sup_search.php">
<input name="flagSearch" type="hidden" value="PushSearch">
  <table width="880"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
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
                  <td width="175" class="tdleftwhite"><div align="right">                &nbsp;&nbsp;&nbsp;
                    </div></td>
                  <td width="166" class="tdleftwhite">ชื่อ Supplier</td>
                  <td width="403">
				  <input name="strCompany_name" type="text" onKeyDown="if(event.keyCode==13) document.sup_search.submit();" value="<? echo @$strCompany_name; ?>">					</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <th colspan="3"><div align="right"> 
				  <? if($choice_value == 'suprep') {?> <input type="button" name="Button" height="24" value="ออกรายงานเป็น Excel" style="cursor:hand;" onClick="window.open('supplier_report.php?keyword='+document.sup_search.strCompany_name.value);"><? } ?><a 
						onMousedown="document.images['butsearch'].src=search3.src"   style="cursor:hand"
						onMouseup="document.images['butsearch'].src=search1.src"						
						 onMouseOver="document.images['butsearch'].src=search2.src" 
						 onMouseOut="document.images['butsearch'].src=search1.src" target="_blank"
						 onClick="document.sup_search.submit();">
                    <img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0"> </a> </div></th>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
<table width="880"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
  <tr>
    <th width="850">&nbsp;&nbsp;ผลการค้นหา</th>
  </tr>
  <tr >
    <td>
      <table width="100%" border="0" align="center">
        <tr>
          <td>
            <table width="880"  border="1" cellspacing="0" cellpadding="0" >
              <tr>
			  <? if($choice_value == "supup"){ ?>
			  	<td width="30" class="tdcenterblack">Edit</td>
			  <? }else if($choice_value == "supdel"){ ?>
			  	<td width="30" class="tdcenterblack">Del</td>
				<? } ?>
                <td width="100" align="center"   class="tdcenterblack">จดทะเบียน</td>
                <td width="250" align="center"   class="tdcenterblack">ชื่อ Supplier </td>
                <td width="340"   class="tdcenterblack"><p>ที่อยู่ Supplier</p></td>
                <td width="160"  class="tdcenterblack"><p class="tdcenterblack">เงื่อนไขการชำระเงิน</p></td>
                </tr>
 <?
				$curSupplierQUE= @odbc_exec($conn,@$strSupplierQUE);	
  				while(@odbc_fetch_row($curSupplierQUE)){
						$supplier_id = odbc_result($curSupplierQUE, "supplier_id");
						$company_name = odbc_result($curSupplierQUE, "company_name");
						$supplier_address1 = odbc_result($curSupplierQUE, "supplier_address1");
						$supplier_address2 = odbc_result($curSupplierQUE, "supplier_address2");
						$supplier_address3 = odbc_result($curSupplierQUE, "supplier_address3");
						$supplier_payment = odbc_result($curSupplierQUE, "supplier_payment");
						$supplier_title =  odbc_result($curSupplierQUE, "supplier_title");
						$supplier_address3_1 = odbc_result($curSupplierQUE, "supplier_address3_1");
						$tambol = odbc_result($curSupplierQUE, "tambol");
						$district = odbc_result($curSupplierQUE, "district");
						$province = odbc_result($curSupplierQUE, "province");
						$fax_number = odbc_result($curSupplierQUE, "fax_number");
						$postcode = odbc_result($curSupplierQUE, "postcode");
						$country = odbc_result($curSupplierQUE,"country");
						$supplier_address="";
						if($supplier_address1!=""&&$supplier_address2!="")
							$supplier_address1 = $supplier_address1."<br>".$supplier_address2;						
						else if($supplier_address1!=""&&$supplier_address2=="")	
							$supplier_address1 = $supplier_address1;						
						else	if($supplier_address1==""&&$supplier_address2=="")	
							$supplier_address1 = "";
						else	if($supplier_address1==""&&$supplier_address2!="")		
							$supplier_address1 = $supplier_address2;						
							
						if($supplier_address1!="")
							$supplier_address .= $supplier_address1;
							
						$supplier_address2 = "";
						if($province=="กรุงเทพฯ")
						{
							if($tambol!="")
								$supplier_address2 .= " แขวง".$tambol;
							if($district!="")
								$supplier_address2 .= " เขต".$district;
						}else{
							if($tambol!="")
								$supplier_address2 .= " ตำบล".$tambol;
							if($district!="")
								$supplier_address2 .= " อำเภอ".$district;
						}	
						if($province!="")
							$supplier_address2 .= " จังหวัด".$province;					
						if($postcode!="")
							$supplier_address2 .= " รหัสไปรษณีย์ ".$postcode;					
						if($country!="")
							$supplier_address2 .= " ".$country;
						
						if($supplier_address2!="")
						{
							if($supplier_address!="")
								$supplier_address .=  "<br>".$supplier_address2;
							else $supplier_address = $supplier_address2;
						}
								
						if($supplier_address3!="")
							$supplier_address3 = "เบอร์โทรศัพท์ : ".$supplier_address3;
						if($supplier_address3_1!="")
						{	
							if($supplier_address3 != "")
								$supplier_address3 .= ", ".$supplier_address3_1;	
							else	$supplier_address3 = "เบอร์โทรศัพท์ : ".$supplier_address3_1;
						}
						if($fax_number!="")
						{
							if($supplier_address3 != "")
								$supplier_address3 .= "<br>เบอร์แฟกซ์ : ".$fax_number;	
							else	$supplier_address3 = "<br>"."เบอร์แฟกซ์ : ".$supplier_address3_1;
						}
						if($supplier_address3!="")
						{
							if($supplier_address!="")
								$supplier_address .=  "<br>".$supplier_address3;
							else $supplier_address = $supplier_address3;
						}
?>

              <tr>
												  	
								<? 		 	if($choice_value == 'supup') {	?>
														<td><div align="center">
														<a href="./supplier_edit.php?supplier_id=<? echo $supplier_id; ?>&flag=edit"  title="ดูรายละเอียด"><img src="../include/images/edit_icon.png" border="0"></a>
														</div></td>	
								<? 		  	}else if($choice_value == 'supdel') { ?>
														<td><div align="center">
														<a onClick="remote_del('supplier_del.php?supplier_id=<? echo $supplier_id; ?>&flag=del');"   style="cursor:hand"><img src="../include/images/del_icon.png" border="0"></a>
														</div></td>	
								<? 			
												} 
								?> 				
							
                <td  valign="top" style="text-align:center"><? if($supplier_title=="")echo '&nbsp;'; else echo $supplier_title;?></td>
                <td  valign="top">&nbsp;<? echo $company_name;?></td>
                <td  valign="top"><? if($supplier_address=="")echo '&nbsp;'; else echo  $supplier_address; ?>
				 </td>
                <td valign="top">&nbsp;<? echo "&nbsp;".$supplier_payment;?></td>
                </tr>

 <?
					}
?>

          </table>
		  </td>
        </tr>
        <tr>
          <td>
            <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <th>&nbsp;</th>
              </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>
			<script language="JavaScript" type="text/JavaScript">
							document.sup_search.strCompany_name.select();
			</script>
</center>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>

