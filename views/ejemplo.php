set_time_limit(99999999);
ini_set('memory_limit', "9999M");

function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

$url_array = array();

function recurse_it($url, $c) {
    global $url_array;
    $feeds         = fetchUrl($url);
    $feed_data_obj = json_decode($feeds, true);
    if (!empty($feed_data_obj['data'])) {
        $next_url      = $feed_data_obj['paging']['next'];
        $url_array[$c] = $next_url;
        recurse_it($next_url, $c + 1);
    }
    return $url_array;
}

$url = "https://graph.facebook.com/55259308085/groups?access_token=CAACEdEose0cBAKZBS4XhZCoT59hpLiZCpmV2ZBruZB64oFMt0PlO88bYXZAe6GZCZAFCngDff1ZClYob1bnn4WgthyZAvIamRxCBaNbZB63y15WiiOGw5ZCRgvXp9T1PytZAducZCBJEau1QCG3gZCoAFF7mrXgjzZATBjcRJKv4zs5azRZAMD3kU8ZBXhHdb7CwCAG9o3F5gcGZB1InFyoJbIIQ18PZAcZCK";
$arr = recurse_it($url, 0);
print_r($arr);

