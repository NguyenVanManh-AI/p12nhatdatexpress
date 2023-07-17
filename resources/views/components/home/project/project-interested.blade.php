<div class="project-list section project-item-box">
    <div class="head-divide">
        <div class="left">
            <h3>Có thể bạn quan tâm</h3>
        </div>
    </div>
    <div class="project-list-wrapper pt-2">
        <div class="row">
            @foreach($projectInterested as $item)
                <x-news.project.item :item="$item" />
            @endforeach
        </div>
    </div>
</div>
