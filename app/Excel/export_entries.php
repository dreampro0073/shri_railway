<?php

namespace App;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// error_reporting(0);

$styleArrayborder = array(
    'borders' => array(
        'outline' => array(
            'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => 'FF4E81BE'),
        ),
    ),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),),
);

$styleArray4 = array(
    'font' => array('bold' => true,'size'=>20,'color' => array('argb' => 'FF222222',),),
    'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,'color' => array('argb' => 'FF4E81BE',),),
    'borders' => array(
        'outline' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('argb' => 'FF000000'),
        ),
    ),
 );

$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
$Excel_writer = new Xls($spreadsheet);  /*----- Excel (Xls) Object*/
$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();
$activeSheet->setTitle("Associations");

$ar_names = array("SN","Date","From Date","To Date","Name", "Mobile","NO of Day", "No of Bag", "Paid Amount");

$ar_fields = array("sn","date","checkin_date","checkout_date", "name", "mobile_no","no_of_day","no_of_bag" ,"sh_paid_amount");

$ar_width = array("15","25","20","30","20","20","20","20","20","20","20","20","20","20","20","20","20","20");

$seq = 1;
$offset = 0;
$count = 0;
$i = 0;
foreach ($ar_fields as $ar) {

    $cell_val = $i + $offset;
    $cell_val = $this->getNameFromNumber($cell_val);

    $spreadsheet->setActiveSheetIndex(0)->setCellValue($cell_val . $seq, $ar_names[$count]);
    $spreadsheet->getActiveSheet()->getColumnDimension($cell_val)->setWidth($ar_width[$count]);
    
    $i++;
    $count++;
}
$spreadsheet->getActiveSheet()->getStyle($this->getNameFromNumber(0).$seq.':'.$this->getNameFromNumber($i-1).$seq)->applyFromArray($styleArrayborder);

$spreadsheet->setActiveSheetIndex(0)->getStyle($this->getNameFromNumber(0).$seq.':'.$this->getNameFromNumber($i-1).$seq)->getFill()
->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
->getStartColor()->setARGB('b3caf2');

$seq++;
$count = 0;

foreach ($l_entries as $row) {
    
    $i = 0;

    foreach ($ar_fields as $ar) {
        $var = '';
        $cell = $i + $offset;
        $cell_val = $this->getNameFromNumber($cell);

        if ($ar == 'sn') {

            $var = ++$count;

        } elseif ($ar == 'date') {

            $var = isset($row->{$ar})
                ? date("d-m-Y", strtotime($row->{$ar}))
                : "";

        } elseif ($ar == 'checkin_date' || $ar == 'checkout_date') {

            $var = isset($row->{$ar})
                ? date("d-m-Y h:i A", strtotime($row->{$ar}))
                : "";

        } else {

            $var = isset($row->{$ar})
                ? $row->{$ar}
                : '';
        }
        $i++;
        $spreadsheet->getActiveSheet()->setCellValue($cell_val . $seq, $var);
    }

    $seq++;
}


$filename = 'day_book'.date("dmY",strtotime("today")).'.xls';

$writer = new Xls($spreadsheet);

if(env("FTP_STATUS") == 1){
    $path = public_path('temp/');
    $writer->save($path.$filename);
} else {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename); 
    header('Cache-Control: max-age=0');
    $writer->save("php://output");

}

// exit();