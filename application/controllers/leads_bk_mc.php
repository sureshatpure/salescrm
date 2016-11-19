<?php

class Leads extends CI_Controller {

    public $data = array();
    public $post = array();
    public $proddata = array();
    public $leaddata = array();
    public $loginuser;
    public $userid;
    public $loginname;
    public $reportingto;
    public $countryid;
    public $stateid;
    public $login_user_id;
    public $prdetid;
    public $temp_custmaster_id;
    public $datagroup = array();
    public $last_word;

    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('Leads_model');
        $this->load->library('subquery');
        $this->load->library('user_agent');
        $this->load->library('session');
        $this->load->helper('html');
    }

    public function index() {

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $out_array=explode(",",$this->session->userdata['get_assign_to_user_id']);
                
            if($this->session->userdata['reportingto']!="" && count($out_array)==1 )
            {
              $leaddata['selectedIndex_val']=-1;
            }
            else
            {
             $leaddata['selectedIndex_val']=-1;
            }
           // echo" selectedIndex_val "+$leaddata['selectedIndex_val']; die;
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            	//print_r($this->session->userdata);  die;
            
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            $data = array();
            $i = 0;
            $datagroup = array();
            foreach ($leaddata['permission'] as $key => $val) {
                $row = array();

                $row["groupid"] = $key;
                $row["groupname"] = $val;
                $datagroup[$i] = $row;
                $i++;
            }
            $arr = json_encode($datagroup);
            $leaddata['grpperm'] = $arr;
           
               $this->load->view('leads/viewleadsnewsort', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
        }
    }
    function index_search()
    {
       // echo"<pre>";print_r($_POST);echo"</pre>";
        @$from_date =$_POST['fromdate']; //10-Nov-2015
        @$to_date =$_POST['todate'];
        @$branch =$_POST['selectbranch'];
        @$selectuserid =$_POST['selectuser'];
        @$assigntouserid =$_POST['assigntouser'];
        @$selectmc_sub_id =$_POST['marketcircle'];
        
        @$statusid =$_POST['status'];
        @$substatusid =$_POST['substatus'];
        @$customerid =$_POST['customer'];
        @$productid =$_POST['product'];
      //  $date_filter =$_POST['date_filter'];
      

        if (@$_POST['fromdate']!="")
        {
            $fromdate = DateTime::createFromFormat('j-M-Y', $from_date);    
            $fromdate=$fromdate->format('Y-m-d');
        }
        else
        {
         $fromdate="";   
        }
        if (@$_POST['todate']!="")
        {
            $todate = DateTime::createFromFormat('j-M-Y', $to_date);  
            $todate=$todate->format('Y-m-d');
        }
        else
        {
         $todate="";   
        }    
        
      

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();

            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            //  print_r($usergroups);  die;
            $leaddata = array();
            $leaddata['branch']=$branch;
            $leaddata['to_date']=@$_POST['todate'];
            $leaddata['from_date']=@$_POST['fromdate'];
            $leaddata['sel_user_id']=$selectuserid;
            $leaddata['sel_mc_sub_id']=@$selectmc_sub_id;
            $leaddata['assign_user_id']=$assigntouserid;
            
            $leaddata['statusid']=$statusid;
            $leaddata['substatusid']=$substatusid;
            $leaddata['customerid']=$customerid;
            $leaddata['productid']=$productid;

            
           // $leaddata['date_filter']=$date_filter;

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->Leads_model->get_lead_details_all_srch($branch,$selectuserid,$assigntouserid,$statusid,$substatusid,$customerid,$productid,$fromdate,$todate);
                
            } else {
                $leaddata['data'] = $this->Leads_model->get_lead_details_srch($branch,$selectmc_sub_id,$assigntouserid,$statusid,$substatusid,$customerid,$productid,$fromdate,$todate,$this->session->userdata['reportingto']);

               
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            $data = array();
            $i = 0;
            $datagroup = array();
            foreach ($leaddata['permission'] as $key => $val) {
                $row = array();

                $row["groupid"] = $key;
                $row["groupname"] = $val;
                $datagroup[$i] = $row;
                $i++;
            }
            $arr = json_encode($datagroup);
            $leaddata['grpperm'] = $arr;
            $this->load->view('leads/leadslisting', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
        }

    }

    public function convertedleads() {

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            //	print_r($user);
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            //	print_r($usergroups);  die;
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->Leads_model->get_converted_for_grid();
            } else {
                $leaddata['data'] = $this->Leads_model->get_converted_reporting_to_for_grid($this->session->userdata['reportingto']);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            $data = array();
            $i = 0;
            $datagroup = array();
            foreach ($leaddata['permission'] as $key => $val) {
                $row = array();

                $row["groupid"] = $key;
                $row["groupname"] = $val;
                $datagroup[$i] = $row;
                $i++;
            }

            $arr = json_encode($datagroup);

            $leaddata['grpperm'] = $arr;



            $this->load->view('leads/viewleadsconverted', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
        }
    }

    public function add() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $reffer = @$_SERVER['HTTP_REFERER'];
            preg_match("/[^\/]+$/", $reffer, $matches);
            @$last_word = $matches[0];
        } else {
            $last_word = "";
        }
        $def_collector="CHENNAI - GC";


        //echo "last word is ".@$last_word; die;
        //  echo"Rep To ".$this->session->userdata['reportingto'];
        $this->load->helper(array('form', 'url'));
        $designation_role = trim($this->session->userdata['designation']);
        $data['optionslst'] = $this->Leads_model->get_leadstatus_add();


        $data['optionscrd'] = $this->Leads_model->get_leadcredit_assment();
        $data['optionslsr'] = $this->Leads_model->get_leadsource();
        //$data['optionscollector'] = $this->Leads_model->get_collectors();
        $data['optionscollector'] = $this->Leads_model->get_collectors($this->session->userdata['get_assign_to_user_id']);
        
        //$data['optionscmp'] = $this->Leads_model->get_all_company();
       // $data['optionscmp'] = $this->Leads_model->getleadcustomers($def_collector);
        $data['optionscmp'] = $this->Leads_model->getleadcustomers();
        $data['optionsmc'] = $this->Leads_model->getmarketcircles();
        
        
        $data['optionscnt'] = $this->Leads_model->get_country();
        $data['optionsinds'] = $this->Leads_model->get_industry();

        $data['optionsexp'] = $options = array('' => 'Select Option',
            'EOU' => 'EOU',
            'Domestic' => 'Domestic',
            'Domestic and EOU' => 'Domestic and EOU'
        );
        $data['optionsprestsrc'] = $options = array('' => 'Select Option',
            'Import' => 'Import',
            'Domestic' => 'Domestic',
            'Domestic and Import' => 'Domestic and Import'
        );
        if (@$this->session->userdata['reportingto'] == "") 
        {
            $data['optionsasto'] = $this->Leads_model->get_assignto_users();
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add();
        } 
       elseif($designation_role =='CO-ORDINATOR' || $designation_role =='ASSISTANT MANAGER' || $designation_role =='PM')
         {
          $data['optionsasto'] = $this->Leads_model->get_assignto_users();
          $data['optionslocuser'] = $this->Leads_model->get_locationuser_add();
         }

        else 
        {

            $data['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add_order();
        }

        $data['reffer_page'] = $last_word;
        $this->load->view('leads/leadsaddnew', $data);

        // 	$this->load->view('leads/leads',$data);
    }
    public function addold() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $reffer = @$_SERVER['HTTP_REFERER'];
            preg_match("/[^\/]+$/", $reffer, $matches);
            @$last_word = $matches[0];
        } else {
            $last_word = "";
        }

        //echo "last word is ".@$last_word; die;
        //  echo"Rep To ".$this->session->userdata['reportingto'];
        $this->load->helper(array('form', 'url'));
        $data['optionslst'] = $this->Leads_model->get_leadstatus_add();


        $data['optionscrd'] = $this->Leads_model->get_leadcredit_assment();
        $data['optionslsr'] = $this->Leads_model->get_leadsource();
        $data['optionscmp'] = $this->Leads_model->get_all_company();
        $data['optionscnt'] = $this->Leads_model->get_country();
        $data['optionsinds'] = $this->Leads_model->get_industry();

        $data['optionsexp'] = $options = array('' => 'Select Option',
            'EOU' => 'EOU',
            'Domestic' => 'Domestic',
            'Domestic and EOU' => 'Domestic and EOU'
        );
        $data['optionsprestsrc'] = $options = array('' => 'Select Option',
            'Import' => 'Import',
            'Domestic' => 'Domestic',
            'Domestic and Import' => 'Domestic and Import'
        );
        if (@$this->session->userdata['reportingto'] == "") {
            $data['optionsasto'] = $this->Leads_model->get_assignto_users();
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add();
        } else {
//          $data['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $data['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add_order();
        }
        $data['reffer_page'] = $last_word;
        $this->load->view('leads/leadsaddold', $data);
        //  $this->load->view('leads/leads',$data);
    }

    public function adddcproduct($customer_id) {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        if (isset($_SERVER['HTTP_REFERER'])) {

            $reffer = @$_SERVER['HTTP_REFERER'];
            
            preg_match("/[^\/]+$/", $reffer, $matches);
            //print_r($matches);
            @$last_word = $matches[0];
            // echo"last word ".$last_word;
        } else {
            $last_word = "";
        }




        //  echo"Rep To ".$this->session->userdata['reportingto'];
        $this->load->helper(array('form', 'url'));
        $data['optionslst'] = $this->Leads_model->get_leadstatus_add();


        $data['optionslsr'] = $this->Leads_model->get_leadsource();
        $data['optionscrd'] = $this->Leads_model->get_leadcredit_assment();
//		$data['optionscmp'] = $this->Leads_model->get_company();
//		$leaddata['optionscmp'] = $this->Leads_model->get_all_company();
        $data['customerinfo'] = $this->Leads_model->get_customerdetails($customer_id);


        $company_id = $data['customerinfo'][0]['company'];
        $industry_id = $data['customerinfo'][0]['industry_id'];

        if (strlen($data['customerinfo'][0]['country'] > 2)) {
            $countryid = $this->Leads_model->get_country_idbyname(strtolower($data['customerinfo'][0]['country']));
        } else {
            $countryid = $data['customerinfo'][0]['country'];
        }
        //echo $countryid."<br>";
        $stateid = $this->Leads_model->get_state_idbyname(strtolower($data['customerinfo'][0]['state']));

        $data['customerinfo'][0]['country'] = $countryid;
        $data['customerinfo'][0]['state'] = $stateid;
        $data['customerinfo'][0]['city'] = $this->Leads_model->get_city_byname(strtolower($data['customerinfo'][0]['city']));

        /* $leadstusid =  $leaddata['customerinfo'][0]['leadstatusid'];
          $leadstus_order_id =  $leaddata['customerinfo'][0]['order_by'];
          $lst_order_by_id =  $leaddata['customerinfo'][0]['lst_order_by'];
          $lst_parentid_id =  $leaddata['customerinfo'][0]['lst_parentid'];
          $userbranch =  $leaddata['customerinfo'][0]['user_branch'];
          $countryid =  $leaddata['customerinfo'][0]['country'];
          $substatusid =  $leaddata['customerinfo'][0]['ldsubstatus']; */
        $data['optionsst'] = $this->Leads_model->get_states_edit($countryid);
        $data['optionsct'] = $this->Leads_model->get_city_edit($stateid);



        $data['optionscmp'] = $this->Leads_model->get_all_company();
        $data['optionsinds'] = $this->Leads_model->get_industry();

        $data['optionscnt'] = $this->Leads_model->get_country();
        $data['optionsinds'] = $this->Leads_model->get_industry();
        $data['optionsexp'] = $options = array(
            '' => 'Select Option',
            'EOU' => 'EOU',
            'Domestic' => 'Domestic',
            'Domestic and EOU' => 'Domestic and EOU'
        );
        $data['optionsprestsrc'] = $options = array(
            '' => 'Select Option',
            'Import' => 'Import',
            'Domestic' => 'Domestic',
            'Domestic and Import' => 'Domestic and Import'
        );
        if (@$this->session->userdata['reportingto'] == "") {
            $data['optionsasto'] = $this->Leads_model->get_assignto_users();
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add();
        } else {
//			$data['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $data['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $data['optionslocuser'] = $this->Leads_model->get_locationuser_add_order();
        }
        $data['reffer_page'] = 'dailycall';
        $this->load->view('leads/leadsadddaily', $data);
        // 	$this->load->view('leads/leads',$data);
    }

    function edit($id) {
        $this->session->set_userdata('run_time_lead_id', $id);
        $reportingid = $this->session->userdata['loginname'];
       // $user_list_ids = $this->Leads_model->get_user_list_ids($reportingid);
        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //set it
        $this->session->set_userdata($get_assign_to_user_id);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }

        if ($this->session->userdata['reportingto'] == "") {
            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details_all($id);
        } else {
            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details($id);
        }
        $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

        $i = 0;
        $datagroup = array();
        foreach ($leaddata['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);

        $leaddata['grpperm'] = $arr;

        //print_r($leaddata['leaddetails']); $customer_id=$leaddata['leaddetails'][0]['customer_id'];  echo "customer id ".$customer_id; die;
        //	$customer_id=$leaddata['leaddetails'][0]['customer_id'];
        $crd_name = $leaddata['leaddetails'][0]['crd_assesment'];
        $crd_id = $leaddata['leaddetails'][0]['crd_id'];
        $company_id = $leaddata['leaddetails'][0]['company'];
        $leadstusid = $leaddata['leaddetails'][0]['leadstatusid'];
        $leadstus_order_id = $leaddata['leaddetails'][0]['order_by'];
        $lst_order_by_id = $leaddata['leaddetails'][0]['lst_order_by'];
        $lst_parentid_id = $leaddata['leaddetails'][0]['lst_parentid'];
        $userbranch = $leaddata['leaddetails'][0]['user_branch'];
        $substatusid = $leaddata['leaddetails'][0]['ldsubstatus'];

      //  $countryid = $leaddata['leaddetails'][0]['country'];
      //  $stateid = $leaddata['leaddetails'][0]['state'];
      
        
        $leaddata['optionslst'] = $this->Leads_model->get_leadstatus_edit($leadstusid, $leadstus_order_id);
        $leaddata['appiontment_date'] = str_replace("-", "/", $this->Leads_model->get_appointment_date($id, $substatusid));
        $leaddata['no_reason'] = $this->Leads_model->get_appointment_noreason($id, $substatusid);
        $leaddata['lead_2pa_no'] = $this->Leads_model->get_lead_2panumber($id, $substatusid);
        //$leaddata['lead_2pa_no']='123YU';
        $leaddata['optionslsr'] = $this->Leads_model->get_leadsource();
        $leaddata['optionscrd'] = $this->Leads_model->get_leadcredit_assment();
        //		$leaddata['optionscmp'] = $this->Leads_model->get_company();
        $leaddata['optionscmp'] = $this->Leads_model->get_all_company();
        $leaddata['optionscnt'] = $this->Leads_model->get_country();
        $leaddata['optionsinds'] = $this->Leads_model->get_industry();
        $leaddata['optionsexp'] = $options = array(
            '' => 'Select Option',
            'EOU' => 'EOU',
            'Domestic' => 'Domestic',
            'Domestic and EOU' => 'Domestic and EOU'
        );
        $leaddata['optionsprestsrc'] = $options = array(
            '' => 'Select Option',
            'Import' => 'Import',
            'Domestic' => 'Domestic',
            'Domestic and Import' => 'Domestic and Import'
        );
      //  $leaddata['optionsst'] = $this->Leads_model->get_states_edit($countryid);
        //$leaddata['optionslsubst'] = $this->Leads_model->get_substatus_edit($substatusid);
        $leaddata['optionslsubst'] = $this->Leads_model->get_substatus_edit_all($substatusid, $lst_parentid_id, $lst_order_by_id);
        //$leaddata['optionsct'] = $this->Leads_model->get_city_edit($stateid);

        if ($this->session->userdata['reportingto'] == "") {
            $leaddata['optionsasto'] = $this->Leads_model->get_assignto_users();
            $leaddata['optionslocuser'] = $this->Leads_model->get_locationuser_add();
        } else {
            $leaddata['optionslocuser'] = $this->Leads_model->get_locationuser_add_order();
//				$leaddata['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
            $leaddata['optionsasto'] = $this->Leads_model->get_assignto_users_order_edit($this->session->userdata['reportingto'], $userbranch);
        }
/*Start*/

        $customer_address = $this->Leads_model->getcustomerdetails_view($company_id);
        //echo"<pre>";print_r($customer_address); echo"</pre>";die;

        $leaddata['country']= $customer_address['contryname'];
        $leaddata['state'] = $customer_address['statename'];
        $leaddata['city'] = $customer_address['cityname'];
        $leaddata['address'] = trim($customer_address['address']);
        //$leaddata['address'] = str_replace("'","\'",trim($customer_address['address']));
        $leaddata['postalcode'] = $customer_address['postalcode'];
        $leaddata['mobile_no'] = $customer_address['mobile_no'];
        $leaddata['fax'] = $customer_address['fax'];
        $leaddata['contact_person']= $customer_address['contact_person'];
        $leaddata['contact_number'] = $customer_address['contact_number'];
        $leaddata['contact_mailid'] = $customer_address['contact_mailid'];
        $leaddata['cust_account_id'] = $customer_address['cust_account_id'];
/*End*/  

        $leaddata['leadproducts'] = $this->Leads_model->get_lead_product_details($id);
        $product_name = $this->Leads_model->get_productname($leaddata['leadproducts'][0]['productid']);
        $leaddata['optionsproedit'] = $this->Leads_model->get_products_edit();
        $leaddata['optionsprotypeedit'] = $this->Leads_model->get_products_dispatch();

        $leaddata['data'] = $this->Leads_model->get_synched_products($company_id, $product_name);
        
      //  echo"<pre>";print_r($leaddata);echo"</pre>"; die;
        $this->load->view('leads/editleadsnew', $leaddata);
    }


    function getleadssource() {

        $data['options'] = $this->Leads_model->get_leadssource();
        $this->load->view('leads/leads', $data);
    }

    function getproducts() {
        $data = array();
        $data = $this->Leads_model->get_products();
        print_r($data);
    }

    function getcompanyjson() {
        $data = array();
        $data = $this->Leads_model->get_all_company_json();
        print_r($data);
    }

    function getdispatch() {
        $data = array();
        $data = $this->Leads_model->get_dispatch();
        print_r($data);
    }

    function getinitial_lead_sub() {
        $data = array();
        $data = $this->Leads_model->get_getinitial_lead_sub();
        print_r($data);
    }

    function getstates($country_id) {
        $this->Leads_model->country_id = $country_id;
        $states = $this->Leads_model->get_states();
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($states);
    }

    function getleadsubstatus($parent_id) {

        $this->Leads_model->parent_id = $parent_id;

        $substatus = $this->Leads_model->get_leadsubstatus();
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($substatus);
    }
     function getleadsubstatus_srch($parent_id) {
        $this->Leads_model->parent_id = urldecode($parent_id);
        $substatus = $this->Leads_model->get_status_srch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $substatus;
    }
    

    function getleadsubstatusadd($parent_id) {

        $this->Leads_model->parent_id = $parent_id;

        $substatus = $this->Leads_model->get_leadsubstatus_add();
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($substatus);
    }
    function getleadcustomersadd($marketcircle) {

        $this->Leads_model->collector = $marketcircle;

        $customers = $this->Leads_model->get_lead_customersadd($marketcircle);
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($customers);
    }


     function getleadcustomersaddmc($collector,$marketcircle) {

        $this->Leads_model->collector = $marketcircle;

        $customers = $this->Leads_model->get_lead_customersadd_mc($collector,$marketcircle);
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($customers);
    }
    function getmarketcirclesadd($collector) {

        $this->Leads_model->collector = $collector;
        //echo"collector ".$collector; die;
        $marketcircle = $this->Leads_model->get_lead_marketcircles($collector);
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($marketcircle);
    }
    

    function getassignedtobranch($brach_sel) {

        $this->Leads_model->brach_sel = html_entity_decode($brach_sel);

        $substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($substatus);
    }

    function getcustomeraddress($customer_id) {

        $this->Leads_model->customer_id = $customer_id;

        $customer_address = $this->Leads_model->get_customer_address();
        //   print_r($customer_address); die;
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($customer_address);
    }

    function getcities($state_id) {
        $this->Leads_model->state_id = $state_id;
        $cities = $this->Leads_model->get_cities();
        header('Content-Type: application/x-json; charset=utf-8');
        echo json_encode($cities);
    }

    function savelead() {


        $insert_dc_hdr=0;
        $dte = $this->input->post('uploaded_date'); //    [uploaded_date] => 03/05/2016
        $reffer_page = $this->input->post('hdn_refferer');  //dailycall

        $dt = new DateTime();
        $date = $dt->createFromFormat('d/m/Y', $dte);
        $dates = $date->format('Y-m-d');
        $time = date('H:i:s');
        $uploaded_time = $dates . " " . $time;
        $substatus_id = explode("-", $this->input->post('leadsubstatus'));
        $dt_appiontment = $this->input->post('content_appiontment_date');
        $samle_reject_count=0;
        
        $dt_vistit = $this->input->post('uploaded_date');
        $dtappv = new DateTime();
        $date_visit = $dtappv->createFromFormat('d/m/Y', $dt_vistit);
        $date_v = $date_visit->format('Y-m-d');
        $date_visit_time = $date_v . " " . $time;
        //$to_date= date("Y-m-d"); 
  

        $reason_no_appointment = $this->input->post('not_able_to_get_appointment');
        $sample_rejected_reason = $this->input->post('sample_rejected_reason');
        if ($dt_appiontment != "") {
            $dtapp = new DateTime();
            $date_app = $dtapp->createFromFormat('d/m/Y', $dt_appiontment);
            $dates_app = $date_app->format('Y-m-d');
            $time = date('H:i:s');
            //echo" appiontment_date ".$appiontment_date = $dates." ".$time;
            $appiontment_date = $dates_app;
            //echo " mail_alert_date ".$mail_alert_date = "timestamp".$appiontment_date." ".$time."-interval 24 hours";
            $mail_alert_date = $appiontment_date . " " . $time;
        }
        if(trim($this->input->post('regularProd'))=="true")
        {
            $sales_type_flag="R";
        }
        else
        {
            $sales_type_flag="I";
        }

     

        $hdn_grid_row_data = json_decode($_POST['hdn_grid_row_data'], TRUE);

      /*  echo"<pre>";print_r($_POST);echo"</pre>";
        echo"<pre>";print_r($hdn_grid_row_data);echo"</pre>"; 
        
        $potential_arry = array();
        foreach ($hdn_grid_row_data as $key => $val)
        {
            array_push($potential_arry, $val);
        }
        $potential_out = array_slice($potential_arry[0], 2, 9);
        foreach ($potential_out as $key => $val)
        {
             echo "key ".$key."<br>";
            echo "val ".$val."<br>";
        }
         echo"<pre> potential_out";print_r($potential_out);echo"</pre>";
        $maxPot = max($potential_out);
        $saletype = array_search(max($potential_out), $potential_out);
        $sale_type_name =$this->Leads_model->GetSalesName($saletype);
          echo "sales type name ".$sale_type_name."<br>";
          echo "sales pontential ".$maxPot."<br>";
          
        echo"<pre>"; print_r($this->session->userdata);echo"</pre>"; 
        echo"date_v ".$date_v."<br>";
        echo"date_visit_time ".$date_visit_time."<br>";

        die;*/

        $customFieldPoten = array();
        $leaddata = array();


        if ($this->input->post('saveleads') || $this->input->post('hdn_saveleads')) {
            $login_user_id = $this->session->userdata['user_id'];
            $login_username = $this->session->userdata['username'];
            $duser = $this->session->userdata['loginname'];
            $empcode =$this->session->userdata['empcode'];
            
            $assign_to_dc = $this->Leads_model->GetAssigntoDetails($this->input->post('assignedto'));

            $login_user_id_dc = $assign_to_dc['0']['header_user_id'];
            $login_username_dc = $assign_to_dc['0']['aliasloginname'];
            $duser_dc = $assign_to_dc['0']['duser'];
            $empcode_dc =$assign_to_dc['0']['empcode'];

            
            $account_yr = $this->Leads_model->get_current_accnt_yr($dates);
      

            $lead_status_name = $this->Leads_model->GetLeadStatusName($this->input->post('leadstatus'));
            $lead_sub_status_name = $this->Leads_model->GetLeadSubStatusName($substatus_id[0]);
          
            $assign_to_array = $this->Leads_model->GetAssigntoName($this->input->post('assignedto'));
            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
            $duser = $assign_to_array['0']['duser'];
          
            $cust_account_id = $this->Leads_model->CheckNewCustomer($this->input->post('company'));
            $lead_seq1 = $this->Leads_model->GetMaxVal('leaddetails', 'leadid');
            $lead_seq1 = $lead_seq1 + 1;
            $lead_src = $this->Leads_model->GetLeadSourceVal($this->input->post('leadsource'));
            $lead_src_name = $this->Leads_model->GetLeadSourceName($this->input->post('leadsource'));
            $lead_crd = $this->Leads_model->GetLeadCredit($this->input->post('credit_assesment'));
            $lead_no = 'LEAD-' . $lead_src;
            $customer_id = $this->Leads_model->GetTempCustId($this->input->post('company'));
            $customer_details = $this->Leads_model->GetCustomerdetails($this->input->post('company'));
            $customer_number = $customer_details['customer_number'];
            $user_branch = strtoupper($assign_to_array['0']['location_user']);
            $customer_name = $customer_details['customer_name'];

            if ($customer_details['customergroup'] != "") {
                $customergroup = $customer_details['customergroup'];
            } else {
                $customergroup = $customer_details['tempcustname'];
            }

            if ($this->input->post('presentsource') == "Domestic and Import" || $this->input->post('presentsource') == "Domestic") {
                $domestic_supplier_name = trim($this->input->post('txtDomesticSource'));
            } else {
                $domestic_supplier_name = "";
            }
             

            if ($this->input->post('designation') == "") {
                $leaddetails = array('lead_no' => $lead_no,
                    'leadstatus' => $this->input->post('leadstatus'),
                    'company' => $this->input->post('company'),
                    'customer_id' => $customer_id,
                    'email_id' => trim($this->input->post('email_id')),
                    'firstname' => $this->input->post('firstname'),
                    'industry_id' => $this->input->post('industry'),
                    'lastname' => $this->input->post('lastname'),
                    'lead_2pa_no' => $this->input->post('lead_2pa_no'),
                    'uploaded_date' => $uploaded_time,
                    'leadsource' => $this->input->post('leadsource'),
                    'crd_id' => $this->input->post('credit_assesment'),
                    'crd_assesment' => $lead_crd,
                    'ldsubstatus' => $substatus_id[0],
                    'assignleadchk' => $this->input->post('assignedto'),
                    'collector' => $this->input->post('branch'),
                    'user_branch' => $user_branch,
                    'description' => trim($this->input->post('description')),
                    'comments' => trim($this->input->post('comments')),
                    'producttype' => trim($this->input->post('producttype')),
                    'exporttype' => trim($this->input->post('exportdomestic')),
                    'presentsource' => trim($this->input->post('presentsource')),
                    'decisionmaker' => trim($this->input->post('purchasedecision')),
                    'domestic_supplier_name' => $domestic_supplier_name,
                    'sales_type_flag' => $sales_type_flag,
                    'createddate' => date('Y-m-d:H:i:s'),
                    'last_modified' => date('Y-m-d:H:i:s'),
                    'created_user' => $login_user_id
                );
            } else {
                $leaddetails = array('lead_no' => $lead_no,
                    'leadstatus' => $this->input->post('leadstatus'),
                    'company' => $this->input->post('company'),
                    'customer_id' => $customer_id,
                    'email_id' => trim($this->input->post('email_id')),
                    'firstname' => $this->input->post('firstname'),
                    'industry_id' => $this->input->post('industry'),
                    'lastname' => $this->input->post('lastname'),
                    'lead_2pa_no' => $this->input->post('lead_2pa_no'),
                    'uploaded_date' => $uploaded_time,
                    'leadsource' => $this->input->post('leadsource'),
                    'designation' => $this->input->post('designation'),
                    'crd_id' => $this->input->post('credit_assesment'),
                    'crd_assesment' => $lead_crd,
                    'ldsubstatus' => $substatus_id[0],
                    'assignleadchk' => $this->input->post('assignedto'),
                    'user_branch' => $user_branch,
                    'collector' => $this->input->post('branch'),
                    'description' => trim($this->input->post('description')),
                    'comments' => trim($this->input->post('comments')),
                    'producttype' => trim($this->input->post('producttype')),
                    'exporttype' => trim($this->input->post('exportdomestic')),
                    'presentsource' => trim($this->input->post('presentsource')),
                    'decisionmaker' => trim($this->input->post('purchasedecision')),
                    'domestic_supplier_name' => $domestic_supplier_name,
                    'sales_type_flag' => $sales_type_flag,
                    'createddate' => date('Y-m-d:H:i:s'),
                    'last_modified' => date('Y-m-d:H:i:s'),
                    'created_user' => $login_user_id
                );
            }

                      $user1 = $this->session->userdata['loginname'];
                      // check if the user is a executive or not - added by jsuresh 3rd Sept 2015
                      $isExecutive = $this->Leads_model->check_executive_user($duser_dc);
                      if($isExecutive['0']['noofrows']>0)
                      {
                        
                        $check_duplicates = $this->Leads_model->check_dailyhdr_duplicates($date_v, $duser_dc);
                        if($check_duplicates['0']['noofrows']>0)
                        {
                         // get the header id and do set update flag=1
                            $daily_hdr_id=$check_duplicates['0']['id'];
                            $insert_dc_hdr=0;
                        }
                        else
                        {
                        // insert 
                        $daily_hdr_id = $this->Leads_model->GetMaxValdc('dailyactivityhdr');
                        $daily_hdr_id = $daily_hdr_id + 1;
                        $insert_dc_hdr=1;
                        $dc_act_hdr = array('id' => $daily_hdr_id,
                                    'currentdate' =>$date_visit_time,
                                    'execode' => $empcode_dc,
                                    'user1' => $duser_dc,
                                    'exename' => $login_username_dc,
                                    'companycode' => 'PPC',
                                    'accperiod' => $account_yr,
                                    'creationuser' => $login_username_dc,
                                    'creationdate' => date('Y-m-d:H:i:s'),
                                    'header_user_code' => $login_user_id_dc
                                );
                        }
                      }
                      

            
                    

            $proddata = array();
            $potential_updated = array();
            $lead_prod_poten_type = array();
            $lead_customer_pontential = array();
            $lead_status_mailalert = array();
            $update_leadstatus_mailalert_revert = array();
             $leadids = array();
            $k = 0;
            if($insert_dc_hdr==1)
            {
             $dchdr_id = $this->Leads_model->save_daily_hdr($dc_act_hdr);   
            }
            
            foreach ($hdn_grid_row_data as $key => $val) {

                if ($hdn_grid_row_data[$key]['product_id'] != "") {
                    $lead_id = $this->Leads_model->save_lead($leaddetails);
                    
                    if ($lead_id > 0) {

                        $leadaddress = array('leadaddressid' => $lead_id,
                            'city' => $this->input->post('city'),
                            'street' => $this->input->post('street'),
                            'state' => $this->input->post('state'),
                            'pobox' => $this->input->post('postalcode'),
                            'country' => $this->input->post('country'),
                            'mobile_no' => $this->input->post('mobile'),
                            'phone' => $this->input->post('phone'),
                            'fax' => $this->input->post('fax'),
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );

                       
                    


                        /*        if (($this->input->post('leadstatus') == 1) && ($this->input->post('leadsubstatus') == 3 || $this->input->post('leadsubstatus') == 4 || $this->input->post('leadsubstatus') == 5 || $this->input->post('leadsubstatus') == 6 || $this->input->post('leadsubstatus') == 7 )) */

                        
                        if (($this->input->post('leadstatus') == 1) && ( $substatus_id[0] == 3 ||
                                $substatus_id[0] == 4 || $substatus_id[0] == 5 || $substatus_id[0] == 6 ) || ($this->input->post('leadstatus') == 2) && ($substatus_id[0] == 7 )) 
                        { // Start of if condition for inserting in the mail alert table
                            if (@$appiontment_date != "") {/*4*/
                                
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $this->input->post('branch'),
                                        'lead_status_id' => $this->input->post('leadstatus'),
                                        'lead_substatus_id' => $substatus_id[0],
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $this->input->post('assignedto'),
                                        'appointment_due_date' => $appiontment_date,
                                        'not_able_to_get_appiontment' => $reason_no_appointment,
                                        'status_created_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                                        'status_action_type' => "Insert"
                                    );
                                } else if ($substatus_id[0] == 3) {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($substatus_id[0] == 6) {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($substatus_id[0] == 7) {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            }
                            
                        } // end of if condition for inserting in the mail alert table
                        /*Start for Sample Trails status*/

                        /*Start for Enquiry/offer*/
                             if (($this->input->post('leadstatus') == 5) && ( $substatus_id[0] == 26))
                             {

                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            
                            }
                        /*End for Enquiry/offer*/
                        
                        if (($this->input->post('leadstatus') == 4) && ( $substatus_id[0] == 16 ||
                                $substatus_id[0] == 17 || $substatus_id[0] == 18 || $substatus_id[0] == 19  || $substatus_id[0] == 20 || $substatus_id[0] == 21 )) 
                            {
                             
                                $today_date = date('Y-m-d:H:i:s');
                                $mail_alert_date="timestamp '" . $today_date . "' +interval '0 hours'";
                                if ($substatus_id[0] == 18 || $substatus_id[0] == 19 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '48 hours'"; 
                                }
                                if ($substatus_id[0] == 20 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                                }
                                $lead_status_mailalert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "Insert"
                                );
                            }
                            if  ($this->input->post('leadstatus') == 4 &&  $substatus_id[0] == 21) 
                            {
                                $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => 4,
                                    'lead_substatus_id' => 16,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                );
                            }
                            else if ($substatus_id[0] == 20)
                            {
                               $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => 5,
                                    'lead_substatus_id' => 24,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                ); 
                            }
                        /*END for Sample trails status*/
                       

                        
                        if ($substatus_id[0] == 3 || $substatus_id[0] == 4 || $substatus_id[0] == 5 || $substatus_id[0] == 6 || $substatus_id[0] == 7 || $substatus_id[0] == 16 || $substatus_id[0] == 17 || $substatus_id[0] == 18 || $substatus_id[0] == 19 || $substatus_id[0] == 20 || $substatus_id[0] == 21 || $substatus_id[0] == 26 || $substatus_id[0] == 26) {
                            $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert($lead_status_mailalert);
                        }
                         if (($this->input->post('leadstatus') == 4 &&  $substatus_id[0] == 21 ) || ($substatus_id[0] == 20 ))
                            {
                                $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_revert($update_leadstatus_mailalert_revert);
                            }
                        

                       // $addid = $this->Leads_model->save_lead_address($leadaddress); // commented by suresh for skipping leadaddress table
                         
                            if ($customer_details['customer_number'] == "" or $customer_details['customer_number'] == null )
                             {
                                 $customerhdr_email_contact = array('contact_mailid' => $this->input->post('email_id'),
                                                                    'contact_persion' => $this->input->post('contact_name'),
                                                                    'contact_no' => $this->input->post('phone'),
                                                                     );

                             
                             }
                             else
                             {
                                $exist_customer_detail = $this->Leads_model->getcustomerdetails_view($this->input->post('company'));
                                {
                                    //print_r($exist_customer_detail);
                                    $customerhdr_email_contact = array();
                                    if ($exist_customer_detail['contact_mailid']=="")
                                        {
                                            $customerhdr_email_contact['contact_mailid']=$this->input->post('email_id');
                                        }
                                        if ($exist_customer_detail['contact_person']=="")
                                        {
                                            $customerhdr_email_contact['contact_persion']=$this->input->post('contact_name');
                                        }
                                        if ($exist_customer_detail['contact_number']=="")
                                        {
                                            $customerhdr_email_contact['contact_no']=$this->input->post('phone');
                                        }
                                        
                                }
                             }
                             
                                
                        $array_len=count($customerhdr_email_contact);
                        
                             
                            if($array_len>0) 
                             {  // check if the array is empty
                                $customerhdr_email_contact['lead_update_user_id']=$login_user_id;
                                $customerhdr_email_contact['leadid']=$lead_id;
                                $customerhdr_email_contact['lastupdateuser']=$login_username; // username 
                                $customerhdr_email_contact['lastupdatedate']=date('Y-m-d:H:i:s');
                                 
                                $this->Leads_model->update_custmastrhdr_addlead($customerhdr_email_contact,$this->input->post('company'));        
                             }
                             else
                             {
                                //echo"customerhdr_email_contact is empty <br>";
                             }
                        
                        
                        $itemgroup_name = $this->Leads_model->GetItemgroup($hdn_grid_row_data[$key]['product_id']);
                        if ($itemgroup_name['itemgroup'] != "") {
                            $itemgroup_name = $itemgroup_name['itemgroup'];
                        } else {
                            $itemgroup_name = $itemgroup_name['description'];
                        }
                        if ($hdn_grid_row_data[$key]['requirment'] == "") {
                            $hdn_grid_row_data[$key]['requirment'] = 0;
                        }
                        $leadproducts = array('leadid' => $lead_id,
                            'productid' => $hdn_grid_row_data[$key]['product_id'],
                            'product_itemname' => $hdn_grid_row_data[$key]['prod_item_name'],
                            'quantity' => $hdn_grid_row_data[$key]['requirment'],
                            'product_group' => $itemgroup_name,
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );
                        $proddata[$key]['leadid'] = $lead_id;
                        $proddata[$key]['productid'] = $hdn_grid_row_data[$key]['product_id'];
                        //echo"<pre>leadproducts ";print_r($leadproducts);echo"</pre>";	 
                         $prdetid = $this->Leads_model->save_lead_products_all($leadproducts);
                       
                        if ($hdn_grid_row_data[$key]['product_id']!= '') {
                            /* start for inserting other prodtype in lead_prod_potential_types */

                            $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
                            $lpid_seq = $this->Leads_model->GetNextlpid($lead_id);
                            $lpid_seq = $lpid_seq;
 
                            for ($k = 0; $k < count($product_sale_type); $k++) {
                                $lead_prod_poten_type[$k]['leadid'] = $lead_id;
                                $lead_prod_poten_type[$k]['productid'] = $hdn_grid_row_data[$key]['product_id'];
                                $lead_prod_poten_type[$k]['product_type_id'] = $product_sale_type[$k]['n_value_id'];
                                if ($hdn_grid_row_data[$key][$product_sale_type[$k]['n_value']] != "") {
                                    $lead_prod_poten_type[$k]['potential'] = $hdn_grid_row_data[$key][$product_sale_type[$k]['n_value']];
                                } else {
                                    $lead_prod_poten_type[$k]['potential'] = 0;
                                }
                            }
                            //echo"<pre>lead_prod_poten_type ";print_r($lead_prod_poten_type);echo"</pre>";
                            $lead_pord_poten_id = $this->Leads_model->save_leadprodpotentypes($lead_prod_poten_type);

                            $k = 0;
                            
                         
                            for ($m = 0; $m < count($product_sale_type); $m++) {
                                $lead_customer_pontential[$m]['id'] = $lead_id;
                                $lead_customer_pontential[$m]['line_id'] = $lpid_seq;
                                $lead_customer_pontential[$m]['user1'] = strtoupper($duser);
                                $lead_customer_pontential[$m]['customergroup'] = $customergroup;
                                $lead_customer_pontential[$m]['itemgroup'] = $itemgroup_name;
                                $lead_customer_pontential[$m]['customer_number'] = $customer_number;
                                $lead_customer_pontential[$m]['customer_name'] = $customer_name;
                                $lead_customer_pontential[$m]['types'] = "LEAD";
                                $lead_customer_pontential[$m]['current_acc_yr'] =$account_yr;
                                $lead_customer_pontential[$m]['collector'] = $this->input->post('branch');
                                $lead_customer_pontential[$m]['lead_created_date'] = date('Y-m-d:H:i:s');
                                $lead_customer_pontential[$m]['user_code'] = $this->input->post('assignedto');
                                $lead_customer_pontential[$m]['businesscategory'] = strtoupper($product_sale_type[$m]['n_value_displayname']);
                                if ($hdn_grid_row_data[$key][$product_sale_type[$m]['n_value']] != "") {
                                    //echo"in if ---";
                                    $lead_customer_pontential[$m]['yearly_potential_qty'] = ($hdn_grid_row_data[$key][$product_sale_type[$m]['n_value']] * 12);
                                } else {
                                /*  echo"in else --- ";*/
                                    $lead_customer_pontential[$m]['yearly_potential_qty'] = 0;
                                }
                            }
                            $m = 0;
                             
                            $lead_pord_poten_id = $this->Leads_model->save_leadcustomer_potential_update($lead_customer_pontential);

                            /* end for inserting other business in Potential update table */

                            if($isExecutive['0']['noofrows']>0)
                            {
                             /* save dailycall details start */

                                $product_sale_type = $this->Leads_model->get_leadpotentials($lead_id,$sales_type_flag);
                                //echo"<pre> product_sale_type";print_r($product_sale_type);  echo"</pre>";
                                $daily_dtl = array('id' => $daily_hdr_id,
                                 'date' => $date_visit_time,
                                 'itemgroup' => $itemgroup_name,
                                 'custgroup' => $customergroup,
                                 'potentialqty' => $product_sale_type['potential'],
                                 'subactivity' => 'LEADS',
                                 'hour_s' => 0,
                                 'minit' => 0,
                                 'modeofcontact' => $product_sale_type['email_id'],
                                 'quantity' => $hdn_grid_row_data[$key]['requirment'],
                                 'division' => $product_sale_type['lead_sale_type'],
                                 'creationdate' => date('Y-m-d:H:i:s'),
                                 'creationuser' => $login_username_dc,
                                 'leadid' => $lead_id
                                );
                               //echo"<pre> daily_dtl";print_r($daily_dtl);echo"</pre>";
                              
                               $prdetid = $this->Leads_model->save_lead_dailydtl($daily_dtl); 
                          
                           
                          /* save dailycall details end */
                            }
                        }
                    }

                   /* log details */
                    $lead_log_details = array('lh_lead_id' => $lead_id,
                        'lh_user_id' => $login_user_id,
                        'lh_lead_curr_status' => $lead_status_name,
                        'lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                        'lh_created_date' => date('Y-m-d:H:i:s'),
                        'lh_created_user' => $login_user_id,
                        'lh_comments' => $this->input->post('comments'),
                        'action_type' => 'Insert',
                        'created_user_name' => $login_username,
                        'assignto_user_id ' => $this->input->post('assignedto'),
                        'assignto_user_name' => $lead_assign_name
                    );
                    
                    $logid = $this->Leads_model->create_leadlog($lead_log_details);

                    $lead_sublog_details = array(
                        'lhsub_lh_id' => $logid,
                        'lhsub_lh_lead_id' => $lead_id,
                        'lhsub_lh_user_id' => $login_user_id,
                        'lhsub_lh_lead_curr_status' => $lead_status_name,
                        'lhsub_lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                        'lhsub_lh_lead_curr_sub_status' => $lead_sub_status_name,
                        'lhsub_lh_lead_curr_sub_statusid' => $substatus_id[0],
                        'lhsub_lh_comments' => $this->input->post('comments'),
                        'lhsub_lh_created_date' => date('Y-m-d:H:i:s'),
                        'lhsub_lh_created_user' => $login_user_id,
                        'lhsub_action_type' => 'Insert',
                        'lhsub_created_user_name' => $login_username,
                        'lhsub_assignto_user_id ' => $this->input->post('assignedto'),
                        'lhsub_assignto_user_name' => $lead_assign_name
                    );


                    $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
                    /* end log details */
                    // Sample and Trails log and sublog entry for revert back - Start 
                        
                        if ($substatus_id[0] == 20 || $substatus_id[0] == 21) 
                        {   $samle_reject_count=0;
                            $lead_status_update='Y';
                            // Start update leaddetails 
                            $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count));
                            $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($substatus_id[0],$samle_reject_count));
                            $leaddetails_update = array(
                                'leadstatus' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                                'ldsubstatus' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                                'last_modified' => date('Y-m-d:H:i:s'),
                                'last_updated_user' => $login_user_id
                            );
                            
                            $id = $this->Leads_model->update_lead_status($leaddetails_update, $lead_id);

                            // End update leaddetails_update 

                            // insert a record in lead log details
                            // Start lead log details revert back 
                            $lead_log_details_update = array('lh_lead_id' => $lead_id,
                                'lh_user_id' => $login_user_id,
                                'lh_lead_curr_status' => $log_lead_status_name,
                                'lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                                'lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lh_last_updated_user' => $login_user_id,
                                'lh_comments' => trim($this->input->post('comments')),
                                'action_type' => 'RevertBack',
                                'modified_user_name' => $login_username,
                                'assignto_user_id ' => $this->input->post('assignedto'),
                                'assignto_user_name' => $lead_assign_name,
                                'status_update' => $lead_status_update
                            );

                            $logid = $this->Leads_model->create_leadlog($lead_log_details_update);
                             //End lead log details revert back 

                            // insert a record in lead sub log details
                            // Start revert back sub log details 
                            $lead_sublog_details_update = array(
                                'lhsub_lh_id' => @$logid,
                                'lhsub_lh_lead_id' => $lead_id,
                                'lhsub_lh_user_id' => $login_user_id,
                                'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                                'lhsub_lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lhsub_lh_last_updated_user' => $login_user_id,
                                'lhsub_lh_comments' => trim($this->input->post('comments')),
                                'lhsub_action_type' => "RevertBack",
                                'lhsub_modified_user_name' => $login_username,
                                'lhsub_assignto_user_id ' => $this->input->post('assignedto'),
                                'lhsub_assignto_user_name' => $lead_assign_name,
                                'lhsub_status_update' => $lead_status_update
                            );
                            $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);

                            

                        }
                         // Sample and Trails log and sublog entry for revert back - END 



                    $temp_itemmaster_id = $this->Leads_model->update_tempitemmaster_leadid($lead_id, $proddata, $login_user_id);
                } // end of customFieldName //
              //  echo"lead_id ".$lead_id."<br>";
                
                array_push($leadids, $lead_id);
            }
         
            //echo"<pre>";print_r($leadproducts);echo"</pre>";
            //$prdetid = $this->Leads_model->save_lead_products($proddata);
            //$prdetid = $this->Leads_model->save_lead_products_all($proddata);
            //$prdpoten = $this->Leads_model->save_potential_update($potential_updated);
            if ($prdetid > 0) {
                $message = "Saved lead with products";
            } else {
                $message = "";
            }



            //  echo"last inserted id ".$lead_id;
            if (@$addid > 0) {
                $message = "Saved Lead details ";
            } else {
                $message = "";
            }
            // 	redirect('leads');

            $custmastrhdr = array(
                'executivename' => $lead_assign_name,
                'execode' => $this->session->userdata['loginname']
            );
            // before updating customermaster check if it is only a new customer.
            if ($cust_account_id == "") {
                $temp_custmasterhdr_id = $this->Leads_model->update_custmastrhdr_assignto($this->input->post('assignedto'), $this->input->post('company'));
            }
            //$temp_custmaster_id = $this->Leads_model->update_tempcustmaster_leadid($lead_id,$this->input->post('company'),$login_user_id);

          //  $leaddata['leadids']=$leadids;
            $leads=implode(",", $leadids);


            $this->session->set_flashdata('message', "Lead Created Successfully with follwing leadid(s)- ".$leads);
            //echo "ref page ".$reffer_page; 		echo $url =base_url();
            if($this->input->post('branch')=='CHENNAI - GC')
            {
                require('../mobileapps/release2D/staging/class_sendnewleads.php');
                $objA = new A();
                $objA->foo();  
            }
            if ($reffer_page == "dailycall") {
                redirect($url . 'dailycall');
            } else {

               
               redirect('leads/add');
              


            }
        }
    }


    function updatelead($id) {
        $substatus_id = explode("-", $this->input->post('leadsubstatus'));
        // echo"final post of leadsubstatus ".$substatus_id[0]; die;
        $dte = $this->input->post('content_appiontment_date');
        $reffer_page = $this->input->post('hdn_refferer');  //dailycall
        $reason_no_appointment = $this->input->post('not_able_to_get_appointment');
        $sample_rejected_reason = $this->input->post('sample_rejected_reason');
        $order_cancelled_reason = $this->input->post('order_cancelled_reason');
        $samle_reject_count=0;
        
        if ($dte != "") {
            $dt = new DateTime();
            $date = $dt->createFromFormat('d/m/Y', $dte);
            $dates = $date->format('Y-m-d');
            $time = date('H:i:s');
            //echo" appiontment_date ".$appiontment_date = $dates." ".$time;
            $appiontment_date = $dates;
            $mail_alert_date = $appiontment_date;
        }
        echo"<pre>";print_r($_POST); echo"</pre>";
        $login_user_id = $this->session->userdata['user_id'];
        $login_username = $this->session->userdata['username'];
        $duser = $this->session->userdata['loginname'];

        if ($this->input->post('updatelead')) {
            //$lead_seq1= $this->Leads_model->GetNextSeqVal('leaddetails_leadid_seq');
            $leadid = $id;
            $lead_seq1 = $this->Leads_model->GetNextSeqVal('leaddetails_leadid_seq');
            $lead_status_name = $this->Leads_model->GetLeadStatusName($this->input->post('leadstatus'));
            $lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($substatus_id[0]);
            $lead_crd = $this->Leads_model->GetLeadCredit($this->input->post('credit_assesment'));
            $assign_to_array = $this->Leads_model->GetAssigntoName($this->input->post('assignedto'));
            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
            $duser_for_update = $assign_to_array['0']['duser'];
            $duser = $assign_to_array['0']['duser'];
            $user_branch = strtoupper($assign_to_array['0']['location_user']);

            $customer_id = $this->Leads_model->GetTempCustId($this->input->post('company'));

            $cust_account_id = $this->Leads_model->CheckNewCustomer($this->input->post('company'));
           // $customer_details = $this->Leads_model->GetCustomerdetails($this->input->post('company'));
            $customer_details = $this->Leads_model->getcustomerdetails_view($this->input->post('company'));
            
            $itemgroup_name = $this->Leads_model->GetItemgroup($_POST['customFieldName'][0]);
            if ($itemgroup_name['itemgroup'] != "") {
                $itemgroup_name = $itemgroup_name['itemgroup'];
            } else {
                $itemgroup_name = $itemgroup_name['description'];
            }

            if ($customer_details['customergroup'] != "") {
                $customergroup = $customer_details['customergroup'];
            } else {
                $customergroup = $customer_details['tempcustname'];
            }
            $customer_number = $customer_details['customer_number'];
            $customer_name = $customer_details['customer_name'];


            //$lead_no = $lead_no = 'LEAD'.$lead_seq1;
            $lead_desc = $this->input->post('hdn_desc') . "-" . $this->input->post('description');
            if ($this->input->post('leadstatus') != $this->input->post('hdn_status_id') || $substatus_id[0] != $this->input->post('hdn_sub_status_id') || $this->input->post('assignedto') != $this->input->post('hdn_assign_to') || trim($this->input->post('comments')) != trim($this->input->post('hdn_cmnts'))) {
                $update_log = 1;
            } else {
                $update_log = 0;
            }

            if ($this->input->post('leadstatus') != $this->input->post('hdn_status_id') || ($substatus_id[0] != $this->input->post('hdn_sub_status_id'))) {
                $lead_status_update = 'Y';
            } else {
                $lead_status_update = 'N';
            }


            $crm_first_soc_no = $this->input->post('txtLeadsoc');
            if ($crm_first_soc_no == "") {
                $crm_first_soc_no = 0;
                $ld_converted = 0;
            } else {
                $crm_first_soc_no = $this->input->post('txtLeadsoc');
                $ld_converted = 1;
            }
            if ($this->input->post('presentsource') == "Domestic and Import" || $this->input->post('presentsource') == "Domestic") {
                $domestic_supplier_name = trim($this->input->post('txtDomesticSource'));
            } else {
                $domestic_supplier_name = "";
            }
            if ($this->input->post('leadstatus')==8)
            {
               $lead_close_status=1;
               $lead_close_option =$lead_substatus_name;
            }
            else
            {
              $lead_close_status=0; 
              $lead_close_option=""; 
            }
            
            $leaddetails = array(
                'leadstatus' => $this->input->post('leadstatus'),
                'company' => $this->input->post('company'),
                'customer_id' => $customer_id,
                'leadsource' => $this->input->post('leadsource'),
                'collector' => $this->input->post('branch'),
                'user_branch' => $user_branch,
                'industry_id' => $this->input->post('industry'),
                'email_id' => $this->input->post('email_id'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'lead_2pa_no' => $this->input->post('lead_2pa_no'),
                'leadsource' => $this->input->post('leadsource'),
                'designation' => $this->input->post('designation'),
                'crd_id' => $this->input->post('credit_assesment'),
                'crd_assesment' => $lead_crd,
                'assignleadchk' => $this->input->post('assignedto'),
                'description' => $this->input->post('description'),
                'ldsubstatus' => $substatus_id[0],
                'lead_close_status' => $lead_close_status,
                'lead_close_option' => $lead_close_option,
                'lead_close_comments' => $_POST['closingcomments'],
                'lead_crm_soc_no' => $crm_first_soc_no,
                'app_sync_flag' => 'N',
                'converted' => $ld_converted,
                'comments' => $this->input->post('comments'),
                'producttype' => trim($this->input->post('producttype')),
                'exporttype' => trim($this->input->post('exportdomestic')),
                'presentsource' => trim($this->input->post('presentsource')),
                'decisionmaker' => trim($this->input->post('purchasedecision')),
                'domestic_supplier_name' => $domestic_supplier_name,
                'website' => $this->input->post('website'),
                'nextstepdate' => date('Y-m-d'),
                'last_modified' => date('Y-m-d:H:i:s'),
                'last_updated_user' => $login_user_id
            );

/* start for customermasterhdr and detail update*/
                if ($customer_details['customer_number'] == "" or $customer_details['customer_number'] == null )
                    {
                      $customerhdr_email_contact = array('contact_mailid' => $this->input->post('email_id'),
                                                         'contact_persion' => $this->input->post('contact_name'),
                                                         'contact_no' => $this->input->post('phone'),
                                                        );

                             
                    }
                    else
                    {
                        $exist_customer_detail = $this->Leads_model->getcustomerdetails_view($this->input->post('company'));
                        {
                            //print_r($exist_customer_detail);
                            $customerhdr_email_contact = array();
                            if ($exist_customer_detail['contact_mailid']=="")
                            {
                                $customerhdr_email_contact['contact_mailid']=$this->input->post('email_id');
                            }
                            if ($exist_customer_detail['contact_person']=="")
                            {
                                $customerhdr_email_contact['contact_persion']=$this->input->post('contact_name');
                            }
                            if ($exist_customer_detail['contact_number']=="")
                            {
                                $customerhdr_email_contact['contact_no']=$this->input->post('phone');
                            }

                        }
                    }
                     
                        $array_len=count($customerhdr_email_contact);
                        
                       

                   //  if(array_key_exists($keyval, $customerhdr_email_contact) && is_null($customerhdr_email_contact[$keyval]))
                       if($array_len>0)  
                     {  // check if the array is empty

                        $customerhdr_email_contact['lead_update_user_id']=$login_user_id;
                        $customerhdr_email_contact['leadid']=$leadid;
                        $customerhdr_email_contact['lastupdateuser']=$login_username; // username 
                        $customerhdr_email_contact['lastupdatedate']=date('Y-m-d:H:i:s');
                        
                        $this->Leads_model->update_custmastrhdr_addlead($customerhdr_email_contact,$this->input->post('company'));   

                     }
                     else
                     {
                        //echo"customerhdr_email_contact is empty in update <br>";
                     }
/* end for customermasterhdr and detail update*/
                    /* Start for Managing and implementation*/
                    $today_date = date('Y-m-d');
                    if ($this->input->post('leadstatus') == 6 ) 
                        {
                            $lead_status_mailalert = array('leadid' => $leadid,
                                'user_id' => $login_user_id,
                                'branch' => $this->input->post('branch'),
                                'lead_status_id' => $this->input->post('leadstatus'),
                                'lead_substatus_id' => $substatus_id[0],
                                'last_update_user_id' => $login_user_id,
                                'assignto_id' => $this->input->post('assignedto'),
                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                'status_action_type' => "Update"
                            );
                        }
                    if ($this->input->post('leadstatus') == 7 ) 
                        {
                            $lead_status_mailalert = array('leadid' => $leadid,
                                'user_id' => $login_user_id,
                                'branch' => $this->input->post('branch'),
                                'lead_status_id' => $this->input->post('leadstatus'),
                                'lead_substatus_id' => $substatus_id[0],
                                'last_update_user_id' => $login_user_id,
                                'assignto_id' => $this->input->post('assignedto'),
                                'soc_no' => $crm_first_soc_no,
                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                'status_action_type' => "Update"
                            );
                        }                        

                    /* End for Managing and implementation*/

            if ($substatus_id[0] == 6) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('assignedto'),
                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                );
            } else {
                if ((@$appiontment_date != "") && ($substatus_id[0] == 4)) {    
                    $lead_status_mailalert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('branch'),
                        'lead_status_id' => $this->input->post('leadstatus'),
                        'lead_substatus_id' => $substatus_id[0],
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('assignedto'),
                        'appointment_due_date' => $appiontment_date,
                        'not_able_to_get_appiontment' => $reason_no_appointment,
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                        'status_action_type' => "Update"
                    );
                } else if ($substatus_id[0] == 3) {
                    $today_date = date('Y-m-d');
                    $lead_status_mailalert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('branch'),
                        'lead_status_id' => $this->input->post('leadstatus'),
                        'lead_substatus_id' => $substatus_id[0],
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('assignedto'),
                        'appointment_due_date' => @$appiontment_date,
                        'not_able_to_get_appiontment' => $reason_no_appointment,
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                        'status_action_type' => "Update"
                    );
                }
            }
            if ($substatus_id[0] == 7) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('assignedto'),
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                );
            }
              if ($substatus_id[0] == 21) 
              {
                $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($leadid,$substatus_id[0]);
               } 
            if ($substatus_id[0] == 15) 
            {
                $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($substatus_id[0],$samle_reject_count));
                $leaddetails_close = array(
                    'lead_close_status' => 1,
                    'lead_close_option' => $sublog_lead_substatus_name,
                    'lead_close_comments' => "RevertBack",
                    'last_modified' => date('Y-m-d:H:i:s'),
                    'last_updated_user' => $login_user_id
                );


                $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $leadid);
                //  print_r($leaddetails_close);
            }

            /*Start for Sample Trails status*/
                if ( $substatus_id[0] == 16 ||  $substatus_id[0] == 17 || $substatus_id[0] == 18 || $substatus_id[0] == 19 || $substatus_id[0] == 20 || $substatus_id[0] == 21 || $substatus_id[0] == 27) 
                    {
    
                           if ($substatus_id[0] == 21) 
                            {
                               
                                if ($samle_reject_count>1)
                                {
                                    $today_date = date('Y-m-d:H:i:s');
                                         $lead_status_mailalert = array('leadid' =>$leadid,
                                        'user_id' => $login_user_id,
                                        'branch' => $this->input->post('branch'),
                                        'lead_status_id' => 8,
                                        'lead_substatus_id' => 33,
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $this->input->post('assignedto'),
                                        'sample_reject_reason' => $sample_rejected_reason,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "Sample Rejected"
                                    );

                                        $sublog_lead_substatus_name = 
                                        $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($substatus_id[0],$samle_reject_count));
                                        $leaddetails_close = array(
                                        'lead_close_status' => 1,
                                        'lead_close_option' => $sublog_lead_substatus_name,
                                        'lead_close_comments' => "RevertBack",
                                        'last_modified' => date('Y-m-d:H:i:s'),
                                        'last_updated_user' => $login_user_id
                                        );

                                        $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $leadid);
                                }
                                else 
                                {
                                    $today_date = date('Y-m-d:H:i:s');
                                         $lead_status_mailalert = array('leadid' =>$leadid,
                                        'user_id' => $login_user_id,
                                        'branch' => $this->input->post('branch'),
                                        'lead_status_id' => $this->input->post('leadstatus'),
                                        'lead_substatus_id' => $substatus_id[0],
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $this->input->post('assignedto'),
                                        'sample_reject_reason' => $sample_rejected_reason,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "RevertBack"
                                    );
                                }
                                
                            }
                            else if ($substatus_id[0] == 20) 
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            // Order Cancelled start
                            else if ($substatus_id[0] == 27) 
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'order_cancelled_reason' => $order_cancelled_reason,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            // Order Cancelled end 
                            else if ($substatus_id[0] == 18 || $substatus_id[0] == 19) 
                            {
                               $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                    'status_action_type' => "Update"
                                ); 
                            }
                             else if ($substatus_id[0] == 16 ) 
                            {
                               $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('assignedto'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                ); 
                            }
      
                }
                /*END for Sample trails status*/
                /*Start for Enquiry/offer*/
                if ($substatus_id[0] == 26) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('assignedto'),
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                    );
                }
                /*End for Enquiry/offer*/



            $leadaddress = array('leadaddressid' => $leadid,
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'street' => $this->input->post('street'),
                'pobox' => $this->input->post('postalcode'),
                'phone' => $this->input->post('phone'),
                'country' => $this->input->post('country'),
                'mobile_no' => $this->input->post('mobile_no'),
                'fax' => $this->input->post('fax'),
                'last_modified' => date('Y-m-d:H:i:s')
            );


            $proddata = array();
            $potential_updated = array();
            $lead_customer_pontential = array();
            $k = 0;

            $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
            $producttypeid = $this->Leads_model->get_leadproduct_saletypeids();

            $proddata[0]['lpid'] = $_POST['leadprodid'][0];
            $proddata[0]['productid'] = $_POST['customFieldName'][0];
            $proddata[0]['quantity'] = $_POST['customFieldValue'][0];
            $proddata[0]['last_modified'] = date('Y-m-d:H:i:s');
            $proddata[0]['last_updated_user'] = $login_user_id;
            //  echo"<pre>proddata ";print_r($proddata);echo"</pre>";
            foreach ($_POST['customDispatch'] as $key => $val) 
            {

                $businesscat_type_name = array_search($_POST['customDispatch'][$key], $producttypeid); // $key = 2;
                $lead_prod_poten_type[$key]['leadid'] = $leadid;
                $lead_prod_poten_type[$key]['productid'] = $_POST['customFieldName'][0];
                $lead_prod_poten_type[$key]['product_type_id'] = $_POST['customDispatch'][$key];
                $lead_prod_poten_type[$key]['potential'] = $_POST['customFieldPoten'][$key];
                
                $lead_customer_pontential[$key]['id'] = $leadid;
                $lead_customer_pontential[$key]['line_id'] = $_POST['leadprodid'][$key];
                $lead_customer_pontential[$key]['user1'] = strtoupper($duser_for_update);
                $lead_customer_pontential[$key]['customergroup'] = $customergroup;
                $lead_customer_pontential[$key]['user_code'] = $_POST['assignedto'];
                $lead_customer_pontential[$key]['customer_number'] = $customer_number;
                $lead_customer_pontential[$key]['customer_name'] = $customer_name;
                $lead_customer_pontential[$key]['itemgroup'] = $itemgroup_name;
                $lead_customer_pontential[$key]['types'] = "LEAD";
                $lead_customer_pontential[$key]['collector'] = $this->input->post('branch');
                $lead_customer_pontential[$key]['lead_created_date'] = date('Y-m-d:H:i:s');
                $lead_customer_pontential[$key]['yearly_potential_qty'] = ($_POST['customFieldPoten'][$key] * 12);
                //$lead_customer_pontential[$key]['businesscategory'] = $business_cat_type[$businesscat_type_name];
                $lead_customer_pontential[$key]['businesscategory'] = strtoupper($product_sale_type[$businesscat_type_name]['n_value_displayname']);
            } // End of For Loop

            $lead_pord_poten_id = $this->Leads_model->update_leadprodpotentypes($lead_prod_poten_type, $leadid);

            $lead_pord_poten_id = $this->Leads_model->update_leadcustomer_potential_update($lead_customer_pontential, $leadid);
            $id = $this->Leads_model->update_lead($leaddetails, $leadid);
            if ($cust_account_id == "") {
                $temp_custmasterhdr_id = $this->Leads_model->update_custmastrhdr_assignto($this->input->post('assignedto'), $this->input->post('company'));
                    $customerhdr_email_contact = array('contact_mailid' => $this->input->post('email_id'),
                                     'contact_persion' => $this->input->post('contact_name'),
                                     'contact_no' => $this->input->post('phone')
                                     );
                    
                 $this->Leads_model->update_custmastrhdr_addlead($customerhdr_email_contact,$this->input->post('company'));

            }
           
           // echo"mailalert array <pre>";print_r($lead_status_mailalert); echo"</pre>";
            if ($id) {
                //$addid = $this->Leads_model->update_lead_address($leadaddress, $leadid); //commented by suresh for skipping leadaddress table

                if ($_POST['customFieldValue'][0] != '') {

                    $prdetid = $this->Leads_model->update_lead_products_alltype($proddata, $leadid);
                    //echo"last prdetid id ".$prdetid;
                }
                if ($substatus_id[0] == 3 || $substatus_id[0] == 4 || $substatus_id[0] == 5 || $substatus_id[0] == 6 || $substatus_id[0] == 7 || $substatus_id[0] == 16 || $substatus_id[0] == 17|| $substatus_id[0] == 18|| $substatus_id[0] == 19|| $substatus_id[0] == 20|| $substatus_id[0] == 21 || $substatus_id[0] == 26 || $substatus_id[0] == 27 || $substatus_id[0] == 28 || $substatus_id[0] == 29) {
                    $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_update(@$lead_status_mailalert);
                }
            }

            if (@$addid) {
                $message = "Updated Lead details ";
            } else {
                $message = "";
            }
          //  $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($leadid,$substatus_id[0]);
            $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->input->post('leadstatus'));
            $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($substatus_id[0]);
            $lead_log_details = array('lh_lead_id' => $leadid,
                'lh_user_id' => $login_user_id,
                'lh_lead_curr_status' => $log_lead_status_name,
                'lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lh_updated_date' => date('Y-m-d:H:i:s'),
                'lh_last_updated_user' => $login_user_id,
                'lh_comments' => trim($this->input->post('comments')),
                'action_type' => 'Update',
                'modified_user_name' => $login_username,
                'assignto_user_id ' => $this->input->post('assignedto'),
                'assignto_user_name' => $lead_assign_name,
                'status_update' => $lead_status_update
            );
            if ($update_log == 1) {
                @$logid = $this->Leads_model->create_leadlog($lead_log_details);
            }
           // $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($leadid,$substatus_id[0]);
           
            $lead_sublog_details = array(
                'lhsub_lh_id' => @$logid,
                'lhsub_lh_lead_id' => $leadid,
                'lhsub_lh_user_id' => $login_user_id,
                'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                'lhsub_lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                'lhsub_lh_lead_curr_sub_statusid' => $substatus_id[0],
                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                'lhsub_lh_last_updated_user' => $login_user_id,
                'lhsub_lh_comments' => trim($this->input->post('comments')),
                'lhsub_action_type' => 'Update',
                'lhsub_modified_user_name' => $login_username,
                'lhsub_assignto_user_id ' => $this->input->post('assignedto'),
                'lhsub_assignto_user_name' => $lead_assign_name,
                'lhsub_status_update' => $lead_status_update
            );

            if ($update_log == 1) {
                $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
            }
            $update_date = $this->Leads_model->update_prev_moddate(@$logid);
            //echo"sample reject count ".$samle_reject_count."<br>";
            //if ($substatus_id[0] == 5 || $substatus_id[0] == 15 || $substatus_id[0] == 20 || $substatus_id[0] == 21 ) // Order Cancelled
            if ($substatus_id[0] == 5 || $substatus_id[0] == 15 || $substatus_id[0] == 20 || $substatus_id[0] == 21 || $substatus_id[0] == 27) 
            {
                // update leadsubstatus in leaddetails_update
                /* Start update leaddetails */
                $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count));
                $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($substatus_id[0],$samle_reject_count));
                $leaddetails_update = array(
                    'leadstatus' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'company' => $this->input->post('company'),
                    'customer_id' => $customer_id,
                    'leadsource' => $this->input->post('leadsource'),
                    'collector' => $this->input->post('branch'),
                    'user_branch' => $user_branch,
                    'industry_id' => $this->input->post('industry'),
                    'email_id' => $this->input->post('email_id'),
                    'firstname' => $this->input->post('firstname'),
                    'lastname' => $this->input->post('lastname'),
                    'leadsource' => $this->input->post('leadsource'),
                    'designation' => $this->input->post('designation'),
                    'crd_id' => $this->input->post('credit_assesment'),
                    'crd_assesment' => $lead_crd,
                    'assignleadchk' => $this->input->post('assignedto'),
                    'description' => $this->input->post('description'),
                    'ldsubstatus' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                    'lead_crm_soc_no' => $crm_first_soc_no,
                    'app_sync_flag' => 'N',
                    'converted' => $ld_converted,
                    'comments' => $this->input->post('comments'),
                    'producttype' => trim($this->input->post('producttype')),
                    'exporttype' => trim($this->input->post('exportdomestic')),
                    'presentsource' => trim($this->input->post('presentsource')),
                    'decisionmaker' => trim($this->input->post('purchasedecision')),
                    'domestic_supplier_name' => $domestic_supplier_name,
                    'website' => $this->input->post('website'),
                    'nextstepdate' => date('Y-m-d'),
                    'last_modified' => date('Y-m-d:H:i:s'),
                    'last_updated_user' => $login_user_id
                );
                $id = $this->Leads_model->update_lead($leaddetails_update, $leadid);

                /* Endt update leaddetails_update */

                // insert a record in lead log details
                /* Start lead log details revert back */
                $lead_log_details_update = array('lh_lead_id' => $leadid,
                    'lh_user_id' => $login_user_id,
                    'lh_lead_curr_status' => $log_lead_status_name,
                    'lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'lh_updated_date' => date('Y-m-d:H:i:s'),
                    'lh_last_updated_user' => $login_user_id,
                    'lh_comments' => trim($this->input->post('comments')),
                    'action_type' => 'RevertBack',
                    'modified_user_name' => $login_username,
                    'assignto_user_id ' => $this->input->post('assignedto'),
                    'assignto_user_name' => $lead_assign_name,
                    'status_update' => $lead_status_update
                );

                $logid = $this->Leads_model->create_leadlog($lead_log_details_update);
                /* End lead log details revert back */

                // insert a record in lead sub log details
                /* Start revert back sub log details */
                $lead_sublog_details_update = array(
                    'lhsub_lh_id' => @$logid,
                    'lhsub_lh_lead_id' => $leadid,
                    'lhsub_lh_user_id' => $login_user_id,
                    'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                    'lhsub_lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                    'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                    'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                    'lhsub_lh_last_updated_user' => $login_user_id,
                    'lhsub_lh_comments' => trim($this->input->post('comments')),
                    'lhsub_action_type' => "RevertBack",
                    'lhsub_modified_user_name' => $login_username,
                    'lhsub_assignto_user_id ' => $this->input->post('assignedto'),
                    'lhsub_assignto_user_name' => $lead_assign_name,
                    'lhsub_status_update' => $lead_status_update
                );
                $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);

                $today_date = date('Y-m-d');
                if($substatus_id[0] == 5)
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '48 hours'";    
                }
                else if($substatus_id[0] == 20)
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                }
                else if($substatus_id[0] == 21)
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'";
                } 
                else 
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'"; 
                }
                $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('branch'),
                    'lead_status_id' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('assignedto'),
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => $mail_alert_rev_date,
                    'status_action_type' => "RevertBack"
                );
                //echo"<pre> revert array"; print_r($update_leadstatus_mailalert_revert); echo"</pre>";
          
                 if ($samle_reject_count <= 1 )  
                {
                    $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_revert($update_leadstatus_mailalert_revert);
                }
                if ($samle_reject_count== 2 )  
                 {
                   
                    $leaddetails_close = array(
                        'lead_close_status' => 1,
                        'lead_close_option' => $sublog_lead_substatus_name,
                        'lead_close_comments' => "RevertBack",
                        'last_modified' => date('Y-m-d:H:i:s'),
                        'last_updated_user' => $login_user_id
                    );

                //print_r($leaddetails_close);
                  $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $leadid);
                 }
                /* End revert back sub log details  */
            }

            if($this->input->post('branch')== 'CHENNAI - GC')
            {
                require('../mobileapps/release2D/staging/class_sendleads.php');
                $objA = new A();
                $objA->foo();  
            }
           
            
            redirect('leads/viewleaddetails/'.$leadid);
        }
    }
   

    function saveleaddailycall() {

        $dte = $this->input->post('uploaded_date');
        $from_leadpage = $this->input->post('from_leadpage');
        $hdncustomer_id = $this->input->post('hdncustomer_id');
        $reffer_page = $this->input->post('hdn_refferer');  //dailycall

        $dt = new DateTime();
        $date = $dt->createFromFormat('d/m/Y', $dte);
        $dates = $date->format('Y-m-d');
        $time = date('H:i:s');
        $uploaded_time = $dates . " " . $time;
        $hdn_grid_row_data = json_decode($_POST['hdn_grid_row_data'], TRUE);

        if ($this->input->post('hdn_saveleads')) {
            $login_user_id = $this->session->userdata['user_id'];
            $login_username = $this->session->userdata['username'];
            $lead_seq1 = $this->Leads_model->GetNextSeqVal('leaddetails_leadid_seq');
            $lead_status_name = $this->Leads_model->GetLeadStatusName($this->input->post('leadstatus'));
            $lead_sub_status_name = $this->Leads_model->GetLeadSubStatusName($this->input->post('leadsubstatus'));

            $assign_to_array = $this->Leads_model->GetAssigntoName($this->input->post('assignedto'));
            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
            $duser = $assign_to_array['0']['duser'];

            $cust_account_id = $this->Leads_model->CheckNewCustomer($this->input->post('company'));


            $lead_seq1 = $lead_seq1 + 1;
            $lead_src = $this->Leads_model->GetLeadSourceVal($this->input->post('leadsource'));

            $lead_no = 'LEAD-' . $lead_src;
            $customer_id = $this->Leads_model->GetTempCustId($this->input->post('company'));

            $customer_details = $this->Leads_model->GetCustomerdetails($this->input->post('company'));
            $customer_number = $customer_details['customer_number'];
            $customer_name = $customer_details['customer_name'];


            if ($customer_details['customergroup'] != "") {
                $customergroup = $customer_details['customergroup'];
            } else {
                $customergroup = $customer_details['tempcustname'];
            }

            if ($this->input->post('presentsource') == "Domestic and Import" || $this->input->post('presentsource') == "Domestic") {
                $domestic_supplier_name = trim($this->input->post('txtDomesticSource'));
            } else {
                $domestic_supplier_name = "";
            }

            $leaddetails = array('lead_no' => $lead_no,
                'leadstatus' => $this->input->post('leadstatus'),
                'company' => $this->input->post('company'),
                'customer_id' => $customer_id,
                'email_id' => trim($this->input->post('email_id')),
                'firstname' => $this->input->post('firstname'),
                'industry_id' => $this->input->post('industry'),
                'lastname' => $this->input->post('lastname'),
                'uploaded_date' => $uploaded_time,
                'leadsource' => $this->input->post('leadsource'),
                'ldsubstatus' => $this->input->post('leadsubstatus'),
                'assignleadchk' => $this->input->post('assignedto'),
                'user_branch' => $this->input->post('branch'),
                'description' => trim($this->input->post('description')),
                'comments' => trim($this->input->post('comments')),
                'producttype' => trim($this->input->post('producttype')),
                'exporttype' => trim($this->input->post('exportdomestic')),
                'presentsource' => trim($this->input->post('presentsource')),
                'decisionmaker' => trim($this->input->post('purchasedecision')),
                'domestic_supplier_name' => $domestic_supplier_name,
                'createddate' => date('Y-m-d:H:i:s'),
                'last_modified' => date('Y-m-d:H:i:s'),
                'created_user' => $login_user_id
            );




            foreach ($hdn_grid_row_data as $key => $val) {
                if ($hdn_grid_row_data[$key]['product_id'] != "") {
                    $lead_id = $this->Leads_model->save_lead($leaddetails);
                    if ($lead_id > 0) {
                        $leadaddress = array('leadaddressid' => $lead_id,
                            'city' => $this->input->post('city'),
                            'street' => $this->input->post('street'),
                            'state' => $this->input->post('state'),
                            'pobox' => $this->input->post('postalcode'),
                            'country' => $this->input->post('country'),
                            'mobile_no' => $this->input->post('mobile'),
                            'phone' => $this->input->post('phone'),
                            'fax' => $this->input->post('fax'),
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );
                        $addid = $this->Leads_model->save_lead_address($leadaddress);
                        $itemgroup_name = $this->Leads_model->GetItemgroup($hdn_grid_row_data[$key]['product_id']);
                        if ($itemgroup_name['itemgroup'] != "") {
                            $itemgroup_name = $itemgroup_name['itemgroup'];
                        } else {
                            $itemgroup_name = $itemgroup_name['description'];
                        }
                        if ($hdn_grid_row_data[$key]['requirment'] == "") {
                            $hdn_grid_row_data[$key]['requirment'] = 0;
                        }
                        $leadproducts = array('leadid' => $lead_id,
                            'productid' => $hdn_grid_row_data[$key]['product_id'],
                            'quantity' => $hdn_grid_row_data[$key]['requirment'],
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );
                        $proddata[$key]['leadid'] = $lead_id;
                        $proddata[$key]['productid'] = $hdn_grid_row_data[$key]['product_id'];
                        $prdetid = $this->Leads_model->save_lead_products_all($leadproducts);
                        if ($hdn_grid_row_data[$key]['product_id'] != '') {

                        /*$producttypeid = array("0" => "173794", "1" => "1", "2" => "681046", "3" => "173795", "4" => "3", "5" => "681045");
                          $potential_cat_type = array("0" => "bulk", "1" => "repack", "2" => "small_packing", "3" => "intact", "4" => "part_tanker", "5" => "single_tanker");
                        */
                            $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
                            $lpid_seq = $this->Leads_model->GetNextlpid($lead_id);
                            $lpid_seq = $lpid_seq;
                            for ($k = 0; $k < count($product_sale_type); $k++) {

                                $lead_prod_poten_type[$k]['leadid'] = $lead_id;
                                $lead_prod_poten_type[$k]['productid'] = $hdn_grid_row_data[$key]['product_id'];
                                $lead_prod_poten_type[$k]['product_type_id'] = $product_sale_type[$k]['n_value_id'];
                                if ($hdn_grid_row_data[$key][$product_sale_type[$k]['n_value']] != "") {
                                    $lead_prod_poten_type[$k]['potential'] = $hdn_grid_row_data[$key][$product_sale_type[$k]['n_value']];
                                } else {
                                    $lead_prod_poten_type[$k]['potential'] = 0;
                                }
                            }
                            $lead_pord_poten_id = $this->Leads_model->save_leadprodpotentypes($lead_prod_poten_type);

                            $k = 0;
                            $business_cat_type = array("0" => "BULK", "1" => "REPACK", "2" => "SMALL PACKING", "3" => "INTACT", "4" => "PART TANKER", "5" => "SINGLE - TANKER");

                            for ($m = 0; $m <= 5; $m++) {

                                $lead_customer_pontential[$m]['id'] = $lead_id;
                                $lead_customer_pontential[$m]['line_id'] = $lpid_seq;
                                $lead_customer_pontential[$m]['user1'] = strtoupper($duser);
                                $lead_customer_pontential[$m]['customergroup'] = $customergroup;
                                $lead_customer_pontential[$m]['itemgroup'] = $itemgroup_name;
                                $lead_customer_pontential[$m]['customer_number'] = $customer_number;
                                $lead_customer_pontential[$m]['customer_name'] = $customer_name;
                                $lead_customer_pontential[$m]['types'] = "LEAD";
                                $lead_customer_pontential[$m]['collector'] = $this->input->post('branch');
                                $lead_customer_pontential[$m]['lead_created_date'] = date('Y-m-d:H:i:s');
                                $lead_customer_pontential[$m]['user_code'] = $this->input->post('assignedto');
                                $lead_customer_pontential[$m]['businesscategory'] = $business_cat_type[$m];
                                if ($hdn_grid_row_data[$key][$potential_cat_type[$m]] != "") {
                                    //echo"in if ---";
                                    $lead_customer_pontential[$m]['yearly_potential_qty'] = ($hdn_grid_row_data[$key][$potential_cat_type[$m]] * 12);
                                } else {
                                    //echo"in else --- ";
                                    $lead_customer_pontential[$m]['yearly_potential_qty'] = 0;
                                }
                            }
                            $m = 0;
                            $lead_pord_poten_id = $this->Leads_model->save_leadcustomer_potential_update($lead_customer_pontential);
                        }
                    }// end  of if($lead_id>0)
                } // end of if($hdn_grid_row_data[$key]['product_id']!="")	
            } // end of foreach($hdn_grid_row_data as $key=>$val)

            $custmastrhdr = array(
                'executivename' => $lead_assign_name,
                'execode' => $this->session->userdata['loginname']
            );
            // before updating customermaster check if it is only a new customer.
            if ($cust_account_id == "") {
                $temp_custmasterhdr_id = $this->Leads_model->update_custmastrhdr_assignto($this->input->post('assignedto'), $this->input->post('company'));
            }
            //$temp_custmaster_id = $this->Leads_model->update_tempcustmaster_leadid($lead_id,$this->input->post('company'),$login_user_id);
            $temp_itemmaster_id = $this->Leads_model->update_tempitemmaster_leadid($lead_id, $proddata, $login_user_id);
            $lead_log_details = array('lh_lead_id' => $lead_id,
                'lh_user_id' => $login_user_id,
                'lh_lead_curr_status' => $lead_status_name,
                'lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lh_created_date' => date('Y-m-d:H:i:s'),
                'lh_created_user' => $login_user_id,
                'lh_comments' => $this->input->post('comments'),
                'action_type' => 'Insert',
                'created_user_name' => $login_username,
                'assignto_user_id ' => $this->input->post('assignedto'),
                'assignto_user_name' => $lead_assign_name
            );
            $logid = $this->Leads_model->create_leadlog($lead_log_details);

            $lead_sublog_details = array(
                'lhsub_lh_id' => $logid,
                'lhsub_lh_lead_id' => $lead_id,
                'lhsub_lh_user_id' => $login_user_id,
                'lhsub_lh_lead_curr_status' => $lead_status_name,
                'lhsub_lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lhsub_lh_lead_curr_sub_status' => $lead_sub_status_name,
                'lhsub_lh_lead_curr_sub_statusid' => $this->input->post('leadsubstatus'),
                'lhsub_lh_comments' => $this->input->post('comments'),
                'lhsub_lh_created_date' => date('Y-m-d:H:i:s'),
                'lhsub_lh_created_user' => $login_user_id,
                'lhsub_action_type' => 'Insert',
                'lhsub_created_user_name' => $login_username,
                'lhsub_assignto_user_id ' => $this->input->post('assignedto'),
                'lhsub_assignto_user_name' => $lead_assign_name
            );


            $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);

            $this->session->set_flashdata('message', "Lead Created Successfully");
            echo "ref page " . $reffer_page;
            echo $url = base_url();

            if ($reffer_page == "dailycall") {
                //redirect($url.'dailycall/customerdetails/'.$hdncustomer_id.'/0');
                redirect($url . 'dailycall/customerdetails/' . $customergroup . '/0');
            } else {
                redirect('leads');
            }
        } // end of if($this->input->post('hdn_saveleads'))
    }

// end of function 	


    

    function revert_substatus_old($substatus_rever) {
        if ($substatus_rever == 5) {
            return 3;
        } else if ($substatus_rever == 15) {
            return 30;
        }
        else if ($substatus_rever == 21) {
            return 16;
        }
    }

    function revert_substatus($substatus_rever,$samle_reject_count) {
        /*echo" in revert_substatus substatus id ".$substatus_rever."<br>";
        echo" in revert_substatus samle_reject_count ".$samle_reject_count."<br>";*/
        if ($substatus_rever == 5) {
            return 3;
        } else if ($substatus_rever == 15) {
            return 30;
        }
        else if ($substatus_rever == 21 && $samle_reject_count <= 1) {
            //echo "return 16"."<br>";
            return 16;
        }
        else if ($substatus_rever == 21 && $samle_reject_count > 1) {
              //echo "return 33"."<br>";
            return 33;
        }
        else if ($substatus_rever == 20 ) {
              //echo "return 24"."<br>";
            return 24;
        }
        else if ($substatus_rever == 27 ) {  // Order Cancelled
              //echo "return 24"."<br>";
            return 23;
        }
        else 
            return $substatus_rever;
    }

    function revert_status($status_rever, $substatus_rever,$samle_reject_count=0) {
        /*echo" status_rever ".$status_rever."<br>";
        echo" substatus_rever ".$substatus_rever."<br>";
        echo" in revert_status samle_reject_count ".$samle_reject_count."<br>";*/
        
        if ($status_rever == 1) {
            return $status_rever;

        } else if (($status_rever == 3) && ($substatus_rever == 15)) {
            return 8;
        }
        else if (($status_rever == 3) && ($substatus_rever != 15)) {
            return $status_rever;
        }
        else if (($status_rever == 4) && ($substatus_rever == 21) && ($samle_reject_count<=1)) {
         //   echo "samle_reject_count < 0"."<br>"; echo "return status 4"."<br>";
            return 4;
        }
        else if (($status_rever == 4) && ($substatus_rever == 21) && ($samle_reject_count>1)) {
           // echo "samle_reject_count > 0"."<br>"; echo "return status 8"."<br>";
            return 8;
        }
        else if (($status_rever == 4) && ($substatus_rever == 20)) {
                   //echo "return status same"."<br>";
            return 5;
        }
        else if (($status_rever == 4) && ($substatus_rever != 21)) {
                 //  echo "return status same"."<br>";
            return $status_rever;
        }
        else if (($status_rever == 5) && ($substatus_rever == 27)) { // Order Cancelled
                 //  echo "return status same"."<br>";
            return $status_rever;
        }
    }

    function revert_action_type($status_rever, $substatus_rever) {
        if ($status_rever == 1) {
            return "RevertBackSame";
        } else if (($status_rever == 15) && ($substatus_rever == 3)) {
            return "RevertBackDiff";
        }
    }

    function colselead($id) {
        //print_r($_POST); 
        //print($this->input->post);

        $leadid = $id;

        $login_user_id = $this->session->userdata['user_id'];
        $login_username = $this->session->userdata['username'];
        $leaddetails = array(
            'lead_close_status' => 1,
            'lead_close_option' => $_POST['closeleadoptions'],
            'lead_close_comments' => $_POST['closingcomments'],
            'last_modified' => date('Y-m-d:H:i:s'),
            'last_updated_user' => $login_user_id
        );

        $id = $this->Leads_model->update_leadclosestatus($leaddetails, $leadid);
        //$this->load->view('leads/viewleadsnewsort');	
        $this->session->set_flashdata('message', "Lead closed Successfully");
        redirect('leads');
        //$this->index();
    }

    function editstatus($id) {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        $this->session->set_userdata('run_time_lead_id', $id);

        $reportingid = $this->session->userdata['loginname'];
        //$user_list_ids = $this->Leads_model->get_user_list_ids($reportingid);
        $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $get_assign_to_user_id = array('get_assign_to_user_id' => $user_list_ids); //set it
        $this->session->set_userdata($get_assign_to_user_id);

        if ($this->session->userdata['reportingto'] == "") {

            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details_all($id);
        } else {
            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details($id);
        }
        $company_id = $leaddata['leaddetails'][0]['company'];
        $leadassigntoid = $leaddata['leaddetails'][0]['assignleadchk'];
        $leadstusid = $leaddata['leaddetails'][0]['leadstatusid'];
        $substatusid = $leaddata['leaddetails'][0]['ldsubstatus'];
        $leadstus_order_id = $leaddata['leaddetails'][0]['order_by'];
        $lst_order_by_id = $leaddata['leaddetails'][0]['lst_order_by'];
        $lst_parentid_id = $leaddata['leaddetails'][0]['lst_parentid'];
        $branch = $leaddata['leaddetails'][0]['user_branch'];
        $collector = $leaddata['leaddetails'][0]['collector'];
        
      //  $countryid = $leaddata['leaddetails'][0]['country'];
     //   $stateid = $leaddata['leaddetails'][0]['state'];

        /*Start*/

        $customer_address = $this->Leads_model->getcustomerdetails_view($company_id);
       // echo"<pre>";print_r($customer_address);echo"</pre>"; die;
        $leaddata['cust_account_id']= $customer_address['cust_account_id'];
        $leaddata['country']= $customer_address['contryname'];
        $leaddata['state'] = $customer_address['statename'];
        $leaddata['city'] = $customer_address['cityname'];
        $leaddata['address'] = trim($customer_address['address']);
        $leaddata['postalcode'] = $customer_address['postalcode'];
        $leaddata['mobile_no'] = $customer_address['mobile_no'];
        $leaddata['fax'] = $customer_address['fax'];
        $leaddata['contact_person']= $customer_address['contact_person'];
        $leaddata['contact_number'] = $customer_address['contact_number'];
        $leaddata['contact_mailid'] = $customer_address['contact_mailid'];
       /*End*/

        $leaddata['optionslst'] = $this->Leads_model->get_leadstatus_edit($leadstusid, $leadstus_order_id);
        $leaddata['optionslsubst'] = $this->Leads_model->get_substatus_edit_all($substatusid, $lst_parentid_id, $lst_order_by_id);
        $leaddata['appiontment_date'] = str_replace("-", "/", $this->Leads_model->get_appointment_date($id, $substatusid));
        $leaddata['no_reason'] = $this->Leads_model->get_appointment_noreason($id, $substatusid);
        $leaddata['lead_2pa_no'] = $this->Leads_model->get_lead_2panumber($id, $substatusid);
        //$leaddata['lead_2pa_no']='ZSD786';
        $leaddata['optionslsr'] = $this->Leads_model->get_leadsource();
        $leaddata['optionscmp'] = $this->Leads_model->get_all_company();
      //  $leaddata['optionscnt'] = $this->Leads_model->get_country();
        $leaddata['optionsinds'] = $this->Leads_model->get_industry();
       // $leaddata['optionsst'] = $this->Leads_model->get_states_edit($countryid);
      //  $leaddata['optionsct'] = $this->Leads_model->get_city_edit($stateid);
        if ($this->session->userdata['reportingto'] == "") {
            $leaddata['optionsasto'] = $this->Leads_model->get_assignto_users();
        } else {
            $leaddata['optionsasto'] = $this->Leads_model->get_assignto_users_order($this->session->userdata['reportingto']);
        }

        $leaddata['leadproducts'] = $this->Leads_model->get_lead_product_details($id);
        $product_name = $this->Leads_model->get_productname($leaddata['leadproducts'][0]['productid']);
        $leaddata['closedlead'] = $leaddata['leaddetails']['0']['lead_close_status'];
        $leaddata['optionsproedit'] = $this->Leads_model->get_products_edit();
        $leaddata['data'] = $this->Leads_model->get_synched_products($company_id, $product_name);

        $this->load->view('leads/editleadsstatus', $leaddata);
    }

    function updateleadstatus($id) {

        $login_user_id = $this->session->userdata['user_id'];
        $login_username = $this->session->userdata['username'];
        $substatus_id = explode("-", $this->input->post('leadsubstatus'));
        $sample_rejected_reason = $this->input->post('sample_rejected_reason');
        $order_cancelled_reason = $this->input->post('order_cancelled_reason');
      // echo"<pre>"; print_r($_POST); echo"<pre>"; 
        $samle_reject_count=0;

        if ($this->input->post('updateleadstatus')) {
            /*START 1*/
            $assign_to_array = $this->Leads_model->GetAssigntoName($this->input->post('hdn_assignto_id'));

            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
            $duser_for_update = $assign_to_array['0']['duser'];
            $duser = $assign_to_array['0']['duser'];
            $customer_id = $this->Leads_model->GetTempCustId($this->input->post('hdn_company'));
            $cust_account_id = $this->Leads_model->CheckNewCustomer($this->input->post('hdn_company'));
            //$customer_details = $this->Leads_model->GetCustomerdetails($this->input->post('hdn_company'));
            $customer_details = $this->Leads_model->getcustomerdetails_view($this->input->post('hdn_company'));
            $itemgroup_name = $this->Leads_model->GetItemgroup($_POST['customFieldName'][0]);
            if ($itemgroup_name['itemgroup'] != "") {
                $itemgroup_name = $itemgroup_name['itemgroup'];
            } else {
                $itemgroup_name = $itemgroup_name['description'];
            }

            if ($customer_details['customergroup'] != "") {
                $customergroup = $customer_details['customergroup'];
            } else {
                $customergroup = $customer_details['tempcustname'];
            }
            $customer_number = $customer_details['customer_number'];
            $customer_name = $customer_details['customer_name'];

            /*END 1*/


            $dte = $this->input->post('content_appiontment_date');
            $reason_no_appointment = $this->input->post('not_able_to_get_appointment');
            
            if ($dte != "") {
                $dt = new DateTime();
                $date = $dt->createFromFormat('d/m/Y', $dte);
                $dates = $date->format('Y-m-d');
                $time = date('H:i:s');
                //echo" appiontment_date ".$appiontment_date = $dates." ".$time;
                $appiontment_date = $dates;
                $mail_alert_date = $appiontment_date;
            }
            if ($reason_no_appointment != "") {
                $mail_alert_date = date('Y-m-d');
            }
            $leadid = $id;
            $lead_seq1 = $this->Leads_model->GetNextSeqVal('leaddetails_leadid_seq');
            $lead_status_name = $this->Leads_model->GetLeadStatusName($this->input->post('leadstatus'));
            $lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($substatus_id[0]);
            $assign_to_array = $this->Leads_model->GetAssigntoName($this->input->post('hdn_assignto_id'));
            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
           //      echo"<pre>"; print_r($lead_assign_name); echo"<pre>"; 
            //$lead_no = $lead_no = 'LEAD'.$lead_seq1;
            // $lead_desc = $this->input->post('hdn_desc')."-".$this->input->post('description');

            if (($this->input->post('leadstatus') != $this->input->post('hdn_status_id')) || ($substatus_id[0] != $this->input->post('hdn_sub_status_id')) || (trim($this->input->post('comments')) != "")) {
                $update_log = 1;
            } else {
                $update_log = 0;
            }

            if ($this->input->post('leadstatus') != $this->input->post('hdn_status_id') || ($substatus_id[0] != $this->input->post('hdn_sub_status_id') )) {
                $lead_status_update = 'Y';
            } else {
                $lead_status_update = 'N';
            }

            $crm_first_soc_no = $this->input->post('txtLeadsoc');
            if ($crm_first_soc_no == "") {
                $crm_first_soc_no = 0;
                $ld_converted = 0;
            } else {
                $crm_first_soc_no = $this->input->post('txtLeadsoc');
                $ld_converted = 1;
            }
            if ($this->input->post('leadstatus')==8)
                {
                  $lead_close_status=1;
                  $lead_close_option=$lead_substatus_name;
                }
                else
                {
                    $lead_close_status=0;
                    $lead_close_option="";
                }
            $leaddetails = array(
                'leadstatus' => $this->input->post('leadstatus'),
                'ldsubstatus' => $substatus_id[0],
                'comments' => $this->input->post('comments'),
                'email_id' => $this->input->post('email_id'),
                'lead_2pa_no' => $this->input->post('lead_2pa_no'),
                'lead_crm_soc_no' => $crm_first_soc_no,
                'lead_close_status' => $lead_close_status,
                'lead_close_option' => $lead_close_option,
                'lead_close_comments' => $_POST['closingcomments'],
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'app_sync_flag' => 'N',
                'converted' => $ld_converted,
                'nextstepdate' => date('Y-m-d'),
                'last_modified' => date('Y-m-d:H:i:s'),
                'last_updated_user' => $login_user_id
            );

            $id = $this->Leads_model->update_lead($leaddetails, $leadid);
            /* Start of substatus validations */

                /* Start for Managing and implementation*/
                    $today_date = date('Y-m-d');
                    if ($this->input->post('leadstatus') == 6 ) 
                        {
                            $lead_status_mailalert = array('leadid' => $leadid,
                                'user_id' => $login_user_id,
                                'branch' => $this->input->post('branch'),
                                'lead_status_id' => $this->input->post('leadstatus'),
                                'lead_substatus_id' => $substatus_id[0],
                                'last_update_user_id' => $login_user_id,
                                'assignto_id' => $this->input->post('assignedto'),
                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                'status_action_type' => "Update"
                            );
                        }
                    if ($this->input->post('leadstatus') == 7 ) 
                        {
                            $lead_status_mailalert = array('leadid' => $leadid,
                                'user_id' => $login_user_id,
                                'branch' => $this->input->post('hdn_user_branch'),
                                'lead_status_id' => $this->input->post('leadstatus'),
                                'lead_substatus_id' => $substatus_id[0],
                                'last_update_user_id' => $login_user_id,
                                'assignto_id' => $this->input->post('hdn_assignto_id'),
                                'soc_no' => $crm_first_soc_no,
                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                'status_action_type' => "Update"
                            );
                        }                        

                    /* End for Managing and implementation*/
            if ($substatus_id[0] == 6) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('hdn_user_branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '1 hours'",
                    'status_action_type' => "Update"
                );
            } else {
                if (@$appiontment_date != "" && $substatus_id[0] == 4 ) {
                    $lead_status_mailalert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->input->post('leadstatus'),
                        'lead_substatus_id' => $substatus_id[0],
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'appointment_due_date' => $appiontment_date,
                        'not_able_to_get_appiontment' => @$reason_no_appointment,
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                        'status_action_type' => "Update"
                    );
                } else if ($substatus_id[0] == 3) {
                    $today_date = date('Y-m-d');
                    $lead_status_mailalert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->input->post('leadstatus'),
                        'lead_substatus_id' => $substatus_id[0],
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'appointment_due_date' => @$appiontment_date,
                        'not_able_to_get_appiontment' => $reason_no_appointment,
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                        'status_action_type' => "Update"
                    );
                }
            }
            if ($substatus_id[0] == 7) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('hdn_user_branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                );
            }
             /*Start for Enquiry/Offer*/
             if ($substatus_id[0] == 26) {
                $today_date = date('Y-m-d');
                $lead_status_mailalert = array('leadid' => $leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('hdn_user_branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                );
            }
             /*End for Enquiry/Offer*/
             // Order Cancelled start
             if ($substatus_id[0] == 27) 
            {
                $today_date = date('Y-m-d:H:i:s');
                $lead_status_mailalert = array('leadid' =>$leadid,
                    'user_id' => $login_user_id,
                    'branch' => $this->input->post('hdn_user_branch'),
                    'lead_status_id' => $this->input->post('leadstatus'),
                    'lead_substatus_id' => $substatus_id[0],
                    'last_update_user_id' => $login_user_id,
                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                    'order_cancelled_reason' => $order_cancelled_reason,
                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                    'status_action_type' => "Update"
                );
            }
            // Order Cancelled end 
            /*Start for Sample Trails updateleadstatus*/
                        if ($substatus_id[0] == 16 || $substatus_id[0] == 17 || $substatus_id[0] == 18 || $substatus_id[0] == 19 || $substatus_id[0] == 20 || $substatus_id[0] == 21 ) 
                         {
                            if ($substatus_id[0] == 21) 
                            {
                                $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($leadid,$substatus_id[0]);
                                if ($samle_reject_count>1)
                                {
                                    $today_date = date('Y-m-d:H:i:s');
                                         $lead_status_mailalert = array('leadid' =>$leadid,
                                        'user_id' => $login_user_id,
                                        'branch' => $this->input->post('hdn_user_branch'),
                                        'lead_status_id' => 8,
                                        'lead_substatus_id' => 33,
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                                        'sample_reject_reason' => $sample_rejected_reason,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "Sample Rejected"
                                    );
                                }
                                else 
                                {
                                    $today_date = date('Y-m-d:H:i:s');
                                         $lead_status_mailalert = array('leadid' =>$leadid,
                                        'user_id' => $login_user_id,
                                        'branch' => $this->input->post('hdn_user_branch'),
                                        'lead_status_id' => $this->input->post('leadstatus'),
                                        'lead_substatus_id' => $substatus_id[0],
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                                        'sample_reject_reason' => $sample_rejected_reason,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "Update"
                                    );
                                }
                                
                            }
                            else if ($substatus_id[0] == 20)
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('hdn_user_branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            else if ($substatus_id[0] == 18 || $substatus_id[0] == 19)
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('hdn_user_branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            else 
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$leadid,
                                    'user_id' => $login_user_id,
                                    'branch' => $this->input->post('hdn_user_branch'),
                                    'lead_status_id' => $this->input->post('leadstatus'),
                                    'lead_substatus_id' => $substatus_id[0],
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $this->input->post('hdn_assignto_id'),
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                        }
                       // echo"<pre>";print_r($lead_status_mailalert);echo"</pre>";
                        /*END for Sample trails status*/

            /* End of substatus validations */

            /* if leadstatus is greater than prospect update leadaddress,leadproducts,lead_prod_potential_types,potential_updated_table START */


            $leadaddress = array('leadaddressid' => $leadid,
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'street' => $this->input->post('street'),
                'pobox' => $this->input->post('postalcode'),
                'phone' => $this->input->post('phone'),
                'country' => $this->input->post('country'),
                'mobile_no' => $this->input->post('mobile_no'),
                'fax' => $this->input->post('fax'),
                'last_modified' => date('Y-m-d:H:i:s')
            );
            //   echo"<pre>leadaddress ";print_r($leadaddress);echo"</pre>";
           // $addid = $this->Leads_model->update_lead_address($leadaddress, $leadid); //commented by suresh for skipping leadaddress table
/* Start for update in customer master table*/
                            if ($customer_details['customer_number'] == "" or $customer_details['customer_number'] == null )
                             {
                                 $customerhdr_email_contact = array('contact_mailid' => $this->input->post('email_id'),
                                                                    'contact_persion' => $this->input->post('contact_name'),
                                                                    'contact_no' => $this->input->post('phone'),
                                                                     );

                             
                             }
                             else
                             {
                                $exist_customer_detail = $this->Leads_model->getcustomerdetails_view($this->input->post('hdn_company'));
                                {
                                    //print_r($exist_customer_detail);
                                    $customerhdr_email_contact = array();
                                    if ($exist_customer_detail['contact_mailid']=="")
                                        {
                                            $customerhdr_email_contact['contact_mailid']=$this->input->post('email_id');
                                        }
                                        if ($exist_customer_detail['contact_person']=="")
                                        {
                                            $customerhdr_email_contact['contact_persion']=$this->input->post('contact_name');
                                        }
                                        if ($exist_customer_detail['contact_number']=="")
                                        {
                                            $customerhdr_email_contact['contact_no']=$this->input->post('phone');
                                        }
                                        
                                }
                             }

                                $array_len=count($customerhdr_email_contact);
                              

                             if($array_len>0)  
                             {  // check if the array is empty
                                $customerhdr_email_contact['lead_update_user_id']=$login_user_id;
                                $customerhdr_email_contact['leadid']=$leadid;
                                $customerhdr_email_contact['lastupdateuser']=$login_username; // username 
                                $customerhdr_email_contact['lastupdatedate']=date('Y-m-d:H:i:s');
                                
                                
                                $this->Leads_model->update_custmastrhdr_addlead($customerhdr_email_contact,$this->input->post('hdn_company'));   

                             }
                             else
                             {
                                //echo"customerhdr_email_contact is empty in update <br>";
                             }
/**/

            $proddata = array();
            $potential_updated = array();
            $lead_customer_pontential = array();
            $k = 0;


            $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
            $producttypeid = $this->Leads_model->get_leadproduct_saletypeids();

            $proddata[0]['lpid'] = $_POST['leadprodid'][0];
            $proddata[0]['productid'] = $_POST['customFieldName'][0];
            $proddata[0]['quantity'] = $_POST['customFieldValue'][0];
            $proddata[0]['last_modified'] = date('Y-m-d:H:i:s');
            $proddata[0]['last_updated_user'] = $login_user_id;
            //echo"<pre>proddata "; print_r($proddata); echo"</pre>";
            foreach ($_POST['prod_type_id'] as $key => $val) {

                $businesscat_type_name = array_search($_POST['prod_type_id'][$key], $producttypeid); // $key = 2;
                $lead_prod_poten_type[$key]['leadid'] = $leadid;
                $lead_prod_poten_type[$key]['productid'] = $_POST['customFieldName'][0];
                $lead_prod_poten_type[$key]['product_type_id'] = $_POST['prod_type_id'][$key];
                $lead_prod_poten_type[$key]['potential'] = $_POST['customFieldPoten'][$key];

                $lead_customer_pontential[$key]['id'] = $leadid;
                $lead_customer_pontential[$key]['line_id'] = $_POST['leadprodid'][$key];
                $lead_customer_pontential[$key]['user1'] = strtoupper($duser_for_update);
                $lead_customer_pontential[$key]['customergroup'] = $customergroup;
                $lead_customer_pontential[$key]['user_code'] = $_POST['hdn_assignto_id'];
                $lead_customer_pontential[$key]['customer_number'] = $customer_number;
                $lead_customer_pontential[$key]['customer_name'] = $customer_name;
                $lead_customer_pontential[$key]['itemgroup'] = $itemgroup_name;
                $lead_customer_pontential[$key]['types'] = "LEAD";
                $lead_customer_pontential[$key]['collector'] = $this->input->post('hdn_user_branch');
                $lead_customer_pontential[$key]['lead_updated_date'] = date('Y-m-d:H:i:s');
                $lead_customer_pontential[$key]['yearly_potential_qty'] = ($_POST['customFieldPoten'][$key] * 12);
               // $lead_customer_pontential[$key]['businesscategory'] = $business_cat_type[$businesscat_type_name];
                $lead_customer_pontential[$key]['businesscategory'] = strtoupper($product_sale_type[$businesscat_type_name]['n_value_displayname']);
            } // End of For Loop
            
            $lead_pord_poten_id = $this->Leads_model->update_leadprodpotentypes($lead_prod_poten_type, $leadid);

            $lead_pord_poten_id = $this->Leads_model->update_leadcustomer_potential_update($lead_customer_pontential, $leadid);

            $id = $this->Leads_model->update_lead($leaddetails, $leadid);
            if ($cust_account_id == "") {
                $temp_custmasterhdr_id = $this->Leads_model->update_custmastrhdr_assignto($this->input->post('hdn_assignto_id'), $this->input->post('hdn_company'));
                   $customerhdr_email_contact = array('contact_mailid' => $this->input->post('email_id'),
                                     'contact_persion' => $this->input->post('contact_person'),
                                     'contact_no' => $this->input->post('phone')
                                     );
                    
                 $this->Leads_model->update_custmastrhdr_addlead($customerhdr_email_contact,$this->input->post('hdn_company'));
            }
            //echo"lead_status_mailalert <pre>";print_r($lead_status_mailalert); echo"</pre>";
            if ($id) {
                //$addid = $this->Leads_model->update_lead_address($leadaddress, $leadid); //commented by suresh for skipping leadaddress table
                if ($_POST['customFieldValue'][0] != '') {

                    $prdetid = $this->Leads_model->update_lead_products_alltype($proddata, $leadid);
                    
                }
                if ($substatus_id[0] == 3 || $substatus_id[0] == 4 || $substatus_id[0] == 5 || $substatus_id[0] == 6 || $substatus_id[0] == 7 || $substatus_id[0] == 16 || $substatus_id[0] == 17|| $substatus_id[0] == 18|| $substatus_id[0] == 19|| $substatus_id[0] == 20|| $substatus_id[0] == 21 || $substatus_id[0] == 26 || $substatus_id[0] == 27 || $substatus_id[0] == 28 || $substatus_id[0] == 29) {
                    $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_update($lead_status_mailalert);
                }
            }

            /* if leadstatus is greater than prospect update leadaddress,leadproducts,lead_prod_potential_types,potential_updated_table END */

            $lead_log_details = array('lh_lead_id' => $leadid,
                'lh_user_id' => $login_user_id,
                'lh_lead_curr_status' => $lead_status_name,
                'lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lh_updated_date' => date('Y-m-d:H:i:s'),
                'lh_last_updated_user' => $login_user_id,
                'lh_comments' => trim($this->input->post('comments')),
                'action_type' => 'Update',
                'modified_user_name' => $login_username,
                'assignto_user_id ' => $this->input->post('hdn_assignto_id'),
                'assignto_user_name' => $lead_assign_name,
                'status_update' => $lead_status_update
            );




            if ($update_log == 1) {
                @$logid = $this->Leads_model->create_leadlog($lead_log_details);
            }
            $lead_sublog_details = array(
                'lhsub_lh_id' => @$logid,
                'lhsub_lh_lead_id' => $leadid,
                'lhsub_lh_user_id' => $login_user_id,
                'lhsub_lh_lead_curr_status' => $lead_status_name,
                'lhsub_lh_lead_curr_statusid' => $this->input->post('leadstatus'),
                'lhsub_lh_lead_curr_sub_status' => $lead_substatus_name,
                'lhsub_lh_lead_curr_sub_statusid' => $substatus_id[0],
                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                'lhsub_lh_last_updated_user' => $login_user_id,
                'lhsub_lh_comments' => trim($this->input->post('comments')),
                'lhsub_action_type' => 'Update',
                'lhsub_modified_user_name' => $login_username,
                'lhsub_assignto_user_id ' => $this->input->post('hdn_assignto_id'),
                'lhsub_assignto_user_name' => $lead_assign_name,
                'lhsub_status_update' => $lead_status_update
            );
            if ($update_log == 1) {

                $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
            }
            $update_date = $this->Leads_model->update_prev_moddate(@$logid);

            // Start leadsubstatus validation for reverting back 

            
            //if ($substatus_id[0] == 5 || $substatus_id[0] == 15 || $substatus_id[0] == 20 || $substatus_id[0] == 21) // Order Cancelled
            if ($substatus_id[0] == 5 || $substatus_id[0] == 15 || $substatus_id[0] == 20 || $substatus_id[0] == 21 || $substatus_id[0] == 27)
            {
                // update leadsubstatus in leaddetails_update
                /* Start update leaddetails */
                $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($this->input->post('leadstatus'),$substatus_id[0],$samle_reject_count));
                $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($substatus_id[0],$samle_reject_count));

                $leaddetails_update = array(
                    'leadstatus' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'ldsubstatus' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
               /*     'lead_crm_soc_no' => $crm_first_soc_no,
                    'comments' => $this->input->post('comments'),
                    'last_modified' => date('Y-m-d:H:i:s'),*/
                    'last_updated_user' => $login_user_id
                );
                $id = $this->Leads_model->update_lead_status($leaddetails_update, $leadid);

                if ($substatus_id[0] == 15) {
                    $leaddetails_close = array(
                        'lead_close_status' => 1,
                        'lead_close_option' => $sublog_lead_substatus_name,
                        'lead_close_comments' => "RevertBack",
                        'last_modified' => date('Y-m-d:H:i:s'),
                        'last_updated_user' => $login_user_id
                    );
                      //print_r($leaddetails_close);
                  $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $leadid);
                }
                if ($substatus_id[0] == 21 && $samle_reject_count > 1 ) 
                {
                    $leaddetails_close = array(
                        'lead_close_status' => 1,
                        'lead_close_option' => $sublog_lead_substatus_name,
                        'lead_close_comments' => "RevertBack",
                        'last_modified' => date('Y-m-d:H:i:s'),
                        'last_updated_user' => $login_user_id
                    );

                //print_r($leaddetails_close);
                  $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $leadid);
                  
                    
                }
              
                /* Endt update leaddetails_update */

                // insert a record in lead log details
                /* Start */
                $lead_log_details_update = array('lh_lead_id' => $leadid,
                    'lh_user_id' => $login_user_id,
                    'lh_lead_curr_status' => $log_lead_status_name,
                    'lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'lh_updated_date' => date('Y-m-d:H:i:s'),
                    'lh_last_updated_user' => $login_user_id,
                    'lh_comments' => trim($this->input->post('comments')),
                    'action_type' => 'RevertBack',
                    'modified_user_name' => $login_username,
                    'assignto_user_name' => $lead_assign_name,
                    'status_update' => $lead_status_update
                );

                $logid = $this->Leads_model->create_leadlog($lead_log_details_update);
                /* End */

                // insert a record in lead sub log details
                /* Start */
                $lead_sublog_details_update = array(
                    'lhsub_lh_id' => @$logid,
                    'lhsub_lh_lead_id' => $leadid,
                    'lhsub_lh_user_id' => $login_user_id,
                    'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                    'lhsub_lh_lead_curr_statusid' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                    'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                    'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                    'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                    'lhsub_lh_last_updated_user' => $login_user_id,
                    'lhsub_lh_comments' => trim($this->input->post('comments')),
                    'lhsub_action_type' => "RevertBack",
                    'lhsub_modified_user_name' => $login_username,
                    'lhsub_assignto_user_id ' => $this->input->post('hdn_assignto_id'),
                    'lhsub_assignto_user_name' => $lead_assign_name,
                    'lhsub_status_update' => $lead_status_update
                );
                $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);
                $today_date = date('Y-m-d');
                if ($samle_reject_count > 1)
                {
                    $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => 8,
                        'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                        'status_action_type' => "RevertBack"
                    );
                }
                 else if($substatus_id[0] == 20)
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                
                     $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                        'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => $mail_alert_rev_date,
                        'status_action_type' => "RevertBack"
                    );   
                }
                else if ($substatus_id[0] == 21)
                {
                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'";
                     $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                        'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => $mail_alert_rev_date,
                        'status_action_type' => "RevertBack"
                    );   
                } 
                // Order Cancelled start
                 else if ($substatus_id[0] == 27)
                {
                     $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'";
                     $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                        'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => $mail_alert_rev_date,
                        'status_action_type' => "RevertBack"
                    );   
                }
               // Order Cancelled end
                else{
                        
                
                     $update_leadstatus_mailalert_revert = array('leadid' => $leadid,
                        'user_id' => $login_user_id,
                        'branch' => $this->input->post('hdn_user_branch'),
                        'lead_status_id' => $this->revert_status($this->input->post('leadstatus'), $substatus_id[0],$samle_reject_count),
                        'lead_substatus_id' => $this->revert_substatus($substatus_id[0],$samle_reject_count),
                        'last_update_user_id' => $login_user_id,
                        'assignto_id' => $this->input->post('hdn_assignto_id'),
                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                        'status_action_type' => "RevertBack"
                    );   
                }
               // echo"revert array ";print_r($update_leadstatus_mailalert_revert);echo"</pre>";
               // echo" samle_reject_count ".$samle_reject_count."<br>";
                if ($samle_reject_count <= 1 ) 
                    {
                        $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_revert($update_leadstatus_mailalert_revert);
                    }
                /* End */


                // End leadsubstatus validation for reverting back 
            }
             if (trim($this->input->post('hdn_user_branch'))=="CHENNAI")
             {

                require('../mobileapps/release2D/staging/class_sendleads.php');
                $objA = new A();
                $objA->foo();
             }
       
            redirect('leads/viewleaddetails/' . $leadid);
        }
    }

    function addnewcustomer() {
        $data['optionscnt'] = $this->Leads_model->get_country();
        $this->load->view('company/newcompany',$data);
    }

    function test() {
        $loginuser = $this->session->userdata['loginname'];
        //$user_tree = $this->Leads_model->get_subquery_users_order($loginuser);
        // $lead_src = $this->Leads_model->GetLeadSourceVal(3);
         $reportingid = $this->session->userdata['loginname'];
         $user_list_ids=$this->session->userdata['get_assign_to_user_id'];
        $sql="SELECT  leadid from leaddetails WHERE created_user IN ($user_list_ids) union  SELECT  leadid from leaddetails WHERE  assignleadchk in ($user_list_ids)";
        echo $sql; 
        $result = $this->db->query($sql);
         $leaddetails = $result->result_array();
        /* foreach ($leaddetails as $leadids)
          {
                echo"<pre>";print_r($leadids);echo"</pre>";
            }*/
            $leadids = '';
            foreach ($leaddetails as $t)
            {
                $leadids .= $t['leadid'] . ',';
            }
            $result = rtrim($leadids, ',');
            echo"<pre>";print_r($result);echo"</pre>";
        die;
        print_r($this->session->userdata);
    }

    function userTree($parent_id = 0) {
        $query = $this->db->select('header_user_id, empname,duser')->get_where('vw_web_user_login', array('reportingto' => $parent_id, 'active' => 1));

        $branch = array();
        if (!empty($query) && $query->num_rows() > 0) {
            $branch = $query->result();
            foreach ($branch as $key => $child) {
                $branch[$key]->reportingto = $this->userTree($child->header_user_id);
            }
            unset($key);
            unset($child);
        }

        return $branch;
    }

    function check() {
        print($this->session->userdata);
        global $loginuser;
        $loginuser = 'CheSal6';
        $reporting_users = $this->subquery($loginuser);
    }



    function query_union() {
        $q = 'select cast(temp_cust_sync_id as varchar(50)) as id, tempcustomermaster.temp_customername from tempcustomermaster  union all
select  cast(customermasterhdr.id as varchar(50)), customermasterhdr.tempcustname from customermasterhdr LIMIT 10';
        $result = $this->db->query($q);
//print_r($result->result_array());
        $options = $result->result_array();
        $options_arr;
        $options_arr[''] = '-Please Select Company-';
        foreach ($options as $option) {
            $options_arr[$option['id']] = $option['temp_customername'];
            // echo"id ".$option['id']."<br>";
            // echo"customername ".$option['temp_customername']."<br>";
        }
//	return $options_arr;
        print_r($options_arr);
//return $result;;
    }

    function viewleaddetails($lead_id) {
        //  echo"<pre>";print_r($_SERVER); echo"</pre>";echo"<pre>"; die;
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            $ref_url = $_SERVER['REDIRECT_QUERY_STRING'];
            $this->session->set_userdata('reffer_page', $ref_url);

            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }
        $login_user_id = $this->session->userdata['user_id'];
        $this->session->set_userdata('run_time_lead_id', $lead_id);
        if ($this->session->userdata['reportingto'] != "") {
            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details($lead_id);
        } else {

            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details_all($lead_id);
        }
        // echo $leaddata['leaddetails']['0']['lead_close_status']; die;

        //$leaddata['country'] = $this->Leads_model->get_countryname($leaddata['leaddetails']['0']['country']);
        //$leaddata['state'] = $this->Leads_model->get_statename($leaddata['leaddetails']['0']['state']);
        $customer_address = $this->Leads_model->getcustomerdetails_view($leaddata['leaddetails']['0']['company']);
        $substatus_name = $this->Leads_model->GetLeadSubStatusName($leaddata['leaddetails']['0']['ldsubstatus']);

        $leaddata['country']= $customer_address['contryname'];
        $leaddata['state'] = $customer_address['statename'];
        $leaddata['city'] = $customer_address['cityname'];
        $leaddata['address'] = $customer_address['address'];
        $leaddata['postalcode'] = $customer_address['postalcode'];
        $leaddata['mobile_no'] = $customer_address['mobile_no'];
        $leaddata['fax'] = $customer_address['fax'];
        $leaddata['contact_person']= $customer_address['contact_person'];
        $leaddata['contact_number'] = $customer_address['contact_number'];
        $leaddata['contact_mailid'] = $customer_address['contact_mailid'];
        
        $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
        $leaddata['substatus_name'] =$substatus_name;
        
        $i = 0;
        $datagroup = array();
        foreach ($leaddata['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);

        $leaddata['grpperm'] = $datagroup;

        $leaddata['ldstatuslog'] = $this->Leads_model->get_lead_status_details($lead_id, $login_user_id);
        //$leaddata['closedlead'] = $leaddata['leaddetails']['0']['lead_close_status'];
        $leaddata['closedlead'] = $leaddata['leaddetails']['0']['leadstatusid'];
        
        //SELECT 'lh_updated_date'::timestamp - 'lh_last_modified'::timestamp  as Days;
        $leaddata['leadproducts'] = $this->Leads_model->get_lead_product_details_view_detail($lead_id);
 //echo"<pre>";print_r($leaddata);echo"</pre>"; die;
        $this->load->view('leads/viewleaddetails', $leaddata);
    }

    function viewleaddetailsconverted($lead_id) {

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }
        $login_user_id = $this->session->userdata['user_id'];
        $this->session->set_userdata('run_time_lead_id', $lead_id);
        if ($this->session->userdata['reportingto'] != "") {
            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details($lead_id);
        } else {

            $leaddata['leaddetails'] = $this->Leads_model->get_lead_edit_details_all($lead_id);
        }
        // echo $leaddata['leaddetails']['0']['country'];
/*        $leaddata['country'] = $this->Leads_model->get_countryname($leaddata['leaddetails']['0']['country']);
        $leaddata['state'] = $this->Leads_model->get_statename($leaddata['leaddetails']['0']['state']);*/

        $customer_address = $this->Leads_model->getcustomerdetails_view($leaddata['leaddetails']['0']['company']);
        $substatus_name = $this->Leads_model->GetLeadSubStatusName($leaddata['leaddetails']['0']['ldsubstatus']);
          $leaddata['country']= $customer_address['contryname'];
        $leaddata['state'] = $customer_address['statename'];
        $leaddata['city'] = $customer_address['cityname'];
        $leaddata['address'] = $customer_address['address'];
        $leaddata['postalcode'] = $customer_address['postalcode'];
        $leaddata['mobile_no'] = $customer_address['mobile_no'];
        $leaddata['fax'] = $customer_address['fax'];
        $leaddata['contact_person']= $customer_address['contact_person'];
        $leaddata['contact_number'] = $customer_address['contact_number'];
        $leaddata['contact_mailid'] = $customer_address['contact_mailid'];
        // print_r($leaddata);

        $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
        $leaddata['substatus_name'] =$substatus_name;

        $i = 0;
        $datagroup = array();
        foreach ($leaddata['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);

        $leaddata['grpperm'] = $datagroup;

        $leaddata['ldstatuslog'] = $this->Leads_model->get_lead_status_details($lead_id, $login_user_id);
        //SELECT 'lh_updated_date'::timestamp - 'lh_last_modified'::timestamp  as Days;
        $leaddata['leadproducts'] = $this->Leads_model->get_lead_product_details_view_detail($lead_id);

        $this->load->view('leads/viewleaddetailsconverted', $leaddata);
    }

    function getallcompany() {
        $data = array();
        $data = $this->Leads_model->get_all_company_json();
        print_r($data);
    }

    function delete($ld_id) {
        $created_date = date('Y-m-d:H:i:s');

        $login_user_id = $this->session->userdata['user_id'];
        $login_username = $this->session->userdata['username'];
        $created_user_name = $login_username;

        if (isset($ld_id) && !empty($ld_id)) {
            echo" delete id passed is" . $ld_id;
            // code for inserting into delete log table start
            $insert_del_log = $this->Leads_model->insertlead_deletelog($created_date, $login_user_id, $login_username, $ld_id);

            // code for inserting into delete log table end
            if ($insert_del_log > 0) {
                $this->db->where('leadid', $ld_id);
                $this->db->delete('leaddetails');
                $this->session->set_flashdata('message', "Lead deleted Successfully");
                redirect('leads');
            } else {
                $this->session->set_flashdata('message', "Error in Deleting the Lead");
                redirect('leads');
            }
        }
        //	redirect('leads');
    }

    function substatus($parent_status_id) {
        //echo"parent_status_id ".$parent_status_id."<br>"; 
        //echo"leadid ".$this->session->userdata['run_time_lead_id'];
        $lead_id = $this->session->userdata['run_time_lead_id'];
        $leaddata['ldsubstatuslog'] = $this->Leads_model->get_lead_sub_status_details($lead_id, $parent_status_id);
        $this->load->view('leads/leadsubstatus', $leaddata);
    }

    function data() {
        $this->load->view('leads/data/contact');
    }

    function selectproducts_all() {
        //$sql='SELECT  min(id) as id, itemgroup as description,itemname FROM  view_tempitemmaster_pg_pname WHERE length(itemgroup) >1 GROUP BY itemgroup,itemname  ORDER BY itemgroup asc';  
        $sql='SELECT  min(id) as id, itemgroup as description FROM view_tempitemmaster_pg   GROUP BY itemgroup ORDER BY itemgroup asc';
       // $sql = 'SELECT  DISTINCT on (description) id, description FROM view_tempitemmaster ORDER BY description asc';
        //$sql='SELECT    itemgroup as id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        //		$sql='SELECT    id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        $activitydata['dataitemmaster'] = $this->Leads_model->get_all_products($sql);
        //	$viewdata = '['.$activitydata['dataitemmaster'].']'; 
        $viewdata = $activitydata['dataitemmaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }
    function list_prodgroups_names($proditemname) {
        $proditemname = urldecode($proditemname);
        $sql="SELECT  min(id) as id, itemgroup as description,itemname FROM  view_tempitemmaster_pg_pname WHERE itemgroup='".$proditemname."' AND length(itemgroup) >1   GROUP BY itemgroup,itemname  ORDER BY itemgroup asc";  
        //$sql='SELECT  min(id) as id, itemgroup as description FROM view_tempitemmaster_pg   GROUP BY itemgroup ORDER BY itemgroup asc';
       // $sql = 'SELECT  DISTINCT on (description) id, description FROM view_tempitemmaster ORDER BY description asc';
        //$sql='SELECT    itemgroup as id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        //      $sql='SELECT    id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        $activitydata['dataitemmaster'] = $this->Leads_model->get_all_products($sql);
        //  $viewdata = '['.$activitydata['dataitemmaster'].']'; 
        $viewdata = $activitydata['dataitemmaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    function list_allprodgroups_names() {
       
        $sql="SELECT  min(id) as id, itemgroup as description,itemname FROM  view_tempitemmaster_pg_pname WHERE length(itemgroup) >1  GROUP BY itemgroup,itemname  ORDER BY itemgroup asc";  
        //$sql='SELECT  min(id) as id, itemgroup as description FROM view_tempitemmaster_pg   GROUP BY itemgroup ORDER BY itemgroup asc';
       // $sql = 'SELECT  DISTINCT on (description) id, description FROM view_tempitemmaster ORDER BY description asc';
        //$sql='SELECT    itemgroup as id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        //      $sql='SELECT    id,  itemgroup as description FROM itemmaster  WHERE length(itemgroup) >1  GROUP BY itemgroup  ORDER BY itemgroup asc';
        $activitydata['dataitemmaster'] = $this->Leads_model->get_all_products($sql);
        //  $viewdata = '['.$activitydata['dataitemmaster'].']'; 
        $viewdata = $activitydata['dataitemmaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    

    

    function checkduplicate_product($prodid, $customerid) {
        $prodid = $_POST['prodid'];
        $prodgroup = $this->Leads_model->GetItemgroup($prodid);
        $customergroup =$this->Leads_model->GetCustmerGroup($customerid);
       

      //  print_r($prodgroup); die;
        $customerid = $_POST['customerid'];
        $user1 = $this->session->userdata['loginname'];
        //echo "prodid ".$prodid; 			echo "customerid ".$customerid; die;
         $leaddata['response'] = $this->Leads_model->check_prodnameduplicates_lead($prodid, $customerid);
         $leaddata1['response'] = $this->Leads_model->check_prodgroup_dup_saleorder($prodgroup['itemgroup'], $customerid,$customergroup['customergroup']);
        //	echo $activitydata['response'];
        if ($leaddata['response'] == 'true' && $leaddata1['response'] == 'true' ) {
            $response = array(
                'ok' => true,
                'msg' => "<font color=green>Yes..!You can use this product product</font>");
        } else {
            if($leaddata1['response']=='false')
            {
                $response = array(
               'ok' => false,
                'msg' => "<font color=red>This product group has been already billed for this customer</font>");
            }
            else
            {
               $response = array(
               'ok' => false,
                'msg' => "<font color=red>This product group has already a lead for this customer</font>");  
            }
            
        }

        echo json_encode($response);
    }

     function getstatus() {
        $branches = $this->Leads_model->get_status();
        //  $substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }

    function getleadcustomers()
    {
        $leadcustomers = $this->Leads_model->get_leadcustomers();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $leadcustomers;   
    }

    function getcustomers_all()
    {
        $allcustomers = $this->Leads_model->get_customers_all();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $allcustomers;   
    }
    function getleadproducts()
    {
        $leadproducts = $this->Leads_model->get_leadproducts();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $leadproducts;   
    }
    function reassignuser() 
    {
       // echo"<pre>"; print_r($_POST);echo"</pre>"; 
        $login_user_id = $this->session->userdata['user_id'];
        $login_username = $this->session->userdata['username'];
        $duser = $this->session->userdata['loginname'];
        $assignto_id = $_POST['assignto_id'];

        
        if ($_POST['save'] == 'true') 
        {
            //echo"<pre>"; print_r($_POST);echo"</pre>"; 
             $grid_data = array_slice($_POST, 3, null, true); 
        }
                $reassign_branch = $_POST['reassign_br'];
         
        // echo"<pre>grid_data ";print_r($grid_data); echo"</pre>";
         foreach ($grid_data as $key => $val) 
         {
          //  echo"key ".$key."<br>";
          // print_r($val); echo"<br>";
            $log_lead_status_name = $this->Leads_model->GetLeadStatusName($val['statusid']);
            $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($val['substatusid']);

            $assign_to_array = $this->Leads_model->GetAssigntoName($assignto_id);
            $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
            $duser = $assign_to_array['0']['duser'];
            $header_user_id = $assign_to_array['0']['header_user_id'];
            $user_branch = strtoupper($assign_to_array['0']['location_user']);
            
            /*[leadid] => 19890
            [branch] => COIMBATORE*/

            $lead_update[$key]['leadid'] = $val['leadid'];
            $lead_update[$key]['assignleadchk'] = $assignto_id;
            $lead_update[$key]['user_branch'] = $user_branch;
            $lead_update[$key]['collector'] = strtoupper($reassign_branch);
            $lead_update[$key]['last_updated_user'] = $login_user_id;
            $lead_update[$key]['last_modified'] = date('Y-m-d:H:i:s');

            $lead_prod_poten_branch[$key]['leadid'] = $val['leadid'];
            $lead_prod_poten_branch[$key]['user_branch'] = strtoupper($reassign_branch);
            $lead_prod_poten_branch[$key]['user1'] = $duser;
            $lead_prod_poten_branch[$key]['user_code'] =$header_user_id;
/*              'user1' => strtoupper($customer_poten[0]['user1']),
            'user_code' => $customer_poten[0]['user_code']*/


            
            $lead_log_details = array(
                'lh_lead_id' => $val['leadid'],
                'lh_user_id' => $login_user_id,
                'lh_lead_curr_status' => $log_lead_status_name,
                'lh_lead_curr_statusid' => $val['statusid'],
                'lh_updated_date' => date('Y-m-d:H:i:s'),
                'lh_last_updated_user' => $login_user_id,
                'action_type' => 're-assign',
                'modified_user_name' => $login_username,
                'assignto_user_id ' => $assignto_id,
                'assignto_user_name' => $lead_assign_name
            );

 @$logid = $this->Leads_model->create_leadlog($lead_log_details);

            $lead_sublog_details = array(
                'lhsub_lh_id' => @$logid,
                'lhsub_lh_lead_id' => $val['leadid'],
                'lhsub_lh_user_id' => $login_user_id,
                'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                'lhsub_lh_lead_curr_statusid' => $val['statusid'],
                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                'lhsub_lh_lead_curr_sub_statusid' =>$val['substatusid'],
                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                'lhsub_lh_last_updated_user' => $login_user_id,
                'lhsub_action_type' => 're-assign',
                'lhsub_modified_user_name' => $login_username,
                'lhsub_assignto_user_id ' => $assignto_id,
                'lhsub_assignto_user_name' => $lead_assign_name
                );

             $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);

            

         }
/*         echo "<pre> lead_update "; print_r($lead_update); echo "</pre>";
         echo "<pre> lead_log  "; print_r($lead_log_details); echo "</pre>";
         echo "<pre> lead_sublog"; print_r($lead_sublog_details); echo "</pre>";
         echo "<pre> lead_sublog"; print_r($lead_prod_poten_branch); echo "</pre>";*/
      
          $lead_pord_poten_id = $this->Leads_model->potential_updated_table_collector($lead_prod_poten_branch);
         $id = $this->Leads_model->update_lead_reassign($lead_update);


       
       

         $message = "true";
         header('Content-Type: application/x-json; charset=utf-8');
        echo $message;
    }

    
   function getbranches() {
        $branches = $this->Leads_model->get_branches();
        //  $substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }

     public function getautocity()
        {
            
            $type = $_POST['type'];
            $name = $_POST['name_startsWith'];
            $sql = "SELECT  cityname as id,cityname FROM city WHERE UPPER(cityname) LIKE '%".strtoupper($name)."%'";
            $result = pg_query($sql);
            $data = array();
            while ($row = pg_fetch_array($result)) {
                $name = $row['id'].'|'.$row['cityname'];
                array_push($data, $name);
            }
            echo json_encode($data);
        }

        function test1()
        {
            echo $id = urldecode($this->uri->segment(3)); 
              $customer_details = $this->Leads_model->GetCustomerdetails($id);
                     echo"<pre>";print_r($customer_details); echo"</pre>";
              echo"<br>getcustomerdetails_view<br>";
              $customer_details = $this->Leads_model->getcustomerdetails_view($id);
              echo"<pre>";print_r($customer_details); echo"</pre>";


              
        }
        function keepalive()
        {
         print "OK";
        }

        function test2()
        {
            
              $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
              echo"count of array is ".count($product_sale_type)."<br>";
              echo"<pre>";print_r($product_sale_type); echo"</pre>";
              for ($k = 0; $k < count($product_sale_type); $k++) 
                    {
                                echo $product_sale_type[$k]['n_value']."<br>";
                    }
        }

        function saleids()
        {
            
              $product_sale_type = $this->Leads_model->get_leadproduct_saletypeids();
              echo"count of array is ".count($product_sale_type)."<br>";
              echo"<pre>";print_r($product_sale_type); echo"</pre>";
              for ($k = 0; $k < count($product_sale_type); $k++) 
                    {
                                echo $product_sale_type[$k]."<br>";
                    }
        }

        function getpten()
        {
               echo $id = urldecode($this->uri->segment(3));
               echo $flag = urldecode($this->uri->segment(4));
              $product_sale_type = $this->Leads_model->get_leadpotentials($id,$flag);
              echo"count of array is ".count($product_sale_type)."<br>";
              echo"<pre>";print_r($product_sale_type); echo"</pre>";
              echo $product_sale_type['potential']."<br>";
              echo $product_sale_type['lead_sale_type']."<br>";
              
        }

        function getdchdr()
        {
               echo $date = urldecode($this->uri->segment(3));
               echo $user1 =  $this->session->userdata['loginname'];
              $product_sale_type = $this->Leads_model->check_dailyhdr_duplicates($date,$user1);
             // echo"count of array is ".count($product_sale_type)."<br>";
              echo"<pre>";print_r($product_sale_type); echo"</pre>";
              echo $product_sale_type['0']['exename']."<br>";
              echo $product_sale_type['0']['id']."<br>";
              echo $product_sale_type['0']['noofrows']."<br>";

              
              
        }
        function checkuser()
        {
           
            echo $user1 = strtoupper($this->uri->segment(3));
            //$user1='HYDSAL19';
            //$user1='CheSal71';      
            $isExecutive = $this->Leads_model->check_executive_user($user1);
              //echo"<pre>";print_r($isExecutive); echo"</pre>";
              echo "noof rows ".$isExecutive['0']['noofrows'];

        }

        /* market cricle functions */
        function getmcbranches() 
        {
            $branches = $this->Leads_model->get_mcbranches();
            //  $substatus = $this->Leads_model->get_assigned_tobranch();
            header('Content-Type: application/x-json; charset=utf-8');
            echo $branches;
        }
         function getreassignmcbranches($brach_sel) 
        {
            $this->Leads_model->brach_sel = urldecode($brach_sel);
            $branches = $this->Leads_model->get_reassign_mcbranches($brach_sel);
            //  $substatus = $this->Leads_model->get_assigned_tobranch();
            header('Content-Type: application/x-json; charset=utf-8');
            echo $branches;
        }
        function getmcselectedbranch($brach_sel) 
        {
            $this->Leads_model->brach_sel = urldecode($brach_sel);
            $mcbranchlist = $this->Leads_model->get_mcselected_branch();
            header('Content-Type: application/x-json; charset=utf-8');
            echo $mcbranchlist;
        }

        function getmarketcircleloginuser()
        {
            $marketcirlist = $this->Leads_model->get_marketcircle_loginuser();
            header('Content-Type: application/x-json; charset=utf-8');
            echo $marketcirlist;
        }

        function getusersformarketcircle($mc_sel)
        {

            $this->Leads_model->mc_sel = urldecode($mc_sel);
            $marketcirlist = $this->Leads_model->get_users_formarketcircle($mc_sel);
            header('Content-Type: application/x-json; charset=utf-8');
            echo $marketcirlist;

        }

        

}

?>
