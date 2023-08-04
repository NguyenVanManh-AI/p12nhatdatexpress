@extends('Home.Layouts.Master')

@section('Title', $group->meta_title)
@section('Keywords', $group->meta_key)
@section('Description', $group->meta_desc)
@section('Image', $group->getSEOBanner())

@section('Content')
    <div class="page-property pl-2 focus-group__page">
        <x-home.focus.high-light :group="$group" />

        <section class="focus-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-7-3">
                        <div class="content-main">
                            <div class="section knowledge">
                                <div class="section-title">
                                    <h2 class="title">{{ $group->group_name }}</h2>
                                </div>

                                <x-common.list-auto-load-more
                                    :lists="$news"
                                    items-per-page="{{ $itemsPerPage }}"
                                    items-per-row="3"
                                    item-class=".focus-list__item"
                                    more-url="/focus/more-news?group_id={{ $group->id }}"
                                >
                                    <x-slot name="itemLists">
                                        @forelse($news as $item)
                                            <x-home.focus.property-item
                                                :property="$item"
                                                :group="$group"
                                            />
                                        @empty
                                            <div class="text-center col-12">
                                                <p class="p-5">Chưa có dữ liệu</p>
                                            </div>
                                        @endforelse
                                    </x-slot>
                                </x-common.list-auto-load-more>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3-7">
                        <div class="sidebar-right">
                            <div class="widget widget-search focus-group__widget-search c-mb-10">
                                <div class="widget-title">
                                    <h3 class="text-center">Tìm kiếm</h3>
                                </div>
                                <form action="" class="search-form" method="get">
                                    <input type="text" name="keyword" placeholder="Nhập từ khóa" value="{{request('keyword')}}">
                                    <button type="submit" class="btn-search"><img src="{{asset('frontend/images/sidebar/search.png')}}" alt=""></button>
                                </form>
                            </div>
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
