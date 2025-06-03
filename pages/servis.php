<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tombol Kembali dengan Background Lokal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      background-image: ('..uploads/download (21).jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .btn-back {
      padding: 16px 36px;
      font-size: 1.25rem;
      font-weight: 600;
  
      color: #db2777;
      border: none;
      border-radius: 32px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.10);
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
    }
    .btn-back:hover {
      background: #db2777;
      color: #fff;
    }
  </style>
</head>
<body>
  <button class="btn-back" onclick="window.history.back()">Kembali</button>
</body>
</html>
