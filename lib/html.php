<?php/** * 静态资源url规则 * 规则：/ 为开始的url，直接返回应用根目录 * http开始的url，返回自身 * 其他规则返回资源目录，如无资源目录，则相对于静态资源目录 * @param  string $file_name * @param  string $type * @return string */function static_url($file_name, $type){	if(strpos($file_name, '/') === 0){		return APP_URL.substr($file_name, 1);	} else if(strpos($file_name, 'http://') === 0){		return $file_name;	} else {		$map = array(			'css' => CSS_URL,			'js' => JS_URL,			'img' => IMG_URL,			'flash' => FLASH_URL		);		if($map[strtolower($type)]){			return $map[strtolower($type)].$file_name;		}		return STATIC_URL.$file_name;	}}/** * 调用js路径 * @param string $file_name * @return string**/function js_url($file_name){	return static_url($file_name, 'js');}/** * 调用css路径 * @param string $file_name * @return string**/function css_url($file_name){	return static_url($file_name, 'css');}/** * 调用img路径 * @param string $file_name * @return string**/function img_url($file_name){	return static_url($file_name, 'img');}/** * 调用flash路径 * @param string $file_name * @return string**/function flash_url($file_name){	return static_url($file_name, 'flash');}/** * 构造javascript结构 * @param string||array $js * @return string **/function js($js/**, $js2, $js3...*/){	$args = func_get_args();	$rst = '';	foreach($args as $js){		if(gettype($js) == 'string'){			if(stripos('/', $js) === false){				$js = js_url($js);			}			$rst .= '<script type="text/javascript" src="'.$js.'" charset="utf-8"></script>';		} else {			$sc = '<script type="text/javascript"';			foreach($js as $pro=>$val){				if(strtolower($pro) == 'src'){					$val = js_url($val);				}				$sc .= " $pro=\"$val\"";			}			$sc .= '></script>';			$rst .= $sc;		}	}	return $rst;}/** * 构造css结构 * @param string||array $css * @return string**/function css($css/**, $css2, $css3...*/){	$args = func_get_args();	$rst = '';	foreach($args as $css){		if(gettype($css) == 'string'){			$rst .= '<link rel="stylesheet" type="text/css" href="'.css_url($css).'" media="all"/>';		} else {			$lnk = '<link rel="stylesheet" type="text/css"';			foreach($js as $pro=>$val){				if(strtolower($pro) == 'href'){					$val = css_url($val);				}				$lnk .= " $pro=\"$val\"";			}			$lnk .= '/>';			$rst .= $lnk;		}	}	return $rst;}/** * 输出img * @param string $src * @param array $option * @return string **/function img($src, $option=array()){	$exts = '';	$adjust = false;	$adjust_fun = '__img_adjust__';	foreach($option as $key=>$val){		$exts .= " $key=\"$val\"";		if(preg_match("/(minHeight|minWidth|maxHeight|maxWidth)/i", $key)){			$adjust = true;		}	}	return "<img src=\"$src\"$exts".($adjust ? " onload=\"$adjust_fun\"" : "")."/>";};/** * build a form * @param string $url * @param array $fields * @param array $form_option * @return string **/function form($url='', array $fields, array $form_option=array()){	$form_option = array_merge(array(		'method' => 'post',		'url' => $url,		'add_submit' => true,		'title' => '',		//container template		'container_tpl' =>			'<form action="$url" method="$method" class="$class">'.				'<fieldset>'.				'<legend>$title</legend>'.				'$fields_html'.				' $submit_html'.				'</fieldset>'.			'</form>',		'submit_html' => '<dl><dt></dt><dd><input type="submit" value="submit"/></dd></dl>',		//one field template		'field_tpl' => '<dl><dt><label for="$id">$label</label></dt><dd>$element</dd></dl>'	), $form_option);	/**	 * build attribute string for element	 * @param array $field	 * @return string	 */	$build_attr = function($field){		$supports = array('name','type','class','style', 'id');		$supports_map = array(			'placeholder' => array('text','password','textarea'),			'value' => array('text','password','submit','checkbox'),			'size' => array('select'),			'cols' => array('textarea'),			'rows' => array('textarea')		);		foreach($supports_map as $attr_key=>$ls){			if(in_array($field['type'], $ls)){				array_push($supports, $attr_key);			}		}		$attrs = array();		foreach($field as $attr_key=>$val){			if(in_array($attr_key, $supports) && is_scalar($val)){				$val = str_replace('"', '\\"', $val);				array_push($attrs, $attr_key.'='.'"'.$val.'"');			}		}		return implode(' ', $attrs);	};	/**	 * build a php template with given envarionment data	 * @param  string $str	 * @param  array $data	 * @return string	 */	$build_tpl = function($str, $data, $debug=false){		$str = str_replace('"','\\"',$str);		extract($data);		if($debug){			dump(';$a = "'.$str.'"; ');		}		eval(';$a = "'.$str.'"; ');		return $a;	};	$found_submit = false;	$fields_html = '';	foreach($fields as $name=>$field){		if(!$name){			throw new Exception('NO FORM FIELD NAME GIVEN');		}		if($field['type'] == 'submit'){			$found_submit = true;		}		$field['name'] = $name;		$field['label'] = $field['type'] != 'submit' ? ($field['label'] ?: $name) : null;		$field['type'] = $field['type'] ?: 'text';		$field['id'] = $field['type'] != 'radio' ? ($field['id'] ?: '_form_'.$name) : null;		$field['attr_str'] = $build_attr($field);		$item_tpl = $field['item_tpl'] ?: '';		if(!$item_tpl){			switch($field['type']){				case 'textarea':					$item_tpl = '<textarea $attr_str>$value</textarea>';					break;				case 'select':					$item_tpl = '<select size="1" $attr_str>';					foreach($field['options'] as $val=>$label){						$item_tpl .=							'<option value="'.$val.'" '.((isset($field['value']) && ($field['value'] == $val)) ? 'selected':'').'>'.								$label.							'</option>';					}					$item_tpl .= '</select>';					break;				case 'radio':					$item_tpl = '';					foreach($field['options'] as $val=>$label){						$id = $name.'_radio_'.$val;						$item_tpl .= '<input type="radio" id="'.$id.'" value="'.$val.'" $attr_str '.((isset($field['value']) && ($field['value'] == $val)) ? 'checked="checked"':'').'/>';						$item_tpl .= '<label for="'.$id.'">$options['.$val.']</label>';					}					break;				case 'hidden':					$item_tpl = '<input type="hidden" name="$name" value="$value" />';					break;				case 'checkbox':				case 'file':				case 'text':				case 'password':				case 'submit':				default:					$item_tpl = '<input $attr_str/>';					break;			}		}		//build element html		$element = $build_tpl($item_tpl, $field);		//merge		$tmp = array_merge(array('element'=>$element), $field);		//build field html		$fields_html .= $build_tpl($form_option['field_tpl'], $tmp);	}	if($found_submit || !$form_option['add_submit']){		$form_option['submit_html'] = '';	}	$tmp = array_merge($form_option, array('fields_html' => $fields_html));	$con_tpl = $build_tpl($form_option['container_tpl'], $tmp);	return $con_tpl;}/** * 表格输出 * @param array $data 数据 * @param array $fields 显示key集合 * @param array $option 选项 * @return string html字符串 **/function table($data, array $fields=array(), array $option=array()){	$tmp = array_slice($data, 0, 1);	$first = array_pop($tmp);	if(!is_array($data) || !is_array($first) || count($data) == 0){		return '';	}	$fields = $fields ?: array_combine(array_keys($first), array_keys($first));	$option = array_merge(array(		'class' => 'tbl',		'style' => '',		'summary' => '',		'caption' => '',		'tfoot' => ''		//tfoot 扩展	), $option);	$attstr = '';	foreach($option as $k=>$v){		if(!empty($v) && $v != 'caption' && is_scalar($v)){			$attstr .= "$k=\"$v\"";		}	}	$html = '<table'.($attstr ? ' '.$attstr : '').'>';	$html .= $option['caption'] ? "<caption>$config[caption]</caption>" : '';	//colgroup	$html .= '<colgroup>';	foreach(array_keys($fields) as $k){		$html .= "<col class=\"$config[class]_col_$k\">";	}	$html .= '</colgroup>';	//head field	if(!isAssocArray($first)){		$html .= '<thead><tr>';		foreach($fields as $field){			if(is_function($field)){				$html .= '<th>'.$field(null).'</th>';			} else {				$html .= '<th>'.$field.'</th>';			}		}		$html .= '</head>';	}	//data fields	$html .= '<tbody>';	foreach($data as $item){		$html .= '<tr>';		foreach($fields as $k=>$v){			if(is_function($v)){				$html .= '<td>'.$v($item).'</td>';			} else {				$html .= '<td>'.$item[$k].'</td>';			}		}		$html .= '</tr>';	}	$html .= '</tbody>';	if($option['tfoot']){		$cols = count($fields);		$html .= '<tfoot><tr><td colspan="'.$cols.'">'.$option['tfoot'].'</td></tr></tfoot>';	}	$html .= '</table>';	return $html;}function print_exception($exception){	$msg = $exception->getMessage();	$code = $exception->getCode();	$file = $exception->getFile();	$line = $exception->getLine();	echo "<b style=\"font-size:24px\">$msg<br/>[$code]</b><br/>";	echo $file." [$line]";	echo '<pre style="font-size:12px; color:gray">';	dump($exception);}