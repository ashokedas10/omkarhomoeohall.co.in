<!--<link rel="stylesheet" 
href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>-->


<style>
.events{margin: 1px 1px;}

.bottom_border {
    border-bottom: double;
}

input[type=text] {
  border: none;
  background-color: none;
  outline: 0;
  height: 25px;
  /*font-size: 25px;
  width: 300px;
  height: 30px;*/
}

input[type=text]:focus {
  border: none;
  background-color:#CCCCCC;
  outline: 0;
   height: 25px;
  /*width: 300px;
  height: 30px;*/
}

</style>
<style type="text/css">
<!--
.style2 {
	color:#FF3366;
	font-weight: bold;
	font-size:18px;
}
-->
</style>


<div ng-app="Accounts" ng-controller="AccountsTransaction" ng-click="hideMenu($event)" 
ng-init="initarray('CONTRA')" id="myBody">

<div class="panel panel-primary" >	
	 <div class="panel-body" align="center" style="background-color:#99CC00">
	  <span class="blink_me style2">CONTRA ENTRY</span><br />
	  <span class="blink_me style2">{{savemsg}}</span>
	  </div>  
</div>

		
<form id="Transaction_form" name="Transaction_form" >
				
			<div class="panel panel-primary"  >
			<div class="panel-body" > 
				<div  class="form-row" >	 
					<div class="form-row col-md-9" > 
				
				<div  class="form-row" >	 
					<div class="form-row col-md-3" >No</div>
					<div class="form-row col-md-3" >
					<input type="text" id="tran_code"
						ng-model="FormInputArray[0].tran_code" 
						  class="form-control" readonly="">
					</div>
					<div class="form-row col-md-3" >Date</div>
					<div class="form-row col-md-3" >
						<input type="text" id="tran_date"   
						 ng-model="FormInputArray[0].tran_date" 
						 ng-keypress="mainOperation($event,'tran_date',0,0)"
						 class="form-control" >
					</div>
				</div>
				<div class="events">&nbsp;</div>	
				
				<div  class="form-row " >	
				<div class="form-row col-md-2 bg-yellow" >Type</div>
				<div class="form-row col-md-6 bg-yellow" >Particular</div>
				<div class="form-row col-md-2 bg-yellow" 
				style="text-align:right;" >Debit</div>
				<div class="form-row col-md-2 bg-yellow" 
				style="text-align:right;">Credit</div>
				</div> 	
				<div class="events">&nbsp;</div>

				<div  ng-repeat="i in [0,maxloopvalue] | toRange">	
				
				<div  class="form-row" >		
				
					<div class="col-md-2" >
						<input type="hidden" ng-model="FormInputArray[i].ledger_account_id">
						<input type="text" id="{{'CRDR_TYPE-'+i}}"
						ng-model="FormInputArray[i].CRDR_TYPE" select-on-click 
						placeholder="C/D" 
						ng-keypress="mainOperation($event,'CRDR_TYPE',i,0)" 
						class="form-control" style="text-align:left;">
					</div>
				
					<div ng-if="FormInputArray[i].CRDR_TYPE === 'DR'">

						<div class="col-md-6" >
							<input id="{{'ledger_account_name-'+i}}" autofocus type="text" 
							placeholder="Select A/c"  
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							select-on-click
							ng-model="FormInputArray[i].ledger_account_name" 							
							ng-keypress="mainOperation($event,'ledger_account_name',
							i,0,'searchItem')" 
							class="form-control" />
						</div>
						<div class="col-md-2" >
							<input type="text" id="{{'ledger_amount-'+i}}"
							ng-model="FormInputArray[i].ledger_amount" select-on-click 
							placeholder="AMOUNT" 
							ng-keypress="mainOperation($event,'ledger_amount',i,0)" 
							class="form-control" style="text-align:right;">
						</div>		
						<div class="form-row col-md-2" >&nbsp;</div>
					 			
					 </div>
				 
					<div ng-if="FormInputArray[i].CRDR_TYPE === 'CR'">	
					 <div class="col-md-8" >
						<input id="{{'ledger_account_name-'+i}}" autofocus type="text" 
						placeholder="Select A/c"  
						ng-keydown="checkKeyDown($event)" 
						ng-keyup="checkKeyUp($event)" 
						select-on-click
						ng-model="FormInputArray[i].ledger_account_name" 
						ng-keypress="mainOperation($event,'ledger_account_name',
							i,0,'searchItem')" 
						class="form-control" />
					</div>
					 <div class="col-md-2" >
						 <input type="text" id="{{'ledger_amount-'+i}}"
						ng-model="FormInputArray[i].ledger_amount" select-on-click 
						placeholder="AMOUNT " 
						ng-keypress="mainOperation($event,'ledger_amount',i,0)" 
						 class="form-control" style="text-align:right;">
					 </div>
				 </div>			
				</div>
				
				<!-- BILL OR BANK DETAILS ENTRY-->
				
				<div ng-if="FormInputArray[i].detailtype === 'PURCHASE_BILL' || 
				FormInputArray[i].detailtype === 'SALE_BILL'">
				
				 <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Bill No/Ref No </th>
                    <th>Amount</th>                    
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="bil in 
				  [0,FormInputArray[i]['details'].length-1] | toRange">
                    <td>
						<input type="hidden" 
						ng-model="FormInputArray[i]['details'][bil].bill_id">						
							
							<input type="text" id="{{'BILLNO-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].BILL_INSTRUMENT_NO"
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							 select-on-click 
							placeholder="Bill No" 
							ng-keypress=
							"mainOperation($event,'BILLNO',i,bil,'searchItem')" 
							class="form-control" style="text-align:left;">
					</td>
							
                    <td>
							<input type="text" id="{{'BILLAMT-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].AMOUNT"							
							 select-on-click 
							placeholder="Rcv Amt" 
							ng-keypress="mainOperation($event,'BILLAMT',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					
                  </tr>
                 
                </tbody>
              </table>					
				
				</div>
				
				<div ng-if="FormInputArray[i].detailtype === 'BANK'">
					
					<table class="table table-striped">
					<thead>
					  <tr>
						<th>Inst No</th>
						<th>Date</th>    
						<th>Bank</th>
						<th>Branch</th>    
						<th>Amount</th>
					  </tr>
					</thead>
					<tbody>
					  <tr  ng-repeat="bnk in [0,FormInputArray[i]['details'].length-1] 
					| toRange">
						<td>
							<input type="text" id="{{'BILL_INSTRUMENT_NO-'+i+bnk}}"
								ng-model=
								"FormInputArray[i]['details'][bnk].BILL_INSTRUMENT_NO"
								 select-on-click 
								placeholder="Inst No" 
								ng-keypress="mainOperation($event,'BILL_INSTRUMENT_NO',i,bnk)" 
								class="form-control" style="text-align:left;">
						</td>
								
						<td>
								<input type="text" id="{{'CHQDATE-'+i+bnk}}"
								ng-model="FormInputArray[i]['details'][bnk].CHQDATE"
								 select-on-click 
								placeholder="Inst Date" 
								ng-keypress="mainOperation($event,'CHQDATE',i,bnk)" 
								class="form-control" style="text-align:left;">
						</td>
						
						<td>
								<input type="text" id="{{'BANKNAME-'+i+bnk}}"
								ng-model="FormInputArray[i]['details'][bnk].BANKNAME"
								 select-on-click 
								placeholder="Bank Name" 
								ng-keypress="mainOperation($event,'BANKNAME',i,bnk)" 
								class="form-control" style="text-align:left;">
						</td>
						
						<td>
								<input type="text" id="{{'BRANCH-'+i+bnk}}"
								ng-model="FormInputArray[i]['details'][bnk].BRANCH"
								 select-on-click 
								placeholder="Branch" 
								ng-keypress="mainOperation($event,'BRANCH',i,bnk)" 
								class="form-control" style="text-align:left;">
						</td>
						
						<td>
								<input type="text" id="{{'AMOUNT-'+i+bnk}}"
								ng-model="FormInputArray[i]['details'][bnk].AMOUNT"
								 select-on-click 
								placeholder="Amount" 
								ng-keypress="mainOperation($event,'AMOUNT',i,bnk)" 
								class="form-control" style="text-align:right;">
						</td>
						
					  </tr>
					 
					</tbody>
				  </table>					
								
				</div>
			
				<div ng-if="FormInputArray[i].detailtype === 'TT_FUEL_EXP' ">
								
				  <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Truck No</th>
                    <th style="text-align:right;">Dsl Rate</th>   
					<th style="text-align:right;">Dsl Qnty(Lt)</th>    
					<th style="text-align:right;">Cash Amount</th>  
					<th style="text-align:right;">Total Amount</th>                      
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="bil in [0,FormInputArray[i]['details'].length-1] 
				| toRange">
                    <td>					
					<input type="hidden" 
						ng-model="FormInputArray[i]['details'][bil].truck_id">						
							
							<input type="text" id="{{'truck_no-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].truck_no"
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							 select-on-click 
							placeholder="Truck No" 
							ng-keypress=
							"mainOperation($event,'truck_no',i,bil,'searchItem')" 
							class="form-control" style="text-align:left;">
					</td>
							
                    <td>
							<input type="text" id="{{'dsl_rate-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].dsl_rate"							
							 select-on-click 
							placeholder="Dsl Rate" 
							ng-keypress="mainOperation($event,'dsl_rate',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					 <td>
							<input type="text" id="{{'dsl_qnty-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].dsl_qnty"							
							 select-on-click 
							placeholder="Dsl Qnty" 
							ng-keypress="mainOperation($event,'dsl_qnty',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					 <td>
							<input type="text" id="{{'trip_cashamt-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].trip_cashamt"							
							 select-on-click 
							placeholder="Cash Amount" 
							ng-keypress="mainOperation($event,'trip_cashamt',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					 <td>
							<input type="text" id="{{'AMOUNT-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].AMOUNT"							
							 select-on-click 
							placeholder="Total Amount" 
							ng-keypress="mainOperation($event,'AMOUNT',i,bil)" 
							class="form-control" style="text-align:right;" readonly="">
					</td>
					
                  </tr>
                 
                </tbody>
              </table>					
				
				</div>
				
				<div ng-if="FormInputArray[i].detailtype === 'TT_OTHER_EXP' ">
								
				  <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Truck No</th>
                   	<th style="text-align:right;">Amount</th>                      
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="bil in [0,FormInputArray[i]['details'].length-1] 
				| toRange">
                    <td><input type="hidden" 
						ng-model="FormInputArray[i]['details'][bil].truck_id">						
							
							<input type="text" id="{{'truck_no-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].truck_no"
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							 select-on-click 
							placeholder="Truck No" 
							ng-keypress=
							"mainOperation($event,'truck_no',i,bil,'searchItem')" 
							class="form-control" style="text-align:left;">
					</td>
                  
					 <td>
							<input type="text" id="{{'AMOUNT-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].AMOUNT"							
							 select-on-click 
							placeholder="Total Amount" 
							ng-keypress="mainOperation($event,'AMOUNT',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					
                  </tr>
                 
                </tbody>
              </table>					
				
				</div>
				
				<div ng-if="FormInputArray[i].detailtype === 'STAFF' ">
								
				  <table class="table table-striped">
                <thead>
                  <tr>
				     <th>Staff Name</th>
                    <th>Truck No</th>					 
                   	<th style="text-align:right;">Amount</th>                      
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="bil in [0,FormInputArray[i]['details'].length-1] 
				| toRange">
				
					<td>
							<input type="text" id="{{'EMPLOYEE_NAME-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].EMPLOYEE_NAME"
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							 select-on-click 
							placeholder="Staff Name" 
							ng-keypress=
							"mainOperation($event,'EMPLOYEE_NAME',i,bil,'searchItem')" 
							class="form-control" style="text-align:left;">
					</td>
					
                    <td><input type="hidden" 
						ng-model="FormInputArray[i]['details'][bil].truck_id">	
						
						<input type="hidden" 
						ng-model="FormInputArray[i]['details'][bil].employee_id">						
						
							<input type="text" id="{{'truck_no-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].truck_no"
							ng-keydown="checkKeyDown($event)" 
							ng-keyup="checkKeyUp($event)" 
							 select-on-click 
							placeholder="Truck No" 
							ng-keypress=
							"mainOperation($event,'truck_no',i,bil,'searchItem')" 
							class="form-control" style="text-align:left;">
					</td>
					
					
                  
					 <td>
							<input type="text" id="{{'AMOUNT-'+i+bil}}"
							ng-model="FormInputArray[i]['details'][bil].AMOUNT"							
							 select-on-click 
							placeholder="Total Amount" 
							ng-keypress="mainOperation($event,'AMOUNT',i,bil)" 
							class="form-control" style="text-align:right;">
					</td>
					
                  </tr>
                 
                </tbody>
              </table>					
				
				</div>
				
				
				<div class="bottom_border">&nbsp;</div>
				<div class="events">&nbsp;</div>
				
					
				</div> 		
				
				
					
				<div class="events">&nbsp;</div>
				
				<div  class="form-row " >	
				<div class="form-row col-md-8 bg-yellow" >TOTAL</div>
				<div class="form-row col-md-2 bg-yellow" 
				style="text-align:right;" >{{TotalDrAmt}}</div>
				<div class="form-row col-md-2 bg-yellow" 
				style="text-align:right;">{{TotalCrAmt}}</div>
				</div> 	
				
				<div class="events">&nbsp;</div>
							
			
				<div  class="form-row" >	 
						<div class="form-row col-md-3" >
						Comment
						</div>
						<div class="form-row col-md-9">
						 <textarea  cols="50" rows="3"
						  class="form-control" 
						  ng-model="FormInputArray[0].transaction_details">
						  </textarea>
						</div>						
				</div>
				<div  class="form-row" >	 
						<button type="button" class="btn btn-danger" id="Save" name="Save" 
						ng-click="get_set_value('','','DRCRCHECKING')">Submit</button>	
				</div>	
					
			
			</div>
					
					 <div class="form-row col-md-3" >
						<table class="table" style="background-color:#CC9999">
							<tr><th>Search</th></tr>
							<tr ng-repeat="suggestion in suggestions track by $index" 
							ng-class="{active : selectedIndex === $index}"
							ng-click="AssignValueAndHide($index)">				
							<td >{{suggestion}}</td>					
							</tr>
						</table>
					</div>
			</div>
			</div>
			</div>
				
		</form>		
		
		
		
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
				 name="enddate" class="form-control" ng-model="enddate"> 
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
					<td width="89"  align="left">Comment </td> 
					<td width="31"  align="left">Edit</td> 
					<td width="71"  align="left">Delete</td>
					</tr>
				</thead>
			   
			   <tbody>
					
					<tr ng-repeat="dtl in ListOfTransactions">
						<td  align="left">{{dtl.tran_code}}</td> 
						<td  align="left">{{dtl.tran_date}}</td> 		
						<td  align="left">{{dtl.comment}}</td>
						
						<td  align="left"><button class="btn-block btn-info" 
						ng-click="get_set_value(dtl.id_header,'','VIEWALLVALUE')" >Edit</button>
						<td  align="left"><button class="btn-block btn-info" ng-click="delete_transaction(dtl.id_header)"
								onClick="return confirm('Do you want to Delete This Record ?');">Delete</button>
						</td> 
					</tr>			
			 </tbody>
		</table>   

</div>


<?php /*?><script src="<?php echo BASE_PATH_ADMIN;?>theme_files/angular_autocomplete/controllers/angular.min.js"></script>
<script src="<?php echo BASE_PATH_ADMIN;?>theme_files/angular_autocomplete/controllers/general_services.js"></script>
<script src="<?php echo BASE_PATH_ADMIN;?>theme_files/angular_autocomplete/controllers/ProjectController.js"></script>
<link rel="stylesheet" href="<?php echo BASE_PATH_ADMIN;?>theme_files/angular_autocomplete/css/css.css"><?php */?>

<script>
  
  
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
 
  $("#fromdate").datepicker({
   changeMonth: true,
   changeYear: true
 });
  
 $("#fromdate").change(function() {
  var  trandate = $('#fromdate').val();
  trandate=trandate.substring(6, 10)+'-'+ trandate.substring(0, 2)+'-'+ trandate.substring(3, 5);
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
	 
 $("#tran_date").inputmask("yyyy-mm-dd");
	 
</script>



