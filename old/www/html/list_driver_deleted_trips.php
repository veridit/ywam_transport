<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sDays			=	"";
	$sDeptNo		=	"";
	$sStatus		=	"";
	$iVehicle_ID	=	"";
	$iDriver_ID		=	"";
	$iMOST_CANCELLED=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sAction		=	"";
	$iRESERVATION_ID=	"";
	$iRES_ID		=	"";
	//$sSORT_ORDER	=	"tbl_reservations.planned_depart_day_time DESC";
	$sSORT_ORDER	=	"resvrd_by.l_name ASC";
	$sStartDate		=	"";
	$sEndDate		=	"";
	
	if(isset($_REQUEST["action"])	&& $_REQUEST["action"]=="search"){
	
			if(isset($_POST["resid"]) && $_POST["resid"]!="")	{$iRESERVATION_ID		=		$_POST["resid"];}
			
			//if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")	{$iVehicle_ID		=	$_POST["drpvehicle"];	$sCriteriaSQL	.=	" AND tbl_reservations.vehicle_id = ".$iVehicle_ID;}
			if(isset($_REQUEST["txtresvno"]) && $_REQUEST["txtresvno"]!="")	{$iRES_ID			=	$_REQUEST["txtresvno"];	$sCriteriaSQL	.=	" AND tbl_reservations.res_id = ".$iRES_ID;}
			if(isset($_POST["drpdriver"]) && $_POST["drpdriver"]!="")		{$iDriver_ID		=	$_POST["drpdriver"];	$sCriteriaSQL	.=	" AND tbl_reservations.user_id = ".$iDriver_ID;}
			if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{$sDeptNo			=	$_POST["drpdept"];		$sCriteriaSQL	.=	" AND resvrd_by.dept_id = '".$sDeptNo."'";}
			
			if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")			{$sSORT_ORDER		=	$_POST["drpsort"];	}
			
			/*if(isset($_POST["drpdays"]) && $_POST["drpdays"]!=""){
				$sDays			=	$_POST["drpdays"];
				$sCriteriaSQL	.=	" AND (DATEDIFF(CURDATE(), tbl_reservations.driver_cancelled_time) < ".$sDays." AND DATEDIFF(CURDATE(), tbl_reservations.driver_cancelled_time) >0) " ;
			}*/
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
				$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
				$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
				$sCriteriaSQL	.=	" AND (DATE(tbl_reservations.driver_cancelled_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
			}
			
			if(isset($_POST["drpcancel"]) && $_POST["drpcancel"]!="")		{$iMOST_CANCELLED	=	$_POST["drpcancel"];	$sCriteriaSQL	.=	" AND tbl_reservations.user_id IN (SELECT tbl_reservations.user_id FROM tbl_reservations WHERE cancelled_by_driver = 1 ".$sCriteriaSQL." GROUP BY tbl_reservations.user_id HAVING ".$iMOST_CANCELLED.")";}
			
				
			$sSQL	=	"SELECT tbl_reservations.res_id, vehicle_no, CONCAT(resvrd_by.l_name,' ', resvrd_by.f_name) AS user_name, planned_passngr_no, planned_depart_day_time, tbl_reservations.destination, ".
			"planned_return_day_time, tbl_departments.dept_name, tbl_reservations.reg_date, ".
			"CASE WHEN tbl_reservations.driver_cancelled_time IS NULL AND tbl_abandon_trips.res_id IS NOT NULL THEN tbl_abandon_trips.abandon_date ELSE tbl_reservations.driver_cancelled_time END AS driver_cancelled_time, ".
			"CASE WHEN tbl_abandon_trips.res_id IS NOT NULL AND tbl_abandon_trips.user_id IS NULL THEN 'YES' ELSE CASE WHEN tbl_abandon_trips.res_id IS NULL THEN '' END END AS less_than_12 ".
			"FROM tbl_reservations ".
			"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_user resvrd_by ON tbl_reservations.user_id = resvrd_by.user_id ".
			"INNER JOIN tbl_departments ON resvrd_by.dept_id = tbl_departments.dept_id ".
			"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
			"WHERE (tbl_abandon_trips.res_id IS NOT NULL OR tbl_reservations.cancelled_by_driver = 1) ".$sCriteriaSQL." ORDER BY ".$sSORT_ORDER;
			
			
			
			//print($sSQL);
			$rsRES			=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT	=	mysql_num_rows($rsRES);
			
			if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no cancelled trip found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Driver Deleted Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<script type="text/javascript" src="./js/common_scripts.js"></script>
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
<link rel="stylesheet" type="text/css" href="../html/sub_style.css">
</head>
<body>
<div align="center">
	
						<form name="frm1" action="list_driver_deleted_trips.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="resid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<!--<td class="label" width="200">Days:<br />
													<?	//fn_DAYS("drpdays", $sDays, "150", "1", "ALL");	?>
												</td>-->
												<!--<td class="label" width="120">Resv No:<br />
													<input type="text" name="txtresvno" value="<? //echo $iRES_ID?>" style="width:120px;" onKeyDown="return validateNumber(event);" />
												</td>-->
												
												<td class="label" width="200">Dept.:<br />
													<?	fn_DEPARTMENT('drpdept', $sDeptNo, "200", "1", "ALL");	?>
												</td>
												
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												
												<td rowspan="2" align="center"><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
											<tr>
												<td class="label" width="150">
													<!--Vehicle No:<br />-->
													Rsrvd by:<br />
													<?	//fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");?>
													<?	fn_DISPLAY_USERS('drpdriver', $iDriver_ID, "150", "1", "ALL", "CONCAT(l_name,' ',f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF);?>
												</td>
												<td class="label" width="200">Most Cancellations.:<br />
													<?	$arrCANCEL[0][0]	=	"COUNT(tbl_reservations.res_id) = 2";					$arrCANCEL[0][1]	=	"2 Cancel";
														$arrCANCEL[1][0]	=	"COUNT(tbl_reservations.res_id) >=3 AND COUNT(tbl_reservations.res_id) <=4";		$arrCANCEL[1][1]	=	"3-4 Cancel";
														$arrCANCEL[2][0]	=	"COUNT(tbl_reservations.res_id) > 4";					$arrCANCEL[2][1]	=	"More than 4";
															echo "<select name='drpcancel' style='width:200px;' size='1'>";
															echo "<option value=''>--Any--</option>";
															for($iCounter=0;$iCounter<=2;$iCounter++){
													?>
																<option value="<?Php echo $arrCANCEL[$iCounter][0];?>" <? if($iMOST_CANCELLED==$arrCANCEL[$iCounter][0]) echo "selected";?>><?Php echo $arrCANCEL[$iCounter][1];?></option>
													<?Php		}
															echo "</select>";
													?>	
													
												</td>
												<td class="label" width="150">Sort By:<br />
													
													<select name="drpsort" style="width:120px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="res_id ASC" <? if($sSORT_ORDER == "res_id ASC") echo "selected";?>>Resv # Ascending</option>
														<option value="res_id DESC" <? if($sSORT_ORDER == "res_id DESC") echo "selected";?>>Resv # Descending</option>
														<option value="dept_name ASC" <? if($sSORT_ORDER == "dept_name ASC") echo "selected";?>>Dept Name A - Z</option>
														<option value="dept_name DESC" <? if($sSORT_ORDER == "dept_name DESC") echo "selected";?>>Dept Name Z - A</option>
														<option value="l_name ASC" <? if($sSORT_ORDER == "l_name ASC") echo "selected";?>>Rsvrd By L Name A - Z</option>
														<option value="l_name DESC" <? if($sSORT_ORDER == "l_name DESC") echo "selected";?>>Rsvrd By L Name Z - A</option>
														<option value="vehicle_no ASC" <? if($sSORT_ORDER == "vehicle_no ASC") echo "selected";?>>Vehicle # Ascending</option>
														<option value="vehicle_no DESC" <? if($sSORT_ORDER == "vehicle_no DESC") echo "selected";?>>Vehicle # Descending</option>
														<option value="planned_depart_day_time ASC" <? if($sSORT_ORDER == "planned_depart_day_time ASC") echo "selected";?>>Depart Date # Ascending</option>
														<option value="planned_depart_day_time DESC"  <? if($sSORT_ORDER == "planned_depart_day_time DESC") echo "selected";?>>Depart Date # Descending</option>
														<option value="driver_cancelled_time ASC"  <? if($sSORT_ORDER == "driver_cancelled_time ASC") echo "selected";?>>Cancel Date # Ascending</option>
														<option value="driver_cancelled_time DESC" <? if($sSORT_ORDER == "driver_cancelled_time DESC") echo "selected";?>>Cancel Date # Descending</option>
														
													</select>
												</td>
												
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
																		$sFname	=	'excel_reports/driver_deleted_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
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
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Resv_No,Dept_Name,Rsvrd_By,Vehicle,Less_Than_12,Depart_Date,Destination,Driver_Cancelled_Date'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','Resv_No,Dept_Name,Rsvrd_By,Vehicle,End_Gas,Planned_Pasngr,Depart_Date_Time,Return_Date_Time,Overnight,ChileSeat,Destination,Reg_Date,Driver_Cancelled_Date'));}
											
										}
								
								
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Resv.#</td>
												<td width="120" class="colhead">Dept. No</td>
												<td width="100" class="colhead">Rsrvd by</td>
												<td width="40" class="colhead">Veh#</td>
												<td width="70" class="colhead">Aband?</td>
												<td width="105" class="colhead">Depart Date</td>
												<td width="120" class="colhead">Destination</td>
												<td width="105" class="colhead">Date Cancelled</td>
												<td width="30" class="colhead" align="center">Act</td>
											</tr>
											<?		$listed	=	0;
													while($rowRES	=	mysql_fetch_array($rsRES)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowRES['res_id'].",".$rowRES['dept_name'].",".$rowRES['user_name'].",".$rowRES['vehicle_no'].",".$rowRES['less_than_12'].",".$rowRES['planned_depart_day_time'].",".stripslashes(str_replace(","," ",$rowRES['destination'])).",".$rowRES['driver_cancelled_time']));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowRES['res_id'].",".$rowRES['dept_name'].",".$rowRES['user_name'].",".$rowRES['vehicle_no'].",".$rowRES['end_gas'].",".$rowRES['planned_passngr_no'].",".$rowRES['planned_depart_day_time'].",".$rowRES['planned_return_day_time'].",".$rowRES['overnight'].",".$rowRES['childseat'].",".stripslashes($rowRES['destination']).",".$rowRES['reg_date'].",".$rowRES['driver_cancelled_time']));}
														}
														if($listed>=$cur_rows && $listed< $max_rows){
														
														
														
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowRES['res_id'];?></td>
															<td class="coldata"><? echo $rowRES['dept_name'];?></td>
															<td class="coldata"><? echo $rowRES['user_name'];?></td>
															<td class="coldata"><? echo $rowRES['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowRES['less_than_12'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_depart_day_time'], 2);?></td>
															<td class="coldata"><? echo stripslashes($rowRES['destination']);?></td>
															<td class="coldata"><? if($rowRES['driver_cancelled_time']!="") echo fn_cDateMySql($rowRES['driver_cancelled_time'], 2); else echo "&nbsp;";?></td>
															<td class="coldata" align="center"><a href="view_driver_cancelled_trip.php?resid=<? echo $rowRES['res_id'];?>">view</a></td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}if($iRECORD_COUNT>0)mysql_free_result($rsRES);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
							</table>
						</form>
 </div>
</body>
</html>
 
