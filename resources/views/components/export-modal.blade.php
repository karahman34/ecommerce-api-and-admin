<div class="modal fade export-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Export</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ $action }}" method="POST">
          <div class="form-group">
            <label for="take">Take</label>
            <select name="take" id="take" class="form-control">
              <option value="10">10</option>
              <option value="25" selected>25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>

          <div class="form-group">
            <label for="format">Format</label>
            <select name="format" id="format" class="form-control">
              <option value="" selected disabled>Select Format</option>
              @foreach ($formats as $format)
                <option value="{{ $format }}">{{ strtoupper($format) }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-success w-100">
            <i class="fas fa-download mr-1"></i>
            <span>Export</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
