<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');

	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sDays			=	"";
	$iRES_ID		=	"";
	$iVehicle_ID	=	"";
	$sDeptNo		=	"";
	$iRESVD_USER	=	"";
	$sCriteriaSQL	=	"";
	$sINNER_CRITERIA=	"";
	$iMOST_ABANDONMENTS=	"";
	$iGROUP_BY_SQL	=	"";
	$sSORT_ORDER	=	"abandon_id DESC";
	$sStartDate		=	"";
	$sEndDate		=	"";
	
	//if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	if(isset($_REQUEST["action"])	&& $_REQUEST["action"]=="search"){
	
		if(isset($_REQUEST["txtresvno"]) && $_REQUEST["txtresvno"]!="")	{$iRES_ID			=	$_REQUEST["txtresvno"];	$sCriteriaSQL	.=	" AND a.res_id = ".$iRES_ID;}
		//if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")		{$sDays				=	$_POST["drpdays"];		$sINNER_CRITERIA=	" AND (TIMESTAMPDIFF(MONTH, CURDATE(), a.abandon_date) BETWEEN 0 AND ".$sDays.")";}																																											//SELECT r.user_id FROM tbl_reservations r INNER JOIN tbl_abandon_trips a ON r.res_id = a.res_id GROUP BY r.user_id HAVING COUNT(*) = 2
		//if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")		{$sDeptNo			=	$_POST["drpdept"];		$sCriteriaSQL	.=	" AND reserv_user.dept_id = '".$sDeptNo."'";}
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")		{$sDeptNo			=	mysql_real_escape_string($_POST["drpdept"]);		$sCriteriaSQL	.=	" AND assigned_driver.dept_id = '".$sDeptNo."'";}
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")		{$sSORT_ORDER		=	mysql_real_escape_string($_POST["drpsort"]);	}
		
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
			$sStartDate			=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
			$sEndDate			=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
			$sCriteriaSQL		.=	" AND (DATE(a.abandon_date) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
			$sINNER_CRITERIA	.=	" AND (DATE(a.abandon_date) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";	
		}
		
		if(isset($_POST["drpcancel"]) && $_POST["drpcancel"]!="")	{$iMOST_ABANDONMENTS=	mysql_real_escape_string($_POST["drpcancel"]);	$sCriteriaSQL	.=	" AND tbl_reservations.assigned_driver IN (SELECT r.assigned_driver FROM tbl_reservations r  INNER JOIN tbl_abandon_trips a ON r.res_id = a.res_id WHERE 1=1 ".$sINNER_CRITERIA." GROUP BY r.assigned_driver HAVING ".$iMOST_ABANDONMENTS.")";		$iGROUP_BY_SQL	=	"GROUP BY tbl_reservations.assigned_driver HAVING ".$iMOST_ABANDONMENTS;	}
		
		$sSQL	=	"SELECT tbl_reservations.user_id, abandon_id, DATE_FORMAT(abandon_date, '%m-%d-%Y %l:%i %p') AS abandon_date, a.notes, a.res_id, ".
		"CONCAT(reserv_user.f_name, ' ', reserv_user.l_name) AS user_name, CONCAT(assigned_driver.f_name, ' ', assigned_driver.l_name) AS assigned_name, ".
		"CASE WHEN a.user_id IS NULL THEN 'BY SYSTEM' ELSE CONCAT(abandon_user.f_name, ' ', abandon_user.l_name) END AS abandon_name, ".
		"CONCAT('Vehicle: ', vehicle_no, ' From: ', DATE_FORMAT(planned_depart_day_time, '%m-%d-%Y %l:%i %p'), ' To: ', DATE_FORMAT(planned_return_day_time, '%m-%d-%Y %l:%i %p')) AS reservation ".
		"FROM tbl_abandon_trips a INNER JOIN tbl_reservations ON a.res_id = tbl_reservations.res_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_user reserv_user ON tbl_reservations.user_id = reserv_user.user_id ".
		"INNER JOIN tbl_user assigned_driver ON tbl_reservations.assigned_driver = assigned_driver.user_id ".
		"LEFT OUTER JOIN tbl_user abandon_user ON a.user_id = abandon_user.user_id ".
		"WHERE 1=1 ".$sCriteriaSQL.$sINNER_CRITERIA." ORDER BY ".$sSORT_ORDER;
		
		//print($sSQL);
		
		$rsABANOD_TRIP			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsABANOD_TRIP);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no abandon trip found", "C_ERROR");}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>List Abandoned Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../html/sub_style.css">
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
</head>
<body>
<div align="center">
	
						<form name="frm1" action="list_abandon_trips.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<!--<td class="label" width="120">Resv No:<br />
													<input type="text" name="txtresvno" value="<? //echo $iRES_ID?>" style="width:120px;" onKeyDown="return validateNumber(event);" />
												</td>-->
												
												<!--<td class="label" width="100">Days:<br /><?	//fn_DAYS("drpdays", $sDays, "90", "1", "ALL");	?></td>-->
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td></td>
												
												<td rowspan="2" align="center"><br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
											<tr>
												
												<td class="label" width="150">Sort By:<br />
													
													<select name="drpsort" style="width:160px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="user_name ASC" <? if($sSORT_ORDER == "user_name ASC") echo "selected";?>>Reserved By</option>
														<option value="assigned_name ASC" <? if($sSORT_ORDER == "assigned_name ASC") echo "selected";?>>Assgnd Drvr</option>	
														<option value="tbl_reservations.reg_date DESC" <? if($sSORT_ORDER == "tbl_reservations.reg_date DESC") echo "selected";?>>Reserved Date</option>
													</select>
												</td>
												<td class="label" width="150">No Abandoned:<br />
													<?	$arrABANDON[0][0]	=	"COUNT(*) = 2";								$arrABANDON[0][1]	=	"2 Abandonded";
														$arrABANDON[1][0]	=	"COUNT(*) >=3";								$arrABANDON[1][1]	=	"3 or more Abandonded";
															echo "<select name='drpcancel' style='width:150px;' size='1'>";
															echo "<option value=''>--Any--</option>";
															for($iCounter=0;$iCounter<=1;$iCounter++){
													?>		<option value="<?Php echo $arrABANDON[$iCounter][0];?>" <? if($iMOST_ABANDONMENTS==$arrABANDON[$iCounter][0]) echo "selected";?>><?Php echo $arrABANDON[$iCounter][1];?></option>
													<?Php	}
															echo "</select>";
													?>	
													
												</td>
												<td class="label" width="200">Dept.:<br />
													<?	fn_DEPARTMENT('drpdept', $sDeptNo, "200", "1", "ALL");	?>
												</td>
												
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_abandon_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Resrv. No,Reservation,TM_Notes,Abandon_Date,Reservation_Made_By,Assigned_Driver,Aband_By'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Resrv#</td>
												<td width="150" class="colhead">Reservation</td>
												<td width="150" class="colhead">TM Notes</td>
												<td width="100" class="colhead">Abandon Date.</td>
												<td width="100" class="colhead">Resv. made by</td>
												<td width="100" class="colhead">Asgnd Drvr</td>
												<td width="100" class="colhead">Abond. by</td>
												<td width="30" class="colhead">Act,</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowABANDON	=	mysql_fetch_array($rsABANOD_TRIP)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowABANDON["res_id"].",".$rowABANDON['reservation'].",".stripslashes(str_replace(","," ",$rowABANDON['notes'])).",".$rowABANDON['abandon_date'].",".$rowABANDON['user_name'].",".$rowABANDON['assigned_name'].",".$rowABANDON['abandon_name']));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowABANDON['res_id'];?></td>
															<td class="coldata"><? echo $rowABANDON['reservation'];?></td>
															<td class="coldata"><? echo stripslashes($rowABANDON['notes']);?></td>
															<td class="coldata"><? echo $rowABANDON['abandon_date'];?></td>
															<td class="coldata"><? echo $rowABANDON['user_name'];?></td>
															<td class="coldata"><? echo $rowABANDON['assigned_name'];?></td>
															<td class="coldata"><? echo $rowABANDON['abandon_name'];?></td>
															<td class="coldata"><a href="abandon_trip_details.php?resid=<?Php echo $rowABANDON['res_id'];?>">V</a></td>
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}if($iRECORD_COUNT>0)	mysql_free_result($rsABANOD_TRIP);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
							</table>
						</form>
                	
 </div>
</body>
</html>