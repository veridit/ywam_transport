<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sDays			=	"";
	$iPending		=	"";
	$iVehicle_ID	=	"";
	$sCancelStatus	=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sAction		=	"";
	$iRESERVATION_ID=	"";
	
	if(isset($_POST["resid"]) && $_POST["resid"]!="")	{$iRESERVATION_ID		=		$_POST["resid"];}
	
	if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")	{$iVehicle_ID	=	$_POST["drpvehicle"];	$sCriteriaSQL	.=	" AND tbl_reservations.vehicle_id = ".$iVehicle_ID;}
	if(isset($_POST["drpdays"]) && $_POST["drpdays"]!=""){
		$sDays			=	$_POST["drpdays"];
		if($_POST["drpdays"]=="0")
			$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_reservations.planned_depart_day_time) = ".$sDays;
		else
			$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_reservations.planned_depart_day_time) <= ".$sDays;
	}
	if(isset($_POST["drppending"]) && $_POST["drppending"]!="")	{$iPending		=	$_POST["drppending"];	$sCriteriaSQL	.=	" AND tbl_trip_details.end_gas_percent IS NULL";}
	
	/*if($_SESSION["User_Group"]==$iGROUP_TM)	{
		if(isset($_POST["drpcancel"]) && $_POST["drpcancel"]!=""){
			$sCancelStatus	=	$_POST["drpcancel"];		$sCriteriaSQL	.=	" AND tbl_reservations.reservation_cancelled = ".$sCancelStatus;
		}
	}*/
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
		$sSQL		=	"DELETE FROM tbl_trip_details WHERE res_id = ".$iRESERVATION_ID;
		$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL		=	"DELETE FROM tbl_reservations WHERE res_id = ".$iRESERVATION_ID;
		$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage	=	fn_Print_MSG_BOX("trip has been deleted","C_SUCCESS");
	}
	
	$sSQL	=	"SELECT tbl_reservations.res_id, vehicle_no, CONCAT(l_name, ' ', f_name) AS user_name, planned_passngr_no, planned_depart_day_time, ".
	"planned_return_day_time, CASE WHEN tbl_trip_details.end_gas_percent IS NULL THEN 'Pending Trip' ELSE end_gas_percent END AS end_gas ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"WHERE 1=1 ".$sCriteriaSQL." ORDER BY tbl_reservations.res_id DESC";
	//print($sSQL);
	$rsRES			=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsRES);
	
	if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("no reservation found", "C_ERROR");}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>List Reservations</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
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
function fn_DELETE_TRIP(iResID){
	document.frm1.resid.value=iResID;
	document.frm1.action.value='delete';
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
								   				<h1 style="margin-bottom: 0px;">LIST ALL TRIPS</h1>
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
						<form name="frm1" action="list_reservations.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="resid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="680" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="200">Days:<br />
													<?	fn_DAYS("drpdays", $sDays, "150", "1", "ALL");	?>
												</td>
												<?	//if($_SESSION["User_Group"]==$iGROUP_TM)	{?>
												<!--<td class="label">TM Action:<br />
													<?
														$arrCANCELLED[0][0]	=	"0";
														$arrCANCELLED[0][1]	=	"Not-Cancelled";
														$arrCANCELLED[1][0]	=	"1";
														$arrCANCELLED[1][1]	=	"Cancelled";
													?>
													<select name="drpcancel" size="1" style="width:100px;">
														<option value="">All</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrCANCELLED[$iCOUNTER][0]?>" <? if($arrCANCELLED[$iCOUNTER][0]==$sCancelStatus) echo "selected";?>><?=$arrCANCELLED[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>-->
												<? //	}?>
												<td class="label" width="200">
													Vehicle No:<br />
													<?	fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");?>
												</td>
												<td class="label" width="200">Status:<br />
													<select name="drppending" size="1" style="width:150px;">
														<option value="" selected>--All--</option>
														<option value="1" <? if($iPending=="1") echo "selected";?>>Pending</option>
													</select>
												</td>
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Res. No</td>
												<td width="100" class="colhead">Driver</td>
												<td width="50" class="colhead">Vehicle</td>
												<!--<td width="30" class="colhead">Psng.</td>-->
												<td width="100" class="colhead">End Gas</td>
												<td width="120" class="colhead">Sched. Depart Date</td>
												<td width="120" class="colhead">Sched. Return Date</td>
												<td width="50" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;
													while($rowRES	=	mysql_fetch_array($rsRES)){
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowRES['res_id'];?></td>
															<td class="coldata"><? echo $rowRES['user_name'];?></td>
															<td class="coldata"><? echo $rowRES['vehicle_no'];?></td>
															<!--<td class="coldata"><? echo $rowRES['planned_passngr_no'];?></td>-->
															<td class="coldata"><? echo $rowRES['end_gas'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_depart_day_time'], 2);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_return_day_time'], 2);?></td>
															<td class="coldata" align="center">
																<a href="edit_reservation.php?resid=<? echo $rowRES['res_id'];?>">view</a>
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RES_DELETE)){?>
																	<a href="javascript:void(0);" onClick="fn_DELETE_TRIP(<? echo $rowRES['res_id'];?>);">/ delete</a>
																<?	}?>
															</td>
															
														</tr>
											<?			}$listed++;	
													}?>
								
										</table>
									</td>
								</tr>
								<?	}mysql_free_result($rsRES);	?>
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
 