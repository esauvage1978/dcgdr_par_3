$(document).ready(function () {

    var file_index = $('#action_filecount').val() * 1;
    var link_index = $('#action_linkcount').val() * 1;

    $('#action_add-file').click(function (e) {
        action_addItem('file');
        e.preventDefault();
        return false;
    });

    $('#action_add-link').click(function (e) {
        action_addItem('link');
        e.preventDefault();
        return false;
    });

    function action_addItem(type) {

        if (type === 'file') {
            index = file_index;
            file_index++;
            name_label = 'Fichier n°' + (index + 1);
        } else if (type === 'link') {
            index = link_index;
            link_index++;
            name_label = 'Lien n°' + (index + 1);

        }


        var $container = $('#action_media-add-' + type);
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, name_label)
            .replace(/__name__/g, index)
            ;
        var $prototype = $(template);

        action_addDeleteLink($prototype, type);
        $container.append($prototype);

        if (type === 'link') {
            pattern = '#action_edit_actionLinks_' + index + '_updatedAt';
            $(pattern + '_date_month').val(new Date().getMonth() + 1);
            $(pattern + '_date_day').val(new Date().getDate());
            $(pattern + '_date_year').val(new Date().getFullYear());
            $(pattern + '_time_hour').val(new Date().getHours());
            $(pattern + '_time_minute').val(new Date().getMinutes());
            $(pattern).addClass('d-none');
        }
        if (type === 'file') {
            pattern = '#action_edit_actionFiles_' + index + '_updatedAt';
            $(pattern + '_date_month').val(new Date().getMonth() + 1);
            $(pattern + '_date_day').val(new Date().getDate());
            $(pattern + '_date_year').val(new Date().getFullYear());
            $(pattern + '_time_hour').val(new Date().getHours());
            $(pattern + '_time_minute').val(new Date().getMinutes());
            $(pattern).addClass('d-none');
        }
    }

    function action_addDeleteLink($prototype, type) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger" data-msg="' + type + '">Supprimer</a>');
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
        $('#action_media-' + media_url).remove();
        $('#' + media_id).remove();
    });

});

function action_showOtherFile($index) {
    $('#action_actionFile_' + $index).removeClass('d-none');

    pattern = '#action_edit_actionFiles_' + $index + '_updatedAt';
    console.log(pattern);
    $(pattern + '_date_month').val(new Date().getMonth() + 1);
    $(pattern + '_date_day').val(new Date().getDate());
    $(pattern + '_date_year').val(new Date().getFullYear());
    $(pattern + '_time_hour').val(new Date().getHours());
    $(pattern + '_time_minute').val(new Date().getMinutes());
    $(pattern).addClass('d-none');

    $('#action_edit_actionFiles_' + $index + '_tempoDate').val(new Date());

}

function action_showOtherLink($index) {

    pattern = '#action_edit_actionLinks_' + $index + '_updatedAt';
    $(pattern + '_date_month').val(new Date().getMonth() + 1);
    $(pattern + '_date_day').val(new Date().getDate());
    $(pattern + '_date_year').val(new Date().getFullYear());
    $(pattern + '_time_hour').val(new Date().getHours());
    $(pattern + '_time_minute').val(new Date().getMinutes());
    $(pattern).addClass('d-none');

    $('#action_actionLink_' + $index).removeClass('d-none');
}