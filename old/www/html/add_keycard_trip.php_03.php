<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	$iRESERVATION_ID	=	0;
	$iRECORD_COUNT	=	0;
	$bNO_COST		=	0;
	
	if(isset($_POST["action"])	&& $_POST["action"]=="addkeycard"){
	
		if(isset($_POST["drpreservation"]) && $_POST["drpreservation"]!="")		$iRESERVATION_ID	=	$_POST["drpreservation"];
		if(isset($_POST["chknocost"]) && $_POST["chknocost"]!="")				$bNO_COST			=	1;
	
		$sSQL="UPDATE tbl_reservations SET key_no = '".$_POST["txtkey"]."', card_no = '".$_POST["txtcard"]."', no_cost = ".$bNO_COST." WHERE res_id = ".$iRESERVATION_ID;
		$rsTRIP=mysql_query($sSQL) or die(mysql_error());		
		$sMessage		=	fn_Print_MSG_BOX("reservation is processed successfully", "C_SUCCESS");	
	}
	
	$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
	"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
	"tbl_vehicles.vehicle_no ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"WHERE tbl_reservations.coord_approval = 'Approved' AND ".
	"reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
	"AND tbl_trip_details.res_id IS NULL ".
	"AND tbl_abandon_trips.res_id IS NULL ".
	"AND (key_no IS NULL OR key_no = '') AND (card_no IS NULL OR card_no = '') ORDER BY tbl_reservations.res_id DESC";
	$rsROWS			=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=	mysql_num_rows($rsROWS);
	
	if($iRECORD_COUNT==0){$sMessage		.=	fn_Print_MSG_BOX("no reservations are pending to process", "C_SUCCESS");}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Make Trip Slip</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_process(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpreservation.value==""){
		sErrMessage='<li>please select reservation to process';
		iErrCounter++;
	}
	
	if (frm.txtkey.value==""){
		sErrMessage=sErrMessage+'<li>please enter key number';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtkey, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid key number';
			iErrCounter++;
		}
	}
	
	if (frm.txtcard.value==""){
		sErrMessage=sErrMessage+'<li>please enter card number';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtcard, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid card number';
			iErrCounter++;
		}
	}	
			
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}

function ajax_data(rid){
var xmlhttp;
var sURL = "ajax_data.php?";
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
 xmlhttp.open("GET", sURL+"action=process&rid="+rid,true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('reservation_data'))
				document.getElementById('reservation_data').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		document.getElementById('reservation_data').innerHTML	=	xmlhttp.responseText;
		
		//document.getElementById('loadingimage').innerHTML = '';
		//document.getElementById('loadingimage').style.display = 'none';
	
	}
 }
 xmlhttp.send(null)
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
								   				<h1 style="margin-bottom: 0px;">MAKE TRIP SLIP</h1>
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
						<form name="frm1" action="add_keycard_trip.php" method="post">
							<input type="hidden" name="action" value="addkeycard"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?	if($iRECORD_COUNT>0){		?>
								<tr>
									<td width="100" class="label">Reservation No:</td>
									<td width="350">
									<?	if(mysql_num_rows($rsROWS)>0){	?>
										
										
									<select name="drpreservation" style="width:325px;" size="1" onChange="ajax_data(this.value);">
										<option value="" selected>Select Reservation</option>
										<?	while($rowROWS	=	mysql_fetch_array($rsROWS)){?>
											<option value="<? echo $rowROWS['res_id'];?>">Resv.No:&nbsp;<?=$rowROWS['res_id']?>&nbsp;&nbsp;&nbsp;V-No:&nbsp;<?=$rowROWS['vehicle_no']?> FROM <?=fn_cDateMySql($rowROWS['planned_depart_day_time'],2)?> TO <?=fn_cDateMySql($rowROWS['planned_return_day_time'],2)?></option>
										<?	}?>
									</select>
									<?	}?>
									</td>
								</tr>
								<tr>
									<td class="label">Key No:</td>
									<td><input type="text" name="txtkey" maxlength="4" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">Card No:</td>
									<td><input type="text" name="txtcard" maxlength="8" style="width:100px; text-align:right;" /></td>
								</tr>
								<tr>
									<td class="label">No Cost:</td>
									<td><input type="checkbox" name="chknocost" value="1"/><span class="Highlight bold-font">Only IF allowed under current University Policy</span></td>
								</tr>
								<!--<tr>
									<td class="label">First Name:</td>
									<td class="label"><input type="text" name="txtfname" id="txtfname" readonly=""  style="width:100px;" /> &nbsp;Last Name:&nbsp;<input type="text" name="txtlname" id="txtlname" readonly="" style="width:100px;" /></td>
								</tr>
								<tr>
									<td class="label">Depart Date:</td>
									<td><input readonly="" type="text" name="txtdeptdatetime" id="txtdeptdatetime" style="width:100px;" value="" /></td>
								</tr>-->
								<tr><td></td><td class="label" id="reservation_data"></td></tr>
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td></td>
									<td>
										<input type="button" name="btnSUBMIT" value="PROCESS" class="Button" onClick="valid_process(this.form);" style="width:125px;" />
										<?	if(isset($_POST["action"])	&& $_POST["action"]=="addkeycard" && $iRESERVATION_ID!=0){	?>
										<input type="button" name="btnPRINT" value="PRINT SLIP 2" class="Button" onClick="fn_PRINT_TRIP_SLIP(<? echo $iRESERVATION_ID;?>, 0);" style="width:110px;" />
										<?	}	?>
									</td>
								</tr>
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