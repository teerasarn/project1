var _home;

var Home = function() {
  //defining movements and animations
  
  var menuHeight = 0;
  
  // publishing functions
  return {
    init: _init
  };
  
  function _init() {
    menuHeight = $('.navbar-fixed-top').height();
   
    _resize();
    
    $('body').css('display','block');

    setTimeout(function() {
      $('#home-intro h2').addClass('ready');
      setTimeout(function() {
        $('#home-intro h1').addClass('ready');
        $('.menu-video').addClass('ready');
      },1000);
    },500);
   
   

    _setEvents();

    _setWatchers();
  }
  
  function _setEvents() {
    $(document).on("websiteResize", function() {
      _resize();
    });
    
    $('.scroll-down a').on('click', function(e) {
      e.preventDefault();
      $('html,body').animate({
        scrollTop: $(window).height()+2
      }, 500);
    });
    var lang = 'en';
    var lKitchen = '/en/kitchen-renovation';
    var lBathroom = '/en/bathroom-renovation';
    if ( $('body').hasClass('fr') ) {
      lang = 'fr';
      lKitchen = '/fr/renovation-cuisine';
      lBathroom = '/fr/renovation-salle-de-bain';
    }
    $('.prod-bain').on('click', function() {
      document.location.href = lBathroom;
    });
    $('.prod-cuisine').on('click', function() {
      document.location.href = lKitchen;
    });
    
  }
  
  function _setWatchers() {
    var watcherVideoIntro = scrollMonitor.create('#home-intro');
    watcherVideoIntro.exitViewport(function() {
      $('.navbar-fixed-top').addClass('navbar-visible');
    });
    watcherVideoIntro.enterViewport(function() {
      $('.navbar-fixed-top').removeClass('navbar-visible');
    });
    
    var watcherEntreprise = scrollMonitor.create('#home-entreprise');
    watcherEntreprise.fullyEnterViewport(function() {
      if ( !$('#home-entreprise').hasClass('shown') ) {
        //TweenLite.fromTo($('#home-entreprise h2'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, ease: Elastic.easeOut});
        //TweenLite.fromTo($('#home-entreprise .sous-titre'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, delay: 0.25, ease: Elastic.easeOut});
        //TweenLite.fromTo($('#home-entreprise .more-link-wrapper'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, delay: 0.5, ease: Elastic.easeOut});
        //TweenLite.fromTo($('#home-entreprise .stat-1'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, delay: 0.75, ease: Elastic.easeOut});
        //TweenLite.fromTo($('#home-entreprise .stat-2'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, delay: 1, ease: Elastic.easeOut});
        //TweenLite.fromTo($('#home-entreprise .stat-3'),1, {autoAlpha: 0, scale: 0.2}, {autoAlpha: 1, scale: 1, delay: 1.25, ease: Elastic.easeOut});
        $('#home-entreprise').addClass('shown')
      }
    });
    watcherEntreprise.partiallyExitViewport(function() {
      if ( watcherEntreprise.isBelowViewport ) {
        //TweenLite.fromTo($('#home-entreprise .stat-3'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, scale: 0.2, ease: Elastic.inOut});
        //TweenLite.fromTo($('#home-entreprise .stat-2'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, scale: 0.2, delay: 0.25});
        //TweenLite.fromTo($('#home-entreprise .stat-1'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, scale: 0.2, delay: 0.5});
        //TweenLite.fromTo($('#home-entreprise .more-link-wrapper'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, scale: 0.2, delay: 0.75});
        //TweenLite.fromTo($('#home-entreprise .sous-titre'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, scale: 0.2, delay: 1});
        //TweenLite.fromTo($('#home-entreprise h2'),1, {autoAlpha: 1, scale: 1}, {autoAlpha: 0, delay: 1.25, scale: 0.2});
        $('#home-entreprise').removeClass('shown');
      }
    });
    
    var watcherServices = scrollMonitor.create('#home-services h2');
    watcherServices.fullyEnterViewport(function() {
      if ( !$('#home-services .services-menu').hasClass('shown') ) {
        //TweenLite.fromTo($('#home-services .services-menu'), 1, {autoAlpha:0, marginTop: -200, marginBottom: 200}, {autoAlpha: 1, marginTop: 0, marginBottom: 0, ease:Elastic.easeOut});
        $('#home-services .services-menu').addClass('shown');
      }
    });
    watcherServices.partiallyExitViewport(function() {
      if ( watcherServices.isBelowViewport ) {
        //TweenLite.fromTo($('#home-services .services-menu'), 1, {autoAlpha: 1, marginTop: 0, marginBottom: 0}, {autoAlpha:0, marginTop: -200, marginBottom: 200});
        $('#home-services .services-menu').removeClass('shown');
      }
    });
    
    var watcherProducts1 = scrollMonitor.create('#home-products h3');
    watcherProducts1.fullyEnterViewport(function() {
      if ( !$('#home-products h3').hasClass('shown') ) {
        $('#home-products .products').addClass('prod-in');
        $('#home-products h3').addClass('shown');
      }
    });
    var watcherBlog = scrollMonitor.create('#home-blog h3');
    watcherBlog.fullyEnterViewport(function() {
      if ( !$('#home-blog h3').hasClass('shown') ) {
        $('#home-blog .col-md-6').addClass('prod-in');
        $('#home-blog h3').addClass('shown');
      }
    });
    
    watcherProducts1.partiallyExitViewport(function() {
      if ( watcherProducts1.isBelowViewport ) {
        //TweenLite.fromTo($('#home-products h3'), 1, {autoAlpha:1, marginTop: 0, marginBottom: 60}, {autoAlpha: 0, marginTop: -150, marginBottom: 210});
        $('#home-products h3').removeClass('shown');
      }
    });
    
    var watcherProducts = scrollMonitor.create('#home-products');
    watcherProducts.fullyEnterViewport(function() {
      //TweenLite.to($('.prod-cuisine'), 1, {autoAlpha: 1});
      //TweenLite.to($('.prod-bain'), 1, {autoAlpha: 1});
      //TweenLite.to($('.prod-cuisine img'), 1, {marginLeft: 0});
      //TweenLite.to($('.prod-bain img'), 1, {marginLeft: 0});
    });
    watcherProducts.partiallyExitViewport(function() {
      if ( watcherProducts.isBelowViewport ) {
        /*$('.products').removeClass('prod-in');
        $('.products').addClass('prod-out');*/
        //TweenLite.to($('.prod-cuisine'), 1, {autoAlpha: 0});
        //TweenLite.to($('.prod-bain'), 1, {autoAlpha: 0});
        //TweenLite.to($('.prod-cuisine img'), 1, {marginLeft: '-100%'});
        //TweenLite.to($('.prod-bain img'), 1, {marginLeft: '100%'});
      }
    });    
  }
  
  function _resize() {
    var height = $(window).height();
    var width = $(window).width();
      
    $('#home-intro').height(height);
    
    if ( width < 768 ) {
      eWidth = $('#home-services .services-menu .img-placeholder').width();
      $('#home-services .services-menu .img-placeholder').height(eWidth);
      $('.services-menu li').height(eWidth+29);
    } else {
      $('#home-services .services-menu .img-placeholder').attr('style','');
      $('.services-menu li').attr('style','');
    }
  }
};

$(function() {
	_home = new Home();
	_home.init();
});
