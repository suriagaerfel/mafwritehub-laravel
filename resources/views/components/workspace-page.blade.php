
<div class=" page workspace-page">
    <?php if ($pageName != 'Workspace - Website Manager') { ?>
      @include('components/workspace-sidebar')
      @include('components/actual-workspace')
    <?php }?>

    
     <?php if ($pageName == 'Workspace - Website Manager') { ?>
           @include('components/summary')
    <?php }?>
</div>


