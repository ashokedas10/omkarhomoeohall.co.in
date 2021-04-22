<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_controller  extends CI_Controller {

//http://learntallyerp9.blogspot.in/2009/11/list-of-ledgers.html

	public function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			$this->load->helper('form');
			$this->load->library('email');
			$this->load->model('project_model', 'projectmodel');
			//$this->load->model('modeltree');
			$this->load->model('accounts_model');			
			$this->load->library(array('form_validation', 'trackback','pagination'));
			//$this->load->library('numbertowords');
			//$this->load->library('general_library');
			//$this->load->library('pdf'); 
			//$this->load->helper('file'); 			
			//$this->login_validate();
			//$this->load->library('excel');			
		}	

	

            
        public function esp32($uid='test')
        {

            $return_data['uid_status']=1;
            $return_data['display_msg']='test';

           // $return_data['uid']=trim($uid);           
            $whr=" uid='".trim($uid)."'";
            $return_data['uid_status']=$this->projectmodel->GetSingleVal('uid_status','tbl_esp32',$whr); 

           // $return_data['led_value']=$uid;
            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/json");
            echo json_encode($return_data);
                            
        }

        public function esp32_post()
        {

            if (isset($_SERVER['HTTP_ORIGIN'])) {
            	header("Access-Control-Allow-Origin: *");
            	header('Access-Control-Allow-Credentials: true');
            	header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }

            // Access-Control headers are received during OPTIONS requests
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

            	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            		header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            	exit(0);
            }


            

            $id_header=$id_detail='';
            $form_data=json_decode(file_get_contents("php://input"));
            $data=$return_data=$save_details=$save_hdr=array();
            $return_data['uid_status']=1;
            $return_data['display_msg']='test';

            //$return_data['uid']=trim($form_data->uid);
            $return_data['led_value']=trim($form_data->led_value);
            $whr=" uid='".trim($form_data->uid)."'";
            $return_data['uid_status']=$this->projectmodel->GetSingleVal('uid_status','tbl_esp32',$whr);           

            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/json");
            echo json_encode($return_data);
                            
        }


}

?>