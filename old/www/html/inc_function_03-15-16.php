<?
$sCOMPANY_Name	=	"University of the Nations";
$sCOMPANY_Link	=	"http://transportation.uofnkona.edu/";
$sSUPPORT_EMAIL	=	"transportation@uofnkona.edu"; //"helpdesk@uofnkona.edu";
$sCOMPANY_SMTP	=	"prux.uofnkona.edu";

//date_default_timezone_set('Pacific/Honolulu');
//date_default_timezone_set('Asia/Tashkent');


/*************** reCAPTCHA KEYS****************/
$publickey = "6LcKltwSAAAAAEm5V3Fr3k8spoMlKn2J8uVZA8Kv";
$privatekey = "6LcKltwSAAAAANti36SpDxDx54YvF0HlWzTdFrOC";


$iGROUP_TM					=	1;
$iGROUP_TC					=	2;
$iGROUP_DRIVER				=	3;
$iGROUP_SERVICETCH			=	4;
$iGROUP_COORDINATOR_STAFF	=	5;


//==============================OPERATIONS=======================================
//*************USERS***************
 $iOPT_USER_ADD			=	1;
 $iOPT_USER_DELETE		=	2;
 $iOPT_USER_SEARCH		=	3;
 $iOPT_TM_USER_SEARCH	=	4;
 $iOPT_USER_MODIFY		=	5;
 $iOPT_USER_ACTIVATE	=	6;
 $iOPT_USER_DE_ACTIVATE	=	7;
 $iOPT_TM_USER_MODIFY	=	8;
 $iOPT_USER_EMAIL		=	9;

//************RESERVATIONS********
 $iOPT_RES_ADD			=	10;
 $iOPT_RES_DELETE		=	11;
 $iOPT_RES_SEARCH		=	12;
 $iOPT_RES_APPROVAL		=	13;
 $iOPT_RES_CANCELLATION	=	14;
 
 //************TRIPS********
$iOPT_TRIP_ADD			=	15;
$iOPT_TRIP_SEARCH		=	16;
$iOPT_TRIP_EDIT			=	17;
$iOPT_TRIP_DELETE		=	18;
$iOPT_DELETE_OLD_TRIPS	=	19;

//*************ABANDONED TRIPS*******
$iOPT_ABANDON_TRIP_ADD		=	20;
$iOPT_ABANDON_TRIP_SEARCH	=	21;
//************SHOP WORK********
 $iOPT_SHOP_ADD			=	22;
 $iOPT_SHOP_DELETE		=	23;
 $iOPT_SHOP_SEARCH		=	24;
 $iOPT_SHOP_MODIFY		=	25;

//************COMMENTS********
 $iOPT_USER_COMMENT_ADD	=	26;
 $iOPT_USER_COMMENT_DELETE	=	27;
 $iOPT_USER_COMMENT_SEARCH	=	28;
 $iOPT_USER_COMMENT_MODIFY	=	29;

//************COST************
 $iOPT_COST_ADD			=	30;
 $iOPT_COST_DELETE		=	31;
 $iOPT_COST_SEARCH		=	32;
 $iOPT_COST_MODIFY		=	33;

//************VEHICLES********
 $iOPT_VEHICLES_ADD					=	34;
 $iOPT_VEHICLES_DELETE				=	35;
 $iOPT_VEHICLES_SEARCH				=	36;
 $iOPT_VEHICLES_MODIFY				=	37;
 $iOPT_VEHICLES_ISSUES				=	38;			//VEHICLE ISSUES REPORT BASED ON LIST VEHICLES
 $iOPT_VEHICLES_ISSUE_MODIFY		=	39;
 $iOPT_RESTRICTED_VEHICLE_CHARGES	=	40;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH	=	41;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY	=	42;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE	=	43;
 $iOPT_SELL_VEHICLE					=	44;
 $iOPT_VEHICLE_LIMIT_OVERRIDE		=	45;
 $iOPT_VEHICLE_LIMIT_OVERRIDE_SEARCH=	46;
 
 
 //************DEPARTMENTS********
 $iOPT_DEPARTMENT_ADD				=	47;
 $iOPT_DEPARTMENT_DELETE			=	48;
 $iOPT_DEPARTMENT_SEARCH			=	49;
 $iOPT_DEPARTMENT_MODIFY			=	50;
 $iOPT_DEPARTMENT_STATUS			=	51;
 
 //*********REPORTS********
 
 $iOPT_DRIVER_MILEAGE				=	52;
 $iOPT_DEPARTMENT_COST				=	53;
 $iOPT_VEHICLE_COST					=	54;
 $iOPT_VEHICLE_R_M_TASK				=	55;
 $iOPT_VEHICLE_MILEAGE				=	56;
 $iOPT_PENDING_TRIP					=	57;
 $iOPT_GRAPH_PENDING_TRIPS			=	58;
 $iOPT_SCHOOL_COST					=	59;
 $iOPT_INSPECT_DUE					=	60;
 $iOPT_DELETED_TRIPS				=	61;
 $iOPT_TM_START_REPORT				=	62;
 //****************RESERVATIONS----CHARGE DEPT***********************
 $iOPT_DRIVER_DELETED_RESERVATION	=	63;
 $iOPT_EDIT_RESERVATION				=	64;
 $iOPT_CHANGE_CHARGE_DEPT			=	65;
 
 //*********CHECK VEHICLE RESERVATIONS*********************
$iOPT_CHECK_RESERVATIONS			=	66;
//*************************DRIVERS OPERATIONS********************
$iOPT_DEACTIVATE_DRIVERS			=	67;
$iOPT_DELETE_DRIVERS				=	68;
$iOPT_ACTIVE_DRIVERS				=	69;

//***********************INFO LINKS AND NOTICES************************
$iOPT_LINK_ADD						=	70;
$iOPT_LINK_DELETE					=	71;
$iOPT_LINK_SEARCH					=	72;
$iOPT_LINK_MODIFY					=	73;
$iOPT_NOTICE_ADD					=	74;
$iOPT_AIR_PORT_PARKING_LINK			=	75;
$iOPT_LEADER_MSG_LINK				=	76;


//===============SERVICE RESERVATION MESSAGES=======================
$iOPT_SERVICE_RESERVATION_MSG		=	77;
$iOPT_SERVICE_RESERVATION_CANCEL_MSG=	78;

//******************************BACKUPS************************

$iOPT_BACKUP						=	79;
$iOPT_BACKUP_SEARCH					=	80;
$iOPT_RESTORE						=	81;
$iOPT_BACKUP_DELETE					=	82;

//**************************LIST IP LOG*********************
$iOPT_LOG_SEARCH					=	83;
$iOPT_LOG_DELETE					=	84;

//************************LEADERS****************************
$iOPT_DRIVER_SPONSOR				=	85;




function fn_DEPARTMENT($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sOPTIONAL_SQL	=	""){
global $iGROUP_TC;
	if(isset($_SESSION["User_Group"]) && $_SESSION["User_Group"]==$iGROUP_TC){
		$sSQL	=	"SELECT dept_id, CASE WHEN active = 0 THEN CONCAT(dept_name,' ','==IN-ACTIVE==') ELSE dept_name END AS dept_name FROM tbl_departments ORDER BY dept_name";
	}else{
		$sSQL	=	"SELECT dept_id, dept_name FROM tbl_departments WHERE active = 1 ORDER BY dept_name";
	}
	if($sOPTIONAL_SQL!="")	$sSQL	=	$sOPTIONAL_SQL;
	
	$rsDEPT	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsDEPT)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowDEPT	=	mysql_fetch_array($rsDEPT)){	?>
		<option value="<?=$rowDEPT['dept_id']?>" <? if($rowDEPT['dept_id']==$sSELECTED) echo "selected";?>><?=$rowDEPT['dept_id']."&nbsp;".$rowDEPT['dept_name']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no departments are defined in the system", "C_ERROR");
	}mysql_free_result($rsDEPT);
}

function fn_USER_GROUP($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sON_CHANGE_EVT = ""){
	$sSQL	=	"SELECT group_id, group_name FROM tbl_user_group ORDER BY group_name";
	$rsGROUP	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsGROUP)>0){
	
?>
	<select name="<?=$sNAME?>" id="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?Php echo $sON_CHANGE_EVT;?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowGROUP	=	mysql_fetch_array($rsGROUP)){	?>
		<option value="<?=$rowGROUP['group_id']?>" <? if($rowGROUP['group_id']==$sSELECTED) echo "selected";?>><?=$rowGROUP['group_name']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no user group are defined in the system", "C_ERROR");
	}mysql_free_result($rsGROUP);
}

function fn_USER_TYPE($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sCALL_PAGE	=	""){
	
	$iTypeCounter	=	0;
	if($sCALL_PAGE==""){$iTypeCounter	=	3;}elseif($sCALL_PAGE=="edit_user"){$iTypeCounter	=	4;}
	
	$arrUSER_TYPE[0]	=	"Student";
	$arrUSER_TYPE[1]	=	"Staff";
	$arrUSER_TYPE[2]	=	"Mission Bldr.";
	$arrUSER_TYPE[3]	=	"Other";
	//$arrUSER_TYPE[4]	=	"Coordinator-Staff";
	
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		for($iCOUNTER=0;$iCOUNTER<=$iTypeCounter;$iCOUNTER++){?>
		<option value="<?=$arrUSER_TYPE[$iCOUNTER]?>" <? if($arrUSER_TYPE[$iCOUNTER]==$sSELECTED) echo "selected";?>><?=$arrUSER_TYPE[$iCOUNTER]?></option>
<?		}?>
	</select>
<?
}

function fn_VEHICLE_MAKE($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$sSQL	=	"SELECT brand_id, brand_name FROM tbl_vehicle_brand ORDER BY brand_name";
	$rsBRAND	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsBRAND)>0){
	
?>
	<select id="<?=$sNAME?>" name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowBRAND	=	mysql_fetch_array($rsBRAND)){	?>
		<option value="<?=$rowBRAND['brand_id']?>" <? if($rowBRAND['brand_id']==$sSELECTED) echo "selected";?>><?=$rowBRAND['brand_name']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicle makers are defined in the system", "C_ERROR");
	}mysql_free_result($rsBRAND);
}


function fn_VEHICLE_TYPE($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$sSQL	=	"SELECT v_type_id, v_type, capacity FROM tbl_vehicle_type";
	$rsV_TYPE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsV_TYPE)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowV_TYPE	=	mysql_fetch_array($rsV_TYPE)){	?>
		<!--<option value="<?=$rowV_TYPE['v_type_id']?>" <? if($rowV_TYPE['v_type_id']==$sSELECTED) echo "selected";?>><?=$rowV_TYPE['v_type']."-Cap&nbsp;".$rowV_TYPE['capacity'];?></option>-->
		<option value="<?=$rowV_TYPE['v_type_id']?>" <? if($rowV_TYPE['v_type_id']==$sSELECTED) echo "selected";?>><?=$rowV_TYPE['v_type'];?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicle modal are defined in the system", "C_ERROR");
	}mysql_free_result($rsV_TYPE);
}


function fn_WORK_TYPE($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$sSQL	=	"SELECT work_type_id, work_type FROM tbl_work_type ORDER BY work_type";
	$rsWORKTYPE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsWORKTYPE)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowWORKTYPE	=	mysql_fetch_array($rsWORKTYPE)){	?>
		<option value="<?=$rowWORKTYPE['work_type_id']?>" <? if($rowWORKTYPE['work_type_id']==$sSELECTED) echo "selected";?>><?=$rowWORKTYPE['work_type']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no work type is defined in the system", "C_ERROR");
	}mysql_free_result($rsWORKTYPE);
}

function fn_VEHICLE($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sOnChangeEvent=''){
	/*$sSQL	=	"SELECT vehicle_id, vehicle_no, brand_name, year_manuf ".
	"FROM tbl_vehicles INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"ORDER BY vehicle_no";*/
	$sSQL	=	"SELECT v.vehicle_id, v.vehicle_no, CASE WHEN pv.vehicle_id IS NULL THEN 'free' ELSE 'pulled' END AS status FROM tbl_vehicles v LEFT OUTER JOIN ".
	"(SELECT vehicle_id FROM tbl_srvc_resvs s WHERE s.is_cancelled = 0) pv ON v.vehicle_id = pv.vehicle_id ".
	"WHERE v.sold = 0 ORDER BY (vehicle_no+0)";
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE)>0){
	$sUNAVAILABLE	=	"";
	
?>
	<select id="<?=$sNAME?>" name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?=$sOnChangeEvent?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){	
			if($rowVEHICLE['status']=='pulled') $sUNAVAILABLE	=	 "class='unavailable'"; else	$sUNAVAILABLE	=	"";
?>
		<option value="<?=$rowVEHICLE['vehicle_id']?>" <? echo $sUNAVAILABLE; if($rowVEHICLE['vehicle_id']==$sSELECTED) echo "selected";?>><?=$rowVEHICLE['vehicle_no']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicles are defined in the system", "C_ERROR");
	}mysql_free_result($rsVEHICLE);
}

function fn_RESTRICTED_VEHICLES($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sOnChangeEvent=''){
	/*$sSQL	=	"SELECT vehicle_id, vehicle_no, brand_name, year_manuf ".
	"FROM tbl_vehicles INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"WHERE tbl_vehicles.restricted = 0 ORDER BY vehicle_no";*/
	$sSQL	=	"SELECT s.vehicle_id, v.vehicle_no FROM tbl_srvc_resvs s INNER JOIN tbl_vehicles v ON s.vehicle_id = v.vehicle_id GROUP BY v.vehicle_id";
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE)>0){
?>	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?=$sOnChangeEvent?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){	?>
		<option value="<?=$rowVEHICLE['vehicle_id']?>" <? if($rowVEHICLE['vehicle_id']==$sSELECTED) echo "selected";?>><?=$rowVEHICLE['vehicle_no']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicles are defined in the system", "C_ERROR");
	}mysql_free_result($rsVEHICLE);
}
function fn_VEHICLE_CAPACITY($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $bOnlyAvailable, $sOnChangeEvent=''){

	if($bOnlyAvailable)
		$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap, restricted FROM tbl_vehicles WHERE restricted = 1 AND sold = 0 ORDER BY vehicle_no";
	else
		$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap, restricted FROM tbl_vehicles WHERE sold = 0 ORDER BY vehicle_no";
	/*$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap, CASE WHEN restricted = 1 THEN 'Restricted' ELSE '' END AS restricted ".
	"FROM tbl_vehicles ORDER BY vehicle_no";*/
	
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE)>0){
	
?>
<style type="text/css">
	option.unavailable	{background-color:#a70000; color:#fff;}
	
</style>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?=$sOnChangeEvent?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){	?>
		<option value="<?=$rowVEHICLE['vehicle_id']?>" <? if(intval($rowVEHICLE['vehicle_id'])==intval($sSELECTED)) {echo "selected ";} if($rowVEHICLE['restricted']==0) echo "class='unavailable'";?>><?="NO:&nbsp;".$rowVEHICLE['vehicle_no']."&nbsp;-&nbsp;CAPACITY:&nbsp;".$rowVEHICLE['passenger_cap']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicles are defined in the system", "C_ERROR");
	}mysql_free_result($rsVEHICLE);
}

function fn_CONDITION_TECH($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){

	$arrCONDITION[0]	=	"EXCELLENT";
	$arrCONDITION[1]	=	"GOOD";
	$arrCONDITION[2]	=	"FAIR";
	$arrCONDITION[3]	=	"POOR";
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		for($iCOUNTER=0;$iCOUNTER<=3;$iCOUNTER++){?>
		<option value="<?=$arrCONDITION[$iCOUNTER]?>" <? if($arrCONDITION[$iCOUNTER]==$sSELECTED) echo "selected";?>><?=$arrCONDITION[$iCOUNTER]?></option>
<?		}?>
	</select>
<?
}

function fn_COORD_STATUS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$arrSTATUS[0]	=	"Pending";
	$arrSTATUS[1]	=	"Approved";
	$arrSTATUS[2]	=	"Disapproved";
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
		<?	for($iCOUNTER=0;$iCOUNTER<=2;$iCOUNTER++){?>
			<option value="<?=$arrSTATUS[$iCOUNTER]?>" <? if($arrSTATUS[$iCOUNTER]==$sSELECTED) echo "selected";?>><?=$arrSTATUS[$iCOUNTER]?></option>
		<?	}?>
	</select>
<?
}

function fn_MONTHS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
		<?	for($iCOUNTER=1;$iCOUNTER<=12;$iCOUNTER++){?>
			<option value="<?=$iCOUNTER?>" <? if($iCOUNTER==$sSELECTED) echo "selected";?>><?=fn_MONTH_NAME($iCOUNTER)?></option>
		<?	}?>
	</select>
<?
}

function fn_YEARS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
		<?	for($iCOUNTER=2011;$iCOUNTER<=2020;$iCOUNTER++){?>
			<option value="<?=$iCOUNTER?>" <? if($iCOUNTER==$sSELECTED) echo "selected";?>><?=$iCOUNTER?></option>
		<?	}?>
	</select>
<?
}

function fn_DAYS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sCALLINGPAGE = ""){
	
	$iLength	=	2;
	$arrDAYS[0][0]	=	"30";	$arrDAYS[0][1]	=	"Last 30 Days";
	$arrDAYS[1][0]	=	"60";	$arrDAYS[1][1]	=	"Last 60 Days";
	if($sCALLINGPAGE == ""){
		
		$arrDAYS[2][0]	=	"90";	$arrDAYS[2][1]	=	"Last 90 Days";
	}
	if($sCALLINGPAGE == "list_trips" || $sCALLINGPAGE == "list_pendings"){
		$iLength	=	7;
		$arrDAYS[2][0]	=	"14";	$arrDAYS[2][1]	=	"Last 14 Days";
		$arrDAYS[3][0]	=	"-4";	$arrDAYS[3][1]	=	"Next 4 Days";
		$arrDAYS[4][0]	=	"-14";	$arrDAYS[4][1]	=	"Next 14 Days";
		$arrDAYS[5][0]	=	"-21";	$arrDAYS[5][1]	=	"Next 21 Days";
		$arrDAYS[6][0]	=	"0";	$arrDAYS[6][1]	=	"today";
		$arrDAYS[7][0]	=	"-1";	$arrDAYS[7][1]	=	"tomorrow";
	}
?>
<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
	<option value=""><?=$sOPTIONAL?></option>
	<? 	for($iCounter=0;$iCounter<=$iLength;$iCounter++){?>
		<option value="<? echo $arrDAYS[$iCounter][0];?>" <? if($sSELECTED == $arrDAYS[$iCounter][0]) echo "selected";?>><? echo $arrDAYS[$iCounter][1];?></option>
	<?	}?>
</select>
<?
}

function fn_REPEATING_DROP($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){

$arrREPEATING[0][0]		=	"1"; 	$arrREPEATING[0][1]		=	"Repeating";
$arrREPEATING[1][0]		=	"0"; 	$arrREPEATING[1][1]		=	"Non-Repeating";
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value="" selected>--All--</option>
		<?	for($iCounter	=	0;$iCounter<=1;$iCounter++){?>
		<option value="<? echo $arrREPEATING[$iCounter][0]?>" <? if($sSELECTED==$arrREPEATING[$iCounter][0]) echo "selected";?>><? echo $arrREPEATING[$iCounter][1]?></option>
		<?	}?>
	</select>
<?
}
function fn_Print_MSG_BOX($sMessage, $sBOXType){
	if($sBOXType	==	"C_ERROR"){
	return "<table width='100%'><tr><td class='Err' width='100%'>$sMessage</td></tr></table>";
	}elseif($sBOXType	==	"C_SUCCESS"){
	return "<table width='100%'><tr><td class='Success' width='100%'>$sMessage</td></tr></table>";
	}
}

function fn_DISPLAY_USERS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sFIELDS, $sORDERBY, $sTYPE="", $sONCHANGE="", $sWHERE_CLAUSE=""){
	$sTYPE_SQL	=	"";
	if($sTYPE!="") 				$sTYPE_SQL	=	" AND tbl_user.user_group IN (".$sTYPE.")";
	if($sWHERE_CLAUSE!="")		$sTYPE_SQL	.=	" AND ".$sWHERE_CLAUSE;
	//$sSQL	=	"SELECT user_id, ".$sFIELDS.", CASE WHEN active = 0 THEN '===IN-ACTIVE==' ELSE '' END AS status FROM tbl_user WHERE 1=1 ".$sTYPE_SQL." ORDER BY ".$sORDERBY;
	$sSQL	=	"SELECT user_id, ".$sFIELDS.", active AS status FROM tbl_user WHERE 1=1 ".$sTYPE_SQL." ORDER BY ".$sORDERBY;
	//print($sSQL);
	
	$rsUSER	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsUSER)>0){
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?Php echo $sONCHANGE;?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowUSER	=	mysql_fetch_array($rsUSER)){	?>
        <option value="<?=$rowUSER['user_id']?>" <? if ($rowUSER['status'] == 0) { echo 'class="unavailable"';}?>><?=$rowUSER[1]?></option>
		<!--option value="<?=$rowUSER['user_id']?>" <? if($rowUSER['user_id']==$sSELECTED) echo "selected";?>><? echo $rowUSER[1]." ".$rowUSER['status']?></option-->
<?		}?>
	</select>
<?
	}mysql_free_result($rsUSER);
}
function fn_generatePassword ($length = 10){

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ!$#@^&";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }


function GET_MAX_ID($sTableName, $sFieldName){
	$iMaxID = 0;
	//retrieve last/max ID
	$sSQL="select case when max($sFieldName) is null then 1 else max($sFieldName)+1 end max_id from $sTableName";
	$rsMaxID=mysql_query($sSQL) or die(mysql_error());
	$MaxIDRow=mysql_fetch_array($rsMaxID) or die (mysql_error());
	
	$iMaxID=$MaxIDRow['max_id'];
	
	return $iMaxID;
}

function fn_GET_FIELD($sTableName, $iProvidedValue, $sProvidedField, $sGetField){
	$sSQL	=	"SELECT $sGetField FROM $sTableName WHERE $sProvidedField =	$iProvidedValue";
	//print($sSQL);
	$rsFIELD	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsFIELD)>0){
		$rowFIELD	=	mysql_fetch_array($rsFIELD);
		return $rowFIELD[$sGetField];
	}else
		return "false";
}


function fn_GET_FIELD_BY_QUERY($sSQL){
	$rsSINGLE_FIELD	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsSINGLE_FIELD)>0){
		return mysql_result($rsSINGLE_FIELD,0);
	}mysql_free_result($rsSINGLE_FIELD);
}

function fn_USER_PERMISSIONS_TABLE($iLOGGED_USER_ID, $iOPERATION){


//===================================USER GROUP LEVELS=====================================
global $iGROUP_TM,	$iGROUP_TC, $iGROUP_DRIVER, $iGROUP_SERVICETCH, $iGROUP_COORDINATOR_STAFF, $iGROUP_DEPT_LEADER;

//==============================OPERATIONS=======================================
//*************USERS***************
global $iOPT_USER_ADD, $iOPT_USER_DELETE, $iOPT_USER_SEARCH, $iOPT_TM_USER_SEARCH, $iOPT_USER_MODIFY,  $iOPT_USER_ACTIVATE, $iOPT_USER_DE_ACTIVATE, $iOPT_TM_USER_MODIFY, $iOPT_USER_EMAIL;
//************RESERVATIONS********
global $iOPT_RES_ADD, $iOPT_RES_DELETE, $iOPT_RES_SEARCH, $iOPT_RES_MODIFY, $iOPT_RES_APPROVAL, $iOPT_RES_CANCELLATION;
//************TRIPS********
global $iOPT_TRIP_ADD, $iOPT_TRIP_SEARCH, $iOPT_TRIP_EDIT, $iOPT_DELETE_OLD_TRIPS;
//************ABANDON TRIPS*********************************
global $iOPT_ABANDON_TRIP_ADD, $iOPT_ABANDON_TRIP_SEARCH, $iOPT_TRIP_DELETE;
//************SHOP WORK********
global $iOPT_SHOP_ADD, $iOPT_SHOP_DELETE, $iOPT_SHOP_SEARCH, $iOPT_SHOP_MODIFY;
//************COMMENTS********
global $iOPT_USER_COMMENT_ADD, $iOPT_USER_COMMENT_DELETE, $iOPT_USER_COMMENT_SEARCH, $iOPT_USER_COMMENT_MODIFY;
//************COST************
global $iOPT_COST_ADD, $iOPT_COST_DELETE, $iOPT_COST_SEARCH, $iOPT_COST_MODIFY;
//************VEHICLES********
global $iOPT_VEHICLES_ADD, $iOPT_VEHICLES_DELETE, $iOPT_VEHICLES_SEARCH, $iOPT_VEHICLES_MODIFY, $iOPT_VEHICLES_ISSUES, $iOPT_VEHICLES_ISSUE_MODIFY, $iOPT_SELL_VEHICLE, $iOPT_VEHICLE_LIMIT_OVERRIDE, $iOPT_VEHICLE_LIMIT_OVERRIDE_SEARCH;
//************RESTRICTED VEHICLES***************************
global $iOPT_RESTRICTED_VEHICLE_CHARGES,  $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH, $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY, $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE;
//************DEPARTMENT********
global $iOPT_DEPARTMENT_ADD, $iOPT_DEPARTMENT_DELETE, $iOPT_DEPARTMENT_SEARCH, $iOPT_DEPARTMENT_MODIFY, $iOPT_DEPARTMENT_STATUS;
//************REPORTS********
global $iOPT_DRIVER_MILEAGE, $iOPT_DEPARTMENT_COST, $iOPT_VEHICLE_COST, $iOPT_VEHICLE_R_M_TASK, $iOPT_VEHICLE_MILEAGE;
global $iOPT_PENDING_TRIP, $iOPT_GRAPH_PENDING_TRIPS, $iOPT_SCHOOL_COST, $iOPT_INSPECT_DUE, $iOPT_DELETED_TRIPS, $iOPT_TM_START_REPORT;
//************RESERVATIONS------CHAGE CHARGE DEPT*************************
global $iOPT_DRIVER_DELETED_RESERVATION, $iOPT_EDIT_RESERVATION, $iOPT_CHANGE_CHARGE_DEPT;

//**********CHECK VEHICLE RESERVATIONS**************************
global $iOPT_CHECK_RESERVATIONS;
//**************DRIVERS OPERATIONS******************************
global $iOPT_DEACTIVATE_DRIVERS, $iOPT_ACTIVE_DRIVERS, $iOPT_DELETE_DRIVERS;
//**********************LINKI AND NOTICES*******************************
global $iOPT_LINK_ADD,	$iOPT_LINK_DELETE,	$iOPT_LINK_SEARCH,	$iOPT_LINK_MODIFY, $iOPT_NOTICE_ADD, $iOPT_AIR_PORT_PARKING_LINK, $iOPT_LEADER_MSG_LINK;
//**********************SERVICE RESERVATION MESSAGES*********************
global $iOPT_SERVICE_RESERVATION_MSG, $iOPT_SERVICE_RESERVATION_CANCEL_MSG;
//**********************BACKUP*******************************************************
global $iOPT_BACKUP, $iOPT_BACKUP_SEARCH, $iOPT_RESTORE, $iOPT_BACKUP_DELETE;
//************************IP LOG******************************************************
global $iOPT_LOG_SEARCH, $iOPT_LOG_DELETE;
//************************LEADER FUNCTIONS
global $iOPT_DRIVER_SPONSOR;


	$iLOGGED_USER_GROUP	=	fn_GET_FIELD("tbl_user", $iLOGGED_USER_ID, "user_id", "user_group");

	if($iLOGGED_USER_GROUP==$iGROUP_TM){
	
		switch($iOPERATION){
		
			case $iOPT_USER_ADD:
			//case $iOPT_USER_DELETE:
			case $iOPT_USER_ACTIVATE:
			case $iOPT_USER_DE_ACTIVATE:
			case $iOPT_USER_SEARCH:
			case $iOPT_USER_MODIFY:
			case $iOPT_USER_EMAIL:		//change user email
			case $iOPT_RES_ADD:
			case $iOPT_RES_DELETE:
			case $iOPT_RES_SEARCH:
			case $iOPT_RES_MODIFY:
			case $iOPT_TRIP_EDIT:
			case $iOPT_TRIP_SEARCH:
			case $iOPT_TRIP_DELETE:
			case $iOPT_ABANDON_TRIP_ADD:
			case $iOPT_ABANDON_TRIP_SEARCH:
			case $iOPT_CHANGE_CHARGE_DEPT:
			case $iOPT_SHOP_DELETE:			//can delete shop task
			case $iOPT_SHOP_SEARCH:
			case $iOPT_SHOP_MODIFY:		//can modify shop task
			case $iOPT_USER_COMMENT_ADD:
			case $iOPT_USER_COMMENT_DELETE:
			case $iOPT_USER_COMMENT_SEARCH:
			case $iOPT_USER_COMMENT_MODIFY:
			case $iOPT_COST_ADD:
			case $iOPT_COST_DELETE:
			case $iOPT_COST_SEARCH:
			case $iOPT_COST_MODIFY:
			case $iOPT_VEHICLES_ADD:
			case $iOPT_VEHICLES_DELETE:
			case $iOPT_VEHICLES_SEARCH:
			case $iOPT_VEHICLES_MODIFY:
			case $iOPT_VEHICLES_ISSUES:
			case $iOPT_VEHICLES_ISSUE_MODIFY:
			case $iOPT_VEHICLE_LIMIT_OVERRIDE:
			case $iOPT_VEHICLE_LIMIT_OVERRIDE_SEARCH:
			case $iOPT_DEPARTMENT_ADD:
			case $iOPT_DEPARTMENT_SEARCH:
			case $iOPT_DRIVER_MILEAGE:
			case $iOPT_INSPECT_DUE:
			case $iOPT_DELETED_TRIPS:
			case $iOPT_DEPARTMENT_COST:
			case $iOPT_VEHICLE_COST:
			case $iOPT_SCHOOL_COST:
			case $iOPT_GRAPH_PENDING_TRIPS:
			case $iOPT_VEHICLE_R_M_TASK:
			case $iOPT_VEHICLE_MILEAGE:
			case $iOPT_PENDING_TRIP:
			case $iOPT_CHECK_RESERVATIONS:
			case $iOPT_DEACTIVATE_DRIVERS:
			case $iOPT_ACTIVE_DRIVERS:
			case $iOPT_DRIVER_DELETED_RESERVATION:
			case $iOPT_EDIT_RESERVATION:
			case $iOPT_SERVICE_RESERVATION_MSG:
			case $iOPT_SERVICE_RESERVATION_CANCEL_MSG:
			case $iOPT_BACKUP:
			case $iOPT_BACKUP_SEARCH:
			case $iOPT_BACKUP_DELETE:
			case $iOPT_AIR_PORT_PARKING_LINK:
			case $iOPT_LEADER_MSG_LINK:
			case $iOPT_TM_START_REPORT:
				$bACCESS	=	true;
				break;
			case $iOPT_SHOP_ADD:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}
	
	
	if($iLOGGED_USER_GROUP==$iGROUP_TC){
	
		switch($iOPERATION){
						
			case $iOPT_USER_ACTIVATE:
			case $iOPT_USER_DE_ACTIVATE:
			case $iOPT_USER_ADD:
			case $iOPT_USER_SEARCH:
			case $iOPT_USER_MODIFY:
			case $iOPT_USER_EMAIL:		//change user email
			case $iOPT_USER_DELETE:
			case $iOPT_RES_ADD:
			case $iOPT_RES_DELETE:
			case $iOPT_RES_SEARCH:
			case $iOPT_RES_MODIFY:
			case $iOPT_TRIP_EDIT:
			case $iOPT_TRIP_SEARCH:
			case $iOPT_TRIP_DELETE:
			case $iOPT_DELETE_OLD_TRIPS:
			case $iOPT_ABANDON_TRIP_ADD:
			case $iOPT_ABANDON_TRIP_SEARCH:
			case $iOPT_CHANGE_CHARGE_DEPT:
			case $iOPT_SHOP_ADD:
			case $iOPT_SHOP_DELETE:			//can delete shop task
			case $iOPT_SHOP_SEARCH:
			case $iOPT_SHOP_MODIFY:		//can modify shop task
			case $iOPT_USER_COMMENT_ADD:
			case $iOPT_USER_COMMENT_DELETE:
			case $iOPT_USER_COMMENT_SEARCH:
			case $iOPT_USER_COMMENT_MODIFY:
			case $iOPT_COST_ADD:
			case $iOPT_COST_DELETE:
			case $iOPT_COST_SEARCH:
			case $iOPT_COST_MODIFY:
			case $iOPT_VEHICLES_ADD:
			case $iOPT_VEHICLES_DELETE:
			case $iOPT_VEHICLES_SEARCH:
			case $iOPT_VEHICLES_MODIFY:
			case $iOPT_VEHICLES_ISSUES:
			case $iOPT_VEHICLES_ISSUE_MODIFY:
			case $iOPT_VEHICLE_LIMIT_OVERRIDE:
			case $iOPT_VEHICLE_LIMIT_OVERRIDE_SEARCH:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE:
			case $iOPT_SELL_VEHICLE:
			case $iOPT_DEPARTMENT_ADD:
			case $iOPT_DEPARTMENT_DELETE:
			case $iOPT_DEPARTMENT_SEARCH:
			case $iOPT_DEPARTMENT_MODIFY:
			case $iOPT_DEPARTMENT_STATUS:
			//case $iOPT_DRIVER_MILEAGE:
			case $iOPT_DEPARTMENT_COST:
			case $iOPT_VEHICLE_COST:
			case $iOPT_SCHOOL_COST:
			case $iOPT_VEHICLE_R_M_TASK:
			case $iOPT_VEHICLE_MILEAGE:
			case $iOPT_PENDING_TRIP:
			case $iOPT_GRAPH_PENDING_TRIPS:
			case $iOPT_CHECK_RESERVATIONS:
			case $iOPT_DEACTIVATE_DRIVERS:
			case $iOPT_ACTIVE_DRIVERS:
			case $iOPT_INSPECT_DUE:
			case $iOPT_DELETED_TRIPS:
			case $iOPT_DELETE_DRIVERS:
			case $iOPT_DRIVER_DELETED_RESERVATION:
			case $iOPT_LINK_ADD:
			case $iOPT_LINK_DELETE:
			case $iOPT_LINK_SEARCH:
			case $iOPT_LINK_MODIFY:
			case $iOPT_AIR_PORT_PARKING_LINK:
			case $iOPT_LEADER_MSG_LINK:
			case $iOPT_NOTICE_ADD:
			case $iOPT_EDIT_RESERVATION:
			case $iOPT_SERVICE_RESERVATION_MSG:
			case $iOPT_SERVICE_RESERVATION_CANCEL_MSG:
			case $iOPT_BACKUP:
			case $iOPT_BACKUP_SEARCH:
			case $iOPT_RESTORE:
			case $iOPT_BACKUP_DELETE:
			case $iOPT_LOG_SEARCH:
			case $iOPT_LOG_DELETE:
			case $iOPT_TM_START_REPORT:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}
	
	
	if($iLOGGED_USER_GROUP==$iGROUP_DRIVER){
	
		switch($iOPERATION){
			case $iOPT_VEHICLES_SEARCH:
			case $iOPT_RES_ADD:
			case $iOPT_USER_COMMENT_ADD:
			case $iOPT_CHECK_RESERVATIONS:
			case $iOPT_RES_CANCELLATION:
			case $iOPT_GRAPH_PENDING_TRIPS:
			case $iOPT_USER_MODIFY:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}
	
	
	if($iLOGGED_USER_GROUP==$iGROUP_COORDINATOR_STAFF){
	
		switch($iOPERATION){
			case $iOPT_VEHICLES_SEARCH:
			case $iOPT_RES_ADD:
			case $iOPT_USER_COMMENT_ADD:
			case $iOPT_CHECK_RESERVATIONS:
			case $iOPT_RES_CANCELLATION:
			case $iOPT_GRAPH_PENDING_TRIPS:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}
	
	
	if($iLOGGED_USER_GROUP==$iGROUP_DEPT_LEADER){
	
		switch($iOPERATION){
			case $iOPT_DRIVER_SPONSOR:
			case $iOPT_SCHOOL_COST:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}

	if($iLOGGED_USER_GROUP==$iGROUP_SERVICETCH){
	
		switch($iOPERATION){
			case $iOPT_SHOP_ADD:
			case $iOPT_SHOP_SEARCH:
			case $iOPT_SHOP_MODIFY:
			case $iOPT_USER_COMMENT_ADD:
			case $iOPT_USER_COMMENT_SEARCH:
			case $iOPT_USER_COMMENT_MODIFY:
			case $iOPT_VEHICLES_SEARCH:
			case $iOPT_VEHICLES_ISSUE_MODIFY:
				$bACCESS	=	true;
				break;
			default:
				$bACCESS	=	false;
				break;
			
		} 
		
	}


	return $bACCESS;

}

function fn_DELETE_RECORD($sTableName, $sCriteriaField, $sFieldValue){

	$sSQL		=	"DELETE FROM ".$sTableName." WHERE ".$sCriteriaField." = ".$sFieldValue;
	$rsDELETE	=	mysql_query($sSQL) or die(mysql_error());
	
	if($rsDELETE)
		return true;
	else
		return false;
	
}

function fn_cDateMySql($mydate, $dateformat){

	if ($mydate){
		if ( $dateformat == 1 ){		//full date with format mm/dd/yyyy 		
	
			$years	=	substr($mydate, 0, 4);
			$months	=	substr($mydate, 5, 2);
			$days	=	substr($mydate, 8, 2);
		
			
			$newdate	=	 $months. "/" . $days . "/" . $years ;
		}
		
		if ( $dateformat == 2 ){		//full date with format mm/dd/yyyy 		
	
			$years	=	substr($mydate, 0, 4);
			$months	=	substr($mydate, 5, 2);
			$days	=	substr($mydate, 8, 2);
			$stime	=	substr($mydate, 11, 8);
		
			
			$newdate	=	 $months. "/" . $days . "/" . $years ." ".date('g:i a',strtotime($stime));
			
			
		}
		
		if ( $dateformat == 3 ){		//full date with format day name mm/dd/yyyy 		
	
			$years	=	substr($mydate, 2, 2);
			$months	=	substr($mydate, 5, 2);
			$days	=	substr($mydate, 8, 2);
		
			$newdate	=	date('D',strtotime($mydate));
			$newdate	=	$newdate."&nbsp;".	$months. "/" . $days . "/" . $years ;
		}
		
		if ( $dateformat == 4 ){		//full date with format day name mm/dd/yyyy 		
	
			$years	=	substr($mydate, 0, 4);
			$months	=	fn_MONTH_NAME(intval(substr($mydate, 5, 2)));
			$days	=	substr($mydate, 8, 2);
	
			$newdate	=	$days . "&nbsp;".	$months. 	"&nbsp;" .  $years ;
		}
		
		return $newdate;
	}
	else
	{
		return $mydate;
	}
}

function fn_DATE_TO_MYSQL($sDate){

	if($sDate){
		$months	=	substr($sDate, 0, 2);
		$days	=	substr($sDate, 3, 2);
		$years	=	substr($sDate, 6, 4);
	
		$sDate	=	$years . "-" . $months . "-" . $days;
		return $sDate;
	}else{
		return $sDate;
	}
}

function fn_MONTH_NAME($iMonth){
	switch ($iMonth){
		case "1":
		$monthname = "January";
		break;
		case "2":
		$monthname = "February";
		break;
		case "3":
		$monthname = "March";
		break;
		case "4":
		$monthname = "April";
		break;
		case "5":
		$monthname = "May";
		break;
		case "6":
		$monthname = "June";
		break;
		case "7":
		$monthname = "July";
		break;
		case "8":
		$monthname = "August";
		break;
		case "9":
		$monthname = "September";
		break;
		case "10":
		$monthname = "October";
		break;
		case "11":
		$monthname = "November";	
		break;
		case "12":
		$monthname = "December";
		break;
	}
	return $monthname;
}

//========================LEFT MENUS==================================

function fn_DISPLAY_INFO_LINKS($sLink_Path, $sPAGE_NAME	=	""){
	$sLINK_TEXTS	=	"";
	//if($sPAGE_NAME=="")
	//$sSQL	=	"SELECT link_id, link_title, link_order FROM tbl_info_links WHERE link_id NOT IN (12, 13, 14, 15, 16) ORDER BY link_order ASC";
	if($sPAGE_NAME!="")	$sSQL	=	"SELECT link_id, link_title, link_order FROM tbl_info_links WHERE link_display_page = '".$sPAGE_NAME."' ORDER BY link_order ASC";
	
	
	$rsLINKS		=mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsLINKS)>0){
		while($rowLINK	=	mysql_fetch_array($rsLINKS)){
		
			fn_INSERT_SIDE_MENU("info_page.php?id=".$rowLINK['link_id'], stripslashes($rowLINK['link_title']), $rowLINK['link_order']);
		
			//$sLINK_TEXTS	.=	 "<tr><td class='left_side_menu'><a href='".$sLink_Path."/info_page.php?id=".$rowLINK['link_id']."'>".stripslashes($rowLINK['link_title'])."</a></td></tr>";
		}
		//return $sLINK_TEXTS;
	}else{
		return fn_Print_MSG_BOX("no system message is found in the database", "C_ERROR");
	}
}

function fn_DISPLAY_SPECIAL_NOTICE($sNotice_Path){
	$sSQL	=	"SELECT notice_id, notice_title FROM tbl_special_notice WHERE notice_title <> '' AND notice <> ''";
	$rsSPECIAL_NOTICE		=mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsSPECIAL_NOTICE)>0){
		$sLINK_TEXTS	=	"<tr><td>&nbsp;</td></tr><tr><td class='left_side_menu' style='font-size:13pt;'>Special Notice</td></tr>";
		while($rowNOTICE	=	mysql_fetch_array($rsSPECIAL_NOTICE)){
			$sLINK_TEXTS	.=	 "<tr><td class='left_side_menu'><a href='".$sNotice_Path."/notice_page.php?id=".$rowNOTICE['notice_id']."'>".stripslashes($rowNOTICE['notice_title'])."</a></td></tr>";
		}
		return $sLINK_TEXTS;
	}mysql_free_result($rsSPECIAL_NOTICE);
}

function fn_INSERT_SIDE_MENU($sMenuLink, $sMenuName, $iMenuOrder, $sMenu_Class="left_side_menu"){
	$sSQL	=	"INSERT INTO tbl_side_menus VALUES('".session_id()."', '".$sMenuLink."', '".$sMenuName."', ".$iMenuOrder.", '".$sMenu_Class."')";
	//print($sSQL);
	$rsSIDE_MENU	=	mysql_query($sSQL) or die(mysql_error());
}

function fn_GET_SIDE_MENU(){
	$sSQL	=	"SELECT * FROM tbl_side_menus WHERE user_session = '".session_id()."' ORDER BY menu_order ";
	$rsSIDE_MENUS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsSIDE_MENUS)>0){return $rsSIDE_MENUS;}else return false;
}


/*function fn_DELETE_TEMP($ssession){
	//before leaving delete previous temp entries
	$sSQL	=	"DELETE FROM tbl_temp_reservations WHERE session = '".$ssession."'";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
}*/
function fn_CHECK_RESERVATION($ivehicleid, $sstartdate, $senddate, $ssession, $iResID = 0){
	
	$sResSQL	=	"";
	if($iResID!=0)	$sResSQL	=	" AND tbl_reservations.res_id <> ".$iResID;
	
	$sRESERVATIONS_ARRAY	=	array();
	
	$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
	"FROM tbl_reservations ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"INNER JOIN tbl_user assigned ON tbl_reservations.assigned_driver = assigned.user_id ".
	"INNER JOIN tbl_departments ON assigned.dept_id = tbl_departments.dept_id ".
	"WHERE vehicle_id = ".$ivehicleid." AND tbl_trip_details.res_id IS NULL AND tbl_abandon_trips.res_id IS NULL AND ".
	"(DATE(planned_depart_day_time) >= '".$sstartdate."' OR DATE( planned_return_day_time ) >= '".$sstartdate."') AND ".
	"(DATE(planned_depart_day_time) <= '".$senddate."' OR DATE(planned_return_day_time) <= '".$senddate."') AND reservation_cancelled	=	0 AND cancelled_by_driver =	0 AND (coord_approval = 'Approved') ".$sResSQL;
	
	
	//print($sSQL);
	//if($ivehicleid==11)	print($sSQL);
	
	$rsRES	=	mysql_query($sSQL) or die(mysql_error());
	
	//first delete previous temp entries
	//$sSQL	=	"DELETE FROM tbl_temp_reservations WHERE session = '".$ssession."'";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
	//fn_DELETE_TEMP($ssession);
	
	if(mysql_num_rows($rsRES)>0){
		while($rowRES	=	mysql_fetch_array($rsRES)){
			$iHours	=	0;
			while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") < strtotime($rowRES['End_Date'])){
			
			//$sSQL	=	"INSERT INTO tbl_temp_reservations VALUES('".date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"))."', '".$ssession."')";
			//print($sSQL);
			//$rsADD_INTERVALS	=	mysql_query($sSQL) or die(mysql_error());
			
			$sRESERVATIONS_ARRAY[]		=		date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"));
			$iHours++;
			}
			
		}
	}mysql_free_result($rsRES);
//die();
	sort($sRESERVATIONS_ARRAY);
	return $sRESERVATIONS_ARRAY;
}

function fn_CHECK_DRIVER_RESERVATION($idriverid, $sstartdate, $senddate, $ssession, $iResID = 0){

	$sResSQL	=	"";
	if($iResID!=0)	$sResSQL	=	" AND tbl_reservations.res_id <> ".$iResID;
	
	$sDRIVER_RESERVATIONS_ARRAY	=	array();
	
	$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
	"FROM tbl_reservations ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"INNER JOIN tbl_user assigned ON tbl_reservations.assigned_driver = assigned.user_id ".
	"INNER JOIN tbl_departments ON assigned.dept_id = tbl_departments.dept_id ".
	"WHERE assigned_driver = ".$idriverid." AND tbl_trip_details.res_id IS NULL AND tbl_abandon_trips.res_id IS NULL AND ".
	"(DATE(planned_depart_day_time) >= '".$sstartdate."' OR DATE( planned_return_day_time ) >= '".$sstartdate."') AND ".
	"(DATE(planned_depart_day_time) <= '".$senddate."' OR DATE(planned_return_day_time) <= '".$senddate."') AND reservation_cancelled	=	0 AND cancelled_by_driver =	0 AND (coord_approval = 'Approved') ".$sResSQL;


	$rsRES	=	mysql_query($sSQL) or die(mysql_error());
	
	//first delete previous temp entries
	//$sSQL	=	"DELETE FROM tbl_temp_reservations WHERE session = '".$ssession."'";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
	//fn_DELETE_TEMP($ssession);
	
	if(mysql_num_rows($rsRES)>0){
		while($rowRES	=	mysql_fetch_array($rsRES)){
			$iHours	=	0;
			while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") < strtotime($rowRES['End_Date'])){
			
			//$sSQL	=	"INSERT INTO tbl_temp_reservations VALUES('".date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"))."', '".$ssession."')";
			//print($sSQL);
			//$rsADD_INTERVALS	=	mysql_query($sSQL) or die(mysql_error());
			$sDRIVER_RESERVATIONS_ARRAY[]		=		date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"));
			$iHours++;
			}
			
		}
	}mysql_free_result($rsRES);
	sort($sDRIVER_RESERVATIONS_ARRAY);
	return $sDRIVER_RESERVATIONS_ARRAY;
//die();
}


function fn_CUSHION_RESERVATION($iVEHICLE_ID, $sDEPARTURE_DATE_TIME, $sRETURN_DATE_TIME){

	$bCUSHION		=	true;
	
	$sSQL	=	"SELECT planned_return_day_time AS ret_time FROM tbl_reservations ".
	"WHERE (DAY(planned_return_day_time) =	DAY('".$sDEPARTURE_DATE_TIME."') ".
	"AND MONTH(planned_return_day_time) =	MONTH('".$sDEPARTURE_DATE_TIME."') ".
	"AND YEAR(planned_return_day_time) 	= 	YEAR('".$sDEPARTURE_DATE_TIME."')) ".
	"AND (tbl_reservations.vehicle_id = ".$iVEHICLE_ID.")";

	$rsRETURN_TIME_SLOTS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsRETURN_TIME_SLOTS)>0){
		while($rowRETURN_SLOT	=	mysql_fetch_array($rsRETURN_TIME_SLOTS)){
		
			if(strtotime($rowRETURN_SLOT['ret_time']) == strtotime($sDEPARTURE_DATE_TIME)){	$bCUSHION	=	false;			}
			
		}
	}mysql_free_result($rsRETURN_TIME_SLOTS);
	

	$sSQL	=	"SELECT planned_depart_day_time AS depart_time FROM tbl_reservations ".
	"WHERE (DAY(planned_depart_day_time) =	DAY('".$sRETURN_DATE_TIME."') ".
	"AND MONTH(planned_depart_day_time) =	MONTH('".$sRETURN_DATE_TIME."') ".
	"AND YEAR(planned_depart_day_time) 	= 	YEAR('".$sRETURN_DATE_TIME."')) ".
	"AND (tbl_reservations.vehicle_id = ".$iVEHICLE_ID.")";
	
	$rsDEPART_TIME_SLOTS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsDEPART_TIME_SLOTS)>0){
		while($rowDEPART_SLOT	=	mysql_fetch_array($rsDEPART_TIME_SLOTS)){
		
			if(strtotime($rowDEPART_SLOT['depart_time']) == strtotime($sRETURN_DATE_TIME)){$bCUSHION	=	false;			}
			
		}
	}mysql_free_result($rsDEPART_TIME_SLOTS);
	
	return $bCUSHION;
	
}

function fn_PRINT_TRIP_SLIP($iRESERVATION_ID, $bRE_PRINT = 0){

	$sTRIP_SLIP_TEXT	=	"";
	$iLAST_MILEAGE		=	0;
						
							$sSQL	=	"SELECT CASE WHEN tbl_reservations.childseat = 1 THEN 'Yes' ELSE 'No' END childseat, tbl_reservations.vehicle_id, ".
							"tbl_reservations.destination, tbl_reservations.planned_passngr_no, ".
							"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
							"CASE WHEN key_no IS NULL THEN '' ELSE key_no END AS key_no, CASE WHEN card_no IS NULL THEN '' ELSE card_no END AS card_no, ".
							"tbl_vehicles.vehicle_no, tbl_vehicles.passenger_cap, ".
							"assigned.dept_id, assigned_dept.dept_name, assigned_dept.leader_email, reserved.f_name, reserved.l_name, CONCAT(assigned.f_name, ' ', assigned.l_name) AS assigned_driver, assigned.phone, assigned.email, ".
							"home.dept_name AS home_dept, bill.dept_name AS bill_dept ".
							"FROM tbl_reservations ".
							"INNER JOIN tbl_user reserved ON tbl_reservations.user_id = reserved.user_id ".
							"INNER JOIN tbl_user assigned ON tbl_reservations.assigned_driver = assigned.user_id ".
							"INNER JOIN tbl_departments assigned_dept ON assigned.dept_id = assigned_dept.dept_id ".
							"INNER JOIN tbl_departments home ON reserved.dept_id = home.dept_id ".
							"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
							"INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
							"WHERE tbl_reservations.res_id =  ".$iRESERVATION_ID;
							//print($sSQL);
							
							$rsRESERV_INFO	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsRESERV_INFO)>0){
								$rowRESERV_INFO	=	mysql_fetch_array($rsRESERV_INFO);
							}mysql_free_result($rsRESERV_INFO);
							
							
							//============VEHICLE LAST MILEAGE======================
							
							$iLAST_MILEAGE	=	fn_VEHICLE_LAST_MILEAGE($rowRESERV_INFO['vehicle_id']);
							//======================================================
							
							$sTRIP_SLIP_TEXT		=	"<table cellspacing='3' cellpadding='0' border='0' align='left' width='500'>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'><span style='font-size:15px; font-weight:bold;'>University of the Nations - Vehicle Trip Record</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td width='150'><span style='font-size:13px; font-weight:bold;'>Reservation No:</span></td><td width='150'><span style='font-size:13px;'>".$iRESERVATION_ID."</span></td>";
							if($rowRESERV_INFO['card_no']==""){
							$sTRIP_SLIP_TEXT		.=	"<td width='200'><span style='font-size:13px; font-weight:bold;'>GAS CARD No:".str_repeat('&#95;',10)."</span></td></tr>";
							}else{
							$sTRIP_SLIP_TEXT		.=	"<td width='200'><span style='font-size:13px; font-weight:bold;'>GAS CARD No:</span>".str_repeat('&#95;',3).$rowRESERV_INFO['card_no'].str_repeat('&#95;',3)."</td></tr>";
							}
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Reserved By:</span></td><td width='150'><span style='font-size:13px;'>".$rowRESERV_INFO['f_name']."&nbsp;".$rowRESERV_INFO['l_name']."</span></td>";
							if($rowRESERV_INFO['key_no']==""){
							$sTRIP_SLIP_TEXT		.=	"<td><span style='font-size:13px; font-weight:bold;'>KEY No:</span>".str_repeat('&#95;',10)."</td></tr>";
							}else{
							$sTRIP_SLIP_TEXT		.=	"<td><span style='font-size:13px; font-weight:bold;'>KEY No:</span>".str_repeat('&#95;',5).$rowRESERV_INFO['key_no'].str_repeat('&#95;',5)."</td></tr>";
							}
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Assigned Driver:</span></td><td width='150'><span style='font-size:13px;'>".$rowRESERV_INFO['assigned_driver']."</span></td>";

							if($bRE_PRINT==1){		$sTRIP_SLIP_TEXT		.=	"<td><span style='font-size:15px; font-weight:bold;'>REPRINTED</td></tr>";				}
							
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Phone:</span></td><td colspan='2'><span style='font-size:13px;'>".$rowRESERV_INFO['phone']."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Home Dept:</span></td><td colspan='2'><span style='font-size:13px;'>".$rowRESERV_INFO['home_dept']."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Billing Dept:</span></td><td colspan='2'><span style='font-size:13px;'>".$rowRESERV_INFO['bill_dept']."</span></td></tr>";
							
							//$sTRIP_SLIP_TEXT		.=	"<td><span style='font-size:13px; font-weight:bold;'>Department:</span></td><td colspan='2'><span style='font-size:13px;'>".$rowRESERV_INFO['dept_name']."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:15px; font-weight:bold;'>Vehicle number:</span></td><td colspan='2'><span style='font-size:15px;'>".$rowRESERV_INFO['vehicle_no']."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Trip Departure Date & Time:</span></td><td colspan='2'><span style='font-size:13px;'>".fn_cDateMySql($rowRESERV_INFO['planned_depart_day_time'], 2)."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Planned Return Date & Time:</span></td><td colspan='2'><span style='font-size:13px;'>".fn_cDateMySql($rowRESERV_INFO['planned_return_day_time'], 2)."</span></td></tr>";
							//$sTRIP_SLIP_TEXT		.=	"<span style='font-size:13px; font-weight:bold;'>Number of passengers planned:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:13px;'>".$rowRESERV_INFO['planned_passngr_no']."</span><br /><br />";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Destination:</span></td><td colspan='2'><span style='font-size:13px;'>".stripslashes($rowRESERV_INFO['destination'])."</span></td></tr>";
							//$sTRIP_SLIP_TEXT		.=	"<span style='font-size:15px; font-weight:bold;'><u>Driver must enter the following information:</u></span><br /><br /><br />";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Beginning Mileage:</td><td colspan='2'>".str_repeat('&#95;',10)."</span>";
							$sTRIP_SLIP_TEXT		.=	str_repeat('&nbsp;',5)."<span style='font-size:13px; font-weight:bold;'>Ending Gas Level:</span>&nbsp;&nbsp;<span style='font-size:11px;'>25%&nbsp;50%&nbsp;75%&nbsp;100%</span></td></tr>";
							
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:11px; font-weight:bold;'>whole miles only, no tenths</span></td><td colspan='2'>".str_repeat('&nbsp;',25)."<span style='font-size:11px; font-weight:bold;'>If you were given a gas card,</span>".str_repeat('&nbsp;',5)."<span style='font-size:11px; font-weight:bold;'>circle one</span><br />";
							$sTRIP_SLIP_TEXT		.=	str_repeat('&nbsp;',25)."<span style='font-size:11px; font-weight:bold;'>you must use it to fill the tank</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td><span style='font-size:13px; font-weight:bold;'>Ending Mileage:</td><td colspan='2'>".str_repeat('&#95;',25)."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'><span style='font-size:13px; font-weight:bold;'>Describe any problems or vehicle demage</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'><span style='font-size:13px; font-weight:bold;'>".str_repeat('&#95;',85)."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'>&nbsp;</td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'><span style='font-size:13px; font-weight:bold;'>".str_repeat('&#95;',85)."</span></td></tr>";
							$sTRIP_SLIP_TEXT		.=	"<tr><td colspan='3'><span style='font-size:12px; font-weight:bold;'>Put this completed record with the keys and gas card in the mail slot by the Transportation Office</span></td></tr>";
                                                        ### Erik Add ###
                                                        $sTRIP_SLIP_TEXT                .=      "<tr><td colspan='3'></td>&nbsp;</tr>";
                                                        $sTRIP_SLIP_TEXT                .=      "<tr><td colspan='3'><img src='/assets/images/vehicle-inspection.gif' border='0'></td></tr>";
                                                        ### End Erik Add ###
							$sTRIP_SLIP_TEXT		.=	"</table>";
							//=======================================================
							
							
							
							return $sTRIP_SLIP_TEXT;
}


function fn_VEHICLE_LAST_MILEAGE($iVEHICLE_ID){
	$sSQL	=	"SELECT end_mileage FROM tbl_trip_details WHERE res_id = (SELECT MAX(tbl_reservations.res_id) FROM tbl_reservations INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id WHERE vehicle_id = ".$iVEHICLE_ID.") ";	
	//print($sSQL);
	$rsMILEAGE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsMILEAGE)>0){
		return mysql_result($rsMILEAGE,0);
	}else{
		return 0;
	}mysql_free_result($rsMILEAGE);
}

function fn_DELETE_SIDE_MENU($iSESSION_ID){
	$sSQL	=	"DELETE FROM tbl_side_menus WHERE user_session = '".$iSESSION_ID."'";
	$rsDEL_MENU	=	mysql_query($sSQL) or die(mysql_error());
}

function fn_VEHICLE_LAST_END_GAS($iVEHICLE_ID){
	$sSQL	=	"SELECT end_gas_percent FROM tbl_trip_details WHERE res_id = (SELECT MAX(tbl_reservations.res_id) FROM tbl_reservations INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id WHERE vehicle_id = ".$iVEHICLE_ID.") ";	
	//print($sSQL);
	$rsEND_GAS	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsEND_GAS)>0){
		return mysql_result($rsEND_GAS,0);
	}else{
		return 0;
	}mysql_free_result($rsEND_GAS);
}


function fn_VEHICLE_LAST_END_GAS_DATE($iVEHICLE_ID){
	$sSQL	=	"SELECT DATE_FORMAT(reg_date, '%m/%d/%Y') AS end_gas_date FROM tbl_trip_details WHERE res_id = (SELECT MAX(tbl_reservations.res_id) FROM tbl_reservations INNER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id WHERE vehicle_id = ".$iVEHICLE_ID.") ";	
	//print($sSQL);
	$rsEND_GAS_DATE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsEND_GAS_DATE)>0){
		return mysql_result($rsEND_GAS_DATE,0);
	}else{
		return 0;
	}mysql_free_result($rsEND_GAS_DATE);
}

//GET THE ASSIGNED DRIVER NAME
function fn_GET_ASSIGNED_DRIVER($iDRIVER_ID){
	return fn_GET_FIELD_BY_QUERY("SELECT CONCAT(f_name, ' ', l_name) AS driver_name FROM tbl_user WHERE user_id = ".$iDRIVER_ID);
}
function fn_SET_TIME_ZONE(){
	date_default_timezone_set('Pacific/Honolulu');
	//date_default_timezone_set($sTimeZone);
}

function fn_GET_TIME_ZONE(){
	return date_default_timezone_get();
}

/*if(fn_GET_TIME_ZONE()=="UTC"){
	fn_SET_TIME_ZONE();
}*/

/*if(fn_GET_TIME_ZONE()=="America/Los_Angeles"){
	fn_SET_TIME_ZONE();
}*/


fn_SET_TIME_ZONE();



function fn_DEL_DEPT($iDEPT_ID){
	
	$sSQL	=	"SELECT user_id FROM tbl_user WHERE dept_id = '".$iDEPT_ID."'";
	$rsUSER	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsUSER)>0){
		while($rowDEPT	=	mysql_fetch_array($rsUSER)){
			$sSQL	=	"DELETE FROM tbl_vehicles WHERE user_id = ".$rowDEPT['user_id'];
			$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"DELETE FROM tbl_vehicle_comments WHERE posting_user_id = ".$rowDEPT['user_id'];
			$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"DELETE FROM tbl_shop_tasks WHERE user_id = ".$rowDEPT['user_id'];
			$rsDEL_SHOP	=	mysql_query($sSQL) or die(mysql_error());
			
			//DELETE NOTES HERE
			$sSQL	=	"DELETE FROM tbl_user_comments WHERE about_user_id = ".$rowDEPT['user_id']." OR posting_user_id = ".$rowDEPT['user_id'];
			$rsDEL_COMMENTS	=	mysql_query($sSQL) or die(mysql_error());
	
			$sSQL	=	"SELECT tbl_reservations.res_id FROM tbl_reservations WHERE user_id = ".$rowDEPT['user_id'];
			$rsRES	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsRES)>0){
				while($rowRES	=	mysql_fetch_array($rsRES)){
					$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowRES['res_id'];
					$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
					$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowRES['res_id'];
					$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
				}
			}mysql_free_result($rsRES);
			
			$sSQL	=	"DELETE FROM tbl_reservations WHERE user_id = ".$rowDEPT['user_id'];
			$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
		}
	}

	$sSQL		=	"DELETE FROM tbl_user WHERE dept_id = '".$iDEPT_ID."'";
	$rsDEL_USER	=	mysql_query($sSQL) or die(mysql_error());
	
	if(fn_DELETE_RECORD("tbl_departments", "dept_id", $iDEPT_ID))
		return true;
	else
		return false;
}



function fn_CALCULATE_DATE_DIFF($sDATE_1, $sDATE_2){
	/*$date1 = $sDATE_1;
	$date2 = $sDATE_2;
	
	$diff = abs(strtotime($date2) - strtotime($date1));
	
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	
	return $days;*/
	
	$date2 = strtotime($sDATE_2);
	$date1 = strtotime($sDATE_1);
	return intval(($date2-$date1)/60/60/24);
}
function fn_CHECK_VEHICLE_RESERVATION($iVEHICLE_ID, $sDEPARTURE_DATE_TIME, $sRETURN_DATE_TIME, $sSESSION_ID, $iRESERVATION_ID = 0){

	$iHours	=	0; 		$sRESVR_ERR	=	"";		$arrRESVS_ARRAY	=	array();
	$arrRESVS_ARRAY		=	fn_CHECK_RESERVATION($iVEHICLE_ID, substr($sDEPARTURE_DATE_TIME,0,10), substr($sRETURN_DATE_TIME,0,10), $sSESSION_ID, $iRESERVATION_ID);
	while(strtotime($sDEPARTURE_DATE_TIME." + ".$iHours." hour") < strtotime($sRETURN_DATE_TIME)){
		
		$sINC_DEPART_DATE	=	date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($sDEPARTURE_DATE_TIME))." + ".$iHours." hour"));

		if(in_array($sINC_DEPART_DATE, $arrRESVS_ARRAY)){
			$sRESVR_ERR		=	fn_Print_MSG_BOX("<li class='bold-font'>vehicle is already reserved in your specified time period", "C_ERROR");
			break;
		}
		$iHours++;
	}
	
	return $sRESVR_ERR;

}

function fn_CHECK_ASSIGNED_DRIVER_RESERVATION($iDRIVER_ID, $sDEPARTURE_DATE_TIME, $sRETURN_DATE_TIME, $sSESSION_ID, $iRESERVATION_ID = 0){

	$iHours	=	0; 		$sRESVR_ERR	=	"";		$sDRVR_RESVS_ARRAY	=	array();
	$sDRVR_RESVS_ARRAY		=		fn_CHECK_DRIVER_RESERVATION($iDRIVER_ID, substr($sDEPARTURE_DATE_TIME,0,10), substr($sRETURN_DATE_TIME,0,10), $sSESSION_ID, $iRESERVATION_ID);
	while(strtotime($sDEPARTURE_DATE_TIME." + ".$iHours." hour") < strtotime($sRETURN_DATE_TIME)){
		$sINC_DEPART_DATE	=	date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",strtotime($sDEPARTURE_DATE_TIME))." + ".$iHours." hour"));
	
		if(in_array($sINC_DEPART_DATE, $sDRVR_RESVS_ARRAY)){
			$sRESVR_ERR		=	fn_Print_MSG_BOX("<li class='bold-font'>driver is already assigned to some other reservation in specified time period, reservation is not been made", "C_ERROR");
			break;
		}
		$iHours++;
	}
	
	return $sRESVR_ERR;

}

function fn_NUMBER_FORMAT($aNumber,$number_format) {
		 if ($number_format == "1234,56") 	{	$aNumber = number_format($aNumber, 2, ',', '');      }
		 if ($number_format == "1.234,56") 	{   $aNumber = number_format($aNumber, 2, ',', '.');     }
		 if ($number_format == "1234.56") 	{	$aNumber = "$ ". number_format($aNumber, 2, '.', '');      }
		 if ($number_format == "1,234.56") 	{   $aNumber = number_format($aNumber, 2, '.', ',');     }
		 return $aNumber;
}

function fn_VEHICLE_PER_MILE_COST($iRES_ID){
	$iVEHICLE_PERMILE_COST		=	0;
	$sSQL	=	"SELECT v.cost_rate FROM tbl_vehicles v INNER JOIN tbl_reservations r ON v.vehicle_id = r.vehicle_id WHERE r.res_id = ".$iRES_ID;
	$rsVEHICLE_COST	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE_COST)>0){
		list($iVEHICLE_PERMILE_COST)	=	mysql_fetch_row($rsVEHICLE_COST);
	}mysql_free_result($rsVEHICLE_COST);
	
	return $iVEHICLE_PERMILE_COST;
}
function fn_PERMENENT_PULL($iVEHICLE_ID){
	$sSTATUS	=	"";
	$sSQL	=	"SELECT CASE WHEN MAX(srvc_id) IS NULL THEN 'free' ELSE 'pulled' END status  FROM tbl_srvc_resvs WHERE vehicle_id = ".$iVEHICLE_ID." AND is_cancelled = 0";
	$rsDUP_PULL	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsDUP_PULL)>0){
		list($sSTATUS)	=	mysql_fetch_array($rsDUP_PULL);
		if($sSTATUS	==	'pulled'){		return "already";	}else	return "free";
	}mysql_free_result($rsDUP_PULL);
}
function fn_TEMP_SERVICE($sTRANSACTION_TYPE, $iVEHICLE_ID, $sSTART_DATE, $sEND_DATE, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){
				
		if(strtotime($sEND_DATE) <= strtotime($sSTART_DATE)){
			return "future";
		}elseif(fn_CALCULATE_DATE_DIFF($sSTART_DATE, $sEND_DATE)>14){
			return "overlimit";
		}else{	
			//$sSQL	=	"SELECT CASE WHEN MAX(srvc_id) IS NULL THEN 0 ELSE MAX(srvc_id) END AS srvc_id FROM tbl_srvc_resvs WHERE vehicle_id = ".$iVEHICLE_ID." AND ((from_date BETWEEN '".$sSTART_DATE."' AND '".$sEND_DATE."') OR (to_date BETWEEN '".$sSTART_DATE."' AND '".$sEND_DATE."')) AND is_cancelled = 0";
			$sSQL	=	"SELECT CASE WHEN MAX(srvc_id) IS NULL THEN 'free' ELSE 'pulled' END status  FROM tbl_srvc_resvs WHERE vehicle_id = ".$iVEHICLE_ID." AND is_cancelled = 0";
			$rsDUP_RESV		=	mysql_query($sSQL) or die(mysql_error());
			$rowDUP_POLICY	=	mysql_fetch_array($rsDUP_RESV);
			//if($rowDUP_POLICY['srvc_id']!=0){
			if($rowDUP_POLICY['status']=='pulled'){
				return "already";
			}else{
				return fn_SERVICE_RESERVATION($sTRANSACTION_TYPE, 'temporary', $iVEHICLE_ID, $sSTART_DATE, $sEND_DATE, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
			}mysql_free_result($rsDUP_RESV);
			
		}
}
function fn_SERVICE_RESERVATION($sTRANSACTION_TYPE, $sSERVICE_TYPE, $iRESVD_VECHILE, $sSTART_DATE, $sEND_DATE, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){
$iSERVICE_ID		=	0;		$sCRITERIA_SQL	=	"";		$iNUM_RES_CANCELLED	=	0;
$sMessage			=	"";
//global 	$sMessage;

				if($sSTART_DATE!="" && $sEND_DATE!=""){	$sCRITERIA_SQL	=	"AND planned_depart_day_time BETWEEN '".$sSTART_DATE."' AND '".$sEND_DATE."'";	}

				//select all reservations for the vehicle, between start and end date, which are pending
				$sSQL	=	"SELECT r.res_id, r.user_id, r.planned_depart_day_time, CONCAT(u.f_name, ' ', u.l_name) AS reserver_name, u.email, t.trip_id, v.vehicle_no FROM tbl_reservations r ".
				"LEFT OUTER JOIN tbl_trip_details t ON r.res_id = t.res_id ".
				"LEFT OUTER JOIN tbl_abandon_trips a ON r.res_id = a.res_id ".
				"INNER JOIN tbl_user u ON r.user_id = u.user_id ".
				"INNER JOIN tbl_vehicles v ON r.vehicle_id = v.vehicle_id ".
				"WHERE r.vehicle_id = ".$iRESVD_VECHILE." ".
				$sCRITERIA_SQL." ".
				"AND  trip_id IS NULL AND abandon_id IS NULL AND reservation_cancelled = 0 AND cancelled_by_driver = 0";
				
				//print($sSQL);
				
								
				$rsRES	=	mysql_query($sSQL) or die(mysql_error());
				$iNUM_RES_CANCELLED	=		mysql_num_rows($rsRES);	
				if($iNUM_RES_CANCELLED>0){
					
						if($sTRANSACTION_TYPE!="ajax"){
						
								//first insertto database	
								$iSERVICE_ID	=	fn_INSERT_SERVICE_RESERVATION($sSERVICE_TYPE, $iRESVD_VECHILE, $sSTART_DATE, $sEND_DATE);
								
								
									//select the reservation message
								$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 17";
								$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
								$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 17";
								$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));								
								
								
								while($rowRES	=	mysql_fetch_array($rsRES)){
									$iRES_ID	=	$rowRES['res_id'];
									$sRES_EMAIL	=	$rowRES['email'];
									$sRES_NAME	=	$rowRES['reserver_name'];
									$sVEHICLE_NO=	$rowRES['vehicle_no'];
									$sPLAN_DATE	=	$rowRES['planned_depart_day_time'];
									
									//first cancel reservation
									$sSQL	=	"UPDATE tbl_reservations SET reservation_cancelled = 1, res_delete_user = ".$iUSER_ID.", res_delete_datetime = '".date('Y-m-d H:i:s')."' WHERE res_id = ".$iRES_ID;
									mysql_query($sSQL) or die(mysql_error());
																
									$sSQL	=	"INSERT INTO tbl_srvc_resvs_details (srvc_id, res_id) VALUES (".$iSERVICE_ID.", ".$iRES_ID.")";
									mysql_query($sSQL) or die(mysql_error());
									
									$sEDIT_MSG	=	str_replace('#VEHICLE NO#', $sVEHICLE_NO, str_replace('#ON DATE#', fn_cDateMySql($sPLAN_DATE,2), str_replace('#RESERVATION#', $iRES_ID, $sMailMSG)));
									//$sMessage		.=	fn_Print_MSG_BOX($sEDIT_MSG, "C_SUCCESS");
									$mail = new PHPMailer();
									$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
									$mail->From     = $sSUPPORT_EMAIL;
									$mail->FromName = $sCOMPANY_Name;
									$mail->AddAddress($sRES_EMAIL);
									$mail->IsHTML(true);                               // send as HTML
									$mail->Subject  =  $sEmailSubject;
									$mail->Body    	= $sEDIT_MSG;
									if(!$mail->Send()){
									   $sMessage		.=	fn_Print_MSG_BOX("<li>Reservation ".$iRES_ID." have been cancelled, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
									}
									
								}
								if($iNUM_RES_CANCELLED>0)		$sMessage		=	fn_Print_MSG_BOX("<li>Vehicle has been pulled for service and ".$iNUM_RES_CANCELLED." reservation(s) has been cancelled", "C_SUCCESS");
						}else{
							//$sMessage		=	fn_Print_MSG_BOX("<li>This action will delete ".$iNUM_RES_CANCELLED." reservations. The drivers impacted by these deletions will automatically be notified Continued..?", "C_SUCCESS");<br>
							$sMessage		=	"This action will delete ".$iNUM_RES_CANCELLED." reservations.<br />Details of these trips are available using the 'List Deleted Trips' function<br />The drivers impacted by these deletions will automatically be notified Continue..?";
						}
						
						
				}else{
					if($sTRANSACTION_TYPE!="ajax"){
						$iSERVICE_ID	=	fn_INSERT_SERVICE_RESERVATION($sSERVICE_TYPE, $iRESVD_VECHILE, $sSTART_DATE, $sEND_DATE);
						$sMessage		=	fn_Print_MSG_BOX("<li>Vehicle been pulled for service", "C_SUCCESS");
					}
				}mysql_free_result($rsRES);
				
				return $sMessage;
}	
	
function fn_INSERT_SERVICE_RESERVATION($sSERVICE_TYPE, $iRESVD_VECHILE, $sSTART_DATE, $sEND_DATE){
	if($sSERVICE_TYPE=="permanent"){
		$sSQL	=	"INSERT INTO tbl_srvc_resvs (vehicle_id, service_type) VALUES (".$iRESVD_VECHILE.", '".$sSERVICE_TYPE."')";
	}elseif($sSERVICE_TYPE=="temporary"){
		$sSQL	=	"INSERT INTO tbl_srvc_resvs (vehicle_id, from_date, to_date, service_type) VALUES (".$iRESVD_VECHILE.", '".$sSTART_DATE."', '".$sEND_DATE."', '".$sSERVICE_TYPE."')";
	}
	mysql_query($sSQL) or die(mysql_error());
	$iSERVICE_ID		=	mysql_insert_id();
	return $iSERVICE_ID;
}

function fn_SEND_EMAIL_TO_USER($iMSG_ID, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){


	$sDRIVER_NAME	=	"";	$sEND_PERMIT	=	""; $sDEPT_NAME	=	""; $sDRIVER_EMAIL	=	"";
	
	
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = ".$iMSG_ID;
	$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
	$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = ".$iMSG_ID;
	$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));

	if($iMSG_ID==21){	
	//extract user level, username, group leve
		$sSQL			=	"SELECT CONCAT(u.f_name, ' ', u.l_name) AS driver_name, u.end_permit, d.dept_name, u.email FROM tbl_user u INNER JOIN tbl_departments d ON u.dept_id = d.dept_id WHERE user_id = ".$iUSER_ID;
		$rsUSER_STATE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER_STATE)>0){
			list($sDRIVER_NAME, $sEND_PERMIT, $sDEPT_NAME, $sDRIVER_EMAIL)	=	mysql_fetch_row($rsUSER_STATE);
		}mysql_free_result($rsUSER_STATE);
		
		$sMailMSG		=	str_replace('#___________#', $sDRIVER_NAME,	str_replace('#PERMIT END DATE#', fn_cDateMySql($sEND_PERMIT,1), str_replace('#Dept_Name#', $sDEPT_NAME, $sMailMSG)));
	}elseif($iMSG_ID==14){
		$sSQL			=	"SELECT u.password, u.email, g.group_name FROM tbl_user u INNER JOIN tbl_user_group g ON u.user_group = g.group_id WHERE user_id = ".$iUSER_ID;
		$rsUSER_STATE	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER_STATE)>0){
			list($sUSER_PWD, $sDRIVER_EMAIL, $sUSER_GROUP)	=	mysql_fetch_row($rsUSER_STATE);
		}mysql_free_result($rsUSER_STATE);
		
		
		$sMailMSG		=	str_replace('#USER LEVEL#', $sUSER_GROUP, str_replace('#PASSWORD#', $sUSER_PWD, str_replace('#USERNAME#', $sDRIVER_EMAIL, $sMailMSG)));
	}elseif($iMSG_ID==25){//bad guys activation
		//if there some message
		$sSQL			=	"SELECT u.email FROM tbl_user u WHERE u.user_id = ".$iUSER_ID;
		$rsUSER_EMAIL	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER_EMAIL)>0){
			list($sDRIVER_EMAIL)	=	mysql_fetch_row($rsUSER_EMAIL);
		}mysql_free_result($rsUSER_EMAIL);
		
	}
	//$sMessage		=	fn_Print_MSG_BOX($sMailMSG,"C_ERROR");
	//print($sMailMSG."==DRIVER EMAIL ==".$sDRIVER_EMAIL);
	$mail = new PHPMailer();
	$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
	$mail->From     = $sSUPPORT_EMAIL;
	$mail->FromName = $sCOMPANY_Name;
	$mail->AddAddress($sDRIVER_EMAIL);
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  $sEmailSubject;
	$mail->Body    = $sMailMSG;
	if(!$mail->Send())		return false;		else return true;
	
	//if(!$mail->Send()){	  print("FAILED SENDING EMAIL"); } else {print("MAIL SENT");}//$sMessage		=	fn_Print_MSG_BOX("Driver Pemrit has been renewed, <br />but Error in Sending Email!","C_ERROR");	}
}

function fn_CLOSE_PENDING_TRIPS($iRESERVATION_ID	=	0){

	$sCLOSE_BOX_STR		=		""; 	$sSELECTED			=		"";
	$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
	"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
	"tbl_vehicles.vehicle_no ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
	"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
	"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"WHERE tbl_reservations.coord_approval = 'Approved' AND ".
	"reservation_cancelled = 0 AND cancelled_by_driver = 0 AND ".
	"tbl_trip_details.res_id IS NULL ".
	"AND tbl_abandon_trips.res_id IS NULL ".
	"ORDER BY tbl_reservations.res_id DESC";
	$rsROWS		=	mysql_query($sSQL) or die(mysql_error());
	
	if(mysql_num_rows($rsROWS)>0){													
		$sCLOSE_BOX_STR			.=	"<select name='resid' style='width:430px;' size='1' onChange='ajax_data(this.value);'>";
			$sCLOSE_BOX_STR		.=	"<option value='' selected>--Select Reservation--</option>";
				while($rowROWS	=	mysql_fetch_array($rsROWS)){
				if($iRESERVATION_ID==$rowROWS['res_id']) $sSELECTED	=	 "selected";	else 		$sSELECTED	=	"";
				$sCLOSE_BOX_STR	.=	"<option value=".$rowROWS['res_id']." ".$sSELECTED.">R-No:&nbsp;".$rowROWS['res_id']."&nbsp;V-No:&nbsp;".$rowROWS['vehicle_no']." FROM ".fn_cDateMySql($rowROWS['planned_depart_day_time'],2)." TO ".fn_cDateMySql($rowROWS['planned_return_day_time'],2)."</option>";
				}
		$sCLOSE_BOX_STR			.=	"</select>";
	}mysql_free_result($rsROWS);
	
	return $sCLOSE_BOX_STR;
}

function fn_ABANDON_PENDING_TRIPS($iRESERVATION_ID	=	0){

	$sABANDON_BOX_STR		=		""; 	$sSELECTED			=		"";
	$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
	"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
	"tbl_vehicles.vehicle_no ".
	"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
	"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
	"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
	"WHERE tbl_reservations.coord_approval = 'Approved' AND ".
	"reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
	"AND tbl_trip_details.res_id IS NULL ".
	"AND tbl_abandon_trips.res_id IS NULL ".
	"AND tbl_reservations.planned_depart_day_time < NOW() ".
	"ORDER BY tbl_reservations.res_id DESC";
		//print($sSQL);
	$rsROWS		=	mysql_query($sSQL) or die(mysql_error());
	
	if(mysql_num_rows($rsROWS)>0){													
		$sABANDON_BOX_STR			.=	"<select name='resid' style='width:430px;' size='1' onChange='fn_LOAD_RESRVD_BY(this.value);'>";
			$sABANDON_BOX_STR		.=	"<option value='' selected>--Select Reservation--</option>";
				while($rowROWS	=	mysql_fetch_array($rsROWS)){
				if($iRESERVATION_ID==$rowROWS['res_id']) $sSELECTED	=	 "selected";	else 		$sSELECTED	=	"";
				$sABANDON_BOX_STR	.=	"<option value=".$rowROWS['res_id']." ".$sSELECTED.">R-No:&nbsp;".$rowROWS['res_id']."&nbsp;V-No:&nbsp;".$rowROWS['vehicle_no']." FROM ".fn_cDateMySql($rowROWS['planned_depart_day_time'],2)." TO ".fn_cDateMySql($rowROWS['planned_return_day_time'],2)."</option>";
				}
		$sABANDON_BOX_STR			.=	"</select>";
	}mysql_free_result($rsROWS);
	
	return $sABANDON_BOX_STR;

}
?>
