<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div id="modal-book" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Booking</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="date1" class="lead text-dark m-0">Specify the period of auto booking</label>
                    <input type="datetime-local" name="date1" id="date1" class="form-select my-2 border rounded-pill">
                    <input type="datetime-local" name="date2" id="date2" class="form-select my-2 border rounded-pill">
                </div>
                <div class="modal-footer">
                    <div class="d-flex flex-row justify-content-center">
                        <button class="my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-success">
                            Confirm
                        </button>
                        <button class="my-2 mx-1 rounded-pill border border-2 border-dark px-2 py-1 fs-5 bg-danger">
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
                                    html += '<tr>\
                                            <td>'+bookings[i]['id']+'</td>\
                                            <td>'+bookings[i]['category']+'</td>\
                                            <td>'+bookings[i]['model']+'</td>\
                                            <td>'+bookings[i]['booking_from'] +'</td>\
                                            <td>'+bookings[i]['booking_to'] +'</td>\
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
                }
                else{
                    document.getElementById("btn-book").classList.add("visually-hidden");
                }
            })
        })
    </script>

</body>
</html>