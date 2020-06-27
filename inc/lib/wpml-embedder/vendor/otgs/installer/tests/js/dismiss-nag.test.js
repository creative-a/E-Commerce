var fs = require('fs');

describe('dismiss nag', () => {

	const repo = 'wpml';
	const noticeId = 123;
	let oldAjax;

	beforeEach(() => {
		loadScript();

		oldAjax = jQuery.ajax;
		jQuery.ajax = jest.fn();
	});

	afterEach( () => {
		jQuery.ajax = oldAjax;
	});

	const loadScript = () => {
		// There might be a better way of loading legacy code but I don't know of one.

		const content = fs.readFileSync(__dirname + '/../../res/js/dismiss-nag.js');
		eval(content + '; global.otgs_wp_installer_dismiss_nag = otgs_wp_installer_dismiss_nag');
	};

	it('it sends ajax request', () => {

		document.documentElement.innerHTML = `
			<div class="otgs-is-dismissible">
				<div class="installer-dismiss-nag" 
					data-repository="${repo}" 
					data-notice="${noticeId}">
				</div>
			</div>`;

		otgs_wp_installer_dismiss_nag.init();

		jQuery( '.installer-dismiss-nag' ).trigger('click');

		const dataRecieved = jQuery.ajax.mock.calls[0][0];
		expect(dataRecieved.data.action).toBe( 'installer_dismiss_nag');
		expect(dataRecieved.data.repository).toBe( repo);
		expect(dataRecieved.data.noticeId).toBe( noticeId);

	});

	it('it removes the element on ajax success', () => {

		document.documentElement.innerHTML = `
			<div class="otgs-is-dismissible">
				<div class="installer-dismiss-nag" 
					data-repository="${repo}" 
				</div>
			</div>`;

		otgs_wp_installer_dismiss_nag.init();

		jQuery( '.installer-dismiss-nag' ).trigger('click');

		const dataRecieved = jQuery.ajax.mock.calls[0][0];
		dataRecieved.success();
		expect(jQuery('.otgs-is-dismissible').length).toBe(0);

	});

	it('disables buttons during ajax request', () => {
		document.documentElement.innerHTML = `
			<div class="otgs-is-dismissible">
				<div class="installer-dismiss-nag" 
					data-repository="${repo}" 
					data-notice="${noticeId}">
				</div>
				<a class="button-primary">Register</a>
				<a class="button-secondary">This is a development site</a>
			</div>`;

		otgs_wp_installer_dismiss_nag.init();

		jQuery( '.installer-dismiss-nag' ).trigger('click');

		expect(jQuery( '.button-primary' ).attr('disabled')).toBe('disabled');
		expect(jQuery( '.button-secondary' ).attr('disabled')).toBe('disabled');
	});

});
