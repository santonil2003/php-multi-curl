<?php


$start = microtime(true);

//The URLs that we want to send cURL requests to.
$urls = array(
    'http://localhost/php_async/api.php?s=20&r=1',
    'http://localhost/php_async/api.php?s=5&r=2',
    'http://localhost/php_async/api.php?s=1&r=3',
     'http://localhost/php_async/api.php?s=2&r=4',
);

//An array that will contain all of the information
//relating to each request.
$requests = array();


//Initiate a multiple cURL handle
$mh = curl_multi_init();

//Loop through each URL.
foreach($urls as $k => $url){
    $requests[$k] = array();
    $requests[$k]['url'] = $url;
    //Create a normal cURL handle for this particular request.
    $requests[$k]['curl_handle'] = curl_init($url);
    //Configure the options for this request.
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_TIMEOUT, 1);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($requests[$k]['curl_handle'], CURLOPT_SSL_VERIFYPEER, false);
    //Add our normal / single cURL handle to the cURL multi handle.
    curl_multi_add_handle($mh, $requests[$k]['curl_handle']);
}

//Execute our requests using curl_multi_exec.
$stillRunning = false;
do {
    curl_multi_exec($mh, $stillRunning);
} while ($stillRunning);

//Loop through the requests that we executed.
foreach($requests as $k => $request){
    //Remove the handle from the multi handle.
    curl_multi_remove_handle($mh, $request['curl_handle']);
    //Get the response content and the HTTP status code.
    $requests[$k]['content'] = curl_multi_getcontent($request['curl_handle']);
    $requests[$k]['http_code'] = curl_getinfo($request['curl_handle'], CURLINFO_HTTP_CODE);
    //Close the handle.
    curl_close($requests[$k]['curl_handle']);
}
//Close the multi handle.
curl_multi_close($mh);

//var_dump the $requests array for example purposes.
var_dump($requests);


$time_elapsed_secs = microtime(true) - $start;

echo $time_elapsed_secs;
































