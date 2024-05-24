<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iVEHICLE_NO	=	0;
	$iCapacity		=	"";
	$iModelID		=	0;
	$iMakeID		=	0;
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sAVAILABLE	=	"";
	$sFuel			= 0;
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
		
		//first delete child table records
		$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE vehicle_id = ".$_POST["vehicleid"];
		$rsRES	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsRES)>0){
			while($rowRES	=	mysql_fetch_array($rsRES)){
				$sSQL		=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowRES['res_id'];
				$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
				$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowRES['res_id'];
				$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
			}
		}mysql_free_result($rsRES);
		$sSQL			=	"DELETE FROM tbl_reservations WHERE vehicle_id = ".$_POST["vehicleid"];
		$rsDEL_RES		=	mysql_query($sSQL) or die(mysql_error());
		
		$sSQL			=	"DELETE FROM tbl_shop_tasks WHERE vehicle_id = ".$_POST["vehicleid"];
		$rsDEL_TASKS	=	mysql_query($sSQL) or die(mysql_error());
		
		//service pulling
		$sSQL			=	"DELETE FROM tbl_srvc_resvs_details WHERE srvc_id IN (SELECT srvc_id FROM tbl_srvc_resvs WHERE vehicle_id = ".$_POST["vehicleid"].")";
		$rsSRVC_DETAILS	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL			=	"DELETE FROM tbl_srvc_resvs WHERE vehicle_id = ".$_POST["vehicleid"];
		$rsSRVC			=	mysql_query($sSQL) or die(mysql_error());
		
	
		if(fn_DELETE_RECORD("tbl_vehicles", "vehicle_id", $_POST["vehicleid"]))
			$sMessage	=	fn_Print_MSG_BOX("<li>vehicle has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("<li>error! vehicle is not been deleted", "C_ERROR");
		
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
			if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{$iVEHICLE_NO	=	mysql_real_escape_string($_POST["drpvehicle"]);		$sCriteriaSQL	.=	" AND v.vehicle_id = ".$iVEHICLE_NO;}
			if(isset($_POST["drpcapacity"]) && $_POST["drpcapacity"]!="")		{$iCapacity		=	mysql_real_escape_string($_POST["drpcapacity"]);		$sCriteriaSQL	.=	" AND v.passenger_cap IN (".$iCapacity.")";}
			if(isset($_POST["drptype"]) && $_POST["drptype"]!="")				{$iModelID		=	mysql_real_escape_string($_POST["drptype"]);			$sCriteriaSQL	.=	" AND v.model= ".$iModelID;}
			if(isset($_POST["drprestricted"]) && $_POST["drprestricted"]!="")	{
				$sAVAILABLE		=	$_POST["drprestricted"];
				if($sAVAILABLE=="Available")		$sCriteriaSQL	.=	" AND (s.is_cancelled = 1 OR s.is_cancelled IS NULL)";
				if($sAVAILABLE=="Not-Available")	$sCriteriaSQL	.=	" AND s.is_cancelled = 0";
			}
			
			if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF)			$sCriteriaSQL	.=				" AND v.restricted = 1";
			
			$sSQL	=	"SELECT v.*, v_type, brand_name, CONCAT(f_name,' ',l_name) AS user_name , CASE WHEN shop_task.next_oil_due IS NULL THEN 'N/A' ELSE shop_task.next_oil_due END next_oil_due, ".
			"CASE WHEN s.is_cancelled IS NULL OR s.is_cancelled = 1 THEN 'Yes' ELSE 'No' END AS status ".
			"FROM tbl_vehicles v ".
			"INNER JOIN tbl_vehicle_type ON v.model = tbl_vehicle_type.v_type_id ".
			"INNER JOIN tbl_vehicle_brand ON v.make_id = tbl_vehicle_brand.brand_id ".
			"INNER JOIN tbl_user ON v.user_id = tbl_user.user_id ".
			"LEFT OUTER JOIN (SELECT vehicle_id, MAX(next_oil) AS next_oil_due FROM tbl_shop_tasks) shop_task ON v.vehicle_id = shop_task.vehicle_id ".
			"LEFT OUTER JOIN (SELECT s.vehicle_id, s.is_cancelled, MAX(s.srvc_id) AS max_srvc FROM tbl_srvc_resvs s GROUP BY s.vehicle_id) s ON v.vehicle_id = s.vehicle_id ".
			"WHERE 1=1 ".$sCriteriaSQL." ORDER BY (v.vehicle_no+0)";
			
			//"CASE WHEN v.restricted = 1 THEN 'Yes' ELSE 'No' END AS status ".
			//print($sSQL);
			$rsVEHICLES		=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLES);
			if($iRECORD_COUNT<=0){
				$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>no vehicle found", "C_ERROR");
			}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Vehicles</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="generator" content="Bluefish 2.2.8" >

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
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
                	<td width="949" class="TextObject" align="center">
						<form name="frm1" action="list_vehicles.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="vehicleid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<?	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?><td class="label" width="100">Vehicle No:<br /><?	fn_VEHICLE('drpvehicle', $iVEHICLE_NO, "100", "1", "--All--");?></td><?	}?>
												<!--<td class="label" width="120">Make:<br /><?	fn_VEHICLE_MAKE('drpmake', $iMakeID, "100", "1", "--All--");?></td>-->
												<td class="label" width="120">Type:<br /><?	fn_VEHICLE_TYPE('drptype', $iModelID, "100", "1", "--All--");?></td>
												<td class="label" width="120">Capacity:<br />
												<?
													$arrCAPACITY[0][0]	=	"1,2,3,4,5";	$arrCAPACITY[0][1]	=	"1-5 psgr";
													$arrCAPACITY[1][0]	=	"6,7";			$arrCAPACITY[1][1]	=	"6-7 psgr";
													$arrCAPACITY[2][0]	=	"8,9,10,11,12";	$arrCAPACITY[2][1]	=	"8-12 psgr";
													$arrCAPACITY[3][0]	=	"13,14,15";		$arrCAPACITY[3][1]	=	"13-15 psgr";
												?>
												
													<select name="drpcapacity" size="1" style="width:100px;">
														<option value="">--All--</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=3;$iCOUNTER++){?>
															<option value="<?=$arrCAPACITY[$iCOUNTER][0]?>" <? if($arrCAPACITY[$iCOUNTER][0]==$iCapacity) echo "selected";?>><?=$arrCAPACITY[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												
												<?	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?>
												<td class="label" width="120">Status:<br />
												<?	$arrAVAILABLE[0]	=	"Available";
													$arrAVAILABLE[1]	=	"Not-Available";
												?>
												
													<select name="drprestricted" size="1" style="width:130px;">
														<option value="">--All--</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrAVAILABLE[$iCOUNTER]?>" <? if($arrAVAILABLE[$iCOUNTER]==$sAVAILABLE) echo "selected";?>><?=$arrAVAILABLE[$iCOUNTER]?></option>
														<?	}?>
													</select>
												</td>
												<?	}?>
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<?	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?>
								<tr>
									<td colspan="4">
										<table width="100%">
											<tr>
												<td width="70%" class="label">
													<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
													<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
												</td>
												<td width="50%" align="right" class="label">
													<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
															$sFname	=	'excel_reports/list_vehicles.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
														}?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?	}?>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
											if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
												$fp	=	"";
												$fp = fopen($sFname, 'w');
												if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Vehicle_No,Make,Type,TM_Notes,Capacity,Oil_Filter,Next_Oil_Due,Available'));}
												if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','User,Vehicle_No,Vin_No,Oil_Filter,Next_Oil_Due,Safety_Date,Registration_Date,Lic_Plate_No,Make_ID,Model,Year_Manuf,Mileage_Un,Date_To_Un,Cost_To_Un,Cost_Rate,Passenger_Cap,Condition_Tech,TM_Notes,Active,Available,Date_Revised'));}
											}
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<?	if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){?>
												<td width="100" class="colhead">Vehicle No</td>
												<td width="100" class="colhead">Type</td>
												<td width="100" class="colhead">Capacity</td>
												<td width="150" class="colhead">License Plate No</td>
												
												<?	}else{?>
												<td width="40" class="colhead">V No</td>
												<td width="40" class="colhead">Fuel</td>
												<td width="40" class="colhead">Make</td>
												<td width="60" class="colhead">Type</td>
												<td width="130" class="colhead">Veh. Spec.</td>
												<td width="60" class="colhead">Capacity</td>
												<td width="60" class="colhead">Oil Filter</td>
												<td width="55" class="colhead">Next Oil</td>
												<td width="65" class="colhead">Available</td>
												<td width="70" class="colhead">Action</td>
												<?	}?>
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
														if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
															if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
																if($_POST["optExcelReport"]	==	"flds")	{fputcsv($fp, explode(',', $rowVEHICLE["vehicle_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".stripslashes(str_replace(","," ",$rowVEHICLE["restriction"])).",".$rowVEHICLE["passenger_cap"].",".$rowVEHICLE["oil_filter"].",".$rowVEHICLE["next_oil_due"].",".$rowVEHICLE["status"]));}
																if($_POST["optExcelReport"]	==	"cols")	{fputcsv($fp, explode(',', $rowVEHICLE["user_name"].",".$rowVEHICLE["vehicle_no"].",".$rowVEHICLE["vin_no"].",".$rowVEHICLE["oil_filter"].",".$rowVEHICLE["next_oil_due"].",".$rowVEHICLE["safety_date"].",".$rowVEHICLE["registration_date"].",".$rowVEHICLE["lic_plate_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["year_manuf"].",".$rowVEHICLE["mileage_un"].",".$rowVEHICLE["date_to_un"].",".$rowVEHICLE["cost_to_un"].",".$rowVEHICLE["cost_rate"].",".$rowVEHICLE["passenger_cap"].",".stripslashes(str_replace(","," ",$rowVEHICLE["condition_tech"])).",".stripslashes(str_replace(","," ",$rowVEHICLE["restriction"])).",".$rowVEHICLE["active"].",".$rowVEHICLE["status"].",".$rowVEHICLE["date_revised"]));}
															}
														}															
														
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<?	if($_SESSION["User_Group"]==$iGROUP_DRIVER || $_SESSION["User_Group"]==$iGROUP_COORDINATOR_STAFF){?>
																<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['v_type'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['passenger_cap'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['lic_plate_no'];?></td>
															<?	}else{?>
																<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
																<? if($rowVEHICLE['vehicle_id']!=0) {
																									$aFuel = fn_VEHICLE_LAST_END_GAS($rowVEHICLE['vehicle_id']);
																									$bg = "#ffffff";$fg = "#000000";
																									if ( $aFuel == "100%" ) {
																										$bg = "#428724";$fg = "#ffffff";
																									} elseif($aFuel == "75%") { 
																										$bg = "#6ccc43";$fg = "#0000000";
																									} elseif($aFuel == "50%") { 
																										$bg = "#e81760";$fg = "#ffffff";
																									} elseif($aFuel == "25%") { 
																										$bg = "#e81717";$fg = "#ffffff";}
																								}else{ 
																									echo "???";
																								}
																								echo '<td class="coldata" bgcolor="'.$bg.'"><font color="'.$fg.'">'.$aFuel.'</font></td>';
																																		?>
																<td class="coldata"><? echo $rowVEHICLE['brand_name'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['v_type'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['restriction'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['passenger_cap'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['oil_filter'];?></td>
																<td class="coldata"><? if($rowVEHICLE["next_oil_due"]=='N/A') echo "N/A"; else echo fn_cDateMySql($rowVEHICLE["next_oil_due"],1);?></td>
																<td class="coldata"><? echo $rowVEHICLE['status'];?></td>
																<td class="coldata"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_MODIFY)){?><a href="edit_vehicle.php?vid=<? echo $rowVEHICLE['vehicle_id'];?>">view</a><?	}?>&nbsp;<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('Warning! all trip and financial records associated with this vehicle will be lost...Proceed ?')) {fn_DELETE_VEHICLE(<? echo $rowVEHICLE['vehicle_id'];?>);} return false;">delete</a><?	}?></td>
															<?	}?>
														</tr>
											<?			}$listed++;
													}
													if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
													}
											?>
										</table>
									</td>
								</tr>
								<?	}if($iRECORD_COUNT>0)	mysql_free_result($rsVEHICLES);	?>
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
 