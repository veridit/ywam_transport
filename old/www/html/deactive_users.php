<?Php
include('inc_connection.php');
include('inc_function.php');

	$sMessage		=	"";
	//$sSQL		=	"UPDATE tbl_user SET active = 0 WHERE (user_group = ".$iGROUP_DRIVER." OR user_group = ".$iGROUP_SERVICETCH." OR user_group = ".$iGROUP_COORDINATOR_STAFF.") AND (active = 1) AND (YEAR(end_permit) = ".date('Y', strtotime(date("Y", strtotime(date('Y'))) . " -1 year")).")";
	$sSQL		=	"UPDATE tbl_user SET active = 0 WHERE ".
	"(user_group = ".$iGROUP_DRIVER." OR user_group = ".$iGROUP_SERVICETCH." OR user_group = ".$iGROUP_COORDINATOR_STAFF.") AND ".
	"(active = 1) AND ".
	"(YEAR(end_permit) = ".date('Y', strtotime(date("Y", strtotime(date('Y'))) . " -1 year")).") ".
	"AND tbl_user.user_id IN (SELECT r.user_id FROM tbl_reservations r WHERE TIMESTAMPDIFF(MONTH, r.reg_date, CURDATE()) <= 4)";
	
	
	//print($sSQL);
	$rsDEPT		=	mysql_query($sSQL) or die(mysql_error());
	$iUPDATE_RECORDS	=	mysql_affected_rows();
	if($iUPDATE_RECORDS>0)	$sMessage			=	$iUPDATE_RECORDS." Users are deactivated";

	echo $sMessage;
	
	//echo date('d/m/Y', strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " -1 year"));
	//echo date('Y', strtotime(date("Y", strtotime(date('Y'))) . " -1 year"));



?>