<?php

class ClientsList extends CListPageModel
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
			'client_code'=>Yii::t('several','Customer Code'),
			'customer_name'=>Yii::t('several','Customer Name'),
			'company_code'=>Yii::t('several','Company Code'),
			'staff_name'=>Yii::t('several','assign staff'),
			'salesman'=>Yii::t('several','salesman'),
			'firm_name_us'=>Yii::t('several','Clients to firm'),
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

        if(!empty($this->searchYear)){
            $year = str_replace("'","\'",$this->searchYear);
        }else{
            $year = date("Y");
        }
        $this->searchYear = $year;
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select a.*,b.client_code,b.customer_name,d.staff_name,e.company_code,f.staff_name as salesman 
				from sev_customer a 
				LEFT JOIN sev_company b ON a.company_id=b.id 
				LEFT JOIN sev_staff d ON a.staff_id = d.id
				LEFT JOIN sev_group e ON a.group_id = e.id
				LEFT JOIN sev_staff f ON a.salesman_id = f.id
				where a.customer_year='$year' 
			";
        $sql2 = "select count(*) 
				from sev_customer a 
				LEFT JOIN sev_company b ON a.company_id=b.id 
				LEFT JOIN sev_staff d ON a.staff_id = d.id
				LEFT JOIN sev_group e ON a.group_id = e.id
				LEFT JOIN sev_staff f ON a.salesman_id = f.id
				where a.customer_year='$year' 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'client_code':
					$clause .= General::getSqlConditionClause('b.client_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('b.customer_name',$svalue);
					break;
				case 'company_code':
					$clause .= General::getSqlConditionClause('e.company_code',$svalue);
					break;
				case 'staff_name':
					$clause .= General::getSqlConditionClause('d.staff_name',$svalue);
					break;
				case 'salesman':
					$clause .= General::getSqlConditionClause('f.staff_name',$svalue);
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
					'company_code'=>$record['company_code'],
					'staff_name'=>$record['staff_name'],
					'salesman'=>$record['salesman'],
					'firm_name_us'=>$record['firm_name_us']
				);
			}
		}
		$session = Yii::app()->session;
		$session['clients_01'] = $this->getCriteria();
		return true;
	}
}