<?php
class A
{
    function foo()
    { include('connection.php');


			 $sql ="SELECT 
			   customermasterhdr.customergroup as customer_group,
			   leaddetails.company,
			   leaddetails.leadid as lead_number,
			   leaddetails.createddate as date_added,
			   leaddetails.last_modified as date_updated,
			   leaddetails.industry_id as industry_type,
			   leaddetails.leadstatus as lead_status,
			   leaddetails.ldsubstatus as sub_status,
			   leaddetails.leadsource as lead_source,
			   leaddetails.lead_2pa_no as lead_2pa_no,
			   get_appiontment_date(leaddetails.leadid, leaddetails.leadstatus,leaddetails.ldsubstatus) as appointment_date,
			   get_notable_to_get_appiontment(leaddetails.leadid, leaddetails.leadstatus,leaddetails.ldsubstatus) as comments,
			   get_order_cancelled_reason(leaddetails.leadid, leaddetails.leadstatus,leaddetails.ldsubstatus) as order_cancel_reason,
			   get_sample_reject_reason(leaddetails.leadid, leaddetails.leadstatus,leaddetails.ldsubstatus) as sample_reject_reason,
			   get_soc_number(leaddetails.company,leadproducts.product_group) as crm_soc_no,
			   customer_details.contact_person as contact_name,
			  customer_details.contact_mailid as email,
			    customer_details.address as street_address,
			  leaddetails.crd_id as credit_assessment,
			  leadproducts.product_group,
			  leadproducts.quantity as immediate_requirement,
			  sale_type.product_type_id as sales_category,
			  sale_type.potential as potential ,
			 1 as business_type,
			  leaddetails.app_lead_id,
			leaddetails.assignleadchk  as assigned_to
	

			FROM
			  leaddetails 

			    INNER JOIN leadstatus ON leadstatus.leadstatusid = leaddetails.leadstatus  
			    INNER JOIN customermasterhdr ON customermasterhdr.id = leaddetails.company  
			     INNER JOIN industry_segment ON industry_segment.id = leaddetails.industry_id  
			     INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus 
			     INNER JOIN leadsource ON leadsource.leadsourceid = leaddetails.leadsource 
			     INNER JOIN lead_credit_assesment ON leaddetails.crd_id  = lead_credit_assesment.crd_id 
			     INNER JOIN leadproducts ON leaddetails.leadid  = leadproducts.leadid
			      AND mob_lead_stagingtable.delete_flag=0 
			        INNER JOIN (
			                                                                    SELECT 
			                                                                                            dtl.country as contryname,
			                                                                                            dtl.postal_code as postalcode,
			                                                                                            dtl.fax as fax,
			                                                                                            dtl.mobile_no as mobile_no,
			                                                                                            dtl.state as statename,
			                                                                                            dtl.address1 as address,
			                                                                                            hdr.id,
			                                                                                            hdr.contact_persion as contact_person,
			                                                                                            hdr.contact_no as contact_number,
			                                                                                            hdr.contact_mailid as contact_mailid,
			                                                                                            hdr.cust_account_id as cust_account_id,
			                                                                                            hdr.tempcustname,
			                                                                                            hdr.customergroup,
			                                                                                            hdr.customer_number,
			                                                                                            hdr.customer_name
			                                                                    FROM 
			                                                                     customermasterhdr hdr
			                                                                    LEFT JOIN customermasterdtl dtl ON dtl.id=hdr.id
			                                                                                        WHERE 
			                                                                                         dtl.addresstypeid='AddressType-1'
			                                                        ) customer_details  ON customer_details.id= leaddetails.company
			                                                    LEFT JOIN  (
			                                                        SELECT leadid,potential,product_type_id  FROM lead_prod_potential_types WHERE potential > 0 
			                                                        ) sale_type ON sale_type.leadid = leaddetails.leadid 
			                                                         WHERE leaddetails.ldsubstatus!=40 AND leaddetails.app_sync_flag='N'";


			   
			    $result = pg_query($db, $sql);

			    $rows = pg_num_rows($result);

			        $leaddata = array();
			        $i = 0;
			        while ($row_details= pg_fetch_array($result,NULL,PGSQL_ASSOC)) 
			        {
			            

			            if( $row_details['sub_status']==8 )
			            {
			              $row_details['comments']=$row_details['lead_2pa_no'];
			            }
			    
			            if( $row_details['sub_status']==27 )
			            {
			              $row_details['comments']=$row_details['order_cancel_reason'];
			            }
			            if( $row_details['sub_status']==21 )
			            {
			              $row_details['comments']=$row_details['sample_reject_reason'];
			            }
			      
			            $additional_details =array(
			                                    'appointment_date'=>isset($row_details['appointment_date']) && ($row_details['appointment_date']!="") ? "".$row_details['appointment_date']."" : null,
			                                    'comments'=>isset($row_details['comments']) && ($row_details['comments']!="") ? "".$row_details['comments']."" : '',
			                                    'contact_name'=>isset($row_details['contact_name']) && ($row_details['contact_name']!="") ? "".$row_details['contact_name']."" : '',
			                                    'email'=>isset($row_details['email']) && ($row_details['email']!="") ? "".$row_details['email']."" : '',
			                                    'potential'=>(int)$row_details['potential'],
			                                    'reason'=>isset($row_details['order_cancel_reason']) && ($row_details['order_cancel_reason']!="") ? "".$row_details['order_cancel_reason']."" : '',
			                                    'soc_number'=>isset($row_details['crm_soc_no']) && ($row_details['crm_soc_no']!="") ? "".$row_details['crm_soc_no']."" : '',
			                                    'street_address'=>isset($row_details['street_address']) && ($row_details['street_address']!="") ? "".$row_details['street_address']."" :'',
			                                    );
			           
			 	          $row["customer_group"] = $row_details['customer_group'];
			            if( $i==0 || $i==2 || $i==4 )
			            {
			              $row["lead_source"] = (int)$row_details['lead_source'];
			            }
			            else
			            {
			             $row["lead_source"] = (int)$row_details['lead_source']; 
			            }
			            $row["lead_number"] = (int)$row_details['lead_number'];
			            $row["date_added"] = $row_details['date_added'];
			            $row["date_updated"] = $row_details['date_updated'];
			            $row["industry_type"] = (int)$row_details['industry_type'];
			            $row["lead_status"] = (int)$row_details['lead_status'];
			            $row["sub_status"] = (int)$row_details['sub_status'];
			            $row["additional_details"] = array($additional_details);


			            
			            $row["credit_assessment"] = (int)$row_details['credit_assessment'];
			            $row["product_group"] = $row_details['product_group'];
			            $row["immediate_requirement"] = (float)$row_details['immediate_requirement'];
			            $row["sales_category"] = (int)$row_details['sales_category'];
			            $row["potential"] = (float)$row_details['potential'];
			            $row["app_lead_id"] = $row_details['app_lead_id'];
			            $row["assigned_to"] = (int)$row_details['assigned_to'];
			            $row["created_by"] = (int)$row_details['assigned_to'];
			            $row["business_type"] = (int)$row_details['business_type'];
			            $row["status"] = 2;
			            $row["deleted"] = "0";
			            $leaddata[$i] = $row;
			            $i++;

			            $sql_update_sync_flag="UPDATE leaddetails set app_sync_flag='Y' WHERE leadid=".$row_details['lead_number'];
			              $result_update = pg_query($db, $sql_update_sync_flag);
			            
			  }  
			 
			     $arr = "{\"thirdparty_uid\": \"".$api_key."\",\"details\":{\"leads\":". json_encode($leaddata)."}}";
				$header = array(
				    'Content-Type: application/json',
				    'Accept: application/json',
				     'Content-Length: ' . strlen($arr) 
				  );

			 

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $sendleads_url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$arr);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_FAILONERROR,TRUE);
				curl_setopt($ch, CURLOPT_CRLF,FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
			    $error = curl_error($ch);

			    if ( $error != '') {
			    echo 'Error in CURL: '.$error;
			    }

				curl_close($ch);
			//	print_r($result);
			  $data = json_decode($result, 1);
			  

			 

			  if($data['success'])
			  {
			    echo"all leads updated sucessfully";
			  }
			  else
			  {
			     $mobile_get_leads = $data['result']['failedData'];
			     
			     if (count($mobile_get_leads)>0 )
			     {
			      foreach($mobile_get_leads as $value)
			        {
			          $leadid =$value['lead_number'];
			          

			          $sql_faileddata_sync_flag="UPDATE leaddetails set app_sync_flag='N' WHERE leadid=".$leadid;
			          $result_update = pg_query($db, $sql_faileddata_sync_flag);
			        }
			     }
			     else
			     {
			      echo "No Leads to send";
			     }
			    
			  }
				}
    
}
?>