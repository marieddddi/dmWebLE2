$(document).ready(function () {
	$("#logo1").click(function () {
		$("#navigation").toggleClass("open");
	});

	$("#logo3").click(function () {
		$("#navigation").removeClass("open");
	});
});
