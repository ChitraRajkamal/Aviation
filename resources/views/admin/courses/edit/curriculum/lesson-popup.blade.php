<div class="modal modal-drawer drawer-right" id="ModalAddCourseLesson" data-backdrop="static" data-keyboard="false"
    tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">Save Lesson</h5>
                <button type="button" class="close mt-0 text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-close"></span>
                </button>
            </div>
            <div class="modal-body confirm-message">
                <iframe id="iframe-layout-less" src="" style="width: 100%; height: 100%; border: none;">Please wait...</iframe>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#ModalAddCourseLesson').on('shown.bs.modal', function() {
            $('#lesson-title').focus();
        });
    });
</script>
