<?
 @session_start();
if(session_is_registered("valid_userprpo")) {	
	require_once("../include_RedThemes/odbc_connect.php");
	//include("./_HelpShowDesc.php");
	$empno_user = $_SESSION["empno_user"];		
	$flagAction = "";
	$flagAction = @$_POST["flagAction"];		
	
	$head_po_no="";
	$head_id="";
	if($flagAction==""){
	$head_po_no=@$_GET["po_no"];
	$head_id=@$_GET["id"];	
	
	}else{
	
	$head_po_no = @$_POST["h_po_no"];	
	$head_id = @$_POST["h_id"];	
	$h_code= @$_POST["h_code"];	
	$h_prod_type= @$_POST["h_prod_type"];	
	$qty = @$_POST["qty"];
	$for = @$_POST["for"];		
	
    if($flagAction == 'AddCode'){  
	$curGarQty = @odbc_exec($conn,"select ISNULL(gar_qty,0) as Qty from po_details where po_no = '$head_po_no' and id = '$head_id' ");
	$GarQty = @odbc_result($curGarQty, "Qty");
	
	$curtotalQty = @odbc_exec($conn,"select ISNULL(sum(qty),0) as totalQty from po_details_job where po_no = '$head_po_no' and id = '$head_id' ");
	$totalQty = @odbc_result($curtotalQty, "totalQty");
	
	$remainQty = $GarQty-$totalQty;
	
	if(($remainQty > $qty) || ($remainQty == $qty) ){
	
	$curMAX = @odbc_exec($conn,"select  ISNULL(max(detail_id)+1,1) mx  from po_details_job ");
	$mx = @odbc_result($curMAX, "mx");
	
	$strINS = "insert into po_details_job (						
									detail_id, po_no,id,prod_type,code,qty,jobno,rec_user,rec_date
								)values(
									'$mx','$head_po_no','$head_id','$h_prod_type','$h_code','$qty','$for','$empno_user',getdate())"; 								
				$exeINS = @odbc_exec($conn,$strINS);
				//echo $strINS;
				
				if($exeINS){
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo 'window.location.reload("po_details_job.php?id='.$head_id.'&po_no='.$head_po_no.'");';
					echo '</script>';		
				}
	}
	else{
	    			echo '<script language="JavaScript" type="text/JavaScript">';
					echo '</script>';	
	}
	}elseif ( $flagAction == 'DelCode'){
	$checkboxdel = @$_POST["checkboxdel"];
	$i=0;
		while($i<count($checkboxdel)){
		$detail_id = $checkboxdel[$i];
		$strDEL = "delete from po_details_job where  detail_id='$detail_id'"; 
		$exeDEL = @odbc_exec($conn,$strDEL);
		$exeCOMMIT = odbc_exec($conn,"commit"); 
		$i++;
		}
							echo '<script language="JavaScript" type="text/JavaScript">';
					echo 'window.location.reload("po_details_job.php?id='.$head_id.'&po_no='.$head_po_no.'");';
					echo '</script>';	
	}// end $PushSubmit
    }
	
	$strQUEMaster = " select  po_no , id , code , prod_no ,prod_name , prod_qty , prod_unit , gar_qty , gar_unit ,prod_type
								from po_details 
								where  po_no = '$head_po_no' and id ='$head_id' ";
	$curQUEMaster = odbc_exec($conn, $strQUEMaster);
	
	$head_po_no=odbc_result($curQUEMaster, "po_no");
	$head_id =odbc_result($curQUEMaster, "id");
	$head_code =odbc_result($curQUEMaster, "code");
	$head_prod_no =odbc_result($curQUEMaster, "prod_no");
	$head_prod_name =odbc_result($curQUEMaster, "prod_name");
	$head_prod_qty =odbc_result($curQUEMaster, "prod_qty");
	$head_prod_unit =odbc_result($curQUEMaster, "prod_unit");
	$head_gar_qty =odbc_result($curQUEMaster, "gar_qty");
	$head_gar_unit =odbc_result($curQUEMaster, "gar_unit");
	$head_prod_type =odbc_result($curQUEMaster, "prod_type");
		$strQUEDetail =" 	
						select  pdj.detail_id, pdj.po_no , pdj.id , pdj.prod_type , pdj.code ,pdj.qty , pdj.jobno , pd.prod_no
						from po_details_job pdj inner join
						        po_details pd on pdj.po_no = pd.po_no and pdj.id = pd.id
						where  pdj.po_no = '$head_po_no' and pdj.id ='$head_id' 
 		";	

	//echo  $strQUEDetail;
	$curQUEDetail = odbc_exec($conn, $strQUEDetail);

?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language="javascript">
			function CheckOverIssue(limit,obj){
				if(!limit)limit = 0;	
				var count_qty = 0;				
			
				for(var i=0; i < obj.elements.length ; i++){ 
					if((obj.elements[i].name=="checkbox[]")&&(obj.elements[i].checked)){
						count_qty = count_qty + parseInt(obj.elements[i+1].value);
					}
				}
			
				if(count_qty > limit){
					return false;
				}else if(count_qty < limit){
					return true;
				}else{
					return true;
				}
			}
		</script>

	    <style type="text/css">
<!--
.style4 {color: #FF0000}
-->
        </style>
</head>
	<body> 

		<center>
			<form name="form1" method="post" action="po_details_job.php">
			
				<input name="h_po_no" type="hidden" value="<?= $head_po_no; ?>">
				<input name="h_id" type="hidden" value="<?= $head_id; ?>">
				<input name="h_code" type="hidden" value="<?= $head_code; ?>">
				<input name="h_prod_type" type="hidden" value="<?= $head_prod_type; ?>">
				
				<table width="720" border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
				<tr>
					<td colspan="3" class="fontMaster2Set">&nbsp;<?= $head_po_no; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="fontMaster2Set">&nbsp; TEST</td>
				</tr>
				<tr>
					<td width="180" class="fontMaster2Set">&nbsp;<?= $head_prod_no; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="fontMaster2Set">&nbsp;<?= $head_prod_name; ?></td>
				</tr>
				<tr>
					<td height="20" class="tdleftwhite">�ӹǹ�����觫���</td>
					<td class="fontMaster2Set">&nbsp;<? echo number_format($head_prod_qty,2,".",","); ?></td>
					<td width="100"><span class="tdleftwhite">�ӹǹ����ͧ�Ѻ���</span></td>
					<td class="fontMaster2Set">&nbsp;<? echo number_format($head_gar_qty ,2,".",","); ?></td>
				</tr>
				</table>		
				<table width="720"  border="0" cellspacing="0" cellpadding="0">
				<tr  class="tdleftblack" >			
				     <td width="20" >&nbsp;</td>
					<td width="160">�����Թ���</td>
					<td width="180">����Ѻ Job No.</td>
					<td width="150">�ӹǹ</td>
				</tr>
				</table>
				<div id="list_lot" style="  height:270px; width:720; overflow:auto; z-index=2;display:block;border-width:thin;border-style:dashed; border-color: #DCDCDC;">		  
					<table width="699"  border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="E9EAEB">
				<?
							$i=0;
							$class = 'trDetail2Set';	
							while(odbc_fetch_row($curQUEDetail)){ 
								$i++;
								$detail_detail_id=@odbc_result($curQUEDetail, "detail_id");	
								$detail_po_no =@odbc_result($curQUEDetail, "po_no");	
								$detail_id  =@odbc_result($curQUEDetail, "id");
								$detail_prod_type  =@odbc_result($curQUEDetail, "prod_type");
								$detail_code  =@odbc_result($curQUEDetail, "code");
								$detail_qty  =@odbc_result($curQUEDetail, "qty");
								$detail_jobno  =@odbc_result($curQUEDetail, "jobno");
								$detail_prod_no  =@odbc_result($curQUEDetail, "prod_no");
				?>
					<tr class="<?=$class;?>">
						<td width="20" ><input type="checkbox" name="checkboxdel[]" value="<?= $detail_detail_id; ?>"></td>
						<td width="160" >&nbsp;<?=$detail_prod_no;?></td>
						<td width="180" >&nbsp;<?=$detail_jobno;?></td>
						<td width="150" >&nbsp;<?=$detail_qty ;?></td>

					</tr>
				<?														
							if($class == 'trDetail2Set') $class = 'trDetail1Set';
							else  $class = 'trDetail2Set';
						}
				?>						
					</table>
				</div> 
				<table width="720"  border="0" cellspacing="0" cellpadding="0" bgcolor="E9EAEB" >
					<tr>
					  <td colspan="5" align="left"  class="tdleftblack" ><input type="button" name="Submit2" value="ź������" onClick=" if(chkCheckboxChoose(document.form1)){ document.form1.flagAction.value= 'DelCode'; document.form1.submit(); }"></td>
				  </tr>
					<tr>
					  <td colspan="5" align="center" bgcolor="E9EAEB" class="tdleftwhite" ><div align="center">��͡�����Ũӹǹ�Թ�������� Job No.</div>
				      </td>
				  </tr>
					<tr align="center">
						<td width="30%" align="left" bgcolor="E9EAEB"  >&nbsp;</td>
						<td width="25%" align="center" bgcolor="E9EAEB" class="tdleftwhite" > ����Ѻ Job No. </td>
						<td width="2%" align="center" bgcolor="E9EAEB"  >&nbsp; </td>
						<td width="25%" align="right" bgcolor="E9EAEB" class="tdleftwhite" >�ӹǹ</td>
						<td width="18%" align="center" bgcolor="E9EAEB"  >&nbsp;</td>
					</tr>
					<tr>
					  <td width="30%" align="left" bgcolor="E9EAEB"  >&nbsp;</td>
					  <td width="25%" align="center" bgcolor="E9EAEB"><input name="for" type="text" size="25" maxlength="100"></td>
					  <td width="2%" align="center" bgcolor="E9EAEB"  >&nbsp; </td>
					  <td width="25%"  align="center" bgcolor="E9EAEB"><input name="qty" type="text" onKeyDown="return chkNumberInput('float');" size="8" maxlength="8"></td>
					  <td width="18%" align="center" bgcolor="E9EAEB"  >&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="5"  align="center" bgcolor="E9EAEB">&nbsp;</td>
				  </tr>
					<tr>
					  <td width="18%" align="left" bgcolor="E9EAEB" class="fontMenu1Set" >&nbsp;</td>
				  </tr>
					<tr>
					  <td colspan="5"  align="center" bgcolor="E9EAEB" class="fontRemarkSet">&nbsp;</td>
				  </tr>
			  </table>
					<input name="flagAction" type="hidden" value="">					
			</form>	 
		</center>
	</body>
</html>
<?
}else{
	echo '<script language="JavaScript" type="text/JavaScript">';
	echo 'win= top;';
	echo 'win.opener=top;';
	echo 'win.location = "index.html";';
	echo '</script>';
}
?>








