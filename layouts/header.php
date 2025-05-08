<?php
    $root = $GLOBALS["config"]["root"]["path"];
    $proj_name = $GLOBALS["config"]["root"]["project_name"];
      echo <<<HE
        <meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>ohayoo</title>
		<link rel="apple-touch-icon" href="$root/asset/img/logo.svg">
        <link rel="shortcut icon" href="$root/asset/img/logo.svg" type="image/x-icon">   
		<link rel="stylesheet" href="$root/asset/css/simple-line-icons.css" />  
		<link rel="stylesheet" href="$root/asset/css/bootstrap.min.css" />
        <link rel="stylesheet" href="$root/asset/css/bootstrap-icons.min.css" />       
        <link rel="stylesheet" href="$root/asset/css/all.min.css" />
        <link rel="stylesheet" href="$root/asset/css/video.css" /> 
        <link rel="stylesheet" href="$root/asset/css/main.css" /> 
		
            <div class="loader">
            <progress-ring stroke="4" radius="60" progress="0"></progress-ring>
             <div class="logo">
                <img src="$root/asset/img/logo-alt.svg" alt="logo">
                <h2>Ohayoo</h2>
            </div>   
            </div>
            <div class="preloader"></div>
		<script src="$root/asset/js/jquery.min.js"></script>     
        <script src="$root/asset/js/sweetalert.js"></script>		
        <script src="$root/asset/js/loader.js"></script>
        
        <script src="$root/asset/js/bootstrap.bundle.min.js"></script>             
        <script src="$root/asset/js/config.js" ></script>
        <script src="$root/asset/js/global.js" defer></script>
        <script src="$root/asset/js/video.min.js"></script>		
HE;
?>