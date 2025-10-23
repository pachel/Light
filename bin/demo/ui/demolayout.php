<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{TITLE}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .callout {
            --color: blue;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center"><?= $TITLE ?></h1>
    <div class="row">
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Defined variables</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">
                    <?php
                    echo "LAYOUT";
                    $vars = get_defined_vars();
                    foreach ($vars as $name => $var) {
                        if ($name == "content") {
                            continue;
                        }
                        echo "<br>var <b>\$" . $name . "</b> = " . $var;
                    }
                    ?>
                  </p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the demo.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text">
                    <!--[content:content]-->
                    <!--[content:content2]-->
                </p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the load from demo.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text"><!--[content:fromload]--></p>
            </div>
        </div>
        <div class="card col m-2">
            <div class="card-body">
                <h5 class="card-title">Content from the loadtolayout.html</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                <p class="card-text"><!--[load:loadtolayout.html]--></p>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>
</body>
</html>


