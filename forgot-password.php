<?php
session_start();

require "mail.php";

$conn = mysqli_connect('localhost', 'root', 'marx', 'userform');
if (!$conn) {
	die("Connection failed".mysqli_connect_error());
}

$message='';
$message2='';

$mode = "enter_email";
if (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
}

//something is posted

if (count($_POST) > 0) {
	switch ($mode) {
		case 'enter_email':
			$email = $_POST['email'];
			//validate the email
			if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
				$message = "Please enter a valid email";
			} elseif (!valid_email($email)) {
				$message = "Email entered was not found";
			} else {
				$_SESSION['forgot']['email'] = $email;
				send_email($email);

				header("Location: forgot-password.php?mode=enter_code");
				die;
			}
			
			break;

		case 'enter_code':
			$code = $_POST['code'];
			$result = code_authentication($code);

			if ($result == "The Code is Correct") {
				$_SESSION['forgot']['code'] = $code;
				header("Location: forgot-password.php?mode=enter_password");
				die;
			}else{
				$message = $result;
			}
			break;

		case 'enter_password':
			$password = $_POST['password'];
			$cpassword = $_POST['cpassword'];

			if ($password != $cpassword) {
				$message = "Passwords do not match";
			} elseif(!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])){
				header("Location: forgot-password.php");
			die;
			} else {
				save_password($password);
				if (isset($_SESSION['forgot'])) {
					unset($_SESSION['forgot']);
				}
				header("Location: login.php");
			die;
			}
			
			break;
		
		default:
			// code...
			break;
	}
}

function send_email($email){
	global $conn;
	$expire = time() + (60 * 10);
	$code = rand(10000,99999);
	$email=addslashes($email);

	$query = "INSERT INTO resets (Email,Code,Expire)VALUES('$email','$code','$expire')";
	$query_run = mysqli_query($conn,$query) or die("Could not update");

	//send email
	send_mail($email,'Password Reset',"Your code is " . $code);
}

function code_authentication($code){
	global $conn;
	$code = addslashes($code);
	$expire = time();
	$email = addslashes($_SESSION['forgot']['email']);

	$query = "SELECT * FROM resets WHERE code = '$code' && email = '$email' ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result) {
		if (mysqli_num_rows($result) > 0) 
		{
			$row = mysqli_fetch_assoc($result);
			if ($row['Expire'] > $expire) {
				return "The Code is Correct";
			}else{
				return "The Code Has Expired";
			}
		}else{
			return "The Code You've Entered is Incorrect";
		}
	}
	return "The Code You've Entered is Incorrect";
}

function save_password($password){
	global $conn;
	$password = sha1($password);
	$email = addslashes($_SESSION['forgot']['email']);

	$query = "UPDATE members SET password ='$password' WHERE email = '$email' LIMIT 1";
	mysqli_query($conn,$query);
}

function valid_email($email){
	global $conn;
	$email = addslashes($email);

	$query = "SELECT * FROM members WHERE email = '$email' LIMIT 1";
	$result = mysqli_query($conn, $query);
	if ($result) {
		if (mysqli_num_rows($result) > 0) 
		{
			return true;
		}
	}

	return false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <style type="text/css">

		.register-box
		{
			width: 50%;
			margin-left: 28%;
			margin-top: 50px;
			background-color: #f5f5f5;
			border-radius: 15px;
		}

		.wave
		{
			width: 250px;
			border-radius: 30px;
		}

		p
		{
			color: #191970;
		}
		h1
		{
			margin: 5px;
		}
		.save
		{
			margin: 20px;
		}
		#error{
			color: #8B0000;
		}
	</style>
</head>
<body>
    
	<?php
		switch ($mode) {
			case 'enter_email':
	?>
    <div class="container">
		<small id="emailHelp" class="form-text"><?php print("$message2");?></small><br>

        <div class="row">
            <div class="col-md-4 offset-md-4 form">

                <form action="forgot-password.php" method="POST" autocomplete="">
                    <h2 class="text-center">Forgot Password</h2>
					<br/>
        
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check-email" value="Reset">
                    </div>

                    <div class="form-group">
                      <a href="login.php">Back</a>
                    </div>
                </form>

                <?php
					break;
				    case 'enter_code':
				?>
                <div class="register-box">
					<h1 style="text-decoration: underline;" class="text-center">Code Verification</h1>
					<p class="text-center">Enter the Code that was sent to your Email. <br/> <strong>Code Expires in 10 minutes!</strong><p>
					<small id="emailHelp" class="form-text"><?php print("$message2");?></small>
                <div class="text-center" id="error"><?php echo $message;?></div>
				<br/>

                <form action="forgot-password.php?mode=enter_code" method="POST">

                    <input style="width: 50%; margin-left:25%" type="text" class="form-control" name="code" placeholder="Enter Code"> <br>		

                    <button style="width: 30%; margin-left:35%; color: white; background-color: blue;" type="submit" class="form-control button" name="SavePassChanges">Next</button>

                    <a href="forgot-password.php" class="login save">
                        <p style="width: 50%; margin-left:40%">Restart Reset</p>
                    </a>
                </form>

                <?php
					break;
				case 'enter_password':
				?>
				<div class="register-box">
                <h1 style="text-decoration: underline; margin-top: 30px;" class="text-center">Reset Password</h1>
				<br>
				<small id="emailHelp" class="text-center"><?php print("$message2");?></small><br>

                <form action="forgot-password.php?mode=enter_password" method="post">
                
				<div class="form-group">
                <input style="width: 60%; margin-left:22%; border-radius: 5px; height:40px;" type="password" class="text-center" name="password" placeholder="Enter New Password"> <br>
				</div>
				
				<div class="form-group">
                <input style="width: 60%; margin-left:22%; border-radius: 5px; height:40px;" type="password" class="text-center" name="cpassword" placeholder="Confirm Password"> <br>
				</div>	

				<br/>
				<div class="form-group">
                <button style="width: 20%; margin-left:40%; background-color: blue; color:white; border:1px; border-radius: 10px; height:30px;" type="submit" class="text-center" name="SavePassChanges">Continue</button>
				</div>

				<div class="form-group"></div>
                <a href="forgot-password.php" class="login save">
                    <p style="width: 50%; margin-left:42%;" class="startover">Restart Reset</p>
                </a>
				</div>

                </form>
				</div>
                <?php
					break;
				
				default:
					// code...
					break;
                    }
                ?>
			</a>
            </div>
			
        </div>
    </div>
</body>
</html>