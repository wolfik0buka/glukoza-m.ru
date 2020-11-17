<?php
    require_once '../phpexcel/Classes/PHPExcel.php';
    require_once '../phpexcel/Classes/phpexcel/Writer/Excel5.php';
$xls = new PHPExcel();
$xls->setActiveSheetIndex(0);
$sheet = $xls->getActiveSheet();
$sheet->setTitle('Товарный чек');
$startLine = 15;
$borderedHead = new PHPExcel_Style();
$borderedHead->applyFromArray(
    array(
        'borders' => array(
        'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'top'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'left'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        )
    )
);

$sheet->mergeCells('a1:i1')->setCellValue('a1','ООО "Сингер-Мед"');
$sheet->getStyle('a1')->getFont()->setSize(24);
$sheet->mergeCells('a2:i2')->setCellValue('a2','Медицинская техника для контроля диабета и не только…');
$sheet->getStyle('a2')->getFont()->setSize(12);
$sheet->mergeCells('a4:i4')->setCellValue('a4','Санкт-Петербург, Большой Сампсониевский пр. 62, оф 202');
$sheet->mergeCells('a5:i5')->setCellValue('a5','Телефон/факс : +7 (812) 448-6708, +7 (812) 448-6709, +7 (812) 244-4192');
$sheet->mergeCells('a6:i6')->setCellValue('a6','www.glukoza-med.ru');
$sheet->getStyle('a4:a6')->getFont()->setSize(8)->setBold(true);
$sheet->getStyle('a1:a6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->mergeCells('d9:e9')
      ->mergeCells('f9:g9')
      ->mergeCells('d10:e10')
      ->mergeCells('f10:g10')
      ->mergeCells('b10:c10');
$sheet->setCellValue('d9','Номер документа')
      ->setCellValue('f9','Дата составления')
      ->setCellValue('b10','ТОВАРНЫЙ ЧЕК');
$sheet->getStyle('b10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('b10:f10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('b10')->getFont()->setSize(9);

$sheet->setSharedStyle($borderedHead, 'd9:g9');
$sheet->getStyle('d9:g9')->getFont()->setSize(8);
$sheet->getStyle('d9:g9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                         ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Шапка таблицы
$sheet->mergeCells('a13:a14')
      ->mergeCells('c13:c14')
      ->mergeCells('d13:e13')
      ->mergeCells('f13:f14')
      ->mergeCells('g13:h13')
      ->mergeCells('i13:i14')
      ->setCellValue('a13','Номер по порядку')
      ->setCellValue('b13','Товар, тара')
      ->setCellValue('b14','Наименование, характеристика')
      ->setCellValue('c13','Артикул товара')
      ->setCellValue('d13','Единица измерения')
      ->setCellValue('d14','Наиме-нование')
      ->setCellValue('e14','Код по ОКЕИ')
      ->setCellValue('f13','Цена')
      ->setCellValue('g13','Отпущено')
      ->setCellValue('e14','Код по ОКЕИ')
      ->setCellValue('g14','Количест-во (масса)')
      ->setCellValue('h14','Сумма, руб. коп')
      ->setCellValue('i13','Продано на сумму, руб. коп');
$sheet->getColumnDimension('a')->setWidth(6);
$sheet->getColumnDimension('b')->setWidth(25);
$sheet->getColumnDimension('c')->setWidth(6);
$sheet->getColumnDimension('d')->setWidth(7);
$sheet->getColumnDimension('e')->setWidth(6);
$sheet->getColumnDimension('f')->setWidth(7);
$sheet->getColumnDimension('g')->setWidth(7);
$sheet->getColumnDimension('h')->setWidth(7);
$sheet->getColumnDimension('i')->setWidth(10);
$sheet->getRowDimension(14)->setRowHeight(25);
$sheet->setSharedStyle($borderedHead, 'a13:i14');
$sheet->getStyle('a13:i14')->getAlignment()->setWrapText(true)
                                           ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                           ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('a13:i14')->getFont()->setSize(8);
$sheet->getStyle('a1:i200')->getFont()->setName('Arial');


    
    
    
    
//header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
//header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
//header ( "Cache-Control: no-cache, must-revalidate" );
//header ( "Pragma: no-cache" );
//header ( "Content-type: application/vnd.ms-excel" );
//header ( "Content-Disposition: attachment; filename=matrix.xls" );
$objWriter = new PHPExcel_Writer_Excel5($xls);
//$objWriter->save('php://output');
$objWriter->save('../xlsx_2014/ggg.xls');
?>