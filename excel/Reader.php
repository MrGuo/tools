<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';


$file = "product.xlsx";

$objWriter = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objWriter->load($file);

$sheet = $objPHPExcel->getSheet(0); // 读取第一个工作表从0读起
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn()); // 取得总列数


/** 循环读取每个单元格的数据 */
$r = array();
for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
    $line = array();
    for ($column = 0; $column < $highestColumn; $column++) {//列数是以第0列开始
        $columnName = PHPExcel_Cell::stringFromColumnIndex($column);
        //echo $columnName.$row.":".$sheet->getCellByColumnAndRow($column, $row)->getValue()."       ";
        $value = $sheet->getCellByColumnAndRow($column, $row)->getValue();
        $line[] = $value; 
    }
    $r[] = $line;
}

var_dump($r);
