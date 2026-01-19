<?php
/**
 * Template tags for theme
 */

/**
 * Output the post publication date
 *
 * @return void Echoes formatted date
 */
function gni_posted_on() {
    printf( '<span class="posted-on">%s</span>', esc_html( get_the_date() ) );
}

/**
 * Output the post author name
 *
 * @return void Echoes author byline
 */
function gni_author() {
    printf( '<span class="byline">%s</span>', esc_html( get_the_author() ) );
}
