<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Planning App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- @todo: fill with your company info or remove -->
        <meta name="description" content="">
        <meta name="author" content="Cubes School">
        <!-- Bootstrap CSS -->
        <link href="/public/skins/plugins/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="/public/skins/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Theme style -->
        <link href="/public/skins/assets/css/theme-style.css" rel="stylesheet">
        <!-- Your custom override -->
        <link href="/public/skins/assets/css/custom-style.css" rel="stylesheet">
        <!-- @option: Colour skins, choose from: 1. colour-blue.css 2. colour-red.css 3. colour-grey.css 4. colour-purple 5. colour-green.css Uncomment line below to enable -->
        <link href="/public/skins/assets/css/colour-blue.css" rel="stylesheet" id="colour-scheme">
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="plugins/html5shiv/dist/html5shiv.js"></script>
        <script src="plugins/respond/respond.min.js"></script>
        <![endif]-->
        <!-- Le fav and touch icons - @todo: fill with your icons or remove -->
        <link rel="shortcut icon" href="/public/skins/assets/img/favicons/96x96.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/public/skins/assets/img/favicons/114x114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/public/skins/assets/img/favicons/72x72.png">
        <link rel="apple-touch-icon-precomposed" href="/public/skins/assets/img/favicons/96x96.png">
        <link href='https://fonts.googleapis.com/css?family=Monda:400,700' rel='stylesheet' type='text/css'>
        <?php  
        foreach ($this->customCss as $customCss) {
            echo $customCss;
            echo '<br>';
        }
        ?>
    </head>

    <!-- ======== @Region: body ======== -->
    <body class="has-navbar-fixed-top has-highlighted">

        <!-- ======== @Region: #navigation ======== -->
        <div id="navigation" class="wrapper">
            <!--Branding & Navigation Region-->
            <div class="navbar navbar-fixed-top" id="top">
                <div class="navbar-inner">
                    <div class="inner">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle btn btn-navbar" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                                <a class="navbar-brand" href="#" title="Home">
                                    <h1>
                                        Planning App
                                    </h1>
                                    <span>Virtuagym</span> 
                                </a>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-right" id="main-menu">
                                    <li>
                                        <a href="/public/plan">Plans</a>
                                    </li>
                                    <li>
                                        <a href="/public/user">Users</a>
                                    </li>
                                    <li>
                                        <a href="/public/exercise">Exercises</a>
                                    </li>
                                </ul>
                            </div>
                            <!--/.nav-collapse -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once "../app/Views/" . $view . ".php"; ?>
        
        <!-- FOOTER -->

        <!-- ======== @Region: #footer ======== -->
        <footer id="footer">
            <div class="container">
                <div class="row">
                    <!--@todo: replace with company copyright details-->
                    <div class="subfooter">
                        <div class="col-md-5">
                            <p><a href="http://virtuagym.com/">Virtuagym</a> | Copyright 2019 &copy;</p>
                        </div>
                        <div class="col-md-7 col social-media">
                            <!--@todo: replace with company social media details-->
                            <a href="https://www.instagram.com/virtuagym"><i class="fa fa-instagram"></i> Instagram</a>
                            <a href="https://www.facebook.com/virtuagym"><i class="fa fa-facebook"></i> Facebook</a>
                            <a href="https://www.linked.com/company/virtuagym"><i class="fa fa-linkedin"></i> LinkedIn</a>
                            <a href="https://www.youtube.com/channel/UC5S3jd-iRsBdhyFr0qolq6A"><i class="fa fa-youtube"></i> Youtube</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!--@todo: replace with company copyright details-->
                    <div class="subfooter">

                    </div>
                </div>
            </div>
        </footer>
        <!-- Scripts -->
        <script src="/public/skins/assets/js/jquery.js"></script>
        <!-- Bootstrap Javascript -->
        <script src="/public/skins/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Custom Scripts -->
        <script src="/public/skins/assets/js/script.js" type="text/javascript"></script>
        <?php  
        foreach ($this->customJs as $customJs) {
            echo $customJs;
            echo '<br>';
        }
        ?>
    </body>
    
</html>