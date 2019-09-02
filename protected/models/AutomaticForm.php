<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class AutomaticForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $min_num=0;
	public $max_num;
	public $staff_name;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'staff_name'=>Yii::t('several','staff name'),
            'min_num'=>Yii::t('several','automatic min'),
            'max_num'=>Yii::t('several','automatic max'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, staff_name, min_num, max_num','safe'),
			array('staff_name','required'),
			array('min_num','required'),
			array('max_num','required'),
			//array('cross','required'),
			array('min_num','validateNum'),
		);
	}

	public function validateNum($attribute, $params){
	    if(is_numeric($this->max_num)&&is_numeric($this->max_num)){
	        if($this->min_num>$this->max_num){
                $message = "最小值不能大于最大值";
                $this->addError($attribute,$message);
            }else{
                $rows = Yii::app()->db->createCommand()->select("*")->from("sev_automatic")
                    ->where('((min_num<=:min_num and max_num>:min_num) or (min_num<=:max_num and max_num>:max_num) or (min_num>:min_num and max_num<:max_num)) and id!=:id',
                        array(':min_num'=>$this->min_num,':max_num'=>$this->max_num,':id'=>$this->id))->queryRow();
                if($rows){
                    $message = "该规则的区间范围有重复，请修改后保存。"."<br>".$rows["staff_name"]."（最小值：".$rows["min_num"]."，最大值：".$rows["max_num"]."）";
                    $this->addError($attribute,$message);
                }
            }
        }else{
            $message = "最大值和最小值必须为数字";
            $this->addError($attribute,$message);
        }
    }

    //刪除验证
	public function validateDelete(){
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_automatic")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->staff_name = $row['staff_name'];
                $this->min_num = $row['min_num'];
                $this->max_num = $row['max_num'];
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
                $sql = "delete from sev_automatic where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_automatic(
							staff_name, max_num, min_num, lcu,lcd
						) values (
							:staff_name, :max_num, :min_num, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_automatic set
							staff_name = :staff_name, 
							min_num = :min_num, 
							max_num = :max_num, 
							luu = :luu
						where id = :id
						";
				break;
		}//id, company_code, assign_staff, occurrences, assign_date, salesman_one_ts, cross

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':staff_name')!==false)
			$command->bindParam(':staff_name',$this->staff_name,PDO::PARAM_STR);
		if (strpos($sql,':min_num')!==false)
			$command->bindParam(':min_num',$this->min_num,PDO::PARAM_INT);
		if (strpos($sql,':max_num')!==false)
			$command->bindParam(':max_num',$this->max_num,PDO::PARAM_INT);

		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcd')!==false){
            $lcd = date("Y-m-d H:i:s");
            $command->bindParam(':lcd',$lcd,PDO::PARAM_STR);
        }

		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->setScenario("edit");
        }
        return true;
	}

}
