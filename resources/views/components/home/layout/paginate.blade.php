<div class="pagination section">
    @if(isset($auto_load_page))
    {{ $auto_load_page }}
    @endif
    {{ $list->render('Home.Layouts.Pagination') }}
</div>
