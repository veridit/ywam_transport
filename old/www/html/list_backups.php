<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	

	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	fn_Print_MSG_BOX("<li class='bold-font'>When this system crashes or has serious problem, click on 'Restore' in the Action column, top line, to rebuild the system and its records as it was 7 AM today","C_SUCCESS");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Restore System Backup</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/fusion.css">
<link rel="stylesheet" type="text/css" href="../html/style.css">
<link rel="stylesheet" type="text/css" href="../html/nblack.css">


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
								   				<h1 style="margin-bottom: 0px;">RESTORE SYSTEM BACKUP</h1>
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
						<form name="frm1" action="list_backups.php" method="post">
							<input type="hidden" name="action" value=""	/>
							<input type="hidden" name="deptid" value=""	/>
							<input type="hidden" name="pg" value="<?=$pg?>"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								
								
								
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
											<tr>
												<td width="200" class="colhead">File Name</td>
												<td width="400" class="colhead">Date Time</td>
												<td width="300" class="colhead" align="center">Action</td>
											</tr>
											<?		
													
													$backup_dir = "./backup/"; 
													$sBACKUP_FILES = glob($backup_dir."*.sql"); 
													array_multisort(array_map('filemtime', $sBACKUP_FILES), SORT_DESC, $sBACKUP_FILES);
													//print_r($sBACKUP_FILES);
														
												// List the files
												/*$dir = opendir ("./backup");
												$iFILE_COUNTER 	=	0;
												$arrFILE		=	array();
												while (false !== ($file = readdir($dir))) { 
												
													// Print the filenames that have .sql extension
													if (strpos($file,'.sql',1)) { 
												
														// Get time and date from filename
														$date = str_replace('_','/',substr($file, 9, 10));
														$time = str_replace('_',':',substr($file, 20, 8));
													
														// Remove the sql extension part in the filename
														$filenameboth = str_replace('.sql', '', $file);
														
														
														//$arrFILE[]	=		$filenameboth;
														$arrFILE[]	=	str_replace('/', ' ',$date)."".str_replace('/', ' ',$time);
														
													
													}
												}closedir($dir);
												
												//print_r($arrFILE);
												rsort($arrFILE, SORT_NUMERIC);
												print_r($arrFILE);
												//for($iCOUNTER=0;$iCOUNTER<count($arrFILE);$iCOUNTER++){*/
												foreach($sBACKUP_FILES as $FILE_NAME){
												
													$FILE_NAME	=	str_replace($backup_dir, '', $FILE_NAME);
														
													$date = str_replace('_','/',substr($FILE_NAME, 9, 10));
													$time = str_replace('_',':',substr($FILE_NAME, 20, 8));
												
													// Remove the sql extension part in the filename
													$filenameboth = $FILE_NAME;
													// Print the cells
														/*print("<tr>\n");
														print("  <td>" . $filenameboth . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $date . " - " . $time . "</td>\n");
														print("  <td class='action'><a href='restore.php?id=" . $filenameboth . "' class='edit'>Restore</a>\n");
														print("<a href='backup/" . $filenameboth . ".sql' class='view'>Download SQL</a>\n");
														print("<a href='backup/" . $filenameboth . ".zip' class='view'>Download ZIP</a>\n");
														print("<a href='delete.php?file=" . $filenameboth . "' class='delete'>Delete</a></td>\n");
														print("</tr>\n");*/
											?>			<tr>
															<td class="coldata leftbox"><? echo $filenameboth;?></td>
															<td class="coldata"><? echo $date . " - " . $time;?></td>
															<td class="coldata" align="center"><? echo "<a href='download.php?f=$filenameboth&Dir=./backup/'>Download Backup File</a>&nbsp;&nbsp;/"; ?>
															<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_RESTORE)){?>&nbsp;&nbsp;<a href="restore.php?id=<? echo $filenameboth;?>">Restore</a><?	}?>
															<? if(fn_USER_PERMISSIONS_TABLE($_SESSION["User_ID"], $iOPT_BACKUP_DELETE)){?>&nbsp;&nbsp;<a href="delete.php?file=<? echo $filenameboth;?>">Delete</a><?	}?>
															</td>
														</tr>
											<?	
													}
													
											?>
								
										</table>
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
 