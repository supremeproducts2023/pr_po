<?
@session_start();
if(session_is_registered("valid_userprpo")) {

	session_register("choice_value");
	session_register("sespk_no");

	$_SESSION["choice_value"] = $_GET["choice_value"];
	$_SESSION["sespk_no"]='';
	$choice_value= $_SESSION["choice_value"];
	$_SESSION["pr_type"] = @$_GET["type"];
	$_SESSION["po_company"] = @$_GET["company"];
	
	switch($choice_value){
	        case 'showpopr'		:	include("prpo_pagesearch.php");			break;
			case 'pradd'			:	include("pr_pagenew.php");				break;
			case 'prup'			:	
			case 'prdel'			:	
			case 'prcom'		:	
			case 'prapp'			:	
			case 'prrep'			:	include("pr_pagesearch.php");		break;
			case 'poadd'		:	include("po_pagenew.php");			break;
			case 'poup'			:	
			case 'podel'			:	
			case 'porep'			:	
			case 'posend'		:	
			case 'poapp'		:	include("po_pagesearch.php");		break;
			case 'supadd'		:	include("sup_pagenew.php");			break;
			case 'supup'			:	
			case 'supdel'		:	
			case 'suprep'		:	include("sup_pagesearch.php");		break;
			case 'pocheck'	:	include("po_check.php");					break;
			case 'poimport'	: 	include("po_import.php");					break;
			case 'portPR'		:	include("portPRtranstek.php");			break;
			// เพิ่ม Like ตาม Requirement เลขที่ 213
			case 'vendorAdd'		:	include("vendorGroup_add.php");			break;
			case 'vendorEdit'		:	
			case 'vendorDel'		:	include("vendor_search.php");
			
			// END
	}

}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>