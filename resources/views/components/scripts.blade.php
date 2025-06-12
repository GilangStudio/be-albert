<!-- Javascript  -->  
<!-- vendor js -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/toast.init.js') }}"></script>
<script src="{{ asset('assets/js/sortable.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{ asset('assets/js/validate-image.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
{{-- <script src="https://www.jqueryscript.net/demo/Configurable-Date-Picker-Plugin-For-Bootstrap/dist/js/bootstrap-datepicker.js" type="text/javascript"></script> --}}


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/libs/datetimepicker/datetimepicker.min.js') }}"></script>


<!-- Popperjs -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    // const popoverTriggerList = document.querySelectorAll('title')
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
</script>

<script>
    $(document).on('submit', 'form', function() {
        $(this).find('button[type="submit"]').attr('disabled', true);
        $(this).find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    });
</script>

<script>
    // #price must be number
    $('#price, #min-order').on('input', function() {
        this.value = formatRupiah(this.value, 'Rp. ');
    });

    function formatRupiah(number, prefix) {
        number = number.toString();
        var number_string = number.replace(/[^,\d]/g, '').toString(),
          split = number_string.split(','),
          left = split[0].length % 3,
          rupiah = split[0].substr(0, left),
          thousands = split[0].substr(left).match(/\d{3}/gi);

        if (thousands) {
          separator = left ? '.' : '';
          rupiah += separator + thousands.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
      }
</script>
@stack('scripts')