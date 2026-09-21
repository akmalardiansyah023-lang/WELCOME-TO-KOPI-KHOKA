$(function () {

  var NOMOR_WA = '6282299577380';   
  var USERNAME_IG = 'kopi.khoka';   

  var kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function linkWA(teks) {
    var url = 'https://wa.me/' + NOMOR_WA;
    return teks ? url + '?text=' + encodeURIComponent(teks) : url;
  }

  $('[data-aksi="wa"]').attr('href', linkWA('Halo Kopi Khoka, saya mau pesan.'));
  $('[data-aksi="ig"]').attr('href', 'https://instagram.com/' + USERNAME_IG);
  $('[data-aksi="ig-dm"]').attr('href', 'https://ig.me/m/' + USERNAME_IG);
  $('.nomor-wa').text('0' + NOMOR_WA.slice(2));
  $('.nama-ig').text('@' + USERNAME_IG);

  $('#tahun').text(new Date().getFullYear());

  $('.nav-toggle').on('click', function () {
    var terbuka = $('.nav').toggleClass('open').hasClass('open');
    $(this).attr({
      'aria-expanded': String(terbuka),
      'aria-label': terbuka ? 'Tutup menu' : 'Buka menu'
    });
  });

  var $foto = $('.photo-wrap img');

  function fotoGagal() { $('.photo-wrap').addClass('no-photo'); }

  if ($foto.length) {
    if ($foto[0].complete && $foto[0].naturalWidth === 0) fotoGagal();
    $foto.on('error', fotoGagal);
  }

  $('.menu-foto img').each(function () {
    var $wadah = $(this).parent();
    function fotoMenuGagal() { $wadah.addClass('no-photo'); }

    if (this.complete && this.naturalWidth === 0) fotoMenuGagal();
    $(this).on('error', fotoMenuGagal);
  });

  $('.filter button').on('click', function () {
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

  $('.btn-pesan').on('click', function () {
    var nama = $(this).data('nama');
    var harga = $(this).data('harga') || $.trim($(this).closest('.menu-item').find('.price').text());
    var adaHarga = /\d/.test(harga);  
    var teks = 'Halo Kopi Khoka, saya mau pesan ' + nama + (adaHarga ? ' (' + harga + ').' : '.');
    window.open(linkWA(teks), '_blank', 'noopener');
  });

  function tampilkanError(selector, pesan) {
    $(selector).addClass('invalid').attr('aria-invalid', 'true')
      .siblings('.error').text(pesan);
  }

  function bersihkanError() {
    $('.error').text('');
    $('#form-kontak input, #form-kontak textarea').removeClass('invalid').removeAttr('aria-invalid');
    $('#status').text('');
  }

  $('#form-kontak').on('submit', function (e) {
    e.preventDefault();
    bersihkanError();

    var nama = $.trim($('#nama').val());
    var pesan = $.trim($('#pesan').val());
    var valid = true;

    if (nama.length < 2) {
      tampilkanError('#nama', 'Tulis nama kamu dulu.');
      valid = false;
    }
    if (pesan.length < 5) {
      tampilkanError('#pesan', 'Pesannya terlalu singkat. Tulis minimal 5 huruf.');
      valid = false;
    }

    if (!valid) {
      $('.invalid').first().trigger('focus');
      return;
    }

    window.open(linkWA('Halo Kopi Khoka, saya ' + nama + '. ' + pesan), '_blank', 'noopener');

    this.reset();
    $('#status').text('Terima kasih, ' + nama + '! WhatsApp akan terbuka untuk mengirim pesanmu.');
  });

  $('#form-kontak input, #form-kontak textarea').on('input', function () {
    $(this).removeClass('invalid').removeAttr('aria-invalid').siblings('.error').text('');
  });

});
