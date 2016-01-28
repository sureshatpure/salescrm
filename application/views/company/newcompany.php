<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" " http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd ">
<html xmlns=" http://www.w3.org/1999/xhtml ">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head>

        <meta charset="utf-8">
            <title>Leads - Add New Customer</title>
            <link href="<?= base_url() ?>public/css/styles.css" rel="stylesheet" type="text/css">
            <script type="text/javascript" src="<?= base_url() ?>public/jquery/jquery-1.11.1.min.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>public/jquery/jqBootstrapValidation.js"></script>
        
                <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery-ui.min.css" type="text/css"/>
                <script type="text/javascript" src="<?= base_url() ?>public/jquery/jquery-ui.min.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jquery/bootstrap.min.js"></script>
                <script src="<?= base_url() ?>public/js/jquery.validate.min.js"></script>

                <script src="<?= base_url() ?>public/js/additional-methods.js"></script>
                <script src="<?= base_url() ?>public/js/validation_rules.js"></script>
                <script type="text/javascript">
                    var response_msg;

                    $(document).ready(function ()
                    {
                        $('#savecustomer').hide();
                         $( "#savecustomer" ).click(function() {
                              $("#newcompany").submit();
                            });
                        $("#newcompany").validate({
                            errorElement: "span",
                            //set the rules for the fild names
                            rules: {
                                companyname: {
                                    required: true
                                },
                                cust_country: {
                                    required: true
                                },
                                 cust_state: {
                                    required: true
                                },
                                 cust_cityname: {
                                    required: true
                                },
                                cust_address1: {
                                    required: true
                                }
                            },
                            //set error messages
                            messages: {
                                companyname: {
                                    required: "Please Enter the Customer Name"
                                },
                                cust_country: {
                                    required: "Please Enter country"
                                },
                                cust_state: {
                                    required: "Please Enter the Statename"
                                },
                                cust_cityname: {
                                    required: "Please Enter the CityName"
                                },
                                cust_address1: {
                                    required: "Please Enter the Address1"
                                }

                           },
                            //our custom error placement
                            errorElement: "span",
                                    errorPlacement: function (error, element) {
                                        error.appendTo(element.parent());
                                    }
                                    
   /*                                 ,submitHandler: function(form) 
                                    {
                                            alert('valid form');  // for demo
                                            $('form').find(":submit").attr("disabled", true).attr("value","Submitting...");
                                            alert("hie");
                                            // form.submit();  // for demo
                                            return false;      // for demo
                                    }
*/


                        });

                        var validateCompanyname = $('#validateCompanyname');
                        /*$('#companyname').change(function () {
                            var companyname = this;
                            if (this.value != this.lastValue) {
                                if (this.timer)
                                    clearTimeout(this.timer);
                                validateCompanyname.removeClass('error').html('<img src="../public/images/ajax-loader.gif" height="16" width="16" /> checking availability...');

                                    this.timer = setTimeout(function () {
                                    $.ajax({
                                        url: '../company/check_customername',
                                        data: 'action=check_companyname&company_name=' + companyname.value,
                                        dataType: 'json',
                                        type: 'post',
                                        success: function (response)
                                        {

                                            response_msg = response.ok;
                                            validateCompanyname.html(response.msg);
                                            if (response_msg)
                                            {
                                                $('#savecustomer').show();
                                            }
                                            else if (!response_msg)
                                            {
                                                $('#savecustomer').hide();
                                            }
                                            else if (response_msg == 'undefined')
                                            {
                                                $('#savecustomer').hide();
                                            }
                                        }
                                    });
                                }, 200);

                                this.lastValue = this.value;
                            }
                        });*/
                        $('#companyname').autocomplete({
                                    source: function( request, response ) {
                                        $.ajax({
                                            url : '../company/getautocompany',
                                            dataType: "json",
                                            method: 'post',
                                            data: {
                                                name_startsWith: request.term,
                                                type: 'customerName'
                                            },
                                            success: function( data ) {
                                                response( $.map( data, function( item ) {
                                                    var code = item.split("|");
                                                        //$('#hdn_customer_id').val(code[0]);
                                                         $('#hdn_customer_id').val(code[0]);
                                                        return {
                                                            label: code[1],
                                                            value: code[1],
                                                            data : item
                                                        }
                                                    }));
                                                }

                                            });
                                    },
                                    autoFocus: true,            
                                    minLength: 2,
                                    select: function( event, ui ) {
                                        $('#myButton').show();
                                        var names = ui.item.data.split("|");
                                        $(this).val(names[1]);
                                       // alert(" in autocomplete "+names[0]);
                                     //   getClientAddress(names[0]);
                                    },
                                    change:function(event, ui ){
                                            if(ui.item)
                                            {

                                                $('#validateCompanyname').empty().append("<font color='red'>customer already exists</font>");
                                                $('#savecustomer').hide();                                               
                                            }
                                            else
                                            {
                                               $('#validateCompanyname').empty().append("<font color='green'>Yes.! You can add this customer</font>");  
                                               $('#savecustomer').show();
                                            }

                                         }       
                                              
                                });


                    });
                </script>

                </head>
                <body>
                    <div class="floatRight">
                        <span><h1>Creating New Customer</h1></span>
                       
                    </div>
                  
                    <div id="container">
                         
                        <?php
                        $attributes = array('id' => 'newcompany', 'name' => 'newcompany');
                        echo form_open('company/savenewcompany', $attributes);
                        ?>
                         <table>
                            <tr>
                                <td><label for="companyname">Customer Name<font color="red"> *</font></label></td>
                                <td>:</td>
                                <td>
                                    
                                     <input type="text" placeholder="Customer Name" id="companyname" name="companyname" class="form-control ui-autocomplete-input" value="" autocomplete="off">
                                    <span id="validateCompanyname">
                                    </span>
                                    <?php echo form_error('companyname'); ?> 
                                </td>

                            </tr>
                            <tr>
                                <td><label for="contact_name">Contact Person Name</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="contact_name" id="contact_name" value="" size="25" /> 
                                    <span id="validateContactname">
                                    </span>
                                    <?php echo form_error('contact_name'); ?> 
                                </td>
                            </tr>
                            <tr>
                                <td><label for="contact_number">Contact Number</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="contact_number" id="contact_number" value="" size="25" /> 
                                    <span id="validateContactnumber">
                                    </span>
                                    <?php echo form_error('contact_number'); ?> 
                                </td>
                            </tr>
                            <tr>
                                <td><label for="email_id">Email-Id</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="email_id" id="email_id" value="" size="25" /> 
                                    <span id="validateEmail">
                                    </span>
                                    <?php echo form_error('email_id'); ?> 
                                </td>
                            </tr>
                           <!--  <tr>
                                <td><label for="mobile_no">Moblie No</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="mobile_no" id="mobile_no" value="" size="25" /> 
                                    <span id="validateMobileno">
                                    </span>
                                    <?php echo form_error('mobile_no'); ?> 
                                </td>
                            </tr>
                            <tr>
                                <td><label for="fax">Fax</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="fax" id="fax" value="" size="25" /> 
                                    <span id="validateFax">
                                    </span>
                                    <?php echo form_error('fax'); ?> 
                                </td>
                            </tr> -->
                             <tr>
                                <td><label for="cust_address1">Address-1<font color="red"> *</font></label></td>
                                <td>:</td>
                                <td> 
                                    <textarea name="cust_address1" class="row-fluid " id="cust_address1"></textarea>
                                    <span id="cust_address1">
                                    <?php echo form_error('cust_address1'); ?> 
                                    </span> 
                                </td>
                            </tr>
                             <tr>
                                <td>  <label for="cust_address2">Address-2</label></td>
                                <td>:</td>
                                <td>
                                    <textarea name="cust_address2" class="row-fluid " id="cust_address2"></textarea>
                                    <span id="cust_address2">
                                    <?php echo form_error('cust_address2'); ?> 
                                    </span>
                                </td>
                            </tr>
                             <tr>
                                <td> <label for="cust_postal">Postal Code</label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="cust_postal" id="cust_postal" value="" size="25" />
                                    <span id="cust_postal">
                                    <?php echo form_error('cust_postal'); ?>  
                                    </span>
                                </td>
                            </tr>
                             <tr>
                                <td><label for="cust_cityname">CityName<font color="red"> *</font></label></td>
                                <td>:</td>
                                <td>
                                     <input type="text" name="cust_cityname" id="cust_cityname" value="" size="25" />
                                    <span id="cust_cityname">
                                    <?php echo form_error('cust_cityname'); ?>  
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td> <label for="cust_state">Statename<font color="red"> *</font></label></td>
                                <td>:</td>
                                <td>
                                    <input type="text" name="cust_state" id="cust_state" value="" size="25" />
                                    <span id="cust_state">
                                    <?php echo form_error('cust_state'); ?>
                                    </span>
                                </td>
                            </tr>
                             <tr>
                                <td> <label for="cust_country">Select country<font color="red"> *</font></label></td>
                                <td>:</td>
                                <td>
                                       <?php
                                        echo form_dropdown('cust_country', $optionscnt, 'IN', 'id="cust_country"', 'name="cust_country"');
                                        ?>  
                                         <span id="cust_country">
                                         <?php echo form_error('cust_country'); ?>     
                                        </span>   
                                </td>
                            </tr>
                           
                            <tr>
                                <td> </td>
                                <td></td>
                                <td>
                                       <input class="submit" id="savecustomer" name="savecustomer" type="button" value="Submit" />
                                       <input type="hidden" id="hdn_userid" name="hdn_userid" value="<?php echo $this->session->userdata['user_id']; ?>"/>  
                                       <input type="hidden" id="hdn_customer_id" name="hdn_customer_id">
                                       <input type="hidden" id="savecustomer" name="savecustomer"  value="savecustomer" />
                                </td>
                            </tr>
                        </table>

                        </form>

                        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
                    </div>

                </body>
                </htm>

