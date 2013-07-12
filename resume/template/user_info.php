<?php
$PAGE_CLASS .= 'page-user-info';
$PAGE_HEAD .= css('user.css');
include 'header.inc.php';
include 'usernav.inc.php';
?>
<div class="clearfix">
	<div class="left-col">
		<?php
		echo form(url('user/modify'), array(
			'name' => array(
				'label' => '姓名',
				'value' => '奥爸爸',
				'class' => 'txt'
			),
			'email' => array(
				'label' => '邮箱',
				'value' => 'xasdf@ac.com',
				'class' => 'txt'
			),
			'phone' => array(
				'label' => '手机',
				'class' => 'txt'
			),
			'min' => array(
				'label' => '名族',
				'class' => 'txt'
			),
			'loc' => array(
				'label' => '籍贯',
				'class' => 'txt'
			),
			'address' => array(
				'label' => '现居住',
				'class' => 'txt'
			),
			'edu' => array(
				'label' => '最高学历',
				'value' => 'bk',
				'type' => 'select',
				'options' => array(
					'xx' => 'xiaoxue',
					'bk' => '本科'
				)
			),
			'height' => array(
				'label' => '身高',
				'item_tpl' => '<input type="text" name="" id="" class="txt s-txt" /> 厘米'
			),
			'phone' => array(
				'label' => '手机',
				'class' => 'txt'
			),
			'birth' => array(
				'label' => '出生日期',
				'class' => 'txt',
				'item_tpl' => '<select size="1"><option value="">1988年</option></select>'.
							'<select size="1"><option value="">12月</option></select>'.
							'<select size="1"><option value="">19日</option></select>'
			),
			'marry' => array(
				'label' => '婚姻状况',
				'type' => 'radio',
				'class' => 'radio',
				'options' => array(
					'asdf' => '已婚',
					'asdxf' => '未婚',
					'asdfc' => '保密',
				)
			),


			'submit' => array(
				'value' => '保存修改',
				'type' => 'submit',
				'class' => 'btn'
			)
		), array(
			'class' => 'frm user-info-frm'
		));
		?>
	</div>
	<div class="right-col">
		<div class="avatar">
			<a href="<?php echo img_url('demo-avatar.jpg')?>" target="_blank"><?php echo img('demo-avatar.jpg')?></a>
			<p>
				<input type="button" value="重设头像" class="btn" />
			</p>
		</div>
	</div>
</div>
<?php include 'footer.inc.php'?>