<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">


    <title><?= lang('Errors.whoops') ?></title>

    <style>

        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
    </style>
</head>
<body>

    <div class="container text-center">

<<<<<<< HEAD
        <h1 class="headline">Whoops!</h1>

        <p class="lead">We seem to have hit a snag. Please try again later...</p>
=======
        <h1 class="headline"><?= lang('Errors.whoops') ?></h1>

        <p class="lead"><?= lang('Errors.weHitASnag') ?></p>
>>>>>>> 5433b2f62ab061a030954900a99805757d5b4857

    </div>

</body>

</html>
