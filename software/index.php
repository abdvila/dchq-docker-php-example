<?php
require "class/Persistence.php";

//Populate database on first run
$pers = new Persistence();
$pers->populateDatabase();

if (isset($_POST["fName"]) && isset($_POST["lName"])) {
    if (!empty($_POST["fName"]) && !empty($_POST["lName"])) {
        $pers->save($_POST["fName"], $_POST["lName"]);
    }
}

if (isset($_POST["btnRemove"])) {
    $pers->delete($_POST["btnRemove"]);
}
?>
<!DOCTYPE html>
<!--
*File: index.php
*
*Copyright (c) 2015 Marcos Macedo <marcos@softdev.science>
*Developed for client at Upwork.com
*For usage permissions of this file please contact at marcos@softdev.science or the corresponding copyright holder.  
-->

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css" 
        <script src="js/bootstrap.min.js" type="text/javascript"></script>      
        <title>Name Directory</title>
    </head>
    <body>
        <form action="index.php" method="post">
            <div class="container">
                <div class="row" style="margin-bottom:3em;margin-top:3em">

                    <div class="col-md-4">              
                    </div>
                    <div class="col-md-4">
                        <form action="index.php" method="post">
                            First Name: <input type="text" class="form-control" placeholder="First name" name="fName"><br>
                            Last Name: <input type="text" class="form-control" placeholder="Last name" name="lName"><br>
                            <button type="submit" class="btn btn-primary">Add Name</button>              
                    </div>
                    <div class="col-md-4">
                    </div>

                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">              
                    </div>
                    <div class="col-md-6">
                        <h2>Name directory</h2>
                        <hr>
                        <?php
                        $persistence = new Persistence();
                        $listDirectories = $persistence->getList();

                        if (empty($listDirectories)) {
                            echo('<div class="alert alert-info" role="alert">No names found.</div>');
                        }
                        ?>
                        <table class="table table-stripped">
                            <tr>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            $config = Config::getInstance();

                            if ($config->getProvider() == "oraclexe") {
                                //Oracle doesn't use PDO driver..
                                $list = $persistence->getList();
                                while (($row = oci_fetch_array($list, OCI_BOTH)) != false) {
                                    echo("<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td><button name='btnRemove' class='btn btn-xs btn-danger' type='submit' value='" . $row[0] . "'>Remove</button></td>");
                                }
                            } else {
                                foreach ($persistence->getList() as $row) {
                                    //PostgreSQL doesn't like upper case column names..
                                    if ($config->getProvider() == "postgresql") {
                                        echo("<tr><td>" . $row['id'] . "</td><td>" . $row['firstname'] . "</td><td>" . $row['lastname'] . "</td><td><button name='btnRemove' class='btn btn-xs btn-danger' type='submit' value='" . $row['id'] . "'>Remove</button></td>");
                                    } else {
                                        echo("<tr><td>" . $row['ID'] . "</td><td>" . $row['FIRSTNAME'] . "</td><td>" . $row['LASTNAME'] . "</td><td><button name='btnRemove' class='btn btn-xs btn-danger' type='submit' value='" . $row['ID'] . "'>Remove</button></td>");
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="col-md-3">              
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
