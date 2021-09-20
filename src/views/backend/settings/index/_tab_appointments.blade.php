<div class="row column-seperation">
    <div class="col-lg-12">
        <div class="form-group form-group-default required ">
            <label>Can guest checkout</label>
            <select class="full-width select2 form-control" data-init-plugin="select2" name="appointment['can_guest_checkout']">
              <option value="1" @if($settings['appointment']['can_guest_checkout'] == true) selected @endif>Ja</option>
              <option value="0" @if($settings['appointment']['can_guest_checkout']  !== true) selected @endif>Nee</option>
            </select>
        </div>
        <div class="form-group form-group-default required ">
            <label>Title</label>
            <input type="text" class="form-control" placeholder="{{$settings['appointment']['title']}}" name="appointment['title']" value="{{$settings['appointment']['title']}}" required>
        </div>
        <div class="form-group form-group-default required ">
        </div>
    </div>
</div>