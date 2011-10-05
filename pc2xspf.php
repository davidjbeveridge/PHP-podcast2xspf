<?php

/*
Copyright (c) 2010 David Beveridge (davidjbeveridge@gmail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

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
