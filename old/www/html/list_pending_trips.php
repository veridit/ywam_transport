<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>ALSO NOTE that trips can be changed or deleted using the popup from the graph", "C_SUCCESS");
	$sCriteriaSQL	=	"";
	$sDeptNo		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$bRepeating		=	"";
	$iRES_SEARCH_ID	=	"";
	$iDRIVER		=	"";
	$bSLIP_ISSUED	=	"";
	$iDAYS			=	"";

	
	/*if(isset($_POST["action"])	&& $_POST["action"]=="delete-bulk"){
	
		$iDEL_RESV_IDs	=	explode(";",$_REQUEST["resid"]);
		$iDEL_COUNTER	=	0;
		for($iRES_COUNTER=0; $iRES_COUNTER<=count($iDEL_RESV_IDs)-1; $iRES_COUNTER++){
			$sSQL		=	"UPDATE tbl_reservations SET reservation_cancelled = 1, res_delete_user = ".$_SESSION["User_ID"].", res_delete_datetime = '".date('Y-m-d H:i:s')."' WHERE res_id = ".$iDEL_RESV_IDs[$iRES_COUNTER];
			$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
			$iDEL_COUNTER++;	
		}
		
		if($iDEL_COUNTER>0)	$sMessage	=	fn_Print_MSG_BOX("pending trip(s) has been deleted","C_SUCCESS");
	}*/
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){
		
		$sSQL	=	"UPDATE tbl_reservations SET reservation_cancelled = 1, res_delete_user = ".$_SESSION["User_ID"].", res_delete_datetime = '".date('Y-m-d H:i:s')."' WHERE res_id = ".$_POST["resid"];
		$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage	=	fn_Print_MSG_BOX("<li>open trip has been deleted","C_SUCCESS");
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["txtresvno"]) && $_POST["txtresvno"]!="")		{$iRES_SEARCH_ID=	mysql_real_escape_string($_POST["txtresvno"]);	   $sCriteriaSQL		.=	" AND tbl_reservations.res_id 			= 	".$iRES_SEARCH_ID;}
		if(isset($_POST["drpdrivername"]) && $_POST["drpdrivername"]!=""){$iDRIVER		=	mysql_real_escape_string($_POST["drpdrivername"]); $sCriteriaSQL		.=	" AND tbl_reservations.assigned_driver 	=	".$iDRIVER;}
		if(isset($_POST["drprepeating"]) && $_POST["drprepeating"]!="")	{$bRepeating	=	mysql_real_escape_string($_POST["drprepeating"]); $sCriteriaSQL		.=	" AND tbl_reservations.repeating = ".$bRepeating;	}
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")			{$sDeptNo		=	mysql_real_escape_string($_POST["drpdept"]);		$sCriteriaSQL		.=	" AND assigned.dept_id = '".$sDeptNo."'";			}
		//if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")			{$iDAYS			=	$_POST["drpdays"];		$sCriteriaSQL		.=	" AND TIMESTAMPDIFF(DAY, CURDATE(), tbl_reservations.planned_depart_day_time) BETWEEN 0 AND ".$iDAYS;			}
		
				
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
			
			$sStartDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtstartdate"]));		
			$sEndDate		=	fn_DATE_TO_MYSQL(mysql_real_escape_string($_POST["txtenddate"]));
			$sCriteriaSQL	.=	" AND (DATE(tbl_reservations.planned_depart_day_time) BETWEEN '".$sStartDate."' AND '".$sEndDate."')";
		}
		
		
		if(isset($_POST["drpslip"]) && $_POST["drpslip"]!="")			{
			$bSLIP_ISSUED	=	mysql_real_escape_string($_POST["drpslip"]);
			if($bSLIP_ISSUED=="Yes")		$sCriteriaSQL		.=	" AND (tbl_reservations.key_no <> '' AND tbl_reservations.key_no IS NOT NULL)";
			elseif($bSLIP_ISSUED=="No")		$sCriteriaSQL		.=	" AND (tbl_reservations.key_no = '' OR tbl_reservations.key_no IS NULL)";
		}
	
		$sSQL	=	"SELECT tbl_reservations.res_id, vehicle_no, assigned.f_name, assigned.l_name, planned_passngr_no, planned_depart_day_time, planned_return_day_time, key_no, card_no, destination, ".
		"CASE WHEN repeating = 0 THEN '' ELSE 'R' END AS repeating ".
		"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_user assigned ON tbl_reservations.assigned_driver = assigned.user_id ".
		"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
		"WHERE tbl_reservations.coord_approval = 'Approved' AND tbl_reservations.reservation_cancelled = 0 AND cancelled_by_driver = 0 ".$sCriteriaSQL." ".
		"AND tbl_trip_details.res_id IS NULL ".
		"AND tbl_abandon_trips.res_id IS NULL ".
		"ORDER BY tbl_reservations.res_id DESC";
		//print($sSQL);
		$rsPENDING_TRIP			=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsPENDING_TRIP);
		
		if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>no pending trip found", "C_ERROR");}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Change or Delete Open Trips</title>
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
<script type="text/javascript">
function fn_DELETE_TRIP(iResID){
	document.frm1.resid.value=iResID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}
/*function fn_DELETE_BULK_TRIP(){
	var sErrMessage="";
	if (fn_Assign_Values()!=''){
		//document.frm1.action		="list_reservations.php";
		document.frm1.action.value	="delete-bulk";
		document.frm1.resid.value	=fn_Assign_Values();	
		document.frm1.submit();
	}
	else{
		sErrMessage='<li>please select trip(s) to delete';
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

}*/
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
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
								   				<h1 style="margin-bottom: 0px;">CHANGE OR DELETE OPEN TRIPS</h1>
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
						<form name="frm1" action="list_pending_trips.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="resid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0">
											<tr>
												<td class="label" width="200">Resv. No:<br />
													<input type="text" name="txtresvno" value="<? echo $iRES_SEARCH_ID?>" style="width:90px;" />
												</td>
												<td class="label" width="200">Assigned Driver:<br />
													<?	fn_DISPLAY_USERS('drpdrivername', $iDRIVER, "160", "1", "--All Drivers--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_TM.",".$iGROUP_TC);?>
												</td>
												<td class="label" width="200">Department.:<br /><?	fn_DEPARTMENT('drpdept', $sDeptNo, "180", "1", "ALL");	?></td>
												<!--<td class="label" width="100">Repeating:<br />
													<?	//fn_REPEATING_DROP("drprepeating", $bRepeating, "100", "1", "ALL");?>
												</td>-->
												
												<td rowspan="2" align="center" width="200">
												<br />
												<input type="button" name="btnGO" value=" GO " class="Button" style="width:65px;" onClick="fn_RPT_DT_SEARCH();" /><!--&nbsp;<br /><br />-->
												<!--<input type="button" name="delete" value="Delete" class="Button" style="width:65px;" onclick="fn_DELETE_BULK_TRIP();">-->
												</td>
											</tr>
											<tr>
												<td class="label" width="200">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="200">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<!--<td class="label">Days:<br />
													<?Php
														/*$arrDAYS[0][0]	=	"-2";		$arrDAYS[0][1]	=	"Overdue";
														$arrDAYS[1][0]	=	"10";		$arrDAYS[1][1]	=	"Today +10 Days";
														$arrDAYS[2][0]	=	"30";		$arrDAYS[2][1]	=	"Today +30 Days";
														$arrDAYS[3][0]	=	"60";		$arrDAYS[3][1]	=	"Today +60 Days";*/
													?>
													<select name="drpdays" style="width:130px;">
														<option value="">--ALL--</option>
														<?Php //for($iCounter=0;$iCounter<=3;$iCounter++){?>
														<option value="<?Php //echo $arrDAYS[$iCounter][0]?>" <?Php //if($arrDAYS[$iCounter][0]==$iDAYS) echo "selected";?>><?Php //echo $arrDAYS[$iCounter][1]?></option>
														<?Php //}?>
													</select>
												</td>-->
												<td class="label" width="200">Trip Slip Issued:<br />
												<?Php $arrSLIP[0]	=	"Yes";		$arrSLIP[1]	=	"No";?>
												<select name="drpslip" style="width:180px;">
													<option value="" selected>--All--</option>
													<?Php 	for($iCounter=0;$iCounter<=1;$iCounter++){?>
													<option value="<?Php echo $arrSLIP[$iCounter];?>" <?Php if($bSLIP_ISSUED==$arrSLIP[$iCounter]) echo "selected";?>><?Php echo $arrSLIP[$iCounter];?></option>
													<?Php	}?>
												</select>
												</td>
												
											</tr>
										</table>
									</td>
									
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_pending_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Res. No,F_Name,L_Name,Destination,Sched_Depart_Date,Key_No,Card_No,Return_Date'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Resv #</td>
												<!--<td width="30" class="colhead" align="center">D</td>-->
												<td width="100" class="colhead">Name</td>
												<td width="160" class="colhead">Destination.</td>
												<td width="110" class="colhead">Depart Date</td>
												<td width="50" class="colhead">Key #</td>
												<td width="50" class="colhead">Card #</td>
												<td width="100" class="colhead">Rtrn Date</td>
												<td width="50" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;
													while($rowRES	=	mysql_fetch_array($rsPENDING_TRIP)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowRES["res_id"].",".$rowRES["f_name"].",".$rowRES["l_name"].",".stripslashes($rowRES["destination"]).",".$rowRES['planned_depart_day_time'].",".$rowRES['key_no'].",".$rowRES['card_no'].",".$rowRES['planned_return_day_time'].",".$rowRES['repeating']));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowRES['res_id'];?></td>
															<!--<td class="coldata" align="center"><input type="checkbox" value="<? //echo $rowRES['res_id'];?>" name="chkResv" /></td>-->
															<td class="coldata"><? echo $rowRES['f_name']." ".$rowRES['l_name'];?></td>
															<td class="coldata"><? echo stripslashes($rowRES['destination']);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_depart_day_time'], 2);?></td>
															<td class="coldata"><? echo $rowRES['key_no'];?></td>
															<td class="coldata"><? echo $rowRES['card_no'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowRES['planned_return_day_time'], 2);?></td>
															<td class="coldata" align="center">
																<!--<a href="add_tripdetails.php?resid=<? //echo $rowRES['res_id'];?>" title="close">CL</a>&nbsp;/&nbsp;-->
																<a href="javascript:void(0);" onClick="fn_DELETE_TRIP(<? echo $rowRES['res_id'];?>);" title="del trip">Del</a>
																&nbsp;/&nbsp;
																<a href="edit_reservation.php?resid=<? echo $rowRES['res_id'];?>" title="change">Ch</a>
															</td>
															
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsPENDING_TRIP);	?>
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
 