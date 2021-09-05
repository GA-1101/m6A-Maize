<?php
session_start();
error_reporting(0);

//create a jobID
//create a jobID
$str="QAZWSXEDCRFVTGBYHNUJMIKOLP1234567890qazwsxedcrfvtgbyhnujmikolp";
$jobID=substr(str_shuffle($str),26,10);
echo $jobID;

//make a foler for each jobID
if($jobID){
    mkdir("/var/www/html/maize/job2/".$jobID, 0777, true);
    mkdir("/var/www/html/maize/job2/".$jobID."/download", 0777, true);
};

// upload parameters
if($_POST['text']){
    $text = $_POST['text'];
    $myfile = fopen("/var/www/html/maize/job2/".$jobID."/".$jobID.".csv", "w") or die("Unable to open file!");
    $txt = $text."\n";
    fwrite($myfile, $text);
    fclose($myfile);
    $file="/var/www/html/maize/job2/".$jobID."/".$jobID.".csv";
  }else{
    $text = 0;
  };

  if($_FILES["file"]){
    $upload = 1;
    $storeDir = "/var/www/html/maize/job2/".$jobID."/";
    move_uploaded_file($_FILES['file']['tmp_name'], $storeDir.$jobID.".csv");
    $file="/var/www/html/maize/job2/".$jobID."/".$jobID.".csv";
  }else{
    $upload = 0;
  };

//write parapmeter
$array = array('file' => $file,'jobID' => $jobID);
$fp =fopen("/var/www/html/maize/job2/".$jobID."/".$jobID."_para.json", "w") or die("Unable to open file!");
fwrite($fp, json_encode($array, JSON_PRETTY_PRINT));
fclose($fp);

//run Rscript
//$out=" > /var/www/html/maize/job/".$jobID."/".$jobID.".out";

//Linux bash
//system("/usr/bin/R CMD INSTALL /home/zhanmin.liang/maize_web_script/index/BSgenome.Zmays.Ensemble.zmv4_1.0.tar.gz >Rfile.log 2>&1  &");
//system("pip3 install pandas >pyfile.log 2>&1  &");
//system('nohup /usr/bin/Rscript /var/www/html/maize/traits_script/test.R' . ' ' .$jobID. " >Rfile.log 2>&1  &");
system('nohup /usr/bin/python3 /var/www/html/maize/traits_script/traits_web_main.py' . ' ' .$jobID. " >file2.log 2>&1  &");

//debug on Windows
//system("D:\phpstudy_pro\WWW\maize\R-4.0.3\bin\R.exe --vanilla <D:\phpstudy_pro\WWW\maize\upload.R",$out);

?>