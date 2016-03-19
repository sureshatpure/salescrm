<?php

class Dashboard_model extends CI_Model
{
	public $country_id = null;
	public $i, $j;
	public $reporting_user = array();
	public $reporting_user_id = array();
	public $user_list_id;
	public $reportingid;
	public $reporting_to;
	public $user_report_id;
  	public  $get_assign_to_user_id= array();
  	public $lead_ub_count;
  	public $user_id;
  	public $branch;
	
			function __construct()
			{
				$this->load->library('form_validation');
				$this->load->helper('url');
				$this->load->database();
				$this->load->helper('language');
				$this->load->library('subquery');
				$this->load->library('session');
				
			}

		 	function get_leaddetails_aging_dashboard()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
//				 $sql='select  * from  vw_lead_aging_report';
					 $sql='select  * from  vw_lead_aging_report_0days';
				   }
			else	
				    {


			      $sql='SELECT  d.leadstatusid,d.leadstatus,sum(d."<30") as  "<30" ,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120") as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user_0days  d where assign_to_code IN ('.
             	$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus';
				     }
				  //   echo $sql; die;

						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();

						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["zdays"] = $jTableResult['leaddetails'][$i]["<30"];
							$row["tdays"] = $jTableResult['leaddetails'][$i][">30"];
							$row["sdays"] = $jTableResult['leaddetails'][$i][">60"];
							$row["ndays"] = $jTableResult['leaddetails'][$i][">90"];
							$row["twdays"] = $jTableResult['leaddetails'][$i][">120"];
							$row["eidays"] = $jTableResult['leaddetails'][$i][">180"];
							$row["total"] = $jTableResult['leaddetails'][$i]["<30"]+ $jTableResult['leaddetails'][$i][">30"]+$jTableResult['leaddetails'][$i][">60"]+ $jTableResult['leaddetails'][$i][">90"]+$jTableResult['leaddetails'][$i][">120"]+$jTableResult['leaddetails'][$i][">180"];					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_aging_chart()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						 $sql='select  * from  vw_lead_aging_report_0days';
						 }
			else	
							{
						$sql='SELECT  d.leadstatusid,d.leadstatus,sum(d."<30") as  "<30" ,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120") as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user_0days  d where assign_to_code IN ('.$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus ORDER BY 1';
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["zdays"] = $jTableResult['leaddetails'][$i]["<30"];
					$row["tdays"] = $jTableResult['leaddetails'][$i][">30"];
					$row["sdays"] = $jTableResult['leaddetails'][$i][">60"];
					$row["ndays"] = $jTableResult['leaddetails'][$i][">90"];
					$row["twdays"] = $jTableResult['leaddetails'][$i][">120"];
					$row["eidays"] = $jTableResult['leaddetails'][$i][">180"];
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}


	

				function get_leaddetails_for_grid($sid,$days)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND leadstatusid =".$sid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND leadstatusid =".$sid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND leadstatusid =".$sid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND leadstatusid =".$sid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND leadstatusid =".$sid;
								break;
						case 180:
								 $where = "days >180 AND leadstatusid =".$sid;
								break;
						}

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
								$sql='SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where '. $where;
						 }
						else
			 				 {   
								$sql='SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where '. $where. ' and assign_to_id in('.$get_assign_to_user_id.') order by leadstatusid' ;
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}

				function get_subleaddetails_for_grid($subsid,$days)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND lst_sub_id =".$subsid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND lst_sub_id =".$subsid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND lst_sub_id =".$subsid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND lst_sub_id =".$subsid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND lst_sub_id =".$subsid;
								break;
						case 180:
								 $where = "days >180 AND lst_sub_id =".$subsid;
								break;
						}

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
								$sql='SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
											where '. $where;
						 }
						else
			 				 {   
								$sql='SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
										  where '. $where. ' and assign_to_id in('.$get_assign_to_user_id.') order by lst_sub_id' ;
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}


			function get_subleaddetails_filter_for_grid($subsid,$days,$branch,$user_id,$from_date,$to_date)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND lst_sub_id =".$subsid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND lst_sub_id =".$subsid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND lst_sub_id =".$subsid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND lst_sub_id =".$subsid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND lst_sub_id =".$subsid;
								break;
						case 180:
								 $where = "days >180 AND lst_sub_id =".$subsid;
								break;
						}


						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if (($from_date!="") && ($user_id!=""))
							{
								
									$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id." AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";	
							}	
						
						      else if(($from_date=="") && ($user_id!=""))
						      {
						      	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id;	
						      }
						      else if (($from_date=="") && ($user_id=="")&&($branch!=""))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'";	
						      }
						      else if (($branch=="") || ($branch=='Select Branch')||($branch=="Select%20Branch"))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
											where ". $where;
						      }
						
								
						 }
						else
			 			{   
			 				// echo "from_date ".$from_date."<br>";  echo "user_id ".$user_id."<br>";
			 				 	if (($from_date!="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE   order by lst_sub_id" ;	
			 				 	}
			 				 	else if(($from_date=="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  order by lst_sub_id" ;	
			 				 	}
			 				 	
			 				 	else if (($from_date=="") && ($user_id=="") &&($branch!=""))
			 				 	{
								$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  order by leadstatusid" ;	
			 				 	}
			 				    else if (($branch=="") || ($branch=="Select Branch") || ($branch="Select%20Branch"))
							      {
							      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_sub_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")   order by lst_sub_id" ;	
							      }
								
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}


				function get_leaddetailsfilter_for_grid($sid,$days,$branch,$user_id,$from_date,$to_date)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND leadstatusid =".$sid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND leadstatusid =".$sid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND leadstatusid =".$sid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND leadstatusid =".$sid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND leadstatusid =".$sid;
								break;
						case 180:
								 $where = "days >180 AND leadstatusid =".$sid;
								break;
						}

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if (($from_date!="") && ($user_id!=""))
							{
								
									$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id." AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";	
							}	
						
						      else if(($from_date=="") && ($user_id!=""))
						      {
						      	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id;	
						      }
						      else if (($from_date=="") && ($user_id==""))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'";	
						      }
						
								
						 }
						else
			 			{   
			 				// echo "from_date ".$from_date."<br>";  echo "user_id ".$user_id."<br>";
			 				 	if (($from_date!="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE   order by leadstatusid" ;	
			 				 	}
			 				 	else if(($from_date=="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  order by leadstatusid" ;	
			 				 	}
			 				 	
			 				 	else if (($from_date=="") && ($user_id==""))
			 				 	{
								$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  order by leadstatusid" ;	
			 				 	}
								
						   }

       			//	echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}

				


				function get_subleaddetailsfilter_for_grid($sid,$days,$branch,$user_id,$from_date,$to_date)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND leadstatusid =".$sid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND leadstatusid =".$sid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND leadstatusid =".$sid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND leadstatusid =".$sid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND leadstatusid =".$sid;
								break;
						case 180:
								 $where = "days >180 AND leadstatusid =".$sid;
								break;
						}

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if (($from_date!="") && ($user_id!=""))
							{
								
									$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id." AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";	
							}	
						
						      else if(($from_date=="") && ($user_id!=""))
						      {
						      	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND d.assign_to_id=".$user_id;	
						      }
						      else if (($from_date=="") && ($user_id=="")&&($branch!=""))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'";	
						      }
						      else if (($branch=="") || ($branch=='Select Branch')||($branch=="Select%20Branch"))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where;
						      }
						
								
						 }
						else
			 			{   
			 				// echo "from_date ".$from_date."<br>";  echo "user_id ".$user_id."<br>";
			 				 	if (($from_date!="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE   order by leadstatusid" ;	
			 				 	}
			 				 	else if(($from_date=="") && ($user_id!=""))
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  AND d.assign_to_id=".$user_id."  order by leadstatusid" ;	
			 				 	}
			 				 	
			 				 	else if (($from_date=="") && ($user_id=="") &&($branch!=""))
			 				 	{
								$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  order by leadstatusid" ;	
			 				 	}
			 				    else if (($branch=="") || ($branch=="Select Branch") || ($branch="Select%20Branch"))
							      {
							      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")   order by leadstatusid" ;	
							      }
								
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}

				function get_subleaddetailsfilter_nouser_for_grid($sid,$days,$branch,$from_date,$to_date)
				{
						switch ($days) 
						{
						case 0:
								 $where = "days >=0 and days <= 30 AND leadstatusid =".$sid;
								break;
						case 30:
								 $where = "days >30 and days <= 60 AND leadstatusid =".$sid;
								break;
						case 60:
								 $where = "days >60 and days <= 90 AND leadstatusid =".$sid;
								break;
						case 90:
								 $where = "days >90 and days <= 120 AND leadstatusid =".$sid;
								break;
						case 120:
								 $where = "days >120 and days <= 180 AND leadstatusid =".$sid;
								break;
						case 180:
								 $where = "days >180 AND leadstatusid =".$sid;
								break;
						}

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if ($from_date!="")
							{
								
									$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";	
							}	
						
						      else if($from_date=="") 
						      {
						      	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."' ";	
						      }
						      else if (($from_date=="") && ($branch!=""))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where ."  AND branch='".$branch."'";	
						      }
						      else if (($branch=="") || ($branch=='Select Branch')||($branch=="Select%20Branch"))
						      {
						      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
											where ". $where;
						      }
						
								
						 }
						else
			 			{   
			 				// echo "from_date ".$from_date."<br>";  echo "user_id ".$user_id."<br>";
			 				 	if ($from_date!="")
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'   AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE   order by leadstatusid" ;	
			 				 	}
			 				 	else if($from_date=="" )
			 				 	{
			 				 	$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  order by leadstatusid" ;	
			 				 	}
			 				 	
			 				 	else if (($from_date=="")  &&($branch!=""))
			 				 	{
								$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")  AND branch='".$branch."'  order by leadstatusid" ;	
			 				 	}
			 				    else if (($branch=="") || ($branch=="Select Branch") || ($branch="Select%20Branch"))
							      {
							      		$sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to,d.branch from vw_lead_aging_detail_report d
										  where ". $where. " and assign_to_id in(".$get_assign_to_user_id.")   order by leadstatusid" ;	
							      }
								
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
						$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}
					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}





				function get_branches()
				{
				
				$reporting_to = $this->session->userdata['reportingto'];
				$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

				if (@$reporting_to=="")
				{
									$sql="SELECT DISTINCT a.branch FROM ( SELECT 	header_user_id,	UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE LENGTH (location_user) > 2) a ORDER BY a.branch";
				} else
				{

								 
								//$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
								$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
								

								$sql="SELECT DISTINCT a.branch FROM ( SELECT 	header_user_id,	UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE header_user_id IN (".$get_assign_to_user_id.") and LENGTH (location_user) > 2) a ORDER BY a.branch";
		
				}
				//echo $sql; die;
					$result = $this->db->query($sql);
					$options = $result->result_array();
					$arr =  json_encode($options); 
				// return $result = $this->db->query($sql);
        
	//echo $arr; die;
			return $arr;

				}

				function get_branches_all()
				{
				
				$reporting_to = $this->session->userdata['reportingto'];
				$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

				if (@$reporting_to=="")
				{
									$sql="SELECT DISTINCT a.branch FROM ( SELECT 	header_user_id,	UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE LENGTH (location_user) > 2) a ORDER BY a.branch";
				} else
				{

								 
								//$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
								$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
								

								$sql="SELECT DISTINCT a.branch FROM ( SELECT 	header_user_id,	UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE header_user_id IN (".$get_assign_to_user_id.") and LENGTH (location_user) > 2) a ORDER BY a.branch";
		
				}
				//echo $sql; die;
					$result = $this->db->query($sql);
					$options = $result->result_array();
					$all_branch =  array('branch' =>'All');
					array_push($options, $all_branch);
					$arr =  json_encode($options); 
				// return $result = $this->db->query($sql);
        
	//echo $arr; die;
			return $arr;

				}

		   function get_jchdr()
				{
					$sql="SELECT  header_id,line_id,jc_code,'JC'||jc_code as jc_name,jc_period_from,jc_period_to,acc_yr FROM jc_calendar_dtl WHERE acc_yr='2014-2015' order by jc_code";
					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-select-");
	
					$arr =  json_encode($options); 
				return $arr;

				}
				 function get_jchdr_forweek($fin_year)
				{
					$sql="SELECT  header_id,line_id,jc_code,'JC'||jc_code as jc_name,jc_period_from,jc_period_to,acc_yr FROM jc_calendar_dtl WHERE acc_yr='".$fin_year."' order by jc_code";
					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-select-");
	
					$arr =  json_encode($options); 
				return $arr;

				}

 				function get_jcweek_hdr($account_yr,$jc_code)
				{
					$jc_code= $jc_code+1;
				    $sql="SELECT  header_id,line_id,jc_cal_line_id,acc_yr,week_id,'Week'||week_id as jc_weekname,week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE   acc_yr='".$account_yr."' AND jc_cal_line_id in (SELECT line_id from	 jc_calendar_dtl WHERE acc_yr='".$account_yr."' AND jc_code=".$jc_code.")";

					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-select-");
	
					$arr =  json_encode($options); 
				return $arr;

				}				

		 function getgcfromdate($jc_code,$fin_year)
		 {
		 	$sql="select jc_period_from from jc_calendar_dtl where jc_code='".$jc_code."' and acc_yr='".$fin_year."' order by line_id";
				//echo $sql; die;
					$result = $this->db->query($sql);
					$jcperiods = $result->result_array();
					return $jcperiods[0]['jc_period_from'];
		 }

		function getgctodate($jc_code,$fin_year)
		 {
		 	$sql="select jc_period_to from jc_calendar_dtl where jc_code='".$jc_code."' and acc_yr='".$fin_year."' order by line_id";
				//echo $sql; die;
					$result = $this->db->query($sql);
					$jcperiods = $result->result_array();
				return $jcperiods[0]['jc_period_to']; 
		 }

		  function get_jcperiodfromdate()
			{

				$sql="select jc_period_from from jc_calendar_dtl where jc_code='".urldecode($this->jc_code)."' and acc_yr='".urldecode($this->fin_year)."' order by line_id";
				//echo $sql; die;
					$result = $this->db->query($sql);
					$jcperiods = $result->result_array();
				$arr =  json_encode($jcperiods); 
				//	echo $arr; die;
			return $arr;
			}
			function get_jcperiodtodate()
			{
				$sql="select jc_period_to from jc_calendar_dtl where jc_code='".urldecode($this->jc_code)."' and acc_yr='".urldecode($this->fin_year)."' order by line_id";
				
				//echo $sql; die;
				
				
					$result = $this->db->query($sql);
					$jcperiods = $result->result_array();
				$arr =  json_encode($jcperiods); 
				//	echo $arr; die;
			return $arr;
			}
			function get_jcweek_periods()
			{	

				$sql="SELECT week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE  acc_yr='".urldecode($this->fin_year)."' AND jc_cal_line_id='".urldecode($this->jc_code)."' AND line_id='".urldecode($this->jc_week)."' order by line_id";
           // echo $sql; die;
			$result = $this->db->query($sql);
			$jcperiods = $result->result_array();
			$arr =  json_encode($jcperiods); 
			return $arr;
			}



			function get_financeyear()
				{
					$sql="SELECT finance_year FROM jc_calendar_hdr ORDER BY 1 asc  ";
					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-select-");
	
					$arr =  json_encode($options); 
				return $arr;

				}
				

			function get_assigned_tobranch()
			{

				//@$this->session->set_userdata($get_assign_to_user_id); 
				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				if (@$reporting_to=="")
				{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login ) a where upper(branch)='".$this->brach_sel."' order by displayname"; 
												

				}
				else{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login ) a where header_user_id IN (".$get_assign_to_user_id.")  and upper(branch)='".urldecode($this->brach_sel)."' order by displayname";
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

		function get_reassigntouser()
			{

				//@$this->session->set_userdata($get_assign_to_user_id); 
				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				if (@$reporting_to=="")
				{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from 	vw_web_user_login ) a order by displayname"; 
												

				}
				else{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from 				vw_web_user_login ) a where header_user_id IN (".$get_assign_to_user_id.")   order by displayname";
				}
				//echo $sql; die;
					$result = $this->db->query($sql);
					$userlist = $result->result_array();
				$arr =  json_encode($userlist); 
		//	echo $arr; die;
			return $arr;
		}
		function get_usersfor_loginuser()
			{

				//@$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
				@$reporting_to = $this->session->userdata['reportingto'];
				$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				//if ($get_assign_to_user_id=="")
				if (@$reporting_to=="")
				{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login WHERE  duser NOT LIKE 'TOMSAL%' AND  duser NOT LIKE 'ISD%' ) a";

				}
				else{
					$sql="select header_user_id,displayname , branch from 
									(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from vw_web_user_login WHERE  duser NOT LIKE 'TOMSAL%' AND  duser NOT LIKE 'ISD%' ) a where header_user_id IN (".$get_assign_to_user_id.")";
				}

					$result = $this->db->query($sql);
					$userlist = $result->result_array();
// 				       $myarray = $this->array_push_multi_assoc('', count($userlist),'header_user_id','#','displayname','--Select Branch--','branch','null');
//         				$userlist = array_merge((array)$userlist, (array)$myarray); 
					$arr =  json_encode($userlist);   

				
			//echo $arr; die;
			return $arr;
		}



		 function get_leaddetails_aging_dashboard_withfilter($branch,$user_id)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
			 @$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
			//print_r($this->session->userdata); die; 
			 @$reporting_to = $this->session->userdata['reportingto'];
			if (@$reporting_to =='')
				  {
					 if($user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								branch='".$branch."'  
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_based_on_branch_user d
								where 
									branch='".$branch."' AND assign_to_code='".$user_id."'
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code
								order by 1";
							}
				   }
			else	
				    {


			     				if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
									d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$user_id."'
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code
							order by 1";
							}
				     }
                               // echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					$row["total"] = $jTableResult['leaddetails'][$i]["lz"] + $jTableResult['leaddetails'][$i]["gth"]+ $jTableResult['leaddetails'][$i]["gsi"]						 +$jTableResult['leaddetails'][$i]["gni"]+$jTableResult['leaddetails'][$i]["gtw"]+$jTableResult['leaddetails'][$i]["gei"];			

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}


			function get_leaddetails_aging_chart_withfilter($branch,$user_id)
			{
			@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
			@$reporting_to = $this->session->userdata['reportingto'];
				 if (@$reporting_to =='')
						{
							if($user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								branch='".$branch."'  
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_based_on_branch_user d
								where 
									branch='".$branch."' AND assign_to_code='".$user_id."'
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code
								order by 1";
							}
							
						 }
				else // if the reportingto user id is not null	
						{
							if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$user_id."'
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code
							order by 1";
							}
						 
						}

 //echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}

			function get_lead_user_branch_count($branch,$user_id)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				//print_r($get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id']);
                          if (($branch!="") && ($user_id!=""))
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk=".$user_id;	
                          }
                            elseif (($branch!="") &&  (@$reporting_to==""))
                          {
					$sql="select count(*)  from leaddetails where user_branch ='".$branch."'";	
                          }
                          elseif (($user_id!="") &&  ($branch==""))
                          {
					$sql="select count(*)  from leaddetails where  assignleadchk=".$user_id;	
                          }
                          elseif (($user_id=="") &&  ($branch!="") && ($get_assign_to_user_id!=""))
                          {
                          	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk IN (".$get_assign_to_user_id.")";
                          }
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
				//echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}


			function get_sublead_user_branch_count($status_id)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				
                         /* if (($branch!="") and ($user_id!=""))
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk=".$user_id;	
                          }
                          elseif (($user_id!="") &&  ($branch==""))
                          {
					$sql="select count(*)  from leaddetails where  assignleadchk=".$user_id;	
                          }elseif (($user_id=="") &&  ($branch!=""))
                          {
                          	$sql="select count(*)  from leaddetails where user_branch ='".$branch."'" ;	
                          }
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }*/
				$sql="select count(*)  from leaddetails where leadstatus =".$status_id;
		//		echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}

			function get_leaddetails_aging_dashboard_withdatefilter($branch,$user_id,$from_date,$to_date)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
			
			//print_r($this->session->userdata); die; 
			 
				$reportingto=$this->session->userdata['reportingto'];
				$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				if ($reportingto=='')
				{
					 if($user_id=="")
							{
							$sql="SELECT 
									d.leadstatusid, 
									d.leadstatus, 
									d.branch,
									sum(d.lz) as lz, 
									sum(d.gth) as gth, 
									sum(d.gsi) as gsi, 
									sum(d.gni) as gni, 
									sum(d.gtw) as gtw, 
									sum(d.gei) as gei 
							FROM 
									vw_lead_aging_based_on_branch_user_datefilter d 
							where 
								branch='".$branch."'  	

							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_based_on_branch_user_datefilter d
								where 
									branch='".$branch."' AND assign_to_code='".$user_id."' AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code
								order by 1";
							}
				   }
			else	
				    {
/*
				      $sql='SELECT  d.leadstatusid,d.leadstatus,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120")
              as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user d where assign_to_code IN ('.
             	$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus';
*/

			     				if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$user_id."' AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code
							order by 1";
							}
				     }
                               // echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}


			function get_leaddetails_aging_dashboard_withbranchdatefilter($branch,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
				
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_based_on_branch_user_datefilter d
								where 
									branch='".$branch."'  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								group by
									d.leadstatusid,
									d.leadstatus,
									d.branch
									
								order by 1";
							
				   }
			else	
				    {

								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
								
							order by 1";
							
							
				     }
                               // echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_aging_chart_withbranchdatefilter($branch,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
						
							
							
						 }
				else // if the reportingto user id is not null	
						{
							
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							
							
						 
						}

 				//echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}

			function get_leaddetails_aging_chart_withdatefilter($branch,$sel_user_id,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if($sel_user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_based_on_branch_user_datefilter d
								where 
									branch='".$branch."' AND assign_to_code='".$sel_user_id."' AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code
								order by 1";
							}
							
						 }
				else // if the reportingto user id is not null	
						{
							if($sel_user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$sel_user_id."' AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code
							order by 1";
							}
						 
						}

 				//echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}
//additional branch_date_filter - start 
function get_leaddetails_aging_additional_withbranchdatefilter($branch,$from_date,$to_date)
{

				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 $sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
						      WHERE
						      			user_branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				   }
			else	
				    {

 							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND user_branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
			     				
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"] = $jTableResult['leaddetails'][$i]["prospects"]+ $jTableResult['leaddetails'][$i]["met_the_customer"]+$jTableResult['leaddetails'][$i]["credit_sssessment"]+$jTableResult['leaddetails'][$i]["sample_trails_formalities"]+$jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+$jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];		
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;

}
function get_leaddetails_aging_additional_chart_withbranchdatefilter($branch,$from_date,$to_date)
{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						 $sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship
							FROM 
									vw_lead_aging_additional_report_nnl

							 WHERE
						      			user_branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
						 }
			else	
							{
						$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND  user_branch='".$branch."'  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
					/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
}

//additional branch_date_filter - end


			function get_lead_user_branch_count_datefilter($branch,$user_id,$from_date,$to_date)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				if(($branch!="") and ($user_id!="") and ($from_date!="") and ($to_date!="") )
				{
					$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk=".$user_id." AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE  " ;	
				}
                          elseif (($branch!="") and ($user_id!=""))
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk=".$user_id;	
                          }
                          elseif (($user_id!="") &&  ($branch==""))
                          {
					$sql="select count(*)  from leaddetails where  assignleadchk=".$user_id;	
                          }elseif (($user_id=="") &&  ($branch!=""))
                          {
                          	$sql="select count(*)  from leaddetails where user_branch ='".$branch."'" ;	
                          }
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
				//echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}


			function get_lead_user_branch_count_branchdatefilter($branch,$from_date,$to_date)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				if(($branch!="") and ($from_date!="") and ($to_date!="") )
				{
					$sql="select count(leadid)  from leaddetails where user_branch ='".$branch."'  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE  " ;	
				}
                          elseif ($branch!="") 
                          {
                           	$sql="select count(leadid)  from leaddetails where user_branch ='".$branch."'";	
                          }
                          else {
                          	$sql="select count(leadid)  from leaddetails";
                          }
				
				//echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}

			function get_lead_user_branch_count_allbranches($branch,$from_date,$to_date)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				if(($branch!="") and ($from_date!="") and ($to_date!="") )
				{
					$sql="select count(*)  from leaddetails where createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE  " ;	
				}
                          elseif ($branch!="") 
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."'";	
                          }
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
				//echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}

			public function array_push_assoc($array, $key, $value)
			{
			        // Simply insert the key and the value into the array
			        $array[$key] = $value;
			        return $array; // return it 
			}

			function array_push_multi_assoc($array, $key, $key1, $value1,$key2,$value2,$key3,$value3)
			{
				$array[$key][$key1] = $value1;
				$array[$key][$key2] = $value2;
				$array[$key][$key3] = $value3;
			return $array;
		     }

			 function get_subleaddetails_aging_dashboard($status_id)
			 {

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
//				 $sql='select  * from  vw_lead_aging_report';
					 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								 d.leadstatusid=".$status_id." 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,d.substsname
							order by 1";
				   }
			else	
				    {

			      			$sql='SELECT d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname,
			      					sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei   FROM  vw_lead_aging_substatus_based_on_branch_user_datefilter  d where d.assign_to_code IN ('.
             	$get_assign_to_user_id.') and  d.leadstatusid ='.$status_id.'  group by d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname';
				     }
				  //   echo $sql; die;

						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();

						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
							$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];

							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];

					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			 }
			function get_subleaddetails_aging_chart($status_id)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 $sql='SELECT  d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei 

					FROM  vw_lead_aging_substatus_based_on_branch_user_datefilter  d  where  leadstatusid ='.$status_id.'  group by d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname ORDER BY 1';
					 }
				else	
					{
				$sql='SELECT  d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei 

					FROM  vw_lead_aging_substatus_based_on_branch_user_datefilter  d where assign_to_code IN ('.$get_assign_to_user_id.') AND leadstatusid ='.$status_id.'   group by d.leadstatusid,d.leadstatus,d.ldsubstatus,d.substsname ORDER BY 1';
					 }


			//	echo $sql; die;

				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
					$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];

					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];

					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}
			function get_subleaddetails_aging_dashboard_withfilter($status_id,$branch,$user_id)
			{
					//echo"branch ".$branch;  	echo" status_id  ".$status_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 if($user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								branch='".$branch."'  AND d.leadstatusid=".$status_id." 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d
								where 
									branch='".$branch."' AND assign_to_code='".$user_id."' AND d.leadstatusid=".$status_id."
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,d.ldsubstatus,d.substsname
								order by 1";
							}
				   }
			else	
				    {
/*
				      $sql='SELECT  d.leadstatusid,d.leadstatus,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120")
              as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user d where assign_to_code IN ('.
             	$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus';
*/

			     				if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND d.leadstatusid=".$status_id."
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND  d.leadstatusid=".$status_id."  AND  assign_to_code='".$user_id."'
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,d.ldsubstatus,d.substsname
							order by 1";
							}
				     }
                            //    echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
							$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}
			function get_subleaddetails_aging_chart_withfilter($status_id,$branch,$user_id)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if($user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								branch='".$branch."'  AND d.leadstatusid =".$status_id."
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d
								where 
									branch='".$branch."'  AND d.leadstatusid =".$status_id." AND assign_to_code='".$user_id."'
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,d.ldsubstatus,
								d.substsname
								order by 1";
							}
							
						 }
				else // if the reportingto user id is not null	
						{
							if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND d.leadstatusid =".$status_id."
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'   AND d.leadstatusid =".$status_id."  AND  assign_to_code='".$user_id."'
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,d.ldsubstatus,
								d.substsname
							order by 1";
							}
						 
						}

 //echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
					$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];					
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}


			function get_subleaddetails_aging_dashboard_withdatefilter($status_id,$branch,$from_date,$to_date)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 if($user_id=="")
							{
							$sql="SELECT 
									d.leadstatusid, 
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.branch,
									sum(d.lz) as lz, 
									sum(d.gth) as gth, 
									sum(d.gsi) as gsi, 
									sum(d.gni) as gni, 
									sum(d.gtw) as gtw, 
									sum(d.gei) as gei 
							FROM 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d 
							where 
								branch='".$branch."'  AND d.leadstatusid=".$status_id."  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
									d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d
								where 
									branch='".$branch."' AND assign_to_code='".$user_id."' AND d.leadstatusid=".$status_id."  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,d.ldsubstatus,
									d.substsname
								order by 1";
							}
				   }
			else	
				    {
/*
				      $sql='SELECT  d.leadstatusid,d.leadstatus,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120")
              as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user d where assign_to_code IN ('.
             	$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus';
*/

			     				if($user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND d.leadstatusid=".$status_id."  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
									d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$user_id."' AND d.leadstatusid=".$status_id."  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,
								d.ldsubstatus,
									d.substsname
							order by 1";
							}
				     }
                             //  echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
							$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];		
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_subleaddetails_aging_dashboard_withbranchdatefilter($status_id,$branch,$from_date,$to_date)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							$sql="SELECT 
									d.leadstatusid, 
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.branch,
									sum(d.lz) as lz, 
									sum(d.gth) as gth, 
									sum(d.gsi) as gsi, 
									sum(d.gni) as gni, 
									sum(d.gtw) as gtw, 
									sum(d.gei) as gei 
							FROM 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d 
							where 
								branch='".$branch."'  AND d.leadstatusid=".$status_id."  AND 
								createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
						
							
				   }
			else	
				    {
/*
				      $sql='SELECT  d.leadstatusid,d.leadstatus,sum(d.">30") as  ">30" ,sum(d.">60") as ">60",sum(d.">90") as ">90",sum(d.">120")
              as ">120",sum(d.">180") as ">180"  FROM  vw_lead_aging_report_with_user d where assign_to_code IN ('.
             	$get_assign_to_user_id.') group by d.leadstatusid,d.leadstatus';
*/

			     		
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."'  AND d.leadstatusid=".$status_id."  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
									d.substsname
							order by 1";
						
							
							
				     }
                             //  echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
							$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
							$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];		
							$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
							$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
							$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
							$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
							$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
							$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_subleaddetails_aging_chart_withdatefilter($status_id,$branch,$sel_user_id,$from_date,$to_date)
			{
							$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if($sel_user_id=="")
							{
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								branch='".$branch."' AND d.leadstatusid=".$status_id."  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
									d.leadstatusid,
									d.leadstatus,
									d.ldsubstatus,
									d.substsname,
									d.assign_to_code,
									sum(d.lz) as lz,
									sum(d.gth) as gth,
									sum(d.gsi) as gsi,
									sum(d.gni) as gni,
									sum(d.gtw) as gtw,
									sum(d.gei) as gei
								from 
									vw_lead_aging_substatus_based_on_branch_user_datefilter d
								where 
									branch='".$branch."' AND assign_to_code='".$sel_user_id."' AND d.leadstatusid=".$status_id." AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
								group by
									d.leadstatusid,
									d.leadstatus,
									d.assign_to_code,d.ldsubstatus,
								d.substsname
								order by 1";
							}
							
						 }
				else // if the reportingto user id is not null	
						{
							if($sel_user_id=="")
							{
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND d.leadstatusid=".$status_id." AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							}
							else
							{
								$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.assign_to_code,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND  assign_to_code='".$sel_user_id."'  AND d.leadstatusid=".$status_id." AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.assign_to_code,d.ldsubstatus,
								d.substsname
							order by 1";
							}
						 
						}

 //echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
					$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];		
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}

			function get_subleaddetails_aging_chart_withbranchdatefilter($status_id,$branch,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							
							$sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								branch='".$branch."' AND d.leadstatusid=".$status_id."  AND 	createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							
							
							
						 }
				else // if the reportingto user id is not null	
						{
							
								 $sql="SELECT
								d.leadstatusid,
								d.leadstatus,
								d.ldsubstatus,
								d.substsname,
								d.branch,
								sum(d.lz) as lz,
								sum(d.gth) as gth,
								sum(d.gsi) as gsi,
								sum(d.gni) as gni,
								sum(d.gtw) as gtw,
								sum(d.gei) as gei
							from 
								vw_lead_aging_substatus_based_on_branch_user_datefilter d
							where 
								assign_to_code IN (".$get_assign_to_user_id.") AND
								branch='".$branch."' AND d.leadstatusid=".$status_id." AND  createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							group by
								d.leadstatusid,
								d.leadstatus,
								d.branch,d.ldsubstatus,
								d.substsname
							order by 1";
							
							
						 
						}

 //echo $sql; die;


				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$chart_leads_count = count($jTableResult['leaddetails']);
				$this->session->set_userdata('chart_leads_count',$chart_leads_count);
				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
					$row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
					$row["ldsubstatus"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
					$row["substsname"] = $jTableResult['leaddetails'][$i]["substsname"];		
					$row["zdays"] = $jTableResult['leaddetails'][$i]["lz"];
					$row["tdays"] = $jTableResult['leaddetails'][$i]["gth"];
					$row["sdays"] = $jTableResult['leaddetails'][$i]["gsi"];
					$row["ndays"] = $jTableResult['leaddetails'][$i]["gni"];
					$row["twdays"] = $jTableResult['leaddetails'][$i]["gtw"];
					$row["eidays"] = $jTableResult['leaddetails'][$i]["gei"];
					
					

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}

			
			function get_lead_daynop_branch_count($branch)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				
                          if ($branch!="") 
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."'";	
                          }
                         
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
				//echo $sql;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}


			function get_leaddetails_additional_aging_dashboard()
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 $sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				   }
			else	
				    {

 							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
			     				
				     }
                             
                              //	 echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
									

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_additional_aging_chart()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						 $sql='SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							GROUP BY
								user_branch
							ORDER BY
								user_branch';
						 }
			else	
							{
						$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
					/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}



			function get_leaddetails_aging_additional_withfilter($branch)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					
							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									user_branch ='".$branch."'

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				  }
							
			else	
				    {

							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND user_branch ='".$branch."'
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
			     	
							
				     }
                               // echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"] = $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+$jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+$jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_aging_additional_chart($branch)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									user_branch ='".$branch."'

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
						 }
			else	
							{
						$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND user_branch ='".$branch."'
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
					/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}

			function get_leaddetails_aging_additional_withdatefilter($from_date,$to_date)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					
							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									 createddate  between '".$from_date."'::DATE  and '".$to_date."'::DATE

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				  }
							
			else	
				    {

							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
			     	
							
				     }
                        //        echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"] =  $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+$jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+$jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_aging_additional_withdatefilter_chart($from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									 createddate  between '".$from_date."'::DATE  and '".$to_date."'::DATE

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
						 }
			else	
							{
						$sql="SELECT  
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_aging_additional_report_nnl
							WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
					/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;
			}


			function get_lead_branch_count($branch)
			{
				//echo"<pre>";print_r($this->session->userdata);echo"</pre>"; die;
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				$reportingto =$this->session->userdata['get_assign_to_user_id'];
				//print_r($get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id']);
                          if (($branch!="") and ($get_assign_to_user_id!=""))
                          {
                           	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk IN (".$get_assign_to_user_id.")";
                          }
                          elseif (($get_assign_to_user_id!="") &&  ($branch==""))
                          {
					$sql="select count(*)  from leaddetails where   assignleadchk IN (".$get_assign_to_user_id.")";
                          }
                          elseif ( ($branch!="") && ($get_assign_to_user_id!=""))
                          {
                          	$sql="select count(*)  from leaddetails where user_branch ='".$branch."' and assignleadchk IN (".$get_assign_to_user_id.")";
                          }
                          elseif ( ($branch!="") && (@$reportingto==""))
                          {
                          	$sql="select count(*)  from leaddetails where user_branch ='".$branch."'";
                          }
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
			//	echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}

			
			function get_lead_date_count($from_date,$to_date)
			{
				//$this->session->set_userdata('lead_ub_count',$lead_ub_count);
				@$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
				//print_r($get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id']);
                          if (($from_date!="") and ($get_assign_to_user_id!=""))
                          {
                          	
                           	$sql="select count(*)  from leaddetails where assignleadchk IN   (".$get_assign_to_user_id.")  AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";
                          }
                          elseif ((@$reporting_to=="") &&  ($from_date!=""))
                          {
					$sql="select count(*)  from leaddetails where  createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE";
                          }
                         
                          else {
                          	$sql="select count(*)  from leaddetails";
                          }
				
			//	echo $sql; die;
				$result = $this->db->query($sql);
				$lead_count = $result->result_array();
				//$lead_ub_count = $jTableResult['count'];
				
				//echo "count is ".$lead_count[0]['count']; die;
				$this->session->set_userdata('lead_ub_count',$lead_count[0]['count']);
				return $lead_count[0]['count'];
			}

			function get_leaddetails_daynoprogress_dashboard()
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					
							$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";
							
				  }
							
			else	
				    {

							$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 
									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship ";
			     	
							
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
							$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
							$row["total_idle_days"] = $jTableResult['leaddetails'][$i]["total_idle_days"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["avg_idle_days"] = $jTableResult['leaddetails'][$i]["avg_idle_days"];
							$row["total"]= $jTableResult['leaddetails'][$i]["prospect"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+$jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+$jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"]+ $jTableResult['leaddetails'][$i]["avg_idle_days"];
							
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_daynoprogress_chart()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";
						 }
			else	
							{
						$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 

									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship  ";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
					$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
					$row["total_idle_days"] = $jTableResult['leaddetails'][$i]["total_idle_days"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					$row["avg_idle_days"] = $jTableResult['leaddetails'][$i]["avg_idle_days"];
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}
			
			function get_leaddetails_aging_daynop_withfilter($branch)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 if($branch=="")
							{
							$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";
							}
							else
							{
								$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial

								      WHERE
								      		user_branch='".$branch."'
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";

								
							}
				   }
			else	
				    {


			     				if($branch=="")
							{
								 $sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 

									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship  ";
							}
							else
							{
								$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND  user_branch='".$branch."'


									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship  ";
							}
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
							$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
							$row["total_idle_days"] = $jTableResult['leaddetails'][$i]["total_idle_days"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["avg_idle_days"] = $jTableResult['leaddetails'][$i]["avg_idle_days"];
							$row["total"] = $jTableResult['leaddetails'][$i]["prospect"]+
							+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+$jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+ $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"]+ $jTableResult['leaddetails'][$i]["avg_idle_days"];
							
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}
			function get_leaddetails_aging_daynop_withfilter_chart($branch)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					 if($branch=="")
							{
							$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";
							}
							else
							{
								$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
									FROM 
										 vw_lead_day_no_progress_initial

								      WHERE
								      		user_branch='".$branch."'
									GROUP BY

											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship ";

								
							}
				   }
			else	
				    {


			     				if($branch=="")
							{
								 $sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.") 

									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship  ";
							}
							else
							{
								$sql="SELECT 
											empname,
											total_idle_days,
											totalleadcount,
											assign_to_id,
											prospect,
											met_the_customer,
											credit_sssessment,
											sample_trails_formalities,
											enquiry_offer_negotiation,
											managing_and_implementation,
											expanding_and_build_relationship,
											round(avg(total_idle_days /totalleadcount)) as avg_idle_days
							 		FROM 
							 				vw_lead_day_no_progress_initial
									WHERE
									assign_to_id IN   (".$get_assign_to_user_id.")  AND  user_branch='".$branch."'


									GROUP BY
												empname,
												total_idle_days,
												totalleadcount,
												assign_to_id,
												prospect,
												met_the_customer,
												credit_sssessment,
												sample_trails_formalities,
												enquiry_offer_negotiation,
												managing_and_implementation,
												expanding_and_build_relationship  ";
							}
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
							$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
							$row["total_idle_days"] = $jTableResult['leaddetails'][$i]["total_idle_days"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["avg_idle_days"] = $jTableResult['leaddetails'][$i]["avg_idle_days"];
							
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = json_encode($data);
						return $arr;
			}

			function get_leaddetails_generatedleads_dashboard()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new 

								GROUP BY  
								empname,
								createduser";
							
				  }
							
			else	
				    {

							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new 

								GROUP BY  
								empname,
								createduser ";
			     	
							
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
							$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
							$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							
							
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_generatedleads_chart()
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new 

								GROUP BY  
								empname,
								createduser";
						 }
			else	
							{
						$sql=" SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new 

								GROUP BY  
								empname,
								createduser";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
					$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
					$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}


			function get_leaddetails_generatedleads_dashboard_withfilter($branch)
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
					
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new 
								WHERE  user_branch ='".$branch."' 


								GROUP BY  
								empname,
								createduser";
							
				  }
							
			else	
				    {

							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   vw_lead_generated_created_user_new WHERE  user_branch ='".$branch."' 

								GROUP BY  
								empname,
								createduser ";
			     	
							
				     }
                               	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
							$row = array();
							$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
							$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
							$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							
							
							
					

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_generatedleads_chart_withfilter($branch)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   
											     vw_lead_generated_created_user_new WHERE  user_branch ='".$branch."' 

								GROUP BY  
								empname,
								createduser";
						 }
			else	
							{
						$sql=" SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											     vw_lead_generated_created_user_new WHERE  user_branch ='".$branch."' 

								GROUP BY  
								empname,
								createduser";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
					$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
					$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}

			function get_leaddetails_generated_withbranchdatefilter($branch,$from_date,$to_date)
			{

				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
				
								$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM			
								 	vw_lead_generated_created_user_new 
								 WHERE  
								 user_branch ='".$branch."'  AND 
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							
				   }
			else	
				    {


			     			
								 $sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM			
								 	vw_lead_generated_created_user_new 
								 WHERE  
								 user_branch ='".$branch."'  AND 
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							
							
				     }
                               // echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
						$row = array();
						$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
						$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
						$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
						$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
						$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
						$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
						$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
						$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
						$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
						$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_leaddetails_generated_chart_withbranchdatefilter($branch,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   
											     vw_lead_generated_created_user_new 
								WHERE  
									 user_branch ='".$branch."'  AND 
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 


								GROUP BY  
								empname,
								createduser";
						 }
			else	
							{
						$sql=" SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											     vw_lead_generated_created_user_new 
								WHERE  
									 user_branch ='".$branch."'  AND 
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
					$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
					$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}


			function get_leaddetails_generated_allbranches($branch,$from_date,$to_date)
			{

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
				
								$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM			
								 	vw_lead_generated_created_user_new 
								 WHERE  
						
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							
				   }
			else	
				    {


			     			
								 $sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM			
								 	vw_lead_generated_created_user_new 
								 WHERE  
								 
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							
							
				     }
                             //  echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
						$row = array();
						$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
						$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
						$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
						$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
						$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
						$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
						$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
						$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
						$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
						$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}


			function get_leaddetails_generated_chart_allbranches($branch,$from_date,$to_date)
			{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
						
							$sql="SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											   
											     vw_lead_generated_created_user_new 
								WHERE  
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 


								GROUP BY  
								empname,
								createduser";
						 }
			else	
							{
						$sql=" SELECT
								      sum(lead_counts) as totalLeadcount,
										empname,
										createduser
										,sum(prospect) as prospect,sum(met_the_customer) as met_the_customer,sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation, sum(expanding_and_build_relationship) as expanding_and_build_relationship 
								FROM 
											     vw_lead_generated_created_user_new 
								WHERE  
								 createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE 

								GROUP BY  
								empname,
								createduser";
							 }




				$jTableResult = array();
				//$sql='select  * from  vw_lead_aging_report';
				$result = $this->db->query($sql);
				$jTableResult['leaddetails'] = $result->result_array();

				$data = array();
				
				$i=0;
				while($i < count($jTableResult['leaddetails']))
				{    
					$row = array();
					$row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
					$row["totalleadcount"] = $jTableResult['leaddetails'][$i]["totalleadcount"];
					$row["createduser"] = $jTableResult['leaddetails'][$i]["createduser"];
					$row["prospects"] = $jTableResult['leaddetails'][$i]["prospect"];
					$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
					$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
					$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
					$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
					$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
					$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
					
			

					$data[$i] = $row;
					$i++;
				}
				$arr = json_encode($data);
//				$arr = "{\"data\":" .json_encode($data). "}";
//			echo "{ rows: ".$arr." }";
//			echo $arr;
			return $arr;

			}

		/*Persu codings Start*/
		function get_leaddetails_for_branch_grid($branch,$selectedfield)
				{
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							 $sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to from vw_lead_aging_detail_report d, vw_lead_aging_additional_report_with_leadid b where d.leadid=b.leadid and b.user_branch='$branch' and b.$selectedfield='1' order by b.leadid";

						 }
						else
			 				 {   

								 $sql="SELECT d.leadid,d.createddate,d.customer_name,d.industry,d.assigned_to from vw_lead_aging_detail_report d, vw_lead_aging_additional_report_with_leadid b where d.leadid=b.leadid and b.user_branch='$branch' and b.$selectedfield='1' order by b.leadid";
						   }

       				//echo $sql; die;
					$jTableResult = array();
				
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();

					$data = array();
				
					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
						$row = array();
						$row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
						$row["date"] = $jTableResult['leaddetails'][$i]["createddate"];
						$row["customer"] = $jTableResult['leaddetails'][$i]["customer_name"];
						$row["industry"] = $jTableResult['leaddetails'][$i]["industry"];
						$row["assigned"] = $jTableResult['leaddetails'][$i]["assigned_to"];
					//	$row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
			

						$data[$i] = $row;
						$i++;
					}

					$arr = "{\"data\":" .json_encode($data). "}";
			//	echo "{ rows: ".$arr." }";
					return $arr;

					}
		/*Persu codings end*/

			public function get_all_leads_count()
			{
				  $sql="SELECT count(leadid) FROM leaddetails WHERE leadid  IN (SELECT leadid FROM leadproducts)";
				  $result = $this->db->query($sql);
				  $options = $result->result_array();
				  return $options[0]['count'];	
			}

			public function get_month_wise_lead_count($branch=null,$jc_from,$jc_to,$account_yr)
			
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total=0;
			    	if($branch=="All")
			    	{
			    		  $sql="SELECT * FROM fn_month_wise_all_jcbranch_lead_count('".$jc_from."','".$jc_to."','".$account_yr."') ORDER BY 1";	
			    		
			    	}
			    	else
			    	{
			    		$sql="SELECT * FROM fn_jcwise_allbranch_lead_count('".$jc_from."','".$jc_to."','".$account_yr."','".$branch."') ORDER BY 1";
			    	}
			        
					//echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $test = $result->result_array();
					$data = array();
					$retun_data = array();
					$datajson= array();
					$datajsoncum= array();
					$datajson_rows =array();
					$jason_arr= array();
					$jason_arrcum= array();
					$totals = array();
					

					$i=0;
				
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
			
							$jason_arr= array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["lead_status_id"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["status_name"];
							for($j1=$jc_from;$j1<=$jc_to; $j1++)
							{
								$row["jc".$j1.""] = $jTableResult['leaddetails'][$i]["jc".$j1.""];
								
								
						
							}
							for($j2=$jc_from;$j2<=$jc_to; $j2++)
							{
								$row["m_jc".$j2.""] = $jTableResult['leaddetails'][$i]["m_jc".$j2.""];
								
								
							}
							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}
							
							$data[$i] = $row;

							$i++;
						
							
					}

								foreach ($data  as $key => $value) {
								 for($j1=$jc_from;$j1<=$jc_to; $j1++)
									{

										if(!isset($totals["jc".$j1]))
										$totals["jc".$j1]=0;
										$totals["jc".$j1] = isset($data[$key]["jc".$j1]) ? ($data[$key]["jc".$j1]+$totals["jc".$j1]):0;
										if(!isset($totals["m_jc".$j1]))
										$totals["m_jc".$j1]=0;	
										$totals["m_jc".$j1] = isset($data[$key]["m_jc".$j1]) ? ($data[$key]["m_jc".$j1]+$totals["m_jc".$j1]):0;
									}

									
								}
								$totals['leadstatus']="total";
								$totals['leadstatusid']="9";
						


								array_push($data, $totals);
			
						
							//$jason_arr= array();
								$jason_arr_sid='{"text": "Lead Status Id", "dataField": "leadstatusid", "width": "70", "hidden": "true","filterable": "false"}';
								$jason_arr_sname='{"text": "Lead Status", "dataField": "leadstatus", "width": "230"}';
								
								
								array_push($datajson,$jason_arr_sid);
								array_push($datajson,$jason_arr_sname);
								

								array_push($datajsoncum,$jason_arr_sid);
								array_push($datajsoncum,$jason_arr_sname);
								//$jc_from=1;$jc_to=3;
							for($j=$jc_from;$j<=$jc_to; $j++)
								{
									
														
							    $jason_arr='{"text": "JC'.$j.'", "dataField": "jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';		
							     $jason_arrcum='{"text": "JC'.$j.'", "dataField": "m_jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';	
							    
								array_push($datajson,$jason_arr);
								array_push($datajsoncum,$jason_arrcum);
							
								}
						
								$datajson = str_replace("\\", '', json_encode($datajson));
								$datajson = str_replace('"{', '{', $datajson);
								$datajson = str_replace('}"', '}', $datajson);

								$datajsoncum = str_replace("\\", '', json_encode($datajsoncum));
								$datajsoncum = str_replace('"{', '{', $datajsoncum);
								$datajsoncum = str_replace('}"', '}', $datajsoncum);

								$datajson_rows = str_replace("\\", '', json_encode($data));
								$datajson_rows = str_replace('"{', '{', $datajson_rows);
								$datajson_rows = str_replace('}"', '}', $datajson_rows);


					$jasonarr = "'[{\"columns\":$datajson},{\"rows\":$datajson_rows}]'";
					$jasonarrcum = "'[{\"columns\":$datajsoncum},{\"rows\":$datajson_rows}]'";

					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['jasonarr']=$jasonarr;
					$retun_data['jasonarrcum']=$jasonarrcum;
				
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					$test_array =  array();
					$status_name = array();
					$i=0;
					foreach ($test as $row) 
					{
						//echo "check".$data[$i]['leadstatus'];
						$stname = $data[$i]['leadstatus'];
						$test_array[]= "[$row[jc1]]";
						$status_name[] = "[$stname]";
					$i++;	
					}
					$retun_data['test']=$test_array;
					$string_t= join($status_name, ',');
					$retun_data['status_name']=$status_name;

					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }

				public function get_month_wise_lead_qnty($branch=null,$jc_from,$jc_to,$account_yr)
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total_qnty=0;
			    	if($branch=="All")
			    	{
			    		   $sql="SELECT * FROM fn_jcwise_overall_lead_qnty('".$jc_from."','".$jc_to."','".$account_yr."') ORDER BY 1";	
			    		
			    	}
			    	else
			    	{
			    		$sql="SELECT * FROM fn_jcwise_branchwise_lead_qnty('".$jc_from."','".$jc_to."','".$account_yr."','".$branch."') ORDER BY 1";
			    	}
			        
			    	//echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();
					$data = array();
					$retun_data = array();

					$datajson= array();
					$datajsoncum= array();
					$datajson_rows =array();
					$jason_arr= array();
					$jason_arrcum= array();

					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    


							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["lead_status_id"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["status_name"];
							for($j=$jc_from;$j<=$jc_to; $j++)
							{
								$row["jc".$j.""] = $jTableResult['leaddetails'][$i]["jc".$j.""];
							
							}
							for($j2=$jc_from;$j2<=$jc_to; $j2++)
							{
								$row["m_jc".$j2.""] = $jTableResult['leaddetails'][$i]["m_jc".$j2.""];
							}

							$row["total"] = $jTableResult['leaddetails'][$i]["total_qnty"];

							if(@$jTableResult['leaddetails'][$i]["total_qnty"]>$max_total_qnty)
							{
								$max_total_qnty=$jTableResult['leaddetails'][$i]["total_qnty"];
							}
							$data[$i] = $row;
							$i++;
					}
						foreach ($data  as $key => $value) {
								 for($j1=$jc_from;$j1<=$jc_to; $j1++)
									{

										if(!isset($totals["jc".$j1]))
										$totals["jc".$j1]=0;
										$totals["jc".$j1] = isset($data[$key]["jc".$j1]) ? ($data[$key]["jc".$j1]+$totals["jc".$j1]):0;
										if(!isset($totals["m_jc".$j1]))
										$totals["m_jc".$j1]=0;	
										$totals["m_jc".$j1] = isset($data[$key]["m_jc".$j1]) ? ($data[$key]["m_jc".$j1]+$totals["m_jc".$j1]):0;
									}

									
								}
								$totals['leadstatus']="Total";
								$totals['leadstatusid']="9";
						


								array_push($data, $totals);

								$jason_arr_sid='{"text": "Lead Status Id", "dataField": "leadstatusid", "width": "70", "hidden": "true","filterable": "false"}';
								$jason_arr_sname='{"text": "Lead Status", "dataField": "leadstatus", "width": "230"}';
								array_push($datajson,$jason_arr_sid);
								array_push($datajson,$jason_arr_sname);
								array_push($datajsoncum,$jason_arr_sid);
								array_push($datajsoncum,$jason_arr_sname);
								//$jc_from=1;$jc_to=3;
							for($j=$jc_from;$j<=$jc_to; $j++)
								{
								   $jason_arr='{"text": "JC'.$j.'", "dataField": "jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "f2", "repl":"ok"}';		
							       $jason_arrcum='{"text": "JC'.$j.'", "dataField": "m_jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "f2", "repl":"ok"}';	
							    array_push($datajson,$jason_arr);
								array_push($datajsoncum,$jason_arrcum);
								}

					$datajson = str_replace("\\", '', json_encode($datajson));
					$datajson = str_replace('"{', '{', $datajson);
					$datajson = str_replace('}"', '}', $datajson);

					$datajsoncum = str_replace("\\", '', json_encode($datajsoncum));
					$datajsoncum = str_replace('"{', '{', $datajsoncum);
					$datajsoncum = str_replace('}"', '}', $datajsoncum);
				 
					$datajson_rows = str_replace("\\", '', json_encode($data));
					$datajson_rows = str_replace('"{', '{', $datajson_rows);
					$datajson_rows = str_replace('}"', '}', $datajson_rows);

					$jasonarr = "'[{\"columns\":$datajson},{\"rows\":$datajson_rows}]'";
					$jasonarrcum = "'[{\"columns\":$datajsoncum},{\"rows\":$datajson_rows}]'";
					$arr = "{\"data_qnty\":" .json_encode($data). "}";
					$arrct = json_encode($data);

					//echo"max_total ".$max_total; die;
					$arr_qnty = "{\"data_qnty\":" .json_encode($data). "}";
					$arrct_qnty = json_encode($data);

					$retun_data['jasonarr_qnty']=$jasonarr;
					$retun_data['jasonarrcum_qnty']=$jasonarrcum;
					$retun_data['arrct_qnty']=$arrct_qnty;

					$retun_data['arr_qnty']=$jasonarr;
					$retun_data['arrct_qnty']=$arrct_qnty;
					$retun_data['maxVal_qnty']=$max_total_qnty;
					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }
			    
			    public function get_month_wise_ownbranch_count($jc_from,$jc_to,$account_yr,$branch)
			    {
			    	$max_total=0;
			    	//echo"branch ".$branch."<br>";
			    	
				  $sql="SELECT * FROM fn_jcwise_ownbranch_lead_count('".$jc_from."','".$jc_to."','".$account_yr."','".$branch."') ORDER BY 1";
			    	
			        
			    	//	echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();
					$data = array();
					$retun_data = array();

					$datajson= array();
					$datajsoncum= array();
					$datajson_rows =array();
					$jason_arr= array();
					$jason_arrcum= array();
					$totals = array();

					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["lead_status_id"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["status_name"];
							for($j1=$jc_from;$j1<=$jc_to; $j1++)
							{
								$row["jc".$j1.""] = $jTableResult['leaddetails'][$i]["jc".$j1.""];
						
							}
							for($j2=$jc_from;$j2<=$jc_to; $j2++)
							{
								$row["m_jc".$j2.""] = $jTableResult['leaddetails'][$i]["m_jc".$j2.""];
								
							}

							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}

							$data[$i] = $row;
							$i++;
					}

							foreach ($data  as $key => $value) 
							{
							 for($j1=$jc_from;$j1<=$jc_to; $j1++)
								{

									if(!isset($totals["jc".$j1]))
									$totals["jc".$j1]=0;
									$totals["jc".$j1] = isset($data[$key]["jc".$j1]) ? ($data[$key]["jc".$j1]+$totals["jc".$j1]):0;
									if(!isset($totals["m_jc".$j1]))
									$totals["m_jc".$j1]=0;	
									$totals["m_jc".$j1] = isset($data[$key]["m_jc".$j1]) ? ($data[$key]["m_jc".$j1]+$totals["m_jc".$j1]):0;
								}
											
							}
							$totals['leadstatus']="total";
							$totals['leadstatusid']="9";
						    array_push($data, $totals);
			
						
							//$jason_arr= array();
								$jason_arr_sid='{"text": "Lead Status Id", "dataField": "leadstatusid", "width": "70", "hidden": "true","filterable": "false"}';
								$jason_arr_sname='{"text": "Lead Status", "dataField": "leadstatus", "width": "230"}';
								
								
								array_push($datajson,$jason_arr_sid);
								array_push($datajson,$jason_arr_sname);
								

								array_push($datajsoncum,$jason_arr_sid);
								array_push($datajsoncum,$jason_arr_sname);
								//$jc_from=1;$jc_to=3;
							for($j=$jc_from;$j<=$jc_to; $j++)
								{
									
														
							    $jason_arr='{"text": "JC'.$j.'", "dataField": "jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';		
							     $jason_arrcum='{"text": "JC'.$j.'", "dataField": "m_jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';	
							    
								array_push($datajson,$jason_arr);
								array_push($datajsoncum,$jason_arrcum);
							
								}

					$datajson = str_replace("\\", '', json_encode($datajson));
					$datajson = str_replace('"{', '{', $datajson);
					$datajson = str_replace('}"', '}', $datajson);

					$datajsoncum = str_replace("\\", '', json_encode($datajsoncum));
					$datajsoncum = str_replace('"{', '{', $datajsoncum);
					$datajsoncum = str_replace('}"', '}', $datajsoncum);

					$datajson_rows = str_replace("\\", '', json_encode($data));
					$datajson_rows = str_replace('"{', '{', $datajson_rows);
					$datajson_rows = str_replace('}"', '}', $datajson_rows);
					
					
					$jasonarr = "'[{\"columns\":$datajson},{\"rows\":$datajson_rows}]'";
					$jasonarrcum = "'[{\"columns\":$datajsoncum},{\"rows\":$datajson_rows}]'";


					$retun_data['jasonarr']=$jasonarr;
					$retun_data['jasonarrcum']=$jasonarrcum;

					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;

			    }
			    public function get_month_wise_ownbranch_qnty($jc_from,$jc_to,$account_yr,$branch)
			    {
			    	$max_total_qnty=0;
			    	//echo"branch ".$branch."<br>";
			    	
			        $sql="SELECT * FROM fn_jcwise_ownbranch_lead_qnty('".$jc_from."','".$jc_to."','".$account_yr."','".$branch."') ORDER BY 1";
			    	
			        
			    //	echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $result->result_array();
					$data = array();
					$retun_data = array();

					$datajson= array();
					$datajsoncum= array();
					$datajson_rows =array();
					$jason_arr= array();
					$jason_arrcum= array();


					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["lead_status_id"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["status_name"];
							
							

							for($j=$jc_from;$j<=$jc_to; $j++)
							{
								$row["jc".$j.""] = $jTableResult['leaddetails'][$i]["jc".$j.""];
							
							}
							for($j2=$jc_from;$j2<=$jc_to; $j2++)
							{
								$row["m_jc".$j2.""] = $jTableResult['leaddetails'][$i]["m_jc".$j2.""];
							}

							
							if(@$jTableResult['leaddetails'][$i]["total_qnty"]>$max_total_qnty)
							{
								$max_total_qnty=$jTableResult['leaddetails'][$i]["total_qnty"];
							}
							$data[$i] = $row;
							$i++;
					}

					foreach ($data  as $key => $value) {
								 for($j1=$jc_from;$j1<=$jc_to; $j1++)
									{

										if(!isset($totals["jc".$j1]))
										$totals["jc".$j1]=0;
										$totals["jc".$j1] = isset($data[$key]["jc".$j1]) ? ($data[$key]["jc".$j1]+$totals["jc".$j1]):0;
										if(!isset($totals["m_jc".$j1]))
										$totals["m_jc".$j1]=0;	
										$totals["m_jc".$j1] = isset($data[$key]["m_jc".$j1]) ? ($data[$key]["m_jc".$j1]+$totals["m_jc".$j1]):0;
									}

									
								}
								$totals['leadstatus']="Total";
								$totals['leadstatusid']="9";
						


								array_push($data, $totals);

								$jason_arr_sid='{"text": "Lead Status Id", "dataField": "leadstatusid", "width": "70", "hidden": "true","filterable": "false"}';
								$jason_arr_sname='{"text": "Lead Status", "dataField": "leadstatus", "width": "230"}';
								array_push($datajson,$jason_arr_sid);
								array_push($datajson,$jason_arr_sname);
								array_push($datajsoncum,$jason_arr_sid);
								array_push($datajsoncum,$jason_arr_sname);
								//$jc_from=1;$jc_to=3;
							for($j=$jc_from;$j<=$jc_to; $j++)
								{
								   $jason_arr='{"text": "JC'.$j.'", "dataField": "jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "f2", "repl":"ok"}';		
							       $jason_arrcum='{"text": "JC'.$j.'", "dataField": "m_jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "f2", "repl":"ok"}';	
							    array_push($datajson,$jason_arr);
								array_push($datajsoncum,$jason_arrcum);
								}

					$datajson = str_replace("\\", '', json_encode($datajson));
					$datajson = str_replace('"{', '{', $datajson);
					$datajson = str_replace('}"', '}', $datajson);

					$datajsoncum = str_replace("\\", '', json_encode($datajsoncum));
					$datajsoncum = str_replace('"{', '{', $datajsoncum);
					$datajsoncum = str_replace('}"', '}', $datajsoncum);
				 
					$datajson_rows = str_replace("\\", '', json_encode($data));
					$datajson_rows = str_replace('"{', '{', $datajson_rows);
					$datajson_rows = str_replace('}"', '}', $datajson_rows);

					$jasonarr = "'[{\"columns\":$datajson},{\"rows\":$datajson_rows}]'";
					$jasonarrcum = "'[{\"columns\":$datajsoncum},{\"rows\":$datajson_rows}]'";

					$arr_qnty = "{\"data_qnty\":" .json_encode($data). "}";
					$arrct_qnty = json_encode($data);

					$retun_data['jasonarr_qnty']=$jasonarr;
					$retun_data['jasonarrcum_qnty']=$jasonarrcum;
					$retun_data['arrct_qnty']=$arrct_qnty;

					$retun_data['arr_qnty']=$arr_qnty;
					$retun_data['arrct_qnty']=$arrct_qnty;
					$retun_data['maxVal_qnty']=$max_total_qnty;
					return $retun_data;

			    }

			    public function get_month_wise_lead_count_for_chart($branch=null,$jc_from,$jc_to,$account_yr)
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total=0;
			    	if($branch=="All")
			    	{
			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	else
			    	{

			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
								and upper(ld.USER_BRANCH)='".$branch."'
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	
			    	
			        
			    //	echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $test = $result->result_array();
					$data = array();
					$retun_data = array();

					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
							$row["jcperiod"] = $jTableResult['leaddetails'][$i]["jcperiod"];
							$row["prospect"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_assesment"] = $jTableResult['leaddetails'][$i]["credit_assesment"];
							$row["sample_and_trials"] = $jTableResult['leaddetails'][$i]["sample_and_trials"];
							$row["enquiry_offer_nego"] = $jTableResult['leaddetails'][$i]["enquiry_offer_nego"];
							$row["managing_and_implement"] = $jTableResult['leaddetails'][$i]["managing_and_implement"];
							$row["expand_building"] = $jTableResult['leaddetails'][$i]["expand_building"];
							$row["closed"] = $jTableResult['leaddetails'][$i]["closed"];
						
							//$row["total"] = $jTableResult['leaddetails'][$i]["cnt"];

							//$row["total"] = $jTableResult['leaddetails'][$i]["m_apr"]+ $jTableResult['leaddetails'][$i]["m_may"]+$jTableResult['leaddetails'][$i]["m_jun"]+ $jTableResult['leaddetails'][$i]["m_jul"]+$jTableResult['leaddetails'][$i]["m_aug"]+$jTableResult['leaddetails'][$i]["m_sep"]+$jTableResult['leaddetails'][$i]["m_oct"]+$jTableResult['leaddetails'][$i]["m_nov"]+$jTableResult['leaddetails'][$i]["m_dec"]+$jTableResult['leaddetails'][$i]["m_jan"]+$jTableResult['leaddetails'][$i]["m_feb"]+$jTableResult['leaddetails'][$i]["m_feb"];	
						
			

							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}
							$data[$i] = $row;
							$i++;
					}
					//echo"max_total ".$max_total; die;
					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					/*$test_array =  array();
					$status_name = array();
					$i=0;
					foreach ($test as $row) 
					{
						//echo "check".$data[$i]['leadstatus'];
						$stname = $data[$i]['leadstatus'];
						$test_array[]= "[$row[jc1]]";
						$status_name[] = "[$stname]";
					$i++;	
					}
					$retun_data['test']=$test_array;
					
					$string_t= join($status_name, ',');
					
					$retun_data['status_name']=$status_name;*/
					//$retun_data['status_name']=$string_t;
					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }
			    public function get_month_wise_ownbranch_count_chart($branch=null,$jc_from,$jc_to,$account_yr)
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total=0;
			    	if($branch=="All")
			    	{
			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	else
			    	{

			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
								and upper(ld.USER_BRANCH)='".$branch."'
								AND ld.CREATED_USER IN 
								(
                            	 	SELECT 	HEADER_USER_ID FROM
	                          	 	vw_web_user_login WHERE UPPER (LOCATION_USER) ='".$branch."'
                           		)
       							AND ld.ASSIGNLEADCHK  IN 
       							(
                             		SELECT 	HEADER_USER_ID FROM
	                           vw_web_user_login WHERE UPPER (LOCATION_USER) ='".$branch."'
                           		)
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	
			    	
			        
			    	//echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $test = $result->result_array();
					$data = array();
					$retun_data = array();

					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
							$row["jcperiod"] = $jTableResult['leaddetails'][$i]["jcperiod"];
							$row["prospect"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_assesment"] = $jTableResult['leaddetails'][$i]["credit_assesment"];
							$row["sample_and_trials"] = $jTableResult['leaddetails'][$i]["sample_and_trials"];
							$row["enquiry_offer_nego"] = $jTableResult['leaddetails'][$i]["enquiry_offer_nego"];
							$row["managing_and_implement"] = $jTableResult['leaddetails'][$i]["managing_and_implement"];
							$row["expand_building"] = $jTableResult['leaddetails'][$i]["expand_building"];
							$row["closed"] = $jTableResult['leaddetails'][$i]["closed"];
						
							//$row["total"] = $jTableResult['leaddetails'][$i]["cnt"];

							//$row["total"] = $jTableResult['leaddetails'][$i]["m_apr"]+ $jTableResult['leaddetails'][$i]["m_may"]+$jTableResult['leaddetails'][$i]["m_jun"]+ $jTableResult['leaddetails'][$i]["m_jul"]+$jTableResult['leaddetails'][$i]["m_aug"]+$jTableResult['leaddetails'][$i]["m_sep"]+$jTableResult['leaddetails'][$i]["m_oct"]+$jTableResult['leaddetails'][$i]["m_nov"]+$jTableResult['leaddetails'][$i]["m_dec"]+$jTableResult['leaddetails'][$i]["m_jan"]+$jTableResult['leaddetails'][$i]["m_feb"]+$jTableResult['leaddetails'][$i]["m_feb"];	
						
			

							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}
							$data[$i] = $row;
							$i++;
					}
					//echo"max_total ".$max_total; die;
					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					/*$test_array =  array();
					$status_name = array();
					$i=0;
					foreach ($test as $row) 
					{
						//echo "check".$data[$i]['leadstatus'];
						$stname = $data[$i]['leadstatus'];
						$test_array[]= "[$row[jc1]]";
						$status_name[] = "[$stname]";
					$i++;	
					}
					$retun_data['test']=$test_array;
					
					$string_t= join($status_name, ',');
					
					$retun_data['status_name']=$status_name;*/
					//$retun_data['status_name']=$string_t;
					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }

			    

			    public function get_month_wise_lead_quantity($branch=null,$jc_from,$jc_to,$account_yr)
			
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total=0;
			    	if($branch=="All")
			    	{
			    		  $sql="SELECT * FROM fn_month_wise_all_jcbranch_lead_count('".$jc_from."','".$jc_to."','".$account_yr."') ORDER BY 1";	
			    		
			    	}
			    	else
			    	{
			    		$sql="SELECT * FROM fn_jcwise_allbranch_lead_count('".$jc_from."','".$jc_to."','".$account_yr."','".$branch."') ORDER BY 1";
			    	}
			        
					//echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $test = $result->result_array();
					$data = array();
					$retun_data = array();
					$datajson= array();
					$datajsoncum= array();
					$datajson_rows =array();
					$jason_arr= array();
					$jason_arrcum= array();
					$totals = array();
					

					$i=0;
				
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
			
							$jason_arr= array();
							$row["leadstatusid"] = $jTableResult['leaddetails'][$i]["lead_status_id"];
							$row["leadstatus"] = $jTableResult['leaddetails'][$i]["status_name"];
							for($j1=$jc_from;$j1<=$jc_to; $j1++)
							{
								$row["jc".$j1.""] = $jTableResult['leaddetails'][$i]["jc".$j1.""];
								
								
						
							}
							for($j2=$jc_from;$j2<=$jc_to; $j2++)
							{
								$row["m_jc".$j2.""] = $jTableResult['leaddetails'][$i]["m_jc".$j2.""];
								
								
							}
							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}
							
							$data[$i] = $row;

							$i++;
						
							
					}

								foreach ($data  as $key => $value) {
								 for($j1=$jc_from;$j1<=$jc_to; $j1++)
									{

										if(!isset($totals["jc".$j1]))
										$totals["jc".$j1]=0;
										$totals["jc".$j1] = isset($data[$key]["jc".$j1]) ? ($data[$key]["jc".$j1]+$totals["jc".$j1]):0;
										if(!isset($totals["m_jc".$j1]))
										$totals["m_jc".$j1]=0;	
										$totals["m_jc".$j1] = isset($data[$key]["m_jc".$j1]) ? ($data[$key]["m_jc".$j1]+$totals["m_jc".$j1]):0;
									}

									
								}
								$totals['leadstatus']="total";
								$totals['leadstatusid']="9";
						


								array_push($data, $totals);
			
						
							//$jason_arr= array();
								$jason_arr_sid='{"text": "Lead Status Id", "dataField": "leadstatusid", "width": "70", "hidden": "true","filterable": "false"}';
								$jason_arr_sname='{"text": "Lead Status", "dataField": "leadstatus", "width": "230"}';
								
								
								array_push($datajson,$jason_arr_sid);
								array_push($datajson,$jason_arr_sname);
								

								array_push($datajsoncum,$jason_arr_sid);
								array_push($datajsoncum,$jason_arr_sname);
								//$jc_from=1;$jc_to=3;
							for($j=$jc_from;$j<=$jc_to; $j++)
								{
									
														
							    $jason_arr='{"text": "JC'.$j.'", "dataField": "jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';		
							     $jason_arrcum='{"text": "JC'.$j.'", "dataField": "m_jc'.$j.'", "width": "70","cellsalign": "right", "cellsformat": "n", "repl":"ok"}';	
							    
								array_push($datajson,$jason_arr);
								array_push($datajsoncum,$jason_arrcum);
							
								}
						
								$datajson = str_replace("\\", '', json_encode($datajson));
								$datajson = str_replace('"{', '{', $datajson);
								$datajson = str_replace('}"', '}', $datajson);

								$datajsoncum = str_replace("\\", '', json_encode($datajsoncum));
								$datajsoncum = str_replace('"{', '{', $datajsoncum);
								$datajsoncum = str_replace('}"', '}', $datajsoncum);

								$datajson_rows = str_replace("\\", '', json_encode($data));
								$datajson_rows = str_replace('"{', '{', $datajson_rows);
								$datajson_rows = str_replace('}"', '}', $datajson_rows);


					$jasonarr = "'[{\"columns\":$datajson},{\"rows\":$datajson_rows}]'";
					$jasonarrcum = "'[{\"columns\":$datajsoncum},{\"rows\":$datajson_rows}]'";

					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['jasonarr']=$jasonarr;
					$retun_data['jasonarrcum']=$jasonarrcum;
				
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					$test_array =  array();
					$status_name = array();
					$i=0;
					foreach ($test as $row) 
					{
						//echo "check".$data[$i]['leadstatus'];
						$stname = $data[$i]['leadstatus'];
						$test_array[]= "[$row[jc1]]";
						$status_name[] = "[$stname]";
					$i++;	
					}
					$retun_data['test']=$test_array;
					$string_t= join($status_name, ',');
					$retun_data['status_name']=$status_name;

					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }


			    public function get_month_wise_lead_quantityfor_chart($branch=null,$jc_from,$jc_to,$account_yr)
			    {
			    	//echo"branch ".$branch."<br>";
			    	$max_total=0;
			    	if($branch=="All")
			    	{
			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	else
			    	{

			    		$sql="SELECT 	'JC'|| jcperiod as jcperiod
						,sum (prospect) as prospect
						,sum (met_the_customer) as met_the_customer
						,sum (credit_assesment) as credit_assesment
						,sum (sample_and_trials) as sample_and_trials
						,sum (enquiry_offer_nego) as enquiry_offer_nego
						,sum (managing_and_implement) as managing_and_implement
						,sum (expand_building) as expand_building
						,sum (closed) as closed
						FROM 
						(
						SELECT 
						 lm.jcode as  jcperiod,
						      lm.fin_yr,
						count(lm.leadid) as cnt, 
						case when lm.leadstatus=1 THEN count(lm.leadid) ELSE 0 END as prospect,
						case when lm.leadstatus=2 THEN count(lm.leadid) ELSE 0 END as met_the_customer,
						case when lm.leadstatus=3 THEN count(lm.leadid) ELSE 0 END as credit_assesment,
						case when lm.leadstatus=4 THEN count(lm.leadid) ELSE 0 END as sample_and_trials,
						case when lm.leadstatus=5 THEN count(lm.leadid) ELSE 0 END as enquiry_offer_nego,
						case when lm.leadstatus=6 THEN count(lm.leadid) ELSE 0 END as managing_and_implement,
						case when lm.leadstatus=7 THEN count(lm.leadid) ELSE 0 END as expand_building,
						case when lm.leadstatus=8 THEN count(lm.leadid) ELSE 0 END as closed

						FROM 
						(
						SELECT ld.*,ls.leadstatus as lead_status_name FROM 
						(
						select distinct   v.*  ,
						CASE WHEN (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to then    jc_code ELSE  0 END AS JCODE
						from (
						SELECT * , get_acc_yr(createddate::DATE) as fin_yr FROM leaddetails 
						) v 
						, jc_calendar_dtl g 
						where g.acc_yr=v.fin_yr
						and   jc_code BETWEEN '".$jc_from."' and '".$jc_to."' and  acc_yr='".$account_yr."'
						and  (createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to
						) ld,
						 leadproducts p,
								leadstatus ls
						WHERE 
								ld.leadid=p.leadid
								and ld.leadstatus = ls.leadstatusid
								and upper(ld.USER_BRANCH)='".$branch."'
						)lm
						GROUP BY 
									lm.leadstatus ,
						      lm.jcode,
						      lm.fin_yr
						ORDER BY lm.jcode 
						) k 
						GROUP BY jcperiod
						ORDER BY jcperiod::INTEGER";
			    	}
			    	
			    	
			        
			    //	echo $sql; die;
			        $jTableResult = array();
					$result = $this->db->query($sql);
					$jTableResult['leaddetails'] = $test = $result->result_array();
					$data = array();
					$retun_data = array();

					$i=0;
					while($i < count($jTableResult['leaddetails']))
					{    
							$row = array();
							$row["jcperiod"] = $jTableResult['leaddetails'][$i]["jcperiod"];
							$row["prospect"] = $jTableResult['leaddetails'][$i]["prospect"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_assesment"] = $jTableResult['leaddetails'][$i]["credit_assesment"];
							$row["sample_and_trials"] = $jTableResult['leaddetails'][$i]["sample_and_trials"];
							$row["enquiry_offer_nego"] = $jTableResult['leaddetails'][$i]["enquiry_offer_nego"];
							$row["managing_and_implement"] = $jTableResult['leaddetails'][$i]["managing_and_implement"];
							$row["expand_building"] = $jTableResult['leaddetails'][$i]["expand_building"];
							$row["closed"] = $jTableResult['leaddetails'][$i]["closed"];
						
							//$row["total"] = $jTableResult['leaddetails'][$i]["cnt"];

							//$row["total"] = $jTableResult['leaddetails'][$i]["m_apr"]+ $jTableResult['leaddetails'][$i]["m_may"]+$jTableResult['leaddetails'][$i]["m_jun"]+ $jTableResult['leaddetails'][$i]["m_jul"]+$jTableResult['leaddetails'][$i]["m_aug"]+$jTableResult['leaddetails'][$i]["m_sep"]+$jTableResult['leaddetails'][$i]["m_oct"]+$jTableResult['leaddetails'][$i]["m_nov"]+$jTableResult['leaddetails'][$i]["m_dec"]+$jTableResult['leaddetails'][$i]["m_jan"]+$jTableResult['leaddetails'][$i]["m_feb"]+$jTableResult['leaddetails'][$i]["m_feb"];	
						
			

							if(@$jTableResult['leaddetails'][$i]["total_count"]>$max_total)
							{
								$max_total=$jTableResult['leaddetails'][$i]["total_count"];
							}
							$data[$i] = $row;
							$i++;
					}
					//echo"max_total ".$max_total; die;
					$arr = "{\"data\":" .json_encode($data). "}";
					$arrct = json_encode($data);
					$retun_data['arr']=$arr;
					$retun_data['arrct']=$arrct;
					$retun_data['maxVal']=$max_total;
					/*$test_array =  array();
					$status_name = array();
					$i=0;
					foreach ($test as $row) 
					{
						//echo "check".$data[$i]['leadstatus'];
						$stname = $data[$i]['leadstatus'];
						$test_array[]= "[$row[jc1]]";
						$status_name[] = "[$stname]";
					$i++;	
					}
					$retun_data['test']=$test_array;
					
					$string_t= join($status_name, ',');
					
					$retun_data['status_name']=$status_name;*/
					//$retun_data['status_name']=$string_t;
					//echo"<pre>";print_r($retun_data);echo"</pre>"; die;
					return $retun_data;
			    }

			function get_lead_quantity_dashboard()
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];

						if ($reportingto=='')
						{
					 $sql="	SELECT
									user_branch,
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_quantity_report
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				   }
			else	
				    {

 							$sql="SELECT 
										user_branch,
										sum(prospect) as prospects,
										sum(met_the_customer) as met_the_customer,
										sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship

								FROM 
										vw_lead_toal_quantity_report
								WHERE assign_to_id in (".$get_assign_to_user_id.")
								GROUP BY
									user_branch
								ORDER BY
									user_branch";
			     				
				     }
                             
                              //	 echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
									

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_lead_quantity_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week)
			{
				//echo"branch ".urldecode($branch);  echo" user id ".$account_yr;echo" jc_code ".$jc_code;echo" jc_week ".$jc_week; die;
				
				$branch =urldecode($branch);
						$sql1="SELECT  week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE   acc_yr='".$account_yr."' AND jc_cal_line_id in (SELECT line_id from	 jc_calendar_dtl WHERE acc_yr='".$account_yr."' AND jc_code=".$jc_code.") AND week_id=".$jc_week;
							//	echo $sql1;
						$result1 = $this->db->query($sql1);
						$jc_week_date = $result1->result_array();
					//	print_r($jc_week_date);	
						 $jc_week_from=$jc_week_date[0]['week_period_from'];
						 $jc_week_to=$jc_week_date[0]['week_period_to'];
						//die;

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if($branch=="All")
							{
 								$sql="	SELECT 
												user_branch,
												sum(prospect) as prospects,
												sum(met_the_customer) as met_the_customer,
												sum(credit_sssessment) as credit_sssessment,
												sum(sample_trails_formalities) as sample_trails_formalities,
												sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
												sum(managing_and_implementation) as managing_and_implementation,
												sum(expanding_and_build_relationship) as expanding_and_build_relationship

										FROM 
												vw_lead_toal_quantity_report
										WHERE 	
											createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 

											
										GROUP BY
											user_branch
										ORDER BY
											user_branch ";
							}
							else
							{
								 $sql="SELECT 
								 	user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_quantity_report
							WHERE 	
								createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
								AND user_branch='".$branch."'

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							}
						
				   }
			else	
				    {

 							if($branch=="All")
							{
 								$sql="SELECT  
												user_branch,
												sum(prospect) as prospects,
												sum(met_the_customer) as met_the_customer,
												sum(credit_sssessment) as credit_sssessment,
												sum(sample_trails_formalities) as sample_trails_formalities,
												sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
												sum(managing_and_implementation) as managing_and_implementation,
												sum(expanding_and_build_relationship) as expanding_and_build_relationship

										FROM 
												vw_lead_toal_quantity_report
										WHERE 	
											createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
											AND  assign_to_id in (".$get_assign_to_user_id.")	
										GROUP BY
											user_branch
										ORDER BY
											user_branch ";
							}
							else
							{
								 $sql="SELECT 
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_quantity_report
							WHERE 	
								createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
								AND user_branch='".$branch."'
								AND  assign_to_id in (".$get_assign_to_user_id.")										
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							}
			     				
				     }
                             
                              	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							if($i==0)
							{
								$row["total"]="";
							}
							else
							{
								$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							}
							
									

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_lead_countfor_dashboard()
			{
				//echo"branch ".$branch;  	echo" user id ".$user_id; die;
						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];

						if ($reportingto=='')
						{
					 $sql="	SELECT
									user_branch,
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_leadcount_report
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
				   }
			else	
				    {

 							$sql="SELECT 
										user_branch,
										sum(prospect) as prospects,
										sum(met_the_customer) as met_the_customer,
										sum(credit_sssessment) as credit_sssessment,
										sum(sample_trails_formalities) as sample_trails_formalities,
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
										sum(managing_and_implementation) as managing_and_implementation,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship

								FROM 
										vw_lead_toal_leadcount_report
								WHERE assign_to_id in (".$get_assign_to_user_id.")
								GROUP BY
									user_branch
								ORDER BY
									user_branch";
			     				
				     }
                             
                              //	 echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
									

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}

			function get_lead_countfor_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week)
			{
				//echo"branch ".urldecode($branch);  echo" user id ".$account_yr;echo" jc_code ".$jc_code;echo" jc_week ".$jc_week; die;
				
				$branch =urldecode($branch);
						$sql1="SELECT  week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE   acc_yr='".$account_yr."' AND jc_cal_line_id in (SELECT line_id from	 jc_calendar_dtl WHERE acc_yr='".$account_yr."' AND jc_code=".$jc_code.") AND week_id=".$jc_week;
							//	echo $sql1;
						$result1 = $this->db->query($sql1);
						$jc_week_date = $result1->result_array();
					//	print_r($jc_week_date);	
						 $jc_week_from=$jc_week_date[0]['week_period_from'];
						 $jc_week_to=$jc_week_date[0]['week_period_to'];
						//die;

						$reportingto=$this->session->userdata['reportingto'];
						$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
						if ($reportingto=='')
						{
							if($branch=="All")
							{
 								$sql="	SELECT 
												user_branch,
												sum(prospect) as prospects,
												sum(met_the_customer) as met_the_customer,
												sum(credit_sssessment) as credit_sssessment,
												sum(sample_trails_formalities) as sample_trails_formalities,
												sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
												sum(managing_and_implementation) as managing_and_implementation,
												sum(expanding_and_build_relationship) as expanding_and_build_relationship

										FROM 
												vw_lead_toal_leadcount_report
										WHERE 	
											createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 

											
										GROUP BY
											user_branch
										ORDER BY
											user_branch ";
							}
							else
							{
								 $sql="SELECT 
								 	user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_leadcount_report
							WHERE 	
								createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
								AND user_branch='".$branch."'

							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							}
						
				   }
			else	
				    {

 							if($branch=="All")
							{
 								$sql="SELECT  
												user_branch,
												sum(prospect) as prospects,
												sum(met_the_customer) as met_the_customer,
												sum(credit_sssessment) as credit_sssessment,
												sum(sample_trails_formalities) as sample_trails_formalities,
												sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
												sum(managing_and_implementation) as managing_and_implementation,
												sum(expanding_and_build_relationship) as expanding_and_build_relationship

										FROM 
												vw_lead_toal_leadcount_report
										WHERE 	
											createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
											AND  assign_to_id in (".$get_assign_to_user_id.")	
										GROUP BY
											user_branch
										ORDER BY
											user_branch ";
							}
							else
							{
								 $sql="SELECT 
									user_branch,
									
									sum(prospect) as prospects,
									sum(met_the_customer) as met_the_customer,
									sum(credit_sssessment) as credit_sssessment,
									sum(sample_trails_formalities) as sample_trails_formalities,
									sum(enquiry_offer_negotiation) as enquiry_offer_negotiation,
									sum(managing_and_implementation) as managing_and_implementation,
									sum(expanding_and_build_relationship) as expanding_and_build_relationship

							FROM 
									vw_lead_toal_leadcount_report
							WHERE 	
								createddate::DATE  between '".$jc_week_from."' and '".$jc_week_to."' 
								AND user_branch='".$branch."'
								AND  assign_to_id in (".$get_assign_to_user_id.")										
							GROUP BY
								user_branch
							ORDER BY
								user_branch";
							}
			     				
				     }
                             
                              	// echo $sql; die;
						$jTableResult = array();
						//$sql='select  * from  vw_lead_aging_report';
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row["user_branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
							/*$row["new_leads"] = $jTableResult['leaddetails'][$i]["new_leads"];*/
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							if($i==0)
							{
								$row["total"]="";
							}
							else
							{
								$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];
							}
							
									

							$data[$i] = $row;
							$i++;
						}
						$arr = "{\"data\":" .json_encode($data). "}";
				//	echo "{ rows: ".$arr." }";
					return $arr;
			}





}
?>

