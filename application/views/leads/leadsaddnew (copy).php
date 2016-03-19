<?php $this->load->view('header_novalid'); ?>
<!-- jqwidgets scripts -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
<style>
.controls {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 9999;
}
.dropdowncmp {
    width: 75%;
}
</style>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>
<!-- sorting and filtering - start -->

<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxradiobutton.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>


<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.export.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.metrodark.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script> 
    <script src="<?= base_url() ?>public/js/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>public/js/additional-methods.js"></script>
<script src="<?= base_url() ?>public/js/validation_rules.js"></script> 

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
            var lead_sub_onstatus_change;
            var global_leadstatus;
            var controller = 'leads';
            var base_url = '<?php echo site_url(); ?>';
            global_leadstatus = 1;
            function openpopupwindow(obj)
            {
                var id = obj.id;
                window.open(base_url + 'product/selectproduct/' + id, '_blank', 'width=600,height=500,scrollbars=yes,status=yes,resizable=yes,screenx=300,screeny=100,addressbar=no');

            }


//openpopupwindow(this);
            $(document).ready(function ()
            {
                var customer_id = $('#company').val();
                var regularProdChkVal = $('#regularProd').jqxRadioButton('val');
                var indBusChkVal = $('#indentingBusiness').jqxRadioButton('val');
          
                //alert("customer_id "+customer_id);
                var validateProductName = $('#validateProductName');
                validateProductName.html("<font color=red>Please select  the Product</font>");
                var data = {};
                $("#custprodgrid").hide();

                $("#content_appiontment_date").jqxDateTimeInput({width: '170px', height: '25px'});
                $('#content_appiontment_date').val(null);

                /*start*/
                var characters = 250;
                $("#counter").append("You have <strong>" + characters + "</strong> characters remaining");
                $("#street").keyup(function ()
                {
                    if ($(this).val().length > characters)
                    {
                        $(this).val($(this).val().substr(0, characters));
                    }
                    var remaining = characters - $(this).val().length;
                    $("#counter").html("You have <strong>" + remaining + "</strong> characters remaining");
                    if (remaining <= 10)
                    {
                        $("#counter").css("color", "red");
                    }
                    else
                    {
                        $("#counter").css("color", "black");
                    }
                });
                /*end*/


                /*ProdType Listbox start */
                    $("#regularProd").jqxRadioButton({ width: 150, height: 25 });
                    $("#indentingBusiness").jqxRadioButton({ width: 300, height: 25 });
                    // bind to change event.
                    
                    $("#regularProd").bind('change', function (event) {
                        var checked = event.args.checked;
                        alert('regularProd checked: ' + checked);
                        regularProdChkVal=true;
                        indBusChkVal =false;
                         $("#custprodgrid").show();
                         $("#custprodgrid_ind").hide();
                    });
                    $("#indentingBusiness").bind('change', function (event) {
                        var checked = event.args.checked;
                        alert('indentingBusiness checked: ' + checked);
                        regularProdChkVal=false;
                        indBusChkVal =true;
                         $("#custprodgrid").hide();
                         $("#custprodgrid_ind").show();

                    });
                /*ProdType Listbox End */
                var generaterow = function (i) {
                    var row = {};

                    row["product_name"] = "";
                    row["product_id"] = "";
                    row["bulk"] = "";
                    row["repack"] = "";
                    row["small_packing"] = "";
                    row["intact"] = "";
                    row["part_tanker"] = "";
                    row["single_tanker"] = "";
                    row["requirment"] = "";


                    return row;
                }
                for (var i = 0; i < 1; i++) {
                    var row = generaterow(i);
                    data[i] = row;
                }

                var source =
                        {
                            localdata: data,
                            datatype: "local",
                            datafields:
                                    [
                                        {name: 'product_name', type: 'string'},
                                        {name: 'product_id', type: 'string'},
                                        {name: 'bulk', type: 'string'},
                                        {name: 'repack', type: 'string'},
                                        {name: 'small_packing', type: 'number'},
                                        {name: 'intact', type: 'number'},
                                        {name: 'part_tanker', type: 'number'},
                                        {name: 'single_tanker', type: 'number'},
                                        {name: 'requirment', type: 'string'}
                                    ],
                            addrow: function (rowid, rowdata, position, commit) {
                                commit(true);
                            },
                            deleterow: function (rowid, commit) {
                                commit(true);
                            },
                            updaterow: function (rowid, newdata, commit) {
                                commit(true);
                            }
                        };

                var dataAdapter = new $.jqx.dataAdapter(source);
                $('#company').on('change', function () {
                    customer_id = this.value;

                    /*Start for getting customer address details*/
                    /*test obje[{leadid:"22288", cityname:"Gwalior", statecode:"MP", countrycode:"IN", country:"", state:"", address:"test"}]*/
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: "getcustomeraddress/" + customer_id,
                        success: function (customer_address)
                        {
                            /* alert("test obje"+data.toSource());
                             alert(" person "+customer_address[0].contact_person);
                            */

                            if (customer_address[0].cust_account_id == null || customer_address[0].cust_account_id == 0)
                            {
                                //alert("this is a temp customer");
                                $("#trReadOnlyFields > input").attr("readonly", false);
                                $('#street').prop('readonly', false);

                            }
                            else
                            {
                               
                                if (customer_address[0].contact_person == null || customer_address[0].contact_person == "")
                                {
                                    
                                    $("#trReadOnlyFields_name > input").attr("readonly", false);
                                }
                                else
                                {
                                    $("#trReadOnlyFields_name > input").attr("readonly", true);
                                }
                                if (customer_address[0].contact_mailid == null || customer_address[0].contact_mailid == "")
                                {
                                    
                                    $("#trReadOnlyFields_email > input").attr("readonly", false);
                                }
                                else
                                {
                                    $("#trReadOnlyFields_email > input").attr("readonly", true);
                                }
                                if (customer_address[0].contact_number == null || customer_address[0].contact_number == "")
                                {
                                    
                                    $("#trReadOnlyFields_phone > input").attr("readonly", false);
                                }
                                else
                                {
                                    $("#trReadOnlyFields_phone > input").attr("readonly", true);
                                }

                                if (customer_address[0].address == null || customer_address[0].address == "")
                                {
                                    
                                    $("#trReadOnlyFields_street > input").attr("readonly", false);
                                    $('#street').prop('readonly', false);
                                }
                                else
                                {
                                    $("#trReadOnlyFields_street > input").attr("readonly", true);
                                    $('#street').prop('readonly', true);
                                }
                                if (customer_address[0].statename == null || customer_address[0].statename == "")
                                {
                                    
                                    $("#trReadOnlyFields_state > input").attr("readonly", false);
                                }
                                else
                                {

                                    $("#trReadOnlyFields_state > input").attr("readonly", true);
                                }
                                if (customer_address[0].cityname == null || customer_address[0].cityname == "")
                                {
                                    
                                    $("#trReadOnlyFields_city > input").attr("readonly", false);
                                }
                                else
                                {

                                    $("#trReadOnlyFields_city > input").attr("readonly", true);
                                }
                                if (customer_address[0].contryname == null || customer_address[0].contryname == "")
                                {
                                    
                                    $("#trReadOnlyFields_country > input").attr("readonly", false);
                                }
                                else
                                {

                                    $("#trReadOnlyFields_country > input").attr("readonly", true);
                                }
                                if (customer_address[0].postalcode == null || customer_address[0].postalcode == "")
                                {

                                    $("#trReadOnlyFields_po > input").attr("readonly", false);
                                }
                                else
                                {

                                    $("#trReadOnlyFields_po > input").attr("readonly", true);
                                }

                            }
                            $('#street').val(customer_address[0].address);

                            $('#country').val(customer_address[0].contryname);
                            $('#state').val(customer_address[0].statename);
                            $('#postalcode').val(customer_address[0].postalcode);
                            $('#mobile').val(customer_address[0].mobile_no);
                            $('#fax').val(customer_address[0].fax);

                            var optcity = $('#city');
                            var sel = document.getElementById('city');
                            optcity.val(customer_address[0].cityname);

                            $('#contact_name').val(customer_address[0].contact_person);
                            $('#email_id').val(customer_address[0].contact_mailid);
                            $('#phone').val(customer_address[0].contact_number);

                        }
                    });
                    /*End for getting customer address details*/
                });


                $("#custprodgrid").jqxGrid(
                        {
                            width: 900,
                            height: 200,
                            source: dataAdapter,
                            showtoolbar: true,
                            editable: true,
                            enabletooltips: true,
                            rendertoolbar: function (toolbar) {
                                var me = this;
                                var container = $("<div style='margin: 5px;'></div>");
                                toolbar.append(container);
                                container.append('<input id="addrowbutton" type="button" value="Add New Row" />');
                                container.append('<input style="margin-left: 5px;" id="deleterowbutton" type="button" value="Delete Selected Row" />');
                                container.append('<lable><font color=black>Enter Potential and Immediate Requirements in  MT / Month</font></labe>');

                                $("#addrowbutton").jqxButton();
                                $("#deleterowbutton").jqxButton();
                                // update row.

                                // create new row.
                                $("#addrowbutton").on('click', function () {
                                    var datarow = generaterow();
                                    var commit = $("#custprodgrid").jqxGrid('addrow', null, datarow);
                                });

                                // delete row.
                                $("#deleterowbutton").on('click', function () {
                                    var selectedrowindex = $("#custprodgrid").jqxGrid('getselectedrowindex');
                                    var rowscount = $("#custprodgrid").jqxGrid('getdatainformation').rowscount;
                                    if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
                                        var id = $("#custprodgrid").jqxGrid('getrowid', selectedrowindex);
                                        var commit = $("#custprodgrid").jqxGrid('deleterow', id);
                                    }
                                });
                            },
                            cellhover: function (element, pageX, pageY)
                            {

                                var cell = $('#custprodgrid').jqxGrid('getcellatposition', pageX, pageY);
                                var cellValue = cell.value;
                                if (cell.column == "product_name")
                                {
                                    //  alert(" test "+cellValue);
                                    var tooltipContent = "<div style='color: Red;'>Double Click to Select The Product</div>";
                                    $("#custprodgrid").jqxTooltip({content: tooltipContent});
                                    $("#custprodgrid").jqxTooltip('open', pageX + 15, pageY + 15);

                                }
                                else
                                {
                                    $("#custprodgrid").jqxTooltip('close');
                                }
                            },
                            columns: [
                                {text: 'Product Name', datafield: 'product_name', width: 300, editable: false},
                                {text: 'Product id', datafield: 'product_id', width: 50, editable: false, hidden:  hidden: function () 
                                     {
                                      if (regularProdChkVal==true) 
                                      {return true;}
                                      else
                                      {return false;}

                                     }
                                },
                                {text: 'Bulk', datafield: 'bulk', width: 50},
                                {text: 'Repack', datafield: 'repack', width: 65},
                                {text: 'Small Packing', datafield: 'small_packing', width: 110, cellsalign: 'right'},
                                {text: 'Intact', datafield: 'intact', width: 55, cellsalign: 'right'},
                                {text: 'Part Tanker', datafield: 'part_tanker', width: 100, cellsalign: 'right'},
                                {text: 'Single - Tanker', datafield: 'single_tanker', width: 120, cellsalign: 'right'},
                                {text: 'Immediate Requirement', datafield: 'requirment', width: 100, cellsalign: 'right'},
                            ],
                            columnsresize: true,
                        });
                /*Indenting Products Start*/
                $("#custprodgrid_ind").jqxGrid(
                        {
                            width: 900,
                            height: 200,
                            source: dataAdapter,
                            showtoolbar: true,
                            editable: true,
                            enabletooltips: true,
                            rendertoolbar: function (toolbar) {
                                var me = this;
                                var container = $("<div style='margin: 5px;'></div>");
                                toolbar.append(container);
                                container.append('<input id="addrowbutton" type="button" value="Add New Row" />');
                                container.append('<input style="margin-left: 5px;" id="deleterowbutton" type="button" value="Delete Selected Row" />');
                                container.append('<lable><font color=black>Enter Potential and Immediate Requirements in  MT / Month</font></labe>');

                                $("#addrowbutton").jqxButton();
                                $("#deleterowbutton").jqxButton();
                                // update row.

                                // create new row.
                                $("#addrowbutton").on('click', function () {
                                    var datarow = generaterow();
                                    var commit = $("#custprodgrid_ind").jqxGrid('addrow', null, datarow);
                                });

                                // delete row.
                                $("#deleterowbutton").on('click', function () {
                                    var selectedrowindex = $("#custprodgrid_ind").jqxGrid('getselectedrowindex');
                                    var rowscount = $("#custprodgrid_ind").jqxGrid('getdatainformation').rowscount;
                                    if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
                                        var id = $("#custprodgrid_ind").jqxGrid('getrowid', selectedrowindex);
                                        var commit = $("#custprodgrid_ind").jqxGrid('deleterow', id);
                                    }
                                });
                            },
                            cellhover: function (element, pageX, pageY)
                            {

                                var cell = $('#custprodgrid_ind').jqxGrid('getcellatposition', pageX, pageY);
                                var cellValue = cell.value;
                                if (cell.column == "product_name")
                                {
                                    //  alert(" test "+cellValue);
                                    var tooltipContent = "<div style='color: Red;'>Double Click to Select The Product</div>";
                                    $("#custprodgrid_ind").jqxTooltip({content: tooltipContent});
                                    $("#custprodgrid_ind").jqxTooltip('open', pageX + 15, pageY + 15);

                                }
                                else
                                {
                                    $("#custprodgrid_ind").jqxTooltip('close');
                                }
                            },
                            columns: [
                                {text: 'Product Name', datafield: 'product_name', width: 300, editable: false},
                                {text: 'Product id', datafield: 'product_id', width: 50, editable: false, hidden: true},
                                {text: 'Bulk', datafield: 'indent_bulk', width: 50},
                                {text: 'Iso Container', datafield: 'iso_container', width: 150},
                                {text: 'FCL', datafield: 'fcl', width: 110, cellsalign: 'right'},
                                {text: 'Immediate Requirement', datafield: 'requirment', width: 250, cellsalign: 'right'},
                            ],
                            columnsresize: true,
                        });
                /*Indenting Products End*/

                /* Select Product from POPUP start */
                $("#custprodgrid").on("celldoubleclick", function (event)
                {

                    var column = event.args.column;
                    var rowindex = event.args.rowindex;
                    jqxgrid_add_row_index = rowindex;
                    jqxgrid_n_row_index = rowindex;

                    column_edit_index = event.args;
                    var columnindex = event.args.columnindex;
                    var columnname = column.datafield;
                    var columntext = column.text;
                    var displayValue = event.args.value;
                    if (customer_id == "")
                    {
                        alert("Please select the customer before selecting the product");
                    }
                    else
                    {
                        if (columnname == 'product_name')
                        {

                            loadproducts();
                            $('#win_selectItemMaster').jqxWindow({theme: 'darkblue', autoOpen: false, isModal: true, width: 400, height: 500, resizable: true, modalOpacity: 0.01, title: 'select product'});
                            $('#win_selectItemMaster').jqxWindow({position: {x: 500, y: 100}});
                            $('#win_selectItemMaster').jqxWindow('open');

                        }
                    }

                });

    // Select Product for Indenting Business Start
                $("#custprodgrid_ind").on("celldoubleclick", function (event)
                {

                    var column = event.args.column;
                    var rowindex = event.args.rowindex;
                    jqxgrid_add_row_index = rowindex;
                    jqxgrid_n_row_index = rowindex;

                    column_edit_index = event.args;
                    var columnindex = event.args.columnindex;
                    var columnname = column.datafield;
                    var columntext = column.text;
                    var displayValue = event.args.value;
                    if (customer_id == "")
                    {
                        alert("Please select the customer before selecting the product");
                    }
                    else
                    {
                        if (columnname == 'product_name')
                        {

                            loadproducts();
                            $('#win_selectItemMaster').jqxWindow({theme: 'darkblue', autoOpen: false, isModal: true, width: 400, height: 500, resizable: true, modalOpacity: 0.01, title: 'select product'});
                            $('#win_selectItemMaster').jqxWindow({position: {x: 500, y: 100}});
                            $('#win_selectItemMaster').jqxWindow('open');

                        }
                    }

                });
    // Select Product for Indenting Business End 

//  return value from item master start
                $("#jqxgrid_selectItemMaster").on('cellselect', function (event)
                {
                    var rowindex = $("#jqxgrid_selectItemMaster").jqxGrid('getselectedrowindex', event.args.rowindex);
                    var prodName = $("#jqxgrid_selectItemMaster").jqxGrid('getcellvalue', event.args.rowindex, 'description');
                    var prodid = $("#jqxgrid_selectItemMaster").jqxGrid('getcellvalue', event.args.rowindex, 'id');
                    /*code to check product duplicates start */
                    
                    prodid_g = prodid;
                    $.ajax({
                        type: "POST",
                        url: base_url + 'leads/checkduplicate_product/' + prodid + '/' + customer_id,
                        data: 'prodid=' + prodid + '&customerid=' + customer_id,
                        dataType: 'json',
                        success: function (response)
                        {

                            if (response.ok == false)
                            {
                                //  datevalidation=false;
                                validateProductName.html(response.msg);
                                //     alert("Oh..!, this Product Group has already added for this customer.")
                            }
                            else
                            {
                                // datevalidation=true;
                                validateProductName.html(response.msg);
                                $("#custprodgrid").jqxGrid('setcellvalue', jqxgrid_add_row_index, "product_name", prodName);
                                $("#custprodgrid").jqxGrid('setcellvalue', jqxgrid_add_row_index, "product_id", prodid);
                                $('#win_selectItemMaster').jqxWindow('close');

                            }

                        }
                    })

                    /*code to check product dupliates end*/


                });
//  return value from item master end

//  return value from item master to indenting business start
                $("#jqxgrid_selectItemMaster").on('cellselect', function (event)
                {
                    var rowindex = $("#jqxgrid_selectItemMaster").jqxGrid('getselectedrowindex', event.args.rowindex);
                    var prodName = $("#jqxgrid_selectItemMaster").jqxGrid('getcellvalue', event.args.rowindex, 'description');
                    var prodid = $("#jqxgrid_selectItemMaster").jqxGrid('getcellvalue', event.args.rowindex, 'id');
                    /*code to check product duplicates start */
                    
                    prodid_g = prodid;
                    $.ajax({
                        type: "POST",
                        url: base_url + 'leads/checkduplicate_product/' + prodid + '/' + customer_id,
                        data: 'prodid=' + prodid + '&customerid=' + customer_id,
                        dataType: 'json',
                        success: function (response)
                        {

                            if (response.ok == false)
                            {
                                //  datevalidation=false;
                                validateProductName.html(response.msg);
                                //     alert("Oh..!, this Product Group has already added for this customer.")
                            }
                            else
                            {
                                // datevalidation=true;
                                validateProductName.html(response.msg);
                                $("#custprodgrid_ind").jqxGrid('setcellvalue', jqxgrid_add_row_index, "product_name", prodName);
                                $("#custprodgrid_ind").jqxGrid('setcellvalue', jqxgrid_add_row_index, "product_id", prodid);
                                $('#win_selectItemMaster').jqxWindow('close');

                            }

                        }
                    })

                    /*code to check product dupliates end*/


                });
//  return value from item master to indenting business start


                function disableButtons()
                {
                    alert("regularProdChkVal "+regularProdChkVal);
                    alert("indBusChkVal "+indBusChkVal);
                    var btn_val_old = $("input[type=submit][clicked=true]").val()
                    var btn_id = $(this).find("#saveleads").attr("id");
                    var form = $("#leadform");
                    var btns = $("input:submit", form);

                    if (!form.valid()) {
                        // allow user to correct validation errors and re-submit
                        $("#hdn_saveleads").val("saveNoleads");
                        btns.removeAttr("disabled");

                    } else {
                        
                        $(".jqx-grid-validation, .jqx-grid-validation-arrow-up, .jqx-grid-validation-arrow-down").remove();

                        $("#hdn_saveleads").val("saveYesleads");
                        var griddata;
                        var valid_prodname = 0;
                        var pr_name;
                        var req;
                        var valid_req = 0;

                        var rowscount = $("#custprodgrid").jqxGrid('getdatainformation').rowscount;
                        for (var i = 0; i < rowscount; i++)
                        {
                            
                            var pr_name = $('#custprodgrid').jqxGrid('getcellvalue', i, "product_name");
                            var req = $('#custprodgrid').jqxGrid('getcellvalue', i, "requirment");
                            var bulkPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "bulk");
                            var repackPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "repack");
                            var small_packingPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "small_packing");
                            var intactPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "intact");
                            var part_tankerPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "part_tanker");
                            var single_tankerPotential = $('#custprodgrid').jqxGrid('getcellvalue', i, "single_tanker");
                            //  alert("requirment "+req);


                            if (pr_name == "" || pr_name == 'undefined')
                            {
                                $("#custprodgrid").jqxGrid('showvalidationpopup', i, "product_name", "Please Select the Product Name");
                                valid_prodname = 0;

                            }
                            else
                            {
                                valid_prodname = 1;
                            }
                            if (valid_prodname == 0)
                            {
                                return false;
                            }

                            if (req == "" || req == 'undefined' || req.trim() == "")
                            {
                                $("#custprodgrid").jqxGrid('showvalidationpopup', i, "requirment", "Please enter the Present Requirment");
                                valid_req = 0;

                            }
                            else
                            {
                                valid_req = 1;
                            }

                            /*potential validation start*/
                            if ($('#leadstatus').val() > 1 && regularProdChkVal==true)
                            {
                                //alert(" bulkPotential  "+bulkPotential);
                                var potential_reg = 0;
                                if (bulkPotential != "" && bulkPotential != 'undefined' && bulkPotential > 0)
                                    potential_reg = 1;
                                if (repackPotential != "" && repackPotential != 'undefined' && repackPotential > 0)
                                    potential_reg = 1;
                                if (small_packingPotential != "" && small_packingPotential != 'undefined' && small_packingPotential > 0)
                                    potential_reg = 1;
                                if (intactPotential != "" && intactPotential != 'undefined' && intactPotential > 0)
                                    potential_reg = 1;
                                if (part_tankerPotential != "" && part_tankerPotential != 'undefined' && part_tankerPotential > 0)
                                    potential_reg = 1;
                                if (single_tankerPotential != "" && single_tankerPotential != 'undefined' && single_tankerPotential > 0)
                                    potential_reg = 1;

                                if (potential_reg == 0)
                                {
                                    $("#custprodgrid").jqxGrid('showvalidationpopup', i, "bulk", "Enter atleast one Potential, value greater than 0");
                                    return false;
                                }
                            }

                            /*potential validation End*/
                            if (valid_req == 0)
                            {
                                return false;
                            }
                            var rowval;
                            griddata = $('#custprodgrid').jqxGrid('getrowdata', i);

                            var start_str = "{";
                            var json_str = "\"productname\":\"" + griddata.product_name + "\",\"product_id\":\"" + griddata.product_id + "\",\"bulk\":\"" + griddata.bulk + "\",\"repack\":\"" + griddata.repack + "\",\"small_packing\":\"" + griddata.small_packing + "\",\"intact\":\"" + griddata.intact + "\",\"part_tanker\":\"" + griddata.part_tanker + "\",\"single_tanker\":\"" + griddata.single_tanker + "\",\"requirment\":\"" + griddata.requirment + "\"";

                            var end_str = "}";
                            rowstr = start_str + json_str + end_str;
                            rowval = rowval + rowstr + ",";
                        }

                        rowval = rowval.replace("undefined", "");
                        rowval = rowval.substring(0, rowval.length - 1);
                        grid_row_data = "[" + rowval + "]";
                        // btn_val_new= $(this).find("#saveleads").val("saveYesleads");   

                        btns.attr("disabled", "disabled");
                        $('#hdn_grid_row_data').val(grid_row_data);
                        //   return false;
                    }
                }
                $("#leadform").bind("submit", disableButtons);
                var theme = "";
                // Create a jqxMenu
                $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
                $("#jqxMenu").css('visibility', 'visible');

                var j = 0;
                var count = 0;



                $.getJSON("getinitial_lead_sub",
                        {
                            tags: "lead sub",
                            tagmode: "any",
                            format: "json"
                        })
                        .done(function (data)
                        {
                            $.each(data, function (index, text)
                            {
                                $('#leadsubstatus').append(
                                        $('<option></option>').val(text.lst_sub_id).html(text.lst_name)
                                        );
                            });
                        });
                var i = 1;




                $('#leadstatus').change(function ()
                {

                    $("#leadsubstatus > option").remove();
                    var option = $('#leadstatus').val();
                    lead_sub_onstatus_change = $('#leadsubstatus').val();



                    global_leadstatus = option;
                    if (option == '#')
                    {
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: "getleadsubstatusadd/" + option,
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

                            }
                            else
                            {
                                $('#content_no_2pano').hide();
                                $('#lead_2pa_no').hide();

                                $('#content_appiontment_date').hide();
                                $('#appiontment_fixed_date').hide();

                                $('#content_no_appointment').hide();
                                $('#not_able_to_get_appointment').hide();
                            }


                        }

                    });

                });

                /**/
                $('#presentsource').change(function () {


                    var option = $('#presentsource').val();  // here we are taking option id of the selected one.

                    if (option == "Domestic and Import" || option == "Domestic")
                    {
                        $('#contentsuplier').show();
                        $('#txtDomesticSource').show();
                        //popup code

                    }
                    else
                    {
                        $('#txtDomesticSource').hide();
                        $('#contentsuplier').hide();
                        contentsuplier
                    }
                });
                /**/
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
                        url: "getassignedtobranch/" + option,
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






                /*Start*/
                $('#leadsubstatus').change(function () {


                    
                    var leadsubstatus = $('#leadsubstatus').val();  // here we are taking option id of the selected one.
                    
                    leadsubstatus = leadsubstatus.split('-');
                    if ((leadsubstatus[0] == "4") && (global_leadstatus == "1"))
                    {
                        $('#content_appiontment_date').show();
                        $('#appiontment_fixed_date').show();
                        //popup code
                        $('#content_no_2pano').hide();
                        $('#lead_2pa_no').hide();
                    }
                    else
                    {
                        $('#content_appiontment_date').hide();
                        $('#appiontment_fixed_date').hide();
                    }
                    if ((leadsubstatus[0] == "6") && (global_leadstatus == "1"))
                    {
                        $('#content_no_appointment').show();
                        $('#not_able_to_get_appointment').show();
                        //popup code

                        $('#content_no_2pano').hide();
                        $('#lead_2pa_no').hide();

                    }
                    else
                    {
                        $('#content_no_appointment').hide();
                        $('#not_able_to_get_appointment').hide();
                    }

                    if ((leadsubstatus[0] == "8") && (global_leadstatus == "3"))
                    {
                        $('#content_no_2pano').show();
                        $('#lead_2pa_no').show();
                        //popup code


                    }
                    else
                    {
                        $('#content_no_2pano').hide();
                        $('#lead_2pa_no').hide();
                    }
                });
                /*END */



            });
            $(document).ready(function () {
                var theme = "";
                // Create a jqxDateTimeInput
                $("#uploaded_date").jqxDateTimeInput({width: '170px', height: '25px'});
            });
        </script>
<!-- sorting and filtering and export excel - end -->
<!-- paging - start -->

<!-- paging - end -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
<!-- End of jqwidgets -->

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
<div  class="bodyContents" style="min-height: 636px; min-width: 1231px;">
                    <div class="mainContainer row-fluid">
                        <div class="span2 row-fluid">
                            <div class="row-fluid">
                                <div class="sideBarContents">
                                    <div class="quickLinksDiv">
                        <p  id="Leads_sideBar_link_LBL_RECORDS_LIST" class="selectedQuickLink">
                            <a class="quickLinks" href="<?= base_url() ?>leads"><strong>Leads List</strong>
                            </a>
                        </p>
                        <!--converted leads  -->                        
                        <p  id="Leads_sideBar_link_LBL_RECORDS_LIST1" class="selectedQuickLink">
                            <a class="quickLinks" href="<?= base_url() ?>leads/convertedleads"><strong>Converted Leads</strong>
                            </a>
                        </p>
                        <!-- converted leads  -->                                               
                        <p  id="Leads_sideBar_link_LBL_DASHBOARD" class="selectedQuickLink">
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
                                </div> <!-- end of sideBarContents -->
                            </div>
                        </div>
                        <div class="contentsDiv span10 marginLeftZero">

                            <input id="recordId" value="21" type="hidden">
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
                                                            <span class="recordLabel font-x-x-large textOverflowEllipsis pushDown span" title="">
                                                                <span class="salutation"></span>&nbsp;
                                                                <span class="firstname">New Lead Generation</span>&nbsp;

                                                            </span>
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

                                        <div class="contents" style="margin-top:-91px;">
                                            <div class="mainContainer floatRight">

                                                <form action="savelead" method="post" name="leadform" id="leadform" class="form-horizontal recordEditView">

                                                    <div class="floatRight">

                                                        <span class="pull-right">
                                                            <input class="submit" id="saveleads" name="saveleads" type="submit"  value="Submit" />
                                                            <a onclick="javascript:window.history.back();"><input class="reset" type="reset" class="cancelLink" value="Cancel"> </a>
                                                        </span>

                                                    </div>
                                                    <!-- Start -->
                                                    <?php
                                                    $selattrs = array(
                                                        'width' => '750',
                                                        'height' => '300',
                                                        'scrollbars' => 'yes',
                                                        'status' => 'yes',
                                                        'resizable' => 'yes',
                                                        'screenx' => '0',
                                                        'screeny' => '0',
                                                        'id' => 'selid0',
                                                        'class' => 'mySelClass'
                                                    );
                                                    ?>
                                                    <table class="table table-bordered blockContainer showInlineTable">
                                                        <tbody>

                                                            <tr>
                                                                <th colspan="4" class="blockHeader buleboder-full">Select Customer</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType" style="width:2%;">
                                                                    <label class="muted pull-right marginRight10px">Customer<font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            $atts = array(
                                                                                'width' => '750',
                                                                                'height' => '350',
                                                                                'scrollbars' => 'yes',
                                                                                'status' => 'yes',
                                                                                'resizable' => 'yes',
                                                                                'screenx' => '0',
                                                                                'screeny' => '0'
                                                                            );

                                                                            echo form_dropdown('company', $optionscmp, '', 'id="company" name="company" class="dropdowncmp"');
                                                                            echo form_error('company');
                                                                            echo anchor_popup('leads/addnewcustomer', 'Add New Customer', $atts);
                                                                            ?> 
                                                                        </span>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table class="table table-bordered blockContainer showInlineTable"> 
                                                        <tbody>
                                                           
                                                            <tr>
                                                                <th colspan="5" class="blockHeader">Product Details 

                                                                    <span>&nbsp;&nbsp;<?php
                                                                        echo form_error('product');
                                                                        echo anchor_popup('product/addnewitem/' . $this->session->userdata['user_id'], 'Add New Products', $selattrs);
                                                                        ?>
                                                                    </span> 
                                                                </th>
                                                            </tr>
                                                             <tr>
                                                                <th colspan="5" class="blockHeader"  style="float:left; display:flex; width:98%;">Product Type
                                                                  <div id='regularProd' style="padding-left:46px;"> Regular</div><div id='indentingBusiness'>Indenting Business</div>
                                                                </th>
                                                            </tr>
                                                        <th>
                                                        <div id="custprodgrid"></div>
                                                        <div id="custprodgrid_ind"></div>

                                                        </th>
                                                        </tbody>
                                                    </table>
                                                    <!-- End -->
                                                    <!-- hidden table start -->
                                                    <table border="0" name="customhndFields" id="customhndFields" style="width=100%; height: 100%; display:block;">
                                                        <tbody>
                                                            <tr valign="top" id="customFieldsrow" name="customFieldsrow[]">
                                                                <td width="30%">
                                                                    <input class="myhdnClass" type="hidden"  id="hdncustomFieldName0" name="hdncustomFieldName[]"></td>
                                                                <td width=""></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    </td>

                                                    </tr>
                                                    </tbody>
                                                    </table>
                                                    <!-- hidden table end -->


                                                    <table class="table table-bordered blockContainer showInlineTable">
                                                        <tbody>

                                                            <tr>
                                                                <th colspan="4" class="blockHeader buleboder-full">Lead Details</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Designation</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <input type="text" name="designation" id="designation" value="" maxlength="80"  /> 
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Industry Type<font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('industry', $optionsinds, 'id="industry"', 'name="industry"');
                                                                            echo form_error('industry');
                                                                            ?>  

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!--  -->
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Customer Finished Goods / End Products</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">

                                                                            <textarea name="producttype" class="row-fluid " id="producttype"></textarea>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Customer End Product Sale Type </label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('exportdomestic', $optionsexp, '', 'id="exportdomestic"', 'name="exportdomestic"');
                                                                            echo form_error('exportdomestic');
                                                                            ?>  

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Purchase Decision Maker </label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <input type="text" name="purchasedecision" id="purchasedecision" value="" maxlength="40"  /> 
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Present Procurement / Purchase source </label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('presentsource', $optionsprestsrc, '', 'id="presentsource"', 'name="presentsource"');
                                                                            echo form_error('presentsource');
                                                                            ?>  

                                                                        </span>
                                                                    </div>
                                                                    <!-- Start -->
                                                                    <div id='contentsuplier' style="display: none">
                                                                        <div>
                                                                            <font color="blue">Enter the Name of the Supplier</font><input type="text" name="txtDomesticSource" id="txtDomesticSource" style="display: none"  placeholder="Name of the Supplier">
                                                                        </div>

                                                                    </div>
                                                                    <!-- End -->
                                                                </td>
                                                            </tr>
                                                            <!--  -->
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Lead Status<font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('leadstatus', $optionslst, '', 'id="leadstatus"', 'name="leadstatus"');
                                                                            echo form_error('leadstatus');
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Sub Status <font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <select name="leadsubstatus" id="leadsubstatus"><option value="">-Select Substatus--</option></select>
                                                                            <?php echo form_error('leadsubstatus'); ?>      

                                                                        </span>
                                                                    </div>
                                                                    <!-- Start -->
                                                                    <div id='content_appiontment_date' style="display: none;">
                                                                        <div>
                                                                            <font color="blue">Enter the Appiontment Date</font>
                                                                            <input type="text" name="content_appiontment_date" id="content_appiontment_date"  value="">

                                                                        </div>

                                                                    </div>
                                                                    <!-- End -->
                                                                    <!-- Start -->
                                                                    <div id='content_no_appointment' style="display: none">
                                                                        <div>
                                                                            <font color="blue">Reason for Not able to get appointment</font>
                                                                            <input type="text" name="not_able_to_get_appointment" id="not_able_to_get_appointment" style="display: none"  placeholder="Reason for not able to get appiontment">
                                                                            <?php echo form_error('not_able_to_get_appointment'); ?>  


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
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Lead Source <font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('leadsource', $optionslsr, 'id="leadsource"', 'name="leadsource"');
                                                                            echo form_error('leadsource');
                                                                            ?>  

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Uploaded Date <font color="red"> *</font><br></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType"><font size="1" color="red">This field is mandatory!</font>
                                                                    <div id="uploaded_date" class="row-fluid">
                                                                        <span class="span10">
                                                                            <input type="text" name="uploaded_date" id="uploaded_date"  value="">


                                                                        </span>
                                                                    </div>

                                                                </td>
                                                            </tr>   
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Lead Comments </label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <textarea name="comments" class="row-fluid " id="comments"></textarea>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Website</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <input type="text" name="website" id="website" value="" placeholder="http://www.pure-chemical.com" /> 
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Credit Assesment <font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('credit_assesment', $optionscrd, '', 'id="credit_assesment"', 'name="credit_assesment"');
                                                                            echo form_error('credit_assesment');
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </td>   
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Branch<font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('branch', $optionslocuser, '', 'id="branch"', 'name="branch"');
                                                                            echo form_error('branch');
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">

                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Assigned To<font color="red"> *</font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <?php
                                                                            echo form_dropdown('assignedto', $optionsasto, '', 'id="assignedto"', 'name="assignedto"');
                                                                            echo form_error('assignedto');
                                                                            ?>   

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>

                                                                <td class="fieldLabel narrowWidthType">

                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">

                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th colspan="4" class="blockHeader buleboder-full">Address Details</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Contact Name</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_name">
                                                                            <input type="text" class="valid" name="contact_name" id="contact_name" value="" maxlength="40" placeholder="lead contact name"  /> 
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Contact Email</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_email">
                                                                            <input  type="text" name="email_id" id="email_id" value="" placeholder="contact email id"  size="25" />
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Contact Number</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_phone">
                                                                            <input type="text" name="phone" id="phone" value="" size="25"  placeholder="044-12345678" /> 
                                                                            <?php echo form_error('phone'); ?>           
                                                                        </span>
                                                                    </div>
                                                                </td>

                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Mobile </font></label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_mobile">
                                                                            <input  type="text" name="mobile" id="mobile" value=""  placeholder="+919840012345"  />
                                                                            <?php echo form_error('mobile'); ?>           
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
                                                                            <input type="text" placeholder="Country Name" id="country" name="country"/> 
                                                                            <?php echo form_error('country'); ?>     
                                                                        </span>
                                                                    </div>
                                                                </td>

                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">State</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_state">
                                                                            <input type="text" placeholder="State Name" id="state" name="state"/> 
                                                                            <?php echo form_error('state'); ?>        
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
                                                                            <input type="text" placeholder="City Name" id="city" name="city" /> 
                                                                            <?php echo form_error('city'); ?>    
                                                                        </span>
                                                                    </div>
                                                                </td>


                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Postal Box</label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_po">
                                                                            <input type="text" name="postalcode" id="postalcode" value="" size="25" /> 
                                                                            <?php echo form_error('postalcode'); ?>           
                                                                        </span>
                                                                    </div>
                                                                </td>


                                                            </tr>

                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Street Address </label>
                                                                </td>
                                                                <td class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10" id="trReadOnlyFields_street">
                                                                            <textarea rows="3" cols="40" data-maxsize="249" name="street" id="street"/></textarea>
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
                                                                            <input  type="text" name="fax" id="fax" maxlength="14" value="" placeholder="+9144-26161813" />
                                                                            <?php echo form_error('fax'); ?>           
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <th colspan="4" class="blockHeader buleboder-full">Description Details</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="fieldLabel narrowWidthType">
                                                                    <label class="muted pull-right marginRight10px">Description</label>
                                                                </td>
                                                                <td colspan="3" class="fieldValue narrowWidthType">
                                                                    <div class="row-fluid">
                                                                        <span class="span10">
                                                                            <textarea name="description" class="row-fluid " id="description"></textarea>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="floatRight">
                                                        <div class="pull-right">
                                                            <input class="submit" id="saveleads" name="saveleads" type="submit" value="Submit"  />
                                                            <a onclick="javascript:window.history.back();"><input class="reset" type="reset" class="cancelLink" value="Cancel"> </a>
                                                            <input type="hidden" id="hdn_refferer"  name="hdn_refferer" value="<?php echo $reffer_page; ?>">
                                                            <input type="hidden" id="hdn_saveleads"  name="hdn_saveleads">
                                                            <input type="hidden" id="hdn_grid_row_data" name="hdn_grid_row_data">
                                                        </div>
                                                    </div>
                                                    <!-- Select itemmaster popup start -->
                                                    <div id="win_selectItemMaster" style="width: 50%" >
                                                        <span id="validateProductName"></span>
                                                        <div style="margin: 10px">
                                                            <div id="jqxgrid_selectItemMaster" style="width: 400px;"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Select Itemmaster popup end -->
                                                    <div class="clearfix">
                                                    </div>

                                                </form> 
                                            </div>


                                        </div> <!-- end of contents div -->

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>
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
<script type="text/javascript">
                function loadproducts()
                {
                    var url = base_url + 'leads/selectproducts_all'
                    var rows = {};
                    jQuery.ajax({
                        dataType: "html",
                        url: url,
                        type: "POST",
                        async: false,
                        error: function (xhr, status) {
                        },
                        success: function (result) {
                            var obj = jQuery.parseJSON(result);
                            rows = obj.rows;
                        }
                    });

                    var item_source =
                            {
                                datatype: "json",
                                datafields: [
                                    {name: 'id', type: 'number'},
                                    {name: 'description', type: 'text'},
                                ],
                                id: 'id',
                                localdata: rows
                            };

                    var dataAdapterItemMaster = new $.jqx.dataAdapter(item_source);
                    $("#jqxgrid_selectItemMaster").jqxGrid(
                            {
                                width: '100%',
                                source: dataAdapterItemMaster,
                                theme: 'energyblue',
                                selectionmode: 'singlecell',
                                sortable: true,
                                pageable: true,
                                columnsresize: true,
                                sortable: true,
                                        showfilterrow: true,
                                filterable: true,
                                columns:
                                        [
                                            {text: 'Id', dataField: 'id', width: 100},
                                            {text: 'Product Name', dataField: 'description', width: 500, height: 600},
                                        ]
                            });



                }
</script>
<!--<?php $this->load->view('include_idletimeout.php'); ?> -->

</body>
</html>



