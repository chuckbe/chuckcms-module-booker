@extends('chuckcms::backend.layouts.base')

@section('title')
	Settings
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.settings.index') }}">Settings</a></li>
	</ol>
@endsection

@section('content')
<div class="container min-height p-3">
    <div class="row">
      <div class="col-sm-12">
        <nav aria-label="breadcumb mt-3">
          <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item active" aria-current="instellingen">Bewerk instellingen</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
      </div>
      <div class="col-sm-12">
        <div class="my-3">
          <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link{{ $tab == 'appointments'  ? ' active' : ''  }}" id="s_appointment-tab" href="{{ route('dashboard.module.booker.settings.index') }}">Appointments</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link{{ $tab == 'customer'  ? ' active' : ''  }}" id="s_appointment-tab" href="{{ route('dashboard.module.booker.settings.index.customer') }}">Klanten</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link{{ $tab == 'integerations'  ? ' active' : ''  }}" id="s_appointment-tab" href="{{ route('dashboard.module.booker.settings.index.integerations') }}">Integerations</a>
              </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="instellingenTabContent">
      <div class="col-sm-12 tab-pane fade{{ $tab == 'appointments'  ? '  show active' : ''  }}" id="s_appointments" role="tabpanel" aria-labelledby="s_appointments-tab">
        @include('chuckcms-module-booker::backend.settings.index._tab_appointments')
      </div>
      <div class="col-sm-12 tab-pane fade{{ $tab == 'customer'  ? '  show active' : ''  }}" id="s_customer" role="tabpanel" aria-labelledby="s_customer-tab">
        @include('chuckcms-module-booker::backend.settings.index._tab_customers')
      </div>
    </div>
    
</div>
@endsection

@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <link href="//cdn.chuck.be/assets/plugins/summernote/css/summernote.css" rel="stylesheet" media="screen">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script src="//cdn.chuck.be/assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
<script src="//cdn.chuck.be/assets/plugins/summernote/js/summernote.min.js"></script>
<script>
$( document ).ready(function() { 
  init(); 

  function init() {
    //Autonumeric plug-in
    $('.autonumeric').autoNumeric('init');

    //init media manager inputs 
    var domain = "{{ URL::to('dashboard/media')}}";
    $('.img_lfm_link').filemanager('image', {prefix: domain});

    $('.categoryNameInput').keyup(function(){
    console.log('This is the index of the element : ',$('.field_row_container').index($(this)));
    var text = $(this).val();
    var iOrder = $(this).attr('data-order');
    //categories[][name]
    slug_text = 'categories['+text.toLowerCase().replace(/ /g,'_').replace(/[^\w-]+/g,'')+']';
    $(".categoryNameInput[data-order="+iOrder+"]").prop('name', slug_text+'[name]');
    $(".categoryNameCheckboxHidden[data-order="+iOrder+"]").prop('name', slug_text+'[is_displayed]')
    $(".categoryNameCheckbox[data-order="+iOrder+"]").prop('name', slug_text+'[is_displayed]'); 
    });

  }
    
  $('.summernote-text-editor').summernote({
    height: 150,
    fontNames: ['Arial', 'Arial Black', 'Open Sans', 'Helvetica', 'Helvetica Neue', 'Lato'],
    toolbar: [
      // [groupName, [list of button]]
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']]
    ]
  });

  $('#add_extra_field_btn').click(function(){
    $('.field_row_container:first').clone().appendTo('.field_container_wrapper');
    $('.field_container_wrapper').append('<hr />');
    let order = $('.field_row_container').length;
    if($('.field_row_container').length > 1){
      $('#remove_last_field_btn').show();
    }
    $('.field_row_container:last').attr('data-order', order);
    $('.field_row_container:last input[type="text"]').attr('data-order', order);
    $('.field_row_container:last input[type="hidden"]').attr('data-order', order);
    $('.field_row_container:last input[type="checkbox"]').attr('data-order', order);
    $('.field_row_container:last input[type="text"]').val('');
    $('.field_row_container:last input[type="checkbox"]').prop('checked', false);
    
    init();
  });
  
  $('#remove_last_field_btn').click(function(){
    if($('.field_row_container').length > 1){
      $('.field_row_container:last').remove();
      $('.field_container_wrapper hr:last').remove();
      if($('.field_row_container').length == 1){
        $('#remove_last_field_btn').hide();
      }
    }
  });
  
});
</script>
@endsection