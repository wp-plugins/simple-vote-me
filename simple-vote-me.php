<?php
/*
Plugin Name: Simple Vote Me
Plugin URI: https://wordpress.org/plugins/simple-vote-me/
Description: This plugin add cute and simple votes for Wordpress post.
Author: Gonzalo Torreras
Version: 1.3
Author URI: http://www.gonzalotorreras.com
*/

    define('SIMPLEVOTEMESURL', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
    
    include_once(plugin_dir_path(__FILE__) .'/admin.php');
    include_once(plugin_dir_path(__FILE__) .'/widget.php');
    
    global $gt_simplevoteme_version;
    $gt_simplevoteme_version = "1.3";
    
    function gt_simplevoteme_checkversion(){
        $version = get_option('gt_simplevoteme_version');
        
        if ($version === false){
            //install plugin
            
            
            //check if there are old system of votes
            gt_simplevoteme_check_old_votes();
            
            
        }
        
        else if ( $version != $gt_simplevoteme_version){
            //update tables,vars etc.
        }
        
        update_option('gt_simplevoteme_version', $gt_simplevoteme_version);
        
    }
    add_action ( 'plugins_loaded', 'gt_simplevoteme_checkversion');
    
    
    function gt_simplevoteme_check_old_votes(){
    
            $posts = get_posts( 'meta_key=_simplevotemetotal&amp;' );
            
            if($posts){
                $votes = array('positives' => array(),'negatives' => array(),'neutrals'  => array());
                foreach( $posts as $post){

                    $pos = get_post_meta($post->ID, '_simplevotemepositive', true) ? get_post_meta($post->ID, '_simplevotemepositive', true) : 0;
                    $neg = get_post_meta($post->ID, '_simplevotemenegative', true) ? get_post_meta($post->ID, '_simplevotemenegative', true) : 0;
                    $neu = get_post_meta($post->ID, '_simplevotemeneutral', true) ? get_post_meta($post->ID, '_simplevotemeneutral', true) : 0;
                    
                    for($i=0; $i<$pos;$i++){
                        $votes['positives'][] = '0'; //add votes for positive with user_ID 0 like annonymous 
                    }
                    for($i=0; $i<$neg;$i++){
                        $votes['negatives'][] = '0'; //add votes for positive with user_ID 0 like annonymous 
                    }
                    for($i=0; $i<$neu;$i++){
                        $votes['neutrals'][] = '0'; //add votes for positive with user_ID 0 like annonymous 
                    }
                    
                    update_post_meta($post->ID, '_simplevotemevotes', $votes);
                    
                    //echo "updating gt_svtm</br>neg:$neg</br>pos:$pos</br>neu:$neu";
                    //print_r($votes);
                    delete_post_meta($post->ID, '_simplevotemetotal', "");
                    delete_post_meta($post->ID, '_simplevotemepositive', "");
                    delete_post_meta($post->ID, '_simplevotemenegative', "");
                    delete_post_meta($post->ID, '_simplevotemeneutral', "");
                
                    
                }
            }
            
            
    
    }
    function gt_simplevoteme_enqueuescripts(){
    
        wp_register_style( 'simplevotemestyle', SIMPLEVOTEMESURL.'/css/simplevoteme.css' );
        
        wp_register_style( 'simplevotemestyleadmin', SIMPLEVOTEMESURL .'/css/simplevotemeadmin.css' );
        
        wp_enqueue_script('gtsimplevoteme', SIMPLEVOTEMESURL.'/js/simple-vote-me.js', array('jquery'));
        
        
        
        wp_localize_script( 'gtsimplevoteme', 'gtsimplevotemeajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        
        $css = get_option('gt_simplevoteme_custom_default_css');
        
        if(!$css)//default = yes = 0
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
        $user_ID = get_current_user_id();
        $limitVotesPerUser = get_option('gt_simplevoteme_votes');
        
        if(!$noLinks)
            $post_ID = get_the_ID();
        else
            $post_ID = $_POST['postid'];
        
        $votes = get_post_meta($post_ID, '_simplevotemevotes', true) != "" ? get_post_meta($post_ID, '_simplevotemevotes', true) : array('positives' => array(),//id users array 
                                                                                                                                         'negatives' => array(),
                                                                                                                                         'neutrals'  => array(),
                                                                                                                                    );
        //if no limit votes per user or user not logged 
        if( $limitVotesPerUser && $user_ID != 0 && (in_array($user_ID, $votes['positives']) || in_array($user_ID, $votes['negatives']) || in_array($user_ID, $votes['neutrals']))) 
            $noLinks = 1;//check if there are limit per user and the user is in array, if is $nolinks = 1
        
        $votemePositive = count($votes['positives']);
        
        $votemeNeutral = count($votes['neutrals']);
        
        $votemeNegative = count($votes['negatives']);
        
        $votemeTotal = sizeof($votes, 1) - 3; //rest 3 because arrays for separate votes counts.
        
        $votemeResults = get_option('gt_simplevoteme_results');
        $votemeResultsType = get_option('gt_simplevoteme_results_type');
        if($votemeResults){
            if($votemeResults == 1 || ($votemeResults == 2 && $noLinks) ){
            
                if ($votemeTotal != 0 || $votemeTotal != '' ){
                    
                    if($votemeNegative > 0) //if there are votes
                        $percentNegative = round ( $votemeNegative / $votemeTotal, 2) * 100 ."%";
                    else
                        $percentNegative = "0%";
                    
                    if($votemeResultsType == 2)//just total votes
                        $votemePercentNegative = $votemeNegative;
                    else if($votemeResultsType == 1)//only percentages
                        $votemePercentNegative = $percentNegative;
                    else //all
                        $votemePercentNegative = "$percentNegative<small> ($votemeNegative) </small>";


                    
                    if($votemeNeutral > 0) //if there are votes
                        $percentNeutral  = round ( $votemeNeutral / $votemeTotal, 2 ) * 100 . "%";
                    else 
                        $percentNeutral  = "0%";
                    
                    if($votemeResultsType == 2)//just total votes
                        $votemePercentNeutral = $votemeNeutral;
                    else if($votemeResultsType == 1)//only percentages
                        $votemePercentNeutral = $percentNeutral;
                    else //all
                        $votemePercentNeutral = "$percentNeutral<small> ($votemeNeutral) </small>";

                    
                    
                    if ($votemePositive > 0)
                        $percentPositive = round (  $votemePositive / $votemeTotal, 2) * 100 . "%";
                    else
                        $percentPositive = "0%";
                        
                    if($votemeResultsType == 2)//just total votes
                        $votemePercentPositive = $votemePositive;
                    else if($votemeResultsType == 1)//only percentages
                        $votemePercentPositive = $percentPositive;
                    else //all
                        $votemePercentPositive = "$percentPositive<small> ($votemePositive) </small>";
                    
                    
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
        
            $linkPositivo = '<a onclick="simplevotemeaddvote('.$post_ID.', 1,'.$user_ID.');">'. gt_simplevoteme_getimgvote("good") .'</a>';
            $linkNegativo = '<a onclick="simplevotemeaddvote('.$post_ID.', 2,'.$user_ID.');">'. gt_simplevoteme_getimgvote("bad") .'</a>';
            $linkNeutral  = '<a onclick="simplevotemeaddvote('.$post_ID.', 0,'.$user_ID.');">'. gt_simplevoteme_getimgvote("neutral") .'</a>';
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
        
        $bor_G = get_option('gt_simplevoteme_custom_border_good');
        $bor_N = get_option('gt_simplevoteme_custom_border_neutral');    
        $bor_B = get_option('gt_simplevoteme_custom_border_bad');
        $bg_B  = get_option('gt_simplevoteme_custom_background_bad');
        $bg_N  = get_option('gt_simplevoteme_custom_background_neutral');
        $bg_G  = get_option('gt_simplevoteme_custom_background_good');
        
        if($bor_G || $bor_N || $bor_B || $bg_G || $bg_N || $bg_B)
            $result .= "<style>.simplevotemeWrapper span.bad{  background: rgba($bg_B);border:1px solid rgba($bor_B);.simplevotemeWrapper span.neutro{  background: rgba($bg_N);border:1px solid rgba($bor_N);.simplevotemeWrapper span.good{  background: rgba($bg_G);border:1px solid rgba($bor_G);}</style>";
        
        //$result .= print_r($votes);
        return $result;
    }
     
    function gt_simplevoteme_printvotelink_auto($content){
        
        $home = get_option('gt_simplevoteme_auto_insert_home');
        
        $auto  = get_option('gt_simplevoteme_auto_insert_content');
        
            if(!$auto && ( is_home() &&  !$home) )
                return($content);
        
        $login = get_option('gt_simplevoteme_only_login'); //after auto, do not waste resources if is not necessary :)
        
        
            if( $login && !is_user_logged_in() )
                return($content);
                
                
        $position = get_option('gt_simplevoteme_position');//after login, do not waste resources if is not necessary :)
            
        if(is_home() && $home){ //if is home and home is active
                if(!$position)
                    return $content.gt_simplevoteme_getvotelink();
                
                else if($position == 1)
                    return gt_simplevoteme_getvotelink().$content;
                
                else if ($position == 2){
                    $linksVote = gt_simplevoteme_getvotelink(); //launch just once
                    return $linksVote.$content.$linksVote;
                } else
                    return $content;//nothing expected
            
        } else if( ($auto == 1 || $auto == 3 ) && is_single() ){//if is only post(1) or post&page(3)
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
                if(!$position)
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
    function gt_simplevoteme_addvote(){
        $results = '';
        global $wpdb;
        $post_ID = $_POST['postid'];
        $user_ID = $_POST['userid'];
        $type = $_POST['tipo'];
                    $votes = get_post_meta($post_ID, '_simplevotemevotes', true) != "" ? get_post_meta($post_ID, '_simplevotemevotes', true) : array('positives' => array(),
                    'negatives' => array(),
                    'neutrals'  => array()
                    );

        switch($type){
            case 0:
                $votes['neutrals'][] = $user_ID;
            break;
            case 1:
                $votes['positives'][] = $user_ID;
            break;
            case 2:
                $votes['negatives'][] = $user_ID;
            break;
        }
        update_post_meta($post_ID, '_simplevotemevotes', $votes);
        
        
        $result = gt_simplevoteme_getvotelink(1);
        
 
        // Return the String
        die($result);
    }
 
    // creating Ajax call for WordPress
    add_action( 'wp_ajax_nopriv_simplevoteme_addvote', 'gt_simplevoteme_addvote' );
    add_action( 'wp_ajax_simplevoteme_addvote', 'gt_simplevoteme_addvote' );
            
            

  
