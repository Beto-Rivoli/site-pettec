(function ($) {
  'use strict';

  function createAulaRow() {
    return (
      '<div class="pettec-repeater-row" style="display:flex;gap:8px;margin-bottom:8px;">' +
      '<input type="text" name="pettec_aulas_titulo[]" value="" placeholder="Título da aula (ex.: Aula 01 - Introdução)" style="flex:1;">' +
      '<input type="url" name="pettec_aulas_url[]" value="" placeholder="URL do YouTube (vídeo ou playlist)" style="flex:1.5;">' +
      '<button type="button" class="button pettec-remove-row">Remover</button>' +
      '</div>'
    );
  }

  function createMaterialRow() {
    return (
      '<div class="pettec-repeater-row" style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">' +
      '<input type="text" name="pettec_materiais_titulo[]" value="" placeholder="Título (ex.: Lista 01 - Exercícios)" style="flex:1.2;">' +
      '<select name="pettec_materiais_tipo[]" style="flex:0.8;">' +
      '<option value="Lista de Exercícios">Lista de Exercícios</option>' +
      '<option value="Slide">Slide</option>' +
      '<option value="Apostila">Apostila</option>' +
      '<option value="Código">Código</option>' +
      '</select>' +
      '<input type="url" name="pettec_materiais_url[]" value="" placeholder="URL do PDF ou link" class="pettec-media-input" style="flex:1.4;">' +
      '<button type="button" class="button pettec-media-upload">Enviar PDF</button>' +
      '<button type="button" class="button pettec-remove-row">Remover</button>' +
      '</div>'
    );
  }

  $(document).on('click', '.pettec-add-row', function (e) {
    e.preventDefault();
    var $btn = $(this);
    var $rows = $btn.closest('.pettec-repeater').find('.pettec-repeater-rows');
    var tpl = $btn.data('template');
    $rows.append(tpl === 'aula' ? createAulaRow() : createMaterialRow());
  });

  $(document).on('click', '.pettec-remove-row', function (e) {
    e.preventDefault();
    $(this).closest('.pettec-repeater-row').remove();
  });

  $(document).on('click', '.pettec-media-upload', function (e) {
    e.preventDefault();
    var $input = $(this).siblings('.pettec-media-input');
    if (typeof wp === 'undefined' || !wp.media) {
      return;
    }
    var frame = wp.media({
      title: 'Selecionar Arquivo / PDF',
      button: { text: 'Usar este arquivo' },
      multiple: false
    });
    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      if (attachment && attachment.url) {
        $input.val(attachment.url);
      }
    });
    frame.open();
  });

  $(document).on('click', '.pettec-photo-select', function (e) {
    e.preventDefault();
    var $wrap = $(this).closest('.pettec-photo-field');
    if (typeof wp === 'undefined' || !wp.media) {
      return;
    }
    var frame = wp.media({
      title: 'Escolher Foto / Imagem de Capa',
      button: { text: 'Usar esta imagem' },
      library: { type: 'image' },
      multiple: false
    });
    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      if (attachment && attachment.id) {
        var previewUrl =
          (attachment.sizes && attachment.sizes.medium && attachment.sizes.medium.url) ||
          attachment.url;
        $wrap.find('.pettec-photo-id').val(attachment.id);
        $wrap
          .find('.pettec-photo-preview')
          .html('<img src="' + previewUrl + '" alt="" style="width:100%;height:100%;object-fit:cover;">');
      }
    });
    frame.open();
  });

  $(document).on('click', '.pettec-photo-remove', function (e) {
    e.preventDefault();
    var $wrap = $(this).closest('.pettec-photo-field');
    $wrap.find('.pettec-photo-id').val('');
    $wrap.find('.pettec-photo-preview').html('<span>Sem foto</span>');
  });
})(jQuery);
