<style>
  #course-actions .ph{
      font-size: 20px;
  }
  #course-actions .nav-link {
      display: flex;
      align-items: center;
      column-gap: 4px;
  }
</style>
<ul id="course-actions" class="nav nav-tabs" id="modernTab" role="tablist">
  @php
      function generateCourseAction($courseId, $reqAction, $currentAction, $icon){
        $active = $reqAction === $currentAction ? 'active' : '';
        $routeUrl = $active ? '#' : route('admin.courses.custom-edit', [ 'course' => $courseId, 'action' => $currentAction ]);
        $currentActionTitle = ucfirst($currentAction);
        if($currentActionTitle == 'Seo'){
          $currentActionTitle = 'SEO';
        }
        $code = <<<LMS
            <li class="nav-item">
                <a href="$routeUrl" id="$currentAction-tab" class="nav-link sloc $active" >
                    <i class="$icon"></i> $currentActionTitle
                </a>
            </li>
LMS;
        echo $code;
      }
      generateCourseAction($courseId, $action, 'basic', 'ph ph-tag');
      generateCourseAction($courseId, $action, 'curriculum', 'ph ph-notebook');
      generateCourseAction($courseId, $action, 'pricing', 'ph ph-currency-circle-dollar');
      generateCourseAction($courseId, $action, 'info', 'ph ph-copy');
      generateCourseAction($courseId, $action, 'media', 'ph ph-images');
      generateCourseAction($courseId, $action, 'seo', 'ph ph-file-magnifying-glass');
  @endphp
</ul>