$('#complaintForm').on('submit', function (event) {
    debugger;
    event.preventDefault();
    var formData = $(this).serialize();
    var formId = $("#testId").val();
    $.ajax({
        type: 'POST',
        url: 'addComplaint.php',
        data: formData,
        success: function (response) {
            debugger;
            //$("#thankyouModal").modal('show');
            $("#complaintForm")[0].reset();

            window.setTimeout(function () { window.location.href = "yourComplaints.php?id=" + String(formId) }, 10000)
        }
    });
});