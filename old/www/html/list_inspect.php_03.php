<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$iVEHICLE_NO	=	0;
	$iACTION_DUE	=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
			if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
						
				$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
				$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);
				$sCriteriaSQL	.=	" AND ((v.safety_date BETWEEN '".$sStartDate."' AND '".$sEndDate."') OR (v.registration_date BETWEEN '".$sStartDate."' AND '".$sEndDate."')) ";
			}else{
				$sCriteriaSQL	.=	" AND (DATEDIFF(v.safety_date, CURDATE()) BETWEEN 1 AND 40 OR DATEDIFF(v.registration_date, CURDATE()) BETWEEN 1 AND 40)";
			}
			
			if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")			{$iVEHICLE_NO	=	$_POST["drpvehicle"];		$sCriteriaSQL	.=	" AND v.vehicle_id = ".$iVEHICLE_NO;}
			if(isset($_POST["drpaction"]) && $_POST["drpaction"]!="")			{$iACTION_DUE	=	$_POST["drpaction"];		$sCriteriaSQL	.=	" AND ((TIMESTAMPDIFF(WEEK, CURDATE(), v.safety_date) BETWEEN 1 AND ".$iACTION_DUE.")		OR		(TIMESTAMPDIFF(WEEK, CURDATE(), v.registration_date) BETWEEN 1 AND ".$iACTION_DUE."))";}
			
			$sSQL	=	"SELECT v.vehicle_no, v.safety_date, v.registration_date, v_type, brand_name FROM tbl_vehicles v ".
			"INNER JOIN tbl_vehicle_type ON v.model = tbl_vehicle_type.v_type_id ".
			"INNER JOIN tbl_vehicle_brand ON v.make_id = tbl_vehicle_brand.brand_id ".
			"WHERE 1=1 ".$sCriteriaSQL." ORDER BY (v.vehicle_no+0)";
			
			
		//print($sSQL);
			$rsVEHICLES		=	mysql_query($sSQL) or die(mysql_error());
			$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLES);
			if($iRECORD_COUNT<=0){
				$sMessage		=	fn_Print_MSG_BOX("no inspection - registration due found", "C_ERROR");
			}
	}
	
	if($sStartDate =="" && $sEndDate==""){
	
		$sSQL	=	"SELECT CASE WHEN MIN(safety_date) < MIN(registration_date) THEN MIN(safety_date) ELSE MIN(registration_date) END AS start_date, ".
		"CASE WHEN MAX(safety_date) > MAX(registration_date) THEN MAX(safety_date) ELSE MAX(registration_date) END AS end_date FROM tbl_vehicles";
		$rsDATES	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsDATES)>0){
			list($sStartDate, $sEndDate)	=		mysql_fetch_row($rsDATES);
		}mysql_free_result($rsDATES);
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Inspect / Registration Due</title>
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

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>

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
								   				<h1 style="margin-bottom: 0px;">INSPECT - REGISTRATION DUE</h1>
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
						<form name="frm1" action="list_inspect.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="vehicleid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="150">From:<br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To:<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="100">Vehicle No:<br /><?	fn_VEHICLE('drpvehicle', $iVEHICLE_NO, "100", "1", "--All--");?></td>
												<td class="label" width="150">Action Due:<br />
													<?Php
														$arrACTION[0][0]	=	"2";	$arrACTION[0][1]	=	"In Next 2 Weeks";
														$arrACTION[1][0]	=	"4";	$arrACTION[1][1]	=	"In Next 4 Weeks";
													?>
													<select name="drpaction" style="width:150px;">
														<option value="">--Any--</option>
														<?Php 	for($iCounter=0;$iCounter<=1;$iCounter++){?>
														<option value="<?Php echo $arrACTION[$iCounter][0];?>" <?Php if($iACTION_DUE==$arrACTION[$iCounter][0]) echo "selected";?>><?Php echo $arrACTION[$iCounter][1];?></option>
														<?Php	}?>
													</select>
												</td>
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
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
													
												</td>
												<td width="50%" align="right" class="label">
													<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
															$sFname	=	'excel_reports/list_inspect_due.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
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
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Vehicle_No,Make,Type,Safety_Date,Reg_Date'));}
											
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												
												<td width="100" class="colhead">Vehicle No</td>
												<td width="100" class="colhead">Make</td>
												<td width="100" class="colhead">Type</td>
												<td width="100" class="colhead">Safety Date</td>
												<td width="150" class="colhead">Reg. Date</td>
												
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLES)){
															if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
																if($_POST["optExcelReport"]	==	"flds")	{fputcsv($fp, explode(',', $rowVEHICLE["vehicle_no"].",".$rowVEHICLE["brand_name"].",".$rowVEHICLE["v_type"].",".$rowVEHICLE["safety_date"].",".$rowVEHICLE["registration_date"]));}
															}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
																<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['brand_name'];?></td>
																<td class="coldata"><? echo $rowVEHICLE['v_type'];?></td>	
																<td class="coldata"><? echo fn_cDateMySql($rowVEHICLE['safety_date'], 1);?></td>
																<td class="coldata"><? echo fn_cDateMySql($rowVEHICLE['registration_date'], 1);?></td>
														</tr>
											<?			}$listed++;
													}
													
											?>
										</table>
									</td>
								</tr>
								<?
									if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
									}if($iRECORD_COUNT>0)	mysql_free_result($rsVEHICLES);
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
 