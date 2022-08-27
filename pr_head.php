<?
@session_start();
if(session_is_registered("valid_userprpo")) {
?>
<html>
<head>
<title>Untitled Document</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<script language='javascript' src='./include/tabanimate.js'></script>		
</head>
<body topmargin="0" leftmargin="0">
<table width="1002" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<?
			$choice_value = @$_SESSION["choice_value"];
			
			switch($choice_value){
				case 'pradd'		: 	?><img src="./include/tab/pr-03.jpg" name="tab1" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
											break;
				case 'prup'		: 	if($_SESSION["sespk_no"]==''){
													?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/pr-04.jpg" name="tab2" border="0"><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?
											}else{
													$tabno=@$_GET["tabno"];
													if($tabno==1){
															?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=2';parent.main_frame.location.href = './prmas_edit.php';"  style="cursor:hand" onMouseOver="document.images['tabipm'].src=pr02.src"  onMouseOut="document.images['tabipm'].src=pr01.src" target="_self"><img src="./include/tab/pr-01.jpg" name="tabipm" border="0" ></a><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?
													}else if($tabno==2){
															?><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=1';parent.main_frame.location.href = './pr_search.php';"  style="cursor:hand"  onMouseOver="document.images['tsearch'].src=tsearch2.src"  onMouseOut="document.images['tsearch'].src=tsearch1.src" target="_self"><img src="./include/tab/search-01.jpg" name="tsearch" border="0" ></a><img src="./include/tab/pr-03.jpg" name="tabipm" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}
											}								
											break;
				case 'prdel'		: 	
				case 'prcom'	: 	
				case 'prapp'		: 	
				case 'prrep'		: 	
				case 'podel'		: 	
				case 'porep'		: 	
				case 'posend'	: 	
				case 'poapp'	: 	
				case 'supdel'	: 	
				case 'mldel'		: 	?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?
											break;		
				case 'poadd'	: 	?><img src="./include/tab/po-03.jpg" name="tab1" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?		
											break;		
				case 'poup'		: 	if($_SESSION["sespk_no"]==''){
													?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/po-04.jpg" name="tab2" border="0"><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br>	<?
											}else{
													$tabno=@$_GET["tabno"];
													if($tabno==1){
															?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=2';parent.main_frame.location.href = './pomas_edit.php';"  style="cursor:hand" onMouseOver="document.images['tabipm'].src=po02.src"  onMouseOut="document.images['tabipm'].src=po01.src" target="_self"><img src="./include/tab/po-01.jpg" name="tabipm" border="0" ></a><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}else if($tabno==2){
															?><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=1';parent.main_frame.location.href = './po_search.php';"  style="cursor:hand"  onMouseOver="document.images['tsearch'].src=tsearch2.src"  onMouseOut="document.images['tsearch'].src=tsearch1.src" target="_self"><img src="./include/tab/search-01.jpg" name="tsearch" border="0" ></a><img src="./include/tab/po-03.jpg" name="tabipm" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}
											}						
											break;		
				case 'supadd'	: 	?><img src="./include/tab/supplier-03.jpg" name="tab1" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
											break;		
				case 'supup'		: 	if($_SESSION["sespk_no"]==''){
													?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/supplier-04.jpg" name="tab2" border="0"><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br>	<?
											}else{
													$tabno=@$_GET["tabno"];
													if($tabno==1){
															?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=2';parent.main_frame.location.href = './supplier_edit.php';"  style="cursor:hand" onMouseOver="document.images['tabipm'].src=sup02.src"  onMouseOut="document.images['tabipm'].src=sup01.src" target="_self"><img src="./include/tab/supplier-01.jpg" name="tabipm" border="0" ></a><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}else if($tabno==2){
															?><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=1';parent.main_frame.location.href = './sup_search.php';"  style="cursor:hand"  onMouseOver="document.images['tsearch'].src=tsearch2.src"  onMouseOut="document.images['tsearch'].src=tsearch1.src" target="_self"><img src="./include/tab/search-01.jpg" name="tsearch" border="0" ></a><img src="./include/tab/supplier-03.jpg" name="tabipm" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}
											}								
											break;		
				case 'suprep'	: 	?><img src="./include/tab/supplier-03.jpg" name="tab1" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?		
											break;		
				case 'mladd'		: 	?><img src="./include/tab/ml-03.jpg" name="tab1" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
											break;		
				case 'mlup'		: 	if($_SESSION["sespk_no"]==''){
												?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/ml-04.jpg" name="tab2" border="0"><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br><?
											}else{
													$tabno=@$_GET["tabno"];
													if($tabno==1){
															?><img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=2';parent.main_frame.location.href = './ml_edit.php';"  style="cursor:hand" onMouseOver="document.images['tabml'].src=ml02.src"  onMouseOut="document.images['tabml'].src=ml01.src" target="_self"><img src="./include/tab/ml-01.jpg" name="tabml" border="0" ></a><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}else if($tabno==2){
															?><a onClick="parent.mid_frame.location.href = './pr_head.php?tabno=1';parent.main_frame.location.href = './ml_search.php';"  style="cursor:hand"  onMouseOver="document.images['tsearch'].src=tsearch2.src"  onMouseOut="document.images['tsearch'].src=tsearch1.src" target="_self"><img src="./include/tab/search-01.jpg" name="tsearch" border="0" ></a><img src="./include/tab/ml-03.jpg" name="tabipm" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30">	<br><?
													}
											}								
											break;		
			}
?>
				
	</td>
  </tr>
</table>

</body>
</html>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>