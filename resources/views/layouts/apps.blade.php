<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />  
    <link rel="stylesheet" href="{{url('assets/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/feather.css')}}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon.png')}}">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{url('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{url('assets/css/emoji.css')}}">
    
    <link rel="stylesheet" href="{{url('assets/css/lightbox.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<style>
    /* Hide the actual input element */
.file-input {
    display: none;
}

/* Style the label to look like a button */
.file-label {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.file-label:hover {
    background-color: #0056b3;
}

.file-label-icon {
    margin-right: 8px;
    font-size: 1.2em;
}

.file-label-text {
    font-size: 1em;
}

/* Add some responsiveness */
@media (max-width: 600px) {
    .file-label {
        padding: 8px 15px;
        font-size: 0.9em;
    }
}

</style>
</head>

<body class="color-theme-blue mont-font">

    <div class="preloader"></div>

@include('includes.nav')

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{url('assets/js/plugin.js')}}"></script>

    <script src="{{url('assets/js/lightbox.js')}}"></script>
    <script src="{{url('assets/js/scripts.js')}}"></script>
    <script>
        $('#search-query').keyup(function() {
            var search_query = $(this).val();
        var user= $("#user").val();
            $('#search-results').attr("style", "display:block; ")
            $.ajax({
              type: 'POST',
              url: '/searchFriends',
              data: {search_query: search_query,user:user},
              dataType: 'JSON',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
              success: function(data) {
                console.error()
                $('#search-results').empty();
                if (data.length > 0) {
                  $.each(data, function(i, result) {
                   
                    $('#search-results').append('<div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0">' +
    '<figure class="avatar me-3"><a href="/profile_user/' + result.id + '"><img src="/images/' + result.picture + '" alt="image" class="shadow-sm rounded-circle w45"></a></figure>' +
    '<a class="text-decoration-none" href="/profile_user/' + result.id + '"><h4 class="fw-700 text-grey-900 font-xssss mt-1">' + result.name + '</h4></a>' +
    '</div>');


                  });
                  
                } if(data.length == 0) {
                  $('#search-results').html('<div class="text-center font-xssss text-grey-500">No Friend Yet</div>');
                }
              
              },
            //   error: function(xhr, status, error) {
            //     alert(xhr.responseText);
            //   }
            });
          
            if (search_query == '') {
              $('#search-results').attr("style", "display:none;")
            }
          });
    </script>

</body>

</html>