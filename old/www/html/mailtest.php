<?php
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	$sMessage		=	"Dette er en test";
	$bPictureErr	=	false;
	
	$sPhotoSQL		=	", photo";
	$sUserPhoto		=	"noimage.gif";
	
	$iUSER_ID		=	0;
	$iLEADER_ID		=	0;
	$sUSER_PWD		=	"";
	$bActive		=	"0";
	$sDriveTest		=	"";
	$sTestDate		=	"";
	$sGroupLevel	=	"";
	$sRenewDate		=	date('m/d/Y');
	
	$sPERMIT_SQL	=	"";
	$sPERMIT_SQL_VAL=	"";

	$sGROUP_SQL_VAL	=	$iGROUP_DRIVER;
	
	
	
	$iEMAIL_EXISTS	=	0;
	$bCODE_WORD		=	false;
	$bLEADER_CODE	=	false;
	
	$iDUP_USER_ID	=	0;
	$iDUP_USER_GROUP=	0;
	
	$arrERR			=	array();
	
				$sMailMSG = "Dette er en test.";	
				$sEmailSubject = "Testmail";
					//$sMessage	=		fn_Print_MSG_BOX($sMailMSG,"C_ERROR");
					
					//print($sMailMSG);
					$mail = new PHPMailer();
					$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
					$mail->From     = $sSUPPORT_EMAIL;
					$mail->FromName = $sCOMPANY_Name;
					$mail->AddAddress("erikgrotnes@uofnkona.edu");
					$mail->IsHTML(true);                               // send as HTML
					$mail->Subject  =  $sEmailSubject;
					$mail->Body    = $sMailMSG;
					if(!$mail->Send())
					{
					   $sMessage		=	print("Error in Sending Email, $mail->ErrorInfo");
					}else{
						$sMessage		=	print("email has been sent to your registered email address");
					}
					
					
										
										
									
?>
Done
