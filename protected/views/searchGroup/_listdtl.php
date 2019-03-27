
<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('BC01', 'searchGroup/view', 'searchGroup/view', array('index'=>$this->record['id'],'year'=>$this->record['customer_year']));?>'>


    <td><?php echo $this->needHrefButton('BC01', 'searchGroup/view', 'view', array('index'=>$this->record['id'],'year'=>$this->record['customer_year'])); ?></td>

    <td><?php echo $this->record['company_code']; ?></td>
    <td><?php echo $this->record['customer_year']; ?></td>
    <td><?php echo $this->record['occurrences_num']; ?></td>
    <td><?php echo $this->record['arrears_number']; ?></td>
    <td><?php echo $this->record['arrears_money']; ?></td>
    <td><?php echo $this->record['salesman_one_ts']; ?></td>
</tr>
