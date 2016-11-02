<footer class="main-footer">
     
        <center><strong>Copyright &copy; 2010-2015 <a href="http://sgc.co.il">SGC Systems</a></strong> All rights reserved.
       </center>
    </div>
</footer>

<script type="text/javascript">
var baseURL = '{{ url() }}';
</script>
<script src="{{ url('assets/js/jquery-2.1.4.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/js/jquery-ui.min.js') }}" type="text/javascript"></script>


<script src="{{ url('js/jsvalidation.min.js') }}" type="text/javascript"></script>
<script src="{{ url('js/bootbox.js') }}" type="text/javascript"></script>
<script src="{{ url('js/validate.js') }}" type="text/javascript"></script>
<script>
    jQuery(document).ready(function ($) {
        console.log("ready!");
        $('body').on('click', '.upload-image', function () {
            $(this).parent().find('input[type="file"]').click();
        });
    });
</script>
</body>
</html>
