<?php

class dailyactivity extends CI_Controller {

    public $data = array();
    public $post = array();
    public $daily_dtl = array();
    public $activitydata = array();
    public $loginuser;
    public $userid;
    public $loginname;
    public $reportingto;
    public $countryid;
    public $stateid;
    public $login_user_id;
    public $daily_hdr_id;
    public $daily_hdr_insert_status;
    public $datagroup = array();
    public $db_dc;

    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->database('forms', TRUE);
        $this->load->helper('url');
        //$this->load->model('Leads_model');
        $this->load->model('dailyactivity_model');
        $this->load->model('Leads_model');
        $this->load->library('subquery');
        $this->load->library('session');
        $this->load->helper('html');
    }

   
    public function index() {
        //print_r($this->session->userdata); 

        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];

        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $activitydata = array();
            $data = array();
            $activitydata['permission'] = $usergroups->_cache_user_in_group[$this->session->userdata['user_id']];
            $i = 0;
            $datagroup = array();
            foreach ($activitydata['permission'] as $key => $val) {
                $row = array();

                $row["groupid"] = $key;
                $row["groupname"] = $val;
                $datagroup[$i] = $row;
                $i++;
            }

            $arr = json_encode($datagroup);
            $activitydata['grpperm'] = $arr;

            if ($this->session->userdata['reportingto'] == "") {

                $activitydata['data'] = $this->dailyactivity_model->getactivity_data_all();
            } else {
                $activitydata['data'] = $this->dailyactivity_model->getactivity_data($this->session->userdata['user_id']);
            }

            $sql = 'SELECT  DISTINCT  itemgroup FROM itemmaster ORDER BY itemgroup asc';
            $activitydata['dataitemmaster'] = $this->dailyactivity_model->get_products($sql);
            $this->load->view('dailyactivity/viewdailyactivity', $activitydata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function get_edit_data() {
        $header_id = $this->uri->segment(3);
        $activitydata['datacolumn'] = $this->dailyactivity_model->getactivity_data_column();
        $activitydata['datarow'] = $this->dailyactivity_model->getactivity_data_row($header_id);
        $viewdata = '[' . $activitydata['datacolumn'] . ',' . $activitydata['datarow'] . ']';
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    function get_data_addgrid() {

        $activitydata['datacolumn'] = $this->dailyactivity_model->getactivity_data_column();
        $viewdata = '[' . $activitydata['datacolumn'] . ']';
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    function get_data_itemmaster() {
       
        /*$sql = 'SELECT  DISTINCT  itemgroup FROM view_tempitemmaster_grp ORDER BY itemgroup asc';*/
        //$sql = 'SELECT  min(id) as itemid, itemgroup FROM view_tempitemmaster_grp GROUP BY itemgroup ORDER BY itemgroup asc';
        $sql = 'SELECT  min(id) as itemid, itemgroup FROM view_tempitemmaster_pg GROUP BY itemgroup ORDER BY itemgroup asc';
        $activitydata['dataitemmaster'] = $this->dailyactivity_model->get_products($sql);
        $viewdata = $activitydata['dataitemmaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }
     function get_data_customermaster() {

        //$sql='SELECT  distinct on (view_tempcustomermaster.tempcustname) view_tempcustomermaster.id,view_tempcustomermaster.tempcustname FROM     view_tempcustomermaster ORDER BY  tempcustname ASC';

        $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup FROM customermasterhdr order by customergroup";
       
        $activitydata['datacustomermaster'] = $this->dailyactivity_model->get_customers($sql);
        $viewdata = $activitydata['datacustomermaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

     function get_data_customermaster_coll($collector) {

        //$sql='SELECT  distinct on (view_tempcustomermaster.tempcustname) view_tempcustomermaster.id,view_tempcustomermaster.tempcustname FROM     view_tempcustomermaster ORDER BY  tempcustname ASC';
        $collector = urldecode($collector);
        /* $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup,id  FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/
     /*   $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/

        $sql = "SELECT min(id) as id,replace(customergroup,'''','')   as customergroup FROM customermasterhdr WHERE    collector ='".$collector."' or collector is NULL    GROUP BY customergroup order by customergroup";

       //echo $sql; die;
        $activitydata['datacustomermaster'] = $this->dailyactivity_model->get_customers($sql);
        $viewdata = $activitydata['datacustomermaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }
    function get_data_customermaster_coll_mc($collector,$market_circle) {

        //$sql='SELECT  distinct on (view_tempcustomermaster.tempcustname) view_tempcustomermaster.id,view_tempcustomermaster.tempcustname FROM     view_tempcustomermaster ORDER BY  tempcustname ASC';
        $collector = urldecode($collector);
        /* $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup,id  FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/
     /*   $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/

        $sql = "SELECT min(id) as id,replace(customergroup,'''','')   as customergroup,mc_code FROM customermasterhdr WHERE   collector ='".$collector."'  AND mc_code='".$market_circle."' or collector is NULL  GROUP BY customergroup,mc_code order by customergroup ";

     //  echo $sql; die;
        $activitydata['datacustomermaster'] = $this->dailyactivity_model->get_customers($sql);
        $viewdata = $activitydata['datacustomermaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    function get_potentialquantity() {
        $item = html_entity_decode($this->uri->segment(4));
        $customer = html_entity_decode($this->uri->segment(3));
        $activitydata['potential_quantity'] = $this->dailyactivity_model->get_potential_item_customer($item, $customer);
        $viewdata = $activitydata['potential_quantity'];
        echo $viewdata;
    }

    function get_leadpotential($leadid) {

        $activitydata['potential_quantity'] = $this->dailyactivity_model->get_lead_potential($leadid);
        $viewdata = $activitydata['potential_quantity'];
        echo $viewdata;
    }
        function get_leadpotential_update($leadid) {

        $activitydata['potential_quantity'] = $this->dailyactivity_model->get_lead_potential_update($leadid);
        $viewdata = $activitydata['potential_quantity'];
        echo $viewdata;
    }
    

   

    public function index1() {
        if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) {

            $user = $this->admin_auth->user()->row();
            $allgroups = $this->admin_auth->groups()->result();
            $usergroups = $this->admin_auth->group($this->session->userdata['user_id']);
            $activitydata = array();

            $data = array();
            
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
            //  $this->load->view('leads/viewleadsnew',$leaddata);	
            $activitydata['datarow'] = $this->dailyactivity_model->getactivity_data_row();
            $activitydata['datacolumn'] = $this->dailyactivity_model->getactivity_data_column();

            

            $this->load->view('dailyactivity/viewdailyactivity1', $activitydata);
        } else {
            redirect('admin/index', 'refresh');
            //$this->load->view('leads/viewleads',$leaddata);	
        }
    }

    function additemmaster() {
        $createddate = date('Y-m-d:H:i:s');
        $lastupdatedate = date('Y-m-d:H:i:s');
        $creationuser = $this->session->userdata['identity'];
        $lastupdateuser = "";
         $execode = $this->session->userdata['empcode'];
         $exename = $this->session->userdata['identity'];
         $user1 = $this->session->userdata['loginname'];
         $login_username = $this->session->userdata['username'];
         $user_id = $this->session->userdata['user_id'];
        $login_user_id = $this->session->userdata['user_id'];

         $assign_to_array = $this->Leads_model->GetAssigntoName($login_user_id);
         $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
         $duser = $assign_to_array['0']['duser'];
         $header_user_id = $assign_to_array['0']['header_user_id'];
         $sales_type_flag="R";
       
        //echo "current_date ".$_POST[0]['currentdate'];
   //     echo"<pre> _POST data";print_r($_POST);echo"</pre>";
        $hrd_currentdate = $_POST[0]['currentdate'];
        $grid_data = array_slice($_POST, 1, null, true);
      //  echo"<pre> grid data";print_r($grid_data);echo"</pre>"; 
      //  die;
        $check_duplicates = $this->dailyactivity_model->check_dailyhdr_duplicates($hrd_currentdate, $user1);
        //  echo $check_duplicates; die;
        $today_date = date('Y-m-d:H:i:s');
        if ($check_duplicates == 0) {
            if ($_POST['save'] == 'true') {

                /* start insert into dailyhdr table */
                $daily_hdr_id = $this->dailyactivity_model->GetMaxVal('dailyactivityhdr');
                $daily_hdr_id = $daily_hdr_id + 1;
                $daily_hdr = array('id' => $daily_hdr_id,
                    'currentdate' => $hrd_currentdate,
                    'execode' => $execode,
                    'exename' => $exename,
                    'companycode' => 'PPC',
                    'accperiod' => '2015-2016',
                    'user1' => $user1,
                    'creationuser' => $creationuser,
                    'creationdate' => $createddate,
                    'lastupdateuser' => $lastupdateuser
                    );
                
                $daily_hdr_insert_status = $this->dailyactivity_model->save_dailyactivityhdr($daily_hdr);
                

               
                /* end of insert into dailyhdr table*/
                /* Start for inserting into leaddetails*/
                  foreach ($grid_data as $key => $val) 
                  { 
                    if($val['lead_appointmentdt']=='null' or $val['lead_appointmentdt']=='undefined' ||  $val['lead_appointmentdt']=="" || $val['lead_appointmentdt']=='--')
                         {
                            $appiontment_date=NULL;
                         }
                         else
                         {
                            $appiontment_date=$val['lead_appointmentdt'];
                         }

                         if($val['not_able_to_get_appointment']=='null' or $val['not_able_to_get_appointment']=='undefined')
                         {
                            $reason_no_appointment=NULL;
                         }
                         else
                         {
                            $reason_no_appointment=$val['not_able_to_get_appointment'];
                         }
                      $samle_reject_count=0;
                      /*Start of if create_lead flag is set to 1*/
                      if ($val['create_lead']==1) 
                      {
                         $lead_no = 'LEAD-DCV';
                         $lead_status_name = $val['statusid'];
                         $lead_sub_status_name =$val['leadsubstatusid'];
                         $lead_status_id = $this->dailyactivity_model->get_leadstatusidbyname($val['statusid']);
                         $lead_substatus_id = $this->dailyactivity_model->get_leadsub_statusidbyname($val['leadsubstatusid']);
                         $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname($val['division']);

                         $customer_id=$val['hdn_cust_id'];
                         $customer_address[] = $this->dailyactivity_model->get_customer_address($customer_id);
                         $user_branch = $this->dailyactivity_model->get_user_branch($login_user_id);
                         if($val['itemgroup']=="")
                            {
                                $itemgroup_name = $this->Leads_model->GetItemgroup($val['hdn_prod_id']);
                                 if ($itemgroup_name['itemgroup'] != "") {
                                        $itemgroup_name = $itemgroup_name['itemgroup'];
                                    } else {
                                        $itemgroup_name = $itemgroup_name['description'];
                                    }
                            }
                            else
                            {
                                $itemgroup_name = $val['itemgroup'];  
                            }
                         
                        
                       
                         //print_r($customer_address);
                         /* Start for inserting into leaddetails*/
                         $leaddetails = array('lead_no' => $lead_no,
                            'leadstatus' => $lead_status_id,
                            'company' => $customer_id,
                            'customer_id' => $customer_id,
                            'assignleadchk' => $user_id,
                            'leadsource' => 13,
                            'ldsubstatus' => $lead_substatus_id,
                            'createddate' => date('Y-m-d:H:i:s'),
                            'last_modified' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id,
                            'email_id' => trim($customer_address[0]['contact_mailid']),
                            'firstname' => trim($customer_address[0]['contact_person']),
                            'industry_id' => 63,
                            'uploaded_date' => $hrd_currentdate,
                            'crd_id' => 8,
                            'crd_assesment' =>'Update Later',
                            'assignleadchk' => $login_user_id,
                            'user_branch' => $user_branch,
                            /*'description' => "added from dailyactivity",
                            'comments' => "comments added from dailyactivity",*/
                            'description' => $val['Remarks'],
                            'comments' => $val['Remarks'],
                            'sales_type_flag' => $sales_type_flag,
                            'createddate' => date('Y-m-d:H:i:s'),
                            'last_modified' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                            
                        );
                        /* End for inserting into leaddetails*/
                         $lead_id = $this->Leads_model->save_lead($leaddetails);
                         /* Start for saving in status and substatus mail alert table*/

                         if (($lead_status_id == 1) && ( $lead_substatus_id == 3 ||
                                $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 ) || ($lead_status_id == 2) && ($lead_substatus_id == 7 )) 
                        { // Start of if condition for inserting in the mail alert table
                            if (@$appiontment_date != "") {/*4*/
                                
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $login_user_id,
                                        'appointment_due_date' => $appiontment_date,
                                        'not_able_to_get_appiontment' => $reason_no_appointment,
                                        'status_created_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                                        'status_action_type' => "Insert"
                                    );
                                } else if ($lead_substatus_id == 3) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($lead_substatus_id == 6) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($lead_substatus_id == 7) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
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
                             if (($lead_status_id == 5) && ( $lead_substatus_id == 26))
                             {

                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            
                            }
                        /*End for Enquiry/offer*/
                        
                        if (($lead_status_id == 4) && ( $lead_substatus_id == 16 ||
                                $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19  || $lead_substatus_id == 20 || $lead_substatus_id == 21 )) 
                            {
                             
                                $today_date = date('Y-m-d');
                                $mail_alert_date="timestamp '" . $today_date . "' +interval '0 hours'";
                                if ($lead_substatus_id == 18 || $lead_substatus_id == 19 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '48 hours'"; 
                                }
                                if ($lead_substatus_id == 20 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                                }
                                $lead_status_mailalert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "Insert"
                                );
                            }
                            if  ($lead_status_id == 4 &&  $lead_substatus_id == 21) 
                            {
                                $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => 4,
                                    'lead_substatus_id' => 16,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                );
                            }
                            else if ($lead_substatus_id == 20)
                            {
                               $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => 5,
                                    'lead_substatus_id' => 24,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                ); 
                            }
                        /*END for Sample trails status*/
                       

                        
                        if ($lead_substatus_id == 3 || $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 || $lead_substatus_id == 7 || $lead_substatus_id == 16 || $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19 || $lead_substatus_id == 20 || $lead_substatus_id == 21 || $lead_substatus_id == 26 || $lead_substatus_id == 26) {
                            $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert($lead_status_mailalert);
                        }
                         if (($lead_status_id == 4 &&  $lead_substatus_id == 21 ) || ($lead_substatus_id == 20 ))
                            {
                                $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_revert($update_leadstatus_mailalert_revert);
                            }
                         /* End for saving in mail_alert_table*/
                         /* Start for inserting into leadproducts*/
                           if ($val['quantity'] == "" || $val['quantity'] == 'undefined') 
                              {
                                $val['quantity']=0;
                              }

                           $leadproducts = array('leadid' => $lead_id,
                            'productid' => $val['hdn_prod_id'],
                            'quantity' => $val['quantity'],
                            'product_group' => $itemgroup_name,
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );
                        $prdetid = $this->Leads_model->save_lead_products_all($leadproducts);
                         /* End for inserting into leadproducts*/

                         /* Start for inserting into lead_prod_potential_types*/
                          $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
                            $lpid_seq = $this->Leads_model->GetNextlpid($lead_id);
                            $lpid_seq = $lpid_seq;
 
                            for ($k = 0; $k < count($product_sale_type); $k++) {
                                $lead_prod_poten_type[$k]['leadid'] = $lead_id;
                                $lead_prod_poten_type[$k]['productid'] = $val['hdn_prod_id'];
                                $lead_prod_poten_type[$k]['product_type_id'] =$product_sale_type[$k]['n_value_id'];

                                 if ($product_sale_type[$k]['n_value_id'] == $sales_type_id) {
                                    $lead_prod_poten_type[$k]['potential'] = $val['actualpotenqty'];
                                } else {
                                    $lead_prod_poten_type[$k]['potential'] = 0;
                                }

                               
                                
                            }
                            //echo"<pre>lead_prod_poten_type ";print_r($lead_prod_poten_type);echo"</pre>";
                            $lead_pord_poten_id = $this->Leads_model->save_leadprodpotentypes($lead_prod_poten_type);

                            $k = 0;
                         /* End for inserting into lead_prod_potential_types*/

                         /* Start of creating log and sublog details with revert back to previous status*/
                           $lead_log_details = array('lh_lead_id' => $lead_id,
                        'lh_user_id' => $login_user_id,
                        'lh_lead_curr_status' => $lead_status_name,
                        'lh_lead_curr_statusid' => $lead_status_id,
                        'lh_created_date' => date('Y-m-d:H:i:s'),
                        'lh_created_user' => $login_user_id,
                        /*'lh_comments' => "status log comments for lead created from dailyactivity",*/
                        'lh_comments' => $val['Remarks'],
                        'action_type' => 'Insert',
                        'created_user_name' => $login_username,
                        'assignto_user_id ' => $login_user_id,
                        'assignto_user_name' => $lead_assign_name
                    );
                    
                    $logid = $this->Leads_model->create_leadlog($lead_log_details);

                    $lead_sublog_details = array(
                        'lhsub_lh_id' => $logid,
                        'lhsub_lh_lead_id' => $lead_id,
                        'lhsub_lh_user_id' => $login_user_id,
                        'lhsub_lh_lead_curr_status' => $lead_status_name,
                        'lhsub_lh_lead_curr_statusid' => $lead_status_id,
                        'lhsub_lh_lead_curr_sub_status' => $lead_sub_status_name,
                        'lhsub_lh_lead_curr_sub_statusid' => $lead_substatus_id,
                        /*'lhsub_lh_comments' => "substatus log comments for lead created from dailyactivity",*/
                        'lhsub_lh_comments' => $val['Remarks'],
                        'lhsub_lh_created_date' => date('Y-m-d:H:i:s'),
                        'lhsub_lh_created_user' => $login_user_id,
                        'lhsub_action_type' => 'Insert',
                        'lhsub_created_user_name' => $login_username,
                        'lhsub_assignto_user_id ' => $login_user_id,
                        'lhsub_assignto_user_name' => $lead_assign_name
                    );


                    $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
                    /* end log details */
                    // Sample and Trails log and sublog entry for revert back - Start 
                        
                        if ($lead_substatus_id == 20 || $lead_substatus_id == 21) 
                        {   $samle_reject_count=0;
                            $lead_status_update='Y';
                            // Start update leaddetails 
                            $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count));
                            $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                            $leaddetails_update = array(
                                'leadstatus' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'ldsubstatus' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
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
                                'lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lh_last_updated_user' => $login_user_id,
                                'lh_comments' => "status log comments revertback dailyactivity",
                                'action_type' => 'RevertBack',
                                'modified_user_name' => $login_username,
                                'assignto_user_id ' => $login_user_id,
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
                                'lhsub_lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lhsub_lh_last_updated_user' => $login_user_id,
                                'lhsub_lh_comments' => "sublog comments revertback from dailyactivity",
                                'lhsub_action_type' => "RevertBack",
                                'lhsub_modified_user_name' => $login_username,
                                'lhsub_assignto_user_id ' => $login_user_id,
                                'lhsub_assignto_user_name' => $lead_assign_name,
                                'lhsub_status_update' => $lead_status_update
                            );
                            $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);
                         }   
                         /* End of creating log and sublog details with revert back to previous status*/

                      }  // end of if create_lead flag is set to 1
/********************************************************************************************************************/
                                    // update function of leaddetails create_flag=0 and noofleads=1
/********************************************************************************************************************/
                      else if($val['noofleads']>0) //Start of condtion if create_lead flag is set to 0 and no of leads =1 // update function of leaddetails
                      {
                        $lead_id=$val['leadid'];
                        //$order_cancelled_reason=$val['order_cancelled_reason'];
                        $closing_comments="test for closing comments";
                        $lead_close_status=0;
                        $lead_close_option="2";
                        $ld_converted=0;
                        $samle_reject_count=0;
                        $sample_rejected_reason=$val['sample_rejected_reason'];

                        
                         $lead_no = 'LEAD-DCV';
                         $lead_status_name = $val['statusid'];
                         $log_lead_status_name = $val['statusid'];
                         $lead_sub_status_name =$val['leadsubstatusid'];
                         $sublog_lead_substatus_name = $val['leadsubstatusid'];
                         $lead_status_id = $this->dailyactivity_model->get_leadstatusidbyname($val['statusid']);
                         $lead_substatus_id = $this->dailyactivity_model->get_leadsub_statusidbyname($val['leadsubstatusid']);
                         $prev_status_id =$val['prevstatusid'];
                         $prev_substatus_id =$val['prevsubstatusid'];
                       // echo " substatus id ".$lead_substatus_id."<br>";
                         $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname($val['division']);

                         $customer_id=$val['hdn_cust_id'];
                         $customer_address[] = $this->dailyactivity_model->get_customer_address($customer_id);
                         $user_branch = $this->dailyactivity_model->get_user_branch($login_user_id);

                         if (($lead_status_id != $prev_status_id) || ($lead_substatus_id != $prev_substatus_id)) {
                                $update_log = 1;
                            } else {
                                $update_log = 0;
                            }

                            if ($lead_status_id != $prev_status_id || ($lead_substatus_id != $prev_substatus_id )) {
                                $lead_status_update = 'Y';
                            } else {
                                $lead_status_update = 'N';
                            }
                            if($val['crm_soc_number']=='undefined' || $val['crm_soc_number']=="")
                             {
                                $crm_first_soc_no=0;
                                $ld_converted = 0;
                             }
                             else
                             {
                                
                                $crm_first_soc_no =$val['crm_soc_number'];
                                $ld_converted = 1;
                             }

                                $leaddetails = array(
                                'leadstatus' => $lead_status_id,
                                'ldsubstatus' => $lead_substatus_id,
                                /*'comments' => "updated from dailyactivity",*/
                                'comments' => $val['Remarks'],
                                'email_id' => $this->input->post('email_id'),
                                'lead_2pa_no' => 0,
                                'lead_crm_soc_no' => $crm_first_soc_no,
                                'lead_close_status' => $lead_close_status,
                                'lead_close_option' => $lead_close_option,
                                'lead_close_comments' => $closing_comments,
                                'converted' => $ld_converted,
                                'nextstepdate' => date('Y-m-d'),
                                'last_modified' => date('Y-m-d:H:i:s'),
                                'last_updated_user' => $login_user_id
                            );

                            $id = $this->Leads_model->update_lead($leaddetails, $lead_id);

                /* update LMS potential when selected an exisitng lead start*/

                  $leadproducts = array(
                        'quantity' => $val['quantity'],
                        'last_modified' => date('Y-m-d:H:i:s'),
                        'last_updated_user' => $login_user_id
                    );
                $id = $this->Leads_model->update_leadproducts($leadproducts, $lead_id);  

                $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname($val['division']);  
                                                    

                             $lead_prod_poten_type_update = array(
                                'potential' => $val['actualpotenqty']
                            );
                        $lead_pord_poten_id = $this->Leads_model->dcupdate_leadprodpotentypes($lead_prod_poten_type_update, $lead_id,$sales_type_id);
                /* update LMS potential when selected an exisitng lead  end*/                    


                /* Start of substatus validations */

                                /* Start for Managing and implementation*/

                                    $today_date = date('Y-m-d');
                                    if ($lead_status_id == 6 ) 
                                        {
                                            $lead_status_mailalert = array('leadid' => $lead_id,
                                                'user_id' => $login_user_id,
                                                'branch' => $user_branch,
                                                'lead_status_id' => $lead_status_id,
                                                'lead_substatus_id' => $lead_substatus_id,
                                                'last_update_user_id' => $login_user_id,
                                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                                'status_action_type' => "Update"
                                            );
                                        }
                                    if ($lead_status_id == 7 ) 
                                        {
                                            $lead_status_mailalert = array('leadid' => $lead_id,
                                                'user_id' => $login_user_id,
                                                'branch' => $user_branch,
                                                'lead_status_id' => $lead_status_id,
                                                'lead_substatus_id' => $lead_substatus_id,
                                                'last_update_user_id' => $login_user_id,
                                                'soc_no' => "1234",
                                                'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                                'status_action_type' => "Update"
                                            );
                                        }                        

                                    /* End for Managing and implementation*/
                            if ($lead_substatus_id == 6) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '1 hours'",
                                    'status_action_type' => "Update"
                                );
                            } else {
                                if (@$appiontment_date != "" && $lead_substatus_id == 4 ) {
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'appointment_due_date' => $appiontment_date,
                                        'not_able_to_get_appiontment' => @$reason_no_appointment,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                                        'status_action_type' => "Update"
                                    );
                                } else if ($lead_substatus_id == 3) {
                                    $today_date = date('Y-m-d');
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'appointment_due_date' => @$appiontment_date,
                                        'not_able_to_get_appiontment' => @$reason_no_appointment,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                        'status_action_type' => "Update"
                                    );
                                }
                            }
                            if ($lead_substatus_id == 7) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            if ($lead_substatus_id == 21) 
                              {
                                $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($lead_id,$lead_substatus_id);
                               } 
                            if ($lead_substatus_id == 15) 
                            {
                                $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                                $leaddetails_close = array(
                                    'lead_close_status' => 1,
                                    'lead_close_option' => $sublog_lead_substatus_name,
                                    'lead_close_comments' => "RevertBack",
                                    'last_modified' => date('Y-m-d:H:i:s'),
                                    'last_updated_user' => $login_user_id
                                );


                                $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                //  print_r($leaddetails_close);
                            }
                             /*Start for Enquiry/Offer*/
                             if ($lead_substatus_id == 26) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                             /*End for Enquiry/Offer*/
                             // Order Cancelled start
                             if ($lead_substatus_id == 27) 
                            {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'order_cancelled_reason' => $order_cancelled_reason,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                );
                            }
                            if ($lead_substatus_id == 3 || $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 || $lead_substatus_id == 7 || $lead_substatus_id == 16 || $lead_substatus_id == 17|| $lead_substatus_id == 18|| $lead_substatus_id == 19|| $lead_substatus_id == 20|| $lead_substatus_id == 21 || $lead_substatus_id == 26 || $lead_substatus_id == 27 || $lead_substatus_id == 28 || $lead_substatus_id == 29) 
                                {
                                 
                                 $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_update(@$lead_status_mailalert);
                                }
                            // Order Cancelled end 
                            /*Start for Sample Trails updateleadstatus*/
                              if ($lead_substatus_id == 16 || $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19 || $lead_substatus_id == 20 || $lead_substatus_id == 21 ) 
                                         {
                                            if ($lead_substatus_id == 21) 
                                            {
                               
                                                    if ($samle_reject_count>1)
                                                    {
                                                        $today_date = date('Y-m-d:H:i:s');
                                                             $lead_status_mailalert = array('leadid' =>$lead_id,
                                                            'user_id' => $login_user_id,
                                                            'branch' => $user_branch,
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
                                                            $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                                                            $leaddetails_close = array(
                                                            'lead_close_status' => 1,
                                                            'lead_close_option' => $sublog_lead_substatus_name,
                                                            'lead_close_comments' => "RevertBack",
                                                            'last_modified' => date('Y-m-d:H:i:s'),
                                                            'last_updated_user' => $login_user_id
                                                            );

                                                            $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                                    }
                                                    else 
                                                    {
                                                        $today_date = date('Y-m-d:H:i:s');
                                                             $lead_status_mailalert = array('leadid' =>$lead_id,
                                                            'user_id' => $login_user_id,
                                                            'branch' => $user_branch,
                                                            'lead_status_id' => $lead_status_id,
                                                            'lead_substatus_id' => $lead_substatus_id,
                                                            'last_update_user_id' => $login_user_id,
                                                            'assignto_id' => $this->input->post('assignedto'),
                                                            'sample_reject_reason' => $sample_rejected_reason,
                                                            'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                            'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                            'status_action_type' => "RevertBack"
                                                        );
                                                    }
                                                
                                            }
                                            else if ($lead_substatus_id == 20)
                                            {
                                                $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                                    'status_action_type' => "Update"
                                                );
                                            }
                                            else if ($lead_substatus_id == 18 || $lead_substatus_id == 19)
                                            {
                                                $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                                    'status_action_type' => "Update"
                                                );
                                            }
                                            else if ($lead_substatus_id == 16 || $lead_substatus_id == 17)
                                            {
                                                $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                    'status_action_type' => "Update"
                                                );
                                            }
                                        }
                                        
                                        /*END for Sample trails status*/
                                         $lead_log_details_update = array('lh_lead_id' => $lead_id,
                                                'lh_user_id' => $login_user_id,
                                                'lh_lead_curr_status' => $log_lead_status_name,
                                                'lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                                'lh_updated_date' => date('Y-m-d:H:i:s'),
                                                'lh_last_updated_user' => $login_user_id,
                                                /*'lh_comments' => "updated from daily call",*/
                                                'lh_comments' => $val['Remarks'],
                                                'action_type' => 'RevertBack',
                                                'modified_user_name' => $login_username,
                                                'assignto_user_name' => $lead_assign_name,
                                                'status_update' => $lead_status_update
                                            );
                                         //   echo"log array ";print_r($lead_log_details_update);echo"</pre>";

                                            $logid = $this->Leads_model->create_leadlog($lead_log_details_update);
                                            /* End */

                                            // insert a record in lead sub log details
                                            /* Start */
                                            $lead_sublog_details_update = array(
                                                'lhsub_lh_id' => @$logid,
                                                'lhsub_lh_lead_id' => $lead_id,
                                                'lhsub_lh_user_id' => $login_user_id,
                                                'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                                                'lhsub_lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                                'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                                'lhsub_lh_last_updated_user' => $login_user_id,
                                                'lhsub_lh_comments' => "updated from daily call revertback",
                                                'lhsub_action_type' => "RevertBack",
                                                'lhsub_modified_user_name' => $login_username,
                                                'lhsub_assignto_user_id ' => $login_user_id,
                                                'lhsub_assignto_user_name' => $lead_assign_name,
                                                'lhsub_status_update' => $lead_status_update
                                            );
                                            $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);
                                                
                                                /* Start revert back sub log details */
                                                

                                                $today_date = date('Y-m-d');
                                                if($lead_substatus_id == 5)
                                                {
                                                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '48 hours'";    
                                                }
                                                else if($lead_substatus_id == 20)
                                                {
                                                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                                                }
                                                else if($lead_substatus_id == 21)
                                                {
                                                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'";
                                                } 
                                                else 
                                                {
                                                    $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'"; 
                                                }
                                                    $update_leadstatus_mailalert_revert = array('leadid' => $lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                                    'lead_substatus_id' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                                    'last_update_user_id' => $login_user_id,
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
                                                  $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                                 }
                                                /* End revert back sub log details  */
                                            
                                 



                      } //End if  create_lead flag is set to 0 and no of leads =1 // update function of leaddetails
                      /********************************************************************************************************************/
                                    // update function of leaddetails create_flag=0 and noofleads=1
                     /********************************************************************************************************************/
                      /*start of inserting into dailyactivitydtl*/
                        if ($val['potentialqty'] == "" || $val['potentialqty'] == 'undefined') {
                        $val['potentialqty'] = 0;
                        }
                        if ($val['actualpotenqty'] == "" || $val['actualpotenqty'] == 'undefined') {
                        $val['actualpotenqty'] = 0;
                        }
                        if ($val['itemgroup'] == "" || $val['itemgroup'] == 'undefined') {
                        $val['itemgroup'] = 0;
                        }
                        if ($val['Remarks'] == "" || $val['Remarks'] == 'undefined') {
                        $val['Remarks'] = 0;
                        }
                        if ($val['quantity'] == "" || $val['quantity'] == 'undefined') {
                        $val['quantity'] = 0;
                        }
                        if($val['create_lead']==0)
                        {
                             if ($val['leadid'] == "" || $val['leadid'] == 'undefined' || $val['leadid']=='No Leads') {
                                $val['leadid'] = 0;
                                }
                        }
                        else
                        {
                            $val['leadid'] =$lead_id;
                        }
                       

                        $daily_dtl[$key]['id'] = $daily_hdr_id;
                        $daily_dtl[$key]['itemgroup'] = $val['itemgroup'];
                        $daily_dtl[$key]['custgroup'] = $val['custgroup'];
                        $daily_dtl[$key]['leadid'] = $val['leadid'];
                        $daily_dtl[$key]['potentialqty'] = $val['potentialqty'];
                        $daily_dtl[$key]['actualpotenqty'] = $val['actualpotenqty'];
                        $daily_dtl[$key]['subactivity'] = $val['subactivity'];
                        $daily_dtl[$key]['hour_s'] = $val['hour_s'];
                        $daily_dtl[$key]['minit'] = $val['minit'];
                        $daily_dtl[$key]['modeofcontact'] = $val['modeofcontact'];
                        $daily_dtl[$key]['quantity'] = $val['quantity'];
                        $daily_dtl[$key]['division'] = $val['division'];

                        $daily_dtl[$key]['remarks'] = $val['Remarks'];
                        $daily_dtl[$key]['creationdate'] = date('Y-m-d:H:i:s');
                        $daily_dtl[$key]['creationuser'] = $creationuser;
                       
                      /*end of inserting into dailyactivitydtl*/

                } // end of for loop $grid_data
                 if ($daily_hdr_insert_status) {
                        // echo"dailyactivitydtl";print_r($daily_dtl);
                            $daily_dlt_id = $this->dailyactivity_model->save_daily_details($daily_dtl);
                        }

                
                //echo"leaddetails<pre>";print_r(@$leaddetails);echo"</pre>";
                //echo"lead_status_mailalert "; print_r($lead_status_mailalert);echo"</pre>";
                //echo"leadproducts<pre>";print_r(@$leadproducts);echo"</pre>";
                //echo"lead_prod_poten_type<pre>";print_r(@$lead_prod_poten_type);echo"</pre>";
                //echo"lead_log_details<pre>";print_r(@$lead_log_details);echo"</pre>";
                //echo"lead_sublog_details_update<pre>";print_r(@$lead_sublog_details_update);echo"</pre>";

                

               
            }  // end of $_POST=save is true
            
            $message = "true";
        } else {
            
            $message = "Entry already exists for the date " . $hrd_currentdate;

        }
        header('Content-Type: application/x-json; charset=utf-8');
        echo $message;


    }

    function updateitemmaster() {
        	
        $createddate = date('Y-m-d:H:i:s');
        $lastupdatedate = date('Y-m-d:H:i:s');
        $creationuser = $this->session->userdata['identity'];
        $lastupdateuser = $this->session->userdata['identity'];
        $execode = $this->session->userdata['empcode'];
        $exename = $this->session->userdata['identity'];
        $user1 = $this->session->userdata['loginname'];
        $login_username = $this->session->userdata['username'];
        $user_id = $this->session->userdata['user_id'];
        $login_user_id = $this->session->userdata['user_id'];
        $hrd_currentdate = $_POST[0]['currentdate'];
        $hdn_hdr_id = $_POST[0]['hdn_hdr_id'];
        $assign_to_array = $this->Leads_model->GetAssigntoName($login_user_id);
        $lead_assign_name = $assign_to_array['0']['location_user'] . "-" . $assign_to_array['0']['aliasloginname'];
        //echo"<pre> post array";print_r($_POST);echo"</pre>";
        $grid_data = array_slice($_POST, 1, null, true);
        //echo"<pre>";print_r($grid_data);echo"</pre>"; 
        $get_update_id = $hdn_hdr_id;

            if ($_POST['update'] == 'true') {

                $daily_hdr_id = $get_update_id;
                $daily_hdr = array('id' => $daily_hdr_id,
                    'currentdate' => $hrd_currentdate,
                    'execode' => $execode,
                    'exename' => $exename,
                    'companycode' => 'PPC',
                    'accperiod' => '2015-2016',
                    'user1' => $user1,
                    'lastupdateuser' => $lastupdateuser,
                    'lastupdatedate' => $lastupdatedate
                );
                $daily_hdr_update_status = $this->dailyactivity_model->update_dailyactivityhdr($daily_hdr, $daily_hdr_id);
                
                foreach ($grid_data as $key => $val) 
                {      /*Start of loop of grid data*/
                         $lead_no = 'LEAD-DCV';
                         $sales_type_flag="R";
                         $lead_status_name = $val['statusid'];
                         $lead_sub_status_name =$val['leadsubstatusid'];
                         $lead_status_id = $this->dailyactivity_model->get_leadstatusidbyname($val['statusid']);
                         $lead_substatus_id = $this->dailyactivity_model->get_leadsub_statusidbyname($val['leadsubstatusid']);
                         $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname($val['division']);
                        if($val['itemgroup']=="")
                        {
                            $itemgroup_name = $this->Leads_model->GetItemgroup($val['hdn_prod_id']);
                            if ($itemgroup_name['itemgroup'] != "") {
                                $itemgroup_name = $itemgroup_name['itemgroup'];
                            } else {
                                $itemgroup_name = $itemgroup_name['description'];
                            }
                        }
                        else
                        {
                          $itemgroup_name = $val['itemgroup'];   
                        }
                        
                         $lead_close_status=0;
                         $lead_close_option="2";
                         $ld_converted=0;
                         $crm_first_soc_no = 0;
                         $closing_comments="test for closing comments";
                         $sample_rejected_reason=$val['sample_rejected_reason'];
                         $order_cancelled_reason=$val['order_cancelled_reason'];
                         
                         
                         $prev_status_id =$val['prevstatusid'];
                         $prev_substatus_id =$val['prevsubstatusid'];
                         $samle_reject_count=0;
                         $user_branch = $this->dailyactivity_model->get_user_branch($login_user_id);

                         if($val['crm_soc_number']=='undefined' || $val['crm_soc_number']=="")
                         {
                            $crm_first_soc_no=0;
                            $ld_converted = 0;
                         }
                         else
                         {
                            
                            $crm_first_soc_no =$val['crm_soc_number'];
                            $ld_converted = 1;
                         }

                        if ($val['line_id']==0 && $val['create_lead']==1)
                        {/* code for creating lead start*/
                             if($val['crm_soc_number']=='undefined' || $val['crm_soc_number']=="")
                             {
                                $val['crm_soc_number']=0;
                                $ld_converted = 0;
                             }
                             else
                             {
                                $ld_converted = 1;
                             }
                            
                         if($val['lead_appointmentdt']=='null' or $val['lead_appointmentdt']=='undefined' ||  $val['lead_appointmentdt']=="" || $val['lead_appointmentdt']=="--")
                            {
                                
                              $appiontment_date=NULL;
                                       
                            }
                            else
                            {
                                $appiontment_date = $val['lead_appointmentdt'];
                            }
                       //  echo "appiontment_date ".$appiontment_date."<br>";
                         $customer_id=$val['hdn_cust_id'];
                         $customer_address[] = $this->dailyactivity_model->get_customer_address($customer_id);

                            $leaddetails = array('lead_no' => $lead_no,
                            'leadstatus' => $lead_status_id,
                            'company' => $customer_id,
                            'customer_id' => $customer_id,
                            'assignleadchk' => $user_id,
                            'leadsource' => 13,
                            'ldsubstatus' => $lead_substatus_id,
                            'createddate' => date('Y-m-d:H:i:s'),
                            'last_modified' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id,
                            'email_id' => trim($customer_address[0]['contact_mailid']),
                            'firstname' => trim($customer_address[0]['contact_person']),
                            'industry_id' => 63,
                            'uploaded_date' => $hrd_currentdate,
                            'crd_id' => 8,
                            'lead_crm_soc_no' => $crm_first_soc_no,
                            'converted' => $ld_converted,
                            'crd_assesment' =>'Update Later',
                            'assignleadchk' => $login_user_id,
                            'user_branch' => $user_branch,
                            'description' => "added from dailyactivity",
                            'comments' => "comments added from dailyactivity",
                            'sales_type_flag' => $sales_type_flag,
                            'createddate' => date('Y-m-d:H:i:s'),
                            'last_modified' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                            
                        );
                        $lead_id = $this->Leads_model->save_lead($leaddetails);
                        $val['leadid']=$lead_id;
                          /* Start for inserting into leadproducts*/
                           if ($val['quantity'] == "" || $val['quantity'] == 'undefined') 
                              {
                                $val['quantity']=0;
                              }
                           $leadproducts = array('leadid' => $lead_id,
                            'productid' => $val['hdn_prod_id'],
                            'product_group' => $itemgroup_name,
                            'quantity' => $val['quantity'],
                            'created_date' => date('Y-m-d:H:i:s'),
                            'created_user' => $login_user_id
                        );
                        $prdetid = $this->Leads_model->save_lead_products_all($leadproducts);
                         /* End for inserting into leadproducts*/

                         /* Start for inserting into lead_prod_potential_types*/
                            $product_sale_type = $this->Leads_model->get_leadproduct_saletype();
                            $lpid_seq = $this->Leads_model->GetNextlpid($lead_id);
                            $lpid_seq = $lpid_seq;
 
                            for ($k = 0; $k < count($product_sale_type); $k++) 
                            {
                                $lead_prod_poten_type[$k]['leadid'] = $lead_id;
                                $lead_prod_poten_type[$k]['productid'] = $val['hdn_prod_id'];
                                $lead_prod_poten_type[$k]['product_type_id'] =$product_sale_type[$k]['n_value_id'];

                                 if ($product_sale_type[$k]['n_value_id'] == $sales_type_id) {
                                    $lead_prod_poten_type[$k]['potential'] = $val['actualpotenqty'];
                                } else {
                                    $lead_prod_poten_type[$k]['potential'] = 0;
                                }

                             $proddata[$k]['leadid'] = $lead_id;
                             $proddata[$k]['productid'] = $val['hdn_prod_id'];
                                    
                            }
                            //echo"<pre>lead_prod_poten_type ";print_r($lead_prod_poten_type);echo"</pre>";
                            $lead_pord_poten_id = $this->Leads_model->save_leadprodpotentypes($lead_prod_poten_type);

                            $k = 0;
                         /* End for inserting into lead_prod_potential_types*/
                         // Start of if condition for inserting in the mail alert table
                         if (($lead_status_id == 1) && ( $lead_substatus_id == 3 ||  $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 ) || ($lead_status_id == 2) && ($lead_substatus_id == 7 )) 
                            { 
                                if (@$appiontment_date != "") {/*4*/
                                
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'assignto_id' => $login_user_id,
                                        'appointment_due_date' => $appiontment_date,
                                        'not_able_to_get_appiontment' =>@$reason_no_appointment,
                                        'status_created_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                                        'status_action_type' => "Insert"
                                    );
                                } else if ($lead_substatus_id == 3) {
                                $today_date = date('Y-m-d:H:i:s');
                                if($appiontment_date==""){$appiontment_date=null;}
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($lead_substatus_id == 6) {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            } else if ($lead_substatus_id == 7) {
                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            }
                            
                        } // end of if condition for inserting in the mail alert table
                          /*Start for Sample Trails status*/

                        /*Start for Enquiry/offer*/
                             if (($lead_status_id == 5) && ( $lead_substatus_id == 26))
                             {

                                $today_date = date('Y-m-d:H:i:s');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => @$reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Insert"
                                );
                            
                            }
                        /*End for Enquiry/offer*/
                        
                        if (($lead_status_id == 4) && ( $lead_substatus_id == 16 ||
                                $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19  || $lead_substatus_id == 20 || $lead_substatus_id == 21 )) 
                            {
                             
                                $today_date = date('Y-m-d:H:i:s');
                                $mail_alert_date="timestamp '" . $today_date . "' +interval '0 hours'";
                                if ($lead_substatus_id == 18 || $lead_substatus_id == 19 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '48 hours'"; 
                                }
                                if ($lead_substatus_id == 20 )
                                {
                                    $mail_alert_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                                }
                                $lead_status_mailalert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "Insert"
                                );
                            }
                            if  ($lead_status_id == 4 &&  $lead_substatus_id == 21) 
                            {
                                $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => 4,
                                    'lead_substatus_id' => 16,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                );
                            }
                            else if ($lead_substatus_id == 20)
                            {
                               $update_leadstatus_mailalert_revert = array
                                (
                                    'leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => 5,
                                    'lead_substatus_id' => 24,
                                    'last_update_user_id' => $login_user_id,
                                    'assignto_id' => $login_user_id,
                                    'appointment_due_date' => @$appiontment_date,
                                    'not_able_to_get_appiontment' => $reason_no_appointment,
                                    'status_created_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => $mail_alert_date,
                                    'status_action_type' => "RevertBack"
                                ); 
                            }
                        /*END for Sample trails status*/

                          if ($lead_substatus_id == 3 || $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 || $lead_substatus_id == 7 || $lead_substatus_id == 16 || $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19 || $lead_substatus_id == 20 || $lead_substatus_id == 21 || $lead_substatus_id == 26 || $lead_substatus_id == 26) 
                            {
                                $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert($lead_status_mailalert);
                            }
                        if (($this->input->post('leadstatus') == 4 &&  $lead_substatus_id == 21 ) || ($lead_substatus_id == 20 ))
                            {
                                $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_revert($update_leadstatus_mailalert_revert);
                            }

                         /* Start of creating log and sublog details with revert back to previous status*/
                                $lead_log_details = array('lh_lead_id' => $lead_id,
                                            'lh_user_id' => $login_user_id,
                                            'lh_lead_curr_status' => $lead_status_name,
                                            'lh_lead_curr_statusid' => $lead_status_id,
                                            'lh_created_date' => date('Y-m-d:H:i:s'),
                                            'lh_created_user' => $login_user_id,
                                            /*'lh_comments' => 'added log comments from dailyactivity',*/
                                            'lh_comments' => $val['Remarks'],
                                            'action_type' => 'Insert',
                                            'created_user_name' => $login_username,
                                            'assignto_user_id ' => $login_user_id,
                                            'assignto_user_name' => $lead_assign_name
                                        );
                    
                                $logid = $this->Leads_model->create_leadlog($lead_log_details);

                                $lead_sublog_details = array(
                                            'lhsub_lh_id' => $logid,
                                            'lhsub_lh_lead_id' => $lead_id,
                                            'lhsub_lh_user_id' => $login_user_id,
                                            'lhsub_lh_lead_curr_status' => $lead_status_name,
                                            'lhsub_lh_lead_curr_statusid' => $lead_status_id,
                                            'lhsub_lh_lead_curr_sub_status' => $lead_sub_status_name,
                                            'lhsub_lh_lead_curr_sub_statusid' => $lead_substatus_id,
                                            'lhsub_lh_comments' => "substatus log comments for lead",
                                            'lhsub_lh_created_date' => date('Y-m-d:H:i:s'),
                                            'lhsub_lh_created_user' => $login_user_id,
                                            'lhsub_action_type' => 'Insert',
                                            'lhsub_created_user_name' => $login_username,
                                            'lhsub_assignto_user_id ' => $login_user_id,
                                            'lhsub_assignto_user_name' => $lead_assign_name
                                        );


                                $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
                    /* end log details */

                        // Sample and Trails log and sublog entry for revert back - Start 
                        
                        if ($lead_substatus_id == 20 || $lead_substatus_id == 21) 
                        {   $samle_reject_count=0;
                            $lead_status_update='Y';
                            // Start update leaddetails 
                            $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count));
                            $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                            $leaddetails_update = array(
                                'leadstatus' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'ldsubstatus' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
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
                                'lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lh_last_updated_user' => $login_user_id,
                                'lh_comments' => "log comments from dailyactivity revertback",
                                'action_type' => 'RevertBack',
                                'modified_user_name' => $login_username,
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
                                'lhsub_lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                'lhsub_lh_last_updated_user' => $login_user_id,
                                'lhsub_lh_comments' => "substatus log comments for revertback",
                                'lhsub_action_type' => "RevertBack",
                                'lhsub_modified_user_name' => $login_username,
                                'lhsub_assignto_user_name' => $lead_assign_name,
                                'lhsub_status_update' => $lead_status_update
                            );
                            $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);

                            

                        }
                         // Sample and Trails log and sublog entry for revert back - END 

                            $temp_itemmaster_id = $this->Leads_model->update_tempitemmaster_leadid($lead_id, $proddata, $login_user_id);

                           
                        }/* code for creating lead end*/
                        else if ($val['leadid']== "" || $val['leadid'] == 'undefined' || $val['leadid']=='No Leads' || $val['leadid'] == 0)  
                        /* code for not creating lead Start*/
                        {
                           // echo"No lead updates is to be done<br>";
                        } 
                        else
                        {
                           //echo "lead update is to be done for ".$val['leadid'];
                           //echo"lead_status_id ".$lead_status_id."<br>";
                           //echo"lead_substatus_id ".$lead_substatus_id."<br>";
                             if($val['crm_soc_number']=='undefined' || $val['crm_soc_number']=="")
                             {
                                $crm_first_soc_no=0;
                                $ld_converted = 0;
                             }
                             else
                             {
                                
                                $crm_first_soc_no =$val['crm_soc_number'];
                                $ld_converted = 1;
                             }
                             if($val['not_able_to_get_appointment']=='null' or $val['not_able_to_get_appointment']=='undefined')
                             {
                                $reason_no_appointment=NULL;
                             }
                             else
                             {
                                $reason_no_appointment=$val['not_able_to_get_appointment'];
                             }
                            // echo"reason_no_appointment ".$reason_no_appointment."<br>";
                           $lead_id = $val['leadid'];

                            if (($lead_status_id != $prev_status_id) || ($lead_substatus_id != $prev_substatus_id)) {
                                $update_log = 1;
                            } else {
                                $update_log = 0;
                            }

                            if ($lead_status_id != $prev_status_id || ($lead_substatus_id != $prev_substatus_id )) {
                                $lead_status_update = 'Y';
                            } else {
                                $lead_status_update = 'N';
                            }
                            if ($lead_status_id==8)
                            {
                               $lead_close_status=1;
                               $lead_close_option =$lead_sub_status_name;
                            }
                            else
                            {
                              $lead_close_status=0; 
                              $lead_close_option=""; 
                            }

                                $leaddetails = array(
                                'leadstatus' => $lead_status_id,
                                'ldsubstatus' => $lead_substatus_id,
                                'comments' => " update from dailyactivity ",
                                'email_id' => $this->input->post('email_id'),
                                'lead_close_status' => $lead_close_status,
                                'lead_close_option' => $lead_close_option,
                                'lead_close_comments' => $closing_comments,
                                'lead_crm_soc_no' => $crm_first_soc_no,
                                'converted' => $ld_converted,
                                'nextstepdate' => date('Y-m-d'),
                                'last_modified' => date('Y-m-d:H:i:s'),
                                'last_updated_user' => $login_user_id
                            );
                                $leadproducts = array(
                                'quantity' => $val['quantity'],
                                'last_modified' => date('Y-m-d:H:i:s'),
                                'last_updated_user' => $login_user_id
                            );


                             $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname($val['division']);  
                                                    

                             $lead_prod_poten_type_update = array(
                                'potential' => $val['actualpotenqty']
                            );
                                
                            $id = $this->Leads_model->update_lead($leaddetails, $lead_id);
                            $id = $this->Leads_model->update_leadproducts($leadproducts, $lead_id);
                            $lead_pord_poten_id = $this->Leads_model->dcupdate_leadprodpotentypes($lead_prod_poten_type_update, $lead_id,$sales_type_id);

      


                             /* Start for Managing and implementation*/
                                $today_date = date('Y-m-d');
                                if ($lead_status_id == 6 ) 
                                    {
                                        $lead_status_mailalert = array('leadid' => $lead_id,
                                            'user_id' => $login_user_id,
                                            'branch' => $user_branch,
                                            'lead_status_id' => $lead_status_id,
                                            'lead_substatus_id' => $lead_substatus_id,
                                            'last_update_user_id' => $login_user_id,
                                            'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                            'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                            'status_action_type' => "Update"
                                        );
                                    }
                                if ($lead_status_id == 7 ) 
                                    {
                                        $lead_status_mailalert = array('leadid' => $lead_id,
                                            'user_id' => $login_user_id,
                                            'branch' => $user_branch,
                                            'lead_status_id' => $lead_status_id,
                                            'lead_substatus_id' => $lead_substatus_id,
                                            'last_update_user_id' => $login_user_id,
                                            'soc_no' => "1234",
                                            'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                            'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                            'status_action_type' => "Update"
                                        );
                                    }                        

                    /* End for Managing and implementation*/

                                 if ($lead_substatus_id == 6) {
                                    $today_date = date('Y-m-d');
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'not_able_to_get_appiontment' => @$reason_no_appointment,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "Update"
                                    );
                                } else {
                                    if ((@$appiontment_date != "") && ($lead_substatus_id == 4)) {    
                                        $lead_status_mailalert = array('leadid' => $lead_id,
                                            'user_id' => $login_user_id,
                                            'branch' => $user_branch,
                                            'lead_status_id' => $lead_status_id,
                                            'lead_substatus_id' => $lead_substatus_id,
                                            'last_update_user_id' => $login_user_id,
                                            'appointment_due_date' => $appiontment_date,
                                            'not_able_to_get_appiontment' => @$reason_no_appointment,
                                            'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                            'mail_alert_date' => "timestamp '" . $appiontment_date . "' -interval '24 hours'",
                                            'status_action_type' => "Update"
                                        );
                                    } else if ($lead_substatus_id == 3) {
                                        $today_date = date('Y-m-d');
                                        $lead_status_mailalert = array('leadid' => $lead_id,
                                            'user_id' => $login_user_id,
                                            'branch' => $user_branch,
                                            'lead_status_id' => $lead_status_id,
                                            'lead_substatus_id' => $lead_substatus_id,
                                            'last_update_user_id' => $login_user_id,
                                            'appointment_due_date' => @$appiontment_date,
                                            'not_able_to_get_appiontment' => @$reason_no_appointment,
                                            'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                            'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                            'status_action_type' => "Update"
                                        );
                                    }
                                }
                                if ($lead_substatus_id == 7) {
                                    $today_date = date('Y-m-d');
                                    $lead_status_mailalert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $lead_status_id,
                                        'lead_substatus_id' => $lead_substatus_id,
                                        'last_update_user_id' => $login_user_id,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                        'status_action_type' => "Update"
                                    );
                                }
                                if ($lead_substatus_id == 21) 
                                {
                                    $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($lead_id,$lead_substatus_id);
                                } 
                                if ($lead_substatus_id == 15) 
                                {
                                    $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                                    $leaddetails_close = array(
                                    'lead_close_status' => 1,
                                    'lead_close_option' => $sublog_lead_substatus_name,
                                    'lead_close_comments' => "RevertBack",
                                    'last_modified' => date('Y-m-d:H:i:s'),
                                    'last_updated_user' => $login_user_id
                                    );


                                    $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                    //  print_r($leaddetails_close);
                                }
                                /*Start for Sample Trails status*/
                                if ( $lead_substatus_id == 16 ||  $lead_substatus_id == 17 || $lead_substatus_id == 18 || $lead_substatus_id == 19 || $lead_substatus_id == 20 || $lead_substatus_id == 21 || $lead_substatus_id == 27) 
                                    {
                    
                                           if ($lead_substatus_id == 21) 
                                            {
                                               
                                                if ($samle_reject_count>1)
                                                {
                                                    $today_date = date('Y-m-d:H:i:s');
                                                         $lead_status_mailalert = array('leadid' =>$lead_id,
                                                        'user_id' => $login_user_id,
                                                        'branch' => $user_branch,
                                                        'lead_status_id' => 8,
                                                        'lead_substatus_id' => 33,
                                                        'last_update_user_id' => $login_user_id,
                                                        'sample_reject_reason' => $sample_rejected_reason,
                                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                        'status_action_type' => "Sample Rejected"
                                                    );

                                                        $sublog_lead_substatus_name = 
                                                        $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                                                        $leaddetails_close = array(
                                                        'lead_close_status' => 1,
                                                        'lead_close_option' => $sublog_lead_substatus_name,
                                                        'lead_close_comments' => "RevertBack",
                                                        'last_modified' => date('Y-m-d:H:i:s'),
                                                        'last_updated_user' => $login_user_id
                                                        );

                                                        $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                                }
                                                else 
                                                {
                                                    $today_date = date('Y-m-d:H:i:s');
                                                         $lead_status_mailalert = array('leadid' =>$lead_id,
                                                        'user_id' => $login_user_id,
                                                        'branch' => $user_branch,
                                                        'lead_status_id' => $lead_status_id,
                                                        'lead_substatus_id' => $lead_substatus_id,
                                                        'last_update_user_id' => $login_user_id,
                                                        'sample_reject_reason' => $sample_rejected_reason,
                                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                        'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                        'status_action_type' => "RevertBack"
                                                    );
                                                }
                                                
                                            }
                                            else if ($lead_substatus_id == 20) 
                                            {
                                                $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '24 hours'",
                                                    'status_action_type' => "Update"
                                                );
                                            }
                                            // Order Cancelled start
                                            else if ($lead_substatus_id == 27) 
                                            {
                                                $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'order_cancelled_reason' => $order_cancelled_reason,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                    'status_action_type' => "Update"
                                                );
                                            }
                                            // Order Cancelled end 
                                            else if ($lead_substatus_id == 18 || $lead_substatus_id == 19) 
                                            {
                                               $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '48 hours'",
                                                    'status_action_type' => "Update"
                                                ); 
                                            }
                                             else if ($lead_substatus_id == 16 ) 
                                            {
                                               $today_date = date('Y-m-d:H:i:s');
                                                $lead_status_mailalert = array('leadid' =>$lead_id,
                                                    'user_id' => $login_user_id,
                                                    'branch' => $user_branch,
                                                    'lead_status_id' => $lead_status_id,
                                                    'lead_substatus_id' => $lead_substatus_id,
                                                    'last_update_user_id' => $login_user_id,
                                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                                    'status_action_type' => "Update"
                                                ); 
                                            }
                      
                                }
                                /*END for Sample trails status*/
                                /*Start for Enquiry/offer*/
                                if ($lead_substatus_id == 26) {
                                $today_date = date('Y-m-d');
                                $lead_status_mailalert = array('leadid' => $lead_id,
                                    'user_id' => $login_user_id,
                                    'branch' => $user_branch,
                                    'lead_status_id' => $lead_status_id,
                                    'lead_substatus_id' => $lead_substatus_id,
                                    'last_update_user_id' => $login_user_id,
                                    'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                    'mail_alert_date' => "timestamp '" . $today_date . "' +interval '0 hours'",
                                    'status_action_type' => "Update"
                                    );
                                }
                                /*End for Enquiry/offer*/
                                if ($lead_substatus_id == 3 || $lead_substatus_id == 4 || $lead_substatus_id == 5 || $lead_substatus_id == 6 || $lead_substatus_id == 7 || $lead_substatus_id == 16 || $lead_substatus_id == 17|| $lead_substatus_id == 18|| $lead_substatus_id == 19|| $lead_substatus_id == 20|| $lead_substatus_id == 21 || $lead_substatus_id == 26 || $lead_substatus_id == 27 || $lead_substatus_id == 28 || $lead_substatus_id == 29) 
                                {
                                    $mail_alert_id = $this->Leads_model->insert_leadstatus_mailalert_update(@$lead_status_mailalert);
                                }
                                $log_lead_status_name = $this->Leads_model->GetLeadStatusName($lead_status_id);
                                $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($lead_substatus_id);
                                $lead_log_details = array('lh_lead_id' => $lead_id,
                                    'lh_user_id' => $login_user_id,
                                    'lh_lead_curr_status' => $log_lead_status_name,
                                    'lh_lead_curr_statusid' => $lead_status_id,
                                    'lh_updated_date' => date('Y-m-d:H:i:s'),
                                    'lh_last_updated_user' => $login_user_id,
                                    /*'lh_comments' => "log comments from dailyactivity",*/
                                    'lh_comments' => $val['Remarks'],
                                    'action_type' => 'Update',
                                    'modified_user_name' => $login_username,
                                    'assignto_user_name' => $lead_assign_name,
                                    'status_update' => $lead_status_update
                                );
                                if ($update_log == 1) {
                                    @$logid = $this->Leads_model->create_leadlog($lead_log_details);
                                }
                               // $samle_reject_count = $this->Leads_model->get_lead_sample_rejectcnt($leadid,$lead_substatus_id);
                               
                                $lead_sublog_details = array(
                                    'lhsub_lh_id' => @$logid,
                                    'lhsub_lh_lead_id' => $lead_id,
                                    'lhsub_lh_user_id' => $login_user_id,
                                    'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                                    'lhsub_lh_lead_curr_statusid' => $lead_status_id,
                                    'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                    'lhsub_lh_lead_curr_sub_statusid' => $lead_substatus_id,
                                    'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                    'lhsub_lh_last_updated_user' => $login_user_id,
                                    'lhsub_lh_comments' => "sublog comments from dailyactivity",
                                    'lhsub_action_type' => 'Update',
                                    'lhsub_modified_user_name' => $login_username,
                                    'lhsub_assignto_user_name' => $lead_assign_name,
                                    'lhsub_status_update' => $lead_status_update
                                );

                                if ($update_log == 1) {
                                    $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details);
                                }
                                $update_date = $this->Leads_model->update_prev_moddate(@$logid);

                                if ($lead_substatus_id == 5 || $lead_substatus_id == 15 || $lead_substatus_id == 20 || $lead_substatus_id == 21 || $lead_substatus_id == 27) 
                                {
                                    // update leadsubstatus in leaddetails_update
                                    /* Start update leaddetails */
                                    $log_lead_status_name = $this->Leads_model->GetLeadStatusName($this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count));
                                    $sublog_lead_substatus_name = $this->Leads_model->GetLeadSubStatusName($this->revert_substatus($lead_substatus_id,$samle_reject_count));
                                    $leaddetails_update = array(
                                        'leadstatus' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                        'ldsubstatus' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                        'lead_crm_soc_no' => $crm_first_soc_no,
                                        'converted' => $ld_converted,
                                        'comments' => "updated from dailyactivity leaddetails_update ",
                                        'last_modified' => date('Y-m-d:H:i:s'),
                                        'last_updated_user' => $login_user_id
                                    );
                                    
                                    $prodid = $this->Leads_model->update_lead($leaddetails_update, $lead_id);

                                    /* Endt update leaddetails_update */

                                    // insert a record in lead log details
                                    /* Start lead log details revert back */
                                    $lead_log_details_update = array('lh_lead_id' => $lead_id,
                                        'lh_user_id' => $login_user_id,
                                        'lh_lead_curr_status' => $log_lead_status_name,
                                        'lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                        'lh_updated_date' => date('Y-m-d:H:i:s'),
                                        'lh_last_updated_user' => $login_user_id,
                                        'lh_comments' => "log updated from dailyactivity lead_log_details_update ",
                                        'action_type' => 'RevertBack',
                                        'modified_user_name' => $login_username,
                                        'assignto_user_name' => $lead_assign_name,
                                        'status_update' => $lead_status_update
                                    );

                                    $logid = $this->Leads_model->create_leadlog($lead_log_details_update);
                                    /* End lead log details revert back */

                                    // insert a record in lead sub log details
                                    /* Start revert back sub log details */
                                    $lead_sublog_details_update = array(
                                        'lhsub_lh_id' => @$logid,
                                        'lhsub_lh_lead_id' => $lead_id,
                                        'lhsub_lh_user_id' => $login_user_id,
                                        'lhsub_lh_lead_curr_status' => $log_lead_status_name,
                                        'lhsub_lh_lead_curr_statusid' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                        'lhsub_lh_lead_curr_sub_status' => $sublog_lead_substatus_name,
                                        'lhsub_lh_lead_curr_sub_statusid' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                        'lhsub_lh_updated_date' => date('Y-m-d:H:i:s'),
                                        'lhsub_lh_last_updated_user' => $login_user_id,
                                        'lhsub_lh_comments' => "updated for substatus log lead_sublog_details_update",
                                        'lhsub_action_type' => "RevertBack",
                                        'lhsub_modified_user_name' => $login_username,
                                        'lhsub_assignto_user_name' => $lead_assign_name,
                                        'lhsub_status_update' => $lead_status_update
                                    );
                                    $sublogid = $this->Leads_model->create_lead_sublog($lead_sublog_details_update);

                                    $today_date = date('Y-m-d');
                                    if($lead_substatus_id == 5)
                                    {
                                        $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '48 hours'";    
                                    }
                                    else if($lead_substatus_id == 20)
                                    {
                                        $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '24 hours'"; 
                                    }
                                    else if($lead_substatus_id == 21)
                                    {
                                        $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'";
                                    } 
                                    else 
                                    {
                                        $mail_alert_rev_date="timestamp '" . $today_date . "' +interval '0 hours'"; 
                                    }
                                    $update_leadstatus_mailalert_revert = array('leadid' => $lead_id,
                                        'user_id' => $login_user_id,
                                        'branch' => $user_branch,
                                        'lead_status_id' => $this->revert_status($lead_status_id, $lead_substatus_id,$samle_reject_count),
                                        'lead_substatus_id' => $this->revert_substatus($lead_substatus_id,$samle_reject_count),
                                        'last_update_user_id' => $login_user_id,
                                        'substatus_updated_date' => date('Y-m-d:H:i:s'),
                                        'mail_alert_date' => $mail_alert_rev_date,
                                        'status_action_type' => "RevertBack"
                                    );
                                    //echo"samle_reject_count ".$samle_reject_count."<br>";
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
                                      $id = $this->Leads_model->update_leadclosestatus($leaddetails_close, $lead_id);
                                     }
                                    /* End revert back sub log details  */
                                }


                        }/* code for not creating lead End*/




                    if ($val['potentialqty'] == "" || $val['potentialqty'] == 'undefined') {
                        $val['potentialqty'] = 0;
                    }

                    if ($val['itemgroup'] == "" || $val['itemgroup'] == 'undefined') {
                        $val['itemgroup'] = 0;
                    }
                    if ($val['Remarks'] == "" || $val['Remarks'] == 'undefined') {
                        $val['Remarks'] = 0;
                    }
                    if ($val['quantity'] == "" || $val['quantity'] == 'undefined') {
                        $val['quantity'] = 0;
                    }
                     if ($val['actualpotenqty'] == "" || $val['actualpotenqty'] == 'undefined' || $val['actualpotenqty'] == "null") {
                        $val['actualpotenqty'] = 0;
                    }
                      if ($val['leadid'] == "" || $val['leadid'] == 'undefined' || $val['leadid']=='No Leads') {
                        $val['leadid'] = 0;
                    }
                    $daily_dtl[$key]['id'] = $daily_hdr_id;
                    $daily_dtl[$key]['itemgroup'] = $val['itemgroup'];
                    $daily_dtl[$key]['leadid'] = $val['leadid'];
                    $daily_dtl[$key]['custgroup'] = $val['custgroup'];
                    $daily_dtl[$key]['potentialqty'] = $val['potentialqty'];
                    $daily_dtl[$key]['actualpotenqty'] = $val['actualpotenqty'];
                    $daily_dtl[$key]['subactivity'] = $val['subactivity'];
                    $daily_dtl[$key]['hour_s'] = $val['hour_s'];
                    $daily_dtl[$key]['minit'] = $val['minit'];
                    $daily_dtl[$key]['modeofcontact'] = $val['modeofcontact'];
                    $daily_dtl[$key]['quantity'] = $val['quantity'];
                    $daily_dtl[$key]['division'] = $val['division'];
                    $daily_dtl[$key]['remarks'] = $val['Remarks'];
                    $daily_dtl[$key]['lastupdatedate'] = date('Y-m-d:H:i:s');
                    $daily_dtl[$key]['lastupdateuser'] = $creationuser;

                   

                } /*End of loop of grid data*/


                if ($daily_hdr_update_status) {

                    $daily_dlt_id = $this->dailyactivity_model->save_daily_details_up($daily_dtl,$get_update_id);
                }
                if($daily_dlt_id)
                {
                    $message = "true";
                }
                else
                {
                    $message = "Error in Updating Records"; 
                    $this->db->db_debug = TRUE; 
                }
            }
            
        header('Content-Type: application/x-json; charset=utf-8');
        echo $message;
    }

    function getnullleadids()
    {
        $data = array();
        $data = $this->dailyactivity_model->getnull_leadids();
        print_r($data);
    }

    function getleadids($customergroup,$prodgroup)
    {

        $customergroup=html_entity_decode($customergroup);
        $prodgroup=html_entity_decode($prodgroup);
        $data = array();
        $data = $this->dailyactivity_model->get_leadids($customergroup,$prodgroup);
        print_r($data);
    }
    
    function getactivity($leaid)
    {
        $data = array();
        $data = $this->dailyactivity_model->get_activity($leaid);
        print_r($data);
    }

     function getcollectors()
    {
       // $data['optionscollector'] = $this->Leads_model->get_collectors($this->session->userdata['get_assign_to_user_id']);
        $data = array();
        $data = $this->dailyactivity_model->get_collectors($this->session->userdata['get_assign_to_user_id']);
        print_r($data);
    }
  
    function getmarketcircles()
    {
        
        $data = array();
        $data = $this->dailyactivity_model->get_marketcircles($this->session->userdata['get_assign_to_user_id']);
        print_r($data);
    }
    
     function getcollectormc($collector) {
        $this->dailyactivity_model->brach_sel = urldecode($collector);
        $market_circles = $this->dailyactivity_model->get_collector_mc($collector);
        header('Content-Type: application/x-json; charset=utf-8');
        echo $market_circles;
    }

    function checkentrydate($hrd_currentdate){
     $user1 = $this->session->userdata['loginname'];
     $exename = $this->session->userdata['identity'];
     $check_duplicates = $this->dailyactivity_model->check_dailyhdr_duplicates($hrd_currentdate, $user1);
        //  echo $check_duplicates; die;
        if ($check_duplicates == 0)
        {
                $message = "true";   
        }
        else{
            
             // echo "duplicate exists";    
            $message = "Entry already exists for the date " . $hrd_currentdate;
        }
        header('Content-Type: application/x-json; charset=utf-8');
        echo $message;
      }  

      function lms_dailyactivtiy()
      {
        $this->load->view('dailyactivity/lms_dailyactivity_merge');
      }
      function tutortial1()
      {
        $this->load->view('dailyactivity/tutorial1');
      }

       function leadreassign()
      {
        $this->load->view('dailyactivity/tutorial2');
      }

      function newfeatures()
      {
         $this->load->view('dailyactivity/newfeatures');
      }
      function findyourleads()
      {
         $this->load->view('dailyactivity/findyourleads');
      }
/* functions added for merging start*/
    function getldstatus()
    {
        $data = array();
        $data = $this->dailyactivity_model->get_ldstatus();
        print_r($data);
    }

    function getldsubstatus()
    {

        $data = array();
        $data = $this->dailyactivity_model->get_ldsubstatus();
        print_r($data);
    }

    function getldsubstatusbyname($statusname)
    {
        $lead_status_id = $this->dailyactivity_model->GetLeadStatusid($statusname); 
        $data = array();
        $data = $this->dailyactivity_model->get_ldsubstatus_byid($lead_status_id);
        print_r($data);
    }

    function getldsubstatusbynameid($statusname,$leadid)
    {

        $lead_status_id = $this->dailyactivity_model->GetLeadStatusid($statusname);
        $data = array();
        $data = $this->dailyactivity_model->get_ldsubstatus_byleadid($lead_status_id,$leadid);
        print_r($data);
    }
    function getldsubstatusbynameid_update($substatusname,$leadid)
    {
        $lead_status_id = $this->dailyactivity_model->GetLeadStatusid_update($substatusname);
        $data = array();
        $data = $this->dailyactivity_model->get_ldsubstatus_byleadid_update($lead_status_id,$leadid);
        print_r($data);
    }

    function getldsubstatusforlead($leadid)
    {

        $lead_status_id = $this->dailyactivity_model->get_ldsubstatusforlead($leadid);
        $subts_id=$lead_status_id['substatusid'];
        $status_id=$lead_status_id['status_id'];
        $order_id=$lead_status_id['order_id'];
        $data = array();
        $data = $this->dailyactivity_model->get_ldsubstatus_for_lead($subts_id,$status_id,$order_id);
        print_r($data);
    }
   
   

    function getldstatusbyid($leadid) {

        $activitydata['lead_status'] = $this->dailyactivity_model->get_ldstatusbyid($leadid);
        $viewdata = $activitydata['lead_status'];
        echo $viewdata;
    }
    function getldstatusfor($leadid) {

        $activitydata['lead_status'] = $this->dailyactivity_model->get_ldstatusfor($leadid);
        $viewdata = $activitydata['lead_status'];
        echo $viewdata;
    }
    function getldstatusname($leadid) {

        $activitydata['lead_status'] = $this->dailyactivity_model->get_ldstatusname($leadid);
        $viewdata = $activitydata['lead_status'];
        echo $viewdata;
    }

    function checkduplicate_product($prodgrp, $customergroup) {

           // $leaddata1['response'] = $this->dailyactivity_model->check_prodgroup_dup_saleorder($prodgrp, $customergroup);
             $leaddata1['response'] = $this->dailyactivity_model->fncheck_prodgroup_dup_saleorder($prodgrp, $customergroup);
            if ($customergroup=='undefined')
            {

                    $response = array(
                   'ok' => false,
                    'msg' => "<font color=red>No Product has been selected,hence lead will not be created</font>");
               
            }
            else
            {
               if($leaddata1['response']=='false')
                {
                    $response = array(
                   'ok' => false,
                    'msg' => "<font color=red>This product group has been already billed for this customer or lead already exists</font>");
                }
                else
                {
                   $response = array(
                   'ok' => true,
                    'msg' => "<font color=green> A lead will be created </font>");  
                } 
            }

            
        echo json_encode($response);

    }
    function checkduplicate_product_update($prodgrp, $customergroup) {

            $leaddata1['response'] = $this->dailyactivity_model->check_prodgroup_dup_saleorder($prodgrp, $customergroup);
            if ($customergroup=='undefined')
            {

                    $response = array(
                   'ok' => false,
                    'msg' => "<font color=red>No Product has been selected,hence lead will not be created</font>");
               
            }
            else
            {
               if($leaddata1['response']=='false')
                {
                    $response = array(
                   'ok' => false,
                    'msg' => "<font color=red>This product group has been already billed for this customer</font>");
                }
                else
                {
                   $response = array(
                   'ok' => true,
                    'msg' => "<font color=green>&nbsp;&nbsp;No Leads </font>");  
                } 
            }

            
        echo json_encode($response);

    }

    function getleadsalestype()
    {
        $data = array();
        $data = $this->dailyactivity_model->get_leadsalestype();
        print_r($data);
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

    function getsynchedproducts($custgrp,$prodgrp) {
        $custgrp = urldecode($custgrp);
        $prodgrp = urldecode($prodgrp);
        /* $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup,id  FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/
     /*   $sql = "SELECT distinct  replace(customergroup,'''','')   as customergroup FROM customermasterhdr WHERE 
        collector ='".$collector."' or collector is NULL order by customergroup";*/
        
        $sql = "SELECT * FROM vw_lead_get_soc_no_product_custgroup WHERE customergroup ='".$custgrp."' AND product = '".$prodgrp."'";
       //echo $sql; die;
        
        $activitydata['datacustomermaster'] = $this->dailyactivity_model->get_synched_products($sql);
        $viewdata = $activitydata['datacustomermaster'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

    function save_newproduct() {
       // print_r($_POST);
/*                $data['body'] = "Product Added Sucessfully";
                $this->load->view('dailyactivity/sucessitem_view', $data);*/
/*                action save_newproduct 
                item_name   toluenenew*/
        //  
        if ($this->input->post('action')) {
           
            $product_currval = $this->dailyactivity_model->GetNextMaxVal('temp_item_id', 'tempitemmaster');
            //$company_currval= $this->Company_model->GetCurrVal('tempcustomermaster_id_seq');
            //  $company_currval= $this->Company_model->GetNextVal('tempcustomermaster_id_seq');
            $product_nextid = $product_currval + 1;
            //tempcustomer_id_seq
            //INSERT INTO tempcustomermaster (temp_cust_sync_id,temp_customername,creationdate)
            //VALUES    ('TEMP'||CURRVAL('tempcustomermaster_id_seq'),'Pures Chemicals','2013-09-27:09:20:35')
            $item_syn_id = "TEMP" . $product_nextid;
            //$user_id = $this->input->post('hdn_userid');
            $user_id = 676;
            $productdetails = array(
                'temp_item_sync_id' => $item_syn_id,
                'temp_itemname' => strtoupper($this->input->post('item_name')),
                'user_id' => 676,
                //'lead_id' => $leadid,         
                'creationdate' => date('Y-m-d h:i:s')
            );
            $id = $this->dailyactivity_model->save_tempitem($productdetails);
            // echo "the returned id is ".$id."<br>";
            if ($id != "") {
                $data['body'] = "Product Added Sucessfully";
                $this->load->view('product/sucessitem_view', $data);
            } else {
                $data['body'] = "Please Try Again";
                $this->load->view('product/retryitem_view', $data);
            }
            //redirect('leads');    
        }
    }

    function getldstatusupdate()
    {
        $data = array();
        $data = $this->dailyactivity_model->get_ldstatus_update();
        print_r($data);
    }
    
    function getcustomerpotential($customergroup) {
       
        
       // $sql = 'SELECT  min(id) as itemid, itemgroup FROM view_tempitemmaster_pg GROUP BY itemgroup ORDER BY itemgroup asc';
        $activitydata['productpotential'] = $this->dailyactivity_model->get_customerpotential($customergroup);
        $viewdata = $activitydata['productpotential'];
        header('Content-Type: application/x-json; charset=utf-8');
        echo $viewdata;
    }

     function updaterevised_potential($custgrp,$prodgrp,$old_poten,$src,$sal_cat_type,$new_poten)
     {

        $login_username = $this->session->userdata['username'];
        $login_user_id = $this->session->userdata['user_id'];

        $custgrp=urldecode($custgrp);
        $prodgrp=urldecode($prodgrp);
        $sal_cat_type=urldecode($sal_cat_type);
        $prev_poten =$old_poten; 
        $mailflag="No";

        $custgrp_id = $this->dailyactivity_model->get_customergroup_id($custgrp);
        $prodgrp_id = $this->dailyactivity_model->getproductgroup_id($prodgrp);
        $salecat_id = $this->dailyactivity_model->get_bpsalecatid($sal_cat_type);
        $dup_record = $this->dailyactivity_model->checkduplicate_record($custgrp,$prodgrp);
        //$salecat_id =$sal_cat_id['0']['header_id'];
        
        if($dup_record['0']['noofrows']>0)
        {
           //echo "update";
            $potential_id =$dup_record['0']['id'];
            $update_potential = array('dac_cust_groupid' => $custgrp_id,
                'dac_custgroupname' => trim($custgrp),
                'dac_prodgrp_id' => $prodgrp_id,
                'dac_prodgroupname' => trim($prodgrp),
                'dac_source' => $src,
                'dac_updated_date' => date('Y-m-d:H:i:s'),
                'dac_updated_userid' => $login_user_id,
                'dac_updated_username' =>$login_username
            );
               $rev_potential_data  = array('dactivity_revised_potid' => $potential_id,
                'rpsc_salecate_id' => trim($salecat_id),
                'rpsc_salecat_name' => trim($sal_cat_type),
                'rpsc_act_potential' => $old_poten,
                'rpsc_rev_potential' => $new_poten,
                'rpsc_prev_potential' => $prev_poten,
                'rpsc_mail_status' => $mailflag,
                'rpsc_source' => $src,
                'rpsc_updated_date' => date('Y-m-d:H:i:s'),
                'rpsc_updated_userid' => $login_user_id,
                'rpsc_updated_username' =>$login_username
            );
            /* check for duplicate in detail table start*/
            $dup_record_detail = $this->dailyactivity_model->check_salecat_duplicate_record($potential_id,$salecat_id);
            if($dup_record_detail['0']['noofrows']>0)
            {
                // update detail table
              
             //echo"update"; print_r($rev_potential_data);
                $update_status=$this->dailyactivity_model->update_revisedpotential($rev_potential_data,$potential_id,$salecat_id);
            }
            else
            {
                // insert detail table 
                $rev_potential_data  = array('dactivity_revised_potid' => $potential_id,
                'rpsc_salecate_id' => $salecat_id,
                'rpsc_salecat_name' => trim($sal_cat_type),
                'rpsc_act_potential' => $old_poten,
                'rpsc_rev_potential' => $new_poten,
                'rpsc_prev_potential' => $prev_poten,
                'rpsc_mail_status' => $mailflag,
                'rpsc_source' => $src,
                'rpsc_created_date' => date('Y-m-d:H:i:s'),
                'rpsc_created_userid' => $login_user_id,
                'rpsc_created_username' =>$login_username
            );
             //echo"insert";print_r($rev_potential_data);
            $update_status=$this->dailyactivity_model->save_revisedpotential($rev_potential_data);
            }
            /*check for duplicate in detail table end*/
           

        }
        else
        {
              $insert_potential = array('dac_cust_groupid' => $custgrp_id,
                'dac_custgroupname' => trim($custgrp),
                'dac_prodgrp_id' => $prodgrp_id,
                'dac_prodgroupname' => trim($prodgrp),
                'dac_source' => $src,
                'dac_created_date' => date('Y-m-d:H:i:s'),
                'dac_created_userid' => $login_user_id,
                'dac_created_username' => $login_username
            );
              $last_insert_id  =$this->dailyactivity_model->save_newpotential($insert_potential);
             $rev_potential_data_insert  = array('dactivity_revised_potid' => $last_insert_id,
                'rpsc_salecate_id' => $salecat_id,
                'rpsc_salecat_name' => trim($sal_cat_type),
                'rpsc_act_potential' => $old_poten,
                'rpsc_rev_potential' => $new_poten,
                'rpsc_prev_potential' => $prev_poten,
                'rpsc_mail_status' => $mailflag,
                'rpsc_source' => $src,
                'rpsc_created_date' => date('Y-m-d:H:i:s'),
                'rpsc_created_userid' => $login_user_id,
                'rpsc_created_username' =>$login_username
            );
             //echo"insert";print_r($rev_potential_data);
            $update_status=$this->dailyactivity_model->save_revisedpotential($rev_potential_data_insert);

        }
            if($update_status>0)
            {
               // $this->session->set_flashdata('message', 'Potential Updated Sucessfully');
                $message ='Potential Updated Sucessfully';
          //  redirect('admin/manageusers', 'refresh');
            echo $message;
            }

        


     
         
     
     }

     function getcustomerhdr_id($custgrp) 
            {
                $custgrp = urldecode($custgrp);
               // $custgrp = html_entity_decode($this->uri->segment(3));
             //   echo $custgrp; die;
                $cust_id = $this->dailyactivity_model->get_customerhdr_id($custgrp);
                //$customerhrd_id = $cust_id['id'];
                //echo $cust_id; die;
                header('Content-Type: application/x-json; charset=utf-8');
                echo $cust_id;
            }

     function test()
     {
        $sales_type_id = $this->dailyactivity_model->get_salestypeid_byname('Part Tanker');
     }

   
}

?>
