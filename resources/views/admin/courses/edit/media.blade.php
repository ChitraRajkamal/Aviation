<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {{ lms_form_label('Thumbnail', false) }} <span class="file-resolution text-muted">[400 x 280]</span>
            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
            @error('thumbnail')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
            @if ($course->thumbnail)
                <img src="{{ lms_storage($course->thumbnail) }}" class="img-thumbnail mt-3" style="height: 140px;"
                    alt="">
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {{ lms_form_label('Banner', false) }} <span class="file-resolution text-muted">[1200 x 250]</span>
            <input type="file" class="form-control-file" id="banner" name="banner" accept=".jpg,.jpeg,.png,.fig">
            @error('banner')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
            @if ($course->banner)
                <img src="{{ lms_storage($course->banner) }}" class="img-thumbnail mt-3" style="height: 140px;"
                    alt="">
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {{ lms_form_label('Video Type') }}
            <select class="form-control select-two" id="is_online_video" data-placeholder="Choose Video Type"
                name="is_online_video" @lmsparsley(courses_media, is_online_video)>
                <option value="">Video Type</option>
                @foreach (lms_video_type() as $k => $item)
                    <option value="{{ $k }}"
                        {{ old('is_online_video', $course->is_online_video) == $k ? 'selected' : '' }}>
                        {{ $item }}</option>
                @endforeach
            </select>
            @error('is_online_video')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group upload-cont">
            {{ lms_form_label('Choose Video', false) }}
            <input type="file" class="form-control-file" id="uploaded_video_url" name="uploaded_video_url"
                accept=".mp4,.webm,.ogg">
            @error('uploaded_video_url')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
            @if ($course->uploaded_video_url)
                <a href="{{ lms_storage($course->uploaded_video_url) }}" target="_blank"
                    class="btn btn-dark btn-block btn-sm mt-3"><i class="ph ph-link"></i> View it in new tab</a>
            @endif
        </div>
        <div class="form-group online-video-cont">
            {{ lms_form_label('Video URL', false) }}
            <input type="text" class="form-control" name="online_video_url"
                value="{{ old('online_video_url', $course->online_video_url) }}"
                placeholder="{{ __('Enter online video url') }}" @lmsparsley(courses_basic, online_video_url)>
            @error('online_video_url')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
            @if ($course->online_video_url)
                <a href="{{ $course->online_video_url }}" target="_blank" class="btn btn-dark btn-block btn-sm mt-3"><i
                        class="ph ph-link"></i> View it in new tab</a>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-3">
        <div class="form-group">
            {{ lms_form_label('Gallery Images', false) }}
            <span class="file-resolution text-muted">[Multiple images allowed]</span>

            <input type="file" class="form-control-file" id="gallery_images" name="gallery_images[]"
                accept=".jpg,.jpeg,.png,.webp" multiple>

            @error('gallery_images')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror

            @error('gallery_images.*')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror
        </div>

        @if ($course->galleryImages->count())
            <div class="row mt-3">
                @foreach ($course->galleryImages as $image)
                    <div class="col-md-3 mb-3 gallery-image-card">
                        <div class="card h-100">
                            <img src="{{ lms_storage($image->image) }}" class="card-img-top img-thumbnail"
                                style="height:160px;width:100%;object-fit:cover;" alt="{{ $image->alt_text }}">

                            <div class="card-body p-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control gallery-alt-text"
                                        placeholder="Alt text for SEO" value="{{ $image->alt_text }}" maxlength="255">

                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary save-gallery-alt-text px-2"
                                            data-url="{{ route('admin.courses.gallery-images.alt-text', $image) }}">
                                            <i class="ph ph-floppy-disk mr-0"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-danger btn-sm btn-block delete-gallery-image"
                                    data-url="{{ route('admin.courses.gallery-images.destroy', $image) }}">
                                    <i class="ph ph-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#is_online_video').change(function() {
            $('.upload-cont, .online-video-cont').addClass('d-none');
            if (this.value == '0') { // Uploaded video
                $('.upload-cont').removeClass('d-none');
                $('.online-video-cont').addClass('d-none');
            } else {
                $('.upload-cont').addClass('d-none');
                $('.online-video-cont').removeClass('d-none');
            }
        }).trigger('change');

        $('[name="discount_flag"]').change(function() {
            if (this.value == '0') {
                $('.discounted-price-cont input').val('').prop('disabled', true);
            } else {
                $('.discounted-price-cont input').val('').prop('disabled', false).focus();
            }
        }).trigger('change');

        $(document).on('click', '.delete-gallery-image', function() {
            if (!confirm('Are you sure you want to delete this image?')) return;

            const btn = $(this);
            const card = btn.closest('.gallery-image-card');

            btn.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i> Delete');

            $.ajax({
                url: btn.data('url'),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        notify({
                            title: 'Image deleted',
                            type: 'success'
                        });

                        card.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function(xhr) {
                    notify({
                        title: xhr.responseJSON?.message || 'Unable to delete image'
                    });

                    btn.prop('disabled', false).html('<i class="ph ph-trash"></i> Delete');
                }
            });
        });

        $(document).on('click', '.save-gallery-alt-text', function() {
            const btn = $(this);
            const input = btn.closest('.input-group').find('.gallery-alt-text');
            if (!input.val()) {
                notify({
                    title: 'Please enter alt text'
                });
                input.focus();
                return false;
            }

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            input.removeClass('is-invalid');

            $.ajax({
                url: btn.data('url'),
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    alt_text: input.val()
                },
                success: function(res) {
                    notify({
                        title: res.message,
                        type: 'success'
                    });
                },
                error: function(xhr) {
                    let message = 'Unable to update alt text';

                    if (xhr.status === 422 && xhr.responseJSON?.errors?.alt_text) {
                        message = xhr.responseJSON.errors.alt_text[0];
                        input.addClass('is-invalid');
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    notify({
                        title: message,
                        type: 'danger'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="ph ph-floppy-disk"></i>');
                }
            });
        });

        $(document).on('keypress', '.gallery-alt-text', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $(this).closest('.input-group').find('.save-gallery-alt-text').click();
            }
        });
    });
</script>
