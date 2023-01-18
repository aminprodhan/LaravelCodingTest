@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Product</h1>
    </div>
    <form action="{{ route('product.store') }}" id="idForm" enctype="multipart/form-data" method="post" autocomplete="off" spellcheck="false">
        @csrf
        <section>
            <div class="row">
                <div class="col-md-6">
                    <!--                    Product-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Product</h6>
                        </div>
                        <div class="card-body border">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text"
                                       name="product_name"
                                       id="product_name"
                                       required
                                       placeholder="Product Name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="product_sku">Product SKU</label>
                                <input type="text" name="product_sku"
                                       id="product_sku"
                                       required
                                       placeholder="Product Name"
                                       class="form-control"></div>
                            <div class="form-group mb-0">
                                <label for="product_description">Description</label>
                                <textarea name="product_description"
                                          id="product_description"
                                          required
                                          rows="4"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <!--                    Media-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"><h6
                                class="m-0 font-weight-bold text-primary">Media</h6></div>
                        <div class="card-body border">
                            <div id="file-upload" class="dropzone dz-clickable file-upload">
                                <div class="dz-default dz-message">
                                    <span>Drop files here to upload</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                Variants-->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3"><h6
                                class="m-0 font-weight-bold text-primary">Variants</h6>
                        </div>
                        <div class="card-body pb-0" id="variant-sections">
                        </div>
                        <div class="card-footer bg-white border-top-0" id="add-btn">
                            <div class="row d-flex justify-content-center">
                                <button class="btn btn-primary add-btn" onclick="addVariant(event);">
                                    Add another option
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-header text-uppercase">Preview</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr class="text-center">
                                        <th width="33%">Variant</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                    </tr>
                                    </thead>
                                    <tbody id="variant-previews">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" id="button" class="btn btn-lg btn-primary">Save</button>
            <button type="button" class="btn btn-secondary btn-lg">Cancel</button>
        </section>
    </form>
@endsection

@push('page_js')
    <script type="text/javascript" src="{{ asset('js/product.js') }}"></script>
    <script>

        // Dropzone.autoDiscover = false;
        // var myDropzone = new Dropzone("#idForm", {
        //     autoProcessQueue: false,
        //     autoDiscover : false,
        //     url: "{{ route('product.store') }}",
        //     //maxFilesize: 1,
        //     //acceptedFiles: ".jpeg,.jpg,.png,.gif"
        // });
        // $('#uploadFile').click(function(){
        //    // myDropzone.processQueue();
        // });

        $("#file-upload").dropzone({
            autoProcessQueue: false,
            autoDiscover : false,
            parallelUploads:10,
            uploadMultiple:true,
            url: "{{ route('product.store') }}",
            init: function () {
                var myDropzone = this;
                // Update selector to match your button
                $("#button").click(function (e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                });
                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    console.log("append....",file);
                    var data = $('#idForm').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                    //formData.append('files_2[]',file);
                });
            },
            //addRemoveLinks: true,
            success: function (file, response) {
                //
                window.location.reload();
                console.log("successs");
            },
            error: function (file, response) {
                console.log("error");
            }
        });



        // $("#idForm").submit(function(e) {
        //     e.preventDefault();
        //     myDropzone.processQueue();

        //     var form = $(this);
        //     var actionUrl = form.attr('action');
        //     $.ajax({
        //         type: "POST",
        //         url: actionUrl,
        //         data: form.serialize(), // serializes the form's elements.
        //         success: function(data)
        //         {
        //             alert(data); // show response from the php script.
        //         }
        //     });

        // });
    </script>
@endpush
