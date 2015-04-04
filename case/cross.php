<?php
	if(isset($_GET['title'])){
		header('Content-type: application/x-javascript');
		$result['title'] = $_GET['title'];
		$result['content'] = $_GET['content'];
		$json = json_encode($result);
		echo $_GET['c'].'('.$json.')';
	}elseif(isset($_POST['title'])) {
		echo $_POST['title'].'\n'.$_POST['content'];
	}
?>