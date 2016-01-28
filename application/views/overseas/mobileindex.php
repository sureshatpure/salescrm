<!DOCTYPE html>
<html>
<head>
    <title id="Description">Responsive Grid layout built with Bootstrap and jQWidgets</title>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url() ?>public/jqwidgets/styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>public/jqwidgets/styles/bootstrap-theme.min.css" rel="stylesheet" />
    <!-- jQWidgets CSS -->
    <link href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" rel="stylesheet">
    <link href="<?= base_url() ?>public/jqwidgets/styles/jqx.bootstrap.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxexpander.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmaskedinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcombobox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script>


    <style type="text/css">
        body, html {
            height: 100%;
            padding: 0px;
            margin: 0px;
            width: 100%;
            border: none;
            overflow: hidden;
        }

        .required {
            vertical-align: baseline;
            color: red;
            font-size: 10px;
        }

        .control-label {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <script type="text/javascript">
           $(document).ready(function () {
            // set jQWidgets Theme to "Bootstrap"
            $.jqx.theme = "bootstrap";

            $('#sendButton').jqxButton({ height: 25 });
            // create jqxInput widget.

            $('input').jqxInput({ height: 22, width: '100%' });
            $('input').css('margin-top', '5px');
            

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

            $("#myTab").tab();
            $('.pane:nth-child(2)').hide();
            $('.pane:nth-child(3)').hide();

        });
    </script>
    <div style="min-height: 40px; box-shadow: none; -webkit-box-shadow: none;" class="navbar navbar-default navbar-fixed-top" role="navigation">
        <ul id="myTab" style="min-width: 480px; box-shadow: none; -webkit-box-shadow: none; border: none;" class="nav nav-tabs">
            <li style="margin-left: 20px;" class="active"><a data-tab="billing" href="#billing">Customer Information</a></li>
 
        </ul>
    </div>
    <div style="padding-top: 40px; width: 100%; height: 100%;">
        <div id="billing" class="pane" style="width: 100%; height: 100%; overflow-x: hidden; border: none;">
            <form class="navbar-form" id="customer_info" name="customer_info" method="post" action="savecustomerinfo" >
                <div class="form-horizontal">
                    <h2>Enter your Customer Information</h2>
                </div>
                <div class="form-horizontal col-sm-6">
                    <div>
                        <label class="col-sm-4 control-label" for="supplier_name">Supplier Name</label>
                        <div class="col-sm-8">
                            <input placeholder="Supplier Name" id="supplier_name" name="supplier_name" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="product_name">Product Name</label>
                        <div class="col-sm-8">
                            <input placeholder="Product Name" name="product_name" id="product_name" value="" />
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="purchase_price">Purchase Price</label>
                        <div class="col-sm-8">
                            <input placeholder="Purchase Price" id="purchase_price" name="purchase_price" value="" />
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
                            <input  placeholder="Other Remarks" name="other_remarks" id="other_remarks"/>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-4 control-label" for="internation_sp">International selling price</label>
                        <div class="col-sm-8">
                            <input name="billingCity" id="internation_sp" name="internation_sp" placeholder="International selling price" />
                        </div>
                    </div>
                           <div class="col-sm-4">
                            <button style="margin-top: 15px;" id="sendButton" type="button">Submit</button>
                    </div>
     
                </div>

            </form>
        </div>
    </div>
</body>
</html>
