<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iUSER_ID		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$iDays			=	0;
	$sAction		=	"";
	
	if(isset($_POST["action"]) && $_POST["action"]!="")				{$sAction		=	$_POST["action"];}
	if(isset($_POST["drplogdays"]) && $_POST["drplogdays"]!="")		{$iDays			=	$_POST["drplogdays"];	$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_log.login_datetime) >= ".$iDays;}
	if(isset($_POST["drpuser"]) && $_POST["drpuser"]!="")			{$iUSER_ID		=	$_POST["drpuser"];		$sCriteriaSQL	.=	" AND tbl_log.user_id = ".$iUSER_ID;}
	
	if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
		$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);	
		$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);
		$sCriteriaSQL	.=	" AND DATE(tbl_log.login_datetime) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
	}
	
	if($sAction=="delete"){			
		if(fn_DELETE_RECORD("tbl_log", "log_id", $_POST["logid"]))
			$sMessage	=	fn_Print_MSG_BOX("User login routine has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! routine not been deleted", "C_ERROR");
	}
	
	
	if($sAction=="dellist" && $iDays!=0){
		$sSQL			=	"DELETE FROM tbl_log WHERE DATEDIFF(CURDATE(), tbl_log.login_datetime) >= ".$iDays;		
		$rsDEL_LOG_LIST	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("User login routine(s) has been deleted", "C_SUCCESS");
	}
	
	if($sAction=="search"){
		/*$sSQL	=	"SELECT tbl_log.log_id, DATE_FORMAT(tbl_log.login_datetime, '%m/%d/%Y %r') AS login_datetime, DATE_FORMAT(tbl_log.logout_datetime, '%m/%d/%Y %r') AS logout_datetime, ".
		"tbl_log.ip_address, CONCAT(tbl_departments.dept_id, ' ', dept_name) AS dept, CONCAT(f_name, ' ', l_name) AS user_name FROM tbl_log ".
		"INNER JOIN tbl_user ON tbl_log.user_id = tbl_user.user_id ".
		"INNER JOIN tbl_departments ON tbl_user.dept_id = tbl_departments.dept_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY log_id DESC";*/
		$sSQL	=	"SELECT tbl_log.log_id, tbl_log.login_datetime, logout_datetime, ".
		"tbl_log.ip_address, CONCAT(tbl_departments.dept_id, ' ', dept_name) AS dept, CONCAT(f_name, ' ', l_name) AS user_name FROM tbl_log ".
		"INNER JOIN tbl_user ON tbl_log.user_id = tbl_user.user_id ".
		"INNER JOIN tbl_departments ON tbl_user.dept_id = tbl_departments.dept_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY log_id DESC";
		//print($sSQL);
		$rsLOG		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsLOG);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no login routines found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List User Logins</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<!-- firebug lite -->
		<script type="text/javascript" src="./js/common_scripts.js"></script>
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

function fn_DELETE_LOG(iDEPTID){
	document.frm1.logid.value=iDEPTID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}

function fn_DELETE_LOG_LIST(){
	document.frm1.action.value='dellist';
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
								   				<h1 style="margin-bottom: 0px;">LIST USER LOGINS</h1>
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
						<form name="frm1" action="list_log.php" method="post">
							<input type="hidden" name="action" value="<?=$sAction?>"	/>
							<input type="hidden" name="logid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="120">Users:<br />
												<?	fn_DISPLAY_USERS('drpuser', $iUSER_ID, "120", "1", "--All--", "CONCAT(l_name, ' ', f_name) AS l_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_SERVICETCH.",".$iGROUP_COORDINATOR_STAFF);?></td>
												
												<td class="label" width="110">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:80px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="110">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:80px;" class="date-pick dp-applied" />
												</td>
												<td width="100" class="label">Delete<br />
													<?
														$arrLOG[0][0]	=	"60";		$arrLOG[0][1]	=	"60 Days";
														$arrLOG[1][0]	=	"180";		$arrLOG[1][1]	=	"180 Days";
														$arrLOG[2][0]	=	"360";		$arrLOG[2][1]	=	"360 Days";
													?>
													<select name="drplogdays" size="1" style="width:100px;">
														<option value="" selected>--All--</option>
													<? 	for($iCounter=0;$iCounter<=2;$iCounter++){?>
														<option value="<? echo $arrLOG[$iCounter][0]?>" <? if($iDays == $arrLOG[$iCounter][0]) echo "selected";?>><? echo $arrLOG[$iCounter][1]?></option>
													<?	}?>
													</select>
												</td>
												
												<td width="130"><br />
													<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" />&nbsp;&nbsp;&nbsp;
													<input type="button" name="btnDEL" value=" DELETE " class="Button" style="width:70px;" onClick="if(document.frm1.drplogdays.value!=''){ if (confirm('Warning! all routines before '+ document.frm1.drplogdays.value +' Days will be deleted \n excel file downloaded.?')) fn_DELETE_LOG_LIST(); return false;} else{alert('please select Days to delete login routines');}" />
												</td>
											</tr>
											<tr>
												<td colspan="6">
													<table width="100%">
														<tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_loginroutine.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','User,Department,Login_DateTime,Logout_DateTime,IP_Address'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">User</td>
												<td width="170" class="colhead">Department</td>
												<td width="140" class="colhead">Login Date Time</td>
												<td width="140" class="colhead">Logout Date Time</td>
												<td width="70" class="colhead">IP</td>
												<td width="30" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowLOG	=	mysql_fetch_array($rsLOG)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
															fputcsv($fp, explode(',', $rowLOG["user_name"].",".$rowLOG["dept"].",".$rowLOG['login_datetime'].",".$rowLOG["logout_datetime"].",".$rowLOG["ip_address"]));
														}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowLOG['user_name'];?></td>
															<td class="coldata"><? echo $rowLOG['dept'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['login_datetime'],2);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['logout_datetime'],2);?></td>
															<td class="coldata"><? echo $rowLOG['ip_address'];?></td>

															<td class="coldata" align="center"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LOG_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('Are you sure to delete this login-logout routine for the user!')) {fn_DELETE_LOG(<? echo $rowLOG['log_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsLOG);	?>
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
 