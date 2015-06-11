<?php 

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