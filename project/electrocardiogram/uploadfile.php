<?php
$target_path  = "./data/";//接收文件目录
if ($_REQUEST['user']) {
	$target_path = $target_path . $_REQUEST['user']."/".basename( $_FILES['uploadedfile']['name']);
}else $target_path = $target_path . "temp/".basename( $_FILES['uploadedfile']['name']);
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
   echo "The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded";
}  else{
   echo "There was an error uploading the file, please try again!" . $_FILES['uploadedfile']['error'];
}
?>
