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

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>
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
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>

<!-- sorting and filtering - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
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
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />

<!-- End of jqwidgets -->
<!-- end of Menu includes -->
<script type="text/javascript">
    function openpopup(id)
    {
        // alert("company id passed is "+id);
        $('#jqxsoc').jqxWindow('open');
        $("#jqxsoc").jqxWindow({width: 600, height: 220, isModal: true});

    }


    var controller = 'leads';
    var base_url = '<?php echo site_url(); ?>index.php';
    var lead_sub_onstatus_change;

    $(document).ready(function ()
    {
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
        
        /* start for read only */
        if(cust_account_id == null || cust_account_id == 0)
        {
            
            $("#trReadOnlyFields > input").attr("readonly", false);
            $('#street').prop('readonly', false);

        }
        else
        {
            
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
        /* end of read only*/

        if (leadsubstatus_onload == "4")
        {
            $('#content_appiontment_date').show();
            $('#appiontment_fixed_date').show();

        }

        if (leadsubstatus_onload == "8")
        {
            $('#content_no_2pano').show();
            $('#lead_2pa_no').show();
        }


        $("#content_appiontment_date").jqxDateTimeInput({width: '170px', height: '25px'});
        $('#content_appiontment_date').val(app_date);

        /* Start for reason not able to get appiontment */
        var no_reason = '<?php echo $no_reason; ?>';
        var lead_2pa_no = '<?php echo $lead_2pa_no; ?>';

        if (leadsubstatus_onload == "6")
        {
            $('#content_no_appointment').show();
            $('#not_able_to_get_appointment').show();

        }

        $('#not_able_to_get_appointment').val(no_reason);
        $('#lead_2pa_no').val(lead_2pa_no);

        /* End for reason not able to get appiontment */

      
        var option = $('#presentsource').val();  // here we are taking option id of the selected one.

        if (option == "Domestic and Import" || option == "Domestic")
        {
            $('#contentSuplier').show();
            $('#txtDomesticSource').show();

        }
        else
        {
            $('#txtDomesticSource').hide();
            $('#contentSuplier').hide();
        }




        var leadata = <?php echo $data; ?>;

        var source =
                {
                    localdata: leadata,
                    datatype: "array",
                    datafields:
                            [
                                {name: 'crm_soc_no'},
                                {name: 'itemdesc'},
                                {name: 'customer_id'},
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
                    width: 560,
                    height: 250,
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
                                {text: 'Product', datafield: 'itemdesc', cellsalign: 'left', width: 100},
                                {text: 'Customer Name', datafield: 'customer_name', cellsalign: 'left', width: 200},
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

        var theme = "";
        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');

        $("#addCF").on("click", function ()
        {
            var $tableBody = $('#customFields').find("tbody"),
                    $trLast = $tableBody.find("tr:last"),
                    $trNew = $trLast.clone();
            $trLast.after($trNew);
            $trNew.find(':text').val('');

        });
        $('#remCF').on("click", function () {
            $('#customFields tr:last').remove();
        });


        $('#leadstatus').change(function () {

            $("#leadsubstatus > option").remove(); //first of all clear select items
            var option = $('#leadstatus').val();  // here we are taking option id of the selected one.

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
                url: base_url + "leads/getleadsubstatus/" + option,
                success: function (suboptions)
                {
                    $.each(suboptions, function (id, value)
                    {
                        var opt = $('<option />');
                        opt.val(id);
                        opt.text(value);
                        $('#leadsubstatus').append(opt);
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

                        $('#content_order_cancelled').hide();
                        $('#order_cancelled_reason').hide();

                    }
                    else if (lead_sub_onstatus_change[0] == '4')
                    {
                        $('#content_no_2pano').hide();
                        $('#lead_2pa_no').hide();

                        $('#content_appiontment_date').show();
                        $('#appiontment_fixed_date').show();

                        $('#content_no_appointment').hide();
                        $('#not_able_to_get_appointment').hide();

                        $('#content_order_cancelled').hide();
                        $('#order_cancelled_reason').hide();
                    }
                    


                }

            });
        });

        $('#branch').change(function ()
        {
            $("#assignedto > option").remove();
            var option = $('#branch').val();
            if (option == '#')
            {
                return false;
            }

            $.ajax({
                type: "POST",
                url: base_url + "leads/getassignedtobranch/" + option,
                success: function (suboptions)
                {
                    $.each(suboptions, function (header_user_id, displayname)
                    {

                        var opt = $('<option />');
                        opt.val(header_user_id);
                        opt.text(displayname);
                        $('#assignedto').append(opt);
                    });
                }
            });
        });

        /**/
        $('#presentsource').change(function () {

            var option = $('#presentsource').val();  // here we are taking option id of the selected one.
            if (option == "Domestic" || option == "Domestic and Import")
            {
                $('#contentSuplier').show();
                $('#txtDomesticSource').show();
                //popup code

            }
            else
            {
                $('#txtDomesticSource').hide();
                $('#contentSuplier').hide();
            }
        });
        /**/



        /*Start*/
        $('#leadsubstatus').change(function () {


            var leadsubstatus = $('#leadsubstatus').val();  // here we are taking option id of the selected one.
              
            leadsubstatus = leadsubstatus.split('-');
            //alert(" leadsubstatus "+leadsubstatus[0]); // 4
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
              
               
                //popup code
                //var test = $('#appiontment_fixed_date').val();    alert("testing "+test);
            
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

                //popup code
                //var test = $('#appiontment_fixed_date').val();    alert("testing "+test);
            }
           
            if (leadsubstatus[0] == "6")
            {
               // alert("in 6");
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
        /*END */


        

        $(".slidingDiv").hide();
        $(".show_hide").show();

        $('.show_hide').click(function () {
            $(".slidingDiv").slideToggle();
        });

        $(".deleteProd").click(function (e) {
            $this = $(this);
            e.preventDefault();
            var url = $(this).attr("href");
            var result = window.confirm('Are you sure to delete the product ?');
            if (result == false) {
                e.preventDefault();
            }
            else
            {
                $.get(url, function (r) {
                    if (r) {
                        //  $this.closest("tr").remove();
                        alert("Product will be deleted");
                        $this.closest("tr").remove();
                    }
                    else
                    {
                        //alert("returned false");
                    }
                })
            }
        });
    });
</script>
                <div class="announcement noprint" id="announcement">
                    <marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onmouseover="javascript:stop();" onmouseout="javascript:start();">LBL_NO_ANNOUNCEMENTS</marquee>
                </div>
                <input value="Leads" id="module" name="module" type="hidden">
                <input value="" id="parent" name="parent" type="hidden">
                <input value="Detail" id="view" name="view" type="hidden">

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
<div style="min-height: 636px;" class="bodyContents">

    <div class="mainContainer row-fluid">
        <div class="span2 row-fluid">
            <div class="row-fluid">
                <div class="sideBarContents">
                    <div class="quickLinksDiv">
                        <p class="selectedQuickLink" onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_RECORDS_LIST" class="unSelectedQuickLink"><a class="quickLinks" href="<?= base_url() ?>leads"><strong>Leads List</strong></a>
                        </p>
                        <p class="selectedQuickLink" onclick="window.location.href = '#'" id="Leads_sideBar_link_LBL_DASHBOARD" class="unSelectedQuickLink"><a class="quickLinks" href="<?= base_url() ?>dashboard"><strong>Dashboard</strong></a></p>
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
<form action="<?= base_url() ?>leads/updatelead/<?php echo $leaddetails['0']['leadid']; ?>" method="post" name="leadform" id="leadform" class="form-horizontal recordEditView">
            <input id="recordId" value="21" type="hidden">
            <div class="detailViewContainer">
                <div class="row-fluid detailViewTitle">
                    <div class="span10">
                        <div class="row-fluid">
                            <span class="pull-right">
                              <input class="submit" id="updatelead" name="updatelead" type="submit" value="Update" />
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
                            <!--<div class="span5">
                                    <div class="pull-right detailViewButtoncontainer">
                                            <div class="btn-toolbar">
                                                    
                                                    <span class="btn-group">
                                                            <button class="btn" id="Leads_detailView_basicAction_LBL_SEND_EMAIL" onclick='#'><strong>Send Email</strong>
                                                            </button>
                                                    </span>
                                                    <span class="btn-group">
                                                            <button class="btn" id="Leads_detailView_basicAction_LBL_CONVERT_LEAD" onclick='#'><strong>Convert Lead</strong>
                                                            </button>
                                                    </span>
                                            </div>
                                    </div>
                            </div>-->
                        </div>
                    </div>

                </div>
                <div class="detailViewInfo row-fluid">
                    <div class=" span11  details">
                            <div class="contents">
                                 <table class="table table-bordered equalSplit detailview-table">
                        <tbody>
                            <tr>
                                
                                <th class="blockHeader" colspan="4"><img class="cursorPointer alignMiddle blockToggle  hide  " src="<?= base_url() ?>public/vdfiles/arrowRight.png" data-mode="hide" data-id="13"><img class="cursorPointer alignMiddle blockToggle " src="<?= base_url() ?>public/vdfiles/arrowDown.png" data-mode="show" data-id="13">&nbsp;&nbsp;Lead Details
                                            </th>
                            </tr>
                            <tr>

                                <td class="field_name">
                                    <label >Customer</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('company', $optionscmp, set_value('company', (isset($leaddetails['0']['company'])) ? $leaddetails['0']['company'] : ''), 'id="company"', 'name="company"');
                                            echo form_error('company');
                                            ?>           
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Industry Type</label>
                                </td>
                                <td >
                                    <div class="row-fluid"> 
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('industry', $optionsinds, set_value('industry', (isset($leaddetails['0']['industry_id'])) ? $leaddetails['0']['industry_id'] : ''), 'id="industry"', 'name="industry"');
                                            echo form_error('industry');
                                            ?>         
                                        </span>
                                    </div>
                                </td>

                            </tr>
                            <!--  -->
                            <tr>
                                <td >
                                    <label >Customer Finished Goods / End Products </label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <textarea name="producttype" id="producttype"><?php echo trim($leaddetails['0']['producttype']); ?></textarea>
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Customer End Product Sale Type </label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('exportdomestic', $optionsexp, set_value('exportdomestic', (isset($leaddetails['0']['exporttype'])) ? $leaddetails['0']['exporttype'] : ''), 'id="exportdomestic"', 'name="exportdomestic"');
                                            echo form_error('exportdomestic');
                                            ?>  

                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >Purchase Decision Maker </label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <input type="text" name="purchasedecision" id="purchasedecision" value="<?php echo $leaddetails['0']['decisionmaker']; ?>" maxlength="40"  /> 
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Present Procurement / Purchase source</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('presentsource', $optionsprestsrc, set_value('presentsource', (isset($leaddetails['0']['presentsource'])) ? $leaddetails['0']['presentsource'] : ''), 'id="presentsource"', 'name="presentsource"');
                                            echo form_error('presentsource');
                                            ?>  

                                        </span>
                                    </div>

                                    <!-- Start -->
                                    <div id='contentSuplier' style="display: none">
                                        <div>
                                            <font color="blue">Enter the Name of the Supplier</font><input type="text" name="txtDomesticSource" id="txtDomesticSource" style="display: none" value="<?php echo $leaddetails['0']['domestic_supplier_name']; ?>" placeholder="Name of the Supplier">
                                        </div>

                                    </div>
                                    <!-- End -->
                                </td>
                            </tr>
                            <!--  -->
                            <tr>
                                <td >
                                    <label >Lead Status</label>
                                </td>
                                <td >
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
                                        <div id='jqxsoc' style='width:550 px; height:215px;'>
                                            <div> Select the SOC Number</div>
                                            <div>
                                                <div id="jqxcustomergrid" style='width:550px; height:215px;'></div>
                                            </div>
                                            <input type="hidden" id="hdnselid" value="<?= $this->uri->segment(3); ?>">
                                        </div>
                                    </div>
                                    <!-- End -->
                                </td>
                                <td >
                                    <label >Sub Status</label>
                                </td>
                                <td >
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
                                            <input type="text" name="appiontment_fixed_date" id="appiontment_fixed_date"  value="">

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
                                    <!-- End -->
                                     <!-- Start -->
                                    <div id='content_closingcomments' style="display: none">
                                        <div>
                                            <font color="blue">Enter the closing comments</font>
                                              <textarea name="closingcomments" id="closingcomments" style="display: none"  placeholder="enter closing comments "></textarea>
                                            <?php echo form_error('closingcomments'); ?>    
                                        </div>

                                    </div>
                                    <!-- End -->
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >Lead Source</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('leadsource', $optionslsr, set_value('leadsource', (isset($leaddetails['0']['leadsourceid'])) ? $leaddetails['0']['leadsourceid'] : ''), 'id="leadsource"', 'name="leadsource"');
                                            echo form_error('leadsource');
                                            ?> 

                                        </span>
                                    </div>
                                    <input type="hidden" id="hdn_cmnts" name="hdn_cmnts" value="<?php echo $leaddetails['0']['comments']; ?>">
                                    <input type="hidden" id="hdn_status_id" name="hdn_status_id" value="<?php echo $leaddetails['0']['leadstatusid']; ?>">
                                    <input type="hidden" id="hdn_assign_to" name="hdn_assign_to" value="<?php echo $leaddetails['0']['assignleadchk']; ?>">
                                    <input type="hidden" id="hdn_sub_status_id" name="hdn_sub_status_id" value="<?php echo $leaddetails['0']['ldsubstatus']; ?>">


                                </td>
                                <td >
                                    <label >Designation </label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <input type="text" name="designation" id="designation" value="<?php echo $leaddetails['0']['designation']; ?>" maxlength="80"  /> 

                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <!--  -->
                            <tr>
                                <td >
                                    <label >Comments</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <textarea name="comments" id="comments"><?php echo trim($leaddetails['0']['comments']); ?></textarea>
                                        </span>
                                    </div>
                                    <input type="hidden" id="hdn_cmnts" name="hdn_cmnts" value="<?php echo $leaddetails['0']['comments']; ?>">
                                </td>
                                <td >
                                    <label >Website</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <input type="text" name="website" id="website" value="<?php echo set_value('website', $leaddetails['0']['website']); ?>" size="25" /> 
                                            <?php echo form_error('website'); ?>       
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >Credit Assesment</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('credit_assesment', $optionscrd, set_value('credit_assesment', (isset($leaddetails['0']['crd_id'])) ? $leaddetails['0']['crd_id'] : ''), 'id="credit_assesment"', 'name="credit_assesment"');
                                            echo form_error('credit_assesment');
                                            ?> 

                                        </span>
                                    </div>
                                </td>

                                <td >
                                    <label >Branch</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('branch', $optionslocuser, set_value('branch', (isset($leaddetails['0']['user_branch'])) ? $leaddetails['0']['user_branch'] : ''), 'id="branch"', 'name="branch"');
                                            echo form_error('branch');
                                            ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td >
                                   
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                             
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Assigned To</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <?php
                                            echo form_dropdown('assignedto', $optionsasto, set_value('assignedto', (isset($leaddetails['0']['assignleadchk'])) ? $leaddetails['0']['assignleadchk'] : ''), 'id="assignedto"', 'name="assignedto"');
                                            echo form_error('assignedto');
                                            ?>  

                                        </span>
                                    </div>
                                </td>
                            </tr>
                       
                        </tbody>
                    </table>
                    <table class="table table-bordered equalSplit detailview-table">
                        <tbody>

                            <tr>
                                <th colspan="4" class="blockHeader">Address Details</th>
                            </tr>
                            <tr>
                                <td >
                                    <label >Contact Name</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_name">
                                        <input type="text" name="contact_name" id="contact_name" value="<?php echo $contact_person; ?>" size="25" /> 
<?php echo form_error('contact_name'); ?>  
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Email Id</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_email">
                                              <input  type="text" name="email_id" id="email_id" value="<?php echo $contact_mailid; ?>" size="30" />
<?php echo form_error('email_id'); ?>                 
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >Country</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_country">
                                        <input type="text" placeholder="Country Name" id="country" name="country" value="<?php echo $country; ?>" /> 
                                            <?php echo form_error('country');?> 
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >PO Box</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_po">
                                            <input type="text" name="postalcode" id="postalcode" value="<?php echo $postalcode; ?>" size="25" /> 
<?php echo form_error('postalcode'); ?>                
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >State</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_state">
                                        <input type="text" placeholder="State Name" id="state" name="state" value="<?php echo $state; ?>" /> 
                                            <?php echo form_error('state'); ?>       
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Phone</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_phone">
                                            <input  type="text" name="phone" id="phone" value="<?php echo $contact_number; ?>" size="25" />
<?php echo form_error('phone'); ?>               
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >City</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_city">
                                            <input type="text" placeholder="City Name" id="city" name="city" value="<?php echo $city; ?>" />
                                             <?php  echo form_error('city'); ?>
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Mobile</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_mobile">
                                            <input  type="text" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no; ?>" size="25" />
<?php echo form_error('mobile'); ?>               
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label >Street Address</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10" id="trReadOnlyFields_street">
                                            <textarea rows="3" cols="40" data-maxsize="249" name="street" id="street"/><?php echo $address; ?></textarea>
<?php echo form_error('street'); ?>
<div  id="counter"></div>             
                                        </span>
                                    </div>
                                </td>
                                <td >
                                    <label >Fax</label>
                                </td>
                                <td >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <input  type="text" name="fax" id="fax" value="<?php echo $fax; ?>" size="25" />
<?php echo form_error('fax'); ?>          
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered equalSplit detailview-table">
                        <tbody>
                            <tr>
                                <th colspan="4" class="blockHeader">Description Details</th>
                            </tr>
                            <tr>
                                <td >
                                    <label >Description</label>
                                </td>
                                <td colspan="3" >
                                    <div class="row-fluid">
                                        <span class="span10">
                                            <textarea style="width: 427px; height: 82px;" name="description" id="description"><?php echo trim($leaddetails['0']['description']); ?></textarea>
                                        </span>
                                    </div>
                                    <input type="hidden" id="hdn_desc" name="hdn_desc" value="<?php echo $leaddetails['0']['description']; ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     <table class="table table-bordered equalSplit detailview-table">
                        <tbody>
                            <tr>
                                <th colspan="4" class="blockHeader">Product Details</th>
                            </tr>
                        </tbody>
                     </table>       

                    <table class="table table-bordered " style="width=100%; height: 100%;">

                        <tr valign="top">
                            <td><label for="customFieldName">Product Name</label></td>
                            <td style="width=80%">
                                <?php
                                echo form_dropdown('customFieldName[]', $optionsproedit, set_value('customFieldName[]', (isset($leadproducts[0]['description'])) ? $leadproducts[0]['productid'] : ''), 'id="customFieldName"', 'name="customFieldName[]"', 'style="width:100px;"');
                                echo form_error('customFieldName');
                                ?> &nbsp;
                                &nbsp;
                                &nbsp;&nbsp;<b>Quantity</b>&nbsp; <input type="text"  id="customFieldValue" name="customFieldValue[]" class="code1" value="<?php echo $leadproducts[0]['quantity']; ?>" size="8" /> &nbsp;in MT / Month &nbsp;&nbsp;


                            </td>
                        </tr>
                        <?php
                        foreach ($leadproducts as $products) {
                            ?> 

                            <tr>    
                                <td style="width=20%"> <b>Type</b></td>
                                <td style="width=80%"> 
                                    <?php
                                    echo form_dropdown('customDispatch[]', $optionsprotypeedit, set_value('customDispatch[]', (isset($products['prod_type_id'])) ? $products['prod_type_id'] : ''), 'id="customDispatch"', 'name="customDispatch[]"', 'style="width:100px;"');
                                    echo form_error('customDispatch');
                                    ?> 

                                    &nbsp;</td>
                                <td style="width=20%"> <b>Potential</b></td>
                                <td style="width=80%">   
                                    <input type="hidden" id="leadprodid" name="leadprodid[]" value="<?php echo $products['lpid']; ?>"/>
                                    <input type="text" class="code1" id="customFieldPoten" name="customFieldPoten[]" value="<?php echo $products['potential']; ?>" size="8" /> &nbsp;in MT / Month &nbsp;
                            </tr>

                            <?php
                        }
                        ?>
                    </table>
                    <span class="pull-right">
                      <input class="submit" id="updatelead" name="updatelead" type="submit" value="Update" />
                      <a onclick="javascript:window.history.back();"><input class="reset" type="reset" class="cancelLink" value="Cancel"> </a>
                    </span>
                    </div>

                    </div>


                </div>

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
