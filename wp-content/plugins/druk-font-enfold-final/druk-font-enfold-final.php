<?php
/*
Plugin Name: Druk Text Wide Font (Final Enfold Compatible)
Description: Agrega la fuente personalizada 'Druk Text Wide' al selector de fuentes del tema Enfold y la carga con CSS.
Version: 1.3
Author: ChatGPT
*/

// Usar filtro compatible con Enfold
add_filter('avf_google_websafe_fonts', function($fonts) {
    $fonts['Druk Text Wide'] = 'Druk Text Wide';
    return $fonts;
});

// Encolar el CSS para cargar la fuente desde la carpeta del tema
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('druk-font', plugin_dir_url(__FILE__) . 'css/druk-font.css');
});
