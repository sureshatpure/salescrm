<?php
// Include the PHPMailer class
$base_url="http://".$_SERVER['SERVER_NAME'].'/';

include('phpmailer/class.phpmailer.php');
$logo = 'http://www.pure-chemical.com/assets/images/logo/logo.png';

$subject="testing email from smtp";
$message_content="Welcome to email templating";

// do your database query
 include_once("db/config.php");
           // $conn = pg_connect("host=localhost port=5432 dbname=puredata user=postgres password=postgres123");
            if (!$conn)
            {
                die ("Not able to connect to PostGres --> " . pg_last_error($conn));
            }
            else
            {
                 $sql= 'SELECT 
						a.mail_alert_id,ld.assignleadchk as executiveid,
						v.user_contact_mail_id as sales_ref_mailid,
						v.user_contact_mail_id as bm_mailid,
						ld.user_branch,a.leadid,
						a.appointment_due_date as appointment_date,
						a.lead_substatus_id,
						a.substatus_updated_date,
						a.mail_alert_date,
						a.status_action_type,
	   					b.content_text as alert_text,
	   					m.description as ProductName,m.itemgroup,
	   					c.tempcustname,c.stdname,c.customer_name
				FROM  
						lead_status_mail_alerts a ,
						lead_status_mailalert_content b,
						leadproducts	p,
						view_tempitemmaster_grp m,
            			customermasterhdr c,
            			leaddetails ld,	
						vw_web_user_login v

				WHERE 
							a.lead_substatus_id=7
							AND  a.lead_substatus_id = b.substatus_id 
              				AND c.id = ld.company 
              				AND ld.leadid = a.leadid 
							AND ld.leadid = p.leadid
              				AND p.productid::text = m.id::text
              				AND v.header_user_id=ld.assignleadchk 
							AND mail_alert_date = CURRENT_DATE
							AND mail_sent_flag=0
							AND  mail_alert_id in 
							(
								SELECT  max(mail_alert_id)  FROM lead_status_mail_alerts GROUP BY  lead_substatus_id ,leadid 
							)';
               

		// echo $sql; die;
                $result = pg_query($conn, $sql);
                $rows = pg_num_rows($result);
                //$customerdetails = pg_fetch_array($result, 0, PGSQL_NUM);
                
                

                     $to_address = array();
                      $i=0;
                while ($row = pg_fetch_array($result)) 
                    { 
                    	//Replace the codetags with the message contents
                    	
                    $leadid = $row["leadid"]; 	
					$view_leaddetails ="<a href='".$base_url."salescrm/leads/viewleaddetails/".$leadid."'>Check Lead Details</a>"; 	
					$message = file_get_contents( 'email-templates/email-header.php' );
					//Add the message body
					$message .= file_get_contents( 'email-templates/email-body7.php' );
					//Add the message footer content
					$message .= file_get_contents( 'email-templates/email-footer.php' );
					 $replacements = array(
					        '({logo})' => $logo, 
					        '({leadid})' => $row["leadid"], 
					        '({mail_alert_date})' => $row["mail_alert_date"], 
					        '({alert_text})' => $row["alert_text"], 
					        '({productname})' => $row["productname"], 
					        '({itemgroup})' => $row["itemgroup"], 
					        '({tempcustname})' => $row["tempcustname"], 
					        '({view_leaddetails})' => $view_leaddetails, 
					        '({message_body})' => nl2br( stripslashes( $message_content ) )
					    );
					    $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
					    
					    //Make the generic plaintext separately due to lots of css and tables
					    $plaintext = $message_content;
					    $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
					    $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
					    $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
					    $plaintext = html_entity_decode( stripslashes( $plaintext ) );


					// Replace the % with the actual information
					 //$to_address=array( '0'  =>"sureshatpure@gmail.com",'1'=>'jsuresh.innova@gmail.com');
					   
					    $to_address=explode(",", $row["sales_ref_mailid"]);
					    $bm_emailid=explode(",", $row["bm_mailid"]);

					 // $to_address=$row["sales_ref_mailid"];
					 // $to_address='sureshatpure@gmail.com';
					//  $to_address=str_replace(",", ";", $to_address);
					 
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
					$mail->SetFrom('lms.alert@pure-chemical.com', 'LMS Admin');
				    
					// Set who the email is sending to
					 if( is_array( $to_address ) )
					    {
					    
					        foreach( $to_address as $i )
					        {
					        	
					            $mail->AddAddress( $i );
					        }
					    }
					    else
					    {

					        $mail->AddAddress( $to_address, "" );
					    }

					  if( is_array( $bm_emailid ) )
					    {
					    
					        foreach( $bm_emailid as $j )
					        {
					        	
					            $mail->AddAddress( $j );
					        }
					    }
					    else
					    {

					        $mail->AddAddress( $bm_emailid, "" );
					    }
					//$mail->AddAddress('sureshatpure@gmail.com');
					//$mail->AddAddress( $to_address, "" );
					$mail->addBCC('webdevelopment@pure-chemical.com');
					$mail->addCC('sureshatpure@gmail.com');
					//$mail->addCC($bm_emailid);

					// Set the subject
					$mail->Subject = 'Lead Status updated - Met the Customer';
				//	$mail->Subject = 'Lead Status- Met the Customer (Testing - please ignore..!)';

					//Set the message
					$mail->MsgHTML($message);
					//$mail->AltBody(strip_tags($message));
					// Send the email
					 
					
					if(!$mail->Send()) 
					{
					 echo "Mailer Error: " . $mail->ErrorInfo;
					}
					else
					{
						

						// write an update statment to update the "mail_sent_flag to 1 "  in the table lead_status_mail_alerts using the 

						 $sqlupdate= "update lead_status_mail_alerts set mail_sent_flag=1 WHERE mail_alert_id=".@$row[mail_alert_id]; 
						 $resultupdate = pg_query($conn, $sqlupdate);
					}	
				//	echo"<pre>";print_r($mail);echo"</pre>";
                  	
                    } // end of while loop
                    	
	              

            }

// Retrieve the email template required
  

   
?>
