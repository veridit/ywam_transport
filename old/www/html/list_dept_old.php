<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sDEPT_ID		=	"";
	$sDEPT_NAME		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){	
	
		$sSQL	=	"SELECT user_id FROM tbl_user WHERE dept_id = ".$_POST["deptid"];
		$rsUSER	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsUSER)>0){
			while($rowUSER	=	mysql_fetch_array($rsUSER)){
				$sSQL	=	"DELETE FROM tbl_vehicles WHERE user_id = ".$rowUSER['user_id'];
				$rsDEL_VEHICLE	=	mysql_query($sSQL) or die(mysql_error());
				$sSQL	=	"DELETE FROM tbl_shop_tasks WHERE user_id = ".$rowUSER['user_id'];
				$rsDEL_SHOP	=	mysql_query($sSQL) or die(mysql_error());
				$sSQL	=	"DELETE FROM tbl_reservations WHERE user_id = ".$rowUSER['user_id'];
				$rsDEL_TRIP	=	mysql_query($sSQL) or die(mysql_error());
			}
		}
	
		$sSQL		=	"DELETE FROM tbl_user WHERE dept_id = ".$_POST["deptid"];
		$rsDEL_USER	=	mysql_query($sSQL) or die(mysql_error());
		
		if(fn_DELETE_RECORD("tbl_departments", "dept_id", $_POST["deptid"]))
			$sMessage	=	fn_Print_MSG_BOX("department and all its related records has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! department is not been deleted", "C_ERROR");
		
	}
	
	
	
	
	if(isset($_POST["txtdeptno"]) && $_POST["txtdeptno"]!="")			{$sDEPT_ID	=	$_POST["txtdeptno"];		$sCriteriaSQL	.=	" AND tbl_departments.dept_id = '".$sDEPT_ID."'";}
	if(isset($_POST["txtdeptname"]) && $_POST["txtdeptname"]!="")		{$sDEPT_NAME=	$_POST["txtdeptname"];		$sCriteriaSQL	.=	" AND tbl_departments.dept_name LIKE '%".$sDEPT_NAME."%'";}
	
	$sSQL	=	"SELECT dept_id, dept_name, CONCAT(leader_f_name, ' ', leader_l_name) AS leader_name, leader_email FROM tbl_departments ".
	"WHERE 1=1 ".$sCriteriaSQL;
	//print($sSQL);
	$rsDEPT		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsDEPT);
	if($iRECORD_COUNT<=0){
		$sMessage		=	fn_Print_MSG_BOX("<li>no department found", "C_ERROR");
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>List Departments</title>
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

function fn_DELETE_DEPT(iDEPTID){
	document.frm1.deptid.value=iDEPTID;
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
								   				<h1 style="margin-bottom: 0px;">LIST DEPARTMENTS</h1>
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
						<form name="frm1" action="list_dept.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="deptid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="680" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="100">Department No:</td>
												<td><input type="text" name="txtdeptno" style="width:50px;" value="<? if($sDEPT_ID!="") echo $sDEPT_ID; else echo "";?>" /></td>
												<td class="label" width="130">Department Name:</td>
												<td><input type="text" name="txtdeptname" style="width:150px;" value="<? if($sDEPT_NAME!="") echo $sDEPT_NAME; else echo "";?>" /></td>
												
												<td><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
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
												<td width="50" class="colhead">Dept.No</td>
												<td width="150" class="colhead">Dept. Name</td>
												<td width="160" class="colhead">Leader Name</td>
												<td width="150" class="colhead">Email</td>
												<td width="90" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowDEPT	=	mysql_fetch_array($rsDEPT)){
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowDEPT['dept_id'];?></td>
															<td class="coldata"><? echo $rowDEPT['dept_name'];?></td>
															<td class="coldata"><? echo $rowDEPT['leader_name'];?></td>
															<td class="coldata"><? echo $rowDEPT['leader_email'];?></td>

															<td class="coldata" align="center"><? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_MODIFY)){?><a href="edit_dept.php?did=<? echo $rowDEPT['dept_id'];?>">view</a><?	}?>&nbsp;/&nbsp;<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_VEHICLES_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('are you sure to delete this department?')) {fn_DELETE_DEPT(<? echo $rowDEPT['dept_id'];?>);} return false;">delete</a><?	}?></td>
														</tr>
											<?			}$listed++;
													}?>
								<?	}mysql_free_result($rsDEPT);	?>
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
 