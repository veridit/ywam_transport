<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');
	
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Deleted trips are not included here, for information on those trips use the List Deleted Trips function","C_SUCCESS");
	$sDays			=	"";
	$sDeptNo		=	"";
	$iRES_ID		=	"";
	$iVehicle_ID	=	"";
	$iUSER_ID		=	"";
	$sCriteriaSQL	=	"";
	$iTRIP_ID		=	"";
	$bNO_COST		=	0;
	$iTRIP_LENGTH	=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	
	if(isset($_REQUEST["action"])	&& $_REQUEST["action"]=="search"){
	
			if(isset($_POST["tripid"]) && $_POST["tripid"]!="")	{$iTRIP_ID		=		$_POST["tripid"];}
			
			if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")		{$sDeptNo		=	$_POST["drpdept"];		$sCriteriaSQL	.=	" AND home_dept.dept_id = '".$sDeptNo."'";}
			if(isset($_REQUEST["txtresvno"]) && $_REQUEST["txtresvno"]!="")	{$iRES_ID		=	$_REQUEST["txtresvno"];	$sCriteriaSQL	.=	" AND (tbl_trip_details.res_id = ".$iRES_ID." OR tbl_abandon_trips.res_id = ".$iRES_ID.")";}
			if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")		{$sDays			=	$_POST["drpdays"];		$sCriteriaSQL	.=	" AND (DATEDIFF(CURDATE(), tbl_reservations.planned_return_day_time) <= ".$sDays." AND DATEDIFF(CURDATE(), tbl_reservations.planned_return_day_time) >0) ";}
			if(isset($_POST["drpuser"]) && $_POST["drpuser"]!="")		{$iUSER_ID		=	$_POST["drpuser"];		$sCriteriaSQL	.=	" AND tbl_reservations.user_id = ".$iUSER_ID;}
			if(isset($_POST["drptriplen"]) && $_POST["drptriplen"]!="")	{$iTRIP_LENGTH	=	$_POST["drptriplen"];	$sCriteriaSQL	.=	" AND (tbl_trip_details.end_mileage - tbl_trip_details.begin_mileage) ".$iTRIP_LENGTH;}
			if(isset($_POST["chknocost"]) &&	$_POST["chknocost"]!=""){$bNO_COST		=	1;						$sCriteriaSQL	.=	" AND tbl_reservations.no_cost = 1 ";}
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
				$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
				$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
				$sCriteriaSQL	.=	" AND (DATE(tbl_reservations.planned_return_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
			}
		
			/*if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
				
				$sSQL		=	"DELETE FROM tbl_trip_details WHERE trip_id = ".$iTRIP_ID;
				$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
				
				$sMessage	=	fn_Print_MSG_BOX("<li>trip has been deleted (pending status again)","C_SUCCESS");
			}*/
			
			$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, vehicle_no, tbl_vehicles.restriction, passenger_cap, ".
			"CONCAT(rsvrd_by.f_name, ' ', rsvrd_by.l_name) AS reserved_by_name, ".	
			"CONCAT(driver.f_name, ' ', driver.l_name) AS driver_name, ".
			"planned_passngr_no, planned_depart_day_time, planned_return_day_time, overnight, childseat, ".
			"destination, coord_approval, reservation_cancelled, cancelled_by_driver, ".
			"tbl_reservations.key_no, tbl_reservations.card_no, ".
			"home_dept.dept_name AS home_dept_name, charge_dept.dept_name AS charge_dept_name, ".
			"tbl_reservations.billing_dept, ".
			"tbl_trip_details.end_gas_percent AS end_gas_percent, ".
			"tbl_trip_details.begin_mileage AS begin_mileage, ".
			"tbl_trip_details.end_mileage AS end_mileage, ".
			"tbl_trip_details.end_mileage - tbl_trip_details.begin_mileage AS trip_miles, ".
			"CASE WHEN problem = 1 THEN 'YES' ELSE 'NO' END AS problem, ".
			"CASE WHEN no_cost = 0 THEN 'Std. Charge'  ELSE 'No Cost' END AS no_cost, ".
			"CASE WHEN tbl_trip_details.trip_id IS NULL AND tbl_abandon_trips.res_id IS NOT NULL THEN 'abandoned' ELSE CASE WHEN tbl_trip_details.trip_id IS NOT NULL AND tbl_abandon_trips.res_id IS NULL THEN 'normal' END END AS closure, ".
			"CASE WHEN tbl_trip_details.trip_id IS NULL AND tbl_abandon_trips.res_id IS NOT NULL THEN CASE WHEN tbl_abandon_trips.user_id IS NULL THEN 'By System' ELSE CONCAT(abandon_user.f_name, ' ', abandon_user.l_name) END ".
			"ELSE CASE WHEN tbl_trip_details.trip_id IS NOT NULL AND tbl_abandon_trips.res_id IS NULL THEN CASE WHEN tbl_trip_details.user_id IS NULL THEN 'N/A' ELSE CONCAT(closing_user.f_name, ' ', closing_user.l_name) END END END AS closing_user, ".
			"tbl_trip_details.desc_problem ".
			"FROM tbl_reservations ".
			"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
			"INNER JOIN tbl_user rsvrd_by ON tbl_reservations.user_id = rsvrd_by.user_id ".
			"INNER JOIN tbl_user driver ON tbl_reservations.assigned_driver = driver.user_id ".
			"INNER JOIN tbl_departments home_dept ON rsvrd_by.dept_id = home_dept.dept_id ".
			"INNER JOIN tbl_departments charge_dept ON tbl_reservations.billing_dept = charge_dept.dept_id ".
			"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
			"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
			"LEFT OUTER JOIN tbl_user abandon_user ON tbl_abandon_trips.user_id = abandon_user.user_id ".
			"LEFT OUTER JOIN tbl_user closing_user ON tbl_trip_details.user_id = closing_user.user_id ".
			"WHERE (tbl_trip_details.res_id IS NOT NULL OR tbl_abandon_trips.res_id IS NOT NULL) ".$sCriteriaSQL." ".
			"ORDER BY tbl_reservations.res_id DESC";
					
			
			//print($sSQL);
			$rsTRIP			=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT	=	mysql_num_rows($rsTRIP);
			
			if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no trip found", "C_ERROR");}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>List Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">

function fn_DELETE_TRIP(iTRIP_ID){
	document.frm1.tripid.value=iTRIP_ID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}
</script>
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
</head>
<body>
<div align="center">
							<form name="frm1" action="list_closed_trips.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="tripid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="900" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<!--<td class="label" width="120">Resv No:<br />
													<input type="text" name="txtresvno" value="<? //echo $iRES_ID?>" style="width:120px;" onKeyDown="return validateNumber(event);" />
												</td>-->
												<td class="label" width="180">Department.:<br /><?	fn_DEPARTMENT('drpdept', $sDeptNo, "180", "1", "ALL");	?></td>
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												
												<td rowspan="2">&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px; height:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
											<tr>
												<td class="label" width="150">
													Reserved By:<br />
													<?	//fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");?>
													<?	fn_DISPLAY_USERS('drpuser', $iUSER_ID, "120", "1", "--All--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF);?>
												</td>
												<td class="label" colspan="2">
													Trip Length:<br />
													<select name="drptriplen" size="1" style="width:150px;">
													<option value="" selected>--All Lengths--</option>
													<?
														$arrLEN[0][0]	=	" BETWEEN 100 AND 150 ";		$arrLEN[0][1]	=	"greater than 100";
														$arrLEN[1][0]	=	" BETWEEN 151 AND 200 ";		$arrLEN[1][1]	=	"greater than 150";
														$arrLEN[2][0]	=	" > 200 ";						$arrLEN[2][1]	=	"greater than 200";
														for($iCounter=0;$iCounter<=2;$iCounter++){
													?>
															<option value="<? echo $arrLEN[$iCounter][0];?>" <? if($iTRIP_LENGTH==$arrLEN[$iCounter][0]) echo "selected";?>><? echo $arrLEN[$iCounter][1];?></option>
													<?	}
													?>
													
														
													</select>
												</td>
												<td><br /><input type="checkbox" name="chknocost" value="1" <?Php if($bNO_COST==1) echo "checked";?> /><span class="label">No Cost Trips</span></td>
												
											</tr>
											<tr>
												<td colspan="4">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
																<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_closed_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
																	}?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Resv_No,Depart_Date,Department,Reservd_By,Closure,Trip_Miles,Destination,Closed_By'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','Resv_No,Rsvrd_By,Assgnd_Driver,Vehicle,Planned_Pasngr,Home_Dept,Charge_Dept,Key_No,Card_No,Depart_Date_Time,Return_Date_Time,ChileSeat,Destination,No_Cost,Vehicle_Restriction,Begin_Mileage,End_Mileage,End_Gas_Percent,Problem,Destination'));}
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Resv#.</td>
												<td width="100" class="colhead">Depart. Date</td>
												<td width="100" class="colhead">Dept.</td>
												<td width="90" class="colhead">Rsvrd By</td>
												<td width="60" class="colhead">Closure</td>
												<td width="60" class="colhead">Trip Milg</td>
												<td width="300" class="colhead">Destination</td>
												<td width="10" class="colhead">A</td>
												
												
											</tr>
											<?		$listed	=	0;
													while($rowTRIP	=	mysql_fetch_array($rsTRIP)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowTRIP['res_id'].",".$rowTRIP['planned_depart_day_time'].",".$rowTRIP['home_dept_name'].",".$rowTRIP['reserved_by_name'].",".$rowTRIP['closure'].",".$rowTRIP['trip_miles'].",".stripslashes(str_replace(","," ",$rowTRIP['destination'])).",".$rowTRIP['closing_user']));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowTRIP['res_id'].",".$rowTRIP['reserved_by_name'].",".$rowTRIP['driver_name'].",".$rowTRIP['vehicle_no'].",".$rowTRIP['planned_passngr_no'].",".$rowTRIP["home_dept_name"].",".$rowTRIP["charge_dept_name"].",".$rowTRIP["key_no"].",".$rowTRIP["card_no"].",".$rowTRIP['planned_depart_day_time'].",".$rowTRIP['planned_return_day_time'].",".$rowTRIP['childseat'].",".stripslashes(str_replace(","," ",$rowTRIP['destination'])).",".$rowTRIP['no_cost'].",".stripslashes(str_replace(","," ",$rowTRIP['restriction'])).",".$rowTRIP['begin_mileage'].",".$rowTRIP['end_mileage'].",".$rowTRIP['end_gas_percent'].",".$rowTRIP['problem'].",".stripslashes(str_replace(","," ",$rowTRIP['destination']))));}
														}
													
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowTRIP['res_id'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowTRIP['planned_depart_day_time'],2);?></td>
															<td class="coldata"><? echo $rowTRIP['home_dept_name'];?></td>
															<td class="coldata"><? echo $rowTRIP['reserved_by_name'];?></td>
															<td class="coldata"><? echo $rowTRIP['closure'];?></td>
															<td class="coldata"><? echo round($rowTRIP['trip_miles']);?></td>
															<td class="coldata"><? echo stripslashes($rowTRIP['destination']);?></td>
															<td class="coldata" align="center"><a href="closed_trip_details.php?resid=<? echo $rowTRIP['res_id'];?>" title="VIEW">V</a></td>
														</tr>
											<?			}$listed++;	
													}
													
													
											?>
								
										</table>
									</td>
								</tr>
								<?	
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
										
									} if($iRECORD_COUNT>0)	mysql_free_result($rsTRIP);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
							</table>
							</form>
		</div>
</body>
</html>				