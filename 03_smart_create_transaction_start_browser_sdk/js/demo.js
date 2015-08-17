var SecucardConnect = secucardConnect.SecucardConnect;
var SecucardServices = secucardConnect.Services;

var client = null;
var smartTransactions = null;

var demo = {
	
	init: function(token, host) {
		
		console.log('Demo started');
		
		this.$transactionTypeSelect = $('#transactionType');

		this.$transactionCreateEl = $('#transactionCreate');
		console.log(this.$transactionCreateEl);
		
		this.startBtn = this.$transactionCreateEl.find('.action-start');

		console.log(this.startBtn);
		this.startBtn.click((function () {
			console.log('Start transaction!', this.startBtn, this.startBtn.attr('data-transaction'), this.$transactionTypeSelect.val());
			this.showTransactionDisplay();
			this.startTransaction(this.startBtn.data('transaction'), this.$transactionTypeSelect.val());
			this.startBtn.addClass('active');
		}).bind(this));

		this.$transactionResult = $('#transaction-result');

		this.$transactionDisplayEvents = this.$transactionResult.find('.display-events');
		this.$transactionDisplayResult = this.$transactionResult.find('.display-result');
        this.$transactionDisplayReceipt = this.$transactionResult.find('.display-receipt');
        
		var config = {};

		if (host && host != "null") {
			config.stompHost = host;
		}

		// Init client instance
		client = SecucardConnect.create(config);

		console.log('Client created');
		smartTransactions = client.getService(SecucardServices.Smart.Transactions);

		smartTransactions.on('display', (function (data) {
			
			console.log('Display event', data);
			this.$transactionDisplayEvents.JSONView(data, { collapsed: true });
			
		}).bind(this));

		if(token) {
			this.setTokenAndOpen(token);
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
        this.$transactionDisplayReceipt.empty();
		this.$transactionResult.show();
		
	},
	
	onStartTransaction: function (res) {
		console.log('Transaction started', res);
		this.$transactionDisplayResult.empty();
        this.$transactionDisplayReceipt.empty();
		this.$transactionDisplayResult.JSONView(res, { collapsed: true });
        this.$transactionDisplayReceipt.append(this.renderReciept(res.receipt));
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
	},
    
    renderReciept: function (receipt) {
        
        console.log('renderReciept', receipt);
        return receipt.map(function (item) {
            
            var cl = ["row"];
            cl.push(item.type);
            var txt;
            if(item.type == 'separator') {
                txt = '<h1>' + item.value.caption + '</h1>';
            } else if(item.type == 'textline') {
                txt = item.value.text;
                cl = cl.concat(item.value.decoration);
            } else if(item.type == 'name-value') {
                txt = '<div class="name col-md-6">' + item.value.name + ':</div>' + '<div class="value col-md-6">' + item.value.value + '</div>';
                cl = cl.concat(item.value.decoration);
            } else if(item.type == 'space') {
                txt = '&nbsp;';
            }
            
            return '<div class="'+ cl.join(" ") +'">' + (txt != undefined? txt : '') + '</div>' + (item.type == 'separator'? '<hr/>' : '');
            
        }).join('');
    }

};