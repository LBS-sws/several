<?php

class CustomerList extends CListPageModel
{
    public $searchArrears;//
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
			'firm_name'=>Yii::t('several','in firm'),
			'client_code'=>Yii::t('several','Customer Code'),
			'customer_name'=>Yii::t('several','Customer Name'),
			'customer_year'=>Yii::t('several','Customer Year'),
			'company_code'=>Yii::t('several','Company Code'),
			'curr'=>Yii::t('several','Curr'),
			'amt'=>Yii::t('several','Amt'),

            'acca_username'=>Yii::t('several','accountant username'),
            'acca_phone'=>Yii::t('several','accountant phone'),
            'acca_lang'=>Yii::t('several','accountant lang'),
            'acca_discount'=>Yii::t('several','discount'),
            'acca_remark'=>Yii::t('several','accountant remark'),
            'acca_fun'=>Yii::t('several','method'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'group_type'=>Yii::t('several','group type'),
            'lud'=>Yii::t('several','last time'),
            'payment'=>Yii::t('several','payment'),
		);
	}
    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchYear, searchArrears','safe',),
        );
    }
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$firm_str = Yii::app()->user->firm();

		$sql1 = "select a.customer_id,a.firm_id,a.curr,a.amt,a.id as s_id,b.*,c.client_code,c.customer_name,d.company_code,e.firm_name
				from sev_customer_firm a 
				LEFT JOIN sev_firm e ON a.firm_id = e.id
				LEFT JOIN sev_customer b ON a.customer_id = b.id
				LEFT JOIN sev_company c ON c.id = b.company_id
				LEFT JOIN sev_group d ON d.id = b.group_id
				where a.firm_id in($firm_str) 
			";
        $sql2 = "select count(*)
				from sev_customer_firm a 
				LEFT JOIN sev_firm e ON a.firm_id = e.id
				LEFT JOIN sev_customer b ON a.customer_id = b.id
				LEFT JOIN sev_company c ON c.id = b.company_id
				LEFT JOIN sev_group d ON d.id = b.group_id
				where a.firm_id in($firm_str) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'firm_name':
					$clause .= General::getSqlConditionClause('e.firm_name',$svalue);
					break;
				case 'client_code':
					$clause .= General::getSqlConditionClause('c.client_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('c.customer_name',$svalue);
					break;
				case 'customer_year':
					$clause .= General::getSqlConditionClause('b.customer_year',$svalue);
					break;
				case 'company_code':
					$clause .= General::getSqlConditionClause('d.company_code',$svalue);
					break;
				case 'curr':
					$clause .= General::getSqlConditionClause('a.curr',$svalue);
					break;
			}
		}
		if($this->searchArrears == "on arrears"){
            $clause .= " and a.amt>0";
        }elseif ($this->searchArrears == "off arrears"){
            $clause .= " and (a.amt <= 0 or a.amt = '' or a.amt is null)";
        }

        if(!empty($this->searchYear)){
            $year = str_replace("'","\'",$this->searchYear);
            $clause .= " and b.customer_year = '$year' ";
        }
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
		    $StaffForm = new StaffForm();
		    $langList = FunctionForm::getAllLang();
			foreach ($records as $k=>$record) {
			    $color = floatval($record['amt'])>0?"text-danger":"text-primary";
				$this->attr[] = array(
					'id'=>$record['s_id'],
					'customer_id'=>$record['customer_id'],
					'firm_name'=>$record['firm_name'],
					'client_code'=>$record['client_code'],
					'customer_name'=>$record['customer_name'],
					'customer_year'=>$record['customer_year'],
					'company_code'=>$record['company_code'],
					'curr'=>$record['curr'],
                    'amt'=>$record['amt'],
                    'color'=>$color,

					'staff_id'=>$StaffForm->getStaffNameToId($record['staff_id']),
					'salesman_id'=>$StaffForm->getStaffNameToId($record['salesman_id']),
					'payment'=>$record['payment'],
					'group_type'=>empty($record['group_type'])?Yii::t("several","not group"):Yii::t("several","is group"),
					'acca_username'=>$record['acca_username'],
					'acca_phone'=>$record['acca_phone'],
					'acca_fun'=>$record['acca_fun'],
					'acca_lang'=>$langList[$record['acca_lang']],
				);
			}
		}
		$session = Yii::app()->session;
		$session['customer_01'] = $this->getCriteria();
		return true;
	}

	public function getArrearsList(){
	    return array(
	        "all"=>Yii::t("several","all"),
	        "on arrears"=>Yii::t("several","on arrears"),
	        "off arrears"=>Yii::t("several","off arrears"),
        );
    }
}