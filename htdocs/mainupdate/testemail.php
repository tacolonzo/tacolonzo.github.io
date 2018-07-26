<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
$input = file_get_contents('php://input');
if ($input){
$data = json_decode( $input, true );
$name = $data['Name'];
$to      = $data['Email'];

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

$mail->Subject = 'Subscription Verification';
$mail->Body    = "Hello $name, <br/> <b>Thank You For Subscribing!</b>";
$mail->AltBody = "Hello $name, \n Thank You For Subscribing!";

if(!$mail->send()) {
    echo "Please Try Again \n";
    echo 'Error: ' . $mail->ErrorInfo;
} else {
$fh = fopen(__DIR__ . '/content/user.json', 'r+'); //opens the row.json file for reading and writing (dir is the current directory, so you dont have to say C:/ bla bal)
$stat = fstat($fh); //read the information about the file, you can use the ['size'] index to read how many characters the file has
ftruncate($fh, $stat['size']-1); // this shortens (or truncates) the file by 1 character (deleting closing bracket ']')
fclose($fh); //closes the file we just opened
if($stat['size']< 5) {	// if the file is less than 5 characters (4 characters make the two brackets and newline) then the file has now rows
file_put_contents(__DIR__ . '/content/user.json', "$input \n]" , FILE_APPEND);} //if no rows then save the new row (without comma) and close the bracket back with newline '\n]'
else { // if there are other rows then
file_put_contents(__DIR__ . '/content/user.json', ", $input \n]" , FILE_APPEND);} // save the new row at the end of the file (with comma for formatting) and close the bracket back
echo 'Account has been saved, check email for verification';
}

;}
?> 