<?php
/**
 * Plugin Name: HLT - Forzar fuente personalizada en formularios AJAX
 * Description: Elimina estilos inline del mensaje de éxito del formulario y aplica la fuente DrukTextWide.
 * Version: 1.0
 * Author: Tu Nombre o Empresa
 */

add_action('wp_footer', 'hlt_forzar_fuente_ajax_form', 100);

function hlt_forzar_fuente_ajax_form() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach(() => {
                const paragraphs = document.querySelectorAll('.ajaxresponse.avia-form-success p');
                paragraphs.forEach((p) => {
                    if (p.hasAttribute('style') || !p.style.fontFamily.includes('DrukTextWide')) {
                        p.removeAttribute('style');
                        p.style.fontFamily = "'DrukTextWide', sans-serif";
                        p.style.fontSize = "20px";
                        p.style.lineHeight = "1.4";
                        p.style.textAlign = "center";
                        p.style.color = "#000";
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    <?php
}
