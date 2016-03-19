<?php $this->load->view('header_novalid'); ?>
<!-- jqwidgets scripts -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />

<style type="text/css">

            #header {
                text-align: center;
            }
            #wrapper {
                margin:0 auto;
                width:100%;
            }
            #submain {
                margin:0 auto;
                width:100%;
            }
            #sub-left {
                float:left;
                width:100%;
            }
            #sub-right {
                float:right;
                width:43%;
                text-align: right;
            }
            #jqxWidget_qnty .jqx-grid-cell {
                background: none repeat scroll 0 0 #fff;
                border-color: #c7c7c7;
                box-sizing: content-box;
                font-family: Verdana,Arial,sans-serif;
                font-size: 10px;
                font-style: normal;
            }
            #jqxWidget_cum_qnty .jqx-grid-cell {
                background: none repeat scroll 0 0 #fff;
                border-color: #c7c7c7;
                box-sizing: content-box;
                font-family: Verdana,Arial,sans-serif;
                font-size: 10px;
                font-style: normal;
            }
            #jqxWidget_qnty .jqx-grid-header{
                text-align: center;
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
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsreorder.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>


<!-- sorting and filtering - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.aggregates.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>

<!-- sorting and filtering - end -->

<!-- paging - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
<!-- paging - end -->

<!-- charts - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxchart.js"></script>
<!-- charts - end -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.export.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.export.js"></script>
<!-- End of jqwidgets -->
<!-- end of Menu includes -->
<script type="text/javascript">
    var base_url = '<?php echo site_url(); ?>';
    
    
    
    function openpopupwindow(leadstatusid, days)
    {
        //  alert("lead status id"+leadstatusid);
        //  alert("days"+days);
        window.open(base_url + 'dashboard/showleads/' + leadstatusid + '/' + days, '_blank', 'width=800,height=500,scrollbars=yes,status=yes,resizable=yes,screenx=300,screeny=100,addressbar=no');

    }
    $(document).ready(function () {
        
        var theme = "black";
        var hdnfrom_date = $('#hdn_from_date').val();
        var hdnto_date = $('#hdn_to_date').val();
        var hdnaccount_yr = $('#hdn_account_yr').val();
             var br = $('#hdn_branch').val();
       // alert("hdnaccount_yr"+hdnaccount_yr);
        var hdnjc_to = $('#hdn_jc_to').val();
            hdnjc_to =hdnjc_to-1;

        var hdn_jc_week = $('#hdn_jc_week').val();
            hdn_jc_week = hdn_jc_week-1;
        var hdnjc_from = $('#hdn_jc_from').val();
        hdnjc_from=hdnjc_from-1;

        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');
        $("#applyfilter").jqxButton({theme: 'energyblue'});

       
        //alert("date filter option is "+sel_datefilter);
        function convert(from_date)
        {
            var date = new Date(from_date), mnth = ("0" + (date.getMonth() + 1)).slice(-2), day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
            //alert([ date.getFullYear(), mnth, day ].join("-"));
        }

        var leadata =<?php echo $data; ?>;
        var baseurl = base_url;
        var permission = <?php echo $grpperm; ?>;
        var group_len = permission.length;
        var theme = 'energyblue';
        var data = leadata;

       // prepare the data
       


        // alert("summary data"+summaryData.toSource());
         var url = base_url + "dashboard/getbranchesAll";
            branch_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'branch'},
                            {name: 'branch'}
                        ],
                        url: url,
                        async: false
                    };

            var branch_dataAdapter = new $.jqx.dataAdapter(branch_source);
              $("#selectbranch").jqxDropDownList({
                selectedIndex: -1,
                source: branch_dataAdapter,
                displayMember: "branch",
                valueMember: "branch",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Please Select Branch –'
            });
            $("#selectbranch").jqxDropDownList('val', br);


            /*JC period start*/

            var url = base_url + "dashboard/getjchdrforweek/"+hdnaccount_yr;
            jc_source_from =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'jc_name'},
                            {name: 'jc_code'}
                        ],
                        url: url,
                        async: false
                    };
             var url = base_url + "dashboard/getjcweek_hdr/"+hdnaccount_yr+"/"+hdnjc_to;
            jc_week =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'line_id'},
                            {name: 'week_id'}
                        ],
                        url: url,
                        async: false
                    };                    

            var jc_dataAdapter = new $.jqx.dataAdapter(jc_source_from);
              $("#jcperiod_from").jqxDropDownList({
                selectedIndex: hdnjc_to,
                source: jc_dataAdapter,
                displayMember: "jc_name",
                valueMember: "jc_code",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select JC Period –'
            });

             var jc_dataAdapter = new $.jqx.dataAdapter(jc_week);
              $("#jcperiod_week").jqxDropDownList({
                selectedIndex: hdn_jc_week,
                source: jc_dataAdapter,
                displayMember: "jc_weekname",
                valueMember: "week_id",
                width: 200,
                autoDropDownHeight:true,
                theme: theme,
                placeHolder: '– Select JC Week –'
            });

            $("#jcperiod_from").on('select', function (event) {
                updateJcweekDate(event.args.item.value);
            });

            var updateJcweekDate = function (jccode) {
            //  alert('jc code  in updateJcweekDate'+jccode);
           
            }

            $("#jcperiod_week").on('select', function (event) {
                
                updateJcWeekdate(event.args.item.value);
            });

            var updateJcWeekdate = function (jcweek) {
                fin_year =  $("#finance_year").jqxDropDownList('val');
                jccode =  $("#jcperiod_from").jqxDropDownList('val');                
              //  alert("jc_week "+jcweek);
                $.ajax({
                    url: baseurl + "dashboard/getjcweekdates/" + fin_year+"/"+jccode+"/"+jcweek,
                    dataType: "json",
                    success: function (jc_week_dates) {
                       SetJcWeekdate(jc_week_dates);
                    }
                });
            }

        
            // Create a jqxDropDownList
           

             var url = base_url + "dashboard/getfinanceyear";
            financeyr_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'finance_year'},
                            {name: 'finance_year'}
                        ],
                        url: url,   
                        async: false
                    };

            var finyear_dataAdapter = new $.jqx.dataAdapter(financeyr_source);
              $("#finance_year").jqxDropDownList({
                selectedIndex: 1,
                source: finyear_dataAdapter,
                displayMember: "finance_year",
                valueMember: "finance_year",
                width: 200,
                autoDropDownHeight: true,
                theme: theme,
                placeHolder: '– Please Select Year –'
            });
            $("#finance_year").jqxDropDownList('val', hdnaccount_yr);
            function SetJcWeekdate(jc_week_dates){

               /*   alert("jc_period_from "+jc_week_dates[0].week_period_from);
                  alert("jc_period_from "+jc_week_dates[0].week_period_to);
*/
                }
          
             $("#finance_year").on('select', function (event) { 
                
                updateJcweekDatefy(event.args.item.value);
              //  updateJcToDatefy(event.args.item.value);
            });

             var updateJcweekDatefy = function (jccode) {
               alert('updateJcweekDatefy'+jccode);
            fin_year =  $("#finance_year").jqxDropDownList('val');
            jccode =  $("#jcperiod_from").jqxDropDownList('val');
            jcweek =  $("#jcperiod_week").jqxDropDownList('val');
           // alert('fin_year in updateJcweekDatefy'+fin_year);
           // alert('jccode in updateJcweekDatefy'+jccode);
          //  alert('jcweek in updateJcweekDatefy'+jcweek);
               /* $.ajax({
                    url: baseurl + "dashboard/getjcperiodfromdate/" + jccode+"/"+fin_year,
                    dataType: "json",
                    success: function (jcfrom_date) {
                       JcFromdate(jcfrom_date);
                    }
                });*/
           
            }

           

                
/*JC period end*/            



        $("#applyfilter").click(function () {
            dataFieldbranch = $("#selectbranch").jqxDropDownList('val');
            fin_year =  $("#finance_year").jqxDropDownList('val');
            jccode_from =  $("#jcperiod_from").jqxDropDownList('val');
            jccode_week =  $("#jcperiod_week").jqxDropDownList('val');

            if (dataFieldbranch.length == 0)
            {
                dataFieldbranch="All";
                
            }
            else
            {
                // applyFilter(dataField);
                setfilters(dataFieldbranch,jccode_from,jccode_week,fin_year);
            }

        });
        var setfilters = function (datafield, jccode_from,jccode_week,fin_year) {
            //alert("set setfilters - jccode  "+jccode_from);
            //  alert("setfilters - Jc week "+jccode_week);
            //  alert("fin_year  "+fin_year);
            //  alert("the branch "+datafield);

              if (datafield!== "")
            {

                $.ajax({
                    url: baseurl + 'dashboard/lead_quantity_withfilter/'+ datafield +'/'+ fin_year +'/' + jccode_from + '/' + jccode_week,
                    success: function () {
                        // i must remove the div
                        //  alert("success");
                        //   window.location.href=baseurl + 'dashboard/getdatawithfilter/'+ datafield+'/'+dataFielduser+'/'+from_date+'/'+to_date;
                        window.location.href = baseurl + 'dashboard/lead_quantity_withfilter/'+ datafield +'/'+ fin_year +'/' + jccode_from + '/' + jccode_week;

                    }
                });
            }

        } // End for setfilters function 

        // chart data preparation - end
             data = leadata;
            
            // prepare the data
            source =
                    {
                        datatype: "json",
                        sortcolumn: 'id',
                        sortdirection: 'asc',
                        datafields: [
                            {name: 'id'},
                            {name: 'user_branch'},
                            {name: 'prospects'},
                            {name: 'met_the_customer'},
                            {name: 'credit_sssessment'},
                            {name: 'sample_trails_formalities'},
                            {name: 'enquiry_offer_negotiation'},
                            {name: 'managing_and_implementation'},
                            {name: 'expanding_and_build_relationship'},
                            {name: 'total'}

                        ],
                        localdata: data,
                        pagenum: 0,
                        pagesize: 50,
                        pager: function (pagenum, pagesize, oldpagenum) {
                            // callback called when a page or page size is changed.
                        }
                    };


            // alert("summary data"+summaryData.toSource());

             var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
                                    var branch = this.owner.source.records[row]['user_branch'];
                                    var color='#e83636';

                                    var datarow = $("#jqxgrid").jqxGrid('getrowdata', row);
                                        if (datarow.user_branch === "WEIGHTAGE") 
                                        {
                                            return '<span style="margin: 6px; float:'+ columnproperties.cellsalign +';color: #0000ff;">' + value + '</span>';
                                        }
                                        return '<div style="margin: 3px 0 0 3px;">'+value+'</div>';
                                        

                                }

   
             $("#excelExport").click(function () {
                   $("#jqxgrid").jqxGrid('exportdata', 'xls', 'view_lead_overall_qnty');
                  //   dashboard/savefile');
                });
             $("#excelExport").jqxButton({
                 theme: 'energyblue'
                 });
         var dataAdapter = new $.jqx.dataAdapter(source);

            $("#jqxgrid").jqxGrid(
                    {
                        width: '100%',
                        source: dataAdapter,
                        theme: theme,
                        selectionmode: 'singlecell',
                        sortable: true,
                        pageable: true,
                        columnsresize: true,
                        altrows: false,
                        sortable: true,
                        showstatusbar: true,
                        statusbarheight: 50,
                         columnsheight: 45,
                        columns: [
                            {text: 'Branches', dataField: 'user_branch', width: 125, hidden: false, filterable: false,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Branches<br><b><font color="black">WEIGHTAGE %</font></b></div>';
                                    }},
                            {text: '0-Prospect', dataField: 'prospects', width: 85, cellsalign: 'center',
                                    renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">0-Prospect<br><b><font color="black">10 %</font></b></div>';
                                    }, cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div  class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: '1-Met The Customer', dataField: 'met_the_customer', width: 85, cellsalign: 'center',renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">1-Met The Customer<br><b><font color="black">20 %</font></b></div>';
                                    }, cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: 'Credit Assesment', dataField: 'credit_sssessment', width: 85, cellsalign: 'center',renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Credit Assesment<br><b><font color="black">30 %</font></b></div>';
                                    }, cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: 'Sample,Trails & Formalities', dataField: 'sample_trails_formalities', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Sample,Trails & Formalities<br><b><font color="black">50 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }



                            },
                            {text: 'Enquiry Offer Negotiation', dataField: 'enquiry_offer_negotiation', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Enquiry Offer Negotiation<br><b><font color="black">70 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: 'Managing And Implementation', dataField: 'managing_and_implementation', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Managing And Implementation<br><b><font color="black">80 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblack' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: 'Expanding And Building Relation', dataField: 'expanding_and_build_relationship', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Expanding And Building Relation<br><b><font color="black">100 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblue' style='float: left; width: 100%; height: 100%; '>";
                                    $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                        var name = key == 'sum' ? 'Σ' : 'sum';
                                        var color = key == 'sum' ? 'green' : 'red';
                                        renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: right; overflow: hidden;">' + name + ': ' + value + '</div>';
                                    });
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            },
                            {text: 'Total', dataField: 'total', width: 120, cellsalign: 'center', cellsrenderer:cellsrenderer,cellsformat: 'f2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element,row, columnfield) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblue' style='float: left; width: 100%; height: 100%; '>";
                                     
                                     var datarow =     $('#jqxgrid').jqxGrid('getrowdata', 0);
                                        
                                        {
                                           $.each(aggregates, function (key, value) {
                                        //  var name = key == 'min' ? 'Min' : 'Max';
                                        //  var color = key == 'max' ? 'green' : 'red';
                                            var name = key == 'sum' ? 'G-Σ' : 'sum';
                                            var color = key == 'sum' ? 'green' : 'red';
                                            renderstring += '<div style="color: ' + color + '; position: relative; margin: 6px; text-align: left; overflow: hidden;">' + name + ': ' + value + '</div>';
                                            });  
                                        }
                            
                                   
                                    renderstring += "</div>";
                                    return renderstring;
                                }
                            }
                        ],
                        showtoolbar: false,
                        autoheight: true
                       
                    });

$('#jqxgrid').jqxGrid('renderaggregates');


    });
</script>

<div class="announcement noprint" id="announcement">
    <marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onmouseover="javascript:stop();" onmouseout="javascript:start();">LBL_NO_ANNOUNCEMENTS</marquee>
</div>
<input value="Leads" id="module" name="module" type="hidden">
<input value="" id="parent" name="parent" type="hidden">
<input value="List" id="view" name="view" type="hidden">

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
            </div>
        </div>
        <div class="contentsDiv marginLeftZero" style="width:100%;">
            
                <div class="listViewTopMenuDiv noprint">
                    <div >
                         <span class="btn-toolbar span10">
                            <div class="contentHeader row-fluid" style="width:100%;">
                        
                                                  
                                <span title="Petro Chemicals" class="recordLabel font-x-x-large span12">
                                    <strong>Dashboard - Overall Leads Quantity for <? echo $branch;?> Branch</strong>
                                </span>
            

                            </div>
                            <span class="btn-group">
                                    <!-- <button id="Leads_listView_basicAction_LBL_ADD_RECORD" class="btn addButton" onclick='window.location.href="leads/add"'><i class="icon-plus icon-white"></i>&nbsp;<strong>Add Lead</strong></button> -->
                                <!-- <div>Total No Of Leads: </div> -->
                                        <?php if ($this->session->flashdata('message') != "") { ?>
                                    <div class="alert alert-message.success"><p style="width:709px; text-align:center;font-size:18px;"><?php echo $this->session->flashdata('message'); ?></p></div>
                                        <?php } ?>
                            </span>
                        </span>
                        <span class="hide filterActionImages pull-right">
                            <i title="Deny" data-value="deny" class="icon-ban-circle alignMiddle denyFilter filterActionImage pull-right"></i><i title="Approve" data-value="approve" class="icon-ok alignMiddle approveFilter filterActionImage pull-right"></i><i title="Delete" data-value="delete" class="icon-trash alignMiddle deleteFilter filterActionImage pull-right"></i><i title="Edit" data-value="edit" class="icon-pencil alignMiddle editFilter filterActionImage pull-right">
                            </i>
                        </span>

                    </div>
                </div>
                <div class="listViewContentDiv" id="listViewContents" style="float: left; width:100%;">
                    <!-- Start your grid content from here -->  
                       <div>
                        <table width="540px">
                            <tr>
                                <td width="25%" style="padding-left:8px;"><div>Select the Fin-Year:</div></td>
                                <td><div style="float: left" id="finance_year"></div></td>
                            </tr>   
                            <tr>
                                <td width="25%" style="padding-left:8px;"><div>JC Period :</div></td>
                                <td><div style="float: left" id="jcperiod_from"></div></td>
                            </tr>   
                            <tr>
                                <td width="25%" style="padding-left:8px;"><div>JC Week:</div></td>
                                <td><div style="float: left" id="jcperiod_week"></div></td>
                            </tr>  
                            <tr>
                                <td width="25%" style="padding-left:8px;"><div>Select the Branch:</div></td>
                                <td><div style="float: left" id="selectbranch"></div></td>
                            </tr>   

                            <tr>
                                <td></td><td><input type="button" id="applyfilter" value="Search" /></td>
                            </tr>
                           
                        </table>
                    </div>
                    <div id="wrapper">
                        <div id="header"><h1></h1></div>
                        <div id="sub-main">
                            <div id="sub-left">
                                        <div id='jqxWidget'>
                                        <input style='margin-top: 10px;margin-left:733px;' title="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option"   alt="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option" type="button" value="Export to Excel" id='excelExport' />
                                            <div id="jqxgrid"></div>
                                        </div>
                            </div>
                           
                        </div>
                        <div style="clear:both;"></div>
                          <div id="sub-main">
                        
                            <div id="sub-left">
                                <div id='jqxWidget_cum'>
                                    <div id="jqxgrid_cum" ></div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div style="width:100%; float:left;">
                     
                        <div style="width:100%;"></div>
                      
                        <!-- grid for qnty end -->
                <!-- End of Grid content -->                        

                </div>

    </div>
</div>
</div>
</div>
<input id="activityReminder" class="hide noprint" value="60" type="hidden">
<input value="<?php echo $branch; ?>" id="hdn_branch" name="hdn_branch" type="hidden">
<input value="<?php echo @$from_date; ?>" id="hdn_from_date" name="hdn_from_date" type="hidden">
<input value="<?php echo @$to_date; ?>" id="hdn_to_date" name="hdn_to_date" type="hidden">
<input value="<?php echo @$jc_to; ?>" id="hdn_jc_to" name="hdn_jc_to" type="hidden">
<input value="<?php echo @$jc_week; ?>" id="hdn_jc_week" name="hdn_jc_week" type="hidden">
<input value="<?php echo @$jc_from; ?>" id="hdn_jc_from" name="hdn_jc_from" type="hidden">
<input value="<?php echo @$account_yr; ?>" id="hdn_account_yr" name="hdn_account_yr" type="hidden">
<div id="userfeedback" class="feedback noprint">

</div>
<footer class="noprint">
    <p style="margin-top:5px;margin-bottom:0;" align="center">Powered by Pure CRM 6.0.0Beta©2013 - 2018&nbsp;
        <a href="www.pure-chemical.com" target="_blank">pure-chemical.com
        </a>&nbsp;|&nbsp;
    </p>
</footer>
<script type="text/javascript" src="<?= base_url() ?>public/js/html5.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-tab.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/bootstrap-dropdown.js"></script>


<!-- Added in the end since it should be after less file loaded -->
<?php $this->load->view('include_idletimeout.php'); ?> 
</body>
</html>