<!DOCTYPE html>
<html>
<head>
    <title>AJAX CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Load jQuery FIRST -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- ✅ Then jQuery plugins -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <!-- ✅ Then Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <!-- ✅ CSS order doesn’t matter much, but keep it clean -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body>
    
<div class="container">
    <h1>AJAX CRUD</h1>
    <a class="btn btn-info" href="javascript:void(0)" id="createNewProduct"> Add New Product</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Description</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
<div class="modal fade" id="ajaxModelexa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal">
                   <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Name" value="" >
                            <span class="text-danger" id="titleError"></span>

                        </div>
                    </div>
     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-12">
                            <textarea id="description" name="description" required placeholder="Enter Description" class="form-control"></textarea>
                       <span class="text-danger" id="descriptionError"></span>
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="savedata" value="create">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
</body>
    
<script type="text/javascript">
  $(function () {
     
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'title', name: 'title'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
     
    $('#createNewProduct').click(function () {
        $('#savedata').val("create-product");
        $('#id').val('');
        $('#productForm').trigger("reset");
        $('#modelHeading').html("Create New Product");
        $('#ajaxModelexa').modal('show');
    });
    
    $('body').on('click', '.editProduct', function () {
      var id = $(this).data('id');
      $.get("{{ route('products.index') }}" +'/' + id +'/edit', function (data) {
        console.log(data,'d');
          $('#modelHeading').html("Edit Product");
          $('#savedata').val("edit-user");
          $('#ajaxModelexa').modal('show');
          $('#id').val(data.id);
          $('#title').val(data.title);
          $('#description').val(data.description);
      })
   });
    
    $('#savedata').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');
    
        $.ajax({
          data: $('#productForm').serialize(),
          url: "{{ route('products.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            // console.log(data, 'data');
     
              $('#productForm').trigger("reset");
              $('#ajaxModelexa').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              $('#savedata').html('Save Changes');
                // Clear previous errors
                 $('#titleError').text('');
                $('#descriptionError').text('');

                if(data.status === 422){
                    let errors = data.responseJSON.errors;

                    if (errors.title) {
                $('#titleError')
                    .removeClass('text-danger') // remove the red color class
                    .text(errors.title[0])
                    .css('color', 'red'); // ✅ Set the text and change color
                   }

                    if (errors.description) {
                        $('#descriptionError').text(errors.description[0]);
                    }
                   
                    
                }
          }
      });
    });
    
    $('body').on('click', '.deleteProduct', function () {
     
        var id = $(this).data("id");
        confirm("Are You sure want to delete this Product!");
      console.log(id, 'this is get back id and check to this work flow')
        $.ajax({
            type: "DELETE",
            url: "{{ route('products.store') }}"+'/'+id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>
</html>