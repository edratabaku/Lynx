$('#ratingForm').on('submit', function (event) {
    debugger;
    event.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: 'POST',
        url: 'addReview.php',
        data: formData,
        success: function (response) {
            debugger
            //var data = $(this).serializeObject();
            //json_data = JSON.stringify(data);
            //$("#results").text(json_data);
            //$(".modal-body").text(json_data);
            $("#thankyouModal").modal('show');
            $("#ratingForm")[0].reset();
            
            window.setTimeout(function () { window.location.reload() }, 10000)
        }
    });
});