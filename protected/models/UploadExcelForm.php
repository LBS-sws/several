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
	public $firm_id_arr=array();
	public $firm_name_us;
	public $firm_name_us_arr=array();
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

	protected $only_list;//需要導入的欠款
	protected $company_id_list=array();//excel里存在的客戶id
	protected $group_type = 0;
	protected $client_code = "";
	protected $staffOnlyList;
	protected $command;

	protected $lbsMonthArr=array();//總公司有欠款的月份
	protected $othMonthArr=array();//细公司有欠款的月份
	protected $_firmList=array();//lbs公司信息

	public function init()
    {
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
            array('file,cover_bool','safe'),
            array('cover_bool','required'),
            array('file', 'file', 'types'=>'xlsx,xls', 'allowEmpty'=>false, 'maxFiles'=>1),
		);
	}

    private function getFirmListToName($firm_name){
        $this->command->reset();
        $rows = $this->command->select("*")->from("sev_firm")
            ->where('firm_name=:firm_name',array(':firm_name'=>$firm_name))->queryRow();
        if($rows){
            $this->firm_id_arr[] = $rows["id"];
            $this->firm_name_us_arr[] = $rows["firm_name"];
            $this->_firmList[$firm_name] = $rows;
            return $rows;
        }else{
            return false;
        }
    }

    private function resetFirmList($headList){
        foreach ($headList as $str){
            $arr = explode("\n",$str);
            $arr = count($arr)==2?$arr:explode(" ",$str);
            if(count($arr)==2){
                if(!key_exists($arr[0],$this->_firmList)){
                    $this->getFirmListToName($arr[0]);
                }
            }
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

        $this->resetStaffOnlyList();//添加必須存在的員工
        $this->resetFirmList($arr["listHeader"]);//跟进excel页头重置lbs公司
        foreach ($arr["listBody"] as $list_key=> $list){
            $continue = true;
            $this->excel_list_key = $list_key;//
            $this->start_title = current($list);//每行的第一個文本
            $this->amtSum=0; //初始化每條數據
            $this->only_list=array(); //初始化每條數據
            $this->onlyArr=array( //初始化每條數據
                "lcu"=>$this->lcu,
                "lcd"=>date("Y-m-d H:i:s"),
            );
            $this->lbsMonthArr=array();
            $this->othMonthArr=array();
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
                $arrInfoList = $this->only_list;
                //$this->amtSum = $arrInfoList["amtSum"];
                $this->insertCustomer();//導入客戶資料
                foreach ($arrInfoList as $firm_id => $list){
                    $amtSum = isset($list["amtSum"])?$list["amtSum"]:0;
                    $insetId = $this->insertCustomerInfo($firm_id,$amtSum);//導入客戶與LBS的關係表
                    if(!empty($list["list"])){
                        foreach ($list["list"] as &$item){
                            $item["firm_cus_id"]=$insetId;
                            $item["customer_id"]=$this->customer_id;
                            $this->command->reset();
                            $this->command->insert("sev_customer_info", $item);//導入欠款信息
                        }
                    }
                }
            }else{
                $errNum++;
            }
            unset($continue);
        }

        if($errNum == 0){
            //Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
        }
        $this->deleteCompany();//刪除導入以外的客戶追數
        FunctionForm::refreshGroupAll();//刷新集團編號的次數及銷售員

/*        $error = implode("<br>",$this->error_list);
        Dialog::message(Yii::t('dialog','Information'), Yii::t('several','Success Num：').$successNum."<br>".Yii::t('several','Error Num：').$errNum."<br>".$error);*/
    }

    //刪除導入以外的客戶追數
    protected function deleteCompany(){
        if (!empty($this->company_id_list)){
            if(!empty($this->firm_id_arr)){//如果LBS公司為空，則不刪除
                $firmSql = " and a.firm_id in (".implode(",",$this->firm_id_arr).")";
                $companyList = implode(",",$this->company_id_list);
                $sql = "DELETE a,e FROM sev_customer_firm a
            LEFT JOIN sev_customer b ON a.customer_id = b.id
            LEFT JOIN sev_customer_info e ON a.id = e.firm_cus_id
            WHERE b.company_id NOT IN ($companyList) ".$firmSql;
                Yii::app()->db->createCommand($sql)->execute();//刪除追數信息

                $sql = "DELETE FROM sev_customer WHERE id NOT IN (
            SELECT customer_id FROM sev_customer_firm GROUP BY customer_id
            )";//刪除沒有關聯的客戶
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
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

    protected function insertCustomer(){
        $onlyArr = $this->onlyArr;
        $onlyArr["group_type"]=$this->group_type;
        $onlyArr["lbs_month"]=count($this->lbsMonthArr);
        $onlyArr["other_month"]=count($this->othMonthArr);
        $this->autoOnlyArr($onlyArr);
        $onlyArr["lud"]=$onlyArr["lcd"];
        $this->command->reset();
        $row = $this->command->select("id,firm_name_id,lbs_month,other_month,firm_name_us")->from("sev_customer")
            ->where('company_id=:company_id',array(':company_id'=>$onlyArr["company_id"]))->queryRow();
        if($row){//如果已存在客戶關係
            $updateArr = array(
                "lbs_month"=>$onlyArr["lbs_month"]+$row["lbs_month"],
                "other_month"=>$onlyArr["other_month"]+$row["other_month"],
                "firm_name_id"=>implode(",",array_unique(array_merge(explode(",",$row["firm_name_id"]),$this->firm_id_arr))),
                "firm_name_us"=>implode(",",array_unique(array_merge(explode(",",$row["firm_name_us"]),$this->firm_name_us_arr))),
            );
            if($this->cover_bool == 1){
                $this->command->reset();
                $this->command->update("sev_customer", $updateArr,"id=:id",array(":id"=>$row["id"]));
            }else{
                $onlyArr["lbs_month"] = $updateArr["lbs_month"];
                $onlyArr["other_month"] = $updateArr["other_month"];
                $onlyArr["firm_name_id"] = $updateArr["firm_name_id"];
                $onlyArr["firm_name_us"] = $updateArr["firm_name_us"];
                $this->command->reset();
                $this->command->update("sev_customer", $onlyArr,"id=:id",array(":id"=>$row["id"]));
            }
            $this->customer_id = $row["id"];
        }else{
            if($this->add_company_bool){
                $this->command->reset();
                $this->command->update("sev_company", array(
                    "group_id"=>$onlyArr["group_id"]
                ),"id=:id",array(":id"=>$onlyArr["company_id"]));
            }
            $onlyArr["firm_name_id"]=implode(",",$this->firm_id_arr);
            $onlyArr["firm_name_us"]=implode(",",$this->firm_name_us_arr);
            $this->command->reset();
            $this->command->insert("sev_customer", $onlyArr);
            $this->customer_id = Yii::app()->db->getLastInsertID();
        }
    }

    private function insertCustomerInfo($firm_id,$amtSum){
        $this->command->reset();
        $arr = $this->command->select("id")->from("sev_customer_firm")
            ->where('customer_id=:customer_id AND firm_id=:firm_id',array(':customer_id'=>$this->customer_id,':firm_id'=>$firm_id))->queryRow();
        if($arr){
            $this->command->reset();
            $this->command->delete("sev_customer_info","firm_cus_id=:id",array(":id"=>$arr["id"]));
            $this->command->reset();
            $this->command->update("sev_customer_firm", array(
                "amt"=>$amtSum,
            ),"id=:id",array(":id"=>$arr["id"]));
            return $arr["id"];
        }else{
            $this->command->reset();
            $this->command->insert("sev_customer_firm", array(
                "customer_id"=>$this->customer_id,
                "firm_id"=>$firm_id,
                "amt"=>$amtSum,
            ));
            return Yii::app()->db->getLastInsertID();
        }
    }

    protected function autoOnlyArr(&$str){
        if (empty($this->group_type)&&!key_exists("staff_id",$str)){//如果是非集團客戶
            $client_code = $this->client_code;
            $client_code = intval($client_code);
            if(is_numeric($client_code)){
                $this->command->reset();
                $rows = $this->command->select("id")->from("sev_automatic")
                    ->where('min_num<=:num and max_num>:num',array(':num'=>$client_code))->queryRow();
                if($rows){
                    $str["staff_id"] = $this->staffOnlyList[$rows["id"]];
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
        $monthKey = array_search($str,$monthList);
        return $monthKey;
    }

    private function judgeMonth($headStr,$value,$firmList){
        $amt_gt = 1;
        if(strpos($headStr,'月或之前')!==false){
            $amt_gt = 0;
            $headStr = current(explode("或之",$headStr));
        }
        $monthKey = $this->getMonthKeyToStr($headStr);
        if ($monthKey !== false){
            if(is_numeric($value)){
                /*                if(floatval($value)<0){
                                    array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$value."必須大於零（".$headStr."）"));
                                    return false;
                                }*/
                //$this->amtSum+=floatval($value);
                if($value>0){
                    if($firmList["firm_type"]==1){
                        $this->lbsMonthArr[$monthKey] = $value;
                    }else{
                        $this->othMonthArr[$monthKey] = $value;
                    }
                }
                if(!isset($this->only_list[$firmList["id"]]["amtSum"])){
                    $this->only_list[$firmList["id"]]["amtSum"] =0;
                }
                $this->only_list[$firmList["id"]]["amtSum"]+=floatval($value);
                $this->only_list[$firmList["id"]]["list"][$monthKey]=array(
                    "amt_gt"=>$amt_gt,
                    "amt_name"=>$monthKey,
                    "amt_num"=>$value,
                    "lcu"=>$this->lcu,
                );
            }else{
                array_push($this->error_list,array("key"=>$this->excel_list_key,"error"=>$value."只能為數字（".$firmList["firm_name"]."  ".$headStr."）"));
                return false;
            }

        }
        unset($arrList);
        unset($amt_gt);
        return true;
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
                    $fun =  call_user_func(array($this,$list["fun"]),$value);
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

                if(!empty($value)||$value===0){
                    $this->onlyArr[$list["sqlName"]] = $value;
                }
                return true;
            }
        }

        //判斷是否為時間(包含lbs公司)
        $arr = explode("\n",$headStr);
        $arr = count($arr)==2?$arr:explode(" ",$headStr);
        if(count($arr)==2){
            if(key_exists($arr[0],$this->_firmList)){
                $firmList = $this->_firmList[$arr[0]];
            }else{
                $firmList = false;
            }
            if($firmList){
                return $this->judgeMonth($arr[1],$value,$firmList);
            }
        }
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
                    $fun =  call_user_func(array($this,$list["fun"]),$value);
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
                if(!empty($value)||$value ===0){
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
            ->where('customer_code=:customer_code',array(':customer_code'=>$value))->queryRow();
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
                    $this->addCompanyIdList($rows["id"]);//記錄excel里的公司id
                    if($this->cover_bool == 1){
                        $firmSql = "";
                        if(!empty($this->firm_id_arr)){
                            $firmSql = " and a.firm_id in (".implode(",",$this->firm_id_arr).")";
                        }
                        $this->command->reset();
                        $list = $this->command->select("b.id")->from("sev_customer_firm a")
                            ->leftJoin("sev_customer b","a.customer_id = b.id")
                            ->where('b.company_id=:company_id'.$firmSql,
                                array(':company_id'=>$rows["id"]))->queryRow();
                        if($list){
                            return array("status"=>0,"error"=>"該客戶在已存在，不可重複添加。重複ID：".$list["id"]);
                        }
                    }
                    return array("status"=>4,"value"=>$rows["id"]);
                }else{
                    $this->command->reset();
                    $rows = $this->command->select("id")->from("sev_company")
                        ->where('client_code=:client_code or customer_name=:customer_name',array(':client_code'=>$client_code,':customer_name'=>$customer_name))->queryRow();
                    if($rows){
                        return array("status"=>0,"error"=>"客戶編號與名字不一致");
                    }else{
                        $this->add_company_bool = true;
                        $this->command->reset();
                        $this->command->insert("sev_company",
                            array("client_code"=>$client_code,"customer_name"=>$customer_name,"lcu"=>$this->lcu,"lcd"=>date("Y-m-d H:i:s"))
                        );
                        $this->addCompanyIdList(Yii::app()->db->getLastInsertID());//記錄excel里的公司id
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

    protected function addCompanyIdList($id){
        if (!array_search($id,$this->company_id_list)){
            $this->company_id_list[] = $id;
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

    public function validateReferCode($value){
        if(empty($value)){
            return array("status"=>1,"value"=>"");
        }
        if($value == 6 || $value == 8){
            $this->onlyArr["on_off"] = 0;
        }
        return array("status"=>1,"value"=>$value);
    }

    public function validateLang($value){
        if(empty($value)){
            return array("status"=>1,"value"=>"");
        }
        if(in_array($value,array("中文簡體","中文简体","中文"))){
            $value = "zh_cn";
        }elseif (in_array($value,array("中文繁體","中文繁体"))){
            $value = "zh_tw";
        }elseif ($value == "英文"){
            $value = "en_us";
        }else{
            $value = "";
        }
        return array("status"=>1,"value"=>$value);
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
            array("name"=>"聯絡人姓名","sqlName"=>"acca_username","empty"=>false),
            array("name"=>"聯絡人電話","sqlName"=>"acca_phone","empty"=>false),
            array("name"=>"找數方法","sqlName"=>"acca_fun","empty"=>false),
            array("name"=>"聯絡人語言","sqlName"=>"acca_lang","empty"=>false,"fun"=>"validateLang"),
            array("name"=>"聯絡人傳真","sqlName"=>"acca_fax","empty"=>false),
            array("name"=>"參考編號","sqlName"=>"refer_code","empty"=>false,"fun"=>"validateReferCode"),
            array("name"=>"交予同事","sqlName"=>"head_worker","empty"=>false),
            array("name"=>"其它跟進同事","sqlName"=>"other_worker","empty"=>false),
            array("name"=>"預付客戶","sqlName"=>"advance_name","empty"=>false),
            array("name"=>"月結單做法","sqlName"=>"listing_name","empty"=>false),
            array("name"=>"月結單電郵","sqlName"=>"listing_email","empty"=>false),
            array("name"=>"月結單傳真","sqlName"=>"listing_fax","empty"=>false),
            array("name"=>"客戶新增月份","sqlName"=>"new_month","empty"=>false),
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
        $this->command->reset();
        $automaticList = $this->command->select("id,staff_name")->from("sev_automatic")->queryAll();
        if($automaticList){
            foreach ($automaticList as $automatic){
                $this->command->reset();
                $rows = $this->command->select("id")->from("sev_staff")
                    ->where('staff_name=:staff_name',array(':staff_name'=>$automatic["staff_name"]))->queryRow();
                if($rows){
                    $staff_id = $rows["id"];
                }else{
                    $this->command->reset();
                    $this->command->insert("sev_staff", array("staff_name"=>$automatic["staff_name"]));
                    $staff_id = Yii::app()->db->getLastInsertID();
                }
                $arr[$automatic["id"]] = $staff_id;
            }
        }
        $this->staffOnlyList = $arr;
        unset($arr);
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
        $list=array("cover_bool");
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
