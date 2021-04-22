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

.header_section {font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px}
 
  
 /*font-weight:bold;*/
  
</style>

<?php
		
		$grand_total=0;
		$REPORT_NAME='DOCTOR_COMMISSION_SUMMARY';
		$data['fromdate']=$fromdate;
		$data['todate']=$todate;
		$data['ledger_ac']=$doctor_id;	
		
		$report_data =$this->accounts_model->all_mis_report($REPORT_NAME,$data);
		
		//print_r($report_data);
		
		
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
					
					<?php /*?><tr>			
						<td  class="item product_list">Date</td>	
						<td  class="item product_list">Inv</td>		
						<td  class="item product_list">Tot</td>				
						<?php 
							
							if(!empty($report_data[0]['groupname'])){	
													
							$group_cnt=sizeof($report_data[0]['groupname']); 
							if($group_cnt>0){  
							for($cnt=0;$cnt<$group_cnt;$cnt++)
							{			
								
						?>		
						<td  align="left" colspan="2" class="item product_list">&nbsp;</td>
						
						<?php }}} ?>
								
					</tr><?php */?>
					
					<tr >			
						<td  class="item product_list">Date</td>	
						<td  class="item product_list">Inv</td>		
						<td  class="item product_list">Tot</td>			
						
						<?php 							
							//print_r($report_data);
							
							if(!empty($report_data[0]['groupname'])){							
							$group_cnt=sizeof($report_data[0]['groupname']); 
							if($group_cnt>0){  
							for($cnt=0;$cnt<$group_cnt;$cnt++)
							{			
								
						?>		
						<td   align="right" class="item product_list" >
						<?php echo $report_data[0]['shortname'][$cnt]; ?>
						<?php /*?><?php echo $report_data[0]['groupname'][$cnt]; ?><?php */?></td>
						<?php }}} ?>
						<td  align="right" class="item product_list">Amount</td>	
				   </tr>
					
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr>
					
					  <?php 
							
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//echo $trading_rs[$trading_cnt]['href'];			
						?>	
						
						<tr >	
							 <td class="item product_list">
							 <?php $invoice_date= $report_data[$trading_cnt]['invoice_date'];
							 echo substr($invoice_date,8,2).'/'.substr($invoice_date,5,2);	
							  ?></td>
							 
							 <td class="item product_list">
							 <?php 
							 //echo $report_data[$trading_cnt]['invoice_no'];
							 
							 $rslt= (explode("/",$report_data[$trading_cnt]['invoice_no']));
							 echo $rslt[0];
							 
							 ?></td>
							 							
							 <td class="item product_list"  align="right">
							 <?php echo round($report_data[$trading_cnt]['total_trade_value']); ?></td>
							 
							 <?php 
							 
								$row_comm_total=0;				
								$group_cnt=sizeof($report_data[$trading_cnt]['groupname']); 
								if($group_cnt>0){  
								for($cnt=0;$cnt<$group_cnt;$cnt++)
								{		
							?>							
							<td class="item product_list" align="right" >
							<?php echo $report_data[$trading_cnt]['group_commission_amt'][$cnt]; ?>
							</td>
							
						<?php }} ?>
							<td align="right" class="item product_list"><?php 
							$grand_total=$grand_total+$report_data[$trading_cnt]['row_comm_total'];
							echo $report_data[$trading_cnt]['row_comm_total'] ?></td>	 
						 </tr>	
				<?php }} ?>
					
					
					
						 
						 
					
					<!--<tr  >
						<td class="item product_list" > Srl </td>
						<td class="item product_list"> Item </td>
						<td class="Hours product_list" align="right" > MRP </td>
						<td class="item product_list" align="right"> Qty </td>
						<td class="Hours product_list" align="right" > Rate </td>
						<td class="Rate product_list" align="right" > Tax% </td>
						<td class="Rate product_list" align="right" > Total </td>
					</tr>			-->		
					
					<tr><td  colspan="11"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
					<tr >	
						     <td colspan="9">&nbsp;</td>
							 <td class="header_section">Total</td>						
							<td  align="right" class="header_section"><?php echo $grand_total; ?></td>	 
					 </tr>						
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr>
					
							
		  </table>
	  </div><!--End Table-->
					
								
			
  </div><!--End InvoiceBot-->
</div><!--End Invoice-->



</div>

</body>

</html>
