<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	if(!isset($_SESSION["User_ID"]) || $_SESSION["User_ID"]=="") header('location:login.php?err=1');
	
	$sMessage		=	"";

	
	$id	=	"";
	if(isset($_GET['id']))	$id=$_GET['id'];	// Get the provided arg
	if ($id==""){ // Check if the file has needed args
		$sMessage		=	fn_Print_MSG_BOX("You have not provided a backup to restore.", "C_ERROR");
	}else{

			// Generate filename and set error variables
			//$filename = 'backup/' . $id.'.sql';
			$filename = 'backup/' . $id;
			$sqlErrorText = '';
			$sqlErrorCode = 0;
			$sqlStmt      = '';
			
			// Load and explode the sql file
			$f = fopen($filename,"r+");
			$sqlFile = fread($f,filesize($filename));
			$sqlArray = explode(';<|||||||>',$sqlFile);
				  
			// Process the sql file by statements
			foreach ($sqlArray as $stmt) {
				if (strlen($stmt)>3){ $result = mysql_query($stmt);	}
			}
		
		
		// Print message (error or success)
			if ($sqlErrorCode == 0){
				$sMessage		=	fn_Print_MSG_BOX("Database restored successfully!<br />Backup file used: " . $filename, "C_SUCCESS");
			}else{
				$sMessage		=	fn_Print_MSG_BOX("An error occurred while restoring backup!<br><br>Error code: $sqlErrorCode<br />Error text: $sqlErrorText<br>Statement:<br/> $sqlStmt<br>", "C_ERROR");
			}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Restore the Database</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

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
								   				<h1 style="margin-bottom: 0px;">RESTORE</h1>
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
						<form name="frm1" action="restore.php" method="post">
							<input type="hidden" name="action" value="retore"	/>
							<table cellpadding="0" cellspacing="5" border="0" width="500" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?><div id="loadingimage"></div></td></tr>
								
								
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