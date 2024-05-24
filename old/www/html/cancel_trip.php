<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage				=	"";
	$iRESERVATION_ID		=	0;
	$iRECORD_COUNT			=	0;
	$sCHECK_STRING			=	"";
	$sDRIVER_NAME			=	"";
	$sDRIVER_EMAIL			=	"";
	$sPLANNED_DEPART_DATE	=	"";
	$iVEHICLE_MILE_COST		=	0;
	
	if(isset($_POST["action"])	&& $_POST["action"]=="tripcancell"){
	
		if(isset($_POST["drpreservation"]) && $_POST["drpreservation"]!="")		$iRESERVATION_ID	=	mysql_real_escape_string($_POST["drpreservation"]);
		
		//if depart time is less than 12 hours then abandon
		$sSQL		=	"SELECT CASE WHEN DATE_ADD(planned_depart_day_time, INTERVAL -12 HOUR) >= NOW() THEN 'CANCEL' ELSE 'ABANDON' END AS cancel_abandon FROM `tbl_reservations` WHERE res_id = ".$iRESERVATION_ID;
		//print($sSQL);
		$rsCHECK	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsCHECK)>0){
			$rowCHECK	=	mysql_fetch_array($rsCHECK);			
			$sCHECK_STRING	=$rowCHECK['cancel_abandon'];	
		}mysql_free_result($rsCHECK);
		
		//print("check string===".$sCHECK_STRING);
		if($sCHECK_STRING	==	"CANCEL"){		
			$sSQL="UPDATE tbl_reservations SET cancelled_by_driver = 1, driver_cancelled_time = NOW() WHERE res_id = ".$iRESERVATION_ID;
			$sMessage		=	fn_Print_MSG_BOX("<li>trip has been cancelled", "C_SUCCESS");
			$rsTRIP=mysql_query($sSQL) or die(mysql_error());
			
			
		}else{
		
			$iVEHICLE_MILE_COST		=	fn_VEHICLE_PER_MILE_COST($iRESERVATION_ID);		//extract per mile charge for vehicle of current reservations
			
			$sSQL="INSERT INTO tbl_abandon_trips(notes, res_id, mile_charges) VALUES('', ".$iRESERVATION_ID.", ".$iVEHICLE_MILE_COST.")";
			$sMessage		=	"<span class='bold-font'>When you cancel a reservation this late, it deprives other schools a reasonable chance to use the van in this time slot.<br />This reservation will be marked as an abandon trip.<br />Please be more careful in making reservation in future.</span>";
			$rsTRIP=mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"SELECT CONCAT(u.f_name, ' ', u.l_name) AS driver_name, u.email, r.planned_depart_day_time FROM tbl_reservations r INNER JOIN tbl_user u ON r.user_id = u.user_id WHERE r.res_id = ".$iRESERVATION_ID;
			$rsTRIP_STATISTIC	=			mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsTRIP_STATISTIC)>0){
				list($sDRIVER_NAME, $sDRIVER_EMAIL, $sPLANNED_DEPART_DATE)	=	mysql_fetch_row($rsTRIP_STATISTIC);
			}mysql_free_result($rsTRIP_STATISTIC);
			
			$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 23";
			$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
			$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 23";
			$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
			$sMailMSG		=	str_replace('#username#', $sDRIVER_NAME, str_replace('#resvno#', $iRESERVATION_ID, str_replace('#departdate#', fn_cDateMySql($sPLANNED_DEPART_DATE,2), $sMailMSG)));
			
			//print($sMailMSG);
			$mail = new PHPMailer();
			$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
			$mail->From     = $sSUPPORT_EMAIL;
			$mail->FromName = $sCOMPANY_Name;
			$mail->AddAddress($sDRIVER_EMAIL);
			$mail->IsHTML(true);                               // send as HTML
			$mail->Subject  =  $sEmailSubject;
			$mail->Body    	= $sMailMSG;
			//if(!$mail->Send()){   $sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email, $mail->ErrorInfo","C_ERROR");	}
			if(!$mail->Send()){   $sMessage		.=	"<br />Error in Sending Email, $mail->ErrorInfo";	}
			
			
		}
		
	}
	

	
	$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
	"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
	"tbl_vehicles.vehicle_no ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"WHERE tbl_reservations.user_id = ".$_SESSION["User_ID"]." AND tbl_reservations.coord_approval = 'Approved' AND ".
	"reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_trip_details.res_id IS NULL AND tbl_abandon_trips.res_id IS NULL ".
	"AND tbl_reservations.planned_depart_day_time > NOW()";
	$rsROWS			=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=	mysql_num_rows($rsROWS);
	
	//if($iRECORD_COUNT==0){$sMessage		.=	fn_Print_MSG_BOX("you don't have any pending trip to be cancelled", "C_SUCCESS");}
	if($iRECORD_COUNT==0){$sMessage		.=	"<br /><br /><li>you don't have any pending trip to be cancelled";}
	
	if($sMessage!="")		$sMessage		=	fn_Print_MSG_BOX($sMessage,"C_SUCCESS");
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Cancel Trip</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_trip(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpreservation.value==""){
		sErrMessage='<li>please select pending trip to be cancelled';
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
								   				<h1 style="margin-bottom: 0px;">CANCEL RESERVATIONS</h1>
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
						<form name="frm1" action="cancel_trip.php" method="post">
							<input type="hidden" name="action" value="tripcancell"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?	if($iRECORD_COUNT>0){		?>
								<tr>
									<td width="100" class="label">Reservation No:</td>
									<td width="350">
									<?	if(mysql_num_rows($rsROWS)>0){	?>
										
										
									<select name="drpreservation" style="width:325px;" size="1">
										<option value="" selected>Select Reservation</option>
										<?	while($rowROWS	=	mysql_fetch_array($rsROWS)){?>
											<option value="<? echo $rowROWS['res_id'];?>">Resv.No:&nbsp;<?=$rowROWS['res_id']?>&nbsp;&nbsp;&nbsp;V-No:&nbsp;<?=$rowROWS['vehicle_no']?> FROM <?=fn_cDateMySql($rowROWS['planned_depart_day_time'],2)?> TO <?=fn_cDateMySql($rowROWS['planned_return_day_time'],2)?></option>
										<?	}?>
									</select>
									<?	}?>
									</td>
								</tr>
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="CANCEL RESERVATION" class="Button" onClick="valid_trip(this.form);" style="width:165px;" /></td></tr>
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