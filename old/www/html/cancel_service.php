<?Php
include('inc_connection.php');
include('inc_function.php');

	//$sSQL			=	"SELECT MAX(vs.srvc_id) AS srvc_id FROM tbl_srvc_resvs vs WHERE TIMESTAMPDIFF(MINUTE, vs.to_date, NOW()) >= 1 AND is_cancelled = 0 GROUP BY vs.vehicle_id ORDER BY srvc_id";
	$sSQL			=	"UPDATE tbl_srvc_resvs vs SET is_cancelled = 1 WHERE TIMESTAMPDIFF(MINUTE, vs.to_date, NOW()) >= 1 AND is_cancelled = 0 AND service_type = 'temporary'";
	$rsSERVICE		=	mysql_query($sSQL) or die(mysql_error());
	$sMessage		=	"";
	
	echo $sMessage;

?>