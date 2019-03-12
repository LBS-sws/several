<?php

class CompanyList extends CListPageModel
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
			'client_code'=>Yii::t('several','Customer Code'),
			'customer_name'=>Yii::t('several','Customer Name'),
			'group_id'=>Yii::t('several','company Code'),
			//'z_index'=>Yii::t('several','index'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select * 
				from sev_company
				where id>0
			";
        $sql2 = "select count(*)
				from sev_company
				where id>0
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'client_code':
					$clause .= General::getSqlConditionClause('client_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('customer_name',$svalue);
					break;
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
					'client_code'=>$record['client_code'],
					'customer_name'=>$record['customer_name'],
					'group_id'=>GroupForm::getGroupCodeToId($record['group_id']),
				);
			}
		}
		$session = Yii::app()->session;
		$session['company_01'] = $this->getCriteria();
		return true;
	}
}