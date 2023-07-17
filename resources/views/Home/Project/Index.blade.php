@extends('Home.Layouts.Master')
@section('Title', $group->meta_title ?? 'Dự án | Nhà đất express')
@section('Description', $group->meta_desc)
@section('Image',asset($group->image_banner??'frontend/images/home/image_default_nhadat.jpg'))

@section('Content')
    <div id="page-project" class="list-search-category-box js-parents-loadmore pt-2">
        <div class="overlay2"></div>
        <div class="row">
            <x-project.banner-mobile />
            <div class="col-md-12 search-banner list-classified-search-box d-inline-block">
                <div class="row">
                    <div class="search-tool search-tool-width col-md-3-7 mb-3">
                        <x-home.classified.search.category-form :group="$group_parent_id ?? null" />
                    </div>
                    <div class="banner-slide-width col-md-7-3 mb-3 md-hide">
                        <div class="search-image" style="background-image: url({{ asset($group->image_banner??'/frontend/images/banner-duan.jpg') }})"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="category-search-results-section">
            <x-news.project.search-results
                :group="$group"
                :projects="$projects"
            />
        </div>
    </div>
@endsection
