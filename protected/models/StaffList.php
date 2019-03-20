<?php

class StaffList extends CListPageModel
{
    public $searchStaffType;//
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
    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchStaffType','safe',),
        );
    }
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select * 
				from sev_staff
				where id>0
			";
        $sql2 = "select count(*)
				from sev_staff
				where id>0
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'staff_name':
					$clause .= General::getSqlConditionClause('staff_name',$svalue);
					break;
				case 'staff_phone':
					$clause .= General::getSqlConditionClause('staff_phone',$svalue);
					break;
			}
		}
		if(!empty($this->searchStaffType)){
            $svalue = str_replace("'","\'",$this->searchValue);
            if(is_numeric($svalue)){
                $clause .= " and staff_type = $svalue";
            }
        }
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by lcd desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'staff_phone'=>$record['staff_phone'],
					'staff_name'=>$record['staff_name'],
					'staff_type'=>$this->getStaffTypeToNumber($record['staff_type'])
				);
			}
		}
		$session = Yii::app()->session;
		$session['staff_01'] = $this->getCriteria();
		return true;
	}

	public function getStaffTypeList($bool = true){
	    $str = $bool?Yii::t("several","all"):"";
	    return array(
	        ""=>$str,
	        "1"=>Yii::t("several","technician"),
	        "2"=>Yii::t("several","salesman"),
        );
    }

    protected function getStaffTypeToNumber($num){
	    if(empty($num)){
            return "";//排除為空的類型
        }
        $staffTypeList = $this->getStaffTypeList();
	    if(key_exists($num,$staffTypeList)){
            return $staffTypeList[$num];
        }else{
            return "";
        }
    }
}