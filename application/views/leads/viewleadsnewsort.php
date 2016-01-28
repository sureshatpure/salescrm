<?php $this->load->view('header_novalid'); ?>
<!-- jqwidgets scripts -->
<style>
.controls {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 9999;
}
</style>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script> 

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/gettheme.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/index_search.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script> 

<!-- sorting and filtering - start -->


<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>

<!-- End of jqwidgets -->
<script type="text/javascript">
     base_url = "<?= base_url() ?>";
     $(document).ready(function ()
    {

        var theme = "";
        var selectedIndex_val = <?php echo $selectedIndex_val; ?>;
        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');
        $("#btn_search").jqxMenu({width: '100', height: '30px', theme: 'energyblue'}); 
        $("#jqxButtonadd").jqxLinkButton({width: '100', height: '30px', theme: 'energyblue'}); 

        var baseurl = base_url;
        var permission = <?php echo $grpperm; ?>;
        var group_len = permission.length;
        var hdnfrom_date = '2013-10-23';
        var hdnto_date = $('#hdn_to_date').val();
        var br = "SelectBranch";
        var sel_user_id = "SelectUser";
        var assign_user_id = "SelectUser";
        var sel_datefilter;
        var theme = 'energyblue';

       $("#excelExport").click(function () {
            $("#jqxgrid").jqxGrid('exportdata', 'xls', 'viewleaddata');
            //dashboard/savefile');
        });

     
        /* start for searching*/

         var url = base_url + "dashboard/getbranches";
            // prepare the data
            sourceforbranch =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'branch'},
                            {name: 'branch'}
                        ],
                        url: url,
                        async: false
                    };

            
         var branch_dataAdapter = new $.jqx.dataAdapter(sourceforbranch);
            // Create a jqxDropDownList
            $("#selectbranch").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: branch_dataAdapter,
                displayMember: "branch",
                valueMember: "branch",

                height: 25,
                theme: theme,
                placeHolder: '– Select Branch –'
            });


            $("#selectbranch").jqxDropDownList('val', br);

            //alert("selected branch "+br);
            if (br == 'SelectBranch')
            {
                var url = base_url + "dashboard/getusersforloginuser";
            }
            else
            {
                var url = base_url + "dashboard/getassignedtobranch/" + br;
            }


            // prepare the data
            branchsource =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: url,
                        async: false
                    };

            var branchsourcedataAdapter = new $.jqx.dataAdapter(branchsource);
            // Create a jqxDropDownList
            $("#selectuser").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: branchsourcedataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Created By –'
            });
            $("#selectuser").jqxDropDownList('val', sel_user_id);
            $("#assigntouser").jqxDropDownList('val', assign_user_id);
         // end of funtion setInitialgridsource

           $("#selectbranch").on('select', function (event) {
            updateFilterBox(event.args.item.value);
        });

        var updateFilterBox = function (datafield) {
            //   alert('testing'+datafield);

            var url = base_url + "dashboard/getassignedtobranch/" + datafield;

            // prepare the data
            source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: url,
                        async: false
                    };

            var dataAdapter = new $.jqx.dataAdapter(source);
            // Create a jqxDropDownList
            $("#selectuser").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Created By –'
            });
             $("#assigntouser").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Assigned To –'
            });
         }   // end of updateFilterBox       

       /*
            Start for status and substatus

       */
       var url = base_url + "leads/getstatus";
            // prepare the data
            status_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'leadstatus'},
                            {name: 'leadstatusid'}
                        ],
                        url: url,
                        async: false
                    };

            var status_dataAdapter = new $.jqx.dataAdapter(status_source);
            // Create a jqxDropDownList
            $("#status").jqxDropDownList({
                selectedIndex: -1,
                source: status_dataAdapter,
                displayMember: "leadstatus",
                valueMember: "leadstatusid",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select Status –'
            });
             var url = base_url + "leads/getinitial_lead_sub";
            // prepare the data
            substatus_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'lst_sub_id'},
                            {name: 'lst_name'}
                        ],
                        url: url,
                        async: false
                    };

            var substatus_sourcedataAdapter = new $.jqx.dataAdapter(substatus_source);


             $("#substatus").jqxDropDownList({
                selectedIndex: -1,
                source: substatus_sourcedataAdapter,
                displayMember: "lst_name",
                valueMember: "lst_sub_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select SubStatus –'
            });



           $("#status").on('select', function (event) {
                 updateSubstatus(event.args.item.value);
                 });
            

                var updateSubstatus = function (datafield) 
                {
                 //   alert('SubStatus '+datafield);
                    var url = base_url + "leads/getleadsubstatus_srch/" + datafield;
                    source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'lst_sub_id'},
                            {name: 'lst_name'}
                        ],
                        url: url,
                        async: false
                    };

            var dataAdapter = new $.jqx.dataAdapter(source);
            // Create a jqxDropDownList
            $("#substatus").jqxDropDownList({
               selectedIndex: -1,
                source: dataAdapter,
                displayMember: "lst_name",
                valueMember: "lst_sub_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select Status –'
            });
          }  
           /*
            End of Status and Substatus
       */   
        
           
            // prepare the data
           

            var dataAdapter = new $.jqx.dataAdapter(branchsource);
            // Create a jqxDropDownList
            $("#selectuser").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Created By –'
            });
            // end of updateSubstatus   

            var dataAdapter = new $.jqx.dataAdapter(branchsource);
            // Create a jqxDropDownList
            $("#assigntouser").jqxDropDownList({
                selectedIndex: selectedIndex_val,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Assigned To –'
            });
            // end of updateSubstatus   


      
         
            var url = base_url + "leads/getcustomers_all";
            // prepare the data
            customer_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'tempcustname'},
                            {name: 'customer_id'}
                        ],
                        url: url,
                        async: false
                    };

            var customer_sourcedataAdapter = new $.jqx.dataAdapter(customer_source);

        $("#customer").jqxDropDownList({
                selectedIndex: -1,
                source: customer_sourcedataAdapter,
                displayMember: "tempcustname",
                valueMember: "customer_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Select Customer –'
            });

         var url = base_url + "leads/selectproducts_all";
            // prepare the data
            product_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'description'},
                            {name: 'id'}
                        ],
                        url: url,
                        async: false
                    };

            var product_sourcedataAdapter = new $.jqx.dataAdapter(product_source);
        $("#product").jqxDropDownList({
                selectedIndex: -1,
                source: product_sourcedataAdapter,
                displayMember: "description",
                valueMember: "id",
                width:198,
                height: 25,
                theme: theme,
                placeHolder: '– Select Product –'
            });

        //$('#date_filter').jqxCheckBox({checked: false, height: 25, theme: theme});
        $("#fromdate").jqxDateTimeInput({width: '150px', height: '25px', theme: theme, formatString: 'dd-MMM-yyyy', disabled: false});
        $("#todate").jqxDateTimeInput({width: '150px', height: '25px', theme: theme, formatString: 'dd-MMM-yyyy', disabled: false});
        $("#fromdate").jqxDateTimeInput('setDate', hdnfrom_date);
        //$("#todate").jqxDateTimeInput('setDate', hdnto_date);

        /* End for searching */


    });
</script>


<?php //$this->load->view('topmenus');?>

<input value="Leads" id="module" name="module" type="hidden">
<input value="" id="parent" name="parent" type="hidden">
<input value="List" id="view" name="view" type="hidden">
<input value="<?php echo @$from_date; ?>" id="hdn_from_date" name="hdn_from_date" type="hidden">
<input value="<?php echo @$to_date; ?>" id="hdn_to_date" name="hdn_to_date" type="hidden">

<div class="navbar commonActionsContainer noprint">
    <div style="position: relative; top: 5px; left: 5.14999px;" class="actionsContainer row-fluid">
        <div class="span2">
            <span class="companyLogo"><img src="<?= base_url() ?>public/images/logo.png" title="logo.png" alt="logo.png">&nbsp;
            </span>
        </div>
        <div class="span10 marginLeftZero">

        </div>
    </div>
</div>
</div>
</div>
<div class="bodyContents" style="margin-left: 0;min-height: 635px;min-width: 1231px;">
    <div class="mainContainer row-fluid">
        <div class="span2 row-fluid noprint">
            <div class="row-fluid">
                <div class="sideBarContents">
                    <div class="quickLinksDiv">
                        <p  id="Leads_sideBar_link_LBL_RECORDS_LIST" class="selectedQuickLink">
                            <a class="quickLinks" href="<?= base_url() ?>leads"><strong>Lead Search</strong>
                            </a>
                        </p>
                        <!--converted leads  -->                        
                        <p  id="Leads_sideBar_link_LBL_RECORDS_LIST1" class="selectedQuickLink">
                            <a class="quickLinks" href="<?= base_url() ?>leads/convertedleads"><strong>Converted Leads</strong>
                            </a>
                        </p>
                        <!-- converted leads  -->                                               
                        <p  id="Leads_sideBar_link_LBL_DASHBOARD" class="unSelectedQuickLink">
                        <a class="quickLinks" href="<?= base_url() ?>dashboard"><strong>Dashboard</strong></a></p>
                    </div>
                    <div class="clearfix"></div>
                    <div class="quickWidgetContainer accordion">
                        <div class="quickWidget">
                            <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Leads_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="#">
                                <span class="pull-left">
                                    <img class="imageElement" alt="image" data-rightimage="<?= base_url() ?>public/images/images/rightArrowWhite.png" data-downimage="<?= base_url() ?>public/images/images/downArrowWhite.png" src="<?= base_url() ?>public/images/rightArrowWhite.png">
                                </span>
                                <a class="quickLinks" href="<?= base_url() ?>dashboard/executivepipeline"><h5 class="title widgetTextOverflowEllipsis pull-right" title="Branch/User Wise Lead Ageing">Branch/User Wise Lead Ageing</h5></a>

                                <div class="clearfix"></div>
                            </div>
                            <!-- second menu -->
                            <!--<div class="quickWidget">-->
                            <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Leads_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="#">
                                <span class="pull-left">
                                    <img class="imageElement"  alt="image" data-rightimage="<?= base_url() ?>public/images/images/rightArrowWhite.png" data-downimage="<?= base_url() ?>public/images/images/downArrowWhite.png" src="<?= base_url() ?>public/images/rightArrowWhite.png">
                                </span>
                                <a class="quickLinks" href="<?= base_url() ?>dashboard/additional"><h5 class="title widgetTextOverflowEllipsis pull-right" title="Branch/Status Wise Lead Count">Branch/Status Wise Lead Count</h5></a>
                                <div class="clearfix"></div>
                            </div>
                            <!-- second menu end-->
                            <!-- Third menu -->

                            <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Leads_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="#">
                                <span class="pull-left">
                                    <img class="imageElement"  alt="image"  data-rightimage="<?= base_url() ?>public/images/images/rightArrowWhite.png" data-downimage="<?= base_url() ?>public/images/images/downArrowWhite.png" src="<?= base_url() ?>public/images/rightArrowWhite.png">
                                </span>
                                <a class="quickLinks" href="<?= base_url() ?>dashboard/daynoprogress"><h5 class="title widgetTextOverflowEllipsis pull-right" title="Additional">Day No Progress</h5></a>
                                <div class="clearfix"></div>
                            </div>
                            <!-- third menu end-->
                            <!-- Fourth menu -->

                            <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#Leads_sideBar_LBL_RECENTLY_MODIFIED" data-toggle="collapse" data-parent="#quickWidgets" data-label="LBL_RECENTLY_MODIFIED" data-widget-url="#">
                                <span class="pull-left">
                                    <img class="imageElement"  alt="image" data-rightimage="<?= base_url() ?>public/images/images/rightArrowWhite.png" data-downimage="<?= base_url() ?>public/images/images/downArrowWhite.png" src="<?= base_url() ?>public/images/rightArrowWhite.png">
                                </span>
                                <a class="quickLinks" href="<?= base_url() ?>dashboard/generadtedleads"><h5 class="title widgetTextOverflowEllipsis pull-right" title="User /Branch Wise Generated Leads">User /Branch Wise Generated Leads</h5></a>
                                <div class="clearfix"></div>                        
                            </div>
                            <!-- Fourth menu end-->
                            <div class="widgetContainer accordion-body collapse" id="Leads_sideBar_LBL_RECENTLY_MODIFIED" data-url="module=Leads&amp;view=IndexAjax&amp;mode=showActiveRecords">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contentsDiv span10 marginLeftZero">
            <div class="listViewPageDiv">
                <div class="listViewTopMenuDiv noprint">
                    
                        <span class="btn-group">
                                    <!-- <button id="Leads_listView_basicAction_LBL_ADD_RECORD" class="btn addButton" onclick='window.location.href="leads/add"'><i class="icon-plus icon-white"></i>&nbsp;<strong>Add Lead</strong></button> -->
                                <?php if ($this->session->flashdata('message') != "") { ?>
                                    <div class="alert alert-message.success"><p style="width:600px; height:10px; text-align:center;font-size:18px;"><?php echo $this->session->flashdata('message'); ?></p></div>
                                <?php } ?>


                            </span>
                       

                    
                </div>
                <form action="<?= base_url() ?>leads/index_search" method="post" name="leadsrch_form" id="leadsrch_form" class="form-horizontal recordEditView">
                               <table border="1" cellpadding="5" cellspacing="5" style="margin-left:150px;border-width:7px; border-color:#cfdde9;">
                                        <tr>
                                            <td><div style="float: left" id="selectbranch"></div></td>
                                            <td><div style="float: inherit" id="selectuser"></div></td>
                                            <td><div style="float: inherit" id="assigntouser"></div></td>
                                          
                                        </tr>
                                         <tr>
                                             <td><div style="float: left" id="status"></div></td>
                                            <td><div style="float: inherit" id="substatus"></div></td>
                                            <td><div style="float: left" id="customer"></div></td>
                                             
                                          
                                        </tr>
                                                                               
                                         <tr>
                                            <td>
                                                <div style="float:left" id="product"></div>
                                            </td>
                                     
                                             <td colspan="2" align="center" style="line-height:30px;font-weight:bold;">
                                             <div id='date_filter'>Created Date</div>
                                             <label style="float:left;  padding:0px 5px;" >From </label>
                                                 <div style="float:left;"  id="fromdate" ></div>
                                                <label style="float:left;  padding:0px 5px;" >To </label>
                                                <div style="float:left;" id="todate" ></div>
                                            </td>

                                            
                                        </tr>
                                       
                                         <tr>
                                            <td>
                                                 <a style='margin-left: 25px;' href="<?= base_url() ?>leads/add" id='jqxButtonadd'>Add New lead</a>            
                                            </td>
                                            <td>
                                               
                                            </td>
                                            <td><input class="submit" id="btn_search" name="btn_search" type="button" value="Search" />

                                            </td>
                                            
                                        </tr>
                                    </table>
                                    
                            </form>
    


            </div>
        </div>
    </div>
</div>
</div>
<input id="activityReminder" class="hide noprint" value="60" type="hidden">
<div id="userfeedback" class="feedback noprint">
   
</div>
<footer class="noprint">
    <p style="margin-top:5px;margin-bottom:0;" align="center">&nbsp;|&nbsp;
        <a target="_blank" href="http://www.pure-chemical.com">Powered by Pure CRM 6.0.0 Beta©2013 - 2018</a>&nbsp;|&nbsp;
    </p>
</footer>


<script type="text/javascript" src="<?= base_url() ?>public/js/html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-alert.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-tooltip.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-tab.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-collapse.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootbox.js"></script>





<!-- Added in the end since it should be after less file loaded -->
<?php $this->load->view('include_idletimeout.php'); ?>

</body>
</html>



