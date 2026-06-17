<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Portal Login — JDCA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
  {{-- Logo --}}
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl bg-navy mx-auto flex items-center justify-center mb-4">
      <span class="text-white font-bold text-lg">JDCA</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Student Portal</h1>
    <p class="text-gray-500 text-sm mt-1">Jinnah School & Degree College Astore</p>
  </div>

  {{-- Card --}}
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">Sign in to your account</h2>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <form action="{{ route('portal.login.post') }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Roll Number</label>
        <input type="text" name="roll_number" value="{{ old('roll_number') }}" required
          placeholder="e.g. CS-2024-001"
          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy @error('roll_number') border-red-400 @enderror">
        @error('roll_number')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
        <input type="password" name="password" required
          placeholder="Your password"
          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy @error('password') border-red-400 @enderror">
        @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="rounded">
        <label for="remember" class="text-sm text-gray-600">Remember me</label>
      </div>

      <button type="submit" class="w-full py-3.5 bg-navy text-white font-semibold rounded-xl hover:bg-navy-dark transition">
        Sign In
      </button>
    </form>

    <div class="mt-6 pt-5 border-t border-gray-100 text-center text-xs text-gray-400">
      <p>Default password: your roll number</p>
      <p class="mt-1">Contact admin to reset your password</p>
    </div>
  </div>

  <div class="text-center mt-6">
    <a href="{{ route('home') }}" class="text-sm text-navy hover:text-navy-dark transition">← Back to College Website</a>
  </div>
</div>

</body>
</html>
