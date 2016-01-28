<?php

class Excelreportbranch extends CI_Controller {

    public $leaddetails = array();

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        //load new PHPExcel library
        $this->load->library('excel');
        $this->load->helper('download');
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

    function exgetdatawithfilter($branch, $user_id = 0) {
        
                @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                @$reportingto= $this->session->userdata['reportingto'];
              
       $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch', 'Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'AssignedToUserID', 'AssignTo', 'Created Date','lead_created_date', 'CreatedMonth', 'Created By', 'Lastupdate Date', 'Lastupdated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname','BusinessType', 'Immediate_Requirement', 'Potential_Repack', 'Potential_Intact', 'Potential_Bulk', 'Potential_Small Packing', 'Potential_Single Tanker', 'Potential_Part Tanker','Potential_Indent Bulk','Potential_FCL','Potential_ISO Container', 'productupdatedate', 'productcreatedate', 'industrysegment', 'ProdcutId', 'productname', 'itemgroup', 'uom', 'customername', 'customertype','Financial Yr','JC_Code');
        if ($reportingto == "") {
            if ($user_id == "") {
                $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . strtoupper($branch) . "' order by 1 desc";
            } else {
                //$sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND created_user IN (".$user_id.")"; 
                $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . strtoupper($branch) . "' AND asignedto_userid IN (" . $user_id . ") order by 1 desc";
            }
        } else {
            if ($user_id == "") {
                $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . strtoupper($branch) . "' AND asignedto_userid IN (" . $get_assign_to_user_id . ") order by 1 desc";
            } else {
                //$sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND created_user IN (".$user_id.")"; 
                $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . strtoupper($branch) . "' AND asignedto_userid IN (" . $user_id . ") order by 1 desc";
            }
        }


        //	echo $sql; die;
        $result = $this->db->query($sql);
        //	print_r($result); die;
        if (@$result) {
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
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $branch . 'LeadProductsList.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* Start - exgetdatabranch_user_dt_filter */

    function exgetdatabranch_user_dt_filter($branch, $user_id, $from_date, $to_date) {
        //  print_r($this->session->userdata);
        @$reportingto = $this->session->userdata['reportingto'];
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];



       $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch', 'Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'AssignedToUserID', 'AssignTo', 'Created Date','Lead Date', 'CreatedMonth', 'Created By', 'Lastupdate Date', 'Lastupdated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname','BusinessType', 'Immediate_Requirement', 'Potential_Repack', 'Potential_Intact', 'Potential_Bulk', 'Potential_Small Packing', 'Potential_Single Tanker', 'Potential_Part Tanker','Potential_Indent Bulk','Potential_FCL','Potential_ISO Container', 'productupdatedate', 'productcreatedate', 'industrysegment', 'ProdcutId', 'productname', 'itemgroup', 'uom', 'customername', 'customertype','Financial Yr','JC_Code');

        if ($reportingto == "") {
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . $branch . "'  AND asignedto_userid IN (" . $user_id . ") AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        } else {
            //$sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND created_user IN (".$user_id.")"; 
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . $branch . "' AND asignedto_userid IN (" . $user_id . ") AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        }

      //   echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');


            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $branch . $user_id . '-' . $from_date . '_' . $to_date . 'LeadProductsList.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* End - exgetdatabranch_user_dt_filter */

    /* Start - exgetdatabranch_dt_filter */

    function exgetdatabranch_dt_filter($branch, $from_date, $to_date) {
        //   @$reportingto = $this->session->userdata['reportingto'];
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        @$reportingto = $this->session->userdata['reportingto'];
         $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch', 'Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'AssignedToUserID', 'AssignTo', 'Created Date','Lead Date', 'CreatedMonth', 'Created By', 'Lastupdate Date', 'Lastupdated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname','BusinessType', 'Immediate_Requirement', 'Potential_Repack', 'Potential_Intact', 'Potential_Bulk', 'Potential_Small Packing', 'Potential_Single Tanker', 'Potential_Part Tanker','Potential_Indent Bulk','Potential_FCL','Potential_ISO Container', 'productupdatedate', 'productcreatedate', 'industrysegment', 'ProdcutId', 'productname', 'itemgroup', 'uom', 'customername', 'customertype','Financial Yr','JC_Code');
        if ($reportingto == "") {
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . $branch . "' AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        } else {

            //  $sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND asignedto_userid IN (".$user_id.") AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE"; 
            $sql = "SELECT * FROM vw_lead_export_to_exceljcwise_fn WHERE  branchname='" . $branch . "' AND asignedto_userid IN (" . $get_assign_to_user_id . ") AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        }

       // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $branch . '-' . $from_date . '_' . $to_date . 'LeadProductsList.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* End - exgetdatabranch_dt_filter */

    function exgetallleaddata() {

        $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch', 'Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'AssignedToUserID', 'AssignTo', 'Created Date','Lead Date', 'CreatedMonth', 'Created By', 'Lastupdate Date', 'Lastupdated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname','BusinessType', 'Immediate_Requirement', 'Potential_Repack', 'Potential_Intact', 'Potential_Bulk', 'Potential_Small Packing', 'Potential_Single Tanker', 'Potential_Part Tanker','Potential_Indent Bulk','Potential_FCL','Potential_ISO Container', 'productupdatedate', 'productcreatedate', 'industrysegment', 'ProdcutId', 'productname', 'itemgroup', 'uom', 'customername', 'customertype','Financial Yr','JC_Code');
        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];




        if ($this->session->userdata['reportingto'] == "") {
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn order by 1 desc";
        } else {
            //$sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND created_user IN (".$user_id.")"; 
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE asignedto_userid IN (" . $get_assign_to_user_id . ") order by 1 desc";
        }

        //    echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="AllLeadProductsList.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* Start - setfiltersexportAllDatewise to get records based only the month wise for all branches */

    function setfiltersexportAllDatewise($from_date, $to_date) {
        //   @$reportingto = $this->session->userdata['reportingto'];

        
        @$reportingto = $this->session->userdata['reportingto'];

        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        $headings = array('leadid', 'lead_no', 'email_id', 'firstname', 'lastname','Contact Person','phone_no','Branch', 'Comments', 'Converted', 'uploadeddate', 'description', 'address', 'secondaryemail', 'AssignedToUserID', 'AssignTo', 'Created Date','Lead Date', 'CreatedMonth', 'Created By', 'Lastupdate Date', 'Lastupdated By', 'sent_mail_alert', 'leadsource', 'lead_close_status', 'primarystatus', 'substatusname','BusinessType', 'Immediate_Requirement', 'Potential_Repack', 'Potential_Intact', 'Potential_Bulk', 'Potential_Small Packing', 'Potential_Single Tanker', 'Potential_Part Tanker','Potential_Indent Bulk','Potential_FCL','Potential_ISO Container', 'productupdatedate', 'productcreatedate', 'industrysegment', 'ProdcutId', 'productname', 'itemgroup', 'uom', 'customername', 'customertype','Financial Yr','JC_Code');
        if ($reportingto == "") {
            $sql = "SELECT  * FROM vw_lead_export_to_exceljcwise_fn WHERE  createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        } else {

            //  $sql = "SELECT * FROM vw_lead_export_excel WHERE  branchname='".$branch."' AND asignedto_userid IN (".$user_id.") AND createddate::DATE  between '".$from_date."'::DATE  and '".$to_date."'::DATE"; 
            $sql = "SELECT * FROM vw_lead_export_to_exceljcwise_fn WHERE  asignedto_userid IN (" . $get_assign_to_user_id . ") AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";
        }

        //echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $from_date . '_' . $to_date . 'LeadProductsList.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* End - setfiltersexportAllDatewise */
    function loginbranches()
    {
    // print_r($_POST);  
        $branches = $_POST['branchArray'];
        $headings = array('EmpCode','EmpName','Branch','LoginTime','LogoutTime','TimeSpent','Date','Month','Fin-Year','JC Code');
        $sql = "SELECT *  FROM 
                (
                SELECT
                        usr.empcode,
                                lg.user_name,
                                                upper(usr.location_user) as branch,
                              lg.login_time,
                                lg.logout_time,
                                lg.logout_time - login_time as time_spent,
                             lg.login_time::DATE as Date ,
                             to_char( login_time,'MON') as month,
                      get_acc_yr(lg.login_time::DATE) as fin_yr,
                      jl.jc_code
                FROM
                            lead_login_activity lg,
                      vw_web_user_login usr,
                      jc_calendar_dtl jl

                WHERE
                     lg.user_id = usr.header_user_id 
                    AND lg.user_id NOT IN (195,42,612,319,565,59,649,64,241,470,661) AND 
                     lg.login_time::DATE BETWEEN jc_period_from AND jc_period_to AND
                    lg.login_time::DATE!= CURRENT_DATE::DATE 


                ) l WHERE branch in ($branches)";

          //echo $sql; die;
            
            // Create a new PHPExcel object 
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('Banches TimeSpent');

            $rowNumber = 1;
            $col = 'A';
            foreach ($headings as $heading) {
                $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $heading);
                $col++;
            }
            // Loop through the result set 
            $rowNumber = 2;
            $result = $this->db->query($sql);
            if (@$result) {
            $leaddetails = $result->result_array();
            $count = $result->num_rows();
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {


                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $branch='login_logout_details.csv';
           /* header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $branch . '.csv"');
            header('Cache-Control: max-age=0');*/
           // $objWriter->save('php://output');
           // $url = base_url();
            $objWriter->save("/data/html/salescrm/public/".$branch);
            echo $branch;
            exit();
        } else {
            echo"norecords";
        }
    }
    function loginbranchesdates($fromdate,$todate)
    {
    // print_r($_POST);  
        $fromdate = $this->uri->segment(3);
        $todate = $this->uri->segment(4);
        $branches = $_POST['branchArray'];
        $headings = array('EmpCode','EmpName','Branch','LoginTime','LogoutTime','TimeSpent','Date','Month','Fin-Year','JC Code');
        $sql = "SELECT *  FROM 
                (
                SELECT
                        usr.empcode,
                                lg.user_name,
                                                upper(usr.location_user) as branch,
                              lg.login_time,
                                lg.logout_time,
                                lg.logout_time - login_time as time_spent,
                             lg.login_time::DATE as Date ,
                             to_char( login_time,'MON') as month,
                      get_acc_yr(lg.login_time::DATE) as fin_yr,
                      jl.jc_code
                FROM
                            lead_login_activity lg,
                      vw_web_user_login usr,
                      jc_calendar_dtl jl

                WHERE
                     lg.user_id = usr.header_user_id 
                    AND lg.user_id NOT IN (195,42,612,319,565,59,649,64,241,470,661) AND 
                     lg.login_time::DATE BETWEEN jc_period_from AND jc_period_to AND
                    lg.login_time::DATE!= CURRENT_DATE::DATE 


                ) l WHERE  login_time::DATE  >= '".$fromdate."' and login_time::DATE <= '".$todate."' AND branch in ($branches)";

          //echo $sql; die;
            
            // Create a new PHPExcel object 
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getActiveSheet()->setTitle('Banches TimeSpent');

            $rowNumber = 1;
            $col = 'A';
            foreach ($headings as $heading) {
                $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $heading);
                $col++;
            }
            // Loop through the result set 
            $rowNumber = 2;
            $result = $this->db->query($sql);
            if (@$result) {
            $leaddetails = $result->result_array();
            $count = $result->num_rows();
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $branch='login_logout_dt_details.csv';
           /* header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $branch . '.csv"');
            header('Cache-Control: max-age=0');*/
           // $objWriter->save('php://output');
           // $url = base_url();
            $objWriter->save("/data/html/salescrm/public/".$branch);
            echo $branch;
            exit();
        } else {
            echo"norecords";
        }
    }
    
    function downloadfile()
    {
            $filename = $this->uri->segment(3);
            $filename=$filename;
        //echo "filename".$filename;
            //$data = file_get_contents("/var/www/html/salescrm/public/".$filename.".csv");
           //  echo $doc_root =$_SERVER['DOCUMENT_ROOT']; die;
             $doc_root =$_SERVER['DOCUMENT_ROOT']; 
            $data = file_get_contents($doc_root."/salescrm/public/".$filename);
            force_download($filename,$data);
    }
    function vrdownloadfile()
    {
            $filename='visitreport.csv';
            $doc_root =$_SERVER['DOCUMENT_ROOT']; 
            $data = file_get_contents($doc_root."/salescrm/public/".$filename);
            force_download($filename,$data);
    }

    /* Start - visitreportexportAllDatewise to get records based only the month wise for all branches */

    function visitreportexportAllDatewise($from_date, $to_date) {
        //   @$reportingto = $this->session->userdata['reportingto'];

        
        @$reportingto = $this->session->userdata['reportingto'];

        @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
        $headings = array('Branch', 'Executive Name', 'Visted Date', 'Customer Group', 'Potential Qnty','Mode Of Contact','Item Group','Notes/Remarks', 'Creation Date', 'Date of Update');
        if ($reportingto == "") {
          
            $sql = "SELECT location,exename as Executive_Name,currentdate as Visit_Date ,custgroup  as Customer_Group, potentialqty,modeofcontact as Mode_Of_Contact , itemgroup as Item_Group,notes as Notes_Remarks,creationdate as Creation_Date, updatedate as Date_of_Update FROM dailactivityhistory WHERE currentdate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE ORDER BY 3";

        } else {
            
           /* $sql = "SELECT * FROM vw_lead_export_to_exceljcwise_fn WHERE  asignedto_userid IN (" . $get_assign_to_user_id . ") AND createddate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE order by 1 desc";*/

             $sql = "SELECT location,exename as Executive_Name,currentdate as Visit_Date ,custgroup  as Customer_Group, potentialqty,modeofcontact as Mode_Of_Contact , itemgroup as Item_Group,notes as Notes_Remarks,creationdate as Creation_Date, updatedate as Date_of_Update FROM dailactivityhistory WHERE currentdate::DATE  between '" . $from_date . "'::DATE  and '" . $to_date . "'::DATE ORDER BY 3";
        }

       // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');

            // Save as an Excel BIFF (xls) file 
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $from_date . '_' . $to_date . 'VisitReport.csv"');
            //header('Content-Disposition: attachment;filename="LeadsProductsList.xls"'); 
            header('Cache-Control: max-age=0');

            $objWriter->save('php://output');
            exit();
        } else {
            echo"no records";
        }
    }

    /* End - visitreportexportAllDatewise */


    function visitreportwithfilter($itemgroup,$customergrp,$branch,$from_date, $to_date) {
                $users = $_POST['userArray'];
                @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                @$reportingto= $this->session->userdata['reportingto'];
                $itemgroup = urldecode($itemgroup);
                $customergrp =urldecode($customergrp);
                $branch = strtoupper($branch);
                $whereParts = array();
/*                echo"branch ".$branch."<br>";
                echo"itemgroup ".$itemgroup."<br>";
                echo"customergrp ".$customergrp."<br>";
                echo"users ".$users."<br>";*/
                if($branch)     { $whereParts[] = "upper(location)='".$branch."' "; }
           /*     if($from_date) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
                if($to_date)      { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }*/
                if($itemgroup)  { $whereParts[] = "itemgroup = '".$itemgroup."' "; }
                if($customergrp)  { $whereParts[] = "custgroup = '".$customergrp."' "; }
                if($users!='undefined')   { $whereParts[] = "exename  IN (".$users.") "; }
                
                $whereParts[]="currentdate::DATE BETWEEN  '".$from_date."'::DATE AND  '".$to_date."'::DATE";
              
                $headings = array('Branch', 'Executive Name', 'Visted Date', 'Customer Group', 'Potential Qnty','Mode Of Contact','Item Group','Notes/Remarks', 'Creation Date', 'Date of Update');
                if ($reportingto == "") {
                    
                        $sql = "SELECT 
                                    location,exename as Executive_Name,
                                    currentdate as Visit_Date ,
                                    custgroup  as Customer_Group, 
                                    potentialqty,modeofcontact as Mode_Of_Contact ,
                                    itemgroup as Item_Group,
                                    notes as Notes_Remarks,
                                    creationdate as Creation_Date, 
                                    updatedate as Date_of_Update 
                                FROM 
                                    dailactivityhistory ";
                    
                    }
                 else {
                    
                        $sql = "SELECT 
                                    location,exename as Executive_Name,
                                    currentdate as Visit_Date ,
                                    custgroup  as Customer_Group, 
                                    potentialqty,modeofcontact as Mode_Of_Contact ,
                                    itemgroup as Item_Group,
                                    notes as Notes_Remarks,
                                    creationdate as Creation_Date, 
                                    updatedate as Date_of_Update 
                                FROM 
                                    dailactivityhistory ";
                    

                    }
                
                if(count($whereParts)) {
                     $sql .= " WHERE " . implode('AND ', $whereParts);
                }
                $sql .= ' ORDER BY 3 ASC'; 

         // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $branch='visitreport.csv';
            $doc_root =$_SERVER['DOCUMENT_ROOT']; 
            $objWriter->save($doc_root."/salescrm/public/".$branch);
            echo $branch;
            exit();
        } else {
            echo"norecords";
        }
    }

    function visitreportbranch_user_dt_filter($itemgroup,$customergrp,$branch,$from_date, $to_date) {
                $users = $_POST['userArray'];
                @$get_assign_to_user_id = $this->session->userdata['get_assign_to_user_id'];
                @$reportingto= $this->session->userdata['reportingto'];
                $itemgroup = urldecode($itemgroup);
                $customergrp =urldecode($customergrp);
                $branch = strtoupper($branch);
/*                echo"branch ".$branch."<br>";
                echo"itemgroup ".$itemgroup."<br>";
                echo"customergrp ".$customergrp."<br>";
                echo"users ".$users."<br>";*/
                $whereParts = array();
                if($branch)     { $whereParts[] = "upper(location)='".$branch."'"; }
           /*     if($from_date) { $whereParts[] = "leaddetails.created_user = $selectuserid "; }
                if($to_date)      { $whereParts[] = "leaddetails.assignleadchk = $assigntouserid "; }*/
                if($itemgroup)  { $whereParts[] = "itemgroup = '".$itemgroup."'"; }
                if($customergrp)  { $whereParts[] = "custgroup = '".$customergrp."' "; }
                if($users!='undefined')  { $whereParts[] = "exename  IN (".$users.") "; }
                
                $whereParts[]="currentdate::DATE BETWEEN  '".$from_date."'::DATE AND  '".$to_date."'::DATE";
              
       $headings = array('Branch', 'Executive Name', 'Visted Date', 'Customer Group', 'Potential Qnty','Mode Of Contact','Item Group','Notes/Remarks', 'Creation Date', 'Date of Update');
        if ($reportingto == "") {
            
                $sql = "SELECT 
                            location,exename as Executive_Name,
                            currentdate as Visit_Date ,
                            custgroup  as Customer_Group, 
                            potentialqty,modeofcontact as Mode_Of_Contact ,
                            itemgroup as Item_Group,
                            notes as Notes_Remarks,
                            creationdate as Creation_Date, 
                            updatedate as Date_of_Update 
                        FROM 
                            dailactivityhistory ";
            
            }
         else {
            
                $sql = "SELECT 
                            location,exename as Executive_Name,
                            currentdate as Visit_Date ,
                            custgroup  as Customer_Group, 
                            potentialqty,modeofcontact as Mode_Of_Contact ,
                            itemgroup as Item_Group,
                            notes as Notes_Remarks,
                            creationdate as Creation_Date, 
                            updatedate as Date_of_Update 
                        FROM 
                            dailactivityhistory ";
            

            }
        
        if(count($whereParts)) {
             $sql .= " WHERE " . implode('AND ', $whereParts);
        }
        $sql .= ' ORDER BY 3 ASC'; 

         // echo $sql; die;
        $result = $this->db->query($sql);
        //  print_r($result); die;
        if (@$result) {
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
            //  print_r($leaddetails); die;

            for ($i = 0; $i < $count; $i++) {
                $col = 'A';
                foreach ($leaddetails[$i] as $row => $cell) {
                    /* echo"col ".$col."<br>";
                      echo"rowNumber ".$rowNumber."<br>";
                      echo"cell ".$cell."<br>"; */

                    $objPHPExcel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }

            $objPHPExcel->getActiveSheet()->freezePane('A2');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $branch='visitreport.csv';
            
            $doc_root =$_SERVER['DOCUMENT_ROOT']; 
            $objWriter->save($doc_root."/salescrm/public/".$branch);
            echo $branch;
            exit();
        } else {
            echo"norecords";
        }
    }

    


}// End of Class
?>
