<?php
    require_once "../../vendor/autoload.php";
    use classes\{Comment,FuncGlobal as FG};
    if (isset($_GET["post_id"])){
        $n = Comment::count_post_comments(($_GET["post_id"]));
        $n = FG::sanitize_num($n);
        echo $n;
    }
