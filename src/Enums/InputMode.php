<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls\Enums;

use FFI;

final class InputMode {
	public function __construct(protected FFI $ffi,public readonly string $inputMode){
	}
	public function getValue() : int {
		return match(strtoupper($this->inputMode)){
			'FILE' => $this->ffi->NTG_FILE,
			'SHELL' => $this->ffi->NTG_SHELL,
			'FFMPEG' => $this->ffi->NTG_FFMPEG,
			'NO_LATENCY' => $this->ffi->NTG_NO_LATENCY,
			default => throw new \InvalidArgumentException('The desired inputMode was not found !')
		};
	}
}

?>