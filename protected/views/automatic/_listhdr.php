<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('id'),'#',$this->createOrderLink('automatic-list','id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('staff_name').$this->drawOrderArrow('staff_name'),'#',$this->createOrderLink('automatic-list','staff_name'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('min_num').$this->drawOrderArrow('min_num'),'#',$this->createOrderLink('automatic-list','min_num'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('max_num').$this->drawOrderArrow('max_num'),'#',$this->createOrderLink('automatic-list','max_num'))
        ;
        ?>
    </th>
</tr>
