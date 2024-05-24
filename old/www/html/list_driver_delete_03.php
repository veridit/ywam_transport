<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$iREG_MONTH		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>These users have not made a reservation for 6 months or more, <li class='bold-font'>if you want to keep any user active check their box in 'Keep' column before deleting the others", "C_SUCCESS");
	$sAction		=	"";
	$iDEL_COUNTER	=	0;
	$sUSER_TYPE		=	"";
	$sRESV_DURATION	=	0;
	$sREG_DURATION	=	0;
	$sREG_CRITERIA	=	"";
	
	
	$iUSER_COUNT	=	0;
	
	
	if(isset($_POST["action"])	&& $_POST["action"]!=""){$sAction		=	$_POST["action"];}
		
	if($sAction=="delusers"){
	
		$sSQL			=	fn_GET_USER_DEL_SQL();
		if(isset($_POST["userid"]) && $_POST["userid"]!=""){
			$sSQL	=	"SELECT deluser.user_id FROM (".$sSQL.") deluser WHERE deluser.user_id NOT IN(".$_POST["userid"].")";
		}
		//print($sSQL);
		
		$rsDEL_USERS	=	mysql_query($sSQL) or die(mysql_error());
		$iUSER_COUNT	=	mysql_num_rows($rsDEL_USERS);
		
		if($iUSER_COUNT>0){
		
		
			while($rowDEL_USER	=	mysql_fetch_array($rsDEL_USERS)){
				$iUSER_ID	=		$rowDEL_USER['user_id'];	
			//print("<br />USER_ID = ".$iUSER_ID);
				//=========================USER DELETE PROCESS=========================
				$sSQL	=	"DELETE FROM tbl_vehicles WHERE user_id = ".$iUSER_ID;
				$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
				
				$sSQL	=	"DELETE FROM tbl_log WHERE user_id = ".$iUSER_ID;
				$rsDEL_LOG	=	mysql_query($sSQL) or die(mysql_error());
				
				$sSQL	=	"DELETE FROM tbl_shop_tasks WHERE user_id = ".$iUSER_ID;
				$rsDEL_SHOP	=	mysql_query($sSQL) or die(mysql_error());
				
				$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE user_id = ".$iUSER_ID;
				$rsRES	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsRES)>0){
					while($rowRES	=	mysql_fetch_array($rsRES)){
						$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowRES["res_id"];
						$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
						$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowRES['res_id'];
						$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
					}
					
					$sSQL	=	"DELETE FROM tbl_reservations WHERE user_id = ".$iUSER_ID;
					$rsDEL_RES	=	mysql_query($sSQL) or die(mysql_error());
					
					$sSQL	=	"DELETE FROM tbl_reservations WHERE assigned_driver = ".$iUSER_ID;
					$rsDEL_DRIVER	=	mysql_query($sSQL) or die(mysql_error());
				}
			//DELETE NOTES OF THE SELECTED USER
				$sSQL	=	"DELETE FROM tbl_user_comments WHERE about_user_id = ".$iUSER_ID." OR posting_user_id = ".$iUSER_ID;
				$rsDEL_COMMENTS	=	mysql_query($sSQL) or die(mysql_error());
			
				$sSQL		=	"DELETE FROM tbl_user WHERE user_id = ".$iUSER_ID;
				$rsDEL_USER	=	mysql_query($sSQL) or die(mysql_error());
				
				//=========================END USER DELETE PROCESS=====================
			}
		
			
		}mysql_free_result($rsDEL_USERS);
		
		if($iUSER_COUNT>0)	$sMessage		=	fn_Print_MSG_BOX("<li>".$iUSER_COUNT." driver(s) deleted successfully", "C_SUCCESS");
		
	}
	
	//if($sAction=="search"){
	
//=============del_users.php
function fn_GET_USER_DEL_SQL(){
global $iGROUP_DRIVER, $iGROUP_TM, $iGROUP_COORDINATOR_STAFF;		
	$sSQL	=	"SELECT lst.user_id, lst.f_name, lst.l_name, lst.department, lst.group_name, lst.reg_date, CASE WHEN MAX(res.res_id) IS NULL THEN 'No Rsvr' ELSE MAX(res.res_id) END AS max_res, CASE WHEN MAX(res.reg_date) IS NULL THEN 'No Rsvr' ELSE MAX(res.reg_date) END AS max_date FROM (";
		
		
	$sSQL	.=	"SELECT u.user_id, u.f_name, u.l_name, d.dept_name AS department, g.group_name, u.reg_date FROM tbl_user u LEFT OUTER JOIN ".
	"(SELECT r.user_id, r.res_id FROM tbl_reservations r WHERE TIMESTAMPDIFF(MONTH, r.reg_date, CURDATE()) <= 12) reservations ".
	"ON u.user_id = reservations.user_id ".
	"INNER JOIN tbl_departments d ON u.dept_id = d.dept_id ".
	"INNER JOIN tbl_user_group g ON u.user_group = g.group_id ".
	"WHERE reservations.res_id IS NULL AND ".
	"(u.user_group = ".$iGROUP_TM." OR u.user_group = ".$iGROUP_DRIVER." OR u.user_group = ".$iGROUP_COORDINATOR_STAFF.") AND (u.user_type = 'Staff') AND TIMESTAMPDIFF(MONTH, u.reg_date, CURDATE()) >= 9 ".
	"GROUP BY u.user_id	";
	
	$sSQL	.=	"UNION ALL	";
	
	$sSQL	.=	"SELECT u.user_id, u.f_name, u.l_name, d.dept_name AS department, g.group_name, u.reg_date FROM tbl_user u LEFT OUTER JOIN ".
	"(SELECT r.user_id, r.res_id FROM tbl_reservations r WHERE TIMESTAMPDIFF(MONTH, r.reg_date, CURDATE()) <= 6) reservations ".
	"ON u.user_id = reservations.user_id ".
	"INNER JOIN tbl_departments d ON u.dept_id = d.dept_id ".
	"INNER JOIN tbl_user_group g ON u.user_group = g.group_id ".
	"WHERE reservations.res_id IS NULL AND ".
	"(u.user_group = ".$iGROUP_TM." OR u.user_group = ".$iGROUP_DRIVER." OR u.user_group = ".$iGROUP_COORDINATOR_STAFF.") AND ".
	"(u.user_type = 'Student' OR u.user_type = 'Mission Bldr.' OR u.user_type = 'Other') AND TIMESTAMPDIFF(MONTH, u.reg_date, CURDATE()) >= 6 GROUP BY u.user_id";
	
	$sSQL	.=	") lst LEFT OUTER JOIN tbl_reservations res ON lst.user_id = res.user_id GROUP BY lst.user_id, lst.f_name, lst.l_name, lst.department, lst.group_name, lst.reg_date, res.res_id, res.reg_date";
	return $sSQL;
}	
	$sSQL	=	fn_GET_USER_DEL_SQL();
	//print($sSQL);
		$rsLIST_USERS		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsLIST_USERS);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no users are found which had reservations before last 6 months", "C_ERROR");
		}
	//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Delete Driver Explanation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript">
function fn_DEL_USERS(){
	if(confirm('Are you sure to delete below listed users?')){
		
		document.frm1.action.value	="delusers";
		document.frm1.userid.value	=fn_Assign_Values();	
		document.frm1.submit();
	}
	
}
function fn_Assign_Values(){

var bChecked 				= 	false;
var chkUser					=	document.frm1.chkUser;
var chkboxValues			=	'';
var sErrString				=	'';
			

if (typeof chkUser.length != 'undefined')
	for(i=0;i<chkUser.length;i++){
		if (chkUser[i].checked){
		
			bChecked		=	true;
		
			if (bChecked)
				if (chkboxValues	==	'')	chkboxValues	=	chkUser[i].value.substring(chkUser[i].value.search(',')+1,chkUser[i].value.length);
				else chkboxValues	=	chkboxValues+','+chkUser[i].value.substring(chkUser[i].value.search(',')+1,chkUser[i].value.length);

		}					
	}
else
	if (chkUser.checked){
		
		bChecked		=	true;
		
		if (bChecked)
			if (chkboxValues	==	'')	chkboxValues	=	chkUser.value.substring(chkUser.value.search(',')+1,chkUser.value.length);
			else chkboxValues	=	chkboxValues+','+chkUser.value.substring(chkUser.value.search(',')+1,chkUser.value.length);

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
								   				<h1 style="margin-bottom: 0px;">DELETE DRIVERS</h1>
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
						<form name="frm1" action="list_driver_delete.php" method="post">
							<input type="hidden" name="action" value="<?=$sAction?>"	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="userid" value=""	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											
											<tr>
												<td width="50">
													<input type="button" name="btnGO" value=" DELETE LISTED USERS " class="Button" style="width:150px;" onClick="fn_DEL_USERS();" />
												</td>
											</tr>
																						
											
										</table>
									</td>
								</tr>
								
								<?	if($iRECORD_COUNT>0){	?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="30" class="colhead">Keep</td>
												<td width="70" class="colhead">User ID</td>
												<td width="70" class="colhead">First Name</td>
												<td width="70" class="colhead">Last Name</td>
												<td width="150" class="colhead">Department</td>
												<td width="50" class="colhead">Group</td>
												<td width="100" class="colhead">Last Resv</td>
											</tr>
											<?		$listed	=	0;	
													while($rowUSERS	=	mysql_fetch_array($rsLIST_USERS)){
														//if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><input type="checkbox" value="<? echo $rowUSERS['user_id'];?>" name="chkUser" /></td>
															<td class="coldata"><? echo $rowUSERS['user_id'];?></td>
															<td class="coldata"><? echo $rowUSERS['f_name'];?></td>
															<td class="coldata"><? echo $rowUSERS['l_name'];?></td>
															<td class="coldata"><? echo $rowUSERS['department'];?></td>
															<td class="coldata"><? echo $rowUSERS['group_name'];?></td>
															<td class="coldata"><? if($rowUSERS['max_date']!="No Rsvr") echo $rowUSERS['max_res']."-".fn_cDateMySql($rowUSERS['max_date'],2);else echo "No Resv";?></td>
														</tr>
											<?			//}$listed++;
													}
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsLIST_USERS);	?>
								<tr><td><? //include('inc_paginationlinks.php');	?></td></tr>
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
 