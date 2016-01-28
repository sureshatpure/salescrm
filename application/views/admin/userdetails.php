<!DOCTYPE html>
<html lang="en">
<head>
    <title id='Description'>LMS - Manage Users designations</title>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>

    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>  
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // prepare the data
            var data =  <?php echo $data; ?>;
            var g_header_id;
            var g_old_desig;
            var g_new_desig;
            var source =
            {
                localdata: data,
                datatype: "json",
                sortcolumn: 'duser',
                sortdirection: 'asc',
                updaterow: function (rowid, rowdata, commit) {
                    // synchronize with the server - send update command
                    // call commit with parameter true if the synchronization with the server is successful 
                    // and with parameter false if the synchronization failder.
                    commit(true);
                },
                datafields:
                [
                    { name: 'duser', type: 'string' },
                    { name: 'empcode', type: 'string' },
                    { name: 'aliasloginname', type: 'string' },
                    { name: 'header_user_id', type: 'number' },
                    { name: 'designation', type: 'string' },
                    
                ],
                pagenum: 0,
                pagesize: 50,
                pager: function (pagenum, pagesize, oldpagenum) {
                // callback called when a page or page size is changed.
                }
            };

            var dataAdapter = new $.jqx.dataAdapter(source);
            // initialize jqxGrid
            $("#jqxgrid_users").jqxGrid(
            {
                width: 750,
                source: dataAdapter,
                editable: true,
                enabletooltips: true,
                sortable: true,
                pageable: true,
                columnsresize: true,
                sortable: true,
                showfilterrow: true,
                filterable: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'Duser',datafield: 'duser', columntype: 'textbox', width: 120 },
                  { text: 'Emp Code', datafield: 'empcode', columntype: 'textbox', width: 120 },
                  { text: 'LoginName', datafield: 'aliasloginname', columntype: 'textbox', width: 200 },
                  { text: 'Header Id', datafield: 'header_user_id', columntype: 'textbox', width: 75},
                  { text: 'Designation', columntype: 'dropdownlist', datafield: 'designation',width: 195,
                      initeditor:function(row, cellvalue, editor){
                                   g_header_id = $('#jqxgrid_users').jqxGrid('getcellvalue', row, "header_user_id");
                                 

                                },
                      createeditor: function (row, cellvalue, editor) {
                                    editor.jqxDropDownList({source: ["Executive", "Branch Manager", "Regional Manager","Co-ordinator"],autoDropDownHeight: true});
                                  }
                   }
                  
                ]
            });
            // events
            $("#jqxgrid_users").on('cellbeginedit', function (event) {
                var args = event.args;
                $("#cellbegineditevent").text("Event Type: cellbeginedit, Column: " + args.datafield + ", Row: " + (1 + args.rowindex) + ", Value: " + args.value);
                g_old_desig =args.value;
            });
            $("#jqxgrid_users").on('cellendedit', function (event) {
                var args = event.args;
               
                $("#cellendeditevent").text("Event Type: cellendedit, Column: " + args.datafield + ",Row: " + (1 + args.rowindex) + ", Value: " + args.value);
                  //alert("value "+args.value);
                  g_new_desig =args.value;
                  if(args.value!="" && g_old_desig!= g_new_desig)
                  {
                      $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: 'updateuser_designation/'+g_header_id+'/'+args.value,
                        cache: true,
                        data: data,
                        success: function (response) {
                           // alert("Record Updated sucessfully ");
                             alert(result.responseText);
                            //$('#addWindow').hide();
                            //window.location.href = base_url + "dailyactivity";
                        },
                        error: function (result) {
                            alert(result.responseText);
                        }
                    });
                  }
                  else
                  {
                     //alert("No value to update ");
                  }
                  
                 
            });
        });
    </script>
</head>
<body class='default'>
        <div style="margin-left: 10%;"><h1><?php echo "Manage User Designation"; ?></h1>
<a href="<?= base_url() ?>admin/logout">Logout</a>&nbsp;&nbsp;&nbsp;<a href="<?= base_url() ?>admin/manageusers">Manage Users</a></div>
    <p></p>


    <div id='jqxWidget' style="margin-left:10%;">
        <div id="jqxgrid_users"></div>
        <div style="font-size: 12px; font-family: Verdana, Geneva, 'DejaVu Sans', sans-serif; margin-top: 30px; display:none;">
            <div id="cellbegineditevent"></div>
            <div style="margin-top: 10px;" id="cellendeditevent"></div>
       </div>
    </div>
</body>
</html>