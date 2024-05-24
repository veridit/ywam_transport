<?Php
include('inc_connection.php');
include('inc_function.php');

	$iUPDATE_RECORDS	=	0;
	$sMessage			=	"";
	
		//first all childs deletion
		$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE TIMESTAMPDIFF(MONTH, tbl_reservations.reg_date, CURDATE()) >= 9";
		$rsLIST_OLD	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsLIST_OLD)>0){
			while($rowLIST_OLD		=	mysql_fetch_array($rsLIST_OLD)){
				$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowLIST_OLD["res_id"];
				$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
				$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowLIST_OLD['res_id'];
				$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
			}
		}mysql_free_result($rsLIST_OLD);
		$sSQL			=	"DELETE FROM tbl_reservations WHERE TIMESTAMPDIFF(MONTH, tbl_reservations.reg_date, CURDATE()) >= 9";		
		$rsDEL_TRIPS	=	mysql_query($sSQL) or die(mysql_error());
		/*$iUPDATE_RECORDS	=	mysql_affected_rows();
		if($iUPDATE_RECORDS>0)	$sMessage			=	$iUPDATE_RECORDS." Trips Explanations has been deleted";
		echo $sMessage;*/


?>