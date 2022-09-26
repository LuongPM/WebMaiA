function cnb_action_appearance() {
    jQuery('#cnb_action_type').on('change', function (obj) {
        cnb_action_update_appearance(obj.target.value)
    })

    // Setup WHATSAPP integration
    const input = document.querySelector("#cnb_action_value_input_whatsapp")
    if (!input || !window.intlTelInput) {
        return
    }

    const iti = window.intlTelInput(input, {
        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.12/js/utils.min.js',
        nationalMode: false,
        separateDialCode: true,
        hiddenInput: 'actionValueWhatsappHidden'
    })

    // here, the index maps to the error code returned from getValidationError - see readme
    const errorMap = [
        'Invalid number',
        'Invalid country code',
        'Too short',
        'Too long',
        'Invalid number']

    const errorMsg = jQuery('#cnb-error-msg')
    const validMsg = jQuery('#cnb-valid-msg')

    const reset = function() {
        input.classList.remove('error')
        errorMsg.html('')
        errorMsg.hide()
        validMsg.hide()
    }

    const onBlur = function() {
        reset()
        if (input.value.trim()) {
            if (iti.isValidNumber()) {
                validMsg.show()
            } else {
                const errorCode = iti.getValidationError()
                if (errorCode < 0) {
                    // Unknown error, ignore for now
                    return
                }
                input.classList.add('error')
                errorMsg.text(errorMap[errorCode])
                errorMsg.show()
            }
        } else {
            // Empty
            reset()
        }
    }

    // on blur: validate
    input.addEventListener('blur', onBlur)

    // on keyup / change flag: reset
    input.addEventListener('change', onBlur)
    input.addEventListener('keyup', onBlur)

    // init
    onBlur()
}

function cnb_action_update_appearance(value) {
    const emailEle = jQuery('.cnb-action-properties-email')
    const emailExtraEle = jQuery('.cnb-action-properties-email-extra')
    const linkEle = jQuery('.cnb-action-properties-link')
    const whatsappEle = jQuery('.cnb-action-properties-whatsapp')
    const whatsappExtraEle = jQuery('.cnb-action-properties-whatsapp-extra')
    const signalEle = jQuery('.cnb-action-properties-signal')
    const smsEle = jQuery('.cnb-action-properties-sms')
    const smsExtraEle = jQuery('.cnb-action-properties-sms-extra')
    const mapEle = jQuery('.cnb-action-properties-map')
    const iframeEle = jQuery('.cnb-action-properties-iframe')
    const tallyEle = jQuery('.cnb-action-properties-tally')
    const intercomEle = jQuery('.cnb-action-properties-intercom')
    const anchorEle = jQuery('.cnb-action-properties-anchor')

    const valueEle = jQuery('.cnb-action-value')
    const valueTextEle = jQuery('#cnb_action_value_input')
    const valuelabelEle = jQuery('#cnb_action_value')
    const whatsappValueEle = jQuery('#cnb_action_value_input_whatsapp')
    const intlInputLabel = jQuery('#cnb_action_value_input_intl_input')

    const $all = emailEle
        .add(emailExtraEle)
        .add(linkEle)
        .add(whatsappEle)
        .add(whatsappExtraEle)
        .add(signalEle)
        .add(smsEle)
        .add(smsExtraEle)
        .add(mapEle)
        .add(iframeEle)
        .add(tallyEle)
        .add(intercomEle)
        .add(anchorEle)

    $all.hide()

    valueEle.show()
    valueTextEle.prop( 'disabled', false )
    whatsappValueEle.prop( 'disabled', true )

    valueTextEle.removeAttr("required")
    whatsappValueEle.removeAttr("required")

    switch (value) {
        case 'ANCHOR':
            valuelabelEle.text('Page anchor')
            valueTextEle.attr("required", "required")
            anchorEle.show()
            break
        case 'EMAIL':
            valuelabelEle.text('E-mail address')
            valueTextEle.attr("required", "required")
            emailEle.show()
            break
        case 'LINK':
            valuelabelEle.text('Full URL')
            valueTextEle.attr("required", "required")
            linkEle.show()
            break
        case 'MAP':
            valuelabelEle.text('Address')
            valueTextEle.attr("required", "required")
            mapEle.show()
            break
        case 'PHONE':
            valuelabelEle.text('Phone number')
            valueTextEle.attr("required", "required")
            break
        case 'SMS':
            valuelabelEle.text('Phone number')
            valueTextEle.attr("required", "required")
            smsEle.show()
            // SMS has a field conflict with WhatsApp, fix it
            jQuery('#action-properties-message-whatsapp').attr('disabled', true)
            jQuery('#action-properties-message-sms').attr('disabled', false)
            break
        case 'WHATSAPP':
            valuelabelEle.text('WhatsApp number')
            intlInputLabel.text('WhatsApp number')
            valueEle.hide()
            valueTextEle.prop( 'disabled', true )
            whatsappValueEle.prop( 'disabled', false )
            whatsappValueEle.attr("required", "required")
            whatsappEle.show()

            // WhatsApp has a field conflict with SMS, fix it
            jQuery('#action-properties-message-whatsapp').attr('disabled', false)
            jQuery('#action-properties-message-sms').attr('disabled', true)

            // To ensure the modal properties are correct, fix them after revealing all
            cnb_set_action_modal_fields()
            break
        case 'FACEBOOK':
        case 'TELEGRAM':
            valuelabelEle.text('Username')
            valueTextEle.attr("required", "required")
            break
        case 'SIGNAL':
            valuelabelEle.text('Signal number')
            intlInputLabel.text('Signal number')
            valueEle.hide()
            valueTextEle.prop( 'disabled', true )
            whatsappValueEle.prop( 'disabled', false )
            whatsappValueEle.attr("required", "required")
            signalEle.show()
            break
        case 'IFRAME':
            valuelabelEle.text('Iframe URL')
            valueTextEle.attr("required", "required")
            iframeEle.show()
            break
        case 'TALLY':
            valuelabelEle.text('Tally Form ID')
            valueTextEle.attr("required", "required")
            iframeEle.show()
            tallyEle.show()
            break
        case 'INTERCOM':
            valuelabelEle.text('Intercom App ID')
            valueTextEle.attr("required", "required")
            intercomEle.show()
            break
        default:
            valuelabelEle.text('Action value')
            valueTextEle.attr("required", "required")
    }
    cnb_clean_up_advanced_view()
}

function cnb_action_update_map_link(element) {
    jQuery(element).prop("href", "https://maps.google.com?q=" + jQuery('#cnb_action_value_input').val())
}

function cnb_action_iframe_modal_height() {
    const value = jQuery('#cnb-action-properties-modal-height-value')
    const unit = jQuery('#cnb-action-properties-modal-height-unit')

    const updateVal = () => {
        let v = parseInt(value.val())
        const u = unit.val() // "px" or "vh"

        // px defaults
        let min = 250
        let max = 1500

        // Update slider val
        if (u === 'vh') {
            min = 20
            max = 100
        }
        value.attr('min', min)
        value.attr('max', max)

        if (v < min) {
            v = min
        }
        if (v > max) {
            v = max
        }

        // Update the slider left/right "helper" value
        jQuery('#cnb-action-properties-modal-height-value-min').text(min)
        jQuery('#cnb-action-properties-modal-height-value-max').text(max)

        // Update the fields that hold the result
        const result = v + u
        jQuery('#cnb-action-properties-modal-height').val(result)
        jQuery('.cnb-action-properties-modal-height-result').text(result)
    }

    value.on('change input', updateVal)
    unit.on('change input', updateVal)

    // Also run on render
    updateVal()
}

function cnb_action_settings_section() {
  jQuery(".cnb-settings-section-table").addClass("cnb-settings-section-collapsed")
  jQuery(".cnb-settings-section-title").on('click', function() {
    const section = jQuery(this).data("cnb-settings-block")
    jQuery(this).find(".dashicons-arrow-right").toggleClass("cnb-rotate-90")
    jQuery(".cnb-settings-section-" + section + " .cnb-settings-section-table").toggleClass("cnb-settings-section-collapsed")
      // To ensure the modal properties are correct, fix them after revealing all
    cnb_set_action_modal_fields()
  });
}

jQuery( function() {
    cnb_action_appearance()
    cnb_action_update_appearance(jQuery('#cnb_action_type').val())
    cnb_action_iframe_modal_height()
    cnb_action_settings_section()
})
