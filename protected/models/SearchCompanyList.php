<?php

class SearchCompanyList extends CListPageModel
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

			'sum_num'=>Yii::t('several','arrears money'),
			//'salesman_one_ts'=>Yii::t('several','salesman one'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
//GROUP BY e.customer_id
		$sql1 = "SELECT a.*,sum(b.amt) as sum_num,g.client_code,g.customer_name
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_company g ON g.id = a.company_id
            WHERE a.id>0 
			";
        $sql2 = "SELECT COUNT(*)
             FROM sev_customer a
            LEFT JOIN sev_company g ON g.id = a.company_id
            WHERE a.id>0 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'client_code':
					$clause .= General::getSqlConditionClause('g.client_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('g.customer_name',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.lcd desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();

		$sql = $sql1.$clause." GROUP BY a.id ".$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
                //$record['sum_num'] = $this->getAmtNum($record);
				$this->attr[] = array(
					'id'=>$record['id'],
					'client_code'=>$record['client_code'],
					'customer_name'=>$record['customer_name'],
					'sum_num'=>$record['sum_num'],
					'color'=>intval($record['sum_num'])>0?"text-danger":"text-primary",
				);
			}
		}
		$session = Yii::app()->session;
		$session['searchCompany_01'] = $this->getCriteria();
		return true;
	}

	public function getAmtNum($rs){
        $row = Yii::app()->db->createCommand()->select("sum(amt)")->from("sev_customer_firm")
            ->where("customer_id=:id", array(':id'=>$rs["id"]))->queryScalar();
        return $row;
    }
}