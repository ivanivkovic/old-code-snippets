<form action="" method="POST">
	Enter string for hash: <input type="text" name="pass"/><input type="submit"/>
</form>

<?php

if(isset($_POST['pass'])){
	
	echo '</br>sha1 hash for the string : ' . sha1($_POST['pass']) . '';
	
}
	
?>
