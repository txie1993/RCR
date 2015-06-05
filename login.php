<!doctype html>
<?php 
//include 'functions.php';

/*for security purposes, the servername, username, and password have not been included.*/

			$conn = mysql_connect($servername,$username,$password);
			if($conn->connect_error)
			{
				die('CONNECTION FAILED');
			}
			
	function createSessionID($ip)
	{
				$addr_array = explode(".",$ip);
				if(strlen($ip) < 18)
				{
					$addr_array = explode(".",$ip);
					$session_created = false;
					while($session_created == false)
					{	
						$chars = "abcdefghijklmnopqrstuvwxyz";
						$sessionId = $sessionId.rand(1000,9999).$chars[rand(0,25)].$addr_array[0].$chars[rand(0,25)].rand(100,999).$chars[rand(0,25)].$addr_array[1].$chars[rand(0,25)].rand(10,99).$chars[rand(0,25)].$addr_array[2].$chars[rand(0,25)].rand(10,99).$chars[rand(0,25)].$addr_array[3];
						return $sessionId;
					}
				}
	}
		
			$id = $_POST['id'];
			$password = $_POST['password'];
			
			$user = mysql_query("SELECT * FROM rcr_db.rcr_logins WHERE net_id='".$id."' AND password='".$password."';");
			if(mysql_num_rows($user) == 1)
			{
				
				$ip = $_SERVER['REMOTE_ADDR'];
				$sessionId = createSessionID($ip);
				$session_query = mysql_query("SELECT session_id FROM rcr_db.rcr_logins WHERE session_id=".$sessionId);
				$unique_id = false;
				while($unique_id == false)
				{
					if(mysql_num_rows($session_query) < 1)
					{
						$unique_id = true;
						mysql_query("UPDATE rcr_db.rcr_logins SET session_id='".$sessionId."' WHERE net_id='".$id."'");
					}
				}
				setcookie('s_id',$sessionId,time() + 3000);
				header("Location: http://128.122.209.95/index.php?s_id=".$sessionId."&u=".$id);
			}
			
			
?>
<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Login</title>

	<!-- CSS -->
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
	
</head>
<script type="text/javascript">

</script>
	<!-- Main HTML -->
	
<body>
	
	<!-- Begin Page Content -->
	
	<div id="container">
		
		<form method="post">
		
		<label for="name">Username:</label>
		
		<input type="name" name='id' id='id'>
		
		<label for="username">Password:</label>
		
		<p><a href="#">Forgot your password?</a>
		
		<input type="password" name='password'>
		
		<div id="lower">
		
		<input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label>
		
		<input type="submit" value="Login">
		
		</div>
		
		</form>
		
	</div>
	
	
	<!-- End Page Content -->
	
</body>

</html>
	
	
	
	
	
		
	