<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class UploadExcelForm extends CFormModel
{
	/* User Fields */
	public $file;
	public $onlyArr;//需要導入的數據
	public $onlyArrInfo;
	public $amtSum;
	public $error_list=array();
	public $start_title="";

    public function attributeLabels()
    {
        return array(
            'file'=>Yii::t('dialog','File Name'),
        );
    }
	/**
     *
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('file','safe'),
            array('file', 'file', 'types'=>'xlsx,xls', 'allowEmpty'=>false, 'maxFiles'=>1),
		);
	}

	//批量導入
    public function loadSeveral($arr){
	    $errNum = 0;//失敗條數
	    $successNum = 0;//成功條數
        $validateArr = $this->getList();
        foreach ($validateArr as $vaList){
            if(!in_array($vaList["name"],$arr["listHeader"])){
                Dialog::message(Yii::t('dialog','Validation Message'), $vaList["name"].Yii::t("several"," Did not find"));
                return false;
            }
        }
        foreach ($arr["listBody"] as $list){
            $continue = true;
            $this->start_title = current($list);//每行的第一個文本
            $this->amtSum=0; //初始化每條數據
            $this->onlyArrInfo=array(); //初始化每條數據
            $this->onlyArr=array( //初始化每條數據
                "lcu"=>Yii::app()->user->id,
                "city"=>Yii::app()->user->city(),
                "customer_year"=>date("Y"),
            );

            foreach ($list as $key=>$value){
                $headStr = $arr["listHeader"][$key];
                if(!$this->validateList($headStr,$value)){
                    $continue = false;
                    break;
                }
            }
            if($continue){
                $thisAmt = floatval($this->onlyArr["amt"]);
                if($this->amtSum != $thisAmt){
                    array_push($this->error_list,$this->start_title." - ".$thisAmt."剩餘數額計算錯誤，請重新計算");
                    $continue = false;
                    $errNum++;
                }else{
                    $successNum++;
                }
            }else{
                $errNum++;
            }

            if($continue){ //導入數據
                Yii::app()->db->createCommand()->insert("sev_customer", $this->onlyArr);
                $insetId = Yii::app()->db->getLastInsertID();
                if(!empty($this->onlyArrInfo)){
                    foreach ($this->onlyArrInfo as $item){
                        $item["customer_id"]=$insetId;
                        Yii::app()->db->createCommand()->insert("sev_customer_info", $item);
                    }
                }
            }
        }
        $error = implode("<br>",$this->error_list);
        Dialog::message(Yii::t('dialog','Information'), Yii::t('several','Success Num：').$successNum."<br>".Yii::t('several','Error Num：').$errNum."<br>".$error);
    }

    private function validateList($headStr,$value){
        $headList = $this->getList();
        $monthList = $this->getMonth();
        foreach ($headList as $list){
            if($list["name"] == $headStr){
                //開始驗證
                if($list["empty"]&&empty($value)){ //不能為空
                    array_push($this->error_list,$this->start_title." - ".$list["name"]."不能為空");
                    return false;
                }
                if(key_exists("fun",$list)){ //有函數驗證
                    $fun =  call_user_func(array("UploadExcelForm",$list["fun"]),$value);
                    if($fun["status"] ==  0){
                        array_push($this->error_list,$fun["error"]);
                        return false;
                    }else{
                        $value = $fun["value"];
                    }
                }

                $this->onlyArr[$list["sqlName"]] = $value;
                return true;
            }
        }
        $amt_gt = 1;
        if(strpos($headStr,'月或之')!==false){
            $amt_gt = 0;
            $headStr = current(explode("或之",$headStr));
        }
        $monthKey = array_search($headStr,$monthList);
        if ($monthKey !== false){
            if(is_numeric($value)){
                if(floatval($value)<0){
                    array_push($this->error_list,$this->start_title." - ".$value."必須大於零（$headStr）");
                    return false;
                }
                $this->amtSum+=floatval($value);
                $this->onlyArrInfo[]=array(
                    "amt_gt"=>$amt_gt,
                    "amt_name"=>$monthKey,
                    "amt_num"=>$value,
                    "lcu"=>Yii::app()->user->id,
                );
            }else{
                array_push($this->error_list,$this->start_title." - ".$value."只能為數字（$headStr）");
                return false;
            }

        }
        return true;
    }

    public function validateCode($value){
        $year = date("Y");
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_customer")
            ->where('customer_code=:customer_code AND customer_year=:customer_year',array(':customer_code'=>$value,':customer_year'=>$year))->queryRow();
        if($rows){
            return array("status"=>0,"error"=>"客戶編號已存在:".$value);
        }
        return array("status"=>1,"value"=>$value);
    }

    private function getList(){
        $arr = array(
            array("name"=>"客戶編號","sqlName"=>"customer_code","empty"=>true,"fun"=>"validateCode"),
            array("name"=>"客戶名稱","sqlName"=>"customer_name","empty"=>true),
            array("name"=>"集團公司編號","sqlName"=>"company_code","empty"=>true),
            array("name"=>"貨幣","sqlName"=>"curr","empty"=>true),
            array("name"=>"剩餘數額","sqlName"=>"amt","empty"=>false),
        );
        return $arr;
    }

    public function getMonth(){
        return array(
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
    }
}
