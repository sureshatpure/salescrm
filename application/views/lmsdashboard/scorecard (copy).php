<?php $this->load->view('header_novalid'); ?>
<!-- jqwidgets scripts -->
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets412/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets412/styles/jqx.energyblue.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets412/styles/jqx.black.css" type="text/css" />

<style type="text/css">

          

        .chart-inner-text
        {
            fill: #00BAFF;
            color: #00BAFF;
            font-size: 30px;
            font-family: Verdana;
        }    

            

</style>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>

    <!-- <script type="text/javascript" src="../../scripts/jquery-1.11.1.min.js"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxdraw.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxchart.core.js"></script>


    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxcalendar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/globalization/globalize.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxdata.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxmenu.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxlistbox.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.aggregates.js"></script> 

    <!-- paging - start -->
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxdata.export.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets412/jqxgrid.export.js"></script>
<!-- paging - end -->

<!-- End of jqwidgets -->
<!-- end of Menu includes -->
<script type="text/javascript">
    var base_url = '<?php echo site_url(); ?>';
      
    $(document).ready(function () 
    {

        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');
        var theme = 'energyblue';
        var zn = $('#hdn_zone').val();
        var cl = $('#hdn_collector').val();
        var mc = $('#hdn_marketcircle').val();
        var item = $('#hdn_itemgroup').val();

        var hdnfrom_date = $('#hdn_from_date').val();
        var hdnto_date = $('#hdn_to_date').val();
        var hdnaccount_yr = $('#hdn_account_yr').val();
        var br = $('#hdn_branch').val();


        var hdn_jc_week = $('#hdn_jc_week').val();
        
        var hdnjc_period = $('#hdn_jc_period').val();

        var sel_index_for_jc_period =hdnjc_period -1;
        var sel_index_for_jc_week =hdn_jc_week -1;
       // alert("sel_index_for_jc_week "+sel_index_for_jc_week);
       // alert("hdnjc_period "+hdnjc_period);
        var jc_week_line_id;


        
        var leadata =<?php echo $data; ?>;
        var lmsscorecard =<?php echo $data_sc; ?>;
        var lmsscorecard_chart =<?php echo $arr_sc_chart; ?>;
        var baseurl = base_url;


        $("#fromdate").jqxDateTimeInput({width: '150px', height: '25px', theme: 'energyblue', formatString: 'dd-MMM-yyyy', disabled: true});
        $("#todate").jqxDateTimeInput({width: '150px', height: '25px', theme: 'energyblue', formatString: 'dd-MMM-yyyy', disabled: true});

        $("#fromdate").jqxDateTimeInput('setDate', hdnfrom_date);
        $("#todate").jqxDateTimeInput('setDate', hdnto_date);
        



        

        from_date = $("#fromdate").jqxDateTimeInput('getDate');
        from_date = convert(from_date);
          

        to_date = $("#todate").jqxDateTimeInput('getDate');
        to_date = convert(to_date);


        //$("#fromdate").jqxDateTimeInput({disabled: false});
       // $("#todate").jqxDateTimeInput({disabled: false});
        sel_datefilter = $('#date_filter').val();
        //alert("date filter option is "+sel_datefilter);
        function convert(from_date)
        {
            var date = new Date(from_date), mnth = ("0" + (date.getMonth() + 1)).slice(-2), day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
            //alert([ date.getFullYear(), mnth, day ].join("-"));
        }

        $('#fromdate').on('valuechanged', function (event) {
            from_date = $("#fromdate").jqxDateTimeInput('getDate');
            //   alert("from date in "+from_date);
            from_date = convert(from_date);

        });
        $('#todate').on('valuechanged', function (event) {
            to_date = $("#todate").jqxDateTimeInput('getDate');
            //   alert("todate in "+to_date);
            to_date = convert(to_date);

        });            



         function displayzone()
         {
             // alert("summary data"+summaryData.toSource());
            var url = base_url + "lmsscorecard/getzone";
            zone_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'zone'},
                            {name: 'zone'}
                        ],
                        url: url,
                        async: false
                    };

            var zone_dataAdapter = new $.jqx.dataAdapter(zone_source);
            var number = zone_source.length;
            var auto;
                if (number > 5) {
                    auto = false;
                } else {
                    auto = true;
                };
           
              $("#zone").jqxDropDownList({
                selectedIndex: -1,
                source: zone_dataAdapter,
                displayMember: "zone",
                valueMember: "zone",
                width: 100,
                dropDownHeight: 10, 
                autoDropDownHeight: auto,
                theme: theme,
                placeHolder: '– Zone –'
            });
            $("#zone").jqxDropDownList('val', zn);
         }

         function displaycollector()
         {
             // alert("summary data"+summaryData.toSource());
            var url = base_url + "lmsscorecard/getcollectors";
            collector_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'collectors'},
                            {name: 'collectors'}
                        ],
                        url: url,
                        async: false
                    };

            var collector_dataAdapter = new $.jqx.dataAdapter(collector_source);
              $("#collector").jqxDropDownList({
                selectedIndex: -1,
                source: collector_dataAdapter,
                displayMember: "collectors",
                valueMember: "collectors",
                width: 100,
                height: 25,
                theme: theme,
                placeHolder: '– Select collector –'
            });
            $("#collector").jqxDropDownList('val', cl);
         }

         function displaymarketcircle()
         {
             // alert("summary data"+summaryData.toSource());
            var url = base_url + "lmsscorecard/getmarketcircles";
            mc_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'market_circle'},
                            {name: 'market_circle'}
                        ],
                        url: url,
                        async: false
                    };

            var mc_dataAdapter = new $.jqx.dataAdapter(mc_source);
              $("#market_circle").jqxDropDownList({
                selectedIndex: -1,
                source: mc_dataAdapter,
                displayMember: "market_circle",
                valueMember: "market_circle",
                width: 100,
                height: 25,
                theme: theme,
                placeHolder: '– Select MC –'
            });
            $("#market_circle").jqxDropDownList('val', mc);
         }

         function displayproducts()
         {
             // alert("summary data"+summaryData.toSource());
            var url = base_url + "lmsscorecard/getproductgroups";
            product_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'product_group'},
                            {name: 'product_group'}
                        ],
                        url: url,
                        async: false
                    };

            var product_dataAdapter = new $.jqx.dataAdapter(product_source);
              $("#itemgroup").jqxDropDownList({
                selectedIndex: -1,
                source: product_dataAdapter,
                displayMember: "product_group",
                valueMember: "product_group",
                width: 100,
                height: 25,
                theme: theme,
                placeHolder: '– Product –'
            });
            $("#itemgroup").jqxDropDownList('val', item);
         }
        

        function  displayLeadCounts()
        {
            //data = leadata;
        // prepare the data
            source =
                    {
                        datatype: "json",
                        sortcolumn: 'id',
                        sortdirection: 'asc',
                        datafields: [
                            {name: 'id'},
                            {name: 'collector'},
                            {name: 'prospects'},
                            {name: 'met_the_customer'},
                            {name: 'credit_sssessment'},
                            {name: 'sample_trails_formalities'},
                            {name: 'enquiry_offer_negotiation'},
                            {name: 'managing_and_implementation'},
                            {name: 'expanding_and_build_relationship'},
                            {name: 'total'}

                        ],
                        localdata: leadata,
                        pagenum: 0,
                        pagesize: 50,
                        pager: function (pagenum, pagesize, oldpagenum) {
                            // callback called when a page or page size is changed.
                        }
                    };


            // alert("summary data"+summaryData.toSource());

             var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
                                    var branch = this.owner.source.records[row]['collector'];
                                    var color='#e83636';

                                    var datarow = $("#lmsleadcounts").jqxGrid('getrowdata', row);
                                        if (datarow.collector === "WEIGHTAGE") 
                                        {
                                            return '<span style="margin: 6px; float:'+ columnproperties.cellsalign +';color: #0000ff;">' + value + '</span>';
                                        }
                                        return '<div style="margin: 3px 0 0 3px;">'+value+'</div>';
                                        

                                }

   
             $("#excelExport").click(function () {
                   $("#lmsleadcounts").jqxGrid('exportdata', 'xls', 'view_lead_overall_qnty');
                  //   dashboard/savefile');
                });
             $("#excelExport").jqxButton({
                 theme: 'energyblue'
                 });
         var dataAdapter = new $.jqx.dataAdapter(source);

            $("#lmsleadcounts").jqxGrid(
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
                            {text: 'Branches', dataField: 'collector', width: 125, hidden: false, filterable: false,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Branches<br><b><font color="black">WEIGHTAGE %</font></b></div>';
                                    }},
                            {text: '0-Prospect', dataField: 'prospects', width: 85, cellsalign: 'center',
                                    renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">0-Prospect<br><b><font color="black">10 %</font></b></div>';
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Total', dataField: 'total', width: 120, cellsalign: 'center', cellsrenderer:cellsrenderer,cellsformat: 'n2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element,row, columnfield) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblue' style='float: left; width: 100%; height: 100%; '>";
                                     
                                     var datarow =     $('#lmsleadcounts').jqxGrid('getrowdata', 0);
                                        
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

            $('#lmsleadcounts').jqxGrid('renderaggregates');
        
        } // End of function displayLeadCounts()

        function  displayLmsScorecard()
        {
            //data = leadata;
        // prepare the data
            source =
                    {
                        datatype: "json",
                        sortcolumn: 'id',
                        sortdirection: 'asc',
                        datafields: [
                            {name: 'id'},
                            {name: 'collector'},
                            {name: 'prospects_sc'},
                            {name: 'met_the_customer_sc'},
                            {name: 'credit_sssessment_sc'},
                            {name: 'sample_trails_formalities_sc'},
                            {name: 'enquiry_offer_negotiation_sc'},
                            {name: 'managing_and_implementation_sc'},
                            {name: 'expanding_and_build_relationship_sc'},
                            {name: 'total_sc'}

                        ],
                        localdata: lmsscorecard,
                        pagenum: 0,
                        pagesize: 50,
                        pager: function (pagenum, pagesize, oldpagenum) {
                            // callback called when a page or page size is changed.
                        }
                    };


            // alert("summary data"+summaryData.toSource());

             var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
                                    var branch = this.owner.source.records[row]['collector'];
                                    var color='#e83636';

                                    var datarow = $("#lmsscore_card").jqxGrid('getrowdata', row);
                                        if (datarow.collector === "WEIGHTAGE") 
                                        {
                                            return '<span style="margin: 6px; float:'+ columnproperties.cellsalign +';color: #0000ff;">' + value + '</span>';
                                        }
                                        return '<div style="margin: 3px 0 0 3px;">'+value+'</div>';
                                        

                                }

   
             $("#excelExportlmssc").click(function () {
                   $("#lmsscore_card").jqxGrid('exportdata', 'xls', 'view_lms_scorecard_overall');
                  //   dashboard/savefile');
                });
             $("#excelExportlmssc").jqxButton({
                 theme: 'energyblue'
                 });
         var dataAdapter = new $.jqx.dataAdapter(source);

            $("#lmsscore_card").jqxGrid(
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
                            {text: 'Branches', dataField: 'collector', width: 125, hidden: false, filterable: false,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Branches<br><b><font color="black">WEIGHTAGE %</font></b></div>';
                                    }},
                            {text: '0-Prospect', dataField: 'prospects_sc', width: 85, cellsalign: 'center',
                                    renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">0-Prospect<br><b><font color="black">10 %</font></b></div>';
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: '1-Met The Customer', dataField: 'met_the_customer_sc', width: 85, cellsalign: 'center',renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">1-Met The Customer<br><b><font color="black">20 %</font></b></div>';
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Credit Assesment', dataField: 'credit_sssessment_sc', width: 85, cellsalign: 'center',renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Credit Assesment<br><b><font color="black">30 %</font></b></div>';
                                    }, cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Sample,Trails & Formalities', dataField: 'sample_trails_formalities_sc', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Sample,Trails & Formalities<br><b><font color="black">50 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Enquiry Offer Negotiation', dataField: 'enquiry_offer_negotiation_sc', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Enquiry Offer Negotiation<br><b><font color="black">70 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Managing And Implementation', dataField: 'managing_and_implementation_sc', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Managing And Implementation<br><b><font color="black">80 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Expanding And Building Relation', dataField: 'expanding_and_build_relationship_sc', width: 85,renderer: function (defaultText, alignment, height) {
                                        return '<div style="margin: 3px 0 0 3px;">Expanding And Building Relation<br><b><font color="black">100 %</font></b></div>';
                                    }, cellsalign: 'center', cellsformat: 'n2', aggregates: ['sum'],
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
                            {text: 'Total', dataField: 'total_sc', width: 120, cellsalign: 'center', cellsrenderer:cellsrenderer,cellsformat: 'n2', aggregates: ['sum'],
                                aggregatesrenderer: function (aggregates, column, element,row, columnfield) {
                                    var renderstring = "<div class='jqx-widget-content jqx-widget-content-energyblue' style='float: left; width: 100%; height: 100%; '>";
                                     
                                     var datarow =     $('#lmsscore_card').jqxGrid('getrowdata', 0);
                                        
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

            $('#lmsscore_card').jqxGrid('renderaggregates');
        
        } // End of function displayLeadCounts()
        displayzone();
        displaycollector();
        displaymarketcircle();
        displayproducts();
        displayLeadCounts();
        displayLmsScorecard();
     
      /*JC period start*/

            var url = base_url + "lmsscorecard/getjchdrforweek/"+hdnaccount_yr;
            jc_source_from =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'jc_name'},
                            {name: 'line_id'}
                        ],
                        url: url,
                        async: false
                    };
                      

            var jc_dataAdapter_jcperiod = new $.jqx.dataAdapter(jc_source_from);
              $("#jcperiod_from").jqxDropDownList({
                selectedIndex: sel_index_for_jc_period,
                source: jc_dataAdapter_jcperiod,
                displayMember: "jc_name",
                valueMember: "line_id",
                width: 75,
                autoDropDownHeight:true,
                theme: theme,
                placeHolder: '– JC Period –'
            });
               $("#jcperiod_from").jqxDropDownList('val', hdnjc_period);
            var url = base_url + "lmsscorecard/getjcweek_hdr/"+hdnaccount_yr+"/"+sel_index_for_jc_period;
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

             var jc_dataAdapter_week = new $.jqx.dataAdapter(jc_week);
              $("#jcperiod_week").jqxDropDownList({
                selectedIndex: sel_index_for_jc_week,
                source: jc_dataAdapter_week,
                displayMember: "week_id",
                valueMember: "line_id",
                width: 75,
                autoDropDownHeight:true,
                theme: theme,
                placeHolder: '– JC Week –'
            });

            //$("#jcperiod_week").jqxDropDownList('val', hdn_jc_week);

             $("#jcperiod_from").on('change', function (event) {
                var args = event.args;
                 var item_jcperiod = args.item;
                    // get item's label and value.
                    var label_jcperiod = item_jcperiod.label;
                    var value_jcperiod = item_jcperiod.value;
                   // alert(" in jcperiod on change label_jcperiod is "+label_jcperiod);
                   // alert(" in jcperiod on change value_jcperiod is "+value_jcperiod);
                    var  jcweek_in_jcperiod =  $("#jcperiod_week").jqxDropDownList('getSelectedItem'); 
                   // alert(" in jcperiod on change jcweek_in_jcperiod is "+jcweek_in_jcperiod.value);
               // updateJcWeekdate(event.args.item.value);
              // jc_period_line_id=event.args.item.value;
               var finyear_in_jcperiod =  $("#finance_year").jqxDropDownList('getSelectedItem').value; 

               //  var url = base_url + "lmsscorecard/reloadjcweek_hdr/"+hdnaccount_yr+"/"+value_jcperiod;
                 //alert(" in finyear_in_jcperiod on change finyear_in_jcperiod is "+finyear_in_jcperiod);

                 var url = base_url + "lmsscorecard/reloadjcweek_hdr/"+finyear_in_jcperiod+"/"+value_jcperiod;
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
                 var jc_dataAdapter_week = new $.jqx.dataAdapter(jc_week);
                  $("#jcperiod_week").jqxDropDownList({
                    selectedIndex: sel_index_for_jc_week,
                    source: jc_dataAdapter_week,
                    displayMember: "week_id",
                    valueMember: "line_id",
                    width: 75,
                    autoDropDownHeight:true,
                    theme: theme,
                    placeHolder: '– JC Week –'
                });
                var  jcweek_in_jcperiod =  $("#jcperiod_week").jqxDropDownList('getSelectedItem');  
    
               // alert(" in jcperiod on change label_jcperiod is "+label_jcperiod);
                //  alert(" in jcperiod on change value_jcperiod is "+value_jcperiod);
                 // 


                  

            });
             


              $("#jcperiod_week").on('change', function (event) {
                
                 jc_week_line_id =event.args.item.value;
             //   alert(" in updateJcWeekdate line_id is "+jc_week_line_id);
                updateJcWeekdate(jc_week_line_id);
            });
          

           /* var updateJcWeekdate = function (jcweek) {
              alert('jc code  in updateJcweekDate '+jcweek);
           
            }*/

            

            var updateJcWeekdate = function (jcweek) {
                fin_year =  $("#finance_year").jqxDropDownList('val');
                jccode =  $("#jcperiod_from").jqxDropDownList('val');  
              //  jccode =  $("#jcperiod_from").jqxDropDownList('val');  
               // alert("jccode line_id "+jccode);              
              //  alert("jc_week line_id "+jcweek);
                $.ajax({
                    url: baseurl + "lmsscorecard/getjcweekdates/" + fin_year+"/"+jccode+"/"+jcweek,
                    dataType: "json",
                    success: function (jc_week_dates) {
                       SetJcWeekdate(jc_week_dates);
                    }
                });
            }


        
            // Create a jqxDropDownList
           

             var url = base_url + "lmsscorecard/getfinanceyear";
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
                width: 100,
                autoDropDownHeight: true,
                theme: theme,
                placeHolder: '– Select Year –'
            });
            $("#finance_year").jqxDropDownList('val', hdnaccount_yr);

            function SetJcWeekdate(jc_week_dates){
                 // alert("jc_period_from jc_week_dates[0] "+jc_week_dates.toSource());
                  //alert("type jc_week_dates[0] "+typeof(jc_week_dates));
                  try {
                        $("#fromdate").jqxDateTimeInput('setDate', jc_week_dates[0].week_period_from);                  
                       // $("#todate").jqxDateTimeInput('setDate', jc_week_dates[0].week_period_to);
                    } catch(e) {
                      e.message;  // "foo is not defined"
                    }

                 
                   

                }
             
          
             $("#finance_year").on('change', function (event) {

                 var args = event.args;
                 var item_finyear = args.item;
                 var label_finyear = item_finyear.label;
                 var value_finyear = item_finyear.value;

                 var jcperiod_in_finyear =  $("#finance_year").jqxDropDownList('getSelectedItem'); 
                  alert(" in finance_year on change jcperiod_in_finyear is "+jcperiod_in_finyear.value);

              var url_finyr = base_url + "lmsscorecard/getjchdrforweek/"+value_finyear;
            jc_source_from =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'jc_name'},
                            {name: 'line_id'}
                        ],
                        url: url_finyr,
                        async: false
                    };
                      

            var jc_dataAdapter_jcperiod = new $.jqx.dataAdapter(jc_source_from);
              $("#jcperiod_from").jqxDropDownList({
                selectedIndex: sel_index_for_jc_period,
                source: jc_dataAdapter_jcperiod,
                displayMember: "jc_name",
                valueMember: "line_id",
                width: 75,
                autoDropDownHeight:true,
                theme: theme,
                placeHolder: '– JC Period –'
            });
            $("#jcperiod_from").jqxDropDownList('val', value_finyear);
            var url = base_url + "lmsscorecard/getjcweek_hdr/"+value_finyear+"/"+sel_index_for_jc_period;
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

             var jc_dataAdapter_week = new $.jqx.dataAdapter(jc_week);
              $("#jcperiod_week").jqxDropDownList({
                selectedIndex: sel_index_for_jc_week,
                source: jc_dataAdapter_week,
                displayMember: "week_id",
                valueMember: "line_id",
                width: 75,
                autoDropDownHeight:true,
                theme: theme,
                placeHolder: '– JC Week –'
            });
          
       
             
            });

             var updateJcweekDatefy = function (jccode) {
               alert('updateJcweekDatefy'+jccode);
            fin_year =  $("#finance_year").jqxDropDownList('val');
            jccode =  $("#jcperiod_from").jqxDropDownList('val');
            jcweek =  $("#jcperiod_week").jqxDropDownList('val');
           // alert('fin_year in updateJcweekDatefy'+fin_year);
           // alert('jccode line_id in updateJcweekDatefy'+jccode);
          //  alert('jcweek line_id in updateJcweekDatefy'+jcweek);
               /* $.ajax({
                    url: baseurl + "dashboard/getjcperiodfromdate/" + jccode+"/"+fin_year,
                    dataType: "json",
                    success: function (jcfrom_date) {
                       JcFromdate(jcfrom_date);
                    }
                });*/
           
            }

            $("#zone").on('change', function (event) {
                var args = event.args;
                var item_zoneforcoll = args.item;
                // get item's label and value.
                var label_item_zoneforcoll = item_zoneforcoll.label;
                var value_item_zoneforcoll = item_zoneforcoll.value;
                // alert(" in jcperiod on change label_jcperiod is "+label_jcperiod);
                // alert(" in jcperiod on change value_jcperiod is "+value_jcperiod);
                var  zone_for_collector =  $("#zone").jqxDropDownList('getSelectedItem').value; 

                  var url = base_url + "lmsscorecard/getcollectorsforfilter/"+zone_for_collector;
                        collector_source =
                                {
                                    datatype: "json",
                                    datafields: [
                                        {name: 'collectors'},
                                        {name: 'collectors'}
                                    ],
                                    url: url,
                                    async: false
                                };

                        var collector_dataAdapter = new $.jqx.dataAdapter(collector_source);
                          $("#collector").jqxDropDownList({
                            selectedIndex: 0,
                            source: collector_dataAdapter,
                            displayMember: "collectors",
                            valueMember: "collectors",
                            width: 100,
                            height: 25,
                            theme: theme,
                            placeHolder: '– Select collector –'
                        });
                 });
           
           $("#collector").on('change', function (event) {
                var args = event.args;
               
                 var item_collformc = args.item;
               

              // alert("on collector change is "+item_collect_mc);
              var  collector_for_mc =  $("#collector").jqxDropDownList('getSelectedItem').value; 

              var url = base_url + "lmsscorecard/getmarketcirclesforfilter/"+collector_for_mc;
                    mc_source =
                            {
                                datatype: "json",
                                datafields: [
                                    {name: 'market_circle'},
                                    {name: 'market_circle'}
                                ],
                                url: url,
                                async: false
                            };

                    var mc_dataAdapter = new $.jqx.dataAdapter(mc_source);
                      $("#market_circle").jqxDropDownList({
                        selectedIndex: 0,
                        source: mc_dataAdapter,
                        displayMember: "market_circle",
                        valueMember: "market_circle",
                        width: 100,
                        height: 25,
                        theme: theme,
                        placeHolder: '– Select MC –'
                    });
                
                
                 });

                
/*JC period end*/     

        $("#applyfilter").click(function () 
        {
                fin_year =  $("#finance_year").jqxDropDownList('val');
                jccode =  $("#jcperiod_from").jqxDropDownList('val');  
                jcperiod_week =  $("#jcperiod_week").jqxDropDownList('val');  
                zone =  $("#zone").jqxDropDownList('val');  
                collector =  $("#collector").jqxDropDownList('val');  
                market_circle =  $("#market_circle").jqxDropDownList('val');  
                itemgroup =  $("#itemgroup").jqxDropDownList('val');  
                
                from_date = $("#fromdate").jqxDateTimeInput('getDate');
                from_date = convert(from_date);
          

                to_date = $("#todate").jqxDateTimeInput('getDate');
                to_date = convert(to_date);

              //  finance_year,jcperiod_from,jcperiod_week,zone,collector,market_circle,itemgroup,fromdate,todate 
              //  jccode =  $("#jcperiod_from").jqxDropDownList('val');  
               /* alert("fin_year "+fin_year);              
                alert("jccode  "+jccode);
                alert("jcperiod_week  "+jcperiod_week);              
                alert("zone  "+zone);
                alert("collector  "+collector);              
                alert("market_circle  "+market_circle);
                alert("itemgroup  "+itemgroup);              
                alert("from_date  "+from_date);
                alert("to_date  "+to_date);*/
          /*  if ()
            else if ()
            else if ()
            else 
            {
                 setfilters(dataFieldbranch,jccode_from,jccode_to,fin_year);
            }    */
            //return false;
            setfilters(fin_year,jccode,jcperiod_week,zone,collector,market_circle,itemgroup,from_date,to_date);

        });

         var setfilters = function (fin_year,jccode,jcperiod_week,zone,collector,market_circle,itemgroup,from_date,to_date) {

                        $.ajax({
                        url: base_url + 'lmsscorecard/getlmswithfilters/' + fin_year + '/' + jccode + '/' + jcperiod_week +'/'+fin_year+'/'+zone+'/'+collector+'/'+market_circle+'/'+encodeURIComponent(itemgroup)+'/'+from_date+'/'+to_date,
                        success: function () {
                            // i must remove the div
                            //  alert("success");
                            window.location.href = base_url + 'lmsscorecard/getlmswithfilters/' + fin_year + '/' + jccode + '/' + jcperiod_week +'/'+zone+'/'+collector+'/'+market_circle+'/'+encodeURIComponent(itemgroup)+'/'+from_date+'/'+to_date;
                            //window.location.href=base_url + 'dashboard/getdatawithdate_filter/'+ datafield+'/'+dataFielduser+'/'+from_date+'/'+to_date;

                        }
                    });
         }

 var dataAdapter = new $.jqx.dataAdapter(source,
                {
                    autoBind: true,
                    async: false,
                    downloadComplete: function () {
                    },
                    loadComplete: function () {
                    },
                    loadError: function () {
                    }
                });
         var settings = {
                title: "LMS & Last 3 JC scorecard",
                description: "Scorecard of leads Prospects in JC Wise",
                enableAnimations: true,
                showLegend: true,
                padding: { left: 5, top: 5, right: 5, bottom: 5 },
                titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
                source: lmsscorecard_chart,
                categoryAxis:
                    {
                        text: 'Category Axis',
                        textRotationAngle: 0,
                        dataField: 'score',
                        showTickMarks: true,
                        tickMarksInterval: 1,
                        tickMarksColor: '#888888',
                        unitInterval: 1,
                        showGridLines: false,
                        gridLinesInterval: 1,
                        gridLinesColor: '#888888',
                        axisSize: 'auto'
                    },
                colorScheme: 'scheme05',
                seriesGroups:
                    [
                        {
                            type: 'line',
                            valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                maxValue: 100,
                                displayValueAxis: false,
                                description: 'No of Leads',
                                axisSize: 'auto',
                                tickMarksColor: '#888888'
                            },
                            series: [
                                    { dataField: 'score', displayText: 'Overall LMS Scorecard', opacity: 0.7 }
                                ]
                        },
                        {
                            type: 'column',
                            columnsGapPercent: 100,
                            seriesGapPercent: 5,
                            valueAxis:
                            {
                                unitInterval: 10,
                                minValue: 0,
                                maxValue: 100,
                                displayValueAxis: true,
                                description: 'Time in minutes',
                                axisSize: 'auto',
                                tickMarksColor: '#888888',
                                gridLinesColor: '#777777'
                            },
                            series: [
                                    { dataField: 'JC1Week1', displayText: 'JC1Week1' },
                                    { dataField: 'JC1Week2', displayText: 'JC1Week2' },
                                    { dataField: 'JC1Week3', displayText: 'JC1Week3' },
                                    { dataField: 'JC1Week4', displayText: 'JC1Week4' },
                                ]
                        }
                    ]
            };
// setup the chart

        $('#chart_lmsscore_card').jqxChart(settings);
        $('#chart_lmsscore_card').jqxChart('addColorScheme', 'myScheme', ['#215BCF', '#CC3300', '#7AA300', '#5C00E6', '#996633', '#FF0066','#CCCC00','#520029']);

        // apply the new scheme by setting the chart's colorScheme property
        $('#chart_lmsscore_card').jqxChart('colorScheme', 'myScheme');
        $('#chart_lmsscore_card').jqxChart({showLegend: true});
        $('#chart_lmsscore_card').jqxChart({rtl: false});

        

          
                       
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
                                    <strong>Dashboard - Leads Count for <? echo $zone;?> Branchs till date</strong>
                                </span>
            

                            </div>
                            <span class="btn-group">

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
                        <table width="70%" border="1">
                            <tr>
                                <td width="25%" style="padding-left:8px;"><div>Select the Fin-Year:</div></td>
                                <td><div style="float: left" id="finance_year"></div></td>
                          
                                <td width="25%" style="padding-left:8px;"><div>JC Period :</div></td>
                                <td><div style="float: left" id="jcperiod_from"></div></td>
                          
                                <td width="25%" style="padding-left:8px;"><div>JC Week:</div></td>
                                <td><div style="float: left" id="jcperiod_week"></div></td>

                                <td width="25%" style="padding-left:8px;"><div>Region:</div></td>
                                <td><div style="float: left" id="zone"></div></td>
                         
                                <td width="25%" style="padding-left:8px;"><div>Collector:</div></td>
                                <td><div style="float: left" id="collector"></div></td>

                                <td width="25%" style="padding-left:8px;"><div>Market Cricle:</div></td>
                                <td><div style="float: left" id="market_circle"></div></td>

                                <td width="25%" style="padding-left:8px;"><div>Product:</div></td>
                                <td><div style="float: left" id="itemgroup"></div></td>    
                            </tr> 
                             <tr>
                               <td><label>JC Period From </label><div style="float: inherit;" id="fromdate" name="fromdate"></div></td>
                                <td><label>JC Period To </label><div style="float: inherit;" id="todate" name="todate"></div></td>
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
                                    <div id="lmsleadcounts"></div>
                                </div>
                            </div>
                           
                        </div>
                        <div style="clear:both;"></div>
                          <div id="sub-main">
                        
                            <div id="sub-left">
                                <div id='jqxWidget_cum'>
                                  <input style='margin-top: 10px;margin-left:733px;' title="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option"   alt="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option" type="button" value="Export to Excel" id='excelExportlmssc' />
                                    <div id="lmsscore_card" ></div>
                                </div>
                            </div>
                        </div>
                        <div style="width:100%; float:left;">
                            <div style="width:100%;"></div>
                            <!-- grid for qnty start -->
                         <table width="100%">
                                                     
                             <tr>
                                <td>
                                    <div id='chart_lmsscore_card' style="width:93%; height: 500px"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id='eventText' style="width:100%; height: 30px"/> 
                                </td>
                            </tr>
                        </table>
                            <!-- grid for qnty end -->
                        </div>




                    </div>
                                       
                <!-- End of Grid content -->
                 <div style="width:100%; float:left;">
                     
                        <div style="width:100%;"></div>                        

                </div>

    </div>
</div>
</div>
</div>
<input id="activityReminder" class="hide noprint" value="60" type="hidden">
<input value="<?php echo $zone; ?>" id="hdn_zone" name="hdn_zone" type="hidden">
 <input value="<?php echo $collector; ?>" id="hdn_collector" name="hdn_collector" type="hidden">
 <input value="<?php echo $zone; ?>" id="hdn_zone" name="hdn_zone" type="hidden">
 <input value="<?php echo $marketcircle; ?>" id="hdn_marketcircle" name="hdn_marketcircle" type="hidden">
 <input value="<?php echo $itemgroup; ?>" id="hdn_itemgroup" name="hdn_itemgroup" type="hidden">
  
<input value="<?php echo @$fromdate; ?>" id="hdn_from_date" name="hdn_from_date" type="hidden">
<input value="<?php echo @$todate; ?>" id="hdn_to_date" name="hdn_to_date" type="hidden">

<input value="<?php echo @$jc_week; ?>" id="hdn_jc_week" name="hdn_jc_week" type="hidden">
<input value="<?php echo @$jc_period; ?>" id="hdn_jc_period" name="hdn_jc_period" type="hidden">
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