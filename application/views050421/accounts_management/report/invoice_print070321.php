<link href="https://fonts.googleapis.com/css?family=PT+Serif&display=swap" rel="stylesheet">



<style type="text/css">

#invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:1mm;
  margin: 5px auto;
  width: 4in;
  background: #FFF;
  /*font-family: 'PT Serif', serif;*/
  font-size:12px;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
	position:absolute;
	top:10px;
	left:0px;
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  /*//padding: 5px 0 5px 15px;
  //border: 1px solid #EEE*/
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.tableitem_number{
   
  font-family: Arial Black; 
  font-size:11px;;
  font-weight:bold;
  background: #EEE;
}

.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}
</style>


<style type="text/css">
.stamp {
	position:absolute;
	top:150px;
	right:150px;
  transform: rotate(12deg);
	color: #555;
	font-size: 3rem;
	font-weight: 300;
	border: 0.25rem solid #555;
	display: inline-block;
	padding: 0.25rem 1rem;
	text-transform: uppercase;
	border-radius: 1rem;
	font-family: 'Courier';
	-webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
  -webkit-mask-size: 944px 604px;
  mix-blend-mode: multiply;
}

.is-nope {
  color: #D23;
  border: 0.5rem double #D23;
  transform: rotate(45deg);
	-webkit-mask-position: 2rem 3rem;
  font-size: 2rem;  
}

.is-approved {
	color: #0A9928;
	border: 0.5rem solid #0A9928;
	-webkit-mask-position: 13rem 6rem;
	transform: rotate(-14deg);
  border-radius: 0;
} 

.is-draft {
	color: #C4C4C4;
	border: 1rem double #C4C4C4;
	transform: rotate(-5deg);
  font-size: 6rem;
  font-family: "Open sans", Helvetica, Arial, sans-serif;
  border-radius: 0;
  padding: 0.5rem;
} 
.style1 {font-family: 'Courier'; font-weight:bold; font-size: 16px}

.product_list {font-family:'Bookman Old Style'; font-weight:500;  font-size: 12px}

.header_section {font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px}
 
  
 /*font-weight:bold;*/
  
</style>

<?php
		
		
		$image_path=BASE_PATH_ADMIN.'uploads/'.'logo.png';
		//$billimg='BILL-'.$table_id;
		$billtype='UNPAID';
		//$billimg=BASE_PATH_ADMIN.'uploads/'.$billimg.'.png'; 	
		$Bill_msg='Welcome To Omkar Homoeo Hall';	
		$challan_no=$challan_date='';	
		$grand_total=0;	
		$doctor='';
		
		if($table_id<>0)
		{
			$sql2="select * from invoice_summary where id=".$table_id." ";
			$invoice_summary = $this->projectmodel->get_records_from_sql($sql2);
			
			$doctor=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',
			' id='.$invoice_summary[0]->doctor_ledger_id);			
		}
		
		if($invoice_summary[0]->status=='SALE')
		{
			if($invoice_summary[0]->BILL_TYPE=='CASH_BILL'){$billtype='CASH BILL';}
			else
			{$billtype='CREDIT BILL';}
		}
		
		if($invoice_summary[0]->status=='SALE_RTN')
		{
			if($invoice_summary[0]->BILL_TYPE=='CASH_BILL'){$billtype='SALE RETURN IN CASH';}
			else
			{$billtype='SALE RETURN CREDITED';}
		}
		
		$gst_enable=false;		
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;					
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		if($company_records[0]->GSTNo<>''){$gst_enable=true;}
		
		if($invoice_summary[0]->tbl_party_id<>317)
		{
			$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$invoice_summary[0]->tbl_party_id);			
			$party_records="select * from tbl_party where id=".$tbl_party_id." ";
			$party_records = $this->projectmodel->get_records_from_sql($party_records);	
		}
		
		
		
?>

<html>
<head></head>
<!--onafterprint="self.close()"-->
<body onLoad="window.print();" onafterprint="self.close()">


<div id="invoice-POS" >

	
    
    <?php /*?><center id="top">
      <div class="logo"></div>
      <div class="info">
	  <span style="font-size:16px; position:absolute; top:10px; left:400px;"><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?></span>
	  <img src="<?php echo $image_path; ?>" width="50" height="50"/>
	  </div><!--End Info-->
    </center><!--End InvoiceTop--><?php */?>
	
	<div id="mid">
      <div class="info" style="border:" >                 
          <img src="<?php echo $image_path; ?>"  height="60"/>
		  <span style="font-size:22px; position:absolute; top:20px; left:90px;">
		  <?php /*?><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?><?php */?></span>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="mid">
      <div class="info header_section" >                 
           Add : <?php echo $company_records[0]->ADDRESS;?></br>
           Email : <?php echo $company_records[0]->company_mailid;?></br>
           Ph : <?php echo $company_records[0]->PhoneNo;?></br>
		   DL NO:<?php echo $company_records[0]->DLNO1;?><br>
		   <?php if($company_records[0]->GSTNo<>''){?>
		   GST NO:<?php echo $company_records[0]->GSTNo;?><br>
		   <?php }?>
      </div>
    </div><!--End Invoice Mid-->
	<br><br>
	<div id="mid"  align="center" style="border:dotted;font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px">
		Bill Details
	</div>
	<div id="mid" >
	<!--stamp section-->
	<?php /*?><span class="stamp is-nope"><?php echo $billtype;?></span><?php */?>
      <div class="info header_section" > 
           Bill No   :<?php echo $invoice_summary[0]->invoice_no.'.    Date: '.$invoice_summary[0]->invoice_date.' Time: '.$invoice_summary[0]->invoice_time; ?>
		   
		   <?php /*?>Date/Time :<?php echo $invoice_summary[0]->invoice_date.'/'.$invoice_summary[0]->invoice_time; ?><?php */?></br>
		  <?php /*?> Bill Type   :<?php echo '<strong>'.$billtype.'</strong>'; ?></br><?php */?>
		   
           Bill By   :<?php echo $invoice_summary[0]->emp_name; ?></br> 
		   Ref By   :<?php echo $doctor; ?></br> 
      </div>
    </div><!--End Invoice Mid-->
	<?php /*?><br>
	<div id="mid"  style="border:dotted;font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px" align="center">Party Details</div><?php */?>
	<!--End Invoice Mid-->
	
	<?php if($invoice_summary[0]->tbl_party_id<>317){ ?>
	<div id="mid" >
      <div class="info header_section" > 
           Name :<?php echo $party_records[0]->party_name; ?></br>
           <?php echo $party_records[0]->address.'|'.$party_records[0]->city.'|'.$party_records[0]->pin;?></br>
           GST No : <?php echo $party_records[0]->GSTNo; ?></br>
      </div>
    </div><!--End Invoice Mid-->
	<?php }else{ ?>	
	<div id="mid" >
      <div class="info header_section" > 
          <!-- Party :Cash-->
		   Name :<?php echo  $invoice_summary[0]->patient_name; ?></br>
		   Address :<?php echo  $invoice_summary[0]->patient_address; ?></br>
		   
      </div>
    </div><!--End Invoice Mid-->	
	<br>
	<?php }?>
	
	
    
    <div id="bot">

		<div id="table">					
			<table style="width:100%" align="center">					
					<!--<td class="Rate" align="right" style="background-color:#999999"> Dis% </td>-->
					<?php /*?><?php if($gst_enable){?>
					<tr  >
						<td class="item product_list" style="background-color:#999999"> Srl </td>
						<td class="item product_list"  style="background-color:#999999"> Item </td>
						<td class="Hours product_list" align="right" style="background-color:#999999"> MRP </td>
						<td class="item product_list" style="background-color:#999999"> Qty </td>
						<td class="Hours product_list" align="right" style="background-color:#999999"> Rate </td>
						<td class="Rate product_list" align="right" style="background-color:#999999"> Tax% </td>
						<td class="Rate product_list" align="right" style="background-color:#999999"> Total </td>
					</tr>
					<?php }else{ ?>
					<tr  >
						<td class="item product_list" style="background-color:#999999"> Srl </td>
						<td class="item product_list"  style="background-color:#999999" > Item </td>
						<td class="item product_list"  style="background-color:#999999" > Pack </td>
						<td class="Hours product_list" align="right" style="background-color:#999999"> MRP </td>
						<td class="Hours product_list" align="right" style="background-color:#999999"> Rate </td>
						<td class="item product_list" style="background-color:#999999"> Qty </td>
						<td class="Rate product_list" align="right" style="background-color:#999999" > Total </td>
					</tr>
					<?php }?><?php */?>
					
					<tr><td  colspan="7"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
					
					<?php if($gst_enable){?>
					<tr  >
						<td class="item product_list" > Srl </td>
						<td class="item product_list"> Item </td>
						<td class="Hours product_list" align="right" > MRP </td>
						<td class="item product_list" align="right"> Qty </td>
						<td class="Hours product_list" align="right" > Rate </td>
						<td class="Rate product_list" align="right" > Tax% </td>
						<td class="Rate product_list" align="right" > Total </td>
					</tr>
					<?php }else{ ?>
					<tr  >
						<td class="item product_list"> Srl </td>
						<td class="item product_list"  colspan="2"> Item </td>
						<td class="Hours product_list" align="right" > MRP </td>
						<td class="Hours product_list" align="right" > Rate </td>
						<td class="item product_list" align="center"> Qty </td>
						<td class="Rate product_list" align="right" > Total </td>
					</tr>
					<?php }?>
					
					<tr><td  colspan="7"><div style="border-top:solid;">&nbsp;</div></td></tr>
				
				<!-- NOT MOTHER MIXURE-->			
			<?php
				$grand_mrp=0;
				$total_mrp=$total_amt=0;
				$total_disc_amt=0;
				$total_taxable_amt=0;
				$total_cgst_amt=0;
				$total_sgst_amt=0;
				$total_qnty=0;
				//$grand_total=0;	
													
				$sql="select * from invoice_details where  	invoice_summary_id=".$table_id." 
				and main_group_id not in (57,58,59,60,61,62) order by  id ";
				$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
				$i =0;
				if(count($rowrecord) > 0){
				foreach ($rowrecord as $row){ 
				$alt = ($i%2==0)?'alt1':'alt2';
				$i =$i +1;
				$stotal=0;		
				$tax_per=$row->tax_per;		
				
				$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row->product_id);
					
				
				//potency
				if($row->potency_id>0)
				{
					$potency_name=
					$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row->potency_id);
				}
				else
				{ $potency_name='';}
				
				if($row->Synonym<>'')
				{$potency_name=$row->Synonym;}
			
				//pack			
				if($row->pack_id>0)
				{$pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row->pack_id);}
				else
				{ $pack_name='';}
				
				
				$no_of_dose=$row->no_of_dose;
				$dose_formula=$row->dose_Synonym;
								
				if($row->main_group_id==50) //MOTHER
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					$product_name=$product_name.' '.$potency_name;
				}
				
				if($row->main_group_id==52) //TRITURATION
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					$product_name=$product_name;
				}
				
				if($row->main_group_id==53) //DILUTION
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					$product_name=$product_name.' '.$potency_name;
										
					if($row->pack_synonym<>'')
					{$product_name=$product_name.' GL :'.$row->pack_synonym;}
			
				}
				
				if($row->main_group_id==54) //BIOCHEMIC
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					$product_name=$product_name.' '.$potency_name;
				}
				
				if($row->main_group_id==55) //WATER
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					$product_name=$product_name.' '.$potency_name.' DO:'.$no_of_dose;
				}
				
				if($row->main_group_id==56) //SUGAR_OF_MILK
				{
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					$pack_name='';
					
					$product_name=$product_name.' '.$potency_name; 
				    //$pack_name=$dose_formula.'='.$no_of_dose.' Dose ';
					if($dose_formula<>'')
					{$product_name=$product_name.' DO:'.$dose_formula.'='.$no_of_dose;}
					else
					{$product_name=$product_name.' DO:'.$no_of_dose;}
					
				}
				
				if($row->main_group_id==51) //PATENT
				{
					$product_name=$product_name;
					$pack_name='';
				}	
		
				
			/*	if($row->main_group_id==57) //MM1
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}
				
				if($row->main_group_id==57) //MM2
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}
				
				if($row->main_group_id==57) //MM3
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}
				
				if($row->main_group_id==57) //MM4
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}
				
				if($row->main_group_id==57) //MM5
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}
			 	
				if($row->main_group_id==57) //MM6
				{$product_name=$product_name.' '.$potency_name.' '.$no_of_dose;}*/
				
								
				//$product_name=$row->product_name;
				$total_mrp=$total_mrp+($row->mrp*$row->qnty);
				$total_amt=$total_amt+$row->subtotal;
				//$total_disc_amt=$total_disc_amt+$row->disc_amt;
				$total_disc_amt=$total_disc_amt+($total_mrp-$total_amt);
				$total_taxable_amt=+$total_taxable_amt+$row->taxable_amt;
				$total_cgst_amt=$total_cgst_amt+$row->cgst_amt;
				$total_sgst_amt=$total_sgst_amt+$row->sgst_amt;
				$total_qnty=$total_qnty+$row->qnty;
				//$grand_mrp=$grand_mrp+$total_mrp;
				
				$sql_grandmrp="select sum(mrp*qnty) grandmrp from invoice_details 
				where ITEM_DELETE_STATUS='NOT_DELETED' and  invoice_summary_id=".$table_id;
				$sql_grandmrp = $this->projectmodel->get_records_from_sql($sql_grandmrp);
				$grand_mrp=$sql_grandmrp[0]->grandmrp;
				//$grand_total=$grand_total+$row->taxable_amt+$row->cgst_amt+$row->sgst_amt;	
			?>		
					
						
						<?php if($gst_enable){?>
						<tr class="service">
							<td class="tableitem product_list"><?PHP echo $i; ?></td>
							<td class="tableitem product_list" ><?PHP echo $product_name; ?></td>
							<td class="tableitem product_list"><?PHP echo $row->qnty; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->mrp; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->srate; ?></td>
							<td class="tableitem product_list" align="center"><?PHP echo $row->tax_per; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->subtotal; ?></td>
						</tr>
						
						<?php }else{ ?>
						<tr class="service">
							<td class="tableitem product_list"><?PHP echo $i; ?></td>
							<td class="tableitem product_list"  colspan="2" style="width:auto"><?PHP echo $product_name.' '.$pack_name; ?></td>
							<?php /*?><td class="tableitem product_list" align="left"><?PHP echo $pack_name; ?></td><?php */?>
							<td class="tableitem product_list" align="right"><?PHP echo $row->mrp; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->rate; ?></td>
							<td class="tableitem product_list" align="center"><?PHP echo $row->qnty; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->subtotal; ?></td>
						</tr>
						
						<?php }?>
						
					
			<?PHP }} ?>	
			
			
			
													
							
			<!--FOR MOTHER MIXTURE SECTION-->	
			
			<?php
				$total_mrp=$total_amt=0;
				$total_disc_amt=0;
				$total_taxable_amt=0;
				$total_cgst_amt=0;
				$total_sgst_amt=0;
				
				//$grand_total=0;	
				
				/*$string = 'Sarah has 4 dolls and 6 bunnies.';
				preg_match_all('!\d+!', $string, $matches);
				print_r($matches);*/
				$mixno=0;				
				$mother_name='Mother T.Mix';
				$groups="select main_group_id from  invoice_details where 
				invoice_summary_id =".$table_id." and  main_group_id in (57,58,59,60,61,62) group by main_group_id";
				$groups = $this->projectmodel->get_records_from_sql($groups);
				foreach ($groups as $group)
				{
				$i =$i +1;
				$mixno=$mixno+1;	
				//TOTAL PACK CALCULATION
				$subtotal=$total_pack=0;
				$total_qnty=$total_qnty+1;
				$pack_calc='';
				
				$sql="select * from invoice_details where  	invoice_summary_id=".$table_id."  and main_group_id=".$group->main_group_id." order by id";
				$rowrecord = $this->projectmodel->get_records_from_sql($sql);						
				foreach ($rowrecord as $row)
				{
					//$matches=array();
					if($row->pack_id>0)
					{$pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row->pack_id);}
					else
					{ $pack_name='';}
					
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					
					
					$pack_calc=$pack_calc.' '.$pack_name;	
					$subtotal=$subtotal+$row->subtotal;		
				}
				
				preg_match_all('!\d+!', $pack_calc, $matches);
				
				//print_r($matches);
				//$total_pack=$total_pack+$matches[0];
				
				foreach ($matches as $key=>$matche)
				{
					foreach ($matche as $mat)
					{
						$total_pack=$total_pack+$mat;
					}
				}
				
											
		?>
		
			<tr class="service">
				<td class="tableitem product_list"><?PHP echo $i; ?></td>
				<td class="tableitem product_list" ><?PHP echo $mother_name.'-'.$mixno; ?></td>
				<td class="tableitem product_list" ><?php echo $total_pack.' ML'; ?></td>
				<td class="tableitem product_list" align="right">&nbsp;</td>
				<td class="tableitem product_list" align="right">&nbsp;</td>
				<td class="tableitem product_list"  align="center">1</td>
				<td class="tableitem product_list" align="right"><?PHP echo sprintf('%0.2f',$subtotal);  ?></td>
			</tr>
				
		<?php		
					$sql="select * from invoice_details where  	invoice_summary_id=".$table_id."  and main_group_id=".$group->main_group_id." order by id";
					$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
					
					if(count($rowrecord) > 0){
					foreach ($rowrecord as $row){ 
					$alt = ($i%2==0)?'alt1':'alt2';
					
					$stotal=0;		
					$tax_per=$row->tax_per;		
					
					$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row->product_id);
									
					if($row->product_Synonym<>'')
					{$product_name=$row->product_Synonym;}
					
					//potency
					if($row->potency_id>0)
					{
						$potency_name=
						$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row->potency_id);
					}
					else
					{ $potency_name='';}
					
					if($row->Synonym<>'')
					{$potency_name=$row->Synonym;}
				
					//pack			
					if($row->pack_id>0)
					{$pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row->pack_id);}
					else
					{ $pack_name='';}
					
					if($row->pack_synonym<>'')
					{$pack_name=$row->pack_synonym;}
					
					$no_of_dose=$row->no_of_dose;
					$dose_formula=$row->dose_Synonym;
									
					$product_name=$product_name.' '.$potency_name.' '.$pack_name;
					
			
				
								
				//$product_name=$row->product_name;
				 $total_mrp=$total_mrp+($row->mrp*$row->qnty);
				
				$total_amt=$total_amt+$row->subtotal;
				//$total_disc_amt=$total_disc_amt+$row->disc_amt;
				$total_disc_amt=$total_disc_amt+($total_mrp-$total_amt);
				$total_taxable_amt=+$total_taxable_amt+$row->taxable_amt;
				$total_cgst_amt=$total_cgst_amt+$row->cgst_amt;
				$total_sgst_amt=$total_sgst_amt+$row->sgst_amt;
				
				
				$sql_grandmrp="select sum(mrp*qnty) grandmrp from invoice_details 
				where ITEM_DELETE_STATUS='NOT_DELETED' and  invoice_summary_id=".$table_id;
				$sql_grandmrp = $this->projectmodel->get_records_from_sql($sql_grandmrp);
				$grand_mrp=$sql_grandmrp[0]->grandmrp;
		
				//echo $grand_mrp=$grand_mrp+$total_mrp;
				//echo '<br>';
				//$grand_total=$grand_total+$row->taxable_amt+$row->cgst_amt+$row->sgst_amt;	
			?>		
					
						
						<?php if($gst_enable){?>
						<tr class="service">
							<td class="tableitem product_list">--</td>
							<td class="tableitem product_list" >..&nbsp;&nbsp;<?PHP echo $product_name; ?></td>
							<td class="tableitem product_list">&nbsp;</td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->mrp; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->srate; ?></td>
							<td class="tableitem product_list" align="center"><?PHP echo $row->tax_per; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->subtotal; ?></td>
						</tr>
						
						<?php }else{ ?>
						<tr class="service">
							<td class="tableitem product_list"></td>
							<td class="tableitem product_list" colspan="2">&nbsp;&nbsp;[<?PHP echo $product_name; ?>]</td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->mrp; ?></td>
							<td class="tableitem product_list" align="right"><?PHP echo $row->rate; ?></td>
							<td class="tableitem product_list">&nbsp;</td>
							<td class="tableitem product_list">&nbsp;</td>
						</tr>
						
						<?php }?>
						
					
			<?PHP }}} ?>	
				
				
								
				
							<?php /*?><tr style="background-color:#999999"><td class="tableitem" colspan="7">
							<span class="product_list"><?PHP echo 'Total No of Products :'.$total_qnty; ?></span>
							</td></tr><?php */?>
							
							<tr><td  colspan="7"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
							
							<tr >
							<td class="tableitem" colspan="2">
							<span class="product_list"><?PHP echo 'Total Qnty :'.$total_qnty; ?></span>
							</td>
							
							<td class="tableitem" colspan="3" align="left"><strong>Grand Total</strong></td>
								<td class="tableitem" colspan="2" align="right">
								<span class="style1"><?PHP echo $invoice_summary[0]->total_amt; ?> </span></td>
							</tr>
							<tr><td  colspan="7"><div style="border-top:solid;">&nbsp;</div></td></tr>
							
						<?php /*?>	<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">Total</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $total_amt; ?></span></td>
								
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">Discount</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $total_disc_amt; ?></span></td>
								
							</tr><?php */?>
							
							<?php if($gst_enable){?>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">Taxable Amt</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $total_taxable_amt; ?></span></td>
							</tr>
							
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">CGST Amt</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $total_cgst_amt; ?></span></td>
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">SGST Amt</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $total_sgst_amt; ?> </span></td>
							</tr>
							<?php }?>
							
							<?php /*?><tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2"><span class="style1">Grand Total</span></td>
								<td class="tableitem" colspan="3" align="right">
								<span class="style1"><?PHP echo $invoice_summary[0]->total_amt; ?> </span></td>
							</tr>
							<?php */?>
							
							<tr class="service">
								<td class="header_section" colspan="7" align="left">
								<span class="style1">[ Total MRP :<?php  echo '<strong>'.sprintf('%0.2f',$grand_mrp).'</strong>';?> ]</span></td>
							</tr>
							
							<tr class="service">
								<td class="header_section" colspan="7" align="left">
								[ Rs Payable :<?php echo 
								'<strong>'.strtoupper($this->numbertowords->convert_digit_to_words(intval($invoice_summary[0]->total_amt)).' Only').'</strong>';?> ]								</td>
							</tr>
							
							<?php if($invoice_summary[0]->status=='SALE'){?>						
							<tr class="service">
								<td class="header_section" colspan="7" align="left">
								<span class="style1">[ Your Savings Rs.<?php 
								
								$mrpsum="SELECT SUM( mrp * qnty ) totmrp 
								FROM invoice_details WHERE invoice_summary_id =".$table_id;					
								$mrpsum = $this->projectmodel->get_records_from_sql($mrpsum);
								$totmrp=$mrpsum[0]->totmrp;
								$totmrp=$totmrp-$invoice_summary[0]->total_amt;
								echo '<strong>'.sprintf('%0.2f',$totmrp).'</strong>';														
								/*echo '<strong>'.
								strtoupper($this->numbertowords->convert_digit_to_words(
								round($totmrp-$invoice_summary[0]->grandtot))).'</strong>';?> ]	*/
								?>]</span>
								</td>
							</tr>
							<?php }?>
							
							<tr class="service">
								<td class="product_list" colspan="7" align="center">
								Goods once sold are not returnable<br><br>
								***<?php echo $Bill_msg; ?>***
								<?php /*?><img src="<?php echo $billimg; ?>"/><?php */?>
								</td>
							</tr>
						</table>
	  </div><!--End Table-->
					
								
				<?php if($invoice_summary[0]->status=='SALE'){?>
					<!--<div id="legalcopy" style="margin-right:10px;">
						<p align="justify" class="legal"><?php //echo '<strong>Thank you for your business!</strong>'; ?>
						 Payment is expected within 31 days; please process this invoice within that time. There will be a 5% 
						 interest charge per month on late invoices.</p>
					</div>-->
				<?php }?>	

  </div><!--End InvoiceBot-->
</div><!--End Invoice-->



</body>

</html>
