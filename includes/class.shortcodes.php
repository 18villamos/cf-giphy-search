<?php

class BartokShortcodes {
  public function __construct() {
      add_shortcode( );
  }
}


function work_soundcloud($whichwork) {
    $look_up = heroic_featured_work($whichwork);
    $this_soundcloud=$look_up["soundCloud"];
    echo do_shortcode('[bartok_soundcloud url=' . $this_soundcloud . ' show_art=true]');
}
