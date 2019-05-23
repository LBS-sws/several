<tr>
	<th></th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('g.client_code'),'#',$this->createOrderLink('searchCompany-list','g.client_code'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('g.customer_name'),'#',$this->createOrderLink('searchCompany-list','g.customer_name'))
        ;
        ?>
    </th>
    <th>
        <?php echo TbHtml::link($this->getLabelName('sum_num').$this->drawOrderArrow('sum_num'),'#',$this->createOrderLink('searchCompany-list','sum_num'))
        ;
        ?>
    </th>
</tr>
