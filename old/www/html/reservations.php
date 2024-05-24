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
	$iHOME_DEPT_ID	=	"";
	$iHOME_DEPT_NAME=	"";
	$iUSER_MAX_PASSENGERS=0;
	$iASSND_DRIVER	=	"";
	$bONLY_AVAILABLE=	true;
	$bONLY_2_3_VEHICLES	=	true;
	$sLOGOUT_MESSAGE	=	"";
	$sNOTICE			=	"";
	$iNEW_RESERVATION_NO=	"";
	$sREPEAT_DEPART_DATETIME	=	"";
	$sREPEAT_RETURN_DATETIME	=	"";
	$iTIME_1		=	"00:00";
	$iTIME_2		=	"00:00";
	$sDESTINATION	=	"";
	$iPASSENGER		=	"";
	$iMAX_PASSENGER	=	0;
	$bOVERNIGHT		=	0;
	$bCHILDSEAT		=	0;
	$bVEHICLE_RESERVED	=	"";		//FOR check vehicle is reserved for service or REMOVED
	$bNO_COST		=	0;

	$iREPEAT_WEEK	=	"";
	$sSELECTED_VEHICEL	=	"";



	//selecting home department of the logged in user
	$sSQL	=	"SELECT u.dept_id, u.max_passengers, d.dept_name FROM tbl_user u INNER JOIN tbl_departments d ON u.dept_id = d.dept_id WHERE u.user_id = ".$_SESSION["User_ID"];
	$rsHOME_DEPT	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsHOME_DEPT)>0){
		list($iHOME_DEPT_ID, $iUSER_MAX_PASSENGERS, $iHOME_DEPT_NAME)	=	mysql_fetch_row($rsHOME_DEPT);
	}mysql_free_result($rsHOME_DEPT);

	if($iHOME_DEPT_NAME=="")		$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Your home department is not set, you must need to have an Home Department to reserve a vehicle", "C_ERROR");

	//$iHOME_DEPT_ID	=	fn_GET_FIELD('tbl_user', $_SESSION["User_ID"], 'user_id', 'dept_id');

	if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC){	$bONLY_AVAILABLE=	false;	}

	if(isset($_POST['drpdriver']) && $_POST['drpdriver']!="")				$iASSND_DRIVER	=		mysql_real_escape_string($_POST['drpdriver']);	else	$iASSND_DRIVER	=		$_SESSION["User_ID"];
	if(isset($_POST["txtdestination"]) && $_POST["txtdestination"]!="")		$sDESTINATION	=		mysql_real_escape_string($_POST["txtdestination"]);
	if(isset($_POST["txtpassenger"]) && $_POST["txtpassenger"]!="")			$iPASSENGER		=		mysql_real_escape_string($_POST["txtpassenger"]);
	//if(isset($_POST["chkovernight"]) && $_POST["chkovernight"]!="")			$bOVERNIGHT	=	1;
	//if(isset($_POST["chkseat"]) && $_POST["chkseat"]!="")					$bCHILDSEAT	=	1;
	//searching criterias

	$bREPEAT_RESERVATION	=	false;

	if(isset($_POST["chkrepeat"]) && $_POST["chkrepeat"]=="1")	$bREPEAT_RESERVATION	=	true;
	if(isset($_POST["chknocost"]) && $_POST["chknocost"]=="1")	$bNO_COST				=	1;

	$sTimePickerDate1=date('m/d/Y',strtotime(date("Y-m-d")));
	$sTimePickerDate2=	date('m/d/Y',strtotime(date("Y-m-d")));


	if($bREPEAT_RESERVATION	==	false){
		if(isset($_POST["txtdeptdatetime"]) && $_POST["txtdeptdatetime"]!="")	$sTimePickerDate1 = $_POST["txtdeptdatetime"];
		if(isset($_POST["txtreturndatetime"]) && $_POST["txtreturndatetime"]!="")	$sTimePickerDate2 = $_POST["txtreturndatetime"];
		if(isset($_POST["drptime1"]))			$iTIME_1		=	$_POST["drptime1"];
		if(isset($_POST["drptime2"]))			$iTIME_2		=	$_POST["drptime2"];

	}else{		//repeating reservation
		if(isset($_POST["drpweek"]) && $_POST["drpweek"]!="")	{$iREPEAT_WEEK	=	$_POST["drpweek"];}
		if(isset($_POST["txtrepeatdeptdatetime"]) && $_POST["txtrepeatdeptdatetime"]!=""){	$sTimePickerDate1 = $_POST["txtrepeatdeptdatetime"];	$sTimePickerDate2 = $_POST["txtrepeatdeptdatetime"];}
		if(isset($_POST["drprepeattime1"]))			$iTIME_1		=	$_POST["drprepeattime1"];
		if(isset($_POST["drprepeattime2"]))			$iTIME_2		=	$_POST["drprepeattime2"];
	}

	if(isset($_POST["action"])	&& $_POST["action"]!="")				$sAction		=	$_POST["action"];
	if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			$iVehicleID		=	mysql_real_escape_string($_POST["drpvehicle"]);
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				$sBILLING_DEPT	=	mysql_real_escape_string($_POST["drpdept"]);



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
			$iDayCol_WIDTH	=	"93";

			$sReserved_COLOR	=	"#FF6633";
			$sFree_COLOR		=	"#FFEBD7";
			//starting date

			if(isset($_POST["scurrentdate"]) && $_POST["scurrentdate"]!="")	$sToday_Date = $_POST["scurrentdate"];	else	$sToday_Date = date("Y-m-d");

			if(strtotime($sToday_Date)<strtotime(date('Y-m-d'))) 		$sToday_Date		=	date("Y-m-d");


	}

	if($iVehicleID!="")		$iMAX_PASSENGER		=	fn_GET_FIELD("tbl_vehicles", $iVehicleID, "vehicle_id", "passenger_cap");		//maximum passengers

	if($sAction=="addreservation"){

			if($bREPEAT_RESERVATION	==	true){
				$sDEPART_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtrepeatdeptdatetime"]);
				$sDEPART_DATETIME		.=	" ".$_POST["drprepeattime1"].":00";

				$sRETURN_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtrepeatdeptdatetime"]);
				$sRETURN_DATETIME		.=	" ".$_POST["drprepeattime2"].":00";
			}else{

				$sDEPART_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtdeptdatetime"]);
				$sDEPART_DATETIME		.=	" ".$_POST["drptime1"].":00";

				$sRETURN_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtreturndatetime"]);
				$sRETURN_DATETIME		.=	" ".$_POST["drptime2"].":00";
			}


				//check if vehicle has removed for service for temporary time period

				/*$sSQL	=	"SELECT CASE WHEN '".$sDEPART_DATETIME."' BETWEEN ".
				"(SELECT CASE WHEN MAX(from_date) IS NULL THEN 0 ELSE MAX(from_date) END AS from_date FROM tbl_srvc_resvs WHERE vehicle_id = ".$iVehicleID." AND is_cancelled = 0) ".
				"AND ".
				"(SELECT CASE WHEN MAX(to_date) IS NULL THEN 0 ELSE MAX(to_date) END AS to_date FROM tbl_srvc_resvs WHERE vehicle_id = ".$iVehicleID." AND is_cancelled = 0) ".
				"THEN 'between' ELSE 'not between' END AS found FROM tbl_work_type";

				$rsSRVC_RESV	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsSRVC_RESV)>0){
					$rowSRVC_RESV	=	mysql_fetch_array($rsSRVC_RESV);
					$bVEHICLE_RESERVED		=	$rowSRVC_RESV['found'];
				}mysql_free_result($rsSRVC_RESV);


				if($bVEHICLE_RESERVED=='between'){
					$sMessage		=	fn_Print_MSG_BOX("Vehicle is temporary unavailable during the selected time period", "C_ERROR");
				}else{*/

					//check 1 department will be able to reserve only 3 vehicles per day

					if($iHOME_DEPT_ID!="1720" && $iHOME_DEPT_ID!="1700" && $iHOME_DEPT_ID!="1300" && $iHOME_DEPT_ID!="1020" && $iHOME_DEPT_ID!=""){

						$sSQL	=	"SELECT CASE WHEN MAX(from_date) IS NULL THEN 0 ELSE MAX(from_date) END AS from_date, CASE WHEN MAX(to_date) IS NULL THEN 0 ELSE MAX(to_date) END AS to_date FROM tbl_3_vehicle_limit WHERE dept_id = '".$iHOME_DEPT_ID."'";
						//print($sSQL);
						$rsPOLICY	=	mysql_query($sSQL) or die(mysql_error());
						$rowPOLICY	=	mysql_fetch_array($rsPOLICY);
						if($rowPOLICY['from_date']!=0 && $rowPOLICY['to_date']!=0){

							$sPOLICY_FROM_DATE	=	$rowPOLICY['from_date'];
							$sPOLICY_TO_DATE	=	$rowPOLICY['to_date'];
							mysql_query("set @i = -1");
							$sSQL	=	"SELECT 'matched' FROM tbl_reservations WHERE '".substr($sDEPART_DATETIME, 0, 10)."' IN (";
							$sSQL	.=	"SELECT DATE(ADDDATE('".$sPOLICY_FROM_DATE."', INTERVAL @i:=@i+1 DAY)) AS date FROM tbl_user HAVING @i < DATEDIFF('".$sPOLICY_TO_DATE."', '".$sPOLICY_FROM_DATE."')";
							$sSQL	.=	")";
							$rsPOLICY_RESULT	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsPOLICY_RESULT)<=0){
								$bONLY_2_3_VEHICLES		=	fn_3_VEHICLE_LIMIT($iHOME_DEPT_ID, substr($sDEPART_DATETIME, 0, 10));
								if($bONLY_2_3_VEHICLES==false)		$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>your department has already reserved 3 vehicles for ".fn_cDateMySql($sDEPART_DATETIME, 4), "C_ERROR");
							}

						}else{
							$bONLY_2_3_VEHICLES		=	fn_3_VEHICLE_LIMIT($iHOME_DEPT_ID, substr($sDEPART_DATETIME, 0, 10));
							if($bONLY_2_3_VEHICLES==false)		$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>your department has already reserved 3 vehicles for ".fn_cDateMySql($sDEPART_DATETIME, 4), "C_ERROR");
						}
					}



					if(strtotime($sDEPART_DATETIME) < strtotime(date('Y-m-d H:i:s'))){
						$sMessage		=	fn_Print_MSG_BOX("<li>vehicle cannot be reserved in past date or time", "C_ERROR");
					}else{

						if($bONLY_2_3_VEHICLES	==	true){


							if($bREPEAT_RESERVATION	==	true){//there would be repeating reservation calculation==================

								for($iWeekCounter=1;$iWeekCounter<=intval($_POST["drpweek"]);$iWeekCounter++){
									if($iWeekCounter>1){ //increment for 2nd week

										$sREPEAT_DEPART_DATETIME	=	strtotime(date("Y-m-d H:i:s", strtotime($sDEPART_DATETIME)) . " +".($iWeekCounter-1)." week");
										$sREPEAT_RETURN_DATETIME	=	date('Y-m-d',$sREPEAT_DEPART_DATETIME)." ".$_POST["drprepeattime2"].":00";
										$sREPEAT_DEPART_DATETIME	=	date('Y-m-d H:i:s',$sREPEAT_DEPART_DATETIME);

									}else{
										$sREPEAT_DEPART_DATETIME	=	$sDEPART_DATETIME;
										$sREPEAT_RETURN_DATETIME	=	$sRETURN_DATETIME;
									}

									//check vehicle reservation
									$sMessage	=	fn_CHECK_VEHICLE_RESERVATION($iVehicleID, $sREPEAT_DEPART_DATETIME, $sREPEAT_RETURN_DATETIME, session_id());		//for first reservation will use same statistics as normal reservation
									//now check driver reservation
									if($sMessage=="")	$sMessage	=	fn_CHECK_ASSIGNED_DRIVER_RESERVATION($_POST["drpdriver"], $sREPEAT_DEPART_DATETIME, $sREPEAT_RETURN_DATETIME, session_id());

									if($sMessage!="")
										break;
									//else
									//	if(!fn_CUSHION_RESERVATION($iVehicleID, $sREPEAT_DEPART_DATETIME, $sREPEAT_RETURN_DATETIME)){	$sMessage	=	fn_Print_MSG_BOX("there must need to have 1 hour cushion between reservations", "C_ERROR");			}
								}

							}else{//================end of repeating reservations=====================

									//FIRST CHECK FOR 60 DAYS LIMIT FROM RESERVATION DATE
									$iDAYS_LIMIT	=	fn_CALCULATE_DATE_DIFF(date("Y-m-d H:i:s"), $sDEPART_DATETIME);
//tony Feb 2019 allowing admin to have unmited time in advance to do reservations
									if($iDAYS_LIMIT>=59) {
										if($_SESSION["User_Group"]==$iGROUP_TM|| $_SESSION["User_Group"]==$iGROUP_TC){
											$sMessage=fn_CHECK_VEHICLE_RESERVATION($iVehicleID, $sDEPART_DATETIME, $sRETURN_DATETIME, session_id());
											if($sMessage=="")$sMessage	=	fn_CHECK_ASSIGNED_DRIVER_RESERVATION($_POST["drpdriver"], $sDEPART_DATETIME, $sRETURN_DATETIME, session_id());
										} else {
											$sMessage	=	fn_Print_MSG_BOX("<li class='bold-font'>Reservations more than 60 days in the future are not allowed (Unless Admin)", "C_ERROR");	
										}
									}else{

										$sMessage	=	fn_CHECK_VEHICLE_RESERVATION($iVehicleID, $sDEPART_DATETIME, $sRETURN_DATETIME, session_id());
										if($sMessage=="")	$sMessage	=	fn_CHECK_ASSIGNED_DRIVER_RESERVATION($_POST["drpdriver"], $sDEPART_DATETIME, $sRETURN_DATETIME, session_id());
										//if(!fn_CUSHION_RESERVATION($iVehicleID, $sDEPART_DATETIME, $sRETURN_DATETIME)){	$sMessage	=	fn_Print_MSG_BOX("there must need to have 1 hour cushion between reservations", "C_ERROR");			}

									}

							}


							if($sMessage==""){

								if($bREPEAT_RESERVATION	==	true){
									for($iWeekCounter=1;$iWeekCounter<=intval($_POST["drpweek"]);$iWeekCounter++){

										if($iWeekCounter>1){ //increment / calculate for 2nd week
											$sREPEAT_DEPART_DATETIME	=	strtotime(date("Y-m-d H:i:s", strtotime($sDEPART_DATETIME)) . " +".($iWeekCounter-1)." week");
											$sREPEAT_RETURN_DATETIME	=	date('Y-m-d',$sREPEAT_DEPART_DATETIME)." ".$_POST["drprepeattime2"].":00";
											$sREPEAT_DEPART_DATETIME	=	date('Y-m-d H:i:s',$sREPEAT_DEPART_DATETIME);
										}else{
											$sREPEAT_DEPART_DATETIME	=	$sDEPART_DATETIME;
											$sREPEAT_RETURN_DATETIME	=	$sRETURN_DATETIME;
										}

										$sSQL="INSERT INTO tbl_reservations(vehicle_id, user_id, planned_passngr_no, planned_depart_day_time, ".
										"planned_return_day_time, overnight, childseat, destination, billing_dept, assigned_driver, repeating, no_cost) ".
										"VALUES(".$iVehicleID.", ".$_SESSION["User_ID"].", '".$_POST["txtpassenger"]."', ".
										"'".$sREPEAT_DEPART_DATETIME."', '".$sREPEAT_RETURN_DATETIME."', ".$bOVERNIGHT.", ".$bCHILDSEAT.", '".addslashes($_POST["txtdestination"])."', ".$_POST["drpdept"].", ".$_POST["drpdriver"].", 1, ".$bNO_COST.")";

										/*print("<br />query");
										print($sSQL);*/

										$rsRESERVATION=mysql_query($sSQL) or die(mysql_error());
										$iNEW_RESERVATION_NO = mysql_insert_id();

										$sMessage	=	fn_SEND_RESERVATION_EMAIL($_SESSION["User_ID"], $_SESSION["User_Group"], $_POST["drpdept"], $iNEW_RESERVATION_NO);		//=============EMAIL SENDING START=================

									}
								}else{

									$sSQL="INSERT INTO tbl_reservations(vehicle_id, user_id, planned_passngr_no, planned_depart_day_time, ".
									"planned_return_day_time, overnight, childseat, destination, billing_dept, assigned_driver, no_cost) ".
									"VALUES(".$iVehicleID.", ".$_SESSION["User_ID"].", '".$_POST["txtpassenger"]."', ".
									"'".$sDEPART_DATETIME."', '".$sRETURN_DATETIME."', ".$bOVERNIGHT.", ".$bCHILDSEAT.", '".addslashes($_POST["txtdestination"])."', ".$_POST["drpdept"].", ".$_POST["drpdriver"].", ".$bNO_COST.")";
									$rsRESERVATION=mysql_query($sSQL) or die(mysql_error());
									$iNEW_RESERVATION_NO = mysql_insert_id();

									$sMessage	.=	fn_SEND_RESERVATION_EMAIL($_SESSION["User_ID"], $_SESSION["User_Group"], $_POST["drpdept"], $iNEW_RESERVATION_NO);		//=============EMAIL SENDING START=================
								}



								//fn_DELETE_TEMP(session_id());		//after check reservations delete temp




							}
						}//end if $bONLY_2_3_VEHICLES	=	false;
					}

				//}//end else part of temporary availablilty

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
<meta name="generator" content="Bluefish 2.2.8" >

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
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<?	//print("SESSION=".$_SESSION["load_counter"])	;
		if(isset($_SESSION["load_counter"]) && $_SESSION["load_counter"]=="1" && $sNOTICE!=""){
		$_SESSION["load_counter"]="2";
?>
<script type="text/javascript" src="./js/popup.js"></script>
<script language="JavaScript">
		$(document).ready(function () {
			document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
			document.getElementById('contactArea').innerHTML	=	<?php echo json_encode("<h1 class='notice-heading'notice_heading>".$sNOTICE_TITLE."</h1><br /><br />".$sNOTICE); ?>;
			centerPopup();
			loadPopup();
		});
</script>
<?	}?>
<script language="JavaScript">
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

function fn_CHANGE_TO_DATE(){

	if(document.frm1.txtdeptdatetime.value!=""){					document.frm1.txtreturndatetime.value	=	document.frm1.txtdeptdatetime.value;}

}
function valid_reservation(frm){

	var sErrMessage='';
	var iErrCounter=0;

	var sTODAY_DATE	=	"<?Php echo date('m/d/Y');?>";

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
		}else{
			if(parseInt(<?Php echo $iMAX_PASSENGER?>) < parseInt(frm.txtpassenger.value)){
				sErrMessage=sErrMessage+'<li>'+frm.txtpassenger.value+' passengers are not allowed in the selected vehicle';
				iErrCounter++;
			}
		}
	}
	<?Php if($_SESSION["User_Group"]==$iGROUP_TM|| $_SESSION["User_Group"]==$iGROUP_TC){?>

	if(frm.chkrepeat.checked){
		if (frm.drpweek.value==""){
			sErrMessage=sErrMessage+'<li>please select weeks for reservation';
			iErrCounter++;
		}
		if (frm.txtrepeatdeptdatetime.value==""){
			sErrMessage=sErrMessage+'<li>please select departure date and time';
			iErrCounter++;
		}else{

			if(days_between(frm.txtrepeatdeptdatetime.value, sTODAY_DATE)>30){
				sErrMessage=sErrMessage+'<li>Repeating reservations must be start within 30 days';
				iErrCounter++;
			}else{

				if(CompareDates('01/01/1970', frm.drprepeattime1.value, '01/01/1970', frm.drprepeattime2.value)==false){
					sErrMessage=sErrMessage+'<li>return time must be greater than departure time';
					iErrCounter++;
				}
			}
		}
	}else{
		if (frm.txtdeptdatetime.value==""){
			sErrMessage=sErrMessage+'<li>please select departure date and time';
			iErrCounter++;
		}

		if (frm.txtreturndatetime.value==""){
			sErrMessage=sErrMessage+'<li>please select return date and time';
			iErrCounter++;
		}


//		if(days_between(frm.txtdeptdatetime.value, sTODAY_DATE)>60){
//				sErrMessage=sErrMessage+'<li>Reservations more than 60 days in the future are not allowed';
//				iErrCounter++;
//		}else{

			if (frm.txtdeptdatetime.value!="" && frm.txtreturndatetime.value!=""){
				if(CompareDates(frm.txtdeptdatetime.value, frm.drptime1.value, frm.txtreturndatetime.value, frm.drptime2.value)==false){
					sErrMessage=sErrMessage+'<li>return date and time must be greater than departure date and time';
					iErrCounter++;
				}
			}
//		}
	}
	<?	}else{?>

			if (frm.txtdeptdatetime.value==""){
				sErrMessage=sErrMessage+'<li>please select departure date and time';
				iErrCounter++;
			}

			if (frm.txtreturndatetime.value==""){
				sErrMessage=sErrMessage+'<li>please select return date and time';
				iErrCounter++;
			}

			if(days_between(frm.txtdeptdatetime.value, sTODAY_DATE)>60){
					sErrMessage=sErrMessage+'<li>Reservations more than 60 days in the future are not allowed';
					iErrCounter++;
			}else{
				if (frm.txtdeptdatetime.value!="" && frm.txtreturndatetime.value!=""){
					if(CompareDates(frm.txtdeptdatetime.value, frm.drptime1.value, frm.txtreturndatetime.value, frm.drptime2.value)==false){
						sErrMessage=sErrMessage+'<li>return date and time must be greater than departure date and time';
						iErrCounter++;
					}
				}
			}
	<?	}?>

	if (frm.txtdestination.value==""){
		sErrMessage=sErrMessage+'<li>please enter destination and purpose';
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
		<?Php if($_SESSION["User_Group"]==$iGROUP_TM|| $_SESSION["User_Group"]==$iGROUP_TC){?>
		if(frm.chkrepeat.checked){
			var sDEPART_HOUR	=	 new Date (new Date().toDateString() + ' ' + frm.drprepeattime1.value);
		}else{
			var sDEPART_HOUR	=	 new Date (new Date().toDateString() + ' ' + frm.drptime1.value);
		}
		<?Php	}else{?>
		var sDEPART_HOUR	=	 new Date (new Date().toDateString() + ' ' + frm.drptime1.value);
		<?Php	}?>
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

function days_between(sDate1, sDate2) {

	var str1 = sDate1;
    var str2 = sDate2;


    var dt1  = parseInt(str1.substring(3,5),10);
    var mon1 = parseInt(str1.substring(0,2),10);
    var yr1  = parseInt(str1.substring(6,10),10);
	var hr1	=	0;
	var min1	=	0;
	var sec1	=	0;

    var dt2  = parseInt(str2.substring(3,5),10);
    var mon2 = parseInt(str2.substring(0,2),10);
    var yr2  = parseInt(str2.substring(6,10),10);
	var hr2	=	0;
	var min2	=	0;
	var sec2	=	0;

    var date1 = new Date(yr1, mon1, dt1, hr1, min1, sec1);
    var date2 = new Date(yr2, mon2, dt2, hr2, min2, sec2);


    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24

    // Convert both dates to milliseconds
    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms)

    // Convert back to days and return
    return Math.round(difference_ms/ONE_DAY)

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
function fn_SHOW_HIDE_REPEAT(bDISPLAY){
	if(bDISPLAY){
		document.getElementById('repeat_reservation').style.display = 'block';
		document.getElementById('non_repeating').style.display = 'none';
	}else{
		document.getElementById('repeat_reservation').style.display = 'none';
		document.getElementById('non_repeating').style.display = 'block';
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
                	<td width="949" class="TextObject" align="center">

						<form name="frm1" action="reservations.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="scurrentdate" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="800" align="center" class="box">

								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr><td width="100%" align="center" class="Highlight bold-font">When you can't make a reservation, please tell us by an email to <a href="mailto:transportation@uofnkona.edu">transportation@uofnkona.edu</a></td></tr>
								<tr><td class="Highlight" align="center">Current Time:&nbsp;<? echo date('m/d/Y g:i a');?></td></tr>
								<tr>
									<td align="center">
										<table cellpadding="0" cellspacing="5" border="0">
											<tr>
												<td class="label">Vehicle:</td>
												<td colspan="2">
												<?	//fn_VEHICLE_CAPACITY('drpvehicle', $iVehicleID, "150", "1", "Select Vehicle", $bONLY_AVAILABLE, "fn_VIEW_RESERVATIONS();");
												$sSQL	=	"SELECT v.vehicle_id, v.vehicle_no, v.passenger_cap FROM tbl_vehicles v WHERE v.vehicle_id NOT IN (SELECT s.vehicle_id FROM tbl_srvc_resvs s WHERE s.is_cancelled = 0) AND v.sold = 0 AND v.passenger_cap <= ".mysql_real_escape_string($iUSER_MAX_PASSENGERS)." ORDER BY (vehicle_no+0)";
												$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
													if(mysql_num_rows($rsVEHICLE)>0){


														echo "<select name='drpvehicle' size='1' style='width:150px;' onchange='fn_VIEW_RESERVATIONS();'>";
														echo "<option value=''>Select Vehicle</option>";
														while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){
															if($iVehicleID==$rowVEHICLE['vehicle_id'])	$sSELECTED_VEHICEL	=	"selected";		else	$sSELECTED_VEHICEL	=	"";
															echo "<option value=".$rowVEHICLE['vehicle_id']." ".$sSELECTED_VEHICEL.">NO:&nbsp;".$rowVEHICLE['vehicle_no']."&nbsp;-&nbsp;CAPACITY:&nbsp;".$rowVEHICLE['passenger_cap']."</option>";
														}
														echo "</select>";
													}mysql_free_result($rsVEHICLE);
												?>
												<div style="font-size: 12px; display: inline-block; padding-left: 10px; width: 400px;">Vehicle list is limited by the maximum number of passengers the currently logged in user is authorized to drive.</div>
												</td>
											</tr>
											<tr>
												<td class="label" width="150">Assigned Driver:</td>
												<td colspan="2">
												<?Php
													if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC || ($_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF && fn_GET_FIELD("tbl_user", $_SESSION["User_ID"], "user_id", "user_type")!="Student")){
														fn_DISPLAY_USERS('drpdriver', $iASSND_DRIVER, "150", "1", "Select Driver", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_TM.",".$iGROUP_TC, "","tbl_user.active = 1");
													}else{
														if(fn_GET_FIELD("tbl_user", $iASSND_DRIVER, "user_id", "user_type")=="Student" && $_SESSION["User_Group"] != $iGROUP_COORDINATOR_STAFF){
															echo "<input type='hidden' name='drpdriver' value='".$iASSND_DRIVER."' />" ;
															echo "<input type='text' readonly name='txtdrivername' value='".fn_GET_FIELD("tbl_user", $iASSND_DRIVER, "user_id", "l_name")." ".fn_GET_FIELD("tbl_user", $iASSND_DRIVER, "user_id", "f_name")." (self)' />";
														}else{
															fn_DISPLAY_USERS('drpdriver', $iASSND_DRIVER, "150", "1", "Select Driver", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF, "","tbl_user.active = 1");
														}
													}
													?>
													<span class="Highlight bold-font" style="padding-left: 10px;">Our insurance only covers drivers registered in this system</span>
												</td>

											</tr>

											<?Php if($_SESSION["User_Group"]==$iGROUP_TM|| $_SESSION["User_Group"]==$iGROUP_TC){?>
											<tr>
												<td class="label">No Cost:</td>
												<td><input type="checkbox" name="chknocost" value="1"/><span class="Highlight">Admin must approve no cost trips</span></td>
											</tr>
											<tr>
												<td class="label" colspan="3">
													<input type="checkbox" name="chkrepeat" value="1" onClick="fn_SHOW_HIDE_REPEAT(this.checked);" <? if($bREPEAT_RESERVATION) echo "checked";?> />Repeating Reservation:
													<span class="Highlight" style="font-weight:bold;">Duplicates reservation weekly in same time slot for no. of weeks you choose</span>
												</td>
											</tr>

											<tr>
												<td colspan="3">
													<div id="repeat_reservation" style="border:1px solid #ccc; display:<? if($bREPEAT_RESERVATION) echo "block;"; else echo "none;";?>">
													<table cellspacing="3" cellpadding="0" border="0">
														<tr>
															<td colspan="4" class="label"></td>
														</tr>
														<tr>
															<td class="label" width="100">Weeks:</td>
															<td class="label" width="120">First Day:</td>
															<td class="label" width="100">Depart Time:</td>
															<td class="label" width="100">Return Time:</td>
														</tr>
														<tr>
															<td>
																<select name="drpweek" style="width:100px;">
																	<option value="">--Weeks--</option>
																	<?	for($iWeekCounter=2;$iWeekCounter<=8;$iWeekCounter++){?>
																	<option value="<? echo $iWeekCounter;?>" <? if(intval($iREPEAT_WEEK)==$iWeekCounter) echo "selected";?>><? echo $iWeekCounter;?></option>
																	<?	}?>
																</select>
															</td>
															<td class="label">
																<input readonly="" type="text" name="txtrepeatdeptdatetime" id="txtrepeatdeptdatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo $sTimePickerDate1;?>" onChange="fn_VIEW_RESERVATIONS();" />
															</td>
															<td>

																<select name="drprepeattime1" style="width:100px;">
																	<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
																		<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==$iTIME_1) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
																	<?	}?>
																</select>
															</td>
															<td>
																<select name="drprepeattime2" style="width:100px;">
																	<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
																		<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==$iTIME_2) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
																	<?	}?>
																</select>
															</td>
														</tr>
													</table>
													</div>

												</td>
											</tr>
											<?Php }	?>
											<tr>

												<td colspan="3">
													<div id="non_repeating" style="border:1px solid #ccc; display:<? if($bREPEAT_RESERVATION) echo "none;"; else echo "block;";?>">
													<table cellpadding="0" cellspacing="3" border="0">
														<tr>
															<td class="label" width="250">Depart. Date Time:</td>
															<td class="label" width="250">Return Date Time:</td>
														</tr>
														<tr>
															<td>
																<input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo $sTimePickerDate1;?>" onChange="fn_VIEW_RESERVATIONS(); fn_CHANGE_TO_DATE();" />
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
																		<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==$iTIME_2) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
																	<?	}?>
																</select>
															</td>
														</tr>
													</table>
													</div>
												</td>

											</tr>
											<tr>

												<td class="label" valign="top">Destination <br />&<br />Purpose:</td>
												<td>
												<textarea name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;" onKeyDown="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);" onKeyUp="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);"><? echo $sDESTINATION;?></textarea>
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
												<td><input type="text" name="txtpassenger" value="<? echo $iPASSENGER;?>" maxlength="2" style="width:50px; text-align:right;"  /></td>
												<td class="label">
													<!--Overnight?<input type="checkbox" name="chkovernight" value="1" <? //if($bOVERNIGHT==1) echo "checked";?> />&nbsp;&nbsp;&nbsp; -->
													<!--Need child seat?<input type="checkbox" name="chkseat" value="1" <? //if($bCHILDSEAT==1) echo "checked";?> />-->
												</td>
											</tr>
											<tr>
												<td class="label">Your Home Dept:</td>
												<?
													//$iHOME_DEPT_NAME=	fn_GET_FIELD('tbl_departments', $iHOME_DEPT_ID, 'dept_id', 'dept_name');

													if($sBILLING_DEPT==""){	$sBILLING_DEPT		=		$iHOME_DEPT_ID;}
												?>
												<td colspan="2"><input type="text" readonly="" name="txthomedept" value="<? echo $iHOME_DEPT_NAME; ?>" style="width:200px;"  /></td>
											</tr>
											<tr>
												<?Php 	if(fn_GET_FIELD("tbl_user", $iASSND_DRIVER, "user_id", "user_type")=="Student" && $_SESSION["User_Group"] != $iGROUP_COORDINATOR_STAFF){
															echo "<td colspan='3'><input type='hidden' name='drpdept' value='".$sBILLING_DEPT."' /></td>";
														}else{
												?>
												<td class="label">Charge this Dept:</td>
												<td colspan="2"><?	fn_DEPARTMENT("drpdept", $sBILLING_DEPT, "200", "1", "--Select Billing Dept--");?><span class="Highlight bold-font" style="padding-left: 10px;">Do not charge another dept. without approval</span></td>
												<?Php	}?>
											</tr>
											<?Php 	if($iHOME_DEPT_NAME!=""){?>
											<tr><td><input type="button" name="btngo" value="RESERVE VEHICLE" class="Button" onClick="valid_reservation(this.form);" /></td></tr>
											<?Php	}?>

										</table>



										<?	if($sAction=="viewreservations"){
												$arrRESERVATIONS		=		array();
										?>
										<br /><br />

										<table cellpadding="0" cellspacing="0" border="0" class="box">
											<tr>
												<td>

													<div style="width:750px; height:350px; overflow:auto; scrollbars:auto;" align="center">
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
															$arrRESERVATIONS	=	fn_CHECK_RESERVATION($iVehicleID, $sToday_Date, date('Y-m-d',$sNext_Date), session_id());
															//print_r($arrRESERVATIONS);

															for($iCounter=0;$iCounter<=23;$iCounter++){
														?>
														<tr>

															<td align="right" height="25" class="coldata leftbox" style="background-color:<?='#CA0000'?>; color:<?='#FFEBD7'?>"><? echo $arrTIME[$iCounter][1]?></td>

															<?	for($iDays=0;$iDays<=6;$iDays++){

																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");
																	//print("<BR />TIME==".$arrTIME[$iCounter][0].":00");
																	//if(fn_CHECK_RESERVATION_TIME(date('Y-m-d',$sNext_Date), substr($arrTIME[$iCounter][0], 0, 2), session_id())){
																	if(in_array(date('Y-m-d',$sNext_Date)." ".$arrTIME[$iCounter][0].":00", $arrRESERVATIONS)){
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
											//fn_DELETE_TEMP(session_id());
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
<?Php

function fn_3_VEHICLE_LIMIT($iRES_PER_HOME_DEPT, $sDEPART_DATE){
	$sSQL	=	"SELECT COUNT(res_id) total_reservation FROM tbl_reservations ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"WHERE tbl_user.dept_id = '".$iRES_PER_HOME_DEPT."' ".
	"AND DATE(tbl_reservations.planned_depart_day_time) = DATE('".$sDEPART_DATE."')";
	//print($sSQL);
	$rsCOUNT_RES	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsCOUNT_RES)>0){
		$rowCOUNT_RES	=	mysql_fetch_array($rsCOUNT_RES);
		if($rowCOUNT_RES['total_reservation']	>=	3){
	// steve want admins to not have restrictions feb 2019
			if($_SESSION["User_Group"]==$iGROUP_TM|| $_SESSION["User_Group"]==$iGROUP_TC){
				return true; 
			} else {
				return false;
			}
		}else{
			return true;
		}
	}mysql_free_result($rsCOUNT_RES);
}


function fn_SEND_RESERVATION_EMAIL($iRESRV_USER, $iRESRV_USER_GROUP, $sBILLING_DEPT, $iRESERVATION_NO){

$sDriver_Email	=	"";		$sDriver_Dept	=	"";		$sLeader_Email	=	"";
$sEmailSubject	=	"";		$sMailMSG		=	"";		$sLOGOUT_MESSAGE=	"";
$sReserving_Person	=	"";
global $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name, $iGROUP_DRIVER, $iGROUP_COORDINATOR_STAFF;

							$sSQL	=	"SELECT u.email, u.dept_id, d.leader_email FROM tbl_user u INNER JOIN tbl_departments d ON u.dept_id = d.dept_id WHERE u.user_id = ".$iRESRV_USER;
							$rsRESV_PERSON_STATISTICS	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsRESV_PERSON_STATISTICS)>0){
								list($sDriver_Email, $sDriver_Dept, $sLeader_Email)	=	mysql_fetch_row($rsRESV_PERSON_STATISTICS);
							}mysql_free_result($rsRESV_PERSON_STATISTICS);

							if($sDriver_Dept!=$sBILLING_DEPT){
								$sReserving_Person	=	fn_GET_FIELD_BY_QUERY("SELECT CONCAT(f_name, ' ',l_name) AS reserving_person FROM tbl_user WHERE user_id = ".$iRESRV_USER);
								//send email to charged department
								$sCharged_Dept_Leader_Email	=	fn_GET_FIELD("tbl_departments", $sBILLING_DEPT, "dept_id", "leader_email");
								//$sEmailSubject	=	"Billing Notice for vehicle reservation";
								//$sMailMSG		=	"<span style='font-size:15px; font-weight:bold;'>Your department has been billed for van reservation No.  ___".$iRESERVATION_NO."__ that was charged to you by another department, <br />that reservation was made by ".$sReserving_Person.".<br />   If you did not approve this reservation, please contact ".$sReserving_Person." so they can arrange to have this billing corrected.</span><br /><br />";
								$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 12";
								$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
								$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 12";
								$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
								$sMailMSG		=	str_replace('#TESTING PERSON#', $sReserving_Person, str_replace('#RESV NO#', $iRESERVATION_NO, $sMailMSG));
								//return fn_Print_MSG_BOX($sMailMSG,"C_ERROR");
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

							$sMailMSG		=	fn_PRINT_TRIP_SLIP($iRESERVATION_NO);

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

							if($iGROUP_DRIVER==$iRESRV_USER_GROUP || $iGROUP_COORDINATOR_STAFF==$iRESRV_USER_GROUP){$sLOGOUT_MESSAGE	=	"<br /><br />--------BE SURE TO LOG OFF WHEN YOU ARE DONE HERE--------";}

							if(!$mail->Send())
							{
								return	fn_Print_MSG_BOX("Your reservation has been recorded, <br />but Error in Sending Email, $mail->ErrorInfo".$sLOGOUT_MESSAGE,"C_ERROR");
							}else{

								return	fn_Print_MSG_BOX("Your reservation has been recorded.<br />You will receive a trip slip to confirm it.<br />The Transportation Manager will have a copy of your trip slip and the keys hanging on the Departure Board for you to pick up during office hours.".$sLOGOUT_MESSAGE, "C_SUCCESS");
							}
}
?>
