<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls;

use Tak\PhpTgcalls\Structures\Async;

use Tak\PhpTgcalls\Structures\MediaDescription;

use FFI;

use FFI\CData;

use Stringable;

final class API implements Stringable {
	private int $uid;

	public function __construct(protected FFI $ffi,public readonly int $chatid){
		$this->uid = $ffi->ntg_init();
	}
	public function create(MediaDescription $mediaDescription) : object {
		$buffer = $this->ffi->new('char[1024]');
		$anonymous = new class($this->ffi,$buffer) extends Async implements Stringable {
			public function __construct(protected FFI $ffi,private CData $buffer){
				parent::__construct($ffi);
			}
			public function __toString() : string {
				return FFI::string($this->buffer);
			}
		};
		$this->ffi->ntg_create($this->uid,$this->chatid,$mediaDescription->getCData(),$buffer,1024,$anonymous->getCData());
		return $anonymous;
	}
	public function connect(string $data) : object {
		$async = new Async($this->ffi);
		$this->ffi->ntg_connect($this->uid,$this->chatid,$data,$async->getCData());
		return $async;
	}
	public function getVersion() : string {
		$versionBuffer = $this->ffi->new('char[128]');
		$this->ffi->ntg_get_version($versionBuffer,128);
		return FFI::string($versionBuffer);
	}
	public function __toString() : string {
		return strval($this->uid);
	}
	public function __destruct(){
		$this->ffi->ntg_destroy($this->uid);
	}
}

?>