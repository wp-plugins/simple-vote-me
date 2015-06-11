<?php

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
            update_option( 'gt_simplevoteme_auto_insert_home' , $_POST[ 'gt_simplevoteme_auto_insert_home' ] );
            update_option( 'gt_simplevoteme_position' , $_POST[ 'gt_simplevoteme_position' ] );
            update_option( 'gt_simplevoteme_only_login' , $_POST[ 'gt_simplevoteme_only_login' ] );
            update_option( 'gt_simplevoteme_default_css' , $_POST[ 'gt_simplevoteme_default_css' ] );
            update_option( 'gt_simplevoteme_custom_css' , $_POST[ 'gt_simplevoteme_custom_css' ] );
            update_option( 'gt_simplevoteme_results' , $_POST[ 'gt_simplevoteme_results' ] );
            update_option( 'gt_simplevoteme_results_type' , $_POST[ 'gt_simplevoteme_results_type' ] );
            update_option( 'gt_simplevoteme_custom_img' , $_POST[ 'gt_simplevoteme_custom_img' ] );
            update_option( 'gt_simplevoteme_custom_border_good' , $_POST[ 'gt_simplevoteme_custom_border_good' ] );
            update_option( 'gt_simplevoteme_custom_img_good' , $_POST[ 'gt_simplevoteme_custom_img_good' ] );
            update_option( 'gt_simplevoteme_custom_border_neutral' , $_POST[ 'gt_simplevoteme_custom_border_neutral' ] );
            update_option( 'gt_simplevoteme_custom_img_neutral' , $_POST[ 'gt_simplevoteme_custom_img_neutral' ] );
            update_option( 'gt_simplevoteme_custom_border_bad' , $_POST[ 'gt_simplevoteme_custom_border_bad' ] );
            update_option( 'gt_simplevoteme_custom_img_bad' , $_POST[ 'gt_simplevoteme_custom_img_bad' ] );
            update_option( 'gt_simplevoteme_votes' , $_POST[ 'gt_simplevoteme_votes' ] );
            update_option( 'gt_simplevoteme_custom_post_types' , $_POST[ 'gt_simplevoteme_custom_post_types' ]);

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
                            <span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/simple-vote-me?rate=3#postform" data-rating="3" title="">
                            <span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org//support/view/plugin-reviews/simple-vote-me?rate=4#postform" data-rating="4" title="">
                            <span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span>
                        </a>
                        <a target="_blank" href="https://wordpress.org//support/view/plugin-reviews/simple-vote-me?rate=5#postform" data-rating="5" title="">
                            <span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span>
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
                    <th scope="row"><?php echo __('Title') ; ?></th>
                    <td>
                    <?php $title = get_option('gt_simplevoteme_title'); ?>
                        <input name="gt_simplevoteme_title" value="<?php if($title) echo $title; ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Auto Insert in Content?') ; ?></th>
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
                    <th scope="row"><?php echo __('Select all options where you want the poll:') ; ?></th>
                    <td style="-webkit-column-count: 2;-moz-column-count: 2;column-count: 2;">
                    <?php $typesActive = get_option('gt_simplevoteme_custom_post_types');
                          $types = get_post_types(array('public'=>true),'objects');
                    foreach ($types as $type){?>
                        <div>
                        <input type="checkbox" id="<?php echo $type->name; ?>" name="gt_simplevoteme_custom_post_types[]" value="<?php echo $type->name; ?>" <?php if(in_array($type->name,$typesActive)) echo 'checked';?>/>
                        <label for="<?php echo $type->name; ?>"><?php echo $type->labels->menu_name; ?></label>
                        <?php //print_r($type); ?>
                        </div>
                    <?
                    }
                    
                    ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Display in Home?') ; ?></th>
                    <td>
                        <?php $home = get_option('gt_simplevoteme_auto_insert_home'); ?>
                        <label for="home_yes"><?php echo __('Yes'); ?></label>
                        <input type="radio" id="home_yes" name="gt_simplevoteme_auto_insert_home" value="1" <?php if ($home) echo "checked"; ?> />
                        
                        <label for="home_no"><?php echo __('No'); ?></label>
                        <input type="radio" id="home_no" name="gt_simplevoteme_auto_insert_home" value="0" <?php if (!$home) echo "checked"; ?> />    
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Position of the poll (on content)') ; ?></br><small><?php echo __('(Only if you have selected the auto insert).'); ?></small></th>
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
                    <th scope="row"><?php echo __('Only for registered users'); ?></th>
                     <td>
                         <?php $login = get_option('gt_simplevoteme_only_login'); ?>
                         <label for="only_login_yes"><?php echo __('Yes'); ?></label>
                         <input type="radio" id="only_login_yes" name="gt_simplevoteme_only_login" value="1" <?php if ($login) echo "checked"; ?> />
                         <label for="only_login_no"> <?php echo __('No'); ?></label>
                         <input type="radio" id="only_login_yes" name="gt_simplevoteme_only_login" value="0" <?php if (!$login) echo "checked"; ?> />
                     </td>
                    
                </tr>
                <tr>
                    <th scope="row"><?php echo __('How many times can each user vote?'); ?></th>
                    <td>
                        <?php $votes = get_option('gt_simplevoteme_votes'); ?>
                        <select id="votes" name="gt_simplevoteme_votes" >
                            <option value="0" <?php if(!$votes) echo "selected"; ?>><?php echo __('Infinite'); ?></option>
                            <option value="1" <?php if($votes) echo "selected"; ?>><?php echo __('Once per user') ; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Show Results?'); ?></th>
                    <td>
                        <?php $results= get_option('gt_simplevoteme_results'); ?>
                        <select id="results" name="gt_simplevoteme_results" >
                            <option value="1" <?php if($results) echo "selected"; ?>><?php echo __('Always'); ?></option>
                            <option value="2" <?php if($results == 2) echo "selected"; ?>><?php echo __('After vote'); ?></option>
                            <option value="0" <?php if(!$results ) echo "selected"; ?>><?php echo __('Never') ; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('What to show?'); ?></th>
                    <td>
                        <?php $results_type= get_option('gt_simplevoteme_results_type'); ?>
                        <select id="results" name="gt_simplevoteme_results_type" >
                            <option value="0" <?php if(!$results_type) echo "selected"; ?>><?php echo __('Total votes and percentages'); ?></option>
                            <option value="1" <?php if($results_type ) echo "selected"; ?>><?php echo __('Only percentages') ; ?></option>
                            <option value="2" <?php if($results_type == 2) echo "selected"; ?>><?php echo __('Only total votes'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Default CSS'); ?></th>
                    <td>
                        <?php $default_css= get_option('gt_simplevoteme_default_css'); ?>
                        <label for="default_css_yes"><?php echo __('Activate'); ?></label>
                        <input type="radio" id="default_css_yes" name="gt_simplevoteme_default_css" value="0"<?php if (!$default_css) echo "checked"; ?> />
                        <label for="default_css_nope"><?php echo __('Deactivate'); ?></label>
                        <input type="radio" id="default_css_nope" name="gt_simplevoteme_default_css" value="1"<?php if ($default_css) echo "checked"; ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom CSS'); ?></th>
                    <td>
                        <?php $css= get_option('gt_simplevoteme_custom_css'); ?>
                        <textarea name="gt_simplevoteme_custom_css"><?php if($css) echo $css ; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom Images'); ?></th>
                    <td>
                        <?php $customImg = get_option('gt_simplevoteme_custom_img'); ?>
                        <select name="gt_simplevoteme_custom_img">
                            <option value="0" <?php if(!$customImg) echo "selected" ; ?>><?php echo __('No'); ?></option>
                            <option value="1" <?php if($customImg) echo "selected" ; ?>><?php echo __('Yes'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom image for Good'); ?></th>
                    <td>
                        <?php $customImgG = get_option('gt_simplevoteme_custom_img_good'); ?>
                        <input type="text" name="gt_simplevoteme_custom_img_good" value="<?php if($customImgG) echo $customImgG ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom border for Good'); ?></th>
                    <td>
                        <?php $customBorG = get_option('gt_simplevoteme_custom_border_good'); ?>
                        <input type="color" name="gt_simplevoteme_custom_border_good" value="<?php if($customBorG) echo $customBorG ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom image for Neutral'); ?></th>
                    <td>
                        <?php $customImgN = get_option('gt_simplevoteme_custom_img_neutral'); ?>
                        <input name="gt_simplevoteme_custom_img_neutral" value="<?php if($customImgN) echo $customImgN ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom border for Neutral'); ?></th>
                    <td>
                        <?php $customBorN = get_option('gt_simplevoteme_custom_border_neutral'); ?>
                        <input type="color" name="gt_simplevoteme_custom_border_neutral" value="<?php if($customBorN) echo $customBorN ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom image for Bad'); ?></th>
                    <td>
                        <?php $customImgB = get_option('gt_simplevoteme_custom_img_bad'); ?>
                        <input name="gt_simplevoteme_custom_img_bad" value="<?php if($customImgB) echo $customImgB ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Custom border for Bad'); ?></th>
                    <td>
                        <?php $customBorB = get_option('gt_simplevoteme_custom_border_bad'); ?>
                        <input type="color" name="gt_simplevoteme_custom_border_bad" value="<?php if($customBorB) echo $customBorB ; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Reset all votes?'); ?></th>
                    <td>
                        <select id="reset" name="gt_simplevoteme_reset">
                            <option value="0"><?php echo __('No' ); ?></option>
                            <option value="1"><?php echo __('Yes'); ?></option>
                        </select>
                    </td>
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
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_auto_insert_home');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_position');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_only_login');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_default_css');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_css');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_results');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_results_type');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_good');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_border_good');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_background_good');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_neutral');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_border_neutral');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_background_neutral');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_img_bad');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_border_bad');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_background_bad');
        
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_votes');
        register_setting( 'gt_simplevoteme_options', 'gt_simplevoteme_custom_post_types');
    
   }
    
    function gt_simplevoteme_reset($reset = false){
        if($reset){
            
            $the_query = new WP_Query( 'meta_key=_simplevotemevotes&amp;orderby=meta_value_num&amp;order=DESC&amp;' );
            // The Loop
            while ( $the_query->have_posts() ) : $the_query->the_post();
                update_post_meta(get_the_ID(), '_simplevotemevotes', "");
            endwhile;
            wp_reset_postdata();
        }
    }
    
    
    
    
      /** Show the info in admin panel **/
    function gt_simplevoteme_custom_columns(){
        $types = get_option('gt_simplevoteme_custom_post_types');
        if(is_array($types)){
            foreach($types as $type){
                //add cols
                add_filter( 'manage_edit-'.$type.'_columns', 'gt_simplevoteme_extra_columns' );
                //add content
                add_action( 'manage_'.$type.'_posts_custom_column', 'gt_simplevoteme_content_column_row', 10, 2 );
            }
        }
    }
    
    gt_simplevoteme_custom_columns();
    
    function gt_simplevoteme_extra_columns( $columns ) {
        $columns[ 'simplevotemetotal' ] = __( 'Votes' );
        $columns[ 'simplevotemenegative' ] = __( ':(' );
        $columns[ 'simplevotemeneutral' ] = __( ':|' );
        $columns[ 'simplevotemepositive' ] = __( ':)' );
        return $columns;
    }

    
    function gt_simplevoteme_content_column_row( $column ) {
        global $post;
        $post_id = $post->ID;
        $votes = get_post_meta($post_id, '_simplevotemevotes', true) != '' ? get_post_meta($post_id, '_simplevotemevotes', true) : array('positives' => array(),
                                                                                                                                         'negatives' => array(),
                                                                                                                                         'neutrals'  => array(),
                                                                                                                                        );
        $users = array('positives' => array(), 'negatives' => array(), 'neutrals' => array());
        foreach ($votes as $key => $voteType){
            foreach ($voteType as $vote){
                if($vote != 0){
                    $user = get_userdata($vote);
                    $users[$key][] = '<a href="'. get_author_posts_url($vote, $user->display_name) .'" target="_blank">'. $user->display_name .'</a>';

                } else
                    $users[$key][] =  __('Anonymous');
            }
        }
        
            switch($column):
                case('simplevotemepositive'):
                    echo count($votes['positives']);
                    foreach($users["positives"] as $user){
                        echo "</br>" . $user;
                    }
                break;
                
                case('simplevotemenegative'):
                    echo count($votes['negatives']);
                    foreach($users["negatives"] as $user){
                        echo "</br>" . $user;
                    }
                break;
                case('simplevotemeneutral'):
                    echo count($votes['neutrals']);
                    foreach($users["neutrals"] as $user){
                        echo "</br>" . $user;
                    }
                break;
                case('simplevotemetotal'):
                    echo sizeof($votes, 1) - 3; //rest 3 because arrays for separate votes counts.
                break;

                default:
                break;
            endswitch;

    }



//Meta box for post
function gt_simplevoteme_metabox_votes($post){
     wp_nonce_field(basename(__FILE__), "meta-box-nonce");
 
    ?>
    <ul class="categorychecklist" style="text-transform: capitalize;">
        <?php
        $votes = get_post_meta($post->ID, '_simplevotemevotes', true) != '' ? get_post_meta($post->ID, '_simplevotemevotes', true) : array('positives' => array(),'negatives' => array(),'neutrals'  => array());
        
        $users = array('positives' => array(), 'negatives' => array(), 'neutrals' => array());
            foreach ($votes as $key => $voteType){
                foreach ($voteType as $vote){
                    if($vote != 0){
                        $user = get_userdata($vote);
                        $users[$key][] = '<a href="'. get_author_posts_url($vote, $user->display_name) .'" target="_blank">'. $user->display_name .'</a>';

                    } else
                        $users[$key][] =  __('Anonymous');
                }
            }
        ?>
        <li><span>Totals:</span><?php echo sizeof($votes, 1) - 3;?></li>
        <?php foreach($users as $key => $usersCat){
            echo "<li><span>$key:</span><ul class='children'>";
            foreach ($usersCat as $usr){
                echo "<li>$usr</li>";
            }
            
            echo "</ul></li>";
        }?>
    </ul>
    <style>
        #gt_simplevoteme_votes > .inside ul {
          overflow: auto;
        }
        #gt_simplevoteme_votes ul.categorychecklist > li {
          width: 33.333%;
          padding: 0;
          float: left;
        }
        #gt_simplevoteme_votes span{font-size:1.1em;font-weight:bold;}
        #gt_simplevoteme_votes ul.categorychecklist > li:first-child {
          width: 100%;
          margin-bottom: .5em;
        }
    </style>
    <?php
    
}
 
function gt_simplevoteme_add_meta_box_votes(){
    $types = get_option('gt_simplevoteme_custom_post_types');
    if(is_array($types)){
        foreach($types as $type){
    add_meta_box("gt_simplevoteme_votes", "Votes", "gt_simplevoteme_metabox_votes", $type, "side", "high", null);
        }
     }
}
 
add_action("add_meta_boxes", "gt_simplevoteme_add_meta_box_votes");