<?php
if(isset($_SESSION["empno_user"])) {
		require_once("../include_RedThemes/MSSQLServer_connect_2.php");				
		$pr_no=@$_GET["pr_no"];

			require_once("../include/open_report.php");				
			//------------------------- mysql ----------------------------------
			require_once("../include_RedThemes/mysql_connect.php");	
			require_once("../include_RedThemes/funcShow.php");								
											
				// ========= ź��������� ========= 		
				$del_pr_master = "delete from pr_master";
				$del_pr_detail = "delete from pr_details";
				
				@odbc_exec($conn_mysql, $del_pr_master);
				@odbc_exec($conn_mysql, $del_pr_detail);				
				// ========= Insert Master ========= 		
						$str_pr_master = "select format(p.pr_date,'yyyy-MM-dd') pr_date, '(' + p.deptno + ') ' + d.deptname deptname,
													pr_payment,flag_obj,obj_name1,obj_name2,obj_name3,estimate_day,
													p.supplier_id,s.company_name,pr_remark,vat_include,
													e1.e_name empname,e2.e_name mngname,mng_remark 
													from pr_master p
													left join supplier s on p.supplier_id=s.supplier_id
													left join dept d on p.deptno=d.deptno
													left join emp e1 on p.empno=e1.empno
													left join emp e2 on p.mngno=e2.empno
													where p.pr_no='$pr_no'";
						$cur_pr_master = @odbc_exec($conn,$str_pr_master);	
							
						$pr_date=@odbc_result($cur_pr_master, "pr_date");
						$deptname=@odbc_result($cur_pr_master, "deptname");
						$empname=@odbc_result($cur_pr_master, "empname");
						$mngname=@odbc_result($cur_pr_master, "mngname");
						$pr_payment=@odbc_result($cur_pr_master, "pr_payment");
						$flag_obj=@odbc_result($cur_pr_master, "flag_obj");
						$obj_name1=@odbc_result($cur_pr_master, "obj_name1");
						$obj_name2=@odbc_result($cur_pr_master, "obj_name2");
						$obj_name3=@odbc_result($cur_pr_master, "obj_name3");
						$estimate_day=@odbc_result($cur_pr_master, "estimate_day");				
						if(@odbc_result($cur_pr_master, "supplier_id")!=''){
								$suppliername="(".@odbc_result($cur_pr_master, "supplier_id").") ".@odbc_result($cur_pr_master, "company_name");
						}else{ $suppliername=''; }
						$pr_remark=@odbc_result($cur_pr_master, "pr_remark");
						$vat_include=@odbc_result($cur_pr_master, "vat_include");

						if($flag_obj=="7"){
							$obj_name1 = "Sub-Contact";
						}
						
				
						if($vat_include=="0") $pr_vat = 'ราคาสินค้าที่ระบุเป็นราคาสินค้าที่ยังไม่รวม VAT';
						else $pr_vat = 'ราคาสินค้าที่ระบุเป็นราคาสินค้าที่รวม VAT แล้ว';
						
						$pr_remark =  $pr_vat."\n".$pr_remark;
									
						$mng_remark=@odbc_result($cur_pr_master, "mng_remark");
						
						
						$txt_addprmaster ="insert into pr_master (
															pr_no,pr_date,deptname,
															empname,mngname,pr_payment, 
															flag_obj,obj_name1,obj_name2,
															obj_name3,estimate_day,suppliername,
															pr_remark,mng_remark
														) values(
															".chkINSMySQL($pr_no).",".chkINSMySQL($pr_date).",".chkINSMySQL($deptname).",
															".chkINSMySQL($empname).",".chkINSMySQL($mngname).",".chkINSMySQL($pr_payment).",
															".chkINSMySQL($flag_obj).",".chkINSMySQL($obj_name1).",".chkINSMySQL($obj_name2).",
															".chkINSMySQL($obj_name3).",".chkINSMySQL($estimate_day).",".chkINSMySQL($suppliername).",
															".chkINSMySQL($pr_remark).",".chkINSMySQL($mng_remark)."
														)";
						//echo  $txt_addprmaster.'<br>';
						@odbc_exec($conn_mysql, $txt_addprmaster);			
				// ========= Insert Detail ========= 		
						$str_pr_details = "select  id,prod_no,prod_name,prod_qty,prod_unit,prod_price,ISNULL(discount_baht,0) discount_baht 
														from pr_details 
														where pr_no='$pr_no'
														order by id";
						$cur_pr_details = @odbc_exec($conn,$str_pr_details);	

						while(@odbc_fetch_row($cur_pr_details)){ 

							$id=@odbc_result($cur_pr_details, "id");	
							$prod_no=@odbc_result($cur_pr_details, "prod_no");	
							$prod_name=@odbc_result($cur_pr_details, "prod_name");	
							$prod_qty=@odbc_result($cur_pr_details, "prod_qty");	
							$prod_unit=@odbc_result($cur_pr_details, "prod_unit");	
							$prod_price=@odbc_result($cur_pr_details, "prod_price");	
							$discount_baht=@odbc_result($cur_pr_details, "discount_baht");	

							if((($prod_price*$prod_qty)-$discount_baht)==0)$price=0;
							else $price = (($prod_price*$prod_qty)-$discount_baht) / $prod_qty;

										if($flag_obj=="7"){
											$strQUEDetailSubjob = "select pd.subjob_show + '=' + sj.qty subjob_show
															from pr_details_subjob sj, mrp_pd pd
															where sj.subjob=pd.subjob 
															and pr_no= '$pr_no'
															and id='$id'";
											$curQUEDetailSubjob = @odbc_exec($conn, $strQUEDetailSubjob );
											$subjob_show="";
											while(@odbc_fetch_row($curQUEDetailSubjob)){			
												if($subjob_show=="")$subjob_show =@odbc_result($curQUEDetailSubjob,"subjob_show");	
												else $subjob_show .= ",".@odbc_result($curQUEDetailSubjob,"subjob_show");	
											}									
											$prod_name .= "\n(".$subjob_show.")";
										}


							$txt_addprdetails ="insert into pr_details (
																		pr_no,id,prod_no,
																		prod_name,prod_qty,prod_unit,
																		price
																) values(
																		".chkINSMySQL($pr_no).",".chkINSMySQL($id).",".chkINSMySQL($prod_no).",
																		".chkINSMySQL($prod_name).",".chkINSMySQL($prod_qty).",".chkINSMySQL($prod_unit).",
																		".chkINSMySQL($price)."
																) ";
							//echo  $txt_addprmaster.'<br>';
							@odbc_exec($conn_mysql, $txt_addprdetails);	
						}
				// ========= Open Report ========= 												
			if($flag_obj=="8")
				open_report("pr_report_T.rpt",".pdf",dirname(__FILE__));
			else	open_report("pr_report.rpt",".pdf",dirname(__FILE__));

	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
<html><head>
<title>PR REPORT</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	

