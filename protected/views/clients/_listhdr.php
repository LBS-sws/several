<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('a.id'),'#',$this->createOrderLink('clients-list','a.id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('b.client_code'),'#',$this->createOrderLink('clients-list','b.client_code'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('b.customer_name'),'#',$this->createOrderLink('clients-list','b.customer_name'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('e.company_code'),'#',$this->createOrderLink('clients-list','e.company_code'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('staff_name').$this->drawOrderArrow('d.staff_name'),'#',$this->createOrderLink('clients-list','d.staff_name'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('salesman').$this->drawOrderArrow('salesman'),'#',$this->createOrderLink('clients-list','salesman'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('firm_name_us').$this->drawOrderArrow('a.firm_name_us'),'#',$this->createOrderLink('clients-list','a.firm_name_us'))
        ;
        ?>
    </th>
</tr>
