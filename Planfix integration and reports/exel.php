<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
require_once dirname(__FILE__) . "/classes/Baza.php";

$base=new Baza();

$userID=$_GET['user'];
$sort=$_GET['sort'];

$row=$base->getAllFields($userID, $sort);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '№ п/п')
            ->setCellValue('B1', 'Дата')
            ->setCellValue('C1', 'УИК')
            ->setCellValue('D1', 'Статус')
            ->setCellValue('E1', 'Партнер');
$i=1;
foreach ($row as $r)
{
$x=$i+1;
$objPHPExcel->setActiveSheetIndex(0)->getCell('A'.$x)->setValueExplicit("$i", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->setActiveSheetIndex(0)->getCell('B'.$x)->setValueExplicit($r["date_send"], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->setActiveSheetIndex(0)->getCell('C'.$x)->setValueExplicit($r["UIK"], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->setActiveSheetIndex(0)->getCell('D'.$x)->setValueExplicit($r["status"], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->setActiveSheetIndex(0)->getCell('E'.$x)->setValueExplicit($r["PartnerName"], PHPExcel_Cell_DataType::TYPE_STRING);
$i++;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($sort);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>