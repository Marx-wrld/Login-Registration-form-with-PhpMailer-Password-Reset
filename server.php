<?php 
session_start();
//declaration
$email = "";
$username = "";
$contact = "";
$regno = "";
$password = "";
$cpassword = "";
$errors = array();


//connect to database
$con = mysqli_connect('localhost', 'root', 'marx', 'userform');

//if user clicks register button
if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $conatct = mysqli_real_escape_string($con, $_POST['contact']);
    $regno = mysqli_real_escape_string($con, $_POST['regno']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM members WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "The Email that you entered already exists!";
    }
    /*if($name && $email && $phone && $regno && $password && $cpassword = ""){
        $errors['register'] = "Please fill in your details properly!";
    }*/
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $insert_data = "INSERT INTO memberd (Username, Email, contactno, Registration_no, Password)
                        values('$username', '$email', '$contact', '$regno')";
        $data_check = mysqli_query($con, $insert_data);
        }else{
           $errors['db-error'] = "Failed while inserting data into database!";
        }
   }

    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM members WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if($email && $password == ""){
            $errors['email'] = "Email and Password can't be empty";
        }
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It looks like you're not yet a member! Click on the bottom link to register.";
        }
    ?>