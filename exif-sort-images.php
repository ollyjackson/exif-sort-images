#!/usr/bin/php
<?php

// slice from index 3 to skip "." and ".." elements
$dirs = array_slice(scandir(dirname(__FILE__)), 3);

foreach ($dirs as $dir) {
	echo "Scanning directory: " . $dir . "\n";

	$photos = array_slice(scandir($dir),2);

	$years = array();

	foreach ($photos as $photo) {
		// reset $output as exec() concatenates
		$output = array();

		// this is horrible and could likely be done natively in PHP
		exec("exiftool -DateTimeOriginal \"" . dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $photo . "\" | cut -d \":\" -f 2", $output);

		// still have a leading space, trim it off
		$output[0] = trim($output[0]);

		// got this year already?
		if (!in_array($output[0], $years)) {
			$years[] = $output[0];
		}
	}

	// only a single year? boom! append to the directory name
	if (count($years) == 1) {
		echo "Single year found " . $years[0] . ". Renaming dir\n";

		rename(dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir, dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . " - " . $years[0]);
	}
}

echo "Done!\n";