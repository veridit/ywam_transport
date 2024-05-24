<?Php
include('inc_connection.php');
include('inc_function.php');

	$sSQL		=	"SELECT dept_id, dept_name FROM tbl_departments WHERE deactive_date = CURDATE()";
	$rsDEPT		=	mysql_query($sSQL) or die(mysql_error());
	$sMessage	=	"";
	
	if(mysql_num_rows($rsDEPT)>0){
		while($rowDEPT	=	mysql_fetch_array($rsDEPT)){
			if(fn_DEL_DEPT($rowDEPT["dept_id"]))
				$sMessage	.=	"department ".$rowDEPT["dept_name"]." and all its related records has been deleted<BR />";
			else
				$sMessage	=	"error! department is not been deleted";
		}
	}
	
	echo $sMessage;



?>