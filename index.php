<?php 

//force utf8
header( 'Content-Type: text/html; charset=UTF-8' );

//set up some basic configurations
$base_url = "http://apartm.net/hackernews/";
$item_url = 'href="' . $base_url . 'item.php?';
$index_url = 'href="' . $base_url . 'index.php?fnid=';
$info_url = '
<tr>
	<td>
		<cite class="footnote">Mobile hack brought to you by <a href="http://apartm.net">Apartm.net</a></cite>
	</td>
</tr>
</table></center></body>';



$url_to_get = "http://news.ycombinator.com/";
if(isset($_GET['fnid'])) {
	$url_to_get .= "x?fnid=" . $_GET['fnid'];
} elseif(isset($_GET['id'])) {
	$url_to_get .= "item?id=" . $_GET['id'];
}



// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url_to_get);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$output = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

$inject  = '<link rel="stylesheet" href="style.css" />';
$inject .= '<meta name="apple-mobile-web-app-capable" content="no" />';
$inject .= '<link rel="apple-touch-icon" href="icon.png" />';
$inject .= '<meta name="viewport" content="width=320; user-scalable=no">';
$inject .= '<script type="application/x-javascript"> 
    if (navigator.userAgent.indexOf("iPhone") != -1) 
    { 
        addEventListener("load", function() 
        { 
        setTimeout(hideURLbar, 0); 
        }, false); 
    } 
    function hideURLbar() 
    { 
        window.scrollTo(0, 1); 
    } 
</script> 
';


$inject .= "</head>";
$output = str_replace("</head>", $inject, $output);
$output = str_replace('href="/', 'href="http://news.ycombinator.com/', $output);
$output = str_replace('href="vote', 'href="http://news.ycombinator.com/vote', $output);
$output = str_replace('href="user', 'href="http://news.ycombinator.com/user', $output);
$output = str_replace('href="item?', $item_url, $output);
$output = str_replace('href="http://news.ycombinator.com/x?fnid=', $index_url, $output);
$output = str_replace('</table></center></body>', $info_url, $output);


echo $output;