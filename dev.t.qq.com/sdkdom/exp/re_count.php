<?
include('../inc.php');

$id = '31107113996801,77001073611107';
$p = array(
		'f' => 0,
		'ids' => $id
	);
$ret = $c->getReplayCount($p);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0, width=device-width, user-scalable=no">
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="main">
		<p class="title">getReplayCount函数使用（获取转播数）</p>
		<p>参数是一维数组：$arr['id'=>value]</br>
		reid：转发或者回复根结点ID</br>
		f：0 获取转发计数，1点评计数 2 两者都取</br>
		ids：微博ID的列表
		</p>
		<h4>获取转播数</h4>		
		<p class="title">代码示例：</p>		
		<textarea class="codearea" rows="7" cols="50">
$id = '31107113996801,77001073611107';
$p = array(
		'f' => 0,
		'ids' => $id
	);
$ret = $c->getReplayCount($p);
		</textarea>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
	</div>
</body>
</html>
