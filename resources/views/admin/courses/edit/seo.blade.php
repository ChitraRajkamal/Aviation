<div class="row">
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Course Page URL / Slug', false)}}
          <input type="text" class="form-control" name="slug" value="{{ old('slug', $course->slug)}}" 
              data-parsley-required="true" data-parsley-required-message="Category slug is required" placeholder="{{__('Enter Course Slug')}}">
          @error('slug')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Meta Title', false)}}
          <input type="text" class="form-control" name="meta_data[title]" value="{{ old('meta_data.title', $course->meta_data['title'] ?? '') }}" placeholder="{{__('Enter Meta Title')}}" >
          @error('meta_data.title')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Meta Description', false)}}
          <textarea name="meta_data[description]" class="form-control" rows="4" placeholder="{{__('Enter Meta Description')}}">{{ old('meta_data.description', $course->meta_data['description'] ?? '') }}</textarea>
          @error('meta_data.description')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Meta Robot', false)}}
          <input type="text" class="form-control" name="meta_data[robot]" value="{{ old('meta_data.robot', $course->meta_data['robot'] ?? '') }}" placeholder="{{__('Enter Meta Robot')}}" >
          @error('meta_data.robot')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Meta Canonical Url', false)}}
          <input type="text" class="form-control" name="meta_data[canonical_url]" value="{{ old('meta_data.canonical_url', $course->meta_data['canonical_url'] ?? '') }}" placeholder="{{__('Enter Meta Canonical Url')}}" data-parsley-type="url"
          data-parsley-type-message="Enter valid url" >
          @error('meta_data.canonical_url')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Og Title', false)}}
          <input type="text" class="form-control" name="meta_data[og_title]" value="{{ old('meta_data.og_title', $course->meta_data['og_title'] ?? '') }}" placeholder="{{__('Enter Og Title')}}" >
          @error('meta_data.og_title')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Og Description', false)}}
          <input type="text" class="form-control" name="meta_data[og_description]" value="{{ old('meta_data.og_description', $course->meta_data['og_description'] ?? '') }}" placeholder="{{__('Enter Og Description')}}" >
          @error('meta_data.og_description')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Og Image', false)}}
          <input type="file" class="form-control-file" id="meta_data[og_image]" name="meta_data[og_image]" accept=".jpg,.jpeg,.png,.fig">
          @error('meta_data.og_image')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
          @if ($course->meta_data['og_image'] ?? '')
            <img src="{{lms_storage($course->meta_data['og_image'])}}" class="img-thumbnail mt-3" style="height: 120px;" alt="">
          @endif
      </div>
  </div>
</div>