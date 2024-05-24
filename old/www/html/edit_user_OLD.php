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
	
	
	
	//==============VARIABLES=====
	$sACCIDENTS			=	"";		$sRENEW_DATE			=	"";
	$sFNAME				=	"";		$sLNAME					=	"";		$sEMAIL			=	"";		$sPHONE			=	"";		$sDATE_OF_BIRTH		=	"0000-00-00";		$sGOVT_LICENCE		=	"";
	$sLICENCE_STATE		=	"";		$sGOVT_LICENCE_EXPIRE	=	"";		$sSTAFF_TESTED	=	"";		$sDATE_TESTED	=	"";		$sLEADER_SPONSORED	=	0;
	$sPERMIT_EXP_YEAR	=	"0000-00-00";		$sHOME_COUNTRY			=	"";		$iUSER_GROUP	=	"";		$sUSER_TYPE		=	"";		$iHOME_DEPT			=	0;		$sPICTURE			=	"";
	$sPIC_LINK			=	"";
	$sDEPT_LEADER_NAME	=	"";		$bDEPT_CHANGED_BY_LEADER=	0;
	
	if(isset($_REQUEST["userid"]))
		$iUSER_ID		=	$_REQUEST["userid"];
	else
		$iUSER_ID		=	$_SESSION["User_ID"];
	
	if(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)  && !isset($_REQUEST["userid"])){$bDRIVER_PULLDOWN	=	true;}
	
	if($bDRIVER_PULLDOWN	==	true){
		if(isset($_POST["drpuser"]))	$iUSER_ID	=	$_POST["drpuser"];	else		$iUSER_ID		=	0;	
	}
	
	
		
	if(isset($_POST["action"])	&& $_POST["action"]=="active"){		
				
		$sSQL		=	"UPDATE tbl_user SET active = 1, new_user = 0 WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		if(!fn_SEND_EMAIL_TO_USER(14, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
			$sMessage	=	fn_Print_MSG_BOX("<li>user has been activated, but error in sending email","C_SUCCESS");
		}else{
			$sMessage	=	fn_Print_MSG_BOX("<li>user has been activated","C_SUCCESS");
		}
		
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="inactive"){
		$sSQL		=	"UPDATE tbl_user SET active = 0 WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage	=	fn_Print_MSG_BOX("<li>user de-activated","C_SUCCESS");
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="edituser"){
	
		
		$bPictureErr	=	false;
		$sUserPhoto		=	"";
		$sPhotoSQL		=	"";
		$sUSER_PWD		=	"";
		$bActive		=	"0";
		$sTestDate		=	"";
		$sGroupLevel	=	"";
		$sPERMIT_SQL	=	"";
		
			//UPLOAD IMAGE
			/*if ($_FILES['flphoto']['name']!=""){
				//check its size and mime
				$sPictureType		=	$_FILES["flphoto"]["type"];
				$sPictureSize		=	$_FILES["flphoto"]["size"];		
				
				if ((($sPictureType == "image/gif")|| ($sPictureType == "image/jpeg") || ($sPictureType == "image/pjpeg") || ($sPictureType == "image/png"))){
					//Upload Dir
					$sUploadDir				=	"user_photos/";
					$sUserPhoto				=	time() . stristr(basename($_FILES['flphoto']['name']), ".");
					
					if (!move_uploaded_file($_FILES['flphoto']['tmp_name'], $sUploadDir.$sUserPhoto)){
						$sMessage		=	fn_Print_MSG_BOX("error in uploading photo!", "C_ERROR");
						$bPictureErr	=	true;
					}
													
				}else{
					$sMessage		=	fn_Print_MSG_BOX("error in uploading photo, file type are mismatch!", "C_ERROR");
					$bPictureErr	=	true;
				}
				
				$sPhotoSQL			=	", photo = '".$sUserPhoto."'";
				
			}elseif(isset($_POST["txtphoto"]) && $_POST["txtphoto"]!=""){
				$sUserPhoto				=	trim($_POST["txtphoto"]);
				$sPhotoSQL				=	", photo_link = '".$sUserPhoto."'";
			}*/
			
			if ($bPictureErr==false){
			
				$sTestDate		=	fn_DATE_TO_MYSQL($_POST["txttestdate"]);
				$sBirthDate			=	$_POST["drpyear"]."-".$_POST["drpmonth"]."-".$_POST["drpday"];
				$sLicenseExpire	=	fn_DATE_TO_MYSQL($_POST["txtlicenseexpire"]);
				if(isset($_POST["txtrenewdate"]) && $_POST["txtrenewdate"]!="")
					$sRenwPermitDate		=	fn_DATE_TO_MYSQL($_POST["txtrenewdate"]);
				else
					$sRenwPermitDate		=	date('Y-m-d');				
				
				
				if(isset($_POST["optpermitexpireyear"]))	$sEndPermit			=	$_POST["optpermitexpireyear"]."-12-31";
							
				
				/*if($_SESSION["User_Group"]==$iGROUP_DRIVER){
					$sPERMIT_SQL	=	", reg_date = '".date('Y-m-d H:i:s')."', renew_date = '".$sRenwPermitDate."', renew_text = '".addslashes($_POST["txtrenewtext"])."', active = 0, new_user = 1, leader_sponsor = 0, sponsor_date = NULL, dept_by_leader = 0";
				}elseif(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM) && $_SESSION["User_ID"]!=$iUSER_ID){//then Admin or TM is updating his/her record so no need to renew or deactivate
					$sPERMIT_SQL	=	", reg_date = '".date('Y-m-d H:i:s')."', renew_date = '".$sRenwPermitDate."', renew_text = '".addslashes($_POST["txtrenewtext"])."', active = 0, new_user = 1, leader_sponsor = 0, sponsor_date = NULL, dept_by_leader = 0";
				}*/
				
				if($_POST["supdatetype"]=='renew-driver-request'){
				
					$sPERMIT_SQL	=	", end_permit = '".$sEndPermit."' , reg_date = '".date('Y-m-d H:i:s')."', renew_date = '".$sRenwPermitDate."', renew_text = '".addslashes($_POST["txtrenewtext"])."', active = 0, new_user = 1, leader_sponsor = 0, sponsor_date = NULL, dept_by_leader = 0";
				}
				
				
				//renewal actual process
				if($_POST["supdatetype"]=='renew-permit'){
					//if(isset($_POST["optpermitexpireyear"]))	$sEndPermit			=	$_POST["optpermitexpireyear"]."-12-31";							
					$sPERMIT_SQL		=	", end_permit = '".$sEndPermit."' , renew_date = '".$sRenwPermitDate."', renew_text = '".addslashes($_POST["txtrenewtext"])."', active = 1, new_user = 0";
				}
				
				//check inactive department
				$sSQL			=	"SELECT active FROM tbl_departments WHERE dept_id = '".$_POST["drpdepartment"]."'";
				$bDEPT_STATUS	=	mysql_result(mysql_query($sSQL), 0);
				
				if($bDEPT_STATUS==0){
					$sMessage		=	fn_Print_MSG_BOX("<li>selected department is inactive, please select some different department", "C_SUCCESS");
				}else{
				
						//if tm or admin
						if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!=""){			$sGROUP_SQL_VAL	=	$_POST["drpusergroup"];}
				
						$sSQL="UPDATE  tbl_user SET f_name='".$_POST["txtfname"]."', l_name='".$_POST["txtlname"]."', dept_id='". $_POST["drpdepartment"] ."', phone='".$_POST["txtphone"]."', birth_date = '".$sBirthDate."', ".
						"license_no = '".$_POST["txtlicenseno"]."', license_state = '".$_POST["txtlicensestate"]."', license_expire = '".$sLicenseExpire."', drive_tested='".addslashes($_POST["txtdrivetest"])."', test_date='".$sTestDate."', home_st_country='".$_POST["txtcountry"]."', user_group=".$sGROUP_SQL_VAL.", user_type = '".$_POST["drpusertype"]."' ".
						$sPhotoSQL.", permit_type =  '".$_POST["optpermit"]."'".$sPERMIT_SQL." WHERE user_id = ".$iUSER_ID;
						//print($sSQL);
						$rsMEMBER=mysql_query($sSQL) or die(mysql_error());
						
						if($_POST["supdatetype"]=='renew-permit'){						//if renew process
							if(!fn_SEND_EMAIL_TO_USER(21, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
								$sMessage		=	fn_Print_MSG_BOX("Driver Pemrit has been renewed, <br />but Error in Sending Email!","C_ERROR");
							}
						}//end renew process check
							
						if($_POST["supdatetype"]=='info'){
							$sMessage		=	fn_Print_MSG_BOX("<li>Information has been updated", "C_SUCCESS");
						}elseif($_POST["supdatetype"]=='renew-driver-request'){
							$sMessage		=	fn_Print_MSG_BOX("<li>To continue this process, please have the leader of your next school login and sponsor you", "C_SUCCESS");
						}elseif($_POST["supdatetype"]=='renew-permit'){
							$sMessage		=	fn_Print_MSG_BOX("<li>Driver Permit has been renewed, an email has been sent to his/her email address!", "C_SUCCESS");
						}
				}
				
			
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
	
	
	if($rowUSER['leader_sponsor']==0)
		$sLEADER_SPONSORED		=	"No";
	else{
		$sSQL	=	"SELECT CONCAT(f_name,' ', l_name) AS leader_name FROM tbl_user WHERE dept_id  = ".$iHOME_DEPT." AND user_group = ".$iGROUP_DEPT_LEADER;
		$sDEPT_LEADER_NAME	=	mysql_result(mysql_query($sSQL),0);
		$sLEADER_SPONSORED		=	"<input  readonly type='text' name='txtspnnsordate' id='txtspnnsordate' value='".fn_cDateMySql($rowUSER['sponsor_date'], 1)."' style='width:100px;' />";
		if($rowUSER['dept_by_leader']==1)		$bDEPT_CHANGED_BY_LEADER	=	1;
	}
	
	$sPICTURE				=	$rowUSER['photo'];
	$sPIC_LINK				=	$rowUSER['photo_link'];
		

	}/*else{
	
		$sMessage		=	fn_Print_MSG_BOX("no user found!", "C_ERROR");
		
	}*/
	mysql_free_result($rsUSER);
}


//if($_SESSION["User_ID"]==$rowUSER['user_id'] && $_SESSION["User_Group"]==$iGROUP_DRIVER){		$bDRIVER_LOGGED_IN		=	true;	}
if($_SESSION["User_Group"]==$iGROUP_DRIVER){		$bDRIVER_LOGGED_IN		=	true;	}


function fn_SEND_EMAIL_TO_USER($iMSG_ID, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){

	$sDRIVER_NAME	=	"";	$sEND_PERMIT	=	""; $sDEPT_NAME	=	""; $sDRIVER_EMAIL	=	"";
	
	
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = ".$iMSG_ID;
	$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -1)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = ".$iMSG_ID;
	$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));

	if($iMSG_ID==21){	
	//extract user level, username, group leve
		$sSQL			=	"SELECT CONCAT(u.f_name, ' ', u.l_name) AS driver_name, u.end_permit, d.dept_name, u.email FROM tbl_user u INNER JOIN tbl_departments d ON u.dept_id = d.dept_id WHERE user_id = ".$iUSER_ID;
		$rsUSER_STATE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER_STATE)>0){
			list($sDRIVER_NAME, $sEND_PERMIT, $sDEPT_NAME, $sDRIVER_EMAIL)	=	mysql_fetch_row($rsUSER_STATE);
		}mysql_free_result($rsUSER_STATE);
		
		$sMailMSG		=	str_replace('#___________#', $sDRIVER_NAME,	str_replace('#PERMIT END DATE#', fn_cDateMySql($sEND_PERMIT,1), str_replace('#Dept_Name#', $sDEPT_NAME, $sMailMSG)));
	}elseif($iMSG_ID==14){
		$sSQL			=	"SELECT u.password, u.email, g.group_name FROM tbl_user u INNER JOIN tbl_user_group g ON u.user_group = g.group_id WHERE user_id = ".$iUSER_ID;
		$rsUSER_STATE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER_STATE)>0){
			list($sUSER_PWD, $sDRIVER_EMAIL, $sUSER_GROUP)	=	mysql_fetch_row($rsUSER_STATE);
		}mysql_free_result($rsUSER_STATE);
		
		
		$sMailMSG		=	str_replace('#USER LEVEL#', $sUSER_GROUP, str_replace('#PASSWORD#', $sUSER_PWD, str_replace('#USERNAME#', $sDRIVER_EMAIL, $sMailMSG)));
	}
	//$sMessage		=	fn_Print_MSG_BOX($sMailMSG,"C_ERROR");
	
	//print($sMailMSG);
	$mail = new PHPMailer();
	$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
	$mail->From     = $sSUPPORT_EMAIL;
	$mail->FromName = $sCOMPANY_Name;
	$mail->AddAddress($sDRIVER_EMAIL);
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  $sEmailSubject;
	$mail->Body    = $sMailMSG;
	if(!$mail->Send())		return false;		else return true;
	//if(!$mail->Send()){	   $sMessage		=	fn_Print_MSG_BOX("Driver Pemrit has been renewed, <br />but Error in Sending Email!","C_ERROR");	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>
<?	if($bDRIVER_LOGGED_IN==true) 
	echo "RENEW OR EDIT DRIVER PERMIT";
	elseif($bDRIVER_PULLDOWN==true)
	echo "OK PERMIT RENEWAL";
	elseif($_SESSION["User_ID"]==$iUSER_ID)
	echo "UPDATE PROFILE";
	elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)
	echo "RENEW OR EDIT DRIVER PERMIT"; ;
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript">
<!--
function F_loadRollover(){} function F_roll(){}
//-->
</script>
<script type="text/javascript" src="../assets/rollover.js">
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">

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
		<?	if(isset($_POST["action"])	&& $_POST["action"]=="edituser"){	?>
		
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
		<?	}	?>
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
	
	if (frm.txtfname.value==""){
		sErrMessage='<li>please enter your first name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtfname, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid first name';
			iErrCounter++;
		}
	}
	
	if (frm.txtlname.value==""){
		sErrMessage=sErrMessage+'<li>please enter your last name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtlname, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid last name';
			iErrCounter++;
		}
	}
	
	
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
	
	
	if (frm.txtphone.value!=""){
		regExp	=	/\d{3}\-\d{3}\-\d{4}/;
		if (!regExp.test(frm.txtphone.value)){
			sErrMessage=sErrMessage+'<li>please enter valid phone number';
			iErrCounter++;
		}
	}
	
	/*if (frm.txtbirthdate.value == ""){
		sErrMessage=sErrMessage+'<li>please select your date of birth';
		iErrCounter++;
	}*/
	
	if (frm.drpmonth.value == "" || frm.drpday.value == "" || frm.drpyear.value == ""){
		sErrMessage=sErrMessage+'<li>please select your date of birth';
		iErrCounter++;
	}
	
	if (frm.txtlicenseno.value==""){
		sErrMessage=sErrMessage+'<li>please enter your govt. license no';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicenseno, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid license no';
			iErrCounter++;
		}
	}
	if (frm.txtlicensestate.value==""){
		sErrMessage=sErrMessage+'<li>please enter your license state';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9 \-]/i;
		if (!validate_field(frm.txtlicensestate, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid license state';
			iErrCounter++;
		}
	}
	if (frm.txtlicenseexpire.value == ""){
		sErrMessage=sErrMessage+'<li>please select license expire date';
		iErrCounter++;
	}
	
	if (frm.txtdrivetest.value == ""){
		sErrMessage=sErrMessage+'<li>please enter Name of staff person who tested you';
		iErrCounter++;
	}
	
	if (frm.txttestdate.value == ""){
		sErrMessage=sErrMessage+'<li>please select Date tested here';
		iErrCounter++;
	}
	
	/*if (frm.txtendpermit.value == ""){
		sErrMessage=sErrMessage+'<li>please select end UN permit';
		iErrCounter++;
	}*/
	
	<?Php	if(isset($_SESSION["User_Group"]) && ($_SESSION["User_Group"]==$iGROUP_TM ||$_SESSION["User_Group"]==$iGROUP_TC)){?>
	if (frm.drpusergroup.value == ""){
		sErrMessage=sErrMessage+'<li>please select group level';
		iErrCounter++;
	}
	<?Php	}?>
	if (frm.drpusertype.value == ""){
		sErrMessage=sErrMessage+'<li>please select user type';
		iErrCounter++;
	}
	
	if (frm.drpdepartment.value == ""){
		sErrMessage=sErrMessage+'<li>please select your department';
		iErrCounter++;
	}
	<?		if($bDRIVER_LOGGED_IN	==	true){?>
	if(frm.chkterms.checked==false){
		sErrMessage=sErrMessage+'<li>you must agree with the drivers agrement';
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

function fn_ACTIVE_USER(bActive){

	if(bActive)
		document.frm1.action.value='active';
	else
		document.frm1.action.value='inactive';
		
	document.frm1.submit();
}

function fn_PERMIT(frm, sMethod){

	if(sMethod=='renew'){
		document.getElementById('Renew_Permit').style.display='block';
	}else if(sMethod=='first'){
		document.getElementById('Renew_Permit').style.display='none';
		
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
		<? include('inc_side_nav.php');	?>
		
		<!-- actual page	-->
        <td>
        	<table border="0" cellspacing="0" cellpadding="0" width="700">
            	<tr valign="top" align="left">
                	<td width="15" height="16"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
                	<td width="683"><img src="../assets/images/autogen/clearpixel.gif" width="683" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
               	</tr>
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="3" width="685">
                 		<table border="0" cellspacing="0" cellpadding="0" width="685" style="background-image: url('../assets/images/banner.gif'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								
								 		<tr>
								  			<td><img src="../assets/images/autogen/clearpixel.gif" width="18" height="8" border="0" alt=""></td>
								  			<td width="651" class="TextObject">
								   				<h1 style="margin-bottom: 0px;">
												<?	if($bDRIVER_LOGGED_IN==true) 
													echo "RENEW OR EDIT DRIVER PERMIT";
													elseif($bDRIVER_PULLDOWN==true)
													echo "OK PERMIT RENEWAL";
													elseif($_SESSION["User_ID"]==$iUSER_ID)
													echo "UPDATE PROFILE";
													elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM)
												 	echo "RENEW OR EDIT DRIVER PERMIT"; ;
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
                	<td width="683" class="TextObject" align="center">
						<form name="frm1" action="edit_user.php" enctype="multipart/form-data" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="supdatetype" value="" />
							<?Php if($bDRIVER_PULLDOWN	==	false){?><input type="hidden" name="userid" value="<?=$iUSER_ID?>"	/><?Php }?>
							
							<?Php if($bDRIVER_PULLDOWN	==	true){
									fn_DISPLAY_USERS('drpuser', $iUSER_ID, "200", "1", "--Select Driver/Co-ordinator--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF, "document.frm1.submit();");	
							}?>
							
							<input type="hidden" name="optpermit" value="Renew" />
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?	if($iRECORD_COUNT>0){?>
								
								<?	if($bDRIVER_LOGGED_IN==true){?>
								
								<tr>
									<td class="label" valign="top" width="150">Describe any <br />accidents or tickets <br />in last 2 years:</td>
									<td width="315">
									<textarea name="txtrenewtext" id="txtrenewtext" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);" onKeyUp="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);"></textarea>
									&nbsp;<input readonly type="text" name="txtrenewLength" value="200" style="width:25px;">
									</td>
											
								</tr>
								<?	}else{?>								
								
								<tr>
									<td class="label" width="150">Renew Date:</td>
									<td width="315"><input readonly="" type="text" name="txtrenewdate" id="txtrenewdate" value="<?	echo $sRENEW_DATE;?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Describe any <br />accidents or tickets <br />in last 2 years:</td>
									<td>
									<textarea name="txtrenewtext" id="txtrenewtext" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);" onKeyUp="fn_char_Counter(this.form.txtrenewtext,this.form.txtrenewLength,200);"><?	echo $sACCIDENTS;?></textarea>
									&nbsp;<input readonly type="text" name="txtrenewLength" value="200" style="width:25px;">
									</td>
								</tr>
										
								<?	}?>
								<tr>
									<td width="150" class="label">First Name:</td>
									<td width="300"><input type="text" name="txtfname" value="<?Php echo $sFNAME;?>" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Last Name:</td>
									<td><input type="text" name="txtlname" value="<?Php echo $sLNAME;?>" maxlength="20" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Home Country:</td>
									<td><input type="text" name="txtcountry" value="<?Php echo $sHOME_COUNTRY;?>" maxlength="30" style="width:100px;"  /></td>
								</tr>
								<tr>
									<td class="label">Email:</td>
									<td><input readonly="" type="text" name="txtemail" value="<?Php echo $sEMAIL;?>" maxlength="25" style="width:250px;"  /></td>
								</tr>
								<tr>
									<td class="label">Phone:</td>
									<td><input type="text" name="txtphone" value="<?Php echo $sPHONE;?>" maxlength="12" style="width:100px;" />&nbsp;xxx-xxx-xxxx</td>
								</tr>
								<tr>
									<td class="label">Date of Birth:</td>
									<td>
									<?
											echo "<select name='drpmonth' size='1' style='width:100px;'>";
											echo "<option value=''>Month</option>";
											for($iCounter=1;$iCounter<=12;$iCounter++){
												if($iCounter<10) $iMONTH	=	"0".$iCounter; else $iMONTH	=	$iCounter;
												if(substr($sDATE_OF_BIRTH,5,2)==$iMONTH) $sSELECTED	=	"selected"; else $sSELECTED	=	"";
												echo "<option value='".$iMONTH."' ".$sSELECTED.">".date("F", mktime(0, 0, 0, $iCounter, 10))."</option>";
											}
											echo "</select>";
											echo "&nbsp;&nbsp;";
											echo "<select name='drpday' size='1' style='width:70px;'>";
											echo "<option value=''>Day</option>";
											for($iCounter=1;$iCounter<=31;$iCounter++){
												if($iCounter<10) $iMONTH	=	"0".$iCounter; else $iMONTH	=	$iCounter;
												if(substr($sDATE_OF_BIRTH,8,2)==$iMONTH) $sSELECTED	=	"selected"; else $sSELECTED	=	"";
												echo "<option value='".$iMONTH."' ".$sSELECTED.">".$iMONTH."</option>";
											}
											echo "</select>";
											echo "&nbsp;&nbsp;";
											echo "<select name='drpyear' size='1' style='width:70px;'>";
											echo "<option value=''>Year</option>";
											for($iCounter=1900;$iCounter<=2025;$iCounter++){
												if(substr($sDATE_OF_BIRTH,0,4)==$iCounter) $sSELECTED	=	"selected"; else $sSELECTED	=	"";
												echo "<option value='".$iCounter."' ".$sSELECTED.">".$iCounter."</option>";
											}
											echo "</select>";
										?>
									</td>
								</tr>
								<tr>
									<td class="label">Govt. License No:</td>
									<td><input type="text" name="txtlicenseno" id="txtlicenseno" value="<?Php echo $sGOVT_LICENCE;?>" maxlength="20" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">License State:</td>
									<td><input type="text" name="txtlicensestate" id="txtlicensestate" value="<?Php echo $sLICENCE_STATE;?>" maxlength="15" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">Govt. License Expires:</td>
									<td><input readonly="" type="text" name="txtlicenseexpire" id="txtlicenseexpire" value="<? if($bDRIVER_LOGGED_IN==false) echo $sGOVT_LICENCE_EXPIRE;?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Name of staff person who tested you:</td>
									<td><input type="text" name="txtdrivetest" id="txtdrivetest" value="<?Php echo $sSTAFF_TESTED;?>" maxlength="30" style="width:250px;" /></td>
								</tr>
								<tr>
									<td class="label">Date tested here:</td>
									<td><input  readonly="" type="text" name="txttestdate" id="txttestdate" value="<?Php echo $sDATE_TESTED;?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<?	if($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){
										if($sLEADER_SPONSORED!="No"){
								?>
								<tr><td class="label">Date Sponsored:</td><td><?Php echo $sLEADER_SPONSORED;?></td></tr>
								<tr><td class="label">Sponsored By:</td><td><input readonly="" type="text" name="txtleadername" value="<?Php echo $sDEPT_LEADER_NAME;?>" style="width:250px;" /></td></tr>
								<?		}else{	?>
								<tr><td class="label">Sponsored:</td><td><span class="bold-font Highlight"><?Php echo $sLEADER_SPONSORED;?></span></td></tr>
								
								<?	
										}
									}
								?>
								<tr>
									<td class="label" valign="top"><!--End UN permit:--></td>
									<td>
										<!--<input readonly="" type="text" name="txtendpermit" id="txtendpermit" value="<? //if($bDRIVER_LOGGED_IN==false)	echo fn_cDateMySql($rowUSER['end_permit'], 1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />-->
										<!--<br /><div style="clear:both;"></div><span class="Highlight">Enter Dec 31st of current year UNLESS today is after October1st...and if so enter Dec 31st of next year</span>-->
										
										<div style="clear:both;"></div><span class="bold-font Highlight">
										<?Php if(($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM) && ($_SESSION["User_ID"]!=$rowUSER['user_id'])){?>
											The Permit Expires Dec 31st of <br /><input type="radio" name="optpermitexpireyear" value="<?Php echo date('Y');?>" <?Php if(date('Y',strtotime($sPERMIT_EXP_YEAR))==date('Y')) echo "checked";?> />This year&nbsp;<?Php //echo date('Y');?>&nbsp;&nbsp;&nbsp;
											<input type="radio" name="optpermitexpireyear" value="<?Php echo date('Y', strtotime(date("Y", strtotime(date('Y'))) . " +1 year"));?>" <?Php if(date('Y',strtotime($sPERMIT_EXP_YEAR))==date('Y', strtotime(date("Y", strtotime(date('Y'))) . " +1 year"))) echo "checked";?> />&nbsp;Next year&nbsp;<?Php /*echo date('Y', strtotime(date("Y", strtotime(date('Y'))) . " +1 year"));*/} else{ echo "The Permit Expires Dec 31st of this year";}?>
											</span>
										</td>
								</tr>
								
								<?	if($_SESSION["User_Group"]==$iGROUP_TM ||$_SESSION["User_Group"]==$iGROUP_TC){?>
								<tr>
									<td class="label" valign="top">Group:</td>
									<td><?	fn_USER_GROUP('drpusergroup', $iUSER_GROUP, "200", "1", "Select User Group");?></td>
								</tr>
								<?	}//else{?>
								<!--<tr>
									<td class="label" valign="top"></td>
									<td><span class="Highlight" style="font-weight:bold;">YOUR GROUP ASSIGNEMENT CAN BE CHANGED BY MANAGER OR ADMINISTRATOR</span></td>
								</tr>-->
								<?	//}?>
								<tr>
									<td class="label">Type:</td>
									<td><?	fn_USER_TYPE('drpusertype', $sUSER_TYPE, "200", "1", "Select User Type");?></td>
								</tr>
								<tr>
									<td class="label">Home Dept:</td>
									<td><?	
										if($bDRIVER_LOGGED_IN==true)
										fn_DEPARTMENT('drpdepartment', 0, "200", "1", "Select Department");
										else{
										
										echo "<div style='float:left;'>";
										fn_DEPARTMENT('drpdepartment', $iHOME_DEPT, "200", "1", "Select Department");
										echo "</div>";
										}
										?>
									</td>
								</tr>
								<?	if($_SESSION["User_Group"]==$iGROUP_TM ||$_SESSION["User_Group"]==$iGROUP_TC){?>
								<tr>
									<td class="label">Dept Changed by Leader?:</td>
									<td><input type="text" readonly="" value="<?	if($bDEPT_CHANGED_BY_LEADER==1) echo "Yes"; else echo "No"; ?>" style="width:50px;" /></td>
								</tr>
								<?	}?>	
								<!--<tr>
									<td class="label" valign="top">Picture:</td>
									<td>
										<input type="file" name="flphoto" style="width:250px;"  />
										<br />
										<span class="Highlight">or link to photo </span><br /><input type="text" name="txtphoto" value="<?=$rowUSER['photo_link']?>" maxlength="255" style="width:250px;" />
										<br />
										<span class="Highlight">(.jpg, .gif, .png)
										<br />
										please type complete URL like(http://www.domainname.com/filename.jpg)</span>
									</td>
								</tr>-->
								<?				
									//if($sPICTURE!=""){
								?>
								<!--<tr><td></td><td><a href="user_photos/<?=$rowUSER['photo']?>" target="_blank"><img src="user_photos/<?=$rowUSER['photo']?>" width="100" height="100" /></a></td></tr>-->
								<?	//}elseif($sPIC_LINK!=""){?>
								<!--<tr><td></td><td><a href="<?=$rowUSER['photo_link']?>" target="_blank"><img src="<?=$rowUSER['photo_link']?>" width="100" height="100" /></a></td></tr>-->
								<?	//}?>
								
								<?	if($bDRIVER_LOGGED_IN==true){?>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td class="label">&nbsp;</td>
									<td class="label"><input type="checkbox" name="chkterms" value="1" />I have read and will comply with the Drivers Agrement</td>
								</tr>
								<?	}?>
								
								
								<tr>
									
									
										<?	if($bDRIVER_LOGGED_IN	==	true){?>
										<td></td>
										<td>
										<BR /><span class="Highlight">Next step: TM must activate your account</span>
										<input type="button" name="btnSUBMIT" value="REQUEST PERMIT RENEWAL" class="Button" onClick="valid_user(this.form, 'renew-driver-request');" style="width:170px;" />
										<?	}elseif($_SESSION["User_ID"]==$iUSER_ID){?>
										<td></td>
										<td>
										<input type="button" name="btnSUBMIT" value="UPDATE PROFILE" class="Button" onClick="valid_user(this.form);" style="width:110px;" />
										<?	}elseif($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){?>
										
                    					<td colspan="2" align="center"> <input type="button" name="btnSUBMIT2" value="RENEW THIS DRIVER" class="Button" onClick="valid_user(this.form, 'renew-permit');" style="width:130px;" />
                      					&nbsp;&nbsp;&nbsp; 
                      					<input type="button" name="btnSUBMIT" value="UPDATE INFO ONLY" class="Button" onClick="valid_user(this.form, 'info');" style="width:120px;" />&nbsp;&nbsp;&nbsp;
										<?	if($rowUSER['active']=="0"){?><input type="button" name="btnACTIVE" value="ACTIVATE" class="Button" style="width:160px;" onClick="fn_ACTIVE_USER(true);" /><?	}	?>
										<?	if($rowUSER['active']=="1"){?><input type="button" name="btnInACTIVE" value="DE-ACTIVATE THIS USER" class="Button" style="width:150px;" onClick="fn_ACTIVE_USER(false);" /><?	}	?>
										<?	}?>
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