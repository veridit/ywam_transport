<?
	session_start();
	//print(date('Y-m-d h:i:s'));
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iUSER_ID		=	0;
	$iRECORD_COUNT	=	0;
	if(isset($_REQUEST["userid"]))	$iUSER_ID		=	$_REQUEST["userid"];
	
	if(isset($_POST["action"])	&& $_POST["action"]=="change-email"){
	
				$sSQL	=	"SELECT user_id FROM tbl_user WHERE email = '".mysql_real_escape_string($_POST["txtemail"])."' AND user_id <> ".$iUSER_ID;
				$rsEMAIL_CHECK	=	mysql_query($sSQL) or die(mysql_error());
				if (mysql_num_rows($rsEMAIL_CHECK)>0){
					$sMessage=fn_Print_MSG_BOX("<li>user already registered with this email address","C_ERROR");
					mysql_free_result($rsEMAIL_CHECK);
					
				}else{
	
						$sSQL="UPDATE  tbl_user SET email='".mysql_real_escape_string($_POST["txtemail"])."' WHERE user_id = ".$iUSER_ID;
						//print($sSQL);
						$rsMEMBER=mysql_query($sSQL) or die(mysql_error());
				
						$sEmailSubject		=	"Email Change Confirmation";
						$sMailMSG			=	"Your email has been changed to this email address";
						//print($sMailMSG);
						$mail = new PHPMailer();
						$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
						$mail->From     = $sSUPPORT_EMAIL;
						$mail->FromName = $sCOMPANY_Name;
						$mail->AddAddress($_POST["txtemail"]);
						$mail->IsHTML(true);                               // send as HTML
						$mail->Subject  =  $sEmailSubject;
						$mail->Body    = $sMailMSG;
						if(!$mail->Send()){
							$sMessage		=	fn_Print_MSG_BOX("Email has been changed, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
						}else{
							$sMessage		=	fn_Print_MSG_BOX("<li>A confirmation email has been sent to this new address", "C_SUCCESS");
						}
				}
	
}

$sSQL	=		"SELECT f_name, l_name, email FROM tbl_user WHERE user_id = ".$iUSER_ID;
$rsUSER	=		mysql_query($sSQL) or die(mysql_error());
$iRECORD_COUNT	=mysql_num_rows($rsUSER);	
if($iRECORD_COUNT>0){
	$rowUSER	=	mysql_fetch_array($rsUSER);
}else{

	$sMessage		=	fn_Print_MSG_BOX("no user found!", "C_ERROR");
	
}mysql_free_result($rsUSER);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Update User Email</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_user(frm){

	var sErrMessage='';
	var iErrCounter=0;

	
	
	if (frm.txtemail.value==""){
		sErrMessage=sErrMessage+'<li>please enter your email';
		iErrCounter++;
	}else{
		regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;
		if (!regExp.test(Trim(frm.txtemail.value))){
			sErrMessage=sErrMessage+'<li>please enter valid email address';
			iErrCounter++;
		}
	}

	
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		document.frm1.action.value='change-email';
		frm.submit();
	}
	
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
								   				<h1 style="margin-bottom: 0px;">UPDATE USER EMAIL</h1>
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
						<form name="frm1" action="edit_email.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="userid" value="<?=$iUSER_ID?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?	if($iRECORD_COUNT>0){?>						
								
								<tr>
									<td width="150" class="label">First Name:</td>
									<td width="300"><input type="text" readonly="" name="txtfname" value="<?=$rowUSER['f_name']?>" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Last Name:</td>
									<td><input type="text" readonly="" name="txtlname" value="<?=$rowUSER['l_name']?>" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Email:</td>
									<td><input type="text" name="txtemail" value="<?=$rowUSER['email']?>" maxlength="150" style="width:250px;"  /></td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td></td>
									<td>
										
									<input type="button" name="btnSUBMIT" value="CHANGE EMAIL" class="Button" onClick="valid_user(this.form);" style="width:110px;" />
										
									</td>
								</tr>
								<?	}?>
								
								
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
 