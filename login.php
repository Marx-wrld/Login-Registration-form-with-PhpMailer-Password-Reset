<?php
$message = '';

if(isset($_POST['email'])){
	$email=$_POST['email'];
	$pass=$_POST['pass'];
	$remember=$_POST['remember'];
	
	$conn = mysqli_connect('localhost', 'root', 'marx', 'userform');
if (!$conn) {
	die("Connection failed".mysql_connect_error());}
	
//secure the data
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$pass=sha1($pass);
	$query=mysqli_query($conn,"SELECT * FROM members WHERE email='$email'AND password='$pass'LIMIT 1")or die("Could not check Member");
	$count_query=mysqli_num_rows($query);
	if($count_query==0){
		$message="The information you entered was incorrect!";
	}else{
	
	//start the sessions
	
		$_SESSION['pass']=$pass;
		while($row=mysqli_fetch_array($query)){
			$username=$row['username'];
			$id=$row['id'];
		}
	$_SESSION['username']=$username;
	$_SESSION['id']=$id;
		
		if($remember=="yes"){
			
	//create the cookies
			
		setcookie("id_cookie",$id,time()+60*60*24*100,"/");
		setcookie("pass_cookie",$pass,time()+60*60*24*100,"/");	
		
		}
	header("Location:home.php");	
		
	}	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
<style>
    input.form-checkbox{
        width: 17px;
        height: 17px;
    }
</style>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="login.php" method="POST">
                    <h2 class="text-center">Login</h2>
                    <p class="text-center">Login with your email and password.</p>

                    <div id="error" class="text-center"><?php echo $message;?></div>
					<br/>
                    <div class="form-group">
                        <input class="form-control" type="email"  autocomplete="" id="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" autocomplete="" id="pass" name="pass" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input class="form-checkbox" type="checkbox" id="checkbox" name="remember" value="yes" checked="checked">&nbsp; 
                        Remember me
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" id="submit" name="login" value="Login">
                    </div>
                    <br>
                    <div class="link forget-pass text-center">Forgot password? <a href="forgot-password.php"> Reset here</a></div>
                    <div class="link login-link text-center">Not yet a member? <a href="register.php">Register now</a></div>
                </form>
            </div>
        </div>
    </div>
    
    
	<script type="text/javascript">
		const form = document.getElementById('form');
		const username = document.getElementById('username');
		const email = document.getElementById('email');
		const pass = document.getElementById('pass');
		const errorElement = document.getElementById('error');
		const pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

		form.addEventListener('submit',(e) =>{
			let messages = [];
			if (username.value === '' || username.value == null) {
				messages.push('Name is required');
			}

			if (email.value === '' || email.value == null) {
				messages.push('Email is required');
			} else{

				if (email.value.match(pattern)) {

				} else{
					messages.push('Your Email is Invalid');
				}				
			}


			if (pass.value === '' || pass.value == null) {
				messages.push('Password is required');
			}

			if (messages.length > 0) {
				e.preventDefault();
				errorElement.innerText = messages.join(', ');
			}
		});
	</script>
</body>
</html>