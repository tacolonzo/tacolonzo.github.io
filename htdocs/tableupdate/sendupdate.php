<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "../mainupdate/src/Exception.php";
require "../mainupdate/src/PHPMailer.php";
require "../mainupdate/src/SMTP.php";
$input = file_get_contents('php://input');
if ($input){
$indata = json_decode( $input, true );
$inname	= $indata['Name'];
$intitle= $indata['showTitle'];	
$indate	= $indata['recommendedOn'];		
$inrate	= $indata['rating'];
$intype	= $indata['types'];	
$inoption = $indata['options'];		
$incomments	= $indata['comments'];	
$file = "../mainupdate/content/user.json";
$something = count(file($file)) -3;
$that = file_get_contents($file); 
$data = json_decode($that,true);
$i= 0; 
While ($i <= $something){

$name = $data[$i]['Name'];
$to   = $data[$i]['Email'];
$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'rob4700@gmail.com';                 // SMTP username
$mail->Password = 'rob12345';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
$mail->Port = 465;

$mail->From = 'rob4700@gmail.com';
$mail->FromName = 'Robert Floyd';
$mail->addAddress("$to");               // Name is optional
$mail->addReplyTo('rob4700@gmail.com', 'Robert Floyd');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'New Post!';
$mail->Body    = "Hello $name, <br/> <b>There Was A New Post!</b> <br/> $inname recommended $intitle on $indate. This $inoption $intype was rated $inrate. Also, $incomments.";
$mail->AltBody = "Hello $name, \n There Was A New Post! \n $inname recommended $intitle on $indate. This $inoption $intype was rated $inrate. Also, $incomments.";

$mail->send();
  
 $i++; }
  echo 'Message has been sent ';
}

?> 