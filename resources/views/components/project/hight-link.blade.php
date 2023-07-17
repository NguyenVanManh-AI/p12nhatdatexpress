<div class="link section w-100 c-mb-10">
    <div class="head-center">
        <h3 >LIÊN KẾT NỔI BẬT</h3>
    </div>
    <div class="link-list">
        @foreach ($getHightLink as $item)
        <li class="pl-0">
            <a class="d-flex align-items-center" href="{{route('home.project.index', $item->group_url)}}?province_id={{$item->district->province_id}}&district_id={{$item->district->id}}">
               <div class="d-flex align-items-center w-100">
                   <i class="fas fa-circle list-icon"></i>
                   <div class="left-highlight-link">
                       <span>Dự án {{$item->group_name}} </span>
                       <span class="district_name">{{ " " . $item->district->district_name}}</span>
                   </div>
               </div>
                <div class="count">
                    ({{number_format($item->count, 0 , '.', '.')}})
                </div>
            </a>
        </li>
        @endforeach
    </div>
</div>
