<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Registration Form</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container mt-5">
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    <h2>Edit Registration Form</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <p style="text-align: right;"><a href="{{ route('users.list') }}" class="btn btn-primary">Go to user list</a></p>

    <form id="registrationForm" method="POST" action="{{ route('users.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{$user->first_name}}" required>
        </div>
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{$user->last_name}}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required readonly>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required>{{$user->address}}</textarea>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="{{$user->dob}}" required>
        </div>
        <div class="form-group">
            <label>Gender</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" active name="gender" id="male" value="male" {{ $user->gender == 'male' ? 'checked' : '' }} required>
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ $user->gender == 'female' ? 'checked' : '' }}>
                <label class="form-check-label" for="female">Female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="others" value="others" {{ $user->gender == 'others' ? 'checked' : '' }}>
                <label class="form-check-label" for="others">Others</label>
            </div>
        </div>
        <div class="form-group">      
            @if($user->resume)
            <a href="{{asset('uploads/resumes/'.$user->resume)}}" target="_blank" class="mb-2"><i class="fa fa-file-pdf-o" style="font-size:48px;color:red"></i></a><br>
            @endif                
            <label for="resume">Resume (PDF or DOCX)</label>                            
            <input type="file" class="form-control-file" id="resume" name="resume" accept=".pdf,.docx">
        </div>
        <div class="form-group">
            @if($user->photo)
            <img src="{{asset('uploads/photos/'.$user->photo)}}" alt="images" width="50" height="60"><br>
            @endif
            <label for="photo">Applicant's Photo (JPG or PNG)</label>
            <input type="file" class="form-control-file" id="photo" name="photo" accept=".jpg,.png">
        </div>
        <button type="submit" class="btn btn-primary mb-4">Save Changes</button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="myImagePreviewModal" role="dialog">
        <div class="modal-dialog">    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>            
            </div>
            <div class="modal-body" id="userDetails">
            <img id="imagePreview" src="#" alt="Image Preview" style="width: 100%; max-height: 300px;">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>      
        </div>
    </div>

<!-- jQuery -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<!-- jQuery Validation Additional Methods (for custom validation rules) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>

<script>
    $(document).ready(function () {
        // Custom method to validate if the date of birth is for users who are 18 years or older
        $.validator.addMethod("eighteenOrOlder", function(value, element) {
            var dob = new Date(value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age >= 18;
        }, "You must be 18 years or older.");
        // Validation rules
        $('#registrationForm').validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                phone: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                address: {
                    required: true
                },
                dob: {
                    required: true,
                    eighteenOrOlder: true
                },
                gender: {
                    required: true
                },
                resume: {                    
                    accept: "application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                },
                photo: {                    
                    accept: "image/jpeg,image/png"
                }
            },
            messages: {
                resume: {
                    accept: "Please upload a PDF or DOCX file"
                },
                photo: {
                    accept: "Please upload a JPG or PNG image"
                },
                dob: {                   
                    eighteenOrOlder: "You must be 18 years or older."
                }
            }
        });
    });
</script>

<script>
  document.getElementById('photo').addEventListener('change', function(event) {
    var reader = new FileReader();
    reader.onload = function() {
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    $('#myImagePreviewModal').modal('show');
  });
</script>

</body>
</html>
