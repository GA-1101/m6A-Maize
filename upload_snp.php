<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="./img/RNA.png" type="image/icon type">
    <title>Database</title>
    <meta name="author" content="GA-1101">
    <meta content="Maize m6A mutate sites analysed by Weakly supervised model.">
    
    <link rel="stylesheet" href="./file/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./file/337bootstrap.min.css">

    <link rel="stylesheet" href="./file/new_ui/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="./file/new_ui/jquery-3.5.1.min.js"></script>
    <script src="./file/new_ui/337bootstrap.min.js"></script>
    <script src="./file/new_ui/index.js"></script>
    <script src="./file/new_ui/jquery-ui.js"></script>
    <link rel="stylesheet" href="./file/new_ui/jquery-ui.css">
    <link rel="stylesheet" href="./file/timer.snp_process.css">

</head>

<style>
    table, th, td {
        border: 1px solid #ddd;
    }

    .container-fluid {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        position: fixed;
        right: 20px;
    }
</style>

<body style="background-color:#EEEEEE">
    <!-- Navbar 开始-->
    <nav class="navbar navbar-inverse navbar-fixed-top" style="border-bottom-color:#f2ce82; box-shadow:1px 1px 0px 1px #303C51; background-color:#2D3C53">
        
        <div class="container">
          <!-- Left -->
            <div class="navbar-header">
                <a class="navbar-brand" style="font-size:32px; color:#FFFFFF"><b>m6A-Maize</b></a>
            </div>
            <!-- Right -->
            <div class="navbar-collapse" style="font-size:20px">
                <ul id ="nav-txt"class="nav navbar-nav navbar-right">
                  <li><a href="index.html" style="color:#EEEEEE"><i class="fa fa-home" aria-hidden="true"></i><b> Home</b></a></li>
                  <li><a href="sequence.html" style="color:#EEEEEE"><i class="fa fa-server" aria-hidden="true"></i><b> Predictor</b></a></li>
                  <li><a href="gain_unlabel.html" style="color:#EEEEEE"><i class="fa fa-database" aria-hidden="true"></i><b> Database</b></a></li>
                  <li><a href="help.html" style="color:#EEEEEE"><i class="fa fa-question-circle" aria-hidden="true"></i><b> Help</b></a></li>
                  <li><a href="contact.html" style="color:#EEEEEE"><i class="fa fa-comment" aria-hidden="true"></i><b> Contact</b></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar 结束 -->

<?php
ini_set('display_errors', false);
session_start();
$_SESSION['last_action'] = time();
error_reporting(0);
$ID = $_GET['ID'];
?>

    <script src="./file/jquery-3.3.1.min.js"></script>
    <script src="./file/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery.csv.min.js"></script>
    <script src="./file/jquery.dataTables.min.js"></script>
    <script src="./file/dataTables.bootstrap4.min.js"></script>
    <script src="./js/csv_to_html_table.js"></script>

<?php
// result
if(file_exists("/var/www/html/maize/job2/".$ID."/".$ID."_upload.json")){
  echo "<head>";
  echo "<meta http-equiv='refresh' content='0; url=snpresult.php?ID=".$ID."'>";
  echo "</head>";
  $stop = 1;
}else{
  $stop = 0;
  echo "<div class='container' style='margin-top: 90px;'>";
  echo "<div class='thumbnail' style='border-left-color:#303C51;border-left-width:4px'>";
  echo "<h3 style='color:#964B29'><b>Your SNP Data has been uploaded Successfully!</b></h3>";
  echo "<h3 style='color:#964B29'><b>Data Processing.... (Estimated Running Time Within 2 Minutes)</b></h3>";
  echo "<div class='spinner'>";
  echo "<div class='bounce1'></div>";
  echo "<div class='bounce2'></div>";
  echo "<div class='bounce3'></div>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
  echo "<br>";
  echo "<br>";
  echo "<div class='col-md-12'></div>";
}     
?>
</body>

    <!-- footer-->
    <nav class="navbar navbar-inverse navbar-bottom" style="background-color:#EEEEEE; border:0px">
        <div class="container">
            <div style="text-align:center; font-family:Times; font-size:16px; margin-top:20px ">
                <strong>Maize m6A &copy; The Meng Lab (2021). All Rights Reserved</strong>
            </div>
        </div>
    </nav>

</html>

<script language='javascript' type="text/javascript">

var ID = '<?php echo($ID);?>';

var stop = '<?php echo($stop);?>';

if(stop == 0){
setInterval("location.reload()",2000);
}

if(stop == 1){
clearInterval("location.reload()",2000);
}

</script>