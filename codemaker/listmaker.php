<?php 
//后台列表视图生成器
include_once 'maker.php';
echo "<?php\n";
?>
/**
 * @link http://www.shuwon.com/
 * @copyright Copyright (c) 2016 成都蜀美网络技术有限公司
 * @license http://www.shuwon.com/
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;
<?php 
echo "?>\n";
?>
<table id="" class="table table-striped table-bordered responsive">
    <thead>
        <tr>
        <?php 
	    foreach ($column_name_arr as $index=>$column_name){ 
	    ?>
		    <?php 
		    if($is_list_show_arr[$index]){
		    ?>
              <th><?= $column_comment_arr[$index] ?></th>
            <?php } ?>
        <?php } ?>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php echo "<?php foreach (\$provider->models as \$list) { ?>\n";?>
            <tr>
             <?php 
	         foreach ($column_name_arr as $index=>$column_name){   
	         ?>
		         <?php if($is_list_show_arr[$index]>0){ ?>
                    <td class="center">
                    <?php 
                        $field_type = $field_type_arr[$index];
                        if($field_type=='image'){//图片
                        	echo "<img src=\"<?= Yii::getAlias('@static') ?>/<?= Html::encode(\$list->".$column_name.") ?>\" height=\"50\">";
                        }elseif($field_type=='radio' || $field_type=='dropdown'){//单选框或者下拉框
                        	if($is_relation_arr[$index]==1){
                        		$module_name_temp = rtrim($column_name,'_relation');
                        		$module_name_temp = rtrim($module_name_temp,'_relations');
                        		if(strpos($module_name_temp,'_') !== false){
                        			$module_name_temp_tmp = explode('_',$module_name_temp);
                        			array_walk($module_name_temp_tmp,function(&$v,$k){$v = ucfirst($v);});
                        			//获取模型名
                        			$model_name_temp = lcfirst(implode('',$module_name_temp_tmp));
                        		}else{
                        			//获取模型名
                        			$model_name_temp = lcfirst(ucfirst($module_name_temp));
                        		}
                        		echo "<?= Html::encode(\$list->".$model_name_temp."->".$module_name_temp."_title) ?>";
                        	}else{
                        		echo "<?= Yii::\$app->params['".$column_name."'][\$list->".$column_name."] ?>";
                        	}
                        }elseif($field_type=='checkbox'){//复选框
                        	echo "<?= Html::encode(\$list->".$column_name."name) ?>";
                        }elseif($field_type=='time'){//时间组件
                        	echo "<?= Html::encode(date('Y-m-d H:i:s',\$list->".$column_name.")) ?>";
                        }else{
                        	echo "<?= Html::encode(\$list->".$column_name.") ?>";
                        }
                    ?>
                    </td>
                 <?php } ?> 
             <?php } ?>
                <td class="center">
                 
                    <a class="btn btn-info" href='<?php echo "<?= Url::toRoute(['".$view_name."/edit','id' => \$list->".$module_name."_id]) ?>";?>'>
                        <i class="glyphicon glyphicon-edit icon-white"></i>
                        编辑
                    </a>
                 
                    <a class="btn btn-danger" href="javascript:void(0);" onclick='del(<?php echo "<?= \$list->".$module_name."_id ?>";?>)'>
                        <i class="glyphicon glyphicon-trash icon-white"></i>
                        删除
                    </a>
                </td>
            </tr>
        <?php echo "<?php } ?>";?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="20">
                <button class="btn btn-default pull-left" style="display: inline-block" disabled="disabled">(当前<?php echo "<?= \$provider->count ?>";?>条/共<?php echo "<?= \$provider->totalCount ?>";?>条)</button>
                <?php echo "<?=\n";?>
                LinkPager::widget([
                    'pagination' => $provider->pagination,
                    'linkOptions' => ['onclick' => 'return goPage(this)'],
                    'options' => ['class' => 'pagination pull-right', 'style' => 'margin:0px']
                ]);
                <?php echo "?>\n";?>
            </td>
        </tr>
    </tfoot>
</table>
<script type="text/javascript">
    $(function(){
        $('[data-toggle="popover"]').popover();
    })
</script>
<?php 
    $file = ob_get_contents();
    $dir = '../backend/views/'.$view_name;
    if(!is_dir($dir)){
    	mkdir($dir,0777,true);
    }
    file_put_contents($dir.'/list.php', $file);
?>