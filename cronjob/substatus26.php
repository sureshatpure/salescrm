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
							    A .mail_alert_id,
							    ld.assignleadchk AS executiveid,
								ld.created_user as created_user_id,
								u.aliasloginname as createdby,
							    v.sales_ref_mailid,
							    case WHEN pt.potential=0  THEN v."2ND_LVL_MAIL_ID"  ELSE v."2ND_LVL_MAIL_ID"||\',shankar.kg@pure-chemical.com\' END AS bm_mailid,
							    ld.user_branch,
							    A .leadid,
							    A .appointment_due_date AS appointment_date,
							    A .lead_substatus_id,
							    A .substatus_updated_date,
							    A .mail_alert_date,
							    A .status_action_type,
							    b.content_text AS alert_text,
							    M .description AS ProductName,
							    M .itemgroup,
							    C .tempcustname,
							    C .stdname,
							    C .customer_name
							FROM
							    lead_status_mail_alerts A,
							    lead_status_mailalert_content b,
							    leadproducts P,
							    view_tempitemmaster_grp M,
							    customermasterhdr C,
							    leaddetails ld,
							    vw_sales_executive_matrix_mail_id v,
							    lead_prod_potential_types pt,
							    vw_web_user_login u 
							WHERE
							    A .lead_substatus_id = 26
							AND pt.leadid=ld.leadid 
							AND pt.product_type_id=1
							AND A .lead_substatus_id = b.substatus_id
							AND C . ID = ld.company
							AND ld.leadid = A .leadid
							AND ld.leadid = P .leadid
							AND P .productid :: TEXT = M . ID :: TEXT
							AND v.user_code = ld.assignleadchk
							AND mail_alert_date = CURRENT_DATE
							AND mail_sent_flag = 0
							AND u.header_user_id= ld.created_user
							AND mail_alert_id IN (
							    SELECT
							        MAX (mail_alert_id)
							    FROM
							        lead_status_mail_alerts
							    GROUP BY
							        lead_substatus_id,
							        leadid
							)';
               

		// echo $sql; die;
                $result = pg_query($conn, $sql);
                //$rows = pg_num_rows($result);
	        // echo"no of rows ".$rows = pg_num_rows($result)."<br>";
               	$rows = pg_num_rows($result)."<br>";
                //$customerdetails = pg_fetch_array($result); print_r($customerdetails); die;	


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
					        '({createdby})' => $row["createdby"], 
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
					//    echo"bm_emailid <pre>";print_r($bm_emailid);echo"</pre>";
					 
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
					    $ed1='pathy@pure-chemical.com';
					    $ed2='surya@pure-chemical.com';
					    $cmd='cmd@pure-chemical.com';
					    $analyst='analyst@pure-chemical.com';
					    $gm='g.narender@pure-chemical.com';
					 //   $kgs='shankar.kg@pure-chemical.com';
					//$mail->AddAddress('sureshatpure@gmail.com');
					//$mail->AddAddress( $to_address, "" );
					 
					$mail->AddAddress( $ed1, "" );
					$mail->AddAddress( $ed2, "" );
					$mail->AddAddress( $cmd, "" );
					$mail->AddAddress( $gm, "" );
					$mail->AddAddress( $analyst, "" );
					$mail->addBCC('webdevelopment@pure-chemical.com');
					$mail->addCC('sureshatpure@gmail.com');
					//$mail->addCC($bm_emailid);

					// Set the subject
					$mail->Subject = 'Lead Status updated - Order Confirmed';

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
					//echo"<pre>";print_r($mail);echo"</pre>";
                  	
                    } // end of while loop
                    	
	              

            }

// Retrieve the email template required
  

   
?>
