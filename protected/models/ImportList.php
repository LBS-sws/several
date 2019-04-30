<?php

class ImportList extends CListPageModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'id'=>Yii::t('queue','ID'),
			'handle_name'=>Yii::t('queue','Report'),
			'file_name'=>Yii::t('queue','file name'),
			'file_type'=>Yii::t('queue','Format'),
			'state'=>Yii::t('queue','Status'),
			'lcd'=>Yii::t('queue','Req. Date'),
			'lud'=>Yii::t('queue','Comp. Date'),
			//'z_index'=>Yii::t('several','index'),
		);
	}
	
	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$uid = Yii::app()->user->id;
		$sql1 = "select * 
				from sev_file
				where lcu = '$uid' 
			";
        $sql2 = "select count(*)
				from sev_file
				where lcu = '$uid' 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'file_name':
					$clause .= General::getSqlConditionClause('file_name',$svalue);
					break;
				case 'handle_name':
					$clause .= General::getSqlConditionClause('handle_name',$svalue);
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
					'handle_name'=>$record['handle_name'],
					'file_name'=>$record['file_name'],
					'file_type'=>$record['file_type'],
					'status'=>$record['state'],
					'state'=>CGeneral::getJobStatusDesc($record['state']),
					'lcd'=>$record['lcd'],
					'lud'=>$record['lud'],
				);
			}
		}
		$session = Yii::app()->session;
		$session['import_01'] = $this->getCriteria();
		return true;
	}
}