<?php
class TestCommand extends CConsoleCommand {
    public function run() {
        echo "start";
        $row = Yii::app()->db->createCommand()->select("id")->from("sev_file")->where("state='I'")->queryRow();
        if($row){ //如果有進行中的任務，不執行
            return false;
        }
        $this->removeExcel();//刪除過期的任務
        $row = Yii::app()->db->createCommand()->select("file_url,id,lcu")->from("sev_file")->where("state='P'")->queryRow();
        if($row){
            $model = new UploadExcelForm();
            Yii::app()->db->createCommand()->update("sev_file", array("state"=>"I"),"id=:id",array(":id"=>$row["id"]));
            $model->lcu = $row["lcu"];
            $attr = $model->attributeLabels();
            $rows = Yii::app()->db->createCommand()->select("option_name,option_value")->from("sev_file_info")->where("file_id=:id",array(":id"=>$row["id"]))->queryAll();
            foreach ($rows as $item){
                if (key_exists($item["option_name"],$attr)){
                    $model[$item["option_name"]] = $item["option_value"];
                }
            }
            $model->validateFirmId(1,2);
            $loadExcel = new LoadExcel($row["file_url"]);
            $list = $loadExcel->getExcelList();
            //$loadExcel->clear();
            $model->loadSeveral($list);
            if(empty($model->error_list)){
                Yii::app()->db->createCommand()->update("sev_file", array("state"=>"C","lud"=>date("Y-m-d H:i:s")),"id=:id",array(":id"=>$row["id"]));
            }else{
                Yii::app()->db->createCommand()->update("sev_file", array("state"=>"F","lud"=>date("Y-m-d H:i:s")),"id=:id",array(":id"=>$row["id"]));
                $model->exportExcel($row["file_url"]);
            }
        }
        echo "end";
        die();
    }

    public function removeExcel(){
        $date = date("Y/m/d");
        $end = date("Y/m/d",strtotime("$date - 14 day"));
        $rows = Yii::app()->db->createCommand()->select("id,file_url")->from("sev_file")->where("date_format(lcd,'%Y/%m/%d')<='$end'")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $path =Yii::app()->basePath."/../".$row["file_url"];
                if (file_exists($path)){
                    unlink($path);
                }
                Yii::app()->db->createCommand()->delete("sev_file","id=:id",array(":id"=>$row["id"]));
                Yii::app()->db->createCommand()->delete("sev_file_info","file_id=:id",array(":id"=>$row["id"]));
            }
        }
    }
}
?>