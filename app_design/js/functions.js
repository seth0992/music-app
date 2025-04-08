$(function() {
    // Animación para los mensajes de alerta
    $('.alert').fadeIn('slow').delay(5000).fadeOut('slow');
    
    // Validación del formulario al enviar
    $('#frm_song').on('submit', function(e) {
        // Verificar que el título no esté vacío
        if ($('#txt_title').val().trim() === '') {
            e.preventDefault();
            showTempAlert('Por favor, ingresa el título de la canción', 'error');
            $('#txt_title').focus();
            return false;
        }
        
        // Verificar que el artista no esté vacío
        if ($('#txt_artist').val().trim() === '') {
            e.preventDefault();
            showTempAlert('Por favor, ingresa el nombre del artista', 'error');
            $('#txt_artist').focus();
            return false;
        }
        
        // Verificar que se haya seleccionado un género
        if ($('#sel_genre').val() === '') {
            e.preventDefault();
            showTempAlert('Por favor, selecciona un género musical', 'error');
            $('#sel_genre').focus();
            return false;
        }
        
        // Verificar que la reseña no esté vacía
        if ($('#txt_review').val().trim() === '') {
            e.preventDefault();
            showTempAlert('Por favor, escribe una reseña de la canción', 'error');
            $('#txt_review').focus();
            return false;
        }
        
        // Verificar que se haya seleccionado una calificación
        if (!$('input[name="rating"]:checked').val()) {
            e.preventDefault();
            showTempAlert('Por favor, selecciona una calificación', 'error');
            return false;
        }
        
        // Deshabilitar el botón después de enviar para evitar doble envío
        $('.button').prop('disabled', true).css('opacity', '0.7');
        
        return true;
    });
    
    // Sistema de calificación con estrellas
    $('.rating-stars label').hover(function() {
        $(this).css('transform', 'scale(1.2)');
    }, function() {
        $(this).css('transform', 'scale(1)');
    });
    
    // Contador de caracteres para la reseña
    $('#txt_review').on('input', function() {
        const maxLength = 500;
        const currentLength = $(this).val().length;
        
        if (!$('#char_counter').length) {
            $('<div id="char_counter" class="char-counter"></div>').insertAfter(this);
        }
        
        const remaining = maxLength - currentLength;
        
        if (remaining <= 50) {
            $('#char_counter').addClass('warning');
        } else {
            $('#char_counter').removeClass('warning');
        }
        
        $('#char_counter').text(`Caracteres restantes: ${remaining}`);
        
        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
        }
    });
    
    // Animación para los bloques de canciones
    $('.song-block').hover(
        function() {
            $(this).find('.song-title').css('color', '#3498db');
        },
        function() {
            $(this).find('.song-title').css('color', '#8e44ad');
        }
    );
    
    // Confirmar eliminación
    $('.delete-form').on('submit', function(e) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta canción?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Mostrar alerta temporal
    function showTempAlert(message, type) {
        const alertClass = `alert alert-${type === 'error' ? 'danger' : type}`;
        const $alert = $(`<div class="${alertClass}">${message}</div>`);
        
        $alert.insertBefore('#form_box').hide().fadeIn('fast');
        
        setTimeout(function() {
            $alert.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 4000);
    }
    
    // Animar entrada del formulario
    $('#form_box').hide().fadeIn(800);
    
    // Activar automáticamente el área de búsqueda con atajo de teclado
    $(document).on('keydown', function(e) {
        // Ctrl + F o Cmd + F
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 70) {
            e.preventDefault();
            $('#txt_search').focus();
        }
    });
    
    // Añadir tooltip para mostrar géneros completos si son muy largos
    $('.song-genre').each(function() {
        if (this.offsetWidth < this.scrollWidth) {
            $(this).attr('title', $(this).text());
        }
    });
    
    // Animar la eliminación de canciones
    $('.delete-button').on('click', function() {
        const songBlock = $(this).closest('.song-block');
        if (confirm('¿Estás seguro de que deseas eliminar esta canción?')) {
            songBlock.fadeOut('slow');
        }
    });
    
    // Desplazamiento suave a la canción recién agregada
    if (window.location.href.indexOf('?added=true') > -1) {
        $('html, body').animate({
            scrollTop: $('#songs_panel').offset().top - 20
        }, 1000);
    }
    
    // Focus automático en el campo de título al cargar la página
    if (!window.location.href.includes('?edit=')) {
        $('#txt_title').focus();
    }
});