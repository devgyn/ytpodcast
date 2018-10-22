<?php

/**
 * APP Configuration
 *
 * mode:
 *      default: use default, the links is by google
 *      download: download the podcasts into downloads folder and use local links
 */
$appConfig = [
    'mode' => 'default'
];



if (PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) {
    throw new Exception('CLI only');
}

require_once 'vendor/autoload.php';

// load channels IDs from json file
$ytChannelIDs = json_decode(file_get_contents('channels.json'));

if (!isset($ytChannelIDs->channels)) {
    throw new Exception('Invalid channels items');
}

$urls = null;

foreach ($ytChannelIDs->channels as $ytch)
{
    // get videos by youtube feed url
    $youtubeFeed = file_get_contents('https://www.youtube.com/feeds/videos.xml?channel_id=' . $ytch);

    if (!$youtubeFeed) {
        throw new Exception('Youtube feed unreachable');
    }

    $ytXML = new SimpleXMLElement($youtubeFeed);


    foreach ($ytXML->entry as $entry)
    {
        $ytvID = str_replace('yt:video:', '', $entry->id);
        $urls[] = 'https://www.youtube.com/watch?v=' . $ytvID;
    }

}


$feed = new \Suin\RSSWriter\Feed();

$channel = new \Suin\RSSWriter\Channel();

$channel
    ->title('Youtube to podcast audio')
    ->description('fetch youtube audios')
    ->appendTo($feed);

if (!$urls) {
    // if urls is empty, do nothing
    exit;
}

foreach ($urls as $url)
{
    $exec = null;

    if ($appConfig['mode'] == 'default') {
        $exec = json_decode(exec('python ./getAudioLink.py ' . $url));

        if (!isset($exec->title) || !isset($exec->url)) {
            continue;
        }
    }

    if ($exec) {
        $item = new \Suin\RSSWriter\Item();

        $item
        ->title($exec->title)
        ->description($exec->author)
        ->url($url)
        ->enclosure($exec->url)
        ->pubDate(strtotime($exec->date))
        ->appendTo($channel);
    }
}

file_put_contents('feed.xml', $feed);

