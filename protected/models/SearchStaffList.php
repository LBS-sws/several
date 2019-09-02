
<?php

class SearchStaffList extends CListPageModel
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
            'staff_name'=>Yii::t('several','staff name'),
            'staff_phone'=>Yii::t('several','staff phone'),

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
		$sql1 = "SELECT g.id,g.staff_name,g.staff_phone,SUM(b.amt) as collection,sum(case when b.amt>0 then 1 else 0 end ) as collection_num,COUNT(b.amt) AS occurrences_num
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_staff g ON g.id = a.staff_id
            WHERE a.id>0 AND NOT ISNULL(a.staff_id) ";
        $sql2 = "SELECT COUNT(*) FROM 
            (SELECT id,staff_id  FROM sev_customer t WHERE t.id >0 GROUP BY t.staff_id ) a
            LEFT JOIN sev_staff g ON g.id = a.staff_id
            WHERE g.id>0 AND NOT ISNULL(a.staff_id) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'staff_name':
					$clause .= General::getSqlConditionClause('g.staff_name',$svalue);
					break;
				case 'staff_phone':
					$clause .= General::getSqlConditionClause('g.staff_phone',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by g.lcd desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();

		$sql = $sql1.$clause." GROUP BY g.id,g.staff_name,g.staff_phone ".$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'staff_name'=>$record['staff_name'],
					'staff_phone'=>$record['staff_phone'],
					'occurrences_num'=>$record['occurrences_num'],
					'collection_num'=>$record['collection_num'],

					'collection'=>$record['collection'],
					'color'=>intval($record['collection'])>0?"text-danger":"text-primary",
				);
			}
		}
		$session = Yii::app()->session;
		$session['searchStaff_01'] = $this->getCriteria();
		return true;
	}
}