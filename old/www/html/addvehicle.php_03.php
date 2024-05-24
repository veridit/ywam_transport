<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="addvehicle"){
						
		$sDATE_UN			=	fn_DATE_TO_MYSQL($_POST["txtdateun"]);
		$sINSPECT_DATE		=	fn_DATE_TO_MYSQL($_POST["txtinspectdate"]);		
		$sREGISTER_DATE		=	fn_DATE_TO_MYSQL($_POST["txtregisterdate"]);

		
				//check if vehicle no already existed
				$sSQL	=	"SELECT vehicle_id FROM tbl_vehicles WHERE vehicle_no = '".$_POST["txtvno"]."'";
				$rsVEHICLE_CHECK	=	mysql_query($sSQL) or die(mysql_error());
				if (mysql_num_rows($rsVEHICLE_CHECK)>0){
					$sMessage=fn_Print_MSG_BOX("vehicle already existed with this number","C_ERROR");
					mysql_free_result($rsVEHICLE_CHECK);
					
				}else{
					$bActive	=	0;	$bRestricted	=	0;
					//if($_POST["chkactive"]=="1") $bActive = 1; else $bActive = 0;
					//if(isset($_POST["chkrestricted"]) && $_POST["chkrestricted"]=="1") $bRestricted = 1; else $bRestricted = 0;					
							
					$sSQL="INSERT INTO  tbl_vehicles(user_id, vehicle_no, vin_no, oil_filter, safety_date, registration_date, lic_plate_no, make_id, model, ".
					"year_manuf, mileage_un, date_to_un, cost_to_un, cost_rate, ".
					"passenger_cap, condition_tech, restriction, active, restricted, admin_issues) ".
					"VALUES(".$_SESSION["User_ID"].", '".$_POST["txtvno"]."', '".$_POST["txtvinno"]."', '".$_POST["txtoilfilter"]."', '".$sINSPECT_DATE."', '".$sREGISTER_DATE."', '".$_POST["txtplateno"]."', ".$_POST["drpbrand"].", ".$_POST["drpmodel"].", ".
					"'".$_POST["txtyear"]."', '".$_POST["txtmileageun"]."', '".$sDATE_UN."', ".$_POST["txtcostun"].", ".$_POST["txtcostrate"].", ".
					"'".$_POST["txtcap"]."', '".$_POST["drpcondition"]."', '".addslashes($_POST["txtrestriction"])."', ".$bActive.", ".$bRestricted.", '".addslashes($_POST["txtissuebyadmin"])."')";
					//print($sSQL);
					$rsVEHICLE=mysql_query($sSQL) or die(mysql_error());
					
					$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>vehicle is added successfully", "C_SUCCESS");
				}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Add Vehicle</title>
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
function valid_vehicle(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txtvno.value==""){
		sErrMessage='<li>please enter vehicle no';
		iErrCounter++;
	}else{
		regExp = /[a-zA-Z0-9\{9}]/i;
		if (!validate_field(frm.txtvno, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid vehicle no';
			iErrCounter++;
		}
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
		sErrMessage=sErrMessage+'<li>please select vehicle type';
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
				regExp = /[0-9\.{9}'-]/i;
				if (!validate_field(frm.txtyear, regExp)){
					sErrMessage=sErrMessage+'<li>please enter valid manufacturing year';
					iErrCounter++;
				}else if(parseInt(frm.txtyear.value)><?Php echo date('Y')?>){
					sErrMessage=sErrMessage+'<li>manufacturing year must be less than current year';
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
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcostun, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid cost';
			iErrCounter++;
		}
	}
	if (frm.txtcostrate.value == ""){
		sErrMessage=sErrMessage+'<li>please enter per mile cost rate';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
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
	else
		frm.submit();
	
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
								   				<h1 style="margin-bottom: 0px;">ADD VEHICLE</h1>
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
						<form name="frm1" action="addvehicle.php" method="post">
							<input type="hidden" name="action" value="addvehicle"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Vehicle No:</td>
									<td width="400"><input type="text" name="txtvno" value="" maxlength="12" style="width:130px;"  /><!--&nbsp;&nbsp;&nbsp;<span class="Highlight">must be a number from 1-99</span>--></td>
								</tr>
								<tr>
									<td class="label">License Plate No:</td>
									<td><input type="text" name="txtplateno" value="" maxlength="50" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Vin No:</td>
									<td><input type="text" name="txtvinno" value="" maxlength="20" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Oil Filter:</td>
									<td><input type="text" name="txtoilfilter" value="" maxlength="20" style="width:130px;"  /></td>
								</tr>
								<tr>
									<td class="label">Safety Inspect Date:</td>
									<td>
										<input readonly="" type="text" name="txtinspectdate" id="txtinspectdate" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
									</td>
								</tr>
								<tr>
									<td class="label">Registration Date:</td>
									<td>
										<input readonly="" type="text" name="txtregisterdate" id="txtregisterdate" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
									</td>
								</tr>
								<tr>
									<td class="label">Make:</td>
									<td><?	fn_VEHICLE_MAKE('drpbrand', '', "100", "1", "Select Make");?></td>
								</tr>
								<tr>
									<td class="label">Type:</td>
									<td><?	fn_VEHICLE_TYPE('drpmodel', '', "100", "1", "Select Type");?></td>
								</tr>
								
								
								<tr>
									<td class="label">Year Manufacture:</td>
									<td><input type="text" name="txtyear" value="" maxlength="4" style="width:100px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Mileage to UN:</td>
									<td><input type="text" name="txtmileageun" value="" maxlength="7" style="width:100px; text-align:right;"  /></td>
								</tr>
								<tr>
									<td class="label">Purchase Date:</td>
									<td><input type="text" readonly="" name="txtdateun" id="txtdateun" value="" maxlength="10" style="width:100px;" class="date-pick dp-applied" /></td>
								</tr>
								<tr>
									<td class="label">Purchase Price:</td>
									<td><input type="text" name="txtcostun" value="0.00" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Mileage Rate:</td>
									<td><input type="text" name="txtcostrate" value="0.00" maxlength="10" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Passenger Capacity:</td>
									<td><input type="text" name="txtcap" value="" maxlength="2" style="width:100px; text-align:right;" />&nbsp;&nbsp;&nbsp;<span class="Highlight">include driver</span></td>
								</tr>
								<tr>
									<td class="label">Condition:</td>
									<td><?	fn_CONDITION_TECH('drpcondition', '', "100", "1", "Select Condition");?></td>
								</tr>
								<tr>
									<td class="label" valign="top">Vehicle Specifics:</td>
									<td>
									<textarea name="txtrestriction" id="txtrestriction" cols="30" rows="5" style="width:250px;" onkeydown="fn_char_Counter(this.form.txtrestriction,this.form.txtLength,150);" onkeyup="fn_char_Counter(this.form.txtrestriction,this.form.txtLength,150);"></textarea>
									&nbsp;<input readonly type="text" name="txtLength" value="150" style="width:20px;">
									</td>
								</tr>
								<tr>
									<td class="label" valign="top">Issues by Admin:</td>
									<td>
									<textarea name="txtissuebyadmin" id="txtissuebyadmin" cols="30" rows="5" style="width:250px;" ></textarea>
									</td>
								</tr>
								<!--<tr>
									<td class="label">Active:</td>
									<td><input type="checkbox" name="chkactive" value="1" /></td>
								</tr>-->
								<!--<tr>
									<td class="label">Availble for Reservation:</td>
									<td><input type="checkbox" name="chkrestricted" value="1" /></td>
								</tr>-->
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="ADD VEHICLE" class="Button" onClick="valid_vehicle(this.form);" style="width:130px;" /></td></tr>
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