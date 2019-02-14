<?php 
    foreach ($column_name_arr as $index=>$column_name){   
    	$field_form = '';
    	$field_type = $field_type_arr[$index];
    	if($field_type=='hidden'){//隐藏域
    		$field_form .= 'hiddenInput()->label(false)';
    	}elseif($field_type=='text'){//文本框
    		$field_form .= 'textInput()';
    	}elseif($field_type=='textarea'){//文本域
    		$field_form .= 'textarea()';
    	}elseif($field_type=='radio'){//单选框
    		if($is_relation_arr[$index]==1){
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
    			$field_form .= 'inline()->radioList(ArrayHelper::map(common\\models\\'.$model_name_temp.'::find()->all(), \''.$module_name_temp.'_id\', \''.$module_name_temp.'_title\'))';
    		}else{
    			$field_form .= 'inline()->radioList(Yii::$app->params[\''.$column_name.'\'])';
    	    }
    	}elseif($field_type=='checkbox'){//复选框
    		if($is_relation_arr[$index]==2){
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
    			$field_form .= 'inline()->checkboxList(ArrayHelper::map(common\\models\\'.$model_name_temp.'::find()->all(), \''.$module_name_temp.'_id\', \''.$module_name_temp.'_title\'))';
    		}else{
    			$field_form .= 'inline()->checkboxList(Yii::$app->params[\''.$column_name.'\'])';
    		}
    	}elseif($field_type=='dropdown'){//下拉框
    		if($is_relation_arr[$index]==1){
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
    			$field_form .= 'dropDownList(ArrayHelper::map(common\\models\\'.$model_name_temp.'::find()->all(), \''.$module_name_temp.'_id\', \''.$module_name_temp.'_title\'), [\'prompt\' => \'--请选择类别--\'])';
    		}else{
    			$field_form .= 'dropDownList(Yii::$app->params[\''.$column_name.'\'], [\'prompt\' => \'--请选择类别--\'])';
    		}
    	}elseif($field_type=='image'){//图片上传
    		$field_form .= 'widget(\'shuwon\images\Webuploader\')';
    	}elseif($field_type=='file'){//文件上传
    		$field_form .= 'widget(\'shuwon\file\Webuploader\')';
    	}elseif($field_type=='audio'){//音频上传
    		$field_form .= 'widget(\'shuwon\audio\Webuploader\')';
    	}elseif($field_type=='video'){//视频上传
    		$field_form .= 'widget(\'shuwon\video\Webuploader\')';
    	}elseif($field_type=='editor'){//编辑器
    		$field_form .= 'widget(Ueditor::className())';
    	}elseif($field_type=='time'){//时间组件
    		$field_form .= 'textInput()';
    	}else{
    		$field_form .= $field_type.'()';
    	}
?>
<?php  echo "                <?= \$form->field(\$model, '".$column_name."')->".$field_form." ?>\n";?>
    <?php } ?>
<?php  echo "                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>\n";?>