<?php

// Include the PHPMailer class
include('phpmailer/class.phpmailer.php');
$logo = 'http://www.pure-chemical.com/assets/images/logo/logo.png';

$subject = "testing email from smtp";
$message_content = "Welcome to email templating";
$message = file_get_contents('email-templates/email-header.php');
//Add the message body
$message .= file_get_contents('email-templates/email-body.php');
//Add the message footer content
$message .= file_get_contents('email-templates/email-footer.php');


// do your database query
include_once("db/config.php");
// $conn = pg_connect("host=localhost port=5432 dbname=puredata user=postgres password=postgres123");
if (!$conn) {
    die("Not able to connect to PostGres --> " . pg_last_error($conn));
} else {
    $sql = "SELECT 
						a.leadid,a.lead_substatus_id,a.substatus_updated_date,a.mail_alert_date,a.status_action_type,
           					b.content_text as alert_text,
           					m.description as ProductName,m.itemgroup,
           					c.tempcustname,c.stdname,c.customer_name
				FROM  
						lead_status_mail_alerts a ,
						lead_status_mailalert_content b,
						leadproducts	p,
						itemmaster m,
            					customermasterhdr c,
            					leaddetails ld

				WHERE 
							a.lead_substatus_id=3 
							AND  a.lead_substatus_id = b.alert_cont_id 
              					AND c.id = ld.company 
              					AND ld.leadid = a.leadid 
							AND ld.leadid = p.leadid
              					AND p.productid::text = m.itemid::text
							AND mail_alert_date = CURRENT_DATE + 2
							AND  mail_alert_id in 
							(
								SELECT  max(mail_alert_id)  FROM lead_status_mail_alerts GROUP BY  lead_substatus_id ,leadid 
							)";


    echo $sql;
    die;
    $result = pg_query($conn, $sql);
    $rows = pg_num_rows($result);
    //$customerdetails = pg_fetch_array($result, 0, PGSQL_NUM);

    $jTableResult = array();
    $data = array();
    $i = 0;
    while ($row = pg_fetch_array($result)) {
        //Replace the codetags with the message contents
        $message = file_get_contents('email-templates/email-header.php');
        //Add the message body
        $message .= file_get_contents('email-templates/email-body.php');
        //Add the message footer content
        $message .= file_get_contents('email-templates/email-footer.php');
        $replacements = array(
            '({logo})' => $logo,
            '({leadid})' => $row["leadid"],
            '({mail_alert_date})' => $row["mail_alert_date"],
            '({alert_text})' => $row["alert_text"],
            '({productname})' => $row["productname"],
            '({itemgroup})' => $row["itemgroup"],
            '({tempcustname})' => $row["tempcustname"],
            '({message_body})' => nl2br(stripslashes($message_content))
        );
        $message = preg_replace(array_keys($replacements), array_values($replacements), $message);

        //Make the generic plaintext separately due to lots of css and tables
        $plaintext = $message_content;
        $plaintext = strip_tags(stripslashes($plaintext), '<p><br><h2><h3><h1><h4>');
        $plaintext = str_replace(array('<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>'), PHP_EOL, $plaintext);
        $plaintext = str_replace(array('</p>', '</h1>', '</h2>', '</h3>', '</h4>'), '', $plaintext);
        $plaintext = html_entity_decode(stripslashes($plaintext));


        // Replace the % with the actual information
        // Setup PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // This is the SMTP mail server
        $mail->Host = 'smtp.gmail.com';
        // Remove these next 3 lines if you dont need SMTP authentication
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'sureshatpure@gmail.com';
        $mail->Password = 'kumar@123';
        // Set who the email is coming from
        $mail->SetFrom('admin@pure-chemical.com', 'Website Admin');
        // Set who the email is sending to
        $mail->AddAddress('sureshatpure@gmail.com');
        // Set the subject
        $mail->Subject = 'Your account information';
        //Set the message
        $mail->MsgHTML($message);
        //$mail->AltBody(strip_tags($message));
        // Send the email
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}

// Retrieve the email template required
?>