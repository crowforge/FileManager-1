<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

App::import('FileManagerAppController', 'FileManager\Controller');

class FileManagerController extends FileManagerAppController {

	public $layout = 'minimal';

	public function index() {
		if (!isset($this->request->params['ext'])) {
			$this->layout = 'default';
		}

		$path = ROOT;
		if (isset($_GET['node']) && !empty($_GET['node']) && $_GET['node'] != 'root') {
			$path = $_GET['node'];
		}

		$dir = new Folder($path);
		$files = $dir->read();
		$path = str_replace('\\', '/', $path);
		$this->set(compact('files', 'path'));
	}

	public function directory_size() {
		$folder = new Folder($_GET['node']);
		$this->set('dirsize', $folder->dirsize());
	}

	public function directory_create() {
		if (!empty($this->request->data['path']) && !empty($this->request->data['name'])) {
			if ($this->request->data['path'] == 'root') {
				$this->request->data['path'] = ROOT;
			}
			if (!is_dir($this->request->data['path'])) {
				$this->request->data['path'] = dirname($this->request->data['path']);
			}
			$folder = new Folder($this->request->data['path'] . DS . $this->request->data['name'], true, 0777);
			if ($folder) {
				$response = array(
					'success' => true,
					'message' => 'New folder has been created'
				);
			} else {
				$response = array(
					'success' => true,
					'message' => 'New folder cannot be created'
				);
			}
		}

		$this->set(compact('response'));
	}

	public function directory_delete() {
		if (!empty($this->request->data)) {
			$folder = new Folder($this->request->data['path']);
			if ($folder->delete()) {
				$response = array(
					'success' => true,
					'message' => 'Folder has been deleted'
				);
			} else {
				$response = array(
					'success' => false,
					'message' => 'Folder cannot be deleted'
				);
			}
		}

		$this->set(compact('response'));
	}

/*
 * rename — Renames a file or directory
*/
	public function rename() {
		if (!empty($this->request->data['path']) && !empty($this->request->data['name'])) {
			$dir = dirname($this->request->data['path']);
			if (rename($this->request->data['path'], $dir . DS . $this->request->data['name'])) {
				$response = array(
					'success' => true,
					'message' => is_dir($this->request->data['path']) ? 'Folder has been renamed' : 'File has been renamed'
				);
			} else {
				$response = array(
					'success' => true,
					'message' => is_dir($this->request->data['path']) ? 'Folder cannot be renamed' : 'File cannot be renamed'
				);
			}
		}

		$this->set(compact('response'));
	}

	public function file_info() {
		$file = new File($_GET['node']);
		$this->set('info', $file->info());
	}

	public function file_create() {
		if (!empty($this->request->data['path']) && !empty($this->request->data['name'])) {
			if ($this->request->data['path'] == 'root') {
				$this->request->data['path'] = ROOT;
			}
			if (!is_dir($this->request->data['path'])) {
				$this->request->data['path'] = dirname($this->request->data['path']);
			}
			$file = new File($this->request->data['path'] . DS . $this->request->data['name'], true, 0777);
			if ($file->exists()) {
				$response = array(
					'success' => true,
					'message' => 'New file has been created'
				);
			} else {
				$response = array(
					'success' => true,
					'message' => 'New file cannot be created'
				);
			}
		}

		$this->set(compact('response'));
	}

	public function file_open() {
		$node = $content = '';
		if (!empty($this->request->data['node'])) {
			$node = $this->request->data['node'];
			$file = new File($node);
			$is_writable = $file->writable();

			if ($file->exists() && $file->readable()) {
				$content = $file->read();
			}
		}

		$this->set(compact('is_writable', 'content', 'node'));
	}

	public function file_save() {
		if (!empty($_POST)) {
			//debug($_POST);
			$file = new File($_POST['path']);
			if ($file->exists()) {
				if ($file->writable()) {
					if ($file->write($_POST['content'])) {
						$response = array(
							'success' => true,
							'message' => 'File has been saved'
						);
					}
				} else {
					$response = array(
						'success' => false,
						'message' => 'File is not writable'
					);
				}
			} else {
				$response = array(
					'success' => false,
					'message' => 'File does not exists'
				);
			}
		}

		$this->set(compact('response'));
	}

	public function file_delete() {
		if (!empty($this->request->data)) {
			$file = new File($this->request->data['path']);
			if ($file->exists()) {
				if ($file->delete()) {
					$response = array(
						'success' => true,
						'message' => 'File has been deleted'
					);
				} else {
					$response = array(
						'success' => false,
						'message' => 'File cannot be deleted'
					);
				}
			} else {
				$response = array(
					'success' => false,
					'message' => 'File does not exists'
				);
			}
		}

		$this->set(compact('response'));
	}

	public function upload() {}

}