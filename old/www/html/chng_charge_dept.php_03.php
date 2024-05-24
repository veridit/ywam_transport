<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iRESERVATION_ID	=	0;
	$iRECORD_COUNT		=	0;
	$iVEHICLE_ID		=	0;
	$iREQUESTOR_NAME	=	"";
	$sASSIGNED_DRIVER	=	"";
	$sHOME_DEPT			=	"";
	$sBILL_DEPT			=	"";
			
	if(isset($_POST["action"])	&& $_POST["action"]=="changedept"){
	
		$iRESERVATION_ID	=	$_POST["resid"];
	
		$sSQL="UPDATE tbl_reservations SET billing_dept = ".$_POST["drpbilldept"]." WHERE res_id = ".$iRESERVATION_ID;
		$rsTRIP=mysql_query($sSQL) or die(mysql_error());
		
		$sMessage		=	fn_Print_MSG_BOX("charge department has been changed", "C_SUCCESS");
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Change Charge Department</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>


<script type="text/javascript">
function valid_dept(frm){

	var sErrMessage='';
	var iErrCounter=0;
	var sMileageErr	=	"";
	
	if (frm.resid.value==""){
		sErrMessage='<li>please select trip to change billing department';
		iErrCounter++;
	}
	
	
	if (frm.drpbilldept.value == ""){
		sErrMessage=sErrMessage+'<li>please select billing department';
		iErrCounter++;
	}
	
		
		
	if (iErrCounter >0 ){
		fn_draw_ErrMsg(sErrMessage);
		
	}else
		frm.submit();
	
}
function ajax_data(rid){
var xmlhttp;
var sData	=	"";
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
 xmlhttp.open("GET", sURL+"action=change_dept&rid="+rid,true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('loadingimage'))
				document.getElementById('loadingimage').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		
		sData		=	xmlhttp.responseText;
				
		document.frm1.txthomedept.value			=	sData.substring(sData.indexOf('h=')+2,sData.length);
		document.frm1.txtrequestdriver.value	=	sData.substring(sData.indexOf('r=')+2,sData.indexOf('h='));
		document.getElementById('billing_dept').innerHTML		=	sData.substring(0, sData.indexOf('r='));
		
		
		
		document.getElementById('loadingimage').innerHTML = '';
		document.getElementById('loadingimage').style.display = 'none';
	
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
								   				<h1 style="margin-bottom: 0px;">CHANGE CHARGE DEPARTMENT OF CLOSED TRIP</h1>
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
						<form name="frm1" action="chng_charge_dept.php" method="post">
							<input type="hidden" name="action" value="changedept"	/>
							<!--<input type="hidden" name="resid" value="<? echo $iRESERVATION_ID;?>" />-->
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<?
										$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
										"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
										"tbl_vehicles.vehicle_no ".
										"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
										"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
										"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
										"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
										"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
										"WHERE tbl_reservations.coord_approval = 'Approved' AND ".
										"reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
										"ORDER BY tbl_reservations.res_id DESC";
										$rsROWS		=	mysql_query($sSQL) or die(mysql_error());
										
										//if($iRECORD_COUNT>0){
									?>
								<tr>
									<td width="150" class="label">Reservation:</td>
									<td width="450">
									<?	if(mysql_num_rows($rsROWS)>0){	?>
										
										
									<select name="resid" style="width:430px;" size="1" onChange="ajax_data(this.value);">
										<option value="" selected>Select Reservation</option>
										<?	while($rowROWS	=	mysql_fetch_array($rsROWS)){?>
											<option value="<? echo $rowROWS['res_id'];?>" <? if($iRESERVATION_ID==$rowROWS['res_id']) echo "selected";?>>R-No:&nbsp;<?=$rowROWS['res_id']?>&nbsp;V-No:&nbsp;<?=$rowROWS['vehicle_no']?> FROM <?=fn_cDateMySql($rowROWS['planned_depart_day_time'],2)?> TO <?=fn_cDateMySql($rowROWS['planned_return_day_time'],2)?></option>
										<?	}?>
									</select>
									<?	}?>
									</td>
								</tr>
								<tr>
									<td class="label">Rsvrd by:</td>
									<td>
										<div style="float:left;"><input readonly="" type="text" name="txtrequestdriver" value="<? if($iREQUESTOR_NAME!="")	echo $iREQUESTOR_NAME;?>"  style="width:170px;"  />&nbsp;&nbsp;&nbsp;</div>										
									</td>
								</tr>
								<tr>
									<td class="label">Home Dept:</td>
									<td><input readonly="" type="text" name="txthomedept" value="<? if ($sHOME_DEPT!='') echo $sHOME_DEPT; ?>" style="width:170px;" /></td>
								</tr>
								<tr>
									<td class="label">Charge Dept:</td>
									<td>
										<div id="billing_dept">
											<select name="drpbilldept" size="1" style="width:170px;"><option value="">--Charge Department---</option></select>
										</div>
									</td>
								</tr>
								
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="CHANGE CHARGE DEPARTMENT" class="Button" onClick="valid_dept(this.form);" style="width:190px;" /></td></tr>
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
