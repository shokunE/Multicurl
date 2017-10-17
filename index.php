<?php

require_once ('multicurl.php');

$urls = [];

for( $i = 0; $i < 10; $i++ ){
    $urls[] = 'http://httpbin.org/get?i='.$i;
}
//$setopt = [];
//$setopt['CURLOPT_HEADER'] = false;

echo "<pre>";
print_r(multicurl::go($urls));
echo "</pre>";
