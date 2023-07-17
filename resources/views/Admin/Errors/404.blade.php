@extends('Admin.Layouts.Master')
@section('Title', 'Admin | Nhadatexpress')

@section('Content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">404 Not Found</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <section class="content">
                        <div class="error-page">
                            <h2 class="headline text-warning"> 404</h2>

                            <div class="error-content">
                                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

                                <p>
                                    We could not find the page you were looking for.
                                    Meanwhile, you may <a href="{{url('/admin')}}">return to dashboard</a> or try using the search form.
                                </p>

                            </div>
                            <!-- /.error-content -->
                        </div>
                        <!-- /.error-page -->
                    </section>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

