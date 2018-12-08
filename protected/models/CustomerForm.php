<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class CustomerForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $customer_code;
	public $customer_name;
	public $customer_year;
	public $company_code;
	public $curr;
	public $amt;
	public $lcd;
	public $info_arr=array();
	protected $validateMonth;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'customer_code'=>Yii::t('several','Customer Code'),
            'customer_name'=>Yii::t('several','Customer Name'),
            'customer_year'=>Yii::t('several','Customer Year'),
            'company_code'=>Yii::t('several','Company Code'),
            'curr'=>Yii::t('several','Curr'),
            'amt'=>Yii::t('several','Amt'),
            'info_arr'=>Yii::t('several','Info Arr'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, customer_code, customer_name, customer_year, company_code, curr, amt, info_arr','safe'),
			array('customer_code','required'),
			array('customer_name','required'),
			array('company_code','required'),
			array('curr','required'),
			array('info_arr','required'),
			array('info_arr','required'),
			array('customer_code','validateCode'),
			array('info_arr','validateInfoArr'),
		);
	}

	public function validateCode($attribute, $params){
	    $this->amt = 0;//初始化欠款金額
        $id = -1;
        if(!empty($this->id)){
            $id = $this->id;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_customer")
            ->where('customer_code=:customer_code and id!=:id',
                array(':customer_code'=>$this->customer_code,':id'=>$id))->queryAll();
        if(count($rows)>0){
            $message = Yii::t('several','Customer Code'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

	public function validateInfoArr($attribute, $params){
	    $infoArr = $this->info_arr;
	    if(empty($infoArr)){
            $message = Yii::t('several','Info Arr'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
            return false;
        }
        $this->validateMonth = array();
	    $monthList = UploadExcelForm::getMonth();
        foreach ($infoArr as $list){
            if (empty($list["amt_name"])&&$list["amt_name"] === ""){
                $message = Yii::t('several','Amt Name'). "不能為空";
                $this->addError($attribute,$message);
                return false;
            }
            if (!key_exists($list["amt_name"],$monthList)){
                $message = Yii::t('several','Amt Name'). "格式不正確";
                $this->addError($attribute,$message);
                return false;
            }
            if (!key_exists($list["amt_gt"],$this->getGtOrEgt())){
                $message = Yii::t('several','Amt Name'). "格式不正確";
                $this->addError($attribute,$message);
                return false;
            }
            if (!is_numeric($list["amt_num"])){
                $message = Yii::t('several','Amt Num'). "只能為數字";
                $this->addError($attribute,$message);
                return false;
            }
            if (floatval($list["amt_num"])<0){
                $message = Yii::t('several','Amt Num'). "不能小於0";
                $this->addError($attribute,$message);
                return false;
            }
            if(in_array($list["amt_name"],$this->validateMonth)){
                $message = Yii::t('several','Amt Name'). "不能重复";
                $this->addError($attribute,$message);
                return false;
            }
            $this->validateMonth[]=$list["amt_name"];
            $this->amt+=floatval($list["amt_num"]);
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    protected function getGtOrEgt(){
        return array(0=>Yii::t("several","lt"),1=>Yii::t("several","eq"));
    }

    public function tBodyTdHtml($arr=array()){
        if(empty($arr)){
            $arr = array("key" =>":key","amt_name" =>"","amt_gt" =>0,"amt_num" =>"","bgColor" =>"","readonly" =>false);
        }
        $className = "CustomerForm";
        $html = '<tr data-key="'.$arr["key"].'" class="'.$arr["bgColor"].'">';
        $html .= '<td><div class="input-group">';
        $html.='<div class="input-group-btn">'.TbHtml::dropDownList($className."[info_arr][".$arr["key"]."][amt_gt]",$arr["amt_gt"],$this->getGtOrEgt(),array("class"=>"form-control","readonly"=>$arr["readonly"],"style"=>"width:80px"))."</div>";
        $html.=TbHtml::dropDownList($className."[info_arr][".$arr["key"]."][amt_name]",$arr["amt_name"],UploadExcelForm::getMonth(),array("class"=>"form-control","readonly"=>$arr["readonly"]));
        $html.="</div></td>";
        $html .= "<td>".TbHtml::numberField($className."[info_arr][".$arr["key"]."][amt_num]",$arr["amt_num"],array("class"=>"form-control changeAmt","min"=>0,"readonly"=>$arr["readonly"]))."</td>";
        if(!$arr["readonly"]){
            $html .= "<td>".TbHtml::button(Yii::t('misc','Delete'),array("class"=>"delWage btn btn-warning"))."</td>";
        }
        $html .= "</tr>";
	    return $html;
    }

    public function printInfoBody(){
        $bool = $this->onlyReadySearch();
	    $arr = $this->info_arr;
        $html = "<table class='table table-bordered'><thead><tr><th width='50%'>".Yii::t('several','Amt Name')."</th><th width='50%'>".Yii::t('several','Amt Num')."</th>";
        if(!$bool){
            $html.="<th></th>";
        }
        $html.="</tr></thead><tbody id='amt_body'>";
        if(!empty($arr)){
            foreach ($arr as $key=>$list){
                $bgColor = floatval($list["amt_num"])>0?"bg-danger":"";
                $html .= $this->tBodyTdHtml(array("key" =>$key,"amt_name" =>$list["amt_name"],"amt_gt" =>$list["amt_gt"],"amt_num" =>$list["amt_num"],"bgColor" =>$bgColor,"readonly" =>$bool));
            }
        }else{
            $html .= $this->tBodyTdHtml(array("key" =>0,"amt_name" =>"","amt_gt" =>1,"amt_num" =>"","bgColor" =>"","readonly" =>$bool));
        }
        $html.="</tbody>";
        if(!$bool){
            $html.="<tfoot><tr><td colspan='2'></td>";
            $html.="<td>".TbHtml::button(Yii::t('misc','Add'),array("class"=>"btn btn-primary","id"=>"addAmtTr"))."</td>";
            $html.="</tr></tfoot>";
        }
        $html.="</table>";
        return $html;
    }


    //刪除验证
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer")
            ->where('id=:id and amt=0', array(':id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
                $info_arr = Yii::app()->db->createCommand()->select("*")->from("sev_customer_info")
                    ->where('customer_id=:customer_id',
                        array(':customer_id'=>$row['id']))->queryAll();
                if(!$rows){
                    $info_arr = array();
                }
				$this->id = $row['id'];
				$this->customer_code = $row['customer_code'];
                $this->customer_name = $row['customer_name'];
                $this->customer_year = $row['customer_year'];
                $this->company_code = $row['company_code'];
                $this->curr = $row['curr'];
                $this->amt = $row['amt'];
                $this->info_arr = $info_arr;
				break;
			}
		}
		return true;
	}
	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveStaff($connection);
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			var_dump($e);
			throw new CHttpException(404,'Cannot update.');
		}
	}

	protected function saveStaff(&$connection)
	{
		$sql = '';
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from sev_customer where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_customer(
							customer_code, customer_name, customer_year, company_code, curr, amt, city, lcu
						) values (
							:customer_code, :customer_name, :customer_year, :company_code, :curr, :amt, :city, :lcu
						)";
				break;
			case 'edit':
				$sql = "update sev_customer set
							customer_code = :customer_code, 
							customer_name = :customer_name, 
							company_code = :company_code, 
							curr = :curr,  
							amt = :amt,  
							luu = :luu
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':customer_code')!==false)
			$command->bindParam(':customer_code',$this->customer_code,PDO::PARAM_STR);
		if (strpos($sql,':customer_name')!==false)
			$command->bindParam(':customer_name',$this->customer_name,PDO::PARAM_STR);
		if (strpos($sql,':customer_year')!==false){
            $this->customer_year = date("Y");
            $command->bindParam(':customer_year',$this->customer_year,PDO::PARAM_INT);
        }
		if (strpos($sql,':company_code')!==false)
			$command->bindParam(':company_code',$this->company_code,PDO::PARAM_STR);
		if (strpos($sql,':curr')!==false)
			$command->bindParam(':curr',$this->curr,PDO::PARAM_STR);
        if (strpos($sql,':amt')!==false)
            $command->bindParam(':amt',$this->amt,PDO::PARAM_INT);

		if (strpos($sql,':city')!==false)
			$command->bindParam(':city',$city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);

		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->setScenario("edit");
        }
        Yii::app()->db->createCommand()->delete('sev_customer_info', 'customer_id=:customer_id', array(':customer_id'=>$this->id));
        if(!empty($this->info_arr)){
            foreach ($this->info_arr as $item){
                $item["customer_id"]=$this->id;
                Yii::app()->db->createCommand()->insert("sev_customer_info", $item);
            }
        }
        return true;
	}

}
