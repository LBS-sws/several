<?php

class GroupList extends CListPageModel
{
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
			'staff_name'=>Yii::t('several','assign staff'),
			'occurrences'=>Yii::t('several','occurrences'),
			'assign_date'=>Yii::t('several','assign date'),
			'salesman_one_ts'=>Yii::t('several','salesman one'),
			'cross_district'=>Yii::t('several','cross'),
            'occurrences'=>Yii::t('several','occurrences'),
			//'salesman_two_ts'=>Yii::t('several','salesman two'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select a.*,b.staff_name 
				from sev_group a
				LEFT JOIN sev_staff b ON a.assign_id = b.id
				where a.id>0
			";
        $sql2 = "select count(*)
				from sev_group a
				LEFT JOIN sev_staff b ON a.assign_id = b.id
				where a.id>0
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'company_code':
					$clause .= General::getSqlConditionClause('a.company_code',$svalue);
					break;
				case 'staff_name':
					$clause .= General::getSqlConditionClause('b.staff_name',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.company_code asc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
			    //$color = floatval($record['amt'])>0?"text-danger":"text-primary";
				$this->attr[] = array(
					'id'=>$record['id'],
					'company_code'=>$record['company_code'],
					'staff_name'=>$record['staff_name'],
					'occurrences'=>$record['occurrences'],
					'salesman_one_ts'=>$record['salesman_one_ts'],
					//'salesman_two_ts'=>$record['salesman_two_ts'],
					'assign_date'=>$record['assign_date'],
					'cross_district'=>$record['cross_district'],
					//'color'=>$color,
				);
			}
		}
		$session = Yii::app()->session;
		$session['group_01'] = $this->getCriteria();
		return true;
	}
}