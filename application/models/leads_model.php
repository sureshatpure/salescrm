<?php

class Leads_model extends CI_Model {

    public $country_id = null;
    public $i, $j;
    public $reporting_user = array();
    public $reporting_user_id = array();
    public $user_list_id;
    public $reportingid;
    public $user_report_id;
    public $bussinesscat;

    function __construct() {
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->database();
        $this->load->helper('language');
        $this->load->library('subquery');
        $this->load->library('session');
    }       

    public function get_leadstatus() {
        $options = $this->db->select('leadstatusid, leadstatus')->get('leadstatus')->result();
        $options_arr;
        $options_arr[''] = '-Please Select Lead Status-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->leadstatusid] = $option->leadstatus;
        }
        return $options_arr;
    }

    public function get_leadstatus_add() {
        $this->db->order_by("order_by");
        //$this->db->limit(1);
        $this->db->where('leadstatusid <', 6);
        $options = $this->db->select('leadstatusid, leadstatus')->get('leadstatus')->result();

        $options_arr;
        //$options_arr[''] = '-Please Select Lead Status-';
        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->leadstatusid] = $option->leadstatus;
        }
        return $options_arr;
    }

    public function get_locationuser_add() {

                $reporting_to = $this->session->userdata['reportingto'];
                $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

                if (@$reporting_to=="")
                {
                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id";
                } else
                {

                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id AND 
                    v.header_user_id IN (".$get_assign_to_user_id.")";
                }




       
        //$sql = "SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id";
        //echo $sql; die;
        $result = $this->db->query($sql);
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Branch-';
        foreach ($options as $option) {
            $options_arr[$option['branch']] = $option['branch'];
        }
        return $options_arr;
    }

    public function get_locationuser_add_order() {
        @$this->session->set_userdata($get_assign_to_user_id);
        $reporting_to = $this->session->userdata['reportingto'];
        $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

         if (@$reporting_to=="")
                {
                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id";
                } else
                {

                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id AND 
                    v.header_user_id IN (".$get_assign_to_user_id.")";
                }

/*        $sql = "select 	distinct branch from (select  header_user_id, upper(location_user) as branch from  vw_web_user_login ) a 	where 
	header_user_id IN (" . $get_assign_to_user_id . ")  order  	by branch";*/


        $result = $this->db->query($sql);
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Branch-';
        foreach ($options as $option) {
            $options_arr[$option['branch']] = $option['branch'];
        }
        return $options_arr;
    }

    public function get_leadstatus_edit($lid, $l_order_id) {
        //$this->db->where('order_by =', $l_order_id);
        //$this->db->or_where("ordser_by > $l_order_id");
        //$this->db->where('order_by =', $l_order_id);
        $this->db->where("order_by >='$l_order_id'", NULL, FALSE);
        $this->db->order_by("order_by");
        //where("CHAR_LENGTH(empname) > '0'",NULL,FALSE)
        $options = $this->db->select('leadstatusid, leadstatus')->get('leadstatus')->result();
        $options_arr;
        $options_arr[''] = '-Please Select Lead Status-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->leadstatusid] = $option->leadstatus;
        }
        return $options_arr;
    }

    public function get_leadsource() {
        $options = $this->db->select('leadsourceid, leadsource')->get('leadsource')->result();
        $options_arr;
        $options_arr[''] = '-Please Select Lead Source-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->leadsourceid] = $option->leadsource;
        }
        return $options_arr;
    }

    public function get_leadcredit_assment() {
        $options = $this->db->select('crd_id, crd_name')->get('lead_credit_assesment')->result();
        $options_arr;
        $options_arr[''] = '-Select Credit Assesment-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->crd_id] = $option->crd_name;
        }
        return $options_arr;
    }

    public function get_industry() {
        $this->db->order_by('industrysegment');
        $options = $this->db->select('id, industrysegment')->get('industry_segment')->result();
        $options_arr;
        $options_arr[''] = '-Please Select Industry Type-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->id] = $option->industrysegment;
        }
        return $options_arr;
    }

    public function get_company() {
        $options = $this->db->select('id, tempcustname')->get('customermasterhdr')->result();
        $options_arr;
        $options_arr[''] = '-Please Select Company-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->id] = $option->tempcustname;
        }
        return $options_arr;
    }

    public function get_all_company() {
        $sql = 'SELECT  view_tempcustomermaster.id,view_tempcustomermaster.tempcustname FROM view_tempcustomermaster ORDER BY  tempcustname ASC';
        //echo $sql; die;
        $result = $this->db->query($sql);
        //	print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Customer-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option['id']] = $option['tempcustname'];
        }
        return $options_arr;
    }
       public function getleadcustomers() {
      
        $options = array('#=>'=>'-Please Select Customer-');
        $options_arr;
        $options_arr[''] = '-Please Select Customer-';


        // Format for passing into form_dropdown function

        /*foreach ($options as $option) {
            $options_arr[$option['id']] = $option['tempcustname'];
        }*/
        //print_r($options_arr); die;
        return $options_arr;
    }

     public function getmarketcircles() {
      
        $options = array('#=>'=>'-Select Market Circle-');
        $options_arr;
        $options_arr[''] = '-Select Market Circle-';


        // Format for passing into form_dropdown function

        /*foreach ($options as $option) {
            $options_arr[$option['id']] = $option['tempcustname'];
        }*/
        //print_r($options_arr); die;
        return $options_arr;
    }


    public function get_collectors($reporting_user_id) 
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
       
       // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
       // $options_val=array('#'=>'-Please Select Collector-');

        $options_arr['#'] = '-Please Select Collector-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option['collector']] = $option['collector'];
        }
        //array_unshift($options_arr,$options_val);
        return $options_arr;
    }

    

    public function get_country() {
        $options = $this->db->select('id, name')->get('country')->result();
        $options_arr;
        $options_arr[''] = '--Please Select Country--';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->id] = $option->name;
        }
        return $options_arr;
    }

    public function get_states_edit($cntid) {
        $this->db->where('countrycode', $cntid);
        $options = $this->db->select('statecode, statename')->get('states')->result();
        $options_arr;
        $options_arr[''] = '--Please Select state--';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->statecode] = $option->statename;
        }
        return $options_arr;
    }

    public function get_substatus_edit($ld_sts_id) {
        $this->db->where('lst_sub_id', $ld_sts_id);
        $options = $this->db->select('lst_sub_id, lst_name')->get('leadsubstatus')->result();
        $options_arr;
        $options_arr[''] = '--Select Sub Status--';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->lst_sub_id] = $option->lst_name;
        }
        return $options_arr;
    }

    public function get_substatus_edit_all($ld_sts_id, $lst_parentid_id, $lst_order_by_id) {
//		$this->db->where('lst_sub_id',$ld_sts_id);
//		$this->db->where("lst_order_by >='$lst_order_by_id'",NULL,FALSE);  commented to list all substatus - may-14th
        $this->db->where("lst_order_by >='$lst_order_by_id'", NULL, FALSE);
        $this->db->where("lst_parentid", $lst_parentid_id);
        $this->db->order_by("lst_order_by");
        $options = $this->db->select('lst_sub_id, lst_name,lst_order_by')->get('leadsubstatus')->result();
        $options_arr;
        $options_arr[''] = '--Select Sub Status--';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->lst_sub_id] = $option->lst_name;
        }
        return $options_arr;
    }

    public function get_city_edit($stid) {
        $this->db->where('statecode', trim($stid));
        $options = $this->db->select('statecode, cityname')->get('city')->result();
        $options_arr;
        $options_arr[''] = '--Please Select city--';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option->cityname] = $option->cityname;
        }
        return $options_arr;
    }

    function get_assignto_users() {
        $sql = "select header_user_id,displayname from (select  header_user_id, upper(location_user) || '-' || upper(empname) as displayname from  vw_web_user_login ) a  order by displayname";

        $result = $this->db->query($sql);
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select User-';
        foreach ($options as $option) {
            //	$options_arr[$option->header_user_id] = strtoupper($option->location_user)."-".$option->empname;
            $options_arr[$option['header_user_id']] = $option['displayname'];
        }
        return $options_arr;
    }

    function get_assignto_users_order($user_id) {
        //global $get_assign_to_user_id;
//   echo"the value is ".$get_assign_to_user_id."<br>"; 
        @$this->session->set_userdata($get_assign_to_user_id);
        //	print_r($this->session->userdata);
        $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
//print_r($get_assign_to_user_id);die;
        $sql = "select header_user_id,displayname from (select  header_user_id, upper(location_user) || '-' || upper(empname) as displayname from  vw_web_user_login ) a where header_user_id IN (" . $get_assign_to_user_id . ") order by displayname";
        
        $result = $this->db->query($sql);
        $options = $result->result_array();

        $options_arr;
        $options_arr[''] = '-Please Select User-';
        if (count($options) > 0) {
            foreach ($options as $option) {
                $options_arr[$option['header_user_id']] = $option['displayname'];
            }
        } else {
            $user_id = $this->session->userdata['user_id'];
            $name = $this->session->userdata['username'];
            $options_arr[$user_id] = $name;
        }
        return $options_arr;
       
    }

    function get_assignto_users_order_edit($user_id, $branch) {
        //global $get_assign_to_user_id;
        //   echo"the value is ".$get_assign_to_user_id."<br>"; 
        @$this->session->set_userdata($get_assign_to_user_id);
        
        $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
       

       /* $sql = "select header_user_id,displayname,branch from (select  header_user_id,upper(location_user) as branch, upper(location_user) || '-' || upper(empname) as displayname from  vw_web_user_login ) a where header_user_id IN (" . $get_assign_to_user_id . ") and upper(branch)='" . $branch . "' order by displayname";
*/
        $sql = "select header_user_id,displayname,branch from (select  header_user_id,upper(location_user) as branch, upper(location_user) || '-' || upper(empname) as displayname1,upper(empname) || '-' || upper(location_user) as displayname from  vw_web_user_login ) a where header_user_id IN (" . $get_assign_to_user_id . ") and upper(branch)='" . $branch . "' order by displayname";        


   // echo sql; die;
        $result = $this->db->query($sql);
        $options = $result->result_array();

        $options_arr;
        $options_arr[''] = '-Please Select User-';
        if (count($options) > 0) {
            foreach ($options as $option) {
                $options_arr[$option['header_user_id']] = $option['displayname'];
            }
        } else {
            $user_id = $this->session->userdata['user_id'];
            $name = $this->session->userdata['username'];
            $options_arr[$user_id] = $name;
        }
        return $options_arr;
    }

    function get_assignedto() {
        $this->db->select('header_user_id, empname');
        $query = $this->db->get('vw_web_user_login');
        $arr = json_encode($query->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }


    function get_products() {
       
        //	$sql='SELECT  DISTINCT on (description) id, description FROM view_tempitemmaster_with_description_null ORDER BY description asc';
        //$sql='SELECT  DISTINCT on (description) id, description FROM view_tempitemmaster ORDER BY description asc';
        $sql = 'SELECT (itemmaster.itemid)::character varying(20) AS id, itemmaster.description FROM itemmaster UNION ALL SELECT tempitemmaster.temp_item_sync_id AS id, tempitemmaster.temp_itemname AS description FROM tempitemmaster';
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_productsfordailycall() {
      
        $sql = "SELECT 
						(itemmaster.itemid)::character varying(20) AS id, 
						itemmaster.description,
						itemmaster.itemgroup 
					FROM 
						itemmaster 
					UNION ALL 
					SELECT 
						tempitemmaster.temp_item_sync_id AS id, 
						tempitemmaster.temp_itemname AS description,
						tempitemmaster.temp_itemname as itemgroup 
					FROM 
						tempitemmaster";
        //echo $sql; die;
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_synched_products($customer_id, $productname) {
        //echo" in model get_synched_products ".$customer_id; die;
       
        //$sql='select id,tempcustid,tempcustname,stdname,cust_account_id,customer_number from customermasterhdr where cust_account_id > 0 ORDER BY creation_date desc ';
        //$sql='select * from vw_lead_get_soc_no where lead_cusomer_ref_id='.$customer_id.' ORDER BY qdate asc limit 1';
        //$sql="select * from vw_lead_get_soc_no_product where lead_cusomer_ref_id=".$customer_id." AND product ='".$productname."'";
        $sql = "select * from vw_lead_get_soc_no_product where lead_cusomer_ref_id=" . $customer_id . " or product ='" . $productname . "'";
        //echo $sql; die;
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_leaddetails_for_grid() {
        $jTableResult = array();
        $jTableResult['leaddetails'] = $this->get_lead_details_all();

        $data = array();

        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
                $lead_status_for_closed = "Closed";
            } else {
                $closed = "Open";
                $lead_status_for_closed = $jTableResult['leaddetails'][$i]["leadstatus"];
            }
            $row["leadstatus"] = $lead_status_for_closed;
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_leaddetails_for_grid_srch($branch=null,$selectuserid=0,$statusid=0,$substatusid=0,$customerid=0,$productid=0,$from_date=null,$to_date=null)
    {
            echo"get_leaddetails_for_grid_srch<br>"; 

        $jTableResult = array();
        $jTableResult['leaddetails'] = $this->get_lead_details_all_srch($branch=null,$selectuserid=0,$selectassigntoid=0,$statusid=0,$substatusid=0,$customerid=0,$productid=0,$from_date=null,$to_date=null);

       // echo"<pre>"; print_r($jTableResult['leaddetails']); echo"</pre>";
        $data = array();
         //echo"<br>";echo"count is ".count($jTableResult['leaddetails']); 
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
                $lead_status_for_closed = "Closed";
            } else {
                $closed = "Open";
                $lead_status_for_closed = $jTableResult['leaddetails'][$i]["leadstatus"];
            }

            $row["mc_sub_id"] = $jTableResult['leaddetails'][$i]["mc_sub_id"];
            $row["leadstatus"] = $lead_status_for_closed;
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }";
        return $arr;

    }
    

    function get_converted_for_grid() {
        $jTableResult = array();
        $jTableResult['leaddetails'] = $this->get_lead_converted_all();
        $data = array();

        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];
            $row["soc_number"] = $jTableResult['leaddetails'][$i]["lead_crm_soc_no"];
            $row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);

                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_dispatch() {
       
        $sql = "SELECT  
					 	DISTINCT  
							CASE 
								WHEN 
									n_value ='Repacking' THEN 'Repack' 
					    			ELSE 
									n_value END n_value,
							n_value_id
					 	FROM   
					 		vw_sales_despatch_transaction_calss_fnd_flex_values_vl 
					 	WHERE 
					 		flex_value_set_id=1014311 AND  
					 		flex_value_id<>173796
					UNION ALL
					SELECT  'Part Tanker',3";
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_getinitial_lead_sub() {
        $sql = 'select * from leadsubstatus where lst_parentid =1 order by lst_order_by';
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_states() {
        if (!is_null($this->country_id)) {
            $this->db->select('statecode, statename');
            $this->db->where('countrycode', $this->country_id);
            $states = $this->db->get('states');

            // if there are suboptinos for this option...
            if ($states->num_rows() > 0) {
                $states_arr;

                // Format for passing into jQuery loop
                foreach ($states->result() as $state) {
                    $states_arr[$state->statecode] = $state->statename;
                }
                return $states_arr;
            }
        }
        return;
    }

    function get_leadsubstatus() {
       
        $sql = "SELECT lst_sub_id, lst_name, lst_order_by FROM leadsubstatus WHERE lst_order_by >=(select  leadsubstatus.lst_order_by  from  leaddetails, leadsubstatus where leaddetails.ldsubstatus = leadsubstatus.lst_sub_id AND leadid=" . $this->session->userdata['run_time_lead_id'] . ")  AND lst_parentid =" . $this->parent_id." ORDER BY lst_order_by ASC";
        
        $result = $this->db->query($sql);
        $substatus = $result->result_array();

        $substatus_arr;
        foreach ($substatus as $substat) {
            //$substatus_arr[$substat['lst_sub_id']] = $substat['lst_name'];
            $substatus_arr[$substat['lst_sub_id']."-".$substat['lst_order_by']] = $substat['lst_name'];
        }
        return $substatus_arr;
    }

    
     function get_leadsubstatus_srch() 
     {
       
        $sql = "SELECT lst_sub_id, lst_name, lst_order_by FROM leadsubstatus WHERE  lst_parentid =" . $this->parent_id." ORDER BY lst_order_by ASC";
        
        $result = $this->db->query($sql);
        $substatus = $result->result_array();

        $substatus_arr;
        foreach ($substatus as $substat) {
            //$substatus_arr[$substat['lst_sub_id']] = $substat['lst_name'];
            $substatus_arr[$substat['lst_sub_id']."-".$substat['lst_order_by']] = $substat['lst_name'];
        }
        return $substatus_arr;
    }

    function get_leadsubstatus_add() {
        if (!is_null($this->parent_id)) {
            $this->db->select('lst_sub_id, lst_name,lst_order_by');
            $this->db->order_by('lst_order_by');
            $this->db->where('lst_parentid', $this->parent_id);
            $substatus = $this->db->get('leadsubstatus');

            // if there are suboptinos for this option...
            if ($substatus->num_rows() > 0) {
                $substatus_arr;

                // Format for passing into jQuery loop
                foreach ($substatus->result() as $substat) {
                    //$substatus_arr[$substat->lst_sub_id] = $substat->lst_name;
                    $substatus_arr[$substat->lst_sub_id."-".$substat->lst_order_by] = $substat->lst_name;
                }
                return $substatus_arr;
            }
        }
        return;
    }

    public function get_lead_customersadd($collector) {
        $collector = urldecode($collector);
        $sql = "select * from  fn_lead_customer_group('".$collector."') ORDER BY tempcustname";
        $result = $this->db->query($sql);
        //  print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Customer-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option['id']] = $option['tempcustname'];
        }
        return $options_arr;
    }

 public function get_lead_customersadd_mc($collector,$marketcircle) {
        $collector = urldecode($collector);
        
        $sql = "select * from  fn_lead_customer_group_mc('".$collector."','".$marketcircle."') ORDER BY tempcustname";
      //  echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Customer-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option['id']] = $option['tempcustname'];
        }
        return $options_arr;
    }
    public function get_lead_marketcircles($collector) {

        $collector = urldecode($collector);
        $sql_collector = "SELECT id as collector_id  FROM collectormaster WHERE upper(collectorname) ='".strtoupper($collector)."'";
        $result_collector = $this->db->query($sql_collector);
        $collectorid = $result_collector->result_array();
        //echo "collector_id".$collectorid['0']['collector_id'];
        $collector_id =$collectorid['0']['collector_id'];
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        if (@$this->session->userdata['reportingto'] == "")
        {

            /*$sql = "SELECT collector_id,mc_code,mc_zone,mc_sub_id FROM market_circle_hdr WHERE collector_id=".$collector_id." GROUP BY collector_id,mc_code,mc_zone,mc_sub_id";*/
            $sql="SELECT c.collector_id,m.mc_code,m.mc_zone,m.mc_sub_id 
                    FROM market_circle_hdr m,
                                            ar_collectors c,
                                            vw_web_user_login v
                    WHERE 
                    m.collector_id = c.collector_id AND 
                    m.gc_executive_code=v.header_user_id AND 
                    c.collector_id=".$collector_id."
                    GROUP BY c.collector_id,m.mc_code,m.mc_zone,m.mc_sub_id";
        }
        else
        {
            /*$sql = "SELECT collector_id,mc_code,mc_zone,mc_sub_id FROM market_circle_hdr WHERE collector_id=".$collector_id." AND gc_executive_code=".$this->session->userdata['user_id']." GROUP BY collector_id,mc_code,mc_zone,mc_sub_id";*/
            $sql="SELECT c.collector_id,m.mc_code,m.mc_zone,m.mc_sub_id 
                    FROM market_circle_hdr m,
                                            ar_collectors c,
                                            vw_web_user_login v
                    WHERE 
                    m.collector_id = c.collector_id AND 
                    m.gc_executive_code=v.header_user_id AND 
                    v.header_user_id IN (" . $get_assign_to_user_id . ")  AND 
                    c.collector_id=".$collector_id."
                    GROUP BY c.collector_id,m.mc_code,m.mc_zone,m.mc_sub_id";


        }
       // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Select Market Circles-';

        // Format for passing into form_dropdown function
        foreach ($options as $option) {
            $options_arr[$option['mc_sub_id']] = $option['mc_sub_id'];
        }
        return $options_arr;
    }

    

    function get_assigned_tobranch_old() {
        //echo " check ".$this->brach_sel; die;
        @$this->session->set_userdata($get_assign_to_user_id);
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        //if ($get_assign_to_user_id == "") {
        if (@$this->session->userdata['reportingto'] == "") {
            $sql = "select header_user_id,displayname , branch from 
								(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from 
								 vw_web_user_login ) a where upper(branch)=upper('" . urldecode($this->brach_sel) . "') order by displayname";
        } else {
            $sql = "select header_user_id,displayname , branch from 
								(select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname from 
								 vw_web_user_login ) a where header_user_id IN (" . $get_assign_to_user_id . ")  and upper(branch)=upper('" . urldecode($this->brach_sel) . "') order by displayname";
        }

        //	echo $sql;
        $result = $this->db->query($sql);
        //	print_r($result->result_array());
        $options = $result->result_array();

        $options_arr;
        $options_arr[''] = '-Please Select User-';
        if (count($options) > 0) {
            foreach ($options as $option) {
                $options_arr[$option['header_user_id']] = $option['displayname'];
            }
        } else {
            $user_id = $this->session->userdata['user_id'];
            $name = $this->session->userdata['username'];
            $options_arr[$user_id] = $name;
        }
        return $options_arr;
    }

    function get_assigned_tobranch() {
        //echo " check ".$this->brach_sel; die;
        @$this->session->set_userdata($get_assign_to_user_id);
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        //if ($get_assign_to_user_id == "") {
        if (@$this->session->userdata['reportingto'] == "") {
           /* $sql = "select header_user_id,displayname , branch from 
                                (select header_user_id,upper(location_user) as branch , upper(location_user) || '-' || upper(empname) as displayname1,upper(empname) || '-' || upper(location_user) as displayname from 
                                 vw_web_user_login ) a where upper(branch)=upper('" . urldecode($this->brach_sel) . "') order by displayname";*/
              $sql="select header_user_id,displayname,branch from (
                            SELECT  v.header_user_id,c.name as branch,m.mc_sub_id,  upper(v.empname)  || '-' || upper(c.name) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id  AND m.gc_executive_code=v.header_user_id AND upper(c.name)=upper('".urldecode($this->brach_sel)."') GROUP BY  v.header_user_id,upper(v.empname), c.name,m.mc_sub_id ) a 
                                  where upper(branch)=upper('".urldecode($this->brach_sel)."') GROUP BY header_user_id, displayname , branch order by displayname";

        } else {
            /*$sql = "select header_user_id,displayname , branch from 
                                (select header_user_id,upper(location_user) as branch ,upper(location_user) || '-' || upper(empname) as displayname1,upper(empname) || '-' || upper(location_user) as displayname from 
                                 vw_web_user_login ) a where header_user_id IN (" . $get_assign_to_user_id . ")  and upper(branch)=upper('" . urldecode($this->brach_sel) . "') order by displayname";*/

             $sql="select header_user_id,displayname,branch from (
                            SELECT  v.header_user_id,c.name as branch,m.mc_sub_id,  upper(v.empname)  || '-' || upper(c.name) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id  AND m.gc_executive_code=v.header_user_id AND upper(c.name)=upper('".urldecode($this->brach_sel)."') GROUP BY  v.header_user_id,upper(v.empname), c.name,m.mc_sub_id ) a 
                                  where upper(branch)=upper('".urldecode($this->brach_sel)."') and  header_user_id IN (".$get_assign_to_user_id.") GROUP BY header_user_id, displayname , branch order by displayname";


        }

        //echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result->result_array());
        $options = $result->result_array();

        $options_arr;
        $options_arr[''] = '-Please Select User-';
        if (count($options) > 0) {
            foreach ($options as $option) {
                $options_arr[$option['header_user_id']] = $option['displayname'];
            }
        } else {
            $user_id = $this->session->userdata['user_id'];
            $name = $this->session->userdata['username'];
            $options_arr[$user_id] = $name;
        }
        return $options_arr;
    }

    function get_customer_address_old() {
        //echo " check ".$this->brach_sel; die;
        $sql_ld = "SELECT 
                        ld.leadid as leadid,
                        ld.crd_id,
                        ld.industry_id,
                        ld.exporttype,
                        ld.presentsource,
                        ld.company as customer_id,
                        ad.city as cityname,
                        ad.state as statecode, 
                        st.statename,
                        ad.country as countrycode, 
                        ''::text as country,
                        ''::text as state,
                        ad.street as address  
                    FROM 
                            leaddetails ld
                        LEFT JOIN leadaddress ad ON ld.leadid = ad.leadaddressid
                        LEFT JOIN states st ON ad.state = st.statecode 
                        WHERE   
                            ld.company=" . $this->customer_id . " limit 1";

                

        $result = $this->db->query($sql_ld);
        //  echo "No of rowss ".$result->num_rows;
        if ($result->num_rows > 0) {
           //   echo "lead sql".$sql_ld; die;
            $address_array = $result->result_array();
            return $address_array;
        } else {
                $sql_master = "SELECT 
                            ''::text as leadid,
                            upper(dtl.city) as cityname, 
                            ''::text as statecode,
                            ''::text as countrycode ,
                            dtl.country,
                            dtl.state as statename,
                            dtl.address1 as address,
                            hdr.contact_persion as contact_person,
                            hdr.contact_no as contact_number,
                            hdr.contact_mailid as contact_mailid
                        FROM 
                            customermasterhdr hdr
                            LEFT JOIN customermasterdtl dtl ON dtl.id=hdr.id
                        WHERE 
                            hdr.id=" . $this->customer_id . "  AND dtl.addresstypeid  IN (SELECT DISTINCT addresstypeid FROM customermasterdtl  GROUP BY id,addresstypeid ) LIMIT 1";
              // echo "sql_master".$sql_master; die;
            $result1 = $this->db->query($sql_master);
            $customer_detail = $result1->result_array();
          //  echo "customer addressdlt ".$customer_detail[0]['address']."<br>";
            if ($result1->num_rows > 0 && $customer_detail[0]['address']!="")  // if address details is fetched from customermasterdtl table 
            {
                    $cityname = $customer_detail[0]['cityname'];
                    $country_name = $customer_detail[0]['country'];
                    $sql_country = "SELECT 
                    c.name,
                    upper(s.statecode) as statecode,
                    s.statename,
                    s.countrycode,
                    ct.cityname 
                    FROM 
                    country c
                    LEFT JOIN states s ON c.id = s.countrycode 
                    LEFT JOIN city ct ON s.statecode = ct.statecode 
                    WHERE 
                    upper(ct.cityname) like '" . $cityname . "%' AND  UPPER(c.name) like '" . $country_name . "%'";

           //  echo "sql_country ".$sql_country; die;
            $result2 = $this->db->query($sql_country);
            $address_array = $result2->result_array();
            $customer_detail[0]['statecode'] = $address_array[0]['statecode'];
            $customer_detail[0]['countrycode'] = $address_array[0]['countrycode'];
            $customer_detail[0]['statename'] = $address_array[0]['statename'];
           }
            else //  if there is no leads for a customer and no address record in the customermaster detail table
            {
                    $customer_detail[0]['statecode'] = "";
                    $customer_detail[0]['countrycode'] = "";
                    $customer_detail[0]['statename'] = "";
                    
            }

            return $customer_detail;
        }
    }

     function get_customer_address() 
     {
        //echo " check ".$this->brach_sel; die;
     
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
                            hdr.id=" . $this->customer_id . "  AND dtl.addresstypeid  IN (SELECT DISTINCT addresstypeid FROM customermasterdtl  GROUP BY id,addresstypeid ) LIMIT 1";
              // echo "sql_master".$sql_master; die;
            $result1 = $this->db->query($sql_master);
            $customer_detail = $result1->result_array();
          //  echo "customer addressdlt ".$customer_detail[0]['address']."<br>";
           /* if ($result1->num_rows > 0 && $customer_detail[0]['address']!="")  // if address details is fetched from customermasterdtl table 
            {
                    $cityname = $customer_detail[0]['cityname'];
                    $country_name = $customer_detail[0]['country'];
                    $sql_country = "SELECT 
                    c.name,
                    upper(s.statecode) as statecode,
                    s.statename,
                    s.countrycode,
                    ct.cityname 
                    FROM 
                    country c
                    LEFT JOIN states s ON c.id = s.countrycode 
                    LEFT JOIN city ct ON s.statecode = ct.statecode 
                    WHERE 
                    upper(ct.cityname) like '" . $cityname . "%' AND  UPPER(c.name) like '" . $country_name . "%'";

           //  echo "sql_country ".$sql_country; die;
            $result2 = $this->db->query($sql_country);
            $address_array = $result2->result_array();
            $customer_detail[0]['statecode'] = $address_array[0]['statecode'];
            $customer_detail[0]['countrycode'] = $address_array[0]['countrycode'];
            $customer_detail[0]['statename'] = $address_array[0]['statename'];
           }
            else //  if there is no leads for a customer and no address record in the customermaster detail table
            {
                    $customer_detail[0]['statecode'] = "";
                    $customer_detail[0]['countrycode'] = "";
                    $customer_detail[0]['statename'] = "";
                    
            }*/

            return $customer_detail;
        
    }

    function get_cities() {
        if (!is_null($this->state_id)) {
            $this->db->select('statecode, cityname');
            $this->db->where('statecode', $this->state_id);
            $cities = $this->db->get('city');

            // if there are suboptinos for this option...
            if ($cities->num_rows() > 0) {
                $cities_arr;

                // Format for passing into jQuery loop
                foreach ($cities->result() as $city) {
                    $cities_arr[$city->cityname] = $city->cityname;
                }
                return $cities_arr;
            }
        }
        return;
    }

    function save_lead($leaddata) {

        $this->db->insert('leaddetails', $leaddata);
        return $this->db->insert_id();
    }

    function save_lead_address($leadaddress) {
        $this->db->insert('leadaddress', $leadaddress);
        return $this->db->insert_id();
    }

    function save_lead_products_all($leadprods) {
        $this->db->insert('leadproducts', $leadprods);
        return $this->db->insert_id();
    }

    function save_lead_products($leadprods) {
        return $this->db->insert_batch('leadproducts', $leadprods);
    }

    function save_potential_update($potential_updated) {
        foreach ($potential_updated as $potential) 
        {
            //echo"in model<br>";  // echo"<pre>";print_r($prod);echo"</pre>"; //$this->db->insert_batch('leadproducts', $prod);
        }
        return $this->db->insert_batch('potential_updated_table', $potential_updated);
    }

// update functions 
    function update_lead($leaddata, $leadid) {

        $this->db->where('leadid', $leadid);
        $this->db->update('leaddetails', $leaddata);
        return ($this->db->affected_rows() > 0);
    }

    function update_leadclosestatus($leaddata, $leadid) {

        $this->db->where('leadid', $leadid);
        $this->db->update('leaddetails', $leaddata);
        return ($this->db->affected_rows() > 0);
    }

    function update_lead_status($leaddata, $leadid) {

        $this->db->where('leadid', $leadid);
        $this->db->update('leaddetails', $leaddata);
        return ($this->db->affected_rows() > 0);
    }

    function update_lead_address($leadaddress, $leadid) {
        $this->db->where('leadaddressid', $leadid);
        $this->db->update('leadaddress', $leadaddress);
        return ($this->db->affected_rows() > 0);
    }
     function update_custmastrhdr_addlead($customerhdr_email_contact,$customer_id) {
        
        $this->db->where('id', $customer_id);
        $this->db->update('customermasterhdr', $customerhdr_email_contact);
        return ($this->db->affected_rows() > 0);
    }

    function update_custmastrhdr_assignto($custmsthrdid, $customerid) {
        $updatedate = date('Y-m-d:H:i:s');
        $username = $this->session->userdata['username'];
        $userid = $this->session->userdata['user_id'];

        $sql = "update customermasterhdr t set executivename = k.aliasloginname,  execode =k.duser,lastupdatedate='" . $updatedate . "', lastupdateuser='" . $username . "' from (select duser,aliasloginname from 
     	vw_dusermaster where header_user_id= " . $custmsthrdid . ") k where coalesce(t.cust_account_id,0) = 0 and id=" . $customerid;

        $result = $this->db->query($sql);
    }



    function update_lead_products($leadprods, $leadid) {
        $prod = array();
        foreach ($leadprods as $prod) {
            $data = array(
                'productid' => $prod['productid'],
                'quantity' => $prod['quantity'],
                'potential' => $prod['potential'],
                'prod_type_id' => $prod['prod_type'],
                'last_modified' => $prod['last_modified'],
                'last_updated_user' => $prod['last_updated_user'],
                'annualpotential' => $prod['annualpotential']
            );

            $this->db->where('lpid', $prod['lpid']);
            $this->db->where('leadid', $leadid);
            $this->db->update('leadproducts', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    function update_lead_products_alltype($leadprods, $leadid) {
        $prod = array();
        foreach ($leadprods as $prod) {
            $data = array(
                'productid' => $prod['productid'],
                'quantity' => $prod['quantity'],
                'last_modified' => $prod['last_modified'],
                'last_updated_user' => $prod['last_updated_user']
            );

            $this->db->where('lpid', $prod['lpid']);
            $this->db->where('leadid', $leadid);
            $this->db->update('leadproducts', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    function update_leadcustomer_potential_update($customer_poten, $leadid) {
        $potential_update = array();
        foreach ($customer_poten as $potential_update) {
            $data = array(
                'yearly_potential_qty' => $potential_update['yearly_potential_qty'],
                'customer_number' => $potential_update['customer_number'],
                'customer_name' => $potential_update['customer_name'],
                'customergroup' => $potential_update['customergroup'],
                'user1' => $potential_update['user1'],
                'user_code' => $potential_update['user_code'],
                'itemgroup' => $potential_update['itemgroup'],
                'collector' => $potential_update['collector']
            );

            $this->db->where('id', $potential_update['id']);
            $this->db->where('line_id', $potential_update['line_id']);
            $this->db->where('upper(businesscategory)', $potential_update['businesscategory']);
            $this->db->update('potential_updated_table', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    function update_leadprodpotentypes($lead_potential_types, $leadid) {
        $potential_types = array();
        foreach ($lead_potential_types as $potential_types) {
            $data = array(
                'productid' => $potential_types['productid'],
                'product_type_id' => $potential_types['product_type_id'],
                'potential' => $potential_types['potential']
            );

            $this->db->where('product_type_id', $potential_types['product_type_id']);
            $this->db->where('productid', $potential_types['productid']);
            $this->db->where('leadid', $leadid);
            $this->db->update('lead_prod_potential_types', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    

    function update_customer_potential($customer_poten, $leadid) {
        $data = array(
            'customergroup' => $customer_poten[0]['customergroup'],
            'itemgroup' => $customer_poten[0]['itemgroup'],
            'yearly_potential_qty' => $customer_poten[0]['yearly_potential_qty'],
            'businesscategory' => $customer_poten[0]['businesscategory'],
            'collector' => $customer_poten[0]['collector'],
            'user1' => strtoupper($customer_poten[0]['user1']),
            'user_code' => $customer_poten[0]['user_code']
        );

        $this->db->where('line_id', $customer_poten[0]['line_id']);
        $this->db->where('id', $leadid);
        $this->db->update('potential_updated_table', $data);

        return ($this->db->affected_rows() > 0);
    }

    function potential_updated_table_collector($lead_branch) 
    {
       // echo"<pre> in model";print_r($lead_branch);echo"</pre>";
         foreach ($lead_branch as $lead_branchs) {
             $data = array(
            'collector' => $lead_branchs['user_branch'],
            'user1' => strtoupper($lead_branchs['user1']),
            'user_code' => $lead_branchs['user_code'],
            'id' => $lead_branchs['leadid']
        );

        $this->db->where('id', $data['id']);
        $this->db->update('potential_updated_table', $data);


         }
       

        return ($this->db->affected_rows() > 0);
    }

    function GetNextSeqVal($seq) {
        $query = "select nextval('" . $seq . "')";
        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            $row = $result->row();
        }

        return $row->nextval;
    }

    function GetCurrSeqVal($seq) {
        $query = "select currval('" . $seq . "')";
        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            $row = $result->row();
        }

        return $row->currval;
    }

    function GetMaxVal($table, $col) {
        $query = "select max($col) from $table";
        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            $row = $result->row();

        }

        return $row->max;
    }

    function get_lead_details_all() {

        /* $whereParts = array();
        if($branch)     { $whereParts[] = "leaddetails.user_branch ='$branch' "; }
        if($selectuserid) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
        if($assigntouserid) { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }
        if($statusid)  { $whereParts[] = "leaddetails.leadstatus = $statusid "; }
        if($substatusid)  { $whereParts[] = "leaddetails.ldsubstatus = $substatusid "; }
        if($customerid)  { $whereParts[] = "leaddetails.company = $customerid "; }
        if($productid)  { $whereParts[] = "leadproducts.productid::TEXT = '$productid'"; }
        if($fromdate)  { $whereParts[] = "leaddetails.createddate::DATE >= '$fromdate' "; }
        if($todate)  { $whereParts[] = "leaddetails.createddate::DATE <= '$todate' "; }*/
 
//BUILD THE QUERY
        $sql = 'SELECT
            leaddetails.leadid,
            leaddetails.lead_no,
            leaddetails.email_id,
            leaddetails.firstname,
            leaddetails.lastname,
            leaddetails.industry,
            leaddetails.website,
            leaddetails.user_branch,
            leaddetails.converted,
            leaddetails.designation,
            leaddetails.lead_crm_soc_no,
            leaddetails.lead_close_status,
            leaddetails.lead_close_option,
            leaddetails.lead_close_comments,
            leaddetails.comments,
            leaddetails.uploaded_date,
            leaddetails.description,
            leaddetails.ldsubstatus,
            leaddetails.secondaryemail,
            leaddetails.assignleadchk,
            leaddetails.createddate,
            leaddetails.leadstatus AS leadstatusid,
            leaddetails.leadsource AS leadsourceid,
            leadstatus.leadstatus,
            leadsubstatus.lst_name as substatusname,
            leadsource.leadsource,
            leaddetails.company,
            leaddetails.customer_id,
            leaddetails.created_user,
            leaddetails.last_modified,
            leaddetails.last_updated_user,
            leaddetails.sent_mail_alert,
            leaddetails.industry_id,
            leadproducts.productid,
            view_tempitemmaster.description AS productname,
            vw_web_user_login.empname as assign_from_name,
            assignedfrom.empname,
            view_tempcustomermaster.tempcustname
        FROM
            leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
            INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN "vw_web_user_login" AS assignedfrom ON "leaddetails"."assignleadchk" = "assignedfrom"."header_user_id"
            INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid';   
        /*if(count($whereParts)) {
             $sql .= " WHERE " . implode('AND ', $whereParts);
        }*/
        $sql .= ' AND leaddetails.converted=1 ORDER BY leadid DESC';    
        // echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
// echo"count of all leads ".count($productdetails); die;
        //$this->get_lead_converted_all(); commented by suresh for index_page loading slow
        $all_leads_count = count($productdetails);
        $this->session->set_userdata('all_leads_count', $all_leads_count);

        $jTableResult['leaddetails']=$productdetails;
        $data = array();

        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
                $lead_status_for_closed = "Closed";
            } else {
                $closed = "Open";
                $lead_status_for_closed = $jTableResult['leaddetails'][$i]["leadstatus"];
            }
            $row["leadstatus"] = $lead_status_for_closed;
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }";
        return $arr;
       // return $productdetails;
    }

    function get_lead_details_all_srch($branch=null,$selectuserid=0,$assigntouserid=0,$statusid=0,$substatusid=0,$customerid=0,$productid=0,$fromdate=null,$todate=null) 
        {

      /*  echo" branch ".$branch."<br>";
        echo" selectuserid ".$selectuserid."<br>";
        echo" assigntouserid ".$assigntouserid."<br>";
        echo" statusid ".$statusid."<br>";
        echo" substatusid ".$substatusid."<br>";
        echo" customerid ".$customerid."<br>";
        echo" productid ".$productid."<br>";
        echo" from_date ".$fromdate."<br>";
        echo" to_date ".$todate."<br>";*/
 
// changed for missing leads - conflict with the customermasterhdr
     //IF branch, selectuserid, OR statusid HAVE A VALUE, START THE WHERE CLAUSE
         
        $whereParts = array();
        if($branch)     { $whereParts[] = "leaddetails.user_branch ='$branch' "; }
        if($selectuserid) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
        if($assigntouserid) { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }
        if($statusid)  { $whereParts[] = "leaddetails.leadstatus = $statusid "; }
        if($substatusid)  { $whereParts[] = "leaddetails.ldsubstatus = $substatusid "; }
        if($customerid)  { $whereParts[] = "leaddetails.company = $customerid "; }
        if($productid)  { $whereParts[] = "leadproducts.productid::TEXT = '$productid'"; }
        if($fromdate)  { $whereParts[] = "leaddetails.createddate::DATE >= '$fromdate' "; }
        if($todate)  { $whereParts[] = "leaddetails.createddate::DATE <= '$todate' "; }
        //$whereParts[]="(createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to ";
 
//BUILD THE QUERY
          
            $sql='SELECT * FROM (
                    SELECT
                leaddetails.leadid,
                0::text as mc_sub_id,
                leaddetails.lead_no,
                leaddetails.email_id,
                leaddetails.firstname,
                leaddetails.lastname,
                leaddetails.industry,
                leaddetails.website,
                0::text as user_branch,
                leaddetails.converted,
                leaddetails.designation,
                leaddetails.lead_crm_soc_no,
                leaddetails.lead_close_status,
                leaddetails.lead_close_option,
                leaddetails.lead_close_comments,
                leaddetails.comments,
                leaddetails.uploaded_date,
                leaddetails.description,
                leaddetails.ldsubstatus,
                leaddetails.secondaryemail,
                leaddetails.assignleadchk,
                leaddetails.createddate,
                leaddetails.leadstatus AS leadstatusid,
                leaddetails.leadsource AS leadsourceid,
                leadstatus.leadstatus,
                leadsubstatus.lst_name AS substatusname,
                leadsource.leadsource,
                leaddetails.company,
                leaddetails.customer_id,
                leaddetails.created_user,
                leaddetails.last_modified,
                leaddetails.last_updated_user,
                leaddetails.sent_mail_alert,
                leaddetails.industry_id,
                leadproducts.productid,
                leadproducts.product_group,
                view_tempitemmaster.description AS productname,
                leadproducts.product_itemname,
                vw_web_user_login.empname AS assign_from_name,
                assignedfrom.empname,
                view_tempcustomermaster.tempcustname,
                get_acc_yr (
                    leaddetails.createddate :: DATE
                ) AS fin_yr,
                CASE
            WHEN (createddate :: DATE) BETWEEN jc_period_from
            AND jc_period_to THEN
                jc_code
            ELSE
                0
            END AS JCODE
            FROM
                leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
            INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN "vw_web_user_login" AS assignedfrom ON "leaddetails"."assignleadchk" = "assignedfrom"."header_user_id"
            INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid
            INNER JOIN customermasterhdr ON customermasterhdr.id = leaddetails.company  AND (cust_account_id is NULL  OR  cust_account_id =0 ) 

            INNER JOIN jc_calendar_dtl ON get_acc_yr (
                leaddetails.createddate :: DATE
            ) = jc_calendar_dtl.acc_yr
            WHERE
             (createddate :: DATE) BETWEEN jc_period_from
            AND jc_period_to


            UNION

            SELECT
                leaddetails.leadid,
              market_circle_hdr.mc_sub_id,
                leaddetails.lead_no,
                leaddetails.email_id,
                leaddetails.firstname,
                leaddetails.lastname,
                leaddetails.industry,
                leaddetails.website,
                ar_collectors.name as user_branch,
                leaddetails.converted,
                leaddetails.designation,
                leaddetails.lead_crm_soc_no,
                leaddetails.lead_close_status,
                leaddetails.lead_close_option,
                leaddetails.lead_close_comments,
                leaddetails.comments,
                leaddetails.uploaded_date,
                leaddetails.description,
                leaddetails.ldsubstatus,
                leaddetails.secondaryemail,
                leaddetails.assignleadchk,
                leaddetails.createddate,
                leaddetails.leadstatus AS leadstatusid,
                leaddetails.leadsource AS leadsourceid,
                leadstatus.leadstatus,
                leadsubstatus.lst_name AS substatusname,
                leadsource.leadsource,
                leaddetails.company,
                leaddetails.customer_id,
                leaddetails.created_user,
                leaddetails.last_modified,
                leaddetails.last_updated_user,
                leaddetails.sent_mail_alert,
                leaddetails.industry_id,
                leadproducts.productid,
                leadproducts.product_group,
                view_tempitemmaster.description AS productname,
                leadproducts.product_itemname,
                vw_web_user_login.empname AS assign_from_name,
                assignedfrom.empname,
                view_tempcustomermaster.tempcustname,
                get_acc_yr (
                    leaddetails.createddate :: DATE
                ) AS fin_yr,
                CASE
            WHEN (createddate :: DATE) BETWEEN jc_period_from
            AND jc_period_to THEN
                jc_code
            ELSE
                0
            END AS JCODE
            FROM
                leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
            INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN "vw_web_user_login" AS assignedfrom ON "leaddetails"."assignleadchk" = "assignedfrom"."header_user_id"
            INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid

             INNER JOIN customermasterhdr ON customermasterhdr.id = leaddetails.company
             INNER JOIN market_circle_hdr ON market_circle_hdr.mc_sub_id=customermasterhdr.mc_code
             INNER JOIN ar_collectors ON ar_collectors.name=customermasterhdr.collector  

            INNER JOIN jc_calendar_dtl ON get_acc_yr (
                leaddetails.createddate :: DATE
            ) = jc_calendar_dtl.acc_yr
            WHERE
             (createddate :: DATE) BETWEEN jc_period_from  AND jc_period_to ) leaddetails';

        if(count($whereParts)) {
            // $sql .= " WHERE " . implode('AND ', $whereParts);
             $sql .= " WHERE " . implode('AND ', $whereParts);
        }
        $sql .= ' AND converted=0 ORDER BY leadid ASC'; 
  //    echo $sql; die;
         $result = $this->db->query($sql);
        $productdetails = $result->result_array();
// echo"count of all leads ".count($productdetails); die;
       // $this->get_lead_converted_all();
        $all_leads_count = count($productdetails); echo"<br>";
        $this->session->set_userdata('all_leads_count', $all_leads_count);
        //return $productdetails;
        $jTableResult['leaddetails']=$productdetails;
        $data = array();
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["statusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
            $row["substatusid"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
                $lead_status_for_closed = "Closed";
            } else {
                $closed = "Open";
                $lead_status_for_closed = $jTableResult['leaddetails'][$i]["leadstatus"];
            }
            if($jTableResult['leaddetails'][$i]["mc_sub_id"]=="0")
            {
                $row["mc_sub_id"] = "New Customer";
            }
            else
            {
                $row["mc_sub_id"] = $jTableResult['leaddetails'][$i]["mc_sub_id"];
            }
            $row["leadstatus"] = $lead_status_for_closed;
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];

            $row["productname"] = (empty($jTableResult['leaddetails'][$i]["product_itemname"])) ? $jTableResult['leaddetails'][$i]["productname"] : $jTableResult['leaddetails'][$i]["product_itemname"];
           
            
           // $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            
            $row["product_group"] = $jTableResult['leaddetails'][$i]["product_group"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            //$row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];

            if($jTableResult['leaddetails'][$i]["user_branch"]=="0")
            {
                $row["branch"] = "NEW CUSTOMER";
            }
            else
            {
                $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            }

            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }
            $row["fin_yr"] = $jTableResult['leaddetails'][$i]["fin_yr"];
            $row["jcode"] = "JC".$jTableResult['leaddetails'][$i]["jcode"];

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }";
        return $arr;
    }

    

    

    function get_lead_converted_all() {
        /* $whereParts = array();
        if($branch)     { $whereParts[] = "leaddetails.user_branch ='$branch' "; }
        if($selectuserid) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
        if($assigntouserid) { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }
        if($statusid)  { $whereParts[] = "leaddetails.leadstatus = $statusid "; }
        if($substatusid)  { $whereParts[] = "leaddetails.ldsubstatus = $substatusid "; }
        if($customerid)  { $whereParts[] = "leaddetails.company = $customerid "; }
        if($productid)  { $whereParts[] = "leadproducts.productid::TEXT = '$productid'"; }
        if($fromdate)  { $whereParts[] = "leaddetails.createddate::DATE >= '$fromdate' "; }
        if($todate)  { $whereParts[] = "leaddetails.createddate::DATE <= '$todate' "; }*/
 
//BUILD THE QUERY
        $sql = 'SELECT
            leaddetails.leadid,
            leaddetails.lead_no,
            leaddetails.email_id,
            leaddetails.firstname,
            leaddetails.lastname,
            leaddetails.industry,
            leaddetails.website,
            leaddetails.user_branch,
            leaddetails.converted,
            leaddetails.designation,
            leaddetails.lead_crm_soc_no,
            leaddetails.lead_close_status,
            leaddetails.lead_close_option,
            leaddetails.lead_close_comments,
            leaddetails.comments,
            leaddetails.uploaded_date,
            leaddetails.description,
            leaddetails.ldsubstatus,
            leaddetails.secondaryemail,
            leaddetails.assignleadchk,
            leaddetails.createddate,
            leaddetails.leadstatus AS leadstatusid,
            leaddetails.leadsource AS leadsourceid,
            leadstatus.leadstatus,
            leadsubstatus.lst_name as substatusname,
            leadsource.leadsource,
            leaddetails.company,
            leaddetails.customer_id,
            leaddetails.created_user,
            leaddetails.last_modified,
            leaddetails.last_updated_user,
            leaddetails.sent_mail_alert,
            leaddetails.industry_id,
            leadproducts.productid,
            view_tempitemmaster.description AS productname,
            vw_web_user_login.empname as assign_from_name,
            assignedfrom.empname,
            view_tempcustomermaster.tempcustname
        FROM
            leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
            INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
            LEFT OUTER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            LEFT OUTER JOIN "vw_web_user_login" AS assignedfrom ON "leaddetails"."assignleadchk" = "assignedfrom"."header_user_id"
            INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid';   
        /*if(count($whereParts)) {
             $sql .= " WHERE " . implode('AND ', $whereParts);
        }*/
        $sql .= ' AND leaddetails.converted=1 ORDER BY leadid DESC';      
 
   //echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        $all_leads_count = count($productdetails);
        $this->session->set_userdata('all_leads_converted_count', $all_leads_count); // added by perusu
        return $productdetails;
    }

    function get_lead_converted_all_new() {
        $sql = 'SELECT * from leaddetails WHERE converted = 1';
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        $all_leads_count = count($productdetails);
        $this->session->set_userdata('all_leads_converted_count', $all_leads_count); // added by perusu
        return $productdetails;
    }


    function get_lead_details($id) {

        $this->get_lead_details_converted($id);
        global $reportingid;

        $reportingid = $this->session->userdata['reportingto'];
       // $reportingid = $this->session->userdata['loginname'];

        //$user_list_ids = $this->get_user_list_ids($reportingid);
        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];

        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //set it
        //$this->session->set_userdata($get_assign_to_user_id);

        $sql = 'SELECT
        			leaddetails.leadid,
        			leaddetails.lead_no,
        			leaddetails.email_id,
        			leaddetails.firstname,
        			leaddetails.lastname,
        			leaddetails.industry,
        			leaddetails.website,
        			leaddetails.user_branch,
        			leaddetails.converted,
        			leaddetails.designation,
        			leaddetails.lead_crm_soc_no,
        			leaddetails.lead_close_status,
        			leaddetails.lead_close_option,
        			leaddetails.lead_close_comments,
        			leaddetails.comments,
        			leaddetails.domestic_supplier_name,
        			leaddetails.uploaded_date,
        			leaddetails.description,
        			leaddetails.ldsubstatus,
        			leaddetails.secondaryemail,
        			leaddetails.assignleadchk,
        			leaddetails.createddate,
        			leaddetails.leadstatus AS leadstatusid,
        			leaddetails.leadsource AS leadsourceid,
        			leadstatus.leadstatus,
        			leadsubstatus.lst_name as substatusname,
        			leadsource.leadsource,
        			leaddetails.company,
        			leaddetails.customer_id,
        			leaddetails.created_user,
        			leaddetails.last_modified,
        			leaddetails.last_updated_user,
        			leaddetails.sent_mail_alert,
        			leaddetails.industry_id,
        			leadproducts.productid,
        			view_tempitemmaster.description AS productname,
        			vw_web_user_login.empname as assign_from_name,
        			assignedfrom.empname,
        			view_tempcustomermaster.tempcustname
		      FROM
			     "leaddetails"
                    INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
                    INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
                    INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
                    LEFT JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
                    INNER JOIN "vw_web_user_login" AS assignedfrom ON leaddetails.assignleadchk = "assignedfrom"."header_user_id"
                    INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
                    INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
                    INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid
		
		      WHERE
                  "leaddetails"."leadid" IN (
            SELECT  leadid from leaddetails WHERE created_user IN (' . $user_list_ids . ')
            OR  assignleadchk in (' . $user_list_ids . ') ) AND leaddetails.converted=0 order by leadid desc';
        //echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();

        $user_leads_count = count($productdetails);
        $this->session->set_userdata('user_leads_count', $user_leads_count);
        $jTableResult['leaddetails']=$productdetails;
        $data = array();
        //  print_r($jTableResult['leaddetails'] );
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];
            $row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
            } else {
                $closed = "Open";
            }
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["soc_number"] = $jTableResult['leaddetails'][$i]["lead_crm_soc_no"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                //  echo "in if"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                //  echo "in else"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }"; die;
        return $arr;
       // return $productdetails;
    }

    public function get_lead_details_converted($id) {
          global $reportingid;

        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //put list of id's into an array
        $this->session->set_userdata($get_assign_to_user_id); // set the list of ids in a session variable

         
       /* $whereParts = array();
        if($branch)     { $whereParts[] = "leaddetails.user_branch ='$branch' "; }
        if($selectuserid) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
        if($assigntouserid) { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }
        if($statusid)  { $whereParts[] = "leaddetails.leadstatus = $statusid "; }
        if($substatusid)  { $whereParts[] = "leaddetails.ldsubstatus = $substatusid "; }
        if($customerid)  { $whereParts[] = "leaddetails.customer_id = $customerid "; }
        if($productid)  { $whereParts[] = "leadproducts.productid::TEXT = '$productid'"; }
        if($fromdate)  { $whereParts[] = "leaddetails.createddate::DATE >= '$fromdate' "; }
        if($todate)  { $whereParts[] = "leaddetails.createddate::DATE <= '$todate' "; }*/
       // echo"<pre>";print_r($whereParts); echo"</pre>";
//BUILD THE QUERY
        $sql = 'SELECT
            leaddetails.leadid,
            leaddetails.lead_no,
            leaddetails.email_id,
            leaddetails.firstname,
            leaddetails.lastname,
            leaddetails.industry,
            leaddetails.website,
            leaddetails.user_branch,
            leaddetails.converted,
            leaddetails.designation,
            leaddetails.lead_crm_soc_no,
            leaddetails.lead_close_status,
            leaddetails.lead_close_option,
            leaddetails.lead_close_comments,
            leaddetails.comments,
            leaddetails.uploaded_date,
            leaddetails.description,
            leaddetails.ldsubstatus,
            leaddetails.secondaryemail,
            leaddetails.assignleadchk,
            leaddetails.createddate,
            leaddetails.leadstatus AS leadstatusid,
            leaddetails.leadsource AS leadsourceid,
            leadstatus.leadstatus,
            leadsubstatus.lst_name as substatusname,
            leadsource.leadsource,
            leaddetails.company,
            leaddetails.customer_id,
            leaddetails.created_user,
            leaddetails.last_modified,
            leaddetails.last_updated_user,
            leaddetails.sent_mail_alert,
            leaddetails.industry_id,
            leadproducts.productid,
            view_tempitemmaster.description AS productname,
            vw_web_user_login.empname as assign_from_name,
            assignedfrom.empname,
            view_tempcustomermaster.tempcustname
        FROM
            leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN "leadstatus" ON "leadstatus"."leadstatusid" = "leaddetails"."leadstatus"
            INNER JOIN "leadsource" ON "leadsource"."leadsourceid" = "leaddetails"."leadsource"
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN "vw_dusermaster" AS assignedfrom ON "leaddetails"."assignleadchk" = "assignedfrom"."header_user_id"
            INNER JOIN "view_tempcustomermaster" ON "leaddetails"."company" = "view_tempcustomermaster"."id"
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid  WHERE leaddetails.leadid IN (
            SELECT  leadid from leaddetails WHERE created_user IN ('.$user_list_ids.')
            OR  assignleadchk in ('.$user_list_ids.')) AND leaddetails.converted=1';   
       /* if(count($whereParts)) {
             $sql .= " AND " . implode('AND ', $whereParts);
           //  $sql .= "WHERE" . implode('AND ', $whereParts);
        }*/

        $sql .= " ORDER BY leadid DESC";
      
        //echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();

        $user_leads_count = count($productdetails);
        //	$this->session->set_userdata('user_leads_count',$user_leads_count);
        $this->session->set_userdata('user_leads_converted_count', $user_leads_count); // added by perusu
        return $productdetails;
    }

    function get_lead_details_converted_new($id) {
        //echo"test ";die;
        global $reportingid;

        //	$reportingid = $this->session->userdata['reportingto'];
        $reportingid = $this->session->userdata['loginname'];
        //$user_list_ids = $this->get_user_list_ids($reportingid);
        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //set it
        $this->session->set_userdata($get_assign_to_user_id);
        $sql = 'SELECT  leadid from leaddetails WHERE converted =1 AND  (created_user IN (' . $user_list_ids . ') OR '
                . 'assignleadchk IN(' . $user_list_ids . ') )';
        // echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();

        $user_leads_count = count($productdetails);
        //	$this->session->set_userdata('user_leads_count',$user_leads_count);
        $this->session->set_userdata('user_leads_converted_count', $user_leads_count); // added by perusu
        return $productdetails;
    }
    function get_lead_details_srch($branch=null,$selectmc_sub_id=0,$assigntouserid=0,$statusid=0,$substatusid=0,$customerid=0,$productid=0,$fromdate=null,$todate=null,$id)
        {
        $reportingid = $this->session->userdata['loginname'];
        //echo"b".$user_list_ids = $this->get_user_list_ids($reportingid); replaced by SELECT get_hierarchical_user_id on 5-mar-2015
        //$user_list_ids = $this->get_reportingto_ids($reportingid);
        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //put list of id's into an array
        $this->session->set_userdata($get_assign_to_user_id); // set the list of ids in a session variable

         
        $whereParts = array();
        //if($branch)     { $whereParts[] = "leaddetails.user_branch ='$branch' "; }
        //if($selectuserid) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
        if($assigntouserid) { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }
        if($statusid)  { $whereParts[] = "leaddetails.leadstatus = $statusid "; }
        if($substatusid)  { $whereParts[] = "leaddetails.ldsubstatus = $substatusid "; }
        if($customerid)  { $whereParts[] = "leaddetails.customer_id = $customerid "; }
        if($productid)  { $whereParts[] = "leadproducts.productid::TEXT = '$productid'"; }
        if($fromdate)  { $whereParts[] = "leaddetails.createddate::DATE >= '$fromdate' "; }
        if($todate)  { $whereParts[] = "leaddetails.createddate::DATE <= '$todate' "; }
        //if($selectmc_sub_id) { $whereParts[] = "leaddetails.mc_sub_id = '$selectmc_sub_id' "; }
      //  $whereParts[]="(createddate::DATE )  BETWEEN  jc_period_from and  jc_period_to ";
      // echo"<pre>";print_r($whereParts); echo"</pre>";
//BUILD THE QUERY
      
            $sql = 'SELECT * FROM ( SELECT
                leaddetails.leadid,
                0::text as mc_sub_id,
                leaddetails.lead_no,
                leaddetails.email_id,
                leaddetails.firstname,
                leaddetails.lastname,
                leaddetails.industry,
                leaddetails.website,
                0::text as user_branch,
                leaddetails.converted,
                leaddetails.designation,
                leaddetails.lead_crm_soc_no,
                leaddetails.lead_close_status,
                leaddetails.lead_close_option,
                leaddetails.lead_close_comments,
                leaddetails.comments,
                leaddetails.uploaded_date,
                leaddetails.description,
                leaddetails.ldsubstatus,
                leaddetails.secondaryemail,
                leaddetails.assignleadchk,
                leaddetails.createddate,
                leaddetails.leadstatus AS leadstatusid,
                leaddetails.leadsource AS leadsourceid,
                leadstatus.leadstatus,
                leadsubstatus.lst_name AS substatusname,
                leadsource.leadsource,
                leaddetails.company,
                leaddetails.customer_id,
                leaddetails.created_user,
                leaddetails.last_modified,
                leaddetails.last_updated_user,
                leaddetails.sent_mail_alert,
                leaddetails.industry_id,
                leadproducts.productid,
                leadproducts.product_group,
                view_tempitemmaster.description AS productname,
                leadproducts.product_itemname,
                vw_web_user_login.empname AS assign_from_name,
                assignedfrom.empname,
                view_tempcustomermaster.tempcustname,
                get_acc_yr (
                    leaddetails.createddate :: DATE
                ) AS fin_yr,
                CASE
            WHEN (createddate :: DATE) BETWEEN jc_period_from
            AND jc_period_to THEN
                jc_code
            ELSE
                0
            END AS JCODE
            FROM
                leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN leadstatus ON leadstatus.leadstatusid = leaddetails.leadstatus
            INNER JOIN leadsource ON leadsource.leadsourceid = leaddetails.leadsource
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN vw_web_user_login AS assignedfrom ON leaddetails.assignleadchk = assignedfrom.header_user_id
            INNER JOIN view_tempcustomermaster ON leaddetails.company = view_tempcustomermaster.id
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid
            INNER JOIN customermasterhdr ON customermasterhdr.id = leaddetails.company  AND (cust_account_id is NULL  OR  cust_account_id =0 ) 

            INNER JOIN jc_calendar_dtl ON get_acc_yr (
                leaddetails.createddate :: DATE
            ) = jc_calendar_dtl.acc_yr
            WHERE
                leaddetails.leadid IN (
                    SELECT
                        leadid
                    FROM
                        leaddetails
                    WHERE
                        assignleadchk IN ('.$user_list_ids.')
                )
            AND (createddate :: DATE) BETWEEN jc_period_from AND jc_period_to  AND converted = 0


            UNION

            SELECT
                leaddetails.leadid,
                market_circle_hdr.mc_sub_id,
                leaddetails.lead_no,
                leaddetails.email_id,
                leaddetails.firstname,
                leaddetails.lastname,
                leaddetails.industry,
                leaddetails.website,
                ar_collectors.name as user_branch,
                leaddetails.converted,
                leaddetails.designation,
                leaddetails.lead_crm_soc_no,
                leaddetails.lead_close_status,
                leaddetails.lead_close_option,
                leaddetails.lead_close_comments,
                leaddetails.comments,
                leaddetails.uploaded_date,
                leaddetails.description,
                leaddetails.ldsubstatus,
                leaddetails.secondaryemail,
                leaddetails.assignleadchk,
                leaddetails.createddate,
                leaddetails.leadstatus AS leadstatusid,
                leaddetails.leadsource AS leadsourceid,
                leadstatus.leadstatus,
                leadsubstatus.lst_name AS substatusname,
                leadsource.leadsource,
                leaddetails.company,
                leaddetails.customer_id,
                leaddetails.created_user,
                leaddetails.last_modified,
                leaddetails.last_updated_user,
                leaddetails.sent_mail_alert,
                leaddetails.industry_id,
                leadproducts.productid,
                leadproducts.product_group,
                view_tempitemmaster.description AS productname,
                leadproducts.product_itemname,
                vw_web_user_login.empname AS assign_from_name,
                assignedfrom.empname,
                view_tempcustomermaster.tempcustname,
                get_acc_yr (
                    leaddetails.createddate :: DATE
                ) AS fin_yr,
                CASE
            WHEN (createddate :: DATE) BETWEEN jc_period_from
            AND jc_period_to THEN
                jc_code
            ELSE
                0
            END AS JCODE
            FROM
                leaddetails
            INNER JOIN leadsubstatus ON leadsubstatus.lst_sub_id = leaddetails.ldsubstatus
            INNER JOIN leadstatus ON leadstatus.leadstatusid = leaddetails.leadstatus
            INNER JOIN leadsource ON leadsource.leadsourceid = leaddetails.leadsource
            INNER JOIN vw_web_user_login ON leaddetails.created_user = vw_web_user_login.header_user_id
            INNER JOIN vw_web_user_login AS assignedfrom ON leaddetails.assignleadchk = assignedfrom.header_user_id
            INNER JOIN view_tempcustomermaster ON leaddetails.company = view_tempcustomermaster.id
            INNER JOIN leadproducts ON leadproducts.leadid = leaddetails.leadid
            INNER JOIN view_tempitemmaster ON view_tempitemmaster. ID = leadproducts.productid

             INNER JOIN customermasterhdr ON customermasterhdr.id = leaddetails.company
             INNER JOIN market_circle_hdr ON market_circle_hdr.mc_sub_id=customermasterhdr.mc_code
             INNER JOIN ar_collectors ON ar_collectors.name=customermasterhdr.collector  

            INNER JOIN jc_calendar_dtl ON get_acc_yr (leaddetails.createddate :: DATE ) = jc_calendar_dtl.acc_yr
            WHERE  mc_sub_id IN (SELECT mc_sub_id  FROM market_circle_hdr WHERE gc_executive_code in ('.$user_list_ids.')

                        ) AND (createddate :: DATE) BETWEEN jc_period_from AND jc_period_to  AND converted = 0 ) leaddetails'; 



        if(count($whereParts)) {
             $sql .= " WHERE " . implode('AND ', $whereParts);
           //  $sql .= "WHERE" . implode('AND ', $whereParts);
        }
        
        if($selectmc_sub_id!="")
        {
            $sql .= " AND mc_sub_id='".$selectmc_sub_id."'";
        }
           $sql .= ' ORDER BY leadid ASC'; 
       
     
       //echo "sql is ".$sql."<br>"; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        $all_leads_count = count($productdetails); 
        $this->session->set_userdata('all_leads_count', $all_leads_count);
        //return $productdetails;
        $jTableResult['leaddetails']=$productdetails;
        $data = array();
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["statusid"] = $jTableResult['leaddetails'][$i]["leadstatusid"];
            $row["substatusid"] = $jTableResult['leaddetails'][$i]["ldsubstatus"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];
            if($jTableResult['leaddetails'][$i]["mc_sub_id"]=="0")
            {
                $row["mc_sub_id"] = "New Customer";
            }
            else
            {
                $row["mc_sub_id"] = $jTableResult['leaddetails'][$i]["mc_sub_id"];
            }
            

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
                $lead_status_for_closed = "Closed";
            } else {
                $closed = "Open";
                $lead_status_for_closed = $jTableResult['leaddetails'][$i]["leadstatus"];
            }
            $row["leadstatus"] = $lead_status_for_closed;
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            
            $row["productname"] = (empty($jTableResult['leaddetails'][$i]["product_itemname"])) ? $jTableResult['leaddetails'][$i]["productname"] : $jTableResult['leaddetails'][$i]["product_itemname"];
            //$row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["product_group"] = $jTableResult['leaddetails'][$i]["product_group"];
           // $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];

            if($jTableResult['leaddetails'][$i]["user_branch"]=="0")
            {
                $row["branch"] = "NEW CUSTOMER";
            }
            else
            {
                $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            }



            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }
            $row["fin_yr"] = $jTableResult['leaddetails'][$i]["fin_yr"];
            $row["jcode"] = "JC".$jTableResult['leaddetails'][$i]["jcode"];
            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }";
        return $arr;
        
    }



    function get_leaddetails_reporting_to_for_grid($id) {

        $jTableResult = array();
        $jTableResult['leaddetails'] = $this->get_lead_details($id);
        $data = array();
        //	print_r($jTableResult['leaddetails'] );
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];
            $row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
            $row["substatusname"] = $jTableResult['leaddetails'][$i]["substatusname"];

            if ($jTableResult['leaddetails'][$i]["lead_close_status"] == 1) {
                $closed = "Closed";
            } else {
                $closed = "Open";
            }
            $row["lead_close_status"] = $closed;
            $row["lead_close_option"] = $jTableResult['leaddetails'][$i]["lead_close_option"];
            $row["lead_close_comments"] = $jTableResult['leaddetails'][$i]["lead_close_comments"];
            $row["productid"] = $jTableResult['leaddetails'][$i]["productid"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["soc_number"] = $jTableResult['leaddetails'][$i]["lead_crm_soc_no"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                //	echo "in if"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                //	echo "in else"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //	echo "{ rows: ".$arr." }"; die;
        return $arr;
    }

    function get_converted_reporting_to_for_grid($id) {

        $jTableResult = array();
        $jTableResult['leaddetails'] = $this->get_lead_details_converted($id);
        $data = array();
        $i = 0;
        while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["leadid"] = $jTableResult['leaddetails'][$i]["leadid"];
            $row["lead_no"] = $row["leadid"] . "-" . $jTableResult['leaddetails'][$i]["lead_no"];
            $row["leadstatus"] = $jTableResult['leaddetails'][$i]["leadstatus"];
            $row["branch"] = $jTableResult['leaddetails'][$i]["user_branch"];
            $row["soc_number"] = $jTableResult['leaddetails'][$i]["lead_crm_soc_no"];
            $row["leadsource"] = $jTableResult['leaddetails'][$i]["leadsource"];
            $row["productname"] = $jTableResult['leaddetails'][$i]["productname"];
            $row["assign_from_name"] = $jTableResult['leaddetails'][$i]["assign_from_name"];
            $row["tempcustname"] = $jTableResult['leaddetails'][$i]["tempcustname"];
            $row["empname"] = $jTableResult['leaddetails'][$i]["empname"];
            $row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
            $date_cr = new DateTime($row["created_date"]);
            $row["created_date"] = $date_cr->format('Y-m-d');
            $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);

            if ($row["modified_date"] == "") {
                //	echo "in if"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
                $date_mf = new DateTime($row["created_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            } else {
                //	echo "in else"; 
                $row["modified_date"] = substr($jTableResult['leaddetails'][$i]["last_modified"], 0, -8);
                $date_mf = new DateTime($row["modified_date"]);
                $row["modified_date"] = $date_mf->format('Y-m-d');
            }

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //	echo "{ rows: ".$arr." }"; die;
        return $arr;
    }

    function get_lead_edit_details($id) {

        $reportingid_edit = $this->session->userdata['reportingto'];
        $user_list_id_edit = $this->get_user_list_ids($reportingid_edit);
        //print_r($this->session->userdata['get_assign_to_user_id']); 
        //$user_list_id_edit = $this->session->userdata['get_assign_to_user_id'];
        $this->db->select('*');
        $this->db->from('leaddetails');
        $this->db->join('leadstatus', 'leadstatus.leadstatusid = leaddetails.leadstatus', 'inner');
        $this->db->join('leadsubstatus', 'leadsubstatus.lst_sub_id = leaddetails.ldsubstatus', 'inner');
        $this->db->join('leadsource', 'leadsource.leadsourceid= leaddetails.leadsource', 'inner');
        $this->db->join('industry_segment', 'industry_segment.id= leaddetails.industry_id', 'inner');
        $this->db->join('vw_web_user_login', 'leaddetails.assignleadchk = vw_web_user_login.header_user_id', 'inner');
        //$this->db->join('leadaddress', 'leaddetails.leadid = leadaddress.leadaddressid', 'inner');
        $this->db->join('view_tempcustomermaster', 'leaddetails.company = view_tempcustomermaster.id', 'LEFT OUTER');
        $this->db->where('leaddetails.leadid', $id);
        $result = $this->db->get();
        $productdetails = $result->result_array();
        return $productdetails;
    }

    function get_lead_edit_details_all($id) {

        $this->db->select('*');
        $this->db->from('leaddetails');
        $this->db->join('leadstatus', 'leadstatus.leadstatusid = leaddetails.leadstatus', 'inner');
        $this->db->join('leadsubstatus', 'leadsubstatus.lst_sub_id = leaddetails.ldsubstatus', 'inner');
        $this->db->join('leadsource', 'leadsource.leadsourceid= leaddetails.leadsource', 'inner');
        $this->db->join('industry_segment', 'industry_segment.id= leaddetails.industry_id', 'inner');
        $this->db->join('view_tempcustomermaster', 'leaddetails.company = view_tempcustomermaster.id', 'inner');
        $this->db->join('vw_web_user_login', 'leaddetails.assignleadchk = vw_web_user_login.header_user_id', 'left outer');
        //$this->db->join('leadaddress', 'leaddetails.leadid = leadaddress.leadaddressid', 'inner');
        $this->db->where('leaddetails.leadid', $id);
        $result = $this->db->get();
        $productdetails = $result->result_array();
        return $productdetails;
    }

/*    function get_lead_product_details_old($id) {
              $sql = "SELECT  
					(select description from view_tempitemmaster j WHERE j.id = lp.productid) as description
					,(select id from view_tempitemmaster j WHERE j.id = lp.productid) as productid
					,(select n_value_displayname FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as n_value
					,(select n_value_id FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as prod_type_id
					,( SELECT quantity from leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as quantity
					,( SELECT lpid from leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as lpid
					, potential 
									FROM
										lead_prod_potential_types lp

						WHERE       lp.leadid=" . $id;
        echo $sql;die;

        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        return $productdetails;
    }*/

    function get_lead_product_details($id) {
              $sql = "select  * from  (
                       SELECT  (select description from view_tempitemmaster j WHERE j.id = lp.productid) as description ,
                    (select id from view_tempitemmaster j WHERE j.id = lp.productid) as productid ,
                    (select n_value_displayname FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as n_value ,
                    (select n_value_id FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as prod_type_id ,
                    ( SELECT quantity from leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as quantity ,
                    ( SELECT lpid from leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as lpid , potential
                    ,CASE WHEN product_type_id IN (4, 5, 6) THEN 'I' ELSE 'R' END AS sal_flag 
                     FROM lead_prod_potential_types lp WHERE lp.leadid=".$id."
                ) g where  sal_flag  in (select  sales_type_flag from leaddetails where leadid=". $id.")";
        //echo $sql;die;

        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        return $productdetails;
    }


    function get_lead_product_details_alltypes($id) {


        $sql = "select 
			a.leadid,a.productid,a.quantity,a.lpid,a.potential as potential_old,a.annualpotential,a.added_in_lead,a.due_date,a.discussion_points,a.market_information,b.product_type_id as prod_type_id,b.potential

			FROM
			 leadproducts a,lead_prod_potential_types b
			  where a.leadid = b.leadid and b.leadid =" . $id;

        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        return $productdetails;
    }


    function get_productname($productid) {
        $this->db->select('*');
        $this->db->from('view_tempitemmaster_grp');
        $this->db->where('id', $productid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['itemgroup'];
    }

    /*function get_lead_product_details_view_detail($id) {
        $sql = "SELECT  
						(select description from view_tempitemmaster j WHERE j.id = lp.productid) as description
						,(select n_value_displayname FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as n_value
						,( SELECT quantity from leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as quantity
						, potential 
				FROM
					lead_prod_potential_types lp

				WHERE       lp.leadid=" . $id;

        echo $sql; die;
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        return $productdetails;
    }*/

    function get_lead_product_details_view_detail($id) {
      /*  $sql = "SELECT  
                    * 
                    FROM  (
                        SELECT 
                            (SELECT description FROM view_tempitemmaster j WHERE j.id = lp.productid) as description ,
                            (SELECT n_value_displayname FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as n_value ,
                            (SELECT quantity FROM leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as quantity , potential, CASE WHEN product_type_id IN (4, 5, 6) THEN 'I' ELSE 'R' END AS sal_flag 
                            FROM 
                            lead_prod_potential_types lp 
                            WHERE lp.leadid=". $id."
                            )  g WHERE  sal_flag  in (SELECT  sales_type_flag FROM leaddetails WHERE leadid=". $id."
                        )";*/

         $sql = "SELECT  
                    * 
                    FROM  (
                        SELECT 
                            (SELECT description FROM view_tempitemmaster_grp j WHERE j.id = lp.productid) as description ,
                            (SELECT itemgroup FROM view_tempitemmaster_grp j WHERE j.id = lp.productid) as itemgroup , 
                            (SELECT product_itemname FROM leadproducts  p WHERE p.leadid = lp.leadid) as product_item_name ,
                            (SELECT n_value_displayname FROM lead_sale_type j WHERE j.n_value_id = lp.product_type_id ) as n_value ,
                            (SELECT quantity FROM leadproducts j WHERE j.leadid = lp.leadid and lp .productid = j.productid ) as quantity , potential, CASE WHEN product_type_id IN (4, 5, 6) THEN 'I' ELSE 'R' END AS sal_flag 
                            FROM 
                            lead_prod_potential_types lp 
                            WHERE lp.leadid=". $id."
                            )  g WHERE  sal_flag  in (SELECT  sales_type_flag FROM leaddetails WHERE leadid=". $id."
                        )";

     //   echo $sql; die;

                 
        $result = $this->db->query($sql);
        $productdetails = $result->result_array();
        return $productdetails;
    }

    function GetLeadSourceVal($srcid) {
        $this->db->select('lead_src_displayname');
        $this->db->from('leadsource');
        $this->db->where('leadsourceid', $srcid);
        $result = $this->db->get();
        $ld_src = $result->result_array();
        return $ld_src[0]['lead_src_displayname'];
        // print_r($ld_src);
    }

    function GetLeadSourceName($srcid) {
        $this->db->select('leadsource');
        $this->db->from('leadsource');
        $this->db->where('leadsourceid', $srcid);
        $result = $this->db->get();
        $ld_src = $result->result_array();
        return $ld_src[0]['leadsource'];
        // print_r($ld_src);
    }

    function GetLeadCredit($srcid) {
        $this->db->select('crd_name');
        $this->db->from('lead_credit_assesment');
        $this->db->where('crd_id', $srcid);
        $result = $this->db->get();
        $ld_src = $result->result_array();
        return $ld_src[0]['crd_name'];
        // print_r($ld_src);
    }

    public function GetAssigntoName($stsid) {
        $this->db->select('location_user,aliasloginname,duser,header_user_id');
        $this->db->from('vw_web_user_login');
        $this->db->where('header_user_id', $stsid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status;
        // print_r($ld_status);
    }

    public function GetAssigntoDetails($stsid) {
        $this->db->select('duser,empcode,header_user_id,location_user,aliasloginname');
        $this->db->from('vw_web_user_login');
        $this->db->where('header_user_id',$stsid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status;
        // print_r($ld_status);
    }

   
    public function GetLeadStatusName($toid) {
        $this->db->select('*');
        $this->db->from('leadstatus');
        $this->db->where('leadstatusid', $toid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        //print_r($ld_status); die;
        return @$ld_status[0]['leadstatus'];
         
    }
    public function GetSalesName($name) {
        $this->db->select('n_value_displayname');
        $this->db->from('lead_sale_type');
        $this->db->where('n_value', $name);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        //print_r($ld_status); die;
        return $ld_status[0]['n_value_displayname'];
         
    }

    public function GetLeadSubStatusName($substs_id) {
        $this->db->select('*');
        $this->db->from('leadsubstatus');
        $this->db->where('lst_sub_id', $substs_id);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return @$ld_status[0]['lst_name'];
        // print_r($ld_status);
    }

    public function GetTempCustId($tem_cust_id) {
        $this->db->select('*');
        $this->db->from('customermasterhdr');
        $this->db->where('id', $tem_cust_id);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['tempcustid'];
        // print_r($ld_status);
    }

    public function GetNextlpid($leadid) {
        $this->db->select('lpid');
        $this->db->from('leadproducts');
        $this->db->where('leadid', $leadid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['lpid'];
        // print_r($ld_status);
    }

    public function GetCustomerdetails($company) {
        $this->db->select('tempcustname,customergroup,customer_number,customer_name,cust_account_id,contact_person,contact_no,contact_mailid');
        $this->db->from('vw_lead_get_customerdetails');
        $this->db->where('id', $company);
        $result = $this->db->get();
        $ld_status = $result->result_array();

        return $ld_status[0];
        //print_r($ld_status);
    }

    function getcustomerdetails_view($customerid)
    {
        $sql="SELECT 
                    ''::text as leadid,
                    upper(dtl.city) as cityname, 
                    ''::text as statecode,
                    ''::text as countrycode ,
                    dtl.country as contryname,
                    dtl.postal_code as postalcode,
                    dtl.fax as fax,
                    dtl.mobile_no as mobile_no,
                    dtl.state as statename,
                    dtl.address1 as address,
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
                    hdr.id=" .$customerid."  AND dtl.addresstypeid  IN (SELECT DISTINCT addresstypeid FROM customermasterdtl  GROUP BY id,addresstypeid ) LIMIT 1";
           
                 $result = $this->db->query($sql);
         $customer_address= $result->result_array();
         return $customer_address[0];
         //print_r($customer_address);
    }

    public function GetItemgroup($item_id) {
        $sql = "SELECT * FROM view_tempitemmaster_grp WHERE id::character varying ='" . $item_id . "'";

        $result = $this->db->query($sql);
        $productdetails = $result->result_array();

        return @$productdetails[0];
    }

     public function get_leadpotentials($lead_id,$sales_type_flag) {
        $sql = "SELECT lead_prod_potential_types.potential,leadproducts.quantity as requirement,
        leaddetails.leadid,lead_prod_potential_types.product_type_id as id,
        lead_sale_type.n_value_displayname as lead_sale_type, leadsource.leadsource as lead_source_name,
        leaddetails.email_id
        FROM leaddetails 
        INNER JOIN leadproducts ON leaddetails.leadid = leadproducts.leadid 
        INNER JOIN leadsource ON leaddetails.leadsource = leadsource.leadsourceid
        INNER JOIN lead_prod_potential_types ON lead_prod_potential_types.leadid=leaddetails.leadid 
        INNER JOIN lead_sale_type ON lead_sale_type.n_value_id = lead_prod_potential_types.product_type_id 
        WHERE  leaddetails.leadid=".$lead_id." AND lead_sale_type.saletype_flag='".$sales_type_flag."'  ORDER BY lead_prod_potential_types.potential desc LIMIT 1";
       // echo $sql;
        $result = $this->db->query($sql);
        $potendetails = $result->result_array();
       // print_r($potendetails[0]);
        return $potendetails[0];
    }

    public function CheckNewCustomer($tem_cust_id) {
        $this->db->select('*');
        $this->db->from('customermasterhdr');
        $this->db->where('id', $tem_cust_id);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['cust_account_id'];
    }

    public function get_products_edit() {
        $sql = 'SELECT (itemmaster.itemid)::character varying(20) AS id, itemmaster.description FROM itemmaster UNION ALL SELECT tempitemmaster.temp_item_sync_id AS id, tempitemmaster.temp_itemname AS description FROM tempitemmaster';
        //	echo $sql; die;
        $result = $this->db->query($sql);
        $options = $result->result_array();
        $options_arr;
        // Format for passing into form_dropdown function
        foreach ($options as $option) {

            $options_arr[$option['id']] = $option['description'];
        }
        return $options_arr;
    }

    public function get_products_dispatch() {
        // updated for product type from table lead_sale_type
       /* $sql = "SELECT  
					 	DISTINCT  
							CASE 
								WHEN 
									n_value ='Repacking' THEN 'Repack' 
					    			ELSE 
									n_value END n_value,
							n_value_id
					 	FROM   
					 		vw_sales_despatch_transaction_calss_fnd_flex_values_vl 
					 	WHERE 
					 		flex_value_set_id=1014311 AND  
					 		flex_value_id<>173796
					UNION ALL
					SELECT  'Part Tanker',3";*/
        $sql = "SELECT n_value_id,n_value as name,n_value_displayname as n_value  FROM lead_sale_type";
        $result = $this->db->query($sql);
        $options = $result->result_array();
        $options_arr;
        // Format for passing into form_dropdown function
        foreach ($options as $option) {

            $options_arr[$option['n_value_id']] = $option['n_value'];
        }
        return $options_arr;
    }

     function get_leadproduct_saletype()
     {

           $sql = "SELECT sale_type_id,n_value_id,n_value,n_value_displayname FROM lead_sale_type WHERE active=1";
           $result = $this->db->query($sql);
           $options = $result->result_array(); 
         /*  foreach ($options as $option) 
           {
            $options_arr[$option['n_value_id']] = $option['n_value'];
           }
          return $options_arr;*/
          return $options;
     }

     function get_leadproduct_saletypeids()
     {

           $sql = "SELECT n_value_id FROM lead_sale_type";
           $result = $this->db->query($sql);
           $options = $result->result_array(); 
         
           $i=0;
           foreach ($options as $option) 
           {
            $options_arr[$i] = $option['n_value_id'];
            $i++;
           }
           // echo"<pre>options_arr";print_r($options_arr);echo"</pre>";
          return $options_arr;
          //return $options;
     }

    function get_subquery_customerhdr($duser) {
        $data = array(
            'executivename' => $leadid,
            'execode' => $leadid
        );
        $sub = $this->subquery->start_subquery('select');
        $sub->select('aliasloginname,duser')->from('vw_dusermaster k');
        $sub->where('header_user_id', $duser);
        $this->subquery->end_subquery('reportingto');
        $this->db->update('tempcustomermaster', $data);
    }

    function get_subquery_users($duser) {

        $this->db->select('header_user_id,duser,empname,location_user,reportingto');
        $sub = $this->subquery->start_subquery('select');
        $sub->select('duser')->from('dusermaster');
        $sub->where('vw_web_user_login.duser like $duser');
        $this->subquery->end_subquery('reportingto');
        $this->db->from('vw_web_user_login');
        $this->db->where('reportingto', 'test');
    }

    function get_subquery_users_order($parent_id = 0) {
        global $j, $i;
        global $reporting_user;
        $j = $i;
        $this->db->select('header_user_id,duser, empname, location_user,reportingto')->from('vw_web_user_login');
        $sub = $this->subquery->start_subquery('where_in');
        $sub->select('duser')->from('vw_web_user_login')->where('duser', $parent_id);
        $this->subquery->end_subquery('reportingto', TRUE);
        $this->db->order_by('location_user asc');
        $query = $this->db->get();

        $branch = array();
        if (!empty($query) && $query->num_rows() > 0) {
            $branch = $query->result_array();
            foreach ($branch as $key => $val) {
                $i++;
                if ($val['reportingto'] != "") {
                    $reporting_user[$i]['empname'] = strtoupper($val['location_user']) . "-" . $val['empname'];
                    $reporting_user[$i]['header_user_id'] = $val['header_user_id'];
                    $user = $val['duser'];
                    $branch[$key] = $this->get_subquery_users_order($user);
                }
            }
            $j = $i;
            unset($key);
            unset($val);
        }

        return $reporting_user;
    }

    function get_user_list_ids($user_report_id) {
// Hierarchry
        global $user_list_id;
//	echo"user_report_id ".$user_report_id; 
//	print_r($this->session->userdata);
        $arrid = array();
        $userids = $this->get_subquery_user_ids($user_report_id);
//   echo "count ".count($userids);
// print_r($userids);  
// echo"array ".is_array($userids); echo"<br>"; echo "count in get_user_list_ids ".count($userids); echo"<br>";
        if (count($userids) > 0) {
            foreach ($userids as $key => $idval) {
                // echo"in for loop key ".$key."<br>";
                // echo"in for loop idval ";print_r($idval);echo"<br>";
                $id = $idval['header_user_id'];
                if ($id != "") {
                    $user_list_id .= $id . ",";
                }
                // echo" after user_list_id ".$user_list_id;
            }
                // $user_list_id =substr_replace($user_list_id, "0", -1);
                // $user_list_id =substr_replace($user_list_id, ",", -1).$this->session->userdata['user_id'];
            $user_list_id = $user_list_id . $this->session->userdata['user_id'];
                // echo" after replace ".$user_list_id;
            return $user_list_id;
        } else {
            //    echo "in else"; echo"returning value is".$this->session->userdata['user_id']; 
            return $this->session->userdata['user_id'];
        }
        //  echo"<pre>";print_r($user_list_id);echo"</pre>"; 
    }

    function get_subquery_user_ids($parent_id = 0) {
        global $j, $i;
        global $reporting_user_id;
        //  echo " parent_id ".$parent_id; echo " reporting_user_id ".$reporting_user_id; 
        $j = $i;
        $this->db->select('header_user_id,duser, empname, location_user,reportingto')->from('vw_web_user_login');
        $sub = $this->subquery->start_subquery('where_in');
        $sub->select('duser')->from('vw_web_user_login')->where('duser', $parent_id);
        $this->subquery->end_subquery('reportingto', TRUE);
        $this->db->order_by('location_user asc');
        $query = $this->db->get();

        $branch = array();
        if (!empty($query) && $query->num_rows() > 0) {
            $branch = $query->result_array();
            foreach ($branch as $key => $val) {
                $i++;


                if ($val['reportingto'] != "") {
                    //	$reporting_user_id[$i]['empname']=$val['empname'];
                    $reporting_user_id[$i]['header_user_id'] = $val['header_user_id'];
                    $user = $val['duser'];
                    $branch[$key] = $this->get_subquery_user_ids($user);
                }
            }
            $j = $i;
            unset($key);
            unset($val);
            return $reporting_user_id;
        } else {
            return;
        }

        // return $reporting_user_id;
    }

    function update_tempcustmaster_leadid($leadid, $customer_id, $user_id) {
        $data = array(
            'lead_id' => $leadid
        );
        $this->db->where('user_id', $user_id);
        $this->db->where('temp_cust_sync_id', $customer_id);
        $this->db->update('tempcustomermaster', $data);

        return ($this->db->affected_rows() > 0);
    }

    function update_tempitemmaster_leadid($leadid, $productids, $user_id) {
        $prodid = array();
        foreach ($productids as $prodid) {
            $data = array(
                'lead_id' => $prodid['leadid']
            );
            $this->db->where('user_id', $user_id);
            $this->db->where('temp_item_sync_id', $prodid['productid']);
            $this->db->update('tempitemmaster', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    function create_leadlog($lead_log_details) {
        $this->db->insert('web_lead_loghistory', $lead_log_details);
        return $this->db->insert_id();
    }

    function create_lead_sublog($lead_sublog_details) {
        $this->db->insert('web_leadsubsts_loghistory', $lead_sublog_details);
        return $this->db->insert_id();
    }

    function insert_leadstatus_mailalert($lead_status_mailalert) {
        $this->db->set('leadid', @$lead_status_mailalert[leadid]);
        $this->db->set('user_id', @$lead_status_mailalert[user_id]);
        $this->db->set('branch', @$lead_status_mailalert[branch]);
        $this->db->set('lead_status_id', @$lead_status_mailalert[lead_status_id]);
        $this->db->set('lead_substatus_id', @$lead_status_mailalert[lead_substatus_id]);
        $this->db->set('assignto_id', @$lead_status_mailalert[assignto_id]);
        $this->db->set('appointment_due_date', @$lead_status_mailalert[appointment_due_date]);
        $this->db->set('not_able_to_get_appiontment', @$lead_status_mailalert[not_able_to_get_appiontment]);
        $this->db->set('status_created_date', @$lead_status_mailalert[status_created_date]);
        $this->db->set('status_action_type', @$lead_status_mailalert[status_action_type]);
        if (@$lead_status_mailalert[mail_alert_date] != "") {
            $this->db->set('mail_alert_date', @$lead_status_mailalert[mail_alert_date], FALSE);
        }
        $this->db->insert('lead_status_mail_alerts');
        //$this->db->insert('lead_status_mail_alerts', $lead_status_mailalert);
        return $this->db->insert_id();
    }

    function insert_leadstatus_mailalert_update($lead_status_mailalert) {

        $this->db->set('leadid', @$lead_status_mailalert[leadid]);
        $this->db->set('user_id', @$lead_status_mailalert[user_id]);
        $this->db->set('branch', @$lead_status_mailalert[branch]);
        $this->db->set('lead_status_id', @$lead_status_mailalert[lead_status_id]);
        $this->db->set('lead_substatus_id', @$lead_status_mailalert[lead_substatus_id]);
        $this->db->set('assignto_id', @$lead_status_mailalert[assignto_id]);
        $this->db->set('appointment_due_date', @$lead_status_mailalert[appointment_due_date]);
        $this->db->set('not_able_to_get_appiontment', @$lead_status_mailalert[not_able_to_get_appiontment]);
        $this->db->set('sample_reject_reason', @$lead_status_mailalert[sample_reject_reason]);
        $this->db->set('order_cancelled_reason', @$lead_status_mailalert[order_cancelled_reason]);
        $this->db->set('substatus_updated_date', @$lead_status_mailalert[substatus_updated_date]);
        $this->db->set('status_action_type', @$lead_status_mailalert[status_action_type]);
        if (@$lead_status_mailalert[mail_alert_date] != "") {
            $this->db->set('mail_alert_date', @$lead_status_mailalert[mail_alert_date], FALSE);
        }


        $this->db->insert('lead_status_mail_alerts');
        //$this->db->insert('lead_status_mail_alerts', $lead_status_mailalert);
        return $this->db->insert_id();
    }

    function insert_leadstatus_mailalert_revert($lead_status_mailalert) {

        $this->db->set('leadid', @$lead_status_mailalert[leadid]);
        $this->db->set('user_id', @$lead_status_mailalert[user_id]);
        $this->db->set('branch', @$lead_status_mailalert[branch]);
        $this->db->set('lead_status_id', @$lead_status_mailalert[lead_status_id]);
        $this->db->set('lead_substatus_id', @$lead_status_mailalert[lead_substatus_id]);
        $this->db->set('assignto_id', @$lead_status_mailalert[assignto_id]);
        $this->db->set('substatus_updated_date', @$lead_status_mailalert[substatus_updated_date]);
        $this->db->set('mail_alert_date', @$lead_status_mailalert[mail_alert_date], FALSE);
        $this->db->set('status_action_type', @$lead_status_mailalert[status_action_type]);

        $this->db->insert('lead_status_mail_alerts');
        //$this->db->insert('lead_status_mail_alerts', $lead_status_mailalert);
        return $this->db->insert_id();
    }

    function get_appointment_date_old($leadid, $substatusid) {
        $this->db->select('appointment_due_date');
        $this->db->from('lead_status_mail_alerts');
        $this->db->where('leadid', $leadid);
        $this->db->where('lead_substatus_id', $substatusid);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        //print_r($ld_status); die;
        return @$ld_status[0]['appointment_due_date'];
        //$arr =  json_encode($result->result_array());
        //return $arr;
    }

    function get_appointment_date($leadid, $substatusid) {
        $sql = "SELECT appointment_due_date FROM lead_status_mail_alerts  where leadid=" . $leadid . "  and lead_substatus_id=" . $substatusid . " and mail_alert_id in (SELECT  max(mail_alert_id)  FROM lead_status_mail_alerts GROUP BY  lead_substatus_id ,leadid )";

        $result = $this->db->query($sql);
        $ld_status = $result->result_array();
        return @$ld_status[0]['appointment_due_date'];
    }

    function get_appointment_noreason($leadid, $substatusid) {
        $sql = "SELECT not_able_to_get_appiontment FROM lead_status_mail_alerts  where leadid=" . $leadid . "  and lead_substatus_id=" . $substatusid . " and mail_alert_id in (SELECT  max(mail_alert_id)  FROM lead_status_mail_alerts GROUP BY  lead_substatus_id ,leadid )";

        $result = $this->db->query($sql);
        $ld_status = $result->result_array();
        return @$ld_status[0]['not_able_to_get_appiontment'];
    }

    function get_lead_2panumber($leadid, $substatusid) {
        $sql = "SELECT lead_2pa_no FROM leaddetails  where leadid=" . $leadid . "  and ldsubstatus=" . $substatusid;

        $result = $this->db->query($sql);
        $ld_status = $result->result_array();
        return @$ld_status[0]['lead_2pa_no'];
    }

    function get_lead_status_details($lead_id, $user_id) {
        
// changed on Nov 9th for adding Substatus 

        $sql = "SELECT
						lh_id,
						lh_lead_id,
						lh_user_id,
						lh_lead_curr_status,
						lh_lead_curr_statusid,
					lhsub_lh_lead_curr_sub_status,
					lhsub_lh_lead_curr_sub_statusid,
						lh_last_modified,
						lh_created_date,
						lh_created_user,
						lh_last_updated_user,
						action_type,
						lh_comments,
						modified_user_name,
						created_user_name,
						lh_updated_date,
						assignto_user_id,
						assignto_user_name,
						TRUNC(DATE_PART('Days',current_date - lh_created_date)) as idle_days,
						lh_last_modified::date - lh_created_date:: date AS Daysc,
						lh_last_modified::date - lh_updated_date:: date AS Days,   
						TRUNC(DATE_PART('Days', lh_last_modified::timestamp - lh_created_date::timestamp)/7) AS Weekc,
						TRUNC(DATE_PART('Days', lh_last_modified::timestamp - lh_updated_date::timestamp)/7) AS Week

					FROM
						web_lead_loghistory,
					web_leadsubsts_loghistory
					WHERE
						lh_lead_id = " . $lead_id . " and lhsub_lh_id = 	lh_id
					ORDER BY lh_id,
						lh_lead_curr_statusid ASC";

        //echo $sql; die;				
        $result = $this->db->query($sql);
        //	print_r($result->result_array());
        return $lead_log_detatils = $result->result_array();
    }

    function get_lead_sub_status_details($lead_id, $parent_status_id) {


        $sql = "SELECT * FROM web_leadsubsts_loghistory where lhsub_lh_lead_id = " . $lead_id . " and lhsub_lh_lead_curr_statusid =" . $parent_status_id;
        $result = $this->db->query($sql);
        //	print_r($result->result_array());
        return $lead_sublog_detatils = $result->result_array();
    }

    function update_prev_moddate($log_id) {
        $log_id = $log_id - 1;
        $data = array(
            'lh_last_modified' => date('Y-m-d:H:i:s')
        );

        $this->db->where('lh_id', $log_id);
        $this->db->update('web_lead_loghistory', $data);
        return ($this->db->affected_rows() > 0);
    }

    function get_all_company_json() {
        
        $sql = 'SELECT 	distinct on (view_tempcustomermaster.tempcustname) view_tempcustomermaster.id,view_tempcustomermaster.tempcustname FROM 	view_tempcustomermaster ORDER BY  tempcustname ASC';
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        //	echo "{ rows: ".$arr." }";
        return $arr;
    }

    function get_countryname($country_id) {
        $this->db->select('*');
        $this->db->from('country');
        $this->db->where('id', $country_id);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['name'];
    }

    function get_country_idbyname($country_name) {
        $this->db->select('*');
        $this->db->from('country');
        $this->db->where('LOWER(name)', $country_name);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['id'];
    }

    function get_state_idbyname($state_name) {
        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('LOWER(statename)', $state_name);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return @$ld_status[0]['statecode'];
    }

    function get_statename($state_id) {
        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('statecode', $state_id);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return $ld_status[0]['statename'];
    }


    function get_city_byname($city) {
        $this->db->select('*');
        $this->db->from('city');
        $this->db->where('LOWER(cityname)', $city);
        $result = $this->db->get();
        $ld_status = $result->result_array();
        return @$ld_status[0]['cityname'];
    }

    function insertlead_deletelog($created_date, $created_userid, $created_user_name, $leadid) {
        //return $this->db->insert_batch('leadproducts', $leadprods);
       /* $sql = "INSERT INTO 
			web_lead_deletedlog 
(leadid,lead_no,email_id,firstname,lastname,created_user,asignedto_userid,street,endproducttype,productsaletype,presentsource,suppliername,decisionmaker,branchname,comments,uploadeddate,description,secondaryemail,assignedtouser,createddate,createdby,last_modified,updatedby,sent_mail_alert,leadsource,primarystatus,substatusname,loginname,prodqnty,productupdatedate,created_date,prod_type_id,leadpoten,industrysegment,productname,itemgroup,uom,customername,customertype,deleteddate,deleted_userid,deleted_username)
SELECT 
     leadid,lead_no,email_id,firstname,lastname,created_user,asignedto_userid,street,endproducttype,productsaletype,presentsource,suppliername,decisionmaker,branchname,comments,uploadeddate,description,secondaryemail,assignedtouser,createddate,createdby,last_modified,updatedby,sent_mail_alert,leadsource,primarystatus,substatusname,loginname,prodqnty,productupdatedate,created_date,prod_type_id,leadpoten,industrysegment,productname,itemgroup,uom,customername,customertype,'" . $created_date . "'," . $created_userid . ",'" . $created_user_name . "' FROM vw_lead_export_to_exceljcwise where leadid=" . $leadid;*/

     $sql = "INSERT INTO web_lead_deletedlog (leadid,lead_no,email_id,firstname,lastname,created_user,asignedto_userid,street,endproducttype,productsaletype,
presentsource,suppliername,decisionmaker,branchname,comments,uploadeddate,description,secondaryemail,assignedtouser,createddate,createdby,last_modified,updatedby,sent_mail_alert,leadsource,primarystatus,substatusname,loginname,prodqnty,productupdatedate,created_date,prod_type_id,leadpoten,industrysegment,productname,itemgroup,uom,customername,customertype,deleteddate,deleted_userid,deleted_username) 
    (
    SELECT leadid,lead_no,email_id,firstname,lastname,created_user,asignedto_userid,address,industrysegment,
        ( 
            SELECT s.n_value_displayname from lead_prod_potential_types l,lead_sale_type s WHERE l.product_type_id = s.n_value_id AND leadid=".$leadid." AND l.potential >0
        ) as productsaletype,
        presentsource,suppliername,decisionmaker,branchname,comments,uploadeddate,description,secondaryemail,assignedtouser,createddate,createdby,lastupdatedate,updatedby,sent_mail_alert,leadsource,primarystatus,substatusname,createdby,immediate_requirement,productupdatedate,createddate,
        ( 
            SELECT s.n_value_id
            from lead_prod_potential_types l,lead_sale_type s WHERE l.product_type_id = s.n_value_id AND leadid=".$leadid." AND l.potential >0
        ) as prod_type_id,
        ( 
            SELECT l.potential from  lead_prod_potential_types l,lead_sale_type s WHERE l.product_type_id = s.n_value_id AND leadid=39649 AND l.potential >0
        )leadpoten,
        industrysegment,productname,itemgroup,uom,customername,customertype,'" . $created_date . "'," . $created_userid . ",'" . $created_user_name . "' FROM vw_lead_export_to_exceljcwise_fordeletelog where leadid=".$leadid.")";

        //echo $sql; die;
        $result = $this->db->query($sql);
        // echo $this->db->affected_rows();

        return ($this->db->affected_rows() > 0);
    }

    function get_customerdetails($company_id) {
        if ($company_id != "") {
            $sql = "SELECT
								*
						FROM 
							vw_daily_call_customerinfo_new 
						where company =" . $company_id;
        }


        //echo $sql; die;
        $result = $this->db->query($sql);
        $customerdetails = $result->result_array();
        $jTableResult = array();
        $jTableResult['custleaddetails'] = $customerdetails;
        $data = array();
        $i = 0;
        while ($i < count($jTableResult['custleaddetails'])) {

            $row = array();
            $row["leadid"] = $jTableResult['custleaddetails'][$i]["leadid"];
            $row["company"] = $jTableResult['custleaddetails'][$i]["company"];
            $row["firstname"] = $jTableResult['custleaddetails'][$i]["firstname"];
            $row["lastname"] = $jTableResult['custleaddetails'][$i]["lastname"];
            $row["website"] = $jTableResult['custleaddetails'][$i]["website"];
            $row["description"] = $jTableResult['custleaddetails'][$i]["description"];
            $row["industry_id"] = $jTableResult['custleaddetails'][$i]["industry_id"];
            $row["email_id"] = $jTableResult['custleaddetails'][$i]["email_id"];
            $row["user_branch"] = $jTableResult['custleaddetails'][$i]["user_branch"];
            $row["leadsource"] = $jTableResult['custleaddetails'][$i]["leadsource"];
            $row["leadsourceid"] = $jTableResult['custleaddetails'][$i]["leadsourceid"];
            $row["tempcustname"] = $jTableResult['custleaddetails'][$i]["tempcustname"];
            $row["leadstatusid"] = $jTableResult['custleaddetails'][$i]["leadstatusid"];
            $row["leadstatus"] = $jTableResult['custleaddetails'][$i]["leadstatus"];
            $row["leadsubstatusid"] = $jTableResult['custleaddetails'][$i]["leadsubstatusid"];
            $row["leadsubstsname"] = $jTableResult['custleaddetails'][$i]["leadsubstsname"];
            $row["assignleadchk"] = $jTableResult['custleaddetails'][$i]["assignleadchk"];
            $row["assign_to_name"] = $jTableResult['custleaddetails'][$i]["assign_to_name"];
            $row["createddate"] = $jTableResult['custleaddetails'][$i]["createddate"];
            $row["leadid"] = $jTableResult['custleaddetails'][$i]["leadid"];
            $row["last_modified"] = $jTableResult['custleaddetails'][$i]["last_modified"];
            $row["customerid"] = $jTableResult['custleaddetails'][$i]["customerid"];
            $row["cust_account_id"] = $jTableResult['custleaddetails'][$i]["cust_account_id"];
            $row["customeraddress"] = $jTableResult['custleaddetails'][$i]["customeraddress"];
            $row["city"] = $jTableResult['custleaddetails'][$i]["city"];
            $row["fax"] = $jTableResult['custleaddetails'][$i]["fax"];
            $row["country"] = $jTableResult['custleaddetails'][$i]["country"];
            $row["postal_code"] = $jTableResult['custleaddetails'][$i]["postal_code"];
            $row["state"] = $jTableResult['custleaddetails'][$i]["state"];
            $row["phone"] = $jTableResult['custleaddetails'][$i]["phone"];
            $row["mobile_no"] = $jTableResult['custleaddetails'][$i]["mobile_no"];

            $row["companycode"] = $jTableResult['custleaddetails'][$i]["companycode"];
            $row["customergroup"] = $jTableResult['custleaddetails'][$i]["customergroup"];
            $row["executivename"] = $jTableResult['custleaddetails'][$i]["executivename"];
            $row["technicalexecutive"] = $jTableResult['custleaddetails'][$i]["technicalexecutive"];
            $row["contact_persion"] = $jTableResult['custleaddetails'][$i]["contact_persion"];
            $row["contact_no"] = $jTableResult['custleaddetails'][$i]["contact_no"];
            $row["contact_mailid"] = $jTableResult['custleaddetails'][$i]["email_id"];
            $row["branch_manager_mailid"] = $jTableResult['custleaddetails'][$i]["branch_manager_mailid"];
            $row["received_branch"] = $jTableResult['custleaddetails'][$i]["received_branch"];
            $row["industry_segment"] = $jTableResult['custleaddetails'][$i]["industry_segment"];
            $row["purchase_contact_person"] = $jTableResult['custleaddetails'][$i]["purchase_contact_person"];
            $row["purchase_contact_no"] = $jTableResult['custleaddetails'][$i]["purchase_contact_no"];
            $row["purchase_mailid"] = $jTableResult['custleaddetails'][$i]["purchase_mailid"];
            $row["despatch_contact_person"] = $jTableResult['custleaddetails'][$i]["despatch_contact_person"];
            $row["despatch_contact_no"] = $jTableResult['custleaddetails'][$i]["despatch_contact_no"];
            $row["despatch_mailid"] = $jTableResult['custleaddetails'][$i]["despatch_mailid"];
            $row["commercial_manager"] = $jTableResult['custleaddetails'][$i]["commercial_manager"];

            $data[$i] = $row;
            $i++;
        }
        //$arr = "{\"data\":" .json_encode($data). "}";
        $arr = $data;

        return $arr;
    }

    function get_productgroupname($prodid) {

        $sql = "SELECT t.id,t.description,t.itemgroup FROM 
				(
				SELECT 
						(itemmaster.itemid)::character varying(20) AS id, 
						itemmaster.description,
						itemmaster.itemgroup 
				FROM 
						itemmaster 
				UNION ALL 
				SELECT 
						tempitemmaster.temp_item_sync_id AS id, 
						tempitemmaster.temp_itemname AS description,
						tempitemmaster.temp_itemname as itemgroup 
				FROM 
					tempitemmaster
				) t

				WHERE 
					t.id= '" . $prodid . "'";
        //	echo $sql; die;
        $result = $this->db->query($sql);
        $item_name = $result->result_array();
        //print_r($item_name); 

        return $item_name[0]['itemgroup'];
    }

   /* function get_businesscategory($catid) {
        $this->db->select('n_value');
        $this->db->where('n_value_id', $catid);
        $query = $this->db->get('vw_leads_getdispatch');
        $item_name = $query->result();
        // Bulk
        //   Intact
        //   Repacking
        //   Single - Tanker
        //   Small Packing
        //   $businesscat=array("0"=>"BULK", "1"=>"PART TANKER","2"=>"REPACK","3"=>"INTACT","4"=>"SINGLE - TANKER","5"=>"SMALL PACKING"); 

        if ($item_name[0]->n_value == 'Bulk') {
            @$bussinesscat = "BULK";
        } else if ($item_name[0]->n_value == 'INTACT') {
            @$bussinesscat = "BULK";
        } else if ($item_name[0]->n_value == 'Repacking') {
            @$bussinesscat = "REPACK";
        } else if ($item_name[0]->n_value == 'Single - Tanker') {
            @$bussinesscat = "SINGLE - TANKER";
        } else if ($item_name[0]->n_value == 'Small Packing') {
            @$bussinesscat = "SMALL PACKING";
        }
        return @$bussinesscat;
    }*/

    function get_industry_name($industry_id) {
        $this->db->select('industrysegment');
        $this->db->where('id', $industry_id);
        $query = $this->db->get('industry_segment');
        $indus_name = $query->result();
        return $indus_name[0]->industrysegment;
    }

    function get_customergroup($customer_id) {
        $this->db->select('customergroup');
        $this->db->where('id', $customer_id);
        $query = $this->db->get('customermasterhdr');
        $cust_name = $query->result();
        return $cust_name[0]->customergroup;
    }

    function save_daily_details($dcprodinserts) {
        foreach ($dcprodinserts as $dcprodinsert) {

        }
        return $this->db->insert_batch('dailycall_dtl', $dcprodinserts);
    }

    function save_leadprodpotentypes($lead_potential_types) {

        return $this->db->insert_batch('lead_prod_potential_types', $lead_potential_types);

    }

    function save_leadcustomer_potential_update($lead_customer_pontential) {

        return $this->db->insert_batch('potential_updated_table', $lead_customer_pontential);
    }

    function get_all_products($sql) {
        $result = $this->db->query($sql);
        $arr = json_encode($result->result_array());
        $arr = '{ "rows" :' . $arr . ' }';
        return $arr;
    }

    function check_prodnameduplicates_lead($prodid, $customerid) {
         $sql = "SELECT leaddetails.leadid FROM leaddetails,leadproducts WHERE leaddetails.leadid= leadproducts.leadid AND  leaddetails.company=" . $customerid . " AND leadproducts.productid::TEXT='" . $prodid . "'";

        // echo $sql;	die;				
        $result = $this->db->query($sql);
        $rowcount = $result->num_rows();
        if ($rowcount == 0) {
            return "true";
        } {
            return "false";
        }
    }
    function check_prodgroup_dup_saleorder($prodgrp, $customerid,$customergroup) {
        $customergroup=str_replace("'", "", $customergroup);
          //$sql = "select * from vw_lead_check_prod_duplicate WHERE  lead_customer_ref_id=".$customerid." AND product_group = '".$prodgrp."'";
        $sql = "select * from vw_lms_check_billed_product WHERE (lead_customer_ref_id=".$customerid." or  replace(trim(customergroup),'''','') ='".$customergroup."' ) AND product_group = '".$prodgrp."'";


       //  echo $sql;   die;                
        $result = $this->db->query($sql);
        $rowcount = $result->num_rows();
        if ($rowcount == 0) {
            return "true";
        } {
            return "false";
        }
    }

    

    function get_lead_sample_rejectcnt($leadid,$substatus_id)
    {
        $sql="select get_lead_sample_rejected_count(".$leadid.",".$substatus_id.")";
        $result = $this->db->query($sql);
        $reject_limit = $result->result_array();
       // print_r($reject_limit);
       // echo"reject_limit is ".$reject_limit[0]['get_lead_sample_rejected_count']."<br>";
        return $reject_limit[0]['get_lead_sample_rejected_count'];
    }
    function get_status()
                {

                $sql="SELECT leadstatusid,leadstatus,order_by FROM leadstatus ORDER BY order_by ASC";
               //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
            return $arr;

                }

    function get_status_srch()
                {

                $sql = "SELECT lst_sub_id, lst_name FROM leadsubstatus WHERE  lst_parentid =".$this->parent_id." ORDER BY lst_order_by ASC";
               //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    $arr =  json_encode($options); 

              //      array_push($options, "-select-");
                return $arr;

                }

    function get_leadcustomers_slow()
                {

                $sql="SELECT  
                        DISTINCT leaddetails.customer_id,customermasterhdr.tempcustname
                        FROM     
                        leaddetails  
                        INNER JOIN  customermasterhdr ON leaddetails.customer_id= customermasterhdr.id
                        WHERE leaddetails.lead_close_status=0 AND leaddetails.converted=0 ORDER BY customermasterhdr.tempcustname ASC";
               //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
            return $arr;

                }

        function get_leadcustomers()
                {

               $sql="SELECT     
                         b.customer_id,a.tempcustname
                        FROM     
                        (select company as customer_id  from  leaddetails   where leaddetails.lead_close_status=0 AND leaddetails.converted=0  ) b
                        INNER JOIN  (select  id::INTEGER,tempcustname from customermasterhdr) a ON b.customer_id= a.id
                        --WHERE leaddetails.lead_close_status=0 AND leaddetails.converted=0 
                        group by b.customer_id,a.tempcustname";
                /* $sql="select id::INTEGER as customer_id,tempcustname from customermasterhdr a where EXISTS ( select company from leaddetails b where b.lead_close_status=0 AND b.converted=0  and a.id=b.company )"; */
               //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
            return $arr;

                }

                function get_customers_all()
                {

                    $sql = 'SELECT  view_tempcustomermaster.id as customer_id,view_tempcustomermaster.tempcustname FROM view_tempcustomermaster ORDER BY  tempcustname ASC';
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
                    return $arr;
                }


               
 /* 
                function get_leadproducts_local()
                {

                $sql="SELECT id,description,itemgroup from view_tempitemmaster_grp";
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
                    return $arr;

                }
*/

               function get_leadproducts()
                {

                $sql="select 
                        itemid::character varying(15),description from
                        (select  itemid,description from itemmaster ) i
                        ,(select productid from leadproducts where productid  not like'TEMP%')  lp
                        where i.itemid=lp.productid
                        group by itemid,description
                        union all
                        select 
                        itemid::character varying(15),description from
                        (select  temp_item_sync_id as itemid, temp_itemname as description from tempitemmaster ) i
                        ,(select productid::character varying(15) from leadproducts where productid like'TEMP%') lp
                        where i.itemid=lp.productid::character varying(15)
                        group by itemid,description";
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
                    $arr =  json_encode($options); 
            return $arr;

                }




     function update_lead_reassign($leaddetails) {
        $lddetails = array();
        foreach ($leaddetails as $lddetails) {

            $data = array(
                'assignleadchk' => $lddetails['assignleadchk'],
                'user_branch' => $lddetails['user_branch'],
                'collector' => $lddetails['collector'],
                'last_updated_user' => $lddetails['last_updated_user'],
                'last_modified' => $lddetails['last_modified']
            );

            $this->db->where('leadid', $lddetails['leadid']);
            $this->db->update('leaddetails', $data);
        }

        return ($this->db->affected_rows() > 0);
    }

    function get_branches()
                {
                
                @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                @$reportingto= $this->session->userdata['reportingto'];
                if ($reportingto=="")
                {
                                    $sql="SELECT user_branch as branch FROM leaddetails GROUP BY user_branch";
                } else
                {

                              

                                $sql="SELECT DISTINCT a.branch FROM ( SELECT    header_user_id, UPPER (location_user) AS branch FROM vw_web_user_login WHERE header_user_id IN (".$get_assign_to_user_id.") and LENGTH (location_user) > 2) a ORDER BY a.branch";
        
                }
                //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    array_push($options, "-select-");
    //print_r($options); die;
                    $arr =  json_encode($options); 
                // return $result = $this->db->query($sql);
        
    //echo $arr; die;
            return $arr;

                }

             public function get_reportingto_ids($reportingid)
            {
                $sql="SELECT get_hierarchical_user_id('".$reportingid."')";
                $result = $this->db->query($sql);
                    $user_list_ids = $result->result_array();
                $user_list_ids=$user_list_ids[0]['get_hierarchical_user_id'];
                return $user_list_ids;

            }

            public function get_current_accnt_yr($to_date)
            {
                 $sql="SELECT * FROM get_acc_yr('".$to_date."')";
           
                $result = $this->db->query($sql);
                    $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['get_acc_yr'];
            }

            function get_current_jc($curr_date,$acc_yr)
            {
                $sql="SELECT jc_code FROM jc_calendar_dtl  WHERE acc_yr='".$acc_yr."' AND  '".$curr_date."' BETWEEN jc_period_from and jc_period_to";
                
                $result = $this->db->query($sql);
                    $accnt_yr = $result->result_array();
             
                return $accnt_yr[0]['jc_code'];
            }

            function GetMaxValdc($table)
            {
                $sql ="select max(id) from ".$table;
                $result = $this->db->query($sql);
                //print_r($result->result_array());
                $daily_hdr_id = $result->result_array();
            //  print_r($daily_hdr_id);
                //echo"max id is ".$daily_hdr_id[0]['max'];
                return $daily_hdr_id[0]['max'];

            }

             function save_lead_dailydtl($dc_detail) {
                $this->db->insert('dailyactivitydtl', $dc_detail);
                return $this->db->insert_id();
            }

            function save_daily_hdr($dc_hdrdetails) {
                
            if($this->db->insert('dailyactivityhdr', $dc_hdrdetails))
            {
               // echo"inserted sucessfully";
            }
            else
            {
                echo"Error in insert"; die;
            }
            //return $this->db->insert_id();
            return;
            }

            function check_dailyhdr_duplicates($hrd_currentdate,$user1)
            {
              

                $sql ="select exename,id from dailyactivityhdr  where currentdate::Date ='".$hrd_currentdate."' AND user1 ='".$user1."'";
                $result = $this->db->query($sql);
                $result->num_rows();
                $daily_hdr_id = $result->result_array();
             
                if($result->num_rows()==0)
                {
                    $daily_hdr_id['0']['noofrows']=$result->num_rows();
                    $daily_hdr_id['0']['exename']="";
                    $daily_hdr_id['0']['id']=0;    
                }
                else
                {
                    $daily_hdr_id['0']['noofrows']=$result->num_rows();
          
                }
                

             // echo"<pre>";print_r($daily_hdr_id);echo"</pre>";
                //echo"max id is ".$daily_hdr_id[0]['max'];
               // return $daily_hdr_id[0]['id'];
               return $daily_hdr_id;

            }

            function check_executive_user($user1)
            {
              

                $sql ="SELECT designation from vw_web_user_login_desg WHERE designation='Executive' AND upper(duser)='".strtoupper($user1)."'";
                $result = $this->db->query($sql);
                $result->num_rows();
                $isExecutive = $result->result_array();
             
                if($result->num_rows()==0)
                {
                    $isExecutive['0']['noofrows']=$result->num_rows();
                   
                }
                else
                {
                    $isExecutive['0']['noofrows']=$result->num_rows();
          
                }
                

             // echo"<pre>";print_r($daily_hdr_id);echo"</pre>";
                //echo"max id is ".$daily_hdr_id[0]['max'];
               // return $daily_hdr_id[0]['id'];
               return $isExecutive;

            }

        public function GetCustmerGroup($company) {
        $this->db->select('customergroup');
        $this->db->from('vw_lead_get_customerdetails');
        $this->db->where('id', $company);
        $result = $this->db->get();
        $ld_status = $result->result_array();

        return $ld_status[0];
        //print_r($ld_status);
    }

     function update_leadproducts($leadprod, $leadid) {

        $this->db->where('leadid', $leadid);
        $this->db->update('leadproducts', $leadprod);
        return ($this->db->affected_rows() > 0);
    }

      function dcupdate_leadprodpotentypes($leadprod_poten_type, $leadid,$prod_type_id) {

        $this->db->where('leadid', $leadid);
        $this->db->where('product_type_id', $prod_type_id);
        $this->db->update('lead_prod_potential_types', $leadprod_poten_type);
        return ($this->db->affected_rows() > 0);
    }

    /* market cricle functions */


     function get_mcbranches()
                {
                
                $reporting_to = $this->session->userdata['reportingto'];
                $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

                if (@$reporting_to=="")
                {
                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id";
                } else
                {

                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id AND 
                    v.header_user_id IN (".$get_assign_to_user_id.")";
                }
                //echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    $arr =  json_encode($options); 
                // return $result = $this->db->query($sql);
        
    //echo $arr; die;
            return $arr;

                }

    function get_marketcircle_loginuser()
    {
        @$reporting_to = $this->session->userdata['reportingto'];
        $get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
        if (@$reporting_to=="")
        {
            $sql="SELECT m.mc_sub_id FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE m.collector_id = c.collector_id AND 
            m.gc_executive_code=v.header_user_id GROUP BY   c.name,m.mc_sub_id ORDER BY 1";
        }
        else
        {
            $sql="SELECT m.mc_sub_id FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE m.collector_id = c.collector_id AND 
            m.gc_executive_code=v.header_user_id AND  v.header_user_id IN (".$get_assign_to_user_id.") GROUP BY   c.name,m.mc_sub_id ORDER BY 1";
        }
     //   echo $sql; die;
        $result = $this->db->query($sql);
        $marktcircle = $result->result_array();
        $arr =  json_encode($marktcircle); 
        return $arr;        
    }

    function get_mcselected_branch()
    {

        @$reporting_to = $this->session->userdata['reportingto'];
        $get_assign_to_user_id=$this->session->userdata['get_assign_to_user_id'];
        if (@$reporting_to=="")
        {
           
            $sql="SELECT  c.name,m.mc_sub_id FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
                    AND m.gc_executive_code=v.header_user_id AND upper(c.name)='".$this->brach_sel."' GROUP BY    c.name,m.mc_sub_id ORDER BY 1";
        }
        else
        {
              $sql="SELECT  c.name,m.mc_sub_id FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
                    AND m.gc_executive_code=v.header_user_id  AND v.header_user_id IN (".$get_assign_to_user_id.") AND upper(c.name)='".$this->brach_sel."' GROUP BY   c.name,m.mc_sub_id ORDER BY 1";
        }
      //  echo $sql; die;
        $result = $this->db->query($sql);
        $marktcircle = $result->result_array();
        $arr =  json_encode($marktcircle); 
        return $arr;

    }

     function get_reassign_mcbranches()
                {
                
                $reporting_to = $this->session->userdata['reportingto'];
                $get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

                if (@$reporting_to=="")
                {
                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id";
                } else
                {

                    $sql="SELECT DISTINCT c.name as branch FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE 
                    m.collector_id = c.collector_id AND  m.gc_executive_code=v.header_user_id AND 
                    v.header_user_id IN (".$get_assign_to_user_id.")";
                }
              //  echo $sql; die;
                    $result = $this->db->query($sql);
                    $options = $result->result_array();
                    $arr =  json_encode($options); 
                // return $result = $this->db->query($sql);
        
    //echo $arr; die;
            return $arr;

                }
    

     function get_users_formarketcircle()
     {

                @$reporting_to = $this->session->userdata['reportingto'];
                @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                if (@$reporting_to=="")
                {
                    $sql="select 
                                    header_user_id,
                                    displayname , 
                              branch from (
                                                SELECT  v.header_user_id,c.name as branch,m.mc_sub_id,  upper(c.name) || '-' || upper(v.empname) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
                                            AND m.gc_executive_code=v.header_user_id AND upper(m.mc_sub_id)='".$this->mc_sel."' GROUP BY  v.header_user_id,upper(v.empname),  c.name,m.mc_sub_id 
                                                                ) a GROUP BY header_user_id, displayname , branch order by displayname";

                                                

                }
                else{


                        $sql="select 
                                    header_user_id,
                                    displayname , 
                              branch from (
                                                SELECT  v.header_user_id,c.name as branch,m.mc_sub_id,  upper(c.name) || '-' || upper(v.empname) as displayname  FROM  ar_collectors c, market_circle_hdr m, vw_web_user_login v WHERE  m.collector_id = c.collector_id 
                                            AND m.gc_executive_code=v.header_user_id AND upper(m.mc_sub_id)='".$this->mc_sel."' GROUP BY  v.header_user_id,upper(v.empname),  c.name,m.mc_sub_id 
                                                                ) a   GROUP BY header_user_id, displayname , branch order by displayname";
                }
         //   echo $sql; die;
                    $result = $this->db->query($sql);
                    $userlist = $result->result_array();
                $arr =  json_encode($userlist); 
        //  echo $arr; die;
            return $arr;
     

     }


}
?>

