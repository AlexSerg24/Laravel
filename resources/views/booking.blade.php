<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Booking Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<header>
    <div class="bg-warning py-1">
        <i class="fa fa-motorcycle text-dark fa-2x mx-3 moving"></i>
    </div>
</header>
<body>
    <div id="modal-book" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Booking</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-dark m-0">You choosed:</h5>
                    <p id="auto-name" class="text-success m-0 fs-3"></p>
                    <h5 for="date1" class="text-dark m-1">Specify the period of auto booking</h5>
                    <p class="text-danger m-0 visually-hidden" id="empty-dates-error">Some date is empty!</p>
                    <p class="lead m-0">Choose start date</p>
                    <input type="datetime-local" name="date1" id="date1" class="form-select my-2 border rounded-pill" required>
                    <span class="validity"></span>
                    <p class="lead m-0">Choose end date</p>
                    <input type="datetime-local" name="date2" id="date2" class="form-select my-2 border rounded-pill" required>
                    <span class="validity"></span>
                </div>
                <div class="modal-footer">
                    <div class="d-flex flex-row justify-content-center">
                        <button class="my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-success" id="confirm">
                            <i class="fa fa-check"></i>
                            Confirm
                        </button>
                        <button class="my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-danger" type="button" data-bs-dismiss="modal" id="btn-cancel">
                            <i class="fa fa-close"></i>    
                            Cancel
                        </button>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
    <main class="align-items-center d-flex flex-column justify-content-center">
        <div class="d-flex flex-row justify-content-evenly">
            <select class="select my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-info" name="emploee" id="emploee-select">
                <option value="">Select emploee</option>
                @if(count($emploees) > 0 )
                    @foreach($emploees as $emploee)
                        @if($emploee['post_id'] != 1)
                            <option value="{{ $emploee['post_id'] }}">{{  $emploee->last_name }} {{  $emploee->first_name }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
            <select class="select my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-primary visually-hidden" name="auto-select" id="auto-select">
                <option value="">Select auto</option>
            </select>
            <button class="my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-success visually-hidden" id="btn-book" type="button" data-bs-target="#modal-book" data-bs-toggle="modal">
                <i class="fa fa-pencil"></i>    
                Book auto
            </button>
        </div>
        <hr class="m-1 w-75">
        <label for="table" id="tableName" class="m-1 fs-4">All bookings from table</label>

        <table class="table w-75 table-bordered border-secondary align-middle">
            <tr class="table-light">
                <th>Auto ID</th>
                <th>Auto category</th>
                <th>Auto model</th>
                <th>Booking from</th>
                <th>Booking to</th>
            </tr>
            <tbody id="tbody">
                @if(count($bookings) > 0)
                    @foreach($autos as $auto)
                        @foreach($bookings as $booking)
                            @if($auto['id'] == $booking['auto_id'])
                                <tr>
                                    <td>{{  $auto['id'] }}</td>
                                    <td>{{  $auto['category'] }}</td>
                                    <td>{{  $auto['model'] }}</td>
                                    <td>{{  $booking['booking_from'] }}</td>
                                    <td>{{  $booking['booking_to'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </main>
    <script>
        $(document).ready(function(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $("#emploee-select").on('change',function(){
                var postId = $(this).val();
                var auto_select = document.getElementById("auto-select");
                for (var i=auto_select.options.length-1; i>-1; i--){ 
                    if (auto_select.options[i].value!=""){
                        auto_select.remove(i);
                    }                                   
                }
                $("#tableName").html('All information about booking autos available to the employee');
                $.ajax({
                    url:"{{ route('filter')}}",
                    type:"GET",
                    data:{'post_id':postId},
                    success:function(data){
                        var bookings = data.output;
                        var html = '';
                        var options = [];
                        var optionslab = [];
                        if (bookings.length > 0 ) {
                            for (var i = 0; i<bookings.length; i++){
                                if (bookings[i]['booking_from']) {
                                    var fromDate = new Date(bookings[i]['booking_from']);
                                    var toDate = new Date(bookings[i]['booking_to']);
                                    var dayOpt = { day: 'numeric', month: 'long', year:'numeric', timezone: 'UTC' };
            	                    var timeOpt = { hour: 'numeric', minute: 'numeric', timezone: 'UTC' };
                                    var fromTime = fromDate.toLocaleTimeString("ru", timeOpt) + "  " + fromDate.toLocaleDateString("en", dayOpt);
                                    var toTime = toDate.toLocaleTimeString("ru", timeOpt) + "  " + toDate.toLocaleDateString("en", dayOpt);
                                    html += '<tr>\
                                            <td>'+bookings[i]['id']+'</td>\
                                            <td>'+bookings[i]['category']+'</td>\
                                            <td>'+bookings[i]['model']+'</td>\
                                            <td>'+fromTime+'</td>\
                                            <td>'+toTime+'</td>\
                                            </tr>';
                                    if (!options.includes(bookings[i]['id'])){
                                        options.push(bookings[i]['id']);
                                        optionslab.push(bookings[i]['id'] + ' ' + bookings[i]['model']);
                                    }                                           
                                    $("#tbody").html(html);
                                }
                                else {
                                    html += '<tr>\
                                            <td>'+bookings[i]['id']+'</td>\
                                            <td>'+bookings[i]['category']+'</td>\
                                            <td>'+bookings[i]['model']+'</td>\
                                            <td>'+'--'+'</td>\
                                            <td>'+'--'+'</td>\
                                            </tr>';
                                    if (!options.includes(bookings[i]['id'])){
                                        options.push(bookings[i]['id']);
                                        optionslab.push(bookings[i]['id'] + ' ' + bookings[i]['model']);
                                    } 
                                    $("#tbody").html(html);
                                }
                            }
                            if (postId){
                                for (var i=0; i<options.length; i++){
                                    var el = document.createElement("option");
                                    el.textContent = optionslab[i];
                                    el.value = options[i];
                                    auto_select.appendChild(el);
                                }
                                auto_select.classList.remove("visually-hidden");
                            }
                            else{
                                auto_select.classList.add("visually-hidden");
                            }
                        }
                        else{
                            html += '<tr>\
                                    <td>No avaliable autos</td>\
                                    </tr>';
                            $("#tbody").html(html);        
                        }
                    }
                })
            })
            $("#auto-select").on('change',function(){
                var autoId = $(this).val();
                if (autoId!=""){
                    document.getElementById("btn-book").classList.remove("visually-hidden");
                    document.getElementById("auto-name").innerHTML = document.getElementById("auto-select").selectedOptions[0].innerHTML;
                }
                else{
                    document.getElementById("btn-book").classList.add("visually-hidden");
                }
            })
            $("#confirm").on('click',function(){
                var date1 = document.getElementById("date1").value;
                var date2 = document.getElementById("date2").value
                if (date1!="" && date2!=""){
                    var a_id = document.getElementById("auto-select").selectedOptions[0].value;
                    var e_id = document.getElementById("emploee-select").selectedOptions[0].value;
                    var d1 = document.getElementById("date1").value;
                    var d2 = document.getElementById("date2").value;
                    d1 = d1.replace('T', ' ') + ':00';
                    d2 = d2.replace('T', ' ') + ':00';
                    //console.log(d1,d2);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url:'/add_booking',
                        type:"POST",
                        data:{_token: CSRF_TOKEN, 'auto_id':a_id, 'emploee_id':e_id, 'booking_from':d1, 'booking_to':d2}, //
                        dataType: 'JSON',
                        success:function(data){
                            console.log(data);
                            if (data.success) {
                                document.getElementById("date1").value = "";
                                document.getElementById("date2").value = "";
                                var success_msg = document.createElement("p");
                                success_msg.setAttribute("class", "text-success m-0 fs-3");
                                success_msg.setAttribute("id", "success-ms");
                                success_msg.innerHTML = "You have successfully booked this car";
                                document.getElementById("date2").parentNode.insertBefore(success_msg, document.getElementById("date2").nextElementSibling);
                                function hide(){
                                    $('#modal-book').modal('hide');
                                    document.getElementById("success-ms").remove();
                                    var emp_select = document.getElementById("emploee-select");
                                    for(var i, j = 0; i = emp_select.options[j]; j++) {
                                        if(i.value == "") {
                                            emp_select.selectedIndex = j;
                                            break;
                                        }
                                    }
                                    emp_select.dispatchEvent(new Event('change'));
                                    document.getElementById("btn-book").classList.add("visually-hidden");
                                }
                                setTimeout(hide, 2000);
                            }
                            else {
                                var error_msg = document.createElement("p");
                                error_msg.setAttribute("class", "text-danger m-0 fs-3");
                                error_msg.setAttribute("id", "error-ms");
                                if(data.error || data.error2){
                                    error_msg.innerHTML = "You have entered incorrect auto booking dates. Please enter dates that will not overlap with other booking dates for this auto.";
                                }
                                else if(data.validation_error) {
                                    error_msg.innerHTML = "You have entered incorrect auto booking dates. Your start date is greater then end date."
                                }
                                else {
                                    error_msg.innerHTML = "Some uncaught error."
                                }
                                document.getElementById("date2").parentNode.insertBefore(error_msg, document.getElementById("date2").nextElementSibling);
                            }
                        },
                        error: function(data){
                            console.log(data);
                        }
                    })
                }
                else {
                    document.getElementById("empty-dates-error").classList.remove("visually-hidden");
                }               
            })
            $("#date1").on('change',function(){
                if(!document.getElementById("empty-dates-error").classList.contains("visually-hidden")){
                    document.getElementById("empty-dates-error").classList.add("visually-hidden");
                }
                if(document.getElementById("error-ms")){
                    document.getElementById("error-ms").remove();
                }
            })
            $("#date2").on('change',function(){
                if(!document.getElementById("empty-dates-error").classList.contains("visually-hidden")){
                    document.getElementById("empty-dates-error").classList.add("visually-hidden");
                }
                if(document.getElementById("error-ms")){
                    document.getElementById("error-ms").remove();
                }
            })
        })
    </script>

</body>
</html>