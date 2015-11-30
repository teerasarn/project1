var _salledebain;

var SalleDeBain = function() {
  
  // publishing functions
  return {
    init: _init
  };
  
  function _init() {  
    _setEvents();

    _setWatchers();
    
    $('.styles-grid').photobox('a',{ time:0, history: false, counter: false, thumbs: true, zoomable: false, beforeShow: _resize });
    $('.mat-grid').photobox('a',{ time:0, history: false, counter: false, thumbs: true, zoomable: false, beforeShow: _resize });
    
    
    setTimeout(function() {
      _resize();
    },2000);
  }
  
  function _setEvents() {
    $(document).on("websiteResize", function() {
      _resize();
    });
    
    $('.styles-grid a').on('click', function() {
      _resize();
    });
    
    $('.mat-grid a').on('click', function() {
      _resize();
    });
  }
  
  function _setWatchers() {

  }
  
  function _resize() {
    var w = $(window).width();
    var h = $(window).height();
    var fix = 30;
    var pad = 0;
    var cols = 3;
    if ( w < 768 ) {
      $('.header-2').attr('style','');
      fix = 6;
      pad = -6;
      cols = 2;
    } else {
      $('.header-2').height($('.header-2 .image-holder').height());
    }
    //$('.styles-grid').height(w);
    $('.to-fix').css('margin-top',(fix/2)+'px');
    $('.styles-grid-row').height(w/2);
    $('.style-1').height(w/4);
    $('.style-2').height(w/4);
    $('.style-3').height(w/2+fix);
    $('.style-4').height(w/2+fix);
    $('.style-5').height(w/4);
    $('.style-6').height(w/2+fix);
    $('.style-7').height(w/4);
    $('.style-8').height(w/4);
    $('#pbOverlay').height($(window).height() - $('.navbar-fixed-top').height());
    $('.pbWrapper').height($('#pbOverlay').height());
    if ( getGlobal('isMobile') && h > w ) {
      //portrait
      var imHeight = w*9/16;
      $('.pbWrapper').width(w);
      $('.pbWrapper').height(imHeight);
      $('#pbCaption').height($('#pbOverlay').height()-imHeight);
      $('#pbOverlay .prevNext').height(imHeight);
    /*} else if ( ((w-280)/1920 * 1080) < $('.pbWrapper').height() ) {
      $('.pbWrapper').width($('.pbWrapper').height()/1080*1920);*/
    } else {
      $('.pbWrapper').width(w-280);
    }
    var mw = w / cols;
    $('.material-cell').width(mw+pad);
    $('.material-cell').height($('.material-cell').width());
  }
};


$(function() {
	_salledebain = new SalleDeBain();
	_salledebain.init();
});
