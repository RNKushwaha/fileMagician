<?php if(isset($_POST) && isset($_POST['img'])){
		$img = $_POST['img'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace('data:image/jpg;base64,', '', $img);
		$img = str_replace('data:image/gif;base64,', '', $img);
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = substr($_POST['name'],0,strrpos($_POST['name'], '.',true));
		$success = file_put_contents($file.strstr($_POST['name'], '.',false), $data);
		print $success ? 'file saved' : 'Unable to save the file.';
		exit;
	}