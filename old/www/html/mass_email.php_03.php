<?Php
	require("class.phpmailer.php");
	include('inc_connection.php');
	include('inc_function.php');
	
	//mysql_query("DELETE FROM tbl_temp_mass_emails");


	$sMESSAGE	=	"";
	$sACTION	=	"";
	$sSENDER	=	"";
	$sMESSAGE_ID=	"";
	$sACTIVATOR	=	"";
	//VARIABLES
	$iMAX_EMAILS_ONE_BATCH			=	100;
	$iTIMER_GAP						=	60*(1000*60);
	//$iTIMER_GAP						=	1*(1000*15);
	
	
	$sSUBJECT		=	"";		$sNAME		=	"";	$sEMAIL	=	"";		$sEMAIL_MESSAGE	=	"";
	
	if(isset($_REQUEST["action"]) && $_REQUEST["action"]!="")		$sACTION	=	$_REQUEST["action"];
	if(isset($_REQUEST["sender"]) && $_REQUEST["sender"]!="")		$sSENDER	=	$_REQUEST["sender"];
	if(isset($_REQUEST["mid"]) && $_REQUEST["mid"]!="")				$sMESSAGE_ID=	$_REQUEST["mid"];
	
	if($sACTION=="email"){
		
		
		//$iTIMER_GAP					=	intval($_POST["drpTimer"])*(1000*60);			//set the timer gap between two iterations
		$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = $sMESSAGE_ID";
		$sSUBJECT		=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
		$sNAME			=	$sCOMPANY_Name;
		$sEMAIL			=	$sSUPPORT_EMAIL;		
		$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = $sMESSAGE_ID";
		$sEMAIL_MESSAGE	=	stripcslashes(mysql_result(mysql_query($sSQL),0));
	
		if($sSUBJECT==""	&&	$sEMAIL_MESSAGE		==		""){
			$sSQL	=	"SELECT link_title, link_text FROM tbl_info_links WHERE link_id = ".$sMESSAGE_ID;
			$rsMESSAGE_DETAILS	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsMESSAGE_DETAILS)>0){
				list($sSUBJECT, $sEMAIL_MESSAGE)	=	mysql_fetch_row($rsMESSAGE_DETAILS);
			}mysql_free_result($rsMESSAGE_DETAILS);
		}
			
			
			$sSQL	=	"SELECT * FROM tbl_temp_mass_emails ORDER BY driver_name LIMIT 0, $iMAX_EMAILS_ONE_BATCH";
			$rsEMAIL	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsEMAIL)>0){
				
				while($rowEMAIL		=	mysql_fetch_array($rsEMAIL)){
					fn_SEND_EMAIL($sSUBJECT, $rowEMAIL['email_id'], $sEMAIL, $sNAME, $sEMAIL_MESSAGE);
					mysql_query("DELETE FROM tbl_temp_mass_emails WHERE email_id = '".$rowEMAIL['email_id']."'") or die(mysql_error());
				}
				//EMAILS ARE SENT NOW PRE-PRATION FOR NEXT ITRATION
				$sSQL	=	"SELECT COUNT(email_id) AS remaining_emails FROM tbl_temp_mass_emails";
				$iREMAINING_EMAILS	=	mysql_result(mysql_query($sSQL),0);
				//print("INVAL=".$iREMAINING_EMAILS);
				if(intval($iREMAINING_EMAILS)<=0){		///finish the process
					//send confirmation email to admin,
					$sSQL	=	"SELECT email FROM tbl_user WHERE user_id = ".$sSENDER;
					$sSENDER_EMAIL	=	mysql_result(mysql_query($sSQL),0);
					
					fn_SEND_EMAIL("Mass Email Message has been sent", $sSENDER_EMAIL, $sEMAIL, $sNAME, "Mass Email Message has been sent to the drivers or coordinators selected the Drivers Email function");
					echo "<script language='javascript'>setTimeout(\"window.close();\",5000);</script>";
					die();
				}
			}mysql_free_result($rsEMAIL);
		
	}
	

function fn_SEND_EMAIL($sEMAIL_SUBJECT, $sTO_EMAIL, $sFROM_EMAIL, $sFROM_NAME, $sEMAIL_BODY_MESSAGE){
	
		$sMSG	=	"";
	
		global $sCOMPANY_SMTP;
		/*print("<br />TO====".$sTO_EMAIL);
		print("<br />SUBJECT====".$sEMAIL_SUBJECT);
		print("<br />MESSAGE====".$sEMAIL_BODY_MESSAGE);*/
		$mail = new PHPMailer();
		$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
		$mail->From     = $sFROM_EMAIL;
		$mail->FromName = $sFROM_NAME;
		$mail->AddAddress($sTO_EMAIL);
		//$mail->AddAddress("tariqalikhan18@hotmail.com");
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  	$sEMAIL_SUBJECT;
		$mail->Body    	= 	$sEMAIL_BODY_MESSAGE;
		if(!$mail->Send())
		{
		   	$sMSG		=	"<span class='err'>Error in Sending Email, $mail->ErrorInfo</span>";
		}
}

?>
<html>
<head>
<title>Mass Email Sending Function</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript" type="text/javascript">

function fn_SEND_EMAIL(frm){
			frm.submit();	
}


//create timer for 1 hour
var int=window.setInterval("fn_SEND_EMAIL(document.frm1)",<?Php echo $iTIMER_GAP; ?>);
	

</script>
</head>

<body>
<form name="frm1" id="frm1" action="mass_email.php" method="post">
<input type="hidden" name="action" value="email" />
<input type="hidden" name="sender" value="<?Php echo $sSENDER;?>" />
<input type="hidden" name="mid" value="<?Php echo $sMESSAGE_ID;?>" />
</form>
</body>
</html>
