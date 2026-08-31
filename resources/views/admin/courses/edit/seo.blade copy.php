<div class="row">
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Course Page URL / Slug')}}
          <input type="text" class="form-control" name="slug" value="{{ old('slug', $course->slug)}}" 
              data-parsley-required="true" data-parsley-required-message="Category slug is required" placeholder="{{__('Enter Course Slug')}}">
          @error('slug')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Meta Title')}}
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
          {{lms_form_label('Meta Title')}}
          <input type="text" class="form-control" data-max-tags="2" id="meta_keywords" name="meta_keywords" placeholder="Add tags (max 3)"
            value="{{ old('title', $course->title)}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(courses_basic,title)>
          @error('title')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
</div>
<style>
  .bootstrap-tagsinput{
    line-height: 32px;
    display: block;
  }
</style>
<script>
  $(document).ready(function() {
    $('#meta_keywords').tagsinput({
      maxTags: 3
    });
  });
</script>