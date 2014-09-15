	alert('skdjh');
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