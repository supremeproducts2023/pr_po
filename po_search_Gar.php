<script type="text/javascript" language="javascript">
function openEmp(type_use)
{
		returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_emp.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
		return returnvalue;
}
			
function lovEmp()
{
		returnvalue = openEmp(''); 
		if (returnvalue != null)
		{ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= "")
							document.getElementById("empno").value = values[0]; 
					else document.getElementById("empno").value =''; 
					
					if(values[1]!= "")
							document.getElementById("empno_show").value = values[1]; 
					else 
							document.getElementById("empno_show").value =''; 		
		}
}       
function clearname()
{
		document.getElementById("empno").value = '';
		document.getElementById("empno_show").value = '';
}
</script>
<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$roles_user = $_SESSION["roles_user"];
		include "../include_RedThemes/wait.php";
		flush();
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
		$s_empno = "";
		//========== ����ͧ��� Search ���Ǥ�ҧ keyword ==================
		$flagSearch = @$_POST["flagSearch"];
		if(@$flagSearch == 'PushSearch'){
			$s_po_no=@$_POST["s_po_no"];
			$_SESSION["ses_Search"] = "po|-|".$s_po_no;
		}else{
			$ses_Search = $_SESSION["ses_Search"];
			$arr_Search=explode("|-|",$ses_Search);				
			$flagSearch=trim($arr_Search[0]);		
			if($flagSearch=="po"){
				$s_po_no=trim($arr_Search[1]);						
			}
		}
		
		$strQUEGeneral = "select  distinct  p.po_no,format(p.po_date,'dd-MM-yyyy')  po_date,
												p.po_status,format(p.e_mail_date,'dd-MM-yyyy HH:MI') e_mail_date,
												s.supplier_title,s.company_name,p.po_file,p.po_file2,p.po_file3,p.po_company
											from po_master p
											left jon supplier s on p.supplier_id=s.supplier_id
											left join po_details pd on p.po_no=pd.po_no
											where 1 = 1";
		if(@$s_po_no != '') $strQUEGeneral .= "and   p.po_no like upper('%$s_po_no%')  ";			
		$strQUEGeneral .="order by  po_no desc";
	?>
		<br>
		<center>
			<form name="po_search" method="post" action="po_search_Gar.php">
				<input name="flagSearch" type="hidden" value="PushSearch">
				<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750">&nbsp;&nbsp;����</th>
				</tr>
				<tr >
					<td>
						<table width="100%" border="0" align="center">
                        <tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td width="120" class="tdleftwhite">&nbsp;</td>
									<td width="100" class="tdleftwhite">�Ţ��� PO</td>
									<td width="403"><input name="s_po_no" type="text"    onKeyDown="if(event.keyCode==13) document.po_search.submit();" value="<?  echo @$s_po_no; ?>" size="40"></td>
								</tr>
								</table>
							</td>
						</tr>
                        <tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<th colspan="3">
										<div align="right"> 
											<a target="_blank" style="cursor:hand" onClick="document.po_search.submit();"> 
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
			</form>
			<table width="900"  border="1" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750"  colspan="6">&nbsp;&nbsp;�š�ä���</th>
				</tr>
                <tr>
               		<td width="70" align="center"   class="tdcenterblack"><p>��§ҹ</p></td>
                	<td width="100" align="center"   class="tdcenterblack"><p>�Ţ��� PO</p></td>
					<td width="100" align="center"   class="tdcenterblack">�ѹ����Դ PO</td>
					<td class="tdcenterblack"><p>Supplier</p></td>
					<td width="150" class="tdcenterblack"><p class="tdcenterblack">ʶҹ�</p></td>
					<td width="70" align="center" class="tdcenterblack"><p class="tdcenterblack">����ѷ</p></td>
                </tr>
				
                <?
                	  if($flagSearch== 'PushSearch')
					  {
								//echo $strQUEGeneral;	
									$curQUEGeneral= odbc_exec($conn,$strQUEGeneral);	
									while(odbc_fetch_row($curQUEGeneral))
									{
										$po_no = odbc_result($curQUEGeneral, "po_no");
										$po_date = odbc_result($curQUEGeneral, "po_date");
										$company_name = odbc_result($curQUEGeneral, "supplier_title").' '.odbc_result($curQUEGeneral, "company_name");
										$po_status = odbc_result($curQUEGeneral, "po_status");
										$po_company = odbc_result($curQUEGeneral, "po_company");
				?>
                					<tr >
                                    	<td ><div align="center">
                                        		<a onClick="remote_add('Report_po_excel.php?po_no=<? echo $po_no; ?>');" 
                                                style="cursor:hand"><img src="../include/images/report_icon.png" border="0"></a></div>
                                         </td>
                                        <td ><div align="center"><? echo $po_no;?></div></td>
                                        <td ><div align="center">&nbsp;<? echo $po_date;?></div></td>
                                        <td  >&nbsp;<? echo $company_name;?></td>
                                        <td  >&nbsp;
											<?
												switch($po_status){
													case '1'	: echo "MS ���ҧ� PO ��к�"; 		break;
													case '2'	: echo "MS ������ PO ���º��������"; 		break;
													case '3'	: echo "MS Clear � PO"; 			break;
													case '4'	: echo "⡴ѧ���Ѻ�������"; 		break;
													case '5'   	: echo "PO �١¡��ԡ�� MS"; 		break;
												}
											?>										</td>
										<td>&nbsp;<?php if($po_company=="T") echo "Transtek"; else echo "Supreme";?></td>
                                 	</tr>
            	<?
                    				}
					}
				?>
				
			<tr>
				<td   colspan="6">
					<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <th >&nbsp;</th>
                    </tr>
					</table>				
                </td>
			</tr>			
			</table>
		</center>
			<script language="JavaScript" type="text/JavaScript">
				document.po_search.s_po_no.select();
			</script>
		
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

