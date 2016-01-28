$(document).ready(function() 
{
              
    $("#leadform").validate({

            errorElement: "span", 
            //set the rules for the fild names
            rules: {
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
                branch:{ 
                    required: true
                },

                company: {
                    required: true
                },
                industry: {
                    required: true
                },
                        
               country : {
                    required :true
                },
                state : {
                    required :true  
                },
                city: {
                    required :true
                },
                assignedto: {
                    required :true
                },
                txtLeadsoc: {
                    required :true
                },
                 credit_assesment: {
                    required :true
                }
               /* ,
                 producttype: {
                    required :true
                },
                 exportdomestic: {
                    required :true
                },
                  purchasedecision: {
                    required :true
                },
                  presentsource: {
                    required :true
                },
                 txtDomesticSource: {
                    required :true
                }
                */

								
            },
     
            //set error messages
            messages: {
     
                leadstatus:{ 
                    required: "Lead Status is required..",
                },
                leadsubstatus:{ 
                    required: "Lead Sub Status is required..",
                },

                leadsource:{ 
                    required: "Lead Source is required..",
                },
                uploaded_date:{ 
                    required: "Uploaded Date is required..",
                },
                branch:{ 
                    required: "Please Select the Branch",
                },
                 credit_assesment:{ 
                    required: "Please Select the Credit Assesment",
                },
							
                industry:{ 
                    required: "Industry type is required..",
                },
                assignedto:{ 
                    required: "Select the User to Assign",
                },
                company: "Select the Customer.",
                country: "Select the country",
                state: "Select the state",
         /*       producttype: "Please Enter the End Product name",
                exportdomestic: "Please select the Product Sale Type",
                purchasedecision: "Please Enter the Decistion Maker for Purchase",
                presentsource:  "Please select the Present Procurement",
*/
               
            },
     
            //our custom error placement
            errorElement: "span",
            errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                }


   });



  $('.myClass').each(function() {
        $(this).rules('add', {
            required: true,
            //number: true,
            messages: {
                required:  "Please Select at least one Product"
                
              //,  number:  "your custom number message"
            }
        });
    });
    
      

});
