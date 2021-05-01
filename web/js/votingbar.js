/* 
 * Öffnet die votingbar in voting
 */

$('#votingbar').metisMenu({
    activeClass:"open",
    toggle: false
});

$("#voting-open-btn").on("click",function(e){e.preventDefault(),$("#app").toggleClass("voting-open")});
$("#voting-collapse-btn").on("click",function(){$("#app").removeClass("voting-open");return false;});
$("#voting-mobile-menu-handle ").swipe({swipeLeft:function(){e.hasClass("voting-open")&&e.removeClass("votingbar-open")},swipeRight:function(){e.hasClass("votingbar-open")||e.addClass("votingbar-open")},triggerOnTouchEnd:!1});
$("#voting-overlay").on("click",function(){$("#app").removeClass("voting-open");});

