<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	//$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font Highlited'>System will not allow you to choose same email if some user is already been registered with that email", "C_SUCCESS");
	$sMessage		=	"";
	$iDEPT_ID		=	0;
	
	if(isset($_REQUEST["did"]))			$iDEPT_ID	=	$_REQUEST["did"];
	
	if(isset($_POST["action"])	&& $_POST["action"]=="editdept"){		
		//==============================================
		$sSQL="UPDATE tbl_departments SET dept_name = '".trim($_POST["txtdeptname"])."', ".
		"leader_f_name = '".trim($_POST["txtfname"])."', leader_l_name = '".trim($_POST["txtlname"])."', ".
		"leader_phone = '".trim($_POST["txtphone"])."', leader_email = '".trim($_POST["txtemail"])."', dept_info = '".addslashes($_POST["txtInfo"])."' ".
		" WHERE dept_id = ".$iDEPT_ID;
		//print($sSQL);
		$rsSHOPTASK=mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("<li>department modified successfully", "C_SUCCESS");
		//==============================================
			
	
	}
$sSQL	=	"SELECT * FROM tbl_departments WHERE dept_id = ".$iDEPT_ID;
$rsDEPT	=	mysql_query($sSQL) or die(mysql_error());
if(mysql_num_rows($rsDEPT)>0){
	$rowDEPT	=	mysql_fetch_array($rsDEPT);
}else{
	$sMessage		=	fn_Print_MSG_BOX("<li>no department found", "C_ERROR");
}mysql_free_result($rsDEPT);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>Edit Departments</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<script type="text/javascript">
function valid_dept(frm){

	var sErrMessage='';
	var iErrCounter=0;
	
	if (frm.txtdeptno.value == ""){
		sErrMessage='<li>please enter department no';
		iErrCounter++;
	}else{
		regExp = /[ 0-9\.{9}'-]/i;
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
	}else{*/
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
								   				<h1 style="margin-bottom: 0px;">EDIT DEPARTMENT</h1>
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
						<form name="frm1" action="edit_dept.php" method="post">
							<input type="hidden" name="action" value="editdept"	/>
							<input type="hidden" name="did" value="<? echo $iDEPT_ID;?>" />
							<table cellpadding="0" cellspacing="5" border="0" width="600" align="center" class="box">
								
								<tr><td colspan="2" id="Message" width="100%"><?=$sMessage?></td></tr>
								
								<tr>
									<td width="200" class="label">Department Number:</td>
									<td width="400"><input readonly="" type="text" name="txtdeptno" maxlength="4" style="width:50px;" value="<? echo $rowDEPT['dept_id'];?>" /></td>
								</tr>
								<tr>
									<td class="label">Department Name:</td>
									<td><input type="text" name="txtdeptname" maxlength="50" style="width:200px;" value="<? echo $rowDEPT['dept_name'];?>" /></td>
								</tr>
								<tr>
									<td class="label">Leader First Name:</td>
									<td><input type="text" name="txtfname" maxlength="15" style="width:200px;" value="<? echo $rowDEPT['leader_f_name'];?>" /></td>
								</tr>
								<tr>
									<td class="label">Leader Last Name:</td>
									<td><input type="text" name="txtlname" maxlength="15" style="width:200px;" value="<? echo $rowDEPT['leader_l_name'];?>"  /></td>
								</tr>
								<tr>
									<td class="label">Leader Phone:</td>
									<td><input type="text" name="txtphone" maxlength="25" style="width:200px;" value="<? echo $rowDEPT['leader_phone'];?>" /></td>
								</tr>
								<tr>
									<td class="label">Leader Email:</td>
									<td><input type="text" name="txtemail" maxlength="150" style="width:200px;" value="<? echo $rowDEPT['leader_email'];?>" /></td>
								</tr>
								<tr>
									<td class="label" valign="top">Dept. Information:</td>
									<td>
										<textarea name="txtInfo" id="txtInfo" cols="30" rows="5" style="width:250px;" onKeyDown="fn_char_Counter(this.form.txtInfo,this.form.txtinfoLength,200);" onKeyUp="fn_char_Counter(this.form.txtInfo,this.form.txtinfoLength,200);"><? echo stripslashes($rowDEPT['dept_info']);?></textarea>
									&nbsp;<input readonly type="text" name="txtinfoLength" value="200" style="width:25px;">
									</td>
								</tr>
								<tr><td colspan="2">&nbsp;</td></tr>
								<tr><td></td><td><input type="button" name="btnSUBMIT" value="EDIT DEPARTMENT" class="Button" onClick="valid_dept(this.form);" style="width:150px;" /></td></tr>
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