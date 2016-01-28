<?php
    class Overseas_model extends CI_Model{
            function __construct()
			{
				$this->load->library('form_validation');
				$this->load->helper('url');
				$this->load->database();
				$this->load->helper('language');
				$this->load->library('subquery');
				$this->load->library('session');
				
				
			}

	public function save_customer($customerdata) {
        $this->db->insert('overseas_customerinfo', $customerdata);
        return $this->db->insert_id();
    }

    public function getcustomerinfo()
    {
			$sql = "SELECT * FROM overseas_customerinfo";
			//sale_type_id ,suplier_name,product_name,purchase_price,back_to_back_order,other_remarks,inter_selling_price,
			$result = $this->db->query($sql);
			$productdetails = $result->result_array();
			$jTableResult['leaddetails']=$productdetails;
			$data = array();
			$i = 0;
			 while ($i < count($jTableResult['leaddetails'])) {
            $row = array();
            $row["sale_type_id"] = $jTableResult['leaddetails'][$i]["sale_type_id"];
            $row["suplier_name"] = $jTableResult['leaddetails'][$i]["suplier_name"];
            $row["product_name"] = $jTableResult['leaddetails'][$i]["product_name"];

            $row["purchase_price"] = $jTableResult['leaddetails'][$i]["purchase_price"];
            $row["back_to_back_order"] = $jTableResult['leaddetails'][$i]["back_to_back_order"];
            $row["other_remarks"] = $jTableResult['leaddetails'][$i]["other_remarks"];
            $row["inter_selling_price"] = $jTableResult['leaddetails'][$i]["inter_selling_price"];
         
            /*$row["created_date"] = substr($jTableResult['leaddetails'][$i]["createddate"], 0, -8);
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
            }*/

            $data[$i] = $row;
            $i++;
        }
        $arr = "{\"data\":" . json_encode($data) . "}";
        //  echo "{ rows: ".$arr." }";
        return $arr;
    }
} 
 ?>

