<?
    $sHOST	=	"localhost";		$sUSER	=	"transport";	$sPASSWORD	=	"YouWillNeverHackMe123";	$sDB	=	"transportation";
    $link=mysql_connect($sHOST,$sUSER,$sPASSWORD) or die(mysql_error());
    mysql_select_db($sDB);
?>

