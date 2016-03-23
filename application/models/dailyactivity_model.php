<?php

class dailyactivity_model extends CI_Model
{
	public $country_id = null;
	public $i, $j;
	public $reporting_user = array();
	public $reporting_user_id = array();
	public $user_list_id;
	public $reportingid;
	public $user_report_id;
	
	function __construct()
	{
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->database();
		$db_dc=$this->load->database('forms', TRUE);
		$this->load->helper('language');
	  	$this->load->library('subquery');
		$this->load->library('session');
    
	}

	
	function getactivity_data_all()
	{

		@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];

			$jTableResult = array();
			 $sql = "SELECT distinct d.id,d.currentdate :: date,d.creationdate :: date,d.execode,d.exename,d.branch from vw_web_daily_activity d  order by id desc";
			//$sql="SELECT distinct d.id,d.currentdate :: date ,d.execode,d.exename from vw_web_daily_activity d  where user_id=302 order by id desc";

			$result = $this->db->query($sql);
			$activitydetails = $result->result_array();
			$all_record_count = count($activitydetails);
			$this->session->set_userdata('all_record_count',$all_record_count);
			$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
					$row["id"] = $activitydetails[$i]["id"];
					$row["currentdate"] = $activitydetails[$i]["currentdate"];
					$row["execode"] = $activitydetails[$i]["execode"];
					$row["exename"] = $activitydetails[$i]["exename"];
					$row["branch"] = $activitydetails[$i]["branch"];
					$row["creationdate"] = $activitydetails[$i]["creationdate"];
/*
					$row["subactivity"] = $activitydetails[$i]["subactivity"];
					$row["hour_s"] = $activitydetails[$i]["hour_s"];
					$row["modeofcontact"] = $activitydetails[$i]["modeofcontact"];
					$row["Quantity"] = $activitydetails[$i]["Quantity"];
					$row["Division"] = $activitydetails[$i]["Division"]);
					$row["Date"] = substr($activitydetails[$i]["Date"],0,-8);
					$row["Remarks"] = $activitydetails[$i]["Remarks"];
					$row["L1Status"] = $activitydetails[$i]["L1Status"];
					$row["Complaints"] = $activitydetails[$i]["Complaints"];

*/
/*
					$date_cr = new DateTime($row["created_date"]);
				  $row["created_date"]= $date_cr->format('d-M-Y'); 
	
					$row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"],0,-8);
			//		$date_mf = new DateTime($row["modified_date"]);
			//		$row["modified_date"] = $date_mf->format('d-M-Y');
					if($row["modified_date"] =="")
					{ 
						  $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"],0,-8);
						  $date_mf = new DateTime($row["created_date"]);
					    $row["modified_date"] = $date_mf->format('d-M-Y');
					}
					else
					{
						  $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"],0,-8);
							$date_mf = new DateTime($row["modified_date"]);
					  $row["modified_date"] = $date_mf->format('d-M-Y');
					}
*/
					$data[$i] = $row;
					$i++;
				}
		//		$arr = "{\"data\":" .json_encode($data). "}";
				$arr = json_encode($data);
		//	echo "{ rows: ".$arr." }";
   //    echo $arr; die;
		 	return $arr;
	}

	function getactivity_data($user_id)
	{

		@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];

			$jTableResult = array();
			 $sql = "SELECT distinct d.id,d.currentdate :: date,d.creationdate :: date,d.execode,d.exename,d.branch from vw_web_daily_activity d  where d.user_id IN(".$get_assign_to_user_id.") OR d.user_id = ".$user_id."  order by id desc";
			 // echo $sql; die;

			//$sql="SELECT distinct d.id,d.currentdate :: date ,d.execode,d.exename from vw_web_daily_activity d  where user_id=302 order by id desc";

			$result = $this->db->query($sql);
			$activitydetails = $result->result_array();
			$all_record_count = count($activitydetails);
			$this->session->set_userdata('all_record_count',$all_record_count);
			$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
					$row["id"] = $activitydetails[$i]["id"];
					$row["currentdate"] = $activitydetails[$i]["currentdate"];
					$row["execode"] = $activitydetails[$i]["execode"];
					$row["exename"] = $activitydetails[$i]["exename"];
					$row["branch"] = $activitydetails[$i]["branch"];
					$row["creationdate"] = $activitydetails[$i]["creationdate"];
/*
					$row["subactivity"] = $activitydetails[$i]["subactivity"];
					$row["hour_s"] = $activitydetails[$i]["hour_s"];
					$row["modeofcontact"] = $activitydetails[$i]["modeofcontact"];
					$row["Quantity"] = $activitydetails[$i]["Quantity"];
					$row["Division"] = $activitydetails[$i]["Division"]);
					$row["Date"] = substr($activitydetails[$i]["Date"],0,-8);
					$row["Remarks"] = $activitydetails[$i]["Remarks"];
					$row["L1Status"] = $activitydetails[$i]["L1Status"];
					$row["Complaints"] = $activitydetails[$i]["Complaints"];

*/
/*
					$date_cr = new DateTime($row["created_date"]);
				  $row["created_date"]= $date_cr->format('d-M-Y'); 
	
					$row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"],0,-8);
			//		$date_mf = new DateTime($row["modified_date"]);
			//		$row["modified_date"] = $date_mf->format('d-M-Y');
					if($row["modified_date"] =="")
					{ 
						  $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"],0,-8);
						  $date_mf = new DateTime($row["created_date"]);
					    $row["modified_date"] = $date_mf->format('d-M-Y');
					}
					else
					{
						  $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"],0,-8);
							$date_mf = new DateTime($row["modified_date"]);
					  $row["modified_date"] = $date_mf->format('d-M-Y');
					}
*/
					$data[$i] = $row;
					$i++;
				}
		//		$arr = "{\"data\":" .json_encode($data). "}";
				$arr = json_encode($data);
		//	echo "{ rows: ".$arr." }";
   //    echo $arr; die;
		 	return $arr;
	}


			function geteditdata($id)
			{
				$sql = "SELECT custgroup,itemgroup,potentialqty,subactivity,hour_s,minit,modeofcontact,quantity,division,Date,
							Remarks,L1Status,Complaints from vw_web_daily_activity d  where id=".$id."order by id";

				$result = $this->db->query($sql);
				$activitydetails = $result->result_array();
				$all_record_count = count($activitydetails);
				$this->session->set_userdata('all_record_count',$all_record_count);

				$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
					$row["custgroup"] = $activitydetails[$i]["custgroup"];
					$row["itemgroup"] = $activitydetails[$i]["itemgroup"];
					$row["potentialqty"] = $activitydetails[$i]["potentialqty"];
					$row["subactivity"] = $activitydetails[$i]["subactivity"];

					$row["hour_s"] = $activitydetails[$i]["hour_s"];
					$row["minit"] = $activitydetails[$i]["minit"];
					$row["modeofcontact"] = $activitydetails[$i]["modeofcontact"];
					$row["quantity"] = $activitydetails[$i]["quantity"];

					$row["division"] = $activitydetails[$i]["division"];
					$row["Date"] = $activitydetails[$i]["date"];
					$row["Remarks"] = $activitydetails[$i]["remarks"];
					$row["L1Status"] = $activitydetails[$i]["l1status"];
					$row["Complaints"] = $activitydetails[$i]["complaints"];
				

					$data[$i] = $row;

					$i++;
				}
				$arr = json_encode($data);
				return $arr;
//				echo $arr;

			}

			function getactivity_data_column()
			{

				$jTableResult = array();


				//$sql = "SELECT id FROM dailyactivitydtl where id ='".$this->session->userdata['loginname']."'";
				//$sql="select lower(dtl_fld) as datafield, lower(dlblch) as text,cellwidth  :: INTEGER*10 AS  width   from formdtl where formcode='SMKT140' and cellwidth>'0' order by seqno";
				$sql="select lower(dtl_fld) as datafield, lower(dlblch) as text,cellwidth  :: INTEGER*10 AS  width   from formdtl where formcode='SMKT140' and cellwidth>'0'  
					UNION 
					SELECT unnest(string_to_array('noofleads,result_type,leadid', ','))  as datafield,  unnest(string_to_array('noofleads,result_type,leadid' , ',')), unnest(string_to_array('60,100,60' , ',')) ::integer*10";


				$db_dc= $this->load->database('forms', TRUE);
				//$db2->query($sql);
				$result = $db_dc->query($sql);
				$activitydetails = $result->result_array();
				$all_record_count = count($activitydetails);
				$this->session->set_userdata('all_record_count',$all_record_count);
									
            //  echo"count ".$all_record_count; print_r($activitydetails); die; 


				$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
					$row["text"] = $activitydetails[$i]["text"];
					$row["datafield"] = $activitydetails[$i]["datafield"];
					$row["width"] = $activitydetails[$i]["width"];
					$data[$i] = $row;
					$i++;
				}
		//		$arr = "{\"data\":" .json_encode($data). "}";
				$data ='{ "columns": '.json_encode($data).'}';
//				$data =json_encode($data);
				$arr = $data;
		//	echo "{ rows: ".$arr." }";
   //    echo $arr; die;
		 		return $arr;
	}



		/*	function getactivity_data_row($header_id)
			{

				$jTableResult = array();


				 $sql="SELECT id,custgroup,exename,branch,itemgroup,potentialqty,subactivity,modeofcontact,hour_s,minit,quantity,division,date,remarks,l1status,complaints, user_id from vw_web_daily_activity d where id ='".$header_id."'";


				$result = $this->db->query($sql);
				$activitydetails = $result->result_array();
				$all_record_count = count($activitydetails);
				$this->session->set_userdata('all_record_count',$all_record_count);

				//  echo"count ".$all_record_count; print_r($activitydetails); die; 


				$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
				//	$row["id"] = $activitydetails[$i]["id"];
					$row["custgroup"] = $activitydetails[$i]["custgroup"];
					$row["itemgroup"] = $activitydetails[$i]["itemgroup"];
					$row["potentialqty"] = $activitydetails[$i]["potentialqty"];
					$row["subactivity"] = $activitydetails[$i]["subactivity"];
					$row["exename"] = $activitydetails[$i]["exename"];
					$row["branch"] = $activitydetails[$i]["branch"];
					$row["modeofcontact"] = $activitydetails[$i]["modeofcontact"];
					$row["hour_s"] = $activitydetails[$i]["hour_s"];
					$row["minit"] = $activitydetails[$i]["minit"];
					$row["quantity"] = $activitydetails[$i]["quantity"];
					$row["division"] = $activitydetails[$i]["division"];
					$row["date"] = $activitydetails[$i]["date"];
					$row["remarks"] = $activitydetails[$i]["remarks"];
					$row["l1status"] = $activitydetails[$i]["l1status"];
					$row["complaints"] = $activitydetails[$i]["complaints"];


					$data[$i] = $row;
					$i++;
				}
		//		$arr = "{\"data\":" .json_encode($data). "}";
				$data ='{ "rows": '.json_encode($data).'}';
//				$data =json_encode($data);
				$arr = $data;
		//	echo "{ rows: ".$arr." }";
   //    echo $arr; die;
		 	return $arr;
	}*/

		function getactivity_data_row($header_id)
			{

				$jTableResult = array();


				 /*$sql="SELECT id,custgroup,exename,branch,itemgroup,potentialqty,subactivity,modeofcontact,hour_s,minit,quantity,division,date,remarks,l1status,complaints, user_id,leadid,'leads/viewleaddetails/'||leadid as link,
				 				CASE WHEN COALESCE (leadid,0) ='0'  THEN 'Value'  ELSE 'Select' END result_type 
				 				from vw_web_daily_activity d where id ='".$header_id."'";*/
				/* $sql="SELECT id,custgroup,exename,branch,itemgroup,potentialqty,subactivity,modeofcontact,hour_s,minit,quantity,division,date,remarks,l1status,complaints, user_id,leadid
				 				from vw_web_daily_activity d where id ='".$header_id."'";*/

				/* $sql="SELECT id,custgroup,exename,branch,itemgroup,potentialqty,subactivity,modeofcontact,hour_s,minit,quantity,division,date,remarks,l1status,complaints, user_id,d.leadid,ls.leadstatus as statusname,sub.lst_name as substatusname
				 				from vw_web_daily_activity d, leaddetails ld, leadstatus ls, leadsubstatus sub  where id =".$header_id."
              AND ld.leadid=d.leadid 
             AND ld.leadstatus= ls.leadstatusid 
             AND ld.ldsubstatus = sub.lst_sub_id";*/

             $sql="SELECT line_id,id,custgroup,exename,branch,itemgroup,potentialqty,actualpotenqty,0 as create_lead,subactivity,modeofcontact,hour_s,minit,quantity,division,date,remarks,l1status,complaints, user_id,d.leadid,ls.leadstatus as statusname,sub.lst_name as substatusname
				 				from 
							 vw_web_daily_activity d 
							LEFT JOIN leaddetails ld ON ld.leadid=d.leadid 
						  LEFT JOIN leadstatus ls ON ls.leadstatusid=ld.leadstatus 
						LEFT JOIN leadsubstatus sub ON sub.lst_sub_id=ld.ldsubstatus 
             WHERE d.id=".$header_id;

<<<<<<< HEAD
            // echo $sql; die;
=======
             //echo $sql; die;
>>>>>>> cff797a85190895168222e9ccd4c1540cf140f2e
				$result = $this->db->query($sql);
				$activitydetails = $result->result_array();
				$all_record_count = count($activitydetails);
				$this->session->set_userdata('all_record_count',$all_record_count);

				//  echo"count ".$all_record_count; print_r($activitydetails); die; 


				$data = array();
				
				$i=0;
				while($i < count($activitydetails))
				{    
					$row = array();
				//	$row["id"] = $activitydetails[$i]["id"];
					$row["line_id"] = $activitydetails[$i]["line_id"];
					$row["custgroup"] = $activitydetails[$i]["custgroup"];
					$row["itemgroup"] = $activitydetails[$i]["itemgroup"];
					$row["potentialqty"] = $activitydetails[$i]["potentialqty"];
					$row["subactivity"] = $activitydetails[$i]["subactivity"];
					$row["exename"] = $activitydetails[$i]["exename"];
					$row["branch"] = $activitydetails[$i]["branch"];
					$row["modeofcontact"] = $activitydetails[$i]["modeofcontact"];
					$row["hour_s"] = $activitydetails[$i]["hour_s"];
					$row["minit"] = $activitydetails[$i]["minit"];
					$row["quantity"] = $activitydetails[$i]["quantity"];
					$row["create_lead"]= $activitydetails[$i]["create_lead"];
					$row["division"] = $activitydetails[$i]["division"];
					$row["date"] = $activitydetails[$i]["date"];
					$row["remarks"] = $activitydetails[$i]["remarks"];
					$row["l1status"] = $activitydetails[$i]["l1status"];
					if($activitydetails[$i]["leadid"]==0)
					{
					$row["leadid"] = "No Leads";
					$row["leadstatusid"] = "No Status";	
					$row["leadsubstatusid"] = "No Substatus";
					$row["noofleads"] = 0;	
					$row["actualpotenqty"] = $activitydetails[$i]["actualpotenqty"];
					$row["result_type"] = 'Value';	
					
					}
					else
					{
						$row["leadid"] = $activitydetails[$i]["leadid"];
						$row["leadstatusid"] = $activitydetails[$i]["statusname"];	
						$row["noofleads"] = 1;	
						$row["actualpotenqty"] = $this->getlms_potential($activitydetails[$i]["leadid"]);
						$row["result_type"] = 'Select';	
						$row["leadsubstatusid"] = $activitydetails[$i]["substatusname"];
					}
					
				//	$row["result_type"] = $activitydetails[$i]["result_type"];
					$row["complaints"] = $activitydetails[$i]["complaints"];
				//	$row["link"] = $activitydetails[$i]["link"];
					$data[$i] = $row;
					$i++;
				}
		//		$arr = "{\"data\":" .json_encode($data). "}";
				$data ='{ "rows": '.json_encode($data).'}';
//				$data =json_encode($data);
				$arr = $data;
		//	echo "{ rows: ".$arr." }";
   //    echo $arr; die;
		 	return $arr;
	}
			function getlms_potential($leadid)
			{
				$sql ="select max(potential)  FROM lead_prod_potential_types WHERE leadid=".$leadid;
				$result = $this->db->query($sql);
				//print_r($result->result_array());
				$lms_poten = $result->result_array();
			//	print_r($daily_hdr_id);
				//echo"max id is ".$daily_hdr_id[0]['max'];
				return $lms_poten[0]['max'];
			}
			function get_products($sql)
			{
				//$sql='SELECT  DISTINCT  itemgroup FROM itemmaster ORDER BY description asc';
				$result = $this->db->query($sql);
				$arr =  json_encode($result->result_array());
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			}
			function get_customers($sql)
			{
				//$sql='SELECT  DISTINCT  itemgroup FROM itemmaster ORDER BY description asc';
				$result = $this->db->query($sql);
				$arr =  json_encode($result->result_array());
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			}


			function get_field()
			{
				$result = $this->db->list_fields('itemmaster');
				foreach($result as $field)
				{
					$data[] = $field;
					return $data;
				}
			}
			function GetMaxVal($table)
			{
				$sql ="select max(id) from ".$table;
				$result = $this->db->query($sql);
				//print_r($result->result_array());
				$daily_hdr_id = $result->result_array();
			//	print_r($daily_hdr_id);
				//echo"max id is ".$daily_hdr_id[0]['max'];
				return $daily_hdr_id[0]['max'];

			}
			function save_dailyactivityhdr($daily_hdr)
			{

				if($this->db->insert('dailyactivityhdr', $daily_hdr))
					return true;
				else
					return false;
        			
			}

			function save_daily_details($dailydtls)
			{
			 foreach($dailydtls as $daliydtl)
				{
				//echo"in model<br>";
				// echo"<pre>";print_r($prod);echo"</pre>";
			//	$this->db->insert_batch('dailyactivitydtll', $dailydtls);
				}
				return $this->db->insert_batch('dailyactivitydtl', $dailydtls);
			}
			
			/*function save_daily_details_up($dailydtls,$delid)
			{

				
							$this->db->where('id',$delid);
			              	$this->db->delete('dailyactivitydtl');
							$this->db->insert_batch('dailyactivitydtl', $dailydtls);
						
			return TRUE;
			}*/
			
			function save_daily_details_up($dailydtls,$delid)
			{
				
				$this->db->db_debug = FALSE;
				try {
						$this->db->trans_start();
							$this->db->where('id',$delid);
			              	$this->db->delete('dailyactivitydtl');
							$this->db->insert_batch('dailyactivitydtl', $dailydtls);
						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE)
						{
						   // echo"Error in DB";
						    return FALSE;
						}
						else
						{
							//echo"Insert completed";
							return TRUE;
						}
					}
				catch (Exception $e) {
					//echo"in Rollback condition";
					//$this->db->trans_rollback();
					  //log_message('error', sprintf('%s : %s : DB transaction failed. Error no: %s, Error msg:%s, Last query: %s', __CLASS__, __FUNCTION__, $e->getCode(), $e->getMessage(), print_r($this->main_db->last_query(), TRUE)));
					}
			$this->db->db_debug = TRUE; 
			}
			

	
			function check_dailyhdr_duplicates($hrd_currentdate,$user1)
			{
				$sql ="select exename,id from dailyactivityhdr  where currentdate::Date ='".$hrd_currentdate."' AND user1 ='".$user1."'";
                $result = $this->db->query($sql);
                return  $result->num_rows();
			}

			



			function get_dailyhdr_update_id($hrd_currentdate,$user1)
			{
				$sql =  $this->db->select('id')->from('dailyactivityhdr')->where(array('currentdate' => $hrd_currentdate, 'user1' => $user1))->get();
				//echo $sql->num_rows(); 	die;
				//$sql ="SELECT id FROM dailyactivityhdr WHERE currentdate = '".$hrd_currentdate."' AND user1 = '".$user1."'";
				//echo $sql;
			//	$result = $this->db->query($sql);
				$daily_hdr_id = $sql->result_array();
			//	print_r($daily_hdr_id);
				//echo" id is ".$daily_hdr_id[0]['id'];
				return $daily_hdr_id[0]['id'];
			// return $sql->num_rows();
			}

			function deteled_dailyactivitydtl($dtl_id)
			{
				if(isset($dtl_id) && !empty($dtl_id))
		            {
		              	$this->db->where('id',$dtl_id);
		              	$this->db->delete('dailyactivitydtl');
		              	return  $this->db->affected_rows();	
		          
		           	 }
			}

			function update_dailyactivityhdr($daily_hdr,$daily_hdr_id)
			{

				$this->db->where('id', $daily_hdr_id);
				$this->db->update('dailyactivityhdr', $daily_hdr);
				return ($this->db->affected_rows() > 0);
			}

			function get_potential_item_customer($item,$customer)
			{
					$customer = urldecode($customer);
					$customer = trim($customer);
					$item = urldecode($item);

					$sql="SELECT 	leaddetails.leadid,leaddetails.leadid as id,sum(lead_prod_potential_types.potential)as potential
						FROM leaddetails 

					INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
					INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
					INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
					INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
					WHERE replace(trim(customermasterhdr.customergroup),'''','')='".$customer."' AND trim(view_tempitemmaster_grp.itemgroup)='".$item."' AND leaddetails.lead_close_status=0 and converted=0 
					GROUP BY leaddetails.leadid";
				//echo $sql."<br>";
				$sql1="SELECT   business_plan_customer_group_id.header_id as custgroup_id,  sum(business_yearly_gc_plan.potential_annual_qty)  as potential 
				FROM  	business_yearly_gc_plan  
				INNER JOIN business_plan_customer_group_id ON business_yearly_gc_plan.customer_group_id=business_plan_customer_group_id.header_id
				INNER JOIN business_plan_item_group_id ON business_yearly_gc_plan.item_group_id=business_plan_item_group_id.header_id
				WHERE 
					replace(business_plan_customer_group_id.customer_group,'''','') ='".$customer."' 
					AND business_plan_item_group_id.item_group='".$item."'
					GROUP BY custgroup_id";
			//echo $sql1;	
				$result = $this->db->query($sql);

				

			/*	$sql1_old="SELECT leaddetails.leadid,leaddetails.leadid as id FROM leaddetails  
					INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
					INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
					INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
					WHERE trim(customermasterhdr.customergroup)='".$customer."' AND trim(view_tempitemmaster_grp.itemgroup)='".$item."' AND leaddetails.lead_close_status=0 and converted=0";*/
 
				$result1 = $this->db->query($sql1);
				$result_bp = $result1->result_array();
				$no_of_leads= $result->num_rows();
				
				
				$resutl_arrary =$result->result_array();

				if($result->num_rows()==0)
				{
					
					$poten_val['0']['potential']=isset($result_bp['0']['potential']) ? $result_bp['0']['potential']:'0';
					$poten_val['0']['noofleads']=$no_of_leads;
					$poten_val['0']['result_type']='Value';
				}
				else
				{
					//$poten_val = $result->result_array();
<<<<<<< HEAD
					$poten_val['0']['potential']=isset($result_bp['0']['potential']) ? $result_bp['0']['potential']:'0';
=======
					$poten_val['0']['potential']=isset($result_bp['0']['potential']) ? $resutl_arrary['0']['potential']:'0';
>>>>>>> cff797a85190895168222e9ccd4c1540cf140f2e
					$poten_val['0']['noofleads']=$no_of_leads;
					$poten_val['0']['result_type']='Select';

				}
			//	print_r($poten_val);
				$arr =  json_encode($poten_val);
				$arr =	 '{ "rows" :'.$arr.' }';
				//print_r($arr);
				return $arr;
				
			}

			function get_leadids($custgrp,$prodgrp)
			{
					$custgrp = trim(urldecode($custgrp));
			        $prodgrp = trim(urldecode($prodgrp));

					$sql="SELECT leaddetails.leadid,leaddetails.leadid as id FROM leaddetails  
					INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
					INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
					INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
					WHERE replace(trim(customermasterhdr.customergroup),'''','')='".$custgrp."' AND trim(view_tempitemmaster_grp.itemgroup)='".$prodgrp."' AND leaddetails.lead_close_status=0 and converted=0";
				
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"leadid\":" .json_encode($result->result_array()). "}";
				return $arr;
			}

			
			

			//function get_activity($custgrp,$prodgrp)
			function get_activity($leadid)
			{
					//$custgrp = trim(urldecode($custgrp));
			        //$prodgrp = trim(urldecode($prodgrp));
					/*$sql="SELECT leaddetails.leadid,leaddetails.leadid as id FROM leaddetails  
					INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
					INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
					INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
					WHERE trim(customermasterhdr.customergroup)='".$custgrp."' AND trim(view_tempitemmaster_grp.itemgroup)='".$prodgrp."' AND leaddetails.lead_close_status=0 and converted=0";*/
					$sql="SELECT leaddetails.leadid,lead_prod_potential_types.product_type_id as id,lead_sale_type.n_value_displayname as lead_sale_type FROM leaddetails 
INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
INNER JOIN lead_sale_type ON lead_sale_type.n_value_id = lead_prod_potential_types.product_type_id
WHERE  leaddetails.lead_close_status=0 and converted=0 AND leaddetails.leadid=".$leadid." and lead_prod_potential_types.potential >0";
				//echo $sql; die;
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"rows\":" .json_encode($result->result_array()). "}";
				return $arr;
			}

			function get_lead_potential($leaid)
			{
	

				/*$sql="SELECT sum(lead_prod_potential_types.potential) as potential,leadproducts.quantity as requirement 
				  FROM leaddetails  
						INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
						INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
						INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
						INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid
						WHERE leaddetails.leadid=".$leaid." GROUP BY leadproducts.quantity ";*/

						$sql="SELECT lead_prod_potential_types.potential,leadproducts.quantity as requirement,leaddetails.leadid,leaddetails.leadstatus as curr_stats_id, leaddetails.ldsubstatus as curr_substats_id,lead_prod_potential_types.product_type_id as id,lead_sale_type.n_value_displayname as lead_sale_type, leadsource.leadsource as lead_source_name,leaddetails.email_id
							FROM leaddetails 
							INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
							INNER JOIN leadsource ON leaddetails.leadsource = leadsource.leadsourceid
							INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
							INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
							INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
							INNER JOIN lead_sale_type ON lead_sale_type.n_value_id = lead_prod_potential_types.product_type_id
							WHERE  leaddetails.lead_close_status=0 and converted=0 AND leaddetails.leadid=".$leaid." ORDER BY lead_prod_potential_types.potential desc LIMIT 1";
				//echo $sql; die;
				$result = $this->db->query($sql);
				
				if($result->num_rows()==0)
				{
					$poten_val['0']['potential']=0;
					$poten_val['0']['requirement']=0;
					$poten_val['0']['lead_sale_type']=0;
					$poten_val['0']['curr_stats_id']=0;
					$poten_val['0']['curr_substats_id']=0;
				}
				else
				{
					$poten_val = $result->result_array();
				}

<<<<<<< HEAD
			//	print_r($poten_val);
=======
				//print_r($poten_val);
>>>>>>> cff797a85190895168222e9ccd4c1540cf140f2e
				$arr =  json_encode($poten_val);
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
				
			}

		function get_lead_potential_update($leaid)
		{

				/*$sql="SELECT lead_prod_potential_types.potential,leadproducts.quantity as requirement,leaddetails.leadid,leaddetails.leadstatus as curr_stats_id, leaddetails.ldsubstatus as curr_substats_id,lead_prod_potential_types.product_type_id as id,lead_sale_type.n_value_displayname as lead_sale_type, leadsource.leadsource as lead_source_name,leaddetails.email_id,  leadstatus.leadstatus as leadstatusname,leadsubstatus.lst_name as leadsubstatusname
							FROM leaddetails 
							INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
							INNER JOIN leadsource ON leaddetails.leadsource = leadsource.leadsourceid
							INNER JOIN leadstatus ON leadstatus.leadstatusid =leaddetails.leadstatus
							INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id =leaddetails.ldsubstatus
							INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
							INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
							INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
							INNER JOIN lead_sale_type ON lead_sale_type.n_value_id = lead_prod_potential_types.product_type_id
							WHERE  leaddetails.lead_close_status=0 and converted=0 AND leaddetails.leadid=".$leaid." ORDER BY lead_prod_potential_types.potential,lead_prod_potential_types.product_type_id  LIMIT 1";*/
				$sql="SELECT lead_prod_potential_types.potential,leadproducts.quantity as requirement,leaddetails.leadid,leaddetails.leadstatus as curr_stats_id, leaddetails.ldsubstatus as curr_substats_id,lead_prod_potential_types.product_type_id as id,lead_sale_type.n_value_displayname as lead_sale_type, leadsource.leadsource as lead_source_name,leaddetails.email_id,  leadstatus.leadstatus as leadstatusname,leadsubstatus.lst_name as leadsubstatusname
							FROM leaddetails 
							INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
							INNER JOIN leadsource ON leaddetails.leadsource = leadsource.leadsourceid
							INNER JOIN leadstatus ON leadstatus.leadstatusid =leaddetails.leadstatus
							INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id =leaddetails.ldsubstatus
							INNER JOIN customermasterhdr ON leaddetails.company = customermasterhdr.id 
							INNER JOIN view_tempitemmaster_grp ON view_tempitemmaster_grp.id=leadproducts.productid 
							INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
							INNER JOIN lead_sale_type ON lead_sale_type.n_value_id = lead_prod_potential_types.product_type_id
							WHERE  leaddetails.lead_close_status=0 and converted=0 AND leaddetails.leadid=".$leaid." AND
							 	 lead_prod_potential_types.potential =(SELECT max(potential) FROM lead_prod_potential_types WHERE leadid=
							 	 	".$leaid.") ORDER BY lead_prod_potential_types.potential,lead_prod_potential_types.product_type_id";	
				//echo $sql;	die;						
				
				$result = $this->db->query($sql);
				
				if($result->num_rows()==0)
				{
					$poten_val['0']['potential']=0;
					$poten_val['0']['requirement']=0;
					$poten_val['0']['lead_sale_type']=0;
					$poten_val['0']['curr_stats_id']=0;
					$poten_val['0']['curr_substats_id']=0;
					$poten_val['0']['leadstatusname']=0;
					$poten_val['0']['leadsubstatusname']=0;

				}
				else
				{
					$poten_val = $result->result_array();
				}

				//print_r($poten_val);
				$arr =  json_encode($poten_val);
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			
		}
       
			

   		function getnull_leadids()
		{

			$sql="SELECT 0 as leadid, 0 as id FROM leaddetails  LIMIT 1";
			//echo $sql; die;
			$result = $this->db->query($sql);
		//	$arr =  json_encode($result->result_array());
			$arr = "{\"leadid\":" .json_encode($result->result_array()). "}";
			return $arr;
		}

		/*function get_getcollectors()
			{
					
				$sql="SELECT DISTINCT collector as collectorname from customermasterhdr WHERE  length(collector) > 0";
				//echo $sql; die;
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"rows\":" .json_encode($result->result_array()). "}";
				return $arr;
			}*/

	/*public function get_collectors($reporting_user_id) 
    {
        if (@$this->session->userdata['reportingto'] == "")
        {
             $sql = "SELECT  a.collector FROM (
                SELECT 
                CASE WHEN (COALESCE(customermasterhdr.cust_account_id,0)=0) THEN 'NO COLLECTOR' ELSE customermasterhdr.collector END 
                 AS collector
                FROM customermasterhdr ) a GROUP BY  a.collector ORDER BY collector";
        }
        else
        {
            $sql="SELECT collector FROM customermasterhdr  WHERE cust_account_id is NOT NULL  and cust_account_id >0 AND  mc_code in (
                SELECT  
                mc_sub_id
                FROM vw_web_user_login 
                 JOIN market_circle_hdr on market_circle_hdr.gc_executive_code= vw_web_user_login.header_user_id AND vw_web_user_login.header_user_id in (".$reporting_user_id.") ) GROUP BY collector 
			    UNION SELECT 'NO COLLECTOR' as collector";
        }
       
       
        $result = $this->db->query($sql);
        $arr = "{\"rows\":" .json_encode($result->result_array()). "}";
        return $arr;
    }*/
    public function get_collectors($reporting_user_id) 
    {
        if (@$this->session->userdata['reportingto'] == "")
        {
             $sql = "SELECT  a.collector FROM (
                		SELECT 
								customermasterhdr.collector 
                 AS collector
                FROM customermasterhdr WHERE length(collector) > 0 ) a GROUP BY  a.collector ORDER BY collector";
        }
        else
        {
            $sql="SELECT collector FROM customermasterhdr  WHERE cust_account_id is NOT NULL  and cust_account_id >0 AND  mc_code in (
                SELECT  
                mc_sub_id
                FROM vw_web_user_login 
                 JOIN market_circle_hdr on market_circle_hdr.gc_executive_code= vw_web_user_login.header_user_id AND vw_web_user_login.header_user_id in (".$reporting_user_id.") ) GROUP BY collector";
        }
       
       
        $result = $this->db->query($sql);
        $arr = "{\"rows\":" .json_encode($result->result_array()). "}";
        return $arr;
    }

/* functions added for merging start*/

		function get_ldstatusbyid($lead_id)
			{
			$sql="SELECT d.leadstatus as leadstatusid , s.leadstatus as lead_status_name
 FROM leaddetails d, leadstatus s  WHERE leadid=".$lead_id." and d.leadstatus = s.leadstatusid AND d.lead_close_status=0 and d.converted=0";

				$result = $this->db->query($sql);
				if($result->num_rows()==0)
				{
					$leadst_val['0']['id']=0;
					$leadst_val['0']['status_name']=0;
					
				}
				else
				{
					$leadst_val = $result->result_array();
				}

				//print_r($poten_val);
				$arr =  json_encode($leadst_val);
				$arr =	 '{ "statusid" :'.$arr.' }';
				return $arr;
			}

			function get_ldstatusfor($lead_id)
			{
			$sql="SELECT  leadstatusid as statusid , leadstatus as statusname  FROM leadstatus WHERE order_by >= (SELECT  s.order_by as order_id
 FROM  leadstatus s,leaddetails d  WHERE leadid=".$lead_id." and d.leadstatus = s.leadstatusid AND d.lead_close_status=0 and d.converted=0) ORDER BY order_by";

				$result = $this->db->query($sql);
				if($result->num_rows()==0)
				{
					$leadst_val['0']['statusid']=0;
					$leadst_val['0']['statusname']=0;
					
				}
				else
				{
					$leadst_val = $result->result_array();
				}

				//print_r($poten_val);
				$arr =  json_encode($leadst_val);
				$arr =	 '{ "statusid" :'.$arr.' }';
				return $arr;
			}
			function get_ldstatusname($lead_id)
			{
			$sql="SELECT  leadstatus as statusname  FROM leadstatus WHERE order_by >= (SELECT  s.order_by as order_id
 FROM  leadstatus s,leaddetails d  WHERE leadid=".$lead_id." and d.leadstatus = s.leadstatusid AND d.lead_close_status=0 and d.converted=0) ORDER BY order_by";

				$result = $this->db->query($sql);
				if($result->num_rows()==0)
				{
					
					$leadst_val['0']['statusname']=0;
					
				}
				else
				{
					$leadst_val = $result->result_array();
				}

				//print_r($poten_val);
				$arr =  json_encode($leadst_val);
				$arr =	 '{ "statusname" :'.$arr.' }';
				return $arr;
			}

			

			function get_ldstatus()
			{
				$sql="SELECT leadstatusid as statusid, leadstatus as statusname FROM leadstatus WHERE leadstatusid<=6 ORDER BY 1";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"statusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			
			}
			function get_ldstatus_update()
			{
				$sql="SELECT leadstatusid as leadstatusid, leadstatus as statusname FROM leadstatus WHERE leadstatusid<=6 ORDER BY 1";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"statusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			
			}
			function get_ldsubstatus()
			{
				$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid=1 ORDER BY 1";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			
			}
			function get_ldsubstatus_byid($stast_id)
			{
				$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid=".$stast_id." ORDER BY 1";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			
			}
			function get_ldsubstatus_byleadid($stast_id,$leadid)
			{
				$sub_orderid = $this->get_leadsubstatus_orderid($leadid);
				$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid=".$stast_id." AND lst_order_by >= ".$sub_orderid." ORDER BY lst_order_by";
				
				
				//echo $sql;
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			}
			function get_ldsubstatus_byleadid_update($stast_id,$leadid)
			{
				$lead_status_id = $this->get_leadstatus_id($leadid);
				$sub_orderid = $this->get_leadsubstatus_orderid($leadid);
				if($stast_id!=$lead_status_id)
				{
					$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid= ".$stast_id."  AND lst_order_by >= ".$sub_orderid." ORDER BY lst_order_by";
				}
				else
				{
					$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid= ".$stast_id."  AND lst_order_by >= ".$sub_orderid." ORDER BY lst_order_by";
				}
				//echo $sql;
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			}
			function get_ldsubstatus_byidorder($stast_id,$orderid)
			{
				$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname   FROM leadsubstatus  WHERE lst_parentid= ".$stast_id."  AND lst_order_by >= ".$orderid." ORDER BY lst_order_by";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;
			
			}
			
			function GetLeadStatusid($lst_name) 
			{
				$lst_name=urldecode($lst_name);
				$lst_name=str_replace("-","/",$lst_name);
				$this->db->select('leadstatusid');
		        $this->db->from('leadstatus');
		        $this->db->where('leadstatus', $lst_name);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        return $ld_status[0]['leadstatusid'];
			}
			function GetLeadStatusid_update($lsubst_name) 
			{
				$lsubst_name=urldecode($lsubst_name);
				$this->db->select('lst_parentid');
		        $this->db->from('leadsubstatus');
		        $this->db->where('lst_name', $lsubst_name);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        return $ld_status[0]['lst_parentid'];
			}
			
			function get_leadstatus_id($leadid) 
			{
				
				$this->db->select('leadstatus');
		        $this->db->from('leaddetails');
		        $this->db->where('leadid', $leadid);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        return $ld_status[0]['leadstatus'];
			}
			function GetLeadStatusid_order($lst_name) 
			{
				$lst_name=urldecode($lst_name);
				$this->db->select('order_by');
		        $this->db->from('leadstatus');
		        $this->db->where('leadstsatus', $lst_name);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        return $ld_status[0]['order_by'];
		        //return $ld_status[0];
			}

			function get_leadsubstatus_orderid($leadid)
			{
				$sql="SELECT	lsub.lst_sub_id as substatusid, 
							    lsub.lst_parentid as status_id,
                  				lsub.lst_order_by as order_id
                  		FROM 
									leadsubstatus lsub, 
									leadstatus ls, 
									leaddetails ld  
						WHERE 
								lsub.lst_parentid=ld.leadstatus 
							AND lsub.lst_sub_id=ld.ldsubstatus 
      						AND ls.leadstatusid = lsub.lst_parentid
      						AND ld.leadid=".$leadid." ORDER BY 1";

      			$result = $this->db->query($sql);
				if($result->num_rows()==0)
				{
					//$leadst_val['0']['status_id']=1;
					$leadst_val['0']['order_id']=1;
					//$leadst_val['0']['order_id']=1;
					
				}
				else
				{
					$leadst_val = $result->result_array();
				}


				return $leadst_val['0']['order_id'];
			}

			function get_ldsubstatusforlead($leadid)
			{
				$sql="SELECT	lsub.lst_sub_id as substatusid, 
							    lsub.lst_parentid as status_id,
                  				lsub.lst_order_by as order_id
                  		FROM 
									leadsubstatus lsub, 
									leadstatus ls, 
									leaddetails ld  
						WHERE 
								lsub.lst_parentid=ld.leadstatus 
							AND lsub.lst_sub_id=ld.ldsubstatus 
      						AND ls.leadstatusid = lsub.lst_parentid
      						AND ld.leadid=".$leadid." ORDER BY 1";

      			$result = $this->db->query($sql);
				if($result->num_rows()==0)
				{
					$leadst_val['0']['status_id']=1;
					$leadst_val['0']['substatusid']=1;
					$leadst_val['0']['order_id']=1;
					
				}
				else
				{
					$leadst_val = $result->result_array();
				}


				return $leadst_val[0];

		/*		$result1 = $this->db->query($sql1);
				$result_sql=$result1->result_array();
				
				$substatusid =$result_sql[0]['substatusid'];
				$status_id =$result_sql[0]['status_id'];
				$order_id =$result_sql[0]['order_id'];

				$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname, lst_order_by FROM leadsubstatus WHERE lst_order_by >=".$substatusid." AND lst_parentid = ".$status_id." ORDER BY lst_order_by";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;*/



			}

			function get_ldsubstatus_for_lead($substs,$status,$order)
			{
				
			$sql="SELECT lst_sub_id as substatusid, lst_name as substatusname, lst_order_by FROM leadsubstatus WHERE lst_order_by >=".$substs." AND lst_parentid = ".$status." ORDER BY lst_order_by";
				$result = $this->db->query($sql);
			//	$arr =  json_encode($result->result_array());
				$arr = "{\"substatusid\":" .json_encode($result->result_array()). "}";
				return $arr;

			}

			function check_prodgroup_dup_saleorder($customergroup,$prodgroup)
			{

				$customergroup=urldecode($customergroup);
				$prodgroup =urldecode($prodgroup);
				$sql = "select * from vw_lead_check_prod_duplicate WHERE  customergroup='".$customergroup."' AND product_group = '".$prodgroup."'";

         //echo $sql;   die;                
		        $result = $this->db->query($sql);
		        $rowcount = $result->num_rows();
		        if ($rowcount == 0) {
		            return "true";
		        } {
		            return "false";
		        }
			}

			function get_leadstatusidbyname($statusname)
			{
				//SELECT  leadstatusid from	 leadstatus  where	 leadstatus='Prospect'
		        $this->db->select('leadstatusid');
		        $this->db->from('leadstatus');
		        $this->db->where('leadstatus', $statusname);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        //print_r($ld_status); die;
		        return @$ld_status[0]['leadstatusid'];
			}
			function get_leadsub_statusidbyname($sub_statusname)
			{
				//SELECT * FROM leadsubstatus WHERE lst_name='Pending with Regional Manager'  ORDER BY 1
		        $this->db->select('*');
		        $this->db->from('leadsubstatus');
		        $this->db->where('lst_name', $sub_statusname);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        //print_r($ld_status); die;
		        return @$ld_status[0]['lst_sub_id'];
			}

			function get_customer_address($customer_id) 
     		{
    
                $sql_master = "SELECT 
                            ''::text as leadid,
                            upper(dtl.city) as cityname, 
                            ''::text as statecode,
                            ''::text as countrycode ,
                            dtl.country as contryname,
                            dtl.state as statename,
                            dtl.postal_code as postalcode,
                            dtl.mobile_no as mobile_no,
                            dtl.fax as fax,
                            dtl.cust_account_id,
                            dtl.address1 as address,
                            hdr.contact_persion as contact_person,
                            hdr.contact_no as contact_number,
                            hdr.contact_mailid as contact_mailid
                        FROM 
                            customermasterhdr hdr
                            LEFT JOIN customermasterdtl dtl ON dtl.id=hdr.id
                        WHERE 
                            hdr.id=" .$customer_id. "  AND dtl.addresstypeid  IN (SELECT DISTINCT addresstypeid FROM customermasterdtl  GROUP BY id,addresstypeid ) LIMIT 1";
              // echo "sql_master".$sql_master; die;
            $result1 = $this->db->query($sql_master);
            $customer_detail = $result1->result_array();
 			
 			
            return $customer_detail[0];
        
    		}

    		function get_user_branch($user_id)
    		{
    			// SELECT upper(location_user) FROM vw_web_user_login where header_user_id=276

    			   $sql ="SELECT upper(location_user) as location_user  FROM vw_web_user_login where header_user_id=".$user_id;
    			
		         	$result = $this->db->query($sql);
		         	$branch = $result->result_array();
		         	
		        return $branch[0]['location_user'];
    		}


    		 function get_leadproduct_saletype()
     		{

	           $sql = "SELECT sale_type_id,n_value_id,n_value,n_value_displayname FROM lead_sale_type";
	           $result = $this->db->query($sql);
	           $options = $result->result_array(); 
	         /*  foreach ($options as $option) 
	           {
	            $options_arr[$option['n_value_id']] = $option['n_value'];
	           }
	          return $options_arr;*/
	          return $options;
     	 }

     	 function get_leadsalestype()
			{
			//$sql="SELECT sale_type_id,n_value_id,n_value,n_value_displayname FROM lead_sale_type";
			$sql="SELECT n_value_id,n_value_displayname FROM lead_sale_type";
				$result = $this->db->query($sql);
				$leadst_val = $result->result_array();
				//print_r($poten_val);
				$arr =  json_encode($leadst_val);
				$arr =	 '{ "saletypeid" :'.$arr.' }';
				return $arr;
			}
			
			function get_salestypeid_byname($salestype)
			{
				//SELECT * FROM leadsubstatus WHERE lst_name='Pending with Regional Manager'  ORDER BY 1
		        $this->db->select('*');
		        $this->db->from('lead_sale_type');
		        $this->db->where('n_value_displayname', $salestype);
		        $result = $this->db->get();
		        $ld_status = $result->result_array();
		        //print_r($ld_status); die;
		        return @$ld_status[0]['n_value_id'];
			}

			function get_synched_products($sql)
			{
				//$sql='SELECT  DISTINCT  itemgroup FROM itemmaster ORDER BY description asc';
				$result = $this->db->query($sql);
				$arr =  json_encode($result->result_array());
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			}

			function GetNextMaxVal($id,$tablename)
			{
		//select max(id) from customermasterhdr
				$query = "select max(".$id.") from ".$tablename;
				$result =$this->db->query($query);
				
				if ($result->num_rows() > 0)
				{
				   $row = $result->row(); 

				  // echo"next val " .$row->nextval;	die;
				}
				
				return $row->max;
			}

			function save_tempitem($productdata)
			{
		 // print_r($companydata);
			$this->db->insert('tempitemmaster', $productdata);
	       return $this->db->insert_id();	
			}

			function get_customerpotential($customergroup)
			{
					$customergroup =strtoupper($customergroup);
					$customergroup = urldecode($customergroup);
						
				
				 /*$sql=" SELECT 
							
							customergroup,
							item_group,
						sum(bulk) as bulk,
						sum(retail) as retail ,
						sum(small_packing) as \"SMALL PACKING\",
						sum(single_tanker)  as \"SINGLE - TANKER\",
						sum(part_tanker) as \"PART TANKER\"
						 FROM 
						(

						SELECT 
							dac_poten_id,
							customergroup,
							item_group,
							sale_category_id,
							sales_category,
							annual_potential_qty,

						CASE WHEN sale_category_id=1 THEN annual_potential_qty ELSE 0 END  as  bulk,
						CASE WHEN sale_category_id=2 THEN annual_potential_qty ELSE 0 END  as  retail,
						CASE WHEN sale_category_id=3 THEN annual_potential_qty ELSE 0 END  as  small_packing,
						CASE WHEN sale_category_id=4 THEN annual_potential_qty ELSE 0 END  as  single_tanker,
						CASE WHEN sale_category_id=5 THEN annual_potential_qty ELSE 0 END  as  part_tanker,
						source 

						FROM 
							dailyact_rev_potent_for_bp 
						)
						WHERE 
						upper(customergroup) = upper('".$customergroup."')
						GROUP BY 
						customergroup,item_group
						ORDER BY 
						customergroup,item_group";*/
					$sql="  SELECT 
								customer_group,
								item_group,
							sum(bulk) as \"BULK\",
							sum(retail) as \"RETAIL\",
							sum(small_packing) as \"SMALL PACKING\",
							sum(single_tanker)  as \"SINGLE - TANKER\",
							sum(part_tanker) as \"PART TANKER\"
							 FROM 
							(
							SELECT 
								customer_group_id,
								customer_group,
								item_group,
							  item_group_id,
								sale_category_id,
								sales_category,
								rev_qty,
							CASE WHEN sale_category_id=1 THEN rev_qty ELSE 0 END  as  bulk,
							CASE WHEN sale_category_id=2 THEN rev_qty ELSE 0 END  as  retail,
							CASE WHEN sale_category_id=3 THEN rev_qty ELSE 0 END  as  small_packing,
							CASE WHEN sale_category_id=4 THEN rev_qty ELSE 0 END  as  single_tanker,
							CASE WHEN sale_category_id=5 THEN rev_qty ELSE 0 END  as  part_tanker

							FROM 
								dailyact_rev_potent_for_bp 
							)
							WHERE 
							upper(customer_group) = upper('".$customergroup."')
							GROUP BY 
								customer_group,
								item_group";
				 // echo $sql; die;
				$result = $this->db->query($sql);
				$arr =  json_encode($result->result_array());
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			}

			function get_customergroup_id($custgrp)
			{
				$this->db->select('header_id');
		        $this->db->from('business_plan_customer_group_id');
		        $this->db->where('customer_group', $custgrp);
		        $result = $this->db->get();
		        $cust_grpid = $result->result_array();
		        //print_r($ld_status); die;
		        return @$cust_grpid[0]['header_id'];
			}

<<<<<<< HEAD
			
=======
>>>>>>> cff797a85190895168222e9ccd4c1540cf140f2e
			function getproductgroup_id($prodgrp)
			{
				$this->db->select('header_id');
		        $this->db->from('business_plan_item_group_id');
		        $this->db->where('item_group', $prodgrp);
		        $result = $this->db->get();
		        $prod_grpid = $result->result_array();
		        //print_r($ld_status); die;
		        return @$prod_grpid[0]['header_id'];
			}

			function save_newpotential($dailyactivity_poten_insert) {
		        $this->db->insert('dactivity_revised_pot', $dailyactivity_poten_insert);
		        return $this->db->insert_id();
		    }

		    function update_newpotential($dailyactivity_poten_update, $potentialid) {

		        $this->db->where('dac_poten_id', $potentialid);
		        $this->db->update('dactivity_revised_pot', $dailyactivity_poten_update);
		        return ($this->db->affected_rows() > 0);
		    }

			        
        	function checkduplicate_record($custgroup,$prodgrp)
			{
				/*$sql ="SELECT dac_poten_id FROM dactivity_revised_pot  WHERE dac_custgroupname='".$custgroup."' AND dac_prodgroupname ='".$prodgrp."'";
                echo $sql;
                $result = $this->db->query($sql);
                echo"<pre>";print_r($result);echo"</pre>";
				$recordset = $result->result_array();
                if($result->num_rows()>0)
                {
                	 return @$recordset[0]['dac_poten_id'];
                	 return @$recordset[0]['dac_poten_id'];
                }*/

                $sql ="SELECT dac_poten_id FROM dactivity_revised_pot  WHERE  dac_custgroupname='".$custgroup."' AND dac_prodgroupname ='".$prodgrp."'";
                $result = $this->db->query($sql);
                $result->num_rows();
                $daily_potenid = $result->result_array();
              //  echo"<pre>";print_r($daily_potenid);echo"</pre>";
             
                if($result->num_rows()==0)
                {
                    $daily_potenid['0']['noofrows']=$result->num_rows();
                    $daily_potenid['0']['exename']="";
                    $daily_potenid['0']['id']=0; 
                }
                else
                {
                    $daily_potenid['0']['noofrows']=$result->num_rows();
                    $daily_potenid['0']['id']=$daily_potenid['0']['dac_poten_id'];
          
                }
                

            //  echo"<pre>";print_r($daily_potenid['0']);echo"</pre>";
                //echo"max id is ".$daily_hdr_id[0]['max'];
               // return $daily_hdr_id[0]['id'];
               return $daily_potenid;

			}

			function save_revisedpotential($dailyactivity_poten_insert) {
		        $this->db->insert('dactivity_revised_pot_salecategory', $dailyactivity_poten_insert);
		        return $this->db->insert_id();
		    }

		    function update_revisedpotential($dailyactivity_poten_update, $potentialid,$sale_catid) {

		        $this->db->where('dactivity_revised_potid', $potentialid);
		        $this->db->where('rpsc_salecate_id', $sale_catid);
		        $this->db->update('dactivity_revised_pot_salecategory', $dailyactivity_poten_update);
		        return ($this->db->affected_rows() > 0);
		    }

		    function get_bpsalecatid($salestype)
			{
				$salestype=strtoupper($salestype);
				
				$this->db->select('header_id');
		        $this->db->from('business_plan_sales_category_id');
		        $this->db->where('sales_category', $salestype);
		        $result = $this->db->get();
		        $prod_grpid = $result->result_array();
		        //print_r($ld_status); die;
		        return @$prod_grpid[0]['header_id'];
			}



			function check_salecat_duplicate_record($potential_id,$saletypeid)
			{
				
                $sql ="SELECT dactivity_revised_potid FROM dactivity_revised_pot_salecategory  WHERE  dactivity_revised_potid=".$potential_id." AND rpsc_salecate_id =".$saletypeid;
                $result = $this->db->query($sql);
                $result->num_rows();
                $daily_potenid_detail = $result->result_array();
              //  echo"<pre>";print_r($daily_potenid);echo"</pre>";
             
                if($result->num_rows()==0)
                {
                    $daily_potenid_detail['0']['noofrows']=$result->num_rows();
                    $daily_potenid_detail['0']['exename']="";
                    $daily_potenid_detail['0']['id']=0; 
                }
                else
                {
                    $daily_potenid_detail['0']['noofrows']=$result->num_rows();
                    $daily_potenid_detail['0']['id']=$daily_potenid_detail['0']['dactivity_revised_potid'];
          
                }
                

            //  echo"<pre>";print_r($daily_potenid['0']);echo"</pre>";
                //echo"max id is ".$daily_hdr_id[0]['max'];
               // return $daily_hdr_id[0]['id'];
               return $daily_potenid_detail;

			}
<<<<<<< HEAD

			function get_customerhdr_id($custgrp)
			{
				$this->db->select('id');
		        $this->db->from('customermasterhdr');
		        $this->db->where('customergroup', html_entity_decode($custgrp));
		        $result = $this->db->get();
		        $cust_grpid = $result->result_array();
		        //print_r($ld_status); die;
		        //return @$cust_grpid[0]['id'];
		        $arr =  json_encode($cust_grpid[0]['id']);
				$arr =	 '{ "rows" :'.$arr.' }';
				return $arr;
			}
=======
>>>>>>> cff797a85190895168222e9ccd4c1540cf140f2e
}
?>

