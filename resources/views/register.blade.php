<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Registration Form</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">     -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    <h2>Applicant Registration Form</h2>
    <p style="text-align: right;"><a href="{{ route('users.list') }}" class="btn btn-primary">Go to user list</a></p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="registrationForm" method="POST" action="{{ route('registration.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label>Gender</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                <label class="form-check-label" for="female">Female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="others" value="others">
                <label class="form-check-label" for="others">Others</label>
            </div>
        </div>
        <div class="form-group">
            <label for="resume">Resume (PDF or DOCX)</label>
            <input type="file" class="form-control-file" id="resume" name="resume" accept=".pdf,.docx" required>
        </div>
        <div class="form-group">
            <label for="photo">Applicant's Photo (JPG or PNG)</label>
            <input type="file" class="form-control-file" id="photo" name="photo" accept=".jpg,.png" required>
        </div>
        <button type="submit" class="btn btn-primary mb-4">Submit</button>
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
            <canvas id="croppedImage" style="display: none;"></canvas>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <!--<button type="button" class="btn btn-primary" id="cropButton">Crop and Save</button> -->    
            </div>
        </div>      
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- jQuery Validation Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<!-- jQuery Validation Additional Methods (for custom validation rules) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


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
                    required: true,
                    accept: "application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                },
                photo: {
                    required: true,
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
<script>
// document.getElementById('photo').addEventListener('change', function(event) {
    // var reader = new FileReader();
    // reader.onload = function() {
      // var output = document.getElementById('imagePreview');
      // output.src = reader.result;

      // // Initialize Cropper.js
      // var cropper = new Cropper(output, {
        // aspectRatio: 1, // Aspect ratio (square)
        // viewMode: 2, // Show both the cropper and the cropped area outside the canvas
      // });

      // // Crop and save button event handler
      // document.getElementById('cropButton').addEventListener('click', function() {
        // // Get the cropped canvas data
        // var canvas = cropper.getCroppedCanvas({
          // width: 300, // Resize width to 300px
          // height: 300, // Resize height to 300px
        // });

        // // Get the data URL of the cropped image
        // var croppedImage = canvas.toDataURL();

        // // Display the cropped image
        // var croppedImageElement = document.getElementById('croppedImage');
        // croppedImageElement.style.display = 'block';
        // croppedImageElement.src = croppedImage;

        // // You can now submit the cropped image to the server or perform other actions
      // });
    // };
    // reader.readAsDataURL(event.target.files[0]);
    // $('#myImagePreviewModal').modal('show');
  // });
</script>

</body>
</html>
