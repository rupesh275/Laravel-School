<style>
* {box-sizing: border-box}

/* Set height of body and the document to 100% */
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial;
}

/* Style tab links */
.tablink {
  background-color: #555;
  color: white;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  font-size: 17px;
  width: 25%;
}

.tablink:hover {
  background-color: #777;
}

/* Style the tab content (and add height:100% for full page content) */
.tabcontent {
  color: black;
  display: none;
  padding: 100px 20px;
  height: 100%;
  border:2px;
}


</style>
<script src="<?php echo base_url('backend/dist/js/moment.min.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
        <div class="container">
            <div class="row">
                
                <div class="col-md-8 col-sm-12 col-md-offset-2  pb30">
                    <br>
                    <br>
                    <button class="tablink" onclick="openPage('Home', this, 'red')" id="<?php if($ids=="preprimary") echo "defaultOpen"; else echo ""; ?>" >Pre-Primary</button>
                    <button class="tablink" onclick="openPage('News', this, 'green')" id="<?php if($ids=="primary") echo "defaultOpen"; else echo "";  ?>">Primary</button>
                    <button class="tablink" onclick="openPage('Contact', this, 'blue')" id="<?php if($ids=="secondary") echo "defaultOpen"; else echo "";  ?>">Secondary</button>

                    
                    <div id="Home" class="tabcontent">
                        <?php echo $sec_pre_primary['description']; ?>                      
                    </div>
                    
                    <div id="News" class="tabcontent">
                      <?php echo $sec_primary['description']; ?>
                    </div>
                    
                    <div id="Contact" class="tabcontent">
                      <?php echo $sec_secondary['description']; ?>
                    </div>
                    <p style="text-align: justify;"></p>
                    <div class="divider">&nbsp;</div>
                </div> 
            </div>
        </div>

    <section class="facilitieswrapper-co">
        <div class="container">
            
        </div>
        <!--./container-->
    </section>
<script>
function openPage(pageName,elmnt,color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
    
<script type="text/javascript">
var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'M' => 'MMM', 'Y' => 'YYYY']) ?>';
</script>