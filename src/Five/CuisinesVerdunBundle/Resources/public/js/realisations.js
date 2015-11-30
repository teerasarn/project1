var _realisations;

var Realisations = function() {
  
  // publishing functions
  return {
    init: _init
  };
  
  function _init() {  
    _setEvents();

    _setWatchers();
    
    $('.portfolio-grid').photobox('a',{ time:0, history: false, counter: false, thumbs: true, zoomable: false, beforeShow: _resize });
    
    
    setTimeout(function() {
      _resize();      
    },2000);
  }
  
  function _setEvents() {
    $(document).on("websiteResize", _resize )
    
    $('.portfolio-grid a').on('click', _resize );
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
      fix = 6;
      pad = -6;
      cols = 2;
    }
    $('.to-fix').css('margin-top',(fix/2)+'px');
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
    $('.portfolio-cell').height($('.portfolio-cell').width());
  }

};


$(function() {
	_realisations = new Realisations();
	_realisations.init();
});
