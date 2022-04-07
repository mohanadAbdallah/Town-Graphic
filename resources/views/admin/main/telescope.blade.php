@extends('admin.layouts.app')

@section('content')



    <div class="content" style="padding: 0 !important;">
        <!-- Embed element -->

                <div class="" style="width: 100%;height: 100%">
                    <embed style="width: 100%;height: 100%" class="embed-responsive-item" src="{{route('telescope')}}">
                </div>

        <!-- /embed element -->

    </div>
@endsection
