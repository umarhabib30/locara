<script>
    $(document).ready(function() {


        // --------- view meter details ---------------
        $('body').on('click', '.view_meter_details', function(e) {
            e.preventDefault();
            let id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('owner.meter.details', ':id') }}".replace(':id', id),
                type: "GET",
                success: function(response) {
                    console.log(response);
                    $('#meter_id_for_edit').val(id);
                    $('#view_serial_number').val(response.meter.serial_number);

                    let propertyDropdown = $('#view_property_type');
                    propertyDropdown.empty().append(
                        '<option value="">--{{ __('Select Option') }}--</option>');
                    $.each(response.properties, function(index, property) {
                        let isSelected = property.id == response.meter.property_id ?
                            'selected' : '';
                        propertyDropdown.append(
                            `<option value="${property.id}" ${isSelected}>${property.name}</option>`
                        );
                    });

                    let unitDropdown = $('#view_unit_type');
                    unitDropdown.empty().append(
                        '<option value="">--{{ __('Select Option') }}--</option>');
                    $.each(response.units, function(index, unit) {
                        let isSelected = unit.id == response.meter.unit_id ?
                            'selected' : '';
                        unitDropdown.append(
                            `<option value="${unit.id}" ${isSelected}>${unit.unit_name}</option>`
                        );
                    });

                    $('#view_meter_type').val(response.meter.meter_type);

                    let meterHistoryContainer = $('#meter-form-container1');
                    meterHistoryContainer.empty();

                    $.each(response.histories, function(index, history) {
                        let imageUrl = history.asset_url ? history.asset_url : '';
                        let imageContent = imageUrl ?
                            `<img src="${imageUrl}" alt="Meter Image" class="img-preview enlarge-image" style="width: 100px; height: 50px; object-fit: cover; border: 1px solid #ccc;">` :
                            `<div class="img-preview d-flex justify-content-center align-items-center" style="width: 100px; height: 50px; background: #e9ecef; border: 1px solid #ccc;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><defs><style>.cls-1{fill:none;}</style></defs><title>no-image</title><path d="M30,3.4141,28.5859,2,2,28.5859,3.4141,30l2-2H26a2.0027,2.0027,0,0,0,2-2V5.4141ZM26,26H7.4141l7.7929-7.793,2.3788,2.3787a2,2,0,0,0,2.8284,0L22,19l4,3.9973Zm0-5.8318-2.5858-2.5859a2,2,0,0,0-2.8284,0L19,19.1682l-2.377-2.3771L26,7.4141Z"/><path d="M6,22V19l5-4.9966,1.3733,1.3733,1.4159-1.416-1.375-1.375a2,2,0,0,0-2.8284,0L6,16.1716V6H22V4H6A2.002,2.002,0,0,0,4,6V22Z"/><rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" class="cls-1" width="32" height="32"/></svg>
                    </div>`;

                        let meterRow = `
                    <div class="row meter-form">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Date') }}</label>
                                    <input type="date" name="meter_date[]" class="form-control" value="${history.date}" disabled>
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Count') }}</label>
                                    <input type="text" 
                                           name="meter_count[]" 
                                           class="form-control" 
                                           pattern="[0-9]*[,.]?[0-9]+"
                                           placeholder="{{ __('xxxx,xxx') }}"
                                           value="${history.count}"
                                           onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 44 || event.charCode === 46"
                                           oninput="this.value = this.value.replace(/[^0-9,\.]/g, '');" disabled>
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Unit') }}</label>
                                    <input type="text" name="meter_unit[]" class="form-control meter_unit_input" readonly style='background: #e9ecef;' value="${history.unit}" disabled>
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Picture') }}</label>
                                    <div class="d-flex align-items-center">
                                        <input type="hidden" name="meter_picture[]" class="meter-picture-input" value="${history.image}">
                                        ${imageContent}
                                        <input type="file" class="d-none file-input" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                        meterHistoryContainer.append(meterRow);
                    });

                    $('#viewMeterDetailsModel').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });


        // ------ open enlarged image -----------
        document.body.addEventListener("click", function(event) {
            if (event.target.classList.contains("enlarge-image")) {
                let imageUrl = event.target.src;
                document.getElementById("modalImage").src = imageUrl;
                let imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
                imageModal.show();
            }
        });

        // ------ edit model for meter ----------
        $('body').on('click', '#update_meter_btn', function(e) {
            let id = $('#meter_id_for_edit').val();
            $.ajax({
                url: "{{ route('owner.meter.details', ':id') }}".replace(':id', id),
                type: 'GET',
                success: function(response) {
                    $('#edit_meter_id').val(response.meter.id);
                    $('#edit_meter_serial_number').val(response.meter.serial_number);

                    let propertyDropdown = $('#edit_property_type');
                    propertyDropdown.empty().append(
                        '<option value="">--{{ __('Select Option') }}--</option>');
                    $.each(response.properties, function(index, property) {
                        let isSelected = property.id == response.meter.property_id ?
                            'selected' : '';
                        propertyDropdown.append(
                            `<option value="${property.id}" ${isSelected}>${property.name}</option>`
                        );
                    });

                    let unitDropdown = $('#edit_unit_type');
                    unitDropdown.empty().append(
                        '<option value="">--{{ __('Select Option') }}--</option>');
                    $.each(response.units, function(index, unit) {
                        let isSelected = unit.id == response.meter.unit_id ?
                            'selected' : '';
                        unitDropdown.append(
                            `<option value="${unit.id}" ${isSelected}>${unit.unit_name}</option>`
                        );
                    });

                    $('#edit_meter_type').val(response.meter.meter_type);

                    let meterHistoryContainer = $('#edit-meter-form-container');
                    meterHistoryContainer.empty();

                    $.each(response.histories, function(index, history) {
                        let imageUrl = history.asset_url ? history.asset_url : '';
                        let imageContent = imageUrl ?
                            `<img src="${imageUrl}" alt="Meter Image" class="img-preview" style="width: 100px; height: 50px; object-fit: cover; border: 1px solid #ccc;">` :
                            `<div class="img-preview d-flex justify-content-center align-items-center" style="width: 100px; height: 50px; background: #e9ecef; border: 1px solid #ccc;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><defs><style>.cls-1{fill:none;}</style></defs><title>no-image</title><path d="M30,3.4141,28.5859,2,2,28.5859,3.4141,30l2-2H26a2.0027,2.0027,0,0,0,2-2V5.4141ZM26,26H7.4141l7.7929-7.793,2.3788,2.3787a2,2,0,0,0,2.8284,0L22,19l4,3.9973Zm0-5.8318-2.5858-2.5859a2,2,0,0,0-2.8284,0L19,19.1682l-2.377-2.3771L26,7.4141Z"/><path d="M6,22V19l5-4.9966,1.3733,1.3733,1.4159-1.416-1.375-1.375a2,2,0,0,0-2.8284,0L6,16.1716V6H22V4H6A2.002,2.002,0,0,0,4,6V22Z"/><rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" class="cls-1" width="32" height="32"/></svg>
                    </div>`;

                        let meterRow = `
                    <div class="row edit-meter-form">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Date') }}</label>
                                    <input type="date" name="meter_date[]" class="form-control" value="${history.date}">
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Count') }}</label>
                                    <input type="text" 
                                           name="meter_count[]" 
                                           class="form-control" 
                                           pattern="[0-9]*[,.]?[0-9]+"
                                           placeholder="{{ __('xxxx,xxx') }}"
                                           value="${history.count}"
                                           onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 44 || event.charCode === 46"
                                           oninput="this.value = this.value.replace(/[^0-9,\.]/g, '');">
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Unit') }}</label>
                                    <input type="text" name="meter_unit[]" class="form-control meter_unit_input" readonly style='background: #e9ecef;' value="${history.unit}">
                                </div>
                                <div class="col-md-3 mb-25">
                                    <label class="label-text-title font-medium mb-2">{{ __('Meter Picture') }}</label>
                                    <div class="d-flex align-items-center">
                                        <input type="hidden" name="meter_picture[]" class="meter-picture-input" value="${history.image}">
                                        ${imageContent}
                                        <input type="file" class="d-none file-input" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" style="display: flex; align-items: center;">
                            <button type="button" class="p-1 tbl-action-btn"><i class="ri-upload-2-fill"></i></button>
                            <button type="button" class="p-1 tbl-action-btn remove-meter-row-editmodel" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="iconify iconify--ep" width="1em" height="1em" viewBox="0 0 1024 1024">
                                    <path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32"></path>
                                </svg>
                            </button>
                        </div>
                    </div>`;
                        meterHistoryContainer.append(meterRow);
                    });
                },
                error: function(error) {}
            });

            $('#viewMeterDetailsModel').modal('hide');
            $('#editMeterModel').modal('show');
        });

        // Open modal
        $('body').on('click', '#add_meter_btn', function(e) {
            e.preventDefault();
            $('#informationModal').modal('show');
        });

        // Add new meter row
        $("#add-meter-btn").click(function() {
            let newRow = $(".meter-form:first").clone();

            // Clear input values except for meter_unit
            newRow.find("input").not('[name="meter_unit[]"]').val("");

            newRow.find(".img-preview").hide(); // Hide cloned image preview
            newRow.find(".file-input").val(""); // Clear file input
            newRow.find(".meter-picture-input").val(""); // Clear hidden input
            newRow.find(".image-placeholder").show(); // Show placeholder input

            $("#meter-form-container").append(newRow);
        });


        // Add new meter row in edit model 
        $("#add-meter-btn-edit").click(function() {
            // Get the meter unit value from the last existing row
            let lastMeterUnitValue = $(".edit-meter-form:last").find('input[name="meter_unit[]"]')
                .val() || '';

            // Create the new row with the retained meter unit value
            let newRow = $(`
                <div class="row edit-meter-form">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-3 mb-25">
                                <label class="label-text-title font-medium mb-2">Meter Date</label>
                                <input type="date" name="meter_date[]" class="form-control">
                            </div>
                            <div class="col-md-3 mb-25">
                                <label class="label-text-title font-medium mb-2">Meter Count</label>
                                <input type="text" 
                                       name="meter_count[]" 
                                       class="form-control" 
                                       pattern="[0-9]*[,.]?[0-9]+"
                                       placeholder="xxxx,xxxx"
                                       onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 44 || event.charCode === 46"
                                       oninput="this.value = this.value.replace(/[^0-9,\.]/g, '');">
                            </div>
                            <div class="col-md-3 mb-25">
                                <label class="label-text-title font-medium mb-2">Meter Unit</label>
                                <input type="text" name="meter_unit[]" class="form-control meter_unit_input" readonly style='background: #e9ecef;' value="${lastMeterUnitValue}">
                            </div>
                            <div class="col-md-3 mb-25">
                                <label class="label-text-title font-medium mb-2">Meter Picture</label>
                                <div class="d-flex align-items-center">
                                    <input type="hidden" name="meter_picture[]" class="meter-picture-input">
                                    <input type="text" class="form-control image-placeholder">
                                    <img src="" alt="Selected Image" class="img-preview" style="display:none; width: 100px; height: 50px; object-fit: cover; border: 1px solid #ccc;">
                                    <input type="file" class="d-none file-input" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2" style="display: flex; align-items: center;">
                        <button type="button" class="p-1 tbl-action-btn"><i class="ri-upload-2-fill"></i></button>
                        <button type="button" class="p-1 tbl-action-btn remove-meter-row-editmodel" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="iconify iconify--ep" width="1em" height="1em" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64zm64 0h192v-64H416zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32m192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);

            // Append the new row to the container
            $("#edit-meter-form-container").append(newRow);
        });




        // Remove row - prevent opening image selector
        $(document).on("click", ".remove-meter-row", function(e) {
            e.stopPropagation(); // Stop event bubbling to prevent triggering file input
            if ($(".meter-form").length > 1) {
                $(this).closest(".meter-form").remove();
            }
        });

        // Remove row - prevent opening image selector from edit model
        $(document).on("click", ".remove-meter-row-editmodel", function(e) {
            e.stopPropagation(); // Stop event bubbling to prevent triggering file input
            if ($(".edit-meter-form").length > 1) {
                $(this).closest(".edit-meter-form").remove();
            }
        });

        // ---------- Get unit for add meter model --------------
        var thisStateSelector;
        $(document).on('change', '.property_id', function() {
            thisStateSelector = $(this);
            var route = "{{ route('owner.property.getPropertyUnits') }}";
            commonAjax('GET', route, getUnitsRes, getUnitsRes, {
                'property_id': $(thisStateSelector).val()
            });
        });

        function getUnitsRes(response) {
            if (response.data) {
                var unitOptionsHtml = response.data.map(function(opt) {
                    return '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
                }).join('');
                var unitsHtml = '<option value="">--Select Unit--</option>' + unitOptionsHtml
                $('.unit_id').html(unitsHtml);
            } else {
                $('.unit_id').html('<option value="">--Select Unit--</option>');
            }
        }


        // ---------- Get unit for filters --------------
        var thisStateSelector;
        $(document).on('change', '#search_property', function() {
            thisStateSelector = $(this);
            var route = "{{ route('owner.property.getPropertyUnits') }}";
            commonAjax('GET', route, getUnitsResSearch, getUnitsResSearch, {
                'property_id': $(thisStateSelector).val()
            });
        });

        function getUnitsResSearch(response) {
            if (response.data) {
                var unitOptionsHtml = response.data.map(function(opt) {
                    return '<option value="' + opt.id + '">' + opt.unit_name + '</option>';
                }).join('');
                var unitsHtml = '<option value="0">--Select Unit--</option>' + unitOptionsHtml
                $('#search_unit').html(unitsHtml);
            } else {
                $('#search_unit').html('<option value="0">--Select Unit--</option>');
            }
        }





        // Handle image selection - ensure it only triggers on the upload button
        $(document).on("click", ".tbl-action-btn:not(.remove-meter-row)", function(e) {
            let fileInput = $(this).closest(".meter-form").find(".file-input");
            fileInput.click();
        });


        // Handle image selection - ensure it only triggers on the upload button edit model
        $(document).on("click", ".tbl-action-btn:not(.remove-meter-row-editmodel)", function(e) {
            let fileInput = $(this).closest(".edit-meter-form").find(".file-input");
            fileInput.click();
        });

        // Handle image preview and replacement
        $(document).on("change", ".file-input", function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let meterForm = $(event.target).closest(".meter-form");
                    let imgPreview = meterForm.find(".img-preview");
                    let hiddenInput = meterForm.find(".meter-picture-input");
                    let placeholderInput = meterForm.find(".image-placeholder");

                    imgPreview.attr("src", e.target.result).show();
                    hiddenInput.val(e.target.result); // Store base64 if needed
                    placeholderInput.hide(); // Hide placeholder input
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle image preview and replacement
        $(document).on("change", ".file-input", function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let meterForm = $(event.target).closest(".edit-meter-form");
                    let imgPreview = meterForm.find(".img-preview");
                    let hiddenInput = meterForm.find(".meter-picture-input");
                    let placeholderInput = meterForm.find(".image-placeholder");

                    imgPreview.attr("src", e.target.result).show();
                    hiddenInput.val(e.target.result); // Store base64 if needed
                    placeholderInput.hide(); // Hide placeholder input
                };
                reader.readAsDataURL(file);
            }
        });

        // ------ adding meter unit value from meter type ----------
        $(document).on('change', '#meter_type_select', function(e) {
            e.preventDefault();
            let val = $(this).val();
            const meterUnits = {
                "1": "kWh",
                "2": "m³",
                "3": "m³",
                "4": "GJ",
                "5": "m³"
            };
            let value = meterUnits[val];
            $('.meter_unit_input').val(value);
        });

        // ------ adding meter unit value from meter type in edit model ----------
        $(document).on('change', '#edit_meter_type', function(e) {
            e.preventDefault();
            let val = $(this).val();
            const meterUnits = {
                "1": "kWh",
                "2": "m³",
                "3": "m³",
                "4": "GJ",
                "5": "m³"
            };
            let value = meterUnits[val];
            $('.meter_unit_input').val(value);
        });

        // --------- add meter form validation ---------------
        document.querySelector("#informationModal form").addEventListener("submit", function(e) {
            let isValid = true; // Assume form is valid initially
            let form = document.querySelector(
                "#informationModal form"); // Select only the form inside the Add Meter modal

            // Clear previous error messages and reset borders
            form.querySelectorAll(".text-danger").forEach(el => el.remove());
            form.querySelectorAll(".form-control, .form-select").forEach(el => el.classList.remove(
                "is-invalid"));

            // Get form inputs inside the Add Meter modal only
            let serialNumber = form.querySelector("input[name='serial_number']");
            let property = form.querySelector("select[name='property_id']");
            let unit = form.querySelector("select[name='unit_id']");
            let meterType = form.querySelector("select[name='meter_type']");
            let meterUnit = form.querySelector("input[name='meter_unit[]']"); // Select meter unit field

            // Validation function
            function showError(element, message) {
                if (!element || !element.value.trim()) { // Only show error if the field is empty
                    let errorSpan = document.createElement("span");
                    errorSpan.classList.add("text-danger");
                    errorSpan.innerText = message;
                    element.parentNode.appendChild(errorSpan);
                    element.classList.add("is-invalid"); // Add red border
                    isValid = false;
                }
            }

            // Validate Main Form Fields
            showError(serialNumber, "Meter Serial Number is required.");
            showError(property, "Property selection is required.");
            showError(unit, "Unit selection is required.");
            showError(meterType, "Meter Type is required.");
            showError(meterUnit, "Meter Unit is required."); // Validation for the meter unit field

            // Validate ALL Meter History inputs inside the modal (Including Dynamically Added Rows)
            let meterRows = form.querySelectorAll(
                ".meter-form"); // Get all meter history rows inside the modal
            if (meterRows.length === 0) {
                isValid = false;
                alert("At least one meter history entry is required.");
            }

            meterRows.forEach((row) => {
                let meterDate = row.querySelector("input[name='meter_date[]']");
                let meterCount = row.querySelector("input[name='meter_count[]']");

                if (!meterDate || !meterCount) {
                    isValid = false;
                }

                showError(meterDate, "Meter Date is required.");
                showError(meterCount, "Meter Count is required.");
            });

            // Prevent form submission only if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });


        // --------- edit meter form validation -----------
        document.querySelector("#editMeterModel form").addEventListener("submit", function(e) {
            let isValid = true; // Assume form is valid initially
            let form = document.querySelector("#editMeterModel form");

            // Clear previous error messages and reset borders
            form.querySelectorAll(".text-danger").forEach(el => el.remove());
            form.querySelectorAll(".form-control, .form-select").forEach(el => el.classList.remove(
                "is-invalid"));

            // Get form inputs inside the Edit Meter modal
            let serialNumber = form.querySelector("input[name='serial_number']");
            let property = form.querySelector("select[name='property_id']");
            let unit = form.querySelector("select[name='unit_id']");
            let meterType = form.querySelector("select[name='meter_type']");

            // Validation function
            function showError(element, message) {
                if (!element || !element.value.trim()) {
                    let errorSpan = document.createElement("span");
                    errorSpan.classList.add("text-danger");
                    errorSpan.innerText = message;
                    element.parentNode.appendChild(errorSpan);
                    element.classList.add("is-invalid"); // Add red border
                    isValid = false;
                }
            }

            // Validate Main Form Fields
            showError(serialNumber, "Meter Serial Number is required.");
            showError(property, "Property selection is required.");
            showError(unit, "Unit selection is required.");
            showError(meterType, "Meter Type is required.");

            // Validate ALL Meter History inputs inside the modal (Including Dynamically Added Rows)
            let meterRows = form.querySelectorAll(".edit-meter-form"); // FIXED CLASS SELECTOR
            if (meterRows.length === 0) {
                isValid = false;
                alert("At least one meter history entry is required.");
            }

            meterRows.forEach((row) => {
                let meterDate = row.querySelector("input[name='meter_date[]']");
                let meterCount = row.querySelector("input[name='meter_count[]']");

                showError(meterDate, "Meter Date is required.");
                showError(meterCount, "Meter Count is required.");
            });

            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });


        // ------ meter count input field decimal validation -----------
        document.querySelectorAll('input[name="meter_count[]"]').forEach(input => {
            input.addEventListener('blur', function() {
                // Format number with comma as decimal separator
                let value = this.value.replace(/[^\d.,]/g, '');
                value = value.replace(/\./, ',');
                this.value = value;
            });
        });




        //-------- delete the meter -------
        $('body').on('click', '.delete_btn', function(e) {
            e.preventDefault();

            let id = $(this).attr('delete'); // Get meter ID
            if (!id) {
                Swal.fire("Error!", "Invalid meter ID.", "error");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                console.log("Swal Result:", result); // Debugging step

                // Allow deletion only if user confirms
                if (result.dismiss !== 'cancel') {
                    $.ajax({
                        url: "{{ route('owner.meter.delete', ':id') }}".replace(':id',
                            id),
                        type: "GET",
                        success: function(data) {

                            if (data.success) {
                                Swal.fire("Deleted!", "The meter has been deleted.",
                                    "success");
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire("Error!", "Failed to delete the meter.",
                                    "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });



    });

    (function($) {
        "use strict";
        var meterTable;

        // Initialize DataTable
        meterTable = $('#meterTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            responsive: true,
            ajax: {
                url: $('#meterDataRoute').val(),
                data: function(d) {
                    d.property = $('#search_property').val();
                    d.unit = $('#search_unit').val();
                    d.meter_type = $('#search_meter_type').val();
                }
            },
            order: [0, 'desc'],
            ordering: false,
            autoWidth: false,
            dom: '<"d-flex justify-content-between align-items-center flex-wrap mb-2"' +
                '<"dataTables_filter_wrapper d-flex align-items-center gap-3 flex-grow-1"f>' +
                '<"dataTables_length_wrapper flex-shrink-0"l>' +
                '>rt' +
                '<"d-flex justify-content-between align-items-center flex-wrap mt-2"' +
                '<"dataTables_info_wrapper"i>' +
                '<"dataTables_paginate_wrapper"p>' +
                '>',

            drawCallback: function() {
                $(".dataTables_length select").addClass("form-select form-select-sm");
                $(".dataTables_filter input").addClass("form-control form-select-sm").removeAttr(
                    "placeholder");
                $(".dataTables_filter label").addClass("d-flex align-items-center gap-2 mb-0");
                $(".dataTables_length label").addClass("d-flex flex-row align-items-center gap-1");
                $(".dataTables_info").addClass("text-start");
                $(".dataTables_paginate").addClass("text-end");
            },
            language: {
                'paginate': {
                    'previous': '<span class="iconify" data-icon="icons8:angle-left"></span>',
                    'next': '<span class="iconify" data-icon="icons8:angle-right"></span>'
                }
            },
            columns: [{
                    "data": "serial_number",
                    "name": "meters.serial_number"
                },
                {
                    "data": "meter_type",
                    "name": "meters.meter_type",
                    "render": function(data) {
                        return getMeterTypeBadge(data);
                    }
                },
                {
                    "data": "property",
                    "name": "properties.name",
                    "render": function(data, type, row) {
                        return `<h6>${row.property}</h6><p class="font-13">${row.unitname || '-'}</p>`;
                    }
                },
                {
                    "data": "count",
                    "name": "meter_histories.count"
                },
                {
                    "data": "unit",
                    "name": "meter_histories.unit"
                },
                {
                    "data": "action",
                    "class": "text-end",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Inject Filters Before Search Box in Same Line with Increased Width
        $(".dataTables_filter").html(`
            <div class="d-flex gap-2" style="width:110% !important;">
                <label class="fw-bold mt-1">Search:</label>
                <input type="search" class="form-control form-select-sm flex-grow-1" aria-controls="meterTable">
                <select class="form-select form-select-sm flex-grow-1" id="search_property">
                    <option value="">Select Property</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
                <select class="form-select form-select-sm flex-grow-1" id="search_unit">
                    <option value="">Select Unit</option>
                </select>
                <select class="form-select form-select-sm flex-grow-1" id="search_meter_type">
                    <option value="">Select Meter Type</option>
                    <option value="1">Electricity (kWh)</option>
                    <option value="2">Hot Water (m³)</option>
                    <option value="3">Cold Water (m³)</option>
                    <option value="4">Heating (GJ)</option>
                    <option value="5">Gas (m³)</option>
                </select>
            </div>
        `);

        // Apply filters on change
        $('#search_property, #search_unit, #search_meter_type').on('change', function() {
            meterTable.draw();
        });
        $(".dataTables_filter input").on('keyup', function() {
            meterTable.search($(this).val()).draw();
        });

        function getMeterTypeBadge(typeId) {
            let meterTypeMap = {
                1: {
                    icon: "ri-flashlight-fill",
                    text: "Electricity",
                    class: "status-btn-orange"
                },
                2: {
                    icon: "ri-drop-fill",
                    text: "Hot Water",
                    class: "status-btn-red"
                },
                3: {
                    icon: "ri-drop-fill",
                    text: "Cold Water",
                    class: "status-btn-blue"
                },
                4: {
                    icon: "ri-temp-cold-fill",
                    text: "Heating",
                    class: "status-btn-red"
                },
                5: {
                    icon: "ri-fire-fill",
                    text: "Gas",
                    class: "status-btn-red"
                }
            };

            return meterTypeMap[typeId] ?
                `<div class="status-btn ${meterTypeMap[typeId].class} font-13 radius-4">
            <i class="${meterTypeMap[typeId].icon}"></i> ${meterTypeMap[typeId].text}
        </div>` :
                `<div class="status-btn status-btn-gray font-13 radius-4">Unknown</div>`;
        }

    })(jQuery);
</script>
