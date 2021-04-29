<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cron extends CI_Controller {
 
function __construct()
{
	parent::__construct();
	$this->load->helper('url');
	$this->load->library('email');	
	$this->load->library('general_library');
	$this->load->library('pdf'); 
	$this->load->helper('file'); 
	$this->load->helper('directory');
	$this->load->model('project_model', 'projectmodel');
	// this controller can only be called from the command line
	//if (!$this->input->is_cli_request()) show_error('Direct access is not allowed');
}

	public function delete_session_files()
	{
	
		$absolute_path=FCPATH.'sessions/';
		$files = directory_map($absolute_path,1);
		foreach($files as $file)
		{
			$absolute_path=FCPATH.'sessions/'.$file;
			if(file_exists($absolute_path))
			{unlink($absolute_path);}		
		}
		
	}


	
	public function dbbackup()
	{
	   
		 $sqlemp="select * from company_details where id=1";
		 $rowrecordemp = $this->projectmodel->get_records_from_sql($sqlemp);	
		 foreach ($rowrecordemp as $rowemp)
		 { 
			 $COMPNAME=$rowemp->NAME;
			 $MOB_NOS=$rowemp->MOB_NOS;
			 $emailid=$rowemp->EMAIL_IDS;
			 $company_mailid=$rowemp->company_mailid;
			 $authKey=$rowemp->SMS_KEY;
			 $senderId=$rowemp->SMS_SENDERID;
		 }	
	   	  $emailid='ashokedas@gmail.com'; 
		  $company_mailid='info@onlineunilab.co.in';
          $date = date('Ymd');
		  
		// Load the DB utility class
     
	   
	   $this->load->dbutil(); 
        // Backup your entire database and assign it to a variable
        $backup =& $this->dbutil->backup(); 
        // Load the file helper and write the file to your server
        $date = date('Ymd');
        write_file('./backup/filename'.$date.'.zip', $backup); 
        echo 'Backup Process done successfully.backup file at backup folder';
		
		
	   
	   
	   		//MAIL PORTION
	        $this->email->initialize(array('mailtype' => 'html')); 
			$this->email->from($company_mailid,$COMPNAME);
			$this->email->to($emailid); 
			//$this->email->cc('ashokedas@gmail.com'); 
			//$this->email->bcc('them@their-example.com');
			$this->email->subject('Database Backup');
			//$email =$this->load->view('primary_sale_view/invoice_print_sell', 
			//$data_print, TRUE);
			$this->email->message('Backup');	
			//$this->email->attach('./backup/filename'.$date.'.zip');  
			$this->email->attach('./backup/filename20151023.zip');   
			$this->email->send();
	 
	 		echo 'mail sent';
	       //MAIL PORTION END
	  
        //Now delete the file
     //  unlink('./backup/filename'.$date.'.zip');
 
	}
	
	
	
	
	
	
	function send_mail($msg = 'bar')
	{
		//http://duckbilldrugs.net/reporting/index.php/cron/send_mail
		
		 $sqlemp="select * from company_details where id=1";
		 $rowrecordemp = $this->projectmodel->get_records_from_sql($sqlemp);	
		 foreach ($rowrecordemp as $rowemp)
		 { 
			 $COMPNAME=$rowemp->NAME;
			 $MOB_NOS=$rowemp->MOB_NOS;
			 $emailid=$rowemp->EMAIL_IDS;
			 $company_mailid=$rowemp->company_mailid;
			 $authKey=$rowemp->SMS_KEY;
			 $senderId=$rowemp->SMS_SENDERID;
		 }	
		
		
		
		$invoice_no=1;
		$invoice_date=date('Y-m-d');
		$sql="select * from invoice_summary '
		where invoice_date='".$invoice_date."' ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{ 
			$data_print['table_id']=$row1->id;
			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$row1->id;
			$this->pdf->load_view('primary_sale_view/invoice_print_sell', $data_print);
			$this->pdf->render();
			$this->pdf->stream("pdf/invoice.pdf");				
			write_file("pdf/invoice.pdf", $this->pdf->output());			
			$this->email->initialize(array('mailtype' => 'html')); 
			$this->email->from($company_mailid,$COMPNAME);
			
			//GET CLIENT MAIL ID
			$clientid=0;
			$sql_client="select distinct(parentuid) parentuid
			from tbl_organisation_chain where 
			childuid in 
			(select b.tbl_hierarchy_org_id from stockist a,stockist_hq_map b 
			where a.id=b.stockist_id   and a.id=".$row1->tbl_party_id.") 
			and child_desig_srl<=5 ";
			$clients= $this->projectmodel->get_records_from_sql($sql_client);	
			foreach ($clients as $client)
			{$clientid=$clientid.','.$client->parentuid;}
			
			$data_print['table_id']=$row1->id;
			$this->email->to($emailid); 
			//$this->email->cc('ashokedas@gmail.com'); 
			//$this->email->bcc('them@their-example.com');
			$this->email->subject('Sell Invoice:#'.$invno.' /'.$invoice_date);
			$email =$this->load->view('primary_sale_view/invoice_print_sell', 
			$data_print, TRUE);
			$this->email->message($email);	
			$this->email->attach('pdf/invoice.pdf');    
			$this->email->send();
			
		}
		
		$party_emailid=$emailid='ashokedas@gmail.com';
		$this->email->initialize(array('mailtype' => 'html')); 
		$emailmsg='Your Invoice has been Prepared.No :'.$invoice_no;				 
		$this->email->from('info@adequatesolutions.co.in',
						   'INVOICE FROM ADEQUATE SOLUTIONS');
		$this->email->to($party_emailid); 
		$this->email->cc('ashokedas@gmail.com','uditadas.mail@gmail.com'); 
		//$this->email->bcc('them@their-example.com'); 
		$this->email->subject('INVOICE NO :#'.$invoice_no.' Dated'.$invoice_date);
		//$email = $this->load->view('invoice_email_view',$save_data, TRUE);
		$email ='test';
		$this->email->message($email);	
		//this->email->attach('pdf/invoice.pdf');    
		$this->email->send();
		   
	}
	
	
	
		
}

?>