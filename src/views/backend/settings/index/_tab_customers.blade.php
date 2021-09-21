<form action="{{ route('dashboard.module.booker.settings.index.customer.update') }}" method="POST">
<div class="row column-seperation">
    <div class="col-lg-12">
        <div class="form-group form-group-default required ">
            <label>Is Telephone nummer required</label>
            <select class="full-width select2 form-control" data-init-plugin="select2" name="is_tel_required">
              <option value="1" @if($settings['customer']['is_tel_required'] == true) selected @endif>Ja</option>
              <option value="0" @if($settings['customer']['is_tel_required']  !== true) selected @endif>Nee</option>
            </select>
        </div>
        <div class="form-group form-group-default required ">
            <label>Title</label>
            <input type="text" class="form-control" placeholder="{{$settings['customer']['title']}}" name="title" value="{{$settings['customer']['title']}}" required>
        </div>
        <div class="form-group form-group-default required ">
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-12 text-right">
      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <button class="btn btn-outline-success" type="submit">Opslaan</button>
    </div>
</div>
</form>