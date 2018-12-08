<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('customer_code').$this->drawOrderArrow('customer_code'),'#',$this->createOrderLink('customer-list','customer_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('customer_name'),'#',$this->createOrderLink('customer-list','customer_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('company_code'),'#',$this->createOrderLink('customer-list','company_code'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_year').$this->drawOrderArrow('customer_year'),'#',$this->createOrderLink('customer-list','customer_year'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('curr').$this->drawOrderArrow('curr'),'#',$this->createOrderLink('customer-list','curr'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('amt').$this->drawOrderArrow('amt'),'#',$this->createOrderLink('customer-list','amt'))
			;
		?>
	</th>
</tr>
