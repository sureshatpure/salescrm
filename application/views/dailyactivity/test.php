<!DOCTYPE html>
<html>
    <head>
        <title>Leads</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/chosen.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery-ui-1.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/select2.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/bootstrap.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/styles.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/validationEngine.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/select2.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/guiders-1.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery_002.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery_003.css">
        <link rel="stylesheet" media="screen" type="text/css" href="<?= base_url() ?>public/css/datepicker.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/style.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery.css" type="text/css" media="screen">
        <script type="text/javascript" src="<?= base_url() ?>public/js/jquery_007.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>public/jquery/jqueryui.js"></script>
    </head>
    <body>

                <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
                <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />

                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/gettheme.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxnumberinput.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxwindow.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcalendar.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script> 
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdatetimeinput.js"></script>


                <!-- sorting and filtering - start -->
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcombobox.js"></script>


                <!-- sorting and filtering - end -->
                <!-- paging - start -->
                <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>
                <!-- paging - end -->
                <!-- End of jqwidgets -->
                <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
                <script type="text/javascript">
          $(document).ready(function ()
                    {
                    var  Results ={

                                     
                               initResultsEditor: function(row){
                                  var data = $('.results').jqxGrid('getrowdata', row);
                                  if(data.result_type === 'Value') this.columntype = 'textbox';
                                  else if(data.result_type === 'Select Many') this.columntype = 'dropdownlist';
                                  else if(data.result_type === 'Select One') this.columntype = 'dropdownlist';
                                  else if(data.result_type === 'Narrative') this.columntype = 'numberinput';
                               },
                               resultsEditor: function(row, cellvalue, editor){
                                  var data = $('.results').jqxGrid('getrowdata', row);
                                  switch(data.result_type){
                                     case 'Value':
                                        /*Nothing to do here... its handled well by default*/
                                        editor.jqxNumberInput({ decimalDigits: 0, digits: 3 });
                                     break;
                                     case 'Narrative':
                                        var offset = $(".results").offset();
                                        $("#popupWindow").jqxWindow({width: 350, height: 220, position: { x: parseInt(offset.left) + 60, y: parseInt(offset.top) + 60} });
                                        $("#popupWindow .content").html("<textarea cols='45' rows='7'></textarea>");
                                        $("#popupWindow").jqxWindow('show');
                                     break;
                                     case 'Select Many':
                                        editor.jqxDropDownList({ source: data.possible_options});
                                     break;
                                     case 'Select One':
                                        editor.jqxDropDownList({ source: data.possible_options, checkboxes: true, groupName: 'select_one'});
                                     break;
                                     case 'Cascaded':
                                       
                                     break;
                                     default:
                                        alert('Unbound result type.... I dont know how to handle this!!!');
                                     break;
                                  }
                               },
                               renderUnits: function(row, columnfield, value, defaulthtml, columnproperties){
                                  var data = $('.results').jqxGrid('getrowdata', row);
                                  if(data.result_type === 'Value' && data.result !== ''){
                                     defaulthtml = defaulthtml.replace(/>.+<\/div>/ , '>'+ data.result +' '+ data.units +'</div>');
                                  }
                                  return defaulthtml;
                               }
                              };



            var data = new Array();
            var firstNames =
            [
                "Andrew", "Nancy", "Shelley", "Regina", "Yoshi", "Antoni", "Mayumi", "Ian", "Peter", "Lars", "Petra", "Martin", "Sven", "Elio", "Beate", "Cheryl", "Michael", "Guylene"
            ];
            var lastNames =
            [
                "Fuller", "Davolio", "Burke", "Murphy", "Nagase", "Saavedra", "Ohno", "Devling", "Wilson", "Peterson", "Winkler", "Bein", "Petersen", "Rossi", "Vileid", "Saylor", "Bjorn", "Nodier"
            ];
            var productNames =
            [
                "Black Tea", "Green Tea", "Caffe Espresso", "Doubleshot Espresso", "Caffe Latte", "White Chocolate Mocha", "Cramel Latte", "Caffe Americano", "Cappuccino", "Espresso Truffle", "Espresso con Panna", "Peppermint Mocha Twist"
            ];
            var priceValues =
            [
                "2.25", "1.5", "3.0", "3.3", "4.5", "3.6", "3.8", "2.5", "5.0", "1.75", "3.25", "4.0"
            ];
            for (var i = 0; i < 200; i++) {
                var row = {};
                var productindex = Math.floor(Math.random() * productNames.length);
                var price = parseFloat(priceValues[productindex]);
                var quantity = 1 + Math.round(Math.random() * 10);
                row["code"] = firstNames[Math.floor(Math.random() * firstNames.length)];
                row["name"] = lastNames[Math.floor(Math.random() * lastNames.length)];
                row["result_type"] = (i % 2 ==0 ) ? 'Value' : 'Select Many';

                row["possible_options"] = price;
                row["units"] = quantity;
                row["time"] = quantity;
                row["updated_by"] = price * quantity;
                data[i] = row;
            }
            var source =
            {
                localdata: data,
                datatype: "array",
                datafields:
                [
                    { name: 'code', type: 'string' },
                    { name: 'name', type: 'string' },
                    { name: 'result_type', type: 'string' },
                    { name: 'possible_options', type: 'number' },
                    { name: 'units', type: 'number' },
                    { name: 'time', type: 'number' },
                    { name: 'updated_by', type: 'number' }
                ]
            };
            var resultsAdapter = new $.jqx.dataAdapter(source);
                $(".results").jqxGrid({
              
              height: 340,
              source: resultsAdapter,
              theme: 'energyblue',
              rowdetails: true,
              rowsheight: 30,
              /*selectionmode: 'singlecell',*/
              editmode: 'dblclick',
              editable: true,
              columns: [
                 {text: 'Code', datafield: 'code', width: 70, editable: false},
                 {text: 'Name', datafield: 'name', width: 140, editable: false},
                 {text: 'Result', datafield: 'result', width: 70, cellbeginedit: Results.initResultsEditor, createeditor: Results.resultsEditor, cellsrenderer: Results.renderUnits},
                 {text: 'Result Type', datafield: 'result_type', width: 100, hidden: false},
                 {text: 'Options', datafield: 'possible_options', width: 10, hidden: true},
                 {text: 'Units', datafield: 'units', width: 10, hidden: true},
                 {text: 'Time Updated', datafield: 'time', width: 100, editable: false},
                 {text: 'Updated By', datafield: 'updated_by', width: 110, editable: false}
              ]
                 });
           
    });
    </script>
</head>
<body class='default'>
    <div id='jqxWidget'>
        <div id="jqxgrid" class="results">
        </div>
       
    </div>
</body>
</html>