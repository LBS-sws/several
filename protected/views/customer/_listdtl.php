
<tr class='update-row <?php echo $this->record['color']; ?>' data-id="<?php echo $this->record['id']; ?>">


    <td class="float_td"><?php echo $this->drawEditButton('CU02', 'customer/edit', 'customer/view', array('index'=>$this->record['id'])); ?></td>
    <td class="float_td"><?php echo $this->record['id']; ?></td>
    <td class="float_td"><?php echo $this->record['client_code']; ?></td>
    <td class="float_td"><?php echo $this->record['customer_name']; ?></td>
    <td class="float_td"><?php echo $this->record['company_code']; ?></td>

    <?php
    $lbsMonth = 0;//總公司欠款月數
    $otherMonth = 0;//細公司欠款月數
    foreach ($this->model->firmList as $key =>$item){
        if(key_exists($key,$this->model->tableHeardList)){
            $sum = 0;
            foreach ($this->model->tableHeardList[$key] as $amt=>$value){
                $num = 0;
                if(isset($this->record['tableBody'][$key][$amt])){
                    $num = $this->record['tableBody'][$key][$amt];
                    if($key != "branch"&&$item["type"]!=1){
                        if(!isset($this->record['tableBody']["branch"][$amt])){
                            $this->record['tableBody']["branch"][$amt] = 0;
                        }
                        $this->record['tableBody']["branch"][$amt]+=$num;
                    }
                    $sum+=floatval($num);
                }
                if($num>0&&$key != "branch"){
                    if($item["type"]==1){
                        $lbsMonth++;
                    }else{
                        $otherMonth++;
                    }
                }
                echo "<td width='110px;' data-firm='$key'>".$num."</td>";
            }
            echo "<td width='110px;' data-firm='$key' class='notSum'>".$sum."</td>";
        }
    }
    ?>
    <td><?php echo $lbsMonth; ?></td>
    <td><?php echo $otherMonth; ?></td>

    <td><?php echo $this->record['staff_id']; ?></td>
    <td><?php echo $this->record['salesman_id']; ?></td>
    <td class="payment"><?php echo $this->record['payment']; ?></td>
    <td><?php echo $this->record['group_type']; ?></td>
    <td class="on_off"><?php echo $this->record['on_off']; ?></td>
    <td class="acca_username"><?php echo $this->record['acca_username']; ?></td>
    <td class="acca_phone"><?php echo $this->record['acca_phone']; ?></td>
    <td class="status_type"><?php echo Yii::t("code",$this->record['status_type']); ?></td>
    <td class="acca_fun"><?php echo $this->record['acca_fun']; ?></td>
    <td class="acca_lang"><?php echo $this->record['acca_lang']; ?></td>
    <td class="acca_fax"><?php echo $this->record['acca_fax']; ?></td>


    <td class="refer_code"><?php echo $this->record['refer_code']; ?></td>
    <td class="usual_date"><?php echo $this->record['usual_date']; ?></td>
    <td class="head_worker"><?php echo $this->record['head_worker']; ?></td>
    <td class="other_worker"><?php echo $this->record['other_worker']; ?></td>
    <td class="advance_name"><?php echo $this->record['advance_name']; ?></td>
    <td class="listing_name"><?php echo $this->record['listing_name']; ?></td>
    <td class="listing_email"><?php echo $this->record['listing_email']; ?></td>
    <td class="listing_fax"><?php echo $this->record['listing_fax']; ?></td>
    <td class="new_month"><?php echo $this->record['new_month']; ?></td>

</tr>
