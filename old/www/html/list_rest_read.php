<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sDeptNo		=	"";
	$iVehicleNo		=	0;
	$iMonth			=	0;
	$iYear			=	0;
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){		
		if(fn_DELETE_RECORD("tbl_restricted_charges", "charge_id", $_POST["id"]))
			$sMessage	=	fn_Print_MSG_BOX("restricted vehicle charge has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("ERROR!!! restricted vehicle charge is not been deleted", "C_ERROR");
	}
	
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{$sDeptNo	=	mysql_real_escape_string($_POST["drpdept"]);	$sCriteriaSQL	.=	" AND tbl_restricted_charges.dept_id = '".$sDeptNo."'";}
		if(isset($_POST["drpvehicle"]) && $_POST["drpvehicle"]!="")		{$iVehicleNo=	mysql_real_escape_string($_POST["drpvehicle"]);	$sCriteriaSQL	.=	" AND tbl_restricted_charges.vehicle_id = ".$iVehicleNo;}
		if(isset($_POST["drpmonth"]) && $_POST["drpmonth"]!="")			{$iMonth	=	mysql_real_escape_string($_POST["drpmonth"]);	$sCriteriaSQL	.=	" AND tbl_restricted_charges.charge_month = '".$iMonth."'";}
		if(isset($_POST["drpyear"]) && $_POST["drpyear"]!="")			{$iYear		=	mysql_real_escape_string($_POST["drpyear"]);	$sCriteriaSQL	.=	" AND tbl_restricted_charges.charge_year = '".$iYear."'";}
		
		$sSQL	=	"SELECT tbl_restricted_charges.charge_id, tbl_restricted_charges.vehicle_id, tbl_restricted_charges.dept_id, ".
		"MONTHNAME(CONCAT('1970-',tbl_restricted_charges.charge_month,'-10')) AS month_name, tbl_restricted_charges.charge_year AS year, dept_name, vehicle_no, total_charge AS amount, ".
		"begin_mileage, end_mileage, rate ".
		"FROM tbl_restricted_charges ".
		"INNER JOIN tbl_vehicles ON tbl_restricted_charges.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_departments ON tbl_restricted_charges.dept_id = tbl_departments.dept_id ".
		"WHERE calc_method = 'Readings' ".$sCriteriaSQL." ORDER BY (tbl_vehicles.vehicle_no+0)";
		//print($sSQL);
		$rsVEHICLE		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsVEHICLE);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no restricted charges found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Restricted Vehicle Mileage Charges</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}

function fn_DELETE_CHARGES(iCHARGEID){
	document.frm1.id.value=iCHARGEID;
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
								   				<h1 style="margin-bottom: 0px;">LIST RESTRICTED VEHICLE MILEAGE CHARGES</h1>
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
						<form name="frm1" action="list_rest_read.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="id" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="180">Department:<br /><?	fn_DEPARTMENT('drpdept', $sDeptNo, "180", "1", "ALL");	?></td>
												<td class="label" width="120">Vehicle:<br /><?	fn_RESTRICTED_VEHICLES("drpvehicle", $iVehicleNo, "120", "1", "ALL");?></td>
												<td class="label" width="120">Month:<br /><?	fn_MONTHS('drpmonth', $iMonth, "120", "1", "ALL");?></td>
												<td class="label" width="120">Year:<br /><?	fn_YEARS('drpyear', $iYear, "120", "1", "ALL");?></td>
												
												
												<td><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
											<tr>
												<td colspan="4">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_rest_reading.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
																	}?>
															</td>
														</tr>
													</table>
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
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Vehicle_No,Dept_Name,Month,Year,Begin_Mileage,End_Mileage,Rate,Amount'));}
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Vehc.No</td>
												<td width="150" class="colhead">Dept. Name</td>
												<td width="100" class="colhead">Month</td>
												<td width="50" class="colhead">Year</td>
												<td width="100" class="colhead">Begin Miles</td>
												<td width="100" class="colhead">End Miles</td>
												<td width="30" class="colhead">Rate</td>
												<td width="100" class="colhead" align="right">Amount</td>
												<td width="100" class="colhead" align="center">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowVEHICLE["vehicle_no"].",".$rowVEHICLE["dept_name"].",".$rowVEHICLE["month_name"].",".$rowVEHICLE["year"].",".$rowVEHICLE["begin_mileage"].",".$rowVEHICLE["end_mileage"].",".$rowVEHICLE["rate"].",".str_replace(",","",$rowVEHICLE["amount"])));}
														}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowVEHICLE['vehicle_no'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['dept_name'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['month_name'];?></td>
															<td class="coldata"><? echo $rowVEHICLE['year'];?></td>
															<td class="coldata" align="right"><? echo $rowVEHICLE['begin_mileage'];?></td>
															<td class="coldata" align="right"><? echo $rowVEHICLE['end_mileage'];?></td>
															<td class="coldata" align="right"><? echo fn_NUMBER_FORMAT($rowVEHICLE['rate'], "1234.56");?></td>
															<td class="coldata" align="right"><? echo fn_NUMBER_FORMAT($rowVEHICLE['amount'], "1234.56");?></td>
															<td class="coldata" align="center">
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY)){?><a href="edit_restricted_charges.php?id=<? echo $rowVEHICLE['charge_id'];?>">edit</a><? 	}?>&nbsp;/&nbsp;
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('are you sure to delete this reading charge?')) {fn_DELETE_CHARGES(<? echo $rowVEHICLE['charge_id'];?>);} return false;">delete</a><?	}?>
															</td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsVEHICLE);	?>
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
 