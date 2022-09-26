<?php

namespace cnb\admin\button;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\admin\api\CnbAppRemote;
use cnb\admin\api\CnbAppRemotePromotionCodes;
use cnb\admin\domain\CnbDomain;
use cnb\utils\CnbAdminFunctions;
use cnb\notices\CnbAdminNotices;
use cnb\utils\CnbUtils;
use WP_Error;

class CnbButtonView {
    function header() {
        echo 'Buttons ';
    }

    function get_modal_link() {
        $url = admin_url( 'admin.php' );

        return
            add_query_arg(
                array(
                    'TB_inline' => 'true',
                    'inlineId'  => 'cnb-add-new-modal',
                    'height'    => '433', // 433 seems ideal -> To hide the scrollbar. 500 to include validation errors
                    'page'      => 'call-now-button',
                    'action'    => 'new',
                    'type'      => 'single',
                    'id'        => 'new'
                ),
                $url );
    }

    public function cnb_create_new_button() {
        $url = $this->get_modal_link();
        printf(
            '<a href="%s" title="%s" class="thickbox open-plugin-details-modal cnb-button-overview-modal-add-new %s" data-title="%s">%s</a>',
            esc_url( $url ),
            esc_html__( 'Create new button' ),
            'page-title-action',
            esc_html__( 'Choose a Button type' ),
            esc_html__( 'Add New' )
        );
    }

    /**
     * Used by the button-table, in case there are no buttons to render.
     *
     * @return void
     */
    public function render_lets_create_one_link() {
        $url = $this->get_modal_link();
        printf(
            '<a href="%s" title="%s" class="thickbox open-plugin-details-modal cnb-button-overview-modal-add-new" data-title="%s">%s</a>',
            esc_url( $url ),
            esc_html__( 'Create new button' ),
            esc_html__( 'Choose a Button type' ),
            esc_html__( 'Let\'s create one!' )
        );
    }

    /**
     * @param $domain CnbDomain|WP_Error
     * @param $table Cnb_Button_List_Table
     *
     * @return void
     */
    private function set_button_filter( $domain, $table ) {
        $cnb_options = get_option( 'cnb' );
        if ( isset( $cnb_options['show_all_buttons_for_domain'] )
             && $cnb_options['show_all_buttons_for_domain'] != 1
             && $domain != null
             && ! ( $domain instanceof WP_Error ) ) {
            $table->setOption( 'filter_buttons_for_domain', $domain->id );
        }
    }

    function render() {
        $cnb_cloud_domain = CnbAppRemote::cnb_remote_get_wp_domain();

        //Prepare Table of elements
        $wp_list_table = new Cnb_Button_List_Table();

        // Set filter
        $this->set_button_filter( $cnb_cloud_domain, $wp_list_table );

        // If users come to this page before activating, we need the -settings/-premium-activation JS for the activation notice
        wp_enqueue_script( CNB_SLUG . '-settings' );
        wp_enqueue_script( CNB_SLUG . '-premium-activation' );

        add_action( 'cnb_header_name', array( $this, 'header' ) );

        $data = $wp_list_table->prepare_items();

        if ( ! is_wp_error( $data ) ) {
            add_action( 'cnb_after_header', array( $this, 'cnb_create_new_button' ) );

            // Check if we should warn about inactive buttons
            $views        = $wp_list_table->get_views();
            $active_views = isset( $views['active'] ) ? $views['active'] : '';
            if ( false !== strpos( $active_views, '(0)' ) ) {
                $message = '<p>You have no active buttons. The Call Now Button is not visible to your visitors.</p>';
                CnbAdminNotices::get_instance()->warning( $message );
            }
        }

        wp_enqueue_script( CNB_SLUG . '-form-bulk-rewrite' );
        do_action( 'cnb_header' );

        echo '<div class="cnb-two-column-section">';
        echo '<div class="cnb-body-column">';
        echo '<div class="cnb-body-content">';

        echo sprintf( '<form class="cnb_list_event" action="%s" method="post">', esc_url( admin_url( 'admin-post.php' ) ) );
        echo '<input type="hidden" name="page" value="call-now-button-buttons" />';
        echo '<input type="hidden" name="action" value="cnb_buttons_bulk" />';
        $wp_list_table->views();
        $wp_list_table->display();
        echo '</form>';
        echo '</div>';
        echo '</div>';

        $this->render_promos( $cnb_cloud_domain );
        echo '</div>';

        // Do not add the modal code if something is wrong
        if ( ! is_wp_error( $data ) ) {
            $this->render_thickbox( $cnb_cloud_domain );
            $this->render_thickbox_quick_action();
        }
        do_action( 'cnb_footer' );
    }

    private function render_promos( $domain ) {
        $cnb_utils   = new CnbUtils();
        $upgrade_url = $cnb_utils->get_cnb_domain_upgrade( $domain );
        $support_url = $cnb_utils->get_support_url( '', 'promobox-need-help', 'Help Center' );
        $faq_url     = $cnb_utils->get_support_url( 'wordpress/#faq', 'promobox-need-help', 'FAQ' );
        if ( isset( $upgrade_url ) && $upgrade_url ) {
            echo '<div class="cnb-postbox-container cnb-side-column"> <!-- Sidebar promo boxes -->';
            if ( $domain !== null && ! ( $domain instanceof WP_Error ) && $domain->type === 'FREE' ) {
                $coupon                = ( new CnbAppRemotePromotionCodes() )->get_coupon();
                $discount_illustration = plugins_url( '../../../resources/images/discount.png', __FILE__ );
                if ( $coupon != null && ! is_wp_error( $coupon ) ) {
                    ( new CnbAdminFunctions() )->cnb_promobox(
                        'green',
                        'SPECIAL UPGRADE OFFER!',
                        '<h4>Get an extra ' . esc_html( $coupon->get_discount() ) . ' off the PRO plan!</h4>' .
                        '<p>Enter coupon code <code class="cnb-coupon-code">' . esc_html( $coupon->code ) . '</code> during checkout.</p>' .
                        '<div class="cnb-center" style="padding: 10px 30px"><img src="' . esc_url( $discount_illustration ) . '" alt="Upgrade your domain to PRO with an extra discount" style="max-width:300px; width:100%; height:auto;" /></div>',
                        'flag',
                        'Code: <code class="cnb-coupon-code">' . esc_html( $coupon->code ) . '</code>',
                        'Upgrade',
                        $upgrade_url
                    );
                } else {
                    ( new CnbAdminFunctions() )->cnb_promobox(
                        'purple',
                        'Professional features',
                        '<p>
                    ⚡ Slide-in Content Windows<a
                                    href="' . esc_url( $cnb_utils->get_website_url( 'integrations/#iframes', 'pro-upgrade', 'content-windows' ) ) . '"
                                    target="_blank" class="cnb-nounderscore"><span class="dashicons dashicons-editor-help"></span></a><br>
                        📷 Use custom images on buttons<br>
                        🌍 Include and exclude countries<br>
                        ↕️ Set scroll height for buttons to appear<br>
                        🔌 Intercom Chat integration<a
                                        href="' . esc_url( $cnb_utils->get_website_url( 'integrations/#intercom', 'pro-upgrade', 'intercom' ) ) . '"
                                        target="_blank" class="cnb-nounderscore"><span class="dashicons dashicons-editor-help"></span></a><br>
                        ✨ Remove the <em>Powered by</em> notice</p>',
                        'performance',
                        '<strong>&euro;<span class="eur-per-month"></span>/$<span class="usd-per-month"></span> per month</strong>',
                        'Upgrade',
                        $upgrade_url
                    );
                }
            }
            $support_illustration = plugins_url( '../../../resources/images/support.png', __FILE__ );
            ( new CnbAdminFunctions() )->cnb_promobox(
                'blue',
                'Need help?',
                '<p>Please head over to our <strong>Help Center</strong> for all your questions and support needs.</p>

                      <div class="cnb-right" style="padding: 10px 10px 10px 70px"><img src="' . esc_url( $support_illustration ) . '" alt="Our Help Center and support options" style="max-width:300px; width:100%; height:auto;" /></div>',
                'welcome-learn-more',
                '',
                'Open Help Center',
                $support_url
            );
            echo '</div>';
        }
        echo '<br class="clear">';
    }

    /**
     * @param $domain CnbDomain
     *
     * @return void
     */
    private function render_thickbox( $domain = null ) {
        add_thickbox();
        echo '<div id="cnb-add-new-modal" style="display:none;"><div>';

        if ( ! $domain ) {
            // Get the various supported domains
            $domain = CnbAppRemote::cnb_remote_get_wp_domain();
        }

        $button_id = 'new';

        // Create a dummy button
        $button = CnbButton::createDummyButton( $domain );

        $options = array( 'modal_view' => true, 'submit_button_text' => 'Next' );
        ( new CnbButtonViewEdit() )->render_form( $button_id, $button, $domain, $options );
        echo '</div></div>';

    }

    private function render_thickbox_quick_action() {
        $cnb_utils = new CnbUtils();
        $action    = $cnb_utils->get_query_val( 'action', null );
        if ( $action === 'new' ) {
            ?>
            <script>jQuery(function () {
                    setTimeout(cnb_button_overview_add_new_click);
                });</script>
            <?php
        }

        // Change the click into an actual "onClick" event
        // But only on the button-overview page and Action is not set or to "new"
        if ( $action === 'new' || $action == null ) {
            ?>
            <script>jQuery(function () {
                    const ele = jQuery("li.toplevel_page_call-now-button li:contains('Add New') a");
                    ele.attr('href', '#');
                    ele.on("click", cnb_button_overview_add_new_click)
                });</script>
            <?php
        }
    }
}
