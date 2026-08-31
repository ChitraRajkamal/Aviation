<div class="row">
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Title')}}
          <input type="text" class="form-control" name="title" value="{{ old('title', $course->title)}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(courses_basic,title)>
          @error('title')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Category')}}
          <select class="form-control select-two" id="course_category_id" data-placeholder="Choose Category" name="course_category_id" @lmsparsley(courses_basic,course_category_id)>
              <option value="">Choose Category</option>
              @foreach ($categoriesHierarchy as $item)
                <option value="{{$item->id}}" {{ old('course_category_id', $course->course_category_id) == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
              @endforeach
          </select>
          @error('course_category_id')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Course Level')}}
          <select class="form-control select-two" id="level" data-placeholder="Choose Course Level" name="level" @lmsparsley(courses_basic,level)>
              <option value="">Course Level</option>
              @foreach (lms_course_levels() as $item)
                <option value="{{$item}}" {{ old('level', $course->level) == $item ? 'selected' : '' }}>{{$item}}</option>
              @endforeach
          </select>
          @error('level')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
      <div class="form-group">
          {{lms_form_label('Description', false)}}
          <textarea id="description" name="description" class="form-control" rows="4" @lmsparsley(courses_basic,description)>{{ old('description', $course->description)}}</textarea>
          @error('description')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
  <div class="col-md-4">
      <div class="form-group">
          {{lms_form_label('Status')}}
          <select class="form-control select-two" id="status" data-placeholder="Choose Course Status" name="status" @lmsparsley(courses_basic,status)>
              <option value="">Course Status</option>
              @foreach (lms_course_status() as $k => $item)
                <option value="{{$item}}" {{ old('status', $course->status) == $item ? 'selected' : '' }}>{{$item}}</option>
              @endforeach
          </select>
          @error('status')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group">
          {{lms_form_label('Short Description', false)}}
          <textarea name="short_description" class="form-control" rows="4" @lmsparsley(courses_basic,short_description)>{{ old('short_description', $course->short_description)}}</textarea>
          @error('short_description')
              <div class="parsley-errors-list">{{ $message }}</div>
          @enderror
      </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Describe the course',
            tabsize: 2,
            height: 300
        });
    });
</script>