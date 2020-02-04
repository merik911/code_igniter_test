<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property OrderModel order_model
 * @property TransactionModel transaction_model
 */
class Payment extends CI_Controller
{

	const ERROR_CALLBACK_INVALID_PARAMS = 'invalid_params';
	const ERROR_CALLBACK_INVALID_TRN_STATUS = 'invalid_trn_status';
	const ERROR_CALLBACK_UPDATE_STATUS_ERROR = 'update_status_error';

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('payment/index');
	}

	public function callback()
	{
		$this->load->helper('url');
		$this->load->model(OrderModel::class, 'order_model');
		$this->load->model(TransactionModel::class, 'transaction_model');
		$params = $this->getRequestParams();
		if (empty($params)) {
			$this->storeTrnError(self::ERROR_CALLBACK_INVALID_PARAMS);
			$this->returnCallbackError(self::ERROR_CALLBACK_INVALID_PARAMS);
		}
		$trnStatus = $params['transaction']['status'];
		if (!$this->transaction_model->checkStatus($trnStatus)) {
			$this->storeTrnError(self::ERROR_CALLBACK_INVALID_TRN_STATUS);
			$this->returnCallbackError(self::ERROR_CALLBACK_INVALID_TRN_STATUS);
		}
		$orderId = $params['order']['id'];
		$orderStatus = $this->transaction_model->isSuccess($trnStatus) ?
			OrderModel::STATUS_PAID : OrderModel::STATUS_DECLINED;
		$isUpdated = $this->order_model->updateStatus($orderId, $orderStatus);
		if (!$isUpdated) {
			$this->storeTrnError(self::ERROR_CALLBACK_UPDATE_STATUS_ERROR);
			$this->returnCallbackError(self::ERROR_CALLBACK_UPDATE_STATUS_ERROR);
		}
		$this->storeTrnDetails($params['transaction']);
		$this->returnCallbackResult($orderStatus);
	}

	/**
	 * @param string $error
	 */
	protected function returnCallbackError($error)
	{
		print json_encode(['success' => false, 'error' => $error, 'redirectUrl' => 'payment/error_trn']);
		exit;
	}

	/**
	 * @param string $orderStatus
	 */
	protected function returnCallbackResult($orderStatus)
	{
		$redirectUrl = $orderStatus === OrderModel::STATUS_PAID ?
			'payment/success' : 'payment/failed';
		print json_encode(['success' => true, 'error' => '', 'redirectUrl' => base_url($redirectUrl)]);
		exit;
	}

	/**
	 * @param array $details
	 */
	protected function storeTrnDetails(array $details)
	{
		$this->load->library('session');
		$this->session->set_flashdata('last_trn_details', $details);
	}

	/**
	 * @return array
	 */
	protected function getTrnDetails()
	{
		$this->load->library('session');
		return $this->session->flashdata('last_trn_details');
	}

	/**
	 * @param string $error
	 */
	protected function storeTrnError($error)
	{
		$this->load->library('session');
		$this->session->set_flashdata('last_trn_error', $error);
	}

	/**
	 * @return string
	 */
	protected function getTrnError()
	{
		$this->load->library('session');
		return $this->session->flashdata('last_trn_error');
	}

	/**
	 * @return array
	 */
	protected function getRequestParams()
	{
		$params = $this->input->post();
		if (
			empty($params) ||
			!isset($params['order']['order_id']) ||
			!isset($params['transaction']['id']) ||
			!isset($params['transaction']['status']) ||
			!isset($params['transactions'][$params['transaction']['id']])
		) {
			return [];
		}
		$userError = !empty($params['error']) ? $params['error'] : '';
		return [
			'order' => [
				'id' => $params['order']['order_id'],
			],
			'transaction' => [
				'id' => $params['transaction']['id'],
				'status' => $params['transaction']['status'],
				'userError' => $userError,
				'details' => $params['transactions'][$params['transaction']['id']]
			],
		];
	}

	/**
	 * Index Page for this controller.
	 */
	public function success()
	{
		$this->load->view('payment/success');
	}

	/**
	 * Index Page for this controller.
	 */
	public function failed()
	{

		$lastTrnDetails = $this->getTrnDetails();
		$details = [
			'trnId' => $lastTrnDetails['id'],
			'trnError' => $lastTrnDetails['userError']['recommended_message_for_user'],
		];
		$this->load->view('payment/failed', $details);
	}

	public function error()
	{
		$runtimeErrorDescriptions = [
			self::ERROR_CALLBACK_INVALID_PARAMS => 'Invalid post params.',
			self::ERROR_CALLBACK_INVALID_TRN_STATUS => 'Invalid transaction status.',
			self::ERROR_CALLBACK_UPDATE_STATUS_ERROR => 'Error while updating order status.',
		];
		$lastTrnError = $this->getTrnError();
		$error = 'Invalid transaction.';
		if (!empty($lastTrnError) && isset($runtimeErrorDescriptions[$lastTrnError])) {
			$error = $runtimeErrorDescriptions[$lastTrnError];
		}
		$this->load->view('payment/failed', ['error' => $error]);
	}

}
