<?php
namespace common\logic;
use Yii;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ExcelLogic {
    /**
     * 导出Excel文件
     * @param string $fileName  导出的文件名称
     * @param array $headerArr  头部数据：['A'=>'名称']
     * @param array $data       数据：[['name'=>'张三'],['name'=>'李四']]
     * @param string $sheet     工作表的名称
     */
    public function export($fileName="Excel", $headerArr = array(), $data = array(),$sheet = 'Sheet1'){
        ini_set("memory_limit", "2048M");
        set_time_limit(0);
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator('Maarten Balliauw')
        ->setLastModifiedBy('Maarten Balliauw')
        ->setTitle('Office 2007 XLSX Test Document')
        ->setSubject('Office 2007 XLSX Test Document')
        ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
        ->setKeywords('office 2007')
        ->setCategory('Test result file');
       
        
        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle($sheet);
       
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        //获取列字母,设置第一行表头
        $headCharArr = $this->getHeaderChar($headerArr); //A B C
        foreach ($headCharArr as $k => $v) {
            //居中
            $styleArray = [
                    'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
            ];     
            $sheet->getStyle($v.'1')->applyFromArray($styleArray);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(40);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('L')->setWidth(80);
            $sheet->setCellValue($v.'1', $headerArr[$k]);
    
        }
        
      //导出数据
        $j = 2;
        foreach ($data as $k => $v) {
            foreach ($headerArr as $k1 => $v1) {
                //居中
                $styleArray = [
                        'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                ];
                $sheet->getStyle($headCharArr[$k1] . $j)->applyFromArray($styleArray);
                $val = $v[$k1].'';
                $sheet->setCellValue($headCharArr[$k1] . $j, $val);
            }
            $j++;
        } 
        ob_end_clean();
        ob_start();
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        ob_end_flush();
        //清空数据缓存
        unset($data);
        exit;
    }
    
    /**
     * 获取excel列数字母
     * @param array $data
     * @return array
     */
    private function getHeaderChar($data = array())
    {
        $index = 65; //A标签
        $char = '';
        $charArr = array();
        foreach ($data as $k => $v) {
            $charArr[$k] = $char . chr($index++);
            if ($index == 91) {
                $index = 65;
                $char .= 'A';
            }
        }
        return $charArr;
    }
    
    
    
   
}
?>