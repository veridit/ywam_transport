<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iSERVICE_ID		=	0;
	$iNOTIFICATION		=	0;
	if(isset($_POST["drpservc"]) && $_POST["drpservc"]!="")		$iSERVICE_ID	=	$_POST["drpservc"];
	
	
	if(isset($_POST["saction"])	&& $_POST["saction"]=="cancel"){
		
		$sSQL	=	"SELECT CONCAT(u.f_name, ' ', u.l_name) AS reserver_name, u.email, v.vehicle_no FROM tbl_srvc_resvs_details sr ".
		"INNER JOIN tbl_reservations r ON sr.res_id = r.res_id ".
		"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
		"INNER JOIN tbl_user u ON r.user_id = u.user_id ".
		"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
		"WHERE sr.srvc_id = ".$iSERVICE_ID." AND  ".
		"trip_id IS NULL AND abandon_id IS NULL AND reservation_cancelled = 1 AND cancelled_by_driver = 0 GROUP BY u.f_name, u.l_name, u.email, v.vehicle_no";
		//print($sSQL);
		$rsRES				=	mysql_query($sSQL) or die(mysql_error());
		$iNOTIFICATION		=		mysql_num_rows($rsRES);
		
		if($iNOTIFICATION>0){
			
			//FIRST UPDATE THE SERVICE RESERVATION AS CANCELLED
			$sSQL	=	"UPDATE tbl_srvc_resvs SET is_cancelled = 1 WHERE srvc_id = ".$iSERVICE_ID;
			mysql_query($sSQL) or die(mysql_error());
		
			//select the reservation message
			
			$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 18";
			$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
			$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 18";
			$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
			
			while($rowRES	=	mysql_fetch_array($rsRES)){
				
				$sRES_EMAIL	=	$rowRES['email'];
				$sVEHICLE_NO=	$rowRES['vehicle_no'];
				$sEDIT_MSG	=	str_replace('#VEHICLE NO#', $sVEHICLE_NO, $sMailMSG);
				//print($sEDIT_MSG);
				$mail = new PHPMailer();
				$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
				$mail->From     = $sSUPPORT_EMAIL;
				$mail->FromName = $sCOMPANY_Name;
				$mail->AddAddress($sRES_EMAIL);
				$mail->IsHTML(true);                               // send as HTML
				$mail->Subject  =  $sEmailSubject;
				$mail->Body    	= $sEDIT_MSG;
				if(!$mail->Send()){	   $sMessage		=	fn_Print_MSG_BOX("Error in Sending Email, $mail->ErrorInfo","C_ERROR");		}
								
				//$sMessage		.=	fn_Print_MSG_BOX($sEDIT_MSG, "C_SUCCESS");
			}
			
			if($iNOTIFICATION>0)		$sMessage	=	fn_Print_MSG_BOX("<li>Vehicle has been restored<li>".$iNOTIFICATION." drivers are notified for availability of this vehicle", "C_SUCCESS");
			
		}else{	//if no reservation was cancelled by that service then only update service reservation
			$sSQL	=	"UPDATE tbl_srvc_resvs SET is_cancelled = 1 WHERE srvc_id = ".$iSERVICE_ID;
			mysql_query($sSQL) or die(mysql_error());
			$sMessage		=	fn_Print_MSG_BOX("<li>Vehicle has been restored", "C_SUCCESS");
		}
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Restore Pulled Vehicle</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script language="JavaScript">
function valid_service(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpservc.value==""){
		sErrMessage='<li>please select vehicle to restore';
		iErrCounter++;
	}
				
	if (iErrCounter >0){
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		frm.submit();
	}
	
}
function fn_SHOW_VEHICLES(sPULLED_TYPE){
	
	
	//frm.saction.value	=	sPULLED_TYPE;
	$('#pulled-vehicles').html("<img src=../assets/images/loading_busy.gif border='0'>");
	$.get("ajax_data.php", {action: 'restore-vehicle', restore_type: sPULLED_TYPE}, function(data){			  	
			
		//$('#Message').html(data);
		//alert(data.substring(0,4));
		if(data.substring(0,4)=='<li>'){
			$('#Message').html(fn_draw_ErrMsg(data));
			$('#pulled-vehicles').html('');
		}else{
			$('#Message').html('');
			$('#pulled-vehicles').html(data);
			$('#restore-btn').html("<input type='button' name='btnSUBMIT' value='RESTORE VEHICLE' class='Button' onClick='valid_service(this.form);' style='width:165px;' />");
		}
				
				
	}, 'html');
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
								   				<h1 style="margin-bottom: 0px;">RESTORE PULLED VEHICLE</h1>
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
						<form name="frm1" method="post">
							<input type="hidden" name="saction" value="cancel"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td class="label">Restore Vehicle:</td>
									<td>
										<input type="radio" id="opttemporaryremoval" name="optmethod" value="Temporary" onClick="fn_SHOW_VEHICLES('restore-temp-pull');" />&nbsp;<div class="label left">From Temporary Removal</div>&nbsp;&nbsp;&nbsp;<input type="radio" id="optpermanentremoval" name="optmethod" value="Permanent" onClick="fn_SHOW_VEHICLES('restore-perm-pull');" />&nbsp;<div class="label left">From Indefinite Removal</div>
									</td>
								</tr>
								
								<tr>
								
									<td class="label" width="150"></td>
									<td width="200" id="pulled-vehicles"></td>
									
								</tr>
											
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									
									<td colspan="2" align="center" id="restore-btn"></td>
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