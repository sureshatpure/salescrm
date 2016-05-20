<?php

class Lmsscorecard_model extends CI_Model
{
	
	public $reporting_user = array();
	public $reporting_user_id = array();
	public $user_list_id;
	public $reportingid;
	public $reporting_to;
	public $user_report_id;
  	public $get_assign_to_user_id= array();
  	public $user_id;
  	public $branch;

		function __construct()
		{
			$this->load->library('form_validation');
			$this->load->helper('url');
			$this->load->database();
			$this->load->helper('language');
			$this->load->library('session');

		}

   	
		function get_leadlms_scorecard()
		{

			$reportingto=$this->session->userdata['reportingto'];
			$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
			if ($reportingto=='')
			{
				 $sql="	SELECT
									collector,
										sum(prospect) as prospects, 
										sum(prospect) * (0.1)as prospects_sc, 
										sum(met_the_customer) as met_the_customer, 
										sum(met_the_customer) * (0.2)as met_the_customer_sc, 
										sum(credit_sssessment) as credit_sssessment, 
										sum(credit_sssessment) * (0.3)as credit_sssessment_sc, 
										sum(sample_trails_formalities) as sample_trails_formalities, 
										sum(sample_trails_formalities) * (0.5)as sample_trails_formalities_sc, 
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation, 
										sum(enquiry_offer_negotiation) * (0.7)as enquiry_offer_negotiation_sc, 
										sum(managing_and_implementation) as managing_and_implementation, 
										sum(managing_and_implementation) * (0.8)as managing_and_implementation_sc, 
										sum(expanding_and_build_relationship) as expanding_and_build_relationship,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

							FROM 
									vw_lms_scorecard
							GROUP BY
								collector
							ORDER BY
								collector";
			}
			else
			{
					$sql="SELECT 
										collector,
										sum(prospect) as prospects, 
										sum(prospect) * (0.1)as prospects_sc, 
										sum(met_the_customer) as met_the_customer, 
										sum(met_the_customer) * (0.2)as met_the_customer_sc, 
										sum(credit_sssessment) as credit_sssessment, 
										sum(credit_sssessment) * (0.3)as credit_sssessment_sc, 
										sum(sample_trails_formalities) as sample_trails_formalities, 
										sum(sample_trails_formalities) * (0.5)as sample_trails_formalities_sc, 
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation, 
										sum(enquiry_offer_negotiation) * (0.7)as enquiry_offer_negotiation_sc, 
										sum(managing_and_implementation) as managing_and_implementation, 
										sum(managing_and_implementation) * (0.8)as managing_and_implementation_sc, 
										sum(expanding_and_build_relationship) as expanding_and_build_relationship,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

								FROM 
										vw_lms_scorecard
								WHERE assign_to_id in (".$get_assign_to_user_id.")
								GROUP BY
									collector
								ORDER BY
									collector";

			}
			//echo $sql; die;

						$jTableResult = array();
						
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
						$data_score = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row_score = array();
							$row["collector"] = $jTableResult['leaddetails'][$i]["collector"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];

							$row_score["collector"] = $jTableResult['leaddetails'][$i]["collector"];
							$row_score["prospects_sc"] = $jTableResult['leaddetails'][$i]["prospects_sc"];
							$row_score["met_the_customer_sc"] = $jTableResult['leaddetails'][$i]["met_the_customer_sc"];
							$row_score["credit_sssessment_sc"] = $jTableResult['leaddetails'][$i]["credit_sssessment_sc"];
							$row_score["sample_trails_formalities_sc"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"];
							$row_score["enquiry_offer_negotiation_sc"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"];
							$row_score["managing_and_implementation_sc"] = $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"];
							$row_score["expanding_and_build_relationship_sc"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];

							$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];


							$row_score["total_sc"]= $jTableResult['leaddetails'][$i]["prospects_sc"]+$jTableResult['leaddetails'][$i]["met_the_customer_sc"]+ $jTableResult['leaddetails'][$i]["credit_sssessment_sc"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];
									

							$data[$i] = $row;
							$data_score[$i] = $row_score;
							$i++;
						}




	/*					echo"<pre>";print_r($data);echo"</pre>"; 
						echo"<pre>";print_r($data_score);echo"</pre>"; die;*/
						$arr = "{\"data\":" .json_encode($data). "}";
						$arr_sc = "{\"data\":" .json_encode($data_score). "}";
						$arr_arr_sc['arr']=$arr;
						$arr_arr_sc['arr_sc']=$arr_sc;
					//	echo"<pre>";print_r($arr_arr_sc);echo"</pre>"; die;
				 		return $arr_arr_sc;
		}


		function get_leadlms_scorecard_chart()
		{

			$reportingto=$this->session->userdata['reportingto'];
			$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
			if ($reportingto=='')
			{
				 				$sql="SELECT fin_yr,jc_code,jc_week,
                                        sum(prospect) * (0.1) as prospects_sc, 
                                        sum(met_the_customer) * (0.2) as met_the_customer_sc, 
                                        sum(credit_sssessment) * (0.3) as credit_sssessment_sc, 
                                        sum(sample_trails_formalities) * (0.5) as sample_trails_formalities_sc, 
                                        sum(enquiry_offer_negotiation) * (0.7) as enquiry_offer_negotiation_sc, 
                                        sum(managing_and_implementation) * (0.8) as managing_and_implementation_sc, 
                                        sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

                                FROM 
                                        vw_lms_scorecard
                                WHERE createddate >= '2015-04-01' AND createddate <= '2016-05-18'  AND jc_code in (13,1,2)
                                GROUP BY    
                                fin_yr,
                                jc_code,
                                jc_week
                                ORDER BY fin_yr";
			}
			else
			{
							$sql="SELECT fin_yr,jc_code,jc_week,
                                        sum(prospect) * (0.1) as prospects_sc, 
                                        sum(met_the_customer) * (0.2) as met_the_customer_sc, 
                                        sum(credit_sssessment) * (0.3) as credit_sssessment_sc, 
                                        sum(sample_trails_formalities) * (0.5) as sample_trails_formalities_sc, 
                                        sum(enquiry_offer_negotiation) * (0.7) as enquiry_offer_negotiation_sc, 
                                        sum(managing_and_implementation) * (0.8) as managing_and_implementation_sc, 
                                        sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

                                FROM 
                                        vw_lms_scorecard
                                WHERE createddate >= '2015-04-01' AND createddate <= '2016-05-18'  AND jc_code in (13,1,2) AND assign_to_id in (".$get_assign_to_user_id.")
                                GROUP BY    
                                fin_yr,
                                jc_code,
                                jc_week
                                ORDER BY fin_yr";

			}
			//echo $sql; die;

						$jTableResult = array();
						
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
						$data_score_chart = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							
							$row_score_chart = array();
							$row_score_chart["fin_yr"] = $jTableResult['leaddetails'][$i]["fin_yr"];
							$row_score_chart["jc_code"] = $jTableResult['leaddetails'][$i]["jc_code"];
							$row_score_chart["jc_week"] = $jTableResult['leaddetails'][$i]["jc_week"];
							$row_score_chart["prospects_sc"] = $jTableResult['leaddetails'][$i]["prospects_sc"];
							$row_score_chart["met_the_customer_sc"] = $jTableResult['leaddetails'][$i]["met_the_customer_sc"];
							$row_score_chart["credit_sssessment_sc"] = $jTableResult['leaddetails'][$i]["credit_sssessment_sc"];
							$row_score_chart["sample_trails_formalities_sc"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"];
							$row_score_chart["enquiry_offer_negotiation_sc"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"];
							$row_score_chart["managing_and_implementation_sc"] = $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"];
							$row_score_chart["expanding_and_build_relationship_sc"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];


							$row_score_chart["total_sc"]= $jTableResult['leaddetails'][$i]["prospects_sc"]+$jTableResult['leaddetails'][$i]["met_the_customer_sc"]+ $jTableResult['leaddetails'][$i]["credit_sssessment_sc"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];
									

							//$data[$i] = $row;
							$data_score_chart[$i] = $row_score_chart;
							$i++;
						}

						/*
						$sql="SELECT 
									jc_code,
									sum(score) as score,
									CASE WHEN (j.jc_code=1 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC1Week1\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC1Week2\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC1Week3\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC1Week4\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC2Week1\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC2Week2\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC2Week3\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC2Week4\", 
									CASE WHEN (j.jc_code=13 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC13Week1\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC13Week2\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC13Week3\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC13Week4\"
									FROM 
									(
									SELECT 
									ld.jc_code,
									ld.jc_week,
									sum(prospects_sc+met_the_customer_sc+credit_sssessment_sc+sample_trails_formalities_sc+enquiry_offer_negotiation_sc+managing_and_implementation_sc+expanding_and_build_relationship_sc) as score
									FROM 
									(


				 					SELECT fin_yr,jc_code,jc_week,
                                        sum(prospect) * (0.1) as prospects_sc, 
                                        sum(met_the_customer) * (0.2) as met_the_customer_sc, 
                                        sum(credit_sssessment) * (0.3) as credit_sssessment_sc, 
                                        sum(sample_trails_formalities) * (0.5) as sample_trails_formalities_sc, 
                                        sum(enquiry_offer_negotiation) * (0.7) as enquiry_offer_negotiation_sc, 
                                        sum(managing_and_implementation) * (0.8) as managing_and_implementation_sc, 
                                        sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

                                FROM 
                                        vw_lms_scorecard
                                WHERE createddate >= '2015-04-01' AND createddate <= '2016-05-18'  AND jc_code in (13,1,2)
                                GROUP BY    
                                fin_yr,
                                jc_code,
                                jc_week
                                ORDER BY fin_yr
                                ) ld 
								GROUP BY  	
								jc_code,ld.jc_week
								) j
								GROUP BY  	
								j.jc_week ,
								j.jc_code 
								ORDER BY jc_code";
			}
			else
			{
							$sql="SELECT 
									jc_code,
									sum(score) as score,
									CASE WHEN (j.jc_code=1 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC1Week1\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC1Week2\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC1Week3\",
									CASE WHEN (j.jc_code=1 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC1Week4\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC2Week1\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC2Week2\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC2Week3\",
									CASE WHEN (j.jc_code=2 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC2Week4\", 
									CASE WHEN (j.jc_code=13 AND j.jc_week=1) THEN sum(score)  ELSE 0 END  as  \"JC13Week1\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=2) THEN sum(score)  ELSE 0 END  as  \"JC13Week2\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=3) THEN sum(score)  ELSE 0 END  as  \"JC13Week3\",
									CASE WHEN (j.jc_code=13 AND j.jc_week=4) THEN sum(score)  ELSE 0 END  as  \"JC13Week4\"
									FROM 
									(
									SELECT 
									ld.jc_code,
									ld.jc_week,
									sum(prospects_sc+met_the_customer_sc+credit_sssessment_sc+sample_trails_formalities_sc+enquiry_offer_negotiation_sc+managing_and_implementation_sc+expanding_and_build_relationship_sc) as score
									FROM 
									(


										SELECT fin_yr,jc_code,jc_week,
                                        sum(prospect) * (0.1) as prospects_sc, 
                                        sum(met_the_customer) * (0.2) as met_the_customer_sc, 
                                        sum(credit_sssessment) * (0.3) as credit_sssessment_sc, 
                                        sum(sample_trails_formalities) * (0.5) as sample_trails_formalities_sc, 
                                        sum(enquiry_offer_negotiation) * (0.7) as enquiry_offer_negotiation_sc, 
                                        sum(managing_and_implementation) * (0.8) as managing_and_implementation_sc, 
                                        sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

                                FROM 
                                        vw_lms_scorecard
                                WHERE createddate >= '2015-04-01' AND createddate <= '2016-05-18'  AND jc_code in (13,1,2) AND assign_to_id in (".$get_assign_to_user_id.")
                                GROUP BY    
                                fin_yr,
                                jc_code,
                                jc_week
                                ORDER BY fin_yr



								) ld 
								GROUP BY  	
								jc_code,ld.jc_week
								) j
								GROUP BY  	
								j.jc_week ,
								j.jc_code 
								ORDER BY jc_code";

			}
						*/


						

	/*					echo"<pre>";print_r($data);echo"</pre>"; 
						echo"<pre>";print_r($data_score);echo"</pre>"; die;*/
						//$arr = "{\"data\":" .json_encode($data). "}";
						//$arr_sc_chart = "{\"data\":" .json_encode($data_score_chart). "}";
						$arr_sc_chart = json_encode($data_score_chart);
						//$arr_arr_sc['arr']=$arr;
						$arr_arr_sc_chart['arr_sc_chart']=$arr_sc_chart;
					//	echo"<pre>";print_r($arr_arr_sc);echo"</pre>"; die;
				 		return $arr_arr_sc_chart;
		}

		function get_leadlms_scorecard_withfilters($account_yr,$jc_to,$jc_week,$zone,$collector,$marketcircle,$itemgroup,$fromdate,$todate)
		{
			$itemgroup =htmlspecialchars_decode($itemgroup); 
			$reportingto=$this->session->userdata['reportingto'];
			$get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];

			$whereParts = array();
	        if( ($collector) && $collector!='All')     { $whereParts[] = "collector ='$collector' "; }
	        if ( ($account_yr && $account_yr!='All') ) { $whereParts[] = "fin_yr = '$account_yr' "; }
	        if ($reportingto!=''){
	        if( ( $get_assign_to_user_id && $get_assign_to_user_id!='All') ) { $whereParts[] = "assign_to_id IN($get_assign_to_user_id) "; }
	        }
	       
	        if( ( $zone && $zone!='All'))  { $whereParts[] = "mc_zone = '$zone' "; }

	        if( ( $marketcircle && $marketcircle!='All'))  { $whereParts[] = "mc_sub_id = '$marketcircle' "; }
	        if( ( $itemgroup && $itemgroup!='All'))  { $whereParts[] = "product_group = '$itemgroup' "; }
	        if( ($fromdate && $fromdate!='All'))  { $whereParts[] = "createddate >= '$fromdate' "; }
	        if( ( $todate && $todate!='All'))  { $whereParts[] = "createddate <= '$todate' "; }
			if ($reportingto=='')
			{
				 $sql="	SELECT
									collector,
										sum(prospect) as prospects, 
										sum(prospect) * (0.1)as prospects_sc, 
										sum(met_the_customer) as met_the_customer, 
										sum(met_the_customer) * (0.2)as met_the_customer_sc, 
										sum(credit_sssessment) as credit_sssessment, 
										sum(credit_sssessment) * (0.3)as credit_sssessment_sc, 
										sum(sample_trails_formalities) as sample_trails_formalities, 
										sum(sample_trails_formalities) * (0.5)as sample_trails_formalities_sc, 
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation, 
										sum(enquiry_offer_negotiation) * (0.7)as enquiry_offer_negotiation_sc, 
										sum(managing_and_implementation) as managing_and_implementation, 
										sum(managing_and_implementation) * (0.8)as managing_and_implementation_sc, 
										sum(expanding_and_build_relationship) as expanding_and_build_relationship,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

							FROM 
									vw_lms_scorecard WHERE ";
			}
			else
			{
					$sql="SELECT 
										collector,
										sum(prospect) as prospects, 
										sum(prospect) * (0.1)as prospects_sc, 
										sum(met_the_customer) as met_the_customer, 
										sum(met_the_customer) * (0.2)as met_the_customer_sc, 
										sum(credit_sssessment) as credit_sssessment, 
										sum(credit_sssessment) * (0.3)as credit_sssessment_sc, 
										sum(sample_trails_formalities) as sample_trails_formalities, 
										sum(sample_trails_formalities) * (0.5)as sample_trails_formalities_sc, 
										sum(enquiry_offer_negotiation) as enquiry_offer_negotiation, 
										sum(enquiry_offer_negotiation) * (0.7)as enquiry_offer_negotiation_sc, 
										sum(managing_and_implementation) as managing_and_implementation, 
										sum(managing_and_implementation) * (0.8)as managing_and_implementation_sc, 
										sum(expanding_and_build_relationship) as expanding_and_build_relationship,
										sum(expanding_and_build_relationship) as expanding_and_build_relationship_sc

								FROM 
										vw_lms_scorecard
								WHERE ";
								


			}
			//echo"<pre> whereParts";print_r($whereParts);echo"</pre>";
			  if(count($whereParts)) {
                         	$sql .= "". implode('AND ', $whereParts);
        		}
        		$sql .= ' GROUP BY 	collector ORDER BY collector'; 
		//	echo $sql; die;

						$jTableResult = array();
						
				    
						$result = $this->db->query($sql);
						$jTableResult['leaddetails'] = $result->result_array();
						$chart_leads_count = count($jTableResult['leaddetails']);
						$this->session->set_userdata('chart_leads_count',$chart_leads_count);
						$data = array();
						$data_score = array();
				
						$i=0;
						while($i < count($jTableResult['leaddetails']))
						{    
								
							$row = array();
							$row_score = array();
							$row["collector"] = $jTableResult['leaddetails'][$i]["collector"];
							$row["prospects"] = $jTableResult['leaddetails'][$i]["prospects"];
							$row["met_the_customer"] = $jTableResult['leaddetails'][$i]["met_the_customer"];
							$row["credit_sssessment"] = $jTableResult['leaddetails'][$i]["credit_sssessment"];
							$row["sample_trails_formalities"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities"];
							$row["enquiry_offer_negotiation"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"];
							$row["managing_and_implementation"] = $jTableResult['leaddetails'][$i]["managing_and_implementation"];
							$row["expanding_and_build_relationship"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];

							$row_score["collector"] = $jTableResult['leaddetails'][$i]["collector"];
							$row_score["prospects_sc"] = $jTableResult['leaddetails'][$i]["prospects_sc"];
							$row_score["met_the_customer_sc"] = $jTableResult['leaddetails'][$i]["met_the_customer_sc"];
							$row_score["credit_sssessment_sc"] = $jTableResult['leaddetails'][$i]["credit_sssessment_sc"];
							$row_score["sample_trails_formalities_sc"] = $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"];
							$row_score["enquiry_offer_negotiation_sc"] = $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"];
							$row_score["managing_and_implementation_sc"] = $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"];
							$row_score["expanding_and_build_relationship_sc"] = $jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];

							$row["total"]= $jTableResult['leaddetails'][$i]["prospects"]+$jTableResult['leaddetails'][$i]["met_the_customer"]+ $jTableResult['leaddetails'][$i]["credit_sssessment"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship"];


							$row_score["total_sc"]= $jTableResult['leaddetails'][$i]["prospects_sc"]+$jTableResult['leaddetails'][$i]["met_the_customer_sc"]+ $jTableResult['leaddetails'][$i]["credit_sssessment_sc"]+ $jTableResult['leaddetails'][$i]["sample_trails_formalities_sc"]+ $jTableResult['leaddetails'][$i]["enquiry_offer_negotiation_sc"]+ $jTableResult['leaddetails'][$i]["managing_and_implementation_sc"]+$jTableResult['leaddetails'][$i]["expanding_and_build_relationship_sc"];
									

							$data[$i] = $row;
							$data_score[$i] = $row_score;
							$i++;
						}

	/*					echo"<pre>";print_r($data);echo"</pre>"; 
						echo"<pre>";print_r($data_score);echo"</pre>"; die;*/
						$arr = "{\"data\":" .json_encode($data). "}";
						$arr_sc = "{\"data\":" .json_encode($data_score). "}";
						$arr_arr_sc['arr']=$arr;
						$arr_arr_sc['arr_sc']=$arr_sc;
					//	echo"<pre>";print_r($arr_arr_sc);echo"</pre>"; die;
				 		return $arr_arr_sc;
		}

		function get_zones()
				{
				
						$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
											$sql="SELECT 
													m.mc_zone as zone
												FROM 
													ar_collectors a,market_circle_hdr m
												WHERE a.collector_id = m.collector_id GROUP BY 	m.mc_zone ORDER BY m.mc_zone";
						} else
						{


										

										$sql="SELECT 
													m.mc_zone as zone
												FROM 
													ar_collectors a,market_circle_hdr m
													WHERE a.collector_id = m.collector_id AND 	gc_executive_code in (".$get_assign_to_user_id.") 
													GROUP BY m.mc_zone ORDER BY m.mc_zone";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_zone =  array('zone' =>'All');
							array_push($options, $all_zone);
							$arr =  json_encode($options); 
					return $arr;

				}

		function get_collectors()
				{
				
						$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
											$sql="SELECT a.name as collectors
													FROM  ar_collectors a,market_circle_hdr m
													WHERE a.collector_id = m.collector_id 
													GROUP BY collectors ORDER BY collectors";
						} else
						{

										 

										

										$sql="SELECT a.name as collectors
												FROM  ar_collectors a,market_circle_hdr m
												WHERE a.collector_id = m.collector_id 
												AND  gc_executive_code in (".$get_assign_to_user_id.") 	GROUP BY collectors ORDER BY collectors";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_collectors =  array('collectors' =>'All');
							array_push($options, $all_collectors);
							$arr =  json_encode($options); 
					return $arr;

				}
		function get_collectors_forfilter($zone)
		{
			$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
											$sql="SELECT a.name as collectors
													FROM  ar_collectors a,market_circle_hdr m
													WHERE a.collector_id = m.collector_id 
													AND m.mc_zone='".$zone."'
													GROUP BY collectors ORDER BY collectors";
						} else
						{

										 

										

										$sql="SELECT a.name as collectors
												FROM  ar_collectors a,market_circle_hdr m
												WHERE a.collector_id = m.collector_id 
												AND m.mc_zone='".$zone."'
												AND  gc_executive_code in (".$get_assign_to_user_id.") 	GROUP BY collectors ORDER BY collectors";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_collectors =  array('collectors' =>'All');
							array_push($options, $all_collectors);
							$arr =  json_encode($options); 
					return $arr;
		}
		function get_marketcircles()
				{
				
						$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
							$sql="SELECT m.mc_sub_id as market_circle FROM  ar_collectors a, market_circle_hdr m WHERE a.collector_id = m.collector_id 
								GROUP BY mc_sub_id ORDER BY mc_sub_id";
						} else
						{

										 
										$sql="SELECT m.mc_sub_id as market_circle FROM  ar_collectors a, market_circle_hdr m WHERE a.collector_id = m.collector_id 
											  AND  gc_executive_code in (".$get_assign_to_user_id.") GROUP BY mc_sub_id ORDER BY mc_sub_id";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_market_circle =  array('market_circle' =>'All');
							array_push($options, $all_market_circle);
							$arr =  json_encode($options); 
						return $arr;

				}
		function get_marketcircles_forfilter($collector)
				{
				
						$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
							$sql="SELECT m.mc_sub_id as market_circle FROM  ar_collectors a, market_circle_hdr m 
							WHERE a.collector_id = m.collector_id AND upper(a.name)='".strtoupper($collector)."'
								GROUP BY mc_sub_id ORDER BY mc_sub_id";
						} else
						{

										 
										$sql="SELECT m.mc_sub_id as market_circle FROM  ar_collectors a, market_circle_hdr m 
										WHERE a.collector_id = m.collector_id AND upper(a.name)='".strtoupper($collector)."'
											  AND  gc_executive_code in (".$get_assign_to_user_id.") GROUP BY mc_sub_id ORDER BY mc_sub_id";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_market_circle =  array('market_circle' =>'All');
							array_push($options, $all_market_circle);
							$arr =  json_encode($options); 
						return $arr;

				}				
		function get_productgroups()
				{
				
						$reporting_to = $this->session->userdata['reportingto'];
						$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

						if (@$reporting_to=="")
						{
							$sql="SELECT product_group FROM vw_lms_scorecard GROUP BY product_group ORDER BY product_group";
						} else
						{

										 
							$sql="SELECT product_group FROM vw_lms_scorecard  WHERE assign_to_id in  (".$get_assign_to_user_id.") GROUP BY product_group ORDER BY product_group";
				
						}
						//echo $sql; die;
							$result = $this->db->query($sql);
							$options = $result->result_array();
							$all_product_group =  array('product_group' =>'All');
							array_push($options, $all_product_group);
							$arr =  json_encode($options); 
						return $arr;

				}	

		function get_jchdr_forweek($fin_year)
				{
					$sql="SELECT  header_id,line_id,jc_code,'JC'||jc_code as jc_name,jc_period_from,jc_period_to,acc_yr FROM jc_calendar_dtl WHERE acc_yr='".$fin_year."' order by jc_code";
				//echo $sql; die;	
					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-ALL-");
	
					$arr =  json_encode($options); 
				return $arr;	
				}

		function get_jcweek_hdr($account_yr,$jc_code)
				{
					$jc_code= $jc_code+1;
				    $sql="SELECT  header_id,line_id,jc_cal_line_id,acc_yr,week_id,'Week'||week_id as jc_weekname,week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE   acc_yr='".$account_yr."' AND jc_cal_line_id in (SELECT line_id from	 jc_calendar_dtl WHERE acc_yr='".$account_yr."' AND jc_code=".$jc_code.")";
				   // echo $sql; die;
					$result = $this->db->query($sql);
					$options = $result->result_array();
					array_push($options, "-ALL-");
	
					$arr =  json_encode($options); 
				return $arr;

				}

		function reload_jcweek_hdr($fin_year,$jc_code)
		{
			$sql="SELECT line_id,week_id FROM   jc_calendar_week_dtl   WHERE  acc_yr='".$fin_year."' AND jc_cal_line_id='".$jc_code."'";
			$result = $this->db->query($sql);
			$options = $result->result_array();
			array_push($options, "-ALL-");
			$arr =  json_encode($options); 
			return $arr;

		}		

		function get_jcweek_periods()
			{	

				$sql="SELECT week_period_from,week_period_to FROM 
								jc_calendar_week_dtl   WHERE  acc_yr='".urldecode($this->fin_year)."' AND jc_cal_line_id='".urldecode($this->jc_code)."' AND line_id='".urldecode($this->jc_week)."' order by line_id";
            //echo $sql; die;
			$result = $this->db->query($sql);
			$jcperiods = $result->result_array();
			$arr =  json_encode($jcperiods); 
			return $arr;
			}

		function get_financeyear()
			{
				$sql="SELECT finance_year FROM jc_calendar_hdr ORDER BY 1 asc ";
				$result = $this->db->query($sql);
				$options = $result->result_array();
				array_push($options, "-All-");

				$arr =  json_encode($options); 
			return $arr;

			}
		function get_current_jc_week($curr_date,$acc_yr)
		{
			  
			    $sql="SELECT week_id  FROM jc_calendar_week_dtl WHERE acc_yr='".$acc_yr."' AND  '".$curr_date."' BETWEEN week_period_from and week_period_to";
	            $result = $this->db->query($sql);
                $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['week_id'];

		}	

		function get_current_jc($curr_date,$acc_yr)
            {
                $sql="SELECT jc_code FROM jc_calendar_dtl  WHERE acc_yr='".$acc_yr."' AND  '".$curr_date."' BETWEEN jc_period_from and jc_period_to";
                
                $result = $this->db->query($sql);
                    $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['jc_code'];
            }

         function get_current_jc_weekfor_search($account_yr,$jc_id,$jc_week_id)
		{
			  
			    $sql="SELECT week_id  FROM jc_calendar_week_dtl WHERE acc_yr='".$account_yr."' AND  jc_cal_line_id=".$jc_id." AND line_id=".$jc_week_id;
	            $result = $this->db->query($sql);
                $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['week_id'];

		}	

		function get_current_jcfor_search($account_yr,$jc_id,$jc_week_id)
            {
                //$sql="SELECT jc_code FROM jc_calendar_dtl  WHERE acc_yr='".$acc_yr."' AND  '".$curr_date."' BETWEEN jc_period_from and jc_period_to";
            	$sql="SELECT jc_code FROM jc_calendar_dtl  WHERE acc_yr='".$account_yr."' AND  line_id=".$jc_id;
                

                $result = $this->db->query($sql);
                    $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['jc_code'];
            }	



} // End of Class


?>

