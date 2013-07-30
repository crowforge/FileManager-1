<?php

//echo $this->Js->Object($files);

$json = array();
foreach ($files[0] as $dir) {
	$json[] = "{\n
		\t\"text\": \"$dir\",
		\t\"id\": \"$path/$dir\",
		\t\"cls\": \"folder\"
	\n}";
}

foreach ($files[1] as $file) {
	$json[] = "{\n
		\t\"text\": \"$file\",
		\n\t\"leaf\": \"true\",
		\t\"id\": \"$path/$file\",
		\t\"cls\": \"file\",
		\t\"qtip:\": \"test\"
	\n}";
}

echo '[' . implode($json, ',') . ']';