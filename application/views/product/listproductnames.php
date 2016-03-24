<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" " http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd ">
<html xmlns=" http://www.w3.org/1999/xhtml ">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head>
    <title>Leads - Add New Product</title>
    <link href="<?= base_url() ?>public/css/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
    <style>
    .controls {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 9999;
    }
    .dropdowncmp {
        width: 75%;
    }
    </style>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.10.2.min.js"></script>
    <!-- sorting and filtering - start -->

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.columnsresize.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.edit.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxdropdownlist.js"></script>


    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxtooltip.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.pager.js"></script>

    
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxgrid.export.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.metrodark.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/globalization/globalize.js"></script> 
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxvalidator.js"></script> 
    <script type="text/javascript">

    $(document).ready(function ()
    {
        
        var base_url = '<?php echo site_url(); ?>';
        listallprod_groups_names();
            function listallprod_groups_names()
            {

                var url = base_url + 'leads/list_allprodgroups_names'
                var rows = {};
                jQuery.ajax({
                    dataType: "html",
                    url: url,
                    type: "POST",
                    async: false,
                    error: function (xhr, status) {
                    },
                    success: function (result) {
                        var obj = jQuery.parseJSON(result);
                        rows = obj.rows;
                    }
                });

                
                var product_names_groups_source =
                        {
                            datatype: "json",
                            datafields: [
                                {name: 'id', type: 'number'},
                                {name: 'description', type: 'text'},
                                {name: 'itemname', type: 'text'},
                            ],
                            id: 'id',
                            localdata: rows
                        };

                var dataAdapterItemNames = new $.jqx.dataAdapter(product_names_groups_source);
                $("#jqxgrid_product_names_groups").jqxGrid(
                        {
                            width: '100%',
                            source: dataAdapterItemNames,
                            theme: 'energyblue',
                            selectionmode: 'singlecell',
                            sortable: true,
                            pageable: true,
                            columnsresize: true,
                            sortable: true,
                            showfilterrow: true,
                            filterable: true,
                            columns:
                                    [
                                        {text: 'Id', dataField: 'id', width: 50},
                                        {text: 'Product Group', dataField: 'description', width: 300, height: 600},
                                        {text: 'Product Name', dataField: 'itemname', width: 300, height: 600},
                                    ]
                        });



                }


});
                </script>
                </head>
                <body>
                <style type="text/css">
                .error{ color: red; }
                </style>

                    <div id="container">
                        


                        <!-- Select itemmaster popup start -->
                        <div id="win_listproduct_names_groups" style="width:100%; height:800px;" >
                            <span id="validateProductName"></span>
                            <div style="margin: 10px">
                                <div id="jqxgrid_product_names_groups" style="width: 600px; height:600px;"></div>
                            </div>
                        </div>
                        <!-- Select Itemmaster popup end -->
                        <div><input class="submit" id="savenewitem" name="savenewitem" type="submit" value="Submit" /></div>
                        <input type="hidden" id="hdn_userid" name="hdn_userid" value="<?php echo $this->session->userdata['user_id']; ?>"/>
                        <input type="hidden" id="hdn_prod_stat" name="hdn_prod_stat" value="0"/>


                        

                        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
                    </div>


                
                </body>

                </htm>

