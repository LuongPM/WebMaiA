<?php

namespace cnb\admin\settings;

use cnb\admin\api\CnbAdminCloud;
use cnb\admin\api\CnbAppRemote;
use cnb\admin\domain\CnbDomainViewEdit;
use cnb\admin\domain\CnbDomainViewUpgradeFinished;
use cnb\admin\domain\CnbDomainViewUpgradeOverview;
use cnb\admin\models\CnbActivation;
use cnb\admin\models\CnbUser;
use cnb\notices\CnbAdminNotices;
use WP_Error;

class CnbApiKeyActivatedView {
    /**
     * @var CnbActivation
     */
    private $activation;

    function header() {
        echo 'Cloud activation';
    }

    /**
     * @param $error WP_Error
     *
     * @return void
     */
    private function renderButtonError( $error ) {
        $notice = CnbAdminCloud::cnb_admin_get_error_message( 'create', 'Button', $error );
        CnbAdminNotices::get_instance()->renderNotice( $notice );
    }

    /**
     *
     * @return void
     */
    private function renderButtonCreated() {
        $message = '<p>Your existing button has been migrated.</p>';
        CnbAdminNotices::get_instance()->renderSuccess( $message );
    }

    private function getAllButtonsLink() {
        $url = admin_url( 'admin.php' );

        return add_query_arg(
            array(
                'page' => 'call-now-button',
            ),
            $url );
    }

    private function getNewButtonLink() {
        $url = admin_url( 'admin.php' );

        return add_query_arg(
            array(
                'page'   => 'call-now-button',
                'action' => 'new'
            ),
            $url );
    }

    private function getSettingsLink() {
        $url = admin_url( 'admin.php' );

        return add_query_arg(
            array(
                'page' => 'call-now-button-settings',
            ),
            $url );
    }

    private function renderButtonInfo() {
        $button = $this->activation->button;

        if ( is_wp_error( $button ) ) {
            $this->renderButtonError( $button );
            return;
        }

        // If the activation was not successful, don't assume anything about the button
        if ( ! $this->activation->success ) {
            return;
        }

        // If a button is created, tell the user
        if ( $button ) {
            $this->renderButtonCreated();
        }
    }

    private function renderActivationSuccess() {
        echo '<div style="text-align: center;">';
        echo '<div style="width:200px;margin: 0 auto;">';
        ( new CnbDomainViewUpgradeFinished() )->echoBigYaySvg();
        echo '</div>';
        echo '<h1>You have successfully activated Cloud account</h1>';
        echo '</div>';
    }

    private function renderGetStarted() {
        $domain = $this->activation->domain;
        if ( $domain === null ) {
            $domain = CnbAppRemote::cnb_remote_get_wp_domain();
        }
        $nonce_field    = wp_nonce_field( 'cnb_update_domain_timezone', '_wpnonce', true, false );
        $timezoneSelect = ( new CnbDomainViewEdit() )->getTimezoneSelect( $domain );
        echo sprintf( '
            <div class="cnb-get-started cnb-plan-features cnb-center top-50">
            <h1 class="cnb-center"><strong>Let\'s get started</strong></h1><hr>
            <div class="cnb-flexbox">
              <div class="box">
                <h2>Is this your time zone?</h2>
                <div>
                    %4$s
                    %5$s
                </div>
              </div>
              <div class="box">
                <h2>Manage your buttons</h2>
                <p>
                  <a class="button button-primary" href="%1$s">Create new</a>
                  <a class="button premium-button" href="%2$s">Button overview</a>
                </p>
              </div>
              <div class="box">
                <h2>Check your Settings</h2>
                <p><a class="button premium-button" href="%3$s">Open settings
                  </a></p>
              </div>
            </div>
            </div>',
            esc_url( $this->getNewButtonLink() ),
            esc_url( $this->getAllButtonsLink() ),
            esc_url( $this->getSettingsLink() ),
            // phpcs:ignore WordPress.Security
            $timezoneSelect,
            // phpcs:ignore WordPress.Security
            $nonce_field );
    }

    private function renderOnboarding() {
        $img_scheduling    = plugins_url( '../../../resources/images/onboarding/action-scheduling.png', __FILE__ );
        $img_action_extras = plugins_url( '../../../resources/images/onboarding/action-extra-options.png', __FILE__ );
        $img_actions       = plugins_url( '../../../resources/images/onboarding/actions-overview.png', __FILE__ );
        $img_add_action    = plugins_url( '../../../resources/images/onboarding/add-action.png', __FILE__ );
        $img_add_rule      = plugins_url( '../../../resources/images/onboarding/add-display-rule.png', __FILE__ );
        $img_presentation  = plugins_url( '../../../resources/images/onboarding/button-presenation.png', __FILE__ );
        $img_buttons       = plugins_url( '../../../resources/images/onboarding/buttons-overview.png', __FILE__ );
        $img_nav_position  = plugins_url( '../../../resources/images/onboarding/nav-position.png', __FILE__ );
        $img_new_button    = plugins_url( '../../../resources/images/onboarding/new-button.png', __FILE__ );
        $img_preview       = plugins_url( '../../../resources/images/onboarding/preview.png', __FILE__ );
        $img_visibility    = plugins_url( '../../../resources/images/onboarding/visibility-settings.png', __FILE__ );
        ?>
        <div class="cnb_onboarding_guide cnb-plan-features cnb-center top-50">
        <h1><strong>Quick start guide</strong></h1><hr>
        <h2 class="cnb-left">Locating the Call Now Button</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_nav_position ) ?>" alt="Find the Call Now Button in the side nav of your WordPress dashboard."></div>
        <p class="bottom-50">Find the Call Now Button in the side nav of your WordPress dashboard.</p>
        <h2 class="cnb-left">Your buttons overview</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_buttons ) ?>" alt="The buttons overview page where you can add, edit and remove buttons."></div>
        <p class="bottom-50">The buttons overview page where you can add, edit and remove buttons. Click <strong>Add New</strong> at the top to start a new button.</p>
        <h2 class="cnb-left">Create a new button</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_new_button ) ?>" alt="When creating a new button, start with selecting your button type."></div>
        <p class="bottom-50">When creating a new button, start with selecting your button type.</p>
        <h2 class="cnb-left">Add an action to your button</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_add_action ) ?>" alt="Every button contains one or more actions."></div>
        <p class="bottom-50">Every button contains one or more actions.</p>
        <h2 class="cnb-left">Some actions have additional options</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_action_extras ) ?>" alt="Some actions accept extra settings for more advanced features. Here's an example for WhatsApp."></div>
        <p class="bottom-50">Some actions accept extra settings for more advanced features. Here's an example for WhatsApp.</p>
        <h2 class="cnb-left">Multi-action buttons contain action overviews</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_actions ) ?>" alt="You can drag & drop the actions on the Buttonbar and the Multibutton to change the order."></div>
        <p class="bottom-50">You can drag & drop the actions on the Buttonbar and the Multibutton to change the order.</p>
        <h2 class="cnb-left">Every action can be scheduled</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_scheduling ) ?>" alt="Every action can be individually scheduled."></div>
        <p class="bottom-50">Every action can be individually scheduled.</p>
        <h2 class="cnb-left">Change the presentation of your button</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_presentation ) ?>" alt="In the presentation tab you can set placement, the colors and pick an animation effect for your button."></div>
        <p class="bottom-50">In the presentation tab you can set placement, the colors and pick an animation effect for your button.</p>
        <h2 class="cnb-left">Adjust the visibility of your button</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_visibility ) ?>" alt="On the Visibility tab you can decide where your button should appear. Here you also see an overview of all active Display Rules."></div>
        <p class="bottom-50">On the Visibility tab you can decide where your button should appear. Here you also see an overview of all active Display Rules.</p>
        <h2 class="cnb-left">Adding Display Rules</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_add_rule ) ?>" alt="Add display rules to select the pages where the button should appear or not."></div>
        <p class="bottom-50">Add display rules to select the pages where the button should appear or not.</p>
        <h2 class="cnb-left">The preview phone</h2>
        <div class="cnb_screenshot"><img src="<?php echo esc_url( $img_preview ) ?>" alt="A preview phone is always visible to validate your edits. The time in the phone can be changed to test your scheduled actions."></div>
        <p class="bottom-50">A preview phone is always visible to validate your edits. The time in the phone can be changed to test your scheduled actions.</p>
        <h1><strong>Let's go!</strong></h1>
        <hr/>
        <p style="margin-bottom:100px;">
          <a class="button button-primary button-large" href="<?php echo esc_url( $this->getNewButtonLink() ) ?>">Create new button</a>
          <a class="button button-large" href="<?php echo esc_url( $this->getAllButtonsLink() ) ?>">Button overview</a>
        </p>
        </div>

        <?php
    }


    /**
     *
     * @return void
     */
    private function renderUpgradeToPro() {
        $domain = $this->activation->domain;
        if ( $domain === null ) {
            $domain = CnbAppRemote::cnb_remote_get_wp_domain();
        }
        if ( $domain->type !== 'FREE' ) {
            // Already upgraded, so skip all of this
            return;
        }
        echo '<div class="cnb-plan-features cnb-center top-50">';
        echo '<h1><strong>Need even more power?</strong></h1><hr>';
        echo '<h3>All features from Cloud plus...</h3>';
        echo '<div class="cnb-pricebox cnb-smaller">
          <div class="cnb-benefit">📄 Slide-in content windows</div>
          <div class="cnb-benefit">📷 Use custom images on buttons</div>
          <div class="cnb-benefit">🌍 Include and exclude countries</div>
          <div class="cnb-benefit">↕️ Set scroll height for buttons to appear</div>
          <div class="cnb-benefit">🔌 Intercom Chat integration</div>
          <div class="cnb-benefit">✨ "Powered by" notice removed</div>
        </div>';
        ( new CnbDomainViewUpgradeOverview() )->renderUpgradeForm( $domain );
        echo '</div>';
    }

    /**
     * @param $user CnbUser
     *
     * @return void
     */
    private function renderActivationFailure( $user ) {
        if ( ! is_wp_error( $user ) ) {
            echo '<div style="text-align: center"><h2>Cloud is already active</h2></div>';

            return;
        }

        echo '<h1>You tried to activate CLOUD, but something went wrong.</h1>';
    }

    private function renderBenefits() {
        echo '<div>';
        echo '<h2 class="cnb-center">You now have access to the following functionality:</h2>';
        echo '<div class="cnb-benefits-block">';
        ( new CnbDomainViewUpgradeOverview() )->renderBenefits();
        echo '</div></div>';
    }

    private function renderActivationStatus() {
        $user = CnbAppRemote::cnb_remote_get_user_info();
        if ( $this->activation->success ) {
            $this->renderActivationSuccess();
        }
        if ( ! $this->activation->success && ! is_wp_error( $user ) ) {
            echo '<div style="text-align: center"><h1>Cloud is already active</h1></div>';
        }
        if ( $this->activation->success || ! is_wp_error( $user ) ) {
            //$this->renderBenefits();
            $this->renderGetStarted();
            $this->renderOnboarding();
            $this->renderUpgradeToPro();
        } else {
            $this->renderActivationFailure( $user );
        }
    }

    public function render() {
        add_action( 'cnb_header_name', array( $this, 'header' ) );
        wp_enqueue_script( CNB_SLUG . '-settings-activated' );
        wp_enqueue_script( CNB_SLUG . '-profile' );
        wp_enqueue_script( CNB_SLUG . '-domain-upgrade' );
        wp_enqueue_script( CNB_SLUG . '-timezone-picker-fix' );

        do_action( 'cnb_header' );

        $this->renderActivationStatus();


        // Link to Button (if present)
        $this->renderButtonInfo();

        do_action( 'cnb_footer' );
    }

    /**
     * @param CnbActivation $activation
     *
     * @return void
     */
    public function setActivation( $activation ) {
        $this->activation = $activation;
    }
}
