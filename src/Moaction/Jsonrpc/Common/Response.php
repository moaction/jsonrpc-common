<?php

namespace Moaction\Jsonrpc\Common;

use Moaction\Jsonrpc\Common\Exception;

class Response
{
	/**
	 * @var mixed
	 */
	private $result;

	/**
	 * @var Error
	 */
	private $error;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @param mixed $error
	 * @return $this
	 */
	public function setError(Error $error)
	{
		$this->error = $error;

		return $this;
	}

	/**
	 * @return Error
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function hasError()
	{
		return !is_null($this->getError());
	}

	/**
	 * @param mixed $id
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $result
	 * @return $this
	 */
	public function setResult($result)
	{
		$this->result = $result;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @param array $data
	 * @throws Exception
	 * @return self
	 */
	public static function fromArray($data)
	{
		if (empty($data['jsonrpc'])) {
			throw new Exception('Response is not valid JsonRPC response: missing protocol version');
		}

		if ($data['jsonrpc'] != Request::VERSION) {
			throw new Exception('Response is not valid JsonRPC response: invalid protocol version');
		}

		$response = new self();

		if (!empty($data['error'])) {
			$response->setError(static::getErrorObject($data['error']));
		}
		else {
			if (empty($data['result'])) {
				throw new Exception('Response is not valid JsonRPC response: missing result');
			}
			$response->setResult($data['result']);
		}
		if (empty($data['id'])) {
			throw new Exception('Response is not valid JsonRPC response: missing id');
		}
		$response->setId($data['id']);

		return $response;
	}

	/**
	 * @param $data
	 * @return Error
	 */
	protected static function getErrorObject($data)
	{
		return Error::fromArray($data);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$result = array(
			'jsonrpc' => Request::VERSION,
		);

		if ($this->hasError()) {
			$result['error'] = $this->getError()->toArray();

			$errorCode = $this->getError()->getCode();
			if (!$this->getId() && ($errorCode === Error::ERROR_PARSE_ERROR || $errorCode === Error::ERROR_INVALID_REQUEST)) {
				$result['id'] = null;
			}
		}
		else {
			$result['result'] = $this->getResult();
		}

		if ($this->getId()) {
			$result['id'] = $this->getId();
		}

		return $result;
	}
}