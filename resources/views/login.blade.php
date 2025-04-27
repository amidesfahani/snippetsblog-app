<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Snippets</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-md">
            <h1 class="mb-6 text-center text-2xl font-bold">Login</h1>
            <form id="loginForm">
                <div class="mb-4">
                    <label for="username" class="mb-2 block text-gray-700">Username</label>
                    <input type="text" id="username" name="username"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="mb-2 block text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="w-full rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700">
                    Login
                </button>
                <p class="mt-4 text-center text-gray-600">
                    Don't have an account? <a href="#" id="showRegister" class="text-blue-600">Register</a>
                </p>
            </form>

            <form id="registerForm" class="mt-6 hidden">
                <h2 class="mb-4 text-center text-xl font-bold">Register</h2>
                <div class="mb-4">
                    <label for="reg_username" class="mb-2 block text-gray-700">Username</label>
                    <input type="text" id="reg_username" name="username"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="reg_password" class="mb-2 block text-gray-700">Password</label>
                    <input type="password" id="reg_password" name="password"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="w-full rounded-md bg-green-600 px-4 py-2 text-white transition duration-200 hover:bg-green-700">
                    Register
                </button>
                <p class="mt-4 text-center text-gray-600">
                    Already have an account? <a href="#" id="showLogin" class="text-blue-600">Login</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#showRegister').click(function(e) {
                e.preventDefault();
                $('#loginForm').addClass('hidden');
                $('#registerForm').removeClass('hidden');
            });

            $('#showLogin').click(function(e) {
                e.preventDefault();
                $('#registerForm').addClass('hidden');
                $('#loginForm').removeClass('hidden');
            });

            $('#loginForm').submit(function(e) {
                e.preventDefault();
                const username = $('#username').val();
                const password = $('#password').val();

                $.ajax({
                    url: '/api/auth/login',
                    method: 'POST',
                    data: {
                        username,
                        password
                    },
                    success: function(response) {
                        localStorage.setItem('jwt_token', response.token);
                        window.location.href = '/snippets';
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.error || 'Login failed');
                    }
                });
            });

            $('#registerForm').submit(function(e) {
                e.preventDefault();
                const username = $('#reg_username').val();
                const password = $('#reg_password').val();

                $.ajax({
                    url: '/api/auth/register',
                    method: 'POST',
                    data: {
                        username,
                        password
                    },
                    success: function(response) {
                        localStorage.setItem('jwt_token', response.token);
                        window.location.href = '/snippets';
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            alert(Object.values(errors).join('\n'));
                        } else {
                            alert('Registration failed');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
