<?
include('../inc.php');

$p = array(
	'n' => 'username',
	'type' => 0
);
$ret = $c->setMyidol($p);
if(isset($_POST['foname'])&&isset($_POST['type'])){
	$arr = array(
		'n' => $_POST['foname'],
		'type' => $_POST['type']
	);
	$ret1 = $c->setMyidol($arr);
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
		<h4>收听username</h4>
		<p class="title">setMyidol函数使用（收听/取消收听某人）</p>
		<p>参数是一维数组：$arr['num'=>value,'s'=>value]</br>
		n: 用户名</br>
		type: 0 取消收听,1 收听 , 4 加入黑名单 5 从黑名单中删除</br>
		</p>
		<p class="title">示例程序:</p>
		<form action="" method="POST" >
			<input type="text" value="" name="foname" />
			<input type="submit" value="提交" name="" />
			<p>类型: 
				<select name="type">
					<option value="0">取消收听</option>
					<option value="1">收听</option>
					<option value="4">加入黑名单</option>
					<option value="5">从黑名单中删除</option>
				</select>
			</p>
			<label>示例程序，请输入正确的注册用户名</label>
		</form>
		<?if(!isset($ret1)):?>
		<p>输入用户名，提交查看结果</p>
		<?else:?>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret1);
			?>
		</div>
		<?endif?>
		<p class="title">示例代码:</p>
		<div>
			<textarea class="codearea" rows="6" cols="50">
$p = array(
	'n' => 'username',
	'type' => 1
);
$ret = $c->setMyidol($p);
			</textarea>
		</div>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
		<h4>取消收听username</h4>
		<p class="title">示例代码:</p>
		<div>
			<textarea class="codearea" rows="6" cols="50">
$p = array(
	'n' => 'username',
	'type' => 0
);
$ret = $c->setMyidol($p);
			</textarea>
		</div>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
		
		<h4>加入黑名单username</h4>
		<p class="title">示例代码:</p>
		<div>
			<textarea class="codearea" rows="6" cols="50">
$p = array(
	'n' => 'username',
	'type' => 4
);
$ret = $c->setMyidol($p);
			</textarea>
		</div>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
		<h4>从黑名单删除username</h4>
		<div>
			<textarea class="codearea" rows="6" cols="50">
$p = array(
	'n' => 'username',
	'type' => 5
);
$ret = $c->setMyidol($p);
			</textarea>
		</div>
		<div>
			<p>代码返回值：</p>
			<?php
				$c->printArr($ret);
			?>
		</div>
	</div>
</body>
</html>





