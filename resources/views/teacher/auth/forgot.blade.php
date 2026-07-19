<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — Teacher Portal — JDCA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="portal-auth-page min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4" style="background:#1e3a5f">
      <span class="text-white font-bold text-lg">JDCA</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-50">Forgot Password</h1>
    <p class="text-slate-400 text-sm mt-1">Teacher Portal</p>
  </div>

  <div class="portal-auth-card rounded-3xl p-8">
    <p class="text-sm text-slate-400 mb-6">Apna <b>Employee ID</b> (jaise JDCA-T-001) ya <b>email</b> daalein. Aapke email par ek 6-digit code bheja jayega.</p>

    @if(session('success'))
    <div class="rounded-xl p-4 mb-5 text-sm text-emerald-200" style="background: rgba(22,163,74,0.14); border: 1px solid rgba(34,197,94,0.24)">{{ session('success') }}</div>
    @endif

    <form action="{{ route('teacher.password.email') }}" method="POST" class="space-y-5">
      @csrf
      <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Employee ID or Email</label>
        <input type="text" name="login" value="{{ old('login') }}" required autofocus
          class="portal-auth-input w-full px-4 py-3 rounded-xl text-sm focus:outline-none @error('login') border-red-400 @enderror">
        @error('login')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
      <button type="submit" class="portal-btn-primary w-full py-3.5 font-semibold rounded-xl transition">Send Code</button>
    </form>

    <div class="mt-6 pt-5 text-center text-xs" style="border-top: 1px solid rgba(255,255,255,0.08)">
      <a href="{{ route('teacher.login') }}" class="text-slate-300 hover:text-white">← Back to login</a>
    </div>
  </div>
</div>
</body>
</html>
