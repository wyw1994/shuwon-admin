<?php 
//模型生成器
include_once 'maker.php';
echo "<?php\n";
?>
/**
 * @link http://www.shuwon.com/
 * @copyright Copyright (c) 2016 成都蜀美网络技术有限公司
 * @license http://www.shuwon.com/
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * <?= $table_comment; ?>模型。
 * @author 制作人
 * @since 1.0
 */
class <?= $controller_name; ?> extends ActiveRecord {

    public static function tableName() {
        return '<?= $table_name; ?>';
    }

    public function attributeLabels() {
        return [
    <?php 
	    foreach ($column_name_arr as $index=>$column_name){   
	?>
	    '<?= $column_name ?>' => '<?= $column_comment_arr[$index] ?>',
	<?php } ?>
        ];
    }

    public function rules() {
        <?php 
	        $trim = '';
	        $required = '';
	        $length_key = '';
	        $length_value = '';
	        $filter = '';
	        $number = '';
	        $default_key = '';
	        $default_value = '';
	        $diy = '';
	        foreach ($column_name_arr as $index=>$column_name){
	        	//判断字段类型
		        $field_type = $field_type_arr[$index];
		        if($field_type=='text' || $field_type=='textarea' || $field_type=='image' || $field_type=='editor'){//文本框或者文本域或者图片上传或者编辑器
		        	$trim .= "'".$column_name."',";
		        }elseif($field_type=='radio' || $field_type=='dropdown'){//单选框或者下拉框
		        	$number .= "'".$column_name."',";
		        }elseif($field_type=='time'){//时间组件
		        	$filter .= "'".$column_name."',";
		        }elseif($field_type=='checkbox'){//复选框
		        	$diy .= $column_name.',';
		        }else{
		        	$required .= '';
		        }
		        //判断数据类型
		        $column_type = $column_type_arr[$index];
		        if(strpos($column_type,'varchar') !== false){
		        	$length_key .= "".$column_name.",";
		        	$column_type_value = ltrim($column_type,'varchar(');
		        	$column_type_value = rtrim($column_type_value,')');
		        	$length_value .= "".$column_type_value.",";
		        }
		        //判断默认值
		        $column_default = $column_default_arr[$index];
		        if($column_default != ''){
		        	$default_key .= "".$column_name.",";
		        	$default_value .= "".$column_default.",";
		        }
		        //允许为空
		        $is_nullable = $is_nullable_arr[$index];
		        if($is_nullable == 'NO' && $index > 0 && !in_array($column_name,$reserved_word_arr)){
		        	$required .= "'".$column_name."',";
		        }
	        }
	        $trim = $trim ? rtrim($trim,',') : '';
	        $required = $required ? rtrim($required,',') : '';
	        $number = $number ? rtrim($number,',') : '';
	        $filter = $filter ? rtrim($filter,',') : '';
	        $diy_arr = $diy ? explode(',',rtrim($diy,',')) : array();
	        $length_key_arr = $length_key ? explode(',',rtrim($length_key,',')) : array();
	        $length_value_arr = $length_value ? explode(',',rtrim($length_value,',')) : array();
	        $default_key_arr = $default_key ? explode(',',rtrim($default_key,',')) : array();
	        $default_value_arr = $default_value ? explode(',',rtrim($default_value,',')) : array();
        ?>
return [
             <?php if($trim) {?>[[<?= $trim ?>], 'trim'], <?php } ?><?php echo "\n"; ?>
             <?php if($required) {?>[[<?= $required ?>], 'required'], <?php } ?><?php echo "\n"; ?>
             <?php if($filter) {?>[[<?= $filter ?>], 'filter', 'filter' => 'strtotime'], <?php } ?><?php echo "\n"; ?>
             <?php if($number) {?>[[<?= $number ?>], 'number'], <?php } ?><?php echo "\n"; ?>
             <?php if($diy_arr) { foreach ($diy_arr as $diy) {?>[['<?= $diy ?>'], 'set<?= $diy ?>'], <?php }} ?><?php echo "\n"; ?>
             <?php if($length_key_arr) { foreach ($length_key_arr as $index=>$length_key) {?>['<?= $length_key ?>', 'string', 'length' => [1, <?= $length_value_arr[$index] ?>]], <?php }} ?><?php echo "\n"; ?>
             <?php if($default_key_arr) { foreach ($default_key_arr as $index=>$default_key) {?>['<?= $default_key ?>', 'default','value'=><?= $default_value_arr[$index]?>], <?php }} ?><?php echo "\n"; ?>
        ];
    }
    
<?php 
     if($diy_arr) { 
     	$diy_column_name = array();
     	foreach ($column_name_arr as $index=>$column_name){
     		if($is_relation_arr[$index]==2){
     			$diy_column_name[$column_name] = 1;
     		}else{
     			$diy_column_name[$column_name] = 0;
     		}
     	}
     	
    	foreach ($diy_arr as $diy) {
?>
    public function set<?= $diy ?>($attribute, $params){
	    if($this-><?= $diy ?> && is_array($this-><?= $diy ?>)){
	        $this-><?= $diy ?> = '|'.implode('|',$this-><?= $diy ?>).'|';
	    }
	}
	    
	public function get<?= $diy ?>name(){
	    if($this-><?= $diy ?>){
	        $<?= $diy ?>_arr = explode('|',trim($this-><?= $diy ?>,'|'));
	        $<?= $diy ?>name = '';
<?php if($diy_column_name[$diy]==1){ 
	            	$module_name_temp = rtrim($diy,'_relation');
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
?>
			foreach ($<?= $diy ?>_arr as $<?= $diy ?>){
			    $<?= $diy; ?>name .= <?= $model_name_temp; ?>::find()->where(['<?= $module_name_temp; ?>_id'=>$<?= $diy ?>])->select('<?= $module_name_temp; ?>_title')->scalar().' ';
	        }
<?php }else{ ?>
	        foreach ($<?= $diy ?>_arr as $<?= $diy ?>){
	            $<?= $diy ?>name .= Yii::$app->params['<?= $diy ?>'][$<?= $diy ?>].' ';
	        }
<?php } ?>
	        return $<?= $diy ?>name;
	    }else{
	        return '';
	    }
	}
<?php 
    	}
     }
?>
    
<?php 
	   foreach ($column_name_arr as $index=>$column_name){
	   	  if($is_relation_arr[$index]==1){
	   	  	$module_name_temp = rtrim($column_name,'_relation');
	   	  	if(strpos($module_name_temp,'_') !== false){
	   	  		$module_name_temp_tmp = explode('_',$module_name_temp);
	   	  		array_walk($module_name_temp_tmp,function(&$v,$k){$v = ucfirst($v);});
	   	  		//获取模型名
	   	  		$model_name_temp = implode('',$module_name_temp_tmp);
	   	  	}else{
	   	  		//获取模型名
	   	  		$model_name_temp = ucfirst($module_name_temp);
	   	  	}
?>
	public function get<?= $model_name_temp ?>(){
        return $this->hasOne(<?= $model_name_temp ?>::className(), ['<?= $module_name_temp ?>_id' => '<?= $column_name ?>']);
    }
<?php 
	   	  }
       }
?>
    
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
                $this->updated_at = time();
            }else{
                $this->updated_at = time();
            }
            $this->user_id = !empty(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0;
            return true;
        }
        return false;
    }

}

<?php 
    $file = ob_get_contents();
    file_put_contents('../common/models/'.$model_name.'.php', $file);
?>