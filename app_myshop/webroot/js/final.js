$(document).ready(function () {
	$.fn.modal.Constructor.prototype.enforceFocus = function () {
	};

	try {
		showLocationAndApp();
	} catch (err) {
		console.log('Error - Location Nav could not be displayed: ', err.message);
	}

	try {
		lazyLoadImages();
	} catch (err) {
		console.log('Error - Lazy load images: ', err.message);
	}

	try {
		showServerToastMessages();
	} catch (err) {
		console.log('Error - Toast messages: ', err.message);
	}

	try {
		checkIfShareThisApiIsEnabled();
	} catch (err) {
		console.log('Error - Share feature not available ', err.message);
	}
});