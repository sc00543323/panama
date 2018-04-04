  <div id="myModel">
  </div>
  
  <script>

    require(
        [
            'jquery',
            'Magento_Ui/js/modal/modal'
        ],
        function($,modal) {
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Product Question',
                closeText: 'Close'               
              
            };
            var popup = modal(options, $('#myModel'));
            $("#openModel").on("change",function(){
                $('#myModel').modal('openModal');
				$('.modal-footer').hide(); 
            });



        }
    );



</script>