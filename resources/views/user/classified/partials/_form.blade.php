{{-- add field step by step keep position--}}
{{-- <x-common.loading class="loading-inline small project-loading ml-1"/> --}}

<div class="classified-page__project-images-box {{ $selectedProject ? '' : 'd-none' }}">
  @if($selectedProject)
    <p class="classified-page__project-images-title font-italic text-grey fs-normal">
      Gợi ý hình ảnh liên quan đến dự án
      <a href="{{ route('home.project.project-detail', $selectedProject->project_url) }}" target="_blank" class="link-flat js-selected-project-name text-break">
        {{ $selectedProject->project_name }}
      </a>
    </p>
    <div class="classified-page__project-images d-flex scrollable-vertical">
      @if(!empty($project_active->image_url))
        @foreach(json_decode($project_active->image_url) as $image)
          <div class="image-ratio-box relative w-size-120 mr-2">
            <a href="javascript:void(0);" class="js-select-project-image absolute-full">
              <img class="object-cover" src="{{ asset($image) }}" alt="">
            </a>
          </div>
        @endforeach
      @endif
    </div>
  @endif
</div>
