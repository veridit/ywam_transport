<?Php
		include('inc_connection.php');
		include('inc_function.php');
		
		$sACTION	=	"";		$iVECHILE_ID	=	0;
		
		$sACTION 			= 	$_REQUEST["action"];
				
		if(isset($_REQUEST["vid"]) && $_REQUEST["vid"]!=""){		$iVECHILE_ID 				= 	$_REQUEST["vid"];	}
		
		

if ($sACTION == "lastmileage"){

		$sSQL	=	"SELECT end_mileage FROM tbl_trip_details WHERE res_id = (SELECT MAX(tbl_reservations.res_id) FROM tbl_reservations INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id WHERE vehicle_id = ".$iVECHILE_ID.") ";	
		$rsMILEAGE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsMILEAGE)>0){
			$rowMILEAGE	=	mysql_fetch_array($rsMILEAGE);
			echo $rowMILEAGE['end_mileage'];
		}else{
			echo "Vehicle is not having any trip yet";
		}mysql_free_result($rsMILEAGE);
			
}

?>