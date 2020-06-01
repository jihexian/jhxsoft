var main;
function loaded() {
	main = new iScroll('main', { 
		checkDOMChanges: true,
		vScrollbar: false,
		onBeforeScrollStart: function (e) {
			var target = e.target;
			while (target.nodeType != 1) target = target.parentNode;
			if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
				e.preventDefault();
		}
	});
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
document.addEventListener('DOMContentLoaded', loaded, false);

$(function(){
	$('input,select,textarea').click(function(){
		var _this=this;
		$(window).resize(function(){
			main.refresh()
			main.scrollToElement(_this,500)
		});
	})
});