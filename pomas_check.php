<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		$roles_user = $_SESSION["roles_user"];
		if($roles_user=='MS') {
			require_once("../include_RedThemes/odbc_connect.php");			
			require_once("../include_RedThemes/MSSQLServer_connect.php");		
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
<title>ตรวจสอบสถานะการทำรับเข้า</title>
</head>

<body>
<?php
	$flagValue = @$_POST["flagValue"];
	$date_start = @$_POST["date_start"];
	$date_stop = @$_POST["date_stop"];
	$de_date_start = @$_POST["de_date_start"];
	$de_date_stop = @$_POST["de_date_stop"];
	$po_no = trim(@$_POST["po_no"]);
	if($date_start == "" && $date_stop != "")
		$date_start = $date_stop;
	if($date_start != "" && $date_stop == "")
		$date_stop = $date_start;
	if($flagValue=="search")
	{
		$_SESSION["ses_poCheck"] = $po_no."|-|".$date_start."|-|".$date_stop;
		$strSQL = "select p.po_no, format(p.po_date,'dd-mm-yyyy') po_date, s.company_name, p.paycode, p.delivery_time, p.po_status, p.po_file, p.po_file2, p.po_file3
						,format(p.delivery_date,'dd-mm-yyyy') delivery_date
						  from po_master p, supplier s
						  where p.supplier_id = s.supplier_id (+)
						  and p.po_status in (2,3)";
		if($po_no != "")
			$strSQL .= " and upper(trim(p.po_no)) like upper('%$po_no%')"; 
		if($date_start != "" && $date_stop != "")
			$strSQL .= " and (to_date(p.po_date, 'dd-mm-yyyy') between to_date('$date_start','dd-mm-yyyy') and to_date('$date_stop','dd-mm-yyyy'))";
		if($de_date_start != "" && $de_date_stop != "")
			$strSQL .= " and (to_date(p.delivery_date, 'dd-mm-yyyy') between to_date('$de_date_start','dd-mm-yyyy') and to_date('$de_date_stop','dd-mm-yyyy'))";
		$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
	//echo $strSQL;
	}
	else{
				if($_SESSION["ses_poCheck"]!="")
				{	$ses = explode("|-|",$_SESSION["ses_poCheck"]);
					$i=0;
					$po_no = $ses[$i++];
					$date_start = $ses[$i++];
					$date_stop = $ses[$i++];
				}
	}

?>
<form name="po_check" method="post" action="pomas_check.php">
<input type="hidden" name="flagValue"/>
<div align="center">
<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
<tr>
	<th width="750">&nbsp;&nbsp;ตรวจสอบสถานะการทำรับเข้า</th>
</tr>
<tr >
	<td>
		<table width="100%" border="0" align="center">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%" class="tdleftwhite">&nbsp;</td>
					<td width="15%" class="tdleftwhite">เลขที่ PO</td>
					<td width="65%">	<input name="po_no" type="text" onKeyDown="if(event.keyCode==13){ document.po_check.flagValue.value = 'search'; document.po_check.submit();}" 
					value="<?= @$po_no; ?>" size="40"></td>
				</tr>
				<tr>
					<td class="tdleftwhite">&nbsp;</td>
						<td class="tdleftwhite">วันที่เปิด PO </td>
						<td>
							<input name="date_start" type="text" value="<?= @$date_start; ?>" size="10" maxlength="10" 
							onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
							onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
							<script language='javascript'>
									if (!document.layers) {
										document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_check.date_start, \"dd-mm-yyyy\")'>");
									}
							</script>
							ถึง
							<input name="date_stop" type="text" value="<?= @$date_stop; ?>" size="10" maxlength="10"
							onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
							onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) { document.po_check.flagValue.value = 'search'; document.po_check.submit(); }"> 
							<script language='javascript'>
									if (!document.layers) {
										document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_check.date_stop, \"dd-mm-yyyy\")'>");
									}
							</script>
							(รูปแบบ DD-MM-YYYY) 
						</td>
				</tr>
				<tr>
					<td class="tdleftwhite">&nbsp;</td>
						<td class="tdleftwhite">วันที่ส่งของ</td>
						<td>
							<input name="de_date_start" type="text" value="<?= @$de_date_start; ?>" size="10" maxlength="10" 
							onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
							onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
							<script language='javascript'>
									if (!document.layers) {
										document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_check.de_date_start, \"dd-mm-yyyy\")'>");
									}
							</script>
							ถึง
							<input name="de_date_stop" type="text" value="<?= @$de_date_stop; ?>" size="10" maxlength="10"
							onFocus="javascript:vDateType='3'" onBlur="DateFormat(this,this.value,event,true,'3')" 
							onKeyUp="DateFormat(this,this.value,event,false,'3')" onKeyDown="if(event.keyCode==13) { document.po_check.flagValue.value = 'search'; document.po_check.submit(); }"> 
							<script language='javascript'>
									if (!document.layers) {
										document.write("<img src=\"../include_RedThemes/images/date_icon.gif\" style=\"cursor:hand\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, po_check.de_date_stop, \"dd-mm-yyyy\")'>");
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
							<a target="_blank" style="cursor: pointer" onClick="document.po_check.flagValue.value = 'search'; document.po_check.submit();">
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
<table width="950"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
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
					<td class="tdcenterblack" width="13%">Report</td>
					<td class="tdcenterblack" width="8%">เลขที่ PO</td>
					<td class="tdcenterblack" width="8%">วันที่เปิด PO</td>
					<td class="tdcenterblack" width="25%">Supplier</td>
					<td class="tdcenterblack" width="13%">Delivery Time</td>
					<td class="tdcenterblack" width="10%">Delivery Date</td>
					<td class="tdcenterblack" width="10%">วันที่ส่งของตาม<br />Payment Term</td>
					<td class="tdcenterblack" width="17%">สถานะทาง Oracle</td>
				</tr>
				<?php 
					while(@odbc_fetch_row($strResult))
					{
						$pay_code = @odbc_result($strResult,"PayCode");
						$po_no = @odbc_result($strResult,"po_no");
						$po_date = @odbc_result($strResult,"po_date");
						$supplier = @odbc_result($strResult,"company_name");
						$delivery = @odbc_result($strResult,"delivery_time");
						$po_status = @odbc_result($strResult,"po_status");
						$po_file = @odbc_result($strResult,"po_file");
						$po_file2 = @odbc_result($strResult,"po_file2");
						$po_file3 = @odbc_result($strResult,"po_file3");
						$delivery_date = @odbc_result($strResult,"delivery_date");
						$strQUE = "select Day from payment_master where PayCode = '$pay_code'";						
						$resultQUE = @odbc_exec($MSSQL_connect,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));	
						$Day = (int)@odbc_result($resultQUE,"Day");
						//$Day = '555';
						$strSEL = "select format((to_date(p.po_date,'dd-mm-yyyy')+ $Day),'dd-mm-yyyy') payment_date from po_master p where po_no = '$po_no'";
						$resultSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));	
						$payment_date = @odbc_result($resultSEL,"payment_date");
						
				?>	
					<tr>
						<td>
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="35%" style="text-align:center">
									<a onClick="remote_add('pomas_report.php?doc_no=<?= @$po_no; ?>&doc_type=systemgen',500,155);" style="cursor:pointer"><img src="../include/images/report_icon.png" border="0"></a>
								<td width="65%" style="text-align:center">System</td>
							</tr>
							<? if( (@$po_file != "") || (@$po_file2 != "") || (@$po_file3 != "") ){ ?>
							<tr>
								<td width="35%" style="text-align:center">
									<a onClick="remote_add('pomas_report.php?doc_no=<?= @$po_no; ?>&doc_type=userup&po_file=<?= @$po_file; ?>&po_file2=<?= $po_file2; ?>&po_file3=<?= @$po_file3; ?>',500,155);" style="cursor:pointer"><img src="../include/images/report_icon.png" border="0"></a>
								</td>
								<td width="65%" style="text-align:center">User Upload </td>
							</tr>
							<?php 
								}
							?>
							</table>
						</td>
						<td style="text-align:center">&nbsp;<?= @$po_no; ?></td>
						<td style="text-align:center">&nbsp;<?= @$po_date; ?></td>
						<td>&nbsp;<?= @$supplier; ?></td>
						<td>&nbsp;<?= @$delivery; ?></td>
						<td style="text-align:center">&nbsp;<?= @$delivery_date; ?></td>
						<td style="text-align:center">&nbsp;<?= @$payment_date; ?></td>
						<td>&nbsp;<? if(@$po_status == 2) echo "MS พิมพ์ใบ PO เรียบร้อยแล้ว"; else if(@$po_status == 3) echo "MS Clear ใบ PO"; ?></td>
					</tr>
				<?php 
					}
				?>
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
		alert("คุณไม่มีสิทธิ์ในการใช้งานเว็บเพจหน้านี้ค่ะ");
	}
}else{
	include("../include_RedThemes/SessionTimeOut.php");
}
?>