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
				$empno_user=$_SESSION["empno_user"];		
				$http_host = '172.10.0.16';
				$choice_value= $_SESSION["choice_value"];	

	include "../include_RedThemes/wait.php";
	flush();
				$s_empno = "";
				//========== ����ͧ��� Search ���Ǥ�ҧ keyword ==================
				if (@$_GET["flagAction"] != "")
					$flagAction = @$_GET["flagAction"];
				else
					$flagAction = @$_POST["flagAction"];
				if(@$flagAction == 'PushSearch'){
						$s_pr_no=@$_POST["s_pr_no"];
						$s_empno=@$_POST["empno"];
						$s_start=@$_POST["s_start"];
						$s_stop=@$_POST["s_stop"];
						$s_status=@$_POST["s_status"];
						if(($s_start=='')&&($s_stop!='')) $s_start =$s_stop;
						else if(($s_start!='')&&($s_stop=='')) $s_stop = $s_start;
						
						$_SESSION["ses_Search"] = "pr|-|".$s_pr_no."|-|".$s_start."|-|".$s_stop."|-|".$s_status."|-|".$s_empno;	
				}else{
						$ses_Search = $_SESSION["ses_Search"];
						$arr_Search=explode("|-|",$ses_Search);				
						$flagAction=trim($arr_Search[0]);

						if($flagAction=="pr"){
								$s_pr_no=trim($arr_Search[1]);						
								$s_start=trim($arr_Search[2]);						
								$s_stop=trim($arr_Search[3]);			
								$s_status=trim($arr_Search[4]);			
								$s_empno=trim($arr_Search[5]);	
						}
				}
				//echo $empno_user;
				if($empno_user=='20044')
				$choice_value = 'prrep';
				//================================================
				//echo $choice_value;
				$str_query_pr = "select  p.pr_no,format(p.pr_date,'DD-MM-YYYY')  pr_date,p.pr_status,s.company_name,flag_obj,obj_name2,pr_path
										,format(p.approve_date,'dd-MM-yyyy') approve_date
											from pr_master p,supplier s 
											where p.supplier_id=s.supplier_id(+) ";
				if(@$_SESSION["pr_type"]=="T")
					$str_query_pr .= " and p.flag_obj = '8' ";
				else if(@$_SESSION["pr_type"]=="S")	$str_query_pr .= " and p.flag_obj not in ('8') ";						
				if(@$s_pr_no != '') $str_query_pr .= "and   pr_no like upper('%$s_pr_no%')  ";			
				if(@$s_empno != '') $str_query_pr .= "and   empno like upper('%$s_empno%')  ";			
				if(@$s_start != '') $str_query_pr .= "and   to_date(pr_date,'DD-MM-YYYY') between to_date('$s_start','DD-MM-YYYY') and to_date('$s_stop','DD-MM-YYYY')  ";			
				
				if(($choice_value == 'prup')||($choice_value == 'prdel')){
						if((@$s_status=="1")||(@$s_status=="3")||(@$s_status=="7")) $str_query_pr .= "and   pr_status = '$s_status'  ";
						else if((@$s_status=="")||(@$s_status=="2")||(@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6")) $str_query_pr .= "and  pr_status = '1'  ";
						else $str_query_pr .= "and  pr_status  in ('1','3','7') ";			
						$str_query_pr .= "and p.rec_user='$empno_user'  ";
						//echo 'xx1';
				}else if($choice_value == 'prcom'){
						if((@$s_status=="1")||(@$s_status=="2")||(@$s_status=="3")||(@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6")||(@$s_status=="7"))$str_query_pr .= "and   pr_status = '$s_status'  ";
						else if(@$s_status=="")$str_query_pr .= "and  pr_status = '1'  ";			
						$str_query_pr .= "and p.rec_user='$empno_user' ";
//echo 'xx2';						
				}else if($choice_value == 'prapp'){
				//echo 'xx3';
						if((@$s_status=="2")||(@$s_status=="3")||(@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6")||(@$s_status=="7"))$str_query_pr .= "and   pr_status = '$s_status'  ";
						else if((@$s_status=="")||(@$s_status=="1"))$str_query_pr .= "and  pr_status = '2'  ";			
						else $str_query_pr .= "and  pr_status  in ('2','3','4','5','6','7') ";			
						$str_query_pr .= "and p.mngno='$empno_user' ";						
				}else if($choice_value == 'prrep'){
				//echo 'xx4';
						if((@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6"))$str_query_pr .= "and   pr_status = '$s_status'  ";
						else if((@$s_status=="")||(@$s_status=="1")||(@$s_status=="2")||(@$s_status=="3")||(@$s_status=="7")) $str_query_pr .= "and  pr_status = '4'  ";			
						else $str_query_pr .= "and  pr_status  in ('4','5','6') ";			
				}
				$str_query_pr .= "order by  p.pr_date desc,pr_status,pr_no";	
				//echo  $str_query_pr;									
				$cur_query_pr = odbc_exec($conn,$str_query_pr);	
?>
<html>
<head>
		<title>**Search � PR **</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/check_date.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>				
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/button_add.js'></script>						
		<script language='javascript' src='../include/button_del.js'></script>				


</head>
<body  topmargin="0" leftmargin="0">
<br>
<center>

<form name="pr_search" method="post" action="pr_search.php">
<input name="flagAction" type="hidden" value="PushSearch">
  <table width="980"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
    <tr>
      <th width="750">&nbsp;&nbsp;���� PR <?php if(@$_SESSION["pr_type"]=="T") echo "Transtek"; else echo "Supreme"; ?></th>
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
                  <td width="166" class="tdleftwhite">�Ţ��� PR </td>
                  <td width="403">
				  <input name="s_pr_no" type="text" value="<?  echo @$s_pr_no; ?>"   onKeyDown="if(event.keyCode==13) document.pr_search.submit();">					</td>
                </tr>
                <tr>
                  <td class="tdleftwhite"><div align="right">                    &nbsp;&nbsp;&nbsp;
                  </div></td>
                  <td class="tdleftwhite">�ѹ����Դ PR </td>

                  <td>
				  <input name="s_start" type="text" id="s_start"  value="<?  echo @$s_start; ?>" size="10" maxlength="10" onBlur="return testdate(document.pr_search.s_start);"   onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
				  
				  �֧
				  <input name="s_stop" type="text" id="s_stop" value="<?  echo @$s_stop; ?>" size="10" maxlength="10"  onKeyDown="if(event.keyCode==13){ testdate(document.pr_search.s_stop); document.pr_search.submit(); }"> 
				  (�ٻẺ DD-MM-YYYY) </td>
				
                </tr>
                <tr>
                  <td class="tdleftwhite"><div align="right">                    &nbsp;&nbsp;&nbsp;
                  </div></td>
                  <td class="tdleftwhite">�����Դ PR </td>
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
                  <td class="tdleftwhite">ʶҹ�� PR </td>
                  <td>
<?				
				if(($choice_value == 'prup')||($choice_value == 'prdel')){
?>				
						<input name="s_status" type="radio" value="1" <? if((@$s_status=="1")||(@$s_status=="")||(@$s_status=="2")||(@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6")) echo "checked"; ?>>���᷹�����㺢ͫ���(�ѧ�����)
						<input name="s_status" type="radio" value="3" <? if(@$s_status=="3") echo "checked"; ?>>���Ѵ������͹��ѵ�㺢ͫ��� �����
						<input name="s_status" type="radio" value="7" <? if(@$s_status=="7") echo "checked"; ?>>MS �ա�Ѻ㺢ͫ���
						<input name="s_status" type="radio" value="all" <? if(@$s_status=="all") echo "checked"; ?>>������
<?
				}else if($choice_value == 'prcom'){
?>
						<input name="s_status" type="radio" value="1" <? if((@$s_status=="1")||(@$s_status=="")) echo "checked"; ?>>���᷹�����㺢ͫ���(�ѧ�����)
						<input name="s_status" type="radio" value="2" <? if(@$s_status=="2") echo "checked"; ?>>��͹��ѵԨҡ���Ѵ���
						<input name="s_status" type="radio" value="3" <? if(@$s_status=="3") echo "checked"; ?>>���Ѵ������͹��ѵ�㺢ͫ��� �����<br>
						<input name="s_status" type="radio" value="4" <? if(@$s_status=="4") echo "checked"; ?>>�� MS �͡ PO 
						<input name="s_status" type="radio" value="5" <? if(@$s_status=="5") echo "checked"; ?>>MS �͡ PO ���º�������� 
						<input name="s_status" type="radio" value="6" <? if(@$s_status=="6") echo "checked"; ?>>�Ѻ�Թ���������<br>
						<input name="s_status" type="radio" value="7" <? if(@$s_status=="7") echo "checked"; ?>>MS �ա�Ѻ㺢ͫ���
						<input name="s_status" type="radio" value="all" <? if(@$s_status=="all") echo "checked"; ?>>������
<?
				}else if($choice_value == 'prapp'){
?>
						<input name="s_status" type="radio" value="2" <? if((@$s_status=="2")||(@$s_status=="")||(@$s_status=="1")) echo "checked"; ?>>��͹��ѵԨҡ���Ѵ���
						<input name="s_status" type="radio" value="3" <? if(@$s_status=="3") echo "checked"; ?>>���Ѵ������͹��ѵ�㺢ͫ��� �����<br>
						<input name="s_status" type="radio" value="4" <? if(@$s_status=="4") echo "checked"; ?>>�� MS �͡ PO 
						<input name="s_status" type="radio" value="5" <? if(@$s_status=="5") echo "checked"; ?>>MS �͡ PO ���º�������� 
						<input name="s_status" type="radio" value="6" <? if(@$s_status=="6") echo "checked"; ?>>�Ѻ�Թ���������<br>
						<input name="s_status" type="radio" value="7" <? if(@$s_status=="7") echo "checked"; ?>>MS �ա�Ѻ㺢ͫ���
						<input name="s_status" type="radio" value="all" <? if(@$s_status=="all") echo "checked"; ?>>������
<?				
				}else if($choice_value == 'prrep'){
?>
						<input name="s_status" type="radio" value="4" <? if((@$s_status=="4")||(@$s_status=="")||(@$s_status=="1")||(@$s_status=="2")||(@$s_status=="3")||(@$s_status=="7")) echo "checked"; ?>>�� MS �͡ PO 
						<input name="s_status" type="radio" value="5" <? if(@$s_status=="5") echo "checked"; ?>>MS �͡ PO ���º�������� 
						<input name="s_status" type="radio" value="6" <? if(@$s_status=="6") echo "checked"; ?>>�Ѻ�Թ���������
						<input name="s_status" type="radio" value="all" <? if(@$s_status=="all") echo "checked"; ?>>������
<?				
				}
?>					</td>	
                </tr>
            </table>
			</td>
          </tr>
          <tr>
            <td>
              <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <th colspan="3"><div align="right"> <a 
						onMousedown="document.images['butsearch'].src=search3.src"   style="cursor:hand"
						onMouseup="document.images['butsearch'].src=search1.src"						
						 onMouseOver="document.images['butsearch'].src=search2.src" 
						 onMouseOut="document.images['butsearch'].src=search1.src" target="_blank"
						 onClick="document.pr_search.submit();"> 
						 <img src="../include/button/search1.gif" name="butsearch" width="106" height="24" border="0"> </a> </div></th>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</form>
<table width="980"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
  <tr>
    <th width="750">&nbsp;&nbsp;�š�ä���</th>
  </tr>
  <tr >
    <td>
      <table width="100%" border="0" align="center">
        <tr>
          <td>
            <table width="100%"  border="1" cellspacing="0" cellpadding="0" >
              <tr>
					<td width="50" class="tdcenterblack">
							<? if($choice_value == "prup") echo "Edit"; else  if($choice_value == "prdel") echo "Del"; else echo "Report"; ?>
					</td>
					<? if($choice_value == "prcom" || $empno_user=='20044' ) { ?>
								<td width="100" class="tdcenterblack">�觢��������<br>���Ѵ��� Approve</td>
					<? }else if($choice_value=="prapp"){ ?>
								<td width="50" class="tdcenterblack">Approve</td>
					<? }else if($choice_value=="prrep"){ ?>
								<td width="100" class="tdcenterblack">PO �����觫���</td>
								<td width="50" class="tdcenterblack">�ա�Ѻ<br>㺢ͫ���</td>				
								<td width="70" class="tdcenterblack">��Ѻʶҹ�</td>
					<? } ?>
					<td width="70" align="center"   class="tdcenterblack"><p>�Ţ��� PR </p></td>
					<td width="65" align="center"   class="tdcenterblack">�ѹ����Դ PR </td>
					<td   class="tdcenterblack"><p>Supplier</p></td>
					<td width="150"   class="tdcenterblack"><p class="tdcenterblack">ʶҹ�</p></td>
					<td width="50"   class="tdcenterblack">Reqno.</td>
					<td width="80"   class="tdcenterblack">PO �������觫���</td>
					<td width="65" align="center"   class="tdcenterblack">�ѹ���͹��ѵ� PR </td>
					
              </tr>
 <?
   if($flagAction == 'PushSearch'){
		while(odbc_fetch_row($cur_query_pr)){
			$pr_no = odbc_result($cur_query_pr, "pr_no");
			$pr_date = odbc_result($cur_query_pr, "pr_date");
			$pr_path = odbc_result($cur_query_pr, "pr_path");
			$company_name = odbc_result($cur_query_pr, "company_name");
			$pr_status = odbc_result($cur_query_pr, "pr_status");
			$flag_obj = odbc_result($cur_query_pr, "flag_obj");
			$obj_name2 = odbc_result($cur_query_pr, "obj_name2");
			$approve_date = odbc_result($cur_query_pr, "approve_date");
?>
			  <tr>
					<td>
						<div align="center">
									<? if($choice_value == 'prup') {	?>
														<a href="./prmas_edit.php?pr_no=<? echo $pr_no; ?>&flag=edit"  title="����������´">
																<img src="../include/images/edit_icon.png" border="0">
														</a>
									<? }else if($choice_value == 'prdel') { ?>
														<a onClick="remote_del('prmas_delcode.php?pr_no=<? echo $pr_no; ?>&flag=del&flag_obj=<?=$flag_obj;?>');"   style="cursor:hand">
																<img src="../include/images/del_icon.png" border="0">
														</a>
									<? }else if(($choice_value == 'prcom')||($choice_value == 'prrep')||($choice_value == 'prapp')) { ?>
														<a href="./prmas_reportcode.php?pr_no=<? echo $pr_no; ?>"   style="cursor:hand" target="_blank"  title="� PR">
																<img src="../include/images/report_icon.png" border="0">
														</a>
														<? if($pr_path != ""){ ?>
																<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\pr_thai\\<?= $pr_path; ?>');"  target="_blank"  style="cursor:hand"  title="㺡����Ըյ�Ǩ�Ѻ�Թ���">
																	<img src="../include/images/report_ico.gif" border="0">
																</a>
														<? } ?>
									<? } ?> 				
						</div>
					</td>
					<? if($choice_value == "prcom" || $empno_user=='20044'){ ?>
								<td>
										<? if($pr_status == '1'){ 	?>
												<div align="center">
													<a onClick="remote_confirm('pr_commitcode.php?pr_no=<? echo $pr_no; ?>');"   style="cursor:hand"><img src="../include/images/confirm_icon.png" border="0"></a>
												</div>
										<? }else echo "&nbsp;"; ?>
								</td>
					<? }else if($choice_value=="prapp"){	?>
								<td>
										<? if($pr_status == '2'){ ?>
												<div align="center">
													<a onClick="remote_approve('pr_approvecode.php?pr_no=<? echo $pr_no; ?>&flag=yes');"   style="cursor:hand"><img src="../include/images/confirm_icon.png" border="0"></a>
													<a onClick="remote_add('pr_approve.php?pr_no=<? echo $pr_no; ?>&flag=no',450,200);"   style="cursor:hand"><img src="../include/images/cancel_icon.png" border="0"></a>
												</div>												
										<? }else echo "&nbsp;"; ?>
								</td>
					<? }else if($choice_value=="prrep"){ ?>
								<td>
										<div align="center">
											<a onClick="remote_addscroll('pr_add_po.php?pr_no=<? echo $pr_no; ?>&flag=open',500,400);"   style="cursor:hand"><font color="red">�к� PO <br>�����觫����Թ������</font></a>
										</div>
								</td>
								<? if($pr_status=='4'){ ?>											
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<? echo $pr_no; ?>&flag_status=7');"   style="cursor:hand"><font color="red">�ա�Ѻ<br>
														㺢ͫ���</font></a>
											</div>
										</td>														
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<? echo $pr_no; ?>&flag_status=5');"   style="cursor:hand"><font color="red">�͡ PO <br>���º��������</font></a>
											</div>
										</td>														
								<? }else if($pr_status=='5'){ ?>											
										<td>
											<div align="center">&nbsp;</div>
										</td>														
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<? echo $pr_no; ?>&flag_status=6');"   style="cursor:hand"><font color="red">�Ѻ�Թ���������</font></a>
											</div>
										</td>
								<? }else{ ?>		
										<td>&nbsp;</td><td>&nbsp;</td>										
								<? } ?>												
					<? } ?>
					<td><div align="center">&nbsp;<? echo $pr_no;?></div></td>
					<td><div align="center">&nbsp;<? echo $pr_date;?></div></td>
					<td>&nbsp;<? echo $company_name;?></td>
					<td>&nbsp;<?
							if($pr_status=="1")echo "���᷹�����㺢ͫ���(�ѧ�����)";
							else if($pr_status=="2")echo "��͹��ѵԨҡ���Ѵ���";
							else if($pr_status=="3")echo "<font color=red>���Ѵ������͹��ѵ�㺢ͫ��� �����</font>";						
							else if($pr_status=="4")echo "�� MS �͡ PO";										
							else if($pr_status=="5")echo "MS �͡ PO ���º��������";										
							else if($pr_status=="6")echo "�Ѻ�Թ���������";										
							else if($pr_status=="7")echo "<font color=red>MS �ա�Ѻ㺢ͫ���</font>";										
					?></td>
					<td>
						<div align="center">
								<? if($flag_obj == "1"){
												$arrReqno = explode(",",$obj_name2);
												for($i=0;$i<count($arrReqno); $i++){
													echo "<a href='../include_report/requisition_supreme.php?reqno=".$arrReqno[$i]."' target='blank'>".$arrReqno[$i]."</a>,";
												}
									  }else echo "&nbsp;"; ?>				
						</div>
					</td>
					<td><?
							$po_noshow = '';
							$strPoHave = "select  po_no from pr_and_po where pr_no='$pr_no' ";
							$curPoHave = odbc_exec($conn,$strPoHave);	
		
							while(odbc_fetch_row($curPoHave)){
							$po_noshow = odbc_result($curPoHave, "po_no");
							if($empno_user=='04544')
									{
									?>
									<a onClick="remote_add('pomas_reportcode.php?doc_no=<? echo $po_noshow; ?>&doc_type=all');" style="cursor:hand"><font color="red"><?=$po_noshow; ?></font></a><br>
									<?
									}
									else
									{
									?>
									<a onClick="remote_add('pomas_reportcode.php?doc_no=<? echo $po_noshow; ?>&doc_type=nonprice');" style="cursor:hand"><font color="red"><?=$po_noshow; ?></font></a><br>
									<?
									}
							}
							if($po_noshow =='')  echo "&nbsp;"; 
							?>
					</td>	
					<td><div align="center">&nbsp;<? echo $approve_date;?></div></td>					
			  </tr>

 <?
		}
		}
?>

          </table></td>
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
			<table  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3" ><strong>�����˵� - ʶҹТͧ PR ������ </strong></td>
              </tr>
              <tr>
                <td width="300">
							<table width="100%">
											<tr>
											<td width="20">1</td>
											<td> ���᷹�����㺢ͫ���(�ѧ�����)</td>
										  </tr>
										  <tr>
											<td>2</td>
											<td>��͹��ѵԨҡ���Ѵ���</td>
										  </tr>
										  <tr>
											<td width="20">3</td>
											<td>���Ѵ������͹��ѵ�㺢ͫ��� �����</td>
										  </tr>										  
				  </table>				
				</td>
                <td width="300">
							<table width="100%">
										  <tr>
											<td>4</td>
											<td>�� MS �͡ PO</td>
										  </tr>							
										  <tr>
											<td width="20">5</td>
											<td>MS �͡ PO ���º��������</td>
										  </tr>
										  <tr>
											<td>6</td>
											<td>�Ѻ�Թ���������</td>
							  </tr>															  
				  </table>				
				</td>
                <td width="300">
							<table width="100%">
										  <tr>
											<td>7</td>
											<td>MS �ա�Ѻ㺢ͫ���</td>
							  </tr>															  
				  </table>								
				</td>
              </tr>
            </table>
			<script language="JavaScript" type="text/JavaScript">
							document.pr_search.s_pr_no.select();
			</script>
</center>
</body>
</html>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>			

<?
	}else{
			include("index.php");
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("�����¤�� �س�ѧ����� Login");';
			echo '</script>';
	}
?>

