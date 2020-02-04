<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionModel extends CI_Model
{

	const STATUS_FAIL = 'fail';
	const STATUS_SUCCESS = 'success';

	/**
	 * @param string $status
	 * @return bool
	 */
	public function checkStatus($status)
	{
		$availableStatusList = [
			self::STATUS_FAIL,
			self::STATUS_SUCCESS,
		];
		return in_array($status, $availableStatusList, true);
	}

	/**
	 * @param string $status
	 * @return bool
	 */
	public function isSuccess($status)
	{
		return $status === self::STATUS_SUCCESS;
	}

}
