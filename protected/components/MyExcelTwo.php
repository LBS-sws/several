<?php

class MyExcelTwo {
    protected $objPHPExcel;
    protected $objActSheet;
    protected $listArr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    protected $row = 1;
    protected $sheetNum = 0;
    protected $protoSum=array();

    public function __construct() {
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel');
        spl_autoload_unregister(array('YiiBase','autoload'));
        //include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->getProperties()
            ->setCreator("WOLF")
            ->setLastModifiedBy("WOLF")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
        $this->objActSheet = $this->objPHPExcel->setActiveSheetIndex(0); //填充表头
        //$this->objPHPExcel->getActiveSheet()->getStyle('A1:H8')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //$objPHPExcel->getActiveSheet()->freezePane('A2');
    }

    //設置起始行
    public function setStartRow($num){
        $this->row = $num;
    }

    //設置某行的內容
    public function setRowContent($row,$str,$endRow=0){
        $this->objActSheet->setCellValue($row,$str);
        if(!empty($endRow)){
            $this->objActSheet->mergeCells($row.":".$endRow);
        }
    }

    //設置規則提示
    public function setRulesArr($arr){
        for ($i = 0;$i<count($arr);$i++){
            $this->objActSheet->setCellValue("A".($i+1),$arr[$i]);
            $this->objActSheet->getStyle( "A".($i+1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
        }
    }
    //
    public function setProtoValue($cityCode,$key,$value){
        $this->protoSum[$cityCode][$key]=$value;
    }

    //設置表頭
    public function setDataHeard($heardArr,$title="導入錯誤"){
        $this->objPHPExcel->getActiveSheet()->setTitle($title);
        //3.填充表格
        $this->objActSheet->getColumnDimension( 'A')->setWidth(50);         //30宽
        $this->objActSheet->getColumnDimension( 'B')->setWidth(30);         //30宽
        $this->objActSheet->getColumnDimension( 'C')->setWidth(40);         //30宽
        $this->objActSheet->setCellValue('A'.$this->row,"錯誤提示");
        $this->objActSheet->getStyle('A'.$this->row)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
        $i = 1;
        foreach ($heardArr as $item){
            $this->objActSheet->setCellValue($this->listArr[$i].$this->row,$item);
            $i++;
        }
    }

    //繪製表格
    public function printTable($num){
        $str = $this->listArr[$num-1];
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objActSheet->getStyle('A'.($this->row-1).':'.$str.($this->row))->applyFromArray($styleArray);
    }

    //設置内容
    public function setDataBody($bodyArr,$error){

        foreach ($error as $list){
            if(key_exists($list["key"],$bodyArr)){
                $this->row++;
                $i = 0;
                $this->objActSheet->setCellValue($this->listArr[$i].$this->row,$list["error"]);
                $this->objActSheet->getStyle($this->listArr[$i].$this->row)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                $i++;
                $row = $bodyArr[$list["key"]];
                foreach ($row as $item){
                    $this->objActSheet->setCellValue($this->listArr[$i].$this->row,$item);
                    $i++;
                }
            }
        }
    }


    //添加新的sheet
    public function addNewSheet($sheetName=""){
        $this->sheetNum++;
        $this->objPHPExcel->createSheet();
        $this->objActSheet = $this->objPHPExcel->setActiveSheetIndex($this->sheetNum);
        if(!empty($sheetName)){
            $this->objActSheet->setTitle($sheetName);
        }
    }


    //設置sheet的名字
    public function setSheetName($sheetName){
        $this->objPHPExcel->setActiveSheetIndexByName($sheetName);
        //$this->objPHPExcel->getActiveSheet()->setTitle( 'Invoice');
    }

    //輸出excel表格
    public function outDownExcel($fileName){
        ob_end_clean();//清除缓冲区,避免乱码
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header('Content-Disposition: attachment;filename='.$fileName);
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel5');
        $objWriter->save('php://output');
        //exit;
    }

    //生成excel表格
    public function saveExcel($url){
        $url=Yii::app()->basePath."/../$url";
        $excel = new PHPExcel_Writer_Excel2007();
        $excel->setPHPExcel($this->objPHPExcel);
        $excel->save($url);
    }
}
?>