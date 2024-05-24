<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	fn_Print_MSG_BOX("<li class='bold-font'>See the 'List Deleted Trips' function for details of reservations that were deleted in using this function", "C_SUCCESS");
	
	//print("DAY====".fn_CALCULATE_DATE_DIFF('2013-02-01 15:00:00', '2013-02-02 15:00:00'));
	if(isset($_POST["saction"])	&& $_POST["saction"]=="temp-service"){
		//$sSTART_DATE	=	fn_DATE_TO_MYSQL($_POST["txtstartdate"])." ".$_POST["drptime1"].":00";
		$sSTART_DATE	=	substr(str_replace(' pm',':00',str_replace(' am',':00',$_POST["txtstartdate"])),strpos($_POST["txtstartdate"],' ')+1);
		$sSTART_DATE	=	fn_DATE_TO_MYSQL($_POST["txtstartdate"])." ".$_POST["drptime1"].":00";//$sSTART_DATE;
		$sEND_DATE		=	fn_DATE_TO_MYSQL($_POST["txtenddate"])." ".$_POST["drptime2"].":00";
		
		$sMessage	=	fn_TEMP_SERVICE('normal', $_POST["drpvehicle"], $sSTART_DATE, $sEND_DATE, $_SESSION["User_ID"], $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
		
		if($sMessage		==	"future"){
			$sMessage		=		fn_Print_MSG_BOX("<li>Vehicle can only be pulled for future time", "C_ERROR");
		}elseif($sMessage	==	"overlimit"){
			$sMessage		=		fn_Print_MSG_BOX("<li>Temporary removal must be for less than 2 weeks", "C_ERROR");
		}elseif($sMessage	==	"already"){
			$sMessage		=		fn_Print_MSG_BOX("<li>Vehicle is already been pulled for service for the same time period", "C_ERROR");
		}/*else{
			$sMessage		=		fn_Print_MSG_BOX("<li>vehicle has been pulled for service", "C_SUCCESS");
		}*/
					
	}elseif(isset($_POST["saction"])	&& $_POST["saction"]=="permanent"){
	
			fn_SERVICE_RESERVATION('normal', 'permanent', $_POST["drpvehicle"], '', '', $_SESSION["User_ID"], $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
			
			$sSQL	=	"UPDATE tbl_vehicles SET restricted = 0 WHERE vehicle_id = ".$_POST["drpvehicle"];
			mysql_query($sSQL) or die(mysql_error());
			$sMessage		.=	fn_Print_MSG_BOX("<li>vehicle has been removed/unavailable for indefinite time period", "C_SUCCESS");	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Pull Vehicle for Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="generator" content="Bluefish 2.2.8" >
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<!-- jQuery -->
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript" src="./js/popup.js"></script>
<!-- firebug lite -->
		<script type="text/javascript" src="./js/firebug.js"></script>
        
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
				$('.date-pick').datePicker({startDate: '<?Php echo date('m/d/Y');?>', autoFocusNextInput: true});
            });
		</script>
<script type="text/javascript">
$(document).ready(function(){
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupClose").click(function(){
		disablePopup();
		document.frm1.submit();
		
	});
	
	$("#popupCancel").click(function(){
		disablePopup();
	});

});

function valid_service(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpvehicle.value==""){
		sErrMessage='<li>please select vehicle';
		iErrCounter++;
	}
	
	var bMethod = false;
	var sMethodVal	=	"";
	for (var i=0; i <frm.optmethod.length; i++) { 
		if (frm.optmethod[i].checked) { 
	   		bMethod	=	true; 
			sMethodVal=	frm.optmethod[i].value;
		} 
	}
	
	if(bMethod==false){
		sErrMessage=sErrMessage+'<li>please select removal method';
		iErrCounter++;
	}else{
		if(sMethodVal=="Temporary"){
			frm.saction.value	=	'temp-service';
			
			/*if (frm.txtstartdate.value==""){
				sErrMessage=sErrMessage+'<li>please select start date';
				iErrCounter++;
			}*/
			
			if (frm.txtenddate.value==""){
				sErrMessage=sErrMessage+'<li>please select end date';
				iErrCounter++;
			}else{
				$.get("ajax_data.php", {action: 'temp-service', drpvehicle: $('#drpvehicle').val(), txtstartdate: $('#txtstartdate').val(), txtenddate: $('#txtenddate').val(), drptime2: $('#drptime2').val()}, function(data){			  	
						
							//$('#Message').html(data);
							//alert(data.substring(0,4));
							if(data.substring(0,4)=='<li>'){
								document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Error!!!</h1><br /><br />"+data+"</b><br /><br />";
								document.getElementById('popbuttons').innerHTML		=	"<input type='button' name='btnOk' value='CLOSE' class='Button' id='popupOk' style='width:100px;' onclick='disablePopup();' />";
							}else{
								if(data!="")
								document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Confirmation!</h1><br /><br />"+data+"</b><br /><br />";
							}
							if(data!=""){
								centerPopup();
								loadPopup();
							}else{
								frm.submit();
							}
							
				}, 'html');
			}	
		}else{
			frm.saction.value	=	'permanent';
			$.get("ajax_data.php", {action: 'permanent', drpvehicle: $('#drpvehicle').val()}, function(data){
				
				if(data.substring(0,4)=='<li>'){
					document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Error!!!</h1><br /><br />"+data+"</b><br /><br />";
					document.getElementById('popbuttons').innerHTML		=	"<input type='button' name='btnOk' value='CLOSE' class='Button' id='popupOk' style='width:100px;' onclick='disablePopup();' />";
				}else{
					if(data!="")
						document.getElementById('contactArea').innerHTML	=	"<h1 class='notice-heading'>Confirmation!</h1><br /><br />"+data+"</b><br /><br />";
				}
					if(data!=""){	
						centerPopup();
						loadPopup();
					}else{
						frm.submit();
					}
				
			}, 'html');
		}
	}
	
	
				
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		if(sMethodVal=="Temporary"){
			frm.saction.value	=	'temp-service';
		}else{
			frm.saction.value	=	'permanent';
		}
		//frm.submit();
	}
	
}

function fn_SHOW_HIDE_BOX(sSTATUS){
	
	if(sSTATUS=='on'){
		document.getElementById('date-criteria').style.display='block';
	}else{
		document.getElementById('date-criteria').style.display='none';
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
								   				<h1 style="margin-bottom: 0px;">PULL VEHICLE FOR SERVICE</h1>
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
				
				<?Php
				$arrTIME[0][0]	=	"00:00";	$arrTIME[0][1]	=	"12:00 am";		$arrTIME[1][0]	=	"01:00";	$arrTIME[1][1]	=	"01:00 am";
				$arrTIME[2][0]	=	"02:00";	$arrTIME[2][1]	=	"02:00 am";		$arrTIME[3][0]	=	"03:00";	$arrTIME[3][1]	=	"03:00 am";
				$arrTIME[4][0]	=	"04:00";	$arrTIME[4][1]	=	"04:00 am";		$arrTIME[5][0]	=	"05:00";	$arrTIME[5][1]	=	"05:00 am";
				$arrTIME[6][0]	=	"06:00";	$arrTIME[6][1]	=	"06:00 am";		$arrTIME[7][0]	=	"07:00";	$arrTIME[7][1]	=	"07:00 am";
				$arrTIME[8][0]	=	"08:00";	$arrTIME[8][1]	=	"08:00 am";		$arrTIME[9][0]	=	"09:00";	$arrTIME[9][1]	=	"09:00 am";
				$arrTIME[10][0]=	"10:00";	$arrTIME[10][1]=	"10:00 am";		$arrTIME[11][0]=	"11:00";	$arrTIME[11][1]=	"11:00 am";
				$arrTIME[12][0]=	"12:00";	$arrTIME[12][1]=	"12:00 pm";		$arrTIME[13][0]=	"13:00";	$arrTIME[13][1]=	"01:00 pm";
				$arrTIME[14][0]=	"14:00";	$arrTIME[14][1]=	"02:00 pm";		$arrTIME[15][0]=	"15:00";	$arrTIME[15][1]=	"03:00 pm";
				$arrTIME[16][0]=	"16:00";	$arrTIME[16][1]=	"04:00 pm";		$arrTIME[17][0]=	"17:00";	$arrTIME[17][1]=	"05:00 pm";
				$arrTIME[18][0]=	"18:00";	$arrTIME[18][1]=	"06:00 pm";		$arrTIME[19][0]=	"19:00";	$arrTIME[19][1]=	"07:00 pm";
				$arrTIME[20][0]=	"20:00";	$arrTIME[20][1]=	"08:00 pm";		$arrTIME[21][0]=	"21:00";	$arrTIME[21][1]=	"09:00 pm";
				$arrTIME[22][0]=	"22:00";	$arrTIME[22][1]=	"10:00 pm";		$arrTIME[23][0]=	"23:00";	$arrTIME[23][1]=	"11:00 pm";
				
				?>
				
				
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
                	<td width="949" class="TextObject" align="center">
						<form name="frm1" method="post">
							<input type="hidden" name="saction" value=""	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td class="label" width="200">Vehicle No:</td>
									<td width="400"><?	//fn_VEHICLE('drpvehicle', 0, "150", "1", "--Select Vehicle--");?>
										<?
											$sUNAVAILABLE	=	"";
											$sSQL	=	"SELECT v.vehicle_id, v.vehicle_no, CASE WHEN pv.vehicle_id IS NULL THEN 'free' ELSE 'pulled' END AS status FROM tbl_vehicles v LEFT OUTER JOIN (SELECT vehicle_id FROM tbl_srvc_resvs s WHERE s.is_cancelled = 0) pv ON v.vehicle_id = pv.vehicle_id WHERE v.sold = 0 ORDER BY (vehicle_no+0)";
											$rsVEHICLES	=	mysql_query($sSQL) or die(mysql_error());
											if(mysql_num_rows($rsVEHICLES)>0){
												echo "<select name='drpvehicle' id='drpvehicle' style='width:150px;'>";
												echo "<option value=''>--Select Vehicle--</option>";
												while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
													//print($rowVEHICLE['status']."<br />");
													if($rowVEHICLE['status']=='pulled') $sUNAVAILABLE	=	 "class='unavailable'"; else	$sUNAVAILABLE	=	"";
													echo "<option value='".$rowVEHICLE['vehicle_id']."' $sUNAVAILABLE>".$rowVEHICLE['vehicle_no']."</option>";
												}
												echo "</select>";
											}mysql_free_result($rsVEHICLES);
										?>
									</td>
								</tr>
								<tr>
									<td class="label">Removal Method:</td>
									<td>
										<input type="radio" id="opttemporaryremoval" name="optmethod" value="Temporary" onClick="fn_SHOW_HIDE_BOX('on');" />&nbsp;<div class="label left">Temporary Removal</div>&nbsp;&nbsp;&nbsp;<input type="radio" id="optpermanentremoval" name="optmethod" value="Permanent" onClick="fn_SHOW_HIDE_BOX('off');" />&nbsp;<div class="label left">Indefinite Removal</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="date-criteria" style="display:none;">
											<table cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>
													<td class="label" width="150">From:</td>
													<td width="200">
														<input type="text" name="txtstartdate" id="txtstartdate" maxlength="20" style="width:100px;" value="<?Php echo date('m/d/Y');?>" class="date-pick dp-applied"/>
														&nbsp;
														<select id="drptime1" name="drptime1" style="width:100px;">
															<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
																<option value="<? echo $arrTIME[$iCounter][0];?>"><? echo $arrTIME[$iCounter][1];?></option>
															<?	}?>
														</select>
													</td>
												</tr>
												<tr>
													<td class="label">To:</td>
													<td>
														<input type="text" name="txtenddate" id="txtenddate" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
														&nbsp;
														<select id="drptime2" name="drptime2" style="width:100px;">
															<? 	for($iCounter=0;$iCounter<=23;$iCounter++){?>
																<option value="<? echo $arrTIME[$iCounter][0];?>"><? echo $arrTIME[$iCounter][1];?></option>
															<?	}?>
														</select>
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td></td>
									<td><input type="button" name="btnSUBMIT" value="PULL VEHICLE" class="Button" onClick="valid_service(this.form);" style="width:165px;" />
									
									</td>
								</tr>
										
								<?	//}?>
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
<div id="popupContact">
	<div id="contactArea" style="padding-left:10px;"></div>
	<br /><br />
	<div id="popbuttons" style="text-align:center; width:100%; margin:0 auto;">
		<input type="button" name="btnclose" value="OK" class="Button" id="popupClose" style="width:100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="btnclose" value="CANCEL" class="Button" id="popupCancel" style="width:100px;" />
	</div>
	<br /><br />
</div>
<div id="backgroundPopup"></div>