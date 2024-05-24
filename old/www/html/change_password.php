<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	
	if (isset($_POST["action"]) && $_POST["action"]=="change_password"){
			
		$OldPassword=	mysql_real_escape_string($_POST["txtoldpassword"]);
		$NewPassword=	mysql_real_escape_string($_POST["txtpassword"]);
		
		$query="SELECT f_name FROM tbl_user WHERE user_id=".$_SESSION["User_ID"]." AND user_group = ".$_SESSION["User_Group"]." AND password=PASSWORD('$OldPassword')";
		//print($query);
		$rsPASSWORD=mysql_query($query);
		$iRecord=mysql_num_rows($rsPASSWORD);
		if ($iRecord==0)
			$sMessage		=	fn_Print_MSG_BOX("old password is incorrect (password is not changed).", "C_ERROR");
		else {
			$sSQL="UPDATE tbl_user set password=PASSWORD('$NewPassword') WHERE user_id=".$_SESSION["User_ID"]." and password=password('$OldPassword')";
			$rsPASSWORD1=mysql_query($sSQL) or die(mysql_error());
			$sMessage	=	fn_Print_MSG_BOX("password has been changed successfully!", "C_SUCCESS");
			
		}mysql_free_result($rsPASSWORD);	
	}
	
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Change Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_password(frm){
						
var sErrMessage='';
var iErrCounter=0;

if (frm.txtoldpassword.value == ""){
	sErrMessage='<li>please enter your current password';
	iErrCounter++;
}
if (frm.txtpassword.value == ""){
	sErrMessage=sErrMessage+'<li>please enter your new password';
	iErrCounter++;
}

if (frm.txtconfirmpassword.value != "" && frm.txtpassword.value != "") {
	if(frm.txtconfirmpassword.value != frm.txtpassword.value){
		sErrMessage=sErrMessage+'<li>passwords and confirm password are not same';
		iErrCounter++;
		
	}
}else if(frm.txtpassword.value != "" && frm.txtconfirmpassword.value == ""){
	sErrMessage=sErrMessage+'<li>please confirm your new password';
	iErrCounter++;
	
}

if (iErrCounter >0){
	fn_draw_ErrMsg(sErrMessage);
}
else
	frm.submit();

}
</script>

</head>
<body style="margin: 0px;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		<!--start header	-->
		<? include('inc_header.php');	?>
		
   		<!-- start side nav	-->
		
		
		<!-- actual page	-->
		<td>
        <table border="0" cellspacing="0" cellpadding="0" width="980">
            	<tr valign="top" align="left">
                	<td width="15" height="16"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
                	<td width="949"><img src="../assets/images/autogen/clearpixel.gif" width="683" height="1" border="0" alt=""></td>
                	<td width="15"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
               	</tr>
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="2" width="949">
                 		<table border="0" cellspacing="0" cellpadding="0" width="949" style="background-image: url('../assets/images/banner.png'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								 		<tr>			
								  			<td class="TextObject" align="center">
								   				<h1 style="margin-bottom: 0px;">CHANGE PASSWORD</h1>
								  			</td>
								 		</tr>
									</table>
                   				</td>
                  			</tr>
                 		</table>
                	</td>
               	</tr>
				
               	<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
                	<td width="949" class="TextObject" align="center">
						<form name="frm1" action="change_password.php" method="post">
							<input type="hidden" name="action" value="change_password"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="150" class="label">Current Password:</td>
									<td width="300"><input type="password" name="txtoldpassword" value="" style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">New Password:</td>
									<td><input type="password" name="txtpassword" value="" style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">Confirm New Password:</td>
									<td><input type="password" name="txtconfirmpassword" value="" style="width:150px;"  /></td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td></td>
									<td>
										<input type="button" name="btnSUBMIT" value="CHANGE PASSWORD" class="Button" onClick="valid_password(this.form);" style="width:130px;" />
									</td>
								</tr>
							</table>
						</form>
                	</td>
                	<td></td>
               	</tr>
			</table>
		</td>
		
		
		<!-- end actual page	-->
           
      <!-- footer	-->
	  <? include('inc_footer.php');	?>
     </table>
    </td>
   </tr>
  </table>
 </div>
</body>
</html>
 