<!DOCTYPE html>
<html>

<head>
    <title>Tripay - Login</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <h2>Login</h2>

    <input id="username" placeholder="Username">
    <input id="password" type="password" placeholder="Password">
    <button id="loginBtn">Login</button>

    <div id="msg"></div>

    <script>
        $('#loginBtn').on('click', function() {

            $.ajax({
                url: 'auth/login_action.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                success: function(res) {
                    if (res.status === 'ok') {
                        location.href = 'dashboard/index.php';
                    } else {
                        $('#msg').text(res.message);
                    }
                },
                error: function() {
                    $('#msg').text('Server error');
                }
            });

        });
    </script>

</body>

</html>