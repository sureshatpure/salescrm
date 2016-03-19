<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta name="msapplication-tap-highlight" content="no" />
    <title id='Description'>Customer Info- Mobile Example</title>
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/demo.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.windowsphone.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.blackberry.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.android.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.mobile.css" type="text/css" />
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/simulator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxexpander.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmaskedinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script>
    <style type="text/css">
        .register-table td,
        .register-table tr {
            margin: 0px;
            padding: 2px;
            border-spacing: 0px;
            border-collapse: collapse;
            font-size: 16px;
            white-space: nowrap;
        }
        .register-table {
            width: 100%;
            height: 100%;
        }
        .terms .jqx-validator-error-label {
            text-align: center;
        }
        .jqx-validator-error-label {
           font-size: 14px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            var theme = prepareSimulator("validator");
            $("#register").jqxExpander({ theme: theme, toggleMode: 'none', height: '100%', width: '100%', showArrow: false });
            $('#sendButton').jqxButton({ theme: theme, width: '50%' });
            $('#acceptInput').jqxCheckBox({ theme: theme, boxSize: 19, width: '50%' });

            $('#sendButton').on('click', function () {
                $('#testForm').jqxValidator('validate');
            });

            $('.text-input').jqxInput({ theme: theme, width: '100%', height: 30 });
            // initialize validator.
            $('#testForm').jqxValidator({
                hintType: 'label',
                animationDuration: 0,
               /* rules: [
                       { input: '#userInput', message: 'Username is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#userInput', message: 'Between 3 and 12 characters are required!', action: 'keyup, blur', rule: 'length=3,12' },
                       { input: '#passwordInput', message: 'Password is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#passwordInput', message: 'Between 4 and 12 characters are required!', action: 'keyup, blur', rule: 'length=4,12' },
                       { input: '#passwordConfirmInput', message: 'Password is required!', action: 'keyup, blur', rule: 'required' },
                       {
                           input: '#passwordConfirmInput', message: 'Passwords doesn\'t match!', action: 'keyup, focus', rule: function (input, commit) {
                               // call commit with false, when you are doing server validation and you want to display a validation error on this field. 
                               if (input.val() === $('#passwordInput').val()) {
                                   return true;
                               }
                               return false;
                           }
                       },
                       { input: '#emailInput', message: 'E-mail is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#emailInput', message: 'Invalid e-mail!', action: 'keyup', rule: 'email' },
                       { input: '#acceptInput', message: 'You have to accept the terms', action: 'change', rule: 'required', position: 'center' }]*/
                       rules: [
                       { input: '#supplier_name', message: 'Supplier name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#product_name', message: 'Product Name is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#purchase_price', message: 'Purchase price is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#back_to_backorder', message: 'Back to Back order is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#other_remarks', message: 'Other Remarks is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#internation_sp', message: 'Selling price is required!', action: 'keyup, blur', rule: 'required' },
                       { input: '#acceptInput', message: 'You have to accept the terms', action: 'change', rule: 'required', position: 'center' }

                ]
            });
            initSimulator("validator");
        });
    </script>
</head>
<body class='default'>
    <div id="demoContainer" class="device-mobile">
        <div id="container" class="device-mobile-container">
            <div id="register">
                <div>
                    Customer Info
                </div>
                <div style="overflow: hidden;">
                    <form id="testForm" action="./">
                        <table class="register-table">
                            <tr>
                                <td>Supplier Name:</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="supplier_name" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td>Product Name:</td>

                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="product_name" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td>Purchase Price:</td>

                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="purchase_price" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td>Back-2-Back Order:</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="back_to_backorder" placeholder="Order" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td>Other Remarks:</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="other_remarks" placeholder="Order" class="text-input" /></td>
                            </tr>
                             <tr>
                                <td>Internation Selling Price:</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="internation_sp" placeholder="Order" class="text-input" /></td>
                            </tr>
                            <tr>
                                <td class="terms" colspan="2" style="text-align: center; padding: 5px;">
                                    <div id="acceptInput" style="margin-top: 30px; left: -65px; position: relative; margin-left: 50%;" >I accept terms</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="button" value="Send" id="sendButton" /></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
