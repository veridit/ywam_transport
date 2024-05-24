<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	/*$sSQL	=	"SELECT ".
	"CASE WHEN td.res_id IS NULL THEN ".
	"		CASE WHEN a.res_id IS NULL THEN ".
	"				CASE WHEN r.cancelled_by_driver = 0 THEN ".
	"					CASE WHEN r.reservation_cancelled = 0 THEN 'PENDING' ELSE 'DELETED' END ".
	"				ELSE	'CANCEL'	END ".
	"		ELSE 'ABANDONED'	END ".
	"ELSE 'CLOSED'	END AS trip_status ".
	"FROM tbl_reservations r LEFT OUTER JOIN tbl_trip_details td ON r.res_id = td.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
	"WHERE r.res_id = ".$iRESV_ID;
	
	print($sSQL);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Find Reservation By No</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script language="JavaScript">
	function fn_FIND_RESV(){
	// ERIK: Added next line temporary.  var sRptName	=	'';
	var sRptName	=	'list_pending_report';
		if($('#txtresvno').val()!=""){
	
			$('#list_sub_reports').html("<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>");
			$.get("ajax_data.php", {action: 'find-resv', resid: $('#txtresvno').val()}, function(data){	
				if (data=="ERROR"){
					fn_draw_ErrMsg("<li class='bold-font'>Error!!! Invalid Reservation number, details not found");
					$('#list_sub_reports').html('&nbsp;');
				}else{					
					if(data=='PENDING')		{sRptName	=	'list_pending_report';					fn_CHANGE_PG_NAME('OPEN TRIPS (pending)');}
					if(data=='DELETED')		{sRptName	=	'list_deleted_trips';					fn_CHANGE_PG_NAME('DELETED TRIPS');}
					if(data=='CLOSED')		{sRptName	=	'list_closed_trips';					fn_CHANGE_PG_NAME('CLOSED TRIPS');}
					if(data=='ABANDONED')	{sRptName	=	'list_abandon_trips';					fn_CHANGE_PG_NAME('ABANDONED TRIPS');}
					if(data=='CANCEL')		{sRptName	=	'list_driver_deleted_trips';			fn_CHANGE_PG_NAME('DRIVER CANCELLED TRIPS');}
					//alert("'"+data+"'");
					//$('#list_sub_reports').html(data);
					$('#list_sub_reports').html("<iframe id='myFrame' src='"+sRptName+".php?action=search&txtresvno="+$('#txtresvno').val()+"' width='949' frameborder='0' onload='adjustMyFrameSize();'>Browser not supportive</iframe>");
				}
			}, 'html');
			//$('#list_sub_reports').html("<iframe id='myFrame' src='"+sRptName+".php' width='949' frameborder='0' onload='adjustMyFrameSize();'>Browser not supportive</iframe>");
		}else{
			fn_draw_ErrMsg("<li class='bold-font'>Please enter Reservation Number to search");
		}
	}
</script>
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
								   				<h1 style="margin-bottom: 0px;" id="page-heading">FIND RESERVATION BY NO</h1>
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
							
							<div id="Message"></div>
							
							<div style="width:500px; margin:0 auto; text-align:left;">
								
								<span class="label">
									Resv. No:<br />
									<input type="text" name="txtresvno" id="txtresvno" value="" style="width:100px;" onKeyDown="return validateNumber(event);" />
								</span>
								<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_FIND_RESV();" />
							</div>
							
							<div style="clear:both;"></div>
							
							<br />
							
							<div id="list_sub_reports"></div>
						
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
 
