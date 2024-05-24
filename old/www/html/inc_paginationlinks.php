<script language="JavaScript" type="text/javascript">
	function fn_SUBMIT_PAGEINATION(iPageNo){
		document.frm1.action.value	=	'search';
		document.frm1.pg.value	=	iPageNo;
		document.frm1.submit();
	}							
</script>							
							<table align = 'center' width='100%' border="0" cellpadding="5">
							
								<tr>
								<?php

/*$limit = 10;
$start = 1;
$slice = 9;

$q = "SELECT * FROM table";
$r = mysql_query($q, conn());
$totalrows = mysql_num_rows($r);

if(!isset($_GET['page']) ¦¦!is_numeric($_GET['page'])){
$page = 1;
} else {
$page = $_GET['page'];
}

$numofpages = ceil($totalrows / $limit);
$limitvalue = $page * $limit - ($limit);

$q = "SELECT * FROM table LIMIT $limitvalue, $limit";
if ($r = mysql_query($q, conn())) {

//loop results here
while ($row = mysql_fetch_assoc($r)) {
echo $row['column'].'<br />';
}

if($page!= 1){
$pageprev = $page - 1;
echo '<a href="'.$_SERVER['php_SELF'].'?page='.$pageprev.'">PREV</a> - ';
}else{
echo "PREV - ";
}

if (($page + $slice) < $numofpages) {
$this_far = $page + $slice;
} else {
$this_far = $numofpages;
}

if (($start + $page) >= 10 && ($page - 10) > 0) {
$start = $page - 10;
}

for ($i = $start; $i <= $this_far; $i++){
if($i == $page){
echo "<u><b>".$i."</b></u> ";
}else{
echo '<a href="'.$_SERVER['php_SELF'].'?page='.$i.'">'.$i.'</a> ';
}
}

if(($totalrows - ($limit * $page)) > 0){
$pagenext = $page + 1;
echo ' - <a href="'.$_SERVER['php_SELF'].'?page='.$pagenext.'">NEXT</a>';
}else{
echo " - NEXT";
}

}*/
//pagination
									$pages = ceil($iRECORD_COUNT/$max);
										
										
?>									<td width="20%">
										<table cellpadding="0" cellspacing="5">
											<tr><td class="label" style="font-size:10px;">Total Records:</td><td class="Highlight" style="font-weight:bold; font-size:10px;"><?=$iRECORD_COUNT?></td></tr>
											<tr><td class="label" style="font-size:10px;">Total Pages:</td><td class="Highlight" style="font-weight:bold; font-size:10px;"><?=$pages?></td></tr>
										</table>
									</td>
									<td valign="middle" align='center' width="80%" class="PaginationLink">
					
									<?
										//*********************************** 
							
										
										$start = 1;
										$slice = 5;
										
											
										if (($pg + $slice) < $pages) {
										$this_far = $pg + $slice;
										} else {
										$this_far = $pages;
										}
										
										if (($start + $pg) >= 6 && ($pg - 6) > 0) {
										$start = $pg - 6;
										}
												
												
										//===============
									
										if($pg!=1){
											//print("&nbsp;<a href='javascript:void(0);' onclick=\'fn_SUBMIT_PAGEINATION(".($pg-1).")\' alt='Page ".($pg-1)."'>Previous</a>&nbsp;");
									?>
											<a href='javascript:void(0);' onclick="fn_SUBMIT_PAGEINATION(1)" alt="Page 1">First</a>&nbsp;&nbsp;&nbsp;
											<a href='javascript:void(0);' onclick="fn_SUBMIT_PAGEINATION(<?=($pg-1)?>)" alt="Page <?=($pg-1)?>">Previous</a>&nbsp;
										
									<?	}
										
										
										//for($i=1;$i<=$pages;$i++)
										for ($i = $start; $i <= $this_far; $i++)
											if($pages>1)
												if ($i==$pg)
													print("<span class='current'>&nbsp;$i&nbsp;</span>");
												else{	
													//print("&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\'fn_SUBMIT_PAGEINATION(".($i).")\'$sQueryString alt='Page $i'>$i</a>&nbsp;");
									?>
													&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fn_SUBMIT_PAGEINATION(<?=$i?>)" alt="Page <?=$i?>"><?=$i?></a>&nbsp;
									<?			}
										if($pg+1<=$pages){
											//print("&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\'fn_SUBMIT_PAGEINATION(".($pg+1).")\' alt='Page ".($pg+1)."'>Next</a>");
									?>
											&nbsp;&nbsp;<a href='javascript:void(0);' onclick="fn_SUBMIT_PAGEINATION(<?=($pg+1)?>)" alt="Page <?=($pg+1)?>">Next</a>
											&nbsp;&nbsp;<a href='javascript:void(0);' onclick="fn_SUBMIT_PAGEINATION(<?=$pages?>)" alt="Page <?=$pages?>">Last</a>
									<?	}			?>
									</td>
								</tr>
							</table>

