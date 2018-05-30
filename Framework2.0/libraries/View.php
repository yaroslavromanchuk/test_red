<?php
class View extends Orm_Magic {
	protected $_path = APP_DIR;

	public function setRenderPath($path) {
		$this->_path = $path;
	}
	
	public function getRenderPath() {
		return $this->_path;
	}
	
	function render($filename) {
		$result = '';
		$fn = $this->getRenderPath() .'/views/' . $filename;

		if(file_exists($fn) && !is_dir($fn)) {
			ob_start();
			include($fn);
			/*eval("?>" . @file_get_contents($fn) . "<?");*/
			
			$result = ob_get_contents();
			ob_end_clean();
		} else {
			throw new Exception("Template file $filename not found ($fn)");
		}

		return $result;
	}
	
	function show($filename) {
		echo $this->render($filename);
	}

}
