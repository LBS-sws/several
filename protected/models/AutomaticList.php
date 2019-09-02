<?php

class AutomaticList extends CListPageModel
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
            'min_num'=>Yii::t('several','automatic min'),
            'max_num'=>Yii::t('several','automatic max'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$sql1 = "select * 
				from sev_automatic
				where id>0
			";
        $sql2 = "select count(*)
				from sev_automatic
				where id>0
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'staff_name':
					$clause .= General::getSqlConditionClause('staff_name',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by min_num asc ";
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
					'min_num'=>$record['min_num'],
					'max_num'=>$record['max_num'],
					'staff_name'=>$record['staff_name']
				);
			}
		}
		$session = Yii::app()->session;
		$session['automatic_01'] = $this->getCriteria();
		return true;
	}
}