<tr>
	<th></th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('a.id'),'#',$this->createOrderLink('customer-list','a.id'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('firm_name').$this->drawOrderArrow('e.firm_name'),'#',$this->createOrderLink('customer-list','e.firm_name'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('c.client_code'),'#',$this->createOrderLink('customer-list','c.client_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('c.customer_name'),'#',$this->createOrderLink('customer-list','c.customer_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('d.company_code'),'#',$this->createOrderLink('customer-list','d.company_code'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_year').$this->drawOrderArrow('b.customer_year'),'#',$this->createOrderLink('customer-list','b.customer_year'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('curr').$this->drawOrderArrow('a.curr'),'#',$this->createOrderLink('customer-list','a.curr'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('amt').$this->drawOrderArrow('a.amt'),'#',$this->createOrderLink('customer-list','a.amt'))
			;
		?>
	</th>
</tr>
