
<script type="text/javascript">

$(".modal-wide").on("show.bs.modal", function() {
  var height = $(window).height() - 200;
  $(this).find(".modal-body").css("max-height", height);
});


</script>
<style>

div.ex1 {
  background-color: lightblue;
  width: 100%;
  height: 200px;
  overflow: scroll;
}



.activeTR {  
	background-color: yellow;
    color:black;	
    font-weight:bold;
}

</style>
<style type="text/css">

.style2 {
	color: #990000;
	font-weight: bold;
	font-size:18px;
}


.modal.modal-wide .modal-dialog {
  width: 90%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}

/* irrelevant styling */

body p { 
  max-width: 400px; 
  margin: 20px auto; 
  margin-bottom: 400px
}
#tallModal .modal-body p { margin-bottom: 900px }

</style>

<script type = "text/javascript">
/*Final Submit(F8) | New Mixer(F9) | Print Invoice(F10) | Print POS(F11) | New Entry (F1) */
 function shortcut() {		 
		 
		 document.addEventListener("keydown", function(event) {
		 
		 	//alert(event.keyCode);
		 	if(event.keyCode==119)//Final Submit(F8)
			{angular.element(document.getElementById('myBody')).scope().submit_print();}
			if(event.keyCode==120) //New Mixer(F9)
			{$('#shortModal').modal({show: 'false'});document.getElementById(101).focus();}
			if(event.keyCode==121) // Print Invoice(F10)
			{angular.element(document.getElementById('myBody')).scope().print_invoice('INVOICE');}
			if(event.keyCode==122) //Print POS(F11)
			{angular.element(document.getElementById('myBody')).scope().print_invoice('INVOICE_POS');}
			if(event.keyCode==118) //New Entry (F1)
			{angular.element(document.getElementById('myBody')).scope().get_set_value('','','NEWENTRY');}
		  
		});
          
		
			
         }
</script>  
	  
<div ng-app="Accounts"   >



	
	<?php /*?><!--PRODUCT SECTION START-->
		<div  ng-controller="Product_master" >
					<div class="panel panel-primary" >
					<div class="panel-body" >
						<div  class="form-row">	
												
							<table class="table table-bordered table-striped" >
						  
							<tr>
								<td colspan="4">
								
						 <table class="table table-bordered table-striped" >
						  
						  <tr><td class="srscell-head-divider" colspan="5">
						  <div ng-if="spiner === 'ON'">
							 <spinner-loader sl-visible="true" sl-size="large"></spinner-loader>
						 </div>
						 <div ng-if="spiner != 'ON'">
							<span class="style2 activeTR">{{savemsg}}</span>
						 </div>
						  
						 <!-- <span class="style2 activeTR">{{savemsg}}</span>-->
						 
						  </td></tr>
								
							<tr>
								<td width="236">Product Name</td> 
								<td  align="left">HSN Code</td> 
								<td width="192">Tax Rate</td> 
								<td width="192">Product Group</td> 
								<td width="192">Brand name</td> 
							</tr>	
							
							<tr>
								<td  align="left" > 												 
								 <input id="product_id_name" autofocus type="text" 
								 name="product_id_name"   placeholder="Select Product"  
								 ng-keydown="checkKeyDown($event)" 
								ng-keyup="checkKeyUp($event)" ng-model="product_id_name" 
								ng-change="search('product_id_name')" class="form-control" style="width:400px;" ng-keypress="mainOperation($event,1)"/>						
							  </td> 
								 
								 <td width="100"> 
								 <input type="text" id="hsncode" class="form-control"	 style="width:100px;" 
								 name="hsncode" ng-model="hsncode" ng-keypress="mainOperation($event,2)"/>
							  </td> 
								 
								 <td  align="left">
								  <input id="tax_per" autofocus type="text" 
								 name="tax_per"   placeholder="Select tax"  
								 ng-keydown="checkKeyDown($event)" 
								ng-keyup="checkKeyUp($event)" ng-model="tax_per" 
								ng-change="search('tax_per')" class="form-control" ng-keypress="mainOperation($event,3)"/>							 
								 </td> 
								 
								  <td  align="left">
								  <input id="group_id_name" autofocus type="text" 
								 name="group_id_name"   placeholder="Select group"  
								 ng-keydown="checkKeyDown($event)" 
								ng-keyup="checkKeyUp($event)" ng-model="group_id_name" 
								ng-change="search('group_id_name')" class="form-control" ng-keypress="mainOperation($event,4)"/>							 
								 </td> 
								 
								 <td  align="left">
								  <input id="brand_id_name" autofocus type="text" 
								 name="brand_id_name"   placeholder="Select Brand"  
								 ng-keydown="checkKeyDown($event)" 
								ng-keyup="checkKeyUp($event)" ng-model="brand_id_name" 
								ng-change="search('brand_id_name')" class="form-control" ng-keypress="mainOperation($event,5)"/>							 
								 </td> 
								
							</tr>
								
						</table>		
					
							
							  </td> 
							</tr>	
							
							<tr>		
									<td   align="center" colspan="5">
							<button type="button" class="btn btn-danger" id="Save" name="Save" 	ng-click="savedata()">Submit</button>
							&nbsp;&nbsp;
							<button type="button" class="btn btn-success" id="Save" name="Save" 	ng-click="get_set_value('','','REFRESH')">New Entry</button>
									</td> 
							</tr>					
							 
				  </table>
				  
							<!--<div ng-if="searchelement === 'product_id_name'">		-->						
							<table class="table" >				
							<tr>
							<th width="100">Name</th>
							
							</tr>				
							<tr ng-repeat="suggestion in suggestions_products track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
							ng-click="AssignValueAndHide($index)" style="overflow:scroll">
								<td >{{suggestion.name}}</td>
								
							</tr>
							</table>
							<!--</div>-->
						</div>
						
					</div>
					</div>
		</div>
	<!--PRODUCT SECTION END--><?php */?>
	
	
	<div class="panel panel-primary" ng-controller="Sale_test" ng-click="hideMenu($event)" id="myBody" onchange = "shortcut()">
				
				
					 
		<!--<form id="Transaction_form" name="Transaction_form" >-->
		
		<input type="hidden" name="id_header" id="id_header" ng-model="id_header"/>
		<input type="hidden" name="id_detail" id="id_detail" ng-model="id_detail"/>
		<input type="hidden" id="trantype"  name="trantype"  ng-model="trantype" ng-init="trantype='OTHER'"  >
		<input type="hidden" name="product_id" id="product_id"  ng-model="product_id"/>
		<input type="hidden" name="tax_ledger_id" id="tax_ledger_id" 	 ng-model="tax_ledger_id"/>
		<input type="hidden" name="tbl_party_id" id="tbl_party_id" 	 ng-model="tbl_party_id"/>
		<input type="hidden" name="MIX_RAW_LINK_ID" id="MIX_RAW_LINK_ID"  ng-model="MIX_RAW_LINK_ID"/>
		<input type="hidden" name="TRANTYPE" id="TRANTYPE"  ng-model="TRANTYPE" ng-init="TRANTYPE='FINAL'"/>
		<input type="hidden" name="doctor_ledger_id" id="doctor_ledger_id"  ng-model="doctor_ledger_id" />
		<input type="hidden" id="tot_cash_discount"  name="tot_cash_discount" ng-model="tot_cash_discount" />
		
			
	
		<!--MIXTURE SALE SECTION START-->
		
			<div class="panel-body" >
			
				
				 <div id="shortModal" class="modal modal-wide fade">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							  <div class="panel-body" align="center" style="background-color:#99CC00">						
								  <span class="blink_me style2">Mixture Prepare-{{savemsg}}</span>
							  </div>
						  </div>
						   <div ng-if="spiner === 'ON'">{{spiner}}
						 <spinner-loader sl-visible="true" sl-size="large"></spinner-loader>
						 </div>
						  
						<div class="modal-body">
																			
							<table class="table" >
												<input type="hidden" ng-model="product_name_mixture"/>										 
												<input type="hidden"  ng-model="batchno_mixture"/>	
												<input type="hidden"  ng-model="rate_mixture"/>
												<input type="hidden"  ng-model="rate_mixture"/>
												<input type="hidden"  ng-model="mfg_monyr_mixture"/>
												<input type="hidden"  ng-model="exp_monyr_mixture"/>
												
												<tr ><td class="srscell-head-divider" colspan="8">Mixture Details	</td></tr>													  
												   <tr>
														<td  align="left" >Product</td> 
														<td colspan="5">
														 <input type="text" id="product_name_mixture" 
														 class="form-control" 	 name="product_name_mixture" 
														 ng-model="product_name_mixture" readonly="" />
													 </td> 
														  <td  align="left" >Qnty</td> 	
														  <td>
															 <input type="text" id="101" class="form-control"
															name="qnty_mixture" ng-model="qnty_mixture" ng-init="qnty_mixture='1'" 
															ng-keypress="mainOperation($event,101)" autocomplete="off" />
														  </td> 
											  </tr>
															
												<tr><td class="srscell-head-divider" colspan="8">Raw Material Details</td></tr>
						  </table>										
											
							<table class="table table-bordered table-striped" >
						 <tr><td  align="center" colspan="7"><span class="style2 activeTR">{{savemsg}}</span></td></tr>
							
						<tr  bgcolor="#CCCCCC">
							<td  align="left" >Enter Bar Code</td> 
							<td  align="left" >Product</td> 
							<td width="144">Batch</td> 
							<td  align="left">Qnty</td> 
							<td  align="left">Sale Rate </td> 
							<td>Disc 1 %</td> 
							<td>Disc 2 %</td> 	
						</tr>	
						
						<tr>
						
							<td  align="left" >
							<input type="text" id="102" class="form-control" name="barcodemix" ng-model="barcodemix" 
							 ng-change="barcode_value('barcodemix',$event)" ng-keypress="mainOperation($event,102)" autocomplete="off" />
												 
							<!--<input type="text" id="6" class="form-control" name="barcode" ng-model="barcode" 
							 ng-change="barcode_value('barcode',$event)" ng-keypress="mainOperation($event,6)"/>-->				  </td> 	
							 
							<td  align="left" > 
																		 
							<input id="103" autofocus type="text" 
							 name="product_id_name_mixer"   
							 placeholder="Select Product"  
							 ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							ng-model="product_id_name_mixer" 
							ng-change="search('product_id_name_mixer')" 
							ng-focus="search('product_id_name_mixer')"
							class="form-control" ng-keypress="mainOperation($event,103)" autocomplete="off" />
							</td> 
							 
							<td width="144">									
							 <input id="104" autofocus type="text" 
							 name="batchno"   placeholder="Select batch"  	
							 ng-keydown="checkKeyDown_batch($event)" 
							 ng-keyup="checkKeyUp_batch($event)"			
							 ng-model="batchno" 
							 ng-change="search_batch('batchno')" 
							 ng-focus="search('batchno')"
							 class="form-control" ng-keypress="mainOperation($event,104)" autocomplete="off" />
						  </td> 
							 
							
							 
							 <td width="144"> 
							   <input type="text" id="105" class="form-control"
							 name="qnty2" ng-model="qnty" ng-keypress="mainOperation($event,105)" autocomplete="off" /><br />Available : {{AVAILABLE_QTY}}
							 </td> 
							  <td width="144"> 
						    <input type="text" id="106" class="form-control"
							name="rate" ng-model="rate" ng-keypress="mainOperation($event,106)" autocomplete="off" /></td> 
							
							<td width="144"> <input type="text" id="107" class="form-control"
							name="disc_per" ng-model="disc_per" ng-keypress="mainOperation($event,107)" autocomplete="off" /></td>
							
							<td width="144"> <input type="text" id="108" class="form-control"
							name="disc_per2" ng-model="disc_per2" ng-keypress="mainOperation($event,108)" autocomplete="off" /></td>
						</tr>	
						</table>
						
							<table class="table table-bordered table-striped" >									
							<tr  bgcolor="#CCCCCC">
								<td  align="left">Synonym</td>
								<td  align="left">Label print?(Y/N)</td>
								<td >MRP</td> 
								<td width="145">Exp</td> 
								<td width="145">Mfg</td> 									
								<td>Tax %</td> 
							</tr>	
							
							<tr>
								
								 <td > <input type="text" id="109"  class="form-control"
									 name="Synonym" ng-model="Synonym" ng-keypress="mainOperation($event,109)" autocomplete="off" /></td>
									 
									   <td > <input type="text" id="110" class="form-control"
									 name="label_print" ng-model="label_print" ng-keypress="mainOperation($event,110)" autocomplete="off" /></td>
								
								<td > <input type="text" id="111" class="form-control" name="mrp" ng-model="mrp" 
								ng-keypress="mainOperation($event,111)" autocomplete="off" /></td> 
								<td width="145"> 
								<input type="text" id="112" class="form-control" name="exp_monyr" 
								ng-model="exp_monyr" ng-keypress="mainOperation($event,112)" autocomplete="off" /></td> 
								
								<td width="145"> 
								<input type="text" id="113" class="form-control" name="mfg_monyr" 
								ng-model="mfg_monyr" ng-keypress="mainOperation($event,113)" autocomplete="off" /></td> 
								
								<input type="hidden" id="ptr" class="form-control" name="ptr" ng-model="ptr" />
								<input type="hidden" id="srate" class="form-control" name="srate" ng-model="srate"/>									
								
								<td > <input type="text" id="114" class="form-control" name="tax_per" 
								ng-model="tax_per" ng-keypress="mainOperation($event,114)" autocomplete="off" /></td> 									 
								<td   align="center" >
								<div ng-if="product_profit === true">								
													<button type="button" class="btn btn-danger" id="Save" name="Save" 
													ng-click="get_set_value('','','ADDMIXTURE')">Submit</button>
								</div>
								
							  </td>		
							</tr>
						</table>		
											
											
							<div ng-if="searchelement === 'product_id_name_mixer'">		
							<div >						
							<table class="table ">	
							<thead >			
							<tr>
							<th width="100">Product Name</th>
							<th width="100">Quantity Available</th>
							</tr>
							</thead>
							
							<tbody >				
							<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
							ng-click="AssignValueAndHide($index)" style="overflow:scroll">
								<td >{{suggestion.name}}</td>
								<td >{{suggestion.available_qnty}}</td>
							</tr>
							</tbody>
							</table>
							</div>
							</div>
							
							<div ng-if="searchelement === 'batchno'">	
							<table class="table">	
								<tr>	
										<td colspan="8">
											 <table class="table" >
											<tr bgcolor="#CCCCCC">					
												<td>Batch No</td>
												<td>Rack No</td>
												<td>Available Qty</td>
												<td>Rate</td>
												<td>MRP</td>					
												<td>Exp</td>
												<td>MFG</td>
											</tr>
														
											<tr ng-repeat="suggestion in suggestions 	track by $index"  
											ng-class="{activeTR : selectedIndex == $index}" ng-click="AssignValueAndHide($index)"  >					
												<td>{{suggestion.batchno}}</td>
												<td>{{suggestion.rackno}}</td>
												<td>{{suggestion.AVAILABLE_QTY}}</td>
												<td>{{suggestion.rate}}</td>
												<td>{{suggestion.mrp}}</td>
												<td>{{suggestion.exp_monyr}}</td>
												<td>{{suggestion.mfg_monyr}}</td>
											</tr>
										</table>
										</td>
							  </tr>							
							</table>	
							</div>	
													  
							<table  class="table table-bordered table-striped" >
										
												<thead   style="background-color:#99CC00">
													<tr>
													<td   align="left">PRODUCT</td> 
													<td   align="left">BATCH</td> 
													<td   align="left">QNTY</td> 
													<td   align="left">RATE</td> 							 							
													<td  align="left">EXP</td> 
													<td  align="left">MFG</td>
													<td   align="left">EDIT</td>
													<td   align="left">DELETE</td>
													</tr>
												</thead>
											   
											   <tbody>
													
													<tr ng-repeat="Trandtl in listOfDetails_mix">
														<td  align="left">{{Trandtl.product_id_name}}</td> 
														<td  align="left">{{Trandtl.batchno}}</td> 		
														<td  align="left">{{Trandtl.qnty}}</td>
														<td  align="left">{{Trandtl.rate}}</td>	
														<td  align="left">{{Trandtl.exp_monyr}}</td>	
														<td  align="left">{{Trandtl.mfg_monyr}}</td>	
																			
														<td  align="left" >
														 <button class="btn-block btn-info" ng-click="view_dtl(Trandtl.id,'MIXTURE')">Edit</button>
														</td>
														
														<td  align="left">
														<button class="btn-block btn-info" ng-click="delete_product(Trandtl.id)"
														onClick="return confirm('Do you want to Delete This Product ?');" >Delete</button>
														</td> 
													</tr>			
											 </tbody>
					  </table>   
				
				</div>
									
					    </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"
							ng-click="get_set_value('','','RESET_MIXER_PAGE')" >Close</button>
							<!--<button type="button" class="btn btn-primary">Save changes</button>-->
						  </div>
				   </div><!-- /.modal-content -->
			  </div>
			  
			</div>
							
		<!--MIXTURE SALE SECTION END-->
		
		
		
				
		<!--REGULAR SALE SECTION START-->	
					
														
			<table class="table table-bordered table-striped" >
			<tr>
					<td  align="center" colspan="2"  class="activeTR"><strong>SALE ENTRY</strong></td> 
					<td  align="left" colspan="4"><strong>Shortcut Keys</strong> :
					 New Entry (<strong>F7</strong>) | Final Submit(<strong>F8</strong>) | New Mixer(<strong>F9</strong>) |  Print Invoice(<strong>F10</strong>) | 
					 Print POS(<strong>F11</strong>) |
					</td> 	
			</tr>	
			
							<tr>
									<td  align="left" colspan="2">Enter Bar Code of Bill </td> 
									<td  align="left" colspan="4">
									<input type="text" id="billbarcode" class="form-control"
									name="billbarcode" ng-model="billbarcode" 
									ng-keypress="barcode_value('billbarcode',$event)" autocomplete="off" />
									</td> 	
							</tr>	
						   
							<tr>
								<td  align="left">Invoice No</td> 
								<td >Date</td> 
								<td  align="left">Party</td> 
								<td >Bill Type </td> 
								<td  align="left">Ref Doctor</td> 
								<td >PSR</td> 
							</tr>
							
							<tr><!---->
								<td><input type="text" id="invoice_no" class="form-control"  name="invoice_no" ng-model="invoice_no" /></td> 
								<td><input type="text" id="1" class="form-control" name="invoice_date" ng-model="invoice_date" 
								ng-keypress="mainOperation($event,1)" autocomplete="off" /></td> 
								<td>		 
					<input id="2" autofocus type="text" name="tbl_party_id_name"   placeholder="Select Party"  
					ng-keydown="checkKeyDown($event)" ng-keyup="checkKeyUp($event)" ng-model="tbl_party_id_name" 
					ng-change="search('tbl_party_id_name')" 
					ng-focus="search('tbl_party_id_name')" class="form-control" 
					ng-keypress="mainOperation($event,2)" autocomplete="off"/>	
							  </td> 
							  
						  <td>
							 <input id="3" autofocus type="text" 
							name="BILL_TYPE"   placeholder="Select Bill Type"  
							ng-keydown="checkKeyDown($event)" ng-keyup="checkKeyUp($event)" 
							ng-model="BILL_TYPE" 
							ng-change="search('BILL_TYPE')" 
							ng-focus="search('BILL_TYPE')"
							ng-keypress="mainOperation($event,3)" 
							class="form-control" autocomplete="off" />
						  </td> 
								
								
		   						<td>
									<input id="4" autofocus type="text" 
									name="doctor_ledger_id_name"   placeholder="Select Doctor"  
									ng-keydown="checkKeyDown($event)" ng-keyup="checkKeyUp($event)" 
									ng-model="doctor_ledger_id_name" 
									ng-change="search('doctor_ledger_id_name')"
									ng-focus="search('doctor_ledger_id_name')"
									ng-keypress="mainOperation($event,4)" class="form-control" autocomplete="off"/>
							   </td>
							   
							   <td>
							   
									<input id="5" autofocus type="text" 
									name="hq_id_name"   placeholder="Select CSR"  
									ng-keydown="checkKeyDown($event)" ng-keyup="checkKeyUp($event)" 
									ng-model="hq_id_name" 
									ng-change="search('hq_id_name')" 
									ng-focus="search('hq_id_name')"
									ng-keypress="mainOperation($event,5)" 
									class="form-control" autocomplete="off"/>
								
							  </td> 
							 
								
							</tr>
							
							<tr>							
								<td  align="left">Order No</td> 
								<td ><input type="text" id="6" class="form-control" name="challan_no"  ng-model="challan_no" ng-keypress="mainOperation($event,6)"/>
								</td>
								
								<td  align="left">Date</td> 
								<td ><input type="text" id="7" class="form-control" name="challan_date"  ng-model="challan_date" ng-keypress="mainOperation($event,7)"/>
								</td> 
							</tr>
							
							
							
							
							
	  </table>					
			
			<table class="table table-bordered table-striped" >
							
						 <!-- <tr>
							<td  align="left" colspan="3">Enter Bar Code</td> 
							<td  align="left" colspan="4">
							<input type="text" id="barcode" class="form-control"
								 name="barcode" ng-model="barcode" 
								 ng-change="barcode_value('barcode',$event)" /></td> 	
						 </tr>	-->
						 
						 
							 <tr><td  align="center" colspan="7">
							 
							 <div ng-if="spiner === 'ON'">
								 <spinner-loader sl-visible="true" sl-size="large"></spinner-loader>
							 </div>
							 <div ng-if="spiner != 'ON'">
							 	<span class="style2 activeTR">{{savemsg}}</span>							 </div>
							
							 </td></tr>
								
							<tr  bgcolor="#CCCCCC">
								<td  align="left" >Enter Bar Code</td> 
								<td  align="left" >Product</td> 
								<td width="144">Batch</td> 
								<td  align="left">Qnty</td> 
								<td  align="left">Sale Rate </td> 
								<td>Disc 1 %</td> 
								<td>Disc 2 %</td> 	
							</tr>	
							
							<tr>
							
							  <td  align="left" ><input type="text" id="8" class="form-control" name="barcode" ng-model="barcode" 
								 ng-change="barcode_value('barcode',$event)" ng-keypress="mainOperation($event,8)"/></td> 	
								 
								<td  align="left" > 	
																			 
								 <input id="9" autofocus type="text" 
								 name="product_id_name"   placeholder="Select Product"  
								 ng-keydown="checkKeyDown($event)" 
								ng-keyup="checkKeyUp($event)" ng-model="product_id_name" 
								ng-change="search('product_id_name')" 
								ng-focus="search('product_id_name')"
								 class="form-control" ng-keypress="mainOperation($event,9)" autocomplete="off"/></td> 
								 
								<td width="144">									
								<!-- <input id="10" autofocus type="text" 
								 name="batchno"   placeholder="Select batch"  	
								 ng-keydown="checkKeyDown_batch($event)" 
								 ng-keyup="checkKeyUp_batch($event)"			
								 ng-model="batchno" ng-change="search_batch('batchno')" 
								 class="form-control" ng-keypress="mainOperation($event,10)"/>-->	
								 
								  <input id="10" autofocus type="text" 
								 name="batchno"   placeholder="Select batch"  	
								 ng-keydown="checkKeyDown($event)" 
								 ng-keyup="checkKeyUp($event)"			
								 ng-model="batchno" 
								 ng-change="search('batchno')" 
								 ng-focus="search('batchno')"
								 class="form-control" 
								 ng-keypress="mainOperation($event,10)" autocomplete="off"/>
								 
								 </td> 
								 
								<td  width="144"> <input type="text" id="11" class="form-control"
								 name="qnty" ng-model="qnty" ng-keypress="mainOperation($event,11)"/><br />Available : {{AVAILABLE_QTY}}</td> 
								 
								 <td width="144"> 
								 <input type="text" id="12" class="form-control"
								name="rate" ng-model="rate" ng-keypress="mainOperation($event,12)" 
								ng-change="previous_transaction_details(product_id,batchno,'PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION')"/></td> 
								
								<td width="144"> <input type="text" id="13" class="form-control"
								name="disc_per" ng-model="disc_per" 
								ng-keypress="mainOperation($event,13)"
								ng-change="previous_transaction_details(product_id,batchno,'PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION')"/></td>
								
								<td width="144"> <input type="text" id="14" class="form-control"
								name="disc_per2" ng-model="disc_per2" ng-keypress="mainOperation($event,14)"
								ng-change="previous_transaction_details(product_id,batchno,'PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION')" /></td>
							</tr>	
			</table>
			
			<table class="table table-bordered table-striped" >									
				<tr  bgcolor="#CCCCCC">
					<td  align="left">Synonym</td>
					<td  align="left">Label print?(Y/N)</td>
					<td >MRP</td> 
					<td width="145">Exp</td> 
					<td width="145">Mfg</td> 									
					<td>Tax %</td> 
				</tr>	
				
				<tr>
					
					 <td > <input type="text" id="15"  class="form-control"
						 name="Synonym" ng-model="Synonym" ng-keypress="mainOperation($event,15)"/></td>
						 
						   <td > <input type="text" id="16" class="form-control"
						 name="label_print" ng-model="label_print" ng-keypress="mainOperation($event,16)" /></td>
					
					<td > <input type="text" id="17" class="form-control" name="mrp" ng-model="mrp" ng-keypress="mainOperation($event,17)" /></td> 
					<td width="145"> 
					<input type="text" id="18" class="form-control" name="exp_monyr" ng-model="exp_monyr" ng-keypress="mainOperation($event,18)"/></td> 
					
					<td width="145"> 
					<input type="text" id="19" class="form-control" name="mfg_monyr" ng-model="mfg_monyr" ng-keypress="mainOperation($event,19)"/></td> 
					
					<input type="hidden" id="ptr" class="form-control" name="ptr" ng-model="ptr" />
					<input type="hidden" id="srate" class="form-control" name="srate" ng-model="srate"/>									
					
					<td > <input type="text" id="20" class="form-control" name="tax_per" ng-model="tax_per" ng-keypress="mainOperation($event,20)"/></td> 									 
				</tr>
			</table>		
			
			
					
						
					<div ng-if="searchelement === 'product_id_name'">
					<div class="row header" >
						<div class="col-md-6">Product Name</div>
						<div class="col-md-6">Quantity Available</div>
					</div>	
					
					<div   class="ex1">
						<div class="row" ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
								ng-click="AssignValueAndHide($index)" >
							<div class="col-md-6">{{suggestion.name}}</div>
							<div class="col-md-6">{{suggestion.available_qnty}}</div>
						</div>	
					</div>
					
					</div>	
									
					<!--		<table class="table">	
							<thead >			
							<tr>
							<th width="100">Product Name</th>
							<th width="100">Quantity Available</th>
							</tr>
							</thead>
							
							<tbody >				
							<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
							ng-click="AssignValueAndHide($index)" >
								<td >{{suggestion.name}}</td>
								<td >{{suggestion.available_qnty}}</td>
							</tr>
							</tbody>
							</table>-->
					
							
		<!--	<div ng-if="searchelement === 'product_id_name'">	
							
			<table class="table">				
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
			</div>-->
			
			<div ng-if="searchelement === 'tbl_party_id_name'">								
			<table class="table">				
			<tr><th>Party Name</th></tr>				
			<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
			ng-click="AssignValueAndHide($index)" style="overflow:scroll">
				<td >{{suggestion.name}}</td>
			</tr>
			</table>
			</div>
			<div ng-if="searchelement === 'hq_id_name'">								
			<table class="table">				
			<tr><th>CSR Name</th></tr>				
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
			
		
			
			<div ng-if="searchelement === 'BILL_TYPE'">								
			<table class="table">				
			<tr><th>Bill Type</th></tr>				
			<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
			ng-click="AssignValueAndHide($index)" style="overflow:scroll">
				<td >{{suggestion.name}}</td>
			</tr>
			</table>
			</div>
			
			<div ng-if="searchelement === 'batchno'">								
			<table class="table">				
										
				<tr>	
						<td colspan="8">
							 <table class="table" >
							<tr bgcolor="#CCCCCC">					
								<td>Batch No</td>
								<td>Rack No</td>
								<td>Available Qty</td>
								<td>Rate</td>
								<td>MRP</td>					
								<td>Exp</td>
								<td>MFG</td>
							</tr>
										
							<tr ng-repeat="suggestion in suggestions 	track by $index"  
							ng-class="{activeTR : selectedIndex == $index}"
							ng-click="AssignValueAndHide($index)"  >					
								<td>{{suggestion.batchno}}</td>
								<td>{{suggestion.rackno}}</td>
								<td>{{suggestion.AVAILABLE_QTY}}</td>
								<td>{{suggestion.rate}}</td>
								<td>{{suggestion.mrp}}</td>
								<td>{{suggestion.exp_monyr}}</td>
								<td>{{suggestion.mfg_monyr}}</td>
							</tr>
						</table>
						</td>
			  </tr>							
			</table>	
			</div>
			
		
								
			<table class="table table-bordered table-striped" >								
			  <tr>
					<td width="59">Comment</td>
					<td    colspan="2">
					<textarea id="19" name="comment" cols="20" rows="3"
					ng-model="comment" class="form-control"></textarea>
					</td> 
					
					<td width="213" colspan="3">
					<!--<div ng-if="product_profit === true">-->
						<button type="button" class="btn btn-danger" id="Save" name="Save" 
						ng-click="get_set_value('','','DRCRCHECKING')">Submit</button>
					<!--</div>-->
					
					<a data-toggle="modal" data-target="#shortModal" 
					class="btn btn-primary"><i class="fa fa-pencil"></i> NEW MIXER</a>
					
					
					<button type="button" class="btn btn-danger" id="Save" name="Save" 
					ng-click="submit_print()" onclick="return confirm('Do you want to Save ?');">Final submit</button>
					
					<!--<a data-toggle="modal" data-target="#shortModal_product" 
					class="btn btn-primary"><i class="fa fa-pencil"></i> Product Master</a>-->
					
					<!-- <button ng-click="print_invoice('INVOICE')" 
					class="btn btn-primary">Print</button>-->
					
					
				</td> 
					
					
			  </tr>	
	  </table>
			
			<!--REGULAR SALE SECTION END-->				
				
				
					
			<!--	</form>-->
			
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
						<td   align="left">RATE</td> 		
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
							<td  align="left">{{Trandtl.product_id_name}}
							<div ng-if="Trandtl.PRODUCT_TYPE == 'MIXTURE' " >
							({{Trandtl.mixer_name}})						
							</div>							
							</td> 
							
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
							<td  align="left" >
							
							<div ng-if="Trandtl.PRODUCT_TYPE == 'FINISH' " >
							 <button class="btn-block btn-info" ng-click="view_dtl(Trandtl.id,'FINISH')" >Edit</button>
							</div>
							
							<div ng-if="Trandtl.PRODUCT_TYPE == 'MIXTURE' " >
						 <a data-toggle="modal" data-target="#shortModal" > 
							<button class="btn-block btn-info" 
							ng-click="get_set_value(Trandtl.id,'','VIEWDTLMIX')" >Edit</button>
						 </a>
							</div>
							
							<td  align="left">
							<button class="btn-block btn-info" ng-click="delete_product(Trandtl.id)" onClick="return confirm('Do you want to Delete This Product ?');"
								 >Delete</button>
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
						<button type="button" class="btn btn-block btn-success" name="Save" 
						ng-click="GetAllList(startdate,enddate)">Submit</button>
						
						 <button ng-click="print_invoice('INVOICE')" 
						 class="btn btn-block btn-success">Print Invoice</button>
						
						<button ng-click="print_invoice('INVOICE_POS')" 
						 class="btn btn-block btn-success">Print POS Invoice</button>				
						 
						 <button type="button" class="btn btn-block btn-success" 
							ng-click="print_label(id_header,'LABEL')" >Print Label</button>
					
					<button type="button" class="btn btn-success" 
					ng-click="get_set_value('','','NEWENTRY')">New Sale Entry </button>
					
					<!--<button ng-click="print_invoice('TEST_INVOICE')"  class="btn  btn-success">Test Invoice</button>-->
					
				
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
								<td  align="left"><button class="btn-block btn-info" ng-click="get_set_value(dtl.id_header,'','VIEWALLVALUE')" >Edit</button>
								<td  align="left">
								<button class="btn-block btn-info" ng-click="delete_invoice(dtl.id_header)"
								onClick="return confirm('Do you want to Delete This Invoice ?');" >Delete</button>
								
								</td> 
							</tr>			
					 </tbody>
	  </table>   
	
	
	
	</div>

</div>

<script>
  
  $("#1").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#1").change(function() {
  var  trandate = $('#1').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#1").val(trandate);
 });
 
 $("#7").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#7").change(function() {
  var  trandate = $('#7').val();
  trandate=
  trandate.substring(6, 10)+'-'+
  trandate.substring(0, 2)+'-'+
  trandate.substring(3, 5);
  $("#7").val(trandate);
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
  Inputmask.extendAliases({
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
$("#exp_monyr_mixture").inputmask("yyyy-mm");

  
</script>		
