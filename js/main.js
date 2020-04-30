$('.count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 700,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    $('#password, #confirmpassword').on('keyup', function () {
        if ($('#password').val() == $('#confirmpassword').val()) {
          $('#message').html('Matching').css('color', 'green');
        } else 
          $('#message').html('Not Matching').css('color', 'red');
      });


   
      $("input[name='JumlahBuku']").click(function () {
        $('#show-me').css('display', ($(this).val() === '2') ? 'block':'none');
    });


