<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	//$sDays		=	"";
	$iVehicle_ID	=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sCOL_SQL		=	" DATEDIFF(CURDATE(), tbl_reservations.planned_return_day_time) AS time_period, ";
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		//if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")			{$sDays	=	$_POST["drpdays"];		$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_reservations.planned_return_day_time) <= ".$sDays;}
		if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")	{$iVehicle_ID	=	mysql_real_escape_string($_POST["drpvehicle"]);	$sCriteriaSQL	.=	" AND tbl_reservations.vehicle_id = ".$iVehicle_ID;}
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){	
			$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));		
			$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));
	
			
			//$sCriteriaSQL	.=	" AND DATE(tbl_reservations.planned_return_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
			$sCriteriaSQL	.=	" AND (DATE(tbl_reservations.planned_return_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
			//$sCriteriaSQL	.=	" AND DATE(tbl_trip_details.reg_date) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
			$sCOL_SQL		=	" DATEDIFF('".$sEndDate."', tbl_reservations.planned_return_day_time) AS time_period, ";
		}
		
		
		
		$sSQL	=	"SELECT vehicle_no, DATE_FORMAT(MAX(tbl_trip_details.reg_date), '%m/%d/%Y') AS last_mileage_date, ".$sCOL_SQL." SUM(end_mileage - begin_mileage) AS mileage, MAX(end_mileage) AS end_back_mileage ".
		"FROM tbl_reservations INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"WHERE tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".$sCriteriaSQL." ".
		"GROUP BY vehicle_no";
		
				
		
		//print($sSQL);
		$rsMILEAGE			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT		=	mysql_num_rows($rsMILEAGE);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("no record found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Vehicle Mileage</title>
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
								   				<h1 style="margin-bottom: 0px;">VEHICLE MILEAGE</h1>
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
						<form name="frm1" action="list_vehicle_mileage.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="200">
													Vehicle No:<br />
													<?	fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");?>
												</td>
												<td class="label">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
																								
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_vehicle_mileage.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Vehicle_No,Time_Period_(days),Last_Mileage,Last_Mileage_Date,Total_Mileage'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Vehicle No</td>
												<td width="100" class="colhead">Time Period (days)</td>
												<td width="150" class="colhead">Last Mileage</td>
												<td width="150" class="colhead">Date. Last Mileg</td>
												<td width="150" class="colhead">Total Mileage</td>
											</tr>
											<?		$listed	=	0;
													while($rowMILEAGE	=	mysql_fetch_array($rsMILEAGE)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowMILEAGE["vehicle_no"].",".$rowMILEAGE["time_period"].",".$rowMILEAGE["end_back_mileage"].",".$rowMILEAGE["last_mileage_date"].",".$rowMILEAGE["mileage"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowMILEAGE['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowMILEAGE['time_period'];?></td>
															<td class="coldata"><? echo $rowMILEAGE['end_back_mileage'];?></td>
															<td class="coldata"><? echo $rowMILEAGE['last_mileage_date'];?></td>
															<td class="coldata"><? echo $rowMILEAGE['mileage'];?></td>
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											
											?>
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsMILEAGE);	?>
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
 