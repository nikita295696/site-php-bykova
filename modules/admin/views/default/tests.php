<?php

if(count($tests) > 0)
{
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Название</th>
            </tr>
        </thead>
<?php foreach ($tests as $test)   { ?>
        <tr>
            <td><?= $test['id']?></td>
            <td><a href="<?= \yii\helpers\Url::toRoute(['/admin/default/view', 'id'=>$test['id']])?>"><?= $test['name']?></a></td>
        </tr>
    <?php } ?>
    </table>
    <p>Добавить <a href="<?= \yii\helpers\Url::toRoute(['/'.$this->context->module->id.'/default/create'])?>">новый</a></p>
    <?php
}
else{
    echo '<h3>У вас еще нет тестов, добавьте их <a href="'.\yii\helpers\Url::toRoute(['/'.$this->context->module->id.'/default/create']).'">здесь</a></h3>';
}