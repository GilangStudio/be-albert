<!-- App css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/app-material.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
        
<link href="{{ asset('assets/libs/datetimepicker/datetimepicker.css') }}" rel="stylesheet">

<!-- Tempus Dominus Styles -->
{{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}


<style>
    .cursor-pointer {
        cursor: pointer !important;
    }
    
    .object-fit-contain {
        object-fit: contain !important;
    }

    .img-border {
        padding: .25rem;
        background-color: #fff;
        border: 1px solid #eaf0f9;
        border-radius: .25rem;
    }

    textarea {
        resize: none;
    }
</style>

@stack('styles')