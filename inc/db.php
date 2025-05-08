
<?php
    $server="localHost";
    $userDB="root";
    $passDB="";
    $dbName="ohayoo";
    $statusArray= array(0=>"Offline now",1=>"Active now");
    try {
        $conn = new mysqli($server,$userDB,$passDB,$dbName);
    } catch (mysqli_sql_exception $th) {
        echo "error in database";
    }
?>
