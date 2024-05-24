<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	require("class.phpmailer.php");
	
	
	$bERROR			=	false;
	$bEXISTED_USER	=	false;
	$sBirthDate		=	"";
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	fn_Print_MSG_BOX("New department details are available from the finance office. Phone No is optional", "C_SUCCESS");
	if(isset($_POST["action"])	&& $_POST["action"]=="adddept"){
	
			$sSQL			=	"SELECT dept_name FROM tbl_departments WHERE dept_id = '".$_POST["txtdeptno"]."'";
			$rsDUPLICATE	=	mysql_query($sSQL) or die(mysql_error());
			if(mysql_num_rows($rsDUPLICATE)>0){
				$sMessage		=	fn_Print_MSG_BOX("<li>department already existed with this number", "C_ERROR");
				$bERROR			=	true;
			}else{
			
				$sSQL			=	"SELECT dept_id FROM tbl_departments WHERE dept_name = '".$_POST["txtdeptname"]."'";							
				$rsDUPLICATE_NAME	=	mysql_query($sSQL) or die(mysql_error());
				if(mysql_num_rows($rsDUPLICATE_NAME)>0){
					$sMessage		=	fn_Print_MSG_BOX("<li>department already existed with this name", "C_ERROR");
					$bERROR			=	true;
				}mysql_free_result($rsDUPLICATE_NAME);
			}mysql_free_result($rsDUPLICATE);
			
					
			if($bERROR	==	false){
				$sSQL="INSERT INTO  tbl_departments(dept_id, dept_name, leader_f_name, leader_l_name, ".
				"leader_phone, leader_email, dept_info) ".
				"VALUES('".trim($_POST["txtdeptno"])."', '".trim($_POST["txtdeptname"])."', '".trim($_POST["txtfname"])."', '".trim($_POST["txtlname"])."', '".trim($_POST["txtphone"])."', ".
				"'".trim($_POST["txtemail"])."', '".addslashes($_POST["txtInfo"])."')";
				//print($sSQL);
				$rsSHOPTASK=mysql_query($sSQL) or die(mysql_error());
				$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>department has been created", "C_SUCCESS");
							
			}
					
				
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Add Departments</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/JavaScript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_dept(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txtdeptno.value == ""){
		sErrMessage='<li>please enter department no';
		iErrCounter++;
	}else{
		regExp = /[0-9\.{9}'-]/i;
		if (!validate_field(frm.txtdeptno, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid valid dept no';
			iErrCounter++;
		}
	}
	
	
	if (frm.txtdeptname.value==""){
		sErrMessage=sErrMessage+'<li>please enter department name';
		iErrCounter++;
	}	
	
	if (frm.txtfname.value==""){
		sErrMessage=sErrMessage+'<li>please enter leader first name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtfname, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid first name';
			iErrCounter++;
		}
	}
	
	if (frm.txtlname.value==""){
		sErrMessage=sErrMessage+'<li>please enter leader last name';
		iErrCounter++;
	}else{
		regExp = /[ a-z\.'-]/i;
		if (!validate_field(frm.txtlname, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid last name';
			iErrCounter++;
		}
	}
	
	/*if (frm.txtphone.value==""){
		sErrMessage=sErrMessage+'<li>please enter leader phone number';
		iErrCounter++;
	}*/
	if (frm.txtphone.value!=""){
		regExp = /[ 0-9\.{9}'-]/i;
		if (!validate_field(frm.txtphone, regExp)){
			sErrMessage=sErrMessage+'<li>please enter valid phone number';
			iErrCounter++;
		}
	}
	
	
	if (frm.txtemail.value==""){
		sErrMessage=sErrMessage+'<li>please enter leader email';
		iErrCounter++;
	}else{
		regExp=/[a-z0-9\.-_]+@{1}[a-z0-9-_]+\.{1}[a-z]+/i;
		if (!regExp.test(Trim(frm.txtemail.value))){
			sErrMessage=sErrMessage+'<li>please enter valid email address';
			iErrCounter++;
		}
	}
	
	/*if(frm.chkexists.checked==true){
		
		if (frm.txtufname.value==""){
			sErrMessage=sErrMessage+'<li>please enter existed user first name';
			iErrCounter++;
		}else{
			regExp = /[a-z\.'-]/i;
			if (!validate_field(frm.txtufname, regExp)){
				sErrMessage=sErrMessage+'<li>please enter valid first name';
				iErrCounter++;
			}
		}
		
		if (frm.txtulname.value==""){
			sErrMessage=sErrMessage+'<li>please enter existed user last name';
			iErrCounter++;
		}else{
			regExp = /[a-z\.'-]/i;
			if (!validate_field(frm.txtulname, regExp)){
				sErrMessage=sErrMessage+'<li>please enter valid last name';
				iErrCounter++;
			}
		}
		
		if (frm.drpmonth.value == "" || frm.drpday.value == "" || frm.drpyear.value == ""){
			sErrMessage=sErrMessage+'<li>please select existed user date of birth';
			iErrCounter++;
		}
		
	}*/
	
	

	
		
	if (iErrCounter >0){
		
		fn_draw_ErrMsg(sErrMessage);
	}
	else
		frm.submit();
	
}

function fn_SHOW_HIDE(bSTATE){
	if(bSTATE){
		document.getElementById('existed_user').style.display='block';
	}else{
		document.getElementById('existed_user').style.display='none';
	}
}
function fn_AJAX_USER_INFO(iDRIVER_ID){
	$.get("ajax_data.php", {action: 'leader-info', did: iDRIVER_ID}, function(data){			  	
				if (data=="ERROR"){
					$('#EmailMessage').html("Error in loading Mass Email Message<br /> please contact with Web Admin");
				}else{
					//alert(data);
					document.frm1.txtfname.value		=	data.substring(0, data.indexOf('L='));
					document.frm1.txtlname.value		=	data.substring(data.indexOf('L=')+2,data.indexOf('E='));
					document.frm1.txtemail.value		=	data.substring(data.indexOf('E=')+2,data.indexOf('P='));
					document.frm1.txtphone.value		=	data.substring(data.indexOf('P=')+2,data.length);
					
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
								   				<h1 style="margin-bottom: 0px;">ADD DEPARTMENT</h1>
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
						<form name="frm1" action="add_dept.php" method="post">
							<input type="hidden" name="action" value="adddept"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="150" class="label">Department Number:</td>
									<td width="300"><input type="text" name="txtdeptno" maxlength="4" style="width:50px;" /></td>
								</tr>
								<tr>
									<td class="label">Department Name:</td>
									<td><input type="text" name="txtdeptname" maxlength="50" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label"></td>
									<td class="label"><input type="checkbox" name="chkexists" value="1" onClick="fn_SHOW_HIDE(this.checked);" />Leader is User of system now?</td>
								</tr>
								<tr>
									<td colspan="2">
									<div id="existed_user" style="display:none;">
										<table cellpadding="5" cellspacing="0" border="0">
											<tr>
												<td width="150"></td>
												<td width="300">
													
													<?	fn_DISPLAY_USERS('drpuser', 0, "200", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF, "fn_AJAX_USER_INFO(this.value);");?>
												</td>
											</tr>
										</table>
										</div>
									</td>
									
								</tr>
								<tr>
									<td class="label">Leader First Name:</td>
									<td><input type="text" name="txtfname" maxlength="15" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Leader Last Name:</td>
									<td><input type="text" name="txtlname" maxlength="15" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Leader Phone:</td>
									<td><input type="text" name="txtphone" maxlength="25" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label">Leader Email:</td>
									<td><input type="text" name="txtemail" maxlength="150" style="width:200px;" /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Dept. Information:</td>
									<td>
										<textarea name="txtInfo" id="txtInfo" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtInfo,this.form.txtinfoLength,200);" onKeyUp="fn_char_Counter(this.form.txtInfo,this.form.txtinfoLength,200);"></textarea>
									&nbsp;<input readonly type="text" name="txtinfoLength" value="200" style="width:25px;">
									</td>
								</tr>
								
								
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="ADD DEPARTMENT" class="Button" onClick="valid_dept(this.form);" style="width:150px;" /></td></tr>
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