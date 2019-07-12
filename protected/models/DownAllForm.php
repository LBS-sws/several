<?php

class DownAllForm {
    protected $objPHPExcel;
    protected $objActSheet;
    protected $listArr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    protected $row = 1;
    protected $sheetNum = 0;


    //集團客戶
    protected $monthList = array();
    protected $endNum = 0;
    protected $endMonth = 0;//用於計算30天以外的合計
    protected $staffList = array();
    protected $totalAll = array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0);//所有客户的合計
    protected $totalStaff = array();//员工合计
    protected $groupNumList=array();//集團數量不需要重複查詢

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

    protected function resetMonth($monthList,$startNum=0){
        $arr = array();
        if(!empty($monthList)){
            $num = $startNum;
            foreach ($monthList as $key => $month){
                $this->monthList[$key] = $this->listArr[$num];
                $arr[] = $month;
                $num++;
            }
        }
        return $arr;
    }

    //獲取追數系統共有多少月份
    protected function getMonthList(){
        $arr = array();
        $monthList = array(
            1=>"一月",
            2=>"二月",
            3=>"三月",
            4=>"四月",
            5=>"五月",
            6=>"六月",
            7=>"七月",
            8=>"八月",
            9=>"九月",
            10=>"十月",
            11=>"十一月",
            12=>"十二月",
        );
        $rows = Yii::app()->db->createCommand()->select("amt_name,amt_gt")->from("sev_customer_info")
            ->group("amt_name,amt_gt")->order("amt_gt,CAST(amt_name as SIGNED) ASC")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["amt_name"]]=$monthList[$row["amt_name"]];
                if($row["amt_gt"] != 1){
                    $arr[$row["amt_name"]].="或之前";
                }
            }
            $this->endMonth = $row["amt_name"];
        }
        return $arr;
    }

    //設置所有員工
    protected function setStaffList(){
        //集團次數一次性查完
        $rows = Yii::app()->db->createCommand()->select("group_id,count(group_id) as group_num")->from("sev_customer")
            ->where("group_type = 1")
            ->group("group_id")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $this->groupNumList[$row["group_id"]]=$row["group_num"];
            }
        }
        //員工初始化
        $arr = array();
        $rows = Yii::app()->db->createCommand()->select("id,staff_name")->from("sev_staff")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]]=$row["staff_name"];
                $this->totalStaff[$row["id"]]=array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0,"staff_name"=>$row["staff_name"],"staff_num"=>0);
            }
        }
        $this->totalStaff[0]=array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0,"staff_name"=>"","staff_num"=>0);
        $this->staffList = $arr;

    }

    /*
     * $heardArr=array()
     */
    //設置表頭
    protected function setGroupHeard($date,$title="集團客戶"){
        $heardArr=array(
            "dateTime"=>$date,
            "monthList"=>$this->getMonthList()
        );
        $this->objPHPExcel->getActiveSheet()->setTitle($title);
        $this->objPHPExcel->getActiveSheet()->freezePane('H6');
        //3.填充表格
        $this->setWidthToArr(array("A"),15);
        $this->setWidthToArr(array("B","C","D","E","F"),10);
        $this->setWidthToArr(array("G","H","I","J","K","L","M","N","O","P"),13);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);         //第1行字体大小
        $this->objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);         //第2行字体大小
        $this->objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);    //第5行行高
        //設置基礎信息表頭
        $this->objActSheet->setCellValue('A1',"LBS GROUP");
        $this->objActSheet->setCellValue('A2',"STATUS REPORT : GROUP CLIENTS");
        $this->objActSheet->setCellValue('A4',$heardArr["dateTime"]);

        //設置客戶信息表頭
        $arrOne = array("客戶名稱","集團編號","付款期限","指派員工","欠款月數","集團編號次數","銷售員");
        foreach ($arrOne as $key=>$item){
            $str = $this->listArr[$key];
            $this->setRowContent($str."5",$item);
        }
        //設置月份表頭
        $this->resetMonth($heardArr["monthList"],count($arrOne));
        foreach ($heardArr["monthList"] as $key=>$item){
            $str = $this->monthList[$key];
            $this->setRowContent($str."5",$item);
        }
        $endNum = $this->endNum = count($arrOne)+count($heardArr["monthList"]);
        //設置統計表頭
        $arrThree = array(
            array("value"=>"合計","width"=>8),
            array("value"=>"30天以前的合計","width"=>14),
            array("value"=>"總公司以外的合計","width"=>15),
            array("value"=>"工作流程","width"=>50),
            array("value"=>"狀態","width"=>8)
        );
        foreach ($arrThree as $item){
            $str = $this->listArr[$endNum];
            $this->objPHPExcel->getActiveSheet()->getColumnDimension($str)->setWidth($item["width"]);
            $this->setRowContent($str."5",$item["value"]);
            $endNum++;
        }

        $this->row = 6;

    }

    protected function setGroupBody(){
//$arrOne = array("客戶名稱","集團編號","付款期限","指派員工","欠款月數","stores","銷售員");
        $rows = Yii::app()->db->createCommand()->select("a.*,sum(b.amt) as sum_num,g.client_code,g.customer_name,f.company_code as group_code")->from("sev_customer_firm b")
            ->leftJoin("sev_customer a","a.id = b.customer_id")
            ->leftJoin("sev_company g","g.id = a.company_id")
            ->leftJoin("sev_group f","f.id = a.group_id")
            ->where("a.group_type=1")
            ->group("a.id")->queryAll();
        if ($rows){
            $num = $this->row;
            foreach ($rows as $row){
                //echo $row["id"]." --  ".memory_get_usage()."\n";
                $staff_id = empty($row["staff_id"])?0:$row["staff_id"];
                $this->totalStaff[$staff_id]["staff_num"]++;
                $this->setRowContent("A".$num,$row["customer_name"]);//客戶名稱
                $this->setRowContent("B".$num,$row["group_code"]);//集團編號
                $this->setRowContent("C".$num,$row["payment"]);//付款期限
                $this->setRowContent("D".$num,$this->getStaffNameToId($row["staff_id"]));//指派員工
                //$this->setRowContent("E".$num,$row["payment"]);//欠款月數
                $this->setRowContent("F".$num,$this->getGroupNumToGroupId($row["group_id"]));//stores
                $this->setRowContent("G".$num,$this->getStaffNameToId($row["salesman_id"]));//銷售員
                $this->setRowContent($this->listArr[$this->endNum].$num,$row["sum_num"]);//合計
                $this->setRowContent($this->listArr[($this->endNum+3)].$num,$this->getRemark($row["id"]));//流程
                $this->objPHPExcel->getActiveSheet()->getStyle($this->listArr[($this->endNum+3)].$num)->getAlignment()->setWrapText(true);
                $this->setRowContent($this->listArr[($this->endNum+4)].$num,$row["state"]);//狀態

                $this->setMonthMoneyToId($row["id"],$num);
                $num++;
            }
            $num++;
            $this->setRowContent("A".$num,"Sub-total");//寫入統計
            $this->setTotalListToExcel($this->totalAll,$num);//寫入統計
            $num++;
            $this->setRowContent("A".$num,"Total");//寫入統計
            $this->setTotalListToExcel($this->totalAll,$num);//寫入統計
            $this->row=$num+3;
        }
    }
    //獲取集團編號出現次數
    protected function getGroupNumToGroupId($group_id){
        if(key_exists($group_id,$this->groupNumList)){
            return $this->groupNumList[$group_id];
        }else{
            $num = Yii::app()->db->createCommand()->select("count(*)")->from("sev_customer")
                ->where("group_id=$group_id")->queryScalar();
            $this->groupNumList[$group_id] = $num;
            return $num;
        }
    }

    //獲取客戶流程
    protected function getRemark($customer_id){
        $html = "";
        $rows = Yii::app()->db->createCommand()->select("a.*,f.disp_name")->from("sev_remark_list a")
            ->leftJoin("sev_customer_firm b","a.firm_cus_id = b.id")
            ->leftJoin("sev_customer g","g.id = b.customer_id")
            ->leftJoin("sec_user f","f.username = a.lcu")
            ->where("g.id=$customer_id")
            ->order("b.id,a.lcd desc")->queryAll();
        if($rows){
            $num = 0;
            foreach ($rows as $row){
                $num++;
                if(count($rows)>1){
                    $html.="($num) ";
                }
                $html.=$row["remark"]." -- ".$row["disp_name"]." -- ".$row["lcd"];
                $html.="\n";
            }
        }
        return $html;
    }

    //獲取客戶每月統計
    protected function setMonthMoneyToId($customer_id,$num){
        //$this->setRowContent("A".$num,$row["customer_name"]);//客戶名稱
        $rows = Yii::app()->db->createCommand()->select("a.amt_name,a.amt_num,f.firm_type,g.staff_id")->from("sev_customer_info a")
            ->leftJoin("sev_customer_firm b","a.firm_cus_id = b.id")
            ->leftJoin("sev_customer g","g.id = b.customer_id")
            ->leftJoin("sev_firm f","f.id = b.firm_id")
            ->where("g.id=$customer_id")->queryAll();
        if($rows){
            $arrAll = array();
            $sumFirm = 0;
            $sumDay = 0;
            foreach ($rows as $row){
                //array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0,"staff_name"=>$row["staff_name"],"staff_num"=>0)
                if(empty($row["staff_id"])){
                    $row["staff_id"] = 0;
                }
                if(!key_exists($row["amt_name"],$arrAll)){
                    $arrAll[$row["amt_name"]] = 0;
                }
                //統計負責員工
                if(!key_exists($row["amt_name"],$this->totalStaff[$row["staff_id"]]["arrAll"])){
                    $this->totalStaff[$row["staff_id"]]["arrAll"][$row["amt_name"]] = 0;
                }
                if($row["firm_type"]!=1){
                    $sumFirm+=$row["amt_num"];
                    //統計負責員工
                    $this->totalStaff[$row["staff_id"]]["sumFirm"]+=$row["amt_num"];
                }
                if($row["amt_name"]!=$this->endMonth){
                    $sumDay+=$row["amt_num"];
                    //統計負責員工
                    $this->totalStaff[$row["staff_id"]]["sumDay"]+=$row["amt_num"];
                }
                $arrAll[$row["amt_name"]]+=$row["amt_num"];
                //統計負責員工
                $this->totalStaff[$row["staff_id"]]["arrAll"][$row["amt_name"]]+=$row["amt_num"];

            }
            $this->setTotalListToExcel($arr=array("arrAll"=>&$arrAll,"sumFirm"=>$sumFirm,"sumDay"=>$sumDay),$num,true);
            $this->setRowContent("E".$num,count($arrAll));//月份欠款
        }
    }

    protected function setTotalListToExcel($arr=array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0),$num,$bool = false){
        $sum = 0;
        if($bool){
            $this->totalAll["sumFirm"]+=$arr["sumFirm"];
            $this->totalAll["sumDay"]+=$arr["sumDay"];
        }
        $this->setRowContent($this->listArr[($this->endNum+1)].$num,$arr["sumDay"]);//30天以前的合計
        $this->setRowContent($this->listArr[($this->endNum+2)].$num,$arr["sumFirm"]);//总公司以外的合計
        foreach ($arr["arrAll"] as $key => $item){
            $sum+=$item;
            if($bool){
                if(!key_exists($key,$this->totalAll["arrAll"])){
                    $this->totalAll["arrAll"][$key] = 0;
                }
                $this->totalAll["arrAll"][$key]+=$item;
            }
            if(empty($item)){
                $item = "-";
                unset($arr["arrAll"][$key]);
            }
            $str = $this->monthList[$key];
            $this->setRowContent($str.$num,$item);//月份欠款
        }
        if(!$bool){
            $this->setRowContent($this->listArr[$this->endNum].$num,$sum);//合計
        }
    }

    protected function setStaffFooter(){
        $num = $this->row;
        foreach ($this->totalStaff as $item){
            //array("arrAll"=>array(),"sumFirm"=>0,"sumDay"=>0,"staff_name"=>$row["staff_name"],"staff_num"=>0)
            $this->setRowContent("A".$num,$item["staff_name"]);//員工名字
            $this->setRowContent("B".$num,$item["staff_num"]);//員工收款次數
            $this->setTotalListToExcel($item,$num);
            $num++;
        }
        $this->row++;
        $this->printTable($this->endNum+3,count($this->totalStaff)-2);
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
    public function printTable($num,$row=0){
        $str = $this->listArr[$num-1];
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objActSheet->getStyle('A'.($this->row-1).':'.$str.($this->row+$row))->applyFromArray($styleArray);
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

    public function setGroupExcl($date=""){
        if (empty($date)){
            $date = date("Y-m-d H:i:s");
        }else{
            $date = date("Y-m-d H:i:s",strtotime($date));
        }
        set_time_limit(0);

        $this->setStaffList();

        $this->setGroupHeard($date);

        $this->setGroupBody();

        $this->setStaffFooter();
    }

    public function setNotGroupExcel(){
        set_time_limit(0);
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