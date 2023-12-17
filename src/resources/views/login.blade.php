<!-- resources/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

        <form id="loginForm">

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 p-2 w-full border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            </div>

            <button type="button" onclick="submitLogin()"
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:border-blue-700">
                Login
            </button>
        </form>

        <div id="loginResult" class="mt-4 text-red-500 text-center"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function submitLogin() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            axios.post('/api/session', {
                email: email,
                password: password
            })
            .then(function (response) {
                var loginResultElement = document.getElementById('loginResult');
                const accessToken = response.data.data.access_token;
                const refreshToken = response.data.data.refresh_token;
                localStorage.setItem('access_token', accessToken);
                localStorage.setItem('refresh_token', refreshToken);
                window.location.href = '/reminder-list';
            })
            .catch(function (error) {
                var loginResultElement = document.getElementById('loginResult');
                loginResultElement.innerHTML = '<p class="text-red-500">Error: ' + error.response.data.msg + '</p>';
            });
        }
    </script>

</body>
</html>
