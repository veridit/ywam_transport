<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	$sStatus		=	"";
	$iDeptID		=	0;
	$iGroupID		=	0;
	$sCriteriaSQL	=	"";
	$iRECORD_COUNT	=	0;
	$sMessage		=	fn_Print_MSG_BOX("<li>the first nine messages below are automatically sent to the drivers by the system", "C_SUCCESS");
	if(isset($_POST["action"])	&& $_POST["action"]=="delete"){	
		$sSQL		=	"DELETE FROM tbl_info_links WHERE link_id = ".$_POST["id"];
		$rsDEL_LINK	=	mysql_query($sSQL) or die(mysql_error());
		$sMessage		=	fn_Print_MSG_BOX("information link deleted successfully", "C_SUCCESS");
	} else if (isset($_POST["action"]) && $_POST["action"] == "update") {
	    mysql_query("UPDATE tbl_info_links SET link_display_flag = 1 where link_id in (".$_POST["hddnattr"].")") or die(mysql_error());
	    mysql_query("UPDATE tbl_info_links SET link_display_flag = 0 where link_id in (".$_POST["httnaddr"].")") or die(mysql_error());
	    fn_Print_MSG_BOX("Display setting updated :)", "C_SUCCESS");
	}
	

	$sSQL	=	"SELECT link_id, link_title, link_display_flag as flg, DATE_FORMAT(link_date, '%m-%d-%Y %l:%i %p') AS link_date FROM tbl_info_links ORDER BY link_order DESC";
	//print($sSQL);
	$rsLINKS		=	mysql_query($sSQL) or die(mysql_error());
	$iRECORD_COUNT	=	mysql_num_rows($rsLINKS);
	if($iRECORD_COUNT<=0){
		$sMessage		=	fn_Print_MSG_BOX("no information link found", "C_ERROR");
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>OUTGOING MESSAGES-EDIT</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


<script type="text/javascript">
function fn_DELETE_LINK(iLINKID){
	
	document.frm1.id.value=iLINKID;
	document.frm1.action.value='delete';
	document.frm1.submit();
}

function fn_PRINT(iPageID){
	var url="printpage.php?a=print&id="+iPageID;
	var myWindow	=	window.open(url,"_blank","height=600, width=800, resizable=no, scrollbars=yes");
}

function displayChecker() {
    var chklst = document.frm1.ckbx;
    var cklst_string = "";
    var chlst_string = "";
    
    for (var i = 0; i < chklst.length; i++) {
        var chkval = chklst[i];
        if (chkval.checked) {
            cklst_string += chkval.value + ", ";
        } else {
            chlst_string += chkval.value + ", ";
        }
    }
    document.frm1.hddnattr.value = cklst_string.substring(0, cklst_string.length - 2);
    document.frm1.httnaddr.value = chlst_string.substring(0, chlst_string.length - 2);
    document.frm1.action.value = "update";
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
								   				<h1 style="margin-bottom: 0px;">OUTGOING MESSAGES-EDIT</h1>
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
						<form name="frm1" action="list_links.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="id" value=""	/>
							
                            <input type="hidden" name="hddnattr" value = ""/>
                            <input type="hidden" name="httnaddr" value = ""/>
							
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								
								<?	if($iRECORD_COUNT>0){	?>
	
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="500" class="colhead">Title</td>
												<td width="100" class="colhead">Date Updated</td>
												<td width="100" class="colhead">Action</td>
												<td width="20" class="colhead">Display</td>
											</tr>
											<?	while($rowLINK	=	mysql_fetch_array($rsLINKS)){
														
														
											?>			<tr>
															<td class="coldata leftbox"><? echo stripslashes($rowLINK['link_title']);?></td>
															<td class="coldata"><? echo $rowLINK['link_date'];?></td>
															
															<td class="coldata">
																<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LINK_MODIFY)){?>
																	<a href="edit_link.php?id=<? echo $rowLINK['link_id'];?>">view / edit</a>&nbsp;/&nbsp;
																	<a href="javascript:void(0);" onClick="fn_PRINT(<? echo $rowLINK['link_id'];?>);">print</a>
																<?	}?>&nbsp;
																<? //if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_LINK_DELETE) && ($rowLINK['link_id'] !="12" && $rowLINK['link_id'] !="13" && $rowLINK['link_id'] !="14" && $rowLINK['link_id'] !="15" && $rowLINK['link_id'] !="16" && $rowLINK['link_id'] !="10" && $rowLINK['link_id'] !="17" && $rowLINK['link_id'] !="18")){?>
																<!--	<a href="javascript:void(0);" onClick="if(confirm('are you sure to delete this link?')) {fn_DELETE_LINK(<? //echo $rowLINK['link_id'];?>);} return false;">delete</a>-->
																<?	//}?>
															</td>
															<td class="coldata">
															    <input type="checkbox" name="ckbx" value="<?=$rowLINK['link_id']?>" <? if ($rowLINK['flg'] == 1) {echo "checked";} ?>/>
															</td>
														</tr>
											<?	}	?>
											<tr>
										        <td class="coldata"/>
										        <td class="coldata"/>
										        <td class="coldata">Save display setting --> </td>
										        <td class="coldata"><input type="button" value="save" onclick="displayChecker()"/></td>
										    </tr>
										</table>
									</td>
								</tr>
								<?	}mysql_free_result($rsLINKS);	?>
								
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
 