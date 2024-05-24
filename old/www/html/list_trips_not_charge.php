<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iUSER_ID		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCRITERIA_SQL	=	"";
	$sCRITERIA_SQL_2=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sDeptID		=	0;
	$sAction		=	"";
	$sSORT_ORDER	=	"q.resrvd_by ASC";
	
	if(isset($_POST["action"]) && $_POST["action"]!="")				{$sAction		=	$_POST["action"];}
	
	
	
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!=""){	$sDeptID	=	mysql_real_escape_string($_POST["drpdept"]);		$sCRITERIA_SQL_2	.=	" AND tbl_departments.dept_id = '".$sDeptID."'";	}
	if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
				
		$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));				
		$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));
	
		$sCRITERIA_SQL_2.=	" AND DATE(q.planned_return_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
	}
	
	
	
	if($sAction=="search"){
		
		
		$iCURRENT_DAY_NUMBER	=	0;
		$iCURRENT_DAY_NUMBER	=	 date('N', strtotime(date('Y-m-d')));
		$sCURRENT_DATE			=	date('Y-m-d');
		
		if($iCURRENT_DAY_NUMBER==1)	$sCRITERIA_SQL	=	"96 AND 150"; else $sCRITERIA_SQL	=	"48 AND 95";
										
		$sSQL	=	"SELECT  q.res_id, q.planned_depart_day_time, q.planned_return_day_time, q.vehicle_no, q.resrvd_by, q.tag, q.dept_name FROM (";
		
		
		$sSQL	.=	"SELECT nr.res_id, nr.planned_depart_day_time, nr.planned_return_day_time, nr.vehicle_no, nr.resrvd_by, ' ' AS tag, nr.dept_name FROM (";
		
		$sSQL	.=	"SELECT r.res_id, r.planned_depart_day_time, r.planned_return_day_time, v.vehicle_no, CONCAT(resvd_driver.f_name, ' ', resvd_driver.l_name) AS resrvd_by, d.dept_name ".
		"FROM tbl_reservations r ".
		"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
		"INNER JOIN tbl_user resvd_driver ON r.user_id = resvd_driver.user_id ".
		"INNER JOIN tbl_departments d ON r.billing_dept = d.dept_id ".
		"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
		"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND r.no_cost = 1 ".
		"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 5)";
		
		$sSQL	.=	")  nr WHERE ((TIME_TO_SEC(TIMEDIFF('".$sCURRENT_DATE." 00:01:00', nr.planned_return_day_time))/3600) BETWEEN 0 AND 47)";
		
		$sSQL	.=	" UNION ALL ";
		
		$sSQL	.=	"SELECT o_r.res_id, o_r.planned_depart_day_time, o_r.planned_return_day_time, o_r.vehicle_no, o_r.resrvd_by, 'overdue' AS tag, o_r.dept_name FROM (";
		
		$sSQL	.=	"SELECT r.res_id, r.planned_depart_day_time, r.planned_return_day_time, v.vehicle_no, CONCAT(resvd_driver.f_name, ' ', resvd_driver.l_name) AS resrvd_by, d.dept_name ".
		"FROM tbl_reservations r ".
		"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
		"INNER JOIN tbl_user resvd_driver ON r.user_id = resvd_driver.user_id ".
		"INNER JOIN tbl_departments d ON r.billing_dept = d.dept_id ".
		"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
		"WHERE t.res_id IS NULL AND a.res_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0 AND r.no_cost = 1 ".
		"AND (DATEDIFF(CURDATE(), DATE(r.planned_depart_day_time)) BETWEEN 1 AND 5)";
		
		$sSQL	.=	") o_r WHERE ((TIME_TO_SEC(TIMEDIFF('".$sCURRENT_DATE." 00:01:00', o_r.planned_return_day_time))/3600) BETWEEN ".$sCRITERIA_SQL.")";
		
		$sSQL	.=	") q ORDER BY ".$sSORT_ORDER;
		
		//print($sSQL);
		$rsTRIP		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsTRIP);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no trips are found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Trips Not Charged</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
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
								   				<h1 style="margin-bottom: 0px;">TRIPS NOT CHARGED</h1>
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
						<form name="frm1" action="list_trips_not_charge.php" method="post">
							<input type="hidden" name="action" value="<?=$sAction?>"	/>
							
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="200">Department<br />
													<?	fn_DEPARTMENT("drpdept", $sDeptID, "180", "1", "ALL");?>
												</td>
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:150px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="q.resrvd_by ASC" 		<? if($sSORT_ORDER == "q.resrvd_by ASC") echo "selected";?>>Resrvd By A-Z</option>
														<option value="q.resrvd_by DESC" 	<? if($sSORT_ORDER == "q.resrvd_by DESC") echo "selected";?>>Resrvd By Z-A</option>
														<option value="q.planned_depart_day_time ASC"  	<? if($sSORT_ORDER == "q.planned_depart_day_time ASC") echo "selected";?>>Depart Date A-Z</option>
														<option value="q.planned_depart_day_time DESC"  	<? if($sSORT_ORDER == "q.planned_depart_day_time DESC") echo "selected";?>>Depart Date Z-A</option>
													</select>
												</td>
												<td><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Resrv_No,Charge_Dept,MNC,Depart_DateTime,Resrv_By,Destination'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Resrv No</td>
												<td width="170" class="colhead">Charge Dept</td>
												<td width="30" class="colhead">MNC</td>
												<td width="100" class="colhead">Depart Date</td>
												<td width="100" class="colhead">Resrvd By</td>
												<td width="150" class="colhead">Destination</td>
												
											</tr>
											<?		$listed	=	0;	
													while($rowTRIP	=	mysql_fetch_array($rsTRIP)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
															fputcsv($fp, explode(',', $rowTRIP["res_id"].",".$rowTRIP["billing_dept"].",".$rowTRIP["mnc"].",".$rowTRIP['planned_depart_day_time'].",".$rowTRIP["resrvd_by"].",".str_replace(',', ' ', stripslashes($rowTRIP["destination"]))));
														}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowTRIP['res_id'];?></td>
															<td class="coldata"><? echo $rowTRIP['billing_dept'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowTRIP['planned_depart_day_time'],2);?></td>
															<td class="coldata"><? echo $rowTRIP['resrvd_by'];?></td>
															<td class="coldata"><? echo stripslashes($rowTRIP['destination']); ?></td>

															<td class="coldata" align="center"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LOG_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('Are you sure to delete this login-logout routine for the user!')) {fn_DELETE_LOG(<? echo $rowTRIP['log_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsTRIP);	?>
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
 