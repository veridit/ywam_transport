<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font Highlited'>System will not allow you to choose same email if some user is already been registered with that email", "C_SUCCESS");
	if(isset($_POST["action"])	&& $_POST["action"]=="add_action"){
	
		if($_POST["optaction"]=="Notes"){
			$sSQL="INSERT INTO  tbl_user_comments(posting_user_id, about_user_id, comments) VALUES(".$_SESSION["User_ID"].", ".$_POST["drpdriver"].", '".addslashes($_POST["txtcomments"])."')";
			$rsCOMMENTS=mysql_query($sSQL) or die(mysql_error());
			$sMessage		=	fn_Print_MSG_BOX("<li>notes about driver are added successfully", "C_SUCCESS");
		}elseif($_POST["optaction"]=="Change Email"){
		
				$sSQL	=	"SELECT user_id FROM tbl_user WHERE email = '".$_POST["txtemail"]."' AND user_id <> ".$_POST["drpdriver"];
				$rsEMAIL_CHECK	=	mysql_query($sSQL) or die(mysql_error());
				if (mysql_num_rows($rsEMAIL_CHECK)>0){
					$sMessage=fn_Print_MSG_BOX("<li>user already registered with this email address","C_ERROR");
				}else{
						$sSQL="UPDATE  tbl_user SET email='".mysql_real_escape_string($_POST["txtemail"])."' WHERE user_id = ".mysql_real_escape_string($_POST["drpdriver"]);
						//print($sSQL);
						$rsMEMBER=mysql_query($sSQL) or die(mysql_error());
				
						$sEmailSubject		=	"Email Change Confirmation";
						$sMailMSG			=	"Your email has been changed to this email address";
						//print($sMailMSG);
						$mail = new PHPMailer();
						$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
						$mail->From     = $sSUPPORT_EMAIL;
						$mail->FromName = $sCOMPANY_Name;
						$mail->AddAddress($_POST["txtemail"]);
						$mail->IsHTML(true);                               // send as HTML
						$mail->Subject  =  $sEmailSubject;
						$mail->Body    = $sMailMSG;
						if(!$mail->Send()){
							$sMessage		=	fn_Print_MSG_BOX("Email has been changed, <br />but Error in Sending Email, $mail->ErrorInfo","C_ERROR");
						}else{
							$sMessage		=	fn_Print_MSG_BOX("<li>A confirmation email has been sent to this new address", "C_SUCCESS");
						}
				}mysql_free_result($rsEMAIL_CHECK);
			
		}
			
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Driver Notes &amp; Email Change</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/JavaScript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_action(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.drpdriver.value==""){
		sErrMessage=sErrMessage+'<li>please select driver';
		iErrCounter++;
	}
	
	var bMethod = false;
	var sCalcVal	=	"";
	for (var i=0; i <frm.optaction.length; i++) { 
		if (frm.optaction[i].checked) { 
	   		bMethod	=	true; 
			sCalcVal=	frm.optaction[i].value;
		} 
	}
	
	
	if(bMethod==false){
		sErrMessage=sErrMessage+'<li>please select action';
		iErrCounter++;
	}else{
		if(sCalcVal=="Notes"){
			
			if (frm.txtcomments.value==""){
				sErrMessage=sErrMessage+'<li>please enter notes about driver';
				iErrCounter++;
			}
		}else if(sCalcVal=="Change Email"){
			if (frm.txtemail.value==""){
				sErrMessage=sErrMessage+'<li>please enter new email for driver';
				iErrCounter++;
			}else{
				regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;
				if (!regExp.test(Trim(frm.txtemail.value))){
					sErrMessage=sErrMessage+'<li>please enter valid email address for driver';
					iErrCounter++;
				}
			}			
		}
	}
		

	if (iErrCounter >0){	
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}
function fn_ACTION_METHOD(sMethod){

	if(sMethod=='notes'){
		document.getElementById('comment_box').style.display='block';
		document.getElementById('email_box').style.display='none';
		document.getElementById('btnSUBMIT').value='ADD NOTES';
	}else if(sMethod=='email'){
		document.getElementById('comment_box').style.display='none';
		document.getElementById('email_box').style.display='block';
		document.getElementById('btnSUBMIT').value='CHANGE EMAIL';
	}
	
}
function fn_LOAD_EMAIL(iDriverID){
	$.get("ajax_data.php", {action: 'load-email', did: iDriverID}, function(data){			  	
				if (data=="ERROR"){
					$('#Message').html("Error!!! in loading driver email");
				}else{
					$('#txtemail').val(data);
				}
	}, 'html');
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
								   				<h1 style="margin-bottom: 0px;">DRIVER NOTES &amp; EMAIL CHANGE</h1>
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
						<form name="frm1" action="driver_notes_email.php" method="post">
							<input type="hidden" name="action" value="add_action"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td class="label" width="200">Driver:</td>
									<td width="400"><?	fn_DISPLAY_USERS('drpdriver', 0, "200", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF, "fn_LOAD_EMAIL(this.value);");?></td>
								</tr>
								<tr>
									<td class="label">Action:</td>
									<td><div class="left"><input type="radio" id="optnotes" name="optaction" value="Notes" onClick="fn_ACTION_METHOD('notes');"/></div><span class="label left">Add Notes about Driver</span>&nbsp;&nbsp;&nbsp;<input type="radio" id="optreading" name="optaction" value="Change Email" onClick="fn_ACTION_METHOD('email');"/><span class="label">Change Email</span></td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="email_box" style="display:none;">
											<table  cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>
													<td class="label" width="200">Email:</td>
													<td width="400"><input type="text" id="txtemail" name="txtemail" value="" maxlength="150" style="width:250px;"  /></td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="comment_box" style="display:none; width:100%;">
											<table cellpadding="0" cellspacing="5" border="0" width="100%">												
												<tr>
													<td class="label" valign="top" width="200">Notes:</td>
													<td width="400"><textarea name="txtcomments" id="txtcomments" cols="50" rows="10" style="width:250px;" ></textarea></td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
								
																
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" id="btnSUBMIT" value="ACTION" class="Button" onClick="valid_action(this.form);" style="width:150px;" /></td></tr>
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