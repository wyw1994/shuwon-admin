<?php 
//后台单页图文视图生成器
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
use kucha\ueditor\UEditor;

$this->title = '后台首页';
$this->params['breadcrumbs'][] = '<?= $table_comment; ?>信息';
<?php 
echo "?>\n";
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $table_comment; ?>信息</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
    <li class="active"><?= $table_comment; ?>信息</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header" data-original-title="">
                
            </div>

            <div class="box-content">
                    <?php echo "<?php\n"?>
	                $form = ActiveForm::begin([
	                     'id' => 'info-form',
	                     'action' => Url::toRoute('<?= $view_name; ?>/info'),
	                ]);
	                <?php echo "?>\n";?>
	<?php include_once 'formmaker.php';?>
	                <?php echo "<?php ActiveForm::end(); ?>\n";?>
            </div>
            
        </div>
    </div>
</div>
</section>
<?php echo "<?php\n"?>
$js=<<<JS
     $.datetimepicker.setLocale('ch');
<?php foreach ($column_name_arr as $index=>$column_name){ ?>
<?php 	$field_type = $field_type_arr[$index]; ?>
<?php 	if($field_type=='time'){//时间组件 ?>
     $('#<?= $controller_name; ?>-<?= $column_name; ?>').datetimepicker();
<?php 	}?>
<?php } ?>
     $("#info-form").on('beforeSubmit',function(e){
        ajaxSubmitForm($(this));
        return false;
    });
JS;
$this->registerJs($js);
<?php echo "?>\n";?>
<?php 
    $file = ob_get_contents();
    $dir = '../backend/views/'.$view_name;
    if(!is_dir($dir)){
    	mkdir($dir,0777,true);
    }
    mkdir('../backend/views/'.$view_name,0777,true);
    file_put_contents($dir.'/info.php', $file);
?>