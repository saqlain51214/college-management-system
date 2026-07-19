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
<body class="portal-auth-page min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
  {{-- Logo --}}
  <div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4" style="background:#1e3a5f">
      <span class="text-white font-bold text-lg">JDCA</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-50">Student Portal</h1>
    <p class="text-slate-400 text-sm mt-1">Jinnah Degree College Astore</p>
  </div>

  {{-- Card --}}
  <div class="portal-auth-card rounded-3xl p-8">
    <h2 class="text-lg font-semibold text-slate-50 mb-2">Sign in to your account</h2>
    <p class="text-sm text-slate-400 mb-6">Access results, fee status, notices, and your academic profile.</p>

    @if(session('error'))
    <div class="rounded-xl p-4 mb-5 text-sm text-rose-200" style="background: rgba(239,68,68,0.14); border: 1px solid rgba(248,113,113,0.24)">{{ session('error') }}</div>
    @endif

    @if(session('success'))
    <div class="rounded-xl p-4 mb-5 text-sm text-emerald-200" style="background: rgba(22,163,74,0.14); border: 1px solid rgba(34,197,94,0.24)">{{ session('success') }}</div>
    @endif

    <form action="{{ route('portal.login.post') }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Registration Number</label>
        <input type="text" name="login" value="{{ old('login') }}" required
          placeholder="e.g. 2024-KIU-ADP-3048"
          class="portal-auth-input w-full px-4 py-3 rounded-xl text-sm focus:outline-none @error('login') border-red-400 @enderror">
        @error('login')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
        <input type="password" name="password" required
          placeholder="Enter your password"
          class="portal-auth-input w-full px-4 py-3 rounded-xl text-sm focus:outline-none @error('password') border-red-400 @enderror">
        @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center justify-between gap-2">
        <label class="flex items-center gap-2 text-sm text-slate-400">
          <input type="checkbox" name="remember" id="remember" class="rounded"> Remember me
        </label>
        <a href="{{ route('portal.password.request') }}" class="text-sm text-sky-300 hover:text-white">Forgot password?</a>
      </div>

      <button type="submit" class="portal-btn-primary w-full py-3.5 font-semibold rounded-xl transition">
        Sign In
      </button>
    </form>
  </div>

  <div class="text-center mt-6">
    <a href="{{ route('home') }}" class="text-sm text-slate-300 transition hover:text-white">← Back to College Website</a>
  </div>
</div>

</body>
</html>
