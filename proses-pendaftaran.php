<?php

include("config.php");

// Cek apakah tombol daftar sudah diklik atau belum?
if(isset($_POST['daftar'])){

    // Ambil data dari formulir
    $nama    = $_POST['nama'];
    $alamat  = $_POST['alamat'];
    $jk      = $_POST['jenis_kelamin'];
    $agama   = $_POST['agama'];
    $sekolah = $_POST['sekolah_asal'];

    // Buat query insert data
    $sql   = "INSERT INTO calon_siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES ('$nama', '$alamat', '$jk', '$agama', '$sekolah')";
    $query = mysqli_query($db, $sql);

    // Apakah query simpan berhasil?
    if( $query ) {
        // Kalau berhasil alihkan ke halaman index.php dengan status=sukses
        header('Location: index.php?status=sukses');
        exit();
    } else {
        // Kalau gagal alihkan ke halaman index.php dengan status=gagal
        header('Location: index.php?status=gagal');
        exit();
    }

} else {
    // Tampilan pesan jika akses dilarang (diakses langsung tanpa melalui formulir)
    showErrorPage("Akses Dilarang", "Anda tidak memiliki akses langsung ke halaman pemrosesan ini.", "form-daftar.php");
}

// Fungsi Helper untuk Menampilkan Halaman Error/Akses Dilarang dengan Desain Pastel Soft
function showErrorPage($title, $message, $backUrl) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | SMK Coding</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px) scale(0.95)' },
                            '100%': { opacity: '1', transform: 'translateY(0) scale(1)' },
                        }
                    },
                    animation: {
                        blob: 'blob 7s infinite ease-in-out',
                        'blob-delay': 'blob 7s infinite ease-in-out 3.5s',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-700 relative overflow-hidden font-sans">

    <!-- Background Blobs (Soft Pastel Spheres) -->
    <div class="fixed top-1/4 -left-12 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob -z-10"></div>
    <div class="fixed top-1/3 -right-12 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob-delay -z-10"></div>
    <div class="fixed -bottom-8 left-1/3 w-72 h-72 bg-sky-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob -z-10"></div>

    <!-- Main Card Container -->
    <main class="relative w-full max-w-md bg-white/75 backdrop-blur-xl p-8 rounded-3xl shadow-2xl shadow-purple-100/60 border border-white/80 text-center opacity-0 animate-fade-in-up">
        
        <!-- Icon Warning / Alert -->
        <div class="w-16 h-16 bg-rose-100/80 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-2"><?php echo $title; ?></h1>
        <p class="text-slate-500 text-sm mb-6"><?php echo $message; ?></p>

        <!-- Back Button -->
        <a href="<?php echo $backUrl; ?>" class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-purple-500 via-pink-500 to-rose-400 text-white font-bold text-sm shadow-lg shadow-pink-200/50 hover:shadow-xl hover:shadow-pink-300/60 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
            <span>← Ke Formulir Pendaftaran</span>
        </a>

    </main>

</body>
</html>
<?php
    exit();
}
?>