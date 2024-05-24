<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');
	require("class.phpmailer.php");

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sDEPT_ID		=	"";
	$iLEADER_ID		=	0;
	$sStatus		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	//$sMessage		=	fn_Print_MSG_BOX("<li>When a dept. is deactivated, all drivers registered under that dept. are deactivated and are notified by email<li>DEACTIVATED DEPARTMENTS WILL BE DELETED 90 DAYS AFTER DEACTIVATION", "C_SUCCESS");
	$sMessage		=	fn_Print_MSG_BOX("<li>Duplicate emails are OK except within one department", "C_SUCCESS");
	$sSORT_ORDER	=	"d.dept_id+0";
	$sDEPT_NO		=	"";
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){	

		$sDEPT_NO = mysql_real_escape_string($_POST["deptid"]);
		
		if(fn_DEL_DEPT($sDEPT_NO))
			$sMessage	=	fn_Print_MSG_BOX("department and all its related records has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! department is not been deleted", "C_ERROR");
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="Deactivate"){		//deactivate the department and all users
		$date = date("Y-m-d");// current date
		$sSQL		=	"UPDATE tbl_departments SET active = 0, deactive_date = '".date("Y-m-d",strtotime(date("Y-m-d", strtotime($date)) . " +90 day"))."' WHERE dept_id = '".$_POST["deptid"]."'";
		$rsDEPT_DEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		$sSQL		=	"UPDATE tbl_user SET active = 0 WHERE dept_id = '".$sDEPT_NO."'";
		$rsUSER_DEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		//now send notification email to all drivers
		$sSQL	=	"SELECT f_name, l_name, email FROM tbl_user WHERE user_group = ".$iGROUP_DRIVER." AND dept_id = '".$_POST["deptid"]."'";
		$rsDRIVERS	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsDRIVERS)>0){
			while($rowDRIVER	=	mysql_fetch_array($rsDRIVERS)){
			
					$sFName	=	$rowDRIVER['f_name'];
					$sLName	=	$rowDRIVER['l_name'];
					$sEmail	=	$rowDRIVER['email'];
			
					//$sEmailSubject	=	"Inactive Notification From $sCOMPANY_Name";	
					//$sMailMSG		=	"Dear User!<br /> you are inactive status now,<br />Please contact with TM to get reassigned to an active department";
					
					$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--SUBJECT--')+15, (INSTR(tbl_info_links.link_text, '--END SUBJECT--') -1)-(INSTR(tbl_info_links.link_text, '--SUBJECT--')+15)) FROM tbl_info_links WHERE link_id = 16";
					$sEmailSubject	=	str_replace('&nbsp;',' ', strip_tags(stripslashes(mysql_result(mysql_query($sSQL),0))));
					$sSQL			=	"SELECT MID(tbl_info_links.link_text, INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20, (INSTR(tbl_info_links.link_text, '--END MESSAGE BODY--') -3)-(INSTR(tbl_info_links.link_text, '--MESSAGE BODY--')+20)) FROM tbl_info_links WHERE link_id = 16";
					$sMailMSG		=	stripslashes(mysql_result(mysql_query($sSQL),0));
					
					//print($sMailMSG);
					$mail = new PHPMailer();
					$mail->Host     = $sCOMPANY_SMTP; // SMTP servers
					$mail->From     = $sSUPPORT_EMAIL;
					$mail->FromName = $sCOMPANY_Name;
					$mail->AddAddress($sEmail);
					
					//$mail->AddCC('transportation@uofnkona.edu');
					$mail->IsHTML(true);                               // send as HTML
					$mail->Subject  =  $sEmailSubject;
					$mail->Body    = $sMailMSG;
					
										
					if(!$mail->Send())	{	$sMessage	.=	fn_Print_MSG_BOX("Driver $sFName $sLName been deactivated, butError in Sending Email, $mail->ErrorInfo","C_ERROR");		}
			
			}
		}mysql_free_result($rsDRIVERS);
		
		
		
		$sMessage	=	fn_Print_MSG_BOX("department has been deactivated", "C_SUCCESS");
		
	}elseif(isset($_POST["action"])	&& $_POST["action"]=="Activate"){		//deactivate the department and all users
		$sSQL		=	"UPDATE tbl_departments SET active = 1, deactive_date = NULL WHERE dept_id = '".$_POST["deptid"]."'";
		$rsDEPT_DEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		$sSQL		=	"UPDATE tbl_user SET active = 1 WHERE dept_id = '".$_POST["deptid"]."'";
		$rsUSER_DEACTIVE	=	mysql_query($sSQL) or die(mysql_error());
		
		$sMessage	=	fn_Print_MSG_BOX("department has been activated", "C_SUCCESS");
		
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["txtdeptno"]) && $_POST["txtdeptno"]!="")			{$sDEPT_ID	=	mysql_real_escape_string($_POST["txtdeptno"]);		$sCriteriaSQL	.=	" AND d.dept_id = '".$sDEPT_ID."'";}
		if(isset($_POST["drpleader"]) && $_POST["drpleader"]!="")			{$iLEADER_ID=	$_POST["drpleader"];		$sCriteriaSQL	.=	" AND d.dept_name LIKE '%".$iLEADER_ID."%'";}
		if(isset($_POST["drpstatus"]) && $_POST["drpstatus"]!="")			{$sStatus	=	mysql_real_escape_string($_POST["drpstatus"]);		$sCriteriaSQL	.=	" AND d.active = ".$sStatus;}
		if(isset($_POST["drpsort"]) && $_POST["drpsort"]!="")				{$sSORT_ORDER	= mysql_real_escape_string($_POST["drpsort"]);	}
		
		$sSQL	=	"SELECT d.*, CASE WHEN d.active = 1 THEN 'ACTIVE' ELSE 'INACTIVE' END AS status, ".
		"d.dept_id, d.dept_name, CONCAT(d.leader_f_name, ' ', d.leader_l_name) AS leader_name, leader_email FROM tbl_departments d ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY ".$sSORT_ORDER;
		//print($sSQL);
		$rsDEPT		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsDEPT);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no department found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?Php if($_SESSION["User_Group"]==$iGROUP_TC) echo "Departments: Deactivate-List-Edit"; else echo "List Departments";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">


<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
<!--<script type="text/javascript" src="./js/overlib_mini.js"></script>-->
<script type="text/javascript">
	/*var ol_fgcolor	=	"#FFEBD7";
	var ol_bgcolor	=	"#CA0000";
	var ol_textfont	=	"Arial,    Helvetica,    Geneva,    Sans-serif";
	var ol_textsize	=	"2";
	var ol_wrap		= 	1;
	var ol_width	=	"150";*/
	
	
function fn_STATUS(bSTATUS, iDEPT_ID){
	document.frm1.action.value=bSTATUS;
	document.frm1.deptid.value=iDEPT_ID;
	document.frm1.submit();
}
function fn_SEARCH(){
	document.frm1.action.value='search';
	document.frm1.pg.value	=	'1';
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
<!--<div id="overDiv" style="Z-INDEX: 1000; VISIBILITY: hidden; POSITION: absolute"></div>-->
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
								   				<h1 style="margin-bottom: 0px;"><?Php if($_SESSION["User_Group"]==$iGROUP_TC) echo "DEPARTMENTS: DEACTIVATE-LIST-EDIT"; else echo "LIST DEPARTMENTS";?></h1>
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
						<form name="frm1" action="list_dept.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="deptid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												
												<td class="label" width="100">Dept. No:<br /><input type="text" name="txtdeptno" style="width:50px;" value="<? if($sDEPT_ID!="") echo $sDEPT_ID; else echo "";?>" /></td>
												
												
												<td class="label" width="100">Status:<br />
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
												<td class="label" width="130">Sort By:<br />
													
													<select name="drpsort" style="width:100px;" size="1">
														<option value="">--Sort Order--</option>
														<option value="d.dept_id ASC" 		<? if($sSORT_ORDER == "(d.dept_id+0) ASC") echo "selected";?>>Dept No A-Z</option>
														<option value="d.dept_id DESC" 	<? if($sSORT_ORDER == "(d.dept_id+0) DESC") echo "selected";?>>Dept No Z-A</option>	
														<option value="d.dept_name ASC" 	<? if($sSORT_ORDER == "d.dept_name ASC") echo "selected";?>>Dept. Name A-Z</option>
														<option value="d.dept_name DESC"  <? if($sSORT_ORDER == "d.dept_name DESC") echo "selected";?>>Dept. Name Z-A</option>
														<option value="d.leader_email ASC"  <? if($sSORT_ORDER == "d.leader_email ASC") echo "selected";?>>Email A-Z</option>
														<option value="d.leader_email DESC"  <? if($sSORT_ORDER == "d.leader_email DESC") echo "selected";?>>Email Z-A</option>
														<option value="leader_name ASC"  <? if($sSORT_ORDER == "leader_name ASC") echo "selected";?>>Leader Name A-Z</option>
														<option value="leader_name DESC"  <? if($sSORT_ORDER == "leader_name DESC") echo "selected";?>>Leader Name Z-A</option>
													</select>
												</td>
												<td><br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_SEARCH();" /></td>
											</tr>
											<tr>
												<td colspan="6">
													<table width="100%">
														<tr>
															<td width="70%" class="label">
																<input type="radio" name="optExcelReport" value="flds">Generate Excel Report with these fields<br />
																<input type="radio" name="optExcelReport" value="cols">Generate Excel Report with all columns in table
															</td>
															<td width="50%" align="right" class="label">
																<? 	if(isset($_POST["optExcelReport"]) && ($_POST["optExcelReport"]	==	"flds" || $_POST["optExcelReport"]	==	"cols") && $iRECORD_COUNT>0){
																		$sFname	=	'excel_reports/list_depts.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");
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
											if($_POST["optExcelReport"]	==	"flds"){fputcsv($fp, explode(',','Dept_No,Dept_Name,Status,Status_Date,Leader_Name,Email'));}
											if($_POST["optExcelReport"]	==	"cols"){fputcsv($fp, explode(',','Dept_No,Dept_Name,Status,Leader_F_Name,Leader_L_Name,Leader_Phone,Leader_Email,Reg_Date'));}
											
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="50" class="colhead">Dept.No</td>
												<td width="130" class="colhead">Dept. Name</td>
												<td width="50" class="colhead">Status</td>
												<td width="100" class="colhead">Status Ch. Dt</td>
												<td width="130" class="colhead">Leader Name</td>
												<td width="180" class="colhead">Email</td>
												<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_STATUS)){?><td width="80" class="colhead" align="center">Action</td><?Php }?>
											</tr>
											<?		$listed	=	0;	
													while($rowDEPT	=	mysql_fetch_array($rsDEPT)){
														if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!=""){
															if($_POST["optExcelReport"]	==	"flds"){ fputcsv($fp, explode(',', $rowDEPT["dept_id"].",".$rowDEPT["dept_name"].",".$rowDEPT["status"].",".$rowDEPT["deactive_date"].",".$rowDEPT['leader_name'].",".$rowDEPT["leader_email"]));}
															if($_POST["optExcelReport"]	==	"cols"){ fputcsv($fp, explode(',', $rowDEPT["dept_id"].",".$rowDEPT["dept_name"].",".$rowDEPT["status"].",".$rowDEPT["leader_f_name"].",".$rowDEPT["leader_l_name"].",".$rowDEPT["leader_phone"].",".$rowDEPT["leader_email"].",".$rowDEPT["reg_date"]));}
														}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowDEPT['dept_id'];?></td>
															<td class="coldata"><? echo $rowDEPT['dept_name'];?></td>
															<?Php //if($rowDEPT['active']==0)	{
																//$sDEACTIVATE	= 	"delete date:&nbsp;".fn_cDateMySql($rowDEPT['deactive_date'],1); $sBG_COLOR = '#FF6633;';
																//echo "<td class='coldata' style='background-color:".$sBG_COLOR."' onmouseover='return overlib(\"$sDEACTIVATE\");' onmouseout='return nd();'>".$rowDEPT['status']."</td>";
																echo "<td class='coldata'>".$rowDEPT['status']."</td>";
																//} else {
																//$sDEACTIVATE	=	''; $sBG_COLOR = '#FFF;';
																//echo "<td class='coldata'>".$rowDEPT['status']."</td>";
																//}
															?>
															<td class="coldata"><? if($rowDEPT['deactive_date']=="0000-00-00") echo "N/A";	else echo fn_cDateMySql($rowDEPT['deactive_date'],1);?></td>
															<td class="coldata"><? echo $rowDEPT['leader_name'];?></td>
															<td class="coldata"><? echo $rowDEPT['leader_email'];?></td>
															<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_MODIFY) && fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_STATUS)){?>
															<td class="coldata" align="center">
															
															<a href="edit_dept.php?did=<? echo $rowDEPT['dept_id'];?>">change</a>
															&nbsp;/&nbsp;
															<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_DELETE)){?>
															<a href="javascript:void(0);" onClick="if(confirm('Warning: deleting a department will delete all users (admin, tm and drivers) and trips associated with it!')) {fn_DELETE_DEPT(<? echo $rowDEPT['dept_id'];?>);} return false;">delete</a>
															<?	}?>&nbsp;/&nbsp;
															<? //if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_DEPARTMENT_STATUS)){
																	if($rowDEPT['active']==1) {
																		$sSTATUS	=	'Deactivate';	$sA_TITLE	=	'deactivate department';
															?>
																		<a href="javascript:void(0);" onClick="if(confirm('This dept will automatically removed from the system in 90 days')) {fn_STATUS('<?Php echo $sSTATUS;?>', <? echo $rowDEPT['dept_id'];?>);} return false; " title="<?Php echo $sA_TITLE;?>"><?Php echo $sSTATUS?></a>
															<?		}else{
																		$sSTATUS	=	'Activate';	$sA_TITLE	=	'activate department';
															?>
																		<a href="javascript:void(0);" onClick="fn_STATUS('<?Php echo $sSTATUS;?>', <? echo $rowDEPT['dept_id'];?>); " title="<?Php echo $sA_TITLE;?>"><?Php echo $sSTATUS?></a>
															<?		}
																//}
															?>
															
															</td>
															<?	}?>
														</tr>
											<?			}$listed++;
													}
											?>
										</table>
									</td>
								</tr>
								<?		if (isset($_POST["optExcelReport"]) && $_POST["optExcelReport"]!="")			fclose($fp);
								
									}if($iRECORD_COUNT>0)mysql_free_result($rsDEPT);	?>
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
 
