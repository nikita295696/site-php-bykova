<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Система тестирования знаний!</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12" id="selectTests">

            </div>

            <div class="col-lg-12">
                <h2>Вопросы <span id="countAnswers"></span></h2>
                <div id="tests">

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let mssQuestions = [];
    let currQuest = 0;
    //let currCat = 0;

    let mssUserAnswer = [];

    $.ajax({
        url: "<?= Yii::$app->homeUrl?>/api/test-pub/tests-names",
        method: 'get',
        success: function (json) {
            console.log(json);

            let html = "";
            html += "<form id='formSelectedTest row' onsubmit='return false'>";
                html += "<select id='selectTestId' class='form-control'>";
                for(let i =0; i<json.length; i++)
                {
                    html += "<option value='"+json[i]['id']+"'>"+json[i]['name']+"</option>";
                }
                html += "</select>";
            html += "<input class='form-control' placeholder='Количество вопросов' name='countAnswer' value='2'/>";
            html += "<button id='btnGetTest' class='btn btn-primary'>Начать тест</button>";
            html += "</form>";

            $('#selectTests').html(html);

            $('#btnGetTest').click(function (event) {
                $.ajax({
                    url: "<?= Yii::$app->homeUrl?>/api/test-pub/generate-test?id="+$('#selectTestId').val() + '&quest_count=' + $('[name=countAnswer]').val(),
                    method: 'get',
                    success: function (json) {
                        //mssQuestions = json;
                        mssQuestions = [];
                        mssUserAnswer = [];
                        //console.log(json);
                        currQuest = 0;
                        //currCat = 0;

                        for(let i = 0; i < json['categories'].length; i++){
                            let categories = json['categories'];
                            for(let idxQuest = 0; idxQuest < categories[i]['questions'].length; idxQuest++){
                                mssQuestions.push(categories[i]['questions'][idxQuest]);
                            }
                        }

                        //console.log(mssQuestions);

                        let htmlQuestion = "<p id='question'>"+mssQuestions[currQuest]['name']+"<p>";
                        htmlQuestion += "<input class='form-control' id='answer'/>";
                        htmlQuestion += "<button id='nextQuest'>Далее</button>"

                        $('#tests').html(htmlQuestion);

                        $("#nextQuest").click(function (event) {

                            if((currQuest+1) >= mssQuestions.length)
                            {
                                let newHtml = "";

                                for(let idxAnswer = 0; idxAnswer < mssUserAnswer.length; idxAnswer++){
                                    newHtml += "<p>Вопрос: " + mssUserAnswer[idxAnswer]['question'] + ", ответ: " + mssUserAnswer[idxAnswer]['answer'] + "</p>";
                                }

                                $('#tests').html(newHtml);
                            }
                            else{
                                mssUserAnswer.push({question: mssQuestions[currQuest]['name'], answer: $('#answer').val()});
                                currQuest++;
                                $('#question').html(mssQuestions[currQuest]['name']);
                                $('#answer').val("");

                            }


                        });
                    }
                });

                return false;
            });
        }
    });
</script>
