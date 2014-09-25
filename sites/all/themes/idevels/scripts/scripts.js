// function for find caret position

(function ($, undefined) {
  $.fn.getCursorPosition = function() {
    var el = $(this).get(0);
    var pos = 0;
    if('selectionStart' in el) {
        pos = el.selectionStart;
    } else if('selection' in document) {
        el.focus();
        var Sel = document.selection.createRange();
        var SelLength = document.selection.createRange().text.length;
        Sel.moveStart('character', -el.value.length);
        pos = Sel.text.length - SelLength;
    }
    return pos;
 }
})(jQuery);

$(function () {
  hideNonLine($('.user-front .view-Users-front-profiles .views-row'));


  // For right question block
  /*$('.view-question .views-row').each(function () {
    $('> .views-field-title, > .views-field-created-1', this).wrapAll('<div class="question-lastposts-content"></div>');
  });
  $('.view-question .question-lastposts-content').prepend("<div class='buckle-up'></div>");
  $('.view-question .question-lastposts-content').prepend("<div class='buckle-down'></div>");*/
//$('.view-question .view-content > .views-row').wrap('<div class="question-lastposts-content"></div>');
  $('#block-menu-secondary-links .menu li a').each(function () {
    $(this).wrapInner('<span class="tile-title"></span>');
  });

  // Delete background from user link in primary menu.
  $('.button').each(function (e) {
    $(this).mousedown(function () {
      $(this).addClass('active');
    });
    $(this).mouseup(function () {
      $(this).removeClass('active');
    });
    $(this).hover(function () {
    }, function () {
      $(this).removeClass('active');
    });
  });
  $('.steps .step1 br').remove();
  var text = Drupal.t('Menu');
  $('#block-menu-primary-links ul.menu').after('<a href="#" id="pull">' + text + '</a>');

  // For expand primary menu on mobile.
  var pull = $('#pull');
  var menu = $('#block-menu-primary-links ul.menu');
  $('#pull').click(function (e) {
//    e.preventDefault();
    menu.toggle();
  });

  // Move logo to top on mobile devices.
  if ($(window).width() < 801 && $(window).width() > 480) {
    $('#header_banner').prepend($('#wrap_branding'));
  }
  if ($(window).width() < 480) {
    $('#wrap_branding').after($('#header_banner'));
  }


  // On resize.
  $(window).resize(function () {
    $('.user-front .view-users-front .views-row').each(function () {
      $(this).show();
    });
    hideNonLine($('.user-front .view-Users-front-profiles .views-row'));

    // Display menu after resize.
    var w = $(window).width();
    if (w > 479 && menu.is(':hidden')) {
      menu.removeAttr('style');
    }
    if ($(window).width() < 480) {
      $('#wrap_branding').after($('#header_banner'));
    }
    if ($(window).width() < 800 && $(window).width() > 480) {
      $('#header_banner').prepend($('#wrap_branding'));
    }
    if ($(window).width() > 800) {
      $('#header_banner').after($('#wrap_branding'));
    }
  });

  // Random sort elements in Tags views (front page), and hide other 20 elements.
  var tags = $('.view-tegs-front .view-content'),
    tagsrow = tags.children('.views-row').get();

  tagsrow.sort(function(){
    return Math.random()*10 > 5 ? 1 : -1;
  });

  $.each(tagsrow, function(idx, itm) { tags.append(itm); });
  var count = $('.view-tegs-front .view-content .views-row').length;
  count = count -10;
  $('.view-tegs-front .view-content .views-row').slice(-count).hide();

  // Change orientation on mobile device.
  $(window).bind("orientationchange", orientationChange);
  function orientationChange() {
    //whatever needs to happen when it changes.
    $('.user-front .view-users-front .views-row').each(function () {
      $(this).show();
    });

    hideNonLine($('.user-front .view-Users-front-profiles .views-row'));
  }

// Function for remove non line elements in "$('.user-front .view-users-front .views-row')".
  function hideNonLine(obj) {

    var listRow = 0;
    var firstRow = 0;
    var row = 1;
    obj.each(function (i) {
      if ($(this).prev().length > 0) {
        if ($(this).position().top != $(this).prev().position().top) {
          if (row == 1) {
            firstRow = listRow;
          }
          row++;
          listRow = 1;
        } else {
          listRow++;
        }
      }
      else {
        listRow++;
      }
    });
    var count = firstRow*3;
    count = obj.length - count;
    console.log(count);

    if (row > 3) {
      obj.slice(-count).hide();
    }
  }

  // Function for hide file donwnload field in add resources page if it not book

  $( "#edit-taxonomy-12" ).change(function(){
    if (window.location.pathname != '/node/add/resource')
      return;
    $this = $(this);
    console.log(window.location.pathname);
    if ($this.val() == 2491) {
      $( ".link-field-subrow" ).hide();
      $( ".filefield-element" ).show();
      $( "#edit-field-book-autor-0-value-wrapper" ).show();
    }
    else {
      $( ".link-field-subrow" ).show();
      $( "#edit-field-book-0-upload-wrapper" ).hide();
      $( "#edit-field-book-autor-0-value-wrapper" ).hide();
    }
  });

  // Decorate popup on click

  $( ".ctools-use-modal" ).click(function() {
    setTimeout(function(){
      modalFormDecorator();
    },1000);
  });

  $( ".page-events #edit-city-wrapper .form-autocomplete").attr('maxlength','256');

  // Add page argument to autocoplite field on keypress (before ajax request)

  $( ".page-events #edit-city-wrapper .form-autocomplete").keypress(function(e){
    key_presed = e.which || e.keyCode;
    igrore_keys = new Array(33, 34, 35, 36, 37, 38, 39, 40, 45);
    if ((key_presed > 32 || key_presed == 8) && igrore_keys.indexOf(key_presed) == -1) {
      $this = $(this);
      var key = '                                                                                                    |events|';
      if ($this.val().indexOf(key) == -1) {
        pos = $this.getCursorPosition();
        $this.val($this.val() + key);
        $this.attr({
          selectionStart : pos,
          selectionEnd   : pos
        });
      };
    };
  });

  // Remove page argument from autocoplite field after keyup

  $( ".page-events #edit-city-wrapper .form-autocomplete").keyup(function(e){
    $this = $(this);
    var key_presed = e.which || e.keyCode;
    if ((key_presed > 32 || key_presed == 8) && key_presed != 46 && igrore_keys.indexOf(key_presed) == -1 && $this.val().indexOf(key) != -1) {
      var delta = 1;
      if (key_presed == 8) {
        delta = -1;
      }
      $this.val($this.val().replace(key, ''));
      $this.attr({
        selectionStart : pos + delta,
        selectionEnd   : pos + delta
      });
    };
  });

});
