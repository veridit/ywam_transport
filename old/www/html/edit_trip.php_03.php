<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iTRIP_ID		=	0;
	$iRECORD_COUNT	=	0;
	
	//detail variables
	$iVEHICLE_NO		=	"";		$sCHARGE_DEPT		=	"";		$sDEPART_DATE	=	"";
	$sASSIGNED_DRIVER	=	"";		$iBEGINING_MILEAGE	=	"";		$iENDING_MILEAGE=	"";
	$sEND_GAS			=	"";		$bPROBLEM			=	0;		$sDRIVER_NOTES	=	"";		
	
	if(isset($_REQUEST["tripid"]))	$iTRIP_ID		=	$_REQUEST["tripid"];
	
	if(isset($_POST["action"])	&& $_POST["action"]=="edittrip"){		
									
			if(isset($_POST["chkproblem"]) && $_POST["chkproblem"]!="")				$bPROBLEM	=	"1";	else	$bPROBLEM	=	"0";
				
			$sSQL="UPDATE tbl_trip_details SET begin_mileage = ".$_POST["txtbeginmileage"].", ".
			"end_mileage = ".$_POST["txtendmileage"].", end_gas_percent = '".$_POST["drpgas"]."', problem = ".$bPROBLEM." ".
			"WHERE trip_id = ".$iTRIP_ID;
			
			$rsTRIP=mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"UPDATE tbl_user_comments SET comments = '".addslashes($_POST["txtproblem"])."' WHERE trip_id = (SELECT res_id FROM tbl_trip_details WHERE trip_id =".$iTRIP_ID.")";
			mysql_query($sSQL) or die(mysql_error());
			
			$sMessage		=	fn_Print_MSG_BOX("mileage and gas are updated", "C_SUCCESS");
	}
	
	
$sSQL	=		"SELECT td.*, CASE WHEN uc.comments IS NULL THEN '' ELSE uc.comments END AS comments, tbl_vehicles.vehicle_no, tbl_departments.dept_name, ".
"tbl_reservations.planned_depart_day_time, CONCAT(tbl_user.f_name,' ', tbl_user.l_name) AS assigned_driver ".
"FROM tbl_trip_details td ".
"INNER JOIN tbl_reservations ON td.res_id = tbl_reservations.res_id ".
"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
"INNER JOIN tbl_departments ON tbl_reservations.billing_dept = tbl_departments.dept_id ".
"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
"LEFT OUTER JOIN tbl_user_comments uc ON td.res_id = uc.trip_id ".
"WHERE td.trip_id = ".$iTRIP_ID;
$rsTRIP	=		mysql_query($sSQL) or die(mysql_error());
$iRECORD_COUNT	=mysql_num_rows($rsTRIP);	
if($iRECORD_COUNT>0){
	$rowTRIP	=	mysql_fetch_array($rsTRIP);
	$iVEHICLE_NO		=	$rowTRIP['vehicle_no'];
	$sCHARGE_DEPT		=	$rowTRIP['dept_name'];
	$sDEPART_DATE		=	fn_cDateMySql($rowTRIP['planned_depart_day_time'], 2);
	$sASSIGNED_DRIVER	=	$rowTRIP['assigned_driver'];
	$iBEGINING_MILEAGE	=	$rowTRIP['begin_mileage'];
	$iENDING_MILEAGE	=	$rowTRIP['end_mileage'];
	$sEND_GAS			=	$rowTRIP['end_gas_percent'];
	$bPROBLEM			=	$rowTRIP['problem'];
	//$sDRIVER_NOTES		=	stripslashes($rowTRIP['desc_problem']);
	$sDRIVER_NOTES		=	stripslashes($rowTRIP['comments']);
}mysql_free_result($rsTRIP);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Change Mileage &amp; Gas</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/popup.js"></script>
<script type="text/javascript">
$(document).ready(function(){
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupClose").click(function(){
		disablePopup();
		//fn_draw_ErrMsg('');
		fn_draw_ErrMsg('<li>please correct the Possible Mileage Error!');
		fn_CHANGE_TEXT_BOX_COLOR(document.frm1.txtbeginmileage, '#efc3df', '#ff0000');
		fn_CHANGE_TEXT_BOX_COLOR(document.frm1.txtendmileage, '#efc3df', '#ff0000');
		
	});
	
	$("#popupCancel").click(function(){
		disablePopup();
		document.frm1.submit();
	});

});

	
function fn_VIEW_CLOSED_TRIP(frm){
	if(frm.tripid.value!=""){
		frm.action.value	=	'';
		frm.submit();
	}
}
function valid_trip(frm){

	var sErrMessage='';
	var iErrCounter=0;
		
	if(frm.tripid.value==""){
		sErrMessage='<li>please select closed reservation number';
		iErrCounter++;
	}
	
	if (frm.txtbeginmileage.value == ""){
		sErrMessage=sErrMessage+'<li>please enter beginning mileage';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtbeginmileage, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid beginning mileage';
			iErrCounter++;
		}
	}
	
	if (frm.txtendmileage.value == ""){
		sErrMessage=sErrMessage+'<li>please enter ending mileage';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtendmileage, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid ending mileage';
			iErrCounter++;
		}
	}
	
	if (frm.drpgas.value == ""){
		sErrMessage=sErrMessage+'<li>please select end gas percentage';
		iErrCounter++;
	}
	
			
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
		return false;
	}
	else{
		frm.action.value	=	'edittrip';
		return true;
	}
}

function fn_CHECK_MILEAGE(frm){

	var sMileageErr	=	"";
	if(valid_trip(frm)){
		sMileageErr	=fn_CHECK_TRIP_MILEAGE(frm);
		if(sMileageErr!=""){
			fn_SHOW_POPUP(sMileageErr);
		}else{
			frm.submit();
		}
	}			
}

function fn_CHECK_TRIP_MILEAGE(frm){

	var sMileageErr	=	"";

	if(!isNaN(frm.txtbeginmileage.value) && !isNaN(frm.txtendmileage.value)){
		if((parseInt(frm.txtendmileage.value) <= parseInt(frm.txtbeginmileage.value)) || ((parseInt(frm.txtendmileage.value) - parseInt(frm.txtbeginmileage.value)) < 0 )){
			sMileageErr	=	"Possible Error - Mileage is negative";
		}else if(((parseInt(frm.txtendmileage.value) - parseInt(frm.txtbeginmileage.value)) >= 100 )){
			sMileageErr	=	"Possible Error - Charge is over 100 miles<br /><br />to accept the readings you entered, click on the Cancel button";
		}
	}
	
	return sMileageErr;
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
								   				<h1 style="margin-bottom: 0px;">CHANGE MILEAGE &amp; GAS (closed trips only)</h1>
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
						<form name="frm1" action="edit_trip.php" method="post">
							<input type="hidden" name="action" value="edittrip"	/>
							<!--<input type="hidden" name="tripid" value="<?=$iTRIP_ID?>"	/>-->
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<!--<tr>
									<td class="label">Reservation No:</td>
									<td><input readonly="" type="text" name="txtresvno" value="<? //echo $rowTRIP['res_id']; ?>" style="width:110px;" /></td>
								</tr>-->
								<tr>
									<td width="200" class="label">Reservation No:</td>
									<td width="400">
									<?
										$sSQL	=	"SELECT tbl_trip_details.trip_id, tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
										"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
										"tbl_vehicles.vehicle_no ".
										"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
										"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
										"WHERE tbl_reservations.coord_approval = 'Approved' AND reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
										"ORDER BY tbl_trip_details.trip_id DESC";
										//print($sSQL);
										$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
										if(mysql_num_rows($rsRESERVATION)>0){
									?>
									<select name="tripid" style="width:350px;" size="1" onChange="fn_VIEW_CLOSED_TRIP(this.form);">
										<option value="" selected>--Select Reservation--</option>
										<?	while($rowRESERVATION	=	mysql_fetch_array($rsRESERVATION)){?>
											<option value="<? echo $rowRESERVATION['trip_id'];?>" <? if($rowRESERVATION['trip_id']==$iTRIP_ID) echo "selected";?>>Resv #:&nbsp;<?=$rowRESERVATION['res_id']?>&nbsp;V-No:&nbsp;<?=$rowRESERVATION['vehicle_no']?> FROM <?=fn_cDateMySql($rowRESERVATION['planned_depart_day_time'],2)?> TO <?=fn_cDateMySql($rowRESERVATION['planned_return_day_time'],2)?></option>
										<?	}?>
									</select>
									<?	}else{
											echo 	fn_Print_MSG_BOX("no closed trip is found!", "C_ERROR");
										}
									?>
									</td>
								</tr>
								<?	if($iRECORD_COUNT>0){?>
								<tr>
									<td class="label">Vehicle:</td>
									<td><input type="text" readonly="" name="txtvehicle" value="<? echo $iVEHICLE_NO; ?>" style="width:110px;" /></td>
								</tr>
								<tr>
									<td class="label">Charge Dept:</td>
									<td><input type="text" readonly="" name="txtchargedept" value="<? echo $sCHARGE_DEPT; ?>" style="width:110px;" /></td>
								</tr>
								<tr>
									<td class="label">Depart Date:</td>
									<td><input type="text" readonly="" name="txtdepartdate" value="<? echo $sDEPART_DATE; ?>" style="width:110px;" /></td>
								</tr>
								<tr>
									<td class="label">Assigned Driver:</td>
									<td><input type="text" readonly="" name="txtassgnddriver" value="<? echo $sASSIGNED_DRIVER; ?>" style="width:110px;" /></td>
								</tr>
								<tr>
									<td class="label">Beginning Mileage:</td>
									<td><input type="text" name="txtbeginmileage" value="<? echo $iBEGINING_MILEAGE; ?>" maxlength="7" style="width:110px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Ending Mileage:</td>
									<td><input type="text" name="txtendmileage" value="<? echo $iENDING_MILEAGE; ?>" maxlength="7" style="width:110px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">End Gas Percent:</td>
									<td>
										<?
											$arrGAS[0]	=	"25%";
											$arrGAS[1]	=	"50%";
											$arrGAS[2]	=	"75%";
											$arrGAS[3]	=	"100%";
										?>
										<select name="drpgas" size="1" style="width:110px;">
											<option value="">Gas End Percent</option>
											<?	for($iCounter=0;$iCounter<=3;$iCounter++){?>
											<option value="<?=$arrGAS[$iCounter]?>" <? if($sEND_GAS==$arrGAS[$iCounter]) echo "selected";?>><?=$arrGAS[$iCounter]?></option>
											<?	}?>
										</select>
									</td>
								</tr>
								
								<tr>
									<td class="label">Problem:</td>
									<td><input type="checkbox" name="chkproblem" value="1" <? if($bPROBLEM==1) echo "checked";	?> /></td>
								</tr>
								<tr>
									<td class="label" valign="top">TM Notes on Drivers:</td>
									<td>
									<textarea name="txtproblem" id="txtproblem" cols="20" rows="9" style="width:300px;" onkeydown="fn_char_Counter(this.form.txtproblem,this.form.txtLength,300);" onkeyup="fn_char_Counter(this.form.txtproblem,this.form.txtLength,300);"><? echo $sDRIVER_NOTES;?></textarea>
									<br /><input readonly type="text" name="txtLength" value="300" style="width:30px;">
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<!--<tr><td></td><td><input type="button" name="btnSUBMIT" value="UPDATE MILEAGE &amp; GAS" class="Button" onClick="valid_trip(this.form);" style="width:165px;" /></td></tr>-->
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="UPDATE MILEAGE &amp; GAS" class="Button" onClick="fn_CHECK_MILEAGE(this.form);" style="width:165px;" /></td></tr>
								<?	}	?>
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
	<div style="text-align:center; width:100%; margin:0 auto;">
		<input type="button" name="btnclose" value="OK" class="Button" id="popupClose" style="width:100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="btnclose" value="CANCEL" class="Button" id="popupCancel" style="width:100px;" />
	</div>
	<br /><br />
</div>
<div id="backgroundPopup"></div>