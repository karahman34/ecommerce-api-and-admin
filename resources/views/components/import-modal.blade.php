<div class="modal fade import-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ $action }}" method="POST" class="need-ajax has-modal has-datatable"
          enctype="multipart/form-data" data-datatable="#dt-products">
          @csrf

          <div class="form-group mb-4">
            <label for="file">File</label>
            <input type="file" name="file" id="file" class="form-control-file">
          </div>

          <button type="submit" class="btn btn-danger w-100">
            <i class="fas fa-upload mr-1"></i>
            <span>Import</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
