@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">About us </span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">About us</h5>

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


                <form class="rated" action="{{route('about_us.update',$aboutUs->id ?? 0)}}" method="post"
                      enctype="multipart/form-data">
                    @method('put')

                    {{csrf_field()}}
                    <div class="row ">


                        <div class="col-6 ">

                            <div class="form-group">
                                <label class="control-label" for="content_ar">Content (AR)</label>
                                <textarea rows="15" class="form-control" name="content_ar">{{$aboutUs->content_ar ?? ''}}</textarea>
                            </div>



                        </div>

                        <div class="col-6 ">

                            <div class="form-group">
                                <label class="control-label" for="content_en">Content (EN)</label>
                                <textarea rows="15" class="form-control" name="content_en">{{$aboutUs->content_en ?? ''}}</textarea>
                            </div>



                        </div>

                    </div>



                    <div class="form-group">

                        <button class="btn btn-success">Save</button>

                    </div>

                </form>
            </div>
        </div>


    </div>
@endsection
