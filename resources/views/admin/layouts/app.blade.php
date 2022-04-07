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
    <script src="{{asset('portal/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_pages/components_dropdowns.js')}}"></script>

	<script src="{{asset('portal/assets/js/app.js')}}"></script>

	<script src="{{asset('portal/global_assets/js/demo_pages/form_layouts.js')}}"></script>
	<!-- /theme JS files -->




@yield('js_css_header')
	<!-- /theme JS files -->
    <style>
        .navbar-dark{
            background: rgb(176,215,57);
            background: linear-gradient(90deg, rgba(176,215,57,1) 0%, rgba(155,210,71,1) 24%, rgba(132,205,86,1) 49%, rgba(85,196,114,1) 73%, rgba(3,178,166,1) 100%);
        }
        .sidebar-dark{
            background-color: #ffffff !important;
            color: #969696 !important;
        }
        .sidebar-dark .nav-sidebar .nav-link:not(.disabled):hover, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link:not(.disabled):hover {
            color: #31AD4C !important;
            background-color: rgba(255,255,255,.1)  !important;
        }
        .sidebar-dark .nav-sidebar .nav-link, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link {
            color: #969696 !important;
            font-size: 17px;
            font-weight: 400;
            padding: 5px 20px;
        }
        .nav-sidebar .nav-link i{
            margin-right: 10px;
            margin-top: 4px;
        }
        .sidebar-dark .nav-sidebar .nav-item-header, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-header {
            color: #707070 !important;
            padding-bottom: 0;
            padding-top: 0;
        }
        .nav-item-header .font-size-xs{
            font-size: 20px !important;
        }
        .sidebar-dark .nav-sidebar>.nav-item>.nav-link.active{
            background-color: #e4e4e4 !important;
        }
        .dropdown-item {

            color: #77797a !important;

        }
        .navbar{
            padding: 0px !important;
            border-bottom: none !important;
        }
        .navbar-brand{
            min-width: 17rem !important;
            text-align: center !important;
            background: white !important;
        }
        .navbar-expand-md .navbar-brand {
            min-width: 16.9rem !important;
            border-bottom: 0;
        }

        .sidebar:not(.sidebar-component) .sidebar-content {
            position: inherit !important;
            top: auto !important;
            bottom: auto !important;
            width: inherit !important;
            overflow-y: hidden !important;
        }
        .sidebar-expand-md:not(.sidebar-component) {
            border-right: 1px solid #dddddd;
        }
        .bg-blue-400 {
            background-color: #31AD4C;
        }
        .bg-success-400 {
            background-color: #88D645;
        }
        .bg-danger-400 {
            background-color: #FFB045;
        }
        .card{
            border-radius: 15px;
        }
        .btn{
            border-radius: 15px;
        }
        .btn-primary{
            background-color: #88D645;
        }
        .btn-primary:hover{
            background-color: #70b931;
        }
        .btn-info{
            background-color: #31AD4C;
        }
        .btn-info:hover {
            background-color: #6db530;
        }
        .btn-success {
            background-color: #40bf80;
        }
        .btn-success:hover {
            background-color: #1e8a35;
        }
        .table{
            color: #707070;
        }
        .sidebar-dark .nav-sidebar>.nav-item>.nav-link.active{
            background: none !important;
            color: #31AD4C !important;
        }
        .form-control{
            border-radius: 15px;
        }
        .sidebar-user{
            border-bottom: 1px solid #f5f5f5;
        }
        .media-title{
            font-size: 17px;
            color: #3DB34C;
        }
    </style>
{{--	<style>--}}
{{--		.navbar-dark{--}}
{{--			background: rgb(176,215,57);--}}
{{--			background: linear-gradient(90deg, rgba(214, 38, 62, 0.82) 0%, rgba(214, 38, 62, 0.82) 24%, rgba(214, 38, 62, 0.79) 49%, rgb(214, 38, 62) 73%,--}}
{{--            rgb(214, 38, 62) 100%);--}}
{{--		}--}}
{{--		.sidebar-dark{--}}
{{--			background-color: #ffffff !important;--}}
{{--			color: #969696 !important;--}}
{{--		}--}}
{{--		.sidebar-dark .nav-sidebar .nav-link:not(.disabled):hover, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link:not(.disabled):hover {--}}
{{--			color: #d6263e !important;--}}
{{--			background-color: rgba(255,255,255,.1)  !important;--}}
{{--		}--}}
{{--		.sidebar-dark .nav-sidebar .nav-link, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-link {--}}
{{--			color: #969696 !important;--}}
{{--			font-size: 17px;--}}
{{--			font-weight: 400;--}}
{{--			padding: 5px 20px;--}}
{{--		}--}}
{{--		.nav-sidebar .nav-link i{--}}
{{--			margin-right: 10px;--}}
{{--    		margin-top: 4px;--}}
{{--		}--}}
{{--		.sidebar-dark .nav-sidebar .nav-item-header, .sidebar-light .card[class*=bg-]:not(.bg-light):not(.bg-white):not(.bg-transparent) .nav-sidebar .nav-item-header {--}}
{{--			color: #707070 !important;--}}
{{--			padding-bottom: 0;--}}
{{--			padding-top: 0;--}}
{{--		}--}}
{{--		.nav-item-header .font-size-xs{--}}
{{--			font-size: 20px !important;--}}
{{--		}--}}
{{--		.sidebar-dark .nav-sidebar>.nav-item>.nav-link.active{--}}
{{--			background-color: #e4e4e4 !important;--}}
{{--		}--}}
{{--		.dropdown-item {--}}

{{--			color: #77797a !important;--}}

{{--		}--}}
{{--		.navbar{--}}
{{--			padding: 0px !important;--}}
{{--			border-bottom: none !important;--}}
{{--		}--}}
{{--		.navbar-brand{--}}
{{--			min-width: 17rem !important;--}}
{{--			text-align: center !important;--}}
{{--			background: white !important;--}}
{{--		}--}}
{{--			.navbar-expand-md .navbar-brand {--}}
{{--				min-width: 16.9rem !important;--}}
{{--				border-bottom: 0;--}}
{{--			}--}}

{{--		.sidebar:not(.sidebar-component) .sidebar-content {--}}
{{--			position: inherit !important;--}}
{{--			top: auto !important;--}}
{{--			bottom: auto !important;--}}
{{--			width: inherit !important;--}}
{{--			overflow-y: hidden !important;--}}
{{--		}--}}
{{--		.sidebar-expand-md:not(.sidebar-component) {--}}
{{--			border-right: 1px solid #dddddd;--}}
{{--		}--}}
{{--		.bg-blue-400 {--}}
{{--		    background-color: #d6263e;--}}
{{--		}--}}
{{--		.bg-success-400 {--}}
{{--		    background-color: #ea7788;--}}
{{--		}--}}
{{--		.bg-danger-400 {--}}
{{--		    background-color: #FFB045;--}}
{{--		}--}}
{{--		.card{--}}
{{--			border-radius: 15px;--}}
{{--		}--}}
{{--		.btn{--}}
{{--			border-radius: 15px;--}}
{{--            padding: 3px 7px;--}}
{{--		}--}}
{{--		.btn-primary{--}}
{{--		    background-color: #88D645;--}}
{{--		}--}}
{{--		.btn-primary:hover{--}}
{{--		    background-color: #70b931;--}}
{{--		}--}}
{{--		.btn-info{--}}
{{--		    background-color: #31AD4C;--}}
{{--		}--}}
{{--		.btn-info:hover {--}}
{{--		    background-color: #6db530;--}}
{{--		}--}}
{{--		.btn-success {--}}
{{--		    background-color: #40bf80;--}}
{{--		}--}}
{{--		.btn-success:hover {--}}
{{--		    background-color: #1e8a35;--}}
{{--		}--}}
{{--		.table{--}}
{{--			color: #707070;--}}
{{--		}--}}
{{--		.sidebar-dark .nav-sidebar>.nav-item>.nav-link.active{--}}
{{--			background: none !important;--}}
{{--			color: #d6263e !important;--}}
{{--		}--}}
{{--		.form-control{--}}
{{--			border-radius: 15px;--}}
{{--		}--}}
{{--		.sidebar-user{--}}
{{--			border-bottom: 1px solid #f5f5f5;--}}
{{--		}--}}
{{--		.media-title{--}}
{{--			font-size: 17px;--}}
{{--		    color: #d6263e;--}}
{{--		}--}}
{{--	</style>--}}
</head>

<body class="navbar-top">

	<!-- Main navbar -->
	@include('admin.layouts.navbar')
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		@include('admin.layouts.sidebar')
		<!-- /main sidebar -->

		<!-- Main content -->
		<div class="content-wrapper" style="    overflow: hidden;">

			<!-- Content area -->
		@yield('content')
			<!-- /content area -->

			<!-- Footer -->
		@include('admin.layouts.footer')
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	@yield('js_assets')
	@yield('js_code')
</body>
</html>
