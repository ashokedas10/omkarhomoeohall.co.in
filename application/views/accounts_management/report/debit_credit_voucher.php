
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voucher</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo BASE_PATH_ADMIN;?>theme_files/dist/css/AdminLTE.min.css">

 
  </head>
  <!--<body onLoad="window.print();">-->
  <body onLoad="window.print();">
  
<?php if($PRINTTYPE=='RECEIVE_VOUCHER'){ 

		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		
		$acc_tran_header="select * from acc_tran_header where 	id=".$table_id;
		$acc_tran_header = $this->projectmodel->get_records_from_sql($acc_tran_header);	
		
		$acc_tran_details="select * from acc_tran_details where acc_tran_header_id=".$table_id." and cr_ledger_account>0";
		$acc_tran_details = $this->projectmodel->get_records_from_sql($acc_tran_details);			
		$party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$acc_tran_details[0]->cr_ledger_account);
		
		$tbl_party="select * from tbl_party where id=".$party_id." ";
		$tbl_party = $this->projectmodel->get_records_from_sql($tbl_party);	
		
		$acc_tran_details_details="select * from acc_tran_details_details where acc_tran_details_id=".$acc_tran_details[0]->id." ";
		$acc_tran_details_details = $this->projectmodel->get_records_from_sql($acc_tran_details_details);	
		$count=sizeof($acc_tran_details_details); 
		
		
		//transaction by cash cheque
		$acc_tran_details_pay="select * from acc_tran_details where acc_tran_header_id=".$table_id." and dr_ledger_account>0";
		$acc_tran_details_pay = $this->projectmodel->get_records_from_sql($acc_tran_details_pay);		
		

?>

    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
      
        <div class="row invoice-info">
          <div class="col-sm-6 ">           
            <address>
             <!-- <strong>Admin, Inc.</strong><br>-->
			  <b><h2 class="page-header"><?php echo $company_records[0]->NAME;?></h2></b> 
              <?php echo $company_records[0]->ADDRESS;?><br>              
              Phone: <?php echo $company_records[0]->PhoneNo;?><br>
              DL NO:<?php echo $company_records[0]->DLNO1;?><br>
			  GSTIN NO:<?php echo $company_records[0]->GSTNo;?>
            </address>
          </div><!-- /.col -->
          
          <div class="col-sm-6 ">
           
			<b><h2 class="page-header">Money Receipt</h2></b>        
            <b>No:</b> <?php echo $acc_tran_header[0]->tran_code;?><br>
            <b>Date:</b> <?php echo $acc_tran_header[0]->tran_date;?><br>
            
          </div><!-- /.col -->
        </div><!-- /.row -->

       
	      <div class="row">
          
            <p class="lead">
             Received with thanks from "<?php echo $tbl_party[0]->party_name; ?>" of " <?php echo $tbl_party[0]->address; ?>".
			 The Sum of Rupees <?php echo ucwords($this->numbertowords->convert_digit_to_words($acc_tran_details[0]->amount)).' Only';?>.By 
			 <?php 
			 if($acc_tran_details_pay[0]->dr_ledger_account==317){ echo ' Cash';}
			 else
			 {
			  echo ' Bank.Detail as follows <br>';
			  
			  $acc_tran_details_banks="select * from acc_tran_details_details where acc_tran_details_id=".$acc_tran_details_pay[0]->id." ";
			  $acc_tran_details_banks = $this->projectmodel->get_records_from_sql($acc_tran_details_banks);	
			  $count_bank=sizeof($acc_tran_details_banks);
				    if($count_bank>0){  
					for($cnt=0;$cnt<$count_bank;$cnt++)
					{		
					echo 'Ins No :'.$acc_tran_details_banks[$cnt]->BILL_INSTRUMENT_NO.'| Dated :'.$acc_tran_details_banks[$cnt]->CHQDATE.
					' | Amt :'.$acc_tran_details_banks[$cnt]->AMOUNT.'<br>';
					
					}
				}
			 }
			 ?>
			 
            </p>
         	
      
        </div>
	   
	  <?php  if($count>0){?>
        <div class="row">
		<p class="lead">As per Invoice details given below</p>
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr  style="background-color:#CCCCCC">
                  <td><b>Srl</b></td>
                  <td><b>Dated</b></td>
                  <td><b>Invoice No</b></td>
                  <td align="right"><b>Bill Amt</b></td>
                  <td align="right"><b>O/s Amt</b></td>
				  <td align="right"><b>Paid Amt</b></td>
                </tr>
              </thead>
              <tbody>
			   <?php
			   
				for($cnt=0;$cnt<$count;$cnt++)
				{		
				$invoice_date=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$acc_tran_details_details[$cnt]->TABLE_ID);
				$grandtot=$this->projectmodel->GetSingleVal('grandtot','invoice_summary',' id='.$acc_tran_details_details[$cnt]->TABLE_ID);
				
			   ?>
                <tr>
                  <td><?php echo $cnt+1;?></td>
                  <td><?php echo $invoice_date;?></td>
                  <td><?php echo $acc_tran_details_details[$cnt]->BILL_INSTRUMENT_NO;?></td>
                  <td  align="right"><?php echo $grandtot;?></td>
                  <td  align="right"><?php 
				  echo ($grandtot-$this->accounts_model->bill_wise_outstanding('invoice_summary',$acc_tran_details_details[$cnt]->TABLE_ID,'MINUS'));?>
				  </td>
				  <td  align="right"><?php echo $acc_tran_details_details[$cnt]->AMOUNT;?></td>
                </tr>
                <?php }?>		
						
				 <tr>
                  <td colspan="2">Total</td>
                  <td colspan="3">Rs in Words :<?php echo ucwords($this->numbertowords->convert_digit_to_words($acc_tran_details[0]->amount)).' Only';?></td>
				  <td  align="right"><?php echo $acc_tran_details[0]->amount;?></td>
                </tr>
				
				 <tr>
                  
                  
				  <td colspan="6" align="right">
			       <p>Authorised Signature</p></td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->
		
		<?php }?>
     
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

<?php } ?>

<?php if($PRINTTYPE=='PAYMENT_VOUCHER'){ 
		
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;		
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		
		$acc_tran_header="select * from acc_tran_header where 	id=".$table_id;
		$acc_tran_header = $this->projectmodel->get_records_from_sql($acc_tran_header);	
		
		$acc_tran_details="select * from acc_tran_details where acc_tran_header_id=".$table_id." and dr_ledger_account>0";
		$acc_tran_details = $this->projectmodel->get_records_from_sql($acc_tran_details);			
		$party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$acc_tran_details[0]->dr_ledger_account);
		
		$tbl_party="select * from tbl_party where id=".$party_id." ";
		$tbl_party = $this->projectmodel->get_records_from_sql($tbl_party);	
		
		$acc_tran_details_details="select * from acc_tran_details_details where acc_tran_details_id=".$acc_tran_details[0]->id." ";
		$acc_tran_details_details = $this->projectmodel->get_records_from_sql($acc_tran_details_details);	
		$count=sizeof($acc_tran_details_details); 
				
		//transaction by cash cheque
		$acc_tran_details_pay="select * from acc_tran_details where acc_tran_header_id=".$table_id." and cr_ledger_account>0";
		$acc_tran_details_pay = $this->projectmodel->get_records_from_sql($acc_tran_details_pay);		

?>

    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
       
        <div class="row invoice-info">
          <div class="col-sm-6 ">           
            <address>
             <!-- <strong>Admin, Inc.</strong><br>-->
			  <b><h2 class="page-header"><?php echo $company_records[0]->NAME;?></h2></b> 
              <?php echo $company_records[0]->ADDRESS;?><br>              
              Phone: <?php echo $company_records[0]->PhoneNo;?><br>
              DL NO:<?php echo $company_records[0]->DLNO1;?><br>
			  GSTIN NO:<?php echo $company_records[0]->GSTNo;?>
            </address>
          </div><!-- /.col -->
          
          <div class="col-sm-6 ">
           
			<b><h2 class="page-header">Payment Voucher</h2></b>        
            <b>No:</b> <?php echo $acc_tran_header[0]->tran_code;?><br>
            <b>Date:</b> <?php echo $acc_tran_header[0]->tran_date;?><br>
            
          </div><!-- /.col -->
        </div><!-- /.row -->

       
	      <div class="row">
          
            <p class="lead">
             Payment given to "<?php echo $tbl_party[0]->party_name; ?>" of " <?php echo $tbl_party[0]->address; ?>".
			 The Sum of Rupees <?php echo ucwords($this->numbertowords->convert_digit_to_words($acc_tran_details[0]->amount)).' Only';?>.By 
			 <?php 
			 if($acc_tran_details_pay[0]->dr_ledger_account==317){ echo ' Cash';}
			 else
			 {
			  echo ' Bank.Detail as follows <br>';
			  
			  $acc_tran_details_banks="select * from acc_tran_details_details where acc_tran_details_id=".$acc_tran_details_pay[0]->id." ";
			  $acc_tran_details_banks = $this->projectmodel->get_records_from_sql($acc_tran_details_banks);	
			  $count_bank=sizeof($acc_tran_details_banks);
				    if($count_bank>0){  
					for($cnt=0;$cnt<$count_bank;$cnt++)
					{		
					echo 'Ins No :'.$acc_tran_details_banks[$cnt]->BILL_INSTRUMENT_NO.'| Dated :'.$acc_tran_details_banks[$cnt]->CHQDATE.
					' | Amt :'.$acc_tran_details_banks[$cnt]->AMOUNT.'<br>';
					
					}
				}
			 }
			 ?>
			 
            </p>
         	
      
        </div>
	   
	  <?php   if($count>0){?>
        <div class="row">
		<p class="lead">As per Invoice details given below</p>
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr  style="background-color:#CCCCCC">
                  <td><b>Srl</b></td>
                  <td><b>Dated</b></td>
                  <td><b>Invoice No</b></td>
                  <td align="right"><b>Bill Amt</b></td>
                  <td align="right"><b>O/s Amt</b></td>
				  <td align="right"><b>Paid Amt</b></td>
                </tr>
              </thead>
              <tbody>
			  <?php
			  
				for($cnt=0;$cnt<$count;$cnt++)
				{		
				$invoice_date=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$acc_tran_details_details[$cnt]->TABLE_ID);
				$grandtot=$this->projectmodel->GetSingleVal('grandtot','invoice_summary',' id='.$acc_tran_details_details[$cnt]->TABLE_ID);
				
			   ?>
                <tr>
                  <td><?php echo $cnt+1;?></td>
                  <td><?php echo $invoice_date;?></td>
                  <td><?php echo $acc_tran_details_details[$cnt]->BILL_INSTRUMENT_NO;?></td>
                  <td  align="right"><?php echo $grandtot;?></td>
                  <td  align="right"><?php 
				  echo ($grandtot-$this->accounts_model->bill_wise_outstanding('invoice_summary',$acc_tran_details_details[$cnt]->TABLE_ID,'MINUS'));?>
				  </td>
				  <td  align="right"><?php echo $acc_tran_details_details[$cnt]->AMOUNT;?></td>
                </tr>
                <?php }?>				
				 <tr>
                  <td colspan="2">Total</td>
                  <td colspan="3">Rs in Words :<?php echo ucwords($this->numbertowords->convert_digit_to_words($acc_tran_details[0]->amount)).' Only';?></td>
				  <td  align="right"><?php echo $acc_tran_details[0]->amount;?></td>
                </tr>
				
				 <tr>
                  
                  
				  <td colspan="6" align="right">
			       <p>Authorised Signature</p></td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->
		
		<?php }?>
     
      </section><!-- /.content -->
    </div><!-- ./wrapper -->


<?php } ?>

 </body>
</html>


