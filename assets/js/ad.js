$(function() {

    $('#add-image').click(function() {
        const index = +$('#form-counter').val();
        const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

        $('#ad_images').append(tmpl);

        $('#form-counter').val(index + 1) 

        handleDeleteImageForm();
    });

    function handleDeleteImageForm() {
        $('button[data-action=delete]').click(function() {
            const formLine = this.dataset.target;
            $('#'+formLine).remove();
            this.remove();
        });
    }

    function updateCouter() {
        const counter = $('#ad_images div.form-group').length;
        $('#form-counter').val(counter);
    }

    updateCouter();
    handleDeleteImageForm();
})

