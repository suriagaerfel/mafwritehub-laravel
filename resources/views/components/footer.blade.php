
<?php if (str_contains($currentURL, '/workspace/')){ ?>
      <?php if ($pageName !=='Workspace - Site Manager' && $pageName !=='Workspace - Data Analyst' && $pageName !=='Workspace - Funder') {?>
      <img src="{{asset('images/list.svg')}}" class="icon list-icon">
      <img src="{{asset('images/edit.svg')}}" class="icon edit-icon">
      <?php } ?>     
  <?php } ?>


  <?php if ($pageName=='Create Account' || $pageName=='Login' || $pageName=='Get Password Reset Link' || $pageName=='Reset Password') {?>
  <div id="footer-page-links-container">
    <a><small><?php echo date('Y',$currentTime).' &copy; All Rights Reserved'?></small></a>
    <div style="display: flex; gap:15px;">
      <a href="<?php echo $publicFolder.'/terms-of-use/'?>"><small>Terms of Use</small></a>
      <a href="<?php echo $publicFolder.'/data-privacy/'?>"><small>Data Privacy</small></a>
      <a href="<?php echo $publicFolder.'/about-us/'?>"><small>About Us</small></a>
    </div>
  </div>
<?php } ?>

 <script src="<?php echo asset('/js/variables.js');?>"></script>
 <script src="<?php echo asset('/js/functions.js');?>"></script>
  <script src="<?php echo asset('/js/triggers.js'); ?>"></script>
  

  <?php if ($pageName == 'Workspace - Writer') {?>
    <script src="<?php echo $publicFolder.'/assets/js/eskquip-text-editor.js'?>"></script>
  <?php } ?>

 



<?php ob_end_flush();?>