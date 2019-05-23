
<tr class='update-row <?php echo $this->record['color']; ?>' data-id="<?php echo $this->record['id']; ?>">


    <td><?php echo $this->drawEditButton('CU02', 'customer/edit', 'customer/view', array('index'=>$this->record['id'])); ?></td>
    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['firm_name']; ?></td>
    <td><?php echo $this->record['client_code']; ?></td>
    <td><?php echo $this->record['customer_name']; ?></td>
    <td><?php echo $this->record['company_code']; ?></td>
    <td class="curr"><?php echo $this->record['curr']; ?></td>
    <td><?php echo $this->record['firmHtml']; ?></td>
    <td><?php echo $this->record['amt']; ?></td>

    <td><?php echo $this->record['staff_id']; ?></td>
    <td><?php echo $this->record['salesman_id']; ?></td>
    <td class="payment"><?php echo $this->record['payment']; ?></td>
    <td><?php echo $this->record['group_type']; ?></td>
    <td class="acca_username"><?php echo $this->record['acca_username']; ?></td>
    <td class="acca_phone"><?php echo $this->record['acca_phone']; ?></td>
    <td class="remarkHtml"><?php echo $this->record['remarkHtml']; ?></td>
    <td class="status_type"><?php echo Yii::t("code",$this->record['status_type']); ?></td>
    <td class="acca_fun"><?php echo $this->record['acca_fun']; ?></td>
    <td class="acca_lang"><?php echo $this->record['acca_lang']; ?></td>
</tr>
