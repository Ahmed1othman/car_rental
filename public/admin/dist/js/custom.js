// $(document).ready(function() {
//
//     document.addEventListener("DOMContentLoaded", function() {
//         // Function to initialize the editor for the active tab
//         function initializeEditors() {
//             document.querySelectorAll('.teny-editor').forEach((editor) => {
//                 if (!editor.classList.contains('tox-tinymce')) {  // Check if TinyMCE is not already initialized
//                     ClassicEditor.create(editor, {
//                         toolbar: [
//                             'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
//                             'undo', 'redo'
//                         ],
//                         language: editor.getAttribute('data-language') // Set language dynamically if needed
//                     }).catch(error => {
//                         console.error(error);
//                     });
//                 }
//             });
//         }
//
//         // Event listener for tab clicks
//         const languageTabs = document.querySelectorAll('[data-toggle="pill"]');
//         languageTabs.forEach(tab => {
//             tab.addEventListener('shown.bs.tab', function() {
//                 initializeEditors(); // Initialize editor when a new language tab is shown
//             });
//         });
//
//         // Initial setup for the first active tab
//         initializeEditors();
//     });
//
//         ClassicEditor
//         .create(document.querySelector('.teny-editor'), {
//         toolbar: [
//         'heading', '|',
//         'bold', 'italic', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
//         'bulletedList', 'numberedList', '|',
//         'link', 'imageUpload', 'insertTable', '|',
//         'alignment:left', 'alignment:center', 'alignment:right', 'alignment:justify', '|',
//         'undo', 'redo'
//         ],
//         heading: {
//         options: [
//     { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
//     { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
//     { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
//     { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
//         ]
//     },
//         fontSize: {
//         options: [
//         9, 11, 13, 'default', 17, 19, 21
//         ]
//     },
//         alignment: {
//         options: [ 'left', 'center', 'right', 'justify' ]
//     },
//         image: {
//         toolbar: [
//         'imageTextAlternative', '|',
//         'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
//         ]
//     },
//         table: {
//         contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
//     },
//         placeholder: 'Start typing here...',
//         language: 'en'
//     })
//         .catch(error => {
//         console.error(error);
//     });
//
//
//
//     // tinymce.init({
//     //     selector: '.teny-editor',
//     //     height: 400,           // You can adjust the height
//     //     plugins: 'lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount',
//     //     toolbar: 'undo redo | formatselect | fontsize | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
//     //     content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }"
//     // });
//
//     // Toggle Status
    $('.toggle-status').on('change', function() {
        var value = $(this).is(':checked') ? 1 : 0;
        var model = $(this).data('model'); // Get the model name dynamically
        var attribute = $(this).data('attribute'); // Get the model name dynamically
        var id = $(this).data('id'); // Get the ID dynamically

        $.ajax({
            url: 'toggleStatus', // Ensure the correct route is being used
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Use CSRF token dynamically
                model: model,
                id: id,
                value: value,
                attribute: attribute
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to update status!',
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again later.',
                });
            }
        });
    });
//
//     // Delete Action with SweetAlert Confirmation
//     $('.delete-btn').on('click', function() {
//         var id = $(this).data('id');
//         var model = $(this).data('model');
//         var deleteUrl = '/admin/' + model + '/' + id; // Adjust the delete route dynamically
//
//         Swal.fire({
//             title: 'Are you sure?',
//             text: 'You won\'t be able to revert this!',
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes, delete it!',
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 $.ajax({
//                     url: deleteUrl,
//                     method: 'POST',
//                     data: {
//                         _token: $('meta[name="csrf-token"]').attr('content'), // Use CSRF token dynamically
//                         _method: 'DELETE'
//                     },
//                     success: function(response) {
//                         Swal.fire({
//                             icon: 'success',
//                             title: 'Deleted!',
//                             text: 'The record has been deleted.',
//                             timer: 2000,
//                             showConfirmButton: false
//                         });
//
//                         // Optionally reload or remove the row from the table
//                         location.reload();
//                     },
//                     error: function(xhr) {
//                         Swal.fire({
//                             icon: 'error',
//                             title: 'Error',
//                             text: 'Something went wrong. Please try again later.',
//                         });
//                     }
//                 });
//             }
//         });
//     });
//
// });

document.addEventListener('DOMContentLoaded', function () {
    // Function to preview image on input change
    document.querySelectorAll('.image-upload').forEach(inputElement => {
        inputElement.addEventListener('change', function (event) {
            const previewId = this.getAttribute('data-preview'); // Get the associated preview image ID
            const imagePreview = document.getElementById(previewId);
            const file = event.target.files[0];

            if (file) {
                // Display the selected image in the preview
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.style.display = 'block';

                // Dynamically update the label to show the selected file name
                const label = document.querySelector(`label[for="${this.id}"]`);
                label.textContent = file.name;
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const loader = document.getElementById('global-loader');

    // Show loader on form submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            loader.style.display = 'flex';
        });
    });

    // Show loader on button click (if it's not inside a form)
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            if (!form) loader.style.display = 'flex';
        });
    });

    // Hide loader after AJAX requests (if needed for non-form actions)
    if (window.fetch) {
        const originalFetch = window.fetch;
        window.fetch = function (...args) {
            loader.style.display = 'flex';
            return originalFetch(...args)
                .finally(() => loader.style.display = 'none');
        };
    }
});


//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})





