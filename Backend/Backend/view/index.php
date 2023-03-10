<?php
    session_start();
    
    if (empty($_SESSION['mm_user'])) {
        header('Location:  ../login.php ');
        die;
    }
    
    ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    
    require_once('../configuration/Database.php');
    require_once('../model/Category.php');
    require_once('../model/Game.php');
    $category = new Category();
    $query = $category->mightyGetRecord();
    $category_count = count($query);
    
    $game = new Game();
    $game_query = $game->mightyGetRecord();
    $game_count = count($game_query);

    $featured_game_query = $game->mightyGetFeaturedRecord();
    $featured_game_count = count($featured_game_query);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>MightyGame</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="../assets/images/favicon.ico" />
        <link rel="stylesheet" href="../assets/css/backend-plugin.min.css">
        <link rel="stylesheet" href="../assets/css/backend.css?v=1.0.0">
        <link rel="stylesheet" href="../assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
        <link rel="stylesheet" href="../assets/vendor/remixicon/fonts/remixicon.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
              
    </head>
    <body class="">
        <!-- loader Start -->
        <div id="loading">
            <div id="loading-center">
            </div>
        </div>

        <!-- Wrapper Start -->
        <div class="wrapper">
            <?php require "sidebar.php"; ?>
            <?php require "header.php"; ?>
                <div class="content-page">
                <?php
                    if (isset($_GET['page']))
                    {
                        $action = $_GET['page'];
                        $url = explode("_", $action);
                        $page_link = "";
                        
                        foreach ($url as &$value) {
                            $page_link .= $value . '/';
                        }
                        $page_link = rtrim($page_link, "/");

                        if (count($url) == 1) {
                            $page_link .= '/index.php';
                        } else {
                            $page_link .= '.php';
                        }

                        try {
                            if (!file_exists($page_link)){
                                require_once("./404.php");
                            } else{
                                require_once($page_link);
                            }
                        } catch (Exception $e) {
                            require_once("./404.php");
                        }
                    } else {
                ?>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="d-flex align-items-center justify-content-between welcome-content">
                                <div class="navbar-breadcrumb">
                                    <h4 class="mb-0 font-weight-700">Welcome To Dashboard</h4>
                                </div>
                                <div class="">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card card-block card-stretch card-height">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="mm-cart-image text-danger">
                                                    <svg class="svg-icon"  width="50" height="52" id="h-01" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="4" y="4" width="6" height="6" rx="1" />
                                                        <rect x="14" y="4" width="6" height="6" rx="1" />
                                                        <rect x="4" y="14" width="6" height="6" rx="1" />
                                                        <rect x="14" y="14" width="6" height="6" rx="1" />
                                                    </svg>
                                                </div>

                                                <div class="mm-cart-text">
                                                    <h2 class="font-weight-700"><?= $category_count ?></h2>
                                                    <p class="mb-0 text-danger">Total Category</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card card-block card-stretch card-height">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="mm-cart-image text-warning">
                                                    <svg class="svg-icon svg-warning mr-4" width="50" height="52"  id="h-04" 
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="2" y="6" width="20" height="12" rx="2" />
                                                        <path d="M6 12h4m-2 -2v4" />
                                                        <line x1="15" y1="11" x2="15" y2="11.01" />
                                                        <line x1="18" y1="13" x2="18" y2="13.01" />
                                                    </svg>
                                                </div>
                                                <div class="mm-cart-text">
                                                    <h2 class="font-weight-700"><?= $game_count ?></h2>
                                                    <p class="mb-0 text-success">Total Game</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card card-block card-stretch card-height">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="mm-cart-image text-primary">                                                    
                                                    <svg  class="svg-icon svg-success mr-4" width="50" height="52" id="h-03" 
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <rect x="2" y="6" width="20" height="12" rx="2" />
                                                        <path d="M6 12h4m-2 -2v4" />
                                                        <line x1="15" y1="11" x2="15" y2="11.01" />
                                                        <line x1="18" y1="13" x2="18" y2="13.01" />
                                                    </svg>
                                                </div>
                                                <div class="mm-cart-text">
                                                    <h2 class="font-weight-700"><?= $featured_game_count ?></h2>
                                                    <p class="mb-0 text-primary">Total Featured Game</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- Wrapper End -->
        <?php require "footer.php"; ?>

    <?php
        if (isset($_SESSION['success'])) {
            // echo $_SESSION['success'];
    ?>
        <script>
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                type: 'success',
                html: '<?= $_SESSION['success'] ?>'
            });
        </script>
        <?php
            unset($_SESSION['success']);
        }
      ?>

      <?php
        if (isset($_SESSION['error'])) {
      ?>
        <script>
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }).fire({
                type: 'error',
                title: '<?= $_SESSION['error'] ?>'
            });
        </script>
      <?php
      }
        unset($_SESSION['error']);
      ?>
    </body>

</html>