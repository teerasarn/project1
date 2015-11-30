var _contact;

var Contact = function() {

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
  }

  function _setWatchers() {

  }

  function _resize() {

  }
};


$(function() {
	_contact = new Contact();
	_contact.init();
});