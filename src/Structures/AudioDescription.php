<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls\Structures;

use Tak\PhpTgcalls\Enums\InputMode;

use FFI;

use FFI\CData;

final class AudioDescription {
	public readonly CData | null $audioDescription;

	public function __construct(protected FFI $ffi,InputMode $inputMode,string $input,int $sampleRate,int $bitsPerSample,int $channelCount){
		$this->audioDescription = $ffi->new('ntg_audio_description_struct');
		$this->audioDescription->inputMode = $inputMode->getValue();
		$inputString = $ffi->new('char['.strlen($input).']');
		FFI::memcpy($inputString,$input,strlen($input));
		$this->audioDescription->input = $ffi->cast('char*',$inputString);
		$this->audioDescription->sampleRate = $sampleRate;
		$this->audioDescription->bitsPerSample = $bitsPerSample;
		$this->audioDescription->channelCount = $channelCount;
	}
	public function getCData() : CData | null {
		return $this->audioDescription;
	}
}

?>