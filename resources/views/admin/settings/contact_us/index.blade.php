@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Contact us messages</span></h4>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('contact_us.index')}}" class="breadcrumb-item">Contact us messages</a>

                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <!-- Basic table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>

                    <tr>

                        <th class="numeric" style="width: 10%">id</th>
                        <th class="">Name</th>
                        <th class="">Email</th>
                        <th class="">Title</th>
                        <th class="">Message</th>
                        <th class="">Date</th>

                    </tr>

                    </thead>

                    <tbody>


                    @foreach($contactUs as $contactU)

                        <tr @if(session('id') === $contactU->id)class="bg-green" @endif>
                            <td>{{$loop->index + 1 }}</td>
                            <td >{{$contactU->name}}</td>
                            <td >{{$contactU->email}}</td>
                            <td >{{$contactU->title}}</td>
                            <td >{{$contactU->description}}</td>
                            <td >{{$contactU->created_at}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" class="text-center">
                            {{ $contactUs->links() }}
                        </td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
        <!-- /basic table -->




    </div>
@endsection
