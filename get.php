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
    mkdir("/var/www/html/maize/job/".$jobID, 0777, true);
    mkdir("/var/www/html/maize/job/".$jobID."/download", 0777, true);
};

// upload parameters
if($_POST['text']){
    $text = $_POST['text'];
    $myfile = fopen("/var/www/html/maize/job/".$jobID."/".$jobID.".fasta", "w") or die("Unable to open file!");
    $txt = $text."\n";
    fwrite($myfile, $text);
    fclose($myfile);
    $file="/var/www/html/maize/job/".$jobID."/".$jobID.".fasta";
  }else{
    $text = 0;
  };
  
  if($_FILES["file"]){
    $upload = 1;
    $storeDir = "/var/www/html/maize/job/".$jobID."/";
    move_uploaded_file($_FILES['file']['tmp_name'], $storeDir.$jobID.".fasta");
    $file="/var/www/html/maize/job/".$jobID."/".$jobID.".fasta";
  }else{
    $upload = 0;
  };

//write parapmeter
$array = array('file' => $file,'jobID' => $jobID);
$fp =fopen("/var/www/html/maize/job/".$jobID."/".$jobID."_para.json", "w") or die("Unable to open file!");
fwrite($fp, json_encode($array, JSON_PRETTY_PRINT));
fclose($fp);

//run Rscript
//$out=" > /var/www/html/maize/job/".$jobID."/".$jobID.".out";

//Linux bash
//system("pip3 install sklearn >pyfile.log 2>&1  &");
//system('nohup /usr/bin/Rscript /var/www/html/maize/script/full_script_web.R' . ' ' .$jobID. " >Rfile.log 2>&1  &");
system('nohup /usr/bin/python3 /var/www/html/maize/script/web_script.py' . ' ' .$jobID. " >file.log 2>&1  &");
//system('nohup /home/zhanmin.liang/miniconda3/envs/py37/bin/python /var/www/html/maize/script/web_script.py' . ' ' .$jobID. " >pyfile.log 2>&1  &");

//debug on Windows
//system("D:\phpstudy_pro\WWW\maize\R-4.0.3\bin\R.exe --vanilla <D:\phpstudy_pro\WWW\maize\upload.R",$out);

?>