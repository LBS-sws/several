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
    public $acca_fax;
    public $acca_discount;
    public $salesman_id;
    public $staff_id;
    public $lud;
    public $payment;
    public $on_off;
    public $pay_type;
    public $remark;

    public $refer_code;
    public $usual_date;
    public $head_worker;
    public $other_worker;
    public $advance_name;
    public $listing_name;
    public $listing_email;
    public $listing_fax;
    public $new_month;
    public $lbs_month;
    public $other_month;
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
            'acca_fax'=>Yii::t('several','accountant fax'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'lud'=>Yii::t('several','last time'),
            'remark'=>Yii::t('several','Update Remark'),
            'payment'=>Yii::t('several','payment'),

            'on_off'=>Yii::t('several','on off'),
            'pay_type'=>Yii::t('several','pay type'),

            'refer_code'=>Yii::t('several','refer code'),
            'usual_date'=>Yii::t('several','usual date'),
            'head_worker'=>Yii::t('several','head worker'),
            'other_worker'=>Yii::t('several','other worker'),
            'advance_name'=>Yii::t('several','advance name'),
            'listing_name'=>Yii::t('several','listing name'),
            'listing_email'=>Yii::t('several','listing email'),
            'listing_fax'=>Yii::t('several','listing fax'),
            'new_month'=>Yii::t('several','new month'),
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
            ,acca_fax,refer_code,usual_date,head_worker,other_worker,advance_name,listing_name,listing_email,listing_fax,new_month,lbs_month,other_month,
            ,acca_username,acca_phone,acca_lang,acca_discount,acca_remark,acca_fun,on_off,pay_type,payment','safe'),
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
        $row = Yii::app()->db->createCommand()->select("*")->from("sev_customer")
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
                "on_off"=>"",
                "pay_type"=>"",

                "acca_fax"=>"",
                "refer_code"=>"",
                "usual_date"=>"",
                "head_worker"=>"",
                "other_worker"=>"",
                "advance_name"=>"",
                "listing_name"=>"",
                "listing_email"=>"",
                "listing_fax"=>"",
                "new_month"=>"",
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
                "acca_fax"=>$this->acca_fax,
                "payment"=>$this->payment,

                'on_off'=>$this->on_off,
                'pay_type'=>$this->pay_type,
                'refer_code'=>$this->refer_code,
                'usual_date'=>$this->usual_date,
                'head_worker'=>$this->head_worker,
                'other_worker'=>$this->other_worker,
                'advance_name'=>$this->advance_name,
                'listing_name'=>$this->listing_name,
                'listing_email'=>$this->listing_email,
                'listing_fax'=>$this->listing_fax,
                'new_month'=>$this->new_month,
                'lbs_month'=>$this->lbs_month,
                'other_month'=>$this->other_month,
            ),
            "group_id=:group_id",array(":group_id"=>$this->group_id)
        );

	    //添加備註信息
        $rows = Yii::app()->db->createCommand()->select("a.id")->from("sev_customer a")
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
							customer_id, remark, update_type, lcu
						) values (
							:customer_id, :remark, 1, :lcu
						)";
            $command=$connection->createCommand($sql);
            if (strpos($sql,':customer_id')!==false)
                $command->bindParam(':customer_id',$row["id"],PDO::PARAM_INT);
            if (strpos($sql,':remark')!==false)
                $command->bindParam(':remark',$this->remark,PDO::PARAM_INT);
            if (strpos($sql,':lcu')!==false)
                $command->bindParam(':lcu',$lcu,PDO::PARAM_INT);
            $command->execute();
        }
    }


}
