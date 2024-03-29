<!-- 
THEME: Small Apps | Bootstrap App Landing Template
VERSION: 1.0.0
AUTHOR: Themefisher

HOMEPAGE: https://themefisher.com/products/small-apps-free-app-landing-page-template/
DEMO: https://demo.themefisher.com/small-apps/
GITHUB: https://github.com/themefisher/Small-Apps-Bootstrap-App-Landing-Template

Get HUGO Version : https://themefisher.com/products/small-apps-hugo-app-landing-theme/

WEBSITE: https://themefisher.com
TWITTER: https://twitter.com/themefisher
FACEBOOK: https://www.facebook.com/themefisher
-->

<!DOCTYPE html>
<html lang="en">
<head>
   <?php require __DIR__.'/../../../blocks/technext/general/head.php'; ?>
</head>

<body class="body-wrapper" data-spy="scroll" data-target=".privacy-nav">

<?php require __DIR__.'/../../../blocks/technext/general/navbar.php'; ?>


<!--====================================
=            Hero Section            =
=====================================-->
<section class="section gradient-banner">
	<div class="shapes-container">
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="1000" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="1000" data-aos-delay="100"></div>
		<div class="shape" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300"></div>
		<div class="shape" data-aos="fade-down-right" data-aos-duration="500" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-right" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="zoom-out" data-aos-duration="2000" data-aos-delay="500"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="500" data-aos-delay="200"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-up" data-aos-duration="500" data-aos-delay="0"></div>
		<div class="shape" data-aos="fade-down" data-aos-duration="500" data-aos-delay="0"></div>
		<div class="shape" data-aos="fade-up-right" data-aos-duration="500" data-aos-delay="100"></div>
		<div class="shape" data-aos="fade-down-left" data-aos-duration="500" data-aos-delay="0"></div>
	</div>
	<div class="container">
                <?php switch ($page??null) :
                case 'login' :
                    require __DIR__.'/../../../blocks/technext/login/main.php';
                break;
                case 'profile' :
                    require __DIR__.'/../../../blocks/technext/profile/main.php';
                break;
                case 'password' :
                    require __DIR__.'/../../../blocks/technext/profile/password.php';
                break;
                case 'calendar' :
                    require __DIR__.'/../../../blocks/technext/calendar/list.php';
                break;
                case 'calendar/add' :
                    require __DIR__.'/../../../blocks/technext/calendar/edit.php';
                break;
                case 'prey' :
                    require __DIR__.'/../../../blocks/technext/prey/list.php';
                break;    
                case 'prey/add' :
                    require __DIR__.'/../../../blocks/technext/prey/edit.php';
                break;
                case 'content' :
                    require __DIR__.'/../../../blocks/technext/content/list.php';
                break;
                case 'content/add' :
                    require __DIR__.'/../../../blocks/technext/content/edit.php';
                break;
                default:
                    require __DIR__.'/../../../blocks/technext/home/main.php';
                break;
                endswitch; ?>
	</div>
</section>
<!--====  End of Hero Section  ====-->

<!--==================================
=            Feature Grid            =
===================================-->
<section class="feature section pt-0">
<?php
/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $this->get('entity_manager');
$queryBuilder = $entityManager
->getConnection()
->createQueryBuilder();
$query = $queryBuilder
->select('*')
->from('content')
->where(
        $queryBuilder->expr()->eq('code', ':search')
)
->setParameter('search', 'footer');
$content = $query->fetchAssociative();
?>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 ml-auto justify-content-center">
				<!-- Feature Mockup -->
				<div class="image-content" data-aos="fade-right">
					<img class="img-fluid" src="<?=$this->get('settings')['baseUrl'] ?>/theme/technext/small-apps/images/religiosita.png" alt="iphone">
				</div>
			</div>
			<div class="col-lg-6 mr-auto align-self-center">
				<div class="feature-content">
					<!-- Feature Title -->
					<h2><?=$content['title'];?></h2>
					<!-- Feature Description -->
					<p class="desc"><?=$content['content'];?></p>
				</div>
			</div>
		</div>
	</div>
</section>

<!--============================
=            Footer            =
=============================-->
<footer>
  <div class="footer-main">
    <div class="container">
      <div class="row">
        
      </div>
    </div>
  </div>
  <div class="text-center bg-dark py-4">
    <small class="text-secondary">Copyright &copy; <script>document.write(new Date().getFullYear())</script>. Designed &amp; Developed by <a href="https://themefisher.com/">Themefisher</a></small class="text-secondary">
  </div>

	<div class="text-center bg-dark py-1">
   <small> <p>Distributed By <a href="https://themewagon.com/">Themewagon</a></p></small class="text-secondary">
  </div>
</footer>


  <!-- To Top -->
  <div class="scroll-top-to">
    <i class="ti-angle-up"></i>
  </div>
  <?php require __DIR__.'/../../../blocks/technext/general/foot.php'; ?>
</body>

</html>