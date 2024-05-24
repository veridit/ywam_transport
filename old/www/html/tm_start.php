<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');

	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sSlip_Due_Message		=	"";		$sSlip_Close_Message		=	"";		$sActive_Message		=	"";
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Trips are overdue for closure when highlighted yellow<li class='bold-font'>Reservations made for today after the time this report was made, will not appear here, please monitor the 'List Trips' page for 'Today' and 'Tomorrow' for that information", "C_SUCCESS");
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>TM Start Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}

function fn_DELETE_LOG(iDEPTID){
	document.frm1.logid.value=iDEPTID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}

function fn_DELETE_LOG_LIST(){
	document.frm1.action.value='dellist';
	document.frm1.submit();
}
function fn_PRINT(){
	var url="printtmstart.php?a=print";
	var myWindow	=	window.open(url,"_blank","height=600, width=630, resizable=no, scrollbars=yes");
}
</script>
<style type="text/css">
	.yellow{
		background-color:yellow;
	}
</style>
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
								   				<h1 style="margin-bottom: 0px;">TM START REPORT</h1>
								  			</td>
								 		</tr>
									</table>
                   				</td>
                  			</tr>
                 		</table>
                	</td>
					<td></td>
               	</tr>
				
               	<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
                	<td width="949" class="TextObject" align="center">
						<form name="frm1" action="" method="post">
							<input type="hidden" name="action" value=""	/>
						
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td width="100%">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr><td width="90%"><h1 style="font-style:italic;">Trip Slips to be Closed</h1></td><td><input type="button" name="btnGO" value=" PRINT REPORT " class="Button" onClick="fn_PRINT();" /></td></tr>
										</table>
									</td>
								</tr>
								<?
								
								$iCURRENT_DAY_NUMBER	=	0;
								$iCURRENT_DAY_NUMBER	=	 date('N', strtotime(date('Y-m-d')));
								$sCURRENT_DATE			=	date('Y-m-d');
								//print("DAY===".$iCURRENT_DAY_NUMBER);
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
								"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 10)";
								
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
								"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 10)";
								
								$sSQL	.=	") o_r WHERE ((TIME_TO_SEC(TIMEDIFF('".$sCURRENT_DATE." 00:01:00', o_r.planned_return_day_time))/3600) BETWEEN ".$sCRITERIA_SQL.")";
								
								
								
								//over due
								
								
						
								
								$sSQL	.=	") q ORDER BY assigned_driver";
								
								
								/*please don't check the TM start report, its having a bug still,

please let me know exact ALGORITHM for slips to be closed


first we need to close all reservations which are having Departure Date Time in last 5 days
2nd-the ones which are having Return Date Time is Less than or Equal to 47 hrs from Current Date Time will be considered as Normal
3rd - the ones which are having Return Date Time difference from Current Date Time is between 48-95 hrs will be considered as Overdue for weekdays
4th-the ones which are having Return Date Time difference from Current Date Time is between 48-95 hrs will be considered as Overdue for weekends


thanks*/
								
								
								
								
								//"AND ((TIME_TO_SEC(TIMEDIFF(r.planned_depart_day_time, '".$sCURRENT_DATE." 00:01:00'))/3600) BETWEEN 1 AND 36) ORDER BY asgnd_driver.l_name";
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
												
											</tr>
											<?	while($rowSLIP	=	mysql_fetch_array($rsSLIPS_DUE)){						?>
											<tr <? if($rowSLIP['tag']=='overdue') echo "class=yellow";?>>
												<td class="coldata leftbox"><? echo $rowSLIP['res_id'];?></td>
												<td class="coldata"><? echo fn_cDateMySql($rowSLIP['planned_depart_day_time'], 2);?></td>
												<td class="coldata"><? echo fn_cDateMySql($rowSLIP['planned_return_day_time'], 2);?></td>
												<td class="coldata"><? echo $rowSLIP['vehicle_no'];?></td>
												<td class="coldata"><? echo $rowSLIP['assigned_driver'];?></td>
												
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
												//if($iCURRENT_DAY_NUMBER==6)	$sCRITERIA_SQL	=	"0 AND 3"; else $sCRITERIA_SQL	=	"0 AND 1";
												//SELECT TIMEDIFF(planned_depart_day_time, planned_return_day_time) AS HOURS FROM tbl_reservations
																				
												/*$sSQL	=	"SELECT r.res_id, planned_depart_day_time, planned_return_day_time, vehicle_no, ".
												"CONCAT(resv.f_name,' ', resv.l_name) AS resvd_by, CONCAT(asgnd.f_name,' ', asgnd.l_name) AS assigned_to ".
												"FROM tbl_reservations r ".
												"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
												"INNER JOIN tbl_user resv ON r.user_id = resv.user_id ".
												"INNER JOIN tbl_user asgnd ON r.assigned_driver = asgnd.user_id ".
												"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
												"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
												"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
												"AND key_no = '' AND card_no = '' ".
												"AND DATEDIFF(r.planned_depart_day_time, CURDATE()) BETWEEN ".$sCRITERIA_SQL;*/
												
												//if($iCURRENT_DAY_NUMBER==6)	$sCRITERIA_SQL	=	"'84:00:00' AND DATEDIFF(r.planned_depart_day_time, CURDATE()) BETWEEN 0 AND 3)"; else $sCRITERIA_SQL	=	"'36:00:00' AND DATEDIFF(r.planned_depart_day_time, CURDATE()) BETWEEN 0 AND 1)";
												if($iCURRENT_DAY_NUMBER==6)	$sCRITERIA_SQL	=	"0 AND 84"; else $sCRITERIA_SQL	=	"0 AND 36";
												
												
												$sSQL	=	"SELECT r.res_id, planned_depart_day_time, planned_return_day_time, vehicle_no, ".
												"CONCAT(resv.f_name,' ', resv.l_name) AS resvd_by, CONCAT(asgnd.f_name,' ', asgnd.l_name) AS assigned_to, v.vehicle_no ".
												"FROM tbl_reservations r ".
												"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
												"INNER JOIN tbl_user resv ON r.user_id = resv.user_id ".
												"INNER JOIN tbl_user asgnd ON r.assigned_driver = asgnd.user_id ".
												"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
												"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
												"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
												"AND (key_no = '' OR card_no = '' OR key_no IS NULL OR card_no IS NULL) ".
												"AND ((TIME_TO_SEC(TIMEDIFF(r.planned_depart_day_time, '".$sCURRENT_DATE." 00:01:00'))/3600) BETWEEN ".$sCRITERIA_SQL.")";
												
												//SELECT r.res_id, planned_depart_day_time, planned_return_day_time, vehicle_no, CONCAT(resv.f_name,' ', resv.l_name) AS resvd_by, TIME_TO_SEC(TIMEDIFF('2012-10-28 00:01:00', r.planned_depart_day_time))/3600 AS hours, CONCAT(asgnd.f_name,' ', asgnd.l_name) AS assigned_to FROM tbl_reservations r INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id INNER JOIN tbl_user resv ON r.user_id = resv.user_id INNER JOIN tbl_user asgnd ON r.assigned_driver = asgnd.user_id LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND key_no = '' AND card_no = '' AND ((TIME_TO_SEC(TIMEDIFF(r.planned_depart_day_time, '2012-10-28 00:01:00'))/3600) BETWEEN 0 AND 36)
												//SELECT r.res_id, planned_depart_day_time, planned_return_day_time, vehicle_no, CONCAT(resv.f_name,' ', resv.l_name) AS resvd_by, TIME_TO_SEC(TIMEDIFF(planned_depart_day_time, '2012-10-28 00:01:00'))/3600 AS hours, CONCAT(asgnd.f_name,' ', asgnd.l_name) AS assigned_to FROM tbl_reservations r INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id INNER JOIN tbl_user resv ON r.user_id = resv.user_id INNER JOIN tbl_user asgnd ON r.assigned_driver = asgnd.user_id  WHERE ((TIME_TO_SEC(TIMEDIFF(planned_depart_day_time, '2012-10-28 00:01:00'))/3600) BETWEEN 0 AND 36)
												
												//print($sSQL);
												$rsSLIPS_TO_CLOSE		=	mysql_query($sSQL) or die(mysql_error());
												$iRECORD_COUNT		=	mysql_num_rows($rsSLIPS_TO_CLOSE);
												//print("RECORDS==".$iRECORD_COUNT);
												if($iRECORD_COUNT<=0){		$sSlip_Close_Message		=	fn_Print_MSG_BOX("<li>no slips to make", "C_ERROR");}
												
												if($iRECORD_COUNT>0){
													$sSlip_Close_Message	=	"<table cellpadding='0' cellspacing='0' border='0' width='100%' align='center'>";
													$sSlip_Close_Message	.=	"<tr>";
													$sSlip_Close_Message	.=	"<td width='100' class='colhead'>Resv #</td>";
													$sSlip_Close_Message	.=	"<td width='170' class='colhead'>Deprtr Date-Time</td>";
													$sSlip_Close_Message	.=	"<td width='130' class='colhead'>Return Date-Time</td>";
													$sSlip_Close_Message	.=	"<td width='140' class='colhead'>Reserved by</td>";
													$sSlip_Close_Message	.=	"<td width='140' class='colhead'>Assigned Driver</td>";
													$sSlip_Close_Message	.=	"<td width='30' class='colhead'>V #</td>";
													$sSlip_Close_Message	.=	"</tr>";
													while($rowSLIP	=	mysql_fetch_array($rsSLIPS_TO_CLOSE)){
														$sSlip_Close_Message	.=	"<tr>";
														$sSlip_Close_Message	.=	"<td class='coldata leftbox'>".$rowSLIP['res_id']."</td>";
														$sSlip_Close_Message	.=	"<td class='coldata'>".fn_cDateMySql($rowSLIP['planned_depart_day_time'], 2)."</td>";
														$sSlip_Close_Message	.=	"<td class='coldata'>".fn_cDateMySql($rowSLIP['planned_return_day_time'], 2)."</td>";
														$sSlip_Close_Message	.=	"<td class='coldata'>".$rowSLIP['resvd_by']."</td>";
														$sSlip_Close_Message	.=	"<td class='coldata'>".$rowSLIP['assigned_to']."</td>";
														$sSlip_Close_Message	.=	"<td class='coldata'>".$rowSLIP['vehicle_no']."</td>";
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
 