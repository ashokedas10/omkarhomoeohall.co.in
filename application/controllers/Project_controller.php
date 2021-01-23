<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_controller  extends CI_Controller {

// auto complete
//http://codeigniterlover.blogspot.in/2013/01/jquery-autocomplete-in-codeigniter-php.html
//http://www.php-guru.in/2013/html-to-pdf-conversion-in-codeigniter/

// DB BACKUP AND MAIL  ...
//http://snipt.org/wponh

//file:///E:/ALL_WEBSITE_NEW/xampp/htdocs/money_market/psrgroupnew/jquery_jqwidgets
//demos/jqxtree/checkboxes.htm

	public function __construct()
		{
			parent::__construct();
			
			$this->load->helper(array('form', 'url'));
			 $this->load->library(array('form_validation', 
			'trackback', 'pagination'));
			$this->load->model('Project_model', 'projectmodel');
			$this->load->model('company_structure_model');
			$this->load->model('Form_report_create_model', 'FrmRptModel');
			$this->load->model('accounts_model');
			$this->load->library('numbertowords');
			$this->load->library('pdf');
			$this->load->helper('file'); 
			$this->load->library('Highcharts');	
			$this->load->library('general_library');
			
			//$this->load->library('excel');
			$this->load->library('Excel_reader');
			
			ini_set('max_execution_time', 0); 
			ini_set('memory_limit','2048M');
			


			/* Open the file
    Click CTRL + F
    Select "Current document" in "Find in" 
	(You can also select the folder if you have multiple files)
    Search in "Source code"
    Tick "Use regular expression"
    Type "[\r\n]{2,}" (without quotes) in "Find"
    Type "\n" (without quotes) in "Replace"
    Press "Replace All"*/
	
}
	
	
	public function load_page()
	{
		$this->login_validate();	
		$data_list['pageId'] = $this->input->post('pageId');		
		$this->load->view('ActiveReport/TemplateForm', $data_list);
		print_r($data_list);
		//redirect('Project_controller/TempleteForm/24/list/');
	}
	

	public function urgent_codes($frmrpttemplatehdrID=0)
	{
		//update stock balance
		$company_id=1;
		// $products = "select * from  invoice_summary a, invoice_details b  
		// where a.id=b.invoice_summary_id and  a.status='PURCHASE'  and a.company_id=1" ;
		$start=2500;
		$end=2700;
		//LIMIT ".$start." , ".$end
		
		$products = "select * from   invoice_details where product_id>0 and status='PURCHASE' AND `qty_available`<=0 ";
		$products = $this->projectmodel->get_records_from_sql($products);
		if(count($products)>0){foreach ($products as $key=>$product)
		{		
			$tot_purchase=$product->qnty;
			$tot_sale=0;

			// if($product->product_id>0)
			// {
				$records = "select sum(qnty) totqnty from invoice_details 
				where product_id=".$product->product_id." and PURCHASEID='".$product->id."' and  status='SALE' " ;
				$records = $this->projectmodel->get_records_from_sql($records);
				$tot_sale=$records[0]->totqnty;

				//if(count($records)>0){foreach ($records as $record){$tot_sale=$record->totqnty;}}
			// }
			

			$inv_details['qty_available']=$tot_purchase-$tot_sale;


			//echo 'product :'.$product->product_id.' batch :'.$product->batchno.' Available: '.$inv_details['qty_available'];
			//echo '<br>';

			$this->projectmodel->save_records_model($product->id,'invoice_details',$inv_details);
			
			echo 'item :'.$key.'Updated';

			// $inv_details['qty_available']=$tot_purchase-$tot_sale+$SELL_RTN-$PRUCHAR_RTN-$tot_sample;
			
			// $this->save_records_model($product->id,'invoice_details',$inv_details);
						
			// $this->projectmodel->product_update($product->product_id,'productmstr',$company_id);
			// $PURCHASEID=$product->PURCHASEID;
			// $status=$product->status;

			// if( $status=='PURCHASE')			
			// {$this->projectmodel->product_update($product->id,'invoice_details');}
			// else
			// {$this->projectmodel->product_update($PURCHASEID,'invoice_details');}	

		}}

		echo "Process done";
	}


public function TemplateReports($frmrpttemplatehdrID=0)
{
	$this->login_validate();
	if($frmrpttemplatehdrID==29) //STOCK REPORT
	{	
	  $data['GridHeader']= array("SysId#-left", "product_id-left","product_name-left","totqnty-left",
	  "batchno-left","exp_monyr-left","mfg_monyr-left","sale rate-left","MRP-left");
	}
	   
   $data['frmrpttemplatehdrID']=$frmrpttemplatehdrID;
   
   $records="select * from frmrpttemplatehdr where id=".$frmrpttemplatehdrID;
   $records = $this->projectmodel->get_records_from_sql($records);	
   foreach ($records as $record)
   {	
		$data['DisplayGrid']=$record->DisplayGrid;
		$data['NEWENTRYBUTTON']=$record->NEWENTRYBUTTON;
		
		$data['FormRptName']=$record->FormRptName;
		$data['DataFields']=$record->DataFields;
		$data['TableName']=$record->TableName;
		$data['WhereCondition']=$record->WhereCondition;
		$data['OrderBy']=$record->OrderBy;	
		$ControllerFunctionLink=$record->ControllerFunctionLink.$frmrpttemplatehdrID.'/';	 
		$data['tran_link'] = ADMIN_BASE_URL.$ControllerFunctionLink; 
		$view_path_name=$record->ViewPath; 
		$data['body']=$this->projectmodel->Activequery($data,'LIST');
   }
		   
   $view_path_name=$view_path_name;
   $this->page_layout_display($view_path_name,$data);
}


//TEMPLATE FORM REPORT IN ANGULARJS


public function load_form_report($TranPageName='',$pagetype='',$form_id=0)
{

	$output =$data= array();
	$this->session->set_userdata('form_id', $form_id);
	$this->session->set_userdata('TranPageName', $TranPageName);

	if($pagetype=='acc_tran')
	{	$view_path_name='accounts_management/transaction/'.$TranPageName;}
	else if($pagetype=='report')
	{
		$TranPageName='experimental_report';
		$view_path_name='accounts_management/reports/'.$TranPageName;
	}
	else if($pagetype=='master_form')
	{
		$TranPageName='template_master_form';
		$view_path_name='accounts_management/transaction/'.$TranPageName;

		$whr=" id=".$form_id;	
		$TranPageName=$this->projectmodel->GetSingleVal('FormRptName','frmrpttemplatehdr',$whr);		
		$this->session->set_userdata('TranPageName', $TranPageName);

	}
	
	else
	{$view_path_name='accounts_management/master/'.$TranPageName;}

	$this->page_layout_display($view_path_name,$data);		
	
}



public function list_of_values($datatype='',$cond=0)
{


	$output=array();

		if($datatype=='SAVE')
		{
			$id_header=$id_detail='';
			$data=$return_data=$save_details=$save_hdr=array();				
			$RAW_DATA=file_get_contents("php://input");
			$form_data=json_decode(file_get_contents("php://input"));
			$json_array_count=sizeof($form_data);

						
			$id_header=$form_data[0]->id_header;			

			$count=sizeof($form_data[0]->list_of_values);		
			for($cnt=0;$cnt<=$count-1;$cnt++)
			{			
					$id_detail=$form_data[0]->list_of_values[$cnt]->id_detail;
					$save_details['parent_id']=$id_header;
					$save_details['Status']='LIST';
					$save_details['active_inactive']=$form_data[0]->list_of_values[$cnt]->active_inactive;
					$save_details['FieldID']=$save_details['FieldVal']=$form_data[0]->list_of_values[$cnt]->FieldVal;	
					$save_details['comment']=$form_data[0]->list_of_values[$cnt]->comment;	
					$save_details['display_order']=$form_data[0]->list_of_values[$cnt]->display_order;	
					
					$this->projectmodel->save_records_model($id_detail,'frmrptgeneralmaster',$save_details);						
						
			}

			$return_data['id_header']=$id_header;
			
			header('Access-Control-Allow-Origin: *');
			header("Content-Type: application/json");
			echo json_encode($return_data);

		}




		if($datatype=='VIEWALLVALUE')
		{
						
				$acc_tran_details['id_header']=$cond;

				//delete array elements				
				// for($bil=0;$bil<=300-1;$bil++)
				// {unset($acc_tran_details['list_of_values'][$bil]);}
				
					$arraindx=0;
					$acc_tran_details['list_of_values'][$arraindx]['id_detail']='';
					$acc_tran_details['list_of_values'][$arraindx]['FieldVal']=0;				
					$acc_tran_details['list_of_values'][$arraindx]['comment']='';								
					$acc_tran_details['list_of_values'][$arraindx]['display_order']='';
					$acc_tran_details['list_of_values'][$arraindx]['active_inactive']='';		
				
					if($cond>0)
					{
						$sql_bills="select * from  frmrptgeneralmaster where 	parent_id=".$cond." ORDER BY id";					
						$sql_bills = $this->projectmodel->get_records_from_sql($sql_bills);	
						foreach ($sql_bills as $sql_bill)
						{				
							$acc_tran_details['list_of_values'][$arraindx]['id_detail']=$sql_bill->id;
							$acc_tran_details['list_of_values'][$arraindx]['FieldVal']=$sql_bill->FieldVal;		
							$acc_tran_details['list_of_values'][$arraindx]['comment']=$sql_bill->comment;						
							$acc_tran_details['list_of_values'][$arraindx]['display_order']=$sql_bill->display_order;
							$acc_tran_details['list_of_values'][$arraindx]['active_inactive']=$sql_bill->active_inactive;

							$arraindx=$arraindx+1;
						}
					}
			
					array_push($output,$acc_tran_details);
					header('Access-Control-Allow-Origin: *');
					header("Content-Type: application/json");
					echo json_encode($output);
		}

		
	

}


public function experimental_form_grid($datatype='',$hindx=0,$flfindx=0)
{						
	
	//CHECK DATASAVE TABLE
	// $rs=$resval=$form_structure=$output=$save_details=array();
	// $whr=" id=1";	
	// $raw_data=$this->projectmodel->GetSingleVal('test_data','test_table',$whr);
	// $form_data=json_decode($raw_data);
	// $headers=json_decode(json_encode($form_data[0]->header), true );
	// $save_details=$this->FrmRptModel->create_save_array($headers);

	
	// foreach($save_details as $key1=>$tables)
	// {
	// 	if($key1<2)
	// 	{
	// 		foreach($tables as $key2=>$fields)
	// 		{
				
	// 					$table_name=$key2;		
	// 					$savedata=array();	
	// 					$save_statue=true;

	// 			foreach($fields as $key3=>$value)
	// 					{
	// 						//HERE REQUIRE CUSTOMIZATION
	// 						if($key3=='id' && $table_name=='invoice_summary')
	// 						{
	// 							if($value>0)
	// 							{$header_id=$value;}
	// 							else 
	// 							{$header_id='';}  											
	// 						}
	// 						else if ($key3<>'id' && $table_name=='invoice_summary')
	// 						{$savedata[$key3]=$value;}
	// 						else if ($key3=='id' && $table_name=='invoice_details')
	// 						{if($value>0){$id=$value;}else {$id='';}   }
	// 						else if ($key3=='invoice_summary_id' && $table_name=='invoice_details')
	// 						{$savedata[$key3]=$header_id; }
	// 						else 
	// 						{
	// 							$savedata[$key3]=$value; 
	// 							//if($savedata['product_id']==0){$save_statue=false;}

	// 						}

	// 						echo $key3.$savedata[$key3].'<br>';

	// 					}

	// 					//HEADER SECTION
	// 					if($table_name=='invoice_summary')
	// 					{
	// 						// $this->projectmodel->save_records_model($header_id,$table_name,$savedata);
	// 						// if($key1==0 && $header_id=='')
	// 						// {$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";}	
	// 						// else if($key1==0 && $header_id>0){$server_msg="Record has been Updated Successfully!";}										
							
	// 					//	echo $savedata[$key3].'<br>';
	// 					}

	// 					if($table_name=='invoice_details')
	// 					{
	// 						// if($savedata['product_id']>0)
	// 						// {$this->projectmodel->save_records_model($id,$table_name,$savedata);}

	// 					//	echo $savedata[$key3].'<br>';
	// 					}



	// 		}
	// 	}
		

	// }

	// echo '<pre>';
	// print_r($save_details);
	// echo '</pre>';

	

	//CHECK DATASAVE TABLE

	// echo 'test ';
	// $url="http://122.176.31.190/realitycsapi/api/Leaderboard/GetComparison_SalesRevenueTrunover?StartDate=01%2F01%2F2018&EndDate=01%2F31%2F2018";
	// $return_data=	$setting=	$rs=$resval=$form_structure=$output=array();			
	// $form_data1=json_decode($url);

	// echo '<pre>';
	// print_r($url);
	// echo '</pre>';

	// $arr['header'][0]['section_type']='FORM';
	// $arr['header'][0]['id']=0;
	// $arr['header'][0]['parent_id']='';
	// $arr['header'][0]['TableName']='invoice_summary';
	// $arr['header'][0]['fields'][0]['id']['MainTable']='a_import_doctor_master';
	// $arr['header'][0]['fields'][0]['id']['input_id_index']=1;
	// $arr['header'][0]['fields'][0]['id']['LabelName']='id';
	// $arr['header'][0]['fields'][0]['id']['MainTable']='a_import_doctor_master';


				 


	// $id=0;
	// $indx=0;
	// $form_id=36;
	// $whr=" id=".$form_id;	
	// $DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
	// $DataFields2=$this->projectmodel->GetSingleVal('DataFields2','frmrpttemplatehdr',$whr);	
	// $DataFields3=$this->projectmodel->GetSingleVal('DataFields3','frmrpttemplatehdr',$whr);	
	// $DataFields4=$this->projectmodel->GetSingleVal('DataFields4','frmrpttemplatehdr',$whr);	
	// $DataFields5=$this->projectmodel->GetSingleVal('DataFields5','frmrpttemplatehdr',$whr);	
	// $TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
	// $section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
	// $rs[$indx]['section_type']=$section_type;	
	// $rs[$indx]['frmrpttemplatehdr_id']=$form_id;
	// $rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
	// $rs[$indx]['fields']=$DataFields;
	// $rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where id=".$id;



	// $indx=1;
	// $form_id=37;
	// $invoice_summary_id=$id;
	// $whr=" id=".$form_id;	
	// $DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);
	// $TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
	// $section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
	// $rs[$indx]['section_type']='GRID_ENTRY';		
	// $rs[$indx]['frmrpttemplatehdr_id']=$form_id;
	// $rs[$indx]['id']=0;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
	// //	$rs[$indx]['fields']=$DataFields.$setting['segments'];
	// $rs[$indx]['fields']=$DataFields;
	// $rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']."
	// 	where invoice_summary_id=".$invoice_summary_id;


	// $form_structure=$this->FrmRptModel->create_form($rs,$id);
	// $form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);

	// echo '<pre>';
	// print_r($form_structure);
	// echo '</pre>';
	
	//$id=12945;
	//$this->projectmodel->check_own_company_and_transfer($id);

	//$this->projectmodel->transaction_update($id);

				//CHECK AND TRANSFER STOCK TO OWN COMPANY			
	//$this->projectmodel->check_own_company_and_transfer($id);	
	

	// $id=572;
	// $form_detail_id=35;
	// $indx=0;
				
	// $form_id=$form_detail_id;
	// $whr=" id=".$form_id;	
	// $DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
	// $TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
	// $section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
	// $WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
	// $WhereCondition=$WhereCondition." and invoice_summary_id=".$id;

	// $rs[$indx]['section_type']='GRID_ENTRY';	
	// $rs[$indx]['frmrpttemplatehdr_id']=$form_id;
	// $rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
	// $rs[$indx]['fields']=$DataFields;
	// $rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
	// $resval=$this->FrmRptModel->create_report($rs,$id); 

		// echo '<pre>';
		// print_r($form_structure['header']);
		// echo '</pre>';


		// $form_summary_id=34;
		// $form_detail_id=35;
		// $id=0;	
		// $form_name='invoice_entry'	;				
		// $form_structure=$this->form_view($form_name,$id);
		//$header_id=1;
		//$this->projectmodel->transaction_update($header_id);

		$form_detail_id=35;
		$id=5;	
		$indx=0;
		
		$form_id=$form_detail_id;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
		$WhereCondition=$WhereCondition." and invoice_summary_id=".$id;

		$rs[$indx]['section_type']='GRID_ENTRY';	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
		

		$resval=$this->FrmRptModel->create_report($rs,$id); 
		 echo '<pre>';
		 print_r($resval);
		 echo '</pre>';

}	



public function print_documents($doc_type='',$cond=0)
{

	//https://stackoverflow.com/questions/8240472/printing-a-web-page-using-just-url-and-without-opening-new-window
	
	if($doc_type=='POS_INVOICE')
	{
			$this->load->library('zend');
			$this->zend->load('Zend/Barcode');
		
			$barcodetext=$cond;
			$image='BILL-'.$cond.'.png';
			$imageResource = Zend_Barcode::factory('code128', 
			'image', array('text'=>$barcodetext,'barThickWidth'=>6,'barThinWidth'=>2,
			'drawText' => false), array())->draw();

			imagepng($imageResource, 'uploads/'.$image);

			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$cond;		
			//$data_print['datatype']=$datatype;

			$report_path='accounts_management/report/invoice_print';
			$this->report_page_layout_display($report_path,$data_print);

			// $report_path='accounts_management/MIS_REPORTS/invoice_print_pos';
			// $this->report_page_layout_display($report_path,$data_print);
	}

	//A4 INVOICE
	if($doc_type=='INVOICE')
	{
		
			$this->load->library('zend');
			$this->zend->load('Zend/Barcode');

			$barcodetext=$cond;
			$image='BILL-'.$cond.'.png';
			$imageResource = Zend_Barcode::factory('code128', 
		   'image', array('text'=>$barcodetext,'barThickWidth'=>6,'barThinWidth'=>2,
		   'drawText' => false), array())->draw();
		   
			imagepng($imageResource, 'uploads/'.$image);
		
			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$cond;		
			//$data_print['datatype']=$datatype;
		
			$report_path='accounts_management/report/invoice_print_sell';
			$this->pdf->load_view($report_path, $data_print);			
			$this->pdf->render();
			$this->pdf->stream("uploads/invoice.pdf");				
			//write_file("uploads/invoice.pdf", $this->pdf->output());				
	}	

	
	if($doc_type=='BARCODE_PRINT')
	{
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$cnt=1;
		$sql="select * from  invoice_details where  invoice_summary_id =".$cond;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{
				$barcode=$row1->id;
				$image=$row1->id.'.png';					
			//DOCUMENT
			//https://docs.zendframework.com/zend-barcode/objects/								
			$barcodeOptions = array('text' => $barcode, 'barHeight'=> 16,'barThickWidth'=>6,'barThinWidth'=>2,'drawText' => false);				
			// No required options
			$rendererOptions = array();				
			// Draw the barcode in a new image,
			$imageResource = Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->draw();				 				 					 
			/* $imageResource = Zend_Barcode::factory('code128','image', array('text'=>$barcode,'barHeight' => 30,'drawText' => false), array())->draw();*/				 
				imagepng($imageResource, 'uploads/purchase_barcode/'.$image);	
				$cnt=$cnt+1;	
		}

			$report_path='accounts_management/report/print_purchase_barcode';
			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$cond;		
			$data_print['PRINTTYPE']=$doc_type;			
			$this->report_page_layout_display($report_path,$data_print);

	}	

	

}	


public function product_rate_master($datatype='',$cond=0)
{
	

	$return=$dtlist_total=$output=array();	

	$form_data1=json_decode(file_get_contents("php://input"));	
	$subtype=$form_data1->subtype;
	

	if($subtype=='PRODUCT_GROUP')
	{
		$id=$form_data1->id;

		$records="select * from misc_mstr where mstr_type='PRODUCT_GROUP' ";
		$records = $this->projectmodel->get_records_from_sql($records);
		foreach ($records as $fieldIndex=>$record)
		{	
			$dtlist_total['id']=$record->id;
			$dtlist_total['name']=$record->name;
			$dtlist_total['name_value']=$record->name_value;
			array_push($output,$dtlist_total);
		}
		
		 $return['group_list']=$output;
		$this->FrmRptModel->tranfer_data($return);			
	}

	if($subtype=='rate_list')
	{
		$id=$form_data1->id;

		//MAIN GROUP 
		$whr=" id=".$id;
		$main_grp_id=$this->projectmodel->GetSingleVal('parent_id','misc_mstr',$whr);

		$dtlist_total['GROUP_ID']=$id;

		//POTENCY 
		$potency="select * from misc_mstr where parent_id=".$main_grp_id." 
		 and mstr_type='POTENCY' and status='ACTIVE'";
		$potency = $this->projectmodel->get_records_from_sql($potency);
		foreach ($potency as $potencyIndex=>$pot)
		{			
			$dtlist_total['POTENCY_ID']=$pot->id;
			$dtlist_total['POTENCY_NAME']=$pot->name;

			$packs="select * from misc_mstr where parent_id=".$main_grp_id." 
			and mstr_type='PACK_SIZE' and status='ACTIVE'";
			$packs = $this->projectmodel->get_records_from_sql($packs);
			foreach ($packs as $packIndex=>$pack)
			{	
				$dtlist_total['PACK_ID']=$pack->id;
				$dtlist_total['PACK_NAME']=$pack->name;
				$product_rate_mstr_id=$RATE=$MRP='';

				$whr=" GROUP_ID=".$id." and POTENCY_ID=".$pot->id." and PACK_ID=".$pack->id;	
				$product_rate_mstr_id=$this->projectmodel->GetSingleVal('id','product_rate_mstr',$whr);
				$RATE=$this->projectmodel->GetSingleVal('RATE','product_rate_mstr',$whr);
				$MRP=$this->projectmodel->GetSingleVal('MRP','product_rate_mstr',$whr);

				$dtlist_total['id']=$product_rate_mstr_id;
				$dtlist_total['RATE']=$RATE;
				$dtlist_total['MRP']=$MRP;

				array_push($output,$dtlist_total);
			}
		}

		$return['header']=array('Potency','Pack Size','Rate','MRP');
		$return['body']=$output;
		$this->FrmRptModel->tranfer_data($return);			
	}
	

	if($subtype=='SAVE_DATA')
	{
		$save_details2['test_data']=$raw_data=$form_data1->raw_data;
		$raw_data=json_decode($raw_data);
		$raw_data=json_decode(json_encode($raw_data), true );	
		
		foreach($raw_data as $key1=>$raw_data1)
		 {
			$id=$raw_data1['id'];
			$save_data['GROUP_ID']=$raw_data1['GROUP_ID'];
			$save_data['POTENCY_ID']=$raw_data1['POTENCY_ID'];
			$save_data['PACK_ID']=$raw_data1['PACK_ID'];
			$save_data['RATE']=$raw_data1['RATE'];
			$save_data['MRP']=$raw_data1['MRP'];
			$this->projectmodel->save_records_model($id,'product_rate_mstr',$save_data);						
		 }	

	}


}	

public function experimental_form($datatype='',$cond=0)
{
	
	$validation_type=$return_msg='';
	$dtlist_total=$return_data=$setting=$rs=$resval=$form_structure=$output=array();	
	$company_id=$this->session->userdata('COMP_ID');
		
	$form_data1=json_decode(file_get_contents("php://input"));	
	$form_name=$form_data1->form_name;	//PARAMETERS		
	$subtype=$form_data1->subtype;
		

	if($form_name=='invoice_entry')
	{
			$form_summary_id=34;
			$form_detail_id=35;

			if($subtype=='view_list')
			{			
			
				$id=$form_data1->id;						
				$form_structure=$this->form_view($form_name,$id);
				$form_structure['header'][0]['fields'][0]['invoice_date']['Inputvalue']=date('Y-m-d');

				// $sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
				// from productmstr a, product_balance_companywise b 
				// where a.id=b.product_id and b.company_id=".$company_id;

				// if($id>0)
				// {
				// 	$sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
				// 	from productmstr a, product_balance_companywise b 
				// 	where a.id=b.product_id ";
				// 	$datafields_array =$this->projectmodel->get_records_from_sql($sql);
				// 	$form_structure["header"][1]['fields'][0]['product_id']['datafields']=
				// 	json_decode(json_encode($datafields_array), true);
				// }
				

				array_push($output,$form_structure);
				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			
			//save section
			if($subtype=='SAVE_DATA')
			{
				
				// $save_details2['test_data']=$form_data1->raw_data;
				// $this->projectmodel->save_records_model(1,'test_table',$save_details2);
				// $rs=$resval=$form_structure=$output=$save_details=array();
				// $whr=" id=1";	
				// $raw_data=$this->projectmodel->GetSingleVal('test_data','test_table',$whr);

				$raw_data=$form_data1->raw_data;
				$form_data=json_decode($raw_data);
				$headers=json_decode(json_encode($form_data[0]->header), true );
				$save_details=$this->FrmRptModel->create_save_array($headers);

				$VALID_STATUS='VALID';
				$invoice_no=$header_id=$id='';
										
				foreach($save_details as $key1=>$tables)
				{
					if($key1<2)
					{
						foreach($tables as $key2=>$fields)
						{
							
									$table_name=$key2;		
									$savedata=array();	
									$save_statue=true;

									foreach($fields as $key3=>$value)
									{
										//HERE REQUIRE CUSTOMIZATION
										if($key3=='id' && $table_name=='invoice_summary')
										{
											if($value>0)
											{$header_id=$value;}
											else 
											{$header_id='';}  											
										}
										else if ($key3<>'id' && $table_name=='invoice_summary')
										{$savedata[$key3]=$value;}
										else if ($key3=='id' && $table_name=='invoice_details')
										{if($value>0){$id=$value;}else {$id='';}   }
										else if ($key3=='invoice_summary_id' && $table_name=='invoice_details')
										{$savedata[$key3]=$header_id; }
										else 
										{
											$savedata[$key3]=$value; 
											//if($savedata['product_id']==0){$save_statue=false;}

										}

									}

									if($header_id=='')
									{										
										if($savedata['invoice_date']=='')
										{$savedata['invoice_date']=date('Y-m-d');}
										$tran_no=$this->projectmodel->tran_no_generate('SALE',$savedata['invoice_date']);
										$savedata['finyr']=$tran_no['finyr'];
										$savedata['srl']=$tran_no['srl'];
										$savedata['invoice_no']=$tran_no['srl'].'/'.$savedata['finyr'];
										$savedata['status']='SALE';
										$savedata['invoice_time']=date('H:i');	
																		
									}

									$savedata['status']='SALE';
									

									//HEADER SECTION
									if($table_name=='invoice_summary')
									{																				
										$savedata['emp_name']=$this->session->userdata('login_name');
										$savedata['emp_id']=$this->session->userdata('login_emp_id');
										$savedata['entry_from_software']='NEW';
										$savedata['company_id']=$this->session->userdata('COMP_ID');		
										
										
										$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
										if($key1==0 && $header_id=='')
										{
											

											$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}		
										else if($key1==0 && $header_id>0)
										{
											$server_msg="Record has been Updated Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}										
										
									}

									if($table_name=='invoice_details')
									{
										if($savedata['product_id']>0)
										{$this->projectmodel->save_records_model($id,$table_name,$savedata);}
									}

						}
					}
					

				}

				
							
				$return_data['id_header']=$header_id;
				$return_data['server_msg']=$server_msg;		
				$return_data['invoice_no']=$invoice_no;

				$this->projectmodel->transaction_update($header_id);
				

				$this->projectmodel->send_json_output($return_data);


			}	

			if($subtype=='FINAL_SUBMIT')
			{
				$id=$form_data1->id;	
				
				$this->projectmodel->transaction_update($id);

				//CHECK AND TRANSFER STOCK TO OWN COMPANY			
				$this->projectmodel->check_own_company_and_transfer($id);	
				
				$return_data['id_header']=$id;
				$return_data['server_msg']='Final Submit Completed';		
				

				$this->projectmodel->send_json_output($return_data);

				//$this->print_documents('POS_INVOICE',$id);	

			}


			if($subtype=='MAIN_GRID')
			{
				$startdate=$form_data1->startdate;
				$enddate=$form_data1->enddate;

				$indx=0;
				$id=$form_id=$form_summary_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_date between '$startdate' and '$enddate' 
				and company_id=".$company_id;
								
				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." 
				from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist')
			{
				
				$id=$form_data1->id;	
				$indx=0;
				
				$form_id=$form_detail_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_summary_id=".$id;

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				
				$resval=$this->FrmRptModel->create_form($rs,$id); 
				

				foreach($resval['header'][0]['fields'] as $key1=>$flds)
				{
						foreach($flds as $key2=>$fld)
						{
							
							$save_details[$fld['InputName']]['LabelName']=$fld['LabelName'];          
							$save_details[$fld['InputName']]['Inputvalue']=$fld['Inputvalue'];
							$save_details[$fld['InputName']]['Inputvalue_id']=$fld['Inputvalue_id'];
							//$save_details[$fld['InputName']]['InputType']=$fld['InputType'];
							$save_details[$fld['InputName']]['InputType']='text';
							
							 if($key2=='id')
							 {
								$save_details[$fld['InputName']]['InputType']='hidden';
							 }

							// if($key2=='product_id')
							// {
							// 	$whr=" id=".$fld['Inputvalue_id'];	
							// 	$pname=$this->projectmodel->GetSingleVal('productname','productmstr',$whr);

							// 	$save_details[$fld['InputName']]['LabelName']=$fld['LabelName'];    
							// 	$save_details[$fld['InputName']]['Inputvalue']=$pname;
							// 	$save_details[$fld['InputName']]['Inputvalue_id']=$fld['Inputvalue_id'];
							// 	$save_details[$fld['InputName']]['InputType']=$fld['InputType'];
							// }

						}							
						if($save_details['id']['Inputvalue']>0){array_push($output,$save_details);}
				}

				$return_data['header']=$output;

				// $resval=$this->FrmRptModel->create_report($rs,$id); 
				// $return_data['header']=$resval;


				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($return_data);

			}
	
			//DONE
			if($subtype=='dtlist_view')
			{	

				$someArray=array();

				$detail_id=$form_data1->id;
				$someArray = json_decode($form_data1->raw_data, true);

				$indx=1;
				foreach($someArray[0]['header'][$indx]['fields'][0] as $key1=>$values)
				{
										
					if($someArray[0]['header'][$indx]['fields'][0][$key1]['datafields']<>'')
					{
						$whr=" id=".$detail_id;	
						$Inputvalue_id=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
						$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue_id']=$Inputvalue_id;

						$MainTable=$someArray[0]['header'][$indx]['fields'][0][$key1]['MainTable'];
						$LinkField=$someArray[0]['header'][$indx]['fields'][0][$key1]['LinkField'];
						
						$whr=" id=".$Inputvalue_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($LinkField,$MainTable,$whr);
						$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;

					}
					else
					{

						$whr=" id=".$detail_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
						$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;

						// if($key1=='product_id')
						// {
						// 	$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue_id']=$Inputvalue;
						// 	$whr=" id=".$Inputvalue;	
						// 	$pname=$this->projectmodel->GetSingleVal('productname','productmstr',$whr);

						// 	$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;
						// }
						// else
						// {$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;}


					}
										
							
				}

				$this->FrmRptModel->tranfer_data($someArray);

			}

			//DONE
			if($subtype=='dtlist_total')
			{
				
				$id=$form_data1->id;	

				$dtlist_total['id']=$id;
				$records="select * from invoice_summary where id=".$id;
				$records = $this->projectmodel->get_records_from_sql($records);
				foreach ($records as $fieldIndex=>$record)
				{	
						$dtlist_total['total_amt']=$record->total_amt;
						$dtlist_total['tot_discount']=$record->tot_discount;
						$dtlist_total['Taxable_Amt']=$record->total_amt-$record->tot_discount;
						$dtlist_total['totvatamt']=$record->totvatamt;
						$dtlist_total['Net_Amt']=$record->grandtot;

						//$dtlist_total['total_paid']=$record->total_paid;
						//$dtlist_total['total_due']=$record->total_due;

				}
				$output['header']=$dtlist_total;

				$this->FrmRptModel->tranfer_data($output);				

			}

			if($subtype=='other_search')
			{			
										
					$someArray=array();

					$header_index=$form_data1->header_index;
					$field_index=$form_data1->field_index;
					$searchelement=$form_data1->searchelement;
					$someArray = json_decode($form_data1->raw_data, true);

					if($searchelement=='tbl_party_id')
					{							
						$tbl_party_val=$someArray[0]['header'][0]['fields'][0]['tbl_party_id']['Inputvalue_id'];
						$tbl_party_id=$someArray[0]['header'][0]['fields'][0]['tbl_party_id']['Inputvalue_id'];
						


						//$someArray[0]["header"][1]['fields'][0]['product_id']['Inputvalue']=$company_id;
						// $sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
						// from productmstr a, product_balance_companywise b 
						// where a.id=b.product_id and a.company_id=".$company_id;

						// $sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
						// from productmstr a, product_balance_companywise b 
						// where a.id=b.product_id and b.company_id=".$company_id;
						// $datafields_array =$this->projectmodel->get_records_from_sql($sql);
						// $someArray[0]["header"][1]['fields'][0]['product_id']['datafields']=
						// json_decode(json_encode($datafields_array), true);

						// $this->FrmRptModel->tranfer_data($someArray);

					}

					if($searchelement=='main_group_id')
					{	
						
						$main_group_val=$someArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue'];

						$whr="Status='MAIN_PRODUCT_GROUP' and FieldID='".trim($main_group_val)."'";
						$main_group_id=$this->projectmodel->GetSingleVal('id','frmrptgeneralmaster',$whr);
						if($main_group_id==0)
						{$main_group_id=$someArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue_id'];}						
						$MAIN_PRODUCT_GROUP=$this->projectmodel->GetSingleVal('FieldVal','frmrptgeneralmaster','id='.$main_group_id);
						
						$someArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue_id']=$main_group_id;
						//$main_group_id=$this->projectmodel->GetSingleVal('parent_id','misc_mstr','id='.$product_group_id);
						
						$product_group_id='0';
						$records="select * from misc_mstr 
						where  parent_id=".$main_group_id." and mstr_type='PRODUCT_GROUP' and status='ACTIVE'";			
						$records = $this->projectmodel->get_records_from_sql($records);	
						foreach ($records as $record)
						{$product_group_id=$product_group_id.','.$record->id;}

						//$someArray[0]['header'][1]['fields'][0]['potency_id']['Inputvalue']=$MAIN_PRODUCT_GROUP;

						// $someArray[0]['header'][1]['fields'][0]['batchno']['InputType']='text';
						// $someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
						// $someArray[0]['header'][1]['fields'][0]['no_of_dose']['InputType']='text';
						
						//$someArray[0]["header"][1]['fields'][0]['product_id']['Inputvalue']=$product_group_id;

						//$someArray[0]['header'][1]['fields'][0]['product_id']['Inputvalue']=$product_group_id;

						
						$records="select * from frmrpttemplatedetails 
						where  frmrpttemplatehdrID=".$form_detail_id." 	and InputType='hidden' ";			
						$records = $this->projectmodel->get_records_from_sql($records);	
						foreach ($records as $record)
						{
							$someArray[0]['header'][1]['fields'][0][$record->InputName]['InputType']='hidden';
							$someArray[0]['header'][1]['fields'][0][$record->InputName]['input_id_index']=9000;
						}

						

						//UPDATE PRODUCT
						 $sql="select a.id FieldID,TRIM(a.productname) FieldVal from productmstr a,misc_mstr b
						 where a.group_id in (".$product_group_id.") and  a.group_id=b.id 
						 and b.mstr_type='PRODUCT_GROUP' and b.status='ACTIVE'";
						 $datafields_array =$this->projectmodel->get_records_from_sql($sql);
						 $someArray[0]["header"][1]['fields'][0]['product_id']['datafields']=
						 json_decode(json_encode($datafields_array), true);

						 //UPDATE potency_id
						 if($MAIN_PRODUCT_GROUP=='MOTHER_TINCTURE' || $MAIN_PRODUCT_GROUP=='TRITURATION' || 
						  $MAIN_PRODUCT_GROUP=='BIOCHEMIC' || $MAIN_PRODUCT_GROUP=='DILUTION' 
						  || $MAIN_PRODUCT_GROUP=='WATER') 
						 {
							
							 $sql="select id FieldID,name FieldVal from misc_mstr
							 where parent_id=".$main_group_id." and mstr_type='POTENCY' and status='ACTIVE' ";
							 $datafields_array =$this->projectmodel->get_records_from_sql($sql);
							 $someArray[0]["header"][1]['fields'][0]['potency_id']['datafields']=
							 json_decode(json_encode($datafields_array), true);							
						 }	


						 //UPDATE PACK
						 if($MAIN_PRODUCT_GROUP=='MOTHER_TINCTURE' || $MAIN_PRODUCT_GROUP=='TRITURATION' || 
						  $MAIN_PRODUCT_GROUP=='BIOCHEMIC' || $MAIN_PRODUCT_GROUP=='DILUTION' 
						  || $MAIN_PRODUCT_GROUP=='WATER' || $MAIN_PRODUCT_GROUP=='SUGAR_OF_MILK') 
						 {
							 $sql="select id FieldID,name FieldVal from misc_mstr
							 where parent_id=".$main_group_id." and mstr_type='PACK_SIZE' and status='ACTIVE' ";
							 $datafields_array =$this->projectmodel->get_records_from_sql($sql);
							 $someArray[0]["header"][1]['fields'][0]['pack_id']['datafields']=
							 json_decode(json_encode($datafields_array), true);							
						 }	





						//FIELD OPEN OR HIDE GROUP WISE

						if($MAIN_PRODUCT_GROUP=='MOTHER_TINCTURE' ||
						 $MAIN_PRODUCT_GROUP=='TRITURATION' || 
						 $MAIN_PRODUCT_GROUP=='BIOCHEMIC' ) 
						{
							// $someArray[0]['header'][1]['fields'][0]['batchno']['InputType']='hidden';
							// $someArray[0]['header'][1]['fields'][0]['batchno']['input_id_index']=9000;		
							
							// $someArray[0]['header'][1]['fields'][0]['no_of_dose']['InputType']='hidden';
							// $someArray[0]['header'][1]['fields'][0]['no_of_dose']['input_id_index']=9000;
							
							$someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['pack_id']['InputType']='text';	
							$someArray[0]['header'][1]['fields'][0]['label_print']['Inputvalue']='Y';	
							$someArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue']=1;					
							
						}
						else if($MAIN_PRODUCT_GROUP=='DILUTION')
						{						
							$someArray[0]['header'][1]['fields'][0]['product_Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['pack_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['label_print']['Inputvalue']='Y';
							$someArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue']=1;	
							
						}

						else if($MAIN_PRODUCT_GROUP=='WATER')
						{	
							$someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['pack_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['no_of_dose']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['label_print']['Inputvalue']='Y';
							$someArray[0]['header'][1]['fields'][0]['no_of_dose']['Inputvalue']=1;
							$someArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue']=1;			
						}
						else if($MAIN_PRODUCT_GROUP=='SUGAR_OF_MILK')
						{
							$someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['pack_id']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['no_of_dose']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['dose_Synonym']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['label_print']['Inputvalue']='Y';	
							$someArray[0]['header'][1]['fields'][0]['no_of_dose']['Inputvalue']=1;	
							$someArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue']=1;						

						}
						else if($MAIN_PRODUCT_GROUP=='PATENT')
						{
							$someArray[0]['header'][1]['fields'][0]['batchno']['InputType']='text';
							$someArray[0]['header'][1]['fields'][0]['label_print']['Inputvalue']='';
							$someArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue']=1;	
						}
						else 
						{
							// $someArray[0]['header'][1]['fields'][0]['batchno']['InputType']='text';
							// $someArray[0]['header'][1]['fields'][0]['no_of_dose']['InputType']='text';
							// $someArray[0]['header'][1]['fields'][0]['potency_id']['InputType']='text';
						}

						$someArray=$this->FrmRptModel->re_arrange_input_index_type2($someArray);

						$this->FrmRptModel->tranfer_data($someArray);

					}	

					if($searchelement=='product_id')
					{							
						
						$product_id=$someArray[0]['header'][1]['fields'][0]['product_id']['Inputvalue_id'];
						$whr="id=".$product_id."";
						$product_group_id=$this->projectmodel->GetSingleVal('group_id','productmstr',$whr);
					
						$someArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue_id']=$product_group_id;
						$someArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue']=$product_group_id;

						//PRODUCT WISE PARTY WISE LAST SALE RATE .

						// $rs = "select max(a.id) maxid from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id and 
						// b.product_id=".$product_id."  and a.tbl_party_id=".$tbl_party_id." 	and a.company_id=".$company_id ;
						// $rs = $this->projectmodel->get_records_from_sql($rs);		
						// $maxid=$rs[0]->maxid;

						// $whr="product_id=".$product_id." and invoice_summary_id=".$maxid;
						// $rate=$this->projectmodel->GetSingleVal('rate','invoice_details',$whr);
						// $disc1=$this->projectmodel->GetSingleVal('disc_per','invoice_details',$whr);
						// $disc2=$this->projectmodel->GetSingleVal('disc_per2','invoice_details',$whr);
					
						// $tbl_party_id=$this->projectmodel->GetSingleVal('tbl_party_id','invoice_summary','id='.$maxid);
						// $invoice_date=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary','id='.$maxid);
						// $party_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers','id='.$tbl_party_id);
			
						// $someArray[0]["header"][1]['fields'][0]['product_id']['validation_msg']=
						// 'Last Sale To '.$party_name.' Date:'.$invoice_date.' @ Rs'.$rate.' Disc1= '.$disc1.'% Disc2= '.$disc2.'%';
							
						//RATE VALIDATION

						// $whr=" id=".$product_id;	
						// $someArray[0]["header"][1]['fields'][0]['Synonym']['Inputvalue']=
						// $this->projectmodel->GetSingleVal('Synonym','productmstr',$whr);

						// $tax_ledger_id=$this->projectmodel->GetSingleVal('tax_ledger_id','productmstr',$whr);
						// $someArray[0]["header"][1]['fields'][0]['tax_ledger_id']['Inputvalue_id']=$tax_ledger_id;

						// $whr=" id=".$tax_ledger_id;
						// $tax_ledger_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr);
						// $someArray[0]["header"][1]['fields'][0]['tax_ledger_id']['Inputvalue']=$tax_ledger_name;

					
						// $sql = "select b.batchno FieldID,CONCAT(b.batchno, '=')  as FieldVal,b.rackno RackNo,b.qty_available AvailbQnty 
						// ,(b.taxable_amt+b.cgst_amt+b.sgst_amt+b.igst_amt)/b.qnty PurRate ,
						// b.srate Rate ,b.mrp MRP,b.exp_monyr ExpDate ,b.mfg_monyr MfgDate,b.id PID
						// from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id and 
						// (a.status='OPEN_BALANCE' or a.status='PURCHASE' or a.status='SALE_RTN') 
						// and b.product_id=".$product_id." and a.company_id=".$company_id."  order by b.qty_available desc ";
						// $datafields_array =$this->projectmodel->get_records_from_sql($sql);
						// $someArray[0]["header"][1]['fields'][0]['batchno']['datafields']=
						// json_decode(json_encode($datafields_array), true);


						$this->FrmRptModel->tranfer_data($someArray);

					}


					if($searchelement=='potency_id' || $searchelement=='pack_id' || $searchelement=='no_of_dose' )
					{	
						$product_id=$someArray[0]['header'][1]['fields'][0]['product_id']['Inputvalue_id'];
						$whr="id=".$product_id."";
						$product_group_id=$this->projectmodel->GetSingleVal('group_id','productmstr',$whr);
						
						$someArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue_id']=$product_group_id;
						$someArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue']=$product_group_id;

						$main_group_id=$this->projectmodel->GetSingleVal('parent_id','misc_mstr','id='.$product_group_id);
						$MAIN_PRODUCT_GROUP=$this->projectmodel->GetSingleVal('FieldVal','frmrptgeneralmaster','id='.$main_group_id);

						$potency_id=$someArray[0]['header'][1]['fields'][0]['potency_id']['Inputvalue_id'];
						$pack_id=$someArray[0]['header'][1]['fields'][0]['pack_id']['Inputvalue_id'];
						$no_of_dose=$someArray[0]['header'][1]['fields'][0]['no_of_dose']['Inputvalue'];

						// $product_name=$this->projectmodel->GetSingleVal('productname','productmstr','id='.$product_id);
						// $potency_name=$this->projectmodel->GetSingleVal('name','misc_mstr','id='.$potency_id);
						// $pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr','id='.$pack_id);

						
						//  $msg="Product id:".$product_id." PGROUP: ".$group_id.
						//  " potency_id: ".$potency_id." pack_id:".$pack_id;
						 //$someArray[0]['header'][1]['fields'][0]['Synonym']['Inputvalue']=trim($msg);
						
						if($MAIN_PRODUCT_GROUP=='MOTHER_TINCTURE' ||
						 $MAIN_PRODUCT_GROUP=='TRITURATION' || 
						 $MAIN_PRODUCT_GROUP=='BIOCHEMIC' || 
						 $MAIN_PRODUCT_GROUP=='DILUTION') 
						{
							$whr="GROUP_ID=".$product_group_id." 
							and POTENCY_ID=".$potency_id." and PACK_ID=".$pack_id;
							$rate=$this->projectmodel->GetSingleVal('RATE','product_rate_mstr',$whr);	
							$mrp=$this->projectmodel->GetSingleVal('MRP','product_rate_mstr',$whr);

							$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=$rate;
							$someArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=$mrp;

							
						}
						else if($MAIN_PRODUCT_GROUP=='WATER')
						{
							$whr="GROUP_ID=".$product_group_id." 
							and POTENCY_ID=".$potency_id." and PACK_ID=".$pack_id;
							$rate=$this->projectmodel->GetSingleVal('RATE','product_rate_mstr',$whr);								
							$mrp=$this->projectmodel->GetSingleVal('MRP','product_rate_mstr',$whr);

							$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=$rate;
							$someArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=$mrp;		
							
							
							if($no_of_dose>0)
							{
								$whr=" parent_id=".$main_group_id." 
								and name='".$no_of_dose."' and mstr_type='DOSE_DISCOUNT' and status='ACTIVE' ";
								$discount_per=$this->projectmodel->GetSingleVal('name_value','misc_mstr',$whr);	
								$rate=($rate-round(($rate*$discount_per/100)))*$no_of_dose;								
								$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=$rate;	
							}

						}
						else if($MAIN_PRODUCT_GROUP=='SUGAR_OF_MILK')
						{
							$whr="GROUP_ID=".$product_group_id." and  PACK_ID=".$pack_id;
							$rate=$this->projectmodel->GetSingleVal('RATE','product_rate_mstr',$whr);								
							$mrp=$this->projectmodel->GetSingleVal('MRP','product_rate_mstr',$whr);

							$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=$rate;
							$someArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=$mrp;

							if($no_of_dose>0)
							{
								$whr=" parent_id=".$main_group_id." 
								and name='".$no_of_dose."' and mstr_type='DOSE_DISCOUNT' and status='ACTIVE' ";
								$discount_per=$this->projectmodel->GetSingleVal('name_value','misc_mstr',$whr);	
								$rate=($rate-round(($rate*$discount_per/100)))*$no_of_dose;								
								$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=$rate;	
							}
						}

						$this->FrmRptModel->tranfer_data($someArray);

					}
					

					//$searchelement=='batchno' || 
					if($searchelement=='rate' || $searchelement=='disc_per' || $searchelement=='disc_per2')
					{
					
						$product_group_id=$someArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue_id'];
						$main_group_id=$this->projectmodel->GetSingleVal('parent_id','misc_mstr','id='.$product_group_id);
						$MAIN_PRODUCT_GROUP=$this->projectmodel->GetSingleVal('FieldVal','frmrptgeneralmaster','id='.$main_group_id);
					
						if($MAIN_PRODUCT_GROUP=='PATENT')
						{

							$product_id=$someArray[0]['header'][1]['fields'][0]['product_id']['Inputvalue_id'];
							$batchno=$someArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];

							$PURCHASEID=$someArray[0]['header'][1]['fields'][0]['PURCHASEID']['Inputvalue'];
							$rate=$someArray[0]['header'][1]['fields'][0]['rate']['Inputvalue'];
							$disc_per=$someArray[0]['header'][1]['fields'][0]['disc_per']['Inputvalue'];
							$disc_per2=$someArray[0]['header'][1]['fields'][0]['disc_per2']['Inputvalue'];

							$price_after_disc1=$rate -($rate*$disc_per/100) ;
							$disc_per2_amt=$price_after_disc1*$disc_per2/100;

							$effective_amt=$price_after_disc1-$disc_per2_amt;

							$whr=" id=".$PURCHASEID;
							$taxable_amt=$this->projectmodel->GetSingleVal('taxable_amt','invoice_details',$whr);
							$qnty=$this->projectmodel->GetSingleVal('qnty','invoice_details',$whr);
							$purchase_rate=ceil($taxable_amt/$qnty);	
							
							$profit=$effective_amt-$purchase_rate;

							if($profit<=0)
							{
								$return_msg='Your Purchase Rate:'.$purchase_rate.' | Your Sale rate '.$effective_amt;
								$validation_type='NOT_OK';
							}
							else
							{	
								$profit=$effective_amt-$purchase_rate;
								$return_msg='You are in profit.Which is : '.$profit;							
							}
							
							$someArray[0]["header"][1]['fields'][0]['batchno']['validation_type']=$validation_type;						
							$someArray[0]["header"][1]['fields'][0]['batchno']['validation_msg']=$return_msg;
							$this->FrmRptModel->tranfer_data($someArray);

							}
						

					}	


			 }

	}


	if($form_name=='purchase_entry')
	{
			$form_summary_id=36;
			$form_detail_id=37;

			if($subtype=='view_list')
			{			
				
				// $products = "select * from productmstr ";
				// $products = $this->projectmodel->get_records_from_sql($products);
				// foreach ($products as $product)
				// {

				// 	$companys = "select * from company_details ";
				// 	$companys = $this->projectmodel->get_records_from_sql($companys);
				// 	foreach ($companys as $company)
				// 	{
				// 		$comp['product_id']=$product->id;
				// 		$comp['company_id']=$company->id;
				// 		$this->projectmodel->save_records_model('','product_balance_companywise',$comp);					
				// 	}
				// }

				$id=$form_data1->id;						
				$form_structure=$this->form_view($form_name,$id);
				$form_structure['header'][0]['fields'][0]['invoice_date']['Inputvalue']=date('Y-m-d');

				// $sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
				// from productmstr a, product_balance_companywise b 
				// where a.id=b.product_id and b.company_id=".$company_id;

				if($id>0)
				{
					$sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
					from productmstr a, product_balance_companywise b 
					where a.id=b.product_id ";
					$datafields_array =$this->projectmodel->get_records_from_sql($sql);
					$form_structure["header"][1]['fields'][0]['product_id']['datafields']=
					json_decode(json_encode($datafields_array), true);
				}
				

				array_push($output,$form_structure);
				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			

			//save section
			if($subtype=='SAVE_DATA')
			{
				//$this->projectmodel->save_data($form_data1->raw_data,$form_name);


				// $save_details2['test_data']=$form_data1->raw_data;
				// $this->projectmodel->save_records_model(1,'test_table',$save_details2);
				// $rs=$resval=$form_structure=$output=$save_details=array();
				// $whr=" id=1";	
				// $raw_data=$this->projectmodel->GetSingleVal('test_data','test_table',$whr);

				$raw_data=$form_data1->raw_data;
				$form_data=json_decode($raw_data);
				$headers=json_decode(json_encode($form_data[0]->header), true );
				$save_details=$this->FrmRptModel->create_save_array($headers);

				$VALID_STATUS='VALID';
				$invoice_no=$header_id=$id='';
						
				foreach($save_details as $key1=>$tables)
				{
					if($key1<2)
					{
						foreach($tables as $key2=>$fields)
						{
							
									$table_name=$key2;		
									$savedata=array();	
									$save_statue=true;

									foreach($fields as $key3=>$value)
									{
										//HERE REQUIRE CUSTOMIZATION
										if($key3=='id' && $table_name=='invoice_summary')
										{
											if($value>0)
											{$header_id=$value;}
											else 
											{$header_id='';}  											
										}
										else if ($key3<>'id' && $table_name=='invoice_summary')
										{$savedata[$key3]=$value;}
										else if ($key3=='id' && $table_name=='invoice_details')
										{if($value>0){$id=$value;}else {$id='';}   }
										else if ($key3=='invoice_summary_id' && $table_name=='invoice_details')
										{$savedata[$key3]=$header_id; }
										else 
										{
											$savedata[$key3]=$value; 
											//if($savedata['product_id']==0){$save_statue=false;}
										}

									}

									if($header_id=='')
									{										
										// if($savedata['invoice_date']=='')
										// {$savedata['invoice_date']=date('Y-m-d');}
										// $tran_no=$this->projectmodel->tran_no_generate('SALE',$savedata['invoice_date']);
										// $savedata['finyr']=$tran_no['finyr'];
										// $savedata['srl']=$tran_no['srl'];
										// $savedata['invoice_no']='BILL/'.$tran_no['srl'].'/'.$savedata['finyr'];
									}

									$savedata['status']='PURCHASE';
									//HEADER SECTION
									if($table_name=='invoice_summary')
									{																				
										$savedata['emp_name']=$this->session->userdata('login_name');
										$savedata['emp_id']=$this->session->userdata('login_emp_id');
										$savedata['invoice_time']=date('H:i');
										$savedata['company_id']=$this->session->userdata('COMP_ID');	
										$savedata['entry_from_software']='NEW';
										
										$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
										if($key1==0 && $header_id=='')
										{
											
											$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}		
										else if($key1==0 && $header_id>0)
										{
											$server_msg="Record has been Updated Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}										
										
									}

									if($table_name=='invoice_details')
									{
										if($savedata['product_id']>0)
										{$this->projectmodel->save_records_model($id,$table_name,$savedata);}
									}

						}
					}
					

				}

				
							
				$return_data['id_header']=$header_id;
				$return_data['server_msg']=$server_msg;		
				$return_data['invoice_no']=$invoice_no;

				//$this->projectmodel->update_transactions($header_id,$form_name);
				

				$this->projectmodel->send_json_output($return_data);


			}	

			if($subtype=='FINAL_SUBMIT')
			{
				$id=$form_data1->id;	
				
				$this->projectmodel->transaction_update($id);

				//CHECK AND TRANSFER STOCK TO OWN COMPANY			
				//$this->projectmodel->check_own_company_and_transfer($id);	
				
				$return_data['id_header']=$id;
				$return_data['server_msg']='Final Submit Completed';
				$this->projectmodel->send_json_output($return_data);
			

			}


			if($subtype=='MAIN_GRID')
			{
				$startdate=$form_data1->startdate;
				$enddate=$form_data1->enddate;

				$indx=0;
				$id=$form_id=$form_summary_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_date between '$startdate' and '$enddate' ";
								
				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist')
			{
				
				$id=$form_data1->id;	
				$indx=0;
				
				$form_id=$form_detail_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_summary_id=".$id;

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				
				$resval=$this->FrmRptModel->create_form($rs,$id); 

				foreach($resval['header'][0]['fields'] as $key1=>$flds)
				{
						foreach($flds as $key2=>$fld)
						{
							$save_details[$fld['InputName']]['LabelName']=$fld['LabelName'];          
							$save_details[$fld['InputName']]['Inputvalue']=$fld['Inputvalue'];
							$save_details[$fld['InputName']]['Inputvalue_id']=$fld['Inputvalue_id'];

							if($key2=='product_id')
							{
								$whr=" id=".$fld['Inputvalue_id'];	
								$pname=$this->projectmodel->GetSingleVal('productname','productmstr',$whr);

								$save_details[$fld['InputName']]['LabelName']=$fld['LabelName'];    
								$save_details[$fld['InputName']]['Inputvalue']=$pname;
								$save_details[$fld['InputName']]['Inputvalue_id']=$fld['Inputvalue_id'];
							}

						}							
						if($save_details['id']['Inputvalue']>0){array_push($output,$save_details);}
				}
				//$return_data['header']=$resval;		

				$return_data['header']=$output;

				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($return_data);

			}
	
			//DONE
			if($subtype=='dtlist_view')
			{	

				$someArray=array();

				$detail_id=$form_data1->id;
				$someArray = json_decode($form_data1->raw_data, true);


				 $indx=1;
				
				//  $someArray[0]['header'][$indx]['fields'][0]['id']['Inputvalue_id']=$detail_id;
				//  $someArray[0]['header'][$indx]['fields'][0]['product_id']['Inputvalue']=$detail_id;
				//  $someArray[0]['header'][$indx]['fields'][0]['batchno']['Inputvalue']=$detail_id;

				//id,product_id,batchno,mrp,qnty,rate,subtotal,disc_per,disc_per2,disc_amt,taxable_amt,tax_ledger_id,taxamt,net_amt

				 $records="select * from invoice_details where id=".$detail_id;
				 $records = $this->projectmodel->get_records_from_sql($records);
				 foreach ($records as $record)
				 {

					$someArray[0]['header'][$indx]['fields'][0]['id']['Inputvalue_id']=$record->id;
					$someArray[0]['header'][$indx]['fields'][0]['product_id']['Inputvalue_id']=$record->product_id;
					
					$whr="id=".$record->product_id;
					$val=$this->projectmodel->GetSingleVal('productname','productmstr',$whr);					
					$someArray[0]['header'][$indx]['fields'][0]['product_id']['Inputvalue']=$val;

					$someArray[0]['header'][$indx]['fields'][0]['batchno']['Inputvalue']=$record->batchno;
					$someArray[0]['header'][$indx]['fields'][0]['mrp']['Inputvalue']=$record->mrp;
					$someArray[0]['header'][$indx]['fields'][0]['qnty']['Inputvalue']=$record->qnty;
					$someArray[0]['header'][$indx]['fields'][0]['rate']['Inputvalue']=$record->rate;
					$someArray[0]['header'][$indx]['fields'][0]['subtotal']['Inputvalue']=$record->subtotal;

				 }



				// $indx=1;
				// foreach($someArray[0]['header'][$indx]['fields'][0] as $key1=>$values)
				// {
										
				// 	if($someArray[0]['header'][$indx]['fields'][0][$key1]['datafields']<>'')
				// 	{
				// 		$whr=" id=".$detail_id;	
				// 		$Inputvalue_id=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
				// 		$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue_id']=$Inputvalue_id;

				// 		$MainTable=$someArray[0]['header'][$indx]['fields'][0][$key1]['MainTable'];
				// 		$LinkField=$someArray[0]['header'][$indx]['fields'][0][$key1]['LinkField'];
						
				// 		$whr=" id=".$Inputvalue_id;	
				// 		$Inputvalue=$this->projectmodel->GetSingleVal($LinkField,$MainTable,$whr);
				// 		$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;

				// 	}
				// 	else
				// 	{

				// 		$whr=" id=".$detail_id;	
				// 		$Inputvalue=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
				// 		$someArray[0]['header'][$indx]['fields'][0][$key1]['Inputvalue']=$Inputvalue;
				// 	}
										
							
				// }

				$this->FrmRptModel->tranfer_data($someArray);

			}

			//DONE
			if($subtype=='dtlist_total')
			{
				
				$id=$form_data1->id;	

				$dtlist_total['id']=$id;
				$records="select * from invoice_summary where id=".$id;
				$records = $this->projectmodel->get_records_from_sql($records);
				foreach ($records as $fieldIndex=>$record)
				{	
						$dtlist_total['total_amt']=$record->total_amt;
						$dtlist_total['tot_discount']=$record->tot_discount;
						$dtlist_total['Taxable_Amt']=$record->total_amt-$record->tot_discount;
						$dtlist_total['totvatamt']=$record->totvatamt;
						$dtlist_total['Net_Amt']=$record->grandtot;

						//$dtlist_total['total_paid']=$record->total_paid;
						//$dtlist_total['total_due']=$record->total_due;

				}
				$output['header']=$dtlist_total;

				$this->FrmRptModel->tranfer_data($output);				

			}

			if($subtype=='other_search')
			{			
										
					$someArray=array();

					$header_index=$form_data1->header_index;
					$field_index=$form_data1->field_index;
					$searchelement=$form_data1->searchelement;
					$someArray = json_decode($form_data1->raw_data, true);


					if($searchelement=='tbl_party_id')
					{							
						
						$tbl_party_id=$someArray[0]['header'][0]['fields'][0]['tbl_party_id']['Inputvalue_id'];
					
						// $sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
						// from productmstr a, product_balance_companywise b 
						// where a.id=b.product_id and a.company_id=".$company_id;
						//$company_id=1;

						$sql="select a.id FieldID,a.productname FieldVal,b.available_qnty,a.Synonym,a.tax_ledger_id
						from productmstr a, product_balance_companywise b 
						where a.id=b.product_id and b.company_id=".$company_id;
						$datafields_array =$this->projectmodel->get_records_from_sql($sql);
						$someArray[0]["header"][1]['fields'][0]['product_id']['datafields']=
						json_decode(json_encode($datafields_array), true);

						$this->FrmRptModel->tranfer_data($someArray);

					}

					if($searchelement=='product_id')
					{							
						
						$product_id=$someArray[0]['header'][1]['fields'][0]['product_id']['Inputvalue_id'];
						$tbl_party_id=$someArray[0]['header'][0]['fields'][0]['tbl_party_id']['Inputvalue_id'];

						//PRODUCT WISE PARTY WISE LAST SALE RATE .

						// $rs = "select max(a.id) maxid from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id and 
						// b.product_id=".$product_id."  and a.tbl_party_id=".$tbl_party_id." 	and a.company_id=".$company_id ;
						// $rs = $this->projectmodel->get_records_from_sql($rs);		
						// $maxid=$rs[0]->maxid;

						// $whr="product_id=".$product_id." and invoice_summary_id=".$maxid;
						// $rate=$this->projectmodel->GetSingleVal('rate','invoice_details',$whr);
						// $disc1=$this->projectmodel->GetSingleVal('disc_per','invoice_details',$whr);
						// $disc2=$this->projectmodel->GetSingleVal('disc_per2','invoice_details',$whr);
					
						// $tbl_party_id=$this->projectmodel->GetSingleVal('tbl_party_id','invoice_summary','id='.$maxid);
						// $invoice_date=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary','id='.$maxid);
						// $party_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers','id='.$tbl_party_id);
			
						// $someArray[0]["header"][1]['fields'][0]['product_id']['validation_msg']=
						// 'Last Sale To '.$party_name.' Date:'.$invoice_date.' @ Rs'.$rate.' Disc1= '.$disc1.'% Disc2= '.$disc2.'%';
							
						
						//RATE VALIDATION

						//PRODUCT WISE PARTY WISE LAST SALE RATE .
											
						$whr=" id=".$product_id;	
						$tax_ledger_id=$this->projectmodel->GetSingleVal('tax_ledger_id','productmstr',$whr);
						$someArray[0]["header"][1]['fields'][0]['tax_ledger_id']['Inputvalue_id']=$tax_ledger_id;

						$whr=" id=".$tax_ledger_id;
						$tax_ledger_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr);
						$someArray[0]["header"][1]['fields'][0]['tax_ledger_id']['Inputvalue']=$tax_ledger_name;
						
						$this->FrmRptModel->tranfer_data($someArray);

					}

					

			}

	}
	
	
	
	
	if($form_name=='invoice_credit_note')
	{
			$form_summary_id=39;
			$form_detail_id=40;

			if($subtype=='view_list')
			{			
			
				$id=$form_data1->id;						
				$form_structure=$this->form_view($form_name,$id);

				array_push($output,$form_structure);
				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			

			//save section
			if($subtype=='SAVE_DATA')
			{
				//$this->projectmodel->save_data($form_data1->raw_data,$form_name);


				$save_details2['test_data']=$form_data1->raw_data;
				$this->projectmodel->save_records_model(1,'test_table',$save_details2);
				$rs=$resval=$form_structure=$output=$save_details=array();
				$whr=" id=1";	
				$raw_data=$this->projectmodel->GetSingleVal('test_data','test_table',$whr);
				$form_data=json_decode($raw_data);
				$headers=json_decode(json_encode($form_data[0]->header), true );
				$save_details=$this->FrmRptModel->create_save_array($headers);

				$VALID_STATUS='VALID';
				$invoice_no=$header_id=$id='';
		
				// if($VALID_STATUS=='VALID')
				// {

				// 	$form_data=json_decode($form_structure);
				// 	$headers=json_decode(json_encode($form_data[0]->header), true );
				// 	$header_scount=sizeof($headers);
				// 	$id_header=0;	
				// 	$count=sizeof($form_data[0]->header);		
				// 	$headers=json_decode(json_encode($form_data[0]->header), true );
				// 	$save_details=$this->FrmRptModel->create_save_array($headers);
				// 	$header_id=$id='';
				// }
				
				foreach($save_details as $key1=>$tables)
				{
					if($key1<2)
					{
						foreach($tables as $key2=>$fields)
						{
							
									$table_name=$key2;		
									$savedata=array();	
									$save_statue=true;

									foreach($fields as $key3=>$value)
									{
										//HERE REQUIRE CUSTOMIZATION
										if($key3=='id' && $table_name=='invoice_summary')
										{
											if($value>0)
											{$header_id=$value;}
											else 
											{$header_id='';}  											
										}
										else if ($key3<>'id' && $table_name=='invoice_summary')
										{$savedata[$key3]=$value;}
										else if ($key3=='id' && $table_name=='invoice_details')
										{if($value>0){$id=$value;}else {$id='';}   }
										else if ($key3=='invoice_summary_id' && $table_name=='invoice_details')
										{$savedata[$key3]=$header_id; }
										else 
										{
											$savedata[$key3]=$value; 
											//if($savedata['product_id']==0){$save_statue=false;}

										}

									}

									if($header_id=='')
									{										
										if($savedata['invoice_date']=='')
										{$savedata['invoice_date']=date('Y-m-d');}
										$tran_no=$this->projectmodel->tran_no_generate('SALE_RTN',$savedata['invoice_date']);
										$savedata['finyr']=$tran_no['finyr'];
										$savedata['srl']=$tran_no['srl'];
										$savedata['invoice_no']='CN/'.$tran_no['srl'].'/'.$savedata['finyr'];
									}

									$savedata['status']='SALE_RTN';

									//HEADER SECTION
									if($table_name=='invoice_summary')
									{
										$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
										if($key1==0 && $header_id=='')
										{
											$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}		
										else if($key1==0 && $header_id>0)
										{
											$server_msg="Record has been Updated Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}										
										
									}

									if($table_name=='invoice_details')
									{
										if($savedata['product_id']>0)
										{$this->projectmodel->save_records_model($id,$table_name,$savedata);}
									}

						}
					}
					

				}

				
							
				$return_data['id_header']=$header_id;
				$return_data['server_msg']=$server_msg;		
				$return_data['invoice_no']=$invoice_no;
				
				$this->projectmodel->update_transactions($header_id,$form_name);
				

				$this->projectmodel->send_json_output($return_data);


			}	

			if($subtype=='MAIN_GRID')
			{
				$startdate=$form_data1->startdate;
				$enddate=$form_data1->enddate;

				$indx=0;
				$id=$form_id=$form_summary_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_date between '$startdate' and '$enddate' ";
								

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist')
			{
				
				$id=$form_data1->id;	
				$indx=0;
				
				$form_id=$form_detail_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_summary_id=".$id;

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist_view')
			{	

				$someArray=array();

				$detail_id=$form_data1->id;
				$someArray = json_decode($form_data1->raw_data, true);


				foreach($someArray[0]['header'][1]['fields'][0] as $key1=>$values)
				{
										
					if($someArray[0]['header'][1]['fields'][0][$key1]['datafields']<>'')
					{
						$whr=" id=".$detail_id;	
						$Inputvalue_id=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue_id']=$Inputvalue_id;

						$MainTable=$someArray[0]['header'][1]['fields'][0][$key1]['MainTable'];
						$LinkField=$someArray[0]['header'][1]['fields'][0][$key1]['LinkField'];
						$whr=" id=".$Inputvalue_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($LinkField,$MainTable,$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue']=$Inputvalue;

					}
					else
					{

						$whr=" id=".$detail_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($key1,'invoice_details',$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue']=$Inputvalue;
					}
										
							
				}

				$this->FrmRptModel->tranfer_data($someArray);


			}

			if($subtype=='other_search')
			{			
										
					$someArray=array();

					$header_index=$form_data1->header_index;
					$field_index=$form_data1->field_index;
					$searchelement=$form_data1->searchelement;
					$someArray = json_decode($form_data1->raw_data, true);

					if($searchelement=='tbl_party_id')
					{							
						
						$tbl_party_id=$someArray[0]['header'][$header_index]['fields'][$field_index]['tbl_party_id']['Inputvalue_id'];
						$list_of_hq_ids=$this->projectmodel->gethierarchy_list($tbl_party_id,'HQ_STOCKIST_LEDGER');

					//	$someArray[0]['header'][$header_index]['fields'][$field_index]['tbl_party_id']['Inputvalue']=$list_of_hq_ids;

						$sql="select  id FieldID,hierarchy_name FieldVal  from tbl_hierarchy_org
						where  id in (".$list_of_hq_ids.") order by hierarchy_name	";
						$datafields_array =$this->projectmodel->get_records_from_sql($sql);
						$someArray[0]["header"][$header_index]['fields'][$field_index]['hq_id']['datafields']=
						json_decode(json_encode($datafields_array), true);

						$this->FrmRptModel->tranfer_data($someArray);
					}

					if($searchelement=='product_id')
					{							
						
						$product_id=$someArray[0]['header'][$header_index]['fields'][$field_index]['product_id']['Inputvalue_id'];
						$tbl_party_id=$someArray[0]['header'][0]['fields'][0]['tbl_party_id']['Inputvalue_id'];


						$sql="select  a.invoice_no Invoice,a.invoice_date Date,b.id FieldID,b.batchno FieldVal,b.srate Rate ,b.disc_per DiscPer,
						b.uom UOM,b.mrp MRP,b.ptr PTR,b.exp_monyr ExpDate,b.mfg_monyr ManufacDate,b.mrp MRP,b.mrp MRP
						from invoice_summary a,invoice_details b
						where  a.id=b.invoice_summary_id and a.status='SALE' and a.tbl_party_id=".$tbl_party_id." and 
						b.product_id=".$product_id;
						$datafields_array =$this->projectmodel->get_records_from_sql($sql);
						$someArray[0]["header"][$header_index]['fields'][$field_index]['batchno']['datafields']=
						json_decode(json_encode($datafields_array), true);



						$whr=" id=".$product_id;	
						$output_gst_ledger_id=$this->projectmodel->GetSingleVal('output_gst_ledger_id','brands',$whr);		

						$whr=" id=".$output_gst_ledger_id;	
						$output_gst_ledger_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr);

						$someArray[0]['header'][$header_index]['fields'][$field_index]['tax_ledger_id']['Inputvalue_id']=$output_gst_ledger_id;
						$someArray[0]['header'][$header_index]['fields'][$field_index]['tax_ledger_id']['Inputvalue']=$output_gst_ledger_name;
						
						$this->FrmRptModel->tranfer_data($someArray);
					}


			}

	}


	if($form_name=='receive_from_party')
	{
			$form_summary_id=44;
			$form_detail_id=45;

			if($subtype=='view_list')
			{			
			
				$id=$form_data1->id;						
				$form_structure=$this->form_view($form_name,$id);

				array_push($output,$form_structure);
				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			
			//save section
			if($subtype=='SAVE_DATA')
			{
				//$this->projectmodel->save_data($form_data1->raw_data,$form_name);


				$save_details2['test_data']=$form_data1->raw_data;
				$this->projectmodel->save_records_model(1,'test_table',$save_details2);
				$rs=$resval=$form_structure=$output=$save_details=array();
				$whr=" id=1";	
				$raw_data=$this->projectmodel->GetSingleVal('test_data','test_table',$whr);
				$form_data=json_decode($raw_data);
				$headers=json_decode(json_encode($form_data[0]->header), true );
				$save_details=$this->FrmRptModel->create_save_array($headers);

				$VALID_STATUS='VALID';
				$invoice_no=$header_id=$id='';
		
				// if($VALID_STATUS=='VALID')
				// {

				// 	$form_data=json_decode($form_structure);
				// 	$headers=json_decode(json_encode($form_data[0]->header), true );
				// 	$header_scount=sizeof($headers);
				// 	$id_header=0;	
				// 	$count=sizeof($form_data[0]->header);		
				// 	$headers=json_decode(json_encode($form_data[0]->header), true );
				// 	$save_details=$this->FrmRptModel->create_save_array($headers);
				// 	$header_id=$id='';
				// }
				
				foreach($save_details as $key1=>$tables)
				{
					if($key1<2)
					{
						foreach($tables as $key2=>$fields)
						{
							
									$table_name=$key2;		
									$savedata=array();	
									$save_statue=true;

									foreach($fields as $key3=>$value)
									{
										//HERE REQUIRE CUSTOMIZATION
										if($key3=='id' && $table_name=='invoice_payment_receive')
										{
											if($value>0)
											{$header_id=$value;}
											else 
											{$header_id='';}  											
										}
										else if ($key3<>'id' && $table_name=='invoice_payment_receive')
										{$savedata[$key3]=$value;}
										else if ($key3=='id' && $table_name=='invoice_payment_receive_details')
										{if($value>0){$id=$value;}else {$id='';}   }
										else if ($key3=='invoice_payment_receive_id' && $table_name=='invoice_payment_receive_details')
										{$savedata[$key3]=$header_id; }
										else 
										{
											$savedata[$key3]=$value; 
											//if($savedata['product_id']==0){$save_statue=false;}

										}

									}

									if($header_id=='')
									{										
										if($savedata['invoice_no']=='')
										{$savedata['invoice_date']=date('Y-m-d');}
										$tran_no=$this->projectmodel->tran_no_generate('RECEIVE',$savedata['invoice_date']);
										$savedata['finyr']=$tran_no['finyr'];
										$savedata['srl']=$tran_no['srl'];
										$savedata['invoice_no']='BILL/'.$tran_no['srl'].'/'.$savedata['finyr'];
										$savedata['status']='RECEIVE';
									}

									

									//HEADER SECTION
									if($table_name=='invoice_payment_receive')
									{
										$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
										if($key1==0 && $header_id=='')
										{
											$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}		
										else if($key1==0 && $header_id>0)
										{
											$server_msg="Record has been Updated Successfully!";
											$invoice_no=$savedata['invoice_no'];
										}										
										
									}

									if($table_name=='invoice_payment_receive_details')
									{
										// if($savedata['amount']>0 && $savedata['invoice_summary_id']>0)
										// {}

											$this->projectmodel->save_records_model($id,$table_name,$savedata);
									}

						}
					}
					

				}
			
							
				$return_data['id_header']=$header_id;
				$return_data['server_msg']=$server_msg;		
				$return_data['invoice_no']=$invoice_no;

				$this->projectmodel->update_transactions($header_id,$form_name);
				

				$this->projectmodel->send_json_output($return_data);


			}	

			if($subtype=='MAIN_GRID')
			{
				$startdate=$form_data1->startdate;
				$enddate=$form_data1->enddate;

				$indx=0;
				$id=$form_id=$form_summary_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_date	 between '$startdate' and '$enddate' ";								

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist')
			{
				
				$id=$form_data1->id;	
				$indx=0;
				
				$form_id=$form_detail_id;
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
				$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
				$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
				$WhereCondition=$WhereCondition." and invoice_payment_receive_id=".$id;

				$rs[$indx]['section_type']='GRID_ENTRY';	
				$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
				$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
				$rs[$indx]['fields']=$DataFields;
				$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
				$resval=$this->FrmRptModel->create_report($rs,$id); 

			}

			if($subtype=='dtlist_view')
			{	

				$someArray=array();

				$detail_id=$form_data1->id;
				$someArray = json_decode($form_data1->raw_data, true);


				foreach($someArray[0]['header'][1]['fields'][0] as $key1=>$values)
				{
										
					if($someArray[0]['header'][1]['fields'][0][$key1]['datafields']<>'')
					{
						$whr=" id=".$detail_id;	
						$Inputvalue_id=$this->projectmodel->GetSingleVal($key1,'invoice_payment_receive_details',$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue_id']=$Inputvalue_id;

						$MainTable=$someArray[0]['header'][1]['fields'][0][$key1]['MainTable'];
						$LinkField=$someArray[0]['header'][1]['fields'][0][$key1]['LinkField'];
						$whr=" id=".$Inputvalue_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($LinkField,$MainTable,$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue']=$Inputvalue;

					}
					else
					{

						$whr=" id=".$detail_id;	
						$Inputvalue=$this->projectmodel->GetSingleVal($key1,'invoice_payment_receive_details',$whr);
						$someArray[0]['header'][1]['fields'][0][$key1]['Inputvalue']=$Inputvalue;
					}
										
							
				}

				$this->FrmRptModel->tranfer_data($someArray);


			}

			if($subtype=='other_search')
			{			
										
					$someArray=array();

					$header_index=$form_data1->header_index;
					$field_index=$form_data1->field_index;
					$searchelement=$form_data1->searchelement;
					$someArray = json_decode($form_data1->raw_data, true);
					$batch_array=array();

					if($searchelement=='debtor_creditor_ledger_id')
					{							
						
						$debtor_creditor_ledger_id=
						$someArray[0]['header'][$header_index]['fields'][$field_index]['debtor_creditor_ledger_id']['Inputvalue_id'];
					
						//and total_due>0

						$records="select *	from invoice_summary where  tbl_party_id=".$debtor_creditor_ledger_id."  and status='SALE' ";
						$records = $this->projectmodel->get_records_from_sql($records);
						foreach ($records as $fieldIndex=>$record)
						{	

							//$batch_array[$fieldIndex]['depot_name']=$this->projectmodel->GetSingleVal('name','misc_mstr','id='.$record->depot_id); 	
							$batch_array[$fieldIndex]['FieldID']=$record->id;		
							$batch_array[$fieldIndex]['FieldVal']=$record->invoice_no;		

							$batch_array[$fieldIndex]['Grand Total']=$record->grandtot;		
							$batch_array[$fieldIndex]['Total paid']=$record->total_paid;
							$batch_array[$fieldIndex]['Total Due']=$record->total_due;

						}	

						$someArray[0]["header"][1]['fields'][0]['invoice_summary_id']['datafields']=$batch_array;

						$this->FrmRptModel->tranfer_data($someArray);

					}

					if($searchelement=='bank_id')
					{							
						
						$bank_id=$someArray[0]['header'][$header_index]['fields'][$field_index]['bank_id']['Inputvalue_id'];
						
					
						if($bank_id==317) //CASH
						{

							$sql="select  id FieldID, FieldVal  from frmrptgeneralmaster where id=59";
							$datafields_array =$this->projectmodel->get_records_from_sql($sql);
							$someArray[0]["header"][$header_index]['fields'][$field_index]['bank_payment_doc']['datafields']=
							json_decode(json_encode($datafields_array), true);

							$someArray[0]['header'][$header_index]['fields'][$field_index]['clearance_status']['Inputvalue_id']=62;
							$someArray[0]['header'][$header_index]['fields'][$field_index]['clearance_status']['Inputvalue']='CLEARED'; 	

						}
						else
						{

							$sql="select  id FieldID, FieldVal  from frmrptgeneralmaster where id in (60,61) ";
							$datafields_array =$this->projectmodel->get_records_from_sql($sql);
							$someArray[0]["header"][$header_index]['fields'][$field_index]['bank_payment_doc']['datafields']=
							json_decode(json_encode($datafields_array), true);

						}
					
						
						$this->FrmRptModel->tranfer_data($someArray);
					}


					if($searchelement=='invoice_summary_id')
					{							
						
						$debtor_creditor_ledger_id=$someArray[0]['header'][0]['fields'][0]['debtor_creditor_ledger_id']['Inputvalue_id'];
					
						$records="select *	from invoice_summary where  tbl_party_id=".$debtor_creditor_ledger_id."  and status='SALE' ";
						$records = $this->projectmodel->get_records_from_sql($records);
						foreach ($records as $fieldIndex=>$record)
						{	
							$batch_array[$fieldIndex]['FieldID']=$record->id;		
							$batch_array[$fieldIndex]['FieldVal']=$record->invoice_no;	
							$batch_array[$fieldIndex]['Grand Total']=$record->grandtot;		
							$batch_array[$fieldIndex]['Total paid']=$record->total_paid;
							$batch_array[$fieldIndex]['Total Due']=$record->total_due;

						}	

						$someArray[0]["header"][1]['fields'][0]['invoice_summary_id']['datafields']=$batch_array;

						$this->FrmRptModel->tranfer_data($someArray);

					}

					


			}

	}
	

}


public function form_view($form_name,$id)
{

	$return_data=	$setting=	$rs=$resval=$form_structure=$output=array();		
	//$setting=$this->projectmodel->user_wise_setting(); 


	if($form_name=='invoice_entry')
	{
	
		$indx=0;
		$form_id=34;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
		$DataFields2=$this->projectmodel->GetSingleVal('DataFields2','frmrpttemplatehdr',$whr);	
		$DataFields3=$this->projectmodel->GetSingleVal('DataFields3','frmrpttemplatehdr',$whr);	
		$DataFields4=$this->projectmodel->GetSingleVal('DataFields4','frmrpttemplatehdr',$whr);	
		$DataFields5=$this->projectmodel->GetSingleVal('DataFields5','frmrpttemplatehdr',$whr);	
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']=$section_type;	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where id=".$id;



		$indx=1;
		$form_id=35;
		$invoice_summary_id=$id;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']='GRID_ENTRY';		
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=0;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		//	$rs[$indx]['fields']=$DataFields.$setting['segments'];
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']."
		 where invoice_summary_id=".$invoice_summary_id;


		$form_structure=$this->FrmRptModel->create_form($rs,$id);
		$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);
		//$form_structure=$this->projectmodel->other_setting($form_structure,$form_name);
		
		
		return $form_structure;

	}	


	if($form_name=='purchase_entry')
	{
			$form_summary_id=36;
			$form_detail_id=37;

			$indx=0;
			$form_id=$form_summary_id;
			$whr=" id=".$form_id;	
			$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
			$DataFields2=$this->projectmodel->GetSingleVal('DataFields2','frmrpttemplatehdr',$whr);	
			$DataFields3=$this->projectmodel->GetSingleVal('DataFields3','frmrpttemplatehdr',$whr);	
			$DataFields4=$this->projectmodel->GetSingleVal('DataFields4','frmrpttemplatehdr',$whr);	
			$DataFields5=$this->projectmodel->GetSingleVal('DataFields5','frmrpttemplatehdr',$whr);	
			$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
			$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
			$rs[$indx]['section_type']=$section_type;	
			$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
			$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
			$rs[$indx]['fields']=$DataFields;
			$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where id=".$id;



			$indx=1;
			$form_id=$form_detail_id;
			$invoice_summary_id=$id;
			$whr=" id=".$form_id;	
			$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);
			$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
			$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
			$rs[$indx]['section_type']='GRID_ENTRY';		
			$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
			$rs[$indx]['id']=0;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
			//	$rs[$indx]['fields']=$DataFields.$setting['segments'];
			$rs[$indx]['fields']=$DataFields;
			$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']."
			where invoice_summary_id=".$invoice_summary_id;


			$form_structure=$this->FrmRptModel->create_form($rs,$id);
			$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);
			//$form_structure=$this->projectmodel->other_setting($form_structure,$form_name);
			
			return $form_structure;



	}


	if($form_name=='invoice_credit_note')
	{
		
		$indx=0;
		$form_id=39;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']=$section_type;	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where id=".$id;


		$indx=1;
		$form_id=40;
		$invoice_summary_id=$id;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']='GRID_ENTRY';		
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=0;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		//	$rs[$indx]['fields']=$DataFields.$setting['segments'];
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where invoice_summary_id=".$invoice_summary_id;

		$form_structure=$this->FrmRptModel->create_form($rs,$id);
		$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);
		//$form_structure=$this->projectmodel->other_setting($form_structure,$form_name);
		
		return $form_structure;

	}

	if($form_name=='receive_from_party')
	{

		$indx=0;
		$form_id=44;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']=$section_type;	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where id=".$id;


		$indx=1;
		$form_id=45;
		$invoice_payment_receive_id=$id;
		$whr=" id=".$form_id;	
		$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$rs[$indx]['section_type']='GRID_ENTRY';		
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=0;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;		
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." 
		where invoice_payment_receive_id=".$invoice_payment_receive_id;

		$form_structure=$this->FrmRptModel->create_form($rs,$id);
		$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);
		//$form_structure=$this->projectmodel->other_setting($form_structure,$form_name);
		
		return $form_structure;

	}	



}


public function master_form()
{
		

		$return_data=	$setting=	$rs=$resval=$form_structure=$output=array();			
		$form_data1=json_decode(file_get_contents("php://input"));	
		//$form_name=$form_data1->form_name;	//PARAMETERS		
		$form_id=$form_data1->form_id;
		$subtype=$form_data1->subtype;

		if($form_id<>8 && $form_id<>27 && $form_id<>75 && $form_id<>14 && $form_id<>31
		 && $form_id<>32 && $form_id<>40  && $form_id<>10)
		{$setting=$this->projectmodel->user_wise_setting(); }
		
		
		if($subtype=='view_list')
		{			
				
								
				$id=$form_data1->id;	
				$whr=" id=".$form_id;	
				$DataFields=$this->projectmodel->GetSingleVal('DataFields','frmrpttemplatehdr',$whr);	
				$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
				$rs[0]['section_type']='FORM';	
				$rs[0]['frmrpttemplatehdr_id']=$form_id;
				$rs[0]['id']=$id;	$rs[0]['parent_id']='';	$rs[0]['TableName']=$TableName;
				$rs[0]['fields']=$DataFields;
				$rs[0]['sql_query']="select ".$rs[0]['fields']." from ".$rs[0]['TableName']." where id=".$id;

				$form_structure=$this->FrmRptModel->create_form($rs,$id);
			  $form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);


				  //Setting 
				  if($form_id==37)//OPM ITEM SUMMARY
				  {

				  $form_structure["header"][0]['fields'][0]['organization']['Inputvalue_id']=
				  $setting['req_organization_id'];							
					$form_structure["header"][0]['fields'][0]['organization']['Inputvalue']=
					$setting['req_organization_name'];	


					}
					if($form_id==91)//BATCH SUMMARY
				  {
						$form_structure["header"][0]['fields'][0]['organisation_id']['Inputvalue_id']=
						$setting['req_organization_id'];							
						$form_structure["header"][0]['fields'][0]['organisation_id']['Inputvalue']=
						$setting['req_organization_name'];	

					}	
					


				array_push($output,$form_structure);
				header('Access-Control-Allow-Origin: *');
				header("Content-Type: application/json");
				echo json_encode($output);	
		}

		if($subtype=='SAVE_DATA')
		{
			
					//VALIDATION PORTION
												
					$data_for_validation = json_decode($form_data1->raw_data, true);								
					//$VALID_STATUS=$this->FrmRptModel->validation_data($data_for_validation);
					$VALID_STATUS='VALID';
					$server_msg='VALID';
					//VALIDATION PORTION
				
					if($VALID_STATUS=='VALID')
					{

						//$form_data=json_decode($form_data1->raw_data);
							
							// $save_details2['test_data']=$form_data1->raw_data;
							// $save_details2['test_data']=json_decode(json_encode($form_data1->raw_data), true );
							// $this->projectmodel->save_records_model(1,'test_table',$save_details2);

									$form_data=json_decode($form_data1->raw_data);
									$headers=json_decode(json_encode($form_data[0]->header), true );
									$header_scount=sizeof($headers);
									$id_header=0;	
									$count=sizeof($form_data[0]->header);		
									$headers=json_decode(json_encode($form_data[0]->header), true );								
									$save_details=$this->FrmRptModel->create_save_array($headers);
								
									$header_id=$id='';
									foreach($save_details as $key1=>$tables)
									{
										
										foreach($tables as $key2=>$fields)
										{
												$table_name=$key2;		
												$savedata=array();	
												$save_statue=true;
																	
												foreach($fields as $key3=>$value)
												{
													//HERE REQUIRE CUSTOMIZATION
													if($key3=='id' )
													{
														if($value>0)
														{$header_id=$value;}
														else 
														{$header_id='';}  											
													}
													else if ($key3<>'id')
													{$savedata[$key3]=$value;}															
												}
	
												//HEADER SECTION
												$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
												if($key1==0 && $header_id=='')
												{$header_id=$this->db->insert_id();$server_msg="Record has been inserted Successfully!";}	
												else if($key1==0 && $header_id>0){$server_msg="Record has been Updated Successfully!";}										
											
										}	
	
									}

									if($form_id==91)
									{	$this->projectmodel->update_transactions($header_id,'BATCH_CREATE'); }	
									
									$return_data['id_header']=$header_id;
									$return_data['server_msg']=$server_msg;


				
									header('Access-Control-Allow-Origin: *');
									header("Content-Type: application/json");
									echo json_encode($return_data);

						}
		}	

		if($subtype=='MAIN_GRID')
		{
		
			$whr=" id=".$form_id;	
			$GridHeader=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);	
			$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);	
			$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
			$id=11;	
			$rs[0]['section_type']='GRID_ENTRY';	
			$rs[0]['frmrpttemplatehdr_id']=$form_id;
			$rs[0]['id']=$id;	$rs[0]['parent_id']='';	$rs[0]['TableName']=$TableName;
			$rs[0]['fields']=$GridHeader;
			$rs[0]['sql_query']="select ".$rs[0]['fields']." from ".$rs[0]['TableName']." where ".$WhereCondition;					
			$resval=$this->FrmRptModel->create_report($rs,$id); 

		}


		if($subtype=='other_search')
		{			
									
			 $someArray=array();

			 $header_index=$form_data1->header_index;
			 $field_index=$form_data1->field_index;
			 $searchelement=$form_data1->searchelement;
			 $someArray = json_decode($form_data1->raw_data, true);

			if($form_id==40)
			{							
					$chart_of_ac_id=$someArray[0]['header'][0]['fields'][0]['chart_of_account_id']['Inputvalue_id'] ;

				  $setting=$this->projectmodel->get_ac_segments($chart_of_ac_id); 
					$field_qualifier_name='';
					$cnts=sizeof($setting['segment']);
					for($cnt=0;$cnt<$cnts;$cnt++)
					{
						$field_qualifier_name=$setting['segment'][$cnt]['field_qualifier_name'];
						if($field_qualifier_name=='account_id')
						{

							$someArray[0]['header'][0]['fields'][0]['p2p_grn_dr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['p2p_grn_cr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['p2p_service_dr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['p2p_service_cr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['p2p_invoice_dr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['o2c_despatch_dr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['o2c_despatch_cr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['o2c_service_dr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['o2c_service_cr']['datafields']=
							$setting['segment'][$cnt]['value_set'];

							$someArray[0]['header'][0]['fields'][0]['o2c_invoice_cr']['datafields']=
							$setting['segment'][$cnt]['value_set'];
					
							
						}											
					}
					

				
					$this->FrmRptModel->tranfer_data($someArray);


			}


			//Bank,Supplier,Customer
			if($form_id==38 || $form_id==33 || $form_id==35)
			{							
					//$chart_of_ac_id=$someArray[0]['header'][0]['fields'][0]['chart_of_account_id']['Inputvalue_id'] ;
					// $setting=$this->projectmodel->get_ac_segments($chart_of_ac_id); 

					$setting=$this->projectmodel->user_wise_setting();  
					$field_qualifier_name='';
					$cnts=sizeof($setting['segment']);
					for($cnt=0;$cnt<$cnts;$cnt++)
					{
						$field_qualifier_name=$setting['segment'][$cnt]['field_qualifier_name'];
						if($field_qualifier_name=='account_id')
						{
							$someArray[0]['header'][0]['fields'][0]['chart_of_account_ledger_id']['datafields']=
							$setting['segment'][$cnt]['value_set'];
						}											
					}					
					$this->FrmRptModel->tranfer_data($someArray);
			}


		

		}



}


public function experimental_report($datatype='')
{
		$return_data=	$setting=	$rs=$resval=$form_structure=$output=array();			
		$form_data1=json_decode(file_get_contents("php://input"));	
		$form_name=$form_data1->form_name;	//PARAMETERS		
		$subtype=$form_data1->subtype;
		$month_year=$form_data1->id;

		$setting=$this->projectmodel->user_wise_setting(); 

		if($form_name=='po_entry')
		{
							
				if($subtype=='view_list')
				{			
					
						$id=mysql_real_escape_string($form_data1->id);

						//$id=0;//for NEW entry			
						//$id=40;//for old entry
						//$id=51;
						//HEADER SECTION
						$rs[0]['section_type']='FORM';	
						$rs[0]['frmrpttemplatehdr_id']=41;
						$rs[0]['id']=$id;	$rs[0]['parent_id']='';	$rs[0]['TableName']='invoice_summary';
						$rs[0]['fields']='id,parent_id,req_operating_unit,created_date_time,req_number,req_type,req_supplier,req_site,req_contact,comment,status';
						//,	req_preparer,req_organization,req_location,req_accounting_date,status,last_updated_by,last_updated_date_time,created_by,create_date_time

						//BODY OR GRID ENTRY SECTION					
						$rs[1]['section_type']='GRID_ENTRY';		
						$rs[1]['frmrpttemplatehdr_id']=48;
						$rs[1]['id']=0;	$rs[1]['parent_id']=$id;$rs[1]['TableName']='invoice_details';		
						$rs[1]['fields']='id,invoice_summary_id,item_id,qnty,uom,price,billing_address,shipping_address';
											
						// //FOOTER SECTION
						$rs[2]['section_type']='FORM';	
						$rs[2]['frmrpttemplatehdr_id']=41;
						$rs[2]['id']=$id;	$rs[2]['parent_id']='';$rs[2]['TableName']='invoice_summary';
						$rs[2]['fields']='req_preparer,req_organization,req_location,req_accounting_date,last_updated_by,last_updated_date_time,created_by,create_date_time,req_status';

						$rs[0]['sql_query']="select ".$rs[0]['fields']." from ".$rs[0]['TableName']." where id=".$id;					
						$rs[1]['sql_query']="select ".$rs[1]['fields']." from ".$rs[1]['TableName']." where  invoice_summary_id=".$id;	
						$rs[2]['sql_query']="select ".$rs[2]['fields']." from ".$rs[2]['TableName']." where id=".$id;							
						
						$form_structure=$this->FrmRptModel->create_form($rs,$id);
						$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);

						//FORM CUSTOM SETTING

						$form_structure["header"][0]['fields'][0]['req_type']['Inputvalue']='Standard Purchase Order';
						$form_structure["header"][0]['fields'][0]['req_type']['Inputvalue_id']=93;
						$form_structure["header"][0]['fields'][0]['status']['Inputvalue']='PO_ENTRY';
						$form_structure["header"][2]['fields'][0]['last_updated_by']['Inputvalue_id']=$setting['login_emp_id'];
						$form_structure["header"][2]['fields'][0]['last_updated_date_time']['Inputvalue_id']=date('Y-m-d H:i:s');

						//USER
						if($form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue_id']=='')
						{				

							$form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue_id']=$setting['login_emp_id'];							
							$form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue']=$setting['login_emp_name'];
							// $form_structure["header"][2]['fields'][0]['req_requiester']['Inputvalue_id']=$setting['login_emp_id'];							
							// $form_structure["header"][2]['fields'][0]['req_requiester']['Inputvalue']=$setting['login_emp_name'];			
							$form_structure["header"][2]['fields'][0]['req_organization']['Inputvalue_id']=$setting['req_organization_id'];							
							$form_structure["header"][2]['fields'][0]['req_organization']['Inputvalue']=$setting['req_organization_name'];	
							$form_structure["header"][2]['fields'][0]['req_location']['Inputvalue']=$setting['req_location'];	
							$form_structure["header"][2]['fields'][0]['req_accounting_date']['Inputvalue']=date('Y-m-d');
							$form_structure["header"][2]['fields'][0]['created_by']['Inputvalue_id']=	$setting['login_emp_id'];
							$form_structure["header"][2]['fields'][0]['create_date_time']['Inputvalue_id']=	date('Y-m-d H:i:s');

						}
					
						// if($form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue_id']=='')
						// {								
						// 	$form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue_id']=$setting['currency_id'];							
						// 	$form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue']=$setting['currency_name'];
						// }
										
						$sql="select  id FieldID,req_number FieldVal  from invoice_summary where  status='REQUISITION' AND req_status=91 ";
						$datafields_array =$this->projectmodel->get_records_from_sql($sql);
						$form_structure["header"][0]['fields'][0]['parent_id']['datafields']=json_decode(json_encode($datafields_array), true);		
						$form_structure["header"][0]['fields'][0]['parent_id']['LabelName']='Enter Requisition';	

						//FORM CUSTOM SETTING END



					array_push($output,$form_structure);
					header('Access-Control-Allow-Origin: *');
					header("Content-Type: application/json");
					echo json_encode($output);						

				}

				//save section
				if($subtype=='SAVE_DATA')
				{
					
							$form_data=json_decode($form_data1->raw_data);
							
						//	$save_details2['test_data']=$form_data1->raw_data;
							//$save_details2['test_data']=json_decode(json_encode($form_data1->raw_data), true );
						//	$this->projectmodel->save_records_model(1,'test_table',$save_details2);

							$headers=json_decode(json_encode($form_data[0]->header), true );
							$header_scount=sizeof($headers);
							$id_header=0;	
							$count=sizeof($form_data[0]->header);		
							$headers=json_decode(json_encode($form_data[0]->header), true );
						//	$save_details=$this->create_save_array($headers);
							$save_details=$this->FrmRptModel->create_save_array($headers);
						

							// echo '<pre>';
							// print_r($save_details);
							// echo '</pre>';

							$header_id=$id='';
							foreach($save_details as $key1=>$tables)
							{
								
								foreach($tables as $key2=>$fields)
								{
										$table_name=$key2;		
										$savedata=array();	
										$save_statue=true;
															
										foreach($fields as $key3=>$value)
										{
											//HERE REQUIRE CUSTOMIZATION
											if($key3=='id' && $table_name=='invoice_summary')
											{
												if($value>0)
												{$header_id=$value;}
												else 
												{$header_id='';}  											
											}
											else if ($key3<>'id' && $table_name=='invoice_summary')
											{$savedata[$key3]=$value;}
											else if ($key3=='id' && $table_name=='invoice_details')
											{if($value>0){$id=$value;}else {$id='';}   }
											else if ($key3=='invoice_summary_id' && $table_name=='invoice_details')
											{$savedata[$key3]=$header_id; }
											else 
											{$savedata[$key3]=$value; if($savedata['item_id']==0){$save_statue=false;}}

										}

										 //echo $table_name.' - '.$header_id.' - '.$id;
										// echo '<br>';
										// echo '<pre>';
										// print_r($savedata);
										// echo '</pre>';

										//HEADER SECTION
										if($table_name=='invoice_summary')
										{
											$this->projectmodel->save_records_model($header_id,$table_name,$savedata);
											if($key1==0 && $header_id==''){$header_id=$this->db->insert_id();}												
										}

										if($save_statue && $table_name=='invoice_details')
										{
											$this->projectmodel->save_records_model($id,$table_name,$savedata);
										}
								}	

							}
							
							$return_data['id_header']=$header_id;
		
							header('Access-Control-Allow-Origin: *');
							header("Content-Type: application/json");
							echo json_encode($return_data);


				}	

				if($subtype=='MAIN_GRID')
				{
				
					$id=33;	
					$rs[0]['section_type']='GRID_ENTRY';	
					$rs[0]['frmrpttemplatehdr_id']=41;
					$rs[0]['id']=$id;	$rs[0]['parent_id']='';	$rs[0]['TableName']='invoice_summary';
					$rs[0]['fields']='id,req_operating_unit,req_number,req_type,req_preparer,req_description,req_status,req_currency_id,req_total';
					$rs[0]['sql_query']="select ".$rs[0]['fields']." from ".$rs[0]['TableName']." where status='PO_ENTRY'";		
					$resval=$this->FrmRptModel->create_report($rs,$id); 

				}

				if($subtype=='other_search')
				{			
											
					$output= $someArray=array();

					 $header_index=mysql_real_escape_string($form_data1->header_index);
					 $field_index=mysql_real_escape_string($form_data1->field_index);
					 $searchelement=mysql_real_escape_string($form_data1->searchelement);
				   $someArray = json_decode($form_data1->raw_data, true);

					// if($searchelement=='item_id')
					// {
					// 	$sql="select  id FieldID,name FieldVal  from tbl_currency_master where id in (1,2) ";
					// 	$datafields_array =$this->projectmodel->get_records_from_sql($sql);
					// 	$someArray[0]["header"][$header_index]['fields'][$field_index]['qnty']['datafields']=json_decode(json_encode($datafields_array), true);
						
					// 	$this->FrmRptModel->tranfer_data($someArray);						
					// }

					if($searchelement=='parent_id')
					{
							//$id=$parent_id=$someArray[0]["header"][$header_index]['fields'][$field_index]['parent_id']['Inputvalue_id'];
							//$whr=" id=".$parent_id;	
							//	$id=$this->projectmodel->GetSingleVal('id','invoice_summary',$whr);

						
								$id=0;
								$parent_id=$someArray[0]["header"][$header_index]['fields'][$field_index]['parent_id']['Inputvalue_id'];
								
								$entry_type='EDIT';
								$whr=" parent_id=".$parent_id;	
								$id=$this->projectmodel->GetSingleVal('id','invoice_summary',$whr); //PO ID

								if($id==0)
								{
									$whr=" id=".$parent_id;	
									$id=$this->projectmodel->GetSingleVal('id','invoice_summary',$whr); //requisition id
									$entry_type='NEW';
								}						
					 

								$rs[0]['section_type']='FORM';	
								$rs[0]['frmrpttemplatehdr_id']=41;
								$rs[0]['id']=$id;	$rs[0]['parent_id']='';	$rs[0]['TableName']='invoice_summary';
								$rs[0]['fields']='id,parent_id,req_operating_unit,created_date_time,req_number,req_type,req_supplier,req_site,req_contact,comment,status';
								//,	req_preparer,req_organization,req_location,req_accounting_date,status,last_updated_by,last_updated_date_time,created_by,create_date_time
	
								//BODY OR GRID ENTRY SECTION					
								$rs[1]['section_type']='GRID_ENTRY';		
								$rs[1]['frmrpttemplatehdr_id']=48;
								$rs[1]['id']=0;	$rs[1]['parent_id']=$id;$rs[1]['TableName']='invoice_details';		
								$rs[1]['fields']='id,invoice_summary_id,item_id,qnty,uom,price,billing_address,shipping_address';
													
								// //FOOTER SECTION
								$rs[2]['section_type']='FORM';	
								$rs[2]['frmrpttemplatehdr_id']=41;
								$rs[2]['id']=$id;	$rs[2]['parent_id']='';$rs[2]['TableName']='invoice_summary';
								$rs[2]['fields']='req_preparer,req_organization,req_location,req_accounting_date,last_updated_by,last_updated_date_time,created_by,create_date_time,req_status';
	
								$rs[0]['sql_query']="select ".$rs[0]['fields']." from ".$rs[0]['TableName']." where id=".$id;					
								$rs[1]['sql_query']="select ".$rs[1]['fields']." from ".$rs[1]['TableName']." where  invoice_summary_id=".$id;	
								$rs[2]['sql_query']="select ".$rs[2]['fields']." from ".$rs[2]['TableName']." where id=".$id;							
								
								$form_structure=$this->FrmRptModel->create_form($rs,$id);
								$form_structure=$this->FrmRptModel->re_arrange_input_index($form_structure);

								$whr=" id=".$parent_id;	
									$req_number=$this->projectmodel->GetSingleVal('req_number','invoice_summary',$whr); //requisition id
								$form_structure["header"][$header_index]['fields'][$field_index]['parent_id']['Inputvalue']=$req_number;
								$form_structure["header"][$header_index]['fields'][$field_index]['parent_id']['Inputvalue_id']=$parent_id;

								if($entry_type=='NEW')
								{
									$form_structure["header"][$header_index]['fields'][$field_index]['id']['Inputvalue']='';
									$form_structure["header"][$header_index]['fields'][$field_index]['id']['Inputvalue_id']=0;
									
									$count_parent=sizeof($form_structure['header'][1]['fields']);		
									for($cnt_parent=0;$cnt_parent<=$count_parent-1;$cnt_parent++)
									{	
										$form_structure["header"][1]['fields'][$cnt_parent]['id']['Inputvalue']='';
										$form_structure["header"][1]['fields'][$cnt_parent]['id']['Inputvalue_id']=0;
									}	

								}

								//FORM CUSTOM SETTING

						$form_structure["header"][0]['fields'][0]['req_type']['Inputvalue']='Standard Purchase Order';
						$form_structure["header"][0]['fields'][0]['req_type']['Inputvalue_id']=93;
						$form_structure["header"][0]['fields'][0]['status']['Inputvalue']='PO_ENTRY';

						$form_structure["header"][2]['fields'][0]['last_updated_by']['Inputvalue_id']=$setting['login_emp_id'];
						$form_structure["header"][2]['fields'][0]['last_updated_date_time']['Inputvalue_id']=date('Y-m-d H:i:s');

						//USER
						if($form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue_id']=='')
						{				

							$form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue_id']=$setting['login_emp_id'];							
							$form_structure["header"][2]['fields'][0]['req_preparer']['Inputvalue']=$setting['login_emp_name'];

							// $form_structure["header"][2]['fields'][0]['req_requiester']['Inputvalue_id']=$setting['login_emp_id'];							
							// $form_structure["header"][2]['fields'][0]['req_requiester']['Inputvalue']=$setting['login_emp_name'];			
							
							$form_structure["header"][2]['fields'][0]['req_organization']['Inputvalue_id']=$setting['req_organization_id'];							
							$form_structure["header"][2]['fields'][0]['req_organization']['Inputvalue']=$setting['req_organization_name'];	
							
							$form_structure["header"][2]['fields'][0]['req_location']['Inputvalue']=$setting['req_location'];	
							$form_structure["header"][2]['fields'][0]['req_accounting_date']['Inputvalue']=date('Y-m-d');

							$form_structure["header"][2]['fields'][0]['created_by']['Inputvalue_id']=	$setting['login_emp_id'];
							$form_structure["header"][2]['fields'][0]['create_date_time']['Inputvalue_id']=	date('Y-m-d H:i:s');

						}
					
						// if($form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue_id']=='')
						// {								
						// 	$form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue_id']=$setting['currency_id'];							
						// 	$form_structure["header"][0]['fields'][0]['req_currency_id']['Inputvalue']=$setting['currency_name'];
						// }
										

						$sql="select  id FieldID,req_number FieldVal  from invoice_summary where  status='REQUISITION' AND req_status=90 ";
						$datafields_array =$this->projectmodel->get_records_from_sql($sql);
						$form_structure["header"][0]['fields'][0]['parent_id']['datafields']=json_decode(json_encode($datafields_array), true);		
						$form_structure["header"][0]['fields'][0]['parent_id']['LabelName']='Enter Requisition';					


						//FORM CUSTOM SETTING END





						array_push($output,$form_structure);
						$this->FrmRptModel->tranfer_data($output);

					}

			

				}

		}
	

}


//TEMPLATE FORM REPORT IN ANGULARJS



public function Master_upload()
{
	
	$this->login_validate();
	$data=array();
	
	// insert into product master
	/*$sql_led="select product_id,tax_ledger_id,HSNCODE	from import_product_batch_data ";			
	$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
	foreach ($rowledgers as $rowledger)
	{ 
		
		 $save_hdr['tax_ledger_id']=$rowledger->tax_ledger_id;
		 $save_hdr['hsncode']=$rowledger->HSNCODE;
		 
		 $this->projectmodel->save_records_model($rowledger->product_id,'productmstr',$save_hdr);
	}*/
	
	//update batch 
	
	/*$sql_led="select * 	from import_product_batch_data ";			
	$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
	foreach ($rowledgers as $rowledger)
	{ 
		$id=$rowledger->id;
		$whr=" productname like '%".$rowledger->Name."%' ";
		$save_hdr['product_id']=$this->projectmodel->GetSingleVal('id','productmstr',$whr);	
	    $this->projectmodel->save_records_model($id,'import_product_BATCH_DATA',$save_hdr);
	}*/
	
	
		//DETAIL SETIONS
	
		
		
	$data['DisplayGrid']='NO';
	$data['msgdelete']="";
	if(isset($_POST['Upload']))
	{	
		
	
	if($_FILES['image1']['tmp_name']!='')
	{
			$setname='upload';	
			$uploads_dir='./uploads/';
			$tmp_name = $_FILES["image1"]["tmp_name"];
			$fileextension = substr(strrchr($_FILES["image1"]["name"], '.'), 1);
			$file_name=$setname.'.'.$fileextension;
			move_uploaded_file($tmp_name, "$uploads_dir/$file_name");	
			
				
			$frmrpttemplatehdrID=$this->input->post('SettingName');
			$temp_original=$this->input->post('temp_original');
			//$file_name='DOCTOR_FORMAT_FINALNEW.xls';
			//$file=HEARD_PATH.'uploads/'.$file_name;
			
			$this->excel_reader->read('./uploads/upload.xls');
			// Get the contents of the first worksheet
			$worksheet = $this->excel_reader->sheets[0];
			$highestRow = $worksheet['numRows']; // ex: 14
			$numCols = $worksheet['numCols']; // ex: 4
			$cells = $worksheet['cells']; // the 1st row are usually the field's name

			//PRODUCT_MASTER -SECTION
			if($frmrpttemplatehdrID=='PRODUCT_MASTER')
			{	
				//temporary UPLOAD
				if($temp_original=='FINAL')
				{
					$COLUMNS=3;
									
					$this->db->query("delete from import_product_master");
				//	$this->db->query("delete from productmstr");
					//$this->db->query("delete from misc_mstr");
					
					for ($row = 2; $row <= $highestRow; ++$row) 
					{			
						for ($ColNo = 1; $ColNo <= $COLUMNS;  $ColNo++)
						{	
												
							if( isset($cells[$row][$ColNo]) )
							{
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]=$cells[$row][$ColNo];
							}
							else
							{								
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]='0';							
							}
						}
						
						//print_r($save_hdr);
						//INSERT SECTION
						 $this->projectmodel->save_records_model('','import_product_master',$save_hdr);
					}

					// $this->db->query("delete from import_product_master 
					// where SVLNO=0 and DOCNAME='0'");
					$this->company_structure_model->UPDATE_MASTER('PRODUCT');	
				}

			}//DOCTOR_MASTER -SECTION

			
			if($frmrpttemplatehdrID=='PRODUCT_BATCH_DATA')
			{	
				//temporary UPLOAD
				if($temp_original=='FINAL')
				{
					$COLUMNS=7;									
					//$this->db->query("delete from import_product_BATCH_DATA");
									
					for ($row = 2; $row <= $highestRow; ++$row) 
					{			
						for ($ColNo = 1; $ColNo <= $COLUMNS;  $ColNo++)
						{	
												
							if( isset($cells[$row][$ColNo]) )
							{
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]=$cells[$row][$ColNo];
							}
							else
							{								
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]='0';							
							}
						}						
						 $this->projectmodel->save_records_model('','import_product_BATCH_DATA',$save_hdr);
					}
				}

			}
			
			//party_upload
			if($frmrpttemplatehdrID=='party_upload')
			{	
				
				if($temp_original=='FINAL')
				{
					$COLUMNS=4;									
					//$this->db->query("delete from import_product_BATCH_DATA");
									
					for ($row = 2; $row <= $highestRow; ++$row) 
					{			
						for ($ColNo = 1; $ColNo <= $COLUMNS;  $ColNo++)
						{	
												
							if( isset($cells[$row][$ColNo]) )
							{
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]=$cells[$row][$ColNo];
							}
							else
							{								
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]='0';							
							}
						}						
						 $this->projectmodel->save_records_model('','tbl_party',$save_hdr);
					}
				}

				$this->company_structure_model->UPDATE_MASTER('PARTY_LEDGER');	

			}
			//party_upload end
			

			//DOCTOR_UPLOAD -SECTION
			if($frmrpttemplatehdrID=='DOCTOR_UPLOAD')
			{	
				//temporary UPLOAD
				if($temp_original=='FINAL')
				{
					$COLUMNS=5;
									
					$this->db->query("delete from doctor_mstr");
					for ($row = 2; $row <= $highestRow; ++$row) 
					{			
						for ($ColNo = 1; $ColNo <= $COLUMNS;  $ColNo++)
						{	
												
							if( isset($cells[$row][$ColNo]) )
							{
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]=$cells[$row][$ColNo];
							}
							else
							{								
								$header_name =$cells[1][$ColNo];								
								$save_hdr[$header_name]='0';							
							}
						}
						//INSERT SECTION
						 $this->projectmodel->save_records_model('','doctor_mstr',$save_hdr);
						 $id_header=$this->db->insert_id();
						 $this->accounts_model->ledger_master_create('doctor_mstr',$id_header,312,'DOCTOR_MASTER'); 
					}

				}

			}//DOCTOR_UPLOAD -SECTION
			
		
			
		}
		
	}			
		
		$view_path_name='ActiveReport/UploadExcell';
	  	$this->page_layout_display($view_path_name,$data);
		
}

public function print_all($cond,$PRINTTYPE='BARCODE')
{
		
	$this->login_validate();
		
	if($PRINTTYPE=='BARCODE')
	{
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$cnt=1;
		$sql="select * from  invoice_details where  invoice_summary_id =".$cond;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{
				$barcode=$row1->id;
				$image=$row1->id.'.png';					
			//DOCUMENT
			//https://docs.zendframework.com/zend-barcode/objects/								
			$barcodeOptions = array('text' => $barcode, 'barHeight'=> 16,'barThickWidth'=>6,'barThinWidth'=>2,'drawText' => false);				
			// No required options
			$rendererOptions = array();				
			// Draw the barcode in a new image,
			$imageResource = Zend_Barcode::factory('code128', 'image', $barcodeOptions, $rendererOptions)->draw();				 				 					 
			/* $imageResource = Zend_Barcode::factory('code128','image', array('text'=>$barcode,'barHeight' => 30,'drawText' => false), array())->draw();*/				 
				imagepng($imageResource, 'uploads/purchase_barcode/'.$image);	
				$cnt=$cnt+1;	
		}

			// print lable section
			//http://biostall.com/codeigniter-labels-library/

			//$this->load->library('labels');        
			// Specify the format  
			//$config['format'] = 'word';  
			// Specify the address layout, using HTML <br /> tags to determine line breaks  
			// The elements listed here become the address array keys (see below)  
			//$config['layout'] = "first_name last_name<br />address_1<br />address_2<br />town<br />county<br />postcode";  
			//$this->labels->initialize($config);  


			$report_path='accounts_management/report/print_purchase_barcode';
			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$cond;		
			$data_print['PRINTTYPE']=$PRINTTYPE;			
			$this->report_page_layout_display($report_path,$data_print);

	}	

	if($PRINTTYPE=='LABEL')
	{
			$report_path='accounts_management/report/print_all';
			$data_print['table_name']='invoice_summary';
			$data_print['table_id']=$cond;		
			$data_print['PRINTTYPE']=$PRINTTYPE;			
			$this->report_page_layout_display($report_path,$data_print);
	}	
			
	
	if($PRINTTYPE=='RECEIVE_VOUCHER' || $PRINTTYPE=='PAYMENT_VOUCHER' )
	{
	
		$report_path='accounts_management/report/debit_credit_voucher';
		$data_print['table_name']='invoice_summary';
		$data_print['table_id']=$cond;		
		$data_print['PRINTTYPE']=$PRINTTYPE;			
		$this->report_page_layout_display($report_path,$data_print);

	}			 
		
}



public function TempleteFormReport($displaytype='',$id_header='',$id_detail='')
{

	$this->login_validate();
	
		$data['tran_link'] = ADMIN_BASE_URL.'Project_controller/TempleteFormReport/'; 
		$view_path_name='ActiveReport/TemplateFormRptMaster';	
		$sqlinv="select * from  frmrpttemplatehdr  order by  FormRptName ";		
		$data['projectlist'] =$this->projectmodel->get_records_from_sql($sqlinv);
		$data['MainTable'] =$data['tran_table_name']='';
		
				
		if($displaytype=="list")
		{
			//HEADER TABLE
			$data['id_header']=0;
			$data['id_detail']=0;
			$data['parent_id']=0;
			
			$data['NEWENTRYBUTTON']=$data['DisplayGrid']='';
			$data['FormRptName']=$data['Type']='';

			$data['GridHeader_name']=$data['DataFields_name']='';
			$data['DataFields2_name']=$data['DataFields3_name']=$data['DataFields4_name']='';

			$data['GridHeader']=$data['DataFields']='';
			$data['DataFields2']=$data['DataFields3']=$data['DataFields4']=$data['DataFields5']='';
			$data['TableName']=$data['WhereCondition']='';
			$data['OrderBy']=$data['ControllerFunctionLink']='';
			$data['child_ids']=$data['ViewPath']=$data['parent_table_field_name']=
			$data['child_table_field_name']='';
			
			
			//DETAIL TABLE
			$data['frmrpttemplatehdrID']=0;
			$data['InputName']=$data['InputType']=$data['Inputvalue']='';
			$data['LogoType']=$data['RecordSet']=$data['LabelName']='';
			$data['DIVClass']=$data['Section']='';
			$data['validation_type']=146;
		}
		
		
		if($displaytype=='addeditview')
		{
			//HEADER TABLE
			$data['id_header']=0;
			$data['id_detail']=0;
			$data['parent_id']=0;
			$data['NEWENTRYBUTTON']=$data['DisplayGrid']='';
			$data['FormRptName']=$data['Type']='';

			$data['GridHeader_name']=$data['DataFields_name']='';
			$data['DataFields2_name']=$data['DataFields3_name']=$data['DataFields4_name']='';
			$data['GridHeader']=$data['DataFields']='';
			$data['DataFields2']=$data['DataFields3']=$data['DataFields4']=$data['DataFields5']='';
			$data['TableName']=$data['WhereCondition']='';
			$data['OrderBy']=$data['ControllerFunctionLink']='';
			$data['child_ids']=$data['ViewPath']=$data['parent_table_field_name']=$data['child_table_field_name']='';
			
			//DETAIL TABLE
			$data['frmrpttemplatehdrID']=0;
			$data['InputName']=$data['InputType']=$data['Inputvalue']='';
			$data['LogoType']=$data['RecordSet']=$data['LabelName']='';
			$data['DIVClass']=$data['Section']='';
			$data['validation_type']=146;
			$data['tran_table_name']='';
			  
					$maxhdrid=0;
					$sqlinv="select max(id) maxhdrid from  frmrpttemplatedetails 		where  	frmrpttemplatehdrID=".$id_header;
					$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
					foreach ($rows as $row)
					{ $maxhdrid=$row->maxhdrid;}
				
				 if($maxhdrid>0)
			   	{	
				   $sqlinv="select * from  frmrpttemplatedetails 
					where  	id=".$maxhdrid;
					$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
					foreach ($rows as $row)
					{ 
					$data['tran_table_name']=$row->tran_table_name;
					$data['MainTable']=$row->MainTable;
					}
				}
				
				//tran header
				$sqlinv="select * from  frmrpttemplatehdr where id=".$id_header;
				$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
				foreach ($rows as $row)
				{ 	
					$data['id_header']=$id_header;
					$data['Type']=$row->Type;
					$data['FormRptName']=$row->FormRptName;

					$data['GridHeader_name']=$row->GridHeader_name;
					$data['DataFields_name']=$row->DataFields_name;
					$data['DataFields2_name']=$row->DataFields2_name;
					$data['DataFields3_name']=$row->DataFields3_name;
					$data['DataFields4_name']=$row->DataFields4_name;
					
					$data['GridHeader']=$row->GridHeader;
					$data['DataFields']=$row->DataFields;
					$data['DataFields2']=$row->DataFields2;
					$data['DataFields3']=$row->DataFields3;
					$data['DataFields4']=$row->DataFields4;;
					$data['DataFields5']=$row->DataFields5;

					$data['TableName']=$row->TableName;
					$data['WhereCondition']=$row->WhereCondition;
					$data['OrderBy']=$row->OrderBy;
					$data['ControllerFunctionLink']=$row->ControllerFunctionLink;
					$data['ViewPath']=$row->ViewPath;
					$data['NEWENTRYBUTTON']=$row->NEWENTRYBUTTON;
					$data['DisplayGrid']=$row->DisplayGrid;
					$data['parent_id']=$row->parent_id;
					$data['parent_table_field_name']=$row->parent_table_field_name;
					$data['child_table_field_name']=$row->child_table_field_name;
					$data['child_ids']=$row->child_ids;
				}
		}	
		
		
		if(isset($_POST) and $displaytype=='save')
		{
			
			//HEADER ENTRY part
			
			$save_hdr['FormRptName']=$this->input->post('FormRptName');;
			$save_hdr['Type']=$this->input->post('Type');


			$save_hdr['GridHeader_name']=$this->input->post('GridHeader_name');
			$save_hdr['DataFields_name']=$this->input->post('DataFields_name');
			$save_hdr['DataFields2_name']=$this->input->post('DataFields2_name');
			$save_hdr['DataFields3_name']=$this->input->post('DataFields3_name');
			$save_hdr['DataFields4_name']=$this->input->post('DataFields4_name');
			
			$save_hdr['GridHeader']=$this->input->post('GridHeader');
			$save_hdr['DataFields']=$this->input->post('DataFields');
			$save_hdr['DataFields2']=$this->input->post('DataFields2');
			$save_hdr['DataFields3']=$this->input->post('DataFields3');
			$save_hdr['DataFields4']=$this->input->post('DataFields4');
			$save_hdr['DataFields5']=$this->input->post('DataFields5');
			
			$save_hdr['TableName']=$this->input->post('TableName');
			$save_hdr['WhereCondition']=$this->input->post('WhereCondition');
			$save_hdr['OrderBy']=$this->input->post('OrderBy');
			$save_hdr['ControllerFunctionLink']=$this->input->post('ControllerFunctionLink');
			$save_hdr['ViewPath']=$this->input->post('ViewPath');
			$save_hdr['NEWENTRYBUTTON']=$this->input->post('NEWENTRYBUTTON');
			$save_hdr['DisplayGrid']=$this->input->post('DisplayGrid');

			$save_hdr['parent_id']=$this->input->post('parent_id');
			$save_hdr['parent_table_field_name']=$this->input->post('parent_table_field_name');
			$save_hdr['child_table_field_name']=$this->input->post('child_table_field_name');
			$save_hdr['child_ids']=$this->input->post('child_ids');
			
			if($id_header==0) 
			{					
				$this->projectmodel->save_records_model($id_header,
				'frmrpttemplatehdr',$save_hdr);
				$id_header=$this->db->insert_id();
				$this->session->set_userdata('alert_msg',
				'One Record Inserted Successfully');
			}	
			
			if($id_header>0)// update data....
			{
				$this->projectmodel->save_records_model($id_header,
				'frmrpttemplatehdr',$save_hdr);
				$this->session->set_userdata('alert_msg', 
				'One Record Updated Successfully');						
			}
				
			//DETAIL SECTIONS 
				//ADD SECTION
				$save_dtl['frmrpttemplatehdrID']=$id_header;
				$save_dtl['InputName']=trim($this->input->post('InputName'));
				$save_dtl['InputType']=trim($this->input->post('InputType'));
				$save_dtl['Inputvalue']=trim($this->input->post('Inputvalue'));
				$save_dtl['LabelName']=trim($this->input->post('LabelName'));
				$save_dtl['LogoType']=trim($this->input->post('LogoType'));
				$save_dtl['DIVClass']=trim($this->input->post('DIVClass'));
				$save_dtl['Section']=trim($this->input->post('Section'));
				$save_dtl['tran_table_name']=trim($this->input->post('tran_table_name'));
				
				$save_dtl['FieldOrder']=trim($this->input->post('FieldOrder'));
				$save_dtl['datafields']=trim($this->input->post('datafields'));
				//$save_dtl['table_name']=$this->input->post('table_name');
				//$save_dtl['where_condition']=$this->input->post('where_condition');
				//$save_dtl['orderby']=$this->input->post('orderby');
				$save_dtl['SectionType']=trim($this->input->post('SectionType'));
				$save_dtl['MainTable']=trim($this->input->post('MainTable'));
				$save_dtl['LinkField']=trim($this->input->post('LinkField'));
				$save_dtl['validation_type']=trim($this->input->post('validation_type'));
				
			

				/*$save_dtl['RecordSet']=$this->input->post('RecordSet');*/
												
				if($save_dtl['InputName']<>'')
				{
					$this->projectmodel->save_records_model('',
					'frmrpttemplatedetails',$save_dtl);
				}
				
			//EDIT SECTION	
			$sql="select *  from frmrpttemplatedetails 		where frmrpttemplatehdrID=".$id_header."  ";
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{ 
				$dtl_id=$row1->id;
				$save_dtl['frmrpttemplatehdrID']=$id_header;
				$save_dtl['InputName']=trim($this->input->post('InputName'.$dtl_id));
				$save_dtl['InputType']=trim($this->input->post('InputType'.$dtl_id));
				$save_dtl['Inputvalue']=trim($this->input->post('Inputvalue'.$dtl_id));
				$save_dtl['LogoType']=trim($this->input->post('LogoType'.$dtl_id));
				$save_dtl['LabelName']=trim($this->input->post('LabelName'.$dtl_id));
				$save_dtl['DIVClass']=trim($this->input->post('DIVClass'.$dtl_id));
				$save_dtl['Section']=trim($this->input->post('Section'.$dtl_id));
				$save_dtl['tran_table_name']=trim($this->input->post('tran_table_name'.$dtl_id));
				
				$save_dtl['FieldOrder']=trim($this->input->post('FieldOrder'.$dtl_id));
				$save_dtl['datafields']=trim($this->input->post('datafields'.$dtl_id));
				$save_dtl['table_name']=trim($this->input->post('table_name'.$dtl_id));
				$save_dtl['where_condition']=trim($this->input->post('where_condition'.$dtl_id));
				$save_dtl['orderby']=trim($this->input->post('orderby'.$dtl_id));
				$save_dtl['SectionType']=trim($this->input->post('SectionType'.$dtl_id));
				$save_dtl['MainTable']=trim($this->input->post('MainTable'.$dtl_id));
				$save_dtl['LinkField']=trim($this->input->post('LinkField'.$dtl_id));
				$save_dtl['validation_type']=trim($this->input->post('validation_type'.$dtl_id));

			   $data['MainTable'] =$save_dtl['MainTable'];
			   $data['tran_table_name']=$save_dtl['tran_table_name'];
										
				if($save_dtl['InputName']<>'')
				{$this->projectmodel->save_records_model($dtl_id,'frmrpttemplatedetails',$save_dtl);}
			
			}		
				
				redirect('Project_controller/TempleteFormReport/addeditview/'.
				$id_header.'/0/');
		}
		
			
		if($displaytype=='delete')
		{
		$sql="delete from frmrpttemplatedetails  where id=".$id_detail;
		$this->db->query($sql);
		redirect('Project_controller/TempleteFormReport/addeditview/'.
				$id_header.'/0/');
		}
		
		if($displaytype=='deleteAll')
		{
			$sql="delete from  frmrpttemplatedetails 
			 where frmrpttemplatehdrID=".$id_header;
			$this->db->query($sql);	
			$this->session->set_userdata('alert_msg','Deleted!');
					
			$sql="delete from  frmrpttemplatehdr  where id=".$id_header;
			$this->db->query($sql);	
		    redirect('Project_controller/TempleteFormReport/list/0/0/');
		}

		if($displaytype=='copy_create')
		{
			

			$sql="insert into frmrpttemplatehdr(parent_id,section_order,parent_table_field_name,child_table_field_name,
			child_ids,FormRptName,Type,GridHeader,DataFields,DataFields2,DataFields3,DataFields4,DataFields5,
			TableName,WhereCondition,OrderBy,ControllerFunctionLink,ViewPath,DisplayGrid,NEWENTRYBUTTON) 

			select parent_id,section_order,parent_table_field_name,child_table_field_name,child_ids,FormRptName,
			Type,GridHeader,DataFields,DataFields2,
			DataFields3,DataFields4,DataFields5,TableName,WhereCondition,OrderBy,ControllerFunctionLink,
			ViewPath,DisplayGrid,NEWENTRYBUTTON from  frmrpttemplatehdr where id=".$id_header;
			$this->db->query($sql);	
			$id_header_copy=$this->db->insert_id();

						
			$records="select *  from frmrpttemplatedetails 	where frmrpttemplatehdrID=".$id_header."  ";
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{ 
				
				$save_dtl['frmrpttemplatehdrID']=$id_header_copy;
				$save_dtl['InputName']=$record->InputName;
				$save_dtl['InputType']=$record->InputType;
				$save_dtl['Inputvalue']=$record->Inputvalue;
				$save_dtl['LogoType']=$record->LogoType;
				$save_dtl['LabelName']=$record->LabelName;
				$save_dtl['DIVClass']=$record->DIVClass;
				$save_dtl['Section']=$record->Section;
				$save_dtl['tran_table_name']=$record->tran_table_name;
				
				$save_dtl['FieldOrder']=$record->FieldOrder;;
				$save_dtl['datafields']=$record->datafields;;
				$save_dtl['table_name']=$record->table_name;;
				$save_dtl['where_condition']=$record->where_condition;;
				$save_dtl['orderby']=$record->orderby;;
				$save_dtl['SectionType']=$record->SectionType;;
				$save_dtl['MainTable']=$record->MainTable;;
				$save_dtl['LinkField']=$record->LinkField;;
				$save_dtl['validation_type']=$record->validation_type;

				$this->projectmodel->save_records_model('','frmrpttemplatedetails',$save_dtl);
			
			}	


			$this->session->set_userdata('alert_msg','Created!');

		    redirect('Project_controller/TempleteFormReport/list/'.$id_header_copy.'/0/');
		}

		
		$this->page_layout_display($view_path_name,$data);
		
		
}

public function TempleteFormReport_old($displaytype='',$id_header='',$id_detail='')
{

	$this->login_validate();
      //  return $imageResource;   
    
		
		$data['tran_link'] = ADMIN_BASE_URL.'Project_controller/TempleteFormReport/'; 
		$view_path_name='ActiveReport/TemplateFormRptMaster';	
		$sqlinv="select * from  frmrpttemplatehdr  order by  FormRptName ";		
		$data['projectlist'] =$this->projectmodel->get_records_from_sql($sqlinv);
		$data['MainTable'] =$data['tran_table_name']='';
		
		
		if($displaytype=="list")
		{
			//HEADER TABLE
			$data['id_header']=0;
			$data['id_detail']=0;
			
			$data['NEWENTRYBUTTON']=$data['DisplayGrid']='';
			$data['FormRptName']=$data['Type']='';
			$data['GridHeader']=$data['DataFields']='';
			$data['TableName']=$data['WhereCondition']='';
			$data['OrderBy']=$data['ControllerFunctionLink']='';
			$data['ViewPath']='';
			
			
			//DETAIL TABLE
			$data['frmrpttemplatehdrID']=0;
			$data['InputName']=$data['InputType']=$data['Inputvalue']='';
			$data['LogoType']=$data['RecordSet']=$data['LabelName']='';
			$data['DIVClass']=$data['Section']='';
		}
		
		
		if($displaytype=='addeditview')
		{
			//HEADER TABLE
			$data['id_header']=0;
			$data['id_detail']=0;
			$data['NEWENTRYBUTTON']=$data['DisplayGrid']='';
			$data['FormRptName']=$data['Type']='';
			$data['GridHeader']=$data['DataFields']='';
			$data['TableName']=$data['WhereCondition']='';
			$data['OrderBy']=$data['ControllerFunctionLink']='';
			$data['ViewPath']='';
			
			//DETAIL TABLE
			$data['frmrpttemplatehdrID']=0;
			$data['InputName']=$data['InputType']=$data['Inputvalue']='';
			$data['LogoType']=$data['RecordSet']=$data['LabelName']='';
			$data['DIVClass']=$data['Section']='';
			$data['tran_table_name']='';
			  
					$maxhdrid=0;
					$sqlinv="select max(id) maxhdrid from  frmrpttemplatedetails 
					where  	frmrpttemplatehdrID=".$id_header;
					$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
					foreach ($rows as $row)
					{ $maxhdrid=$row->maxhdrid;}
				
				 if($maxhdrid>0)
			   	{	
				   $sqlinv="select * from  frmrpttemplatedetails 
					where  	id=".$maxhdrid;
					$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
					foreach ($rows as $row)
					{ 
					$data['tran_table_name']=$row->tran_table_name;
					$data['MainTable']=$row->MainTable;
					}
				}
				
				//tran header
				$sqlinv="select * from  frmrpttemplatehdr where id=".$id_header;
				$rows =$this->projectmodel->get_records_from_sql($sqlinv);	
				foreach ($rows as $row)
				{ 	
					$data['id_header']=$id_header;
					$data['Type']=$row->Type;
					$data['FormRptName']=$row->FormRptName;
					$data['GridHeader']=$row->GridHeader;
					$data['DataFields']=$row->DataFields;
					$data['TableName']=$row->TableName;
					$data['WhereCondition']=$row->WhereCondition;
					$data['OrderBy']=$row->OrderBy;
					$data['ControllerFunctionLink']=$row->ControllerFunctionLink;
					$data['ViewPath']=$row->ViewPath;
					$data['NEWENTRYBUTTON']=$row->NEWENTRYBUTTON;
					$data['DisplayGrid']=$row->DisplayGrid;
				}
		}	
		
		
		if(isset($_POST) and $displaytype=='save')
		{
			
			//HEADER ENTRY part
			
			$save_hdr['FormRptName']=$this->input->post('FormRptName');;
			$save_hdr['Type']=$this->input->post('Type');
			
			$save_hdr['GridHeader']=$this->input->post('GridHeader');
			$save_hdr['DataFields']=$this->input->post('DataFields');
			$save_hdr['TableName']=$this->input->post('TableName');
			$save_hdr['WhereCondition']=$this->input->post('WhereCondition');
			$save_hdr['OrderBy']=$this->input->post('OrderBy');
			$save_hdr['ControllerFunctionLink']=
			$this->input->post('ControllerFunctionLink');
			$save_hdr['ViewPath']=$this->input->post('ViewPath');
			$save_hdr['NEWENTRYBUTTON']=$this->input->post('NEWENTRYBUTTON');
			$save_hdr['DisplayGrid']=$this->input->post('DisplayGrid');
					
			
			if($id_header==0) 
			{					
				$this->projectmodel->save_records_model($id_header,
				'frmrpttemplatehdr',$save_hdr);
				$id_header=$this->db->insert_id();
				$this->session->set_userdata('alert_msg',
				'One Record Inserted Successfully');
			}	
			
			if($id_header>0)// update data....
			{
				$this->projectmodel->save_records_model($id_header,
				'frmrpttemplatehdr',$save_hdr);
				$this->session->set_userdata('alert_msg', 
				'One Record Updated Successfully');						
			}
				
			//DETAIL SECTIONS 
				//ADD SECTION
				$save_dtl['frmrpttemplatehdrID']=$id_header;
				$save_dtl['InputName']=$this->input->post('InputName');
				$save_dtl['InputType']=$this->input->post('InputType');
				$save_dtl['Inputvalue']=$this->input->post('Inputvalue');
				$save_dtl['LabelName']=$this->input->post('LabelName');
				$save_dtl['LogoType']=$this->input->post('LogoType');
				$save_dtl['DIVClass']=$this->input->post('DIVClass');
				$save_dtl['Section']=$this->input->post('Section');
				$save_dtl['tran_table_name']=$this->input->post('tran_table_name');
				
				$save_dtl['FieldOrder']=$this->input->post('FieldOrder');
				$save_dtl['datafields']=$this->input->post('datafields');
				//$save_dtl['table_name']=$this->input->post('table_name');
				//$save_dtl['where_condition']=$this->input->post('where_condition');
				//$save_dtl['orderby']=$this->input->post('orderby');
				$save_dtl['SectionType']=$this->input->post('SectionType');
				$save_dtl['MainTable']=$this->input->post('MainTable');
				$save_dtl['LinkField']=$this->input->post('LinkField');
				
				/*$save_dtl['RecordSet']=$this->input->post('RecordSet');*/
												
				if($save_dtl['InputName']<>'')
				{
					$this->projectmodel->save_records_model('',
					'frmrpttemplatedetails',$save_dtl);
				}
				
			//EDIT SECTION	
			$sql="select *  from frmrpttemplatedetails 
			where frmrpttemplatehdrID=".$id_header."  ";
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{ 
				$dtl_id=$row1->id;
				$save_dtl['frmrpttemplatehdrID']=$id_header;
				$save_dtl['InputName']=$this->input->post('InputName'.$dtl_id);
				$save_dtl['InputType']=$this->input->post('InputType'.$dtl_id);
				$save_dtl['Inputvalue']=$this->input->post('Inputvalue'.$dtl_id);
				$save_dtl['LogoType']=$this->input->post('LogoType'.$dtl_id);
				$save_dtl['LabelName']=$this->input->post('LabelName'.$dtl_id);
				$save_dtl['DIVClass']=$this->input->post('DIVClass'.$dtl_id);
				$save_dtl['Section']=$this->input->post('Section'.$dtl_id);
				$save_dtl['tran_table_name']=$this->input->post('tran_table_name'.$dtl_id);
				
				$save_dtl['FieldOrder']=$this->input->post('FieldOrder'.$dtl_id);
				$save_dtl['datafields']=$this->input->post('datafields'.$dtl_id);
				$save_dtl['table_name']=$this->input->post('table_name'.$dtl_id);
				$save_dtl['where_condition']=$this->input->post('where_condition'.$dtl_id);
				$save_dtl['orderby']=$this->input->post('orderby'.$dtl_id);
				$save_dtl['SectionType']=$this->input->post('SectionType'.$dtl_id);
				$save_dtl['MainTable']=$this->input->post('MainTable'.$dtl_id);
				 $save_dtl['LinkField']=$this->input->post('LinkField'.$dtl_id);
				
			   $data['MainTable'] =$save_dtl['MainTable'];
			   $data['tran_table_name']=$save_dtl['tran_table_name'];
										
				if($save_dtl['InputName']<>'')
				{
				$this->projectmodel->save_records_model($dtl_id,
					'frmrpttemplatedetails',$save_dtl);
				}
			
			}		
				
				redirect('Project_controller/TempleteFormReport/addeditview/'.
				$id_header.'/0/');
		}
		
			
		if($displaytype=='delete')
		{
		$sql="delete from frmrpttemplatedetails  where id=".$id_detail;
		$this->db->query($sql);
		redirect('Project_controller/TempleteFormReport/addeditview/'.
				$id_header.'/0/');
		}
		
		if($displaytype=='deleteAll')
		{
			$sql="delete from  frmrpttemplatedetails 
			 where frmrpttemplatehdrID=".$id_header;
			$this->db->query($sql);	
			$this->session->set_userdata('alert_msg','Deleted!');
					
			$sql="delete from  frmrpttemplatehdr  where id=".$id_header;
			$this->db->query($sql);	
		    redirect('Project_controller/TempleteFormReport/list/0/0/');
		}
		
		$this->page_layout_display($view_path_name,$data);
		
		
}


public function TempleteForm($frmrpttemplatehdrID=2,$operation='',$id_header='',$id_detail='')
{
		$this->login_validate();
		//DATA GRID SECTION
		  $data['id']=$id_header;	
		 if($frmrpttemplatehdrID==7)//PRODUCT MASTER
		 {$data['GridHeader']=array("SysId#-left", "FieldID-left", "FieldVal-left", "Status-left");}		
		 		
		 if($frmrpttemplatehdrID==2)//PRODUCT MASTER
		 {$data['GridHeader']= array("SysId#-left", "Product Name-left");}	
		 	 
		 if($frmrpttemplatehdrID==21) //BRAND MASTER
		 {$data['GridHeader']= array("SysId#-left", "Product Name-left");}	
		 	
		 if($frmrpttemplatehdrID==22) //PRODUCT GROUP
		 {$data['GridHeader']=array("SysId#-left", "Product Name-left");}
		 
		 if($frmrpttemplatehdrID==23) //ACCOUNT GROUP
		 {$data['GridHeader']=array("SysId#-left", "Code-left","A/c name-left","Under A/c-left");}
		 
		 if($frmrpttemplatehdrID==24) //ACCOUNT GROUP
		 {$data['GridHeader']=array("SysId#-left", "Code-left","A/c name-left","Under A/c-left");}
		 
		 if($frmrpttemplatehdrID==25) //ACCOUNT GROUP
		 {$data['GridHeader']= array("SysId#-left", "Code-left","Name-left");}
		 
		 if($frmrpttemplatehdrID==9) //Vendor Mastet
		 {$data['GridHeader']= array("SysId#-left", "Name-left","Address-left","Mob No-left","Email-left","Type-left","Status-left");}
		 
		 
		 
		 if($frmrpttemplatehdrID==8) //Retailer  Mastet
		 {$data['GridHeader']= array("SysId#-left", "NAME-left","ADDRESS-left");}

		 if($frmrpttemplatehdrID==26) //STATE MASTER
		 {$data['GridHeader']= array("SysId#-left", "State Name-left");}
				 
		 
		 if($frmrpttemplatehdrID==32) //Menu Detail master
		 { $data['GridHeader']=array("SysId#-left", "Name-left", "menu_order-left"); }
		 
		 if($frmrpttemplatehdrID==30) //QUERY BUILDER
		 {$data['GridHeader']=array("SysId#-left","FormRptName-left","Query_name-left"); }
		 
		if($frmrpttemplatehdrID==31) //user master
		 {$data['GridHeader']=array("SysId#-left","Name-left","Login Type -left","Company-left"); }

		 if($frmrpttemplatehdrID==38) //POTENCY MASTER
		 {$data['GridHeader']=array("SysId#-left", "Potency Name-left", "Group Name-left");}

		 if($frmrpttemplatehdrID==39) //PACK SIZE
		 {$data['GridHeader']=array("SysId#-left", "Pack Name-left", "Group Name-left");}

		 if($frmrpttemplatehdrID==40) //DOSE WISE PARCENTAGE
		 {$data['GridHeader']=array("SysId#-left", "Dose Name-left", "Group Name-left");}

		//  $id_header=129;
		//  $save_data_primary_details = "select * from invoice_details where   invoice_summary_id=".$id_header."  " ;		
		//  $save_data_primary_details = $this->projectmodel->get_records_from_sql($save_data_primary_details);
		//  $save_data_primary_details = json_decode(json_encode($save_data_primary_details), true);
		//  $count_detail=sizeof($save_data_primary_details); 
		//  for($cnt=0;$cnt<$count_detail;$cnt++)
		//  {$save_data_primary_details[$cnt]['id']='';$save_data_primary_details[$cnt]['status']='PURCHASE';}

		//  	print_r($save_data_primary_details);
		 





		 
		$data['frmrpttemplatehdrID']=$frmrpttemplatehdrID;
		$view_path_name='';
		
		$records="select * from frmrpttemplatehdr where id=".$frmrpttemplatehdrID;
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{	
			 $data['DisplayGrid']=$record->DisplayGrid;
			 $data['NEWENTRYBUTTON']=$record->NEWENTRYBUTTON;
			 
			 $data['FormRptName']=$record->FormRptName;
			 $data['DataFields']=$record->DataFields;
			 $data['TableName']=$record->TableName;
			 $data['WhereCondition']=$record->WhereCondition;
			 $data['OrderBy']=$record->OrderBy;	
			 $ControllerFunctionLink=$record->ControllerFunctionLink.$frmrpttemplatehdrID.'/';	 
			 $data['tran_link'] = ADMIN_BASE_URL.$ControllerFunctionLink; 
			 $view_path_name=$record->ViewPath; 
			 $data['body']=$this->projectmodel->Activequery($data,'LIST');
		}
		
		$Party_Type='';		
		if($operation=="save" )
		{
			//HEADER SECTION
			$records="select * from frmrpttemplatedetails 
			where frmrpttemplatehdrID=".$frmrpttemplatehdrID."
			 and SectionType='HEADER' order by Section ";
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{		
			 $id_header=$this->input->post('id');
			//echo $record->InputName.'<br>';
			 $save_hdr[$record->InputName]=$this->input->post(trim($record->InputName));
			 $tran_table_name=$record->tran_table_name;
			 
			 if(trim($record->InputName)=='Party_Type')
			 {$Party_Type=$this->input->post(trim($record->InputName));}
			 
			}
			
			 if($frmrpttemplatehdrID==7) //General Mastet
		 	{
				//ACCOUNTS SECTIONS
				$multi_selects=$this->input->post('DEBIT_LEDGER');			
				$save_hdr['DEBIT_LEDGER']='0';
				if (count($multi_selects) > 0) {
				for ($i=0;$i<count($multi_selects);$i++) {
				 $save_hdr['DEBIT_LEDGER']=$save_hdr['DEBIT_LEDGER'].','.
				$multi_selects[$i];
				}} 	
				
				$multi_selects=$this->input->post('CREDIT_LEDGER');			
				$save_hdr['CREDIT_LEDGER']='0';
				if (count($multi_selects) > 0) {
				for ($i=0;$i<count($multi_selects);$i++) {
				 $save_hdr['CREDIT_LEDGER']=$save_hdr['CREDIT_LEDGER'].','.
				$multi_selects[$i];
				}} 	
				
				$multi_selects=$this->input->post('DEBIT_GROUP');			
				$save_hdr['DEBIT_GROUP']='0';
				if (count($multi_selects) > 0) {
				for ($i=0;$i<count($multi_selects);$i++) {
				 $save_hdr['DEBIT_GROUP']=$save_hdr['DEBIT_GROUP'].','.
				$multi_selects[$i];
				}} 	
				
				$multi_selects=$this->input->post('CREDIT_GROUP');			
				$save_hdr['CREDIT_GROUP']='0';
				if (count($multi_selects) > 0) {
				for ($i=0;$i<count($multi_selects);$i++) {
				 $save_hdr['CREDIT_GROUP']=$save_hdr['CREDIT_GROUP'].','.
				$multi_selects[$i];
				}} 	
			}
			if($frmrpttemplatehdrID==24){  $save_hdr['company_id']=$this->session->userdata('COMP_ID');}
						
			if($id_header==0) 
			{					
				if($data['NEWENTRYBUTTON']=='YES'){
				
					$this->projectmodel->save_records_model($id_header,$tran_table_name,$save_hdr);
					$id_header=$this->db->insert_id();
				}				
			}				
			if($id_header>0)// update data....
			{
				$this->projectmodel->save_records_model($id_header,$tran_table_name,$save_hdr);
			}
			
			
			
			 if($frmrpttemplatehdrID==9) //PARTY / VENDOR 
			 {	
			 	if($Party_Type=='REGULAR_CREDITOR' or $Party_Type=='COMPOSITE_CREDITOR')
				{	
				$this->accounts_model->ledger_master_create('tbl_party',$id_header,27,'SUNDRY_CREDITORS');
				}
				else
				{
				$this->accounts_model->ledger_master_create('tbl_party',$id_header,28,'SUNDRY_DEBTORS');
				}				
			 }
			 
			 if($frmrpttemplatehdrID==25) //doctor master
			 {	
			 	$this->accounts_model->ledger_master_create('doctor_mstr',$id_header,312,'DOCTOR_MASTER');
			 }

			 if($frmrpttemplatehdrID==8) //company setting
			 {	
				$count=0;
				$products="select count(*) cnt from  product_balance_companywise where company_id=".$id_header;
				$products = $this->projectmodel->get_records_from_sql($products);
				foreach ($products as $product){$count=$product->cnt;}

				if($count==0)
				{
					$sql="insert into product_balance_companywise(product_id,company_id	) select id,".$id_header." from productmstr";
					$this->db->query($sql);
				}
			 }
			
			//HEADER SECTION END 
			redirect($ControllerFunctionLink.'addeditview/0/0/');
		}
				
		   
	  
	   $this->page_layout_display($view_path_name,$data);

}

	
	public function index($msg='')
	{	
	$layout_data = array();
	$data = array();
	$layout_date['body'] = $this->load->view('login',$data,true);
	$this->load->view('login', $layout_date);
	}
	
	private function home()
	{
		$layout_data = array();
		$data = array();
		 $view_path_name='dashboard';
		$this->page_layout_display($view_path_name,$data);
	}
	
	public function logout()
	{
		$this->projectmodel->logout(); 
	}
	
	public function login_process(){
        // Validate the user can login
        $result = $this->projectmodel->validate();
        // Now we verify the result
        if(! $result){
            // If user did not validate, then show them login page again
            $this->index();
        }else{
           
		   $this->home();
        }    
		
				
    }
	private function login_validate()
	{
       if($this->session->userdata('login_userid')=='')
		{ $this->logout();}
			else
	   {
	        //  $COMP_ID='';
			//  $sqlemp="select * from company_details where id=1";
			//  $rowrecordemp = $this->projectmodel->get_records_from_sql($sqlemp);	
			//  foreach ($rowrecordemp as $rowemp)
			//  {$this->session->set_userdata('COMP_ID', $rowemp->COMP_ID);}	
	   }	
	   
    }
	

	/*PASSWORD CHANGE*/

	function changepassword($msg=''){
	
		$layout_data = array();
		$data = array();
		$data['msg'] = $msg;
		//$this->authmod->is_admin_login();
		$view_path_name='changepass';
		$this->page_layout_display($view_path_name,$data);	
	}
	
	function changepassword_act()
	{
			$this->form_validation->set_rules('pass1', 'Old Password', 'required');
			$this->form_validation->set_rules('pass2', 'New Password', 'required');
			$this->form_validation->set_rules('pass3', 
			'For Confermation Same New Password is', 'required|matches[pass2]');
			if ($this->form_validation->run())
			{
				$value = $this->input->post();
				if(!$this->projectmodel->update_password($value))
				{
					$this->changepassword("Invalid old password");
				}
			}
			else
			{
				$value = $this->input->post();
				$this->changepassword();
			}		
	}
	/*LOGIN PROCESS END*/	
	
	public function doctor_commission_set($operation_type='listing',$id=0)
	{
		$this->login_validate();
		$layout_data=array();
		$data=array();
		$data['doctor_mstr_id']=0;
		$data['tran_link'] =ADMIN_BASE_URL.'Project_controller/doctor_commission_set/';
		
		$sqlinv="select * from doctor_mstr where  status='ACTIVE' order by  name";			
		$data['doctor_list'] =$this->projectmodel->get_records_from_sql($sqlinv); 
					
		if(isset($_POST['submit']))
		{	
			if($operation_type=='listing')
			{$data['doctor_mstr_id']=$this->input->post('doctor_mstr_id');}
			
			if($operation_type=='save')
			{
				//echo $operation_type;
				 $data['doctor_mstr_id']=$this->input->post('doctor_mstr_id');	
				$this->form_validation->set_rules('doctor_mstr_id','Doctot','required');
				if($this->form_validation->run()==FALSE )
				{
					$this->session->set_userdata('alert_msg', validation_errors());
					redirect('Project_controller/doctor_commission_set/listing/');
				}
				else  //validation pass
				{
				
					$doctor_mstr_id=$save_data['doctor_mstr_id']=$this->input->post('doctor_mstr_id');	
					
					$sql="select * from misc_mstr where  mstr_type='PRODUCT_GROUP' ";
					$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
					foreach ($rowrecord as $row1)
					{
					    $product_group_id=$row1->id;
						$save_data['product_group_id']=$product_group_id;						
						$save_data['commission_percentage']=$this->input->post('doc_commission'.$product_group_id);
						$insert_id='';
						$sql_exp="select * from doctor_commission_set where 
						doctor_mstr_id=".$doctor_mstr_id." and product_group_id=".$product_group_id." ";
						$expenses = $this->projectmodel->get_records_from_sql($sql_exp);	
						foreach ($expenses as $expense)
						{$insert_id=$expense->id;}
						$this->projectmodel->save_records_model($insert_id,'doctor_commission_set',$save_data);
					}	
						
				}	//end else
				$this->session->set_userdata('alert_msg','Record Updated Successfully');
			}	// end save 	
			
		}	// end post
		
	   $view_path_name='accounts_management/master/doctor_commission_set';
	   $this->page_layout_display($view_path_name,$data);
		   
	}	
	
	
	private function report_page_layout_display($view_path_name,$data)
	{
		   $layout_data['data_info'] = 
		   $this->load->view($view_path_name,$data, true);			
		   $layout_data['body'] = $this->load->view('common/body', $layout_data, true);		 
		   $this->load->view('report_layout', $layout_data);
		   $this->session->set_userdata('alert_msg', '');	
	}	
	private function page_layout_display($view_path_name,$data)
	{
		
		   $data['user_name'] = $this->session->userdata('user_name');
		   $layout_data['left_bar'] = $this->load->view('common/left_bar', '', true);
		   $layout_data['top_menu'] = $this->load->view('common/top_menu', $data, true);
		   $layout_data['data_info'] = 
		   $this->load->view($view_path_name,$data, true);			
		   $layout_data['body'] = $this->load->view('common/body', $layout_data, true);
		   $this->load->view('layout', $layout_data);
		   $this->session->set_userdata('alert_msg', '');	
	
	}


	
}

?>
