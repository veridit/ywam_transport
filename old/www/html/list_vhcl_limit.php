<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	
	$sAction		=	"";
	
	if(isset($_POST["action"]) && $_POST["action"]!="")				{$sAction		=	$_POST["action"];}
	
	/*if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
		$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
		$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
		$sCriteriaSQL	.=	" AND vl.from_date >= '".$sStartDate."' AND vl.to_date<='".$sEndDate."'";
	}*/
	
	if($sAction=="delete"){			
		if(fn_DELETE_RECORD("tbl_3_vehicle_limit", "limit_id", $_POST["pid"]))
			$sMessage	=	fn_Print_MSG_BOX("3 vehicle limit override policy has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! policy not been deleted", "C_ERROR");
	}
	
	
	
		$sSQL	=	"SELECT vl.limit_id, vl.from_date, vl.to_date, d.dept_name ".
		"FROM tbl_3_vehicle_limit vl ".
		"INNER JOIN tbl_departments d ON vl.dept_id = d.dept_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY limit_id DESC";
		//print($sSQL);
		$rsLOG		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsLOG);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no over ride 3 vehicle policy found", "C_ERROR");
		}
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List Override 3 Vehicle Limit</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<script type="text/javascript">
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
	document.frm1.submit();
}

function fn_DELETE_POLICY(iPID){
	document.frm1.pid.value=iPID;
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
								   				<h1 style="margin-bottom: 0px;">RESTORE 3 VEHICLE LIMIT</h1>
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
						<form name="frm1" action="list_vhcl_limit.php" method="post">
							<input type="hidden" name="action" value="<?=$sAction?>"	/>
							<input type="hidden" name="pid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											
											<tr>
												<td colspan="6">
													<table width="100%">
														<tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/list_override_vehicle_limit.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr><td><hr /></td></tr>
								<?	if($iRECORD_COUNT>0){	
										if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
											$fp	=	"";
											$fp = fopen($sFname, 'w');
											fputcsv($fp, explode(',','Department,From_Date,To_Date'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="20" class="colhead">Department</td>
												<td width="100" class="colhead">From</td>
												<td width="100" class="colhead">To</td>
												<td width="100" class="colhead" align="center">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowLOG	=	mysql_fetch_array($rsLOG)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){	fputcsv($fp, explode(',', $rowLOG["dept_name"].",".$rowLOG['from_date'].",".$rowLOG["to_date"]));}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowLOG['dept_name'];?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['from_date'],1);?></td>
															<td class="coldata"><? echo fn_cDateMySql($rowLOG['to_date'],1);?></td>
															<td class="coldata" align="center"><a href="javascript:void(0);" onClick="if(confirm('Are you sure to delete this over-ride policy!')) {fn_DELETE_POLICY(<? echo $rowLOG['limit_id'];?>);} return false;">delete</a></td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0)	mysql_free_result($rsLOG);	?>
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