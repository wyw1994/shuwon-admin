<?php 
//后台首页视图生成器
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
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = '后台首页';
$this->params['breadcrumbs'][] = '<?= $table_comment; ?>列表';
<?php 
echo "?>\n";
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $table_comment; ?>列表</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active"><?= $table_comment; ?>列表</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header" data-original-title="">
                
            </div>

            <div class="box-create">
                    <?php echo "<?php\n"?>
	                $form = ActiveForm::begin([
	                     'id' => 'list-form',
                         'action' => Url::toRoute('<?= $view_name; ?>/list'),
                         'options' => ['class' => 'form-horizontal'],
	                ]);
	                <?php echo "?>\n";?>
	                 <?php 
		                 $search_column_name = array();
		                 $search_column_comment = array();
		                 $search_field_type = array();
		                 $search_is_relation = array();
				         foreach ($column_name_arr as $index=>$column_name){   
					         if($is_search_show_arr[$index]>0){
					            $search_column_name[] = $column_name;
					            $search_column_comment[] = $column_comment_arr[$index];
					            $search_field_type[] = $field_type_arr[$index];
					            $search_is_relation[] = $is_relation_arr[$index];
					         }
				         }
		              ?>
	                  <?php 
	                     $search_cnt = count($search_column_name)-1;
				         foreach ($search_column_name as $index=>$column_name){
				      ?>
				         
<?php 
			         	if($index==0 || $index%3==0){
?>
<?php 
			         	if($index!=0){
?>
			        </div>
<?php 
			         	}
?>
			        <div class="form-group form-group-sm">
<?php 
			         	}
?>
					        <?php 
					            $column_comment = $search_column_comment[$index];
		                        $field_type = $search_field_type[$index];
		                        if($field_type=='text'){//文本框
		                        	echo '<label class="control-label col-md-1">'.$column_comment.'</label>';
		                        	echo '<div class="col-md-2">';
		                        	echo '<input class="form-control" type="text" name="'.$column_name.'">';
		                        	echo '</div>';
		                        }elseif($field_type=='radio'){//单选框
		                        	echo '<label class="control-label col-md-1">'.$column_comment.'</label>';
		                        	echo '<div class="col-md-2">';
		                        	if($search_is_relation[$index]==1){
		                        		$module_name_temp = rtrim($column_name,'_relation');
		                        		$module_name_temp = rtrim($module_name_temp,'_relations');
		                        		if(strpos($module_name_temp,'_') !== false){
		                        			$module_name_temp_tmp = explode('_',$module_name_temp);
		                        			array_walk($module_name_temp_tmp,function(&$v,$k){$v = ucfirst($v);});
		                        			//获取模型名
		                        			$model_name_temp = implode('',$module_name_temp_tmp);
		                        		}else{
		                        			//获取模型名
		                        			$model_name_temp = ucfirst($module_name_temp);
		                        		}
		                        		echo '<?= Html::radioList("'.$column_name.'", 0, ArrayHelper::map(common\\models\\'.$model_name_temp.'::find()->all(), \''.$module_name_temp.'_id\', \''.$module_name_temp.'_title\')) ?>';
		                        	}else{
		                        		echo '<?= Html::radioList("'.$column_name.'", 0, Yii::$app->params["'.$column_name.'"]) ?>';
		                        	}
		                        	echo '</div>';
		                        }elseif($field_type=='dropdown'){//下拉框
		                        	echo '<label class="control-label col-md-1">'.$column_comment.'</label>';
		                        	echo '<div class="col-md-2">';
		                        	if($search_is_relation[$index]==1){
		                        		$module_name_temp = rtrim($column_name,'_relation');
		                        		$module_name_temp = rtrim($module_name_temp,'_relations');
		                        		if(strpos($module_name_temp,'_') !== false){
		                        			$module_name_temp_tmp = explode('_',$module_name_temp);
		                        			array_walk($module_name_temp_tmp,function(&$v,$k){$v = ucfirst($v);});
		                        			//获取模型名
		                        			$model_name_temp = implode('',$module_name_temp_tmp);
		                        		}else{
		                        			//获取模型名
		                        			$model_name_temp = ucfirst($module_name_temp);
		                        		}
		                                echo '<?= Html::dropDownList("'.$column_name.'", 0, ArrayHelper::map(common\\models\\'.$model_name_temp.'::find()->all(), \''.$module_name_temp.'_id\', \''.$module_name_temp.'_title\'), ["prompt" => "全部", "class" => "form-control"]) ?>';
	                                }else{
	                                	echo '<?= Html::dropDownList("'.$column_name.'", 0, Yii::$app->params["'.$column_name.'"], ["prompt" => "全部", "class" => "form-control"]) ?>';
	                                }
		                            echo '</div>';
		                        }
		                    ?>
			             <?php } ?>
			             
<?php 
			         	if($index==$search_cnt){
?>
			        </div>
<?php 
			         	}
?>

	                <div class="form-group form-group-sm">
	                    <input name="page" value="1" type="hidden">
	                    <div class="col-md-2">
	                        <a href="#" onclick="getList(1)" class="btn btn-default btn-block" ><i class="glyphicon glyphicon-search"></i><span>查询</span></a>
	                    </div>
	                </div>

	                <?php echo "<?php ActiveForm::end(); ?>\n";?>
            </div>
            
            <div class="box-content"  data-list="<?php echo "<?= Url::toRoute('".$view_name."/list') ?>";?>" data-del="<?php echo "<?= Url::toRoute('".$view_name."/del') ?>";?>">

            </div>
            
        </div>
    </div>
</div>
</section>
<?php echo "<?php\n"?>
$js=<<<JS
   $(function(){
        getList();
   });
   //获得<?= $table_comment; ?>列表
   window.getList=function(page){
 	   page!=null?$("#list-form input[name='page']").val(page):null; 
       $.ajax({
        url: $(".box-content").data("list"),
 		data:$("#list-form").serialize(),
        beforeSend: function () {
            layer.load();
        },
        complete: function () {
            layer.closeAll('loading');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            layer.alert('出错拉:' + textStatus + ' ' + errorThrown, {icon: 5});
        },
        success: function (data) {
            $(".box-content").html(data);
        }
    });
   }
   //禁用<?= $table_comment; ?>信息
   window.del=function(id){
        layer.confirm('确定删除?', function(index){
            layer.close(index);
            $.ajax({
                url: $(".box-content").data("del"),
                data:{
                    id:id
                },
                beforeSend: function () {
                    layer.load();
                },
                complete: function () {
                    layer.closeAll('loading');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.alert('出错拉:' + textStatus + ' ' + errorThrown, {icon: 5});
                },
                success: function (data) {
                    if (data.status == 1){
                        layer.alert(data.message, {icon: 6},function(index){
                            getList();
                            layer.close(index);
                        });
                    }else{
                        layer.alert(data.message, {icon: 5}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });  
        });  
   }
   window.goPage=function(obj){
          var page=$(obj).data('page')+1;
          getList(page);
          return false;
   }
JS;
$this->registerJs($js);
<?php echo "?>\n";?>
<?php 
    $file = ob_get_contents();
    $dir = '../backend/views/'.$view_name;
    if(!is_dir($dir)){
    	mkdir($dir,0777,true);
    }
    file_put_contents($dir.'/index.php', $file);
?>