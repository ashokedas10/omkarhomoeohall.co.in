<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<style type="text/css">
<!--
.style2 {
	color: #990000;
	font-weight: bold;
	font-size:18px;
}
.input_field_hight
{
height:27px;
font-family:Arial, Helvetica, sans-serif bold;
font-size:12px;
color:#000000;
font-weight:300;
}

input:focus {
  background-color: yellow;
}

-->
</style>

<div ng-app="Accounts" ng-controller="product_master_minimum_stock" 
ng-click="hideMenu($event)" ng-init="initarray('TRIP_ENTRY')" id="myBody">



<!--<div class="panel panel-primary" >
	
    <div class="panel-body" align="center" style="background-color:#CCCCCC">  	
	  <span class="blink_me style2">{{savemsg}}</span></div>
	</div>-->
	
	
	<div class="container" style="width:95%">
	<div class="row" style="overflow:auto" >   
		<div class="panel panel-danger srstable"   >	
		
		<div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="panel-title" id="contactLabel"><span class="glyphicon glyphicon-info-sign">
				Minimum Stock Report</span>{{savemsg}}</h4>
				<!--{{FormInputArray}}-->
				
				
		</div>	
		
			<form id="Transaction_form" name="Transaction_form" >

			
				
			<table  class="table"  style="100%" cellpadding="0" cellspacing="0" border="0"> 
					
			<tr>
					
					
					<td  align="right" >Brand :</td>
					<td  align="left" >										
					<select name="select" 
						class="form-control input_field_hight" 
						ng-model="FormInputArray[0].brand_id" >
						<option  value="0">NA</option>
						<option ng-repeat="option in brand_list" 
						value="{{option.id}}">{{option.name_value}} -	{{option.name}}</option>
					</select>					
					</td>
					
					<td  align="right" >
					<button type="button" class="btn btn-danger" id="Save" 
					name="Save"  
					ng-click="view_list(FormInputArray[0].group_id,FormInputArray[0].brand_id,'')">Display</button>	
					
					
					</td>
					 
			  </tr>				 
		
		</table>
		
		<table  class="table"  style="100%" cellpadding="0" cellspacing="0" border="0"> 
		
				<tr  >
                    <td >Search : 
					
					
					
					</td>
                    <td colspan="7" >
					<input  type="text" style="text-align:left; width:200px;"
					  class="form-control input_field_hight"   ng-model="search"  
					  ng-change="view_list(FormInputArray[0].group_id,FormInputArray[0].brand_id,search)"/>
					</td>    
					
                  </tr>
				  		 
				  <tr class="bg-primary" >
				  	<th >Brand</th>  
                    <th >Product Name </th>
					<th >Avai.Qnty</th> 
					<th >Min Stock</th>
					<th >Order Qnty</th> 
					
                  </tr>
				  
				
	<?php /*?>  <tr ng-repeat="layer in  [0,FormInputArray[0].list_of_values.length-1] | filter:searchText"  style="padding-top:0px;"><?php */?>
			
	 <tr ng-repeat="(layer, data) in FormInputArray[0].list_of_values " style="padding-top:0px;">
			
		<td  >
		
		{{FormInputArray[0].list_of_values[layer].brand_name}}
		
			<!--<select name="select" 
				class="input_field_hight" 
				ng-model="FormInputArray[0].list_of_values[layer].brand_id" >
				<option  value="0">NA</option>
				<option ng-repeat="option in brand_list" 
				value="{{option.id}}">{{option.name}}</option>
			</select>-->
		</td> 
		   
		<td align="right"  >
		{{FormInputArray[0].list_of_values[layer].productname}}
		 </td> 
		 
		
		 
		
		
		
		
		 
		  <td align="right"  >
		{{FormInputArray[0].list_of_values[layer].available_qnty}}
		 </td> 
		 
		  <td align="right"  >
		  <input  type="text" style="text-align:left"	class="form-control input_field_hight" 
		 ng-model="FormInputArray[0].list_of_values[layer].minimum_stock"/>		
		 </td>   
		 
		 <!-- <td align="right"  >
		  <input  type="text" style="text-align:left"	
		  class="form-control input_field_hight" 
		  ng-model="FormInputArray[0].list_of_values[layer].exp_mmyy"/>
		 </td>   -->
		 
		 
		 
		 
	
		
		<td align="right"  >
		<input  type="text" style="text-align:left"	class="form-control input_field_hight" 
		 ng-model="FormInputArray[0].list_of_values[layer].order_qnty"/>
		 </td> 
		 
		
		 
		
		
			
		   
					
	
	  </tr>
	  
	  <tr>
	  <td   align="center" colspan="5"   >
	  
		<button type="button" class="btn btn-danger" id="Save" name="Save" 	ng-click="savedata()">
		Final Submit</button>	
		
		<button type="button" class="btn btn-primary" id="Save" name="Save" 	
		ng-click="print()">Print </button>	
		
					
	</td>
	
	<!--<button type="button" class="btn btn-primary" name="Save" ng-click="print_bill(id_header)">Print</button>-->
	
	</tr>
	</table>		
				
</form>
			
	</div></div></div>

</div>

