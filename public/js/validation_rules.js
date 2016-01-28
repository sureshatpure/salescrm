$(document).ready(function ()
{
    var leadsub = $('#leadsubstatus').val();
    var leadsubchange = [];
        leadsubchange[0] = 0;
    
    $('#leadsubstatus').change(function ()
        {
             leadsubchange = $('#leadsubstatus').val();
             
             leadsubchange = leadsubchange.split('-');
             
        }
    );
    $("#leadform").validate({
        errorElement: "span",
        //set the rules for the fild names
        rules: {
               'customFieldPoten[]': {  
                required:true,
                number: true,
                min: function (element)
                {
                    var array_1 = $('input[name="customFieldPoten[]"]').map(function() {return this.value}).get()
                    var max_val = Math.max.apply(Math,array_1); 
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 &&  leadsubchange[0]!=15))
                    {
                        if (max_val>0.001) 
                           {
                            return 0;
                           }
                           else
                           {
                            return 0.001
                           }
                    }   
                    else
                    {
                        return 0;    
                    }
                      
                }
            },

           'customFieldValue[]': {
                required: true,
                number: true,
                min:0
            },
            leadstatus: {
                required: true
            },
            leadsubstatus: {
                required: true
            },
            leadsource: {
                required: true
            },
            uploaded_date: {
                required: true
            },
            branch: {
                required: true
            },
            company: {
                required: true
            },
            industry: {
                required: true
            },
            country: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            },
            assignedto: {
                required: true
            },
            txtLeadsoc: {
                required: true
            },
            credit_assesment: {
                required: true
            },
            not_able_to_get_appointment: {
                required: true
            },
            sample_rejected_reason: {
                required: true
            },
            order_cancelled_reason: {
                required: true
            },
            content_appiontment_date: {
                required: true
            },
            closingcomments: {
                required: true
            },
            
            street: {
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 && leadsubchange[0]!=15 ))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            },
            firstname: {
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 && leadsubchange[0]!=15))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            },
            lastname: {
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 && leadsubchange[0]!=15))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            },
            contact_name: {
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 && leadsubchange[0]!=15))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            },
            email_id: {
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 2 && $('#leadstatus').val()!=8 && leadsubchange[0]!=15))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                },
                multiemail: true

            },
            lead_2pa_no:{
                required: function (element)
                {
                    if (($('#leadstatus').val() >= 3))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }

            

        }, // end of rules
        //set error messages
        messages: {
            leadstatus: {
                required: "Lead Status is required..",
            },
             leadsubstatus: {
                required: "Lead Sub Status is required..",
            },
            leadsource: {
                required: "Lead Source is required..",
            },
            uploaded_date: {
                required: "Actual Lead date is required..",
            },
            branch: {
                required: "Please Select the Branch",
            },
            credit_assesment: {
                required: "Please Select the Credit Assesment",
            },
            industry: {
                required: "Industry type is required..",
            },
            assignedto: {
                required: "Select the User to Assign",
            },
            company: "Select the Customer.",
            country: "Select the country",
            state: "Select the state",
            city: "Enter the City",
            not_able_to_get_appointment: "Please enter the reason",
            sample_rejected_reason:"Please enter sample rejected reason",
            order_cancelled_reason:"Please enter order cancelled reason",
            content_appiontment_date: "Select the Appiontment Date",
            closingcomments: "Please enter the comments for closing this lead",
            
            'customFieldValue[]': {
                required: "Please enter the Requirement",
            },
            street:
                    {
                        required: "Enter the Address details"
                    },
            firstname:
                    {
                        required: "Contact Person's First Name"
                    },
            lastname:
                    {
                        required: "Contact Person's Last Name"
                    },
            contact_name:
                    {
                        required: "Contact Person's Name"
                    },
                    
 /*           email_id:
                    {
                        required: "Enter the Email ID",
                        email: "Enter the valid Email id"
                    },*/
           email_id:
                    {
                        required: "Enter the Email ID",
                        multiemail: "Invalid email format: please use a comma to separate multiple email addresses."
                    },
                     
         lead_2pa_no:
                    {
                       required: "Please enter the 2PA Number"     
                    }
                    
           
        }, // end of messages
        

        //our custom error placement
        errorElement: "span",
                errorPlacement: function (error, element) {
                    error.appendTo(element.parent());
                }
         


    });



    $('.myClass').each(function () {
        $(this).rules('add', {
            required: true,
            //number: true,
            messages: {
                required: "Please Select at least one Product"

                        //,  number:  "your custom number message"
            }
        });
    });



});
