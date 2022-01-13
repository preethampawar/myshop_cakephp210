$(document).ready(function () {
	$.fn.modal.Constructor.prototype.enforceFocus = function () {
	};

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
		enableLightBoxForImages()
	} catch (err) {
		console.log('Error - Light box for images: ', err.message);
	}

	try {
		checkIfShareThisApiIsEnabled();
	} catch (err) {
		console.log('Error - Share feature not available ', err.message);
	}
});
