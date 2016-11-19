<?php

class Crontodo extends CI_Controller {

   
    function __construct()
     {
      parent::__construct();
      //load the model
      $this->load->model('cronjob_model','',TRUE);
     }

     function index()
     {
      $this->cronjob_model->my_cronjob();
     }
    
       

}

?>
