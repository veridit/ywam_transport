<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iUSER_ID		=	"";
	
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li>This system automatically deletes trips that are more than 9 months old.<li class='bold-font'>The Trips listed below will be deleted next Sunday", "C_SUCCESS");
	$iDays			=	0;
	$sAction		=	"";
	
	if(isset($_POST["action"]) && $_POST["action"]!="")				{$sAction		=	$_POST["action"];}
	//if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")			{$iDays			=	$_POST["drpdays"];	$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_reservations.reg_date) >= ".$iDays;}
	if(isset($_POST["drpdays"]) && $_POST["drpdays"]!="")			{$iDays			=	$_POST["drpdays"];	$sCriteriaSQL	.=	" AND TIMESTAMPDIFF(MONTH, tbl_reservations.reg_date, CURDATE()) >= ".$iDays;}
	
	


	if($sAction=="delete"){			
		if(fn_DELETE_RECORD("tbl_reservations", "res_id", $_POST["resid"]))
			$sMessage	=	fn_Print_MSG_BOX("Old Trip has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! old trip not been deleted", "C_ERROR");
	}
	
	
	if($sAction=="dellist" && $iDays!=0){
		//first all childs deletion
		$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE TIMESTAMPDIFF(MONTH, tbl_reservations.reg_date, CURDATE()) >= ".$iDays;
		$rsLIST_OLD	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsLIST_OLD)>0){
			while($rowLIST_OLD		=	mysql_fetch_array($rsLIST_OLD)){
				$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowLIST_OLD["res_id"];
				$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
				$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowLIST_OLD['res_id'];
				$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
			}
		}mysql_free_result($rsLIST_OLD);
		$sSQL			=	"DELETE FROM tbl_reservations WHERE TIMESTAMPDIFF(MONTH, tbl_reservations.reg_date, CURDATE()) >= ".$iDays;		
		$rsDEL_TRIPS	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("Old trip(s) has been deleted", "C_SUCCESS");
	}
	
	
		$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.reg_date AS reservation_datetime, tbl_reservations.planned_depart_day_time AS planned_depart_datetime,tbl_reservations.planned_return_day_time AS planned_return_datetime, ".
		"CONCAT(tbl_departments.dept_id, ' ', dept_name) AS dept, CONCAT(driver.f_name, ' ', driver.l_name) AS driver_name  FROM tbl_reservations ".
		"INNER JOIN tbl_user resv_user ON tbl_reservations.user_id = resv_user.user_id ".
		"INNER JOIN tbl_departments ON resv_user.dept_id = tbl_departments.dept_id ".
		"INNER JOIN tbl_user driver ON tbl_reservations.assigned_driver = driver.user_id ".
		"INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY tbl_reservations.reg_date DESC";
		//print($sSQL);
		$rsLOG		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsLOG);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no trips explanations are found", "C_ERROR");
		}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Delete Trips Explanation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">



<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}

function fn_DELETE_LOG(iRESID){
	document.frm1.resid.value=iRESID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}

function fn_DELETE_OLD_TRIPS(){
	document.frm1.action.value='dellist';
	document.frm1.submit();
}

function fn_EXCEL(){
	var sErrMessage='';
	if(document.frm1.chkCSV.checked==true){
		document.frm1.action.value='excel';
		document.frm1.submit();
	}else{
		sErrMessage=sErrMessage+'<li>please click on "Download Excel Backup of Preview List"';
		fn_draw_ErrMsg(sErrMessage);
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
								   				<h1 style="margin-bottom: 0px;">DELETE TRIPS EXPLANATION</h1>
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
						<form name="frm1" action="delete_old_trips.php" method="post">
							<input type="hidden" name="action" value="<?=$sAction?>"	/>
							<input type="hidden" name="resid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								
								<?	if($iRECORD_COUNT>0){
										$sFname	=	'excel_reports/delete_old_trips.csv'; print("<tr><td><a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a></td></tr>");
									//if ((isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") && ($sAction=="excel")){
										$fp	=	"";
										$fp = fopen($sFname, 'w');
										fputcsv($fp, explode(',','Resv#,Department,Resv_DateTime,Depart_DateTime,Return_DateTime,Assigned_Driver'));
									//}	
								?>
								<tr><td><hr /></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Resv#</td>
												<td width="170" class="colhead">Department</td>
												<td width="140" class="colhead">Resv Date</td>
												<td width="140" class="colhead">Depart Date</td>
												<td width="140" class="colhead">Return Date</td>
												<td width="100" class="colhead">Assgnd Driver</td>
												
												<td width="30" class="colhead">Action</td>
											</tr>
											<?	
										
												$listed	=	0;	
													while($rowLOG	=	mysql_fetch_array($rsLOG)){
														if ((isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")	&& ($sAction=="excel")){
															fputcsv($fp, explode(',', $rowLOG["res_id"].",".$rowLOG["dept"].",".$rowLOG['reservation_datetime'].",".$rowLOG["planned_depart_datetime"].",".$rowLOG["planned_return_datetime"].",".$rowLOG["driver_name"]));
														}
														
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowLOG['res_id'];?></td>
															<td class="coldata"><? echo $rowLOG['dept'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['reservation_datetime'], 2);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['planned_depart_datetime'], 2);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['planned_return_datetime'], 2);?></td>
															<td class="coldata"><? echo $rowLOG['driver_name'];?></td>

															<td class="coldata" align="center"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DELETE_OLD_TRIPS)){?><a href="javascript:void(0);" onClick="if(confirm('Are you sure to delete this trip?')) {fn_DELETE_LOG(<? echo $rowLOG['res_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}
													fclose($fp);
										
											?>
								
										</table>
									</td>
								</tr>
								<?	
									}	//mysql_free_result($rsLOG);	
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
 