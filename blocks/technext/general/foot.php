
  <!-- JAVASCRIPTS -->
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery/dist/jquery.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/slick-carousel/slick/slick.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery-fancybox/source/js/jquery.fancybox.pack.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/jquery-syotimer/build/jquery.syotimer.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/aos/dist/aos.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap-table/dist/bootstrap-table.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap-table/dist/locale/bootstrap-table-it-IT.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootstrap-table/dist/themes/bootstrap-table/bootstrap-table.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootbox/dist/bootbox.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/bootbox/dist/bootbox.locales.min.js"></script>
      
  <script src="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/js/script.js"></script>
  
  <?php switch ($page??null) :
        case 'login' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/login.js"></script>
   <?php break;
        case 'calendar/add' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/tinymce/tinymce.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/tinymce-langs/langs/it.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/calendar.add.js"></script>
   <?php break;
        case 'calendar' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/calendar.list.js"></script>
   <?php break;
    case 'prey' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/prey.list.js"></script>
   <?php break;
        case 'prey/add' : ?>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/tinymce/tinymce.min.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/node_modules/tinymce-langs/langs/it.js"></script>
  <script src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/js/prey.add.js"></script>
   <?php break;
   endswitch; ?>