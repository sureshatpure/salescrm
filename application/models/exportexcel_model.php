<?php
class exportexcel_model extends CI_Model
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
	function get_customergroup()
				{
				
				$reporting_to = $this->session->userdata['reportingto'];
				$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

				
				$sql="SELECT DISTINCT customergroup from customermasterhdr";
			
				//echo $sql; die;
					$result = $this->db->query($sql);
					$options = $result->result_array();
					//$all_branch =  array('branch' =>'All');
					//array_push($options, $all_branch);
					$arr =  json_encode($options); 
				// return $result = $this->db->query($sql);
        
	//echo $arr; die;
			return $arr;

				}


			function get_itemgroups()
			{

				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				//if ($get_assign_to_user_id=="")
				
					$sql="SELECT DISTINCT itemgroup from itemmaster WHERE itemgroup!=0";

				

					$result = $this->db->query($sql);
					$userlist = $result->result_array();
// 				       $myarray = $this->array_push_multi_assoc('', count($userlist),'header_user_id','#','displayname','--Select Branch--','branch','null');
//         				$userlist = array_merge((array)$userlist, (array)$myarray); 
					$arr =  json_encode($userlist);   

				
			//echo $arr; die;
			return $arr;
		}

		function get_assigned_tobranch()
			{

				//@$this->session->set_userdata($get_assign_to_user_id); 
				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				 $designation_role = trim($this->session->userdata['designation']);
				if (@$reporting_to=="")
				{
					/*$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(aliasloginname) as displayname from vw_web_user_login ) a where upper(branch)='".$this->brach_sel."' order by displayname"; */
					$sql="select 
									header_user_id,
									displayname , 
						      branch from (
												SELECT  v.header_user_id,c.name as branch,m.mc_sub_id, upper(v.aliasloginname) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
						                    AND m.gc_executive_code=v.header_user_id AND upper(c.name)='".urldecode($this->brach_sel)."' GROUP BY  v.header_user_id,upper(v.aliasloginname),  c.name,m.mc_sub_id 
																) a 
						where upper(branch)='".urldecode($this->brach_sel)."' GROUP BY header_user_id, displayname , branch order by displayname";
												

				}
				elseif($designation_role =='CO-ORDINATOR' || $designation_role =='ASSISTANT MANAGER' || $designation_role =='PM')
                {
                    $sql="select 
									header_user_id,
									displayname , 
						      branch from (
												SELECT  v.header_user_id,c.name as branch,m.mc_sub_id, upper(v.aliasloginname) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
						                    AND m.gc_executive_code=v.header_user_id AND upper(c.name)='".urldecode($this->brach_sel)."' GROUP BY  v.header_user_id,upper(v.aliasloginname),  c.name,m.mc_sub_id 
																) a 
						where upper(branch)='".urldecode($this->brach_sel)."' GROUP BY header_user_id, displayname , branch order by displayname";
                }
				else{
					/*$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(aliasloginname) as displayname from vw_web_user_login ) a where header_user_id IN (".$get_assign_to_user_id.")  and upper(branch)='".urldecode($this->brach_sel)."' order by displayname";*/
					$sql="select 
									header_user_id,
									displayname , 
						      branch from (
												SELECT  v.header_user_id,c.name as branch,m.mc_sub_id,  upper(v.aliasloginname) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
						                    AND m.gc_executive_code=v.header_user_id AND upper(c.name)='".urldecode($this->brach_sel)."' GROUP BY  v.header_user_id,upper(v.aliasloginname),  c.name,m.mc_sub_id 
																) a 
						where header_user_id IN (".$get_assign_to_user_id.") and upper(branch)='".urldecode($this->brach_sel)."' GROUP BY header_user_id, displayname , branch order by displayname";
				}
				//echo $sql; die;
					$result = $this->db->query($sql);
					$userlist = $result->result_array();
				$arr =  json_encode($userlist); 
		//	echo $arr; die;
			return $arr;
		}

		function get_distinct_branch()
			{

				//@$this->session->set_userdata($get_assign_to_user_id); 
				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				if (@$reporting_to=="")
				{
					$sql="select DISTINCT branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login  WHERE  length(location_user) >2) a order by branch"; 

												

				}
				else{


						$sql="select  DISTINCT branch from (select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login where length(location_user) >2 ) a where header_user_id IN (".$get_assign_to_user_id.") order by branch";									
				}
				//echo $sql; die;
					$result = $this->db->query($sql);
					$userlist = $result->result_array();
				$arr =  json_encode($userlist); 
		//	echo $arr; die;
			return $arr;
		}




} // end of class function
?>
