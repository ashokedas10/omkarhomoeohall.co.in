
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

	
	
	<div ng-app="Accounts" ng-controller="Product_master" >
			 
				<div class="panel panel-primary" >
				<div class="panel-body" >
					<div  class="form-row">	
						<div class="form-row col-md-12" >
											
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
							<td  align="left" >HSN Code</td> 
							<td width="192">Tax Rate</td> 
							<td width="192">Product Group</td> 
							<td width="192">Brand name</td> 
						</tr>	
						
						<tr>
							<td  align="left" > 												 
							 <input id="1" autofocus type="text" 
							 name="product_id_name"   placeholder="Select Product"  
							 ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" ng-model="product_id_name" 
							ng-change="search('product_id_name')" class="form-control" style="width:400px;" ng-keypress="mainOperation($event,1)"/>						
						  </td> 
							 
							 <td width="100"> 
							 <input type="text" id="2" class="form-control"	 style="width:100px;" name="hsncode" ng-model="hsncode" ng-keypress="mainOperation($event,2)"/>
						  </td> 
							 
							 <td  align="left">
							  <input id="3" autofocus type="text" 
							 name="tax_per"   placeholder="Select tax"  
							 ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" ng-model="tax_per" 
							ng-change="search('tax_per')" class="form-control" ng-keypress="mainOperation($event,3)"/>							 
							 </td> 
							 
							  <td  align="left">
							  <input id="4" autofocus type="text" 
							 name="group_id_name"   placeholder="Select group"  
							 ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" ng-model="group_id_name" 
							ng-change="search('group_id_name')" class="form-control" ng-keypress="mainOperation($event,4)"/>							 
							 </td> 
							 
							 <td  align="left">
							  <input id="5" autofocus type="text" 
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
				<tr ng-repeat="suggestion in suggestions track by $index" ng-class="{'activeTR': selectedIndex == $index}"				
				ng-click="AssignValueAndHide($index)" style="overflow:scroll">
					<td >{{suggestion.name}}</td>
					
				</tr>
				</table>
				<!--</div>-->
					
						</div>
						
					
					</div>
					
				</div>
				</div>
				
				
	
	</div>


