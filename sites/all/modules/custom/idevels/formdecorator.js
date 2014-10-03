//Some magic

(function($) {
    var origAppend = $.fn.append;

    $.fn.append = function () {
        return origAppend.apply(this, arguments).trigger("append");
    };
})(jQuery);

// Try to chach append

$("body").bind("append", function(e) {
  if ($("#modal-content").length) {
    $("#modal-content").bind("append", function(e) {
      if ($(".error").length) {
        modalFormDecorator();
      };
    });
  };
});

// Function for change fields in add resources popup

function modalFormDecorator() {
  $( ".page-resources #modal-content form .form-radios input:checked" ).parent().addClass('cselected');
  $( ".page-resources #modal-content form .form-radios input").change(function(){
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
  $('#edit-field-book-0').bind('change', function() {
    $this = $(this);
    if (this.files[0].size > 20000000) {
      var $clone = $this.val('').clone( true )
      $this.replaceWith($clone);
      $this = $clone;
      alert('Розмір файла має бути не більшим 20Mb');
    };
  });
  return true;
};
