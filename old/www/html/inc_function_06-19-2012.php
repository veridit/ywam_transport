<?
$sCOMPANY_Name	=	"University of the Nations";
$sCOMPANY_Link	=	"http://transtest.net/";
$sSUPPORT_EMAIL	=	"support@uofn.com";
$sCOMPANY_SMTP	=	"smtp.transtest.net";

//date_default_timezone_set('Pacific/Honolulu');
//date_default_timezone_set('Asia/Tashkent');


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
 $iOPT_TM_USER_MODIFY	=	6;

//************RESERVATIONS********
 $iOPT_RES_ADD			=	7;
 $iOPT_RES_DELETE		=	8;
 $iOPT_RES_SEARCH		=	9;
 $iOPT_RES_APPROVAL		=	10;
 $iOPT_RES_CANCELLATION	=	11;
 
 //************TRIPS********
$iOPT_TRIP_ADD			=	12;
$iOPT_TRIP_SEARCH		=	13;
$iOPT_TRIP_EDIT			=	14;
$iOPT_TRIP_DELETE		=	15;

//*************ABANDONED TRIPS*******
$iOPT_ABANDON_TRIP_ADD		=	16;
$iOPT_ABANDON_TRIP_SEARCH	=	17;
//************SHOP WORK********
 $iOPT_SHOP_ADD			=	18;
 $iOPT_SHOP_DELETE		=	19;
 $iOPT_SHOP_SEARCH		=	20;
 $iOPT_SHOP_MODIFY		=	21;

//************COMMENTS********
 $iOPT_USER_COMMENT_ADD	=	22;
 $iOPT_USER_COMMENT_DELETE	=	23;
 $iOPT_USER_COMMENT_SEARCH	=	24;
 $iOPT_USER_COMMENT_MODIFY	=	25;

//************COST************
 $iOPT_COST_ADD			=	26;
 $iOPT_COST_DELETE		=	27;
 $iOPT_COST_SEARCH		=	28;
 $iOPT_COST_MODIFY		=	29;

//************VEHICLES********
 $iOPT_VEHICLES_ADD		=	30;
 $iOPT_VEHICLES_DELETE	=	31;
 $iOPT_VEHICLES_SEARCH	=	32;
 $iOPT_VEHICLES_MODIFY	=	33;
 $iOPT_VEHICLES_ISSUES	=	34;			//VEHICLE ISSUES REPORT BASED ON LIST VEHICLES
 $iOPT_VEHICLES_ISSUE_MODIFY	=	35;
 $iOPT_RESTRICTED_VEHICLE_CHARGES	=	36;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH	=	37;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY	=	38;
 $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE	=	39;
 
 //************DEPARTMENTS********
 $iOPT_DEPARTMENT_ADD	=	40;
 $iOPT_DEPARTMENT_DELETE=	41;
 $iOPT_DEPARTMENT_SEARCH=	42;
 $iOPT_DEPARTMENT_MODIFY=	43;
 
 //*********REPORTS********
 
 $iOPT_DRIVER_MILEAGE	=	44;
 $iOPT_DEPARTMENT_COST	=	45;
 $iOPT_VEHICLE_COST		=	46;
 $iOPT_VEHICLE_R_M_TASK	=	47;
 $iOPT_VEHICLE_MILEAGE	=	48;
 $iOPT_PENDING_TRIP		=	49;
 $iOPT_GRAPH_PENDING_TRIPS=	50;
 $iOPT_SCHOOL_COST		=	51;
 $iOPT_INSPECT_DUE		=	52;
 $iOPT_DELETED_TRIPS	=	53;
 //****************RESERVATIONS----CHARGE DEPT***********************
 $iOPT_DRIVER_DELETED_RESERVATION	=	54;
 $iOPT_EDIT_RESERVATION	=	55;
 $iOPT_CHANGE_CHARGE_DEPT	=	56;
 
 //*********CHECK VEHICLE RESERVATIONS*********************
$iOPT_CHECK_RESERVATIONS=	57;
//*************************DRIVERS OPERATIONS********************
$iOPT_DEACTIVATE_DRIVERS=	58;
$iOPT_DELETE_DRIVERS	=	59;
$iOPT_ACTIVE_DRIVERS	=	60;

//***********************INFO LINKS AND NOTICES************************
$iOPT_LINK_ADD		=	61;
$iOPT_LINK_DELETE	=	62;
$iOPT_LINK_SEARCH	=	63;
$iOPT_LINK_MODIFY	=	64;
$iOPT_NOTICE_ADD	=	65;

//******************************BACKUPS************************

$iOPT_BACKUP		=	66;
$iOPT_BACKUP_SEARCH	=	67;
$iOPT_RESTORE		=	68;
$iOPT_BACKUP_DELETE	=	69;

//**************************LIST IP LOG*********************
$iOPT_LOG_SEARCH	=	70;
$iOPT_LOG_DELETE	=	71;



function fn_DEPARTMENT($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$sSQL	=	"SELECT dept_id, dept_name FROM tbl_departments ORDER BY dept_name";
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

function fn_USER_GROUP($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL){
	$sSQL	=	"SELECT group_id, group_name FROM tbl_user_group ORDER BY group_name";
	$rsGROUP	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsGROUP)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
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
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
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
	$sSQL	=	"SELECT vehicle_id, vehicle_no, brand_name, year_manuf ".
	"FROM tbl_vehicles INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"ORDER BY vehicle_no";
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?=$sOnChangeEvent?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){	?>
		<!--<option value="<?=$rowVEHICLE['vehicle_id']?>" <? if($rowVEHICLE['vehicle_id']==$sSELECTED) echo "selected";?>><?=$rowVEHICLE['vehicle_no']."-".$rowVEHICLE['brand_name']."-".$rowVEHICLE['year_manuf']?></option>-->
		<option value="<?=$rowVEHICLE['vehicle_id']?>" <? if($rowVEHICLE['vehicle_id']==$sSELECTED) echo "selected";?>><?=$rowVEHICLE['vehicle_no']?></option>
<?		}?>
	</select>
<?
	}else{
		echo fn_Print_MSG_BOX("Sorry no vehicles are defined in the system", "C_ERROR");
	}mysql_free_result($rsVEHICLE);
}

function fn_RESTRICTED_VEHICLES($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sOnChangeEvent=''){
	$sSQL	=	"SELECT vehicle_id, vehicle_no, brand_name, year_manuf ".
	"FROM tbl_vehicles INNER JOIN tbl_vehicle_brand ON tbl_vehicles.make_id = tbl_vehicle_brand.brand_id ".
	"WHERE tbl_vehicles.restricted = 0 ORDER BY vehicle_no";
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
		$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap FROM tbl_vehicles WHERE restricted = 1 ORDER BY vehicle_no";
	else
		$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap FROM tbl_vehicles ORDER BY vehicle_no";
	/*$sSQL	=	"SELECT vehicle_id, vehicle_no, passenger_cap, CASE WHEN restricted = 1 THEN 'Restricted' ELSE '' END AS restricted ".
	"FROM tbl_vehicles ORDER BY vehicle_no";*/
	$rsVEHICLE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsVEHICLE)>0){
	
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>" onChange="<?=$sOnChangeEvent?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowVEHICLE	=	mysql_fetch_array($rsVEHICLE)){	?>
		<option value="<?=$rowVEHICLE['vehicle_id']?>" <? if($rowVEHICLE['vehicle_id']==$sSELECTED) echo "selected";?>><?="NO:&nbsp;".$rowVEHICLE['vehicle_no']."&nbsp;-&nbsp;CAPACITY:&nbsp;".$rowVEHICLE['passenger_cap']?></option>
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

function fn_DISPLAY_USERS($sNAME, $sSELECTED, $sWIDTH, $sSIZE, $sOPTIONAL, $sFIELDS, $sORDERBY, $sTYPE=""){
	$sTYPE_SQL	=	"";
	if($sTYPE!="") $sTYPE_SQL	=	" WHERE tbl_user.user_group IN (".$sTYPE.")";
	$sSQL	=	"SELECT user_id, ".$sFIELDS." FROM tbl_user ".$sTYPE_SQL." ORDER BY ".$sORDERBY;
	//print($sSQL);
	$rsUSER	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsUSER)>0){
?>
	<select name="<?=$sNAME?>" style="width:<?=$sWIDTH?>px;" size="<?=$sSIZE?>">
		<option value=""><?=$sOPTIONAL?></option>
<?		while($rowUSER	=	mysql_fetch_array($rsUSER)){	?>
		<option value="<?=$rowUSER['user_id']?>" <? if($rowUSER['user_id']==$sSELECTED) echo "selected";?>><?=$rowUSER[1]?></option>
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
global $iGROUP_TM,	$iGROUP_TC, $iGROUP_DRIVER, $iGROUP_SERVICETCH, $iGROUP_COORDINATOR_STAFF;

//==============================OPERATIONS=======================================
//*************USERS***************
global $iOPT_USER_ADD, $iOPT_USER_DELETE, $iOPT_USER_SEARCH, $iOPT_TM_USER_SEARCH, $iOPT_USER_MODIFY, $iOPT_TM_USER_MODIFY;
//************RESERVATIONS********
global $iOPT_RES_ADD, $iOPT_RES_DELETE, $iOPT_RES_SEARCH, $iOPT_RES_MODIFY, $iOPT_RES_APPROVAL, $iOPT_RES_CANCELLATION;
//************TRIPS********
global $iOPT_TRIP_ADD, $iOPT_TRIP_SEARCH, $iOPT_TRIP_EDIT;
//************ABANDON TRIPS*********************************
global $iOPT_ABANDON_TRIP_ADD, $iOPT_ABANDON_TRIP_SEARCH, $iOPT_TRIP_DELETE;
//************SHOP WORK********
global $iOPT_SHOP_ADD, $iOPT_SHOP_DELETE, $iOPT_SHOP_SEARCH, $iOPT_SHOP_MODIFY;
//************COMMENTS********
global $iOPT_USER_COMMENT_ADD, $iOPT_USER_COMMENT_DELETE, $iOPT_USER_COMMENT_SEARCH, $iOPT_USER_COMMENT_MODIFY;
//************COST************
global $iOPT_COST_ADD, $iOPT_COST_DELETE, $iOPT_COST_SEARCH, $iOPT_COST_MODIFY;
//************VEHICLES********
global $iOPT_VEHICLES_ADD, $iOPT_VEHICLES_DELETE, $iOPT_VEHICLES_SEARCH, $iOPT_VEHICLES_MODIFY, $iOPT_VEHICLES_ISSUES, $iOPT_VEHICLES_ISSUE_MODIFY;
//************RESTRICTED VEHICLES***************************
global $iOPT_RESTRICTED_VEHICLE_CHARGES,  $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH, $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY, $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE;
//************DEPARTMENT********
global $iOPT_DEPARTMENT_ADD, $iOPT_DEPARTMENT_DELETE, $iOPT_DEPARTMENT_SEARCH, $iOPT_DEPARTMENT_MODIFY;
//************REPORTS********
global $iOPT_DRIVER_MILEAGE, $iOPT_DEPARTMENT_COST, $iOPT_VEHICLE_COST, $iOPT_VEHICLE_R_M_TASK, $iOPT_VEHICLE_MILEAGE, $iOPT_PENDING_TRIP, $iOPT_GRAPH_PENDING_TRIPS, $iOPT_SCHOOL_COST, $iOPT_INSPECT_DUE, $iOPT_DELETED_TRIPS;
//************RESERVATIONS------CHAGE CHARGE DEPT*************************
global $iOPT_DRIVER_DELETED_RESERVATION, $iOPT_EDIT_RESERVATION, $iOPT_CHANGE_CHARGE_DEPT;

//**********CHECK VEHICLE RESERVATIONS**************************
global $iOPT_CHECK_RESERVATIONS;
//**************DRIVERS OPERATIONS******************************
global $iOPT_DEACTIVATE_DRIVERS, $iOPT_ACTIVE_DRIVERS, $iOPT_DELETE_DRIVERS;
//**********************LINKI AND NOTICES*******************************
global $iOPT_LINK_ADD,	$iOPT_LINK_DELETE,	$iOPT_LINK_SEARCH,	$iOPT_LINK_MODIFY, $iOPT_NOTICE_ADD;
//**********************BACKUP*******************************************************
global $iOPT_BACKUP, $iOPT_BACKUP_SEARCH, $iOPT_RESTORE, $iOPT_BACKUP_DELETE;
//************************IP LOG******************************************************
global $iOPT_LOG_SEARCH, $iOPT_LOG_DELETE;



	$iLOGGED_USER_GROUP	=	fn_GET_FIELD("tbl_user", $iLOGGED_USER_ID, "user_id", "user_group");

	if($iLOGGED_USER_GROUP==$iGROUP_TM){
	
		switch($iOPERATION){
		
			case $iOPT_USER_ADD:
			//case $iOPT_USER_DELETE:
			case $iOPT_USER_SEARCH:
			case $iOPT_USER_MODIFY:
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
			/*case $iOPT_RESTRICTED_VEHICLE_CHARGES:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE:*/
			case $iOPT_DEPARTMENT_ADD:
			//case $iOPT_DEPARTMENT_DELETE:
			case $iOPT_DEPARTMENT_SEARCH:
			case $iOPT_DEPARTMENT_MODIFY:
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
			case $iOPT_DELETE_DRIVERS:
			case $iOPT_DRIVER_DELETED_RESERVATION:
			case $iOPT_EDIT_RESERVATION:
			case $iOPT_BACKUP:
			case $iOPT_BACKUP_SEARCH:
			case $iOPT_BACKUP_DELETE:
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
						
			case $iOPT_USER_ADD:
			case $iOPT_USER_SEARCH:
			case $iOPT_USER_MODIFY:
			case $iOPT_USER_DELETE:
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
			case $iOPT_RESTRICTED_VEHICLE_CHARGES:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_SEARCH:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_MODIFY:
			case $iOPT_RESTRICTED_VEHICLE_CHARGES_DELETE:
			case $iOPT_DEPARTMENT_ADD:
			case $iOPT_DEPARTMENT_DELETE:
			case $iOPT_DEPARTMENT_SEARCH:
			case $iOPT_DEPARTMENT_MODIFY:
			case $iOPT_DRIVER_MILEAGE:
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
			case $iOPT_NOTICE_ADD:
			case $iOPT_EDIT_RESERVATION:
			case $iOPT_BACKUP:
			case $iOPT_BACKUP_SEARCH:
			case $iOPT_RESTORE:
			case $iOPT_BACKUP_DELETE:
			case $iOPT_LOG_SEARCH:
			case $iOPT_LOG_DELETE:
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
	
			$years	=	substr($mydate, 0, 4);
			$months	=	substr($mydate, 5, 2);
			$days	=	substr($mydate, 8, 2);
		
			$newdate	=	date('l',strtotime($mydate));
			$newdate	=	$newdate."<br />".	$months. "/" . $days . "/" . $years ;
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

function fn_DISPLAY_INFO_LINKS($sLink_Path){
	$sLINK_TEXTS	=	"";
	$sSQL	=	"SELECT link_id, link_title FROM tbl_info_links ORDER BY link_order ASC";
	$rsLINKS		=mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsLINKS)>0){
		while($rowLINK	=	mysql_fetch_array($rsLINKS)){
			$sLINK_TEXTS	.=	 "<tr><td class='left_side_menu'><a href='".$sLink_Path."/info_page.php?id=".$rowLINK['link_id']."'>".stripslashes($rowLINK['link_title'])."</a></td></tr>";
		}
		return $sLINK_TEXTS;
	}else{
		return fn_Print_MSG_BOX("no information link is found in the database", "C_ERROR");
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

function fn_DELETE_TEMP($ssession){
	//before leaving delete previous temp entries
	$sSQL	=	"DELETE FROM tbl_temp_reservations WHERE session = '".$ssession."'";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
}
function fn_CHECK_RESERVATION($ivehicleid, $sstartdate, $senddate, $ssession, $iResID = 0){
	
	$sResSQL	=	"";
	if($iResID!=0)	$sResSQL	=	" AND res_id <> ".$iResID;
	/*$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
	"FROM tbl_reservations WHERE vehicle_id = ".$ivehicleid." AND DATE(planned_depart_day_time) >= '".$sstartdate."' AND ".
	"DATE(planned_return_day_time) <= '".$senddate."' AND reservation_cancelled	=	0	AND (coord_approval = 'Pending' OR coord_approval = 'Approved') ".$sResSQL;*/
	
	$sSQL	=	"SELECT planned_depart_day_time AS Start_Date, planned_return_day_time AS End_Date ".
	"FROM tbl_reservations WHERE vehicle_id = ".$ivehicleid." AND ".
	"(DATE(planned_depart_day_time) >= '".$sstartdate."' OR DATE( planned_return_day_time ) >= '".$sstartdate."') AND ".
	"(DATE(planned_depart_day_time) <= '".$senddate."' OR DATE(planned_return_day_time) <= '".$senddate."') AND reservation_cancelled	=	0 AND cancelled_by_driver =	0 AND (coord_approval = 'Pending' OR coord_approval = 'Approved') ".$sResSQL;
	//print($sSQL);												 
	
	$rsRES	=	mysql_query($sSQL) or die(mysql_error());
	
	//first delete previous temp entries
	$sSQL	=	"DELETE FROM tbl_temp_reservations WHERE session = '".$ssession."'";	$rsTEMP	=	mysql_query($sSQL) or die(mysql_error());
	fn_DELETE_TEMP($ssession);
	
	if(mysql_num_rows($rsRES)>0){
		while($rowRES	=	mysql_fetch_array($rsRES)){
			$iHours	=	0;
			while(strtotime($rowRES['Start_Date']." + ".$iHours." hour") < strtotime($rowRES['End_Date'])){
			
			$sSQL	=	"INSERT INTO tbl_temp_reservations VALUES('".date('Y-m-d H:i:s', strtotime($rowRES['Start_Date']." + ".$iHours." hour"))."', '".$ssession."')";
			//print($sSQL);
			$rsADD_INTERVALS	=	mysql_query($sSQL) or die(mysql_error());
			$iHours++;
			}
			
		}
	}mysql_free_result($rsRES);
//die();
}

function fn_CHECK_RESERVATION_TIME($sCOMPARE_DATE, $sCOMPARE_TIME, $ssession){


	$bRESERVED	=	false;
	$sSQL	=	"SELECT * FROM tbl_temp_reservations WHERE DATE(reservations) = '".$sCOMPARE_DATE."' AND MID(reservations, 12, 2) = '".$sCOMPARE_TIME."' AND session = '".$ssession."'";
	//print($sSQL);
	
	$rsTIME_MATCH	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsTIME_MATCH)>0){
		$bRESERVED	=	true;
	}else{
		$bRESERVED	=	false;
	}mysql_free_result($rsTIME_MATCH);
	
	return $bRESERVED;
	
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

function fn_PRINT_TRIP_SLIP($iRESERVATION_ID){

	$sTRIP_SLIP_TEXT	=	"";
	$iLAST_MILEAGE	=	0;					
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

if(fn_GET_TIME_ZONE()=="America/Los_Angeles"){
	fn_SET_TIME_ZONE();
}


?>