<!doctype html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<style>	
	
.invoice{
  width:100% !important;
  margin:50px auto;
  .invoice-header{
    padding:25px 25px 15px;
    h1{
      margin:0
    }
    .media{
      .media-body{
        font-size:.9em;
        margin:0;
      }
    }
  }
  .invoice-body{
    border-radius:10px;
    padding:25px;
    background:#FFF;
  }
  .invoice-footer{
    padding:15px;
    font-size:0.9em;
    text-align:left;
    color:#999;
  }
}
.logo{
  max-height:70px;
  border-radius:10px;
}
.dl-horizontal{
  margin:0;
  dt{
    float: left;
    width: 80px;
    overflow: hidden;
    clear: left;
    text-align: left;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  dd{
    margin-left:10px;
  }
}
.rowamount{
  padding-top:15px !important;
}
.rowtotal{
  font-size:1.3em;
}
.colfix{
  width:12%;
}
.mono{
  font-family:monospace;
}

   </style>


</head>
<?php
		
		$billimg='BILL-'.$table_id;
		$billimg=BASE_PATH_ADMIN.'uploads/'.$billimg.'.png'; 
		
		$challan_no=$challan_date='';	
		$grand_total=0;	
		if($table_id<>0)
		{
			$sql2="select * from invoice_summary where id=".$table_id." ";
			$rowrecord2 = $this->projectmodel->get_records_from_sql($sql2);	
			foreach ($rowrecord2 as $row2){ 
			$invoice_no=$row2->invoice_no; 
			$invoice_date=$row2->invoice_date; 	
			$tbl_party_id=$row2->tbl_party_id;	
			$comment=$row2->comment;
			$invoice_time=$row2->invoice_time;
			$emp_name=$row2->emp_name;
			$tot_cash_discount=$row2->tot_cash_discount;
			$grand_total=$row2->grandtot;
			}
		}
				
		$company_records="select * from company_details where id=1";
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		
		if($tbl_party_id<>124)
		{
			$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$tbl_party_id);
			
			$party_records="select * from tbl_party where id=".$tbl_party_id." ";
			$party_records = $this->projectmodel->get_records_from_sql($party_records);	
		}
		
?>
<body>

<div class="container ">    
<div class="panel panel-default">
        	  
		  
<div class="row">      
      <div class="col-xs-12" align="center">
          <ul class="media-body list-unstyled">
            <li><h3><strong><?php echo $company_records[0]->NAME;?></strong></h3></li>
            <li><?php echo $company_records[0]->ADDRESS;?></li>
            <li>Contact No:<?php echo $company_records[0]->MOB_NOS;?>&nbsp;|&nbsp;
			Email:<?php echo $company_records[0]->EMAIL_IDS;?></li>            
			<li>GST NO:<?php echo $company_records[0]->GSTNo;?></li>
          </ul>
      </div>
</div></br>

<div class="panel-heading">
<h3 class="panel-title" align="center">Bill Details</h3>
</div>

<div class="row">      
       <div class="col-xs-3">Bill No</div>
	   <div class="col-xs-3"><?php echo $invoice_no; ?></div>
	   <div class="col-xs-3">Time:</div>
	   <div class="col-xs-3"><?php echo $invoice_time; ?></div>
</div>

<div class="row">      
       <div class="col-xs-3">Date</div>
	   <div class="col-xs-3"><?php echo $invoice_date; ?></div>
	   <div class="col-xs-3">User</div>
	   <div class="col-xs-3"><?php echo $emp_name; ?></div>
</div>
<?php if($tbl_party_id<>124){ ?>

<div class="row">      
       <div class="col-xs-3">Party</div>
	   <div class="col-xs-9"><?php echo $party_records[0]->party_name;?></div>
</div>
<div class="row">      
       <div class="col-xs-3">Address</div>
	   <div class="col-xs-9"><?php echo $party_records[0]->address.'|'.
	   $party_records[0]->city.'|'.$party_records[0]->pin;?></div>
</div>
<div class="row">      
       <div class="col-xs-3">GST No</div>
	   <div class="col-xs-9"><?php echo $party_records[0]->GSTNo;?></div>
</div>

<?php }else{ ?>
<div class="row">      
       <div class="col-xs-3">Party</div>
	   <div class="col-xs-9">Cash</div>
</div>

<?php }?>

<div class="row" style="background-color:#33CCFF" >  
 <div class="panel-heading" >
       <div class="col-xs-1 ">SL</div>
	   <div class="col-xs-5" align="left">Description</div>
	   <div class="col-xs-2">Qty</div>
	   <div class="col-xs-2">Rate</div>
	    <div class="col-xs-2">Amount</div>
</div>
</div>

<div class="row" style="background-color:#33CCFF">  
   <div class="panel-heading" >
       <div class="col-xs-4">Batch</div>
	   <div class="col-xs-2">Mfg</div>
	   <div class="col-xs-2">Exp</div>
	   <div class="col-xs-2">Disc%</div>
	   <div class="col-xs-2">GST%</div>
</div>
</div>


<?php
	$total_amt=0;
	$total_disc_amt=0;
	$total_taxable_amt=0;
	$total_cgst_amt=0;
	$total_sgst_amt=0;
	//$grand_total=0;	
										
	$sql="select * from invoice_details where 
	 PRODUCT_TYPE in ('FINISH','MIXTURE') and invoice_summary_id=".$table_id." 	order by  id ";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	$i =0;
	if(count($rowrecord) > 0){
	foreach ($rowrecord as $row){ 
	$alt = ($i%2==0)?'alt1':'alt2';
	$i =$i +1;
	$stotal=0;		
	$tax_per=$row->tax_per;		
	
	/*$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',
					' id='.$row->product_id);*/
					
	$product_name=$row->product_name;
	$total_amt=$total_amt+$row->subtotal;
	$total_disc_amt=$total_disc_amt+$row->disc_amt;
	$total_taxable_amt=+$total_taxable_amt+$row->taxable_amt;
	$total_cgst_amt=$total_cgst_amt+$row->cgst_amt;
	$total_sgst_amt=$total_sgst_amt+$row->sgst_amt;
	//$grand_total=$grand_total+$row->taxable_amt+$row->cgst_amt+$row->sgst_amt;	
?>

<div class="row" >  
 
       <div class="col-xs-1"><?PHP echo $i; ?></div>
	   <div class="col-xs-5"  align="left"><?PHP echo $product_name; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->qnty; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->srate; ?></div>
	    <div class="col-xs-2"><?PHP echo $row->subtotal; ?></div>

</div>
<div class="row" >  
    
       <div class="col-xs-4"><?PHP echo $row->batchno; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->mfg_monyr; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->exp_monyr; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->disc_per; ?></div>
	   <div class="col-xs-2"><?PHP echo $row->tax_per; ?></div>

</div>
<br><br>

<?php }} ?>

	
<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left">TOTAL</h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right">
 <?php echo sprintf('%0.2f', $total_amt); ?></h3></div>
 </div>
</div> 

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left">DISC</h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right">
 <?php echo sprintf('%0.2f', $total_disc_amt); ?></h3></div>
 </div>
</div> 

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left">SGST</h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right">
 <?php echo sprintf('%0.2f', $total_sgst_amt); ?></h3></div>
 </div>
</div> 

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left">CGST</h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right">
 <?php echo sprintf('%0.2f', $total_cgst_amt); ?>
 </h3></div>
 </div>
</div> 


<?php if($tot_cash_discount>0){ ?>

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left"><strong>CASH DISCOUNT</strong></h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right"><strong>
 <?php echo  sprintf('%0.2f',round($tot_cash_discount)); ?>
 </strong></h3></div>
 </div>
</div> 

<?PHP } ?>

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-8"><h3 class="panel-title" align="left"><strong>PAYABLE AMOUNT</strong></h3></div>
 <div class="col-xs-4"><h3 class="panel-title" align="right"><strong>
 <?php echo  sprintf('%0.2f',round($grand_total)); ?>
 </strong></h3></div>
 </div>
</div> 





<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-12"><h3 class="panel-title" align="left">
 [ Rs in Words :<?php echo $this->numbertowords->convert_digit_to_words(round($grand_total)).' Only';?> ]
 </h3></div>
 
</div> 

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-12" align="center">
 <img src="<?php echo $billimg; ?>"/>
 </div>
 </div>
</div> 

<br><br><br><br><br><br>

<div class="row" > 
<div class="panel-heading">
 <div class="col-xs-12"><h3 class="panel-title" align="center">For <?php echo $company_records[0]->NAME;?></h3></div>
 </div>
</div> 

</div>
</div>

   
</body>
</html>



