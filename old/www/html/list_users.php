<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');
	require("class.phpmailer.php");

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	$iDeptID		=	0;
	$iGroupID		=	0;
	$sUserType		=	"";
	$bNEW_USER		=	0;
	$sCriteriaSQL	=	"";
	$sTMSQL			=	"";
	$iRECORD_COUNT	=	0;
	//$sUSER_NAME		=	"";
	$iDRIVER		=	0;
	$sSORT_ORDER	=	"u.l_name ASC";
	$sMessage		=	"";
	
	
	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Driver details can be edited or their permits activated, renewed or deactivated by clicking on View in the action column.", "C_SUCCESS");;
	}
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delusers"){	///DEL USER PROCESS
	
		$iDEL_USERS	=	explode(";",$_REQUEST["userid"]);
		for($iUSER_COUNTER=0; $iUSER_COUNTER<=count($iDEL_USERS)-1; $iUSER_COUNTER++){
			
			$sSQL		=	"DELETE FROM tbl_log WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsDEL_LOG	=	mysql_query($sSQL) or die(mysql_error());
				
			$sSQL		=	"DELETE FROM tbl_vehicles WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"DELETE FROM tbl_shop_tasks WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsDEL_SHOP	=	mysql_query($sSQL) or die(mysql_error());
			
			$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsRES	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsRES)>0){
				while($rowRES	=	mysql_fetch_array($rsRES)){
					$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowRES["res_id"];
					$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
					$sSQL		=	"DELETE FROM tbl_abandon_trips WHERE res_id = ".$rowRES['res_id'];
					$rsDEL_ABANDON	=	mysql_query($sSQL) or die(mysql_error());
				}
				$sSQL	=	"DELETE FROM tbl_reservations WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
				$rsDEL_RES	=	mysql_query($sSQL) or die(mysql_error());
				
				$sSQL	=	"DELETE FROM tbl_reservations WHERE assigned_driver = ".$iDEL_USERS[$iUSER_COUNTER];
				$rsDEL_DRIVER	=	mysql_query($sSQL) or die(mysql_error());
			}
		//DELETE NOTES OF THE SELECTED USER
			$sSQL	=	"DELETE FROM tbl_user_comments WHERE about_user_id = ".$iDEL_USERS[$iUSER_COUNTER]." OR posting_user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsDEL_COMMENTS	=	mysql_query($sSQL) or die(mysql_error());
		
			$sSQL		=	"DELETE FROM tbl_user WHERE user_id = ".$iDEL_USERS[$iUSER_COUNTER];
			$rsDEL_USER	=	mysql_query($sSQL) or die(mysql_error());
			$sMessage		=	fn_Print_MSG_BOX("<li>pending application(s) deleted successfully", "C_SUCCESS");
			
		}
		
		
		
		
	}
	
	if(isset($_POST["action"])	&& ($_POST["action"]=="DA" || $_POST["action"]=="A")){
		if($_POST["action"]=="DA"){
			$sSQL	=	"UPDATE tbl_user SET active = 0 WHERE user_id = ".$_POST["userid"];
			$rsDEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
			$sMessage		=	fn_Print_MSG_BOX("user deactivated successfully", "C_SUCCESS");
		}else{
			$sSQL	=	"UPDATE tbl_user SET active = 1, new_user = 0 WHERE user_id = ".$_POST["userid"];
			$rsDEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
			//$sMessage		=	fn_Print_MSG_BOX("user activated successfully", "C_SUCCESS");
			
			//=====================EMAIL PROCESS
				$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 14";
				$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
				$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 14";
				$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
				
				//extract user level, username, group leve
				$sSQL			=	"SELECT email, password, group_name FROM tbl_user u INNER JOIN tbl_user_group ug ON u.user_group = ug.group_id WHERE u.user_id = ".$_POST["userid"];
				$rsUSER_STATE	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsUSER_STATE)>0){
					list($sEMAIL, $sPASSWORD, $sUSER_GROUP)	=	mysql_fetch_row($rsUSER_STATE);
				}mysql_free_result($rsUSER_STATE);
				
				
				$sMailMSG		=	str_replace('#USER LEVEL#', $sUSER_GROUP, str_replace('#PASSWORD#', $sPASSWORD, str_replace('#USERNAME#', $sEMAIL, $sMailMSG)));
				//$sMessage		=	fn_Print_MSG_BOX($sMailMSG,"C_ERROR");
				
				//print($sMailMSG);
				$mail = new PHPMailer();
				$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
				$mail->From     = $sSUPPORT_EMAIL;
				$mail->FromName = $sCOMPANY_Name;
				$mail->AddAddress($sEMAIL);
				$mail->IsHTML(true);                               // send as HTML
				$mail->Subject  =  $sEmailSubject;
				$mail->Body    = $sMailMSG;
				if(!$mail->Send()){
				   $sMessage		=	fn_Print_MSG_BOX("<li>User has been activated, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
				}else{
					$sMessage		=	fn_Print_MSG_BOX("<li>User has been activated, <br />an email has been sent to his/her address", "C_SUCCESS");
				}
			
			
		}
		
		
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!="")		{$iGroupID	=	$_POST["drpusergroup"];		$sCriteriaSQL	.=	" AND u.user_group = ".$iGroupID;}
		if(isset($_POST["drpusertype"]) && $_POST["drpusertype"]!="")		{$sUserType	=	$_POST["drpusertype"];		$sCriteriaSQL	.=	" AND u.user_type = '".$sUserType."'";}
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				{$iDeptID	=	$_POST["drpdept"];			$sCriteriaSQL	.=	" AND u.dept_id= ".$iDeptID;}
		if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	$_POST["drpstatus"];		$sCriteriaSQL	.=	" AND u.active = ".$sStatus;}
		//if(isset($_POST["drplname"]) && $_POST["drplname"]!="")				{$sUSER_NAME=	$_POST["drplname"];			$sCriteriaSQL	.=	" AND (u.l_name = '". substr($sUSER_NAME,0,strpos($sUSER_NAME,' ',0))."' OR u.f_name = '". substr($sUSER_NAME,strpos($sUSER_NAME,' ',0)+1)."')";}
		if(isset($_POST["drplname"]) && $_POST["drplname"]!="")				{$iDRIVER	=	$_POST["drplname"];			$sCriteriaSQL	.=	" AND u.user_id = ".$iDRIVER;}
		if(isset($_POST["chkNew"]) &&	$_POST["chkNew"]!="")				{$bNEW_USER	=	1;							$sCriteriaSQL	.=	" AND u.new_user = 1 ";}
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")				{$sSORT_ORDER		=	$_POST["drpsort"];	}
		if($_SESSION["User_Group"]==$iGROUP_TM)			$sTMSQL	=	" AND u.user_group <> ".$iGROUP_TC;
		
		
		
		$sSQL	=	"SELECT u.user_id, u.f_name, u.l_name, u.email, u.user_type, u.drive_tested, u.phone, u.birth_date, u.license_no, u.license_state, u.drive_tested, u.test_date, u.home_st_country, u.permit_type, u.renew_date, u.renew_text,  ".
		"CASE WHEN u.active = 1 THEN 'Active' ELSE 'InActive' END AS status, dept_name, u.dept_id, group_name, u.reg_date, u.new_user, ".
		"CASE WHEN u.driver_permission = 1 THEN 'YES' ELSE 'NO' END driver_permission, ".
		"CASE WHEN MAX(login_datetime) IS NULL THEN 'NEVER LOGGED IN' ELSE MAX(login_datetime) END AS last_login, ".
		"CASE WHEN u.status_date IS NULL THEN 'N/A' ELSE u.status_date END AS status_ch_date ".
		"FROM tbl_user u ".
		"INNER JOIN tbl_departments ON u.dept_id = tbl_departments.dept_id ".
		"INNER JOIN tbl_user_group ON u.user_group = tbl_user_group.group_id ".
		"LEFT OUTER JOIN tbl_log l ON u.user_id = l.user_id ".
		"WHERE 1=1 ".$sCriteriaSQL.$sTMSQL." ".
		"GROUP BY u.user_id, u.f_name, u.l_name, u.email, u.user_type, u.drive_tested, u.phone, u.birth_date, ".
		"u.license_no, u.license_state, u.drive_tested, u.test_date, u.home_st_country, u.permit_type, u.renew_date, u.renew_text, u.active, ".
		"dept_name, u.dept_id, group_name, u.reg_date, u.new_user, u.driver_permission ".
		"ORDER BY ".$sSORT_ORDER;
		
		//print($sSQL);
		$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsUSERS);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no user found", "C_ERROR");
		}
	}
	

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>
<?Php	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
		echo "ACTIVATE, RENEW, DEACTIVATE PERMITS";
		}else{
		echo "DRIVERS LIST";
		}
?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="generator" content="Bluefish 2.2.10" >

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>


<script type="text/javascript">

function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}

function fn_DELETE_USER(iUSERID, sSTATUS){

	document.frm1.userid.value=iUSERID;
	//document.frm1.action.value='delete';
	document.frm1.action.value=sSTATUS;
	document.frm1.submit();
}


function fn_DELETE_APPLICANTS(){

	var sErrMessage='';

	if (fn_Assign_Values()!=''){
		
		document.frm1.action.value	="delusers";
		document.frm1.userid.value	=fn_Assign_Values();	
		document.frm1.submit();
	}
	else{
		sErrMessage='<li>please select pending application(s) to delete';
		fn_draw_ErrMsg(sErrMessage);
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
				if (chkboxValues	==	'')	chkboxValues	=	chkUser[i].value.substring(chkUser[i].value.search(';')+1,chkUser[i].value.length);
				else chkboxValues	=	chkboxValues+';'+chkUser[i].value.substring(chkUser[i].value.search(';')+1,chkUser[i].value.length);

		}					
	}
else
	if (chkUser.checked){
		
		bChecked		=	true;
		
		if (bChecked)
			if (chkboxValues	==	'')	chkboxValues	=	chkUser.value.substring(chkUser.value.search(';')+1,chkUser.value.length);
			else chkboxValues	=	chkboxValues+';'+chkUser.value.substring(chkUser.value.search(';')+1,chkUser.value.length);

	}
	

if(!bChecked)
	return '';
else
	return chkboxValues;

}
function fn_VALID_COMMENTS(){
	var sErrMessage="";
	var iErrCounter=0;
	if(document.frm1.txtcomments.value==""){
		sErrMessage='<li>please enter notes';
		iErrCounter++;
	}
	if (iErrCounter >0){
		document.getElementById('CommentMessage').style.display	=	'block';
		document.getElementById('CommentMessage').innerHTML="<table width='100%'><tr><td class='Err'>"+sErrMessage+"</td></tr></table>";
	}else{
		document.frm1.action.value='comments';
		document.frm1.submit();
	}
}

function fn_SHOW_HIDE_COMMENT_BOX(iUserID, bDisplay){
/*var str	=	"";
var elem = document.frm1.elements;
        for(var i = 0; i < elem.length; i++)
        {
            str += "<b>Type:</b>" + elem[i].type + "&nbsp&nbsp";
            str += "<b>Name:</b>" + elem[i].name + "&nbsp;&nbsp;";
            str += "<b>Value:</b><i>" + elem[i].value + "</i>&nbsp;&nbsp;";
            str += "<BR>";
        } 
		document.getElementById('Message').innerHTML="<table width='100%'><tr><td class='Err'>"+str+"</td></tr></table>";*/
		centerPopup();
	document.frm1.userid.value=iUserID;
	document.frm1.txtcomments.value="";
	document.getElementById('comment_box').style.display	=	bDisplay;
	document.getElementById('CommentMessage').style.display	=	'none';
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	
	var popupHeight = 250;
	var popupWidth = 400;
	//centering
	
	//document.getElementById("comment_box").style.position	=	"fixed";
	document.getElementById("comment_box").style.position	=	"absolute";
	document.getElementById("comment_box").style.top	=	windowHeight/2-popupHeight/2;
	document.getElementById("comment_box").style.left	=	windowWidth/2-popupWidth/2;
	
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
								   				<h1 style="margin-bottom: 0px;">
												<?Php	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){
															echo "ACTIVATE, RENEW, DEACTIVATE PERMITS";
														}else{
															echo "DRIVERS LIST";
														}
												?>
												</h1>
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
						<form name="frm1" action="list_users.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="userid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="140">User Group:<br /><?	fn_USER_GROUP('drpusergroup', $iGroupID, "140", "1", "All Groups");?></td>
												<td class="label" width="100">User Type:<br /><?	fn_USER_TYPE('drpusertype', $sUserType, "100", "1", "All User Type");?></td>
												<td class="label" width="100">Users:<br />
												<?	fn_DISPLAY_USERS('drplname', $iDRIVER, "160", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_SERVICETCH);?>
												
												</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td class="label" width="150">Department:<br /><?	fn_DEPARTMENT('drpdept', $iDeptID, "150", "1", "All Departments");?></td>
												
												<td class="label" width="80">Status:<br />
													<?
														$arrSTATUS[0][0]	=	"1";
														$arrSTATUS[0][1]	=	"Active";
														$arrSTATUS[1][0]	=	"0";
														$arrSTATUS[1][1]	=	"InActive";
													?>
													<select name="drpstatus" size="1" style="width:80px;">
														<option value="">Any</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrSTATUS[$iCOUNTER][0]?>" <? if($arrSTATUS[$iCOUNTER][0]==$sStatus) echo "selected";?>><?=$arrSTATUS[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:100px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="l_name ASC" 		<? if($sSORT_ORDER == "l_name ASC") echo "selected";?>>Last Name A-Z</option>
														<option value="l_name DESC" 	<? if($sSORT_ORDER == "l_name DESC") echo "selected";?>>Last Name Z-A</option>	
														<option value="dept_name ASC" 	<? if($sSORT_ORDER == "dept_name ASC") echo "selected";?>>Dept. Name A-Z</option>
														<option value="dept_name DESC"  <? if($sSORT_ORDER == "dept_name DESC") echo "selected";?>>Dept. Name Z-A</option>
														<option value="email ASC" 		<? if($sSORT_ORDER == "email ASC") echo "selected";?>>Email A-Z</option>
														<option value="email DESC"  	<? if($sSORT_ORDER == "email DESC") echo "selected";?>>Email Z-A</option>
														<option value="reg_date ASC"  	<? if($sSORT_ORDER == "reg_date ASC") echo "selected";?>>Reg.Date A-Z</option>
														<option value="reg_date DESC"  	<? if($sSORT_ORDER == "reg_date DESC") echo "selected";?>>Reg.Date Z-A</option>
													</select>
												</td>
												<td width="50">
													<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /><br /><br />													
												</td>
											</tr>
											<tr>
												<td colspan="3"class="label"><input type="checkbox" name="chkNew" value="1" <?Php if($bNEW_USER==1) echo "checked";?> />Find new applications&nbsp;&nbsp;&nbsp;&nbsp;
												<span class="Highlight" style="font-weight:bold;">	To find NEW and renewal applications that need to be activated, check this box and click on the GO button </span>
												
												</td>
												<td>&nbsp;</td>
											</tr>
											
											<tr>
												<td colspan="5">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
																<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_users.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
																		//http://transportation.uofnkona.edu/html/download.php?f=excel_reports/list_users.csv&Dir=./excel_reports/
																	}?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Dept_Name,F_Name,L_Name,Status,Status_Ch_Date,Group,User_Type,Reg_Date,Last_Login'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','User_ID,F_Name,L_Name,Dept_ID,Dept_Name,Phone,Birth_Date,License_No,License_State,Email,Tested_By,Date_Tested,Home_Country,Active,Group,User_Type,Register_Date,Permit,New,Renew_Date,Renew_Text,Reports,Last_Login'));}		
										}
								?>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>												
												<td width="130" class="colhead">Dept Name</td>
												<td width="100" class="colhead">Name</td>
												<td width="40" class="colhead">Status</td>
												<td width="80" class="colhead">Status Ch Date</td>
												<td width="80" class="colhead">Group</td>
												<td width="50" class="colhead">Type</td>
												<td width="45" class="colhead">Reg Date</td>
												<td width="75" class="colhead">Last Login</td>
												<?Php	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?>
												<td width="50" class="colhead" align="center">Act.</td>
												<?Php	}?>
												
											</tr>
											<?		$listed	=	0;	
													while($rowUSER	=	mysql_fetch_array($rsUSERS)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowUSER["dept_name"].",".$rowUSER["f_name"].",".$rowUSER["l_name"].",".$rowUSER["status"].",".$rowUSER["status_ch_date"].",".$rowUSER["group_name"].",".$rowUSER["user_type"].",".$rowUSER["reg_date"].",".$rowUSER["last_login"]));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowUSER["user_id"].",".$rowUSER["f_name"].",".$rowUSER["l_name"].",".$rowUSER["dept_id"].",".$rowUSER["dept_name"].",".$rowUSER["phone"].",".$rowUSER["birth_date"].",".$rowUSER["license_no"].",".$rowUSER["license_state"].",".$rowUSER["email"].",".$rowUSER["drive_tested"].",".$rowUSER["test_date"].",".$rowUSER["home_st_country"].",".$rowUSER["status"].",".$rowUSER["group_name"].",".$rowUSER["user_type"].",".$rowUSER["reg_date"].",".$rowUSER["permit_type"].",".$rowUSER["new_user"].",".$rowUSER["renew_date"].",".stripslashes($rowUSER["renew_text"].",".$rowUSER["driver_permission"].",".$rowUSER["last_login"])));}
														}
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata"><? echo $rowUSER['dept_name'];?></td>
															<td class="coldata"><? echo $rowUSER['f_name']." ".$rowUSER['l_name'];?></td>
															<td class="coldata"><? echo $rowUSER['status'];?></td>
															<td class="coldata"><? if($rowUSER['status_ch_date']!='N/A') echo fn_cDateMySql($rowUSER['status_ch_date'],2); else echo $rowUSER['status_ch_date'];?></td>
															<td class="coldata"><? echo $rowUSER['group_name'];?></td>
															<td class="coldata"><? echo $rowUSER['user_type'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowUSER['reg_date'], 1);?></td>
															<td class="coldata"><? if($rowUSER['last_login']!='NEVER LOGGED IN') echo fn_cDateMySql($rowUSER['last_login'],2); else echo 'NEVER LOGGED IN';?></td>
															<?Php	if($_SESSION["User_Group"]!=$iGROUP_DRIVER && $_SESSION["User_Group"]!=$iGROUP_COORDINATOR_STAFF){?>
															<td class="coldata" align="center">
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_MODIFY)){?><a href="edit_user.php?userid=<? echo $rowUSER['user_id'];?>" title="VIEW">View</a><?	}?>
															</td>
															<?Php	}?>
														</tr>
											<?			}$listed++;
													}
											?>
										</table>
									</td>
								</tr>
								<?Php
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
									}	if($iRECORD_COUNT>0)	mysql_free_result($rsUSERS);	
								?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
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
 