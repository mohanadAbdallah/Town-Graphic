@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Edit User {{$user->name}}</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item">Users</a>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
                    <span class="breadcrumb-item active">Edit</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">User Details</h5>

            </div>

            <div class="card-body">

                <form class="rated" action="{{route('users.update',$user->id)}}" method="post"
                      enctype="multipart/form-data">
                    @method('put')

                    {{csrf_field()}}
                    <div class="row">

                        <div class="col-5 ">

                            <div class="form-group">
                                <label class="control-label" for="name">Name (AR)</label>
                                <input type="text" class="form-control" id="name_ar" name="name_ar" value="{{$user->name_ar ?? $user->name}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Name (EN)</label>
                                <input type="text" class="form-control" id="name_en" name="name_en" value="{{$user->name_en ?? $user->name}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                       value="{{$user->email}}">
                            </div>



                        </div>


                        <div class="col-5 offset-md-1">
                            <div class="form-group">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="mobile">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                       value="{{$user->mobile}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Role</label>


                                <select required class="form-control" type="text"
                                        name="user_group" id="permissions">

                                    @foreach($roles as $users_group)
                                        <option value="{{$users_group->id}}"
                                                @if(isset($user, $user->getRoleNames()[0]) && $users_group->name === $user->getRoleNames()[0])  selected @endif>{{$users_group->name}}</option>
                                    @endforeach
                                </select>
                                <small class="form-control-feedback"></small>
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
