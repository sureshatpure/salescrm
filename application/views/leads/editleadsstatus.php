<?php $this->load->view('header'); ?>
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
<style>
    .error{
        color: red;
    }
    #leadform label {
    cursor: pointer;
    font-size: 10px;
    font-weight: bold;
    line-height: 30px;
    text-align: left;
    width:100%;
  }
  .outer_table{
    width:100%;
    padding:5px;
    border: 1px solid #ddd;
  }
  .outer_table tr td {
      padding:5px;
  }
  .reset {
    border: 1px solid #dedede;
    color: #484848;
    cursor: pointer;
    font-family: arial,verdana,sans-serif;
    font-size: 14px;
    font-weight: bold;
    margin-left: 10px;
    padding: 8px 10px;
  }
</style>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/gettheme.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>


<!-- sorting and filtering - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>
<!-- sorting and filtering and export excel - end -->
<!-- paging - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.export.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.export.js"></script>
<!-- paging - end -->

<script src="<?= base_url() ?>public/js/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>public/js/additional-methods.js"></script>
<script src="<?= base_url() ?>public/js/validation_rules.js"></script> 
<!-- paging - end -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
<!-- end of Menu includes -->

<script type="text/javascript">

    function openpopup(id)
    {
        // alert("company id passed is "+id);
        $('#jqxsoc').jqxWindow('open');
        $("#jqxsoc").jqxWindow({width: 600, height: 220, isModal: true, title: "Select the SOC"});

    }
    var base_url = '<?php echo site_url(); ?>index.php';
    var lead_sub_onstatus_change;
    var global_leadstatus;
    $(document).ready(function ()
    {
        /* Start for appointment date */
        var app_date = '<?php echo $appiontment_date; ?>';

        var cust_account_id = '<?php echo $cust_account_id; ?>';
        var contryname = '<?php echo $country; ?>';
        var statename = '<?php echo $state; ?>';
        var cityname = '<?php echo $city; ?>';
        var address = '<?php echo $address; ?>';
        var postalcode = '<?php echo $postalcode; ?>';
        var mobile_no = '<?php echo $mobile_no; ?>';
        var fax = '<?php echo $fax; ?>';
        var contact_person = '<?php echo $contact_person; ?>';
        var contact_number = '<?php echo $contact_number; ?>';
        var contact_mailid = '<?php echo $contact_mailid; ?>';

        var leadsubstatus_onload = $('#leadsubstatus').val();  // here we are taking option id of the selected one.
        var leadstatus_onload = $('#leadstatus').val();

         /* start for read only */
         if(cust_account_id == null || cust_account_id == 0)
                        {
                           // alert("this is a temp customer");
                            $("#trReadOnlyFields > input").attr("readonly", false);
                            $('#street').prop('readonly', false);

                        }
                        else
                        {
                            //alert(" cust_account_id is "+ cust_account_id);
                            if (contact_person == null || contact_person == "")
                            {
                               $("#trReadOnlyFields_name > input").attr("readonly", false); 
                            }
                            else
                            {
                               $("#trReadOnlyFields_name > input").attr("readonly", true);   
                            }
                            if (contact_mailid == null || contact_mailid == "")
                            {
                               $("#trReadOnlyFields_email > input").attr("readonly", false); 
                            }
                            else
                            {
                                 $("#trReadOnlyFields_email > input").attr("readonly", true); 
                            }
                            if (contact_number == null || contact_number =="" )
                            {
                               
                               $("#trReadOnlyFields_phone > input").attr("readonly", false); 
                            }
                            else
                            {
                                  $("#trReadOnlyFields_phone > input").attr("readonly", true); 
                            }

                            if (address == null || address == "")
                            {
                              
                               $("#trReadOnlyFields_street > input").attr("readonly", false); 
                                $('#street').prop('readonly', false);
                            }
                            else
                            {
                                  $("#trReadOnlyFields_street > input").attr("readonly", true); 
                                   $('#street').prop('readonly', true);
                            }
                            if (statename == null || statename == "")
                            {
                               
                               $("#trReadOnlyFields_state > input").attr("readonly", false); 
                            }
                            else
                            {
                              
                                  $("#trReadOnlyFields_state > input").attr("readonly", true); 
                            }
                            if (cityname == null || cityname == "")
                            {
                              
                               $("#trReadOnlyFields_city > input").attr("readonly", false); 
                            }
                            else
                            {
                              
                                  $("#trReadOnlyFields_city > input").attr("readonly", true); 
                            }
                            if (contryname == null || contryname == "")
                            {
                              
                               $("#trReadOnlyFields_country > input").attr("readonly", false); 
                            }
                            else
                            {
                              
                                  $("#trReadOnlyFields_country > input").attr("readonly", true); 
                            }
                             if (postalcode == null || postalcode == "")
                            {
                               
                               $("#trReadOnlyFields_po > input").attr("readonly", false); 
                            }
                            else
                            {
                              
                                  $("#trReadOnlyFields_po > input").attr("readonly", true); 
                            }

                        }

        /*start*/
         var characters = 250;
        $("#counter").append("You have <strong>"+  characters+"</strong> characters remaining");
        $("#street").keyup(function()
        {
            if($(this).val().length > characters)
            {
                $(this).val($(this).val().substr(0, characters));
            }
            var remaining = characters -  $(this).val().length;
            $("#counter").html("You have <strong>"+  remaining+"</strong> characters remaining");
            if(remaining <= 10)
            {
                $("#counter").css("color","red");
            }
            else
            {
                $("#counter").css("color","black");
            }
        });
        /*end*/

        if ($('#leadstatus').val() >= 2)
        {
            $('#credit_Assessment').css("visibility", "visible");
        }
        else
        {
            $('#credit_Assessment').css("visibility", "hidden");

        }


        if (leadsubstatus_onload == "4")
        {
            $('#content_appiontment_date').show();
            $('#appiontment_fixed_date').show();
        }

        $("#content_appiontment_date").jqxDateTimeInput({width: '170px', height: '25px'});
        $('#content_appiontment_date').val(app_date);
        /* End for appointment date */

        /* Start for reason not able to get appiontment */
        var no_reason = '<?php echo $no_reason; ?>';
        var lead_2pa_no = '<?php echo $lead_2pa_no; ?>';

        var leadsubstatus_onload = $('#leadsubstatus').val();  // here we are taking option id of the selected one.
        if (leadsubstatus_onload == "6")
        {
            $('#content_no_appointment').show();
            $('#not_able_to_get_appointment').show();
        }
        if (leadsubstatus_onload == "8")
        {
            $('#content_no_2pano').show();
            $('#lead_2pa_no').show();

        }


        $('#not_able_to_get_appointment').val(no_reason);
        $('#lead_2pa_no').val(lead_2pa_no);
        /* End for reason not able to get appiontment */

        var theme = "";
        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');


        var leadata = <?php echo $data; ?>;

        var source =
                {
                    localdata: leadata,
                    datatype: "array",
                    datafields:
                            [
                                {name: 'crm_soc_no'},
                                {name: 'customer_id'},
                                {name: 'itemdesc'},
                                {name: 'customer_name', type: 'string'},
                                {name: 'lead_cusomer_ref_id'},
                                {name: 'customer_number'},
                            ],
                    pagenum: 0, pagesize: 35, pager: function (pagenum, pagesize, oldpagenum) {
                        // callback called when a page or page size is changed.
                    }
                };

        var dataAdapter = new $.jqx.dataAdapter(source);
        $("#jqxcustomergrid").jqxGrid(
                {
                    width: 700,
                    height: 500,
                    source: dataAdapter,
                    selectionmode: 'singlerow',
                    theme: 'energyblue',
                    sortable: true,
                    pageable: true,
                    columnsresize: true,
                    editable: false,
                    showfilterrow: true,
                    filterable: true,
                    autoheight: true,
                    showtoolbar: true,
                    pageable: true,
                            columns: [
                                {text: 'SocID', datafield: 'crm_soc_no', width: 100},
                                {text: 'Customer Id', datafield: 'customer_id', cellsalign: 'left', width: 100},
                                {text: 'Customer Name', datafield: 'customer_name', cellsalign: 'left', width: 200},
                                {text: 'Product Name', datafield: 'itemdesc', cellsalign: 'left', width: 200},
                                {text: 'Customer Number', datafield: 'customer_number', cellsalign: 'left', width: 100}
                            ]
                });
        $("#jqxcustomergrid").on('celldoubleclick', function (event)
        {
            var column = event.args.column;
            var rowindex = event.args.rowindex;
            var jqxcustomergrid_row_index = rowindex;
            var columnindex = event.args.columnindex;
            var columnname = column.datafield;
            var custgroup_val = $('#jqxcustomergrid').jqxGrid('getcellvalue', rowindex, "crm_soc_no");


            $('#txtLeadsoc').val(custgroup_val);
            $('#jqxsoc').jqxWindow('hide');

        });


        $("#jqxcustomergrid").on("cellselect", function (event)
        {
            var column = event.args.column;
            var rowindex = event.args.rowindex;
            var jqxcustomergrid_row_index = rowindex;
            var columnindex = event.args.columnindex;
            var columnname = column.datafield;
            var custgroup_val = $('#jqxcustomergrid').jqxGrid('getcellvalue', rowindex, "crm_soc_no");
        });


        $('#leadstatus').change(function () { //any select change on the dropdown with id options trigger this code

            $("#leadsubstatus > option").remove(); //first of all clear select items
            var option = $('#leadstatus').val();  // here we are taking option id of the selected one.

            lead_sub_onstatus_change = $('#leadsubstatus').val();
            //if (option == "6" || option == "7")
            if (option == "7")
            {
                $('#content').show();
                $('#txtLeadsoc').show();
                //popup code
                openpopup(this.id);
            }
            else
            {
                $('#txtLeadsoc').hide();
                $('#content').hide();
            }
            if (option == "2")
            {
                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_no_2pano').hide();
                $('#lead_2pa_no').hide();

                $('#content_closingcomments').hide();
                $('#closingcomments').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();



            }
            if (option == "3")
            {
                 $('#content_no_2pano').show();
                $('#lead_2pa_no').show();

                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#not_able_to_get_appointment').hide();
                $('#content_closingcomments').hide();
                $('#closingcomments').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();

            }
            if ((option == "1") || (option == "4") || (option == "5") || (option == "6") || (option == "7"))
            {
                $('#content_no_2pano').hide();
                $('#lead_2pa_no').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_closingcomments').hide();
                $('#closingcomments').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();

            }

             if (option == "8")
            {
                 $('#content_closingcomments').show();
                 $('#closingcomments').show();
                

                $('#content_no_2pano').hide();
                $('#lead_2pa_no').hide();

                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();

            }



            if (option == '#') {
                return false; // return false after clearing sub options if 'please select was chosen'
            }
            if (option >= 2)
            {
                $('#credit_Assessment').css("visibility", "visible");
            }
            else
            {
                $('#credit_Assessment').css("visibility", "hidden");

            }

            $.ajax({
                type: "POST",
                url: base_url+"leads/getleadsubstatus/" + option, //here we are calling our dropdown controller and getsuboptions method passing the option

                success: function (suboptions) //we're calling the response json array 'suboptions'
                {
                    $.each(suboptions, function (id, value) //here we're doing a foeach loop round each sub option with id as the key and value as the value
                    {
                        var opt = $('<option />'); // here we're creating a new select option for each suboption
                        opt.val(id);
                        opt.text(value);
                        $('#leadsubstatus').append(opt); //here we will append these new select options to a dropdown with the id 'suboptions'
                    });
                    lead_sub_onstatus_change = $("#leadsubstatus option:selected").val();


                    if (lead_sub_onstatus_change[0] == '8')
                    {
                        $('#content_no_2pano').show();
                        $('#lead_2pa_no').show();

                        $('#content_appiontment_date').hide();
                        $('#appiontment_fixed_date').hide();

                        $('#content_no_appointment').hide();
                        $('#not_able_to_get_appointment').hide();

                    }
                    else if (lead_sub_onstatus_change[0] == '4')
                    {
                        $('#content_no_2pano').hide();
                        $('#lead_2pa_no').hide();

                        $('#content_appiontment_date').show();
                        $('#appiontment_fixed_date').show();

                        $('#content_no_appointment').hide();
                        $('#not_able_to_get_appointment').hide();
                    }
                }


            });

        });




        /*Start lead substatus validations */
        $('#leadsubstatus').change(function ()
        {


            var leadsubstatus = $('#leadsubstatus').val();  // here we are taking option id of the selected one.
            leadsubstatus = leadsubstatus.split('-');
            
            if (leadsubstatus[0] == "4")
            {
               $('#content_appiontment_date').show();
                $('#appiontment_fixed_date').show();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();
                
                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();

            }
              if (leadsubstatus[0] == "5" || leadsubstatus[0] == "3" || leadsubstatus[0] == "2" || leadsubstatus[0] == "1" || leadsubstatus[0] == "16" || leadsubstatus[0] == "17" || leadsubstatus[0] == "18" || leadsubstatus[0] == "19" || leadsubstatus[0] == "20" || leadsubstatus[0] == "21" || leadsubstatus[0] == "22" || leadsubstatus[0] == "23" || leadsubstatus[0] == "24" || leadsubstatus[0] == "25" || leadsubstatus[0] == "26")
            {
                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_no_appointment').hide();
                $('#not_able_to_get_appointment').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();

            }

            if (leadsubstatus[0] == "6")
            {
                $('#content_no_appointment').show();
                $('#not_able_to_get_appointment').show();

                $('#content_appiontment_date').hide();
                $('#appiontment_fixed_date').hide();

                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();
                //popup code

            }
            
            if (leadsubstatus[0] == "8")
            {
                $('#content_no_2pano').show();
                $('#lead_2pa_no').show();
                
                $('#content_sample_rejected').hide();
                $('#sample_rejected_reason').hide();

                $('#content_order_cancelled').hide();
                $('#order_cancelled_reason').hide();
                //popup code

            }
            else
            {
                $('#content_no_2pano').hide();
                $('#lead_2pa_no').hide(); 
            }
            
            if (leadsubstatus[0] == '21')
            {
                $('#content_sample_rejected').show();
                $('#sample_rejected_reason').show();
            }

             if (leadsubstatus[0] == '27')
            {
                $('#content_order_cancelled').show();
                $('#order_cancelled_reason').show();
            }                   
            
        });
        /*Start lead substatus validations */
    });

   

</script>
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
    </div>
</div>
<div style="min-height: 400px;" class="bodyContents">

    <div class="mainContainer row-fluid">
        <div class="span2 row-fluid">
            <div class="row-fluid">
                <div class="sideBarContents">
                    <div class="quickLinksDiv">
                        <p class="selectedQuickLink" onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_RECORDS_LIST"><a class="quickLinks" href="<?= base_url() ?>leads"><strong>Leads List</strong></a>
                        </p>
                        <p class="selectedQuickLink" onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_DASHBOARD" ><a class="quickLinks" href="<?= base_url() ?>dashboard"><strong>Dashboard</strong></a></p>
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
        <form action="<?= base_url() ?>leads/updateleadstatus/<?php echo $leaddetails['0']['leadid']; ?>" method="post" name="leadform" id="leadform" class="form-horizontal recordEditView">
            <input id="recordId" value="21" type="hidden">
            <div class="detailViewContainer">
                <div class="row-fluid detailViewTitle">
                    <div class="span10">
                        <div class="row-fluid">
                            <span class="pull-right">
                              <input class="submit" id="updateleadstatus" name="updateleadstatus" type="submit" value="updateleadstatus" />
                              <a onclick="javascript:window.history.back();"><input class="reset" type="reset" class="cancelLink" value="Cancel"> </a>
                            </span>
                            <div class="span14">
                                <div class="row-fluid">
                                    <span class="span14"><img src="<?= base_url() ?>public/vdfiles/summary_Leads.png" class="summaryImg">
                                    </span>
                                    <span class="span14 margin0px">
                                        <span class="row-fluid">
                                            <span class="recordLabel font-x-x-large textOverflowEllipsis pushDown span" title="">
                                                <span class="salutation"></span>&nbsp;
                                                <span class="firstname"><?php echo $leaddetails['0']['tempcustname']; ?></span>&nbsp;
                                                
                                            </span>
                                        </span>

                                    </span>
                                </div>
                            </div>
                         
                        </div>
                    </div>

                </div>
                <div class="detailViewInfosts row-fluid">
                     <div class="span11  details">
                        <div class="contents">
                        <table class="table table-bordered equalSplit detailview-table">
                    <tbody>
                        <tr>
                            <th colspan="6" class="blockHeader">Lead Status Update</th>
                        </tr>
                        <tr>
                            <td class="fieldLabel narrowWidthType">
                                <label class="muted pull-right marginRight10px">Lead Status</label>
                            </td>
                            <td class="fieldValue narrowWidthType" >
                                <div class="row-fluid">
                                    <span class="span10">
                                        <?php
                                        echo form_dropdown('leadstatus', $optionslst, set_value('leadstatus', (isset($leaddetails['0']['leadstatusid'])) ? $leaddetails['0']['leadstatusid'] : ''), 'id="leadstatus"');
                                        echo form_error('leadstatus');
                                        ?>         
                                    </span>
                                </div>
                                <!-- Start -->
                                <div id='content' style="display: none">
                                    <div><input type="text" name="txtLeadsoc" id="txtLeadsoc" style="display: none" readonly="true" placeholder="Get SOC Number"></div>
                                    <div id='jqxsoc'>
                                        <div>
                                            <div id="jqxcustomergrid"></div>
                                        </div>
                                        <input type="hidden" id="hdnselid" value="<?= $this->uri->segment(3); ?>">
                                    </div>
                                </div>
                                <!-- End -->

                            </td>
                            <td class="fieldLabel narrowWidthType">
                                <label class="muted pull-right marginRight10px">Sub Status</label>
                            </td>
                            <td class="fieldValue narrowWidthType">
                                <div class="row-fluid">
                                    <span class="span10">
                                        <?php
                                        echo form_dropdown('leadsubstatus', $optionslsubst, set_value('leadsubstatus', (isset($leaddetails['0']['ldsubstatus'])) ? $leaddetails['0']['ldsubstatus'] : ''), 'id="leadsubstatus"', 'name="leadsubstatus"');
                                        echo form_error('leadsubstatus');
                                        ?> 
                                    </span>
                                </div>
                                <!-- Start -->
                                <div id='content_appiontment_date' style="display: none;">
                                    <div>
                                        <font color="blue">Enter the Appiontment Date</font>
                                        <input type="text" name="content_appiontment_date" id="content_appiontment_date"  value="" onMouseOver="javascript: this.value = 'Select Appiontment Date';">

                                    </div>

                                </div>
                                <!-- End -->
                                <!-- Start -->
                                <div id='content_no_appointment' style="display: none">
                                    <div>
                                        <font color="blue">Reason for Not able to get appointment</font>
                                        <input type="text" name="not_able_to_get_appointment" id="not_able_to_get_appointment" style="display: none"  placeholder="Reason for not able to get appiontment">


                                    </div>

                                </div>
                                <!-- End -->
                                <!-- Start -->
                                    <div id='content_sample_rejected' style="display: none">
                                        <div>
                                            <font color="blue">Reason for sample rejection</font>
                                            <input type="text" name="sample_rejected_reason" id="sample_rejected_reason" style="display: none"  placeholder="Reason for sample rejection">
                                        </div>

                                    </div>
                                <!-- End -->
                                 <!-- Start -->
                                    <div id='content_order_cancelled' style="display: none">
                                        <div>
                                            <font color="blue">Reason for order cancelled</font>
                                            <input type="text" name="order_cancelled_reason" id="order_cancelled_reason" style="display: none"  placeholder="Reason for sample rejection">
                                        </div>

                                    </div>
                                    <!-- End -->
                                <!-- Start -->
                                <div id='content_no_2pano' style="display: none">
                                    <div>
                                        <font color="blue">Enter the copy of 2PA Number</font>
                                        <input type="text" name="lead_2pa_no" id="lead_2pa_no" style="display: none"  placeholder="copy of2PA ">
                                        <?php echo form_error('lead_2pa_no'); ?>    
                                    </div>

                                </div>
                                <!-- Start -->
                                    <div id='content_closingcomments' style="display: none">
                                        <div>
                                            <font color="blue">Enter the closing comments</font>
                                              <textarea name="closingcomments" id="closingcomments" style="display: none"  placeholder="enter closing comments "></textarea>
                                            <?php echo form_error('closingcomments'); ?>    
                                        </div>

                                    </div>
                                    <!-- End -->
                                <!-- End -->
                            </td>

                        </tr>
                        <tr>
                            <td class="fieldLabel narrowWidthType">
                                <label class="muted pull-right marginRight10px">Comments for  Lead Status</label>
                            </td>
                            <td  class="fieldValue narrowWidthType" colspan="3">
                                <div class="row-fluid">
                                    <span class="span10">
                                        <textarea name="comments" id="comments" style="width: 649px; height: 88px;"></textarea>
                                    </span>
                                </div>
                                <input type="hidden" id="hdn_cmnts" name="hdn_cmnts" value="<?php echo $leaddetails['0']['comments']; ?>">
                                <input type="hidden" id="hdn_status_id" name="hdn_status_id" value="<?php echo $leaddetails['0']['leadstatusid']; ?>">
                                <input type="hidden" id="hdn_sub_status_id" name="hdn_sub_status_id" value="<?php echo $leaddetails['0']['ldsubstatus']; ?>">
                                <input type="hidden" id="hdn_assignto_id" name="hdn_assignto_id" value="<?php echo $leaddetails[0]['assignleadchk'] ?>">
                                <input type="hidden" id="hdn_user_branch" name="hdn_user_branch" value="<?php echo $leaddetails[0]['user_branch'] ?>">
                                <input type="hidden" id="hdn_company" name="hdn_company" value="<?php echo $leaddetails[0]['company'] ?>">


                            </td>
                        </tr>

                    </tbody>
                </table>
<!-- //end of table 1 -->
                        <div id="credit_Assessment">
                        <table class="table table-bordered equalSplit detailview-table">
                        <tbody>
                           
                            <tr>
                                <th colspan="4" class="blockHeader">Address Details</th>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Email Address</label>
                                </td>
                                <td class="fieldValue narrowWidthType" >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_email">
                                           <input  type="text" name="email_id" id="email_id" value="<?php echo $contact_mailid; ?>" size="25" />
<?php echo form_error('email'); ?>                  
                                        </span>
                                    </div>
                                </td>
                                <td class="fieldLabel narrowWidthType">
                                            <label class="muted pull-right marginRight10px">Contact Person((Lead Contact))</label>
                                </td>
                                <td class="fieldValue narrowWidthType" >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_name">
                                            <input type="text" name="contact_name" id="contact_name" value="<?php echo $contact_person; ?>" size="25" /> 
<?php echo form_error('contact_person'); ?>                  
                                        </span>
                                    </div>
                                </td>


                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Country</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_country">
                                           <input type="text" placeholder="Country Name" id="country" name="country" value="<?php echo $country; ?>" /> 
                                            <?php echo form_error('country');?> 
                                        </span>
                                    </div>
                                </td>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">PO Box</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_po">
                                            <input type="text" name="postalcode" id="postalcode" value="<?php echo $postalcode; ?>" size="25" /> 
<?php echo form_error('postalcode'); ?>                 
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">State</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_state">
                                           <input type="text" placeholder="State Name" id="state" name="state" value="<?php echo $state; ?>" /> 
                                            <?php echo form_error('state'); ?>      
                                        </span>
                                    </div>
                                </td>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Contact Number</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_phone">
                                              <input  type="text" name="phone" id="phone" value="<?php echo $contact_number; ?>" size="25" />
<?php echo form_error('phone'); ?>                 
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">City</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_city">
                                            <input type="text" placeholder="City Name" id="city" name="city" value="<?php echo $city; ?>" />
                                             <?php  echo form_error('city'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Mobile</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_mobile">
                                            <input  type="text" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no; ?>" size="25" />
<?php echo form_error('mobile'); ?>                
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Street Address</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_street">
                                           <textarea rows="3" cols="40" data-maxsize="249" name="street" id="street"/><?php echo $address; ?></textarea>
<?php echo form_error('street'); ?>
<div  id="counter"></div>  
                                        </span>
                                    </div>
                                </td>
                                <td class="fieldLabel narrowWidthType">
                                    <label class="muted pull-right marginRight10px">Fax</label>
                                </td>
                                <td class="fieldValue narrowWidthType">
                                    <div class="row-fluid">
                                        <span class="span10">
                                             <input  type="text" name="fax" id="fax" value="<?php echo $fax; ?>" size="25" />
<?php echo form_error('phone'); ?>          
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

<!-- //end of table 2 -->

<table style="width=100%; height: 100%;">

                        <tr valign="top">
                            <td><label for="customFieldName">Product Name</label></td>
                            <td style="width=27%">
                                <?php
                                echo form_dropdown('customFieldName[]', $optionsproedit, set_value('customFieldName[]', (isset($leadproducts[0]['description'])) ? $leadproducts[0]['productid'] : ''), 'id="customFieldName"', 'name="customFieldName[]"', 'style="width:100px;"');
                                echo form_error('customFieldName');
                                ?> 

                            </td>
                            <td style="width: 91px;"><b>Quantity</b></td>
                            <td> <input type="text"  id="customFieldValue" name="customFieldValue[]" class="code1"  value="<?php echo $leadproducts[0]['quantity']; ?>" size="8" /> &nbsp;in MT / Month &nbsp;&nbsp;
                            </td>
                        </tr>
                        <?php
                        foreach ($leadproducts as $products) {
                            ?> 

                            <tr>    
                                <td style="width=20%"> <b>Type</b></td>
                                <td style="width=27%"> 
                                    <?php echo $products['n_value']; ?> 

                                    &nbsp;</td>
                                <td style="width=20%"> <b>Potential</b></td>
                                <td style="width=27%">   
                                    <input type="hidden" id="leadprodid" name="leadprodid[]" value="<?php echo $products['lpid']; ?>"/>
                                    <input type="hidden" id="prod_type_id" name="prod_type_id[]" value="<?php echo $products['prod_type_id']; ?>"/>
                                    <input type="text" class="code1" id="customFieldPoten" name="customFieldPoten[]" value="<?php echo $products['potential']; ?>" size="8" /> &nbsp;in MT / Month &nbsp;
                                </td>
                            </tr>

                            <?php
                        }
                        ?>


                    </table>

                    </div> <!-- end of credit_Assessment -->
                     
                    </div>

                     </div>
                     
                     <span class="pull-right" style="margin-right: 86px;">
                              <input class="submit" id="updateleadstatus" name="updateleadstatus" type="submit" value="updateleadstatus" />
                              <a onclick="javascript:window.history.back();"><input class="reset" type="reset" class="cancelLink" value="Cancel"> </a>
                            </span>
                    
                </div>  
                
                </div><!-- end of detailViewInfo row-fluid -->


            </div> <!-- end detailViewContainer -->

        </div>
        </form>
        </div>
    </div>
</div>
</div>
<input id="activityReminder" class="hide noprint" value="60" type="hidden">
<div id="userfeedback" class="feedback noprint"><a href="javascript:;" onclick="javascript:window.open('<?= base_url() ?>product/feedback', 'feedbackwin', 'height=400,width=550,top=200,left=300')" class="handle">Feedback</a>
</div>
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


</div></body></html>
