/* 
 * Öffnet die Pagebar in page/edit (pagebar html in main
 */

$('#pagebar').metisMenu({
    activeClass:"open",
    toggle: false
});

$("#pagebar-open-btn").on("click",function(e){e.preventDefault(),$("#app").toggleClass("pagebar-open")});
$("#pagebar-collapse-btn").on("click",function(){$("#app").removeClass("pagebar-open");return false;});
$("#sidebar-mobile-menu-handle ").swipe({swipeLeft:function(){e.hasClass("pagebar-open")&&e.removeClass("pagebar-open")},swipeRight:function(){e.hasClass("pagebar-open")||e.addClass("pagebar-open")},triggerOnTouchEnd:!1});
$("#sidebar-overlay").on("click",function(){$("#app").removeClass("pagebar-open");});

