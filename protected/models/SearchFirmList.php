
<?php

class SearchFirmList extends CListPageModel
{
    public $searchYear;//
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
            'customer_year'=>Yii::t('several','Customer Year'),
			//'salesman_one_ts'=>Yii::t('several','salesman one'),
		);
	}
    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchYear','safe',),
        );
    }
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
        if(!empty($this->searchYear)){
            $year = str_replace("'","\'",$this->searchYear);
        }
        if(empty($year)||!is_numeric($year)){
            $year = date("Y");
        }
        $this->searchYear = $year;
//GROUP BY e.customer_id
		$sql1 = "SELECT g.*,a.customer_year,SUM(b.sum_num) as collection,sum(case when b.sum_num>0 then 1 else 0 end ) as collection_num,COUNT(b.firm_id) AS occurrences_num
             FROM sev_customer a
            LEFT JOIN (
            SELECT SUM(f.amt_num) as sum_num,e.firm_id,e.customer_id FROM sev_customer_firm e
            LEFT JOIN sev_customer_info f ON f.firm_cus_id = e.id
            GROUP BY e.id
            ) b ON a.id = b.customer_id
            LEFT JOIN sev_firm g ON g.id = b.firm_id
            WHERE a.customer_year = '$year' ";
        $sql2 = "SELECT COUNT(*) FROM 
            (SELECT t.firm_id,a.customer_year  FROM sev_customer_firm t LEFT JOIN sev_customer a ON t.customer_id = a.id WHERE a.customer_year = '$year' GROUP BY t.firm_id ) a
            LEFT JOIN sev_firm g ON g.id = a.firm_id
            WHERE a.customer_year = '$year' AND NOT ISNULL(a.firm_id) 
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

		$sql = $sql1.$clause." GROUP BY b.firm_id ".$order;
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
					'customer_year'=>$record['customer_year'],

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