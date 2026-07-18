<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found — Jinnah Degree College Astore</title>
    <style>
        :root { --brand: #1A3A5F; --brand-dark: #122A45; }
        * { margin: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
               min-height: 100vh; display: flex; align-items: center; justify-content: center;
               background: linear-gradient(135deg, var(--brand), var(--brand-dark)); color: #fff; padding: 24px; }
        .card { text-align: center; max-width: 520px; }
        .code { font-size: clamp(72px, 18vw, 140px); font-weight: 800; line-height: 1; letter-spacing: -2px;
                color: rgba(255,255,255,.95); text-shadow: 0 6px 30px rgba(0,0,0,.35); }
        h1 { font-size: clamp(20px, 5vw, 28px); font-weight: 700; margin: 8px 0 10px; }
        p { color: rgba(255,255,255,.75); font-size: 15px; line-height: 1.6; margin-bottom: 26px; }
        .btns { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
        a.btn { display: inline-block; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px;
                text-decoration: none; transition: opacity .2s, background .2s; }
        .btn-primary { background: #fff; color: var(--brand); }
        .btn-ghost { border: 1px solid rgba(255,255,255,.35); color: #fff; }
        .btn-primary:hover { opacity: .9; }
        .btn-ghost:hover { background: rgba(255,255,255,.12); }
        .mark { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,.5); margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="mark">Jinnah Degree College Astore</div>
        <div class="code">404</div>
        <h1>Page Not Found</h1>
        <p>The page you’re looking for doesn’t exist or may have been moved. Please check the address or head back to the homepage.</p>
        <div class="btns">
            <a href="{{ url('/') }}" class="btn btn-primary">← Back to Home</a>
            <a href="{{ url('/admissions') }}" class="btn btn-ghost">Admissions</a>
        </div>
    </div>
</body>
</html>
