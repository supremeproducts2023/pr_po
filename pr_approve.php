<?
  	@session_start();
	if(isset($_SESSION["valid_userprpo"])) {
			require_once("../include_RedThemes/odbc_connect.php");
?>
<html><head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script language='javascript' src='../include/checkinput.js'></script>				
		<script language='javascript' src='../include/buttonanimate.js'></script>				
		 
</head>
<body topmargin="0" leftmargin="0">
<center>
			<form name="form_pr" action="pr_approvecode.php" method="post">
			<input name="pr_no" type="hidden" value="<?php echo $_GET["pr_no"]; ?>">
		<table width="450"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;เหตุผลที่ไม่อนุมัติ</th>
            <th><div align="right">&nbsp;</div></th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><textarea name="mng_remark" cols="70" rows="8" onKeyUp="return check_string(document.form_pr.mng_remark,300);"></textarea></td>
                </tr>
                <tr>
                  <td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="3"><div align="right">                          
						<a onClick="return fill_all_obj(document.form_pr);" style="cursor:hand"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 						 
						<a onClick="window.close();" style="cursor:hand"
						onMousedown="document.images['butcancel'].src=cancel3.src" 
						onMouseup="document.images['butcancel'].src=cancel1.src"						
						 onMouseOver="document.images['butcancel'].src=cancel2.src" 
						 onMouseOut="document.images['butcancel'].src=cancel1.src">						 
						 <img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
						</a>
                        </div>
						
					  </th>
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
			<script language="JavaScript" type="text/JavaScript">
							document.form_pr.mng_remark.focus();
			</script>
</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>









