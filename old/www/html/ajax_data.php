<?Php
		session_start();
		include('inc_connection.php');
		include('inc_function.php');
		require("class.phpmailer.php");
		
		$sACTION	=	"";		$iVECHILE_ID	=	0;		$iRES_ID	=	0;		$sBETWEEN_RES_NO	=	"";		$sPROCESS_STRING	=	"";		$iNOTES_ID	=	0;
		
		
		$sACTION 			= 	$_REQUEST["action"];
		
				
		if(isset($_REQUEST["vid"]) && $_REQUEST["vid"]!=""){		$iVECHILE_ID 				= 	$_REQUEST["vid"];	}
		if(isset($_REQUEST["rid"]) && $_REQUEST["rid"]!=""){		$iRES_ID 					= 	$_REQUEST["rid"];	}
		if(isset($_REQUEST["nid"]) && $_REQUEST["nid"]!=""){		$iNOTES_ID 					= 	$_REQUEST["nid"];	}
		
		

if ($sACTION == "lastmileage"){	echo	fn_VEHICLE_LAST_MILEAGE($iVECHILE_ID);}
if ($sACTION == "shoptask")		{
	$sShopTask	=	"";
	$sShopTask	=	fn_VEHICLE_LAST_MILEAGE($iVECHILE_ID);
	$sShopTask	.=	"t=".fn_GET_FIELD_BY_QUERY("SELECT tbl_vehicle_type.v_type FROM tbl_vehicle_type INNER JOIN tbl_vehicles ON tbl_vehicle_type.v_type_id = tbl_vehicles.model WHERE tbl_vehicles.vehicle_id = ".$iVECHILE_ID);
	$sShopTask	.=	"m=".fn_GET_FIELD_BY_QUERY("SELECT tbl_vehicle_brand.brand_name FROM tbl_vehicle_brand INNER JOIN tbl_vehicles ON tbl_vehicle_brand.brand_id = tbl_vehicles.make_id WHERE tbl_vehicles.vehicle_id = ".$iVECHILE_ID);
	$sShopTask	.=	"f=".fn_GET_FIELD_BY_QUERY("SELECT tbl_vehicles.oil_filter FROM tbl_vehicles WHERE tbl_vehicles.vehicle_id = ".$iVECHILE_ID);
	echo $sShopTask;
}

if ($sACTION == "restrictedmileage"){	
	$sRESTRICTED_DATA	=	"";
	$sEND_MILES			=	0;		$sEND_MILES_DATE	=	0;
	/*$sRESTRICTED_DATA	.=	fn_VEHICLE_LAST_MILEAGE($iVECHILE_ID);
	$sRESTRICTED_DATA	.=	"d=".fn_VEHICLE_LAST_END_GAS_DATE($iVECHILE_ID);*/
	
	$sSQL	=	"SELECT end_mileage, reg_date FROM tbl_restricted_charges rc WHERE vehicle_id = ".$iVECHILE_ID." ORDER BY charge_id DESC LIMIT 1";
	//print($sSQL);
	$rsEND_MILEAGE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsEND_MILEAGE)>0){
		list($sEND_MILES, $sEND_MILES_DATE)		=		mysql_fetch_row($rsEND_MILEAGE);
	}mysql_free_result($rsEND_MILEAGE);
	
	$sRESTRICTED_DATA	.=	$sEND_MILES;
	$sRESTRICTED_DATA	.=	"d=".fn_cDateMySql($sEND_MILES_DATE,2);
	$sRESTRICTED_DATA	.=	"m=".number_format(fn_GET_FIELD("tbl_vehicles", $iVECHILE_ID, "vehicle_id", "cost_rate"), 2, '.', '');
	echo $sRESTRICTED_DATA;
}
if ($sACTION == "end_miles_gas"){
	$sSQL	=	"SELECT tbl_reservations.vehicle_id, tbl_reservations.user_id, tbl_reservations.card_no, CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS requestor_name, tbl_reservations.assigned_driver, ".
	"home.dept_name AS home_dept, bill.dept_name AS bill_dept ".
	"FROM tbl_reservations INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
	"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
	"WHERE tbl_reservations.res_id = ".$iRES_ID;
	//print($sSQL);
	$rsRES_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRES_VEHICLE)>0){
	$rowRES_VEHICLE	=	mysql_fetch_array($rsRES_VEHICLE);
	if($rowRES_VEHICLE['user_id']!=$rowRES_VEHICLE['assigned_driver'] && $rowRES_VEHICLE['assigned_driver']!=0){$sPROCESS_STRING	=	"a=".fn_GET_ASSIGNED_DRIVER($rowRES_VEHICLE['assigned_driver']);}
	//below was good
	echo	"m=".fn_VEHICLE_LAST_MILEAGE($rowRES_VEHICLE['vehicle_id'])."g=".fn_VEHICLE_LAST_END_GAS($rowRES_VEHICLE['vehicle_id'])."d=".fn_VEHICLE_LAST_END_GAS_DATE($rowRES_VEHICLE['vehicle_id'])."r=".$rowRES_VEHICLE['requestor_name']."h=".$rowRES_VEHICLE['home_dept']."b=".$rowRES_VEHICLE['bill_dept']."c=".$rowRES_VEHICLE['card_no'].$sPROCESS_STRING;

	}mysql_free_result($rsRES_VEHICLE);
}

if ($sACTION == "change_dept"){
	$sSQL	=	"SELECT CONCAT(tbl_user.f_name, ' ', tbl_user.l_name) AS requestor_name, tbl_reservations.billing_dept, ".
	"home.dept_name AS home_dept, bill.dept_name AS bill_dept ".
	"FROM tbl_reservations INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
	"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
	"WHERE tbl_reservations.res_id = ".$iRES_ID;
	//print($sSQL);
	$rsRES_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRES_VEHICLE)>0){
	$rowRES_VEHICLE	=	mysql_fetch_array($rsRES_VEHICLE);
	
	//below was good
	echo	fn_DEPARTMENT("drpbilldept", $rowRES_VEHICLE['billing_dept'], "170", "1", "--Billing Department--")."r=".$rowRES_VEHICLE['requestor_name']."h=".$rowRES_VEHICLE['home_dept'];

	}mysql_free_result($rsRES_VEHICLE);
}

if ($sACTION == "restricted"){

	$sSQL	=	"SELECT restricted FROM tbl_vehicles WHERE vehicle_id = ".$iVECHILE_ID;
	$rsRESTRICTED	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRESTRICTED)>0){
		echo mysql_result($rsRESTRICTED,0);
	}else{
		echo "please select valid vehicle number";
	}mysql_free_result($rsRESTRICTED);
}

if ($sACTION == "process"){
	
	
	$sSQL	=	"SELECT tbl_reservations.user_id, tbl_user.f_name, tbl_user.l_name, tbl_reservations.vehicle_id, tbl_reservations.destination, ".
	"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
	"tbl_reservations.assigned_driver, tbl_user_group.group_name FROM tbl_reservations ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_user_group ON tbl_user.user_group = tbl_user_group.group_id ".
	"WHERE res_id = ".$iRES_ID;
	$rsPROCESS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsPROCESS)>0){
		$rowPROCESS	=	 mysql_fetch_array($rsPROCESS);
		if($rowPROCESS['user_id']!=$rowPROCESS['assigned_driver'] && $rowPROCESS['assigned_driver']!=0){$sPROCESS_STRING	=	"Assgnd Driver:&nbsp;<span class='normal'>".fn_GET_ASSIGNED_DRIVER($rowPROCESS['assigned_driver'])."</span><br />";}
		
		$sPROCESS_STRING	.=	"Rsvrd by:&nbsp;<span class='normal'>".$rowPROCESS['f_name']."</span>".
		"&nbsp;&nbsp;&nbsp;<span class='normal'>".$rowPROCESS['l_name']."</span><br />".
		"Rsvrd User Group:&nbsp;<span class='normal'>".$rowPROCESS['group_name']."</span><br />".
		"Depart Date:&nbsp;<span class='normal'>".fn_cDateMySql($rowPROCESS['planned_depart_day_time'],2)."</span><br />".
		"Return Date:&nbsp;<span class='normal'>".fn_cDateMySql($rowPROCESS['planned_return_day_time'],2)."</span><br />".
		"Last Gas:&nbsp;<span class='normal'>".fn_VEHICLE_LAST_END_GAS($rowPROCESS['vehicle_id'])."</span><br />".
		"Last Gas Date:&nbsp;<span class='normal'>".fn_VEHICLE_LAST_END_GAS_DATE($rowPROCESS['vehicle_id'])."</span><br />".
		"Destination:&nbsp;<span class='normal'>".stripslashes(($rowPROCESS['destination']))."</span><br />";
		
		echo $sPROCESS_STRING;
	}else{
		echo "please select valid reservation to process";
	}mysql_free_result($rsPROCESS);
}

if ($sACTION == "tmnotes"){
	$sSQL	=	"SELECT comments FROM tbl_user_comments WHERE id = ".$iNOTES_ID;
	$rsNOTES	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsNOTES)>0){
		echo	stripslashes(mysql_result($rsNOTES,0));
	}else{
		echo "please select valid notes to edit";
	}mysql_free_result($rsNOTES);
}


if ($sACTION == "mass-email"){
	$sMESSAGE_ID	=	$_REQUEST["mid"];
	$sSQL	=	"SELECT link_text FROM tbl_info_links WHERE link_id = ".$sMESSAGE_ID;
	$rsMASS_EMAIL	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsMASS_EMAIL)>0){
		echo	stripslashes(mysql_result($rsMASS_EMAIL,0));
	}else{
		echo "ERROR";
	}mysql_free_result($rsMASS_EMAIL);
}


if ($sACTION == "send-mass-email"){


	$sEMAIL_TEXT	=	$_REQUEST["txtemail"];
	$sMESSAGE_ID	=	$_REQUEST["msgid"];
	
	
	$sStatus		=	"";
	$sCriteriaSQL	=	"";
	$sACTIVE_SQL	=	"";
	$iSEL_DRIVER	=	"";
	$sUserType		=	"";
	$iUSER_GROUP	=	0;
	
	//=========
	if(isset($_REQUEST["drpstatus"]) && $_REQUEST["drpstatus"]!="")				{$sStatus		=	$_REQUEST["drpstatus"];			$sCriteriaSQL	.=	" AND tbl_user.active = ".$sStatus;	$sACTIVE_SQL	=	" AND d.active = ".$sStatus;}
	if(isset($_REQUEST["drpusers"]) && $_REQUEST["drpusers"]!="")				{$iSEL_DRIVER	=	$_REQUEST["drpusers"];			$sCriteriaSQL	.=	" AND tbl_user.l_name = '".$iSEL_DRIVER."'";}
	if(isset($_REQUEST["drpusertype"]) && $_REQUEST["drpusertype"]!="")		{
		$sUserType	=	$_REQUEST["drpusertype"];
		if($sUserType=="Non-Staff")
			$sCriteriaSQL	.=	" AND (tbl_user.user_type = 'Mission Bldr.' OR tbl_user.user_type = 'Student' OR tbl_user.user_type = 'Other')";
		else
			$sCriteriaSQL	.=	" AND tbl_user.user_type = 'Staff'";
	}
	if(isset($_REQUEST["drpusergroup"]) && $_REQUEST["drpusergroup"]!="")			{$iUSER_GROUP		=	$_REQUEST["drpusergroup"];		$sCriteriaSQL	.=	" AND tbl_user.user_group = ".$iUSER_GROUP;}
	//===================
	
	$sSQL			=	"UPDATE tbl_info_links SET link_text = '".addslashes($sEMAIL_TEXT)."' WHERE link_id = $sMESSAGE_ID";
	$rsUPDATE		=	mysql_query($sSQL) or die(mysql_error());
	
	mysql_query("DELETE FROM tbl_temp_mass_emails");
	
	if($iUSER_GROUP=="leaders"){
		mysql_query("INSERT INTO tbl_temp_mass_emails (SELECT leader_email, CONCAT(leader_f_name, ' ', leader_l_name) FROM tbl_departments d WHERE 1=1 ".$sACTIVE_SQL.")") or die(mysql_error());
	}else{
		//mysql_query("INSERT INTO tbl_temp_mass_emails (SELECT email, CONCAT(f_name, ' ', l_name) FROM tbl_user WHERE tbl_user.active = 1 ".$sCriteriaSQL.")");
		mysql_query("INSERT INTO tbl_temp_mass_emails (SELECT email, CONCAT(f_name, ' ', l_name) FROM tbl_user WHERE 1 = 1 ".$sCriteriaSQL.")");
	}
			
	echo "SUCCESS";
}


if ($sACTION == "send-deactivation-notice"){

	$sMessage		=	"SUCCESS";
	$sEMAIL_TEXT	=	$_REQUEST["txtemail"];
	$sMESSAGE_ID	=	$_REQUEST["msgid"];
	
	if($sEMAIL_TEXT!=""){

		$sSQL			=	"UPDATE tbl_info_links SET link_text = '".addslashes($sEMAIL_TEXT)."' WHERE link_id = $sMESSAGE_ID";
		$rsUPDATE		=	mysql_query($sSQL) or die(mysql_error());
	}
	
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = $sMESSAGE_ID";
	$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = $sMESSAGE_ID";
	$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
	
	
	$sSQL	=	"SELECT u.email FROM tbl_user u  INNER JOIN ".
	"(SELECT r.user_id FROM tbl_reservations r WHERE TIMESTAMPDIFF(MONTH, r.reg_date, CURDATE()) >= 4) reservations ".
	"ON u.user_id = reservations.user_id ".
	"WHERE u.active = 1 GROUP BY u.user_id, u.email ORDER BY u.l_name";
	
	$rsDRIVERS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsDRIVERS)>0){
		while(list($sDRIVER_EMAIL)	=	mysql_fetch_row($rsDRIVERS)){
		
			$mail = new PHPMailer();
			$mail->Host     = 	$sCOMPANY_SMTP; // SMTP servers
			$mail->From     = 	$sSUPPORT_EMAIL;
			$mail->FromName = 	$sCOMPANY_Name;
			$mail->AddAddress($sDRIVER_EMAIL);
			$mail->IsHTML(true);                               // send as HTML
			$mail->Subject  =  	$sEmailSubject;
			$mail->Body    	= 	$sMailMSG;
			if(!$mail->Send()){			   $sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email to $sDRIVER_EMAIL","C_ERROR");	}
			
			//$sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email to $sDRIVER_EMAIL","C_ERROR");
		}
	}mysql_free_result($rsDRIVERS);
	
			
	echo $sMessage;
}


if ($sACTION == "slips-to-close"){
	$bFRIDAY	=	$_REQUEST["bfriday"];
	echo fn_SLIPS_TO_MADE($bFRIDAY);
}


if ($sACTION == "leader-info"){
	$iDRIVER_ID	=	$_REQUEST["did"];
	$sLEADER_STRING	=	"";
	$sSQL	=	"SELECT f_name, l_name, email, phone FROM tbl_user WHERE user_id = ".$iDRIVER_ID;
	$rsLEADER_INFO	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsLEADER_INFO)>0){
		$rowLEADER	=	mysql_fetch_array($rsLEADER_INFO);
		$sLEADER_STRING	=	$rowLEADER['f_name']."L=".$rowLEADER['l_name']."E=".$rowLEADER['email']."P=".$rowLEADER['phone'];
		echo $sLEADER_STRING;
	}else{
		echo "ERROR";
	}mysql_free_result($rsLEADER_INFO);
}


if ($sACTION == "load-email"){
	$iDRIVER_ID	=	$_REQUEST["did"];
	$sLEADER_STRING	=	"";
	$sSQL	=	"SELECT email FROM tbl_user WHERE user_id = ".$iDRIVER_ID;
	$rsDRIVER_EMAIL	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsDRIVER_EMAIL)>0){
		$rowDRIVER_EMAIL	=	mysql_fetch_array($rsDRIVER_EMAIL);
		$sLEADER_STRING		=	$rowDRIVER_EMAIL['email'];
		echo $sLEADER_STRING;
	}else{
		echo "ERROR";
	}mysql_free_result($rsDRIVER_EMAIL);
}

if ($sACTION == "load-reserved-by"){
	$iRES_ID	=	$_REQUEST["rid"];
	$sDRIVER_NAME		=	"";
	$sASSGINED_DRIVER	=	"";
	$sOVER_DUE			=	"";
	$sDEPT_NAME			=	"";
	$sTRIP_STRING		=	"";

	$sSQL			=	"SELECT resv_drvr.driver_name, resv_drvr.asgnd_driver_name, resv_drvr.overdue, CASE WHEN abandoneds.res_id IS NULL THEN 'NO ABANDONED RESERVATION FOUND' ELSE abandoneds.res_id END AS abandoned_resv, resv_drvr.dept_name FROM  ";
	
	$sSQL			.=	"(SELECT CONCAT(u.f_name,' ',u.l_name) AS driver_name, CONCAT(asgned_drvr.f_name,' ',asgned_drvr.l_name) AS asgnd_driver_name, TIMESTAMPDIFF(DAY, r.planned_return_day_time, CURDATE()) AS overdue, r.assigned_driver, d.dept_name ".
	"FROM tbl_reservations r INNER JOIN tbl_user u ON r.user_id = u.user_id INNER JOIN tbl_user asgned_drvr ON r.assigned_driver = asgned_drvr.user_id INNER JOIN tbl_departments d ON asgned_drvr.dept_id = d.dept_id WHERE r.res_id = ".$iRES_ID.") resv_drvr ";
	
	
	$sSQL			.=	"LEFT OUTER JOIN ";
	$sSQL			.=	"(SELECT a.res_id, r.assigned_driver FROM tbl_abandon_trips a INNER JOIN tbl_reservations r ON a.res_id = r.res_id WHERE r.assigned_driver = (SELECT r.assigned_driver FROM tbl_reservations r WHERE r.res_id = ".$iRES_ID.") AND TIMESTAMPDIFF(DAY, a.abandon_date, CURDATE()) <= 60) abandoneds ON resv_drvr.assigned_driver = abandoneds.assigned_driver ";

	/*$sSQL			=	"SELECT resv_drvr.driver_name, resv_drvr.asgnd_driver_name, resv_drvr.overdue, CASE WHEN abandoneds.res_id IS NULL THEN 'NO ABANDONED RESERVATION FOUND' ELSE abandoneds.res_id END AS abandoned_resv FROM  ";
	
	$sSQL			.=	"(SELECT CONCAT(u.f_name,' ',u.l_name) AS driver_name, CONCAT(asgned_drvr.f_name,' ',asgned_drvr.l_name) AS asgnd_driver_name, TIMESTAMPDIFF(DAY, r.planned_return_day_time, CURDATE()) AS overdue, r.user_id ".
	"FROM tbl_reservations r INNER JOIN tbl_user u ON r.user_id = u.user_id INNER JOIN tbl_user asgned_drvr ON r.assigned_driver = asgned_drvr.user_id WHERE r.res_id = ".$iRES_ID.") resv_drvr ";
	
	
	$sSQL			.=	"LEFT OUTER JOIN ";
	$sSQL			.=	"(SELECT a.res_id, r.user_id FROM tbl_abandon_trips a INNER JOIN tbl_reservations r ON a.res_id = r.res_id WHERE r.user_id = (SELECT user_id FROM tbl_reservations r WHERE r.res_id = ".$iRES_ID.") AND TIMESTAMPDIFF(DAY, a.abandon_date, CURDATE()) <= 60) abandoneds ON resv_drvr.user_id = abandoneds.user_id ";*/

	
	$rsRESRVD_BY	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRESRVD_BY)>0){
		while($rowRESRVD_BY=mysql_fetch_array($rsRESRVD_BY)){
			$sDRIVER_NAME		=	$rowRESRVD_BY['driver_name'];
			$sASSGINED_DRIVER	=	$rowRESRVD_BY['asgnd_driver_name'];
			$sOVER_DUE			=	$rowRESRVD_BY['overdue'];
			$sDEPT_NAME			=	$rowRESRVD_BY['dept_name'];
			$sTRIP_STRING		.=	$rowRESRVD_BY['abandoned_resv'].",";
		}
		//list($sDRIVER_NAME, $sOVER_DUE)	=	mysql_fetch_row($rsRESRVD_BY);
		//$sTRIP_STRING	=	"driver=".$sDRIVER_NAME."overdue=".$sOVER_DUE;
		$sTRIP_STRING	=	"driver=".$sDRIVER_NAME."asgnddrvr=".$sASSGINED_DRIVER."dept=".$sDEPT_NAME."overdue=".$sOVER_DUE."abandoned=".$sTRIP_STRING;
		echo $sTRIP_STRING;
	}else{
		echo "ERROR";
	}mysql_free_result($rsRESRVD_BY);
}
if ($sACTION == "temp-service"){

		$sSTART_DATE	=	substr(str_replace(' pm',':00',str_replace(' am',':00',$_REQUEST["txtstartdate"])),strpos($_REQUEST["txtstartdate"],' ')+1);
		$sSTART_DATE	=	fn_DATE_TO_MYSQL($_REQUEST["txtstartdate"])." ".$sSTART_DATE;
		$sEND_DATE		=	fn_DATE_TO_MYSQL($_REQUEST["txtenddate"])." ".$_REQUEST["drptime2"].":00";		
		$sMessage			=	fn_TEMP_SERVICE('ajax', $_REQUEST["drpvehicle"], $sSTART_DATE, $sEND_DATE, $_SESSION["User_ID"], $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
		
		if($sMessage		==	"future"){
			$sMessage		=		"<li>Vehicle can only be pulled for future time";
		}elseif($sMessage	==	"overlimit"){
			$sMessage		=		"<li>Temporary removal must be for less than 2 weeks";
		}elseif($sMessage	==	"already"){
			$sMessage		=		"<li>Vehicle is already been pulled for service";
		}
		echo $sMessage;
}

if ($sACTION == "permanent"){
	if(fn_PERMENENT_PULL($_REQUEST["drpvehicle"])=='free'){
		echo fn_SERVICE_RESERVATION('ajax', 'permanent', $_REQUEST["drpvehicle"], '', '', $_SESSION["User_ID"], $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
	}else{
		$sMessage	=	"<li>Vehicle is already been pulled for service";
		echo $sMessage;
	}
}

if ($sACTION == "restore-vehicle"){

	$sTEMP_PULLED_VEHICLES	=	"";	$sRESTORE_TYPE	=	"";		$sPULLED_TYPE	=	"";		$sOPTION_TXT	=	"";
	$sRESTORE_TYPE	=	$_REQUEST["restore_type"];

	if($sRESTORE_TYPE=="restore-temp-pull"){	$sPULLED_TYPE	=	"temporary";	$sOPTION_TXT	=	" Temporary Pulled ";	}else{$sPULLED_TYPE	=	"permanent";	$sOPTION_TXT	=	" Permanent Pulled ";}
	//echo "adsfasdsdafasdf";
	$sSQL	=	"SELECT pv.srvc_id, pv.reg_date, v.vehicle_id, v.vehicle_no FROM tbl_vehicles v INNER JOIN ".
	"(SELECT MAX(s.srvc_id) AS srvc_id, s.reg_date, s.vehicle_id FROM tbl_srvc_resvs s WHERE s.is_cancelled = 0 AND service_type = '".$sPULLED_TYPE."' GROUP BY s.reg_date, s.vehicle_id) pv ".
	"ON v.vehicle_id = pv.vehicle_id WHERE v.sold = 0 ORDER BY (vehicle_no+0)";
	$rsTEMP_PULLED_VEH	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsTEMP_PULLED_VEH)>0){
		$sTEMP_PULLED_VEHICLES	=	"<select name='drpservc' size='1' style='width:350px;'>";
		$sTEMP_PULLED_VEHICLES	.=	"<option value=''>--Select".$sOPTION_TXT."Vehicle--</option>";
		while($rowSRVC	=	mysql_fetch_array($rsTEMP_PULLED_VEH)){
			$sTEMP_PULLED_VEHICLES	.= "<option value=".$rowSRVC['srvc_id'].">V #&nbsp;".$rowSRVC['vehicle_no']."&nbsp;Pulled On:&nbsp;".fn_cDateMySql($rowSRVC['reg_date'],2)."</option>";
		}
		$sTEMP_PULLED_VEHICLES	.= "</select>";
	}else{
		$sTEMP_PULLED_VEHICLES	=	"<li>No $sPULLED_TYPE pulled Vehicle found";
	}mysql_free_result($rsTEMP_PULLED_VEH);
	echo $sTEMP_PULLED_VEHICLES;
}

if ($sACTION == "captcha"){
	
	/*require_once('recaptchalib.php');
     
			  $resp = recaptcha_check_answer ($privatekey,
											  $_SERVER["REMOTE_ADDR"],
											  $_REQUEST["recaptcha_challenge_field"],
											  $_REQUEST["recaptcha_response_field"]);*/
	if(strtolower($_REQUEST["captcha"])!=strtolower($_SESSION['securimage_code_value'])) {
		echo "ERROR";
	}else{
		echo "SUCCESS";
	}
	
	
	
	//echo "ERROR";

}


if ($sACTION == "close-box"){	echo fn_CLOSE_PENDING_TRIPS();}
if ($sACTION == "abandon-box"){	echo fn_ABANDON_PENDING_TRIPS();}

if ($sACTION == "update-ogm"){
	$iOGM_ID		=	$_REQUEST["msgid"];
	$sEMAIL_TEXT	=	$_REQUEST["txtemail"];
	
	$sSQL	=	"UPDATE tbl_info_links SET link_text = '".addslashes($sEMAIL_TEXT)."' WHERE link_id = ".$iOGM_ID;
	//echo $sSQL;
	$rsUPDATE_OGM	=	mysql_query($sSQL) or die(mysql_error());
	if($rsUPDATE_OGM){		echo "SUCCESS";	}else{echo "ERROR";}
}


if ($sACTION == "find-resv"){
	$iRESV_ID		=	$_REQUEST["resid"];
	
	$sSQL	=	"SELECT ".
	"CASE WHEN td.res_id IS NULL THEN ".
	"		CASE WHEN a.res_id IS NULL THEN ".
	"				CASE WHEN r.cancelled_by_driver = 0 THEN ".
	"					CASE WHEN r.reservation_cancelled = 0 THEN 'PENDING' ELSE 'DELETED' END ".
	"				ELSE	'CANCEL'	END ".
	"		ELSE 'ABANDONED'	END ".
	"ELSE 'CLOSED'	END AS trip_status ".
	"FROM tbl_reservations r LEFT OUTER JOIN tbl_trip_details td ON r.res_id = td.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
	"WHERE r.res_id = ".$iRESV_ID;
	//echo $sSQL;
	$rsRESV_STATUS		=	mysql_query($sSQL) or die(mysql_error());
	
	if(mysql_num_rows($rsRESV_STATUS)>0){
		//echo $rsRESV_STATUS[0]['trip_status'];
		$rowSTATUS	=	mysql_fetch_array($rsRESV_STATUS);
		echo $rowSTATUS['trip_status'];
		
	}else{
		echo "ERROR";
	}
}

?>