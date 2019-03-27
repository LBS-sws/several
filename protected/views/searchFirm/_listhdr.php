<tr>
	<th></th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('firm_name').$this->drawOrderArrow('g.firm_name'),'#',$this->createOrderLink('searchFirm-list','g.firm_name'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_year').$this->drawOrderArrow('a.customer_year'),'#',$this->createOrderLink('searchFirm-list','a.customer_year'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('occurrences_num').$this->drawOrderArrow('occurrences_num'),'#',$this->createOrderLink('searchFirm-list','occurrences_num'))
        ;
        ?>
    </th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('collection_num').$this->drawOrderArrow('collection_num'),'#',$this->createOrderLink('searchFirm-list','collection_num'))
			;
		?>
	</th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('collection').$this->drawOrderArrow('collection'),'#',$this->createOrderLink('searchFirm-list','collection'))
        ;
        ?>
    </th>
</tr>
