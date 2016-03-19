<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Overseas extends CI_Controller{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();
        $this->load->library('admin_auth');
        $this->lang->load('admin');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper('html');

        
      
    }


       
     public function index() {
           if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) 
        {
         $this->load->view('overseas/index');
        }
     
    }

    public function customerinfo() {
        $this->load->model('Overseas_model');
           if (!$this->admin_auth->logged_in()) {
            //redirect them to the login page
            redirect('admin/login', 'refresh');
        } elseif (!$this->admin_auth->is_admin()) 
        {
            $leaddata['data'] = $this->Overseas_model->getcustomerinfo();
            
            $this->load->view('overseas/viewinfo',$leaddata);
        }
     
    }

    public function savecustomerinfo()
    {
         $this->load->model('Overseas_model');
         $login_user_id = $this->session->userdata['user_id'];
            $login_username = $this->session->userdata['username'];
       

                $overseas_customerinfo = array(
                    'suplier_name' => $this->input->post('supplier_name'),
                    'product_name' => $this->input->post('product_name'),
                    'purchase_price' => trim($this->input->post('purchase_price')),
                    'back_to_back_order' => $this->input->post('back_to_backorder'),
                    'other_remarks' => $this->input->post('other_remarks'),
                    'inter_selling_price' => $this->input->post('internation_sp'),
                    'created_date' => date('Y-m-d:H:i:s'),
                    'created_by' => $login_user_id
                );
        
        
         $cust_id = $this->Overseas_model->save_customer($overseas_customerinfo);
         if ($cust_id!="")
         {
            $this->session->set_flashdata('message', "Information Saved Successfully");   
         }
         else
         {
            $this->session->set_flashdata('message', " Error in Customer Creation");   
         }
          redirect('overseas');
         
    }
    


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
