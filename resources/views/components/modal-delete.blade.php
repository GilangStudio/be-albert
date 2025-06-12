<div class="modal fade" data-bs-backdrop="static" id="modal-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form class="modal-content" action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title">Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {!! $message !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light mb-0" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger mb-0">Delete</button>
        </div>
      </form>
    </div>
</div>