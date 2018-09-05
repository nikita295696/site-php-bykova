<h1><?= $test['name']?></h1>
<div class="container row">
<?php foreach ($test['categories'] as $category) { ?>
    <div class="col-lg-6 col-md-12">
        <h2><?= $category['name']?></h2>
        <?php foreach ($category['questions'] as $question) { ?>
            <p><?= $question['name']?></p>
        <?php } ?>
    </div>
<?php } ?>

</div>
