<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class BatchModifyForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
    public $group_id;

    public $acca_username;
    public $acca_phone;
    public $acca_remark;
    public $acca_fun;
    public $acca_lang;
    public $acca_discount;
    public $salesman_id;
    public $staff_id;
    public $lud;
    public $payment;
    public $remark;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'z_index'=>Yii::t('several','index'),
            'group_id'=>Yii::t('several','company Code'),

            'acca_username'=>Yii::t('several','accountant username'),
            'acca_phone'=>Yii::t('several','accountant phone'),
            'acca_lang'=>Yii::t('several','accountant lang'),
            'acca_discount'=>Yii::t('several','discount'),
            'acca_remark'=>Yii::t('several','accountant remark'),
            'acca_fun'=>Yii::t('several','method'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'lud'=>Yii::t('several','last time'),
            'remark'=>Yii::t('several','Update Remark'),
            'payment'=>Yii::t('several','payment'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('group_id, remark
            ,acca_username,acca_phone,acca_lang,acca_discount,acca_remark,acca_fun,payment','safe'),
			array('group_id,remark','required'),
			//array('cross','required'),
			array('group_id','validateGroupId'),
		);
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

    public function getCustomerDetail($group_id){
        $row = Yii::app()->db->createCommand()->select("acca_username,acca_phone,acca_lang,acca_discount,acca_remark,acca_fun,payment")->from("sev_customer")
            ->where("group_id=:group_id",array(":group_id"=>$group_id))->queryRow();
        if($row){
            return array("status"=>1,"data"=>$row);
        }else{
            return array("status"=>0,"data"=>array(
                "acca_username"=>"",
                "acca_phone"=>"",
                "acca_lang"=>"",
                "acca_discount"=>"",
                "acca_remark"=>"",
                "acca_fun"=>"",
                "payment"=>"",
            ));
        }
    }
	
	public function saveData()
	{
        //修改客戶信息
        Yii::app()->db->createCommand()->update("sev_customer",
            array(
                "acca_username"=>$this->acca_username,
                "acca_phone"=>$this->acca_phone,
                "acca_lang"=>$this->acca_lang,
                "acca_discount"=>$this->acca_discount,
                "acca_remark"=>$this->acca_remark,
                "acca_fun"=>$this->acca_fun,
                "payment"=>$this->payment,
            ),
            "group_id=:group_id",array(":group_id"=>$this->group_id)
        );

	    //添加備註信息
        $rows = Yii::app()->db->createCommand()->select("b.id")->from("sev_customer_firm b")
            ->leftJoin("sev_customer a","b.customer_id = a.id")
            ->where("a.group_id=:group_id",array(":group_id"=>$this->group_id))->queryAll();
        if($rows){
            $connection = Yii::app()->db;
            $transaction=$connection->beginTransaction();
            try {
                $this->saveStaff($connection,$rows);
                $transaction->commit();
            }catch(Exception $e) {
                $transaction->rollback();
                throw new CHttpException(404,'Cannot update.');
            }
        }
	}

    protected function saveStaff(&$connection,$rows){
        $lcu = Yii::app()->user->id;
        foreach ($rows as $row){
            $sql = "insert into sev_remark_list(
							firm_cus_id, remark, update_type, lcu
						) values (
							:firm_cus_id, :remark, 1, :lcu
						)";
            $command=$connection->createCommand($sql);
            if (strpos($sql,':firm_cus_id')!==false)
                $command->bindParam(':firm_cus_id',$row["id"],PDO::PARAM_INT);
            if (strpos($sql,':remark')!==false)
                $command->bindParam(':remark',$this->remark,PDO::PARAM_INT);
            if (strpos($sql,':lcu')!==false)
                $command->bindParam(':lcu',$lcu,PDO::PARAM_INT);
            $command->execute();
        }
    }


}
