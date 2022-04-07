@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Show </span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('products.index')}}" class="breadcrumb-item">Product</a>
                    <span class="breadcrumb-item active">Show</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Product Details</h5>

            </div>

            <div class="card-body">


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form class="rated" action="{{route('products.update',$product->id)}}" method="post"
                      enctype="multipart/form-data">
                    @method('put')

                    {{csrf_field()}}
                    <div class="row">

                        <div class="col-6 ">

                            <div class="form-group">
                                <label class="control-label" for="name">Title (AR)</label>
                                <input type="text" class="form-control" disabled id="title_ar" name="title_ar" value="{{$product->title_ar}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Title (EN)</label>
                                <input type="text" class="form-control" disabled  id="title_en" name="title_en" value="{{$product->title_en}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Description (AR)</label>
                                <textarea rows="3" class="form-control"  disabled name="description_ar">{{$product->description_ar}}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Description (EN)</label>
                                <textarea rows="3" class="form-control" disabled  name="description_en">{{$product->description_en}}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Material type (AR)</label>
                                <input type="text" class="form-control"  disabled  id="material_type_ar" name="material_type_ar" value="{{$product->material_type_ar}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Material type (EN)</label>
                                <input type="text" class="form-control"   disabled id="material_type_en" name="material_type_en" value="{{$product->material_type_en}}">
                            </div>


                        </div>
                        <div class="col-6 ">
                            <div class="form-group">
                                <label class="control-label" for="name">Category</label>
                                <input type="text" class="form-control" disabled  id="title_en" name="title_en" value="{{$product->category->title_ar ?? ''}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Sub Category</label>
                                <input type="text" class="form-control" disabled  id="title_en" name="title_en" value="{{$product->subcategory->title_ar ?? ''}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Size</label>
                                <input type="text" class="form-control" disabled id="size" name="size" value="{{$product->size}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Price</label>
                                <input type="text" class="form-control" disabled  id="price" name="price" value="{{$product->price}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Currency</label>
                                <input type="text" class="form-control" disabled  id="currency" name="currency" value="{{$product->currency}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Count images (AR)</label>
                                <input type="text" class="form-control"disabled  id="images_count_ar" name="images_count_ar" value="{{$product->images_count_ar}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Count images (EN)</label>
                                <input type="text" class="form-control"disabled  id="images_count_en" name="images_count_en" value="{{$product->images_count_en}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Amount</label>
                                <input type="number" class="form-control" disabled id="amount" name="amount" value="{{$product->amount}}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="file" name="images[]" class="file-input" data-show-caption="false" data-show-upload="false" multiple data-fouc>
                        </div>
                    </div>



                </form>
            </div>
        </div>


    </div>
@endsection
@section('js_assets')
    <script src="{{asset('portal/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
@endsection
@section('js_code')
    <script>

        $(document).ready(function () {

            $(".btn-file").remove();
            $(".remove-image-btn").remove();
        });

    </script>
    <script>
        $(".category_id").on("change", function (e) {
            var category_id = $(this).val();
            $('#sub_category_id').html('<option value="">Firstly, Choose Category</option>');
            $.ajax({
                type: 'get',
                dataType: "json",
                url: '{{url('admin/categories/subcategories')}}/' + category_id,
                data: {'sub_category_id': '{{$product->sub_category_id ?? ''}}'},
                cache: "false",
                success: function (data) {
                    $('#sub_category_id').html(data.options);
                }, error: function (data) {
                    if (category_id === '') {
                        $('#sub_category_id').html('<option value="">Firstly, Choose Category</option>');
                    } else {
                        $('#sub_category_id').html('<option value="">There is no sub categories</option>');
                    }
                }
            });
            return false;
        });
        $(".category_id").change();

    </script>
    <script>
        var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
            '  <div class="modal-content">\n' +
            '    <div class="modal-header align-items-center">\n' +
            '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
            '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
            '    </div>\n' +
            '    <div class="modal-body">\n' +
            '      <div class="floating-buttons btn-group"></div>\n' +
            '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
            '    </div>\n' +
            '  </div>\n' +
            '</div>\n';

        // Buttons inside zoom modal
        var previewZoomButtonClasses = {
            toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
            fullscreen: 'btn btn-light btn-icon btn-sm',
            borderless: 'btn btn-light btn-icon btn-sm',
            close: 'btn btn-light btn-icon btn-sm'
        };

        // Icons inside zoom modal classes
        var previewZoomButtonIcons = {
            prev: '<i class="icon-arrow-left32"></i>',
            next: '<i class="icon-arrow-right32"></i>',
            toggleheader: '<i class="icon-menu-open"></i>',
            fullscreen: '<i class="icon-screen-full"></i>',
            borderless: '<i class="icon-alignment-unalign"></i>',
            close: '<i class="icon-cross2 font-size-base"></i>'
        };

        // File actions
        var fileActionSettings = {
            zoomClass: '',
            showRemove: true,
            zoomIcon: '<i class="icon-zoomin3"></i>',
            dragClass: 'p-2',
            dragIcon: '<i class="icon-three-bars"></i>',
            removeClass: 'remove-image-btn',
            removeErrorClass: 'text-danger',
            removeIcon: '<i class="icon-bin"></i>',
            indicatorNew: '<i class="icon-file-plus text-success"></i>',
            indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
            indicatorError: '<i class="icon-cross2 text-danger"></i>',
            indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
        };
        var options = {
            browseLabel: 'Browse',
            browseIcon: '<i class="icon-file-plus mr-2"></i>',
            uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
            removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
            layoutTemplates: {
                icon: '<i class="icon-file-check"></i>',
                modal: modalTemplate
            },
            deleteUrl : "{{url('admin/city/districts')}}",
            overwriteInitial: false,

            initialPreviewAsData: true,
            initialCaption: "No file selected",
            allowedFileExtensions: ["jpg", "gif", "png"],
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        };
        @isset($product->images)
            options['initialPreview'] = [
                @foreach($product->images as $image)
                '{{FileStorage::getUrl($image)}}',
            @endforeach
            ];
            options['initialPreviewConfig'] =  [
@foreach($product->images as $image)
                {caption: 'Image',  key: '{{$image}}',method:'get', url: '{{url('admin/products/image/delete').'/'.$product->id}}', showDrag: false},
            @endforeach
            ];
@endisset
        $('.file-input').fileinput(options);


    </script>
@endsection
