var SecucardConnect = secucardConnect.SecucardConnect;
var SecucardServices = secucardConnect.Services;

var client = SecucardConnect.create();
var smartTransactions = client.getService(SecucardServices.Smart.Transactions);

var demo = {
	
	init: function(token) {
		
		console.log('Demo started');
		
		this.$transactionTypeSelect = $('#transactionType');
		
		this.$transactionCreateEl = $('#transactionCreate');
		console.log(this.$transactionCreateEl);
		
		this.startBtn = this.$transactionCreateEl.find('.action-start');
		this.startBtn.click((function () {
			console.log('Start transaction!', this.startBtn, this.startBtn.attr('data-transaction'), this.$transactionTypeSelect.val());
			this.showTransactionDisplay();
			this.startTransaction(this.startBtn.data('transaction'), this.$transactionTypeSelect.val());
			this.startBtn.addClass('active');
		}).bind(this));
		
		this.$transactionResult = $('#transaction-result');
		
		this.$transactionDisplayEvents = this.$transactionResult.find('.display-events');
		this.$transactionDisplayResult = this.$transactionResult.find('.display-result');
		
		smartTransactions.on('display', (function (data) {
			
			console.log('Display event', data);
			this.$transactionDisplayEvents.JSONView(data, { collapsed: true });
			
		}).bind(this));
		
		if(token) {
			this.setTokenAndOpen(token.access_token);
		}
		
	},
	
	onOpened: function () {
		console.log('Demo connected');
	},

	onConnectionError: function (err) {
		
		console.log('Demo connection error', err);
		
	},

	setTokenAndOpen: function (token) {

		var credentials = {
			token: {
				access_token: token,
				expires_in: 1200,
				token_type: 'bearer',
				scope: 'https://scope.secucard.com/e/api'
			}
		};

		client.setCredentials(credentials);
		return client.open().then(this.onOpened.bind(this)).catch(this.onConnectionError.bind(this));

	},
	
	/*
	onClosed: function () {
		
	},
	
	close: function () {
		client.close().then(this.onClosed.bind(this));
	}
	*/
	
	showTransactionDisplay: function () {
		
		this.$transactionDisplayResult.empty();
		this.$transactionDisplayEvents.empty();
		this.$transactionResult.show();
		
	},
	
	onStartTransaction: function (res) {
		console.log('Transaction started', res);
		this.$transactionDisplayResult.empty();
		this.$transactionDisplayResult.JSONView(res, { collapsed: true });
		this.startBtn.removeClass('active');
	},
	
	onStartTransactionError: function (err) {
		console.log('Start transaction error', err);
		this.$transactionDisplayResult.empty();
		this.$transactionDisplayResult.append('<span class="text-danger">'+ 'Error: ' + err.error +'</span>');
		this.startBtn.removeClass('active');
	},
	
	startTransaction: function (transactionId, type) {
		smartTransactions.start(transactionId, type)
			.then(this.onStartTransaction.bind(this))
			.catch(this.onStartTransactionError.bind(this));
	}

};