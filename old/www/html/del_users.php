<?Php
include('inc_connection.php');
include('inc_function.php');

$sSQL	=	"SELECT u.user_id FROM tbl_user u LEFT OUTER JOIN ".
"(SELECT r.user_id, r.res_id FROM tbl_reservations r WHERE PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'), DATE_FORMAT(r.reg_date, '%Y%m')) <= 12) reservations ".
"ON u.user_id = reservations.user_id WHERE reservations.res_id IS NULL AND ".
"(u.user_group = 1 OR u.user_group = 3 OR u.user_group = 5) AND (u.user_type = 'Staff') AND TIMESTAMPDIFF(MONTH, u.reg_date, CURDATE()) >= 9 ".
"GROUP BY u.user_id ";

$sSQL	.=	"UNION ALL	";

$sSQL	.=	"SELECT u.user_id FROM tbl_user u LEFT OUTER JOIN ".
"(SELECT r.user_id, r.res_id FROM tbl_reservations r WHERE PERIOD_DIFF(DATE_FORMAT(CURDATE(), '%Y%m'), DATE_FORMAT(r.reg_date, '%Y%m')) <= 6) reservations ".
"ON u.user_id = reservations.user_id WHERE reservations.res_id IS NULL AND ".
"(u.user_group = 1 OR u.user_group = 3 OR u.user_group = 5) AND ".
"(u.user_type = 'Student' OR u.user_type = 'Mission Bldr.' OR u.user_type = 'Other') AND TIMESTAMPDIFF(MONTH, u.reg_date, CURDATE()) >= 6 GROUP BY u.user_id";
	
	//print($sSQL);
	
	$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
	$sMessage		=	"No user has been  deleted for this week";
	$iUSER_COUNT	=	0;
	$iUSER_COUNT	=	mysql_num_rows($rsUSERS);
	
	if($iUSER_COUNT>0){
		while(list($iUSER_ID)	=	mysql_fetch_row($rsUSERS)){
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
	}mysql_free_result($rsUSERS);

	if($iUSER_COUNT>0)	$sMessage		=	"<li> ".$iUSER_COUNT." user(s) deleted successfully";

	echo $sMessage;
			
?>