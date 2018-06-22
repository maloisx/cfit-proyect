(function( $ ) {

  $(document).ready(function() {

    var $cartForm = $("#cart-form");
    $cartForm.on("click", "#proceed-to-checkout-button, #apply-coupon-code", function(e) {
      e.preventDefault();

      showPreloader($cartForm.find(".cart-content-container, .cart_totals"));

      var redirectTo = $(this).attr("data-default-href");

      var updateCartOrApplyCoupon = $(this).attr("id") === "apply-coupon-code" ? { apply_coupon: "Apply coupon" } : { update_cart: "Update Cart" };
      var formData = $cartForm.serializeArray().reduce(function(result, curr) {

        result = result || {};

        if (isComplexKey(curr.name)) {

          var key = curr.name;
          var parentKey = key.substring(0, key.indexOf("["));

          result[parentKey] = result[parentKey] || {};

          var transformedIntoObject = transformComplexIntoObject(key, curr.value);

          result[parentKey] = Object.assign({}, result[parentKey], transformedIntoObject);
        } else {

          result[curr.name] = curr.value;
        }

        return result;
      }, updateCartOrApplyCoupon);

      $.ajax({
        method: "POST",
        url: "/cart",
        data: formData
      })
      .done(function(response) {

        hidePreloader($cartForm.find(".cart-content-container, .cart_totals"));

        var $response = $(response);
        var $cartContent = $cartForm.find(".cart-content-container");
        var $cartTotals = $cartForm.find(".cart_totals");

        $cartContent.html($response.find(".cart-content-container").html());
        $cartTotals.html($response.find(".cart_totals").html());

        if (!!updateCartOrApplyCoupon.update_cart) {

          window.location.href = redirectTo;
        }
      });
    });

    function showPreloader(container) {

      var preloader =
        '<div class="checkout-preloader-container">' +
        '<div class="preloader-wrapper big active">' +
        '<div class="spinner-layer spinner-blue-only">' +
        '<div class="circle-clipper left">' +
        '<div class="circle"></div>' +
        '</div>' +
        '<div class="gap-patch">' +
        '<div class="circle"></div>' +
        '</div>' +
        '<div class="circle-clipper right">' +
        '<div class="circle"></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';

      $(container).prepend(preloader);
    }

    function hidePreloader(container) {

      $(container).find(".checkout-preloader-container").remove();
    }

    function isComplexKey(key) {
      return key.indexOf("[") !== -1;
    }

    function transformComplexIntoObject(key, deepestValue) {

      var resultObject;

      resultObject = cutOfParentKey(key);
      resultObject = getAllKeysAsArray(resultObject);
      resultObject = startBuildingTheResultFromTheEnd(resultObject);
      resultObject = transformReversedKeysIntoNestedObject(resultObject, deepestValue);

      return resultObject;
    }

    function cutOfParentKey(key) {
      return key.substring(key.indexOf("["));
    }

    function getAllKeysAsArray(key) {
      return key
        .replace(/\[/g, "")
        .split("]")
        .filter(function (el) {
          return el !== ""
        });
    }

    function startBuildingTheResultFromTheEnd(keys) {
      return keys.reverse();
    }

    function transformReversedKeysIntoNestedObject(keys, firstValue) {
      return keys
        .reduce(function (result, curr) {
          var temp = result;

          result = {};
          result[curr] = temp;

          return result;
        }, firstValue);
    }

    $cartForm.on("click", ".remove-cart-item", function (e) {
      e.preventDefault();

      showPreloader($cartForm.find(".cart-content-container, .cart_totals"));

      var url = $(this).attr("data-remove-url");

      $.ajax({
        method: "POST",
        url: url
      })
      .done(function(response) {

        hidePreloader($cartForm.find(".cart-content-container, .cart_totals"));

        var $response = $(response);
        var $cartContent = $cartForm.find(".cart-content-container");
        var $cartTotals = $cartForm.find(".cart_totals");
        var $chartCollaterals = $cartForm.find(".chart-collaterals");

        if ( $response.find(".cart-content-container").length ) {

          $cartContent.html($response.find(".cart-content-container").html());
          $cartTotals.html($response.find(".cart_totals").html());
          $chartCollaterals.html($response.find(".chart-collaterals").html());
        } else {

          $(".main-wrapper").html($response.find(".main-wrapper").html());
        }
      });
    });

    $cartForm.on("change", "input.form-control.qty", function (e) {
      e.preventDefault();

      var $itemTotal = $(this).closest("tr").find(".cart-item-total .amount");
      var $itemPrice = $(this).closest("tr").find(".cart-item-price .amount");
      var $orderSubtotal = $cartForm.find(".cart_totals .cart-subtotal .amount");
      var $orderTotal = $cartForm.find(".cart_totals .order-total .amount");

      var $itemTotalTextNode = getTextNodes($itemTotal).first();
      var $itemPriceTextNode = getTextNodes($itemPrice).first();
      var $orderSubtotalTextNode = getTextNodes($orderSubtotal).first();
      var $orderTotalTextNode = getTextNodes($orderTotal).first();

      var itemPrice = +$itemPriceTextNode.text();
      var itemQty = +$(this).val();

      $itemTotalTextNode.replaceWith(" " + (itemQty * itemPrice));
      var oldItemTotal = +$itemTotalTextNode.text().replace(/,/g, "");
      var newItemTotal = +getTextNodes($itemTotal).first().text().replace(/,/g, "");

      var increase = newItemTotal - oldItemTotal;
      var currentSubtotal = +$orderSubtotalTextNode.text().replace(/,/g, "");
      var currentTotal = +$orderTotalTextNode.text().replace(/,/g, "");

      $orderSubtotalTextNode.replaceWith(" " + (currentSubtotal + increase));
      $orderTotalTextNode.replaceWith(" " + (currentTotal + increase));
    });

    function getTextNodes($container) {
      return $container
        .contents()
        .filter(function() {
          return this.nodeType === 3; // `Node.TEXT_NODE`; IE7 does not define `Node` globally
        });
    }

    $cartForm.on("click", ".cross-sell-add-to-cart-btn", function (e) {
      e.preventDefault();

      var url = $(this).attr("href");
      var data = {
        "add-to-cart": $(this).attr("data-product-id")
      };

      addToCart(url, data);
    });

    $cartForm.on("change", ".cross-sell-add-to-cart-select", function (e) {

      var url = $(this).attr("data-cart-url");
      var data = {
        "add-to-cart": $(this).attr("data-product-id"),
        "variation_id": $(this).val()
      };

      addToCart(url, data);
    });

    function addToCart(cartUrl, data) {

      showPreloader($cartForm.find(".cart-content-container, .cart_totals"));

      $.ajax({
        method: "GET",
        url: cartUrl,
        data: data
      })
      .done(function (response) {

        var $response = $(response);
        var $cartContent = $cartForm.find(".cart-content-container");
        var $cartTotals = $cartForm.find(".cart_totals");
        var $chartCollaterals = $cartForm.find(".chart-collaterals");

        $cartContent.html($response.find(".cart-content-container").html());
        $cartTotals.html($response.find(".cart_totals").html());
        $chartCollaterals.html($response.find(".chart-collaterals").html());

        hidePreloader($cartForm.find(".cart-content-container, .cart_totals"));
      });
    }
  });

})( jQuery );
