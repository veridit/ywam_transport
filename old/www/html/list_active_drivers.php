<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sStatus		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>Set your pull-down combination first, then preview list, then create &amp; send the msg.<li class='bold-font'>Message must be approved by the Administrator using his 'Edit Automatic Message' function<li class='bold-font'>You must disable your popup blocker to use this function", "C_ERROR");
	$sDriverEmails	=	"";		$sDriverNames	=	"";	
	$iSEL_DRIVER	=	"";
	$sUserType		=	"";
	$iUSER_GROUP	=	0;
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus		=	mysql_real_escape_string($_POST["drpstatus"]);		$sCriteriaSQL	.=	" AND u.active = ".$sStatus;}
		if(isset($_POST["drpusers"]) && $_POST["drpusers"]!="")				{$iSEL_DRIVER	=	mysql_real_escape_string($_POST["drpusers"]);			$sCriteriaSQL	.=	" AND u.l_name = '".$iSEL_DRIVER."'";}
		if(isset($_POST["drpusertype"]) && $_POST["drpusertype"]!="")		{
			$sUserType	=	mysql_real_escape_string($_POST["drpusertype"]);
			if($sUserType=="Non-Staff")
				$sCriteriaSQL	.=	" AND (u.user_type = 'Mission Bldr.' OR u.user_type = 'Student' OR u.user_type = 'Other')";
			else
				$sCriteriaSQL	.=	" AND u.user_type = 'Staff'";
		}
		if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!="")			{$iUSER_GROUP		=	mysql_real_escape_string($_POST["drpusergroup"]);		$sCriteriaSQL	.=	" AND u.user_group = ".$iUSER_GROUP;}
			
		$sSQL	=	"SELECT u.user_id, CONCAT(u.f_name, ' ', u.l_name) AS driver_name, u.email, CASE WHEN active = 0 THEN 'InActive' ELSE 'Active' END AS status, g.group_name ".
		"FROM tbl_user u INNER JOIN tbl_user_group g ON u.user_group = g.group_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY f_name";
		
		//print($sSQL);
		$rsDRIVER_EMAIL		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsDRIVER_EMAIL);
		if($iRECORD_COUNT<=0){	$sMessage		=	fn_Print_MSG_BOX("<li>no user found", "C_ERROR");	}
	}
	

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Driver Emails: Make &amp; Send</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<script type="text/JavaScript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>

<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
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
<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}
function fn_EMAIL(){
	
	//if(confirm('An email will be sent to all drivers listed below..continue..?')==true){
	//alert('adfasdf');
		$.get("ajax_data.php", {action: 'mass-email', mid: '15'}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html("Error in loading Mass Email Message<br /> please contact with Web Admin");
				}else{
						//alert('aaaaa');
					/*$('#txtemail').html(data);*/
					tinyMCE.get('txtemail').setContent(data);
					centerPopup();
					$('#email_box').show();
				}
		}, 'html');
	//}
	//document.frm1.submit();
	
}
function fn_HIDE_EMAIL_BOX(){
	$('#email_box').hide();
}
//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	/*var popupHeight = $("#comment_box").height();
	var popupWidth = $("#comment_box").width();*/
	/*var popupHeight = document.getElementById("comment_box").style.height;
	var popupWidth = document.getElementById("comment_box").style.width;*/
	var popupHeight = 495;
	var popupWidth = 650;
	//centering
	
	document.getElementById("email_box").style.position	=	"fixed";
	$('#email_box').css('top', windowHeight/2-popupHeight/2);
	$('#email_box').css('left', windowWidth/2-popupWidth/2);
	
	//document.getElementById("comment_box").style.position	=	"fixed";
	/*document.getElementById("email_box").style.position	=	"absolute";
	document.getElementById("email_box").style.top	=	windowHeight/2-popupHeight/2;
	document.getElementById("email_box").style.left	=	windowWidth/2-popupWidth/2;*/
	
}

function fn_VALID_EMAIL(sQSTR){
	var sErrMessage='';
	var iErrCounter=0;
	
	
	if (tinyMCE.get('txtemail').getContent()==""){
		sErrMessage=sErrMessage+'<li>please enter email message for driver(s)';
		iErrCounter++;
	}
	
	
	if (iErrCounter >0){
		
		document.getElementById('EmailMessage').style.display	=	'block';
		document.getElementById('EmailMessage').innerHTML="<table width='100%'><tr><td class='Err'>"+sErrMessage+"</td></tr></table>";
	}
	else{
	
		$.get("ajax_data.php", {msgid: '15', action: 'send-mass-email', drpstatus: $('#drpstatus').val(), drpusers: $('#drpusers').val(), drpusertype: $('#drpusertype').val(), drpusergroup: $('#drpusergroup').val(), txtemail: tinyMCE.get('txtemail').getContent()}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html("Error in loading Mass Email Message<br /> please contact with Web Admin");
				}else{
					
					var url='mass_email.php?action=email&sender=<?Php echo $_SESSION["User_ID"];?>&mid=15';
					var myWindow	=	window.open(url,'_blank','height=50, width=50, resizable=no, scrollbars=no');
					
					$('#email_box').hide();
					$('#Message').html("<table width='100%'><tr><td class='Err'><li>Mass Email Process has been started!</td></tr></table>");
				}
		}, 'html');
	
		/*document.frm1.action.value='email';
		document.frm1.submit();*/
	}
}
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">

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
								   				<h1 style="margin-bottom: 0px;">DRIVER EMAILS: MAKE &amp; SEND</h1>
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
						<form name="frm1" action="list_active_drivers.php" method="post">
							<input type="hidden" name="action" value=""	/>
							
							
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="90">Status:<br />
													<?
														$arrSTATUS[0][0]	=	"1";
														$arrSTATUS[0][1]	=	"Active";
														$arrSTATUS[1][0]	=	"0";
														$arrSTATUS[1][1]	=	"InActive";
													?>
													<select name="drpstatus" id="drpstatus" size="1" style="width:80px;">
														<option value="">Any</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrSTATUS[$iCOUNTER][0]?>" <? if($arrSTATUS[$iCOUNTER][0]==$sStatus) echo "selected";?>><?=$arrSTATUS[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td class="label" width="120">Last Name:<br />
												<?
													$sSQL	=	"SELECT l_name, CASE WHEN active = 0 THEN '===IN-ACTIVE==' ELSE '' END AS status FROM tbl_user WHERE user_group = ".$iGROUP_DRIVER." OR user_group = ".$iGROUP_COORDINATOR_STAFF." ORDER BY l_name";
													$rsLNAME	=	mysql_query($sSQL) or die(mysql_error());
													if(mysql_num_rows($rsLNAME)>0){?>
													<select name="drpusers" id="drpusers" size="1" style="width:120px;">
													<option value="">--All--</option>
												<?	while($rowLNAME	=	mysql_fetch_array($rsLNAME)){	?>
														<option value="<? echo $rowLNAME['l_name'];?>" <? if($rowLNAME['l_name'] == $iSEL_DRIVER) echo "selected";?>><? echo $rowLNAME['l_name']." ". $rowLNAME['status'];?></option>
												<?	}?>
													</select>
												<?	}mysql_free_result($rsLNAME);?>
												</td>
												<td class="label" width="100">
													User Type:<br />
													<?
														
														$arrUSER_TYPE[0]	=	"Staff";
														$arrUSER_TYPE[1]	=	"Non-Staff";
														echo "<select name='drpusertype' id='drpusertype' size='1' style='width:100px;'>";
														echo "<option value=''>All User Type</option>";
														for($iCounter=0;$iCounter<=1;$iCounter++){
													?>		<option value="<? echo $arrUSER_TYPE[$iCounter]?>" <? if($sUserType==$arrUSER_TYPE[$iCounter]) echo "selected";?>><? echo $arrUSER_TYPE[$iCounter]?></option>
													<?	}?>
														</select>
												</td>
												<td class="label" width="130">
													Group:<br />
													<?	fn_USER_GROUP('drpusergroup', $iUSER_GROUP, "130", "1", "--All Groups--");?>
												</td>
												<td align="center">
													
													<input type="button" name="btnGO" value=" PREVIEW LIST " class="Button" style="width:150px;" onClick="fn_SEARCH();" />
													<input type="button" name="btnEMAIL" value=" CREATE EMAIL MSG " class="Button" style="width:150px;" onClick="fn_EMAIL();" />
												</td>
											</tr>
											
											<tr>
												<td colspan="4">
													<table width="100%">
														<tr>
															<td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Download Recipent List</td>
															<td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/driver_emails.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download recipent excel list</a>&nbsp;&nbsp;&nbsp;");}?></td>
														</tr>
													</table>
												</td>
											</tr>
											
										</table>
									</td>
								</tr>
								
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Driver_Email,Driver_Name,Status,Group'));
										}
								?>
								<tr>
									<td>
											<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">												
												<tr>
													<td width="200" class="colhead">Email</td>
													<td width="200" class="colhead">Name</td>
													<td width="100" class="colhead">Status</td>
													<td width="200" class="colhead">Group</td>
												</tr>
												<?	$listed	=	0;	
													while($rowDRIVER	=	mysql_fetch_array($rsDRIVER_EMAIL)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowDRIVER["email"].",".$rowDRIVER['driver_name'].",".$rowDRIVER['status'].",".$rowDRIVER['group_name']));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowDRIVER["email"];?></td>
															<td class="coldata"><? echo $rowDRIVER['driver_name'];?></td>
															<td class="coldata"><? echo $rowDRIVER['status'];?></td>
															<td class="coldata"><? echo $rowDRIVER['group_name'];?></td>
														</tr>
											<?			}$listed++;
													}
											?>
											</table>
									</td>
								</tr>
								<?		if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
									}if($iRECORD_COUNT>0)	mysql_free_result($rsDRIVER_EMAIL);
								?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
								<tr>
									<td>
										<div id="email_box" style="width:650px; height:495px; display:none; z-index:5; top:270px; left:450px; position:fixed; background-color:#fff;">
											<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box" height="250">
												<tr><td colspan="2" id="EmailMessage" width="100%"></td></tr>
												
												<tr>
													<td class="label">Mass Email Message:<BR /><BR /><textarea name="txtemail" id="txtemail" cols="50" rows="25" style="height:380px;"  ></textarea></td>
												</tr>
												
												<tr><td>&nbsp;</td></tr>
												<tr><td><input type="button" name="btnSendEmail" value="SEND EMAIL" class="Button" onClick="fn_VALID_EMAIL();" />&nbsp;<input type="button" name="btnCANCEL" value="CANCEL" class="Button" onClick="fn_HIDE_EMAIL_BOX();" /></td></tr>
											</table>
										</div>
									</td>
								</tr>
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
 