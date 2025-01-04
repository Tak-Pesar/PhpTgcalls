<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls;

use FFI;

use function Amp\File\read;

final class Load {
	protected FFI $ffi;

	public function __construct(string | null $header = null,string | null $lib = null){
		if(is_null($header)):
			$header = __DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'ntgcalls.h';
		endif;
		if(is_null($lib)):
			$lib = __DIR__.DIRECTORY_SEPARATOR.'ntgcalls.dll';
		endif;
		$this->ffi = FFI::cdef(simplifyHeader($header),$lib);
	}
	private function simplifyHeader(string $path) : string {
		$content = read($path);
		$patterns = [
			'/#.*?$/m',
			'/\/\/.*?$/m',
			'/\/\*.*?\*\//s',
			'/NTG_C_EXPORT/',
			'/\n\s*\n/',
			'/extern\s*"C"\s*{(.*)}/s'
		];
		$replacements = [
			strval(null),
			strval(null),
			strval(null),
			strval(null),
			chr(10),
			'$1'
		];
		return preg_replace($patterns,$replacements,$content);
	}
	public function getNewAPI(int $chatid) : API {
		return new API($this->ffi,$chatid);
	}
	public function getVersion() : string {
		$versionBuffer = $this->ffi->new('char[128]');
		$this->ffi->ntg_get_version($versionBuffer,128);
		return FFI::string($versionBuffer);
	}
	public function __call(string $name,array $arguments) : mixed {
		if($class = $this->createObject('Tak\\PhpTgcalls\\Structures\\'.ucfirst($name))):
			return new $class($this->ffi,...$arguments);
		elseif($class = $this->createObject('Tak\\PhpTgcalls\\Enums\\'.ucfirst($name))):
			return new $class($this->ffi,...$arguments);
		else:
			throw new \Exception('Call to undefined function '.$name.'()');
		endif;
	}
	private function createObject(string $class) : object | false {
		return class_exists($class) ? new $class : false;
	}
}

?>