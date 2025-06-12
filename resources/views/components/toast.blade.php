<div class="toast-container position-fixed top-0 end-0 p-3 mt-3" style="z-index: 1000">
    {{-- <div class="toast d-flex align-items-center text-white bg-{{ $color }} border-0" role="alert" aria-live="assertive" aria-atomic="true"> --}}
    <div class="toast d-flex align-items-center border border-2 border-top-0 border-end-0 border-{{ $color }}" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body d-flex align-items-start">
            <div>
                <i class="ti ti-{{ $icon }} text-{{ $color }} font-18 me-2"></i>
            </div>
            {{ $message }}
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script>
    setTimeout(() => {
        $('.toast-container').remove();
    }, 5000);
</script>