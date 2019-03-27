<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class FirmForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $firm_name;
	public $z_index;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'firm_name'=>Yii::t('several','firm name'),
            'z_index'=>Yii::t('several','index'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, firm_name, z_index','safe'),
			array('firm_name','required'),
			//array('cross','required'),
			array('firm_name','validateName'),
		);
	}

	public function validateName($attribute, $params){
        $id = $this->id;
        if(empty($id)){
            $id = 0;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_firm")
            ->where('firm_name=:firm_name and id!=:id',
                array(':firm_name'=>$this->firm_name,':id'=>$id))->queryRow();
        if($rows){
            $message = Yii::t('several','firm name'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    public function getFirmList(){
        $arr=array();
        $rows = Yii::app()->db->createCommand()->select("id,firm_name")->from("sev_firm")->order("z_index desc")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["firm_name"];
            }
        }
        return $arr;
    }

    public function getFirmNameToId($firm_id){
        $row = Yii::app()->db->createCommand()->select("firm_name")->from("sev_firm")->where("id=:id",array(":id"=>$firm_id))->queryRow();
        if($row){
            return $row["firm_name"];
        }
        return $firm_id;
    }

    //刪除验证
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer_firm")
            ->where('firm_id=:firm_id', array(':firm_id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_firm")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->firm_name = $row['firm_name'];
                $this->z_index = $row['z_index'];
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
                $sql = "delete from sev_firm where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_firm(
							firm_name, z_index, lcu,lcd
						) values (
							:firm_name, :z_index, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_firm set
							firm_name = :firm_name, 
							z_index = :z_index, 
							luu = :luu
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':firm_name')!==false)
			$command->bindParam(':firm_name',$this->firm_name,PDO::PARAM_STR);
		if (strpos($sql,':z_index')!==false)
			$command->bindParam(':z_index',$this->z_index,PDO::PARAM_STR);

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
