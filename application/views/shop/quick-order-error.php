<?php
if(isset($this->errors['error'])) {
	foreach ($this->errors['error'] as $error)
	echo '<span style="color: red;">' . $error . '</span><br />';
}elseif(isset($this->error_email)){
	echo '<span style="color: red;">' . $this->error_email . '</span><br /><br />';
	} ?>

