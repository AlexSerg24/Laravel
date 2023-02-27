<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Search</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <main style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <select name="emploee" id="emploee-select" style="border: 2px solid #dee2e6; border-radius: 50rem; font-size: 1rem; font-weight: 400; padding: 0.275rem 0.75rem;">
            <option value="">Select emploee</option>
            @if(count($emploees) > 0 )
                @foreach($emploees as $emploee)
                    @if($emploee['post_id'] != 1)
                        <option value="{{ $emploee['post_id'] }}">{{  $emploee->last_name }} {{  $emploee->first_name }}</option>
                    @endif
                @endforeach
            @endif
        </select>

        <hr width="50%" style="margin: 0.5rem;">
        <label for="table" id="tableName" style="font-size: 1rem; font-weight: 700; margin: 0.5rem;">All bookings from table</label>

        <table border=1 width="50%" >
            <tr>
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
                $("#tableName").html('All information about booking autos available to the employee');
                //console.log(postId);
                $.ajax({
                    url:"{{ route('filter')}}",
                    type:"GET",
                    data:{'post_id':postId},
                    success:function(data){
                        //console.log(data);
                        var autoCategory = data.posts[0]['auto_category'];
                        //console.log(autoCategory);
                        $.ajax({
                            url:"{{ route('filter_auto') }}",
                            type:"GET",
                            data:{'auto_category':autoCategory},
                            success:function(data){
                                var autosArray = data.autos;
                                var html = '';
                                if (autosArray.length > 0) {
                                    //console.log(autosArray);
                                    Array.prototype.forEach.call(autosArray, function(auto){
                                        $.ajax({
                                            url:"{{ route('filter_booking') }}",
                                            type:"GET",
                                            data:{'auto_id':auto['id']},
                                            success:function(data){
                                                //console.log(data);
                                                var bookings = data.bookings;
                                                if(bookings.length > 0) {
                                                    for (var i = 0; i<bookings.length; i++){
                                                        html += '<tr>\
                                                                <td>'+auto['id']+'</td>\
                                                                <td>'+auto['category']+'</td>\
                                                                <td>'+auto['model']+'</td>\
                                                                <td>'+bookings[i]['booking_from']+'</td>\
                                                                <td>'+bookings[i]['booking_to']+'</td>\
                                                                </tr>';
                                                        $("#tbody").html(html);
                                                    }
                                                }
                                                else {
                                                    html += '<tr>\
                                                            <td>'+auto['id']+'</td>\
                                                            <td>'+auto['category']+'</td>\
                                                            <td>'+auto['model']+'</td>\
                                                            <td>'+'--'+'</td>\
                                                            <td>'+'--'+'</td>\
                                                            </tr>';
                                                    $("#tbody").html(html);
                                                }
                                            }
                                        })
                                    })
                                }
                                else{
                                    html += '<tr>\
                                            <td>No avaliable autos</td>\
                                            </tr>';
                                    $("#tbody").html(html);        
                                }

                                
                            }
                        })
                    }
                })
            })
        })
    </script>

</body>
</html>