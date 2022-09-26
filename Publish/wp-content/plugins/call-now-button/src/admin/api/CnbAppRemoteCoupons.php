<?php

namespace cnb\admin\api;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\admin\models\CnbPlan;
use cnb\coupons\CnbPromotionCode;
use WP_Error;

class CnbAppRemotePromotionCodes {

    /**
     * @return CnbPromotionCode|WP_Error
     */
    public function get_coupon() {
        $rest_endpoint = '/v1/stripe/coupons/wp';

        return CnbPromotionCode::fromObject( CnbAppRemote::cnb_remote_get( $rest_endpoint ) );
    }
}
