BX.ready(() => {

    let statisticsWrapper = document.getElementById('news-statistics-wrapper');
    let ajaxWrapper = statisticsWrapper.parentNode;

    //Выбор пользователя
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.id === 'news-statistics-change-user') {
            let onloadWindow = BX.PopupWindowManager.create("onload-window-user-search", null, {
                content: '<div class="search-user-input-wrapper">' +
                    '<input id="search-user-input" class="search-user-input" type="text" name="SEARCH_USER" placeholder="Имя, фамилия или ID пользователя" />' +
                    '<span id="search-user-input-button" class="search-user-input-button">Найти</span>' +
                    '</div>' +
                    '<div class="search-user-results-wrapper">' +
                    '<ul id="search-user-results" class="search-user-results"></ul>' +
                    '</div>',
                width: 400,
                height: 200,
                zIndex: 100,
                closeIcon: {},
                titleBar: 'Поиск пользователя...',
                closeByEsc: true,
                darkMode: false,
                autoHide: true,
                draggable: false,
                resizable: false,
                min_height: 200,
                min_width: 400,
                lightShadow: true,
                angle: false,
                overlay: {
                    //объект со стилями фона
                    backgroundColor: 'black',
                    opacity: 500
                },
                buttons: [],
                events: {
                    onPopupShow: () => {
                        let searchUserInput = document.getElementById('search-user-input');
                        let searchUsersList = document.getElementsByClassName('news-statistics-user-point');
                        let searchUserButton = document.getElementById('search-user-input-button');
                        let searchUserResults = document.getElementById('search-user-results');
                        searchUserButton.onclick = () => {
                            let searchUserResultList = {};
                            if (searchUserInput.value != '') {
                                for (let i = 0; i < searchUsersList.length; i++) {
                                    let userID = searchUsersList[i].getAttribute('data-user-id');
                                    let userName = searchUsersList[i].getAttribute('data-user-name');
                                    if (
                                        userID.indexOf(searchUserInput.value) != '-1' ||
                                        userName.indexOf(searchUserInput.value) != '-1'
                                    ) {
                                        if (typeof searchUserResultList[userID] != 'object') searchUserResultList[userID] = {};
                                        searchUserResultList[userID] = userName;
                                    }
                                }
                            }
                            let objectLength = 0;
                            for (let searchUserResultuserID in searchUserResultList) {
                                if (searchUserResultList.hasOwnProperty(searchUserResultuserID)) objectLength++;
                            }
                            if (objectLength > 0) {
                                searchUserResults.innerHTML = '';
                                for (let searchUserResultuserID in searchUserResultList) {
                                    let searchResultLIWrapper = document.createElement('li');
                                    searchResultLIWrapper.classList.add('search-result-li-wrapper');
                                    searchResultLIWrapper.setAttribute('data-user-id', searchUserResultuserID);
                                    searchResultLIWrapper.innerText = searchUserResultList[searchUserResultuserID];
                                    searchUserResults.append(searchResultLIWrapper);
                                    searchResultLIWrapper.onclick = () => {
                                        let userID = searchResultLIWrapper.getAttribute('data-user-id');
                                        if (userID != '') {
                                            BX.ajax({
                                                method: 'POST',
                                                data: {USER_ID: userID},
                                                url: window.location.href + 'index.php',
                                                dataType: 'html',
                                                timeout: '60',
                                                async: true,
                                                processData: false,
                                                scriptsRunFirst: true,
                                                emulateOnload: true,
                                                start: true,
                                                cache: false,
                                                onsuccess: function (success) {
                                                    onloadWindow.close();
                                                    ajaxWrapper.innerHTML = success;
                                                    document.getElementById('news-statistics-post-diagramms-title').click();
                                                },
                                                onfailure: function (error) {

                                                }
                                            });
                                        }
                                    }
                                }
                            } else {
                                let searchResultLIWrapper = document.createElement('li');
                                searchResultLIWrapper.classList.add('zero-search-result-li-wrapper');
                                searchResultLIWrapper.innerText = 'Никого не найдено';
                                searchUserResults.append(searchResultLIWrapper);
                            }
                        }
                    },
                    onPopupClose: () => {

                    }
                }
            });
            onloadWindow.show();
        }
    });
    //}

    //Удаление тэга
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className === 'news-statistics-tag-delete') {
            let tagID = event.target.getAttribute('data-tag-id');
            let tagName = event.target.getAttribute('data-tag-name');
            if (parseInt(tagID) > 0) {
                let onloadWindow = BX.PopupWindowManager.create("onload-window", null, {
                    content: 'После удаления тэга он будет удален у всех постов к которым был привязан',
                    width: 400,
                    height: 200,
                    zIndex: 100,
                    closeIcon: {},
                    titleBar: 'Удалить тэг «' + tagName + '»?',
                    closeByEsc: true,
                    darkMode: false,
                    autoHide: true,
                    draggable: false,
                    resizable: false,
                    min_height: 200,
                    min_width: 400,
                    lightShadow: true,
                    angle: false,
                    overlay: {
                        // объект со стилями фона
                        backgroundColor: 'black',
                        opacity: 500
                    },
                    buttons: [
                        new BX.PopupWindowButton({
                            text: 'Да', // текст кнопки
                            id: 'news-statistics-confirm-delete-tag',
                            className: 'ui-btn ui-btn-success',
                            events: {
                                click: () => {
                                    BX.ajax.runComponentAction('uradugi:company.news.statistics', 'DeleteTag', {
                                        mode: 'ajax',
                                        data: {TAG_ID: tagID}
                                    }).then((response) => {
                                        if (response.status == 'success' && response.errors.length < 1) {
                                            //Показываем всплывашку, что все прошло удачно
                                            let deleteTagSuccess = document.getElementById('news-statistics-tag-delete-success-' + tagID);
                                            deleteTagSuccess.style.display = 'block';
                                            let deleteTagSuccessLI = document.getElementById('news-statistics-tag-li-' + tagID);
                                            function hideDeletedTagSuccess() {
                                                deleteTagSuccess.style.display = 'none';
                                                deleteTagSuccessLI.style.display = 'none';
                                                onloadWindow.close();
                                            } setTimeout(hideDeletedTagSuccess, 2000);
                                        } else {
                                            //Показываем всплывашку, что есть ошибка
                                            let deleteTagError = document.getElementById('news-statistics-tag-delete-error-' + tagID);
                                            deleteTagError.style.display = 'block';
                                            function hideDeletedTagSuccess() {
                                                deleteTagError.style.display = 'none';
                                                onloadWindow.close();
                                            } setTimeout(hideDeletedTagSuccess, 2000);
                                        }
                                    }, (response) => {
                                        //Показываем всплывашку, что есть ошибка (тут проблема не в коде, а в запросе)
                                        let deleteTagError = document.getElementById('news-statistics-tag-delete-error-' + tagID);
                                        deleteTagError.style.display = 'block';
                                        function hideDeletedTagSuccess() {
                                            deleteTagError.style.display = 'none';
                                            onloadWindow.close();
                                        } setTimeout(hideDeletedTagSuccess, 2000);
                                    });
                                }
                            }
                        }),
                        new BX.PopupWindowButton({
                            text: 'Нет',
                            id: 'news-statistics-cancel-delete-tag',
                            className: 'ui-btn ui-btn-primary',
                            events: {
                                click: () => {
                                    onloadWindow.close();
                                }
                            }
                        })
                    ],
                    events: {
                        onPopupShow: () => {

                        },
                        onPopupClose: () => {

                        }
                    }
                });
                onloadWindow.show();
            }
        }
    });

    //Показ списка постов
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        let statisticsInfo = document.getElementById('news-statistics-info');
        if (event.target.className === 'news-statistics-tag-info-posts') {
            let tagID = event.target.getAttribute('data-tag-id');
            let postsWrapper = document.getElementById('news-statistics-posts-wrapper-' + tagID);
            statisticsInfo.innerHTML = '';
            let postsWrapperClone = postsWrapper.cloneNode(true);
            statisticsInfo.append(postsWrapperClone);
        }
    });

    //Очистка основного поля
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        let statisticsInfo = document.getElementById('news-statistics-info');
        if (event.target.id === 'news-statistics-info-wrapper-clear') {
            statisticsInfo.innerHTML = '';
        }
    });

    //Переключение вкладок
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className.indexOf('news-statistics-info-menu-top-point') != -1) {
            let infoMenuTopPoint = document.getElementsByClassName('news-statistics-info-menu-top-point');
            let infoTop = document.getElementsByClassName('news-statistics-info-top');
            let currentInfoMenuTopPoint;
            for (let q = 0; q < infoMenuTopPoint.length; q++) {
                infoMenuTopPoint[q].classList.remove('news-statistics-info-menu-active');
            }
            let dataInfoPoint = event.target.getAttribute('data-info-point');
            for (let j = 0; j < infoTop.length; j++) {
                let dataInfoTop = infoTop[j].getAttribute('data-info-point');
                if (dataInfoTop != dataInfoPoint) {
                    infoTop[j].style.display = 'none';
                } else {
                    currentInfoMenuTopPoint = event.target;
                    infoTop[j].style.display = 'block';
                }
            }
            currentInfoMenuTopPoint.classList.add('news-statistics-info-menu-active');
        }
    });

    //Открытие/закрытие комментариев
    function showCloseDropdown(targetClassName, dataset, openClassName, display)
    {
        if (event.target.className == targetClassName) {
            let needleAttribute = event.target.getAttribute(dataset);
            let needleSelector = document.getElementById(openClassName + needleAttribute);
            if (needleSelector.style.display != display) {
                needleSelector.style.display = display;
            } else {
                needleSelector.style.display = 'none';
            }
        }
    }
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        showCloseDropdown('dropdown-arrow', 'data-post-id', 'news-statistics-info-top-votes-user-list-wrapper-', 'flex');
        showCloseDropdown('news-statistics-comments-detail-list', 'data-user-id', 'news-statistics-commentators-list-wrapper-', 'block');
        showCloseDropdown('news-statistics-comment-detail', 'data-post-id', 'news-statistics-commentators-list-', 'block');
    });

    //Диаграммы
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.id == 'news-statistics-post-diagramms-title') {
            let diagrammPoint = document.getElementsByClassName('news-statistics-post-diagramms-select-month-point');
            let diagrammObj = {};
            for (let i = 0; i < diagrammPoint.length; i++) {
                let diagrammYear = diagrammPoint[i].getAttribute('data-year');
                let diagrammMonth = diagrammPoint[i].getAttribute('data-month');
                let diagrammCount = diagrammPoint[i].getAttribute('data-count');
                if (typeof diagrammObj[diagrammYear] !== 'object') {
                    diagrammObj[diagrammYear] = {};
                }
                if (typeof diagrammObj[diagrammYear][diagrammMonth] !== 'object') {
                    diagrammObj[diagrammYear][diagrammMonth] = {};
                }
                diagrammObj[diagrammYear][diagrammMonth] = {'month': parseInt(diagrammMonth), 'count': diagrammCount};
            }

            for (let year in diagrammObj) {
                let diagrammWrapper = document.getElementById('diagramm-year-' + year);
                let arData = [];
                for (let month in diagrammObj[year]) {
                    arData.push(diagrammObj[year][month]);
                }
                //Рисуем диаграмму
                am4core.ready(() => {
                    am4core.useTheme(am4themes_animated);
                    //am4core.options.autoDispose = true;
                    var chart = am4core.create(diagrammWrapper, am4charts.PieChart);
                    chart.startAngle = 160;
                    chart.endAngle = 380;
                    chart.innerRadius = am4core.percent(40);
                    chart.data = arData;

                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "count";
                    pieSeries.dataFields.category = "month";
                    pieSeries.slices.template.stroke = new am4core.InterfaceColorSet().getFor("background");
                    pieSeries.slices.template.strokeWidth = 1;
                    pieSeries.slices.template.strokeOpacity = 1;
                    pieSeries.labels.template.disabled = true;
                    pieSeries.ticks.template.disabled = true;
                    pieSeries.slices.template.states.getKey("hover").properties.shiftRadius = 0;
                    pieSeries.slices.template.states.getKey("hover").properties.scale = 1;
                    pieSeries.radius = am4core.percent(40);
                    pieSeries.innerRadius = am4core.percent(30);

                    var cs = pieSeries.colors;
                    cs.list = [am4core.color(new am4core.ColorSet().getIndex(0))];

                    cs.stepOptions = {
                        lightness: -0.05,
                        hue: 0
                    };
                    cs.wrap = false;

                    var pieSeries2 = chart.series.push(new am4charts.PieSeries());
                    pieSeries2.dataFields.value = "count";
                    pieSeries2.dataFields.category = "month";
                    pieSeries2.slices.template.stroke = new am4core.InterfaceColorSet().getFor("background");
                    pieSeries2.slices.template.strokeWidth = 1;
                    pieSeries2.slices.template.strokeOpacity = 1;
                    pieSeries2.slices.template.states.getKey("hover").properties.shiftRadius = 0.05;
                    pieSeries2.slices.template.states.getKey("hover").properties.scale = 1;
                    pieSeries2.labels.template.disabled = true;
                    pieSeries2.ticks.template.disabled = true;

                    var label = chart.seriesContainer.createChild(am4core.Label);
                    label.textAlign = "middle";
                    label.horizontalCenter = "middle";
                    label.verticalCenter = "middle";
                    label.adapter.add("text", function (text, target) {
                        return "[font-size:16px]total[/]:\n[normal font-size:30px]" + pieSeries.dataItem.values.value.sum + "[/]";
                    })
                });
            }
        }
    });
    document.getElementById('news-statistics-post-diagramms-title').click();
});