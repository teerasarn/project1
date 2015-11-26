var _services;

var Services = function() {
  var topWF = 0;
  var wHeight = 0;
  var heightWF = 0;
  var fixed = false;
  var posTop = true;
  var menuHeight = 0;
  var firefox = false;
  
  // publishing functions
  return {
    init: _init
  };
  
  
  
  function _init() {
    menuHeight = 60;
    
    var browser=navigator.userAgent.toLowerCase();
    firefox = (browser.indexOf('firefox') > -1);
    
    _setEvents();

    _setWatchers();
    

    setTimeout(function() {
      _resize();
    },1000);
  }
  
  function _setEvents() {
    $(document).on("websiteResize", function() {
      _resize();
    });
    
    if ( !getGlobal('isMobile') ) {
      $(window).on('scroll', function() {
        _doScroll();
      });
    }
  }
  
  function _doScroll() {
    if ( topWF === 0 ) {
      _resize();
    }
    var top = $(window).scrollTop()+menuHeight;
    
    if ( firefox ) {
      top = $(window).scrollTop();
    }
    if ( top < topWF || top > topWF+heightWF ) {
      if ( fixed ) {
        posTop = ( top < topWF );
        $('.plan-wrapper').css('position','absolute');
        if ( posTop ) {
          $('.wireframe-wrapper').height(heightWF);
          $('.plan-wrapper').css('bottom',0);
        } else {
          $('.wireframe-wrapper').height(0);
          $('.plan-wrapper').css('bottom',(-heightWF)+'px');
        }
        fixed = false;
      }
    } else {
      var position = top - topWF;
      var newHeight = heightWF-position;
      if ( !fixed ) {
        $('.plan-wrapper').css('position','fixed');
        $('.plan-wrapper').css('bottom',-menuHeight+'px');
        fixed = true;
      }
      $('.wireframe-wrapper').height(Math.floor(newHeight));
    }
  }
  
  function _setWatchers() {

  }
  
  function _resize() {
    if ( !getGlobal('isMobile') ) {
      wHeight = $(window).height();
      var offset = $('#service-step-5').offset();
      topWF = offset.top;
      heightWF = $('#service-step-5').height()+300;
      $('.plan-wrapper').height(heightWF);
      $('.wireframe-wrapper').height(heightWF);
      $('.p-wireframe').height(heightWF);
      $('#service-step-5').css('margin-bottom', heightWF);
      //$('#service-end').height(heightWF);
    }
    
    var width = $(window).width();
      
    if ( width < 991 ) {
      $('.renovation .img-placeholder').parent().css('width','33%');
      $('.renovation .fixtures').parent().css('margin-left','16.67%');
      var eWidth = $('.renovation .img-placeholder').parent().width()*.80;
      var eHeight = eWidth;
      $('.renovation  .img-placeholder').width(eWidth);
      $('.renovation  .img-placeholder').height(eHeight);
    } else {
      $('.renovation  .img-placeholder').parent().attr('style','');
      $('.renovation  .img-placeholder').attr('style','');
    }
  }
};

$(function() {
	_services = new Services();
	_services.init();
});
