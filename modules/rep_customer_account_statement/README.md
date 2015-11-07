# Overview
*FrontAccounting* report displaying a customer statement in a 'bank statement' way,
i.e. doesn't take allocation into account but display a running balance instead.
It should be more straigh forward and easier for the customer to understand.
The report is also split in two parts, overdue and coming soon.


The reports looks like:

    Date	Transaction		#	  Issued		Charges		Credit |  Balance		
    2013/08/10	Invoice 10	2013/07/11  50.00				   |     -50.00
    2013/08/23	Invoice 20	2013/07/24 100.00				   |    -150.00
    2013/08/11	Payment 20										 100.00	 |     -50.00
    
    Due Soon
    2013/09/05	Invoice 20	2013/08/05  200.00				  |   -200.00
      																	  Total Balance |   -250.00
    
    																		 	*Overdue		 	|     50.00*

																		
																					



# Parameters
The report gives the statement for the current date. However, the starting point
is not the start date parameter but the more recent date when the balance was null
between the `start` and `end` date parameters. The idea is too only show the meaningful
transactions which are basicall what's coming up ,what's been unpaid and everyting since
the last unpaid or disagreement.

If you want the report from a certain date set the `start` and `end` date to this date.
Then default value, beginning of fiscal year to now should be ok in most case, bringing forward
last year balance and showing only the transaction folloiwing the first unpaid.


# No allocation ?
This report doesn't take allocation into account. If you need an 'allocation' report use the standard
*FrontAcconting* statement report. The problem with allocations is that if don't you allocate in the
exact same way as your customer did and you disagree on some payments, it's hard to explain the difference
to your customer. If I disagree on the balance with a customer, what I need to show him is the balance,
whith all the transaction, period. With the standard report, if hide allocated transactions, then transactions
are showing or not (depending how I allocated them) and then the customer starts arguing that he paid one which
is showing, and when I reallocate payments according to his view, why some new transaction are appearing
(the one I deallocated). If I send the full report, it starts from the beginning of time (there is no start date
parameter) and as it doesn't display a running balance it's hard to spot when stuff went wrong.

Let's look at the example above, obvsiously payment 20 stands for invoice 20 (amount 100.0) and the customer
has missed Invooice 10.  However, if for some reason I allocated Payment 20 to Invoice 10 (50.00) and
Invoice 20 (50.00).  The normal statement will show

    Invoice 20 100.00 allocated 50.00 oustanding 50.00

When I send the statment to the customer, the answer will be : I've paid Invoice 20, where is payment 20 ?
If I send the full statement, I get pretty much the same answer. I've paid Invoice 20 why is there 50.00 oustanding ?
(for those which look at the oustanding column).

So I reallocate Payment 20 to Invoice 10 and resend the statment

		Invoice 10 50.00 allocated 50.00 oustanding 50.00

The customer answer is, "Where does Invoice 10 come from ? It wasn't on the previous statement and I thought
we agreed that Invoice 20 was fully paid with Payment 20, therefore we are sorted and I don't owe you anything. "
With the "running balance" report such conversation should arrive anymore.


