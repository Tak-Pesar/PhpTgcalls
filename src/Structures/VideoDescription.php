<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls\Structures;

use Tak\PhpTgcalls\Enums\InputMode;

use FFI;

use FFI\CData;

final class VideoDescription {
	public readonly CData | null $videoDescription;

	public function __construct(protected FFI $ffi,InputMode $inputMode,string $input,int $width,int $height,int $fps){
		$this->videoDescription = $ffi->new('ntg_video_description_struct');
		$this->videoDescription->inputMode = $inputMode->getValue();
		$inputString = $ffi->new('char['.strlen($input).']');
		FFI::memcpy($inputString,$input,strlen($input));
		$this->videoDescription->input = $ffi->cast('char*',$inputString);
		$this->videoDescription->width = $width;
		$this->videoDescription->height = $height;
		$this->videoDescription->fps = $fps;
	}
	public function getCData() : CData | null {
		return $this->videoDescription;
	}
}

?>