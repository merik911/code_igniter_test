<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Payment</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="<?= base_url('/assets/js/payment.js') ?>"></script>
</head>
<body>

<div id="container">
	<button id="failed-trn" onclick="Payment.failPay()">Failed transaction</button>
	<button id="success-trn" onclick="Payment.successPay()">Success transaction</button>
</div>

</body>
</html>
