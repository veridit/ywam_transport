<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iVEHICLE_ID		=	0;
	$iRECORD_COUNT		=	0;
	
	//==============vehicle fields===============
	$sSOLD_DATE		=	"";
					
	if(isset($_REQUEST["vid"]))	$iVEHICLE_ID		=	$_REQUEST["vid"];
	
	if(isset($_POST["action"])	&& $_POST["action"]=="sell-vehicle"){
						
		$sSOLD_DATE		=	substr($_POST["txtsolddate"],6, 4);
		$sSOLD_DATE		.=	"-".substr($_POST["txtsolddate"],0, 2);
		$sSOLD_DATE		.=	"-".substr($_POST["txtsolddate"],3, 2);
	
		
		$sSQL="UPDATE tbl_vehicles SET sold = 1, sold_date='".$sSOLD_DATE."', vehicle_no = CONCAT(vehicle_no,'".date('mY')."') WHERE vehicle_id = ".$iVEHICLE_ID; 
		//print($sSQL);
		$rsVEHICLE=mysql_query($sSQL) or die(mysql_error());
		
		$sMessage		=	fn_Print_MSG_BOX("vehicle updated to sold successfully", "C_SUCCESS");
	}
	
	$sSQL	=	"SELECT CASE WHEN sold_date IS NULL THEN '0000-00-00' ELSE sold_date END AS sold_date FROM tbl_vehicles WHERE vehicle_id = ".$iVEHICLE_ID;
	//print($sSQL);
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	
	$iRECORD_COUNT	=mysql_num_rows($rsVEHICLE);
	
	if($iRECORD_COUNT>0){
		$rowVEHICLE			=	mysql_fetch_array($rsVEHICLE);
		$sSOLD_DATE			=	fn_cDateMySql($rowVEHICLE['sold_date'], 1);
	}mysql_free_result($rsVEHICLE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Sell Disponse of Vehicle</title>
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
function fn_VIEW_VEHICLE(frm){
	if(frm.vid.value!=""){
		frm.action.value	=	'';
		frm.submit();
	}
}
function valid_vehicle(frm){

	var sErrMessage='';
	var iErrCounter=0;

	
	if(frm.vid.value==""){
		sErrMessage='<li>please select vehicle no';
		iErrCounter++;
	}
	
	
	
		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else{
		frm.action.value	=	'sell-vehicle';
		frm.submit();
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
								   				<h1 style="margin-bottom: 0px;">SELL DISPOSE OF VEHICLE</h1>
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
						<form name="frm1" action="sell_vehicle.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<!--<input type="hidden" name="vid" value="<?=$iVEHICLE_ID?>" />-->
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Vehicle No:</td>
									<td width="400">
										<?	fn_VEHICLE('vid', $iVEHICLE_ID, "130", "1", "--Select Vehicle--", "fn_VIEW_VEHICLE(this.form);");?>
									</td>
								</tr>
								
								<tr>
									<td class="label">Sold Date:</td>
									<td>
										<input readonly="" type="text" name="txtsolddate" id="txtsolddate" value="<?=$sSOLD_DATE?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="SELL-DISPOSE" class="Button" onClick="valid_vehicle(this.form);" style="width:130px;" /></td></tr>
								
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