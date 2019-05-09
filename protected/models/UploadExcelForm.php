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
	public $file_name;
	public $file_type;
	public $file_url;
	public $year;
	public $firm_id;
	public $firm_name_us;
	public $onlyArr;//需要導入的數據
	public $onlyStaff;//需要導入的員工數據
	public $onlyArrInfo;
	public $amtSum;
	public $error_list=array();
	public $start_title="";
	public $cover_bool=1; //1:重複數據不覆蓋  2：覆蓋更新

	public $lcu; //1:重複數據不覆蓋  2：覆蓋更新
	public $city; //1:重複數據不覆蓋  2：覆蓋更新

	protected $customer_id;
	protected $code_name;//用於驗證客戶編號及名字是否一致
	protected $excel_list;//導入的list
	protected $excel_list_key;//正在導入的键值
	protected $add_company_bool;//正在導入的键值

	protected $year_list;//需要導入的年份
	protected $year_key_list;//需要導入的年份
	protected $group_type = 0;
	protected $client_code = "";
	protected $staffOnlyList;
	protected $command;

	public function init()
    {
        $this->year = date("Y");
        $this->command = Yii::app()->db->createCommand();
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function getExcelList(){
	    return $this->excel_list;
    }
    public function setExcelList($list){
	    $this->excel_list = $list;
    }

    public function attributeLabels()
    {
        return array(
            'firm_id'=>Yii::t('several','Clients firm'),
            'year'=>Yii::t('report','Year'),
            'file'=>Yii::t('dialog','File Name'),
            'cover_bool'=>Yii::t('several','overwrite files'),
        );
    }
	/**
     *
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('file,firm_id,year','safe'),
            array('firm_id','required'),
            array('cover_bool','required'),
            array('firm_id','validateFirmId'),
            array('file', 'file', 'types'=>'xlsx,xls', 'allowEmpty'=>false, 'maxFiles'=>1),
		);
	}

    public function validateFirmId($attribute, $params){
        $id = $this->firm_id;
        if(!empty($id)){
            $this->command->reset();
            $rows = $this->command->select("firm_name")->from("sev_firm")
                ->where('id=:id',array(':id'=>$id))->queryRow();
            if($rows){
                $this->firm_name_us = $rows["firm_name"];
            }else{
                $message = Yii::t('several','Clients firm'). Yii::t('several',' does not exist');
                $this->addError($attribute,$message);
            }
        }
    }

    private function onlyYearList(){
        $arr = $this->excel_list;
        $listHeader =$arr["listHeader"];
        foreach ($listHeader as $header){
            if(strpos($header,'年')!==false){
                $year = current(explode("年",$header));
                if(is_numeric($year)){
                    $this->year_key_list[$year]=$year;
                }
            }
        }

        if (empty($this->year_key_list)){
            Dialog::message(Yii::t('dialog','Validation Message'), "该excel里没有年份，无法导入");
            return false;
        }else{
            return true;
        }
    }

	//批量導入（欠款)
    public function loadSeveral($arr){
        set_time_limit(0);
        //init_set("memory_limit","128M");
        $this->excel_list = $arr;
	    $errNum = 0;//失敗條數
	    $successNum = 0;//成功條數
        $validateArr = $this->getList();
        foreach ($validateArr as $vaList){
            if(!in_array($vaList["name"],$arr["listHeader"])){
                Dialog::message(Yii::t('dialog','Validation Message'), $vaList["name"].Yii::t("several"," Did not find"));
                return false;
            }
        }
        unset($validateArr);
        //$bool = $this->onlyYearList();//查詢頁頭有幾個年份
        if (!$this->onlyYearList()){//查詢頁頭有幾個年份
            return false;
        }

        $this->resetStaffOnlyList();//添加必須存在的員工
        foreach ($arr["listBody"] as $list_key=> $list){
            $continue = true;
            $this->excel_list_key = $list_key;//
            $this->start_title = current($list);//每行的第一個文本
            $this->amtSum=0; //初始化每條數據
            $this->year_list=array(); //初始化每條數據
            $this->onlyArr=array( //初始化每條數據
                "lcu"=>$this->lcu,
                "lcd"=>date("Y-m-d H:i:s"),
            );

            foreach ($list as $key=>$value){
                $headStr = $arr["listHeader"][$key];
                if(!$this->validateList($headStr,$value)){
                    $continue = false;
                    break;
                }
                unset($headStr);
            }
            if($continue){
                //導入數據
                foreach ($this->year_list as $year_key => $arrInfoList){
                    $this->year = $year_key;
                    $this->amtSum = $arrInfoList["amtSum"];
                    $insetId = $this->insertCustormer();
                    if(!empty($arrInfoList["list"])){
                        foreach ($arrInfoList["list"] as &$item){
                            $item["firm_cus_id"]=$insetId;
                            $item["customer_id"]=$this->customer_id;
                            $this->command->reset();
                            $this->command->insert("sev_customer_info", $item);
                        }
                    }
                    unset($insetId);
                }
            }else{
                $errNum++;
            }
            unset($continue);
        }

        if($errNum == 0){
            //Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
        }
        FunctionForm::refreshGroupAll();//刷新集團編號的次數及銷售員
/*        $error = implode("<br>",$this->error_list);
        Dialog::message(Yii::t('dialog','Information'), Yii::t('several','Success Num：').$successNum."<br>".Yii::t('several','Error Num：').$errNum."<br>".$error);*/
    }

    public function exportExcel($url){
        $list = $this->excel_list["listBody"];
        $error_num = count($this->error_list);
        $myExcel = new MyExcelTwo();
        $myExcel->setRowContent("A1","完成时间：".date("Y-m-d H:i:s"),"F1");
        $myExcel->setRowContent("A2","追數公司：".$this->firm_name_us,"F2");
        $myExcel->setRowContent("A3","成功數量：".(count($list)-$error_num),"F3");
        $myExcel->setRowContent("A4","錯誤數量：$error_num","F4");
        $myExcel->setStartRow(6);
        $myExcel->setDataHeard($this->excel_list["listHeader"]);
        $myExcel->setDataBody($list,$this->error_list);
        $myExcel->saveExcel($url);
        //die();
    }

    protected function insertCustormer(){
        $onlyArr = $this->onlyArr;
        $onlyArr["group_type"]=$this->group_type;
        $this->autoOnlyArr($onlyArr);
        $this->command->reset();
        $row = $this->command->select("id,firm_name_id")->from("sev_customer")
            ->where('company_id=:company_id AND customer_year=:customer_year',array(':company_id'=>$onlyArr["company_id"],':customer_year'=>$this->year))->queryRow();
        if($row){//如果已存在客戶關係
            $this->command->reset();
            $this->command->update("sev_customer", $onlyArr,"id=:id",array(":id"=>$row["id"]));
            $this->customer_id = $row["id"];
            $this->command->reset();
            $arr = $this->command->select("id")->from("sev_customer_firm")
                ->where('customer_id=:customer_id AND firm_id=:firm_id',array(':customer_id'=>$row["id"],':firm_id'=>$this->firm_id))->queryRow();
            if($arr){//如果已存在客戶追數信息
                $this->command->reset();
                $this->command->delete("sev_customer_info","firm_cus_id=:id",array(":id"=>$arr["id"]));
                $this->command->reset();
                $this->command->update("sev_customer_firm", array(
                    "amt"=>$this->amtSum,
                ),"id=:id",array(":id"=>$arr["id"]));
                unset($onlyArr);
                return $arr["id"];
            }else{
                $sql = "update sev_customer set firm_name_id=CONCAT(firm_name_id,',".$this->firm_id."'),firm_name_us=CONCAT(firm_name_us,',".$this->firm_name_us."') where id=".$row["id"];
                Yii::app()->db->createCommand($sql)->execute();
                $this->command->reset();
                $this->command->insert("sev_customer_firm", array(
                    "customer_id"=>$row["id"],
                    "firm_id"=>$this->firm_id,
                    "amt"=>$this->amtSum,
                ));
                unset($onlyArr);
                return Yii::app()->db->getLastInsertID();
            }
        }else{

            if($this->add_company_bool){
                $this->command->reset();
                $this->command->update("sev_company", array(
                    "group_id"=>$onlyArr["group_id"]
                ),"id=:id",array(":id"=>$onlyArr["company_id"]));
            }
            $onlyArr["customer_year"]=$this->year;
            $onlyArr["firm_name_id"]=$this->firm_id;
            $onlyArr["firm_name_us"]=$this->firm_name_us;
            $this->command->reset();
            $this->command->insert("sev_customer", $onlyArr);
            $this->customer_id = Yii::app()->db->getLastInsertID();
            $this->command->reset();
            $this->command->insert("sev_customer_firm", array(
                "customer_id"=>$this->customer_id,
                "firm_id"=>$this->firm_id,
                "amt"=>$this->amtSum,
            ));
            unset($onlyArr);
            return Yii::app()->db->getLastInsertID();
        }
    }

    protected function autoOnlyArr(&$str){
        if (empty($this->group_type)&&!key_exists("staff_id",$str)){//如果是非集團客戶
            $client_code = $this->client_code;
            $client_code = intval($client_code);
            if(is_numeric($client_code)){
                if($client_code<=10){
                    $str["staff_id"] = $this->staffOnlyList["LISA"];
                }elseif (($client_code>=11&&$client_code<=119)||$client_code>=300){
                    $str["staff_id"] = $this->staffOnlyList["NATALIE"];
                }elseif ($client_code>=120&&$client_code<=199){
                    $str["staff_id"] = $this->staffOnlyList["JOANN"];
                }elseif ($client_code>=200&&$client_code<=299){
                    $str["staff_id"] = $this->staffOnlyList["DAVID"];
                }
            }
            unset($client_code);
        }
    }

    private function getGroupType($str){
        $group_type = 0;
        $code = $str;
        if(!empty($code)){
            $code = current(str_split($code,1));
            if($code != "z"&&$code!="Z"){
                $group_type = 1;
            }
        }
        unset($code);
        return $group_type;
    }

	//批量導入（集團編號)
    public function loadGroup($arr){
        set_time_limit(0);
	    $errNum = 0;//失敗條數
	    $successNum = 0;//成功條數
        $validateArr = $this->getGroupList();
        foreach ($validateArr as $vaList){
            if(!in_array($vaList["name"],$arr["listHeader"])){
                Dialog::message(Yii::t('dialog','Validation Message'), $vaList["name"].Yii::t("several"," Did not find"));
                return false;
            }
        }
        foreach ($arr["listBody"] as $list){
            $continue = true;
            $this->start_title = current($list);//每行的第一個文本
            $this->onlyArr=array();
            $this->onlyStaff=array();
            foreach ($list as $key=>$value){
                $headStr = $arr["listHeader"][$key];
                if(!$this->validateGroupList($headStr,$value)){
                    $continue = false;
                    break;
                }
            }

            if($continue){ //導入數據
                $successNum++;
                $this->command->reset();
                $this->command->insert("sev_group", $this->onlyArr);
            }else{
                $errNum++;
            }
        }
        $error = implode("<br>",$this->error_list);
        Dialog::message(Yii::t('dialog','Information'), Yii::t('several','Success Num：').$successNum."<br>".Yii::t('several','Error Num：').$errNum."<br>".$error);
    }

    private function getMonthKeyToStr($str){
        $monthList = $this->getMonth();
        if(strpos($str,'年')!==false){
            $list = explode("年",$str);
            if(count($list)==2 && is_numeric($list[0])){
                $year = $list[0];
                $month = $list[1];
                if(!key_exists($year,$this->year_list)){
                    $this->year_list[$year] = array(
                        "amtSum"=>0,
                        "list"=>array()
                    );
                }

                $monthKey = array_search($month,$monthList);
                unset($monthList);
                if ($monthKey !== false){
                    return array(
                        "monthKey"=>$monthKey,
                        "year"=>$year
                    );
                }
            }
        }
        unset($monthList);
        return false;
    }

    private function validateList($headStr,$value){
        $headList = $this->getList();
        //判斷是否為必須字段
        foreach ($headList as $list){
            if($list["name"] == $headStr){
                //開始驗證
                if($list["empty"]&&empty($value)){ //不能為空
                    array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$headStr."不能為空"));
                    return false;
                }
                if(key_exists("fun",$list)){ //有函數驗證
                    $fun =  call_user_func(array("UploadExcelForm",$list["fun"]),$value);
                    if($fun["status"] ==  0){
                        array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$fun["error"]));
                        return false;
                    }elseif($fun["status"] ==  3){
                        $this->code_name[$list["sqlName"]] = $value;
                        return true;
                    }elseif($fun["status"] ==  4){
                        $this->onlyArr["company_id"] = $fun["value"];
                        return true;
                    }elseif($fun["status"] ==  2){
                        return true;
                    }else{
                        $value = $fun["value"];
                    }
                }

                if(!empty($value)){
                    $this->onlyArr[$list["sqlName"]] = $value;
                }
                return true;
            }
        }

        //判斷是否為時間
        $amt_gt = 1;
        if(strpos($headStr,'月或之前')!==false){
            $amt_gt = 0;
            $headStr = current(explode("或之",$headStr));
        }
        $arrList = $this->getMonthKeyToStr($headStr);
        if ($arrList !== false){
            if(is_numeric($value)){
/*                if(floatval($value)<0){
                    array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$value."必須大於零（".$headStr."）"));
                    return false;
                }*/
                //$this->amtSum+=floatval($value);
                $this->year_list[$arrList["year"]]["amtSum"]+=floatval($value);
                $this->year_list[$arrList["year"]]["list"][]=array(
                    "amt_gt"=>$amt_gt,
                    "amt_name"=>$arrList["monthKey"],
                    "amt_num"=>$value,
                    "lcu"=>$this->lcu,
                );
            }else{
                array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$value."只能為數字（".$headStr."）"));
                return false;
            }

        }
        unset($arrList);
        unset($amt_gt);
        return true;
    }

    private function validateGroupList($headStr,$value){
        $headList = $this->getGroupList();
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

                if($list["sqlName"]=="assign_date"&&!empty($value)){
                    $n = intval(($value - 25569) * 3600 * 24);     //转换成1970年以来的秒数
                    $value = gmdate('Y-m-d',$n);
                }
                if(!empty($value)){
                    $this->onlyArr[$list["sqlName"]] = $value;
                }
                return true;
            }
        }
        return true;
    }

    public function validateCode($value){
        $this->command->reset();
        $rows = $this->command->select("id")->from("sev_customer")
            ->where('customer_code=:customer_code AND customer_year=:customer_year',array(':customer_code'=>$value,':customer_year'=>date("Y")))->queryRow();
        if($rows){
            unset($rows);
            return array("status"=>0,"error"=>"客戶編號已存在:".$value);
        }
        unset($rows);
        return array("status"=>1,"value"=>$value);
    }

    public function validateGroupCode($value){
        $this->command->reset();
        $rows = $this->command->select("id")->from("sev_group")
            ->where('company_code=:company_code',array(':company_code'=>$value))->queryRow();
        if($rows){
            unset($rows);
            return array("status"=>0,"error"=>"集團編號已存在:".$value);
        }
        unset($rows);
        return array("status"=>1,"value"=>$value);
    }

    public function validateGroupOld($value){
        $this->group_type = $this->getGroupType($value);
        if(!empty($value)){
            $this->command->reset();
            $rows = $this->command->select("id")->from("sev_group")
                ->where('company_code=:company_code',array(':company_code'=>$value))->queryRow();
            if($rows){
                return array("status"=>1,"value"=>$rows["id"]);
            }else{
                unset($rows);
                $this->command->reset();
                $this->command->insert("sev_group", array("company_code"=>$value,"lcu"=>$this->lcu,"lcd"=>date("Y-m-d H:i:s")));
                return array("status"=>1,"value"=>Yii::app()->db->getLastInsertID());
            }
        }else{
            $this->add_company_bool = false;
            return array("status"=>2,"value"=>"");
        }
    }

    public function validateCusCode($value){
        $this->add_company_bool = false;
        $arr = $this->code_name;
        if(is_array($arr)){
            if(count($arr)===1){
                $this->code_name = array();
                if(key_exists("customer_name",$arr)){
                    $client_code = $value;
                    $customer_name = $arr["customer_name"];
                }else{
                    $client_code = $arr["client_code"];
                    $customer_name = $value;
                }
                $this->client_code = $client_code;
                $this->command->reset();
                $rows = $this->command->select("id")->from("sev_company")
                    ->where("client_code=:client_code and customer_name=:customer_name",
                        array(':client_code'=>$client_code,':customer_name'=>$customer_name))->queryRow();
                if($rows){
                    unset($client_code);
                    unset($customer_name);
                    if($this->cover_bool == 1){
                        if (!empty($this->year_key_list)){
                            foreach ($this->year_key_list as &$year){
                                $this->command->reset();
                                $list = $this->command->select("b.id")->from("sev_customer_firm a")
                                    ->leftJoin("sev_customer b","a.customer_id = b.id")
                                    ->where('b.company_id=:company_id and b.customer_year=:year and a.firm_id=:firm_id',
                                        array(':company_id'=>$rows["id"],':year'=>$year,':firm_id'=>$this->firm_id))->queryRow();
                                if($list){
                                    return array("status"=>0,"error"=>"該客戶在 $year 已存在，不可重複添加。重複ID：".$list["id"]);
                                }
                            }
                        }
                    }

                    return array("status"=>4,"value"=>$rows["id"]);
                }else{
                    $this->command->reset();
                    $this->command->select("id")->from("sev_company")
                        ->where('client_code=:client_code or customer_name=:customer_name',array(':client_code'=>$client_code,':customer_name'=>$customer_name))->queryRow();
                    if($rows){
                        unset($client_code);
                        unset($customer_name);
                        return array("status"=>0,"error"=>"客戶編號與名字不一致");
                    }else{
                        $this->add_company_bool = true;
                        $this->command->reset();
                        $this->command->insert("sev_company",
                            array("client_code"=>$client_code,"customer_name"=>$customer_name,"lcu"=>$this->lcu,"lcd"=>date("Y-m-d H:i:s"))
                        );
                        unset($client_code);
                        unset($customer_name);
                        return array("status"=>4,"value"=>Yii::app()->db->getLastInsertID());
                    }
                }
            }else{
                return array("status"=>3,"value"=>$value);
            }
        }else{
            $this->code_name = array();
            return array("status"=>3,"value"=>$value);
        }
    }

    public function validateStaff($value){
        if(empty($value)){
            return array("status"=>1,"value"=>"");
        }
        $this->command->reset();
        $rows = $this->command->select("id")->from("sev_staff")
            ->where('staff_name=:staff_name',array(':staff_name'=>$value))->queryRow();
        if($rows){
            return array("status"=>1,"value"=>$rows["id"]);
        }else{
            unset($rows);
            $this->command->reset();
            $this->command->insert("sev_staff", array("staff_name"=>$value));
            return array("status"=>1,"value"=>Yii::app()->db->getLastInsertID());
        }
    }

    private function getList(){
        return array(
            array("name"=>"客戶編號","sqlName"=>"client_code","empty"=>true,"fun"=>"validateCusCode"),
            array("name"=>"客戶名稱","sqlName"=>"customer_name","empty"=>true,"fun"=>"validateCusCode"),
            array("name"=>"集團號碼","sqlName"=>"group_id","empty"=>false,"fun"=>"validateGroupOld"),
            array("name"=>"指派員工","sqlName"=>"staff_id","empty"=>false,"fun"=>"validateStaff"),
            array("name"=>"銷售員","sqlName"=>"salesman_id","empty"=>false,"fun"=>"validateStaff"),
            array("name"=>"付款期限","sqlName"=>"payment","empty"=>false),
            //array("name"=>"貨幣","sqlName"=>"curr","empty"=>true),
            //array("name"=>"剩餘數額","sqlName"=>"amt","empty"=>false),
        );
    }

    private function getGroupList(){
        $arr = array(
            array("name"=>"集團編號","sqlName"=>"company_code","empty"=>true,"fun"=>"validateGroupCode"),
            array("name"=>"指派員工","sqlName"=>"assign_id","empty"=>true,"fun"=>"validateStaff"),
            array("name"=>"指派日期","sqlName"=>"assign_date","empty"=>false),
            array("name"=>"跨區","sqlName"=>"cross_district","empty"=>false),
        );
        return $arr;
    }

    private function resetStaffOnlyList(){
        $arr = array();
        $staffList = array("LISA","NATALIE","JOANN","DAVID");
        foreach ($staffList as $staff){
            $this->command->reset();
            $rows = $this->command->select("id")->from("sev_staff")
                ->where('staff_name=:staff_name',array(':staff_name'=>$staff))->queryRow();
            if($rows){
                $arr[$staff] = $rows["id"];
            }else{
                $this->command->reset();
                $this->command->insert("sev_staff", array("staff_name"=>$staff));
                $arr[$staff] = Yii::app()->db->getLastInsertID();
            }
            unset($rows);
        }
        $this->staffOnlyList = $arr;
        unset($arr);
        unset($staffList);
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

    public function getYear(){
        $arr = array(""=>"");
        $year = date("Y");
        for ($i=$year-5;$i<$year+5;$i++){
            $arr[$i] = $i.Yii::t('report','Year');
        }
        unset($year);
        return $arr;
    }

    public function getCoverType(){
        $arr = array(1=>"重複數據不覆蓋",2=>"數據覆蓋上傳");
        return $arr;
    }

    public function save(){
        $file = CUploadedFile::getInstance($this,'file');
        $city = Yii::app()->user->city();
        $url = "upload/excel/".$city."/".date("YmdHis").".".$file->getExtensionName();
        $this->file = $file->getName();
        $this->file_name = $file->getName();
        $this->file_type = $file->getExtensionName();
        $this->file_url = $url;

        $path =Yii::app()->basePath."/../upload/";
        if (!file_exists($path)){
            mkdir($path);
        }
        $path =Yii::app()->basePath."/../upload/excel/";
        if (!file_exists($path)){
            mkdir($path);
        }
        $path.=$city."/";
        if (!file_exists($path)){
            mkdir($path);
        }
        $file->saveAs($url);

         return $this->saveSql();
    }

    protected function saveSql(){
        $list=array("firm_id","cover_bool");
        $postList = $_POST["UploadExcelForm"];

        $loadExcel = new LoadExcel($this->file_url,false);
        $header = $loadExcel->getListHeader();


        $validateArr = $this->getList();
        foreach ($validateArr as $vaList){
            if(!in_array($vaList["name"],$header)){
                unlink($this->file_url);
                Dialog::message(Yii::t('dialog','Validation Message'), $vaList["name"].Yii::t("several"," Did not find"));
                return false;
            }
        }
        $this->excel_list["listHeader"] = $header;
        $bool = $this->onlyYearList();//查詢頁頭有幾個年份
        if (!$bool){
            unlink($this->file_url);
            return false;
        }

        $this->command->insert("sev_file", array(
            "handle_name"=>"追数导入",
            "file_name"=>$this->file_name,
            "file_type"=>$this->file_type,
            "file_url"=>$this->file_url,
            "state"=>"P",
            "lcu"=>Yii::app()->user->id,
            //"lcd"=>date("Y-m-d H:i:s"),
        ));

        $id =Yii::app()->db->getLastInsertID();
        foreach ($list as $item){
            if (key_exists($item,$postList)){
                $this->command->reset();
                $this->command->insert("sev_file_info", array(
                    "file_id"=>$id,
                    "option_name"=>$item,
                    "option_value"=>$postList[$item]
                ));
            }
        }
        return true;
    }
}
