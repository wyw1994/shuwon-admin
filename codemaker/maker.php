<?php
include_once 'common.php';
//获取表名
$table_name = $_POST['table_name'];
//获取表备注
$table_comment = mb_substr($_POST['table_comment'],0,mb_strlen($_POST['table_comment'],'UTF8')-3,'utf-8');

$module_name = ltrim($table_name,'tbl_');
if(strpos($module_name,'_') !== false){
	$module_name_tmp = explode('_',$module_name);
	//获取视图名
	$view_name = implode('-',$module_name_tmp);
	array_walk($module_name_tmp,function(&$v,$k){$v = ucfirst($v);});
	//获取控制器名
	$controller_name = implode('',$module_name_tmp);
	//获取模型名
	$model_name = implode('',$module_name_tmp);
}else{
	//获取视图名
	$view_name = $module_name;
	//获取模型名
	$controller_name = ucfirst($module_name);
	//获取模型名
	$model_name = ucfirst($module_name);
}

//字段名
$column_name_arr = explode('|',sw_trim($_POST['column_name'])) ;
//数据类型
$column_type_arr = explode('|',sw_trim($_POST['column_type'])) ;
//默认值
$column_default_arr = explode('|',sw_trim($_POST['column_default'])) ;
//允许为空
$is_nullable_arr = explode('|',sw_trim($_POST['is_nullable'])) ;
//备注
$column_comment_arr = explode('|',sw_trim($_POST['column_comment'])) ;
//字段类型
$field_type_arr = explode('|',sw_trim($_POST['field_type'])) ;
//是否在搜索显示
$is_search_show_arr = explode('|',sw_trim($_POST['is_search_show'])) ;
//是否在列表显示
$is_list_show_arr = explode('|',sw_trim($_POST['is_list_show'])) ;
//是否在详情显示
$is_info_show_arr = explode('|',sw_trim($_POST['is_info_show'])) ;
//是否关联表
$is_relation_arr = explode('|',sw_trim($_POST['is_relation'])) ;

function mb_trim($string, $trim_chars = '\s'){
	return preg_replace('/^['.$trim_chars.']*(?U)(.*)['.$trim_chars.']*$/u', '\\1',$string);
}

function sw_trim($string){
	return substr(substr($string,1),0,-1);
}

?>
