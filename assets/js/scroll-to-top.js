(function () {
	'use strict';

	var button = document.getElementById('lsw-scroll-to-top');
	if (!button) {
		return;
	}

	var scrollOffset = parseInt(button.getAttribute('data-scroll-offset'), 10) || 300;
	var ticking = false;

	function toggleVisibility() {
		if (window.scrollY > scrollOffset) {
			button.classList.add('is-visible');
		} else {
			button.classList.remove('is-visible');
		}
		ticking = false;
	}

	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(toggleVisibility);
			ticking = true;
		}
	}

	function scrollToTop() {
		var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		window.scrollTo({
			top: 0,
			behavior: prefersReducedMotion ? 'auto' : 'smooth',
		});
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	button.addEventListener('click', scrollToTop);
	toggleVisibility();
})();
