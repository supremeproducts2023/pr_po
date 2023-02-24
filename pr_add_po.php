<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
				require_once("../include_RedThemes/odbc_connect.php");				
?>
<html>
<head>
<title>*** เลือกเลขที่ PO ***</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
</head>
<body topmargin="0" leftmargin="0">
<?
	include "../include_RedThemes/wait.php";
	flush();
				$flag=@$_GET["flag"];

				if($flag=='open'){
						$_SESSION["sespk_no"]=@$_GET["pr_no"];
						$dateKeySearch=date("d-m-Y");
				}else $dateKeySearch =@ $_GET["dateKeySearch"];

				$pr_no = $_SESSION["sespk_no"]; 
				
				if($flag=='add'){
						$po_no=@$_GET["po_no"];
						$strInsert = "insert into pr_and_po (po_no,pr_no) values('$po_no','$pr_no')";
						$exeInsert = odbc_exec($conn,$strInsert);
						$exeCommit = odbc_exec($conn,"commit");
				}else if($flag=='del'){
						$po_no=@$_GET["po_no"];
						$strDelete = "delete from pr_and_po where po_no='$po_no' and pr_no='$pr_no'";
						$exeDelete = odbc_exec($conn,$strDelete);
						$exeCommit = odbc_exec($conn,"commit");
				}

				$strPoHave = "select  pr.po_no,format(po_date,'DD-MM-YYYY') po_date,company_name 
											from pr_and_po pr,po_master po
											left join supplier s on  po.supplier_id=s.supplier_id
											where pr.po_no=po.po_no 
											and pr.pr_no ='$pr_no'
											order by pr.po_no";
				$curPoHave = odbc_exec($conn, $strPoHave);
				$strPoHaveList = "";
  				while(odbc_fetch_row($curPoHave)){
						$po_no=odbc_result($curPoHave, "po_no");
						if($strPoHaveList=="")$strPoHaveList="'".$po_no."'";
						else $strPoHaveList=$strPoHaveList.",'".$po_no."'";
				}
				$curPoHave = odbc_exec($conn, $strPoHave);
				
						$strPoChoice = "select  po_no,format(po_date,'DD-MM-YYYY') po_date,company_name 
														from po_master p
														left join supplier s on p.supplier_id=s.supplier_id
														where 1 = 1 ";
				
						if($dateKeySearch!="") $strPoChoice .= "and po_date='$dateKeySearch' ";
						if($strPoHaveList!="") $strPoChoice .= "and po_no not in(".$strPoHaveList.") ";
				
						$strPoChoice .= "order by po_no";
				
				$curPoChoice = odbc_exec($conn, $strPoChoice);
?>
<div align="center">  
        <table width="480"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
    <tr>
      <th width="550">&nbsp;&nbsp;รายการ PO ที่เลือกแล้ว</th>
    </tr>
    <tr>
      <td> 
	  <table width="100%" border="0" align="center">
        <tr>
          <td>
		  
		  <table width="95%"  border="1" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="25" class="tdcenterblack">Del</td>
              <td width="70" class="tdcenterblack">เลขที่ PO </td>
              <td width="70" class="tdcenterblack">วันที่</td>
              <td class="tdcenterblack">ตัวแทนขาย</td>
            </tr>
<?
  				while(odbc_fetch_row($curPoHave)){
				$po_no=odbc_result($curPoHave, "po_no");
				$po_date=odbc_result($curPoHave, "po_date");
				$company_name=odbc_result($curPoHave, "company_name");
?>		  			
            <tr>
              <td width="25" class="tdleftwhite">
					<a href="pr_add_po.php?po_no=<? echo $po_no; ?>&flag=del&dateKeySearch=<? echo $dateKeySearch; ?>"   style="cursor:hand">
							  <img src="../include/images/del_icon.png" border="0">
					</a>
			  </td>
              <td><div align="center"><? echo $po_no; ?></div></td>
              <td><div align="center"><? echo $po_date; ?></div></td>
              <td>&nbsp;<? echo $company_name; ?></td>
            </tr>
<?
				}
?>			
          </table>
		  
		  </td>
		</tr>			
    <tr>
      <th width="550">&nbsp;&nbsp;ค้นหา</th>
    </tr>
		  
		<tr><td>
	  <form name="form1" action="pr_add_po.php" method="get">
		<table width="95%"  border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="120" class="tdleftwhite">&nbsp;PO วันที่</td>
            <td width="439"><input name="dateKeySearch" type="text"  value="<? echo $dateKeySearch; ?>" onKeyDown="if(event.keyCode==13) document.form1.submit();"> 
              (รูปแบบ DD-MM-YYYY) </td>
          </tr>
        </table>
		<input name="pr_no" type="hidden" value="<? echo $pr_no; ?>">
	  </form>
		
              </td>
		</tr>			
    <tr>
      <th width="550">&nbsp;&nbsp;รายการ PO ที่ค้นพบ </th>
    </tr>
		<tr><td>
		
		<table width="95%"  border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="tdcenterblack" width="45">Choice</td>
            <td width="70" class="tdcenterblack">เลขที่ PO </td>
            <td width="70" class="tdcenterblack">วันที่</td>
            <td class="tdcenterblack">ตัวแทนขาย</td>
          </tr>
<?
  				while(odbc_fetch_row($curPoChoice)){
				$po_no=odbc_result($curPoChoice, "po_no");
				$po_date=odbc_result($curPoChoice, "po_date");
				$company_name=odbc_result($curPoChoice, "company_name");
?>		  
          <tr>
            <td width="25" class="tdleftwhite" align="center">
					<a href="pr_add_po.php?po_no=<? echo $po_no; ?>&flag=add&dateKeySearch=<? echo $dateKeySearch; ?>"   style="cursor:hand">
							<img src="../include/images/up_icon.png" border="0" >
					</a>
			</td>
            <td><div align="center"><? echo $po_no; ?></div></td>
            <td><div align="center"><? echo $po_date; ?></div></td>
            <td>&nbsp;<? echo $company_name; ?></td>
          </tr>
<?
				}
?>		  
        </table>
		
		
              </td>
		</tr>			  		  
	<tr><td>				
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
                        <th ><div align="right">                        
                          <a onClick="window.close();" style="cursor:hand"
						onMouseDown="document.images['butsave'].src=close3.src" 
						onMouseUp="document.images['butsave'].src=close1.src"						
						 onMouseOver="document.images['butsave'].src=close2.src" 
						 onMouseOut="document.images['butsave'].src=close1.src"> 
						 <img src="../include/button/close1.gif" name="butsave" width="106" height="24" border="0" ></a>
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
  
</div>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>
			<script language="JavaScript" type="text/JavaScript">
							document.form1.dateKeySearch.focus();
			</script>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
