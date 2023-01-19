@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
    </div>
    <form action="{{ route('product.store') }}"
        id="idForm"
        enctype="multipart/form-data"
        method="post"
        autocomplete="off" spellcheck="false">
        @csrf
        <input type="hidden" name="updateId" value="{{$product->id}}" />
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
                                       value="{{$product->title}}"
                                       required
                                       placeholder="Product Name"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="product_sku">Product SKU</label>
                                <input type="text" name="product_sku"
                                       id="product_sku"
                                       value="{{$product->sku}}"
                                       required
                                       placeholder="Product Name"
                                       class="form-control"></div>
                            <div class="form-group mb-0">
                                <label for="product_description">Description</label>
                                <textarea name="product_description"
                                          id="product_description"
                                          required
                                          rows="4"
                                          class="form-control">{{$product->description}}</textarea>
                            </div>
                        </div>
                    </div>
                    <!--                    Media-->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"><h6
                                class="m-0 font-weight-bold text-primary">Media</h6></div>
                        <div class="card-body border">
                            <div id="file-upload" class="dropzone dz-clickable file-upload">
                                <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($product->getProductImages as $val)
                            <div class="col-3">
                                <img style="width: 100px;height:100px" src="{{$val->file_path}}" /><br>
                                <button onclick="handlePImageDelete(this,{{$val->id}})" type="button" class="btn btn-lg btn-danger">Delete</button>
                            </div>
                        @endforeach
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
            <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary">Save</button>
            <button type="button" class="btn btn-secondary btn-lg">Cancel</button>
        </section>
    </form>
@endsection

@push('page_js')
    <script>
        var variants=@json($product->variantTransaction->groupBy("variant_id"));
        var variant_prices=@json($product->getVariantsPrice);
    </script>
    <script type="text/javascript" src="{{ asset('js/product.js') }}"></script>
    <script>


        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#idForm", {
            autoProcessQueue: false,
            autoDiscover : false,
            parallelUploads:10,
            uploadMultiple:true,
            url: "{{ route('product.store') }}",
            //maxFilesize: 1,
            //acceptedFiles: ".jpeg,.jpg,.png,.gif"
        });

        $(document).ready(function () {
            Object.keys(variants).forEach(function(key){
                console.log("kkk=",key);
                addVariantTemplate(key,variants[key]);
            })
            setPrices(variant_prices);

        });
        function setPrices(variant_prices){
            var tableBody='';
            $(variant_prices).each(function (index, row) {

                var variant_title=(row['get_variant_one'] ? (row['get_variant_one']['variant'] ?? '') : '') + "/"+ (row['get_variant_two'] ? (row['get_variant_two']['variant'] ?? '') : '') + "/"+(row['get_variant_three'] ? (row['get_variant_three']['variant'] ?? '') : '');
                console.log("row=",variant_title);
                tableBody += `<tr>
                            <th>
                                <input class="pv-index-${index}" data-edited='1' type="hidden" name="product_preview[${index}][variant]" value="${row.id}">
                                <span class="font-weight-bold">${variant_title}</span>
                            </th>
                            <td>
                                <input type="text" class="form-control" value="${row.price}" name="product_preview[${index}][price]" required>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="${row.stock}" name="product_preview[${index}][stock]">
                            </td>
                        </tr>`;
            });
            $("#variant-previews").empty().append(tableBody);

        }
        $("#idForm").submit(function(e) {
            e.preventDefault();
            myDropzone.processQueue();
            var form = $(this);
            var actionUrl = form.attr('action');
            $("#btnSubmit").attr("disabled",true);
            $.ajax({
                type: "POST",
                url: actionUrl,
                data: form.serialize(), // serializes the form's elements.
                success: function(data)
                {
                    $("#btnSubmit").attr("disabled",false);
                    alert("Success"); // show response from the php script.
                    window.location.reload();
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    $("#btnSubmit").attr("disabled",false);
                    alert(msg);
                },
            });

        });
        function handlePImageDelete(v,id){
            $(v).text("Deleting...");
            $(v).attr("disabled",true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('product.image.delete') }}",
                dataType: 'json',
                data: {id:id}, // serializes the form's elements.
                success: function(data)
                {
                    alert("Success"); // show response from the php script.
                    window.location.reload();
                }
            });
        }
        //console.log("varitants=",variants);
    </script>
@endpush
