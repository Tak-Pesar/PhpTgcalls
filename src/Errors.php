<?php

declare(strict_types = 1);

namespace Tak\PhpTgcalls;

use Exception;

use FFI;

final class Errors extends Exception {
	public function __construct(FFI $ffi,int $code = 0){
		$message = match($code){
			$ffi->NTG_CONNECTION_ALREADY_EXISTS => 'Connection already exists. Please check your connection status',
			$ffi->NTG_CONNECTION_NOT_FOUND => 'Connection not found. Verify the provided connection details',
			$ffi->NTG_CRYPTO_ERROR => 'A cryptographic error occurred. Ensure keys and encryption settings are valid',
			$ffi->NTG_MISSING_FINGERPRINT => 'Fingerprint missing. Verify the security configuration',
			$ffi->NTG_SIGNALING_ERROR => 'Signaling error encountered. Check network and signaling server settings',
			$ffi->NTG_SIGNALING_UNSUPPORTED => 'Signaling protocol is unsupported. Update to a compatible version',
			$ffi->NTG_FILE_NOT_FOUND => 'File not found. Ensure the specified file path is correct',
			$ffi->NTG_ENCODER_NOT_FOUND => 'Encoder not found. Confirm encoder installation and configuration',
			$ffi->NTG_FFMPEG_NOT_FOUND => 'FFmpeg not found. Install FFmpeg and check system paths',
			$ffi->NTG_SHELL_ERROR => 'Shell error occurred. Verify command execution permissions',
			$ffi->NTG_RTMP_NEEDED => 'RTMP support required. Install necessary components',
			$ffi->NTG_INVALID_TRANSPORT => 'Invalid transport protocol specified. Verify configuration',
			$ffi->NTG_CONNECTION_FAILED => 'Connection failed. Check network settings and retry',
			$ffi->NTG_UNKNOWN_EXCEPTION => 'An unknown exception occurred. Review logs for more details',
			$ffi->NTG_INVALID_UID => 'Invalid UID provided. Ensure the UID is formatted correctly',
			$ffi->NTG_ERR_TOO_SMALL => 'Provided data size is too small. Adjust input and retry',
			$ffi->NTG_ASYNC_NOT_READY => 'Async operation not ready. Wait and retry later',
			default => 'An unspecified error occurred. Please check the logs for details',
		};
		parent::__construct($message,$code);
	}
}

?>