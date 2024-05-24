<?
	include('inc_connection.php');
	include('inc_function.php');
	
	$sMessage		=	"";
	$iLINK_ID	=	0;
	if(isset($_REQUEST["a"]))	$sPageAction		=	$_REQUEST["a"];
	
	if ($sPageAction	==	"print"){
		if(isset($_REQUEST["id"]))	$iLINK_ID	=	$_REQUEST["id"];
			
		$sSQL	=	"SELECT * FROM tbl_info_links WHERE link_id = ".$iLINK_ID;
		$rsLINK	=	mysql_query($sSQL) or die(mysql_error());
		if(mysql_num_rows($rsLINK)>0){
			$rowLINK	=	mysql_fetch_array($rsLINK);
		}else{
			$sMessage		=	fn_Print_MSG_BOX("please select a valid information link", "C_ERROR");
		}
	}
	
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Generator" content="NetObjects Fusion 11 for Windows">

<link rel="stylesheet" type="text/css" href="../html/style.css">

</head>
<body style="margin: 0px;background-color:#fff;">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
  		
        <td>
        	<table border="0" cellspacing="0" cellpadding="0" width="700">
				
               
                
						
							
								
								
								<tr><td><p style="text-align:justify;"><? echo stripslashes($rowLINK['link_text']);?></p></td></tr>
								
							
							
						
                	
               
			</table>
		</td>

     </table>
    </td>
   </tr>
  </table>
 </div>
 <script language="javascript">window.print();</script>
</body>
</html>