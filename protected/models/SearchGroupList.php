<?php

class SearchGroupList extends CListPageModel
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
			'occurrences_num'=>Yii::t('several','occurrences number'),
			'arrears_number'=>Yii::t('several','arrears number'),
			'arrears_money'=>Yii::t('several','arrears money'),
			'salesman_one_ts'=>Yii::t('several','salesman one'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
//GROUP BY e.customer_id
		$sql1 = "SELECT g.id,g.company_code,g.salesman_one_ts,SUM(b.amt) as arrears_money,COUNT(b.amt) AS occurrences_num,
            sum(case when b.amt>0 then 1 else 0 end ) as arrears_number
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_group g ON g.id = a.group_id
            WHERE a.id>0 AND NOT ISNULL(a.group_id) 
			";
        $sql2 = "SELECT COUNT(*) FROM 
            (SELECT t.group_id  FROM sev_customer t WHERE t.id>0 GROUP BY t.group_id ) a
            LEFT JOIN sev_group g ON g.id = a.group_id
            WHERE NOT ISNULL(a.group_id) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'company_code':
					$clause .= General::getSqlConditionClause('g.company_code',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by g.id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();

		$sql = $sql1.$clause." GROUP BY g.id,g.company_code,g.salesman_one_ts ".$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'company_code'=>$record['company_code'],
					'occurrences_num'=>$record['occurrences_num'],
					'arrears_number'=>$record['arrears_number'],
					'arrears_money'=>$record['arrears_money'],
					'salesman_one_ts'=>$record['salesman_one_ts'],
					'color'=>intval($record['arrears_money'])>0?"text-danger":"text-primary",
				);
			}
		}
		$session = Yii::app()->session;
		$session['searchGroup_03'] = $this->getCriteria();
		return true;
	}
}