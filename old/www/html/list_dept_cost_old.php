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
	$sMessage		=	"";
	
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{
		$sDeptID	=	$_POST["drpdept"];
		$sCriteriaSQL	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";
		$sCriteriaSQL2	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";
	}
	if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
				
		$sStartDate		=	substr($_POST["txtstartdate"],6, 4);
		$sStartDate		.=	"-".substr($_POST["txtstartdate"],0, 2);
		$sStartDate		.=	"-".substr($_POST["txtstartdate"],3, 2);
		
		$sEndDate		=	substr($_POST["txtenddate"],6, 4);
		$sEndDate		.=	"-".substr($_POST["txtenddate"],0, 2);
		$sEndDate		.=	"-".substr($_POST["txtenddate"],3, 2);
		
		
		$sCriteriaSQL	.=	" AND tbl_reservations.planned_return_day_time >= '".$sStartDate."' AND tbl_reservations.planned_return_day_time <= '".$sEndDate."'";
		//$sCriteriaSQL2	.=	" AND tbl_restricted_charges.reg_date >= '".$sStartDate."' AND tbl_restricted_charges.reg_date <= '".$sEndDate."'";
		//$sCriteriaSQL2	.=	" AND (tbl_restricted_charges.charge_month BETWEEN '".substr($sStartDate,5, 2)."' AND '".substr($sEndDate,5, 2)."') AND (tbl_restricted_charges.charge_year BETWEEN '".substr($sStartDate,0, 4)."' AND '".substr($sEndDate,0, 4)."')";
		$sCriteriaSQL2	.=	" AND (CAST(tbl_restricted_charges.charge_month AS UNSIGNED) BETWEEN CAST(".substr($sStartDate,5, 2)." AS UNSIGNED) AND CAST(".substr($sEndDate,5, 2)." AS UNSIGNED)) AND (CAST(tbl_restricted_charges.charge_year AS UNSIGNED) BETWEEN CAST(".substr($sStartDate,0, 4)." AS UNSIGNED) AND CAST(".substr($sEndDate,0, 4)." AS UNSIGNED))";
	}
	
	
	$sSQL	=	"SELECT total_cost.dept_no, total_cost.dept_name, SUM(total_cost.miles) AS miles, SUM(total_cost.charges) AS charges FROM ";
		
	$sSQL	.=	"( ";
	
	$sSQL	.=	"SELECT dept_cost.dept_no, dept_cost.dept_name, SUM(dept_cost.miles) AS miles, SUM(dept_cost.charges) AS charges FROM ";
	$sSQL	.=	"(SELECT tbl_departments.dept_id as dept_no, dept_name, ".
	"CASE WHEN SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) END AS miles, ".
	"CASE WHEN SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (cost_rate)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * cost_rate) END AS charges ".
	"FROM tbl_departments INNER JOIN tbl_reservations ON tbl_departments.dept_id = tbl_reservations.billing_dept ".
	"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
	"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"WHERE tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".$sCriteriaSQL." ".
	"GROUP BY tbl_reservations.res_id, tbl_departments.dept_id, tbl_departments.dept_name, tbl_vehicles.vehicle_id, tbl_reservations.planned_return_day_time, tbl_user.f_name, tbl_user.l_name) dept_cost ".
	"GROUP BY dept_cost.dept_no, dept_cost.dept_name";
	
	$sSQL	.=	" UNION ALL ";
	//total charges from restricted vehicles
	$sSQL	.=	"SELECT tbl_departments.dept_id AS dept_no, tbl_departments.dept_name, 0 AS miles, SUM(total_charge) AS charges ".
	"FROM tbl_restricted_charges INNER JOIN tbl_departments ON tbl_restricted_charges.dept_id = tbl_departments.dept_id ".
	"INNER JOIN tbl_vehicles ON tbl_restricted_charges.vehicle_id = tbl_vehicles.vehicle_id ".
	"WHERE tbl_restricted_charges.calc_method = 'Total Charge' ".$sCriteriaSQL2." GROUP BY tbl_departments.dept_id, tbl_departments.dept_name ";
	
	$sSQL	.=	" UNION ALL ";
	
	$sSQL	.=	"SELECT tbl_departments.dept_id AS dept_no, tbl_departments.dept_name, ".
	"CASE WHEN SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) < 10 THEN 10 ELSE SUM(CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) END AS miles, ".
	"CASE WHEN SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * (rate)) < 10 THEN 10 ELSE SUM((CAST(end_mileage AS UNSIGNED) - CAST(begin_mileage AS UNSIGNED)) * rate) END AS charges ".
	"FROM tbl_restricted_charges INNER JOIN tbl_departments ON tbl_restricted_charges.dept_id = tbl_departments.dept_id ".
	"INNER JOIN tbl_vehicles ON tbl_restricted_charges.vehicle_id = tbl_vehicles.vehicle_id ".
	"WHERE tbl_restricted_charges.calc_method = 'Readings' ".$sCriteriaSQL2." GROUP BY tbl_departments.dept_id, tbl_departments.dept_name ";
	
	
	$sSQL	.=	") total_cost GROUP BY total_cost.dept_no, total_cost.dept_name ";
	
	
	
	//print($sSQL);
	$rsCOST			=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT		=	mysql_num_rows($rsCOST);
	
	if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("no record found", "C_ERROR");}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Department Cost</title>
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
<script type="text/javascript">
<!--
function F_loadRollover(){} function F_roll(){}
//-->
</script>
<script type="text/javascript" src="../assets/rollover.js">
</script>
<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">

</head>
<body style="margin: 0px;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		<!--start header	-->
		<? include('inc_header.php');	?>
		
   		<!-- start side nav	-->
		<? include('inc_side_nav.php');	?>
		
		<!-- actual page	-->
        <td>
        	<table border="0" cellspacing="0" cellpadding="0" width="700">
            	<tr valign="top" align="left">
                	<td width="15" height="16"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
                	<td width="683"><img src="../assets/images/autogen/clearpixel.gif" width="683" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
               	</tr>
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="3" width="685">
                 		<table border="0" cellspacing="0" cellpadding="0" width="685" style="background-image: url('../assets/images/banner.gif'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								
								 		<tr>
								  			<td><img src="../assets/images/autogen/clearpixel.gif" width="18" height="8" border="0" alt=""></td>
								  			<td width="651" class="TextObject">
								   				<h1 style="margin-bottom: 0px;">DEPARTMENT COST Accumulated</h1>
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
                	<td width="683" class="TextObject" align="center">
						<form name="frm1" action="list_dept_cost.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="680" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label">Department<br />
													<?	fn_DEPARTMENT("drpdept", $sDeptID, "170", "1", "ALL");?>
												</td>
												<td class="label">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
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
												<td width="100" class="colhead">Miles</td>
												<td width="100" class="colhead" align="right">Charges</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowCOST	=	mysql_fetch_array($rsCOST)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowCOST["dept_no"].",".$rowCOST["dept_name"].",".$rowCOST["miles"].",".$rowCOST["charges"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowCOST['dept_no'];?></td>
															<td class="coldata"><? echo $rowCOST['dept_name'];?></td>
															<td class="coldata"><? echo $rowCOST['miles'];?></td>
															<td class="coldata" align="right"><? echo number_format($rowCOST['charges'], 2, '.', '');?></td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
										</table>
									</td>
								</tr>
								<?	}mysql_free_result($rsCOST);	?>
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
 