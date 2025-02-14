$(document).ready(function () {
  alertify.set("notifier", "position", "top-right");

  $(document).on("click", ".increment", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodId").val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue)) {
      var qtyVal = currentValue + 1;
      $quantityInput.val(qtyVal);
      quantityIncDec(productId, qtyVal);
    }
  });

  $(document).on("click", ".decrement", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodId").val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue) && currentValue > 1) {
      var qtyVal = currentValue - 1;
      $quantityInput.val(qtyVal);
      quantityIncDec(productId, qtyVal);
    }
  });

  // alertify product

  // Function to handle adding items via AJAX
  function selectProduct(productId) {
    // Prevent default form submission
    event.preventDefault();

    // Get the quantity from the input field
    var quantity = document.querySelector('input[name="quantity"]').value;

    // Perform AJAX request to add the item
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: {
        product_id: productId,
        quantity: quantity,
      },
      success: function (response) {
        var res = JSON.parse(response); // Parse JSON response

        // Show notification based on response
        if (res.status === 200) {
          alertify.success(res.message);
        } else {
          alertify.error(res.message);
        }

        // Optionally reload the product area if needed
        $("#productArea").load(" #productArea > *");
      },
      error: function () {
        alertify.error("An error occurred while processing your request.");
      },
    });
  }

  // Attach the function to button click events
  $(document).on("click", ".product-button", function () {
    var productId = $(this).data("product-id");
    selectProduct(productId);
  });

  // prevent default form submission in AJAX wewew

  //proceed to place order
  $(document).on("click", ".proceedToPlace", function () {
    var cname = $("#cname").val();
    var payment_mode = $("#payment_mode").val();

    if (payment_mode == "") {
      swal("Select Payment Mode", "Select Payment Mode", "warning");
      return false;
    }

    if (cname == "") {
      swal(
        "Enter Your Name",
        "(THis is just for the your order to indentiy)",
        "warning"
      );
      return false;
    }

    var data = {
      proceedToPlaceBtn: true,
      cname: cname,
      payment_mode: payment_mode,
    };
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: data,
      success: function (response) {
        var res = JSON.parse(response);
        if (res.status == 200) {
          window.location.href = "order-summary.php";
        } else if (res.status == 404) {
          swal(res.message, res.message, res.status_type, {
            buttons: {
              catch: {
                text: "Add Customer",
                value: "catch",
              },
              cancel: "Cancel",
            },
          }).then((value) => {
            switch (value) {
              case "catch":
                $("#c_name").val(cname);
                break;
              default:
            }
          });
        } else {
          swal(res.message, res.message, res.status_type);
        }
      },
    });
  });

  // Handle saving order
  $(document).on("click", "#saveOrder", function () {
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: {
        saveOrder: true,
      },
      success: function (response) {
        var res = JSON.parse(response);

        if (res.status == 200) {
          swal(res.message, res.message, res.status_type);
          $("#orderPlaceSuccessMessage").text(res.message);
          $("#orderSuccessModal").modal("show");
        } else {
          swal(res.message, res.message, res.status_type);
        }
      },
    });
  });
});

function printMyBillingArea() {
  var divContents = document.getElementById("myBillingArea").innerHTML;

  var cssStyles = `
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 900px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #fff;
            }
            .header {
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .header h4 {
                margin: 0;
                font-size: 24px;
                font-weight: bold;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .header .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                color: #fff;
                background-color: #dc3545;
                text-decoration: none;
                font-size: 14px;
            }
            .body {
                padding: 20px;
            }
            .no-tracking {
                padding: 20px;
            }
            .no-tracking h5 {
                margin: 0;
                font-size: 18px;
            }
            .no-tracking .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                color: #fff;
                background-color: #007bff;
                text-decoration: none;
                font-size: 14px;
            }
            .invoice-info {
                width: 100%;
                margin-bottom: 20px;
                border-collapse: collapse;
            }
            .invoice-info td,
            .invoice-info th {
                padding: 8px;
                border: 1px solid #ddd;
            }
            .invoice-info .center {
                text-align: center;
            }
            .invoice-info .right {
                text-align: right;
            }
            .invoice-info h4 {
                font-size: 20px;
                margin: 0;
            }
            .invoice-info p {
                font-size: 14px;
                margin: 0;
            }
            .product-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            .product-table th,
            .product-table td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }
            .product-table th {
                background-color: #f4f4f4;
                font-weight: bold;
            }
            .product-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .product-table .bold {
                font-weight: bold;
            }
            .actions {
                margin-top: 20px;
                text-align: right;
            }
            .actions .btn {
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 4px;
                color: #fff;
                font-size: 14px;
                cursor: pointer;
            }
            .actions .btn-info {
                background-color: #17a2b8;
            }
            .actions .btn-primary {
                background-color: #007bff;
            }
        </style>
    `;

  // Open a new window for printing
  var a = window.open("", "");
  a.document.write(
    "<html><head><title>LAMESA POS SYSTEM | Reciept</title>" +
      cssStyles +
      "</head>"
  );
  a.document.write("<body>");
  a.document.write(divContents);
  a.document.write("</body></html>");
  a.document.close();
  a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo) {
  var elementHTML = document.querySelector("#myBillingArea");
  docPDF.html(elementHTML, {
    callback: function () {
      docPDF.save(invoiceNo + ".pdf");
    },
    x: 15,
    y: 15,
    width: 170,
    windowWidth: 800,
  });
}
