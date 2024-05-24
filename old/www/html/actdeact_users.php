<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');
	require("class.phpmailer.php");

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	
	$sCriteriaSQL	=	"";
	$sTMSQL			=	"";
	$iRECORD_COUNT	=	0;
	$iDRIVER		=	0;
	$sSORT_ORDER	=	"u.l_name, u.f_name ASC";
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>DO NOT USE THIS PAGE FOR NEW APPLICANTS...USE THE 'ACTIVATE-RENEW PERMITS' FUNCTION  ","C_SUCCESS");
	
	if(isset($_POST["userid"]) && $_POST["userid"]!="")		$iUSER_ID	=	mql_real_escape_string($_POST["userid"]);
	
	if(isset($_POST["action"])	&& $_POST["action"]=="DA"){
		$sSQL		=	"UPDATE tbl_user SET active = 0 WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage	=	fn_Print_MSG_BOX("<li>user de-activated","C_SUCCESS");
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="A"){
		$sSQL		=	"UPDATE tbl_user SET active = 1 WHERE user_id = ".$iUSER_ID;
		$rsACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		if(!fn_SEND_EMAIL_TO_USER(25, $iUSER_ID, $sCOMPANY_SMTP, $sSUPPORT_EMAIL, $sCOMPANY_Name)){
			$sMessage	=	fn_Print_MSG_BOX("<li>user has been activated, but error in sending email","C_SUCCESS");
		}else{
			$sMessage	=	fn_Print_MSG_BOX("<li>user has been activated","C_SUCCESS");
		}
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		
		if(isset($_POST["drplname"]) && $_POST["drplname"]!="")				{
			$iDRIVER	=	mysql_real_escape_string($_POST["drplname"]);
			$sCriteriaSQL	.=	" AND u.user_id = ".$iDRIVER;
		}
		
		if($_SESSION["User_Group"]==$iGROUP_TM)			$sTMSQL	=	" AND u.user_group <> ".$iGROUP_TC;
	
		
		
		$sSQL	=	"SELECT u.user_id, u.f_name, u.l_name, u.email, u.user_type, u.drive_tested, u.phone, u.birth_date, u.license_no, u.drive_tested, u.test_date, u.home_st_country, u.permit_type, u.renew_date, u.renew_text,  ".
		"CASE WHEN u.new_user = 1 THEN 'YES' ELSE 'NO' END AS new_user_status,".
		"CASE WHEN u.active = 1 THEN 'Active' ELSE 'InActive' END AS status, dept_name, u.dept_id, group_name, u.reg_date, u.new_user, ".
		"CASE WHEN u.driver_permission = 1 THEN 'YES' ELSE 'NO' END driver_permission ".
		"FROM tbl_user u ".
		"INNER JOIN tbl_departments ON u.dept_id = tbl_departments.dept_id ".
		"INNER JOIN tbl_user_group ON u.user_group = tbl_user_group.group_id ".
		"WHERE 1=1 ".$sCriteriaSQL.$sTMSQL." ORDER BY ".$sSORT_ORDER;
		//print($sSQL);
		$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsUSERS);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no user found", "C_ERROR");
		}
	}
	

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>
<?Php	echo "DEACTIVATE-ACTIVATE";?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript" src="./js/common_scripts.js"></script>


<script type="text/javascript">

function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}


function fn_SET_STATUS(iUSER_ID, sSTATUS){
	document.frm1.userid.value	=	iUSER_ID;
	document.frm1.action.value	=	sSTATUS;
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
									<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								 		<tr>			
								  			<td class="TextObject" align="center">
								   				<h1 style="margin-bottom: 0px;">
												<?Php	echo "DEACTIVATE-ACTIVATE";	?>
												</h1>
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
						<form name="frm1" action="actdeact_users.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="userid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											
											<tr>
												
												
												<td class="label" width="80">Users:<br />
													<?	//fn_DISPLAY_USERS('drplname', $iDRIVER, "160", "1", "--Select User--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_SERVICETCH);?>
													<?Php
															$sSELECTED_DRIVER	=	"";
															
															$sSQL	=	"SELECT user_id, CASE WHEN active = 0 THEN CONCAT(l_name, ' ', f_name, '===IN-ACTIVE===') ELSE CONCAT(l_name, ' ', f_name) END AS user_name FROM tbl_user WHERE new_user = 0 ORDER BY l_name, f_name";
															$rsUSERS_LIST	=	mysql_query($sSQL) or die(mysql_error());
															if(mysql_num_rows($rsUSERS_LIST)>0){
																echo "<select name='drplname' style='width:160px;'>";
																echo "<option value=''>--Select User--</option>";
																while($rowUSERS	=mysql_fetch_array($rsUSERS_LIST)){
																	if($iDRIVER==$rowUSERS['user_id'])		$sSELECTED_DRIVER	=	"selected";		else $sSELECTED_DRIVER	=	"";
																	echo "<option value='".$rowUSERS['user_id']."' ".$sSELECTED_DRIVER.">".$rowUSERS['user_name']."</option>";
																}
																echo "</select>";
															}mysql_free_result($rsUSERS_LIST);
													?>
												</td>
												
												<td width="50">
													<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /><br />
																										
												</td>
											</tr>
											
											
											
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												
												<td width="50" class="colhead">Dept #</td>
												<td width="100" class="colhead">Dept Name</td>
												<td width="80" class="colhead">Name</td>
												<td width="70" class="colhead">Status</td>
												<td width="120" class="colhead">Email</td>
												<td width="30" class="colhead">New</td>
												<td width="90" class="colhead">Group</td>
												<td width="35" class="colhead">Type</td>
												
												
												<td width="50" class="colhead">Reg Date</td>
												<td width="50" class="colhead">Reprts</td>
												<td width="20" class="colhead">Act</td>
												
												
											</tr>
											<?		$listed	=	0;	
													while($rowUSER	=	mysql_fetch_array($rsUSERS)){
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															
															<td class="coldata leftbox"><? echo $rowUSER['dept_id'];?></td>
															<td class="coldata"><? echo $rowUSER['dept_name'];?></td>
															<td class="coldata"><? echo $rowUSER['f_name']." ".$rowUSER['l_name'];?></td>
															<td class="coldata"><? echo $rowUSER['status'];?></td>
															<td class="coldata"><? echo $rowUSER['email'];?></td>
															<td class="coldata"><? echo $rowUSER['new_user_status'];?></td>
															<td class="coldata"><? echo $rowUSER['group_name'];?></td>
															<td class="coldata"><? echo $rowUSER['user_type'];?></td>
															
															
															<td class="coldata"><? echo fn_cDateMySql($rowUSER['reg_date'], 1);?></td>
															<td class="coldata"><? echo $rowUSER['driver_permission'];?></td>
															<td class="coldata">
															<?Php	
																		if($rowUSER['status']=="Active") {
																			if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_DE_ACTIVATE)){
																				$sSTATUS	=	'DA';	$sA_TITLE	=	'DEACTIVATE';
																?>				<a href="javascript:void(0);" onClick="fn_SET_STATUS(<?Php echo $rowUSER['user_id']?>, '<?Php echo $sSTATUS;?>');" title="<?Php echo $sA_TITLE;?>">DA</a>
																<?			}	
																		}else{
																			if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_ACTIVATE)){
																				$sSTATUS	=	'A';	$sA_TITLE	=	'ACTIVATE';
																?>				<a href="javascript:void(0);" onClick="fn_SET_STATUS(<?Php echo $rowUSER['user_id']?>, '<?Php echo $sSTATUS;?>');" title="<?Php echo $sA_TITLE;?>"><?Php echo $sSTATUS;?></a>
																<?			}
																		}
															?>
															</td>
															
														</tr>
											<?			}$listed++;
													}
											?>
										
										</table>
									</td>
								</tr>
								<?Php
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
										
									}	if($iRECORD_COUNT>0)	mysql_free_result($rsUSERS);	
								?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								
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
 
