<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class ClientsForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $company_id = 0;
	public $staff_id;
	public $salesman_id;
	public $group_id;
	public $firm_name_id;
	public $firm_name_us;
	public $customer_year;

	public $client_code;
	public $customer_name;
	public $company_code;
	private $add_firm_list;

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'company_id'=>Yii::t('several','Customer Code'),
            'client_code'=>Yii::t('several','Customer Code'),
            'customer_name'=>Yii::t('several','Customer Name'),
            'group_id'=>Yii::t('several','company Code'),
            'company_code'=>Yii::t('several','Company Code'),
            'staff_id'=>Yii::t('several','assign staff'),
            'staff_name'=>Yii::t('several','assign staff'),
            'salesman_id'=>Yii::t('several','salesman'),
            'salesman'=>Yii::t('several','salesman'),
            'firm_name_id'=>Yii::t('several','Clients to firm'),
            'customer_year'=>Yii::t('several','Customer Year'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, company_id, staff_id, salesman_id, group_id, customer_year, firm_name_id, firm_name_us,
             client_code, customer_name, company_code','safe'),
			array('company_id','required','on'=>'new'),
			array('salesman_id,staff_id','required'),

			array('salesman_id,staff_id','validateStaff'),
			array('group_id','validateGroup'),
			array('firm_name_id','required'),

            array('company_id','validateCompany','on'=>'new'),
            array('firm_name_id','validateFirm'),
		);
	}

	public function validateFirm($attribute, $params){
	    $firm_list = $this->firm_name_id;
	    if(!empty($firm_list)){
            $this->firm_name_us=array();
            $this->add_firm_list=array();
	        $old_firm_list = array();
	        if($this->getScenario()=="edit"){
	            $list = Yii::app()->db->createCommand()->select("id,firm_id")->from("sev_customer_firm")
                    ->where('customer_id=:id',array(':id'=>$this->id))->queryAll();
	            foreach ($list as $item){ //驗證是否有刪除的公司
                    $old_firm_list[]=$item["firm_id"];
	                if(!in_array($item["firm_id"],$firm_list)){
                        $row = Yii::app()->db->createCommand()->select("id")->from("sev_customer_info")
                            ->where('firm_cus_id=:firm_cus_id',array(':firm_cus_id'=>$item["id"]))->queryRow();
                        if($row){
                            $this->firm_name_id[] = $item["firm_id"];
                            $message = "可追数公司已經追數，無法刪除";
                            $this->addError($attribute,$message);
                            return false;
                        }else{
                            Yii::app()->db->createCommand()->delete('sev_customer_firm', 'id=:id',array(':id'=>$item["id"]));
                        }
                    }
                }
            }
            //var_dump($old_firm_list);die();
	        foreach ($this->firm_name_id as $firm_id){
                $rows = Yii::app()->db->createCommand()->select("id,firm_name")->from("sev_firm")
                    ->where('id=:id',array(':id'=>$firm_id))->queryRow();
                if(!$rows){
                    $message = Yii::t('several','Clients to firm'). Yii::t('several',' does not exist');
                    $this->addError($attribute,$message);
                    return false;
                }else{
                    if(!in_array($firm_id,$old_firm_list)){
                        $this->add_firm_list[] = $firm_id;
                    }
                    $this->firm_name_us[]=$rows["firm_name"];
                }
            }
            $this->firm_name_us = implode(",",$this->firm_name_us);
        }
    }

	public function validateCompany($attribute, $params){
	    if(!empty($this->company_id)){
            $rows = Yii::app()->db->createCommand()->select("id")->from("sev_company")
                ->where('id=:id',array(':id'=>$this->company_id))->queryRow();
            if(!$rows){
                $message = Yii::t('several','Customer Name'). Yii::t('several',' does not exist');
                $this->addError($attribute,$message);
            }else{
                $year = date("Y");
                $rows = Yii::app()->db->createCommand()->select("id")->from("sev_customer")
                    ->where('company_id=:company_id and customer_year=:year and id!=:id',
                        array(':company_id'=>$this->company_id,':year'=>$year,':id'=>$this->id))->queryRow();
                if($rows){
                    $message = "該客戶在 $year 已存在，不可重複添加。重複ID：".$rows["id"];
                    $this->addError($attribute,$message);
                }
            }
        }
    }

	public function validateStaff($attribute, $params){
	    $staff_id = $this->getAttributes()[$attribute];
	    if(!empty($staff_id)){
            $rows = Yii::app()->db->createCommand()->select("id")->from("sev_staff")
                ->where('id=:id',array(':id'=>$staff_id))->queryRow();
            if(!$rows){
                $message = $this->getAttributeLabel($attribute). Yii::t('several',' does not exist');
                $this->addError($attribute,$message);
            }
        }
    }

	public function validateGroup($attribute, $params){
	    $group_id = $this->getAttributes()[$attribute];
	    if(!empty($staff_id)){
            $rows = Yii::app()->db->createCommand()->select("id")->from("sev_group")
                ->where('id=:id',array(':id'=>$group_id))->queryRow();
            if(!$rows){
                $message = Yii::t('several','company Code'). Yii::t('several',' does not exist');
                $this->addError($attribute,$message);
            }
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    //異步獲取客戶公司信息
    public function ajaxCompanyList($company_id){
        if(empty($company_id)){
            return array("status"=>0);
        }
        $rows = Yii::app()->db->createCommand()->select("a.customer_name,a.group_id,b.assign_id")->from("sev_company a")
            ->leftJoin("sev_group b","a.group_id=b.id")
            ->where('a.id=:id',array(':id'=>$company_id))->queryRow();
        if($rows){
            return array("status"=>1,"data"=>$rows);
        }else{
            return array("status"=>0);
        }
    }

    //刪除验证
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer_info")
            ->where('customer_id=:customer_id', array(':customer_id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }else{
            Yii::app()->db->createCommand()->delete('sev_customer_firm', 'customer_id=:customer_id', array(':customer_id'=>$this->id));
            FunctionForm::refreshGroupOne($this->group_id);
        }
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("a.*,b.client_code,b.customer_name,c.company_code")->from("sev_customer a")
            ->leftJoin("sev_company b","a.company_id=b.id")
            ->leftJoin("sev_group c","a.group_id=c.id")
            ->where("a.id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
                $this->company_id = $row['company_id'];
				$this->staff_id = $row['staff_id'];
                $this->salesman_id = $row['salesman_id'];
                $this->group_id = $row['group_id'];
                $this->customer_year = $row['customer_year'];
                $this->client_code = $row['client_code'];
                $this->customer_name = $row['customer_name'];
                $this->company_code = $row['company_code'];
                $this->firm_name_id = explode(",",$row['firm_name_id']);
                $this->firm_name_us = $row['firm_name_us'];
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
                $sql = "delete from sev_customer where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_customer(
							company_id, staff_id, salesman_id, group_id, customer_year, firm_name_id, firm_name_us, lcu,lcd
						) values (
							:company_id, :staff_id, :salesman_id, :group_id, :customer_year, :firm_name_id, :firm_name_us, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_customer set
							staff_id = :staff_id, 
							salesman_id = :salesman_id, 
							group_id = :group_id, 
							firm_name_id = :firm_name_id, 
							firm_name_us = :firm_name_us, 
							luu = :luu
						where id = :id
						";
				break;
		}//client_code, customer_name, company_code, company_id, staff_id, group_id, customer_year

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':company_id')!==false)
			$command->bindParam(':company_id',$this->company_id,PDO::PARAM_INT);
		if (strpos($sql,':staff_id')!==false)
			$command->bindParam(':staff_id',$this->staff_id,PDO::PARAM_INT);
		if (strpos($sql,':salesman_id')!==false)
			$command->bindParam(':salesman_id',$this->salesman_id,PDO::PARAM_INT);
		if (strpos($sql,':group_id')!==false)
			$command->bindParam(':group_id',$this->group_id,PDO::PARAM_INT);
		if (strpos($sql,':customer_year')!==false){
		    $year = date("Y");
            $command->bindParam(':customer_year',$year,PDO::PARAM_INT);
        }

		if (strpos($sql,':firm_name_id')!==false){
            $this->firm_name_id = implode(",",$this->firm_name_id);
            $command->bindParam(':firm_name_id',$this->firm_name_id,PDO::PARAM_STR);
        }
		if (strpos($sql,':firm_name_us')!==false)
			$command->bindParam(':firm_name_us',$this->firm_name_us,PDO::PARAM_STR);

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
        if(!empty($this->add_firm_list)){
            foreach ($this->add_firm_list as $firm_id){
                Yii::app()->db->createCommand()->insert('sev_customer_firm', array(
                    'customer_id'=>$this->id,
                    'firm_id'=>$firm_id,
                ));
            }
        }

        FunctionForm::refreshGroupOne($this->group_id);
        return true;
	}

}
