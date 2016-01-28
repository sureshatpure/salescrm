<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta name="msapplication-tap-highlight" content="no" />
    <title id='Description'>JavaScript Input - Mobile Example</title>
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/demo.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.windowsphone.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.blackberry.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/jqwidgets/styles/jqx.android.css" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>/public/jqwidgets/styles/jqx.mobile.css" type="text/css" />

    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/simulator.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript">
        $(document).ready(function () {
            var theme = prepareSimulator("input");
            var countries = new Array("Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Belgium", "Belize", "Benin", "Iceland", "India", "Indonesia", "Vietnam", "Yemen", "Zambia", "Zimbabwe");
            $("#input").jqxInput({ theme: theme, placeHolder: "Enter a Country", height: 35, width: '80%', minLength: 1, source: countries });
            initSimulator("input");
        });
    </script>
</head>
<body class='default'>
    <div id="demoContainer" class="device-mobile">
        <div id="container" class="device-mobile-container">
            <div style='margin-left: 10%; margin-top: 100px;'>
                Type your country into the field:
            </div>
             <input style='margin-left: 10%; margin-top: 10px;' type="text" id="input"/>
   
        </div>
    </div>
</body>
</html>
