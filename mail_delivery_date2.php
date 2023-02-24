<?

header('Content-type: text/html; charset=tis-620');
			require_once("../include_RedThemes/odbc_connect.php");			
			//require_once("../include_RedThemes/MSSQLServer_connect.php");		
		
			$flag = '';
				
								$sql_sendmail_PO = "Select P.Po_No, format(P.Po_Date,'dd-mm-yyyy') Po_Date, S.Company_Name, P.Paycode, P.Delivery_Time,
													P.Po_Status, P.Po_File, P.Po_File2, P.Po_File3 ,format(P.Delivery_Date,'dd-mm-yyyy') Delivery_Date 
													,(format(p.Delivery_Date,'dd-mm-yyyy')-format(getdate(),'dd-mm-yyyy')) as datediff
													From Po_Master P
													left join Supplier S on P.Supplier_Id = S.Supplier_Id
													Where P.Po_Status In (2,3) 
													and Delivery_Date is not null order by P.Po_No";
								$strResult = @odbc_exec($conn,$sql_sendmail_PO)	;
									$message = '';
									$message = '<table width="950"  border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
									<tr>
										<th width="750">&nbsp;&nbsp;รายการ PO ที่จะส่งของภายใน 2 วัน</th>
									</tr>
									<tr>
										<td>
											<table width="100%" border="0" align="center">
											<tr>
												<td>
													<table width="100%"  border="1" cellspacing="0" cellpadding="0" bgcolor="FFFFFF">
													<tr style="background: #3366CC">
														
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold" width="8%">เลขที่ PO
														</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="8%">วันที่เปิด PO</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="25%">Supplier</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="13%">Delivery Time</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="10%">Delivery Date</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="10%">วันที่ส่งของตาม<br />Payment Term</td>
														<td style="color: white; font-family: Tahoma; font-size:12px; font-weight: bold; text-align:center; " width="17%">สถานะทาง Oracle</td>
													</tr>';
					while(@odbc_fetch_row($strResult))
					{$date_diff  = '';
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
						$date_diff = @odbc_result($strResult,"datediff");
						$strQUE = "select Day from payment_master where PayCode = '$pay_code'";						
						//$resultQUE = @odbc_exec($MSSQL_connect,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));	
						//$Day = (int)@odbc_result($resultQUE,"Day");
						$Day = '555';
						$strSEL = "select format((format(p.po_date,'dd-mm-yyyy')+ $Day),'dd-mm-yyyy') payment_date from po_master p where po_no = '$po_no'";
						$resultSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));	
						$payment_date = @odbc_result($resultSEL,"payment_date");
						
						
						
						if($date_diff == '2')
						{
							//echo $date_diff.'<br>';
						
									if($po_status = 2)
									{$po_status_d = 'MS พิมพ์ใบ PO เรียบร้อยแล้ว';}
									else if($po_status = 3)
									{$po_status_d =  'MS Clear ใบ PO';}
								$str_po_inv = '<a href="pomas_reportcode.php?doc_no='.$po_no.'&doc_type=all"   style="cursor:hand" target="_blank">'.$po_no.'</a>';
									$flag = '2';		
									//echo '<br>in loop flag is -->'.$flag;
									$message .=	'<tr >

									
									<td style="text-align:center; color: black; font-family: Tahoma; font-size:12px; font-weight: bold">&nbsp;
										
									'.$po_no.'
              
									</td>
									<td style="text-align:center;  color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px">&nbsp;'.$po_date.'</td>
									<td style="text-align:center;  color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px"> &nbsp;'.$supplier.'</td>
									<td style="text-align:center;  color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px"> &nbsp;'.$delivery.'</td>
									<td style="text-align:center; color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px">&nbsp;'.$delivery_date.'</td>
									<td style="text-align:center; color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px">&nbsp;'.$payment_date.'</td>
									<td style="text-align:center;  color: black; font-family: Tahoma; font-size:11px; font-weight: bold; height:30px"> &nbsp;'.$po_status_d.'</td>
							       </tr>';
		
							
				
											
						}
						
					}
					
							$message .='	</table>	
														</td>
														</tr>
														</table>		
													</td>
												</tr>
												</table>';
							//echo '<br>flag is -->'.$flag;
							echo $message;	
							if ($flag == '2' )
							{
								
								require("../phpmailer/class.phpmailer.php");					
											// สร้าง+ส่ง Mail	
											$m = new PHPMailer();
											$m->IsSMTP();                                  			// send via SMTP
											$m->Host     = "mail.hitera.com"; 	// SMTP servers
											$m->SMTPAuth = true;    				 // turn on SMTP authentication	
											$m->CharSet = "tis-620";
											// ใส่ E-mail ผู้รับ
													
													$m->AddAddress("mslocal@supremeproducts.co.th","");
													$m->AddBCC("Nattawon.Nea@supremeproducts.co.th",""); 
													
											$m->From = "mslocal@supremeproducts.co.th";							
											$m->FromName = "mslocal ";
											$m->IsHTML(true);                               // send as HTML ?
											$m->Subject  =  "แจ้งเตือนการส่งของล่วงหน้า";
											$m->Body     =  $message;
											$m->Send(); 			
											$m->Send(); 
							}
							//echo $_SESSION["strGloUsername"].'<br>';
						//	echo $_SESSION["strGloPassword"];
?>