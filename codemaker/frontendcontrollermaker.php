<?php 
//前台控制器生成器
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
    //获取<?= $table_comment; ?>列表。
    public function actionList(){
        $result = array();
        $result['result'] = true;
        
        $category_id_str = Yii::$app->request->post('category_id');
        $page = Yii::$app->request->post('page');
        $pageSize = Yii::$app->request->post('pageSize',6);
  
        $query = <?= $model_name; ?>::find()->where(['enabled' => 1]);
        
        $category_id_arr = explode('|',$category_id_str);
        
        $query = <?= $model_name; ?>::find()->where(['in', '<?= $module_name; ?>_category', $category_id_arr]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => $pageSize,'page' => ($page-1)]);//分页
        $result_arr = $query->offset($pages->offset)->orderby(['<?= $module_name; ?>_id'=>SORT_DESC])->limit($pages->limit)->all();//排序
        
        $result['data']['<?= $module_name; ?>_list'] = array();
        if($result_arr){
            foreach ($result_arr as $val){
            	//返回数组
            	$result['data']['<?= $module_name; ?>_list'][] = array(
<?php foreach ($column_name_arr as $index=>$column_name){ ?>
<?php if($is_list_show_arr[$index]){ ?>
<?php $field_type = $field_type_arr[$index];?>
<?php if($field_type=='image'){//图片 ?>
                     '<?= $column_name_arr[$index] ?>'=>$val['<?= $column_name_arr[$index] ?>'] ? Yii::getAlias('@static').'/'.$val['<?= $column_name_arr[$index] ?>'] : '',
<?php }elseif($field_type=='radio' || $field_type=='dropdown'){//单选框或者下拉框 ?>
                     '<?= $column_name_arr[$index] ?>'=>$val['<?= $column_name_arr[$index] ?>'],
<?php }elseif($field_type=='checkbox'){//复选框?>
                     '<?= $column_name_arr[$index] ?>'=>$val['<?= $column_name_arr[$index] ?>name'],
<?php }elseif($field_type=='time'){//时间组件?>
                     '<?= $column_name_arr[$index] ?>'=>date('Y年m月d日',$val['<?= $column_name_arr[$index] ?>']),
<?php }else{ ?>
                     '<?= $column_name_arr[$index] ?>'=>$val['<?= $column_name_arr[$index] ?>'],
<?php }?>
<?php } ?>
<?php } ?>
            	);
            }
            $result['data']['total'] = $countQuery->count();
            $result['msg'] = '查询成功';
        }else{
            $result['result'] = false;
            $result['msg'] = '暂无数据';
        }
        
        return $this->renderJson($result);
    }
   
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
<?php if($field_type=='image' || $field_type=='file' || $field_type=='audio' || $field_type=='video'){//图片或者文件或者音频或者视频 ?>
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