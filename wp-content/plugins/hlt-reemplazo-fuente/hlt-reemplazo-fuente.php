<?php
/**
 * Plugin Name: HLT - Reemplazo de fuente en formularios AJAX
 * Description: Reemplaza el contenido del mensaje de éxito del formulario y aplica la fuente DrukTextWide.
 * Version: 1.1
 * Author: Tu Nombre o Empresa
 */

add_action('wp_footer', 'hlt_reemplazar_html_formulario_exito', 100);
function hlt_reemplazar_html_formulario_exito() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const observer = new MutationObserver(() => {
            const container = document.querySelector('.ajaxresponse.avia-form-success');
            if (container && container.innerHTML.includes('Tahoma')) {
                container.innerHTML = `
                    <p style="font-family: 'DrukTextWide', sans-serif; font-size: 20px; line-height: 1.4; text-align: center; color: #000;">
                        Registro completado. Pronto recibirás tu código de descuento.<br>
                        ✨ Bienvenidx al ritual Amuletum 🧿
                    </p>
                `;
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    <?php
}
