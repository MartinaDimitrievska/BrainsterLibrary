document.addEventListener('DOMContentLoaded', function () {
    let editNoteId;
    let isEditing = false;

    function loadPrivateNotes() {
        let bookId = $('#private-notes-container').data('book-id');

        $.ajax({
            url: './book-info.php',
            method: 'GET',
            data: { book_id: bookId },
            dataType: 'json',
            success: function (response) {
                $('#private-notes-container').empty();

                response.forEach(function (note) {
                    $('#private-notes-container').append(
                        '<div class="card my-3 border-3 border-violet-500">' +
                        '<div class="card-body">' +
                        '<span class="private-note-content" data-note-id="' + note.id + '">' + note.private_note + '</span>' +
                        '<button class="ml-2 btn btn-sm btn-warning edit-private-note" data-note-id="' + note.id + '">Edit</button>' +
                        '<button class="ml-2 btn btn-sm btn-danger delete-private-note" data-note-id="' + note.id + '">Delete</button>' +
                        '</div>' +
                        '</div>'
                    );
                });
            }
        });
    }

    $('#private-notes-container').on('click', '.edit-private-note', function () {
        editNoteId = $(this).data('note-id');
        const existingNote = $(this).closest('.card-body').find('.private-note-content').text();

        isEditing = true;
        $('#add-private-note-form textarea[name="private_note"]').val(existingNote);
        $('#add-private-note-form #edit_private_note_id').val(editNoteId);
    });

    $('#add-private-note-form').on('submit', function (e) {
        e.preventDefault();

        let bookId = $('#book-id').val();
        let privateNote = $(this).find('textarea[name="private_note"]').val();

        if (privateNote.trim() === "") {
            alert("Please enter a private note.");
            return;
        }

        if (isEditing) {
            let existingNoteContainer = $('.private-note-content[data-note-id="' + editNoteId + '"]').closest('.card-body');
            existingNoteContainer.find('.private-note-content').text(privateNote);

            isEditing = false;
            editNoteId = undefined;
            // $.ajax({
            //     url: './book-info.php?id=' + bookId,
            //     method: 'POST',
            //     data: { book_id: bookId, private_note: privateNote, edit_private_note_id: editNoteId },
            //     dataType: 'json',
            //     success: function (response) {
            //         if (response.status === 'success') {
            //             let existingNoteContainer = $('.private-note-content[data-note-id="' + editNoteId + '"]').closest('.card-body');
            //             existingNoteContainer.find('.private-note-content').text(privateNote);
    
            //             isEditing = false;
            //             editNoteId = undefined;
    
            //             $('#add-private-note-form')[0].reset();
            //         }
            //         console.log('Data sent successfully:', response);
            //     },
            //     error: function (status, error) {
            //         console.error('AJAX Error:', status, error);
            //     }
            // });
        } else {
            $.ajax({
                url: './book-info.php?id=' + bookId,
                method: 'POST',
                data: { book_id: bookId, private_note: privateNote },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        var newNoteHtml =
                            '<div class="card my-3 border-3 border-violet-500">' +
                            '<div class="card-body">' +
                            '<div class="private-note-content" data-note-id="' + response.id + '">' +
                            '<strong>Your Note:</strong> ' + response.private_note +
                            '</div>' +
                            '<button class="ml-2 btn btn-sm btn-warning edit-private-note" data-note-id="' + response.id + '">Edit</button>' +
                            '<button class="ml-2 btn btn-sm btn-danger delete-private-note" data-note-id="' + response.id + '">Delete</button>' +
                            '</div>' +
                            '</div>';
    
                        $('#private-notes-container').append(newNoteHtml);
    
                        $('#add-private-note-form')[0].reset();
                        $('#add-private-note-form #edit_private_note_id').val('');
                    }
                        console.log('Data sent successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    });

    $('#private-notes-container').on('click', '.delete-private-note', function () {
        let noteId = $(this).data('note-id');
        let bookId = $('#private-notes-container').data('book-id');

        $(this).prop('disabled', true);

        $.ajax({
            url: './book-info.php?id=' + bookId,
            method: 'POST',
            data: { delete_private_note_id: noteId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    loadPrivateNotes();
                } else {
                    console.error('Failed to delete private note:', response);
                }

                $('#add-private-note-form')[0].reset();
            },
            error: function () {
                $('.delete-private-note').prop('disabled', false);
            }
        });
    });

    loadPrivateNotes();
});