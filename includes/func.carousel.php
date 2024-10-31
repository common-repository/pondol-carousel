<?php
function read_dir($dirList){
	$open_dir = opendir($dirList);
	$tmpl = array();
	while($opendir = readdir($open_dir)) {
		if(($opendir != ".") && ($opendir != "..") ) {
			array_push($tmpl, array("dir"=>$dirList.$opendir, "name"=>$opendir));
		}
	}
	closedir($open_dir);
	return $tmpl;
}
