<?php
// Get the filename to be deleted
$file=$_GET['file'];

// Check if the file has needed args
if ($file==NULL){
  print("<script type='text/javascript'>window.alert('You have not provided a file to delete.')</script>");
  print("<script type='text/javascript'>window.location='list_backups.php'</script>");
  print("You have not provided a file to delete.<br>Click <a href='list_backups.php'>here</a> if your browser doesn't automatically redirect you.");
  die();
}

// Delete the SQL file
if (!is_dir("backup/" . $file . '.sql')) {
unlink("backup/" . $file . '.sql');
}

// Redirect
header("Location: list_backups.php");
?>
