var SessionId = "", RowsPerPageInListViews = 20, CurrentAccountId = "", CurrentContactId = "", CurrentSupplierContactId = "", CurrentQuotationId = "", CurrentSalesOrderId = "", CurrentPurchaseOrderId = "", CurrentInvoiceId = "", CurrentProductId = "", CurrentSupplierId = "", AccountListNextOffset = 0, AccountListPrevOffset = 0, AccountListCurrentOffset = 0, ContactListNextOffset = 0, ContactListPrevOffset = 0, ContactListCurrentOffset = 0, QuotationListNextOffset = 0, QuotationListPrevOffset = 0, QuotationListCurrentOffset = 0, SalesOrderListNextOffset = 0, SalesOrderListPrevOffset = 0, SalesOrderListCurrentOffset = 0, PurchaseOrderListNextOffset = 0, PurchaseOrderListPrevOffset = 0, PurchaseOrderListCurrentOffset = 0, InvoiceListNextOffset = 0, InvoiceListPrevOffset = 0, InvoiceListCurrentOffset = 0, ProductListNextOffset = 0, ProductListPrevOffset = 0, ProductListCurrentOffset = 0, SupplierListNextOffset = 0, SupplierListPrevOffset = 0, SupplierListCurrentOffset = 0;
var CompanyIndex = "0";

$("#HomePage").live("pagecreate", function() {
	$(".clsIcon a").attr("class", "clsIconLink")
});
$("#AccountListPage").live("pageshow", function() {
	FaGetAccountListFromServer(AccountListCurrentOffset)
});
$("#ContactListPage").live("pageshow", function() {
	FaGetContactListFromServer(ContactListCurrentOffset)
});
$("##QuotationListPage").live("pageshow", function() {
	FaGetQuotationListFromServer(QuotationListCurrentOffset)
});
$("#SalesOrderListPage").live("pageshow", function() {
	FaGetSalesOrderListFromServer(SalesOrderListCurrentOffset)
});
$("#PurchaseOrderListPage").live("pageshow", function() {
	FaGetPurchaseOrderListFromServer(PurchaseOrderListCurrentOffset)
});
$("#InvoiceListPage").live("pageshow", function() {
	FaGetInvoiceListFromServer(InvoiceListCurrentOffset)
});
$("#ProductListPage").live("pageshow", function() {
	FaGetProductListFromServer(ProductListCurrentOffset)
});
$("#SupplierListPage").live("pageshow", function() {
	FaGetSupplierListFromServer(SupplierListCurrentOffset)
});
$("#HomePage").live("pageshow", function() {
	SessionId === "" && $.mobile.changePage("#LoginPage")
});
function Login() {
	$.mobile.showPageLoadingMsg();
	CompanyIndex = $("#company").val();
	var _username = $("#username").val(); 
	var _password = $("#pswd").val();
	var _module = 'USER';
	var _method = 'login';
	var _company = CompanyIndex;
	
	$.post("../ws/api.php", {
		module  : _module,
		method  : _method,
		company : _company,
		username: _username,
		password: _password,
		input_type : "JSON",
		response_type : "JSON"
	}, function(rest_result) {
		
		if (rest_result !== "") {
			rest_result = jQuery.parseJSON(rest_result);			
			if (rest_result.success !== undefined && rest_result.success === false)
				alert(rest_result.message);
			else {				
				SessionId = rest_result.success;
				$("#username").val("");
				$("#pswd").val("");
				$.mobile.changePage("#HomePage")
			}
		} else
			alert("An error occurred during logging in.");
		$.mobile.hidePageLoadingMsg()
	})
}
window.onbeforeunload = function() {
	$.get("../ws/api.php", {
		module : 'USER',
		method : "logout",
		input_type : "JSON",
		response_type : "JSON"
	})
};
function LogOut() {
	$.get("../ws/api.php", {
		module : 'USER',
		method : "logout",
		input_type : "JSON",
		response_type : "JSON"
	}, function() {
		$.mobile.changePage("#LoginPage", {
			reverse : "true"
		})
	})
}

function FaGetAccountListFromServer(a) {
	if ($("#AllAccountListDiv li").length === 0 || AccountListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		AccountListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'CUSTOMER';
		var _method = 'get_all_account';
		var _company = CompanyIndex;
		var _param1 = '';
		
		$.get("../ws/api.php",
						{
							module  : _module,
							method  : _method,
							company : _company,							
							param1  : _param1,
							pgIndex : AccountListCurrentOffset,
							recordPerPage : RowsPerPageInListViews,
							input_type : "JSON",
							response_type : "JSON"
						},
				function(c) {
					if (c !== undefined) {
						c = jQuery.parseJSON(c);
								
						if (c !== undefined && c.results !== undefined) {
							if (c.recordPerPage === 0)
								AccountListCurrentOffset = AccountListPrevOffset + RowsPerPageInListViews;
							else if (c.pgIndex === 0)
								AccountListCurrentOffset = 0;
							if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
								alert("No more records");
							else {
								$("#AllAccountListDiv li").remove();
								var b = 0;
								for (b = 0; b <= c.results.length; b++)
									if (c.results[b] !== undefined) {
										var d = c.results[b], f = $("<li/>"), e = "<h4>"
												+ d.name
												+ "</h4>", g = "<p>"
												+ d.address												
												+ "</p>";
										d = $(
												"<a/>",
												{
													href : "#",
													"data-identity" : d.debtor_no,
													click : function() {
														CurrentAccountId = $(this).data("identity");
														$.mobile.changePage("#ViewAccountDetailsPage");
														$.mobile.showPageLoadingMsg();
														FaGetAccountDetails()
													}
												});
										d.append(e);
										d.append(g);
										f.append(d);
										$("#AllAccountListDiv").append(f)
									}
								$("#AllAccountListDiv").listview("refresh");
								AccountListNextOffset = c.pgIndex;
								AccountListPrevOffset = a - RowsPerPageInListViews
								
								if (AccountListPrevOffset < 0)
									AccountListPrevOffset = 0;
							}
						}
					}
					$.mobile.hidePageLoadingMsg()
				})
	}
}
function FaGetAccountDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewAccountDetailsPageDetailsList li").remove();
	$("#TxtAccountName").html("");
	$("#TxtAccountDescription").html("");
	var _module = 'CUSTOMER';
	var _method = 'get_account_by_id';
	var _company = CompanyIndex;
	var _param1 = CurrentAccountId;
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,							
						param1  : _param1,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						if (a !== undefined) {
							a = jQuery.parseJSON(a);
							
							if (a !== undefined && a.results !== undefined)
								if (a.results[0] !== undefined) {
									account = a.results[0];
									$("#TxtAccountName").html(account.name);
									$("#TxtAccountDescription").html(account.notes);
									$("#ViewAccountDetailsPageDetailsList").append('<li data-role="list-divider">Account Overview</li>');

									if (account.sales_type !== undefined && account.sales_type !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ account.sales_type
												+ "</h4>";
										a.append("<p><br />Sales Type</p>");
										a.append(c);
										$("#ViewAccountDetailsPageDetailsList").append(a)
									}
									
									a = $("<li/>");
									c = "<h4>"
											+ account.curr_code
											+ "</h4>";
									
									a.append("<p><br />Currency Code</p>");
									a.append(c);
									$("#ViewAccountDetailsPageDetailsList").append(a)
									
									if (account.reason_description !== undefined && account.reason_description !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ account.reason_description
												+ "</h4>";
										a.append("<p><br />Credit Status</p>");
										a.append(c);
										$("#ViewAccountDetailsPageDetailsList").append(a)
									}
									
									if (account.credit_limit !== undefined && account.credit_limit !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ CurrencyFormatted(account.credit_limit)
												+ "</h4>";
										a.append("<p><br />Credit Limit</p>");
										a.append(c);
										$("#ViewAccountDetailsPageDetailsList").append(a)
									}
									
									if (account.terms !== undefined && account.terms !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ account.terms
												+ "</h4>";
										a.append("<p><br />Terms</p>");
										a.append(c);
										$("#ViewAccountDetailsPageDetailsList").append(a)
									}
									
									if (account.address !== undefined) {
										f = account.address;
										d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
										a = $("<li/>");
										f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
										d = $(
												"<a/>",
												{
													href : d,
													rel : "external",
													target : "_new",
													style : "text-decoration:none;color:#444;"
												});
										d
												.append("<p><br />Address</p>");
										d.append(f);
										a.append(d);
										$("#ViewAccountDetailsPageDetailsList").append(a)
									}
								}
						}
						$("#ViewAccountDetailsPageDetailsList").listview("refresh")
					});
	
	$("#ViewAccountDetailsPageContactListUl li").remove();
	
	var _module = 'CONTACT';
	var _method = 'get_contact_by_account';
	var _company = CompanyIndex;
	var _param1 = CurrentAccountId;
	var _param2 = 'customer';
	var _param3 = '';
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,							
						param1  : _param1,
						param2  : _param2,
						param3  : _param3,
						pgIndex : ContactListCurrentOffset,
						recordPerPage : RowsPerPageInListViews,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						$("#ViewAccountDetailsPageContactListUl").append('<li data-role="list-divider">Contact</li>');
						if (a !== undefined) {
							a = jQuery.parseJSON(a);
							
							if (a !== undefined && a.results !== undefined)
								if (a.results.length > 0) {
									var c = 0;
									for (c = 0; c <= a.results.length; c++)
										if (a.results[c] !== undefined) {
											var b = a.results[c], d = $("<li/>"), f = "<h4>"
													+ b.name
													+ "&nbsp;"
													+ b.name2
													+ "</h4>", e = "<p>"
													+ b.email
													+ "</p>";
											b = $(
													"<a/>",
													{
														href : "#",
														"data-identity" : b.id,
														click : function() {
															CurrentContactId = $(this).data("identity");
															$.mobile.changePage("#ViewContactDetailsPage");
															$.mobile.showPageLoadingMsg();
															FaGetContactDetails()
														}
													});
											b.append(f);
											b.append(e);
											d.append(b);
											$("#ViewAccountDetailsPageContactListUl").append(d)
										}
								} else {
									a = $("<li/>");
									a.append("<h4>No Data</h4>");
									$("#ViewAccountDetailsPageContactListUl").append(a)
								}
							$("#ViewAccountDetailsPageContactListUl").listview("refresh")
						}
						$.mobile.hidePageLoadingMsg()
					});
}

function FaGetContactListFromServer(a) {
	if ($("#AllContactListDiv li").length === 0
			|| ContactListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		ContactListCurrentOffset = a;
		SessionId == "" && $.mobile.changePage("#HomePage");
		
		var _module = 'CONTACT';
		var _method = 'get_all_contact';
		var _company = CompanyIndex;
		var _param1 = '';
		var _param2 = 'customer';
		var _param3 = '';
		
		$.get("../ws/api.php",
						{
							module  : _module,
							method  : _method,
							company : _company,							
							param1  : _param1,
							param2  : _param2,
							param3  : _param3,
							pgIndex : ContactListCurrentOffset,
							recordPerPage : RowsPerPageInListViews,
							input_type : "JSON",
							response_type : "JSON"
						},
						function(c) {
							if (c != undefined) {
								c = jQuery.parseJSON(c);
								
								if (c != undefined && c.results != undefined) {
									if (c.recordPerPage === 0)
										ContactListCurrentOffset = ContactListPrevOffset + RowsPerPageInListViews;
									else if (c.pgIndex === 0)
										ContactListCurrentOffset = 0;
									if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
										alert("No more records");
									else {
										$("#AllContactListDiv li").remove();
										var b = 0;
										for (b = 0; b <= c.results.length; b++)
											if (c.results[b] != undefined) {
												var d = c.results[b], f = $("<li/>"), e = "<h4>"
														+ d.name
														+ "&nbsp;"
														+ d.name2
														+ "</h4>", g = d.email;
												if (d.name != undefined)
													g += " at "
															+ d.customer_name;
												g = "<p>" + g + "</p>";
												d = $(
														"<a/>",
														{
															href : "#",
															"data-identity" : d.id,
															click : function() {
																CurrentContactId = $(this).data("identity");
																$.mobile.changePage("#ViewContactDetailsPage");
																$.mobile.showPageLoadingMsg();
																FaGetContactDetails()
															}
														});
												d.append(e);
												d.append(g);
												f.append(d);
												$("#AllContactListDiv").append(f)
											}
										$("#AllContactListDiv").listview("refresh");
										ContactListNextOffset = c.pgIndex;
										ContactListPrevOffset = a - RowsPerPageInListViews;
										
										if (ContactListPrevOffset < 0)
											ContactListPrevOffset = 0;
									}
								}
							}
							$.mobile.hidePageLoadingMsg()
						})
	}
}
function FaGetContactDetails() {
	$("#TxtContactName").html("");
	$("#TxtCustomerName").html("");
	$("#ViewContactDetailsPageDetailsList li").remove();
	SessionId == "" && $.mobile.changePage("#HomePage");
	var _module = 'CONTACT';
	var _method = 'get_contact_by_id';
	var _company = CompanyIndex;
	var _param1 = CurrentContactId;
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,							
						param1  : _param1,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						if (a != undefined) {
							a = jQuery.parseJSON(a);
							
							if (a != undefined && a.results != undefined)
								if (a.results[0] != undefined) {
									a = a.results[0];
									$("#TxtContactName").html(a.name + "&nbsp;" + a.name2);
									var c = a.customer_name;								
									$("#TxtCustomerName").html(c);
									$("#ViewContactDetailsPageDetailsList").append('<li data-role="list-divider">Contact Information</li>');
									if (a.email !== undefined && a.email !== "") {
										c = $("<li/>");
										d = "<h4>"
												+ a.email
												+ "</h4>";
										f = $("<a/>",
												{
													href : "mailto:"
															+ a.email,
													rel : "external",
													style : "text-decoration:none;color:#444;"
												});
										f.append("<p><br />Email</p>");
										f.append(d);
										c.append(f);
										$("#ViewContactDetailsPageDetailsList").append(c)
									}
									if (a.phone !== undefined && a.phone !== "") {
										c = $("<li/>");
										var b = a.phone
												.replace("(", "");
										b = b.replace(")", "");
										b = b.replace(" ", "");
										b = b.replace("-", "");
										if (a.phone !== undefined) {
											var d = "<h4>"
													+ a.phone
													+ "</h4>", f = $(
													"<a/>",
													{
														href : "tel:+1" + b,
														rel : "external",
														style : "text-decoration:none;color:#444;"
													});
											f
													.append("<p><br />Office Phone</p>");
											f.append(d);
											c.append(f)
										}
										$("#ViewContactDetailsPageDetailsList").append(c)
									}
									if (a.phone2 !== undefined && a.phone2 !== "") {
										c = $("<li/>");
										d = a.phone2
												.replace("(", "");
										b.replace(")", "");
										b.replace(" ", "");
										d = b.replace("-", "");
										b = "<h4>"
												+ a.phone2
												+ "</h4>";
										d = $(
												"<a/>",
												{
													href : "tel:+1" + d,
													rel : "external",
													style : "text-decoration:none;color:#444;"
												});
										d.append("<p><br />Mobile Phone</p>");
										d.append(b);
										c.append(d);
										$("#ViewContactDetailsPageDetailsList").append(c)
									}
									if (a.fax !== undefined && a.fax !== "") {
										b = $("<li/>");
										c = "<h4>"
												+ a.fax
												+ "</h4>";
										b.append("<p><br />Fax</p>");
										b.append(c);
										$("#ViewContactDetailsPageDetailsList")
												.append(b)
									}
									if (a.notes !== undefined && a.notes !== "") {
										c = $("<li/>");
										d = "<h4>"
												+ a.notes
												+ "</h4>";
										c.append("<p><br />Description</p>");
										c.append(d);
										$("#ViewContactDetailsPageDetailsList").append(c)
									}
									if (a.address !== undefined) {
										f = a.address;
										var e = a.address;
										d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
										c = $("<li/>");
										f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
										d = $(
												"<a/>",
												{
													href : d,
													rel : "external",
													target : "_new",
													style : "text-decoration:none;color:#444;"
												});
										d
												.append("<p><br />Address</p>");
										d.append(f);
										c.append(d);
										$("#ViewContactDetailsPageDetailsList").append(c)
									}
								}
							$("#ViewContactDetailsPageDetailsList").listview("refresh")
						}
						$.mobile.hidePageLoadingMsg()
					});
}

function FaGetSupplierListFromServer(a) {
	if ($("#AllSupplierListDiv li").length === 0 || SupplierListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		SupplierListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'SUPPLIER';
		var _method = 'get_all_supplier';
		var _company = CompanyIndex;
		var _param1 = '';
		
		$.get("../ws/api.php",
						{
							module  : _module,
							method  : _method,
							company : _company,
							param1  : _param1,
							pgIndex : SupplierListCurrentOffset,
							recordPerPage : RowsPerPageInListViews,
							input_type : "JSON",
							response_type : "JSON"
						},
				function(c) {
					if (c !== undefined) {
						c = jQuery.parseJSON(c);
								
						if (c !== undefined && c.results !== undefined) {
							if (c.recordPerPage === 0)
								SupplierListCurrentOffset = SupplierListPrevOffset + RowsPerPageInListViews;
							else if (c.pgIndex === 0)
								SupplierListCurrentOffset = 0;
							if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
								alert("No more records");
							else {
								$("#AllSupplierListDiv li").remove();
								var b = 0;
								for (b = 0; b <= c.results.length; b++)
									if (c.results[b] !== undefined) {
										var d = c.results[b], f = $("<li/>"), e = "<h4>"
												+ d.supp_name
												+ "</h4>", g = "<p>"
												+ d.address
												+ "</p>";
										d = $(
												"<a/>",
												{
													href : "#",
													"data-identity" : d.supplier_id,
													click : function() {
														CurrentSupplierId = $(this).data("identity");
														$.mobile.changePage("#ViewSupplierDetailsPage");
														$.mobile.showPageLoadingMsg();
														FaGetSupplierDetails()
													}
												});
										d.append(e);
										d.append(g);
										f.append(d);
										$("#AllSupplierListDiv").append(f)
									}
								$("#AllSupplierListDiv").listview("refresh");
								SupplierListNextOffset = c.pgIndex;
								SupplierListPrevOffset = a - RowsPerPageInListViews
								
								if (SupplierListPrevOffset < 0)
									SupplierListPrevOffset = 0;
							}
						}
					}
					$.mobile.hidePageLoadingMsg()
				})
	}
}
function FaGetSupplierDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewSupplierDetailsPageDetailsList li").remove();
	$("#TxtSupplierName").html("");
	$("#TxtSupplierDescription").html("");
	var _module = 'SUPPLIER';
	var _method = 'get_supplier_by_id';
	var _company = CompanyIndex;
	var _param1 = CurrentSupplierId;
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,
						param1  : _param1,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						if (a !== undefined) {
							a = jQuery.parseJSON(a);
							
							if (a !== undefined && a.results !== undefined)
								if (a.results[0] !== undefined) {
									supplier = a.results[0];
									$("#TxtSupplierName").html(supplier.supp_name);
									$("#TxtSupplierDescription").html(supplier.notes);
									$("#ViewSupplierDetailsPageDetailsList").append('<li data-role="list-divider">Supplier Overview</li>');

									if (supplier.website !== "") {
										c = "";
										c = supplier.website
												.substring(0, 4) !== "http" ? "http://"
												+ supplier.website
												: supplier.website;
										a = $("<li/>");
										b = "<h4>"
												+ supplier.website
												+ "</h4>";
										c = $(
												"<a/>",
												{
													href : c,
													rel : "external",
													target : "_new",
													style : "text-decoration:none;color:#444;"
												});
										c.append("<p><br />Web Site</p>");
										c.append(b);
										a.append(c);
										$("#ViewSupplierDetailsPageDetailsList").append(a)
									}
									
									if (supplier.curr_code !== undefined && supplier.curr_code !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ supplier.curr_code
												+ "</h4>";
										a.append("<p><br />Currency Code</p>");
										a.append(c);
										$("#ViewSupplierDetailsPageDetailsList").append(a)
									}
									
									if (supplier.credit_limit !== undefined && supplier.credit_limit !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ CurrencyFormatted(supplier.credit_limit)
												+ "</h4>";
										a.append("<p><br />Credit Limit</p>");
										a.append(c);
										$("#ViewSupplierDetailsPageDetailsList").append(a)
									}
									
									if (supplier.terms !== undefined && supplier.terms !== "") {
										a = $("<li/>");
										c = "<h4>"
												+ supplier.terms
												+ "</h4>";
										a.append("<p><br />Terms</p>");
										a.append(c);
										$("#ViewSupplierDetailsPageDetailsList").append(a)
									}
									
									if (supplier.address !== undefined) {
										f = supplier.address;
										d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
										a = $("<li/>");
										f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
										d = $(
												"<a/>",
												{
													href : d,
													rel : "external",
													target : "_new",
													style : "text-decoration:none;color:#444;"
												});
										d
												.append("<p><br />Address</p>");
										d.append(f);
										a.append(d);
										$("#ViewSupplierDetailsPageDetailsList").append(a)
									}
								}
						}
						$("#ViewSupplierDetailsPageDetailsList").listview("refresh")
					});
	
	$("#ViewSupplierDetailsPageContactListUl li").remove();
	
	var _module = 'CONTACT';
	var _method = 'get_contact_by_supplier_id';
	var _company = CompanyIndex;
	var _param1 = CurrentSupplierId;
	var _param2 = 'supplier';
	var _param3 = '';
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,
						param1  : _param1,
						param2  : _param2,
						param3  : _param3,
						pgIndex : ContactListCurrentOffset,
						recordPerPage : RowsPerPageInListViews,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						$("#ViewSupplierDetailsPageContactListUl").append('<li data-role="list-divider">Contact</li>');
						if (a !== undefined) {
							a = jQuery.parseJSON(a);
							
							if (a !== undefined && a.results !== undefined)
								if (a.results.length > 0) {
									var c = 0;
									for (c = 0; c <= a.results.length; c++)
										if (a.results[c] !== undefined) {
											var b = a.results[c], d = $("<li/>"), f = "<h4>"
													+ b.name
													+ "&nbsp;"
													+ b.name2
													+ "</h4>", e = "<p>"
													+ b.email
													+ "</p>";
											b = $(
													"<a/>",
													{
														href : "#",
														"data-identity" : b.id,
														click : function() {
															CurrentSupplierContactId = $(this).data("identity");
															$.mobile.changePage("#ViewSupplierContactDetailsPage");
															$.mobile.showPageLoadingMsg();
															FaGetSupplierContactDetails()
														}
													});
											b.append(f);
											b.append(e);
											d.append(b);
											$("#ViewSupplierDetailsPageContactListUl").append(d)
										}
								} else {
									a = $("<li/>");
									a.append("<h4>No Data</h4>");
									$("#ViewSupplierDetailsPageContactListUl").append(a)
								}
							$("#ViewSupplierDetailsPageContactListUl").listview("refresh")
						}
						$.mobile.hidePageLoadingMsg()
					});
}

function FaGetSupplierContactDetails() {
	$("#TxtSupplierContactName").html("");
	$("#TxtSupplierDetailName").html("");
	$("#ViewSupplierContactDetailsPageDetailsList li").remove();
	SessionId == "" && $.mobile.changePage("#HomePage");
	var _module = 'CONTACT';
	var _method = 'get_supplier_contact_by_id';
	var _company = CompanyIndex;
	var _param1 = CurrentSupplierContactId;
	
	$.get("../ws/api.php",
					{
						module  : _module,
						method  : _method,
						company : _company,
						param1  : _param1,
						input_type : "JSON",
						response_type : "JSON"
					},
					function(a) {
						if (a != undefined) {
							a = jQuery.parseJSON(a);
							
							if (a != undefined && a.results != undefined)
								if (a.results[0] != undefined) {
									a = a.results[0];
									$("#TxtSupplierContactName").html(a.name + "&nbsp;" + a.name2);
									var c = a.supp_name;
									$("#TxtSupplierDetailName").html(c);
									$("#ViewSupplierContactDetailsPageDetailsList").append('<li data-role="list-divider">Contact Information</li>');
									if (a.email !== undefined && a.email !== "") {
										c = $("<li/>");
										d = "<h4>"
												+ a.email
												+ "</h4>";
										f = $("<a/>",
												{
													href : "mailto:"
															+ a.email,
													rel : "external",
													style : "text-decoration:none;color:#444;"
												});
										f.append("<p><br />Email</p>");
										f.append(d);
										c.append(f);
										$("#ViewSupplierContactDetailsPageDetailsList").append(c)
									}
									if (a.phone !== undefined && a.phone !== "") {
										c = $("<li/>");
										var b = a.phone
												.replace("(", "");
										b = b.replace(")", "");
										b = b.replace(" ", "");
										b = b.replace("-", "");
										if (a.phone !== undefined) {
											var d = "<h4>"
													+ a.phone
													+ "</h4>", f = $(
													"<a/>",
													{
														href : "tel:+1" + b,
														rel : "external",
														style : "text-decoration:none;color:#444;"
													});
											f
													.append("<p><br />Office Phone</p>");
											f.append(d);
											c.append(f)
										}
										$("#ViewSupplierContactDetailsPageDetailsList").append(c)
									}
									if (a.phone2 !== undefined && a.phone2 !== "") {
										c = $("<li/>");
										d = a.phone2
												.replace("(", "");
										b.replace(")", "");
										b.replace(" ", "");
										d = b.replace("-", "");
										b = "<h4>"
												+ a.phone2
												+ "</h4>";
										d = $(
												"<a/>",
												{
													href : "tel:+1" + d,
													rel : "external",
													style : "text-decoration:none;color:#444;"
												});
										d.append("<p><br />Mobile Phone</p>");
										d.append(b);
										c.append(d);
										$("#ViewSupplierContactDetailsPageDetailsList").append(c)
									}
									if (a.fax !== undefined && a.fax !== "") {
										b = $("<li/>");
										c = "<h4>"
												+ a.fax
												+ "</h4>";
										b.append("<p><br />Fax</p>");
										b.append(c);
										$("#ViewSupplierContactDetailsPageDetailsList")
												.append(b)
									}
									if (a.notes !== undefined && a.notes !== "") {
										c = $("<li/>");
										d = "<h4>"
												+ a.notes
												+ "</h4>";
										c.append("<p><br />Description</p>");
										c.append(d);
										$("#ViewSupplierContactDetailsPageDetailsList").append(c)
									}
									if (a.address !== undefined) {
										f = a.address;
										var e = a.address;
										d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
										c = $("<li/>");
										f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
										d = $(
												"<a/>",
												{
													href : d,
													rel : "external",
													target : "_new",
													style : "text-decoration:none;color:#444;"
												});
										d
												.append("<p><br />Address</p>");
										d.append(f);
										c.append(d);
										$("#ViewSupplierContactDetailsPageDetailsList").append(c)
									}
								}
							$("#ViewSupplierContactDetailsPageDetailsList").listview("refresh")
						}
						$.mobile.hidePageLoadingMsg()
					});
}

function FaGetProductListFromServer(a) {
	if ($("#AllProductListDiv li").length === 0 || ProductListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		ProductListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'ITEM';
		var _method = 'get_all_stock';
		var _company = CompanyIndex;
		var _param1 = '';
		
		$.get("../ws/api.php",
			{
				module  : _module,
				method  : _method,
				company : _company,
				param1  : _param1,
				pgIndex : ProductListCurrentOffset,
				recordPerPage : RowsPerPageInListViews,
				input_type : "JSON",
				response_type : "JSON"
			},
	function(c) {
		if (c !== undefined) {
			c = jQuery.parseJSON(c);
					
			if (c !== undefined && c.results !== undefined) {
				if (c.recordPerPage === 0)
					ProductListCurrentOffset = ProductListPrevOffset + RowsPerPageInListViews;
				else if (c.pgIndex === 0)
					ProductListCurrentOffset = 0;
				if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
					alert("No more records");
				else {
					$("#AllProductListDiv li").remove();
					var b = 0;
					for (b = 0; b <= c.results.length; b++)
						if (c.results[b] !== undefined) {
							var d = c.results[b], f = $("<li/>"), e = "<h4>"
									+ d.description + '  (' + d.curr_code + ' ' + CurrencyFormatted(d.list_price) + ')'
									+ "</h4>", g = "<p>"
									+ d.category
									+ "</p>";
							d = $(
									"<a/>",
									{
										href : "#",
										"data-identity" : d.stock_id,
										click : function() {
											CurrentProductId = $(this).data("identity");
											$.mobile.changePage("#ViewProductDetailsPage");
											$.mobile.showPageLoadingMsg();
											FaGetProductDetails()
										}
									});
							d.append(e);
							d.append(g);
							f.append(d);
							$("#AllProductListDiv").append(f)
						}
					$("#AllProductListDiv").listview("refresh");
					ProductListNextOffset = c.pgIndex;
					ProductListPrevOffset = a - RowsPerPageInListViews
					
					if (ProductListPrevOffset < 0)
						ProductListPrevOffset = 0;
				}
			}
		}
		$.mobile.hidePageLoadingMsg()
	})
	}
}
function FaGetProductDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewProductDetailsPageDetailsList li").remove();
	$("#TxtProductName").html("");
	$("#TxtProductDescription").html("");
	var _module = 'ITEM';
	var _method = 'get_stock_by_stock_id';
	var _company = CompanyIndex;
	var _param1 = CurrentProductId;
	
	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
			
				if (a !== undefined && a.results !== undefined)
					if (a.results[0] !== undefined) {
						product = a.results[0];
						$("#TxtProductName").html(product.description);
						$("#TxtProductDescription").html(product.category);
						$("#ViewProductDetailsPageDetailsList").append('<li data-role="list-divider">Product Overview</li>');

						if (product.stock_id !== undefined && product.stock_id !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.stock_id
									+ "</h4>";
							a.append("<p><br />Stock ID</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
					
						if (product.tax_type_name !== undefined && product.tax_type_name !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.tax_type_name
									+ "</h4>";
							a.append("<p><br />Item Tax Type</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.mb_flag !== undefined && product.mb_flag !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.mb_flag
									+ "</h4>";
							a.append("<p><br />Item Type</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.location_name !== undefined && product.location_name !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.location_name
									+ "</h4>";
							a.append("<p><br />Location</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.reorder_level !== undefined && product.reorder_level !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.reorder_level
									+ "</h4>";
							a.append("<p><br />Reorder Level</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.on_demand !== undefined && product.on_demand !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.on_demand
									+ "</h4>";
							a.append("<p><br />On Demand</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.qty_on_hand !== undefined && product.qty_on_hand !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.qty_on_hand
									+ "</h4>";
							a.append("<p><br />Quantity On Hand</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.on_order !== undefined && product.on_order !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.on_order
									+ "</h4>";
							a.append("<p><br />On Order</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
						
						if (product.available_stock !== undefined && product.available_stock !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ product.available_stock
									+ "</h4>";
							a.append("<p><br />Available Stock</p>");
							a.append(c);
							$("#ViewProductDetailsPageDetailsList").append(a)
						}
					}
			}
			$("#ViewProductDetailsPageDetailsList").listview("refresh")
		});
	$.mobile.hidePageLoadingMsg()
}


function FaGetPurchaseOrderListFromServer(a) {
	if ($("#AllPurchaseOrdersListDiv li").length === 0 || PurchaseOrdersListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		PurchaseOrdersListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'PURCHASE_ORDER';
		var _method = 'get_all_purchase_order';
		var _company = CompanyIndex;
		var _param1 = '';
		
		$.get("../ws/api.php",
			{
				module  : _module,
				method  : _method,
				company : _company,
				param1  : _param1,
				pgIndex : PurchaseOrdersListCurrentOffset,
				recordPerPage : RowsPerPageInListViews,
				input_type : "JSON",
				response_type : "JSON"
			},
	function(c) {
		if (c !== undefined) {
			c = jQuery.parseJSON(c);
					
			if (c !== undefined && c.results !== undefined) {
				if (c.recordPerPage === 0)
					PurchaseOrdersListCurrentOffset = PurchaseOrdersListPrevOffset + RowsPerPageInListViews;
				else if (c.pgIndex === 0)
					PurchaseOrdersListCurrentOffset = 0;
				if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
					alert("No more records");
				else {
					$("#AllPurchaseOrdersListDiv li").remove();
					var b = 0;
					for (b = 0; b <= c.results.length; b++)
						if (c.results[b] !== undefined) {
							var d = c.results[b], f = $("<li/>"), e = "<h4>"
									+ d.comments + '  (' + d.curr_code + ' ' + CurrencyFormatted(d.total) + ')'
									+ "</h4>", g = "<p>"
									+ d.supp_name + ' (' + d.reference + ')'
									+ "</p>";
							d = $(
									"<a/>",
									{
										href : "#",
										"data-identity" : d.order_no,
										click : function() {
											CurrentPurchaseOrderId = $(this).data("identity");
											$.mobile.changePage("#ViewPurchaseOrderDetailsPage");
											$.mobile.showPageLoadingMsg();
											FaGetPurchaseOrderDetails()
										}
									});
							d.append(e);
							d.append(g);
							f.append(d);
							$("#AllPurchaseOrdersListDiv").append(f)
						}
					$("#AllPurchaseOrdersListDiv").listview("refresh");
					PurchaseOrdersListNextOffset = c.pgIndex;
					PurchaseOrdersListPrevOffset = a - RowsPerPageInListViews
					
					if (PurchaseOrdersListPrevOffset < 0)
						PurchaseOrdersListPrevOffset = 0;
				}
			}
		}
		$.mobile.hidePageLoadingMsg()
	})
	}
}
function FaGetPurchaseOrderDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewPurchaseOrderDetailsPageDetailsList li").remove();
	$("#TxtPurchaseOrderName").html("");
	$("#TxtPurchaseOrderDescription").html("");
	var _module = 'PURCHASE_ORDER';
	var _method = 'get_purchase_order_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentPurchaseOrderId;
	
	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
			
				if (a !== undefined && a.results !== undefined)
					if (a.results[0] !== undefined) {
						purchase_order = a.results[0];
						$("#TxtPurchaseOrderName").html(purchase_order.comments);
						$("#TxtPurchaseOrderDescription").html(purchase_order.supp_name);
						$("#ViewPurchaseOrderDetailsPageDetailsList").append('<li data-role="list-divider">Purchase Order Overview</li>');

						if (purchase_order.ord_date !== undefined && purchase_order.ord_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ purchase_order.ord_date
									+ "</h4>";
							a.append("<p><br />Order Date</p>");
							a.append(c);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(a)
						}
						
						if (purchase_order.order_no !== undefined && purchase_order.order_no !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ purchase_order.order_no
									+ "</h4>";
							a.append("<p><br />Order No</p>");
							a.append(c);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(a)
						}
						
						if (purchase_order.curr_code !== undefined && purchase_order.curr_code !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ purchase_order.curr_code + ' ' + CurrencyFormatted(purchase_order.total)
									+ "</h4>";
							a.append("<p><br />Total Amount (included Tax)</p>");
							a.append(c);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(a)
						}
						
						if (purchase_order.location_name !== undefined && purchase_order.location_name !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ purchase_order.location_name
									+ "</h4>";
							a.append("<p><br />	Deliver Into Location</p>");
							a.append(c);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(a)
						}
						
						if (purchase_order.requisition_no !== undefined && purchase_order.requisition_no !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ purchase_order.requisition_no
									+ "</h4>";
							a.append("<p><br />Supplier's Reference</p>");
							a.append(c);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(a)
						}
						
						if (purchase_order.delivery_address !== undefined) {
							f = purchase_order.delivery_address;
							var e = purchase_order.delivery_address;
							d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
							c = $("<li/>");
							f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
							d = $(
									"<a/>",
									{
										href : d,
										rel : "external",
										target : "_new",
										style : "text-decoration:none;color:#444;"
									});
							d
									.append("<p><br />Delivery Address</p>");
							d.append(f);
							c.append(d);
							$("#ViewPurchaseOrderDetailsPageDetailsList").append(c)
						}
						
						 
					}
			}
			$("#ViewPurchaseOrderDetailsPageDetailsList").listview("refresh")
		});
	
$("#ViewPurchaseOrderDetailsPageOrderItemsListUl li").remove();
	
	var _module = 'PURCHASE_ORDER_ITEMS';
	var _method = 'get_purchase_order_item_details_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentPurchaseOrderId;	

	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			$("#ViewPurchaseOrderDetailsPageOrderItemsListUl").append('<li data-role="list-divider">Line Items</li>');
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
				
				if (a !== undefined && a.results !== undefined)
					if (a.results.length > 0) {
						var b = 0;
						for (b = 0; b <= a.results.length; b++)
							if (a.results[b] !== undefined) {
								var d = a.results[b];
								var stock_id = '';
								
								if (d.line_item_type == 'Services') {
									stock_id = d.service_code;
								} else {
									stock_id = d.product_code;
								}
								
								f = $("<li/>"), e = "<h4>"
										+ d.product_name
										+ "</h4>", g = "<p>"
										+ "</b>Qty: <b>" + d.qty + "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:<b>" + CurrencyFormatted(d.qty  * d.list_price) + "</b>"
										+ "</p>";
								d = $(
										"<a/>",
										{
											href : "#",
											"data-identity" : stock_id,
											click : function() {
												CurrentProductId = $(this).data("identity");
												$.mobile.changePage("#ViewProductDetailsPage");
												$.mobile.showPageLoadingMsg();
												FaGetProductDetails()
											}
										});
								d.append(e);
								d.append(g);
								f.append(d);
								$("#ViewPurchaseOrderDetailsPageOrderItemsListUl").append(f)
							}
					} else {
						a = $("<li/>");
						a.append("<h4>No Data</h4>");
						$("#ViewPurchaseOrderDetailsPageOrderItemsListUl").append(a)
					}
				$("#ViewPurchaseOrderDetailsPageOrderItemsListUl").listview("refresh")
			}
			$.mobile.hidePageLoadingMsg()
		});
}

function FaGetQuotationListFromServer(a) {
	if ($("#AllQuotationListDiv li").length === 0 || QuotationListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		QuotationListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'QUOTATION';
		var _method = 'get_all_sales_order';
		var _company = CompanyIndex;
		var _param1 = '';
		var _param2 = '32';
		
		$.get("../ws/api.php",
			{
				module  : _module,
				method  : _method,
				company : _company,
				param1  : _param1,
				param2  : _param2,
				pgIndex : QuotationListCurrentOffset,
				recordPerPage : RowsPerPageInListViews,
				input_type : "JSON",
				response_type : "JSON"
			},
	function(c) {
		if (c !== undefined) {
			c = jQuery.parseJSON(c);
					
			if (c !== undefined && c.results !== undefined) {
				if (c.recordPerPage === 0)
					QuotationListCurrentOffset = QuotationListPrevOffset + RowsPerPageInListViews;
				else if (c.pgIndex === 0)
					QuotationListCurrentOffset = 0;
				if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
					alert("No more records");
				else {
					$("#AllQuotationListDiv li").remove();
					var b = 0;
					for (b = 0; b <= c.results.length; b++)
						if (c.results[b] !== undefined) {
							var d = c.results[b], f = $("<li/>"), e = "<h4>"
									+ d.comments + '  (' + d.curr_code + ' ' + CurrencyFormatted(d.total) + ')'
									+ "</h4>", g = "<p>"
									+ d.name + ' (' + d.reference + ')'
									+ "</p>";
							d = $(
									"<a/>",
									{
										href : "#",
										"data-identity" : d.order_no,
										click : function() {
											CurrentQuotationId = $(this).data("identity");
											$.mobile.changePage("#ViewQuotationDetailsPage");
											$.mobile.showPageLoadingMsg();
											FaGetQuotationDetails()
										}
									});
							d.append(e);
							d.append(g);
							f.append(d);
							$("#AllQuotationListDiv").append(f)
						}
					$("#AllQuotationListDiv").listview("refresh");
					QuotationListNextOffset = c.pgIndex;
					QuotationListPrevOffset = a - RowsPerPageInListViews
					
					if (QuotationListPrevOffset < 0)
						QuotationListPrevOffset = 0;
				}
			}
		}
		$.mobile.hidePageLoadingMsg()
	})
	}
}
function FaGetQuotationDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewQuotationDetailsPageDetailsList li").remove();
	$("#TxtQuotationName").html("");
	$("#TxtQuotationDescription").html("");
	var _module = 'QUOTATION';
	var _method = 'get_sales_order_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentQuotationId;
	var _param2 = '32';
	
	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
			
				if (a !== undefined && a.results !== undefined)
					if (a.results[0] !== undefined) {
						sales_order = a.results[0];
						$("#TxtQuotationName").html(sales_order.comments);
						$("#TxtQuotationDescription").html(sales_order.name);
						$("#ViewQuotationDetailsPageDetailsList").append('<li data-role="list-divider">Quotation Overview</li>');

						if (sales_order.ord_date !== undefined && sales_order.ord_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.ord_date
									+ "</h4>";
							a.append("<p><br />Ordered On</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_date !== undefined && sales_order.delivery_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.delivery_date
									+ "</h4>";
							a.append("<p><br />Valid Until</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.order_no !== undefined && sales_order.order_no !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.order_no
									+ "</h4>";
							a.append("<p><br />Quote No#</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_to !== undefined && sales_order.delivery_to !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.delivery_to
									+ "</h4>";
							a.append("<p><br />	Deliver To</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.curr_code !== undefined && sales_order.curr_code !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.curr_code + ' ' + CurrencyFormatted(sales_order.total)
									+ "</h4>";
							a.append("<p><br />Total Amount (included Tax)</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.customer_ref !== undefined && sales_order.customer_ref !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.customer_ref
									+ "</h4>";
							a.append("<p><br />Customer Order Ref.</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.contact_phone !== undefined && sales_order.contact_phone !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.contact_phone
									+ "</h4>";
							a.append("<p><br />Telephone</p>");
							a.append(c);
							$("#ViewQuotationDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_address !== undefined) {
							f = sales_order.delivery_address;
							var e = sales_order.delivery_address;
							d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
							c = $("<li/>");
							f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
							d = $(
									"<a/>",
									{
										href : d,
										rel : "external",
										target : "_new",
										style : "text-decoration:none;color:#444;"
									});
							d
									.append("<p><br />Delivery Address</p>");
							d.append(f);
							c.append(d);
							$("#ViewQuotationDetailsPageDetailsList").append(c)
						}
						
						 
					}
			}
			$("#ViewQuotationDetailsPageDetailsList").listview("refresh")
		});
	
$("#ViewQuotationDetailsPageOrderItemsListUl li").remove();
	
	var _module = 'QUOTATION_ITEMS';
	var _method = 'get_sales_order_item_details_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentQuotationId;
	var _param2 = '32';

	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			$("#ViewQuotationDetailsPageOrderItemsListUl").append('<li data-role="list-divider">Line Items</li>');
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
				
				if (a !== undefined && a.results !== undefined)
					if (a.results.length > 0) {
						var b = 0;
						for (b = 0; b <= a.results.length; b++)
							if (a.results[b] !== undefined) {
								var d = a.results[b];
								var stock_id = '';
								
								if (d.line_item_type == 'Services') {
									stock_id = d.service_code;
								} else {
									stock_id = d.product_code;
								}
								
								f = $("<li/>"), e = "<h4>"
										+ d.product_name
										+ "</h4>", g = "<p>"
										+ "</b>Qty: <b>" + d.qty + "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:<b>" + CurrencyFormatted((d.qty  * d.list_price) * ((100 - d.discount_percent) /100)) + "</b>"
										+ "</p>";
								d = $(
										"<a/>",
										{
											href : "#",
											"data-identity" : stock_id,
											click : function() {
												CurrentProductId = $(this).data("identity");
												$.mobile.changePage("#ViewProductDetailsPage");
												$.mobile.showPageLoadingMsg();
												FaGetProductDetails()
											}
										});
								d.append(e);
								d.append(g);
								f.append(d);
								$("#ViewQuotationDetailsPageOrderItemsListUl").append(f)
							}
					} else {
						a = $("<li/>");
						a.append("<h4>No Data</h4>");
						$("#ViewQuotationDetailsPageOrderItemsListUl").append(a)
					}
				$("#ViewQuotationDetailsPageOrderItemsListUl").listview("refresh")
			}
			$.mobile.hidePageLoadingMsg()
		});
}


function FaGetSalesOrderListFromServer(a) {
	if ($("#AllSalesOrdersListDiv li").length === 0 || SalesOrdersListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		SalesOrdersListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'SALES_ORDER';
		var _method = 'get_all_sales_order';
		var _company = CompanyIndex;
		var _param1 = '';
		var _param2 = '30';
		
		$.get("../ws/api.php",
			{
				module  : _module,
				method  : _method,
				company : _company,
				param1  : _param1,
				param2  : _param2,
				pgIndex : SalesOrdersListCurrentOffset,
				recordPerPage : RowsPerPageInListViews,
				input_type : "JSON",
				response_type : "JSON"
			},
	function(c) {
		if (c !== undefined) {
			c = jQuery.parseJSON(c);
					
			if (c !== undefined && c.results !== undefined) {
				if (c.recordPerPage === 0)
					SalesOrdersListCurrentOffset = SalesOrdersListPrevOffset + RowsPerPageInListViews;
				else if (c.pgIndex === 0)
					SalesOrdersListCurrentOffset = 0;
				if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
					alert("No more records");
				else {
					$("#AllSalesOrdersListDiv li").remove();
					var b = 0;
					for (b = 0; b <= c.results.length; b++)
						if (c.results[b] !== undefined) {
							var d = c.results[b], f = $("<li/>"), e = "<h4>"
									+ d.comments + '  (' + d.curr_code + ' ' + CurrencyFormatted(d.total) + ')'
									+ "</h4>", g = "<p>"
									+ d.name + ' (' + d.reference + ')'
									+ "</p>";
							d = $(
									"<a/>",
									{
										href : "#",
										"data-identity" : d.order_no,
										click : function() {
											CurrentSalesOrderId = $(this).data("identity");
											$.mobile.changePage("#ViewSalesOrderDetailsPage");
											$.mobile.showPageLoadingMsg();
											FaGetSalesOrderDetails()
										}
									});
							d.append(e);
							d.append(g);
							f.append(d);
							$("#AllSalesOrdersListDiv").append(f)
						}
					$("#AllSalesOrdersListDiv").listview("refresh");
					SalesOrdersListNextOffset = c.pgIndex;
					SalesOrdersListPrevOffset = a - RowsPerPageInListViews
					
					if (SalesOrdersListPrevOffset < 0)
						SalesOrdersListPrevOffset = 0;
				}
			}
		}
		$.mobile.hidePageLoadingMsg()
	})
	}
}
function FaGetSalesOrderDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewSalesOrderDetailsPageDetailsList li").remove();
	$("#TxtSalesOrderName").html("");
	$("#TxtSalesOrderDescription").html("");
	var _module = 'SALES_ORDER';
	var _method = 'get_sales_order_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentSalesOrderId;
	var _param2 = '30';
	
	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
			
				if (a !== undefined && a.results !== undefined)
					if (a.results[0] !== undefined) {
						sales_order = a.results[0];
						$("#TxtSalesOrderName").html(sales_order.comments);
						$("#TxtSalesOrderDescription").html(sales_order.name);
						$("#ViewSalesOrderDetailsPageDetailsList").append('<li data-role="list-divider">Sales Order Overview</li>');

						if (sales_order.ord_date !== undefined && sales_order.ord_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.ord_date
									+ "</h4>";
							a.append("<p><br />Order Date</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_date !== undefined && sales_order.delivery_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.delivery_date
									+ "</h4>";
							a.append("<p><br />	Deliver Date</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.order_no !== undefined && sales_order.order_no !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.order_no
									+ "</h4>";
							a.append("<p><br />Order No</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_to !== undefined && sales_order.delivery_to !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.delivery_to
									+ "</h4>";
							a.append("<p><br />	Deliver To</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.curr_code !== undefined && sales_order.curr_code !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.curr_code + ' ' + CurrencyFormatted(sales_order.total)
									+ "</h4>";
							a.append("<p><br />Total Amount (included Tax)</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.customer_ref !== undefined && sales_order.customer_ref !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.customer_ref
									+ "</h4>";
							a.append("<p><br />Customer Order Ref.</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.contact_phone !== undefined && sales_order.contact_phone !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ sales_order.contact_phone
									+ "</h4>";
							a.append("<p><br />Telephone</p>");
							a.append(c);
							$("#ViewSalesOrderDetailsPageDetailsList").append(a)
						}
						
						if (sales_order.delivery_address !== undefined) {
							f = sales_order.delivery_address;
							var e = sales_order.delivery_address;
							d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
							c = $("<li/>");
							f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
							d = $(
									"<a/>",
									{
										href : d,
										rel : "external",
										target : "_new",
										style : "text-decoration:none;color:#444;"
									});
							d
									.append("<p><br />Delivery Address</p>");
							d.append(f);
							c.append(d);
							$("#ViewSalesOrderDetailsPageDetailsList").append(c)
						}
						
						 
					}
			}
			$("#ViewSalesOrderDetailsPageDetailsList").listview("refresh")
		});
	
$("#ViewSalesOrderDetailsPageOrderItemsListUl li").remove();
	
	var _module = 'SALES_ORDER_ITEMS';
	var _method = 'get_sales_order_item_details_by_order_no';
	var _company = CompanyIndex;
	var _param1 = CurrentSalesOrderId;
	var _param2 = '30';

	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			$("#ViewSalesOrderDetailsPageOrderItemsListUl").append('<li data-role="list-divider">Line Items</li>');
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
				
				if (a !== undefined && a.results !== undefined)
					if (a.results.length > 0) {
						var b = 0;
						for (b = 0; b <= a.results.length; b++)
							if (a.results[b] !== undefined) {
								var d = a.results[b];
								var stock_id = '';
								
								if (d.line_item_type == 'Services') {
									stock_id = d.service_code;
								} else {
									stock_id = d.product_code;
								}
								
								f = $("<li/>"), e = "<h4>"
										+ d.product_name
										+ "</h4>", g = "<p>"
										+ "</b>Qty: <b>" + d.qty + "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:<b>" + CurrencyFormatted((d.qty  * d.list_price) * ((100 - d.discount_percent) /100)) + "</b>"
										+ "</p>";
								d = $(
										"<a/>",
										{
											href : "#",
											"data-identity" : stock_id,
											click : function() {
												CurrentProductId = $(this).data("identity");
												$.mobile.changePage("#ViewProductDetailsPage");
												$.mobile.showPageLoadingMsg();
												FaGetProductDetails()
											}
										});
								d.append(e);
								d.append(g);
								f.append(d);
								$("#ViewSalesOrderDetailsPageOrderItemsListUl").append(f)
							}
					} else {
						a = $("<li/>");
						a.append("<h4>No Data</h4>");
						$("#ViewSalesOrderDetailsPageOrderItemsListUl").append(a)
					}
				$("#ViewSalesOrderDetailsPageOrderItemsListUl").listview("refresh")
			}
			$.mobile.hidePageLoadingMsg()
		});
}


function FaGetInvoiceListFromServer(a) {
	if ($("#AllInvoiceListDiv li").length === 0 || InvoiceListCurrentOffset !== a) {
		$.mobile.showPageLoadingMsg();
		InvoiceListCurrentOffset = a;
		SessionId === "" && $.mobile.changePage("#HomePage");
		
		var _module = 'INVOICE';
		var _method = 'get_all_invoice';
		var _company = CompanyIndex;
		var _param1 = '';
		var _param2 = '10';
		
		$.get("../ws/api.php",
			{
				module  : _module,
				method  : _method,
				company : _company,
				param1  : _param1,
				param2  : _param2,
				pgIndex : InvoiceListCurrentOffset,
				recordPerPage : RowsPerPageInListViews,
				input_type : "JSON",
				response_type : "JSON"
			},
	function(c) {
		if (c !== undefined) {
			c = jQuery.parseJSON(c);
					
			if (c !== undefined && c.results !== undefined) {
				if (c.recordPerPage === 0)
					InvoiceListCurrentOffset = InvoiceListPrevOffset + RowsPerPageInListViews;
				else if (c.pgIndex === 0)
					InvoiceListCurrentOffset = 0;
				if (c.pgIndex === 0  || c.recordPerPage === 0 || c.results.length == 0)
					alert("No more records");
				else {
					$("#AllInvoiceListDiv li").remove();
					var b = 0;
					for (b = 0; b <= c.results.length; b++)
						if (c.results[b] !== undefined) {
							var d = c.results[b], f = $("<li/>"), e = "<h4>"
									+ d.comments + '  (' + d.curr_code + ' ' + CurrencyFormatted(d.TotalAmount) + ')'
									+ "</h4>", g = "<p>"
									+ d.name + ' (' + d.type_desc + ')'
									+ "</p>";
							d = $(
									"<a/>",
									{
										href : "#",
										"data-identity" : d.trans_no,
										click : function() {
											CurrentInvoiceId = $(this).data("identity");
											$.mobile.changePage("#ViewInvoiceDetailsPage");
											$.mobile.showPageLoadingMsg();
											FaGetInvoiceDetails()
										}
									});
							d.append(e);
							d.append(g);
							f.append(d);
							$("#AllInvoiceListDiv").append(f)
						}
					$("#AllInvoiceListDiv").listview("refresh");
					InvoiceListNextOffset = c.pgIndex;
					InvoiceListPrevOffset = a - RowsPerPageInListViews
					
					if (InvoiceListPrevOffset < 0)
						InvoiceListPrevOffset = 0;
				}
			}
		}
		$.mobile.hidePageLoadingMsg()
	})
	}
}
function FaGetInvoiceDetails() {
	SessionId === "" && $.mobile.changePage("#HomePage");
	$("#ViewInvoiceDetailsPageDetailsList li").remove();
	$("#TxtInvoiceName").html("");
	$("#TxtInvoiceDescription").html("");
	var _module = 'INVOICE';
	var _method = 'get_invoice_by_trans_no';
	var _company = CompanyIndex;
	var _param1 = CurrentInvoiceId;
	var _param2 = '10';
	
	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
			
				if (a !== undefined && a.results !== undefined)
					if (a.results[0] !== undefined) {
						invoice = a.results[0];
						$("#TxtInvoiceName").html(invoice.comments);
						$("#TxtInvoiceDescription").html(invoice.DebtorName);
						$("#ViewInvoiceDetailsPageDetailsList").append('<li data-role="list-divider">Invoice Overview</li>');

						if (invoice.type_desc !== undefined && invoice.type_desc !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.type_desc
									+ "</h4>";
							a.append("<p><br />Type</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.trans_no !== undefined && invoice.trans_no !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.trans_no
									+ "</h4>";
							a.append("<p><br />Invoice No</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.tran_date !== undefined && invoice.tran_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.tran_date
									+ "</h4>";
							a.append("<p><br />	Invoice Date</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.due_date !== undefined && invoice.due_date !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.due_date
									+ "</h4>";
							a.append("<p><br />	Due Date</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.curr_code !== undefined && invoice.curr_code !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.curr_code + ' ' + CurrencyFormatted(invoice.ov_amount)
									+ "</h4>";
							a.append("<p><br />Total Invoice (included " + invoice.tax_group_name + ")</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.customer_ref !== undefined && invoice.customer_ref !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.customer_ref
									+ "</h4>";
							a.append("<p><br />Customer Order Ref.</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.shipper_name !== undefined && invoice.shipper_name !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.shipper_name
									+ "</h4>";
							a.append("<p><br />Shipping Company</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.sales_type !== undefined && invoice.sales_type !== "") {
							a = $("<li/>");
							c = "<h4>"
									+ invoice.sales_type
									+ "</h4>";
							a.append("<p><br />Sales Type</p>");
							a.append(c);
							$("#ViewInvoiceDetailsPageDetailsList").append(a)
						}
						
						if (invoice.address !== undefined) {
							f = invoice.address;
							var e = invoice.address;
							d = "http://maps.google.com/?q=" + f + "&t=m&z=13";
							c = $("<li/>");
							f = "<h4>" + f + "<br />" + "<br />" + "</h4>";
							d = $(
									"<a/>",
									{
										href : d,
										rel : "external",
										target : "_new",
										style : "text-decoration:none;color:#444;"
									});
							d
									.append("<p><br />Charge To</p>");
							d.append(f);
							c.append(d);
							$("#ViewInvoiceDetailsPageDetailsList").append(c)
						}
						
						 
					}
			}
			$("#ViewInvoiceDetailsPageDetailsList").listview("refresh")
		});
	
$("#ViewInvoiceDetailsPageOrderItemsListUl li").remove();
	
	var _module = 'INVOICE_ITEMS';
	var _method = 'get_invoice_item_details_by_trans_no';
	var _company = CompanyIndex;
	var _param1 = CurrentInvoiceId;
	var _param2 = '10';

	$.get("../ws/api.php",
		{
			module  : _module,
			method  : _method,
			company : _company,
			param1  : _param1,
			param2  : _param2,
			input_type : "JSON",
			response_type : "JSON"
		},
		function(a) {
			$("#ViewInvoiceDetailsPageOrderItemsListUl").append('<li data-role="list-divider">Line Items</li>');
			if (a !== undefined) {
				a = jQuery.parseJSON(a);
				
				if (a !== undefined && a.results !== undefined)
					if (a.results.length > 0) {
						var b = 0;
						for (b = 0; b <= a.results.length; b++)
							if (a.results[b] !== undefined) {
								var d = a.results[b];
								var stock_id = '';
								
								if (d.line_item_type == 'Services') {
									stock_id = d.service_code;
								} else {
									stock_id = d.product_code;
								}
								
								f = $("<li/>"), e = "<h4>"
										+ d.product_name
										+ "</h4>", g = "<p>"
										+ "</b>Qty: <b>" + d.qty + "</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:<b>" + CurrencyFormatted((d.qty  * d.list_price) * ((100 - d.discount_percent) /100)) + "</b>"
										+ "</p>";
								d = $(
										"<a/>",
										{
											href : "#",
											"data-identity" : stock_id,
											click : function() {
												CurrentProductId = $(this).data("identity");
												$.mobile.changePage("#ViewProductDetailsPage");
												$.mobile.showPageLoadingMsg();
												FaGetProductDetails()
											}
										});
								d.append(e);
								d.append(g);
								f.append(d);
								$("#ViewInvoiceDetailsPageOrderItemsListUl").append(f)
							}
					} else {
						a = $("<li/>");
						a.append("<h4>No Data</h4>");
						$("#ViewInvoiceDetailsPageOrderItemsListUl").append(a)
					}
				$("#ViewInvoiceDetailsPageOrderItemsListUl").listview("refresh")
			}
			$.mobile.hidePageLoadingMsg()
		});
}

function CurrencyFormatted(amount)
{
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0.00; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
    	
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	
	return s;
}