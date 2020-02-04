<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model
{

	const STATUS_DECLINED = 'declined';
	const STATUS_PAID = 'paid';

	/**
	 * @param string $orderId
	 * @param string $status
	 * @return bool
	 */
	public function updateStatus($orderId, $status)
	{
		$this->load->database();
		$orderId = $this->db->escape_str($orderId);
		if (!$this->checkStatus($status) || !$this->isOrderExists($orderId)) {
			return false;
		}
		$query = $this->db->query('
			UPDATE orders
			SET status = ?
			WHERE real_order_id = ?
		', [$status, $orderId]);
		return $query != false;
	}

	/**
	 * @param string $status
	 * @return bool
	 */
	public function checkStatus($status)
	{
		$availableStatusList = [
			self::STATUS_DECLINED,
			self::STATUS_PAID,
		];
		return in_array($status, $availableStatusList, true);
	}

	/**
	 * @param string $orderId
	 * @return bool
	 */
	public function isOrderExists($orderId)
	{
		$this->load->database();
		$query = $this->db->query('
			SELECT id
			FROM orders
			WHERE real_order_id = ?
		', [$orderId]);
		return $query->num_rows() === 1;
	}

}
