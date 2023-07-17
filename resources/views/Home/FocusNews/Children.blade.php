@extends('Home.Layouts.Master')

@section('Style')
<style>
    .list-knowledge .thumbnail img{
        width: 100% !important;
        height: 150px !important;
        object-fit: cover;
    }
</style>
@endsection
@section('Content')

    <div class="page-property pl-2">

        <x-home.focus.high-light :group="$group" />

        <section class="focus-bottom">

            <div class="container">

                <div class="row">

                    <div class="col-md-7-3">
                        <div class="content-main">
                            <div class="section knowledge">
                                <div class="section-title">
                                    <h2 class="title">{{$group->group_name}}</h2>
                                </div>
                                <div class="list-knowledge">
                                    <div class="row">
                                    @forelse($list as $item)                                       
                                        <div class="col-4">
                                            <x-home.focus.property-item :property="$item" :group="$group" />
                                        </div>                                   
                                    @empty
                                    <div class="text-center col-12">
                                        <p class="p-5">Chưa có dữ liệu</p>
                                    </div>
                                    @endforelse
                                </div>
                                </div>

                                @if($list->lastPage() > 1)
                                    <x-home.layout.paginate :list="$list">
                                        @slot('auto_load_page')
                                            <div class="auto_load auto-paged">
                                                <input type="checkbox" name="autoload" id="autoload" data-start="{{$num_collection['num_cur']}}">
                                                <label for="autoload" class="m-0"><i class="fas fa-sync mr-2"></i>Tự động qua trang</label>
                                            </div>
                                        @endslot
                                    </x-home.layout.paginate>
                                @endif

                            </div>
                        </div>

                    </div>

                    <div class="col-md-3-7" style="padding-left: 1%">

                        <div class="sidebar-right">

                            <!-- search -->
                            <div class="widget widget-search c-mb-10">
                                <div class="widget-title">
                                    <h3 class="text-center">Tìm kiếm</h3>
                                </div>
                                <form action="" class="search-form" method="get">
                                    <input type="text" name="keyword" placeholder="Nhập từ khóa" value="{{request('keyword')}}">
                                    <button type="submit" class="btn-search"><img src="{{asset('frontend/images/sidebar/search.png')}}" alt=""></button>
                                </form>
                            </div>
                            <!-- //search -->
                            <x-home.event />
                            <x-home.focus.most-viewed />
                            <x-home.exchange/>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
@endsection

@section('Script')
    <script>
        $(function () {
            // Handle change input auto load
            $('#autoload').change(function () {
                $(this).parents('.auto_load').toggleClass('on');
                if(this.value){
                    no = 0;
                    let data_ajax = { _token: '{{csrf_token()}}' };
                    autoload('#autoload', '{{route('home.focus.ajax_list', $group->id)}}', data_ajax, '.list-knowledge');
                }
            })
            // Handle scroll
            $(document).scroll(function (event) {
                let data_ajax = { _token: '{{csrf_token()}}' };
                autoload('#autoload', '{{route('home.focus.ajax_list', $group->id)}}', data_ajax, '.list-knowledge');
            });
        })
    </script>
@endsection
