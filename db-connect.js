/*
 * db-connect.js (FILE BARU)
 * Menyambungkan halaman Kopi Khoka ke database lewat PHP tanpa mengubah
 * index.html, menu.html, kontak.html, script.js, dan style.css.
 * File ini dimuat otomatis oleh .htaccess (lihat catatan di sana).
 */
(function ($) {
  'use strict';
  if (!$) return;

  var API = 'php/api/';

  // Simpan pesan form kontak ke database.
  // Pakai fase "capture" supaya jalan SEBELUM script.js mengosongkan form.
  document.addEventListener('submit', function (e) {
    if (!e.target || e.target.id !== 'form-kontak') return;
    var nama = $.trim($('#nama').val());
    var pesan = $.trim($('#pesan').val());
    if (nama.length >= 2 && pesan.length >= 5) {
      $.post(API + 'simpan_pesan.php', { nama: nama, pesan: pesan });
    }
  }, true);

  $(function () {
    if (!$('.menu-list').length) return;

    var kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var penanganTerpasang = false;

    // Nomor WA diambil dari tautan yang sudah diatur script.js
    function linkWA(teks) {
      var dasar = ($('[data-aksi="wa"]').first().attr('href') || 'https://wa.me/').split('?')[0];
      return teks ? dasar + '?text=' + encodeURIComponent(teks) : dasar;
    }

    function formatRupiah(angka) {
      return String(angka).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function pasangFoto() {
      $('.menu-foto img').each(function () {
        var $wadah = $(this).parent();
        function gagal() { $wadah.addClass('no-photo'); }
        if (this.complete && this.naturalWidth === 0) gagal();
        $(this).on('error', gagal);
      });
    }

    // Penangan klik untuk elemen hasil database (yang statis sudah diganti semua)
    function pasangPenangan() {
      if (penanganTerpasang) return;
      penanganTerpasang = true;

      $(document).on('click', '.filter button', function () {
        var kategori = $(this).data('filter');
        var $semua = $('.menu-item');
        var durasi = kurangiGerak ? 0 : 200;

        $('.filter button').removeClass('active').attr('aria-pressed', 'false');
        $(this).addClass('active').attr('aria-pressed', 'true');

        if (kategori === 'semua') {
          $semua.stop(true, true).fadeIn(durasi);
        } else {
          $semua.filter('[data-cat="' + kategori + '"]').stop(true, true).fadeIn(durasi);
          $semua.not('[data-cat="' + kategori + '"]').stop(true, true).hide();
        }
      });

      $(document).on('click', '.btn-pesan', function () {
        var nama = $(this).data('nama');
        var harga = $(this).data('harga') || $.trim($(this).closest('.menu-item').find('.price').text());
        var adaHarga = /\d/.test(harga);
        var teks = 'Halo Kopi Khoka, saya mau pesan ' + nama + (adaHarga ? ' (' + harga + ').' : '.');
        window.open(linkWA(teks), '_blank', 'noopener');
      });
    }

    function buatMenu(data) {
      var $filter = $('.filter').empty();
      $('<button type="button" class="active" data-filter="semua" aria-pressed="true"></button>')
        .text('Semua').appendTo($filter);
      $.each(data.kategori, function (_, k) {
        $('<button type="button" aria-pressed="false"></button>')
          .attr('data-filter', k.slug).text(k.nama).appendTo($filter);
      });

      var $daftar = $('.menu-list').empty();
      $.each(data.menu, function (_, m) {
        var $foto = $('<figure class="menu-foto"></figure>');
        if (m.foto) {
          $foto.append($('<img width="160" height="160">').attr({ src: m.foto, alt: m.nama }));
        } else {
          $foto.addClass('no-photo');
        }

        var $baris = $('<div class="row"></div>').append(
          $('<h2></h2>').text(m.nama),
          '<span class="dots"></span>',
          $('<span class="price price-tanya"></span>').text(m.harga > 0 ? formatRupiah(m.harga) : 'Tanya harga')
        );

        var $isi = $('<div class="menu-isi"></div>').append(
          $baris,
          $('<p></p>').text(m.deskripsi),
          $('<button type="button" class="btn-pesan">Pesan lewat WA</button>')
            .attr({ 'data-nama': m.nama, 'aria-label': 'Pesan ' + m.nama + ' lewat WhatsApp' })
        );

        $('<li class="menu-item"></li>').attr('data-cat', m.kategori).append($foto, $isi).appendTo($daftar);
      });

      pasangFoto();
      pasangPenangan();
    }

    // Kalau gagal (database/PHP mati), menu statis di menu.html tetap tampil.
    $.getJSON(API + 'menu.php').done(function (data) {
      if (data && $.isArray(data.menu) && data.menu.length) buatMenu(data);
    });
  });
})(window.jQuery);
