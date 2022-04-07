@extends('admin.layouts.app')

@section('content')
    <style>
        .form-control:disabled {
            color: #00a905 !important;
        }
    </style>
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
                    <a href="{{route('orders.index')}}" class="breadcrumb-item">Orders</a>
                    <span class="breadcrumb-item active">Show</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Order Details</h5>

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


                <form class="rated" action="{{route('advertisements.update',$order->id)}}" method="post"
                      enctype="multipart/form-data">
                    @method('put')

                    {{csrf_field()}}
                    <div class="row">

                        <div class="col-6 ">

                            <div class="form-group">
                                <label class="control-label" for="name">Order No.</label>
                                <input type="text" class="form-control" disabled  value="#{{$order->order_number}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">User name</label>
                                <input type="text" class="form-control" disabled  value="{{$order->appUser->name}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">User Mobile</label>
                                <input type="text" class="form-control" disabled value="{{$order->appUser->mobile}}">
                            </div>


                            <div class="form-group">
                                <label class="control-label" for="name">Agent name</label>
                                <input type="text" class="form-control" disabled value="{{$order->agent->name}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Delivery date</label>
                                <input type="text" class="form-control" disabled value="{{$order->order_delivery_date}}">
                            </div>




                        </div>
                        <div class="col-6 ">

                            <div class="form-group">
                                <label class="control-label" for="name">Status</label>
                                <input type="text" class="form-control" disabled value="#{{$order->status_name}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Total amount</label>
                                <input type="text" class="form-control" disabled value="{{$order->total_amount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Delivery amount</label>
                                <input type="text" class="form-control" disabled value="{{$order->delivery_amount}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Total amount with fees</label>
                                <input type="text" class="form-control" disabled value="{{$order->total_amount_with_fees}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="name">Transaction id</label>
                                @if($order->type == 1 or $order->type == 3)
                                    <input type="text" class="form-control" disabled value="#{{$order->transaction_id}}">
                                @else
                                    <input type="text" class="form-control" disabled value="Cash pay">
                                @endif
                            </div>




                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="control-label font-weight-bold" for="end_date">Delivery Location</label>
                            <span class="google-map" style="width:100%; height:350px;">
                                                <iframe allowfullscreen="" frameborder="0"
                                                        src="https://maps.google.com/maps?q={{$order->cart->delivery_location ?? ''}}&hl=es&z=14&amp;output=embed"

                                                        style="border:0;width:100%; height:350px;"></iframe>
                                            </span>
                        </div>
                    </div>
                    @foreach($order->items as $orderItem)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label" for="">
                                <span class="font-weight-bold">Product name:</span> <a href="{{route('products.show',$orderItem->cartItem->product->id)}}">{{$orderItem->cartItem->product->title_en}}</a>
                            </label>
                            <br>
                            <label class="control-label" for="">
                                <span class="font-weight-bold">Price:</span> {{$orderItem->price}}
                            </label>
                            <br>
                            <label class="control-label" for="">
                                <span class="font-weight-bold">Amount:</span> {{$orderItem->amount}}
                            </label>
                            <br>
                            <label class="control-label" for="">
                                <span class="font-weight-bold">Size:</span> {{$orderItem->cartItem->size}}
                            </label>
                            <br>
                            <label class="control-label" for="">
                                <span class="font-weight-bold">Selected Photos by user:</span>
                            </label>
                            <input type="file" name="images[]" multiple="multiple" class="file-input-{{$orderItem->id}}" data-show-caption="false" data-show-upload="false" data-fouc>
                        </div>
                    </div>
                    @endforeach



                </form>
            </div>
        </div>


    </div>
@endsection
@section('js_assets')
    <script src="{{asset('portal/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&language=ar"></script>
@endsection
@section('js_code')
    <script>

        $(document).ready(function () {

            $(".btn-file").remove();
        });

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
            zoomIcon: '<i class="icon-zoomin3"></i>',
            dragClass: 'p-2',
            dragIcon: '<i class="icon-three-bars"></i>',
            removeClass: '',
            removeErrorClass: 'text-danger',
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

            initialPreviewAsData: true,
            initialCaption: "No file selected",
            allowedFileExtensions: ["jpg", "gif", "png"],
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        };
        @foreach($order->items as $orderItem)
         @isset($orderItem->cartItem->images_urls)
            options['initialPreview'] = [
            @foreach($orderItem->cartItem->images_urls as $image)
                '{{$image}}',
            @endforeach
        ];
        options['initialPreviewConfig'] = [
                @foreach($orderItem->cartItem->images_urls as $image)
            {
                caption: 'Image',
                key: '{{$image}}',
                method: 'get',
                showDrag: false
            },
            @endforeach
        ];
        @endisset
        $('.file-input-{{$orderItem->id}}').fileinput(options);
       @endforeach
    </script>
@endsection
