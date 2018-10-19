<?php
header('application/rss+xml');

if (!file_exists('feed.xml')) {
	throw new Exception('Update feed needed');
}

echo file_get_contents('feed.xml');
