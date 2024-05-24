<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iRESERVATION_ID	=	0;

	$sCRITERIA_SQL		=	"";

	$bCANCELLED_BY_DRIVER			=	false;
	if(isset($_REQUEST["resid"]))	$iRESERVATION_ID		=	$_REQUEST["resid"];
	
	
	$sSQL	=	"SELECT tbl_user.user_id, tbl_reservations.vehicle_id, vehicle_no, tbl_vehicles.restriction, passenger_cap, CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS driver_name, ".	
	"planned_passngr_no, planned_depart_day_time, planned_return_day_time, overnight, childseat, ".
	"destination, coord_approval, reservation_cancelled, cancelled_by_driver, ".
	"tbl_reservations.key_no, tbl_reservations.card_no, ".
	"tbl_departments.dept_name, billing_dept.dept_name AS billing_dept, CONCAT(assgnd_drvr.f_name, ' ', assgnd_drvr.l_name) AS assigned_driver, ".
	"tbl_reservations.driver_cancelled_time ".
	"FROM tbl_reservations ".
	"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_user assgnd_drvr ON tbl_reservations.user_id = assgnd_drvr.user_id ".
	"INNER JOIN tbl_departments ON tbl_user.dept_id = tbl_departments.dept_id ".
	"INNER JOIN tbl_departments billing_dept ON tbl_reservations.billing_dept = billing_dept.dept_id ".
	"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID." AND cancelled_by_driver = 1";
	
	$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=mysql_num_rows($rsRESERVATION);	
	if($iRECORD_COUNT>0){
		$rowRESERVATION	=	mysql_fetch_array($rsRESERVATION);
		$bCANCELLED_BY_DRIVER		=	true;
	}else{
		$sMessage		=	fn_Print_MSG_BOX("<li>trip is not been cancelled by driver!", "C_ERROR");
		
	}mysql_free_result($rsRESERVATION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Driver Cancelled Trip</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../html/sub_style.css">
</head>
<body>
<div align="center">
						<form name="frm1" action="view_driver_cancelled_trip.php" method="post">
							
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?Php if($bCANCELLED_BY_DRIVER==true){?>
								<tr>
									<td width="150" class="label">Resv. No:</td>
									<td width="300"><input readonly="" type="text" name="txtvresno" value="<? echo $iRESERVATION_ID;?>" style="width:150px;" /></td>
								</tr>
								
								<tr>
									<td class="label">Assgnd Driver:</td>
									<td><input readonly="" type="text" name="txtassgnddriver" value="<? echo $rowRESERVATION['assigned_driver'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Vehicle:</td>
									<td><input readonly="" type="text" name="txtvehicleno" value="<? echo $rowRESERVATION['vehicle_no'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">No of Psngrs Planned:</td>
									<td><input readonly="" type="text" name="txtpassenger" value="<? echo $rowRESERVATION['planned_passngr_no'];?>" maxlength="2" style="width:50px; text-align:right;"  /></td>
								</tr>
								
								<tr>
									<td class="label">Rsvrd by:</td>
									<td><input readonly="" type="text" name="txtdrivername" value="<? echo $rowRESERVATION['driver_name'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Home Dept.:</td>
									<td><input readonly="" type="text" name="txthomedept" value="<? echo $rowRESERVATION['dept_name'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Charge Dept.:</td>
									<td><input readonly="" type="text" name="txtbilldept" value="<? echo $rowRESERVATION['billing_dept'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Key No:</td>
									<td><input readonly="" type="text" name="txtkey" value="<? echo $rowRESERVATION['key_no'];?>" maxlength="4" style="width:100px; text-align:right;" /></td>
								</tr>
								
								<tr>
									<td class="label">Card No:</td>
									<td><input readonly="" type="text" name="txtcard" value="<? echo $rowRESERVATION['card_no'];?>" maxlength="8" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Planned Departure Date Time:</td>
									<td>
										<input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:130px;" value="<? echo fn_cDateMySql($rowRESERVATION['planned_depart_day_time'], 2);?>" />
									</td>
								</tr>
								<tr>
									<td class="label">Planned Return Date Time:</td>
									<td><input readonly="" type="text" name="txtreturndatetime" id="txtreturndatetime" style="width:130px;" value="<? echo fn_cDateMySql($rowRESERVATION['planned_return_day_time'], 2);?>" /></td>
								</tr>
								
								<tr>
									<td class="label" valign="top">Destination:</td>
									<td><textarea readonly name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;"><? echo stripslashes($rowRESERVATION['destination']);?></textarea></td>
								</tr>
								<tr>
												
									<td class="label">Vehicle Restriction:</td>
									<td>
									
									<input readonly="" type="text" name="txtvrestriction" id="txtvrestriction" style="width:200px;" value="<? echo $rowRESERVATION['restriction'];?>" />							
									</td>
								</tr>
							
								<tr>
									<td class="label" valign="top">Cancelled Date:</td>
									<td class="label"><? echo fn_cDateMySql($rowRESERVATION['driver_cancelled_time'],2); ?> </td>
								</tr>
								
								<?Php }?>
							</table>
							
						</form>
                	
 </div>
</body>
</html>