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


	<th>
		<?php echo TbHtml::link($this->getLabelName('staff_id').$this->drawOrderArrow('b.staff_id'),'#',$this->createOrderLink('customer-list','b.staff_id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('salesman_id').$this->drawOrderArrow('b.salesman_id'),'#',$this->createOrderLink('customer-list','b.salesman_id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('payment').$this->drawOrderArrow('b.payment'),'#',$this->createOrderLink('customer-list','b.payment'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('group_type').$this->drawOrderArrow('b.group_type'),'#',$this->createOrderLink('customer-list','b.group_type'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('acca_username').$this->drawOrderArrow('b.acca_username'),'#',$this->createOrderLink('customer-list','b.acca_username'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('acca_phone').$this->drawOrderArrow('b.acca_phone'),'#',$this->createOrderLink('customer-list','b.acca_phone'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('acca_fun').$this->drawOrderArrow('b.acca_fun'),'#',$this->createOrderLink('customer-list','b.acca_fun'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('acca_lang').$this->drawOrderArrow('b.acca_lang'),'#',$this->createOrderLink('customer-list','b.acca_lang'))
			;
		?>
	</th>
</tr>
