<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iCHARGE_ID		=	0;
	$iRECORD_COUNT	=	0;
	
	if(isset($_REQUEST["id"]) && $_REQUEST["id"]!=""){	$iCHARGE_ID		=	$_REQUEST["id"];}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="editrestricted"){
	
			$sSQL			=	"SELECT charge_id FROM tbl_restricted_charges WHERE vehicle_id = ".$_POST["drpvehicle"]." ".
			"AND dept_id = '".$_POST["drpdept"]."' AND charge_month = '".$_POST["drpmonth"]."' AND charge_year = '".$_POST["drpyear"]."'";
			$rsDUPLICATE	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsDUPLICATE)>1){
				$sMessage		=	fn_Print_MSG_BOX("ERROR!!! vehicle already been charged for this department in this month", "C_ERROR");
			}
			
			if($sMessage==""){
				
				if($_POST["optmethod"]=="Total Charge"){
				
					$sSQL="UPDATE tbl_restricted_charges SET vehicle_id = ".$_POST["drpvehicle"].", dept_id = '".$_POST["drpdept"]."', ".
					"charge_month = '".$_POST["drpmonth"]."', charge_year = '".$_POST["drpyear"]."', ".
					"calc_method = 'Total Charge', total_charge = ".$_POST["txttotalcharge"].", begin_mileage = NULL, end_mileage = NULL, rate = NULL WHERE charge_id = ".$iCHARGE_ID;
					
				}elseif($_POST["optmethod"]=="Readings"){
				
					$sSQL="UPDATE  tbl_restricted_charges SET vehicle_id = ".$_POST["drpvehicle"].", dept_id = '".$_POST["drpdept"]."', ".
					"charge_month = '".$_POST["drpmonth"]."', charge_year = '".$_POST["drpyear"]."', calc_method = 'Readings', ".
					"total_charge = ".$_POST["txtreadingcharges"].", begin_mileage = '".$_POST["txtstartreading"]."', end_mileage = '".$_POST["txtendreading"]."', rate = ".$_POST["txtrate"]."  ".
					"WHERE charge_id = ".$iCHARGE_ID;
										
				}
			
				
				//print($sSQL);
				$rsRESTRICTED=mysql_query($sSQL) or die(mysql_error());
				$sMessage		=	fn_Print_MSG_BOX("charges are modified successfully", "C_SUCCESS");
			}
					
				
	}
	
	
	
	$sSQL	=	"SELECT charges.vehicle_id, charges.dept_id, charges.charge_month, charges.charge_year, charges.calc_method, ".
	"charges.total_charge, ".
	"CASE WHEN charges.calc_method = 'Total Charge' THEN '' ELSE charges.begin_mileage END AS begin_mileage, ".
	"CASE WHEN charges.calc_method = 'Total Charge' THEN '' ELSE charges.end_mileage END AS end_mileage, ".
	"CASE WHEN charges.calc_method = 'Total Charge' THEN '' ELSE charges.rate END AS rate ".
	" FROM tbl_restricted_charges charges WHERE charge_id = ".$iCHARGE_ID;
	$rsRESTRICTED_CHARGES	=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsRESTRICTED_CHARGES);
	if($iRECORD_COUNT>0){
		$rowCHARGES	=	mysql_fetch_array($rsRESTRICTED_CHARGES);
	}else{
		$sMessage		=	fn_Print_MSG_BOX("no restricted charges are found for the selected entry", "C_ERROR");
	}mysql_free_result($rsRESTRICTED_CHARGES);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Edit Restricted Vehicle Charges</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_charge(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpvehicle.value == ""){
		sErrMessage='<li>please select vehicle no';
		iErrCounter++;
	}
	
	
	if (frm.drpdept.value==""){
		sErrMessage=sErrMessage+'<li>please select department to charge';
		iErrCounter++;
	}
	
	if (frm.drpmonth.value==""){
		sErrMessage=sErrMessage+'<li>please select month of charge';
		iErrCounter++;
	}
	
	if (frm.drpyear.value==""){
		sErrMessage=sErrMessage+'<li>please select year of charge';
		iErrCounter++;
	}
	
	
	var bMethod = false;
	var sCalcVal	=	"";
	for (var i=0; i <frm.optmethod.length; i++) { 
		if (frm.optmethod[i].checked) { 
	   		bMethod	=	true; 
			sCalcVal=	frm.optmethod[i].value;
		} 
	}
	
	
	if(bMethod==false){
		sErrMessage=sErrMessage+'<li>please select calculation method';
		iErrCounter++;
	}else{
		if(sCalcVal=="Total Charge"){
			
			if (frm.txttotalcharge.value=="" || frm.txttotalcharge.value=="0.00"){
				sErrMessage=sErrMessage+'<li>please enter total $ to be charged';
				iErrCounter++;
			}else{
				regExp = /[0-9\.{9}'-]/i;
				if (!validate_field(frm.txttotalcharge, regExp)){
					sErrMessage=sErrMessage+'<li>please enter valid total $ to be charged';
					iErrCounter++;
				}
			}
			
			
				
			
		}else if(sCalcVal=="Readings"){
			
			if (frm.txtstartreading.value == "0" || frm.txtstartreading.value == ""){
				sErrMessage=sErrMessage+'<li>please enter start reading';
				iErrCounter++;
			}else{
				regExp = /[0-9\.{9}'-]/i;
				if (!validate_field(frm.txtstartreading, regExp)){
					sErrMessage=sErrMessage+'<li>please enter valid start reading';
					iErrCounter++;
				}else
					if(parseInt(frm.txtstartreading.value)<parseInt(frm.txtlastreading.value)){
						sErrMessage=sErrMessage+'<li>start reading cann\'t be less than last reading';
						iErrCounter++;
					}
			}
			
			if (frm.txtendreading.value == "0" || frm.txtendreading.value == ""){
				sErrMessage=sErrMessage+'<li>please enter end reading';
				iErrCounter++;
			}else{
				regExp = /[0-9\.{9}'-]/i;
				if (!validate_field(frm.txtendreading, regExp)){
					sErrMessage=sErrMessage+'<li>please enter valid end reading';
					iErrCounter++;
				}else
					if(parseInt(frm.txtendreading.value)<parseInt(frm.txtstartreading.value)){
						sErrMessage=sErrMessage+'<li>end reading cann\'t be less than start reading';
						iErrCounter++;
					}
			}
			
		}
	}
		

	if (iErrCounter >0){	
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
function fn_CALC_METHOD(sMethod){

	if(sMethod=='totalcharge'){
		document.getElementById('Total_Charge').style.display='block';
		document.getElementById('Reading').style.display='none';
	}else if(sMethod=='reading'){
		document.getElementById('Total_Charge').style.display='none';
		document.getElementById('Reading').style.display='block';
	}
	
}

function ajax_data(vid){

var xmlhttp;
var sURL = "ajax_data.php?";
var sData	=	"";
try
{
		// Firefox, Opera 8.0+, Safari
		xmlhttp=new XMLHttpRequest();
}
catch (e){
	 try {
	  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	 } catch (e) {
		  try {
			   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
			   xmlhttp = false;
			}
	 }
}

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
 xmlhttp.open("GET", sURL+"action=restrictedmileage&vid="+vid,true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('loadingimage'))
				document.getElementById('loadingimage').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		//document.frm1.txtlastmileage.value	=	xmlhttp.responseText;
		sData	=	xmlhttp.responseText;
		
		document.frm1.txtlastreading.value		=	sData.substring(0, sData.indexOf('d='));
		document.frm1.txtdatelastreading.value	=	sData.substring(sData.indexOf('d=')+2,sData.indexOf('m='));
		document.frm1.txtrate.value				=	sData.substring(sData.indexOf('m=')+2,sData.length);
		
		fn_CALCULATE_CHARGE();
		
		document.getElementById('loadingimage').innerHTML = '';
		document.getElementById('loadingimage').style.display = 'none';
	
	}
 }
 xmlhttp.send(null)
}

function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
		 //alert(charCode);
		 //if(charCode!=46)
         if (charCode > 31 && (charCode < 48 || charCode > 57))
		  return false;
 				
			 return true;
		
}
function fn_CALCULATE_CHARGE(){
	var iResultTotal = 0;
	if(document.frm1.txtstartreading.value!="0" &&  document.frm1.txtendreading.value!="0"){
		iResultTotal	=	parseFloat(document.frm1.txtrate.value)*(parseInt(document.frm1.txtendreading.value)-parseInt(document.frm1.txtstartreading.value));
		document.frm1.txtreadingcharges.value	=		iResultTotal.toFixed(2);
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
								   				<h1 style="margin-bottom: 0px;">MODIFY CHARGE RESTRICTED VEHICLE</h1>
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
						<form name="frm1" action="edit_restricted_charges.php" method="post">
							<input type="hidden" name="action" value="editrestricted"	/>
							<input type="hidden" name="id" value="<?Php echo $iCHARGE_ID;?>" />
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?Php if($iRECORD_COUNT>0){?>
								<tr>
									<td width="200" class="label">Restricted Vehicle:</td>
									<td width="400">
									<?	//fn_RESTRICTED_VEHICLES("drpvehicle", $rowCHARGES['vehicle_id'], "200", "1", "--Select Vehicle--", "ajax_data(this.value);");			
										fn_VEHICLE("drpvehicle", $rowCHARGES['vehicle_id'], "200", "1", "ALL", "ajax_data(this.value);");
									?>
									</td>
									
								</tr>
								<tr>
									<td class="label">Department:</td>
									<td><?	fn_DEPARTMENT('drpdept', $rowCHARGES['dept_id'], "200", "1", "--Select Department--");	?></td>
								</tr>
								<tr>
									<td class="label">Charge Month:</td>
									<td><?	fn_MONTHS('drpmonth', $rowCHARGES['charge_month'], "200", "1", "--Select Month--");?></td>
								</tr>
								<tr>
									<td class="label">Charge Year:</td>
									<td><?	fn_YEARS('drpyear', $rowCHARGES['charge_year'], "200", "1", "--Select Year--");?></td>
								</tr>
								<tr>
									<td class="label">Calc Method:</td>
									<td><input type="radio" id="opttotalcharge" name="optmethod" value="Total Charge" onClick="fn_CALC_METHOD('totalcharge');" <?Php if($rowCHARGES['calc_method']=='Total Charge') echo "checked";?>/><span class="label left">Flat Rate</span>&nbsp;&nbsp;&nbsp;<input type="radio" id="optreading" name="optmethod" value="Readings" onClick="ajax_data(document.frm1.drpvehicle.value); fn_CALC_METHOD('reading');" <?Php if($rowCHARGES['calc_method']=='Readings') echo "checked";?>/><span class="label left">Readings</span></td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="Total_Charge" style="display:<?Php if($rowCHARGES['calc_method']=='Total Charge') echo 'block'; else echo 'none';?>;">
											<table  cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>
													<td class="label" width="150">Total $ to Charge:</td>
													<td width="300"><input type="text" value="<?Php echo $rowCHARGES['total_charge'];?>" name="txttotalcharge" maxlength="7" style="width:130px; text-align:right;" /></td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="Reading" style="display:<?Php if($rowCHARGES['calc_method']=='Readings') echo 'block'; else echo 'none';?>;">
											<div id="loadingimage"></div>
											<table  cellpadding="0" cellspacing="0" border="0" width="100%">
												
												<tr>
													<td width="150" class="label">Last Reading:</td>
													<td width="300"><input value="<?Php echo fn_VEHICLE_LAST_MILEAGE($rowCHARGES['vehicle_id']);?>" readonly="" type="text" name="txtlastreading" style="width:130px; text-align:right;" /><span class="Highlight">This is reading from the last 'end reading'</span></td>
												</tr>
												<tr>
													<td class="label">Date Last Reading:</td>
													<td><input value="<?Php echo fn_VEHICLE_LAST_END_GAS_DATE($rowCHARGES['vehicle_id']);?>" readonly="" type="text" name="txtdatelastreading" style="width:130px;" /></td>
												</tr>
												<tr>
													<td class="label">Start Reading:</td>
													<td><input value="<?Php if($rowCHARGES['calc_method']=='Readings') echo $rowCHARGES['begin_mileage']; else echo '0';?>" type="text" id="txtstartreading" name="txtstartreading" maxlength="7" style="width:130px; text-align:right;" onKeyPress="return isNumberKey(event);" onBlur="fn_CALCULATE_CHARGE();" /></td>
												</tr>
												<tr>
													<td class="label">End Reading:</td>
													<td><input value="<?Php if($rowCHARGES['calc_method']=='Readings') echo $rowCHARGES['end_mileage']; else echo '0';?>" type="text" id="txtendreading" name="txtendreading" maxlength="7" style="width:130px; text-align:right;" onKeyPress="return isNumberKey(event);" onBlur="fn_CALCULATE_CHARGE();" /></td>
												</tr>
												<tr>
													<td class="label">Rate per mile:</td>
													<td><input value="<?Php if($rowCHARGES['calc_method']=='Readings') echo $rowCHARGES['rate']; else echo '0.00';?>" readonly="" type="text" name="txtrate" maxlength="6" style="width:130px; text-align:right;" /></td>
												</tr>
												<tr>
													<td class="label">Charges:</td>
													<td><input readonly="" type="text" name="txtreadingcharges" value="<?Php if($rowCHARGES['calc_method']=='Readings') echo $rowCHARGES['total_charge']; else echo "0.00";?>" maxlength="10" style="width:130px; text-align:right;" /></td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								
																
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="MODIFY THIS CHARGE" class="Button" onClick="valid_charge(this.form);" style="width:150px;" /></td></tr>
								<?Php	}	?>
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