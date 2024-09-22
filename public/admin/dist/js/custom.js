$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('change', function() {
        var is_active = $(this).is(':checked') ? 1 : 0;
        var model = $(this).data('model'); // Get the model name dynamically
        var id = $(this).data('id'); // Get the ID dynamically

        $.ajax({
            url: 'toggleStatus', // Ensure the correct route is being used
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Use CSRF token dynamically
                model: model,
                id: id,
                is_active: is_active
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

    // Delete Action with SweetAlert Confirmation
    $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        var model = $(this).data('model');
        var deleteUrl = '/admin/' + model + '/' + id; // Adjust the delete route dynamically

        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // Use CSRF token dynamically
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The record has been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Optionally reload or remove the row from the table
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.',
                        });
                    }
                });
            }
        });
    });

});

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




