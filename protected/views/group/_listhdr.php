<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('a.company_code'),'#',$this->createOrderLink('group-list','a.company_code'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('staff_name').$this->drawOrderArrow('b.staff_name'),'#',$this->createOrderLink('group-list','b.staff_name'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('assign_date').$this->drawOrderArrow('a.assign_date'),'#',$this->createOrderLink('group-list','a.assign_date'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('cross_district').$this->drawOrderArrow('a.cross_district'),'#',$this->createOrderLink('group-list','a.cross_district'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('occurrences').$this->drawOrderArrow('a.occurrences'),'#',$this->createOrderLink('group-list','a.occurrences'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('salesman_one_ts').$this->drawOrderArrow('a.salesman_one_ts'),'#',$this->createOrderLink('group-list','a.salesman_one_ts'))
        ;
        ?>
    </th>
</tr>
