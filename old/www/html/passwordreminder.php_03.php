<?
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	$sMessage		=	"";
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="passwordreminder"){
	
		$sEmail 		= $_POST["txtemail"];
						
				if ($sEmail == "") {
					$sMessage		=	fn_Print_MSG_BOX("please enter your registered email address", "C_ERROR");
				}
				else{
						
						$sSQLText 	= "SELECT user_id, password, user_group FROM tbl_user WHERE email = '" . $sEmail . "' AND active = 1 AND new_user = 0";
						$rsPASSWORDREMIND	=	mysql_query($sSQLText) or die(mysql_error());
						
						if (mysql_num_rows($rsPASSWORDREMIND)<=0) {
							$sMessage		=	fn_Print_MSG_BOX("<li>email is invalid, not found in the system. please try again.", "C_ERROR");
						}
						else{
							$rowMEMBER					=	mysql_fetch_array($rsPASSWORDREMIND);
						
							$sGroupLevel	=	fn_GET_FIELD("tbl_user_group", $rowMEMBER["user_group"], "group_id", "group_name");	//select group level name
							$sPassword		= 	$rowMEMBER["password"];
							
							$sEmailSubject		=	"Password Reminder From $sCOMPANY_Name";
							$sEmailMessage		=	"<span class='success'>Dear User<br />";
							$sEmailMessage		.=	"below is your account information on ".$sCOMPANY_Name."<br />";
							$sEmailMessage		.=	"<b>Your Password is :</b>".$sPassword."<br />";
							$sEmailMessage		.=	"<b>Your Group is :</b>".$sGroupLevel."<br />";
							$sEmailMessage		.=	"Login to your account On ".$sCOMPANY_Name." <a href=".$sCOMPANY_Link."html/login.php>Please Click here</a><br />";
							$sEmailMessage		.=	"Thanks<br />";
							$sEmailMessage		.=	"Regards,<br />";
							$sEmailMessage		.=	"<a href=".$sCOMPANY_Link.">".$sCOMPANY_Name."</a></span>";
							//$sMessage			=	$sEmailMessage;
							
							
							//print($sMailMSG);
print "Host :$sCOMPANY_SMTP";
							$mail = new PHPMailer();
							$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
							$mail->From     = $sSUPPORT_EMAIL;
							$mail->FromName = $sCOMPANY_Name;
							$mail->AddAddress($_POST["txtemail"]);
							$mail->IsHTML(true);                               // send as HTML
							$mail->Subject  =  	utf8_decode($sEmailSubject);
							$mail->Body    	= 	utf8_decode($sEmailMessage);
							if(!$mail->Send())
							{
							   $sMessage		=	fn_Print_MSG_BOX("<li>error in sending email please contact our support team $sSUPPORT_EMAIL, $mail->ErrorInfo","C_ERROR");
							}else{
								$sMessage		=	fn_Print_MSG_BOX("<li>your password has been sent to your email address", "C_SUCCESS");
							}
							
							//headers
							/*$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
							// More headers
							$headers .= "From:".$sProject_EMAIL."\r\n";*/
						
							//echo $sEmailMessage;
							/*if(mail( $email, 'Password Recovery', $sMessag, $headers))
								$sError	= "Your Password has been sent Successfully to your Email account";
							else
								$sError	= "Error in Sending Mail";*/
							
							
						}
				}
			
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Password Reminder</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">

function valid_reminder(frm){

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
								   				<h1 style="margin-bottom: 0px;">PASSWORD REMINDER</h1>
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
						<form name="frm1" action="passwordreminder.php" method="post">
							<input type="hidden" name="action" value="passwordreminder"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="350" align="center" class="box">
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td width="100" class="label">Email:</td>
									<td width="250"><input type="text" name="txtemail" value="" style="width:245px;"  /></td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td align="right"><input type="button" name="btnSUBMIT" value="SEND" class="Button" onClick="valid_reminder(this.form);" style="width:85px;"/></td></tr>
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
