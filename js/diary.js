if(typeof activeYear === 'undefined' && typeof activeMonth === 'undefined') {
	var activeYear = null; // Store the active year for the diary
	var activeMonth = null; // Store the active month for the diary
}

$(document).ready(function() {
	// Diary
	yearsSlider();
	initMonths();
	getMonthStrip();
	detectHashChange();
});
/**
 * Create scrollable navigation carousel for the Year's Navigation
 */
function yearsSlider()
{
	var frame  = $('.years-navigation-carousel .frame');
	var slidee = frame.children('ul').eq(0);
	var wrap   = $('.years-navigation-carousel').parent();
	frame.sly({
		horizontal: 1,
		itemNav: 'basic',
		smart: 1,
		activateOn: 'click',
		mouseDragging: 1,
		touchDragging: 1,
		releaseSwing: 1,
		startAt: 0,
		scrollBar: wrap.find('.scrollbar'),
		scrollBy: 1,
		activatePageOn: 'click',
		speed: 300,
		elasticBounds: 1,
		easing: 'easeOutExpo',
		dragHandle: 1,
		dynamicHandle: 1,
		clickBar: 1,
		// Buttons
		// forward: wrap.find('.forward'),
		// backward: wrap.find('.backward'),
		//prev: wrap.find('.prev'),
		//next: wrap.find('.next'),
		prevPage: wrap.find('.prevPage'),
		nextPage: wrap.find('.nextPage')
	});
}
/**
 * Function for handling the Years & Months on the diary view
 */
function initMonths()
{
	var yearsNav = $('.years-navigation');
	// if(document.location.href.indexOf('the-diary') > -1 && getFromHash('year')) {

	// 		if(document.location.href.indexOf('sidebar') == -1) {
	// 			// Load the first year on page load
	// 			var initialActiveYear = yearsNav.find('li:first a');
	// 			// Update hashbang for navigation
	// 			activeYear = initialActiveYear.text();
	// 			updateHashURL();
	// 		}
	// 	}
	// 	if(document.location.href.indexOf('sidebar') == -1) {
	// 		var initialActiveYearID = initialActiveYear.attr('data-year-id');
	// 		getMonths(initialActiveYearID);  
	// 	}
	// }
	// if(document.location.href.indexOf('sidebar') > -1) {
	// 	// Display the sidebar strips
	// 	displayDiaryStrip();
	// }

	if($('.diary-strip-post').length > 0) {
		// On page load, ensure the default diary strip is displaying correctly
		displayDiaryStrip();
	}

	// When a year is clicked, make an AJAX call to get the month's for the chosen year
	$('.get-months').click(function(event) {
		event.preventDefault();
		var anchor = $(this);
		var yearID = anchor.attr('data-year-id');
		// Update hashbang for navigation
		activeYear = anchor.text();
		updateHashURL();
		yearsNav.find('.year-active').removeClass();
		anchor.parent('li').addClass('year-active');
		$('.months-navigation ul').finish().animate({
			opacity: 0,
			top: '-5px'
		}, 250, 'swing');
		getMonths(yearID, false);
	});
}
function getMonths(yearID)
{
	var monthsNav = $('.months-navigation');
	// Hide existing month strip
	hideMonthStrip();
	$.get('/wp-json/diary/get-months-for-year/' + yearID, function(months) {
		monthsNav.find('li').remove();
		for (var m = 0; m < months.length; m++) {
			var month = months[m];
			var month_shorthand = month.month_shorthand;

			var empty_month_html = '<li class="' + month_shorthand.toLowerCase() + '">' + month_shorthand + '</li>';
			var month_has_posts_html = '<li class="' + month_shorthand.toLowerCase() + ' has-posts"><a href="#" class="get-month-strip" data-month-id="' + month.id + '" data-month="' + month_shorthand + '">' + month_shorthand + '</a></li>';

			if(month.hidden) {
				var html = empty_month_html;
			} else if(month.id && month.description != '' || month.id && month.post_count > 0) {
				var html = month_has_posts_html;
			} else {
				var html = empty_month_html;
			}
			
			monthsNav.find('ul').append(html).animate({
				opacity: 1,
				top: 0
			}, 250, 'jswing');
		}
		if(getFromHash('month') && monthsNav.find('a[data-month="' + getFromHash('month') + '"]').length == 1) {
			// Load the month from the hash
			activeMonth = getFromHash(month);
			monthsNav.find('a[data-month="' + getFromHash('month') + '"]').click();
		} else {
			// Load first available month once a year is clicked
			monthsNav.find('li').each(function() {
				var anchor = $(this).find('a');
				if(anchor.length == 1) {
					anchor.click();
					return false;
				} else {
					// No month found
					activeMonth = '';
					updateHashURL();
				}
			});
		}
	});
}
function getMonthStrip()
{
	var monthsNav = $('.months-navigation');
	var diaryStripContainer = $('.diary-strip-container');
	$('body').on('click', '.get-month-strip', function(event) {
		event.preventDefault();
		$('.intro').fadeOut();
		// Hide existing month strip
		hideMonthStrip();
		var anchor = $(this);
		var monthID = anchor.attr('data-month-id');
		activeMonth = anchor.text();
		updateHashURL();
		monthsNav.find('li.active').removeClass('active');
		anchor.parent().addClass('active');
		$.get('/wp-json/diary/get-months-strip/' + monthID, function(html) {
			diaryStripContainer.append(html).imagesLoaded().then(function() {
				displayDiaryStrip();
			});
		});
	});
}
function displayDiaryStrip()
{
	var strip = $('.diary-strip');
	// Match height of diary strip posts
	var heights = [];	
	strip.find('.diary-strip-post').each(function(i) {
		heights.push($(this).find('.post-content').outerHeight(true));
		if($(this).css('opacity') != '1') {
			$(this).delay(i*250).animate({
				opacity: 1,
				top: 0
			}, 250, 'jswing');	
		}
	});
	// Custom scrollbar
	// strip.niceScroll({
	// 	autohidemode: false,
	// 	cursorcolor: '#000',
	// 	cursoropacitymax: '.4',
	// 	cursorwidth: '8px',
	// 	cursorborderradius: 0,
	// 	background: 'rgba(0,0,0,0.2)'
	// });
	strip.find('.post-content').css('height', Math.max.apply(Math, heights) + 'px');	
}
function detectHashChange()
{
	var yearsNav = $('.years-navigation');
	$(window).on('hashchange', function(e) {
		var newYear = getFromHash('year');
		var newMonth = getFromHash('month');
		if(newYear != activeYear && yearsNav.find('a[data-year="' + getFromHash('year') + '"]').length == 1) {
			activeYear = getFromHash('year');
			// On page load, check whether to load months based on the year within the #hash
			newActiveYear = yearsNav.find('a[data-year="' + getFromHash('year') + '"]');
			// Update active year within nav
			yearsNav.find('.year-active').removeClass();
			newActiveYear.parent('li').addClass('year-active');
			var yearIndex = newActiveYear.parent('li').index();
			// Go to year within carousel
			$('.years-navigation-carousel .frame').sly('toStart', [yearIndex]);
			var newActiveYearID = newActiveYear.attr('data-year-id');
			getMonths(newActiveYearID);  
		}
	});
}
function hideMonthStrip()
{
	$('.diary-strip').fadeOut(function() {
		$(this).remove();
	});
}
function updateHashURL()
{
	var hash = activeYear + (activeMonth != '' ? '/' + activeMonth : '');
	if(window.history.pushState) {
	    //history.pushState({}, null, hash);
	    window.history.pushState(null, null, '/the-diary/' + hash);
	    console.log('test2');
	} else {
	    window.location.href = '/the-diary/' + hash;
	}
}
function getFromHash(name)
{
	var href = window.location.href;
	var hash = href.split('wynnesdiary.com/the-diary/')[1];

	console.log(hash);

	if(hash != '') {
		var hash = hash.split('/');
	} else {
		return false;
	}

	if(name == 'year' && hash[0] != 'null') {
		return hash[0];
	}
	if(name == 'month' && hash[1] != 'null') {
		return hash[1];
	}
}
/**
 * Helpers
 */
// Fn to allow an event to fire after all images are loaded
$.fn.imagesLoaded = function () {
    // get all the images (excluding those with no src attribute)
    var $imgs = this.find('img[src!=""]');
    // if there's no images, just return an already resolved promise
    if (!$imgs.length) {return $.Deferred().resolve().promise();}
    // for each image, add a deferred object to the array which resolves when the image is loaded (or if loading fails)
    var dfds = [];  
    $imgs.each(function(){
        var dfd = $.Deferred();
        dfds.push(dfd);
        var img = new Image();
        img.onload = function(){dfd.resolve();}
        img.onerror = function(){dfd.resolve();}
        img.src = this.src;
    });
    // return a master promise object which will resolve when all the deferred objects have resolved
    // IE - when all the images are loaded
    return $.when.apply($,dfds);
}
