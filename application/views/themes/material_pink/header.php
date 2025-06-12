    
  
    <header>
        <div class="container">
            <div style="display:none;">
                <?php 
                // print_r($result);
                ?>

            </div>
            <div class="row">
                <div class="col-md-7 col-sm-7">
                    <div style="display: flex;align-items:center">
                        <a class="logo" href="<?php echo base_url(); ?>"><img src="<?php echo base_url($front_setting->logo); ?>" alt=""></a>
                        <div style="padding-left:10px;">
                            <span style="font-family: Roboto, serif;color:#bd0745;font-size:20px;font-weight:bold"><?php echo $school_setting->name?></span><br>
                            <span style="font-family: Roboto, serif;color:#1b0972;font-size:15px;font-weight:bold">C.B.S.E. Affiliation No. : <?php echo $result->affilation_no; ?></span>
                        </div>
                    </div>
                </div><!--./col-md-4-->
                <div class="col-md-5 col-sm-5">
                    <ul class="header-extras">
                        <li><i class="fa fa-phone i-plain"></i><div class="he-text"><?php echo $this->lang->line('call_us'); ?><span><?php echo $school_setting->phone; ?></span></div></li>
                        <li><a class="complainbtn" href="<?php echo site_url('site/parentlogin') ?>">Login</a></li>
                       <!-- <li><i class="fa fa-pencil-square-o i-plain"></i>
                           <div class="he-text"><?php echo $this->lang->line('feedback'); ?><span><a href="<?php echo site_url('page/complain') ?>"><?php echo $this->lang->line('complain'); ?></a></span>
                           </div>
                       </li> -->
                    </ul>
                </div><!--./col-md-8-->
            </div><!--./row-->
        </div><!--./container-->
    <div class="navborder">
        <div class="container">
            <div class="row">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-3">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-collapse-3">
                        <?php 
                        if($layout_type){
                        ?>
                        <ul class="nav navbar-nav">
                            <?php
                            foreach ($main_menus as $menu_key => $menu_value) {
                            
                                $submenus = false;
                                $cls_menu_dropdown = "";
                                $menu_selected = "";
                                if ($menu_value['page_slug'] == $active_menu) {
                                    $menu_selected = "active";
                                }
                                if (!empty($menu_value['submenus'])) {
                                    $submenus = true;
                                    $cls_menu_dropdown = "dropdown";
                                }
                                ?>

                                <li class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>" >
                                    <?php
                                    if (!$submenus) {
                                        $top_new_tab = '';
                                        $url = '#';
                                        if ($menu_value['open_new_tab']) {
                                            $top_new_tab = "target='_blank'";
                                        }
                                        if ($menu_value['ext_url']) {
                                            $url = $menu_value['ext_url_link'];
                                        } else {
                                            $url = site_url($menu_value['page_url']);
                                        }
                                        ?>

                                        <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $menu_value['menu']; ?></a>

                                        <?php
                                    } else {
                                        $child_new_tab = '';
                                        $url = '#';
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu_value['menu']; ?> <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <?php
                                            foreach ($menu_value['submenus'] as $submenu_key => $submenu_value) {
                                                if ($submenu_value['open_new_tab']) {
                                                    $child_new_tab = "target='_blank'";
                                                }
                                                if ($submenu_value['ext_url']) {
                                                    $url = $submenu_value['ext_url_link'];
                                                } else {
                                                    $url = site_url($submenu_value['page_url']);
                                                }
                                                ?>
                                                <li><a href="<?php echo $url; ?>" <?php echo $child_new_tab; ?> ><?php echo $submenu_value['menu'] ?></a></li>
                                                <?php
                                            }
                                            ?>

                                        </ul>

                                        <?php
                                    }
                                    ?>


                                </li>
                                <?php
                            }
                            ?>


                        </ul>
<?php
}else{
    ?>
  <ul class="nav navbar-nav">
                           
                <li class="active" >
            <a href="<?php echo site_url('online_admission'); ?>" >Online Admission --r</a>
                                  

                                </li>
                        </ul>
                                <?php
                            


}
 ?>
                      
                    </div><!-- /.navbar-collapse -->
                </nav><!-- /.navbar -->
            </div>
        </div>   
    </div> 

</header>
</div>