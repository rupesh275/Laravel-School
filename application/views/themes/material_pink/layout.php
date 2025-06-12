<!DOCTYPE html>

<html dir="<?php echo ($front_setting->is_active_rtl) ? " rtl" : "ltr" ; ?>" lang="
<?php echo ($front_setting->is_active_rtl) ? "ar" : "en"; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo $page['title']; ?>
    </title>
    <meta name="title" content="<?php echo $page['meta_title']; ?>">
    <meta name="keywords" content="<?php echo $page['meta_keyword']; ?>">
    <meta name="description" content="<?php echo $page['meta_description']; ?>">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url($front_setting->fav_icon); ?>" type="image/x-icon">
    <link href="<?php echo $base_assets_url; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_assets_url; ?>css/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo $base_assets_url; ?>css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $base_assets_url; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">
    <link rel="stylesheet" href="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker3.css" />
    <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
    <!--file dropify-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    <!--file dropify-->
    <script src="<?php echo base_url(); ?>backend/dist/js/dropify.min.js"></script>
    <script type="text/javascript">
        var base_url = "<?php echo base_url() ?>";
    </script>
    <?php        
        if ($front_setting->is_active_rtl) {
            ?>
    <link href="<?php echo $base_assets_url; ?>rtl/bootstrap-rtl.min.css" rel="stylesheet">
    <link href="<?php echo $base_assets_url; ?>rtl/style-rtl.css" rel="stylesheet">
    <?php
        }
        ?>
    <?php echo $front_setting->google_analytics; ?>
<style>
video {
  max-width: 100%;
  height: auto;
}
</style>    
</head>
<body>
    <div id="alert" class="affix-top">
        <div class="topsection">
            <section class="newsarea">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="newscontent">
                                <?php
                              if (in_array('news', json_decode($front_setting->sidebar_options))) {
                                  ?>
                                <div class="newstab">Latest News</div>
                                <div class="newscontent">
                                    <marquee class="" behavior="scroll" direction="left" onmouseover="this.stop();"
                                        onmouseout="this.start();">
                                        <ul id="" class="">
                                            <?php
                                              if (!empty($flashnews)) {
  
                                                  foreach ($flashnews as $banner_notice_key => $banner_notice_value) {
                                                      ?>
                                            <li><a
                                                    href="<?php echo site_url('read/' . $banner_notice_value['slug']) ?>" >
                                                    <!--
                                                    <div class="datenews">
                                                        <?php
                                                                  echo date('d', strtotime($banner_notice_value['date'])) . " " . $this->lang->line(strtolower(date('F', strtotime($banner_notice_value['date'])))) . " " . date('Y', strtotime($banner_notice_value['date']));
                                                                  ?>
                                                        <span>
                                                        </span>
                                                    </div>
                                                    -->
                                                    <?php echo $banner_notice_value['title']; ?>
                                                </a></li>
                                            <?php
                                                  }
                                              }
                                              ?>
                                        </ul>

                                    </marquee>
                                </div>
                                <!--./newscontent-->

                                <?php
                              }
                              ?>
                            </div>
                            <!--./sidebar-->

                        </div>
                        <!--./col-md-12-->
                    </div>
                </div>
            </section>

            <div class="toparea">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <ul class="toplist">
                                <li>
                                    <a href="mailto:<?php echo $school_setting->email; ?>"><i
                                            class="fa fa-envelope-o"></i>
                                        <?php echo $school_setting->email; ?>
                                    </a>
                            </ul>

                        </div>
                        <!--./col-md-5-->
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <ul class="topicon">
                                <li>Follow Us</li>
                                <?php $this->view('/themes/darkgray/social_media'); ?>
                            </ul>
                        </div>
                        <!--./col-md-6-->


                    </div>
                </div>
            </div>
            <!--./toparea-->
        </div>
        <!--./topsection-->


        <?php echo $header; ?>

        <?php echo $slider; ?>

        <?php if (isset($featured_image) && $featured_image != "") {
              ?>
        <?php
          }
          ?>
        <?php
                  $page_colomn = "col-md-12 spacet60 pt-0-mobile";
  
                  if ($page_side_bar) {
  
                      $page_colomn = "col-md-9 col-sm-9";
                  }
                  ?>
        <?php if (strpos($content, 'home page') == false) { ?>
        <div class="<?php echo $page_colomn; ?>" style = "margin-left:50px;padding-right:100px;">
            <?php echo $content; ?>
        </div>

        <?php
                  }
                  else
                  {
                  ?>


        <?php    if (strpos($content, 'home page') !== false) {  ?>




        <?php  ?>
        <div class="container " style="padding-top: 120px;">
            <div class="row">
                <section class="services">
                    <div class="service-inner">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 service-box">
                                    <div class="service-box-content">
                                        <h3><a href="https://sngcentralschool.org/web/page/admission-procedure">Admission</a></h3>
                                        <p></p>
                                        <div class="service-box-icon"><img
                                                src="https://demo.smart-school.in/uploads/gallery/media/book-icon.png" />
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-4-->
                                <div class="col-md-3 col-sm-3 service-box">
                                    <div class="service-box-content">
                                        <h3><a href="https://sngcentralschool.org/web/online_admission">Registration</a></h3>
                                        <p></p>
                                        <div class="service-box-icon"><img
                                                src="https://demo.smart-school.in/uploads/gallery/media/scholarship-icon.png" />
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-4-->
                                <div class="col-md-3 col-sm-3 service-box">
                                    <div class="service-box-content">
                                        <h3><a href="<?php echo base_url(); ?>page/enquiry">Enquiry</a></h3>
                                        <p></p>
                                        <div class="service-box-icon"><img
                                                src="<?php echo base_url(); ?>upload/teacher-icon.png" />
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-4-->
                                <div class="col-md-3 col-sm-3 service-box">
                                    <div class="service-box-content">
                                        <h3><a href="#" data-toggle="modal" data-target="#schoolvideo" class="video-play Play_icon">School Video</a></h3>

                                        <p></p>
                                        <div class="service-box-icon"><img
                                                src="<?php echo base_url(); ?>upload/teacher-icon.png" />
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-4-->                                
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php  ?>
        <section>
            <div class="container">
                <div class="row aboutsection">
                    <div class="col-md-6"  style="text-align: justify;">
                        <h2>About Us</h2>
                        <?php echo $aboutus['description']; ?>
                    </div>
                    <div class="col-md-6" style="margin-bottom:20px">
                        <img class="img-responsive img-rounded"
                            src="<?php echo $aboutus['feature_image'];?>" />
                    </div>
                </div>
                <div class="row">

                    <!--./row-->
                </div>
                    <!--./container-->
                </div>
            </div>
            </div>
            <!--./container-->
        </section>


    </div>
     <section style="background-color:#fcecbd">
        <div class="container" >
            <div class="row" style="padding-bottom:50px;">
                <div class="col-md-12">
                    <h2>Sree Narayana Guru</h2>
                </div>
                <div class="col-md-3">
                        <img class="img-responsive img-rounded" style="height:200px;width:200px;" src="<?php echo $gurudev['feature_image']; ?>" />                        
                        <p>
                        
                        </p>
                    </div>
                    <div class="col-md-9">
                        <?php echo $gurudev['description']; ?>
                        <p>
                            <a class="btn-read" href="https://sngcentralschool.org/web/page/about-guru">Read More</a>
                        </p>
                    </div>        
            </div>
        </div>
    </section>   

    <section class="principalmessage">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Our Principal's Message</h2>
                </div>
                <div class="col-md-6" style="text-align: justify;">
                    <hr>
                    <?php echo $princmsg['description']; ?>
                </div>
                <div class="col-md-6">
                    <div class="ourprencipal">
                        <img class="img-responsive img-rounded"
                            src="<?php echo $princmsg['feature_image'];?>" style="height:300px;" />
                        <h3>Mrs.Deepa Jayaroy</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="threesection">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h2>Notice Board</h2>
                    <hr>
                    <ul class="event-wrapper pagescrolling" style="height:407px;  background: #FFFFFF;">
                        <marquee direction="up" height="397">
                            <?php
                              foreach ($news as $key => $new1) {
                             ?>
                            <li class="wow bounceInUp" data-wow-duration="2s" data-wow-delay=".1s" style="">
                                
                                <div class="staff-content">
                                    <h3>
                                        <a class="btn-read" href="<?php if(isset($new1['feature_image'])) echo $new1['feature_image']; else echo base_url().$new1['url']; ?>" target="_blank">
                                            <?php echo date("d-m-Y", strtotime($new1['date'])); ?>
                                        </a></h3>

                                    <h4><br>
                                    <a  href="<?php if(isset($new1['feature_image'])) echo $new1['feature_image']; else echo base_url().$new1['url']; ?>" target="_blank">
                                    <span class="post">
                                        
                                            <?php echo $new1['title']; ?>
                                        </span></h4>
                                    </a>
                                        <div class="newimage">
                                            <img src="<?php echo base_url(); ?>upload/new.gif"
                                                class="img-responsive newnotice" alt="news">
                                        </div>                                        
                                </div>
                                </a>
                            </li>
                            <?php } ?>
                        </marquee>
                    </ul>                    
                    <div class="news-btn-holder">
                        <a href="<?php echo base_url('page/notice-board');?>" class="view-all-accent-btn">View
                            All</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h2>Upcoming Events</h2>
                    <hr>
                    <ul class="event-wrapper pagescrolling" style="height:407px;  background: #FFFFFF;">
                        <marquee direction="up" height="397">
                            <?php
                              foreach ($events as $key => $event) {
                              ?>
                              <!--<a  href="<?php if(isset($new1['feature_image'])) echo $new1['feature_image']; else echo base_url().$new1['url']; ?>" target="_blank"> -->
                            <li class="wow bounceInUp" data-wow-duration="2s" data-wow-delay=".1s" style="">
                                <div class="staff-content">
                                    <h3>
                                        <a class="btn-read" href="#" target="_blank">
                                           <?php echo date("d-m-Y", strtotime($event['event_start'])); ?>
                                        </a>
                                        </h3>
                                    <h4><br>
                                    <span class="post">
                                            <?php echo $event['title']; ?>
                                    </span></h4>
                                </div>
                            </li>
                            <!-- </a> -->
                            <?php } ?>
                        </marquee>
                    </ul>
                    <div class="event-btn-holder">
                        <a href="<?php echo base_url('page/upcoming-events');?>" class="view-all-primary-btn">View
                            All</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h2>Our Achievements</h2>
                    <hr>
                    <div class="teamstaff">
                        <div class="row">
                            <div class="owl-carousel achievements">
                            <?php
                              foreach ($ach as $key => $achs) { 
                              ?>
                                <div class="col-md-12 col-sm-12">
                                    <div class="courses-box"  style="height:400px;">
                                        <div class="courses-box-img"  style="height:300px;"><img
                                                src="<?php echo $achs['feature_image'];?>" />
                                        </div>
                                        <!--./courses-box-img-->
                                        <div class="course-inner">
                                            <div class="details-view">
                                                <?php echo $achs['description'];  ?>
                                            </div>

                                        </div>
                                    </div>                                    
                                    <!--./courses-box-->
                                </div>
                                <!--./col-md-12-->
                                <?php } ?>

                            </div>
                            <div class="ach-viewall event-btn-holder-bt"><a href="https://sngcentralschool.org/web/page/achievements">View All</a></div>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </section>
<section class="facilitieswrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Our Facilities </h2>

                    <p></p>

                    <div class="divider">&nbsp;</div>
                </div>

                <div class="row">
                    <div class="owl-carousel courses-carousel">
                        <?php
                              foreach ($facility as $key => $facility_list) {
                        ?>                        
                        <div class="col-md-12 col-sm-12">
                            <div class="courses-box">
                                <div class="courses-box-img"><img
                                        src="<?php echo $facility_list['feature_image']; ?>"  style="height:250px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner"  style="height:200px;"><a class="course-subject" href="#"></a>
                                    <?php
                                    echo  $facility_list['description'];
                                    ?>
                                    <!-- <a class="btn-read" href="#">More</a>-->
                                </div>
                            </div>
                            <!--./courses-box-->
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!--./courses-carousel-->
            </div>
            <!--./row-->
        </div>
        <!--./container-->
    </section>
    
<!-- <section class="facilitieswrapper"  style="background-color:#9c243c;margin-left:30px;margin-right:30px;border-radius:30px;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title" style="color:white;">Choose Your Section</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>

                <div class="row" >
                      
                        <div class="col-md-4 col-sm-4" >

                            <div class="courses-box" style="background-color:#abb1d9;">
                            <div>
                                    <h3 style="text-align:center;">Pre Primary</h3>
        							<p  style="text-align:center;">NURSERY, JUNIOR KG AND SENIOR KG</p>                                
        						<div class="courses-box" style="height:500px;">
        								<?php echo $index_preprimary['description']; ?>
        						</div> 
        						<div class="pricing-button" style="font-weight: 900;line-height: 60px;width: 100%;text-transform: uppercase;display: inline-block;text-align:center;color:yellow;"><a style="color:yellow;" href="https://sngcentralschool.org/web/curriculam/preprimary">Read More</a></div>
        						</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4" >

                            <div class="courses-box" style="background-color:#abb1d9;">
                            <div>
                                    <h3 style="text-align:center;">Primary</h3>
        							<p  style="text-align:center;">CLASS I - V</p>                                
                                                         
        						<div class="courses-box"  style="height:500px;">
        							<?php echo $index_primary['description']; ?>
        						</div>                                
        						<div class="pricing-button" style="font-weight: 900;line-height: 60px;width: 100%;text-transform: uppercase;display: inline-block;text-align:center;color:yellow;"><a style="color:yellow;" href="https://sngcentralschool.org/web/curriculam/primary">Read More</a></div>
        						</div>        						
                            </div>
                        </div>                        
                        <div class="col-md-4 col-sm-4" >

                            <div class="courses-box" style="background-color:#abb1d9;">
                            <div>
                                    <h3 style="text-align:center;">Secondary</h3>
        							<p  style="text-align:center;">CLASS VI - VIII</p>                                
                             
        						<div class="courses-box"  style="height:500px;">
        							<?php echo $index_secondary['description']; ?>
        						</div>   
        						<div class="pricing-button" style="font-weight: 900;line-height: 60px;width: 100%;text-transform: uppercase;display: inline-block;text-align:center;color:yellow;"><a style="color:yellow;" href="https://sngcentralschool.org/web/curriculam/secondary">Read More</a></div>
        						</div>        						
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section> -->
    


    <section class="facilitieswrapper-co">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center pb30">
                    <h2 class="head-title">Our Celebrations</h2>
                    <p></p>
                    <div class="divider">&nbsp;</div>
                </div>

                <div class="row">
                    <div class="owl-carousel courses-carousel">
                        <?php
                              foreach ($celebrations as $key => $celebration) {
                        ?>                        
                        <div class="col-md-12 col-sm-12">
                            <div class="courses-box">
                                <div class="courses-box-img"><img
                                        src="<?php echo $celebration['feature_image']; ?>"  style="height:250px;" /></div>
                                <!--./courses-box-img-->
                                <div class="course-inner"  style="height:200px;"><a class="course-subject" href="#"></a>
                                    <?php
                                    echo  $celebration['description'];
                                    ?>
                                    <!-- <a class="btn-read" href="#">More</a> -->
                                </div>
                            </div>
                            <!--./courses-box-->
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!--./courses-carousel-->
            </div>
            <!--./row-->
        </div>
        <!--./container-->        
    </section>
    
    <section class="teachers ">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <h2>Our Experienced Staff</h2>

                    <div class="teamstaff">
                        <div class="row">
                            <div class="owl-carousel staff-carousel">
                                
                             <?php
                              foreach ($staff as $key => $staffs) {
                              ?>
                                <div class="col-md-12 col-sm-12">
                                    <div class="staffteam">
                                        <div class="staffteamimg">
                                            <img alt=""  src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $staffs['image']; ?>"  style="height:145px;width:162px;"/>
                                            <ul class="social-links">
                                                <li>Facebook</li>
                                                <li>Twitter</li>
                                                <li>Linkedin</li>
                                                <li>Google Plus</li>
                                            </ul>
                                        </div>

                                        <div class="staff-content">
                                            <h5><?php echo $staffs['name']; ?></h5>
                                            <span class="post"><?php echo $staffs['qualification']; ?></span><br>
                                            <span class="post"><?php echo $staffs['note']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>


<section class="commonbg testi">
<div class="container">
<div class="row">
<div class="col-md-6">
<h2>What we Care</h2> 

<ul class="wecareall">
    <li><i class="fa fa-shield" aria-hidden="true"></i> Safety and Security</li>
    <li><i class="fa fa-bus" aria-hidden="true"></i> Intelligent Transportation system </li>
    <li><i class="fa fa-video-camera" aria-hidden="true"></i> CCTV cameras and physical surveillance </li>
    <li><i class="fa fa-book" aria-hidden="true"></i> Sports Arena </li>
    <li><i class="fa fa-line-chart" aria-hidden="true"></i> State-of-the-art multi-purpose complex </li>
    <li><i class="fa fa-pie-chart" aria-hidden="true"></i> Multi Purpose Turf </li>
                              </ul>
</div>

<div class="col-md-6">
<h2>Testimonials</h2>    

<div class="testiwrapper">
<div class="row">
                    <div class="owl-carousel testimonials">
                        <?php
                        foreach ($testimonials as $key => $testimonial) {
                        ?>
                        <div class="col-md-12 col-sm-12">
                            <div class="courses-box">
                                <div class="course-inner"><a class="course-subject" href="#"></a>
                                    <?php echo $testimonial['description']; ?>
                                    <!--
                                    <h4>Gopal Viswanath</h4>
                                    <p>"We believe that school is the appropriate medium to bring our young generation with proper education, knowledge and skill. Keeping this in view, SNG International started its journey in 2018. We must appreciate the efforts of this school for the exemplary hard work and motivation of the principle, teachers and the staff."</p>
                                    -->
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                              </div>
</div>
</div>
</div>
</section>
    <section class="spaceb40 spacet40 ">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2 text-center">
                    <h2>Photo Gallery</h2>
                    <div class="divider">&nbsp;</div>
                </div>
                <!--./col-md-8-->

                <div class="teamstaff">
                    <div class="row">
                        <div class="owl-carousel photogallery">
                            <?php
                              foreach ($gallery as $key => $gal) {
                              ?>
                            <div class="col-md-12 col-sm-12">
                                <div class="eventbox">
                                    <a href="<?php echo site_url($gal['url']); ?>">
                                        <?php
                                      if ($gal['feature_image'] == "") {
                                          $feature_image = base_url('uploads/gallery/gallery_default.png');
                                      } else {
                                          $feature_image = $gal['feature_image'];
                                      }
                                      ?>
                                        <img src="<?php echo $feature_image; ?>" alt="" title="">
                                        <div class="evcontentfix">
                                            <h3>
                                                <?php echo $gal['title']; ?>
                                            </h3>
                                        </div>
                                        <!--./around20-->
                                    </a>
                                </div>
                                <!--./eventbox-->
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                    <!--./staff-->
                </div>
                <!--./teamstaff-->
            </div>
            <!--./row-->
        </div>
        <!--./container-->
    </section>


    </div>

    <?php  } } ?>

    <?php  echo $footer; ?>
    <div class="modal" tabindex="-1" role="dialog" id="schoolvideo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <p style="float:left;"><h5 style="float:left; class="modal-ti">School Video</h5></p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          
      <iframe width="100%" height="315" src="https://www.youtube.com/embed/BFmqK-zET7U?autoplay=1&mute=1&enablejsapi=1&rel=0"  allow='autoplay' title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <script src="<?php echo $base_assets_url; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo $base_assets_url; ?>js/owl.carousel.min.js"></script>

    <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.waypoints.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.counterup.min.js"></script>
    <script src="<?php echo $base_assets_url; ?>js/ss-lightbox.js"></script>
    <script src="<?php echo $base_assets_url; ?>js/custom.js"></script>
    <!-- Include Date Range Picker -->
    <script type="text/javascript"
        src="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript">
        $("marquee").hover(function () {
            this.stop();
        }, function () {
            this.start();
        });
        $(function () {
            jQuery('img.svg').each(function () {
                var $img = jQuery(this);
                var imgID = $img.attr('id');
                var imgClass = $img.attr('class');
                var imgURL = $img.attr('src');

                jQuery.get(imgURL, function (data) {
                    // Get the SVG tag, ignore the rest
                    var $svg = jQuery(data).find('svg');

                    // Add replaced image's ID to the new SVG
                    if (typeof imgID !== 'undefined') {
                        $svg = $svg.attr('id', imgID);
                    }
                    // Add replaced image's classes to the new SVG
                    if (typeof imgClass !== 'undefined') {
                        $svg = $svg.attr('class', imgClass + ' replaced-svg');
                    }

                    // Remove any invalid XML tags as per http://validator.w3.org
                    $svg = $svg.removeAttr('xmlns:a');

                    // Check if the viewport is set, else we gonna set it if we can.
                    if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                        $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                    }

                    // Replace image with new SVG
                    $img.replaceWith($svg);

                }, 'xml');

            });
        });

    </script>

</body>

</html>