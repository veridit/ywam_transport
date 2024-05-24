<?Php
include('inc_connection.php');
include('inc_function.php');

$iUPDATE_RECORDS	=	0;
$sMessage			=	"";
$sSQL				=	"UPDATE tbl_trip_details SET desc_problem = '' WHERE DATEDIFF(CURDATE(), reg_date) >= 90 AND desc_problem <> ''";
$rsDRIVER_NOTES		=	mysql_query($sSQL) or die(mysql_error());
$iUPDATE_RECORDS	=	mysql_affected_rows();
if($iUPDATE_RECORDS>0)	$sMessage			=	$iUPDATE_RECORDS." Driver notes deleted";
echo $sMessage;
?>