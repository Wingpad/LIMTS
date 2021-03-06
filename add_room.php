<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedRoom,
    ajax;

function onFormSubmitted() {
  var building = parseInt($("#building-selector option:selected").val());
  var number = $("#room-number").val();
  var capacity = $("#max-capacity").val();
  var handicap_accessible = $("#handicap-accessible").is(':checked');

  if (!building || !number || !capacity) {
    if (!building) {
      $("#building-selector").parents(".form-group").addClass("has-error");
    }

    if (!number) {
      $("#room-number").parents(".form-group").addClass("has-error");
    }

    if (!capacity) {
      $("#max-capacity").parents(".form-group").addClass("has-error");
    }

    $("#warning-alert").offcanvas("show");
  } else {
    $.ajax({
      dataType: "json",
      url: "creators/create_room.php",
      data: {
        handicapAccessible: handicap_accessible,
        capacity: capacity,
        number: number,
        building: building,
        id: editId
      },
      success: function(data) {
        if (data.response !== "Success") {
          alert(data.response);
        } else {
          window.location.href = "?page=calendar" + location.hash;
        }
      }
    });
  }
}

$(function() {
  $("#building-selector option:selected").change(function() {
    var parent = $("#building-selector").parents(".form-group");
    parent.removeClass("has-error");
    parent.addClass("has-success");
  });

  $("#room-number").change(function() {
    var parent = $("#room-number").parents(".form-group");
    parent.removeClass("has-error");
    parent.addClass("has-success");
  });

  $("#max-capacity").change(function() {
    var parent = $("#max-capacity").parents(".form-group");
    parent.removeClass("has-error");
    parent.addClass("has-success");
  });

  ajax = loadSelector("building", function() {
    var parent = $("#building-selector").parents(".form-group");
    parent.removeClass("has-error");
    parent.addClass("has-success");
  }, {
    addBlank: true,
    multiselect: false,
    precheck: false
  });

  // If "edit", load the existing data
  if (editId) {
    $("#form-header").text("Edit Room");
    ajaxLoadJSON("room", function(i, room) {
      loadedRoom = room;

      $("#building-selector").val(room.name);
      $("#room-number").val(room.nmbr);
      $("#max-capacity").val(room.cap);

      $.when(ajax).then(function() {
        $('#building-selector option[value="' + loadedRoom.building + '"]').prop("selected", true);
      });
    }, {
      id: editId
    });
  }
});
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
	<strong>Error!</strong> You must fill out the highlighted fields!
</div>

<h1 id="form-header">Add Room</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
	    <label for="building-selector">Building:</label>

    	<select class="form-control" id="building-selector" name="building">
	    </select>
    </div>

    <div class="form-group">
	    <label for="room-number">Room Number:</label>
	    <input type="text" class="form-control" id="room-number" name="room-number">
	</div>

    <div class="form-group">
	    <label for="max-capacity">Room Capacity:</label>
	    <input type="text" class="form-control" id="max-capacity" name="max-capacity">
    </div>

    <div class="form-group">
		<label class="checkbox-inline">
  			<input type="checkbox" id="handicap-accessible" value="handicap-accessible"> Handicap Accessible
		</label>
	</div>

    <div class="form-group">
    	<input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
