<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_redirect.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$sAction		=	"";
	$iPENDING_RESERVATION_ID	=	"";

	
	
	//searching criterias
	$iTIME_1		=	"00:00";		$iTIME_2		=	"00:00";
	
	$sTimePickerDate1=date('m/d/Y',strtotime(date("Y-m-d")." +1 day")); //date('m/d/Y');
	if(isset($_POST["txtdeptdatetime"]) && $_POST["txtdeptdatetime"]!="")	$sTimePickerDate1 = $_POST["txtdeptdatetime"];
	
	
	if(isset($_POST["action"])	&& $_POST["action"]!="")				$sAction		=	$_POST["action"];

			$arrTIME[0][0]	=	"00:00";	$arrTIME[0][1]	=	"12";		$arrTIME[1][0]	=	"01:00";	$arrTIME[1][1]	=	"01";
			$arrTIME[2][0]	=	"02:00";	$arrTIME[2][1]	=	"02";		$arrTIME[3][0]	=	"03:00";	$arrTIME[3][1]	=	"03";
			$arrTIME[4][0]	=	"04:00";	$arrTIME[4][1]	=	"04";		$arrTIME[5][0]	=	"05:00";	$arrTIME[5][1]	=	"05";
			$arrTIME[6][0]	=	"06:00";	$arrTIME[6][1]	=	"06";		$arrTIME[7][0]	=	"07:00";	$arrTIME[7][1]	=	"07";
			$arrTIME[8][0]	=	"08:00";	$arrTIME[8][1]	=	"08";		$arrTIME[9][0]	=	"09:00";	$arrTIME[9][1]	=	"09";
			$arrTIME[10][0]=	"10:00";	$arrTIME[10][1]=	"10";		$arrTIME[11][0]=	"11:00";	$arrTIME[11][1]=	"11";
			$arrTIME[12][0]=	"12:00";	$arrTIME[12][1]=	"12";		$arrTIME[13][0]=	"13:00";	$arrTIME[13][1]=	"01";
			$arrTIME[14][0]=	"14:00";	$arrTIME[14][1]=	"02";		$arrTIME[15][0]=	"15:00";	$arrTIME[15][1]=	"03";
			$arrTIME[16][0]=	"16:00";	$arrTIME[16][1]=	"04";		$arrTIME[17][0]=	"17:00";	$arrTIME[17][1]=	"05";
			$arrTIME[18][0]=	"18:00";	$arrTIME[18][1]=	"06";		$arrTIME[19][0]=	"19:00";	$arrTIME[19][1]=	"07";
			$arrTIME[20][0]=	"20:00";	$arrTIME[20][1]=	"08";		$arrTIME[21][0]=	"21:00";	$arrTIME[21][1]=	"09";
			$arrTIME[22][0]=	"22:00";	$arrTIME[22][1]=	"10";		$arrTIME[23][0]=	"23:00";	$arrTIME[23][1]=	"11";
	
	
	
			$iVEHICLE_COL_WIDTH	=	"45";
			$iDAY_COL_WIDTH		=	"230";
			$iTIME_SLOT_WIDTH	=	"20";
			$iCHART_ROW_HEIGHT	=	"10";
			
			$sReserved_COLOR	=	"#FF6633";
			$sFREE_COLOR		=	"#FFF";
			$sPULLED_COLOR		=	"#000000";
			
			$sCALENDAR_DAYS		=	3;
			//starting date
			
			if(isset($_POST["scurrentdate"]) && $_POST["scurrentdate"]!="")	$sToday_Date = $_POST["scurrentdate"];	else	$sToday_Date = date("Y-m-d");
			if(strtotime($sToday_Date)<strtotime(date('Y-m-d'))) 		$sToday_Date		=	date("Y-m-d");	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Pending Trips Graphical Representation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<!-- jQuery -->
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script language="JavaScript">
	var ol_fgcolor	=	"#FFEBD7";
	var ol_bgcolor	=	"#CA0000";
	var ol_textfont	=	"Arial,    Helvetica,    Geneva,    Sans-serif";
	var ol_textsize	=	"2";
	var ol_wrap		= 	1;
	var ol_width	=	"90";
	
function fn_VIEW_RESERVATIONS(sDate){

	if(typeof sDate== 'undefined'){
		if(document.frm1.txtdeptdatetime.value!=""){
			var sStartDate	=	document.frm1.txtdeptdatetime.value;
			sDate	=	sStartDate.substr(6,4)+'-'+sStartDate.substr(0,2)+'-'+sStartDate.substr(3,2);
			//alert(sDate);
		}else{
			var time	=	new Date();
			sDate	=		time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate();
		}
	}
	
	document.frm1.scurrentdate.value = sDate;
	document.frm1.action.value='viewreservations';
	document.frm1.submit();
			
}




</script>


<script type="text/javascript" src="./js/popup.js"></script>
<script language="JavaScript">
	//alert('aaa');
		function fn_LOAD_PENDING_TRIP (tid) {
			//document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
			$('#contactArea').html("<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>");
			//document.getElementById('contactArea').innerHTML	=	"<iframe src='pending_trip_status.php?resid="+tid+"' width='600' height='513' frameborder='0'>Browser not supportive</iframe>";
			$('#contactArea').html("<iframe src='pending_trip_status.php?resid="+tid+"' width='600' height='505' frameborder='0'>Browser not supportive</iframe>");
			centerPopup();
			loadPopup();
			
		}
</script>
<style type="text/css">
	.time_slot{font-weight:bold; padding-left:0px; font-szie:9pt;}
</style>
<script type="text/javascript" src="./js/overlib_mini.js"></script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
</head>
<body style="margin: 0px;">
<div id="overDiv" style="Z-INDEX: 1000; VISIBILITY: hidden; POSITION: absolute"></div>
<div align="center">

        	<table border="0" cellspacing="0" cellpadding="0" width="1000" style="background-color:#fff;">
            
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="3" width="985">
                 		<table border="0" cellspacing="0" cellpadding="0" width="985" style="background-image: url('../assets/images/banner.gif'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%" align="center"><h1 style="margin-bottom: 0px;">PENDING TRIPS</h1>
									
                   				</td>
                  			</tr>
                 		</table>
                	</td>
               	</tr>
				
               	<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
			   	<tr valign="top" align="left"><td colspan="4"><? echo	fn_Print_MSG_BOX("<li>Vehicle is available where squares are white</li>and<br /><li>Pending Reservation number is shown by clicking on a red square", "C_SUCCESS")?></td></tr>
				<tr valign="top" align="left"><td colspan="4">&nbsp;</td></tr>
				
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
                	<td width="983" class="TextObject" align="center">
					
						<form name="frm1" action="pending_trips_chart.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="scurrentdate" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="970" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td align="center" valign="top">
										<table cellpadding="0" cellspacing="5" border="0">
											<tr>
											<td>
											<input type="hidden" name="txtdeptdatetime" id="txtdeptdatetime" style="width:80px;" value="<? echo $sTimePickerDate1;?>" onChange="fn_VIEW_RESERVATIONS();" />
											</td>
											</tr>								
										</table>
									
										<table width="100%">
											<tr>
												<td align="left" width="250" class='left_side_menu'>
																										
													<? 	if(strtotime($sToday_Date)>strtotime(date('Y-m-d'))) {
														$sPREVIOUS_NAV = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " -".$sCALENDAR_DAYS." day");
													?>	<a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sPREVIOUS_NAV)?>');">PREVIOUS</a>
													<?	}
														$sNEXT_NAV = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +2 day");
													?>
												</td>
												<td align="center" class='left_side_menu'><a href="javascript:void(0);" onClick="window.parent.focus(); window.close();">CLOSE THIS GRAPH</a></td>													
												<td align="right" width="250" class='left_side_menu'><a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sNEXT_NAV)?>');">NEXT</a></td>
												
											</tr>
											
										</table>
										<br /><br />
										
										<table cellpadding="0" cellspacing="0" border="0" class="box">
											<tr>
												<td>
												
													<div style="width:970px; height:650px; overflow:auto; scrollbars:auto;" align="center">
													<table cellpadding="0" cellspacing="0" border="0">
														
														<tr>
															<!-- PRINTING HEADERS FOR V NO-->
															<td width="<?=$iVEHICLE_COL_WIDTH?>" align="center" class="colhead">V.NO</td>	<!--time column	-->
															<?	for($iDays=0;$iDays<=2;$iDays++){
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");?>
																	<td width="<?=$iDAY_COL_WIDTH?>" align="center">		<!-- PRINTING HEADERS FOR DAYS-->
																		
																		<table cellpadding="0" cellspacing="0" border="0">
																			<tr><td class="colhead" align="center"><? echo fn_cDateMySql(date('Y-m-d',$sNext_Date),3);	?></td></tr>
																			<tr>
																				<td>		<!-- PRINTING HEADERS FOR TIME SLOTS-->
																					<table cellpadding="0" cellspacing="0" border="0">
																						<tr>
																							<?	for($iCounter=4;$iCounter<=22;$iCounter++){	?>
																								<td width="<? echo $iTIME_SLOT_WIDTH;?>" class="colhead" style="font-weight:bold; padding-left:0px; font-size:8pt;"><? echo $arrTIME[$iCounter][1]?></td>
																							<?	}?>
																						</tr>
																					</table>
																					
																				</td>
																			</tr>
																		</table>
																		
																	</td>
															<?	}	?>
														</tr>
														<!-- NOW EXTRACT ALL RESERVATIONS FOR THESE CURRENT 7 DAYS	-->
														<?	$sSQL	=	"SELECT v.vehicle_id, v.vehicle_no FROM tbl_vehicles v WHERE v.vehicle_id NOT IN (SELECT s.vehicle_id FROM tbl_srvc_resvs s WHERE s.is_cancelled = 0 AND service_type = 'permanent') AND v.sold = 0 ORDER BY (vehicle_no+0)";
															$arrVEHICLE_PENDING_TRIPS		=			array();
															$iBOUND_TRACKER					=			0;
															
															$rsVEHICLES	=	mysql_query($sSQL) or die(mysql_error());
															if(mysql_num_rows($rsVEHICLES)>0){
																while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
																	$arrVEHICLE_PENDING_TRIPS		=	fn_CHECK_PENDING_TRIPS($rowVEHICLE['vehicle_id'], $sToday_Date, date('Y-m-d',$sNext_Date), session_id());
																	$iBOUND_TRACKER					=			0;
																	//print_r($arrVEHICLE_PENDING_TRIPS);
																	/*foreach($arrVEHICLE_PENDING_TRIPS as $key => $value) {
																		if(in_array('2013-02-19 12:00:00', $value)) print('KEY='.$key);
																	}*/
																	
														?>
														<tr>															
															<td align="right" height="<? echo $iCHART_ROW_HEIGHT;?>" class="coldata leftbox" style="color:#000; font-weight:bold;"><? echo $rowVEHICLE['vehicle_no'];?></td>
															
															<?
																for($iDays=0;$iDays<=2;$iDays++){
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");
															?>
																<td>
																	<table cellpadding="0" cellspacing="0" border="0" width="100%">
																		<tr>
																			<?	for($iHourCounter=4;$iHourCounter<=22;$iHourCounter++){	
																					$iSELECTED_PENDING_SLOT			=	-1;
																					for($i=$iBOUND_TRACKER;$i<count($arrVEHICLE_PENDING_TRIPS);$i++){
																						if(strtotime($arrVEHICLE_PENDING_TRIPS[$i]['pending_trips'])==strtotime(date('Y-m-d',$sNext_Date)." ".$arrTIME[$iHourCounter][0].":00")) { $iSELECTED_PENDING_SLOT	=	$i; $iBOUND_TRACKER	=	$i; $iBOUND_TRACKER++;	break; }
																					}
																					
																					if($iSELECTED_PENDING_SLOT!=-1){
																						
																						if($arrVEHICLE_PENDING_TRIPS[$iSELECTED_PENDING_SLOT]['res_type']=="normal"){
																							if($_SESSION["User_Group"]==$iGROUP_TM || $_SESSION["User_Group"]==$iGROUP_TC){
																								echo "<td align='center' width ='".$iTIME_SLOT_WIDTH."' height='".$iCHART_ROW_HEIGHT."' style='background-color:".$sReserved_COLOR."' class='coldata' onmouseover='return overlib(\"Resv No:&nbsp;".$arrVEHICLE_PENDING_TRIPS[$iSELECTED_PENDING_SLOT]['res_id']."\");' onmouseout='return nd();' onclick='fn_LOAD_PENDING_TRIP(".$arrVEHICLE_PENDING_TRIPS[$iSELECTED_PENDING_SLOT]['res_id'].");'>&nbsp;</td>";
																							}else{
																								echo "<td align='center' width ='".$iTIME_SLOT_WIDTH."' height='".$iCHART_ROW_HEIGHT."' style='background-color:".$sReserved_COLOR."' class='coldata' onmouseover='return overlib(\"Resv No:&nbsp;".$arrVEHICLE_PENDING_TRIPS[$iSELECTED_PENDING_SLOT]['res_id']."\");' onmouseout='return nd();'>&nbsp;</td>";
																							}	
																						}else{
																							$sRESTORE_PULL_DATE		=	fn_GET_FIELD("tbl_srvc_resvs", $arrVEHICLE_PENDING_TRIPS[$iSELECTED_PENDING_SLOT]['res_id'], "srvc_id", "to_date");
																							echo "<td align='center' width ='".$iTIME_SLOT_WIDTH."' height='".$iCHART_ROW_HEIGHT."' style='background-color:".$sPULLED_COLOR."' class='coldata' onmouseover='return overlib(\"Vehicle being Repaired it will be available again at ".fn_cDateMySql($sRESTORE_PULL_DATE, 2)."\");' onmouseout='return nd();'>&nbsp;</td>";
																						}
																					}else{
																						echo "<td align='center' width ='".$iTIME_SLOT_WIDTH."'	height='".$iCHART_ROW_HEIGHT."' style='background-color:".$sFREE_COLOR."' class='coldata'>&nbsp;</td>";
																					}
																				}
																			?>
																		</tr>
																	</table>
																</td>
																	
															<?	}?>
														</tr>
														
														<tr><td colspan="<? echo $sCALENDAR_DAYS+1?>" class="coldata leftbox">&nbsp;</td></tr>
														<?	}
														}mysql_free_result($rsVEHICLES);
														?>
														
													</table>
													</div>
													
												</td>
											</tr>
										</table>
										<table width="100%">
											<tr>
												<td align="left" width="250" class='left_side_menu'>													
													<? if(strtotime($sToday_Date)>strtotime(date('Y-m-d'))) {
														$sToday_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " -".$sCALENDAR_DAYS." day");
													?>	<a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sToday_Date)?>');">PREVIOUS</a>
													<?	}?>
												</td>
												<td style="background-color:<?=$sReserved_COLOR?>; border:1px solid #000;" width="5">&nbsp;</td>
												<td width="45">reserved</td>
												<td style="background-color:<?=$sFREE_COLOR?>; border:1px solid #000;" width="5"></td>
												<td width="45">available</td>
												<td align="right" width="250" class='left_side_menu'><a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sNext_Date)?>');">NEXT</a></td>
												
											</tr>
											<tr><td colspan="6" align="center" class='left_side_menu'><a href="javascript:void(0);" onClick="window.parent.focus(); window.close();">CLOSE THIS GRAPH</a></td></tr>
										</table>
										
									</td>
								</tr>
							</table>
						</form>
					
                	</td>
                	<td></td>
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
<?
function fn_CHECK_PENDING_TRIPS($ivehicleid, $sstartdate, $senddate, $ssession){

	$arrPENDING_TRIPS		=		array();	
	
	$sSQL	=	"SELECT pt.res_id, pt.Start_Date, pt.End_Date, pt.r_type FROM(";
	
	$sSQL	.=	"SELECT tbl_reservations.res_id, planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date, 'normal' AS r_type ".
	"FROM tbl_reservations ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"INNER JOIN tbl_user assigned ON tbl_reservations.assigned_driver = assigned.user_id ".
	"INNER JOIN tbl_departments ON assigned.dept_id = tbl_departments.dept_id ".
	"WHERE vehicle_id = ".$ivehicleid." AND tbl_trip_details.res_id IS NULL AND tbl_abandon_trips.res_id IS NULL AND ".
	"(DATE(planned_depart_day_time) >= '".$sstartdate."' OR DATE( planned_return_day_time ) >= '".$sstartdate."') AND ".
	"(DATE(planned_depart_day_time) <= '".$senddate."' OR DATE(planned_return_day_time) <= '".$senddate."') AND reservation_cancelled	=	0 AND cancelled_by_driver =	0 AND (coord_approval = 'Approved') ";
	//if($ivehicleid==11)	print($sSQL);
	
	$sSQL	.=	" UNION ALL ";
	
	$sSQL	.=	"SELECT s.srvc_id AS res_id, s.from_date AS Start_Date, s.to_date AS End_Date, 'pulled' AS r_type FROM tbl_srvc_resvs s ".
	"INNER JOIN tbl_srvc_resvs_details sd ON s.srvc_id = sd.srvc_id ".
	"WHERE s.vehicle_id = ".$ivehicleid." AND ((DATE(s.from_date) BETWEEN '".$sstartdate."' AND '".$senddate."') OR (DATE(s.to_date) BETWEEN '".$sstartdate."' AND '".$senddate."')) ".
	"AND s.is_cancelled = 0 AND s.service_type = 'temporary'";
	
	$sSQL	.=		") pt ORDER BY pt.Start_Date";
	
	$rsRES	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRES)>0){
		$iROW_COUNTER		=		0;
		while($rowRES	=	mysql_fetch_array($rsRES)){
			$iHours	=	0;
			while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") < strtotime($rowRES['End_Date'])){			
			$arrPENDING_TRIPS[$iROW_COUNTER]['res_id']			=		$rowRES['res_id'];
			$arrPENDING_TRIPS[$iROW_COUNTER]['pending_trips']	=		date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"));
			$arrPENDING_TRIPS[$iROW_COUNTER]['res_type']		=		$rowRES['r_type'];
			
			$iHours++;
			$iROW_COUNTER++;
			}
			
		}
	}mysql_free_result($rsRES);

	//sort($arrPENDING_TRIPS);
	return $arrPENDING_TRIPS;
}
?>