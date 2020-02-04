
var Payment = {

	failTrn : {
		"pay_form": {
			"token": "xxx",
			"design_name": "des1"
		},
		"transactions": {
			"1-12345": {
				"id": "1-12345",
				"operation": "pay",
				"status": "fail",
				"descriptor": "FAKE_PSP",
				"amount": 2345,
				"currency": "USD",
				"fee": {
					"amount": 0,
					"currency": "USD"
				},
				"card": {
					"bank": "CITIZENS STATE BANK",
				}
			}
		},
		"error": {
			"code": "6.01",
			"messages": [
				"Unknown decline code"
			],
			"recommended_message_for_user": "Unknown decline code"
		},
		"order": {
			"order_id": "123-12345",
			"status": "declined",
			"amount": 2345,
			"refunded_amount": 0,
			"currency": "USD",
			"marketing_amount": 2345,
			"marketing_currency": "USD",
			"processing_amount": 2345,
			"processing_currency": "USD",
			"descriptor": "FAKE_PSP",
			"fraudulent": false,
			"total_fee_amount": 0,
			"fee_currency": "USD"
		},
		"transaction": {
			"id": "1-12345",
			"operation": "pay",
			"status": "fail"
		}
	},
	successTrn : {
		"pay_form": {
			"token": "xxx",
			"design_name": "des1"
		},
		"transactions": {
			"2-12345": {
				"id": "2-12345",
				"operation": "pay",
				"status": "success",
				"descriptor": "FAKE_PSP",
				"amount": 2345,
				"currency": "USD",
				"fee": {
					"amount": 0,
					"currency": "USD"
				},
				"card": {
					"bank": "CITIZENS STATE BANK",
				}
			}
		},
		"error": {},
		"order": {
			"order_id": "123-12345",
			"status": "paid",
			"amount": 2345,
			"refunded_amount": 0,
			"currency": "USD",
			"marketing_amount": 2345,
			"marketing_currency": "USD",
			"processing_amount": 2345,
			"processing_currency": "USD",
			"descriptor": "FAKE_PSP",
			"fraudulent": false,
			"total_fee_amount": 0,
			"fee_currency": "USD"
		},
		"transaction": {
			"id": "2-12345",
			"operation": "pay",
			"status": "success"
		}
	},
	failPay : function () {
		this.pay(this.failTrn);
	},
	successPay: function () {
		this.pay(this.successTrn);
	},
	pay: function (paymentData) {
		$.ajax({
			url: '/payment/callback',
			method: 'post',
			data: paymentData,
			dataType: 'json',
		}).done(function(response) {
			window.location.replace(response.redirectUrl);
		}).error(function () {
			window.location.replace("/payment/error");
		});
	},
};

