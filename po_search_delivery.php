<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		require_once("../include/alert.php");
		include "../include_RedThemes/wait.php";
?>
<html>
	<head>
		<title>**Search � PO **</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/check_date.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>				
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/button_add.js'></script>						
		<script language='javascript' src='../include/button_del.js'></script>				
	</head>
	<body  topmargin="0" leftmargin="0">
<?
		$po_no=@$_GET["po_no"];	
		$strQUEGeneral = "select    p.po_no,to_char(p.po_date,'DD-MM-YYYY')  po_date,s.supplier_title,s.company_name,to_char(lg.NEW_DATE,'DD-MM-YYYY')  as NEW_DATE,emp.e_name,ISNULL(lg.remark,'-') as log_remark
											from po_master p, supplier s, log_sent_ambu lg,emp
											where p.supplier_id=s.supplier_id(+)
											and lg.rec_user = emp.empno(+)
											and lg.log_type='p' 
											and  p.po_no = '$po_no'  
											order by  lg.ID
											";
	?>
		<br>
		<center>
		<table>
		<tr>
		<td align = 'right'><a href="./po_Delivery_Date.php?po_no=<? echo $po_no; ?>"  title="����͹�ѹ��">
																		<img src="../include/menu_pic/Calendar.png" border="0" height="25px" width="25px"> ����͹�ѹ��</a></td></tr>
		</table>
			<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750">&nbsp;&nbsp;��¡������͹�ѹ������Թ���</th>
				</tr>
				<tr >
					<td>
						<table width="100%" border="0" align="center">
						<tr>
							<td>
								<table width="100%"  border="1" cellspacing="0" cellpadding="0" >
								<tr>
																		<!--<td class="tdcenterblack" width="60">����͹�ѹ��</td>-->
								
									<td width="70" align="center"   class="tdcenterblack"><p>�Ţ��� PO</p></td>
									<td width="70" align="center"   class="tdcenterblack">�ѹ����Դ PO</td>
									<td class="tdcenterblack"><p>Supplier</p></td>
									<td width="150" class="tdcenterblack"><p class="tdcenterblack">�ѹ�������͹</p></td>
									<td width="150" class="tdcenterblack"><p class="tdcenterblack">���˵�</p></td>
									<td width="70" align="center" class="tdcenterblack"><p class="tdcenterblack">���ѹ�֡��¡��</p></td>
								</tr>
								<?
									$curQUEGeneral= @odbc_exec($conn,$strQUEGeneral);	
									while(@odbc_fetch_row($curQUEGeneral)){
										$po_no = odbc_result($curQUEGeneral, "po_no");
										$po_date = odbc_result($curQUEGeneral, "po_date");
										$company_name = odbc_result($curQUEGeneral, "supplier_title").' '.odbc_result($curQUEGeneral, "company_name");
										$po_delivery = odbc_result($curQUEGeneral, "NEW_DATE");
										$e_name = odbc_result($curQUEGeneral, "e_name");
										$log_remark = odbc_result($curQUEGeneral, "log_remark");
								?>
									<tr >
									
														<!--	<td>
															<div align="center">	
														
																	<a href="./po_Delivery_Date.php?po_no=<? //echo $po_no; ?>"  title="����͹�ѹ��">
																		<img src="../include/menu_pic/Calendar.png" border="0" height="25px" width="25px"></a>
													
																</div>
															</td>-->
										<td ><div align="center"><? echo @$po_no;?></div></td>
										<td ><div align="center">&nbsp;<? echo @$po_date;?></div></td>
										
										<td  >&nbsp;<? echo @$company_name;?></td>
										<td  >&nbsp;<? echo @$po_delivery;?></td>
										<td  >&nbsp;<? echo @$log_remark;?></td>
										<td  >&nbsp;<? echo @$e_name;?></td>
									</tr>
							<?}?>
								</table>							
								</td>
						</tr>
						</table>					</td>
				</tr>
			<tr>
				<td>
					<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<th>&nbsp;</th>
	</tr>
	</table>				</td>
			</tr>			
			</table>
			<br>
			<table  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="3" ><strong>�����˵� - ʶҹТͧ PO ������ </strong></td>
			</tr>
			<tr>
				<td>
					<table width="900">
					<tr>
						<td width="20">1</td>
						<td width="200"> MS ���ҧ� PO ��к�</td>
						<td width="20">2</td>
						<td width="200">MS ������ PO ���º��������</td>
						<td width="20">3</td>
						<td width="200">MS Clear � PO</td>
						<td width="20">4</td>
						<td>⡴ѧ���Ѻ�������</td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;&nbsp;��������ͧ ��� PO ����� port ������ ��ѧ B1 ����</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</center>
		
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

