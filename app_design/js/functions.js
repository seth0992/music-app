$(function() {
    //alert('Hola');
    // Animación para los mensajes de alerta
    $('.alert').fadeIn('slow').delay(5000).fadeOut('slow');

        // Validación del formulario al enviar
        $('#frm_song').on('submit', function(e) {
            let isValid = true;
            
            // Verificar que el título no esté vacío
            if ($('#txt_title').val().trim() === '') {
                e.preventDefault();
                showTempAlert('Por favor, ingresa el título de la canción', 'error');
                $('#txt_title').focus();
                isValid = false;
            }
            
            // Verificar que el artista no esté vacío
            else if ($('#txt_artist').val().trim() === '') {
                e.preventDefault();
                showTempAlert('Por favor, ingresa el nombre del artista', 'error');
                $('#txt_artist').focus();
                isValid = false;
            }
            
            // Verificar que se haya seleccionado un género
            else if ($('#sel_genre').val() === '') {
                e.preventDefault();
                showTempAlert('Por favor, selecciona un género musical', 'error');
                $('#sel_genre').focus();
                isValid = false;
            }
            
            // Verificar que la reseña no esté vacía
            else if ($('#txt_review').val().trim() === '') {
                e.preventDefault();
                showTempAlert('Por favor, escribe una reseña de la canción', 'error');
                $('#txt_review').focus();
                isValid = false;
            }
            
            // Verificar que se haya seleccionado una calificación
            else if (!$('input[name="rating"]:checked').val()) {
                e.preventDefault();
                showTempAlert('Por favor, selecciona una calificación', 'error');
                isValid = false;
            }
            
            // Solo deshabilitar el botón si todos los campos son válidos
            if (isValid) {
                // Usar un setTimeout para permitir que el formulario se envíe primero
                setTimeout(function() {
                    $(e.target).find('button[type="submit"]').prop('disabled', true).css('opacity', '0.7');
                }, 10);
            }
            
            return isValid;
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
    
        // Sistema de calificación con estrellas
        $('.rating-stars label').hover(function() {
            $(this).css('transform', 'scale(1.2)');
        }, function() {
            $(this).css('transform', 'scale(1)');
        });
        
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

});