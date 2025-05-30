<form class="ajax" action="{{ route('owner.property.image.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="text" name="property_id" class="d-none property_id" value="{{ $property->id }}">
    <div class="form-card add-property-box bg-off-white theme-border radius-4 p-20">
        <div class="add-property-title border-bottom pb-25 mb-3">
            <h4>{{ __('Property Images and Documents') }}</h4>
        </div>
        <div class="add-property-inner-box bg-white theme-border radius-4 p-20">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h6 class="label-text-title color-heading font-medium font-14">{{ __('Thumbnail') }}</h6>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-25">
                            <div class="show-uploaded-img radius-4 overflow-hidden">
                                <div class="profile-user position-relative d-inline-block">
                                    <img src="{{ $property->thumbnail_image ?? asset('assets/images/users/empty-user.jpg') }}"
                                        class="rounded avatar-xl app-logo-user-profile-image" alt="user-profile-image">
                                    <div class="avatar-xs p-0 rounded-circle app-logo-profile-photo-edit">
                                        <input id="app-logo-profile-img-file-input" type="file"
                                            class="thumbnailImage app-logo-profile-img-file-input"
                                            data-route="{{ route('owner.property.thumbnailImage.update', $property->id) }}">
                                        <label for="app-logo-profile-img-file-input"
                                            class="app-logo-profile-photo-edit avatar-xs">
                                            <span class="avatar-title rounded-circle" title="Upload Image">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="add-property-inner-box bg-white theme-border radius-4 p-20 mt-4">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h6 class="label-text-title color-heading font-medium font-14">{{ __('Gallery') }}</h6>
                </div>
                <div class="col-lg-12">
                
                        <div class="list-unstyled mb-0 d-flex flex-wrap" id="dropzone-preview">
                            @if (count(@$property->propertyImages) > 0)
                                <div class="mt-2" id="dropzone-preview-list">
                                    <div class="dropzone-img-wrap theme-border radius-4 position-relative">
                                        <div class="p-2">
                                            <div class="">
                                                <div class="avatar-sm bg-light rounded text-center">
                                                    <img data-dz-thumbnail class="img-fluid rounded" src="#"
                                                        alt="Dropzone-Image" width="175" height="120">
                                                </div>
                                            </div>
                                            <div class="dropzone-remove-icon">
                                                <button type="button" data-dz-remove
                                                    class="btn btn-sm btn-danger">x</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @forelse(@$property->propertyImages as $propertyImage)
                                <div class="mt-2" id="dropzone-preview-list">
                                    <div class="dropzone-img-wrap theme-border radius-4 position-relative">
                                        <div class="p-2">
                                            <div class="">
                                                <div class="avatar-sm bg-light rounded text-center">
                                                    <img data-dz-thumbnail class="img-fluid rounded"
                                                        src="{{ @$propertyImage?->single_image?->file_url }}"
                                                        alt="{{ @$propertyImage?->single_image?->file_name }}"
                                                        width="175" style="height: 110px;">
                                                </div>
                                            </div>
                                            <div class="dropzone-remove-icon">
                                                <button type="button" data-dz-remove
                                                    class="btn btn-sm btn-danger removeImage"
                                                    data-route="{{ route('owner.property.image.delete', $propertyImage->id) }}">x
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="mt-2" id="dropzone-preview-list">
                                    <div class="dropzone-img-wrap theme-border radius-4 position-relative">
                                        <div class="p-2">
                                            <div class="">
                                                <div class="avatar-sm bg-light rounded text-center">
                                                    <img data-dz-thumbnail class="img-fluid rounded" src="#"
                                                        alt="Dropzone-Image" width="175" height="140">
                                                </div>
                                            </div>
                                            <div class="dropzone-remove-icon">
                                                <button data-dz-remove class="btn btn-sm btn-danger">x</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-25">
                                <div class="dropzone mt-2">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple="multiple">
                                    </div>
                                    <div class="dz-message needsclick">
                                        <div class="dropzone-upload-sign-icon mb-2">
                                            <i class="ri-upload-2-fill"></i>
                                        </div>
                                        <p class="theme-link font-13">{{ __('Browse More Image..') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <input type="button" name="previous" class="imageBack action-button-previous theme-btn mt-25" value="Back">
    <a href="{{ route('owner.property.allProperty') }}" class="action-button theme-btn mt-25">{{ __('Done') }}</a>
</form>


<script>
    (function() {
        function initializeDragAndDrop() {
            const dragItems = document.querySelectorAll('#dropzone-preview-list .dropzone-img-wrap');
            let dragSrcEl = null;

            function handleDragStart(e) {
                dragSrcEl = this;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.outerHTML);

                this.classList.add('moving');
            }

            function handleDragOver(e) {
                if (e.preventDefault) e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                return false;
            }

            function handleDragEnter() {
                this.classList.add('over');
            }

            function handleDragLeave() {
                this.classList.remove('over');
            }

            function handleDrop(e) {
                if (e.stopPropagation) e.stopPropagation();

                if (dragSrcEl !== this) {
                    dragSrcEl.outerHTML = this.outerHTML;
                    this.outerHTML = e.dataTransfer.getData('text/html');

                    // Reinitialize the event listeners since the DOM has changed
                    initializeDragAndDrop();

                    // Update the order of images on the backend
                    updateImageOrder();
                }
                return false;
            }

            function handleDragEnd() {
                dragItems.forEach((item) => {
                    item.classList.remove('over');
                    item.classList.remove('moving');
                });
            }

            // Function to send the new order to the server
            function updateImageOrder() {
                const orderData = [];
                document.querySelectorAll('#dropzone-preview-list .dropzone-img-wrap').forEach((item, index) => {
                    const imageId = item.querySelector('button.removeImage').dataset.route.split('/').pop();
                    orderData.push({
                        id: imageId,
                        order: index + 1
                    });
                });

                // Send the data to the server via AJAX
                $.ajax({
                    url: '/owner/property/images/reorder', // Define your route URL here
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content // CSRF token for security
                    },
                    contentType: 'application/json',
                    data: JSON.stringify(orderData),
                    success: function(response) {
                      
                        toastr.success('Order Updated Successfully', 'Success');
                        console.log('Order updated successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to update order:', error);
                    }
                });
            }

            dragItems.forEach((item) => {
                item.setAttribute('draggable', 'true');
                item.addEventListener('dragstart', handleDragStart, false);
                item.addEventListener('dragenter', handleDragEnter, false);
                item.addEventListener('dragover', handleDragOver, false);
                item.addEventListener('dragleave', handleDragLeave, false);
                item.addEventListener('drop', handleDrop, false);
                item.addEventListener('dragend', handleDragEnd, false);
            });
        }

        initializeDragAndDrop();
    })();
</script>
