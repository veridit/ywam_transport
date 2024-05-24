<?
	include('inc_connection.php');
	include('inc_function.php');
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sSlip_Due_Message		=	"";		$sSlip_Close_Message		=	"";		$sActive_Message		=	"";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>TM Start Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/style.css">
</head>
<body style="margin: 0px; background-color:#fff;">
				
<table cellpadding="0" cellspacing="5" border="0" width="600" align="center">
	
	<tr>
		<td width="100%">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td width="450"><h1 style="font-style:italic;">Trip Slips to be Closed</h1></td><td class="label" align="right" width="150">Print Date:&nbsp;<?Php echo date('m/d/Y');?></td></tr>
			</table>
		</td>
	</tr>
	<?	
	$iCURRENT_DAY_NUMBER	=	0;
	$iCURRENT_DAY_NUMBER	=	 date('N', strtotime(date('Y-m-d')));
	$sCURRENT_DATE			=	date('Y-m-d');
		
	if($iCURRENT_DAY_NUMBER==1)	$sCRITERIA_SQL	=	"96 AND 150"; else $sCRITERIA_SQL	=	"48 AND 95";
									
	$sSQL	=	"SELECT  q.res_id, q.planned_depart_day_time, q.planned_return_day_time, q.vehicle_no, q.assigned_driver, q.tag FROM (";
								
								
								$sSQL	.=	"SELECT nr.res_id, nr.planned_depart_day_time, nr.planned_return_day_time, nr.vehicle_no, nr.assigned_driver, ' ' AS tag FROM (";
								
								$sSQL	.=	"SELECT r.res_id, r.planned_depart_day_time, r.planned_return_day_time, v.vehicle_no, CONCAT(asgnd_driver.f_name, ' ', asgnd_driver.l_name) AS assigned_driver ".
								"FROM tbl_reservations r ".
								"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
								"INNER JOIN tbl_user asgnd_driver ON r.assigned_driver = asgnd_driver.user_id ".
								"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
								"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
								"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
								"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 5)";
								
								$sSQL	.=	")  nr WHERE ((TIME_TO_SEC(TIMEDIFF('".$sCURRENT_DATE." 00:01:00', nr.planned_return_day_time))/3600) BETWEEN 0 AND 47)";
								
								$sSQL	.=	" UNION ALL ";
								
								$sSQL	.=	"SELECT o_r.res_id, o_r.planned_depart_day_time, o_r.planned_return_day_time, o_r.vehicle_no, o_r.assigned_driver, 'overdue' AS tag FROM (";
								
								$sSQL	.=	"SELECT r.res_id, r.planned_depart_day_time, r.planned_return_day_time, v.vehicle_no, CONCAT(asgnd_driver.f_name, ' ', asgnd_driver.l_name) AS assigned_driver ".
								"FROM tbl_reservations r ".
								"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
								"INNER JOIN tbl_user asgnd_driver ON r.assigned_driver = asgnd_driver.user_id ".
								"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
								"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
								"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
								"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 5)";
								
								$sSQL	.=	") o_r WHERE ((TIME_TO_SEC(TIMEDIFF('".$sCURRENT_DATE." 00:01:00', o_r.planned_return_day_time))/3600) BETWEEN ".$sCRITERIA_SQL.")";
								
								
								
								//over due
								
								
						
								
								$sSQL	.=	") q ORDER BY assigned_driver";
	//print($sSQL);
	$rsSLIPS_DUE		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT		=	mysql_num_rows($rsSLIPS_DUE);
	if($iRECORD_COUNT<=0){		$sSlip_Due_Message		=	fn_Print_MSG_BOX("<li>no slips to be made", "C_ERROR");							}
	?>
	<tr><td><div id="slip_due_msg"><?Php echo $sSlip_Due_Message?></div></td></tr>
	<?
	if($iRECORD_COUNT>0){	?>

	<tr>
		<td>
			<div id="slip_due">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
				
				<tr>
				<td width="100" class="colhead">Resv #</td>
					<td width="130" class="colhead">Deprtr Date-Time</td>
					<td width="130" class="colhead">Retrn Date-Time</td>
					<td width="100" class="colhead">Vehicle No</td>
					<td width="140" class="colhead">Assgnd Drvr</td>
					<td width="70" class="colhead">Status</td>
				</tr>
				<?	while($rowSLIP	=	mysql_fetch_array($rsSLIPS_DUE)){						?>
				<tr <?Php if($rowSLIP['tag']=="overdue") echo "style='background-color:#b4b4b4;'";?>>
					<td class="coldata leftbox"><? echo $rowSLIP['res_id'];?></td>
					<td class="coldata"><? echo fn_cDateMySql($rowSLIP['planned_depart_day_time'], 2);?></td>
					<td class="coldata"><? echo fn_cDateMySql($rowSLIP['planned_return_day_time'], 2);?></td>
					<td class="coldata"><? echo $rowSLIP['vehicle_no'];?></td>
					<td class="coldata"><? echo $rowSLIP['assigned_driver'];?></td>
					<td class="coldata"><? echo $rowSLIP['tag'];?></td>
				</tr>
				<?	}		?>
			</table>
			</div>
		</td>
	</tr>
	<?	}mysql_free_result($rsSLIPS_DUE);	?>
	
	<tr><td><hr /></td></tr>
	
	<tr><td width="100%"><h1 style="font-style:italic;">Slips to be Made</h1></td></tr>
	<tr>
		<td>
			<div id="slip_close">
				<?
					if($iCURRENT_DAY_NUMBER==6)	$sCRITERIA_SQL	=	"0 AND 84"; else $sCRITERIA_SQL	=	"0 AND 36";
					
					$sSQL	=	"SELECT r.res_id, planned_depart_day_time, planned_return_day_time, vehicle_no, ".
					"CONCAT(resv.f_name,' ', resv.l_name) AS resvd_by, CONCAT(asgnd.f_name,' ', asgnd.l_name) AS assigned_to ".
					"FROM tbl_reservations r ".
					"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
					"INNER JOIN tbl_user resv ON r.user_id = resv.user_id ".
					"INNER JOIN tbl_user asgnd ON r.assigned_driver = asgnd.user_id ".
					"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
					"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
					"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
					"AND key_no = '' AND card_no = '' ".
					"AND ((TIME_TO_SEC(TIMEDIFF(r.planned_depart_day_time, '".$sCURRENT_DATE." 00:01:00'))/3600) BETWEEN ".$sCRITERIA_SQL.")";
					//print($sSQL);
					$rsSLIPS_TO_CLOSE		=	mysql_query($sSQL) or die(mysql_error());
					$iRECORD_COUNT		=	mysql_num_rows($rsSLIPS_TO_CLOSE);
					//print("RECORDS==".$iRECORD_COUNT);
					if($iRECORD_COUNT<=0){		$sSlip_Close_Message		=	fn_Print_MSG_BOX("<li>no slips to make", "C_ERROR");}
					
					if($iRECORD_COUNT>0){
						$sSlip_Close_Message	=	"<table cellpadding='0' cellspacing='0' border='0' width='100%' align='center'>";
						$sSlip_Close_Message	.=	"<tr>";
						$sSlip_Close_Message	.=	"<td width='100' class='colhead'>Resv #</td>";
						$sSlip_Close_Message	.=	"<td width='130' class='colhead'>Deprtr Date-Time</td>";
						$sSlip_Close_Message	.=	"<td width='130' class='colhead'>Return Date-Time</td>";
						$sSlip_Close_Message	.=	"<td width='140' class='colhead'>Reserved by</td>";
						$sSlip_Close_Message	.=	"<td width='140' class='colhead'>Assigned Driver</td>";
						$sSlip_Close_Message	.=	"</tr>";
						while($rowSLIP	=	mysql_fetch_array($rsSLIPS_TO_CLOSE)){
							$sSlip_Close_Message	.=	"<tr>";
							$sSlip_Close_Message	.=	"<td class='coldata leftbox'>".$rowSLIP['res_id']."</td>";
							$sSlip_Close_Message	.=	"<td class='coldata'>".fn_cDateMySql($rowSLIP['planned_depart_day_time'], 2)."</td>";
							$sSlip_Close_Message	.=	"<td class='coldata'>".fn_cDateMySql($rowSLIP['planned_return_day_time'], 2)."</td>";
							$sSlip_Close_Message	.=	"<td class='coldata'>".$rowSLIP['resvd_by']."</td>";
							$sSlip_Close_Message	.=	"<td class='coldata'>".$rowSLIP['assigned_to']."</td>";
							$sSlip_Close_Message	.=	"</tr>";
						}
						$sSlip_Close_Message	.=	"</table>";
					}mysql_free_result($rsSLIPS_TO_CLOSE);
					echo $sSlip_Close_Message;
				?>
			</div>
		</td>
	</tr>
	<tr><td><hr /></td></tr>
	
	<tr><td width="100%"><h1 style="font-style:italic;">Activate New Driver Registrations</h1></td></tr>
	<tr>
		<td>
			<div id="active_users">
					<?

					$sSQL	=	"SELECT u.user_id, CONCAT(f_name, ' ', l_name) AS name, dept_name, u.reg_date FROM tbl_user u ".
					"INNER JOIN tbl_departments ON u.dept_id = tbl_departments.dept_id WHERE u.new_user = 1";
					//print($sSQL);
					$rsACTIVE_USERS		=	mysql_query($sSQL) or die(mysql_error());
					$iRECORD_COUNT		=	mysql_num_rows($rsACTIVE_USERS);
					if($iRECORD_COUNT<=0){		$sActive_Message		=	fn_Print_MSG_BOX("<li>no slips due in found", "C_ERROR");							}
					
					if($iRECORD_COUNT>0){
						$sActive_Message	=	"<table cellpadding='0' cellspacing='0' border='0' width='100%' align='center'>";
						$sActive_Message	.=	"<tr>";
						$sActive_Message	.=	"<td width='100' class='colhead'>Name</td>";
						$sActive_Message	.=	"<td width='170' class='colhead'>Department</td>";
						$sActive_Message	.=	"<td width='140' class='colhead'>Register Date</td>";
						$sActive_Message	.=	"</tr>";
						while($rowACTIVE	=	mysql_fetch_array($rsACTIVE_USERS)){
							$sActive_Message	.=	"<tr>";
							$sActive_Message	.=	"<td class='coldata leftbox'>".$rowACTIVE['name']."</td>";
							$sActive_Message	.=	"<td class='coldata'>".$rowACTIVE['dept_name']."</td>";
							$sActive_Message	.=	"<td class='coldata'>".fn_cDateMySql($rowACTIVE['reg_date'], 1)."</td>";							
							$sActive_Message	.=	"</tr>";
						}
						$sActive_Message	.=	"</table>";
					}mysql_free_result($rsACTIVE_USERS);
					echo $sActive_Message;
					?>
				</div>
			</td>
		</tr>
	
</table>
<script language="javascript">window.print();</script>
</body>
</html>
 