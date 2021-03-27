<?php
class Accounts_model extends CI_Model {
		
		function __construct()
    {
        // Call the Model constructor
        parent::__construct();
				$this->load->database();
				$this->load->library('session');
				$this->load->library('general_library');
				$this->load->library('excel');
    }
		//Ledger Transaction

function get_ledger_id($ref_table_id='',$ref_table_name='')
 {
 		$ledger_account_header=0;	
		$sql_led="select * 	from acc_group_ledgers where
		ref_table_name='".$ref_table_name."' and  ref_table_id=".$ref_table_id;			
		$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
		foreach ($rowledgers as $rowledger)
		{ return $ledger_account_header=$rowledger->id; }
 
 }
 
function acc_tran_details($acc_tran_header_id='',$cr_ledger_account='',$dr_ledger_account='',$amount='',$matching_tran_id=1)
 {
 		    
			$save_dtl['acc_tran_header_id']=$acc_tran_header_id;
			$save_dtl['cr_ledger_account']=$cr_ledger_account;
			$save_dtl['dr_ledger_account']=$dr_ledger_account;
			$save_dtl['amount']=$amount;
			$save_dtl['matching_tran_id']=$matching_tran_id;
			
			$this->projectmodel->save_records_model('','acc_tran_details',$save_dtl);
			return $this->db->insert_id();
 
 }
 
 function ledger_transactions_delete($tran_table_id='',$TRAN_TYPE='')
 {
 		 
			if($TRAN_TYPE=='SALE' or $TRAN_TYPE=='PURCHASE' or $TRAN_TYPE=='PURCHASE_RTN' or $TRAN_TYPE=='SALE_RTN')//sell invoice section
			{
				
				   $acc_tran_details_id=$acc_tran_header_id=0;

					$sql_led="select * 	from acc_tran_header where
					tran_table_name='invoice_summary' and  
					tran_table_id=".$tran_table_id;			
					$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
					foreach ($rowledgers as $rowledger)
					{ $acc_tran_header_id=$rowledger->id; }

					$sql_led="select * 	from acc_tran_details where
					acc_tran_header_id=".$acc_tran_header_id;			
					$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
					foreach ($rowledgers as $rowledger)
					{ $acc_tran_details_id= $acc_tran_details_id.','.$rowledger->id; }
				
					$sql="delete from acc_tran_details_details  
					where acc_tran_details_id in (".$acc_tran_details_id.") ";
					$this->db->query($sql);

					$sql="delete from acc_tran_details  
					where acc_tran_header_id=".$acc_tran_header_id;
					$this->db->query($sql);
					
					$sql="delete from acc_tran_header  where id=".$acc_tran_header_id;
					$this->db->query($sql);
				
			}
			else
			{

				$acc_tran_details_id=$acc_tran_header_id=0;

				$sql_led="select * 	from acc_tran_details where			acc_tran_header_id=".$tran_table_id;			
				$rowledgers = $this->projectmodel->get_records_from_sql($sql_led);	
				foreach ($rowledgers as $rowledger)
				{ $acc_tran_details_id= $acc_tran_details_id.','.$rowledger->id; }
			
				$sql="delete from acc_tran_details_details  	where acc_tran_details_id in (".$acc_tran_details_id.") ";
				$this->db->query($sql);

				$sql="delete from acc_tran_details  where acc_tran_header_id=".$tran_table_id;
				$this->db->query($sql);
				
				$sql="delete from acc_tran_header  where id=".$tran_table_id;
				$this->db->query($sql);

			}
			
			
 }

 function acc_tran_details_details($acc_tran_details_id,$TABLE_NAME,$TABLE_ID,$BILL_INSTRUMENT_NO,$AMOUNT,$STATUS,$OPERATION_TYPE)
 {

	$save_hdr['acc_tran_details_id']=$acc_tran_details_id;
	$save_hdr['TABLE_NAME']=$TABLE_NAME;
	$save_hdr['TABLE_ID']=$TABLE_ID;
	$save_hdr['BILL_INSTRUMENT_NO']=$BILL_INSTRUMENT_NO;
	$save_hdr['AMOUNT']=$AMOUNT;
	$save_hdr['STATUS']=$STATUS;
	$save_hdr['OPERATION_TYPE']=$OPERATION_TYPE;

	$this->projectmodel->save_records_model('','acc_tran_details_details',$save_hdr);

 }

function ledger_transactions($tran_table_id='',$TRAN_TYPE='')
 {
	$acc_tran_details_id=0;
	$company_id=$this->session->userdata('COMP_ID');

	if($TRAN_TYPE=='SALE')//sell invoice section
	{	
		$this->ledger_transactions_delete($tran_table_id,$TRAN_TYPE);
		
		$save_hdr['tran_table_name']='invoice_summary';
		$save_hdr['tran_table_id']=$tran_table_id;
		
		$sqlfld="SELECT * FROM  invoice_summary where id=".$tran_table_id; 
		$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
		foreach ($fields as $field)
		{	
			$tbl_party_id=$save_hdr['ledger_account_header']=$field->tbl_party_id;
			$save_hdr['tran_date']=$field->invoice_date;
			$save_hdr['tran_code']=$field->invoice_no;
			$save_hdr['TRAN_TYPE']='SALE';
			$save_hdr['company_id']=$field->company_id;
			
			$AMOUNT=$field->grandtot;
			$BILL_TYPE=$field->BILL_TYPE; //cash or credit bill

			$this->projectmodel->save_records_model('','acc_tran_header',$save_hdr);
			$id_header=$this->db->insert_id();
			
			//DETAILS OF TRANSACTIONS
			$matching_tran_id=1;
			$amount=$field->total_amt;
			$cr_ledger_account=323; //sales a/c
			$dr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$acc_tran_details_id=$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);

			$this->acc_tran_details_details($acc_tran_details_id,$save_hdr['tran_table_name'],$save_hdr['tran_table_id'],$save_hdr['tran_code'],
			$AMOUNT,$save_hdr['TRAN_TYPE'],'PLUS');
			}
			
			$matching_tran_id=$matching_tran_id+1;			
			$amount=$field->interest_charge;
			$cr_ledger_account=95; //Interest Receive
			$dr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			
			$matching_tran_id=$matching_tran_id+1;					
			$amount=$field->freight_charge;
			$dr_ledger_account=94; //Freight Charge
			$cr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			

			$doc_commission=0;
			$records="SELECT sum(taxable_amt*doctor_commission_percentage/100) doc_commission FROM  invoice_details where  	invoice_summary_id=".$tran_table_id; 
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{$doc_commission=$record->doc_commission;}
				
			$amount=$doc_commission;
			$matching_tran_id=$matching_tran_id+1;				
			$dr_ledger_account=1814; //DOCTOR COMMISSION LEDGER
			$cr_ledger_account=$field->doctor_ledger_id; //DOCTOR LEDGER
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}

			//TAX SECTION
			$sql_vatper="select distinct(tax_ledger_id) tax_ledger_id
			from invoice_details where invoice_summary_id=".$tran_table_id."  ";
			$rowsql_vatper = $this->projectmodel->get_records_from_sql($sql_vatper);	
			foreach ($rowsql_vatper as $rows_vatper)
			{ 
				$tax_ledger_id=$rows_vatper->tax_ledger_id;	
				
				$taxamt=0;	
				$sql_vatamt="select sum(taxamt) taxamt
				from invoice_details where invoice_summary_id=".$tran_table_id." 
				and  tax_ledger_id=".$tax_ledger_id;
				$rowsql_vatamt = $this->projectmodel->get_records_from_sql($sql_vatamt);	
				foreach ($rowsql_vatamt as $rows_vatamt)
				{$taxamt=$rows_vatamt->taxamt;}
				
				$matching_tran_id=$matching_tran_id+1;		
				$amount=$taxamt;
				$dr_ledger_account=$tbl_party_id;
				$cr_ledger_account=$tax_ledger_id; 								
				if($amount>0)
				{
				$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
				}
			}

			if($BILL_TYPE=='CASH_BILL')
			{
				if($tbl_party_id<>317)
				{
					$matching_tran_id=$matching_tran_id+1;			
					$amount=$field->grandtot;
					$cr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors 
					$dr_ledger_account=317; //cash ledger
					if($amount>0)
					{
					$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
					$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
					}
				}
			}


		
		
		}
	}
	//sell invoice section end
	
	//PURCHASE SECTION	
	if($TRAN_TYPE=='PURCHASE')//sell invoice section
	{	
		$this->ledger_transactions_delete($tran_table_id,$TRAN_TYPE);
		
		$save_hdr['tran_table_name']='invoice_summary';
		$save_hdr['tran_table_id']=$tran_table_id;
		
		$sqlfld="SELECT * FROM  invoice_summary where id=".$tran_table_id; 
		$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
		foreach ($fields as $field)
		{				
			$tbl_party_id=$save_hdr['ledger_account_header']=$field->tbl_party_id;
			$save_hdr['tran_date']=$field->invoice_date;
			$save_hdr['tran_code']=$field->invoice_no;
			$save_hdr['TRAN_TYPE']='PURCHASE';
			$save_hdr['company_id']=$field->company_id;
			$AMOUNT=$field->grandtot;

			$this->projectmodel->save_records_model('','acc_tran_header',$save_hdr);
			$id_header=$this->db->insert_id();
						
			//DETAILS OF TRANSACTIONS
			$matching_tran_id=0;
			$matching_tran_id=$matching_tran_id+1;		
			$amount=$field->total_amt;
			$dr_ledger_account=322; //purchase ledger
			$cr_ledger_account=$tbl_party_id; // a/c sundry creditor
			if($amount>0)
			{
			
			$acc_tran_details_id=$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);			
			$this->acc_tran_details_details($acc_tran_details_id,$save_hdr['tran_table_name'],$save_hdr['tran_table_id'],$save_hdr['tran_code'],
			$AMOUNT,$save_hdr['TRAN_TYPE'],'PLUS');

			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			
			//TAX SECTION
			$sql_vatper="select distinct(tax_ledger_id) tax_ledger_id
			from invoice_details where invoice_summary_id=".$tran_table_id."  ";
			$rowsql_vatper = $this->projectmodel->get_records_from_sql($sql_vatper);	
			foreach ($rowsql_vatper as $rows_vatper)
			{ 
				$tax_ledger_id=$rows_vatper->tax_ledger_id;	
				
				$taxamt=0;	
				$sql_vatamt="select sum(taxamt) taxamt
				from invoice_details where invoice_summary_id=".$tran_table_id." 
				and  tax_ledger_id=".$tax_ledger_id;
				$rowsql_vatamt = $this->projectmodel->get_records_from_sql($sql_vatamt);	
				foreach ($rowsql_vatamt as $rows_vatamt)
				{$taxamt=$rows_vatamt->taxamt;}
				
				$matching_tran_id=$matching_tran_id+1;		
				$amount=$taxamt;
				$cr_ledger_account=$tbl_party_id;
				$dr_ledger_account=$tax_ledger_id; 								
				if($amount>0)
				{
				$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
				}
			}
			
		}
		
	}
	//PURCHASE SECTION END


	if($TRAN_TYPE=='SALE_RTN')//sell invoice section
	{	
		$this->ledger_transactions_delete($tran_table_id,$TRAN_TYPE);
		
		$save_hdr['tran_table_name']='invoice_summary';
		$save_hdr['tran_table_id']=$tran_table_id;
		
		$sqlfld="SELECT * FROM  invoice_summary where id=".$tran_table_id; 
		$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
		foreach ($fields as $field)
		{	
			$tbl_party_id=$save_hdr['ledger_account_header']=$field->tbl_party_id;
			$save_hdr['tran_date']=$field->invoice_date;
			$save_hdr['tran_code']=$field->invoice_no;
			$save_hdr['TRAN_TYPE']='SALE_RTN';
			$save_hdr['company_id']=$field->company_id;
			
			$AMOUNT=$field->grandtot;
			$BILL_TYPE=$field->BILL_TYPE; //cash or credit bill

			$this->projectmodel->save_records_model('','acc_tran_header',$save_hdr);
			$id_header=$this->db->insert_id();
			
			//DETAILS OF TRANSACTIONS
			$matching_tran_id=1;
			$amount=$field->total_amt;
		
			$cr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			$dr_ledger_account=323; //sales a/c 
			if($amount>0)
			{

				$acc_tran_details_id=$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				$this->acc_tran_details_details($acc_tran_details_id,$save_hdr['tran_table_name'],$save_hdr['tran_table_id'],$save_hdr['tran_code'],
				$AMOUNT,$save_hdr['TRAN_TYPE'],'MINUS');

				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);		

			}
			
			$matching_tran_id=$matching_tran_id+1;			
			$amount=$field->interest_charge;
			$dr_ledger_account=95; //Interest Receive
			$cr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			
			$matching_tran_id=$matching_tran_id+1;					
			$amount=$field->freight_charge;
			$cr_ledger_account=94; //Freight Charge
			$dr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			

			$doc_commission=0;
			$records="SELECT sum(taxable_amt*doctor_commission_percentage/100) doc_commission FROM  invoice_details where  	invoice_summary_id=".$tran_table_id; 
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{$doc_commission=$record->doc_commission;}
				
			$amount=$doc_commission;
			$matching_tran_id=$matching_tran_id+1;				
			$cr_ledger_account=1814; //DOCTOR COMMISSION LEDGER
			$dr_ledger_account=$field->doctor_ledger_id; //DOCTOR LEDGER
			if($amount>0)
			{
			$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
			$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}

			//TAX SECTION
			$sql_vatper="select distinct(tax_ledger_id) tax_ledger_id
			from invoice_details where invoice_summary_id=".$tran_table_id."  ";
			$rowsql_vatper = $this->projectmodel->get_records_from_sql($sql_vatper);	
			foreach ($rowsql_vatper as $rows_vatper)
			{ 
				$tax_ledger_id=$rows_vatper->tax_ledger_id;	
				
				$taxamt=0;	
				$sql_vatamt="select sum(taxamt) taxamt
				from invoice_details where invoice_summary_id=".$tran_table_id." 
				and  tax_ledger_id=".$tax_ledger_id;
				$rowsql_vatamt = $this->projectmodel->get_records_from_sql($sql_vatamt);	
				foreach ($rowsql_vatamt as $rows_vatamt)
				{$taxamt=$rows_vatamt->taxamt;}
				
				$matching_tran_id=$matching_tran_id+1;		
				$amount=$taxamt;
				$cr_ledger_account=$tbl_party_id;
				$dr_ledger_account=$tax_ledger_id; 								
				if($amount>0)
				{
				$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
				}
			}

			if($BILL_TYPE=='CASH_BILL')
			{
				if($tbl_party_id<>317)
				{
					$matching_tran_id=$matching_tran_id+1;			
					$amount=$field->grandtot;
					$dr_ledger_account=$tbl_party_id; //stockist a/c sundry debtors 
					$cr_ledger_account=317; //cash ledger
					if($amount>0)
					{
					$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
					$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
					}
				}
			}


		
		
		}
	}
	
	//PURCHASE SECTION	
	if($TRAN_TYPE=='PURCHASE_RTN')//sell invoice section
	{	
		$this->ledger_transactions_delete($tran_table_id,$TRAN_TYPE);
		
		$save_hdr['tran_table_name']='invoice_summary';
		$save_hdr['tran_table_id']=$tran_table_id;
		
		$sqlfld="SELECT * FROM  invoice_summary where id=".$tran_table_id; 
		$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
		foreach ($fields as $field)
		{				
			$tbl_party_id=$save_hdr['ledger_account_header']=$field->tbl_party_id;
			$save_hdr['tran_date']=$field->invoice_date;
			$save_hdr['tran_code']=$field->invoice_no;
			$save_hdr['TRAN_TYPE']='PURCHASE_RTN';
			$save_hdr['company_id']=$field->company_id;
			$AMOUNT=$field->grandtot;

			$this->projectmodel->save_records_model('','acc_tran_header',$save_hdr);
			$id_header=$this->db->insert_id();
						
			//DETAILS OF TRANSACTIONS
			$matching_tran_id=0;
			$amount=$field->total_amt-$field->tot_cash_discount-$field->tot_discount;
			$dr_ledger_account=$tbl_party_id ; //purchase ledger
			$cr_ledger_account=322; // a/c sundry creditor
			if($amount>0)
			{
				$acc_tran_details_id=$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				
				// $this->acc_tran_details_details($acc_tran_details_id,$save_hdr['tran_table_name'],$save_hdr['tran_table_id'],$save_hdr['tran_code'],
				// $AMOUNT,$save_hdr['TRAN_TYPE'],'MINUS');

				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
			}
			
			//TAX SECTION
			$sql_vatper="select distinct(tax_ledger_id) tax_ledger_id
			from invoice_details where invoice_summary_id=".$tran_table_id."  ";
			$rowsql_vatper = $this->projectmodel->get_records_from_sql($sql_vatper);	
			foreach ($rowsql_vatper as $rows_vatper)
			{ 
				$tax_ledger_id=$rows_vatper->tax_ledger_id;	
				
				$taxamt=0;	
				$sql_vatamt="select sum(taxamt) taxamt
				from invoice_details where invoice_summary_id=".$tran_table_id." 
				and  tax_ledger_id=".$tax_ledger_id;
				$rowsql_vatamt = $this->projectmodel->get_records_from_sql($sql_vatamt);	
				foreach ($rowsql_vatamt as $rows_vatamt)
				{$taxamt=$rows_vatamt->taxamt;}
				
				$amount=$taxamt;
				$cr_ledger_account=$tax_ledger_id;
				$dr_ledger_account=$tbl_party_id ; 								
				if($amount>0)
				{
				$matching_tran_id=$matching_tran_id+1;
				$this->acc_tran_details($id_header,$cr_ledger_account,0,$amount,$matching_tran_id);
				$this->acc_tran_details($id_header,0,$dr_ledger_account,$amount,$matching_tran_id);
				}
			}
			
		}
		
	}
	//PURCHASE SECTION END

	
}

function bill_wise_outstanding($TABLE_NAME='',$TABLE_ID='',$status='')
{
	
	$tot_due=$plus_amt=$minus_amt=0;

	$balancesheets="select sum(AMOUNT) plus_amt from acc_tran_details_details 
	where  TABLE_NAME='".$TABLE_NAME."' AND TABLE_ID='".$TABLE_ID."' and OPERATION_TYPE='PLUS' ";
	$balancesheets =$this->projectmodel->get_records_from_sql($balancesheets);
	foreach ($balancesheets as $balancesheet)
	{$plus_amt=$balancesheet->plus_amt;}

	$balancesheets="select sum(AMOUNT) minus_amt from acc_tran_details_details 
	where  TABLE_NAME='".$TABLE_NAME."' AND TABLE_ID='".$TABLE_ID."' and OPERATION_TYPE='MINUS' ";
	$balancesheets =$this->projectmodel->get_records_from_sql($balancesheets);
	foreach ($balancesheets as $balancesheet)
	{$minus_amt=$balancesheet->minus_amt;}

	$tot_due=$plus_amt-$minus_amt;

	if($status=='PLUS'){$tot_due=$plus_amt;}
	if($status=='MINUS'){$tot_due=$minus_amt;}

	return $tot_due;


}


function bill_wise_due($invoice_id='')
{
	$tot_payment=0;	
	$balancesheets="select sum(AMOUNT) tot_payment from acc_tran_details_details where  bill_id=".$invoice_id." ";
	$balancesheets =$this->projectmodel->get_records_from_sql($balancesheets);
	foreach ($balancesheets as $balancesheet)
	{$tot_payment=$balancesheet->tot_payment;}
	
	return $tot_payment;
}

function ledger_master_create($ref_table_name='',$ref_table_id='',$parent_id='',$TRAN_TYPE='')
{
	//TALLY LEDGER MASTER DETAILS	
	//https://teachoo.com/725/228/Tally-Ledger-Groups-List-(Ledger-under-Which-Head-or-Group-in-Accounts)/category/Ledger-Creation-and-Alteration/
	
	
			$id='';
			$sqlfld="SELECT id  FROM  acc_group_ledgers where 	ref_table_name='".$ref_table_name."' and ref_table_id=".$ref_table_id;			
			$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
			foreach ($fields as $field)
			{$id=$field->id;}
			
			
			$sqlfld="SELECT * FROM  acc_group_ledgers 		where id=".$parent_id;
			$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
			foreach ($fields as $field)
			{
			$save_ledger['VOUCHER_TYPE']=$field->VOUCHER_TYPE;
			$save_ledger['acc_nature']=$field->acc_nature;
			}
			
			 $sqlfld="SELECT * FROM  ".$ref_table_name." where id=".$ref_table_id;
			$fields = $this->projectmodel->get_records_from_sql($sqlfld);	
			foreach ($fields as $field)
			{
			
			if($ref_table_name=='tbl_party')
			{
			$save_ledger['acc_name']=$field->party_name;
			}
			
			if($ref_table_name=='doctor_mstr')
			{
				 $save_ledger['acc_name']=$field->name;
			}
			
			}
			
			$save_ledger['parent_id']=$parent_id;
			$save_ledger['acc_type']='LEDGER';
			$save_ledger['EDIT_STATUS']='NO';			
			$save_ledger['ref_table_name']=$ref_table_name;
			$save_ledger['ref_table_id']=$ref_table_id;
			
			
			$this->projectmodel->save_records_model($id,'acc_group_ledgers',$save_ledger);	
}

function batch_wise_product_available($batchno=0,$product_id=0)
{
	$AVAILABLE_QTY=$OPEN_BALANCE=$PURCHASEQNTY=$SALEQNTY=$PURCHASE_RTN=$SALE_RTN=0;

	$sqlqty="select SUM(qnty) qnty from invoice_details where product_id=".$product_id." 
	and batchno='".$batchno."' and status='OPEN_BALANCE' ";
	$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($avlqty as $rowq){	
	$OPEN_BALANCE=$rowq->qnty;
	}

	//purchase
	$sqlqty="select SUM(qnty) qnty from invoice_details where product_id=".$product_id." 
	and batchno='".$batchno."' and status='PURCHASE' ";
	$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($avlqty as $rowq){		$PURCHASEQNTY=$rowq->qnty;}

	//sale
	$sqlqty="select SUM(qnty) qnty from invoice_details where product_id=".$product_id." 
	and batchno='".$batchno."' and status='SALE' ";
	$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($avlqty as $rowq){		$SALEQNTY=$rowq->qnty;	}

	//PURCHASE RETURN
	$sqlqty="select SUM(qnty) qnty from invoice_details where product_id=".$product_id." 
	and batchno='".$batchno."' and status='PURCHASE_RTN' ";
	$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($avlqty as $rowq){		$PURCHASE_RTN=$rowq->qnty;	}

	//SALE RETURN
	$sqlqty="select SUM(qnty) qnty from invoice_details where product_id=".$product_id." 
	and batchno='".$batchno."' and status='SALE_RTN' ";
	$avlqty = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($avlqty as $rowq){		$SALE_RTN=$rowq->qnty;	}
	
	$AVAILABLE_QTY=$OPEN_BALANCE+$PURCHASEQNTY-$SALEQNTY-$PURCHASE_RTN+$SALE_RTN;
	return $AVAILABLE_QTY;
}


function all_mis_report($REPORT_NAME,$param_array)
{
	$rsval=array();
	$tranlink=ADMIN_BASE_URL.'Accounts_controller/all_mis_reports/';
	$company_id=$this->session->userdata('COMP_ID');

	//print_r($param_array);
	//echo $param_array['fromdate'].' todate::'.$param_array['todate'];

	if($REPORT_NAME=='PURCHASE_REGISTER')
	{
			
		$fromdate=$param_array['fromdate'];
		$todate=$param_array['todate'];
		$ledger_ac=$param_array['ledger_ac'];

		$indx=0;
		$form_id=$id=49;
		$whr=" id=".$id;	
		$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
		
		if($ledger_ac==0){$WhereCondition=$WhereCondition." and invoice_date between '$fromdate' and '$todate'   order by invoice_date";}
		else{$WhereCondition=$WhereCondition." and invoice_date between '$fromdate' and '$todate'  and tbl_party_id=".$ledger_ac." order by invoice_date";}
		
						
		$rs[$indx]['section_type']='GRID_ENTRY';	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
		$count = $this->projectmodel->get_records_from_sql($rs[$indx]['sql_query']);	
		if(sizeof($count)>0)
		{
			$output=$this->FrmRptModel->create_report_for_web($rs,$id);  
			return $output;	
		}
		

	}

	if($REPORT_NAME=='SALE_REGISTER')
	{
			
		$fromdate=$param_array['fromdate'];
		$todate=$param_array['todate'];
		$ledger_ac=$param_array['ledger_ac'];

		$indx=0;
		$form_id=$id=50;
		$whr=" id=".$id;	
		$DataFields=$this->projectmodel->GetSingleVal('GridHeader','frmrpttemplatehdr',$whr);									
		$TableName=$this->projectmodel->GetSingleVal('TableName','frmrpttemplatehdr',$whr);
		$section_type=$this->projectmodel->GetSingleVal('Type','frmrpttemplatehdr',$whr);	
		$WhereCondition=$this->projectmodel->GetSingleVal('WhereCondition','frmrpttemplatehdr',$whr);	
		
		if($ledger_ac==0){$WhereCondition=$WhereCondition." and invoice_date between '$fromdate' and '$todate'   order by invoice_date";}
		else{$WhereCondition=$WhereCondition." and invoice_date between '$fromdate' and '$todate'  and tbl_party_id=".$ledger_ac." order by invoice_date";}
		
						
		$rs[$indx]['section_type']='GRID_ENTRY';	
		$rs[$indx]['frmrpttemplatehdr_id']=$form_id;
		$rs[$indx]['id']=$id;	$rs[$indx]['parent_id']='';	$rs[$indx]['TableName']=$TableName;
		$rs[$indx]['fields']=$DataFields;
		$rs[$indx]['sql_query']="select ".$rs[$indx]['fields']." from ".$rs[$indx]['TableName']." where ".$WhereCondition;		
		$count = $this->projectmodel->get_records_from_sql($rs[$indx]['sql_query']);	
		if(sizeof($count)>0)
		{
			$output=$this->FrmRptModel->create_report_for_web($rs,$id);  
			return $output;	
		}

	}
	

	if($REPORT_NAME=='DOCTOR_COMMISSION_SUMMARY' ) 
	{
		
				$mainindx=$balance=0;		
				$ledger_ac=$param_array['ledger_ac'];
				$fromdate=$param_array['fromdate'];
				$todate=$param_array['todate'];

					//	echo $REPORT_NAME.' iiiiiii ' ;
				//	and doctor_ledger_id=".$ledger_ac

				$grpid='0';
				$details = "select distinct(c.group_id) grpid		from invoice_summary a, invoice_details b,productmstr c		
				where a.id=b.invoice_summary_id  and b.product_id=b.id  and a.invoice_date 
					between '".$param_array['fromdate']."' and '".$param_array['todate']."'
				and a.status='SALE' and  a.company_id=".$company_id." and a.doctor_ledger_id=".$ledger_ac ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0)
				{foreach ($details as $detail){
					$grpid=$grpid.','.$detail->grpid;
				}}


					$records = "select id,invoice_no,invoice_date
					 from invoice_summary where invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."'
					and status='SALE' and  company_id=".$company_id." and doctor_ledger_id=".$ledger_ac ;
					$records = $this->projectmodel->get_records_from_sql($records);
					if(count($records)>0){
					foreach ($records as $record){

									$rsval[$mainindx]['id']=$record->id;
									$rsval[$mainindx]['invoice_no']=$record->invoice_no;
									$rsval[$mainindx]['invoice_date']=$record->invoice_date;
									$rsval[$mainindx]['total_trade_value']=0;
									
									$ledger_ac_mstr=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers','id='.$ledger_ac); 

									$groups = "select * from 	frmrptgeneralmaster where  Status='MAIN_PRODUCT_GROUP' and id not in (57,58,59,60,61,62) " ;
									$groups = $this->projectmodel->get_records_from_sql($groups);
									if(count($groups)>0){
									foreach ($groups as $grpkey=>$group){

											$rsval[$mainindx]['group_id'][$grpkey]=$group->id;		
											$rsval[$mainindx]['groupname'][$grpkey]=$group->FieldVal;		
											$rsval[$mainindx]['shortname'][$grpkey]=$group->FieldID;

											$whr="product_group_id=".$group->id." and doctor_mstr_id=".$ledger_ac_mstr;
											$doc_commission=$this->projectmodel->GetSingleVal('commission_percentage','doctor_commission_set',$whr);

											$rsval[$mainindx]['group_wise_trade_val'][$grpkey]=''; 						
											$rsval[$mainindx]['group_commission'][$grpkey]=$doc_commission;
											$rsval[$mainindx]['group_commission_amt'][$grpkey]='';	
									}}
									$rsval[$mainindx]['row_comm_total']=0;
									

									$details = "select a.main_group_id,sum(a.taxable_amt) taxable_amt
									from invoice_details a,productmstr b		
									where a.invoice_summary_id=".$record->id." and a.product_id=b.id  group by a.main_group_id" ;
									$details = $this->projectmodel->get_records_from_sql($details);
									if(count($details)>0)
									{
									foreach ($details as $detail)
									{
		
												//$ledger_ac_mstr=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers','id='.$ledger_ac); 

												$whr="product_group_id=".$detail->main_group_id." and doctor_mstr_id=".$ledger_ac_mstr;
												$doc_commission=floatval($this->projectmodel->GetSingleVal('commission_percentage','doctor_commission_set',$whr));

											   $array_index = array_search($detail->main_group_id, $rsval[$mainindx]['group_id']);
												$rsval[$mainindx]['group_wise_trade_val'][$array_index]=$detail->taxable_amt; 
												$rsval[$mainindx]['group_commission'][$array_index]=$doc_commission;
												$rsval[$mainindx]['group_commission_amt'][$array_index]=
												round(($doc_commission*$detail->taxable_amt)/100,2);

												$rsval[$mainindx]['total_trade_value']=$rsval[$mainindx]['total_trade_value']+$detail->taxable_amt; 

												$rsval[$mainindx]['row_comm_total']=$rsval[$mainindx]['row_comm_total']+
												$rsval[$mainindx]['group_commission_amt'][$array_index];

									}}

									$mainindx=$mainindx+1;

				}}

			// $total_index=$mainindx+1;
			// $rsval[$total_index]['id']='';
			// $rsval[$total_index]['invoice_no']='';
			// $rsval[$total_index]['invoice_date']='';
			// $rsval[$total_index]['total_trade_value']=0;	
			// $rsval[$total_index]['row_comm_total']=0;

			// foreach ($rsval as $grpkey=>$rs){

			// 	$rsval[$total_index]['row_comm_total']=$rsval[$total_index]['row_comm_total']+$rs['row_comm_total'];
			// 	$rsval[$total_index]['total_trade_value']=$rsval[$total_index]['total_trade_value']+$rs['total_trade_value'];
				
			// }




			//print_r($rsval);
			return $rsval;		

	}



	if($REPORT_NAME=='DOCTOR_PRESCRIPTIONS' ) 
	{
		
		$mainindx=$balance=0;		
		$ledger_ac=$param_array['ledger_ac'];
		$fromdate=$param_array['fromdate'];
		$todate=$param_array['todate'];


	 $records = "select *
	from patient_prescription  where prescription_date	
	between '".$param_array['fromdate']."' and '".$param_array['todate']."'
	and   doctor_mstr_id=".$ledger_ac." ORDER BY id ";
	$records = $this->projectmodel->get_records_from_sql($records);
	if(count($records)>0){
	foreach ($records as $record){

		$rsval[$mainindx]['token_no']=$record->token_no;
		$rsval[$mainindx]['token_status']=$record->token_status;
		$rsval[$mainindx]['ACTUAL_VISIT_AMT']=$record->ACTUAL_VISIT_AMT;		
		$rsval[$mainindx]['patient_registration_id']=$record->patient_registration_id;
		$party_name=$this->projectmodel->GetSingleVal('party_name','patient_registration',
		'id='.$record->patient_registration_id);
		$rsval[$mainindx]['pname']=$party_name;			
		
		//doctor id get

		$whr="id=".$record->doctor_mstr_id;
		$ref_table_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',$whr);

		$whr="id=".$ref_table_id;
		$chamber_commission=$this->projectmodel->GetSingleVal('chamber_commission','doctor_mstr',$whr);

		$rsval[$mainindx]['comm_amt']=round($record->ACTUAL_VISIT_AMT*$chamber_commission/100);
		 
		$mainindx=$mainindx+1;

		}}

			
			return $rsval;		

	}





	if($REPORT_NAME=='PRODUCT_GROUP')
	{		
		$mainindx=0;
		$whr='id >0';
		$rsval=$this->projectmodel->GetMultipleVal('*','productmstr',$whr,' productname');
		$group1_cnt=sizeof($rsval);	 
		return $rsval;	
	}


	if($REPORT_NAME=='PRODUCT_GROUP_WISE_LISTING')
	{		
		$mainindx=0;
		$ledger_ac=$param_array['ledger_ac'];	
		$records="select * 	from productmstr  where group_id=".$ledger_ac;
		$records = $this->projectmodel->get_records_from_sql($records);
		foreach ($records as $key=>$record)
		{ 
			$rsval[$key]['Product Name']=$record->productname; 
			$rsval[$key]['Synonym']=$record->Synonym; 
			$rsval[$key]['Tax']=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$record->tax_ledger_id); 
			$rsval[$key]['hsncode']=$record->hsncode; 
		
		}

		return $rsval;	
	}
	
	if($REPORT_NAME=='PRODUCT_BATCH') 
	{
		
		$invoice_summary_id='0';
		$records="select id  from  invoice_summary where 	company_id=".$company_id;
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{$invoice_summary_id=$invoice_summary_id.','.$record->id;}



		$mainindx=0;
		$records="select batchno,exp_monyr,mfg_monyr  from invoice_details where 	product_id=".$param_array['param1']." 
		and invoice_summary_id in (".$invoice_summary_id.") 	group by batchno,exp_monyr,mfg_monyr";
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $records)
		{
			$opening_amount=0;
			//$rsval[$mainindx]['id']=$records->id;
			$rsval[$mainindx]['batchno']=$records->batchno; 
			$rsval[$mainindx]['exp_monyr']=$records->exp_monyr; 
			$rsval[$mainindx]['mfg_monyr']=$records->mfg_monyr; 
			$rsval[$mainindx]['total_purchase']=$rsval[$mainindx]['total_sale']=
			$rsval[$mainindx]['TOTAL_SELL_RTN']=$rsval[$mainindx]['TOTAL_PRUCHAR_RTN']=$rsval[$mainindx]['tot_sample']=0;
		
			//and exp_monyr='".$records->exp_monyr."' and mfg_monyr='".$records->mfg_monyr."'
			$products = "select sum(qnty) totqnty from invoice_details where 
			product_id=".$param_array['param1']." and 	batchno='".$records->batchno."'  and status='PURCHASE' and invoice_summary_id in (".$invoice_summary_id.")  " ;				
			$products = $this->projectmodel->get_records_from_sql($products);
			if(count($products)>0){foreach ($products as $product){$rsval[$mainindx]['total_purchase']=$product->totqnty;	}}

			$products = "select sum(qnty) totqnty from invoice_details where 
			product_id=".$param_array['param1']." and 	batchno='".$records->batchno."' and status='SALE' and invoice_summary_id in (".$invoice_summary_id.") " ;				
			$products = $this->projectmodel->get_records_from_sql($products);
			if(count($products)>0){foreach ($products as $product){$rsval[$mainindx]['total_sale']=$product->totqnty;	}}

			$products = "select sum(qnty) totqnty from invoice_details where 
			product_id=".$param_array['param1']." and 	batchno='".$records->batchno."'  and status='SALE_RTN' and invoice_summary_id in (".$invoice_summary_id.") " ;				
			$products = $this->projectmodel->get_records_from_sql($products);
			if(count($products)>0){foreach ($products as $product){$rsval[$mainindx]['TOTAL_SELL_RTN']=$product->totqnty;}}

			$products = "select sum(qnty) totqnty from invoice_details where 
			product_id=".$param_array['param1']." and 	batchno='".$records->batchno."' and status='PRUCHAR_RTN' and invoice_summary_id in (".$invoice_summary_id.") " ;				
			$products = $this->projectmodel->get_records_from_sql($products);
			if(count($products)>0){foreach ($products as $product){$rsval[$mainindx]['TOTAL_PRUCHAR_RTN']=$product->totqnty;}}

			// $products = "select sum(totqnty) totqnty from sample_tran_details where product_id=".$param_array['param1']." and 
			// batchno='".$records->batchno."'  and  status='SAMPLE_RCV' " ;
			// $products = $this->projectmodel->get_records_from_sql($products);
			// if(count($products)>0){foreach ($products as $product){$rsval[$mainindx]['tot_sample']= $product->totqnty;}}

			$qnty_available=$rsval[$mainindx]['total_available_qnty']=
			$rsval[$mainindx]['total_purchase']-$rsval[$mainindx]['total_sale']+
			$rsval[$mainindx]['TOTAL_SELL_RTN']-$rsval[$mainindx]['TOTAL_PRUCHAR_RTN']-$rsval[$mainindx]['tot_sample'];

			//$rate=$rsval[$mainindx]['rate']=$this->projectmodel->GetSingleVal('sell_price','productmstr',' id='.$param_array['param1']); 
			$whr="product_id=".$param_array['param1']." and 	batchno='".$records->batchno."'  and status='PURCHASE' and invoice_summary_id in (".$invoice_summary_id.") ";
			$rate=$rsval[$mainindx]['rate']=$this->projectmodel->GetSingleVal('mrp','invoice_details',$whr);
			$rsval[$mainindx]['total_amt']=$qnty_available*$rate;
			
			$rsval[$mainindx]['href']=$tranlink.'PRODUCT_BATCH_TRANSACTIONS/'.$param_array['param1'].'/'.$records->batchno;				

			$rsval[$mainindx]['qnty_available']=$qnty_available;
			$rsval[$mainindx]['rate']=$rate;	
			$rsval[$mainindx]['total']=$qnty_available*$rate;				
			
			$mainindx=$mainindx+1;
		}

		return $rsval;			
	}

	if($REPORT_NAME=='PRODUCT_BATCH_TRANSACTIONS') 
	{
		//delete invalid	
		$status='';
		$inv_detail_id='';
		$mfg_monyr='';
		$exp_monyr='';
		$totqnty='';
	
		// $records="select id,invoice_summary_id from invoice_details  ";
		// $records = 	$this->projectmodel->get_records_from_sql($records);
		// foreach ($records as $record)
		// {	
		// 	$dlts="select count(*) cnt from invoice_summary where id=".$record->invoice_summary_id;
		// 	$dlts = 	$this->projectmodel->get_records_from_sql($dlts);
		// 	foreach ($dlts as $dlt)
		// 	{	
		// 		if($dlt->cnt==0)
		// 		{
		// 			//echo 'Invalid sale detail id :'.$record->id.' | summary id :'.$record->invoice_summary_id.'<br>';
		// 			$this->db->Query("delete from invoice_details where invoice_summary_id=".$record->invoice_summary_id);
		// 		}
		// 	}			
		// }
	
		//delete invalid
		
			$mainindx=$balance=0;
			$records="select a.id,a.tbl_party_id,a.status,a.invoice_no,a.invoice_date ,b.qnty
			from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id and 
			b.product_id=".$param_array['param1']."  and b.batchno='".$param_array['param2']."' and a.company_id=".$company_id." order by a.invoice_date,a.id";
			$records = $this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{ 
			
				$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
				$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
				$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$record->tbl_party_id); 
				$rsval[$mainindx]['qnty']=$record->qnty; 
				$rsval[$mainindx]['status']=$record->status; 
				if($record->status=='PURCHASE' 	or $record->status=='SALE_RTN' or $record->status=='OPEN_BALANCE')
				{$balance=$balance+$record->qnty;}
				if($record->status=='SALE')
				{$balance=$balance-$record->qnty;}
				$rsval[$mainindx]['balance']=$balance;

				$rsval[$mainindx]['href']='';
				$mainindx=$mainindx+1;
			}
			return $rsval;		
	}

	if($REPORT_NAME=='EXPIRY_REGISTER')
	{
		
		$invoice_summary_id='0';
		$records="select id  from  invoice_summary where 	company_id=".$company_id;
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{$invoice_summary_id=$invoice_summary_id.','.$record->id;}

		$mainindx=0;	
		$trandate=$this->general_library->get_date($param_array['todate'],0,1,0);
		$trandate=substr($trandate,0,7).'-01';

		$whr=" EXPIRY_DATE<'$trandate' and  qty_available>0 and (status='PURCHASE' ) and invoice_summary_id in (".$invoice_summary_id.") ";
		$rsval=$this->projectmodel->GetMultipleVal('*','invoice_details',$whr);
		$group1_cnt=sizeof($rsval);	

		for($cnt=0;$cnt<$group1_cnt;$cnt++)
		{
			$rsval[$cnt]['href']='';
			$rsval[$cnt]['product_name']=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$rsval[$cnt]['product_id']); 
			$rsval[$cnt]['total_amt']=$rsval[$cnt]['qty_available']*$rsval[$cnt]['mrp'];	
		}
		
		// $records="select a.*,b.productname from  invoice_details a,productmstr b  where 
		// a.EXPIRY_DATE<'$trandate' and  a.qty_available>0 and (a.status='PURCHASE' or a.status='OPEN_BALANCE') and a.product_id=b.id	 ";
		// $records = $this->projectmodel->get_records_from_sql($records);	
		// foreach ($records as $records)
		// {
		// 	$opening_amount=0;
		// 	$PURCHASEID=$rsval[$mainindx]['id']=$records->id;
		// 	//$rsval[$mainindx]['product_name']=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$records->product_id);  
		// 	$mainindx=$mainindx+1;
		// }
		return $rsval;		

	}
	
	
	if($REPORT_NAME=='PRODUCT_TRANSACTIONS') 
	{
		//delete invalid	
		$status='';
		$inv_detail_id='';
		$mfg_monyr='';
		$exp_monyr='';
		$totqnty='';
				
		//delete invalid
		
			$mainindx=$balance=0;
			$records="select a.id,a.tbl_party_id,a.status,a.invoice_no,a.invoice_date ,b.totqnty
			from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id and 
			b.product_id=".$param_array['param1']."  and b.status='".$param_array['param2']."' and a.company_id=".$company_id." order by a.invoice_date,a.id";
			$records = $this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{ 
			
				$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
				$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
				$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('retail_name','stockist',' id='.$record->tbl_party_id); 
				$rsval[$mainindx]['qnty']=$record->totqnty; 
				$rsval[$mainindx]['status']=$record->status; 
				if($record->status=='PURCHASE' 	or $record->status=='SELL_RTN')
				{$balance=$balance+$record->totqnty;}
				if($record->status=='SELL')
				{$balance=$balance-$record->totqnty;}
				$rsval[$mainindx]['balance']=$balance;

				$rsval[$mainindx]['href']='';
				$mainindx=$mainindx+1;
			}
			
			
			// $records="select a.id,a.tbl_party_id,a.status,a.invoice_no,a.invoice_date ,b.totqnty
			// from sample_tran_summary a,sample_tran_details b where a.id=b.invoice_summary_id and 
			// b.product_id=".$param_array['param1']."   and b.status='SAMPLE_RCV' order by a.invoice_date,a.id";
			// $records = $this->projectmodel->get_records_from_sql($records);
			// foreach ($records as $key=>$record)
			// { 
			
			// 	$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
			// 	$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
			// 	$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('retail_name','stockist',' id='.$record->tbl_party_id); 
			// 	$rsval[$mainindx]['qnty']=$record->totqnty; 
			// 	$rsval[$mainindx]['status']=$record->status; 
			// 	$balance=$balance-$record->totqnty;
			// 	$rsval[$mainindx]['balance']=$balance;

			// 	$rsval[$mainindx]['href']='';
			// 	$mainindx=$mainindx+1;
			// }

			return $rsval;		
	}

	//GST RELATED REPORT
	if($REPORT_NAME=='BILL_WISE_SALE') 
	{
		//delete invalid	
		$status='';
		$inv_detail_id='';
		$mfg_monyr='';
		$exp_monyr='';
		$totqnty='';
		
		
			$mainindx=$balance=0;
			if($param_array['ledger_ac']==0)
			{
				$records="select * 	from invoice_summary where  invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
				and status='SALE' and company_id=".$company_id." ";
			}
			else
			{
				$records="select * 	from invoice_summary where tbl_party_id=".$param_array['ledger_ac']." 
				and  invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
				and status='SALE' and company_id=".$company_id." ";
			}

			if($param_array['user_ac']>0)
			{$records=$records." and emp_id=".$param_array['user_ac']." ";}
			
			$records=$records." order by invoice_date,id ";
			
		
			$records = $this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{ 
			
				$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
				$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
				$whr="id=".$record->tbl_party_id;
				$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',$whr); 
				$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('party_name','tbl_party',' id='.$tbl_party_id); 
				$rsval[$mainindx]['GSTNO']=$this->projectmodel->GetSingleVal('GSTNo','tbl_party',' id='.$tbl_party_id); 
				$rsval[$mainindx]['destination']=$this->projectmodel->GetSingleVal('address','tbl_party',' id='.$tbl_party_id); ; 
				


					//0% gst section
					$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from 
					invoice_details where  	tax_ledger_id=0 and invoice_summary_id=".$record->id ;
					$details = $this->projectmodel->get_records_from_sql($details);
					if(count($details)>0){foreach ($details as $detail)
					{
						
						$rsval[$mainindx]['taxable_amt_0']=$detail->taxable_amt; 
						$rsval[$mainindx]['CGST_0']=$detail->cgst_amt; 
						$rsval[$mainindx]['SGST_0']=$detail->sgst_amt; 
						$rsval[$mainindx]['IGST_0']=$detail->igst_amt; 
						$rsval[$mainindx]['amount_with_tax_0']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
					}}



				//5% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from invoice_details 
				where  	tax_ledger_id=319 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_5']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_5']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_5']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_5']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_5']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}


				//12% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from invoice_details 
				where  	tax_ledger_id=320 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_12']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_12']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_12']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_12']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_12']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}

				//18% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from 
				invoice_details where  	tax_ledger_id=321 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_18']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_18']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_18']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_18']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_18']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}

				// $rsval[$mainindx]['freegoods']=0;
				// $details = "select sum(freeqty*srate) freegoods_amt from invoice_details where   invoice_summary_id=".$record->id ;
				// $details = $this->projectmodel->get_records_from_sql($details);
				// if(count($details)>0){foreach ($details as $detail)
				// {
				// 	$rsval[$mainindx]['freegoods']=$detail->freegoods_amt; 
				// }}
				
				
				// $rsval[$mainindx]['interest_charge']=$record->interest_charge; 
				// $rsval[$mainindx]['delivery_charge']=$record->freight_charge; 
				// $rsval[$mainindx]['cash_discount']=$record->tot_cash_discount; 

				$rsval[$mainindx]['round_off']=$record->rndoff; 
				$rsval[$mainindx]['grand_total']=$record->grandtot; 


				$rsval[$mainindx]['href']='';
				$mainindx=$mainindx+1;
			}

			return $rsval;		
	}

	if($REPORT_NAME=='BILL_WISE_PURCHASE') 
	{
		//delete invalid	
		$status='';
		$inv_detail_id='';
		$mfg_monyr='';
		$exp_monyr='';
		$totqnty='';
		
		
			$mainindx=$balance=0;
			if($param_array['ledger_ac']==0)
				{
					$records="select * 	from invoice_summary where  invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
					and status='PURCHASE' and company_id=".$company_id." order by invoice_date,id";
				}
				else
				{
					$records="select * 	from invoice_summary where tbl_party_id=".$param_array['ledger_ac']." 
					and  invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
					and status='PURCHASE' and company_id=".$company_id." order by invoice_date,id";
				}
			$records = $this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{ 
			
				$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
				$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
				$whr="id=".$record->tbl_party_id;
				$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',$whr); 
				$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('party_name','tbl_party',' id='.$tbl_party_id); 
				$rsval[$mainindx]['GSTNO']=$this->projectmodel->GetSingleVal('GSTNo','tbl_party',' id='.$tbl_party_id); 
				$rsval[$mainindx]['destination']=$this->projectmodel->GetSingleVal('address','tbl_party',' id='.$tbl_party_id); ; 
				

				//5% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from invoice_details 
				where  	tax_ledger_id=319 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_5']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_5']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_5']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_5']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_5']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}


				//12% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from invoice_details 
				where  	tax_ledger_id=320 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_12']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_12']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_12']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_12']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_12']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}

				//18% gst section
				$details = "select sum(taxable_amt) taxable_amt,sum(cgst_amt) cgst_amt,sum(sgst_amt) sgst_amt,sum(igst_amt) igst_amt from 
				invoice_details where  	tax_ledger_id=321 and invoice_summary_id=".$record->id ;
				$details = $this->projectmodel->get_records_from_sql($details);
				if(count($details)>0){foreach ($details as $detail)
				{
					
					$rsval[$mainindx]['taxable_amt_18']=$detail->taxable_amt; 
					$rsval[$mainindx]['CGST_18']=$detail->cgst_amt; 
					$rsval[$mainindx]['SGST_18']=$detail->sgst_amt; 
					$rsval[$mainindx]['IGST_18']=$detail->igst_amt; 
					$rsval[$mainindx]['amount_with_tax_18']=$detail->taxable_amt+$detail->cgst_amt+$detail->sgst_amt+$detail->igst_amt; 					
				}}

				// $rsval[$mainindx]['freegoods']=0;
				// $details = "select sum(freeqty*srate) freegoods_amt from invoice_details where   invoice_summary_id=".$record->id ;
				// $details = $this->projectmodel->get_records_from_sql($details);
				// if(count($details)>0){foreach ($details as $detail)
				// {
				// 	$rsval[$mainindx]['freegoods']=$detail->freegoods_amt; 
				// }}
				
				
				// $rsval[$mainindx]['interest_charge']=$record->interest_charge; 
				// $rsval[$mainindx]['delivery_charge']=$record->freight_charge; 
				// $rsval[$mainindx]['cash_discount']=$record->tot_cash_discount; 

				$rsval[$mainindx]['round_off']=$record->rndoff; 
				$rsval[$mainindx]['grand_total']=$record->grandtot; 


				$rsval[$mainindx]['href']='';
				$mainindx=$mainindx+1;
			}

			return $rsval;		
	}

	if($REPORT_NAME=='HSN_WISE_SALE') 
	{
			 
		
		// select c.hsncode ,c.productname,sum(b.qnty) tot_qnty from invoice_summary a,invoice_details b,productmstr c 
		// where a.invoice_date between '2019-05-01' and '2019-07-13' and a.status='SALE' and a.id=b.invoice_summary_id group by b.product_id

		// select b.product_id,c.productname,c.hsncode from invoice_summary a,invoice_details b,productmstr c 
		 //where a.invoice_date between '2019-05-01' and '2019-07-13' and a.status='SALE' and a.id=b.invoice_summary_id and b.product_id=c.id group by c.hsncode

		 //931202
		 //
				$mainindx=$balance=0;		

				$records = "select b.product_id,c.productname,c.hsncode from invoice_summary a,invoice_details b,productmstr c 
					where a.invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."'
				 and a.status='SALE' and a.id=b.invoice_summary_id and b.product_id=c.id  and a.company_id=".$company_id." group by b.product_id " ;
				$records = $this->projectmodel->get_records_from_sql($records);
				if(count($records)>0){foreach ($records as $record)
				{

					$details = "select sum(b.qnty) tot_qnty,sum(b.subtotal) tot_value
					,sum(b.taxable_amt) taxable_amt,sum(b.cgst_amt) cgst_amt,sum(b.sgst_amt) sgst_amt,sum(b.igst_amt) igst_amt from 
					invoice_summary a,invoice_details b	where  	
					a.invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
					and a.status='SALE' and a.id=b.invoice_summary_id and  a.company_id=".$company_id." b.product_id=".$record->product_id ;
					$details = $this->projectmodel->get_records_from_sql($details);
					if(count($details)>0){foreach ($details as $detail)
					{
						$rsval[$mainindx]['hsn_code']=$record->hsncode; 
						$rsval[$mainindx]['hsn_desc']=$record->productname; 
						$rsval[$mainindx]['uqc']=''; 
						$rsval[$mainindx]['tot_qnty']=$detail->tot_qnty; 
						$rsval[$mainindx]['tot_value']=$detail->tot_value; 				
						$rsval[$mainindx]['taxable_amt']=$detail->taxable_amt;
						$rsval[$mainindx]['igst_amt']=$detail->igst_amt;
						$rsval[$mainindx]['cgst_amt']=$detail->cgst_amt;
						$rsval[$mainindx]['sgst_amt']=$detail->sgst_amt;
						$rsval[$mainindx]['href']='';
						$mainindx=$mainindx+1;
					}}

			}}
			return $rsval;		
	}
	

	if($REPORT_NAME=='HSN_WISE_SUMMARY') 
	{
			 
				$mainindx=$balance=0;		

				$records = "select c.tax_ledger_id,c.hsncode,c.productname from invoice_summary a,invoice_details b,productmstr c 
				where a.invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."'
			  and a.status='SALE' and a.id=b.invoice_summary_id and b.product_id=c.id and a.company_id=".$company_id." group by c.hsncode " ;
				$records = $this->projectmodel->get_records_from_sql($records);
				if(count($records)>0){foreach ($records as $record)
				{

					$product_ids=$this->get_product_ids_hsnwise($record->hsncode);

					$details = "select sum(b.qnty) tot_qnty,sum(b.subtotal) tot_value
					,sum(b.taxable_amt) taxable_amt,sum(b.cgst_amt) cgst_amt,sum(b.sgst_amt) sgst_amt,sum(b.igst_amt) igst_amt from 
					invoice_summary a,invoice_details b	where  	
					a.invoice_date 	between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
					and a.status='SALE' and  a.company_id=".$company_id." and  a.id=b.invoice_summary_id and b.product_id  in (".$product_ids.")" ;
					$details = $this->projectmodel->get_records_from_sql($details);
					if(count($details)>0){foreach ($details as $detail)
					{
						$rsval[$mainindx]['hsn_code']=$record->hsncode; 
						$rsval[$mainindx]['productname']=$record->productname; 

						$rsval[$mainindx]['gst_per']=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$record->tax_ledger_id); 					
						$rsval[$mainindx]['tot_qnty']=$detail->tot_qnty; 
					//	$rsval[$mainindx]['tot_value']=$detail->tot_value; 				
						$rsval[$mainindx]['taxable_amt']=$detail->taxable_amt;
						$rsval[$mainindx]['igst_amt']=$detail->igst_amt;
						$rsval[$mainindx]['cgst_amt']=$detail->cgst_amt;
						$rsval[$mainindx]['sgst_amt']=$detail->sgst_amt;
						$rsval[$mainindx]['tot_value']=$rsval[$mainindx]['taxable_amt']+$rsval[$mainindx]['igst_amt']+$rsval[$mainindx]['cgst_amt']+$rsval[$mainindx]['sgst_amt'];
						
						$rsval[$mainindx]['href']='';
						$mainindx=$mainindx+1;
					}}

			}}
			return $rsval;		
	}

	//GST RELATED REPORT END

	if($REPORT_NAME=='DEBTORS_SUMMARY' || $REPORT_NAME=='CREDITORS_SUMMARY') 
	{return $this->all_mis_report('GENERAL_LEDGER',$param_array);}


	if($REPORT_NAME=='DOCTOR_COMMISSION_DETAILS') 
	{
		return $this->all_mis_report('GENERAL_LEDGER',$param_array);	
	}
	
	if($REPORT_NAME=='PRODUCT_WISE_PURCHASE' || $REPORT_NAME=='PRODUCT_WISE_SALE') 
	{
			//delete invalid	
			$status='';
			$inv_detail_id='';
			$mfg_monyr='';
			$exp_monyr='';
			$totqnty='';
			$product_id=$param_array['param1'];

			if($REPORT_NAME=='PRODUCT_WISE_PURCHASE')
			{$status='PURCHASE';}
			if($REPORT_NAME=='PRODUCT_WISE_SALE')
			{$status='SALE';}
			
		
			$mainindx=$balance=0;
			if($product_id==0)
			{
				$records="select * 	from invoice_summary a,invoice_details b,productmstr c where  
				a.id=b.invoice_summary_id and b.product_id=c.id and b.RELATED_TO_MIXER='NO' and
				a.invoice_date  between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
				and a.status='$status' and a.company_id=".$company_id." order by c.id,a.id";
			}
			else
			{
			 	$records="select * 	from invoice_summary a,invoice_details b,productmstr c where  
				a.id=b.invoice_summary_id and b.product_id=c.id and b.RELATED_TO_MIXER='NO' and
				a.invoice_date  between '".$param_array['fromdate']."' and '".$param_array['todate']."' 
				and a.status='$status' and a.company_id=".$company_id." and b.product_id=".$product_id." order by c.id,a.id";
		
			}


			$records = $this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{ 
			
				$rsval[$mainindx]['invoice_no']=$record->invoice_no; 
				$rsval[$mainindx]['invoice_date']=$record->invoice_date; 
				$rsval[$mainindx]['party_name']=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$record->tbl_party_id); 
				$rsval[$mainindx]['product']=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$record->product_id); 
			
				$rsval[$mainindx]['rackno']=$record->rackno; 
				$rsval[$mainindx]['batchno']=$record->batchno;

				$rsval[$mainindx]['qnty']=$record->qnty; 
				if($REPORT_NAME=='PRODUCT_WISE_PURCHASE')
				{$rsval[$mainindx]['rate']=$record->rate;}
				if($REPORT_NAME=='PRODUCT_WISE_SALE')
				{$rsval[$mainindx]['rate']=$record->rate;}

				
				$rsval[$mainindx]['total']=$record->subtotal;
				$rsval[$mainindx]['discount']=$record->disc_amt;
				$rsval[$mainindx]['grandtot']=$record->taxable_amt;

				$rsval[$mainindx]['href']='';
				$mainindx=$mainindx+1;
			}

			return $rsval;		
	}



	//accounts report
	if($REPORT_NAME=='GENERAL_LEDGER')
	{
		//LEFT SECTION
		$mainindx=0;
		$ledger_ac=$param_array['ledger_ac'];
		$fromdate=$param_array['fromdate'];
		$todate=$param_array['todate'];
		$cr_open_balance=$dr_open_balance=0;
		if($ledger_ac>0)
		{
			
			$cr_open_balance=floatval($this->ledger_opening_balance($ledger_ac,$fromdate,'CR'));	
			$dr_open_balance=floatval($this->ledger_opening_balance($ledger_ac,$fromdate,'DR'));				

			if($cr_open_balance>0 || $dr_open_balance>0)
			{
				$rsval[$mainindx]['href']='';
				$rsval[$mainindx]['tran_date']=$fromdate;
				$rsval[$mainindx]['tran_type']='';
				$rsval[$mainindx]['tran_code']='';
				$rsval[$mainindx]['id']=0;
				$rsval[$mainindx]['particular']='Opening Balance';
				$rsval[$mainindx]['debit_amount']=$cr_open_balance;
				$rsval[$mainindx]['credit_amount']=$dr_open_balance ;	
				$rsval[$mainindx]['tran_table_name']='';
				$rsval[$mainindx]['tran_table_id']=0;
				$mainindx=$mainindx+1;
			}

			$records="select a.id hdr_id,a.tran_table_id,b.id dtl_id, a.tran_code,a.tran_date,b.amount,b.cr_ledger_account,b.dr_ledger_account,
			a.comment,a.TRAN_TYPE,b.matching_tran_id,a.tran_table_name,a.tran_table_id from acc_tran_header a,acc_tran_details b 
			where a.id=b.acc_tran_header_id  and a.tran_date between '".$fromdate."' and '".$todate."' 
			and (b.cr_ledger_account=".$ledger_ac."  or b.dr_ledger_account=".$ledger_ac.") and a.company_id=".$company_id." order by a.tran_date";						
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{								

				$credit_amount=$debit_amount=$opening_amount=0;

				$hdr_id=$record->hdr_id;
				$dtl_id=$record->dtl_id;
				$matching_tran_id=$record->matching_tran_id;
				
				$href=ADMIN_BASE_URL.'Accounts_controller/load_form_report/';

				// if($record->TRAN_TYPE=='PURCHASE')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'Purchase/'.$record->tran_table_id;}
				// if($record->TRAN_TYPE=='FREIGHT')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'Service_purchase/'.$record->tran_table_id;}
				// if($record->TRAN_TYPE=='SALE')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'sale_entry/'.$record->tran_table_id;}
				// if($record->TRAN_TYPE=='PAYMENT')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'AccountsPayment/'.$record->hdr_id;}
				// if($record->TRAN_TYPE=='RECEIVE')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'AccountsReceive/'.$record->hdr_id;}
				// if($record->TRAN_TYPE=='JOURNAL')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'AccountsJournal/'.$record->hdr_id;}
				// if($record->TRAN_TYPE=='CONTRA')
				// {$rsval[$mainindx]['href']=$rsval[$mainindx]['href'].'AccountsContra/'.$record->hdr_id;}
				
				// $rsval[$mainindx]['tran_date']=$record->tran_date;
				// $rsval[$mainindx]['tran_type']=$record->TRAN_TYPE;
				// $rsval[$mainindx]['tran_code']=$record->tran_code;

				$rsval[$mainindx]['details'][0]['particular']='';						
				$rsval[$mainindx]['details'][0]['qnty']='';
				$rsval[$mainindx]['details'][0]['rate']='';
				$rsval[$mainindx]['details'][0]['total']=''; 
				$rsval[$mainindx]['details'][0]['crdr']=''; 
				
				
				
				if($record->cr_ledger_account==$ledger_ac)
				{
					$credit_amount=$record->amount;

					$whr="  acc_tran_header_id=".$hdr_id." and matching_tran_id=".$matching_tran_id." and  dr_ledger_account>0 ";
					$rs=$this->projectmodel->GetMultipleVal('*','acc_tran_details',	$whr,'id ASC ');
					$json_array_count=sizeof($rs);	 
					for($fieldIndex=0;$fieldIndex<$json_array_count;$fieldIndex++)
					{	
						
						
						if($record->TRAN_TYPE=='PURCHASE')
						{$rsval[$mainindx]['href']=$href.'Purchase/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='FREIGHT')
						{$rsval[$mainindx]['href']=$href.'Service_purchase/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='SALE')
						{$rsval[$mainindx]['href']=$href.'sale_entry/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='PAYMENT')
						{$rsval[$mainindx]['href']=$href.'AccountsPayment/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='RECEIVE')
						{$rsval[$mainindx]['href']=$href.'AccountsReceive/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='JOURNAL')
						{$rsval[$mainindx]['href']=$href.'AccountsJournal/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='CONTRA')
						{$rsval[$mainindx]['href']=$href.'AccountsContra/'.$record->hdr_id;}
						
						$rsval[$mainindx]['tran_date']=$record->tran_date;
						$rsval[$mainindx]['tran_type']=$record->TRAN_TYPE;
						$rsval[$mainindx]['tran_code']=$record->tran_code;
						$rsval[$mainindx]['tran_table_name']=$record->tran_table_name;
						$rsval[$mainindx]['tran_table_id']=$record->tran_table_id;

						$rsval[$mainindx]['id']=$record->hdr_id;
						$rsval[$mainindx]['particular_ledger_account']=$rs[$fieldIndex]['dr_ledger_account'];
						$rsval[$mainindx]['particular']=
						$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$rs[$fieldIndex]['dr_ledger_account']); 		
						
						$parentid=$this->projectmodel->GetSingleVal('parent_id','acc_group_ledgers',' id='.$rs[$fieldIndex]['dr_ledger_account']); 
						if($record->TRAN_TYPE=='PURCHASE' && $parentid==14)
						{
							$dtl_records="select * from invoice_details where invoice_summary_id=".$record->tran_table_id;						
							$dtl_records = $this->projectmodel->get_records_from_sql($dtl_records);	
							foreach ($dtl_records as $key=>$dtl_record)
							{	
								$rsval[$mainindx]['details'][$key]['particular']=
								$parentid.$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$dtl_record->product_id);
								$rsval[$mainindx]['details'][$key]['qnty']=
								$dtl_record->qnty.' ';
								//$this->projectmodel->GetSingleVal('unit_name','unit_master',' id='.$dtl_record->unit_type_id); ;
								$rsval[$mainindx]['details'][$key]['rate']=$dtl_record->rate;
								$rsval[$mainindx]['details'][$key]['total']=$dtl_record->subtotal; 
								if($record->cr_ledger_account==$ledger_ac){$rsval[$mainindx]['details'][$key]['crdr']='Cr';}
								else {$rsval[$mainindx]['details'][$key]['crdr']='Dr'; }
							}

						}
						

						$debit_amount=$rs[$fieldIndex]['amount'];				
						if($credit_amount<=$debit_amount)
						{$rsval[$mainindx]['debit_amount']=$credit_amount;}
						else
						{$rsval[$mainindx]['debit_amount']=$debit_amount;}							
						$rsval[$mainindx]['credit_amount']='';	
						$mainindx=$mainindx+1;
					}

				}

				if($record->dr_ledger_account==$ledger_ac)
				{
					$debit_amount=$record->amount;

					$whr=" acc_tran_header_id=".$hdr_id." and matching_tran_id=".$matching_tran_id." and cr_ledger_account>0 ";
					$rs=$this->projectmodel->GetMultipleVal('*','acc_tran_details',	$whr,'id ASC ');
					$json_array_count=sizeof($rs);	 
					for($fieldIndex=0;$fieldIndex<$json_array_count;$fieldIndex++)
					{

						if($record->TRAN_TYPE=='PURCHASE')
						{$rsval[$mainindx]['href']=$href.'Purchase/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='FREIGHT')
						{$rsval[$mainindx]['href']=$href.'Service_purchase/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='SALE')
						{$rsval[$mainindx]['href']=$href.'sale_entry/'.$record->tran_table_id;}
						if($record->TRAN_TYPE=='PAYMENT')
						{$rsval[$mainindx]['href']=$href.'AccountsPayment/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='RECEIVE')
						{$rsval[$mainindx]['href']=$href.'AccountsReceive/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='JOURNAL')
						{$rsval[$mainindx]['href']=$href.'AccountsJournal/'.$record->hdr_id;}
						if($record->TRAN_TYPE=='CONTRA')
						{$rsval[$mainindx]['href']=$href.'AccountsContra/'.$record->hdr_id;}
						
						$rsval[$mainindx]['tran_date']=$record->tran_date;
						$rsval[$mainindx]['tran_type']=$record->TRAN_TYPE;
						$rsval[$mainindx]['tran_code']=$record->tran_code;
						$rsval[$mainindx]['tran_table_name']=$record->tran_table_name;
						$rsval[$mainindx]['tran_table_id']=$record->tran_table_id;
						
						$rsval[$mainindx]['id']=$record->hdr_id;
						$rsval[$mainindx]['particular_ledger_account']=$rs[$fieldIndex]['cr_ledger_account'];

						$rsval[$mainindx]['particular']=
						$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$rs[$fieldIndex]['cr_ledger_account']); 						
						
						$parentid=$this->projectmodel->GetSingleVal('parent_id','acc_group_ledgers',' id='.$rs[$fieldIndex]['cr_ledger_account']); 							
						if($record->TRAN_TYPE=='SALE' && $parentid==15)
						{
							$dtl_records="select * from invoice_details where invoice_summary_id=".$record->tran_table_id;						
							$dtl_records = $this->projectmodel->get_records_from_sql($dtl_records);	
							foreach ($dtl_records as $key=>$dtl_record)
							{	
								$rsval[$mainindx]['details'][$key]['particular']=
								$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$dtl_record->product_id);
								$rsval[$mainindx]['details'][$key]['qnty']=$dtl_record->qnty;
								//$this->projectmodel->GetSingleVal('unit_name','unit_master',' id='.$dtl_record->unit_type_id); ;
								$rsval[$mainindx]['details'][$key]['rate']=$dtl_record->rate;
								$rsval[$mainindx]['details'][$key]['total']=$dtl_record->subtotal; 
								if($record->cr_ledger_account==$ledger_ac){$rsval[$mainindx]['details'][$key]['crdr']='Cr';}
								else {$rsval[$mainindx]['details'][$key]['crdr']='Dr'; }
							}
						}

						$credit_amount=$rs[$fieldIndex]['amount'];						
						if($debit_amount<=$credit_amount)
						{$rsval[$mainindx]['credit_amount']=$debit_amount;}
						else
						{$rsval[$mainindx]['credit_amount']=$credit_amount;}
						$rsval[$mainindx]['debit_amount']='';	
						$mainindx=$mainindx+1;
					}

				}							
				
				
			}

			return $rsval;
		}
	}

	if($REPORT_NAME=='TRIAL_BALANCE')
		{
					//dynamic TREE HIERARCHY ARRAY CREATE VIDEO
					//https://www.youtube.com/watch?v=lewf32viAwA

					$mainindx=0;
					$output=array();	
					$fromdate=$param_array['fromdate'];
					$todate=$param_array['todate'];

					$sql="update acc_group_ledgers set temp_debit_balance=0 where id='1819'  ";
					$this->db->query($sql);
					$sql="update acc_group_ledgers set temp_debit_balance=0 where id='1818'  ";
					$this->db->query($sql);

					/*
					//opening,closing stock
					$records="select * FROM productmstr  ";						
					$records = $this->projectmodel->get_records_from_sql($records);	
					foreach ($records as $record)
					{			
						//opeing stock			
						$CLOSING_STOCK=$OPEING=$PURCHASE=$SALE=$PURCHASE_RTN=$SALE_RTN=0;

						$PURCHASE=$this->stock_transactions($fromdate,'PURCHASE',$record->id);
						$SALE=$this->stock_transactions($fromdate,'SALE',$record->id);
						$PURCHASE_RTN=$this->stock_transactions($fromdate,'PURCHASE_RTN',$record->id);
						$SALE_RTN=$this->stock_transactions($fromdate,'SALE_RTN',$record->id);
						$OPEING=$PURCHASE+$SALE_RTN-$SALE-$PURCHASE_RTN;
						
					//	echo 'id :'.$record->id.' PURCHASE'.$PURCHASE.' SALE'.$SALE.'<br>';

						$sql="update acc_group_ledgers set temp_debit_balance=temp_debit_balance+".$OPEING." where id='1818'  ";
						$this->db->query($sql);	

						$sql="update acc_group_ledgers set temp_debit_balance=0 where id='1819'  ";
						$this->db->query($sql);

						// $CLOSING_STOCK=$PURCHASE=$SALE=$PURCHASE_RTN=$SALE_RTN=0;
						// $dat1=$this->general_library->get_date($todate,1,0,0);

						// $PURCHASE=$this->stock_transactions($dat1,'PURCHASE',$record->id);
						// $SALE=$this->stock_transactions($dat1,'SALE',$record->id);
						// $PURCHASE_RTN=$this->stock_transactions($dat1,'PURCHASE_RTN',$record->id);
						// $SALE_RTN=$this->stock_transactions($dat1,'SALE_RTN',$record->id);

						// $CLOSING_STOCK=$PURCHASE+$SALE_RTN-$SALE-$PURCHASE_RTN-$OPEING;

						// $sql="update acc_group_ledgers set temp_debit_balance=temp_debit_balance+".$CLOSING_STOCK." where id='1819'  ";
						// $this->db->query($sql);	
						
					}
					*/


							$parent_id=0;		
							$rsval=$this->accounts_group_ledger_hierarchy(1,0,$fromdate, $todate);
							

							$JSON = json_encode(array_values($rsval));
							$jsonIterator = new RecursiveIteratorIterator(
							new RecursiveArrayIterator(json_decode($JSON, TRUE)),
							RecursiveIteratorIterator::SELF_FIRST);
							$mainindx=0;
							foreach ($jsonIterator as $key => $val) 
							{
									
									if(!is_array($val)) 
									{
											if($key == "id") {$output[$mainindx][$key]=$val;}
											if($key == "parent_id") {$output[$mainindx][$key]=$val;}
											if($key == "index") {$output[$mainindx][$key]=$val;}
											if($key == "name") {$output[$mainindx][$key]=$val;}
											if($key == "SHOW_IN_TRIAL_BALANCE") {$output[$mainindx][$key]=$val;}
											if($key == "FINAL_AC_TYPE") {$output[$mainindx][$key]=$val;}
											if($key == "acc_type") {$output[$mainindx][$key]=$val;$mainindx=$mainindx+1;}
									}			
						}
						
						//UPDATE LEDGER BALANCE
						$trading_cnt_total=sizeof($output); 						
						if($trading_cnt_total>0){  
						for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
						{	
								
							$records="select * FROM acc_group_ledgers where  id=".$output[$trading_cnt]['id'];						
							$records = $this->projectmodel->get_records_from_sql($records);	
							foreach ($records as $record)
							{			
								$output[$trading_cnt]['debit_amt']=$record->temp_debit_balance;
								$output[$trading_cnt]['credit_amt']=$record->temp_credit_balance;
							}
						}}

						//STOCK LEDGER UPDATE
						// //opening stock
						// $array_index = array_search(1814, array_column($output, 'id')); 		
						// $output[$array_index]['debit_amt']=150;
						// $output[$array_index]['credit_amt']=130;

						// //closing stock
						// $array_index = array_search(1815, array_column($output, 'id')); 		
						// $output[$array_index]['debit_amt']=260;
						// $output[$array_index]['credit_amt']=300;

						//UPDATE GROUP WISE  BALANCE
						// $trading_cnt_total=sizeof($output); 						
						// if($trading_cnt_total>0){  
							
						// 		for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
						// 		{	
						// 				// if($output[$trading_cnt]['index']=='')
						// 				// {$output[$trading_cnt]['index']=0;}
						// 				$array_index = array_search($output[$trading_cnt]['parent_id'], array_column($output, 'id')); 
						// 				$output[$array_index]['debit_amt']=$output[$array_index]['debit_amt']+$output[$trading_cnt]['debit_amt'];
						// 				$output[$array_index]['credit_amt']=$output[$array_index]['credit_amt']+$output[$trading_cnt]['credit_amt'];
						// 		}		
						// }

						return $output;

		
		}


		if($REPORT_NAME=='PROFIT_LOSS_ACCOUNT')
		{}



		if($REPORT_NAME=='BALANCE_SHEET')
		{}
		

	//accounts report end 

}



	function get_product_ids_hsnwise($hsncode='')
	{

		$product_ids='0';
		$records="select * FROM productmstr where  hsncode='".$hsncode."'";						
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{	$product_ids=$product_ids.','.$record->id;}

		return $product_ids;		
	}


	function accounts_group_ledger_hierarchy($parent_id='',$index=0,$fromdate, $todate)
	{
			$output=array();
			$records="select * FROM acc_group_ledgers where  parent_id=".$parent_id;						
			$records = $this->projectmodel->get_records_from_sql($records);	
			foreach ($records as $record)
			{								
				$sub_array=array();
				$sub_array['index']=$index;
				$sub_array['id']=$record->id;
				$sub_array['parent_id']=$record->parent_id;
				$sub_array['name']=$record->acc_name;
				$sub_array['SHOW_IN_TRIAL_BALANCE']=$record->SHOW_IN_TRIAL_BALANCE;
				$sub_array['FINAL_AC_TYPE']=$record->FINAL_AC_TYPE;
				$sub_array['acc_type']=$record->acc_type;

				$whr='id='.$record->parent_id;
				$FINAL_AC_TYPE=$this->projectmodel->GetSingleVal('FINAL_AC_TYPE','acc_group_ledgers',$whr);
				if($FINAL_AC_TYPE<>'NA')
				{$this->db->query("update acc_group_ledgers set FINAL_AC_TYPE='".$FINAL_AC_TYPE."' where parent_id=".$record->parent_id);}

				//get ledger balance--
				if($record->acc_type=='LEDGER')
				{
					if($record->id ==1818 || $record->id ==1819)
					{

						$dr_balance_total=$cr_balance_total=0;
						 $totals="select sum(temp_debit_balance) temp_debit_balance,sum(temp_credit_balance) temp_credit_balance
						FROM acc_group_ledgers where  id=".$record->id;						
						$totals = $this->projectmodel->get_records_from_sql($totals);	
						foreach ($totals as $total)
						{$dr_balance_total=$total->temp_debit_balance; $cr_balance_total=$total->temp_credit_balance;	}	
						if(is_null($dr_balance_total)){$dr_balance_total=0;}
						if(is_null($cr_balance_total)){$cr_balance_total=0;}
						
							$sql="update acc_group_ledgers 
						set temp_debit_balance=temp_debit_balance+".$dr_balance_total.",temp_credit_balance=temp_credit_balance+".$cr_balance_total." where id=".$parent_id;
						//	echo '<br>';
						$this->db->query($sql);	
	
					}
					else
					{
						$rs=$this->ledger_wise_transactions($record->id,$fromdate, $todate);
						$this->db->query("update acc_group_ledgers 
						set temp_debit_balance=".$rs[0]['dr_balance'].",temp_credit_balance=".$rs[0]['cr_balance']." where id=".$record->id);	
						
						$this->db->query("update acc_group_ledgers 
						set temp_debit_balance=temp_debit_balance+".$rs[0]['dr_balance'].",temp_credit_balance=temp_credit_balance+".$rs[0]['cr_balance']." where id=".$parent_id);	
	
					}
				
				}
				//UPDATE GROUP WISE BALANCE
				if($record->acc_type=='GROUP')
				{
					$dr_balance_total=$cr_balance_total=0;
					$totals="select sum(temp_debit_balance) temp_debit_balance,sum(temp_credit_balance) temp_credit_balance
					FROM acc_group_ledgers where acc_type='GROUP' and parent_id=".$record->id;						
					$totals = $this->projectmodel->get_records_from_sql($totals);	
					foreach ($totals as $total)
					{$dr_balance_total=$total->temp_debit_balance; $cr_balance_total=$total->temp_credit_balance;	}	
						if(is_null($dr_balance_total)){$dr_balance_total=0;}
						if(is_null($cr_balance_total)){$cr_balance_total=0;}

					$this->db->query("update acc_group_ledgers 	set temp_debit_balance=".$dr_balance_total.",
					temp_credit_balance=".$cr_balance_total." where id=".$record->id);
				}
				


				$sub_array['nodes']=array_values($this->accounts_group_ledger_hierarchy($record->id,$index+1,$fromdate, $todate));
				// if($record->acc_type=='GROUP')
				// {$sub_array['nodes']=array_values($this->accounts_group_ledger_hierarchy($record->id,$index+1));}
				$output[]=$sub_array;
			}
			return $output;
	}
	
	function ledger_wise_transactions($ledger_ac='', $fromdate='', $todate='')
	{
		$rsval=array();

		$cr_open_balance=$this->ledger_opening_balance($ledger_ac,$fromdate,'CR');
		$dr_open_balance=$this->ledger_opening_balance($ledger_ac,$fromdate,'DR');
		//$cr_open_balance=$dr_open_balance=0;

		$sqlinv="select sum(b.amount) amount 		from acc_tran_header a,acc_tran_details b 
		where a.id=b.acc_tran_header_id and   a.tran_date between '".$fromdate."' and '".$todate."' and b.cr_ledger_account=".$ledger_ac." ";
		$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
		foreach ($cr_ledger_accounts as $cr_ledger_account)
		{$cr_open_balance=$cr_open_balance+$cr_ledger_account->amount;}		
			
		$sqlinv="select sum(b.amount) amount	from acc_tran_header a,acc_tran_details b 
		where a.id=b.acc_tran_header_id and   a.tran_date between '".$fromdate."' and '".$todate."' and b.dr_ledger_account=".$ledger_ac." ";
		$dr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
		foreach ($dr_ledger_accounts as $dr_ledger_account)
		{$dr_open_balance=$dr_open_balance+$dr_ledger_account->amount;}		
			
		$retuer_value='';
		$key=0;
		$rsval[$key]['dr_balance']=$rsval[$key]['cr_balance']=0;

		if($dr_open_balance>=$cr_open_balance)
		{$rsval[$key]['dr_balance']=$dr_open_balance-$cr_open_balance;}
		else
		{$rsval[$key]['cr_balance']=$cr_open_balance-$dr_open_balance;}

	
		return $rsval;
	}


	function ledger_opening_balance($ledger_ac='',$from_date='',$ac_tran_type='CR')
	{
		
					$FINAL_AC_TYPE='NA';
					$cr_open_balance=$dr_open_balance=$TRAN_TYPE='CR';
					$OB_AMT=0;

					//BALANCE AS PER MASTER
					$whr='id='.$ledger_ac;
					$FINAL_AC_TYPE=$this->projectmodel->GetSingleVal('FINAL_AC_TYPE','acc_group_ledgers',$whr);
					$TRAN_TYPE=$this->projectmodel->GetSingleVal('TRAN_TYPE','acc_group_ledgers',$whr);
					$OB_AMT=$this->projectmodel->GetSingleVal('OB_AMT','acc_group_ledgers',$whr);		
					if($TRAN_TYPE=='CR')
					{$cr_open_balance=$OB_AMT;$dr_open_balance=0;}
					else
					{ $cr_open_balance=0;$dr_open_balance=$OB_AMT;}

					//TRANSACTIONAL BALANCE

					if($FINAL_AC_TYPE=='BALANCE_SHEET_ASSET' || $FINAL_AC_TYPE=='BALANCE_SHEET_LIABILITY') // BALANCESHEET LEDGERS
					{
						$from_date=$this->general_library->get_date($from_date,-1,0,0);

						$sqlinv="select sum(b.amount) amount 	from acc_tran_header a,acc_tran_details b 
						where a.id=b.acc_tran_header_id and  a.tran_date<='".$from_date."' and b.cr_ledger_account=".$ledger_ac." ";
						$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
						foreach ($cr_ledger_accounts as $cr_ledger_account)
						{$cr_open_balance=$cr_open_balance+$cr_ledger_account->amount;}		
						
						$sqlinv="select sum(b.amount) amount	from acc_tran_header a,acc_tran_details b 
						where a.id=b.acc_tran_header_id and   a.tran_date<='".$from_date."' and b.dr_ledger_account=".$ledger_ac." ";
						$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
						foreach ($cr_ledger_accounts as $cr_ledger_account)
						{$dr_open_balance=$dr_open_balance+$cr_ledger_account->amount;}		

					}					
					else //NON BALANCESHEET LEDGERS
					{					
						$cr_open_balance=$dr_open_balance=0;
						$finyr=$this->general_library->get_fin_yr_yyyy($from_date);
						$finyr_start_date=substr($finyr,0,4).'-04-01';

						if($finyr_start_date<$from_date)
						{$from_date=$this->general_library->get_date($from_date,-1,0,0);}					
					
						$sqlinv="select sum(b.amount) amount 	from acc_tran_header a,acc_tran_details b 
						where a.id=b.acc_tran_header_id and a.tran_date>='".$finyr_start_date."' and  
						a.tran_date<='".$from_date."' and b.cr_ledger_account=".$ledger_ac." ";
						$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
						foreach ($cr_ledger_accounts as $cr_ledger_account)
						{$cr_open_balance=$cr_open_balance+$cr_ledger_account->amount;}		
						
						$sqlinv="select sum(b.amount) amount	from acc_tran_header a,acc_tran_details b 
						where a.id=b.acc_tran_header_id and a.tran_date>='".$finyr_start_date."' and  
						a.tran_date<='".$from_date."' and b.dr_ledger_account=".$ledger_ac." ";
						$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
						foreach ($cr_ledger_accounts as $cr_ledger_account)
						{$dr_open_balance=$dr_open_balance+$cr_ledger_account->amount;}		
					
					}
								
					if($cr_open_balance<=$dr_open_balance)
					{	$dr_open_balance=$dr_open_balance-$cr_open_balance;$cr_open_balance=0;}
					else
					{	$cr_open_balance=$cr_open_balance-$dr_open_balance;$dr_open_balance=0;}
									
					if($ac_tran_type=='CR'){return $cr_open_balance;}
					else	{return $dr_open_balance;}
					
	}

	function stock_transactions($trandate='',$tran_type='',$stockid=0)
	{

		if($tran_type=='PURCHASE')
		{
				$cnt=$total_value=0;

				$records="select count(*) cnt  from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id 
				and  a.status='PURCHASE' and a.invoice_date<'$trandate' and b.product_id=".$stockid;
				$records=$this->projectmodel->get_records_from_sql($records);
				foreach ($records as $key=>$record)
				{$cnt=$record->cnt;}
				
				if($cnt>0)
				{
					$records="select sum(b.subtotal) amount  from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id 
					and  a.status='PURCHASE' and a.invoice_date<'$trandate'  and b.product_id=".$stockid;
					$records=$this->projectmodel->get_records_from_sql($records);
					foreach ($records as $key=>$record)
					{$total_value=$record->amount;}
				}
					if(is_null($total_value))
					{$total_value=0;}
					return round($total_value,2);
		}

		if($tran_type=='SALE' || $tran_type=='PURCHASE_RTN' || $tran_type=='SALE_RTN')
		{
			$cnt=$total_value=0;

		 	$records="select count(*) cnt  from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id 
			and  a.status='".$tran_type."' and a.invoice_date<'$trandate' and b.product_id=".$stockid;
			$records=$this->projectmodel->get_records_from_sql($records);
			foreach ($records as $key=>$record)
			{$cnt=$record->cnt;}

			if($cnt>0)
			{
				$records="select b.* from invoice_summary a,invoice_details b where a.id=b.invoice_summary_id 
				and  a.status='".$tran_type."' and a.invoice_date<'$trandate' and b.product_id=".$stockid;
				$records=$this->projectmodel->get_records_from_sql($records);
				foreach ($records as $key=>$record)
				{
					$batchno=$record->batchno;
					$exp_monyr=$record->exp_monyr;
					$whr="status='".$tran_type."' and product_id=".$stockid." and batchno='$batchno' and exp_monyr='$exp_monyr' " ;
					$rate=$this->projectmodel->GetSingleVal('rate','invoice_details',$whr);
					$total_value=$total_value+($record->qnty*$record->rate);
				}
			}
				if(is_null($total_value))
				{$total_value=0;}
				return round($total_value,2);
		}

		

	}


		function ledger_opening_balance_old($ledger_ac='',$from_date='',$ac_tran_type='CR')
		{
			
			$FINAL_AC_TYPE='NA';
			$parent_id=0;
			
			$balancesheets="select * from acc_group_ledgers 
			where  id=".$ledger_ac." AND status='ACTIVE' order by acc_name";
			$balancesheets =$this->projectmodel->get_records_from_sql($balancesheets);
			foreach ($balancesheets as $balancesheet)
			{$parent_id=$balancesheet->parent_id;}
			
			$balancesheets="select * from acc_group_ledgers 
			where  id=".$parent_id." AND status='ACTIVE' order by acc_name";
			$balancesheets =$this->projectmodel->get_records_from_sql($balancesheets);
			foreach ($balancesheets as $balancesheet)
			{$FINAL_AC_TYPE=$balancesheet->FINAL_AC_TYPE;}
			
			
			$finyr=$this->general_library->get_fin_yr_yyyy($from_date);
			$finyr_start_date=substr($finyr,0,4).'-04-01';
			
			$cr_open_balance=$dr_open_balance=$TRAN_TYPE='CR';
			$OB_AMT=0;
			$sqlinv="select * from acc_group_ledgers where id=".$ledger_ac ;
			$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledger_names as $ledger_name)
			{
				$TRAN_TYPE=$ledger_name->TRAN_TYPE;
				$OB_AMT=$ledger_name->OB_AMT;
			}
			if($TRAN_TYPE=='CR')
			{  
				$cr_open_balance=$OB_AMT;
				$dr_open_balance=0; 
			}
			else
			{    
				$cr_open_balance=0;
				$dr_open_balance=$OB_AMT;
			}

			if($FINAL_AC_TYPE<>'BALANCE_SHEET')
			{$cr_open_balance=$dr_open_balance=0;}
			
			$from_date=$this->general_library->get_date($from_date,-1,0,0);
			
			if($finyr_start_date>$from_date)
			{$from_date=$finyr_start_date;}
			
			//echo $FINAL_AC_TYPE;
			
				$sqlinv="select sum(b.amount) amount
			from acc_tran_header a,acc_tran_details b 
			where a.id=b.acc_tran_header_id 
			and a.tran_date>='".$finyr_start_date."' and  a.tran_date<='".$from_date."' and b.cr_ledger_account=".$ledger_ac." ";
			$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($cr_ledger_accounts as $cr_ledger_account)
			{$cr_open_balance=$cr_open_balance+$cr_ledger_account->amount;}		
			
				$sqlinv="select sum(b.amount) amount
			from acc_tran_header a,acc_tran_details b 
			where a.id=b.acc_tran_header_id 
			and a.tran_date>='".$finyr_start_date."' and  a.tran_date<='".$from_date."' and b.dr_ledger_account=".$ledger_ac." ";
			$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($cr_ledger_accounts as $cr_ledger_account)
			{$dr_open_balance=$dr_open_balance+$cr_ledger_account->amount;}		
			
			if($cr_open_balance<=$dr_open_balance)
			{
				$dr_open_balance=$dr_open_balance-$cr_open_balance;
				$cr_open_balance=0;
			}
			else
			{
				$cr_open_balance=$cr_open_balance-$dr_open_balance;
				$dr_open_balance=0;
			}
			
			
			
			
			if($ac_tran_type=='CR')
			{return $cr_open_balance;}
			else
			{return $dr_open_balance;}
			

		}



}
?>