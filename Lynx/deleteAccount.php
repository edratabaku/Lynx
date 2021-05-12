<?php
require_once "configuration.php";
$id = $_REQUEST["id"];
$update = mysqli_query($mysqli,"UPDATE Users SET IsActive=0 WHERE Id='$id'");
header("logout.php");
?>




if(count($_POST)>0){
38
    if($_POST['type']==3){
39
        $id=$_POST['id'];
40
        $sql = "DELETE FROM `student` WHERE id=$id ";
41
        if (mysqli_query($conn, $sql)) {
42
            echo $id;
43
        }
44
        else {
45
            echo "Error: " . $sql . "
<br />" . mysqli_error($conn);
46
        }
47
        mysqli_close($conn);
48
    }
49
}
$(document).on("click", ".delete", function() {
53
        var id=$(this).attr("data-id");
54
        $('#id_d').val(id);
55
         
56
    });
57
    $(document).on("click", "#delete", function() {
58
        $.ajax({
59
            url: "save.php",
60
            type: "POST",
61
            cache: false,
62
            data:{
63
                type:3,
64
                id: $("#id_d").val()
65
            },
66
            success: function(dataResult){
67
                location.reload(); 
68
                    $('#deleteEmployeeModal').modal('hide');
69
                    $("#"+dataResult).remove();
70
                     
71
            }
72
        });
73
    });
74
    $(document).on("click", "#delete_multiple", function() {
75
        var user = [];
76
        $(".user_checkbox:checked").each(function() {
77
            user.push($(this).data('user-id'));
78
        });
79
        if(user.length <=0) {
80
            alert("Please select records.");
81
        }
82
        else {
83
            WRN_PROFILE_DELETE = "Are you sure you want to delete "+(user.length>1?"these":"this")+" row?";
84
            var checked = confirm(WRN_PROFILE_DELETE);
85
            if(checked == true) {
86
                var selected_values = user.join(",");
87
                console.log(selected_values);
88
                $.ajax({
89
                    type: "POST",
90
                    url: "save.php",
91
                    cache:false,
92
                    data:{
93
                        type: 4,                       
94
                        id : selected_values
95
                    },
96
                    success: function(response) {
97
                        var ids = response.split(",");
98
                        for (var i=0; i < ids.length; i++ ) {   
99
                            $("#"+ids[i]).remove();
100
                        }  
101
                    }
102
                });location.reload();   
103
            } 
104
        }
105
    });
106
    $(document).ready(function(){
107
        $('[data-toggle="tooltip"]').tooltip();
108
        var checkbox = $('table tbody input[type="checkbox"]');
109
        $("#selectAll").click(function(){
110
            if(this.checked){
111
                checkbox.each(function(){
112
                    this.checked = true;                       
113
                });
114
            } else{
115
                checkbox.each(function(){
116
                    this.checked = false;                       
117
                });
118
            }
119
        });
120
        checkbox.click(function(){
121
            if(!this.checked){
122
                $("#selectAll").prop("checked", false);
123
            }
124
        });
125
    });


