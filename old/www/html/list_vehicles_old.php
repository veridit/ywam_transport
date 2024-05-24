<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
		
		//first delete child table records
		$sSQL	=	"DELETE ";	
	
		if(fn_DELETE_RECORD("tbl_vehicles", "vehicle_id", $_POST["vehicleid"]))
			$sMessage	=	fn_Print_MSG_BOX("vehicle has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! user is not been deleted", "C_ERROR");
		
	}
	
	$iVEHICLE_NO	=	0;
	$iCapacity		=	"";
	$iModelID		=	0;
	$iMakeID		=	0;
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	
	
	if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{$iVEHICLE_NO	=	$_POST["drpvehicle"];		$sCriteriaSQL	.=	" AND tbl_vehicles.vehicle_id = ".$iVEHICLE_NO;}
	if(isset($_POST["drpcapacity"]) && $_POST["drpcapacity"]!="")		{$iCapacity		=	$_POST["drpcapacity"];		$sCriteriaSQL	.=	" AND tbl_vehicles.passenger_cap IN (".$iCapacity.")";}
	if(isset($_POST["drptype"]) && $_POST["drptype"]!="")				{$iModelID		=	$_POST["drptype"];			$sCriteriaSQL	.=	" AND tbl_vehicles.model= ".$iModelID;}
	//if(isset($_POST["drpmake"]) && $_POST["drpmake"]!="")				{$iMakeID		=	$_POST["drpmake"];			$sCriteriaSQL	.=	" AND tbl_vehicles.make_id = ".$iMakeID;}-->
	
	$sSQL	=	"SELECT vehicle_id, vehicle_no, v_type, brand_name, restriction, passenger_cap, condition_tech FROM tbl_vehicles ".
	"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
	"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"WHERE 1=1 ".$sCriteriaSQL;
	//print($sSQL);
	$rsVEHICLES		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLES);
	if($iRECORD_COUNT<=0){
		$sMessage		=	fn_Print_MSG_BOX("no vehicle found", "C_ERROR");
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>List Vehicles</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript">
<!--
function F_loadRollover(){} function F_roll(){}
//-->
</script>
<script type="text/javascript" src="../assets/rollover.js">
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">



<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}

function fn_DELETE_VEHICLE(iVEHICLEID){
	document.frm1.vehicleid.value=iVEHICLEID;
	document.frm1.action.value='delete';
	document.frm1.submit();
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
								   				<h1 style="margin-bottom: 0px;">LIST VEHICLES</h1>
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
						<form name="frm1" action="list_vehicles.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="vehicleid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="680" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="100">Vehicle No:<br /><?	fn_VEHICLE('drpvehicle', $iVEHICLE_NO, "100", "1", "--All--");?></td>
												<!--<td class="label" width="120">Make:<br /><?	fn_VEHICLE_MAKE('drpmake', $iMakeID, "100", "1", "--All--");?></td>-->
												<td class="label" width="120">Type:<br /><?	fn_VEHICLE_TYPE('drptype', $iModelID, "100", "1", "--All--");?></td>
												<td class="label" width="120">Capacity:<br />
												<?
													$arrCAPACITY[0][0]	=	"1,2,3";	$arrCAPACITY[0][1]	=	"1-3 psgr";
													$arrCAPACITY[1][0]	=	"4,5,6";	$arrCAPACITY[1][1]	=	"4-6 psgr";
													$arrCAPACITY[2][0]	=	"7,8,9";	$arrCAPACITY[2][1]	=	"7-9 psgr";
													$arrCAPACITY[3][0]	=	"10,11,12";	$arrCAPACITY[3][1]	=	"10-12 psgr";
													$arrCAPACITY[4][0]	=	"13,14";	$arrCAPACITY[4][1]	=	"13-14 psgr";
												?>
												
													<select name="drpcapacity" size="1" style="width:100px;">
														<option value="">--All--</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=4;$iCOUNTER++){?>
															<option value="<?=$arrCAPACITY[$iCOUNTER][0]?>" <? if($arrCAPACITY[$iCOUNTER][0]==$iCapacity) echo "selected";?>><?=$arrCAPACITY[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_vehicles.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Vehicle_No,Make,Type,Restrictions,Capacity,Condition'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Vehicle No</td>
												<td width="50" class="colhead">Make</td>
												<td width="100" class="colhead">Type</td>
												<td width="100" class="colhead">Restriction &amp; Notes</td>
												<td width="75" class="colhead">Capacity</td>
												<td width="100" class="colhead">Condition</td>
												<td width="100" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowVEHICLE["vehicle_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["restriction"].",".$rowVEHICLE["passenger_cap"].",".$rowVEHICLE["condition_tech"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['brand_name'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['v_type'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['restriction'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['passenger_cap'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['condition_tech'];?></td>

															<td class="coldata"><? //if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_MODIFY)){?><a href="edit_vehicle.php?vid=<? echo $rowVEHICLE['vehicle_id'];?>">view</a><?	//}?>&nbsp;<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('are you sure to delete this vehicle?')) {fn_DELETE_VEHICLE(<? echo $rowVEHICLE['vehicle_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
									}mysql_free_result($rsVEHICLES);	?>
										</table>
									</td>
								</tr>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
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
 