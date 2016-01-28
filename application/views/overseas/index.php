<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title id='Description'>Overseas Customer Info</title>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>   
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script><!DOCTYPE html>


        <link rel="icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/chosen.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery-ui-1.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/select2.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/bootstrap.css" type="text/css" media="screen">
        
        <link rel="stylesheet" href="<?= base_url() ?>public/css/styles.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/validationEngine.css">

        
        <link rel="stylesheet" href="<?= base_url() ?>public/css/style.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery.css" type="text/css" media="screen">
            <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
        <style type="text/css">@media print {.noprint { display:none; }}</style>

        <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>

    <!-- jQWidgets CSS -->

     

    
     <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>
    
    
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 

    </head>
    <body>

        <div id="page"><!-- container which holds data temporarly for pjax calls -->
               <div  class="bodyContents">
            <div class="navbar navbar-fixed-top  navbar-inverse ">
                <?php $this->load->view('menuoverseas'); ?> 

<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />

  <style type="text/css">@media print {.noprint { display:none; }}
            .content strong {
                color: red;
                text-align: left;

            }
            .textOverflowEllipsis {
                  overflow: hidden;
                  text-overflow: ellipsis;
                  white-space: normal;
                  width: 9em;
                }
            .form-horizontal .control-label {
                float: left;
                padding-top: 5px;
                text-align: left;
                width: 311px;
            }
        </style>


<!-- paging - end -->

<!-- paging - end -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
<!-- end of Menu includes -->

        
        <!-- -->
        <script type="text/javascript">
           $(document).ready(function () {

        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');

          
            // Create the Form Validator. 
            $('#customer_info').jqxValidator({
                hintType: 'label',
                rules: [
                       { input: '#supplier_name', message: 'Supplier name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#product_name', message: 'Product Name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#purchase_price', message: 'Purchase price is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#purchase_price', message: 'Enter a valid price!', action: 'keyup, blur', rule: 'number' },
                       { input: '#back_to_backorder', message: 'Back to Back order is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#other_remarks', message: 'Other Remarks is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#internation_sp', message: 'Selling price is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#internation_sp', message: 'Enter a valid Selling price!', action: 'keyup, blur', rule: 'number' }

                ]
            });

            // Validate form.
            $("#sendButton").click(function () {
                var validationResult = function (isValid) {
                    if (isValid) {
                        $("#customer_info").submit();
                    }
                }
                $('#customer_info').jqxValidator('validate', validationResult);
            });
            // prepare chart data as an array


            // prepare jqxChart settings
             // setup the chart
            // create DataTable.


        });
    </script>
            
                
                <div style="clear:both;"></div>

                            <div class="navbar commonActionsContainer noprint">
                    <div style="position: relative; top: 5px; left: 5.14999px;" class="actionsContainer row-fluid">
                        <div class="span2">
                            <span class="companyLogo"><img src="<?= base_url() ?>public/vdfiles/logo.png" title="logo.png" alt="logo.png">&nbsp;</span>
                        </div>

                    </div>
                </div>
                </div> 

               <div style="clear:both;"></div>
                     
                 
                       
                           
  
               
  


    <div style="padding:20px;">

       
        <span class="btn-group">
            <!-- <button id="Leads_listView_basicAction_LBL_ADD_RECORD" class="btn addButton" onclick='window.location.href="leads/add"'><i class="icon-plus icon-white"></i>&nbsp;<strong>Add Lead</strong></button> -->
        <?php if ($this->session->flashdata('message') != "") { ?>
            <div class="alert alert-message.success"><p style="height:10px; text-align:center;font-size:18px;"><?php echo $this->session->flashdata('message'); ?></p></div>
        <?php } ?>


        </span>
        <form class="navbar-form" id="customer_info" name="customer_info" method="post" action="<?= base_url() ?>overseas/savecustomerinfo" >
           
            <div class="detailViewContainer">
                <div class="form-horizontal">
                    <h1 style="margin-left:2%;">International Item Purchase</h1>
                </div>
                <div class="form-horizontal col-sm-6">
                    <div>
                        <label class="col-sm-4 control-label" for="supplier_name">Supplier Name</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="Supplier Name" id="supplier_name" name="supplier_name" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="product_name">Product Name</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Product Name" name="product_name" id="product_name" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="purchase_price">Purchase Price</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Purchase Price" id="purchase_price" name="purchase_price" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="back_to_backorder">Back to Back order</label>
                        <div class="col-sm-8">
                            <input placeholder="Back to Back order" id="back_to_backorder" name="back_to_backorder" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="other_remarks">Other Remarks</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Other Remarks" name="other_remarks" id="other_remarks"/>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="internation_sp">International selling price</label>
                        <div class="col-sm-8">
                            <input type="text" id="internation_sp" name="internation_sp" placeholder="International selling price" />
                        </div>
                    </div>
                           <div class="col-sm-4">
                            <button style="margin-top: 15px;" id="sendButton" type="button">Submit</button>
                    </div>
     
                </div>
                
            </div><!-- end of detailViewInfo row-fluid -->

        </form>
       
    </div>
<div style="clear:both;"></div></div>
<footer class="noprint">
    <p align="center" style="margin-top:5px;margin-bottom:0;"><a target="_blank" href="http://www.pure-chemical.com">Powered by Pure Chemicals</a></p>
</footer>

<script src="<?= base_url() ?>public/html5shim/html5.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-alert.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-tooltip.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-tab.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-collapse.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootbox.js"></script>


<!-- Added in the end since it should be after less file loaded -->
<script src="<?= base_url() ?>public/bootstrap/js/less.min.js" type="text/javascript"></script>

<!-- Added in the end since it should be after less file loaded -->
<?php $this->load->view('include_idletimeout.php'); ?>

</div>
</body></html>

   
