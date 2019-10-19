// No JS library used

;(function(document, window, index) {
    'use strict';

    var elSelector = 'header',
        element = document.querySelector(elSelector);

    if (!element) return true;

    var elHeight = 0,
        elTop = 0,
        dHeight = 0,
        wHeight = 0,
        wScrollCurrent = 0,
        wScrollBefore = 0,
        wScrollDiff = 0;

    window.addEventListener('scroll', function() {
        elHeight = element.offsetHeight;
        dHeight = document.body.offsetHeight;
        wHeight = window.innerHeight;
        wScrollCurrent = window.pageYOffset;
        wScrollDiff = wScrollBefore - wScrollCurrent;
        elTop = parseInt(window.getComputedStyle(element).getPropertyValue('top')) + wScrollDiff;

        if (wScrollCurrent <= 0)
            element.style.top = '0px';

        else if (wScrollDiff > 0)
            element.style.top = (elTop > 0 ? 0 : elTop) + 'px';

        else if (wScrollDiff < 0) {
            if (wScrollCurrent + wHeight >= dHeight - elHeight)
                element.style.top = ((elTop = wScrollCurrent + wHeight - dHeight) < 0 ? elTop : 0) + 'px';

            else
                element.style.top = (Math.abs(elTop) > elHeight ? -elHeight : elTop) + 'px';
        }

        wScrollBefore = wScrollCurrent;
    });

}(document, window, 0));

// fixed header

var scrollScript = function(){
            var scrollTop = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
            if (scrollTop >= 107) {
                document.getElementById("header").className = 'fixedheader'
            } else {
                document.getElementById("header").className = ''
            }
};

document.addEventListener("scroll", scrollScript);

function addClass(element,className) {
  var currentClassName = element.getAttribute("class");
  if (typeof currentClassName!== "undefined" && currentClassName) {
    element.setAttribute("class",currentClassName + " "+ className);
  }
  else {
    element.setAttribute("class",className); 
  }
}
function removeClass(element,className) {
  var currentClassName = element.getAttribute("class");
  if (typeof currentClassName!== "undefined" && currentClassName) {

    var class2RemoveIndex = currentClassName.indexOf(className);
    if (class2RemoveIndex != -1) {
        var class2Remove = currentClassName.substr(class2RemoveIndex, className.length);
        var updatedClassName = currentClassName.replace(class2Remove,"").trim();
        element.setAttribute("class",updatedClassName);
    }
  }
  else {
    element.removeAttribute("class");   
  } 
}

// enquiry
jQuery('.enquiry-btn').on('click', function(e) {
    jQuery('body').addClass('enquiry-open');
    e.preventDefault();
   
});

jQuery('.enquiry-form .close').on('click', function(e) {
  jQuery('body').removeClass('enquiry-open');
  e.stopPropagation();
});
// menu
jQuery('.menu-button').on('click', function(e) {
     jQuery('body').toggleClass('open-menu');
    e.preventDefault();
   
});
jQuery('.footer-btn').on('click', function(e) {
     jQuery('body').addClass('enquiry-open');
    e.preventDefault();
   
});
// service
// enquiry
jQuery('.home-services .box').on('click', function(e) {
  jQuery('.service-expand').removeClass('open');
   jQuery(this).find('.service-expand').addClass('open');
    /*e.preventDefault();*/
   
});
jQuery('.home-services').on('click','.service-expand.open .service-tilte', function(e) {
 // window.location = $(this).attr('href');
  e.preventDefault();
 });
jQuery('.home-services .service-expand .service-tilte').on('click', function(e) {
  e.preventDefault();
 });

jQuery('.service-expand .close').on('click', function(e) {
  jQuery('.service-expand').removeClass('open');
 e.stopPropagation();
});


jQuery(function($) {
  
  // Function which adds the 'animated' class to any '.animatable' in view
  var doAnimations = function() {
    
    // Calc current offset and get all animatables
    var offset = jQuery(window).scrollTop() + jQuery(window).height(),
        $animatables = jQuery('.animatable');
    
    // Unbind scroll handler if we have no animatables
    if ($animatables.size() == 0) {
      jQuery(window).off('scroll', doAnimations);
    }
    
    // Check all animatables and animate them if necessary
    $animatables.each(function(i) {
       var $animatable = jQuery(this);
      if (($animatable.offset().top + $animatable.height() - 100) < offset) {
        $animatable.removeClass('animatable').addClass('animated');
      }
    });

  };
  
  // Hook doAnimations on scroll, and trigger a scroll
  jQuery(window).on('scroll', doAnimations);
  jQuery(window).trigger('scroll');

});

jQuery(document).ready(function () {
 // $(".submenu>li").hoverIntent(function () {
 //            $(this).parent("ul").find('.active').removeClass('active');

 //            if ($(this).children('ul').size()) {
 //                $(this).addClass("active");


 //            }
 // }); 
 
  jQuery('li.archive-accordion-year').click(function() {
      // Change CSS of current year
      jQuery('li.archive-accordion-year').not(this).children('ul').slideUp(250);
      jQuery(this).children('ul').slideToggle(250);
    
  });
   jQuery(".submenu").hover(function(){
                    jQuery(this).parents("li").find("a").addClass("navActive2");
                    jQuery(this).children("li").find("a").removeClass("navActive2");
                },
                function(){
                    jQuery(this).parents("li").find("a").removeClass("navActive2");
                    jQuery(this).children("li").find("a").removeClass("navActive2");
            });
   jQuery(".menu-list-2").hover(function(){
                    jQuery(this).parents("li").find(">a").addClass("navActive2");
                    jQuery(this).children("li").find(">a").removeClass("navActive2");
                },
                function(){
                    jQuery(this).parents("li").find(">a").removeClass("navActive2");
                    jQuery(this).children("li").find(">a").removeClass("navActive2");
            });

     jQuery("nav .icon").click(function(e){
            jQuery(this).toggleClass("open");
            jQuery(this).parent('li').children(".submenu").slideToggle();
            e.preventDefault();

       });
  }); 

