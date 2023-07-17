<!-- modal them khách hang -->
<div class="modal " id="filemag" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:10000;">
    <div class="modal-dialog modal-file" role="document">
        <div class="modal-content modal-dialog-centered">
            <div class="modal-headers">
                <h4 class="modal-title" id="exampleModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <iframe src="{{url('responsive_filemanager/filemanager/dialog.php?type=1&field_id=inp-images-list')}}" frameborder="0" style="width: 100%; height: 70vh" ></iframe>
            </div>
        </div>
    </div>
</div>
