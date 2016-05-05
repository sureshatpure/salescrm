<?php $this->load->view('header_novalid'); ?>
<!-- jqwidgets scripts -->
<script type="text/javascript" src="<?= base_url() ?>public/js/jquery_007.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jquery/jqueryui.js"></script>
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
<script type="text/javascript" src="<?= base_url() ?>public/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/js/index_search.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script> 



<!-- sorting and filtering - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>


<!-- sorting and filtering and export excel - end -->
<!-- paging - start -->
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.export.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.export.js"></script>
<!-- paging - end -->

<!-- End of jqwidgets -->


<script type="text/javascript">
    var base_url = '<?php echo site_url(); ?>';
    $(document).ready(function ()
    {
          
        var theme = "";
        // Create a jqxMenu
        $("#jqxMenu").jqxMenu({width: '670', height: '30px', theme: 'black'});
        $("#jqxMenu").css('visibility', 'visible');
        $("#btn_search").jqxMenu({width: '100', height: '30px', theme: 'energyblue'});
        $("#btn_reset").jqxMenu({width: '100', height: '30px', width:'175px', theme: 'energyblue'});
        var leadata = <?php echo $data; ?>;
        var baseurl = base_url;
        var permission = <?php echo $grpperm; ?>;
        var group_len = permission.length;
        var br = $('#hdn_branch').val();
        var sel_user_id = $('#hdn_userid').val();
        var sel_mc_id = $('#hdn_mc_sub_id').val();
        
        var assign_user_id = $('#hdn_assign_user_id').val();
        var statusid = $('#hdn_status_id').val();
        var substatusid = $('#hdn_substatus_id').val();
        var customerid = $('#hdn_customerid').val();
        var productid = $('#hdn_productid').val();
        var from_date = $('#hdn_from_date').val();
        var to_date = $('#hdn_to_date').val();
        var sel_datefilter;
        var clearbranch=0;
        var clearstatus=0;
        var re_assign_user_id;
        var re_assign_branch;
        var theme = 'energyblue';
        var data = leadata;

        
        $("#btn_reset").on('click', function (event) {
            //alert("check");
            clearbranch=1;
            clearstatus=1;
                //$("#selectuser").jqxDropDownList({ selectedIndex: -1});
                $("#marketcircle").jqxDropDownList({ selectedIndex: -1});
                $("#assigntouser").jqxDropDownList({selectedIndex: -1});
                
                $("#selectbranch").jqxDropDownList({ selectedIndex: -1});
                $("#product").jqxDropDownList({ selectedIndex: -1});
                $("#customer").jqxDropDownList({ selectedIndex: -1});
                $('#fromdate ').jqxDateTimeInput('setDate', null);
                $('#todate ').jqxDateTimeInput('setDate', null);
                $("#status").jqxDropDownList({ selectedIndex: -1});
                $("#substatus").jqxDropDownList({ selectedIndex: -1});

                $("#product").jqxDropDownList('clearSelection', true);
                $("#customer").jqxDropDownList('clearSelection', true);
                //$("#selectuser").jqxDropDownList('clearSelection', true);
                $("#marketcircle").jqxDropDownList({ selectedIndex: -1});
                $("#assigntouser").jqxDropDownList('clearSelection', true);
                $("#substatus").jqxDropDownList('clearSelection', true);
                $("#selectbranch").jqxDropDownList('clearSelection', true);
                $("#status").jqxDropDownList('clearSelection', true);

        });

        $("#excelExport").jqxButton({
            theme: 'energyblue'
        });

        $("#excelExport").click(function () {
           $("#jqxgrid").jqxGrid('exportdata', 'xls', 'viewleaddata');
          //   dashboard/savefile');
        });

        var source =
                {
                    datatype: "json",
                    sortcolumn: 'leadid',
                    sortdirection: 'desc',
                    datafields: [
                        {name: 'leadid',type: 'number'},
                        {name: 'statusid'},
                        {name: 'substatusid'},
                        {name: 'lead_no'},
                        {name: 'lead_close_status'},
                        {name: 'branch'},
                        {name: 'mc_sub_id', type:'string'},
                        {name: 'leadstatus'},
                        {name: 'substatusname'},
                        {name: 'leadsource'},
                        {name: 'productname', type: 'string'},
                        {name: 'product_group', type: 'string'},
                        /*{ name: 'salestype',type:'string'},*/
                        {name: 'assign_from_name'},
                        {name: 'empname'},
                        {name: 'tempcustname'},
                        {name: 'created_date', type: 'datetime'},
                        {name: 'modified_date', type: 'datetime'},
                        {name: 'fin_yr', type: 'string'},
                        {name: 'jcode', type: 'string'}

                    ],
                    localdata: data,
                    pagenum: 0,
                    pagesize: 50,
                    pager: function (pagenum, pagesize, oldpagenum) {
                        // callback called when a page or page size is changed.
                    }
                };


        var dataAdapter = new $.jqx.dataAdapter(source);
        $("#jqxgrid").jqxGrid(
                {
                    width: '100%',
                    source: dataAdapter,
                    theme: theme,
                    selectionmode: 'checkbox',
                    sortable: true,
                    pageable: true,
                    columnsresize: true,
                    sortable: true,
                    showfilterrow: true,
                    filterable: true,
                    columns: [
                        {text: 'LeadId', dataField: 'leadid', width: 75, hidden: false},
                        {text: 'Sid', dataField: 'statusid', width: 50, hidden: true},
                        {text: 'Subid', dataField: 'substatusid', width: 50, hidden: true},
                        {text: 'LeadNo', dataField: 'lead_no', width: 100,hidden: true},
                        {text: 'Closed', dataField: 'lead_close_status', width: 75,hidden: true},
                        {text: 'Branch', dataField: 'branch', width: 159, cellsalign: 'left'},
                        {text: 'Mkt Circle', dataField: 'mc_sub_id', width: 100, cellsalign: 'left'},
                        {text: 'Lead Status', dataField: 'leadstatus', width: 85},
                        {text: 'Lead SubStatus', dataField: 'substatusname', width: 120},
                        {text: 'Lead Source', dataField: 'leadsource', width: 85, cellsalign: 'left'},
                        {text: 'Product Name', dataField: 'productname', width: 100, cellsalign: 'left'},
                        {text: 'Item Group', dataField: 'product_group', width: 100, cellsalign: 'left'},
                        {text: 'Assigned From', dataField: 'assign_from_name', width: 120, cellsalign: 'left'},
                        {text: 'Assigned To', dataField: 'empname', width: 125, cellsalign: 'left'},
                        {text: 'Customer Name', dataField: 'tempcustname', cellsalign: 'left', minwidth: 150},
                        {text: 'Created Date', dataField: 'created_date', cellsalign: 'left', width: 95, cellsformat: 'd', formatString: 'd'},
                        {text: 'Last Activity', dataField: 'modified_date', cellsalign: 'left', width: 95, cellsformat: 'd', formatString: 'd'},
                        {text: 'Accnt-Year', dataField: 'fin_yr', width: 85,cellsalign: 'center'},
                        {text: 'JC Period', dataField: 'jcode', width: 85,cellsalign: 'center'}

                    ],
                    showtoolbar: true,
                    autoheight: true,
                    rendertoolbar: toolbarfunc
                });


        var toolbarfunc = function (toolbar) {
            var me = this;
            var theme = 'energyblue';
            //  alert("theme "+theme);

            var container = $("<div style='width:200px; margin-top: 6px;' id='jqxWidget'></div>");
            var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'></span>");
            var startdiv = $("<div>");
            var addlead = $("<a role='button' class='jqx-link jqx-link-energyblue' style='margin-left: 25px;' href='#' id='jqxButtonadd'><input type='button' class='jqx-wrapper jqx-reset jqx-reset-energyblue jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-pressed jqx-fill-state-pressed-energyblue' style='width: 65px; height: 22px;' value='Add' id='jqxWidget09c6ffa4' role='button' aria-disabled='false'></a>");
            var viewlead = $("<a role='button' class='jqx-link ' style='margin-left: 25px;' target='_blank' href='#' id='jqxButton'><input type='button' class='jqx-wrapper jqx-reset jqx-reset-energyblue jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-pressed jqx-fill-state-pressed-energyblue' style='width: 79px; height: 22px;' value='View' id='jqxWidget09c6ffa4' role='button' aria-disabled='false'></a>");

            var edit = $("<a style='margin-left: 25px;' href='#' id='jqxButtonedit'><input type='button' class='jqx-wrapper jqx-reset jqx-reset-energyblue jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-pressed jqx-fill-state-pressed-energyblue' style='width: 65px; height: 22px;' value='Edit' id='jqxWidget09c6ffa4' role='button' aria-disabled='false'></a>");
            var statusupdate = $("<a style='margin-left: 25px;' target='' href='#' id='jqxButtonUpdate'><input type='button' class='jqx-wrapper jqx-reset jqx-reset-energyblue jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-pressed jqx-fill-state-pressed-energyblue' style='width: 88px; height: 22px;' value='Update' id='jqxWidget09c6ffa4' role='button' aria-disabled='false'></a>");
             var reassign = $("<a style='margin-left: 25px;' target='' href='#' id='jqxReassign'><input type='button' class='jqx-wrapper jqx-reset jqx-reset-energyblue jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-pressed jqx-fill-state-pressed-energyblue' style='width: 88px; height: 22px;' value='Re-Assign' id='jqxWidget09c6ffa4' role='button' aria-disabled='false'></a>");
            var enddiv = $("</div>");
            toolbar.append(container);
            container.append(span);
            container.append(startdiv);
            container.append(addlead);
            container.append(viewlead);
            container.append(statusupdate);
            
            for (i = 0; i < permission.length; i++)
            {
                //alert(permission[i].groupname);
                if (permission[i].groupname == 'Edit')
                {
                    container.append(edit);
                }
                if (permission[i].groupname == 'Reassign')
                {
                    container.append(reassign);
                }
            }
            container.append(enddiv);
            if (theme != "") {
            }

            var leadid;
            $('#jqxgrid').bind('checkbox', function (event)
            {
                var rowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                
                leadid = event.args.row.leadid;
            });
            $('#jqxgrid').bind('celldoubleclick', function (event) {
                 var rowindex = args.rowindex;
                 var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');

                if(rowindex==-1)
                {
                   alert("Please select a lead and double click to veiw the lead details");

                   return false;
                     
                }
                else
                {
                      var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');
                
                    if (leadid == null)
                    {
                        alert("Please Select a row");
                        //  $('#jqxButton').attr('href','http://google.com');
                        return false;

                    }
                    else
                    {
                        window.open('viewleaddetails/' + leadid); 
                    }
                }
               
                
               
            });
          

            var oldVal = "";
            viewlead.on('click', function (event)
            {
                var rowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');


                $('#jqxButton').attr('href', base_url + 'leads/viewleaddetails/' + leadid);
                if (leadid == null)
                {
                    alert("Please Select a row");
                    //	$('#jqxButton').attr('href','http://google.com');
                    return false;

                }
                else
                {
                    $('#jqxButton').attr('href', base_url + 'leads/viewleaddetails/' + leadid);
                }

            });

            edit.on('click', function (event)
            {
                var rowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');
                var closed = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'lead_close_status');
                if (leadid == null || closed == "Closed")
                {
                    if (leadid == null)
                    {
                        alert("Please Select a row ");
                        return false;
                    }
                    else
                    {
                        alert("You are not allowed to edit a closed lead ");
                        return false;

                    }


                }


                else
                {
                    $('#jqxButtonedit').attr('href', base_url + 'leads/edit/' + leadid);
                }

            });
            statusupdate.on('click', function (event)
            {
                var rowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');
                var closed = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'lead_close_status');
                if (leadid == null || closed == "Closed")
                {
                    if (leadid == null)
                    {
                        alert("Please Select a row ");
                        return false;
                    }
                    else
                    {
                        alert("You are not allowed to update a closed lead ");
                        return false;

                    }


                }
                else
                {
                    $('#jqxButtonUpdate').attr('href', base_url + 'leads/editstatus/' + leadid);
                }

            });

            addlead.on('click', function (event)
            {
                $('#jqxButtonadd').attr('href', base_url + 'leads/add');

            });

             reassign.on('click', function (event)
             {
                  var griddata=null;
                  var rowscount = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
                  var rowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
                  var leadid = $("#jqxgrid").jqxGrid('getcellvalue', rowindex, 'leadid');
                  var dataval = {};
                  var datavalbr = {};

                  var branch_validate=0;
                  //alert("leadid is "+leadid);
              //  alert("griddata is "+griddata);

                    if (leadid == null && griddata== null )
                    // if (leadid == null)    
                    {
                        alert("Please select one or more leads with same branch");
                        return false;
                    }
                    else
                    {
                        var selectedRows = $("#jqxgrid").jqxGrid('getselectedrowindexes'); 
                        var i=0;

                            for (var rowid in selectedRows) 
                            {
                                var rowval = {};
                                //var t = $("#jqxgrid").jqxGrid('getrowdata', selectedRows[rowid].leadid); 
                              //  alert("selected row id is "+selectedRows[rowid]);  
                                var leadidt = $("#jqxgrid").jqxGrid('getcellvalue',selectedRows[rowid],'leadid');  
                                var branchdt = $("#jqxgrid").jqxGrid('getcellvalue',selectedRows[rowid],'branch');  
                                var statusid = $("#jqxgrid").jqxGrid('getcellvalue',selectedRows[rowid],'statusid');  
                                var substsid = $("#jqxgrid").jqxGrid('getcellvalue',selectedRows[rowid],'substatusid');  
                                griddata = $('#jqxgrid').jqxGrid('getrowdata', selectedRows[rowid]);   
                                rowval["leadid"] = griddata.leadid;  
                                rowval["branch"] = griddata.branch;  
                                rowval["statusid"] = griddata.statusid;   
                                rowval["substatusid"] = griddata.substatusid;   
                                dataval[i] = rowval; 
                                datavalbr[i] = rowval['branch']; 
                                i++;                 
                            } 
                            var branchinfo = JSON.stringify(datavalbr);
                                  branchinfo ="{\"branches\":["+branchinfo+"]}";
                                  var my_obj = JSON.parse(branchinfo);
                                 
                                    for (var key in my_obj) {
                                           var arr = my_obj[key];
                                          // alert("arr length "+arr.length);
                                           for( var i = 0; i < arr.length; i++ ) 
                                            {
                                               
                                               var obj = arr[i];
                                               var j=0;
                                               for (var prop in obj) 
                                               {
                                                    if(obj.hasOwnProperty(prop))
                                                   {
                                                      // console.log(prop + " = " + obj[prop]);
                                                      // alert("j "+j );
                                                      // alert("branch name "+ obj[j]);
                                                       if(j>0)
                                                       {
                                                        if (obj[j-1]!=obj[j])
                                                        {
                                                            alert("Please select leads with same branch only");
                                                            branch_validate=1;
                                                            break;
                                                        }
                                                       }
                                                       else
                                                       {
                                                        //alert("only one branch selected");
                                                       }
                                                      
                                               
                                                   }
                                                         j++; 
                                      
                                               }
                                           }
                                        }


                              $('#branch_vw').val(branchdt);
                              $("#branch_vw").jqxInput({placeHolder: "Branch", height: 25, width: 200, minLength: 1, theme: theme, disabled: false});

                               var url = base_url + "dashboard/getassignedtobranch/" + branchdt;
                               
                         // prepare the data
                                    branchsource1 =
                                            {
                                                datatype: "json",
                                                datafields: [
                                                    {name: 'displayname'},
                                                    {name: 'header_user_id'}
                                                ],
                                                url: url,
                                                async: false
                                            };
                                    var branchsourcedataAdapter1 = new $.jqx.dataAdapter(branchsource1);
                                    // Create a jqxDropDownList
                                    
                                   $("#re_assign_user").jqxDropDownList({
                                        selectedIndex: -1,
                                        source: branchsourcedataAdapter1,
                                        displayMember: "displayname",
                                        valueMember: "header_user_id",
                                        width: 200,
                                        height: 25,
                                        theme: theme,
                                        placeHolder: '– Select User –'
                                    });

                                   var url = base_url + "leads/getreassignmcbranches/" + branchdt;
                                   to_branchsrc =
                                            {
                                                datatype: "json",
                                                datafields: [
                                                    {name: 'branch'},
                                                    {name: 'branch'}
                                                ],
                                                url: url,
                                                async: false
                                            };
                                    var to_branchsrcdatadapter = new $.jqx.dataAdapter(to_branchsrc);

                                    $("#to_branch").jqxDropDownList({
                                        selectedIndex: -1,
                                        source: to_branchsrcdatadapter,
                                        displayMember: "branch",
                                        valueMember: "branch",
                                        width: 200,
                                        height: 25,
                                        theme: theme,
                                        placeHolder: '– Select Branch –'
                                    });
                                   

/*                                    if (br == 'SelectBranch' || br == '')
                                        {
                                            var url_reassign = base_url + "leads/getmarketcircleloginuser";
                                        }
                                        else
                                        {
                                            var url_reassign = base_url + "leads/getmcselectedbranch/" + br;
                                        }*/
                                     var url_reassign = base_url + "leads/getmarketcircleloginuser";                                        

                                    reassign_mc_source =
                                                {
                                                    datatype: "json",
                                                    datafields: [
                                                        {name: 'mc_sub_id'},
                                                        {name: 'mc_sub_id'}
                                                    ],
                                                    url: url_reassign,
                                                    async: false
                                                };

                                        var reassignmcsource_dataAdapter = new $.jqx.dataAdapter(reassign_mc_source);


                                        

                                         $("#to_marketcircle").jqxDropDownList({
                                            selectedIndex: -1,
                                            source: reassignmcsource_dataAdapter,
                                            displayMember: "mc_sub_id",
                                            valueMember: "mc_sub_id",
                                            height: 25,
                                            width:262,
                                            theme: theme,
                                            placeHolder: '– Market Circle –'
                                        });
                                          $("#to_marketcircle").jqxDropDownList('val', sel_mc_id);
                              
                              
                   
                    //$('#hdn_branch').val(branchdt);       
                    


            var x = $(window).width() / 2 - 125;
            var y = $(window).height() / 2 - 400;
            var windowScrollLeft = $(window).scrollLeft();
            var windowScrollTop = $(window).scrollTop();
            //$("#popupWindow").jqxWindow({ position: { x: x + windowScrollLeft, y: y + windowScrollTop} });
            $("#popupWindow").jqxWindow({ position: { x: x + windowScrollLeft, y: 50} });
            $('#popupWindow').jqxWindow({theme: 'energyblue', autoOpen: false, isModal: true, width: 500, height: 200, resizable: true, modalOpacity: 0.01, title: 'Select the Executive to Re-Assign'});

             // show the popup window.
             //alert("branch_validate "+branch_validate);
             if(branch_validate==0)
             {$("#popupWindow").jqxWindow('open');}
             
             $('#re_assign_user').on('change', function (event) 
                             {
                          
                                var args = event.args;
                                var item = $('#re_assign_user').jqxDropDownList('getItem', args.index);
                                var to_branch = $('#to_branch').jqxDropDownList('val');
                                if(item==null)
                                {
                                 
                                  return false;  
                                }
                                else
                                {
                                    re_assign_user_id=item.value;
                                    
                                }
                                re_assign_branch=to_branch;
                               // alert("re_assign_branch value is "+re_assign_branch);
                                alert("re_assign_user_id value is "+re_assign_user_id);


 
                            }); 

                        $("#save").click(function (event)
                        {   
                            var data = "save=true&assignto_id="+re_assign_user_id+"&reassign_br="+re_assign_branch+"&"+$.param(dataval);
                            $.ajax({
                                dataType: 'json',
                                type: "POST",
                                url: base_url+'leads/reassignuser',
                                cache: true,
                                data: data,
                                success: function (response) {
                                    alert("Re-Assigned to User sucessfully ");
                                    $('#popupWindow').hide();
                                    window.location.href = base_url + "leads/index_search";
                                },
                                error: function (result) {
                                    alert(result.responseText);
                                }
                            });

                              
                          
                        }); // end of updateuser button click
                        $('#cancel').click(function () {
                            $('#popupWindow').jqxWindow('close');
                        });
                        
                
             } // end of else

            }); // end of reassign.on

        };
        $("#jqxgrid").jqxGrid({rendertoolbar: toolbarfunc});
        /* start for searching*/

         /*var url = base_url + "leads/getbranches";
            // prepare the data
            sourceforbranch =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'branch'},
                            {name: 'branch'}
                        ],
                        url: url,
                        async: false
                    };

            
         var branch_dataAdapter = new $.jqx.dataAdapter(sourceforbranch);
            // Create a jqxDropDownList
            $("#selectbranch").jqxDropDownList({
                selectedIndex: -1,
                source: branch_dataAdapter,
                displayMember: "branch",
                valueMember: "branch",
                height: 25,
                theme: theme,
                placeHolder: '– Select Branch –'
            });


            $("#selectbranch").jqxDropDownList('val', br);*/
            var url = base_url + "leads/getmcbranches";
            // prepare the data
            sourceforbranch =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'branch'},
                            {name: 'branch'}
                        ],
                        url: url,
                        async: false
                    };

            
         var branch_dataAdapter = new $.jqx.dataAdapter(sourceforbranch);
            // Create a jqxDropDownList
            $("#selectbranch").jqxDropDownList({
                selectedIndex: -1,
                source: branch_dataAdapter,
                displayMember: "branch",
                valueMember: "branch",
                height: 25,
                theme: theme,
                placeHolder: '– Select Branch –'
            });
             $("#selectbranch").jqxDropDownList('val', br);

           // alert("selected branch "+br);
            if (br == 'SelectBranch' || br == '')
            {
                var urlassignto = base_url + "dashboard/getusersforloginuser";
            }
            else
            {
                var urlassignto = base_url + "dashboard/getassignedtobranch/" + br;
            }

            if (br == 'SelectBranch' || br == '')
            {
                var url = base_url + "leads/getmarketcircleloginuser";
            }
            else
            {
                var url = base_url + "leads/getmcselectedbranch/" + br;
            }


            // prepare the data
            select_user_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: urlassignto,
                        async: false
                    };

            var selectuser_dataAdapter = new $.jqx.dataAdapter(select_user_source);

            select_mc_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'mc_sub_id'},
                            {name: 'mc_sub_id'}
                        ],
                        url: url,
                        async: false
                    };

            var selectmcsource_dataAdapter = new $.jqx.dataAdapter(select_mc_source);
            // Create a jqxDropDownList
         /*   $("#selectuser").jqxDropDownList({
                selectedIndex: -1,
                source: selectuser_dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width:200,
                height: 25,
                theme: theme,
                placeHolder: '– Created By –'
            });
            $("#selectuser").jqxDropDownList('val', sel_user_id);*/

             $("#marketcircle").jqxDropDownList({
                selectedIndex: -1,
                source: selectmcsource_dataAdapter,
                displayMember: "mc_sub_id",
                valueMember: "mc_sub_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Market Circle –'
            });
              $("#marketcircle").jqxDropDownList('val', sel_mc_id); 

            // Create a jqxDropDownList
            $("#assigntouser").jqxDropDownList({
                selectedIndex: -1,
                source: selectuser_dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width:262,
                height: 25,
                theme: theme,
                placeHolder: '– Assigned To –'
            });
            $("#assigntouser").jqxDropDownList('val', assign_user_id);
         // end of funtion setInitialgridsource

           $("#selectbranch").on('select', function (event) {
                if(clearbranch==0){
                  updateFilterBox(event.args.item.value);   
                }
                else{
                  clearbranch=0;   
                }
            });

            $("#to_branch").on('select', function (event) {
                if(clearbranch==0)
                {
                    if(event.args.item!=null)
                    {
                     updateFilterBoxReassign(event.args.item.value);         
                    }    
                  
                }
                else{
                  clearbranch=0;   
                }
            });

             $("#to_marketcircle").on('select', function (event) {
                if(clearbranch==0)
                {
                    if(event.args.item!=null)
                    {
                     updateMarketCirUsers(event.args.item.value);         
                    }    
                  
                }
                else{
                  clearbranch=0;   
                }
            });
            var updateMarketCirUsers = function (datafield) {
               // alert("select marketcircle "+datafield);

                 var url_reassign = base_url + "leads/getusersformarketcircle/" + datafield;

                   reassign_mc_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: url_reassign,
                        async: false
                    };

            var reassignmcsource_dataAdapter = new $.jqx.dataAdapter(reassign_mc_source);


                 $("#re_assign_user").jqxDropDownList({
                                selectedIndex: 1,
                                source: reassignmcsource_dataAdapter,
                                displayMember: "displayname",
                                valueMember: "header_user_id",
                                width: 262,
                                height: 25,
                                theme: theme,
                                placeHolder: '– Re-AssignTo –'
                            });
               }

            var updateFilterBox = function (datafield) {
            //   alert('testing'+datafield);

            var url = base_url + "dashboard/getassignedtobranch/" + datafield;

            // prepare the data
            source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: url,
                        async: false
                    };

            var dataAdapter = new $.jqx.dataAdapter(source);
            // Create a jqxDropDownList
         /*   $("#selectuser").jqxDropDownList({
                selectedIndex: -1,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width:262,
                height: 25,
                theme: theme,
                placeHolder: '– Created By –'
            });*/
             var urlmc = base_url + "leads/getmcselectedbranch/" + datafield;

            // prepare the data
            sourcemc =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'mc_sub_id'},
                            {name: 'mc_sub_id'}
                        ],
                        url: urlmc,
                        async: false
                    };
        var dataAdaptermc = new $.jqx.dataAdapter(sourcemc);                    
            // Create a jqxDropDownList
           $("#marketcircle").jqxDropDownList({
                selectedIndex: -1,
                source: dataAdaptermc,
                displayMember: "mc_sub_id",
                valueMember: "mc_sub_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Market Circle –'
            });
           
             $("#assigntouser").jqxDropDownList({
                selectedIndex: -1,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width: 262,
                height: 25,
                theme: theme,
                placeHolder: '– Assigned To –'
            });
            

             
         }   // end of updateFilterBox       

         var updateFilterBoxReassign = function (datafield) {
            //   alert('testing'+datafield);

            var url = base_url + "dashboard/getassignedtobranch/" + datafield;

            // prepare the data
            source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'displayname'},
                            {name: 'header_user_id'}
                        ],
                        url: url,
                        async: false
                    };

            var dataAdapter = new $.jqx.dataAdapter(source);
   
             var urlmc = base_url + "leads/getmcselectedbranch/" + datafield;

            // prepare the data
            sourcemc =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'mc_sub_id'},
                            {name: 'mc_sub_id'}
                        ],
                        url: urlmc,
                        async: false
                    };
        var dataAdaptermc = new $.jqx.dataAdapter(sourcemc);                    
            // Create a jqxDropDownList
           $("#to_marketcircle").jqxDropDownList({
                selectedIndex: -1,
                source: dataAdaptermc,
                displayMember: "mc_sub_id",
                valueMember: "mc_sub_id",
                height: 25,
                width:262,
                theme: theme,
                placeHolder: '– Market Circle –'
            });
           
            
             $("#re_assign_user").jqxDropDownList({
                selectedIndex: 1,
                source: dataAdapter,
                displayMember: "displayname",
                valueMember: "header_user_id",
                width: 262,
                height: 25,
                theme: theme,
                placeHolder: '– Re-AssignTo –'
            });

             
         }   // end of updateFilterBox      

       /*
            Start for status and substatus

       */
       var url = base_url + "leads/getstatus";
            // prepare the data
            status_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'leadstatus'},
                            {name: 'leadstatusid'}
                        ],
                        url: url,
                        async: false
                    };

            var status_dataAdapter = new $.jqx.dataAdapter(status_source);
            // Create a jqxDropDownList
            $("#status").jqxDropDownList({
                selectedIndex: -1,
                source: status_dataAdapter,
                displayMember: "leadstatus",
                valueMember: "leadstatusid",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select Status –'
            });
            $("#status").jqxDropDownList('val', statusid);
             var url = base_url + "leads/getinitial_lead_sub";
            // prepare the data
            substatus_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'lst_sub_id'},
                            {name: 'lst_name'}
                        ],
                        url: url,
                        async: false
                    };

            var substatus_sourcedataAdapter = new $.jqx.dataAdapter(substatus_source);


             $("#substatus").jqxDropDownList({
                selectedIndex: -1,
                source: substatus_sourcedataAdapter,
                displayMember: "lst_name",
                valueMember: "lst_sub_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select SubStatus –'
            });

           $("#substatus").jqxDropDownList('val', substatusid);

           $("#status").on('select', function (event) {
                 if(clearstatus==0){
                       updateSubstatus(event.args.item.value); 
                    }
                    else{
                       clearstatus=0;   
                    }
                 });
            

                var updateSubstatus = function (datafield) 
                {
                 //   alert('SubStatus '+datafield);
                    var url = base_url + "leads/getleadsubstatus_srch/" + datafield;
                    source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'lst_sub_id'},
                            {name: 'lst_name'}
                        ],
                        url: url,
                        async: false
                    };

            var dataAdapter = new $.jqx.dataAdapter(source);
            // Create a jqxDropDownList
            $("#substatus").jqxDropDownList({
               selectedIndex: -1,
                source: dataAdapter,
                displayMember: "lst_name",
                valueMember: "lst_sub_id",
                width: 200,
                height: 25,
                theme: theme,
                placeHolder: '– Select Status –'
            });
          }   // end of updateSubstatus       

       /*
            End of Status and Substatus
       */
         
            var url = base_url + "leads/getcustomers_all";
            // prepare the data
            customer_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'tempcustname'},
                            {name: 'customer_id'}
                        ],
                        url: url,
                        async: false
                    };

            var customer_sourcedataAdapter = new $.jqx.dataAdapter(customer_source);

        $("#customer").jqxDropDownList({
                selectedIndex: -1,
                source: customer_sourcedataAdapter,
                displayMember: "tempcustname",
                valueMember: "customer_id",
                width: 262,
                height: 25,
                theme: theme,
                placeHolder: '– Select Customer –'
            });
         $("#customer").jqxDropDownList('val', customerid);
         var url = base_url + "leads/selectproducts_all";
            // prepare the data
            product_source =
                    {
                        datatype: "json",
                        datafields: [
                            {name: 'description'},
                            {name: 'id'}
                        ],
                        url: url,
                        async: false
                    };

            var product_sourcedataAdapter = new $.jqx.dataAdapter(product_source);
        $("#product").jqxDropDownList({
                selectedIndex: -1,
                source: product_sourcedataAdapter,
                displayMember: "description",
                valueMember: "id",
                width: 198,
                height: 25,
                theme: theme,
                placeHolder: '– Select Product –'
            });
        $("#product").jqxDropDownList('val', productid);

        //$('#date_filter').jqxCheckBox({checked: true, height: 25, theme: theme});
        $("#fromdate").jqxDateTimeInput({width: '150px', height: '25px', theme: theme, formatString: 'dd-MMM-yyyy', disabled: false});
        $("#todate").jqxDateTimeInput({width: '150px', height: '25px', theme: theme, formatString: 'dd-MMM-yyyy', disabled: false});
        $("#fromdate").jqxDateTimeInput('setDate', from_date);
        $("#todate").jqxDateTimeInput('setDate', to_date);
       // $('#todate').jqxDateTimeInput('setMaxDate', new Date(03,'MAR',2015));




    });
</script>


<?php //$this->load->view('topmenus');?>
<div class="announcement noprint" id="announcement">
    <marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onmouseover="javascript:stop();" onmouseout="javascript:start();">LBL_NO_ANNOUNCEMENTS</marquee>
</div>
<input value="Leads" id="module" name="module" type="hidden">
<input value="" id="parent" name="parent" type="hidden">
<input value="<?php echo $branch; ?>" id="hdn_branch" name="hdn_branch" type="hidden">
<input value="<?php echo @$sel_user_id; ?>" id="hdn_userid" name="hdn_userid" type="hidden">
<input value="<?php echo @$sel_mc_sub_id; ?>" id="hdn_mc_sub_id" name="hdn_mc_sub_id" type="hidden">
<input value="<?php echo @$assign_user_id; ?>" id="hdn_assign_user_id" name="hdn_assign_user_id" type="hidden">
<input value="<?php echo @$from_date; ?>" id="hdn_from_date" name="hdn_from_date" type="hidden">
<input value="<?php echo @$to_date; ?>" id="hdn_to_date" name="hdn_to_date" type="hidden">
<input value="<?php echo @$statusid; ?>" id="hdn_status_id" name="hdn_status_id" type="hidden">
<input value="<?php echo @$substatusid; ?>" id="hdn_substatus_id" name="hdn_substatus_id" type="hidden">
<input value="<?php echo @$customerid; ?>" id="hdn_customerid" name="hdn_customerid" type="hidden">
<input value="<?php echo @$productid; ?>" id="hdn_productid" name="hdn_productid" type="hidden">

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
        <div class="contentsDiv span8 marginLeftTen" style="min-height:200px;">
            <div class="listViewPageDiv">
                            <form action="<?= base_url() ?>leads/index_search" method="post" name="leadsrch_form" id="leadsrch_form" class="form-horizontal recordEditView">
                                 <table border="1" cellpadding="5" cellspacing="5" style="margin-left:150px;border-width:7px; border-color:#e0e9f5;">

                                        <tr>
                                 
                                            <td><div style="float: left" id="selectbranch"></div></td>
                                            <td>
                                            <div style="float: inherit" id="selectuser"></div>
                                            <div style="float: inherit" id="marketcircle"></div>
                                            </td>

                                            <td><div style="float: inherit" id="assigntouser"></div></td>
                                        </tr>
                                        <tr>
                                             <td><div style="float: left" id="status" name="status"></div></td>
                                             <td><div style="float: inherit" id="substatus"></div></td>
                                             <td><div style="float: left" id="customer"></div></td>
                                       
                                        </tr>

                                        <tr>
                                            <td>
                                                <div style="float:left" id="product"></div>
                                            </td>
                                     
                                             <td colspan="2" align="center" style="line-height:30px;font-weight:bold;">
                                             <div id='date_filter'>Created Date</div>
                                             <label style="float:left;  padding:0px 5px;" >From </label>
                                                 <div style="float:left;"  id="fromdate" name="fromdate"></div>
                                                <label style="float:left;  padding:0px 5px;" >To </label>
                                                <div style="float:left;" id="todate" name="todate"></div>
                                            </td>

                                            
                                        </tr>
                                       
                                      
                                       
                                         <tr>
                                           <td ></td>
                                            <td>
                                                <input class="submit" id="btn_reset" name="btn_reset" type="button" value="Clear selected options" />   
                                            </td>
                                            <td >
                                            <input class="submit" id="btn_search" name="btn_search" type="button" value="Search" />    
                                            </td>
                                        </tr>

                                         
                                        
                                    </table>
                            </form>
                            <span class="btn-group">
                                    <!-- <button id="Leads_listView_basicAction_LBL_ADD_RECORD" class="btn addButton" onclick='window.location.href="leads/add"'><i class="icon-plus icon-white"></i>&nbsp;<strong>Add Lead</strong></button> -->
                                <?php if ($this->session->flashdata('message') != "") { ?>
                                    <div class="alert alert-message.success"><p style="width:709px; text-align:center;font-size:18px;"><?php echo $this->session->flashdata('message'); ?></p></div>
                                <?php } ?>


                            </span>

                </div>
                </div>
                <div class="contentsDiv span12 marginLeftTen">
                 <div  id="listViewContents" style="float: left; width:95%;">
                    <!-- Start your grid content from here -->  
                    <div id='jqxWidget' style="float: left; width:100%;">
                        <input style='margin-top: 10px;' title="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option"   alt="Currently you cannot export all the data,instead filter the data and try to use Export to Excel option" type="button" value="Export to Excel" id='excelExport' />
                        <div id="jqxgrid"></div>
                        <!-- reassign popup window start -->
                         <form action="<?= base_url() ?>leads/index_search" method="post" name="reassign_form" id="reassign_form" class="form-horizontal recordEditView">
                           <div id="popupWindow" style="display: none;">
                            <div>Re - Assign User</div>
                            <div style="overflow: hidden;">
                                <table>
                                    <tr>
                                        <td><input type="hidden" id="reassign_branch" name="reassign_branch"/></td>
                                        <td align="left">Select the Branch</td>
                                        <td align="left"><div id='to_branch' name='to_branch'></div></td>
                                    </tr>
                                    <tr>
                                        <td><input type="hidden" id="reassign_marketcircle" name="reassign_branch"/></td>
                                        <td align="left">Select the Market Circle</td>
                                        <td align="left"><div id='to_marketcircle' name='to_marketcircle'></div></td>
                                    </tr>
                                     <tr>
                                        <td><input type="hidden" id="branch_vw" name="branch_vw"/></td>
                                        <td align="left">Select User to Re-Assign:</td>
                                        <td align="left"><div id='re_assign_user' name='re_assign_user'></div></td>
                                    </tr>
                                    
                                    <tr>
                                        <td align="right"></td>
                                        <td style="padding-top: 10px;" align="right">
                                        
                                        <input id="save"  class="jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-normal jqx-fill-state-normal-energyblue" type="button" value="Update User" />
                                        <input id="cancel" class="jqx-rc-all jqx-rc-all-energyblue jqx-button jqx-button-energyblue jqx-widget jqx-widget-energyblue jqx-fill-state-normal jqx-fill-state-normal-energyblue" type="button" value="Cancel" /></td>
                                    </tr>
                                </table>
                            </div>
                       </div>
                       </form>
                     <!-- reassign popup window End -->


                    </div>
                    </div>


            </div>
        
    </div>
</div>
</div>
</div>
<input id="activityReminder" class="hide noprint" value="60" type="hidden">
<div id="userfeedback" class="feedback noprint">

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

<?php $this->load->view('include_idletimeout.php'); ?>


</body>
</html>
