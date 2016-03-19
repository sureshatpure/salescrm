<?php

class Loginlogout_model extends CI_Model
{
	
	
			function __construct()
			{
				$this->load->library('form_validation');
				$this->load->helper('url');
				$this->load->database();
				$this->load->helper('language');
				$this->load->library('session');
				
			}

			public function get_usertime_spent()
			{

				$sql = "SELECT
							*
							 FROM 
							(
							SELECT
							        usr.empcode,
							                lg.user_name,
							                upper(usr.location_user) as branch,
							              lg.login_time,
							                lg.logout_time,
							                lg.logout_time - login_time as time_spent,
							             lg.login_time::DATE as Date ,
							             to_char( login_time,'MON'),
							      get_acc_yr(lg.login_time::DATE) as fin_yr,
							      jl.jc_code
							FROM
							            lead_login_activity lg,
							      vw_web_user_login usr,
							      jc_calendar_dtl jl

							WHERE
							     lg.user_id = usr.header_user_id 
							    AND lg.user_id NOT IN (195,42,612,319,565,59,649,64,241,470,661) AND 
							     lg.login_time::DATE BETWEEN jc_period_from AND jc_period_to AND
							    lg.login_time::DATE!= current_date::DATE

							)l 
							 
							  ORDER BY login_time::Date  desc ";   
							 // WHERE login_time::DATE  >= '2015-05-30' and login_time::DATE <= '2015-07-21'
    
				     // echo $sql; die;
				         $result = $this->db->query($sql);
					     $productdetails = $result->result_array();
				
				        $jTableResult['leaddetails']=$productdetails;
				        $data = array();
				        $i = 0;
				        
				        while ($i < count($jTableResult['leaddetails'])) {
				            $row = array();
				            $row["empcode"] = $jTableResult['leaddetails'][$i]["empcode"];
				            $row["user_name"] = $jTableResult['leaddetails'][$i]["user_name"];
				            $row["branch"] = $jTableResult['leaddetails'][$i]["branch"];
				            $row["login_time"] = $jTableResult['leaddetails'][$i]["login_time"];
				            $row["date"] = $jTableResult['leaddetails'][$i]["date"];
				            $row["month"] = $jTableResult['leaddetails'][$i]["to_char"];

				            $row["logout_time"] = $jTableResult['leaddetails'][$i]["logout_time"];
				            $row["time_spent"] = $jTableResult['leaddetails'][$i]["time_spent"];

				            $row["fin_yr"] = $jTableResult['leaddetails'][$i]["fin_yr"];
				            $row["jcode"] = "JC".$jTableResult['leaddetails'][$i]["jc_code"];

				            $data[$i] = $row;
				            $i++;
				        }
				        $arr = "{\"data\":" . json_encode($data) . "}";
				        //  echo "{ rows: ".$arr." }";
				        return $arr;
			}

			function get_branches()
                {
                
                $reporting_to = $this->session->userdata['reportingto'];
                $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

                if (@$reporting_to=="")
                {
                                    $sql="SELECT DISTINCT a.branch FROM ( SELECT    header_user_id, UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE LENGTH (location_user) > 2) a ORDER BY a.branch";
                } else
                {

                                 
                                //$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                                $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                                

                                $sql="SELECT DISTINCT a.branch FROM ( SELECT    header_user_id, UPPER(trim(location_user)) AS branch FROM vw_web_user_login WHERE header_user_id IN (".$get_assign_to_user_id.") and LENGTH (location_user) > 2) a ORDER BY a.branch";
        
                }
                //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
    /*
                    $all_array = array('branch'=>'All');
                    array_unshift($options,$all_array);*/
                    $arr =  json_encode($options); 
                
        
    //echo $arr; die;
            return $arr;

                }



}
?>

