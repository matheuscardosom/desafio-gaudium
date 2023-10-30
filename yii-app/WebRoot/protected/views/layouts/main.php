<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<!-- page -->
	<div class="container" id="page">
		<!-- header -->
		<div id="header">
			<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
		</div>

		<!-- mainmenu -->
		<div id="mainmenu">
			<?php $this->widget('zii.widgets.CMenu', [
				'items' => [
					['label' => 'Home', 'url' => ['/site/index']],
					['label' => 'About', 'url' => ['/site/page', 'view' => 'about']],
					['label' => 'Contact', 'url' => ['/site/contact']],
					['label' => 'Login', 'url' => ['/site/login'], 'visible' => Yii::app()->user->isGuest],
					['label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => ['/site/logout'], 'visible' => !Yii::app()->user->isGuest],
				],
			]); ?>
		</div>

		<!-- breadcrumbs -->
		<?php
		if (isset($this->breadcrumbs)) {
			$this->widget('zii.widgets.CBreadcrumbs', [
				'links' => $this->breadcrumbs,
			]);
		}
		?>

		<?php echo $content; ?>

		<div class="clear"></div>

		<!-- footer -->
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by Machine.
		</div>
	</div>
</body>
</html>