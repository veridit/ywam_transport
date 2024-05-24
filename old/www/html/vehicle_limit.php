<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
		
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	fn_Print_MSG_BOX("<li>These departments are always exempt from this policy: <br />Transportation, Campus Services, Presidents Fund and General Fund", "C_SUCCESS");		$sPOLICY		=	"";		$sFROM_DATE	=	date('Y-m-d');		$sTO_DATE	=	"";
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="policy"){
	
		if($_POST["drppolicy"]=="1")
			$sTO_DATE	=	date('Y-m-d');
		elseif($_POST["drppolicy"]=="2")
			$sTO_DATE	=	date('Y-m-d',strtotime(date("Y-m-d")." +2 day"));
		elseif($_POST["drppolicy"]=="3")
			$sTO_DATE	=	date('Y-m-d',strtotime(date("Y-m-d")." +30 day"));
			
		$sSQL	=	"SELECT CASE WHEN MAX(limit_id) IS NULL THEN 0 ELSE MAX(limit_id) END AS limit_id FROM tbl_3_vehicle_limit WHERE dept_id = '".$_POST["drpdept"]."' AND (		(from_date BETWEEN '".$sFROM_DATE."' AND '".$sTO_DATE."') OR (to_date BETWEEN '".$sFROM_DATE."' AND '".$sTO_DATE."'))";
		
		$rsDUP_POLICY	=	mysql_query($sSQL) or die(mysql_error());
		$rowDUP_POLICY	=	mysql_fetch_array($rsDUP_POLICY);
		if($rowDUP_POLICY['limit_id']!=0){
			$sMessage	=	fn_Print_MSG_BOX("Over-ride policy has already been defined for this department for the same time period", "C_SUCCESS");
		}else{
			$sSQL		=	"INSERT INTO tbl_3_vehicle_limit(soption, dept_id, user_id, from_date, to_date) VALUES(".$_POST["drppolicy"].", '".$_POST["drpdept"]."', ".$_SESSION["User_ID"].", '".$sFROM_DATE."', '".$sTO_DATE."')";
			mysql_query($sSQL) or die(mysql_error());
			$sMessage	=	fn_Print_MSG_BOX("Over-ride policy has been defined", "C_SUCCESS");
		}mysql_free_result($rsDUP_POLICY);
		
	}
	
	
	/*$sDEPART_DATETIME		=	'2012-10-19';
	$sPOLICY_TO_DATE		=	'2012-11-21';
	$sPOLICY_FROM_DATE		=	'2012-10-22';
	
	mysql_query("set @i = -1");
						$sSQL	=	"SELECT 'matched' FROM tbl_reservations WHERE '".$sDEPART_DATETIME."' IN (";
						$sSQL	.=	"SELECT DATE(ADDDATE('".$sPOLICY_FROM_DATE."', INTERVAL @i:=@i+1 DAY)) AS date FROM tbl_user HAVING @i < DATEDIFF('".$sPOLICY_TO_DATE."', '".$sPOLICY_FROM_DATE."')";
						$sSQL	.=	")";
						$rsRESULT	=	mysql_query($sSQL) or die(mysql_error());
	while($row	=	mysql_fetch_array($rsRESULT)){
		print($row['matched']."<br />");
	}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Override Vehicle Limit</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_policy(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpdept.value==""){
		sErrMessage=sErrMessage+'<li>please select department';
		iErrCounter++;
	}	
	
	if (frm.drppolicy.value==""){
		sErrMessage=sErrMessage+'<li>please select over-ride period';
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
								   				<h1 style="margin-bottom: 0px;">OVER RIDE 3 VEHICLE POLICY</h1>
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
						<form name="frm1" action="vehicle_limit.php" method="post">
							<input type="hidden" name="action" value="policy"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Department:</td>
									<td width="400"><?	fn_DEPARTMENT("drpdept", 0, "250", "1", "--Select Department--");?></td>
								</tr>
								<tr>
									<td class="label">Over-ride period:</td>
									<?		$arrPOLICY[0][0]	=	"1";		$arrPOLICY[0][1]	=	"Today";
											$arrPOLICY[1][0]	=	"2";		$arrPOLICY[1][1]	=	"Today + 2 days";
											$arrPOLICY[2][0]	=	"3";		$arrPOLICY[2][1]	=	"Today + 30 days";
									?>
									<td>
										<?	$sPOLICY	=	 "<select name='drppolicy' style='width:250px; size='1'>'";
											$sPOLICY	.=	 "<option value=''>--Select Override Policy--</option>";
											for($iCounter=0;$iCounter<=2;$iCounter++){
												$sPOLICY	.= "<option value='".$arrPOLICY[$iCounter][0]."'>".$arrPOLICY[$iCounter][1]."</option>";
											}	
											$sPOLICY	.= "</select>";
											echo $sPOLICY;
										?>
									</td>
								</tr>
																
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="ACTIVATE" class="Button" onClick="valid_policy(this.form);" style="width:150px;" /></td></tr>
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