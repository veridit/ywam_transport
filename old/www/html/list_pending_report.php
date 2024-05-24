<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sDays			=	"";
	$sDeptNo		=	"";
	$sStatus		=	"";
	//$bRepeating		=	"";
	$iVehicle_ID	=	"";
	$sCancelStatus	=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li>Note: only pending trip details can be viewed from this page!<li>Note: trip details for abandon trips are not available from this page. Use 'List Abandoned Trips' for that info ","C_SUCCESS");
	$sAction		=	"";
	$iRESERVATION_ID=	"";
	$iRES_SEARCH_ID	=	"";
	$bCOST			=	"";
	$sASSND_DRIVER	=	0;
	$iRESV_DRIVER	=	0;
	$sSORT_ORDER	=	"r.planned_depart_day_time DESC";
	$sStartDate		=	"";
	$sEndDate		=	"";
	
	
	//if(isset($_POST["action"]) && $_POST["action"]!="")		$sAction		=	$_POST["action"];
	if(isset($_REQUEST["action"]) && $_REQUEST["action"]!="")		$sAction		=	$_REQUEST["action"];
	
	if($sAction=="search"){
	
		//if(isset($_REQUEST["resid"]) && $_REQUEST["resid"]!="")					{$iRESERVATION_ID		=	$_REQUEST["resid"];																								}
		if(isset($_REQUEST["txtresvno"]) && $_REQUEST["txtresvno"]!="")			{$iRES_SEARCH_ID		=	$_REQUEST["txtresvno"];	$sCriteriaSQL		.=	" AND r.res_id 			= ".$iRES_SEARCH_ID;	}
		if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{$iVehicle_ID			=	mysql_real_escape_string($_POST["drpvehicle"]);	$sCriteriaSQL		.=	" AND r.vehicle_id 		= ".$iVehicle_ID;		}
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				{$sDeptNo				=	mysql_real_escape_string($_POST["drpdept"]);		$sCriteriaSQL		.=	" AND r.billing_dept	= '".$sDeptNo."'";		}
		if(isset($_POST["drpcost"]) && $_POST["drpcost"]!="")				{$bCOST					=	mysql_real_escape_string($_POST["drpcost"]);		$sCriteriaSQL		.=	" AND r.no_cost 			= ".$bCOST;				}
		if(isset($_POST["drpassgnddrvr"]) && $_POST["drpassgnddrvr"]!="")	{$sASSND_DRIVER			=	mysql_real_escape_string($_POST["drpassgnddrvr"]);  $sCriteriaSQL		.=	" AND r.assigned_driver 	= ".$sASSND_DRIVER;		}
		if(isset($_POST["drpresvdrvr"]) && $_POST["drpresvdrvr"]!="")		{$iRESV_DRIVER			=	mysql_real_escape_string($_POST["drpresvdrvr"]);	$sCriteriaSQL		.=	" AND r.user_id 			= ".$iRESV_DRIVER;		}
		
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
			$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));
			$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));		
			$sCriteriaSQL	.=	" AND (DATE(r.planned_depart_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
		}
		
		if(isset($_POST["drpdays"]) && $_POST["drpdays"]!=""){
			$sDays			=	mysql_real_escape_string($_POST["drpdays"]);
			$sCriteriaSQL	.=	" AND (TIMESTAMPDIFF(DAY, CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN ".$sDays.")";			
		}
		
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")			{$sSORT_ORDER		=	mysql_real_escape_string($_POST["drpsort"]);	}

		
		$sSQL	=	"SELECT r.res_id, vehicle_no, ".
		"CONCAT(assigned.f_name, ' ', assigned.l_name) AS assigned_name, ".
		"CONCAT(rsvrd_by.f_name, ' ', rsvrd_by.l_name) AS rsvrd_by_name, ".
		"planned_passngr_no, planned_depart_day_time, r.destination, ".
		"planned_return_day_time, ".
		"tbl_departments.dept_name, Charge_Dept.dept_name AS charge_dept, ".
		"CASE WHEN overnight = 1 THEN 'YES' ELSE 'NO' END AS overnight, CASE WHEN childseat = 1 THEN 'YES' ELSE 'NO' END AS childseat, r.reg_date, ".
		"CASE WHEN key_no IS NULL THEN ' ' ELSE key_no END AS key_no, CASE WHEN card_no IS NULL THEN ' ' ELSE card_no END AS card_no, ".
		"CASE WHEN repeating = 0 THEN '' ELSE 'R' END AS repeating, ".
		"CASE WHEN no_cost = 0 THEN 'Std. Charge'  ELSE 'No Cost' END AS no_cost ".
		"FROM tbl_reservations r ".
		"INNER JOIN tbl_vehicles ON r.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
		"INNER JOIN tbl_user assigned ON r.assigned_driver = assigned.user_id ".
		"INNER JOIN tbl_departments ON assigned.dept_id = tbl_departments.dept_id ".
		"INNER JOIN tbl_departments Charge_Dept ON r.billing_dept = Charge_Dept.dept_id ".
		"INNER JOIN tbl_user rsvrd_by ON r.user_id = rsvrd_by.user_id ".
		"LEFT OUTER JOIN tbl_trip_details ON r.res_id = tbl_trip_details.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips ON r.res_id = tbl_abandon_trips.res_id ".
		"WHERE 1=1 AND tbl_abandon_trips.res_id IS NULL AND tbl_trip_details.res_id IS NULL AND r.reservation_cancelled = 0 AND cancelled_by_driver = 0 ".$sCriteriaSQL." ORDER BY ".$sSORT_ORDER;
		//print($sSQL);
		//OLD QUERY
		//"CASE WHEN tbl_trip_details.end_gas_percent IS NULL AND tbl_abandon_trips.res_id IS NULL THEN 'Pending Trip' ELSE CASE WHEN tbl_abandon_trips.res_id IS NOT NULL THEN 'Abandon Trip' ELSE end_gas_percent END END AS end_gas, ".
		
		$rsRES			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsRES);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no trip found", "C_ERROR");}
		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Open Trips (Pending)</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">

function fn_DELETE_TRIP(iResID){
	document.frm1.resid.value=iResID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}


</script>
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
	
						<form name="frm1" action="list_pending_report.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="resid" value=""	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<!--<td class="label" width="200">Resv. No:<br />
													<input type="text" name="txtresvno" value="<? //echo $iRES_SEARCH_ID?>" style="width:100px;" onKeyDown="return validateNumber(event);" />
												</td>-->
												<!--<td class="label" width="200">Days:<br />
													<?	//fn_DAYS("drpdays", $sDays, "160", "1", "ALL", "list_trips");	?>
													
												</td>
												-->
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="200">Charged Department.:<br />
													<?	fn_DEPARTMENT('drpdept', $sDeptNo, "160", "1", "ALL");	?>
												</td>
												
												<td rowspan="3" align="center" width="200"><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
											<tr>
												<td class="label" width="90">
													V #:<br />
													<?	fn_VEHICLE("drpvehicle", $iVehicle_ID, "100", "1", "ALL");?>
												</td>
												<!--<td class="label" width="70">Trip Status:<br />
													<select name="drppending" size="1" style="width:160px;">
														<option value="" selected>--All--</option>
														<option value="pending" <? //if($sStatus=="pending") echo "selected";?>>Pending</option>
														<option value="closed" <? //if($sStatus=="closed") echo "selected";?>>Closed</option>
														<option value="abandoned" <? //if($sStatus=="abandoned") echo "selected";?>>Abandoned</option>
														<option value="cancelled" <? //if($sStatus=="cancelled") echo "selected";?>>Cancelled</option>
														<option value="deleted" <? //if($sStatus=="deleted") echo "selected";?>>Deleted</option>
														
													</select>
												</td>-->
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:160px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="res_id ASC" <? if($sSORT_ORDER == "res_id ASC") echo "selected";?>>Resv # Ascending</option>
														<option value="res_id DESC" <? if($sSORT_ORDER == "res_id DESC") echo "selected";?>>Resv # Descending</option>	
														<option value="planned_depart_day_time ASC" <? if($sSORT_ORDER == "planned_depart_day_time ASC") echo "selected";?>>Depart Date # Ascending</option>
														<option value="planned_depart_day_time DESC"  <? if($sSORT_ORDER == "planned_depart_day_time DESC") echo "selected";?>>Depart Date # Descending</option>
													</select>
												</td>
												<td class="label" width="200">Days:<br />
													<?															
															$arrDAYS[0][0]	=	"0 AND 0";	$arrDAYS[0][1]	=	"today";
															$arrDAYS[1][0]	=	"1 AND 1";	$arrDAYS[1][1]	=	"tomorrow";
															$arrDAYS[2][0]	=	"0 AND 5";	$arrDAYS[2][1]	=	"Today +5 Days";
															$arrDAYS[3][0]	=	"0 AND 30";	$arrDAYS[3][1]	=	"Today +30 Days";
															$arrDAYS[4][0]	=	"0 AND 45";	$arrDAYS[4][1]	=	"Today +45 Days";
													?>
													<select name="drpdays" style="width:160px;" size="1">
														<option value="">--All--</option>
														<? 	for($iCounter=0;$iCounter<=4;$iCounter++){?>
															<option value="<? echo $arrDAYS[$iCounter][0];?>" <? if($sDays == $arrDAYS[$iCounter][0]) echo "selected";?>><? echo $arrDAYS[$iCounter][1];?></option>
														<?	}?>
													</select>
													
													
												</td>
												
											</tr>
											<tr>
												<td class="label" width="150">Trip Cost:<br />
													<select name="drpcost" size="1" style="width:100px;">
														<option value="" selected>--All--</option>
														<option value="1" <? if($bCOST=="1") echo "selected";?>>No Cost</option>
														<option value="0" <? if($bCOST=="0") echo "selected";?>>Std. Charge</option>
													</select>
												</td>
												<td class="label">Assigned Driver:<br />
												<?	fn_DISPLAY_USERS('drpassgnddrvr', $sASSND_DRIVER, "160", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF);?>
												</td>
												<td class="label">Reserve Driver:<br />
												<?	fn_DISPLAY_USERS('drpresvdrvr', $iRESV_DRIVER, "160", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_TM.",".$iGROUP_TC);?>
												</td>
											</tr>
											<tr>
												<td colspan="5">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
																<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_reservations.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
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
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Resv_No,Charge_Dept,Assgnd_Driver,Rtrn_Date,Vehicle_No,Depart_Date,Rsvrd_By,Destination,Key_No,Card_No,No_Cost,R'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','Resv_No,Charge_Dept,Rsvrd_By,Assgnd_Driver,Vehicle,End_Gas,Planned_Pasngr,Depart_Date_Time,Return_Date_Time,Overnight,ChileSeat,Destination,Reg_Date,Key_No,Card_No,No_Cost,R'));}
											
										}
								
								
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" align="center">
											<tr>
												<td width="50" class="colhead">Res.#</td>
												<td width="130" class="colhead">Charge Dept.</td>
												<td width="110" class="colhead">Depart Date</td>
												<td width="100" class="colhead">Rtrn Date</td>
												<td width="40" class="colhead">Veh #</td>
												<td width="110" class="colhead">Rsvrd By</td>
												<td width="100" class="colhead">Asgnd Drvr</td>
												<td width="150" class="colhead">Destination</td>
												<td width="25" class="colhead">Key</td>
												<td width="25" class="colhead">Card</td>
												<td width="50" class="colhead" align="center">A</td>
											</tr>
											<?		$listed	=	0;
													while($rowRES	=	mysql_fetch_array($rsRES)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowRES['res_id'].",".$rowRES['charge_dept'].",".$rowRES['assigned_name'].",".$rowRES['planned_return_day_time'].",".$rowRES['vehicle_no'].",".$rowRES['planned_depart_day_time'].",".$rowRES['rsvrd_by_name'].",".stripslashes(str_replace(","," ",$rowRES['destination'])).",".$rowRES['key_no'].",".$rowRES['card_no'].",".$rowRES['no_cost'].",".$rowRES['repeating']));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowRES['res_id'].",".$rowRES['charge_dept'].",".$rowRES['rsvrd_by_name'].",".$rowRES['assigned_name'].",".$rowRES['vehicle_no'].",".$rowRES['end_gas'].",".$rowRES['planned_passngr_no'].",".$rowRES['planned_depart_day_time'].",".$rowRES['planned_return_day_time'].",".$rowRES['overnight'].",".$rowRES['childseat'].",".stripslashes(str_replace(","," ",$rowRES['destination'])).",".$rowRES['reg_date'].",".$rowRES['key_no'].",".$rowRES['card_no'].",".$rowRES['no_cost'].",".$rowRES['repeating']));}
														}
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowRES['res_id'];?></td>
															
															<td class="coldata"><? echo $rowRES['charge_dept'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_depart_day_time'], 2);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_return_day_time'], 2);?></td>
															<td class="coldata"><? echo $rowRES['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowRES['rsvrd_by_name'];?></td>
															<td class="coldata"><? echo $rowRES['assigned_name'];?></td>
															<td class="coldata"><? echo stripslashes($rowRES['destination']);?></td>
															<td class="coldata"><? echo $rowRES['key_no'];?></td>
															<td class="coldata"><? echo $rowRES['card_no'];?></td>
															
															<td class="coldata" align="center">
																<a href="edit_pending_trip.php?resid=<? echo $rowRES['res_id'];?>" title="VIEW">V</a>&nbsp;/&nbsp;
																<a href="close_trip.php?resid=<? echo $rowRES['res_id'];?>" onclick="javascript:fn_CHANGE_PG_NAME('CLOSE TRIP SLIP');" title="CLOSE">CL</a>
																
															</td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}if($iRECORD_COUNT>0)	mysql_free_result($rsRES);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
							</table>
						</form>
                	
 </div>
</body>
</html>
 