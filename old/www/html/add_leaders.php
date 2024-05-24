<?Php
include('inc_connection.php');
include('inc_function.php');
require("class.phpmailer.php");

$sSQL	=	"SELECT * FROM tbl_departments WHERE dept_id NOT IN (
'1500',
'1520',
'2540',
'2860',
'3917',
'3922',
'4421',
'4545',
'4600',
'7221',
'7241',
'7281',
'7291',
'7312',
'7322',
'8225',
'8235',
'8253',
'8265',
'8295',
'8421',
'8432',
'8442',
'8462',
'8472',
'8482',
'9015',
'9025',
'9035',
'9082',
'9092',
'9293',
'9303',
'9313',
'9323',
'9343'
) ORDER BY dept_id";
$rsDEPT	=	mysql_query($sSQL) or die(mysql_error());
	$sMessage	=	"Message is been sent";
if(mysql_num_rows($rsDEPT)>0){
	while($rowDEPT	=	mysql_fetch_array($rsDEPT)){
		$sUSER_PWD			=	fn_generatePassword();
		$sSQL	=	"INSERT INTO tbl_user(f_name, l_name, dept_id, phone, email, password, end_permit, active, user_group, user_type, new_user, photo) ".
					"VALUES('".$rowDEPT["leader_f_name"]."', '".$rowDEPT["leader_l_name"]."', '".$rowDEPT["dept_id"]."', '".$rowDEPT["leader_phone"]."', '".$rowDEPT["leader_email"]."', '".$sUSER_PWD."', '".date('Y')."-12-31', 1, ".$iGROUP_DEPT_LEADER.", 'Staff', 0, 'noimage.gif')";	
		mysql_query($sSQL) or die(mysql_error());
		//print("INSERTED==".$rowDEPT["leader_f_name"]."-".$rowDEPT["leader_l_name"]."<BR />");
		
		$sSUBJECT		=	"Notification to Leaders - New process in Reservation System";
		$sMailMSG		=	"Dear School or Department Leader:
		
		<br /><br />

		It is our policy to suspend all campus driving permits at the end of the year.  To prepare for the renewal of these driving permits, please do the following:
		<br /><br />

		Login to the reservation system AND use the system-generated password shown below.  Set your group to the <b>new</b> Leader option when you log in.  You can change your password to something you like better after this login.  
		<br /><br />
		Because we are in the midst of this change, please wait two or more days from receiving this message to use this procedure.
		<br /><br />
		
		Your Password is <b>$sUSER_PWD</b>
		<br /><br />
		<b>Thank you,<br />
		Steve Foth <br />
		Your Transportation Team</b>
		";
		//print("<br />".$sMailMSG);
		$mail = new PHPMailer();
		$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
		$mail->From     = $sSUPPORT_EMAIL;
		$mail->FromName = $sCOMPANY_Name;
		$mail->AddAddress($rowDEPT["leader_email"]);
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  $sSUBJECT;
		$mail->Body    	= $sMailMSG;
		if(!$mail->Send()){			   $sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email to ".$rowDEPT["leader_f_name"]." ".$rowDEPT["leader_l_name"],"C_ERROR");	}
		
	}
	
	print $sMessage;
}mysql_free_result($rsDEPT);
?>