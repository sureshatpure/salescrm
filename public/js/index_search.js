$(document).ready(function ()
{
             //var st = $('#status').val();  alert("selected status "+st);
        var result=0;
        var date_result=0;
        $('#btn_search').bind('click', function () 
        {


            var check = $('#leadsrch_form').jqxValidator('validate');
              //  alert("check value "+check);

                if (check == true)
                {
                  //  alert("Can submit the form");
                    $('#leadsrch_form').submit();
                }
                else
                {
                 //   alert("Cannot submit the form");
                }
        });     

        $('#leadsrch_form').jqxValidator({
            rules:[
                     {
                         input: '#selectuser',
                         message: 'Please Select any one of the search options',
                         action: 'blur',
                         /*rule: function (input, commit) 
                                {
                                    var index = $("#status").jqxDropDownList('getSelectedIndex');
                                    alert("index "+index);
                                    return index != -1;
                                   
                                }*/
                        rule: function () 
                        { 
                           var status_val = $("#status").jqxDropDownList('getSelectedIndex');
                          //  alert(" status_val "+status_val);
                           var substatus_val = $("#substatus").jqxDropDownList('getSelectedIndex');
                         //  alert(" substatus_val "+substatus_val);
                           var selectbranch_val = $("#selectbranch").jqxDropDownList('getSelectedIndex');
                        //   alert(" selectbranch_val "+selectbranch_val);
                           var assigntouser_val = $("#assigntouser").jqxDropDownList('getSelectedIndex');
                         //  alert(" assigntouser_val "+assigntouser_val);
                          var selectuser_val = $("#selectuser").jqxDropDownList('getSelectedIndex');
                         //  alert(" selectuser_val "+selectuser_val);
                           var product_val = $("#customer").jqxDropDownList('getSelectedIndex');
                        //   alert(" product_val "+product_val);
                           var customer_val = $("#product").jqxDropDownList('getSelectedIndex');
                        //   alert(" customer_val "+customer_val);
                          var fromdate_val =  $('#fromdate').jqxDateTimeInput('val');
                        //      alert(" fromdate_val "+fromdate_val.length);
                          var todate_val =  $('#todate').jqxDateTimeInput('val');
                        //      alert(" todate_val "+todate_val.length);
                        // var sel_date = $('#date_filter').jqxCheckBox('checked')
                         //   alert(" sel_date "+sel_date);

                          

                              
                               if (fromdate_val.length>0 && todate_val.length==0)
                                    { 
                                      alert("Please select the To Date");
                                      date_result=0;
                                    }
                              if ( (fromdate_val.length>0) && (todate_val.length>0))
                                    {

                                      date_result=1;
                                      
                                    }
                           
                           

                            
                           if (status_val < 0 && substatus_val < 0 && selectbranch_val  < 0 && selectuser_val < 0 && assigntouser_val < 0 && product_val < 0 && customer_val < 0 && date_result == 0 )
                              {
                                result=0; 
                              } 
                            else   
                              {
                                result=1;
                              }
                          //  alert("result "+result);    
                           return result; 

                       },
                       position:'topleft:214,3'
                     }
                  ]
        });

});
