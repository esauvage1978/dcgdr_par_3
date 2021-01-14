$(document).ready(function () {

    var file_index = $('#cadrage_filecount').val() * 1;
    var link_index = $('#cadrage_linkcount').val() * 1;

    $('#cadrage_add-file').click(function (e) {
        cadrage_addItem('file');
        e.preventDefault();
        return false;
    });

    $('#cadrage_add-link').click(function (e) {
        cadrage_addItem('link');
        e.preventDefault();
        return false;
    });

    function cadrage_addItem(type) {
        if (type === 'file') {
            index = file_index;
            file_index++;
            name_label = 'Fichier n°' + (index + 1);
        } else if (type === 'link') {
            index = link_index;
            link_index++;
            name_label = 'Lien n°' + (index + 1);

        }


        var $container = $('#cadrage_media-add-' + type);
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, name_label)
            .replace(/__name__/g, index)
            ;
        var $prototype = $(template);

        cadrage_addDeleteLink($prototype,type);
        $container.append($prototype);

        if (type === 'link') {
            pattern = '#action_edit_cadrageLinks_' + index + '_updatedAt';
            $(pattern + '_date_month').val(new Date().getMonth() + 1);
            $(pattern + '_date_day').val(new Date().getDate());
            $(pattern + '_date_year').val(new Date().getFullYear());
            $(pattern + '_time_hour').val(new Date().getHours());
            $(pattern + '_time_minute').val(new Date().getMinutes());
            $(pattern).addClass('d-none');
        }
        if (type === 'file') {
            pattern ='#action_edit_cadrageFiles_' + index + '_updatedAt';
            $(pattern + '_date_month').val(new Date().getMonth() + 1);
            $(pattern + '_date_day').val(new Date().getDate());
            $(pattern + '_date_year').val(new Date().getFullYear());
            $(pattern + '_time_hour').val(new Date().getHours());
            $(pattern + '_time_minute').val(new Date().getMinutes());
            $(pattern ).addClass('d-none');
        }
    }

    function cadrage_addDeleteLink($prototype, type) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger" data-msg="' + type +'">Supprimer</a>');
        $prototype.append($deleteLink);

        $deleteLink.click(function (e) {
            var media_type = $(this).attr('data-msg');
            if (media_type === 'file') {
                file_index--;
            } else if (media_type === 'link') {
                link_index--;
            }
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

    $('.media-delete').click(function (e) {
        e.preventDefault();
        var media_url = $(this).attr('data-media');
        var media_id = $(this).attr('data-msg');
        $('#cadrage_media-' + media_url).remove();
        $('#' + media_id).remove();
    });


    $(".image-pop").on("click", function () {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});

function cadrage_showOtherFile($index) {
    $('#cadrage_cadrageFile_' + $index).removeClass('d-none');
    
    pattern = '#action_edit_cadrageFiles_' + $index + '_updatedAt';
    console.log(pattern);
    $(pattern + '_date_month').val(new Date().getMonth() + 1);
    $(pattern + '_date_day').val(new Date().getDate());
    $(pattern + '_date_year').val(new Date().getFullYear());
    $(pattern + '_time_hour').val(new Date().getHours());
    $(pattern + '_time_minute').val(new Date().getMinutes());
    $(pattern).addClass('d-none');
    
    $('#action_edit_cadrageFiles_' + $index + '_tempoDate').val(new Date());
    
}

function cadrage_showOtherLink($index) {

    pattern = '#action_edit_cadrageLinks_' + $index + '_updatedAt';
    $(pattern + '_date_month').val(new Date().getMonth() + 1);
    $(pattern + '_date_day').val(new Date().getDate());
    $(pattern + '_date_year').val(new Date().getFullYear());
    $(pattern + '_time_hour').val(new Date().getHours());
    $(pattern + '_time_minute').val(new Date().getMinutes());
    $(pattern).addClass('d-none');

    $('#cadrage_cadrageLink_' + $index).removeClass('d-none');
}