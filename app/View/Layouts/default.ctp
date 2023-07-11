<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Message Board');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui.css');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		$module = strtolower($this->fetch('title'));
	?>
</head>
<body>
	<div id="container">
		<!-- <div id="header">
			<h1></?php echo $this->Html->link($cakeDescription, 'https://cakephp.org'); ?></h1>
		</div> -->
		<div id="content" class="<?= $module ?>">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<footer>
			<!-- <p>2023 All right reserved</p> -->
		</footer>
	</div>

	<?php
		echo $this->Html->script('plugins/jquery/dist/jquery.min.js');
		echo $this->Html->script('plugins/jquery-ui/jquery-ui.js');
		echo $this->Html->script('modules/global.js');
		echo $this->Html->script("modules/$module.js");
	?>
</body>
</html>
