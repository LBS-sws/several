<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('client_code'),'#',$this->createOrderLink('company-list','client_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('customer_name'),'#',$this->createOrderLink('company-list','customer_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('group_id').$this->drawOrderArrow('group_id'),'#',$this->createOrderLink('company-list','group_id'))
			;
		?>
	</th>
</tr>
