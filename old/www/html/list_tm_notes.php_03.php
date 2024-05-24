<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	include('inc_pagination_settings.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$iUSER_ID		=	"";
	$sStartDate		=	"";
	$sEndDate		=	"";
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	"";
	$iDays			=	0;
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){			
		if(fn_DELETE_RECORD("tbl_user_comments", "id", $_POST["noteid"]))
			$sMessage	=	fn_Print_MSG_BOX("Notes has been deleted", "C_SUCCESS");
		else
			$sMessage	=	fn_Print_MSG_BOX("error! in deleting notes", "C_ERROR");
	}
	
	if(isset($_POST["action"])	&& $_POST["action"]=="comments"){
		$sSQL="UPDATE tbl_user_comments SET posting_user_id = ".$_SESSION["User_ID"].", comments = '".addslashes($_POST["txtcomments"])."' WHERE id =  ".$_POST["noteid"];
		$rsCOMMENTS=mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("notes are updated successfully", "C_SUCCESS");	
	}
	
	
	if(isset($_POST["action"])	&& $_POST["action"]=="search"){
	
		if(isset($_POST["drpuser"]) && $_POST["drpuser"]!="")			{$iUSER_ID	=	$_POST["drpuser"];		$sCriteriaSQL	.=	" AND tbl_user_comments.about_user_id = ".$iUSER_ID;}
		if(isset($_POST["drpnotesdays"]) && $_POST["drpnotesdays"]!=""){
			$iDays			=	$_POST["drpnotesdays"];
			if($_POST["drpnotesdays"]=="361")
				$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_user_comments.comments_date) > ".$iDays;
			else
				$sCriteriaSQL	.=	" AND DATEDIFF(CURDATE(), tbl_user_comments.comments_date) <= ".$iDays;
		}
		
		if(isset($_POST["txtstartdate"]) && isset($_POST["txtenddate"]) && ($_POST["txtstartdate"]!="" && $_POST["txtenddate"]!="")){
			$sStartDate		=	fn_DATE_TO_MYSQL($_POST["txtstartdate"]);
			$sEndDate		=	fn_DATE_TO_MYSQL($_POST["txtenddate"]);		
			$sCriteriaSQL	.=	" AND DATE(tbl_user_comments.comments_date) BETWEEN '".$sStartDate."' AND '".$sEndDate."'";
		}
		
		
		$sSQL	=	"SELECT tbl_user_comments.id, tbl_user_comments.about_user_id, tbl_user_comments.comments, ".
		"tbl_user.f_name, tbl_user.l_name, DATE_FORMAT(tbl_user_comments.comments_date, '%m/%d/%Y %r') AS comments_date, ".
		"CASE WHEN trip_id IS NULL OR 0 THEN ' ' ELSE trip_id END AS res_id ".
		"FROM tbl_user_comments ".
		"INNER JOIN tbl_user ON tbl_user_comments.about_user_id = tbl_user.user_id ".
		"WHERE 1=1 ".$sCriteriaSQL." ORDER BY tbl_user.l_name ASC";
		//print($sSQL);
		$rsNOTES		=	mysql_query($sSQL) or die(mysql_error());
		$iRECORD_COUNT	=	mysql_num_rows($rsNOTES);
		if($iRECORD_COUNT<=0){
			$sMessage		=	fn_Print_MSG_BOX("<li>no notes found", "C_ERROR");
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>List TM Notes on Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">
<script type="text/javascript" src="./js/common_scripts.js"></script>
<!-- firebug lite -->
		<script type="text/javascript" src="./js/firebug.js"></script>

        <!-- jQuery -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>
        
        <!-- required plugins -->
		<script type="text/javascript" src="./js/date.js"></script>
		<!--[if lt IE 7]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->
        
        <!-- jquery.datePicker.js -->
		<script type="text/javascript" src="./js/jquery.datePicker.js"></script>
        
        <!-- datePicker required styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/datePicker.css">
		
        <!-- page specific styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./css/demo.css">
        
        <!-- page specific scripts -->
		<script type="text/javascript" charset="utf-8">
			Date.format = 'mm/dd/yyyy';
            $(function()
            {
				$('.date-pick').datePicker({startDate: '01/01/1970', autoFocusNextInput: true});
            });
		</script>
<script type="text/javascript">

function fn_DELETE_NOTES(iNOTEID){
	document.frm1.noteid.value=iNOTEID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}

function fn_VALID_COMMENTS(){
	var sErrMessage="";
	var iErrCounter=0;
	if(document.frm1.txtcomments.value==""){
		sErrMessage='<li>please enter notes';
		iErrCounter++;
	}
	if (iErrCounter >0){
		document.getElementById('CommentMessage').style.display	=	'block';
		document.getElementById('CommentMessage').innerHTML="<table width='100%'><tr><td class='Err'>"+sErrMessage+"</td></tr></table>";
	}else{
		document.frm1.action.value='comments';
		document.frm1.submit();
	}
}

function fn_SHOW_HIDE_COMMENT_BOX(iNoteID, bDisplay){

	document.frm1.noteid.value=iNoteID;
	
	if(bDisplay=='block'){
		centerPopup();
		//call ajax function to load comments
		ajax_NOTES(iNoteID);
	}
	
	document.getElementById('comment_box').style.display	=	bDisplay;
	
	document.getElementById('CommentMessage').style.display	=	'none';
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	
	var popupHeight = 250;
	var popupWidth = 400;
	//centering

	document.getElementById("comment_box").style.position	=	"fixed";
	$('#comment_box').css('top', windowHeight/2-popupHeight/2);
	$('#comment_box').css('left', windowWidth/2-popupWidth/2);
	
}
function ajax_NOTES(nid){
var xmlhttp;
var sURL = "ajax_data.php?";
var sData	=	"";
try
{
		// Firefox, Opera 8.0+, Safari
		xmlhttp=new XMLHttpRequest();
}
catch (e){
	 try {
	  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	 } catch (e) {
		  try {
			   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
			   xmlhttp = false;
			}
	 }
}

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
 xmlhttp.open("GET", sURL+"action=tmnotes&nid="+nid,true);
 xmlhttp.onreadystatechange=function() {
	if(xmlhttp.readyState == 1)
		{
			if (document.getElementById && document.getElementById('CommentMessage'))
				document.getElementById('CommentMessage').innerHTML = "<img src=../assets/images/loading_busy.gif border='0'>";
			
		}


	if (xmlhttp.readyState==4) {
   
		
		sData	=	xmlhttp.responseText;
		document.frm1.txtcomments.value	=	sData;
		
		document.getElementById('CommentMessage').innerHTML = '';
		document.getElementById('CommentMessage').style.display = 'none';
	
	}
 }
 xmlhttp.send(null)
}
</script>

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">
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
								   				<h1 style="margin-bottom: 0px;">LIST TM NOTES ABOUT DRIVERS</h1>
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
						<form name="frm1" action="list_tm_notes.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="noteid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>

							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="10" border="0" width="100%" align="center">
											<tr>
												<td class="label" width="150">User Last Name:<br />
												<?	fn_DISPLAY_USERS('drpuser', $iUSER_ID, "150", "1", "--All--", "CONCAT(l_name, ' ', f_name) AS user_name", "l_name", $iGROUP_TM.",".$iGROUP_TC.",".$iGROUP_DRIVER.",".$iGROUP_SERVICETCH.",".$iGROUP_COORDINATOR_STAFF);?></td>
												<td class="label" width="150">From <br />
													<input type="text" name="txtstartdate" id="txtstartdate" value="<? if($sStartDate!="") echo fn_cDateMySql($sStartDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>
												<td class="label" width="150">To<br />
													<input type="text" name="txtenddate" id="txtenddate" value="<? if($sEndDate!="") echo fn_cDateMySql($sEndDate,1);?>" maxlength="10" style="width:100px;" class="date-pick dp-applied" />
												</td>											
												<td><br /><input type="button" name="btnGO" value=" GO " class="Button" style="width:50px;" onClick="fn_RPT_DT_SEARCH();" /></td>
											</tr>
											<tr>
												<td colspan="4">
													<table width="100%">
														<tr><td width="50%" class="label"><input type="checkbox" name="chkCSV" value="csv">Generate Excel Report</td><td width="50%" align="right" class="label"><? if(isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv" && $iRECORD_COUNT>0){ $sFname	=	'excel_reports/notes_on_users.csv'; print("<a href='download.php?f=$sFname&Dir=./excel_reports/'>download excel file</a>&nbsp;&nbsp;&nbsp;");}?></td></tr>
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
											fputcsv($fp, explode(',','First_Name,Last_Name,Notes_by_TM,Notes_Date,Resv_No'));
										}
								?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="70" class="colhead">First Name</td>
												<td width="70" class="colhead">Last Name</td>
												<td width="200" class="colhead">Notes By TM</td>
												<td width="120" class="colhead">Notes Date</td>
												<td width="50" class="colhead">Resv #</td>
												<td width="70" class="colhead">Action</td>
											</tr>
											<?		$listed	=	0;	
													while($rowNOTES	=	mysql_fetch_array($rsNOTES)){
														if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv"){
															fputcsv($fp, explode(',', $rowNOTES["f_name"].",".$rowNOTES["l_name"].",".str_replace(","," ",stripslashes($rowNOTES['comments'])).",".$rowNOTES["comments_date"].",".$rowNOTES["res_id"]));
														}
														
														if($listed>=$cur_rows && $listed< $max_rows){
											?>			<tr>
															<td class="coldata leftbox"><? echo $rowNOTES['f_name'];?></td>
															<td class="coldata"><? echo $rowNOTES['l_name'];?></td>
															<td class="coldata"><? echo stripslashes($rowNOTES['comments']);?></td>
															<td class="coldata"><? echo $rowNOTES["comments_date"];?></td>
															<td class="coldata"><? echo $rowNOTES['res_id'];?></td>
															<td class="coldata" align="center">
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_COMMENT_DELETE)){?><a href="javascript:void(0);" onClick="if(confirm('Are you sure to delete this note?')) {fn_DELETE_NOTES(<? echo $rowNOTES['id'];?>);} return false;">delete</a><?	}?>&nbsp;/&nbsp;
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_USER_COMMENT_MODIFY)){?><a href="javascript:void(0);" onClick="document.frm1.noteid.value=<? echo $rowNOTES['id'];?>;fn_SHOW_HIDE_COMMENT_BOX(<? echo $rowNOTES['id'];?>,'block');" title="Edit Notes TM">edit</a><?	}?>
															</td>
														</tr>
											<?			}$listed++;
													}
													if (isset($_POST["chkCSV"]) && $_POST["chkCSV"]	==	"csv")			fclose($fp);
											?>
								
										</table>
									</td>
								</tr>
								<?	}	if($iRECORD_COUNT>0) mysql_free_result($rsNOTES);	?>
								<tr><td><? include('inc_paginationlinks.php');	?></td></tr>
								<tr>
									<td>
										
										<div id="comment_box" style="width:400px; height:250px; display:none; z-index:5; top:10px; left:10px; position:fixed; background-color:#fff;">
											
											<table cellpadding="0" cellspacing="5" border="0" width="400" align="center" class="box" height="250">
												<tr><td colspan="2" id="CommentMessage" width="100%"></td></tr>
												
												<tr>
													<td class="label" valign="top">Comments:</td>
													<td><textarea name="txtcomments" id="txtcomments" cols="50" rows="10" style="width:250px;" ></textarea></td>
												</tr>
												
												<tr><td colspan="2">&nbsp;</td></tr>
												<tr><td></td><td><input type="button" name="btnCOMMENTS" value="EDIT NOTES" class="Button" onClick="fn_VALID_COMMENTS();" />&nbsp;<input type="button" name="btnCANCEL" value="CANCEL" class="Button" onClick="fn_SHOW_HIDE_COMMENT_BOX(0,'none');" /></td></tr>
											</table>
										</div>
									</td>
								</tr>
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
 