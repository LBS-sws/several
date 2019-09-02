
<?php

class SearchFirmList extends CListPageModel
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
            'firm_name'=>Yii::t('several','firm name'),

            'occurrences_num'=>Yii::t('several','occurrences number'),
            'collection_num'=>Yii::t('several','collection number'),
            'collection'=>Yii::t('several','For collection'),
			//'salesman_one_ts'=>Yii::t('several','salesman one'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
//GROUP BY e.customer_id
		$sql1 = "SELECT g.id,g.firm_name,SUM(b.amt) as collection,sum(case when b.amt>0 then 1 else 0 end ) as collection_num,COUNT(b.firm_id) AS occurrences_num
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_firm g ON g.id = b.firm_id
            WHERE a.id>0 ";
        $sql2 = "SELECT COUNT(*) FROM 
            (SELECT t.firm_id  FROM sev_customer_firm t LEFT JOIN sev_customer a ON t.customer_id = a.id WHERE a.id>0 GROUP BY t.firm_id ) a
            LEFT JOIN sev_firm g ON g.id = a.firm_id
            WHERE g.id>0 AND NOT ISNULL(a.firm_id) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'firm_name':
					$clause .= General::getSqlConditionClause('g.firm_name',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by g.z_index desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();

		$sql = $sql1.$clause." GROUP BY g.id,g.firm_name ".$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'firm_name'=>$record['firm_name'],
					'occurrences_num'=>$record['occurrences_num'],
					'collection_num'=>$record['collection_num'],

					'collection'=>$record['collection'],
					'color'=>intval($record['collection'])>0?"text-danger":"text-primary",
				);
			}
		}
		$session = Yii::app()->session;
		$session['searchFirm_01'] = $this->getCriteria();
		return true;
	}
}