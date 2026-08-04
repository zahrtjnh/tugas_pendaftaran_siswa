<?php

include("config.php");

// kalau tidak ada id di query string
if( !isset($_GET['id']) ){
    header('Location: list-siswa.php');
    exit;
}

// ambil id dari query string
$id = $_GET['id'];

// buat query untuk ambil data dari database
$sql = "SELECT * FROM calon_siswa WHERE id=$id";
$query = mysqli_query($db, $sql);
$siswa = mysqli_fetch_assoc($query);

// jika data yang di-edit tidak ditemukan
if( mysqli_num_rows($query) < 1 ){
    die("Data siswa tidak ditemukan...");
}

?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa | SMK Krian 1</title>
    
    <!-- 1. Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- 2. Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 3. FontAwesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- 4. Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        pastel: {
                            purple: '#8B5CF6',
                            purpleLight: '#DDD6FE',
                            teal: '#14B8A6',
                            tealLight: '#CCFBF1',
                            rose: '#F43F5E',
                            roseLight: '#FFE4E6',
                            dark: '#1E1B4B',
                            slate: '#475569',
                        }
                    },
                    animation: {
                        'float-slow': 'float 7s ease-in-out infinite',
                        'float-medium': 'float 4.5s ease-in-out infinite',
                        'float-fast': 'float 3s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 5s ease-in-out infinite',
                        'sidebar-pop': 'sidebarPop 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'pop-in': 'popIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg) scale(1)' },
                            '50%': { transform: 'translateY(-22px) rotate(6deg) scale(1.03)' },
                        },
                        glowPulse: {
                            '0%, 100%': { opacity: '0.45', transform: 'scale(1)' },
                            '50%': { opacity: '0.8', transform: 'scale(1.08)' },
                        },
                        sidebarPop: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        popIn: {
                            '0%': { opacity: '0', transform: 'scale(0.96) translateY(20px)' },
                            '100%': { opacity: '1', transform: 'scale(1) translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Glassmorphism Panel Aesthetic */
        .glass-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 
                0 20px 40px -15px rgba(139, 92, 246, 0.12),
                0 10px 20px -10px rgba(20, 184, 166, 0.08);
        }

        /* Animated Floating Sidebar Glass */
        .glass-sidebar-floating {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 
                0 15px 35px -10px rgba(139, 92, 246, 0.15),
                inset 0 1px 2px rgba(255, 255, 255, 0.9);
        }

        /* Interactive Menu Hover Gradient Bar Animation */
        .menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s ease;
        }

        .menu-item:hover::before {
            left: 100%;
        }

        /* Glass Bubbles Style */
        .bubble-container {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.1) 60%, rgba(221, 214, 254, 0.4) 100%);
            box-shadow: 
                inset 0 0 10px rgba(255, 255, 255, 0.8),
                inset -3px -3px 8px rgba(139, 92, 246, 0.2),
                0 8px 20px rgba(139, 92, 246, 0.12);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .bubble::after {
            content: '';
            position: absolute;
            top: 18%;
            left: 20%;
            width: 25%;
            height: 25%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            filter: blur(0.5px);
        }

        /* Smooth Exit Animation */
        .page-exit {
            opacity: 0 !important;
            transform: scale(0.96) translateY(-12px) !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
    </style>
</head>

<body class="bg-[#F5F3FF] font-sans antialiased text-pastel-dark min-h-screen flex flex-col md:flex-row relative overflow-x-hidden p-0 sm:p-4 md:p-6 gap-0 sm:gap-6">

    <!-- Soft Ambient Glow Backgrounds -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-28 -left-20 w-[600px] h-[600px] bg-[#DDD6FE]/70 rounded-full filter blur-[110px] animate-glow-pulse"></div>
        <div class="absolute top-1/2 -right-20 w-[500px] h-[500px] bg-[#CCFBF1]/80 rounded-full filter blur-[120px] animate-glow-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-28 left-1/3 w-[650px] h-[650px] bg-[#FFE4E6]/70 rounded-full filter blur-[130px] animate-glow-pulse" style="animation-delay: 3.5s;"></div>
    </div>

    <!-- Animated Glass Bubbles Floating -->
    <div class="bubble-container">
        <div class="bubble w-20 h-20 top-[8%] left-[22%] animate-float-slow"></div>
        <div class="bubble w-12 h-12 top-[30%] left-[10%] animate-float-medium" style="animation-delay: 1s;"></div>
        <div class="bubble w-24 h-24 top-[12%] right-[8%] animate-float-slow" style="animation-delay: 1.8s;"></div>
        <div class="bubble w-14 h-14 top-[62%] right-[14%] animate-float-medium" style="animation-delay: 0.5s;"></div>
        <div class="bubble w-16 h-16 bottom-[8%] left-[38%] animate-float-fast" style="animation-delay: 2.2s;"></div>
    </div>

    <!-- Mobile Header Bar (Khusus Layar HP) -->
    <div class="md:hidden flex items-center justify-between p-4 bg-white/80 backdrop-blur-md border-b border-purple-100 z-30 sticky top-0 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-pastel-purple to-indigo-500 flex items-center justify-center text-white shadow-md shadow-purple-300 font-bold">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="font-outfit font-extrabold text-lg text-slate-800 tracking-tight">SMK Krian 1</span>
        </div>
        <button id="mobileMenuBtn" class="w-10 h-10 rounded-2xl bg-purple-50 text-pastel-purple hover:bg-purple-100 flex items-center justify-center transition-all">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Sidebar Backdrop Overlay (HP Only) -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- SIDEBAR FLOATING ANIMATED (Kiri) -->
    <aside id="sidebar" class="fixed md:sticky top-0 md:top-6 left-0 h-screen md:h-[calc(100vh-3rem)] w-72 glass-sidebar-floating md:rounded-[2.5rem] z-50 flex flex-col justify-between p-6 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shrink-0 animate-sidebar-pop">
        <div>
            <!-- Brand Logo Header -->
            <div class="flex items-center gap-3.5 px-2 py-3 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-pastel-purple via-indigo-500 to-teal-400 p-0.5 shadow-lg shadow-purple-500/25 transition-transform hover:scale-105 duration-300">
                    <div class="w-full h-full bg-white rounded-[0.9rem] flex items-center justify-center text-pastel-purple">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="font-outfit font-extrabold text-xl text-slate-900 tracking-tight leading-none">SMK Krian 1</h2>
                    <span class="text-xs font-bold text-pastel-teal mt-1.5 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-teal-50 border border-teal-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span> PPDB 2026
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-2">
                <p class="text-[11px] font-extrabold text-purple-400 uppercase tracking-widest px-3 mb-3">Menu Utama</p>
                
                <!-- Link Beranda -->
                <a href="index.php" onclick="navigasiDenganAnimasi(event, this.href)" 
                   class="menu-item flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-600 hover:bg-white hover:text-pastel-purple font-semibold text-sm transition-all group hover:shadow-md hover:shadow-purple-500/5 hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center text-pastel-purple transition-colors">
                        <i class="fa-solid fa-house text-sm"></i>
                    </div>
                    <span>Beranda</span>
                </a>

                <!-- Link Form Pendaftaran -->
                <a href="form-daftar.php" onclick="navigasiDenganAnimasi(event, this.href)"
                   class="menu-item flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-600 hover:bg-white hover:text-pastel-purple font-semibold text-sm transition-all group hover:shadow-md hover:shadow-purple-500/5 hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center text-pastel-purple transition-colors">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <span>Daftar Siswa Baru</span>
                </a>

                <!-- Link Data Pendaftar (Active context) -->
                <a href="list-siswa.php" onclick="navigasiDenganAnimasi(event, this.href)" 
                   class="menu-item flex items-center justify-between px-4 py-3.5 rounded-2xl bg-gradient-to-r from-pastel-purple to-indigo-600 text-white font-bold text-sm shadow-lg shadow-purple-500/30 transition-all hover:scale-[1.02]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white backdrop-blur-md">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </div>
                        <span>Edit Data Siswa</span>
                    </div>
                    <span class="text-[10px] bg-white/20 text-white font-bold px-2.5 py-1 rounded-full backdrop-blur-md">Mode Edit</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Floating Footer Card -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-500/10 via-teal-500/5 to-rose-500/10 border border-white/80 text-center relative overflow-hidden backdrop-blur-sm">
            <div class="relative z-10">
                <div class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-pastel-teal mx-auto mb-2 text-lg">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h6 class="font-bold text-xs text-slate-800 mb-0">Butuh Bantuan?</h6>
                <p class="text-[11px] text-slate-500 mt-1 leading-snug">Tim CS kami siap membantu proses pendaftaran Anda.</p>
                <a href="https://wa.me/6285648624430" target="_blank" class="mt-3 inline-flex items-center justify-center gap-2 text-xs font-bold text-white bg-gradient-to-r from-pastel-teal to-teal-600 hover:from-teal-600 hover:to-teal-700 px-3 py-2.5 rounded-xl transition-all shadow-md shadow-teal-500/20 w-full no-underline hover:scale-[1.02]">
                    <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp CS
                </a>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA (Kanan) -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 flex flex-col justify-between z-10">
        
        <div id="mainContent" class="max-w-2xl mx-auto w-full my-auto animate-pop-in">
            
            <!-- Main Form Card -->
            <div class="glass-card rounded-[2.5rem] p-6 sm:p-10 relative overflow-hidden my-4 sm:my-0">
                
                <!-- Ambient Glow inside card -->
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-gradient-to-br from-purple-200/50 to-teal-200/50 rounded-full filter blur-xl pointer-events-none"></div>

                <!-- Header Section -->
                <header class="mb-8 relative z-10">
                    <a href="list-siswa.php" onclick="navigasiDenganAnimasi(event, this.href)" 
                       class="inline-flex items-center gap-2 text-xs font-bold text-pastel-purple hover:text-indigo-800 bg-purple-50 hover:bg-purple-100 px-3.5 py-1.5 rounded-full transition-all duration-200 mb-4 border border-purple-100 shadow-sm w-fit group no-underline">
                        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Data Pendaftar
                    </a>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-outfit tracking-tight">
                                Edit Data Siswa
                            </h1>
                            <p class="text-slate-500 text-xs sm:text-sm mt-1.5 font-medium leading-relaxed">
                                Perbarui informasi pendaftaran siswa secara akurat.
                            </p>
                        </div>
                        <span class="text-xs font-bold text-pastel-purple bg-purple-100/70 border border-purple-200 px-3 py-1.5 rounded-xl shadow-sm">
                            ID: #<?php echo $siswa['id']; ?>
                        </span>
                    </div>
                </header>

                <!-- Form Section -->
                <form action="proses-edit.php" method="POST" class="space-y-5 relative z-10">
                    
                    <!-- Hidden ID -->
                    <input type="hidden" name="id" value="<?php echo $siswa['id'] ?>" />

                    <!-- Input Nama -->
                    <div>
                        <label for="nama" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            <i class="fa-regular fa-user text-pastel-purple mr-1"></i> Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="nama"
                            name="nama" 
                            placeholder="Masukkan nama lengkap siswa" 
                            value="<?php echo $siswa['nama'] ?>"
                            required
                            class="w-full px-4 py-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-pastel-purple focus:ring-4 focus:ring-purple-500/15 transition-all duration-200 shadow-sm"
                        />
                    </div>

                    <!-- Input Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-map-pin text-pastel-purple mr-1"></i> Alamat Lengkap
                        </label>
                        <textarea 
                            id="alamat"
                            name="alamat" 
                            rows="3" 
                            placeholder="Tuliskan alamat tempat tinggal lengkap"
                            required
                            class="w-full px-4 py-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-pastel-purple focus:ring-4 focus:ring-purple-500/15 transition-all duration-200 resize-none shadow-sm"
                        ><?php echo $siswa['alamat'] ?></textarea>
                    </div>

                    <!-- Input Jenis Kelamin -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-venus-mars text-pastel-purple mr-1"></i> Jenis Kelamin
                        </label>
                        <?php $jk = $siswa['jenis_kelamin']; ?>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 p-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-700 text-sm font-semibold cursor-pointer hover:bg-purple-50/50 transition-all duration-200 has-[:checked]:bg-gradient-to-r has-[:checked]:from-pastel-purple has-[:checked]:to-indigo-600 has-[:checked]:border-transparent has-[:checked]:text-white shadow-sm">
                                <input type="radio" name="jenis_kelamin" value="laki-laki" <?php echo ($jk == 'laki-laki') ? "checked": "" ?> class="accent-pastel-purple w-4 h-4 hidden" required>
                                <i class="fa-solid fa-mars"></i> Laki-laki
                            </label>
                            <label class="flex items-center justify-center gap-2 p-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-700 text-sm font-semibold cursor-pointer hover:bg-purple-50/50 transition-all duration-200 has-[:checked]:bg-gradient-to-r has-[:checked]:from-pastel-purple has-[:checked]:to-indigo-600 has-[:checked]:border-transparent has-[:checked]:text-white shadow-sm">
                                <input type="radio" name="jenis_kelamin" value="perempuan" <?php echo ($jk == 'perempuan') ? "checked": "" ?> class="accent-pastel-purple w-4 h-4 hidden" required>
                                <i class="fa-solid fa-venus"></i> Perempuan
                            </label>
                        </div>
                    </div>

                    <!-- Input Agama -->
                    <div>
                        <label for="agama" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-hands-praying text-pastel-purple mr-1"></i> Agama
                        </label>
                        <?php $agama = $siswa['agama']; ?>
                        <div class="relative">
                            <select 
                                id="agama"
                                name="agama"
                                class="w-full px-4 py-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-800 text-sm focus:outline-none focus:border-pastel-purple focus:ring-4 focus:ring-purple-500/15 transition-all duration-200 appearance-none cursor-pointer shadow-sm"
                            >
                                <option <?php echo ($agama == 'Islam') ? "selected": "" ?>>Islam</option>
                                <option <?php echo ($agama == 'Kristen') ? "selected": "" ?>>Kristen</option>
                                <option <?php echo ($agama == 'Katolik') ? "selected": "" ?>>Katolik</option>
                                <option <?php echo ($agama == 'Hindu') ? "selected": "" ?>>Hindu</option>
                                <option <?php echo ($agama == 'Buddha' || $agama == 'Budha') ? "selected": "" ?>>Buddha</option>
                                <option <?php echo ($agama == 'Khonghucu') ? "selected": "" ?>>Khonghucu</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Input Sekolah Asal -->
                    <div>
                        <label for="sekolah_asal" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-school text-pastel-purple mr-1"></i> Sekolah Asal
                        </label>
                        <input 
                            type="text" 
                            id="sekolah_asal"
                            name="sekolah_asal" 
                            placeholder="Nama SMP / MTs asal" 
                            value="<?php echo $siswa['sekolah_asal'] ?>"
                            required
                            class="w-full px-4 py-3.5 rounded-2xl bg-white/80 border border-purple-100 text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:border-pastel-purple focus:ring-4 focus:ring-purple-500/15 transition-all duration-200 shadow-sm"
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex items-center gap-3">
                        <a href="list-siswa.php" onclick="navigasiDenganAnimasi(event, this.href)" class="w-1/3 py-4 px-4 rounded-2xl bg-purple-50 hover:bg-purple-100 text-pastel-purple font-bold text-sm text-center transition-all duration-200 no-underline border border-purple-100">
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            name="simpan" 
                            value="Simpan"
                            class="group w-2/3 py-4 px-6 rounded-2xl bg-gradient-to-r from-pastel-purple via-indigo-600 to-pastel-teal hover:from-indigo-600 hover:to-teal-600 text-white font-bold text-sm shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/35 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer border-0"
                        >
                            <span>Simpan Perubahan</span>
                            <i class="fa-solid fa-check group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>

                </form>

            </div>

        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-xs text-slate-400 font-medium">
            <p class="mb-0">&copy; 2026 SMK Krian 1. All rights reserved.</p>
        </footer>

    </main>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Interactive Scripts -->
    <script>
        // 1. Sidebar Toggle Mobile
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                sidebarBackdrop.classList.add('opacity-0');
                setTimeout(() => sidebarBackdrop.classList.add('hidden'), 300);
            } else {
                sidebarBackdrop.classList.remove('hidden');
                setTimeout(() => sidebarBackdrop.classList.remove('opacity-0'), 10);
                sidebar.classList.remove('-translate-x-full');
            }
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', toggleSidebar);

        // 2. Page Navigation Animation (Smooth Exit)
        function navigasiDenganAnimasi(e, destinationUrl) {
            e.preventDefault();
            const content = document.getElementById('mainContent');
            content.classList.add('page-exit');
            
            setTimeout(() => {
                window.location.href = destinationUrl;
            }, 350);
        }
    </script>
</body>
</html>