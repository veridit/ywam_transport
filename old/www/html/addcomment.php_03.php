<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_redirect.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="addcomment"){
								
		$sSQL="INSERT INTO  tbl_comment_log(user_id, comments) ".
		"VALUES(".$_SESSION["User_ID"].", '".addslashes($_POST["txtcomments"])."')";
		//print($sSQL);
		$rsCOMMENTS=mysql_query($sSQL) or die(mysql_error());
		
		$sMessage		=	fn_Print_MSG_BOX("comments are added successfully", "C_SUCCESS");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Add Comments</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_comment(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txtcomments.value==""){
		sErrMessage='<li>please enter comments';
		iErrCounter++;
	}
	
	
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
</script>

</head>
<body style="margin: 0px;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		<!--start header	-->
		<? include('inc_header.php');	?>
		
   		<!-- start side nav	-->
		<? include('inc_side_nav.php');	?>
		
		<!-- actual page	-->
        <td>
        	<table border="0" cellspacing="0" cellpadding="0" width="700">
            	<tr valign="top" align="left">
                	<td width="15" height="16"><img src="../assets/images/autogen/clearpixel.gif" width="15" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
                	<td width="683"><img src="../assets/images/autogen/clearpixel.gif" width="683" height="1" border="0" alt=""></td>
                	<td width="1"><img src="../assets/images/autogen/clearpixel.gif" width="1" height="1" border="0" alt=""></td>
               	</tr>
               	<tr valign="top" align="left">
                	<td height="40"></td>
                	<td colspan="3" width="685">
                 		<table border="0" cellspacing="0" cellpadding="0" width="685" style="background-image: url('../assets/images/banner.gif'); height: 40px;">
                  			<tr align="left" valign="top">
                   				<td width="100%">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								
								 		<tr>
								  			<td><img src="../assets/images/autogen/clearpixel.gif" width="18" height="8" border="0" alt=""></td>
								  			<td width="651" class="TextObject">
								   				<h1 style="margin-bottom: 0px;">ADD COMMENTS</h1>
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
                	<td width="683" class="TextObject" align="center">
						<form name="frm1" action="addcomment.php" method="post">
							<input type="hidden" name="action" value="addcomment"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								<!--<tr><td colspan="2"><span id="Message" class="Err" <? //if($sMessage!="") {echo "style='display:block;'";}else{ echo "style='display:none;'"; }?>><?=$sMessage?></span></td></tr>-->
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td class="label" valign="top">Comments:</td>
									<td>
									<textarea name="txtcomments" id="txtcomments" cols="50" rows="10" style="width:250px;" ></textarea>
									
									</td>
								</tr>
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="ADD COMMENTS" class="Button" onClick="valid_comment(this.form);" /></td></tr>
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