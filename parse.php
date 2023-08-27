<?php

$listfp = fopen(__DIR__ . '/data/list.csv', 'w');
fputcsv($listfp, ['mid', 'name']);
$columns = [
    'foldername',
    'placename',
    '電腦編號',
    '村里別',
    '地址',
    '緯度',
    '經度',
    '地下樓層數',
    '可容納人數',
    '轄管分局',
    '備註',
];

foreach (glob("./outputs/*.kml") as $kmlfile) {
    $mid = substr($kmlfile, 10, -4);
    $output = fopen(__DIR__ . "/data/{$mid}.csv", 'w');
    fputcsv($output, $columns);
    error_log($mid);
    $data = [];
    $data['mid'] = $mid;
    $doc = new DOMDocument();
    $doc->load($kmlfile);
    $data['name'] = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    fputcsv($listfp, [$mid, $data['name']]);
    foreach ($doc->getElementsByTagName('Folder') as $folder) {
        $data['foldername'] = $folder->getElementsByTagName('name')->item(0)->nodeValue;
        foreach ($folder->getElementsByTagName('Placemark') as $placemark) {
            $data['placename'] = $placemark->getElementsByTagName('name')->item(0)->nodeValue;
            $data['placedesc'] = $placemark->getElementsByTagName('description')->item(0)->nodeValue;
            foreach ($placemark->getElementsByTagName('Data') as $data_dom) {
                $dataname = $data_dom->getAttribute('name');
                $datavalue = $data_dom->getElementsByTagName('value')->item(0)->nodeValue;
                $data[$dataname] = $datavalue;
            }
            fputcsv($output, array_map(function($k) use ($data) {
                return isset($data[$k]) ? $data[$k] : '';
            }, $columns));
        }
    }


}
