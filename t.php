<?php
echo getcwd();

echo dirname(__FILE__);

$FOLDER=(isset($_GET['rt']))?$_GET['rt']:'movie';
$PATH=dirname(__FILE__)."/$FOLDER/";

echo $PATH;
?>