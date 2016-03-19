<!DOCTYPE html>
<html>
    <head>
    <title id="Description">Responsive Grid layout built with Bootstrap and jQWidgets</title>
    <!-- Bootstrap core CSS -->
    <link rel="SHORTCUT ICON" href="#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>public/bootstrap/css/bootstrap.css" type="text/css" media="screen">
    
    
    <!-- jQWidgets CSS -->
    
    <link rel="stylesheet" href="<?= base_url() ?>public/skins/softed/style.css" type="text/css" media="screen">
    
    <link rel="stylesheet" href="<?= base_url() ?>public/css/chosen.css" type="text/css" media="screen">
     

    
    <link rel="stylesheet" href="<?= base_url() ?>public/css/validationEngine.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= base_url() ?>public/css/guiders-1.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery_002.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery_003.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
    <!-- jQWidgets CSS -->

     

    
     <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 

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
        </style>
        
        <!-- -->
        <script type="text/javascript">
           $(document).ready(function () {
          
            // Create the Form Validator. 
            $('#customer_info').jqxValidator({
                hintType: 'label',
                rules: [
                       { input: '#supplier_name', message: 'Supplier name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#product_name', message: 'Product Name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#purchase_price', message: 'Purchase price is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#back_to_backorder', message: 'Back to Back order is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#other_remarks', message: 'Other Remarks is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#internation_sp', message: 'Selling price is required!', action: 'keyup, blur', rule: 'required' }

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
        <!-- -->
    </head>
    <body >
        <div id="page">
            <!-- container which holds data temporarly for pjax calls -->
            <div id="pjaxContainer" class="hide noprint"></div>
            <div class="navbar navbar-fixed-top  navbar-inverse noprint" style="min-width:1050px">
                <div class="navbar" id="topMenus">
                    <div class="navbar-inner" id="nav-inner">
                        <div class="menuBar row-fluid">
                            <div class="span8" style="height: 30px;">
                                <ul class="nav modulesList">
                                    <li class="tabs"><a class="alignMiddle " href="#"><img src="<?= base_url() ?>public/vdfiles/home.png" alt="Home" title="Home"></a>
                                    </li>
                                    <li class="tabs"><a id="menubar_item_Leads" href="<?= base_url() ?>leads" class="selected">Leads</a>
                                    </li>
                                    <li class="" id="moreMenu"><a data-toggle="dropdown" href="#moreMenu"><b class="caret"></b></a>
                                        <div class="dropdown-menu moreMenus">
                                            <a id="menubar_item_moduleManager" href="#" class="pull-right"></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="span3 row-fluid" id="headerLinks">
                                <span class="pull-right headerLinksContainer">
                                    <span class="dropdown span settingIcons "><a id="menubar_item_right_LBL_CRM_SETTINGS" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?= base_url() ?>public/vdfiles/setting.png" alt="CRM Settings" title="CRM Settings"></a>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a target="" id="menubar_item_right_LBL_CRM_SETTINGS" href="#">CRM Settings</a>
                                            </li>
                                        </ul>
                                    </span>
                                    <span class="dropdown span">
                                        <span class="dropdown-toggle row-fluid" data-toggle="dropdown" href="#"><a id="menubar_item_right_Administrator" class="userName textOverflowEllipsis span" title="User"><?php echo @$this->session->userdata['username'] . "-" . @$this->session->userdata['user_id'] . "-" . @$this->session->userdata['reportingto']; ?> <i class="caret"></i> </a>
                                        </span>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a target="" id="menubar_item_right_LBL_MY_PREFERENCES" href="#">My Preferences</a>
                                            </li>
                                            <li class="divider">&nbsp;
                                            </li>
                                            <li><a target="" id="menubar_item_right_LBL_SIGN_OUT" href="<?= base_url() ?>admin/logout">Sign Out</a>
                                            </li>
                                        </ul>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="announcement noprint" id="announcement">
                    <marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onmouseover="javascript:stop();" onmouseout="javascript:start();">LBL_NO_ANNOUNCEMENTS</marquee>
                </div>


                <div class="navbar commonActionsContainer noprint">
                    <div style="position: relative; top: 5px; left: 5.14999px;" class="actionsContainer row-fluid">
                        <div class="span2">
                            <span class="companyLogo"><img src="<?= base_url() ?>public/vdfiles/logo.png" title="logo.png" alt="logo.png">&nbsp;</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    
<div style="min-height: 636px;" class="bodyContents">

    <div class="mainContainer row-fluid">
        <div class="span2 row-fluid">
            <div class="row-fluid">
                <div class="sideBarContents">
                    <div class="quickLinksDiv">
                        <p onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_RECORDS_LIST" class="unSelectedQuickLink"><a class="quickLinks" href="<?= base_url() ?>leads"><strong>Leads List</strong></a>
                        </p>
                        <p onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_DASHBOARD" class="unSelectedQuickLink"><a class="quickLinks" href="<?= base_url() ?>dashboard"><strong>Dashboard</strong></a></p>
                    </div>
                    <div class="clearfix">
                    </div>
                    <div class="quickWidgetContainer accordion">
                        <div class="quickWidget">
                            <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Leads_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="module=Leads&amp;view=IndexAjax&amp;mode=showActiveRecords">
                                <span class="pull-left"><img class="imageElement" data-rightimage="layouts/vlayout/skins/images/rightArrowWhite.png" data-downimage="layouts/vlayout/skins/images/downArrowWhite.png" src="<?= base_url() ?>public/vdfiles/rightArrowWhite.png">
                                </span><h5 class="title widgetTextOverflowEllipsis pull-right" title="Recently Modified">Recently Modified</h5>
                                <div class="loadingImg hide pull-right">
                                    <div class="loadingWidgetMsg"><strong>Loading Widget</strong>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widgetContainer accordion-body collapse" id="Leads_sideBar_LBL_RECENTLY_MODIFIED" data-url="module=Leads&amp;view=IndexAjax&amp;mode=showActiveRecords">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contentsDiv span10 marginLeftZero">

            
            <div class="detailViewContainer">
                <div class="row-fluid detailViewTitle">
                    <div class="span10">
                        <div class="row-fluid">
                            <div class="span14">
                                <div class="row-fluid">
                                    <span class="span14"><img src="<?= base_url() ?>public/vdfiles/summary_Leads.png" class="summaryImg">
                                    </span>
                                    <span class="span14 margin0px">
                                        <span class="row-fluid">
                                         
                                        </span>
                                        <span class="row-fluid">
                                            <span class="designation_label"></span>
                                            <span class="company_label"></span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                          
                        </div>
                    </div>
                    <div class="span2 detailViewPagingButton">
                        <span class="btn-group pull-right">
                            <button class="btn" id="detailViewPreviousRecordButton" disabled="disabled">
                                <i class="icon-chevron-left"></i>
                            </button>
                            <button class="btn" id="detailViewNextRecordButton" onclick="window.location.href = '#'"><i class="icon-chevron-right"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="detailViewInfo row-fluid">
                    <div class=" span11  details">
                        <form class="navbar-form" id="customer_info" name="customer_info" method="post" action="savecustomerinfo" >
                <div class="form-horizontal">
                    <h3>Enter your Customer Information</h3>
                </div>
                <div class="form-horizontal col-sm-6">
                    <div>
                        <label class="col-sm-4 control-label" for="supplier_name">Supplier Name</label>
                        <div class="col-sm-8">
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

            </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="userfeedback" class="feedback noprint"><a href="javascript:;" onclick="javascript:window.open('<?= base_url() ?>product/feedback', 'feedbackwin', 'height=400,width=550,top=200,left=300')" class="handle">Feedback</a>
</div>
<footer class="noprint">
    <p align="center" style="margin-top:5px;margin-bottom:0;"><a target="_blank" href="http://www.pure-chemical.com">Powered by Pure Chemicals</a></p>
</footer>



<!-- Added in the end since it should be after less file loaded -->
<script type="text/javascript" src="<?= base_url() ?>public/vdfiles/less.js"></script>





</body></html>
