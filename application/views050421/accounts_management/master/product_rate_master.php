
<div ng-app="Accounts" ng-controller="product_rate_master" 
ng-click="hideMenu($event)" ng-init="initarray('TRIP_ENTRY')" id="myBody">


<!--<div class="container" style="width:95%">
	<div class="row" style="overflow:auto" >   
		<div class="panel panel-danger srstable"   >	
		
		<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="panel-title" id="contactLabel">Rate Master</h4>
		</div>	
		
		
		

</div>
</div>
</div>-->

<!--{{rate_list_array['header']}}-->

<div class="panel panel-primary"  style="overflow:auto">

<table  class="table"  > 
				
			<tr>
					<td  align="left" >Select Group :</td>
					<td  align="left" >
					 <select ng-model="id_header" class="form-control" 
					 ng-click="rate_list(id_header)">	<option  value="0">Select</option>
					<option ng-repeat="option in rate_list_array['group_list']" 
					value="{{option.id}}">{{option.name}}</option>
					</select></td>
			  </tr>				 
		</table>

<table   class="table"  style="overflow:auto">
												
		<tr  style="background-color:#999999">
		<td >&nbsp;</td>
		<td ng-repeat="steps in rate_list_array['header']" >	
			{{steps}}
			</td>
		</tr>
		
	<tr  ng-repeat="steps in rate_list_array['body']">
		<td >&nbsp;</td>
		<td >{{steps.POTENCY_NAME}}</td>
		<td >{{steps.PACK_NAME}}</td>
		<td >		
			<input  type="text"  placeholder="{{steps.RATE}}"  
			 ng-model="steps.RATE" autocomplete="off" style="width:70px;"/>
		</td>
		<td >		
			<input  type="text"  placeholder="{{steps.MRP}}"  
			 ng-model="steps.MRP" autocomplete="off" style="width:70px;"/>
		</td>
		
		<td ><input  type="text"  placeholder="{{steps.dose2_mrp}}"   ng-model="steps.dose2_mrp" autocomplete="off"    style="width:70px;" /></td>
		<td ><input  type="text"  placeholder="{{steps.dose2_rate}}"   ng-model="steps.dose2_rate" autocomplete="off"  style="width:70px;"/></td>
		
		<td ><input  type="text"  placeholder="{{steps.dose3_mrp}}"   ng-model="steps.dose3_mrp" autocomplete="off"    style="width:70px;"/></td>
		<td ><input  type="text"  placeholder="{{steps.dose3_rate}}"   ng-model="steps.dose3_rate" autocomplete="off"  style="width:70px;"/></td>
		
		<td ><input  type="text"  placeholder="{{steps.dose4_mrp}}"   ng-model="steps.dose4_mrp" autocomplete="off"    style="width:70px;"/></td>
		<td ><input  type="text"  placeholder="{{steps.dose4_rate}}"   ng-model="steps.dose4_rate" autocomplete="off"  style="width:70px;"/></td>
		
		<td ><input  type="text"  placeholder="{{steps.dose5_mrp}}"   ng-model="steps.dose5_mrp" autocomplete="off"  style="width:70px;"/></td>
		<td ><input  type="text"  placeholder="{{steps.dose5_rate}}"   ng-model="steps.dose5_rate" autocomplete="off" style="width:70px;"/></td>
		
	
	</tr>
	
	<tr style="background-color:#999999"><td  colspan="5" align="center">
	<button class="btn-block btn-info" ng-click="savedata()" >Save</button></td></tr>		
		
</table>

</div>


</div>