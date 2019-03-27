<tr>
	<th></th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('staff_name').$this->drawOrderArrow('g.staff_name'),'#',$this->createOrderLink('searchStaff-list','g.staff_name'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('staff_phone').$this->drawOrderArrow('g.staff_phone'),'#',$this->createOrderLink('searchStaff-list','g.staff_phone'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_year').$this->drawOrderArrow('a.customer_year'),'#',$this->createOrderLink('searchStaff-list','a.customer_year'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('occurrences_num').$this->drawOrderArrow('occurrences_num'),'#',$this->createOrderLink('searchStaff-list','occurrences_num'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('collection_num').$this->drawOrderArrow('collection_num'),'#',$this->createOrderLink('searchStaff-list','collection_num'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('collection').$this->drawOrderArrow('collection'),'#',$this->createOrderLink('searchStaff-list','collection'))
        ;
        ?>
    </th>
</tr>
