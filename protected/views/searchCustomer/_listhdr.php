<tr>
	<th></th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('a.id'),'#',$this->createOrderLink('searchCustomer-list','a.id'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('firm_name').$this->drawOrderArrow('e.firm_name'),'#',$this->createOrderLink('searchCustomer-list','e.firm_name'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('c.client_code'),'#',$this->createOrderLink('searchCustomer-list','c.client_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('c.customer_name'),'#',$this->createOrderLink('searchCustomer-list','c.customer_name'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('d.company_code'),'#',$this->createOrderLink('searchCustomer-list','d.company_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('curr').$this->drawOrderArrow('a.curr'),'#',$this->createOrderLink('searchCustomer-list','a.curr'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('amt').$this->drawOrderArrow('a.amt'),'#',$this->createOrderLink('searchCustomer-list','a.amt'))
			;
		?>
	</th>
</tr>
