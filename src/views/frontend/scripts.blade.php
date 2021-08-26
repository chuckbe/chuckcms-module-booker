<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy'
    });
    $('#datepicker').on('changeDate', function() {
        $('#datepicker_hidden').val(
            $('#datepicker').datepicker('getFormattedDate')
        );
        let date = $('#datepicker').datepicker('getFormattedDate');
        $('.date-data #day').html(moment(date, "DD-MM-YYYY").format('dddd'));
        $('.date-data #date').html(moment(date, "DD-MM-YYYY").format('DD MMMM yyyy'));
        $('#datepicker_date_hidden').val(date);
    });
    $('.date-data #day').html(moment($('#datepicker').data('date'), "DD-MM-YYYY").format('dddd'));
    $('.date-data #date').html(moment($('#datepicker').data('date'), "DD-MM-YYYY").format('DD MMMM yyyy'));
    $('#datepicker_date_hidden').val($('#datepicker').data('date'));
    $("#soortafspraak").select2({
        ajax: {
            url: '/dashboard/booker/getservices',
            type: 'get',
            // headers: {
            //     'Accept': 'application/json',
            //     'Authorization': 'Bearer '._token
            // },
            // data: {
            //     _token: $('input[name="_token"]').attr('value')
            // },
            processResults: function (data) {
                let arr = []
                $.each(data.services, function( index, value ) {
                    // console.log(value);
                   arr.push({
                       id : index,
                       text: value.name
                   })
                });
                return {
                    results: arr
                };
            }
        },
        placeholder: 'Select an option',
        allowClear: true
    });
    $("#location").select2({
        ajax: {
            url: '/dashboard/booker/getlocations',
            type: 'get',
            processResults: function (data) {
                let arr = []
                $.each(data.locations, function( index, value ) {
                   arr.push({
                       id : index,
                       text: value.name
                   })
                });
                return {
                    results: arr
                };
            }
        },
        placeholder: 'Select an option',
        allowClear: true
    });
    $('body').on('click', '.step1btn', function(e){
        e.preventDefault();
        if($('form.afspraakform .step1 #soortafspraak').val().length > 0 &&  $("#checkmedicalquestionnaire:checked").length !== 0  && $("#checkmedicalquestionnaire:checked").length !== 0){
            if(!$('form.afspraakform .step1').hasClass('d-none')){
                $('form.afspraakform .step1').addClass('d-none');
                if($('form.afspraakform .step2').hasClass('d-none')){
                    $('form.afspraakform .step2').removeClass('d-none');
                }
            }
            
        }
        // console.log($("#checkmedicalquestionnaire:checked").length);
        // console.log($("#checkterms:checked").length);
        // $('form.afspraakform .step2').removeClass('d-none');
    })

</script>