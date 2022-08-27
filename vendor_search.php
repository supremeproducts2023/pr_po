<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
				require_once("../include_RedThemes/odbc_connect.php");				
				$choice_value= $_SESSION["choice_value"];	

				$vendor=@$_POST["vendor"];
				$status=@$_POST["status"];
				
				$strvendorQUE = "select * from vendor_group";
				if ($vendor != "")
						$strvendorQUE .= " where  vendor_no like '%$vendor%'  or  vendor_name like '%$vendor%'";
						
				if($vendor != "" && $status  != "")
						$strvendorQUE .= " and vendor_status = '$status' ";
				elseif($vendor == "" && $status  != "")
						$strvendorQUE .= " where vendor_status = '$status' ";
						
				$strvendorQUE .= " order by  vendor_no";		

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
<form name="sup_search" method="post" action="vendor_search.php">
  <table width="600"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
    <tr>
      <th width="670">&nbsp;&nbsp;ค้นหา</th>
    </tr>
    <tr >
      <td>
        <table width="100%" border="0" align="center">
          <tr>
            <td>
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr height="28px">
                  <td width="175"><div align="right">                &nbsp;&nbsp;&nbsp;
                    </div></td>
                  <td width="166">รหัส / ชื่อ vendor Group</td>
                  <td width="403">
				  <input name="vendor" type="text"  value="<? echo @$vendor; ?>">					</td>
                </tr>
                 <tr height="28px">
                  <td width="175"><div align="right">                &nbsp;&nbsp;&nbsp;
                    </div></td>
                  <td width="166">สถานะ</td>
                  <td width="403">
				  <select name="status" id="status" style="width:100px;">
                  	<option value="" <? if($status == "") echo "selected";?>>ทั้งหมด</option>
                    <option value="1" <? if($status == "1") echo "selected";?>>ใช้งาน</option>
                    <option value="0" <? if($status == "0") echo "selected";?>>เลิกใช้งาน</option>
                  </select>
                  </td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td>
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <th colspan="3"><div align="right"> 
				  	<a 
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
<table width="593"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
  <tr>
    <th width="850">&nbsp;&nbsp;ผลการค้นหา</th>
  </tr>
  <tr >
    <td>
      <table width="91%" border="0" align="center">
        <tr>
          <td>
            <table width="598"  border="1" cellspacing="0" cellpadding="0" >
              <tr>
			  <? if($choice_value == "vendorEdit"){ ?>
			  	<td width="30" class="tdcenterblack">Edit</td>
			  <? }else if($choice_value == "vendorDel"){ ?>
			  	<td width="30" class="tdcenterblack">Del</td>
				<? } ?>
                <td width="50" align="center"   class="tdcenterblack">ลำดับ</td>
                <td width="130" align="center"  class="tdcenterblack">เลขที่ vendor Group </td>
                <td width="340"   class="tdcenterblack"><p>ชื่อ vendor Group </p></td>
                <td width="100"   class="tdcenterblack"><p>สถานะ </p></td>
                </tr>
 <?
				$curvendorQUE= @odbc_exec($conn,@$strvendorQUE);	
				$i = 1;
  				while(@odbc_fetch_row($curvendorQUE)){
						$vendor_id = odbc_result($curvendorQUE, "vendor_id");
						$vendor_no = odbc_result($curvendorQUE, "vendor_no");
						$vendor_name = odbc_result($curvendorQUE, "vendor_name");
						$vendor_status = odbc_result($curvendorQUE, "vendor_status");
?>

              <tr>
												  	
								<? 		 	if($choice_value == 'vendorEdit') {	?>
														<td><div align="center">
														<a href="./vendorGroup_edit.php?v_no=<? echo $vendor_no; ?>"><img src="../include/images/edit_icon.png" border="0"></a>
														</div></td>	
								<? 		  	}else if($choice_value == 'vendorDel') { ?>
														<td>
                                                        <?
                                                        	if($vendor_status=="1")
															{
														?>
                                                        <div align="center">
														<a onClick="remote_del('vendorGroup_del.php?v_id=<? echo $vendor_id; ?>');"   style="cursor:hand"><img src="../include/images/del_icon.png" border="0"></a>
														</div>
                                                        <? }?></td>	
								<? 			
												} 
								?> 				
				<td  style="text-align:center"><?=$i;?></td>
                <td  style="text-align:center"><?=$vendor_no;?></td>
                <td >&nbsp;<?=$vendor_name;?></td>
                <td   style="text-align:center"><? if($vendor_status == "1") echo "ใช้งาน"; else echo "เลิกใช้งาน";?></td>
                </tr>

 <?
 					$i++;
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

