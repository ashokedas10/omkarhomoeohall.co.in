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
		}
		
		$gst_enable=false;		
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;					
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
			
		
?>

<html>
<head></head>
<!--onafterprint="self.close()"-->
<body onLoad="window.print();" >


<div id="invoice-POS"  >

	
    
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
     	
	<div id="mid"  align="center" style="border:dotted;font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px">
		Slip For <?php echo $invoice_summary[0]->status; ?> 
	</div>
	<div id="mid" >
	
      <div class="info header_section" > 
           Slip No   :<?php echo $invoice_summary[0]->invoice_no.'.    Date: '.$invoice_summary[0]->invoice_date.' Time: '.$invoice_summary[0]->invoice_time; ?>
			</br> 
           By   :<?php echo $invoice_summary[0]->emp_name; ?>
      </div>
    </div><!--End Invoice Mid-->
	 
    <div id="bot">

		<div id="table">	 				
			<table style="width:100%" align="center">	 
					<tr><td  colspan="5"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
					<tr  >
						<td class="item product_list" > Srl </td>
						<td class="item product_list"> Item </td>
						<td class="Hours product_list" align="right" > MRP </td>
						<td class="item product_list" align="right"> Qty </td>
						<td class="Hours product_list" align="right" > Rack No </td>
						
					</tr>
					<tr><td  colspan="5"><div style="border-top:solid;">&nbsp;</div></td></tr>
				
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
													
				$sql="select * from invoice_details where  	invoice_summary_id=".$table_id." order by  id ";
				$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
				$i =0;
				if(count($rowrecord) > 0){
				foreach ($rowrecord as $row){ 
				$alt = ($i%2==0)?'alt1':'alt2';
				$i =$i +1;
				$stotal=0;		
				$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row->product_id);
				if($row->main_group_id==51) //PATENT
				{
					$product_name=$product_name;
					$pack_name='';
				}	
				
				$rackno=$TranPageName=$this->projectmodel->GetSingleVal('rack_name','rack_master','id='.$row->rackno);	
		
			?>		
						
			<tr class="service">
				<td class="tableitem product_list"><?PHP echo $i; ?></td>
				<td class="tableitem product_list" ><?PHP echo $product_name; ?></td>
				<td class="tableitem product_list" align="right"><?PHP echo $row->mrp; ?></td>
				<td class="tableitem product_list" align="right"><?PHP echo $row->qnty; ?></td>
				<td class="tableitem product_list" align="right"><?PHP echo $rackno; ?></td>
			</tr>
					
	 <?PHP }} ?>	
											
			



</body>

</html>
