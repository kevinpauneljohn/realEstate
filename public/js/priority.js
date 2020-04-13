$(function(){

    let addPriorityForm = $('#add-priority-form');

    addPriorityForm.submit(function(form){
        form.preventDefault()

        let data = addPriorityForm.serialize();

        submitform(
            '/priorities',
            'POST',
            data,
            'New Priority Successfully Added!',
            false,
            '',
            true,
            ''
        );

        clear_errors('name','description','day','color');
    });
});
