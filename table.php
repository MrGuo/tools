<?php

require "db.php";

$table = 'countcardwastebook';
$ret = DB::instance("gaoliwei")->read("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'gaoliwei' AND TABLE_NAME = '{$table}'");

foreach ($ret as $value) {
   $str[] = "\n'{$value['COLUMN_NAME']}'";
}

echo implode(",", $str), "\n";
