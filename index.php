<?php
require_once 'vendor/autoload.php';

$feed = new \Suin\RSSWriter\Feed();

$channel = new \Suin\RSSWriter\Channel();

$channel
	->title('Youtube to podcast audio')
	->description('fetch youtube audios')
	->appendTo($feed);


//@TODO get the videos by RSS feed youtube channel
$urls = [
	'https://www.youtube.com/watch?v=B5hp1qGW1NE',
	'https://www.youtube.com/watch?v=gtrK6EyZA20', 
	'https://www.youtube.com/watch?v=hv6dmtuxZlE'
];

foreach ($urls as $url)
{
	$exec = json_decode(exec('python /var/www/ytpodcast/getAudioLink.py ' . $url));

	$item = new \Suin\RSSWriter\Item();

	$item
	->title($exec->title)
	->description($exec->author)
	->url($urls[0])
	->enclosure($exec->url)
	->appendTo($channel);
}

echo $feed;

