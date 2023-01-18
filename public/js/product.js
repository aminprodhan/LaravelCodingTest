var currentIndex = 0;

var indexs = [];

$(document).ready(function () {
    if(variant_prices == undefined)
        addVariantTemplate();
    // $("#file-upload").dropzone({
    //     url: "{{ route('file-upload') }}",
    //     method: "post",
    //     addRemoveLinks: true,
    //     success: function (file, response) {
    //         //
    //     },
    //     error: function (file, response) {
    //         //
    //     }
    // });
});

function addVariant(event) {
    event.preventDefault();
    addVariantTemplate();
}

function getCombination(arr, pre) {

    pre = pre || '';

    if (!arr.length) {
        return pre;
    }

    return arr[0].reduce(function (ans, value) {
        return ans.concat(getCombination(arr.slice(1), pre + value + '/'));
    }, []);
}

function updateVariantPreview() {

    var valueArray = [];
    $(".select2-value").each(function () {
        valueArray.push($(this).val());
    });
    var variantPreviewArray = getCombination(valueArray);
    var tableBody = '';

    $(variantPreviewArray).each(function (index, element) {
        // var isExist=$(".pv-index-"+index).val();
        // console.log('row=',isExist);
        // if(isExist != undefined)
        //     return;
        tableBody += `<tr>
                        <th>
                                        <input type="hidden" name="product_preview[${index}][variant]">
                                        <span class="font-weight-bold">${element}</span>
                                    </th>
                        <td>
                                        <input type="text" class="form-control" value="0" name="product_preview[${index}][price]" required>
                                    </td>
                        <td>
                                        <input type="text" class="form-control" value="0" name="product_preview[${index}][stock]">
                                    </td>
                      </tr>`;
    });

    $("#variant-previews").empty().append(tableBody);
}
var flag=0;
function addVariantTemplate(key_value,values,variant_prices) {

    $("#variant-sections").append(`<div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Option</label>
                                        <select id="select2-option-${currentIndex}" data-index="${currentIndex}" name="product_variant[${currentIndex}][option]" class="form-control custom-select select2 select2-option">
                                            <option value="1">
                                                Color
                                            </option>
                                            <option value="2">
                                                Size
                                            </option>
                                            <option value="6">
                                                Style
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="d-flex justify-content-between">
                                            <span>Value</span>
                                            <a href="#" class="remove-btn" data-index="${currentIndex}" onclick="removeVariant(event, this);">Remove</a>
                                        </label>
                                        <select id="select2-value-${currentIndex}" data-index="${currentIndex}" name="product_variant[${currentIndex}][value][]" class="select2 select2-value form-control custom-select" multiple="multiple">
                                        </select>
                                    </div>
                                </div>
                            </div>`);

        $(`#select2-option-${currentIndex}`).select2(
            {
                placeholder: "Select Option", theme: "bootstrap4",
            });

        $(`#select2-option-${currentIndex}`).select2("trigger", "select", {
            data: {
                id:key_value,
            }
        });
    //console.log("vlaue=",values);
    var values_edit=[];var values_edit_ids=[];
    if(key_value != undefined){
        values.map(row => {
            values_edit.push({id:row.variant,text:row.variant});
            values_edit_ids.push(row.variant);
        })
    }
    //console.log("valueeee=",valu);
    $(`#select2-value-${currentIndex}`).select2({
            tags: true,
            multiple: true,
            placeholder: "Type tag name",
            allowClear: true,
            theme: "bootstrap4",
            data: values_edit,
        })
        .on('change', function (val) {
            console.log("change val=",flag);
            if(key_value == undefined || flag == 0){
                updateVariantPreview();
            }
            else
                flag=0;

        });

    if(key_value != undefined){
        flag=1;
        $(`#select2-value-${currentIndex}`).val(values_edit_ids).change();
    }

    indexs.push(currentIndex);

    currentIndex = (currentIndex + 1);

    if (indexs.length >= 3) {
        $("#add-btn").hide();
    } else {
        $("#add-btn").show();
    }
}

function removeVariant(event, element) {

    event.preventDefault();

    var jqElement = $(element);

    var position = indexs.indexOf(jqElement.data('index'))

    indexs.splice(position, 1)

    jqElement.parent().parent().parent().parent().remove();

    if (indexs.length >= 3) {
        $("#add-btn").hide();
    } else {
        $("#add-btn").show();
    }

    updateVariantPreview();
}

