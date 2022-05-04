document.addEventListener("DOMContentLoaded", function () {
  jQuery(document).ready(function ($) {
    //check if the redirect input exists
    if ($("#flw-elementor-redirecturl").length) {
      $("#flw-elementor-redirecturl").attr(
        "value",
        f4b_data.apiUrl + "/flutterwave-for-business/v1/verifytransaction"
      );
    }
  });
});
