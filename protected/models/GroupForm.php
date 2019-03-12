<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class GroupForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $company_code;
	public $assign_id;
	public $occurrences;
	public $assign_date;
	public $salesman_one_ts;
	public $cross_district;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'company_code'=>Yii::t('several','company Code'),
            'assign_id'=>Yii::t('several','assign staff'),
            'occurrences'=>Yii::t('several','occurrences'),
            'assign_date'=>Yii::t('several','assign date'),
            'salesman_one_ts'=>Yii::t('several','salesman one'),
            'cross_district'=>Yii::t('several','cross'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, company_code, assign_id, occurrences, assign_date, salesman_one_ts, cross_district','safe'),
			array('company_code','required'),
			array('assign_id','required'),
			array('assign_date','required'),
			//array('cross','required'),
			array('company_code','validateCode'),
			array('assign_id','validateStaffId'),
		);
	}

	public function validateCode($attribute, $params){
        $id = $this->id;
        if(empty($id)){
            $id = 0;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_group")
            ->where('company_code=:company_code and id!=:id',
                array(':company_code'=>$this->company_code,':id'=>$id))->queryRow();
        if($rows){
            $message = Yii::t('several','company Code'). Yii::t('several',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

	public function validateStaffId($attribute, $params){
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_staff")
            ->where('id=:id',array(':id'=>$this->assign_id))->queryRow();
        if(!$rows){
            $message = Yii::t('several','assign staff'). Yii::t('several',' does not exist');
            $this->addError($attribute,$message);
        }
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    //系統生成集團編號
    public function getSysgen($num=0){
        $rows = Yii::app()->db->createCommand()->select("company_code")->from("sev_group")->order("company_code desc")->limit(1,$num)->queryRow();
        //var_dump($rows);
        if($rows){
            $company_code = $rows["company_code"];
            $arr = str_split($company_code);
            if (count($arr)<3){
                $num++;
                return $this->getSysgen($num);
            }else{
                if(is_numeric($arr[2])){
                    $arr[2] = intval($arr[2]);
                    if($arr[2]<9&&$arr[2]>0){
                        $code = $arr[0].$arr[1].($arr[2]+1).'0';
                        return array("status"=>1,"code"=>$code);
                    }else {
                        $strTwo=$this->strAZOrder($arr[1]);
                        if($strTwo["status"]==1){
                            if($strTwo["bool"]){
                                $strOne=$this->strAZOrder($arr[0]);
                                if($strOne["status"]==1){
                                    if($strOne["bool"]){
                                        $num++;
                                        return $this->getSysgen($num);
                                    }else{
                                        $code = $strOne["str"].$strTwo["str"].'10';
                                        return array("status"=>1,"code"=>$code);
                                    }
                                }else{
                                    $num++;
                                    return $this->getSysgen($num);
                                }
                            }else{
                                $code = $arr[0].$strTwo["str"].'10';
                                return array("status"=>1,"code"=>$code);
                            }
                        }else{
                            $num++;
                            return $this->getSysgen($num);
                        }
                    }
                }else{
                    $num++;
                    return $this->getSysgen($num);
                }
            }
        }else{
            return array("status"=>1,"code"=>"AA10");
        }
    }

    protected function strAZOrder($str){
        if (preg_match('/^[a-zA-Z]{1}$/', $str)) {
            $bool = false;
            $str = strtoupper($str);
            if($str=="Z"){
                $str = "A";
                $bool = true;
            }else{
                $str = chr(ord($str)+1);
            }
            return array("status"=>1,"str"=>$str,"bool"=>$bool);
        } else {
            return array("status"=>0);
        }
    }


    //獲取集團編號
	public function getGroupCodeToId($id){
        $rows = Yii::app()->db->createCommand()->select("company_code")->from("sev_group")
            ->where('id=:id', array(':id'=>$id))->queryRow();
        if ($rows){
            return $rows["company_code"];
        }
        return $id;
    }

    //獲取所有集團編號
	public function getGroupCodeList($bool = true){
	    if($bool){
            $arr[""]="";
        }else{
	        $arr = array();
        }
        $rows = Yii::app()->db->createCommand()->select("id,company_code")->from("sev_group")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]]=$row["company_code"];
            }
        }
        return $arr;
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

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_group")
            ->where("id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->company_code = $row['company_code'];
                $this->assign_id = $row['assign_id'];
                $this->occurrences = $row['occurrences'];
                $this->company_code = $row['company_code'];
                $this->assign_date = $row['assign_date'];
                $this->salesman_one_ts = $row['salesman_one_ts'];
                $this->cross_district = $row['cross_district'];
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
                $sql = "delete from sev_group where id = :id ";
				break;
			case 'new':
				$sql = "insert into sev_group(
							company_code, assign_id, assign_date, cross_district, lcu,lcd
						) values (
							:company_code, :assign_id, :assign_date, :cross_district, :lcu,:lcd
						)";
				break;
			case 'edit':
				$sql = "update sev_group set
							assign_id = :assign_id, 
							assign_date = :assign_date, 
							cross_district = :cross_district,
							luu = :luu
						where id = :id
						";
				break;
		}//id, company_code, assign_id, occurrences, assign_date, salesman_one_ts, cross

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':company_code')!==false)
			$command->bindParam(':company_code',$this->company_code,PDO::PARAM_STR);
		if (strpos($sql,':assign_id')!==false)
			$command->bindParam(':assign_id',$this->assign_id,PDO::PARAM_STR);
		if (strpos($sql,':assign_date')!==false)
			$command->bindParam(':assign_date',$this->assign_date,PDO::PARAM_STR);
		if (strpos($sql,':cross_district')!==false)
			$command->bindParam(':cross_district',$this->cross_district,PDO::PARAM_STR);

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
