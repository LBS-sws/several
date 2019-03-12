
<tr class='clickable-row' data-href='<?php echo $this->getLink('MR02', 'clients/edit', 'clients/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('MR02', 'clients/edit', 'clients/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['client_code']; ?></td>
    <td><?php echo $this->record['customer_name']; ?></td>
    <td><?php echo $this->record['company_code']; ?></td>
    <td><?php echo $this->record['staff_name']; ?></td>
    <td><?php echo $this->record['salesman']; ?></td>
    <td><?php echo $this->record['firm_name_us']; ?></td>
</tr>
