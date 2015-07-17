$(document).ready(function () {
	$('.house').each(function () {
		$this = $(this);
		$this.parent().children(':first-child').before($this);
		$this.css('display','block');
	});
});
