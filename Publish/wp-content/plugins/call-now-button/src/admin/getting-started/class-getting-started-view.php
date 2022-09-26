<?php

namespace cnb\admin\gettingstarted;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\CnbHeaderNotices;

class GettingStartedView {
    public function render() {

        wp_enqueue_style( CNB_SLUG . '-styling' );
        wp_enqueue_script( CNB_SLUG . '-premium-activation' );

        // Create link to the regular legacy page
        $url      = admin_url( 'admin.php' );
        $link =
            add_query_arg(
                array(
                    'page'   => 'call-now-button'
                ),
                $url );
        ?>
        <div class="cnb-welcome-page">
          <div class="cnb-welcome-blocks cnb-extra-top">

            <img class="cnb-logo" src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/icon-256x256.png');?>" width="128" height="128" alt="Call Now Button icon" />
            <h1>Welcome to Call Now Button</h1>
            <h3>Thank you for choosing Call Now Button - The web's most popular click-to-call button</h3>
            <div class="cnb-divider"></div>
            <h2>Create an account to enable additional features:</h2>
              <div class="cnb-block cnb-features-list">
                <div class="cnb-column cnb-col-1">
                  <h3>👋 Additional actions</h3>
                  <p>SMS/Text, Email, Maps, URLs, Scroll to top</p>
                  <h3>🤗 Social chat</h3>
                  <p>WhatsApp, Messenger, Telegram, Signal</p>
                  <h3>🆕 Lots of buttons</h3>
                  <p>Multiple buttons  for your website, even on a single page</p>
                  <h3>🗂️ Multi action buttons</h3>
                  <p>Multibutton (expandable) and Buttonbar (full width)</p>
                  <h3>💬 WhatsApp chat modal</h3>
                  <p>A WhatsApp chat panel to slide into the screen</p>
                </div>

                <div class="cnb-column cnb-col-2">
                  <h3>🖥️ All devices</h3>
                  <p>Desktop/laptop and mobile support</p>
                  <h3>🎯 Advanced page targeting</h3>
                  <p>Create smart rules for your buttons to appear</p>
                  <h3>🕘 Scheduler</h3>
                  <p>Create a weekly schedule for your buttons</p>
                  <h3>👋 Animations</h3>
                  <p>Add extra attention grabbing animations</p>
                  <h3>🎨 Icon picker</h3>
                  <p>Select the right icon for your button</p>
                  <h3>👽 3rd party integrations</h3>
                  <p>Content Windows, Intercom chat and Tally forms</p>

                </div>
              </div>
              <div class="cnb-block cnb-signup-box">
                <h2>Sign up now to get all this and more</h2>
                <?php echo CnbHeaderNotices::cnb_settings_email_activation_input(); // phpcs:ignore WordPress.Security ?>
              </div>
              <div class="cnb-divider"></div>
              <p><i>Only need a Call button? <a href="<?php echo esc_url( $link ) ?>">Continue without an account</a>.</i></p>
            </div>
            <div class="cnb-welcome-blocks">

              <div class="cnb-block">
                <h1>Why do I need an account?</h1>
                <h3>With an account you enable the cloud features from callnowbutton.com.</h3>
                <p>Here's a close-up of some of the cloud features:</p>
                <div class="cnb-divider"></div>

                <h2>🎁 More actions and icons 🎁</h2>
                  <img class="cnb-width-80 cnb-extra-space" src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/cnb-icons-actions.png');?>" alt="WhatsApp modal">
                <p>New actions include WhatsApp, SMS/Text, Email, Signal, Telegram, Messenger, Location, Link and Smooth scroll to point.</p>

                <div class="cnb-divider"></div>

                <h2>💬 Pop up windows for WhatsApp, iframes & more 💬</h2>
                <img src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/whatsapp-modal.png');?>" alt="WhatsApp modal">
                <p>Start the WhatsApp conversation on your website.</p>

                <div class="cnb-divider"></div>

                <h2>💎 Multibutton 💎</h2>
                <img class="cnb-width-80" src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/multibutton.png');?>" alt="Multibutton">
                <p>Takes up little space but reveals a treasure of options.</p>

                <div class="cnb-divider"></div>

                <h2>✨ Buttonbar ✨</h2>
                <img class="cnb-width-80" src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/buttonbar.png');?>" alt="Buttonbar">
                <p>Create a web app experience on your website.</p>

                <div class="cnb-divider"></div>

                <h2>🕘 The scheduler 🕔</h2>
                <img src="<?php echo esc_url(WP_PLUGIN_URL . '/' . CNB_BASEFOLDER . '/resources/images/button-scheduler.png');?>" alt="The scheduler">
                <p>Control exactly when your buttons are displayed. Maybe a call button during business hours and a mail buttons when you're closed.</p>

                <div class="cnb-divider"></div>
                <h3>And much more!</h3>
              </div>
          </div>
          <div class="cnb-welcome-blocks">
            <h2>PRO features include</h2>
              <div class="cnb-center">
                <h3>📄 Slide-in content windows</h3>
                <h3>📷 Use custom images on buttons</h3>
                <h3>🌍 Include and exclude countries</h3>
                <h3>↕️ Set scroll height for buttons to appear</h3>
                <h3>🔌 Intercom Chat integration</h3>
              </div>
          </div>
        </div>
          <div class="cnb-welcome-blocks">
            <div class="cnb-block cnb-signup-box">
              <h2>Create your free account and supercharge your Call Now Button.</h2>
              <?php echo CnbHeaderNotices::cnb_settings_email_activation_input(); // phpcs:ignore WordPress.Security ?>
            </div>
            <div class="cnb-divider"></div>
            <p><i>Only need a Call button? <a href="<?php echo esc_url( $link ) ?>">Continue without an account</a>.</i></p>
          </div>

  <?php  }
}
