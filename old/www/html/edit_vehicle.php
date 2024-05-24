<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iVEHICLE_ID		=	0;
	$iRECORD_COUNT		=	0;
	
	$sCANCEL_MESSAGE	=	"";
	
	
	//==============vehicle fields===============
	$sVEHICLE_NO = "";	 $sLIC_PLATE_NO		=	"";		$sVIN_NO		=	"";		$sOIL_FILTER	=	"";		$sSAFETY_DATE	=	"";
	$sREGISTER_DATE		=	"";		$sMAKE			=	"";		$sMODEL			=	"";		$sYEAR			=	"";
	$sMILEAGE_UN		=	"";		$sPURCHASE_DATE	=	"";		$iPURCHASE_PRICE=	"";		$iMILEAGE_RATE	=	"";
	$iPASSENGER_CAPACITY=	"";		$sCOMDITION		=	"";		$sTM_NOTES		=	"";		$bACTIVE		=	0;		$bRESTRICTED	=	0;
	$sADMIN_ISSUES			=	"";	
					
	if(isset($_REQUEST["vid"]))	$iVEHICLE_ID		=	$_REQUEST["vid"];
	
	if(isset($_POST["action"])	&& $_POST["action"]=="modifyvehicle"){
						
		$sDATE_UN			=	fn_DATE_TO_MYSQL($_POST["txtdateun"]);
		$sINSPECT_DATE		=	fn_DATE_TO_MYSQL($_POST["txtinspectdate"]);		
		$sREGISTER_DATE		=	fn_DATE_TO_MYSQL($_POST["txtregisterdate"]);
		
		
		//if($_POST["chkactive"]=="1") $bACTIVE = 1; else $bACTIVE = 0;
		//if(isset($_POST["chkrestricted"]) && $_POST["chkrestricted"]=="1") $bRESTRICTED = 1; else $bRESTRICTED = 0;
			
		
		$sSQL="UPDATE tbl_vehicles SET user_id=".$_SESSION["User_ID"].", vehicle_no = '".mysql_real_escape_string($_POST["txtvehicleno"])."', vin_no = '".mysql_real_escape_string($_POST["txtvinno"])."', oil_filter = '".mysql_real_escape_string($_POST["txtoilfilter"])."', ".
		"safety_date = '".$sINSPECT_DATE."', registration_date = '".$sREGISTER_DATE."', ".
		"lic_plate_no='".mysql_real_escape_string($_POST["txtplateno"])."', make_id=".mysql_real_escape_string($_POST["drpbrand"]).", model=".mysql_real_escape_string($_POST["drpmodel"]).", ".
		"year_manuf='".mysql_real_escape_string($_POST["txtyear"])."', mileage_un='".mysql_real_escape_string($_POST["txtmileageun"])."', date_to_un='".$sDATE_UN."', cost_to_un=".mysql_real_escape_string($_POST["txtcostun"]).", cost_rate = ".mysql_real_escape_string($_POST["txtcostrate"]).", ".
		"passenger_cap='".mysql_real_escape_string($_POST["txtcap"])."', condition_tech='".mysql_real_escape_string($_POST["drpcondition"])."', restriction='".mysql_real_escape_string(addslashes($_POST["txtrestriction"]))."', active = ".$bACTIVE.", restricted = ".$bRESTRICTED." ".
		", admin_issues = '".mysql_real_escape_string(addslashes($_POST["txtissuebyadmin"]))."' 
		WHERE vehicle_id = ".$iVEHICLE_ID; 
		//print($sSQL);
		$rsVEHICLE=mysql_query($sSQL) or die(mysql_error());
		
		$sMessage		.=	fn_Print_MSG_BOX("<li class='bold-font'>vehicle modified successfully", "C_SUCCESS");
	}
	
	$sSQL	=	"SELECT * FROM tbl_vehicles WHERE vehicle_id = ".$iVEHICLE_ID;
	//print($sSQL);
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=mysql_num_rows($rsVEHICLE);
	
	if($iRECORD_COUNT>0){
		$rowVEHICLE	=	mysql_fetch_array($rsVEHICLE);
		$sVEHICLE_NO			=	$rowVEHICLE['vehicle_no'];
		$sLIC_PLATE_NO		=	$rowVEHICLE['lic_plate_no'];
		$sVIN_NO			=	$rowVEHICLE['vin_no'];
		$sOIL_FILTER		=	$rowVEHICLE['oil_filter'];
		$sSAFETY_DATE		=	fn_cDateMySql($rowVEHICLE['safety_date'], 1);
		$sREGISTER_DATE		=	fn_cDateMySql($rowVEHICLE['registration_date'], 1);
		$sMAKE				=	$rowVEHICLE['make_id'];
		$sMODEL				=	$rowVEHICLE['model'];
		$sYEAR				=	$rowVEHICLE['year_manuf'];
		$sMILEAGE_UN		=	$rowVEHICLE['mileage_un'];
		$sPURCHASE_DATE		=	fn_cDateMySql($rowVEHICLE['date_to_un'], 1);
		$iPURCHASE_PRICE	=	$rowVEHICLE['cost_to_un'];
		$iMILEAGE_RATE		=	$rowVEHICLE['cost_rate'];
		$iPASSENGER_CAPACITY=	$rowVEHICLE['passenger_cap'];
		$sCOMDITION			=	$rowVEHICLE['condition_tech'];
		$sTM_NOTES			=	stripslashes($rowVEHICLE['restriction']);
		$sADMIN_ISSUES		=	stripslashes($rowVEHICLE['admin_issues']);
		$bRESTRICTED		=	$rowVEHICLE['restricted'];
					
	}mysql_free_result($rsVEHICLE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Modify Vehicle</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
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

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function fn_VIEW_VEHICLE(frm){
	if(frm.vid.value!=""){
		frm.action.value	=	'';
		frm.submit();
	}
}
function valid_vehicle(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	/*if (frm.txtvno.value==""){
		sErrMessage='<li>please enter vehicle no';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9\{9}]/i;
		if (!validate_field(frm.txtvno, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid vehicle no';
			iErrCounter++;
		}
	}*/
	
	if(frm.vid.value==""){
		sErrMessage='<li>please select vehicle no';
		iErrCounter++;
	}
	
	if (frm.txtplateno.value==""){
		sErrMessage=sErrMessage+'<li>please enter license plate no';
		iErrCounter++;
	}
	
	if (frm.txtvinno.value==""){
		sErrMessage=sErrMessage+'<li>please enter vin no';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9\.{9}'-]/i;
		if (!validate_field(frm.txtvinno, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid vin no';
			iErrCounter++;
		}
	}
	
	if (frm.txtoilfilter.value==""){
		sErrMessage=sErrMessage+'<li>please enter oil filter number';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9\.{9}'-]/i;
		if (!validate_field(frm.txtoilfilter, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid oil filter number';
			iErrCounter++;
		}
	}
	
	if (frm.txtinspectdate.value==""){
		sErrMessage=sErrMessage+'<li>please select safety insp. date';
		iErrCounter++;
	}
	
	if (frm.txtregisterdate.value==""){
		sErrMessage=sErrMessage+'<li>please select registration date';
		iErrCounter++;
	}
	
	if (frm.drpbrand.value == ""){
		sErrMessage=sErrMessage+'<li>please select vehicle maker';
		iErrCounter++;
	}
	
	if (frm.drpmodel.value == ""){
		sErrMessage=sErrMessage+'<li>please vehicle type';
		iErrCounter++;
	}
	
	if (frm.txtyear.value == ""){
		sErrMessage=sErrMessage+'<li>please enter manufacturing year';
		iErrCounter++;
	}else{
			if(frm.txtyear.value.length < 4){
			sErrMessage=sErrMessage+'<li>please enter 4 digits for manufacturing year';
			iErrCounter++;
			}else{
				regExp = /[ 0-9\.{9}'-]/i;
				if (!validate_field(frm.txtyear, regExp)){
					sErrMessage=sErrMessage+'<li>please enter valid manufacturing year';
					iErrCounter++;
				}
			}
	}
	
	if (frm.txtmileageun.value == ""){
		sErrMessage=sErrMessage+'<li>please enter mileage to un';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtmileageun, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid mileage';
			iErrCounter++;
		}
	}
	
	if (frm.txtdateun.value == ""){
		sErrMessage=sErrMessage+'<li>please select date to un';
		iErrCounter++;
	}
	
	if (frm.txtcostun.value == ""){
		sErrMessage=sErrMessage+'<li>please enter cost to un';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcostun, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid cost';
			iErrCounter++;
		}
	}
	
	if (frm.txtcostrate.value == ""){
		sErrMessage=sErrMessage+'<li>please enter per mile cost rate';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcostrate, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid per mile cost rate';
			iErrCounter++;
		}
	}
	
	if (frm.txtcap.value == ""){
		sErrMessage=sErrMessage+'<li>please enter vehicle passenger capacity';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcap, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid passenger capacity';
			iErrCounter++;
		}
	}
		
	if (frm.drpcondition.value == ""){
		sErrMessage=sErrMessage+'<li>please select vehicle technical condition';
		iErrCounter++;
	}
	
		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		frm.action.value	=	'modifyvehicle';
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
								   				<h1 style="margin-bottom: 0px;">MODIFY VEHICLE</h1>
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
						<form name="frm1" action="edit_vehicle.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="vehstatus" value="<? echo $bRESTRICTED;?>" />
							<!--<input type="hidden" name="vid" value="<?=$iVEHICLE_ID?>" />-->
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Vehicle No:</td>	
									<td width="400"><?	fn_VEHICLE('vid', $iVEHICLE_ID, "130", "1", "--Select Vehicle--", "fn_VIEW_VEHICLE(this.form);");?></td>
								</tr>
								<?	if($iRECORD_COUNT>0){?>
								<tr>
									<td class="label">Vehicle No:</td>
									<td><input type="text" name="txtvehicleno" value="<?=$sVEHICLE_NO?>" maxlength="50" style="width:130px;"  /></td>
								</tr>								
								<tr>
									<td class="label">License Plate No:</td>
									<td><input type="text" name="txtplateno" value="<?=$sLIC_PLATE_NO?>" maxlength="50" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Vin No:</td>
									<td><input type="text" name="txtvinno" value="<?=$sVIN_NO?>" maxlength="20" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Oil Filter:</td>
									<td><input type="text" name="txtoilfilter" value="<?=$sOIL_FILTER?>" maxlength="20" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Safety Inspect Date:</td>
									<td>
										<input readonly="" type="text" name="txtinspectdate" id="txtinspectdate" value="<?=$sSAFETY_DATE?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
									</td>
								</tr>
								<tr>
									<td class="label">Registration Date:</td>
									<td>
										<input readonly="" type="text" name="txtregisterdate" id="txtregisterdate" value="<?=$sREGISTER_DATE?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
									</td>
								</tr>
								<tr>
									<td class="label">Make:</td>
									<td><?	fn_VEHICLE_MAKE('drpbrand', $sMAKE, "100", "1", "Select Make");?></td>
								</tr>
								<tr>
									<td class="label">Type:</td>
									<td><?	fn_VEHICLE_TYPE('drpmodel', $sMODEL, "100", "1", "Select Model");?></td>
								</tr>
								
								
								<tr>
									<td class="label">Year Manufacture:</td>
									<td><input type="text" name="txtyear" value="<?=$sYEAR?>" maxlength="4" style="width:100px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Mileage to UN:</td>
									<td><input type="text" name="txtmileageun" value="<?=$sMILEAGE_UN?>" maxlength="7" style="width:100px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Purchase Date:</td>
									<td><input type="text" readonly="" name="txtdateun" id="txtdateun" value="<?=$sPURCHASE_DATE?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Purchase Price:</td>
									<td><input type="text" name="txtcostun" value="<?=$iPURCHASE_PRICE?>" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Mileage Rate:</td>
									<td><input type="text" name="txtcostrate" value="<?=$iMILEAGE_RATE?>" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Passenger Capacity:</td>
									<td><input type="text" name="txtcap" value="<?=$iPASSENGER_CAPACITY?>" maxlength="2" style="width:100px; text-align:right;" />&nbsp;&nbsp;&nbsp;<span class="Highlight">include driver</span></td>
								</tr>
								<tr>
									<td class="label">Condition:</td>
									<td><?	fn_CONDITION_TECH('drpcondition', $sCOMDITION, "100", "1", "Select Condition");?></td>
								</tr>
								<tr>
									<td class="label" valign="top">Vehicle Specifics:</td>
									<td>
									<textarea name="txtrestriction" id="txtrestriction" cols="30" rows="5" style="width:250px;" onkeydown="fn_char_Counter(this.form.txtrestriction,this.form.txtLength,150);" onkeyup="fn_char_Counter(this.form.txtrestriction,this.form.txtLength,150);"><?=$sTM_NOTES?></textarea>
									&nbsp;<input readonly type="text" name="txtLength" value="150" style="width:20px;">
									</td>
								</tr>
								<tr>
									<td class="label" valign="top">Issues by Admin:</td>
									<td>
									<textarea name="txtissuebyadmin" id="txtissuebyadmin" cols="30" rows="5" style="width:250px;" ><?=$sADMIN_ISSUES?></textarea>
									</td>
								</tr>
								
								<!--<tr>
									<td class="label">Available for Reservation:</td>
									<td><input type="checkbox" name="chkrestricted" value="1" <? //if($bRESTRICTED==1) echo "checked";?> /></td>
								</tr>-->
								<!--onClick="if(this.checked==false) alert('PLEASE NOTIFY DRIVERS AFFECTED BY THIS ACTION !');" -->
								<!--<tr>
									<td class="label"></td>
									<td class="bold-font Highlight">Process for making vehicle available - unavailable is now under the Reservations Tab / Remove Veh. from service</td>
								</tr>-->
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="MODIFY VEHICLE" class="Button" onClick="valid_vehicle(this.form);" style="width:130px;" /></td></tr>
								<?	}?>
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
		<div id="contactArea" style="padding-left:10px;">asdfasdf</div>
		<div style="text-align:center; width:100px; margin:0 auto;"><input type="button" name="btnclose" value="CLOSE" class="Button" id="popupContactClose" style="width:100px;" /><input type="button" name="btnprint" value="PRINT" class="Button" style="width:100px;" /></div>
	</div>
<div id="backgroundPopup"></div>