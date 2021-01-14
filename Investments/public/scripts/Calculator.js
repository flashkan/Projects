class Calculator {
    static run(action) {
        const calculator = new Calculator();
        const isExist = calculator.check(action);

        if (isExist) calculator.remove(action, isExist);
        else calculator.add(action);

        const sum = calculator.calculate();
        calculator.render(sum);
    }

    check(action) {
        const storage = Storage.items;
        let isExist = false; // false || exist id
        for (let key in storage) {
            if (storage.hasOwnProperty(key) && storage[key].id === action.id) {
                isExist = key;
                break;
            }
        }
        return isExist;
    }

    add(action) {
        Storage.items.push(action);
    }

    remove(action, id) {
        Storage.items.splice(id, 1);
    }

    calculate() {
        let sum = 0;

        for (let item of Storage.items) {
            const result = +(+item.count * +item.price - +item.commis).toFixed(2);
            if (item.operation === 'sale' || item.operation === 'dividend') {
                sum += result;
            } else if(item.operation === 'buy') {
                sum -= result;
            }
        }
        return +sum.toFixed(2);
    }

    render(sum) {
        let a = document.querySelector('.action-header_sum');
        a.lastChild.replaceWith(sum);
    }
}

class Storage {
    static items = [];
}