<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 $website_type = get_theme_mod('website_type');
 
 if ($website_type != 3) {
 ?>
        <div class="siegel" aria-hidden="true" role="presentation">
             <?php fau_use_svg("fau-siegel",320,320,"hero-siegel"); ?>
        </div>
 <?php } ?>