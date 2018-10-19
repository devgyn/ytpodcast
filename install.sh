#!/bin/sh

echo "\n\n Installing depedences...\n\n"

pip install youtube-dl
pip install pafy

composer install

echo "\n\n updating feed...\n\n"


php update.php

echo "\n\nPlease add to cron php update.php to automatically check updates\n\n"
