$(document).ready(function () {
	$('.house').each(function () {
		$this = $(this);
		$this.parent().children('a.avatar').append($this);
		$this.css('display','block');
	});
});
