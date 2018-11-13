<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">


    <title>Contact list</title>
  </head>
  <body>
      <div class="container">
       <div class="row">
        <div class="col-lg-12">
   
            <h4>Contact list
                <a onclick="addForm()" class="btn btn-sm btn-primary" >Add Contact</a>
            </h4>
              
            <table id="contact-table" class="cell-border compact stripe ">
                <thead>
                  <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>phone</th>
                      <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>         
            </div>
        </div>
        @include('form');
      </div>
   </div>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>



    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>


 
    <script type="text/javascript">
     var table1 = $('#contact-table').DataTable({
            ajax: "{{ route('all.contact') }}",
            columns: [
              {data:'name', name:'name'},
              {data:'email', name:'email'},
              {data:'phone', name:'phone'},
              {data:'action', name:'action'}
            ]
          });
     
      function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-form').modal('show');
        $('#modal-form form')[0].reset();
        $('.modal-title').text('Add Contact');
        $('#insertbutton').text('Add Contact');
      }

         $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('contact') }}";
                    else url = "{{ url('contact') . '/' }}" + id;
                    console.log(url)
                    $.ajax({
                       url : url,
                       type : "POST",
                       data: new FormData($("#modal-form form")[0]),
                       contentType: false,
                       processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table1.ajax.reload();
                            swal({
                              title: "Good job!",
                              text: "You clicked the button!",
                              icon: "success",
                              button: "Great!",
                            });
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    
       function showData(id) {
          $.ajax({
              url: "{{ url('contact') }}" + '/' + id,
              type: "GET",
              dataType: "JSON",
            success: function(data) {
              $('#single-data').modal('show');
              $('.modal-title').text(data.name +' '+'Informations');
              $('#contactid').text(data.id); 
              $('#fullname').text(data.name);
              $('#contactemail').text(data.email);
              $('#contactnumber').text(data.phone);

            },
            error : function() {
                alert("Oops...");
            }
          });
        }
    

         function editForm(id) {
         save_method = 'edit';
          $('input[name=_method]').val('PATCH');
          $('#modal-form form')[0].reset();
          $.ajax({
            url: "{{ url('contact') }}" + '/' + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
              $('#modal-form').modal('show');
              $('.modal-title').text('Edit Contact');
              $('#insertbutton').text('Update Contact');
              $('#id').val(data.id);
              $('#name').val(data.name);
              $('#email').val(data.email);
              $('#phone').val(data.phone);
            },
            error : function() {
                alert("Nothing Data");
            }
          });
        }    

      function deleteData(id){
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                  url : "{{ url('contact') }}" + '/' + id,
                  type : "POST",
                  data : {'_method' : 'DELETE', '_token' : csrf_token},
                  success : function(data) {
                      table1.ajax.reload();
                      swal({
                        title: "Delete Done!",
                        text: "You clicked the button!",
                        icon: "success",
                        button: "Done",
                      });
                  },
                  error : function () {
                      swal({
                          title: 'Oops...',
                          text: data.message,
                          type: 'error',
                          timer: '1500'
                      })
                  }
              });
            } else {
              swal("Your imaginary file is safe!");
            }
          });

        }

    </script>
  </body>
</html>