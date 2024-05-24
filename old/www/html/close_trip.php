<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage			=	"";
	$iRESERVATION_ID	=	0;
	$iRECORD_COUNT		=	0;
	$iVEHICLE_ID		=	0;
	$iREQUESTOR_NAME	=	"";
	$sASSIGNED_DRIVER	=	"";
	$sHOME_DEPT			=	"";
	$sBILL_DEPT			=	"";
	$sCARD_NO			=	"";
	$iVEHICLE_MILE_COST	=	0;
	
	//==================
	$iABANDON_RECORD_COUNT		=	0;
	$sASSIGNED_ID				=	"";
	$sASSIGNED_NAME				=	"";
	$sASSIGNED_EMAIL			=	"";
	$sABANDON_ASSIGNED_DRIVER	=	"";
	
	$sRESERVED_ID				=	"";
	$sRESERVED_NAME				=	"";
	$sRESERVED_EMAIL			=	"";
	$bCALCULATE_FINE			=	0;
	
	//============
	$sLEADER_NAME				=	"";
	$sLEADER_EMAIL				=	"";
	
	$iABANDON_VEHICLE_MILE_COST	=	0;
	
	$iSECONDS			=	0;
	
	if(isset($_REQUEST["resid"]) && $_REQUEST["resid"]!="")				$iRESERVATION_ID			=	$_REQUEST["resid"];			///normal closing
	
	
	if(isset($_POST["optcloseoption"])	&& $_POST["optcloseoption"]=="close"){
	
			$bProblem	=	0;
			//print("RESERVATION ID==".$iRESERVATION_ID);
									
			if(isset($_POST["chkproblem"]) && $_POST["chkproblem"]!="")				$bProblem	=	"1";	else	$bProblem	=	"0";
				
			$sSQL	=	"SELECT trip_id FROM tbl_trip_details WHERE res_id = ".$iRESERVATION_ID;
			$rsCHKDUPLICATE	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsCHKDUPLICATE)>0){
				$sMessage		=	fn_Print_MSG_BOX("<li>trip already been closed", "C_ERROR");
			}else{
					//check for furture depart day
					$sSQL	=	"SELECT r.planned_depart_day_time FROM tbl_reservations r WHERE res_id = ".$iRESERVATION_ID;
					$sPLANNED_DEPART_DATETIME	=	mysql_result(mysql_query($sSQL),0);
					if(strtotime(date('Y-m-d H:i:s'))> strtotime($sPLANNED_DEPART_DATETIME)){
			
						$iVEHICLE_MILE_COST		=	fn_VEHICLE_PER_MILE_COST($iRESERVATION_ID);		//extract per mile charge for vehicle of current reservations
						
						$sSQL="INSERT INTO tbl_trip_details(res_id, begin_mileage, end_mileage, end_gas_percent, problem, desc_problem, mile_charges, user_id) ".
						"VALUES(".$iRESERVATION_ID.", ".mysql_real_escape_string($_POST["txtbeginmileage"]).", ".mysql_real_escape_string($_POST["txtendmileage"]).", '".mysql_real_escape_string($_POST["drpgas"])."', ".
						" ".$bProblem.", '".mysql_real_escape_string(addslashes($_POST["txtproblem"]))."', ".$iVEHICLE_MILE_COST.", ".$_SESSION["User_ID"].")";
						//print($sSQL);
						$rsTRIP=mysql_query($sSQL) or die(mysql_error());
						
						//get assigned driver for commenting from TM notes
						$sASSIGNED_DRIVER	=	mysql_result(mysql_query("SELECT assigned_driver FROM tbl_reservations WHERE res_id = ".$iRESERVATION_ID),0);
						
						if(isset($_POST["txtTMNotes"]) && $_POST["txtTMNotes"]!=""){	//add into comments table
							$sSQL="INSERT INTO  tbl_user_comments(posting_user_id, about_user_id, comments, trip_id) VALUES(".$_SESSION["User_ID"].", ".$sASSIGNED_DRIVER.", '".mysql_real_escape_string(addslashes($_POST["txtTMNotes"]))."', ".$iRESERVATION_ID.")";
							$rsNOTES	=	mysql_query($sSQL) or die(mysql_error());
						}
						
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>trip has been closed", "C_SUCCESS");
					}else{
						$sMessage		=	fn_Print_MSG_BOX("<li>trip cann't be closed, departure date and time is in future", "C_ERROR");
					}
			}mysql_free_result($rsCHKDUPLICATE);
			
	}elseif(isset($_POST["optcloseoption"])	&& $_POST["optcloseoption"]=="abandon"){		//abandon trip
		
			$sSQL	=	"SELECT abandon_id FROM tbl_abandon_trips WHERE res_id = ".$iRESERVATION_ID;
			$rsCHKDUPLICATE	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsCHKDUPLICATE)>0){
				$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>TRIP ALREADY BEEN MARKED AS ABANDONED", "C_ERROR");
			}else{
				
				$sSQL	=	"SELECT TIMESTAMPDIFF(SECOND, planned_return_day_time, NOW()) FROM tbl_reservations WHERE res_id = ".$iRESERVATION_ID;
				$rsRETURN_CHK	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsRETURN_CHK)>0){
					list($iSECONDS)	=	mysql_fetch_row($rsRETURN_CHK);
					//print("SECONDS====".$iSECONDS);
					if(intval($iSECONDS)<=0){
						$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>return date or time is not been passed, you can't mark this trip as abandon", "C_ERROR");
					}else{
					
						$iVEHICLE_MILE_COST		=	fn_VEHICLE_PER_MILE_COST($iRESERVATION_ID);		//extract per mile charge for vehicle of current reservations
						if(isset($_POST["optmsgoption"]) && $_POST["optmsgoption"]=="26"){					$bCALCULATE_FINE		=	1;}
						
						$sSQL="INSERT INTO tbl_abandon_trips(notes, res_id, user_id, mile_charges, calculate_fine) ".
						"VALUES('".mysql_real_escape_string(addslashes($_POST["txtnotes"]))."', ".$iRESERVATION_ID.", ".$_SESSION["User_ID"].", ".$iVEHICLE_MILE_COST.", ".$bCALCULATE_FINE.")";
						//print($sSQL);
						$rsABANDON=mysql_query($sSQL) or die(mysql_error());
						$sMessage		.=	fn_Print_MSG_BOX("<li class='bold-font'>TRIP HAS BEEN MARKED AS ABANDONED", "C_SUCCESS");
						
						if(isset($_POST["optmsgoption"]) && ($_POST["optmsgoption"]== "19" || $_POST["optmsgoption"]=="26")){		//if checkbox is selected then send an email to assigned driver
					
							$sSQL	=	"SELECT r.assigned_driver AS assigned_id, CONCAT(a.f_name, ', ', a.l_name) AS assigned_name, a.email, r.user_id AS resv_id, ".
							"CONCAT(resv.f_name, ', ', resv.l_name) AS resv_name, resv.email, r.planned_depart_day_time, ".
							"CONCAT(d.leader_f_name,', ', d.leader_l_name) AS leader_name, d.leader_email ".
							"FROM tbl_user a INNER JOIN tbl_reservations r ON a.user_id = r.assigned_driver ".
							"INNER JOIN tbl_user resv ON r.user_id = resv.user_id ".
							"INNER JOIN tbl_departments d ON resv.dept_id = d.dept_id ".
							"WHERE r.res_id = ".$iRESERVATION_ID;
							//print($sSQL);
							$rsASSIGNED_DRIVER	=	mysql_query($sSQL) or die(mysql_error());
							if(mysql_num_rows($rsASSIGNED_DRIVER)>0){
								list($sASSIGNED_ID, $sASSIGNED_NAME, $sASSIGNED_EMAIL, $sRESERVED_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sPLANNED_DEPART_DATETIME, $sLEADER_NAME, $sLEADER_EMAIL)	=	mysql_fetch_row($rsASSIGNED_DRIVER);
							}mysql_free_result($rsASSIGNED_DRIVER);
							
							if($_POST["optmsgoption"]=="26"){
								$sSQL		=	"UPDATE tbl_user SET active = 0, status_date = '".date('Y-m-d H:i:s')."' WHERE user_id = ".$sASSIGNED_ID;
								$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
							}
							
							$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = ".mysql_real_escape_string($_POST["optmsgoption"]);
							//print($sSQL);
							$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
							$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = ".mysql_real_escape_string($_POST["optmsgoption"]);
							//print($sSQL);
							$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
							
							if($sASSIGNED_ID!=$sRESERVED_ID){
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sASSIGNED_NAME, $sASSIGNED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
							}else{
								fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sRESERVED_NAME, $sRESERVED_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
							}
							//SEND CC TO LEADER
							fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sLEADER_NAME, $sLEADER_EMAIL, $sMailMSG, $sEmailSubject, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name);
							
							if($_POST["optmsgoption"]== "19")		$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>TRIP IS MARKED ABANDONED AND EMAIL WARNING MESSAGE HAS BEEN SENT TO DRIVER", "C_SUCCESS");
							elseif($_POST["optmsgoption"]== "26")	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>TRIP IS MARKED ABANDONEDAND NOTICE WITH CHARGE OF 25 MILES HAS BEEN SENT TO DRIVER", "C_SUCCESS");
						}
						
						
					}
				}mysql_free_result($rsRETURN_CHK);
			
			}mysql_free_result($rsCHKDUPLICATE);
		
		
	}
	
	if(!isset($_POST["optcloseoption"]) && $iRESERVATION_ID!=0){
		$sSQL	=	"SELECT tbl_reservations.res_id, tbl_reservations.vehicle_id, ".
		"tbl_reservations.planned_depart_day_time, tbl_reservations.planned_return_day_time, ".
		"tbl_vehicles.vehicle_no, CONCAT(tbl_user.f_name,' ', tbl_user.l_name) AS requestor_name, tbl_reservations.user_id, tbl_reservations.assigned_driver, ".
		"home.dept_name AS home_dept, bill.dept_name AS bill_dept ".
		"FROM tbl_reservations INNER JOIN tbl_vehicles ON tbl_reservations.vehicle_id = tbl_vehicles.vehicle_id ".
		"INNER JOIN tbl_user ON tbl_reservations.user_id = tbl_user.user_id ".
		"INNER JOIN tbl_departments home ON tbl_user.dept_id = home.dept_id ".
		"INNER JOIN tbl_departments bill ON tbl_reservations.billing_dept = bill.dept_id ".
		"LEFT OUTER JOIN tbl_trip_details ON tbl_reservations.res_id = tbl_trip_details.res_id ".
		"LEFT OUTER JOIN tbl_abandon_trips ON tbl_reservations.res_id = tbl_abandon_trips.res_id ".
		"WHERE tbl_reservations.res_id = ".$iRESERVATION_ID." AND tbl_reservations.coord_approval = 'Approved' AND ".
		"reservation_cancelled = 0 AND cancelled_by_driver = 0 ".
		"AND tbl_trip_details.res_id IS NULL ".
		"AND tbl_abandon_trips.res_id IS NULL";
		//print($sSQL);
		$rsRESERVATION	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsRESERVATION)<=0){$sMessage	=		fn_Print_MSG_BOX("<li>Please select valid pending trip or trip has been closed", "C_ERROR");}
		else{
			$iRECORD_COUNT	=	mysql_num_rows($rsRESERVATION);
			$rowRESERVATION	=	mysql_fetch_array($rsRESERVATION);
			$iVEHICLE_ID	=	$rowRESERVATION['vehicle_id'];
			$iREQUESTOR_NAME=	$rowRESERVATION['requestor_name'];
			$sHOME_DEPT		=	$rowRESERVATION['home_dept'];
			$sBILL_DEPT		=	$rowRESERVATION['bill_dept'];
			if($rowRESERVATION['user_id']!=$rowRESERVATION['assigned_driver'] && $rowRESERVATION['assigned_driver']!=0)			$sASSIGNED_DRIVER=	fn_GET_ASSIGNED_DRIVER($rowRESERVATION['assigned_driver']);}
	}
	
function fn_SEND_RESV_ASSIGNED_EMAIL($sPLANNED_DEPART_DATETIME, $iRESERVATION_ID, $sDRIVER_NAME, $sDRIVER_EMAIL, $sMSG, $sSUBJECT, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name){

		global $sMessage;
		$sMailMSG		=	str_replace('#departdate#', fn_cDateMySql($sPLANNED_DEPART_DATETIME,2), str_replace('#resvno#', $iRESERVATION_ID, str_replace('#username#', $sDRIVER_NAME, $sMSG)));
					
		//print("<br />".$sMailMSG);
		$mail = new PHPMailer();
		$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
		$mail->From     = $sSUPPORT_EMAIL;
		$mail->FromName = $sCOMPANY_Name;
		$mail->AddAddress($sDRIVER_EMAIL);
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  $sSUBJECT;
		$mail->Body    	= $sMailMSG;
		if(!$mail->Send()){			   $sMessage		.=	fn_Print_MSG_BOX("Error in Sending Email, $mail->ErrorInfo","C_ERROR");	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Close Trip Slip</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../html/sub_style.css">

<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/popup.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "exact",
		elements : "txtemail",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "styles.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/lists/template_list.js",
        external_link_list_url : "js/lists/link_list.js",
        external_image_list_url : "js/lists/image_list.js",
        media_external_list_url : "js/lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
});
</script>
<script language="JavaScript">
$(document).ready(function(){
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupClose").click(function(){
		disablePopup();
		//fn_draw_ErrMsg('');
		fn_draw_ErrMsg('<li>please correct the Possible Mileage Error!');
		fn_CHANGE_TEXT_BOX_COLOR(document.frm1.txtbeginmileage, '#efc3df', '#ff0000');
		fn_CHANGE_TEXT_BOX_COLOR(document.frm1.txtendmileage, '#efc3df', '#ff0000');
		
	});
	
	$("#popupCancel").click(function(){
		disablePopup();
		document.frm1.submit();
	});

});
		
	function fn_CHECK_MILEAGE(frm){
			
		var sMileageErr	=	"";
		if(valid_trip(frm)){
			sMileageErr	=fn_CHECK_TRIP_MILEAGE(frm);
			if(sMileageErr!=""){
				fn_SHOW_POPUP(sMileageErr);
			}else
				frm.submit();
		}
	}
	
</script>

<script type="text/javascript">
function valid_trip(frm){

	var sErrMessage='';
	var iErrCounter=0;
	var sMileageErr	=	"";
	
	if (frm.resid.value==""){
		sErrMessage='<li>please select pending trip to close';
		iErrCounter++;
	}
	
	if (frm.txtbeginmileage.value == ""){
		sErrMessage=sErrMessage+'<li>please enter beginning mileage';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtbeginmileage, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid beginning mileage';
			iErrCounter++;
		}
	}
	
	if (frm.txtendmileage.value == ""){
		sErrMessage=sErrMessage+'<li>please enter ending mileage';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtendmileage, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid ending mileage';
			iErrCounter++;
		}
	}
	
	if (frm.drpgas.value == ""){
		sErrMessage=sErrMessage+'<li>please select end gas percentage';
		iErrCounter++;
	}
	
		
		
	if (iErrCounter >0 ){
		fn_draw_ErrMsg(sErrMessage);
		return false;
	}else
		return true;
	
}
function ajax_data(rid){
var xmlhttp;
var sData	=	"";
var sURL = "ajax_data.php?";
try
{
		// Firefox, Opera 8.0+, Safari
		xmlhttp=new XMLHttpRequest();
}
catch (e){
	 try {
	  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	 } catch (e) {
		  try {
			   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
			   xmlhttp = false;
			}
	 }
}

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
 xmlhttp.open("GET", sURL+"action=end_miles_gas&rid="+rid+"&t="+Math.random(),true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('loadingimage'))
				document.getElementById('loadingimage').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		//document.frm1.txtlastmileage.value	=	xmlhttp.responseText;
		sData		=	xmlhttp.responseText;
		//alert(sData);
		
		document.frm1.txtlastmileage.value		=	sData.substring(2, sData.indexOf('g='));
		//document.frm1.txtlastmileage.value		=	sData.substring(sData.indexOf('c=')+2, sData.indexOf('g='));
		document.frm1.txtlastendgas.value		=	sData.substring(sData.indexOf('g=')+2,sData.indexOf('d='));
		document.frm1.txtendgasdate.value		=	sData.substring(sData.indexOf('d=')+2,sData.indexOf('r='));
		document.frm1.txthomedept.value			=	sData.substring(sData.indexOf('h=')+2,sData.indexOf('b='));
		document.frm1.txtbilldept.value			=	sData.substring(sData.indexOf('b=')+2,sData.indexOf('c='));
		document.frm1.txtrequestdriver.value	=	sData.substring(sData.indexOf('r=')+2,sData.indexOf('h='));
		if(sData.indexOf('a=')!=-1)	{
			document.frm1.txtassigneddriver.value	=	sData.substring(sData.indexOf('a=')+2,sData.length); document.getElementById('assigned_driver').style.display='block';
		}else{
			document.frm1.txtassigneddriver.value	=	document.frm1.txtrequestdriver.value;	
		}
		//if(sData.indexOf('a=')!=-1)	{document.frm1.txtbilldept.value		=	sData.substring(sData.indexOf('b=')+2,sData.indexOf('a='));} else {document.frm1.txtbilldept.value		=	sData.substring(sData.indexOf('b=')+2,sData.length);}
		if(sData.indexOf('a=')!=-1)	{document.frm1.txtcardno.value		=	sData.substring(sData.indexOf('c=')+2,sData.indexOf('a='));} else {document.frm1.txtcardno.value		=	sData.substring(sData.indexOf('c=')+2,sData.length);}
		
		
		
		document.getElementById('loadingimage').innerHTML = '';
		document.getElementById('loadingimage').style.display = 'none';
	
	}
 }
 xmlhttp.send(null)
}

function fn_CHECK_BIGN_MILES(frm){
	var sMileageErr	=	"";
	
	if(!isNaN(frm.txtlastmileage.value) && !isNaN(frm.txtbeginmileage.value)){
		if(parseInt(frm.txtbeginmileage.value) < parseInt(frm.txtlastmileage.value)){
			sMileageErr	=	"This is less miles than Last End Miles - are you sure?";
			//fn_draw_ErrMsg('<li>'+sMileageErr);
			if(!confirm(sMileageErr))		fn_CHANGE_TEXT_BOX_COLOR(frm.txtbeginmileage, '#efc3df', '#ff0000');
		}
	}
	return sMileageErr;
}

function fn_CHECK_TRIP_MILEAGE(frm){

	var sMileageErr	=	"";

	if(!isNaN(frm.txtbeginmileage.value) && !isNaN(frm.txtendmileage.value)){
		if((parseInt(frm.txtendmileage.value) <= parseInt(frm.txtbeginmileage.value)) || ((parseInt(frm.txtendmileage.value) - parseInt(frm.txtbeginmileage.value)) < 0 )){
			sMileageErr	=	"Possible Error - Mileage is negative";
		}else if(((parseInt(frm.txtendmileage.value) - parseInt(frm.txtbeginmileage.value)) >= 100 )){
			sMileageErr	=	"Possible Error - Charge is over 100 miles - CONTINUE ?";
		}
	}
	
	return sMileageErr;
}
function fn_PRINT_SLIP(iTRIP_ID){
	if(iTRIP_ID==""){
		fn_draw_ErrMsg('<li>Please select Trip to print the slip');
	}else{
		fn_PRINT_TRIP_SLIP(iTRIP_ID, 1);
	}
	
}

function fn_SHOW_BOX(sBOX){
	
	if(sBOX=='close_box'){
		$('#abandon_box').hide();
		$('#Message').html(fn_draw_ErrMsg('<li>if you didn\'t found a pending trip here, please also check abandoned trips'));
		$('#close_pending_trips').html("<img src=../assets/images/loading_busy.gif border='0'>");
		$.get("ajax_data.php", {action: 'close-box'}, function(data){
					  	
			if (data=="ERROR"){
				$('#Message').html("<li class='bold-font'>Error!!! in loading pending trips");
			}else{
				$('#abandon_pending_trips').html('');					
				$('#close_pending_trips').html(data);
			}
		}, 'html');
		
		
	}else{
		$('#close_box').hide();
		$('#Message').html(fn_draw_ErrMsg('<li>Only trips with depart time in the past are available here'));
		$('#abandon_pending_trips').html("<img src=../assets/images/loading_busy.gif border='0'>");
		$.get("ajax_data.php", {action: 'abandon-box'}, function(data){
					  	
			if (data=="ERROR"){
				$('#Message').html("<li class='bold-font'>Error!!! in loading pending trips");
			}else{	
				$('#close_pending_trips').html('');				
				$('#abandon_pending_trips').html(data);
			}
		}, 'html');
	}
	$('#'+sBOX).show();	
}



function fn_LOAD_RESRVD_BY(iRES_ID){
	if(iRES_ID!=""){
		$.get("ajax_data.php", {action: 'load-reserved-by', rid: iRES_ID}, function(data){			  	
					if (data=="ERROR"){
						$('#Message').html("<li class='bold-font'>Error!!! in loading requesting driver and overdue period");
					}else{
						//alert(data);					
						$('#txtabandonrequestdriver').val(data.substring(7, data.indexOf('asgnddrvr=')));
						$('#txtabandonassigneddriver').val(data.substring(data.indexOf('asgnddrvr=')+10, data.indexOf('dept=')));
						$('#txtassigneddriverdept').val(data.substring(data.indexOf('dept=')+5, data.indexOf('overdue=')));
						//$('#txtoverdue').val(data.substring(data.indexOf('overdue=')+8, data.length) + ' Days');
						$('#txtoverdue').val(data.substring(data.indexOf('overdue=')+8, data.indexOf('abandoned=')) + ' Days');
						$('#last-abandoneds').html(data.substring(data.indexOf('abandoned=')+10, data.length-1));
						
					}
		}, 'html');
	}else{
		$('#txtrequestdriver').val('');
	}
}


function valid_abandon_trip(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.resid.value==""){
		sErrMessage='<li>please select trip to abandon';
		iErrCounter++;
	}
	
	var bMSG_SEL = false;
	var sMSG_ID	=	"";
	for (var i=0; i <frm.optmsgoption.length; i++) { 
		if (frm.optmsgoption[i].checked) { 
	   		bMSG_SEL	=	true; 
			sMSG_ID		=	frm.optmsgoption[i].value;
		} 
	}

	if(bMSG_SEL==false){
		sErrMessage=sErrMessage+'<li>please select abandon option';
		iErrCounter++;
	}
	
	/*if (frm.txtnotes.value==""){
		sErrMessage=sErrMessage+'<li>please enter some notes';
		iErrCounter++;
	}*/
				
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
function fn_VIEW_MSG(sMSG_ID){

	/*var sErrMessage='';
	var iErrCounter=0;
	var bMSG_SEL = false;
	var sMSG_ID	=	"";
	for (var i=0; i <frm.optmsgoption.length; i++) { 
		if (frm.optmsgoption[i].checked) { 
	   		bMSG_SEL	=	true; 
			sMSG_ID		=	frm.optmsgoption[i].value;
		} 
	}

	if(bMSG_SEL==false){
		sErrMessage=sErrMessage+'<li>please select message to view';
		iErrCounter++;
	}
	
	if (iErrCounter >0){
		$('#Message').html(fn_draw_ErrMsg(sErrMessage));
	}else{*/
	
		$.get("ajax_data.php", {action: 'mass-email', mid: sMSG_ID}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html(fn_draw_ErrMsg("<li>Error in loading Abandon Warning Message<br /> please contact with Web Admin"));
				}else{
					tinyMCE.get('txtemail').setContent(data);
					centerPopup_msgBox();
					if(sMSG_ID=="19")	$('#box_title').html('Abandon Trip Warning Message:');	else	$('#box_title').html('Abandon Trip Notice W /Charge of 25 Miles:');
					$('#msg_box').show();
				}
		}, 'html');
	//}
	
	
}
function centerPopup_msgBox(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	
	var popupHeight = 509;
	var popupWidth = 650;
	//centering

	document.getElementById("msg_box").style.position	=	"fixed";
	$('#msg_box').css('top', windowHeight/2-popupHeight/2);
	$('#msg_box').css('left', windowWidth/2-popupWidth/2);
	
}

function fn_HIDE_BOX(){
	$('#msg_box').hide();
}

</script>

</head>
<body>
<div align="center">
	
						<form name="frm1" action="close_trip.php" method="post">
							<input type="hidden" name="action" value="addtrip"	/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="700" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td colspan="2" width="100%" align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="45%">
											<tr>
												<td>
												<input type="radio" name="optcloseoption" id="closetrip" value="close" onClick="fn_SHOW_BOX('close_box');" <?Php if(!isset($_POST["optcloseoption"]) && $iRESERVATION_ID!=0) echo "checked";?> /><span class="left label">Close Trip</span>
												</td>
												
												<td><input type="radio" name="optcloseoption" id="abandontrip" value="abandon" onClick="fn_SHOW_BOX('abandon_box');" /><span class="left label">Mark Trip Abandon</span></td>
								</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="close_box" <?Php if(!isset($_POST["optcloseoption"]) && $iRESERVATION_ID!=0) echo "style='display:block;'"; else echo "style='display:none;'";?>>
											<table cellpadding="0" cellspacing="5">
												
											<tr>
												<td width="150" class="label">Reservation:</td>
												<td width="450">
												<div id="close_pending_trips"><?Php if(!isset($_POST["optcloseoption"]) && $iRESERVATION_ID!=0) echo fn_CLOSE_PENDING_TRIPS($iRESERVATION_ID);?></div>
												</td>
											</tr>
											<tr>
												<td class="label">Rsvrd by:</td>
												<td>
													<div style="float:left;"><input readonly="" type="text" name="txtrequestdriver" value="<? if($iREQUESTOR_NAME!="")	echo $iREQUESTOR_NAME;?>"  style="width:150px;"  />&nbsp;&nbsp;&nbsp;</div>
													<div id="assigned_driver"
 style="float:left; <? if($sASSIGNED_DRIVER!="") echo 'display:block'; else echo 'display:none;';?>"><span class="label left">Assgnd Driver:</span>&nbsp;<input readonly="" type="text" name="txtassigneddriver"  value="<? echo $sASSIGNED_DRIVER;?>" style="width:150px;"  /></div>
												</td>
											</tr>
											<tr>
												<td class="label">Home Dept:</td>
												<td><input readonly="" type="text" name="txthomedept" value="<? if ($sHOME_DEPT!='') echo $sHOME_DEPT; ?>" style="width:150px;" />&nbsp;
												<span class="label left">Charge Dept:</span>&nbsp;<input readonly="" type="text" name="txtbilldept" value="<? if ($sBILL_DEPT!='') echo $sBILL_DEPT; ?>" style="width:150px;"  /></td>
											</tr>
											<tr>
												<td class="label">Card No:</td>
												<td><input readonly="" type="text" name="txtcardno" value="<? if ($sCARD_NO!='') echo $sCARD_NO; ?>" style="width:150px;" /></td>
											</tr>
											<tr>
												<td class="label">Beginning Mileage:</td>
												<td><input type="text" name="txtbeginmileage" value="" maxlength="7" style="width:100px; text-align:right;" onBlur="fn_CHANGE_TEXT_BOX_COLOR(this, '#fff', '#000'); fn_CHECK_BIGN_MILES(this.form);"  />&nbsp;
												<span class="label left">Last End Miles</span>&nbsp;<input readonly="" type="text" name="txtlastmileage"  maxlength="6" value="<? if($iVEHICLE_ID!=0) echo fn_VEHICLE_LAST_MILEAGE($iVEHICLE_ID); else echo "";?>" style="width:100px; text-align:right;"  /><span id="loadingimage"></span></td>
											</tr>
											<tr>
												<td class="label">Ending Mileage:</td>
												<td><input type="text" name="txtendmileage" value="" maxlength="7" style="width:100px; text-align:right;"  /></td>
											</tr>
											<tr>
												<td class="label">End Gas Percent:</td>
												<td>
													<?
														$arrGAS[0]	=	"25%";
														$arrGAS[1]	=	"50%";
														$arrGAS[2]	=	"75%";
														$arrGAS[3]	=	"100%";
													?>
													<select name="drpgas" size="1" style="width:100px;">
														<option value="">Gas End Percent</option>
														<?	for($iCounter=0;$iCounter<=3;$iCounter++){?>
														<option value="<?=$arrGAS[$iCounter]?>"><?=$arrGAS[$iCounter]?></option>
														<?	}?>
													</select>
													&nbsp;
													<span class="label left">Last End Gas</span>&nbsp;<input readonly="" type="text" name="txtlastendgas" value="<? if($iVEHICLE_ID!=0) echo fn_VEHICLE_LAST_END_GAS($iVEHICLE_ID); else echo "";?>" style="width:50px; text-align:right;"  />
													&nbsp;&nbsp;&nbsp;
													<span class="label left">Gas Date</span>&nbsp;<input readonly="" type="text" name="txtendgasdate" value="<? if($iVEHICLE_ID!=0) echo fn_VEHICLE_LAST_END_GAS_DATE($iVEHICLE_ID); else echo "";?>" style="width:70px; text-align:right;"  />
												</td>
											</tr>
											<tr>
												<td class="label">Safety Problem:</td>
												<td><input type="checkbox" name="chkproblem" value="1" /><span class="Highlight" style="font-weight:bold;">TM choice based on Driver notes</span></td>
											</tr>
											<tr>
												<td class="label" valign="top">Driver notes about vehicle Only:</td>
												<td>
												<textarea name="txtproblem" id="txtproblem" cols="20" rows="5" style="width:300px;" onKeyDown="fn_char_Counter(this.form.txtproblem,this.form.txtLength,300);" onKeyUp="fn_char_Counter(this.form.txtproblem,this.form.txtLength,300);"></textarea>
												<br /><input readonly type="text" name="txtLength" value="300" style="width:30px;">
												</td>
											</tr>
											<tr>
												<td class="label" valign="top">TM notes about Driver:</td>
												<td><textarea name="txtTMNotes" id="txtTMNotes" cols="20" rows="5" style="width:300px;" ></textarea></td>
											</tr>
											
											<tr><td colspan="2">&nbsp;</td></tr>
											<tr>
												<td></td>
												<td>
													<input type="button" name="btnSUBMIT" value="ADD CLOSING DATA" class="Button" onClick="fn_CHECK_MILEAGE(this.form);" style="width:165px;" />&nbsp;
													<input type="button" name="btnPRINT" value="REPRINT SLIP" class="Button" onClick="fn_PRINT_SLIP(this.form.resid.value);" style="width:110px;" />
												</td>
											</tr>
											</table>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="abandon_box" style="display:none;">
											<table cellpadding="0" cellspacing="5" width="600">
												
												<tr>
													<td width="150" class="label">Reservation:</td>
													<td width="450"><div id="abandon_pending_trips"></div></td>
												</tr>
												<tr>
													<td class="label">Reserved By:</td>
													<td><input readonly="" type="text" id="txtabandonrequestdriver" name="txtabandonrequestdriver" value=""  style="width:150px;"  /></td>
												</tr>
												<tr>
													<td class="label">Trip Assigned To:</td>
													<td><input readonly="" type="text" id="txtabandonassigneddriver" name="txtabandonassigneddriver" value=""  style="width:150px;"  /></td>
												</tr>
												<tr>
													<td class="label">Dept:</td>
													<td><input readonly="" type="text" id="txtassigneddriverdept" name="txtassigneddriverdept" value=""  style="width:150px;"  /></td>
												</tr>
												<tr>
													<td class="label">Trips abandoned by the assigned driver in last 60 days:</td>
													<td><div id="last-abandoneds"></div></td>
												</tr>
												<tr>
													<td class="label">Over Due:</td>
													<td><input readonly="" type="text" id="txtoverdue" name="txtoverdue" value=""  style="width:150px;"  /></td>
												</tr>
												<tr>
													<td class="label" valign="top">TM note:<br /><div class='Highlight' style="font-size:13px;">These notes will only recorded in this system, they will not sent to the driver</div></td>
													<td>
														<textarea name="txtnotes" id="txtnotes" cols="75" rows="7" style="width:425px;"></textarea>
														
													</td>
												</tr>
												<tr>
													<td class="label" valign="top"></td>
													<td class="label">
														<input type="radio" name="optmsgoption" id="withoutmsg" value="0" /><span class="left label">Mark abandoned without sending message</span>
													</td>
												</tr>
												<tr>
													<td class="label" valign="top"></td>
													<td class="label">
														<input type="radio" name="optmsgoption" id="warningmsg" value="19" />
														<span class="left label">Abandoned & Send warning message</span>&nbsp;&nbsp;&nbsp;
														<input type="button" name="btnMSG" value="VIEW WARNING MSG" class="Button" onClick="fn_VIEW_MSG('19');" style="width:150px;" />
														<br /><br />
														<span class="Highlight">(cc will goto school leader)</span>
													</td>
												</tr>
												
												<tr>
													<td class="label" valign="top"></td>
													<td class="label">
														<input type="radio" name="optmsgoption" id="chargemsg" value="26" />
														<span class="left label">Abandoned, Send notice & suspend permit</span>
														<input type="button" name="btnMSG" value="VIEW NOTICE" class="Button" onClick="fn_VIEW_MSG('26');" style="width:100px;" />
														<br /><br />
														<span class="Highlight">(cc will goto school leader)</span>
													</td>
												</tr>

												<tr><td colspan="2">&nbsp;</td></tr>
												<tr>
													<td></td>
													<td>
														<input type="button" name="btnSUBMIT" value="GO" class="Button" onClick="valid_abandon_trip(this.form);" style="width:150px;" />&nbsp;&nbsp;
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td align="center"><input type="button" name="btnBACK" value="RETURN TO LIST" onClick="location.href='list_pending_report.php'" class="Button" style="width:170x;" /></td></tr>
								<tr>
									<td colspan="2">
										<div id="msg_box" style="width:650px; height:509px; display:none; z-index:5; top:270px; left:450px; position:fixed; background-color:#fff;">
											<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box" height="250">
												<tr><td colspan="2" id="EmailMessage" width="100%"></td></tr>
												
												<tr>
													<td class="label"><div id="box_title">Abandon Trip Warning:</div><BR /><BR /><textarea name="txtemail" id="txtemail" cols="50" rows="25" style="height:380px;"  ></textarea></td>
												</tr>
												
												<tr><td>&nbsp;</td></tr>
												<tr><td><input type="button" name="btnCANCEL" value="CLOSE" class="Button" onClick="fn_HIDE_BOX();" /></td></tr>
											</table>
										</div>
									</td>
								</tr>
								
							</table>
						</form>
                	
 </div>
</body>
</html>
<div id="popupContact">
	<div id="contactArea" style="padding-left:10px;"></div>
	<br /><br />
	<div style="text-align:center; width:100%; margin:0 auto;">
		<input type="button" name="btnclose" value="YES" class="Button" id="popupCancel" style="width:100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="btnclose" value="NO" class="Button" id="popupClose" style="width:100px;" />
		
	</div>
	<br /><br />
</div>
<div id="backgroundPopup"></div>