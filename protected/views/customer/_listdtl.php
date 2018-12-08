
<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('CU01', 'customer/edit', 'customer/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('CU01', 'customer/edit', 'customer/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['customer_code']; ?></td>
    <td><?php echo $this->record['customer_name']; ?></td>
    <td><?php echo $this->record['company_code']; ?></td>
    <td><?php echo $this->record['customer_year']; ?></td>
    <td><?php echo $this->record['curr']; ?></td>
    <td><?php echo $this->record['amt']; ?></td>
</tr>
