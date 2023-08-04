<!-- Sidebar Menu -->
<nav class="mt-2"> 
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach($page_sidebar as $item)
            <li class="nav-item">
                <a href="{{ url($item['page_url'] ?? '' ) }}" class="nav-link">
                    <i class="nav-icon {{$item['page_icon']}}"></i>
                    <p>{{$item['page_name']}}
{{--                        - {{$item['id']}}--}}
                        @if(isset($notify[$item['id']]) && $notify[$item['id']] > 0)
                            <span class="badge badge-danger right">{{ $notify[$item['id']] < 100 ? $notify[$item['id']] : '99+' }}</span>
                        @elseif($item['children_count'] > 0)
                            <i class="right fas fa-angle-left"></i>
                        @endif
                    </p>
                </a>
                @if($item['children_count'] > 0)
                    <ul class="nav nav-treeview">
                        @foreach($item['children'] as $child)
                        <li class="nav-item">
                            <a href="{{ url($child['page_url'] ?? '#')  }}" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>{{$child['page_name']}}
{{--                                    - {{$child['id']}}--}}
                                    @if(isset($notify[$child['id']]) && $notify[$child['id']] > 0)
                                        <span class="badge badge-danger right">{{ $notify[$child['id']] < 100 ? $notify[$child['id']] : '99+' }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
<!-- /.sidebar-menu -->
