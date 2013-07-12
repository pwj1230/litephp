<?
include('../inc.php');

$p =array(
	'p' => 0,
	'n' => 2 
);
$ret = $c->getPublic($p);

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
		<h4>广播大厅时间线</h4>
		<p class="title">getPublic函数使用（获取广播大厅消息）</p>
		<p>参数是一维数组：$arr['p'=>value,'n'=>value]
		p: 记录的起始位置（第一次请求是填0，继续请求进填上次返回的Pos）
		n: 每次请求记录的条数（1-20条）
		</p>
		<p class="title">代码示例：</p>
		<textarea class="codearea" rows="6" cols="50">
$p =array(
	'p' => 0,
	'n' => 2 
);
$ret = $c->getPublic($p);
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
