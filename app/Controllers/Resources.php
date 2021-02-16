<?php namespace App\Controllers;
#
# Resources proxy script
# To load a resource file from the filesystem directly into the html
#
# Some files are output directly (images, videos), and some other has some checks before allowing to be
# downloaded (compressed template files)
#
class Resources extends BaseController {
	#
	# Get a single resource file (for output directly into the HTML DOM)
	# Params: dir (directory name), typ (resource type), res (file name)
	#
	public function get() {
		define('ITEMS_ROOT', WRITEPATH . ((substr(WRITEPATH, -1) == "/") || (substr(WRITEPATH, -1) == "\\") ? "" : "/") . "ProdItems");

		/*
		Quoted from php.net
		The superglobals $_GET and $_REQUEST are already decoded. Using urldecode() on an element in $_GET or $_REQUEST could have unexpected and dangerous results.
		*/

		# Check parameters
		$ParamFolder = $this->request->getGet('dir', FILTER_SANITIZE_STRING);
		if (empty($ParamFolder)) return NULL;

		$ParamResName = $this->request->getGet('res', FILTER_SANITIZE_STRING);
		if (empty($ParamResName)) return NULL;

		$ParamType = intval($this->request->getGet('typ', FILTER_SANITIZE_NUMBER_INT));
		if (empty($ParamType)) return NULL; # Not allowed to be "0"
		if ($ParamType == 1) { // Image (jpg)    //, jpeg, png, gif, bmp)
			if (strtoupper(substr($ParamResName, -4)) != '.JPG' &&
					1) {
				return NULL;
			}
		}
		if ($ParamType == 2) { // Video (mp4)
			if (strtoupper(substr($ParamResName, -4)) != '.MP4' &&
					1) {
				return NULL;
			}
		}


		# Build a fully-qualified path to the resource on the filesystem, and send it to the client
		$ServerSavedFilePath = ITEMS_ROOT . "/" . $ParamFolder . "/" . $ParamResName;

		# Don't send too much headers, leave them to be set globally by the server
		header('Content-type: ' . mime_content_type($ServerSavedFilePath));
		# It's advisable NOT to send the file size, especially if we use GZip on the server
		#header('Content-Length: ' . filesize($ServerSavedFilePath));

		readfile($ServerSavedFilePath);
	}
}