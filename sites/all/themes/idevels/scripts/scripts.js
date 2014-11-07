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

  if ($('#edit-pass-pass1').length) {
    $('#edit-pass-pass1').attr('placeholder', Drupal.t('Password'));
    $('#edit-pass-pass2').attr('placeholder', Drupal.t('Confirm password'));
  }

  // Rewrite autocoplit submit function for event page

  if ($("body").hasClass("page-events")) {
    Drupal.ACDB.prototype.search = function (searchString) {
      var db = this;
      this.searchString = searchString;

      // See if this key has been searched for before
      if (this.cache[searchString]) {
        return this.owner.found(this.cache[searchString]);
      }

      // Initiate delayed search
      if (this.timer) {
        clearTimeout(this.timer);
      }
      this.timer = setTimeout(function() {
        db.owner.setStatus('begin');

        // Ajax GET request for autocompletion
        $.ajax({
          type: "GET",
          // There I add "event" to GET qecuest
          url: db.uri +'/'+ Drupal.encodeURIComponent(searchString) + '/event',
          dataType: 'json',
          success: function (matches) {
            if (typeof matches['status'] == 'undefined' || matches['status'] != 0) {
              db.cache[searchString] = matches;
              // Verify if these are still the matches the user wants to see
              if (db.searchString == searchString) {
                db.owner.found(matches);
              }
              db.owner.setStatus('found');
            }
          },
          error: function (xmlhttp) {
            alert(Drupal.ahahError(xmlhttp, db.uri));
          }
        });
      }, this.delay);
    };
  };

  $(".vud-widget-upanddown").each(function(){
    $(this).parent().parent().find('.meta-links > .meta').after($(this));
  });

  // (Event page) Add user avatar if user push "I'll go" button
  $(".node-type-events .panel-display .views-field-ops .flag-be-there a").live('mousedown', function(event) {
    if (!$('.node-type-events .panel-display .pane-events-panel-pane-3 .views-field-uid').length) {
      $('.node-type-events .panel-display .pane-events-panel-pane-3 .views-row-1').prepend('<div class="views-field-uid"></div>');
    };
    $.getJSON('/idevels-user/info', {format: "json"}, function(data) {
      if ($(".node-type-events .panel-display .pane-events-panel-pane-3 .views-field-uid a[href='/users/"+data["user_name"]+"']").length) {
        $(".node-type-events .panel-display .pane-events-panel-pane-3 .views-field-uid a[href='/users/"+data["user_name"]+"']").remove();
      }
      else {
        var avatar = $('<span id="ajax_avatar" class="field-content"><a>'+data["avatar"]+'</a></span>');
        $(".node-type-events .panel-display .pane-events-panel-pane-3 .views-field-uid").prepend(avatar);
        $('#ajax_avatar a').attr("href", '/users/'+data["user_name"]);
      }
    });
  });

});
