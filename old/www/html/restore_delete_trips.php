<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');

	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li>trips charged to deactivated deparments can be restored here, as a group","C_SUCCESS");
	$iRES_ID		=	"";
	$iDeptID		=	0;
	$iRESVD_USER	=	"";
	$sCriteriaSQL	=	"";
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="restore-bulk"){
	
		$iRESTORE_RESV_IDs	=	explode(";",$_REQUEST["resid"]);
		$iRESTORE_COUNTER	=	0;
		for($iRES_COUNTER=0; $iRES_COUNTER<=count($iRESTORE_RESV_IDs)-1; $iRES_COUNTER++){
			$sSQL		=	"UPDATE tbl_reservations SET reservation_cancelled = 0, res_delete_user = NULL, res_delete_datetime = NULL WHERE res_id = ".$iRESTORE_RESV_IDs[$iRES_COUNTER];
			$rsDELTE	=	mysql_query($sSQL) or die(mysql_error());
			$iRESTORE_COUNTER++;	
		}
		
		if($iRESTORE_COUNTER>0)	$sMessage	=	fn_Print_MSG_BOX("trip(s) are restored successfully","C_SUCCESS");
	}
	
	
	if(isset($_POST["txtresvno"]) && $_POST["txtresvno"]!="")	{$iRES_ID		=	mysql_real_escape_string($_POST["txtresvno"]);	$sCriteriaSQL	.=	" AND tbl_reservations.res_id = ".$iRES_ID;}
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")		{$iDeptID		=	mysql_real_escape_string($_POST["drpdept"]);	$sCriteriaSQL	.=	" AND resv_user.dept_id= ".$iDeptID;}
	
	$sSQL	=	"SELECT res_id, res_delete_datetime, CONCAT(resv_user.f_name, ' ', resv_user.l_name) AS resv_user_name, CONCAT(delete_user.f_name, ' ', delete_user.l_name) AS delete_user_name, tbl_departments.dept_name, ".
	"CONCAT('Vehicle: ', vehicle_no, ' From: ', DATE_FORMAT(planned_depart_day_time, '%m/%d/%Y %l:%i %p'), ' To: ', DATE_FORMAT(planned_return_day_time, '%m/%d/%Y %l:%i %p')) AS reservation ".
	"FROM tbl_reservations ".
	"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_user resv_user ON tbl_reservations.user_id = resv_user.user_id ".
	"INNER JOIN tbl_user delete_user ON tbl_reservations.res_delete_user = delete_user.user_id ".
	"INNER JOIN tbl_departments ON tbl_departments.dept_id = resv_user.dept_id ".
	"WHERE reservation_cancelled = 1 ".$sCriteriaSQL." ".
	"ORDER BY res_id DESC";
	
	//"INNER JOIN tbl_user ON tbl_abandon_trips.user_id = tbl_user.user_id ".
	//print($sSQL);
	$rsABANOD_TRIP			=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsABANOD_TRIP);
	
	if($iRECORD_COUNT<=0){$sMessage		=	fn_Print_MSG_BOX("<li>no deleted trip(s) found", "C_ERROR");}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Restore Deleted Trips</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}


function fn_RESTORE_BULK_TRIP(){
	var sErrMessage="";
	if (fn_Assign_Values()!=''){
		document.frm1.action.value	="restore-bulk";
		document.frm1.resid.value	=fn_Assign_Values();	
		document.frm1.submit();
	}
	else{
		sErrMessage='<li>please select trip(s) to restore';
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
								   				<h1 style="margin-bottom: 0px;">RESTORE DELETED TRIPS</h1>
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
						<form name="frm1" action="restore_delete_trips.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="resid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="120">Resv No:<br />
													<input type="text" name="txtresvno" value="<? echo $iRES_ID?>" style="width:120px;" />
												</td>
												
												<td class="label" width="150">Restore by Dept:<br /><?	fn_DEPARTMENT('drpdept', $iDeptID, "150", "1", "All Departments", "SELECT dept_id, dept_name FROM tbl_departments WHERE active = 0 ORDER BY dept_name");?></td>
												<td>
													<br /><input type="button" name="btnGO" value=" RESTORE DEPT " class="Button" style="width:150px;" onClick="fn_SEARCH();" />&nbsp;
													<input type="button" name="delete" value="Restore Individual Trips" class="Button" style="width:165px;" onclick="fn_RESTORE_BULK_TRIP();">
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_deleted_trips.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Resrv. No,Reservation,Department,Deleted_Date_Time,Delete_By'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="30" class="colhead">RIT</td>
												<td width="50" class="colhead">Resrv#</td>
												<td width="270" class="colhead">Reservation</td>
												<td width="70" class="colhead">Resvd By</td>
												<td width="150" class="colhead">Dept.</td>
												<td width="100" class="colhead">Date Time</td>
												<td width="70" class="colhead">Deleted by</td>
												
											</tr>
											<?		$listed	=	0;
													while($rowTRIP	=	mysql_fetch_array($rsABANOD_TRIP)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowTRIP["res_id"].",".$rowTRIP['reservation'].",".$rowTRIP['dept_name'].",".$rowTRIP['res_delete_datetime'].",".$rowTRIP['user_name']));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><input type="checkbox" value="<? echo $rowTRIP['res_id'];?>" name="chkResv" /></td>
															<td class="coldata"><? echo $rowTRIP['res_id'];?></td>
															<td class="coldata"><? echo $rowTRIP['reservation'];?></td>
															<td class="coldata"><? echo $rowTRIP['resv_user_name'];?></td>
															<td class="coldata"><? echo $rowTRIP['dept_name'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowTRIP['res_delete_datetime'],2);?></td>
															
															<td class="coldata"><? echo $rowTRIP['delete_user_name'];?></td>
														</tr>
											<?			}$listed++;	
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}mysql_free_result($rsABANOD_TRIP);	?>
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