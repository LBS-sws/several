<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class CompanyForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $client_code;
	public $customer_name;
	public $group_id;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'client_code'=>Yii::t('several','Customer Code'),
            'customer_name'=>Yii::t('several','Customer Name'),
            'group_id'=>Yii::t('several','company Code'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, client_code, customer_name, group_id','safe'),
			array('client_code','required'),
			array('customer_name','required'),
            array('group_id','required'),
			//array('cross','required'),
			array('client_code','validateCode'),
			array('customer_name','validateName'),
			array('group_id','validateGroupId'),
		);
	}

	public function validateCode($attribute, $params){
        $id = $this->id;
        if(empty($id)){
            $id = 0;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_company")
            ->where('client_code=:client_code and id!=:id',
                array(':client_code'=>$this->client_code,':id'=>$id))->queryRow();
        if($rows){
            $message = Yii::t('several','Customer Code'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

	public function validateName($attribute, $params){
        $id = $this->id;
        if(empty($id)){
            $id = 0;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_company")
            ->where('customer_name=:customer_name and id!=:id',
                array(':customer_name'=>$this->customer_name,':id'=>$id))->queryRow();
        if($rows){
            $message = Yii::t('several','Customer Name'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

	public function validateGroupId($attribute, $params){
	    if(!empty($this->group_id)){
            $rows = Yii::app()->db->createCommand()->select("id")->from("sev_group")
                ->where('id=:id',
                    array(':id'=>$this->group_id))->queryRow();
            if(!$rows){
                $message = Yii::t('several','company Code'). Yii::t('several',' does not exist');
                $this->addError($attribute,$message);
            }
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    //刪除验证
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer")
            ->where('company_id=:company_id', array(':company_id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }
        return true;
    }

    //獲取所有集團編號
    public function getCompanyList($bool = true){
        if($bool){
            $arr[""]="";
        }else{
            $arr = array();
        }
        $rows = Yii::app()->db->createCommand()->select("id,client_code,customer_name")->from("sev_company")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]]=$row["client_code"]." -- ".$row["customer_name"];
            }
        }
        return $arr;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_company")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->client_code = $row['client_code'];
                $this->customer_name = $row['customer_name'];
                $this->group_id = $row['group_id'];
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
                $sql = "delete from sev_company where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_company(
							client_code, customer_name, group_id, lcu,lcd
						) values (
							:client_code, :customer_name, :group_id, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_company set
							client_code = :client_code, 
							customer_name = :customer_name, 
							group_id = :group_id, 
							luu = :luu
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':client_code')!==false)
			$command->bindParam(':client_code',$this->client_code,PDO::PARAM_STR);
		if (strpos($sql,':customer_name')!==false)
			$command->bindParam(':customer_name',$this->customer_name,PDO::PARAM_STR);
		if (strpos($sql,':group_id')!==false)
			$command->bindParam(':group_id',$this->group_id,PDO::PARAM_INT);

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
