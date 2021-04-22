<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
function getlink(href) 
{
	//alert(href);	
	if(href) {window.location = href;}			
}

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
</script>
<style>

table#example {
    border-collapse: collapse;   
}
#example tr {
    background-color: #eee;
    border-top: 1px solid #fff;
}
#example tr:hover {
    background-color: #ccc;
}
#example th {
    background-color: #fff;
}
#example th, #example td {
    padding: 3px 5px;
}
#example td:hover {
    cursor: pointer;
}

a {
  color:#000000;
  text-decoration: none;
}
.style1 {
	color: #FFFFFF;
	font-size: 14px;
	font-weight: bold;
}
</style>

<div class="panel panel-primary" >
	
	  <div class="panel-body" align="center" style="background-color:#99CC00">
		<h3><span class="label label-default"><?php echo $REPORT_NAME;?></span>
		<span class="label label-default">
		<?php 
		if($this->session->userdata('alert_msg')<>''){
		echo '<br><br><br>'.$this->session->userdata('alert_msg');
		}
		 ?>
		</span></h3>
	  </div>
</div>
  
<!--REPORT PARAMETER SECTION-->
 
<?php echo $report_parameter;?>

 <!--REPORT PARAMETER SECTION END-->
  
<div id="printablediv"  style="overflow:auto" >
		
		
		<?php if($REPORT_NAME=='PURCHASE_REGISTER' && $display_report=='YES'){
			if(sizeof($report_data)>0){			
			 ?>
			
			
			<table   class="table table-bordered table-striped" id="example">
				<tr >
				<?php foreach($report_data['header'][0] as $key=>$value){ if($key<>'id'){?>	
				<td  class="bg-primary" <?php if($key=='Bill Amt' || $key=='Discount Rcv' || $key=='Tax' || $key=='Grand'){ ?>align="right" <?php } ?>> 
				<?php echo $key; ?></td><?php }} ?>	
				 </tr>
				 
				<?php 
				
				$total_amt=0;
				$tot_discount=0;
				$totvatamt=0;
				$grandtot=0;
				
				
				foreach($report_data['header'] as $key=>$array_record){  ?>	
				<tr>
				<?php 
				$total_amt=$total_amt+$array_record['Bill Amt'];
				$tot_discount=$tot_discount+$array_record['Discount Rcv'];
				$totvatamt=$totvatamt+$array_record['Tax'];
				$grandtot=$grandtot+$array_record['Grand'];
				
				foreach($array_record as $key2=>$values)
				{
					if($key2<>'id'){
				?>				
				<td <?php if($key2=='Bill Amt' || $key2=='Discount Rcv' || $key2=='Tax' || $key2=='Grand'){ ?>align="right" <?php } ?> > <?php echo $values; ?></td>
				<?php }} ?>	
				 </tr>	
				<?php } ?>	
				
				<tr  style="background-color:#FF3333">
					<td colspan="3" class="style1">Total </td>
					<td  align="right" class="style1"><?php echo $total_amt; ?></td>
					<td align="right" class="style1"><?php echo $tot_discount; ?> </td>
					<td align="right" class="style1"><?php echo $totvatamt; ?> </td>
					<td align="right" class="style1"><?php echo $grandtot; ?> </td>
				</tr>
								
				</table>
			<?php }else{ ?>
			
			<table   class="table table-bordered table-striped" id="example">
				<tr>
					<td  class="bg-primary" align="center">No data Found</td>
				 </tr>	
			</table>
			
			<?php }} ?>
		
			
			<?php if($REPORT_NAME=='SALE_REGISTER' && $display_report=='YES'){
			if(sizeof($report_data)>0){			
			 ?>
			 
			<table   class="table table-bordered table-striped" id="example">
				<tr >
				<?php foreach($report_data['header'][0] as $key=>$value){ if($key<>'id'){?>	
				<td  class="bg-primary" <?php if($key=='Bill Amt' || $key=='Discount Rcv' || $key=='Tax' || $key=='Grand'){ ?>align="right" <?php } ?>> 
				<?php echo $key; ?></td><?php }} ?>	
				 </tr>
				 
				<?php 
				
				$total_amt=0;
				$tot_discount=0;
				$totvatamt=0;
				$grandtot=0;
				
				
				foreach($report_data['header'] as $key=>$array_record){  ?>	
				<tr>
				<?php 
				$total_amt=$total_amt+floatval($array_record['Bill Amt']);
				/*$tot_discount=$tot_discount+$array_record['Discount Rcv'];
				$totvatamt=$totvatamt+$array_record['Tax'];
				$grandtot=$grandtot+$array_record['Grand'];*/
				
				foreach($array_record as $key2=>$values)
				{
					if($key2<>'id'){
				?>				
				<td <?php if($key2=='Bill Amt' || $key2=='Discount Rcv' || $key2=='Tax' || $key2=='Grand'){ ?>align="right" <?php } ?> > <?php echo $values; ?></td>
				<?php }} ?>	
				 </tr>	
				<?php } ?>	
				
				<tr  style="background-color:#FF3333">
					<td colspan="6" class="style1">Total </td>
					<td  align="right" class="style1"><?php echo $total_amt; ?></td>
					<?php /*?><td align="right" class="style1"><?php echo $tot_discount; ?> </td>
					<td align="right" class="style1"><?php echo $totvatamt; ?> </td>
					<td align="right" class="style1"><?php echo $grandtot; ?> </td><?php */?>
				</tr>
								
				</table>
			<?php }else{ ?>
			
			<table   class="table table-bordered table-striped" id="example">
				<tr>
					<td  class="bg-primary" align="center">No data Found</td>
				 </tr>	
			</table>
			
			<?php }} ?>
		
		
		
		
		
		
		
		
		<?php /*?><?php if($REPORT_NAME=='PRODUCT_GROUP_WISE_LISTING' && $display_report=='YES'){ 
			$trading_rs=$report_data;
			//print_r($trading_rs);
			
			?>
			
			<table   class="table table-bordered table-striped" id="example">
				<tr >
					<?php foreach($report_data[0] as $key=>$value){?>	
					<td  class="bg-primary"> <?php echo $key; ?></td><?php } ?>	
				 </tr>
				 
				<?php foreach($report_data as $key=>$array_record){?>	
				<tr>
							
				<?php foreach($array_record as $key2=>$values){?>				
				<td> <?php echo $values; ?></td>
				<?php } ?>	
				
				 </tr>	
				<?php } ?>						
				</table>
			<?php }else{ ?>
			
			<table   class="table table-bordered table-striped" id="example">
				<tr >
					<td  class="bg-primary" align="center">No data Found</td>
				 </tr>	
			</table>
			
			
			
			
			<?php } ?><?php */?>
		
		
		
		
		
		<?php if($REPORT_NAME=='DOCTOR_COMMISSION_SUMMARY' && $display_report=='YES'){ 
			$trading_rs=$report_data;
			
			$grand_total=0;
			//print_r($trading_rs);
			
			?>
				<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="7" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',
					' id='.$ledger_ac); 		
					echo 'Ledger Transaction of '.$acc_name.' From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Invoice No</td>		
					<td  align="left">Total Amount</td>				
					<?php 
													
						$group_cnt=sizeof($report_data[0]['groupname']); 
						if($group_cnt>0){  
						for($cnt=0;$cnt<$group_cnt;$cnt++)
						{			
							
					?>		
					<td  align="left" colspan="2" >&nbsp;</td>
					
					<?php }} ?>
					
				</tr>
				
				
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left" colspan="3">-</td>	
					
					<?php 
													
						$group_cnt=sizeof($report_data[0]['groupname']); 
						if($group_cnt>0){  
						for($cnt=0;$cnt<$group_cnt;$cnt++)
						{			
							
					?>		
					<td  align="left" ><?php echo $report_data[0]['groupname'][$cnt]; ?></td>
					<?php /*?><td  align="left" ><?php echo $report_data[0]['group_commission'][$cnt]; ?>%</td><?php */?>
					<?php }} ?>
					<td  align="left" >Tot Comm</td>	
				</tr>
				
				<?php /*?><tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left" colspan="3">&nbsp;</td>	
					
					<?php 
													
						$group_cnt=sizeof($report_data[0]['groupname']); 
						if($group_cnt>0){  
						for($cnt=0;$cnt<$group_cnt;$cnt++)
						{			
							
					?>		
					<td  align="left" >Tot</td>
					<!--<td  align="left" >Comm</td>-->
					<?php }} ?>
					<td  align="left" >Tot Comm</td>	
					
				</tr><?php */?>
				
			 				
					  <?php 
							
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//echo $trading_rs[$trading_cnt]['href'];			
						?>	
						<tr >	
							 <td><?php echo $report_data[$trading_cnt]['invoice_date'] ?></td>
							 <td><?php echo $report_data[$trading_cnt]['invoice_no'] ?></td>							
							 <td><?php echo $report_data[$trading_cnt]['total_trade_value'] ?></td>
							 <?php 
							 
								$row_comm_total=0;				
								$group_cnt=sizeof($report_data[$trading_cnt]['groupname']); 
								if($group_cnt>0){  
								for($cnt=0;$cnt<$group_cnt;$cnt++)
								{		
							?>		
							<?php /*?><td  align="left" >
							<?php echo $report_data[$trading_cnt]['group_wise_trade_val'][$cnt]; ?>
							</td><?php */?>
							
							<?php /*?><td  align="left" >
							<?php echo $report_data[$trading_cnt]['group_commission'][$cnt]; ?>
							</td><?php */?>
														
							<td  align="right" >
							<?php echo $report_data[$trading_cnt]['group_commission_amt'][$cnt]; ?>
							</td>
							
						<?php }} ?>
							<td align="right"><?php 
							$grand_total=$grand_total+$report_data[$trading_cnt]['row_comm_total'];
							echo $report_data[$trading_cnt]['row_comm_total'] ?></td>	 
						 </tr>	
				<?php 	
				
				}} ?>
				
	
				<?php /*?><tr >	
							 <td colspan="2"> &nbsp;</td>
							 <td><?php echo $report_data[$trading_cnt+1]['total_trade_value'] ?></td>
							<td><?php echo $report_data[$trading_cnt+1]['row_comm_total'] ?></td>	 
				</tr>	<?php */?>
	
				
				
						<tr >	
						     <td colspan="9">&nbsp;</td>
							 <td><strong>Total</strong></td>						
							<td  align="right"><strong><?php echo $grand_total; ?></strong></td>	 
						 </tr>	
				
				
				
				</table>
			<?php } ?>
		
		
		
		
		
			<?php if($REPORT_NAME=='GENERAL_LEDGER'){ ?>
			<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="7" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$ledger_ac); 		
					echo 'Ledger Transaction of '.$acc_name.' From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Particular</td>	
					<td  align="left">Vch Type</td>
					<td  align="left">Vch/Invoice No</td>
					<td  align="right">Debit</td>	
					<td  align="right">Credit</td>				
					<td  align="right">Balance</td> 
				</tr>
			 				
					  <?php 
							$credit_cumulative_balance=$debit_cumulative_balance=
							$tot_dr_balance=$tot_cr_balance=0;
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//echo $trading_rs[$trading_cnt]['href'];			
						?>	
						<tr >						 
							<td  ><a href="<?php echo $report_data[$trading_cnt]['href'];  ?>">
							<?php echo $report_data[$trading_cnt]['tran_date'] ?></a></td>
							 <td><?php echo $report_data[$trading_cnt]['particular'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_type'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_code'] ?></td>	
							 <td align="right"><?php echo $report_data[$trading_cnt]['credit_amount'] ?></td>			
							 <td align="right"><?php echo $report_data[$trading_cnt]['debit_amount'] ?></td>			
							 <td align="right">
							 <?php 
				
								$debit_cumulative_balance=$debit_cumulative_balance+$report_data[$trading_cnt]['credit_amount'];
								$credit_cumulative_balance=$credit_cumulative_balance+$report_data[$trading_cnt]['debit_amount'];
								
								if($debit_cumulative_balance>=$credit_cumulative_balance)
								{ $bal=$debit_cumulative_balance-$credit_cumulative_balance; echo $bal.'Dr';}
								else
								{$bal=$credit_cumulative_balance-$debit_cumulative_balance; echo $bal.'Cr';}
				
								$tot_dr_balance=$tot_dr_balance+$report_data[$trading_cnt]['credit_amount'];
								$tot_cr_balance=$tot_cr_balance+$report_data[$trading_cnt]['debit_amount'];
								
							?></td>  		 
						 </tr>	
				<?php }} ?>
				<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="4"><strong>Total</strong></td>
	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_dr_balance;?></strong> </td>
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_cr_balance;?></strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong>
	<?php 
	$drbalance=$crbalance=0;
	if($tot_dr_balance>=$tot_cr_balance)
	{ $drbalance=$tot_dr_balance-$tot_cr_balance; echo round($drbalance,2).' DR';}
	else
	{  $crbalance=$tot_cr_balance-$tot_dr_balance;echo round($crbalance,2).' CR'; }
	
	?>
	</strong></td>			
	</tr>
				
				</table>
			<?php } ?>	
			<!--TRADE PRODUCT SECTIONS-->			
			<?php if($REPORT_NAME=='PRODUCT_GROUP'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<input type="text" placeholder="Search {{test_pack}}" ng-model="searchtext" class="form-control"/>
			
								 
				<tr ><td width="40"  colspan="4" style="background-color:#999999"><?php //echo $rs[$group1]['site_name']; ?></td></tr>
				<tr  style="background-color:#999999">
				 <td width="40" >Product Name</td>
				 <!--<td width="40" >Qnty Available</td>-->
				<!-- <td width="40" >Rate</td>
				 <td width="40" >Total</td>		-->	 
				 </tr>			
			 				
					  <?php 
					  		$tranlink=ADMIN_BASE_URL.'Accounts_controller/all_mis_reports/';
							
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{
							$href=$tranlink.'PRODUCT_BATCH/'.$trading_rs[$trading_cnt]['id'];	
							//if($trading_rs[$trading_cnt]['available_qnty']<>0){
						?>	
						<tr bgcolor="#999999">						 
							<td width="40" ><a href="<?php echo $href;  ?>">
							<?php echo $trading_rs[$trading_cnt]['productname'] ?></a></td>
							 <?php /*?><td width="40" ><?php echo $trading_rs[$trading_cnt]['available_qnty'] ?></td><?php */?>
							 <?php /*?><td width="40" ><?php //echo $trading_rs[$trading_cnt]['rate'] ?></td>
							 <td width="40" ><?php //echo $trading_rs[$trading_cnt]['total'] ?></td>		<?php */?>	 
						 </tr>		
						
				<?php }}//} ?>
				
				</table>
			<?php } ?>
			
			<?php if($REPORT_NAME=='PRODUCT_BATCH'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="9" style="background-color:#999999"><?php //echo $rs[$group1]['site_name']; ?></td></tr>
				<tr  style="background-color:#999999">
				 <td width="40" >Batch No</td>
				 <td width="40" >Mfg Date</td>
				 <td width="40" >Exp date</td>
				 <td width="40"  align="right">Purchase Qnty</td>	
				 <td width="40" align="right">Sale Qnty</td>
				 <td width="40" align="right">Sale Rtn</td>
				<!-- <td width="40" align="right">Sample Issue</td>-->
				 <td width="40" align="right">Availavle Qnty</td>
				 <td width="40" align="right">Rate</td>
				 <td width="40" align="right">Total</td>			 
				 </tr>			
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_sample=$TOTAL_SELL_RTN=$total_purchase=$total_sale=$total_available_qnty=$total_amt=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							$total_purchase=$total_purchase+$trading_rs[$trading_cnt]['total_purchase'];
							$total_sale=$total_sale+$trading_rs[$trading_cnt]['total_sale'];
							$total_available_qnty=$total_available_qnty+$trading_rs[$trading_cnt]['total_available_qnty'];
							$total_amt=$total_amt+$trading_rs[$trading_cnt]['total_amt'];	
							$tot_sample=$tot_sample+$trading_rs[$trading_cnt]['tot_sample'];	
							$TOTAL_SELL_RTN=$TOTAL_SELL_RTN+$trading_rs[$trading_cnt]['TOTAL_SELL_RTN'];	
						?>	
							<tr >
							 <td width="40" >
							 <a href="<?php echo $trading_rs[$trading_cnt]['href'];  ?>">
							 <?php echo $trading_rs[$trading_cnt]['batchno'] ?></a></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['mfg_monyr'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['exp_monyr'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['total_purchase'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['total_sale'] ?></td>
							  <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['TOTAL_SELL_RTN'] ?></td>
							  <?php /*?> <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['tot_sample'] ?></td><?php */?>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['total_available_qnty'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['rate'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['total_amt'] ?></td>			 
							 </tr>	
					<?php }} ?>
							<tr  style="background-color:#CC6633">
							 <td width="40" colspan="3"  style="background-color:#CC6633">Total</td>
							 <td width="40" align="right"><?php echo $total_purchase; ?></td>	
							 <td width="40" align="right"><?php echo $total_sale; ?></td>
							 <td width="40" align="right"><?php echo $TOTAL_SELL_RTN; ?></td>
							<?php /*?> <td width="40" align="right"><?php echo $tot_sample; ?></td><?php */?>
							 <td width="40" align="right"><?php echo $total_available_qnty; ?></td>
							 <td width="40" >&nbsp;</td>
							 <td width="40" align="right"><?php echo $total_amt; ?></td>			 
							 </tr>	
				</table>
			<?php } ?>
			
			<?php if($REPORT_NAME=='PRODUCT_BATCH_TRANSACTIONS'){ ?>
				<div class="panel-body" > 					
					<div  class="row" style="background-color:#CCCC66">						
						<div class="col-md-2"  align="left">Invoice No</div>
						<div class="col-md-2"  align="left">Invoice Date</div>
						<div class="col-md-2"  align="left">Party</div>
						<div class="col-md-2"  align="right">Status</div>
						<div class="col-md-2"  align="right">Qnty</div>
						<div class="col-md-2"  align="right">Balance Qnty</div>
					</div>
					
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$total_purchase=$total_sale=$total_available_qnty=$total_amt=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
					 ?>	
					<div class="row"  
					style="background:white" onmouseover="this.style.background='gray';" onmouseout="this.style.background='white';">
					
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['invoice_no']; ?></div> 
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['invoice_date']; ?></div> 
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['party_name']; ?></div> 							
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['status'] ?></div> 
							<div class="col-md-2"  align="right" ><?php echo $trading_rs[$trading_cnt]['qnty']; ?></div> 
							<div class="col-md-2"  align="right" ><?php echo $trading_rs[$trading_cnt]['balance'] ?></div> 
							
					</div>
					<?php }} ?>
					
			</div>			
			<?php } ?>
									
			<!-- PRODUCT ALL TRANSACTION SECTIONS-->
			<?php if($REPORT_NAME=='PRODUCT_TRANSACTIONS'){ ?>			
				<div class="panel-body" > 					
					<div  class="row" style="background-color:#CCCC66">						
						<div class="col-md-2"  align="left">Invoice No</div>
						<div class="col-md-2"  align="left">Invoice Date</div>
						<div class="col-md-2"  align="left">Party</div>
						<div class="col-md-2"  align="right">Status</div>
						<div class="col-md-2"  align="right">Qnty</div>
						
					</div>
					
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$total_purchase=$total_sale=$total_available_qnty=$total_amt=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
					 ?>	
					<div class="row"  
					style="background:white" onmouseover="this.style.background='gray';" onmouseout="this.style.background='white';">
					
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['invoice_no']; ?></div> 
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['invoice_date']; ?></div> 
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['party_name']; ?></div> 							
							<div class="col-md-2"  align="left" ><?php echo $trading_rs[$trading_cnt]['status'] ?></div> 
							<div class="col-md-2"  align="right" ><?php echo $trading_rs[$trading_cnt]['qnty']; ?></div> 
					</div>
					<?php }} ?>
					
			</div>			
			<?php } ?>		
						
			<!-- GST REPORT SECTIONS-->			
			<?php if($REPORT_NAME=='BILL_WISE_SALE'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="26" style="background-color:#FFFF33" align="center">
			<?php echo 'Sale Report From '. $fromdate.' To '.$todate; ?></td></tr>
				<tr  style="background-color:#CCFFFF">
				 <td width="40" >Date</td>
				 <td width="40" >Tax Invoice No</td>
				 <td width="40" >Customer Name</td>				
				<!-- <td width="40" >Place of Supply</td>-->
				 <td width="40" >GSTIN </td>
				 <td width="40" align="center" colspan="5">GST 0 %</td>
				 <td width="40" align="center" colspan="5">GST 5 %</td>
				 <td width="40" align="center" colspan="5">GST 12 %</td>
				 <td width="40" align="center" colspan="5">GST 18 %</td>
				<!-- <td width="40" align="right">Free Goods</td>	
				 <td width="40" align="right">INTEREST</td>
				 <td width="40" align="right">Delivery Charge</td>
				 <td width="40" align="right">Cash Discount</td>
				 <td width="40" align="right">R/OFF</td>-->
				<!-- <td width="40" align="right">Bill Amount</td>		--> 
				 </tr>	
				 
				 <tr  style="background-color:#999999">
				 
				 <td width="40" colspan="4">--</td>
				 
				  <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>		
				 	
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>		
					
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>	
				
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>	  			
				<!--<td width="40" colspan="6">--</td>-->
				 </tr>					
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$grand_total=$taxable_amt_0=$taxable_amt_5=$taxable_amt_12=$taxable_amt_18=0;
							
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$taxable_amt_0=$taxable_amt_0+$trading_rs[$trading_cnt]['taxable_amt_0'];
							$taxable_amt_5=$taxable_amt_5+$trading_rs[$trading_cnt]['taxable_amt_5'];
							$taxable_amt_12=$taxable_amt_12+$trading_rs[$trading_cnt]['taxable_amt_12'];
							$taxable_amt_18=$taxable_amt_18+$trading_rs[$trading_cnt]['taxable_amt_18'];
							$grand_total=$grand_total+$trading_rs[$trading_cnt]['grand_total'];
						?>	
							<tr>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['invoice_date'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['invoice_no'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['party_name'] ?></td>	
							<?php /*?> 
							<td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['destination'] ?>
							</td>	
							<?php */?>	
											
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['GSTNO'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_0'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_0'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_0'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_0'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_0'] ?></td>
							 
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_5'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_5'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_5'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_5'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_5'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_12'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_12'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_12'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_12'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_12'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_18'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_18'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_18'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_18'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_18'] ?></td>
							<?php /*?> 
							<td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['grand_total'] ?>
							</td>	
							<?php */?>
							 </tr>
							 	
						<?php }} ?>
							<tr  style="background-color:#CC6633">
							 <td width="40" colspan="4"  style="background-color:#CC6633">Total</td>	
							 <td width="40" align="right"><?php echo $taxable_amt_0; ?></td>
							 
							 <td width="40" align="right" colspan="4">&nbsp;</td>					
							 <td width="40" align="right"><?php echo $taxable_amt_5; ?></td>
							 
							 <td width="40" align="right" colspan="4">&nbsp;</td>
							 <td width="40" align="right"><?php echo $taxable_amt_12; ?></td>
							  
							  <td width="40" align="right" colspan="4">&nbsp;</td>
							  <td width="40" align="right"><?php echo $taxable_amt_18; ?></td>
							  
							  <td width="40" align="right" colspan="4">--</td>
							<?php /*?> <td width="40" align="right"><?php echo $grand_total; ?></td>	<?php */?>		 
							 </tr>	
				</table>
			<?php } ?>	
			
			<?php if($REPORT_NAME=='BILL_WISE_PURCHASE'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="21" style="background-color:#FFFF33" align="center">
			<?php echo 'Purchase Report From '. $fromdate.' To '.$todate; ?></td></tr>
				<tr  style="background-color:#CCFFFF">
				 <td width="40" >Date</td>
				 <td width="40" >Tax Invoice No</td>
				 <td width="40" >Supplier Name</td>				
				 <td width="40" >Address</td>
				 <td width="40" >GSTIN </td>
				 <td width="40" align="center" colspan="5">GST 5 %</td>
				 <td width="40" align="center" colspan="5">GST 12 %</td>
				 <td width="40" align="center" colspan="5">GST 18 %</td>
				 <td width="40" align="right">Bill Amount</td>		 
				 </tr>	
				 
				 <tr  style="background-color:#999999">
				 
				 <td width="40" colspan="5">--</td>
				 	
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>		
					
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>	
				
				 <td width="40" align="right" >Taxable Amount</td>	
				 <td width="40" align="right" >CGST</td>	
				 <td width="40" align="right" >SGST</td>	
				 <td width="40" align="right" >IGST</td>	
				 <td width="40" align="right" >AMOUNT WITH TAX</td>	  			
				<td width="40" colspan="6">--</td>
				 </tr>					
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_sample=$tot_sample_issue=$total_purchase=$total_sale=$total_available_qnty=$total_amt=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							
						?>	
							<tr>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['invoice_date'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['invoice_no'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['party_name'] ?></td>	
							 <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['destination'] ?></td>						
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['GSTNO'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_5'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_5'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_5'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_5'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_5'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_12'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_12'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_12'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_12'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_12'] ?></td>
							 
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt_18'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['CGST_18'] ?></td>	
							 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['SGST_18'] ?></td>			
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['IGST_18'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['amount_with_tax_18'] ?></td>
							<?php /*?> 
							 <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['freegoods'] ?></td>			
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['interest_charge'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['delivery_charge'] ?></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['cash_discount'] ?></td>	
							 <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['round_off'] ?></td>			<?php */?>
							 <td width="40"align="right" ><?php echo $trading_rs[$trading_cnt]['grand_total'] ?></td>	
							 </tr>	
					<?php }} ?>
							<?php /*?><tr  style="background-color:#CC6633">
							 <td width="40" colspan="3"  style="background-color:#CC6633">Total</td>							
							 <td width="40" align="right"><?php echo $tot_sample; ?></td>
							 <td width="40" align="right"><?php echo $tot_sample_issue; ?></td>
							 <td width="40" align="right"><?php echo $total_available_qnty; ?></td>
							 <td width="40" >&nbsp;</td>
							 <td width="40" align="right"><?php echo $total_amt; ?></td>			 
							 </tr>	<?php */?>
				</table>
			<?php } ?>	
			
			<?php if($REPORT_NAME=='HSN_WISE_SALE'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="21" style="background-color:#FFFF33" align="center">
			<?php echo 'HSN Wise Sale Report From '. $fromdate.' To '.$todate; ?></td></tr>
				<tr  style="background-color:#CCFFFF">
				 <td width="40" >HSN Code</td>
				 <td width="40" >Description</td>
				 <td width="40" >UQC</td>				
				 <td width="40" align="right">Total Quantity</td>
				 <td width="40" align="right">Total Value </td>
				 <td width="40" align="right" >Taxable Value</td>
				 <td width="40" align="right" >Integrated Tax Amount</td>
				 <td width="40" align="right" >Central Tax Amount</td>
				 <td width="40" align="right">State/UT Tax Amount</td>		 
				 </tr>	
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_qnty=$tot_value=$taxable_amt=$igst_amt=$cgst_amt=$sgst_amt=0;
							if($trading_cnt_total>0){
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$tot_qnty=$tot_qnty+$trading_rs[$trading_cnt]['tot_qnty'];
							$tot_value=$tot_value+$trading_rs[$trading_cnt]['tot_value'];
							$taxable_amt=$taxable_amt+$trading_rs[$trading_cnt]['taxable_amt'];
							$igst_amt=$igst_amt+$trading_rs[$trading_cnt]['igst_amt'];
							$cgst_amt=$cgst_amt+$trading_rs[$trading_cnt]['cgst_amt'];
							$sgst_amt=$sgst_amt+$trading_rs[$trading_cnt]['sgst_amt'];
						?>	
							<tr>
								 <td width="40" ><?php echo $trading_rs[$trading_cnt]['hsn_code'] ?></td>
								 <td width="40" ><?php echo $trading_rs[$trading_cnt]['hsn_desc'] ?></td>
								 <td width="40" ><?php echo $trading_rs[$trading_cnt]['uqc'] ?></td>	
								 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['tot_qnty'] ?></td>						
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['tot_value'] ?></td>							 
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt'] ?></td>
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['igst_amt'] ?></td>	
								 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['cgst_amt'] ?></td>			
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['sgst_amt'] ?></td>
								 
							 </tr>	
					<?php }} ?>
					
							<tr style="background-color:#FFFF33">
								 <td width="40" colspan="3" >Total</td>
								 <td width="40" align="right" ><?php echo $tot_qnty ?></td>						
								 <td width="40" align="right"><?php echo $tot_value ?></td>							 
								 <td width="40" align="right"><?php echo $taxable_amt ?></td>
								 <td width="40" align="right"><?php echo $igst_amt ?></td>	
								 <td width="40" align="right" ><?php echo $cgst_amt ?></td>			
								 <td width="40" align="right"><?php echo $sgst_amt ?></td>
							 </tr>	
							
				</table>
			<?php } ?>	
			
			<?php if($REPORT_NAME=='HSN_WISE_SUMMARY'){ ?>
			
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="21" style="background-color:#FFFF33" align="center">
			<?php echo 'HSN Wise Sale Report From '. $fromdate.' To '.$todate; ?></td></tr>
				<tr  style="background-color:#CCFFFF">
				 <td width="40" >HSN Code</td>	
				 <td width="40" >Product Name</td>
				 <td width="40" >GST %</td>			 		
				 <td width="40" align="right">Total Quantity</td>				 
				 <td width="40" align="right" >Taxable Value</td>
				 <td width="40" align="right" >Integrated Tax Amount</td>
				 <td width="40" align="right" >Central Tax Amount</td>
				 <td width="40" align="right">State/UT Tax Amount</td>	
				 <td width="40" align="right">Gross Total </td>	 
				 </tr>	
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_qnty=$tot_value=$taxable_amt=$igst_amt=$cgst_amt=$sgst_amt=0;
							if($trading_cnt_total>0){
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$tot_qnty=$tot_qnty+$trading_rs[$trading_cnt]['tot_qnty'];
							$tot_value=$tot_value+$trading_rs[$trading_cnt]['tot_value'];
							$taxable_amt=$taxable_amt+$trading_rs[$trading_cnt]['taxable_amt'];
							$igst_amt=$igst_amt+$trading_rs[$trading_cnt]['igst_amt'];
							$cgst_amt=$cgst_amt+$trading_rs[$trading_cnt]['cgst_amt'];
							$sgst_amt=$sgst_amt+$trading_rs[$trading_cnt]['sgst_amt'];
						?>	
							<tr>
								 <td width="40" ><?php echo $trading_rs[$trading_cnt]['hsn_code'] ?></td>
								  <td width="40" ><?php echo $trading_rs[$trading_cnt]['productname'] ?></td>
								 <td width="40" ><?php echo $trading_rs[$trading_cnt]['gst_per'] ?></td>									
								 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['tot_qnty'] ?></td>	
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['taxable_amt'] ?></td>
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['igst_amt'] ?></td>	
								 <td width="40" align="right" ><?php echo $trading_rs[$trading_cnt]['cgst_amt'] ?></td>			
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['sgst_amt'] ?></td>
								 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['tot_value'] ?></td>	
								 
							 </tr>	
					<?php }} ?>
					
							<tr style="background-color:#FFFF33">
								 <td width="40" colspan="3" >Total</td>
								 <td width="40" align="right" ><?php echo $tot_qnty ?></td>	
								 <td width="40" align="right"><?php echo $taxable_amt ?></td>
								 <td width="40" align="right"><?php echo $igst_amt ?></td>	
								 <td width="40" align="right" ><?php echo $cgst_amt ?></td>			
								 <td width="40" align="right"><?php echo $sgst_amt ?></td>
								 <td width="40" align="right"><?php echo $tot_value ?></td>		
							 </tr>	
							
				</table>
			<?php } ?>	
			
			
			
			<!-- GST REPORT SECTIONS-->	
			
			<?php if($REPORT_NAME=='EXPIRY_REGISTER'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td width="40"  colspan="9" style="background-color:#999999"><?php echo 'Expiry Register'; ?></td></tr>
				<tr  style="background-color:#999999">
				 <td width="40" >Product Name</td>
				 <td width="40" >Batch No</td>
				 <td width="40" >Exp date</td>
				 <td width="40" >Mfg date</td>				
				 <td width="40" align="right">Availavle Qnty</td>
				 <td width="40" align="right">Rate</td>
				 <td width="40" align="right">Total</td>			 
				 </tr>			
				 
					 <?php 
							$trading_rs=$report_data;
							$trading_cnt_total=sizeof($trading_rs); 
							$tot_sample=$TOTAL_SELL_RTN=$total_purchase=$total_sale=$total_available_qnty=$total_amt=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$total_available_qnty=$total_available_qnty+$trading_rs[$trading_cnt]['qty_available'];
							$total_amt=$total_amt+$trading_rs[$trading_cnt]['total_amt'];	
							
						?>	
							<tr >
							 <td width="40" >
							 <a href="<?php echo $trading_rs[$trading_cnt]['href'];  ?>">
							 <?php echo $trading_rs[$trading_cnt]['product_name'] ?></a></td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['batchno'] ?></td>
							 <td width="40" >
							 <?php 
							 echo $trading_rs[$trading_cnt]['exp_monyr'].'('.$trading_rs[$trading_cnt]['EXPIRY_DATE'].')' ?>
							 
							 </td>
							 <td width="40" ><?php echo $trading_rs[$trading_cnt]['mfg_monyr'] ?></td>							
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['qty_available'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['rate'] ?></td>
							 <td width="40" align="right"><?php echo $trading_rs[$trading_cnt]['total_amt'] ?></td>			 
							 </tr>	
					<?php }} ?>
							<tr  style="background-color:#CC6633">
							 <td width="40" colspan="4"  style="background-color:#CC6633">Total</td>							
							 <td width="40" align="right"><?php echo $total_available_qnty; ?></td>
							 <td width="40" >&nbsp;</td>
							 <td width="40" align="right"><?php echo $total_amt; ?></td>			 
							 </tr>	
				</table>
			<?php } ?>
			
			
			
			
			
			
			
			
			
			
			
			<?php if($REPORT_NAME=='DOCTOR_COMMISSION_DETAILS'){ ?>
				<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="7" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php
					//print_r($report_data);
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$ledger_ac); 		
					echo 'Ledger Transaction of '.$acc_name.' From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Particular</td>	
					<td  align="left">Vch Type</td>
					<td  align="left">Vch/Invoice No</td>
					<td  align="right">Paid</td>	
					<td  align="right">Due</td>				
					
				</tr>
			 				
					  <?php 
							$credit_cumulative_balance=$debit_cumulative_balance=$tot_dr_balance=$tot_cr_balance=0;
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//echo $trading_rs[$trading_cnt]['href'];			
						?>	
						<tr  style="background-color:<?php 
						if($report_data[$trading_cnt]['debit_amount']>0)
						{echo '#CC6666';}else{echo '#33CC66';} ?>">						 
							<td  ><a href="<?php //echo $report_data[$trading_cnt]['href'];  ?>">
							<?php echo $report_data[$trading_cnt]['tran_date'] ?></a></td>
							 <td ><?php echo $report_data[$trading_cnt]['particular'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_type'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_code'] ?></td>	
							 <td align="right"><?php echo $report_data[$trading_cnt]['credit_amount'] ?></td>			
							 <td align="right"><?php echo $report_data[$trading_cnt]['debit_amount'] ?></td>
						 </tr>	
						 
						 <!--DETAILS-->
						 <?php if($report_data[$trading_cnt]['debit_amount']>0 && $report_data[$trading_cnt]['tran_table_id']>0){ ?>
						 <tr >
						 <td  colspan="6" >	
						 <table    class="table table-bordered table-striped" id="example">
						  <tr>	
						 	<td  align="left">Product Name</td>	
							<td  align="right">Qnty</td>	
							<td  align="right">Rate</td>
							<td  align="right">Total After disc</td>
							<td  align="right">Commission %</td>	
							<td  align="right">Commission Amt</td>	
						  </tr>
						   <?php 
								
							$whr="  invoice_summary_id=".$report_data[$trading_cnt]['tran_table_id']." and RELATED_TO_MIXER='NO'";
							$invoice_details_rs=$this->projectmodel->GetMultipleVal('*','invoice_details',	$whr,'id ASC ');
							$cnt=sizeof($invoice_details_rs);	 
							for($fieldIndex=0;$fieldIndex<$cnt;$fieldIndex++)
							{				
							?>	
							 <tr>	
								<td  align="left"><?php 
								echo $this->projectmodel->GetSingleVal('productname','productmstr',' id='.$invoice_details_rs[$fieldIndex]['product_id']);
								 ?></td>	
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['qnty']; ?></td>	
								<td align="right"><?php echo $invoice_details_rs[$fieldIndex]['rate']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['taxable_amt']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['doctor_commission_percentage']; ?></td>	
								<td  align="right"><?php 
								$tot_amt=$invoice_details_rs[$fieldIndex]['doctor_commission_percentage']*$invoice_details_rs[$fieldIndex]['taxable_amt']/100;
								echo $tot_amt; ?></td>	
							  </tr>
						  <?php } ?>
						 </table>
						 </td>
						 </tr>
						 <?php } ?>
						 
						 
				<?php 				
					$debit_cumulative_balance=$debit_cumulative_balance+$report_data[$trading_cnt]['credit_amount'];
					$credit_cumulative_balance=$credit_cumulative_balance+$report_data[$trading_cnt]['debit_amount'];
					
					if($debit_cumulative_balance>=$credit_cumulative_balance)
					{ $bal=$debit_cumulative_balance-$credit_cumulative_balance;} //echo $bal.'Dr';}
					else
					{$bal=$credit_cumulative_balance-$debit_cumulative_balance;} //echo $bal.'Cr';}
	
					$tot_dr_balance=$tot_dr_balance+$report_data[$trading_cnt]['credit_amount'];
					$tot_cr_balance=$tot_cr_balance+$report_data[$trading_cnt]['debit_amount'];
				
				}} ?>
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="4"><strong>Total</strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_dr_balance;?></strong> </td>
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_cr_balance;?></strong></td>		
	</tr>
	
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="6">
	<strong>
	<?php 		
	
		if($tot_cr_balance>$tot_dr_balance)
		{echo 'Total Due :'.($tot_cr_balance-$tot_dr_balance);}
		else
		{echo 'Total Advance paid :'.($tot_dr_balance-$tot_cr_balance); }
	?>
	
	</strong></td>			
	</tr>
				
				</table>
			<?php } ?>
			
			<?php 
			if($display_report=='YES'){
			if($REPORT_NAME=='PRODUCT_WISE_PURCHASE' || $REPORT_NAME=='PRODUCT_WISE_SALE'){ ?>
				<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="11" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php echo ' From :'.$fromdate.' To :'.$todate;?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Invoice No</td>	
					<td  align="left">Party Name</td>
					<td  align="left">Product Name</td>
					<td  align="left">Batch No</td>
					<td  align="left">Rack No</td>
					<td  align="right">Qnty</td>	
					<td  align="right">Rate</td>	
					<td  align="right">Total</td>	
					<td  align="right">Disc Amt</td>	
					<td  align="right">Grand Total</td>	
				</tr>
			 				
					  <?php 
							$total_total=$discount_total=$grandtot_total=0;
							
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{			
							//echo $trading_rs[$trading_cnt]['href'];			
						?>	
						<tr >						 
							<td  align="left"><a href="<?php //echo $report_data[$trading_cnt]['href'];  ?>">
							<?php echo $report_data[$trading_cnt]['invoice_date'] ?></a></td>
							 <td align="left"><?php echo $report_data[$trading_cnt]['invoice_no']; ?></td>
							 <td align="left"><?php echo $report_data[$trading_cnt]['party_name']; ?></td>
							 <td align="left"><?php echo $report_data[$trading_cnt]['product']; ?></td>
							 <td align="left"><?php echo $report_data[$trading_cnt]['batchno']; ?></td>
							 <td align="left"><?php echo $report_data[$trading_cnt]['rackno']; ?></td>
							<td  align="right"><?php echo $report_data[$trading_cnt]['qnty']; ?></td>	
							<td  align="right"><?php echo $report_data[$trading_cnt]['rate']; ?></td>	
							<td  align="right"><?php echo $report_data[$trading_cnt]['total']; ?></td>	
							<td  align="right"><?php echo $report_data[$trading_cnt]['discount']; ?></td>	
							<td  align="right"><?php echo $report_data[$trading_cnt]['grandtot'];?></td>				
							 
					 </tr>	
				<?php 	
					$total_total=$total_total+$report_data[$trading_cnt]['total'];
					$discount_total=$discount_total+$report_data[$trading_cnt]['discount'];
					$grandtot_total=$grandtot_total+$report_data[$trading_cnt]['grandtot'];
				}} ?>
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="8"><strong>Total</strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $total_total;?></strong> </td>
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $discount_total;?></strong></td>		
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $grandtot_total;?></strong></td>
	</tr>
	
	
				
				</table>
			<?php }} ?>
			
			
			<?php if($REPORT_NAME=='DEBTORS_SUMMARY'){ ?>
				<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="7" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$ledger_ac); 		
					echo 'Ledger Transaction of '.$acc_name.' From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Vch Type</td>
					<td  align="left">Vch/Invoice No</td>
					<td  align="left">Particular</td>	
					<td  align="right">Due Amount</td>	
					<td  align="right">Receive Amount</td>				
					<!--<td  align="right">Balance</td> -->
				</tr>
			 				
					  <?php 
					  		
							$credit_cumulative_balance=$debit_cumulative_balance=$tot_dr_balance=$tot_cr_balance=0;
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$display='YES';
							if($report_data[$trading_cnt]['credit_amount']>0  )
							{
								if($report_data[$trading_cnt]['particular_ledger_account']==323)
								{$display='YES';}
								else
								{$display='NO';}
								
								$whr="  id=".$report_data[$trading_cnt]['tran_table_id'];
								$report_data[$trading_cnt]['credit_amount']=$this->projectmodel->GetSingleVal('grandtot','invoice_summary',	$whr,'id ASC ');
																
							}
							
							if($display=='YES')
							{		
									
						?>	
						<tr style="background-color:<?php 
						if($report_data[$trading_cnt]['debit_amount']>0)
						{echo '#CC6666';}else{echo '#33CC66';} ?>">						 
							<td  ><a href="<?php //echo $report_data[$trading_cnt]['href'];  ?>">
							<?php echo $report_data[$trading_cnt]['tran_date'] ?></a></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_type'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_code'] ?></td>	
							 <td><?php echo $report_data[$trading_cnt]['particular'] ?></td>
							 <td align="right"><?php echo $report_data[$trading_cnt]['credit_amount'] ?></td>			
							 <td align="right"><?php echo $report_data[$trading_cnt]['debit_amount'] ?></td>			
							 
						 </tr>	
						 
						 <?php if($report_data[$trading_cnt]['credit_amount']>0 && $report_data[$trading_cnt]['tran_table_id']>0){ ?>
						 <tr >
						 <td  colspan="6" >	
						 <table    class="table table-bordered table-striped" id="example">
						  <tr>	
						 	<td  align="left">Product Name</td>	
							<td  align="right">Qnty</td>	
							<td  align="right">Rate</td>
							<td  align="right">Total After disc</td>
							<td  align="right">Tax %</td>	
							<td  align="right">Total After tax</td>	
						  </tr>
						   <?php 
								
							$whr="  invoice_summary_id=".$report_data[$trading_cnt]['tran_table_id']." and RELATED_TO_MIXER='NO'";
							$invoice_details_rs=$this->projectmodel->GetMultipleVal('*','invoice_details',	$whr,'id ASC ');
							$cnt=sizeof($invoice_details_rs);	 
							for($fieldIndex=0;$fieldIndex<$cnt;$fieldIndex++)
							{				
							?>	
							 <tr>	
								<td  align="left"><?php 
								echo $this->projectmodel->GetSingleVal('productname','productmstr',' id='.$invoice_details_rs[$fieldIndex]['product_id']);
								 ?></td>	
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['qnty']; ?></td>	
								<td align="right"><?php echo $invoice_details_rs[$fieldIndex]['rate']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['taxable_amt']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['tax_per']; ?></td>	
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['taxable_amt']+
								$invoice_details_rs[$fieldIndex]['cgst_amt']+
								$invoice_details_rs[$fieldIndex]['sgst_amt']+
								$invoice_details_rs[$fieldIndex]['igst_amt']; ?></td>	
							  </tr>
						  <?php } ?>
						 </table>
						 </td>
						 </tr>
						 <?php } ?>
				<?php 				
					$debit_cumulative_balance=$debit_cumulative_balance+floatval($report_data[$trading_cnt]['credit_amount']);
					$credit_cumulative_balance=$credit_cumulative_balance+floatval($report_data[$trading_cnt]['debit_amount']);
					
					if($debit_cumulative_balance>=$credit_cumulative_balance)
					{ $bal=$debit_cumulative_balance-$credit_cumulative_balance;} //echo $bal.'Dr';}
					else
					{$bal=$credit_cumulative_balance-$debit_cumulative_balance;} //echo $bal.'Cr';}
	
					$tot_dr_balance=$tot_dr_balance+floatval($report_data[$trading_cnt]['credit_amount']);
					$tot_cr_balance=$tot_cr_balance+floatval($report_data[$trading_cnt]['debit_amount']);
				
				}}} ?>
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="4"><strong>Total</strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_dr_balance;?></strong> </td>
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_cr_balance;?></strong></td>		
	</tr>
	
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="6">
	<strong>
	<?php 		
	
		if($tot_cr_balance>$tot_dr_balance)
		{echo 'Total Advance paid  :'.($tot_cr_balance-$tot_dr_balance);}
		else
		{echo 'Total Due :'.($tot_dr_balance-$tot_cr_balance); }
	?>
	
	</strong></td>			
	</tr>
				
				</table>
			<?php } ?>
			
			
			<?php if($REPORT_NAME=='CREDITORS_SUMMARY'){ ?>
				<table    class="table table-bordered table-striped" id="example">
			
				<tr>			
					<td  colspan="7" align="center" style="background: linear-gradient(#CC9933,#CC9933);">
					<?php
					$acc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',' id='.$ledger_ac); 		
					echo 'Ledger Transaction of '.$acc_name.' From :'.$fromdate.' To :'.$todate;
					?>
					</td>	
				</tr>
				
				<tr style="background: linear-gradient(#CC9933,#CC9933);">			
					<td  align="left">Date</td>	
					<td  align="left">Vch Type</td>
					<td  align="left">Vch/Invoice No</td>
					<td  align="left">Particular</td>	
					<td  align="right">Due Amount</td>	
					<td  align="right">Paid Amount</td>				
					<!--<td  align="right">Balance</td> -->
				</tr>
			 				
					  <?php 
					  		
							$credit_cumulative_balance=$debit_cumulative_balance=$tot_dr_balance=$tot_cr_balance=0;
							$trading_cnt_total=sizeof($report_data); 
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
							$display='YES';
							
							// IGNORE THE GST LEDGERS
							if($report_data[$trading_cnt]['particular_ledger_account']==319 || 
							$report_data[$trading_cnt]['particular_ledger_account']==320 || $report_data[$trading_cnt]['particular_ledger_account']==321)
							{$display='NO';  }
							
							if($report_data[$trading_cnt]['particular_ledger_account']==322 && $report_data[$trading_cnt]['credit_amount']>0)
							{
								$whr="  id=".$report_data[$trading_cnt]['tran_table_id'];
								echo $report_data[$trading_cnt]['credit_amount']=$this->projectmodel->GetSingleVal('grandtot','invoice_summary',	$whr,'id ASC ');							
							}
							
							
							/*echo '----------------------------------'.$report_data[$trading_cnt]['particular_ledger_account'];
							if($report_data[$trading_cnt]['credit_amount']>0  )
							{
								if($report_data[$trading_cnt]['particular_ledger_account']==322)
								{$display='YES';}
								else
								{$display='NO';}
								
								$whr="  id=".$report_data[$trading_cnt]['tran_table_id'];
								$report_data[$trading_cnt]['credit_amount']=$this->projectmodel->GetSingleVal('grandtot','invoice_summary',	$whr,'id ASC ');
																
							}
							*/
							
							if($display=='YES')
							{		
									
						?>	
						<tr style="background-color:<?php 	if($report_data[$trading_cnt]['debit_amount']>0){echo '#CC6666';}else{echo '#33CC66';} ?>">						 
							<td  ><a href="<?php //echo $report_data[$trading_cnt]['href'];  ?>">
							<?php echo $report_data[$trading_cnt]['tran_date'] ?></a></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_type'] ?></td>
							 <td  ><?php echo $report_data[$trading_cnt]['tran_code'] ?></td>	
							 <td><?php echo $report_data[$trading_cnt]['particular'] ?></td>
							 <td align="right"><?php echo $report_data[$trading_cnt]['debit_amount'] ?></td>
							 <td align="right"><?php echo $report_data[$trading_cnt]['credit_amount'] ?></td>				
							 
						 </tr>	
						 
						 <?php if($report_data[$trading_cnt]['debit_amount']>0 && $report_data[$trading_cnt]['tran_table_id']>0){ ?>
						 <tr >
						 <td  colspan="6" >	
						 <table    class="table table-bordered table-striped" id="example">
						  <tr>	
						 	<td  align="left">Product Name</td>	
							<td  align="right">Qnty</td>	
							<td  align="right">Rate</td>
							<td  align="right">Total After disc</td>
							<td  align="right">Tax %</td>	
							<td  align="right">Total After tax</td>	
						  </tr>
						   <?php 
								
							$whr="  invoice_summary_id=".$report_data[$trading_cnt]['tran_table_id']." and RELATED_TO_MIXER='NO'";
							$invoice_details_rs=$this->projectmodel->GetMultipleVal('*','invoice_details',	$whr,'id ASC ');
							$cnt=sizeof($invoice_details_rs);	 
							for($fieldIndex=0;$fieldIndex<$cnt;$fieldIndex++)
							{				
							?>	
							 <tr>	
								<td  align="left"><?php 
								echo $this->projectmodel->GetSingleVal('productname','productmstr',' id='.$invoice_details_rs[$fieldIndex]['product_id']);
								 ?></td>	
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['qnty']; ?></td>	
								<td align="right"><?php echo $invoice_details_rs[$fieldIndex]['rate']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['taxable_amt']; ?></td>
								<td  align="right"><?php echo $invoice_details_rs[$fieldIndex]['tax_per']; ?></td>	
								<td  align="right"><?php 
								echo $invoice_details_rs[$fieldIndex]['taxable_amt']+
								$invoice_details_rs[$fieldIndex]['cgst_amt']+
								$invoice_details_rs[$fieldIndex]['sgst_amt']+
								$invoice_details_rs[$fieldIndex]['igst_amt']; ?></td>	
							  </tr>
						  <?php } ?>
						 </table>
						 </td>
						 </tr>
						 <?php } ?>
				<?php 				
					$debit_cumulative_balance=$debit_cumulative_balance+floatval($report_data[$trading_cnt]['credit_amount']);
					$credit_cumulative_balance=$credit_cumulative_balance+floatval($report_data[$trading_cnt]['debit_amount']);
					
					if($debit_cumulative_balance>=$credit_cumulative_balance)
					{ $bal=$debit_cumulative_balance-$credit_cumulative_balance;} //echo $bal.'Dr';}
					else
					{$bal=$credit_cumulative_balance-$debit_cumulative_balance;} //echo $bal.'Cr';}
	
					$tot_dr_balance=$tot_dr_balance+floatval($report_data[$trading_cnt]['credit_amount']);
					$tot_cr_balance=$tot_cr_balance+floatval($report_data[$trading_cnt]['debit_amount']);
				
				}}} ?>
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="4"><strong>Total</strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_cr_balance;?></strong></td>	
	<td style="background: linear-gradient(#CC9933,#CC9933);" align="right"><strong><?php echo $tot_dr_balance;?></strong> </td>	
	</tr>
	
	<tr>
	<td style="background: linear-gradient(#CC9933,#CC9933);"  colspan="6">
	<strong>
	<?php 		
	
		if($tot_cr_balance>$tot_dr_balance)
		{echo 'Total Due :'.($tot_cr_balance-$tot_dr_balance);}
		else
		{echo 'Total Advance paid   :'.($tot_dr_balance-$tot_cr_balance); }
	?>
	
	</strong></td>			
	</tr>
				
				</table>
			<?php } ?>
			
			<?php if($REPORT_NAME=='TRIAL_BALANCE'){ ?>
			<table   class="table table-bordered table-striped" id="example">
			<tr ><td    colspan="6" style="background-color:#FFFF33" align="center">
			<?php echo 'Trial balance From '. $fromdate.' To '.$todate; ?></td></tr>
			
				<tr  style="background-color:#CCFFFF">
				<!-- <td   >id</td>
				 <td   >Parent id</td>
				 <td   >Level</td>-->
				 
				 <td    colspan="3">A/c Name</td>
				 <td   align="right" >Debit</td>				
				 <td   align="right">Credit</td>
				 </tr>	
					 <?php 
					 	
						$trading_rs=$report_data;
						$trading_cnt_total=sizeof($trading_rs); 
						$total_debit=$total_credit=0;
							if($trading_cnt_total>0){  
							for($trading_cnt=0;$trading_cnt<$trading_cnt_total;$trading_cnt++)
							{	
								$manual_pading='&nbsp;';
								
								/*$child_count=1;	
								echo $childs = "select count(*) totqnty from acc_group_ledgers where parent_id=".$trading_rs[$trading_cnt]['id']."  " ;
								$childs = $this->projectmodel->get_records_from_sql($childs);
								foreach ($childs as $child){$child_count= $child->totqnty;} */
							
								if($trading_rs[$trading_cnt]['index']>0){
								
								for($i=1;$i<=$trading_rs[$trading_cnt]['index'];$i++)
								{$manual_pading=$manual_pading.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
							
							if($trading_rs[$trading_cnt]['debit_amt']>0 or $trading_rs[$trading_cnt]['credit_amt']>0){
						 ?>
						 
						 <tr >
						 
						 	<?php /*?> <td   ><?php echo $trading_rs[$trading_cnt]['id'] ?></td>
							 <td   ><?php echo $trading_rs[$trading_cnt]['parent_id'] ?></td>	
							 <td   ><?php echo $trading_rs[$trading_cnt]['index'] ?></td><?php */?>
							 
							 <?php if($trading_rs[$trading_cnt]['index']==1){?><!--MAIN GROUP-->
							 <td     bgcolor="#99CC66" colspan="3"><?php echo $manual_pading.$trading_rs[$trading_cnt]['name'] ?></td>
							 <td   align="right"><?php 
							 $total_debit=$total_debit+$trading_rs[$trading_cnt]['debit_amt'];
							 $total_credit=$total_credit+$trading_rs[$trading_cnt]['credit_amt'];
							 
							 echo ($trading_rs[$trading_cnt]['debit_amt'] > 0 ? $trading_rs[$trading_cnt]['debit_amt'] : ' '); ?></td>
							 <td   align="right"><?php echo ($trading_rs[$trading_cnt]['credit_amt'] > 0 ? $trading_rs[$trading_cnt]['credit_amt'] : ' '); ?></td>
							<?php } ?>
							
							<?php if($trading_rs[$trading_cnt]['index']>1){?><!--OTHERS-->
							
								<?php  if($trading_rs[$trading_cnt]['acc_type']=='GROUP'){?>
								<td   ><strong><?php echo $manual_pading.$trading_rs[$trading_cnt]['name'] ?></strong></td>
								<td   align="right">
								<strong><?php echo ($trading_rs[$trading_cnt]['debit_amt'] > 0 ? $trading_rs[$trading_cnt]['debit_amt'].' Dr' : ' '); ?></strong></td>
								 <td   align="right"><strong>
								 <?php echo ($trading_rs[$trading_cnt]['credit_amt'] > 0 ? $trading_rs[$trading_cnt]['credit_amt'].' Cr' : ' '); ?></strong></td>
								 <td   colspan="2" >&nbsp;</td>
								 
								<?php }else {?>
								<td   ><?php echo $manual_pading.$trading_rs[$trading_cnt]['name'] ?></td>
								<td  align="right" ><?php echo  ($trading_rs[$trading_cnt]['debit_amt'] > 0 ? $trading_rs[$trading_cnt]['debit_amt'].' Dr' : ' '); ?></td>
								 <td   align="right"><?php echo ($trading_rs[$trading_cnt]['credit_amt'] > 0 ? $trading_rs[$trading_cnt]['credit_amt'].' Cr' : ' '); ?></td>
								  <td   colspan="2" >&nbsp;</td>
								 <?php }?>
							 
							 <?php } ?>
							 
							 </tr>	
						
							<?php }}}} ?>
							
							 <tr >
							 <td     bgcolor="#99CC66" colspan="3">Total</td>
							 <td   align="right" ><?php echo $total_debit; ?></td>
							 <td   align="right"><?php echo $total_credit; ?></td>
							 </tr>	
							
				</table>
			<?php } ?>	
		
				
</div>	
  

<div class="panel panel-primary" >
			<div class="panel-body" align="center" style="background-color:#3c8dbc">
					<button type="button" class="btn btn-danger" onclick="myExportToExcel();">Excel</button>
					<button type="button" class="btn btn-danger" onclick="printDiv('printablediv');">Print</button>	
			</div>
</div> 



<script>
$(document).ready(function() {

    $('#example tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {window.location = href;}
    });

});
</script>

 <script>
  
     $("#fromdate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#fromdate").change(function() {
	 var  trandate = $('#fromdate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#fromdate").val(trandate);
	});
	
	 $("#todate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#todate").change(function() {
	 var  trandate = $('#todate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#todate").val(trandate);
	});
	
  </script>
  

	
	