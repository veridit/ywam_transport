<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sDeptID		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$sCriteriaSQL2	=	"";
	$iRECORD_COUNT	=	0;
	$sStatus		=	"";
	$sMessage		=	fn_Print_MSG_BOX("<li>This summary includes the cost of restricted vehicles as well as reserved vehicles.  <br />Please enter the data for the restricted vehicles BEFORE using this report.", "C_SUCCESS");
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
			if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{
				$sDeptID	=	mysql_real_escape_string($_POST["drpdept"]);
				$sCriteriaSQL	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";
				$sCriteriaSQL2	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";
			}
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
						
				$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));				
				$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));
			
				$sCriteriaSQL	.=	" AND tbl_reservations.planned_return_day_time >= '".$sStartDate."' AND tbl_reservations.planned_return_day_time <= '".$sEndDate."'";
				$sCriteriaSQL2	.=	" AND (CAST(tbl_restricted_charges.charge_month AS UNSIGNED) BETWEEN CAST(".substr($sStartDate,5, 2)." AS UNSIGNED) AND CAST(".substr($sEndDate,5, 2)." AS UNSIGNED)) AND (CAST(tbl_restricted_charges.charge_year AS UNSIGNED) BETWEEN CAST(".substr($sStartDate,0, 4)." AS UNSIGNED) AND CAST(".substr($sEndDate,0, 4)." AS UNSIGNED))";
			}
			
			if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	mysql_real_escape_string($_POST["drpstatus"]);		$sCriteriaSQL	.=	" AND tbl_departments.active = ".$sStatus;}
			
			
			$sSQL	=	"SELECT total_cost.dept_no, total_cost.dept_name, total_cost.status, SUM(total_cost.miles) AS miles, SUM(total_cost.charges) AS charges FROM ";
				
			$sSQL	.=	"( ";
		# Note chnages made for error in calulations some of these are now signed	
			$sSQL	.=	"SELECT dept_cost.dept_no, dept_cost.dept_name, dept_cost.status, SUM(dept_cost.miles) AS miles, SUM(dept_cost.charges) AS charges FROM ";
			$sSQL	.=	"(SELECT tbl_departments.dept_id as dept_no, dept_name, CASE WHEN tbl_departments.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, ".
			"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN 25 ELSE 0 END ELSE CASE WHEN SUM(CAST(end_mileage AS SIGNED) - CAST(begin_mileage AS SIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS SIGNED) - CAST(begin_mileage AS SIGNED)) END END AS miles, ".
			"CASE WHEN end_mileage IS NULL THEN CASE WHEN a.calculate_fine = 1 THEN (a.mile_charges) * 25 ELSE 0 END ELSE CASE WHEN SUM((CAST(end_mileage AS SIGNED) - CAST(begin_mileage AS SIGNED)) * (td.mile_charges)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS SIGNED) - CAST(begin_mileage AS SIGNED)) * (td.mile_charges)) END END AS charges ".
			"FROM tbl_departments INNER JOIN tbl_reservations ON tbl_departments.dept_id = tbl_reservations.billing_dept ".
			"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
			"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"LEFT OUTER JOIN tbl_trip_details td ON tbl_reservations.res_id = td.res_id ".
			"LEFT OUTER JOIN tbl_abandon_trips a ON tbl_reservations.res_id = a.res_id ".
			"WHERE (td.res_id IS NOT NULL OR a.res_id IS NOT NULL) AND tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_reservations.no_cost = 0 ".$sCriteriaSQL." ".
			"GROUP BY tbl_reservations.res_id, tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active, tbl_vehicles.vehicle_id, tbl_reservations.planned_return_day_time, tbl_user.f_name, tbl_user.l_name) dept_cost ".
			"GROUP BY dept_cost.dept_no, dept_cost.dept_name, dept_cost.status ";
			
			$sSQL	.=	" UNION ALL ";
			//total charges from restricted vehicles
			$sSQL	.=	"SELECT tbl_departments.dept_id AS dept_no, tbl_departments.dept_name, CASE WHEN tbl_departments.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, 0 AS miles, SUM(total_charge) AS charges ".
			"FROM tbl_restricted_charges INNER JOIN tbl_departments ON tbl_restricted_charges.dept_id = tbl_departments.dept_id ".
			"INNER JOIN tbl_vehicles ON tbl_restricted_charges.vehicle_id = tbl_vehicles.vehicle_id ".
			"WHERE tbl_restricted_charges.calc_method = 'Total Charge' ".$sCriteriaSQL2." GROUP BY tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active ";
			
			$sSQL	.=	" UNION ALL ";
			
			$sSQL	.=	"SELECT tbl_departments.dept_id AS dept_no, tbl_departments.dept_name, CASE WHEN tbl_departments.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, ".
			"CASE WHEN SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) END AS miles, ".
			"CASE WHEN SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (rate)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * rate) END AS charges ".
			"FROM tbl_restricted_charges INNER JOIN tbl_departments ON tbl_restricted_charges.dept_id = tbl_departments.dept_id ".
			"INNER JOIN tbl_vehicles ON tbl_restricted_charges.vehicle_id = tbl_vehicles.vehicle_id ".
			"WHERE tbl_restricted_charges.calc_method = 'Readings' ".$sCriteriaSQL2." GROUP BY tbl_departments.dept_id, tbl_departments.dept_name, tbl_departments.active ";
			
			
			$sSQL	.=	") total_cost GROUP BY total_cost.dept_no, total_cost.dept_name, total_cost.status ";
			
			
			
			//print($sSQL);
			$rsCOST			=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT		=	mysql_num_rows($rsCOST);
			
			if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("no record found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Department Cost Summary</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
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
								   				<h1 style="margin-bottom: 0px;">DEPARTMENT COST SUMMARY</h1>
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
						<form name="frm1" action="list_dept_cost.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="200">Department<br />
													<?	fn_DEPARTMENT("drpdept", $sDeptID, "170", "1", "ALL");?>
												</td>
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">Status:<br />
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
												<td><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td colspan="7"><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_dept_cost.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Dept_No,Dept_Name,Miles,Charges'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Dept. No</td>
												<td width="200" class="colhead">Dept. Name</td>
												<td width="50" class="colhead">Status</td>
												<td width="100" class="colhead">Miles</td>
												<td width="100" class="colhead" align="right">Charges</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowCOST	=	mysql_fetch_array($rsCOST)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowCOST["dept_no"].",".$rowCOST["dept_name"].",".$rowCOST['status'].",".$rowCOST["miles"].",".$rowCOST["charges"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowCOST['dept_no'];?></td>
															<td class="coldata"><? echo $rowCOST['dept_name'];?></td>
															<td class="coldata"><? echo $rowCOST['status'];?></td>
															<td class="coldata"><? echo $rowCOST['miles'];?></td>
															<td class="coldata" align="right"><? echo fn_NUMBER_FORMAT($rowCOST['charges'], "1234.56");?></td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
										</table>
									</td>
								</tr>
								<?	}if($iRECORD_COUNT>0) mysql_free_result($rsCOST);	?>
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
 
