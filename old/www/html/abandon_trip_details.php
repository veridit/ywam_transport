<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iTRIP_ID		=	0;
	$iRECORD_COUNT	=	0;	
	
	if(isset($_REQUEST["resid"]))	$iTRIP_ID		=	$_REQUEST["resid"];
	
	
	
$sSQL	=	"SELECT r.vehicle_id, vehicle_no, tbl_vehicles.restriction, passenger_cap, ".
	"CONCAT(rsvrd_by.f_name, ' ', rsvrd_by.l_name) AS rsvrd_by_name, ".	
	"CONCAT(driver.f_name, ' ', driver.l_name) AS driver_name, ".	
	"planned_passngr_no, planned_depart_day_time, planned_return_day_time, overnight, childseat, ".
	"destination, r.key_no, r.card_no, ".
	"home_dept.dept_name AS home_dept_name, charge_dept.dept_name AS charge_dept_name, ".
	"r.billing_dept, ".
	"a.abandon_date, a.notes, CASE WHEN a.calculate_fine = 1 THEN mile_charges*25 ELSE 'NOT FINED' END AS fine_charges, ".
	"CASE WHEN a.user_id IS NULL THEN 'SYSTEM' ELSE CONCAT(abandon_by.f_name, ' ', abandon_by.l_name) END AS abandon_by ".
	"FROM tbl_reservations r ".
	"INNER JOIN tbl_vehicles ON r.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"INNER JOIN tbl_user rsvrd_by ON r.user_id = rsvrd_by.user_id ".
	"INNER JOIN tbl_user driver ON r.assigned_driver = driver.user_id ".
	"INNER JOIN tbl_departments home_dept ON rsvrd_by.dept_id = home_dept.dept_id ".
	"INNER JOIN tbl_departments charge_dept ON r.billing_dept = charge_dept.dept_id ".
	"INNER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
	"LEFT OUTER JOIN tbl_user abandon_by ON a.user_id = abandon_by.user_id ".
	"WHERE r.res_id = ".$iTRIP_ID;

$rsTRIP	=		mysql_query($sSQL) or die(mysql_error());
$iRECORD_COUNT	=mysql_num_rows($rsTRIP);	
if($iRECORD_COUNT>0){
	$rowTRIP	=	mysql_fetch_array($rsTRIP);

}else{
	$sMessage		=	fn_Print_MSG_BOX("<li>trip details not found", "C_ERROR");
}mysql_free_result($rsTRIP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Abandon Trip Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../html/style.css">

</head>
<body style="margin: 0px; background:none;">
<div align="center">
	
						<form name="frm1" action="abandon_trip_details.php" method="post">
							<input type="hidden" name="action" value="edittrip"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?Php	if($iRECORD_COUNT>0){?>
								<tr>
									<td width="200" class="label">Resv. No:</td>
									<td width="400"><input readonly="" type="text" name="txtvresno" value="<? echo $iTRIP_ID;?>" style="width:50px;" /></td>
								</tr>
								
								<tr>
									<td class="label">Rsvrd By:</td>
									<td><input readonly="" type="text" name="txtrsvrdby" value="<? echo $rowTRIP['rsvrd_by_name'];?>" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Assgnd Driver:</td>
									<td><input readonly="" type="text" name="txtdriver" value="<? echo $rowTRIP['driver_name'];?>" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Vehicle:</td>
									<td><input readonly="" type="text" name="txtvehicleno" value="<? echo $rowTRIP['vehicle_no'];?>" style="width:50px;" /></td>
								</tr>
								<tr>
									<td class="label">No of Psngrs Planned:</td>
									<td><input readonly="" type="text" name="txtpassenger" value="<? echo $rowTRIP['planned_passngr_no'];?>" maxlength="2" style="width:50px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Home Dept.:</td>
									<td><input readonly="" type="text" name="txthomedept" value="<? echo $rowTRIP['home_dept_name'];?>" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Charge Dept.:</td>
									<td><input readonly="" type="text" name="txthomedept" value="<? echo $rowTRIP['charge_dept_name'];?>" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Key No:</td>
									<td><input readonly="" type="text" name="txtkey" value="<? echo $rowTRIP['key_no'];?>" maxlength="4" style="width:110px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Card No:</td>
									<td><input readonly="" type="text" name="txtcard" value="<? echo $rowTRIP['card_no'];?>" maxlength="8" style="width:110px; text-align:right;" /></td>
								</tr>
								
								<tr>
									<td class="label">Planned Departure Date Time:</td>
									<td><input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:110px;"  value="<? echo fn_cDateMySql($rowTRIP['planned_depart_day_time'], 2);?>" /></td>
								</tr>
								<tr>
									<td class="label">Planned Return Date Time:</td>
									<td><input readonly="" type="text" name="txtreturndatetime" id="txtreturndatetime" style="width:110px;" value="<? echo fn_cDateMySql($rowTRIP['planned_return_day_time'], 2);?>" /></td>
								</tr>
								
								<tr>
									<td class="label" valign="top">Destination:</td>
									<td><textarea readonly name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;" ><? echo stripslashes($rowTRIP['destination']);?></textarea></td>
								</tr>								
								<tr>
									<td class="label">Abandoned On:</td>
									<td><input readonly="" type="text" name="txtabandondatetime" id="txtabandondatetime" style="width:110px;" value="<? echo fn_cDateMySql($rowTRIP['abandon_date'], 2);?>" /></td>
								</tr>
								<tr>
									<td class="label">Abandoned By:</td>
									<td><input readonly="" type="text" name="txtabandonedby" value="<? echo $rowTRIP['abandon_by'];?>" style="width:200px;" /></td>
								</tr>								
								<tr>
									<td class="label" valign="top">TM Notes:</td>
									<td><textarea readonly name="txtnotes" id="txtnotes" cols="20" rows="3" style="width:200px;"  ><? echo stripslashes($rowTRIP['notes']);?></textarea></td>
								</tr>
								<tr>
									<td class="label">Fine:</td>
									<td><input readonly="" type="text" name="txtfine" value="<? if($rowTRIP['fine_charges']!="NOT FINED") echo fn_NUMBER_FORMAT($rowTRIP['fine_charges'], "1,234.56"); else echo $rowTRIP['fine_charges'];?>" style="width:110px;" /></td>
								</tr>
					
								<?Php	}	?>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td colspan="2" align="center"><input type="button" name="btnBACK" value="RETURN TO LIST" onClick="location.href='list_abandon_trips.php'" class="Button" style="width:170x;" /></td></tr>
							</table>
						</form>
                
 </div>
</body>
</html>
