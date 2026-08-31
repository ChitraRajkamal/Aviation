@php
  $faqs = $course->faqs && $course->faqs != '[]' ? json_decode($course->faqs) : json_decode('{"name":[""],"value":[""]}');
  $requirements = $course->requirements && $course->requirements != '[]' ? json_decode($course->requirements) : json_decode('{"name":[""]}');
  $outcomes = $course->outcomes && $course->outcomes != '[]' ? json_decode($course->outcomes) : json_decode('{"name":[""]}');
@endphp
<div class="row">
  <div class="col-md-12">
    {{lms_form_label('Requirements', false)}}
    <div class="controls p-3 bg-light">
      <?php foreach ($requirements->name as $k => $d) { ?>
        <div class="entry row">
          <div class="col-md-10">
            <div class="form-group">
              <input type="text" class="form-control form-control-sm requirements" name="requirements[name][]" placeholder="Requirements" 
                value="<?php echo $requirements->name[$k]; ?>" <?php if(!$requirements->name[$k]) echo 'requireds'; ?>  />
              <div class="help-block with-errors empty"></div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-0">
              <?php if ((count($requirements->name) - 1) == 0 || (count($requirements->name) - 1) == $k){ ?>
                <?php if (count($requirements->name) > 1){ ?>
                  <button type="button" class="btn btn-remove remove btn-sm btn-danger gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
                <?php } ?>
                <button type="button" class="btn btn-success btn-sm btn-add" data-load="false"><span class="ph ph-plus"></span></button>
              <?php }else{ ?>
                <button type="button" class="btn btn-danger btn-sm btn-remove gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="col-md-12">
    {{lms_form_label('Outcomes', false)}}
    <div class="controls p-3 bg-light">
      <?php foreach ($outcomes->name as $k => $d) { ?>
        <div class="entry row">
          <div class="col-md-10">
            <div class="form-group">
              <input type="text" class="form-control form-control-sm outcomes" name="outcomes[name][]" placeholder="Outcomes" 
                value="<?php echo $outcomes->name[$k]; ?>" <?php if(!$outcomes->name[$k]) echo 'requireds'; ?>  />
              <div class="help-block with-errors empty"></div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-0">
              <?php if ((count($outcomes->name) - 1) == 0 || (count($outcomes->name) - 1) == $k){ ?>
                <?php if (count($outcomes->name) > 1){ ?>
                  <button type="button" class="btn btn-remove remove btn-sm btn-danger gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
                <?php } ?>
                <button type="button" class="btn btn-success btn-sm btn-add" data-load="false"><span class="ph ph-plus"></span></button>
              <?php }else{ ?>
                <button type="button" class="btn btn-danger btn-sm btn-remove gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <div class="col-md-12">
    {{lms_form_label('FAQs', false)}}
    <div class="controls p-3 bg-light">
      <?php foreach ($faqs->name as $k => $d) { ?>
        <div class="entry row">
          <div class="col-md-5">
            <div class="form-group">
              <input type="text" class="form-control form-control-sm faqs" name="faqs[name][]" placeholder="Question" 
                value="<?php echo $faqs->name[$k]; ?>" <?php if(!$faqs->name[$k]) echo 'requireds'; ?>  />
              <div class="help-block with-errors empty"></div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <input type="text" class="form-control form-control-sm answers" name="faqs[value][]" placeholder="Answer" 
                value="<?php echo $faqs->value[$k]; ?>" <?php if(!$faqs->value[$k]) echo 'requireds'; ?>  />
              <div class="help-block with-errors empty"></div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-0">
              <?php if ((count($faqs->name) - 1) == 0 || (count($faqs->name) - 1) == $k){ ?>
                <?php if (count($faqs->name) > 1){ ?>
                  <button type="button" class="btn btn-remove remove btn-sm btn-danger gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
                <?php } ?>
                <button type="button" class="btn btn-success btn-sm btn-add" data-load="false"><span class="ph ph-plus"></span></button>
              <?php }else{ ?>
                <button type="button" class="btn btn-danger btn-sm btn-remove gconfirm" data-load="false"><span class="ph ph-minus"></span></button>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<style>
.controls .btn-sm { padding: 6px 10px; }
.controls .btn-danger { margin-right: 10px; }
</style>
<script>
    $(document).ready(function() {
        var deleteEntryButton;
        $(document).on('click', '.btn-add', function(e) {
            e.preventDefault();

            var controlForm = $(this).parents('.controls'),
                currentEntry = $(this).parents('.entry:first'),
                lastEntry = $(this).parents('.entry:last');

            if (lastEntry.find('.requirements') && lastEntry.find('.requirements').val() == '') {
                lastEntry.find('.requirements').focus();
                notify({ title: 'Please enter requirement'} );
                return false;
            }

            if (lastEntry.find('.outcomes') && lastEntry.find('.outcomes').val() == '') {
                lastEntry.find('.outcomes').focus();
                notify({ title: 'Please enter outcome'} );
                return false;
            }

            if (lastEntry.find('.faqs') && lastEntry.find('.faqs').val() == '') {
                lastEntry.find('.faqs').focus();
                notify({ title: 'Please FAQ question'} );
                return false;
            }

            if (lastEntry.find('.answers') && lastEntry.find('.answers').val() == '') {
                lastEntry.find('.answers').focus();
                notify({ title: 'Please FAQ answer'} );
                return false;
            }

            var newEntry = $(currentEntry.clone()).appendTo(controlForm)
            newEntry.find('.form-control').val('').prop('required', true);
            newEntry.find('.form-control:first').focus();
            newEntry.find('.gallery').removeClass('d-none');
            newEntry.find('.e-view-image, .old_degree').remove();
            newEntry.find('.with-errors').text('');

            controlForm.find('.entry .remove').remove();
            controlForm.find('.entry:last .btn-add')
                .before(
                    '<button type="button" class="btn btn-sm btn-remove remove btn-danger gconfirm" data-load="false"><span class="ph ph-minus"></span></button>'
                    );
            controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove gconfirm')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="ph ph-minus"></span>');
        }).on('click', '.btn-remove', function(e) {
            deleteEntryButton = $(this);
            if(confirm('Are you sure you want to delete?')){
              var controlForm = deleteEntryButton.parents('.controls');
              deleteEntryButton.parents('.entry:first').remove();
              if (deleteEntryButton.hasClass("remove") || controlForm.find('.entry .btn-remove').length == 1) {
                  controlForm.find('.entry:last .btn-add').remove();
                  controlForm.find('.entry:last .btn-remove').after(
                      '<button type="button" class="btn btn-success btn-add btn-sm" data-load="false"><span class="ph ph-plus"></span></button>'
                  );
                  if (!deleteEntryButton.hasClass("remove") || controlForm.find('.entry .btn-remove').length == 1) {
                      controlForm.find('.entry:last .btn-remove').remove();
                  } else {
                      controlForm.find('.entry:last .btn-remove').addClass("remove");
                  }
              }
            }
        });

        /*$(document).on('mhb_confirmed', '.gconfirm', function(e) {
            var controlForm = deleteEntryButton.parents('.controls');
            deleteEntryButton.parents('.entry:first').remove();
            if (deleteEntryButton.hasClass("remove") || controlForm.find('.entry .btn-remove').length == 1) {
                controlForm.find('.entry:last .btn-add').remove();
                controlForm.find('.entry:last .btn-remove').after(
                    '<button type="button" class="btn btn-success btn-add btn-sm" data-load="false"><span class="ph ph-plus"></span></button>'
                );
                if (!deleteEntryButton.hasClass("remove") || controlForm.find('.entry .btn-remove').length == 1) {
                    controlForm.find('.entry:last .btn-remove').remove();
                } else {
                    controlForm.find('.entry:last .btn-remove').addClass("remove");
                }
            }
        });*/
    });
</script>
