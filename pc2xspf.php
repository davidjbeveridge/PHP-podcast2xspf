<?php

if(!empty($_REQUEST['pcurl']))	{
	$url = urldecode($_REQUEST['pcurl']);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$xmldata = curl_exec($ch);
	curl_close($ch);
	$podcast = simplexml_load_string($xmldata);
	if(!$podcast) die("Podcast Not Found");
	header('Content-type: text/xml');
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	foreach($podcast->channel as $channel)	{
		echo "\t<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n";
		echo "\t\t<title>$channel->title</title>\n";
		echo "\t\t<creator>$channel->author</creator>\n";
		echo "\t\t<trackList>\n";
		foreach($channel->item as $item)	{
			echo "\t\t\t<track>\n";
			echo "\t\t\t\t<location>".$item->enclosure['url']."</location>\n";
			echo "\t\t\t\t<title>$item->title</title>\n";
			echo "\t\t\t\t<info>$item->link</info>\n";
			echo "\t\t\t</track>\n";
		}
		echo "\t\t</trackList>\n";
		echo "\t</playlist>\n";
	}

}
