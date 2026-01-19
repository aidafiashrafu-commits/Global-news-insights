<?php
/**
 * Template tags for theme
 */

function gni_posted_on() {
    printf( '<span class="posted-on">%s</span>', esc_html( get_the_date() ) );
}

function gni_author() {
    printf( '<span class="byline">%s</span>', esc_html( get_the_author() ) );
}
