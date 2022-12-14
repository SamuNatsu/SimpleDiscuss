<?php
namespace SimpleDiscuss;

class PathException extends \Exception {}

class Path {
	static public $rewrite = false;
	static private $_host = null;
	static private $_rawTable = [];
	static private $_urlTable = [];
	static private $_dirTable = [];

	// Register path
    static public function register(string $name, string $dir, bool $overwrite = false): void {
		$dir = realpath($dir);
		if ($dir === false)
			throw new PathException('Cannot get absolute path', 1);
		self::$_dirTable[$name] = $dir;

		self::$_rawTable[$name] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
		self::$_urlTable[$name] = (self::$_host ?? self::parseHost()) . self::$_rawTable[$name];
	}

	// Get real path url
    static public function url(string $name, string $suffix = ''): string {
		return (self::$_urlTable[$name] ?? '') . $suffix;
	}
	static public function _url(string $name, string $suffix = ''): void {
		echo self::url($name, $suffix);
	}

	// Get real path
	static public function dir(string $name, string $suffix = ''): string {
		return (self::$_dirTable[$name] ?? '') . $suffix;
	}
	static public function _dir(string $name, string $suffix = ''): void {
		echo self::dir($name, $suffix);
	}

	// Get virtual path url for router
	static public function vurl(string $url): string {
		if (self::$rewrite)
			return (self::$_host ?? self::parseHost()) . $url;
		else 
			return (self::$_host ?? self::parseHost()) . '?' . $url;
	}
	static public function _vurl(string $url): void {
		echo self::vurl($url);
	}

	// Parse host
	static private function parseHost(): string {
		self::$_host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://');
		self::$_host .= $_SERVER['HTTP_HOST'];
		if (($_SERVER['SERVER_PORT'] == '80' && self::$_host[4] != ':') ||
			($_SERVER['SERVER_PORT'] == '443' && self::$_host[4] != 's') ||
			($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443'))
			self::$_host .= ':' . $_SERVER['SERVER_PORT'];
		return self::$_host;
	}

}
