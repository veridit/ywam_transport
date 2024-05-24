<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_redirect.php');
	require("class.phpmailer.php");

	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$sAction		=	"";
	$iVehicleID		=	"";
	$sBILLING_DEPT	=	"";
	$iASSND_DRIVER	=	"";
	$bONLY_AVAILABLE=	true;
	$bONLY_2_3_VEHICLES	=	true;
	$sLOGOUT_MESSAGE	=	"";
	$sNOTICE			=	"";
	

	if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC){
		$bONLY_AVAILABLE=	false;
	}
	
	if(isset($_POST['drpdriver']) && $_POST['drpdriver']!="")		$iASSND_DRIVER	=		$_POST['drpdriver'];	else	$iASSND_DRIVER	=		$_SESSION["User_ID"];
	
	//searching criterias
	$iTIME_1		=	"00:00";		$iTIME_2		=	"00:00";
	//$iAMPM_1		=	0;		$iAMPM_2		=	0;
	$sTimePickerDate1=date('m/d/Y',strtotime(date("Y-m-d")." +1 day")); //date('m/d/Y');
	$sTimePickerDate2=	date('m/d/Y',strtotime(date("Y-m-d")." +1 day"));
	if(isset($_POST["txtdeptdatetime"]) && $_POST["txtdeptdatetime"]!="")	$sTimePickerDate1 = $_POST["txtdeptdatetime"];
	if(isset($_POST["txtreturndatetime"]) && $_POST["txtreturndatetime"]!="")	$sTimePickerDate2 = $_POST["txtreturndatetime"];
	
	
	if(isset($_POST["action"])	&& $_POST["action"]!="")				$sAction		=	$_POST["action"];
	if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			$iVehicleID		=	$_POST["drpvehicle"];
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				$sBILLING_DEPT	=	$_POST["drpdept"];
	
	
	
			$arrTIME[0][0]	=	"00:00";	$arrTIME[0][1]	=	"12:00 am";		$arrTIME[1][0]	=	"01:00";	$arrTIME[1][1]	=	"01:00 am";
			$arrTIME[2][0]	=	"02:00";	$arrTIME[2][1]	=	"02:00 am";		$arrTIME[3][0]	=	"03:00";	$arrTIME[3][1]	=	"03:00 am";
			$arrTIME[4][0]	=	"04:00";	$arrTIME[4][1]	=	"04:00 am";		$arrTIME[5][0]	=	"05:00";	$arrTIME[5][1]	=	"05:00 am";
			$arrTIME[6][0]	=	"06:00";	$arrTIME[6][1]	=	"06:00 am";		$arrTIME[7][0]	=	"07:00";	$arrTIME[7][1]	=	"07:00 am";
			$arrTIME[8][0]	=	"08:00";	$arrTIME[8][1]	=	"08:00 am";		$arrTIME[9][0]	=	"09:00";	$arrTIME[9][1]	=	"09:00 am";
			$arrTIME[10][0]=	"10:00";	$arrTIME[10][1]=	"10:00 am";		$arrTIME[11][0]=	"11:00";	$arrTIME[11][1]=	"11:00 am";
			$arrTIME[12][0]=	"12:00";	$arrTIME[12][1]=	"12:00 pm";		$arrTIME[13][0]=	"13:00";	$arrTIME[13][1]=	"01:00 pm";
			$arrTIME[14][0]=	"14:00";	$arrTIME[14][1]=	"02:00 pm";		$arrTIME[15][0]=	"15:00";	$arrTIME[15][1]=	"03:00 pm";
			$arrTIME[16][0]=	"16:00";	$arrTIME[16][1]=	"04:00 pm";		$arrTIME[17][0]=	"17:00";	$arrTIME[17][1]=	"05:00 pm";
			$arrTIME[18][0]=	"18:00";	$arrTIME[18][1]=	"06:00 pm";		$arrTIME[19][0]=	"19:00";	$arrTIME[19][1]=	"07:00 pm";
			$arrTIME[20][0]=	"20:00";	$arrTIME[20][1]=	"08:00 pm";		$arrTIME[21][0]=	"21:00";	$arrTIME[21][1]=	"09:00 pm";
			$arrTIME[22][0]=	"22:00";	$arrTIME[22][1]=	"10:00 pm";		$arrTIME[23][0]=	"23:00";	$arrTIME[23][1]=	"11:00 pm";
	
	
	
	if($sAction=="viewreservations"){
			$iTimeCol_WIDTH	=	"80";
			$iDayCol_WIDTH	=	"83";
			
			$sReserved_COLOR	=	"#FF6633";
			$sFree_COLOR		=	"#FFEBD7";
			//starting date
			
			//if(isset($_POST["scurrentdate"]) && $_POST["scurrentdate"]!="")	$sToday_Date = date('Y-m-d',strtotime(date("Y-m-d", strtotime($_POST["scurrentdate"])) . " +1 day"));	else	$sToday_Date = date("Y-m-d");
			
			if(isset($_POST["scurrentdate"]) && $_POST["scurrentdate"]!="")	$sToday_Date = $_POST["scurrentdate"];	else	$sToday_Date = date("Y-m-d");
			//print('CURRETNN=='.$sToday_Date);
			if(strtotime($sToday_Date)<strtotime(date('Y-m-d'))) 		$sToday_Date		=	date("Y-m-d");
			
	}
	
	if($sAction=="addreservation"){
	
		
		
		
		
		
			$bOVERNIGHT	=	0;		$bCHILDSEAT		=	0;
					
			$sDEPART_DATETIME		=	substr($_POST["txtdeptdatetime"],6, 4);
			$sDEPART_DATETIME		.=	"-".substr($_POST["txtdeptdatetime"],0, 2);
			$sDEPART_DATETIME		.=	"-".substr($_POST["txtdeptdatetime"],3, 2);
			$sDEPART_DATETIME		.=	" ".$_POST["drptime1"].":00";
			
			$sRETURN_DATETIME		=	substr($_POST["txtreturndatetime"],6, 4);
			$sRETURN_DATETIME		.=	"-".substr($_POST["txtreturndatetime"],0, 2);
			$sRETURN_DATETIME		.=	"-".substr($_POST["txtreturndatetime"],3, 2);
			$sRETURN_DATETIME		.=	" ".$_POST["drptime2"].":00";
			//print("CURRET==".date('Y-m-d H:i:s'));
				//cannot make reservation in past
				
				
			//check if driver, then check for 3 vehicles
			if($iGROUP_DRIVER==$_SESSION["User_Group"] || $iGROUP_COORDINATOR_STAFF==$_SESSION["User_Group"]){
				$sSQL	=	"SELECT COUNT(res_id) total_reservation FROM tbl_reservations WHERE tbl_reservations.user_id = ".$_SESSION["User_ID"]." AND DATE(tbl_reservations.planned_depart_day_time) = DATE('".$sDEPART_DATETIME."')";
				//print($sSQL);
				$rsCOUNT_RES	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsCOUNT_RES)>0){
					$rowCOUNT_RES	=	mysql_fetch_array($rsCOUNT_RES);
					if($rowCOUNT_RES['total_reservation']	==	3){
						$bONLY_2_3_VEHICLES	=	false;
						$sMessage		=	fn_Print_MSG_BOX("you cannot reserve more than 3 vehicles in one day", "C_ERROR");
					}else{
					
						if(fn_GET_FIELD("tbl_vehicles", $iVehicleID, "vehicle_id", "model")==2){		//if selected vehicle is maxivan then
						
							$sSQL	=	"SELECT COUNT(res_id) total_max_van ".
							"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
							"WHERE tbl_reservations.user_id = ".$_SESSION["User_ID"]." AND DATE(tbl_reservations.planned_depart_day_time) = DATE('".$sDEPART_DATETIME."') AND tbl_vehicles.model = 2";
							//print($sSQL);
							$rsCOUNT_MAX_VAN	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsCOUNT_MAX_VAN)>0){
								$rowCOUNT_MAX_VAN	=	mysql_fetch_array($rsCOUNT_MAX_VAN);
								if($rowCOUNT_MAX_VAN['total_max_van']==2){
									$bONLY_2_3_VEHICLES	=	false;
									$sMessage		=	fn_Print_MSG_BOX("you cannot reserve more than 2 large vans in one day", "C_ERROR");
								}
							}mysql_free_result($rsCOUNT_MAX_VAN);
						}	
					}
				}mysql_free_result($rsCOUNT_RES);
			}
				
			
				if(strtotime($sDEPART_DATETIME) < strtotime(date('Y-m-d H:i:s'))){
					$sMessage		=	fn_Print_MSG_BOX("vehicle cannot be reserved in past date or time", "C_ERROR");
				}else{
				
					if($bONLY_2_3_VEHICLES	==	true){
						//check already reserved vehicle for this time
						fn_CHECK_RESERVATION($iVehicleID, substr($sDEPART_DATETIME,0,10), substr($sRETURN_DATETIME,0,10), session_id());
					
						$iHours	=	0;
						//while(strtotime($sDEPART_DATETIME." + ".$iHours." hour") < strtotime($sRETURN_DATETIME)){
						while(strtotime($sDEPART_DATETIME." + ".$iHours." hour") <= strtotime($sRETURN_DATETIME)){
							
							//print("<BR />TIME======".date("Y-m-d",strtotime(date("Y-m-d H:i:s",strtotime($sDEPART_DATETIME))." + ".$iHours." hour")));
							$sINC_DEPART_DATE	=	date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($sDEPART_DATETIME))." + ".$iHours." hour"));
							//print("<BR />INC DATE TIME=".$sINC_DEPART_DATE);
							
							//if(fn_CHECK_RESERVATION_TIME(substr($sDEPART_DATETIME,0,10), substr($sDEPART_DATETIME, 11, 2), session_id())){
							if(fn_CHECK_RESERVATION_TIME(substr($sINC_DEPART_DATE,0,10), substr($sINC_DEPART_DATE, 11, 2), session_id())){
								$sMessage		=	fn_Print_MSG_BOX("vehicle is already reserved in your specified time period", "C_ERROR");
								break;
							}
							$iHours++;
						}	
				
						if($sMessage==""){
							if(isset($_POST["chkovernight"]) && $_POST["chkovernight"]!="")				$bOVERNIGHT	=	"1";	else	$bOVERNIGHT	=	"0";
							if(isset($_POST["chkseat"]) && $_POST["chkseat"]!="")						$bCHILDSEAT	=	"1";	else	$bCHILDSEAT	=	"0";
								
							$sSQL="INSERT INTO tbl_reservations(vehicle_id, user_id, planned_passngr_no, planned_depart_day_time, ".
							"planned_return_day_time, overnight, childseat, destination, billing_dept, assigned_driver) ".
							"VALUES(".$_POST["drpvehicle"].", ".$_SESSION["User_ID"].", '".$_POST["txtpassenger"]."', ".
							"'".$sDEPART_DATETIME."', '".$sRETURN_DATETIME."', ".$bOVERNIGHT.", ".$bCHILDSEAT.", '".addslashes($_POST["txtdestination"])."', ".$_POST["drpdept"].", ".$_POST["drpdriver"].")";
							$rsRESERVATION=mysql_query($sSQL) or die(mysql_error());
							$iNEW_RESERVATION_NO = mysql_insert_id();
							
							
							//after check reservations delete temp
							fn_DELETE_TEMP(session_id());
							
							$sDriver_Email	=	fn_GET_FIELD("tbl_user", $_SESSION["User_ID"], "user_id", "email");
							$sDriver_Dept	=	fn_GET_FIELD("tbl_user", $_SESSION["User_ID"], "user_id", "dept_id");
							$sLeader_Email	=	fn_GET_FIELD("tbl_departments", $sDriver_Dept, "dept_id", "leader_email");
							
							if($sDriver_Dept!=$_POST["drpdept"]){
								//send email to charged department
								$sCharged_Dept_Leader_Email	=	fn_GET_FIELD("tbl_departments", $_POST["drpdept"], "dept_id", "leader_email");
								$sEmailSubject	=	"Billing Notice for vehicle reservation";
							
								$sMailMSG		=	"You will be billed for vehicle reservation No. ___".$iNEW_RESERVATION_NO."__ charged to you by another department. Call Transportation office for details.";
								$mail = new PHPMailer();
								$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
								$mail->From     = $sSUPPORT_EMAIL;
								$mail->FromName = $sCOMPANY_Name;
								$mail->AddAddress($sCharged_Dept_Leader_Email);
								$mail->IsHTML(true);                               // send as HTML
								$mail->Subject  =  $sEmailSubject;
								$mail->Body    = $sMailMSG;
								if(!$mail->Send()){	$sMessage		=	fn_Print_MSG_BOX("Error in Sending Email to charged department leader, $mail->ErrorInfo","C_ERROR");}
							}
							
							
							$sEmailSubject	=	"Trip Slip From $sCOMPANY_Name";
							
							$sMailMSG		=	fn_PRINT_TRIP_SLIP($iNEW_RESERVATION_NO);
							
							//print($sMailMSG);
							$sMailMSG		=	html_entity_decode($sMailMSG, ENT_NOQUOTES,'ISO-8859-1');
							$mail = new PHPMailer();
							$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
							$mail->From     = $sSUPPORT_EMAIL;
							$mail->FromName = $sCOMPANY_Name;
							$mail->AddAddress($sDriver_Email);
							$mail->AddCC($sLeader_Email);
							//$mail->AddCC('transportation@uofnkona.edu');
							$mail->IsHTML(true);                               // send as HTML
							$mail->Subject  =  $sEmailSubject;
							$mail->Body    = $sMailMSG;
							
							if($iGROUP_DRIVER==$_SESSION["User_Group"] || $iGROUP_COORDINATOR_STAFF==$_SESSION["User_Group"]){$sLOGOUT_MESSAGE	=	"<br /><br />--------PLEASE USE THE LOGOUT BUTTON WHEN YOU FINISH HERE--A SECURITY PRECAUTION--------";}
							
							if(!$mail->Send())
							{
								$sMessage		.=	fn_Print_MSG_BOX("Your reservation has been recorded, <br />but Error in Sending Email, $mail->ErrorInfo".$sLOGOUT_MESSAGE,"C_ERROR");
							}else{
								
								$sMessage		.=	fn_Print_MSG_BOX("Your reservation has been recorded.<br />You will receive a trip slip to confirm it.<br />The Transportation Manager will have a copy of your trip slip and the keys hanging on the Departure Board for you to pick up during office hours.".$sLOGOUT_MESSAGE, "C_SUCCESS");
							}
							
						}
					}//end if $bONLY_2_3_VEHICLES	=	false;
				}
		
	}		
	//}
	
	if(($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF) && $_SESSION["load_counter"]=="1"){
		$sSQL	=	"SELECT notice_title, notice FROM tbl_special_notice WHERE notice_id = 1";
		$rsNOTICE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsNOTICE)>0){
			$rowNOTICE	=	mysql_fetch_array($rsNOTICE);
			$sNOTICE_TITLE	=	stripslashes($rowNOTICE['notice_title']);
			$sNOTICE	=	stripslashes($rowNOTICE['notice']);
		}mysql_free_result($rsNOTICE);
		
	}
	
?>
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Reservations</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript">
<!--
function F_loadRollover(){} function F_roll(){}
//-->
</script>
<script type="text/javascript" src="../assets/rollover.js"></script>
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
				$('.date-pick').datePicker({startDate: '<?=date('m/d/Y')?>', autoFocusNextInput: true});
            });
		</script>
<style type="text/css">
#backgroundPopup{
display:none;
position:fixed;
/*_position:absolute;*/ /* hack for internet explorer 6*/
height:100%;
width:100%;
top:0;
left:0;
background:#000000;
border:1px solid #cecece;
z-index:1;
}
#popupContact{
/*-------------------*/
background:#FFFFFF;
border:1px solid #CA0000;
position:fixed;
_position:absolute; /* hack for internet explorer 6*/

float: left;
font-weight: normal;
width: 600px;
display:none;
z-index:2;
}
.notice-heading{
	font-family: Arial,   Helvetica,   Geneva,   Sans-serif;
	font-size : 14pt;
	color : #CA0000;
	border-bottom:1px solid #FF3000;
}
</style>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<?	//print("SESSION=".$_SESSION["load_counter"])	;
		if(isset($_SESSION["load_counter"]) && $_SESSION["load_counter"]=="1" && $sNOTICE!=""){
		$_SESSION["load_counter"]="2";
?>
<script type="text/javascript" src="./js/popup.js"></script>
<script language="JavaScript">
		$(document).ready(function () {
			document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
			centerPopup();
			loadPopup();
			document.getElementById('contactArea').innerHTML	=	<?php echo json_encode("<h1 class='notice-heading'notice_heading>".$sNOTICE_TITLE."</h1><br /><br />".$sNOTICE); ?>;
			
		});
</script>
<?	}?>
<script language="JavaScript">
/*var arrVEHICLE	=	new Array();
var iArrCounter	=	0;*/
<?
	/*$sSQL	=	"SELECT vehicle_id, restricted FROM tbl_vehicles ORDER BY vehicle_id";
	$rsREST	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsREST)>0){
		while($rowREST	=	mysql_fetch_array($rsREST)){*/
?>
			/*arrVEHICLE[iArrCounter]			=	new Array(2);
			//arrVEHICLE[iArrCounter]			=		<? echo $rowREST['vehicle_id'];?>;
			arrVEHICLE[iArrCounter][0]			=		<? echo $rowREST['vehicle_id'];?>;
			arrVEHICLE[iArrCounter][1]			=		<? echo $rowREST['restricted'];?>;
			iArrCounter++;*/
<?	/*	}
	}mysql_free_result($rsREST);*/

?>

/*function fn_VALID_RESTRICT(iVehicleID){
	for(iArrCounter=0;iArrCounter<arrVEHICLE.length;iArrCounter++){
		//alert('aaa='+arrVEHICLE[iArrCounter][0]);
		if(arrVEHICLE[iArrCounter][0]==iVehicleID){
			if(arrVEHICLE[iArrCounter][1]==1){
				//alert(arrVEHICLE[iArrCounter][1]);
				return false;
			}
		}
	}
	return true;
}*/
function fn_VIEW_RESERVATIONS(sDate){
	if(document.frm1.drpvehicle.value==''){
		fn_draw_ErrMsg('<li>please select vehicle to view reservations');
	}else{
		if(typeof sDate== 'undefined'){
			if(document.frm1.txtdeptdatetime.value!=""){
				var sStartDate	=	document.frm1.txtdeptdatetime.value;
				sDate	=	sStartDate.substr(6,4)+'-'+sStartDate.substr(0,2)+'-'+sStartDate.substr(3,2);
				//alert(sDate);
			}else{
				var time	=	new Date();
				sDate	=		time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate();
			}
		}
	
		document.frm1.scurrentdate.value = sDate;
		document.frm1.action.value='viewreservations';
		document.frm1.submit();
		
	}
}
function valid_reservation(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpdriver.value==""){
		sErrMessage='<li>please select driver for this reservation';
		iErrCounter++;
	}
	
	if (frm.drpvehicle.value==""){
		sErrMessage=sErrMessage+'<li>please select vehicle';
		iErrCounter++;
	}
	
	
	if (frm.txtpassenger.value == ""){
		sErrMessage=sErrMessage+'<li>please enter passengers';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtpassenger, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid passengers';
			iErrCounter++;
		}
	}
	if (frm.txtdeptdatetime.value==""){
		sErrMessage=sErrMessage+'<li>please select departure date and time';
		iErrCounter++;
	}
		
	if (frm.txtreturndatetime.value==""){
		sErrMessage=sErrMessage+'<li>please select return date and time';
		iErrCounter++;
	}
	if (frm.txtdeptdatetime.value!="" && frm.txtreturndatetime.value!=""){
		if(CompareDates(frm.txtdeptdatetime.value, frm.drptime1.value, frm.txtreturndatetime.value, frm.drptime2.value)==false){
			sErrMessage=sErrMessage+'<li>return date and time must be greater than departure date and time';
			iErrCounter++;
		}
	}
	
	if (frm.txtdestination.value==""){
		sErrMessage=sErrMessage+'<li>please enter destination';
		iErrCounter++;
	}

	if (frm.drpdept.value==""){
		sErrMessage=sErrMessage+'<li>please select charge department';
		iErrCounter++;
	}
		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		var sDEPART_HOUR	=	 new Date (new Date().toDateString() + ' ' + frm.drptime1.value);
		if(sDEPART_HOUR.getHours() >=0 && sDEPART_HOUR.getHours()<=7){
			if(confirm("Are you leaving at night?")){
				frm.action.value='addreservation';
				frm.submit();
			}
		}else{
				frm.action.value='addreservation';
				frm.submit();
		}
	}
	
}
function CompareDates(sDate1, sTime1, sDate2, sTime2){

    var str1 = sDate1;
    var str2 = sDate2;
    var dt1  = parseInt(str1.substring(3,5),10);
    var mon1 = parseInt(str1.substring(0,2),10);
    var yr1  = parseInt(str1.substring(6,10),10);
	var hr1	=	parseInt(sTime1.substring(0,2),10);
	var min1	=	0;
	var sec1	=	0;
	
    var dt2  = parseInt(str2.substring(3,5),10);
    var mon2 = parseInt(str2.substring(0,2),10);
    var yr2  = parseInt(str2.substring(6,10),10);
	var hr2	=	parseInt(sTime2.substring(0,2),10);
	var min2	=	0;
	var sec2	=	0;
		
    var date1 = new Date(yr1, mon1, dt1, hr1, min1, sec1);
    var date2 = new Date(yr2, mon2, dt2, hr2, min2, sec2);
	
	var milli_d1 = date1.getTime();
	var milli_d2 = date2.getTime();
	
    if(milli_d2 <= milli_d1){ return false;  }
}
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
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
								   				<h1 style="margin-bottom: 0px;">RESERVATIONS</h1>
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
					
						<form name="frm1" action="reservations.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="scurrentdate" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="670" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr><td width="100%" align="center" class="Highlight" style="font-weight:bold;">When you can't make a reservation, please tell us by an email to <a href="mailto:transportation@uofnkona.edu">transportation@uofnkona.edu</a></td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td align="center">
										<table cellpadding="0" cellspacing="5" border="0">
											<tr><td colspan="3" class="label">Current Server Time:&nbsp;<? echo date('m/d/Y g:i a');?></td></tr>
											
											<tr>
												<td class="label" colspan="3">
													Assgnd Driver:
													<?	fn_DISPLAY_USERS('drpdriver', $iASSND_DRIVER, "150", "1", "Select Driver", "CONCAT(f_name, ' ', l_name) AS user_name", "f_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF);?><span class="Highlight" style="font-weight:bold;">Our insurance only covers drivers registered in this system</span>
												</td>
												
											</tr>
											<tr>
												<td class="label" width="150">Vehicle:</td>
												<td class="label" width="250">Depart. Date Time:</td>
												<td class="label" width="250">Return Date Time:</td>
											</tr>
											<tr>
												<td><?	fn_VEHICLE_CAPACITY('drpvehicle', $iVehicleID, "150", "1", "Select Vehicle", $bONLY_AVAILABLE, "fn_VIEW_RESERVATIONS();");?></td>
												<td>
													<input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo $sTimePickerDate1;?>" onChange="fn_VIEW_RESERVATIONS();" />
													&nbsp;
													<select name="drptime1" style="width:100px;">
														<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
															<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==$iTIME_1) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
														<?	}?>
													</select>
												</td>
												<td>
													<input readonly="" type="text" name="txtreturndatetime" id="txtreturndatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo $sTimePickerDate2;?>" />
													&nbsp;
													<select name="drptime2" style="width:100px;">
														<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
															<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==$iTIME_1) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
														<?	}?>
													</select>
												</td>
												
												
											</tr>
											<tr>
												
												<td class="label" valign="top">Destination <br />&<br />other comments:</td>
												<td>
												<textarea name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;" onKeyDown="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);" onKeyUp="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);"></textarea>
												&nbsp;<input readonly type="text" name="txtLength" value="100" style="width:25px;">
												</td>
											</tr>
											<? 	if($iVehicleID!=""){?>
											<tr>
												
												<td class="label">Vehicle Restriction:</td>
												<td>
												
												<input readonly="" type="text" name="txtvrestriction" id="txtvrestriction" style="width:200px;" value="<? echo fn_GET_FIELD("tbl_vehicles", $iVehicleID, "vehicle_id", "restriction"); ?>" />							
												</td>
											</tr>
											<?	}?>
											<tr>
												<td class="label">No.of Passengers:</td>
												<td><input type="text" name="txtpassenger" value="" maxlength="2" style="width:50px; text-align:right;"  /></td>
												<td class="label">Overnight?<input type="checkbox" name="chkovernight" value="1" />&nbsp;&nbsp;&nbsp; Need child seat?<input type="checkbox" name="chkseat" value="1" /></td>
											</tr>
											<tr>
												<td class="label">Your Home Dept:</td>
												<? 	
													if($sBILLING_DEPT==""){
														$iHOME_DEPT_ID	=	fn_GET_FIELD('tbl_user', $_SESSION["User_ID"], 'user_id', 'dept_id');
													}else{
														$iHOME_DEPT_ID	=	$sBILLING_DEPT;
													}
													$iHOME_DEPT_NAME=	fn_GET_FIELD('tbl_departments', $iHOME_DEPT_ID, 'dept_id', 'dept_name');
												
												?>
												<td colspan="2"><input type="text" readonly="" name="txthomedept" value="<? echo $iHOME_DEPT_NAME; ?>" style="width:170px;"  /></td>
											</tr>
											<tr>
												<td class="label">Charge this Dept:</td>
												<td colspan="2"><?	fn_DEPARTMENT("drpdept", $iHOME_DEPT_ID, "170", "1", "ALL");?><span class="Highlight" style="font-weight:bold;">Do not charge another dept. without approval</span>
												</td>
											</tr>
											<tr><td><input type="button" name="btngo" value="RESERVE VEHICLE" class="Button" onClick="valid_reservation(this.form);" /></td></tr>
																					
										</table>
										
										<?	if($sAction=="viewreservations"){?>
										<br /><br />
										
										<table cellpadding="0" cellspacing="0" border="0" class="box">
											<tr>
												<td>
												
													<div style="width:670px; height:350px; overflow:auto; scrollbars:auto;" align="center">
													<table cellpadding="0" cellspacing="0" border="0">
														
														<tr>
															<td width="<?=$iTimeCol_WIDTH?>" align="center" class="colhead">Time</td>	<!--time column	-->
															<?	for($iDays=0;$iDays<=6;$iDays++){
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");?>
																	<td width="<?=$iDayCol_WIDTH?>" align="center" class="colhead"><? echo fn_cDateMySql(date('Y-m-d',$sNext_Date),3);	?></td>
															<?	}	?>
														</tr>
														<!-- NOW EXTRACT ALL RESERVATIONS FOR THESE CURRENT 7 DAYS	-->
														<?															
															fn_CHECK_RESERVATION($iVehicleID, $sToday_Date, date('Y-m-d',$sNext_Date), session_id());
														
															for($iCounter=0;$iCounter<=23;$iCounter++){
														?>
														<tr>
															
															<td align="right" height="25" class="coldata leftbox" style="background-color:<?='#CA0000'?>; color:<?='#FFEBD7'?>"><? echo $arrTIME[$iCounter][1]?></td>
															
															<?	for($iDays=0;$iDays<=6;$iDays++){
																	
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");
																	if(fn_CHECK_RESERVATION_TIME(date('Y-m-d',$sNext_Date), substr($arrTIME[$iCounter][0], 0, 2), session_id())){
																	
																		echo "<td align='center' height='25' style='background-color:".$sReserved_COLOR."' class='coldata'>&nbsp;</td>";
																	}else{
																		echo "<td align='center' height='25' style='background-color:".$sFree_COLOR."' class='coldata'>&nbsp;</td>";
																	}
																}
															?>
														</tr>
														
														<?	}	?>
														
													</table>
													</div>
													
												</td>
											</tr>
										</table>
										<table width="100%">
											<tr>
												<td align="left" width="250">													
													<? if(strtotime($sToday_Date)>strtotime(date('Y-m-d'))) {
														$sToday_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " -7 day");
													?>	<a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sToday_Date)?>');">PREVIOUS</a>
													<?	}?>
												</td>
												<td style="background-color:<?=$sReserved_COLOR?>" width="5">&nbsp;</td>
												<td width="45">reserved</td>
												<td style="background-color:<?=$sFree_COLOR?>" width="5"></td>
												<td width="45">available</td>
												<td align="right" width="250"><a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sNext_Date)?>');">NEXT</a></td>
												
											</tr>
											
										</table>
										<?	}	
											//after check reservations delete temp
											fn_DELETE_TEMP(session_id());
										?>
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
	<div id="popupContact">
		<div id="contactArea" style="padding-left:10px;">asdfasdf</div>
		<div style="text-align:center; width:100px; margin:0 auto;"><input type="button" name="btnclose" value="CLOSE" class="Button" id="popupContactClose" style="width:100px;" /></div>
	</div>
<div id="backgroundPopup"></div>