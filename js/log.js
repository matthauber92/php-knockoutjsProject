$(document).ready(function(){
  $('.resize').hover(enlarge, returnSize);
  function enlarge() {
    $(this).animate({height: '+=20%', width: '+=20%'});
  }	
  function returnSize() {
    $(this).animate({height: '-=20%', width: '-=20%'});
  }
		/*$('.resize').mouseover(function () {
    		$('.overlay').fadeIn();
		}).mouseout(function () {
    		$('.overlay').fadeOut();
     });*/
});
$(document).ready(function(){
  var scrollTop = 0;
  $(window).scroll(function(){
    scrollTop = $(window).scrollTop();
    $('.counter').html(scrollTop);
    
    if (scrollTop >= 100) {
      $('#global-nav').addClass('scrolled-nav');
      $('#ToggleHeader').removeClass('toggleHead');
      $('#InpStyle').addClass('inp-style');
    } else if (scrollTop < 100) {
      $('#global-nav').removeClass('scrolled-nav');
      $('#ToggleHeader').addClass('toggleHead');
      $('#InpStyle').removeClass('inp-style');
    }   
  });   
});