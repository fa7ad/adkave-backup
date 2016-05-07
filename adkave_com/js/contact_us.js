$(function() {
    var form_action = $("#contactForm").attr('action');
    $("input,textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // additional error messages or events
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            $.ajax({
                url: $($form).attr('action'),
                type: "POST",
                data: $($form).serializeJSON(),
                dataType: 'json',
                cache: false,
                success: function() {
                    // Success message
                    $('#success').html("<div class='alert alert-success'>");
                    $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-success')
                        .append("<strong>You will receive a call from us soon.</strong>");
                    $('#success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#contactForm').trigger("reset");
                    $('#offer-row,#budget-row,#email-row').fadeOut();
                    $('input[name=company]').closest('.row').fadeIn();
                    $('input[name=name]').closest('.row').fadeIn();
                    $('input[name=designation]').closest('.row').fadeIn();
                },
                error: function() {
                    // Fail message
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-danger').append("<strong>Sorry, it seems that our server is not responding. Please try again later!");
                    $('#success > .alert-danger').append('</div>');
                    //clear all fields
                    $('#contactForm').trigger("reset");
                    $('#offer-row,#budget-row,#email-row').fadeOut();
                    $('input[name=company]').closest('.row').fadeIn();
                    $('input[name=name]').closest('.row').fadeIn();
                    $('input[name=designation]').closest('.row').fadeIn();
                },
            })
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
