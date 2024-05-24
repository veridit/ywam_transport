<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li>Notices will sent to those who have used the system in the last 4 months", "C_SUCCESS");
	$sAction		=	"";
	
	
	$sSQL	=	"SELECT u.user_id, u.dept_id, d.dept_name, u.f_name, u.l_name, u.phone, u.email, g.group_name, u.user_type, reservations.reg_date, ".
	"CASE WHEN MAX(ul.login_datetime) IS NULL THEN 'NEVER LOGGED IN' ELSE MAX(login_datetime) END AS last_login ".
	"FROM tbl_user u  INNER JOIN ".
	"(SELECT r.user_id, r.res_id, r.reg_date FROM tbl_reservations r WHERE TIMESTAMPDIFF(MONTH, r.reg_date, CURDATE()) >= 4 GROUP BY r.user_id) reservations ".
	"ON u.user_id = reservations.user_id ".
	"INNER JOIN tbl_departments d ON u.dept_id = d.dept_id ".
	"INNER JOIN tbl_user_group g ON u.user_group = g.group_id ".
	"LEFT OUTER JOIN tbl_log ul ON u.user_id = ul.user_id ".
	"WHERE u.active = 1 AND user_group IN (".$iGROUP_DRIVER.", ".$iGROUP_SERVICETCH.", ".$iGROUP_COORDINATOR_STAFF.") ".
	"GROUP BY u.user_id, u.dept_id, d.dept_name, u.f_name, u.l_name, u.phone, u.email, g.group_name, u.user_type ORDER BY u.l_name";
	
	
	//print($sSQL);
	$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsUSERS);
	if($iRECORD_COUNT<=0){
		$sMessage		=	fn_Print_MSG_BOX("<li>no user found", "C_ERROR");
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Drivers: Year End Notice</title>
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
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_EXCEL(){
	document.frm1.excel.value='excel';
	document.frm1.submit();
}
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}

function fn_VIEW_NOTICE(){
	
	//if(confirm('An email will be sent to all drivers listed below..continue..?')==true){
	//alert('adfasdf');
		$.get("ajax_data.php", {action: 'mass-email', mid: '22'}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html("Error in loading Deactivation Notice Message<br /> please contact with Web Admin");
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

function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	
	var popupHeight = 495;
	var popupWidth = 650;
	//centering
	
	//document.getElementById("comment_box").style.position	=	"fixed";
	document.getElementById("email_box").style.position	=	"absolute";
	document.getElementById("email_box").style.top	=	windowHeight/2-popupHeight/2;
	document.getElementById("email_box").style.left	=	windowWidth/2-popupWidth/2;
	
}
function fn_SEND_NOTICE(sNOTICE_TXT){

	var sErrMessage='';
	var iErrCounter=0;
	
	
	/*if (sNOTICE_TXT==""){
		sErrMessage=sErrMessage+'<li>please enter deactivation notice for driver(s)';
		iErrCounter++;
	}*/
	
	
	if (iErrCounter >0){
		document.getElementById('Message').innerHTML="<table width='100%'><tr><td class='Err'>"+sErrMessage+"</td></tr></table>";
	}
	else{
	
		$.get("ajax_data.php", {msgid: '22', action: 'send-deactivation-notice', txtemail: sNOTICE_TXT}, function(data){
			$('#email_box').hide();
			$('#Message').html("<table width='100%'><tr><td'><img src=../assets/images/loading_busy.gif border='0'></td></tr></table>");
			
				if (data!="SUCCESS"){
					//$('#Message').html("Error in loading Mass Email Message<br /> please contact with Web Admin");
					$('#Message').html(data);
				}else{
					$('#Message').html("<table width='100%'><tr><td class='Err'><li>Deactivation Notice has been sent to following Users!</td></tr></table>");
				}
		}, 'html');
	
		/*document.frm1.action.value='email';
		document.frm1.submit();*/
	}
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
								   				<h1 style="margin-bottom: 0px;">DRIVERS: YEAR END NOTICE</h1>
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
						<form name="frm1" action="deactivate_drivers.php" method="post">
							<input type="hidden" name="action" value="<? echo $sAction;?>"	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<input type="hidden" name="excel" value="" />
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td><input type="button" name="btnVIEW" value=" VIEW-SEND NOTICE " class="Button" style="width:150px;" onClick="fn_VIEW_NOTICE();" />
												&nbsp;<input type="button" name="btnExcel" value="DOWNLOAD EXCEL FILE" class="Button" onClick="fn_EXCEL();" />
												<? 	if(isset($_POST["excel"]) && ($_POST["excel"]	==	"excel") && $iRECORD_COUNT>0){
														$sFname	=	'excel_reports/deactive_notifications.csv'; print("<br /><a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
												}?>
												</td>
											</tr>
											
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){
								
										if (isset($_POST["excel"]) && $_POST["excel"]	==	"excel"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','User_ID,Dept_Name,F_Name,L_Name,Last_Login,Group,Email'));
										}	
										
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead leftcol">User ID</td>
												<td width="100" class="colhead">Dept Name</td>
												<td width="50" class="colhead">F Name</td>
												<td width="50" class="colhead">L Name</td>
												<td width="70" class="colhead">Last Login</td>
												<td width="70" class="colhead">Group</td>
												<td width="130" class="colhead">Email</td>
											</tr>
											<?		$listed	=	0;	
													while($rowUSER	=	mysql_fetch_array($rsUSERS)){
														if (isset($_POST["excel"]) && $_POST["excel"]	==	"excel"){
															fputcsv($fp, explode(',', $rowUSER['user_id'].",".$rowUSER['dept_name'].",".$rowUSER['f_name'].",".$rowUSER['l_name'].",".$rowUSER['last_login'].",".$rowUSER['group_name'].",".$rowUSER['email']));
														}
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata"><? echo $rowUSER['user_id'];?></td>
															<td class="coldata"><? echo $rowUSER['dept_name'];?></td>
															<td class="coldata"><? echo $rowUSER['f_name'];?></td>
															<td class="coldata"><? echo $rowUSER['l_name'];?></td>
															<td class="coldata"><? if($rowUSER['last_login']!='NEVER LOGGED IN') echo fn_cDateMySql($rowUSER['last_login'],2); else echo 'NEVER LOGGED IN';?></td>
															<td class="coldata"><? echo $rowUSER['group_name'];?></td>
															<td class="coldata"><? echo $rowUSER['email'];?></td>
														</tr>
											<?			}$listed++;
													}
											?>
									
										</table>
									</td>
									
								</tr>
								
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								<?	}mysql_free_result($rsUSERS);	?>
								<tr>
									<td>
										<div id="email_box" style="width:650px; height:495px; display:none; z-index:5; top:270px; left:450px; position:fixed; background-color:#fff;">
											<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box" height="250">
												<tr><td colspan="2" id="EmailMessage" width="100%"></td></tr>
												
												<tr>
													<td class="label">Deactivation Notice:<BR /><BR /><textarea name="txtemail" id="txtemail" cols="50" rows="25" style="height:380px;"  ></textarea></td>
												</tr>
												
												<tr><td>&nbsp;</td></tr>
												<tr><td><input type="button" name="btnSendEmail" value="SEND NOTICE" class="Button" onClick="fn_SEND_NOTICE(tinyMCE.get('txtemail').getContent());" />&nbsp;<input type="button" name="btnCANCEL" value="CANCEL" class="Button" onClick="fn_HIDE_EMAIL_BOX();" /></td></tr>
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
 