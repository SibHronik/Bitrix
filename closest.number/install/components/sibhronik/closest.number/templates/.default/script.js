BX.ready(() => {
    document.getElementById('closest-number-wrapper').addEventListener('click', event => {
        if (event.target.className === 'closest-number-send-data') {
            let method = event.target.getAttribute('data-method');
            let arr = document.getElementById('closest-number-array-' + method).value;
            let num = document.getElementById('closest-number-input-' + method).value;
            let side = false;
            if (document.getElementById('closest-number-min-value-chunk') != undefined) {
                side = document.getElementById('closest-number-min-value-chunk').checked;
            }
            let data = {ARR: arr, NUM: num, SIDE: side};
            document.getElementById('closest-number-warn-' + method).style.display = 'none';
            document.getElementById('closest-number-result-' + method).style.display = 'none';
            BX.ajax.runAction('closest:number.api.search.' + method, {
                data: data
            }).then(response => {
                if (response.data.ERROR != undefined) {
                    document.getElementById('closest-number-warn-' + method).innerText = response.data.ERROR;
                    document.getElementById('closest-number-warn-' + method).style.display = 'block';
                } else {
                    document.getElementById('closest-number-result-text-' + method).innerText = response.data;
                    document.getElementById('closest-number-result-' + method).style.display = 'block';
                }
            }, (response) => {
                //console.log('error');
                //console.log(response);
            });
        }

        if (event.target.id === 'closest-number-send-data-js') {
            let method = event.target.getAttribute('data-method');
            let arr = document.getElementById('closest-number-array-' + method).value;
            let num = document.getElementById('closest-number-input-' + method).value;
            document.getElementById('closest-number-warn-' + method).style.display = 'none';
            document.getElementById('closest-number-result-' + method).style.display = 'none';
            if (isNaN(num)) {
                document.getElementById('closest-number-warn-' + method).innerText = 'Введите число';
                document.getElementById('closest-number-warn-' + method).style.display = 'block';
            }
            if (arr.length == 0) {
                document.getElementById('closest-number-warn-' + method).innerText = 'Введите числа массива через запятую';
                document.getElementById('closest-number-warn-' + method).style.display = 'block';
            }
            let newArr = arr.replaceAll(' ', '');
            newArr = newArr.split(',');
            let result = newArr.sort((a, b) => Math.abs(num - a) - Math.abs(num - b) )[0];
            document.getElementById('closest-number-result-text-' + method).innerText = result;
            document.getElementById('closest-number-result-' + method).style.display = 'block';
        }
    });

    document.getElementById('closest-number-wrapper').addEventListener('input', event => {
        if (event.target.className.indexOf('closest-number-input') != '-1') {
            let elemWidth = 32 + event.target.value.length * 8 + 'px';
            event.target.style.width = elemWidth;
        }
    });
}); // end BX.ready