<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'sureshatpure@gmail.com';                 // SMTP username
$mail->Password = 'kumar@123';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'webdevelopment@pure-chemical.com';
$mail->FromName = 'Mailer';
$mail->addAddress('sureshatpure@gmail.com', 'Joe User');     // Add a recipient
$mail->addAddress('ellen@gmail.com');               // Name is optional
$mail->addReplyTo('info@gmail.com', 'Information');
$mail->addCC('crmsupport@pure-chemical.com');
$mail->addBCC('webdevelopment@pure-chemical.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}