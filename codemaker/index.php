<?php
/**
 * 代码生成器
 */
include_once 'common.php';
// 取得所有表名
while ($row = mysql_fetch_array($result)){
	$tables[]['TABLE_NAME'] = $row[0];
}
// 循环取得所有表的备注及表中列消息
foreach($tables as $k => $v){
	$sql = 'SELECT * FROM ';
	$sql .= 'INFORMATION_SCHEMA.TABLES ';
	$sql .= 'WHERE ';
	$sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database['DB_NAME']}'";
	$table_result = mysql_query($sql, $mysql_conn);
	while ($t = mysql_fetch_array($table_result)){
		$tables[$k]['TABLE_COMMENT'] = $t['TABLE_COMMENT'];
	}
	$sql = 'SELECT * FROM ';
	$sql .= 'INFORMATION_SCHEMA.COLUMNS ';
	$sql .= 'WHERE ';
	$sql .= "table_name = '{$v['TABLE_NAME']}' AND table_schema = '{$database['DB_NAME']}'";
	$fields = array();
	$field_result = mysql_query($sql, $mysql_conn);
	while ($t = mysql_fetch_array($field_result)){
		$fields[] = $t;
	}
	$tables[$k]['COLUMN'] = $fields;
}
mysql_close($mysql_conn);

$tbl = !empty($_GET['tbl']) ? $_GET['tbl'] : die("还未选择表");

$html = '';
// 循环所有表
foreach($tables as $k => $v){
	if(strpos($v['TABLE_NAME'],'tbl_') !== false && $v['TABLE_NAME']==$tbl){
		$html .= '<table border="1" cellspacing="0" cellpadding="0" align="center">';
		$html .= '<caption onclick="maker(\'' . $v['TABLE_NAME'] . '\',\'' . $v['TABLE_COMMENT'] . '\');">表名：' . $v['TABLE_NAME'] . ' ------- ' . $v['TABLE_COMMENT'] . '  ' . '</caption>';
		$html .= '<tbody><tr><th>字段名</th><th>数据类型</th><th>默认值</th><th>是否必填</th><th>自动递增</th><th>备注</th><th>字段类型</th><th>是否在搜索显示</th><th>是否在列表显示</th><th>是否在详情显示</th><th>是否关联表</th></tr>';
		$html .= '';
		foreach($v['COLUMN'] AS $k=>$f){
			$html .= '<td class="c1">' . $f['COLUMN_NAME'] . '</td>';
			//隐藏字段名
			$html .= '<input type="hidden" name="' . $v['TABLE_NAME'] . '_column_name[]" value="' . $f['COLUMN_NAME'] . '"/>';
			
			$html .= '<td class="c2">' . $f['COLUMN_TYPE'] . '</td>';
			//隐藏数据类型
			$html .= '<input type="hidden" name="' . $v['TABLE_NAME'] . '_column_type[]" value="' . $f['COLUMN_TYPE'] . '"/>';
			
			$html .= '<td class="c3">' . $f['COLUMN_DEFAULT'] . '</td>';
			//隐藏默认值
			$html .= '<input type="hidden" name="' . $v['TABLE_NAME'] . '_column_default[]" value="' . $f['COLUMN_DEFAULT'] . '"/>';
			
			$html .= '<td class="c4">' . ($f['IS_NULLABLE'] == 'NO' ? '是' : '否') . '</td>';
			//允许非空
			$html .= '<input type="hidden" name="' . $v['TABLE_NAME'] . '_is_nullable[]" value="' . $f['IS_NULLABLE'] . '"/>';
			
			$html .= '<td class="c5">' . ($f['EXTRA'] == 'auto_increment' ? '是' : ' ') . '</td>';
			
			$html .= '<td class="c6">' . $f['COLUMN_COMMENT'] . '</td>';
			//隐藏备注
			$html .= '<input type="hidden" name="' . $v['TABLE_NAME'] . '_column_comment[]" value="' . $f['COLUMN_COMMENT'] . '"/>';
			
			$field_type = '<select name="' . $v['TABLE_NAME'] . '_field_type[]">';
			
			$field_type .= '<option value ="text">文本框</option>';
			$field_type .= '<option value ="textarea" '.($f['COLUMN_TYPE'] == 'text'  ? 'selected' : '').'>文本域</option>';
			
			$field_type .= '<option value ="radio" '.(strpos($f['COLUMN_TYPE'],'tinyint(1)') !== false  ? 'selected' : '').'>单选框</option>';
			$field_type .= '<option value ="checkbox" '.(strpos($f['COLUMN_TYPE'],'tinytext') !== false  ? 'selected' : '').'>复选框</option>';
			$field_type .= '<option value ="dropdown" '.(strpos($f['COLUMN_TYPE'],'smallint(5)') !== false  ? 'selected' : '').'>下拉框</option>';

			$field_type .= '<option value ="image" '.(strpos($f['COLUMN_NAME'],'img') !== false || strpos($f['COLUMN_NAME'],'face') !== false ? 'selected' : '').'>图片</option>';
			$field_type .= '<option value ="file" '.(strpos($f['COLUMN_NAME'],'file') !== false ? 'selected' : '').'>文件</option>';
			$field_type .= '<option value ="audio" '.(strpos($f['COLUMN_NAME'],'audio') !== false ? 'selected' : '').'>音频</option>';
			$field_type .= '<option value ="video" '.(strpos($f['COLUMN_NAME'],'video') !== false ? 'selected' : '').'>视频</option>';
			
			$field_type .= '<option value ="editor" '.($f['COLUMN_TYPE'] == 'longtext'  ? 'selected' : '').'>编辑器</option>';
			
			$field_type .= '<option value ="time" '.(strpos($f['COLUMN_NAME'],'time') !== false  ? 'selected' : '').'>时间组件</option>';
			
			$field_type .= '<option value ="hidden" '.(in_array($f['COLUMN_NAME'],$reserved_word_arr) || $f['EXTRA'] == 'auto_increment'  ? 'selected' : '').'>隐藏域</option>';
			
			$field_type .= '</select>';
			
			$html .= '<td class="c7">'.$field_type.'</td>';
			
			$html .= '<td class="c8"><input type="checkbox" name="' . $v['TABLE_NAME'] . '_is_search_show" '.(!in_array($f['COLUMN_NAME'],$reserved_word_arr) && $f['COLUMN_TYPE'] != 'tinytext' && $f['COLUMN_TYPE'] != 'text' && $f['COLUMN_TYPE'] != 'longtext' && strpos($f['COLUMN_NAME'],'time') === false && strpos($f['COLUMN_NAME'],'img') === false && strpos($f['COLUMN_NAME'],'face') === false && strpos($f['COLUMN_NAME'],'file') === false && strpos($f['COLUMN_NAME'],'audio') === false && strpos($f['COLUMN_NAME'],'video') === false && $k > 0 ? 'checked' : '').'/></td>';
			
			$html .= '<td class="c8"><input type="checkbox" name="' . $v['TABLE_NAME'] . '_is_list_show" '.(!in_array($f['COLUMN_NAME'],$reserved_word_arr) && $f['COLUMN_TYPE'] != 'longtext' && strpos($f['COLUMN_NAME'],'file') === false && strpos($f['COLUMN_NAME'],'audio') === false && strpos($f['COLUMN_NAME'],'video') === false ? 'checked' : '').'/></td>';
			
			$html .= '<td class="c8"><input type="checkbox" name="' . $v['TABLE_NAME'] . '_is_info_show" '.(!in_array($f['COLUMN_NAME'],$reserved_word_arr) ? 'checked' : '').'/></td>';
			
			$html .= '<td class="c9"><select name="' . $v['TABLE_NAME'] . '_is_relation[]"><option value ="0">否</option><option value ="1" '.(strpos($f['COLUMN_NAME'],'relation') !== false  ? 'selected' : '').'>一对一 </option><option value ="2" '.(strpos($f['COLUMN_NAME'],'relations') !== false  ? 'selected' : '').'>一对多</option></select></td>';
					 
			$html .= '</tr>';
		}
	}
	$html .= '</tbody></table></p>';
}
?>
<html>
	<meta charset="utf-8">
	<title>代码生成器</title>
	<style>
		body,td,th {font-family:"宋体"; font-size:12px;}
		table,h1,p{width:960px;margin:0px auto;}
		table{border-collapse:collapse;border:1px solid #CCC;background:#efefef;}
		table caption{text-align:left; background-color:#fff; line-height:2em; font-size:14px; font-weight:bold; }
		table th{text-align:left; font-weight:bold;height:26px; line-height:26px; font-size:12px; border:1px solid #CCC;padding-left:5px;}
		table td{height:20px; font-size:12px; border:1px solid #CCC;background-color:#fff;padding-left:5px;}
		.c1{ width: 150px;}
		.c2{ width: 150px;}
		.c3{ width: 80px;}
		.c4{ width: 100px;}
		.c5{ width: 100px;}
		.c6{ width: 100px;}
		.c7{ width: 150px;}
		.c8{ width: 80px;}
		.c9{ width: 100px;}
	</style>
	<body>
<h1 style="text-align:center;">代码生成器</h1>
<div style="text-align:center;"><input type="checkbox" name="is_form"/>是否是表单   <input type="checkbox" name="is_page"/>是否是单页图文   <input type="checkbox" name="is_make_model" checked/>是否生成模型   <input type="checkbox" name="is_make_backendcontroller" checked/>是否生成后台控制器  <input type="checkbox" name="is_make_backendview" checked/>是否生成后台视图   <input type="checkbox" name="is_make_frontendcontroller" checked/>是否生成前台控制器</div>
<?php echo $html; ?>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
	function maker(table_name,table_comment){
        //字段名
        var column_name = '|';
		$("input[name^='"+table_name+"_column_name[]']").each(function(i){  
			column_name += this.value+'|';  
        });
        //数据类型
        var column_type = '|';
        $("input[name^='"+table_name+"_column_type[]']").each(function(i){  
        	column_type += this.value+'|';  
        });
        //默认值
        var column_default = '|';
        $("input[name^='"+table_name+"_column_default[]']").each(function(i){  
        	column_default += this.value+'|';  
        });
        //允许为空
        var is_nullable = '|';
        $("input[name^='"+table_name+"_is_nullable[]']").each(function(i){  
        	is_nullable += this.value+'|';  
        });
        //备注
	    var column_comment = '|';
		$("input[name^='"+table_name+"_column_comment[]']").each(function(i){  
			column_comment += this.value+'|';  
        });
	    //字段类型
		var field_type = '|';
		$("select[name^='"+table_name+"_field_type[]']").each(function(i){  
			field_type += this.value+'|';  
        });
		//是否在搜索显示
		var is_search_show = '|';
		$("input[name^='"+table_name+"_is_search_show']").each(function(i){  
		    if(this.checked){
		    	is_search_show += '1|';  
			}else{
				is_search_show += '0|';  
		    }
        });
		//是否在列表显示
		var is_list_show = '|';
		$("input[name^='"+table_name+"_is_list_show']").each(function(i){  
		    if(this.checked){
		    	is_list_show += '1|';  
			}else{
				is_list_show += '0|';  
		    }
        });
		//是否在详情显示
		var is_info_show = '|';
		$("input[name^='"+table_name+"_is_info_show']").each(function(i){  
		    if(this.checked){
		    	is_info_show += '1|';  
			}else{
				is_info_show += '0|';  
		    }
        });
		//是否关联表
		var is_relation = '|';
		$("select[name^='"+table_name+"_is_relation']").each(function(i){  
			is_relation += this.value+'|';  
        });
		
		//是否是单页图文
		var is_page = 0;
        if($("input[name^='is_page']").is(':checked')){
        	is_page = 1;
        }

        //是否是表单
		var is_form = 0;
        if($("input[name^='is_form']").is(':checked')){
        	is_form = 1;
        }
        
        var data = {
        		table_name: table_name,
        		table_comment: table_comment,
        		column_name: column_name,
        		column_type: column_type,
        		column_default: column_default,
        		is_nullable: is_nullable,
        		column_comment: column_comment,
        		field_type: field_type,
        		is_search_show: is_search_show,
        		is_list_show: is_list_show,
        		is_info_show: is_info_show,
        		is_relation: is_relation,
                } ;
        
        //是否生成模型
        if($("input[name^='is_make_model']").is(':checked')){
        	$.ajax({
    			type: "post",
    			url: 'modelmaker.php',
    			data: data,
    			dataType: 'json',
    			complete: function(XMLHttpRequest, status) {
    				//alert('模型生成成功');
    			}
    		})
        }

        //是否生成后台控制器
        if($("input[name^='is_make_backendcontroller']").is(':checked')){
        	$.ajax({
    			type: "post",
    			url: is_page ? 'backendpagecontrollermaker.php' : 'backendcontrollermaker.php',
    			data: data,
    			dataType: 'json',
    			complete: function(XMLHttpRequest, status) {
    				//alert('后台控制器生成成功');
    			}
    		})
        }
        
        //是否生成后台视图
        if($("input[name^='is_make_backendview']").is(':checked')){
            if(is_page){//是否是单页图文
            	$.ajax({
        			type: "post",
        			url: 'infomaker.php',
        			data: data,
        			dataType: 'json',
        			complete: function(XMLHttpRequest, status) {
        				//alert('后台单页图文视图生成成功');
        			}
        		})
            }else{
            	$.ajax({
        			type: "post",
        			url: 'addmaker.php',
        			data: data,
        			dataType: 'json',
        			complete: function(XMLHttpRequest, status) {
        				//alert('后台视图生成成功');
        			}
        		})
        		$.ajax({
        			type: "post",
        			url: 'editmaker.php',
        			data: data,
        			dataType: 'json',
        			complete: function(XMLHttpRequest, status) {
        				//alert('后台视图生成成功');
        			}
        		})
        		$.ajax({
        			type: "post",
        			url: 'indexmaker.php',
        			data: data,
        			dataType: 'json',
        			complete: function(XMLHttpRequest, status) {
        				//alert('后台首页生成成功');
        			}
        		})
        		$.ajax({
        			type: "post",
        			url: 'listmaker.php',
        			data: data,
        			dataType: 'json',
        			complete: function(XMLHttpRequest, status) {
        				//alert('后台列表生成成功');
        			}
        		})
            }
        	
        }

        //是否生成前台控制器
        if($("input[name^='is_make_frontendcontroller']").is(':checked')){
			$.ajax({
				type: "post",
				url: is_page ? 'frontendpagecontrollermaker.php' : (is_form ? 'frontendformcontrollermaker.php' : 'frontendcontrollermaker.php'),
				data: data,
				dataType: 'json',
				complete: function(XMLHttpRequest, status) {
					//alert('前台控制器生成成功');
				}
			})
        }
    }
</script>
</html>
