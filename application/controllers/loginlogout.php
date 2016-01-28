<?php

class Loginlogout extends CI_Controller {

   
    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->model('Leads_model');
        $this->load->model('loginlogout_model');
        $this->load->helper('url');
        $this->load->helper('html');
    }

    public function index() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $branch = $this->uri->segment(3);
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $out_array=explode(",",$this->session->userdata['get_assign_to_user_id']);
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            
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
            if($this->session->userdata['reportingto']!="" && count($out_array)==1 )
            {
              $leaddata['selectedIndex_val']=0;
            }
            else
            {
             $leaddata['selectedIndex_val']=-1;
            }
           
            $to_date= date("Y-m-d");  
            $account_yr = $this->Leads_model->get_current_accnt_yr($to_date);   
            $account_yr_from="2013-2014";
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2013-10-23';
            $leaddata['to_date'] = $to_date;
           
            $this->load->view('loginlogout/usertimespent', $leaddata);
        }
    }

     

     function getbranches() {
        $branches = $this->loginlogout_model->get_branches();
        //  $substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }

    public function indexold() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $branch = $this->uri->segment(3);
            $user_id = $this->uri->segment(4);

            $user = $this->admin_auth->user()->row();
            $out_array=explode(",",$this->session->userdata['get_assign_to_user_id']);
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $leaddata = array();

            
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
            if($this->session->userdata['reportingto']!="" && count($out_array)==1 )
            {
              $leaddata['selectedIndex_val']=0;
            }
            else
            {
             $leaddata['selectedIndex_val']=-1;
            }
            $leaddata['data'] = $this->loginlogout_model->get_usertime_spent();
            $to_date= date("Y-m-d");  
            $account_yr = $this->Leads_model->get_current_accnt_yr($to_date);   
            $account_yr_from="2013-2014";
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2013-10-23';
            $leaddata['to_date'] = $to_date;
           
            $this->load->view('loginlogout/usertimespent_with_griddata', $leaddata);
        }
    }

	function update_null_logout()
        {
                $date = date ('Y-m-j G:i:s');
                $format='Y-m-d';
                $previous_day = date ( $format, strtotime ('-1 day'.$date));  
                echo $sql="UPDATE lead_login_activity SET logout_time= login_time + (11 ||' minutes')::interval  WHERE login_time::DATE = '".$previous_day."'  AND logout_time is NULL";
                $result = $this->db->query($sql);
               
                
                     
       

        }
}

?>
