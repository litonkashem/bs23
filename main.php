<?php
session_start();
require_once __DIR__ . "/classes/myclassautoloader.php";
// check session is expired or not?

$mysqlConn = new DbConn();

$thisPageDir = dirname($_SERVER['PHP_SELF']);
$mainPageDir = $thisPageDir;
$pageTittle = '';




?>

<!DOCTYPE HTML>
<html>
<head>
    <title> <?php echo $pageTittle; ?> </title>


    <!-- Template Js and CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="/css/ZERPTemplate.css?t=12345"> -->

</head>
<body class="right-sidebar">

<div class="row">
    <div class="12u skel-cell-important">
        <!-- Content -->
        <div id="content">
        <article class="last">
    <!-- HEADLINES -->
        <div class="headline1"></div>   
        <!-- <div class="headline2"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
<!-- LINKS -->
        <a href="main.php?task=task1">Task 1</a> &nbsp;|&nbsp; 
        <a href="main.php?task=task2">Task 2</a> &nbsp;|&nbsp;
        <a href="main.php">Refresh</a> &nbsp;|&nbsp;
<!--         <a href="#">vitae risus tristique</a> &nbsp;|&nbsp;
        <a href="#">tristique volutpat</a> &nbsp;|&nbsp;
        <a href="#">Cras rutrum</a> -->
        <br><br>

<!-- PAGE CONTENTS -->


        </article>
        </div>
    </div>



</div>
</div>
</div>


</body>

<?php
if($_GET['task'] == 'task1'){
    $pageTittle = 'Task 1';
    $clsObj1 = new task1();
    $resultData = $clsObj1->getData();
    $importDataTable = $clsObj1->resultToTable($resultData);
    echo $importDataTable;  
} else if($_GET['task'] == 'task2'){
    $pageTittle = 'Task 2';
    $clsObj2 = new task2();
    $resultData = $clsObj2->getTree();
}
?>

</html>










                                









<!-- 
We use below class style
=== NOW ===

***
*ABS_Object_Panel detailObj - for form


x-panel-bwrap
x-panel-tbar
x-toolbar
x-toolbar-layout-ct
x-toolbar-ct
x-grid3-scroller
x-grid3-body
x-toolbar-left
x-toolbar-right
x-panel-body x-panel-body-noheader x-panel-body-noborder

ABS_TextToSearch_Panel
ABS_accordionMenu
ABS_user-menu-tree0
ABS_user-menu-tree1
ABS_recentMenu
id="ABS_FavoritesGroup"

=== ERP ===
header-wrapper
container
header
main-wrapper
container
content
last
headline1
headline2
sidebar
footer-wrapper 

-->