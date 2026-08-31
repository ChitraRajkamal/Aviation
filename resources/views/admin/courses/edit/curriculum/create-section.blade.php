<div class="modal modal-drawer drawer-right" id="ModalAddCourseSection" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title">Save Section</h5>
              <button type="button" class="close mt-0 text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" class="fa fa-close"></span>
              </button>
          </div>
          <div class="modal-body confirm-message">                
              <form id="saveForm" class="autoSubmit p-2" action="{{ route('admin.courses.sections.create') }}" method="POST" data-parsley-validate>
                  <input type="hidden" name="course_id" value="{{$course->id}}">
                  <input type="hidden" name="id" id="section-id" value="0">
                  @csrf
                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{lms_form_label('Title')}}
                            <input type="text" class="form-control" id="section-title" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Section Title')}}"
                              data-parsley-required="true" data-parsley-required-message="Section title is required">
                            @error('title')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                            @error('course_id')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-save"></i> Save</button>
                            <a href="javascript:;" data-dismiss="modal" class="ml-2">Cancel</a>
                        </div>
                    </div>
                </div>
              </form>
          </div>
      </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    @error('section-error')
      $('#section-id').val({{old('id')}});
      $('#ModalAddCourseSection').modal('show');
    @enderror

    $('#ModalAddCourseSection').on('shown.bs.modal', function() {
      $('#section-title').focus();
    });
  });
</script>