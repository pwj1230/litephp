<?
include('../inc.php');

$ret = $c->getUserInfo();

$p = array(
		'n' => 't'
	);
$ret1 = $c->getUserInfo($p);

if(isset($_POST['wbname'])){
	$p = array('n' => $_POST['wbname']);
	$ret2 = $c->getUserInfo($p);
}
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
		<p class="title">getUserInfo函数使用（获取当前用户的信息）</p>
		<p>参数是一维数组：$arr['n'=>value]</br>
		n:用户名 空表示本人</br>
		</p>
		<h4>获取自己的详细资料</h4>
		<p class="title">代码示例：</p>
		<div>
		<textarea class="codearea" rows="2" cols="50">
$ret = $c->getUserInfo();
		</textarea>
		<div>
			<p>代码返回结果</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
		</div>
		<h4>获取其他用户详细资料</h4>
		<form action="" method="POST" name="form" id="form">
			<input type="text" name="wbname" value="" />
			<input type="submit" value="提交" />
			<label>仅为示例程序，请输入英文注册用户名</label>
		</form>
		<div>
			<?if(isset($ret2)):?>
				<p>代码返回结果</p>
				<?php
					$c->printArr($ret2);
				?>
			<?else:?>
				<p>提交查看返回结果</p>
			<?endif?>
		</div>
		<p class="title">代码示例：</p>
		<textarea class="codearea" rows="5" cols="50">
$p = array(
		'n' => 't'
	);
$ret1 = $c->getUserInfo($p);
		</textarea>
		<div>
			<p>代码返回结果</p>
			<?php
				$c->printArr($ret1);
			?>
		</div>
	</div>
</body>
</html>


