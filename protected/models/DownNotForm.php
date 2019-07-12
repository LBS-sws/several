<?php

class DownNotForm {
    protected $objPHPExcel;
    protected $objActSheet;
    protected $listArr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    protected $row = 1;
    protected $sheetNum = 0;


    //集團客戶
    protected $columnList=array(
        "sum"=>"No of Clients",
        "seven"=>" - Status within 7 days",
        "eight"=>" - Status 8-30 days",
        "auto"=>" - Status over 30 days"
    );
    protected $rowListStr=array(
        "three"=>array("name"=>">=3","width"=>6),
        "two"=>array("name"=>"2","width"=>6),
        "one"=>array("name"=>"1","width"=>6),
        "total"=>array("name"=>"Total O/S","width"=>6),
        "not"=>array("name"=>"No#8","width"=>6),
        "five"=>array("name"=>"Long OS >5m","width"=>6),
        "cod"=>array("name"=>"COD","width"=>6),
        "test"=>array("name"=>"","width"=>1)
    );
    protected $rowList=array(
        "three"=>0,
        "two"=>0,
        "one"=>0,
        "total"=>0,
        "not"=>0,
        "five"=>0,
        "cod"=>0
    );
    protected $headList;
    protected $excelList = array();
    protected $firmList = array();
    protected $staffList = array();

    protected $excel_date;//excel生成時間
    protected $totalDay=array();//合計所有（根據更新時間分類)
    protected $totalFirm=array();//合計所有（根據公司分類)

    public function __construct() {
        $phpExcelPath = Yii::getPathOfAlias('ext.phpexcel');
        //spl_autoload_unregister(array('YiiBase','autoload'));
        include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

        $this->objPHPExcel = new PHPExcel();

        $this->objPHPExcel->getProperties()
            ->setCreator("WOLF")
            ->setLastModifiedBy("WOLF")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
        $this->objActSheet = $this->objPHPExcel->setActiveSheetIndex(0); //填充表头
        $this->objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(12);      //字体大小
        //$this->objPHPExcel->disconnectWorksheets();
        //var_dump($this->objPHPExcel);die();
        //$this->objPHPExcel->getActiveSheet()->getStyle('A1:H8')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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

    protected function setWidthToArr($arr,$width){
        if(is_array($arr)){
            foreach ($arr as $str){
                $str = strtoupper($str);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension($str)->setWidth($width);
            }
        }else{
            $str = strtoupper($arr);
            $this->objPHPExcel->getActiveSheet()->getColumnDimension($str)->setWidth($width);
        }
    }

    //設置所有員工
    protected function setStaffList(){
        //員工初始化
        $arr = array();
        $rows = Yii::app()->db->createCommand()->select("id,staff_name")->from("sev_staff")->queryAll();
        $i=0;
        if ($rows){
            $num =0;
            $j=0;
            array_push($rows,array("id"=>0,"staff_name"=>"未命名員工"));
            array_push($rows,array("id"=>"total","staff_name"=>"TOTAL"));//統計
            foreach ($rows as $row){
                $i++;
                $num = 7*$i+1;
                $j = 0;
                $this->objActSheet->setCellValue("A".$num,$row["staff_name"]);
                $arr[$row["id"]]=array("name"=>$row["staff_name"],"startRow"=>$num,"countList"=>$this->rowList);
                foreach ($this->columnList as $key=>$item){
                    $j++;
                    $this->headList[$row["id"]][$key]=$this->rowList;
                    $this->objActSheet->setCellValue("A".($num+$j),$item);
                }
            }
            $this->row = $num+$j;
        }

        $this->staffList = $arr;

        $this->totalDay=array(
            "seven"=>$this->rowList,
            "eight"=>$this->rowList,
            "auto"=>$this->rowList,
        );
    }

    //設置excel背景顏色 cellColor('A',"D6D6D6") cellColor('B2',"D6D6D6") cellColor('C3:D4',"D6D6D6")
    protected function cellColor($cells,$color){
        $this->objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    //绘制公司名字
    protected function setFirmNameList($i,$row){
        $num = 8*$i-7;
        $j=0;
        $str = $this->getStrToNum($num);
        $endStr = $this->getStrToNum($num+6);
        $this->firmList[$row["firm_id"]]=$num;
        $this->setRowContent($str."6",$row["firm_name"]);//客戶名稱      //第1行字体大小
        $this->objPHPExcel->getActiveSheet()->getStyle($str."6")->getFont()->setSize(14);
        $this->objPHPExcel->getActiveSheet()->getStyle($str."6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->objPHPExcel->getActiveSheet()->mergeCells($str."6:".$endStr."6");
        foreach ($this->rowListStr as $value){
            $str = $this->getStrToNum($num+$j);
            $this->setWidthToArr($str,$value["width"]);
            if(!empty($value["name"])){
                $this->setRowContent($str."7",$value["name"]);//客戶名稱
            }
            $j++;
        }
        $this->cellColor($str,"D6D6D6");
    }

    /*
     * $heardArr=array()
     */
    //設置表頭
    protected function setNotGroupHeard($dateTime="",$title="非集團客戶"){
        $dateTime = empty($dateTime)?"2019-7-4 14:40:45":$dateTime;
        $this->excel_date = date("Y-m-d",strtotime($dateTime));
        $this->objPHPExcel->getActiveSheet()->setTitle($title);
        $this->objPHPExcel->getActiveSheet()->freezePane('G8');
        //3.填充表格
        $this->setWidthToArr(array("A"),20);
        /*        $this->setWidthToArr(array("B","C","D","E","F"),10);
                $this->setWidthToArr(array("G","H","I","J","K","L","M","N","O","P"),13);*/
        $this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);         //第1行字体大小
        $this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);         //第2行字体大小
        $this->objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(30);    //第5行行高
        //設置基礎信息表頭
        $this->objActSheet->setCellValue('A1',"LBS GROUP");
        $this->objActSheet->setCellValue('A2',"STATUS REPORT : NON-GROUP CLIENTS");
        $this->objActSheet->setCellValue('A4',$dateTime);

        $arr = array();
        $rows = Yii::app()->db->createCommand()->select("a.*,b.id as s_id,b.firm_id,b.amt,f.firm_name")->from("sev_customer_firm b")
            ->leftJoin("sev_customer a","a.id = b.customer_id")
            ->leftJoin("sev_firm f","f.id = b.firm_id")
            ->where("a.group_type=0")->order("b.firm_id")->queryAll();
        if($rows){
            $i = 0;
            foreach ($rows as $row){
                $row["monthCount"] = $this->getMonthCountToId($row);//判斷有多少個月份欠款
                $row["staff_id"] = empty($row["staff_id"])?0:$row["staff_id"];
                if(!key_exists($row["firm_id"],$arr)){
                    $i++;
                    $this->setFirmNameList($i,$row);//繪製公司
                    $arr[$row["firm_id"]] = $this->headList;
                }
                $row["row_open"] = "open";
                foreach ($this->columnList as $key=>$item){
                    $this->setArrCount($key,$row,$arr);
                    $row["row_open"] = "close";
                }
            }
        }
        $this->excelList = $arr;
    }

    protected function getMonthCountToId(&$arr){
        if($arr["amt"]>0){
            $rows = Yii::app()->db->createCommand()->select("amt_name")->from("sev_customer_info")
                ->where("firm_cus_id = :id",array(":id"=>$arr["s_id"]))->group("amt_name,amt_gt")->queryAll();
            if($rows){
                return count($rows);
            }
        }
        return 0;
    }

    protected function getStrToNum($num){
        $arr = $this->listArr;
        if(count($arr)<=$num){
            $i = intval($num/count($arr))-1;
            $j = $num%count($arr);
            return $arr[$i].$arr[$j];
        }else{
            return $arr[$num];
        }
    }

    protected function getTotalKey($date){
        $excelDate = $this->excel_date;
        if(date("Y-m-d",strtotime($date."+7 day"))>$excelDate){
            return "seven";
        }elseif (date("Y-m-d",strtotime($date."+7 day"))<=$excelDate&&date("Y-m-d",strtotime($date."+30 day"))>$excelDate){
            return "eight";
        }else{
            return "auto";
        }
    }

    //數據疊加
    protected function setArrCount($key,$row,&$arr){
        $bool = false;
        $row["lud"] = empty($row["lud"])?$row["lcd"]:$row["lud"];
        $totalKey = $this->getTotalKey($row["lud"]);
        $excelDate = $this->excel_date;
        switch ($key){
            case "sum"://合計
                $bool = true;
                break;
            case "seven"://7天內有更新
                $bool = date("Y-m-d",strtotime($row["lud"]."+7 day"))>$excelDate;
                break;
            case "eight"://30天內有更新
                $bool = date("Y-m-d",strtotime($row["lud"]."+7 day"))<=$excelDate&&date("Y-m-d",strtotime($row["lud"]."+30 day"))>$excelDate;
                break;
            case "auto"://30天外有更新
                $bool = date("Y-m-d",strtotime($row["lud"]."+30 day"))<$excelDate;
                break;
        }
        $arrList = array(
            "total"=>$row["amt"]>0,
            "five"=>$row["monthCount"]>=5,
            "three"=>$row["monthCount"]>=3,
            "two"=>$row["monthCount"]==2,
            "one"=>$row["monthCount"]==1,
            "cod"=>intval($row["pay_type"])===1,
            "on_off"=>empty($row["on_off"]),
        );
        foreach ($arrList as $str=>$value){
            $this->resetAllArr($value,$bool,$row,$key,$totalKey,$str,$arr);
        }
    }

    protected function resetAllArr($bool2,$bool,$row,$key,$totalKey,$str,&$arr){
        if($bool2){
            if($row["row_open"]=="open"){
                $this->staffList[$row["staff_id"]]["countList"][$str]++;
            }
            if($bool){
                $arr[$row["firm_id"]][$row["staff_id"]][$key][$str]++;
                $arr[$row["firm_id"]]["total"][$key][$str]++;//公司統計
                $this->totalDay[$totalKey][$str]++;
            }
        }
    }

    protected function setNotGroupBody(){
        foreach ($this->excelList as $firm_id=>$firm_list){
            //$firm_id:集團id
            $column = $this->firmList[$firm_id];
            foreach ($firm_list as $staff_id=>$staff_list){
                //staff_id:員工id
                $row = $this->staffList[$staff_id]["startRow"];
                $i = 0;
                foreach ($staff_list as $key=>$item){
                    //$key:sum、seven、eight、auto
                    $i++;
                    $j = 0;
                    foreach ($this->rowList as $header =>$test){
                        $str = $this->getStrToNum($column+$j);
                        $this->setRowContent($str.($row+$i),$item[$header]);
                        $j++;
                    }
                }
            }
        }
    }

    protected function setTotalFooter(){
        //員工統計
        $this->row+=2;
        $this->setRowContent("A".$this->row,"TOTAL");
        $i=0;
        foreach ($this->rowListStr as $value){//統計的標題
            $i++;
            $str = $this->getStrToNum($i);
            $this->setRowContent($str.$this->row,$value["name"]);
        }
        unset($this->staffList[0]);
        unset($this->staffList["total"]);
        foreach ($this->staffList as $value){
            $this->row++;
            $this->setRowContent("A".$this->row,$value["name"]);
            $i=0;
            foreach ($this->rowList as $key=>$item){//統計的標題
                $i++;
                $str = $this->getStrToNum($i);
                $this->setRowContent($str.$this->row,$value["countList"][$key]);
            }
        }
        $this->printTable("A".($this->row-count($this->staffList)).":H".$this->row);

        //時間統計
        $this->row+=2;
        foreach ($this->totalDay as $aaa=>$dayList){
            $this->row++;
            $this->setRowContent("A".$this->row,$this->columnList[$aaa]);
            $i=0;
            foreach ($this->rowList as $key=>$item){//統計的標題
                $i++;
                $str = $this->getStrToNum($i);
                $this->setRowContent($str.$this->row,$dayList[$key]);
            }
        }
        $this->printTable("A".($this->row-2).":H".$this->row);
    }

    protected function getStaffNameToId($id){
        if(empty($id)){
            return "";
        }
        if(key_exists($id,$this->staffList)){
            return $this->staffList[$id];
        }else{
            return "";
        }
    }

    //繪製表格
    public function printTable($str){
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objActSheet->getStyle($str)->applyFromArray($styleArray);
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

    public function setNotGroupExcel($date=""){
        if (empty($date)){
            $date = date("Y-m-d H:i:s");
        }else{
            $date = date("Y-m-d H:i:s",strtotime($date));
        }
        set_time_limit(0);

        $this->setStaffList();

        $this->setNotGroupHeard($date);

        $this->setNotGroupBody();

        $this->setTotalFooter();
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
        if (file_exists($url)){
            unlink($url);
        }
        $excel = new PHPExcel_Writer_Excel2007();
        $excel->setPHPExcel($this->objPHPExcel);
        $excel->save($url);
    }
}
?>