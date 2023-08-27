<?php

$obj = file_get_contents('https://adr.npa.gov.tw/indexgo');
// https://www.google.com/maps/d/edit?mid=(mid)
preg_match_all('/https:\/\/www\.google\.com\/maps\/d\/edit\?mid=([^&]*)/', $obj, $matches);
foreach ($matches[1] as $mid) {
    $target = __DIR__ . "/outputs/{$mid}.kml";
    if (file_exists($target) and filesize($target)) {
        continue;
    }
    file_put_contents($target, file_get_contents("https://www.google.com/maps/d/u/0/kml?mid={$mid}&forcekml=1"));
}
