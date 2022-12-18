
  <!-- JAVASCRIPTS -->
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/slick-carousel/slick/slick.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery-fancybox/source/js/jquery.fancybox.pack.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery-syotimer/build/jquery.syotimer.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/aos/dist/aos.js"></script>
  
  <script src="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/js/script.js"></script>
  
  <?php switch ($page??null) :
        case 'login' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/login.js"></script>
   <?php break;
   endswitch; ?>