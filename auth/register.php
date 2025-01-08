<?php
session_start();
require_once '../Classes/Authentication.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'utilisateur';

    if (!empty($username) || !empty($email) || !empty($password)) {
        $auth = new Authentication();
        $auth->signup($full_name, $username, $email, $password, $role);
        header('Location: login.php');
        
    } else {
        echo "Please fill in all fields.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Blog</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="https://preline.co/favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- CSS Preline -->
    <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">
</head>

<body class="dark:bg-neutral-900">
    <header class="bg-white border-b border-gray-200 flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full hidden">
        <nav class="relative max-w-[85rem] w-full md:flex md:items-center md:justify-between md:gap-3 mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <!-- Logo w/ Collapse Button -->
            <div class="flex items-center justify-between">
                <a class="flex-none font-semibold text-xl text-black focus:outline-none focus:opacity-80" href="#" aria-label="Brand">Brand</a>

                <!-- Collapse Button -->
                <div class="md:hidden">
                    <button type="button" class="hs-collapse-toggle relative size-9 flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" id="hs-header-classic-collapse" aria-expanded="false" aria-controls="hs-header-classic" aria-label="Toggle navigation" data-hs-collapse="#hs-header-classic">
                        <svg class="hs-collapse-open:hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6" />
                            <line x1="3" x2="21" y1="12" y2="12" />
                            <line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                        <svg class="hs-collapse-open:block shrink-0 hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                        <span class="sr-only">Toggle navigation</span>
                    </button>
                </div>
                <!-- End Collapse Button -->
            </div>
            <!-- End Logo w/ Collapse Button -->

            <!-- Collapse -->
            <div id="hs-header-classic" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block" aria-labelledby="hs-header-classic-collapse">
                <div class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
                    <div class="py-2 md:py-0 flex flex-col md:flex-row md:items-center md:justify-end gap-0.5 md:gap-1">
                        <a class="p-2 flex items-center text-sm text-blue-600 focus:outline-none focus:text-blue-600" href="../index.php" aria-current="page">
                            <svg class="shrink-0 size-4 me-3 md:me-2 block md:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                                <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            </svg>
                            Home
                        </a>

                        <a class="p-2 flex items-center text-sm text-gray-800 hover:text-gray-500 focus:outline-none focus:text-gray-500" href="#">
                            <svg class="shrink-0 size-4 me-3 md:me-2 block md:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Blog
                        </a>

                        <a class="p-2 flex items-center text-sm text-gray-800 hover:text-gray-500 focus:outline-none focus:text-gray-500" href="#">
                            <svg class="shrink-0 size-4 me-3 md:me-2 block md:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 12h.01" />
                                <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                                <path d="M22 13a18.15 18.15 0 0 1-20 0" />
                                <rect width="20" height="14" x="2" y="6" rx="2" />
                            </svg>
                            Contact us
                        </a>

                        <a class="p-2 flex items-center text-sm text-gray-800 hover:text-gray-500 focus:outline-none focus:text-gray-500" href="#">
                            <svg class="shrink-0 size-4 me-3 md:me-2 block md:hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2" />
                                <path d="M18 14h-8" />
                                <path d="M15 18h-5" />
                                <path d="M10 6h8v4h-8V6Z" />
                            </svg>
                            About us
                        </a>


                        <!-- End Dropdown -->

                        <!-- Button Group -->
                        <div class="relative flex flex-wrap items-center gap-x-1.5 md:ps-2.5 mt-1 md:mt-0 md:ms-1.5 before:block before:absolute before:top-1/2 before:-start-px before:w-px before:h-4 before:bg-gray-300 before:-translate-y-1/2">
                            <a class="p-2 w-full flex items-center text-sm text-gray-800 hover:text-gray-500 focus:outline-none focus:text-gray-500" href="#">
                                <svg class="shrink-0 size-4 me-3 md:me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Log in
                            </a>
                        </div>

                        <!-- End Button Group -->
                    </div>
                </div>
            </div>
            <!-- End Collapse -->
        </nav>
    </header>

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md mx-auto mt-12 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="p-6 sm:p-8">
                <div class="text-center">
                    <h1 class="block text-2xl font-bold text-gray-800">Register</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        you have an account
                        <a class="text-blue-600 hover:underline font-medium" href="login.php">sing in</a>
                    </p>
                </div>

                <div class="mt-6">

                    <!-- Form -->
                    <form action="./register.php" method="POST">
                        <div class="grid gap-y-4">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm mb-2">Full Name</label>
                                <input type="text" id="full_name" name="full_name"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter your full name" required>
                            </div>

                            <!-- Username -->
                            <div>
                                <label for="username" class="block text-sm mb-2">Username</label>
                                <input type="text" id="username" name="username"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter your username" required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm mb-2">Email address</label>
                                <input type="email" id="email" name="email"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter your email" required>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm mb-2">Password</label>
                                <input type="password" id="password" name="password"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter your password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirm_password" class="block text-sm mb-2">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Confirm your password" required>
                            </div>

                            <!-- Role Selection -->
                            <!-- <div>
                                <label for="role" class="block text-sm mb-2">Role</label>
                                <select id="role" name="role"
                                    class="py-2.5 px-4 block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="utilisateur">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div> -->

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-2.5 px-4 rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">Register</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>


    <!-- ========== END HEADER ========== -->
    <!-- JS Implementing Plugins -->

    <!-- JS PLUGINS -->
    <!-- Required plugins -->
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
    <script src="./node_modules/preline/dist/preline.js"></script>
</body>

</html>