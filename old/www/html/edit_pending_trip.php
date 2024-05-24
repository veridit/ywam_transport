<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iRESERVATION_ID	=	0;
	$iVEHICLE_ID		=	0;
	$iRECORD_COUNT		=	0;
	$sCRITERIA_SQL		=	"";
	$sAction			=	"";
	$bCLOSED			=	false;
	if(isset($_REQUEST["resid"]))	$iRESERVATION_ID		=	$_REQUEST["resid"];
	if(isset($_POST["action"])	&& $_POST["action"]!="")				$sAction		=	$_POST["action"];
	
	
	//UPDATE RESERVATION
	if($sAction=="updatetrip"){
		
		if(isset($_POST["txtvehicleno"])	&& $_POST["txtvehicleno"]!=""){	
			$iVEHICLE_ID	=	fn_GET_FIELD("tbl_vehicles", mysql_real_escape_string($_POST["txtvehicleno"]), "vehicle_no", "vehicle_id");	}
		
		if($iVEHICLE_ID!="false"){
			
					$bOVERNIGHT	=	0;		$bCHILDSEAT		=	0;
							
					$sDEPART_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtdeptdatetime"]);
					$sDEPART_DATETIME		.=	" ".$_POST["drptime1"].":00";
					
					$sRETURN_DATETIME		=	fn_DATE_TO_MYSQL($_POST["txtreturndatetime"]);
					$sRETURN_DATETIME		.=	" ".$_POST["drptime2"].":00";
					//echo "Updating FRED 3 ";													

					//FIRST CHECK FOR 60 DAYS LIMIT FROM RESERVATION DATE
					$iDAYS_LIMIT	=	fn_CALCULATE_DATE_DIFF(date("Y-m-d H:i:s"), $sDEPART_DATETIME);	
					
					if($iDAYS_LIMIT>=59)
						$sMessage	=	fn_Print_MSG_BOX("<li>Vehicle can't be reserved after 60 days in future", "C_ERROR");
					else{

						$sMessage	=	fn_CHECK_VEHICLE_RESERVATION($iVEHICLE_ID, $sDEPART_DATETIME, $sRETURN_DATETIME, session_id(), $iRESERVATION_ID);
						if($sMessage=="")	$sMessage	=	fn_CHECK_ASSIGNED_DRIVER_RESERVATION($_POST["drpdriver"], $sDEPART_DATETIME, $sRETURN_DATETIME, session_id(), $iRESERVATION_ID);
					}
				
					//if(!fn_CUSHION_RESERVATION($iVEHICLE_ID, $sDEPART_DATETIME, $sRETURN_DATETIME)){	$sMessage	=	fn_Print_MSG_BOX("there must need to have 1 hour cushion between reservations", "C_ERROR");			}
				
					if($sMessage==""){
														
						$sSQL="UPDATE tbl_reservations SET vehicle_id = ".$iVEHICLE_ID.", planned_passngr_no = '".mysql_real_escape_string($_POST["txtpassenger"])."', planned_depart_day_time = '".$sDEPART_DATETIME."', ".
						"planned_return_day_time = '".$sRETURN_DATETIME."', destination = '".mysql_real_escape_string(addslashes($_POST["txtdestination"]))."', ".
						"billing_dept = ".mysql_real_escape_string($_POST["drpdept"]).", assigned_driver = ".mysql_real_escape_string($_POST["drpdriver"]).", ".
						"key_no = '".mysql_real_escape_string($_POST['txtkey'])."', card_no = '".mysql_real_escape_string($_POST['txtcard'])."' ".
						"WHERE res_id = ".$iRESERVATION_ID;
						 
						//print($sSQL)."FRED";
						$rsRES=mysql_query($sSQL) or die(mysql_error());
																		
						//send updated trip slip to assigned driver through email
							$sSQL	=	"SELECT u.email FROM tbl_reservations r INNER JOIN tbl_user u ON r.assigned_driver = u.user_id WHERE r.res_id = ".$iRESERVATION_ID;
							//print($sSQL);
							$sASSIGNED_EMAIL	=	mysql_result(mysql_query($sSQL),0);
							$sEmailSubject	=	"Updated Trip Slip From $sCOMPANY_Name";
							$sMailMSG		=	"<span style='font-size:13px; font-weight:bold;'>Your Reservation has been changed as shown below</span><br /><br />".fn_PRINT_TRIP_SLIP($iRESERVATION_ID);
							//print($sMailMSG);
							$sMailMSG		=	html_entity_decode($sMailMSG, ENT_NOQUOTES,'ISO-8859-1');
							$mail = new PHPMailer();
							$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
							$mail->From     = $sSUPPORT_EMAIL;
							$mail->FromName = $sCOMPANY_Name;
							$mail->AddAddress($sASSIGNED_EMAIL);
							$mail->IsHTML(true);                               // send as HTML
							$mail->Subject  =  $sEmailSubject;
							$mail->Body    = $sMailMSG;
					
							if(!$mail->Send())
							{
								$sMessage		=	fn_Print_MSG_BOX("trip has been updated, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
							}else{
								
								$sMessage		=	fn_Print_MSG_BOX("<li>trip has been updated, and updated trip slip is sent to assigned driver", "C_SUCCESS");
							}
						
						
					}
			
		}else{
			$sMessage		=	fn_Print_MSG_BOX("please enter correct vehicle", "C_ERROR");
		}
	}
		
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){//	delete query
		$sSQL		=	"UPDATE tbl_reservations SET reservation_cancelled = 1, res_delete_user = ".$_SESSION["User_ID"].", res_delete_datetime = '".date('Y-m-d H:i:s')."' WHERE res_id = ".$iRESERVATION_ID;
		$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL	=	"SELECT resv.email, a.email, r.planned_depart_day_time FROM tbl_reservations r INNER JOIN tbl_user resv ON r.user_id = resv.user_id INNER JOIN tbl_user a ON r.assigned_driver = a.user_id WHERE r.res_id = ".$iRESERVATION_ID;
		//print($sSQL);
		$sRESV_DRVR_EMAIL	=	mysql_result(mysql_query($sSQL),0,0);
		$sASGD_DRVR_EMAIL	=	mysql_result(mysql_query($sSQL),0,1);
		$sDEPART_DATETIME	=	mysql_result(mysql_query($sSQL),0,2);
		//print("RESERVING PERSON".$sRESV_DRVR_EMAIL."==ASSIGNED==".$sASGD_DRVR_EMAIL);
		$sEmailSubject	=	"Trip Deletion notification From $sCOMPANY_Name";
		$sMailMSG		=	"Your reservation $iRESERVATION_ID for depart date ".fn_cDateMySql($sDEPART_DATETIME, 2)." has been deleted for administrative reasons. Contact Transportation office for more information";
		//print($sMailMSG);
		
		$mail = new PHPMailer();
		$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
		$mail->From     = $sSUPPORT_EMAIL;
		$mail->FromName = $sCOMPANY_Name;
		
		if($sRESV_DRVR_EMAIL!=$sASGD_DRVR_EMAIL){
			$mail->AddAddress($sRESV_DRVR_EMAIL);
			$mail->AddAddress($sASGD_DRVR_EMAIL);
		}else{
			$mail->AddAddress($sRESV_DRVR_EMAIL);
		}
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  $sEmailSubject;
		$mail->Body    = $sMailMSG;

		if(!$mail->Send())
		{
			$sMessage		=	fn_Print_MSG_BOX("<li>trip has been deleted, <br />but Error in Sending Email to reserving and assigned drivers, $mail->ErrorInfo","C_ERROR");
		}else{
			
			$sMessage		=	fn_Print_MSG_BOX("<li>trip has been delete, and an email has been sent to reserving and assigned driver", "C_SUCCESS");
		}
		
		
	}
	
	///===========================TIME ARRAY====================================
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
	//==========================================================================
	
	$sSQL	=	"SELECT tbl_user.user_id, tbl_reservations.vehicle_id, vehicle_no, tbl_vehicles.restriction, passenger_cap, CONCAT(f_name, ' ', l_name) AS driver_name, ".	
	"planned_passngr_no, planned_depart_day_time, tbl_reservations.reg_date, planned_return_day_time, overnight, childseat, ".
	"destination, coord_approval, reservation_cancelled, cancelled_by_driver, ".
	"tbl_reservations.key_no, tbl_reservations.card_no, ".
	"tbl_departments.dept_name, tbl_reservations.billing_dept, tbl_reservations.assigned_driver, tbl_trip_details.trip_id, ".
	"CASE WHEN tbl_reservations.driver_cancelled_time IS NULL THEN '' ELSE tbl_reservations.driver_cancelled_time END AS driver_cancelled_time, ".
	"CASE WHEN tbl_trip_details.end_gas_percent IS NULL THEN '' ELSE tbl_trip_details.end_gas_percent END AS end_gas, ".
	"CASE WHEN tbl_trip_details.begin_mileage IS NULL THEN '' ELSE tbl_trip_details.begin_mileage END AS begin_miles, ".
	"CASE WHEN tbl_trip_details.end_mileage IS NULL THEN '' ELSE tbl_trip_details.end_mileage END AS end_miles, ".
	"tbl_abandon_trips.abandon_date ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_departments ON tbl_user.dept_id = tbl_departments.dept_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID." AND tbl_trip_details.trip_id IS NULL AND tbl_abandon_trips.res_id IS NULL  ".
	"AND tbl_reservations.coord_approval = 'Approved' AND tbl_reservations.reservation_cancelled = 0 AND cancelled_by_driver = 0";
	
	$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=mysql_num_rows($rsRESERVATION);	
	if($iRECORD_COUNT>0){
		$rowRESERVATION	=	mysql_fetch_array($rsRESERVATION);
	}else{
		if(!isset($_POST["action"])	|| $_POST["action"]==""){
			$sMessage		=	fn_Print_MSG_BOX("<li>Trip is closed, it can be viewed from the List closed trips page!", "C_ERROR");
		}
		$bCLOSED		=	true;	
	}mysql_free_result($rsRESERVATION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Update Reservation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../html/sub_style.css">

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
<script type="text/javascript">
function fn_DELETE_TRIP(frm){
	frm.action.value='delete';
	frm.submit();
}
function valid_cancel(frm){
		frm.action.value='cancel';
		frm.submit();
}
function fn_UPDATE_TRIP(frm){
	if(valid_reservation(frm)){
		frm.action.value='updatetrip';
		frm.submit();
	}
}
function valid_reservation(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	var sTODAY_DATE	=	"<?Php echo date('m/d/Y');?>";
	
	if (frm.drpdriver.value==""){
		sErrMessage='<li>please select driver for this reservation';
		iErrCounter++;
	}
	
	if (frm.txtvehicleno.value==""){
		sErrMessage=sErrMessage+'<li>please enter vehicle no';
		iErrCounter++;
	}
	
	if (frm.drpdept.value==""){
		sErrMessage=sErrMessage+'<li>please select charge department';
		iErrCounter++;
	}
	
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
	
	if (frm.txtdestination.value==""){
		sErrMessage=sErrMessage+'<li>please enter destination';
		iErrCounter++;
	}

		
	if (iErrCounter >0){
		fn_draw_ErrMsg(sErrMessage);
		return false;
	}
	else{
		//frm.action.value='addreservation';
		//frm.submit();
		return true;
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

</script>

</head>
<body>
<div align="center">
						<form name="frm1" action="edit_pending_trip.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="resid" value="<?=$iRESERVATION_ID?>"	/>
							<input type="hidden" name="vid" value="<? echo $rowRESERVATION['vehicle_id'];?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="650" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?Php if($bCLOSED==false){?>
								<tr>
									<td width="200" class="label">:Resv. No::</td>
									<td width="450"><input readonly="" type="text" name="txtvresno" value="<? echo $iRESERVATION_ID;?>" style="width:150px;" /></td>
								</tr>
								<? 	if(!is_null($rowRESERVATION['abandon_date'])){?>
								<tr>
									<td colspan="2" class="Highlight" align="center" style="font-weight:bold;">the trips has been abandoned on <? echo fn_cDateMySql($rowRESERVATION['abandon_date'],2);?></td>
								</tr>
								<?	}?>
								<tr>
									<td class="label">Assigned Driver:</td>
									<td><?	fn_DISPLAY_USERS('drpdriver', $rowRESERVATION['assigned_driver'], "150", "1", "Select Driver", "CONCAT(f_name, ' ', l_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF);?></td>
								</tr>
								<tr>
									<td class="label">Vehicle:</td>
									<td><input type="text" name="txtvehicleno" value="<? echo $rowRESERVATION['vehicle_no'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">No of Psngrs Planned:</td>
									<td><input type="text" name="txtpassenger" value="<? echo $rowRESERVATION['planned_passngr_no'];?>" maxlength="2" style="width:50px; text-align:right;"  /></td>
								</tr>
								
								<tr>
									<td class="label">Rsvrd by:</td>
									<td><input readonly="" type="text" name="txtdrivername" value="<? echo $rowRESERVATION['driver_name'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Date/Time Resvd:</td>
									<td><input readonly="" type="text" name="txtdatetimeresvd" value="<? echo fn_cDateMySql($rowRESERVATION['reg_date'],2);?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Home Dept.:</td>
									<td><input readonly="" type="text" name="txthomedept" value="<? echo $rowRESERVATION['dept_name'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Charge Dept.:</td>
									<td><?	fn_DEPARTMENT("drpdept", $rowRESERVATION['billing_dept'], "215", "1", "Select Department");?></td>
								</tr>
								<tr>
									<td class="label">Key No:</td>
									<td><input type="text" name="txtkey" value="<? echo $rowRESERVATION['key_no'];?>" maxlength="4" style="width:100px; text-align:right;" /></td>
								</tr>
								
								<tr>
									<td class="label">Card No:</td>
									<td><input type="text" name="txtcard" value="<? echo $rowRESERVATION['card_no'];?>" maxlength="8" style="width:100px; text-align:right;" /></td>
								</tr>
								
								<tr>
									<td class="label">Planned Departure Date Time:</td>
									<td>
										<input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo fn_cDateMySql($rowRESERVATION['planned_depart_day_time'], 1);?>" />
										&nbsp;
										<select name="drptime1" style="width:100px;">
											<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
												<option value="<? echo $arrTIME[$iCounter][0];?>" <? if($iCounter==substr($rowRESERVATION['planned_depart_day_time'],11, strlen($rowRESERVATION['planned_depart_day_time']))) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
											<?	}?>
										</select>
									</td>
								</tr>
								<tr>
									<td class="label">Planned Return Date Time:</td>
									<td><input readonly="" type="text" name="txtreturndatetime" id="txtreturndatetime" style="width:80px;" class="date-pick dp-applied" value="<? echo fn_cDateMySql($rowRESERVATION['planned_return_day_time'], 1);?>" />
										&nbsp;
										<select name="drptime2" style="width:100px;">
											<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
												<option value="<? echo $arrTIME[$iCounter][0];?>"<? if($iCounter==substr($rowRESERVATION['planned_return_day_time'],11, strlen($rowRESERVATION['planned_return_day_time']))) echo "selected";?>><? echo $arrTIME[$iCounter][1];?></option>
											<?	}?>
										</select>
									</td>
								</tr>
								
								<tr>
									<td class="label" valign="top">Destination:</td>
									<td><textarea name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;" onKeyDown="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);" onKeyUp="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);"><? echo stripslashes($rowRESERVATION['destination']);?></textarea>
												&nbsp;<input readonly type="text" name="txtLength" value="100" style="width:25px;"></td>
								</tr>
								<tr>
												
									<td class="label">Vehicle Restriction:</td>
									<td>
									
									<input readonly="" type="text" name="txtvrestriction" id="txtvrestriction" style="width:200px;" value="<? echo $rowRESERVATION['restriction'];?>" />							
									</td>
								</tr>
								
								
								<?	if($_SESSION["User_Group"]==$iGROUP_TM && $rowRESERVATION['cancelled_by_driver']==1)	{?>
								
								
								<tr>
									<td class="label" valign="top">Cancelled By Driver:</td>
									<td class="label"><? if($rowRESERVATION['cancelled_by_driver']==1) echo "Yes"; else echo "No";?> </td>
								</tr>
								<tr>
									<td class="label" valign="top">Cancelled Date:</td>
									<td class="label"><? echo fn_cDateMySql($rowRESERVATION['driver_cancelled_time'],2); ?> </td>
								</tr>
								<?	}?>
								
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								
								<? if($_SESSION["User_Group"]==$iGROUP_TC || $_SESSION["User_Group"]==$iGROUP_TM){?>
								<tr>
									
									<td colspan="2" align="center">
										<input type="button" name="btnDELETE" value="DELETE AND EMAIL DRIVER" onClick="fn_DELETE_TRIP(this.form);" class="Button" style="width:170x;" />
										<input type="button" name="btnUPDATE" value="MAKE CHANGE AND EMAIL DRIVER" onClick="fn_UPDATE_TRIP(this.form);" class="Button" style="width:205x;" />
									</td>
								</tr>
								<?	}?>
								
								
								
								<?Php }?>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td colspan="2" align="center"><input type="button" name="btnBACK" value="RETURN TO LIST" onClick="location.href='list_pending_report.php'" class="Button" style="width:170x;" /></td></tr>
							</table>
							
						</form>
 </div>
</body>
</html>
