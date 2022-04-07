<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Env Friends') }}</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/global_assets/css/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('portal/assets/css/custom.css')}}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.13/css/tableexport.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{asset('portal/global_assets/js/main/jquery.min.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->


    <!-- Theme JS files -->
    <script src="{{asset('portal/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>


    <script src="{{asset('portal/assets/js/app.js')}}"></script>

    <script src="{{asset('portal/global_assets/js/demo_pages/form_layouts.js')}}"></script>
    <!-- /theme JS files -->
</head>
<body>
<div class="container">
    <div class="row"></div>
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="alert alert-success text-center font-weight-bold">
                تمت عملية الدفع بنجاح
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

</body>
</html>
