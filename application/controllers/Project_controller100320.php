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
			$this->load->model('accounts_model');
			$this->load->library('numbertowords');
			$this->load->library('pdf');
			$this->load->helper('file'); 
			$this->load->library('Highcharts');	
			$this->load->library('general_library');
			$this->load->library('excel');
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
					$COLUMNS=7;
									
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
						 $this->projectmodel->save_records_model('',
						 'import_product_master',$save_hdr);
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
				imagepng($imageResource, 'uploads/'.$image);	
				$cnt=$cnt+1;	
		}

			// print lable section
			//http://biostall.com/codeigniter-labels-library/

			$this->load->library('labels');        
			// Specify the format  
			$config['format'] = 'word';  
			// Specify the address layout, using HTML <br /> tags to determine line breaks  
			// The elements listed here become the address array keys (see below)  
			$config['layout'] = "first_name last_name<br />address_1<br />address_2<br />town<br />county<br />postcode";  
			$this->labels->initialize($config);  


			$report_path='accounts_management/report/print_all';
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
