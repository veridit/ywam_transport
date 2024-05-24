<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_redirect.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=		fn_Print_MSG_BOX("<li>This is the popup message that every driver & coordinator sees <br />when they login and before they can use the system<li>if you want to delete the notice you just need to make empty Title and Notice fields", "C_SUCCESS");
	if(isset($_POST["action"])	&& $_POST["action"]=="addnotice"){
								
		$sSQL="UPDATE  tbl_special_notice SET notice_date = '".date('Y-m-d h:i:s')."', user_id = ".$_SESSION["User_ID"].", notice_title = '".addslashes($_POST["txttitle"])."', notice = '".addslashes($_POST["txtinfo"])."' WHERE notice_id= 1";
		//print($sSQL);
		$rsNOTICE=mysql_query($sSQL) or die(mysql_error());
		
		$sMessage		=	fn_Print_MSG_BOX("special notice is updated successfully", "C_SUCCESS");
	}
	
	$sSQL	=	"SELECT notice_id, DATE_FORMAT(notice_date, '%m/%d/%Y %r') AS notice_date, notice_title, notice, CONCAT(f_name,' ', l_name) AS user_name ".
	"FROM tbl_special_notice INNER JOIN tbl_user ON tbl_special_notice.user_id = tbl_user.user_id WHERE notice_id = 1";
	$rsNOTICE	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsNOTICE)>0){
		$rowNOTICE	=	mysql_fetch_array($rsNOTICE);
	}mysql_free_result($rsNOTICE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Login Message</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<!--<script type="text/javascript">
function valid_link(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txttitle.value==""){
		sErrMessage='<li>please enter notice title';
		iErrCounter++;
	}
	if (tinyMCE.get('txtinfo').getContent()==""){
		sErrMessage=sErrMessage+'<li>please enter notice';
		iErrCounter++;
	}
	
	
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
</script>-->
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
								   				<h1 style="margin-bottom: 0px;">LOGIN MESSAGE</h1>
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
						<form name="frm1" action="add_notice.php" method="post">
							<input type="hidden" name="action" value="addnotice"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								
								<tr><td id="Message" width="100%" colspan="2"><?=$sMessage?></td></tr>
								
								<tr>
									<td class="label" width="150">Last Updated by User:</td>
									<td width="350"><input readonly="" type="text" name="txtUser" value="<? echo $rowNOTICE['user_name'];?>" style="width:150px;" /></td>
								</tr>
								<tr>
									<td class="label">Last Updated Date:</td>
									<td><input readonly="" type="text" name="txtDate" value="<? echo $rowNOTICE['notice_date'];?>" style="width:150px;"/></td>
								</tr>
								<tr>
									<td colspan="2">
										<span class="label">Notice Title:</span><br />
										<input type="text" name="txttitle" style="width:250px;" value="<? echo stripslashes($rowNOTICE['notice_title']);?>" maxlength="100" />
									</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr>
									<td colspan="2">
										<span class="label">Notice:</span><br />
									<textarea name="txtinfo" id="txtinfo" cols="60" rows="30" ><? echo stripslashes($rowNOTICE['notice']);?></textarea>
									
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="submit" name="btnSUBMIT" value="UPDATE NOTICE" class="Button" style="width:130px;"  /></td></tr>
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