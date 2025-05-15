</div>
    <!-- Footer -->
    <div class="footer clearfix">
        <div class="pull-left">&copy; 2013. Londinium Admin Template by <a href="http://themeforest.net/user/Kopyov">Eugene Kopyov</a></div>
    	<div class="pull-right icons-group">
    		<a href="#"><i class="icon-screen2"></i></a>
    		<a href="#"><i class="icon-balance"></i></a>
    		<a href="#"><i class="icon-cog3"></i></a>
    	</div>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>application.js"></script>
         <?php
        if(isset($_layoutParam['route_js']) && count($_layoutParam['route_js'])):
  ?>
  <?php 
            for($i=0; $i<count($_layoutParam['route_js']); $i++):?>
                <script type="text/javascript" src="<?php echo $_layoutParam['js'][$i];?>"></script>
  <?php 
            endfor;
        endif;
  ?>
    </div>
    </body>
    </html>