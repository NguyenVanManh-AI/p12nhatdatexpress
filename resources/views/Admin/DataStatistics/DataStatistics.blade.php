@extends('Admin.Layouts.Master')
@section('Title', 'Thống kê dữ liệu')
@section('Style')
    <link rel="stylesheet" href="{{asset('system/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/plugins/slickjs/slick.css')}}">
    <link rel="stylesheet" href="{{asset('system/css/admin-data-statistics.css')}}">
    <style>
        .member-rank .member-item .avatar .image > img{
            border-radius: 50%;
        }
        .member-info .info{
            margin-top: 5px;
        }
    </style>
@endsection
@section('Content')
    <section class="content">
        <div class="container-fluid">

            <x-admin.statistics.access />

            <x-admin.statistics.member />

            <x-admin.statistics.revenue />

            <x-admin.statistics.classified />

            <x-admin.statistics.customer />

            <x-admin.statistics.member-rank />

        </div>
    </section>
@endsection
@section('Script')
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
@endsection
