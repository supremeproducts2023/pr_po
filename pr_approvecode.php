<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {	
		require_once("../include_RedThemes/odbc_connect.php");				
			
		$flag=@$_GET["flag"];
		
		if($flag=="yes"){
		
				$pr_no=@$_GET["pr_no"];		
				$txt_up = "update pr_master set pr_status='4',mng_remark='',approve_date=sysdate,pr_date=sysdate where pr_no='$pr_no'";
				$exe_up = odbc_exec($conn,$txt_up);
				$exe_commit = odbc_exec($conn,"commit");
				?>
				<script language="JavaScript" type="text/JavaScript">
						alert ("ส่งข้อมูลเรียบร้อยแล้วค่ะ");
						window.location = './pr_search.php?flagAction=PushSearch';
				</script>
				<?	
		}else{
				$pr_no=@$_POST["pr_no"];		
				$mng_remark=@$_POST["mng_remark"];
		
				$txt_up = "update pr_master set pr_status='3',mng_remark='$mng_remark',approve_date=sysdate,pr_date=sysdate where pr_no='$pr_no'";
				$exe_up = odbc_exec($conn,$txt_up);
				$exe_commit = odbc_exec($conn,"commit");
?>				
			<script language="JavaScript" type="text/JavaScript">
					window.opener.location.reload('./pr_search.php');
					window.close();			
			</script>
<?				
		}
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>


<html><head>
<title>APPROVE</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	

