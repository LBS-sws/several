<tr>
	<th></th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('id'),'#',$this->createOrderLink('firm-list','id'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('firm_name').$this->drawOrderArrow('firm_name'),'#',$this->createOrderLink('firm-list','firm_name'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('z_index').$this->drawOrderArrow('z_index'),'#',$this->createOrderLink('firm-list','z_index'))
        ;
        ?>
    </th>
</tr>
