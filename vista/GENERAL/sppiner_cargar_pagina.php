<?php
// Usar eso en los document ready para ocultar el cargado 
// $(window).on('load', function() {
//     $("#loader-overlay").fadeOut("slow");
// });
?>

<div id="loader-overlay" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;">

    <div class="loader"></div>
    <p style="margin-top: 15px; font-family: sans-serif; color: #333;">Cargando sistema...</p>
</div>

<style>
    .loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        /* Color del spinner */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>