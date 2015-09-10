function equalsSidebarAndMain() {
	var sidebar = $('#sidebar');
	var main = $('#site > main');

	main.css('min-height', '0px');
	sidebar.css('min-height', '0px');

	var height = Math.max(sidebar.height(), main.height());
	main.css('min-height', height);
	sidebar.css('min-height', height);
}

$(function(){ // Wait that the page is ready
	//Navigation
	$('nav#main-nav h1').click(function() {
		$(this).next('ul').toggle();
		if ($(this).next('ul').is(":visible")) {
			$(this).addClass('open');
		} else {
			$(this).removeClass('open');
		}
		equalsSidebarAndMain();
	});

    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
});

$(window).load(function() { // Wait that all the images are loaded
	// Make the sidebar and the main content the same height
	equalsSidebarAndMain();
});

// Google Analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-58722163-1', 'auto');
ga('send', 'pageview');

// Google+
window.___gcfg = {lang: '<? echo strtolower($_SESSION["__langue__"]);?>'};

(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/platform.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();

(function() {
	$('#footer').css('top', $('#footer').outerHeight());

	var contentHeight = $(window).height() - $('#header').outerHeight() - $('#footer').outerHeight();
	$('#contenu').css('min-height', contentHeight);
})();