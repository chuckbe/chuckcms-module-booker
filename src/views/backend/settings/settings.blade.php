@extends('chuckcms::backend.layouts.base')

@section('title')
	Settings
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.order_form.settings.index') }}">Settings</a></li>
	</ol>
@endsection

@section('content')
<div class="container min-height p-3">
  <form action="{{ route('dashboard.module.order_form.settings.update') }}" method="POST">
    <div class="row">
      <div class="col-sm-12">
        <nav aria-label="breadcumb mt-3">
          <ol class="breadcrumb mt-3">
            <li class="breadcrumb-item active" aria-current="instellingen">Bewerk instellingen</li>
          </ol>
        </nav>
      </div>
    </div>
    @if ($errors->any())
      <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
        <div class="col">
          <div class="my-3">
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col">
        <div class="my-3">
          <ul class="nav nav-tabs justify-content-start" id="instellingenTab" role="tablist">
            {{-- <li class="nav-item" role="presentation">
                <a class="nav-link active" id="categories_setup-tab" data-target="#tab_resource_categories_setup" data-toggle="tab" href="#" role="tab" aria-controls="#categories_setup" aria-selected="true">Categories</a>
            </li> --}}
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="form_setup-tab" data-target="#tab_resource_form_setup" data-toggle="tab" href="#" role="tab" aria-controls="#form_setup" aria-selected="false">Form</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="cart_setup-tab" data-target="#tab_resource_cart_setup" data-toggle="tab" href="#" role="tab" aria-controls="#cart_setup" aria-selected="false">Cart</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="order_setup-tab" data-target="#tab_resource_order_setup" data-toggle="tab" href="#" role="tab" aria-controls="#order_setup" aria-selected="false">Order</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="emails_setup-tab" data-target="#tab_resource_emails_setup" data-toggle="tab" href="#" role="tab" aria-controls="#emails_setup" aria-selected="false">Emails</a>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <a class="nav-link" id="locations_setup-tab" data-target="#tab_resource_locations_setup" data-toggle="tab" href="#" role="tab" aria-controls="#locations_setup" aria-selected="false">Locations</a>
            </li> --}}
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="delivery_setup-tab" data-target="#tab_resource_delivery_setup" data-toggle="tab" href="#" role="tab" aria-controls="#delivery_setup" aria-selected="false">Delivery</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pos_setup-tab" data-target="#tab_resource_pos_setup" data-toggle="tab" href="#" role="tab" aria-controls="#pos_setup" aria-selected="false">POS</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="instellingenTabContent">
      
      {{-- categories-tab-starts --}}
      {{-- <div class="col-sm-12 tab-pane fade show active" id="tab_resource_categories_setup" role="tabpanel" aria-labelledby="categories_setup-tab">
        <h4>Categories</h4>
        <div class="field_container_wrapper">
        @foreach ($settings["categories"] as $categoryName => $categoryValue)
          <div class="row column-seperation field_row_container" data-order="{{ $loop->iteration }}">
            <div class="col">
              <div class="form-group form-group-default required ">
                <label>Name</label>
                <input type="text" class="form-control categoryNameInput" placeholder="name" name="categories[{{strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $categoryValue["name"]), '_'))}}][name]" value="{{$categoryValue["name"]}}" data-order="{{ $loop->iteration }}" required>
              </div>
            </div>
            <div class="col">
              <div class="form-group form-group-default required ">
                <label>Deze categorie tonen</label>
                <div class="form-check">
                  <input id="is_displayedHidden" class="categoryNameCheckboxHidden" type="hidden" value="0" name="categories[{{strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $categoryValue["name"]), '_'))}}][is_displayed]" data-order="{{ $loop->iteration }}">
                  <input type="checkbox" class="form-check-input categoryNameCheckbox" value="1" id="is_displayed" name="categories[{{strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $categoryValue["name"]), '_'))}}][is_displayed]" @if($categoryValue["is_displayed"] == "true" OR $categoryValue["is_displayed"] == "1" ) checked @endif data-order="{{ $loop->iteration }}">
                  <label class="form-check-label" for="is_displayed">is displayed</label>
                </div>
              </div>
            </div>
          </div>
          <hr>
        @endforeach
        </div>
        <div class="row">
          <div class="col-lg-6">
            <button class="btn btn-primary btn-lg" type="button" id="add_extra_field_btn"><i class="fa fa-plus"></i> Extra veld toevoegen</button>
          </div>
          <div class="col-lg-6">
            <button class="btn btn-warning btn-lg" type="button" id="remove_last_field_btn" @if(count($settings["categories"]) == 1) style="display:none;" @endif><i class="fa fa-minus"></i> Laatste veld verwijderen</button>
          </div>
        </div>
      </div> --}}
      {{-- categories-tab-ends --}}


      {{-- form-tab-starts --}}
      <div class="col-sm-12 tab-pane fade show active" id="tab_resource_form_setup" role="tabpanel" aria-labelledby="form_setup-tab">
        @foreach ($settings["form"] as $formOption => $formOptionValue)
          <div class="row column-seperation">
            <div class="col">
              @if (is_bool($formOptionValue))
                <div class="form-group form-group-default required ">
                  <label>{{$formOption}}</label>
                  <select class="full-width select2 form-control" data-init-plugin="select2" name="form[{{$formOption}}]">
                    <option value="1" @if($formOptionValue == true) selected @endif>Ja</option>
                    <option value="0" @if($formOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              @else
                <div class="form-group form-group-default required ">
                  <label>{{$formOption}}</label>
                  <input type="text" class="form-control" placeholder="{{$formOption}}" name="form[{{$formOption}}]" value="{{$formOptionValue}}" required>
                </div>
              @endif
            </div>
          </div>
          @if(!$loop->last)
          <hr>
          @endif
        @endforeach
      </div>
      {{-- form-tab-ends --}}
      
      {{-- cart-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_cart_setup" role="tabpanel" aria-labelledby="cart_setup-tab">
        <div class="row column-seperation">  
          <div class="col"> 
            <div class="form-group form-group-default required ">
              <label>Use ui</label>
              <select class="full-width select2 form-control" data-init-plugin="select2" name="cart[use_ui]">
                <option value="1" @if($settings["cart"]["use_ui"] == true) selected @endif>Ja</option>
                <option value="0" @if($settings["cart"]["use_ui"] !== true) selected @endif>Nee</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      {{-- cart-tab-ends --}}
      
      {{-- order-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_order_setup" role="tabpanel" aria-labelledby="order_setup-tab">
        @foreach ($settings["order"] as $order => $orderValue)
          @if (is_bool($orderValue))
            <div class="row column-seperation">  
              <div class="col"> 
                <div class="form-group form-group-default required ">
                  <label>{{$order}}</label>
                  <select class="full-width select2 form-control" data-init-plugin="select2" name="order[{{$order}}]">
                    <option value="1" @if($orderValue == true) selected @endif>Ja</option>
                    <option value="0" @if($orderValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              </div>
            </div>
          @else
            <div class="form-group form-group-default required ">
              <label>{{$order}}</label>
              <input type="text" class="form-control" placeholder="{{$orderValue}}" name="order[{{$order}}]" value="{{$orderValue}}" required>
            </div>
          @endif
        @endforeach
      </div>
      {{-- order-tab-ends --}}
      
      {{-- emails-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_emails_setup" role="tabpanel" aria-labelledby="emails_setup-tab">
        @foreach ($settings["emails"] as $emailOption => $emailOptionValue)
          @if (is_bool($emailOptionValue) && $emailOption !== 'to_cc'  && $emailOption !== 'to_bcc')
            <div class="row column-seperation">  
              <div class="col"> 
                <div class="form-group form-group-default required ">
                  <label>{{$emailOption}}</label>
                  <select class="full-width select2 form-control" data-init-plugin="select2" name="emails[{{$emailOption}}]">
                    <option value="1" @if($emailOptionValue == true) selected @endif>Ja</option>
                    <option value="0" @if($emailOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              </div>
            </div>
          @else
            <div class="form-group form-group-default required ">
              <label>{{$emailOption}}</label>
              <input type="text" class="form-control" placeholder="{{$emailOptionValue}}" name="emails[{{$emailOption}}]" value="{{$emailOptionValue}}" @if($emailOption !== 'to_cc'  && $emailOption !== 'to_bcc') required @endif>
            </div>
          @endif
        @endforeach
      </div>
      {{-- emails-tab-ends --}}
      
      {{-- locations-tab-starts --}}
      {{-- <div class="col-sm-12 tab-pane fade" id="tab_resource_locations_setup" role="tabpanel" aria-labelledby="locations_setup-tab">
        <h4>Locations</h4>
        @foreach ($settings["locations"] as $locationOption => $locationOptionValue)
          <h5>{{ucfirst($locationOption)}}</h5>
          <div class="row column-seperation">  
            <div class="col">
              @foreach ($locationOptionValue as $locationOptionType => $locationOptionTypeValue)
                @if (is_bool($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <select class="full-width select2 form-control" data-init-plugin="select2" name="locations[{{$locationOption}}][{{$locationOptionType}}]">
                      <option value="1" @if($locationOptionTypeValue == true) selected @endif>Ja</option>
                      <option value="0" @if($locationOptionTypeValue !== true) selected @endif>Nee</option>
                    </select>
                  </div>
                @elseif (is_null($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="N/A" name="locations[{{$locationOption}}][{{$locationOptionType}}]">
                  </div>
                @elseif (is_array($locationOptionTypeValue))
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="voer elke postcode in met een komma" name="locations[{{$locationOption}}][{{$locationOptionType}}]" 
                    value="{{implode(",", $locationOptionTypeValue)}}"
                    >
                  </div>
                @else
                  <div class="form-group form-group-default required ">
                    <label>{{$locationOptionType}}</label>
                    <input type="text" class="form-control" placeholder="{{$locationOptionTypeValue}} name" name="locations[{{$locationOption}}][{{$locationOptionType}}]" value="{{$locationOptionTypeValue}}" required>
                  </div>
                @endif 
              @endforeach 
            </div>
          </div>
          <hr>
        @endforeach
      </div> --}}
      {{-- locations-tab-ends --}}
      
      {{-- delivery-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_delivery_setup" role="tabpanel" aria-labelledby="delivery_setup-tab">
        @foreach ($settings["delivery"] as $deliveryOption => $deliveryOptionValue)
          <div class="row column-seperation"> 
            <div class="col">
              @if (is_bool($deliveryOptionValue))
                <div class="form-group form-group-default required ">
                  <label>{{$deliveryOption}}</label>
                  <select class="full-width select2 form-control" data-init-plugin="select2" name="delivery[{{$deliveryOption}}]">
                    <option value="1" @if($deliveryOptionValue == true) selected @endif>Ja</option>
                    <option value="0" @if($deliveryOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              @else
                <div class="form-group form-group-default required ">
                  <label>{{$deliveryOption}}</label>
                  <input type="text" class="form-control" placeholder="{{$deliveryOption}}" name="delivery[{{$deliveryOption}}]" value="{{$deliveryOptionValue}}" >
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>{{-- delivery-tab-ends --}}

      {{-- pos-tab-starts --}}
      <div class="col-sm-12 tab-pane fade" id="tab_resource_pos_setup" role="tabpanel" aria-labelledby="pos_setup-tab">
        @foreach ($settings["pos"] as $posOption => $posOptionValue)
          @if (is_bool($posOptionValue) && $posOption !== 'to_cc'  && $posOption !== 'to_bcc')
            <div class="row column-seperation">  
              <div class="col"> 
                <div class="form-group form-group-default required ">
                  <label>{{$posOption}}</label>
                  <select class="full-width select2 form-control" data-init-plugin="select2" name="pos[{{$posOption}}]">
                    <option value="1" @if($posOptionValue == true) selected @endif>Ja</option>
                    <option value="0" @if($posOptionValue !== true) selected @endif>Nee</option>
                  </select>
                </div>
              </div>
            </div>
          @else
            <div class="form-group form-group-default required ">
              <label>{{$posOption}}</label>
              <input type="text" class="form-control" placeholder="{{$posOptionValue}}" name="pos[{{$posOption}}]" value="{{$posOptionValue}}" required>
            </div>
          @endif
        @endforeach
      </div>
      {{-- pos-tab-ends --}}
    </div>
    <div class="row">
      <div class="col">
        <p class="pull-right">
          <input type="hidden" name="_token" value="{{ Session::token() }}">
          <input type="hidden" name="settings_id" value="">
          <button type="submit" name="settings_save" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
          <a href="{{ route('dashboard.module.order_form.products.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
        </p>
      </div>
    </div>
  </form>
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