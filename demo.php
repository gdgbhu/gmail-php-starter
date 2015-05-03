<?php include('gmail.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GMail API PHP Starter</title>

    <!-- Bootstrap -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="bower_components/summernote/dist/summernote.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $_SERVER['PHP_SELF']; ?>">MyGMail</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if(isset($loginUrl)) { ?>
                    <li class="active"><a href="<?php echo $loginUrl; ?>"><i class="glyphicon glyphicon-log-in"></i> Sign In</a></li>
                <?php } else { ?>
                    <li class="active"><a href="?logout"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                <?php } ?>

            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">
    <?php if(isset($loginUrl) || $authException) { ?>
    <div class="row">
        <div class="col-lg-8">
            <a class="btn btn-primary" href="<?php echo $loginUrl; ?>"><i class="glyphicon glyphicon-log-in"></i> Login to MyGMail</a>
        </div>
    </div>
    <?php } else { ?>

    <div class="row">
        <?php echo $notice; ?>

        <div class="col-lg-8">
            <h3>Compose Email</h3>
            <div class="well well-lg">

                <form role="form" name="gmail-form" method="post">
                    <div class="form-group">
                        <input type="email" class="form-control" id="to" name="to" placeholder="To:">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="cc" name="cc" placeholder="Cc:">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="bcc" name="bcc" placeholder="Bcc:">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject:">
                    </div>
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success pull-right" name="send">
                            <i class="glyphicon glyphicon-play"></i>
                            Send
                        </button>
                        <button type="submit" class="btn btn-info" name="draft">
                            <i class="glyphicon glyphicon-pencil"></i>
                            Save as Draft
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <div class="col-lg-4">
            <h3>Inbox</h3>
            <ul class="list-group">
                <?php if(count($inboxMessage) > 0) { ?>
                    <?php foreach($inboxMessage as $item) { ?>
                        <li class="list-group-item">
                            <?php echo $item['messageSubject']; ?><br>
                            <a href="https://mail.google.com/mail/u/0/?tab=wm&pli=1#inbox/<?php echo $item['messageId']; ?>" target="_blank">Read message</a>
                        </li>
                    <?php } ?>
                    <li class="list-group-item" style="text-align: center;">
                        <a href="https://mail.google.com/mail/u/0/?tab=wm&pli=1#inbox" target="_blank">See all messages</a>
                    </li>
                <?php } else { ?>
                    <li class="list-group-item">Your inbox is empty.</li>
                <?php } ?>

            </ul>
        </div>
    </div>
        <?php } ?>


</div><!-- /.container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/summernote/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#message').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['undo', ['undo']],
                ['redo', ['redo']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    });
</script>
</body>
</html>
 