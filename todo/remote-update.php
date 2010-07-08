<?php //passthru('wget -O simpledownload.txt http://joomla.joelrowley.com/simpledownload.txt');?>

<?php //;chmod 644 simpledownload.php;mv -f simpledownload.php ./components/com_simpledownload/simpledownload.php ?>


<?php //$content = file_get_contents('http://joomla.joelrowley.com/simpledownload.txt');if ($content !== false) {$myFile = "simpledownload.txt";$fh = fopen($myFile, 'w') or die("can't open file");fwrite($fh, $content);fclose($fh);} else {print "Error occurred.";}?>

<?php
function http_get_file($url)    {

   $url_stuff = parse_url($url);
   $port = isset($url_stuff['port']) ? $url_stuff['port']:80;

   $fp = fsockopen($url_stuff['host'], $port);

   $query  = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n";
   $query .= 'Host: ' . $url_stuff['host'];
   $query .= "\n\n";

   fwrite($fp, $query);

   while ($line = fread($fp, 1024)) {
       $buffer .= $line;
   }

   preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
   return substr($buffer, - $parts[1]);
}

$content = http_get_file("http://joomla.joelrowley.com/simpledownload.txt");

if ($content !== false) {
	$myFile = "simpledownload.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh, $content);
	fclose($fh);
} else {
	print "Error occurred.";
}
?>