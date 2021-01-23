
<style type="text/css">
<!--
.style2 {	
	font-weight: bold;
	font-size:18px;
}
-->
</style>

<style>
input {
  font-family: monospace;
}
label {
  display: block;
}
div {
  margin: 0 0 1rem 0;
}

.shell {
  position: relative;
  line-height: 1; }
  .shell span {
    position: absolute;
    left: 3px;
    top: 1px;
    color: #ccc;
    pointer-events: none;
    z-index: -1; }
    .shell span i {
      font-style: normal;
      /* any of these 3 will work */
      color: transparent;
      opacity: 0;
      visibility: hidden; }

input.masked,
.shell span {
  font-size: 16px;
  font-family: monospace;
  padding-right: 10px;
  background-color: transparent;
  text-transform: uppercase; }

</style>



<div ng-app="Accounts" ng-controller="PurchaseEntry" >
		
		<input type="hidden" name="id_header" id="id_header" ng-model="id_header"/>
		<input type="hidden" name="id_detail" id="id_detail" ng-model="id_detail"/>
			 
		 <input type="hidden" id="trantype"  name="trantype" 
		 ng-model="trantype" ng-init="trantype='OTHER'"  >
		 
		 <input type="hidden" name="product_id" id="product_id" ng-model="product_id"/>
		 <input type="hidden" name="tax_ledger_id" id="tax_ledger_id" 
		 ng-model="tax_ledger_id"/>
		 <input type="hidden" name="tbl_party_id" id="tbl_party_id" ng-model="tbl_party_id"/>
		  <input type="hidden" id="tot_cash_discount"  name="tot_cash_discount" ng-model="tot_cash_discount" />
		  		  	
		 
			<div class="panel panel-primary" >
			<div class="panel-body" >
				<div  class="form-row">	
					<div class="form-row col-md-12" >
										
					<table class="table table-bordered table-striped" >
				  
				  <!--<tr><td class="srscell-head-divider" colspan="4">Purchase  Summary
				  </td></tr>-->
						
					<tr>
						<td width="148"  align="left">Invoice No</td> 
						<td width="260">Date</td> 
						<td width="144"  align="left">Challan No</td> 
						<td width="328">Date</td> 
					</tr>
					
					<tr>
						<td>
						 <input type="text" id="1" class="form-control"	 name="invoice_no" ng-model="invoice_no" ng-keypress="mainOperation($event,1)"/>
					  </td> 
					  
						<td>
						 <input type="text" id="2" class="form-control"	 name="invoice_date" ng-model="invoice_date" ng-keypress="mainOperation($event,2)"/>
					  </td> 
					  
						<td>
						 <input type="text" id="3" class="form-control" name="challan_no" ng-model="challan_no" ng-keypress="mainOperation($event,3)"/>
					  </td> 
					  
						<td>
						 <input type="text" id="4" class="form-control"	 name="challan_date" ng-model="challan_date" ng-keypress="mainOperation($event,4)"/>
					  </td> 
						
					</tr>
					
					<tr>
						<td  align="left" >Party</td> 
						<td  align="left" > 
						<input id="5" autofocus type="text" 
						 name="tbl_party_id_name"   placeholder="Select Party"  
						 ng-keypress="mainOperation($event,5)"
						 ng-keydown="checkKeyDown($event)" 
						ng-keyup="checkKeyUp($event)" ng-model="tbl_party_id_name" 
						ng-change="search('tbl_party_id_name')" class="form-control" />
						</td>
						<td  >Comment</td> 
						<td  ><input type="text" id="6" class="form-control"  name="comment" ng-model="comment" ng-keypress="mainOperation($event,6)"/></td>
					</tr>
					
					<!--SHOW DETAILS-->
					
					<tr>
						<td colspan="4">
						
				 <table class="table table-bordered table-striped" >
				  
				  <tr><td class="srscell-head-divider" colspan="7">
				  <div ng-if="spiner === 'ON'">
					 <spinner-loader sl-visible="true" sl-size="large"></spinner-loader>
				 </div>
				 <div ng-if="spiner != 'ON'">
					<span class="style2 activeTR">{{savemsg}}</span>
				 </div>
				  
				 <!-- <span class="style2 activeTR">{{savemsg}}</span>-->
				 
				  </td></tr>
						
					<tr>
						<td  align="left" colspan="2">Product</td> 
						<td width="144">Batch</td> 
						<td width="144">Rack No</td> 
						<td  align="left">Qnty</td> 
						<td width="145">Exp</td> 
						<td width="145">Mfg</td> 
					</tr>	
					
					<tr>
						<td  align="left" colspan="2"> 												 
						 <input id="7" autofocus type="text" 
						 name="product_id_name"   placeholder="Select Product"  
						 ng-keydown="checkKeyDown($event)" 
						ng-keyup="checkKeyUp($event)" ng-model="product_id_name" 
						ng-change="search('product_id_name')" class="form-control" ng-keypress="mainOperation($event,7)"/>						
						 </td> 
						 
						<td width="144"> 
						
						  <input id="8" autofocus type="text" 
									 name="batchno"   placeholder="Select batch"  	
									 ng-keydown="checkKeyDown_batch($event)" 
									 ng-keyup="checkKeyUp_batch($event)"			
									 ng-model="batchno" ng-change="search_batch('batchno')" 
									 class="form-control" ng-keypress="mainOperation($event,8)"/>	
						 </td> 
						 <td  align="left"> 
						 <input type="text" id="9" class="form-control"	 name="rackno" ng-model="rackno" ng-keypress="mainOperation($event,9)"/></td> 
						<td  align="left"> <input type="text" id="10" class="form-control"	 name="qnty" ng-model="qnty" ng-keypress="mainOperation($event,10)"/></td> 
						
						<td width="145"> <input type="text" id="exp_monyr" class="form-control masked"
						 name="exp_monyr" placeholder="MM/YY"  pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)" data-valid-example="05/18" 
						 ng-model="exp_monyr" ng-keypress="mainOperation($event,11)"/></td> 
						<td width="145"> <input type="text" id="mfg_monyr" class="form-control masked" 
						placeholder="MM/YY"  pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)" data-valid-example="05/18"  name="mfg_monyr"
						 ng-model="mfg_monyr" ng-keypress="mainOperation($event,12)"/></td> 
					</tr>
					
					<tr>
						<td  align="left">Purchase Rate</td> 
						<td >MRP</td> 
						<td  >PTR</td> 
						<td >S.Rate</td> 
						<td >Disc 1 %</td> 
						<td >Disc 2 %</td> 
						<td >Tax %</td> 
					</tr>	
					
					<tr>
						<td> <input type="text" id="13" class="form-control" name="rate" ng-model="rate" ng-keypress="mainOperation($event,13)"/></td> 
						<td> <input type="text" id="14" class="form-control" name="mrp" ng-model="mrp" ng-keypress="mainOperation($event,14)"/></td> 
						<td> <input type="text" id="15" class="form-control" name="ptr" ng-model="ptr" ng-keypress="mainOperation($event,15)"/></td> 
						<td> <input type="text" id="16" class="form-control" name="srate" ng-model="srate" ng-keypress="mainOperation($event,16)"/></td> 
						<td> <input type="text" id="17" class="form-control" name="disc_per" ng-model="disc_per" ng-keypress="mainOperation($event,17)"/></td>	
						<td> <input type="text" id="18" class="form-control" name="disc_per2" ng-model="disc_per2" ng-keypress="mainOperation($event,18)"/></td>	
						<td> <input type="text" id="19" class="form-control" name="tax_per" ng-model="tax_per" ng-keypress="mainOperation($event,19)"/></td>
					</tr>	
						
				</table>		
				
				<?php /*?> <table class="table">
				
				<tr bgcolor="#CCCCCC"><th>Search</th></tr>
				
				<tr ng-repeat="suggestion in suggestions track by $index" 
				ng-class="{activeTR : selectedIndex === $index}"
				ng-click="AssignValueAndHide($index)" style="overflow:scroll">
					<td >{{suggestion}}</td>
				</tr>
								
				<tr>	
								<td colspan="6">
									 <table class="table" >
									<tr bgcolor="#CCCCCC">					
										<td>Batch No </td>
										<td>Rack No</td>
										<td>Available Qty</td>
										<td>Rate</td>
										<td>MRP</td>					
										<td>Exp</td>
										<td>MFG</td>
									</tr>
												
									<tr ng-repeat="suggestion in suggestions_batch 
									track by $index"  
									ng-class="{activeTR : selectedIndex_batch === $index}"
									ng-click="AssignValueAndHide_batch($index)"  >					
										<td>{{suggestion.batchno}}</td>
										<td>{{suggestion.rackno}}</td>
										<td>{{suggestion.AVAILABLE_QTY}}</td>
										<td>{{suggestion.rate}}</td>
										<td>{{suggestion.mrp}}</td>
										<td>{{suggestion.exp_monyr}}{{suggestion.exp_date}}</td>
										<td>{{suggestion.mfg_monyr}}</td>
									</tr>
								</table>
								</td>
								</tr>
								
			</table>	<?php */?>
					
					  </td> 
					</tr>	
					
					<tr>		
							<td   align="center" colspan="6">
					<button type="button" class="btn btn-danger" id="Save" name="Save" 
					ng-click="get_set_value('','','DRCRCHECKING')">Submit</button>
					
					<button type="button" class="btn btn-danger" 
					ng-click="print_barcode(id_header)" >Print </button>
					<button type="button" class="btn btn-success" ng-click="get_set_value('','','NEWENTRY')">
					New Entry</button>
					
					<button type="button" class="btn btn-danger" id="Save" name="Save" 
					ng-click="submit_print()" onclick="return confirm('Do you want to Save ?');">Final submit</button>
					
							</td> 
							
							
					</tr>					
					 
		  </table>
		  
		  	<div ng-if="searchelement === 'product_id_name'">								
			<table class="table" >				
			<tr>
			<th width="100">Product Name</th>
			<th width="100">Quantity Available</th>
			</tr>				
			<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
			ng-click="AssignValueAndHide($index)" style="overflow:scroll">
				<td >{{suggestion.name}}</td>
				<td >{{suggestion.available_qnty}}</td>
			</tr>
			</table>
			</div>
			
			<div ng-if="searchelement === 'tbl_party_id_name'">								
			<table class="table">				
			<tr><th>Party Name</th></tr>				
			<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
			ng-click="AssignValueAndHide($index)" style="overflow:scroll">
				<td >{{suggestion.name}}</td>
			</tr>
			</table>
			</div>
			
			<div ng-if="searchelement === 'doctor_ledger_id_name'">								
			<table class="table">				
			<tr><th>Doctot Name</th></tr>				
			<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
			ng-click="AssignValueAndHide($index)" style="overflow:scroll">
				<td >{{suggestion.name}}</td>
			</tr>
			</table>
			</div>
			
			<div ng-if="searchelement === 'batchno'">								
			<table class="table">				
			<tr>	
							<td colspan="6">
								 <table class="table" >
								<tr  bgcolor="#CCCCCC">					
									<td>Batch No</td>
									<td>Rack No</td>
									<td>Available Qty</td>
									<td>Rate</td>
									<td>MRP</td>					
									<td>Exp</td>
									<td>MFG</td>
								</tr>
											
								<tr ng-repeat="suggestion in suggestions_batch 
								track by $index"  	ng-class="{activeTR : selectedIndex_batch === $index}"
								ng-click="AssignValueAndHide_batch($index)"  >				
									<td>{{suggestion.batchno}}</td>
									<td>{{suggestion.rackno}}</td>
									<td>{{suggestion.AVAILABLE_QTY}}</td>
									<td>{{suggestion.rate}}</td>
									<td>{{suggestion.mrp}}</td>
									<td>{{suggestion.exp_monyr}}{{suggestion.exp_date}}</td>
									<td>{{suggestion.mfg_monyr}}</td>
								</tr>
							</table>
							</td>
							</tr>								
			</table>	
			</div>
				
					</div>
					
				
				</div>
				
			</div>
			</div>
			
			
			<div class="panel panel-primary" >
			<div class="panel-body" >
							
			<table  class="table table-bordered table-striped" >
		
				<thead   style="background-color:#99CC00">
					<tr>
					<td   align="left">Srl</td> 
					<td   align="left">PRODUCT</td> 
					<td   align="left">BATCH</td> 
					<td  align="left">EXP</td> 
					<td  align="left">MFG</td>
					<td   align="left">QNTY</td> 
					<td   align="left">P.RATE</td> 		
					<td   align="left">TOTAL</td>	
					<td   align="left">DISC</td>		
					<td   align="left">TAXABLE AMT</td>				 							
					<td   align="left">TAX</td>	
					<td   align="left">NET TOTAL</td>	
					<td   align="left">EDIT</td>
					<td   align="left">DELETE</td>
					</tr>
				</thead>
			   
			   <tbody>
					
					<tr ng-repeat="Trandtl in listOfDetails track by $index">
						<td  align="left">{{$index+1}}</td>
						<td  align="left">{{Trandtl.product_id_name}}</td> 
						<td  align="left">{{Trandtl.batchno}}</td> 	
						<td  align="left">{{Trandtl.exp_monyr}}</td>	
						<td  align="left">{{Trandtl.mfg_monyr}}</td>		
						<td  align="left">{{Trandtl.qnty}}</td>
						<td  align="left">{{Trandtl.rate}}</td>	
						<td  align="left">{{Trandtl.subtotal}}</td>		
						<td  align="left">{{Trandtl.disc_per}}% +{{Trandtl.disc_per2}}% </td>
						<td  align="left">{{Trandtl.taxable_amt}}</td>						
						<td  align="left">{{Trandtl.tax_ledger}}</td>	
						<td  align="left">{{Trandtl.NET_TOTAL}}</td>	
											
						<td  align="left"><button class="btn-block btn-info" 
						ng-click="get_set_value(Trandtl.id,'','VIEWDTL')">Edit</button>
						<td  align="left"><button class="btn-block btn-info" 
						ng-click="delete_product(Trandtl.id)" onClick="return confirm('Do you want to Delete This Product ?');">Delete</button>
						</td> 
					</tr>			
			 </tbody>
		</table>   
			
			<table  class="table table-bordered table-striped" >
		
				<thead   >
				<tr>
					<td   style="background-color:#CC6633">Total</td> 
					<td   style="background-color:#CC6633">Less discount</td> 
					<td  style="background-color:#CC6633">Taxable Amount</td> 
					<td  style="background-color:#CC6633">Tax</td>
					<td   style="background-color:#CC6633">Grand Total</td> 
				</tr>
				
				<tr>
					<td   align="left"><strong>{{total_amt}}</strong></td> 
					<td   align="left"><strong>{{tot_discount}}</strong></td> 
					<td  align="left"><strong>{{tot_taxable_amt}}</strong></td> 
					<td  align="left"><strong>{{totvatamt}}</strong></td>
					<td   align="left"><strong>{{grandtot}}</strong></td> 
				</tr>
				</thead>
		</table>   
			</div></div>		
				
		
	
	<!--LIST OF ALL CONSIGNMENT FROM TO WISE SEARCH-->		  
	<div class="panel panel-primary" >
			<div class="panel-body" align="center" style="background-color:#3c8dbc">
	 <div class="form-row">
			   
				<div class="form-group col-md-4">
				  <label for="inputState">From Date</label>
				 <input type="text"  id="startdate"  
				 name="startdate" class="form-control"  ng-model="startdate"> 				
				</div>
				
				 <div class="form-group col-md-4">
				  <label for="inputState">To date</label>
				 <input type="text"  id="enddate"  
				 name="enddate" class="form-control"  ng-model="enddate"> 
				 </div>
				 
				  <div class="form-group col-md-4">
				<button type="button" class="btn btn-primary" name="Save" 
				ng-click="GetAllList(startdate,enddate)">Submit</button>
			  	 </div>
				
			  </div>
	
	</div></div>
	<table class="table table-bordered table-striped" >
		
				<thead>
					<tr>
					<td width="106"  align="left">No</td> 
					<td width="123"  align="left">Date</td> 
					<td width="123"  align="left">Party Name</td>
					<td width="123"  align="left">Bill Amount</td>				
					<td width="31"  align="left">Edit</td> 
					<td width="71"  align="left">Delete</td>
					</tr>
				</thead>
			   
			   <tbody>
					
					<tr ng-repeat="dtl in ListOfTransactions">
						<td  align="left">{{dtl.invoice_no}}</td> 
						<td  align="left">{{dtl.invoice_date}}</td> 
						<td  align="left">{{dtl.party_name}}</td> 	
						<td  align="left">{{dtl.grandtot}}</td> 			
					
						
						<td  align="left"><button class="btn-block btn-info" 
						ng-click="get_set_value(dtl.id_header,'','VIEWALLVALUE')" >Edit</button>
						<td  align="left"><button class="btn-block btn-info" >Delete</button>
						</td> 
					</tr>			
			 </tbody>
		</table>   

</div>


<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/masking-input.js" data-autoinit="true"></script>

<script>
  
  $("#2").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#2").change(function() {
  var  trandate = $('#2').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#2").val(trandate);
 });
 
 $("#4").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#4").change(function() {
  var  trandate = $('#4').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#4").val(trandate);
 });
 
  
 
  $("#startdate").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#startdate").change(function() {
  var  trandate = $('#startdate').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#startdate").val(trandate);
 });
 
 $("#enddate").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#enddate").change(function() {
  var  trandate = $('#enddate').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#enddate").val(trandate);
 });
	 
 
	 
</script>

<script>
 /* Inputmask.extendAliases({
  "yyyy-mm": {
    mask: "y-2",
    placeholder: "yyyy-mm",
    alias: "datetime",
    separator: "-"
  }
})

$("#exp_monyr").inputmask("yyyy-mm");
$("#mfg_monyr").inputmask("yyyy-mm");

$("#mfg_monyr_mixture").inputmask("yyyy-mm");
$("#exp_monyr_mixture").inputmask("yyyy-mm");*/

  
</script>		
