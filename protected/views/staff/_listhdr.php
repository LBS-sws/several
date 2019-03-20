<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('id'),'#',$this->createOrderLink('staff-list','id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('staff_name').$this->drawOrderArrow('staff_name'),'#',$this->createOrderLink('staff-list','staff_name'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('staff_type').$this->drawOrderArrow('staff_type'),'#',$this->createOrderLink('staff-list','staff_type'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('staff_phone').$this->drawOrderArrow('staff_phone'),'#',$this->createOrderLink('staff-list','staff_phone'))
        ;
        ?>
    </th>
</tr>
