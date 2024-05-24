<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iVEHICLE_NO	=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$sCriteriaSQL_2	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
			if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{
				$iVEHICLE_NO	=	$_POST["drpvehicle"];		
				$sCriteriaSQL	.=	" AND tbl_vehicles.vehicle_id = ".$iVEHICLE_NO;
				$sCriteriaSQL_2	.=	" AND tbl_reservations.vehicle_id = ".$iVEHICLE_NO;
			}
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
						
				$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
				$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);
				$sCriteriaSQL	.=	" AND DATE(tbl_shop_tasks.work_start_date) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
			}
			
			//============================================================
			$sSQL	=	"SELECT shop_task.vehicle_id, shop_task.vehicle_no, shop_task.year_manuf, shop_task.v_type, shop_task.brand_name, SUM(shop_task.total_cost) AS total_cost, SUM(shop_task.last_mileage) AS last_mileage FROM ";
			
			$sSQL	.=	"(SELECT tbl_vehicles.vehicle_id, tbl_vehicles.vehicle_no, tbl_vehicles.year_manuf, tbl_vehicle_type.v_type, tbl_vehicle_brand.brand_name, ".
			"SUM(tbl_shop_tasks.total_cost) AS total_cost, 0 AS last_mileage FROM tbl_shop_tasks ".
			"INNER JOIN tbl_vehicles ON tbl_shop_tasks.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
			"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
			"WHERE 1 = 1 ".$sCriteriaSQL." ".
			"GROUP BY tbl_vehicles.vehicle_id, tbl_vehicles.year_manuf, tbl_vehicle_type.v_type, tbl_vehicle_brand.brand_name ";
			
			$sSQL	.=	"UNION ALL ";
			
			$sSQL	.=	" SELECT tbl_vehicles.vehicle_id, tbl_vehicles.vehicle_no, tbl_vehicles.year_manuf, tbl_vehicle_type.v_type, tbl_vehicle_brand.brand_name, ".
			"0 AS total_cost, tbl_trip_details.end_mileage AS last_mileage ".
			"FROM tbl_trip_details INNER JOIN  (SELECT tbl_vehicles.vehicle_id, MAX(tbl_reservations.res_id) AS max_res FROM tbl_reservations ".
			"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
			"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"WHERE 1 = 1 ".$sCriteriaSQL_2." GROUP BY tbl_reservations.vehicle_id) reservations ON tbl_trip_details.res_id = reservations.max_res ".
			"INNER JOIN tbl_vehicles ON reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
			"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
			") shop_task ".
			"GROUP BY shop_task.vehicle_id, shop_task.vehicle_no, shop_task.year_manuf, shop_task.v_type, shop_task.brand_name ";
				
			//============================================================
			
			//print($sSQL);
			$rsCOST			=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT		=	mysql_num_rows($rsCOST);
			
			if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("no record found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Vehicle Cost</title>
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
								   				<h1 style="margin-bottom: 0px;">VEHICLE COST Accumulated</h1>
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
						<form name="frm1" action="list_vehicle_cost.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="130">Vehicle No:<br />
													<?	fn_VEHICLE('drpvehicle', $iVEHICLE_NO, "100", "1", "--All--");?>
												</td>
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td width="60"><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td colspan="7"><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_vehicle_cost.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Vehicle_No,Make,Type,Total_Cost,Last_Mileage'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Vehicle No</td>
												<td width="100" class="colhead">Make</td>
												<td width="100" class="colhead">Type</td>
												<td width="100" class="colhead" align="right">Cost</td>
												<td width="100" class="colhead" align="right">Last Mileage</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowCOST	=	mysql_fetch_array($rsCOST)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowCOST["vehicle_no"].",".$rowCOST["brand_name"].",".$rowCOST["v_type"].",".$rowCOST["total_cost"].",".$rowCOST["last_mileage"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowCOST['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowCOST['brand_name'];?></td>
															<td class="coldata"><? echo $rowCOST['v_type'];?></td>
															<td class="coldata" align="right"><? echo number_format($rowCOST['total_cost'], 2, '.', '');?></td>
															<td class="coldata" align="right"><? echo $rowCOST['last_mileage'];?></td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsCOST);	?>
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
 