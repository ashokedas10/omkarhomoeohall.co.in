//CRUD VIDEO
// https://www.youtube.com/watch?v=DB-kVs76XZ4
//https://www.codeproject.com/Tips/872181/CRUD-in-Angular-js
//https://github.com/chieffancypants/angular-hotkeys/ 
//http://www.codexworld.com/angularjs-crud-operations-php-mysql/
'use strict';


var domain_name="http://localhost/Subhojit_DEPAK_BHUIYA/homeopathi/omkar_homeo/";


//var domain_name="https://omkarhomoeohall.co.in/";


var query_result_link=domain_name+"Accounts_controller/query_result/";

//************************ACCOUNT RECEIVE START*****************************************//
var app = angular.module('Accounts',['GeneralServices']);



app.directive('scrollToBottom', function($timeout, $window) {
	return {
			scope: {
					scrollToBottom: "="
			},
			restrict: 'A',
			link: function(scope, element, attr) {
					scope.$watchCollection('scrollToBottom', function(newVal) {
							if (newVal) {
									$timeout(function() {
											element[0].scrollTop =  element[0].scrollHeight;
									}, 0);
							}

					});
			}
	};
});

//************************ACCOUNT -RECEIVE,PAYMENT,JOURNAL,CONTRA START*****************************************//

app.directive('selectOnClick', ['$window', function ($window) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            element.on('blur', function () {
                if (!$window.getSelection().toString()) {
                    // Required for mobile Safari
                    this.setSelectionRange(0, this.value.length)
                }
            });
        }
    };
}]);

app.filter('toRange', function(){
	return function(input) {
	  var lowBound, highBound;
	  if (input.length == 1) {       
		lowBound = 0;
		highBound = +input[0] - 1;      
	  } else if (input.length == 2) {      
		lowBound = +input[0];
		highBound = +input[1];
	  }
	  var i = lowBound;
	  var result = [];
	  while (i <= highBound) {
		result.push(i);
		i++;
	  }
	  return result;
	}
	});
	
	app.run(['$anchorScroll', function($anchorScroll) {
		$anchorScroll.yOffset =1000;   // always scroll by 50 extra pixels
	}]);




app.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          // confirm() requires jQuery
          if(message && !confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    }
  }
]);



//DONE ALL
app.controller('retail_bill_experiment',['$scope','$rootScope','$http','$window','general_functions','$location',
function($scope,$rootScope,$http,$window,general_functions,$location)
{
	"use strict";


		var CurrentDate=new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();
		if (dt < 10) {	dt = '0' + dt;}
		if (month < 10) {month = '0' + month;}
		$scope.tran_date=year+'-' + month + '-'+dt;
		$scope.startdate=$scope.enddate=	$scope.tran_date;
		//$scope.barcodeimg=domain_name+'uploads/BILL-2.png';

		//$scope.input_id_index=0;
		// $rootScope.fld_arr=[];	

		// $rootScope.sec_indx0=0;	
		// $rootScope.sec_indx1=1;	
		// $rootScope.sec_indx2=2;	
		// $rootScope.sec_indx3=3;	
		// $rootScope.sec_indx4=4;	

	//	$rootScope.final_array=[];
		$rootScope.FormInputArray_template=[];	
		$rootScope.FormInputArray=[];	
		$rootScope.returnobject={};
		$rootScope.returnArray=[];	
		$rootScope.spiner=false;

		$rootScope.main_grid_array=[];
		$rootScope.dtlist_array=[];
		$rootScope.dtlist_total_array=[];
	
		$rootScope.product_rate_mstr_array=[];
		$rootScope.product_rates=[];

		$rootScope.test=[];
		$rootScope.all_master=[];

		$rootScope.product_master=[];
		$rootScope.potency_master=[];
		$rootScope.pack_master=[];






		$rootScope.savedone_status='SAVE_NOT_DONE';
		$rootScope.grandtotal=0;
		$scope.formname='';
		$rootScope.array_name='';

		var BaseUrl=domain_name+"Project_controller/experimental_form/";
		$rootScope.search_div_display='none';
		//block none

		//$scope.codestructure1_id=0;
		$scope.server_msg='Enter Form';
		//$rootScope.current_form_report='grn_entry';

		 $rootScope.current_form_report=localStorage.getItem("TranPageName");
		 console.log('page name :'+$rootScope.current_form_report);

		 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
		 console.log(data_save);

			//GENERAL FUNCTIONS START
			

			

			$scope.download_all_master=function(id)
			{	
				general_functions.download_all_master(BaseUrl);
				//general_functions.all_master(BaseUrl);
				//console.log('PRODUCT LIST :------------------------'+$rootScope.product_rate_mstr_array);
			}
			$scope.download_all_master(1);


			$scope.main_grid=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.main_grid($rootScope.current_form_report,'main_grid',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}
			$scope.main_grid(1);

			$scope.setTotals = function(item)
			{
				angular.forEach(item, function (values, key) 
					{ 	
						if(key=='Grand Total')
						{
							console.log(key+' '+values)	;		
							$rootScope.grandtotal =Number($rootScope.grandtotal) +Number(values);
						
						}								
					}); 
			}
			
			

			$scope.view_bill_wise_item=function(id)
			{general_functions.view_bill_wise_item(BaseUrl,id);}

			$scope.dtlist=function(id)
			{general_functions.dtlist(BaseUrl,id);}

			$scope.dtlist_total=function(id)
			{general_functions.dtlist_total(BaseUrl,id);}
			
			$scope.dtlist_view=function(indx)
			{

				//general_functions.dtlist_view(BaseUrl,id);		

				//,'PURCHASEID'
				if($rootScope.current_form_report=='issue_entry')
				{var field_list=['id','product_id','product_Synonym','batchno','rackno','qnty','exp_monyr'];}

					
				if($rootScope.current_form_report=='purchase_entry')
				{
				var field_list=['id','product_id','batchno','mrp','disc_per','rate','qnty','subtotal','exp_monyr','tax_ledger_id','rackno'];	
				}

				//id,product_id,batchno,qnty,rate,subtotal,disc_per,disc_per2,disc_amt,taxable_amt,mrp,
				//exp_monyr,mfg_monyr,tax_ledger_id,taxamt,net_amt,Synonym,label_print

				//product_id,batchno,qnty,rate,disc_per,disc_per2,Synonym,label_print,mrp,exp_monyr,mfg_monyr,tax_ledger_id

				if($rootScope.current_form_report=='invoice_entry')
				{
					var field_list=['id','invoice_summary_id','PURCHASEID','product_id','batchno','qnty','rate',
					'batchno','disc_per','disc_per2','mrp', 'potency_id','pack_id','no_of_dose','main_group_id','product_group_id'
					,'exp_monyr','mfg_monyr','tax_ledger_id','Synonym','label_print'];		
				}

				angular.forEach(field_list, function (values, key) 
				{ 

				//	console.log('values: '+values +' KEY :'+key);
				//	console.log(	$rootScope.dtlist_array[0]['tax_ledger_id']['Inputvalue'])

					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue']=
					$rootScope.dtlist_array[indx][values]['Inputvalue'];

					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue_id']=
					$rootScope.dtlist_array[indx][values]['Inputvalue_id'];
					
				}); 

				if($rootScope.current_form_report=='purchase_entry'|| $rootScope.current_form_report=='invoice_entry')
				{document.getElementById(8).focus(); }


				if($rootScope.current_form_report=='issue_entry')
				{document.getElementById(2).focus(); }
									
							
			}

			$scope.other_search=function(id,subtype,header_index,field_index,searchelement,input_id_index)
			{
				general_functions.other_search_new($rootScope.current_form_report,'other_search',BaseUrl,id,input_id_index);		
				$rootScope.suggestions = [];	
			}

			$scope.view_detail=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.view_detail($rootScope.current_form_report,BaseUrl,id);										
			}
			
					
			$scope.view_list=function(id)
			{
				console.log('view list id :'+id+'  :'+$rootScope.current_form_report);
				//$scope.server_msg="Data Loading....Please Wait";						
				general_functions.list_items($rootScope.current_form_report,'view_list',BaseUrl,id);	
				// $scope.dtlist(id);
				// 

				$scope.view_bill_wise_item(id);
				$scope.dtlist_total(id);	

			}
			$scope.view_list(0);

			$scope.get_field_name=function(header_index,field_param,field_name)
			{return $rootScope.FormInputArray[0]['header'][header_index]['fields'][0][field_name][field_param];}

			$scope.create_date_field=function(inputid)
			{	
				//console.log('inputid :'+inputid);

				$( function() {			
					$("#"+inputid ).datepicker({changeMonth: true,dateFormat: 'yy-mm-dd',changeYear: true});
					$("#"+inputid).change(function() {var  trandate = $("#"+inputid).val();
					$("#"+inputid).val(trandate);});
				} );	

			}
			$scope.create_date_field('startdate');
			$scope.create_date_field('enddate');
			

			$scope.gotoAnchor = function(x) 
			{
			 var newHash = 'innerAnchor' + x;

			 if ($location.hash() !== newHash) {							
				 $location.hash('innerAnchor' + x);
			 } else {							
				 $anchorScroll();
			 }

		 };

		 $scope.test=function()
		 {
			 //console.log('searchelement '+$rootScope.searchelement+' header index '+$rootScope.indx1+' Field Index '+$rootScope.index2);
			 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
			 console.log(data_save);

			 for(var indx=0;indx<2;indx++)
			 {
					angular.forEach($rootScope.FormInputArray[0]['header'][indx]['fields'][0], function (values, key) 
					{ 
						if($rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['InputType']=='datefield')
						{
							var inputid=$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['input_id_index'];
							$scope.create_date_field(inputid);
							$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['Inputvalue']=$scope.tran_date;
							
						}							
					}); 
			 }
			 

			 $( function() {			
				$('#exp_monyr').inputmask("datetime",{
					mask: "1/y", 
					placeholder: "mm/yyyy", 
					leapday: "-02-29", 
					separator: "/", 
					alias: "dd/mm/yyyy"
				});  
			} );	

			 if($rootScope.current_form_report=='invoice_entry' )
			 {document.getElementById(4).focus();}

			 if($rootScope.current_form_report=='invoice_entry_wholesale')
			 {document.getElementById(3).focus();}

			 if($rootScope.current_form_report=='purchase_entry')
			 {document.getElementById(0).focus();}

			 if($rootScope.current_form_report=='issue_entry')
			 {document.getElementById(1).focus(); }
			 
		 }
			 
			$scope.delete_bill=function(id)
			{
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
					$scope.view_list(0);
			}	

			$scope.delete_item=function(id)
			{
				
				general_functions.delete_item($rootScope.current_form_report,'delete_item',BaseUrl,id);
									
				var mainid=	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				console.log('mainid:'+mainid);
				
				$scope.view_bill_wise_item(mainid);
				// $scope.dtlist(mainid);
				$scope.dtlist_total(mainid);
				$scope.main_grid(1);

			}
			

		 $scope.new_entry=function(id)
			{	

				// if($rootScope.current_form_report=='retail_bill_experiment' )
				// {									
				// 	$rootScope.indx1=0;
				// 	$rootScope.index2=0;
				// 	$rootScope.searchelement='BILL_STATUS_CHECK_FOR_DELETE';
				// 	var billid=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				// 	if(billid>0)
				// 	{$scope.delete_bill(billid);}	
				// }

			//	var billid=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				if(id>0)
				{
					console.log('Delete bill '+id);
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
				}	

				$scope.view_list(0);

				if($rootScope.current_form_report=='retail_bill_experiment' )
				{	document.getElementById(4).focus();}	

				if($rootScope.current_form_report=='invoice_entry_wholesale' )
				{	document.getElementById(1).focus();}	

				if($rootScope.current_form_report=='purchase_entry' )
				{	document.getElementById(0).focus();}	

				if($rootScope.current_form_report=='doctor_prescription' )
				{	
					document.getElementById(2).focus();
				
				}	
			}

	

		$scope.mainOperation=function(event,header_index,field_index,Index2,index3,input_id_index)
		{	
			
			if(event.keyCode === 13)
				{						
												
						//CHANGES HERE FORM BASIS
						if($rootScope.current_form_report=='retail_bill_experiment')
						{	
									
							if($rootScope.searchelement=='qnty')	
							{$scope.save_check();}	

							input_id_index=Number(input_id_index+1);	

							if($rootScope.searchelement=='main_group_id' )	
							{

								angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
								{ 
									if(key!='main_group_id' )
									{
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
									}		

									if(key!='main_group_id' && key!='main_group_name' && key!='product_id' && key!='product_Synonym' )
									{
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['InputType']='hidden';
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['input_id_index']=9000;	
									}				

								}); 

								general_functions.populate_data($rootScope.indx1,$rootScope.index2,$rootScope.searchelement);


							//	$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement,input_id_index);
								document.getElementById(input_id_index).focus();	
							
							}
							else if($rootScope.searchelement=='tbl_party_id'  || $rootScope.searchelement=='batchno'|| 
							 $rootScope.searchelement=='rate' ||  $rootScope.searchelement=='disc_per'	 ||
							 $rootScope.searchelement=='disc_per2'  ||  $rootScope.searchelement=='patient_name' ||  $rootScope.searchelement=='product_Synonym' 
							 ||  $rootScope.searchelement=='product_id')	
							 {
								 //$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement,input_id_index);
								 document.getElementById(input_id_index).focus();	
							 }	
							 else  if($rootScope.searchelement=='potency_id' ||  $rootScope.searchelement=='pack_id'  ||  $rootScope.searchelement=='no_of_dose')	
							 {
										
										general_functions.set_rate();
										document.getElementById(input_id_index).focus();	

							 }
								else
								{		
									document.getElementById(input_id_index).focus();									
								}	

						}	

				}		
				if(event.keyCode === 39)
				{	
					input_id_index=Number(input_id_index+1);			
					document.getElementById(input_id_index).focus();		
				}		
				if(event.keyCode === 37)
				{	
					input_id_index=Number(input_id_index-1);			
					document.getElementById(input_id_index).focus();		
				}					
				

		}

		//	$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		{		
			 //console.log('searchelement '+searchelement+' indx1 '+indx1+' index2 '+index2+' index3 '+index3+' index4 '+index4+' array name '+$rootScope.current_form_report);

			 //console.log('$rootScope.current_form_report '+$rootScope.current_form_report);

			 $rootScope.spiner=false;
				//invoice_no
				$rootScope.search_div_display='none';
				$rootScope.searchelement=searchelement;
				$rootScope.array_name=array_name;							
				$rootScope.indx1=indx1;
				$rootScope.index2=index2;
				$rootScope.index3=index3;
				$rootScope.index3=index4;

				if($rootScope.current_form_report=='retail_bill_experiment' )
				{
					if(searchelement=='mrp' || searchelement=='disc_per'  || searchelement=='qnty')
					{
						
						if($rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue']=='P' )
						{
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];
						}

						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['subtotal']['Inputvalue']=
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue'])*
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0][searchelement]['Inputvalue']);


					}

					// var data_link=query_result_link+"42/";
					// console.log(data_link);
					// $http.get(data_link).then(function(response){
					// angular.forEach(response.data,function(value,key){					
					
					// 	if(value.PACK_ID==187 && value.POTENCY_ID==186 && value.GROUP_ID==93)	
					// 	{										
					// 		$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=700;										
					// 	}

					// });
					// });

				}
			
				$rootScope.searchItems=[];
				$rootScope.searchTextSmallLetters='';
				$rootScope.selectedIndex =0;
				
				angular.forEach($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement], function (values, key) 
				{ 
					
					if(key=='Inputvalue')
					{	$rootScope.searchTextSmallLetters = angular.uppercase(values);}
					
					if(values!='' && key=='datafields')
					{
							var array_length=values.length;
							if(array_length>0)
							{
								$rootScope.searchItems=values;
								//console.log('$rootScope.searchItems:'+$rootScope.searchItems);
								$rootScope.search_div_display='block';
								//block none											
							}
					}
					
				}); 


				if($rootScope.searchelement=='product_id')
				{
					$rootScope.searchItems=$rootScope.product_master;
					$rootScope.search_div_display='block';
				}

				if($rootScope.searchelement=='potency_id')
				{
					$rootScope.searchItems=$rootScope.potency_master;
					$rootScope.search_div_display='block';
				}

				if($rootScope.searchelement=='pack_id')
				{
					$rootScope.searchItems=$rootScope.pack_master;
					$rootScope.search_div_display='block';
				}

			


			
				//console.log('$rootScope.searchItems'+$rootScope.searchItems);
				//$rootScope.suggestions=$rootScope.searchItems;
				$rootScope.suggestions = [];
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].FieldVal);							
						if( searchItemsSmallLetters.indexOf($rootScope.searchTextSmallLetters) >=0)
						{									
							
							$rootScope.suggestions.push($rootScope.searchItems[i]);
							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 50)
							{break;}
							
						}						
				}

		};

			
			$rootScope.$watch('selectedIndex',function(val)
			{		
				if(val !== -1) 
				{
					
					if($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][0][$rootScope.searchelement]['Inputvalue']!='')
					{
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldVal'];
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldID'];
					}
					

					//inner div $anchorScroll
					//http://plnkr.co/edit/yFj9fL3sOhDqjhMawI72?p=preview&preview
					
					$scope.gotoAnchor(val);

				
				}
			});		
			
			$rootScope.checkKeyDown = function(event,header_index,field_index,Index2,index3,input_id_index)
			{
				
				console.log(event.keyCode);
					if(event.keyCode === 40){//down key, increment selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
						$rootScope.selectedIndex++;
					}else{
						$rootScope.selectedIndex = 0;
					}

					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					//console.log('event.keyCode:'+event.keyCode+' header_index:'+header_index+' field_index:'+field_index);
					//console.log('Index2:'+Index2+' index3:'+index3+' input_id_index:'+input_id_index);
				
				}else if(event.keyCode === 38){ //up key, decrement selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex-1 >= 0){
						$rootScope.selectedIndex--;
					}else{
						$rootScope.selectedIndex = $rootScope.suggestions.length-1;
					}
				}
				else if(event.keyCode === 13){ //enter key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 9){ //enter tab key
					//console.log($rootScope.selectedIndex);
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}			
		
				}
				else if(event.keyCode === 27){ //ESC key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}
				else if(event.keyCode === 39){ //right key
					//$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 37){ //left key
				//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 113){ //F2 KEY FOR ADD
					//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					}
				
				else{
					$rootScope.search();	
				}
			};
			
			//ClickOutSide
			var exclude1 = document.getElementById($rootScope.searchelement);
			$rootScope.hideMenu = function($event){
				$rootScope.search();
				//make a condition for every object you wat to exclude
				if($event.target !== exclude1) {
				
				}
			};
			//======================================
			
			//Function To Call on ng-keyup
			$rootScope.checkKeyUp = function(event)
			{ 
				if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
					if($scope[$rootScope.searchelement] === ""){
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];			
						$rootScope.selectedIndex = -1;
					}
				}
			};
			//======================================
			//List Item Events
			//Function To Call on ng-click
				$rootScope.AssignValueAndHide = function(index)
				{

				
					if( $rootScope.current_form_report=='retail_bill_experiment')
					{	
						if($rootScope.searchelement=='batchno' )
						{		
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
								$rootScope.suggestions[index]['FieldVal'];
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
								$rootScope.suggestions[index]['FieldID'];

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
								= $rootScope.suggestions[index]['Rack_No'];		

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
								= $rootScope.suggestions[index]['Rkid'];	

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
								= $rootScope.suggestions[index]['exp_monyr'];

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
								= $rootScope.suggestions[index]['rate'];
							
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
								= $rootScope.suggestions[index]['MRP'];		

						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}
					}


				$rootScope.suggestions=[];
				$rootScope.searchItems=[];		
				$rootScope.selectedIndex = -1;
			};


		$scope.bill_process_functions=function()
		{					
				var txt;
				if (confirm("Save Bill ?")) {
					$scope.final_submit();

					if (confirm("Label print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='LABEL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_label();
					}
					if (confirm("Bill print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='BILL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_documents();
					}

				} else {
					txt = "You pressed Cancel!";
				}
			console.log('uou have pressed'+txt);
				
		}

		$scope.final_submit=function(id)
		{
					// $scope.save_check();

					var data_link=BaseUrl;
					var success={};	
					var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
					var data_save = {'form_name':$scope.current_form_report,'subtype':'FINAL_SUBMIT','id':id};

					console.log('data_save final id : '+id);
					
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config)
					.then (function success(response){
							$scope.server_msg=response.data.server_msg;
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});

					$scope.save_check();

		}


		
	
		$scope.save_check=function()
		{	

				$rootScope.final_array=[];
				$rootScope.final_array = JSON.parse(JSON.stringify($rootScope.FormInputArray));
				$rootScope.save_status='OK';
				for(var i=0;i<2;i++)
				{
					angular.forEach($rootScope.final_array[0]['header'][i]['fields'][0], function (values, key) 
					{ 
						$rootScope.final_array[0]['header'][i]['fields'][0][key]['datafields']='';	

						if($rootScope.final_array[0]['header'][i]['fields'][0][key]['validation_type']=='NOT_OK' 
						&& $rootScope.save_status=='OK')
						{ 
							$rootScope.save_status='NOT_OK'; 
							$scope.server_msg='Record Can Not Be Save! Please Rectify the '+
							$rootScope.final_array[0]['header'][i]['fields'][0][key]['LabelName'] +' Related data';
						}
						
					}); 
				}		

				// console.log('$rootScope.save_status :'+$rootScope.savedone_status);
				// if($rootScope.save_status=='OK' && $rootScope.savedone_status=='SAVE_NOT_DONE')
				// {$rootScope.savedone_status='SAVE_DONE'; $scope.savedata();}

				$rootScope.savedone_status='SAVE_DONE'; 
				$scope.savedata();
			
		}	

		//SAVE SECTION...
		$scope.savedata=function()
		{
			$scope.showarray='YES';
		

						var data_link=BaseUrl;
						var success={};	
						var data = JSON.stringify($rootScope.final_array);	
						var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			
						var config = {headers : 
							{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
						}
						//$http.post(data_link, data,config)
						$http.post(data_link,data_save,config)
						.then (function success(response){
			
								$scope.server_msg=response.data.server_msg;
								$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']=response.data.id_header;
								$rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_no']['Inputvalue']=response.data.invoice_no;
								
								$scope.view_bill_wise_item(response.data.id_header);
								$scope.dtlist_total(response.data.id_header);	
								$scope.main_grid(1);
								angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
								{ 
									if(key!='main_group_id' )
									{
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
									}				
								}); 
			
							//CHANGES HERE FORM BASIS
							if($rootScope.current_form_report=='retail_bill_experiment')
							{document.getElementById(7).focus();}
			
							$rootScope.savedone_status='SAVE_NOT_DONE';
					
						},
						function error(response){
							$scope.errorMessage = 'Error adding user!';
							$scope.message = '';
						});

		}

		//SAVE SECTION...


		//FOR BILL PRINT
		$scope.print_documents = function(printtype) 
		{ 
			var printtype='POS_INVOICE';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};
	

		$scope.print_label = function() 
		{ 
			
			var PRINTTYPE='LABEL';
			var id_header=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var BaseUrl=domain_name+"Project_controller/print_all/";
			var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
			window.popup(data_link); 				 
		};

		$scope.print_slip = function(printtype) 
		{ 
			var printtype='PRINT_SLIP';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};


		
		

}]);


//DONE ALL
app.controller('prescription_entry',['$scope','$rootScope','$http','$window','general_functions','$location',
function($scope,$rootScope,$http,$window,general_functions,$location)
{
	"use strict";


		var CurrentDate=new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();
		if (dt < 10) {	dt = '0' + dt;}
		if (month < 10) {month = '0' + month;}
		$scope.tran_date=year+'-' + month + '-'+dt;
		$scope.startdate=$scope.enddate=	$scope.tran_date;
		//$scope.barcodeimg=domain_name+'uploads/BILL-2.png';

		//$scope.input_id_index=0;
		$rootScope.fld_arr=[];	

		$rootScope.sec_indx0=0;	
		$rootScope.sec_indx1=1;	
		$rootScope.sec_indx2=2;	
		$rootScope.sec_indx3=3;	
		$rootScope.sec_indx4=4;	

	//	$rootScope.final_array=[];
		$rootScope.FormInputArray_template=[];	
		$rootScope.FormInputArray=[];	
		$rootScope.returnobject={};
		$rootScope.returnArray=[];	

		$rootScope.main_grid_array=[];
		$rootScope.dtlist_array=[];
		$rootScope.dtlist_total_array=[];
		$rootScope.patient_list_array=[];
		
		$rootScope.savedone_status='SAVE_NOT_DONE';
		$rootScope.grandtotal=0;
		$scope.formname='';
		$rootScope.array_name='';

		var BaseUrl=domain_name+"Project_controller/experimental_form/";
		$rootScope.search_div_display='none';
		//block none

		//$scope.codestructure1_id=0;
		$scope.server_msg='Enter Form';
		//$rootScope.current_form_report='grn_entry';

		 $rootScope.current_form_report=localStorage.getItem("TranPageName");
		// console.log('page name :'+$rootScope.current_form_report);

		 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
		 
	
			//GENERAL FUNCTIONS START

			//GET LIST OF PATIENT
			general_functions.patient_list(BaseUrl);

			$scope.main_grid=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.main_grid($rootScope.current_form_report,'main_grid',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}
			$scope.main_grid(1);

			$scope.setTotals = function(item)
			{
				angular.forEach(item, function (values, key) 
					{ 			
							
						if(key=='Grand Total')
						{
							console.log(key+' '+values)	;		
							$rootScope.grandtotal =Number($rootScope.grandtotal) +Number(values);
						
						}								
					}); 
			}
			

			$scope.dtlist=function(id)
			{general_functions.dtlist(BaseUrl,id);}

			$scope.dtlist_total=function(id)
			{general_functions.dtlist_total(BaseUrl,id);}
			
			$scope.dtlist_view=function(indx)
			{		
							
			}

			$scope.other_search=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.other_search($rootScope.current_form_report,'other_search',BaseUrl,id);		
				$rootScope.suggestions = [];								
			}

			$scope.view_detail=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.view_detail($rootScope.current_form_report,BaseUrl,id);										
			}
			
					
			$scope.view_list=function(id)
			{
				console.log('view list id :'+id+'  :'+$rootScope.current_form_report);
				//$scope.server_msg="Data Loading....Please Wait";						
				general_functions.list_items($rootScope.current_form_report,'view_list',BaseUrl,id);	
				$scope.dtlist(id);
				$scope.dtlist_total(id);	

			}
			$scope.view_list(0);

			$scope.get_field_name=function(header_index,field_param,field_name)
			{return $rootScope.FormInputArray[0]['header'][header_index]['fields'][0][field_name][field_param];}

			$scope.create_date_field=function(inputid)
			{	
				//console.log('inputid :'+inputid);

				$( function() {			
					$("#"+inputid ).datepicker({changeMonth: true,dateFormat: 'yy-mm-dd',changeYear: true});
					$("#"+inputid).change(function() {var  trandate = $("#"+inputid).val();
					$("#"+inputid).val(trandate);});
				} );	

			}
			$scope.create_date_field('startdate');
			$scope.create_date_field('enddate');
			

			$scope.gotoAnchor = function(x) 
			{
			 var newHash = 'innerAnchor' + x;

			 if ($location.hash() !== newHash) {							
				 $location.hash('innerAnchor' + x);
			 } else {							
				 $anchorScroll();
			 }

		 };

				 
			$scope.delete_bill=function(id)
			{
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
					$scope.view_list(0);
			}	

			$scope.delete_item=function(id)
			{
				general_functions.delete_item($rootScope.current_form_report,'delete_item',BaseUrl,id);
				var mainid=	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];				
				$scope.dtlist(mainid);
				$scope.dtlist_total(mainid);
				$scope.main_grid(1);

			}
			
		 $scope.new_entry=function(id)
			{	
				if(id>0)
				{$scope.delete_bill(id);}	

				$scope.view_list(0);
				document.getElementById(2).focus();
			}


		$scope.mainOperation=function(event,header_index,field_index,Index2,index3,input_id_index)
		{	
			
			if(event.keyCode === 13)
				{						
												
						//CHANGES HERE FORM BASIS
																
							/*
							if($rootScope.searchelement=='party_name' 
							|| $rootScope.searchelement=='mobno' 
							|| $rootScope.searchelement=='address' 
							||  $rootScope.searchelement=='doctor_mstr_id' 
							||  $rootScope.searchelement=='id'
							||  $rootScope.searchelement=='age_yy'
							||  $rootScope.searchelement=='age_mm' )	
							{$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}

							if($rootScope.searchelement=='ACTUAL_VISIT_AMT')	
							{$scope.save_prescription();}	

							
							 if($rootScope.searchelement=='party_name')	
							{
								
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									{
										document.getElementById(14).focus();
									}
									else
									{
										input_id_index=Number(input_id_index+1);			
										document.getElementById(input_id_index).focus();		
									}
								
							}											
							else if($rootScope.searchelement=='doctor_mstr_id')	
							{
								if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue']!='')
								{
									document.getElementById(19).focus();
								}
								else
								{
									input_id_index=Number(input_id_index+1);			
									document.getElementById(input_id_index).focus();

								}
							}
							else
							{
								input_id_index=Number(input_id_index+1);			
								document.getElementById(input_id_index).focus();
							}	

							*/

							if($rootScope.searchelement=='ACTUAL_VISIT_AMT')	
							{$scope.save_prescription('PRINT_PRESCRIPTION');}	

							if($rootScope.searchelement=='age_yy' ||  $rootScope.searchelement=='age_mm' )	
							{$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}

						
							if($rootScope.searchelement=='id')	
							{									 
							  								
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									{
										var id=	$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue'];
										general_functions.set_patient_records(id);
										input_id_index=Number(input_id_index+1);	
										document.getElementById(input_id_index).focus();	
									}
									else
									{
										input_id_index=Number(input_id_index+1);			
										document.getElementById(input_id_index).focus();		
									}


							}	
							else if($rootScope.searchelement=='party_name')	
							{		
								
									 if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									 {
										var id=	$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue'];
										general_functions.set_patient_records(id);									 
										$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);
										document.getElementById(14).focus();
										
										}
									 else
									 {
										 input_id_index=Number(input_id_index+1);			
										 document.getElementById(input_id_index).focus();		
									 }

							}		
							else if($rootScope.searchelement=='mobno')	
							{		
								
									 if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									 {
										var id=	$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue'];
										general_functions.set_patient_records(id);									 
										$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);
										document.getElementById(14).focus();
										
										}
									 else
									 {
										 input_id_index=Number(input_id_index+1);			
										 document.getElementById(input_id_index).focus();		
									 }

							}		
							else if($rootScope.searchelement=='address')	
							{		
								
									 if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									 {
										var id=	$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue'];
										general_functions.set_patient_records(id);									 
										$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);
										document.getElementById(14).focus();
										
										}
									 else
									 {
										 input_id_index=Number(input_id_index+1);			
										 document.getElementById(input_id_index).focus();		
									 }

							}		
							else if($rootScope.searchelement=='doctor_mstr_id')	
							{							
								$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);
								if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue']!='')
								{document.getElementById(19).focus();}
								else
								{
									input_id_index=Number(input_id_index+1);			
									document.getElementById(input_id_index).focus();
								}
							}					
							else
							{
								input_id_index=Number(input_id_index+1);			
								document.getElementById(input_id_index).focus();
							}			

				}		
				if(event.keyCode === 39)
				{	
					input_id_index=Number(input_id_index+1);			
					document.getElementById(input_id_index).focus();		
				}		
				if(event.keyCode === 37)
				{	
					input_id_index=Number(input_id_index-1);			
					document.getElementById(input_id_index).focus();		
				}					
				

		}

		//	$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		{		
			 //console.log('searchelement '+searchelement+' indx1 '+indx1+' index2 '+index2+' index3 '+index3+' index4 '+index4+' array name '+$rootScope.current_form_report);

			 //console.log('$rootScope.current_form_report '+$rootScope.current_form_report);

				//invoice_no
				$rootScope.search_div_display='none';
				$rootScope.searchelement=searchelement;
				$rootScope.array_name=array_name;							
				$rootScope.indx1=indx1;
				$rootScope.index2=index2;
				$rootScope.index3=index3;
				$rootScope.index3=index4;
			
				$rootScope.searchItems=[];
				$rootScope.searchTextSmallLetters='';
			//	$rootScope.selectedIndex =0;
				
				angular.forEach($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement], function (values, key) 
				{ 
					
					if(key=='Inputvalue')
					{	$rootScope.searchTextSmallLetters = angular.uppercase(values);}
				
					if(values!='' && key=='datafields')
					{
							var array_length=values.length;
							if(array_length>0)
							{
								$rootScope.searchItems=values;
								//console.log('$rootScope.searchItems:'+$rootScope.searchItems);
								$rootScope.search_div_display='block';
								//block none											
							}
					}
				}); 
			
		
				$rootScope.suggestions = [];
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].FieldVal);							
						if( searchItemsSmallLetters.indexOf($rootScope.searchTextSmallLetters) >=0)
						{									
							
							$rootScope.suggestions.push($rootScope.searchItems[i]);
							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 50)
							{break;}
							
						}						
				}

		};

			
			$rootScope.$watch('selectedIndex',function(val)
			{		
				if(val !== -1) 
				{
					
					if($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][0][$rootScope.searchelement]['Inputvalue']!='')
					{
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldVal'];
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldID'];
					}
					

					//inner div $anchorScroll
					//http://plnkr.co/edit/yFj9fL3sOhDqjhMawI72?p=preview&preview
					
					$scope.gotoAnchor(val);

				
				}
			});		
			
			$rootScope.checkKeyDown = function(event,header_index,field_index,Index2,index3,input_id_index)
			{
				
				console.log(event.keyCode);
					if(event.keyCode === 40){//down key, increment selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
						$rootScope.selectedIndex++;
					}else{
						$rootScope.selectedIndex = 0;
					}

					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					//console.log('event.keyCode:'+event.keyCode+' header_index:'+header_index+' field_index:'+field_index);
					//console.log('Index2:'+Index2+' index3:'+index3+' input_id_index:'+input_id_index);
				
				}else if(event.keyCode === 38){ //up key, decrement selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex-1 >= 0){
						$rootScope.selectedIndex--;
					}else{
						$rootScope.selectedIndex = $rootScope.suggestions.length-1;
					}
				}
				else if(event.keyCode === 13){ //enter key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 9){ //enter tab key
					//console.log($rootScope.selectedIndex);
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}			
		
				}
				else if(event.keyCode === 27){ //ESC key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}
				else if(event.keyCode === 39){ //right key
					//$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 37){ //left key
				//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 113){ //F2 KEY FOR ADD
					//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					}
				
				else{
					$rootScope.search();	
				}
			};
			
			//ClickOutSide
			var exclude1 = document.getElementById($rootScope.searchelement);
			$rootScope.hideMenu = function($event){
				$rootScope.search();
				//make a condition for every object you wat to exclude
				if($event.target !== exclude1) {
				
				}
			};
			//======================================
			
			//Function To Call on ng-keyup
			$rootScope.checkKeyUp = function(event)
			{ 
				if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
					if($scope[$rootScope.searchelement] === ""){
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];			
						$rootScope.selectedIndex = -1;
					}
				}
			};
			//======================================
			//List Item Events
			//Function To Call on ng-click
				$rootScope.AssignValueAndHide = function(index)
				{
		
						
						if($rootScope.searchelement=='party_name' )
						{			
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['party_name']['Inputvalue']=='')
									{	
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	
										
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['party_name']['Inputvalue']
										= $rootScope.suggestions[index]['FieldVal'];										
									}
									else
									{

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
										$rootScope.suggestions[index]['FieldVal'];
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
										$rootScope.suggestions[index]['FieldID'];

									}
						}
					else	if($rootScope.searchelement=='mobno' )
						{			
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['mobno']['Inputvalue']=='')
									{	
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	
										
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mobno']['Inputvalue']
										= $rootScope.suggestions[index]['FieldVal'];										
									}
									else
									{

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
										$rootScope.suggestions[index]['FieldVal'];
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
										$rootScope.suggestions[index]['FieldID'];

									}
						}
						else	if($rootScope.searchelement=='address' )
						{			
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['address']['Inputvalue']=='')
									{	
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	
										
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['address']['Inputvalue']
										= $rootScope.suggestions[index]['FieldVal'];										
									}
									else
									{

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
										= $rootScope.suggestions[index]['FieldID'];		

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
										= $rootScope.suggestions[index]['FieldID'];	

										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
										$rootScope.suggestions[index]['FieldVal'];
										$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
										$rootScope.suggestions[index]['FieldID'];

									}
						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}


				$rootScope.suggestions=[];
				$rootScope.searchItems=[];		
				$rootScope.selectedIndex = -1;
			};


		$scope.bill_process_functions=function()
		{					
				var txt;
				if (confirm("Save Bill ?")) {
					$scope.final_submit();

					if (confirm("Label print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='LABEL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_label();
					}
					if (confirm("Bill print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='BILL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_documents();
					}

				} else {
					txt = "You pressed Cancel!";
				}
			console.log('uou have pressed'+txt);
				
		}

	

		//DOCTOR PRESCRIPTION

			//SAVE SECTION...
			$scope.save_prescription=function(PRINT_STATUS)
			{
				var data_link=BaseUrl;
				var success={};	
				var data = JSON.stringify($rootScope.FormInputArray);	
				var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			//	console.log('data_save final : '+data);
				
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config)
				.then (function success(response){

						$scope.server_msg=response.data.server_msg;
						$scope.prescription_id=response.data.prescription_id;
						$scope.token_no=response.data.token_no;
						$scope.id_header=response.data.id_header;
						
						if(PRINT_STATUS=='PRINT_PRESCRIPTION')
						{$scope.print_prescription($scope.prescription_id);}
						else
						{
							$scope.new_entry();
							document.getElementById(2).focus();
						}
						$scope.prescription_list(1);
				
						
						
						console.log($scope.prescription_id);
						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id']=response.data.prescription_id;
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});


			}
		
			$scope.print_prescription = function(id) 
			{ 
				
				var PRINTTYPE='PRESCRIPTION';
				//var prescription_id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id'];
				var prescription_id=id;
				var BaseUrl=domain_name+"Project_controller/print_all/";
				var data_link=BaseUrl+prescription_id+'/'+PRINTTYPE;
				window.popup(data_link); 	
				
				$scope.new_entry();
				document.getElementById(2).focus();
			};

			$scope.prescription_list=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.prescription_list($rootScope.current_form_report,'prescription_list',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}

			$scope.prescription_list(1);


			$scope.view_list_prescription=function(id)
			{
				console.log('view list id :'+id+'  :'+$rootScope.current_form_report);
			
				general_functions.prescription_edit(BaseUrl,id);

			}

			//print_prescription
	



			//RECEIVE -PAYMENT SECTION
			//SAVE SECTION...
			$scope.save_receive_payment=function(TRAN_TYPE)
			{
				var data_link=BaseUrl;
				var success={};	
				var data = JSON.stringify($rootScope.FormInputArray);	
				var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			//	console.log('data_save final : '+data);
				
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config)
				.then (function success(response){

						$scope.server_msg=response.data.server_msg;
						//$scope.prescription_id=response.data.prescription_id;
						//$scope.token_no=response.data.token_no;
						$scope.id_header=response.data.id_header;
						$scope.new_entry();
						document.getElementById(2).focus();
						
					//	$scope.print_receipt_payment($scope.id_header,TRAN_TYPE);
						
						
					//	console.log($scope.prescription_id);
					//	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id']=response.data.prescription_id;
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});


			}
		
			$scope.print_receipt_payment = function(PRINTTYPE) 
			{ 
				//var PRINTTYPE='PRESCRIPTION';
				var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue_id'];
				var prescription_id=id;
				var BaseUrl=domain_name+"Project_controller/print_all/";
				var data_link=BaseUrl+id+'/'+PRINTTYPE;
				window.popup(data_link); 							
				$scope.new_entry();
				document.getElementById(2).focus();
			};

			$scope.receipt_payment_list=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.receipt_payment_list($rootScope.current_form_report,'receipt_payment_list',BaseUrl,id,$scope.startdate,$scope.enddate);
			}
			$scope.receipt_payment_list(1);

			//print_prescription

}]);




app.controller('wholesale_bill_experiment',['$scope','$rootScope','$http','$window','general_functions','$location',
function($scope,$rootScope,$http,$window,general_functions,$location)
{
	"use strict";


		var CurrentDate=new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();
		if (dt < 10) {	dt = '0' + dt;}
		if (month < 10) {month = '0' + month;}
		$scope.tran_date=year+'-' + month + '-'+dt;
		$scope.startdate=$scope.enddate=	$scope.tran_date;
		//$scope.barcodeimg=domain_name+'uploads/BILL-2.png';

		//$scope.input_id_index=0;
		$rootScope.fld_arr=[];	

		$rootScope.sec_indx0=0;	
		$rootScope.sec_indx1=1;	
		$rootScope.sec_indx2=2;	
		$rootScope.sec_indx3=3;	
		$rootScope.sec_indx4=4;	

	//	$rootScope.final_array=[];
		$rootScope.FormInputArray_template=[];	
		$rootScope.FormInputArray=[];	
		$rootScope.returnobject={};
		$rootScope.returnArray=[];	
		$rootScope.spiner=false;

		$rootScope.main_grid_array=[];
		$rootScope.dtlist_array=[];
		$rootScope.dtlist_total_array=[];
		$rootScope.product_rate_mstr={};
		$rootScope.product_rate_mstr_array=[];
		$rootScope.product_rates=[];


		$rootScope.savedone_status='SAVE_NOT_DONE';
		$rootScope.grandtotal=0;
		$scope.formname='';
		$rootScope.array_name='';

		var BaseUrl=domain_name+"Project_controller/experimental_form/";
		$rootScope.search_div_display='none';
		//block none

		//$scope.codestructure1_id=0;
		$scope.server_msg='Enter Form';
		//$rootScope.current_form_report='grn_entry';

		 $rootScope.current_form_report=localStorage.getItem("TranPageName");
		 console.log('page name :'+$rootScope.current_form_report);

		 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
		 console.log(data_save);

			//GENERAL FUNCTIONS START			

			$scope.product_rates=function(id)
			{	
				general_functions.product_rates(BaseUrl);
			}
			$scope.product_rates(1);


			$scope.main_grid=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.main_grid($rootScope.current_form_report,'main_grid',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}
			$scope.main_grid(1);

			$scope.setTotals = function(item)
			{
				angular.forEach(item, function (values, key) 
					{ 	
						if(key=='Grand Total')
						{
							console.log(key+' '+values)	;		
							$rootScope.grandtotal =Number($rootScope.grandtotal) +Number(values);
						
						}								
					}); 
			}
			
			

			$scope.view_bill_wise_item=function(id)
			{general_functions.view_bill_wise_item(BaseUrl,id);}

			$scope.dtlist=function(id)
			{general_functions.dtlist(BaseUrl,id);}

			$scope.dtlist_total=function(id)
			{general_functions.dtlist_total(BaseUrl,id);}
			
			$scope.dtlist_view=function(indx)
			{
							
			}

			$scope.other_search=function(id,subtype,header_index,field_index,searchelement,input_id_index)
			{
				general_functions.other_search_new($rootScope.current_form_report,'other_search',BaseUrl,id,input_id_index);		
				$rootScope.suggestions = [];	
			}

			$scope.view_detail=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.view_detail($rootScope.current_form_report,BaseUrl,id);										
			}
			
					
			$scope.view_list=function(id)
			{		
				general_functions.list_items($rootScope.current_form_report,'view_list',BaseUrl,id);				
				$scope.view_bill_wise_item(id);
				$scope.dtlist_total(id);	
			}
			$scope.view_list(0);

			$scope.get_field_name=function(header_index,field_param,field_name)
			{return $rootScope.FormInputArray[0]['header'][header_index]['fields'][0][field_name][field_param];}

			$scope.create_date_field=function(inputid)
			{	
				//console.log('inputid :'+inputid);

				$( function() {			
					$("#"+inputid ).datepicker({changeMonth: true,dateFormat: 'yy-mm-dd',changeYear: true});
					$("#"+inputid).change(function() {var  trandate = $("#"+inputid).val();
					$("#"+inputid).val(trandate);});
				} );	

			}
			$scope.create_date_field('startdate');
			$scope.create_date_field('enddate');
			

			$scope.gotoAnchor = function(x) 
			{
			 var newHash = 'innerAnchor' + x;

			 if ($location.hash() !== newHash) {							
				 $location.hash('innerAnchor' + x);
			 } else {							
				 $anchorScroll();
			 }

		 };

		 $scope.test=function()
		 {
			 //console.log('searchelement '+$rootScope.searchelement+' header index '+$rootScope.indx1+' Field Index '+$rootScope.index2);
			 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
			 console.log(data_save);

			 for(var indx=0;indx<2;indx++)
			 {
					angular.forEach($rootScope.FormInputArray[0]['header'][indx]['fields'][0], function (values, key) 
					{ 
						if($rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['InputType']=='datefield')
						{
							var inputid=$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['input_id_index'];
							$scope.create_date_field(inputid);
							$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['Inputvalue']=$scope.tran_date;
							
						}							
					}); 
			 }
			 

			 $( function() {			
				$('#exp_monyr').inputmask("datetime",{
					mask: "1/y", 
					placeholder: "mm/yyyy", 
					leapday: "-02-29", 
					separator: "/", 
					alias: "dd/mm/yyyy"
				});  
			} );	

			document.getElementById(3).focus();
			 
		 }
			 
			$scope.delete_bill=function(id)
			{
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
					$scope.view_list(0);
			}	

			$scope.delete_item=function(id)
			{
				
				general_functions.delete_item($rootScope.current_form_report,'delete_item',BaseUrl,id);									
				var mainid=	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				console.log('mainid:'+mainid);				
				$scope.view_bill_wise_item(mainid);
				// $scope.dtlist(mainid);
				$scope.dtlist_total(mainid);
				$scope.main_grid(1);

			}
			

		 $scope.new_entry=function(id)
			{	

				if(id>0)
				{
					console.log('Delete bill '+id);
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
				}	

				$scope.view_list(0);
				document.getElementById(1).focus();
			}

		$scope.mainOperation=function(event,header_index,field_index,Index2,index3,input_id_index)
		{	
			
			if(event.keyCode === 13)
				{						
												
						//CHANGES HERE FORM BASIS					
									
							if($rootScope.searchelement=='qnty')	
							{$scope.save_check();}	

							input_id_index=Number(input_id_index+1);	

							if($rootScope.searchelement=='main_group_id' )	
							{
								angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
								{ 
									if(key!='main_group_id' )
									{
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
									}				
								}); 
								$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement,input_id_index);
								document.getElementById(input_id_index).focus();	
							
							}
							else if($rootScope.searchelement=='tbl_party_id'  || $rootScope.searchelement=='batchno'|| 
							 $rootScope.searchelement=='rate' ||  $rootScope.searchelement=='disc_per'	 ||
							 $rootScope.searchelement=='disc_per2'  ||  $rootScope.searchelement=='patient_name' ||  $rootScope.searchelement=='product_Synonym' 
							 ||  $rootScope.searchelement=='product_id')	
							 {
								 $scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement,input_id_index);
								 document.getElementById(input_id_index).focus();	
							 }	
							 else  if($rootScope.searchelement=='potency_id' ||  $rootScope.searchelement=='pack_id'  ||  $rootScope.searchelement=='no_of_dose')	
							 {										
										general_functions.set_rate();
										document.getElementById(input_id_index).focus();
							 }
								else
								{		
									document.getElementById(input_id_index).focus();									
								}	


				}		
				if(event.keyCode === 39)
				{	
					input_id_index=Number(input_id_index+1);			
					document.getElementById(input_id_index).focus();		
				}		
				if(event.keyCode === 37)
				{	
					input_id_index=Number(input_id_index-1);			
					document.getElementById(input_id_index).focus();		
				}					
				

		}

		//	$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		{		
			
			  $rootScope.spiner=false;
				$rootScope.search_div_display='none';
				$rootScope.searchelement=searchelement;
				$rootScope.array_name=array_name;							
				$rootScope.indx1=indx1;
				$rootScope.index2=index2;
				$rootScope.index3=index3;
				$rootScope.index3=index4;

					if(searchelement=='mrp' || searchelement=='disc_per'  || searchelement=='qnty')
					{
						
						if($rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue']=='P' )
						{
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];
						}

						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['subtotal']['Inputvalue']=
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue'])*
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0][searchelement]['Inputvalue']);

					}
			
				$rootScope.searchItems=[];
				$rootScope.searchTextSmallLetters='';
				$rootScope.selectedIndex =0;
				
				angular.forEach($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement], function (values, key) 
				{ 
					
					if(key=='Inputvalue')
					{	$rootScope.searchTextSmallLetters = angular.uppercase(values);}
				
					if(values!='' && key=='datafields')
					{
							var array_length=values.length;
							if(array_length>0)
							{
								$rootScope.searchItems=values;
								//console.log('$rootScope.searchItems:'+$rootScope.searchItems);
								$rootScope.search_div_display='block';
								//block none											
							}
					}
				}); 

				$rootScope.suggestions = [];
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].FieldVal);							
						if( searchItemsSmallLetters.indexOf($rootScope.searchTextSmallLetters) >=0)
						{									
							
							$rootScope.suggestions.push($rootScope.searchItems[i]);
							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 50)
							{break;}
							
						}						
				}

		};

			
			$rootScope.$watch('selectedIndex',function(val)
			{		
				if(val !== -1) 
				{
					
					if($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][0][$rootScope.searchelement]['Inputvalue']!='')
					{
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldVal'];
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldID'];
					}
					
					$scope.gotoAnchor(val);
				
				}
			});		
			
			$rootScope.checkKeyDown = function(event,header_index,field_index,Index2,index3,input_id_index)
			{
				
				console.log(event.keyCode);
					if(event.keyCode === 40){//down key, increment selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
						$rootScope.selectedIndex++;
					}else{
						$rootScope.selectedIndex = 0;
					}

					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					//console.log('event.keyCode:'+event.keyCode+' header_index:'+header_index+' field_index:'+field_index);
					//console.log('Index2:'+Index2+' index3:'+index3+' input_id_index:'+input_id_index);
				
				}else if(event.keyCode === 38){ //up key, decrement selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex-1 >= 0){
						$rootScope.selectedIndex--;
					}else{
						$rootScope.selectedIndex = $rootScope.suggestions.length-1;
					}
				}
				else if(event.keyCode === 13){ //enter key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 9){ //enter tab key
					//console.log($rootScope.selectedIndex);
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}			
		
				}
				else if(event.keyCode === 27){ //ESC key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}
				else if(event.keyCode === 39){ //right key
					//$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 37){ //left key
				//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 113){ //F2 KEY FOR ADD
					//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					}
				
				else{
					$rootScope.search();	
				}
			};
			
			//ClickOutSide
			var exclude1 = document.getElementById($rootScope.searchelement);
			$rootScope.hideMenu = function($event){
				$rootScope.search();
				//make a condition for every object you wat to exclude
				if($event.target !== exclude1) {
				
				}
			};
			//======================================
			
			//Function To Call on ng-keyup
			$rootScope.checkKeyUp = function(event)
			{ 
				if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
					if($scope[$rootScope.searchelement] === ""){
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];			
						$rootScope.selectedIndex = -1;
					}
				}
			};
			//======================================
			//List Item Events
			//Function To Call on ng-click
				$rootScope.AssignValueAndHide = function(index)
				{
						
						if($rootScope.searchelement=='batchno' )
						{		
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
								$rootScope.suggestions[index]['FieldVal'];
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
								$rootScope.suggestions[index]['FieldID'];

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
								= $rootScope.suggestions[index]['Rack_No'];		

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
								= $rootScope.suggestions[index]['Rkid'];	

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
								= $rootScope.suggestions[index]['exp_monyr'];

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
								= $rootScope.suggestions[index]['rate'];
							
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
								= $rootScope.suggestions[index]['MRP'];		

						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}

				$rootScope.suggestions=[];
				$rootScope.searchItems=[];		
				$rootScope.selectedIndex = -1;
			};


		$scope.bill_process_functions=function()
		{					
				var txt;
				if (confirm("Save Bill ?")) {
					$scope.final_submit();

					if (confirm("Label print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='LABEL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_label();
					}
					if (confirm("Bill print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='BILL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_documents();
					}

				} else {
					txt = "You pressed Cancel!";
				}
			console.log('uou have pressed'+txt);
				
		}

		$scope.final_submit=function(id)
		{
					var data_link=BaseUrl;
					var success={};	
					var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
					var data_save = {'form_name':$scope.current_form_report,'subtype':'FINAL_SUBMIT','id':id};
					console.log('data_save final id : '+id);					
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config)
					.then (function success(response){
							$scope.server_msg=response.data.server_msg;
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});

					$scope.save_check();
		}


		
	
		$scope.save_check=function()
		{	

				$rootScope.final_array=[];
				$rootScope.final_array = JSON.parse(JSON.stringify($rootScope.FormInputArray));
				$rootScope.save_status='OK';
				for(var i=0;i<2;i++)
				{
					angular.forEach($rootScope.final_array[0]['header'][i]['fields'][0], function (values, key) 
					{ 
						$rootScope.final_array[0]['header'][i]['fields'][0][key]['datafields']='';	

						if($rootScope.final_array[0]['header'][i]['fields'][0][key]['validation_type']=='NOT_OK' 
						&& $rootScope.save_status=='OK')
						{ 
							$rootScope.save_status='NOT_OK'; 
							$scope.server_msg='Record Can Not Be Save! Please Rectify the '+
							$rootScope.final_array[0]['header'][i]['fields'][0][key]['LabelName'] +' Related data';
						}
						
					}); 
				}		

				$rootScope.savedone_status='SAVE_DONE'; 
				$scope.savedata();
			
		}	

		//SAVE SECTION...
		$scope.savedata=function()
		{
			$scope.showarray='YES';
		

						var data_link=BaseUrl;
						var success={};	
						var data = JSON.stringify($rootScope.final_array);	
						var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			
						var config = {headers : 
							{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
						}
						//$http.post(data_link, data,config)
						$http.post(data_link,data_save,config)
						.then (function success(response){
			
								$scope.server_msg=response.data.server_msg;
								$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']=response.data.id_header;
								$rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_no']['Inputvalue']=response.data.invoice_no;
								
								$scope.view_bill_wise_item(response.data.id_header);
								$scope.dtlist_total(response.data.id_header);	
								$scope.main_grid(1);
								angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
								{ 
									if(key!='main_group_id' )
									{
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
										$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
									}				
								}); 
			
							//CHANGES HERE FORM BASIS
							document.getElementById(7).focus();
							$rootScope.savedone_status='SAVE_NOT_DONE';
					
						},
						function error(response){
							$scope.errorMessage = 'Error adding user!';
							$scope.message = '';
						});

		}

		//SAVE SECTION...


		//FOR BILL PRINT
		$scope.print_documents = function(printtype) 
		{ 
			var printtype='POS_INVOICE';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};
	

		$scope.print_label = function() 
		{ 
			
			var PRINTTYPE='LABEL';
			var id_header=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var BaseUrl=domain_name+"Project_controller/print_all/";
			var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
			window.popup(data_link); 				 
		};

		$scope.print_slip = function(printtype) 
		{ 
			var printtype='PRINT_SLIP';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};


		
		

}]);

app.controller('Product_master',['$scope','$rootScope','$http',
function($scope,$rootScope,$http)
{
	"use strict";

	//$scope.appState='EMIPAYMENT';
	//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
	$scope.spiner='ON';
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/Product_master/";
			

			$scope.mainOperation=function(event,element_name)
			{	
					console.log('event '+event);
					// if(element_name===19)
					// {
					// 	$scope.get_set_value('','','DRCRCHECKING');
					// 	document.getElementById(7).focus();			
					// }			

					// if(event.keyCode === 13)
					// {	
					// 	if(element_name===10)
					// 	{document.getElementById('exp_monyr').focus();}		

					// 	if(element_name===11)
					// 	{document.getElementById('mfg_monyr').focus();}			

					// 	element_name=Number(element_name+1);			
					// 	document.getElementById(element_name).focus();		
					// }						
			}

			//===================START SEARCH SECTION =========================================
		
			$rootScope.taxlist= [];
			$rootScope.brandlist= [];
			$rootScope.grouplist= [];
			$rootScope.productlist=[];			

			var data_link=query_result_link+"34/";
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.productlist_master.push({id: value.id,name:value.productname});});});
			
			$scope.savemsg='Product master Loading. Please wait ...... '; 	
			var data_link=BaseUrl+"product_master/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){		
					$scope.savemsg='All product loaded'; 			
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
				});
			});
			
			$scope.spiner='OFF';

			var data_link=query_result_link+"38/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.brandlist.push({id:value.id,name:value.name});});});

			var data_link=query_result_link+"39/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.grouplist.push({id:value.id,name:value.name});});});

			var data_link=query_result_link+"40/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.taxlist.push({id:value.id,name:value.acc_name});});});


		$rootScope.search = function(searchelement)
		{
		
			$scope.SEARCHTYPE='PRODUCT';
			$rootScope.searchelement=searchelement;
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
		
			if($rootScope.searchelement=='product_id_name')
			{$rootScope.searchItems=$rootScope.productlist;}	
			else if($rootScope.searchelement=='group_id_name')
			{$rootScope.searchItems=$rootScope.grouplist;}	
			else if($rootScope.searchelement=='brand_id_name')
			{$rootScope.searchItems=$rootScope.brandlist;}	
			else if($rootScope.searchelement=='tax_per')
			{$rootScope.searchItems=$rootScope.taxlist;}	
			else
			{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
			}
	
			console.log($rootScope.searchItems);
						
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++)
			{
				
					var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
					var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
					if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
	
						if($rootScope.searchelement=='product_id_name' )
						{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
						else
						{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}
	
						myMaxSuggestionListLength += 1;
						if(myMaxSuggestionListLength === 1500)
						{break;}
					}
			}

		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {
		//	$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex];
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	
		}
	});		
	$rootScope.checkKeyDown = function(event){
		console.log(event.keyCode);

		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
		//	event.preventDefault();			
		//	$rootScope.suggestions = [];
		//	$rootScope.searchItems=[];
		//	$rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{

		$scope[$rootScope.searchelement]= $rootScope.suggestions[index];
	
		if($rootScope.searchelement=='product_id_name')
		{
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"product_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 					
					$scope['hsncode']=value.hsncode; 

					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; 

					$scope['group_id']=value.group_id; 
					$scope['group_id_name']=value.group_id_name; 

					$scope['brand_id']=value.brand_id; 
					$scope['brand_id_name']=value.brand_id_name; 
																					
				});
			});

			
		}

		if($rootScope.searchelement=='group_id_name')
		{
			$scope['group_id']=$rootScope.suggestions[index]['id'];
			$scope['group_id_name']=$rootScope.suggestions[index]['name'];				
		}
		if($rootScope.searchelement=='tax_per')
		{
			$scope['tax_ledger_id']=$rootScope.suggestions[index]['id'];
			$scope['tax_per']=$rootScope.suggestions[index]['name'];				
		}
		if($rootScope.searchelement=='brand_id_name')
		{
			$scope['brand_id']=$rootScope.suggestions[index]['id'];
			$scope['brand_id_name']=$rootScope.suggestions[index]['name'];				
		}
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];
		 $rootScope.selectedIndex = -1;
	};

	//===================END SEARCH SECTION =========================================


	$scope.savedata=function()
	{
		var data_link=BaseUrl+"SAVE";
		var success={};
		$scope.spiner='ON';
		//console.log('$scope.id_detail'+$scope.id_detail);
		var data_save = 
		{
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'productname': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'hsncode': $scope.get_set_value($scope.hsncode,'str','SETVALUE'),
			'group_id': $scope.get_set_value($scope.group_id,'num','SETVALUE'),
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'brand_id': $scope.get_set_value($scope.brand_id,'num','SETVALUE')
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){

			//console.log('ID HEADER '+response.data.id_header);
			$scope.savemsg='Product has been saved Successfully!'; 			
			$scope.get_set_value(response.data,'','REFRESH');
			document.getElementById('1').focus();
			
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
		});
		

	}
	
	$scope.get_set_value=function(datavalue,datatype,operation)
	{
		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}
	
		if(operation=='REFRESH')
		{		
			$scope.spiner='OFF';
			$scope['product_id']='';
			$scope['product_id_name']='';				
			$scope['hsncode']='';
			$scope['group_id']='';
			$scope['group_id_name']='';
			$scope['tax_ledger_id']='';
			$scope['tax_per']='';
			$scope['brand_id']='';
			$scope['brand_id_name']='';	
			document.getElementById('1').focus();	

		}
	}



}]);


app.controller('main_transaction_controller',['$scope','$rootScope','$http','$window','general_functions','$location',
function($scope,$rootScope,$http,$window,general_functions,$location)
{
	"use strict";


		var CurrentDate=new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();
		if (dt < 10) {	dt = '0' + dt;}
		if (month < 10) {month = '0' + month;}
		$scope.tran_date=year+'-' + month + '-'+dt;
		$scope.startdate=$scope.enddate=	$scope.tran_date;
		//$scope.barcodeimg=domain_name+'uploads/BILL-2.png';

		//$scope.input_id_index=0;
		$rootScope.fld_arr=[];	

		$rootScope.sec_indx0=0;	
		$rootScope.sec_indx1=1;	
		$rootScope.sec_indx2=2;	
		$rootScope.sec_indx3=3;	
		$rootScope.sec_indx4=4;	

	//	$rootScope.final_array=[];
		$rootScope.FormInputArray_template=[];	
		$rootScope.FormInputArray=[];	
		$rootScope.returnobject={};
		$rootScope.returnArray=[];	

		$rootScope.main_grid_array=[];
		$rootScope.dtlist_array=[];
		$rootScope.dtlist_total_array=[];
		
		$rootScope.savedone_status='SAVE_NOT_DONE';
		$rootScope.grandtotal=0;
		$scope.formname='';
		$rootScope.array_name='';

		var BaseUrl=domain_name+"Project_controller/experimental_form/";
		$rootScope.search_div_display='none';
		//block none

		//$scope.codestructure1_id=0;
		$scope.server_msg='Enter Form';
		//$rootScope.current_form_report='grn_entry';

		 $rootScope.current_form_report=localStorage.getItem("TranPageName");
		 console.log('page name :'+$rootScope.current_form_report);

		 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
		 console.log(data_save);
	
			//GENERAL FUNCTIONS START
			$scope.main_grid=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.main_grid($rootScope.current_form_report,'main_grid',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}

			$scope.main_grid(1);

			$scope.setTotals = function(item)
			{
				
				angular.forEach(item, function (values, key) 
					{ 			
							
						if(key=='Grand Total')
						{
							console.log(key+' '+values)	;		
							$rootScope.grandtotal =Number($rootScope.grandtotal) +Number(values);
						
						}								
					}); 
			}
			

			$scope.dtlist=function(id)
			{general_functions.dtlist(BaseUrl,id);}

			$scope.dtlist_total=function(id)
			{general_functions.dtlist_total(BaseUrl,id);}
			
			$scope.dtlist_view=function(indx)
			{

				//general_functions.dtlist_view(BaseUrl,id);		

				//,'PURCHASEID'
				if($rootScope.current_form_report=='issue_entry')
				{var field_list=['id','product_id','product_Synonym','batchno','rackno','qnty','exp_monyr'];}

					
				if($rootScope.current_form_report=='purchase_entry')
				{
				var field_list=['id','product_id','batchno','mrp','disc_per','rate','qnty','subtotal','exp_monyr','tax_ledger_id','rackno'];	
				}

				//id,product_id,batchno,qnty,rate,subtotal,disc_per,disc_per2,disc_amt,taxable_amt,mrp,
				//exp_monyr,mfg_monyr,tax_ledger_id,taxamt,net_amt,Synonym,label_print

				//product_id,batchno,qnty,rate,disc_per,disc_per2,Synonym,label_print,mrp,exp_monyr,mfg_monyr,tax_ledger_id

				if($rootScope.current_form_report=='invoice_entry')
				{
					var field_list=['id','invoice_summary_id','PURCHASEID','product_id','batchno','qnty','rate',
					'batchno','disc_per','disc_per2','mrp', 'potency_id','pack_id','no_of_dose','main_group_id','product_group_id'
					,'exp_monyr','mfg_monyr','tax_ledger_id','Synonym','label_print'];		
				}

				angular.forEach(field_list, function (values, key) 
				{ 

				//	console.log('values: '+values +' KEY :'+key);
				//	console.log(	$rootScope.dtlist_array[0]['tax_ledger_id']['Inputvalue'])

					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue']=
					$rootScope.dtlist_array[indx][values]['Inputvalue'];

					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue_id']=
					$rootScope.dtlist_array[indx][values]['Inputvalue_id'];
					
				}); 

				if($rootScope.current_form_report=='purchase_entry'|| $rootScope.current_form_report=='invoice_entry')
				{document.getElementById(8).focus(); }


				if($rootScope.current_form_report=='issue_entry')
				{document.getElementById(2).focus(); }
									
							
			}

			$scope.other_search=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.other_search($rootScope.current_form_report,'other_search',BaseUrl,id);		
				$rootScope.suggestions = [];								
			}

			$scope.view_detail=function(id,subtype,header_index,field_index,searchelement)
			{
				general_functions.view_detail($rootScope.current_form_report,BaseUrl,id);										
			}
			
					
			$scope.view_list=function(id)
			{
				console.log('view list id :'+id+'  :'+$rootScope.current_form_report);
				//$scope.server_msg="Data Loading....Please Wait";						
				general_functions.list_items($rootScope.current_form_report,'view_list',BaseUrl,id);	
				$scope.dtlist(id);
				$scope.dtlist_total(id);	

			}
			$scope.view_list(0);

			$scope.get_field_name=function(header_index,field_param,field_name)
			{return $rootScope.FormInputArray[0]['header'][header_index]['fields'][0][field_name][field_param];}

			$scope.create_date_field=function(inputid)
			{	
				//console.log('inputid :'+inputid);

				$( function() {			
					$("#"+inputid ).datepicker({changeMonth: true,dateFormat: 'yy-mm-dd',changeYear: true});
					$("#"+inputid).change(function() {var  trandate = $("#"+inputid).val();
					$("#"+inputid).val(trandate);});
				} );	

			}
			$scope.create_date_field('startdate');
			$scope.create_date_field('enddate');
			

			$scope.gotoAnchor = function(x) 
			{
			 var newHash = 'innerAnchor' + x;

			 if ($location.hash() !== newHash) {							
				 $location.hash('innerAnchor' + x);
			 } else {							
				 $anchorScroll();
			 }

		 };

		 $scope.test=function()
		 {
			 //console.log('searchelement '+$rootScope.searchelement+' header index '+$rootScope.indx1+' Field Index '+$rootScope.index2);
			 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
			 console.log(data_save);

			 for(var indx=0;indx<2;indx++)
			 {
					angular.forEach($rootScope.FormInputArray[0]['header'][indx]['fields'][0], function (values, key) 
					{ 
						if($rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['InputType']=='datefield')
						{
							var inputid=$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['input_id_index'];
							$scope.create_date_field(inputid);
							$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['Inputvalue']=$scope.tran_date;
							
						}							
					}); 
			 }
			 

			 $( function() {			
				$('#exp_monyr').inputmask("datetime",{
					mask: "1/y", 
					placeholder: "mm/yyyy", 
					leapday: "-02-29", 
					separator: "/", 
					alias: "dd/mm/yyyy"
				});  
			} );	

			 if($rootScope.current_form_report=='invoice_entry' )
			 {document.getElementById(4).focus();}

			 if($rootScope.current_form_report=='invoice_entry_wholesale')
			 {document.getElementById(3).focus();}

			 if($rootScope.current_form_report=='purchase_entry')
			 {document.getElementById(0).focus();}

			 if($rootScope.current_form_report=='issue_entry')
			 {document.getElementById(1).focus(); }
			 
		 }
			 
			$scope.delete_bill=function(id)
			{
					general_functions.delete_bill($rootScope.current_form_report,'delete_bill',BaseUrl,id);
					$scope.main_grid(1);
					$scope.view_list(0);
			}	

			$scope.delete_item=function(id)
			{
				
				general_functions.delete_item($rootScope.current_form_report,'delete_item',BaseUrl,id);
									
				var mainid=	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				//console.log('mainid:'+mainid);
				
				$scope.dtlist(mainid);
				$scope.dtlist_total(mainid);
				$scope.main_grid(1);

			}
			

		 $scope.new_entry=function(id)
			{	

				// if($rootScope.current_form_report=='invoice_entry' )
				// {									
				// 	$rootScope.indx1=0;
				// 	$rootScope.index2=0;
				// 	$rootScope.searchelement='BILL_STATUS_CHECK_FOR_DELETE';
				// 	var billid=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
				// 	if(billid>0)
				// 	{$scope.delete_bill(billid);}	
				// }

				if(id>0)
				{$scope.delete_bill(id);}	

				$scope.view_list(0);

				if($rootScope.current_form_report=='invoice_entry' )
				{	document.getElementById(4).focus();}	

				if($rootScope.current_form_report=='invoice_entry_wholesale' )
				{	document.getElementById(1).focus();}	

				if($rootScope.current_form_report=='purchase_entry' )
				{	document.getElementById(0).focus();}	



				if($rootScope.current_form_report=='doctor_prescription' )
				{	
					document.getElementById(2).focus();
				}	





					//var txt;
					// var r = confirm("Do you want to Create New Invoice ?");
					// if (r == true) {

								//CHECKING THE BILL IF BILL_STATUS =NONE THEN DELETE THE BILL 
								



								// angular.forEach($rootScope.FormInputArray[0]['header'][0]['fields'][0], function (values, key) 
								// { 
								// 	$rootScope.FormInputArray[0]['header'][0]['fields'][0][key]['Inputvalue']='';
								// 	$rootScope.FormInputArray[0]['header'][0]['fields'][0][key]['Inputvalue_id']='';	
								// }); 

								// angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
								// { 								
								// 		$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
								// 		$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
								// }); 

								// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_date']['Inputvalue']=$scope.tran_date;
														
								// $scope.dtlist(0);
								// $scope.test();
								// document.getElementById(0).focus();
					//}

			}

	

		$scope.mainOperation=function(event,header_index,field_index,Index2,index3,input_id_index)
		{	
			
			if(event.keyCode === 13)
				{						
												
						//CHANGES HERE FORM BASIS
						if($rootScope.current_form_report=='invoice_entry' || $rootScope.current_form_report=='invoice_entry_wholesale')
						{
							
												
							if($rootScope.searchelement=='qnty')	
							{$scope.save_check();}	

							 if($rootScope.searchelement=='tbl_party_id'  || $rootScope.searchelement=='batchno'|| 
							 $rootScope.searchelement=='rate' ||  $rootScope.searchelement=='disc_per'	 ||
							 $rootScope.searchelement=='disc_per2' ||  $rootScope.searchelement=='main_group_id' 
							 ||  $rootScope.searchelement=='potency_id' ||  $rootScope.searchelement=='pack_id' 
							 ||  $rootScope.searchelement=='no_of_dose' ||  $rootScope.searchelement=='patient_name' ||  $rootScope.searchelement=='product_Synonym' 
							 ||  $rootScope.searchelement=='product_id')	
							 {$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}


							 
						input_id_index=Number(input_id_index+1);			
						document.getElementById(input_id_index).focus();

						}	

						if($rootScope.current_form_report=='purchase_entry' )
						{
						
							if($rootScope.searchelement=='qnty')	
							{$scope.save_check();}	

							//|| $rootScope.searchelement=='batchno'
							 if($rootScope.searchelement=='tbl_party_id' || $rootScope.searchelement=='product_id' 
							 || $rootScope.searchelement=='batchno' ||  $rootScope.searchelement=='product_Synonym' )	
							 {$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}
							 
						
							 input_id_index=Number(input_id_index+1);			
							 document.getElementById(input_id_index).focus();
						
						}	

						if($rootScope.current_form_report=='issue_entry')
						{
						
							if($rootScope.searchelement=='qnty')	
							{$scope.save_check();}	

							//|| $rootScope.searchelement=='batchno'
							 if($rootScope.searchelement=='tbl_party_id' || $rootScope.searchelement=='product_id' 
							 || $rootScope.searchelement=='batchno' ||  $rootScope.searchelement=='product_Synonym' )	
							 {$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}
							 
						
						
							 input_id_index=Number(input_id_index+1);			
							 document.getElementById(input_id_index).focus();
						
						}	

						if($rootScope.current_form_report=='doctor_prescription' )
						{

							if($rootScope.searchelement=='party_name' 
							|| $rootScope.searchelement=='mobno' 
							|| $rootScope.searchelement=='address' 
							||  $rootScope.searchelement=='doctor_mstr_id' 
							||  $rootScope.searchelement=='id'
							||  $rootScope.searchelement=='age_yy'
							||  $rootScope.searchelement=='age_mm' )	
							{$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}


							if($rootScope.searchelement=='ACTUAL_VISIT_AMT')	
							{$scope.save_prescription();}	



							// if($rootScope.searchelement=='id')	
							// {

							// 	if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
							// 	{
							// 		document.getElementById(14).focus();
							// 	}
							// 	else
							// 	{
							// 		input_id_index=Number(input_id_index+1);			
							// 		document.getElementById(input_id_index).focus();		
							// 	}
							// }	
							
							 if($rootScope.searchelement=='party_name')	
							{
								
									if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=='')
									{
										document.getElementById(14).focus();
									}
									else
									{
										input_id_index=Number(input_id_index+1);			
										document.getElementById(input_id_index).focus();		
									}
								
							}											
							else if($rootScope.searchelement=='doctor_mstr_id')	
							{
								if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue']!='')
								{
									document.getElementById(19).focus();
								}
								else
								{
									input_id_index=Number(input_id_index+1);			
									document.getElementById(input_id_index).focus();

								}
							}
							else
							{
								input_id_index=Number(input_id_index+1);			
								document.getElementById(input_id_index).focus();
							}	

						
						}	

						if($rootScope.current_form_report=='receipt_whole_sale' ||  $rootScope.current_form_report=='payment_expense')
						{
							input_id_index=Number(input_id_index+1);			
							document.getElementById(input_id_index).focus();
						}	


				

					

				}		
				if(event.keyCode === 39)
				{	
					input_id_index=Number(input_id_index+1);			
					document.getElementById(input_id_index).focus();		
				}		
				if(event.keyCode === 37)
				{	
					input_id_index=Number(input_id_index-1);			
					document.getElementById(input_id_index).focus();		
				}					
				

		}

		//	$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
		{		
			 //console.log('searchelement '+searchelement+' indx1 '+indx1+' index2 '+index2+' index3 '+index3+' index4 '+index4+' array name '+$rootScope.current_form_report);

			 //console.log('$rootScope.current_form_report '+$rootScope.current_form_report);

				//invoice_no
				$rootScope.search_div_display='none';
				$rootScope.searchelement=searchelement;
				$rootScope.array_name=array_name;							
				$rootScope.indx1=indx1;
				$rootScope.index2=index2;
				$rootScope.index3=index3;
				$rootScope.index3=index4;



				//console.log('searchelement '+$rootScope.searchelement+' indx1 '+$rootScope.indx1);
				//console.log('Input Inputvalue :'+$rootScope.FormInputArray[0]['header'][0]['fields'][0]['patient_name']['Inputvalue']);
				

				if($rootScope.current_form_report=='purchase_entry' )
				{
					if(searchelement=='batchno' || searchelement=='disc_per'  || searchelement=='qnty' )
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];

							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=
							Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])-
							(Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])*
							Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['disc_per']['Inputvalue'])/100);

							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['subtotal']['Inputvalue']=
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']*
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue'];

					}
				}

				if($rootScope.current_form_report=='issue_entry' )
				{
					if(searchelement=='batchno' || searchelement=='qnty' )
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];
					}
				}


				if($rootScope.current_form_report=='invoice_entry' )
				{
					if(searchelement=='mrp' || searchelement=='disc_per'  || searchelement=='qnty')
					{
						
						if($rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue']=='P' )
						{
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];
						}

						// $rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=
						// Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])-
						// (Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])*
						// Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['disc_per']['Inputvalue'])/100);

						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['subtotal']['Inputvalue']=
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue'])*
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0][searchelement]['Inputvalue']);
					}
				}

				if($rootScope.current_form_report=='invoice_entry_wholesale' )
				{
					
						if($rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue']=='P' )
						{
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['Inputvalue'];
						}

						if(searchelement=='mrp' || searchelement=='disc_per'  || searchelement=='qnty' )
						{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])-
						(Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue'])*
						Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['disc_per']['Inputvalue'])/100);

						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['subtotal']['Inputvalue']=
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']*
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['qnty']['Inputvalue'];

						}

				}


			
				$rootScope.searchItems=[];
				$rootScope.searchTextSmallLetters='';
				$rootScope.selectedIndex =0;
				
				angular.forEach($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement], function (values, key) 
				{ 
					
					if(key=='Inputvalue')
					{	$rootScope.searchTextSmallLetters = angular.uppercase(values);}
				
					if(values!='' && key=='datafields')
					{
							var array_length=values.length;
							if(array_length>0)
							{
								$rootScope.searchItems=values;
								//console.log('$rootScope.searchItems:'+$rootScope.searchItems);
								$rootScope.search_div_display='block';
								//block none											
							}
					}
				}); 

			


			
				//console.log('$rootScope.searchItems'+$rootScope.searchItems);
				//$rootScope.suggestions=$rootScope.searchItems;
				$rootScope.suggestions = [];
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].FieldVal);							
						if( searchItemsSmallLetters.indexOf($rootScope.searchTextSmallLetters) >=0)
						{									
							
							$rootScope.suggestions.push($rootScope.searchItems[i]);
							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 50)
							{break;}
							
						}						
				}

		};

			
			$rootScope.$watch('selectedIndex',function(val)
			{		
				if(val !== -1) 
				{
					
					if($rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][0][$rootScope.searchelement]['Inputvalue']!='')
					{
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldVal'];
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
						$rootScope.suggestions[$rootScope.selectedIndex]['FieldID'];
					}
					

					//inner div $anchorScroll
					//http://plnkr.co/edit/yFj9fL3sOhDqjhMawI72?p=preview&preview
					
					$scope.gotoAnchor(val);

				
				}
			});		
			
			$rootScope.checkKeyDown = function(event,header_index,field_index,Index2,index3,input_id_index)
			{
				
				console.log(event.keyCode);
					if(event.keyCode === 40){//down key, increment selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
						$rootScope.selectedIndex++;
					}else{
						$rootScope.selectedIndex = 0;
					}

					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					//console.log('event.keyCode:'+event.keyCode+' header_index:'+header_index+' field_index:'+field_index);
					//console.log('Index2:'+Index2+' index3:'+index3+' input_id_index:'+input_id_index);
				
				}else if(event.keyCode === 38){ //up key, decrement selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex-1 >= 0){
						$rootScope.selectedIndex--;
					}else{
						$rootScope.selectedIndex = $rootScope.suggestions.length-1;
					}
				}
				else if(event.keyCode === 13){ //enter key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 9){ //enter tab key
					//console.log($rootScope.selectedIndex);
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}			
		
				}
				else if(event.keyCode === 27){ //ESC key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}
				else if(event.keyCode === 39){ //right key
					//$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 37){ //left key
				//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
				}
				else if(event.keyCode === 113){ //F2 KEY FOR ADD
					//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
					}
				
				else{
					$rootScope.search();	
				}
			};
			
			//ClickOutSide
			var exclude1 = document.getElementById($rootScope.searchelement);
			$rootScope.hideMenu = function($event){
				$rootScope.search();
				//make a condition for every object you wat to exclude
				if($event.target !== exclude1) {
				
				}
			};
			//======================================
			
			//Function To Call on ng-keyup
			$rootScope.checkKeyUp = function(event)
			{ 
				if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
					if($scope[$rootScope.searchelement] === ""){
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];			
						$rootScope.selectedIndex = -1;
					}
				}
			};
			//======================================
			//List Item Events
			//Function To Call on ng-click
				$rootScope.AssignValueAndHide = function(index)
				{

					

					//console.log('mrp mrp' + $rootScope.searchelement + $rootScope.current_form_report);

					if( $rootScope.current_form_report=='issue_entry')
					{	
								if($rootScope.searchelement=='batchno' )
								{		
									
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
									$rootScope.suggestions[index]['FieldVal'];
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
									$rootScope.suggestions[index]['FieldID'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
									= $rootScope.suggestions[index]['Rack_No'];		

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
									= $rootScope.suggestions[index]['Rkid'];	
									
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
									= $rootScope.suggestions[index]['exp_monyr'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
									= $rootScope.suggestions[index]['MRP'];
								
									// // $rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['PURCHASEID']['Inputvalue']
									// // = $rootScope.suggestions[index]['pid'];
																
								}
								else
								{

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
									$rootScope.suggestions[index]['FieldVal'];
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
									$rootScope.suggestions[index]['FieldID'];

								}

				}



					if( $rootScope.current_form_report=='purchase_entry')
					{	
						if($rootScope.searchelement=='batchno' )
						{			
									
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
									$rootScope.suggestions[index]['FieldVal'];
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
									$rootScope.suggestions[index]['FieldID'];
							
							
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['batchno']['Inputvalue_id']='';

									// $rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
									// = $rootScope.suggestions[index]['Rack_No'];		

									// $rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
									// = $rootScope.suggestions[index]['Rkid'];	

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
									= $rootScope.suggestions[index]['exp_monyr'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
									= $rootScope.suggestions[index]['Rate'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
									= $rootScope.suggestions[index]['MRP'];

						

					
						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}
					}


					if( $rootScope.current_form_report=='invoice_entry')
					{	
						if($rootScope.searchelement=='batchno' )
						{			
							
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];


								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
								= $rootScope.suggestions[index]['Rack_No'];		

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
								= $rootScope.suggestions[index]['Rkid'];	

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
								= $rootScope.suggestions[index]['exp_monyr'];

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
								= $rootScope.suggestions[index]['rate'];
							
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
								= $rootScope.suggestions[index]['MRP'];																
						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}
					}


					if( $rootScope.current_form_report=='invoice_entry_wholesale')
					{	
						if($rootScope.searchelement=='batchno' )
						{			
								
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];
									
							
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue']
									= $rootScope.suggestions[index]['Rack_No'];		

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rackno']['Inputvalue_id']
									= $rootScope.suggestions[index]['Rkid'];	

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['exp_monyr']['Inputvalue']
									= $rootScope.suggestions[index]['exp_monyr'];
								
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
									= $rootScope.suggestions[index]['MRP'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
									= $rootScope.suggestions[index]['rate'];
						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}
					}


					if( $rootScope.current_form_report=='doctor_prescription')
					{	
					
					
						
						if($rootScope.searchelement=='party_name' )
						{			
							if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['party_name']['Inputvalue']=='')
							{	
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue']
								= $rootScope.suggestions[index]['FieldID'];		

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['id']['Inputvalue_id']
								= $rootScope.suggestions[index]['FieldID'];	
								
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['party_name']['Inputvalue']
								= $rootScope.suggestions[index]['FieldVal'];										
							}
							else
							{

								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
								$rootScope.suggestions[index]['FieldVal'];
								$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
								$rootScope.suggestions[index]['FieldID'];

							}
						}
						else
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

						}

						// if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['party_name']['Inputvalue']!='')
						// {
						
						// 	if($rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']!=0)
						// 	{
						// 		document.getElementById(14).focus();
						// 	}
						// 	else
						// 	{
						// 		input_id_index=Number(input_id_index+1);			
						// 		document.getElementById(input_id_index).focus();		
						// 	}
							
						// }



					}


					

					if($rootScope.current_form_report=='receipt_whole_sale' ||  $rootScope.current_form_report=='payment_expense')
					{
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
						$rootScope.suggestions[index]['FieldVal'];
						$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
						$rootScope.suggestions[index]['FieldID'];
					}	

				


				$rootScope.suggestions=[];
				$rootScope.searchItems=[];		
				$rootScope.selectedIndex = -1;
			};


		$scope.bill_process_functions=function()
		{					
				var txt;
				if (confirm("Save Bill ?")) {
					$scope.final_submit();

					if (confirm("Label print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='LABEL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_label();
					}
					if (confirm("Bill print ?")) {

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='BILL_PRINTED';
						$scope.save_check();
						$scope.savedata();

						$scope.print_documents();
					}

				} else {
					txt = "You pressed Cancel!";
				}
			console.log('uou have pressed'+txt);
				
		}


		// $scope.final_submit=function()
		// {					
		// 		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['BILL_STATUS']['Inputvalue']='BILL_SAVED';
		// 		// $scope.save_check();
		// 		// $scope.savedata();			
				
				


		// }

		$scope.final_submit=function(id)
		{
				  $scope.save_check();

					var data_link=BaseUrl;
					var success={};	
					var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
					var data_save = {'form_name':$scope.current_form_report,'subtype':'FINAL_SUBMIT','id':id};

					console.log('data_save final id : '+id);
					
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config)
					.then (function success(response){

							$scope.server_msg=response.data.server_msg;
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});


		}

		$scope.save_check=function()
		{	

				$rootScope.final_array=[];
				$rootScope.final_array = JSON.parse(JSON.stringify($rootScope.FormInputArray));
				$rootScope.save_status='OK';

				for(var i=0;i<2;i++)
				{
					angular.forEach($rootScope.final_array[0]['header'][i]['fields'][0], function (values, key) 
					{ 
						$rootScope.final_array[0]['header'][i]['fields'][0][key]['datafields']='';	

						if($rootScope.final_array[0]['header'][i]['fields'][0][key]['validation_type']=='NOT_OK' 
						&& $rootScope.save_status=='OK')
						{ 
							$rootScope.save_status='NOT_OK'; 
							$scope.server_msg='Record Can Not Be Save! Please Rectify the '+
							$rootScope.final_array[0]['header'][i]['fields'][0][key]['LabelName'] +' Related data';
						}
						
					}); 
				}		

				console.log('$rootScope.save_status :'+$rootScope.savedone_status);

				if($rootScope.save_status=='OK' && $rootScope.savedone_status=='SAVE_NOT_DONE')
				{$rootScope.savedone_status='SAVE_DONE'; $scope.savedata();}
			
		}	

		//SAVE SECTION...
		$scope.savedata=function()
		{
			$scope.showarray='YES';
			$scope.spiner='ON';
			//	$scope.savemsg='Please Wait data saving....';
			//var data_link=BaseUrl+"SAVE";
			var data_link=BaseUrl;
			var success={};	
			var data = JSON.stringify($rootScope.final_array);	
			var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};

			console.log('data_save final : '+data);
			
			var config = {headers : 
				{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
			}
			//$http.post(data_link, data,config)
			$http.post(data_link,data_save,config)
			.then (function success(response){

					$scope.server_msg=response.data.server_msg;
					$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']=response.data.id_header;
					$rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_no']['Inputvalue']=response.data.invoice_no;

					$scope.dtlist(response.data.id_header);
					$scope.dtlist_total(response.data.id_header);							
					$scope.main_grid(1);

					angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
					{ 
						if(key!='main_group_id' )
						{
							$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
							$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
						}
				
					}); 


				//CHANGES HERE FORM BASIS

				if($rootScope.current_form_report=='invoice_entry')
				{
					document.getElementById(7).focus();
				
				}

				if( $rootScope.current_form_report=='invoice_entry_wholesale')
				{
					document.getElementById(6).focus();
				}

				if($rootScope.current_form_report=='purchase_entry' )
				{document.getElementById(8).focus();}

				if($rootScope.current_form_report=='issue_entry')
				{
					document.getElementById(2).focus();
				}

				$rootScope.savedone_status='SAVE_NOT_DONE';
		
			},
			function error(response){
				$scope.errorMessage = 'Error adding user!';
				$scope.message = '';
			});


		}

		//SAVE SECTION...


		//FOR BILL PRINT
		$scope.print_documents = function(printtype) 
		{ 
			var printtype='POS_INVOICE';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};
	

		$scope.print_label = function() 
		{ 
			
			var PRINTTYPE='LABEL';
			var id_header=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var BaseUrl=domain_name+"Project_controller/print_all/";
			var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
			window.popup(data_link); 				 
		};

		$scope.print_slip = function(printtype) 
		{ 
			var printtype='PRINT_SLIP';
			//var BaseUrl=domain_name+"Project_controller/experimental_form/";
			var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
			var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
			console.log(data_link);
			//	$http.get(data_link).then(function(response){});
			window.popup(data_link); 
			
		};


		//DOCTOR PRESCRIPTION

			//SAVE SECTION...
			$scope.save_prescription=function()
			{
				var data_link=BaseUrl;
				var success={};	
				var data = JSON.stringify($rootScope.FormInputArray);	
				var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			//	console.log('data_save final : '+data);
				
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config)
				.then (function success(response){

						$scope.server_msg=response.data.server_msg;
						$scope.prescription_id=response.data.prescription_id;
						$scope.token_no=response.data.token_no;
						$scope.id_header=response.data.id_header;
						
						$scope.print_prescription($scope.prescription_id);
						
						
						console.log($scope.prescription_id);
						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id']=response.data.prescription_id;
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});


			}
		
			$scope.print_prescription = function(id) 
			{ 
				
				var PRINTTYPE='PRESCRIPTION';
				//var prescription_id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id'];
				var prescription_id=id;
				var BaseUrl=domain_name+"Project_controller/print_all/";
				var data_link=BaseUrl+prescription_id+'/'+PRINTTYPE;
				window.popup(data_link); 	
				
				$scope.new_entry();
				document.getElementById(2).focus();
			};

			$scope.prescription_list=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.prescription_list($rootScope.current_form_report,'prescription_list',BaseUrl,id,$scope.startdate,$scope.enddate);
				
			}

			$scope.prescription_list(1);


			$scope.view_list_prescription=function(id)
			{
				console.log('view list id :'+id+'  :'+$rootScope.current_form_report);
			
				general_functions.prescription_edit(BaseUrl,id);

			}

			//print_prescription
	



			//RECEIVE -PAYMENT SECTION
			//SAVE SECTION...
			$scope.save_receive_payment=function(TRAN_TYPE)
			{
				var data_link=BaseUrl;
				var success={};	
				var data = JSON.stringify($rootScope.FormInputArray);	
				var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};
			//	console.log('data_save final : '+data);
				
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config)
				.then (function success(response){

						$scope.server_msg=response.data.server_msg;
						//$scope.prescription_id=response.data.prescription_id;
						//$scope.token_no=response.data.token_no;
						$scope.id_header=response.data.id_header;
						$scope.new_entry();
						document.getElementById(2).focus();
						
					//	$scope.print_receipt_payment($scope.id_header,TRAN_TYPE);
						
						
					//	console.log($scope.prescription_id);
					//	$rootScope.FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id']=response.data.prescription_id;
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});


			}
		
			$scope.print_receipt_payment = function(PRINTTYPE) 
			{ 
				//var PRINTTYPE='PRESCRIPTION';
				var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue_id'];
				var prescription_id=id;
				var BaseUrl=domain_name+"Project_controller/print_all/";
				var data_link=BaseUrl+id+'/'+PRINTTYPE;
				window.popup(data_link); 							
				$scope.new_entry();
				document.getElementById(2).focus();
			};

			$scope.receipt_payment_list=function(id)
			{	
				$rootScope.grandtotal=0;
				general_functions.receipt_payment_list($rootScope.current_form_report,'receipt_payment_list',BaseUrl,id,$scope.startdate,$scope.enddate);
			}
			$scope.receipt_payment_list(1);

			//print_prescription

}]);




	
//************************PRODUCT MASTER END*****************************************//



app.controller('product_rate_master',['$scope','$rootScope','general_functions','$http',
function($scope,$rootScope,general_functions,$http){
	"use strict";
	
			$scope.products={};
			$scope.ledgers={};
			$rootScope.FormInputArray=[];	
			$rootScope.rate_list_array=[];

			var BaseUrl=domain_name+"Project_controller/product_rate_master/";
			var AcTranType;
			
			// var data_link=query_result_link+"39/";$http.get(data_link).then(function(response) {$scope.master_lovs=response.data;});
			// console.log(data_link);

			// var data_link=query_result_link+"33/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});

			general_functions.get_master(BaseUrl,0,'PRODUCT_GROUP');


			$scope.rate_list=function(id)
			{	
				general_functions.rate_list(BaseUrl,id);
			}

			$scope.rate_list(1);
		
							
			$scope.savedata=function()
			{
				var data_link=BaseUrl;
				console.log(data_link);
				var success={};		
							
				var data = JSON.stringify($rootScope.rate_list_array['body']);	
				var data_save = {'subtype':'SAVE_DATA','raw_data':data};
			 	console.log(data_save);

				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}		
				$http.post(data_link,data_save,config)
				.then (function success(response){	
					// $scope.savemsg=response.data.server_msg;
					// $scope.id_header=response.data.id_header;
					// console.log($scope.savemsg);
					// $scope.new_entry();

				},
				function error(response){
					$scope.errorMessage = 'Error - Receord Not Saved!';
					$scope.message = '';
				});

			}
	
}]);



app.controller('product_master',['$scope','$rootScope','$http',
function($scope,$rootScope,$http){
	"use strict";
	
			$scope.products={};
			$scope.ledgers={};
			$rootScope.FormInputArray=[];	

			var BaseUrl=domain_name+"Project_controller/product_master/";
			var AcTranType;
			// $scope.initarray=function(trantype){		
			// 	BaseUrl=BaseUrl+trantype+'/';	
			// 	AcTranType=trantype;
			// }

			var CurrentDate=new Date();
			var year = CurrentDate.getFullYear();
			var month = CurrentDate.getMonth()+1;
			var dt = CurrentDate.getDate();
			if (dt < 10) {	dt = '0' + dt;}
			if (month < 10) {month = '0' + month;}
			$scope.tran_date=year+'-' + month + '-'+dt;
			
			
			var data_link=query_result_link+"32/";$http.get(data_link).then(function(response) {$scope.master_lovs=response.data;});
			//console.log(data_link);
			var data_link=query_result_link+"33/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});

			var data_link=query_result_link+"39/";$http.get(data_link).then(function(response) {$scope.product_group_list=response.data;});
			var data_link=query_result_link+"38/";$http.get(data_link).then(function(response) {$scope.brand_list=response.data;});
			
			var data_link=query_result_link+"40/";$http.get(data_link).then(function(response) {$scope.tax_list=response.data;});
			var data_link=query_result_link+"41/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});
			console.log(data_link);

			console.log(data_link);
						
			$scope.entry_index=0;
			$scope.transetting='ENTRY';	
			$scope.id_header=0;

			$rootScope.FormInputArray[0] =
			{	
					group_id:0,	
					brand_id:0,
					list_of_values:[{id_detail:'',productname:'',group_id:'',brand_id:'',sell_discount:'',mrp:'',spl_discount:'',
					hsncode:'',tax_ledger_id:'',label_print:'',available_qnty:'',minimum_stock:'',exp_mmyy:'',active_inactive:'ACTIVE'}]	
			};
			
			$scope.new_entry=function()
			{
				$rootScope.FormInputArray[0] =
				{	
					group_id:0,	
					brand_id:0,
					list_of_values:[{id_detail:'',productname:'',group_id:'',brand_id:'',sell_discount:'',mrp:'',spl_discount:'',
					hsncode:'',tax_ledger_id:'',label_print:'',available_qnty:'',minimum_stock:'',exp_mmyy:'',active_inactive:'ACTIVE'}]				
				};
			}

			$scope.add_entry=function(detail_type)
			{
				console.log(detail_type);
				if(detail_type=='lov_list')
				{
					var length=$rootScope.FormInputArray[0].list_of_values.length;					
					$scope.FormInputArray[0].list_of_values[length]={id_detail:'',productname:'',
					group_id:$rootScope.FormInputArray[0].group_id,
					brand_id:$rootScope.FormInputArray[0].brand_id,
					sell_discount:'',mrp:'',spl_discount:'',hsncode:'',tax_ledger_id:'',label_print:'',available_qnty:'',minimum_stock:'',exp_mmyy:'',active_inactive:'ACTIVE'};				

				}			
			}
			
			$scope.view_list=function(group_id,brand_id,search)
			{
				var data_link=BaseUrl+"VIEWALLVALUE/"+group_id+'/'+brand_id+'/'+search;
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					angular.forEach(response.data,function(value,key)
					{

					//	console.log('test'+value.list_of_values);

						$rootScope.FormInputArray[0] =
							{		
								group_id:value.group_id,	
								brand_id:value.brand_id,								
								list_of_values:value.list_of_values							
							};		
					});	
				});	

				// $scope.id_header=id_header;
				// $rootScope.transetting='ENTRY';	
				// $scope.form_control();

			}		
		
							
			$scope.savedata=function()
			{
				var data_link=BaseUrl+"SAVE/";
				console.log(data_link);
				var success={};		
			
				var data_save = JSON.stringify($rootScope.FormInputArray);	
			    	console.log(data_save);

				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}		
				$http.post(data_link,data_save,config)
				.then (function success(response){	
					//$scope.savemsg=response.data.server_msg;
					//$scope.id_header=response.data.id_header;
					//console.log($scope.savemsg);
					$scope.new_entry();

				},
				function error(response){
					$scope.errorMessage = 'Error - Receord Not Saved!';
					$scope.message = '';
				});

			}
	
}]);


app.controller('product_master_minimum_stock',['$scope','$rootScope','$http',
function($scope,$rootScope,$http){
	"use strict";
	
			$scope.products={};
			$scope.ledgers={};
			$rootScope.FormInputArray=[];	

			var BaseUrl=domain_name+"Project_controller/product_master_minimum_stock/";
			var AcTranType;
			// $scope.initarray=function(trantype){		
			// 	BaseUrl=BaseUrl+trantype+'/';	
			// 	AcTranType=trantype;
			// }

			var CurrentDate=new Date();
			var year = CurrentDate.getFullYear();
			var month = CurrentDate.getMonth()+1;
			var dt = CurrentDate.getDate();
			if (dt < 10) {	dt = '0' + dt;}
			if (month < 10) {month = '0' + month;}
			$scope.tran_date=year+'-' + month + '-'+dt;
			
			
			var data_link=query_result_link+"32/";$http.get(data_link).then(function(response) {$scope.master_lovs=response.data;});
			//console.log(data_link);
			var data_link=query_result_link+"33/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});

			var data_link=query_result_link+"39/";$http.get(data_link).then(function(response) {$scope.product_group_list=response.data;});
			var data_link=query_result_link+"38/";$http.get(data_link).then(function(response) {$scope.brand_list=response.data;});
			console.log(data_link);


			var data_link=query_result_link+"40/";$http.get(data_link).then(function(response) {$scope.tax_list=response.data;});
			var data_link=query_result_link+"41/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});
			//console.log(data_link);

			
						
			$scope.entry_index=0;
			$scope.transetting='ENTRY';	
			$scope.id_header=0;

			$rootScope.FormInputArray[0] =
			{	
					group_id:0,	
					brand_id:0,
					list_of_values:[{id_detail:'',productname:'',group_id:'',brand_id:'',sell_discount:'',mrp:'',order_qnty:'',
					hsncode:'',tax_ledger_id:'',label_print:'',available_qnty:'',
					minimum_stock:'',exp_mmyy:'',active_inactive:'ACTIVE',brand_name:'',group_name:''}]	
			};
			
			$scope.new_entry=function()
			{
				$rootScope.FormInputArray[0] =
				{	
					group_id:0,	
					brand_id:0,
					list_of_values:[{id_detail:'',productname:'',group_id:'',brand_id:'',sell_discount:'',mrp:'',order_qnty:'',
					hsncode:'',tax_ledger_id:'',label_print:'',available_qnty:'',minimum_stock:'',
					exp_mmyy:'',active_inactive:'ACTIVE',brand_name:'',group_name:''}]				
				};
			}

			$scope.add_entry=function(detail_type)
			{
				console.log(detail_type);
				if(detail_type=='lov_list')
				{
					var length=$rootScope.FormInputArray[0].list_of_values.length;					
					$scope.FormInputArray[0].list_of_values[length]={id_detail:'',productname:'',
					group_id:$rootScope.FormInputArray[0].group_id,
					brand_id:$rootScope.FormInputArray[0].brand_id,
					sell_discount:'',mrp:'',order_qnty:'',hsncode:'',
					tax_ledger_id:'',label_print:'',available_qnty:'',
					minimum_stock:'',exp_mmyy:'',active_inactive:'ACTIVE',brand_name:'',group_name:''};				

				}			
			}
			
			$scope.view_list=function(group_id,brand_id,search)
			{
				var data_link=BaseUrl+"VIEWALLVALUE/0/"+brand_id+'/'+search;
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					angular.forEach(response.data,function(value,key)
					{

					//	console.log('test'+value.list_of_values);

						$rootScope.FormInputArray[0] =
							{		
								group_id:value.group_id,	
								brand_id:value.brand_id,								
								list_of_values:value.list_of_values							
							};		
					});	
				});	

				// $scope.id_header=id_header;
				// $rootScope.transetting='ENTRY';	
				// $scope.form_control();

			}		
		
							
			$scope.savedata=function()
			{
				var data_link=BaseUrl+"SAVE/";
				console.log(data_link);
				var success={};		
			
				var data_save = JSON.stringify($rootScope.FormInputArray);	
			    	console.log(data_save);

				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}		
				$http.post(data_link,data_save,config)
				.then (function success(response){	
					//$scope.savemsg=response.data.server_msg;
					//$scope.id_header=response.data.id_header;
					//console.log($scope.savemsg);
					

					$scope.new_entry();

				},
				function error(response){
					$scope.errorMessage = 'Error - Receord Not Saved!';
					$scope.message = '';
				});

			}

			$scope.print=function()
			{
				var printtype='PRODUCT_MINIMUM_STOCK';
				var id=0;
				var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
				console.log(data_link);
				//	$http.get(data_link).then(function(response){});
				window.popup(data_link); 


			}







	
}]);

  //************************ACCOUNT NEW RECEIVE  START*****************************************//
	app.controller('AccountsTransaction',['$scope','$window','$rootScope','$http','$location','AccountsTransaction',
	function($scope,$window,$rootScope,$http,$location,AccountsTransaction)
	{
		"use strict";
		
		//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
		var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/";
		var AcTranType;
		var CRDR_TYPE;
	
		//CRDR_TYPE='CR';
		$scope.initarray=function(trantype){		
			BaseUrl=BaseUrl+trantype+'/';	
			AcTranType=trantype;
			if(AcTranType=='RECEIVE'){CRDR_TYPE='DR';}
			else
			{CRDR_TYPE='CR';}
		}
		console.log(BaseUrl);
		//ARRAY EXPERIMENT
		//$scope.FormInputArray={};
		$scope.accounts_id={};
		$scope.accounts_name={};
		$scope.accounts_amount={};
		$rootScope.FormInputArray=[];	
		document.getElementById('tran_date').focus();
		
		var CurrentDate=new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();
		
		if (dt < 10) {	dt = '0' + dt;}
		if (month < 10) {month = '0' + month;}
		$scope.fromdate=year+'-' + month + '-'+dt;
		$scope.todate=year+'-' + month + '-'+dt;
	
	
		$scope.delete_transaction=function(id)
		{	
				var data_link=BaseUrl+"DELETE_TRANSACTION";		
				console.log(data_link);	
				var success={};		
				var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
				console.log(data_save);	
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config).then (function success(response){
					$scope.GetAllList($scope.startdate,$scope.enddate);
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});
		}
		
		
		//CALL GLOBAL FUNCTION
		//https://stackoverflow.com/questions/15025979/can-i-make-a-function-available-in-every-controller-in-angular
	
				$rootScope.FormInputArray[0] =
				{	
				id_header:'',
				id_detail:'',
				trantype:'',
				truck_id:'',
				truck_no:'',
				employee_id:'',
				employee_name:'',
				tran_code:'',	
				tran_date:$scope.todate,
				CRDR_TYPE:'CR',
				ledger_account_id:'',
				ledger_account_name:'',
				ledger_amount:'',				
				transaction_details:'',
				detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
				details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
			};
			
		$rootScope.FormInputArray[0].trantype=AcTranType;
		console.log('AcTranType '+AcTranType+$rootScope.FormInputArray[0].trantype);
		$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
	
		//$rootScope.max_bill_count=$rootScope.FormInputArray[1]['billdetail'].length;	
		//console.log('BANK DETAILS '+$rootScope.FormInputArray[0]['bankdetail'][0].BRANCH);
	
		// console.log('BANK DETAILS '+$rootScope.FormInputArray[1]['bankdetail'][0].BRANCH);
		// console.log('arraindx billdetail '+
		// $rootScope.FormInputArray[0]['billdetail'][2]['testarray'][0].val);
		// console.log('$rootScope.max_bill_count '+$rootScope.max_bill_count);
		//https://stackoverflow.com/questions/18086865/angularjs-move-focus-to-next-control-on-enter
	
			$scope.mainOperation=function(event,element_name,index_value,child_index,searchItem,frmrpt_simple_query_builder_id)
			{	
						
				$rootScope.TotalDrAmt=0;
				$rootScope.TotalCrAmt=0;
				$rootScope.TotalBalanceAmt=0;
							
				if(searchItem=='searchItem')
				{$scope.search(element_name,index_value,child_index,$scope.FormInputArray[index_value].detailtype,frmrpt_simple_query_builder_id);}
				
				var CRDR_TYPE=angular.uppercase($scope.FormInputArray[index_value].CRDR_TYPE);
	
				if(CRDR_TYPE=='D' || CRDR_TYPE=='DR')
				{$scope.FormInputArray[index_value].CRDR_TYPE='DR';}
				else if(CRDR_TYPE=='C' || CRDR_TYPE=='CR')
				{$scope.FormInputArray[index_value].CRDR_TYPE='CR';}
				else
				{$scope.FormInputArray[index_value].CRDR_TYPE='';}
	
				//TOTAL DEBIT,CREDIT AND BALANCE AMOUNT CALCULATION
				for(var i=0; i<=$scope.maxloopvalue;i++)
				{			
					if($scope.FormInputArray[i].CRDR_TYPE=='CR')
					{
						$rootScope.TotalCrAmt=
						Number($rootScope.TotalCrAmt || 0)+Number($rootScope.FormInputArray[i].ledger_amount || 0);
					}
					if($scope.FormInputArray[i].CRDR_TYPE=='DR')
					{
						$rootScope.TotalDrAmt=
						Number($rootScope.TotalDrAmt || 0)+Number($rootScope.FormInputArray[i].ledger_amount || 0);
					}
				}
	
				$rootScope.TotalBalanceAmt=
				Number($rootScope.TotalDrAmt || 0)-Number($rootScope.TotalCrAmt || 0);
				//TOTAL DEBIT,CREDIT AND BALANCE AMOUNT CALCULATION
	
	
	
					if(event.keyCode === 13)
					{
						//OK
						if(element_name=='tran_date')
						{document.getElementById('CRDR_TYPE-0').focus();}
						//OK				
						if(element_name=='CRDR_TYPE')
						{document.getElementById('ledger_account_name-'+index_value).focus();}
	
						//OK
						if(element_name=='ledger_account_name')
						{document.getElementById('ledger_amount-'+index_value).focus();}
						//OK
						if(element_name=='ledger_amount')
						{						
							var indx=Number(index_value || 0)+1;
							var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;
	
							if($rootScope.TotalBalanceAmt!=0 && Number(crtextamt || 0)>0)
							{	
								var CRDR_TYPE='';
								var diffamt='';
	
								if($rootScope.TotalDrAmt>$rootScope.TotalCrAmt)
								{CRDR_TYPE='CR';}
	
								if($rootScope.TotalDrAmt<$rootScope.TotalCrAmt)
								{CRDR_TYPE='DR';}
	
							
								$rootScope.FormInputArray[$rootScope.FormInputArray.length] =
								{	
									id_header:'',
									id_detail:'',
									trantype:'',
									truck_id:'',
									truck_no:'',
									employee_id:'',
									employee_name:'',
									tran_code:'',	
									tran_date:$scope.todate,
									CRDR_TYPE:'DR',
									ledger_account_id:'',
									ledger_account_name:'',
									ledger_amount:'',				
									transaction_details:'',
									detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
									details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
								};
	
								$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
							}
	
							//DELETE BLANK ELEMENT OF ARRAY
							
							for(var i=1; i<=$rootScope.maxloopvalue-1;i++)
							{							
								if(Number($scope.FormInputArray[i].ledger_amount || 0)<=0 || 
								Number($scope.FormInputArray[i].ledger_account_id || 0)==0)
								{$scope.FormInputArray.splice(i, 1);}
								$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
							}
	
	
							if($scope.FormInputArray[index_value].detailtype=='NA')
							{document.getElementById('CRDR_TYPE-'+indx).focus();}						
							else if($scope.FormInputArray[index_value].detailtype!='NA')
							{document.getElementById('BILL_INSTRUMENT_NO-'+index_value+0).focus();}
							else($rootScope.TotalBalanceAmt==0)
							{document.getElementById('transaction_details').focus();}
	
	
						}
	
						//ARRAY DETAILS SECTION
	
						//BANK DETAILS
						if($scope.FormInputArray[index_value].detailtype=='BANK')
						{
	
							if(element_name=='BILL_INSTRUMENT_NO')
							{document.getElementById('CHQDATE-'+index_value+child_index).focus();}
							if(element_name=='CHQDATE')
							{document.getElementById('BANKNAME-'+index_value+child_index).focus();}
							if(element_name=='BANKNAME')
							{document.getElementById('BRANCH-'+index_value+child_index).focus();}
							if(element_name=='BRANCH')
							{document.getElementById('AMOUNT-'+index_value+child_index).focus();}
	
							if(element_name=='AMOUNT')
							{
								var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;
								var bank_amt=0;
								var bankdetail_length=$scope.FormInputArray[index_value].details.length;
								for(var i=0; i<=bankdetail_length-1;i++)
								{			
									bank_amt=bank_amt+
									Number($scope.FormInputArray[index_value].details[i].AMOUNT |0);
								}
								if(crtextamt>bank_amt && 
									Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
								{													
									$scope.FormInputArray[index_value].details[bankdetail_length]=
									{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''};	
								}
								
	
								if(crtextamt>bank_amt && 
									Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
								{							
									document.getElementById('BILL_INSTRUMENT_NO-'+index_value+Number(child_index+1 |0)).focus();
								}
								else
								{document.getElementById('CRDR_TYPE-'+Number(index_value+1)).focus();}
	
								for(var i=1; i<=bankdetail_length-1;i++)
								{							
									if(Number($scope.FormInputArray[index_value].details[i].AMOUNT || 0)<=0 )
									{$scope.FormInputArray[index_value].details.splice(i, 1);}
								}
							
							}
						}
						//BANK DETAILS END
	
						//BILL DETAILS
						if($scope.FormInputArray[index_value].detailtype=='SALE_BILL' || 
						$scope.FormInputArray[index_value].detailtype=='PURCHASE_BILL' || 
						$scope.FormInputArray[index_value].detailtype=='STAFF_DRIVERS')	
						{
							if(element_name=='BILL_INSTRUMENT_NO')
							{document.getElementById('AMOUNT-'+index_value+child_index).focus();}
							
							if(element_name=='AMOUNT')
							{
								var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;
								var total_bill_amt=AccountsTransaction.bill_summary(index_value);
								var current_bill_amt=Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0);
								var billdetail_length=$rootScope.FormInputArray[index_value].details.length;
		
								if(crtextamt>total_bill_amt && current_bill_amt>0)
								{							
									$scope.FormInputArray[index_value].details[billdetail_length]=
									{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''};							
								
								}						
								
								if(crtextamt>total_bill_amt && Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
								{document.getElementById('BILL_INSTRUMENT_NO-'+index_value+Number(child_index+1 |0)).focus();}
								else
								{document.getElementById('CRDR_TYPE-'+Number(index_value+1)).focus();}
		
								for(var i=1; i<=billdetail_length-1;i++)
								{							
									if(Number($scope.FormInputArray[index_value].details[i].AMOUNT || 0)<=0 )
									{$scope.FormInputArray[index_value].details.splice(i, 1);}
								}
							
							}
	
	
						}				
						//BILL DETAILS END 	
	
	
	
	
	
	
					}
					//ENTER EVENT END 
	
	
	
	
			}//mainOperation END
	
	
		
		//===================SEARCH STRAT===============================================	
		
	
		//SEARCH SECTION
			$rootScope.search = function(element_name,index_value,child_index,detailtype,frmrpt_simple_query_builder_id)
			{
					
			$rootScope.element_name=element_name;
			$rootScope.element_value='';
			$rootScope.index_value=index_value;
			$rootScope.child_index=child_index;	
			$rootScope.detailtype=detailtype;	
	
			var CRDR_TYPE=$scope.FormInputArray[index_value].CRDR_TYPE;
			var parent_element_id=0;
			
			
			if(element_name=='ledger_account_name'){	
				$rootScope.element_value= $scope.FormInputArray[index_value].ledger_account_name;	
			}
			
			if(element_name=='BILL_INSTRUMENT_NO')
			{
				parent_element_id=$scope.FormInputArray[index_value].ledger_account_id;
				$rootScope.element_value= 
				$scope.FormInputArray[index_value]['details'][child_index].BILL_INSTRUMENT_NO;
			}
			
	
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];		
			console.log(detailtype);
	
			AccountsTransaction.list_items(element_name,parent_element_id,CRDR_TYPE,AcTranType,detailtype);	
					 
					$rootScope.searchItems.sort();	
					var myMaxSuggestionListLength = 0;
					for(var i=0; i<$rootScope.searchItems.length; i++){
							var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i]);
							var searchTextSmallLetters =angular.uppercase($rootScope.element_value);
							if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) !== -1){
									$rootScope.suggestions.push(searchItemsSmallLetters);
									myMaxSuggestionListLength += 1;
									if(myMaxSuggestionListLength === 12){
											break;
									}
							}
					}
			};
	
		$rootScope.$watch('selectedIndex',function(val)
		{			
			if(val !== -1) {    
				if($rootScope.element_name=='ledger_account_name'){	
					$scope.FormInputArray[$rootScope.index_value].ledger_account_name =
					$rootScope.suggestions[$rootScope.selectedIndex];	
				}	
	
				if($rootScope.element_name=='BILL_INSTRUMENT_NO')
				{
					$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO=
					$rootScope.suggestions[$rootScope.selectedIndex];	
				}
				
			}
		});		
	
			$rootScope.checkKeyDown = function(event)
			{
				if(event.keyCode === 40){//down key, increment selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
						$rootScope.selectedIndex++;
					}else{
						$rootScope.selectedIndex = 0;
					}
					
				}else if(event.keyCode === 38){ //up key, decrement selectedIndex
					event.preventDefault();
					if($rootScope.selectedIndex-1 >= 0){
						$rootScope.selectedIndex--;
					}else{
						$rootScope.selectedIndex = $rootScope.suggestions.length-1;
					}
				}
				else if(event.keyCode === 13){ //enter key, empty suggestions array
	
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}	
				}
				else if(event.keyCode === 9){ //enter tab key
					if($rootScope.selectedIndex>-1){
						$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					}			
	
				}else if(event.keyCode === 27){ //ESC key, empty suggestions array
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
					console.log($rootScope.selectedIndex);
					event.preventDefault();
					$rootScope.suggestions = [];
					$rootScope.searchItems=[];
					$rootScope.selectedIndex = -1;
				}else{
					$rootScope.search();	
				}
			};
	
			//ClickOutSide
			if($rootScope.element_name=='ledger_account_name'){			
				var exclude1 = document.getElementById($rootScope.element_name+'-'+$rootScope.index_value);
			}
	
			if($rootScope.element_name=='BILL_INSTRUMENT_NO')
			{	
				var exclude1 = document.getElementById($rootScope.element_name+'-'+$rootScope.index_value+
				$rootScope.child_index);
			}
	
		
	
			$rootScope.hideMenu = function($event){
				$rootScope.search();
				//make a condition for every object you wat to exclude
				if($event.target !== exclude1) {
					$rootScope.searchItems=[];
					$rootScope.suggestions = [];
					$rootScope.selectedIndex = -1;
				}
			};
	
	
		$rootScope.checkKeyUp = function(event){ 		
			if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
	
				if($rootScope.element_name=='ledger_account_name'){					
					if($scope.FormInputArray[$rootScope.index_value].ledger_account_name === ""){
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];
						$rootScope.selectedIndex = -1;
					}
				}
	
				if($rootScope.element_name=='BILL_INSTRUMENT_NO'){		
							
					if($scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO === "")
					{
						$rootScope.suggestions = [];
						$rootScope.searchItems=[];
						$rootScope.selectedIndex = -1;
					}
				}  
				
			
			}
		};	
		//======================================
		//List Item Events
		//Function To Call on ng-click
			$rootScope.AssignValueAndHide = function(index)
			{
	
				if($rootScope.element_name=='ledger_account_name')
				{		
					$scope.FormInputArray[$rootScope.index_value].ledger_account_name =
					$rootScope.suggestions[index];	
					var str=$scope.FormInputArray[$rootScope.index_value].ledger_account_name;			
					var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
					var data_link=BaseUrl+"ledger_account_id/"+id;					
					//console.log(data_link);					
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){
							$scope.FormInputArray[$rootScope.index_value].ledger_account_id=value.id;
							$scope.FormInputArray[$rootScope.index_value].ledger_account_name=value.name;
							$scope.FormInputArray[$rootScope.index_value].detailtype=value.COST_CENTER;
	
							if(value.COST_CENTER=='BANK')
							{
								$scope.FormInputArray[$rootScope.index_value]['details'][0].BANKNAME=
								$scope.FormInputArray[$rootScope.index_value].ledger_account_name;
	
								$scope.FormInputArray[$rootScope.index_value]['details'][0].BANKNAME=
								$scope.FormInputArray[$rootScope.index_value].ledger_account_name;
	
							}
							
						});
					});
				}
	
				if($rootScope.element_name=='BILL_INSTRUMENT_NO')
				{		
					var str=$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO =
					$rootScope.suggestions[index];	
					
				//	var str=$scope.FormInputArray[$rootScope.index_value].ledger_account_name;			
					var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
	
					if($rootScope.detailtype=='STAFF_DRIVERS')
					{var data_link=BaseUrl+"employee_id/"+id;}
	
					if($rootScope.detailtype=='SALE_BILL' || $rootScope.detailtype=='PURCHASE_BILL')
					{var data_link=BaseUrl+"BILLID/"+id;}
						
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){
	
							$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].TABLE_NAME =value.TABLE_NAME;
							$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].TABLE_ID =value.id;
							$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO =value.name;
							$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].AMOUNT =value.bill_due_amt;											
												
						});
					});
					
				}
				
	
				$rootScope.suggestions=[];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			};
		
		//===================END SEARCH SECTION =========================================
		
	
		$scope.savedata=function(tran_type)
		{
			var data_link=BaseUrl+"SAVE";
			var success={};		
			sessionStorage.setItem("selecteddate", $rootScope.FormInputArray[0].tran_date);
	
			var data_save = JSON.stringify($rootScope.FormInputArray);	
			console.log(data_save);
	
			var config = {headers : 
				{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
			}		
			$http.post(data_link,data_save,config)
			.then (function success(response){	
				$scope.savemsg='Receord Has been saved Successfully!';
				$scope.print_consignment(response.data.id_header,tran_type);
				$scope.get_set_value(response.data,'','RE_INITIAL_INPUT_ARRAY');
			},
			function error(response){
				$scope.errorMessage = 'Error - Receord Not Saved!';
				$scope.message = '';
			});
	
		}
	
		$scope.get_set_value=function(datavalue,datatype,operation)
		{
			if(operation=='SETVALUE')
			{
				if(angular.isUndefined(datavalue)==true)
				{
					if(datatype=='num')
					{return 0;}
					if(datatype=='str')
					{return '';}		
				}
				else
				{return datavalue;}
			}
			if(operation=='DRCRCHECKING')
			{	
				var operation_status='OK';
				if(Number($rootScope.TotalDrAmt)!=Number($rootScope.TotalCrAmt))
				{ 
					operation_status='NOTOK'
					$scope.savemsg='Debit and Credit Amount must be same';
				}		
				if(operation_status=='OK')
				{$scope.savedata(datatype);}
	
			}
			
			if(operation=='RE_INITIAL_INPUT_ARRAY')
			{		
				$rootScope.FormInputArray.length=0;		
	
				$rootScope.FormInputArray[0] =
				{	
				id_header:'',
				id_detail:'',
				trantype:'',
				truck_id:'',
				truck_no:'',
				employee_id:'',
				employee_name:'',
				tran_code:'',	
				tran_date:sessionStorage.getItem("selecteddate"),
				CRDR_TYPE:'CR',
				ledger_account_id:'',
				ledger_account_name:'',
				ledger_amount:'',				
				transaction_details:'',
				detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
				details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',dsl_qnty:''
				,dsl_rate:'',trip_cashamt:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
			};
	
				$rootScope.TotalDrAmt=0;
				$rootScope.TotalCrAmt=0;
				$rootScope.TotalBalanceAmt=0;
				$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
				document.getElementById('tran_date').focus();
			}
	
			if(operation=='VIEWALLVALUE')
			{	
				
				var data_link=BaseUrl+"VIEWALLVALUE/"+datavalue;
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					//console.log(response.data);
					angular.forEach(response.data,function(value,key){
						
							$rootScope.FormInputArray[key] =
							{	
								id_header:value.id_header,
								id_detail:value.id_detail,
								trantype:value.trantype,
								truck_id:value.truck_id,
								truck_no:value.truck_no,
								employee_id:value.employee_id,
								employee_name:value.employee_name,
								tran_code:value.tran_code,	
								tran_date:value.tran_date,
								CRDR_TYPE:value.CRDR_TYPE,
								ledger_account_id:value.ledger_account_id,
								ledger_account_name:value.ledger_account_name,
								ledger_amount:value.ledger_amount,				
								transaction_details:value.transaction_details,						
								detailtype:value.detailtype,//BANK,BILL/NA						
								details:value.details						
							};
							//console.log('Array key'+key+'cr_ledger_account_name '+value.ledger_account_name);
							
					});	
					for(var i=1; i<=$rootScope.maxloopvalue-1;i++)
					{							
						if(Number($scope.FormInputArray[i].cr_ledger_account || 0)==0)
						{$scope.FormInputArray.splice(i, 1);}					
					}
					$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
					console.log('Array length'+$rootScope.FormInputArray.length);
	
				});		
		
			//	FormInputArray
			}
	
	
		}
		
		
		$scope.GetAllList=function(fromdate,todate){
				//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
				//data list GetAllConsignment			
				var data_link=BaseUrl+'GetAllList/'+AcTranType+'/-/-/'+fromdate+'/'+todate;
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{$scope.ListOfTransactions=response.data;});
		}
		
		$scope.print_consignment = function(id_header,tran_type) 
		{ 
			var data_link=domain_name+"Project_controller/print_all/"+id_header+'/'+tran_type;		
			window.popup(data_link); 
		
		};
	
		
	
	}]);

/*
	Masked Input plugin for jQuery
	Copyright (c) 2007-2013 Josh Bush (digitalbush.com)
	Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
	Version: 1.3.1
*/

