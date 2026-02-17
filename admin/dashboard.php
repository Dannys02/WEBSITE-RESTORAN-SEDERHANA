<?php
if (!defined('AKSES_AMAN')) {
  die('Akses langsung tidak diizinkan!');
}
?>

<header class="flex justify-between items-center mb-8">
  <h1 class="text-2xl font-bold text-gray-800">Dashboard Toko</h1>
  <div class="flex items-center justify-end">
    <span class="text-sm text-gray-500">Selasa, 17 Feb 2026</span>
  </div>
</header>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Order Pending
    </p>
    <h3 class="text-2xl font-bold text-gray-800">12</h3>
    <span class="text-xs text-orange-primary mt-2 inline-block font-semibold underline cursor-pointer">Cek Sekarang →</span>
  </div>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Total Produk
    </p>
    <h3 class="text-2xl font-bold text-gray-800">148</h3>
    <span class="text-xs text-gray-400 mt-2 inline-block">5 Stok Menipis</span>
  </div>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <p class="text-gray-500 text-sm">
      Testimoni Baru
    </p>
    <h3 class="text-2xl font-bold text-gray-800">7</h3>
    <span class="text-xs text-green-500 mt-2 inline-block">Perlu Review</span>
  </div>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-orange-primary">
    <p class="text-gray-500 text-sm">
      Pendapatan (Feb)
    </p>
    <h3 class="text-2xl font-bold text-orange-primary">Rp 4.250.000</h3>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="font-bold text-gray-800">Testimoni Baru</h2>
      <button class="text-xs text-orange-primary font-bold">Kelola</button>
    </div>
    <div class="space-y-4">
      <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
        <div class="text-orange-primary mt-1">
          <i class="fa-solid fa-quote-left"></i>
        </div>
        <div>
          <p class="text-xs text-gray-700 italic">
            "Barangnya bagus banget, sampai tepat waktu!"
          </p>
          <p class="text-[10px] text-gray-500 mt-1 font-bold">
            - Rian Pratama (Bintang 5)
          </p>
          <div class="mt-2 space-x-2">
            <button class="text-[10px] text-green-600 font-bold hover:underline">Terbitkan</button>
            <button class="text-[10px] text-red-500 font-bold hover:underline">Hapus</button>
          </div>
        </div>
      </div>
      <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
        <div class="text-orange-primary mt-1">
          <i class="fa-solid fa-quote-left"></i>
        </div>
        <div>
          <p class="text-xs text-gray-700 italic">
            "Respon adminnya agak lambat ya..."
          </p>
          <p class="text-[10px] text-gray-500 mt-1 font-bold">
            - Maya (Bintang 3)
          </p>
          <div class="mt-2 space-x-2">
            <button class="text-[10px] text-green-600 font-bold hover:underline">Terbitkan</button>
            <button class="text-[10px] text-red-500 font-bold hover:underline">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>