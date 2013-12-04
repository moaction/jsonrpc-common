<?php

namespace Moaction\Jsonrpc\Common;

class Error
{
	const ERROR_PARSE_ERROR = -32700;
	const ERROR_INVALID_REQUEST = -32600;
	const ERROR_METHOD_NOT_FOUND = -32601;
	const ERROR_INVALID_PARAMS = -32602;
	const ERROR_INTERNAL_ERROR = -32603;
	/**
	 * @var int
	 */
	private $code;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @param int $code
	 * @param string $message
	 * @param null $data
	 */
	public function __construct($code = null, $message = null, $data = null)
	{
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
	}

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	public static function fromArray(array $data)
	{
		return new self(
			!empty($data['code']) ? (int)$data['code'] : null,
			!empty($data['message']) ? $data['message'] : null,
			!empty($data['data']) ? $data['data'] : null
		);
	}
} 