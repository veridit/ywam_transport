<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iRESERVATION_ID	=	0;
	$iRECORD_COUNT		=	0;
	$sCRITERIA_SQL		=	"";
	if(isset($_REQUEST["resid"]))	$iRESERVATION_ID		=	$_REQUEST["resid"];
	
	/*if(isset($_POST["action"])	&& $_POST["action"]=="coord"){
			$sSQL="UPDATE tbl_reservations SET coord_approval = '".$_POST["drpstatus"]."' WHERE res_id = ".$iRESERVATION_ID;
			//print($sSQL);
			$rsRES=mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
			"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
			"tbl_vehicles.vehicle_no, CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS driver_name, email ".
			"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
			"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID;
			$rsEMAIL	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsEMAIL)>0){
				$rowEMAIL	=	mysql_fetch_array($rsEMAIL);
			}mysql_free_result($rsEMAIL);
			
			//email sending section
				$sStatusMessage	=	"";
				if($_POST["drpstatus"]=="Approved"){
					$sStatusMessage		=	"Approval";
				}elseif($_POST["drpstatus"]=="Disapproved"){
					$sStatusMessage		=	"Disapproval";
				}
				$sEmailSubject		=	"Vehicle Reservation ".$sStatusMessage;
				$sEmailMessage		=	"<span class='success'>Dear ".$rowEMAIL['driver_name']."<br />";
				$sEmailMessage		.=	"your request to reservation of <br />vehicle no: ".$rowEMAIL['vehicle_no']." from ".fn_cDateMySql($rowEMAIL['planned_depart_day_time'],2)." to ".fn_cDateMySql($rowEMAIL['planned_return_day_time'],2)." has been ".$_POST["drpstatus"]." by transportation coordinator,<br />";
				$sEmailMessage		.=	"for further details please visit transportation office<br /><br />";
				
				
				$sEmailMessage		.=	"Thanks<br />";
				$sEmailMessage		.=	"Regards,<br />";
				$sEmailMessage		.=	"<a href=".$sCOMPANY_Link.">".$sCOMPANY_Name."</a></span>";
				//$sMessage			=	$sEmailMessage;
				
				
				//print($sMailMSG);
				$mail = new PHPMailer();
				$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
				$mail->From     = $sSUPPORT_EMAIL;
				$mail->FromName = $sCOMPANY_Name;
				$mail->AddAddress($rowEMAIL["email"]);
				$mail->IsHTML(true);                               // send as HTML
				$mail->Subject  =  	$sEmailSubject;
				$mail->Body    	= 	$sEmailMessage;
				if(!$mail->Send())
				{
				   $sMessage		=	fn_Print_MSG_BOX("<li>error in sending email please contact our support team $sSUPPORT_EMAIL, $mail->ErrorInfo","C_ERROR");
				}else{
					$sMessage		.=	fn_Print_MSG_BOX("reservation has been ".$_POST["drpstatus"]." <br />an email has been sent to requesting driver", "C_SUCCESS");
				}
			
			
	}*/
	if(isset($_POST["action"])	&& $_POST["action"]=="cancel"){
			$bCANCEL	=	0;
			if(isset($_POST["chkcancel"]) && $_POST["chkcancel"]!="")				$bCANCEL	=	"1";	else	$bCANCEL	=	"0";
			
			$sSQL="UPDATE tbl_reservations SET reservation_cancelled = ".$bCANCEL." WHERE res_id = ".$iRESERVATION_ID;
			//print($sSQL);
			$rsRES=mysql_query($sSQL) or die(mysql_error());
			$sMessage		=	fn_Print_MSG_BOX("reservation has been updated", "C_SUCCESS");
	}
	
	
	$sSQL	=	"SELECT CONCAT(vehicle_no, ' ',brand_name, ' ',year_manuf) AS vehicle_no,  CONCAT(f_name, ' ', l_name) AS driver_name, ".
	"planned_passngr_no, planned_depart_day_time, planned_return_day_time, CASE WHEN overnight = 1 THEN 'Yes' ELSE 'No' END overnight, ".
	"CASE WHEN childseat = 1 THEN 'Yes' ELSE 'No' END childseat, ".
	"destination, coord_approval, reservation_cancelled ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID;
	
	$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=mysql_num_rows($rsRESERVATION);	
	if($iRECORD_COUNT>0){
		$rowRESERVATION	=	mysql_fetch_array($rsRESERVATION);
	}else{
		$sMessage		=	fn_Print_MSG_BOX("no reservation found!", "C_ERROR");
	}mysql_free_result($rsRESERVATION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Update Reservation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript">function F_loadRollover(){} function F_roll(){}</script>
<script type="text/javascript" src="../assets/rollover.js"></script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_coord(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpstatus.value==""){
		sErrMessage='<li>please select status for reservation';
		iErrCounter++;
	}
	
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		frm.action.value='coord';
		frm.submit();
	}
}
function valid_cancel(frm){
		frm.action.value='cancel';
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
								   				<h1 style="margin-bottom: 0px;">VEHICLE TRIP STATUS</h1>
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
						<form name="frm1" action="edit_reservation.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="resid" value="<?=$iRESERVATION_ID?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="150" class="label">Vehicle:</td>
									<td width="300"><? echo $rowRESERVATION['vehicle_no'];?></td>
								</tr>
								<tr>
									<td width="150" class="label">Requesting Driver:</td>
									<td width="300"><? echo $rowRESERVATION['driver_name'];?></td>
								</tr>
								<tr>
									<td class="label">No.of Passengers:</td>
									<td><? echo $rowRESERVATION['planned_passngr_no'];?></td>
								</tr>
								
								
								<tr>
									<td class="label">Planned Departure Date Time:</td>
									<td><? echo fn_cDateMySql($rowRESERVATION['planned_depart_day_time'], 2);?></td>
								</tr>
								<tr>
									<td class="label">Planned Return Date Time:</td>
									<td><? echo fn_cDateMySql($rowRESERVATION['planned_return_day_time'], 2);?></td>
								</tr>
								<tr>
									<td class="label">Overnight:</td>
									<td><? echo $rowRESERVATION['overnight'];?></td>
								</tr>
								<tr>
									<td class="label">Child Seat Needed:</td>
									<td><? echo $rowRESERVATION['childseat'];?></td>
								</tr>
								<tr>
									<td class="label" valign="top">Destination:</td>
									<td><? echo stripslashes($rowRESERVATION['destination']);?></td>
								</tr>
								<!--<?	if($_SESSION["User_Group"]==$iGROUP_TC)	{?>
								<?	if($rowRESERVATION['coord_approval']!='Pending'){?>
								<tr>
									<td class="label" valign="top">TC Trip Status:</td>
									<td><? echo $rowRESERVATION['coord_approval'];?></td>
								</tr>
								<?	}else{?>
									<?	if($rowRESERVATION['reservation_cancelled']==1){?>
									
									<tr>
										<td class="label" valign="top">TC Trip Status:</td>
										<td><? echo $rowRESERVATION['coord_approval'];?></td>
									</tr>
									<tr>
										<td class="label">&nbsp;</td>
										<td>Trip is cancelled by Transportation Manager</td>
									</tr>
									<?	}else{?>
									<tr>
										<td class="label" valign="top">TC Trip Status:</td>
										<td><? 	fn_COORD_STATUS("drpstatus", $rowRESERVATION['coord_approval'], "150", "1", "Select Status");?></td>
									</tr>
									<?	}?>
								<?	}?>
								
								
								<?	}?>-->
								
								<?	if($_SESSION["User_Group"]==$iGROUP_TM)	{?>
								<!--<tr>
									<td class="label" valign="top">TC Trip Status:</td>
									<td><? echo $rowRESERVATION['coord_approval'];?></td>
								</tr>-->
								<tr>
									<td class="label" valign="top">Cancelled:</td>
									<td><input type="checkbox" name="chkcancel" value="1" <? if($rowRESERVATION['reservation_cancelled']==1) echo "checked";?> /></td>
								</tr>
								<?	}?>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<? if($rowRESERVATION['coord_approval']=='Pending' || $_SESSION["User_Group"]==$iGROUP_TM){?><tr><td></td><td><input type="button" name="btnSUBMIT" value="UPDATE TRIP" class="Button" <?	if($_SESSION["User_Group"]==$iGROUP_TM)	{?>onClick="valid_cancel(this.form);"<? }else {?> onClick="valid_coord(this.form);" <?	}?> style="width:150px;" /></td></tr><?	}?>
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