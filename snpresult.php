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

    <div class="container" style="margin-top: 90px;">
        <!-- web server-->
        <div class="thumbnail" style="padding-left:20px;padding-right:20px;padding-top:18px;color:black; border-left-color:#000557;border-left-width:5px">

            <main class="row">
            <h1 style='color:#964B29'>Prediction Result:</h1>

            <table id='table' class='table table-bordered'>
                <thead>
                <tr>
                    <th scope='col'>Job ID</th>
                    <th scope='col'>Status</th>
                    <th scope='col'>Result</th>
                    <th scope='col'>Download</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td style='color:#964B29'><?php echo $ID?></td>
                    <td id='Status1' style='color:#964B29'>Completed</td>
                    <td><font style='color:#964B29'>Low-confidence-level Loss</font></td>
                    <td><button id='loss-unlabel' type='button'class='btn btn-info'> 
                        <a style='color:#FFFFFF;text-decoration:none' href = "./job2/<?php echo $ID?>/download/Results/low-confidence-level_loss.csv" download = "" style='text-decoration:none'>Report</a></button> 
                </tr>
                <tr>
                    <td style='color:#964B29'><?php echo $ID?></td>
                    <td id='Status2' style='color:#964B29'>Completed</td>
                    <td><font style='color:#964B29'>Low-confidence-level Gain</font></td>
                    <td><button id='gain-unlabel' type='button'class='btn btn-info'> 
                        <a style='color:#FFFFFF;text-decoration:none' href = "./job2/<?php echo $ID?>/download/Results/low-confidence-level_gain.csv" download = "" style='text-decoration:none'>Report</a></button>
                </tr>
                <tr>
                    <td style='color:#964B29'><?php echo $ID?></td>
                    <td id='Status3' style='color:#964B29'>Completed</td>
                    <td><font style='color:#964B29'>High-confidence-level Loss</font></td>
                    <td><button id='loss-label' type='button'class='btn btn-info'> 
                        <a style='color:#FFFFFF;text-decoration:none' href = "./job2/<?php echo $ID?>/download/Results/high-confidence-level_loss.csv" download = "" style='text-decoration:none'>Report</a></button>
                </tr>
                </tbody>
            </table>
            </main>
        </div>
    </div>
    <script src="./file/jquery-3.3.1.min.js"></script>
    <script src="./file/bootstrap.bundle.min.js"></script>
    <script src="./js/jquery.csv.min.js"></script>
    <script src="./file/jquery.dataTables.min.js"></script>
    <script src="./file/dataTables.bootstrap4.min.js"></script>
    <script src="./js/csv_to_html_table.js"></script>

    <script>
        var id = "<?php echo $ID?>";
        var csvPath1 = "job2/" + id + "/download/Results/low-confidence-level_loss.csv";
        var csvPath2 = "job2/" + id + "/download/Results/low-confidence-level_gain.csv";
        var csvPath3 = "job2/" + id + "/download/Results/high-confidence-level_loss.csv";
        
        console.log(csvPath1);
        console.log(csvPath2);
        console.log(csvPath3);
    </script>


<?php
// 判断文件是否存在
if(file_exists("/var/www/html/maize/job2/".$ID."/download/Results/low-confidence-level_loss.csv") == false){
    echo "<script>$('#loss-unlabel').attr('disabled',true);</script>";
    echo "<script>$('#Status1').append(' (No Data)');</script>";
}
if(file_exists("/var/www/html/maize/job2/".$ID."/download/Results/low-confidence-level_gain.csv") == false){
    echo "<script>$('#gain-unlabel').attr('disabled',true);</script>";
    echo "<script>$('#Status2').append(' (No Data)');</script>";
}
if(file_exists("/var/www/html/maize/job2/".$ID."/download/Results/high-confidence-level_loss.csv") == false){
    echo "<script>$('#loss-label').attr('disabled',true);</script>";
    echo "<script>$('#Status3').append(' (No Data)');</script>";
}
?>

    <!-- footer-->
    <nav class="navbar navbar-inverse navbar-bottom" style="background-color:#EEEEEE; border:0px">
        <div class="container">
            <div style="text-align:center; font-family:Times; font-size:16px; margin-top:20px ">
                <strong>Maize m6A &copy; The Meng Lab (2021). All Rights Reserved</strong>
            </div>
        </div>
    </nav>

</body>

</html>
