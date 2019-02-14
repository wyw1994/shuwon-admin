<?php 
//后台单页图文控制器生成器
include_once 'maker.php';
echo "<?php\n";
?>
/**
 * @link http://www.shuwon.com/
 * @copyright Copyright (c) 2016 成都蜀美网络技术有限公司
 * @license http://www.shuwon.com/
 */
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use common\models\<?= $model_name; ?>;
/**
 * <?= $table_comment; ?>管理控制器。
 * @author 制作人
 * @since 1.0
 */
class <?= $controller_name; ?>Controller extends CommonController {
    
    public function actionInfo() {
    	if(Yii::$app->request->getIsPost()){
	        $data = Yii::$app->request->post('<?= $model_name; ?>');
	        $result = array();
	        if (is_numeric($data['<?= $module_name; ?>_id']) && $data['<?= $module_name; ?>_id'] > 0) {
	            $model = <?= $model_name; ?>::findOne($data['<?= $module_name; ?>_id']);
	            if (!$model) {
	                $result['status'] = 0;
	                $result['message'] = '未找到该记录';
	            }
	        }
	        if ($model->load(Yii::$app->request->post())) {
	            if ($model->save()) {
	                $result['status'] = 1;
	                $result['message'] = '保存成功';
	                $result['url'] = Url::toRoute(['<?= $view_name; ?>/info','id'=>$data['<?= $module_name; ?>_id']]);
	            }
	        }
	        $errors = $model->getFirstErrors();
	        if ($errors) {
	            $result['status'] = 0;
	            $result['message'] = current($errors);
	        }
	        return $this->renderJson($result);
    	}else{
    		$id = Yii::$app->request->get('id');
    		$model = <?= $model_name; ?>::findOne($id);
<?php foreach($column_name_arr as $index=>$column_name){ ?>
<?php 	$field_type = $field_type_arr[$index]; ?>
<?php 	if($field_type=='time'){//时间组件 ?>
            $model-><?= $column_name; ?> = date('Y-m-d H:i',$model-><?= $column_name; ?>); 
<?php   }elseif($field_type=='checkbox'){//复选框 ?>
            if($model-><?= $column_name; ?>){
    		    $model-><?= $column_name; ?> = explode('|',trim($model-><?= $column_name; ?>,'|'));
    		}
<?php   } ?>
<?php } ?>
    	    return $this->render('info', [
    				'model' => $model,
    				]);
    	}
    }

}

<?php 
    $file = ob_get_contents();
    file_put_contents('../backend/controllers/'.$controller_name.'Controller.php', $file);
?>