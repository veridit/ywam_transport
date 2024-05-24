<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');
	require("class.phpmailer.php");

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	$iDeptID		=	0;
	$iGroupID		=	0;
	$sUserType		=	"";
	$bNEW_USER		=	0;
	$sCriteriaSQL	=	"";
	$sTMSQL			=	"";
	$iRECORD_COUNT	=	0;
	//$sUSER_NAME		=	"";
	$iDRIVER		=	0;
	$sSORT_ORDER	=	"u.l_name ASC";
	$sMessage		=	"";
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!="")		{$iGroupID	=	$_POST["drpusergroup"];		$sCriteriaSQL	.=	" AND u.user_group = ".$iGroupID;}
		if(isset($_POST["drpusertype"]) && $_POST["drpusertype"]!="")		{$sUserType	=	$_POST["drpusertype"];		$sCriteriaSQL	.=	" AND u.user_type = '".$sUserType."'";}
		if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				{$iDeptID	=	$_POST["drpdept"];			$sCriteriaSQL	.=	" AND u.dept_id= ".$iDeptID;}
		if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	$_POST["drpstatus"];		$sCriteriaSQL	.=	" AND u.active = ".$sStatus;}
		if(isset($_POST["drplname"]) && $_POST["drplname"]!="")				{$iDRIVER	=	$_POST["drplname"];			$sCriteriaSQL	.=	" AND u.user_id = ".$iDRIVER;}
		
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")				{$sSORT_ORDER		=	$_POST["drpsort"];	}
		if($_SESSION["User_Group"]==$iGROUP_TM)			$sTMSQL	=	" AND u.user_group <> ".$iGROUP_TC;
	
		
		
		$sSQL	=	"SELECT u.user_id, u.f_name, u.l_name, u.email, u.user_type, u.drive_tested, u.phone, u.birth_date, u.license_no, u.drive_tested, u.test_date, u.home_st_country, u.permit_type, u.renew_date, u.renew_text,  ".
		"CASE WHEN u.active = 1 THEN 'Active' ELSE 'InActive' END AS status, dept_name, u.dept_id, group_name, u.reg_date, u.new_user, ".
		"CASE WHEN u.driver_permission = 1 THEN 'YES' ELSE 'NO' END driver_permission, ".
		"CASE WHEN MAX(login_datetime) IS NULL THEN 'NEVER LOGGED IN' ELSE MAX(login_datetime) END AS last_login, ".
		"CASE WHEN u.status_date IS NULL THEN 'N/A' ELSE u.status_date END AS status_ch_date ".
		"FROM tbl_user u ".
		"INNER JOIN tbl_departments ON u.dept_id = tbl_departments.dept_id ".
		"INNER JOIN tbl_user_group ON u.user_group = tbl_user_group.group_id ".
		"LEFT OUTER JOIN tbl_log l ON u.user_id = l.user_id ".
		"WHERE 1=1 ".$sCriteriaSQL.$sTMSQL." ".
		"GROUP BY u.user_id, u.f_name, u.l_name, u.email, u.user_type, u.drive_tested, u.phone, u.birth_date, ".
		"u.license_no, u.drive_tested, u.test_date, u.home_st_country, u.permit_type, u.renew_date, u.renew_text, u.active, ".
		"dept_name, u.dept_id, group_name, u.reg_date, u.new_user, u.driver_permission ".
		"ORDER BY ".$sSORT_ORDER;
		///print($sSQL);
		$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsUSERS);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no driver found", "C_ERROR");
		}
	}
	

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Drivers List</title>
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
												DRIVERS LIST
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
						<form name="frm1" action="list_drivers.php" method="post">
							<input type="hidden" name="action" value=""	/>
							
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="140">User Group:<br /><?	fn_USER_GROUP('drpusergroup', $iGroupID, "140", "1", "All Groups");?></td>
												<td class="label" width="100">User Type:<br /><?	fn_USER_TYPE('drpusertype', $sUserType, "100", "1", "All User Type");?></td>
												<td class="label" width="100">Users:<br />
												<?	fn_DISPLAY_USERS('drplname', $iDRIVER, "160", "1", "--Select Driver--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_COORDINATOR_STAFF.",".$iGROUP_SERVICETCH);?>
												
												</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td class="label" width="150">Department:<br /><?	fn_DEPARTMENT('drpdept', $iDeptID, "150", "1", "All Departments");?></td>
												
												<td class="label" width="80">Status:<br />
													<?
														$arrSTATUS[0][0]	=	"1";
														$arrSTATUS[0][1]	=	"Active";
														$arrSTATUS[1][0]	=	"0";
														$arrSTATUS[1][1]	=	"InActive";
													?>
													<select name="drpstatus" size="1" style="width:80px;">
														<option value="">Any</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrSTATUS[$iCOUNTER][0]?>" <? if($arrSTATUS[$iCOUNTER][0]==$sStatus) echo "selected";?>><?=$arrSTATUS[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td class="label" width="160">Sort By:<br />
													
													<select name="drpsort" style="width:100px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="l_name ASC" 		<? if($sSORT_ORDER == "l_name ASC") echo "selected";?>>Last Name A-Z</option>
														<option value="l_name DESC" 	<? if($sSORT_ORDER == "l_name DESC") echo "selected";?>>Last Name Z-A</option>	
														<option value="dept_name ASC" 	<? if($sSORT_ORDER == "dept_name ASC") echo "selected";?>>Dept. Name A-Z</option>
														<option value="dept_name DESC"  <? if($sSORT_ORDER == "dept_name DESC") echo "selected";?>>Dept. Name Z-A</option>
														<option value="email ASC" 		<? if($sSORT_ORDER == "email ASC") echo "selected";?>>Email A-Z</option>
														<option value="email DESC"  	<? if($sSORT_ORDER == "email DESC") echo "selected";?>>Email Z-A</option>
														<option value="reg_date ASC"  	<? if($sSORT_ORDER == "reg_date ASC") echo "selected";?>>Reg.Date A-Z</option>
														<option value="reg_date DESC"  	<? if($sSORT_ORDER == "reg_date DESC") echo "selected";?>>Reg.Date Z-A</option>
													</select>
												</td>
												<td width="50">
													<input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /><br />
												</td>
											</tr>
																						
											<tr>
												<td colspan="5">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
																<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_users.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
																	}?>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Dept_Name,F_Name,L_Name,Status,Status_Ch_Date,Email,Phone,Group,User_Type,Dept_No,Reg_Date,Last_login,Reports'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','User_ID,F_Name,L_Name,Dept_ID,Dept_Name,Phone,Birth_Date,License_No,Email,Tested_By,Date_Tested,Home_Country,Active,Status_Ch_Date,Group,User_Type,Register_Date,Permit,New,Renew_Date,Renew_Text,Reports'));}
											
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												
												<td width="50" class="colhead">Dept #</td>
												<td width="120" class="colhead">Dept Name</td>
												<td width="80" class="colhead">Name</td>
												<td width="70" class="colhead">Status</td>
												<td width="70" class="colhead">St. Ch. Dt.</td>
												<td width="110" class="colhead">Email</td>
												<td width="70" class="colhead">Phone</td>
												<td width="90" class="colhead">Group</td>
												<td width="35" class="colhead">Type</td>
												<td width="50" class="colhead">Reg.Dt</td>
												<td width="60" class="colhead">Lst Log</td>
												<td width="30" class="colhead">Rprt</td>
												
												
											</tr>
											<?		$listed	=	0;	
													while($rowUSER	=	mysql_fetch_array($rsUSERS)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowUSER["dept_id"].",".$rowUSER["dept_name"].",".$rowUSER["f_name"].",".$rowUSER["l_name"].",".$rowUSER["status"].",".$rowUSER["status_ch_date"].",".$rowUSER["email"].",".$rowUSER["phone"].",".$rowUSER["group_name"].",".$rowUSER["user_type"].",".$rowUSER["reg_date"].",".$rowUSER["last_login"].",".$rowUSER["driver_permission"]));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowUSER["user_id"].",".$rowUSER["f_name"].",".$rowUSER["l_name"].",".$rowUSER["dept_id"].",".$rowUSER["dept_name"].",".$rowUSER["phone"].",".$rowUSER["birth_date"].",".$rowUSER["license_no"].",".$rowUSER["email"].",".$rowUSER["drive_tested"].",".$rowUSER["test_date"].",".$rowUSER["home_st_country"].",".$rowUSER["status"].",".$rowUSER["status_ch_date"].",".$rowUSER["group_name"].",".$rowUSER["user_type"].",".$rowUSER["reg_date"].",".$rowUSER["permit_type"].",".$rowUSER["new_user"].",".$rowUSER["renew_date"].",".stripslashes($rowUSER["renew_text"].",".$rowUSER["driver_permission"])));}
														}
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															
															<td class="coldata"><? echo $rowUSER['dept_id'];?></td>
															<td class="coldata"><? echo $rowUSER['dept_name'];?></td>
															<td class="coldata"><? echo $rowUSER['f_name']." ".$rowUSER['l_name'];?></td>
															<td class="coldata"><? echo $rowUSER['status'];?></td>
															<td class="coldata"><? if($rowUSER['status_ch_date']!='N/A') echo fn_cDateMySql($rowUSER['status_ch_date'],2); else echo $rowUSER['status_ch_date'];?></td>
															<td class="coldata"><? echo $rowUSER['email'];?></td>
															<td class="coldata"><? echo $rowUSER['phone'];?></td>
															
															<td class="coldata"><? echo $rowUSER['group_name'];?></td>
															<td class="coldata"><? echo $rowUSER['user_type'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowUSER['reg_date'], 1);?></td>
															<td class="coldata"><? if($rowUSER['last_login']!='NOT LOGGED IN') echo fn_cDateMySql($rowUSER['last_login'],2); else echo 'NOT LOGGED IN';?></td>
															<td class="coldata"><? echo $rowUSER['driver_permission'];?></td>
															
															
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
 