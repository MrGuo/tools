<?php
$imgPath = "/Users/guochao/Documents/4.jpg";
$imgWritePath = "/Users/guochao/Documents/4-r-1.jpg";
$imgHandle = new Imagick($imgPath);

$start = microtime(true);

$imgHandle->cropthumbnailimage(200, 300);
$res = $imgHandle->gaussianBlurImage(30, 20);
$spend = microtime(true) - $start;
$start = microtime(true);
echo "spend_1:", $spend, "\n";

$imgHandle->writeImage($imgWritePath);


$spend = microtime(true) - $start;
echo "spend_2:", $spend, "\n";
