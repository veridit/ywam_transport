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
	$bDRIVER_LOGGED_IN	=	false;
	$bDRIVER_PULLDOWN	=	false;
	$sGROUP_SQL_VAL		=	$iGROUP_DRIVER;
	
	$bUPDATION_ERROR	=	false;
	$bDEPT_STATUS		=	1;
	$sDEPT_SQL			=	"";
	
	$sREADONLY_FIELD	=	"";
	
	$sEndPermit			=	date("Y")."-12-31";
	
	
	
	//==============VARIABLES=====
	$sACCIDENTS			=	"";		$sRENEW_DATE			=	"";
	$sFNAME				=	"";		$sLNAME					=	"";		$sEMAIL			=	"";		$sPHONE			=	"";		$sDATE_OF_BIRTH		=	"0000-00-00";		$sGOVT_LICENCE		=	"";
	$sLICENCE_STATE		=	"";		$sGOVT_LICENCE_EXPIRE	=	"";		$sSTAFF_TESTED	=	"";		$sDATE_TESTED	=	"";		$bDRIVER_PERMISSION	=	0;		
	$sPERMIT_EXP_YEAR	=	"0000-00-00";		$sHOME_COUNTRY			=	"";		$iUSER_GROUP	=	"";		$sUSER_TYPE		=	"";		$iHOME_DEPT			=	0;		$sPICTURE			=	"";
	$sPIC_LINK			=	"";
	
	if(isset($_REQUEST["userid"]))
		$iUSER_ID		=	$_REQUEST["userid"];
	else
		$iUSER_ID		=	$_SESSION["User_ID"];
	
	if(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)  && !isset($_REQUEST["userid"])){$bDRIVER_PULLDOWN	=	true;}
	
	if($bDRIVER_PULLDOWN	==	true){
		if(isset($_POST["drpuser"]))	$iUSER_ID	=	mysql_real_escape_string($_POST["drpuser"]);	else		$iUSER_ID		=	0;	
	}
	
	
		
	if(isset($_POST["action"])	&& $_POST["action"]=="active"){		
				
		$sSQL		=	"UPDATE tbl_user SET active = 1, new_user = 0, status_date = '".date('Y-m-d H:i:s')."' WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		if(!fn_SEND_EMAIL_TO_USER(14, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
			$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>user has been activated, but error in sending email","C_SUCCESS");
		}else{
			$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>user has been activated","C_SUCCESS");
		}
		
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="inactive"){
		$sSQL		=	"UPDATE tbl_user SET active = 0, status_date = '".date('Y-m-d H:i:s')."' WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>user de-activated","C_SUCCESS");
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="active-bad"){
		$sSQL		=	"UPDATE tbl_user SET active = 1, status_date = '".date('Y-m-d H:i:s')."' WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		//print("========".fn_SEND_EMAIL_TO_USER(25, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name));
		if(!fn_SEND_EMAIL_TO_USER(25, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
			$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>user has been activated, but error in sending email","C_SUCCESS");
		}else{
			$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>user has been activated","C_SUCCESS");
		}
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="edituser"){
	
		
		$bPictureErr	=	false;
		$sUserPhoto		=	"";
		$sPhotoSQL		=	"";
		$sUSER_PWD		=	"";
		$sTestDate		=	"";
		$sGroupLevel	=	"";
		$sPERMIT_SQL	=	"";
		$OldPassword=	mysql_real_escape_string($_POST["txtoldpassword"]);
		$NewPassword=	mysql_real_escape_string($_POST["txtpassword"]);		
		
			
			
			if ($bPictureErr==false){
			
				$sTestDate		=	fn_DATE_TO_MYSQL($_POST["txttestdate"]);
				if(isset($_POST["drpyear"]) && $_POST["drpyear"]!="")	$sPERMIT_SQL	=	", birth_date			=	'".$_POST["drpyear"]."-".$_POST["drpmonth"]."-".$_POST["drpday"]."'";
				$sLicenseExpire	=	fn_DATE_TO_MYSQL($_POST["txtlicenseexpire"]);
				if(isset($_POST["txtrenewdate"]) && $_POST["txtrenewdate"]!="")
					$sRenwPermitDate		=	fn_DATE_TO_MYSQL($_POST["txtrenewdate"]);
				else
					$sRenwPermitDate		=	date('Y-m-d');				
				
				
				if(isset($_POST["optpermitexpireyear"]))	$sEndPermit			=	$_POST["optpermitexpireyear"]."-12-31";
				if(isset($_POST["bdriverpermission"]) && $_POST["bdriverpermission"]!="")			$sPERMIT_SQL	.=	", driver_permission	=	".$_POST["bdriverpermission"];
							
						
				if($_POST["supdatetype"]=='renew-driver-request'){
				
					$sPERMIT_SQL	.=	", end_permit = '".$sEndPermit."' , reg_date = '".date('Y-m-d H:i:s')."', renew_date = '".$sRenwPermitDate."', renew_text = '".mysql_real_escape_string(addslashes($_POST["txtrenewtext"]))."', active = 0, new_user = 1, status_date = '".date('Y-m-d H:i:s')."'";
				}
				
				
				//renewal actual process
				if($_POST["supdatetype"]=='renew-permit'){
					//if(isset($_POST["optpermitexpireyear"]))	$sEndPermit			=	$_POST["optpermitexpireyear"]."-12-31";							
					$sPERMIT_SQL		.=	", end_permit = '".$sEndPermit."' , renew_date = '".$sRenwPermitDate."', renew_text = '".mysql_real_escape_string(addslashes($_POST["txtrenewtext"]))."', active = 1, new_user = 0, status_date = '".date('Y-m-d H:i:s')."'";
				}
				
				//check inactive department
				if(isset($_POST["drpdepartment"]) && $_POST["drpdepartment"]!=""){
					$sSQL			=	"SELECT active FROM tbl_departments WHERE dept_id = '".mysql_real_escape_string($_POST["drpdepartment"])."'";
					$bDEPT_STATUS	=	mysql_result(mysql_query($sSQL), 0);
					$sDEPT_SQL		=	", dept_id=	'". mysql_real_escape_string($_POST["drpdepartment"])."'";
				}
				if($bDEPT_STATUS==0){
					$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>selected department is inactive, please select some different department", "C_SUCCESS");
					$bUPDATION_ERROR	=	true;
				}//else{
				
				//if tm or admin
				if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!=""){
					$sGROUP_SQL_VAL	=	mysql_real_escape_string($_POST["drpusergroup"]);
					//select previouos user group
					$sSQL				=	"SELECT user_group FROM tbl_user WHERE user_id = ".$iUSER_ID;
					//print($sSQL);
					$iUSER_PREV_GROUP	=	mysql_result(mysql_query($sSQL),0);
					
					if($iUSER_PREV_GROUP!=$sGROUP_SQL_VAL){		//check if prev and this group are not same
						$sSQL	=	"SELECT user_id FROM tbl_user WHERE user_group = ".$sGROUP_SQL_VAL." AND LOWER(email) = '".strtolower(mysql_real_escape_string($_POST["txtemail"]))."'";
						$rsDUP_GROUP		=mysql_query($sSQL) or die(mysql_error());
						if(mysql_num_rows($rsDUP_GROUP)>0){
							$sMessage			=	fn_Print_MSG_BOX("<li class='bold-font'>User is already registered with the same email in the selected group, please choose some different group","C_ERROR");
							$bUPDATION_ERROR	=	true;
						}mysql_free_result($rsDUP_GROUP);
					}
					
				}
				
				if($bUPDATION_ERROR==false){

					// $sSQL="UPDATE  tbl_user SET f_name='".mysql_real_escape_string($_POST["txtfname"])."', l_name='".mysql_real_escape_string($_POST["txtlname"])."' ".$sDEPT_SQL.", phone='".mysql_real_escape_string($_POST["txtphone"])."', ".
					// "license_no = '".mysql_real_escape_string($_POST["txtlicenseno"])."', license_state = '".mysql_real_escape_string($_POST["txtlicensestate"])."', license_expire = '".$sLicenseExpire."', drive_tested='".mysql_real_escape_string(addslashes($_POST["txtdrivetest"]))."', test_date='".$sTestDate."', home_st_country='".mysql_real_escape_string($_POST["txtcountry"])."', user_group=".$sGROUP_SQL_VAL.", user_type = '".mysql_real_escape_string($_POST["drpusertype"])."' ".
				
					$sSQL="UPDATE  tbl_user SET password=PASSWORD('$NewPassword').

					$sPhotoSQL.", permit_type =  '".mysql_real_escape_string($_POST["optpermit"])."'".$sPERMIT_SQL." WHERE user_id = ".$iUSER_ID;
					//print($sSQL);
					$rsMEMBER=mysql_query($sSQL) or die(mysql_error());
					
				
					if($_POST["supdatetype"]=='renew-permit'){						//if renew process
						if(!fn_SEND_EMAIL_TO_USER(21, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
							$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Driver Pemrit has been renewed, <br />but Error in Sending Email!","C_ERROR");
						}
					}//end renew process check
						
					if($_POST["supdatetype"]=='info'){
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Information has been updated", "C_SUCCESS");
					}elseif($_POST["supdatetype"]=='renew-driver-request'){
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>To continue this process, please have the leader of your next school login and sponsor you", "C_SUCCESS");
					}elseif($_POST["supdatetype"]=='renew-permit'){
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Driver Permit has been renewed, an email has been sent to his/her email address!", "C_SUCCESS");
					}
					
				}
				//} //dept status checking end
				
			
			}
					
}

if($iUSER_ID!=0){

	$sSQL	=		"SELECT * FROM tbl_user WHERE user_id = ".$iUSER_ID;
	$rsUSER	=		mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=mysql_num_rows($rsUSER);	
	if($iRECORD_COUNT>0){
		$rowUSER	=	mysql_fetch_array($rsUSER);
		
	if(!is_null($rowUSER['renew_text'])) $sACCIDENTS	=	 stripslashes($rowUSER['renew_text']);
	if(!is_null($rowUSER['renew_date'])) $sRENEW_DATE	=	 fn_cDateMySql($rowUSER['renew_date'],1); else $sRENEW_DATE	=	 date('m/d/Y');
	
	$sFNAME					=	$rowUSER['f_name'];		
	$sLNAME					=	$rowUSER['l_name'];
	$sEMAIL					=	$rowUSER['email'];
	$sPHONE					=	$rowUSER['phone'];
	$sDATE_OF_BIRTH			=	$rowUSER['birth_date'];
	$sGOVT_LICENCE			=	$rowUSER['license_no'];
	$sLICENCE_STATE			=	$rowUSER['license_state'];
	$sGOVT_LICENCE_EXPIRE	=	fn_cDateMySql($rowUSER['license_expire'],1);
	$sSTAFF_TESTED			=	$rowUSER['drive_tested'];
	$sDATE_TESTED			=	fn_cDateMySql($rowUSER['test_date'],1);
	$sPERMIT_EXP_YEAR		=	$rowUSER['end_permit'];		
	$sHOME_COUNTRY			=	$rowUSER['home_st_country'];
	$iUSER_GROUP			=	$rowUSER['user_group'];
	$sUSER_TYPE				=	$rowUSER['user_type'];
	$iHOME_DEPT				=	$rowUSER['dept_id'];
	$bDRIVER_PERMISSION		=	$rowUSER['driver_permission'];
		

	}
	mysql_free_result($rsUSER);
}


//if($_SESSION["User_ID"]==$rowUSER['user_id'] && $_SESSION["User_Group"]==$iGROUP_DRIVER){		$bDRIVER_LOGGED_IN		=	true;	}
if($_SESSION["User_Group"]==$iGROUP_DRIVER){		$bDRIVER_LOGGED_IN		=	true;	$sREADONLY_FIELD	=	"readonly";}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>
<?	if($bDRIVER_LOGGED_IN==true) 
	echo "UPDATE REGISTRATION";
	elseif($bDRIVER_PULLDOWN==true)
	echo "OK PERMIT RENEWAL";
	elseif($_SESSION["User_ID"]==$iUSER_ID)
	echo "UPDATE PROFILE";
	elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)
	echo "ACTIVATE-RENEW-EDIT DRIVER PERMIT";
	
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

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
			Date.format = 'mm/dd/yyyy';
            $(function()
            {
				$('.date-pick').datePicker({startDate: '01/01/1970', autoFocusNextInput: true});
            });
		</script>
		
		<script type="text/javascript" src="./js/common_scripts.js"></script>
		<script type="text/javascript" src="./js/popup.js"></script>
		<?	if(isset($_POST["action"])	&& $_POST["action"]=="edituser" && $bUPDATION_ERROR	==	false){	?>
		
		<script language="JavaScript">
				$(document).ready(function () {
					document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
					<?Php	if($bDRIVER_LOGGED_IN	==	true){?>
					document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Account Suspension</h1><br /><br />Your account is now deactivated:<br /><br />Follow these steps to renew your permit: <br /><br /><b>1-Have the leader of your next school login and sponsor you...and<br /><br />2-Bring your governament licence to Transporation Office</b><br /><br />";
					<?Php	}elseif(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM) && $_SESSION["User_ID"]==$iUSER_ID){?>
					document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Account Updated</h1><br /><br />Your account is updated<br /><br />";
					<?Php	}elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){
								if($_POST["supdatetype"]=='renew-permit'){
					?>
					document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Driver Permit Renewal</h1><br /><br /><b>The permit of this driver is renewed</b><br /><br />";
					<?Php	}else{	?>
					document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Information Updated</h1><br /><br />User account information has been updated</b><br /><br />";
					<?Php			}
						}?>
					centerPopup();
					loadPopup();
				});
		</script>
		<?	}elseif($bUPDATION_ERROR	==	true){	?>
		<script language="JavaScript">
			$(document).ready(function () {
				document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
				document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Error!!!</h1><br /><br /><?Php echo $sMessage;?></b><br /><br />";
				
				centerPopup();
				loadPopup();
			});
		</script>
		<?	}?>
<script type="text/javascript">
function valid_user(frm, sUPDATE_TYPE){

	var sErrMessage='';
	var iErrCounter=0;
	
	<?	//if($bDRIVER_LOGGED_IN	==	true){?>
		/*if (frm.txtrenewdate.value == ""){
			sErrMessage=sErrMessage+'<li>please select permit renew date';
			iErrCounter++;
		}*/
	<?	//}?>

// if (frm.txtpassword.value == ""){
// 	sErrMessage=sErrMessage+'<li>please enter your new password';
// 	iErrCounter++;
// }

// if (frm.txtconfirmpassword.value != "" && frm.txtpassword.value != "") {
// 	if(frm.txtconfirmpassword.value != frm.txtpassword.value){
// 		sErrMessage=sErrMessage+'<li>passwords and confirm password are not same';
// 		iErrCounter++;

// 	}
// }else if(frm.txtpassword.value != "" && frm.txtconfirmpassword.value == ""){
// 	sErrMessage=sErrMessage+'<li>please confirm your new password';
// 	iErrCounter++;
	
// }

	if (frm.txtfname.value==""){
		sErrMessage='<li class="bold-font">please enter your first name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtfname, regExp)){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid first name';
			iErrCounter++;
		}
	}
	
	if (frm.txtlname.value==""){
		sErrMessage=sErrMessage+'<li class="bold-font">please enter your last name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtlname, regExp)){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid last name';
			iErrCounter++;
		}
	}
	
	
	if (frm.txtemail.value==""){
		sErrMessage=sErrMessage+'<li class="bold-font">please enter your email';
		iErrCounter++;
	}else{
		regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;
		if (!regExp.test(Trim(frm.txtemail.value))){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid email address';
			iErrCounter++;
		}
	}
	
	
	if (frm.txtphone.value!=""){
		regExp	=	/\d{3}\-\d{3}\-\d{4}/;
		if (!regExp.test(frm.txtphone.value)){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid phone number';
			iErrCounter++;
		}
	}
	
	<?		if($bDRIVER_LOGGED_IN	==	false){?>
	if (frm.drpmonth.value == "" || frm.drpday.value == "" || frm.drpyear.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select your date of birth';
		iErrCounter++;
	}
	<?		}?>
	
	if (frm.txtlicenseno.value==""){
		sErrMessage=sErrMessage+'<li class="bold-font">please enter your govt. license no';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicenseno, regExp)){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid license no';
			iErrCounter++;
		}
	}
	if (frm.txtlicensestate.value==""){
		sErrMessage=sErrMessage+'<li class="bold-font">please enter your license state';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicensestate, regExp)){
			sErrMessage=sErrMessage+'<li class="bold-font">please enter valid license state';
			iErrCounter++;
		}
	}
	if (frm.txtlicenseexpire.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select license expire date';
		iErrCounter++;
	}
	
	if (frm.txtdrivetest.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please enter Name of staff person who tested you';
		iErrCounter++;
	}
	
	if (frm.txttestdate.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select Date tested here';
		iErrCounter++;
	}
	
	<?Php	if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_TM ||$_SESSION["User_Group"]==$iGROUP_TC)){?>
	if (frm.drpusergroup.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select group level';
		iErrCounter++;
	}
	<?Php	}?>
	if (frm.drpusertype.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select user type';
		iErrCounter++;
	}
	
	
	if (frm.drpdepartment.value == ""){
		sErrMessage=sErrMessage+'<li class="bold-font">please select your department';
		iErrCounter++;
	}
	
	<?		if($bDRIVER_LOGGED_IN	==	true){?>
	if(frm.chkterms.checked==false){
		sErrMessage=sErrMessage+'<li class="bold-font">you must agree with the drivers agrement';
		iErrCounter++;
	}
	<?	}?>
	if (iErrCounter >0){
		
		//fn_draw_ErrMsg(sErrMessage);
		//document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'notice_heading>Correct the following Errors</h1><br />"+sErrMessage+"<br /><br />";
		document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Correct the following Errors</h1><br />"+sErrMessage+"<br /><br />";
		centerPopup();
		loadPopup();
	}
	else{
		frm.supdatetype.value	=	sUPDATE_TYPE;
		document.frm1.action.value='edituser';
		frm.submit();
	}
	
}

function fn_ACTIVE_USER(sActive){

	if(sActive=='active')		document.frm1.action.value='active';
	if(sActive=='inactive')		document.frm1.action.value='inactive';
	if(sActive=='active-bad')	document.frm1.action.value='active-bad';
	
	document.frm1.submit();
}

function fn_PERMIT(frm, sMethod){

	if(sMethod=='renew'){
		document.getElementById('Renew_Permit').style.display='block';
	}else if(sMethod=='first'){
		document.getElementById('Renew_Permit').style.display='none';
		
	}
	
}

function fn_SET_DRVR_PERMISSION(bPERMISSION){
	if(bPERMISSION){
		document.frm1.bdriverpermission.value='1';
	}else{
		document.frm1.bdriverpermission.value='0';
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
								   				<h1 style="margin-bottom: 0px;">
												<?	if($bDRIVER_LOGGED_IN==true) 
													echo "UPDATE REGISTRATION";
													elseif($bDRIVER_PULLDOWN==true)
													echo "OK PERMIT RENEWAL";
													elseif($_SESSION["User_ID"]==$iUSER_ID)
													echo "UPDATE PROFILE";
													elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)
													echo "ACTIVATE-RENEW-EDIT DRIVER PERMIT";
												?>
												
												</h1>
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
						<form name="frm1" action="edit_user.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="supdatetype" value="" />
							<?Php 	if($bDRIVER_PULLDOWN	==	false){?><input type="hidden" name="userid" value="<?=$iUSER_ID?>"	/><?Php }?>
							<?Php 	if(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM) && ($_SESSION["User_ID"]!=$rowUSER['user_id'])){?>
							<input type="hidden" name="bdriverpermission" value="<?Php echo $bDRIVER_PERMISSION;?>"	/>
							<?Php	}?>
							
							<?Php if($bDRIVER_PULLDOWN	==	true){
									fn_DISPLAY_USERS('drpuser', $iUSER_ID, "200", "1", "--Select Driver/Co-ordinator--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF, "document.frm1.submit();");	
							}?>
							
							<input type="hidden" name="optpermit" value="Renew" />
							<table cellpadding="0" cellspacing="5" border="0" width="650" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?	if($iRECORD_COUNT>0){?>
								
								<?	if($bDRIVER_LOGGED_IN==true){?>
								
								<tr>
									<td class="label" valign="top" width="200">Describe any <br />accidents or tickets <br />in last 2 years:</td>
									<td width="450">
									<textarea name="txtrenewtext" id="txtrenewtext" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);" onKeyUp="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);"></textarea>
									&nbsp;<input readonly type="text" name="txtrenewLength" value="200" style="width:25px;">
									</td>
											
								</tr>
								<?	}else{?>								
								
										
								<?	}?>

								<tr>
									<td class="label">New Password:</td>
									<td><input type="password" name="txtpassword" value="" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Confirm New Password:</td>
									<td><input type="password" name="txtconfirmpassword" value="" style="width:150px;"  /></td>
								</tr>


									
								
								
						
								<?	if($bDRIVER_LOGGED_IN==true){?>
								<tr><td colspan="2">&nbsp;</td></tr>
								<?	}?>
								
								<tr>
									
									
										<?	if($bDRIVER_LOGGED_IN	==	true){?>
										<td></td>
										<td>
										<BR /><span class="Highlight">Next step: TM must activate your account</span><br />
										<input type="button" name="btnSUBMIT" value="UPDATE REGISTRATION" class="Button" onClick="valid_user(this.form, 'renew-driver-request');" style="width:170px;" />
										<?	}elseif($_SESSION["User_ID"]==$iUSER_ID){?>
										<td></td>
										<td>
										<input type="button" name="btnSUBMIT" value="UPDATE PROFILE" class="Button" onClick="valid_user(this.form);" style="width:110px;" />
										<?	}elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){?>
										
                    						<td colspan="2" align="center"><?	if($iUSER_ID!="782"){?> <?	}?>
                      						&nbsp;&nbsp;&nbsp; 
                      						<input type="button" name="btnSUBMIT" value="UPDATE INFO ONLY" class="Button" onClick="valid_user(this.form, 'info');" style="width:135px;" />&nbsp;&nbsp;&nbsp;
											<?	if($rowUSER['active']==0 && $rowUSER['new_user']==1){	//only for new users?><input type="button" name="btnACTIVE" value="ACTIVATE NEW DRVR" class="Button" style="width:180px;" onClick="fn_ACTIVE_USER('active');" /><?	}	?>
											<?	if($rowUSER['active']==1 && $iUSER_ID!="782"){?><?	}	?>
											<?	if($rowUSER['active']==0 && $rowUSER['new_user']==0){?><input type="button" name="btnInACTIVE" value="ACTIVATE" class="Button" style="width:180px;" onClick="fn_ACTIVE_USER('active-bad');" /><?	}	?>
										<?	}?>
										
										<input type="button" name="btnCANCEL" value="CANCEL" class="Button" onClick="location.href='list_users.php'" style="width:110px;" />
									</td>
								</tr>
								<?	}?>
								<?	if($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){?>
								<tr><td colspan="2"><hr /></td></tr>
								
								
								<?
										$sSQL	=	"SELECT tbl_user_comments.comments, DATE_FORMAT(tbl_user_comments.comments_date, '%m/%d/%Y') AS comments_date ".
										"FROM tbl_user_comments WHERE about_user_id =".$iUSER_ID." ORDER BY id DESC";
										//print($sSQL);
										$rsNOTES		=	mysql_query($sSQL) or die(mysql_error());
										$iRECORD_COUNT	=	mysql_num_rows($rsNOTES);
										if($iRECORD_COUNT>0){
								?>
											<tr>
												<td class="label" valign="top">Notes by TM:</td>
												<td valign="top">
													<table cellpadding="0" cellspacing="3" border="0">
														<?	while($rowNOTES	=	mysql_fetch_array($rsNOTES)){?>
														<tr>
																<td width="300"><? echo stripslashes($rowNOTES['comments']);?></td>
																<td width="70"><? echo $rowNOTES["comments_date"];?></td>
														</tr>
														<tr><td colspan="2" class="TextObject" style="border-bottom:1px solid #ccc;"></td></tr>
														<?	}?>
													</table>
												</td>
											</tr>
											
								<?		}mysql_free_result($rsNOTES);
									}
								?>
								
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
 <div id="popupContact">
		<div id="contactArea" style="padding-left:10px;">asdfasdf</div>
		<div style="text-align:center; width:100px; margin:0 auto;"><input type="button" name="btnclose" value="CLOSE" class="Button" id="popupContactClose" style="width:100px;" /></div>
	</div>
<div id="backgroundPopup"></div>