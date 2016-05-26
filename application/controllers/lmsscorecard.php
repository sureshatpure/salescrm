<?php

class Lmsscorecard extends CI_Controller {

    public $data = array();
    public $post = array();
    public $proddata = array();
    public $leaddata = array();
    public $loginuser;
    public $userid;
    public $loginname;
    public $reportingto;
    public $login_user_id;
    public $datagroup = array();
    public $sel_user_id;
    public $branch;

    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->model('Leads_model');
        $this->load->model('lmsscorecard_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('html');
    }

 
      public function index() {
       // $from_date='2015-01-01';
        $from_date='2015-04-01';
        $to_date= date("Y-m-d");  
        $today_date= date("Y-m-d");  
        $branch="All";
        //$jc_period="1";
        $jc_implement_date='2015-04-01';

        
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
         
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $leaddata['zone'] = 'All';
            $leaddata['collector'] = 'All';
            $leaddata['marketcircle'] = 'All';
            $leaddata['itemgroup'] = 'All';


            
            $leaddata['fromdate'] = $from_date;
            $leaddata['todate'] = $to_date;
            $leaddata['account_yr'] = $account_yr;
            $jc_period = $this->lmsscorecard_model->get_current_jc($to_date,$account_yr);
            $leaddata['jc_week'] = $this->lmsscorecard_model->get_current_jc_week($to_date,$account_yr);
            $leaddata['jc_period'] = $jc_period;
            $leaddata['account_yr'] = $account_yr;
            
            $leaddata['ownbranch'] = "0";
            $leaddatareturn =$this->lmsscorecard_model->get_leadlms_scorecard($jc_implement_date,$today_date);
            $leaddatareturn_chart =$this->lmsscorecard_model->get_leadlms_scorecard_chart($jc_implement_date,$today_date);
            $leaddatareturn_pot_chart =$this->lmsscorecard_model->get_leadlms_potential_chart($jc_implement_date,$today_date);
            $leaddatareturn_con_chart =$this->lmsscorecard_model->get_lms_converted_chart($jc_implement_date,$today_date);
            
            //echo"<pre>";print_r($leaddatareturn_con_chart);echo"</pre>";
            $leaddata['data'] = $leaddatareturn['arr'];
            $leaddata['data_sc'] = $leaddatareturn['arr_sc'];

            $leaddata['arr_sc_chart'] = $leaddatareturn_chart['arr_sc_chart'];
            $leaddata['arr_pot_chart'] = $leaddatareturn_pot_chart['arr_pot_chart'];
            $leaddata['arr_count_chart'] = $leaddatareturn_con_chart['arr_count_chart'];

            
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

           $this->load->view('lmsdashboard/scorecard', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
    }
     function getlmswithfilters($fin_year=0,$jccode=0,$jcperiod_week=0,$zone=0,$collector=0,$market_circle=0,$itemgroup=0,$fromdate,$todate)
    {
        //print_r($_POST);
       //echo"<pre>";print_r($this->uri->segments);echo"</pre>"; die;
        /*
            [1] => lmsscorecard
            [2] => getlmswithfilters
            [3] => 2016-2017
            [4] => 52
            [5] => 53
            [6] => All
            [7] => All
            [8] => All
            [9] => All
            [10] => 2016-04-01
            [11] => 2016-05-21

        Array
        (
            [1] => lmsscorecard
            [2] => getlmswithfilters
            [3] => 2016-2017
            [4] => 53
            [5] => 60
            [6] => 2016-2017
            [7] => All
            [8] => All
            [9] => All
            [10] => ACETIC%20ACID
            [11] => 2016-04-01
            [12] => 2016-05-17
        )

        */
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
         
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $account_yr = $this->uri->segment(3);
            $jc_id = $this->uri->segment(4);
            $jc_week_id = $this->uri->segment(5);

            
            $zone = $this->uri->segment(6);
            $collector = urldecode($this->uri->segment(7));
            $marketcircle = $this->uri->segment(8);
            $itemgroup = urldecode($this->uri->segment(9));
            $fromdate = $this->uri->segment(10);
            $todate = $this->uri->segment(11);


            $jc_to = $this->lmsscorecard_model->get_current_jcfor_search($account_yr,$jc_id,$jc_week_id);
            $jc_week = $this->lmsscorecard_model->get_current_jc_weekfor_search($account_yr,$jc_id,$jc_week_id);

            $leaddata['account_yr'] = $account_yr;
            $leaddata['jc_period'] = $jc_to;
            $leaddata['jc_week']=$jc_week;
            $leaddata['zone'] = $zone;
            $leaddata['collector'] = $collector;
            $leaddata['marketcircle']=$marketcircle;
            $leaddata['itemgroup'] = $itemgroup;
            $leaddata['fromdate'] = $fromdate;
            $leaddata['todate'] = $todate;

//print_r($leaddata); die;
            
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
            
        $leaddatareturn =$this->lmsscorecard_model->get_leadlms_scorecard_withfilters($account_yr,$jc_to,$jc_week,$zone,$collector,$marketcircle,$itemgroup,$fromdate,$todate);
        $leaddatareturn_chart =$this->lmsscorecard_model->get_leadlms_scorecard_chart_search($account_yr,$jc_to,$jc_week,$zone,$collector,$marketcircle,$itemgroup,$fromdate,$todate);
        $leaddatareturn_pot_chart =$this->lmsscorecard_model->get_leadlms_potential_chart_search($account_yr,$jc_to,$jc_week,$zone,$collector,$marketcircle,$itemgroup,$fromdate,$todate);
      $leaddatareturn_con_chart =$this->lmsscorecard_model->get_lms_converted_chart_search($account_yr,$jc_to,$jc_week,$zone,$collector,$marketcircle,$itemgroup,$fromdate,$todate);
      //
      // echo"<pre>";print_r($this->session->userdata);echo"</pre>";
        $leaddata['data'] = $leaddatareturn['arr'];
        $leaddata['data_sc'] = $leaddatareturn['arr_sc'];
        $leaddata['arr_sc_chart'] = $leaddatareturn_chart['arr_sc_chart'];
        $leaddata['arr_pot_chart'] = $leaddatareturn_pot_chart['arr_pot_chart'];
        $leaddata['arr_count_chart'] = $leaddatareturn_con_chart['arr_count_chart'];

           $this->load->view('lmsdashboard/scorecard', $leaddata); 
        }else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);   
        }
       

       
    // echo"<pre>";print_r($leaddata);echo"</pre>";         

    }

    function getzone()
    {
        $zones = $this->lmsscorecard_model->get_zones();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $zones;
    }



    function getcollectors()
    {
        $collectors = $this->lmsscorecard_model->get_collectors();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $collectors; 
    }

    function getcollectorsforfilter($zone)
    {
        $collectors = $this->lmsscorecard_model->get_collectors_forfilter($zone);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $collectors; 
    }

    function getmarketcircles()
    {
        $marketcircles = $this->lmsscorecard_model->get_marketcircles();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $marketcircles;
    }
    function getmarketcirclesforfilter($collector)
    {
        $collector=html_entity_decode($collector);
        $marketcircles = $this->lmsscorecard_model->get_marketcircles_forfilter($collector);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $marketcircles;
    }
    function getproductgroups()
    {
        $itemgroups = $this->lmsscorecard_model->get_productgroups();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $itemgroups;
    }

    function getjchdrforweek($fin_year) 
    {
        $jc_headers = $this->lmsscorecard_model->get_jchdr_forweek($fin_year);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;
    }
    
    function getjcweek_hdr($fin_year,$jc_code) 
    {
        $jc_headers = $this->lmsscorecard_model->get_jcweek_hdr($fin_year,$jc_code);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;
    }

    function reloadjcweek_hdr($fin_year,$jc_code)
    {
         $jc_headers = $this->lmsscorecard_model->reload_jcweek_hdr($fin_year,$jc_code);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_headers;

    }

    function getjcweekdates($yr,$jc,$weekid)
    {
        $this->lmsscorecard_model->fin_year = urldecode($yr);
        $this->lmsscorecard_model->jc_code = urldecode($jc);
        $this->lmsscorecard_model->jc_week = urldecode($weekid);
        
        $get_jcperiods = $this->lmsscorecard_model->get_jcweek_periods();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $get_jcperiods;

    }



    function getfinanceyear() {
        $jc_finyear = $this->lmsscorecard_model->get_financeyear();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $jc_finyear;
    }

   
    
   

}

?>
