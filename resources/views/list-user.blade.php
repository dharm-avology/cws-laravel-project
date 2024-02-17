<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
    <div class="container mt-5">
        @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <h1>User Data</h1>
        <p style="text-align: right;"><a href="{{ route('register') }}" class="btn btn-primary">Register</a></p>
        <table id="userTable" class="table">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Resume</th>
                    <th>Photo</th>
                    <th>Actions</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php 
                $count=1;
                ?>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->dob }}</td>
                        <td>{{ $user->gender }}</td>
                        <td>{{ $user->resume }}</td>
                        <td><img src="{{asset('uploads/photos/'.$user->photo)}}" alt="images" width="50" height="60"></td>
                        <td width="100%">
                            <!-- Action buttons (e.g., edit, delete) -->
                            <a href="javascript:" class="btn btn-primary btn-sm" onclick="getUserDetails({{ $user->id }})">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this record?')) { return true; } else{ return false; }"><i class="fa fa-trash"></i></a>
                             
                        </td>
                    </tr>
                    <?php 
                    $count++;
                    ?>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body" id="userDetails">
            
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>      
        </div>
    </div>   

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
        });
    </script>
    
<script>
  function getUserDetails(userId) {
    // You can use AJAX to fetch user details using the user_id
    // For demonstration, I'll just populate some sample data here
    // Make AJAX call
    $.ajax({
      url: '{{ route("user.details") }}',
      type: 'POST',
      data: {
        user_id: userId,
        _token: '{{ csrf_token() }}'
      },
      success: function(response) {
        if(response.success == true){
            // Populate user details in the modal body
            document.getElementById('userDetails').innerHTML = response.data;
            $('#myModal').modal('show');
        }else{
            alert('User details are not found');
        }
        
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });  
  }
</script>

</body>
</html>
