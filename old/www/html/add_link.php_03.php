<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_redirect.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>The administrator can use this page to put permanent notices to home page <br /> the title used will appear as the function name on the left side of the home page", "C_SUCCESS");
	if(isset($_POST["action"])	&& $_POST["action"]=="addlink"){
								
		$sSQL="INSERT INTO  tbl_info_links(link_title, link_text, link_order, link_display_page) ".
		"VALUES('".addslashes($_POST["txttitle"])."', '".addslashes($_POST["txtinfo"])."', 0, 'home')";
		//print($sSQL);
		$rsLINK=mysql_query($sSQL) or die(mysql_error());
		
		$iMAX_LINK	=	mysql_insert_id();
		
		mysql_query("UPDATE tbl_info_links SET link_order = ".$iMAX_LINK." WHERE link_id = ".$iMAX_LINK) or die(mysql_error());
		
		
		$sSQL	=	"SELECT MAX(link_order) AS link_order FROM tbl_driver_links_order WHERE driver_login = 0";
		$rsLINKS	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsLINKS)>0){
			list($iLINK_ORDER)	=	mysql_fetch_row($rsLINKS);
		}mysql_free_result($rsLINKS);
		
		$sSQL		=	"INSERT INTO tbl_driver_links_order (link_id, link_order, driver_login) VALUES(".$iMAX_LINK.", ".(intval($iLINK_ORDER)+1).", 0)";
		mysql_query($sSQL) or die(mysql_error());
		
		
		$sMessage		=	fn_Print_MSG_BOX("information link is created successfully", "C_SUCCESS");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Add Notice to Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_link(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txttitle.value==""){
		sErrMessage='<li>please enter notice title';
		iErrCounter++;
	}
	if (tinyMCE.get('txtinfo').getContent()==""){
		sErrMessage=sErrMessage+'<li>please enter text / notice for the drivers';
		iErrCounter++;
	}
	
	
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
</script>
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
								   				<h1 style="margin-bottom: 0px;">ADD NOTICE TO HOME PAGE</h1>
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
						<form name="frm1" action="add_link.php" method="post">
							<input type="hidden" name="action" value="addlink"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td>
										<span class="label">Link Title:</span><br />
										<input type="text" name="txttitle" style="width:250px;" maxlength="100" />
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td>
										<span class="label">Information:</span><br />
									<textarea name="txtinfo" id="txtinfo" cols="60" rows="30" ></textarea>
									
									</td>
								</tr>
								
								<tr><td>&nbsp;</td></tr>
								<tr><td><input type="button" name="btnSUBMIT" value="ADD NOTICE" class="Button" style="width:130px;" onClick="valid_link(this.form);" /></td></tr>
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