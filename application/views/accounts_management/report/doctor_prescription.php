
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

.style2 {font-family:'Bookman Old Style'; font-weight:500;  font-size: 18px}

.product_list {font-family:'Bookman Old Style'; font-weight:500;  font-size: 12px}

.header_section {font-family:'Bookman Old Style'; font-weight:500;  font-size: 17px}
 
  
 /*font-weight:bold;*/
  
</style>

<?php
		
		$grand_total=0;
		$REPORT_NAME='DOCTOR_PRESCRIPTIONS';
		$data['fromdate']=$fromdate;
		$data['todate']=$todate;
		$data['ledger_ac']=$doctor_id;	
		
		$report_data =$this->accounts_model->all_mis_report($REPORT_NAME,$data);
		
?>

<html>
<head></head>
<!--onafterprint="self.close()"-->
<body  >


<div id="invoice-POS"  >
	
	<div id="mid">
      <div class="info" style="border:" >                 
          <img src="<?php //echo $image_path; ?>"  height="60"/>
		  <span style="font-size:22px; position:absolute; top:20px; left:90px;">
		  <?php /*?><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?><?php */?></span>
      </div>
    </div><!--End Invoice Mid-->
    
   
	
	
		
	
	<div id="mid"  align="center" style="border:dotted;font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px"></div>
  <div id="mid" ></div>
	<!--End Invoice Mid-->
		
    
    <div id="bot">
		<div id="table">					
			<table style="width:100%;border:dotted" align="center"  >					
				
				
				<tr>			
					<td  colspan="11" align="center" class="header_section">
					<?php
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',
					' id='.$doctor_id); 		
					echo 'Report For '.$acc_name.'<br> From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr><td  colspan="11"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
					
				<tr >			
						<td  class="item product_list">Token </td>	
						<td  class="item product_list">Status</td>		
						<td  class="item product_list" align="center">PID</td>	
						<td  class="item product_list">Patient Name</td>	
						<td  class="item product_list" align="right">Amount</td>	
				 </tr>			
				
					
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr>
					
					  <?php 
							
							$total=$tot_comm=$tot_payable=0;
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_sample=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//$total_purchase=$total_purchase+$trading_rs[$trading_cnt]['total_purchase'];
							//$total_sale=$total_sale+$trading_rs[$trading_cnt]['total_sale'];
							$total=$total+$trading_rs[$trading_cnt]['ACTUAL_VISIT_AMT'];
							$tot_comm=$tot_comm+$trading_rs[$trading_cnt]['comm_amt'];	
						?>	
						
						<tr >	
														 
						 <td class="item product_list" >
						 <?php echo $trading_rs[$trading_cnt]['token_no'] ?></td>
						 
						 <td class="item product_list" >
						 <?php echo $trading_rs[$trading_cnt]['token_status'] ?></td>
						 
						 <td class="item product_list" align="center">
						 <?php echo $trading_rs[$trading_cnt]['patient_registration_id'] ?>
						 </td>
						 
						 <td class="item product_list" ><?php echo $trading_rs[$trading_cnt]['pname'] ?></td>
						 <td class="item product_list" align="right" ><?php 
						 echo $trading_rs[$trading_cnt]['ACTUAL_VISIT_AMT'] ?></td>
						
						</tr>
						
							
						<?php }} ?>
						
				<tr><td  colspan="5"><div style="border-top:solid;">&nbsp;</div></td></tr>		
				<tr  >
					 <td width="40" colspan="4"  class="header_section">Total</td>
					 <td width="40" align="right" class="header_section"><?php echo $total; ?></td>	
					 </tr>	
					 
					 <tr  >
					 <td width="40" colspan="4"  class="header_section">Total Commission</td>
					 <td width="40" align="right" class="header_section"><?php echo $tot_comm; ?></td>	
					 </tr>	
					 
					 <tr><td  colspan="11"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
					  <tr >
					 <td width="40" colspan="4"  class="header_section">Total Payable</td>
					 <td width="40" align="right" class="header_section"><?php echo $total-$tot_comm; ?></td>	
					</tr>
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr>
							
		  </table>
	  </div><!--End Table-->
					
								
			
  </div><!--End InvoiceBot-->
</div><!--End Invoice-->



</div>

</body>

</html>
