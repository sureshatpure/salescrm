<!DOCTYPE html>
<html>
    <head>
        <title>Leads</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon">
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
        <link rel="stylesheet" href="<?= base_url() ?>public/css/datepicker.css" media="screen" type="text/css">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/style.css" type="text/css" media="screen">
        <link rel="stylesheet" href="<?= base_url() ?>public/css/jquery.css" type="text/css" media="screen">
        <style type="text/css">@media print {.noprint { display:none; }}</style>
        <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.energyblue.css" type="text/css" />
        <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.black.css" type="text/css" />
        

        


    </head>
    <body>

        <div id="page"><!-- container which holds data temporarly for pjax calls -->
            <div id="pjaxContainer" class="hide noprint"></div>
            <div class="navbar navbar-fixed-top  navbar-inverse noprint" style="min-width:1050px">
                <?php $this->load->view('menu'); ?> 
