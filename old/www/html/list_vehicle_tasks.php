<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iVehicle_ID	=	"";
	$sStatus		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sSORT_ORDER	=	"work_start_date DESC";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
			if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")	{$iVehicle_ID	=	mysql_real_escape_string($_POST["drpvehicle"]);	$sCriteriaSQL	.=	" AND tbl_shop_tasks.vehicle_id = ".$iVehicle_ID;}
			if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")	{$sStatus		=	mysql_real_escape_string($_POST["drpstatus"]);	$sCriteriaSQL	.=	" AND task_complete = ".$sStatus;}
				
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
				
				$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));				
				$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));
				$sCriteriaSQL	.=	" AND tbl_shop_tasks.work_start_date >= '".$sStartDate."' AND tbl_shop_tasks.work_start_date <= '".$sEndDate."'";
			}
			
			if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
				if(fn_DELETE_RECORD("tbl_shop_tasks", "task_id", $_POST["taskid"]))
					$sMessage	=	fn_Print_MSG_BOX("vehicle shop task has been deleted", "C_SUCCESS");
				else
					$sMessage	=	fn_Print_MSG_BOX("error! vehicle shop task is not been deleted", "C_ERROR");
			}
			
			if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")			{$sSORT_ORDER		=	mysql_real_escape_string($_POST["drpsort"]);	}
			
			$sSQL	=	"SELECT tbl_shop_tasks.*, tbl_vehicle_brand.brand_name AS make, tbl_vehicle_type.v_type AS type, vehicle_no, ".
			"oil_filter, work_type, condition_tech, CONCAT(f_name, ' ', l_name) AS tech_name FROM tbl_shop_tasks ".
			"INNER JOIN tbl_vehicles ON tbl_shop_tasks.vehicle_id = tbl_vehicles.vehicle_id ".
			"INNER JOIN tbl_work_type ON tbl_shop_tasks.work_type_id = tbl_work_type.work_type_id ".
			"INNER JOIN tbl_user ON tbl_shop_tasks.user_id = tbl_user.user_id ".
			"INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
			"INNER JOIN tbl_vehicle_type ON tbl_vehicles.model = tbl_vehicle_type.v_type_id ".
			"WHERE 1 = 1 ".$sCriteriaSQL." ORDER BY ".$sSORT_ORDER;
			//"WHERE task_complete = 1 ".$sCriteriaSQL;
			//print($sSQL);
			$rsVEHICLES		=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLES);
			if($iRECORD_COUNT<=0){
				$sMessage		=	fn_Print_MSG_BOX("no record found", "C_ERROR");
			}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Vehicles R & M Tasks</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
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


<script type="text/javascript">
function fn_DELETE_TASK(iTASKID){
	document.frm1.taskid.value=iTASKID;
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
								   				<h1 style="margin-bottom: 0px;">VEHICLES R & M TASKS</h1>
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
						<form name="frm1" action="list_vehicle_tasks.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="taskid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="150">
													Vehicle No:<br />
													<?	fn_VEHICLE("drpvehicle", $iVehicle_ID, "150", "1", "ALL");?>
												</td>
												<td class="label" width="110">
													Status:<br />
													<?
														$arrSTATUS[0][0]	=	"0";		$arrSTATUS[0][1]	=	"Pending";
														$arrSTATUS[1][0]	=	"1";		$arrSTATUS[1][1]	=	"Done";
														
													?>
													<select name="drpstatus" size="1" style="width:100px;">
														<option value="" selected>--All--</option>
													<? 	for($iCounter=0;$iCounter<=1;$iCounter++){?>
														<option value="<? echo $arrSTATUS[$iCounter][0]?>" <? if($sStatus == $arrSTATUS[$iCounter][0]) echo "selected";?>><? echo $arrSTATUS[$iCounter][1]?></option>
													<?	}?>
													</select>
												</td>
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:160px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="(vehicle_no+0) ASC" <? if($sSORT_ORDER == "(vehicle_no+0) ASC") echo "selected";?>>Veh # A-Z</option>
														<option value="total_cost DESC" <? if($sSORT_ORDER == "total_cost DESC") echo "selected";?>>Cost Z-A</option>	
														
													</select>
												</td>
												<td class="label">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Report with all columns in table</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_shop_tasks.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Task_id,Tech_name,Vehicle_No,Make,Type,Oil_Filter,Work_Type,Date_Work_Done,Total_Cost,Work_Type_Requested,General_Condition,Next_Oil_Due,Parts_Supplier,Test_Drive_Done,Task_Completed,Outside_Mechanic,Time_Stamp'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="40" class="colhead">Ord.No</td>
												<td width="40" class="colhead">V. No</td>
												<td width="60" class="colhead">Work Type</td>
												<td width="60" class="colhead" align="center">Work Date</td>
												<td width="30" class="colhead" align="right">Cost</td>
												<td width="80" class="colhead">Work Req.</td>
												<td width="65" class="colhead">Oil Filter</td>
												<td width="45" class="colhead" align="center">Next Oil</td>
												<td width="25" class="colhead">Status</td>
												<td width="50" class="colhead" align="center">Action</td>
												
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowVEHICLE["task_id"].",".$rowVEHICLE["tech_name"].",".$rowVEHICLE["vehicle_no"].",".$rowVEHICLE["make"].",".$rowVEHICLE["type"].",".$rowVEHICLE["oil_filter"].",".$rowVEHICLE["work_type"].",".fn_cDateMySql($rowVEHICLE['work_start_date'], 1).",".$rowVEHICLE["total_cost"].",".stripslashes(str_replace(","," ",$rowVEHICLE["tech_comments"])).",".$rowVEHICLE["condition_tech"].",".$rowVEHICLE["next_oil"].",".$rowVEHICLE["parts_source"].",".$rowVEHICLE["drive_test_done"].",".$rowVEHICLE["task_complete"].",".$rowVEHICLE["vendor_name"].",".$rowVEHICLE["reg_date"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowVEHICLE['task_id'];?></td>
															<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['work_type'];?></td>
															<td class="coldata" align="center"><? echo fn_cDateMySql($rowVEHICLE['work_start_date'], 1);?></td>
															<td class="coldata" align="right"><? echo fn_NUMBER_FORMAT($rowVEHICLE['total_cost'], "1234.56");?></td>
															<td class="coldata"><? echo $rowVEHICLE['tech_comments'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['oil_filter'];?></td>
															<td class="coldata" align="center"><? echo fn_cDateMySql($rowVEHICLE['next_oil'],1);?></td>
															<td class="coldata"><? if($rowVEHICLE['task_complete']==0) echo "Pending"; else echo "Done";?></td>
															
															<td class="coldata" align="center">
																<?	if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SHOP_MODIFY)){?><a href="edit_shoptask.php?stid=<? echo $rowVEHICLE['task_id'];?>">edit</a><?	}?>
																<?	if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_SHOP_DELETE)){?>&nbsp;<a href="javascript:void(0);" onClick="if(confirm('are you sure to delete?')) {fn_DELETE_TASK(<? echo $rowVEHICLE['task_id'];?>)} return false;">/delete</a><?	}?>
															</td>
															
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
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
 