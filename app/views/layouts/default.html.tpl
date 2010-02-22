<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo htmlspecialchars($rox_page_title); ?></title>
	<?php echo $html->favicon(); ?>
	<?php echo $html->css('rox'); ?>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<p>
				<a href="<?php echo Rox_Router::url('/'); ?>">
				<?php echo $html->image('rox_logo.png', 'RoxPHP'); ?></a>
			</p>
		</div>
		<div id="container">
			<?php foreach ($this->getFlashMessages() as $type => $message) : ?>
				<div class="flash-message <?php echo $type ?>-flash-message">
					<?php echo htmlspecialchars($message); ?>
				</div>
			<?php endforeach; ?>
			<?php echo $rox_layout_content; ?>
		</div>
		<div id="footer">
			<p>Powered by <a href="http://roxphp.com">RoxPHP</a></p>
		</div>
	</div>
</body>
</html>