 <!-- Include Bootstrap JS and dependencies -->
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 <!-- Toastr JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 <!-- Custom JS -->
 {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
 @include('layouts.function')
 @stack('scripts')
