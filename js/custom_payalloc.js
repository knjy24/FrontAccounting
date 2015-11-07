
function focus_alloc(i) {
    save_focus(i);
	i.setAttribute('_last', get_amount(i.name));
}

function blur_alloc(i) {
		var change = get_amount(i.name);
		
		if (i.name != 'payment' && i.name != 'charge' && i.name != 'discount')
			change = Math.min(change, get_amount('maxval'+i.name.substr(6), 1))

		price_format(i.name, change, user.pdec);
		if (i.name != 'payment' && i.name != 'charge') {
			if (change<0) change = 0;
			change = change-i.getAttribute('_last');
			if (i.name == 'discount') change = -change;

			var total = get_amount('payment')+change;
			price_format('payment', total, user.pdec, 0);
		}
}

function allocate_all(doc) {
	var amount = get_amount('payment'+doc);
	var unallocated = get_amount('un_allocated'+doc);
	var total = get_amount('payment');
	var left = 0;
	total -=  (amount-unallocated);
	left -= (amount-unallocated);
	amount = unallocated;
	if(left<0) {
		total  += left;
		amount += left;
		left = 0;
	}
	price_format('payment'+doc, amount, user.pdec);
	price_format('payment', total, user.pdec);

}

function allocate_none(doc) {
	amount = get_amount('payment'+doc);
	total = get_amount('payment');
	price_format('payment'+doc, 0, user.pdec);
	price_format('payment', total-amount, user.pdec);
}

var allocations = {
	'.amount': function(e) {
 		if(e.name == 'allocated_amount' || e.name == 'bank_amount')
 		{
  		  e.onblur = function() {
			var dec = this.getAttribute("dec");
			price_format(this.name, get_amount(this.name), dec);
		  };
 		} else {
			e.onblur = function() {
				blur_alloc(this);
			};
			e.onfocus = function() {
				focus_alloc(this);
			};
		}
	}
}

Behaviour.register(allocations);
