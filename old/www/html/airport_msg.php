<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	

	$sMessage		=	"";
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Send Airport Warning</title>
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
	
	if(confirm('Airport Warning will be sent to all drivers..continue..?')==true){
	//alert('adfasdf');
		$.get("ajax_data.php", {action: 'mass-email', mid: '10'}, function(data){			  	
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
	}
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
	
	//document.getElementById("comment_box").style.position	=	"fixed";
	document.getElementById("email_box").style.position	=	"absolute";
	document.getElementById("email_box").style.top	=	windowHeight/2-popupHeight/2;
	document.getElementById("email_box").style.left	=	windowWidth/2-popupWidth/2;
	
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
	
		$.get("ajax_data.php", {msgid: '10', action: 'send-mass-email', drpstatus: '1', drpusers: '', drpusertype: '', txtemail: tinyMCE.get('txtemail').getContent()}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html("Error in loading Mass Email Message<br /> please contact with Web Admin");
				}else{
					
					var url='mass_email.php?action=email&sender=<?Php echo $_SESSION["User_ID"];?>&mid=10';
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
<body style="margin: 0px;" onLoad="fn_EMAIL();">
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
								   				<h1 style="margin-bottom: 0px;">SEND AIRPORT WARNING</h1>
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
						<form name="frm1" action="airport_msg.php" method="post">
							<input type="hidden" name="action" value=""	/>
							
							
							<table cellpadding="0" cellspacing="5" border="0" width="700" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								
								
								
								<tr>
									<td>
										<div id="email_box" style="width:650px; height:495px; display:none; z-index:5; top:270px; left:450px; position:fixed; background-color:#fff;">
											<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box" height="250">
												<tr><td colspan="2" id="EmailMessage" width="100%"></td></tr>
												
												<tr>
													<td class="label">Airport Warning Message:<BR /><BR /><textarea name="txtemail" id="txtemail" cols="50" rows="25" style="height:380px;"  ></textarea></td>
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
 