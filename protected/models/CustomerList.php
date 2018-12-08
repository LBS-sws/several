<?php

class CustomerList extends CListPageModel
{
    public $searchArrears;//
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'id'=>Yii::t('several','ID'),
			'customer_code'=>Yii::t('several','Customer Code'),
			'customer_name'=>Yii::t('several','Customer Name'),
			'customer_year'=>Yii::t('several','Customer Year'),
			'company_code'=>Yii::t('several','Company Code'),
			'curr'=>Yii::t('several','Curr'),
			'amt'=>Yii::t('several','Amt'),
		);
	}
    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchArrears','safe',),
        );
    }
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select * 
				from sev_customer
				where id>0
			";
        $sql2 = "select count(*)
				from sev_customer
				where id>0
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'customer_code':
					$clause .= General::getSqlConditionClause('customer_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('customer_name',$svalue);
					break;
				case 'customer_year':
					$clause .= General::getSqlConditionClause('customer_year',$svalue);
					break;
				case 'company_code':
					$clause .= General::getSqlConditionClause('company_code',$svalue);
					break;
				case 'curr':
					$clause .= General::getSqlConditionClause('curr',$svalue);
					break;
			}
		}
		if($this->searchArrears == "on arrears"){
            $clause .= " and amt>0";
        }elseif ($this->searchArrears == "off arrears"){
            $clause .= " and (amt = 0 or amt = '' or amt is null)";
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
			    $color = floatval($record['amt'])>0?"text-danger":"text-primary";
				$this->attr[] = array(
					'id'=>$record['id'],
					'customer_code'=>$record['customer_code'],
					'customer_name'=>$record['customer_name'],
					'customer_year'=>$record['customer_year'],
					'company_code'=>$record['company_code'],
					'curr'=>$record['curr'],
					'amt'=>$record['amt'],
					'color'=>$color,
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