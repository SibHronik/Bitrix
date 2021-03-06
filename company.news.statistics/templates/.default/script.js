BX.ready(() => {

    let statisticsWrapper = document.getElementById('news-statistics-wrapper');
    let ajaxWrapper = statisticsWrapper.parentNode;

    //Выбор пользователя
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.id === 'news-statistics-change-user') {
            let onloadWindow = BX.PopupWindowManager.create("onload-window-user-search", null, {
                content:
                    '<div id="news-statistics-load-icon" class="news-statistics-load-icon"></div>' +
                    '<div class="search-user-input-wrapper">' +
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
                            searchUserResults.innerHTML = '';
                            if (objectLength > 0) {
                                for (let searchUserResultuserID in searchUserResultList) {
                                    let searchResultLIWrapper = document.createElement('li');
                                    searchResultLIWrapper.classList.add('search-result-li-wrapper');
                                    searchResultLIWrapper.setAttribute('data-user-id', searchUserResultuserID);
                                    searchResultLIWrapper.innerText = searchUserResultList[searchUserResultuserID];
                                    searchUserResults.append(searchResultLIWrapper);
                                    searchResultLIWrapper.onclick = () => {
                                        document.getElementById('news-statistics-load-icon').style.display = 'block';
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
                                                    document.getElementById('news-statistics-load-icon').style.display = 'none';
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
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', () => {
        if (event.target.className === 'news-statistics-tag-delete') {
            let userID = event.target.getAttribute('data-user-id');
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
                                        data: {CURRENT_USER_ID: userID, TAG_ID: tagID}
                                    }).then((response) => {
                                        let responseErrorData = response.data.errors === undefined ? 0 : response.data.errors.length;
                                        if (response.status == 'success' && response.errors.length < 1 && responseErrorData < 1) {
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
                            onloadWindow.destroy();
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

    //Открытие/закрытие правки постов
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className == 'set-post-data-dropdown-button') {
            let setPostTextWrappers = document.getElementsByClassName('news-statistics-set-post-data-wrapper');
            for (let i = 0; i < setPostTextWrappers.length; i++) {
                setPostTextWrappers[i].style.display = 'none';
            }
            let postID = event.target.getAttribute('data-post-id');
            document.getElementById('news-statistics-set-post-data-wrapper-' + postID).style.display = 'block';
        }
    });

    //Заполнение заголовка поста
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('input', event => {
        if (event.target.className == 'news-statistics-set-title') {
            let postID = event.target.getAttribute('data-post-id');
            let fieldLength = event.target.getAttribute('data-length');
            let fieldCurrentLength = event.target.value.length;
            if (event.target.value != '') {
                document.getElementById('news-statistics-save-post-data-' + postID).classList.remove('news-statistics-save-post-data-disabled');
                if (event.target.value.length > fieldLength) document.getElementById('set-title-sq-passed-' + postID).classList.add('set-title-sq-danger');
                else document.getElementById('set-title-sq-passed-' + postID).classList.remove('set-title-sq-danger');
            } else {
                let postPreviewTextValue = document.getElementById('news-statistics-set-preview-text-area-' + postID).value;
                if (postPreviewTextValue == '') {
                    document.getElementById('news-statistics-save-post-data-' + postID).classList.add('news-statistics-save-post-data-disabled');
                } else {
                    document.getElementById('news-statistics-save-post-data-' + postID).classList.remove('news-statistics-save-post-data-disabled');
                }
                document.getElementById('set-title-sq-passed-' + postID).classList.add('set-title-sq-danger');
            }
            document.getElementById('set-title-sq-passed-' + postID).innerText = fieldCurrentLength;
            document.getElementById('news-statistics-preview-post-title-' + postID).innerText = event.target.value;
        }
    });

    //Заполнение анонса поста
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('input', event => {
        if (event.target.className == 'news-statistics-set-preview-text-area') {
            let postID = event.target.getAttribute('data-post-id');
            let fieldLength = event.target.getAttribute('data-length');
            let fieldCurrentLength = event.target.value.length;
            if (event.target.value != '') {
                document.getElementById('news-statistics-save-post-data-' + postID).classList.remove('news-statistics-save-post-data-disabled');
                if (event.target.value.length > fieldLength) document.getElementById('set-preview-text-sq-passed-' + postID).classList.add('set-preview-text-sq-danger');
                else document.getElementById('set-preview-text-sq-passed-' + postID).classList.remove('set-preview-text-sq-danger');
            } else {
                let postTitleValue = document.getElementById('news-statistics-set-title-' + postID).value;
                if (postTitleValue == '') {
                    document.getElementById('news-statistics-save-post-data-' + postID).classList.add('news-statistics-save-post-data-disabled');
                } else {
                    document.getElementById('news-statistics-save-post-data-' + postID).classList.remove('news-statistics-save-post-data-disabled');
                }
                document.getElementById('set-preview-text-sq-passed-' + postID).classList.add('set-preview-text-sq-danger');
            }
            document.getElementById('set-preview-text-sq-passed-' + postID).innerText = fieldCurrentLength;
            document.getElementById('news-statistics-preview-post-description-' + postID).innerText = event.target.value;
        }
    });

    //Работа с тэгами у поста
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className == 'news-statistics-post-tag') {
            let postID = event.target.getAttribute('data-post-id');
            let tagID = event.target.getAttribute('data-tag-id');
            const postExistTagsList = document.getElementById('news-statistics-exist-post-tags-list-wrapper-' + postID).children;
            if (parseInt(tagID) > 0) {
                for (let i = 0; i < postExistTagsList.length; i++) {
                    let existTagID = postExistTagsList[i].getAttribute('data-tag-id');
                    if (parseInt(existTagID) == parseInt(tagID)) {
                        postExistTagsList[i].classList.remove('news-statistics-exist-post-tag-active');
                        event.target.remove();
                        document.getElementById('news-statistics-preview-post-tag-' + tagID).remove();
                        const postTagsList = document.getElementById('news-statistics-post-tags-list-wrapper-' + postID).children;
                        if (parseInt(postTagsList.length) < 1) {
                            document.getElementById('news-statistics-post-tags-title-' + postID).innerText = 'Тэги отсутствуют';
                        }
                    }
                }
            }
        }
    });
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className.indexOf('news-statistics-exist-post-tag') != '-1') {
            let postID = event.target.getAttribute('data-post-id');
            let existTagID = event.target.getAttribute('data-tag-id');
            let existTagName = event.target.textContent;
            existTagName = existTagName.trim();
            let postTagsList = document.getElementById('news-statistics-post-tags-list-wrapper-' + postID).children;
            let postTagsListWrapper = document.getElementById('news-statistics-post-tags-list-wrapper-' + postID);
            if (parseInt(existTagID) > 0) {
                if (event.target.className.indexOf('news-statistics-exist-post-tag-active') != '-1') {
                    for (let i = 0; i < postTagsList.length; i++) {
                        let tagID = postTagsList[i].getAttribute('data-tag-id');
                        if (parseInt(existTagID) == parseInt(tagID)) {
                            postTagsList[i].remove();
                            event.target.classList.remove('news-statistics-exist-post-tag-active');
                            document.getElementById('news-statistics-preview-post-tag-' + tagID).remove();
                            if (parseInt(postTagsList.length) < 1) {
                                document.getElementById('news-statistics-post-tags-title-' + postID).innerText = 'Тэги отсутствуют';
                            }
                        }
                    }
                } else {
                    let createdTagLI = document.createElement('li');
                    createdTagLI.className = 'news-statistics-post-tag';
                    createdTagLI.setAttribute('data-tag-id', existTagID);
                    createdTagLI.setAttribute('data-post-id', postID);
                    createdTagLI.innerText = existTagName;
                    postTagsListWrapper.appendChild(createdTagLI);
                    event.target.classList.add('news-statistics-exist-post-tag-active');
                    if (parseInt(postTagsList.length) > 0) {
                        document.getElementById('news-statistics-post-tags-title-' + postID).innerText = 'Тэги: ';
                    }
                    let createdPreviewPostTagLI = document.createElement('li');
                    createdPreviewPostTagLI.id = 'news-statistics-preview-post-tag-' + existTagID;
                    createdPreviewPostTagLI.innerText = existTagName;
                    document.getElementById('news-statistics-preview-post-tags-' + postID).appendChild(createdPreviewPostTagLI);
                }
            }
        }
    });

    //Установка заголовка и текста анонса
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className == 'news-statistics-save-post-data') {
            let postID = event.target.getAttribute('data-post-id');
            let userID = event.target.getAttribute('data-user-id');
            let postTitle = document.getElementById('news-statistics-set-title-' + postID).value;
            let postPreviewText = document.getElementById('news-statistics-set-preview-text-area-' + postID).value;
            let postTags = document.getElementById('news-statistics-post-tags-list-wrapper-' + postID).children;
            let arPostTags = [];
            for (let i = 0; i < postTags.length; i++) {
                arPostTags.push(postTags[i].getAttribute('data-tag-id'));
            }
            BX.ajax.runComponentAction('uradugi:company.news.statistics', 'UpdatePostData', {
                mode: 'ajax',
                data: {
                    ID: postID,
                    CURRENT_USER_ID: userID,
                    PREVIEW_TEXT: postPreviewText,
                    TITLE: postTitle,
                    CATEGORY_ID: arPostTags
                }
            }).then((response) => {
                let responseErrorData = response.data.errors === undefined ? 0 : response.data.errors.length;
                if (response.status == 'success' && response.errors.length < 1 && response.errors.length < 1 && responseErrorData < 1) {
                    let setPostDataSuccess = document.getElementById('news-statistics-save-post-data-success-' + postID);
                    setPostDataSuccess.style.display = 'block';
                    function hideSuccessUpdatePostPreviewText() {
                        setPostDataSuccess.style.display = 'none';
                    } setTimeout(hideSuccessUpdatePostPreviewText, 2000);
                } else {
                    let setPostDataError = document.getElementById('news-statistics-save-post-data-error-' + postID);
                    setPostDataError.style.display = 'block';
                    function hideErrorUpdatePostPreviewText() {
                        setPostDataError.style.display = 'none';
                    } setTimeout(hideErrorUpdatePostPreviewText, 2000);
                }
            }, (response) => {
                let setPostDataError = document.getElementById('news-statistics-save-post-data-error-' + postID);
                setPostDataError.style.display = 'block';
                function hideErrorUpdatePostPreviewText() {
                    setPostDataError.style.display = 'none';
                } setTimeout(hideErrorUpdatePostPreviewText, 2000);
            });
        }
    });

    //Открытие предпросмотра поста
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className == 'news-statistics-open-preview-button') {
            let postID = event.target.getAttribute('data-post-id');
            let ajaxWrapperWidth = document.getElementById('news-statistics-ajax-wrapper').offsetWidth;
            let previewPostWrapperWidth = (ajaxWrapperWidth - (ajaxWrapperWidth / 100 * 3)) / 3;
            document.getElementById('news-statistics-preview-post-center-wrapper-' + postID).style.width = previewPostWrapperWidth + 'px';
            if (document.getElementById('news-statistics-preview-posts-wrapper-' + postID).style.display == 'block') {
                document.getElementById('news-statistics-preview-posts-wrapper-' + postID).style.display = 'none';
                event.target.innerText = 'Открыть предпросмотр';
            } else {
                document.getElementById('news-statistics-preview-posts-wrapper-' + postID).style.display = 'block';
                event.target.innerText = 'Закрыть предпросмотр';
            }
        }
    });

    //Пагинация постов
    document.getElementById('news-statistics-ajax-wrapper').addEventListener('click', event => {
        if (event.target.className == 'news-statistics-pagination-point') {
            let postLists = document.getElementsByClassName('news-statistics-set-post-preview-text-list');
            let paginationLists = document.getElementsByClassName('news-statistics-pagination-point');
            let postListCount = event.target.getAttribute('data-post-list');
            if (parseInt(postListCount) && parseInt(postListCount) > 0) {
                for (let i = 0; i < postLists.length; i++) {
                    postLists[i].style.display = 'none';
                }
                document.getElementById('news-statistics-set-post-preview-text-list-' + postListCount).style.display = 'block';

                for (let i = 0; i < paginationLists.length; i++) {
                    paginationLists[i].classList.remove('news-statistics-pagination-point-active');
                }
                event.target.classList.add('news-statistics-pagination-point-active');
            }
        }
    });
});