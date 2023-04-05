<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'PHPMailer-master/src/Exception.php';
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';

function send_mail($recipient,$subject,$message)
{

  $mail = new PHPMailer(true);
  $mail->IsSMTP();
  $mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );
  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE; 
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com";
  //$mail->Host       = "smtp.mail.yahoo.com";
  $mail->Username   = "xyz@gmail.com"; //Enter your mail here
  $mail->Password   = "12345678"; //Enter the generated password

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "Esteemed user");
  $mail->SetFrom("xyz@gmail.com", "xyz");
  //$mail->AddReplyTo("reply-to-email", "reply-to-name");
  //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    echo "<pre>";
    var_dump($mail);
    //return false;
  } else {
    echo "Email sent successfully";
    //return true;
  }

}

?>
