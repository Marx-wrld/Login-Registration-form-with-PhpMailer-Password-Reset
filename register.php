<?php
$message = '';

if(isset($_POST['username'])){
	
	$username=$_POST['username'];
	$email=$_POST['email'];
	$password=$_POST['password'];
	$cpassword=$_POST['cpassword'];
	$contact=$_POST['contact'];
    $regno=$_POST['regno'];
	
	$conn = mysqli_connect('localhost', 'root', 'marx', 'userform');

if (!$conn) {
	die("Connection failed".mysql_connect_error());}
	
	
//securing data
$username=preg_replace("#[^0-9a-z]#i","","$username");
$password=sha1($password);	
$email=mysqli_real_escape_string($conn,$_POST['email']);
$contact=mysqli_real_escape_string($conn,$_POST['contact']);
$regno=mysqli_real_escape_string($conn,$_POST['regno']);
	
//check for duplicates
	
$user_query=mysqli_query($conn,"SELECT username FROM members WHERE username='$username'LIMIT 1")or die("Could not check username");
$count_username=mysqli_num_rows($user_query);
	
$email_query=mysqli_query($conn,"SELECT email FROM members WHERE email='$email'LIMIT 1")or die("Could not check Email");
$count_email=mysqli_num_rows($email_query);

$contact_query=mysqli_query($conn,"SELECT contactno FROM members WHERE contactno='$contact'LIMIT 1")or die("Could not check your contact");
$count_contact=mysqli_num_rows($contact_query);

$regno_query=mysqli_query($conn,"SELECT Registration_no FROM members WHERE Registration_no='$regno'LIMIT 1")or die("Could not check your registration number");
$count_regno=mysqli_num_rows($regno_query);
	
if($count_username>0){
	$message='Your username is already taken';
}elseif($count_email>0){
	$message='Your email is already in use';
}elseif($count_contact>0){
	$message='Your contact is already in use';
}elseif($count_regno>0){
	$message='Your registration number is already in use';
}else{
	
//insert the members
$query=mysqli_query($conn,"INSERT INTO members (Username,Email,contactno, Registration_no,Password)VALUES('$username','$email','$contact','$regno','$password')") or die("Could not insert your information");

header("Location:login.php");
	
}
	
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form name="frm" id="form" action="register.php" method="POST">
                    <h2 class="text-center">Register</h2>
                    <p class="text-center">It's quick and easy.</p>

                    <div id="error" class="text-center"><?php echo $message;?></div>

                    <div class="form-group">
                        <input class="form-control" type="text" name="username" id="username" placeholder="Full Name">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" id="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="contact" id="contact" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="regno" id="regno" placeholder="Registration Number">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm password">
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="register" id="submit" value="Register">
                    </div>
                    <div class="link login-link text-center">Already a member? <a href="login.php">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
    
    
	<script type="text/javascript">
		const form = document.getElementById('form');
		const username = document.getElementById('username');
		const email = document.getElementById('email');
		const contact = document.getElementById('contact');
		const password = document.getElementById('password');
		const errorElement = document.getElementById('error');
		const pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

		form.addEventListener('submit',(e) =>{
			let messages = [];
			if (username.value === '' || username.value == null) {
				messages.push('Name is required');
			}

			if (contact.value === '' || contact.value == null) {
				messages.push('Phone Number is required');
			}

			if (email.value === '' || email.value == null) {
				messages.push('Email is required');
			} else{

				if (email.value.match(pattern)) {
					
				} else{
					messages.push('Your Email is Invalid');
				}				
			}


			if (password.value === '' || password.value == null) {
				messages.push('Password is required');
			}

			else{
					if (cpassword.value === '' || cpassword.value == null) {
					messages.push('Confirmation password is required');
				} else{
					if (password.value === cpassword.value) {}
						else{
							messages.push('Your Password Fields Do Not Match')
						}
				}
			}

			

			if (messages.length > 0) {
				e.preventDefault();
				errorElement.innerText = messages.join(', ');
			}
		});
	</script>	
</body>
</html>