<h1>Curriculum</h1>
<div class="modal modal-drawer drawer-right" id="ConfirmModal111" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Action Confirmation</h5>
                <button type="button" class="close mt-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-close"></span>
                </button>
            </div>
            <div class="modal-body confirm-message">
                <p class="mb-0">Are you sure you want to do this?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default no" data-dismiss="modal">Not now</button>
                <button type="button" class="btn btn-primary yes ">Yes, i do</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#ConfirmModal111').modal('show');
    });
</script>