<?php
$company_id=$this->session->userdata('COMP_ID');
$whr="id=".$company_id;
$company_name=$this->projectmodel->GetSingleVal('NAME','company_details',$whr); 

?>
	  
	  
	  <header class="main-header">
        <!-- Logo -->
       <!-- <a href="www.adequatesolutions.co.in" class="logo">-->
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-size: 18px;
}
-->
          </style>
          


          
          <span class="logo"><?php echo 'Welcome '.$this->session->userdata('login_name'); ?></span>
          <!-- logo for regular state and mobile devices -->
         <!-- <span class="logo-lg"><b>Pharma</b>Soft</span>-->
        <!--</a>-->
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
		
		
          <div align="center">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
              <span class="sr-only">Toggle navigation</span> </a>
            
            
              <span   style="margin-left:100px;" >
              <span class="style1"><?php echo $company_name; ?></span></span>
            
          </div>
          <div class="navbar-custom-menu" >
		  
		  	  	 
           <?php /*?> <ul class="nav navbar-nav">			
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="hidden-xs"><?php echo $this->session->userdata('login_name'); ?></span></a>
              </li>
            </ul><?php */?>
			
			
          </div>
        </nav>
      </header>
	  
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel"></div>
        
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
          <!--<li class="header">SELL SECTION</li>-->
           
		    <li><a href="<?php echo ADMIN_BASE_URL?>
				Project_controller/dashboard/"><i class="fa fa-book"></i> 
			<span>Dash Board</span></a>
			</li> 
		
<?php 

$login_status=$this->session->userdata('login_status');

if($login_status=='SUPER' || $login_status=='ADMIN' ) { 

?>	   
		
 <li class="header">DYNAMIC FORM-RPT TEMPLATE</li>
	
 <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> <span>Master Create</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
 		 <ul class="treeview-menu">		

		 <li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/SHAREDATA/"><i class="fa fa-circle-o"></i>SHARE DATA</a></li> 		 
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/Master_upload/"><i class="fa fa-circle-o"></i>Master Data Upload</a></li> 		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteFormReport/list/"><i class="fa fa-circle-o"></i>TEMPLATE FORM SET</a></li> 
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/7/list/"><i class="fa fa-circle-o"></i>General Master</a></li> 
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/31/list/"><i class="fa fa-circle-o"></i>Menu Header</a> </li>
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/32/list/"><i class="fa fa-circle-o"></i>Menu Detail</a> </li>	
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/30/list/"><i class="fa fa-circle-o"></i>Query Builder</a> </li>	
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TemplateReports/29/list/"><i class="fa fa-circle-o"></i>OPEN STOCK REPORT</a> </li>
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/32/list/"><i class="fa fa-circle-o"></i>Account Group setting</a> </li>
		
		
		<?php /*?><li><a href="<?php echo ADMIN_BASE_URL?>
		Project_controller/TempleteForm/2/list/">
		<i class="fa fa-circle-o"></i>TEMPLATE FORM TESTING</a>
		</li> 
		
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Project_controller/TemplateReports/Products/">
		<i class="fa fa-circle-o"></i>TEMPLATE REPORT</a>
		</li> 
		<?php */?>
		
		
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Project_controller/urgent_codes/">
		<i class="fa fa-circle-o"></i>Urgent Codes</a>
		</li> 
		
				 
        </ul>
	  </li> 
	
<?php } ?>	
	
<?php if($login_status=='ADMIN' || $login_status=='SUPER') { ?>				
	<li class="header">MASTER SECTION</li>
	
	<li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> <span>Master</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
 				  <ul class="treeview-menu">
		
			
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/8/list/"><i class="fa fa-circle-o"></i>Company Setting</a></li> 
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/31/list/"><i class="fa fa-circle-o"></i>User Master</a></li> 		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/26/list/"><i class="fa fa-circle-o"></i>State Master</a></li>
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/44/list/"><i class="fa fa-circle-o"></i>Rack Master</a></li> 	 		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/21/list/"><i class="fa fa-circle-o"></i>Brand Master</a></li> 		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/22/list/"><i class="fa fa-circle-o"></i>Product Group Master</a></li> 		 
		<?php /*?><li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/2/list/"><i class="fa fa-circle-o"></i>Product Master Old</a></li> 	<?php */?>	
	<?php /*?>	<li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/product_master"><i class="fa fa-circle-o"></i>Product Master -old</a></li><?php */?>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/product_master"><i class="fa fa-circle-o"></i>Product Master</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/38/list/"><i class="fa fa-circle-o"></i>Potency Master</a></li> 
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/39/list/"><i class="fa fa-circle-o"></i>Pack Size Master</a></li> 
		
		<?php /*?><li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/40/list/"><i class="fa fa-circle-o"></i>Dose Discount Set</a></li> <?php */?>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/product_rate_master/master/47/">
		<i class="fa fa-circle-o"></i>Rate Master</a></li>
				
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/9/list/"><i class="fa fa-circle-o"></i>Vendor Master</a></li> 
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/41/list/"><i class="fa fa-circle-o"></i>Party Master</a></li> 
		
			<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/42/list/"><i class="fa fa-circle-o"></i>Agent Master</a></li> 
			
				<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/43/list/"><i class="fa fa-circle-o"></i>Patient Registration</a></li> 
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/23/list/"><i class="fa fa-circle-o"></i>Account Group Manage</a></li>					
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/24/list/"><i class="fa fa-circle-o"></i>Ledger Manage</a></li>			
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/TempleteForm/25/list/"><i class="fa fa-circle-o"></i>Doctor master</a></li>			
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/doctor_commission_set/listing/"><i class="fa fa-circle-o"></i>Doctor Commission Set</a></li>			
				 
        </ul>
	  </li>
	  
<?php } ?>		
	
	<li class="header">BILLING TRANSACTION <?php echo 'Comp id : '.$this->session->userdata('COMP_ID'); ?></li>
		
	<li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> <span>Transactions</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			
				
		<?php /*?><li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/Purchase"><i class="fa fa-circle-o"></i>Purchase</a></li>
		<li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/Sale_test"><i class="fa fa-circle-o"></i>Sale</a></li>
		<li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/purchase_rtn"><i class="fa fa-circle-o"></i>Purchase Return</a></li>		
        <li><a href="<?php echo ADMIN_BASE_URL?>Accounts_controller/load_form_report/sale_return"><i class="fa fa-circle-o"></i>Sale Return</a></li> 
		
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/purchase_entry/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Purchase</a></li>
		
		<?php */?>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/invoice_entry/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Sale Retail</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/invoice_entry_wholesale/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Sale Entry-Wholesale </a></li>
		
				
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/purchase_entry/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Purchase Entry</a></li>	
		
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/issue_entry/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Issue Entry</a></li>	
		
		<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/load_form_report/doctor_prescription/acc_tran/47/">
		<i class="fa fa-circle-o"></i>Prescription</a></li>
					
		
		
              </ul>
            </li>	
	
	<li class="header">BILLING REPORTS</li>
		
	<li class="treeview">
	  <a href="#">
		<i class="fa fa-table"></i> <span>Reports</span>
		<i class="fa fa-angle-left pull-right"></i>
	  </a>
        <ul class="treeview-menu">
	
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/PURCHASE_REGISTER/0/0/"><i class="fa fa-circle-o"></i>
		Purchase Register</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/SALE_REGISTER/0/0/"><i class="fa fa-circle-o"></i>
		Sales Register</a></li>
				
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/STOCK_REGISTER/"><i class="fa fa-circle-o"></i>Stock Register</a></li>
				
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/PRODUCT_WISE_PURCHASE/0/0/"><i class="fa fa-circle-o"></i>
		Product Wise Purchase</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/PRODUCT_WISE_SALE/0/0/"><i class="fa fa-circle-o"></i>
		Product Wise Sale</a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/EXPIRY_REGISTER/">
		<i class="fa fa-circle-o"></i>Expiry Register</a></li>
						
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/DEBTORS_SUMMARY/">
		<i class="fa fa-circle-o"></i>Party Ledger Summary </a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/DEBTORS_DETAILS/">
		<i class="fa fa-circle-o"></i>Party Ledger Detail </a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/CREDITORS_SUMMARY/">
		<i class="fa fa-circle-o"></i>Vendor Ledger Summary</a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/CREDITORS_DETAILS/">
		<i class="fa fa-circle-o"></i>Vendor Ledger Details</a></li>
		
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/DOCTOR_COMMISSION_SUMMARY/">
		<i class="fa fa-circle-o"></i>Doctor Commission Summary</a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/DOCTOR_COMMISSION_DETAILS/">
		<i class="fa fa-circle-o"></i>Doctor Commission Details</a></li>
		
		<li><a target ="_self" 	href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/DOCTOR_PRESCRIPTIONS/">
		<i class="fa fa-circle-o"></i>Doctor Prescription</a></li>
		
              </ul>
       </li>	
	
	
	
			
	<?php /*?><li class="header">GST REPORTS</li>
		
	<li class="treeview">
	  <a href="#">
		<i class="fa fa-table"></i> <span>Reports</span>
		<i class="fa fa-angle-left pull-right"></i>
	  </a>
              <ul class="treeview-menu">
			  
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/sale_purchase_crnote_drnote_register/27/list/">
		<i class="fa fa-circle-o"></i>Purchase Report</a></li>
		
			
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/sale_purchase_crnote_drnote_register/28/list/">
		<i class="fa fa-circle-o"></i>Sale Report</a></li>	  
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/BILL_WISE_PURCHASE/0/0/"><i class="fa fa-circle-o"></i>
		Bill Wise Purchase</a></li>
				
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/BILL_WISE_SALE/0/0/"><i class="fa fa-circle-o"></i>
		Bill Wise sales</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/HSN_WISE_SALE/0/0/"><i class="fa fa-circle-o"></i>
		HSN -Product Wise Summary</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/HSN_WISE_SUMMARY/0/0/"><i class="fa fa-circle-o"></i>
		HSN Wise Summary</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/GST_REPORT/0/0/"><i class="fa fa-circle-o"></i>
		GST Report</a></li>
		
              </ul>
       </li>	
		<?php */?>
	
	<li class="header">ACCOUNT TRANSACTION</li>
		
	<li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> <span>Transactions</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			
				
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/load_form_report/AccountsReceive">
		<i class="fa fa-circle-o"></i>Receive</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/load_form_report/AccountsPayment">
		<i class="fa fa-circle-o"></i>Payment</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/load_form_report/AccountsJournal">
		<i class="fa fa-circle-o"></i>Journal</a></li>
		
		<li><a href="<?php echo ADMIN_BASE_URL?>
		Accounts_controller/load_form_report/AccountsContra">
		<i class="fa fa-circle-o"></i>Contra</a></li>
		
					
         
              </ul>
            </li>	
	 
	<li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i> <span>Accounts Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
           <ul class="treeview-menu">
					
			<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/GENERAL_LEDGER/"><i class="fa fa-circle-o"></i>General Ledger</a></li>
			<li><a href="<?php echo ADMIN_BASE_URL?>accounts_controller/all_mis_reports/TRIAL_BALANCE/"><i class="fa fa-circle-o"></i>Trial balance</a></li>					
			
		   </ul>
	</li>
	
	  

			
		
			
	
	<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/logout">
	<i class="fa fa-circle-o text-red"></i> <span>Log Out</span></a></li>
		
	<li><a href="<?php echo ADMIN_BASE_URL?>Project_controller/changepassword">
	<i class="fa fa-circle-o text-yellow"></i> <span>
		Change Password</span></a></li>
		
		<?php /*?><li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>
		Lock Screen</span></a></li><?php */?>
			
			
		  </ul>
            </li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>