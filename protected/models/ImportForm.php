<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class ImportForm extends CFormModel
{
	/* User Fields */
	public $handle_name;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'handle_name'=>Yii::t('several','export Type'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('handle_name','safe'),
			array('handle_name','required'),
			//array('cross','required'),
			array('handle_name','validateName'),
		);
	}

	public function validateName($attribute, $params){
        $uid = Yii::app()->user->id;
        $exportType = $this->getExportGroupType();
        if(key_exists($this->handle_name,$exportType)){
            $rows = Yii::app()->db->createCommand()->select("id")->from("sev_file")
                ->where('handle_name=:handle_name and state in ("P","I") and lcu=:lcu',
                    array(':handle_name'=>$this->handle_name,':lcu'=>$uid))->queryRow();
            if($rows){
                $message = "系統正在導出，請去報表管理員查看";
                $this->addError($attribute,$message);
            }
        }else{
            $message = Yii::t('several','export Type'). "異常";
            $this->addError($attribute,$message);
        }
    }

    public function getExportGroupType(){
	    return array(
	        "导出集团客户"=>Yii::t("app",'export only group'),
	        "导出非集团客户"=>Yii::t("app",'export not group'),
	        "导出客户追数详情"=>Yii::t("app",'export row customer'),
        );
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
			case 'new':
				$sql = "insert into sev_file(
							handle_name,file_name,file_type,file_url,lcu,lcd
						) values (
							:handle_name,'...','xlsx',123,:lcu,:lcd
						)";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':handle_name')!==false)
			$command->bindParam(':handle_name',$this->handle_name,PDO::PARAM_STR);

		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcd')!==false){
            $lcd = date("Y-m-d H:i:s");
            $command->bindParam(':lcd',$lcd,PDO::PARAM_STR);
        }

		$command->execute();
        return true;
	}

}
