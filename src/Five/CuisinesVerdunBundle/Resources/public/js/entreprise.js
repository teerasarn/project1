var _entreprise;

var Entreprise = function() {
  
  // publishing functions
  return {
    init: _init
  };
  
  function _init() {
    _setEvents();

    _setWatchers();
    
    setTimeout(function() {
      _resize();
    },2000);
  }

  
  function _setEvents() {
    $(document).on("websiteResize", function() {
      _resize();
    });
    
    $('.history-text h2').on('click', function() {
      var width = $(window).width();
    
      if ( $('#history').hasClass('opened') ) {
        $('#history').removeClass('opened');
        if ( width <= 768 ) {
          var titleHeight = $('.history-text h2').height() + 20;
          $('.history-text').height(titleHeight);
        }
      } else {
        $('#history').addClass('opened');
        if ( width <= 768 ) {
          $('.history-text').attr('style','');
        }
      }
      _resize();
    });
  }
  
  function _setWatchers() {

  }
  
  function _resize() {
    var width = $(window).width();
    if ( width < 768 ) {
      $('.history-image').height($('.history-image').width());
      if ( !$('#history').hasClass('opened') ) {
        var titleHeight = $('.history-text h2').height() + 20;
        $('.history-text').height(titleHeight);
      }
    } else {
      var titleHeight = $('.history-text h2').height() + 75;
      $('#history').height(titleHeight);
      $('#history.opened').height($('.history-text').height()+60);
    }
    
  }
};


$(function() {
	_entreprise = new Entreprise();
	_entreprise.init();
});