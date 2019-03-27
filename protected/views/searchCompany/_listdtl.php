
<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('BC02', 'searchCompany/view', 'searchGroup/view', array('index'=>$this->record['id'],'year'=>$this->record['customer_year']));?>'>


    <td><?php echo $this->needHrefButton('BC02', 'searchCompany/view', 'view', array('index'=>$this->record['id'],'year'=>$this->record['customer_year'])); ?></td>

    <td><?php echo $this->record['client_code']; ?></td>
    <td><?php echo $this->record['customer_name']; ?></td>
    <td><?php echo $this->record['customer_year']; ?></td>
    <td><?php echo $this->record['sum_num']; ?></td>
</tr>
