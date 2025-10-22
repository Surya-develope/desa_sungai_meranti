<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Desa Sungai Meranti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #00c851);
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        .hero {
            text-align: center;
            color: white;
            padding: 100px 20px;
        }
        .hero h1 {
            font-weight: 700;
            font-size: 3rem;
        }
        .icon-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 20px;
        }
        .icon-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .icon-card i {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Selamat Datang di Website Desa Sungai Meranti</h1>
        <p>Melayani masyarakat dengan cepat, transparan, dan modern.</p>
    </div>

    <div class="container my-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card icon-card p-4">
                    <i class="bi bi-file-earmark-text"></i>
                    <h4 class="mt-3">Pembuatan Surat</h4>
                    <p>Buat berbagai surat administrasi desa secara online dengan mudah.</p>
                    <a href="#" class="btn btn-primary">Masuk</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card icon-card p-4">
                    <i class="bi bi-people-fill"></i>
                    <h4 class="mt-3">Data Penduduk</h4>
                    <p>Lihat dan kelola data penduduk desa secara real-time.</p>
                    <a href="#" class="btn btn-success">Lihat Data</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card icon-card p-4">
                    <i class="bi bi-house-door-fill"></i>
                    <h4 class="mt-3">Profil Desa</h4>
                    <p>Jelajahi profil, sejarah, dan potensi Desa Sungai Meranti.</p>
                    <a href="#" class="btn btn-info text-white">Kunjungi</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-white py-3" style="background: #333;">
        <small>© 2025 Desa Sungai Meranti | Dibuat dengan 💚 Laravel</small>
    </footer>
</body>
</html>
