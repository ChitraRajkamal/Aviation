<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#bb4312" />
    <link rel="icon" href="{{asset('admin-assets/assets/images/favicon.png')}}" type="image/png" />
    <link rel="apple-touch-icon" href="{{asset('admin-assets/assets/images/favicon.png')}}" />
    <title>API Documentation - {{lms_setting('app_name')}}</title>
    <link rel="stylesheet" href="{{asset('assets/css/swagger-ui.css')}}">
    <style>
        body{
            margin: 0px;
        }
        .overlay{
            width: 100%;
            height: 320px;
            opacity: 0.8;
            background-position: top;
            background-size: cover;
            background-repeat: no-repeat;
            background-image: url('/public/assets/img/bg.jpg');
            box-shadow: 1px 1px 1px #575656;
        }
        .swagger-ui .info {
            margin: 20px 0;
        }
        .schemes-server-container{
            /*opacity: 0;*/
        }
        .swagger-ui .scheme-container {
            box-shadow: none;
            margin: 0 0 0px;
            padding: 14px 0;
        }
        .information-container .info{
            text-align: center;
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
        }
        .info, .overlay, .download-url-wrapper{
          display: none !important;
        }
        /*.topbar-wrapper .link{
            display: none !important;
        }*/
        @media only screen and (max-width: 600px) {
            .overlay{
                background-position: right;
                opacity: 0.2;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div id="swagger-ui"></div>
    
    <script src="{{asset('assets/js/swagger-ui-bundle.js')}}"></script>
    <script src="{{asset('assets/js/swagger-ui-standalone-preset.js')}}"></script>

    <script>
        const ui = SwaggerUIBundle({
            url: '{{asset('api/api.json')}}', // URL to your Swagger JSON
            //configUrl: "/public/assets/swagger-config.json",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            layout: "StandaloneLayout",
            persistAuthorization: true,
            defaultModelsExpandDepth: -1,
            filter: true,
            tryItOutEnabled: true,
            //operationsSorter: 'alpha',
            //tagsSorter: 'alpha',
            //requestSnippetsEnabled: true,
            //defaultModelRendering: 'example',
            //docExpansion: 'none',
            onComplete: function () {
                const target = document.querySelector('.title');
                const logo = document.createElement('img');
                logo.src = '{{asset('admin-assets/assets/images/favicon.png')}}';
                logo.style.height = '80px';
                logo.style.marginRight = '15px';

                target.parentNode.insertBefore(logo, target);
            },
            /*requestInterceptor: (request) => {
                console.log("Intercepting request:", request);
                request.headers["CustomHussain"] = "Hello badusah";
                return request;
            }*/
        });

        // Add custom case-insensitive filtering logic
        const inputField = document.querySelector('.operation-filter-input'); // Find the search input box
        if (inputField) {
            inputField.addEventListener('input', (event) => {
                const searchTerm = event.target.value.toLowerCase();
                const allOperations = document.querySelectorAll('.opblock-summary-description, .opblock-summary-path');
                
                allOperations.forEach(op => {
                    const textContent = op.textContent.toLowerCase();
                    const isMatch = textContent.includes(searchTerm);

                    const opBlock = op.closest('.opblock');
                    if (opBlock) {
                        opBlock.style.display = isMatch ? 'block' : 'none';
                    }
                });
            });
        }
    </script>
</body>

</html>
