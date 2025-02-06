<?php

if (intent('saludo')) {
    // Genera un ID de sesión único
    $sessionId = id2();
    $raw = getInput();

    // Crea una nueva sesión en la base de datos
    $sessionCreated = Sessions::createSession($sessionId);

    if (!$sessionCreated) {
        // Manejo de error si no se puede crear la sesión
        triggerError(false, [], ['saludo'], ['no se generó la sesion en bd'], []);
    } else {

        //Definir el contexto y los parámetros de la sesión
        $context = true;
        $contextIndex = [0];
        $parameters = ['config' => ["sessionId" => $sessionId],];

        $contextBody = updateContextParameters($contextIndex, $parameters); // Actualiza la sesión

        // Define los elementos de la interfaz (imagen, título, opciones de idioma)
        $webTitle = ["saludo"];
        $imageArray = ["https://s3.ppllstatics.com/diariosur/www/multimedia/2025/02/03/Horoscopo-1738579854.jpg"];
        $aTitleArray = ["🌟 ¡Hola, soy Sofía! 🌙 Tu asistente personal para descubrir qué te depara el universo hoy."];
        $aSubtitleArray = ["Primero escoge tu idioma"];
        $aTextArray = [""];
        $cTitleArray = ["Español", "English"];
        $cImageArray = ["$img_Spanish", "$img_English"];
        $cCustomArray = ["es", "en"];

        // Define la estructura de la interfaz y los componentes
        $structure = ['image', 'comma', 'card', 'superDivider', 'reply'];
        $components = [
            [$imageArray, $webTitle],
            [],
            [$aTitleArray, $aSubtitleArray, $aTextArray],
            [],
            [$cTitleArray, $cImageArray, $cCustomArray]
        ];

        // Muestra la interfaz al usuario
        webStructureTemplate($context, $contextBody, $webTitle, $structure, $components);
    }
}

if (intent('idioma')) {

    // Obtiene el idioma seleccionado
    $lang = getContextParameter("auto")['idioma'];

    // Define los mensajes de bienvenida y las opciones según el idioma
    if ($lang == "es") {
        $aTitleArray = ["✨ Bienvenido a Sofía, tu asistente de horóscopo diario. 📆"];
        $aSubtitleArray = ["¿En qué podría ayudarte?"];
        $aTextArray = [""];
        $bTitleArray = ["Horóscopo", "Seleccionar otro idioma"];
    } else {
        $aTitleArray = ["✨ Welcome to Sophia, your daily horoscope assistant. 📆"];
        $aSubtitleArray = ["There something I can help you with?"];
        $aTextArray = [""];
        $bTitleArray = ["Horoscope", "Select other language"];
    }

    // Define los elementos de la interfaz (mensaje de bienvenida, opciones)
    $context = false;
    $contextBody = [];
    $webTitle = ["idioma"];

    $imageArray = [""];
    $bCustomArray = ["zodiac_general", "cambio_idioma"];
    $bIconArray = ["travel_explore", "language"];
    $bColorArray = ["#c3d48b", "#329542"];

    // Define la estructura de la interfaz y los componentes
    $structure = ['image', 'comma', 'card', 'superDivider', 'button'];
    $components = [
        [$imageArray, $webTitle],
        [],
        [$aTitleArray, $aSubtitleArray, $aTextArray],
        [],
        [$bTitleArray, $bCustomArray, $bIconArray, $bColorArray]
    ];

    // Muestra la interfaz al usuario
    webStructureTemplate($context, $contextBody, $webTitle, $structure, $components);
}

if (intent('cambio_idioma')) {

    $context = false;
    $contextBody = [];
    $webTitle = ["cambio_idioma"];

    $lang = getContextParameter("auto")['idioma']; // Obtiene el idioma actual

    // Define los mensajes y las opciones según el idioma actual
    if ($lang == 'es') {
        $aTitleArray = ["Selecciona un nuevo idioma"];
        $aSubtitleArray = ["Puedes cambiarlo las veces que desees"];
        $aTextArray = ["text"];
        $cTitleArray = ["English"];
    } else {
        $aTitleArray = ["Choose your language"];
        $aSubtitleArray = ["You can change it wherever you like"];
        $aTextArray = ["text"];
        $cTitleArray = ["Español"];
    }

    $imageArray = [""];
    $cImageArray = ["$img_Spanish", "$img_English"];
    $cCustomArray = ["es", "en"];

    $structure = ['image', 'comma', 'card', 'superDivider', 'reply'];

    $components = [
        [$imageArray, $webTitle],
        [],
        [$aTitleArray, $aSubtitleArray, $aTextArray],
        [],
        [$cTitleArray, $cImageArray, $cCustomArray]
    ];

    webStructureTemplate($context, $contextBody, $webTitle, $structure, $components);
}

?>
