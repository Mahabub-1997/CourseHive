
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>

    <!-- Tailwind CSS (optional, replace with AdminLTE CSS if you prefer) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md p-8 bg-white shadow-xl rounded-xl">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Update Password</h2>
    <p class="text-center text-gray-500 mb-6 text-sm">
        Ensure your account is using a strong password to stay secure.
    </p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="current_password" class="block font-medium text-gray-700">Current Password</label>
            <input id="current_password" name="current_password" type="password" placeholder="Enter current password"
                   class="mt-2 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 @error('current_password') border-red-500 @enderror">
            @error('current_password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block font-medium text-gray-700">New Password</label>
            <input id="password" name="password" type="password" placeholder="Enter new password"
                   class="mt-2 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 @error('password') border-red-500 @enderror">
            @error('password')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-medium text-gray-700">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password"
                   class="mt-2 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 @error('password_confirmation') border-red-500 @enderror">
            @error('password_confirmation')
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg">
                Save Password
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-green-600 text-center mt-2">Password updated successfully! 🎉</p>
            @endif
        </div>
    </form>
</div>

</body>
</html>
