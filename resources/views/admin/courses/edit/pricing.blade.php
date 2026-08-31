<div class="row">
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Price Type')}}
          <select class="form-control select-two" id="is_paid" data-placeholder="Choose Price Type" name="is_paid" @lmsparsley(courses,is_paid)>
              <option value="">Price Type</option>
              @foreach (lms_course_pricing() as $k => $item)
                <option value="{{$k}}" {{ old('is_paid', $course->is_paid) == $k ? 'selected' : '' }}>{{$item}}</option>
              @endforeach
          </select>
          @error('is_paid')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4 pricing-cont">
      <div class="form-group">
          {{lms_form_label('Price')}}
          <input type="text" class="form-control decimal_input" name="price" value="{{ old('price', $course->price)}}" placeholder="{{__('Enter Course Price')}}" @lmsparsley(courses,price)>
          @error('price')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
</div>
<div class="row pricing-cont">
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Any Discount?')}}<br>
          <div class="">
              <label class="mr-2"><input {{old('discount_flag', $course->discount_flag)=='1'?'checked':''}} type="radio" name="discount_flag" value="1" data-parsley-errors-container="#discountError" @lmsparsley(courses,discount_flag)> Yes</label>
              <label><input {{old('discount_flag', $course->discount_flag)=='0'?'checked':''}} type="radio" name="discount_flag" value="0" @lmsparsley(courses,discount_flag)> No</label>
          </div>
          <div id="discountError"></div>
          @error('discount_flag')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4 discounted-price-cont">
      <div class="form-group">
          {{lms_form_label('Discounted Price', false)}}
          <input type="text" class="form-control decimal_input" name="discounted_price" value="{{ old('discounted_price', $course->discounted_price)}}" placeholder="{{__('Enter Discounted Price')}}" @lmsparsley(courses,discounted_price)>
          @error('discounted_price')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#is_paid').change(function() {
            if(this.value == '0'){
                $('.pricing-cont').addClass('d-none');
            }else{
                $('.pricing-cont').removeClass('d-none');
            }
        }).trigger('change');

        $('[name="discount_flag"]').change(function() {
            var discountPriceElement = $('.discounted-price-cont input');
            if(this.value == '0'){
                discountPriceElement.val('0.00').prop('readonly', true);
            }else{
                discountPriceElement.prop('readonly', false);
                if(discountPriceElement.val() == '0.00'){
                    discountPriceElement.focus();
                }
            }
        });

        $('[name="discount_flag"]:checked').trigger('change');
    });
</script>