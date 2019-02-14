<?php 
//前台单页图文控制器生成器
include_once 'maker.php';
echo "<?php\n";
?>
/**
 * @link http://www.shuwon.com/
 * @copyright Copyright (c) 2016 成都蜀美网络技术有限公司
 * @license http://www.shuwon.com/
 */
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use common\models\<?= $model_name; ?>;
/**
 * <?= $table_comment; ?>控制器。
 * @author 制作人
 * @since 1.0
 */
class <?= $controller_name; ?>Controller extends CommonController {
 
    //获取<?= $table_comment; ?>详情。
    public function actionInfo(){
        $result = array();
        $result['result'] = true;
    
        $id = Yii::$app->request->post('id');
    
        $info = <?= $model_name; ?>::findOne($id);
    
        if($info){
            $<?= $module_name; ?>_info = array();
<?php foreach ($column_name_arr as $index=>$column_name){ ?>
<?php if($is_info_show_arr[$index]){ ?>
<?php $field_type = $field_type_arr[$index];?>
<?php if($field_type=='image'){//图片 ?>
            $<?= $module_name; ?>_info['<?= $column_name_arr[$index] ?>'] = $info['<?= $column_name_arr[$index] ?>'] ? Yii::getAlias('@static').'/'.$info['<?= $column_name_arr[$index] ?>'] : '';
<?php }elseif($field_type=='radio' || $field_type=='dropdown'){//单选框或者下拉框 ?>
            $<?= $module_name; ?>_info['<?= $column_name_arr[$index] ?>'] = $info['<?= $column_name_arr[$index] ?>'];
<?php }elseif($field_type=='checkbox'){//复选框?>
            $<?= $module_name; ?>_info['<?= $column_name_arr[$index] ?>'] = $info['<?= $column_name_arr[$index] ?>name'];
<?php }elseif($field_type=='time'){//时间组件?>
            $<?= $module_name; ?>_info['<?= $column_name_arr[$index] ?>'] = date('Y年m月d日',$info['<?= $column_name_arr[$index] ?>']);
<?php }else{ ?>
            $<?= $module_name; ?>_info['<?= $column_name_arr[$index] ?>'] = $info['<?= $column_name_arr[$index] ?>'];
<?php }?>
<?php } ?>
<?php } ?>
            $result['data']['<?= $module_name; ?>_info'] = $<?= $module_name; ?>_info;
            $result['msg'] = '查询成功';
        }else{
            $result['result'] = false;
            $result['msg'] = '暂无数据';
        }
    
        return $this->renderJson($result);
    }

}

<?php 
    $file = ob_get_contents();
    file_put_contents('../frontend/controllers/'.$controller_name.'Controller.php', $file);
?>