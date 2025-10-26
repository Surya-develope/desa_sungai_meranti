@extends('layouts.app')

@section('content')

<!-- Load Tailwind CSS (as it's often external in blade templates) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- CUSTOM STYLES for Icon and Card Hover Effect -->
<style>
    /* Styling untuk membuat ikon dokumen terlihat menarik */
    .document-icon {
        color: #10b981; /* Green-500 */
        font-size: 3rem;
        transition: transform 0.3s ease;
    }
    
    /* Efek hover pada card */
    .service-card:hover .document-icon {
        transform: scale(1.1);
    }
    .service-card:hover {
        border-color: #f59e0b; /* Yellow-500 */
    }
    
    /* Custom Icon - Using inline SVG for a clean 'W' Word document look */
    .doc-svg {
        width: 48px;
        height: 48px;
        display: inline-block;
        fill: currentColor;
    }

    /* Style untuk Vertical Timeline */
    .timeline-item {
        position: relative;
        padding-left: 2.5rem; /* Space for the circle/icon */
        border-left: 2px solid #a7f3d0; /* Border line (Light Green-200) */
    }
    .timeline-item:last-child {
        border-left: none; /* Hilangkan garis di item terakhir */
    }
    .timeline-circle {
        position: absolute;
        left: -0.75rem; /* Setengah dari diameter lingkaran agar menempel di garis */
        top: 0;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 9999px;
        background-color: #059669; /* Green-600 */
        border: 4px solid #ffffff; /* White border */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        box-shadow: 0 0 0 2px #34d399; /* Green-400 outer ring */
    }
</style>

<!-- 1. HEADER HALAMAN -->
<div class="bg-green-800 pt-16 pb-24 shadow-xl">
    <div class="container mx-auto px-6 text-center">
        <div class="bg-white/10 backdrop-blur-sm p-6 rounded-2xl inline-block">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-2 tracking-wider">
                Layanan Administrasi Surat
            </h1>
            <p class="text-xl text-yellow-300 font-medium">
                Pilih jenis surat yang Anda butuhkan untuk mengajukan permohonan secara online.
            </p>
        </div>
    </div>
</div>

<!-- 1.5 PETUNJUK PENGAJUAN SURAT ONLINE -->
<section class="py-16 -mt-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-green-800 mb-12 border-b-4 border-yellow-400 inline-block px-4 pb-2 mx-auto">
            Tata Cara Pengajuan Surat Online
        </h2>

        <!-- Vertical Timeline for Steps -->
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Step 1 -->
            <div class="timeline-item pb-10">
                <div class="timeline-circle">1</div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 ml-4">
                    <h3 class="text-xl font-bold text-green-700 mb-2">Pilih Jenis Surat</h3>
                    <p class="text-gray-600">Pilih salah satu jenis surat yang tersedia di daftar di bawah ini. Pastikan Anda memilih jenis surat yang benar sesuai kebutuhan administrasi Anda.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="timeline-item pb-10">
                <div class="timeline-circle">2</div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 ml-4">
                    <h3 class="text-xl font-bold text-green-700 mb-2">Isi Formulir Permohonan</h3>
                    <p class="text-gray-600">Lengkapi data diri dan semua kolom yang diperlukan dalam formulir digital. Pastikan data yang dimasukkan akurat dan sesuai dengan Kartu Keluarga (KK) dan KTP Anda.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="timeline-item pb-10">
                <div class="timeline-circle">3</div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 ml-4">
                    <h3 class="text-xl font-bold text-green-700 mb-2">Verifikasi dan Proses</h3>
                    <p class="text-gray-600">Setelah diajukan, permohonan Anda akan diverifikasi oleh petugas desa. Anda akan menerima notifikasi status permohonan melalui email atau sistem pelacakan.</p>
                </div>
            </div>

            <!-- Step 4 (Terakhir, tanpa garis di bawah) -->
            <div class="timeline-item">
                <div class="timeline-circle">4</div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 ml-4">
                    <h3 class="text-xl font-bold text-green-700 mb-2">Ambil Surat Resmi</h3>
                    <p class="text-gray-600">Jika permohonan disetujui, Anda dapat mengambil surat yang sudah dicetak dan ditandatangani di Kantor Kepala Desa Sungai Meranti pada jam kerja.</p>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- 2. DAFTAR JENIS SURAT (GRID) -->
<section class="py-16 bg-white"> <!-- Diubah dari -mt-12 menjadi py-16 dan bg-white untuk pemisah yang jelas -->
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center text-green-800 mb-10 border-b-4 border-yellow-400 inline-block px-4 pb-2 mx-auto">
            Daftar Jenis Surat
        </h2>
        
        <!-- Search and Filter (Optional, but good for UX) -->
        <div class="mb-10 flex justify-center">
            <input type="text" id="search-input" placeholder="Cari jenis surat..." 
                   class="w-full max-w-lg p-3 border-2 border-gray-300 rounded-lg shadow-md focus:ring-green-600 focus:border-green-600 transition duration-300"
                   onkeyup="filterCards()">
        </div>

        <!-- Surat Grid -->
        <div id="surat-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8">
            
            <!-- Data Dummy Surat (Berdasarkan gambar yang Anda berikan) -->
            @php
                $surat_list = [
                    'SURAT VERIFIKASI TANAH',
                    'SURAT PENGANTAR PEMBUTAN SKCK',
                    'SURAT KETERANGAN TIDAK MAMPU',
                    'SURAT KETERANGAN TANGGUNGAN ORANG TUA',
                    'SURAT KETERANGAN PINDAH ANTAR DESA',
                    'SURAT KETERANGAN PENGHASILAN ORANG TUA',
                    'SURAT KETERANGAN MANDAH',
                    'SURAT KETERANGAN KEPEMILIKAN TANAH',
                    'SURAT KETERANGAN BEDA NAMA (SALAH NAMA)',
                    'SURAT JALAN',
                    'SURAT DOMISILI',
                    'SURAT DOMISILI KELOMPOK',
                    'SURAT DOMISILI RUMAH IBADA',
                    'KEMATIAN',
                    'IJIN USAHA',
                    'AHLI WARIS',
                    'SURAT KETERANGAN BELUM MENIKAH',
                    'SURAT PENGANTAR NIKAH'
                ];
            @endphp

            @foreach ($surat_list as $surat)
            <a href="/warga/pengajuan/{{ Str::slug($surat) }}" 
               class="service-card bg-white p-6 md:p-8 rounded-2xl shadow-xl border-4 border-white transform hover:scale-[1.03] transition-all duration-300 ease-in-out flex flex-col items-center justify-center text-center group">
                
                <!-- Icon (Menggunakan SVG untuk simbol Dokumen "W") -->
                <div class="text-green-600 mb-3 document-icon">
                    <svg class="doc-svg mx-auto" viewBox="0 0 24 24">
                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2M18 20H6V4H13V9H18V20M12 11.5L14.5 16H13L11.5 13.5L10 16H8.5L11 11.5L8.5 7H10L11.5 9.5L13 7H14.5L12 11.5Z" />
                    </svg>
                </div>
                
                <!-- Nama Surat -->
                <p class="text-base font-bold text-gray-800 mt-2 leading-tight group-hover:text-green-700">
                    {{ $surat }}
                </p>
                <!-- Keterangan Tambahan -->
                <p class="text-xs text-gray-500 mt-1">
                    Ajukan Sekarang
                </p>
            </a>
            @endforeach

        </div>
        
        <!-- Not Found Message -->
        <div id="not-found" class="hidden text-center mt-12">
             <p class="text-xl text-gray-600">Tidak ada jenis surat yang cocok dengan pencarian Anda.</p>
        </div>

    </div>
</section>

<!-- Script Pencarian -->
<script>
    function filterCards() {
        const input = document.getElementById('search-input');
        const filter = input.value.toUpperCase();
        const grid = document.getElementById('surat-grid');
        const cards = grid.getElementsByClassName('service-card');
        let visibleCount = 0;

        for (let i = 0; i < cards.length; i++) {
            const card = cards[i];
            // Mengambil elemen paragraf kedua (nama surat)
            const textElement = card.querySelector('p:nth-child(2)'); 
            
            if (textElement) {
                const txtValue = textElement.textContent || textElement.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = "";
                    visibleCount++;
                } else {
                    card.style.display = "none";
                }
            }
        }
        
        // Tampilkan pesan jika tidak ada hasil
        const notFound = document.getElementById('not-found');
        if (visibleCount === 0) {
            notFound.classList.remove('hidden');
        } else {
            notFound.classList.add('hidden');
        }
    }
</script>

@endsection
