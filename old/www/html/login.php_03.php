<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	$sMessage		=	"";
	$bDRIVER_INACTIVE_ERR	=	false;
	$bDRIVER_ERR_TITLE		=	"";
	$bDRIVER_ERR_MSG		=	"";
	
	
	$sUserName				=	"";
	$sPassword				=	"";
	$iGROUP					=	"";
	$iDEPT_ID				=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="login"){
	
		$sUserName		=	$_POST["txtemail"];
		$sPassword		=	$_POST["txtpassword"];
		$iGROUP			=	$_POST["drpusergroup"];
		
		if(isset($_POST["drpleaderdepts"]) && $_POST["drpleaderdepts"]!="")		$iDEPT_ID		=	$_POST["drpleaderdepts"];
		
		//$sLoginSQL	=	"select user_id, email, user_group from tbl_user where email = '".$sUserName."' and password = '".$sPassword."' and user_group = ".$iGROUP." and active = 1";
		//if($iGROUP==$iGROUP_DRIVER || $iGROUP==$iGROUP_COORDINATOR_STAFF){
			$sLoginSQL	=	"SELECT user_id FROM tbl_user WHERE email = '".$sUserName."' AND BINARY password = '".$sPassword."' AND user_group = ".$iGROUP;
			$iRsLogin	=	mysql_query($sLoginSQL) or die(mysql_error());
			if (mysql_num_rows($iRsLogin)<=0){
				$sMessage		=	fn_Print_MSG_BOX("<li>Error!!! invalid login information, please try again.", "C_ERROR");
				$bDRIVER_ERR_TITLE		=	"Invalid Information!";
				$bDRIVER_ERR_MSG		=	"Your username or password is invalid, please try again.";
				$bDRIVER_INACTIVE_ERR	=	true;
						
			}else{
				list($iUSER_ID)	=	mysql_fetch_row($iRsLogin);
				$sSQL	=	"SELECT active, new_user FROM tbl_user WHERE user_id = ".$iUSER_ID;
				$rsDRIVER	=	mysql_query($sSQL) or die(mysql_error());
				
				if(mysql_num_rows($rsDRIVER)>0){
				
					list($bACTIIVE, $bNEW_DRIVER)	=	mysql_fetch_row($rsDRIVER);
				
					if($bACTIIVE==0 && $bNEW_DRIVER==1){	//DRIVER IS new
						$bDRIVER_ERR_TITLE		=	"Not an approved account!";
						$bDRIVER_ERR_MSG		=	"You are not yet been approved from Administration, please contact to Transportation Office";
						$bDRIVER_INACTIVE_ERR	=	true;
						$sMessage				=	fn_Print_MSG_BOX("<li>Error!!! You are not yet been approved from Administration, please contact to Transportation Office", "C_ERROR");
					}elseif($bACTIIVE==0 && $bNEW_DRIVER==0){	//DRIVER is inactive
						
						$bDRIVER_ERR_TITLE		=	"Inactive Account!";
						if($iGROUP==$iGROUP_DRIVER || $iGROUP==$iGROUP_COORDINATOR_STAFF){
							$bDRIVER_ERR_MSG		=	"Your account has been suspended...<br />you may need to change your home department..<br />contact transportation Office for more information";
							$sMessage				=	fn_Print_MSG_BOX("<li>Error!!! Your account has been suspended...you may need to change your home department..<br />contact transportation Office for more information", "C_ERROR");
						}else{
							$bDRIVER_ERR_MSG		=	"Your account has been suspended...<br />contact administration for more information";
							$sMessage				=	fn_Print_MSG_BOX("<li>Error!!! Your account has been suspended...<br />contact administration for more information", "C_ERROR");
						}
						$bDRIVER_INACTIVE_ERR	=	true;
					}elseif($bACTIIVE==1 && $bNEW_DRIVER==0){
						
	
						$_SESSION["User_ID"]		=	$iUSER_ID;
						$_SESSION["User_Name"]		=	$sUserName;
						$_SESSION["User_Group"]		=	$iGROUP;
						
													
						$sIP_ADDRESS				=	getRealIpAddr();
						//print("DATE==".date('Y-m-d H:i:s'));
						//die();
						$sSQL	=	"INSERT INTO tbl_log (user_id, login_datetime, ip_address) VALUES (".$_SESSION["User_ID"].", '".date('Y-m-d H:i:s')."', '".$sIP_ADDRESS."')";
						$rsLOG	=	mysql_query($sSQL) or die(mysql_error());
						
						if($iGROUP==$iGROUP_DRIVER || $iGROUP==$iGROUP_COORDINATOR_STAFF){
							$_SESSION["load_counter"]	=	"1";
							echo "<SCRIPT LANGUAGE='JAVASCRIPT'> window.location='reservations.php'</SCRIPT>";
						}elseif($iGROUP==$iGROUP_TM){
							echo "<SCRIPT LANGUAGE='JAVASCRIPT'> window.location='management.php'</SCRIPT>";
						}elseif($iGROUP==$iGROUP_TC){
							echo "<SCRIPT LANGUAGE='JAVASCRIPT'> window.location='admin.php'</SCRIPT>";
						}elseif($iGROUP==$iGROUP_SERVICETCH){
							echo "<SCRIPT LANGUAGE='JAVASCRIPT'> window.location='shopwork.php'</SCRIPT>";
						}
							
						
					}
				}
			}
		
				
	}
//FUNCTION TO GET CLIENT IP ADDRESS
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<?Php if($bDRIVER_INACTIVE_ERR==true){?>
<script type="text/javascript" src="./js/popup.js"></script>
<script language="JavaScript">
	//alert('aaa');
		$(document).ready(function () {
			document.getElementById('contactArea').innerHTML	=	"<div style='margin-left:45%;height:150px;'><img src='../assets/images/loading_busy.gif' /></div>";
			centerPopup();
			loadPopup();
			document.getElementById('contactArea').innerHTML	=	<?php echo json_encode("<h1 class='notice-heading'notice_heading>".$bDRIVER_ERR_TITLE."</h1><br />".$bDRIVER_ERR_MSG."<br /><br />"); ?>;
			
		});
</script>
<?Php
	}
?>
<script type="text/javascript">


function valid_login(frm){

	var sErrMessage='';
	var iErrCounter=0;
		
	if (frm.txtemail.value==""){
		sErrMessage=sErrMessage+'<li>please enter your email address';
		iErrCounter++;
	}else{
		regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;
		if (!regExp.test(Trim(frm.txtemail.value))){
			sErrMessage=sErrMessage+'<li>please enter valid email address';
			iErrCounter++;
		}
	}
	
	if(sErrMessage!='')	iErrCounter++;
	
	if (frm.txtpassword.value == ""){
		sErrMessage=sErrMessage+'<li>please enter your password';
		iErrCounter++;
	}
	
	if (frm.drpusergroup.value == ""){
		sErrMessage=sErrMessage+'<li>please select your group';
		iErrCounter++;
	}
	//alert(objLeaderDept);
	
	if (iErrCounter >0){	
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		document.frm1.submit();
	
	
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
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
								 		<tr>			
								  			<td class="TextObject" align="center">
								   				<h1 style="margin-bottom: 0px;">LOGIN...</h1>
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
						<form name="frm1" action="login.php" method="post">
							<input type="hidden" name="action" value="login"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box">
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td width="100" class="label">Email:</td>
									<td width="250"><input type="text" name="txtemail" id="txtemail" value="" style="width:200px;"  /></td>
								</tr>
								
								<tr>
									<td class="label" width="100">Password:</td>
									<td width="300"><input type="password" name="txtpassword" value="" style="width:200px;"  /></td>
								</tr>
								
								<tr>
									<td class="label">Group:</td>
									<td><?	fn_USER_GROUP('drpusergroup', '', "200", "1", "Select User Group");?></td>
								</tr>
								<tr><td><div id="leader_label" class="label"></div></td><td><div id="leader_depts"></div></td></tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="LOGIN" class="Button" onClick="valid_login(this.form);" style="width:100px;" />&nbsp;&nbsp;&nbsp;<a href="passwordreminder.php" style="text-decoration:underline; font-style:italic; letter-spacing:1px;">forgot your password</a></td></tr>
							</table>
						</form>
                	</td>
                	<td></td>
               	</tr>
				<tr><td colspan="4"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></td></tr>
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
<script language="JavaScript" type="text/javascript">document.frm1.txtemail.focus();</script>
 <div id="popupContact">
		<div id="contactArea" style="padding-left:10px;">asdfasdf</div>
		<div style="text-align:center; width:100px; margin:0 auto;"><input type="button" name="btnclose" value="CLOSE" class="Button" id="popupContactClose" style="width:100px;" /></div>
	</div>
<div id="backgroundPopup"></div>