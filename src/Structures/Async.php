<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls\Structures;

use Tak\PhpTgcalls\Errors;

use Amp\DeferredFuture;

use Amp\Cancellation;

use FFI;

use FFI\CData;

readonly class Async {
	protected DeferredFuture $deferredFuture;
	public CData | null $async;
	public CData | null $userData;
	public CData | null $errorCode;

	public function __construct(protected FFI $ffi){
		$this->deferredFuture = new DeferredFuture();
		$this->async = $ffi->new('ntg_async_struct');
		$promise = $ffi->new('ntg_async_callback');
		FFI::addr($promise)[0] = $this->callback(...);
		$this->async->promise = $ffi->cast('void(*)(void*)',$promise);
		$this->userData = $ffi->new('void*');
		$this->async->userData = FFI::addr($this->userData);
		$this->errorCode = $ffi->new('int');
		$this->async->errorCode = FFI::addr($this->errorCode);
	}
	public function callback(CData $data) : void {
		$this->deferredFuture->complete($data[0]);
	}
	public function await(? Cancellation $cancellation = null) : mixed {
		$future = $this->deferredFuture->getFuture();
		$data = $future->await($cancellation);
		if($this->errorCode[0] < 0):
			throw new Errors($this->ffi,$this->errorCode[0]);
		endif;
		return $data;
	}
	public function getCData() : CData | null {
		return $this->async;
	}
}

?>