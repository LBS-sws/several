<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('g.company_code'),'#',$this->createOrderLink('searchGroup-list','g.company_code'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('occurrences_num').$this->drawOrderArrow('occurrences_num'),'#',$this->createOrderLink('searchGroup-list','occurrences_num'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('arrears_number').$this->drawOrderArrow('arrears_number'),'#',$this->createOrderLink('searchGroup-list','arrears_number'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('arrears_money').$this->drawOrderArrow('arrears_money'),'#',$this->createOrderLink('searchGroup-list','arrears_money'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('salesman_one_ts').$this->drawOrderArrow('g.salesman_one_ts'),'#',$this->createOrderLink('searchGroup-list','g.salesman_one_ts'))
			;
		?>
	</th>
</tr>
