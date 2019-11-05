function navLink( elem ){
	elem = jQuery(elem);
	var url = elem.data('url');
	var page = elem.data('name');
	jQuery('.navbar-nav li.nav-item.active').removeClass('active');
	jQuery('.navbar-nav a[data-name="' + page + '"]').parent().addClass('active');
	jQuery('.container_content').each(function () {
		jQuery(this).hide();
	});
	if( url == '/'){
		jQuery('.container_content.dashboard').show();
	} else {
		var div = page.replace('dns-', '');
		jQuery('.container_content.' + div ).show();
	}
	if ("undefined" !== typeof history.pushState) {
		history.pushState({page: page}, page, url);
	} else {
		window.location.assign(url);
	}
	hideBurger();
}

function hideBurger(){
	var burgerMenu = jQuery('#sidebarToggleTop');
	if( burgerMenu.is(':visible') && burgerMenu.hasClass('show') ){
		burgerMenu.click();
	}
}

function changeDomain(elem) {
	elem = jQuery(elem);
	var domain_name = elem.data('domain_name');
	var domain_id = elem.data('domain_id');
	jQuery.cookie("domain", domain_name, {
		expires : 10,
		path    : '/',
	});
	jQuery.cookie("domain_id", domain_id, {
		expires : 10,
		path    : '/',
	});
	hideBurger();
	jQuery(elem).parents('li.nav-item').find('a.nav-link').click();
}

function showStatus(class_name, text){
	jQuery('.modal-content .status')
		.hide()
		.removeClass('error')
		.removeClass('succes')
		.addClass(class_name)
		.html(text)
		.slideDown('slow');
}

function burgerMenu(){
	var button = jQuery('#sidebarToggleTop');
	var navbar = jQuery('ul.navbar-nav');
	if( button.hasClass('show') ){
		navbar.hide(300);
		button.removeClass('show');
	} else {
		navbar.show(300);
		button.addClass('show');
	}
}

function submitVex(){
	jQuery('.modal-content .spinner-border').show();
	jQuery('.modal-content button.submit').prop('disabled', true);
	var data = {};
	jQuery('.modal-content form input').each(function () {
		data[jQuery(this).attr('id')] = jQuery(this).val();
	});
	var url = window.location.href;
	var hash = url.replace(window.location.origin + '/', '' );
	data['hash'] = hash;
	var class_name = 'error';
	var text = 'Chyba pri odosielaní, skúste znova odoslať formulár';
	if( Object.keys(data).length ){
		jQuery.post( '/ajax/function.php', { data: data })
			.done(function( response ) {
				if( typeof response != 'undefined' ){
					jQuery('.modal-content .spinner-border').hide();
					jQuery('.modal-content button.submit').prop('disabled', false);
					var res;
					try {
						res = JSON.parse(response);
						if ( typeof res.return != 'undefined' && ( typeof res.return.status != 'undefined' || typeof res.return.success != 'undefined' ) ){
							if ( res.return.status == 'success' || res.return.success == 'success' ){
								class_name = 'success';
								text = 'Úspešne uložené.';
								if ( typeof res.html != 'undefined' && res.html != '' ){
									jQuery('#content-wrapper').remove();
									jQuery('#wrapper').append(res.html);
								}
								setTimeout(function () {
									jQuery('.modal-content .close').click();
								}, 3000 );
							} else {
								console.log(res);
							}
						}  else if( typeof res.return != 'undefined' && typeof res.return.error != 'undefined'
							&& typeof res.return.error.content != 'undefined' ){
							text = res.return.error.content;
						}
					}
					catch (e) {
						console.log("error: "+e);
						console.log(response);
						showStatus(class_name, text);
					}
				}
				showStatus(class_name, text);
			});
	} else {
		jQuery('.modal-content .spinner-border').hide();
		jQuery('.modal-content button.submit').prop('disabled', false);
		showStatus(class_name, text);
		console.log(data);
		console.log('Niesu data');
	}
}


function vexDns( elem, type, function_name, content_id = '' ){
	elem = jQuery(elem);
	var data = {};
	var domain = jQuery('.navbar-nav .domain_name').text();
	var user_id = jQuery('.navbar-nav [name="user_id"]').val();
	if ( function_name == 'update' ){
		var tr = elem.parent('tr');
		tr.find('td[data-content]').each( function (){
			content = jQuery(this).data('content');
			value = jQuery(this).text();
			data[content] = value;
		});
	}

	jQuery.post( '/ajax/create_form.php',
		{ type: type, domain: domain, function: function_name, user_id: user_id, content_id: content_id, data: data }
		).done(function( response ) {
		var res;
		try {
			res = JSON.parse(response);
			if ( res.html.length > 0 ){
				var html = res.html;

				jQuery('body').append(res.html);
				jQuery('#modal').modal('show')
					.on('show.bs.modal', function (e) {
						if ( jQuery('.ip4').length ){
							jQuery('.ip4').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation: {'Z': {pattern: /[0-9]/, optional: true}}});
						}
					})
					.on('hidden.bs.modal', function (e) {
						jQuery('#modal').remove();
					});
			} else {
				console.log('data su prazdne');
			}
		}
		catch (e) {
			console.log("error: "+e);
			console.log(response);
			getErrorModal();
		}
		});
}

function getErrorModal(){
	var html = '<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
	html += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">';
	html += '<div class="modal-content">';
	html += '<div class="modal-header">';
	html += '<h5 class="modal-title" id="exampleModalLabel">Chyba</h5>';
	html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
	html += '<span aria-hidden="true">&times;</span>';
	html += '</button>';
	html += '</div>';
	html += '<div class="modal-body">';
	html += '<p class="text-center status error">Chyba pri vytváraní formulára, skúste znova.</p>';
	html += '</div>';
	html += '<div class="modal-footer">';
	html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Zavrieť</button>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	jQuery('body').append(html);
	jQuery('#modal').modal('show');
	jQuery('#modal').on('hidden.bs.modal', function (e) {
		jQuery('#modal').remove();
	});
}



