<?
@session_start();
if(session_is_registered("empno_user")) {
	$doc_no= @$_GET["doc_no"];
	$doc_type= @$_GET["doc_type"];

	include "../include_RedThemes/wait.php";
	flush();

		require_once("../include/open_report.php");				
		require_once("./CreateReport.php");				
		
		if($doc_type=="all"){
			CreatePO($doc_no,"1");
		}else if($doc_type=="nonprice"){
			CreatePO($doc_no,"2");
		}else if($doc_type=="nontail"){
			CreatePO($doc_no,"3");
		}
	
		open_report("po_report.rpt",".doc",dirname(__FILE__));
		

	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';

}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>
<html><head>
<title>PO REPORT</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	

