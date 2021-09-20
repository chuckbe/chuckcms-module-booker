<div class="row column-seperation">
    <div class="col-lg-12">
        <div class="form-group form-group-default required ">
            <label>Is Telephone nummer required</label>
            <select class="full-width select2 form-control" data-init-plugin="select2" name="customer['is_tel_required']">
              <option value="1" @if($settings['customer']['is_tel_required'] == true) selected @endif>Ja</option>
              <option value="0" @if($settings['customer']['is_tel_required']  !== true) selected @endif>Nee</option>
            </select>
        </div>
        <div class="form-group form-group-default required ">
            <label>Title</label>
            <input type="text" class="form-control" placeholder="{{$settings['customer']['title']}}" name="customer['title']" value="{{$settings['customer']['title']}}" required>
        </div>
        <div class="form-group form-group-default required ">
        </div>
    </div>
</div>