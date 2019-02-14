<?php 
//前台表单控制器生成器
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
 
    //提交<?= $table_comment; ?>信息。
    public function actionPost(){
        $result = array();
        $result['result'] = true;
    
<?php 
	    foreach ($column_name_arr as $index=>$column_name){ 
	    	if(strpos($column_name,$module_name) !== false && $column_name != $module_name.'_id'){
?>
        $<?= $column_name; ?> = Yii::$app->request->post('<?= $column_name; ?>');
<?php 
	    	}
        }
?>
        
        $<?= $model_name; ?> = new <?= $model_name; ?>();  
<?php 
	    foreach ($column_name_arr as $index=>$column_name){ 
	    	if(strpos($column_name,$module_name) !== false && $column_name != $module_name.'_id'){
?>
        $<?= $model_name; ?>-><?= $column_name; ?> = $<?= $column_name; ?>;
<?php 
	    	}
        }
?>        $<?= $model_name; ?>->save(false);
        
        return $this->renderJson($result);
    }

    public function actionUptoken() {
        $bucket = Yii::$app->params['qiniu']['bucket'];
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];
        $auth = new Auth($accessKey, $secretKey);
    
        $upToken = $auth->uploadToken($bucket);
    
        $result = array('uptoken' => $upToken);
    
        return $this->renderJson($result);
    }
    
    private function isMobile($mobile) {
        if(preg_match('/^1\d{10}$/', $mobile)) return true;
        return false;
    }
}

<?php 
    $file = ob_get_contents();
    file_put_contents('../frontend/controllers/'.$controller_name.'Controller.php', $file);
?>