<?php


  function l($data, $file = 'log.txt') {
        $logpath = __DIR__ . '/log';

        if (!file_exists($logpath)) {
            mkdir($logpath);
        }


        $data = (is_array($data) || is_object($data)) ? print_r($data, true) : $data;

        $prefix = date('Y') . date('m') . '_';

        try {
            $content = "\n$data\n";
            $fp = fopen($logpath . DIRECTORY_SEPARATOR . $prefix . $file, 'a+');
            fwrite($fp, $content);
            fclose($fp);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }


$s = (integer) isset($_REQUEST['s']) ? $_REQUEST['s'] : 0;
$r = (integer) isset($_REQUEST['r']) ? $_REQUEST['r'] : 'request name';




$start = date('Y-m-d H:i:s');
sleep($s);    
$end = date('Y-m-d H:i:s');




$timetaken =  $start.' - '.$end;


l(['request'=>$r, 'output'=>$timetaken]);
