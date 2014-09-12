$(function () {
  hideNonLine($('.user-front .view-Users-front-profiles .views-row'));


  // For right question block
  $('.view-question .views-row').each(function () {
    $('> .views-field-title, > .views-field-created-1', this).wrapAll('<div class="question-lastposts-content"></div>');
  });
  $('.view-question .question-lastposts-content').prepend("<div class='buckle-up'></div>");
  $('.view-question .question-lastposts-content').prepend("<div class='buckle-down'></div>");
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
    }
    else {
      $( ".link-field-subrow" ).show();
      $( ".filefield-element" ).hide();
    }
  });

  // Function for change fields in add resources popup

  function modalFormDecorator() {
    $( " .page-resources #modal-content form .form-radios input:checked" ).parent().addClass('cselected');
    $( " .page-resources #modal-content form .form-radios input").change(function(){
      $this = $(this);
      $this.parents('.form-radios').find('.cselected').removeClass('cselected');
      $this.parent().addClass('cselected');
      if ($this.attr("id") == 'edit-resource-type') {
        $this.parents('form').removeClass('notbook');
        $( "#modal-content #edit-title" ).attr("placeholder", "Назва книги");
        $( "#modal-content #edit-field-link" ).attr("placeholder", "Автор книги");
      }
      else if ($this.attr("id") == 'edit-resource-type-1') {
        $this.parents('form').addClass('notbook');
        $( "#modal-content #edit-title" ).attr("placeholder", "Назва статті");
        $( "#modal-content #edit-field-link" ).attr("placeholder", "Посилання");
      }
      else if ($this.attr("id") == 'edit-resource-type-2') {
        $this.parents('form').addClass('notbook');
        $( "#modal-content #edit-title" ).attr("placeholder", "Назва сайту");
        $( "#modal-content #edit-field-link" ).attr("placeholder", "Посилання");
      }
      else if ($this.attr("id") == 'edit-resource-type-3') {
        $this.parents('form').addClass('notbook');
        $( "#modal-content #edit-title" ).attr("placeholder", "Заголовок");
        $( "#modal-content #edit-field-link" ).attr("placeholder", "Посилання");
      };
    });
  };

  $( ".ctools-use-modal" ).click(function() {
    setTimeout(function(){
      modalFormDecorator();
    },1000);
  });

/*    $( ".page-resources #modal-content form .form-radios label" ).click(function() {
      $(this).css('background', '#aaa')
    });*/
  //If you see what this code string is comment - delete them 
  //Add form for create Resurse
  //$( ".view-Resources" ).append( '<div><p>Виберіть тип матеріалу, який ви хочете добавити:</p><ul><li>Книга</li><li>Стаття</li><li>Сайт</li><li>Посилання</li></ul><form><input type="text" maxlength="255" name="title" id="edit-title" size="60" value="" class="form-text required"><input type="text" maxlength="2048" name="field_link[0][url]" id="edit-field-link-0-url" size="60" value="" class="form-text"><textarea cols="60" rows="20" name="body" id="edit-body" class="form-textarea resizable textarea-processed"></textarea><input type="submit" name="op" id="edit-submit" value="Зберегти" class="form-submit"></form></div>' );

});
