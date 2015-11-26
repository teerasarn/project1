
var _website;
var currentHash = (window.location.hash) ? window.location.hash.split("#")[1] : null;

var WebSite = function() {
  var succursale = -1;
  var map;
  var mapInfowindows = [];
  var mapMarkers = [];
  var geocoder;
  var directionsService;
  var directionsDisplay;
  var lang;
  var lat = 45.5088400;
  var long = -73.5878100;
  var softClick = false;


  // publishing functions
  return {
    init: _init,
    debounce: _debounce,
    getDirections: _getDirections
  };

  /**
   * Initialization of global behaviours, including the forms
   * @returns nothing
   */
  function _init() {

    _resize();

    _setGlobalEvents();
    loadProjects();

    lang = 'en';
    if ($('body').hasClass('fr')) {
      lang = 'fr';
    }
    $('#f-mg-succursale').dropkick({
      change: function(value, label) {
        _changeSuccursale(value);
      }
    });
    $('#f-ap-succursale').dropkick({
      change: function(value, label) {
        _changeSuccursale(value);
      }
    });
    $('#f-sb-succursale').dropkick({
      change: function(value, label) {
        _changeSuccursale(value);
      }
    });

    /* Portfolio dropdown */
    $('#sel_rooms').dropkick({
      change: function(value, label) {
        //alert('hello!');
        //_changeSuccursale(value);
        //loadProjects();
        loadProjects();
      }
    });

    $('#sel_styles').dropkick({
      change: function(value, label) {
        //alert('hello 2');
        //_changeSuccursale(value);
        //loadProjects();
        loadProjects();
      }
    });
    var picGridInit = false;
  function loadProjects() {
    var room = $("#sel_rooms").val();
    var style = $("#sel_styles").val();
    $.ajax({
      url: "/api/" + language + "/gallery/realisations?categories[room]=" + room + "&categories[style]="+ style,
      beforeSend: function( xhr ) {
        xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
      }
    })
      .done(function( data ) {
        //console.log('data',data);
        var projectsWrapper = $(".portfolio-grid:eq(0)");
        $(".portfolio-grid:eq(0)").contents().remove();
        var projects = JSON.parse(data);
        var galleries = projects.galleries;//projects.gallery.images;
        var result = "";
        //projectsWrapper.html("");
        for (var k=0;k < galleries.length; k++) {
          var imgLen = galleries[k].images.length;
          result += '<div class="col-xs-6 col-sm-6 col-md-4 project-cell" style="background-image: url('+ galleries[k]['cover'] + '); background-size: cover;overflow:hidden;">';
          for (var i=0;i < imgLen; i++) {
          //result += '<div class="col-xs-6 col-sm-6 col-md-4 portfolio-cell inner-shadow" style="background-image: url('+ galleries[i]['src'] + '); background-size: cover">'
              if( i == 0 )
              {


                result += '<div class="portfolio-cell borderwrap" style=" background-size: cover;overflow:hidden;">'
                      +  '<div class="gallery-link">'
                      +    '<a href="'+ galleries[k].images[i]['src'] + '"><img src="'+ galleries[k].images[i]['src_thumb'] + '" title="&lt;h4&gt;' + galleries[k].images[i]['title']+'&lt;/h4&gt;' + galleries[k].images[i]['description']+'">'
                      +      '<p>' + galleries[k]['title'] + '</p>'
                      +    '</a>'
                      +  '</div>'
                      + '</div>';
/*                result += '<div class="portfolio-cell" style="visibility:hidden;">'
                      +  '<div class="">'
                      +    '<a href="'+ galleries[k].images[i]['src'] + '"><img src="'+ galleries[k].images[i]['src_thumb'] + '" title="&lt;h4&gt;' + galleries[k].images[i]['title']+'&lt;/h4&gt;' + galleries[k].images[i]['description']+'">'
                      +      '<p>' + galleries[k].images[i]['title'] + '</p>'
                      +    '</a>'
                      +  '</div>'
                      + '</div>'; */
              }
              else
              {
                result += '<div class="portfolio-cell" style="position:absolute;top:0px;z-index:-1;opacity:0;visibility:hidden;">'
                      +  '<div class="gallery-link">'
                      +    '<a href="'+ galleries[k].images[i]['src'] + '"><img src="'+ galleries[k].images[i]['src_thumb'] + '" title="&lt;h4&gt;' + galleries[k].images[i]['title']+'&lt;/h4&gt;' + galleries[k].images[i]['description']+'">'
                      +      '<p>' + galleries[k].images[i]['title'] + '</p>'
                      +    '</a>'
                      +  '</div>'
                      + '</div>';
              }
            }
            result += '</div>';
        }
       projectsWrapper.append(result);
       //$('.portfolio-grid').photobox('prepareDOM');
       $('.portfolio-grid').photobox('destroy');
       $('.portfolio-grid').photobox('.gallery-link a',{ time:0, history: false, counter: false, thumbs: true, zoomable: false, beforeShow: _resize });   
       
       _real_resize()
      });
    };

    function _real_resize() {
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
    } else if ( ((w-280)/1920 * 1080) < $('.pbWrapper').height() ) {
      $('.pbWrapper').width($('.pbWrapper').height()/1080*1920);
    } else {
      $('.pbWrapper').width(w-280);
    }
    var mw = w / cols;
    $('.portfolio-cell').height($('.portfolio-cell').width());
  }


    $('input[placeholder], textarea[placeholder]').placeholder();

    $('button[data-form-link="form-message-general"]').trigger('click');

    $('a.pencil').on('click', function(){
      $('button[data-form-link="form-message-general"]').trigger('click');
    });

    $('a.phone').on('click', function(){
      $('button[data-form-link="form-soumission-cuisine"]').trigger('click');
    });

    _initMap();

  }

  function _initMap() {
    var userZoom = 10;

    var userPos = new google.maps.LatLng(lat, long);

    /*if (navigator.geolocation) {
      success = function(position) {
        lat = position.coords.latitude;
        long = position.coords.longitude;
        userPos = new google.maps.LatLng(lat, long);
        _setupMap(userZoom, userPos);
      };
      error = function() {
        _setupMap(userZoom, userPos);
      };
      navigator.geolocation.getCurrentPosition(success, error);
    } else {*/
      _setupMap(userZoom, userPos);
    //}
  }

  function _setupMap(userZoom, userPos) {
    var mapStyles = [
      {
        "stylers": [
          {"saturation": -100},
          {"lightness": 35},
          {"gamma": 0.86}
        ]
      }
    ];

    var styledMap = new google.maps.StyledMapType(mapStyles, {name: "Cuisines_Verdun"});

    var mapOptions = {
      scrollwheel: false,
      zoom: userZoom,
      center: userPos,
      panControl: false,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      },
      scaleControl: true,
      mapTypeControl: false,
      streetViewControl: false,
      overviewMapControl: false
    };

    map = new google.maps.Map(document.getElementById('carte-wrapper'), mapOptions);
    map.mapTypes.set('map_styled', styledMap);
    map.setMapTypeId('map_styled');

    _setMarkers(map, locations);

    geocoder = new google.maps.Geocoder();
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer({
      suppressMarkers: false
    });
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));

    google.maps.event.addListener(map, 'zoom_changed', function() {
      var minZoomLevel = 9;
      var maxZoomLevel = 16;

      var zoom = map.getZoom();

      if (zoom < minZoomLevel) {
        map.setZoom(minZoomLevel);
        return;
      }
      if (zoom > maxZoomLevel) {
        map.setZoom(maxZoomLevel);
        return;
      }

    });
  }

  function _setMarkers(map, locations) {
    var image = {
      url: getGlobal('assets_path') + 'img/map-marker.png',
      size: new google.maps.Size(56, 66),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(28, 55)
    };

    var shape = {
      coords: [12, 11, 12, 55, 45, 55, 45, 11],
      type: 'poly'
    };
    for (var i = 0; i < locations.length; i++) {
      var branch   = locations[i];
      var myLatLng = new google.maps.LatLng(branch.x, branch.y);
      var hours    = branch.hours.replace(/&gt;/g, '>').replace(/&lt;/g, '<');
      var content  = branch.address.replace('XXXXX', hours).replace(/&gt;/g, '>').replace(/&lt;/g, '<');

      var popupBubbleWidth = ( branch.title.length >= 18 ? 540 : 540 );
      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        shape: shape,
        title: branch.title,
        content: '<div style="clear:both;min-width:'+popupBubbleWidth+'px;max-height:280px;min-height:200px;overflow:auto;">' + content + '</div>',
        id: branch.id
      });

      mapMarkers.push(marker);


    }

    for (var i = 0; i < mapMarkers.length; i++) {
      google.maps.event.addListener(mapMarkers[i], 'click', function() {
        if (mapInfowindows.length > 0) {
          for (var j = 0; j < mapInfowindows.length; j++) {
            var image = {
              url: getGlobal('assets_path') + 'img/map-marker.png',
              size: new google.maps.Size(56, 66),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(28, 55)
            };
            mapInfowindows[j].marker.setIcon(image);
            mapInfowindows[j].close();
          }
          mapInfowindows = [];

        }
        marker = this;
        var image = {
          url: getGlobal('assets_path') + 'img/map-marker-sel.png',
          size: new google.maps.Size(56, 66),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(28, 55)
        };
        marker.setIcon(image);

        var infowindow = new google.maps.InfoWindow({
          content: marker.content,
          marker: marker,
          maxWidth: 800
        });
        infowindow.open(map, marker);

        $(".address").html(_replacePhoneNumbers($(".address").html()));

        if (!softClick) {
          //console.log( 'Marker click + changeSucc', marker.content )
          softClick = true;
          _changeSuccursale(marker.id);
        }
        window.forceUpdateEditor();
        bindPhoneEvents();
        //console.log( 'Marker click ', marker.content )
        softClick = false;
        mapInfowindows.push(infowindow);
        google.maps.event.addListener(infowindow, 'closeclick', function() {
          var image = {
            url: getGlobal('assets_path') + 'img/map-marker.png',
            size: new google.maps.Size(56, 66),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(28, 55)
          };
          marker.setIcon(image);
        });
        mapMarkers[i] = marker;
      });

    }
  }

  function _getDirections(lat, lng) {
    /*
    if ($('#f-mg-cp').val() == '') {
      var latlng = new google.maps.LatLng(lat, long);
      geocoder.geocode({
        'location': latlng
      },
      function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var origin = results[0].geometry.location;
          var destination = new google.maps.LatLng(lat, lng);

          var request = {
            origin: origin,
            destination: destination,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
          };

          directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            }
          });

        } else {
          alert("Directions cannot be computed at this time.");
        }
      });
    } else {
      geocoder.geocode({
        'address': document.getElementById('f-mg-cp').value
      },
      function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var origin = results[0].geometry.location;
          var destination = new google.maps.LatLng(lat, lng);

          var request = {
            origin: origin,
            destination: destination,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
          };

          directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            }
          });

        } else {
          alert("Directions cannot be computed at this time.");
        }
      });
    }
    */
  }

  function _changeSuccursale(value) {
    if ( softClick ) {
      //console.log( 'NO _changeSuccursale' );
      softClick = false;
      return;
    }
    //console.log( 'YES _changeSuccursale' );
    if (value != succursale) {
      succursale = value;
      $('.liste-succursales a').removeClass('selected');

      $('a[data-loc-id=' + value + ']').addClass('selected');

      $('#f-mg-succursale').val(value);
      $('#f-ap-succursale').val(value);
      $('#f-sb-succursale').val(value);
      $('#f-mg-succursale').dropkick('refresh');
      $('#f-ap-succursale').dropkick('refresh');
      $('#f-sb-succursale').dropkick('refresh');

      var found = false;
      for (var i = 0; i < mapMarkers.length; i++) {

        if (mapMarkers[i].id == value && !found) {
          softClick = true;
          google.maps.event.trigger(mapMarkers[i], 'click');
          found = true;
          break;
        }
      }
    }
  }

  function _setGlobalEvents() {
    if(!('ontouchstart' in window)) {
      $('[data-toggle="tooltip"]').tooltip({html: true, trigger: 'hover'});
    }
    
    
    $(window).resize(_debounce(function(event) {
      _resize();
    }, 300));
   
   // $(window).resize(_resize);
   
    //if ( $('html').hasClass('lt-ie9') ) {
    $('.logirenov-over').css('cursor', 'pointer');
    $('.logirenov-over').on('click', function() {
      var win = window.open($('.logirenov-over a').attr('href'), '_blank');
      win.focus();
    });

    //}

    $(window).on('scroll', function() {
      if ($(this).scrollTop() > 33 && $('.navbar-fixed-top').hasClass('tall-menu')) {
        $('.navbar-fixed-top').removeClass('tall-menu');
        _resize();
      } else if ($(this).scrollTop() <= 33 && !$('.navbar-fixed-top').hasClass('tall-menu')) {
        $('.navbar-fixed-top').addClass('tall-menu');
        _resize();
      }
    });

    $('.form-choices button').on('click', function() {
      $('.form-choices button').removeClass('btn-success');
      $('.form-choices button').addClass('btn-default');
      $(this).addClass('btn-success');
      $('.contact-form').css('display', 'none');
      $('#' + $(this).attr('data-form-link')).css('display', 'block');
      /*var offset = $('.form-choices').offset();
       $('html,body').animate({
       scrollTop: offset.top-60
       }, 500);*/
    });

    $('button[data-form-link="form-message-general"]').on('click', function() {
        ga('send', 'event', 'button', 'click', 'getaquote', 1);
    });
    $('button[data-form-link="form-soumission-cuisine"]').on('click', function() {
        ga('send', 'event', 'button', 'click', 'contactme', 1);
    });

    $('.liste-succursales a').on('click', function(e) {
      e.preventDefault();
      var locId = $(this).attr('data-loc-id');
      _changeSuccursale(locId);
      window.forceUpdateEditor();
      bindPhoneEvents();

    });

    $('input[type=text]').on('blur', function() {
      var id = $(this).attr('id');
      var value = $(this).val();

      var f = id.split('-');
      if (f[1] == 'mg') {
        $('#' + f[0] + '-sc-' + f[2]).val(value);
        $('#' + f[0] + '-sb-' + f[2]).val(value);
      } else if (f[1] == 'sc') {
        $('#' + f[0] + '-mg-' + f[2]).val(value);
        $('#' + f[0] + '-sb-' + f[2]).val(value);
      } else {
        $('#' + f[0] + '-sc-' + f[2]).val(value);
        $('#' + f[0] + '-mg-' + f[2]).val(value);
      }
      if (f[2] === 'cp') {
        var url = "/api/" + language + "/fetch_branch/" + value.substring(0, 3);
        $.ajax({
          type: "GET",
          url: url
        }).done(function(response) {
          if (response.success) {
            _changeSuccursale(response.branch);
          }
        });

      }
    });

    $('#f-mg-submit').on('click', function() {
      $('#general-message').submit();
    });
    $('#f-sc-submit').on('click', function() {
      $('#soumission-cuisine').submit();
    });
    $('#f-sb-submit').on('click', function() {
      $('#soumission-bain').submit();
    });

    var _this = this;
    _this[ 'generalMsgFormSubmitting' ]   = false;
    _this[ 'kitchenQuoteFormSubmitting' ] = false;

    $('#general-message').on('submit', function() {

      $('#general-message .alert').remove();
      var url = "/api/" + language + "/message_submit";

      if (_validate('mg')) {
        if( _this[ 'generalMsgFormSubmitting' ] == false )
        {
            // Quick fix to prevend double submit
            _this[ 'generalMsgFormSubmitting' ] = true;

          $.ajax({
            type: "POST",
            url: url,
            data: $("#general-message").serialize()
          }).done(function(response) {
            _this[ 'generalMsgFormSubmitting' ] = false;
            if (response.success) {
              ga('send', 'event', 'button', 'click', 'getaquote-submit', 1);
              
              // Clear the form
              var fields = ['#f-mg-lastname', '#f-mg-firstname', '#f-mg-telephone', '#f-mg-cp', '#f-mg-email', '#f-mg-message' ];
              $.each( fields, function( index, key )
              {                
                if( $( key ) )
                {
                  $( key ).val( '' );
                }
              } );

              $( '#f-mg-succursale' ).val( -1 ).change();
              $('#f-mg-day').prop( 'checked', false ).change();
              $('#f-mg-night').prop( 'checked', false ).change();
              $('#f-mg-weekend').prop( 'checked', false ).change();

              _showFormMessage('#general-message', getGlobal('message_success_mg'));
            } else {
              _showErrors('mg', response.errors);
            }
          });          
        }
      }

      return false;
    });

    $('#soumission-cuisine').on('submit', function() {
      $('#soumission-cuisine .alert').remove();
      var url = "/api/" + language + "/appeler_submit";

      if (_validate('ap')) {
        if( _this[ 'kitchenQuoteFormSubmitting' ] == false )
        {
            _this[ 'kitchenQuoteFormSubmitting' ] = true;

            $.ajax({
              type: "POST",
              url: url,
              data: $("#soumission-cuisine").serialize()
            }).done(function(response) {

              _this[ 'kitchenQuoteFormSubmitting' ] = false;

              if (response.success) {
                ga('send', 'event', 'button', 'click', 'contactme-submit', 1);
                _showFormMessage('#soumission-cuisine', getGlobal('message_success_sc'));

                // Clear the form
                var fields = ['#f-ap-name', '#f-ap-telephone', '#f-ap-cp', '#f-ap-succursale', '#f-ap-message' ];
                $.each( fields, function( index, key )
                {                
                  if( $( key ) )
                  {
                    $( key ).val( '' );
                  }
                } );

                $( '#f-ap-succursale' ).val( -1 ).change();
                $('#f-ap-day').prop( 'checked', false ).change();
                $('#f-ap-night').prop( 'checked', false ).change();
                $('#f-ap-weekend').prop( 'checked', false ).change();
                
              } else {
                _showErrors('cs', response.errors);
              }
            });
        }
      }

      return false;
    });

    // 'form-soumission-bain' c'est 'guide de planification'
  $('#form-soumission-bain a').on('click', function() {
     ga('send', 'event', 'button', 'click', 'guide', 1);
  });

  //   $('#soumission-bain').on('submit', function() {
  //     $('#soumission-bain .alert').remove();
  //     var url = "/api/" + language + "/bain_submit";
  //     if (_validate('sb')) {
  //       $.ajax({
  //         type: "POST",
  //         url: url,
  //         data: $("#soumission-bain").serialize()
  //       }).done(function(response) {
  //         if (response.success) {
  //           //var dimensionValue = 'bain';
  //           //ga('set', 'dimension4', dimensionValue);
  //           ga('send', 'event', 'button', 'click', 'bain', 1);
  //           _showFormMessage('#soumission-bain', getGlobal('message_success_sb'));
  //         } else {
  //           _showErrors('sb', response.errors);
  //         }
  //       });
  //     }
  //
  //     return false;
  //   });
  }

  function _validate(fid) {
    var errors = [];
    // clearing old errors
    if (fid === 'mg') {
      $('#general-message .has-error').removeClass('has-error');
    } else if (fid === 'ap') {
      $('#soumission-cuisine .has-error').removeClass('has-error');
    } else {
      $('#soumission-bain .has-error').removeClass('has-error');
    }
    // required common fields
    if (fid === 'ap') {
        var fullname = $('#f-' + fid + '-name').val();
    } else {
        var fullname = $('#f-' + fid + '-firstname').val() + " " + $('#f-' + fid + '-lastname').val();
    }

    var telephone = $('#f-' + fid + '-telephone').val();
    var cp = $('#f-' + fid + '-cp').val();
    if (fid !== 'ap') {
        var email = $('#f-' + fid + '-email').val();
    }
    var message = $('#f-' + fid + '-message').val();

    if (fullname.trim() === '') {
        if (fid === 'ap') {
            errors.push('#f-' + fid + '-name');
        } else {
            errors.push('#f-' + fid + '-lastname');
            errors.push('#f-' + fid + '-firstname');
        }
    }
    if (telephone.trim() === '' || !_validatePhone(telephone)) {
      errors.push('#f-' + fid + '-telephone');
    }
    if (cp.trim() === '' || !_validatePostalCode(cp)) {
      errors.push('#f-' + fid + '-cp');
    }
    if (fid !== 'ap' && (email.trim() === '' || !_validateEmail(email))) {
      errors.push('#f-' + fid + '-email');
    }
    if (message.trim() === '') {
      errors.push('#f-' + fid + '-message');
    }

    if (errors.length > 0) {
      _showErrors(fid, errors);
      return false;
    }
    return true;
  }

  function _showErrors(fid, errors) {
    $('html, body').animate({
      scrollTop: $("#contact-forms").offset().top
    }, 500);
    var f = '#general-message';
    if (fid === 'sc') {
      f = '#soumission-cuisine';
    } else if (fid === 'sb') {
      f = '#soumission-bain';
    }
    if (Array.isArray(errors)) {
      for (var i = 0; i < errors.length; i++) {
        $(errors[i]).addClass('has-error');
      }
      $(f).prepend('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + getGlobal('message_error') + '</div>');
    } else {
      $(f).prepend('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Warning!</strong>' + errors + '</div>');
    }
  }

  function _showFormMessage(f, message) {
    $('html, body').animate({
      scrollTop: $("#contact-forms").offset().top
    }, 500);

    $(f).prepend('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message + '</div>');
  }

  function _validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }
  function _validatePostalCode(pc) {
    var re = /^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1}$/;
    return re.test(pc);
  }
  function _validatePhone(phone) {
    var re = /^(?:\([2-9]\d{2}\)\ ?|[2-9]\d{2}(?:\-?|\ ?))[2-9]\d{2}[- ]?\d{4}$/;
    return re.test(phone);
  }

  function _resize() {
    // triggering global resize
    if ( $(window).width() < 1310 ) {
      if ($('.navbar .container').length > 0) {
        var cont = $('.navbar .container');
        cont.addClass('container-fluid');
        cont.removeClass('container');
      }
    } else {
      if ($('.navbar .container-fluid').length > 0) {
        var cont = $('.navbar .container-fluid');
        cont.addClass('container');
        cont.removeClass('container-fluid');
      }
    }
    $.event.trigger({
      type: 'websiteResize'
    });
  }

  /*Avoid to call too many time functions like resize*/
  function _debounce(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate)
          func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow)
        func.apply(context, args);
    };
  }

  function _replacePhoneNumbers(oldhtml) {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      //Created by Jon Meck at LunaMetrics.com - Version 1.0
      var countrycodes = "1";
      var delimiters = "-|\\.|—|–|&nbsp;";
      var phonedef = "\\+?(?:(?:(?:" + countrycodes + ")(?:\\s|" + delimiters + ")?)?\\(?[2-9]\\d{2}\\)?(?:\\s|" + delimiters + ")?[2-9]\\d{2}(?:" + delimiters + ")?[0-9a-z]{4})";
      var spechars = new RegExp("([- \(\)\.:]|\\s|" + delimiters + ")", "gi"); //Special characters to be removed from the link
      var phonereg = new RegExp("((^|[^0-9])(href=[\"']tel:)?((?:" + phonedef + ")[\"'][^>]*?>)?(" + phonedef + ")($|[^0-9]))", "gi");

      var newhtml = oldhtml.replace(/href=['"]callto:/gi, 'href="tel:');
      newhtml = newhtml.replace(phonereg, function($0, $1, $2, $3, $4, $5, $6) {
        if ($3)
          return $1;
        else if ($4)
          return $2 + $4 + $5 + $6;
        else
          return $2 + "<a href='tel:" + $5.replace(spechars, "") + "'>" + $5 + "</a>" + $6;
      });
      return newhtml;
    } else {
      return oldhtml;
    }
  }

    function bindPhoneEvents() {
      $('.phone-number').each( function( ) {
        $(this).html(obsfucatePhone($(this).text()));
      });
      $('.phone-number').on('click', function( ) {
        ga('send', 'event', 'button', 'reveal', $(this).attr('data-branch'), 1);
        $(this).text($(this).attr('data-phone'));
      });
    }
};

$(function() {
  _website = new WebSite();
  _website.init();
  //_website.loadProjects();
  setTimeout(function(){clickHash(currentHash);}, 500);
  setTimeout( function()
  {
    $( '#filters_wrapper' ).animate({opacity:1}, 800);
    //.css( 'opacity', .5 ).fadeIn();
  }, 1200 )
});

function removeEvents() {
  document.body.removeEventListener('click', sendInteractionEvent);
  window.removeEventListener('scroll', sendInteractionEvent);
}

/* SL: Force no-bounce by sending page interaction event. */
function sendInteractionEvent() {
  ga('send', 'event', 'Page Interaction');
  removeEvents();
}

/* SL: Automatically select correct submission form */
function clickHash(hash) {
  if (hash == "form-message-general") { jQuery("html, body").animate( { scrollTop: $("#form-message-general").offset().top-150 }, 500); }
  else if (hash == "form-soumission-cuisine") { jQuery("button[data-form-link='form-soumission-cuisine']").click(); jQuery("html, body").animate( { scrollTop: $("#form-soumission-cuisine").offset().top-150 }, 500); }
  else if (hash == "form-soumission-bain") { jQuery("button[data-form-link='form-soumission-bain']").click(); jQuery("html, body").animate( { scrollTop: $("#form-soumission-bain").offset().top-150 }, 500); }
}

/* SL: obsfucate phone number */
function obsfucatePhone(number) {
  var splits = number.split("-");
  var first = splits[0];
  return first + "-XXXX  &nbsp;&nbsp;<span style=\"font-size:11px;\">" + mapPhoneShowLabel + "</span>";
}

setTimeout(function(){document.body.addEventListener('click', sendInteractionEvent);}, 1000);
setTimeout(function(){window.addEventListener('scroll', sendInteractionEvent);}, 1000);
