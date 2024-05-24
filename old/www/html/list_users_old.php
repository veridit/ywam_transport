<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	$iDeptID		=	0;
	$iGroupID		=	0;
	$sCriteriaSQL	=	"";
	$sTMSQL			=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$sLName			=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){	
	
		
		$sSQL	=	"DELETE FROM tbl_vehicles WHERE user_id = ".$_POST["userid"];
		$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL	=	"DELETE FROM tbl_shop_tasks WHERE user_id = ".$_POST["userid"];
		$rsDEL_SHOP	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL	=	"SELECT res_id FROM tbl_reservations WHERE user_id = ".$_POST["userid"];
		$rsRES	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsRES)>0){
			while($rowRES	=	mysql_fetch_array($rsRES)){
				$sSQL	=	"DELETE FROM tbl_trip_details WHERE res_id = ".$rowRES["res_id"];
				$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
			}
			$sSQL	=	"DELETE FROM tbl_reservations WHERE user_id = ".$_POST["userid"];
			$rsDEL_RES	=	mysql_query($sSQL) or die(mysql_error());
		}
		$sSQL	=	"DELETE FROM tbl_user_comments WHERE posting_user_id = ".$_POST["userid"]." OR about_user_id = ".$_POST["userid"];
		$rsDEL_USER_COMMENT	=	mysql_query($sSQL) or die(mysql_error());
		$sSQL	=	"DELETE FROM tbl_vehicle_comments WHERE posting_user_id = ".$_POST["userid"];
		$rsDEL_VEHICLE_COMMENT	=	mysql_query($sSQL) or die(mysql_error());
		
	
		$sSQL		=	"DELETE FROM tbl_user WHERE user_id = ".$_POST["userid"];
		$rsDEL_USER	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("user deleted successfully", "C_SUCCESS");
		
	}
	
	if(isset($_POST["drpusergroup"]) && $_POST["drpusergroup"]!="")		{$iGroupID	=	$_POST["drpusergroup"];		$sCriteriaSQL	.=	" AND tbl_user.user_group = ".$iGroupID;}
	if(isset($_POST["drpdept"]) && $_POST["drpdept"]!="")				{$iDeptID	=	$_POST["drpdept"];			$sCriteriaSQL	.=	" AND tbl_user.dept_id= ".$iDeptID;}
	if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	$_POST["drpstatus"];		$sCriteriaSQL	.=	" AND tbl_user.active = ".$sStatus;}
	if(isset($_POST["drplname"]) && $_POST["drplname"]!="")				{$sLName	=	$_POST["drplname"];			$sCriteriaSQL	.=	" AND tbl_user.l_name = '".$sLName."'";}
	//if($_SESSION["User_Group"]==$iGROUP_TC)			$sCriteriaSQL	.=	" AND tbl_user.user_group <> ".$iGROUP_TM;
	if($_SESSION["User_Group"]==$iGROUP_TM)			$sTMSQL	=	" AND tbl_user.user_group <> ".$iGROUP_TC;
	
	$sSQL	=	"SELECT user_id, tbl_user.dept_id, dept_name, f_name, l_name, phone, email, group_name FROM tbl_user ".
	"INNER JOIN tbl_departments ON tbl_user.dept_id = tbl_departments.dept_id ".
	"INNER JOIN tbl_user_group ON tbl_user.user_group = tbl_user_group.group_id ".
	"WHERE 1=1 ".$sCriteriaSQL.$sTMSQL;
	//print($sSQL);
	$rsUSERS		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsUSERS);
	if($iRECORD_COUNT<=0){
		$sMessage		=	fn_Print_MSG_BOX("no user found", "C_ERROR");
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>List Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript">
<!--
function F_loadRollover(){} function F_roll(){}
//-->
</script>
<script type="text/javascript" src="../assets/rollover.js">
</script>
<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">



<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.submit();
}

function fn_DELETE_USER(iUSERID){
	
	document.frm1.userid.value=iUSERID;
	document.frm1.action.value='delete';
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
								   				<h1 style="margin-bottom: 0px;">LIST USERS</h1>
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
						<form name="frm1" action="list_users.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="userid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="680" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label">User Group:<br /><?	fn_USER_GROUP('drpusergroup', $iGroupID, "150", "1", "All Groups");?></td>
												<td class="label">Last Name:<br />
												<?
													$sSQL	=	"SELECT l_name FROM tbl_user WHERE 1=1 ".$sTMSQL." ORDER BY l_name";
													$rsLNAME	=	mysql_query($sSQL) or die(mysql_error());
													if(mysql_num_rows($rsLNAME)>0){?>
													<select name="drplname" size="1" style="width:150px;">
													<option value="">--All--</option>
												<?	while($rowLNAME	=	mysql_fetch_array($rsLNAME)){	?>
														<option value="<? echo $rowLNAME['l_name'];?>" <? if($rowLNAME['l_name'] == $sLName) echo "selected";?>><? echo $rowLNAME['l_name'];?></option>
												<?	}?>
													</select>
												<?	}mysql_free_result($rsLNAME);?>
												</td>
												<td class="label">Department:<br /><?	fn_DEPARTMENT('drpdept', $iDeptID, "150", "1", "All Departments");?></td>
												
												<td class="label">Status:<br />
													<?
														$arrSTATUS[0][0]	=	"1";
														$arrSTATUS[0][1]	=	"Active";
														$arrSTATUS[1][0]	=	"0";
														$arrSTATUS[1][1]	=	"InActive";
													?>
													<select name="drpstatus" size="1" style="width:100px;">
														<option value="">Any</option>
														<?	for($iCOUNTER=0;$iCOUNTER<=1;$iCOUNTER++){?>
															<option value="<?=$arrSTATUS[$iCOUNTER][0]?>" <? if($arrSTATUS[$iCOUNTER][0]==$sStatus) echo "selected";?>><?=$arrSTATUS[$iCOUNTER][1]?></option>
														<?	}?>
													</select>
												</td>
												<td>&nbsp;<br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
											<tr><td colspan="4"><table width="100%"><tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_users.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr></table></td></tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Dept_Name,F_Name,L_Name,Phone,Email,Group'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="100" class="colhead">Dept Name</td>
												<td width="65" class="colhead">F Name</td>
												<td width="65" class="colhead">L Name</td>
												<td width="75" class="colhead">Phone</td>
												<td width="130" class="colhead">Email</td>
												<td width="110" class="colhead">Group</td>
												<td width="65" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowUSER	=	mysql_fetch_array($rsUSERS)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv") fputcsv($fp, explode(',', $rowUSER["dept_name"].",".$rowUSER["f_name"].",".$rowUSER["l_name"].",".$rowUSER["phone"].",".$rowUSER["email"].",".$rowUSER["group_name"]));
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowUSER['dept_name'];?></td>
															<td class="coldata"><? echo $rowUSER['f_name'];?></td>
															<td class="coldata"><? echo $rowUSER['l_name'];?></td>
															<td class="coldata"><? echo $rowUSER['phone'];?></td>
															<td class="coldata"><? echo $rowUSER['email'];?></td>
															<td class="coldata"><? echo $rowUSER['group_name'];?></td>
															<td class="coldata"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_MODIFY)){?><a href="edit_user.php?userid=<? echo $rowUSER['user_id'];?>">view</a><?	}?>&nbsp;<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('are you sure to delete this user?')) {fn_DELETE_USER(<? echo $rowUSER['user_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
										
									}mysql_free_result($rsUSERS);	?>
										</table>
									</td>
								</tr>
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
 