<tr>
	<th width="30px"></th>
    <th width="110px">
        <?php echo TbHtml::link($this->getLabelName('id').$this->drawOrderArrow('a.id'),'#',$this->createOrderLink('customer-list','a.id'))
        ;
        ?>
    </th>
	<th width="140px">
		<?php echo TbHtml::link($this->getLabelName('client_code').$this->drawOrderArrow('c.client_code'),'#',$this->createOrderLink('customer-list','c.client_code'))
			;
		?>
	</th>

	<th width="250px">
		<?php echo TbHtml::link($this->getLabelName('customer_name').$this->drawOrderArrow('c.customer_name'),'#',$this->createOrderLink('customer-list','c.customer_name'))
			;
		?>
	</th>
	<th width="140px">
		<?php echo TbHtml::link($this->getLabelName('company_code').$this->drawOrderArrow('d.company_code'),'#',$this->createOrderLink('customer-list','d.company_code'))
			;
		?>
	</th>



    <?php
    foreach ($this->model->firmList as $key =>$item){
        if(key_exists($key,$this->model->tableHeardList)){
            foreach ($this->model->tableHeardList[$key] as $value){
                echo "<th width='110px;' data-firm='$key' class='changeTableTop'>".$item["name"]."<br>".$value."</th>";
            }
            echo "<th width='110px;' data-firm='$key' class='changeTableTop notSum'>".$item["name"]."<br>".Yii::t("several","Amt")."</th>";
        }
    }
    ?>

	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('lbs_month').$this->drawOrderArrow('a.lbs_month'),'#',$this->createOrderLink('customer-list','a.lbs_month'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('other_month').$this->drawOrderArrow('a.other_month'),'#',$this->createOrderLink('customer-list','a.other_month'))
			;
		?>
	</th>

	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('staff_id').$this->drawOrderArrow('a.staff_id'),'#',$this->createOrderLink('customer-list','a.staff_id'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('salesman_id').$this->drawOrderArrow('a.salesman_id'),'#',$this->createOrderLink('customer-list','a.salesman_id'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('payment').$this->drawOrderArrow('a.payment'),'#',$this->createOrderLink('customer-list','a.payment'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('group_type').$this->drawOrderArrow('a.group_type'),'#',$this->createOrderLink('customer-list','a.group_type'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('acca_username').$this->drawOrderArrow('a.acca_username'),'#',$this->createOrderLink('customer-list','a.acca_username'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('acca_phone').$this->drawOrderArrow('a.acca_phone'),'#',$this->createOrderLink('customer-list','a.acca_phone'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('status_type').$this->drawOrderArrow('a.status_type'),'#',$this->createOrderLink('customer-list','a.status_type'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('acca_fun').$this->drawOrderArrow('a.acca_fun'),'#',$this->createOrderLink('customer-list','a.acca_fun'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('acca_lang').$this->drawOrderArrow('a.acca_lang'),'#',$this->createOrderLink('customer-list','a.acca_lang'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('acca_fax').$this->drawOrderArrow('a.acca_fax'),'#',$this->createOrderLink('customer-list','a.acca_fax'))
			;
		?>
	</th>

	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('refer_code').$this->drawOrderArrow('a.refer_code'),'#',$this->createOrderLink('customer-list','a.refer_code'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('usual_date').$this->drawOrderArrow('a.usual_date'),'#',$this->createOrderLink('customer-list','a.usual_date'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('head_worker').$this->drawOrderArrow('a.head_worker'),'#',$this->createOrderLink('customer-list','a.head_worker'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('other_worker').$this->drawOrderArrow('a.other_worker'),'#',$this->createOrderLink('customer-list','a.other_worker'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('advance_name').$this->drawOrderArrow('a.advance_name'),'#',$this->createOrderLink('customer-list','a.advance_name'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('listing_name').$this->drawOrderArrow('a.listing_name'),'#',$this->createOrderLink('customer-list','a.listing_name'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('listing_email').$this->drawOrderArrow('a.listing_email'),'#',$this->createOrderLink('customer-list','a.listing_email'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('listing_fax').$this->drawOrderArrow('a.listing_fax'),'#',$this->createOrderLink('customer-list','a.listing_fax'))
			;
		?>
	</th>
	<th width="110px">
		<?php echo TbHtml::link($this->getLabelName('new_month').$this->drawOrderArrow('a.new_month'),'#',$this->createOrderLink('customer-list','a.new_month'))
			;
		?>
	</th>
</tr>
