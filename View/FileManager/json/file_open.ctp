<?php
function get_mime($filename, $mode = 0) {

    // mode 0 = full check
    // mode 1 = extension check only

    $mime_types = array(

        'txt' => 'text/plain',
        'md' => 'text/x-markdown',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'application/x-httpd-php',
        'ctp' => 'application/x-httpd-php',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'properties' => 'text/x-properties',
        'ini' => 'text/x-properties',
        'yaml' => 'text/x-yaml',
        'yml' => 'text/x-yaml',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);

	$info = pathinfo($filename);
	if (isset($info['extension']) && array_key_exists($info['extension'], $mime_types)) {
		return $mime_types[$info['extension']];
	}

	return 'application/octet-stream';

}
?>[{
	xtype: 'form',
	layout: 'fit',
	autoScroll: true,
	fbar: [{
		//id: 'pinfo_<?php echo md5($node); ?>',
		xtype: 'tbtext',
		text: '<?php echo $node; ?>'
	}, '->', {
		id: 'linfo_<?php echo md5($node); ?>',
		xtype: 'tbtext',
		text: 'Line, 1 Column 1'
	}, '-', {
		id: 'progressbar_<?php echo md5($node); ?>',
		xtype: 'progressbar',
		width: 150
	}],
	items: [{
		xtype: 'hiddenfield',
		name: 'path',
		value: '<?php echo $node; ?>'
	}, {
		id: 'textarea_<?php echo md5($node); ?>',
		name: 'content',
		xtype: 'textareafield',
		value: <?php echo str_replace('\'', '\\\'', json_encode($content)); ?>
	}],
	editor: [],
	listeners: {
		afterrender: function(obj, container, pos, eOpts) {
			var t = document.getElementById('textarea_<?php echo md5($node); ?>-inputEl');
			obj.editor = CodeMirror.fromTextArea(t, {
				theme: '<?php echo Configure::read('CodeMirror.theme'); ?>',
				autofocus: <?php echo Configure::read('CodeMirror.autofocus'); ?>,
				lineNumbers: <?php echo Configure::read('CodeMirror.lineNumbers'); ?>,
				lineWrapping: <?php echo Configure::read('CodeMirror.lineWrapping'); ?>,
				styleActiveLine: true,
				matchBrackets: <?php echo Configure::read('CodeMirror.matchBrackets'); ?>,
				mode: '<?php echo get_mime($node); ?>', //'application/x-httpd-php',
				readOnly: <?php echo $is_writable == true ? 0: 1; ?>,
				extraKeys: {
					'Ctrl-S': function() {
						obj.save();
					},
					'Ctrl-Z': function(cm) {
						cm.undo();
					},
					'Ctrl-Y': function(cm) {
						cm.redo();
					}
				}
			});
			obj.editor.on('cursorActivity', function(cm) {
				var pos = cm.getCursor();
				line = pos.line + 1;
				column = pos.ch + 1;
				Ext.getCmp('linfo_<?php echo md5($node); ?>').setText('Line ' + line + ', column ' + column);
			});
		}
	},
	save: function() {
		Ext.getCmp('progressbar_<?php echo md5($node); ?>').wait({
			interval: 100,
			text: 'Saving...'
		});
		this.editor.save();
		this.getForm().submit({
			url: 'file_manager/file_manager/file_save',
			success: function() {
				//Ext.getCmp('progressbar_<?php echo md5($node); ?>').reset().updateText('Done');
				Ext.getCmp('progressbar_<?php echo md5($node); ?>').reset().updateProgress(100, 'File Saved');
			}
		});
	}
}]