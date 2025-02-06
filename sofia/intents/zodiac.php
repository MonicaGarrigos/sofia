<?php

    use GuzzleHttp\Client;
    use GuzzleHttp\RequestOptions;
    use GuzzleHttp\Psr7\Request;

    if(intent('zodiac_general')){

        // ** Obtener el lenguaje
        $lang = getContextParameter("auto")['idioma'];
        
        $sign = getContextParameter("auto")['zodiac_signs']; // Obtiene el signo zodiacal (si ya está definido)
        

        if(!$sign){
            // ** Ofrecer los signos del zodiaco
            // Si no se ha definido el signo, muestra las opciones de signos zodiacales
            $cTitleArray = [];
            $cImageArray = [];

            $signs = Signs::getSigns();  // !! Obtiene la lista de signos desde la base de datos

            // Construye las opciones de signos zodiacales
            for($i = 0; $i<count($signs); $i++){
                array_push($cTitleArray, $signs[$i]["sign_name_$lang"]); // Según el idioma
                array_push($cImageArray,$signs[$i]["sign_image"]);
            }

            $cCustomArray = $cTitleArray; // Las acciones para las opciones son los nombres de los signos

            // Define los mensajes según el idioma
            if($lang=="es"){

                $aTitleArray = ["Escoge tu signo zodiacal"];
                $aSubtitleArray = ["Haz click en el"];
                $aTextArray = [""];

            }else{

                $aTitleArray = ["Choose your Zodiac Sign"];
                $aSubtitleArray = ["Click on it"];
                $aTextArray = [""];

            }

            $context = false;
            $contextBody = [];
            $webTitle=["zodiac_general"];
            $imageArray = ["https://img.freepik.com/vector-gratis/coleccion-signos-zodiaco-dibujados-mano_52683-64292.jpg"];

            $structure = ['image','comma','card','superDivider','reply'];
            $components = [
                            [$imageArray,$webTitle],
                            [],
                            [$aTitleArray,$aSubtitleArray,$aTextArray],
                            [],
                            [$cTitleArray, $cImageArray, $cCustomArray]
                        ];

            webStructureTemplate($context, $contextBody, $webTitle, $structure, $components);


        }else{
            // Si ya se ha definido el signo, muestra el horóscopo
            $actualDate = date("Y-m-d"); // Obtiene la fecha actual

            // !! Consumir API de signos del zodiaco
            $client = new Client();
            $handler = $client->get(
                "https://newastro.vercel.app/$sign/?date=$actualDate&lang=$lang",
                [
                    RequestOptions::HEADERS => [
                        'Accept' => 'application/json'
                    ]
                ]
            );

            $result = $handler->getBody()->getContents();
            $array = json_decode($result,true); // Parsear el resultado

            // Obtener la información
            $horoscope = $array["horoscope"];
            $horoscope = str_replace('"','',$horoscope);
            $icon = $array["icon"];

            // Formatear la información
            $titles = explode(".", $horoscope);
            $zodiac_response = $titles[0];

            for($i=1; $i<count($titles); $i++){
                $zodiac_response .= "\n\n" . $titles[$i];
            }
            
            // Mostramos la información en estructura
            if($lang == "es"){

                $aTitleArray = ["Tu Horoscopo para el día de hoy - Signo $sign "];
                $aSubtitleArray = ["Click aquí => "];
                $aTextArray = ["$zodiac_response"];
                $bTitleArray = ["Seleccionar otro signo", "Dame un consejo"];
                $bCustomArray = ["zodiac_general", "consejo_signo"];
        
            }else{
        
                $aTitleArray = ["Your Horoscope for today - Sign $sign"];
                $aSubtitleArray = ["Click here => "];
                $aTextArray = ["$zodiac_response"];
                $bTitleArray = ["Select another sign", "Give me advice"];
                $bCustomArray = ["zodiac_general", "consejo_signo"];
        
            }
        
            $context = false;
            $contextBody = [];
            $webTitle=["zodiac_general"];
        
            $imageArray = ["$icon"];
         
            $bIconArray = ["change_circle","lightbulb"];
            $bColorArray=["#c3d48b", "#ffd700"];
        
            $structure = ['image','comma','card','superDivider','button'];
        
            $components = [
                            [$imageArray,$webTitle],
                            [],
                            [$aTitleArray,$aSubtitleArray,$aTextArray],
                            [],
                            [$bTitleArray,$bCustomArray,$bIconArray,$bColorArray]
                        ];
        
            webStructureTemplate($context, $contextBody, $webTitle, $structure, $components);
        

        }
        
    }

    if (intent('consejo_signo')) {
        $lang = getContextParameter("auto")['idioma'];
        $sign = getContextParameter("auto")['zodiac_signs'];
    
        // Si no hay signo, solicitarlo al usuario
        if (!$sign) {
            $responseText = $lang == "es" 
                ? "Parece que no tengo registrado tu signo zodiacal. Por favor selecciona primero tu signo." 
                : "It seems I don't have your zodiac sign. Please select it first.";
    
            $aTitleArray = [$responseText];
            $aSubtitleArray = [""];
            $aTextArray = [""];
    
            webStructureTemplate(false, [], ["consejo_signo"], ['card'], [[$aTitleArray, $aSubtitleArray, $aTextArray]]);
            return; // Finalizar la ejecución si no hay signo
        }
    
        // Consejos personalizados
        $consejos = [
            "Aries" => [
                "es" => "Atrévete a hacer esa llamada que has estado evitando. Hoy tienes la energía para enfrentar cualquier situación.",
                "en" => "Dare to make that call you've been avoiding. Today you have the energy to face any situation."
            ],
            "Tauro" => [
                "es" => "Busca tiempo para saborear tu comida favorita, sin prisas. Haz de cada bocado una experiencia memorable.",
                "en" => "Take time to savor your favorite meal without rushing. Make each bite a memorable experience."
            ],
            "Géminis" => [
                "es" => "Inicia una conversación con alguien nuevo o inesperado. Nunca sabes qué ideas sorprendentes pueden surgir.",
                "en" => "Start a conversation with someone new or unexpected. You never know what surprising ideas might come up."
            ],
            "Cáncer" => [
                "es" => "Regala a alguien una carta o mensaje sincero. Tu empatía tiene el poder de iluminar el día de otra persona.",
                "en" => "Gift someone a sincere letter or message. Your empathy can brighten someone else's day."
            ],
            "Leo" => [
                "es" => "Hoy es perfecto para mostrarle al mundo tu brillo único. Comparte un logro o expresa tu creatividad con confianza.",
                "en" => "Today is perfect to show the world your unique shine. Share an achievement or express your creativity with confidence."
            ],
            "Virgo" => [
                "es" => "Ordena ese rincón que siempre postergas. Descubrirás que el orden externo puede traer calma interior.",
                "en" => "Tidy up that corner you've been postponing. You'll discover that external order brings inner calm."
            ],
            "Libra" => [
                "es" => "Haz una pausa en tu rutina para contemplar algo hermoso: un atardecer, una obra de arte o simplemente el cielo.",
                "en" => "Pause your routine to contemplate something beautiful: a sunset, a work of art, or simply the sky."
            ],
            "Escorpio" => [
                "es" => "Reconoce tus emociones sin juzgarlas. A veces el simple hecho de aceptar lo que sientes es el paso más poderoso.",
                "en" => "Acknowledge your emotions without judging them. Sometimes simply accepting how you feel is the most powerful step."
            ],
            "Sagitario" => [
                "es" => "Busca una ruta distinta al lugar de siempre o prueba un platillo exótico. Hoy es un día para explorar nuevos horizontes.",
                "en" => "Take a different route or try an exotic dish. Today is a day to explore new horizons."
            ],
            "Capricornio" => [
                "es" => "Desconéctate por completo durante una hora. Sin notificaciones ni responsabilidades, solo tú y el momento.",
                "en" => "Disconnect completely for an hour. No notifications, no responsibilities, just you and the moment."
            ],
            "Acuario" => [
                "es" => "Haz una lluvia de ideas sin restricciones sobre un proyecto o sueño personal. Tu genialidad florece cuando rompes esquemas.",
                "en" => "Brainstorm without restrictions about a project or personal dream. Your brilliance shines when you break patterns."
            ],
            "Piscis" => [
                "es" => "Escribe tus sueños o visiones en un papel. Hoy tu intuición está más despierta que nunca; presta atención.",
                "en" => "Write your dreams or visions on paper. Today your intuition is sharper than ever; pay attention."
            ]
        ];
        
        
        // Determinar el consejo correspondiente
        $mensaje = $consejos[$sign] ?? "Recuerda cuidar tu bienestar físico y emocional.";
    
        $aTitleArray = [$lang == "es" ? "Consejo de bienestar para $sign" : "Wellness tip for $sign"];
        $aSubtitleArray = [""];
        $aTextArray = ["$mensaje"];
    
        $webTitle = ["consejo_signo"];
        $structure = ['card'];
        $components = [[$aTitleArray, $aSubtitleArray, $aTextArray]];
    
        // Mostrar respuesta del bot
        webStructureTemplate(false, [], $webTitle, $structure, $components);
    }
    




?>
