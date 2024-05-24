<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	fn_Print_MSG_BOX("<li>Only trips with depart time in the past are available here", "C_SUCCESS");
	$iRESERVATION_ID	=	0;
	$iRECORD_COUNT		=	0;
	$sASSIGNED_ID		=	"";
	$sASSIGNED_NAME		=	"";
	$sASSIGNED_EMAIL	=	"";
	
	$sRESERVED_ID		=	"";
	$sRESERVED_NAME		=	"";
	$sRESERVED_EMAIL	=	"";
	
	$iVEHICLE_MILE_COST	=	0;
	
	$iSECONDS			=	0;
	
	
	if(isset($_REQUEST["resid"]) && $_REQUEST["resid"]!="")		$iRESERVATION_ID	=	$_REQUEST["resid"];
	if(isset($_POST["action"])	&& $_POST["action"]=="addabandon"){
				
			$sSQL	=	"SELECT abandon_id FROM tbl_abandon_trips WHERE res_id = ".$iRESERVATION_ID;
			$rsCHKDUPLICATE	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsCHKDUPLICATE)>0){
				$sMessage		=	fn_Print_MSG_BOX("<li>trip already been marked as abandon", "C_ERROR");
			}else{
				
				$sSQL	=	"SELECT TIMESTAMPDIFF(SECOND, planned_return_day_time, NOW()) FROM tbl_reservations WHERE res_id = ".$iRESERVATION_ID;
				$rsRETURN_CHK	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsRETURN_CHK)>0){
					list($iSECONDS)	=	mysql_fetch_row($rsRETURN_CHK);
					//print("SECONDS====".$iSECONDS);
					if(intval($iSECONDS)<=0){
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>return date or time is not been passed, you can't mark this trip as abandon", "C_ERROR");
					}else{
					
						$iVEHICLE_MILE_COST		=	fn_VEHICLE_PER_MILE_COST($iRESERVATION_ID);		//extract per mile charge for vehicle of current reservations
						
						
						$sSQL="INSERT INTO tbl_abandon_trips(notes, res_id, user_id, mile_charges) ".
						"VALUES('".addslashes($_POST["txtnotes"])."', ".$iRESERVATION_ID.", ".$_SESSION["User_ID"].", ".$iVEHICLE_MILE_COST.")";
						//print($sSQL);
						$rsABANDON=mysql_query($sSQL) or die(mysql_error());
						$sMessage		.=	fn_Print_MSG_BOX("<li>trip has been marked as abandon", "C_SUCCESS");
						
						if(isset($_POST["chkremind"]) && $_POST["chkremind"]== "1"){		//if checkbox is selected then send an email to assigned driver
					
							$sSQL	=	"SELECT r.assigned_driver AS assigned_id, CONCAT(a.f_name, ' ', a.l_name) AS assigned_name, a.email, r.user_id AS resv_id, CONCAT(resv.f_name, ' ', resv.l_name) AS resv_name, resv.email, r.planned_depart_day_time FROM tbl_user a INNER JOIN tbl_reservations r ON a.user_id = r.assigned_driver ".
							"INNER JOIN tbl_user resv ON r.user_id = resv.user_id WHERE r.res_id = ".$iRESERVATION_ID;
							$rsASSIGNED_DRIVER	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsASSIGNED_DRIVER)>0){
								list($sASSIGNED_ID, $sASSIGNED_NAME, $sASSIGNED_EMAIL, $sRESERVED_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sPLANNED_DEPART_DATETIME)	=	mysql_fetch_row($rsASSIGNED_DRIVER);
							}mysql_free_result($rsASSIGNED_DRIVER);
							
							$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 19";
							$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
							$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -1)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 19";
							$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
							
							if($sASSIGNED_ID!=$sRESERVED_ID){
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sASSIGNED_NAME, $sASSIGNED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
							}else{
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
							}
							
						}
						
						
					}
				}mysql_free_result($rsRETURN_CHK);
			
			}mysql_free_result($rsCHKDUPLICATE);
	}
	
	if(!isset($_POST["action"]) && $iRESERVATION_ID!=0){
		$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
		"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
		"tbl_vehicles.vehicle_no, CONCAT(tbl_user.f_name,' ', tbl_user.l_name) AS requestor_name, tbl_reservations.user_id, tbl_reservations.assigned_driver, ".
		"home.dept_name AS home_dept, bill.dept_name AS bill_dept ".
		"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
		"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
		"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
		"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
		"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID." AND tbl_reservations.coord_approval = 'Approved' AND ".
		"tbl_reservations.planned_depart_day_time < NOW() AND ".
		"reservation_cancelled = 0 AND cancelled_by_driver = 0 AND tbl_trip_details.res_id AND tbl_abandon_trips.res_id IS NULL";
		//print($sSQL);
		$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsRESERVATION)<=0){$sMessage	=		fn_Print_MSG_BOX("Please select valid pending trip or trip has been closed or already abandoned", "C_ERROR");}
		else{
			$iRECORD_COUNT	=	mysql_num_rows($rsRESERVATION);
			$rowRESERVATION	=	mysql_fetch_array($rsRESERVATION);
			$iVEHICLE_ID	=	$rowRESERVATION['vehicle_id'];
			$iREQUESTOR_NAME=	$rowRESERVATION['requestor_name'];
			$sHOME_DEPT		=	$rowRESERVATION['home_dept'];
			$sBILL_DEPT		=	$rowRESERVATION['bill_dept'];
			if($rowRESERVATION['user_id']!=$rowRESERVATION['assigned_driver'] && $rowRESERVATION['assigned_driver']!=0)			$sASSIGNED_DRIVER=	fn_GET_ASSIGNED_DRIVER($rowRESERVATION['assigned_driver']);}
	}

function fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sDRIVER_NAME, $sDRIVER_EMAIL, $sMSG, $sSUBJECT, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){

		global $sMessage;
		$sMailMSG		=	str_replace('#departdate#', fn_cDateMySql($sPLANNED_DEPART_DATETIME,2), str_replace('#resvno#', $iRESERVATION_ID, str_replace('#username#', $sDRIVER_NAME, $sMSG)));
					
		//print("<br />".$sMailMSG);
		$mail = new PHPMailer();
		$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
		$mail->From     = $sSUPPORT_EMAIL;
		$mail->FromName = $sCOMPANY_Name;
		$mail->AddAddress($sDRIVER_EMAIL);
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  $sSUBJECT;
		$mail->Body    	= $sMailMSG;
		if(!$mail->Send()){			   $sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email, $mail->ErrorInfo","C_ERROR");	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Mark Trip Abandoned</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">

<script type="text/JavaScript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function fn_LOAD_RESRVD_BY(iRES_ID){
	if(iRES_ID!=""){
		$.get("ajax_data.php", {action: 'load-reserved-by', rid: iRES_ID}, function(data){			  	
					if (data=="ERROR"){
						$('#Message').html("<li class='bold-font'>Error!!! in loading requesting driver and overdue period");
					}else{					
						$('#txtrequestdriver').val(data.substring(7, data.indexOf('overdue=')));
						$('#txtoverdue').val(data.substring(data.indexOf('overdue=')+8, data.length) + ' Days');
					}
		}, 'html');
	}else{
		$('#txtrequestdriver').val('');
	}
}


function valid_trip(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.resid.value==""){
		sErrMessage='<li>please select trip to abandon';
		iErrCounter++;
	}
	
	
	if (frm.txtnotes.value==""){
		sErrMessage=sErrMessage+'<li>please enter some notes';
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
								   				<h1 style="margin-bottom: 0px;">MARK TRIP ABANDONED</h1>
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
						<form name="frm1" action="add_abandon.php" method="post">
							<input type="hidden" name="action" value="addabandon"	/>
							<!--<input type="hidden" name="resid" value="<? //echo $iRESERVATION_ID;?>" />-->
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?
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
										"AND tbl_reservations.planned_depart_day_time < NOW() ".
										"ORDER BY tbl_reservations.res_id DESC";
										//print($sSQL);
										$rsROWS		=	mysql_query($sSQL) or die(mysql_error());
										
										//if($iRECORD_COUNT>0){
									?>
								<tr>
									<td width="150" class="label">Reservation:</td>
									<td width="450">
									<?	if(mysql_num_rows($rsROWS)>0){	?>
										
										
									<select name="resid" style="width:430px;" size="1" onChange="fn_LOAD_RESRVD_BY(this.value);">
										<option value="" selected>Select Reservation</option>
										<?	while($rowROWS	=	mysql_fetch_array($rsROWS)){?>
											<option value="<? echo $rowROWS['res_id'];?>" <? if($iRESERVATION_ID==$rowROWS['res_id']) echo "selected";?>>R-No:&nbsp;<?=$rowROWS['res_id']?>&nbsp;V-No:&nbsp;<?=$rowROWS['vehicle_no']?> FROM <?=fn_cDateMySql($rowROWS['planned_depart_day_time'],2)?> TO <?=fn_cDateMySql($rowROWS['planned_return_day_time'],2)?></option>
										<?	}?>
									</select>
									<?	}?>
									</td>
								</tr>
								<tr>
									<td class="label">Reserved By:</td>
									<td><input readonly="" type="text" id="txtrequestdriver" name="txtrequestdriver" value=""  style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label">Over Due:</td>
									<td><input readonly="" type="text" id="txtoverdue" name="txtoverdue" value=""  style="width:150px;"  /></td>
								</tr>
								<tr>
									<td class="label" valign="top">TM note:</td>
									<td><textarea name="txtnotes" id="txtnotes" cols="75" rows="7" style="width:425px;"></textarea></td>
								</tr>
								
								<tr>
									<td class="label" valign="top"></td>
									<td class="label"><input type="checkbox" name="chkremind" value="1" />&nbsp;Send email Reminder</td>
								</tr>
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="MARK AS ABANDON" class="Button" onClick="valid_trip(this.form);" style="width:165px;" /></td></tr>
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