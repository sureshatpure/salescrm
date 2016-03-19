<?php
$base_url="http://".$_SERVER['SERVER_NAME'].'/';
// Include the PHPMailer class
include('phpmailer/class.phpmailer.php');
$logo = 'http://www.pure-chemical.com/assets/images/logo/logo.png';

$subject = "testing email from smtp";
$message_content = "Welcome to email templating";

// do your database query
include_once("db/config.php");
// $conn = pg_connect("host=localhost port=5432 dbname=puredata user=postgres password=postgres123");
if (!$conn) {
    die("Not able to connect to PostGres --> " . pg_last_error($conn));
} else {
        $date = date ('Y-m-j G:i:s');
        $format='Y-m-d';
        $previous_day = date ( $format, strtotime ('-4 day'.$date));  
    $sql = "SELECT 
                    leaddetails.leadid, leaddetails.leadsource,leaddetails.user_branch,leaddetails.createddate,
          leadproducts.productid, leadproducts.quantity,
          leadstatus.leadstatus,
          leadsubstatus.lst_name,
          view_tempitemmaster_pg.itemgroup,
          customermasterhdr.customergroup,
          vw_web_user_login.aliasloginname
FROM
         leaddetails, leadproducts,leadstatus,leadsubstatus,view_tempitemmaster_pg, customermasterhdr, vw_web_user_login 

WHERE
     leaddetails.leadid=leadproducts.leadid
    AND leaddetails.leadstatus=leadstatus.leadstatusid 
    AND leaddetails.ldsubstatus = leadsubstatus.lst_sub_id
    AND view_tempitemmaster_pg.id=leadproducts.productid 
    AND customermasterhdr.id=leaddetails.company
   AND leaddetails.assignleadchk= vw_web_user_login.header_user_id
  AND  leaddetails.leadid in (SELECT leadproducts.leadid from    leadproducts ) AND leaddetails.leadid NOT IN (SELECT lead_prod_potential_types.leadid from  lead_prod_potential_types ) and createddate::DATE >='".$previous_day."' AND leaddetails.interest=0";


  //  echo $sql; die;
    $result = pg_query($conn, $sql);
    $rows = pg_num_rows($result);
    //$customerdetails = pg_fetch_array($result, 0, PGSQL_NUM);

//leadid,leadsource,user_branch,createddate,productid,quantity,leadstatus,lst_name,itemgroup,customergroup,aliasloginname
    $to_address = array();
    $i = 0;
    while ($row = pg_fetch_array($result)) {
         $leadid = $row["leadid"];  
         $view_leaddetails ="<a href='".$base_url."salescrm/leads/viewleaddetails/".$leadid."'>Check Lead Details</a>";  
        //Replace the codetags with the message contents
        $message = file_get_contents('email-templates/email-header.php');
        //Add the message body
        $message .= file_get_contents('email-templates/dailyactivity_error.php');
        //Add the message footer content
        $message .= file_get_contents('email-templates/email-footer.php');
        $replacements = array(
            '({logo})' => $logo,
            '({leadid})' => $row["leadid"],
            '({branch})' => $row["user_branch"],
            '({leadsource})' => $row["leadsource"],
            '({createddate})' => $row["createddate"],
            '({productgroup})' => $row["itemgroup"],
            '({quantity})' => $row["quantity"],
            '({leadstatus})' => $row["leadstatus"],
            '({substatus})' => $row["lst_name"],
            '({tempcustname})' => $row["customergroup"],
            '({createdby})' => $row["aliasloginname"],
            '({view_leaddetails})' => $view_leaddetails,
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
        // Send email to BM and executive
        /*$exe_address=explode(",", $row["sales_ref_mailid"]);
        $bm_address = explode(",", $row["bm_mailid"]);
        $to_address = array_merge($exe_address,$bm_address);
        $to_address = array_unique($to_address);*/
        $to_address="ticketsystem@pure-chemical.com";
    

        // Setup PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // This is the SMTP mail server
        $mail->Host = 'smtp.gmail.com';
        // Remove these next 3 lines if you dont need SMTP authentication
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'lms.alert@pure-chemical.com';
        $mail->Password = 'pure@123';
        // Set who the email is coming from
        //$mail->SetFrom('lms.alert@pure-chemical.com', 'LMS Admin');
        $mail->SetFrom('webdevelopment@pure-chemical.com', 'LMS Admin');

        // Set who the email is sending to
        if (is_array($to_address)) {

            foreach ($to_address as $i) {

                $mail->AddAddress($i);
            }
        } else {

            $mail->AddAddress($to_address, "");
        }
        //$mail->AddAddress('sureshatpure@gmail.com');
        //$mail->AddAddress( $to_address, "" );
        $mail->addBCC('sureshatpure@gmail.com');
	$mail->addCC('webdevelopment@pure-chemical.com');
      //  $mail->addCC('g.narender@pure-chemical.com');

        // Set the subject
        $mail->Subject = 'LMS Lead dailyactivity creation -Error';
        //Set the message
        $mail->MsgHTML($message);
        //$mail->AltBody(strip_tags($message));
        // Send the email

        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {


            // write an update statment to update the "mail_sent_flag to 1 "  in the table lead_status_mail_alerts using the 

            $sqlupdate = "update leaddetails set interest=1 WHERE leadid=".$row['leadid'];
            $resultupdate = pg_query($conn, $sqlupdate);
        }
        //	echo"<pre>";print_r($mail);echo"</pre>";
    } // end of while loop
}

// Retrieve the email template required
?>
