<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=GBK">
	<meta name=”viewport” content=”width=1000, initial-scale=1.5, user-scalable=yes”/>	
</head>
<body id="home">
	<title>搜索</title>
	<link rel="stylesheet" href="index.css">

	<div id="top"></div>
	<div id="header">
		<div id="logoarea"></div>
		<div id="banner">
			<form action="index.php" method="get">
				<input type="text" name="Search" />
				<input type="submit" value="Submit" />
			</form>
		</div>
	</div>
	<div id="main">
	
<?php


include "Snoopy.class.php";
include "gzencode.php";

error_reporting(E_ALL);
include_once('simple_html_dom.php');

header("Content-type:text/html;charset=utf-8");  
$xmlDoc = new DOMDocument();
$query = $_GET["Search"];
$query = urlencode($query);
$xmlDoc->load("http://100.42.231.7:8983/solr/select/?q=title%3A*".$query."*&version=2.2&start=0&rows=10&indent=on");
$x = $xmlDoc->documentElement;
foreach ($x->childNodes AS $doc)
{
	if($doc->nodeName == "#text")continue;
	foreach ($doc->childNodes AS $inf){
		if($inf->nodeName == "#text")continue;
		foreach ($inf->childNodes AS $item)
		{
		if($item->nodeName == "#text")continue;
		if($item->getAttribute ("name")=="title"){
		print "<div id=\"title\">" . $item->nodeValue . "</div>";
		}
		if($item->getAttribute ("name")=="url"){
		print "<div id=\"info\">" . "<a href=\"". $item->nodeValue . "\">". $item->nodeValue ." </a> <br />";

		$snoopy = new Snoopy;
		if($snoopy->fetch($item->nodeValue))
			$htmlstr = gzdecode($snoopy->results);
		else
			echo "error fetching document: ".$snoopy->error."\n";

		$html = str_get_html($htmlstr);
		$element = $html->find('meta[name="keywords"]',-1);
		print $element->content . "<br />";
		$element = $html->find('meta[name="description"]',-1);
		print $element->content . "<br />";

		foreach($html->find('embed') as $element)
		print "视频真实键值（如，新浪视频vid，优酷视频ykid等）：". $element->flashvars . "<br /> </div>";


		}
		}
	}
}
?>

	</div>
	<div id="sider"></div>
	<div id="footer"></div>
</body>

</html>