document.addEventListener('DOMContentLoaded', function() {
	const dropdown = document.querySelector('.dropdown');
	const dropdownContent = document.querySelector('.dropdown-content');
	const dropbtn = document.querySelector('.dropbtn');
	const profileRating = document.querySelector('.profile-rating');

	dropbtn.addEventListener('click', function(event) {
		event.stopPropagation();
		dropdownContent.classList.toggle('show');
		dropbtn.classList.toggle('show');
	});

	window.addEventListener('click', function(event) {
		if (!event.target.matches('.dropbtn')) {
			if (dropdownContent.classList.contains('show')) {
				dropdownContent.classList.remove('show');
				dropbtn.classList.remove('show');
			}
		}
	});

	// Custom tooltip
	profileRating.addEventListener('mouseenter', function() {
		const tooltip = document.createElement('div');
		tooltip.className = 'custom-tooltip';
		tooltip.innerText = "<?php echo $avg_rating; ?> / 5";
		document.body.appendChild(tooltip);

		const rect = profileRating.getBoundingClientRect();
		tooltip.style.left = `${rect.left + window.scrollX}px`;
		tooltip.style.top = `${rect.bottom + window.scrollY}px`;

		profileRating.addEventListener('mouseleave', function() {
			document.body.removeChild(tooltip);
		}, { once: true });
	});
});