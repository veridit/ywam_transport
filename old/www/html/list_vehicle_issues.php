<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
		
	$iVEHICLE_NO	=	0;
	//$iCapacity		=	"";
	//$iModelID		=	0;
	
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sAVAILABLE		=	"";
	$sNotesDate		=	"";
	$sAction		=	"";
	$bPROBLEM		=	0;
	
	if(isset($_POST["action"]) && $_POST["action"]!=""){$sAction	=	$_POST["action"];}
	if($sAction=="delnotes"){		
		/*$sSQL		=		"UPDATE tbl_trip_details SET desc_problem = '' WHERE res_id IN (SELECT res_id FROM tbl_reservations WHERE 1=1 ".$sCriteriaSQL.")";
		$rsDEL_NOTES		=		mysql_query($sSQL) or die(mysql_error());
		if(mysql_affected_rows($link)>0)
			$sMessage	=	fn_Print_MSG_BOX(mysql_affected_rows($link)." drivers notes are deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("there is no note(s) to be deleted", "C_ERROR");*/
		//==============================================
		$iDEL_NOTES	=	explode(";",$_REQUEST["resid"]);
		$iDEL_COUNTER	=	0;
		for($iRES_COUNTER=0; $iRES_COUNTER<=count($iDEL_NOTES)-1; $iRES_COUNTER++){
			$sSQL		=	"UPDATE tbl_trip_details SET desc_problem = '' WHERE res_id = ".$iDEL_NOTES[$iRES_COUNTER];
			$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
			$iDEL_COUNTER++;	
		}
		
		if($iDEL_COUNTER>0)	$sMessage	=	fn_Print_MSG_BOX("<li>drivers notes are deleted", "C_SUCCESS");
		
		
	}
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{$iVEHICLE_NO	=	mysql_real_escape_string($_POST["drpvehicle"]);		$sCriteriaSQL	.=	" AND tbl_vehicles.vehicle_id = ".$iVEHICLE_NO;}
		if(isset($_POST["chkProblem"]) &&	$_POST["chkProblem"]!="")		{$bPROBLEM	=	1;								$sCriteriaSQL	.=	" AND tbl_trip_details.problem = 1 ";}
		if(isset($_POST["drprestricted"]) && $_POST["drprestricted"]!="")	{
			$sAVAILABLE		=	$_POST["drprestricted"];
			if($sAVAILABLE=="Available")	$sCriteriaSQL	.=	" AND tbl_vehicles.restricted= 1";
			if($sAVAILABLE=="Not-Available")	$sCriteriaSQL	.=	" AND tbl_vehicles.restricted= 0";
		}
		
		if(isset($_POST["txtnotesdate"]) && ($_POST["txtnotesdate"]!="")){
					
			$sNotesDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtnotesdate"]));
			$sCriteriaSQL	.=	" AND DATE(tbl_trip_details.reg_date)	> '".$sNotesDate."'";
		}
		
		//$sSQL	=	"SELECT tbl_trip_details.res_id, tbl_trip_details.desc_problem, tbl_vehicles.vehicle_no, tbl_vehicles.restriction, tbl_vehicle_type.v_type ".
		$sSQL	=	"SELECT tbl_trip_details.*, tbl_vehicles.*, tbl_reservations.assigned_driver, tbl_reservations.planned_depart_day_time, ".
		"tbl_vehicle_type.v_type, tbl_vehicle_brand.brand_name, ".
		"CONCAT(f_name,' ',l_name) AS assigned_driver_name ".
		"FROM tbl_trip_details ".
		"INNER JOIN tbl_reservations ON tbl_trip_details.res_id = tbl_reservations.res_id ".
		"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
		"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
		"INNER JOIN tbl_user ON tbl_reservations.assigned_driver = tbl_user.user_id ".
		"WHERE tbl_trip_details.desc_problem <> '' ".$sCriteriaSQL." ORDER BY tbl_vehicles.vehicle_no, tbl_reservations.planned_depart_day_time DESC ";
		
		//print($sSQL);
		$rsVEHICLES		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLES);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no driver notes found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Driver Notes</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
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
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}
function fn_DELETE_NOTES(){

	var sErrMessage='';
	//var iErrCounter=0;
	
	/*if(document.frm1.txtnotesdate.value==""){
		sErrMessage='<li>please select date to view notes before';
		iErrCounter++;
	}*/
	
	/*if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}else{
		document.frm1.action.value='delnotes';
		document.frm1.submit();
	}*/
	//=========================================
	
	
	if (fn_Assign_Values()!=''){
		
		document.frm1.action.value	="delnotes";
		document.frm1.resid.value	=fn_Assign_Values();	
		document.frm1.submit();
	}
	else{
		sErrMessage='<li>please select note(s) to delete';
		fn_draw_ErrMsg(sErrMessage);
	}
	
	
	
	
}
function fn_Assign_Values(){

var bChecked 				= 	false;
var chkResv					=	document.frm1.chkResv;
var chkboxValues			=	'';
var sErrString				=	'';
			

if (typeof chkResv.length != 'undefined')
	for(i=0;i<chkResv.length;i++){
		if (chkResv[i].checked){
		
			bChecked		=	true;
		
			if (bChecked)
				if (chkboxValues	==	'')	chkboxValues	=	chkResv[i].value.substring(chkResv[i].value.search(';')+1,chkResv[i].value.length);
				else chkboxValues	=	chkboxValues+';'+chkResv[i].value.substring(chkResv[i].value.search(';')+1,chkResv[i].value.length);

		}					
	}
else
	if (chkResv.checked){
		
		bChecked		=	true;
		
		if (bChecked)
			if (chkboxValues	==	'')	chkboxValues	=	chkResv.value.substring(chkResv.value.search(';')+1,chkResv.value.length);
			else chkboxValues	=	chkboxValues+';'+chkResv.value.substring(chkResv.value.search(';')+1,chkResv.value.length);

	}
	

if(!bChecked)
	return '';
else
	return chkboxValues;

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
								   				<h1 style="margin-bottom: 0px;">VEHICLE ISSUES & NOTES</h1>
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
						<form name="frm1" action="list_vehicle_issues.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="vehicleid" value=""	/>
							<input type="hidden" name="resid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="100">Vehicle No:<br /><?	fn_VEHICLE('drpvehicle', $iVEHICLE_NO, "100", "1", "--All--");?></td>
																								
												<td class="label" width="150">Notes After <br />
													<input type="text" name="txtnotesdate" id="txtnotesdate" value="<? if($sNotesDate!="") echo fn_cDateMySql($sNotesDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td rowspan="2">
													<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" />
													<input type="button" name="btnDELNOTES" value="DELETE NOTES" class="Button" onClick="fn_DELETE_NOTES();" />
												</td>
											</tr>
											<tr>
												<td class="label" width="120">Status:<br />
												<?
													$arrAvailable[0]	=	"Available";
													$arrAvailable[1]	=	"Not-Available";
												?>
												
													<select name="drprestricted" size="1" style="width:130px;">
														<option value="">--All--</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrAvailable[$iCOUNTER]?>" <? if($arrAvailable[$iCOUNTER]==$sAVAILABLE) echo "selected";?>><?=$arrAvailable[$iCOUNTER]?></option>
														<?	}?>
													</select>
												</td>
												<td class="label"><br /><input type="checkbox" name="chkProblem" value="1" <?Php if($bPROBLEM==1) echo "checked";?> />Safety Problem
												
											</tr>
										</table>
									</td>
								</tr>
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
															$sFname	=	'excel_reports/list_driver_notes.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
														}?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Reserv_No,Vehicle_No,TM_Admin_Notes,Depart_Date,Driver,Driver_Notes'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','Reserv_No,Vehicle_No,Depart_Date,Vin_No,Safety_Date,Registration_Date,Lic_Plate_No,Make_ID,Model,Year_Manuf,Mileage_Un,Date_To_Un,Cost_To_Un,Cost_Rate,Passenger_Cap,Condition_Tech,Active,Restricted,Date_Revised,Driver_Notes,Admin_Issues,Assigned_Driver,Begin_Miles,End_Miles,End_Gas_Percent'));}
											
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="40" class="colhead">Resv#</td>
												<td width="30" class="colhead" align="center">D</td>
												<td width="30" class="colhead">Veh#</td>
												<td width="150" class="colhead">TM &amp; Admin notes on Vehicle</td>
												<td width="60" class="colhead">Depart Date</td>
												<td width="100" class="colhead">Driver</td>
												<!--<td width="150" class="colhead">TM Notes</td>-->
												<td width="150" class="colhead">Driver Notes on Vehicles</td>
												
												
											
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															//if($_POST["optExcelReport"]	==	"flds")	{fputcsv($fp, explode(',', $rowVEHICLE["res_id"].",".$rowVEHICLE["vehicle_no"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["planned_depart_day_time"].",".stripslashes(str_replace(","," ",$rowVEHICLE["restriction"])).",".stripslashes(str_replace(","," ",$rowVEHICLE["desc_problem"]))));}
															//if($_POST["optExcelReport"]	==	"cols")	{fputcsv($fp, explode(',', $rowVEHICLE["res_id"].",".$rowVEHICLE["vehicle_no"].",".$rowVEHICLE["planned_depart_day_time"].",".$rowVEHICLE["vin_no"].",".$rowVEHICLE["safety_date"].",".$rowVEHICLE["registration_date"].",".$rowVEHICLE["lic_plate_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["year_manuf"].",".$rowVEHICLE["mileage_un"].",".$rowVEHICLE["date_to_un"].",".$rowVEHICLE["cost_to_un"].",".$rowVEHICLE["cost_rate"].",".$rowVEHICLE["passenger_cap"].",".$rowVEHICLE["condition_tech"].",".$rowVEHICLE["active"].",".$rowVEHICLE["restricted"].",".$rowVEHICLE["date_revised"].",".stripslashes(str_replace(","," ",$rowVEHICLE["restriction"])).",".stripslashes(str_replace(","," ",$rowVEHICLE["desc_problem"])).",".$rowVEHICLE["assigned_driver_name"].",".$rowVEHICLE["begin_mileage"].",".$rowVEHICLE["end_mileage"].",".$rowVEHICLE["end_gas_percent"]));}
															if($_POST["optExcelReport"]	==	"flds")	{fputcsv($fp, explode(',', $rowVEHICLE["res_id"].",".$rowVEHICLE["vehicle_no"].",".stripslashes(str_replace(","," ",$rowVEHICLE["admin_issues"])).",".$rowVEHICLE["planned_depart_day_time"].",".$rowVEHICLE["assigned_driver_name"].",".stripslashes(str_replace(","," ",$rowVEHICLE["desc_problem"]))));}
															if($_POST["optExcelReport"]	==	"cols")	{fputcsv($fp, explode(',', $rowVEHICLE["res_id"].",".$rowVEHICLE["vehicle_no"].",".$rowVEHICLE["planned_depart_day_time"].",".$rowVEHICLE["vin_no"].",".$rowVEHICLE["safety_date"].",".$rowVEHICLE["registration_date"].",".$rowVEHICLE["lic_plate_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["year_manuf"].",".$rowVEHICLE["mileage_un"].",".$rowVEHICLE["date_to_un"].",".$rowVEHICLE["cost_to_un"].",".$rowVEHICLE["cost_rate"].",".$rowVEHICLE["passenger_cap"].",".$rowVEHICLE["condition_tech"].",".$rowVEHICLE["active"].",".$rowVEHICLE["restricted"].",".$rowVEHICLE["date_revised"].",".stripslashes(str_replace(","," ",$rowVEHICLE["desc_problem"])).",".stripslashes(str_replace(","," ",$rowVEHICLE["admin_issues"])).",".$rowVEHICLE["assigned_driver_name"].",".$rowVEHICLE["begin_mileage"].",".$rowVEHICLE["end_mileage"].",".$rowVEHICLE["end_gas_percent"]));}
														}
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowVEHICLE['res_id'];?></td>
															<td class="coldata" align="center"><input type="checkbox" value="<? echo $rowVEHICLE['res_id'];?>" name="chkResv" /></td>
															<td class="coldata"><? echo $rowVEHICLE['vehicle_no'];?></td>
															<td class="coldata"><? echo stripslashes($rowVEHICLE['admin_issues']);?></td>
															<td class="coldata" align="center"><? echo fn_cDateMySql($rowVEHICLE['planned_depart_day_time'],1);?></td>
															<td class="coldata"><? echo $rowVEHICLE['assigned_driver_name'];?></td>
															<td class="coldata"><? echo stripslashes($rowVEHICLE['desc_problem']);?></td>
														</tr>
											<?			}$listed++;
													}
											?>
										</table>
									</td>
								</tr>
								<?
									if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
									}	if($iRECORD_COUNT>0)	mysql_free_result($rsVEHICLES);
								?>
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
 