<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>To find a trip by using its number, use the function 'Find Resv by no' ","C_SUCCESS");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>List Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
</head>
<body style="margin: 0px;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		<!--start header	-->
		<? include('inc_header.php');	?>
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
								   				<h1 style="margin-bottom: 0px;" id="page-heading">LIST TRIPS</h1>
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
				<tr><td colspan="2"></td><td id="Message" width="949"><?=$sMessage?></td><td width="15">&nbsp;</td></tr>
               	<tr valign="top" align="left">
                	<td colspan="2"></td>
					
					<!--
					CLOSED list_trips.php=================>list_closed_trips.php======================view_close_trip.php====>closed_trip_details.php
					PENDING list_reservations.php=========>list_pending_report.php====================add_tripdetails.php====>close_trip.php=====edit_reservation.php=====>edit_pending_trip.php
					ABANDON list_abandon.php==============>list_abandon_trips.php
					DELETED list_deleted.php==============>list_deleted_trips.php=====================deleted_trip_details.php
					CANCELLED driver_deleted_trips.php====>list_driver_deleted_trips.php==============driver_cancelled_trip.php=====>view_driver_cancelled_trip.php
					-->
                	<td width="949" class="TextObject" align="center">
						<div style="width:500px; margin:0 auto; text-align:left;">
							<!--Trip Status:<br />
							<select name="drppending" size="1" style="width:450px;" onchange="fn_LOAD_SUB_REPORT(this.value);">
								<option value="" selected>-----------------------------------------Select Trip Status-----------------------------------------</option>
								<option value="list_pending_report" <? //if($sStatus=="list_pending_report") echo "selected";?>>Pending</option>
								<option value="list_closed_trips" <? //if($sStatus=="list_closed_trips") echo "selected";?>>Closed</option>
								<option value="list_abandon_trips" <? //if($sStatus=="list_abandon_trips") echo "selected";?>>Abandoned</option>
								<option value="list_driver_deleted_trips" <? //if($sStatus=="list_driver_deleted_trips") echo "selected";?>>Cancelled</option>
								<option value="list_deleted_trips" <? //if($sStatus=="list_deleted_trips") echo "selected";?>>Deleted</option>
							</select>-->
							<span class="label">
								<input type="radio" name="optrptoptions" id="pending" value="list_pending_report" onclick="fn_LOAD_SUB_REPORT(this.value);" /><div class="left">Open Trips&nbsp;&nbsp;&nbsp;</div>
								<input type="radio" name="optrptoptions" id="closed" value="list_closed_trips" onclick="fn_LOAD_SUB_REPORT(this.value);" /><div class="left">Closed&nbsp;&nbsp;&nbsp;</div>
								<input type="radio" name="optrptoptions" id="abandoned" value="list_abandon_trips" onclick="fn_LOAD_SUB_REPORT(this.value);" /><div class="left">Abandoned&nbsp;&nbsp;&nbsp;</div>
								<input type="radio" name="optrptoptions" id="cancelled" value="list_driver_deleted_trips" onclick="fn_LOAD_SUB_REPORT(this.value);" /><div class="left">Cancelled&nbsp;&nbsp;&nbsp;</div>
								<input type="radio" name="optrptoptions" id="deleted" value="list_deleted_trips" onclick="fn_LOAD_SUB_REPORT(this.value);" /><div class="left">Deleted&nbsp;&nbsp;&nbsp;</div>
							</span>
							</div>
							
							<div style="clear:both;"></div>
							
							<br />
							
							<div id="list_sub_reports"></div>
						
                	</td>
                	<td width="15">&nbsp;</td>
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
 