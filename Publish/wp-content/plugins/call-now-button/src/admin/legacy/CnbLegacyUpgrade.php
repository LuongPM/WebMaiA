<?php

namespace cnb\admin\legacy;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\utils\CnbAdminFunctions;
use cnb\CnbHeaderNotices;
use cnb\utils\CnbUtils;

class CnbLegacyUpgrade {
    function header() {
        echo 'Activate Cloud to unlock lots of extra features';
    }

    private function standard_plugin_promobox() {
        ?>
        <div class="cnb-body-column hide-on-mobile">
            <?php
            ( new CnbAdminFunctions() )->cnb_promobox(
                'grey',
                'Lite',
                '<p>&check; One button<br>
                &check; Phone<br><br>
                &check; Circular (single action)<br>
                &check; Buttonbar (single action)<br>
                &check; Action label<br>
                &nbsp;<br>
                </p>
                <hr>
                <p>
                &check; Placement options<br>
                &check; For mobile devices<br>
                &check; Include or exclude pages<br>
                &nbsp;<br>
                &nbsp;<br>
                &nbsp;
                </p>
                <hr>
                <p>
                &check; Google Analytics tracking<br>
                &check; Google Ads conversion tracking<br>
                </p>
                <hr>
                <p>
                &check; Adjust the button size<br>
                &check; Flexible z-index<br>
                &nbsp;
                </p>',
                'database',
                '<strong>Free</strong>',
                'Currently active',
                'disabled'
            );
            ?>
        </div>
    <?php }

    private function premium_plugin_promobox() {
        $cnb_utils = new CnbUtils();
        ?>
        <div class="cnb-body-column">
            <?php
            ( new CnbAdminFunctions() )->cnb_promobox(
                'green',
                'Cloud',
                '
                <p><strong>&check; Lots of buttons!</strong><br>
                &check; Phone, SMS/Text, Email, Maps, URLs, Scroll to point<br>
                &check; WhatsApp, Messenger, Telegram, Signal<br>
                &check; Circular button (single & multi action)<br>
                &check; Buttonbar (multi action)<br>
                &check; Action labels<br>
                &check; WhatsApp chat window<a href="' . esc_url($cnb_utils->get_support_url('wordpress/buttons/whatsapp-modal/', 'question-mark', 'whatsapp-window' ) ) . '" target="_blank" class="cnb-nounderscore"><span class="dashicons dashicons-editor-help"></span></a><br>
                </p>
                <hr>
                <p>
                &check; Placement options<br>
                &check; For mobile and desktop/laptop<br>
                &check; Advanced page targeting<br>
                &check; Scheduling<br>
                &check; Button animations (to draw attention)<br>
                &check; Icon selection<br>
                </p>
                <hr>
                <p>
                &check; Google Analytics tracking<br>
                &check; Google Ads conversion tracking<br>
                </p>
                <hr>
                <p>
                &check; Adjust the button size<br>
                &check; Flexible z-index<br>
                &check; Live button preview</p>
                <hr>
                <p class="cnb_align_center"><strong style="text-decoration:underline">FREE</strong> with subtle branding. PRO from &euro;<span class="eur-per-month"></span>/$<span class="usd-per-month"></span> per month.</p>',
                'cloud',
                CnbHeaderNotices::cnb_settings_email_activation_input(),
                'none'
            );
            ?>
        </div>
    <?php }

    function upgrade_faq() {
      $cnb_utils = new CnbUtils(); ?>
        <div style="max-width:600px;margin:0 auto">
            <h1 class="cnb-center">FAQ</h1>
            <h3>Is Cloud really free?</h3>
            <p>Yes. You can use 99% of the cloud features of the Call Now Button for free. No credit card is required. You
                only need an account for that. The difference with the PRO plan is that a small "Powered by"
                notice is added to your buttons.</p>
            <h3>What's included in PRO?</h3>
            <p>Upgrading to PRO gives you access to a number of premium features: Slide-in content windows (<a href="<?php echo esc_url($cnb_utils->get_website_url('integrations/#iframes', 'faq', 'content-windows' ) );?>" target="_blank">learn more</a>), Custom button images, Intercom chat integration (<a href="<?php echo esc_url($cnb_utils->get_website_url('integrations/#intercom', 'faq', 'intercom' ) );?>" target="_blank">learn more</a>), GEO targeting and Appear after scroll. It also removes the "Powered by Call Now Button" notice from your button.</p>
            <h3>Does Cloud require an account?</h3>
            <p>Yes. We want the Call Now Button to be accessible to all website owners. Even those that do not have a
                WordPress powered website. The Cloud version of the Call Now Button can be used by everyone on any website. You can
                continue to manage your buttons from your WordPress instance, but you could also do this via our web
                app on <a href="https://callnowbutton.com" target="_blank">callnowbutton.com</a>. And should you ever move to a different CMS, your button(s) will just move with you.</p>
            <h3>What is the "Powered by" notice?</h3>
            <p>The cloud version of Call Now Button is available for a small yearly or monthly fee, but there is also a
                <em>free</em> option. The free option introduces a small notice to your buttons that says "Powered by Call
                Now Button". It's very delicate and will not distract the the visitor from your content.</p>
            <h3>Why is it called Cloud?</h3>
            <p>It's called Cloud because it is served from remote servers (the cloud) and no longer stored locally on your website. Therefore you need an account to enabled it. WordPress is currently the only platform that has its own interface so you can still manage your buttons from inside WordPress. Other platforms can use the Call Now Button as well but manage their buttons via the web app on app.callnowbutton.com.</p>
        </div>
    <?php }

    public function render() {
        do_action( 'cnb_init', __METHOD__ );
        wp_enqueue_script( CNB_SLUG . '-settings' );
        wp_enqueue_script( CNB_SLUG . '-premium-activation' );

        add_action( 'cnb_header_name', array( $this, 'header' ) );
        do_action( 'cnb_header' );
        ?>

        <div class="cnb-one-column-section">
            <div class="cnb-body-content">
                <div class="cnb-two-promobox-row">
                    <?php $this->premium_plugin_promobox() ?>
                    <?php $this->standard_plugin_promobox() ?>
                </div>
                <?php $this->upgrade_faq() ?>
            </div>
        </div>
        <hr>
        <?php
        do_action( 'cnb_footer' );
        do_action( 'cnb_finish' );
    }
}
