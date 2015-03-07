<?php
/*
Plugin Name: Simple Vote Me
Plugin URI: https://wordpress.org/plugins/simple-vote-me/
Description: This plugin add cute and simple votes for Wordpress post.
Author: Gonzalo Torreras
Version: 1.1.1
Author URI: http://www.gonzalotorreras.com
*/

    define('SIMPLEVOTEMESURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
    define('SIMPLEVOTEMEPATH', WP_PLUGIN_DIR."/".dirname( plugin_basename( __FILE__ ) ) );
    
    add_action( 'admin_init', 'gt_simplevoteme_admin_init' );
    add_action( 'admin_menu', 'gt_simplevoteme_admin_menu' );

   //init function
    function gt_simplevoteme_admin_init(){
        
        add_action('admin_init', 'gt_simplevoteme_admin_options');
    }
    
    //page admin
    function gt_simplevoteme_admin_menu(){
        if(is_admin()):
            $page = add_submenu_page (
                                        'options-general.php',
                                        __('Simple Vote Me', 'gtsimplevoteme'),
                                        __('Simple Vote Me', 'gtsimplevoteme'),
                                        'manage_options',
                                        __FILE__,
                                        'gt_simplevoteme_page_admin','gt_simplevoteme_page_admin');
          
            //add CSS only for admin page
            add_action ( 'admin_print_styles-' . $page, 'gt_simplevoteme_admin_style');
        endif;
    }
   
   function gt_simplevoteme_admin_style(){
        wp_enqueue_style( 'simplevotemestyleadmin' );
   }
   
   
   //page admin
   function gt_simplevoteme_page_admin(){
    global $blog_id;
        if( isset( $_POST['submit'] ) ){
            
            update_option( 'gt_simplevoteme_title' , $_POST[ 'gt_simplevoteme_title' ] );
            update_option( 'gt_simplevoteme_auto_insert_content' , $_POST[ 'gt_simplevoteme_auto_insert_content' ] );
            update_option( 'gt_simplevoteme_position' , $_POST[ 'gt_simplevoteme_position' ] );
            update_option( 'gt_simplevoteme_only_login' , $_POST[ 'gt_simplevoteme_only_login' ] );
            update_option( 'gt_simplevoteme_custom_css' , $_POST[ 'gt_simplevoteme_custom_css' ] );
            update_option( 'gt_simplevoteme_results' , $_POST[ 'gt_simplevoteme_results' ] );
            update_option( 'gt_simplevoteme_custom_img' , $_POST[ 'gt_simplevoteme_custom_img' ] );
            update_option( 'gt_simplevoteme_custom_img_good' , $_POST[ 'gt_simplevoteme_custom_img_good' ] );
            update_option( 'gt_simplevoteme_custom_img_neutral' , $_POST[ 'gt_simplevoteme_custom_img_neutral' ] );
            update_option( 'gt_simplevoteme_custom_img_bad' , $_POST[ 'gt_simplevoteme_custom_img_bad' ] );
            
            if($_POST['gt_simplevoteme_reset'])
                gt_simplevoteme_reset(1);
        }
    ?>
        <div class="wrap">
            <h2> Simple Vote me </h2>
            
            <div class="rateOnWPORG">
                <h4>Rate 'Simple-Vote-Me' on WP.org</h4>
                <div class="rate">
                    <div class="wporg-ratings rating-stars">
                        <a target="_blank" href="/support/view/plugin-reviews/simple-vote-me?rate=1#postform" data-rating="1" title="">
                            <span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/simple-vote-me?rate=2#postform" data-rating="2" title="">
                            <span class="dashicons dashicons-star-empty" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/simple-vote-me?rate=3#postform" data-rating="3" title="">
                            <span class="dashicons dashicons-star-empty" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org//support/view/plugin-reviews/simple-vote-me?rate=4#postform" data-rating="4" title="">
                            <span class="dashicons dashicons-star-empty" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org//support/view/plugin-reviews/simple-vote-me?rate=5#postform" data-rating="5" title="">
                            <span class="dashicons dashicons-star-empty" style="color:#e6b800 !important;"></span>
                        </a>
                    </div>
                </div>
                <style>
                    .rateOnWPORG{float: right;margin-right: 4em;margin-bottom: -5em;}
                    .rating-stars a{text-decoration:none;}
                </style>
                <script>
                    jQuery(document).ready( function($) {
                        $('.rating-stars').find('a').hover(
                            function() {
                                $(this).nextAll('a').children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                $(this).prevAll('a').children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                $(this).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                            }, function() {
                                var rating = $('input#rating').val();
                                if (rating) {
                                    var list = $('.rating-stars a');
                                    list.children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                    list.slice(0, rating).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                }
                            }
                        );
                    });
                </script>
            </div>
            
            <form method="post" action="" id="simplevoteme">
                
                <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __('Auto Insert Content') ; ?></th>
                    <td>
                    <?php $title = get_option('gt_simplevoteme_title'); ?>
                        <input name="gt_simplevoteme_title" value="<?php if($title) echo $title; ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Auto Insert Content') ; ?></th>
                    <td>
                    <?php $auto = get_option('gt_simplevoteme_auto_insert_content'); ?>
                    <select id="auto" name="gt_simplevoteme_auto_insert_content" >
                        <option value="0" <?php if(!$auto) echo "selected"; ?>>No</option>
                        <option value="1" <?php if($auto == 1) echo "selected"; ?>>Only in post </option>
                        <option value="2" <?php if($auto == 2) echo "selected"; ?>>Only in pages </option>
                        <option value="3" <?php if($auto == 3) echo "selected"; ?>>Post and Pages </option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Position of the poll (on content)') ; ?><small>()Only if you have selected the auto insert).</small></th>
                    <td>
                    <?php $position = get_option('gt_simplevoteme_position'); ?>
                    <select id="position" name="gt_simplevoteme_position" >
                        <option value="0" <?php if(!$position) echo "selected"; ?>>After the Content</option>
                        <option value="1" <?php if($position ==  1) echo "selected"; ?>>Before the content </option>
                        <option value="2" <?php if($position ==  2) echo "selected"; ?>>Both, before and after </option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Only for registered users'); ?>
                     <td>
                     <?php $login = get_option('gt_simplevoteme_only_login'); ?>
                     <label for="only_login_yes"><?php echo __('Yes'); ?></label>
                     <input type="radio" id="only_login_yes" name="gt_simplevoteme_only_login" value="1"<?php if ($login) echo "checked"; ?> />
                     <label for="only_login_no"> <?php echo __('No'); ?></label>
                     <input type="radio" id="only_login_yes" name="gt_simplevoteme_only_login" value="0"<?php if (!$login) echo "checked"; ?> />
                     </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Show Results?'); ?>
                    <td>
                        <?php $results= get_option('gt_simplevoteme_results'); ?>
                        <select id="results" name="gt_simplevoteme_results" >
                            <option value="1" <?php if($results) echo "selected"; ?>><?php echo __('Always'); ?></option>
                            <option value="2" <?php if($results == 2) echo "selected"; ?>><?php echo __('After vote'); ?></option>
                            <option value="0" <?php if(!$results ) echo "selected"; ?>><?php echo __('Never') ; ?></option>
                        </select>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom CSS'); ?>
                    <td>
                        <?php $css= get_option('gt_simplevoteme_custom_css'); ?>
                        <textarea name="gt_simplevoteme_custom_css"><?php if($css) echo $css ; ?></textarea>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom Images'); ?>
                    <td>
                        <?php $customImg = get_option('gt_simplevoteme_custom_img'); ?>
                        <select name="gt_simplevoteme_custom_img">
                            <option value="0" <?php if(!$customImg) echo "selected" ; ?>><?php echo __('No'); ?></option>
                            <option value="1" <?php if($customImg) echo "selected" ; ?>><?php echo __('Yes'); ?></option>
                        </select>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom Image for Good'); ?>
                    <td>
                        <?php $customImgG = get_option('gt_simplevoteme_custom_img_good'); ?>
                        <input name="gt_simplevoteme_custom_img_good" value="<?php if($customImgG) echo $customImgG ; ?>"/>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom Image for Neutral'); ?>
                    <td>
                        <?php $customImgN = get_option('gt_simplevoteme_custom_img_neutral'); ?>
                        <input name="gt_simplevoteme_custom_img_neutral" value="<?php if($customImgN) echo $customImgN ; ?>"/>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom Image for Bad'); ?>
                    <td>
                        <?php $customImgB = get_option('gt_simplevoteme_custom_img_bad'); ?>
                        <input name="gt_simplevoteme_custom_img_bad" value="<?php if($customImgB) echo $customImgB ; ?>"/>
                    </td>
                    </th>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Reset Votes'); ?>
                    <td>
                        <label for="reset">Reset al votes?</label>
                        <select id="reset" name="gt_simplevoteme_reset">
                            <option value="0"><?php echo __('No' ); ?></option>
                            <option value="1"><?php echo __('Yes'); ?></option>
                        </select>
                    </td>
                    <th>
                </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <script>
                  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                  ga('create', 'UA-29011201-3', 'auto');
                  ga('send', 'pageview');
            </script>
        </div>
   <?php 
   }
   
   //page admin options
   function gt_simplevoteme_admin_options(){
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_title');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_auto_insert_content');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_position');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_only_login');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_css');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_results');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_good');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_neutral');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_bad');
   }
    
    function gt_simplevoteme_reset($reset = false){
        if($reset){
            
            $the_query = new WP_Query( 'meta_key=_simplevotemetotal&amp;orderby=meta_value_num&amp;order=DESC&amp;' );
            // The Loop
            while ( $the_query->have_posts() ) : $the_query->the_post();
                update_post_meta(get_the_ID(), '_simplevotemetotal', 0);
                update_post_meta(get_the_ID(), '_simplevotemepositive', 0);
                update_post_meta(get_the_ID(), '_simplevotemenegative', 0);
                update_post_meta(get_the_ID(), '_simplevotemeneutral', 0);
            endwhile;
            wp_reset_postdata();
        }
    }
    function gt_simplevoteme_enqueuescripts(){
    
        wp_register_style( 'simplevotemestyle', SIMPLEVOTEMESURL.'/css/simplevoteme.css' );
        
        wp_register_style( 'simplevotemestyleadmin', SIMPLEVOTEMESURL .'/css/simplevotemeadmin.css' );
        
        wp_enqueue_script('gtsimplevoteme', SIMPLEVOTEMESURL.'/js/simple-vote-me.js', array('jquery'));
        
        
        
        wp_localize_script( 'gtsimplevoteme', 'gtsimplevotemeajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        
        $css = get_option('gt_simplevoteme_custom_css');
        
        if(!$css)
            wp_enqueue_style( 'simplevotemestyle' );
        
    }
    add_action('wp_enqueue_scripts', 'gt_simplevoteme_enqueuescripts');
        
   
   
    function gt_simplevoteme_getimgvote($type){
        $custom = get_option('gt_simplevoteme_custom_img');
        if(!$custom){
            return "<img src='". SIMPLEVOTEMESURL ."/img/$type.png'/>";
        } else{
            $customImg = get_option("gt_simplevoteme_custom_img_$type");
            return "<img src='$customImg'/>";
        }
    }

    function gt_simplevoteme_getvotelink($noLinks = false, $tipo = 'h'){
        $votemelink = "";
        
        if(!$noLinks)
            $post_ID = get_the_ID();
        else if (!get_the_ID())
            $post_ID = $_POST['postid'];
        else
            $post_ID = $_POST['postid'];
        
        $votemePositive = get_post_meta($post_ID, '_simplevotemepositive', true) != '' ? get_post_meta($post_ID, '_simplevotemepositive', true) : '0';
        
        $votemeNeutral = get_post_meta($post_ID, '_simplevotemeneutral', true) != '' ? get_post_meta($post_ID, '_simplevotemeneutral', true) : '0';
        
        $votemeNegative = get_post_meta($post_ID, '_simplevotemenegative', true) != '' ? get_post_meta($post_ID, '_simplevotemenegative', true) : '0';
        
        $votemeTotal = get_post_meta($post_ID, '_simplevotemetotal', true) != '' ? get_post_meta($post_ID, '_simplevotemetotal', true) : '0';
        
        $votemeResults = get_option('gt_simplevoteme_results');
        if($votemeResults){
            if($votemeResults == 1 || ($votemeResults == 2 && $noLinks) ){
            
                if ($votemeTotal != 0 || $votemeTotal != '' ){
                    
                    if($votemeNegative > 0)
                        $votemePercentNegative = round (  $votemeNegative / $votemeTotal, 2) * 100 . "%<small> ($votemeNegative) </small>";
                    else
                        $votemePercentNegative = 0 . "<small> ($votemeNegative) </small>";
                    
                    if($votemeNeutral > 0)
                        $votemePercentNeutral  = round (  $votemeNeutral / $votemeTotal, 2 ) * 100 . "%<small> ($votemeNeutral) </small>";
                    else 
                        $votemePercentNeutral = 0 . "<small> ($votemeNeutral) </small>";
                    
                    if ($votemePositive > 0)
                        $votemePercentPositive = round (  $votemePositive / $votemeTotal, 2) * 100 . "%<small> ($votemePositive) </small>";
                    else
                        $votemePercentPositive = 0 . "<small> ($votemePositive) </small>";
                    
                } else{
                        $votemePercentNegative = "";
                        $votemePercentNeutral = "";
                        $votemePercentPositive = "";
                }
            } else{
                    $votemePercentNegative = "";
                    $votemePercentNeutral = "";
                    $votemePercentPositive = "";
                }
            
        } else{
            
            $votemePercentNegative = "";
            $votemePercentNeutral = "";
            $votemePercentPositive = "";
        }
        
        if(!$noLinks){
        
            $linkPositivo = '<a onclick="simplevotemeaddvote('.$post_ID.', 1);">'. gt_simplevoteme_getimgvote("good") .'</a>';
            $linkNegativo = '<a onclick="simplevotemeaddvote('.$post_ID.', 0);">'. gt_simplevoteme_getimgvote("bad") .'</a>';
            $linkNeutral  = '<a onclick="simplevotemeaddvote('.$post_ID.', 2);">'. gt_simplevoteme_getimgvote("neutral") .'</a>';
        } else{
            $linkPositivo = gt_simplevoteme_getimgvote("good");
            $linkNegativo = gt_simplevoteme_getimgvote("bad");
            $linkNeutral  = gt_simplevoteme_getimgvote("neutral");
        }
        
        $title = get_option('gt_simplevoteme_title');
        
        $votemelink = "<div class='simplevotemeWrapper $tipo' id='simplevoteme-$post_ID' >$title";
        $votemelink .= "<span class='bad'>$linkNegativo <span class='result'>$votemePercentNegative</span></span>";
        $votemelink .= "<span class='neutro'>$linkNeutral <span class='result'>$votemePercentNeutral</span></span>";
        $votemelink .= "<span class='good'>$linkPositivo <span class='result'>$votemePercentPositive</span></span>";
        $votemelink .= "</div>";
        
        $result  = $votemelink;
        
        $css = get_option('gt_simplevoteme_custom_css');
        
        if($css)
            $result .= "<style>".$css."</style>";
        
            
            
        return $result;
    }
     
    function gt_simplevoteme_printvotelink_auto($content){
        
        $auto  = get_option('gt_simplevoteme_auto_insert_content');
        
            if(!$auto)
                return($content);
        
        $login = get_option('gt_simplevoteme_only_login'); //after auto, do not waste resources if is not necessary :)
        
        
            if( $login && !is_user_logged_in() )
                return($content);
            
        $position = get_option('gt_simplevoteme_position');//after login, do not waste resources if is not necessary :)
            
            if( ($auto == 1 || $auto == 3 ) && is_single() ){//if is only post(1) or post&page(3)
                if(!$position)
                    return $content.gt_simplevoteme_getvotelink();
                
                else if($position == 1)
                    return gt_simplevoteme_getvotelink().$content;
                
                else if ($position == 2){
                    $linksVote = gt_simplevoteme_getvotelink(); //launch just once
                    return $linksVote.$content.$linksVote;
                } else
                    return $content;//nothing expected
                    
            }
            
        
            else if( ($auto == 2 || $auto == 3) && is_page() ){//if is only page(2) or post&page(3)
                if(!$postion)
                    return $content.gt_simplevoteme_getvotelink();
                
                else if($position == 1)
                    return gt_simplevoteme_getvotelink().$content;
                
                else if ($position == 3){
                    $linksVote = gt_simplevoteme_getvotelink(); //launch just once
                    return $linksVote.$content.$linksVote;
                } else
                    return $content;//nothing expected
            }
                
            else
                return($content); //nothing expected
    
        
        
            
    }
    add_filter('the_content', 'gt_simplevoteme_printvotelink_auto');
    
    

    function gt_simplevoteme_aftervote(){
        $linkPositivo = gt_simplevoteme_getimgvote("good");
        $linkNegativo = gt_simplevoteme_getimgvote("bad");
        $linkNeutral  = gt_simplevoteme_getimgvote("neutral");
    }

    /** Ajax **/
        function gt_simplevoteme_addpositive(){
            $results = '';
            global $wpdb;
            $post_ID = $_POST['postid'];
            $votemecount = get_post_meta($post_ID, '_simplevotemepositive', true) != "" ? get_post_meta($post_ID, '_simplevotemepositive', true) : "0";
            $votemecountNewPositive = $votemecount + 1;
            update_post_meta($post_ID, '_simplevotemepositive', $votemecountNewPositive);
     
            $votemeTotal = get_post_meta($post_ID, '_simplevotemetotal', true) != "" ? get_post_meta($post_ID, '_simplevotemetotal', true) : "0";
            
            $votemecountNewTotal = $votemeTotal +1;
            update_post_meta($post_ID, '_simplevotemetotal', $votemecountNewTotal);
            
            $result = gt_simplevoteme_getvotelink(1);
            
     
            // Return the String
            die($result);
        }
     
            // creating Ajax call for WordPress
            add_action( 'wp_ajax_nopriv_simplevoteme_addpositive', 'gt_simplevoteme_addpositive' );
            add_action( 'wp_ajax_simplevoteme_addpositive', 'gt_simplevoteme_addpositive' );

        //negative
        function gt_simplevoteme_addnegative(){
            $results = '';
            global $wpdb;
            $post_ID = $_POST['postid'];
            $votemecount = get_post_meta($post_ID, '_simplevotemenegative', true) != '' ? get_post_meta($post_ID, '_simplevotemenegative', true) : '0';
            $votemecountNewNegative = $votemecount + 1;
            update_post_meta($post_ID, '_simplevotemenegative', $votemecountNewNegative);
     
     
            $votemeTotal = get_post_meta($post_ID, '_simplevotemetotal', true) != '' ? get_post_meta($post_ID, '_simplevotemetotal', true) : '0';
            
            $votemecountNewTotal = $votemeTotal +1;
            update_post_meta($post_ID, '_simplevotemetotal', $votemecountNewTotal);
            
            
            $result = gt_simplevoteme_getvotelink(1);
            
     
            // Return the String
            die($result);
        }
     
            // creating Ajax call for WordPress
            add_action( 'wp_ajax_nopriv_simplevoteme_addnegative', 'gt_simplevoteme_addnegative' );
            add_action( 'wp_ajax_simplevoteme_addnegative', 'gt_simplevoteme_addnegative' );
            
        
        //neutral
        function gt_simplevoteme_addneutral(){
            $results = '';
            global $wpdb;
            $post_ID = $_POST['postid'];
            $votemecount = get_post_meta($post_ID, '_simplevotemeneutral', true) != '' ? get_post_meta($post_ID, '_simplevotemeneutral', true) : '0';
            $votemecountNewNeutral = $votemecount + 1;
            update_post_meta($post_ID, '_simplevotemeneutral', $votemecountNewNeutral);
     
            
            $votemeTotal = get_post_meta($post_ID, '_simplevotemetotal', true) != '' ? get_post_meta($post_ID, '_simplevotemetotal', true) : '0';
            
            $votemecountNewTotal = $votemeTotal +1;
            update_post_meta($post_ID, '_simplevotemetotal', $votemecountNewTotal);
            
            
            $result = gt_simplevoteme_getvotelink(1);

            // Return the String
            die($result);
        }
     
            // creating Ajax call for WordPress
            add_action( 'wp_ajax_nopriv_simplevoteme_addneutral', 'gt_simplevoteme_addneutral' );
            add_action( 'wp_ajax_simplevoteme_addneutral', 'gt_simplevoteme_addneutral' );
            
    /** Show the info in admin panel **/
    function gt_simplevoteme_extra_post_columns( $columns ) {
        $columns[ 'simplevotemetotal' ] = __( 'Votes' );
        $columns[ 'simplevotemenegative' ] = __( ':(' );
        $columns[ 'simplevotemeneutral' ] = __( ':|' );
        $columns[ 'simplevotemepositive' ] = __( ':)' );
       
        return $columns;
    }
    add_filter( 'manage_edit-post_columns', 'gt_simplevoteme_extra_post_columns' );

    function gt_simplevoteme_positive_post_column_row( $column ) {
        if ( $column != 'simplevotemepositive' )
        return;
     
        global $post;
        $post_id = $post->ID;
        $votemecount = get_post_meta($post_id, '_simplevotemepositive', true) != '' ? get_post_meta($post_id, '_simplevotemepositive', true) : '0';
        echo $votemecount;
     
    }
    add_action( 'manage_posts_custom_column', 'gt_simplevoteme_positive_post_column_row', 10, 2 );

    function gt_simplevoteme_negative_post_column_row( $column ) {
        if ( $column != 'simplevotemenegative' )
        return;
     
        global $post;
        $post_id = $post->ID;
        $votemecount = get_post_meta($post_id, '_simplevotemenegative', true) != '' ? get_post_meta($post_id, '_simplevotemenegative', true) : '0';
        echo $votemecount;
     
    }
    add_action( 'manage_posts_custom_column', 'gt_simplevoteme_negative_post_column_row', 10, 2 );

    function gt_simplevoteme_neutral_post_column_row( $column ) {
        if ( $column != 'simplevotemeneutral' )
        return;
     
        global $post;
        $post_id = $post->ID;
        $votemecount = get_post_meta($post_id, '_simplevotemeneutral', true) != '' ? get_post_meta($post_id, '_simplevotemeneutral', true) : '0';
        echo $votemecount;
     
    }
    add_action( 'manage_posts_custom_column', 'gt_simplevoteme_neutral_post_column_row', 10, 2 );

    function gt_simplevoteme_total_post_column_row( $column ) {
        if ( $column != 'simplevotemetotal' )
        return;
     
        global $post;
        $post_id = $post->ID;
        $votemecount = get_post_meta($post_id, '_simplevotemetotal', true) != '' ? get_post_meta($post_id, '_simplevotemetotal', true) : '0';
        echo $votemecount;
     
    }
    add_action( 'manage_posts_custom_column', 'gt_simplevoteme_total_post_column_row', 10, 2 );



    /** Snippet [simple-vote]**/
    function gt_shortcode_simplevoteme($atributos){
        $atts = shortcode_atts( array(
                    'type' => 'h',
                ), $atributos);
        
        
        $login = get_option('gt_simplevoteme_only_login'); 
        
        if( $login && !is_user_logged_in() )
            return('');
            
        $voteme = gt_simplevoteme_getvotelink(0, $atts["type"]);
        
        ob_start();
            echo $voteme;
        return ob_get_clean();
        
    }
    add_shortcode ('simplevoteme', 'gt_shortcode_simplevoteme');

    
    //Order by total votes
    function gt_simplevoteme_get_highest_voted_posts($numberofpost){
        $output = '';
        $the_query = new WP_Query( 'meta_key=_simplevotemetotal&amp;orderby=meta_value_num&amp;order=DESC&amp;posts_per_page='.$numberofpost );
        // The Loop
        while ( $the_query->have_posts() ) : $the_query->the_post();
        $output .= '<li>';
        $output .= '<a href="'.get_permalink(). '" rel="bookmark">'.get_the_title().'('.get_post_meta(get_the_ID(), '_simplevotemetotal', true).')'.'</a> ';
        $output .= '</li>';
        endwhile;
        wp_reset_postdata();
        return $output;
    }

    
    //widget Ranking
    class GTSimpleVoteMeTopVotedWidget extends WP_Widget {
    
        function GTSimpleVoteMeTopVotedWidget() {
            // widget actual processes
            $widget_ops = array('classname' => 'GTSimpleVoteMeTopVotedWidget', 'name' =>'Ranking Simple Vote me', 'description' => 'Widget for top voted Posts.' );
            $this->WP_Widget('GTSimpleVoteMeTopVotedWidget','GTSimpleVoteMeTopVotedWidget', $widget_ops);
        }
        function form($instance) {
            // outputs the options form on admin
            $defaults = array( 'title' => 'Top Voted Posts', 'numberofposts' => '5' );
            $instance = wp_parse_args( (array) $instance, $defaults );
         
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo 'Title:'; ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id( 'numberofposts' ); ?>"><?php echo 'Number of Posts'; ?></label>
            <input id="<?php echo $this->get_field_id( 'numberofposts' ); ?>" name="<?php echo $this->get_field_name( 'numberofposts' ); ?>" value="<?php echo $instance['numberofposts']; ?>" class="widefat" />
            </p>
         
            <?php
     
        }
 
        function update($new_instance, $old_instance) {
            // processes widget options to be saved
         
            $instance = $old_instance;
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['numberofposts'] = $new_instance['numberofposts'];
            return $instance;
        }
 
        function widget($args, $instance) {
            // outputs the content of the widget
            extract( $args );
            $title = apply_filters('widget_title', $instance['title'] );
            echo $before_widget;
            if ( $title )
            echo $before_title . $title . $after_title;
         
            echo '<ul>';
            echo gt_simplevoteme_get_highest_voted_posts($instance['numberofposts']);
            echo '</ul>';
            echo $after_widget;
        }
 
}
 
    function gt_simplevoteme_ranking_widget_init() {
     
        // Check for the required API functions
        if ( !function_exists('register_widget') )
        return;
     
        register_widget('GTSimpleVoteMeTopVotedWidget');
    }
 
    add_action('widgets_init', 'gt_simplevoteme_ranking_widget_init');

    
    
    //widget Vote
    class GTSimpleVoteMeWidget extends WP_Widget {
    
        function GTSimpleVoteMeWidget() {
            // widget actual processes
            $widget_ops = array('classname' => 'GTSimpleVoteMeWidget', 'name' =>'Simple Vote me Widget', 'description' => 'Widget for vote Posts.' );
            $this->WP_Widget('GTSimpleVoteMedWidget','GTSimpleVoteMeWidget', $widget_ops);
        }
        function form($instance) {
            // outputs the options form on admin
            $defaults = array( 'title' => 'Vote me!', 'type' => 'v' );
            $instance = wp_parse_args( (array) $instance, $defaults );
         
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo 'Title:'; ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php echo __('Type of Widget'); ?></label>
            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat" >
            <option value="v" <?php if ($instance['type'] == "v") echo "selected"; ?>><?php echo __('Vertical'); ?></option>
            <option value="h" <?php if ($instance['type'] == "h") echo "selected"; ?>><?php echo __('Horizontal'); ?></option>
            </select>
            </p>
         
            <?php
     
        }
 
        function update($new_instance, $old_instance) {
            // processes widget options to be saved
         
            $instance = $old_instance;
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['type'] = $new_instance['type'];
            return $instance;
        }
 
        function widget($args, $instance) {
            // outputs the content of the widget
            extract( $args );
            $title = apply_filters('widget_title', $instance['title'] );
            echo $before_widget;
            if ( $title )
            echo $before_title . $title . $after_title;
         
            echo do_shortcode('[simplevoteme type="'. $instance['type'] .'"]');
            
            echo $after_widget;
        }
 
}
 
    function gt_simplevoteme_widget_init() {
     
        // Check for the required API functions
        if ( !function_exists('register_widget') )
        return;
     
        register_widget('GTSimpleVoteMeWidget');
    }
 
    add_action('widgets_init', 'gt_simplevoteme_widget_init');