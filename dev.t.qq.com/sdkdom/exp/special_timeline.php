<?
include('../inc.php');

$p =array(
	'f' => 0,
	't' => 0,		
	'n' => 5,
    'ct' => 2,
	'a' => 1
);
$ret = $c->getMySpecial($p);
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
		<p class="title">getMySpecial函数使用（获取特别收听）</p>
		<p>参数是一维数组：$arr['t'=>value,'f'=>value,'p'=>value,'n'=>value]</br>
		f 分页标识（0：第一页，1：向下翻页，2向上翻页）</br>
		t: 本页起始时间（第一页 0，继续：根据返回记录时间决定）</br>
		n: 每次请求记录的条数（1-20条）</br>
		ct: 内容过滤 填零表示所有类型 1-带文本 2-带链接 4图片 8-带视频 0x10-带音频</br>
		a: 权限标识 1 表示只显示我发表的</br>
		type : 0x1 原创发表  0x2 转载 0x8 回复  0x10 空回 0x20  提及 0x40 点评 如需拉取多个类型请|上(0x1|0x2) 得到3，type=3即可,填零表示拉取所有类型</br>
		Accesslevel: 权限标识 1 表示只显示我发表的
		</p>
		<h4>获取特别收听的人的时间线</h4>
		<p class="title">代码示例：</p>
		<textarea class="codearea" rows="9" cols="50">
$p =array(
	'f' => 0,
	't' => 0,		
	'n' => 5,
	'ct' => 2,
	'a' => 1
);
$ret = $c->getMySpecial($p);
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



