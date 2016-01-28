<?php

class Excelreport extends CI_Controller {

    public $leaddetails = array();

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('admin_auth');
        $this->lang->load('admin');

        $this->load->model('Excelreport_model');
        $this->load->model('exportexcel_model');
        
        $this->load->model('dashboard_model');
        //load new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

            if ($this->session->userdata['reportingto'] == "") {
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                //	$leaddata['data']=$this->Excelreport_model->get_all_leads_for_grid();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            } else {
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                //	$leaddata['data']=$this->Excelreport_model->get_all_leads_user_based($this->session->userdata['reportingto']);

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
              $leaddata['selectedIndex_val']=-1;
            }
            else
            {
             $leaddata['selectedIndex_val']=-1;
            }
            $to_date= date("Y-m-d");  //2015-07-07
            $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); //2015-2016
            $account_yr_from="2013-2014";
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2013-10-23';
            $leaddata['to_date'] = $to_date;
           
            $this->load->view('excelreport/viewallleads', $leaddata);
        }
    }

    function getbranches() {
        $branches = $this->dashboard_model->get_branches();
        //	$substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }

   /* function getassignedtobranch($brach_sel) {
        $this->dashboard_model->brach_sel = $brach_sel;
        $userlist = $this->dashboard_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }

    function getusersforloginuser() {

        $userlist = $this->dashboard_model->get_usersfor_loginuser();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }*/

    function exgetdatawithfilter($branch) {

        $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch','Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'comments', 'AssignTo', 'Created By', 'Lastupdate Date', 'Updated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname', 'prodqnty', 'Repack', 'Intact', 'Bulk', 'Small Packing', 'Single Tanker', 'Part Tanker', 'productupdatedate', 'productcreatedate', 'industrysegment', 'productname', 'itemgroup', 'uom', 'customername', 'customertype');
        $sql = "SELECT * FROM lead_export_to_excel_no_ldaddress_tbl WHERE  branchname='" . $branch . "' limit 3";
       //	echo $sql; die;
        $result = $this->db->query($sql);
        //	print_r($result); die;
        if ($result) {
            // Create a new PHPExcel object 
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('List of Users');

            $rowNumber = 1;
            $col = 'A';
            foreach ($headings as $heading) {
                $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $heading);
                $col++;
            }
            // Loop through the result set 
            $rowNumber = 2;
            $result = $this->db->query($sql);
            $leaddetails = $result->result_array();
            $count = $result->num_rows();
            //	print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    echo"col " . $col . "<br>";
                    echo"rowNumber " . $rowNumber . "<br>";
                    echo"cell " . $cell . "<br>";

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            /* $objPHPExcel->getActiveSheet()->freezePane('A2'); 

              // Save as an Excel BIFF (xls) file
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

              header('Content-Type: application/vnd.ms-excel');
              header('Content-Disposition: attachment;filename="LeadProductsList.xls"');
              header('Cache-Control: max-age=0');

              $objWriter->save('php://output');
              exit(); */
        }
    }

    function getdatawithfilter($branch, $sel_user_id = 0) {
        $branch = $this->uri->segment(3);
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
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                $leaddata['data'] = $this->Excelreport_model->get_all_leads_for_grid_withfilter($branch, $sel_user_id);
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
            } else {
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                //$leaddata['data']=$this->Excelreport_model->get_all_leads_user_based($this->session->userdata['reportingto']);
                $leaddata['data'] = $this->Excelreport_model->get_leads_user_based_for_grid_withfilter($branch, $sel_user_id);

                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $sel_user_id);
            }

            /* if ($this->session->userdata['reportingto']=="")
              {
              $leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard_withfilter($branch,$sel_user_id);
              $leaddata['datact']=$this->dashboard_model->get_leaddetails_aging_chart_withfilter($branch,$sel_user_id);
              $leaddata['leadcount']=$this->session->userdata['all_leads_count'];
              $leaddata['lead_ub_count']=$this->dashboard_model->get_lead_user_branch_count($branch,$sel_user_id);
              $leaddata['branch'] = $branch;
              $leaddata['sel_user_id'] = $sel_user_id;
              }
              else
              {
              $leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard_withfilter($branch,$sel_user_id);
              $leaddata['datact']=$this->dashboard_model->get_leaddetails_aging_chart_withfilter($branch,$sel_user_id);
              $leaddata['leadcount']=$this->session->userdata['user_leads_count'];
              $leaddata['lead_ub_count']=$this->dashboard_model->get_lead_user_branch_count($branch,$sel_user_id);
              $leaddata['branch'] = $branch;
              $leaddata['sel_user_id'] = $sel_user_id;

              } */
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
            $this->load->view('excelreport/viewallleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getdatawithbranchdtfilter() {

        $branch = $this->uri->segment(3);
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
                $leaddata['data'] = $this->Excelreport_model->get_leads_withbranchdatefilter($branch, $from_date, $to_date);

                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_branchdatefilter($branch, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->Excelreport_model->get_leads_withbranchdatefilter($branch, $from_date, $to_date);

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



            $this->load->view('excelreport/viewallleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function getuserwithdate_filter() {

        $branch = $this->uri->segment(3);
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
                $leaddata['data'] = $this->Excelreport_model->get_leaddetails_userbased_withdatefilter($branch, $sel_user_id, $from_date, $to_date);

                $leaddata['leadcount'] = $this->session->userdata['all_leads_count'];
               // $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count_datefilter($branch, $sel_user_id, $from_date, $to_date);
                $leaddata['branch'] = $branch;
                $leaddata['sel_user_id'] = $sel_user_id;
                $leaddata['from_date'] = $from_date;
                $leaddata['to_date'] = $to_date;
            } else {
                $leaddata['data'] = $this->Excelreport_model->get_leaddetails_userbased_withdatefilter($branch, $sel_user_id, $from_date, $to_date);

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
            $this->load->view('excelreport/viewallleads', $leaddata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }


    public function dcvisitreport() {
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

            if ($this->session->userdata['reportingto'] == "") {
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                //  $leaddata['data']=$this->Excelreport_model->get_all_leads_for_grid();
                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            } else {
                //$leaddata['data']=$this->dashboard_model->get_leaddetails_aging_dashboard();
                //  $leaddata['data']=$this->Excelreport_model->get_all_leads_user_based($this->session->userdata['reportingto']);

                $leaddata['leadcount'] = $this->dashboard_model->get_all_leads_count();
                $leaddata['lead_ub_count'] = $this->dashboard_model->get_lead_user_branch_count($branch, $user_id);
            }
            $leaddata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];

            if (($branch == "") or ( $user_id = "")) {
                $leaddata['maxvalue'] = $leaddata['leadcount'];
                $leaddata['cust_group'] = 'SelectCustomer';
            } else {
                $leaddata['maxvalue'] = $leaddata['lead_ub_count'];
                $leaddata['cust_group'] = 'SelectCustomer';
            }

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
              $leaddata['selectedIndex_val']=-1;
            }
            else
            {
              $leaddata['selectedIndex_val']=-1;
            }
            $to_date= date("Y-m-d");  //2015-07-07
            $account_yr = $this->Leads_model->get_current_accnt_yr($to_date); //2015-2016
            $account_yr_from="2013-2014";
            $leaddata['branch'] = 'All';
            $leaddata['from_date'] = '2013-10-23';
            $leaddata['to_date'] = $to_date;
           
            $this->load->view('excelreport/dailyvisitreport', $leaddata);
        }
    }

     function getcustomergrp() {
        $branches = $this->exportexcel_model->get_customergroup();
        //  $substatus = $this->Leads_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $branches;
    }

    function getitemgroupcustomer($cust_group) {
        $this->exportexcel_model->cust_group = urldecode($cust_group);
        $userlist = $this->exportexcel_model->get_itemgroup_customer();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }

    function getitemgroups() {

        $userlist = $this->exportexcel_model->get_itemgroups();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }
     function getusersforloginuser() {

        $userlist = $this->exportexcel_model->get_usersfor_loginuser();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }
    
    function getassignedtobranch($brach_sel) {
        $this->exportexcel_model->brach_sel = urldecode($brach_sel);
        $userlist = $this->exportexcel_model->get_assigned_tobranch();
        header('Content-Type: application/x-json; charset=utf-8');
        echo $userlist;
    }




}

// End of Class
?>
