<?php

class Dashboard extends CI_Controller {

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
    public $sel_user_id;
    public $branch;

    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('Leads_model');
        $this->load->model('dashboard_model');
        $this->load->library('subquery');
        $this->load->library('session');
        $this->load->helper('html');
    }

    /* 	
      public function index()
      {
      $leaddata = array();
      //$leaddata = $this->Leads_model->get_lead_details();
      $leaddata['leaddetails'] = $this->Leads_model->get_lead_details();
      //echo"<pre>";print_r($leaddata);echo"</pre>";
      $this->load->view('leads/viewleads',$leaddata);
      }
     */
      public function index() {
       // $from_date='2015-01-01';
        $from_date='2015-04-01';
        $to_date= date("Y-m-d");  
        $branch="All";
        $jc_from="1";
        $jc_to="13";
        
        $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
       
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
            $leaddatareturn = array();
            $leaddatareturn_qnty = array();
            $leaddatareturn =$this->dashboard_model->get_month_wise_lead_count($branch,$jc_from,$jc_to,$account_yr);
            $leaddatareturn1 =$this->dashboard_model->get_month_wise_lead_count_for_chart($branch,$jc_from,$jc_to,$account_yr);
            
             $leaddata['data1'] = $leaddatareturn['arr'];
             $leaddata['data'] = $leaddatareturn['jasonarr'];
             $leaddata['datacum'] = $leaddatareturn['jasonarrcum'];
            
          /*  $leaddata['data'] = $leaddatareturn['arr'];
            
*/
            $leaddata['datact'] =$leaddatareturn1['arrct'];
                    
            $leaddata['test'] =$leaddatareturn['test'];
            $leaddata['status_name'] =$leaddatareturn['status_name'];


            $leaddatareturn_qnty =$this->dashboard_model->get_month_wise_lead_qnty($branch,$jc_from,$jc_to,$account_yr);
/*            $leaddata['data_qnty'] = $leaddatareturn_qnty['arr_qnty'];
            $leaddata['datacum_qnty'] = $leaddatareturn_qnty['arr_qnty'];*/
            $leaddata['data_qnty'] = $leaddatareturn_qnty['jasonarr_qnty'];
            $leaddata['datacum_qnty'] = $leaddatareturn_qnty['jasonarrcum_qnty'];
            $leaddata['datact_qnty'] =$leaddatareturn_qnty['arrct_qnty'];
          
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2015-04-01';
            $leaddata['account_yr'] = $account_yr;
            $leaddata['to_date'] = $to_date;
            $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_to'] = $jc_to;
            $leaddata['jc_from'] = $jc_from;
            $leaddata['account_yr'] = $account_yr;
            
            $leaddata['ownbranch'] = "0";
            $leaddata['maxVal'] = $leaddatareturn['maxVal'];
            $leaddata['maxVal_qnty'] = $leaddatareturn_qnty['maxVal_qnty'];

            

            // echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/viewmonthleadcount', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }
    public function getleadmc() {

        $branch = urldecode($this->uri->segment(3));
        $jc_from = $this->uri->segment(4);
        $jc_to = $this->uri->segment(5);
        $account_yr = $this->uri->segment(6);
        //echo"branch ".$branch."<br>"; echo"from_date ".$from_date."<br>"; echo"to_date ".$to_date."<br>";
         /*[segments] => Array
        (
            [1] => dashboard
            [2] => index
            [3] => AHMEDABAD
            [4] => 2015-03-16
            [5] => 2015-03-16
        )*/
       /* $from_date = str_replace('days', '', $this->uri->segment(4));
        $to_date = str_replace('days', '', $this->uri->segment(5));*/
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
            $leaddatareturn = array();
            $leaddatareturn_qnty = array();
            $leaddatareturn =$this->dashboard_model->get_month_wise_lead_count($branch,$jc_from,$jc_to,$account_yr);
            $leaddatareturn1 =$this->dashboard_model->get_month_wise_lead_count_for_chart($branch,$jc_from,$jc_to,$account_yr);
            //$leaddata['data'] = $leaddatareturn['arr'];
            $leaddata['data'] = $leaddatareturn['jasonarr'];
            $leaddata['datacum'] = $leaddatareturn['jasonarrcum'];
            $leaddata['datact'] =$leaddatareturn1['arrct'];
            $leaddata['test'] =$leaddatareturn['test'];

            $leaddatareturn_qnty =$this->dashboard_model->get_month_wise_lead_qnty($branch,$jc_from,$jc_to,$account_yr);
            $leaddata['data_qnty'] = $leaddatareturn_qnty['jasonarr_qnty'];
            $leaddata['datacum_qnty'] = $leaddatareturn_qnty['jasonarrcum_qnty'];
            
            $leaddata['datact_qnty'] =$leaddatareturn_qnty['arrct_qnty'];

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = $branch;
            $from_date = $this->dashboard_model->getgcfromdate($jc_from,$account_yr);
            $to_date = $this->dashboard_model->getgctodate($jc_to,$account_yr);
            $leaddata['from_date'] = $from_date;
            $leaddata['to_date'] = $to_date;
            $leaddata['jc_to'] = $jc_to;
            $leaddata['jc_from'] = $jc_from;
            $leaddata['account_yr'] = $account_yr;
            $leaddata['ownbranch'] = "0";
            $leaddata['maxVal'] = $leaddatareturn['maxVal'];
            $leaddata['maxVal_qnty'] = $leaddatareturn_qnty['maxVal_qnty'];

           //  echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/viewmonthleadcount', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }


    public function ownbranch()
    {
        $branch = "";
        $from_date='2014-04-01';
        $to_date= date("Y-m-d");  
        $account_yr_from="2015-2016";
        $jc_from=1;


                 

      //  echo"branch ".$branch."<br>"; echo"from_date ".$from_date."<br>"; echo"to_date ".$to_date."<br>";
         /*[segments] => Array
        (
            [1] => dashboard
            [2] => index
            [3] => AHMEDABAD
            [4] => 2015-03-16
            [5] => 2015-03-16
        )*/
       /* $from_date = str_replace('days', '', $this->uri->segment(4));
        $to_date = str_replace('days', '', $this->uri->segment(5));*/
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
            $leaddatareturn = array();

            $from_date = $this->dashboard_model->getgcfromdate($jc_from,$account_yr_from);

            $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
            
            $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $to_date = $this->dashboard_model->getgctodate($jc_to,$account_yr);
            
           
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = $branch;
            $leaddata['from_date'] = $from_date;
            $leaddata['to_date'] = $to_date;
            $leaddata['ownbranch'] = "1";
            $leaddata['account_yr'] = $account_yr;
            $leaddata['jc_to'] = $jc_to;
            


            
           //   echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/viewownbranchleadcount', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }
    public function ownbranchsrch()
    {
        $branch = urldecode($this->uri->segment(3));

        $jc_from = $this->uri->segment(4);
        $jc_to = $this->uri->segment(5);
        $account_yr = $this->uri->segment(6);
      //  echo"branch ".$branch."<br>"; echo"from_date ".$from_date."<br>"; echo"to_date ".$to_date."<br>";
         /*[segments] => Array
        (
            [1] => dashboard
            [2] => index
            [3] => AHMEDABAD
            [4] => 2015-03-16
            [5] => 2015-03-16
        )*/
       /* $from_date = str_replace('days', '', $this->uri->segment(4));
        $to_date = str_replace('days', '', $this->uri->segment(5));*/
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
            $leaddatareturn = array();
            $leaddatareturn_qnty = array();

            //$leaddatareturn =$this->dashboard_model->get_month_wise_ownbranch_count($branch,$from_date,$to_date);
            $leaddatareturn =$this->dashboard_model->get_month_wise_ownbranch_count($jc_from,$jc_to,$account_yr,$branch);
            $leaddatareturn1 =$this->dashboard_model->get_month_wise_ownbranch_count_chart($branch,$jc_from,$jc_to,$account_yr);

            $leaddata['data'] = $leaddatareturn['jasonarr'];
            $leaddata['datacum'] = $leaddatareturn['jasonarrcum'];

           // $leaddata['data'] = $leaddatareturn['arr'];
            $leaddata['datact'] =$leaddatareturn1['arrct'];

            $leaddatareturn_qnty =$this->dashboard_model->get_month_wise_ownbranch_qnty($jc_from,$jc_to,$account_yr,$branch);
            $leaddata['data_qnty'] = $leaddatareturn_qnty['jasonarr_qnty'];
            $leaddata['datacum_qnty'] = $leaddatareturn_qnty['jasonarrcum_qnty'];

            //$leaddata['data_qnty'] = $leaddatareturn_qnty['arr_qnty'];
            $leaddata['datact_qnty'] =$leaddatareturn_qnty['arrct_qnty'];

          

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = $branch;
            $from_date = $this->dashboard_model->getgcfromdate($jc_from,$account_yr);
            $to_date = $this->dashboard_model->getgctodate($jc_to,$account_yr);
            $leaddata['from_date'] = $from_date;
            $leaddata['to_date'] = $to_date;
            $leaddata['jc_to'] = $jc_to;
            $leaddata['jc_from'] = $jc_from;
            $leaddata['account_yr'] = $account_yr;
            $leaddata['ownbranch'] = "2";
            $leaddata['maxVal'] = $leaddatareturn['maxVal'];
            $leaddata['maxVal_qnty'] = $leaddatareturn_qnty['maxVal_qnty'];
           //   echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/viewmonthleadcount', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }



    public function index_old() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart();
                //$leaddata['leadcount'] = $this->session->userdata['all_leads_count'];
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart();
                //$leaddata['leadcount'] = $this->session->userdata['user_leads_count'];
                 $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();

            }
           // print_r($leaddata); die;
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


//					$this->load->view('leads/viewleadsnew',$leaddata);	
            $this->load->view('dashboard/viewleadslist', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function showleads() {
        if (!$this->admin_auth->is_admin()) {
            //        echo"1".$this->uri->segment(3);
            //       echo"2".$this->uri->segment(4); 
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
        }
        $status_id = $this->uri->segment(3);
        //str_replace(find,replace,string
        $no_of_days = str_replace('days', '', $this->uri->segment(4));
        //      echo"no of days ".$no_of_days;
        //     echo"status_id ".$status_id;
        $data = array();
        $data['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];




        $i = 0;
        $datagroup = array();
        foreach ($data['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);
        $data['grpperm'] = $arr;


        $data['data'] = $this->dashboard_model->get_leaddetails_for_grid($status_id, $no_of_days);
        $this->load->view('dashboard/showleads', $data);
    }

    function showsubleads() {
        if (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }
        //        echo"1".$this->uri->segment(3);
        //       echo"2".$this->uri->segment(4); 
        $sub_status_id = $this->uri->segment(3);
        //str_replace(find,replace,string
        $no_of_days = str_replace('days', '', $this->uri->segment(4));
        //      echo"no of days ".$no_of_days;
        //     echo"status_id ".$status_id;

        $data = array();

        $data['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
        $i = 0;
        $datagroup = array();
        foreach ($data['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);
        $data['grpperm'] = $arr;
        $data['data'] = $this->dashboard_model->get_subleaddetails_for_grid($sub_status_id, $no_of_days);
        $this->load->view('dashboard/showleads', $data);
    }

    function showsubleadsfilter() {

        if (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }
        $sub_status_id = $this->uri->segment(3);
        $no_of_days = str_replace('days', '', $this->uri->segment(4));

        $branch = urldecode(str_replace('days', '', $this->uri->segment(5)));
        $user_id = str_replace('days', '', $this->uri->segment(6));
        $from_date = str_replace('days', '', $this->uri->segment(7));
        $to_date = str_replace('days', '', $this->uri->segment(8));

        $data = array();
        $data['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
        //	echo "check ".$branch; 
        if (($branch == "Select%20Branch") || ($branch == "Select Branch")) {
            $branch = "";
        }
        //	echo "test ".$branch; die;
        $i = 0;
        $datagroup = array();
        foreach ($data['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);
        $data['grpperm'] = $arr;
        $data['data'] = $this->dashboard_model->get_subleaddetails_filter_for_grid($sub_status_id, $no_of_days, $branch, $user_id, $from_date, $to_date);
        $this->load->view('dashboard/showleads', $data);
    }

    function showleadsfilter() {

        if (!$this->admin_auth->is_admin()) {
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
        }
        $sub_status_id = $this->uri->segment(3);
        $no_of_days = str_replace('days', '', $this->uri->segment(4));

        $branch = urldecode(str_replace('days', '', $this->uri->segment(5)));
        $user_id = str_replace('days', '', $this->uri->segment(6));
        $from_date = str_replace('days', '', $this->uri->segment(7));
        $to_date = str_replace('days', '', $this->uri->segment(8));
        $data = array();
        $data['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

        //	echo "check ".$branch; 
        if (($branch == "Select%20Branch") || ($branch == "Select Branch")) {
            $branch = "";
        }
        //	echo "test ".$branch; die;
        if ($user_id != "") {
            $data['data'] = $this->dashboard_model->get_subleaddetailsfilter_for_grid($sub_status_id, $no_of_days, $branch, $user_id, $from_date, $to_date);
        } else {
            $data['data'] = $this->dashboard_model->get_subleaddetailsfilter_nouser_for_grid($sub_status_id, $no_of_days, $branch, $from_date, $to_date);
        }



        $i = 0;
        $datagroup = array();
        foreach ($data['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);
        $data['grpperm'] = $arr;
        $this->load->view('dashboard/showleads', $data);
    }

    function executivepipeline() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $branch = urldecode($this->uri->segment(3));
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if (($branch == "") or ( $user_id = "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = 'SelectBranch';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = 'Select Branch';
            }
            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            // echo"user id ". $user_id."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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
            $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart();
            $this->load->view('dashboard/viewleadexec', $leaddata);
        }
    }

    function subexecutivepipeline() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $status_id = $this->uri->segment(3);
            $user_id = $this->uri->segment(4);
            //	echo"branch ".$branch;  echo"user_id ".$user_id; die;
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard($status_id);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart($status_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_sublead_user_branch_count($status_id);
            } else {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard($status_id);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart($status_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_sublead_user_branch_count($status_id);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if ((@$branch == "") && ($user_id = "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = 'SelectBranch';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = 'Select Branch';
            }
            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            // echo"user id ". $user_id."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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
            //$leaddata['datact']=$this->dashboard_model->get_leaddetails_aging_chart();
            $this->load->view('dashboard/showleadssub', $leaddata);
        }
    }

    function getbranches() {
        $branches = $this->dashboard_model->get_branches();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }
       function getbranchesAll() {
        $branches = $this->dashboard_model->get_branches_all();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }


    


    function getjchdr() {
        $jc_headers = $this->dashboard_model->get_jchdr();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;
    }
      function getjchdrforweek($fin_year) {
        $jc_headers = $this->dashboard_model->get_jchdr_forweek($fin_year);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;
    }


      function getjcperiodfromdate($jc_code,$fin_year) {
        $this->dashboard_model->jc_code = urldecode($jc_code);
        $this->dashboard_model->fin_year = urldecode($fin_year);
        $get_jcperiods = $this->dashboard_model->get_jcperiodfromdate();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $get_jcperiods;
    }
        function getjcperiodtodate($jc_code,$fin_year) {
        $this->dashboard_model->jc_code = urldecode($jc_code);
        $this->dashboard_model->fin_year = urldecode($fin_year);
        $get_jcperiods = $this->dashboard_model->get_jcperiodtodate();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $get_jcperiods;
    }

    function getfinanceyear() {
        $jc_finyear = $this->dashboard_model->get_financeyear();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_finyear;
    }


    


    function getreassigntouser()
    {
      //  $this->dashboard_model->brach_sel = urldecode($brach_sel);

        $userlist = $this->dashboard_model->get_reassigntouser();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;

    }
    function getdistinctbranch($brach_sel)
    {
          $this->dashboard_model->brach_sel = urldecode($brach_sel);
        $to_branches = $this->dashboard_model->get_distinct_branch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $to_branches;
    }

    function getassignedtobranch($brach_sel) {
        $this->dashboard_model->brach_sel = urldecode($brach_sel);
        $userlist = $this->dashboard_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }

    function getusersforloginuser() {

        $userlist = $this->dashboard_model->get_usersfor_loginuser();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }

    function getdatawithfilter() {
        $branch = urldecode($this->uri->segment(3));
        $sel_user_id = $this->uri->segment(4);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withfilter($branch, $sel_user_id);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withfilter($branch, $sel_user_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withfilter($branch, $sel_user_id);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withfilter($branch, $sel_user_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") && ($sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/viewleadexec', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getsubdatawithfilter() {
        $status_id = $this->uri->segment(3);
        $branch = $this->uri->segment(4);
        $sel_user_id = $this->uri->segment(5);

        $from_date = $this->uri->segment(6);
        $to_date = $this->uri->segment(7);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withfilter($status_id, $branch, $sel_user_id);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withfilter($status_id, $branch, $sel_user_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withfilter($status_id, $branch, $sel_user_id);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withfilter($status_id, $branch, $sel_user_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") or ( $sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/showleadssub', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getdatawithdate_filter() {
        $branch = urldecode($this->uri->segment(3));
        $sel_user_id = $this->uri->segment(4);
        $from_date = $this->uri->segment(5);
        $to_date = $this->uri->segment(6);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withdatefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withdatefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_datefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withdatefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withdatefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_datefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") or ( $sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/viewleadexec', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getdatawithbranchfilter() {
        $branch = urldecode($this->uri->segment(3));
        $from_date = $this->uri->segment(4);
        $to_date = $this->uri->segment(5);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_dashboard_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_chart_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/viewleadexec', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getadditional_withbranchfilter() {
        $branch = urldecode($this->uri->segment(3));
        $from_date = $this->uri->segment(4);
        $to_date = $this->uri->segment(5);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_chart_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_chart_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/additional', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getsubdatawithdatefilter() {
        $status_id = $this->uri->segment(3);
        $branch = urldecode($this->uri->segment(4));
        $sel_user_id = $this->uri->segment(5);
        $from_date = $this->uri->segment(6);
        $to_date = $this->uri->segment(7);

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withdatefilter($status_id, $branch, $sel_user_id, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withdatefilter($status_id, $branch, $sel_user_id, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_datefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withdatefilter($status_id, $branch, $sel_user_id, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withdatefilter($status_id, $branch, $sel_user_id, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_datefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") or ( $sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/showleadssub', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getsubdatawithbranchdatefilter() {
        $status_id = $this->uri->segment(3);
        $branch = urldecode($this->uri->segment(4));
        $from_date = $this->uri->segment(5);
        $to_date = $this->uri->segment(6);

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withbranchdatefilter($status_id, $branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withbranchdatefilter($status_id, $branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_subleaddetails_aging_dashboard_withbranchdatefilter($status_id, $branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_subleaddetails_aging_chart_withbranchdatefilter($status_id, $branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/showleadssub', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function daynoprogress() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $branch = urldecode($this->uri->segment(3));
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_daynoprogress_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_daynoprogress_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_daynoprogress_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_daynoprogress_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if (($branch == "") or ( $user_id = "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = 'SelectBranch';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = 'Select Branch';
            }
            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            // echo"user id ". $user_id."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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
            $this->load->view('dashboard/daynoprogress', $leaddata);
        }
    }

    function daynoprogesswithfilter() {
        $branch = urldecode($this->uri->segment(3));
        $sel_user_id = $this->uri->segment(4);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_daynop_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_daynop_withfilter_chart($branch);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_daynop_branch_count($branch);
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_daynop_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_daynop_withfilter_chart($branch);

                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_daynop_branch_count($branch);
                $leaddata['branch'] = $branch;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") && ($sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/daynoprogress', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function additional() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $branch = urldecode($this->uri->segment(3));
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_additional_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_additional_aging_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_additional_aging_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_additional_aging_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if (($branch == "") or ( $user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = 'SelectBranch';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = 'Select Branch';
            }
            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            // echo"user id ". $user_id."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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
            //	$leaddata['datact']=$this->dashboard_model->get_leaddetails_additional_aging_chart();
            $this->load->view('dashboard/additional', $leaddata);
        }
    }

    function additionalwithfilter() {
        $branch = urldecode($this->uri->segment(3));
        $sel_user_id = $this->uri->segment(4);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_chart($branch);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_chart($branch);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
                $leaddata['branch'] = $branch;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") && ($sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/additional', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getadditional_withdate_filter() {

        $from_date = $this->uri->segment(3);
        $to_date = $this->uri->segment(4);
        @$branch == "Select Branch";

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withdatefilter($from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_withdatefilter_chart($from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_date_count($from_date, $to_date);

                $leaddata['branch'] = @$branch;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_aging_additional_withdatefilter($from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_aging_additional_withdatefilter_chart($from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_date_count($from_date, $to_date);

                $leaddata['branch'] = @$branch;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (@$branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = @$branch;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = @$branch;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/additional', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function generadtedleads() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
            $branch = urldecode($this->uri->segment(3));
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generatedleads_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generatedleads_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generatedleads_dashboard();
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generatedleads_chart();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if (($branch == "") or ( $user_id = "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                //	$leaddata['branch'] ='SelectBranch';
                $leaddata['branch'] = 'AllBranches';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                //$leaddata['branch'] ='Select Branch';
                $leaddata['branch'] = 'AllBranches';
            }
            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            // echo"user id ". $user_id."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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
            $this->load->view('dashboard/generatedleads', $leaddata);
        }
    }

    function generadtedleadswithfilter() {
        $branch = urldecode($this->uri->segment(3));
        $sel_user_id = $this->uri->segment(4);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generatedleads_dashboard_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generatedleads_chart_withfilter($branch);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generatedleads_dashboard_withfilter($branch);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generatedleads_chart_withfilter($branch);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_branch_count($branch);
                $leaddata['branch'] = $branch;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if (($branch == "") && ($sel_user_id == "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/generatedleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function generadtedleadswithdate_filter() {
        $branch = urldecode($this->uri->segment(3));
        $from_date = $this->uri->segment(4);
        $to_date = $this->uri->segment(5);


        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generated_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generated_chart_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generated_withbranchdatefilter($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generated_chart_withbranchdatefilter($branch, $from_date, $to_date);
                @$leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/generatedleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function genleadsallbranches() {
        $branch = urldecode($this->uri->segment(3));
        $from_date = $this->uri->segment(4);
        $to_date = $this->uri->segment(5);
        /* 				echo" branch ".$branch."<br>";
          echo" from_date ".$from_date."<br>";
          echo" to_date ".$to_date."<br>";
          branch AllBranches
          from_date 2014-05-31
          to_date 2014-05-31 */
        //die;

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generated_allbranches($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generated_chart_allbranches($branch, $from_date, $to_date);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_allbranches($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->dashboard_model->get_leaddetails_generated_allbranches($branch, $from_date, $to_date);
                $leaddata['datact'] = $this->dashboard_model->get_leaddetails_generated_chart_allbranches($branch, $from_date, $to_date);
                @$leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_allbranches($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            //if $leaddata['maxvalue'] = $leaddata['leadcount'];
            //$leaddata['maxvalue'] = $leaddata['lead_ub_count'];
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            }
            // echo"user id ". $leaddata['sel_user_id']."<br>"; 	echo"branch  ". $leaddata['branch']."<br>"; 			die;
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


            //					$this->load->view('leads/viewleadsnew',$leaddata);	
            //		print_r($leaddata); die;
            $this->load->view('dashboard/generatedleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    /* Persu Codings start */

    function showleadsdata($branch, $selectedfield) {
        if (!$this->admin_auth->is_admin()) {
            //        echo"1".$this->uri->segment(3);
            //       echo"2".$this->uri->segment(4); 
            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();
        }
        $status_id = $this->uri->segment(3);
        //str_replace(find,replace,string
        $no_of_days = str_replace('days', '', $this->uri->segment(4));
        //      echo"no of days ".$no_of_days;
        //     echo"status_id ".$status_id;
        $data = array();
        $data['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];




        $i = 0;
        $datagroup = array();
        foreach ($data['permission'] as $key => $val) {
            $row = array();

            $row["groupid"] = $key;
            $row["groupname"] = $val;
            $datagroup[$i] = $row;
            $i++;
        }

        $arr = json_encode($datagroup);
        $data['grpperm'] = $arr;


        $data['data'] = $this->dashboard_model->get_leaddetails_for_branch_grid($status_id, $no_of_days);
        $this->load->view('dashboard/showbranchleads', $data);
    }

    /* Persu codings end */


     public function overallleadqnty() {
       // $from_date='2015-01-01';
        $from_date='2015-04-01';
        $to_date= date("Y-m-d");  
        $branch="All";
        $jc_from="1";
        $jc_to="13";
        $jc_week="1";
        
        $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
       
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2015-04-01';
            $leaddata['account_yr'] = $account_yr;
            $leaddata['to_date'] = $to_date;
            $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_to'] = $jc_to;
            $leaddata['jc_week'] = $jc_week;
            $leaddata['jc_from'] = $jc_from;
            $leaddata['account_yr'] = $account_yr;
            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_lead_quantity_dashboard();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            } else {
                $leaddata['data'] = $this->dashboard_model->get_lead_quantity_dashboard();
                     $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            }
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

    
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

            }
          


            

            // echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/overallleadqnty', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }

    function lead_quantity_withfilter()
    {
         $branch = $this->uri->segment(3);
         $account_yr = $this->uri->segment(4);
         $jc_code = $this->uri->segment(5);
         $jc_week = $this->uri->segment(6);

        // $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
       
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = $branch;
            $leaddata['from_date'] = '2015-04-01';
            $leaddata['account_yr'] = $account_yr;
            $leaddata['to_date'] = @$to_date;
          //  $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_to'] = $jc_code;
            $leaddata['jc_week'] = $jc_week;
            $leaddata['jc_from'] = $jc_code;
            $leaddata['account_yr'] = $account_yr;
            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_lead_quantity_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            } else {
                $leaddata['data'] = $this->dashboard_model->get_lead_quantity_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            }
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

    
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

            }
          


            

            // echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/overallleadqnty', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }

    public function overallleadqnty1() {
       // $from_date='2015-01-01';
        $from_date='2015-04-01';
        $to_date= date("Y-m-d");  
        $branch="All";
        $jc_from="1";
        $jc_to="13";
        $jc_week="1";
        
        $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
       
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2015-04-01';
            $leaddata['account_yr'] = $account_yr;
            $leaddata['to_date'] = $to_date;
            $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_to'] = $jc_to;
            $leaddata['jc_week'] = $jc_week;
            $leaddata['jc_from'] = $jc_from;
            $leaddata['account_yr'] = $account_yr;
            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_lead_countfor_dashboard();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            } else {
                $leaddata['data'] = $this->dashboard_model->get_lead_countfor_dashboard();
                     $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            }
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

    
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

            }
          


            

            // echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/overallleadqnty1', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }

    function lead_quantity_withfilter1()
    {
         $branch = $this->uri->segment(3);
         $account_yr = $this->uri->segment(4);
         $jc_code = $this->uri->segment(5);
         $jc_week = $this->uri->segment(6);

        // $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); 
       
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {
           // echo"<pre>";print_r($this->session->userdata);echo"</pre>";

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['branch'] = $branch;
            $leaddata['from_date'] = '2015-04-01';
            $leaddata['account_yr'] = $account_yr;
            $leaddata['to_date'] = @$to_date;
          //  $jc_to = $this->Leads_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_to'] = $jc_code;
            $leaddata['jc_week'] = $jc_week;
            $leaddata['jc_from'] = $jc_code;
            $leaddata['account_yr'] = $account_yr;
            if ($this->session->userdata['reportingto'] == "") {
                $leaddata['data'] = $this->dashboard_model->get_lead_countfor_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            } else {
                $leaddata['data'] = $this->dashboard_model->get_lead_countfor_dashboard_withbranch($branch,$account_yr,$jc_code,$jc_week);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, @$user_id);
             
            }
            if ($branch == "") {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['branch'] = $branch;

    
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['branch'] = $branch;

            }
          


            

            // echo"<pre>";print_r($leaddata);echo"</pre>"; die;
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

           $this->load->view('dashboard/overallleadqnty1', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }

    function getjcweek_hdr($fin_year,$jc_code) {
        $jc_headers = $this->dashboard_model->get_jcweek_hdr($fin_year,$jc_code);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;
    }

    function getjcweekdates($yr,$jc,$weekid)
    {
        $this->dashboard_model->fin_year = urldecode($yr);
        $this->dashboard_model->jc_code = urldecode($jc);
        $this->dashboard_model->jc_week = urldecode($weekid);
        
        $get_jcperiods = $this->dashboard_model->get_jcweek_periods();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $get_jcperiods;

    }

   


}

?>
