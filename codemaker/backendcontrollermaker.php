<?php 
//后台控制器生成器
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

   public function actionIndex() {
        return $this->render('index', [
        		'model' => new <?= $controller_name; ?>(),
               ]);
    }
    
    public function actionAdd() {
        if(Yii::$app->request->getIsPost()){
            $data = Yii::$app->request->post('<?= $model_name; ?>');
            $result = array();
            $model = new <?= $controller_name; ?>();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    $result['status'] = 1;
                    $result['message'] = '保存成功';
                    $result['url'] = Url::toRoute('<?= $view_name; ?>/index');
                }
            }
            $errors = $model->getFirstErrors();
            if ($errors) {
                $result['status'] = 0;
                $result['message'] = current($errors);
            }
            return $this->renderJson($result);
        }else{
            $model = new <?= $controller_name; ?>();
<?php foreach($column_name_arr as $index=>$column_name){ ?>
<?php 	$field_type = $field_type_arr[$index]; ?>
<?php 	if($field_type=='time'){//时间组件 ?>
            $model-><?= $column_name; ?> = date('Y-m-d H:i'); 
<?php   } ?>
<?php } ?>
            return $this->render('add', [
                'model' => $model,
        				]);
        }
    }
    
    public function actionEdit() {
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
	                $result['url'] = Url::toRoute('<?= $view_name; ?>/index');
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
    		return $this->render('edit', [
    				'model' => $model,
    				]);
    	}
    }

    public function actionList() {
        $query = <?= $model_name; ?>::find();
        <?php 
            $search_column_name = array();
            $search_column_comment = array();
            $search_field_type = array();
		    foreach ($column_name_arr as $index=>$column_name){   
			    if($is_search_show_arr[$index]>0){
			        $search_column_name[] = $column_name;
			        $search_column_comment[] = $column_comment_arr[$index];
			        $search_field_type[] = $field_type_arr[$index];
			    }
		    }
        ?>
        
<?php 
   foreach ($search_column_name as $index=>$column_name){   
?>
<?php 
	    $column_comment = $search_column_comment[$index];
        $field_type = $search_field_type[$index];
        if($field_type=='text'){//文本框
              echo "        \$query->andFilterWhere([\"like\",\"".$column_name."\",Yii::\$app->request->get(\"".$column_name."\")]);\n";
        }elseif($field_type=='radio' || $field_type=='dropdown'){//单选框或者下拉框
              echo "        \$query->andFilterWhere([\"".$column_name."\"=>Yii::\$app->request->get(\"".$column_name."\")]);\n";
        }
?>
<?php } ?>
        
        $provider = new ActiveDataProvider([
        		'query' => $query,
        		'pagination' => [
        		'pageSize' => 10,
        		],
        		'sort' => [
        		'defaultOrder' => [
        		'<?= $module_name; ?>_id' => SORT_DESC,
        		]
        		],
        		]);
        return $this->renderPartial('list', ['provider' => $provider]);
    }

    public function actionDel($id) {
        $result = array();
        $model = <?= $model_name; ?>::findOne($id);
        $model->delete();
        $result['status'] = 1;
        $result['message'] = '删除成功';
        return $this->renderJson($result);
    }

}

<?php 
    $file = ob_get_contents();
    file_put_contents('../backend/controllers/'.$controller_name.'Controller.php', $file);
?>