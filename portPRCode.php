<?php
	@session_start();
	if (session_is_registered("valid_userprpo")) {
		require_once("../include/odbc_connect.php");	
		require_once("../include/alert.php");	
		
		function cvtDate($DATE)
		{
			include("../include/odbc_connect.php");	
			$strSQL = "select to_char(to_date('$DATE','mm/dd/yyyy'),'dd-mm-yyyy') cDATE from dual";
			$strResult = @odbc_exec($conn,$strSQL) or die(alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�����żŢ�����㹰ҹ����������"));
			return @odbc_result($strResult,"cDATE");																						
		}
		function emp($name)
		{
			include("../include/odbc_connect.php");	
			$strSQL = "select empno from emp where trim(upper(e_name)) like trim(upper('$name'))";
			$strResult = @odbc_exec($conn,$strSQL) or die(alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�����żŢ�����㹰ҹ����������"));
			return @odbc_result($strResult,"empno");																				
		}
		
		if(@$_POST["flag"]=="Submit")
		{	
			$REC_USER = $_SESSION["empno_user"];
			$PR_NO = $_POST["PR_NO"];
			$PR_DATE = $_POST["PR_DATE"];
			$EMPNO = $_POST["EMPNO"];
			$DEPTNO = $_POST["DEPTNO"];
			$PAYCODE = $_POST["PAYCODE"];
			$PR_PAYMENT = $_POST["PR_PAYMENT"];
			$FLAG_OBJ = $_POST["FLAG_OBJ"];
			$OBJ_NAME1 = $_POST["OBJ_NAME1"];
			$ESTIMATE_DAY = $_POST["ESTIMATE_DAY"];
			$SUPPLIER_ID = $_POST["SUPPLIER_ID"];
			$PR_REMARK = $_POST["PR_REMARK"];
			$MNGNO = $_POST["MNGNO"];
			$PR_STATUS = $_POST["PR_STATUS"];
			$VAT_INCLUDE = $_POST["VAT_INCLUDE"];
			$NUMBER = $_POST["NUMBER"];
			if(!($PR_NO=="" || $DEPTNO=="" || $PR_DATE=="" || $PAYCODE=="" || $OBJ_NAME1=="" || $ESTIMATE_DAY=="" || $EMPNO=="" || $MNGNO==""))
			{				
				$strSQL =   "insert into pr_master(PR_NO,PR_DATE,DEPTNO,EMPNO,MNGNO,PR_PAYMENT,FLAG_OBJ,OBJ_NAME1,
									ESTIMATE_DAY,SUPPLIER_ID,PR_REMARK,PR_STATUS,REC_USER,REC_DATE,VAT_INCLUDE,PAYCODE)
									values('$PR_NO',to_date('$PR_DATE','dd-mm-yyyy'),'$DEPTNO','$EMPNO','$MNGNO','$PR_PAYMENT','$FLAG_OBJ','$OBJ_NAME1',
									'$ESTIMATE_DAY','$SUPPLIER_ID','$PR_REMARK','$PR_STATUS','$REC_USER',getdate(),'$VAT_INCLUDE','$PAYCODE')";
				$strResult1 = @odbc_exec($conn,$strSQL);													
				for($i=1;$i<=$NUMBER;$i++)
				{
					$ID = $_POST["ID".$i];
					$PROD_NO = $_POST["PROD_NO".$i];
					$PROD_UNIT = $_POST["PROD_UNIT".$i];
					$PROD_QTY = $_POST["PROD_QTY".$i];
					$PROD_PRICE = $_POST["PROD_PRICE".$i];
										if($PROD_PRICE =="" )
										{
											$PROD_PRICE = "0";
										}
					$strSQL =   "insert into pr_details(PR_NO,ID,PROD_NO,PROD_PRICE,discount_percent,discount_baht,
										PROD_QTY,PROD_UNIT,REC_USER,REC_DATE)
										values('$PR_NO','$ID','$PROD_NO','$PROD_PRICE','0','0',
										'$PROD_QTY','$PROD_UNIT','$REC_USER',getdate())";
					$strResult2 = @odbc_exec($conn,$strSQL);					
				}
				if($strResult1 && $strResult2)
				{																															
					@odbc_exec($conn,"commit");										
					alert("�ѹ�֡ PR �Ţ��� $PR_NO ŧ㹰ҹ���������º�������Ǥ��");
					$_SESSION["pr_type"] = "T";
					$_SESSION["choice_value"] = "prup";
					echo '<script language="javascript"  type="text/javascript">
								 window.parent.location.replace("pr_pagesearch.php?pr_no='.$PR_NO.'&flag=edit");	 
							</script>';	
				}else{
							@odbc_exec($conn,"rollback");
							$strSQL = "delete from pr_master where pr_no = '$PR_NO'";						
							@odbc_exec($conn,$strSQL);
							$strSQL = "delete from pr_details where pr_no = '$PR_NO'";						
							@odbc_exec($conn,$strSQL);
							@odbc_exec($conn,"commit");
							alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�ѹ�֡ PR ���ŧ㹰ҹ����������");
				}
			}else{
				alert("�������ö�ѹ�֡ PR ���ŧ㹰ҹ���������� \\n���ͧ�ҡ�Ҵ��������ǹ�Ӥѭ (��ͧ��� highlight �����ժ���) ���");
			}	
		}
	$target_path = "C:\\WebServ\\webroot\\temp_report\\";
	$file_name = "excel_PRtranstek.xls";
	$ExcelApp = new COM("Excel.Application");
	$ExcelWorkBook = $ExcelApp->Workbooks->Open($target_path.$file_name);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<style type="text/css">
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	line-height: 25px;
	color: #000000;
	text-align:left;
	vertical-align:top;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	line-height: 25px;
	color: #000000;
	text-align:left;
	vertical-align:top;
}
.listFindShow{
	overflow:auto; 
	z-index=2;
	display:block;
	border-width:thin;
	border-style:dashed; 
	border-color: #3399FF;
}
.style4 {
	color: #0033FF;
	font-weight: bold;
}
</style>
</head>
<body onload="this.moveTo(0,0); resizeTo(screen.availWidth,screen.availHeight);">
<form name="form1" action="portPRCode.php" method="post">
<input type="hidden" name="flag" id="flag" value="Submit" />
<table><tr><td>
<div style="width:980px; height:450px;" class="listFindShow">
<?php
//  Generate Primary key   PR YY xxxxx //
	$str_int_year = "select substr(to_char(getdate(),'YYYY')+543,3,2) int_year from dual";			
	$cur_int_year = @odbc_exec($conn,$str_int_year);
	$int_year = @odbc_result($cur_int_year, "int_year");
						
	$str_mx = "select ISNULL(max(substr(pr_no,5,5))+1,1) int_mx from pr_master ";
	$str_mx = $str_mx."where substr(pr_no,3,2) = '".$int_year."'";
	$cur_mx = @odbc_exec($conn,$str_mx);
	$int_mx = @odbc_result($cur_mx, "int_mx");
							
	if ($int_mx >= 10000) $str_middle = '';
	else if ($int_mx >= 1000) $str_middle = '0';
	else if ($int_mx >= 100) $str_middle = '00';
	else if ($int_mx >= 10) $str_middle = '000';
	else $str_middle = '0000';				
	
	$PR_NO = "PR" . $int_year . $str_middle . $int_mx;																									//PR_NO
//  End Generate  //

	$ExcelSheet = $ExcelWorkBook->Worksheets(1);	
	echo "<br><table width='900' border = '0' align='center' cellpadding='0' cellspacing='0' style='font-size:26px'>";	
	echo "<tr><th colspan='3'><div align='center'>".$ExcelSheet->Cells(1,1)."</div></th></tr>";
	echo "<tr><th colspan='3'><div align='center'>".$ExcelSheet->Cells(2,1)."</div></th></tr>";
	echo "<tr><th colspan='3'>&nbsp;</th></tr>";
	echo "<tr><td colspan='2'><div align='right'><b>".$ExcelSheet->Cells(4,5)."&nbsp;</b></div></td><td width='300' style='color:#0000FF'>&nbsp;$PR_NO</td></tr>";
	$pr_date = $ExcelSheet->Cells(5,6);
	$pr_date = (string)$pr_date;
	$PR_DATE =$pr_date; //cvtDate($pr_date);																															//PR_DATE
	if($PR_DATE=="") 
		echo "<tr><td colspan='2'><div align='right'><b>".$ExcelSheet->Cells(5,5)."&nbsp;</b></div></td><td bgcolor='#FFCCFF'>&nbsp;$PR_DATE</td></tr>";	
	else echo "<tr><td colspan='2'><div align='right'><b>".$ExcelSheet->Cells(5,5)."&nbsp;</b></div></td><td>&nbsp;$PR_DATE</td></tr>";
	echo "<tr><th colspan='3'>&nbsp;</th></tr>";
	$empno_show = $ExcelSheet->Cells(220,3);
	$empno_show = (string)$empno_show;
	$strSQL = "select empno,e_name from emp where empno = '$empno_show'";
	$strResult = @odbc_exec($conn,$strSQL) or die(alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�����żŢ�����㹰ҹ����������"));
	$EMPNO = @odbc_result($strResult,"empno");																										//EMPNO
	$e_name = @odbc_result($strResult,"e_name");
	$strSQL = "select d.deptno,d.deptname from emp e, dept d where e.empno = '$EMPNO' and e.deptno = d.deptno";
	$strResult = @odbc_exec($conn,$strSQL) or die(alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�����żŢ�����㹰ҹ����������"));
	$DEPTNO = @odbc_result($strResult,"deptno");																										//DEPTNO
	$deptname = @odbc_result($strResult,"deptname");								
	if($DEPTNO=="")
		echo "<tr><td width='50'>&nbsp;<b>".$ExcelSheet->Cells(7,1)."</b>&nbsp;</td><td width='550' bgcolor='#FFCCFF'>$deptname</td><td width='300'><b>&nbsp;".$ExcelSheet->Cells(7,5)."</b></td></tr>";
	else echo "<tr><td width='50'>&nbsp;<b>".$ExcelSheet->Cells(7,1)."</b>&nbsp;</td><td width='550' style='color:#0000FF'>$deptname</td><td width='300'><b>&nbsp;".$ExcelSheet->Cells(7,5)."</b></td></tr>";
	echo "<tr><th colspan='3'>&nbsp;</th></tr>";
	echo "<tr><td colspan='3'><table width='900' border = '1' align='center' cellpadding='0' cellspacing='0'>";
	echo "<tr><td width='50'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,1)."</b></div></td>";
	echo "<td width='350'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,2)."</b></div></td>";
	echo "<td width='110'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,3)."</b></div></td>";
	echo "<td width='130'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,4)."</b></div></td>";
	echo "<td width='130'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,5)."</b></div></td>";
	echo "<td width='130'><div align='center' style='font-size:12px'><b>".$ExcelSheet->Cells(9,6)."</b></div></td></tr>";
	$number=0;
	for($i=10;$i<=209;$i++){
		$PROD_NO = $ExcelSheet->Cells($i,2);
		$PROD_NO = (string)$PROD_NO;
		$PROD_UNIT = $ExcelSheet->Cells($i,3);
		$PROD_UNIT = (string)$PROD_UNIT;
		$PROD_QTY = $ExcelSheet->Cells($i,4);
		$PROD_QTY = (float)$PROD_QTY;
		
		$PROD_PRICE = $ExcelSheet->Cells($i,5);
		if($PROD_PRICE =="" )
		{
			$PROD_PRICE = "0";
		}
		$PROD_PRICE = (float)$PROD_PRICE;
		$total = $PROD_QTY*$PROD_PRICE;		
		
		if($PROD_NO!="" && $PROD_QTY!="" ){			
			++$number;		
			echo '<input type="hidden" name="ID'.$number.'" value="'.$number.'" />';	
	  		echo '<input type="hidden" name="PROD_NO'.$number.'" value="'.$PROD_NO.'" />';
	  		echo '<input type="hidden" name="PROD_UNIT'.$number.'" value="'.$PROD_UNIT.'" />';
			echo '<input type="hidden" name="PROD_QTY'.$number.'" value="'.$PROD_QTY.'" />';
			echo '<input type="hidden" name="PROD_PRICE'.$number.'" value="'.$PROD_PRICE.'" />';
		    echo "<tr><td><div align='center' style='font-size:12px'>".$number."</div></td>";
			echo "<td><div align='left' style='font-size:12px'>&nbsp;".$PROD_NO."</div></td>";
			echo "<td><div align='center' style='font-size:12px'>&nbsp;".$PROD_UNIT."</div></td>";
			echo "<td><div align='right' style='font-size:12px'>".number_format($PROD_QTY,2,".",",")."&nbsp;</div></td>";
			echo "<td><div align='right' style='font-size:12px'>".number_format($PROD_PRICE,2,".",",")."&nbsp;</div></td>";
			echo "<td><div align='right' style='font-size:12px'>".number_format($total,2,".",",")."&nbsp;</div></td></tr>";
		}		
	}	
	echo "</table></td></tr><tr><td colspan='3'><br>";
	echo "<table width='900' border = '1' align='center' cellpadding='0' cellspacing='0'>";
	$PAYCODE = $ExcelSheet->Cells(212,3);																																			//PAYCODE
	$PAYCODE = (string)$PAYCODE;
	$PR_PAYMENT = $ExcelSheet->Cells(212,5);																																	//PR_PAYMENT
	$PR_PAYMENT = (string)$PR_PAYMENT;	
	if($PAYCODE=="")
		echo "<tr><td width='150'><b>&nbsp;".$ExcelSheet->Cells(212,2)."</b></td><td width='200' bgcolor='#FFCCFF'>&nbsp;".$PAYCODE."</td><td width='550'>&nbsp;".$PR_PAYMENT."</td></tr>";
	else 	echo "<tr><td width='150'><b>&nbsp;".$ExcelSheet->Cells(212,2)."</b></td><td width='200'>&nbsp;".$PAYCODE."</td><td width='550'>&nbsp;".$PR_PAYMENT."</td></tr>";
	$FLAG_OBJ = "8";																																				//FLAG_OBJ																														
	$OBJ_NAME1 = $ExcelSheet->Cells(213,3);																																		//OBJ_NAME1
	$OBJ_NAME1 = (string)$OBJ_NAME1;	
	if($OBJ_NAME1=="")
		echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(213,2)."</b></td><td colspan='2' bgcolor='#FFCCFF'>&nbsp;".$OBJ_NAME1."</td></tr>";
	else	echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(213,2)."</b></td><td colspan='2'>&nbsp;".$OBJ_NAME1."</td></tr>";
	$ESTIMATE_DAY = $ExcelSheet->Cells(214,3);																																	//ESTIMATE_DAY
	$ESTIMATE_DAY = (string)$ESTIMATE_DAY;	
	if($ESTIMATE_DAY=="")
		echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(214,2)."</b></td><td bgcolor='#FFCCFF'><div align='center'>&nbsp;".$ESTIMATE_DAY."</div></td><td><b>&nbsp;�ѹ</b></td></tr>";
	else	echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(214,2)."</b></td><td><div align='center'>&nbsp;".$ESTIMATE_DAY."</div></td><td><b>&nbsp;�ѹ</b></td></tr>";
	$supplier_name = $ExcelSheet->Cells(215,3);
	$supplier_name = (string)$supplier_name;	
	$strSQL = "select supplier_id from supplier where upper(trim(company_name)) like upper(trim('$supplier_name'))";
	$strResult = @odbc_exec($conn,$strSQL);
	$SUPPLIER_ID = @odbc_result($strResult,"supplier_id");																													//SUPPLIER_ID
	if($SUPPLIER_ID=="")
		echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(215,2)."</b></td><td colspan='2' bgcolor='#FFFF99'>&nbsp;".$supplier_name."</td></tr>";
	else	echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(215,2)."</b></td><td colspan='2'>&nbsp;".$supplier_name."</td></tr>";
	$PR_REMARK = $ExcelSheet->Cells(216,3);
	$PR_REMARK = (string)$PR_REMARK;																																				//PR_REMARK
	echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(216,2)."</b></td><td colspan='2'>&nbsp;".$PR_REMARK."</td></tr>";
	echo "</td></tr></table><br><table width='900' border = '1' align='center' cellpadding='0' cellspacing='0'>";
	if($EMPNO=="")
		echo "<tr><td width='150'><b>&nbsp;".$ExcelSheet->Cells(220,2)."</b></td><td bgcolor='#FFCCFF' width='150'>&nbsp;".$EMPNO."</td><td width='600'>&nbsp;$e_name</td></tr>";
	else	echo "<tr><td width='150'><b>&nbsp;".$ExcelSheet->Cells(220,2)."</b></td><td width='150'>&nbsp;".$EMPNO."</td><td width='600'>&nbsp;$e_name</td></tr>";
	$mng_name = $ExcelSheet->Cells(221,3);
	$mng_name = (string)$mng_name;	
	$MNGNO = emp($mng_name);																																						//MNG_NO
	if($MNGNO=="")
		echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(221,2)."</b></td><td colspan='2' bgcolor='#FFCCFF'>&nbsp;".$mng_name."</td></tr>";
	else	echo "<tr><td><b>&nbsp;".$ExcelSheet->Cells(221,2)."</b></td><td colspan='2'>&nbsp;".$mng_name."</td></tr>";
	echo "</table><br></td></tr></table>";
	
	$ExcelWorkBook->Close();
    $ExcelApp->Quit();
	unset($ExcelApp,$ExcelWorkBook,$ExcelSheet);  
?>
</div>
<br /><br />
<div align="center" style="width:980px;">
<input type="hidden" name="PR_NO" value="<?= $PR_NO; ?>" />
<input type="hidden" name="PR_DATE" value="<?= $PR_DATE; ?>" />
<input type="hidden" name="EMPNO" value="<?= $EMPNO; ?>" />
<input type="hidden" name="DEPTNO" value="<?= $DEPTNO; ?>" />
<input type="hidden" name="PAYCODE" value="<?= $PAYCODE; ?>" />
<input type="hidden" name="PR_PAYMENT" value="<?= $PR_PAYMENT; ?>" />
<input type="hidden" name="FLAG_OBJ" value="<?= $FLAG_OBJ; ?>" />
<input type="hidden" name="OBJ_NAME1" value="<?= $OBJ_NAME1; ?>" />
<input type="hidden" name="ESTIMATE_DAY" value="<?= $ESTIMATE_DAY; ?>" />
<input type="hidden" name="SUPPLIER_ID" value="<?= $SUPPLIER_ID; ?>" />
<input type="hidden" name="PR_REMARK" value="<?= $PR_REMARK; ?>" />
<input type="hidden" name="MNGNO" value="<?= $MNGNO; ?>" />
<input type="hidden" name="PR_STATUS" value="1" />
<input type="hidden" name="VAT_INCLUDE" value="1" />
<input type="hidden" name="NUMBER" value="<?=$number;?>" />
<input name="submit" type="submit" value="�ѹ�֡ PR 㺹��ŧ㹰ҹ������" style="font-size:15px; width:230px; height:35px; color:#0033CC; cursor:pointer;" />
</div>
</td><td>
<table width="240">
<tr><td bgcolor="#FFCCFF"><div align="justify"><span class="style4">���ŷ��ժ��� </span>���¶֧ ������㹪�ͧ��� �����繤����ҧ </div></td></tr>
<tr><td bgcolor="#FFFF99"><div align="justify"><span class="style4">���ŷ�������ͧ </span>���¶֧ �����ŷ��س��͡���ç����ҹ�����Ţͧ����ѷ  ��Ҥس������ "�ѹ�֡ PR 㺹��ŧ㹰ҹ������" �����Ū�ͧ���ж١�ѹ�֡�繤����ҧ (null) </div></td></tr>
<tr><td style="color:#0000FF"><div align="justify"><strong>�����ŷ���繵���ѡ���չ���Թ</strong> ��� �����ŷ��ӡ�ô֧�Ҩҡ�ҹ������</div></td></tr>
</table>
</td></tr></table>
</form>
</body>
</html>
<?php	
	}else {
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>