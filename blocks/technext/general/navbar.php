<nav class="navbar main-nav navbar-expand-lg px-2 px-sm-0 py-2 py-lg-0">
  <div class="container">
    <a class="navbar-brand" href="index.html"><img src="<?=$this->get('settings')['baseUrl'] ?>/vendor/technext/small-apps/images/logo.png" alt="logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="ti-menu"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown <?=(empty($page)?'active':'')?>">
          <a class="nav-link dropdown-toggle" href="<?=$this->get('settings')['baseUrl'] ?>/">Home
          </a> 
        </li>
        <?php if (empty($_SESSION['username'])) : ?>
        <li class="nav-item dropdown @@pages <?=(($page??'')==='login'?'active':'')?>">
          <a class="nav-link dropdown-toggle" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/login">Accedi</a>
        </li>
        <?php else : ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Amministrazione
            <span><i class="ti-angle-down"></i></span>
          </a>
          <!-- Dropdown list -->  
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/profilo">Profilo</a></li>
            <li><a class="dropdown-item" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/password">Cambia password</a></li>
            <li><a class="dropdown-item" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/calendari">Caledari</a></li>
            <li><a class="dropdown-item" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/preghiere">Preghiere</a></li>
          </ul>
        </li>    
        <li class="nav-item dropdown @@pages <?=(($page??'')==='logout'?'active':'')?>">
          <a class="nav-link dropdown-toggle" href="<?=$this->get('settings')['baseUrl'] ?>/index.php/logout">Esci</a>
        </li>
        <?php endif; ?>
        <li class="nav-item @@about">
          <a class="nav-link" href="about.html">About</a>
        </li>
        <li class="nav-item @@contact">
          <a class="nav-link" href="contact.html">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>