<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
    <base href="<?php echo Router::url('/', true); ?>file_manager">
	<style type="text/css">
	#loading-mask{
        background-color:white;
        height:100%;
        position:absolute;
        left:0;
        top:0;
        width:100%;
        z-index:20000;
    }
    #loading{
        height:auto;
        position:absolute;
        left:45%;
        top:40%;
        padding:2px;
        z-index:20001;
    }
    #loading a {
        color:#225588;
    }
    #loading .loading-indicator{
        background:white;
        color:#444;
        font:bold 13px Helvetica, Arial, sans-serif;
        height:auto;
        margin:0;
        padding:10px;
    }
    #loading-msg {
        font-size: 10px;
        font-weight: normal;
    }
    .x-form-item-body {
        vertical-align: top;
    }
    table.x-form-item-hidden {
        height: 0 !important;
    }
    .CodeMirror-wrap {
        height: 100% !important;
    }
    .CodeMirror-activeline-background {background: #292929 !important;}
	</style>
	<?php
		echo $this->Html->meta('icon');
        echo $this->fetch('meta');

		echo $this->Html->css(array(
			//'/file_manager/js/ext-4.2.0.663/resources/css/ext-all',
			'/file_manager/js/ext-4.2.0.663/resources/ext-theme-neptune/ext-theme-neptune-all',
			//'/file_manager/css/extjs-themes/resources/css/bootstrap/bootstrap',
			//'KitchenSink-all',
            '/file_manager/js/codemirror-3.14/lib/codemirror',
            '/file_manager/js/codemirror-3.14/theme/' . Configure::read('CodeMirror.theme'),
			'/file_manager/css/app'
		));
		echo $this->fetch('css');

		echo $this->Html->script(array(
			'/file_manager/js/ext-4.2.0.663/ext-all-debug',
            '/file_manager/js/codemirror-3.14/lib/codemirror',
            '/file_manager/js/codemirror-3.14/addon/selection/active-line',
            '/file_manager/js/codemirror-3.14/mode/xml/xml',
            '/file_manager/js/codemirror-3.14/mode/javascript/javascript',
            '/file_manager/js/codemirror-3.14/mode/css/css',
            '/file_manager/js/codemirror-3.14/mode/vbscript/vbscript',
            '/file_manager/js/codemirror-3.14/mode/htmlmixed/htmlmixed',
            '/file_manager/js/codemirror-3.14/mode/clike/clike',
            '/file_manager/js/codemirror-3.14/mode/php/php',
            '/file_manager/js/codemirror-3.14/mode/markdown/markdown',
            '/file_manager/js/codemirror-3.14/mode/sql/sql',
            '/file_manager/js/codemirror-3.14/mode/properties/properties',
            '/file_manager/js/codemirror-3.14/mode/yaml/yaml',
            '/file_manager/js/md5',
			'/file_manager/js/app'
		));
        echo $this->fetch('script');
	?>
</head>
<body>
    <?php echo $this->fetch('content'); ?>
</body>
</html>
