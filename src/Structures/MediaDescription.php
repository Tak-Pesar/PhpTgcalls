<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls\Structures;

use FFI;

use FFI\CData;

final class MediaDescription {
	public readonly CData | null $mediaDescription;

	public function __construct(protected FFI $ffi,AudioDescription $audioDescription,VideoDescription $videoDescription){
		$this->mediaDescription = $ffi->new('ntg_media_description_struct');
		$this->mediaDescription->audio = FFI::addr($audioDescription->getCData());
		$this->mediaDescription->video = FFI::addr($videoDescription->getCData());
	}
	public function getCData() : CData | null {
		return $this->mediaDescription;
	}
}

?>