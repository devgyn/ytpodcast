#!/bin/sh

pip install youtube-dl
pip install pafy

composer install

php update.php
