<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div id="closest-number-wrapper" class="closest-number-wrapper">
    <div class="closest-number-block">
        <div class="closest-number-title">PHP <i>(метод chunk)</i></div>
        <div class="closest-number-data">
            <div class="closest-number-array-wrapper">
                <input id="closest-number-array-chunk" class="closest-number-array" type="text" name="chunk" />
                <label class="closest-number-array-label" for="closest-number-array-chunk">Введите цифры массива через запятую <i>[-60, -50, -40, -30, -20, -10, 50, 60, 70, 90]</i></label>
            </div>
            <div class="closest-number-input-wrapper">
                <input id="closest-number-input-chunk" class="closest-number-input" type="text" name="number_chunk" />
                <label for="closest-number-input-chunk">Введите число</label>
            </div>
            <div class="closest-number-min-value-wrapper">
                <input id="closest-number-min-value-chunk" class="closest-number-min-value" type="checkbox" />
                <label for="closest-number-min-value-chunk">Получить меньшее число при равнозначном удалении искомого от чисел</label>
            </div>
            <div class="closest-number-send-data" data-method="chunk">Отправить</div>
        </div>
        <div id="closest-number-result-chunk" class="closest-number-result">Ближайшее число в массиве: «<span id="closest-number-result-text-chunk"></span>»</div>
        <div id="closest-number-warn-chunk" class="closest-number-warn"></div>
    </div>
    <div class="closest-number-block">
        <div class="closest-number-title">PHP <i>(метод reduce)</i></div>
        <div class="closest-number-data">
            <div class="closest-number-array-wrapper">
                <input id="closest-number-array-reduce" class="closest-number-array" type="text" name="reduce" />
                <label class="closest-number-array-label" for="closest-number-array-reduce">Введите цифры массива через запятую <i>[-60, -50, -40, -30, -20, -10, 50, 60, 70, 90]</i></label>
            </div>
            <div class="closest-number-input-wrapper">
                <input id="closest-number-input-reduce" class="closest-number-input" type="text" name="number_reduce" />
                <label for="closest-number-input-reduce">Введите число</label>
            </div>
            <div class="closest-number-send-data" data-method="reduce">Отправить</div>
        </div>
        <div id="closest-number-result-reduce" class="closest-number-result">Ближайшее число в массиве: «<span id="closest-number-result-text-reduce"></span>»</div>
        <div id="closest-number-warn-reduce" class="closest-number-warn"></div>
    </div>
    <div class="closest-number-block">
        <div class="closest-number-title">PHP <i>(метод iter)</i></div>
        <div class="closest-number-data">
            <div class="closest-number-array-wrapper">
                <input id="closest-number-array-iter" class="closest-number-array" type="text" name="iter" />
                <label class="closest-number-array-label" for="closest-number-array-iter">Введите цифры массива через запятую <i>[-60, -50, -40, -30, -20, -10, 50, 60, 70, 90]</i></label>
            </div>
            <div class="closest-number-input-wrapper">
                <input id="closest-number-input-iter" class="closest-number-input" type="text" name="number_iter" />
                <label for="closest-number-input-iter">Введите число</label>
            </div>
            <div class="closest-number-send-data" data-method="iter">Отправить</div>
        </div>
        <div id="closest-number-result-iter" class="closest-number-result">Ближайшее число в массиве: «<span id="closest-number-result-text-iter"></span>»</div>
        <div id="closest-number-warn-iter" class="closest-number-warn"></div>
    </div>
    <div class="closest-number-block">
        <div class="closest-number-title">JS</div>
        <div class="closest-number-data">
            <div class="closest-number-array-wrapper">
                <input id="closest-number-array-js" class="closest-number-array" type="text" name="chunk" />
                <label class="closest-number-array-label" for="closest-number-array-js">Введите цифры массива через запятую <i>[-60, -50, -40, -30, -20, -10, 50, 60, 70, 90]</i></label>
            </div>
            <div class="closest-number-input-wrapper">
                <input id="closest-number-input-js" class="closest-number-input" type="text" name="number_chunk" />
                <label for="closest-number-input-js">Введите число</label>
            </div>
            <div id="closest-number-send-data-js" class="closest-number-send-data-js" data-method="js">Отправить</div>
        </div>
        <div id="closest-number-result-js" class="closest-number-result">Ближайшее число в массиве: «<span id="closest-number-result-text-js"></span>»</div>
        <div id="closest-number-warn-js" class="closest-number-warn"></div>
    </div>
</div><!--/.wrapper-->
