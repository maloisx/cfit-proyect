function Validator() {

    this.countryCode = "";
    this.vatId = "";
    this.$checkoutForm = "";
    this.$invoiceFields = "";
    this.$requiredFields = "";
    this.submitCheckoutForm = true;
    this.isValid = true;
}

Validator.prototype.showBillingTab = function() {

  this.$checkoutForm.find(".tab-pane, .nav-link").removeClass("active show");
  this.$checkoutForm.find("#tabCheckoutBilling, [href='#tabCheckoutBilling']").addClass("active show");
};

Validator.prototype.displayErrorMessage = function(message, description) {

    var timestampId = Date.now();
    var $message = "" +
        "<div id='validation-error-" + timestampId + "' class='woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout'>" +
            "<div class='woocommerce-error'>" + message +
                (!!description ? "<p class=small>" + description + "</p>" : "") +
            "</div>" +
        "</div>";

    this.$checkoutForm.prepend($message);

    setTimeout(function() {
        jQuery("#validation-error-" + timestampId).fadeOut("slow").remove();
    }, 10000);

    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
};

Validator.prototype.isValidForCountry = function() {

    var validFormats = {
        "AT": /^U\d{8}$/m,
        "BE": /^\d{10}$/m,
        "BG": /^\d{9,10}$/m,
        "CY": /^\d{8}[A-Z]$/m,
        "CZ": /^\d{8,10}$/m,
        "DE": /^\d{9,11}$/m,
        "DK": /^\d{8}$/m,
        "EE": /^\d{9}$/m,
        "EL": /^\d{9}$/m,
        "GR": /^\d{9}$/m, // alias for EL
        "ES": /^[A-Z\d]\d{7}[A-Z\d]$/m,
        "FI": /^\d{8}$/m,
        "FR": /^[A-HJ-NP-Z\d]{2}\d{9}$/m,
        "GB": /^[A-Z\d]{2}\d{3}(\d{4}(\d{3})?)?$/m,
        "HR": /^\d{11}$/m,
        "HU": /^\d{8}$/m,
        "IE": /^\d[A-Z\d]\d{5}[A-Z]$/m,
        "IT": /^\d{11}$/m,
        "LT": /^\d{9}(\d{3})?$/m,
        "LU": /^\d{8}$/m,
        "LV": /^\d{11}$/m,
        "MT": /^\d{8}$/m,
        "NL": /^\d{9}[A-Z]\d{2}$/m,
        "PL": /^\d{10}$/m,
        "PT": /^\d{9}$/m,
        "RO": /^\d{2,10}$/m,
        "SE": /^\d{12}$/m,
        "SI": /^\d{8}$/m,
        "SK": /^\d{10}$/m
    };

    if (!validFormats[this.countryCode]) {
        return false;
    }

    return validFormats[this.countryCode].test( this.vatId.replace( /\s/g, "" ) );
};

Validator.prototype.isValidGeneral = function() {

    return /([A-Z]{2}[\S\-])?[A-Z\d]{2,12}/.test( this.vatId.replace(/\s/g, "") );
};

Validator.prototype.areAllFieldsFilled = function() {

    var needInvoice = jQuery("input[type=checkbox]#billing_invoice_checkbox").is(":checked");
    var isEuVatPayer = jQuery("input[type=checkbox]#billing_euvat_checkbox").is(":checked");

    var allFieldsFilled = true;

    if (needInvoice && isEuVatPayer) {

        this.$invoiceFields.each(function(index, element) {

            if (jQuery(element).val() === "") {
                allFieldsFilled = false;
            }

        });
    }

    this.$requiredFields.each(function(index, element) {

        if (jQuery(element).val() === "") {
            allFieldsFilled = false;
        }

    });

    return allFieldsFilled;
};

Validator.prototype.validateCheckoutForm = function(e) {
    e.preventDefault();

    this.countryCode = jQuery("select#billing_country").val();
    this.vatId = this.$checkoutForm.find("input#billing_vat").val();

    var needInvoice = jQuery("input[type=checkbox]#billing_invoice_checkbox").is(":checked");
    var isEuVatPayer = jQuery("input[type=checkbox]#billing_euvat_checkbox").is(":checked");
    if (needInvoice && isEuVatPayer) {

        var isValidGeneral = this.isValidGeneral();
        if (!isValidGeneral) {

            this.displayErrorMessage("Given VAT ID " + this.vatId + " is not valid.");
            this.showBillingTab();
            this.$checkoutForm.find("#billing_vat_field").addClass("woocommerce-invalid");
            this.isValid = false;

            return void 0;
        }

        var isValidForCountry = this.isValidForCountry();
        if (!isValidForCountry) {

            this.displayErrorMessage(
                "Given VAT ID " + this.vatId + " is not valid for your country.",
                "Remember to remove all invalid characters like dots, dashes or spaces. Also please remove country code if provided."
            );
            this.showBillingTab();
            this.$checkoutForm.find("#billing_vat_field").addClass("woocommerce-invalid");
            this.isValid = false;

            return void 0;
        }

    }

    var allFieldsFilled = this.areAllFieldsFilled();
    if (!allFieldsFilled) {

        this.displayErrorMessage("Please fill all required fields.");
        this.showBillingTab();
        this.$invoiceFields.closest('p.invoice-field').addClass("woocommerce-invalid");
        this.$requiredFields.closest('p').addClass("woocommerce-invalid");
        this.isValid = false;

        return void 0;
    }

    if (this.submitCheckoutForm) {

      this.$checkoutForm.submit();
    }
};

Validator.prototype.init = function() {

    this.$checkoutForm  = jQuery("form[name='checkout']");
    this.$invoiceFields = this.$checkoutForm.find(".invoice-field input").not("#billing_address_2");
    this.$requiredFields = this.$checkoutForm.find(".required-field");

    this.$checkoutForm.on("click", "[type=submit]", this.validateCheckoutForm.bind(this));
};

var validator = new Validator();
jQuery(document).ready(validator.init.bind(validator));
