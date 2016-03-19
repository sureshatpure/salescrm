<?php
	$message="";
	$footer_content="";
	$base_url="http://".$_SERVER['SERVER_NAME'].'/';

	include('phpmailer/class.phpmailer.php');
	$logo = 'http://www.pure-chemical.com/assets/images/logo/logo.png';

	//$subject="Some of the Potentials for the Product Group as been Revised based on the Customer";
	$message_content="Welcome to email templating";

	include_once("db/config.php");
	if (!$conn)
	{
		die ("Not able to connect to PostGres --> " . pg_last_error($conn));
	}
	else
	{
		$date = date ('Y-m-j G:i:s');
		$format='Y-m-d';
		$week_start = date ( $format, strtotime ('-6 day'.$date));  
		$week_end = date ( $format, strtotime ('0 day'.$date));  
		$sql= "SELECT 
		d.rpsc_line_id,
		h.dac_custgroupname,
		h.dac_prodgroupname,
		d.rpsc_salecat_name,
		d.rpsc_salecate_id,
		d.rpsc_rev_potential,
		d.rpsc_prev_potential,
		d.rpsc_mail_status,
		d.rpsc_created_username,
		d.rpsc_created_date,
		d.rpsc_updated_username,
		d.rpsc_updated_date,
		d.rpsc_created_date::DATE as dated

		FROM
		dactivity_revised_pot h,
		dactivity_revised_pot_salecategory d 

		WHERE 
		h.dac_poten_id=d.dactivity_revised_potid
		AND rpsc_mail_status='No'
		AND ( (d.rpsc_updated_date::DATE BETWEEN '".$week_start."' AND '".$week_end."')  OR  (d.rpsc_created_date::DATE BETWEEN '".$week_start."' AND '".$week_end."' ))  order by 1";

		
		$result = pg_query($conn, $sql);
		$rows = pg_num_rows($result);
		if ($rows >0)
		{
			$subject="Some of the Potentials for the Product Group as been Revised based on the Customer";
		}
		else
		{
			$subject="No Potentials were updated";
		}

	/*	if ($rows >0)
		{*/

			$to_address = array();
			$i=0;

			
			$header_content = file_get_contents( 'email-templates/email-header_rp.php' );
			$header_content= preg_replace('({subject_message})', $subject,$header_content);
			
			while ($row = pg_fetch_array($result)) 
			{ 
				$message .= file_get_contents( 'email-templates/email-revpotential.php' );
				$alert_text="Some of the Potentials for the Product Group as been Revised based on the Customer";
				$replacements = array(
				'({logo})' => $logo, 
				'({mail_alert_date})' => $row["dated"], 
				'({alert_text})' => $alert_text, 
				'({customergroup})' => $row["dac_custgroupname"], 
				'({itemgroup})' => $row["dac_prodgroupname"], 
				'({prev_potential})' => $row["rpsc_prev_potential"], 
				'({updated_potential})' => $row["rpsc_rev_potential"], 
				'({sale_category})' => $row["rpsc_salecat_name"], 
				'({updatedby})' => $row["rpsc_updated_username"], 	
				'({view_leaddetails})' => "test", 
				'({message_body})' => nl2br( stripslashes( $message_content ) )
				);
				/*Replace the codetags with the message contents*/
				$message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
			} /* end of while loop*/
			$footer_content .= file_get_contents( 'email-templates/email-footer_rp.php' );
			/*Make the generic plaintext separately due to lots of css and tables*/
			$final_message=$header_content.$message.$footer_content;
			$plaintext = $message_content;
			$plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
			$plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
			$plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
			$plaintext = html_entity_decode( stripslashes( $plaintext ) );


			$bm_address="";

			$to_address = array(1 => 'shankar.kg@pure-chemical.com','g.narender@pure-chemical.com','pathy@pure-chemical.com','c.rajarathinam@pure-chemical.com');
			/*$to_address = array(1 => 'appssupport@pure-chemical.com');*/
			$to_address = array_unique($to_address);

			
			$mail = new PHPMailer();
			$mail->IsSMTP();
			
			$mail->Host = 'smtp.gmail.com';
			
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'tls';     
			$mail->Username = 'lms.alert@pure-chemical.com';
			$mail->Password = 'pure@123';
			
			$mail->SetFrom('lms.alert@pure-chemical.com', 'LMS Admin');

			/* Set who the email is sending to*/
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

			$mail->addBCC('webdevelopment@pure-chemical.com');
			$mail->addCC('sureshatpure@gmail.com');
			$mail->addCC('anto.fernando@pure-chemical.com');
			$mail->addCC('crmsupport@pure-chemical.com'); 

			
			$mail->Subject = 'Revised Potential Alert';
			
			$mail->MsgHTML($final_message);
			/*$mail->AltBody(strip_tags($message));*/
			


			if(!$mail->Send()) 
			{
				echo "Mailer Error: " . $mail->ErrorInfo;

			}
			else
			{
				 
				$sqlupdate="UPDATE dactivity_revised_pot_salecategory SET rpsc_mail_status='Yes' WHERE rpsc_line_id IN (
				SELECT d.rpsc_line_id FROM dactivity_revised_pot h, dactivity_revised_pot_salecategory d WHERE h.dac_poten_id=d.dactivity_revised_potid 
				AND rpsc_mail_status='No' AND d.rpsc_updated_date::DATE BETWEEN '".$week_start."' AND  '".$week_end."')";
				/*echo $sqlupdate; die;*/
				$resultupdate = pg_query($conn, $sqlupdate);
			}	
		/*} // end of no of rows greater
		else
		{
			echo"No rows selected";
		}*/

			
	} // end of else for $conn sucessful


?>
