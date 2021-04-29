<?php
class Project_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
		//$this->load->model('thumb_model', 'thumb');
    }
	
    /*LOGIN LOGOUT*/
			
	public function validate(){
        // grab user input
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
      
		 $cnt=0;
		 $records="select count(*) cnt from tbl_employee_mstr where userid='$username' and password='$password' and status<>'INACTIVE'";
		 $records = $this->get_records_from_sql($records);	
		 foreach ($records as $record)
		 {$cnt=$record->cnt;}
		
        // // Prep the query
        // $this->db->where('userid', $username);
        // $this->db->where('password', $password);
		// $this->db->where('status <>', 'INACTIVE');
        // $this->db->where('tbl_designation_id <>',6);
        // // Run the query
        // $query = $this->db->get('tbl_employee_mstr');
		// // Let's check if there are any results
		
        if($cnt>0)
        {
			//$row = $query->row();			
			//$this->session->set_userdata('billing_emp_desig', 13);
			//$this->session->set_userdata('billing_emp_id',141);
			 
			// if($row->login_status=='MANAGEMENT' or 	$row->login_status=='SUPER_STOCKIST' or $row->login_status=='ADMIN')
			// {$this->session->set_userdata('HIERARCHY_STATUS','SUPERUSER');}
			// else
			// {$this->session->set_userdata('HIERARCHY_STATUS','NORMAL_USER');}
			$records="select * from tbl_employee_mstr where userid='$username' and password='$password' and status<>'INACTIVE'";
			$records = $this->get_records_from_sql($records);
			 
			 $data = array(
				'login_userid' => $records[0]->userid,
				'login_name' => $records[0]->name,
				'login_emp_id' => $records[0]->id,
				'login_tbl_designation_id'=> $records[0]->tbl_designation_id,
				'login_status'=> $records[0]->login_status,
				'activity_status'=> $records[0]->status,
				'COMP_ID'=> $records[0]->company_id,
				'validated' => true
				);
									           
            // $data = array(
            //         'login_userid' => $row->userid,
            //         'login_name' => $row->name,
            //         'login_emp_id' => $row->id,
			// 		'login_tbl_designation_id'=> $row->tbl_designation_id,
			// 		'login_status'=> $row->login_status,
			// 		'activity_status'=> $row->status,
			// 		'COMP_ID'=> $row->company_id,
            //         'validated' => true
			// 		);
					
            $this->session->set_userdata($data);
            return true;
        }
        // If the previous process did not validate
        // then return false.
        return false;
	}
	
	//INVOICE RELATED FUNCTIONS
	public function tran_no_generate($TRAN_TYPE='',$invoice_date='')
	{
		$tran_no=array();

		$finyr=$this->general_library->get_fin_yr($invoice_date);
		$tran_no['finyr']=$finyr;	
		
		if($TRAN_TYPE=='RECEIVE' || $TRAN_TYPE=='PAYMENT')
		{

			$invsrl=1;
			$sql="select max(srl) srl  from invoice_payment_receive where  finyr='$finyr' and status='$TRAN_TYPE' ";				
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{ $invsrl=$row1->srl+1;}
			$tran_no['srl']=$invsrl;	

		}
		else
		{
			$invsrl=1;
			$sql="select max(srl) srl  from invoice_summary where  finyr='$finyr' and status='$TRAN_TYPE' ";				
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{ $invsrl=$row1->srl+1;}
			$tran_no['srl']=$invsrl;	

		}	
		

		return $tran_no;
		

	}	

	
	public function check_own_company_and_transfer($id_header=0)	
	{
			$company_id_sister_concern=1;
			$status=$this->projectmodel->GetSingleVal('status','invoice_summary',' id='.$id_header);
			$company_id=$this->projectmodel->GetSingleVal('company_id','invoice_summary',' id='.$id_header);
			$tbl_party_id=$this->projectmodel->GetSingleVal('tbl_party_id','invoice_summary',' id='.$id_header);
			$id_header_sister=0;
			$ledger_id=$ledger_id_primary=0;

			if($status=='SALE' && $company_id==1)
			{
					
							$company_id_sister_concern=$this->projectmodel->GetSingleVal('id','company_details',' ledger_id='.$tbl_party_id);
							$ledger_id_primary=$this->projectmodel->GetSingleVal('ledger_id','company_details',' id='.$company_id);

							//insert or update HEADER section
							$save_data_secondary = "select * from invoice_summary where company_id=".$company_id_sister_concern." and parent_id=".$id_header." and status='PURCHASE' " ;
							$save_data_secondary = $this->projectmodel->get_records_from_sql($save_data_secondary);
							$count=sizeof($save_data_secondary); 
							if($count==1) // EDIT
							{$id_header_sister=$save_data_secondary[0]->id;}
							else  //new 
							{
									
									$save_data_secondary =array();
									$save_data_secondary = "select * from invoice_summary where   id=".$id_header."  " ;		
									$save_data_secondary = $this->projectmodel->get_records_from_sql($save_data_secondary);
									$save_data_secondary = json_decode(json_encode($save_data_secondary), true);
									$save_data_secondary=$save_data_secondary[0];			 
									$save_data_secondary['id']='';
									$save_data_secondary['status']='PURCHASE';
									$save_data_secondary['parent_id']=$id_header;
									$save_data_secondary['company_id']=$company_id_sister_concern;					
									$save_data_secondary['tbl_party_id']=$ledger_id_primary;
									$this->projectmodel->save_records_model('','invoice_summary',$save_data_secondary);
									$id_header_sister=$this->db->insert_id();
									

									//insert or update DETAIL section
									$sql="select * from invoice_details where  invoice_summary_id=".$id_header;			
									$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
									foreach ($rowrecord as $row1)
									{

										$save_data_secondary =array();
										$save_data_secondary = "select * from invoice_details where   id=".$row1->id."  " ;		
										$save_data_secondary = $this->projectmodel->get_records_from_sql($save_data_secondary);
										$save_data_secondary = json_decode(json_encode($save_data_secondary), true);
										$save_data_secondary=$save_data_secondary[0];		
										$save_data_secondary['id']='';
										$save_data_secondary['status']='PURCHASE';
										$save_data_secondary['parent_id']=$row1->id;
										$save_data_secondary['invoice_summary_id']=$id_header_sister;		 
										$save_data_secondary['doctor_commission_percentage']=0;							
										$this->projectmodel->save_records_model('','invoice_details',$save_data_secondary);

									}

									//Tax Update
									$sql="select * from invoice_details where  invoice_summary_id=".$id_header_sister;			
									$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
									foreach ($rowrecord as $row1)
									{
											$inv_details['srate']=$rate=$row1->rate;

											if($row1->tax_ledger_id==319)
											{ $inv_details['rate']=$rate-($rate*4.76/100);}
											else	if($row1->tax_ledger_id==320)
											{ $inv_details['rate']=$rate-($rate*10.71/100);}
											else if($row1->tax_ledger_id==321)
											{ $inv_details['rate']=$rate-($rate*15.25/100);}
											else
											{$inv_details['rate']=$rate;}
					
											$this->projectmodel->save_records_model($row1->id,'invoice_details',$inv_details);
									}
								
									$this->transaction_update($id_header_sister);
									//Tax Update
							}

			}


	}
	

	public function product_update_sale($invoice_id=0)
	{

		 $records = "select b.id
		from invoice_summary a, invoice_details b 
		where a.id=b.invoice_summary_id and  a.BILL_STATUS='BILL_SAVED' and b.main_group_id=51 and  a.id=".$invoice_id ;
		$records = $this->get_records_from_sql($records);
		foreach ($records as $record)
		{
			$this->product_update($record->id,'ADD_STOCK');
		}	

		//UPDATE DOCTOR COMMISSION AND OTHERS
		$records = "select b.id
		from invoice_summary a, invoice_details b 
		where a.id=b.invoice_summary_id and  a.BILL_STATUS='BILL_SAVED'  and  a.id=".$invoice_id ;
		$records = $this->get_records_from_sql($records);
		foreach ($records as $record)
		{
			$this->transaction_update($record->id);
		}	
	
	}

	public function transaction_update($id_detail=0)	
	{

		//$company_id=$this->session->userdata('COMP_ID');
		//$company_gst_no=$this->projectmodel->GetSingleVal('GSTNo','company_details',' id='.$company_id);
		
		$id_header=$this->projectmodel->GetSingleVal('invoice_summary_id','invoice_details',' id='.$id_detail);

		if($id_header>0)
		{
					$bank_charge_fine=0;
					$freight_charge=0;
					$interest_charge=0;
					$gsttype='sgst_cgst';
					$gst2nos='';
					$tax_per=0;
				
					$sql="select * from invoice_summary where id=".$id_header;			
					$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
					foreach ($rowrecord as $row_hdr)
					{
						$BILL_TYPE=$row_hdr->BILL_TYPE;
						$status=$row_hdr->status;
						$tbl_party_id=$row_hdr->tbl_party_id;
						$invoice_date=$row_hdr->invoice_date;
						$company_id=$row_hdr->company_id;
						$company_gst_no=$this->projectmodel->GetSingleVal('GSTNo','company_details',' id='.$company_id);
						$doctor_ledger_id=$row_hdr->doctor_ledger_id;
						$doctor_ledger_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers','id='.$doctor_ledger_id);

						//select  id FieldID,acc_name FieldVal  from acc_group_ledgers where acc_type='LEDGER' and parent_id=312 order by   acc_name

						$BILL_FROM=$row_hdr->BILL_FROM;

						//CUSTOMER TAX RATE
						if($tbl_party_id==317)
						{$gst2nos=''; }//for west bengal
						else
						{
							$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$tbl_party_id);
							$party_records="select * from tbl_party where id=".$tbl_party_id." ";
							$party_records = $this->projectmodel->get_records_from_sql($party_records);	
							$gst2nos=substr($party_records[0]->GSTNo,0,2);
						}
						
					}
					
					$save_hdr['total_amt']=0;
					$save_hdr['tot_discount']=0;
					$save_hdr['totvatamt']=0;
					$BILL_TYPE='VATBILL';
			
			if($status=='PURCHASE' or $status=='SALE' or $status=='PURCHASE_RTN' or $status=='SALE_RTN')
			{
					$sql="select * from invoice_details where  id=".$id_detail;			
					$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
					foreach ($rowrecord as $row1)
					{
				
							$PURCHASEID=$row1->PURCHASEID;	
							$product_id=$row1->product_id;								
							$rate=$row1->rate;
							$mrp=$row1->mrp;
							

							// if($status=='PURCHASE')
							// {$inv_details['srate']=$row1->mrp;}	

							// if($status=='SALE')
							// {
							// 	if($row1->tax_ledger_id==319)
							// 	{ $inv_details['srate']=$rate-($rate*4.76/100);}
							// 	else	if($row1->tax_ledger_id==320)
							// 	{ $inv_details['srate']=$rate-($rate*10.71/100);}
							//     else if($row1->tax_ledger_id==321)
							// 	{ $inv_details['srate']=$rate-($rate*15.25/100);}
							// 	else
							// 	{$inv_details['srate']=$rate;}								
							// }
						
							//EXPIRY CALCULATE
							if($status=='PURCHASE')
							{
								$inv_details['EXPIRY_DATE']= $this->general_library->get_date($invoice_date,0,0,1);
								$inv_details['exp_monyr']=substr($inv_details['EXPIRY_DATE'],5,2).'/'.substr($inv_details['EXPIRY_DATE'],2,2);
								
							}



							$inv_details['subtotal']=$rate*$row1->qnty;		

							//DISCOUNT PORTION
							$disc_amt1=$inv_details['disc_amt']=$mrp*$row1->qnty*$row1->disc_per/100;
							$after_first_disc_amount=$inv_details['subtotal']-$disc_amt1;
							$disc_amt2=$after_first_disc_amount*$row1->disc_per2/100;
							//$disc_amt2=0;
							//$inv_details['disc_amt']=$disc_amt1+$disc_amt2;	
							
							//DOCTOR COMMISSION CALCULATION
							 $doctor_commission_percentage=$inv_details['doctor_commission_percentage']=0;
							if($status=='SALE' && $BILL_FROM=='RETAIL')
							{

								$whr="product_group_id=".$row1->main_group_id." and doctor_mstr_id=".$doctor_ledger_id;
								$doctor_commission_percentage=floatval($this->projectmodel->GetSingleVal('	commission_percentage','doctor_commission_set',$whr));
								$inv_details['doctor_commission_percentage']=$doctor_commission_percentage;
							}
							
							//TAXABLE AMOUNT PORTION
							$inv_details['taxable_amt']=$inv_details['subtotal'];	
							//$inv_details['taxable_amt']=$inv_details['taxable_amt']-$inv_details['disc_amt'];
							

							//GST PORTION
							$inv_details['cgst_rate']=0;
							$inv_details['sgst_rate']=0;
							$inv_details['igst_rate']=0;

							$tax_per=floatval($this->projectmodel->GetSingleVal('default_value','acc_group_ledgers',' id='.$row1->tax_ledger_id));
							if($gst2nos=='' || $gst2nos==19)
							{
								$inv_details['cgst_rate']=$tax_per/2;
								$inv_details['sgst_rate']=$tax_per/2;
								$inv_details['igst_rate']=0;
							}
							else
							{
								$inv_details['cgst_rate']=0;
								$inv_details['sgst_rate']=0;
								$inv_details['igst_rate']=$tax_per;
							}

							$inv_details['cgst_amt']=$inv_details['taxable_amt']*$inv_details['cgst_rate']/100;
							$inv_details['sgst_amt']=$inv_details['taxable_amt']*$inv_details['sgst_rate']/100;
							$inv_details['igst_amt']=$inv_details['taxable_amt']*$inv_details['igst_rate']/100;
									
						
							if($company_gst_no=='' && $status=='SALE')
							{
								$inv_details['cgst_rate']=$inv_details['sgst_rate']=$inv_details['igst_rate']=0;
								$inv_details['cgst_amt']=$inv_details['sgst_amt']=$inv_details['igst_amt']=0;
								$inv_details['tax_ledger_id']=0;
							}
							//GST PORTION


							//TOTAL TAX AMOUNT
							$inv_details['taxamt']=$inv_details['cgst_amt']+$inv_details['sgst_amt']+$inv_details['igst_amt'];
							$inv_details['net_amt']=$inv_details['taxable_amt']+$inv_details['taxamt'];
											
						
							//PRODUCT QNTY ADJUSTMENT		
							// if( $status=='PURCHASE')			
							// {$this->projectmodel->product_update($row1->id,'invoice_details');}
							// else
							// {$this->projectmodel->product_update($PURCHASEID,'invoice_details');}		
							
							// $save_details['PREVIOUS_PURCHASEID']	=$row1->PURCHASEID;

							 $this->projectmodel->save_records_model($row1->id,'invoice_details',$inv_details);

							//$this->projectmodel->product_update($product_id,'productmstr',$company_id);

							//UPDATE SUMMARY TABLE			
							
							$records="select sum(subtotal) subtotal,sum(disc_amt) disc_amt,sum(taxamt) taxamt
							from  invoice_details where invoice_summary_id=".$id_header;
							$records = $this->projectmodel->get_records_from_sql($records);								
							foreach ($records as $record)
							{

								$save_hdr['tot_cash_discount']=0;
								$save_hdr['total_amt']=$record->subtotal;   
								$save_hdr['tot_discount']=$record->disc_amt; 
								$save_hdr['totvatamt']=$record->taxamt;  
								$grandtot=$save_hdr['total_amt']+$save_hdr['totvatamt'];
								
								$grandtot=sprintf("%01.2f",$grandtot);
								$grandtot1=sprintf("%01.0f",$grandtot);
								$rndoff=$grandtot-$grandtot1;
								$save_hdr['rndoff']=sprintf("%01.2f",$rndoff);	
								$save_hdr['grandtot']=$grandtot-$rndoff;

								$save_hdr['grandtot']=round($grandtot);
								
								$this->projectmodel->save_records_model($id_header,'invoice_summary',$save_hdr);
							
							}

							// $save_hdr['total_amt']=$save_hdr['total_amt']+$inv_details['subtotal'];
							// $save_hdr['tot_discount']=$save_hdr['tot_discount']+$inv_details['disc_amt'];
							// $save_hdr['tot_cash_discount']= $tot_cash_discount;

							// $save_hdr['totvatamt']=$save_hdr['totvatamt']+$inv_details['taxamt'];	
							// $grandtot=$save_hdr['total_amt']-$save_hdr['tot_discount']-$save_hdr['tot_cash_discount']+$save_hdr['totvatamt'];
							
							// $grandtot=sprintf("%01.2f",$grandtot);
							// $grandtot1=sprintf("%01.0f",$grandtot);
							// $rndoff=$grandtot-$grandtot1;
							// $save_hdr['rndoff']=sprintf("%01.2f",$rndoff);	
							// $save_hdr['grandtot']=$grandtot-$rndoff;							
							// $this->projectmodel->save_records_model($id_header,'invoice_summary',$save_hdr);	
					}

					//$this->accounts_model->ledger_transactions($id_header,$status);
			
				
			
				//if no product available delete...
				// $cnt=0;
				// $sql="select count(*) cnt from invoice_details where PRODUCT_TYPE<>'RAW' AND invoice_summary_id=".$id_header;			
				// $rowrecord = $this->projectmodel->get_records_from_sql($sql);	
				// foreach ($rowrecord as $row1)
				// {$cnt=$row1->cnt; }			
				// if($cnt==0)
				// {$this->db->query("delete from invoice_summary where id=".$id_header);}


			}
			


		}

	}

	
public function transaction_checking($param_array)
{

	

	if($param_array['checking_type']=='BATCH')
	{
		
		$whr="product_id=".$param_array['product_id']." and batchno='".$param_array['batchno']."'  and status='PURCHASE'";
		$return_data['tbl_party_id_name']=$this->projectmodel->GetSingleVal('acc_name','invoice_details',$whr);

		$batch_cnt=0;
		$products = "select count(*) cnt from invoice_details where 
		product_id=".$param_array['product_id']." and 	batchno='".$param_array['batchno']."'  and status='PURCHASE' " ;				
		$products = $this->projectmodel->get_records_from_sql($products);
		if(count($products)>0){foreach ($products as $product){$batch_cnt=$product->cnt;}}

		$batch_price_cnt=0;
		$products = "select count(*) cnt from invoice_details where 
		product_id=".$param_array['product_id']." and 	batchno='".$param_array['batchno']."' and rate=".$param_array['rate']."  and status='PURCHASE' " ;				
		$products = $this->projectmodel->get_records_from_sql($products);
		if(count($products)>0){foreach ($products as $product){$batch_price_cnt=$product->cnt;}}

		if($param_array['batchno']=='')
		{$param_array['TRANSACTION']='NO_ADD_EDIT';$param_array['TRANSACTION_MSG']='Please Enter Batch No';}
		if($batch_cnt==0)
		{$param_array['TRANSACTION']='ADD';}
		if($batch_cnt>0 && $batch_price_cnt==0)
		{$param_array['TRANSACTION']='NO_ADD_EDIT';$param_array['TRANSACTION_MSG']='This batch No with rate already entered.';}
		if($batch_cnt>0 && $batch_price_cnt>0)
		{$param_array['TRANSACTION']='ADD_EDIT';}
	}

	return $param_array;

}

public function delete_invoice($id=0)
{
	$tran_array=$return_data=array();

	$status=$this->projectmodel->GetSingleVal('status','invoice_summary','id='.$id);
	
	//SALE 	
	if($status=='SALE')
	{
		$records = $this->projectmodel->get_records_from_sql("select * from invoice_details where  invoice_summary_id=".$id);	
		$count=sizeof($records); 
		if($count>0){  
		for($cnt=0;$cnt<$count;$cnt++)
		{		
				$sql = "update  invoice_details set qty_available=qty_available+".$records[$cnt]->qnty." 
				where  id=".$records[$cnt]->PURCHASEID ;
				$this->db->query($sql);

				$tran_array[$cnt]['PURCHASEID']=$records[$cnt]->PURCHASEID;
				$tran_array[$cnt]['product_id']=$records[$cnt]->product_id;

		}}

	}	
	else if($status=='SALE_RTN')
	{
		//product adjustment			
		$records = $this->projectmodel->get_records_from_sql("select * from invoice_details where  invoice_summary_id=".$id);	
		$count=sizeof($records); 
		if($count>0){  
		for($cnt=0;$cnt<$count;$cnt++)
		{		
				$sql = "update  invoice_details set qty_available=qty_available-".$records[$cnt]->qnty." 
				where  id=".$records[$cnt]->PURCHASEID ;
				$this->db->query($sql);
				$tran_array[$cnt]['PURCHASEID']=$records[$cnt]->PURCHASEID;
				$tran_array[$cnt]['product_id']=$records[$cnt]->product_id;
		}}
	}
	else if($status=='PURCHASE_RTN')
	{

		$records = $this->projectmodel->get_records_from_sql("select * from invoice_details where  invoice_summary_id=".$id);	
		$count=sizeof($records); 
		if($count>0){  
		for($cnt=0;$cnt<$count;$cnt++)
		{		
				$sql = "update  invoice_details set qty_available=qty_available+".$records[$cnt]->qnty." 
				where  id=".$records[$cnt]->PURCHASEID ;
				$this->db->query($sql);
				$tran_array[$cnt]['PURCHASEID']=$records[$cnt]->PURCHASEID;
				$tran_array[$cnt]['product_id']=$records[$cnt]->product_id;
		}}


	}
	else //purchase
	{


	}

	$this->db->query("delete from invoice_summary where id=".$id);
	$this->db->query("delete from invoice_details where invoice_summary_id=".$id);	
	$this->accounts_model->ledger_transactions_delete($id,$status);

	$count=sizeof($tran_array); 
	if($count>0)
	{  
		for($cnt=0;$cnt<$count;$cnt++)
		{	
			$this->product_update($tran_array[$cnt]['PURCHASEID'],'invoice_details');
			$this->product_update($tran_array[$cnt]['product_id'],'productmstr');
		}
	}



	$return_data['id_header']=$id;				
	header('Access-Control-Allow-Origin: *');
	header("Content-Type: application/json");
	echo json_encode($return_data);		

}	




	//END OF INVOICE RELATED FUNCTIONS


	public function set_table_default_values($table_name='invoice_summary')
	{

		$return_arr=array();
		$sqlfields="DESCRIBE  ".$table_name;
		$fields = $this->projectmodel->get_records_from_sql($sqlfields);
		foreach ($fields as $field)
		{
			
			$arr = explode("(", $field->Type, 2);
			$first = $arr[0];
			
			//	echo $field->Field.' '.$first.'<br>' ;}
			if($field->Field<>'id')
			{

				if($first=='int' || $first=='double')
				{$return_arr[$field->Field]=0;}	

				if($first=='date')
				{$return_arr[$field->Field]=date('Y-m-d');}	
				if($first=='date')
				{$return_arr[$field->Field]=date('Y-m-d');}	
				if($first=='varchar' || $first=='text')
				{$return_arr[$field->Field]='-';}	


			}
			
			

		// $res = mysql_query('DESCRIBE products');
		// while($row = mysql_fetch_array($res)) {
		// 	echo "{$row['Field']} - {$row['Type']}n";
		// }

		}

		return $return_arr;

	}	





	function logout(){
		
		$this->session->unset_userdata('login_userid');
		$this->session->unset_userdata('login_name');
		$this->session->unset_userdata('login_emp_id');
		$this->session->unset_userdata('login_tbl_designation_id');
		$this->session->unset_userdata('login_status');
		$this->session->unset_userdata('validated');
		
		//redirect('project_controller/', 'refresh');
		redirect('/', 'refresh');
		exit();
	}
	public function employee_insert($data)
	{
	  $this->db->insert('employees', $data);
	}


	public function create_product_barcode($product_id=0)
	{

		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$cnt=1;
		
		$barcode=$product_id.'|';
		$image=$product_id.'.png';					
		//DOCUMENT
		//https://docs.zendframework.com/zend-barcode/objects/								
		$barcodeOptions = array('text' => $barcode, 'barHeight'=> 16,'barThickWidth'=>6,'barThinWidth'=>2,'drawText' => false);				
		// No required options
		$rendererOptions = array();				
		// Draw the barcode in a new image,
		$imageResource = Zend_Barcode::factory('Code128', 'image', $barcodeOptions, $rendererOptions)->draw();				 				 					 
		/* $imageResource = Zend_Barcode::factory('code128','image', array('text'=>$barcode,'barHeight' => 30,'drawText' => false), array())->draw();*/				 
			imagepng($imageResource, 'uploads/'.$image);	
		
	}



	
	public function priviledge_value($menu_details_id='')
	{
		$tbl_employee_mstr_id=$this->session->userdata('login_emp_id');
		$OPERATION='';
		$whr=" tbl_employee_mstr_id=".$tbl_employee_mstr_id." and menu_details_id=".$menu_details_id;
		$OPERATION=$this->GetSingleVal('OPERATION','menu_user_priviledge',$whr);
		return $OPERATION;
	}
	
//invoice wise payment due
function HQ_List_Current_User()
{
		$hqlist='';
		if($this->session->userdata('HIERARCHY_STATUS')=='NORMAL_USER')
		{$empid=$this->session->userdata('login_emp_id');}
		
		if($this->session->userdata('HIERARCHY_STATUS')=='SUPERUSER')
		{$empid=$this->session->userdata('billing_emp_id');}
		
		$Whr=' employee_id='.$empid;
		$parentid=$this->GetSingleVal('id','tbl_hierarchy_org',$Whr);
		
		if($this->session->userdata('login_tbl_designation_id')<>5)
		{$hqlist=$this->gethierarchy_list($parentid,'HQ');}
		
		if($this->session->userdata('login_tbl_designation_id')==5)
		{$hqlist=$parentid;}
		
		return $hqlist;
}	

function invoice_wise_due($invoiceid=0)
{
	$sql="select * from invoice_summary where id=".$invoiceid;
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{ 	
		$grandtot=$row1->grandtot;	
		$total_adjust_amt=$row1->total_adjust_amt;
		$grandtot=$grandtot-$total_adjust_amt;
	}
	//total paid amt 
	
	$sql="select sum(amt) totpaid
	 from trn_rcv_expense_details where invoice_summary_id=".$invoiceid;
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$totpaid=$row1->totpaid;}
	
	$totdue=$grandtot-$totpaid;
	return $totdue;
}	

function invoice_tot_collection($invoiceid=0)
{
	$totpaid=0;	
	$sql="select sum(amt) totpaid
	 from trn_rcv_expense_details where invoice_summary_id=".$invoiceid;
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$totpaid=$row1->totpaid;}
	
	return $totpaid;
}	
	
	function update_password($data){
		//$pass = $this->encoded($data['pass1']);
		$pass = $data['pass1'];
		$a_id = $this->session->userdata('login_emp_id');
		$query = $this->db->query("SELECT count(*) FROM tbl_employee_mstr WHERE id = '$a_id' AND password = '$pass' AND   login_status = 'USER'");
		if ($query->num_rows() > 0){
			//$pass = $this->encoded($data['pass2']);
			$pass =$data['pass2'];
			$query = $this->db->query("UPDATE 
			`tbl_employee_mstr` SET password = '$pass' WHERE `id` = '$a_id' ");
			$this->logout();
		}
	}
	/*LOGIN LOGOUT END*/
//INVOICE SUMMARY UPDATE
function invoice_summary_update()
{
		$sql="select * from invoice_summary where status='SELL'";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{ 
			$invoiceid=$row1->id;			
			$save_data['disc_per']=$row1->disc_per;	
			$save_data['cash_disc']=$row1->cash_disc;
			$save_data['bank_charge_fine']=$row1->bank_charge_fine;
					
			$subtotal=0;
			$totvatamt=0;
			$grandtot=0;
			$tot_discount=0;
			$tot_cash_discount=0;
			$sqlqty="select SUM(subtotal) subtotal,SUM(vatamt) vatamt
			,SUM(totqnty * ed) toted 
			from invoice_details 
			where invoice_summary_id=".$invoiceid;
			
			$avlqty = 
			$this->projectmodel->get_records_from_sql($sqlqty);
			
			foreach ($avlqty as $rowq){	
			$subtotal=$rowq->subtotal;
			$tot_discount=$subtotal*$save_data['disc_per']/100;
			$tot_after_discount=$subtotal-$tot_discount;
			$tot_cash_discount=$tot_after_discount*$save_data['cash_disc']/100;
			$totvatamt=$rowq->vatamt;
			$toted=$rowq->toted;
			$grandtot=$subtotal-$tot_discount-$tot_cash_discount+$totvatamt+
			$save_data['bank_charge_fine'];
			}
			
			$grandtot=sprintf("%01.2f",$grandtot);
			$grandtot1=sprintf("%01.0f",$grandtot);
			$rnd=$grandtot-$grandtot1;
			$rnd=sprintf("%01.2f",$rnd);
		
			if($rnd>=.5)
			{ $grandtot=$grandtot+$rnd;	}
			else
			{ $grandtot=$grandtot-$rnd;	}
				
							
			 $sql="update invoice_summary set 
			 total_amt='$subtotal',tot_discount='$tot_discount',
			 totvatamt='$totvatamt',grandtot='$grandtot',rndoff='$rnd' 
			 where id=".$invoiceid;
			 $this->db->query($sql);
			
		}
}
	
	//HIERARCHY LIST 
function gethierarchy_list($parentuid='',$returntype='HQ')
{
	 $returnvalue=0;
	 	 
	 //for hq list
	  if($returntype=='HQ')
		 {
			
			 $sqlemp="select childuid from tbl_organisation_chain where
			 child_desig_srl=5 and parentuid=".$parentuid." ";
			 $rowrecordemp = $this->projectmodel->get_records_from_sql($sqlemp);
			 foreach ($rowrecordemp as $rowemp)
			 { $returnvalue=$returnvalue.','.$rowemp->childuid;}		
			return $returnvalue;
		}	 
	
	 //for field list			
	 if($returntype=='FIELD')
	 {
		
		 $sqlemp="select childuid from tbl_organisation_chain where
		 child_desig_srl=6 and parentuid=".$parentuid." ";
		 $rowrecordemp = $this->projectmodel->get_records_from_sql($sqlemp);
		 foreach ($rowrecordemp as $rowemp)
		 { $returnvalue=$returnvalue.','.$rowemp->childuid;}		
		return $returnvalue;
	}
	
	if($returntype=='STOCKIST')
	 {
		$returnvalue=0;
		$sqlfields="select a.stockist_id from stockist_hq_map a,
		tbl_organisation_chain b 
		where a.tbl_hierarchy_org_id=b.childuid and
		b.child_desig_srl=6 and b.parentuid='$parentuid' ";
		$fields = $this->projectmodel->get_records_from_sql($sqlfields);
		foreach ($fields as $field)
		{$returnvalue=$returnvalue.','.$field->stockist_id;}
		return $returnvalue;
	}
		
	
	
}

	



	public function product_update($invoice_details_id=0,$operation_type='ADD_STOCK')
	{
				
		//$company_id=$this->session->userdata('COMP_ID');
		
		$tot_purchase=$tot_sale=$tot_issue=0;

		//PRODUCT UPDATE
		$products = "select b.product_id,b.rackno,b.id,a.company_id,a.status,b.qnty
		from invoice_summary a, invoice_details b 
		where a.id=b.invoice_summary_id and  a.BILL_STATUS='BILL_SAVED' and b.main_group_id=51 and  b.id=".$invoice_details_id ;
		$products = $this->get_records_from_sql($products);
		foreach ($products as $product)
		{
			
			$rack_save['total_available_qnty']=$product_save['available_qnty']=0;

			$records = "select sum(b.qnty) totqnty from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and a.status='PURCHASE'  and b.product_id=".$product->product_id." and  a.company_id=".$product->company_id ;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_purchase=$record->totqnty;}}
		

			$records = "select sum(b.qnty) totqnty from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and a.status='SALE'  and b.product_id=".$product->product_id." and   a.company_id=".$product->company_id ;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_sale=$record->totqnty;}}
		

			$records = "select sum(b.qnty) totqnty from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and a.status='ISSUE'  and b.product_id=".$product->product_id." and  a.company_id=".$product->company_id ;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_issue=$record->totqnty;}}

			$product_save['available_qnty']=$tot_purchase-$tot_sale-$tot_issue;
			
			$product_balance_companywise_id='';			
			$records = "select * from product_balance_companywise where company_id=".$product->company_id." and product_id=".$product->product_id ;
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{ $product_balance_companywise_id=$record->id;  }
			
			if($operation_type=='MINUS_STOCK')
			{
				if($product->status=='PURCHASE')
				{$product_save['available_qnty']=$product_save['available_qnty']-intval($product->qnty); }
				if($product->status=='SALE' || $product->status=='ISSUE')
				{$product_save['available_qnty']=$product_save['available_qnty']+intval($product->qnty); }
			}

		
			if($product->product_id>0 && $product_save['available_qnty']>-1)
			{
				//$this->save_records_model($product->product_id,'product_balance_companywise',$product_save);

				$product_save['product_id']=$product->product_id;
				$product_save['company_id']=$product->company_id;	
				$this->projectmodel->save_records_model($product_balance_companywise_id,'product_balance_companywise',$product_save);
			
			}
			//CLEARSTONE - 30ML			
			
			$tot_purchase=$tot_sale=$tot_issue=0;	
			//RACK WISE UPDATE

			$records = "select sum(qnty) totqnty from invoice_summary a, invoice_details b where  
			a.id=b.invoice_summary_id and     a.status='PURCHASE' and b.rackno=".$product->rackno." 
			and b.product_id=".$product->product_id." and a.company_id=".$product->company_id;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_purchase=$record->totqnty;}}

			$records = "select sum(qnty) totqnty from invoice_summary a, invoice_details b where  
			a.id=b.invoice_summary_id and a.status='SALE' and b.rackno=".$product->rackno." 
			and b.product_id=".$product->product_id." and a.company_id=".$product->company_id;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_sale=$record->totqnty;}}

			$records = "select sum(qnty) totqnty from invoice_summary a, invoice_details b where  
			a.id=b.invoice_summary_id and a.status='ISSUE' and b.rackno=".$product->rackno." 
			and b.product_id=".$product->product_id." and a.company_id=".$product->company_id;
			$records = $this->get_records_from_sql($records);
			if(count($records)>0){foreach ($records as $record){$tot_issue=$record->totqnty;}}

			$rack_save['total_available_qnty']=$tot_purchase-$tot_sale-$tot_issue;
			
			if($operation_type=='MINUS_STOCK')
			{
				if($product->status=='PURCHASE')
				{$rack_save['total_available_qnty']=$rack_save['total_available_qnty']-intval($product->qnty); }
				if($product->status=='SALE' || $product->status=='ISSUE')
				{$rack_save['total_available_qnty']=$rack_save['total_available_qnty']+intval($product->qnty); }
			}

			if($product->rackno>0 )
			{$this->save_records_model($product->rackno,'rack_master',$rack_save);}
			$tot_purchase=$tot_sale=$tot_issue=0;	

		}

		
			
	}
			
	function batch_wise_available_stock($product_id=0,$MRP='',$exp_monyr='',$company_id=1)
	{
			$totqnty=0;
			$purchase_qnty=0;
			$records="select SUM(b.qnty) qnty	from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and  b.product_id=".$product_id." 
			and b.mrp='".$MRP."' and b.exp_monyr='".$exp_monyr."' and a.status='PURCHASE' and a.company_id=".$company_id;
			$records = $this->projectmodel->get_records_from_sql($records);
			$purchase_qnty=$records[0]->qnty;


			$sale_qnty=0;
			$records="select SUM(b.qnty) qnty	from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and  b.product_id=".$product_id." 
			and b.mrp='".$MRP."' and b.exp_monyr='".$exp_monyr."'  and a.status='SALE' and a.company_id=".$company_id;
			$records = $this->projectmodel->get_records_from_sql($records);
			$sale_qnty=$records[0]->qnty;

			$issue_qnty=0;
			$records="select SUM(b.qnty) qnty	from invoice_summary a,invoice_details b 
			where a.id=b.invoice_summary_id and  b.product_id=".$product_id." 
			and b.mrp='".$MRP."' and b.exp_monyr='".$exp_monyr."'  and a.status='ISSUE' and a.company_id=".$company_id;
			$records = $this->projectmodel->get_records_from_sql($records);
			$issue_qnty=$records[0]->qnty;

			$totqnty=$purchase_qnty-$sale_qnty-$issue_qnty;
			return $totqnty;
	}	

	function batch_list($product_id=0,$company_id=1)
	{
		$data=array();
		$key=0;
		$records = "select b.batchno FieldID,b.batchno FieldVal,b.rackno ,b.mrp MRP,b.exp_monyr ,b.product_id ,b.id, c.productname 
		from invoice_summary a ,invoice_details b ,productmstr c
		where a.id=b.invoice_summary_id and c.id=b.product_id and 
		(a.status='OPEN_BALANCE' or a.status='PURCHASE' or a.status='SALE_RTN') and b.product_id=".$product_id." 
		and a.company_id=".$company_id." group by b.exp_monyr,b.mrp" ;

		$records = $this->get_records_from_sql($records);	
		foreach ($records as $record)
		{
			$available_qnty=$this->batch_wise_available_stock($product_id,$record->MRP,$record->exp_monyr,$company_id);
			if($available_qnty>0)
			{
				$data[$key]['FieldID']=$record->FieldID;
				$data[$key]['FieldVal']=$record->FieldVal;
				$data[$key]['Product_name']=$record->productname;
				$data[$key]['MRP']=$record->MRP;
				$discount_per=floatval($this->projectmodel->GetSingleVal('sell_discount','productmstr','id='.$record->product_id));
				$data[$key]['rate']=$data[$key]['MRP']-($data[$key]['MRP']*$discount_per/100);
				$data[$key]['exp_monyr']=$record->exp_monyr;
				$data[$key]['available_qnty']=$available_qnty;

				$data[$key]['Rack_No']=$this->projectmodel->GetSingleVal('rack_name','rack_master','id='.$record->rackno);
				$data[$key]['Avilable_qnty']=intval($this->projectmodel->GetSingleVal('total_available_qnty','rack_master','id='.$record->rackno));
				$data[$key]['Max_qnty']=intval($this->projectmodel->GetSingleVal('max_qnty','rack_master','id='.$record->rackno));
				$data[$key]['Avilable_qnty']=$data[$key]['Max_qnty']-$data[$key]['Avilable_qnty'];
							
			
				//calculate available qnty
				
				$data[$key]['Rkid']=$record->rackno;
				$data[$key]['pid']=$record->id;
				$key=$key+1;
			}

		}	
		
		return $data;


	}

	function doctor_wise_calculation($party_id=0,$doctor_mstr_id=0,$prescription_date='')
	{
		$data=array();

		$whr="id=".$doctor_mstr_id;
		$doc_id=$this->GetSingleVal('ref_table_id','acc_group_ledgers',$whr);

		$whr="id=".$doc_id."";
		$VISIT_1=$this->GetSingleVal('VISIT_1','doctor_mstr',$whr);
		$NEXT_VISIT=$this->GetSingleVal('NEXT_VISIT','doctor_mstr',$whr);
		$GAP_DAYS=$this->GetSingleVal('GAP_DAYS','doctor_mstr',$whr);

		$data['VISIT_1']=$VISIT_1;
		$data['NEXT_VISIT']=$NEXT_VISIT;
		$data['GAP_DAYS']=$GAP_DAYS;

	
		$sqlfields="select count(*) cnt from patient_prescription
		where patient_registration_id=".$party_id." and doctor_mstr_id=".$doctor_mstr_id ;
		$fields = $this->get_records_from_sql($sqlfields);
		foreach ($fields as $field)
		{$cnt=$field->cnt;}
		

		//AAISHI DAS
		// $CURRENT_DATE
		$MX_PRES_DATE=$prescription_date;
		$NEXT_VISIT_DATE=date('Y-m-d');
		if($cnt>0)
		{

			$sqlfields="select MAX(prescription_date) MX_PRES_DATE  from patient_prescription
			where patient_registration_id=".$party_id." and doctor_mstr_id=".$doctor_mstr_id ;
			$fields = $this->get_records_from_sql($sqlfields);
			foreach ($fields as $field)
			{
				$MX_PRES_DATE=$field->MX_PRES_DATE;
				$NEXT_VISIT_DATE=$this->general_library->get_date($MX_PRES_DATE,$GAP_DAYS,0,0);
			}
		}
		

		if($cnt==0)
		{
			$data['ACTUAL_VISIT_AMT']=$VISIT_1;
		}
		else
		{
			
			if($NEXT_VISIT_DATE<=$prescription_date) //NEXT VISIT
			{
				$data['ACTUAL_VISIT_AMT']=$NEXT_VISIT;
			}
			else  //REPORT VISIT
			{
				$data['ACTUAL_VISIT_AMT']=0;
			}

		}
		
		return $data;


	}	

	

	function rack_wise_available_stock()
	{
		$company_id=$this->session->userdata('COMP_ID');
		
		$data=array();
		$key=0;
		$records = "select * FROM rack_master WHERE company_id=".$company_id." ORDER BY rack_name" ;
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{			
			$available_qnty=intval($this->GetSingleVal('total_available_qnty','rack_master','id='.$record->id));	
			$max_qnty=intval($this->GetSingleVal('max_qnty','rack_master','id='.$record->id));						
			if($available_qnty<$max_qnty)
			{
				$data[$key]['FieldID']=$record->id;
				$data[$key]['FieldVal']=$record->rack_name;
				$data[$key]['max_qnty']=$record->max_qnty;
				$data[$key]['total_available_qnty']=$available_qnty;
				$key=$key+1;
			}	
		}	
		return $data;	
	}	



/*UPDATE TAX IN BILL*/
	function update_tax_bill()
	{
		$sql="select * from invoice_summary where status='SELL' ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{
						$invoiceid=$row1->id;
						$stockistid=$row1->tbl_party_id;
						$disc_per=$row1->disc_per;	
														
						
						$BILL_TYPE='VATBILL';
						$sqlss="select * 
						from stockist where id=".$stockistid." ";
						$rowrecordss = $this->projectmodel->get_records_from_sql($sqlss);	
						foreach ($rowrecordss as $rowss)
						{$BILL_TYPE=$rowss->BILL_TYPE;}
						
						$subtotal=0;
						$totvatamt=0;
						$grandtot=0;
						$tot_discount=0;
						
						$sqlqty="select * from invoice_details where 
						invoice_summary_id=".$invoiceid;						
						$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
						foreach ($avlqty as $rowq)
						{	
													
							if($BILL_TYPE=='VATBILL')
							{					
								$vat_per=$rowq->vat_per;
								if($vat_per<=0){$vat_per=4.76;}
								$vatamt=$rowq->totqnty*$rowq->mrp*$vat_per/100;
							}
							
							if($BILL_TYPE=='CST_BILL')
							{
								$vat_per=2;
								$cstdisc=($rowq->rate*$disc_per)/100;					
								$vatamt=$rowq->qnty*($rowq->rate-$cstdisc)*2/100;			
							}	
							
							 $sqlupdate="update invoice_details set 
							 vat_per='$vat_per',vatamt='$vatamt' 
							 where id=".$rowq->id;
							 $this->db->query($sqlupdate);
												
						
						}		
						 
		}
	}
	
	
	
/*REPORTING SECTION*/

	function getAllDoctors_visit($startingdate='',$closingdate='',$hq_id='',
	$field_id='',$speciality='',$brand_id='',$doc_type='')
	{
	
			/*if($hq_id=='' && $field_id=='' && $speciality=='' && $doc_type=='' 
			&& $brand_id=='' && $startingdate=='' && $closingdate=='')
			{	
				$sql="select * from mr_manager_doctor where status='DOCTOR' and id=0";
			}
			else
			{
				$sql="select * from mr_manager_doctor where status='DOCTOR' ";
			}*/
					$sql="select * from mr_manager_doctor where status='DOCTOR' ";
	
						if($hq_id<>'')
						{
							$sql=$sql." and 
							headq in (select childuid from tbl_organisation_chain where
							child_desig_srl=6 and parentuid=".$hq_id." ) ";
						}
						if($field_id<>'')
						{
							$sql=$sql." and	headq=".$field_id;
						}
						
						if($speciality<>'')
						{
							$sql=$sql." and	speciality=".$speciality;
						}
						
						if($doc_type<>'')
						{
							$sql=$sql." and	doc_type='".$doc_type."' ";
						}
						
						if($brand_id<>'')
						{
							$sql=$sql." and	id in 
							(select doctor_id from map_all where 
							brand_id=".$brand_id."  
							and status='brand-doctor' ) ";
						}	
						
						if($startingdate<>'' and $closingdate<>'')
						{
						$sql=$sql." and	id in 
							(select retailer_doctor_id from visit_details where
							trandate between '".$startingdate."' 
						    and '".$closingdate."' and status='DOCTOR' ) ";
						}
						
																		
				 $sql=$sql." order by headq,code,name";
				 return  $this->projectmodel->get_records_from_sql($sql);
		
		
		
}
	
/*REPORTING SECTION*/
	
	
/*PRIMARY SALE SECTION*/
function stockist_list($stockist_id,$desig1,$desig2,$desig3,$desig4,$desig5)
{
				//$data['stockist']=$stockist=$data['stockist_id1']=$stockist_id;
								
				//STOCKIST PORTION
				$stockist_ids=$this->session->userdata('stockist_ids');
				$parentuid=0;
				$login_emp_id=$this->session->userdata('login_emp_id');
				$sqlemp="select * from 
				tbl_hierarchy_org where	employee_id=".$login_emp_id;
				$rowrecord = $this->projectmodel->get_records_from_sql($sqlemp);	
				foreach ($rowrecord as $row1)
				{$parentuid=$row1->id;}
				
				if($desig1<>'')	{$parentuid=$data['desig1']=$desig1;}				
				if($desig2<>'')	{$parentuid=$data['desig2']=$desig2;}				
				if($desig3<>'')	{$parentuid=$data['desig3']=$desig3;}				
				if($desig4<>'') {$parentuid=$data['desig4']=$desig4;}				
				if($desig5<>'')	{$parentuid=$data['desig5']=$desig5;}				
			 	
				$stockist_ids=0;
				
				  $sqlfield="select childuid from tbl_organisation_chain
				  where  child_desig_srl=6 and parentuid='$parentuid' ";	
				
					$fields = $this->projectmodel->get_records_from_sql($sqlfield);
					foreach ($fields as $field)
						{ 	
							$sql="select * from stockist_hq_map 
							where tbl_hierarchy_org_id=".$field->childuid;
							$rowrecord = $this->projectmodel->get_records_from_sql($sql);
							foreach ($rowrecord as $row1)
							{ 		
								$stockist_ids=$stockist_ids.','.$row1->stockist_id;
							}
						}
					$stockist_id=$stockist_ids;
										
				/*	if($stockist_id=='')
					{
						$stockist_id=$stockist_ids;
					}*/
					
			return	$stockist_id;
					
				//echo $stockist_ids;
				
				//STOCKIST PORTION END
}
	
	
function batchwise_stock($fromdate='',$todate='',$product_id='',
$batchno='',$pkg='',$status='PURCHASE')
	{
		//FROM VIEW BATCH_WISE_STOCK
		$invsummary_id=0;
		$sql_invsummary="select id from 
		invoice_summary where id>0 and status='$status' 
		and invoice_date between '".$fromdate."' and '".$todate."' ";
		$rowrecord2 = $this->get_records_from_sql($sql_invsummary);
		foreach ($rowrecord2 as $rcdval)
		{$invsummary_id=$invsummary_id.','.$rcdval->id;}
		
		
		$totqnty=0;
		$sql422 = "select sum(totqnty) totqnty from invoice_details where 
		product_id=".$product_id." and 	batchno='".$batchno."'  
		and invoice_summary_id in (".$invsummary_id.")" ;
		$rowrecord2 = $this->get_records_from_sql($sql422);
		if(count($rowrecord2)>0){
		foreach ($rowrecord2 as $rcdval)
		{$totqnty=$rcdval->totqnty;	}}
		
		return $totqnty;
	
}
/*PRIMARY SALE SECTION END*/


/*SECONDARY SALE SECTION END*/	
function find_by_id($id)
{
		//return $this->db->where->('id',$id)->limit(1)->get('p_product')->row();
		
		$sql = "SELECT * FROM p_product WHERE id=$id";
		$query = $this->db->query($sql);
		return $query->result();
		
}
public function get_all_record($table_name,$where_array)
{
	
		$res=$this->db->get_where($table_name,$where_array);
		//$res1=$res->result_array();
		return $res->result();
		//return $res1;
		
		/*$sql = "SELECT * FROM ".$table_name." WHERE id=".$id;
		$query = $this->db->query($sql);
		return $query->result();*/
		
}	
public function get_records_from_sql($sql)
{
		//$sql = "SELECT * FROM ".$table_name." WHERE id=".$id;
		$query = $this->db->query($sql);
		return $query->result();
}
	
public function get_single_record($table_name,$id)
{
		/*$res=$this->db->get_where($table_name,array('id'=>$id));
		$res1=$res->result_array();
		return $res1;*/
		
		$sql = "SELECT * FROM ".$table_name." WHERE id=".$id;
		$query = $this->db->query($sql);
		return $query->result();
		
}
	
public function save_records_model($id,$table_name,$tabale_data)
{
		if($id>0)
		{
			$this->db->update($table_name, $tabale_data, array('id' => $id));
		}
		else
		{
			$this->db->insert($table_name,$tabale_data);
		}	
}
	
public function delete_record($id=0,$table_name)
{
	//$this->db->delete('user',array('id'=>$id));
	$this->db->delete($table_name,array('id'=>$id));
}
	
public function Activequery($ActiveRecords,$QueryType='')
{
	if($QueryType=='LIST')
	{		
		if($ActiveRecords['DataFields']<>''){
		$this->db->select($ActiveRecords['DataFields']);}
		
		if($ActiveRecords['TableName']<>''){
		$this->db->from($ActiveRecords['TableName']);	}
		
		if($ActiveRecords['WhereCondition']<>''){
		$this->db->where($ActiveRecords['WhereCondition']);}	
		
		if($ActiveRecords['OrderBy']<>''){
		$this->db->order_by($ActiveRecords['OrderBy']);}
		
		$query = $this->db->get(); 
		$query =json_encode($query->result());
		$query =json_decode($query);
		
		return $query;
		
		
	}
	
	if($QueryType=='SingleVal')
	{		
		if($ActiveRecords['DataFields']<>''){
		$this->db->select($ActiveRecords['DataFields']);}
		
		if($ActiveRecords['TableName']<>''){
		$this->db->from($ActiveRecords['TableName']);	}
		
		if($ActiveRecords['WhereCondition']<>''){
		$this->db->where($ActiveRecords['WhereCondition']);}	
		
		if($ActiveRecords['OrderBy']<>''){
		$this->db->order_by($ActiveRecords['OrderBy']);}
		
		$query = $this->db->get(); 
		$query =json_encode($query->result());
		$query =json_decode($query);
		$rtnval='';
		 foreach($query as $key=>$bd){
		 foreach($bd as $key1=>$bdr){	
		 $rtnval=$bdr;
		 }}	
		 return $rtnval;
		
		
	}
	
	if($QueryType=='SUM')
	{	
	    $this->db->select_sum($ActiveRecords['DataFields']);
		$this->db->from($ActiveRecords['TableName']);	
		$this->db->where($ActiveRecords['WhereCondition']);	
		$query = $this->db->get();
		return $query->result();
			
	}
	
	
}	


public function create_field($InputType,$LogoType,$LabelName,$InputName,
$Inputvalue,$RecordSet)
{
		
	$inputval='';
		
	if($InputType=='SingleSelect') 
	{	
		$options='<option value="0">Select All</option>';
		foreach ($RecordSet as $row){
		$id=$fieldval=$slcted='';
		if($row->FieldID==$Inputvalue) 
		{$slcted='selected="selected"'; }
		$options=$options.
		'<option value="'.$row->FieldID.'"'.$slcted.' >'.$row->FieldVal.'</option>';
		}
		
		$frmcontrol='form-control select2';		
		$multiple='multiple';
		$placeholder='Select';
		$styletype='width: 100%;';
							 
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="'.$InputName.'">
                     '.$options.'</select>';
		
					 
	}
	
	if($InputType=='MultiSelect')
	{	
		$options='';
		$retail_field = explode(",",$Inputvalue);
		 
		$sql="select * from tbl_hierarchy_org where tbl_designation_id='6'
		order by  under_tbl_hierarchy_org desc,hierarchy_name ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row)
		{ 				
			$id=$fieldval=$slcted='';
			if (in_array($row->id, $retail_field)) 
			{ $slcted='selected="selected"'; }
			
			$sql_parent="select * from tbl_hierarchy_org 
			where id=".$row->under_tbl_hierarchy_org;
			$rowrecord_parents = 
			$this->projectmodel->get_records_from_sql($sql_parent);	
			foreach ($rowrecord_parents as $rowrecord_parent)
			{
			$options=$options.'<option value="'.$row->id.'"'.$slcted.' >'
			.$row->hierarchy_name.'('.$rowrecord_parent->hierarchy_name.')'.'</option>';
			}
		
		}
		
		$frmcontrol='form-control select2';
		$multiple='multiple';
		$placeholder='---Select---';
		$styletype='width: 100%;';
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="retail_field[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					style="'.$styletype.'">'.$options.'</select>';
					 
				 
	}
	
	
	if($InputType=='DEBIT_LEDGER' or $InputType=='CREDIT_LEDGER' 
	or $InputType=='DEBIT_GROUP' or $InputType=='CREDIT_GROUP') 
	{	
		
		$options='';
		$retail_field = explode(",",$Inputvalue);
		 if($InputType=='DEBIT_LEDGER' or $InputType=='CREDIT_LEDGER' )
		 {
		 $sql="select * from acc_group_ledgers where  acc_type='LEDGER'
		 order by  acc_name ";
		 }
		 if( $InputType=='DEBIT_GROUP' or $InputType=='CREDIT_GROUP' )
		 {
		 $sql="select * from acc_group_ledgers where  acc_type='GROUP'
		 order by  acc_name ";
		 }
		 
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row)
		{ 				
			$id=$fieldval=$slcted='';
			if (in_array($row->id, $retail_field)) 
			{ $slcted='selected="selected"'; }
			
			$options=$options.'<option value="'.$row->id.'"'.$slcted.' >'
			.$row->acc_name.'</option>';
		
		}
		
		$frmcontrol='form-control select2';
		$multiple='multiple';
		$placeholder='---Select---';
		$styletype='width: 100%;';
		if($InputType=='DEBIT_LEDGER')
		 {
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="DEBIT_LEDGER[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					>'.$options.'</select>';
		}
		if($InputType=='CREDIT_LEDGER' )
		 {		 
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="CREDIT_LEDGER[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					>'.$options.'</select>';
		}
		
		if($InputType=='DEBIT_GROUP')
		 {
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="DEBIT_GROUP[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					>'.$options.'</select>';
		}
		if($InputType=='CREDIT_GROUP' )
		 {		 
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="CREDIT_GROUP[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					>'.$options.'</select>';
		}
		
					 				 
	}
	
	
	if($InputType=='password')
	{				  
			$inputval='<label>'.$LabelName.'</label>
						   <input type="password" id="'.$InputName.'" class="form-control"
				  value="'.$Inputvalue.'" name="'.$InputName.'" />';	 
				  
	}			
	
	//$inputval='';
	if($InputType=='hidden')
	{
			$inputval='<input type="hidden" id="'.$InputName.'" class="form-control"
				  value="'.$Inputvalue.'" name="'.$InputName.'" />';
	}			
	
	if($InputType=='text')
	{
			$inputval='<label>'.$LabelName.'</label>
			<input type="text" id="'.$InputName.'" class="form-control"
			  value="'.$Inputvalue.'" name="'.$InputName.'" />';
	}				   
	
	if($InputType=='text_area')
	{
			$inputval='<label>'.$LabelName.'</label>
			<textarea name="'.$InputName.'"  id="'.$InputName.'" cols="50" rows="3">'.$Inputvalue.'"</textarea>';
	}		
	
	return $inputval;
	
}

public function create_field_300818($InputType,$LogoType,$LabelName,$InputName,
$Inputvalue,$RecordSet)
{
		
	$inputval='';
		
	if($InputType=='SingleSelect') 
	{	
		$options='';
		foreach ($RecordSet as $row){
		$id=$fieldval=$slcted='';
		if($row->FieldID==$Inputvalue) 
		{$slcted='selected="selected"'; }
		$options=$options.
		'<option value="'.$row->FieldID.'"'.$slcted.' >'.$row->FieldVal.'</option>';
		}
		
		$frmcontrol='form-control select2';		
		$multiple='multiple';
		$placeholder='Select';
		$styletype='width: 100%;';
							 
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="'.$InputName.'">
                     '.$options.'</select>';
		
					 
	}
	
	if($InputType=='MultiSelect')
	{	
		$options='';
		foreach ($RecordSet as $row)
		{
		$slcted='';
		if($Inputvalue==$row->id)
		{
		$slcted='selected="selected"';
		}
		
		$options=$options.'<option value="'.$row->id.'" '.$slcted.' 
		>'.$row->fieldval.'</option>';
		}
		
		$frmcontrol='form-control select2';
		$multiple='multiple';
		$placeholder='---Select---';
		$styletype='width: 100%;';
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="'.$InputName.'"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					style="'.$styletype.'">'.$options.'</select>';
				 
	}
	
	if($InputType=='FieldHQSingleSelect') 
	{	
		$options='';
		$sql="select * from tbl_hierarchy_org where tbl_designation_id='6'
		 order by  under_tbl_hierarchy_org,hierarchy_name ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row)
		{ 	
			$hq='';
			$sql_parent="select * from tbl_hierarchy_org 
			where id=".$row->under_tbl_hierarchy_org;
			$rowrecord_parents = 
			$this->projectmodel->get_records_from_sql($sql_parent);	
			foreach ($rowrecord_parents as $rowrecord_parent)
			{ $hq=$rowrecord_parent->hierarchy_name; }
		
			$id=$fieldval=$slcted='';
			if($row->id==$Inputvalue) 
			{$slcted='selected="selected"'; }
			$options=$options.'<option value="'.$row->id.'"'.$slcted.' >'
			.$row->hierarchy_name.'('.$hq.')'.'</option>';
		
		}
		
		$frmcontrol='form-control select2';
		//$frmcontrol='select2';
		$multiple='multiple';
		$placeholder='Select';
		$styletype='width: 100%;';
							 
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="'.$InputName.'">
                     '.$options.'</select>';					 
	}
	
	
	if($InputType=='FieldHQMultiSelect') 
	{	
		$options='';
		$retail_field = explode(",",$Inputvalue);
		 
		$sql="select * from tbl_hierarchy_org where tbl_designation_id='6'
		order by  under_tbl_hierarchy_org desc,hierarchy_name ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row)
		{ 				
			$id=$fieldval=$slcted='';
			if (in_array($row->id, $retail_field)) 
			{ $slcted='selected="selected"'; }
			
			$sql_parent="select * from tbl_hierarchy_org 
			where id=".$row->under_tbl_hierarchy_org;
			$rowrecord_parents = 
			$this->projectmodel->get_records_from_sql($sql_parent);	
			foreach ($rowrecord_parents as $rowrecord_parent)
			{
			$options=$options.'<option value="'.$row->id.'"'.$slcted.' >'
			.$row->hierarchy_name.'('.$rowrecord_parent->hierarchy_name.')'.'</option>';
			}
		
		}
		
		$frmcontrol='form-control select2';
		$multiple='multiple';
		$placeholder='---Select---';
		$styletype='width: 100%;';
		$inputval='<label>'.$LabelName.'</label>
                    <select class="'.$frmcontrol.'" name="retail_field[]"
					multiple="'.$multiple.'" data-placeholder="'.$placeholder.'" 
					style="'.$styletype.'">'.$options.'</select>';
					 
	}
	
	
	if($InputType=='password')
	{				  
			$inputval='<label>'.$LabelName.'</label>
						   <input type="password" id="'.$InputName.'" class="form-control"
				  value="'.$Inputvalue.'" name="'.$InputName.'" />';	 
				  
	}			
	
	//$inputval='';
	if($InputType=='hidden')
	{
			$inputval='<input type="hidden" id="'.$InputName.'" class="form-control"
				  value="'.$Inputvalue.'" name="'.$InputName.'" />';
	}			
	
	if($InputType=='text')
	{
			$inputval='<label>'.$LabelName.'</label>
			<input type="text" id="'.$InputName.'" class="form-control"
			  value="'.$Inputvalue.'" name="'.$InputName.'" />';
	}				   
		
	
	return $inputval;
	
}


public function GetSingleVal($DataFields='',$TableName='',$WhereCondition='')
	{
		$rtnval=0;
			
		if($DataFields<>'' and $TableName<>'' and $WhereCondition<>'' )
		{
			$this->db->select($DataFields);
			$this->db->from($TableName);
			$this->db->where($WhereCondition);
			
			$query = $this->db->get(); 
			$query =json_encode($query->result());
			$query =json_decode($query);
			$rtnval='';
			 foreach($query as $key=>$bd){
			 foreach($bd as $key1=>$bdr){	
			 $rtnval=$bdr;
			 }}	
		 
		}
		 return $rtnval;
	}
	
	public function GetMultipleVal($DataFields='',$TableName='',$WhereCondition='',$OrderBy='')
	{
			$this->db->select($DataFields);		
			$this->db->from($TableName);		
			$this->db->where($WhereCondition);
			if($OrderBy<>''){$this->db->order_by($OrderBy);}
			
			$query = $this->db->get(); 
			$query =json_encode($query->result());
			$query =json_decode($query, true);
			//json_decode($jsonData, true);
			return $query;
		
	}

	public function Join_tables_query($DataFields='',$TableName='',$WhereCondition='',$OrderBy='')
	{
			// $this->db->select($DataFields);		
			// $this->db->from($TableName);		
			// $this->db->where($WhereCondition);
			// if($OrderBy<>''){$this->db->order_by($OrderBy);}			
			// $query = $this->db->get(); 


			//$this->db->select('*')->from('clients')->join('users', 'users.id = clients.a_1 OR users.id=clients.a_2 OR users.id = clients.a_3');

			//$this->db->select('t1.field, t2.field2')->from('table1 AS t1, table2 AS t2')
			//->where('t1.id = t2.table1_id')->where('t1.user_id', $user_id);

			// $this->db->select('*')->from('student')
            // ->where('student.roll_no',$id)
            // ->join('student_details','student_details.roll_no = student.roll_no')
            // ->join('course_details','course_details.roll_no = student.roll_no');


			$query = $this->db->get();

			$query =json_encode($query->result());
			$query =json_decode($query, true);
			return $query;
		
	}

	public function get_product_master($DataFields='',$TableName='',
	$WhereCondition='',$OrderBy='')
	{
			$this->db->select($DataFields);		
			$this->db->from($TableName);		
			$this->db->where($WhereCondition);
			if($OrderBy<>''){$this->db->order_by($OrderBy);}
			
			$query = $this->db->get(); 
			$query =json_encode($query->result());
			return $query;
		
	}
	
	public function send_json_output($rs)
	{
			header('Access-Control-Allow-Origin: *');
			header("Content-Type: application/json");
			echo json_encode($rs);
		
	}
	
	
}
?>