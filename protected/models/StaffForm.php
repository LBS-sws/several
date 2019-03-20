<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class StaffForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $staff_name;
	public $staff_type;
	public $staff_phone;
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
            'staff_type'=>Yii::t('several','staff type'),
            'staff_phone'=>Yii::t('several','staff phone'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, staff_name, staff_type, staff_phone','safe'),
			array('staff_name','required'),
			array('staff_type','required'),
			//array('cross','required'),
			array('staff_name','validateName'),
		);
	}

	public function validateName($attribute, $params){
        $id = $this->id;
        if(empty($id)){
            $id = 0;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_staff")
            ->where('staff_name=:staff_name and id!=:id',
                array(':staff_name'=>$this->staff_name,':id'=>$id))->queryRow();
        if($rows){
            $message = Yii::t('several','staff name'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    public function getStaffList(){
        $arr[""]="";
        $rows = Yii::app()->db->createCommand()->select("id,staff_name")->from("sev_staff")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["staff_name"];
            }
        }
        return $arr;
    }

    public function getStaffNameToId($id){
        $row = Yii::app()->db->createCommand()->select("staff_name")->from("sev_staff")
            ->where('id=:id', array(':id'=>$id))->queryRow();
        if($row){
            return $row["staff_name"];
        }
        return $id;
    }

    public function getStaffPhoneToId($id){
        $row = Yii::app()->db->createCommand()->select("staff_phone")->from("sev_staff")
            ->where('id=:id', array(':id'=>$id))->queryRow();
        if($row){
            return $row["staff_phone"];
        }
        return $id;
    }

    //刪除验证
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer")
            ->where('staff_id=:staff_id', array(':staff_id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }else{
            $rows = Yii::app()->db->createCommand()->select()->from("sev_group")
                ->where('assign_id=:staff_id or salesman_one like "%,:staff_id,%"', array(':staff_id'=>$this->id))->queryRow();
            if($rows){
                return false;
            }
        }
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_staff")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->staff_name = $row['staff_name'];
                $this->staff_type = $row['staff_type'];
                $this->staff_phone = $row['staff_phone'];
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
                $sql = "delete from sev_staff where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_staff(
							staff_name, staff_type, staff_phone, lcu,lcd
						) values (
							:staff_name, :staff_type, :staff_phone, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_staff set
							staff_name = :staff_name, 
							staff_type = :staff_type, 
							staff_phone = :staff_phone, 
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
		if (strpos($sql,':staff_type')!==false)
			$command->bindParam(':staff_type',$this->staff_type,PDO::PARAM_STR);
		if (strpos($sql,':staff_phone')!==false)
			$command->bindParam(':staff_phone',$this->staff_phone,PDO::PARAM_STR);

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
