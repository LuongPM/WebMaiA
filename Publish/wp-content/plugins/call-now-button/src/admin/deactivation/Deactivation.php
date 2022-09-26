<?php

namespace cnb\admin\deactivation;

/**
 * On Deactivation of our plugin.
 */
class Deactivation {

    /**
     * This is called /during/ the deactivation process (so, not before - there is no change for output).
     *
     * @return void
     */
    static public function on_deactivation() {
        // Noop
    }
}
