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
					//alert(values[0]);
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
		//require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$roles_user = $_SESSION["roles_user"];
		include "../include_RedThemes/wait.php";
		flush();
?>
<html>
	<head>
		<title>**Search ใบ PR **</title>
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
		
		//========== เรื่องการ Search แล้วค้าง keyword ==================
		$flagSearch = @$_POST["flagSearch"];
		if(@$flagSearch == 'PushSearch'){
            $s_pr_company = @$_POST["pr_company"];
			$s_pr_no=@$_POST["s_pr_no"];
			$s_start=@$_POST["s_start"];
			$s_stop=@$_POST["s_stop"];
			
			$s_pr_status=@$_POST["s_pr_status"];
			$s_for_ref=@$_POST["s_for_ref"];
			$s_empno=@$_POST["empno"];
			//$s_prod_name=@$_POST["s_prod_name"];
			if(($s_start=='')&&($s_stop!='')) $s_start =$s_stop;
			else if(($s_start!='')&&($s_stop=='')) $s_stop = $s_start;
			$_SESSION["ses_Search"] = "pr|-|".$s_pr_no."|-|".$s_start."|-|".$s_stop."|-|".$s_pr_status."|-|".$s_for_ref."|-|".$s_pr_company."|-|".$s_empno;
		}else{
		
			$ses_Search = $_SESSION["ses_Search"];
			$arr_Search=explode("|-|",$ses_Search);				
			$flagSearch=trim($arr_Search[0]);		
			if($flagSearch=="pr"){
				$s_pr_no=trim($arr_Search[1]);						
				$s_start=trim($arr_Search[2]);						
				$s_stop=trim($arr_Search[3]);				
				//$s_supplier=trim($arr_Search[4]);						
				$s_pr_status=trim($arr_Search[4]);						
				$s_for_ref=trim($arr_Search[5]);			
				//$s_prod_name=trim($arr_Search[6]);			
				$s_pr_company = trim($arr_Search[6]);
				$s_empno = trim($arr_Search[7]);
			}
		}
		//================================================

		$strQUEGeneral = "select distinct p.pr_no,format(p.pr_date,'DD-MM-YYYY')  pr_date,
												p.pr_status,p.obj_name1,p.flag_obj,pp.po_no
											from pr_master p, pr_details pd,pr_and_po pp 
											where 1=1 
											and p.pr_no=pd.pr_no(+)
 											 and p.pr_no=pp.pr_no(+) ";
		if(@$s_pr_no != '') $strQUEGeneral .= "and   p.pr_no like upper('%$s_pr_no%')  ";			
		if(@$s_start != '') $strQUEGeneral .= "and   to_date(p.pr_date,'DD-MM-YYYY') between to_date('$s_start','DD-MM-YYYY') and to_date('$s_stop','DD-MM-YYYY')  ";			
		
		if(@$s_for_ref!="") $strQUEGeneral .= "and upper(p.obj_name1) like upper('%$s_for_ref%') ";		
		//if(@$s_prod_name!="") $strQUEGeneral .= "and ((upper(pd.prod_name) like upper('%$s_prod_name%')) or (upper(pd.prod_no) like upper('%$s_prod_name%'))) ";	
		if(@$s_empno!="") $strQUEGeneral .= "and upper(p.rec_user) like upper('%$s_empno%') ";	
		
        if(@$s_pr_company=="T") $strQUEGeneral .= "and p.flag_obj in ('8') ";
        else $strQUEGeneral .= "and (p.flag_obj not in ('8') ) ";

	
				if(@$s_pr_status=="") $strQUEGeneral .= "and p.pr_status = '1' ";								
				else if(@$s_pr_status!="all") $strQUEGeneral  .= "and p.pr_status = '$s_pr_status' ";							
				
		 
			$strQUEGeneral .="order by  pr_status,pr_no";
		

	?>
		<br>
		<center>
			<form name="pr_searchNew" method="post" action="pr_searchNew.php">
				<input name="flagSearch" type="hidden" value="PushSearch">
				<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
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
									<td width="120" class="tdleftwhite">&nbsp;</td>
									<td width="100" class="tdleftwhite">เลขที่ PR</td>
									<td width="403"><input name="s_pr_no" type="text"    onKeyDown="if(event.keyCode==13) document.pr_searchNew.submit();" value="<?  echo @$s_pr_no; ?>" size="40"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">วันที่เปิด PR</td>
								
									<td>
									<input name="s_start" type="text"  value="<?  echo @$s_start; ?>" size="10" maxlength="10" onBlur="return testdate(document.pr_searchNew.s_start);"  onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
										ถึง
									<input name="s_stop" type="text"  value="<?  echo @$s_stop; ?>" size="10" maxlength="10"  onKeyDown="if(event.keyCode==13){ testdate(document.pr_searchNew.s_stop); document.pr_searchNew.submit(); }"> 
										(รูปแบบ DD-MM-YYYY) 
									</td>
								</tr>
                                <tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">ชื่อผู้จัดทำ PR</td>
									<td>
                                     <?
											$str = "select e_name from emp where empno = '".$s_empno."'";
											$cur_query = odbc_exec($conn,$str);
											$empno_show = odbc_result($cur_query, "e_name");
									  ?>
                                    		<input name="empno" id="empno" type="text" value="<?  echo @$s_empno; ?>"   size="10" maxlength="15" class="style_readonly" readonly="">
                                            <input name="empno_show" id="empno_show" type="text" value="<?  echo $empno_show ?>"   size="50"  class="style_readonly" readonly="">
                                            <img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovEmp();">
                                            <img src="include/images/close.png" width="20" height="19" onClick="clearname();">
                                    </td>
								</tr>
								
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">FOR</td>
									<td><input name="s_for_ref" type="text"  onKeyDown="if(event.keyCode==13) document.pr_searchNew.submit();" value="<?  echo @$s_for_ref; ?>" size="40"></td>
								</tr>
													
								<tr>
									<td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">สถานะของ PR </td>
									<td>
										<select name="s_pr_status">
										
											<option value="1" <? if((@$s_pr_status=="")||(@$s_po_status=="1")) echo "selected"; ?>>ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)</option>
										
											<option value="2" <? if(@$s_pr_status=="2") echo "selected"; ?>>รออนุมัติจากผู้จัดการ</option>
											<option value="3" <? if(@$s_pr_status=="3") echo "selected"; ?>>ผู้จัดการไม่อนุมัติใบขอซื้อ(รอแก้ไข)</option>
											<option value="4" <? if(@$s_pr_status=="4") echo "selected"; ?>>รอการออก PO</option>
											<option value="5" <? if(@$s_pr_status=="5") echo "selected"; ?>>การออก PO เรียบร้อยแล้ว</option>
											<option value="6" <? if(@$s_pr_status=="6") echo "selected"; ?>>รับสินค้าได้แล้ว</option>
											<option value="7" <? if(@$s_pr_status=="7") echo "selected"; ?>>การตีกลับใบขอซื้อ</option>
											<option value="all" <? if(@$s_pr_status=="all") echo "selected"; ?>>ทุกสถานะ</option>
										</select>
									</td>
								</tr>
								<tr>
                                    <td class="tdleftwhite">&nbsp;</td>
									<td class="tdleftwhite">บริษัท </td>
                                    <td>
                                        <select name="pr_company">
                                          <option value = "S" <?php if(@$s_pr_company=="") echo "selected"; ?>>Supreme</option>
                                          <option value = "T" <?php if(@$s_pr_company=="T") echo "selected"; ?>>Transtek</option>
                                        </select>
                                    </td>
								</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<th colspan="3">
										<div align="right"> 
											<a target="_blank" style="cursor:hand" onClick="document.pr_searchNew.submit();"> 
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
			<table width="900"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<th width="750">&nbsp;&nbsp;ผลการค้นหา PR</th>
				</tr>
				<tr >
					<td>
						<table width="100%" border="0" align="center">
						<tr>
							<td>
								<table width="100%"  border="1" cellspacing="0" cellpadding="0" >
								<tr>
								<td width="70" align="center"   class="tdcenterblack"><p>Report</p></td>
									<td width="70" align="center"   class="tdcenterblack"><p>เลขที่ PR</p></td>
										<td width="70" align="center"   class="tdcenterblack"><p>เลขที่ PO</p></td>
									<td width="70" align="center"   class="tdcenterblack">วันที่เปิด PR</td>
									<td class="tdcenterblack"><p>For</p></td>
									<td width="150" class="tdcenterblack"><p class="tdcenterblack">สถานะ</p></td>
									<td width="70" align="center" class="tdcenterblack"><p class="tdcenterblack">บริษัท</p></td>
								</tr>
								<?
								 	//echo $strQUEGeneral;
									if(@$flagSearch == 'PushSearch'){
									$curQUEGeneral= odbc_exec($conn,$strQUEGeneral);	
									while(odbc_fetch_row($curQUEGeneral)){
										$pr_no = odbc_result($curQUEGeneral, "pr_no");
										$pr_date = odbc_result($curQUEGeneral, "pr_date");
										$pr_status = odbc_result($curQUEGeneral, "pr_status");
										$obj_name1 = odbc_result($curQUEGeneral, "obj_name1");
										$po_no = odbc_result($curQUEGeneral, "po_no");
										$pr_company = odbc_result($curQUEGeneral, "flag_obj");
										
										
								?>
									<tr >
									    <td width="60" align="center">
													<a href="./prmas_reportcode.php?pr_no=<? echo $pr_no; ?>"   style="cursor:hand" target="_blank"  title="ใบ PR">
																<img src="../include/images/report_icon.png" border="0">
														</a>
										</td>   		
										<td ><div align="center"><? echo $pr_no;?></div></td>
										<td ><div align="center">
										<? if ($po_no!=""){?>
											<a onClick="remote_add('pomas_report.php?doc_no=<? echo $po_no; ?>&doc_type=systemgen',500,155);" style="cursor:hand"><? echo $po_no; ?></a>
										<?}
										else
										{
										?>
											&nbsp;
										<?}?>
											</div></td>
										<td ><div align="center">&nbsp;<? echo $pr_date;?></div></td>
										<td  >&nbsp;<? echo $obj_name1;?></td>
										<td  >&nbsp;
											<?
												switch($pr_status){
													case '1'	: echo "ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)"; 		break;
													case '2'	: echo "รออนุมัตจากผู้จัดการ"; 		break;
													case '3'	: echo "ผู้จัดการไม่อนุมัติใบขอซื้อ(รอแก้ไข)"; 			break;
													case '4'	: echo "รอการออก PO"; 		break;
													case '5'   	: echo "การออก PO เรียบร้อยแล้ว"; 		break;
													case '6'   	: echo "รับสินค้าได้แล้ว"; 		break;
													case '7'   	: echo "การตีกลับใบขอซื้อ"; 		break;
												}
											?>										</td>
										<td>&nbsp;<?php if($pr_company=="8") echo "Transtek"; else echo "Supreme";?></td>
									</tr>
								<?
									}
									}
								?>
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
	
		</center>
			<script language="JavaScript" type="text/JavaScript">
				document.pr_searchNew.s_pr_no.select();
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

