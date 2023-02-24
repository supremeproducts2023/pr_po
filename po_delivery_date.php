<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
			require_once("../include_RedThemes/odbc_connect.php");
			require_once("../include_RedThemes/MSSQLServer_connect.php");	
			require_once("../include/alert.php");
			$empno_user = $_SESSION["empno_user"];
			if(trim(@$_POST["po_no"]) != '')
			{$po_no = @$_POST["po_no"];}
			else
			{$po_no = @$_GET["po_no"];}
			$flagAction = @$_POST["flagAction"];
			$log_remark = @$_POST["log_remark"];
			if(@$flagAction =='AddCode')
			{
					$delivery_date=@$_POST["delivery_date"];
					$user_id = $_SESSION['empno_user'];
					$strUpd = "update po_master set 
					delivery_date = format('$delivery_date','dd-MM-yyyy')
					, LAST_USER = '$user_id'
					,LAST_DATE = getdate()
					where PO_NO = '$po_no' ";
						if(@odbc_exec($conn,$strUpd))
						{
							$strINS = "insert into LOG_SENT_AMBU (
											ID,ID_RUN,LOG_TYPE,
											NEW_DATE,REMARK,REC_USER,REC_DATE
											) values(
											(select ISNULL(max(ID)+1,1) from LOG_SENT_AMBU),
											'$po_no','p',format('$delivery_date','dd-MM-yyyy'),'$log_remark',
											'$user_id',getdate())";
											if(@odbc_exec($conn,$strINS))
											{echo '<script language="JavaScript" type="text/JavaScript">
										alert ("��Ѻ��ا������ Delivery Date ���º�������Ǥ��");';
										echo 'parent.main_frame.location.href = "./po_search_Delivery.php?po_no='.$po_no.'";';
										echo '</script>';
														$exeCOMMIT = @odbc_exec($conn,"commit");
										}
										else
										{echo '<script language="JavaScript" type="text/JavaScript">
										alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�ѹ�֡������ PO ���ŧ���ҹ����������");';
										//echo 'parent.main_frame.location.href = "./pomas_edit.php";';
								echo '</script>';
								}
						}
						else
						{
						echo '<script language="JavaScript" type="text/JavaScript">
										alert("�Դ��ͼԴ��Ҵ��鹡Ѻ�к� ������������ö�ѹ�֡������ PO ���ŧ���ҹ����������");';
										//echo 'parent.main_frame.location.href = "./pomas_edit.php";';
								echo '</script>';
						}
			}			
				
?>
<html><head>
		<title>*** PO ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script language='javascript' src='../include/popcalendar.js'></script>				
		 		
		<!-- Check Not null -->
		 <script language='javascript'>
					function check_po(obj){
							if(obj.log_remark.value==""){  	
								alert("��سҡ�͡���˵ء������͹��˹����Թ��Ҥ��");
								obj.log_remark.focus();
								return false;
							}		
							obj.submit();
				}
		 </script>							
</head>
<body topmargin="0" leftmargin="0">
<br>
<center>
			<form name="form_po" action="po_delivery_date.php" method="post">
			<input name="flagAction" type="hidden" value="AddCode">
			<input name="po_no" type="hidden" value="<?=$po_no;?>">
			<?php $po_company = $_SESSION["po_company"]; ?>
            <input name="po_company" type="hidden" value="<?=$po_company;?>">
			<?
			$strQUEGeneral = "select  distinct  format(p.delivery_date,'DD-MM-YYYY')  as delivery_date
											from po_master p 
											where p.po_no = '$po_no'";
			$po_deliv_date = "";
			$curQUEGeneral= odbc_exec($conn,$strQUEGeneral);	
									while(odbc_fetch_row($curQUEGeneral)){
										$po_deliv_date = odbc_result($curQUEGeneral, "delivery_date");
										}
			
			?>
		<table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;����͹��˹����Թ���</th>
            <th><div align="right">&nbsp;</div></th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      </tr>
                        <td class="tdleftwhite">&nbsp;Delivery Date</td>
                        <td><input name="delivery_date_old" type="text"  class="style_readonly"value="<? echo $po_deliv_date; ?>" size="15" readonly="" ></td>
						 <td class="tdleftwhite">&nbsp;����͹���Թ���</td>
						 <td>
						 <input name="delivery_date" type="text"  class="style_readonly"value="<? echo $po_deliv_date; ?>" size="15" readonly="" >						  
                        <script language='javascript'>
											<!-- 
											  if (!document.layers) {
												document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_po.delivery_date, \"dd-mm-yyyy\")'>");
											}
											//-->
							</script>	
						 </td>
                      </tr>
					  <tr>
					  <td>���˵�</td>
					  <td colspan = 3><textarea name="log_remark" cols="50" id="log_remark" onKeyUp="return chkStringInput(this,100);"><? echo @$log_remark; ?></textarea></td>
					  </tr>
					</table>					  
				 </td>
                </tr>
                <tr>
                  <td ><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="tdleftwhite" colspan="3"><div align="right">                          
						<a onClick="return check_po(document.form_po);" style="cursor:hand"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 						 
						<a onClick="parent.main_frame.location.href = './po_search_Delivery.php?po_no=<?=$po_no?>';" style="cursor:hand"
						onMousedown="document.images['butcancel'].src=cancel3.src" 
						onMouseup="document.images['butcancel'].src=cancel1.src"						
						 onMouseOver="document.images['butcancel'].src=cancel2.src" 
						 onMouseOut="document.images['butcancel'].src=cancel1.src">						 
						 <img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
						</a>
                        </div>
					  </td>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
			</form>		
</center>
</body>
</html>
<?
	}
	else{
			include("index.php");
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("�����¤�� �س�ѧ����� Login");';
			echo '</script>';
	}
?>









