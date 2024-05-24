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
	//$iVehicle_ID	=	"";
	$iRESERVED_BY	=	"";
	$iRESVD_USER	=	"";
	$sCriteriaSQL	=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["txtresvno"]) && $_POST["txtresvno"]!="")	{$iRES_ID		=	$_POST["txtresvno"];	$sCriteriaSQL	.=	" AND tbl_reservations.res_id = ".$iRES_ID;}
		//if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")	{$iVehicle_ID	=	$_POST["drpvehicle"];	$sCriteriaSQL	.=	" AND tbl_reservations.vehicle_id = ".$iVehicle_ID;}	
		//if(isset($_POST["drpreservedby"]) && $_POST["drpreservedby"]!="")	{$iRESERVED_BY	=	$_POST["drpreservedby"];	$sCriteriaSQL	.=	" AND tbl_reservations.user_id = ".$iRESERVED_BY;}	
		if(isset($_POST["drpdays"]) && $_POST["drpdays"]!=""){
			$sDays			=	$_POST["drpdays"];
			$sCriteriaSQL	.=	" AND (DATEDIFF(CURDATE(), tbl_reservations.res_delete_datetime) < ".$sDays." AND DATEDIFF(CURDATE(), tbl_reservations.res_delete_datetime) >0) " ;
		}
		
		$sSQL	=	"SELECT res_id, res_delete_datetime, CONCAT(d_u.f_name, ' ', d_u.l_name) AS user_name, CONCAT(resrvd_by.f_name, ' ', resrvd_by.l_name) AS resrvd_by_name, ".
		"CONCAT('Vehicle: ', vehicle_no, ' From: ', DATE_FORMAT(planned_depart_day_time, '%m/%d/%Y %l:%i %p'), ' To: ', DATE_FORMAT(planned_return_day_time, '%m/%d/%Y %l:%i %p')) AS reservation, ".
		"destination, d.dept_name ".
		"FROM tbl_reservations ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_user d_u ON tbl_reservations.res_delete_user = d_u.user_id ".
		"INNER JOIN tbl_user resrvd_by ON tbl_reservations.user_id = resrvd_by.user_id ".
		"INNER JOIN tbl_departments d ON resrvd_by.dept_id = d.dept_id ".
		"WHERE reservation_cancelled = 1 ".$sCriteriaSQL." ".
		"ORDER BY res_id DESC";
		
		//"INNER JOIN tbl_user ON tbl_abandon_trips.user_id = tbl_user.user_id ".
		//print($sSQL);
		$rsABANOD_TRIP			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsABANOD_TRIP);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no deleted trip(s) found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Deleted Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/popup.js"></script>
<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}

function fn_LOAD_PENDING_TRIP (tid) {
	document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
	document.getElementById('contactArea').innerHTML	=	"<iframe src='pending_trip_status.php?resid="+tid+"' width='600' height='513' frameborder='0'>Browser not supportive</iframe>";
	centerPopup();
	loadPopup();
	
}

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
								   				<h1 style="margin-bottom: 0px;">DELETED TRIPS</h1>
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
						<form name="frm1" action="list_deleted.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="120">Resv No:<br />
													<input type="text" name="txtresvno" value="<? echo $iRES_ID?>" style="width:120px;" onKeyDown="return validateNumber(event);" />
												</td>
												<!--<td class="label" width="150">
													Resvrd By:<br />
													<?	//fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");
														//fn_DISPLAY_USERS('drpreservedby', $iRESERVED_BY, "160", "1", "--All--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_SERVICETCH);
													?>
												</td>-->
												<td class="label" width="200">Days:<br />
													<?	fn_DAYS("drpdays", $sDays, "150", "1", "ALL");	?>
												</td>
												<td><br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_deleted_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Resrv. No,Reservation,Destination,Deleted_Date_Time,Resrvd_By,Resrved_By_Dept,Delete_By'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Res #.</td>
												<td width="300" class="colhead">Reservation</td>
												<td width="300" class="colhead">Destination</td>
												<td width="100" class="colhead">Date Time</td>
												<td width="100" class="colhead">Resvrd By</td>
												<td width="100" class="colhead">Resvrd By Dept</td>
												<td width="100" class="colhead">Deleted By</td>
												<td width="20" class="colhead">Act.</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowABANDON	=	mysql_fetch_array($rsABANOD_TRIP)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowABANDON["res_id"].",".$rowABANDON['reservation'].",".stripslashes(str_replace(","," ",$rowABANDON['destination'])).",".$rowABANDON['res_delete_datetime'].",".$rowABANDON['resrvd_by_name'].",".$rowABANDON['dept_name'].",".$rowABANDON['user_name']));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowABANDON['res_id'];?></td>
															<td class="coldata"><? echo $rowABANDON['reservation'];?></td>
															<td class="coldata"><? echo stripslashes($rowABANDON['destination']);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowABANDON['res_delete_datetime'],2);?></td>
															<td class="coldata"><? echo $rowABANDON['resrvd_by_name'];?></td>
															<td class="coldata"><? echo $rowABANDON['dept_name'];?></td>
															<td class="coldata"><? echo $rowABANDON['user_name'];?></td>
															<td class="coldata"><a href="javascript:void(0);" onclick="fn_LOAD_PENDING_TRIP(<?Php echo $rowABANDON['res_id'];?>);">V</a></td>
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
<div id="popupContact" style="background-color:#fff;">
		<div id="contactArea">asdfasdf</div>
		<div style="text-align:center; width:100px; margin:0 auto;"><input type="button" name="btnclose" value="CLOSE" class="Button" id="popupContactClose" style="width:100px;" /></div>
	</div>
<div id="backgroundPopup"></div>