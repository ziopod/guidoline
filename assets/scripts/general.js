
$(function(){
		document.body.className = document.body.className.replace("no-js","js");
		$('.modale').nyroModal();
		$('.tip').tooltipster({position: 'bottom'});
		$('.tipleft').tooltipster({position: 'left'});
		$("#js-main-menu").mmenu();
});