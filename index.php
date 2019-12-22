<?php 
	$url = '';
	if (isset($_POST['url']) && $_POST['url']){
		$url = $_POST['url'];
	}
	if (isset($_POST['data'])&& $_POST['data']) {
		$data = $_POST['data'];
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
* { 
	margin:0;
	padding:0;
}
form {
	margin:25px;
	padding:25px;
}

input, textarea {
	padding:5px;
	width:600px;
	margin:5px;
}
button {
	width:100px;
	height:35px;
	background-color:#3490dc;
	border-radius:10px;
	font-weight: bold;
	color:#fff;
}
select {
	width:100px;
	padding:5px;
}
</style>
</head>

<body>
	<form action="index.php" method="post">
		<select name="action">
			<option value="GET">get</option>
			<option value="POST">post</option>
			<option value="PUT">put</option>
			<option value="DELETE">delete</option>
		</select>
		<input type="text" name="url" value="<?php echo $url;?>">
<br>
		<label for="data">Enter Data formatted as array without quotes (eg. title=>title text, body=>body text)</label>
		<textarea name="data"></textarea>
		<button type="submit">Submit</button>
	</form>
<?php
	if (isset($data)) {
		$params = explode(',', $data);
		$a = array();
		foreach($params as $param)
		{
			$keyval = explode("=>",$param);
			$keyval[0] = trim($keyval[0]);
			$keyval[1] = trim($keyval[1]);
			$a[$keyval[0]] = $keyval[1];
		}		
		$data_json = json_encode($a);
	}
	if (isset($_POST['url'])){
		$ch = curl_init($_POST['url']);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_POST['action']);

		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		if (isset($data)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));			
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		} else {
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type'=>'application/json']);			
		}

		$response = curl_exec($ch);
		curl_close($ch);
		
		echo $response;
	}
?>	
</body>
</html>