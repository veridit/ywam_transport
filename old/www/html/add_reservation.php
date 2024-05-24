<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iPageCounter	=	0;
	$iFirstPage		=	0;
	$iBacked 		=	0;
	$sAction		=	"";
	$iVehicleID		=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]!="")				$sAction		=	$_POST["action"];
	if(isset($_POST["ipagecounter"]) && $_POST["ipagecounter"]!="")		$iPageCounter	=	$_POST["ipagecounter"];
	if(isset($_POST["txtfirstpage"]) && $_POST["txtfirstpage"]!="")		$iFirstPage		=	$_POST["txtfirstpage"];
	if(isset($_POST["txtbacked"]) && $_POST["txtbacked"]!="")			$iBacked		=	$_POST["txtbacked"];
	if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			$iVehicleID		=	$_POST["drpvehicle"];
	
	
	if($sAction=="addreservation"){
	
		$bOVERNIGHT	=	0;
						
		$sDEPART_DATETIME		=	substr($_POST["txtdeptdatetime"],6, 4);
		$sDEPART_DATETIME		.=	"-".substr($_POST["txtdeptdatetime"],0, 2);
		$sDEPART_DATETIME		.=	"-".substr($_POST["txtdeptdatetime"],3, 2);
		$sDEPART_DATETIME		.=	" ".substr($_POST["txtdeptdatetime"],11, 2).":00:00";
		
		$sRETURN_DATETIME		=	substr($_POST["txtreturndatetime"],6, 4);
		$sRETURN_DATETIME		.=	"-".substr($_POST["txtreturndatetime"],0, 2);
		$sRETURN_DATETIME		.=	"-".substr($_POST["txtreturndatetime"],3, 2);
		$sRETURN_DATETIME		.=	" ".substr($_POST["txtreturndatetime"],11, 2).":00:00";
		//print("CURRET==".date('Y-m-d H:i:s'));
			//cannot make reservation in past
			
			
			if(strtotime($sDEPART_DATETIME) < strtotime(date('Y-m-d H:i:s'))){
				$sMessage		=	fn_Print_MSG_BOX("vehicle cannot be reserved in past date or time", "C_ERROR");
			}else{
			
			
					//check already reserved vehicle for this time
					fn_CHECK_RESERVATION($iVehicleID, substr($sDEPART_DATETIME,0,10), substr($sRETURN_DATETIME,0,10));
				
					$iHours	=	0;
					while(strtotime($sDEPART_DATETIME." + ".$iHours." hour") < strtotime($sRETURN_DATETIME)){
						if(fn_CHECK_RESERVATION_TIME(substr($sDEPART_DATETIME,0,10), substr($sDEPART_DATETIME, 11, 2))){
							$sMessage		=	fn_Print_MSG_BOX("vehicle is already reserved in your specified time period", "C_ERROR");
							break;
						}
						$iHours++;
					}	
				
					if($sMessage==""){
						if(isset($_POST["chkovernight"]) && $_POST["chkovernight"]!="")				$bOVERNIGHT	=	"1";	else	$bOVERNIGHT	=	"0";
							
						$sSQL="INSERT INTO tbl_reservations(vehicle_id, user_id, planned_passngr_no, planned_depart_day_time, ".
						"planned_return_day_time, overnight, destination) ".
						"VALUES(".$_POST["drpvehicle"].", ".$_SESSION["User_ID"].", '".$_POST["txtpassenger"]."', ".
						"'".$sDEPART_DATETIME."', '".$sRETURN_DATETIME."', ".$bOVERNIGHT.", '".addslashes($_POST["txtdestination"])."')";
						
						$rsRESERVATION=mysql_query($sSQL) or die(mysql_error());
						$sMessage		=	fn_Print_MSG_BOX("your request to reserve the vehicle is submitted, <br />you will be notify by an email, for the trip of this reservation, after the approval of transportation coordinator (TC)", "C_SUCCESS");
					}
			}
					
				
	}
	
	
	if($sAction=="viewreservations"){
			$iTimeCol_WIDTH	=	"80";
			$iDayCol_WIDTH	=	"83";
			
			$sReserved_COLOR	=	"#FF6633";
			$sFree_COLOR		=	"#FFEBD7";
			
			$arrTIME[0][0]	=	"00:00";	$arrTIME[0][1]	=	"12:00 am";		$arrTIME[1][0]	=	"01:00";	$arrTIME[1][1]	=	"1:00 am";
			$arrTIME[2][0]	=	"02:00";	$arrTIME[2][1]	=	"2:00 am";		$arrTIME[3][0]	=	"03:00";	$arrTIME[3][1]	=	"3:00 am";
			$arrTIME[4][0]	=	"04:00";	$arrTIME[4][1]	=	"4:00 am";		$arrTIME[5][0]	=	"05:00";	$arrTIME[5][1]	=	"5:00 am";
			$arrTIME[6][0]	=	"06:00";	$arrTIME[6][1]	=	"6:00 am";		$arrTIME[7][0]	=	"07:00";	$arrTIME[7][1]	=	"7:00 am";
			$arrTIME[8][0]	=	"08:00";	$arrTIME[8][1]	=	"8:00 am";		$arrTIME[9][0]	=	"09:00";	$arrTIME[9][1]	=	"9:00 am";
			$arrTIME[10][0]=	"10:00";	$arrTIME[10][1]=	"10:00 am";		$arrTIME[11][0]=	"11:00";	$arrTIME[11][1]=	"11:00 am";
			$arrTIME[12][0]=	"12:00";	$arrTIME[12][1]=	"12:00 pm";		$arrTIME[13][0]=	"13:00";	$arrTIME[13][1]=	"1:00 pm";
			$arrTIME[14][0]=	"14:00";	$arrTIME[14][1]=	"2:00 pm";		$arrTIME[15][0]=	"15:00";	$arrTIME[15][1]=	"3:00 pm";
			$arrTIME[16][0]=	"16:00";	$arrTIME[16][1]=	"4:00 pm";		$arrTIME[17][0]=	"17:00";	$arrTIME[17][1]=	"5:00 pm";
			$arrTIME[18][0]=	"18:00";	$arrTIME[18][1]=	"6:00 pm";		$arrTIME[19][0]=	"19:00";	$arrTIME[19][1]=	"7:00 pm";
			$arrTIME[20][0]=	"20:00";	$arrTIME[20][1]=	"8:00 pm";		$arrTIME[21][0]=	"21:00";	$arrTIME[21][1]=	"9:00 pm";
			$arrTIME[22][0]=	"22:00";	$arrTIME[22][1]=	"10:00 pm";		$arrTIME[23][0]=	"23:00";	$arrTIME[23][1]=	"11:00 pm";
			
			//starting date
			if(isset($_POST["scurrentdate"]) && $_POST["scurrentdate"]!="")	$sToday_Date = $_POST["scurrentdate"];	else	$sToday_Date = date("Y-m-d");
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Add Reservation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<!--<script type="text/javascript">function F_loadRollover(){} function F_roll(){}</script>-->
<!--<script type="text/javascript" src="../assets/rollover.js"></script>-->
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">

<link rel="stylesheet" href="./js/protoplasm/protoplasm.css" /> 
		<script type="text/javascript" language="javascript" src="./js/protoplasm/protoplasm.js"></script> 
		<script type="text/javascript" language="javascript" src="./js/protoplasm/common.js"></script> 
		
      	<script language="javascript">
            // transform() calls can be chained together
            Protoplasm.use('datepicker')
                .transform('input.datepicker')
               .transform('.datetimepicker', { timePicker: true })
        </script>

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script language="JavaScript">
function fn_VIEW_RESERVATIONS(sDate, iPage){
	if(document.frm1.drpvehicle.value==''){
		fn_draw_ErrMsg('<li>please select vehicle to view reservations');
	}else{
	
		if(typeof sDate== 'undefined'){
			var time	=	new Date();
			sDate	=		time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate();
		}
		
		if(iPage=='undefined')			{		iPage		=	0;	}
		
		document.frm1.scurrentdate.value = sDate;
		document.frm1.ipagecounter.value = iPage;
		document.frm1.action.value='viewreservations';
		document.frm1.submit();
	}
}

function valid_reservation(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpvehicle.value==""){
		sErrMessage='<li>please select vehicle';
		iErrCounter++;
	}
	
	
	if (frm.txtpassenger.value == ""){
		sErrMessage=sErrMessage+'<li>please enter passengers';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtpassenger, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid passengers';
			iErrCounter++;
		}
	}
	if (frm.txtdeptdatetime.value==""){
		sErrMessage=sErrMessage+'<li>please select departure date and time';
		iErrCounter++;
	}
		
	if (frm.txtreturndatetime.value==""){
		sErrMessage=sErrMessage+'<li>please select return date and time';
		iErrCounter++;
	}

		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		frm.action.value='addreservation';
		frm.submit();
	}
	
}

</script>
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
								   				<h1 style="margin-bottom: 0px;">VEHICLE RESERVATION</h1>
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
						<form name="frm1" action="add_reservation.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="scurrentdate" value=""	/>
							<input type="hidden" name="ipagecounter" value="<?=$iPageCounter?>"	/>
							<input type="hidden" name="txtfirstpage" value="<?=$iFirstPage?>"	/>
							<input type="hidden" name="txtbacked" value="<?=$iBacked?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="670" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td align="center">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td class="label" width="130">Vehicle:</td>
												<td width="180"><?	fn_VEHICLE('drpvehicle', $iVehicleID, "150", "1", "Select Vehicle");?></td>
												<td width="130">&nbsp;</td>
												<td width="200" align="right"><input type="button" name="btngo" value="VIEW RESERVATIONS" class="Button" onClick="document.frm1.txtfirstpage.value=1; fn_VIEW_RESERVATIONS();" /></td>
											</tr>
											<tr>
												<td class="label">No.of Passengers:</td>
												<td><input type="text" name="txtpassenger" value="" maxlength="2" style="width:150px; text-align:right;"  /></td>
												<td class="label">Overnight?</td>
												<td><input type="checkbox" name="chkovernight" value="1" /></td>
											</tr>
											
											
											<tr>
												<td class="label">Planned Departure Date Time:</td>
												<td>
													<input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:130px;" class="datetimepicker" /><br/>
													<span class="Highlight">(only hour part will be considered)</span>
												</td>
												<td class="label">Planned Return Date Time:</td>
												<td>
													<input readonly="" type="text" name="txtreturndatetime" id="txtreturndatetime" style="width:130px;" class="datetimepicker" /><br />
													<span class="Highlight">(only hour part will be considered)</span>
												</td>
											</tr>
											<tr>
												<td class="label" valign="top">Destination:</td>
												<td>
												<textarea name="txtdestination" id="txtdestination" cols="20" rows="5" style="width:150px;" onkeydown="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);" onkeyup="fn_char_Counter(this.form.txtdestination,this.form.txtLength,100);"></textarea>
												&nbsp;<input readonly type="text" name="txtLength" value="100" style="width:25px;">
												</td>
											</tr>
											<tr><td colspan="4" align="center"><input type="button" name="btnSUBMIT" value="RESERVE THIS VEHICLE" class="Button" onClick="valid_reservation(this.form);" style="width:150px;" /></td></tr>
										</table>
										
										<?	if($sAction=="viewreservations"){?>
										<br /><br />
										
										<table cellpadding="0" cellspacing="0" border="0" class="box">
											<tr>
												<td>
												
													<div style="width:670px; height:350px; overflow:auto; scrollbars:auto;" align="center">
													<table cellpadding="0" cellspacing="0" border="0">
														
														<tr>
															<td width="<?=$iTimeCol_WIDTH?>" align="center" class="colhead">Time</td>	<!--time column	-->
															<?	for($iDays=1;$iDays<=7;$iDays++){		//days column	-->
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");?>
																	<td width="<?=$iDayCol_WIDTH?>" align="center" class="colhead"><? echo fn_cDateMySql(date('Y-m-d',$sNext_Date),3);	?></td>
															<?	}	?>
														</tr>
														<!-- NOW EXTRACT ALL RESERVATIONS FOR THESE CURRENT 7 DAYS	-->
														<?	
																/*$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
																"FROM tbl_reservations WHERE vehicle_id = ".$iVehicleID." AND DATE(planned_depart_day_time) >= '".date('Y-m-d',strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +1 day"))."' AND ".
																"DATE(planned_return_day_time) <= '".date('Y-m-d',$sNext_Date)."'";*/
																/*$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
																"FROM tbl_reservations WHERE vehicle_id = ".$iVehicleID." AND DATE(planned_depart_day_time) >= '".$sToday_Date."' AND ".
																"DATE(planned_return_day_time) <= '".date('Y-m-d',$sNext_Date)."'";
																												 
																$rsRES	=	mysql_query($sSQL) or die(mysql_error());
																
																//first delete previous temp entries
																$sSQL	=	"DELETE FROM tbl_temp_reservations";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
																
																if(mysql_num_rows($rsRES)>0){
																	while($rowRES	=	mysql_fetch_array($rsRES)){
																		$iHours	=	0;
																		while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") <= strtotime($rowRES['End_Date'])){
																		
																		$sSQL	=	"INSERT INTO tbl_temp_reservations VALUES('".date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"))."')";
																		$rsADD_INTERVALS	=	mysql_query($sSQL) or die(mysql_error());
																		$iHours++;
																		}
																		
																	}
																}mysql_free_result($rsRES);*/
															
															fn_CHECK_RESERVATION($iVehicleID, $sToday_Date, date('Y-m-d',$sNext_Date));
														
															for($iCounter=0;$iCounter<=23;$iCounter++){
														?>
														<tr>
															
															<td align="right" height="25" class="coldata leftbox" style="background-color:<?='#CA0000'?>; color:<?='#FFEBD7'?>"><? echo $arrTIME[$iCounter][1]?></td>
															
															<?	for($iDays=1;$iDays<=7;$iDays++){
																	
																	$sNext_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " +".$iDays." day");
																	
																	/*$sSQL	=	"SELECT * FROM tbl_temp_reservations WHERE DATE(reservations) = '".date('Y-m-d',$sNext_Date)."' AND MID(reservations, 12, 2) = '".substr($arrTIME[$iCounter][0], 0, strpos($arrTIME[$iCounter][0], ':'))."'";
																	$rsTIME_MATCH	=	mysql_query($sSQL) or die(mysql_error());
																	if(mysql_num_rows($rsTIME_MATCH)>0){
																		echo "<td align='center' height='25' style='background-color:".$sReserved_COLOR."' class='coldata'>&nbsp;</td>";
																	}else{
																		echo "<td align='center' height='25' style='background-color:".$sFree_COLOR."' class='coldata'>&nbsp;</td>";
																	}mysql_free_result($rsTIME_MATCH);*/
																	if(fn_CHECK_RESERVATION_TIME(date('Y-m-d',$sNext_Date), substr($arrTIME[$iCounter][0], 0, 2))){
																	
																		echo "<td align='center' height='25' style='background-color:".$sReserved_COLOR."' class='coldata'>&nbsp;</td>";
																	}else{
																		echo "<td align='center' height='25' style='background-color:".$sFree_COLOR."' class='coldata'>&nbsp;</td>";
																	}
																}
															?>
														</tr>
														
														<?	}
																if($iPageCounter>0) {
																	$sToday_Date = strtotime(date("Y-m-d", strtotime($sToday_Date)) . " -7 day");
																}
														?>
														
													</table>
													</div>
													
												</td>
											</tr>
										</table>
										<table width="100%">
											<tr>
												<td align="left" width="250">
													<? if($iFirstPage	==	1 ||($iPageCounter	==	0 && $iBacked == 0)) {?><a href="javascript:void(0);" onClick="document.frm1.txtbacked.value=1; document.frm1.txtfirstpage.value=0; fn_VIEW_RESERVATIONS('<?=date('Y-m-d',strtotime(date('Y-m-d', strtotime($sToday_Date)) . ' -1 day'))?>', 0);">PREVIOUS</a><?	}?>
													<? if($iPageCounter	>	0) {?><a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sToday_Date)?>', <?=$iPageCounter-1?>);">PREVIOUS</a><?	}?>
												</td>
												<td style="background-color:<?=$sReserved_COLOR?>" width="5">&nbsp;</td>
												<td width="45">reserved</td>
												<td style="background-color:<?=$sFree_COLOR?>" width="5"></td>
												<td width="45">available</td>
												<td align="right" width="250"><a href="javascript:void(0);" onClick="fn_VIEW_RESERVATIONS('<?=date('Y-m-d',$sNext_Date)?>', <?=$iPageCounter+1?>);">NEXT</a></td>
											</tr>
											
										</table>
										<?	}	?>
									</td>
								</tr>
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
<?

function fn_CHECK_RESERVATION($ivehicleid, $sstartdate, $senddate){
	
	$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
	"FROM tbl_reservations WHERE vehicle_id = ".$ivehicleid." AND DATE(planned_depart_day_time) >= '".$sstartdate."' AND ".
	"DATE(planned_return_day_time) <= '".$senddate."' AND reservation_cancelled	=	0	AND (coord_approval = 'Pending' OR coord_approval = 'Approved') ";
													 
	$rsRES	=	mysql_query($sSQL) or die(mysql_error());
	
	//first delete previous temp entries
	$sSQL	=	"DELETE FROM tbl_temp_reservations";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
	
	if(mysql_num_rows($rsRES)>0){
		while($rowRES	=	mysql_fetch_array($rsRES)){
			$iHours	=	0;
			while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") < strtotime($rowRES['End_Date'])){
			
			$sSQL	=	"INSERT INTO tbl_temp_reservations VALUES('".date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"))."')";
			$rsADD_INTERVALS	=	mysql_query($sSQL) or die(mysql_error());
			$iHours++;
			}
			
		}
	}mysql_free_result($rsRES);

}

function fn_CHECK_RESERVATION_TIME($sCOMPARE_DATE, $sCOMPARE_TIME){

	$bRESERVED	=	false;
	$sSQL	=	"SELECT * FROM tbl_temp_reservations WHERE DATE(reservations) = '".$sCOMPARE_DATE."' AND MID(reservations, 12, 2) = '".$sCOMPARE_TIME."'";
	$rsTIME_MATCH	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsTIME_MATCH)>0){
		$bRESERVED	=	true;
	}else{
		$bRESERVED	=	false;
	}mysql_free_result($rsTIME_MATCH);
	
	return $bRESERVED;
	
}
?>