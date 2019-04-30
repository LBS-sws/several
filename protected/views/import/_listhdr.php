<tr>
	<th>
		<?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('id'),'#',$this->createOrderLink('import-list','id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('handle_name').$this->drawOrderArrow('handle_name'),'#',$this->createOrderLink('import-list','handle_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('file_name').$this->drawOrderArrow('file_name'),'#',$this->createOrderLink('import-list','file_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('file_type').$this->drawOrderArrow('file_type'),'#',$this->createOrderLink('import-list','file_type'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('lcd').$this->drawOrderArrow('lcd'),'#',$this->createOrderLink('import-list','lcd'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('lud').$this->drawOrderArrow('lud'),'#',$this->createOrderLink('import-list','lud'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('state').$this->drawOrderArrow('state'),'#',$this->createOrderLink('import-list','state'))
			;
		?>
	</th>
</tr>
