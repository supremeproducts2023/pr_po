<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {		
		$http_host = '172.10.0.16';
		$roles_user = $_SESSION["roles_user"];
		
		$doc_no= @$_GET["doc_no"];
		$doc_type= @$_GET["doc_type"];
		$po_file= @$_GET["po_file"];
		$po_file2= @$_GET["po_file2"];
		$po_file3= @$_GET["po_file3"];
?>
<html>
<head>
		<title>**Search ข้อมูลรายงาน **</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language="javascript">
		function OpenFile(a) {
			window.open(a);
		}
		
		</script>
</head>
<body  topmargin="0" leftmargin="0">
<center>
<form name="report_search" method="get" action="pomas_reportcode.php" target="_blank">
  <table width="500"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
    <tr>
      <th width="451">&nbsp;&nbsp;ค้นหา</th>
    </tr>
    <tr >
      <td>
        <table width="100%" border="0" align="center">
				<tr>
				<td width="130" class="tdleftwhite">&nbsp;เลขที่เอกสารที่ส่ง <span class="style_star">*</span></td>
				<td><input name="doc_no" type="text" class="style_readonly" value="<? echo $doc_no; ?>"  readonly="">
				</td>
				</tr>      
<?
			if($doc_type=="systemgen"){
?>
				<tr>
				<td class="tdleftwhite">&nbsp;ประเภทเอกสารที่แสดง <span class="style_star">*</span></td>
				<td>
						<input name="doc_type" type="radio" value="all" <? if(($roles_user == 'MNGShowPO')or ($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "disabled"; else echo "checked"; ?>> PO ที่ระบบ Generate แสดงรายการทั้งหมด <br>
						<input type="radio" name="doc_type" value="nonprice"  <? if(($roles_user == 'MNGShowPO')or($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "checked"; ?>>PO ที่ระบบ Generate ปิดราคา <br>									
						<input type="radio" name="doc_type" value="nontail" <? if(($roles_user == 'MNGShowPO')or($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "disabled"; ?>> PO ที่ระบบ Generate ปิดท้าย </td>
				</tr>	
<?
			}else if($doc_type=="userup"){
?>						
				<tr>
				<td class="tdleftwhite">&nbsp;ประเภทเอกสารที่แสดง <span class="style_star">*</span></td>
				<td>
						<input name="doc_type" type="radio" value="all"  <? if(($po_file == '')or($roles_user == 'MNGShowPO')or($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "disabled";  else echo "checked"; ?>> 
						<input name="path1" type="hidden" value="<? echo "\\\\".$http_host."\\iso\\po_thai\\".$po_file; ?>">											
						PO ที่ upload โดยผู้ใช้งาน แสดงรายการทั้งหมด <br>
						<input type="radio" name="doc_type" value="nonprice"  <? if($po_file2 == '')echo "disabled"; else if(($roles_user == 'MNGShowPO')or($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "checked"; ?>> 
				 		 <input name="path2" type="hidden" value="<? echo "\\\\".$http_host."\\iso\\po_thai2\\".$po_file2; ?>">
						PO ที่ upload โดยผู้ใช้งาน  ปิดราคา<br>
						<input type="radio" name="doc_type" value="nontail" <? if(($po_file3 == '')or($roles_user == 'MNGShowPO')or($roles_user == 'ShowPO2')or ($roles_user=='MNGGWD'))echo "disabled"; ?>> 
				 		 <input name="path3" type="hidden" value="<? echo "\\\\".$http_host."\\iso\\po_thai3\\".$po_file3; ?>">				
						PO ที่ upload โดยผู้ใช้งาน				  ปิดท้าย </td>
				</tr>	
<?
			}	
?>											    
          <tr>
            <td colspan="2">
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <th colspan="3"><div align="right"> 
<?
			if($doc_type=="systemgen"){
?>
				  <a onClick="document.report_search.submit();" style="cursor:hand">
						 <img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0"> 
				 </a> 	
<?
			}else if($doc_type=="userup"){
?>						
				  <a onClick="if(document.report_search.doc_type[0].checked) OpenFile(document.report_search.path1.value); else if (document.report_search.doc_type[1].checked) OpenFile(document.report_search.path2.value); else if (document.report_search.doc_type[2].checked) OpenFile(document.report_search.path3.value);" style="cursor:hand">
						 <img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0"> 
				 </a> 	
<?
			}	
?>											    
				</div></th>
                </tr>
            </table>			</td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
</center>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
