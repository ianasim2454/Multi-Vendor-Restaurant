$(function(){
    $(document).on('click','#delete',function(e){
        e.preventDefault();
        var link = $(this).attr("href");

  
                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete This Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                      )
                    }
                  }) 


    });

  });



  $(function(){
    $(document).on('click','#confirmOrder',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
  
                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Confirm This Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Confirm it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Confirm!',
                        'Your file has been Confirm.',
                        'success'
                      )
                    }
                  }) 
    });
  });



  $(function(){
    $(document).on('click','#processingOrder',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
  
                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Processing This Order?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Processing it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Processing!',
                        'Your Order has been Processing.',
                        'success'
                      )
                    }
                  }) 
    });
  });

  
  $(function(){
    $(document).on('click','#deliverdOrder',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
  
                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Deliverd This Order?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, deliverd it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'deliverd!',
                        'Your Order has been deliverd.',
                        'success'
                      )
                    }
                  }) 
    });
  });

