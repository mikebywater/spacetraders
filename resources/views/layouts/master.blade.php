<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>Space Traders</title>

    <meta name="description" content="A futuristic dashboard by pixelcave. You can download it from https://pixelcave.com">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Favicons (just the basics for now, check out http://realfavicongenerator.net/ for all available) -->
    <link rel="shortcut icon" href="assets/img/favicon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">

    <!-- Web fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:200,300,400,600,700&Open+Sans:300,400,600,700">

    <!-- Bootstrap and Ares CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/ares.css">
</head>
<body>
<!-- Page Container -->
<!--
    Available Classes: 'modern-sf', 'vintage-sf', 'interstellar-sf'
-->
<div id="page-container" class="modern-sf">
    @yield('content')
    <!-- END Main Content -->
</div>
<!-- END Page Container -->

<!-- Scripts -->
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/plugins/jquery.appear.min.js"></script>
<script src="assets/js/plugins/jquery.countTo.min.js"></script>
<script src="assets/js/plugins/jquery.easypiechart.min.js"></script>
<script src="assets/js/ares.js"></script>

<!-- Page JS Code -->
<script>
    jQuery(function(){
        // Init page helpers (Appear + CountTo + Easy Pie Chart plugins)
        Ares.initHelpers(['appear', 'appear-countTo', 'easy-pie-chart']);
    });
</script>
</body>
</html>

