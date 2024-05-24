<?
	session_start();
	include('inc_connection.php');
	// nothing is echoed
	include('inc_function.php');
	// sets up variable, nothing would appear to be directly echoed.
	require("class.phpmailer.php");
	// used when an email needs to be sent


/*
This form does not remember what was previously entered it just wipes and asks to continue.
This form needs to remember:
txtfname
txtlname
txtcountry








*/
	$sMessage		=	"";
	$bPictureErr	=	false;

	$sPhotoSQL		=	", photo";
	$sUserPhoto		=	"noimage.gif";

	$iUSER_ID		=	0;
	$iLEADER_ID		=	0;
	$sUSER_PWD		=	"";
	$bActive		   =	"0";
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


	if(isset($_POST["action"])	&& $_POST["action"]=="adduser"){

			  //checking errors
			  	if(!isset($_POST['txtfname']) || $_POST['txtfname']==""){
			  		$arrERR[]		=	"<li>Please enter your first name";
					$txtfname = "";
			  	}elseif(!preg_match('/^[a-zA-Z -]+$/',$_POST['txtfname'])){
			  		$arrERR[]		=	"<li>Please enter valid first name";
					$txtfname = $_POST['txtfname'];
			  	}

			  	if(!isset($_POST['txtlname']) || $_POST['txtlname']==""){
			  		$arrERR[]		=	"<li>Please enter your last name";
					$txtlname = "";
			  	}elseif(!preg_match('/^[a-zA-Z -]+$/',$_POST['txtlname'])){
			  		$arrERR[]		=	"<li>Please enter valid last name";
					$txtlname = $_POST['txtlname'];
			  	}

			  	if(!isset($_POST['txtcountry']) || $_POST['txtcountry']==""){
					$arrERR[]		=	"<li>Please enter your home country";
					$txtcountry = "";
			  	}elseif(!preg_match('/^[a-zA-Z -]+$/',$_POST['txtcountry'])){
					$arrERR[]		=	"<li>Please enter valid home country name";
					$txtcountry = $_POST['txtcountry'];
				}

			  	if (!isset($_POST['txtemail']) || $_POST['txtemail']==""){
					$arrERR[]		=	'<li>please enter your email';
					$txtemail = "";
				}elseif(!preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$_POST['txtemail'])){
					//Prev Reg Exp	--> /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/
					$arrERR[]		=	'<li>please enter valid email address.';
					$txtemail = $_POST['txtemail'];
				}else{
					//check existence of email and Username
					$sSQL			=	"SELECT user_id FROM tbl_user WHERE email = '".mysql_real_escape_string($_POST["txtemail"])."'";
					$rsEMAIL_CHECK	=	mysql_query($sSQL) or die(mysql_error());
					if (mysql_num_rows($rsEMAIL_CHECK)>0){
						$arrERR[]		="<li class='bold-font'>user already registered with this email address";
					}mysql_free_result($rsEMAIL_CHECK);
					$txtemail = $_POST['txtemail'];
				}

				if ($_POST['txtphone']=="use hyphens"){
					$arrERR[]		='<li>please enter your phone number';
				}elseif(!preg_match('/^(\({1}\d{3}\){1}|\d{3})(\s|-|.)\d{3}(\s|-|.)\d{4}$/',$_POST['txtphone'])){
					$arrERR[]		=	'<li>please enter valid phone number';
				}
				$txtphone = $_POST['txtphone'];

				if ($_POST['drpmonth'] == "" || $_POST['drpday'] == "" || $_POST['drpyear'] == ""){
					$arrERR[]		='<li>please select your date of birth';
				}else{
					$sBirthDate			=	$_POST["drpyear"]."-".$_POST["drpmonth"]."-".$_POST["drpday"];
					//check the combination for duplicate register
					$sSQL	=	"SELECT user_id FROM tbl_user WHERE LOWER(l_name) = '".strtolower(mysql_real_escape_string($_POST["txtlname"]))."' AND birth_date = '".$sBirthDate."'";
					$rsDUP_COMBINATION	=	mysql_query($sSQL) or die(mysql_error());
					if(mysql_num_rows($rsDUP_COMBINATION)>0){
						$arrERR[]		="<li class='bold-font'>You can not re-register at this time. Contact Transportation Administrator for more information";
					}mysql_free_result($rsDUP_COMBINATION);
				}
				$drpmonth = $_POST['drpmonth'];
				$drpday = $_POST['drpday'];
				$drpyear = $_POST['drpyear'];

				if (!isset($_POST['txtlicenseno']) || $_POST['txtlicenseno']==""){
					$arrERR[]			='<li>please enter your govt. license no';
					$txtlicenseno = "";
				}elseif(!preg_match('/^[a-zA-Z0-9_\-]+$/',$_POST['txtlicenseno'])){
					$arrERR[]			='<li>please enter valid license no';
					$txtlicenseno = $_POST['txtlicenseno'];
				}

				if (!isset($_POST['txtlicensestate']) || $_POST['txtlicensestate']==""){
					$arrERR[]			='<li>please enter your license state';
					$txtlicensestate = "";
				}elseif(!preg_match('/^[a-zA-Z0-9_\ -]+$/',$_POST['txtlicensestate'])){
					$arrERR[]			='<li>please enter valid license state';
					$txtlicensestate = $_POST['txtlicensestate'];
				}

				if (!isset($_POST['txtlicensecountry']) || $_POST['txtlicensecountry']==""){
					$arrERR[]			='<li>please enter your license country';
					$txtlicensecountry = "";
				}elseif(!preg_match('/^[a-zA-Z0-9_\ -]+$/',$_POST['txtlicensecountry'])){
					$arrERR[]			='<li>please enter valid license country';
					$txtlicensecountry = $_POST['txtlicensecountry'];
				}

				if (!isset($_POST['txtmaxpassengers']) || $_POST['txtmaxpassengers']==""){
					$arrERR[]			='<li>please enter max passengers allowed by license';
					$txtmaxpassengers = "";
				}elseif(!preg_match('/^[0-9]+$/',$_POST['txtmaxpassengers'])){
					$arrERR[]			='<li>please enter valid max passengers';
					$txtmaxpassengers = $_POST['txtmaxpassengers'];
				}elseif(intval($_POST['txtmaxpassengers']) < 5 || intval($_POST['txtmaxpassengers']) > 19){
					$arrERR[]			='<li>max allowed passengers must be in range 5-19';
					$txtmaxpassengers = $_POST['txtmaxpassengers'];
				}

				if (!isset($_POST['txtlicenseexpire']) || $_POST['txtlicenseexpire']==""){
					$arrERR[]			='<li>please select license expire date';
					$txtlicenseexpire = "";
				}

				if(!isset($_POST['txtdrivetest']) || $_POST['txtdrivetest']==""){
			  		$arrERR[]		='<li>please enter Name of staff person who tested you';
					$txtdrivetest = "";
			  	}elseif(!preg_match('/^[a-zA-Z -]+$/',$_POST['txtdrivetest'])){
			  		$arrERR[]		=	"<li>please enter valid staff person name who tested you";
					$txtdrivetest = $_POST['txtdrivetest'];
			  	}
				if (!isset($_POST['txttestdate']) || $_POST['txttestdate']==""){
					$arrERR[]			='<li>please select Date tested here';
					$txttestdate = "";
				} else {
					$txttestdate = $_POST['txttestdate'];
				}
				if (!isset($_POST['drpusertype']) || $_POST['drpusertype']==""){
					$arrERR[]		='<li>please select user type';
					$drpusertype = "";
				} else {
					$drpusertype = $_POST['drpusertype'];
				}
				if (!isset($_POST['drpdepartment']) || $_POST['drpdepartment']==""){
					$arrERR[]		='<li>please select your department';
					$drpdepartment = "";
				} else {
					$drpdepartment = $_POST['drpdepartment'];
				}
				if (!isset($_POST['chkterms'])){
					$arrERR[]		='<li>you must agree with the drivers agreement';
					$txtlicenseexpire = "";
				} else {
					$chkterms = $_POST['chkterms'];
				}

				if (!isset($_POST["captcha"]) || $_POST["captcha"]==""){
					$arrERR[]		='<li>Please enter security image letters';
				}elseif(strtolower($_POST["captcha"])!=strtolower($_SESSION['securimage_code_value'])) {
					$arrERR[]		="<li class='bold-font'>Image Verification failed!. Go back and try again.";
				}

			  if (!empty($arrERR)){
					foreach($arrERR	as	$err){ $sMessage		.=	$err; }
					$sMessage =	fn_Print_MSG_BOX($sMessage,"C_ERROR");
				}

			  	if($sMessage==""){

					$sUSER_PWD			=	fn_generatePassword();
					$sTestDate			=	fn_DATE_TO_MYSQL($_POST["txttestdate"]);
					$sLicenseExpire =	fn_DATE_TO_MYSQL($_POST["txtlicenseexpire"]);

					// no need for a if(isset($_POST["txtpermitdate"]) && $_POST["txtpermitdate"]!="") {
					// as we default the date to the end of this quartre.
					// The four quarters 03/31 06/30 09/30 12/31
						$tYear = date('Y');
						$tDate =	date('Y-m-d');
						$Qrtr = array(1 => $iYear . "-03-31", 2 => $iYear . "-06-30", 3 => $iYear . "-09-30", 4 => $iYear . "-12-31");
						
						if ( $Qrtr[1] > $tDate ) {
							$txtpermitdate = $Qrtr[1];
						} elseif($Qrtr[2] > $tDate) {
							$txtpermitdate = $Qrtr[2];
						} elseif($Qrtr[3] > $tDate) {
							$txtpermitdate = $Qrtr[3];
						} else {
							$txtpermitdate = $Qrtr[4];
						}			
						$sEndPermit = fn_DATE_TO_MYSQL($txtpermitdate);

					if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!=""){$sGROUP_SQL_VAL	=	mysql_real_escape_string($_POST["drpusergroup"]);	}	//if tm or admin
					if($bCODE_WORD	==	true && $bLEADER_CODE	==	true)		$sGROUP_SQL_VAL	=	$iGROUP_DEPT_LEADER;		//leader is trying to register

					$sSQL="INSERT INTO  tbl_user(f_name, l_name, dept_id, phone, birth_date, license_no, license_state, license_country, max_passengers, license_expire, email, ".
					"password, drive_tested, test_date, end_permit, home_st_country, user_group, user_type ".
					$sPhotoSQL.", comment, renew_text, new_user) ".
					"VALUES('".mysql_real_escape_string($_POST["txtfname"])."', '".mysql_real_escape_string($_POST["txtlname"])."', '". mysql_real_escape_string($_POST["drpdepartment"]) ."', '".mysql_real_escape_string($_POST["txtphone"])."', '".$sBirthDate."', '".mysql_real_escape_string($_POST["txtlicenseno"])."', '".mysql_real_escape_string($_POST["txtlicensestate"])."', '".mysql_real_escape_string($_POST["txtlicensecountry"])."', '".mysql_real_escape_string($_POST["txtmaxpassengers"])."', '".$sLicenseExpire."', '".mysql_real_escape_string($_POST["txtemail"])."', ".
					"'".$sUSER_PWD."', '".mysql_real_escape_string(addslashes($_POST["txtdrivetest"]))."', '".$sTestDate."', '".$sEndPermit."', '".mysql_real_escape_string($_POST["txtcountry"])."', ".$sGROUP_SQL_VAL.", '".mysql_real_escape_string($_POST["drpusertype"])."', ".
					"'".$sUserPhoto."', '', '".mysql_real_escape_string(addslashes($_POST["txtrenewtext"]))."', 1)";
					//print($sSQL);
					$rsMEMBER=mysql_query($sSQL) or die(mysql_error());

					$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 13";
					$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
					$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 13";
					$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));


					//$sMessage	=		fn_Print_MSG_BOX($sMailMSG,"C_ERROR");

					//print($sMailMSG);
					$mail = new PHPMailer();
					$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
					$mail->From     = $sSUPPORT_EMAIL;
					$mail->FromName = $sCOMPANY_Name;
					$mail->AddAddress(mysql_real_escape_string($_POST["txtemail"]));
					$mail->IsHTML(true);                               // send as HTML
					$mail->Subject  =  $sEmailSubject;
					$mail->Body    = $sMailMSG;
					if(!$mail->Send())
					{
					   $sMessage		=	fn_Print_MSG_BOX("You are registered successfully, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
					}else{
						$sMessage		=	fn_Print_MSG_BOX("you are registered successfully, <br />an email has been sent to your registered email address for your account details<br />you will be able to login after the verification of your details by TM", "C_SUCCESS");
					}


				}




} else {
	$txtfname = "";
	$txtlname = "";
	$txtcountry = "";
	$txtemail = "";
	$txtphone = "";
	$drpmonth = "";
	$txtlicenseno = "";
	$txtlicensestate = "";
	$txtmaxpassengers = "";
	$txtlicensecountry = "";
	$txtmaxpassengers = "";
	$txtlicenseexpire = "";
	$txtdrivetest = "";
	$txttestdate = "";
	$drpusertype = "";
	$drpdepartment = "";
	$chkterms = "";
	
}
//question greater than.
$render = '<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Add User</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="generator" content="Bluefish 2.2.8" >

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<!-- firebug lite -->
		<script type="text/javascript" src="./js/firebug.js"></script>

        <!-- jQuery -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>

        <!-- required plugins -->
		<script type="text/javascript" src="./js/date.js"></script>
		<!--[if lt IE 7]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->

        <!-- jquery.datePicker.js -->
		<script type="text/javascript" src="./js/jquery.datePicker.js"></script>

        <!-- datePicker required styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/datePicker.css">

        <!-- page specific styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/demo.css">

        <!-- page specific scripts -->
		<script type="text/javascript" charset="utf-8">
			Date.format = \'mm/dd/yyyy\';
            $(function()
            {
				$(\'.date-pick\').datePicker({startDate: \'01/01/1930\', autoFocusNextInput: true});
            });
		</script>
		<script type="text/javascript" src="./js/common_scripts.js"></script>
		<script type="text/javascript" src="./js/popup.js"></script>
<script type="text/javascript">
function valid_user(frm){

	var sErrMessage=\'\';
	var iErrCounter=0;

	if (frm.txtfname.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your first name\';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.\'-]/i;
		if (!validate_field(frm.txtfname, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid first name\';
			iErrCounter++;
		}
	}

	if (frm.txtlname.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your last name\';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.\'-]/i;
		if (!validate_field(frm.txtlname, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid last name\';
			iErrCounter++;
		}
	}

	if (frm.txtcountry.value==""){
		sErrMessage=sErrMessage+\'<li>please enter home country\';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.\'-]/i;
		if (!validate_field(frm.txtcountry, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid country name\';
			iErrCounter++;
		}
	}

	if (frm.txtemail.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your email\';
		iErrCounter++;
	}else{
		/*regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;*/
		regExp=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (!regExp.test(Trim(frm.txtemail.value))){
			sErrMessage=sErrMessage+\'<li>please enter valid email address\';
			iErrCounter++;
		}
	}

	if (frm.txtphone.value=="use hyphens"){
		sErrMessage=sErrMessage+\'<li>please enter your phone number\';
		iErrCounter++;
	}else{
		regExp	=	/\d{3}\-\d{3}\-\d{4}/;
		if (!regExp.test(frm.txtphone.value)){
			sErrMessage=sErrMessage+\'<li>please enter valid phone number\';
			iErrCounter++;
		}
	}

	/*if (frm.txtbirthdate.value == ""){
		sErrMessage=sErrMessage+\'<li>please select your date of birth\';
		iErrCounter++;
	}*/

	if (frm.drpmonth.value == "" || frm.drpday.value == "" || frm.drpyear.value == ""){
		sErrMessage=sErrMessage+\'<li>please select your date of birth\';
		iErrCounter++;
	}

	if (frm.txtlicenseno.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your govt. license no\';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicenseno, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid license no\';
			iErrCounter++;
		}
	}

	if (frm.txtlicensestate.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your license state\';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicensestate, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid license state\';
			iErrCounter++;
		}
	}

	if (frm.txtlicensecountry.value==""){
		sErrMessage=sErrMessage+\'<li>please enter your license country\';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicensecountry, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid license country\';
			iErrCounter++;
		}
	}

	if (frm.txtmaxpassengers.value==""){
		sErrMessage=sErrMessage+\'<li>please enter max allowed passengers\';
		iErrCounter++;
	}else{
		regExp = /[0-9]/i;
		var maxPasengers = parseInt(frm.txtmaxpassengers.value);
		if (!validate_field(frm.txtmaxpassengers, regExp) || maxPasengers < 5 || maxPasengers > 19){
			sErrMessage=sErrMessage+\'<li>max allowed passengers must be in range 5-19\';
			iErrCounter++;
		}
	}

	if (frm.txtlicenseexpire.value == ""){
		sErrMessage=sErrMessage+\'<li>please select license expire date\';
		iErrCounter++;
	}

	if (frm.txtdrivetest.value == ""){
		sErrMessage=sErrMessage+\'<li>please enter Name of staff person who tested you\';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.\'-]/i;
		if (!validate_field(frm.txtdrivetest, regExp)){
			sErrMessage=sErrMessage+\'<li>please enter valid staff person name who tested you\';
			iErrCounter++;
		}
	}


	if (frm.txttestdate.value == ""){
		sErrMessage=sErrMessage+\'<li>please select Date tested here\';
		iErrCounter++;
	}';

	/*if (frm.txtendpermit.value == ""){
		sErrMessage=sErrMessage+\'<li>please select end UN permit\';
		iErrCounter++;
	}*/
	if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_TM ||$_SESSION["User_Group"]==$iGROUP_TC)){
$render.='
	if (frm.drpusergroup.value == ""){
		sErrMessage=sErrMessage+\'<li>please select group level\';
		iErrCounter++;
	}';
	}
$render.='
	if (frm.drpusertype.value == ""){
		sErrMessage=sErrMessage+\'<li>please select user type\';
		iErrCounter++;
	}

	if (frm.drpdepartment.value == ""){
		sErrMessage=sErrMessage+\'<li>please select your department\';
		iErrCounter++;
	}
	if(frm.chkterms.checked==false){
		sErrMessage=sErrMessage+\'<li>you must agree with the drivers agreement\';
		iErrCounter++;
	}

	//alert(frm.recaptcha_response_field.value);
	if(frm.captcha.value==""){
		sErrMessage=sErrMessage+\'<li>please enter image verification letters\';
		iErrCounter++;
		$(\'#contactArea\').html("<h1 class=\'notice-heading\'>Error!!!</h1><br /><br /><b>Please correct the following errors!</b><br /><br />");
		centerPopup();
		loadPopup();
		fn_draw_ErrMsg(sErrMessage);
	}else{
		$.get("ajax_data.php", {action: \'captcha\', captcha: $(\'#captcha\').val()}, function(data){
				if (data=="ERROR"){
					sErrMessage=sErrMessage+\'<li>please enter correct image varification letters\';
					iErrCounter++;
					$(\'#contactArea\').html("<h1 class=\'notice-heading\'>Error!!!</h1><br /><br /><b>Please correct the following errors!</b><br /><br />");
					centerPopup();
					loadPopup();
					fn_draw_ErrMsg(sErrMessage);
				}else{

					frm.submit();
				}
		}, \'html\');

	}

	/*if (iErrCounter >0){

		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();*/

}
function fn_PERMIT(frm, sMethod){

	if(sMethod==\'renew\'){
		document.getElementById(\'Renew_Permit\').style.display=\'block\';
	}else if(sMethod==\'first\'){
		document.getElementById(\'Renew_Permit\').style.display=\'none\';

	}

}
</script>

</head>';
// on to the body
$render.='
	<body style="margin: 0px;">
	<div align="center">
		<table border="0" cellspacing="0" cellpadding="0">
  			<!--start header	-->';
  			
  			
ob_start();
include 'inc_header.php';
$output = ob_get_clean();  			
$render.= $output;

$render.='<!-- start side nav	-->


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
                 		<table border="0" cellspacing="0" cellpadding="0" width="949" style="background-image: url(../assets/images/banner.png); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								 		<tr>
								  			<td class="TextObject" align="center">
								   				<h1 style="margin-bottom: 0px;">Driver Registration-Step 2</h1>
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
						<form name="frm1" action="adduser.php" method="post">
							<input type="hidden" name="action" value="adduser"	/>

							<table cellpadding="0" cellspacing="5" border="0" width="650" align="center" class="box">

								<tr><td colspan="2" id="Message" width="100%">'.$sMessage.'</td></tr>
								<tr>
									<td class="label" valign="top" width="250">Describe any <br />accidents or tickets <br />in last 2 years:</td>
									<td width="400">
									<textarea name="txtrenewtext" id="txtrenewtext" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);" onKeyUp="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);"></textarea>
									&nbsp;<input readonly type="text" name="txtrenewLength" value="200" style="width:25px;">
									</td>
								</tr>
								<tr>
									<td class="label">First Name:</td>';
$render.='						<td><input type="text" name="txtfname" value="'.$txtfname.'" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Last Name:</td>
									<td><input type="text" name="txtlname" value="'.$txtlname.'" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Home Country:</td>
									<td><input type="text" name="txtcountry" value="'.$txtcountry.'" maxlength="30" style="width:100px;"  /></td>
								</tr>
								<tr>
									<td class="label">Email:</td>
									<td><input type="text" name="txtemail" value="'.$txtemail.'" maxlength="150" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Phone:</td>
									<td><input type="text" name="txtphone" onclick="this.value=\'\';" onblur="this.value=!this.value?\'use hyphens\':this.value;" value="use hyphens" maxlength="12" style="width:100px;" />&nbsp;xxx-xxx-xxxx</td>
								</tr>
								<tr>
									<td class="label">Date of Birth:</td>
									<td>';
$render.= "<select name='drpmonth' size='1' style='width:100px;'>";
$render.= "<option value=''>Month</option>";
for($iCounter=1;$iCounter<=12;$iCounter++){
	if($iCounter<10) $iMONTH	=	"0".$iCounter; else $iMONTH	=	$iCounter;
	$render.= "<option value='".$iMONTH."'>".date("F", mktime(0, 0, 0, $iCounter, 10))."</option>";
}
$render.= "</select>";
$render.= "&nbsp;&nbsp;";
$render.= "<select name='drpday' size='1' style='width:70px;'>";
$render.= "<option value=''>Day</option>";
for($iCounter=1;$iCounter<=31;$iCounter++){
	if($iCounter<10) $iMONTH	=	"0".$iCounter; else $iMONTH	=	$iCounter;
	$render.= "<option value='".$iMONTH."'>".$iMONTH."</option>";
}
$render.= "</select>";
$render.= "&nbsp;&nbsp;";
$render.= "<select name='drpyear' size='1' style='width:70px;'>";
$render.= "<option value=''>Year</option>";
for($iCounter=1900;$iCounter<=2025;$iCounter++){
	$render.= "<option value='".$iCounter."'>".$iCounter."</option>";
}
$render.= "</select>";
$render.= '
									</td>
								</tr>
								<tr>
									<td class="label">Govt. License No:</td>
									<td><input type="text" name="txtlicenseno" id="txtlicenseno" value="'.$txtlicenseno.'" maxlength="20" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">License State:</td>
									<td><input type="text" name="txtlicensestate" id="txtlicensestate" value="'.$txtlicensestate.'" maxlength="15" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">License Country:</td>
									<td><input type="text" name="txtlicensecountry" id="txtlicensecountry" value="'.$txtlicensecountry.'" maxlength="60" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">Govt. License Expires:</td>
									<td><input readonly="" type="text" name="txtlicenseexpire" id="txtlicenseexpire" value="'.$txtlicenseexpire.'" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Maximum Allowed Passengers:</td>
									<td><input type="text" name="txtmaxpassengers" id="txtmaxpassengers" value="'.$txtmaxpassengers.'" maxlength="2" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">Name of staff person who tested you:</td>
									<td><input type="text" name="txtdrivetest" id="txtdrivetest" value="'.$txtdrivetest.'" maxlength="30" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">Date tested here:</td>
									<td><input readonly="" type="text" name="txttestdate" id="txttestdate" value="'.$txttestdate.'" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>';
if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC)){
	$render.='				<tr>
									<td class="label"><font color=red>This permit expires</font></td>
									<td><input type="text" name="txtpermitdate" id="txtpermitdate" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Group:</td>
									<td>'.	fn_USER_GROUP("drpusergroup", "", "200", "1", "Select User Group") .'</td>
								</tr>';
}
$render.='					<tr>
									<td class="label">Type:</td>
									<td>';
									
ob_start();
fn_USER_TYPE("drpusertype", "", "200", "1", "Select User Type");
$output = ob_get_clean();  			
$render.= $output;									
$render.='						</td>
								</tr>
								<tr>
									<td class="label">Home Dept:</td>
									<td>';
									
ob_start();
fn_DEPARTMENT("drpdepartment", "", "200", "1", "Select Department");
$output = ob_get_clean();  			
$render.= $output;									
$render.='						</td>
								</tr>


								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td class="label">&nbsp;</td>
									<td class="label"><input type="checkbox" name="chkterms" value="1" />I have read and will comply with the Drivers Agreement</td>
								</tr>
								<tr>
									<td class="label">Image Verification</td>
									<td>
										<div style="float:left;">
									  <img id="siimage" align="left" src="securimage/securimage_show.php?sid='.rand().'" style="border: 0pt none ; padding-right: 5px;"/>
										<a onclick="document.getElementById(\'siimage\').src = \'securimage/securimage_show.php?sid=\' + Math.random(); return false" title="Refresh Image" href="#" style="border-style: none;" >
											<img border="0" align="bottom" onclick="this.blur()" alt="Reload Image" src="securimage/images/refresh.gif"/>
										</a>
										</div>
										<div style="clear:both;"></div>
										<div style="float:left;">
										<input type="text" name="captcha" id="captcha" value="" maxlength="4" style="width:200px;"	/>
										</div>
									  </td>
								  </tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><span class="Highlight">Next step: TM must activate your account</span></td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="REQUEST REGISTRATION" class="Button" onClick="valid_user(this.form);" style="width:150px;" /></td></tr>
							</table>
						</form>
                	</td>
                	<td></td>
               	</tr>
			</table>
		</td>


		<!-- end actual page	-->

      <!-- footer	-->';

ob_start();
include 'inc_footer.php';
$output = ob_get_clean();  			
$render.= $output;



$render.='
     </table>
    </td>
   </tr>
  </table>
 </div>
</body>
</html>
<div id="popupContact">
	<div id="contactArea" style="padding-left:10px;"></div>
	<br /><br />
	<div style="text-align:center; width:100%; margin:0 auto;">
		<input type="button" name="btnclose" value="OK" class="Button" id="popupClose" style="width:100px;" onClick="disablePopup();" />
	</div>
	<br /><br />
</div>
<div id="backgroundPopup"></div>';

echo $render;
?>