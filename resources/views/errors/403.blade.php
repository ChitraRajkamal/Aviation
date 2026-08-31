<html lang="en"><head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 Unauthorized Access</title>
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav.png">
  <!-- fontawesome 6.4.2 -->
  <link rel="stylesheet" href="{{asset('assets/css/plugins/fontawesome-6.css')}}">
  <!-- custom style css -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
<style id="theia-sticky-sidebar-stylesheet-TSS">.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style></head>

<body>

  <div class="rts-404-area-start">
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="inner-content">
                      <h1 class="title" style="color: #E53935;">403</h1>
                      <h2 class="title">UNAUTHORIZED ACCESS
                      </h2>
                      <p>Sorry, you don't have permission to access this page.</p>
                      @if (request()->route() && str(request()->route()->uri())->contains('admin'))
                        <a href="{{route('admin.dashboard')}}" class="rts-btn btn-primary">GO TO DASHBOARD</a>
                      @else
                        <a href="{{url('/')}}" class="rts-btn btn-primary">GO BACK HOME</a>
                      @endif                      
                  </div>
              </div>
          </div>
      </div>
  </div>


</body>
</html>