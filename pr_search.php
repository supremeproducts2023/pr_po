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
<?php
  	@session_start();
	if(isset($_SESSION["valid_userprpo"])) {
				require_once("../include_RedThemes/MSSQLServer_connect_2.php");				
				$empno_user=$_SESSION["empno_user"];		
				$http_host = '172.10.0.16';
				$choice_value= $_SESSION["choice_value"];	

	include "../include_RedThemes/wait.php";
	flush();
				$s_empno = "";
				//========== เรื่องการ Search แล้วค้าง keyword ==================
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
				$str_query_pr = "select  p.pr_no,format(p.pr_date,'dd-MM-yyyy')  pr_date,p.pr_status,s.company_name,flag_obj,obj_name2,pr_path
										,format(p.approve_date,'dd-MM-yyyy') approve_date
											from pr_master p
											left join supplier s on p.supplier_id=s.supplier_id
											where 1 = 1 ";
				if(@$_SESSION["pr_type"]=="T")
					$str_query_pr .= " and p.flag_obj = '8' ";
				else if(@$_SESSION["pr_type"]=="S")	$str_query_pr .= " and p.flag_obj not in ('8') ";						
				if(@$s_pr_no != '') $str_query_pr .= "and   pr_no like upper('%$s_pr_no%')  ";			
				if(@$s_empno != '') $str_query_pr .= "and   empno like upper('%$s_empno%')  ";			
				if(@$s_start != '') $str_query_pr .= "and   format(pr_date,'dd-MM-yyyy') between format('$s_start','dd-MM-yyyy') and format('$s_stop','dd-MM-yyyy')  ";			
				
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
<br>
<center>

<form name="pr_search" method="post" action="pr_search.php">
<input name="flagAction" type="hidden" value="PushSearch">
  <table width="980"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
    <tr>
      <th width="750">&nbsp;&nbsp;ค้นหา PR <?php  if(@$_SESSION["pr_type"]=="T") echo "Transtek"; else echo "Supreme"; ?></th>
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
                  <td width="166" class="tdleftwhite">เลขที่ PR </td>
                  <td width="403">
				  <input name="s_pr_no" type="text" value="<?php  echo @$s_pr_no; ?>"   onKeyDown="if(event.keyCode==13) document.pr_search.submit();">					</td>
                </tr>
                <tr>
                  <td class="tdleftwhite"><div align="right">                    &nbsp;&nbsp;&nbsp;
                  </div></td>
                  <td class="tdleftwhite">วันที่เปิด PR </td>

                  <td>
				  <input name="s_start" type="text" id="s_start"  value="<?php  echo @$s_start; ?>" size="10" maxlength="10" onBlur="return testdate(document.pr_search.s_start);"   onKeyDown="if(event.keyCode==13) event.keyCode=9;"> 
				  
				  ถึง
				  <input name="s_stop" type="text" id="s_stop" value="<?php  echo @$s_stop; ?>" size="10" maxlength="10"  onKeyDown="if(event.keyCode==13){ testdate(document.pr_search.s_stop); document.pr_search.submit(); }"> 
				  (รูปแบบ dd-MM-yyyy) </td>
				
                </tr>
                <tr>
                  <td class="tdleftwhite"><div align="right">                    &nbsp;&nbsp;&nbsp;
                  </div></td>
                  <td class="tdleftwhite">ผู้ขอเปิด PR </td>
                  <td>
                  <?php
                  		$str = "select e_name from emp where empno = '".$s_empno."'";
						$cur_query = odbc_exec($conn,$str);
						$empno_show = odbc_result($cur_query, "e_name");
				  ?>
                  		<input name="empno" id="empno" type="text" value="<?php  echo @$s_empno; ?>"   size="10" maxlength="15" class="style_readonly" readonly="">
						<input name="empno_show" id="empno_show" type="text" value="<?php  echo $empno_show ?>"   size="50"  class="style_readonly" readonly="">
                        <img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovEmp();">
                        <img src="include/images/close.png" width="20" height="19" onClick="clearname();">
                  </td>
                </tr>
                <tr>
                  <td class="tdleftwhite">&nbsp;</td>
                  <td class="tdleftwhite">สถานะใบ PR </td>
                  <td>
<?php				
				if(($choice_value == 'prup')||($choice_value == 'prdel')){
?>				
						<input name="s_status" type="radio" value="1" <?php if((@$s_status=="1")||(@$s_status=="")||(@$s_status=="2")||(@$s_status=="4")||(@$s_status=="5")||(@$s_status=="6")) echo "checked"; ?>>ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)
						<input name="s_status" type="radio" value="3" <?php if(@$s_status=="3") echo "checked"; ?>>ผู้จัดการไม่อนุมัติใบขอซื้อ รอแก้ไข
						<input name="s_status" type="radio" value="7" <?php if(@$s_status=="7") echo "checked"; ?>>MS ตีกลับใบขอซื้อ
						<input name="s_status" type="radio" value="all" <?php if(@$s_status=="all") echo "checked"; ?>>ทั้งหมด
<?php
				}else if($choice_value == 'prcom'){
?>
						<input name="s_status" type="radio" value="1" <?php if((@$s_status=="1")||(@$s_status=="")) echo "checked"; ?>>ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)
						<input name="s_status" type="radio" value="2" <?php if(@$s_status=="2") echo "checked"; ?>>รออนุมัติจากผู้จัดการ
						<input name="s_status" type="radio" value="3" <?php if(@$s_status=="3") echo "checked"; ?>>ผู้จัดการไม่อนุมัติใบขอซื้อ รอแก้ไข<br>
						<input name="s_status" type="radio" value="4" <?php if(@$s_status=="4") echo "checked"; ?>>รอ MS ออก PO 
						<input name="s_status" type="radio" value="5" <?php if(@$s_status=="5") echo "checked"; ?>>MS ออก PO เรียบร้อยแล้ว 
						<input name="s_status" type="radio" value="6" <?php if(@$s_status=="6") echo "checked"; ?>>รับสินค้าได้แล้ว<br>
						<input name="s_status" type="radio" value="7" <?php if(@$s_status=="7") echo "checked"; ?>>MS ตีกลับใบขอซื้อ
						<input name="s_status" type="radio" value="all" <?php if(@$s_status=="all") echo "checked"; ?>>ทั้งหมด
<?php
				}else if($choice_value == 'prapp'){
?>
						<input name="s_status" type="radio" value="2" <?php if((@$s_status=="2")||(@$s_status=="")||(@$s_status=="1")) echo "checked"; ?>>รออนุมัติจากผู้จัดการ
						<input name="s_status" type="radio" value="3" <?php if(@$s_status=="3") echo "checked"; ?>>ผู้จัดการไม่อนุมัติใบขอซื้อ รอแก้ไข<br>
						<input name="s_status" type="radio" value="4" <?php if(@$s_status=="4") echo "checked"; ?>>รอ MS ออก PO 
						<input name="s_status" type="radio" value="5" <?php if(@$s_status=="5") echo "checked"; ?>>MS ออก PO เรียบร้อยแล้ว 
						<input name="s_status" type="radio" value="6" <?php if(@$s_status=="6") echo "checked"; ?>>รับสินค้าได้แล้ว<br>
						<input name="s_status" type="radio" value="7" <?php if(@$s_status=="7") echo "checked"; ?>>MS ตีกลับใบขอซื้อ
						<input name="s_status" type="radio" value="all" <?php if(@$s_status=="all") echo "checked"; ?>>ทั้งหมด
<?php				
				}else if($choice_value == 'prrep'){
?>
						<input name="s_status" type="radio" value="4" <?php if((@$s_status=="4")||(@$s_status=="")||(@$s_status=="1")||(@$s_status=="2")||(@$s_status=="3")||(@$s_status=="7")) echo "checked"; ?>>รอ MS ออก PO 
						<input name="s_status" type="radio" value="5" <?php if(@$s_status=="5") echo "checked"; ?>>MS ออก PO เรียบร้อยแล้ว 
						<input name="s_status" type="radio" value="6" <?php if(@$s_status=="6") echo "checked"; ?>>รับสินค้าได้แล้ว
						<input name="s_status" type="radio" value="all" <?php if(@$s_status=="all") echo "checked"; ?>>ทั้งหมด
<?php				
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
    <th width="750">&nbsp;&nbsp;ผลการค้นหา</th>
  </tr>
  <tr >
    <td>
      <table width="100%" border="0" align="center">
        <tr>
          <td>
            <table width="100%"  border="1" cellspacing="0" cellpadding="0" >
              <tr>
					<td width="50" class="tdcenterblack">
							<?php if($choice_value == "prup") echo "Edit"; else  if($choice_value == "prdel") echo "Del"; else echo "Report"; ?>
					</td>
					<?php if($choice_value == "prcom" || $empno_user=='20044' ) { ?>
								<td width="100" class="tdcenterblack">ส่งข้อมูลให้<br>ผู้จัดการ Approve</td>
					<?php }else if($choice_value=="prapp"){ ?>
								<td width="50" class="tdcenterblack">Approve</td>
					<?php }else if($choice_value=="prrep"){ ?>
								<td width="100" class="tdcenterblack">PO ที่สั่งซื้อ</td>
								<td width="50" class="tdcenterblack">ตีกลับ<br>ใบขอซื้อ</td>				
								<td width="70" class="tdcenterblack">ปรับสถานะ</td>
					<?php } ?>
					<td width="70" align="center"   class="tdcenterblack"><p>เลขที่ PR </p></td>
					<td width="65" align="center"   class="tdcenterblack">วันที่เปิด PR </td>
					<td   class="tdcenterblack"><p>Supplier</p></td>
					<td width="150"   class="tdcenterblack"><p class="tdcenterblack">สถานะ</p></td>
					<td width="50"   class="tdcenterblack">Reqno.</td>
					<td width="80"   class="tdcenterblack">PO ที่ใช้สั่งซื้อ</td>
					<td width="65" align="center"   class="tdcenterblack">วันที่อนุมัติ PR </td>
					
              </tr>
 <?php
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
									<?php if($choice_value == 'prup') {	?>
														<a href="./prmas_edit.php?pr_no=<?php echo $pr_no; ?>&flag=edit"  title="ดูรายละเอียด">
																<img src="../include/images/edit_icon.png" border="0">
														</a>
									<?php }else if($choice_value == 'prdel') { ?>
														<a onClick="remote_del('prmas_delcode.php?pr_no=<?php echo $pr_no; ?>&flag=del&flag_obj=<?=$flag_obj;?>');"   style="cursor:hand">
																<img src="../include/images/del_icon.png" border="0">
														</a>
									<?php }else if(($choice_value == 'prcom')||($choice_value == 'prrep')||($choice_value == 'prapp')) { ?>
														<a href="./prmas_reportcode.php?pr_no=<?php echo $pr_no; ?>"   style="cursor:hand" target="_blank"  title="ใบ PR">
																<img src="../include/images/report_icon.png" border="0">
														</a>
														<?php if($pr_path != ""){ ?>
																<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\pr_thai\\<?= $pr_path; ?>');"  target="_blank"  style="cursor:hand"  title="ใบกรรมวิธีตรวจรับสินค้า">
																	<img src="../include/images/report_ico.gif" border="0">
																</a>
														<?php } ?>
									<?php } ?> 				
						</div>
					</td>
					<?php if($choice_value == "prcom" || $empno_user=='20044'){ ?>
								<td>
										<?php if($pr_status == '1'){ 	?>
												<div align="center">
													<a onClick="remote_confirm('pr_commitcode.php?pr_no=<?php echo $pr_no; ?>');"   style="cursor:hand"><img src="../include/images/confirm_icon.png" border="0"></a>
												</div>
										<?php }else echo "&nbsp;"; ?>
								</td>
					<?php }else if($choice_value=="prapp"){	?>
								<td>
										<?php if($pr_status == '2'){ ?>
												<div align="center">
													<a onClick="remote_approve('pr_approvecode.php?pr_no=<?php echo $pr_no; ?>&flag=yes');"   style="cursor:hand"><img src="../include/images/confirm_icon.png" border="0"></a>
													<a onClick="remote_add('pr_approve.php?pr_no=<?php echo $pr_no; ?>&flag=no',450,200);"   style="cursor:hand"><img src="../include/images/cancel_icon.png" border="0"></a>
												</div>												
										<?php }else echo "&nbsp;"; ?>
								</td>
					<?php }else if($choice_value=="prrep"){ ?>
								<td>
										<div align="center">
											<a onClick="remote_addscroll('pr_add_po.php?pr_no=<?php echo $pr_no; ?>&flag=open',500,400);"   style="cursor:hand"><font color="red">ระบุ PO <br>ที่สั่งซื้อสินค้าให้</font></a>
										</div>
								</td>
								<?php if($pr_status=='4'){ ?>											
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<?php echo $pr_no; ?>&flag_status=7');"   style="cursor:hand"><font color="red">ตีกลับ<br>
														ใบขอซื้อ</font></a>
											</div>
										</td>														
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<?php echo $pr_no; ?>&flag_status=5');"   style="cursor:hand"><font color="red">ออก PO <br>เรียบร้อยแล้ว</font></a>
											</div>
										</td>														
								<?php }else if($pr_status=='5'){ ?>											
										<td>
											<div align="center">&nbsp;</div>
										</td>														
										<td>
											<div align="center">
														<a onClick="remote_do('pr_closecode.php?pr_no=<?php echo $pr_no; ?>&flag_status=6');"   style="cursor:hand"><font color="red">รับสินค้าได้แล้ว</font></a>
											</div>
										</td>
								<?php }else{ ?>		
										<td>&nbsp;</td><td>&nbsp;</td>										
								<?php } ?>												
					<?php } ?>
					<td><div align="center">&nbsp;<?php echo $pr_no;?></div></td>
					<td><div align="center">&nbsp;<?php echo $pr_date;?></div></td>
					<td>&nbsp;<?php echo iconv( "windows-874", "utf-8" ,$company_name);?></td>
					<td>&nbsp;<?
							if($pr_status=="1")echo "ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)";
							else if($pr_status=="2")echo "รออนุมัติจากผู้จัดการ";
							else if($pr_status=="3")echo "<font color=red>ผู้จัดการไม่อนุมัติใบขอซื้อ รอแก้ไข</font>";						
							else if($pr_status=="4")echo "รอ MS ออก PO";										
							else if($pr_status=="5")echo "MS ออก PO เรียบร้อยแล้ว";										
							else if($pr_status=="6")echo "รับสินค้าได้แล้ว";										
							else if($pr_status=="7")echo "<font color=red>MS ตีกลับใบขอซื้อ</font>";										
					?></td>
					<td>
						<div align="center">
								<?php if($flag_obj == "1"){
												$arrReqno = explode(",",$obj_name2);
												for($i=0;$i<count($arrReqno); $i++){
													echo "<a href='../include_report/requisition_supreme.php?reqno=".$arrReqno[$i]."' target='blank'>".$arrReqno[$i]."</a>,";
												}
									  }else echo "&nbsp;"; ?>				
						</div>
					</td>
					<td><?php
							$po_noshow = '';
							$strPoHave = "select  po_no from pr_and_po where pr_no='$pr_no' ";
							$curPoHave = odbc_exec($conn,$strPoHave);	
		
							while(odbc_fetch_row($curPoHave)){
							$po_noshow = odbc_result($curPoHave, "po_no");
							if($empno_user=='04544')
									{
									?>
									<a onClick="remote_add('pomas_reportcode.php?doc_no=<?php echo $po_noshow; ?>&doc_type=all');" style="cursor:hand"><font color="red"><?=$po_noshow; ?></font></a><br>
									<?php
									}
									else
									{
									?>
									<a onClick="remote_add('pomas_reportcode.php?doc_no=<?php echo $po_noshow; ?>&doc_type=nonprice');" style="cursor:hand"><font color="red"><?=$po_noshow; ?></font></a><br>
									<?php
									}
							}
							if($po_noshow =='')  echo "&nbsp;"; 
							?>
					</td>	
					<td><div align="center">&nbsp;<?php echo $approve_date;?></div></td>					
			  </tr>

 <?php
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
                <td colspan="3" ><strong>หมายเหตุ - สถานะของ PR ทั้งหมด </strong></td>
              </tr>
              <tr>
                <td width="300">
							<table width="100%">
											<tr>
											<td width="20">1</td>
											<td> ผู้แทนพิมพ์ใบขอซื้อ(ยังไม่ส่ง)</td>
										  </tr>
										  <tr>
											<td>2</td>
											<td>รออนุมัติจากผู้จัดการ</td>
										  </tr>
										  <tr>
											<td width="20">3</td>
											<td>ผู้จัดการไม่อนุมัติใบขอซื้อ รอแก้ไข</td>
										  </tr>										  
				  </table>				
				</td>
                <td width="300">
							<table width="100%">
										  <tr>
											<td>4</td>
											<td>รอ MS ออก PO</td>
										  </tr>							
										  <tr>
											<td width="20">5</td>
											<td>MS ออก PO เรียบร้อยแล้ว</td>
										  </tr>
										  <tr>
											<td>6</td>
											<td>รับสินค้าได้แล้ว</td>
							  </tr>															  
				  </table>				
				</td>
                <td width="300">
							<table width="100%">
										  <tr>
											<td>7</td>
											<td>MS ตีกลับใบขอซื้อ</td>
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
<?php
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>			

<?php
	}else{
			include("index.php");
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("ขออภัยค่ะ คุณยังไม่ได้ Login");';
			echo '</script>';
	}
?>

