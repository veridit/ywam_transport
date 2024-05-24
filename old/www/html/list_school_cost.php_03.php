<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sStatus		=	"";
	$sDeptID		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$sCriteriaSQL_CHARGES	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sDEPT_NO		=	"";
	$sDEPT_NAME		=	"";
	$iDEPT_NO		=	"";
	$iCHARGE_VAL	=	-1;
	$sSORT_ORDER	=	"tbl_departments.dept_id DESC";
	
	if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
		$sSQL	=	"SELECT u.dept_id, d.dept_name FROM tbl_user u INNER JOIN tbl_departments d ON u.dept_id = d.dept_id WHERE u.user_id = ".$_SESSION["User_ID"];
		$rsDEPT_STATS	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsDEPT_STATS)>0){
			list($sDEPT_NO, $sDEPT_NAME)	=	mysql_fetch_row($rsDEPT_STATS);
		}mysql_free_result($rsDEPT_STATS);
	}
	
	
	if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
		$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Note: Charges to inactive departments are possible. <br />To get the cost of these \"excess trips\" set the \"from date\" to the official closing date of dept<br /> and make the \"to date\" today</span>","C_SUCCESS");
	}
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{
			$sDeptID	=	$_POST["drpdept"];		$sCriteriaSQL	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";
		}elseif($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){
			//first select currently leader's department
			$sSQL	=	"SELECT dept_id, dept_name FROM tbl_user WHERE user_id = ".$_SESSION["User_ID"];
			$rsLEADER_DEPT	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsLEADER_DEPT)>0){
				list($iDEPT_NO)	=	mysql_fetch_row($rsLEADER_DEPT);
			}mysql_free_result($rsLEADER_DEPT);
			
			$sCriteriaSQL	.=	" AND tbl_departments.dept_id = '".$iDEPT_NO."'";
		}
		
		
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
			$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
			$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
			$sCriteriaSQL	.=	" AND (DATE(tbl_reservations.planned_return_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
		}
		
		if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	$_POST["drpstatus"];		$sCriteriaSQL	.=	" AND tbl_departments.active = ".$sStatus;}
		if(isset($_POST["drpcharge"]) && $_POST["drpcharge"]!="")			{$iCHARGE_VAL	=	$_POST["drpcharge"];		$sCriteriaSQL_CHARGES	=	" AND mileage.charges ".$iCHARGE_VAL;}
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")				{$sSORT_ORDER		=	$_POST["drpsort"];	}
		
		$sSQL	=	"SELECT cols.dept_no, cols.dept_name, cols.status, cols.res_id, cols.destination, cols.driver_name, cols.miles, cols.ab_fine, cols.depart_date, cols.closing_user, cols.reserved_by, mileage.charges FROM ".
		"(SELECT tbl_departments.dept_id as dept_no, tbl_departments.dept_name, CASE WHEN tbl_departments.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, ".
		"tbl_reservations.res_id, tbl_reservations.destination, CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS driver_name, ".
		"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN 25 ELSE 0 END ELSE CASE WHEN SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) END END AS miles, ".
		"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN 'Yes' END ELSE '' END AS ab_fine, ".
		"DATE_FORMAT(tbl_reservations.planned_depart_day_time, '%m/%d/%Y') AS depart_date, CONCAT(resrvd_by.f_name,' ', resrvd_by.l_name) AS reserved_by, ".
		"CASE WHEN td.trip_id IS NULL AND a.res_id IS NOT NULL THEN CASE WHEN a.user_id IS NULL THEN 'By System' ELSE CONCAT(abandon_user.f_name, ' ', abandon_user.l_name) END ".
		"ELSE CASE WHEN td.trip_id IS NOT NULL AND a.res_id IS NULL THEN CASE WHEN td.user_id IS NULL THEN 'N/A' ELSE CONCAT(closing_user.f_name, ' ', closing_user.l_name) END END END AS closing_user ".
		"FROM tbl_departments INNER JOIN tbl_reservations ON tbl_departments.dept_id = tbl_reservations.billing_dept ".
		"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
		"INNER JOIN tbl_user resrvd_by ON tbl_reservations.user_id = resrvd_by.user_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"LEFT OUTER JOIN tbl_trip_details td ON tbl_reservations.res_id = td.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON tbl_reservations.res_id = a.res_id ".
		"LEFT OUTER JOIN tbl_user abandon_user ON a.user_id = abandon_user.user_id ".
		"LEFT OUTER JOIN tbl_user closing_user ON td.user_id = closing_user.user_id ".
		"WHERE (td.res_id IS NOT NULL OR a.res_id IS NOT NULL) AND tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_reservations.no_cost = 0 ".$sCriteriaSQL." ".
		"GROUP BY tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active, tbl_vehicles.vehicle_id, ".
		"tbl_reservations.res_id, tbl_reservations.planned_return_day_time, tbl_user.f_name, tbl_user.l_name ORDER BY ".$sSORT_ORDER.") cols ";
		
		
		$sSQL	.=	"INNER JOIN (SELECT  tbl_reservations.res_id,  ".
		"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN (a.mile_charges) * 25 ELSE 0 END ELSE CASE WHEN SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (td.mile_charges)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (td.mile_charges)) END END AS charges ".
		"FROM tbl_departments INNER JOIN tbl_reservations ON tbl_departments.dept_id = tbl_reservations.billing_dept ".
		"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"LEFT OUTER JOIN tbl_trip_details td ON tbl_reservations.res_id = td.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON tbl_reservations.res_id = a.res_id ".
		"WHERE (td.res_id IS NOT NULL OR a.res_id IS NOT NULL) AND tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_reservations.no_cost = 0 ".$sCriteriaSQL." ".
		"GROUP BY tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active, tbl_vehicles.vehicle_id, ".
		"tbl_reservations.res_id, tbl_reservations.planned_return_day_time, tbl_user.f_name, tbl_user.l_name ORDER BY ".$sSORT_ORDER.") mileage ";
		
		$sSQL	.=	"ON cols.res_id = mileage.res_id WHERE 1=1 ".$sCriteriaSQL_CHARGES;
		
		//print($sSQL);
		$rsCOST			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT		=	mysql_num_rows($rsCOST);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no record found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?Php if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF)  echo "Trips by School"; else echo "Trips Charged to ".$sDEPT_NAME;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
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
								   				<h1 style="margin-bottom: 0px;"><?Php if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF) echo "TRIPS BY SCHOOL"; else echo "TRIPS CHARGED TO ".$sDEPT_NO."-".$sDEPT_NAME;?></h1>
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
						<form name="frm1" action="list_school_cost.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											
											<tr>
												<?Php if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?><td class="label">Department:<br /><?	fn_DEPARTMENT("drpdept", $sDeptID, "170", "1", "ALL");?></td><?	}?>
												<td class="label">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td rowspan="2">
													<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" />
												</td>
											</tr>
											<?Php if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?>
											<tr>
												
												<td class="label" width="80">Status:<br />
													<?
														$arrSTATUS[0][0]	=	"1";
														$arrSTATUS[0][1]	=	"Active";
														$arrSTATUS[1][0]	=	"0";
														$arrSTATUS[1][1]	=	"InActive";
													?>
													<select name="drpstatus" size="1" style="width:80px;">
														<option value="">Any</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrSTATUS[$iCOUNTER][0]?>" <? if($arrSTATUS[$iCOUNTER][0]==$sStatus) echo "selected";?>><?=$arrSTATUS[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:160px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="f_name ASC" <? if($sSORT_ORDER == "f_name ASC") echo "selected";?>>Assgnd Driver A-Z</option>
														<option value="f_name DESC" <? if($sSORT_ORDER == "f_name DESC") echo "selected";?>>Assgnd Driver Z-A</option>	
														<option value="planned_depart_day_time ASC" <? if($sSORT_ORDER == "planned_depart_day_time ASC") echo "selected";?>>Depart Date # Ascending</option>
														<option value="planned_depart_day_time DESC"  <? if($sSORT_ORDER == "planned_depart_day_time DESC") echo "selected";?>>Depart Date # Descending</option>
													</select>
												</td>
												<td class="label" width="250">
												$ Charge:<br />
												<select name="drpcharge" style="width:100px;">
													<option value="">--All--</option>
													<option value="=0" <?Php if($iCHARGE_VAL=='=0') echo "selected";?>>Zero</option>
													<option value=">100" <?Php if($iCHARGE_VAL=='>100') echo "selected";?>>Over $100</option>
												</select>
												</td>
											</tr>
											<?	}?>
												
											
										</table>
									</td>
								</tr>
								<tr><td colspan="7"><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_school_cost.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Status,Assgnd_Driver,Reserved_By,Resv_No,Destination,Depart_Date,Closed_By,Charges,AB_Fine'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="150" class="colhead">Dept. Name</td>
												<td width="50" class="colhead">Status</td>
												<td width="100" class="colhead">Asgnd Drvr</td>
												<td width="100" class="colhead">Rsvd By</td>
												<td width="70" class="colhead">R #</td>
												<td width="150" class="colhead">Destination</td>
												<td width="100" class="colhead" align="center">Depart. Date</td>
												<td width="70" class="colhead">Closed By</td>
												<td width="50" class="colhead" align="right">Charges</td>
												<td width="70" class="colhead" align="center">AB Fine</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowCOST	=	mysql_fetch_array($rsCOST)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowCOST['status'].",".$rowCOST["driver_name"].",".$rowCOST["reserved_by"].",".$rowCOST["res_id"].",".str_replace(","," ",stripslashes($rowCOST["destination"])).",".$rowCOST["depart_date"].",".$rowCOST['closing_user'].",".$rowCOST["charges"].",".$rowCOST["ab_fine"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata"><? echo $rowCOST['dept_name'];?></td>
															<td class="coldata"><? echo $rowCOST['status'];?></td>
															<td class="coldata"><? echo $rowCOST['driver_name'];?></td>
															<td class="coldata"><? echo $rowCOST['reserved_by'];?></td>
															<td class="coldata"><? echo $rowCOST['res_id'];?></td>
															<td class="coldata"><? echo stripslashes($rowCOST['destination']);?></td>
															<td class="coldata" align="center"><? echo $rowCOST['depart_date'];?></td>
															<td class="coldata"><? echo $rowCOST['closing_user'];?></td>
															<td class="coldata" align="right"><? echo fn_NUMBER_FORMAT($rowCOST['charges'], "1234.56");?></td>
															<td class="coldata" align="right"><? echo $rowCOST['ab_fine'];?></td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
										</table>
									</td>
								</tr>
								<?	} if($iRECORD_COUNT>0)mysql_free_result($rsCOST);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
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
<!--
 $sSQL	=	"SELECT tbl_departments.dept_id as dept_no, tbl_departments.dept_name, CASE WHEN tbl_departments.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, ".
		"tbl_reservations.res_id, tbl_reservations.destination, CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS driver_name, ".
		"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN 25 ELSE 0 END ELSE CASE WHEN SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) END END AS miles, ".
		"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN (a.mile_charges) * 25 ELSE 0 END ELSE CASE WHEN SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (td.mile_charges)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (td.mile_charges)) END END AS charges, ".
		"DATE_FORMAT(tbl_reservations.planned_depart_day_time, '%m/%d/%Y') AS depart_date ".
		"FROM tbl_departments INNER JOIN tbl_reservations ON tbl_departments.dept_id = tbl_reservations.billing_dept ".
		"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"LEFT OUTER JOIN tbl_trip_details td ON tbl_reservations.res_id = td.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON tbl_reservations.res_id = a.res_id ".
		"WHERE (td.res_id IS NOT NULL OR a.res_id IS NOT NULL) AND tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_reservations.no_cost = 0 ".$sCriteriaSQL." ".
		"GROUP BY tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active, tbl_vehicles.vehicle_id, ".
		"tbl_reservations.res_id, tbl_reservations.planned_return_day_time, tbl_user.f_name, tbl_user.l_name ORDER BY ".$sSORT_ORDER;-->