<?
	session_start();
	include('inc_connection.php');
	include('inc_function.php');
	
	$sMessage		=	"";
	$iLINK_ID	=	0;
	if(isset($_REQUEST["id"]))	$iLINK_ID	=	$_REQUEST["id"];
		
	$sSQL	=	"SELECT * FROM tbl_info_links WHERE link_id = ".$iLINK_ID;
	$rsLINK	=	mysql_query($sSQL) or die(mysql_error());
	if(mysql_num_rows($rsLINK)>0){
		$rowLINK	=	mysql_fetch_array($rsLINK);
	}else{
		$sMessage		=	fn_Print_MSG_BOX("please select a valid information link", "C_ERROR");
	}
	
?>
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><? echo stripslashes($rowLINK['link_title']);?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<script type="text/javascript">
	function fn_PRINT(iPageID){
		var url="printpage.php?a=print&id="+iPageID;
		var myWindow	=	window.open(url,"_blank","height=600, width=800, resizable=no, scrollbars=yes");
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
								   				<h1 style="margin-bottom: 0px;"><? echo stripslashes($rowLINK['link_title']);?></h1>
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
						
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center" class="box">
								
								<tr><td id="Message" width="100%"><?=$sMessage?></td></tr>
								<tr><td><p style="text-align:justify;"><? echo stripslashes($rowLINK['link_text']);?></p></td></tr>
								
							</table>
							<table cellpadding="0" cellspacing="5" border="0" width="949" align="center">
								
								<tr><td width="100%" align="right"><input type="button" name="btnPrint" class="Button" value="PRINT THIS PAGE" onClick="fn_PRINT(<? echo $rowLINK['link_id'];?>);" /></td></tr>
								
								
							</table>
						
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
 