<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		//require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		include "../include_RedThemes/wait.php";
		flush();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<link href = "../include/style1.css" rel="stylesheet" type="text/css">
<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>		
<script language='javascript' src='../include_RedThemes/popcalendar.js'></script>				
<script language='javascript' src='../include/button_add.js'></script>				
<title>ตรวจสอบสินค้ารับเข้า</title>
</head>

<body>
<?php
	$flagValue = @$_POST["flagValue"];
	$date_start = @$_POST["date_start"];
	$date_stop = @$_POST["date_stop"];
	$po_no = trim(@$_POST["po_no"]);
	if($date_start == "" && $date_stop != "")
		$date_start = $date_stop;
	if($date_start != "" && $date_stop == "")
		$date_stop = $date_start;
	if($flagValue=="search")
	{
		$_SESSION["ses_poImport"] = $po_no."|-|".$date_start."|-|".$date_stop;
	}
	else{
				if($_SESSION["ses_poImport"] != "")
				{	$ses = explode("|-|",$_SESSION["ses_poImport"]);
					$i=0;
					$po_no = $ses[$i++];
					$date_start = $ses[$i++];
					$date_stop = $ses[$i++];
				}
	}
	$MSSQL = "select distinct po_no from podt_center where received = 'Y'";					
	if($po_no != "")
		$MSSQL .= " and upper(Rtrim(Ltrim(po_no))) like upper('%$po_no%') ";
	$MSSQL_result = @odbc_exec($MSSQL_connect,$MSSQL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
?>

<form name="po_import" action="pomas_import.php" method="post">
<input type="hidden" name="flagValue" value="search" />
<div align="center">
	<table width="900" border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
		<tr>
			<th width="750">&nbsp;&nbsp;ตรวจสอบสินค้ารับเข้า</th>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" align="center">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="tdleftwhite">&nbsp;</td>
							<td width="15%" class="tdleftwhite">เลขที่ PO</td>
							<td width="65%">	<input name="po_no" type="text" onKeyDown="if(event.keyCode==13) { document.po_import.submit(); }" 
							value="<?= @$po_no; ?>" size="40"></td>
						</tr>
						<tr>
							<td class="tdleftwhite">&nbsp;</td>
								<td class="tdleftwhite">วันที่เปิด PO</td>
								<td>
									<input name="date_start" type="text" value="<?= @$date_start; ?>" size="10" maxlength="10" 
									onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
									onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
									<script language='javascript'>
											if (!document.layers) {
												document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_import.date_start, \"dd-mm-yyyy\")'>");
											}
									</script>
									ถึง
									<input name="date_stop" type="text" value="<?= @$date_stop; ?>" size="10" maxlength="10"
									onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
									onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) { document.po_import.submit(); }"> 
									<script language='javascript'>
											if (!document.layers) {
												document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_import.date_stop, \"dd-mm-yyyy\")'>");
											}
									</script>
									(รูปแบบ DD-MM-YYYY) 
								</td>
						</tr>
						</table>
					</td>
				<tr>
					<td>
						<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<th colspan="3">
								<div align="right"> 
									<a target="_blank" style="cursor: pointer" onClick="document.po_import.submit();">
										<img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0">
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
	<br />
	<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
		<tr>
			<th width="750">&nbsp;&nbsp;ผลการค้นหา</th>
		</tr>
		<tr>
		<td>
			<table width="100%" border="0" align="center">
			<tr>
				<td>
					<table width="100%"  border="1" cellspacing="0" cellpadding="0" >
						<tr>
							<td class="tdcenterblack" width="10%">แสดงผล</td>
							<td class="tdcenterblack" width="15%">เลขที่ PO</td>
							<td class="tdcenterblack" width="15%">วันที่เปิด PO</td>
							<td class="tdcenterblack" width="40%">Supplier</td>
							<td class="tdcenterblack" width="20%">สถานะ</td>
						</tr>
						<?php 
							while(@odbc_fetch_row($MSSQL_result))
							{
								$po_selected = @odbc_result($MSSQL_result,"po_no");
								$strSQL = "select p.po_no, format(p.po_date,'dd-mm-yyyy') po_date, s.company_name, p.po_status
												  from po_master p, supplier s
												  where p.supplier_id = s.supplier_id
												  and p.po_no like '$po_selected' ";
								if($date_start != "" && $date_stop != "")
									$strSQL .= " and format(p.po_date,'dd-mm-yyyy') between format('$date_start','dd-mm-yyyy') and format('$date_stop','dd-mm-yyyy')";
								$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
					if(@odbc_result($strResult,"po_no")!="")
					{
						?>
						<tr>
							<td style="text-align:center"><a href="../pr_po/pomas_import(2).php?po_no=<?=$po_selected;?>" target="_blank" title="รายละเอียดสินค้ารับเข้า"><img src="./include/images/report_icon.jpg" border="0" height="25px"></a></td>
							<td style="text-align:center">&nbsp;<?=@odbc_result($strResult,"po_no");?></td>
							<td style="text-align:center">&nbsp;<?=@odbc_result($strResult,"po_date");?></td>
							<td style="text-align:left">&nbsp;<?=@odbc_result($strResult,"company_name");?></td>
							<?php
								$status = @odbc_result($strResult,"po_status");
								if($status == "1")
									$status = "MS สร้างใบ PO ในระบบ";
								else if($status == "2")
									$status = "MS พิมพ์ใบ PO เรียบร้อยแล้ว";
								else if($status == "3")
									$status = "MS Clear ใบ PO";
								else if($status == "4")
									$status = "โกดังทำรับเข้าแล้ว";
								else if($status == "5")
									$status = "ไม่ใช้งาน";
								else $status = "";		
							?>
							<td style="text-align:left">&nbsp;<?=$status;?></td>
						</tr>
			<?php }
					} ?>
					</table>
				</td>
			</tr>
		</table>
		</td>
		</tr>		
	</table>	
</div>	
</form>
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