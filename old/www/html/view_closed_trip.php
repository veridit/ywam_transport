<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iTRIP_ID		=	0;
	$iRECORD_COUNT	=	0;	
	
	if(isset($_REQUEST["resid"]))	$iTRIP_ID		=	$_REQUEST["resid"];
	
	
	
$sSQL	=	"SELECT tbl_reservations.vehicle_id, vehicle_no, tbl_vehicles.restriction, passenger_cap, ".
	"CONCAT(rsvrd_by.f_name, ' ', rsvrd_by.l_name) AS rsvrd_by_name, ".	
	"CONCAT(driver.f_name, ' ', driver.l_name) AS driver_name, ".	
	"planned_passngr_no, planned_depart_day_time, planned_return_day_time, overnight, childseat, ".
	"destination, coord_approval, reservation_cancelled, cancelled_by_driver, ".
	"tbl_reservations.key_no, tbl_reservations.card_no, ".
	"home_dept.dept_name AS home_dept_name, charge_dept.dept_name AS charge_dept_name, ".
	"tbl_reservations.billing_dept, ".
	"CASE WHEN tbl_reservations.driver_cancelled_time IS NULL THEN '' ELSE tbl_reservations.driver_cancelled_time END AS driver_cancelled_time, ".
	"CASE WHEN tbl_user_comments.trip_id IS NULL THEN '' ELSE tbl_user_comments.comments END AS tm_notes_about_driver, ".
	"tbl_trip_details.end_gas_percent AS end_gas, ".
	"tbl_trip_details.begin_mileage AS begin_miles, ".
	"tbl_trip_details.end_mileage AS end_miles, ".
	"tbl_trip_details.problem, ".
	"tbl_trip_details.desc_problem ".
	"FROM tbl_reservations ".
	"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"INNER JOIN tbl_user rsvrd_by ON tbl_reservations.user_id = rsvrd_by.user_id ".
	"INNER JOIN tbl_user driver ON tbl_reservations.assigned_driver = driver.user_id ".
	"INNER JOIN tbl_departments home_dept ON rsvrd_by.dept_id = home_dept.dept_id ".
	"INNER JOIN tbl_departments charge_dept ON tbl_reservations.billing_dept = charge_dept.dept_id ".
	"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_user_comments ON tbl_trip_details.res_id = tbl_user_comments.trip_id ".
	"WHERE tbl_reservations.res_id = ".$iTRIP_ID;

$rsTRIP	=		mysql_query($sSQL) or die(mysql_error());
$iRECORD_COUNT	=mysql_num_rows($rsTRIP);	
if($iRECORD_COUNT>0){
	$rowTRIP	=	mysql_fetch_array($rsTRIP);

}else{
	$sMessage		=	fn_Print_MSG_BOX("<li>trip is been marked as abandon, here you can only view details of normaly closed trip", "C_ERROR");
}mysql_free_result($rsTRIP);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Closed Trip Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
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
								   				<h1 style="margin-bottom: 0px;">CLOSED TRIP DETAILS</h1>
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
						<form name="frm1" action="view_closed_trip.php" method="post">
							<input type="hidden" name="action" value="edittrip"	/>
							<!--<input type="hidden" name="tripid" value="<?=$iTRIP_ID?>"	/>-->
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
									<td class="label">Childseat:</td>
									<td><input readonly="" type="checkbox" name="chkchildseat" value="1" <? if($rowTRIP['childseat']==1) echo "checked";	?> /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Destination:</td>
									<td><textarea readonly name="txtdestination" id="txtdestination" cols="20" rows="3" style="width:200px;" ><? echo stripslashes($rowTRIP['destination']);?></textarea></td>
								</tr>
								<tr>	
									<td class="label">Vehicle Specifics:</td>
									<td><textarea readonly name="txtvrestriction" id="txtvrestriction" cols="20" rows="3" style="width:200px;"  ><? echo stripslashes($rowTRIP['restriction']);?></textarea></td>
								</tr>
								
								<tr>
									<td class="label">Beginning Mileage:</td>
									<td><input readonly="" type="text" name="txtbeginmileage" value="<? echo $rowTRIP['begin_miles']; ?>" maxlength="7" style="width:110px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Ending Mileage:</td>
									<td><input readonly="" type="text" name="txtendmileage" value="<? echo $rowTRIP['end_miles']; ?>" maxlength="7" style="width:110px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">End Gas Percent:</td>
									<td><input readonly="" type="text" name="txtendgas" value="<? echo $rowTRIP['end_gas']; ?>" maxlength="7" style="width:50px; text-align:right;"  /></td>
								</tr>
								
								<tr>
									<td class="label">Safety Problem:</td>
									<td><input readonly="" type="checkbox" name="chkproblem" value="1" <? if($rowTRIP['problem']==1) echo "checked";	?> /><span class="Highlight" style="font-weight:bold;">TM choice based on Driver notes</span></td>
								</tr>
								<tr>
									<td class="label" valign="top">Driver notes about vehicle Only:</td>
									<td><textarea readonly name="txtproblem" id="txtproblem" cols="20" rows="3" style="width:200px;"  ><? echo stripslashes($rowTRIP['desc_problem']);?></textarea></td>
								</tr>
								<tr>
									<td class="label" valign="top">TM notes about Driver:</td>
									<td><textarea readonly name="txtTMNotes" id="txtTMNotes" cols="20" rows="3" style="width:200px;"  ><? echo stripslashes($rowTRIP['tm_notes_about_driver']);?></textarea></td>
								</tr>
								<?Php	}	?>
								
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
	<div id="contactArea" style="padding-left:10px;"></div>
	<br /><br />
	<div style="text-align:center; width:100%; margin:0 auto;">
		<input type="button" name="btnclose" value="OK" class="Button" id="popupClose" style="width:100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="btnclose" value="CANCEL" class="Button" id="popupCancel" style="width:100px;" />
	</div>
	<br /><br />
</div>
<div id="backgroundPopup"></div>