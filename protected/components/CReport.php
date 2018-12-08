<?php
class CReport {
	public $criteria;
	
	public $data = array();
	
	public $excel;
	public $title;
	public $subtitle;
	
	public function genReport() {
		return true;
	}

	protected function sendEmail(&$connection, $record=array()) {
		$suffix = Yii::app()->params['envSuffix'];
		$suffix1 = ($suffix=='dev') ? '_w' : $suffix;
		$sql = "insert into swoper$suffix1.swo_email_queue
					(from_addr, to_addr, cc_addr, subject, description, message, status, lcu)
				values
					(:from_addr, :to_addr, :cc_addr, :subject, :description, :message, 'P', 'admin')
			";
		$command = $connection->createCommand($sql);
		if (strpos($sql,':from_addr')!==false)
			$command->bindParam(':from_addr',$record['from_addr'],PDO::PARAM_STR);
		if (strpos($sql,':to_addr')!==false)
			$command->bindParam(':to_addr',$record['to_addr'],PDO::PARAM_STR);
		if (strpos($sql,':cc_addr')!==false)
			$command->bindParam(':cc_addr',$record['cc_addr'],PDO::PARAM_STR);
		if (strpos($sql,':subject')!==false)
			$command->bindParam(':subject',$record['subject'],PDO::PARAM_STR);
		if (strpos($sql,':description')!==false)
			$command->bindParam(':description',$record['description'],PDO::PARAM_STR);
		if (strpos($sql,':message')!==false)
			$command->bindParam(':message',$record['message'],PDO::PARAM_STR);
		$command->execute();
	}
	
	protected function sendEmailWithAttachment(&$connection, $record=array(), $attachment=array()) {
		$suffix = Yii::app()->params['envSuffix'];
		$suffix1 = ($suffix=='dev') ? '_w' : $suffix;

		$transaction=$connection->beginTransaction();
		try {
			$sql = "insert into swoper$suffix1.swo_email_queue
						(from_addr, to_addr, cc_addr, subject, description, message, status, lcu)
					values
						(:from_addr, :to_addr, :cc_addr, :subject, :description, :message, 'P', 'admin')
				";
			$command = $connection->createCommand($sql);
			if (strpos($sql,':from_addr')!==false)
				$command->bindParam(':from_addr',$record['from_addr'],PDO::PARAM_STR);
			if (strpos($sql,':to_addr')!==false)
				$command->bindParam(':to_addr',$record['to_addr'],PDO::PARAM_STR);
			if (strpos($sql,':cc_addr')!==false)
				$command->bindParam(':cc_addr',$record['cc_addr'],PDO::PARAM_STR);
			if (strpos($sql,':subject')!==false)
				$command->bindParam(':subject',$record['subject'],PDO::PARAM_STR);
			if (strpos($sql,':description')!==false)
				$command->bindParam(':description',$record['description'],PDO::PARAM_STR);
			if (strpos($sql,':message')!==false)
				$command->bindParam(':message',$record['message'],PDO::PARAM_STR);
			$command->execute();

			if (!empty($attachment)) {
				$id = $connection->getLastInsertID();
				$sql = "insert into swoper$suffix1.swo_email_queue_attm
							(queue_id, name, content)
						values
							(:queue_id, :name, :content)
					";
				foreach ($attachment as $key=>$content) {
					$command = $connection->createCommand($sql);
					if (strpos($sql,':queue_id')!==false)
						$command->bindParam(':queue_id',$id,PDO::PARAM_INT);
					if (strpos($sql,':name')!==false)
						$command->bindParam(':name',$key,PDO::PARAM_STR);
					if (strpos($sql,':content')!==false)
						$command->bindParam(':content',$content,PDO::PARAM_LOB);
					$command->execute();
				}
			}

			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			echo 'Cannot update.'.$e->getMessage();
			Yii::app()->end();
		}
	}
	
	protected function fields() {
		return array();
	}
	
	protected function exportExcel() {
		$this->excel = new ExcelTool();
		$this->excel->start();
		
		$this->excel->newFile();
		if (!empty($this->sheetname)) $this->excel->getActiveSheet()->setTitle($this->sheetname);
		$this->excel->setReportDefaultFormat();
		$this->printHeader();
		$this->printDetail();
		$outstring = $this->excel->getOutput();
		
		$this->excel->end();
		return $outstring;
	}
	
	protected function printHeader() {
		$title = empty($this->title) ? '' : $this->title;
		$subtitle = empty($this->subtitle) ? '' : $this->subtitle;

		$fields = $this->fields();
		
		$this->excel->writeReportTitle($title, $subtitle);
		if (!empty($fields)) {		
			$j = 0; // column
			$row = 3;
			foreach ($fields as $id=>$items) {
				$this->excel->writeCell($j, $row, $items['label']);
				$this->excel->setColWidth($j, $items['width']);
				$j++;
			}

			$itemcnt = count($fields);
			$range = $this->excel->getColumn(0).$row.':'.$this->excel->getColumn($itemcnt-1).$row;
			$this->excel->setRangeStyle($range,true,false,'C','C','allborders',true);
		}
	}
	
	protected function printDetail() {
		$fields = $this->fields();
		if (!empty($fields) && !empty($this->data)) {		
			$itemcnt = count($this->data);
			// Print Detail
			$y = 4;
			foreach ($this->data as $row) {
				$x = 0;
				foreach ($fields as $key=>$items) {
					$val = $row[$key];
					$this->excel->writeCell($x, $y, $val, array('align'=>$items['align']));
					$x++;
				}
				$y++;
			}
		}
	}
}
?>